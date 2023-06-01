<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpleadoSucursal extends Model
{
    protected $table = 'EMPLEADO_SUCURSAL';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_sucursal',
        'id_empleado'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
