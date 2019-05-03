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
    public function iva()
    {
        return $this->belongsTo('App\CondicionIva','condicioniva_id');
    }
    public function provincia()
    {
        return $this->belongsTo('App\Provincia');
    }
    public function localidad()
    {
        return $this->belongsTo('App\Localidad');
    }
}
