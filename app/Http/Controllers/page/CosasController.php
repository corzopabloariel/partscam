<?php

namespace App\Http\Controllers\page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Empresa;
use App\Contenido;
use App\Slider;
use App\Familia;
class CosasController extends Controller
{
    public $idioma = "es";
    public function pagos() {
        $title = "PAGOS Y ENVÍOS";
        $view = "page.parts.pagos";
        $datos = [];
        $datos["contenido"] = json_decode(Contenido::where('seccion','pagos')->first()["data"],true);
        $datos["contenido"]["CONTENIDO"]["texto"] = $datos["contenido"]["CONTENIDO"]["texto"][$this->idioma];
        $datos["contenido"]["CONTENIDO"]["titulo"] = $datos["contenido"]["CONTENIDO"]["titulo"][$this->idioma];
        
        $datos["slider"] = Slider::where('seccion','pagos')->get();
        foreach($datos["slider"] AS $s)
            $s["texto"] = json_decode($s["texto"],true)[$this->idioma];
        $datos["empresa"] = self::datos();
        $datos["familias"] = self::familiaMenu();
        return view('page.distribuidor',compact('title','view','datos'));
    }
    
    public function terminos() {
        $title = "TÉRMINOS Y CONDICIONES";
        $view = "page.parts.terminos";
        $datos = [];
        $datos["contenido"] = json_decode(Contenido::where('seccion','terminos')->first()["data"],true);
        $datos["contenido"]["CONTENIDO"]["texto"] = $datos["contenido"]["CONTENIDO"]["texto"][$this->idioma];
        $datos["contenido"]["CONTENIDO"]["titulo"] = $datos["contenido"]["CONTENIDO"]["titulo"][$this->idioma];
        $datos["empresa"] = self::datos();
        $datos["familias"] = self::familiaMenu();
        return view('page.distribuidor',compact('title','view','datos'));
    }

    public function datos() {
        $empresa = Empresa::first();

        $empresa["email"] = json_decode($empresa["email"], true);
        $empresa["telefono"] = json_decode($empresa["telefono"], true);
        $empresa["domicilio"] = json_decode($empresa["domicilio"], true);
        $empresa["images"] = json_decode($empresa["images"], true);
        $empresa["pago"] = json_decode($empresa["pago"], true);

        return $empresa;
    }
    public function familiaMenu() {
        $data = Familia::where("id","!=",5)->orderBy('orden')->pluck('nombre','id');
        $familias = [];
        foreach($data AS $i => $n) {
            $dd = Familia::find($i)->categorias->where("padre_id",0)->pluck('nombre','id');
            $familias[$i] = [];
            $familias[$i]["nombre"] = $n;
            $familias[$i]["sub"] = $dd;
        }
        return $familias;
    }
}
