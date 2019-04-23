<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'mercadolibre',
        'categoria_id',
        'orden'
    ];
    
    public function imagenes()
    {
        return $this->hasMany('App\Productoimages')->orderBy('orden');
    }
    public function precio()
    {
        return $this->hasOne('App\Productoprecio');
    }
    public function stock()
    {
        return $this->hasOne('App\Productostock');
    }
}
