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
        
        if(isset($row[4])) {
            $aux = Familia::where("nombre","LIKE","%{$row[4]}%")->first();
            if(empty($aux)) {//NO EXISTE
                $aux = Familia::create([
                    "nombre" => $row[4]
                ]);
            }
            $familia_id = $aux["id"];
        }
        
        $precio = (empty($row[3]) ? "$ 0,00" : $row[3]);
        if(strpos($precio, "$") !== false)
            $precio = str_replace("$","",$precio);
        if(strpos($precio, ".") !== false && strpos($precio, ",") !== false)
            $precio = str_replace(".","",$precio);
        if(strpos($precio, ",") !== false)
            $precio = str_replace(",",".",$precio);
        $precio = trim($precio);
        $modelos = $row[5];
        $categorias = $row[6];
        $nombre = str_replace('"',"'",$row[1]);
        $modelos = explode("==.==",$modelos);
        $categorias = explode("==.==",$categorias);
        $Arr_modelos = $Arr_categorias = [];
        if(!empty($modelos)) {
            for($i = 0 ; $i < count($modelos) ; $i++) {
                $aux = Modelo::where("nombre","LIKE","%{$modelos[$i]}%")->first();
                if(!empty($aux))
                    $Arr_modelos[] = $aux["id"];
            }
        }
        if(!empty($categorias)) {
            for($i = 0 ; $i < count($categorias) ; $i++) {
                $aux = Categoria::where("nombre","LIKE","%{$categorias[$id]}%")->first();
                if(!empty($aux))
                    $Arr_categorias[] = $aux["id"];
            }
        }

        $dataProducto = Producto::where("nombre","LIKE","%{$nombre}%")->where("familia_id",$familia_id)->first();
        if(empty($dataProducto)) {
            $dataProducto = Producto::create([
                'codigo'    => $row[0],
                'nombre'    => $nombre,
                'familia_id'    => $familia_id
            ]);
            $aa = Productoprecio::create([
                "precio" => $precio,
                "producto_id" => $dataProducto["id"]
            ]);
            $bb = Productostock::create([
                "cantidad" => (empty($row[2]) ? 0 : $row[2]),
                "producto_id" => $dataProducto["id"]
            ]);
        } else {
            $dataProducto->fill([
                'codigo'    => $row[0],
                'familia_id'    => $familia_id,
                'categoria_id'  => $categoria_id
            ]);
            $dataProducto->save();

            $dataPrecio = Productoprecio::where("producto_id",$dataProducto["id"])->first();
            $dataStock = Productostock::where("producto_id",$dataProducto["id"])->first();

            $dataPrecio->fill([
                "precio" => (empty($row[3]) ? 0 : $row[3])
            ]);
            $dataPrecio->save();
            $dataStock->fill([
                "cantidad" => (empty($row[2]) ? 0 : $row[2])
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
