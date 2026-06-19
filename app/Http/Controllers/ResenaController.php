<?php

namespace App\Http\Controllers;

use App\Models\Resena;
use Illuminate\Http\Request;

class ResenaController extends Controller
{
    // =====================================================
    // VISTA PÚBLICA (Muestra la hoja de reseñas)
    // =====================================================
    public function index()
    {
        $resenas = Resena::orderBy('created_at', 'desc')->get();
        return view('agregados.comentarios.resenas', compact('resenas'));
    }

    // =====================================================
    // GUARDAR NUEVA RESEÑA (Comentario inicial)
    // =====================================================
    public function store(Request $request)
    {
        $request->validate([
            'nombre_cliente' => 'required|string|max:255',
            'comentario' => 'required|string',
        ]);

        Resena::create([
            'nombre_cliente' => $request->nombre_cliente,
            'comentario' => $request->comentario,
            'calificacion' => 5, // Valor oculto por defecto
            'votos_count' => 0,
            'estrellas_sum' => 0,
        ]);

        return redirect()->back()->with('success', '¡Gracias por tus comentarios! Tu reseña ha sido publicada exitosamente.');
    }

    // =====================================================
    // LÓGICA DE VOTACIÓN (Comunidad)
    // =====================================================
    public function votar(Request $request, $id)
    {
        $request->validate([
            'estrellas' => 'required|integer|min:1|max:5',
        ]);

        $resena = Resena::findOrFail($id);
        
        // Sumamos el voto nuevo a la base de datos
        $resena->increment('votos_count');
        $resena->increment('estrellas_sum', $request->estrellas);

        // Calculamos el nuevo promedio (Ej. 4.5)
        $promedio = round($resena->estrellas_sum / $resena->votos_count, 1);

        // Devolvemos los datos en formato JSON para que el Javascript actualice la pantalla sin recargar
        return response()->json([
            'success' => true,
            'promedio' => $promedio,
            'total_votos' => $resena->votos_count
        ]);
    }
}