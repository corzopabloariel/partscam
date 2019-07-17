<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Servicio;
class ServicioController extends Controller
{
    public $idioma = ["es","en","it"];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $servicios = Servicio::orderBy('orden')->get();
        $view = "adm.parts.servicio";
        $title = "Servicios";

        return view('adm.distribuidor',compact('view','title','servicios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $data = null)
    {
        $OBJ = [];
        $datosRequest = $request->all();
        
        try {
            $datosRequest["ATRIBUTOS"] = json_decode( $datosRequest["ATRIBUTOS"] , true );
            if( isset( $datosRequest["JSON"] ) )
                $datosRequest["JSON"] = json_decode( $datosRequest["JSON"] , true );
            foreach($datosRequest["ATRIBUTOS"] AS $nombre => $tipo) {
                $OBJ[$nombre] = null;
                if($tipo == "TP_FILE") {
                    if(!is_null($data))
                        $OBJ[$nombre] = $data[$nombre];
                    $file = $request->file($nombre);
                    if(!is_null($file)) {
                        $path = public_path('images/servicios/');
                        if (!file_exists($path))
                            mkdir($path, 0777, true);
                        $imageName = time().".".$file->getClientOriginalExtension();
                        
                        $file->move($path, $imageName);
                        $OBJ[$nombre] = "images/servicios/{$imageName}";
                        
                        if(!is_null($data)) {
                            $filename = public_path() . "/" . $data[$nombre];
                            if(!empty($data[$nombre])) {
                                if (file_exists($filename))
                                    unlink($filename);
                            }
                        }
                    }
                } else {
                    $flag = true;
                    if(isset($datosRequest["JSON"])) {
                        if(isset($datosRequest["JSON"][$nombre])) {
                            $flag = false;
                            $OBJ[$nombre] = [];
                            for($i = 0 ; $i < count($this->idioma) ; $i++) {
                                $OBJ[$nombre][$this->idioma[$i]] = null;
                                if(isset($datosRequest["{$nombre}_{$this->idioma[$i]}"]))
                                    $OBJ[$nombre][$this->idioma[$i]] = $datosRequest["{$nombre}_{$this->idioma[$i]}"];
                            }
                        }
                    }
                    if($flag) {
                        if(isset($datosRequest[$nombre]))
                            $OBJ[$nombre] = $datosRequest[$nombre];
                    }
                }
            }
            if(is_null($data)) {
                Servicio::create($OBJ);
                echo 1;
            } else {
                $data->fill($OBJ);
                $data->save();
                echo 1;
            }
        } catch (\Throwable $th) {
            echo 0;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Servicio::find($id);
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
        return self::store($request,$data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = self::edit($id);
        if(!empty($data["image"])) {
            $filename = public_path() . "/{$data["image"]}";
            if (file_exists($filename))
                unlink($filename);
        }

        Servicio::destroy($id);
        return 1;
    }
}