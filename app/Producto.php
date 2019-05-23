<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'mercadolibre',
        'aplicacion',
        'familia_id',
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
    public function categoria()
    {
        return $this->belongsTo('App\Categoria');
    }
    public function stock()
    {
        return $this->hasOne('App\Productostock');
    }
    public function oferta()
    {
        return $this->hasOne('App\Productooferta');
    }
    public function familia()
    {
        return $this->belongsTo('App\Familia')->orderBy('orden');
    }
    public function categoriaM()
    {
        return $this->hasOne('App\ProductoCategoria');
    }
    /** RELACION */
    public function productos()
    {
        return $this->belongsToMany('App\Producto', 'productorelaciones', 'producto_id', 'producto_relacion')->orderBy('orden');
    }
}
