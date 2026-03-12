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

        $usuarios = \App\Models\User::all();
        
        $roles = DB::table('roles')
                    ->whereNotIn('id_rol', [1, 4]) 
                    ->get(); 

        return view('dashboard.usuarios.usuario', compact('usuarios', 'roles'));
    }

    public function updateRol(Request $request, $id)
    {
        if (Auth::user()->id_rol != 1) {
            abort(403, 'Acceso denegado.');
        }

        $request->validate([
            'id_rol' => 'required|exists:roles,id_rol'
        ]);

        $usuario = User::findOrFail($id);

        if ($usuario->id_usuario === Auth::user()->id_usuario && $request->id_rol != 1) {
            return redirect()->back()->with('error', 'No puedes quitarte el rol de Superadmin a ti mismo.');
        }

        $usuario->id_rol = $request->id_rol;
        $usuario->save();

        return redirect()->back()->with('success', 'El rol de ' . $usuario->nombre . ' ha sido actualizado.');
    }
    
    public function destroy($id)
    {
        $usuario = \App\Models\User::findOrFail($id);

        if ($usuario->id_usuario == auth()->user()->id_usuario) {
            return redirect()->back()->withErrors(['error' => 'No puedes eliminar tu propia cuenta de Superadmin.']);
        }

        // =================================================================
        // NUEVA LÓGICA: Buscar al Superadmin y transferirle la autoría
        // =================================================================
        $adminPrincipal = \App\Models\User::where('id_rol', 1)
                                          ->where('id_usuario', '!=', $id)
                                          ->first();

        if ($adminPrincipal) {
            // Transferir publicaciones
            \Illuminate\Support\Facades\DB::table('publicaciones')
                ->where('id_usuario', $id)
                ->update(['id_usuario' => $adminPrincipal->id_usuario]);
                
            // Transferir mensajes respondidos
            \Illuminate\Support\Facades\DB::table('mensajes')
                ->where('id_usuario', $id)
                ->update(['id_usuario' => $adminPrincipal->id_usuario]);
        }
        // =================================================================

        if ($usuario->foto) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($usuario->foto);
        }

        $usuario->delete();

        return redirect()->back()->with('success', 'Usuario eliminado. Sus publicaciones fueron reasignadas al Administrador.');
    }
}