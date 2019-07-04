<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productomodelos extends Model
{
    protected $table = "productomodelos";

    protected $fillable = [
        "producto_id",
        "modelo_id"
    ];

    public function modelo()
    {
        return $this->belongsTo('App\Modelo');
    }
}
