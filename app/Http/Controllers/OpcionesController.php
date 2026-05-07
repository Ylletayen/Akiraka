<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; 
use App\Models\Configuracion; 
use App\Models\Equipo;

class OpcionesController extends Controller
{
    public function index()
    {
        $configuracion = Configuracion::first() ?: new Configuracion();

        $equipo = Equipo::with('usuario')->get();

        return view('dashboard.opciones.opciones', compact('configuracion', 'equipo'));
    }

    public function updatePerfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nombre'         => 'required|string|max:100',
            'correo'         => 'required|email|unique:usuarios,correo,' . $user->id_usuario . ',id_usuario',
            'foto'           => 'nullable|image|max:2048',
            'password_nueva' => 'nullable|string|min:6'
        ]);

        if ($request->hasFile('foto')) {
            if ($user->foto) Storage::disk('public')->delete($user->foto);
            $user->foto = $request->file('foto')->store('perfiles', 'public');
        }
        
        if($request->filled('password_nueva')){
            $user->password = Hash::make($request->password_nueva);
        } 
        
        $user->nombre = $request->nombre;
        $user->correo = $request->correo;
        $user->save(); 
        
        return back()->with('success', 'Perfil actualizado.');
    }

    public function updatePublicos(Request $request)
    {
        $request->validate([
            'telefono'           => 'nullable|string|max:50',
            'correo_contacto'    => 'nullable|email|max:150',
            'correo_prensa'      => 'nullable|email|max:150',
            'correo_laboral_1'   => 'nullable|email|max:150',
            'correo_laboral_2'   => 'nullable|email|max:150',
            'instagram'          => 'nullable|url|max:255',
            'facebook'           => 'nullable|url|max:255',
            'direccion'          => 'nullable|string',
            'quienes_somos_texto'=> 'nullable|string',
            'valores_texto'      => 'nullable|string',
            'landing_hero_image' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,webm|max:20480', 
            'puestos.*.puesto'   => 'nullable|string|max:100' 
        ]);

        $configuracion = Configuracion::first() ?: new Configuracion();

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