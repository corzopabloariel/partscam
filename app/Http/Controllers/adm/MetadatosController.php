<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Empresa;
class MetadatosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Empresa :: Metadatos";
        $view = "adm.parts.empresa.edit";
        $seccion = "metadatos";
        $metadatos = Empresa::first();
        if(is_null($metadatos["metadatos"])) {
            $aux = ["descripcion" => null,"metas" => null];
            $ARR = [];
            $ARR["home"] = $aux;
            $ARR["empresa"] = $aux;
            $ARR["productos"] = $aux;
            $ARR["ofertas"] = $aux;
            $ARR["contacto"] = $aux;
            $metadatos->fill(["metadatos" => json_encode($ARR)]);
            $metadatos = $metadatos->save();
        }
        $metadatos = json_decode($metadatos["metadatos"], true);
        return view('adm.distribuidor',compact('title','view','metadatos','seccion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $seccion = null, $data = null)
    {
        $requestData = $request->all();
        
        $ARR_data = $data["metadatos"];
        $ARR_data[$seccion]["descripcion"] = $requestData["descripcion"] == "" ? null : $requestData["descripcion"];
        $ARR_data[$seccion]["metas"] = $requestData["metas"] == "" ? null : $requestData["metas"];

        
        $data->fill(["metadatos" => json_encode($ARR_data)]);
        $data->save();
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $seccion)
    {
        $metadatos = Empresa::first();
        $metadatos["metadatos"] = json_decode($metadatos["metadatos"], true);
        self::store($request,$seccion,$metadatos);
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
