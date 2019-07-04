<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $fillable = [
        'nombre',
        'imagen',
        'orden'
    ];
}
