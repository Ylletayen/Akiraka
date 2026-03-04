<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Bloquea el acceso si no han pasado por el login
    }

    public function index()
    {
        return view('dashboard.main'); // Tu vista principal de administrador
    }
}