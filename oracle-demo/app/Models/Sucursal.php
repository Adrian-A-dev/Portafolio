<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'SUCURSAL'; 
    protected $primaryKey = 'id_sucursal';

    #Campos que podremos usar INSERT INTO
    protected $fillable = [
        'sucursal',
        'estado',
        'comuna_id_comuna'
    ];


    public function comuna()
    {
        return $this->belongsTo('App\Models\Comuna', 'comuna_id_comuna');
    }

}
