<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasoReserva extends Model
{
    protected $table = 'PASO_RESERVA';
    protected $primaryKey = 'id_paso_reserva';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'paso_reserva',
        'descripcion'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
