<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; 
use App\Models\Configuracion; 
use App\Models\Equipo; // Importamos el modelo Equipo

class OpcionesController extends Controller
{
    public function index()
    {
        $configuracion = Configuracion::first() ?: new Configuracion();
        
        // Cargamos a todo el equipo con su usuario relacionado
        $equipo = Equipo::with('usuario')->get();

        return view('dashboard.opciones.opciones', compact('configuracion', 'equipo'));
    }

    public function updatePerfil(Request $request)
    {
        $user = Auth::user();
        $request->validate(['foto' => 'nullable|image|max:2048']);
        if ($request->hasFile('foto')) {
            if ($user->foto) Storage::disk('public')->delete($user->foto);
            $user->foto = $request->file('foto')->store('perfiles', 'public');
        }
        if($request->filled('password_nueva')) $user->password = Hash::make($request->password_nueva);
        $user->nombre = $request->nombre;
        $user->correo = $request->correo;
        $user->save(); 
        return back()->with('success', 'Perfil actualizado.');
    }

    public function updatePublicos(Request $request)
    {
        $configuracion = Configuracion::first() ?: new Configuracion();

        // 1. Guardar Imagen de Landing si existe
        if ($request->hasFile('landing_hero_image')) {
            if ($configuracion->landing_hero_image) {
                Storage::disk('public')->delete($configuracion->landing_hero_image);
            }
            $configuracion->landing_hero_image = $request->file('landing_hero_image')->store('landing', 'public');
        }

        // 2. Guardar Datos de Configuración
        $configuracion->fill($request->only([
            'telefono', 'correo_contacto', 'correo_prensa', 
            'correo_laboral_1', 'correo_laboral_2', 'direccion', 
            'instagram', 'facebook', 'quienes_somos_texto', 'valores_texto'
        ]));
        $configuracion->save();

        // 3. ¡MAGIA! Guardar Puestos del Equipo de forma masiva
        if ($request->has('puestos')) {
            foreach ($request->puestos as $id_miembro => $datos) {
                $miembro = Equipo::find($id_miembro);
                if ($miembro) {
                    $miembro->puesto = $datos['puesto'];
                    $miembro->save();
                }
            }
        }

        return back()->with('success', 'Contenido y roles de la empresa actualizados correctamente.');
    }
}