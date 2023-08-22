<?php

namespace App\Http\Livewire\Reportes;

use App\Models\Creditoprofesore;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Entregaprofesores extends Component
{
    public $selFecha = "", $tabla1 = array();

    public function render()
    {
        return view('livewire.reportes.entregaprofesores')->extends('layouts.app');
    }

    public function generar(){
        $creditos = Creditoprofesore::where('fecha', $this->selFecha)->where('sucursale_id', Auth::user()->sucursale_id)->get();
        $this->tabla1 = $creditos;
    }

    public function pdf()
    {
        $tabla1 = $this->tabla1;
        $fecha = $this->selFecha;
        $pdf = Pdf::loadView('reports.entregasprofesores', compact('tabla1', 'fecha'))->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "RPT_Entregas_Profesores_" . $this->selFecha . ".pdf"
        );
    }
}
