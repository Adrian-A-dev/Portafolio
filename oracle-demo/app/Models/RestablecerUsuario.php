<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestablecerUsuario extends Model
{
    protected $table = 'RESTABLECER_USUARIO';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'token',
        'fecha_cambio',
        'fecha_vencimiento',
        'estado',
        'id_usuario'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
