<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoReserva extends Model
{
    protected $table = 'ESTADO_RESERVA';
    protected $primaryKey = 'id_estado_reserva';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'estado_reserva',
        'descripcion'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
