<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartamentoAccesorio extends Model
{
    protected $table = 'DEPARTAMENTO_ACCESORIO';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_accesorio',
        'id_departamento'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
