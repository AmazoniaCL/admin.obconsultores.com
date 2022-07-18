<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proceso extends Model
{
    protected $fillable = [
        'id', 'codigo', 'num_proceso', 'tipo', 'sub_tipo', 'tipo_insolvencia', 'departamento', 'ciudad', 'descripcion', 'proceso_file', 'contrato', 'poder', 'titulo_valor', 'radicado', 'juzgado', 'juez', 'direccion', 'telefono', 'correo', 'fiscalia', 'fiscal', 'telefono_fiscal', 'direccion_fiscal', 'correo_fiscal', 'clientes_id', 'users_id'
    ];

    public function clientes() {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function actuaciones() {
        return $this->hasMany('App\Models\Actuacion', 'procesos_id')->orderBy('fecha','desc');
    }

    public function users() {
        return $this->belongsTo('App\User');
    }

    public function archivos() {
        return $this->hasMany('App\Models\Procesos_archivo', 'procesos_id');
    }

    public function acceso_proceso() {
        return $this->hasMany('App\Models\Sistema\Acceso_proceso', 'procesos_id');
    }
}
