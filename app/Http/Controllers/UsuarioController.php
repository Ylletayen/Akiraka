<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = \App\Models\User::all();

        if (Auth::user()->id_rol == 1) {
            $roles = DB::table('roles')->whereNotIn('id_rol', [4])->get();
        } else {
            $roles = DB::table('roles')->whereNotIn('id_rol', [1, 4])->get();
        }

        return view('dashboard.usuarios.usuario', compact('usuarios', 'roles'));
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->id_rol, [1, 2])) {
            abort(403, 'Acceso denegado.');
        }

        if (Auth::user()->id_rol == 2 && $request->id_rol == 1) {
            abort(403, 'No tienes permisos para crear un Superadmin.');
        }

        $request->validate([
            'nombre'   => 'required|string|max:255',
            'correo'   => 'required|string|email|max:255|unique:usuarios,correo', 
            'password' => 'required|string|min:8',
            'id_rol'   => 'required|exists:roles,id_rol'
        ]);

        User::create([
            'nombre'   => $request->nombre,
            'correo'   => $request->correo,
            'password' => bcrypt($request->password),
            'id_rol'   => $request->id_rol,
        ]);

        return redirect()->back()->with('success', 'Usuario creado correctamente.');
    }

    public function updateRol(Request $request, $id)
    {
        if (!in_array(Auth::user()->id_rol, [1, 2])) {
            abort(403, 'Acceso denegado.');
        }

        $request->validate([
            'id_rol' => 'required|exists:roles,id_rol'
        ]);

        $usuario = User::findOrFail($id);

        if (Auth::user()->id_rol == 2 && $usuario->id_rol == 1) {
            abort(403, 'No puedes modificar el rol de un Superadmin.');
        }

        if (Auth::user()->id_rol == 2 && $request->id_rol == 1) {
            abort(403, 'No tienes permisos para otorgar el rol de Superadmin.');
        }

        if ($usuario->id_usuario === Auth::user()->id_usuario && $request->id_rol != 1) {
            return redirect()->back()->with('error', 'No puedes quitarte el rol de Superadmin a ti mismo.');
        }

        $usuario->id_rol = $request->id_rol;
        $usuario->save();

        return redirect()->back()->with('success', 'El rol de ' . $usuario->nombre . ' ha sido actualizado.');
    }
    
    public function destroy($id)
    {
        if (!in_array(Auth::user()->id_rol, [1, 2])) {
            abort(403, 'Acceso denegado.');
        }

        $usuario = \App\Models\User::findOrFail($id);

        if (Auth::user()->id_rol == 2 && $usuario->id_rol == 1) {
            abort(403, 'No tienes permisos para eliminar a un Superadmin.');
        }

        if ($usuario->id_usuario == auth()->user()->id_usuario) {
            return redirect()->back()->withErrors(['error' => 'No puedes eliminar tu propia cuenta.']);
        }

        $adminPrincipal = \App\Models\User::where('id_rol', 1)
                                          ->where('id_usuario', '!=', $id)
                                          ->first();

        if ($adminPrincipal) {
            \Illuminate\Support\Facades\DB::table('publicaciones')
                ->where('id_usuario', $id)
                ->update(['id_usuario' => $adminPrincipal->id_usuario]);
                
            \Illuminate\Support\Facades\DB::table('mensajes')
                ->where('id_usuario', $id)
                ->update(['id_usuario' => $adminPrincipal->id_usuario]);
        }

        if ($usuario->foto) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($usuario->foto);
        }

        $usuario->delete();
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE usuarios AUTO_INCREMENT = 1;');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE equipo AUTO_INCREMENT = 1;');

        return redirect()->back()->with('success', 'Usuario eliminado.');
    }
}