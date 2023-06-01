<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Usuario extends Model
{

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'flg_cambio_password',
        'email',
        'avatar',
        'id_rol',
        'remember_token',
        'estado',
        'token_password'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function rol()
    {
        return $this->belongsTo('App\Models\Rol', 'id_rol');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'id_usuario');
    }

    public function empleado()
    {
        return $this->hasMany(Empleado::class, 'id_usuario');
    }
}
