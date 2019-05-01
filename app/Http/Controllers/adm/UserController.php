<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()["is_admin"]) {
            $title = "Usuarios";
            $view = "adm.parts.usuarios.index";

            $usuarios = User::where("id","!=",Auth::user()["id"])->get();

            return view('adm.distribuidor',compact('title','view','usuarios'));
        } else {

        }
    }
    public function datos() {
        $title = "Mis datos";
        $view = "adm.parts.usuarios.datos";

        $usuario = User::find(Auth::user()["id"]);

        return view('adm.distribuidor',compact('title','view','usuario'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $data = null)
    {
        $datosRequest = $request->all();
        $ARR_data = [];
        $ARR_data["name"] = $datosRequest["name"];
        $ARR_data["username"] = $datosRequest["username"];
        $ARR_data["password"] = null;
        $ARR_data["is_admin"] = is_null($datosRequest["is_admin"]) ? 0 : $datosRequest["is_admin"];
        
        if(is_null($data)) {
            $ARR_data["password"] = Hash::make($datosRequest["password"]);
            User::create($ARR_data);
        } else {
            $ARR_data["password"] = $data["password"];
            if(!empty($datosRequest["password"]))
                $ARR_data["password"] = Hash::make($datosRequest["password"]);
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
        return User::find($id);
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
        return self::store($request,self::edit($id));
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

        User::destroy($id);
        return 1;
    }
}
