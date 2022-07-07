<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email_mensaje_adjunto extends Model
{
    protected $table = 'emails_mensajes_adjuntos';
    protected $fillable = ['nombre','file','emails_mensaje_id','user_id'];
    public function mensaje()
    {
        return $this->belongsTo('App\Models\Email_mensaje');
    }
}
