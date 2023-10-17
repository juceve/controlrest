<?php

namespace App\Http\Livewire;

use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Pruebas extends Component
{
    public function render()
    {
        $misVentas = misVentasHoy(2);
        dd($misVentas);

        return view('livewire.pruebas');
    }
}
