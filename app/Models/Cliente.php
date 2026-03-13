<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    // Indicamos el nombre exacto de la tabla
    protected $table = 'clientes';
    
    // Indicamos la llave primaria personalizada
    protected $primaryKey = 'id_cliente';

    // Los campos que se pueden llenar masivamente (sin contraseñas)
    protected $fillable = [
        'nombre',
        'correo',
        'telefono'
    ];
}