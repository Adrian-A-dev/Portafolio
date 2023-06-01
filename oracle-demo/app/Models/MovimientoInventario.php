<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    protected $table = 'MOVIMIENTO_INVENTARIO';
    protected $primaryKey = 'id_movimiento_inventario';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total',
        'nro_reserva',
        'detalle',
        'id_tipo_movimiento',
        'estado',
        'cancelado',
        'id_usuario'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
