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
    public function productos()
    {
        return $this->hasMany('App\TransaccionProducto');
    }
    public function persona()
    {
        return $this->hasOne('App\Persona');
    }
}
