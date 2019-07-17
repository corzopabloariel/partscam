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
use App\Modelo;
use App\Producto;
use App\Productooferta;
use App\ProductoCategoria;
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

use App\Servicio;

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

        $datos["servicios"] = Servicio::orderBy("orden")->get();

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
            $o["o"] = $o->producto;
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
    public function familia($id, $order = "ASC") {
        $title = "PRODUCTOS";
        $view = "page.parts.familia";
        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = self::familiaMenu();
        $datos["marcas"] = Marca::orderBy('orden')->get();

        $datos["familia"] = Familia::find($id);
        if($id == 5)
            $datos["productosSIN"] = Producto::where("familia_id",$id)->orderBy("nombre",$order)->paginate(15);
        
        $familias = Familia::orderBy("orden")->pluck("nombre","id");
        $datos["modelos"] = Modelo::orderBy("nombre")->pluck("nombre","id");
        $datos["menu"] = [];
        $datos["order"] = $order;
        
        foreach($familias AS $i => $f) {
            $datos["menu"][$i] = [];
            $datos["menu"][$i]["activo"] = 0;
            if($datos["familia"]["id"] == $i)
                $datos["menu"][$i]["activo"] = 1;
            $datos["menu"][$i]["nombre"] = $f;
            $datos["menu"][$i]["modelos"] = [];
            if(strtoupper($f) != "SIN ESPECIFICAR") {
                foreach($datos["modelos"] AS $ii => $m) {
                    $datos["menu"][$i]["modelos"][$ii] = [];
                    $datos["menu"][$i]["modelos"][$ii]["nombre"] = $m;
                    $datos["menu"][$i]["modelos"][$ii]["activo"] = 0;
                    $datos["menu"][$i]["modelos"][$ii]["categorias"] = [];
                }
            }
        }
        
        return view('page.distribuidor',compact('title','view','datos'));
    }
    public function modelo($familia_id,$modelo_id,$tipo, $order = "ASC") {
        $title = "PRODUCTOS";
        $view = "page.parts.familia";
        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = self::familiaMenu();
        $datos["marcas"] = Marca::orderBy('orden')->get();

        $datos["familia"] = Familia::find($familia_id);
        $datos["categorias"] = $datos["familia"]->categorias->where("tipo",$tipo);
        $datos["order"] = $order;
        $datos["modelo_id"] = $modelo_id;
        $categorias = $datos["categorias"]->pluck("nombre","id");
        
        $datos["productos"] = Producto::whereHas('modelosMM', function ($query) use ($modelo_id) {
            $query->where('modelo_id',$modelo_id);
        })->where('familia_id',$familia_id)->orderBy("nombre",$order)->paginate(15);
        
        //$datos["productos"] = Producto::where("familia_id",$id)->orderBy("nombre")->paginate(15);
        //$datos["categorias"] = $datos["categorias"]::paginate(15);
        $familias = Familia::orderBy("orden")->pluck("nombre","id");
        $datos["modelos"] = Modelo::orderBy("nombre")->pluck("nombre","id");
        $datos["menu"] = [];
        
        foreach($familias AS $i => $f) {
            $datos["menu"][$i] = [];
            $datos["menu"][$i]["activo"] = ($datos["familia"]["id"] == $i) ? 1 : 0;
            $datos["menu"][$i]["nombre"] = $f;
            $datos["menu"][$i]["menu"] = "familia";
            $datos["menu"][$i]["tipo"] = 0;
            $datos["menu"][$i]["modelos"] = [];
            foreach($datos["modelos"] AS $ii => $m) {
                $datos["menu"][$i]["modelos"][$ii] = [];
                $datos["menu"][$i]["modelos"][$ii]["activo"] = ($ii == $modelo_id) ? 1 : 0;
                $datos["menu"][$i]["modelos"][$ii]["nombre"] = $m;
                $datos["menu"][$i]["modelos"][$ii]["menu"] = "modelo";
                $datos["menu"][$i]["modelos"][$ii]["tipo"] = 1;
                $datos["menu"][$i]["modelos"][$ii]["categorias"] = [];
                foreach($categorias AS $iii => $c) {
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$iii] = [];
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$iii]["nombre"] = $c;
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$iii]["menu"] = "categoria";
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$iii]["tipo"] = 2;
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$iii]["activo"] = 0;
                }
            }
        }
        
        return view('page.distribuidor',compact('title','view','datos'));
    }
    /** */
    public function categoria($familia_id,$modelo_id,$categoria_id, $tipo, $order = "ASC") {
        $title = "PRODUCTOS";
        $view = "page.parts.familia";
        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = self::familiaMenu();
        $datos["familia"] = Familia::find($familia_id);
        $datos["categoria"] = Categoria::find($categoria_id);
        $datos["categorias"] = $datos["familia"]->categorias;
        
        $categorias = $datos["categorias"];
        $datos["order"] = $order;
        $aux = $datos["categoria"];
        $datos["modelo_id"] = $modelo_id;
        $familias = Familia::pluck("nombre","id");
        $datos["modelos"] = Modelo::orderBy("nombre")->pluck("nombre","id");
        $datos["productos"] = Producto::whereHas('modelosMM', function ($query) use ($modelo_id) {
            $query->where('modelo_id',$modelo_id);
        })->whereHas('categoriaMM', function ($query) use ($categoria_id) {
            $query->where('categoria_id',$categoria_id);
        })->where('familia_id',$familia_id)->orderBy("nombre",$order)->paginate(15);
        $datos["menu"] = [];
        $datos["marcas"] = Marca::orderBy('orden')->get();
        
        foreach($familias AS $i => $f) {
            $datos["menu"][$i] = [];
            $datos["menu"][$i]["activo"] = ($familia_id == $i) ? 1 : 0;
            $datos["menu"][$i]["nombre"] = $f;
            $datos["menu"][$i]["menu"] = "familia";
            $datos["menu"][$i]["tipo"] = 0;
            $datos["menu"][$i]["modelos"] = [];
            foreach($datos["modelos"] AS $ii => $m) {
                $datos["menu"][$i]["modelos"][$ii] = [];
                $datos["menu"][$i]["modelos"][$ii]["activo"] = ($ii == $modelo_id) ? 1 : 0;
                $datos["menu"][$i]["modelos"][$ii]["nombre"] = $m;
                $datos["menu"][$i]["modelos"][$ii]["menu"] = "modelo";
                $datos["menu"][$i]["modelos"][$ii]["tipo"] = 1;
                $datos["menu"][$i]["modelos"][$ii]["categorias"] = [];
                foreach($categorias AS $c) {
                    $hijos = $c->hijos->where("tipo",$tipo);
                    
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]] = [];
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["nombre"] = $c["nombre"];
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["menu"] = "categoria";
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["tipo"] = 2;
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["activo"] = ($c["id"] == $categoria_id) ? 1 : 0;
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"] = [];

                    if(count($hijos) > 0) {
                        foreach($hijos AS $cc) {
                            $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]] = [];
                            $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["activo"] = 0;
                            $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["nombre"] = $cc["nombre"];
                            $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["menu"] = "subcategoria";
                            $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["tipo"] = 3;
                            //$datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["activo"] = 0;
                        }
                    }
                }
            }
        }
        
        return view('page.distribuidor',compact('title','view','datos'));
    }
    /** */
    public function scategoria($familia_id,$modelo_id,$categoria_id,$scategoria_id, $tipo, $order = "ASC") {
        $title = "PRODUCTOS";
        $view = "page.parts.familia";
        $datos = [];
        $datos["empresa"] = self::datos();
        $datos["familias"] = self::familiaMenu();
        $datos["familia"] = Familia::find($familia_id);
        $datos["categoria"] = Categoria::find($categoria_id);
        $datos["categorias"] = $datos["familia"]->categorias;
        
        $categorias = $datos["categorias"];
        $datos["order"] = $order;
        $aux = $datos["categoria"];
        $familias = Familia::pluck("nombre","id");
        $datos["modelo_id"] = $modelo_id;
        $datos["modelos"] = Modelo::orderBy("nombre")->pluck("nombre","id");
        $datos["productos"] = Producto::whereHas('modelosMM', function ($query) use ($modelo_id) {
            $query->where('modelo_id',$modelo_id);
        })->whereHas('categoriaMM', function ($query) use ($categoria_id) {
            $query->where('categoria_id',$categoria_id);
        })->where('familia_id',$familia_id)->orderBy("nombre",$order)->paginate(15);
        $datos["menu"] = [];
        $datos["marcas"] = Marca::orderBy('orden')->get();
        
        foreach($familias AS $i => $f) {
            $datos["menu"][$i] = [];
            $datos["menu"][$i]["activo"] = ($familia_id == $i) ? 1 : 0;
            $datos["menu"][$i]["nombre"] = $f;
            $datos["menu"][$i]["menu"] = "familia";
            $datos["menu"][$i]["tipo"] = 0;
            $datos["menu"][$i]["modelos"] = [];
            foreach($datos["modelos"] AS $ii => $m) {
                $datos["menu"][$i]["modelos"][$ii] = [];
                $datos["menu"][$i]["modelos"][$ii]["activo"] = ($ii == $modelo_id) ? 1 : 0;
                $datos["menu"][$i]["modelos"][$ii]["nombre"] = $m;
                $datos["menu"][$i]["modelos"][$ii]["menu"] = "modelo";
                $datos["menu"][$i]["modelos"][$ii]["tipo"] = 1;
                $datos["menu"][$i]["modelos"][$ii]["categorias"] = [];
                foreach($categorias AS $c) {
                    $hijos = $c->hijos->where("tipo",$tipo - 1);
                    
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]] = [];
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["nombre"] = $c["nombre"];
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["menu"] = "categoria";
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["tipo"] = 2;
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["activo"] = ($c["id"] == $categoria_id) ? 1 : 0;
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"] = [];

                    if(count($hijos) > 0) {
                        foreach($hijos AS $cc) {
                            $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]] = [];
                            $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["activo"] = ($cc["id"] == $scategoria_id) ? 1 : 0;
                            $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["nombre"] = $cc["nombre"];
                            $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["menu"] = "subcategoria";
                            $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["tipo"] = 3;
                            $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["ssubcategorias"] = [];
                            $hijos = $cc->hijos->where("tipo",$tipo);
                            foreach($hijos AS $ccc) {
                                $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["ssubcategorias"][$ccc["id"]] = [];
                                $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["ssubcategorias"][$ccc["id"]]["activo"] = 0;
                                $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["ssubcategorias"][$ccc["id"]]["nombre"] = $ccc["nombre"];
                                $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["ssubcategorias"][$ccc["id"]]["menu"] = "ssubcategoria";
                                $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["ssubcategorias"][$ccc["id"]]["tipo"] = 4;
                            }
                        }
                    }
                }
            }
        }
        
        return view('page.distribuidor',compact('title','view','datos'));
    }
    public function producto($id,$modelo_id) {
        $title = "PRODUCTOS";
        $view = "page.parts.productoGeneral";
        $datos = [];
        $datos["modelo_id"] = $modelo_id;
        $datos["empresa"] = self::datos();
        $datos["familias"] = self::familiaMenu();
        $datos["producto"] = Producto::find($id);
        $datos["imagenes"] = $datos["producto"]->imagenes;
        $datos["oferta"] = empty($datos["producto"]->oferta) ? null : number_format($datos["producto"]->oferta["precio"],2,",",".");
        $datos["precio"] = number_format($datos["producto"]->precio["precio"],2,",",".");
        $datos["stock"] = $datos["producto"]->stock;
        $datos["categoria"] = [];//$datos["producto"]->categoriaM->categoria;
        $datos["familia"] = $datos["producto"]->familia;
        $datos["productos"] = $datos["producto"]->productos;
        $datos["modelos"] = Modelo::orderBy("nombre")->pluck("nombre","id");
        $datos["categorias"] = $datos["familia"]->categorias;
        
        $categorias = $datos["categorias"];
        $idsCategorias = $datos["producto"]->categoriaMM->pluck("categoria_id");
        
        $familia_id = $datos["producto"]["familia_id"];
        $categoria_id = "";
        $scategoria_id = "";
        $sscategoria_id = "";
        
        if(!empty($idsCategorias)) {
            $categoria_id = $idsCategorias[0];
            if(isset($idsCategorias[1]))
                $scategoria_id = $idsCategorias[1];
            if(isset($idsCategorias[2]))
                $sscategoria_id = $idsCategorias[2];
        }
        $datos["idsCategorias"] = $idsCategorias;
        $familias = Familia::orderBy("orden")->pluck("nombre","id");
        $datos["menu"] = [];
        $tipo = 3;
        
        foreach($familias AS $i => $f) {
            $datos["menu"][$i] = [];
            $datos["menu"][$i]["activo"] = ($familia_id == $i) ? 1 : 0;
            $datos["menu"][$i]["nombre"] = $f;
            $datos["menu"][$i]["menu"] = "familia";
            $datos["menu"][$i]["tipo"] = 0;
            $datos["menu"][$i]["modelos"] = [];
            foreach($datos["modelos"] AS $ii => $m) {
                $datos["menu"][$i]["modelos"][$ii] = [];
                $datos["menu"][$i]["modelos"][$ii]["activo"] = ($ii == $modelo_id) ? 1 : 0;
                $datos["menu"][$i]["modelos"][$ii]["nombre"] = $m;
                $datos["menu"][$i]["modelos"][$ii]["menu"] = "modelo";
                $datos["menu"][$i]["modelos"][$ii]["tipo"] = 1;
                $datos["menu"][$i]["modelos"][$ii]["categorias"] = [];
                foreach($categorias AS $c) {
                    $hijos = $c->hijos->where("tipo",$tipo - 1);
                    
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]] = [];
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["nombre"] = $c["nombre"];
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["menu"] = "categoria";
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["tipo"] = 2;
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["activo"] = ($c["id"] == $categoria_id) ? 1 : 0;
                    $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"] = [];

                    if(count($hijos) > 0) {
                        foreach($hijos AS $cc) {
                            $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]] = [];
                            $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["activo"] = ($cc["id"] == $scategoria_id) ? 1 : 0;
                            $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["nombre"] = $cc["nombre"];
                            $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["menu"] = "subcategoria";
                            $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["tipo"] = 3;
                            $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["ssubcategorias"] = [];
                            $hijos = $cc->hijos->where("tipo",$tipo);
                            foreach($hijos AS $ccc) {
                                $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["ssubcategorias"][$ccc["id"]] = [];
                                $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["ssubcategorias"][$ccc["id"]]["activo"] = 0;
                                $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["ssubcategorias"][$ccc["id"]]["nombre"] = $ccc["nombre"];
                                $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["ssubcategorias"][$ccc["id"]]["menu"] = "ssubcategoria";
                                $datos["menu"][$i]["modelos"][$ii]["categorias"][$c["id"]]["subcategorias"][$cc["id"]]["ssubcategorias"][$ccc["id"]]["tipo"] = 4;
                            }
                        }
                    }
                }
            }
        }
        //dd($datos["menu"]);
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
                $menu[$c["id"]]["productos"] = ProductoCategoria::where("categoria_id",$c["id"])->paginate(15);
                foreach($menu[$c["id"]]["productos"] AS $i) {
                    $i["producto"] = $i->producto;
                    $i["imagenes"] = $i["producto"]->imagenes;
                }
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
        if($tipo == "home") {
            $datos["resultados"] = Producto::where("codigo","LIKE","%{$buscar}%")->
                                            orWhere("nombre","LIKE","%{$buscar}%")->get();
        } else
            $datos["resultados"] = Producto::where("codigo","LIKE","%{$buscar}%")->get();
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
        
        return $codigo.date("ymdHis");
    }
    public function enviarDetalle($transaccion, $cliente = 0) {
        $persona = $transaccion->persona;
        $productos = $transaccion->productos;
        if($cliente) {//ENVIO A CLIENTE LA INFO
            $empresa = Empresa::first();
            $empresa["pago"] = json_decode($empresa["pago"], true);
            /*Mail::to($persona["email"])
                ->send(new PedidoCliente($transaccion, $persona, $productos, $empresa["pago"]));*/
        } else {
            Mail::to('corzo.pabloariel@gmail.com')
                ->send(new Pedido($transaccion, $persona, $productos));
        }
    }
    public function pedido($tipo) {
        $title = "CARRITO";
        $view = "page.parts.confirmar.ok";
        //self::enviarDetalle(Transaccion::find(Cookie::get("transaccion")));
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
            
        }

    }
    public function payment($tipo) {
        
    }
    public function datos() {
        return Empresa::first();
    }
    public function familiaMenu() {
        $data = Familia::where("id","!=",5)->orderBy('orden')->pluck('nombre','id');
        $familias = [];
        foreach($data AS $i => $n) {
            $dd = Modelo::orderBy("nombre")->pluck("nombre","id");
            //$dd = Familia::find($i)->categorias->where("padre_id",0)->pluck('nombre','id');
            $familias[$i] = [];
            $familias[$i]["nombre"] = $n;
            $familias[$i]["sub"] = $dd;
        }
        
        return $familias;
    }
}