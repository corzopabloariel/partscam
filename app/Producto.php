<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'mercadolibre',
        'aplicacion',
        'familia_id',
        'categoria_id',
        'orden'
    ];
    
    public function imagenes()
    {
        return $this->hasMany('App\Productoimages')->orderBy('orden');
    }
    public function modelosMM()
    {
        return $this->hasMany('App\Productomodelos');
    }
    public function precio()
    {
        return $this->hasOne('App\Productoprecio');
    }
    public function stock()
    {
        return $this->hasOne('App\Productostock');
    }
    public function oferta()
    {
        return $this->hasOne('App\Productooferta');
    }
    public function categoria()
    {
        return $this->belongsTo('App\Categoria');
    }
    public function familia()
    {
        return $this->belongsTo('App\Familia')->orderBy('orden');
    }
    public function categoriaMM()
    {
        return $this->hasMany('App\ProductoCategoria');
    }
    /** RELACION */
    public function productos()
    {
        return $this->belongsToMany('App\Producto', 'productorelaciones', 'producto_id', 'producto_relacion')->orderBy('orden');
    }
    public function modelos()
    {
        return $this->belongsToMany('App\Producto', 'productomodelos', 'producto_id', 'modelo_id')->orderBy('orden');
    }
    public function categorias()
    {
        return $this->belongsToMany('App\Producto', 'productocategorias', 'producto_id', 'categoria_id')->orderBy('orden');
    }
    /** */
    public function modelosM() {
        $Arr = $this->modelosMM;
        $r = "";
        foreach($Arr AS $m) {
            if(!empty($r)) $r .= " / ";
            $r .= $m->modelo->nombre;
        }
        return $r;
    }
}
