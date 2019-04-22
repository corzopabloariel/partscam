<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Empresa;
class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datos()
    {
        $title = "Empresa :: Datos generales";
        $view = "adm.parts.empresa.edit";
        $seccion = "empresa";
        $datos = Empresa::first();

        $datos["email"] = json_decode($datos["email"], true);
        $datos["telefono"] = json_decode($datos["telefono"], true);
        $datos["domicilio"] = json_decode($datos["domicilio"], true);
        $datos["images"] = json_decode($datos["images"], true);
        return view('adm.distribuidor',compact('title','view','datos','seccion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $datos = Empresa::first();
        $requestData = $request->all();
        //dd($requestData);
        $ARR_data = [];
        $ARR_data["horario"] = $requestData["horario"];
        $ARR_data["email"] = empty($requestData["email_email"]) ? [] : $requestData["email_email"];
        $ARR_data["telefono"] = [];
        $ARR_data["domicilio"] = [];
        $ARR_data["domicilio"]["calle"] = $requestData["calle"];
        $ARR_data["domicilio"]["altura"] = $requestData["altura"];
        $ARR_data["domicilio"]["barrio"] = $requestData["barrio"];
        $ARR_data["images"] = [];
        $ARR_data["images"]["logo"] = null;
        $ARR_data["images"]["logoFooter"] = null;
        $ARR_data["images"]["favicon"] = [];
        $ARR_data["images"]["favicon"]["t"] = null;
        $ARR_data["images"]["favicon"]["i"] = null;

        for($i = 0; $i < count($requestData["tipo_telefono"]); $i ++) {
            if(empty($requestData["tipo_telefono"][$i]) || empty($requestData["telefono_telefono"][$i])) continue;

            if(!isset($ARR_data["telefono"][$requestData["tipo_telefono"][$i]]))
                $ARR_data["telefono"][$requestData["tipo_telefono"][$i]] = [];
            $ARR_data["telefono"][$requestData["tipo_telefono"][$i]][] = $requestData["telefono_telefono"][$i];
        }
        
        $logo = $request->file("logo");
        $logoFooter = $request->file("logoFooter");
        $favicon = $request->file("favicon");
        
        if(!is_null($logo)) {
            $path = public_path('images/empresa/');
            if (!file_exists($path))
                mkdir($path, 0777, true);
            $imageName = "logo.".$logo->getClientOriginalExtension();
            
            $logo->move($path, $imageName);
            $ARR_data["images"]["logo"] = "images/empresa/{$imageName}";
        }
        if(!is_null($logoFooter)) {
            $path = public_path('images/empresa/');
            if (!file_exists($path))
                mkdir($path, 0777, true);
            $imageName = "logoFooter.".$logoFooter->getClientOriginalExtension();
            
            $logoFooter->move($path, $imageName);
            $ARR_data["images"]["logoFooter"] = "images/empresa/{$imageName}";
        }
        if(!is_null($favicon)) {
            $path = public_path('images/empresa/');
            if (!file_exists($path))
                mkdir($path, 0777, true);
            $imageName = "favicon.".$favicon->getClientOriginalExtension();
            
            $favicon->move($path, $imageName);
            $ARR_data["images"]["favicon"]["t"] = $favicon->getClientOriginalExtension();
            $ARR_data["images"]["favicon"]["i"] = "images/empresa/{$imageName}";
        }
        $ARR_data["email"] = json_encode($ARR_data["email"]);
        $ARR_data["telefono"] = json_encode($ARR_data["telefono"]);
        $ARR_data["domicilio"] = json_encode($ARR_data["domicilio"]);
        $ARR_data["images"] = json_encode($ARR_data["images"]);
        $datos->fill($ARR_data);
        $datos->save();
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
