<?php

namespace App\Http\Controllers\page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Empresa;
use App\Contenido;
use App\Slider;
use App\Marca;

class GeneralController extends Controller
{
    public $idioma = "es";
    public function index() {
        $title = "HOME";
        $view = "page.parts.index";
        $datos = [];
        $datos["contenido"] = json_decode(Contenido::where('seccion','home')->first()["data"],true);
        $datos["contenido"]["CONTENIDO"]["texto"] = $datos["contenido"]["CONTENIDO"]["texto"][$this->idioma];
        $datos["slider"] = Slider::where('seccion','home')->get();
        foreach($datos["slider"] AS $s)
            $s["texto"] = json_decode($s["texto"],true)[$this->idioma];
        $datos["marcas"] = Marca::orderBy('orden')->get();
        $datos["empresa"] = self::datos();

        return view('page.distribuidor',compact('title','view','datos'));
    }

    public function empresa() {
        $title = "EMPRESA";
        $view = "page.parts.empresa";
        $datos = [];
        $datos["contenido"] = json_decode(Contenido::where('seccion','empresa')->first()["data"],true);
        $datos["contenido"]["CONTENIDO"]["empresa"]["titulo"] = $datos["contenido"]["CONTENIDO"]["empresa"]["titulo"][$this->idioma];
        $datos["contenido"]["CONTENIDO"]["empresa"]["texto"] = $datos["contenido"]["CONTENIDO"]["empresa"]["texto"][$this->idioma];
        $datos["contenido"]["CONTENIDO"]["filosofia"]["titulo"] = $datos["contenido"]["CONTENIDO"]["filosofia"]["titulo"][$this->idioma];
        $datos["contenido"]["CONTENIDO"]["filosofia"]["texto"] = $datos["contenido"]["CONTENIDO"]["filosofia"]["texto"][$this->idioma];
        $datos["slider"] = Slider::where('seccion','empresa')->get();
        foreach($datos["slider"] AS $s)
            $s["texto"] = json_decode($s["texto"],true)[$this->idioma];
        $datos["empresa"] = self::datos();
        
        return view('page.distribuidor',compact('title','view','datos'));
    }

    public function contacto() {
        $title = "CONTACTO";
        $view = "page.parts.contacto";
        $datos = [];
        $datos["empresa"] = self::datos();

        return view('page.distribuidor',compact('title','view','datos'));
    }

    public function datos() {
        $empresa = Empresa::first();

        $empresa["email"] = json_decode($empresa["email"], true);
        $empresa["telefono"] = json_decode($empresa["telefono"], true);
        $empresa["domicilio"] = json_decode($empresa["domicilio"], true);
        $empresa["images"] = json_decode($empresa["images"], true);

        return $empresa;
    }
}
