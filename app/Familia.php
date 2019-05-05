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
    public function modelos() 
    {
        return $this->hasMany('App\Categoria')->where("tipo",1)->where("padre_id",0)->orderBy('orden');
    }
    public function categorias() 
    {
        return $this->hasMany('App\Categoria')->orderBy('orden');
    }
    public function productos() 
    {
        return $this->hasMany('App\Producto');
    }
}
