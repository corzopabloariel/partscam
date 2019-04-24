<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Marca;
class AdmController extends Controller
{
    public function index() {
        $title = "Administración";
        $view = "adm.parts.index";
        return view('adm.distribuidor',compact('title', 'view'));
    }
    public function marcas() {
        $title = "Marcas";
        $view = "adm.parts.marca.index";
        $marcas = Marca::orderBy('orden')->get();
        return view('adm.distribuidor',compact('title', 'view','marcas'));
    }
    public function store(Request $request, $data = null) {
        $dataRequest = $request->all();
        $ARR_data = [];
        $ARR_data["orden"] = $dataRequest["orden"];
        $ARR_data["nombre"] = $dataRequest["nombre"];
        $ARR_data["image"] = null;

        $file = $request->file("image");
        
        if(!is_null($data))
            $ARR_data["image"] = $data["image"];
        if(!is_null($file)) {
            $path = public_path('images/marcas/');
            if (!file_exists($path))
                mkdir($path, 0777, true);
            $imageName = time()."_marca.".$file->getClientOriginalExtension();
            
            $file->move($path, $imageName);
            $ARR_data["image"] = "images/marcas/{$imageName}";
            
            if(!is_null($data)) {
                $filename = public_path() . "/" . $data["image"];
                if (file_exists($filename))
                    unlink($filename);
            }
        }
        if(is_null($data))
            Marca::create($ARR_data);
        else {
            $data->fill($ARR_data);
            $data->save();
        }
        return back();
    }
    public function edit($id) {
        return Marca::find($id);
    }
    public function update(Request $request, $id) {
        $data = self::edit($id);
        self::store($request,$data);
        return back();
    }

    public function destroy($id) {
        $data = self::edit($id);
        $filename = public_path() . "/{$data["image"]}";
        if (file_exists($filename))
            unlink($filename);

        Marca::destroy($id);
        return 1;
    }
    /** */
    public function logout() {
        Auth::logout();
    	return redirect()->to('/adm');
    }
}
