<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuraciones';

    protected $fillable = [
        'telefono', 
        'correo_contacto', 
        'correo_prensa',
        'correo_laboral_1',
        'correo_laboral_2',
        'direccion', 
        'instagram',
        'facebook',
        'quienes_somos_texto',
        'valores_texto',
        'landing_hero_image'
    ];
}