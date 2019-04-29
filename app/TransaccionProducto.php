<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransaccionProducto extends Model
{
    protected $table = "transaccionesprod";
    protected $fillable = [
        'cantidad',
        'precio',
        'consultar',
        'transaccion_id',
        'producto_id'
    ];
}
