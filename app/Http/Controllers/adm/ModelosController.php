<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Familia;
use App\Modelo;
use App\Categoria;
use App\Producto;
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
        /**
         * padre_id = 0 -> identificador de MODELO
         * != 0 -> CATEGORÍA / SUBCATEGORIA..etc
         */
        $modelos = Modelo::orderBy('orden')->get();
        
        return view('adm.distribuidor',compact('title','view','familias','modelos'));
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
        $ARR_data = [];
        $ARR_data["nombre"] = $datosRequest["nombre"];
        $ARR_data["orden"] = $datosRequest["orden"];
        
        if(is_null($data))
            Modelo::create($ARR_data);
        else {
            $data->fill($ARR_data);
            $data->save();
        }
        return back();
    }

    public function edit($id) {
        return Modelo::find($id);
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
        return self::store($request, self::edit($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Modelo::destroy($d["id"]);
        
        return 1;
    }
}
