<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensaje;  
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB; // <-- IMPORTANTE: Agregado para usar DB::statement

class MensajesController extends Controller
{
    public function index()
    {
        // obtener mensajes de la base de datos
        $mensajes = Mensaje::orderBy('fecha_envio','desc')->get();

        // enviar a la vista
        return view('dashboard.mensajes.mensajes', compact('mensajes'));
    }
    
    public function guardarMensaje(Request $request)
    {
        $request->validate([
            'nombre_cliente' => 'required',
            'correo_cliente' => 'required|email:rfc,dns',
            'mensaje' => 'required'
        ]);

        Mensaje::create([
            'nombre_cliente' => $request->nombre_cliente,
            'correo_cliente' => $request->correo_cliente,
            'mensaje' => $request->mensaje
        ]);

        return back()->with('success','Mensaje enviado correctamente');
    }
    
    public function responder(Request $request, $id)
    {
        $mensaje = Mensaje::findOrFail($id);

        $request->validate([
            'respuesta' => 'required'
        ]);

        Mail::raw($request->respuesta,function($mail) use ($mensaje){

            $mail->to($mensaje->correo_cliente)
            ->subject('Respuesta a tu mensaje');

        });

        $mensaje->fecha_respuesta = now();
        $mensaje->estado_respuesta = 'Respondido';
        $mensaje->id_usuario = auth()->user()->id_usuario;

        $mensaje->save();

        return back()->with('success','Respuesta enviada');
    }

    // =================================================================
    // ELIMINAR MENSAJE (Agregado con la protección de IDs)
    // =================================================================
    public function eliminar($id)
    {
        $mensaje = Mensaje::findOrFail($id);
        $mensaje->delete();

        // =========================================================
        // MAGIA: Resetea el contador para evitar saltos en la BD
        // =========================================================
        DB::statement('ALTER TABLE mensajes AUTO_INCREMENT = 1;');

        return back()->with('success', 'Mensaje eliminado.');
    }
}