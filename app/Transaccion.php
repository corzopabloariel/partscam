<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    protected $table = "transacciones";
    protected $fillable = [
        'shipping',
        'tipopago',
        'codigo',
        'estado',
        'total'
    ];
}
