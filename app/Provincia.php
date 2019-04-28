<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = "provincia";
    protected $fillable = [
        'nombre',
        'codigo31662'
    ];
    
    public function localidades()
    {
        return $this->hasMany('App\Localidad')->orderBy('nombre');
    }
}
