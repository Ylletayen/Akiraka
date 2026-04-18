<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Servicio;

class ContactoController extends Controller
{

    public function index() 
    {
        // 1. Obtenemos los servicios
        $servicios = Servicio::orderBy('nombre', 'asc')->get();

        // 2. Apuntamos a la ruta real: agregados.contacto.contacto
        return view('agregados.contacto.contacto', compact('servicios'));
    }

    public function enviar(Request $request)
    {
        Mail::raw($request->mensaje, function ($message) use ($request) {
            $message->to('akirakaestudio140@gmail.com')
                    ->subject($request->asunto ?? 'Mensaje desde la web');
        });

        return back()->with('success', 'Mensaje enviado correctamente');
    }
}