<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoCaja extends Model
{
    protected $table = 'MOVIMIENTO_CAJA';
    protected $primaryKey = 'id_movimiento_caja';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'origen',
        'total',
        'nro_reserva',
        'id_tipo_movimiento_caja',
        'id_usuario',
        'total'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
