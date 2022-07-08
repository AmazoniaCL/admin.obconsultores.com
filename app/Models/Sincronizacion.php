<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sincronizacion extends Model
{
    protected $table = 'sincronizaciones';

    protected $fillable = [
        'fecha', 'users_id'
    ];

    public function users() {
        return $this->belongsTo('App\User');
    }

    public function procesos() {
        return $this->hasMany('App\Models\SincronizacionProceso', 'sincronizaciones_id');
    }
}
