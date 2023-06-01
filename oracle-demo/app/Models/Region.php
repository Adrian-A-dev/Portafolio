<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'REGION';
    protected $primaryKey = 'id_region';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'nombre_largo'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
