<?php

namespace App\Imports;

use App\Producto;
use App\Productoprecio;
use App\ProductoCategoria;
use App\Productomodelos;
use App\Productostock;
use App\Familia;
use App\Categoria;
use App\Modelo;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductosImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    /**
     * 0 => Código
     * 1 => Nombre
     * 2 => Stock
     * 3 => Precio
     * 4 => Familia
     * 5 => Modelo (ARRAY)      ==.==
     * 6 => Categoria (ARRAY)   ==.==
     */
    public function model(array $row)
    {
        if(empty($row[1]))
            return null;
        $familia_id = 5;
        $codigo = trim($row[0]);
        $nombre = trim($row[1]);
        $stock = trim($row[2]);
        $precio = trim($row[3]);
        $familia = trim($row[4]);
        $modelos = isset($row[5]) ? trim($row[5]) : null;
        $categorias = isset($row[6]) ? trim($row[6]) : null;

        if(isset($familia)) {
            if(!empty($familia)) {
                $aux = Familia::where("nombre","LIKE","%{$familia}%")->first();
                if(!empty($aux)) {
                    $familia_id = $aux["id"];
                }
            }
        }
        if(!is_numeric($stock))
            $stock = null;
        if(!is_numeric($precio))
            $precio = null;
        $precio = (empty($precio) ? "$ 0,00" : $precio);
        if(strpos($precio, "$") !== false)
            $precio = str_replace("$","",$precio);
        if(strpos($precio, ".") !== false && strpos($precio, ",") !== false)
            $precio = str_replace(".","",$precio);
        if(strpos($precio, ",") !== false)
            $precio = str_replace(",",".",$precio);
        $precio = trim($precio);
        
        $nombre = str_replace('"',"'",$nombre);
        $modelos = explode("==.==",$modelos);
        
        $categorias = explode("==.==",$categorias);
        $Arr_modelos = $Arr_categorias = [];
        if(!empty($modelos)) {
            for($i = 0 ; $i < count($modelos) ; $i++) {
                $m = trim($modelos[$i]);
                if(empty($m)) continue;
                $aux = Modelo::where("nombre","LIKE","%{$m}%")->first();
                if(!empty($aux))
                    $Arr_modelos[] = $aux["id"];
            }
        }
        if(!empty($categorias)) {
            for($i = 0 ; $i < count($categorias) ; $i++) {
                $c = trim($categorias[$i]);
                if(empty($c)) continue;
                $aux = Categoria::where("nombre","LIKE","%{$c}%")->first();
                if(!empty($aux))
                    $Arr_categorias[] = $aux["id"];
            }
        }
        $dataProducto = Producto::where("codigo","=","{$codigo}")->first();
        //dd($dataProducto);
        if(empty($dataProducto)) {
            $dataProducto = Producto::create([
                'codigo'    => $codigo,
                'nombre'    => $nombre,
                'familia_id'    => $familia_id
            ]);
            $aa = Productoprecio::create([
                "precio" => $precio,
                "producto_id" => $dataProducto["id"]
            ]);
            $bb = Productostock::create([
                "cantidad" => (empty($stock) ? 0 : $stock),
                "producto_id" => $dataProducto["id"]
            ]);
        } else {
            $dataProducto->fill([
                'codigo'    => $codigo,
                'nombre'    => $nombre,
                'familia_id'    => $familia_id
            ]);
            $dataProducto->save();

            $dataPrecio = Productoprecio::where("producto_id",$dataProducto["id"])->first();
            $dataStock = Productostock::where("producto_id",$dataProducto["id"])->first();

            $dataPrecio->fill([
                "precio" => $precio
            ]);
            $dataPrecio->save();
            $dataStock->fill([
                "cantidad" => (empty($stock) ? 0 : $stock),
            ]);
            $dataStock->save();
        }
        if(!empty($Arr_modelos)) {
            
            $dataProducto->modelos()->sync($Arr_modelos);
        }
        if(!empty($Arr_categorias)) {
            $dataProducto->categorias()->sync($Arr_categorias);
        }
        return $dataProducto;
    }
}
