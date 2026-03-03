<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function show($id) 
    {
        // Definimos los datos aquí para que la vista no "truene"
        $proyecto_titulo = "Residencial Valle de Bravo";
        
        $slides = [
            [
                'img' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
                'desc' => 'El proyecto nace de la necesidad de integrar la arquitectura con el entorno boscoso. Se utilizaron materiales locales como piedra volcánica.'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1600607687940-4e524cb35497?auto=format&fit=crop&w=1200&q=80',
                'desc' => 'La estructura principal se eleva del suelo para minimizar el impacto en el terreno natural, permitiendo que la vegetación siga su curso.'
            ]
        ];

        // Pasamos las variables a la vista usando compact()
        return view('partials.main_view', compact('proyecto_titulo', 'slides'));
    }
}