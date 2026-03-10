<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $table = 'mensajes';

    protected $primaryKey = 'id_mensaje';

    public $timestamps = true;

    protected $fillable = [
        'nombre_cliente',
        'correo_cliente',
        'mensaje',
        'fecha_envio',
        'id_usuario',
        'fecha_respuesta',
        'id_estado'
    ];
}