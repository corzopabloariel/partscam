<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Familia;
use App\Categoria;
class ModelosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Modelos de productos";
        $view = "adm.parts.familia.modelo";
        $familias = Familia::orderBy('orden')->pluck('nombre', 'id');
        /**
         * padre_id = 0 -> identificador de MODELO
         * != 0 -> CATEGORÍA / SUBCATEGORIA..etc
         */
        $categorias = DB::table('categorias AS c')
                            ->join('familias AS f', 'f.id', '=', 'c.familia_id')
                        ->where('c.padre_id',0)
                        ->where('c.tipo',1)
                            ->select('c.*', 'f.nombre AS familia')
                            ->orderBy('c.orden')
                        ->simplePaginate(15);
        return view('adm.distribuidor',compact('title','view','familias','categorias'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
