<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- IMPORTANTE: Agregado para usar DB::statement

class EquipoController extends Controller
{
    public function index()
    {
        $miembros = Equipo::with('usuario')->get();
        $usuariosDisponibles = User::all(); 
        
        return view('dashboard.equipo.quienes_somos', compact('miembros', 'usuariosDisponibles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'biografia' => 'required|string',
            // OJO AQUÍ: Cambia 'usuarios' por 'users' si tu tabla de BD está en inglés
            'id_usuario' => 'required|exists:usuarios,id_usuario' 
        ]);

        Equipo::create($request->all());
        return redirect()->back()->with('success', 'Miembro añadido al equipo.');
    }

    public function update(Request $request, $id)
    {
        // 1. Agregamos validación para no recibir datos basura
        $request->validate([
            'biografia' => 'required|string',
        ]);

        $miembro = Equipo::findOrFail($id);
        
        // 2. Usamos only() por seguridad, para que no puedan inyectar un 'id_usuario' diferente desde el inspector de elementos
        $miembro->update($request->only('biografia')); 
        
        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function destroy($id)
    {
        Equipo::findOrFail($id)->delete(); 
        
        // =========================================================
        // MAGIA: Resetea el contador para evitar saltos en la BD
        // =========================================================
        DB::statement('ALTER TABLE equipo AUTO_INCREMENT = 1;');

        return redirect()->back()->with('success', 'Miembro eliminado del equipo. Contador reajustado.');
    }
}