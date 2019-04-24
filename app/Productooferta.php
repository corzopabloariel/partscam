<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productooferta extends Model
{
    protected $table = "productooferta";
    protected $fillable = [
        'producto_id',
        'precio',
        'porcentaje',
        'orden'
    ];

    public function producto() {
        return $this->belongsTo('App\Producto');
    }
}
