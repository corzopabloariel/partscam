<?php

namespace App\Http\Controllers\page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\Contacto;
use App\Mail\Consultar;
use App\Producto;
class FormController extends Controller
{
    public function index(Request $request, $seccion) {
        return self::$seccion($request);
    }

    public function contacto(Request $request) {
        $data = $request->all();
        
        if(!isset($data["terminos"]))
            return back()->withInput($data)->withErrors(['mssg' => "Acepte los términos y condiciones"]);
        
        Mail::to('corzo.pabloariel@gmail.com')->send(new Contacto($data["nombre"], $data["apellido"], $data["telefono"], $data["email"], $data["mensaje"], $data["marca"], $data["modelo"], $data["anio"]));
        Mail::to('franco_spagnoletti@hotmail.com')->send(new Contacto($data["nombre"], $data["apellido"], $data["telefono"], $data["email"], $data["mensaje"], $data["marca"], $data["modelo"], $data["anio"]));
        
        if (count(Mail::failures()) > 0)
            return back()->withErrors(['mssg' => "Ha ocurrido un error al enviar el correo"]);
        else
            return back()->withSuccess(['mssg' => "Correo enviado correctamente"]);
    }

    public function consultar(Request $request) {
        $data = $request->all();
        $producto = Producto::find($data["productoIDinput"]);
        $producto["familia"] = $producto->familia["nombre"];
        Mail::to('corzo.pabloariel@gmail.com')
            ->send(new Consultar($data["nombre"], $data["email"], $data["consulta"], $data["productoCantidad"], $producto));
        Mail::to('franco_spagnoletti@hotmail.com')
            ->send(new Consultar($data["nombre"], $data["email"], $data["consulta"], $data["productoCantidad"], $producto));
        
        if (count(Mail::failures()) > 0)
            return back()->withErrors(['mssg' => "Ha ocurrido un error al enviar la consulta"]);
        else
            return back()->withSuccess(['mssg' => "Consulta enviada correctamente"]);
    }
}
