<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resena; // Asegúrate de tener este modelo creado

class ResenaController extends Controller
{
    /**
     * Muestra la vista principal con todas las reseñas.
     */
    public function index()
    {
        $resenas = Resena::orderBy('calificacion', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->get();

        // Cambia 'resenas' por 'agregados.resenas'
        return view('agregados.comentarios.resenas', compact('resenas')); 
    }

    /**
     * Muestra el formulario para que el cliente deje su reseña.
     */
    public function create()
    {
        // Aquí mandarías a llamar la vista de tu formulario de reseñas
        return view('formulario_resena'); 
    }

    /**
     * Guarda la reseña en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validamos que el cliente no mande datos vacíos o manipulados
        $request->validate([
            'nombre_cliente' => 'required|string|max:255',
            'comentario'     => 'required|string',
            'calificacion'   => 'required|integer|min:1|max:5',
        ]);

        // 2. Guardamos la reseña en la base de datos
        Resena::create([
            'nombre_cliente' => $request->nombre_cliente,
            'comentario'     => $request->comentario,
            'calificacion'   => $request->calificacion,
        ]);

        // 3. Redirigimos de vuelta a la página de reseñas con un mensaje de éxito
        return redirect()->route('resenas.index')
                         ->with('success', '¡Gracias por compartir tu experiencia con Estudio Akiraka!');
    }
}