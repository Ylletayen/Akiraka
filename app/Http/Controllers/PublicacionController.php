<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;

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

}