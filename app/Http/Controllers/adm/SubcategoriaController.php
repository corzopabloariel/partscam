<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Categoria;
use App\Producto;
use App\Familia;
class SubcategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $datosRequest = $request->all();
        
        $padre_id = $datosRequest["padre_id"];
        $tipo = $datosRequest["tipo"];
        $categoria = Categoria::find($padre_id);
        
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
            $aux = Categoria::where("familia_id","!=",5)->where("tipo",$tipo + 1)->orderBy("did","DESC")->first();
            $did = 1;
            if(!empty($aux))
                $did = $aux["did"] + 1;
            $aux_r = null;
            
            $ARR_data = [];
            $ARR_data["did"] = $did;
            $ARR_data["image"] = $image;
            $ARR_data["familia_id"] = $datosRequest["familia_id"];
            $ARR_data["padre_id"] = $padre_id;
            $ARR_data["nombre"] = $datosRequest["nombre"];
            $ARR_data["orden"] = $datosRequest["orden"];
            $ARR_data["tipo"] = $tipo + 1;
            
            $aux_r = Categoria::create($ARR_data);
            
            return $aux_r;
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
            
            foreach($categorias AS $c) {
                $find = Categoria::
                        where("tipo",$tipo + 1)->
                        where("familia_id",$c["familia_id"])->
                        where("padre_id",$c["id"])->
                        where("did",$data["did"])->first();
                
                if(is_null($find)) {
                    $aux = [];
                    $aux["image"] = $image;
                    $aux["did"] = $data["did"];
                    $aux["familia_id"] = $c["familia_id"];
                    $aux["padre_id"] = $c["id"];
                    $aux["nombre"] = $datosRequest["nombre"];
                    $aux["orden"] = $datosRequest["orden"];
                    $aux["tipo"] = $tipo + 1;
                    Categoria::create($aux);
                } else {
                    $aux = [];
                    $aux["image"] = $image;
                    $aux["nombre"] = $datosRequest["nombre"];
                    $aux["orden"] = $datosRequest["orden"];
                    $Arr[] = $aux;
                    $find->fill($aux);
                    $find->save();
                }
            }
            return self::edit($data["id"]);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Categoria::find($id);
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
            $filename = public_path() . "/" . $data["image"];
            if (file_exists($filename))
                unlink($filename);
        }
        $Arr_data = Categoria::where("did",$data["did"])->where("tipo",$data["tipo"])->pluck("id");
        try {
            //code...
            Categoria::destroy($Arr_data);
            $prd = Producto::whereNull("categoria_id")->get();
            foreach($prd as $p) {
                $p->fill(["familia_id" => 5,"categoria_id" => 69]);
                $p->save();
            }
            return ["estado" => "ok"];
        } catch (\Throwable $th) {
            Categoria::destroy($Arr_data);
            $prd = Producto::whereNull("categoria_id")->get();
            foreach($prd as $p) {
                $p->fill(["familia_id" => 5,"categoria_id" => 69]);
                $p->save();
            }
            
            return ["estado" => "ok"];
        }
    }
}
