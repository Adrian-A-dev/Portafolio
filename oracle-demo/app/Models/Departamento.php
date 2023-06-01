<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'DEPARTAMENTO';
    protected $primaryKey = 'id_departamento';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'precio_costo',
        'valor_arriendo',
        'descripcion',
        'id_estado_departamento',
        'id_empleado',
        'cantidad_reservas',
        'flg_nuevo',
        'flg_destacado',
        'id_sucursal',
        'cantidad_huespedes'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function sucursal()
    {
        return $this->belongsTo('App\Models\Sucursal', 'id_sucursal');
    }

    public function estado_departamento()
    {
        return $this->belongsTo('App\Models\EstadoDepartamento', 'id_estado_departamento');
    }

    public function empleado()
    {
        return $this->belongsTo('App\Models\Empleado', 'id_empleado');
    }
}
