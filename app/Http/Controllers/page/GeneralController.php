<?php

namespace App\Http\Controllers\page;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use MP;
use Cookie;

use App\Empresa;
use App\Contenido;
use App\Slider;
use App\Marca;
use App\Familia;
use App\Producto;
use App\Productooferta;
use App\Categoria;

use App\CondicionIva;
use App\Provincia;
use App\Localidad;
use App\Persona;

use App\Transaccion;
use App\TransaccionProducto;

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

    public function confirmar($tipo) {
        $title = "CARRITO";
        $view = "page.parts.confirmar.{$tipo}";
        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = Familia::orderBy('orden')->pluck('nombre','id');
        $datos["select2"] = [];
        $condicion = CondicionIva::orderBy('nombre')->pluck('nombre', 'id');
        $provincias = Provincia::orderBy('nombre')->pluck('nombre', 'id');
        $datos["select2"]["condicion"] = [];
        $datos["select2"]["provincia"] = [];
        $datos["select2"]["condicion"][] = ["id" => "", "text" => ""];
        $datos["select2"]["provincia"][] = ["id" => "", "text" => ""];

        foreach($condicion AS $k => $v)
            $datos["select2"]["condicion"][] = ["id" => $k, "text" => $v];
        foreach($provincias AS $k => $v)
            $datos["select2"]["provincia"][] = ["id" => $k, "text" => $v];
        
        return view('page.distribuidor',compact('title','view','datos'));
    }
    public function persona($tipo, $value) {
        $data = Persona::where($tipo,"=",$value)->orderBy('id',"DESC")->first();
        
        return $data;
    }

    public function localidad($provincia_id) {
        $data = Provincia::find($provincia_id)->localidades;
        
        $ARR = [];
        $ARR[] = ["id" => "", "text" => ""];

        foreach($data AS $l)
            $ARR[] = ["id" => $l["id"], "text" => $l->getNombreCPAttribute()];
        
        return $ARR;
    }
    public function order(Request $request) {
        $data = $request->all();
        $nombre = $data["nombre"];
        $apellido = $data["apellido"];
        $email = $data["email"];
        $cuit = $data["cuit"];
        $domicilio = $data["domicilio"];
        $provincia_id = $data["provincia_id"];
        $localidad_id = $data["localidad_id"];
        $telefono = $data["telefono"];
        /** ------------- */
        $pedido = json_decode($data["pedido"], true);
        /** ------------- */
        $condicioniva_id = $data["condicioniva_id"];
        $payment_method = $data["payment_method"];
        $payment_shipping = $data["payment_shipping"];

        //dd($payment_method);
        switch($payment_method) {
            case "pl":
                //GUARDA información
                $transaccion = Transaccion::create([
                    "tipopago" => strtoupper($payment_method),
                    "shipping" => strtoupper($payment_shipping),
                    "estado" => 1,
                    "total" => $pedido["TOTAL"]
                ]);
                Persona::create([
                    "email" => $email,//ENVIAR COMPOBANTE
                    "cuit" => $cuit,
                    "nombre" => $nombre,
                    "apellido" => $apellido,
                    "telefono" => $telefono,
                    "domicilio" => $domicilio,
                    "condicioniva_id" => $condicioniva_id,
                    "transaccion_id" => $transaccion["id"],
                    "provincia_id" => $provincia_id,
                    "localidad_id" => $localidad_id
                ]);
                foreach($pedido AS $i => $v) {
                    if($i == "TOTAL") continue;
                    TransaccionProducto::create([
                        "cantidad" => $v["PEDIDO"],
                        "consultar" => $v["PEDIDO"] - $v["STOCK"],
                        "precio" => $v["PRECIO"],
                        "transaccion_id" => $transaccion["id"],
                        "producto_id" => $i
                    ]);
                }
                Cookie::queue("transaccion", $transaccion["id"], 100);
                break;
        }
        return ["tipo" => $payment_method];
    }

    public function pedido($tipo) {
        $title = "CARRITO";
        $view = "page.parts.carrito";
        //Cookie::queue("prueba", "1", 10);

       // dd(Cookie::get("prueba"));

        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = Familia::orderBy('orden')->pluck('nombre','id');
        
        return view('page.distribuidor',compact('title','view','datos'));
    }

    public function carrito() {
        $title = "CARRITO";
        $view = "page.parts.carrito";
        //Cookie::queue("prueba", "1", 10);

       // dd(Cookie::get("prueba"));

        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = Familia::orderBy('orden')->pluck('nombre','id');
        
        return view('page.distribuidor',compact('title','view','datos'));
    }

    public function getCreatePreference()
    {
        $preferenceData = [
            'items' => [
                [
                    'id' => 12,
                    'category_id' => 'phones',
                    'title' => 'iPhone 6',
                    'description' => 'iPhone 6 de 64gb nuevo',
                    'picture_url' => 'http://d243u7pon29hni.cloudfront.net/images/products/iphone-6-dorado-128-gb-red-4g-8-mpx-1256254%20(1)_m.png',
                    'quantity' => 1,
                    'currency_id' => 'ARS',
                    'unit_price' => 14999
                ]
            ],
        ];

        try {
            $preference = MP::create_preference($preferenceData);
            return redirect()->to($preference['response']['init_point']);
        } catch (Exception $e){
            dd($e->getMessage());
        }

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
