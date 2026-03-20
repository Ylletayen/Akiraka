<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use Illuminate\Support\Facades\DB; // <-- IMPORTANTE: Agregado para usar DB::statement
use Illuminate\Http\Request;

class PublicacionController extends Controller
{

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

    // =================================================================
    // ELIMINAR PUBLICACIÓN (Agregado para incluir la limpieza de IDs)
    // =================================================================
    public function destroy($id)
    {
        $publicacion = Publicacion::findOrFail($id);
        
        // Si las publicaciones tienen imágenes asociadas en el servidor, 
        // puedes agregar aquí el código para borrarlas usando Storage::disk('public')->delete(...)

        $publicacion->delete();

        // =========================================================
        // MAGIA: Resetea el contador para evitar saltos gigantes en BD
        // =========================================================
        DB::statement('ALTER TABLE publicaciones AUTO_INCREMENT = 1;');

        return back()->with('success', 'Publicación eliminada.');
    }

}