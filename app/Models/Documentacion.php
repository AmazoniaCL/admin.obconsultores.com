<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentacion extends Model
{
    protected $table = 'documentacion';

    protected $fillable = [
        'nombre', 'file',
    ];
}
