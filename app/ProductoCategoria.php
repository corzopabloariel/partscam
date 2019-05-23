<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoCategoria extends Model
{
    protected $table = "productocategorias";
    protected $fillable = [
        'producto_id',
        'categoria_id'
    ];

    public function producto()
    {
        return $this->belongsTo('App\Producto');
    }
    public function categoria()
    {
        return $this->belongsTo('App\Categoria');
    }
}
