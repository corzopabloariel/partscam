<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = [
        'image',
        'titulo',
        'subtitulo',
        'orden',
        'detalle'
    ];
}
