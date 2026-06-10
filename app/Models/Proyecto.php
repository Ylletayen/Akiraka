<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ImagenProyecto;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    protected $primaryKey = 'id_proyecto';

    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'descripcion',
        'id_estado',
        'anio',
        'orden'
    ];

    public function imagenes()
    {
        return $this->hasMany(ImagenProyecto::class, 'id_proyecto', 'id_proyecto');
    }

    public function portadaPrincipal()
    {
        return $this->hasOne(ImagenProyecto::class, 'id_proyecto', 'id_proyecto')
            ->whereRaw('LOWER(descripcion) LIKE ?', ['%portada%']);
    }
}