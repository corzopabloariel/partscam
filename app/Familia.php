<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    protected $fillable = [
        'image',
        'nombre',
        'orden'
    ];
    public function categorias() 
    {
        return $this->hasMany('App\Categoria');
    }
}
