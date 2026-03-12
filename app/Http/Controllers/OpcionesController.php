<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; 
use App\Models\Configuracion; 

class OpcionesController extends Controller
{
    public function index()
    {
        $configuracion = Configuracion::first();
        
        if (!$configuracion) {
            $configuracion = new Configuracion();
        }

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
        $configuracion = Configuracion::first();
        if (!$configuracion) {
            $configuracion = new Configuracion();
        }

        // Guardamos todos los campos, incluyendo los nuevos correos
        $configuracion->telefono = $request->telefono;
        $configuracion->correo_contacto = $request->correo_contacto;
        $configuracion->correo_prensa = $request->correo_prensa;
        $configuracion->correo_laboral_1 = $request->correo_laboral_1;
        $configuracion->correo_laboral_2 = $request->correo_laboral_2;
        $configuracion->direccion = $request->direccion;
        $configuracion->instagram = $request->instagram;
        $configuracion->facebook = $request->facebook;
        
        $configuracion->save();

        return back()->with('success', 'Datos públicos actualizados correctamente en la base de datos.');
    }
}