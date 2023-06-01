<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'RESERVA';
    protected $primaryKey = 'id_reserva';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inicio_reserva',
        'final_reserva',
        'fecha_estimada_checkin',
        'fecha_estimada_checkout',
        'numero_huespedes',
        'descripcion',
        'id_departamento',
        'id_estado_reserva',
        'id_cliente',
        'id_check_in',
        'id_check_out',
        'total_reserva',
        'total_pagado',
        'diferencia_pago',
        'flg_confirmacion_reserva',
        'id_paso_reserva',
        'flg_asigna_huespedes',
        'cantidad_noches',
        'valor_noches'
   
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function paso_reserva()
    {
        return $this->belongsTo('App\models\PasoReserva', 'id_paso_reserva');
    }

    public function cliente()
    {
        return $this->belongsTo('App\models\Cliente', 'id_cliente');
    }
    
    public function departamento()
    {
        return $this->belongsTo('App\models\Departamento', 'id_departamento');
    }
    public function estado_reserva()
    {
        return $this->belongsTo('App\models\EstadoReserva', 'id_estado_reserva');
    }

    public function check_out()
    {
        return $this->belongsTo('App\models\CheckOut', 'id_check_out');
    }
}
