<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Familia;
use App\Categoria;
class ModelosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Modelos de productos";
        $view = "adm.parts.familia.modelo";
        $familias = Familia::orderBy('orden')->pluck('nombre', 'id');
        /**
         * padre_id = 0 -> identificador de MODELO
         * != 0 -> CATEGORÍA / SUBCATEGORIA..etc
         */
        $categorias = DB::select(
            DB::raw('SELECT DISTINCT c.nombre, c.orden, c.id FROM categorias AS c WHERE c.padre_id = 0 AND c.tipo = 1 GROUP BY c.nombre ORDER BY c.orden asc'));
        
        return view('adm.distribuidor',compact('title','view','familias','categorias'));
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
        $familias = Familia::get()->pluck('nombre', 'id');
        if(is_null($data)) {
            foreach($familias AS $i => $n) {
                $ARR_data = [];
                $ARR_data["image"] = null;
                $ARR_data["familia_id"] = $i;
                $ARR_data["padre_id"] = 0;
                $ARR_data["nombre"] = $datosRequest["nombre"];
                $ARR_data["orden"] = $datosRequest["orden"];
                $ARR_data["tipo"] = 1;    
                Categoria::create($ARR_data);
            }
        } else {
            for($i = 0; $i < count($data) ; $i ++) {
                $aux = Categoria::find($data[$i]["id"]);
                $ARR_data = [];
                $ARR_data["image"] = null;
                $ARR_data["padre_id"] = 0;
                $ARR_data["nombre"] = $datosRequest["nombre"];
                $ARR_data["orden"] = $datosRequest["orden"];
                $ARR_data["tipo"] = 1;
                
                $aux->fill($ARR_data);
                $aux->save();
            }
        }
        return back();
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
        $data = Categoria::find($id);
        $datas = Categoria::where("nombre",$data["nombre"])->get();
        return self::store($request, $datas);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Categoria::find($id);
        $datas = Categoria::where("nombre",$data["nombre"])->get();

        foreach($datas AS $d)
            Categoria::destroy($d["id"]);
        return 1;
    }
}
