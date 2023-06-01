<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'CARGO';
    protected $primaryKey = 'id_cargo';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cargo',
        'estado'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
