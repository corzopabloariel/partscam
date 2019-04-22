<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productorelacion extends Model
{
    protected $table = "productorelaciones";
    protected $fillable = [
        'producto_id',
        'producto_relacion'
    ];
}
