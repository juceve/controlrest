<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Ventasgestion extends Component
{
    public $resultado = "", $resultado2 = "", $totalBs = 0, $totalBs2 = 0, $colores = [];

    public function mount()
    {
        $this->colores = [
            'rgba(255, 99, 132, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(255, 206, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(153, 102, 255, 0.5)',
            'rgba(255, 159, 64, 0.5)'
        ];
    }
    public function render()
    {
        $resultado = ventasGestion(date('Y'));
        $tabla = [];
        $data = "";
        foreach ($resultado as $item) {
            $this->totalBs += $item->importe;
            $data .= $item->producto . '|' . $item->cantidad . '|' . $item->importe . "^";
            $tabla[] = array($item->producto, $item->cantidad, $item->importe);
        }

        $this->resultado = substr($data, 0, -1);

        $resultado2 = ventasGestion2(date("Y"));
        $tabla2 = [];
        $data2 = "";
        foreach ($resultado2 as $item) {
            $this->totalBs2 += $item->importe;
            $data2 .= $item->tipopago . '|' . $item->cantidad . '|' . $item->importe . "^";
            $tabla2[] = array($item->tipopago, $item->cantidad, $item->importe);
        }
        $this->resultado2 = substr($data2, 0, -1);

        return view('livewire.ventasgestion', compact('tabla', 'tabla2'));
    }
}
