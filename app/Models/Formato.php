<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formato extends Model
{
    protected $table = 'formatos';

    protected $fillable = [
        'id', 'nombre', 'adjunto', 'descripcion'
    ];
}
