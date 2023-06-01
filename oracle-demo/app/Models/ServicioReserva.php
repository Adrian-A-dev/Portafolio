<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioReserva extends Model
{
    protected $table = 'SERVICIO_RESERVA'; 
    protected $primaryKey = 'id';

    #Campos que podremos usar INSERT INTO
    protected $fillable = [
        'flg_notifica_ida',
        'fecha_ida',
        'flg_notifica_vuelta',
        'fecha_vuelta',
        'id_servicio',
        'id_reserva',
        'precio',
    ];

    public function servicio()
    {
        return $this->belongsTo('App\models\Servicio', 'id_servicio');
    }
}
