<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    use HasFactory;

    // Si tu tabla en la base de datos se llama distinto a "resenas", 
    // descomenta la siguiente línea y ponle el nombre exacto:
    // protected $table = 'resenas';

    // El arreglo $fillable protege tu base de datos (Mass Assignment)
    // Solo permite que estos campos específicos se guarden desde el formulario
    protected $fillable = [
        'nombre_cliente',
        'comentario',
        'calificacion',
    ];
}