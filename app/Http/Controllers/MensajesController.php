<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensaje;   // IMPORTANTE

class MensajesController extends Controller
{
    public function index()
    {
        // obtener mensajes de la base de datos
        $mensajes = Mensaje::orderBy('fecha_envio','desc')->get();

        // enviar a la vista
        return view('dashboard.mensajes.mensajes', compact('mensajes'));
    }
}