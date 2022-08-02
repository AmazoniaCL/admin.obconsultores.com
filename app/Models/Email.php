<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $table = 'emails';
    protected $fillable = ['asunto','estado','user_id','cliente_id'];

    public function mensajes() {
        return $this->hasMany('App\Models\Email_mensaje');
    }

    public function cliente() {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
