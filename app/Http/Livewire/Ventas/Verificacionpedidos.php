<?php

namespace App\Http\Livewire\Ventas;

use App\Models\Tipopago;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Verificacionpedidos extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $filtroBusqueda = "", $busqueda = "";

    public function updatedFiltroBusqueda(){
        $this->resetPage();
    }

    public function updatedBusqueda(){
        $this->resetPage();
    }

    public function render()
    {
       
        $tipopagos = Tipopago::all();
        if ($this->filtroBusqueda == "") {
            $ventas = Venta::where('estadopago_id',1)->where('cliente','LIKE','%'.$this->busqueda.'%')->paginate(5);
        }else{
            $ventas = Venta::where('estadopago_id',1)->where('cliente','LIKE','%'.$this->busqueda.'%')->where('tipopago_id',$this->filtroBusqueda)->paginate(5);
        }
        return view('livewire.ventas.verificacionpedidos',compact('ventas','tipopagos'))->extends('layouts.app');
    }
}
