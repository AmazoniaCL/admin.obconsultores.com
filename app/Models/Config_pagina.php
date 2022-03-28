<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config_pagina extends Model
{
    protected $table = "config_pagina";

    protected $fillable = [
        'telefono', 'direccion', 'correo', 'facebook', 'twitter', 'instagram'
    ];
}
