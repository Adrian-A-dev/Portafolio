<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoServicio extends Model
{
    protected $table = 'TIPO_SERVICIO'; 
    protected $primaryKey = 'id_tipo_servicio';

    #Campos que podremos usar INSERT INTO
    protected $fillable = [
        'tipo_servicio',
        'estado'
    ];
}
