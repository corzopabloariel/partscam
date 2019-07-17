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
                case "terminos":
                    $ARR_data["data"] = [];
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
                $ARR_data["data"]["CONTENIDO"]["image"] = $contenido["data"]["CONTENIDO"]["image"];
                $ARR_data["data"]["CONTENIDO"]["texto"][$this->idioma] = $datosRequest["texto_{$this->idioma}"];
                
                $file = $request->file("image");
                //dd($file);
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
                $datosRequest["ATRIBUTOS_REPUESTOS"] = json_decode( $datosRequest["ATRIBUTOS_REPUESTOS"] , true );
                $datosRequest["ATRIBUTOS_ICONO"] = json_decode( $datosRequest["ATRIBUTOS_ICONO"] , true );
                $datosRequest["ATRIBUTOS_REPUESTO"] = json_decode( $datosRequest["ATRIBUTOS_REPUESTO"] , true );
                foreach($datosRequest AS $k => $v) {
                    $pos = strpos($k, "icono_texto_");
                    if ($pos !== false) {
                        $aux = explode("_",$k);
                        $aux_key = "";
                        for($i = 0 ; $i < count($aux) ; $i ++) {
                            if(is_numeric($aux[$i])) continue;
                            if(!empty($aux_key)) $aux_key .= "_";
                            $aux_key .= $aux[$i];
                        }
                        if(!isset($aux_datos[$aux_key]))
                            $aux_datos[$aux_key] = [];
                        if(!is_array($v))
                            $aux_datos[$aux_key][] = $v;
                        //dd($v);
                        unset($datosRequest[$k]);
                    }
                }
                $datosRequest = array_merge($datosRequest, $aux_datos);
                
                $ARR_data["data"]["repuestos"] = [];
                $ARR_data["data"]["iconos"] = [];
                
                foreach($datosRequest["ATRIBUTOS_REPUESTOS"] AS $nombre => $tipo) {
                    $ARR_data["data"]["repuestos"][$nombre] = null;
                    if($tipo == "TP_FILE") {
                        if(isset($contenido["data"]["repuestos"][$nombre]))
                            $ARR_data["data"]["repuestos"][$nombre] = $contenido["data"]["repuestos"][$nombre];
                        $file = $request->file($nombre);
                        //dd($file);
                        //dd($ARR_data["data"]["repuestos"]);
                        
                        if(!is_null($file)) {
                            $path = public_path("images/contenido/{$seccion}");
                            if (!file_exists($path))
                                mkdir($path, 0777, true);
                            $imageName = time()."_{$seccion}.".$file->getClientOriginalExtension();
                            
                            $file->move($path, $imageName);
                            $ARR_data["data"]["repuestos"][$nombre] = "images/contenido/{$seccion}/{$imageName}";
                            
                            if(!empty($contenido["data"]["repuestos"])) {
                                $filename = public_path() . "/" . $contenido["data"]["repuestos"][$nombre];
                                if(!empty($contenido["data"][$nombre])) {
                                    if (file_exists($filename))
                                        unlink($filename);
                                }
                            }
                            
                        }
                    } else
                        $ARR_data["data"]["repuestos"][$nombre] = $datosRequest["{$nombre}"];
                }
                //dd($ARR_data["data"]["repuestos"]);
                for($i = 0 ; $i < count($datosRequest["removeIcono"]) ; $i++) {
                    $OBJ_AUX = [];
                    foreach($datosRequest["ATRIBUTOS_ICONO"] AS $nombre => $tipo) {

                        if($tipo == "TP_FILE") {
                            $file = $request->file("icono_{$nombre}");
                            $OBJ_AUX[$nombre] = $contenido["data"]["iconos"][$i][$nombre];
                            if(!empty($file)) {
                                if(!empty($file[$i])) {
                                    $path = public_path("images/contenido/{$seccion}");
                                    if (!file_exists($path))
                                        mkdir($path, 0777, true);
                                    $imageName = time()."_{$i}_icono_{$seccion}.".$file->getClientOriginalExtension();
                                    
                                    $file[$i]->move($path, $imageName);
                                    $OBJ_AUX[$nombre] = "images/contenido/{$seccion}/{$imageName}";
                                    if(!empty($contenido["data"]["iconos"])) {
                                        if(!isset($contenido["data"]["iconos"][$i])) {
                                            $filename = public_path() . "/" . $contenido["data"]["iconos"][$i];
                                            if(!empty($contenido["data"]["iconos"][$i][$nombre])) {
                                                if (file_exists($filename))
                                                    unlink($filename);
                                            }
                                        }
                                    }
                                }
                            }
                        } else
                            $OBJ_AUX[$nombre] = $datosRequest["icono_{$nombre}"][$i];
                    }
                    $ARR_data["data"]["iconos"][] = $OBJ_AUX;
                }
                
                for($i = 0 ; $i < count($datosRequest["removeRepuesto"]) ; $i++) {
                    $OBJ_AUX = [];
                    foreach($datosRequest["ATRIBUTOS_REPUESTO"] AS $nombre => $tipo) {
                        if($tipo == "TP_FILE") {
                            //$file = $request->file("repuesto_{$nombre}")[$i];
                            $file = $request->file("repuesto_{$nombre}");
                            $OBJ_AUX[$nombre] = $contenido["data"]["repuestos"]["repuesto"][$i][$nombre];
                            if(!empty($file)) {
                                $path = public_path("images/contenido/{$seccion}");
                                if (!file_exists($path))
                                    mkdir($path, 0777, true);
                                $imageName = time()."_{$i}_repuesto_{$seccion}.".$file->getClientOriginalExtension();
                                
                                $file->move($path, $imageName);
                                $OBJ_AUX[$nombre] = "images/contenido/{$seccion}/{$imageName}";
                                if(!empty($contenido["data"]["repuestos"]["repuesto"])) {
                                    if(!isset($contenido["data"]["repuestos"]["repuesto"][$i])) {
                                        $filename = public_path() . "/" . $contenido["data"]["repuestos"]["repuesto"][$i];
                                        if(!empty($contenido["data"]["repuestos"]["repuesto"][$i][$nombre])) {
                                            if (file_exists($filename))
                                                unlink($filename);
                                        }
                                    }
                                }
                            }
                        } else
                            $OBJ_AUX[$nombre] = $datosRequest["repuesto_{$nombre}"][$i];
                    }
                    $ARR_data["data"]["repuestos"]["repuesto"][] = $OBJ_AUX;
                }
                 
                $contenido->fill(["data" => json_encode($ARR_data["data"])]);
                $contenido->save();
                return 1;
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
                $ARR_data["data"]["CONTENIDO"]["image"] = $contenido["data"]["CONTENIDO"]["image"];
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
            case "terminos":
                $ARR_data["data"]["CONTENIDO"]["titulo"] = [];
                $ARR_data["data"]["CONTENIDO"]["titulo"][$this->idioma] = $datosRequest["titulo_{$this->idioma}"];
                $ARR_data["data"]["CONTENIDO"]["texto"] = [];
                $ARR_data["data"]["CONTENIDO"]["texto"][$this->idioma] = $datosRequest["texto_{$this->idioma}"];
                break;
        }
        $contenido->fill(["data" => json_encode($ARR_data["data"])]);
        $contenido->save();

        return back();
    }
}