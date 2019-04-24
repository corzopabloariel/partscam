<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'padre_id',
        'familia_id',
        'nombre',
        'image',
        'orden'
    ];

    public function familia()
    {
        return $this->belongsTo('App\Familia');
    }
    public function productos()
    {
        return $this->hasMany('App\Producto')->orderBy('orden');
    }
    public function padre()
    {
        return $this->belongsTo('App\Categoria');
    }
    public function hijos()
    {
        return $this->hasMany('App\Categoria','padre_id','id')->orderBy('orden');
    }
}
