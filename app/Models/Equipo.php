<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Para el eliminated (borrado lógico)

class Equipo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipo';
    protected $primaryKey = 'id_miembro';

    protected $fillable = [
        'biografia',
        'id_usuario',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }
}