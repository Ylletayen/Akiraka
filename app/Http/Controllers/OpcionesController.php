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
        
        // 1. AÑADIDAS VALIDACIONES ESTRICTAS DE BACKEND
        $request->validate([
            'nombre'         => 'required|string|max:100',
            // Asegura que el correo no esté tomado, exceptuando el del propio usuario actual
            'correo'         => 'required|email|unique:usuarios,correo,' . $user->id_usuario . ',id_usuario',
            'foto'           => 'nullable|image|max:2048', // Máximo 2MB
            'password_nueva' => 'nullable|string|min:6'    // Mínimo 6 caracteres si decide cambiarla
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
        // 1. AÑADIDAS VALIDACIONES PARA LOS DATOS PÚBLICOS
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
            // Permite imágenes o videos de hasta 20MB
            'landing_hero_image' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,webm|max:20480', 
            // Valida que los puestos del array no pasen de 100 letras
            'puestos.*.puesto'   => 'nullable|string|max:100' 
        ]);

        $configuracion = Configuracion::first() ?: new Configuracion();

        // 2. Guardar Imagen o Video de Landing si existe
        if ($request->hasFile('landing_hero_image')) {
            if ($configuracion->landing_hero_image) {
                Storage::disk('public')->delete($configuracion->landing_hero_image);
            }
            $configuracion->landing_hero_image = $request->file('landing_hero_image')->store('landing', 'public');
        }

        // 3. Guardar Datos de Configuración
        $configuracion->fill($request->only([
            'telefono', 'correo_contacto', 'correo_prensa', 
            'correo_laboral_1', 'correo_laboral_2', 'direccion', 
            'instagram', 'facebook', 'quienes_somos_texto', 'valores_texto'
        ]));
        $configuracion->save();

        // 4. ¡MAGIA! Guardar Puestos del Equipo de forma masiva
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