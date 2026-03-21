<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Mantengo la importación de tu compañero

class PublicacionController extends Controller
{
    // ======================
    // PÚBLICO
    // ======================
    public function index()
    {
        $publicaciones = Publicacion::orderBy('fecha','desc')->get();
        return view('partials.project_detail', compact('publicaciones'));
    }

    public function show($id)
    {
        $publicacion = Publicacion::findOrFail($id);
        return view('publicaciones.show', compact('publicacion'));
    }

    // ======================
    // DASHBOARD (Tus funciones de administración)
    // ======================
    public function adminIndex()
    {
        $publicaciones = Publicacion::orderBy('fecha','desc')->get();
        // Nota: He dejado la ruta que tenías en tu código personal
        return view('publicaciones.index', compact('publicaciones'));
    }

    public function store(Request $request)
    {
        Publicacion::create([
            'id_usuario' => Auth::id(),
            'titulo' => $request->titulo,
            'url' => $request->url,
            'fecha' => now(),
            'descripcion' => $request->descripcion,
            'id_medio' => $request->id_medio
        ]);

        return back()->with('success','Publicación creada');
    }

    public function update(Request $request, $id)
    {
        $publicacion = Publicacion::findOrFail($id);
        $publicacion->update($request->all());

        return back()->with('success','Publicación actualizada');
    }

    // =================================================================
    // ELIMINAR (Fusión de ambos: tu lógica + limpieza de IDs de tu amigo)
    // =================================================================

    public function destroy($id)
    {
        // 1. BUSCAR LA PUBLICACIÓN
        $publicacion = Publicacion::findOrFail($id);
        
        // 2. BORRARLA DE LA BASE DE DATOS (Esta es la línea que faltaba)
        $publicacion->delete();

        // 3. REINICIAR EL CONTADOR (Lógica de tu compañero)
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE publicaciones AUTO_INCREMENT = 1;');

        return back()->with('success', 'Publicación eliminada correctamente.');
    }


}

