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
            
        // MAGIA DE SEGURIDAD EN LA VISTA: 
        // Si es Superadmin (1), puede asignar roles 1, 2, 3 (excluye 4: Pendiente)
        // Si es Admin (2), solo puede asignar roles 2 y 3 (excluye 1 y 4)
        if (Auth::user()->id_rol == 1) {
            $roles = DB::table('roles')->whereNotIn('id_rol', [4])->get();
        } else {
            $roles = DB::table('roles')->whereNotIn('id_rol', [1, 4])->get();
        }

        return view('dashboard.usuarios.usuario', compact('usuarios', 'roles'));
    }

    // =================================================================
    // FUNCIÓN PARA CREAR NUEVOS USUARIOS
    // =================================================================
    public function store(Request $request)
    {
        // 1. Permitir acceso solo a Superadmin (1) y Admin (2)
        if (!in_array(Auth::user()->id_rol, [1, 2])) {
            abort(403, 'Acceso denegado.');
        }

        // 2. Un Admin NO puede crear un Superadmin
        if (Auth::user()->id_rol == 2 && $request->id_rol == 1) {
            abort(403, 'No tienes permisos para crear un Superadmin.');
        }

        $request->validate([
            'nombre'   => 'required|string|max:255',
            'correo'   => 'required|string|email|max:255|unique:usuarios,correo', // Asumiendo que tu tabla es 'usuarios'
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

    // =================================================================
    // ACTUALIZAR ROL
    // =================================================================
    public function updateRol(Request $request, $id)
    {
        // 1. Permitir acceso al rol 1 y 2
        if (!in_array(Auth::user()->id_rol, [1, 2])) {
            abort(403, 'Acceso denegado.');
        }

        $request->validate([
            'id_rol' => 'required|exists:roles,id_rol'
        ]);

        $usuario = User::findOrFail($id);

        // 2. Regla de oro: Un Admin (2) NO puede editar a un Superadmin (1)
        if (Auth::user()->id_rol == 2 && $usuario->id_rol == 1) {
            abort(403, 'No puedes modificar el rol de un Superadmin.');
        }

        // 3. Regla de oro: Un Admin (2) NO puede ascender a nadie a Superadmin (1)
        if (Auth::user()->id_rol == 2 && $request->id_rol == 1) {
            abort(403, 'No tienes permisos para otorgar el rol de Superadmin.');
        }

        // 4. El propio Superadmin no puede auto-quitarse su rol
        if ($usuario->id_usuario === Auth::user()->id_usuario && $request->id_rol != 1) {
            return redirect()->back()->with('error', 'No puedes quitarte el rol de Superadmin a ti mismo.');
        }

        $usuario->id_rol = $request->id_rol;
        $usuario->save();

        return redirect()->back()->with('success', 'El rol de ' . $usuario->nombre . ' ha sido actualizado.');
    }
    
    // =================================================================
    // ELIMINAR USUARIO
    // =================================================================
    public function destroy($id)
    {
        // 1. Permitir acceso al rol 1 y 2
        if (!in_array(Auth::user()->id_rol, [1, 2])) {
            abort(403, 'Acceso denegado.');
        }

        $usuario = \App\Models\User::findOrFail($id);

        // 2. Un Admin (2) NO puede eliminar a un Superadmin (1)
        if (Auth::user()->id_rol == 2 && $usuario->id_rol == 1) {
            abort(403, 'No tienes permisos para eliminar a un Superadmin.');
        }

        if ($usuario->id_usuario == auth()->user()->id_usuario) {
            return redirect()->back()->withErrors(['error' => 'No puedes eliminar tu propia cuenta.']);
        }

        // LÓGICA DE TRANSFERENCIA DE AUTORÍA
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

        // =========================================================
        // MAGIA: Resetea el contador para evitar saltos gigantes en BD
        // =========================================================
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE usuarios AUTO_INCREMENT = 1;');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE equipo AUTO_INCREMENT = 1;');

        return redirect()->back()->with('success', 'Usuario eliminado. Sus datos fueron reasignados al Superadmin.');
    }
}