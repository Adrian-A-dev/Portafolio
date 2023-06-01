<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'SERVICIO';
    protected $primaryKey = 'id_servicio';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'servicio',
        'precio',
        'estado',
        'id_tipo_servicio',
        'lugar_origen',
        'lugar_destino',
        'fecha_inicio',
        'fecha_fin',
        'hora_inicio',
        'hora_fin',
        'id_transportista',
        'flg_lugar',
        'flg_horario',
        'flg_transportista',
        'descripcion_corta',

    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function tipo_servicio()
    {
        return $this->belongsTo('App\Models\TipoServicio', 'id_tipo_servicio');
    }
    public function transportista()
    {
        return $this->belongsTo('App\Models\Transportista', 'id_transportista');
    }

}
