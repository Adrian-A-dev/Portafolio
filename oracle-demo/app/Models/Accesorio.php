<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accesorio extends Model
{
    protected $table = 'ACCESORIO';
    protected $primaryKey = 'id_accesorio';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'accesorio',
        'estado'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
