<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function index()
    {
        // 1. Validar que SOLO el Superadmin (id_rol = 1) pueda ver esto
        if (Auth::user()->id_rol != 1) {
            abort(403, 'Acceso denegado. Solo el Superadmin puede gestionar roles.');
        }

        // 2. Traer a los usuarios y los roles disponibles
        $usuarios = User::all();
        $roles = DB::table('roles')->get(); 

        return view('dashboard.usuarios', compact('usuarios', 'roles'));
    }

    public function updateRol(Request $request, $id)
    {
        // 1. Validar seguridad nuevamente por si intentan forzar la petición
        if (Auth::user()->id_rol != 1) {
            abort(403, 'Acceso denegado.');
        }

        $request->validate([
            'id_rol' => 'required|exists:roles,id_rol'
        ]);

        $usuario = User::findOrFail($id);

        // 2. Medida de seguridad: Evitar que el Superadmin se quite su propio rol por accidente
        if ($usuario->id_usuario === Auth::user()->id_usuario && $request->id_rol != 1) {
            return redirect()->back()->with('error', 'No puedes quitarte el rol de Superadmin a ti mismo.');
        }

        // 3. Actualizar
        $usuario->id_rol = $request->id_rol;
        $usuario->save();

        return redirect()->back()->with('success', 'El rol de ' . $usuario->nombre . ' ha sido actualizado.');
    }
}