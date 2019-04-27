<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Producto;
use App\Productooferta;
class OfertaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Ofertas de productos";
        $view = "adm.parts.familia.oferta";
        $productos = Producto::orderBy("orden")->get();//
        foreach($productos AS $p) {
            $precio = $p->precio;
            $p["precio"] = $precio["precio"];
            $precio = number_format($precio["precio"],2,",",".");
            $p["nombre"] = "{$p["nombre"]} - $ {$precio}";
        }
        $precios = $productos->pluck('precio','id');
        
        $productos = $productos->pluck('nombre', 'id');
        $ofertasActivas = Productooferta::pluck('id','producto_id');
        $ofertas = Productooferta::simplePaginate(15);
        foreach($ofertas AS $o) {
            $porc = number_format($o["porcentaje"],2,",",".");
            $precioAnterior = number_format($o->producto->precio["precio"],2,",",".");
            $precioNuevo = number_format($o["precio"],2,",",".");
            $o["precio"] = "<strike>$ {$precioAnterior}</strike><br/>$ {$precioNuevo}";
            $o["nombre"] = "{$o->producto->familia["nombre"]}<br/>{$o->producto["nombre"]}";
            $o["porcentaje"] = "{$porc} %";
        }
        
        return view('adm.distribuidor',compact('title','view','ofertas','productos','precios','ofertasActivas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $data = null)
    {
        $dataRequest = $request->all();
        $dataRequest["precio"] = str_replace(".","",$dataRequest["precio"]);
        $dataRequest["precio"] = str_replace(",",".",$dataRequest["precio"]);
        $dataRequest["precio"] = str_replace("$ ","",$dataRequest["precio"]);
        
        $dataRequest["porcentaje"] = str_replace(" %","",$dataRequest["porcentaje"]);
        $dataRequest["porcentaje"] = str_replace(".","",$dataRequest["porcentaje"]);
        $dataRequest["porcentaje"] = str_replace(",",".",$dataRequest["porcentaje"]);

        $ARR_data = [];
        $ARR_data["producto_id"] = $dataRequest["producto"];
        $ARR_data["precio"] = $dataRequest["precio"];
        $ARR_data["porcentaje"] = $dataRequest["porcentaje"];
        $ARR_data["orden"] = $dataRequest["orden"];
        
        if(is_null($data)) 
            Productooferta::create($ARR_data);
        else {
            $data->fill($ARR_data);
            $data->save();
        }
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Productooferta::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = self::edit($id);
        self::store($request,$data);
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
        Productooferta::destroy($id);
        return 1;
    }
}
