<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multa extends Model
{
    protected $table = 'MULTA';
    protected $primaryKey = 'id_multa';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'multa',
        'monto',
        'estado',
        'id_tipo_multa',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function tipo_multa()
    {
        return $this->belongsTo('App\Models\TipoMulta', 'id_tipo_multa');
    }
}
