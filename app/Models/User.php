<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nombre',
        'foto',
        'correo',
        'password',
        'id_rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getEmailAttribute()
    {
        return $this->correo;
    }
}