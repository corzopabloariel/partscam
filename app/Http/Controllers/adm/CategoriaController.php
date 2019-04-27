<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Familia;
use App\Categoria;
class CategoriaController extends Controller
{
    public function rec_padre($data, $sin = null) {
        if(empty($data->padre))
            return is_null($sin) ? "{$data["nombre"]}" : "";
        else
            return self::rec_padre($data->padre) . ", {$data["nombre"]}";
    }

    public function rec_modelo($data) {
        if(empty($data->padre))
            return $data["id"];
        else
            return self::rec_modelo($data->padre);
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
        $categorias = Categoria::where("padre_id","!=","0")->orderBy("padre_id")->simplePaginate(15);

        $categorias = DB::table("categorias AS c")
                            ->join('familias AS f', 'f.id', '=', 'c.familia_id')
                        ->where('c.padre_id',0)
        $familiasSelect2 = [];
        $familiasSelect2["results"] = [];
        $familiasSelect2["results"][] = ["id" => "", "text" => ""];
        foreach($familias AS $k => $v)
            $familiasSelect2["results"][] = ["id" => $k, "text" => $v];
        
        foreach($categorias AS $c) {
            $c["nombre"] = self::rec_padre($c);
            $c["familia"] = $c->familia;
        }
        return view('adm.distribuidor',compact('title','view','familias','categorias','familiasSelect2'));
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
    public function familia_categoria($id, $tipo)
    {
        $familia = Categoria::find($id)->familia;
        $categoria = self::edit($id);
        //dd($familia->categorias);
        if($tipo == 1) {
            $catTOTAL = $familia->categorias->where("padre_id",0);
            foreach($catTOTAL AS $c)
                $c["nombre"] = self::rec_padre($c);
        } else {
            $catTOTAL = $familia->categorias->where("padre_id",self::rec_modelo($categoria));
            foreach($catTOTAL AS $c)
                $c["nombre"] = self::rec_padre($c,1);
        }
        
        $categoria = $catTOTAL->pluck('nombre', 'id');
        $select2 = [];
        $select2["results"] = [];
        $select2["results"][] = ["id" => "", "text" => ""];
        foreach($categoria AS $k => $v)
            $select2["results"][] = ["id" => $k, "text" => $v];
        
        return $select2;
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
        $categoria["modelo"] = self::rec_modelo($categoria);
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
        $data["modelo_id"] = self::store($request, $data);
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
