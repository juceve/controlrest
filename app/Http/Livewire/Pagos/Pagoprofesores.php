<?php

namespace App\Http\Livewire\Pagos;

use App\Models\Creditoprofesore;
use App\Models\Estudiante;
use App\Models\Sucursale;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Pagoprofesores extends Component
{

    public $fechainicio = "",$fechafin = "", $creditos, $resultados;

    public function render()
    {
        $profesores = Estudiante::where('esestudiante', 0)->get();
        $sucursale = Sucursale::where('id',Auth::user()->sucursale_id)->first();


        return view('livewire.pagos.pagoprofesores', compact('profesores'))->extends('layouts.app');
    }

    public function buscar()
    {
        $creditos = Creditoprofesore::where('pagado',0)->where('sucursale_id',Auth::user()->sucursale_id)->get();
        dd($creditos);
    }
}
