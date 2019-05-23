<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'did',
        'padre_id',
        'familia_id',
        'tipo',
        'nombre',
        'image',
        'orden',
        'tipo'
    ];

    public function familia()
    {
        return $this->belongsTo('App\Familia');
    }
    public function productos()
    {
        return $this->hasMany('App\Producto')->orderBy('orden');
    }
    public function padre()
    {
        return $this->belongsTo('App\Categoria');
    }
    public function hijos()
    {
        return $this->hasMany('App\Categoria','padre_id','id')->orderBy('orden');
    }

    public function padres( $data, &$Arr ) {
        if(empty($data->padre))
            $Arr[] = $data->id;
        else {
            $Arr[] = $data->id;
            self::padres( $data->padre, $Arr );
        }
    }
}
