<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Slider;
class SliderController extends Controller
{
    public $idioma = "es";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($seccion)
    {
        $title = "Slider: " . strtoupper($seccion);
        $view = "adm.parts.slider.index";
        $sliders = Slider::where('seccion',$seccion)->orderBy('orden')->get();
        foreach($sliders AS $s)
            $s["texto"] = json_decode($s["texto"], true)[$this->idioma];
        
        return view('adm.distribuidor',compact('title','view','sliders','seccion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $seccion, $data = null)
    {
        $datosRequest = $request->all();
        $ARR_data = [];
        $ARR_data["image"] = null;
        $ARR_data["seccion"] = $seccion;
        $ARR_data["orden"] = $datosRequest["orden"];
        $ARR_data["texto"] = [];
        $ARR_data["texto"][$this->idioma] = $datosRequest["texto_{$this->idioma}"];
        $ARR_data["texto"] = json_encode($ARR_data["texto"]);
        
        $file = $request->file("image");
        
        if(!is_null($data))
            $ARR_data["image"] = $data["image"];
        if(!is_null($file)) {
            $path = public_path('images/sliders/')."{$seccion}";
            if (!file_exists($path))
                mkdir($path, 0777, true);
            $imageName = time()."_{$seccion}.".$file->getClientOriginalExtension();
            
            $file->move($path, $imageName);
            $ARR_data["image"] = "images/sliders/{$seccion}/{$imageName}";
            
            if(!is_null($data)) {
                $filename = public_path() . "/" . $data["image"];
                if (file_exists($filename))
                    unlink($filename);
            }
        }
        
        if(is_null($data))
            Slider::create($ARR_data);
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
        $s = Slider::find($id);
        $s["texto"] = json_decode($s["texto"], true)[$this->idioma];
        return $s;
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
        self::store($request,$data["seccion"],$data);
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
        $data = self::edit($id);
        $filename = public_path() . "/{$data["image"]}";
        if (file_exists($filename))
            unlink($filename);

        Slider::destroy($id);
        return 1;
    }
}
