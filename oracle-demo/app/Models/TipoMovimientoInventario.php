<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMovimientoInventario extends Model
{
    protected $table = 'TIPO_MOVIMIENTO_INVENTARIO'; 
    protected $primaryKey = 'id_tipo_movimiento_inventario';

    #Campos que podremos usar INSERT INTO
    protected $fillable = [
        'descripcion'
    ];
}
