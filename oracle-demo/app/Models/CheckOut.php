<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckOut extends Model
{
    protected $table = 'CHECK_OUT';
    protected $primaryKey = 'id_check_out';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_checkout',
        'estado', 'id_empleado',
        'flg_cumple_estado',
        'observacion_estado',
        'flg_posee_multa',
        'observacion_multa',
        'total_multa',
        'flg_problema_adicional',
        'observacion_adicional',
        'total_adicional'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
