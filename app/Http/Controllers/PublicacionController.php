<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PublicacionController extends Controller
{
    // =========================================================
    // VISTAS PÚBLICAS (Lo que ven los clientes)
    // =========================================================
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

    // =========================================================
    // VISTAS DEL DASHBOARD ADMIN (Lo que ve tu equipo)
    // =========================================================
    public function adminIndex()
    {
        $publicaciones = Publicacion::orderBy('fecha','desc')->get();
        
        // CORRECCIÓN: Apuntando a la carpeta correcta en el Dashboard
        // Si tu archivo se llama index.blade.php pones '.index', 
        // si se llama publicaciones.blade.php pones '.publicaciones'
        return view('dashboard.publicaciones.index', compact('publicaciones'));
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

        return back()->with('success', 'Publicación creada exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $publicacion = Publicacion::findOrFail($id);
        $publicacion->update($request->all());

        return back()->with('success', 'Publicación actualizada correctamente.');
    }

    public function destroy($id)
    {
        $publicacion = Publicacion::findOrFail($id);
        $publicacion->delete();
        
        // Reorganiza el ID autoincrementable limpio
        DB::statement('ALTER TABLE publicaciones AUTO_INCREMENT = 1;');

        return back()->with('success', 'Publicación eliminada de forma permanente.');
    }
}