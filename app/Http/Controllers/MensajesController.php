<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensaje;   // IMPORTANTE

class MensajesController extends Controller
{
    // 1. Muestra la vista del dashboard
    public function index()
    {
        // obtener mensajes de la base de datos (del más nuevo al más viejo)
        $mensajes = Mensaje::orderBy('fecha_envio','desc')->get();

        return view('dashboard.mensajes.mensajes', compact('mensajes'));
    }

    // 2. Guarda el mensaje que manda el cliente desde la vista de Contacto
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|max:150',
            'asunto' => 'required|string',
            'mensaje' => 'required|string'
        ]);

        // Unimos el departamento y asunto al cuerpo del mensaje para no perder contexto
        $cuerpoCompleto = "DEPARTAMENTO: " . ($request->departamento ?? 'General') . "\n";
        $cuerpoCompleto .= "ASUNTO: " . $request->asunto . "\n";
        $cuerpoCompleto .= "-----------------------------------\n\n";
        $cuerpoCompleto .= $request->mensaje;

        Mensaje::create([
            'nombre_cliente' => $request->nombre,
            'correo_cliente' => $request->correo,
            'mensaje' => $cuerpoCompleto
        ]);

        return redirect()->back()->with('success', '¡Gracias! Tu mensaje ha sido enviado correctamente al Estudio.');
    }

    // 3. Elimina un mensaje desde el Dashboard
    public function destroy($id)
    {
        Mensaje::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Mensaje eliminado de la bandeja.');
    }
}