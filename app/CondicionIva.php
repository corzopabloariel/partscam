<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CondicionIva extends Model
{
    protected $table = "condicioniva";
    protected $fillable = [
        'nombre'
    ];
}
