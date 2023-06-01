<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuespedReserva extends Model
{
    protected $table = 'HUESPED_RESERVA';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_huesped',
        'id_reserva'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function reserva()
    {
        return $this->belongsTo('App\Models\Reserva', 'id_reserva');
    }

    public function huesped()
    {
        return $this->belongsTo('App\Models\Huesped', 'id_huesped');
    }
}
