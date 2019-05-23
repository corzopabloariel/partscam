<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Familia;
use App\Categoria;
use App\Producto;
use App\Productoimages;
use App\Productoprecio;
use App\Productostock;
use App\ProductoCategoria;
use App\Transaccion;
use App\TransaccionProducto;
use App\Persona;
use App\CondicionIva;
class ProductoController extends Controller
{
    public function rec_modelo($data) {
        if($data["tipo"] == 1) 
            return $data;
        else
            return self::rec_modelo($data->padre);
    }
    public function rec_padre($data, $tipo = 0) {
        if(empty($data->padre["padre_id"]))
            return ($tipo ? "{$data["nombre"]}---" : $data["nombre"]);
        else
            return self::rec_padre($data->padre, $tipo) . ", {$data["nombre"]}";
    }
    public function rec_hijos($data) {
        $data["hijos"] = $data->hijos;
        
        if(empty($data["hijos"]))
            return $data;
        else {
            foreach($data["hijos"] AS $h)
                $h["hijos"] = self::rec_hijos($h);
            return $data["hijos"];
        }
    }
    public function select2($data) {
        if(count($data["hijos"]) == 0) {
            return ["id" => $data["id"], "text" => $data["nombre"]];
        } else {
            $aux = [];
            for($i = 0; $i < count($data["hijos"]); $i++) {
                $aux[] = self::select2($data["hijos"][$i]);
            }
            return $aux;
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = "Productos";
        $view = "adm.parts.familia.producto";
        $familias = Familia::where("id","!=",5)->orderBy('orden')->pluck('nombre', 'id');
        if(!empty($request->all()["buscar"]))
            $productos = Producto::where("codigo","LIKE","{$request->all()["buscar"]}")->orderBy("orden")->paginate(15);
        else
            $productos = Producto::orderBy("orden")->paginate(15);
        $prod = Producto::orderBy('orden')->groupBy('nombre')->pluck('nombre', 'id');
        
        foreach($productos AS $p) {
            if(empty($p["categoria_id"]))
                $c = Categoria::find($p->categoriaM["categoria_id"]);
            else
                $c = Categoria::find($p["categoria_id"]);
            $p["familia"] = $p->familia["nombre"];
            if($c["id"] == 0)
                $p["categoria"] = $c["nombre"];
            else
                $p["categoria"] = self::rec_padre($c);
            $p["imagenes"] = $p->imagenes;
            $p["precio"] = $p->precio;
            $p["stock"] = $p->stock;
        }
        return view('adm.distribuidor',compact('title','view','familias','productos','prod'));
    }

    public function carga() {
        $title = "Productos Carga";
        $view = "adm.parts.familia.carga";

        return view('adm.distribuidor',compact('title','view'));
    }

    public function familia_categoria($familia_id, $modelo_id)
    {
        $familia = Familia::find($familia_id);
        if(empty($modelo_id)) {
            $modelos = $familia->categorias->where("tipo",1);
            
            $select2[] = ["id" => "", "text" => ""];
            foreach($modelos AS $m)
                $select2[] = ["id" => $m["id"], "text" => $m["nombre"]];
            return $select2;
        } else {
            $catTOTAL = $familia->categorias->where("padre_id",$modelo_id)->where("tipo",">",1)->groupBy("nombre");
            $select2 = [];
            $select2[] = ["id" => "", "text" => ""];
            foreach($catTOTAL AS $c) {
                $c[0]["hijos"] = self::rec_hijos($c[0]);
                
                if(count($c[0]["hijos"]) == 0)
                    $select2[] = ["id" => $c[0]["id"], "text" => $c[0]["nombre"]];
                else
                    $select2[] = ["text" => $c[0]["nombre"], "children" => self::select2($c[0])];
            }
            return $select2;
        }
    }

    public function compras()
    {
        $title = "Compras";
        $view = "adm.parts.compras";

        $compras = Transaccion::orderBy('created_at','DESC')->get();
        
        return view('adm.distribuidor',compact('title','view','compras'));
    }

    public function transaccion($id) {
        $data = Transaccion::find($id);
        $data["persona"] = $data->persona;
        $data["persona"]["iva"] = $data->persona->iva;
        $data["persona"]["provincia"] = $data->persona->provincia;
        $data["persona"]["localidad"] = $data->persona->localidad;
        $data["productos"] = $data->productos;
        foreach($data["productos"] AS $p) {
            $p["producto"] = $p->producto;
            $p["producto"]["imagenes"] = $p->producto->imagenes;
            $p["producto"]["familia"] = $p->producto->familia;
            $p["producto"]["categoria"] = $p->producto->categoria;
        }
        return $data;
    }

    public function transaccionEstado( $id , $estado ) {
        $data = Transaccion::find($id);
        $data->fill(["estado" => $estado]);
        $data->save();

        if($estado == 0) {
            $prod = TransaccionProducto::where("transaccion_id",$id)->pluck("cantidad","producto_id");
            foreach($prod AS $i => $c) {
                $pp = Productostock::where("producto_id",$i)->first();
                $cantidad = $pp["cantidad"] + $c;
                $pp->fill(["cantidad" => $cantidad]);
                $pp->save();
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $data = null)
    {
        $ARR_imagenes = null;
        $datosRequest = $request->all();
        $Arr = [];
        $cat = Categoria::find($datosRequest["categoria_id"]);
        $cat->padres( $cat , $Arr );
        $Arr = array_reverse ( $Arr );//OBTENGO todos los padres de categorias
        /**
         * el primer elemento corresponde al modelo
         * busco el item 1 del array, para sacar la CATEGORÍA
         */
        unset($Arr[0]);//Quito el modelo - no sirve para la búsqueda
        $categorias = Categoria::whereIn("padre_id",$datosRequest["modelo_id"])
                        ->where("nombre","LIKE","%" . Categoria::find($Arr[1])["nombre"] . "%")
                        ->where("familia_id",$datosRequest["familia_id"])->pluck("id");
        unset($Arr[1]);
        /**
         * Quito el siguiente nivel de jerarquía; si continuúa con elementos, busca en sub
         * Como resultado, obtengo ARRAY de categorias.
         */
        if(!empty($Arr)) {
            $categorias = Categoria::whereIn("padre_id",$categorias)
                        ->where("nombre","LIKE","%" . Categoria::find($Arr[2])["nombre"] . "%")
                        ->where("familia_id",$datosRequest["familia_id"])->pluck("id");
        }
        $categoria_id = 0;
        if(isset($datosRequest["categoria_id"]))
            $categoria_id = is_null($datosRequest["categoria_id"]) ? 0 : $datosRequest["categoria_id"];
        $ARR_data = [];
        $ARR_data["codigo"] = $datosRequest["codigo"];
        $ARR_data["nombre"] = $datosRequest["nombre"];
        $ARR_data["categoria_id"] = null;
        $ARR_data["mercadolibre"] = $datosRequest["mercadolibre"];
        $ARR_data["aplicacion"] = $datosRequest["aplicacion"];
        $ARR_data["orden"] = $datosRequest["orden"];
        $ARR_data["familia_id"] = $datosRequest["familia_id"];
        //dd($ARR_data);
        $precio = 0;
        if(isset($datosRequest["precio"])) {
            $precio = $datosRequest["precio"];
            $precio = str_replace("$","",$precio);
            $precio = str_replace(".","",$precio);
            $precio = str_replace(",",".",$precio);
            $precio = trim($precio);
        }
        //dd($precio);
        $stock = 0;
        if(isset($datosRequest["stock"]))
            $stock = $datosRequest["stock"];
        //$stock = 
        if(is_null($data)) {
            $data = Producto::create($ARR_data);
            $data->productos()->sync($request->get('relaciones'));
            foreach($categorias AS $c) 
                ProductoCategoria::create(["producto_id" => $data["id"],"categoria_id" => $c]);
            Productoprecio::create(["producto_id" => $data["id"], "precio" => $precio]);
            Productostock::create(["producto_id" => $data["id"], "cantidad" => $stock]);
        } else {
            $auxP = Productoprecio::where("producto_id",$data["id"])->first();
            $auxS = Productostock::where("producto_id",$data["id"])->first();
            $Arr = ProductoCategoria::where("producto_id",$data["id"])->get()->pluck("id");
            ProductoCategoria::destroy($Arr);
            //ELIMINO CATEGORIAS
            foreach($categorias AS $c) 
                ProductoCategoria::create(["producto_id" => $data["id"],"categoria_id" => $c]);
            
            if(empty($auxP)) {
                Productoprecio::create(["producto_id" => $data["id"], "precio" => $precio]);
            } else if(empty($auxP["precio"])) {
                $auxP->fill(["precio" => $stock]);
                $auxP->save();
            }
            
            if(empty($auxS)) {
                Productostock::create(["producto_id" => $data["id"], "cantidad" => $stock]);
            } else if(empty($auxS["cantidad"])) {
                $auxS->fill(["cantidad" => $stock]);
                $auxS->save();
            }
            $ARR_imagenes = $data["imagenes"];
            unset($data["productos"]);
            unset($data["modelo_id"]);
            unset($data["imagenes"]);
            unset($data["precio"]);
            unset($data["stock"]);
            $data->fill($ARR_data);
            $data->save();
            $data->productos()->sync($request->get('relaciones'));
        }
        /** */
        $imagenes = $request->file('image_image');
        
        $path = public_path('images/productos/');
        if (!file_exists($path))
            mkdir($path, 0777, true);
        if(isset($datosRequest["imageURL"])) {
            for($i = 0; $i < count($datosRequest["imageURL"]); $i++) {
                //NUEVA IMAGEN
                if(is_null($datosRequest["imageURL"][$i])) {
                    $imageName = time().'_producto_' . ($i + 1) . '.'.$imagenes[$i]->getClientOriginalExtension();
                    $imagenes[$i]->move($path, $imageName);
                    Productoimages::create([
                        "producto_id" => $data["id"],
                        "image" => "images/productos/{$imageName}",
                        "orden" => $datosRequest["orden_image"][$i]
                    ]);
                } else {
                    $productoImage = Productoimages::where("image",$datosRequest["imageURL"][$i])->first();
                    $ARR_image = [];
                    $flag = true;
                    //dd($ARR_imagenes);
                    for($xx = 0 ; $xx < count($ARR_imagenes) ; $xx ++) {
                        if(strcmp($ARR_imagenes[$xx]["image"],$datosRequest["imageURL"][$i]) == 0)
                            unset($ARR_imagenes[$xx]);
                    }
                    if(isset($imagenes[$i]))
                        $flag = false;
                    
    
                    if($flag){
                        $ARR_image["image"] = $datosRequest["imageURL"][$i];
                        $ARR_image["orden"] = $datosRequest["orden_image"][$i];
                        
                        $productoImage->fill($ARR_image);
                        $productoImage->save();
                    } else {
                        $filename = public_path() . "/{$datosRequest["imageURL"][$i]}";
                        if (file_exists($filename))
                            unlink($filename);
                        $imageName = time().'_producto_' . ($i + 1) . '.'.$imagenes[$i]->getClientOriginalExtension();
                        $imagenes[$i]->move($path, $imageName);
                        
                        Productoimages::create([
                            "producto_id" => $data["id"],
                            "image" => "images/productos/{$imageName}",
                            "orden" => $datosRequest["orden_image"][$i]
                        ]);
                    }
                }
            }
            if(!is_null($ARR_imagenes)) {
                foreach($ARR_imagenes AS $xx) {
                    $filename = public_path() . "/{$xx["image"]}";
                    if (file_exists($filename))
                        unlink($filename);
                    Productoimages::destroy($xx["id"]);
                }
            }
        }
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = self::edit($id);
        $data["oferta"] = $data->oferta;
        $data["familia"] = $data->familia;
        $data["categoria"] = self::rec_padre($data->categoria,1);
        
        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $p = Producto::find($id);
        //$p["familia_id"] = Categoria::find($p["categoria_id"])["familia_id"];
        if($p["familia_id"] == 5)
            $p["modelo_id"] = ["id" => 69];
        else {
            $p["categoria_id"] = ["id" => ProductoCategoria::where("producto_id",$id)->first()->categoria["id"] ];
            $modelos = [];
            $categorias = ProductoCategoria::where("producto_id",$id)->get()->pluck("categoria_id");
            foreach($categorias AS $c) {
                $cat = Categoria::find($c);
                $Arr = [];
                $cat->padres( $cat , $Arr );
                $Arr = array_reverse ( $Arr );//OBTENGO todos los padres de categorias
                
                $modelos[] = $Arr[0];
            }
            $p["modelo_id"] = ["id" => $modelos];
        
        }
        $p["imagenes"] = $p->imagenes;
        $p["precio"] = $p->precio;
        $p["stock"] = $p->stock;
        $p["productos"] = $p->productos->pluck('nombre','id');
        return $p;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = self::edit($id);
        return self::store($request, $data);
    }

    public function updateModal(Request $request, $id) {
        $datosRequest = $request->all();
        $producto = self::edit($id);
        $return = "";

        if($datosRequest["tipo_modal"] == "stock") {
            $producto["stock"]->fill(["cantidad" => $datosRequest["stock_modal"]]);
            $producto["stock"]->save();
            $return = "STOCK cambiado";
        } else {
            $precio = $datosRequest["precio_modal"];
            if(empty($precio)) $precio = 0;
            $precio = str_replace(".","",$precio);
            $precio = str_replace(",",".",$precio);
            $producto["precio"]->fill(["precio" => $precio]);
            $producto["precio"]->save();
            $return = "PRECIO cambiado";
        }
        return back()->withSuccess(['mssg' => $return]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Producto::destroy($id);
        return 0;
    }
}
