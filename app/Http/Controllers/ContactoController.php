<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactoController extends Controller
{
    public function enviar(Request $request)
    {
        Mail::raw($request->mensaje, function ($message) use ($request) {
            $message->to('akirakaestudio140@gmail.com')
                    ->subject($request->asunto ?? 'Mensaje desde la web');
        });

        return back()->with('success', 'Mensaje enviado correctamente');
    }
}