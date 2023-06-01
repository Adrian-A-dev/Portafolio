<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoDepartamento extends Model
{
    protected $table = 'ESTADO_DEPARTAMENTO';
    protected $primaryKey = 'id_estado_departamento';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'estado_departamento',
        'descripcion'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
