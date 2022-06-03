<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anotacion_files extends Model
{
    protected $fillable = [
        'id', 'nombre', 'anotacion_file','actuaciones_id'
    ];

    public function actuacion() {
        return $this->belongsTo('App\Models\Actuacion');
    }
}
