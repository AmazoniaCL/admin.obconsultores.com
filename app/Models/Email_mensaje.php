<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email_mensaje extends Model
{
    protected $table = 'emails_mensajes';
    protected $fillable = ['mensaje','email_id','user_id'];

    public function adjuntos() {
        return $this->hasMany('App\Models\Email_mensaje_adjunto', 'emails_mensaje_id');
    }

    public function email() {
        return $this->belongsTo('App\Models\Email');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
