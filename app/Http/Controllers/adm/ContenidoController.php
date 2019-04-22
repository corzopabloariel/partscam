<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contenido;
class ContenidoController extends Controller
{
    public $idioma = "es";

    public function edit($seccion) {
        $contenido = Contenido::where("seccion",$seccion)->first();
        $view = "adm.parts.contenido.edit";
        if(empty($contenido)) {
            $ARR_data = [
                "seccion" => $seccion,
                "data" => null
            ];
            switch($seccion) {
                //PAGE - solo modifica la aparición de elementos en la vista, excepto header y footer
                case "home":
                    $ARR_data["data"] = [];
                    $ARR_data["data"]["PAGE"] = ["slider","marcas","familias","buscador","ofertas","entrega"];
                    $ARR_data["data"]["CONTENIDO"] = [];
                    $ARR_data["data"]["CONTENIDO"]["texto"] = null;
                    $ARR_data["data"]["CONTENIDO"]["image"] = null;
                    break;
                case "empresa":
                    $ARR_data["data"] = [];
                    $ARR_data["data"]["PAGE"] = ["slider"];
                    $ARR_data["data"]["CONTENIDO"] = [];
                    $ARR_data["data"]["CONTENIDO"]["empresa"] = [];
                    $ARR_data["data"]["CONTENIDO"]["empresa"]["titulo"] = null;
                    $ARR_data["data"]["CONTENIDO"]["empresa"]["texto"] = null;
                    $ARR_data["data"]["CONTENIDO"]["filosofia"] = [];
                    $ARR_data["data"]["CONTENIDO"]["filosofia"]["titulo"] = null;
                    $ARR_data["data"]["CONTENIDO"]["filosofia"]["texto"] = null;
                    $ARR_data["data"]["CONTENIDO"]["image"] = null;
                    break;
                case "productos":
                    $ARR_data["data"] = [];
                    $ARR_data["data"]["PAGE"] = ["familias","marcas"];
                    $ARR_data["data"]["CONTENIDO"] = [];
                    $ARR_data["data"]["CONTENIDO"]["texto"] = null;
                    $ARR_data["data"]["CONTENIDO"]["image"] = null;
                    break;
                case "pagos":
                    $ARR_data["data"] = [];
                    $ARR_data["data"]["PAGE"] = ["slider"];
                    $ARR_data["data"]["CONTENIDO"] = [];
                    $ARR_data["data"]["CONTENIDO"]["titulo"] = null;
                    $ARR_data["data"]["CONTENIDO"]["texto"] = null;
                    break;
            }
            $ARR_data["data"] = json_encode($ARR_data["data"]);
            $contenido = Contenido::create($ARR_data);
        }
        $contenido["data"] = json_decode($contenido["data"], true);
        $title = "Contenido: " . strtoupper($seccion);
        return view('adm.distribuidor',compact('title','view','contenido','seccion'));
    }
    public function update(Request $request, $seccion) {
        $datosRequest = $request->all();
        $contenido = Contenido::where('seccion',$seccion)->first();

        $contenido["data"] = json_decode($contenido["data"], true);
        $ARR_data = [];
        $ARR_data["data"] = [];
        $ARR_data["data"]["CONTENIDO"] = [];
        switch($seccion) {
            case "home":
                $ARR_data["data"]["CONTENIDO"]["texto"] = [];
                $ARR_data["data"]["CONTENIDO"]["image"] = null;
                $ARR_data["data"]["CONTENIDO"]["texto"][$this->idioma] = $datosRequest["texto_{$this->idioma}"];
                $ARR_data["data"]["PAGE"] = $datosRequest["page"];
                
                $file = $request->file("image");
                if(!is_null($file)) {
                    $path = public_path('images/contenido/')."{$seccion}";
                    if (!file_exists($path))
                        mkdir($path, 0777, true);
                    $imageName = time()."_{$seccion}.".$file->getClientOriginalExtension();
                    
                    $file->move($path, $imageName);
                    $ARR_data["data"]["CONTENIDO"]["image"] = "images/contenido/{$seccion}/{$imageName}";
                    
                    if(!is_null($contenido["data"]["CONTENIDO"]["image"])) {
                        $filename = public_path() . "/{$contenido["data"]["CONTENIDO"]["image"]}";
                        if (file_exists($filename))
                            unlink($filename);
                    }
                }
                break;
            case "empresa":
                $ARR_data["data"]["CONTENIDO"]["empresa"] = [];
                $ARR_data["data"]["CONTENIDO"]["empresa"]["titulo"] = [];
                $ARR_data["data"]["CONTENIDO"]["empresa"]["titulo"][$this->idioma] = $datosRequest["titulo_empresa_{$this->idioma}"];
                $ARR_data["data"]["CONTENIDO"]["empresa"]["texto"] = [];
                $ARR_data["data"]["CONTENIDO"]["empresa"]["texto"][$this->idioma] = $datosRequest["texto_empresa_{$this->idioma}"];
                $ARR_data["data"]["CONTENIDO"]["filosofia"] = [];
                $ARR_data["data"]["CONTENIDO"]["filosofia"]["titulo"] = [];
                $ARR_data["data"]["CONTENIDO"]["filosofia"]["titulo"][$this->idioma] = $datosRequest["titulo_filosofia_{$this->idioma}"];
                $ARR_data["data"]["CONTENIDO"]["filosofia"]["texto"] = [];
                $ARR_data["data"]["CONTENIDO"]["filosofia"]["texto"][$this->idioma] = $datosRequest["texto_filosofia_{$this->idioma}"];
                $ARR_data["data"]["CONTENIDO"]["image"] = null;
                $ARR_data["data"]["PAGE"] = $datosRequest["page"];

                $file = $request->file("image");
                if(!is_null($file)) {
                    $path = public_path('images/contenido/')."{$seccion}";
                    if (!file_exists($path))
                        mkdir($path, 0777, true);
                    $imageName = time()."_{$seccion}.".$file->getClientOriginalExtension();
                    
                    $file->move($path, $imageName);
                    $ARR_data["data"]["CONTENIDO"]["image"] = "images/contenido/{$seccion}/{$imageName}";
                    
                    if(!is_null($contenido["data"]["CONTENIDO"]["image"])) {
                        $filename = public_path() . "/{$contenido["data"]["CONTENIDO"]["image"]}";
                        if (file_exists($filename))
                            unlink($filename);
                    }
                }
                break;
            case "pagos":
                $ARR_data["data"]["CONTENIDO"]["titulo"] = [];
                $ARR_data["data"]["CONTENIDO"]["titulo"][$this->idioma] = $datosRequest["titulo_{$this->idioma}"];
                $ARR_data["data"]["CONTENIDO"]["texto"] = [];
                $ARR_data["data"]["CONTENIDO"]["texto"][$this->idioma] = $datosRequest["texto_{$this->idioma}"];
                $ARR_data["data"]["PAGE"] = $datosRequest["page"];
                break;
        }
        $contenido->fill(["data" => json_encode($ARR_data["data"])]);
        $contenido->save();

        return back();
    }
}