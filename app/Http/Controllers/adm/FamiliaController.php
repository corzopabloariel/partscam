<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Familia;
use App\Producto;
use App\Categoria;
class FamiliaController extends Controller
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
        $title = "Familia de productos";
        $view = "adm.parts.familia.index";
        $familias = Familia::where("id","!=",5)->orderBy('orden')->get();
        
        return view('adm.distribuidor',compact('title','view','familias'));
    }

    public function sin()
    {
        $title = "Productos sin clasificación";
        $view = "adm.parts.familia.producto";
        $familias = Familia::where("id","!=",5)->orderBy('orden')->pluck('nombre', 'id');
        $productos = Producto::where("familia_id",5)->orderBy("orden")->simplePaginate(15);
        $prod = Producto::orderBy('orden')->pluck('nombre', 'id');
        $sin = 1;
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
        return view('adm.distribuidor',compact('title','view','familias','productos','prod','sin'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $data = null)
    {
        $dataRequest = $request->all();
        $ARR_data = [];
        $ARR_data["orden"] = $dataRequest["orden"];
        $ARR_data["nombre"] = $dataRequest["nombre"];
        $ARR_data["image"] = null;

        $file = $request->file("image");
        
        if(!is_null($data))
            $ARR_data["image"] = $data["image"];
        if(!is_null($file)) {
            $path = public_path('images/marcas/');
            if (!file_exists($path))
                mkdir($path, 0777, true);
            $imageName = time()."_marca.".$file->getClientOriginalExtension();
            
            $file->move($path, $imageName);
            $ARR_data["image"] = "images/marcas/{$imageName}";
            
            if(!is_null($data)) {
                $filename = public_path() . "/" . $data["image"];
                if (file_exists($filename))
                    unlink($filename);
            }
        }
        if(is_null($data))
            Familia::create($ARR_data);
        else {
            $data->fill($ARR_data);
            $data->save();
        }
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Familia::find($id);
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
        self::store($request,$data);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = self::edit($id);
        
        $filename = public_path() . "/{$data["image"]}";
        if(!empty($data["image"])) {
            if (file_exists($filename))
                unlink($filename);
        }
        Familia::destroy($id);
        return 1;
    }
}
