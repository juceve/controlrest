<?php

namespace App\Http\Controllers;

use App\Models\Cierrecaja;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function cierrecaja($id){
        
        return view('reports.cierrecaja',compact('cierre','detalles','totalpp','totalpr','totalpa'));
    }
}
