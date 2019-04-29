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
    protected $appends = ['nombre_cp'];

    public function getNombreCPAttribute() {
        return "{$this->nombre} ({$this->codigopostal})";
    }
}
