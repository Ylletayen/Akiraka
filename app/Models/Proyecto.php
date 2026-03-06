<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';
    
    protected $primaryKey = 'id_proyecto';
    
    // Apagamos los timestamps porque tu tabla no los usa
    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'descripcion',
        'costo_inicial',
        'costo_final',
        'id_estado'
    ];
}