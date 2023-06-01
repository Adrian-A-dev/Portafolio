<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Huesped extends Model
{
    protected $table = 'HUESPED';
    protected $primaryKey = 'id_huesped';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dni',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'email',
        'celular',
        'estado',
        'id_cliente'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

   
}
