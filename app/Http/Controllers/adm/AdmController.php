<?php

namespace App\Http\Controllers\adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdmController extends Controller
{
    public function index() {
        $title = "Administración";
        $view = "adm.parts.index";
        return view('adm.distribuidor',compact('title', 'view'));
    }
    /** */
    public function logout() {
        Auth::logout();
    	return redirect()->to('/adm');
    }
}
