<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SincronizacionProceso extends Model
{
    protected $table = 'sincronizaciones_procesos';

    protected $fillable = [
        'cantidad', 'sincronizaciones_id', 'procesos_id'
    ];

    public function sincronizacion() {
        return $this->belongsTo('App\Models\Sincronizacion');
    }

    public function procesos() {
        return $this->belongsTo('App\Models\Proceso');
    }
}
