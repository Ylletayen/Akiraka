<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    use HasFactory;

    protected $table = 'resenas';

    protected $fillable = [
        'nombre_cliente',
        'comentario',
        'calificacion', // La dejamos por si tu BD la exige
        'votos_count',
        'estrellas_sum'
    ];
}