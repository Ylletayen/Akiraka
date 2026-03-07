<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MensajesController extends Controller
{
    public function index()
    {
        // Por ahora los mensajes siguen siendo estáticos
        return view('dashboard.mensajes');
    }
}

