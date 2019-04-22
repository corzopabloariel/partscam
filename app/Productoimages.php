<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productoimages extends Model
{
    protected $fillable = [
        'image',
        'orden',
        'producto_id'
    ];
}
