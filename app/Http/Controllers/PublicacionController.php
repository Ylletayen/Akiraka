<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function adminIndex()
    {
        $publicaciones = Publicacion::orderBy('fecha','desc')->get();
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

    public function destroy($id)
    {
        $publicacion = Publicacion::findOrFail($id);
        $publicacion->delete();
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE publicaciones AUTO_INCREMENT = 1;');

        return back()->with('success', 'Publicación eliminada correctamente.');
    }


}

