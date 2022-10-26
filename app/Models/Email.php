<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $table = 'emails';
    protected $fillable = ['asunto','estado','tipo','user_id','cliente_id','procesos_id'];

    public function mensajes() {
        return $this->hasMany('App\Models\Email_mensaje');
    }

    public function cliente() {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function procesos() {
        return $this->belongsTo('App\Models\Proceso');
    }
}
