<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';
    protected $primaryKey = 'id_cita';

    // PERMISO PARA LLENAR DATOS
    protected $fillable = ['id_cliente', 'id_servicio', 'fecha_hora', 'estado', 'notas_cliente', 'notas_admin'];
}