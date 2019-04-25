<?php

namespace App\Http\Controllers\page;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Empresa;
use App\Contenido;
use App\Slider;
use App\Marca;
use App\Familia;
use App\Producto;
use App\Productooferta;
use App\Categoria;

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
        $datos["familias"] = Familia::orderBy('orden')->pluck('nombre','id');
        $datos["prodfamilias"] = Familia::orderBy('orden')->get();

        $datos["ofertas"] = Productooferta::orderBy('orden')->limit(4)->get();
        foreach($datos["ofertas"] AS $o) {
            $image = null;
            if(count($o->producto->imagenes) > 0)
                $image = $o->producto->imagenes[0]["image"];
            $o["precioAnterior"] = number_format($o->producto->precio["precio"],2,",",".");
            $o["precio"] = number_format($o["precio"],2,",",".");
            $o["producto"] = $o->producto["nombre"];
            $o["image"] = $image;

        }

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
        $datos["familias"] = Familia::orderBy('orden')->pluck('nombre','id');
        
        return view('page.distribuidor',compact('title','view','datos'));
    }

    public function productos() {
        $title = "PRODUCTOS";
        $view = "page.parts.producto";
        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = Familia::orderBy('orden')->pluck('nombre','id');
        $datos["prodfamilias"] = Familia::orderBy('orden')->get();

        return view('page.distribuidor',compact('title','view','datos'));
    }
    
    public function ofertas() {
        $title = "OFERTAS DE PRODUCTOS";
        $view = "page.parts.ofertas";
        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = Familia::orderBy('orden')->pluck('nombre','id');
        $datos["slider"] = Slider::where('seccion','oferta')->get();
        foreach($datos["slider"] AS $s)
            $s["texto"] = json_decode($s["texto"],true)[$this->idioma];
        $datos["ofertas"] = Productooferta::orderBy('orden')->get();
        foreach($datos["ofertas"] AS $o) {
            $image = null;
            if(count($o->producto->imagenes) > 0)
                $image = $o->producto->imagenes[0]["image"];
            $o["precioAnterior"] = number_format($o->producto->precio["precio"],2,",",".");
            $o["precio"] = number_format($o["precio"],2,",",".");
            $o["producto"] = $o->producto["nombre"];
            $o["image"] = $image;

        }
        
        return view('page.distribuidor',compact('title','view','datos'));
    }

    public function contacto() {
        $title = "CONTACTO";
        $view = "page.parts.contacto";
        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = Familia::orderBy('orden')->pluck('nombre','id');

        return view('page.distribuidor',compact('title','view','datos'));
    }
    /** */
    public function familia($id) {
        $title = "PRODUCTOS";
        $view = "page.parts.familia";
        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = Familia::orderBy('orden')->pluck('nombre','id');
        $datos["marcas"] = Marca::orderBy('orden')->get();

        $datos["familia"] = Familia::find($id);
        $familias = Familia::get();
        $datos["menu"] = [];
        foreach($familias AS $f) {
            $datos["menu"][$f["id"]] = [];
            $datos["menu"][$f["id"]]["nivel"] = 0;
            $datos["menu"][$f["id"]]["titulo"] = $f["nombre"];
            $datos["menu"][$f["id"]]["hijos"]= self::categoriasRec($f->categorias->where('padre_id',0),0);
        }
        return view('page.distribuidor',compact('title','view','datos'));
    }
    /** */
    public function categoria($id) {
        $title = "PRODUCTOS";
        $view = "page.parts.categoria";
        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = Familia::orderBy('orden')->pluck('nombre','id');
        $datos["categoria"] = Categoria::find($id);
        $datos["familia"] = $datos["categoria"]->familia;

        $aux = $datos["categoria"];
        $idsCategorias = [];
        
        do {
            $idsCategorias[] = $aux["id"];
            $aux = $aux->padre;
        } while(!empty($aux));
        $idsCategorias[] = $datos["familia"]["id"];
        $idsCategorias = array_reverse ($idsCategorias);
        
        $datos["idsCategorias"] = $idsCategorias;
        $familias = Familia::get();
        $datos["menu"] = [];
        foreach($familias AS $f) {
            $datos["menu"][$f["id"]] = [];
            $datos["menu"][$f["id"]]["activo"] = 0;

            if($f["id"] == $idsCategorias[0])
                $datos["menu"][$f["id"]]["activo"] = 1;
            $datos["menu"][$f["id"]]["nivel"] = 0;
            $datos["menu"][$f["id"]]["titulo"] = $f["nombre"];
            $datos["menu"][$f["id"]]["image"] = $f["image"];
            $datos["menu"][$f["id"]]["hijos"]= self::categoriasRec($f->categorias->where('padre_id',0),0,$idsCategorias);
        }
        //dd($datos["menu"]);
        return view('page.distribuidor',compact('title','view','datos'));
    }
    public function producto($id) {
        $title = "PRODUCTOS";
        $view = "page.parts.productoGeneral";
        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = Familia::orderBy('orden')->pluck('nombre','id');
        $datos["producto"] = Producto::find($id);
        $datos["imagenes"] = $datos["producto"]->imagenes;
        $datos["oferta"] = empty($datos["producto"]->oferta) ? null : number_format($datos["producto"]->oferta["precio"],2,",",".");
        $datos["precio"] = number_format($datos["producto"]->precio["precio"],2,",",".");
        $datos["stock"] = $datos["producto"]->stock;
        $datos["categoria"] = $datos["producto"]->categoria;
        $datos["familia"] = $datos["producto"]->familia;
        $datos["productos"] = $datos["producto"]->productos;

        $aux = $datos["categoria"];
        $idsCategorias = [];
        do {
            $idsCategorias[] = $aux["id"];
            $aux = $aux->padre;
        } while(!empty($aux));
        $idsCategorias[] = $datos["familia"]["id"];
        $idsCategorias = array_reverse ($idsCategorias);
        
        $datos["idsCategorias"] = $idsCategorias;
        $familias = Familia::get();
        $datos["menu"] = [];
        foreach($familias AS $f) {
            $datos["menu"][$f["id"]] = [];
            $datos["menu"][$f["id"]]["activo"] = 0;

            if($f["id"] == $idsCategorias[0])
                $datos["menu"][$f["id"]]["activo"] = 1;
            $datos["menu"][$f["id"]]["nivel"] = 0;
            $datos["menu"][$f["id"]]["titulo"] = $f["nombre"];
            $datos["menu"][$f["id"]]["image"] = $f["image"];
            $datos["menu"][$f["id"]]["hijos"]= self::categoriasRec($f->categorias->where('padre_id',0),0,$idsCategorias);
        }
        return view('page.distribuidor',compact('title','view','datos'));
    }
    /** */
    public function categoriasRec($categorias, $nivel, $activo = null) {
        $menu = [];
        $nivel ++;
        foreach($categorias AS $c) {
            if(!isset($menu[$c["id"]])) {
                $menu[$c["id"]] = [];
                $menu[$c["id"]]["activo"] = 0;
                if(isset($activo[$nivel])) {
                    if($activo[$nivel] == $c["id"])
                        $menu[$c["id"]]["activo"] = 1;
                }
                $menu[$c["id"]]["nivel"] = $nivel;
                $menu[$c["id"]]["titulo"] = $c["nombre"];
                $menu[$c["id"]]["image"] = $c["image"];
                $menu[$c["id"]]["productos"] = $c->productos;
                foreach($menu[$c["id"]]["productos"] AS $i)
                    $i["imagenes"] = $i->imagenes;
                $menu[$c["id"]]["hijos"] = [];
            }
            $menu[$c["id"]]["hijos"] = self::categoriasRec($c->hijos, $nivel, $activo);
        }
        return $menu;
    }
    /** */
    public function buscador(Request $request, $tipo) {
        $buscar = $request->all()["input"];

        $results = DB::table('productos')
                        ->distinct()
                        /*->leftJoin('categorias', function($join) {
                            $join->on('categorias.id', '=', 'productos.categoria_id');
                        })
                        ->leftJoin('familias', function($join) {
                            $join->on('familias.id', '=', 'productos.familia_id');
                        })*/
                ->where('productos.nombre','like',"%{$buscar}%")
                ->orWhere('productos.codigo','like',"%{$buscar}%")
                //->orWhere('categorias.nombre','like',"%{$buscar}%")
                //->orWhere('familias.nombre','like',"%{$buscar}%")
            ->get();
        dd($results);
    }

    public function carrito() {
        $title = "CARRITO";
        $view = "page.parts.carrito";
        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = Familia::orderBy('orden')->pluck('nombre','id');
        

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
