<?php

namespace App\Http\Livewire\Reportes;

use App\Models\Pago;
use App\Models\Tipopago;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;


class Rpagocreditos extends Component
{
    public $fechaI = "", $fechaF = "", $pagos = null;

    public function render()
    {
        $this->emit('datatableRender');
        return view('livewire.reportes.rpagocreditos')->extends('layouts.app');
    }

    public function generar()
    {
        $this->pagos = Pago::where([
            ["fecha", '>=', $this->fechaI],
            ["fecha", '<=', $this->fechaF],
            ['tipoinicial', 'CREDITO'],
            ['estadopago_id', 2]
        ])
            ->orderBy('tipopago', 'ASC')
            ->get();
    }

    public function pdf()
    {
        $pagos = $this->pagos;
        $fecInicio = $this->fechaI;
        $fecFin = $this->fechaF;
        $tipopagos = Tipopago::all();

        $pdf = Pdf::loadView('reports.rpagocreditos', compact('pagos', 'fecInicio', 'fecFin','tipopagos'))->output();
        
        return response()->streamDownload(
            fn () => print($pdf),
            "Rpt_Pago_Creditos_" . $this->fechaI . "_" . $this->fechaF . ".pdf"
        );
    }
}
