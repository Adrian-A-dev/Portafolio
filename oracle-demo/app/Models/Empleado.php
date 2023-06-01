<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'EMPLEADO';
    protected $primaryKey = 'id_empleado';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dni',
        'nombre', 
        'apellido', 
        'fecha_nacimiento', 
        'email',  
        'celular', 
        'id_usuario', 
        'id_cargo', 
        'estado'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'id_usuario');
    }

    public function cargo()
    {
        return $this->belongsTo('App\Models\Cargo', 'id_cargo');
    }


}