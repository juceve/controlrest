<?php

namespace App\Http\Livewire\Reportes;

use App\Models\Venta;
use Livewire\Component;

class RptVentas extends Component
{
    public $fechaI = "", $fechaF = "";

    public function render()
    {
        $ventas = null;
        if ($this->fechaI!="" && $this->fechaF != "") {
            $this->emit('loading');

            $ventas = Venta::whereBetween('fecha', [$this->fechaI, $this->fechaF])->where('estado',1)->get();    
            
            $ventas_pr = $ventas->where('estadopago_id',2);
            $ventas_pp = $ventas->where('estadopago_id',1);
            dd($ventas_pp->sum('importe'));
            $this->emit('unLoading');
        }
        
        return view('livewire.reportes.rpt-ventas',compact('ventas'))->extends('layouts.app');
    }
}
