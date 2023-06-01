<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'ROL';
    protected $primaryKey = 'id_rol';
    protected $fillable = [
        'rol',
        'estado',
        'descripcion'
    ];
}
