<?php

namespace App\Exports;

use App\Models\Ventas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VentasExport implements FromView, ShouldAutoSize
{
    public $contenedor, $fechai, $fechaf;

    public function __construct($contenedor,$fecInicio, $fecFin)
    {
        $this->contenedor = $contenedor;
        $this->fechai = $fecInicio;
        $this->fechaf = $fecFin;
    }

    public function view(): View
    {
        return view('venta.reportes.excel.ventas', [
            "contenedor" => $this->contenedor,
            "fechai" => $this->fechai,
            "fechaf" => $this->fechaf,
        ]);
    }
}
