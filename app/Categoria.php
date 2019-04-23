<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'padre_id',
        'familia_id',
        'nombre',
        'image',
        'orden'
    ];

    public function familia()
    {
        return $this->belongsTo('App\Familia');
    }
    public function padre()
    {
        return $this->belongsTo('App\Categoria');
    }
}
