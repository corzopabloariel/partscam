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
class ProductoController extends Controller
{
    public function rec_padre($data, $tipo = 0) {
        if(empty($data->padre["padre_id"]))
            return ($tipo ? "{$data["nombre"]}---" : $data["nombre"]);
        else
            return self::rec_padre($data->padre, $tipo) . ", {$data["nombre"]}";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Productos";
        $view = "adm.parts.familia.producto";
        $familias = Familia::where("id","!=",5)->orderBy('orden')->pluck('nombre', 'id');
        $productos = Producto::orderBy("orden")->simplePaginate(15);
        $prod = Producto::orderBy('orden')->pluck('nombre', 'id');
        
        foreach($productos AS $p) {
            $c = Categoria::find($p["categoria_id"]);
            
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

    public function familia_categoria($id)
    {
        $familia = Familia::find($id);
        $catTOTAL = $familia->categorias;
        foreach($catTOTAL AS $c)
            $c["nombre"] = self::rec_padre($c);
        
        return $catTOTAL->pluck('nombre', 'id');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $categoria_id = 0;
        if(isset($datosRequest["categoria_id"]))
            $categoria_id = is_null($datosRequest["categoria_id"]) ? 0 : $datosRequest["categoria_id"];
        $ARR_data = [];
        $ARR_data["codigo"] = $datosRequest["codigo"];
        $ARR_data["nombre"] = $datosRequest["nombre"];
        $ARR_data["categoria_id"] = $categoria_id;
        $ARR_data["mercadolibre"] = $datosRequest["mercadolibre"];
        $ARR_data["orden"] = $datosRequest["orden"];
        $ARR_data["familia_id"] = $datosRequest["familia_id"];
        //dd($ARR_data);
        $precio = 0;
        if(isset($datosRequest["precio"])) {
            $precio = $datosRequest["precio"];
            $precio = str_replace(".","",$precio);
            $precio = str_replace(",",".",$precio);
        }
        //dd($precio);
        $stock = 0;
        if(isset($datosRequest["stock"]))
            $stock = $datosRequest["stock"];
        //$stock = 
        if(is_null($data)) {
            $data = Producto::create($ARR_data);
            $data->productos()->sync($request->get('relaciones'));
            Productoprecio::create(["producto_id" => $data["id"], "precio" => $precio]);
            Productostock::create(["producto_id" => $data["id"], "cantidad" => $stock]);
        } else {
            $auxP = Productoprecio::where("producto_id",$data["id"])->first();
            $auxS = Productostock::where("producto_id",$data["id"])->first();
            
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
        self::store($request, $data);
        return back();
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
