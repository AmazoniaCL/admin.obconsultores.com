<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['id', 'tipo_cliente', 'identificacion', 'verificacion', 'nombre', 'direccion', 'telefono', 'celular', 'correo', 'correo_dos', 'identificacion_representante', 'nombre_representante', 'direccion_representante', 'celular_representante', 'cedula', 'contrato', 'poder', 'titulo_valor', 'eps', 'arl', 'afp'];

    public function procesos() {
        return $this->hasMany('App\Models\Proceso', 'clientes_id');
    }
    public function emails()
    {
        return $this->hasMany('App\Models\Email');
    }
}
