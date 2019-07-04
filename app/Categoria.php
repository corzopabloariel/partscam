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
        'orden'
    ];

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
        return $this->hasMany('App\Categoria','padre_id','id')->whereNotNull("padre_id")->orderBy('orden');
    }

    /*public function padres( $data, &$Arr ) {
        if(empty($data->padre))
            $Arr[] = $data->id;
        else {
            $Arr[] = $data->id;
            self::padres( $data->padre, $Arr );
        }
    }*/
    public function hijosRecursivo($data, &$hijos) {
        if(empty($data->hijos)) {
            if(!empty($h["id"])) {
                $hijos[$data["id"]] = [];
                $hijos[$data["id"]]["nombre"] = $data["nombre"];
                $hijos[$data["id"]]["activo"] = 0;
            }
        } else {
            foreach($data->hijos AS $h) {
                if(empty($h["id"])) continue;
                $hijos[$h["id"]] = [];
                $hijos[$h["id"]]["nombre"] = $h["nombre"];
                $hijos[$h["id"]]["activo"] = 0;
                self::hijosRecursivo($data->h,$hijos);
            }
        }
    }
    public function hijosM() {
        $hijos = [];
        self::hijosRecursivo($this,$hijos);
        return $hijos;
    }
    public function padresRecursivo($data, &$padres, $tipo) {
        if(empty($data->padre))
            $padres[] = $tipo ? $data["id"] : ["nombre" => $data["nombre"], "id" => $data["id"]];
        else {
            $padres[] = $tipo ? $data["id"] : ["nombre" => $data["nombre"], "id" => $data["id"]];
            self::padresRecursivo($data->padre,$padres, $tipo);
        }
    }
    public function padres($tipo = 1) {
        $padres = [];
        self::padresRecursivo($this,$padres, $tipo);
        return $padres;
    }
}
