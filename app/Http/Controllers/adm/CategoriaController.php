<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Familia;
use App\Categoria;
class CategoriaController extends Controller
{
    public function rec_padre($data) {
        if(empty($data->padre))
            return $data["nombre"];
        else
            return self::rec_padre($data->padre) . ", {$data["nombre"]}";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Categoría de productos";
        $view = "adm.parts.familia.categoria";
        $familias = Familia::orderBy('orden')->pluck('nombre', 'id');
        $categorias = Categoria::orderBy("padre_id")->simplePaginate(15);
        
        foreach($categorias AS $c) {
            $c["nombre"] = self::rec_padre($c);
            $c["familia"] = $c->familia;
        }
        return view('adm.distribuidor',compact('title','view','familias','categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $data = null)
    {
        $datosRequest = $request->all();
        $padre_id = 0;
        if(isset($datosRequest["padre_id"]))
            $padre_id = is_null($datosRequest["padre_id"]) ? 0 : $datosRequest["padre_id"];
        $ARR_data = [];
        $ARR_data["image"] = null;
        $ARR_data["familia_id"] = $datosRequest["familia_id"];
        $ARR_data["padre_id"] = $padre_id;
        $ARR_data["nombre"] = $datosRequest["nombre"];
        $ARR_data["orden"] = $datosRequest["orden"];
        
        $file = $request->file("image");
        
        if(!is_null($data))
            $ARR_data["image"] = $data["image"];
        if(!is_null($file)) {
            $path = public_path('images/categorias/');
            if (!file_exists($path))
                mkdir($path, 0777, true);
            $imageName = time()."_categoria.".$file->getClientOriginalExtension();
            
            $file->move($path, $imageName);
            $ARR_data["image"] = "images/categorias/{$imageName}";
            
            if(!is_null($data)) {
                $filename = public_path() . "/" . $data["image"];
                if (file_exists($filename))
                    unlink($filename);
            }
        }
        if(is_null($data))
            Categoria::create($ARR_data);
        else {
            $data->fill($ARR_data);
            $data->save();
        }
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function familia_categoria($id)
    {
        $familia = Familia::find($id);
        $catTOTAL = $familia->categorias;
        foreach($catTOTAL AS $c)
            $c["nombre"] = self::rec_padre($c);
        
        return $catTOTAL->pluck('nombre', 'id');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categoria = Categoria::find($id);
        return $categoria;
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
