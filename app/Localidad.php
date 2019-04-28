<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    protected $table = "localidad";
    protected $fillable = [
        'nombre',
        'codigopostal',
        'provincia_id'
    ];
}
