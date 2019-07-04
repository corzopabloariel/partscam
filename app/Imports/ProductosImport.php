<?php

namespace App\Imports;

use App\Producto;
use App\Productoprecio;
use App\Productostock;
use App\Familia;
use App\Categoria;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductosImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(empty($row[1]))
            return null;

        $familia_id = 5;
        $modelo_id = 0;
        $categoria_id = 69;
        if(isset($row[4])) {
            $aux = Familia::where("nombre",$row[4])->first();
            if(empty($aux)) {//NO EXISTE
                $aux = Familia::create([
                    "nombre" => $row[4]
                ]);
            }
            $familia_id = $aux["id"];
        }
        /*if(isset($row[5])) {
            $aux = Categoria::where("familia_id",$familia_id)->where("nombre",$row[5])->where("tipo",1)->first();
            if(empty($aux)) {
                $aux = Categoria::create([
                    "familia_id"    => $familia_id,
                    "padre_id"      => 0,
                    "tipo"          => 1,
                    "nombre"        => $row[5],
                ]);
            }
            $modelo_id = $aux["id"];
        }
        if(isset($row[6])) {
            $pos = strrpos($row[6], "==.==");
            if ($pos === false) {
                $aux = Categoria::where("familia_id",$familia_id)->where("padre_id",$modelo_id)->where("nombre",$row[6])->where("tipo",2)->first();
                $categoria_id = $aux["id"];
            } else {
                $auxCat = explode($aux);
            }
        }*/

        $nombre = str_replace('"',"'",$row[1]);
        $dataProducto = Producto::where("nombre",$nombre)->where("familia_id",$familia_id)->first();
        if(empty($dataProducto)) {
            
            $dataProducto = Producto::create([
                'codigo'    => $row[0],
                'nombre'    => $nombre,
                'familia_id'    => $familia_id,
                'categoria_id'  => $categoria_id
            ]);
            
            $aa = Productoprecio::create([
                "precio" => (empty($row[3]) ? 0 : $row[3]),
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
        return $dataProducto;
    }
}
