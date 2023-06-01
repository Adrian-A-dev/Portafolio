<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'CLIENTE';
    protected $primaryKey = 'id_cliente';
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
        'id_comuna'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function usuario()
    {
        return $this->belongsTo('App\Models\Usuarios', 'id_usuario');
    }

    public function comuna()
    {
        return $this->belongsTo('App\Models\Comuna', 'id_comuna');
    }
}
