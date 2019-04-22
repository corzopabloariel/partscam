<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productoprecio extends Model
{
    protected $table = "productoprecio";
    protected $fillable = [
        'producto_id',
        'precio'
    ];
}
