<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    protected $table = 'CHECK_IN';
    protected $primaryKey = 'id_check_in';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_checkin',
        'estado',
        'id_empleado',
        'condiciones',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
