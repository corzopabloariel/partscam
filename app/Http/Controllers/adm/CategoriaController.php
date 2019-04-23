<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Familia;
use App\Categoria;
class CategoriaController extends Controller
{
    public function rec_padre($data) {
        if(empty($data->padre))
            return $data["nombre"];
        else
            return self::rec_padre($data->padre) . ", {$data["nombre"]}";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Categoría de productos";
        $view = "adm.parts.familia.categoria";
        $familias = Familia::orderBy('orden')->pluck('nombre', 'id');
        $categorias = Categoria::orderBy("padre_id");
        $catTOTAL = $categorias->get();
        $categorias = $categorias->simplePaginate(15);
        foreach($catTOTAL AS $c) {
            $c["familia"] = $c->familia;
            $c["nombre"] = self::rec_padre($c);
        }   
        $OP_categorias = $catTOTAL->pluck('nombre', 'id');
        
        return view('adm.distribuidor',compact('title','view','familias','categorias','OP_categorias'));
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
    public function store(Request $request)
    {
        $dataRequest = $request->all();
        dd($dataRequest);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function familia_categoria($id)
    {
        $familia = Familia::find($id);

        return $familia->categorias;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
