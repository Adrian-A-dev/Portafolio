<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comuna extends Model
{
    protected $table = 'COMUNA';
    protected $primaryKey = 'id_comuna';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo_comuna',
        'comuna',
        'region_id_region'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public function region()
    {
        return $this->belongsTo('App\Models\Region', 'region_id_region');
    }
}
