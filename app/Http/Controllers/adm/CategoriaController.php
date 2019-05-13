<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Familia;
use App\Categoria;
class CategoriaController extends Controller
{
    public function rec_padre($data, $sin = null, $tipo = 0, $strong = 0) {
        if($data["tipo"] == 2) 
            return is_null($sin) ? "{$data["nombre"]}" : "";
        else
            return self::rec_padre($data->padre, $sin, $tipo, $strong) . ($tipo != $data["tipo"] ? ", " . ($strong ? "<strong>{$data["nombre"]}</strong>" : "{$data["nombre"]}") : "");
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
        $familias = Familia::where("id","!=",5)->orderBy('orden')->pluck('nombre', 'id');
        
        $categorias = Categoria::where("tipo",2)
                            ->orderBy("tipo")
                            ->orderBy("familia_id")
                            ->orderBy("orden")
                                ->groupBy("familia_id")
                                ->groupBy("nombre")
                                    ->paginate(15);
        foreach($categorias AS $c) {
            $c["familia"] = $c->familia["nombre"];
        }
        return view('adm.distribuidor',compact('title','view','familias','categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * CONSIDERACIONES
     * TIPO = 1: MODELO
     * TIPO = 2: CATEGORIA
     */
    public function store(Request $request, $data = null)
    {
        $datosRequest = $request->all();
        $tipo = 2;
        $family = Familia::find($datosRequest["familia_id"]);
        $image = null;

        $file = $request->file("image");
        if(!is_null($file)) {
            $path = public_path('images/categorias/');
            if (!file_exists($path))
                mkdir($path, 0777, true);
            $imageName = time()."_categoria.".$file->getClientOriginalExtension();
            
            $file->move($path, $imageName);
            $image = "images/categorias/{$imageName}";
        }

        if(is_null($data)) {
            $aux = Categoria::where("familia_id","!=",5)->where("tipo",$tipo)->orderBy("did","DESC")->first();
            $did = $aux["did"] + 1;
            $modelos = $family->modelos;
            foreach($modelos AS $m) {
                $ARR_data = [];
                $ARR_data["did"] = $did;
                $ARR_data["image"] = $image;
                $ARR_data["familia_id"] = $family["id"];
                $ARR_data["padre_id"] = $m["id"];
                $ARR_data["nombre"] = $datosRequest["nombre"];
                $ARR_data["orden"] = $datosRequest["orden"];
                $ARR_data["tipo"] = $tipo;
                
                Categoria::create($ARR_data);
            }
        } else {
            if(is_null($image))
                $image = $data["image"];
            else {
                if(!is_null($data["image"])) {
                    $filename = public_path() . "/" . $data["image"];
                    if (file_exists($filename))
                        unlink($filename);
                }
            }
            $modelos = $family->modelos;
            foreach($modelos AS $m) {
                $find = Categoria::
                        where("tipo",$tipo)->
                        where("familia_id",$family["id"])->
                        where("padre_id",$m["id"])->
                        where("did",$data["did"])->first();
                if(is_null($find)) {
                    $aux = [];
                    $aux["image"] = $image;
                    $aux["did"] = $data["did"];
                    $aux["familia_id"] = $family["id"];
                    $aux["padre_id"] = $m["id"];
                    $aux["nombre"] = $datosRequest["nombre"];
                    $aux["orden"] = $datosRequest["orden"];
                    $aux["tipo"] = $tipo;
                    Categoria::create($aux);
                } else {
                    $aux = [];
                    $aux["image"] = $image;
                    $aux["nombre"] = $datosRequest["nombre"];
                    $aux["orden"] = $datosRequest["orden"];
                    $find->fill($aux);
                    $find->save();
                }
            }
        }
        
        return back();
    }
    /**
     * 
     */
    public function show($id, $tipo) {
        $data = Categoria::find($id);
        $data["hijos"] = $data->hijos->where("tipo", $tipo + 1)->groupBy("nombre");
        
        foreach($data["hijos"] AS $h) {
            $h[0]["familia"] = Familia::find($h[0]->familia_id)["nombre"];
        }
        $data["padre"] = $data->padre;
        return $data;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function familia_categoria($id, $tipo)
    {
        /**
         * FAMILIA
         */
        if($tipo == 1) {
            $catTOTAL = Familia::find($id)->categorias->where("tipo",1);
        } else {
            $catTOTAL = self::edit($id);
            
                $catTOTAL = Familia::find($catTOTAL["familia_id"])->categorias->where("tipo",$catTOTAL["tipo"] + 1);
            //dd($catTOTAL->pluck('nombre', 'id'));
            foreach($catTOTAL AS $c)
                $c["nombre"] = self::rec_padre($c);
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
        return self::store($request, $data);
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
        if(!is_null($data["image"])) {
            if(!empty($data["image"])) {
                $filename = public_path() . "/" . $data["image"];
                if (file_exists($filename))
                    unlink($filename);
            }
        }
        $Arr_data = Categoria::where("did",$data["did"])->where("tipo",$data["tipo"])->pluck("id");
        
        Categoria::destroy($Arr_data);
        return 1;
    }
}
