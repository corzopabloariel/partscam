<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = "transaccionespersona";
    protected $fillable = [
        'email',
        'cuit',
        'nombre',
        'apellido',
        'telefono',
        'domicilio',
        'condicioniva_id',
        'transaccion_id',
        'provincia_id',
        'localidad_id'
    ];
}
