<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use App\Models\ConfiguracionSite; // Si llegas a crear una tabla para los datos públicos

class OpcionesController extends Controller
{
    public function index()
    {
        // Esto solo carga la vista que acabamos de hacer
        return view('dashboard.opciones');
    }

    public function updatePerfil(Request $request)
    {
        $user = Auth::user();

        // Si escribió algo en la contraseña nueva, la encriptamos y la guardamos
        if($request->filled('password_nueva')){
            $user->password = Hash::make($request->password_nueva);
        }

        // Guardamos su nombre y correo editados
        $user->nombre = $request->nombre;
        $user->correo = $request->correo;
        $user->save(); // Hace el UPDATE en tu base de datos (tabla usuarios)

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function updatePublicos(Request $request)
    {
        // Aquí iría tu código para guardar el Teléfono, Instagram y Dirección.
        // Como no veo una tabla en tu BD para esto, puedes guardarlo en una nueva tabla 
        // llamada `configuraciones` o donde tu equipo lo decida.

        return back()->with('success', 'Datos públicos actualizados correctamente.');
    }
}