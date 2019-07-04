<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductosImport;

use App\Producto;
use App\Productoprecio;
use App\Productostock;
use App\Familia;
use App\Categoria;

class CargaController extends Controller
{
    public function index(Request $request) {
        set_time_limit(0);

        $archivo = $request->file("archivo");
        try {
            $path = public_path('cargas/');
            Excel::import(new ProductosImport,$request->file('archivo'));            
        } catch (Exception $e) {
            return back()->withErrors(['mssg' => "Ocurrió un error"]);
        }
        return back()->withSuccess(['mssg' => "Carga finalizada"]);
    }
}
