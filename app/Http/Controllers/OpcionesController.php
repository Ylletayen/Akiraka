<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; 
use App\Models\Configuracion; // <--- No olvides importar el nuevo modelo

class OpcionesController extends Controller
{
    public function index()
    {
        // Traemos la configuración de la BD (asumimos que siempre es el ID 1)
        $configuracion = Configuracion::first();
        
        // Si por alguna razón la BD está vacía, creamos un objeto vacío para que no truene la vista
        if (!$configuracion) {
            $configuracion = new Configuracion();
        }

        // Pasamos la variable a la vista
        return view('dashboard.opciones.opciones', compact('configuracion'));
    }

    public function updatePerfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $rutaFoto = $request->file('foto')->store('perfiles', 'public');
            $user->foto = $rutaFoto;
        }

        if($request->filled('password_nueva')){
            $user->password = Hash::make($request->password_nueva);
        }

        $user->nombre = $request->nombre;
        $user->correo = $request->correo;
        $user->save(); 

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function updatePublicos(Request $request)
    {
        // Buscamos el registro 1, si no hay, creamos uno nuevo
        $configuracion = Configuracion::first();
        if (!$configuracion) {
            $configuracion = new Configuracion();
        }

        // Actualizamos TODOS los campos, incluyendo el nuevo de Facebook
        $configuracion->telefono = $request->telefono;
        $configuracion->correo_contacto = $request->correo_contacto;
        $configuracion->direccion = $request->direccion;
        $configuracion->instagram = $request->instagram;
        $configuracion->facebook = $request->facebook;
        $configuracion->save();

        return back()->with('success', 'Datos públicos actualizados correctamente en la base de datos.');
    }
}