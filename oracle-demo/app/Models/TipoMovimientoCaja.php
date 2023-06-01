<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMovimientoCaja extends Model
{
    protected $table = 'TIPO_MOVIMIENTO_CAJA'; 
    protected $primaryKey = 'id_tipo_movimiento_caja';

    #Campos que podremos usar INSERT INTO
    protected $fillable = [
        'descripcion'
    ];
}
