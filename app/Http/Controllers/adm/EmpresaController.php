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
        $datos["pago"] = json_decode($datos["pago"], true);
        
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
        $datos["images"] = json_decode($datos["images"], true);
        $requestData = $request->all();
        
        $ARR_data = [];
        $ARR_data["horario"] = $requestData["horario"];
        $ARR_data["email"] = empty($requestData["email_email"]) ? [] : $requestData["email_email"];
        $ARR_data["telefono"] = [];
        $ARR_data["domicilio"] = [];
        $ARR_data["domicilio"]["calle"] = $requestData["calle"];
        $ARR_data["domicilio"]["altura"] = $requestData["altura"];
        $ARR_data["domicilio"]["barrio"] = $requestData["barrio"];
        $ARR_data["images"] = [];
        $ARR_data["images"]["logo"] = $datos["images"]["logo"];
        $ARR_data["images"]["logoFooter"] = $datos["images"]["logoFooter"];
        $ARR_data["images"]["favicon"] = $datos["images"]["logoFooter"];

        $ARR_data["pago"] = [];
        $ARR_data["pago"]["tb"] = [];
        $ARR_data["pago"]["mp"] = $requestData["textomp"];
        $ARR_data["pago"]["pl"] = $requestData["textopl"];
        $ARR_data["pago"]["tb"]["banco"] = $requestData["banco"];
        $ARR_data["pago"]["tb"]["nro"] = $requestData["nro"];
        $ARR_data["pago"]["tb"]["suc"] = $requestData["suc"];
        $ARR_data["pago"]["tb"]["tipo"] = $requestData["tipo"];
        $ARR_data["pago"]["tb"]["nombre"] = $requestData["nombre"];
        $ARR_data["pago"]["tb"]["cuit"] = $requestData["cuit"];
        $ARR_data["pago"]["tb"]["cbu"] = $requestData["cbu"];
        $ARR_data["pago"]["tb"]["emailpago"] = $requestData["emailpago"];

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
        $ARR_data["pago"] = json_encode($ARR_data["pago"]);
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
