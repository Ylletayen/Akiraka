<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'citas';
    protected $primaryKey = 'id_cita';
    protected $fillable = ['id_cliente', 'id_servicio', 'fecha_hora', 'estado', 'notas_cliente', 'notas_admin'];
}