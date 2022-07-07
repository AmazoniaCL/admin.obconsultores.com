<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email_mensaje extends Model
{
    protected $table = 'emails_mensajes';
    protected $fillable = ['mensaje','email_id'];
    public function adjunto()
    {
        return $this->hasMany('App\Models\Email_mensaje_adjunto');
    }
    public function email()
    {
        return $this->belongsTo('App\Models\Email');
    }
}
