<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Añadido para manejar borrado de fotos viejas

class OpcionesController extends Controller
{
    public function index()
    {
        return view('dashboard.opciones.opciones');
    }

    public function updatePerfil(Request $request)
    {
        $user = Auth::user();

        // 1. Validamos que la imagen sea realmente una imagen (seguridad)
        $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Máximo 2MB
        ]);

        // 2. ¿Subieron una foto nueva?
        if ($request->hasFile('foto')) {
            // Si el usuario ya tenía una foto vieja, la borramos del disco para no ocupar espacio basura
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            // Guardamos la nueva foto en la carpeta public/perfiles (igual que en tu AuthController)
            $rutaFoto = $request->file('foto')->store('perfiles', 'public');
            
            // Actualizamos el registro de la BD
            $user->foto = $rutaFoto;
        }

        // 3. Si escribió algo en la contraseña nueva, la encriptamos y la guardamos
        if($request->filled('password_nueva')){
            $user->password = Hash::make($request->password_nueva);
        }

        // 4. Guardamos su nombre y correo editados
        $user->nombre = $request->nombre;
        $user->correo = $request->correo;
        $user->save(); 

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function updatePublicos(Request $request)
    {
        // Lógica de datos públicos
        return back()->with('success', 'Datos públicos actualizados correctamente.');
    }
}