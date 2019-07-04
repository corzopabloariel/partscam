<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = "empresa";

    protected $fillable = [
        'email',//JSON - TEXT
        'telefono',//JSON - TEXT
        'domicilio',//JSON - TEXT
        'horario',
        'metadatos',//JSON - TEXT
        'images',
        'pago',//JSON
        'validaciones'
    ];
    
    protected $casts = [
        "email" => "array",
        "telefono"  => "array",
        "domicilio" => "array",
        "images"    => "array",
        "metadatos" => "array",
        "pago" => "array"
    ];
}
