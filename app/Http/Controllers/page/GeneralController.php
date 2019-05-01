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
use App\Productostock;

use Illuminate\Support\Facades\Mail;
use App\Mail\Pedido;
use App\Mail\PedidoCliente;

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
        $datos["familias"] = self::familiaMenu();
        $datos["prodfamilias"] = Familia::where("id","!=",5)->orderBy('orden')->get();

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
        $datos["familias"] = self::familiaMenu();
        
        return view('page.distribuidor',compact('title','view','datos'));
    }

    public function productos() {
        $title = "PRODUCTOS";
        $view = "page.parts.producto";
        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = self::familiaMenu();
        $datos["prodfamilias"] = Familia::where("id","!=",5)->orderBy('orden')->get();

        return view('page.distribuidor',compact('title','view','datos'));
    }
    
    public function ofertas() {
        $title = "OFERTAS DE PRODUCTOS";
        $view = "page.parts.ofertas";
        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = self::familiaMenu();
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
        $datos["familias"] = self::familiaMenu();

        return view('page.distribuidor',compact('title','view','datos'));
    }
    /** */
    public function familia($id) {
        $title = "PRODUCTOS";
        $view = "page.parts.familia";
        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = self::familiaMenu();
        $datos["marcas"] = Marca::orderBy('orden')->get();

        $datos["familia"] = Familia::where("id","!=",5)->find($id);
        $familias = Familia::where("id","!=",5)->get();
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
        $datos["familias"] = self::familiaMenu();
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
        $familias = Familia::where("id","!=",5)->get();
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
    public function producto($id) {
        $title = "PRODUCTOS";
        $view = "page.parts.productoGeneral";
        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = self::familiaMenu();
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
        } while(!is_null($aux["padre_id"]));
        $idsCategorias[] = $datos["familia"]["id"];
        $idsCategorias = array_reverse ($idsCategorias);
        
        $datos["idsCategorias"] = $idsCategorias;
        $familias = Familia::where("id","!=",5)->get();
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
        $title = "CARRITO";
        $view = "page.parts.buscador";
        
        $datos = [];
        $datos["resultados"] = Producto::where("codigo",$buscar)->get();
        $datos["empresa"] = self::datos();
        $datos["buscar"] = $buscar;
        $datos["familias"] = self::familiaMenu();
        $datos["marcas"] = Marca::orderBy('orden')->get();
        
        return view('page.distribuidor',compact('title','view','datos'));
    }

    public function confirmar($tipo) {
        $title = "CARRITO";
        $view = "page.parts.confirmar.{$tipo}";
        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = self::familiaMenu();
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
        
        //GUARDA información
        $transaccion = Transaccion::create([
            "tipopago" => strtoupper($payment_method),
            "shipping" => strtoupper($payment_shipping),
            "estado" => 1,
            "codigo" => self::generarCodigo(),
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
            $aux = Productostock::where("producto_id",$i)->first();
            $pedido = $v["PEDIDO"];
            if($pedido > $v["STOCK"])
                $pedido = $v["PEDIDO"] - $v["STOCK"];
            $aux->fill(["cantidad" => $pedido]);
            $aux->save();
            TransaccionProducto::create([
                "cantidad" => $v["PEDIDO"],
                "consultar" => $v["PEDIDO"] - $v["STOCK"],
                "precio" => $v["PRECIO"],
                "transaccion_id" => $transaccion["id"],
                "producto_id" => $i
            ]);
        }
        Cookie::queue("transaccion", $transaccion["id"], 100);
        
        return ["tipo" => $payment_method];
    }
    public function generarCodigo($longitud = 5) {
        $codigo = "";
        $caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $max = strlen($caracteres) - 1;
        for( $i = 0 ; $i < $longitud ; $i++)
            $codigo .= $caracteres[rand(0,$max)];
        
        return $codigo;
    }
    public function enviarDetalle($transaccion, $cliente = 0) {
        $persona = $transaccion->persona;
        $productos = $transaccion->productos;
        if($cliente) {//ENVIO A CLIENTE LA INFO
            $empresa = Empresa::first();
            $empresa["pago"] = json_decode($empresa["pago"], true);
            Mail::to($persona["email"])
                ->send(new PedidoCliente($transaccion, $persona, $productos, $empresa["pago"]));
        } else {
            Mail::to('corzo.pabloariel@gmail.com')
                ->send(new Pedido($transaccion, $persona, $productos));
        }
    }
    public function pedido($tipo) {
        $title = "CARRITO";
        $view = "page.parts.confirmar.ok";
        self::enviarDetalle(Transaccion::find(Cookie::get("transaccion")));
        self::enviarDetalle(Transaccion::find(Cookie::get("transaccion")), 1);
        if($tipo == "ok") {
            $datos = [];
            $datos["empresa"] = self::datos();
            $datos["familias"] = self::familiaMenu();
            return view('page.distribuidor',compact('title','view','datos'));
        } else {
            return self::getCreatePreference();
        }
    }

    public function carrito() {
        $title = "CARRITO";
        $view = "page.parts.carrito";
        //Cookie::queue("prueba", "1", 10);

        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = self::familiaMenu();
        
        return view('page.distribuidor',compact('title','view','datos'));
    }

    public function getCreatePreference()
    {
        $transaccion = Transaccion::find(Cookie::get("transaccion"));
        $productos = $transaccion->productos;
        $preferenceData = [
            'external_reference' => Cookie::get("transaccion"),
            'items' => [],
            'back_urls' => [
                'success' => route('payment.success'),
                'failure' => route('payment.failure'),
                'pending' => route('payment.pending'),
            ],
            'notification_url' => route('ipn')
        ];
        $id = "";
        $descripcion = "";
        foreach($productos AS $p) {
            if(!empty($id)) $id .= "-";
            if(!empty($descripcion)) $descripcion .= " / ";
            $id .= $p->producto["id"];
            $o = $p->producto->oferta;
            $precio = $p->producto->precio["precio"];
            if(!empty($o))
                $precio = $o["precio"];
            $precio = number_format($precio,2,",",".");
            $descripcion .= "{$p->producto["nombre"]} - {$p->producto->familia["nombre"]}: $ {$precio} x {$p["cantidad"]}";
        }
        $preferenceData["items"][] = [
            'id' => $id,
            'category_id' => 'PARTSCAM',
            'title' => 'Compra en partscam.com.ar',
            'description' => 'Producto de partscam.com.ar',
            'picture_url' => $descripcion,
            'quantity' => 1,
            'currency_id' => 'ARS',
            'unit_price' => $transaccion["total"]
        ];
        try {
            $preference = MP::create_preference($preferenceData);
            
            return redirect()->to($preference['response']['init_point']);
        } catch (Exception $e){
            dd($e->getMessage());
        }

    }
    public function payment($tipo) {
        dd($tipo);
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
