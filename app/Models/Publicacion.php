<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publicacion extends Model
{
    //use SoftDeletes;

    protected $table = 'publicaciones';

    protected $primaryKey = 'id_publicacion';

    protected $fillable = [
        'id_usuario',
        'titulo',
        'url',
        'fecha',
        'descripcion',
        'id_medio'
    ];
}