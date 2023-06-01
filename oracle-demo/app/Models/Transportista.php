<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportista extends Model
{
    protected $table = 'TRANSPORTISTA';
    protected $primaryKey = 'id_transportista';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dni',
        'nombre',
        'vehiculo',
        'patente',
        'estado'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

   
}
