<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productostock extends Model
{
    protected $fillable = [
        'producto_id',
        'cantidad'
    ];
}
