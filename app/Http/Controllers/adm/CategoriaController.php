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
        $categorias = DB::select(
            DB::raw('SELECT DISTINCT c.nombre, c.orden, c.id FROM categorias AS c WHERE c.tipo > 2 GROUP BY c.nombre ORDER BY c.orden asc'));
        $categorias = DB::table('categorias')->where("tipo",2)->distinct()->select("nombre","id","image","orden","tipo")->orderBy("tipo")->orderBy("orden")->simplePaginate(15);
        
        foreach($categorias AS $c) {
            $aux = Categoria::find($c->id);
            $c->familia = $aux->familia["nombre"];
        }
        return view('adm.distribuidor',compact('title','view','familias','categorias','select2'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * CONSIDERACIONES
     * @param(modelo_id) pertenece a la tabla CATEGORIA
     * @param(padre_id) si es != null, es una subcategoria - buscar y agregar tipo correspondiente
     */
    public function store(Request $request, $data = null)
    {
        $datosRequest = $request->all();
        /**
         * @param firstFmaily - elemento por defecto en el guardado
         */
        $firstFamily = Familia::orderBy('orden')->first();
        $flagFamily = false;

        $padre_id = 0;
        $tipo = 1;
        if(isset($datosRequest["padre_id"])) {
            if(is_null($datosRequest["padre_id"]))
                $flagFamily = true;
            $padre_id = is_null($datosRequest["padre_id"]) ? $firstFamily["id"] : $datosRequest["padre_id"];
        }
        if(!is_null($data)) {
            $tipo = $data["tipo"];
            if($data["tipo"] == 2)
                $padre_id = $datosRequest["modelo_id"];
        } else {
            if(empty($datosRequest["padre_id"]))
                $tipo = 2;
            else
                $tipo = 3;
        }
        $ARR_data = [];
        $ARR_data["image"] = null;
        $ARR_data["familia_id"] = $datosRequest["familia_id"];
        $ARR_data["padre_id"] = $padre_id;
        $ARR_data["nombre"] = $datosRequest["nombre"];
        $ARR_data["orden"] = $datosRequest["orden"];
        $ARR_data["tipo"] = $tipo;
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
        if($flagFamily)
            return back()->withErrors(['mssg' => 'Registro guardado sin Familia / Modelo / Categoría']);
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
