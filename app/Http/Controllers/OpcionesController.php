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
        $configuracion = Configuracion::first() ?: new Configuracion();
        return view('dashboard.opciones.opciones', compact('configuracion'));
    }

    public function updatePerfil(Request $request)
    {
        $user = Auth::user();
        $request->validate(['foto' => 'nullable|image|max:2048']);
        
        if ($request->hasFile('foto')) {
            if ($user->foto) Storage::disk('public')->delete($user->foto);
            $user->foto = $request->file('foto')->store('perfiles', 'public');
        }
        if($request->filled('password_nueva')) {
            $user->password = Hash::make($request->password_nueva);
        }
        
        $user->nombre = $request->nombre;
        $user->correo = $request->correo;
        $user->save(); 
        
        return back()->with('success', 'Perfil actualizado.');
    }

    public function updatePublicos(Request $request)
    {
        $configuracion = Configuracion::first() ?: new Configuracion();

        // AQUÍ ESTÁ EL CAMBIO: Aceptamos mp4, webm y gif (hasta 15MB)
        $request->validate([
            'landing_hero_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,webm|max:15360',
        ]);

        if ($request->hasFile('landing_hero_image')) {
            if ($configuracion->landing_hero_image) {
                Storage::disk('public')->delete($configuracion->landing_hero_image);
            }
            $configuracion->landing_hero_image = $request->file('landing_hero_image')->store('landing', 'public');
        }

        $configuracion->fill($request->only([
            'telefono', 'correo_contacto', 'correo_prensa', 
            'correo_laboral_1', 'correo_laboral_2', 'direccion', 
            'instagram', 'facebook', 'quienes_somos_texto', 'valores_texto'
        ]));
        
        $configuracion->save();
        return back()->with('success', 'Contenido del sitio actualizado correctamente.');
    }
}