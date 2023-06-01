<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMulta extends Model
{
    protected $table = 'TIPO_MULTA'; 
    protected $primaryKey = 'id_tipo_multa';

    #Campos que podremos usar INSERT INTO
    protected $fillable = [
        'tipo_multa',
        'estado'
    ];
}
