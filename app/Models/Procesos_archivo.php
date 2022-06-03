<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procesos_archivo extends Model
{
    protected $fillable = [
        'nombre', 'file', 'procesos_id',
    ];

    public function proceso() {
        return $this->belongsTo('App\Models\Proceso');
    }
}
