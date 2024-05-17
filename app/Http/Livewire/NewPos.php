<?php

namespace App\Http\Livewire;

use App\Models\Evento;
use App\Models\Menu;
use App\Models\Preciomenu;
use App\Models\Tipomenu;
use App\Models\Ventasconfig;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NewPos extends Component
{
    public  $itemSel = "", $cantidad = 1, $cart = [], $productos, $total = 0, $descuento = false;

    public function updatedDescuento()
    {
        $this->calcularTotal();
    }

    public function mount()
    {
        $evento = Evento::where('fecha', date('Y-m-d'))
            ->where('sucursale_id', Auth::user()->sucursale_id)
            ->first();
        $i = 0;
        $configPOS = Ventasconfig::where('sucursale_id', Auth::user()->sucursale_id)->first();
        if ($evento) {
            foreach ($evento->detalleeventos as $detalle) {
                $preciomenu = Preciomenu::where('tipomenu_id', $detalle->menu->tipomenu_id)
                    ->where('nivelcurso_id', $configPOS->nivelcurso_id)
                    ->first();
                if ($preciomenu) {
                    $this->productos[] = array($detalle->menu->id, $detalle->menu->nombre, $preciomenu->precio, 0, 0, $i, $detalle->menu->descripcion, $preciomenu->preciopm, $preciomenu->tipomenu->nombre);
                }
                $i++;
            }
        }
    }


    public function render()
    {

        return view('livewire.new-pos')->extends('layouts.app');
    }

    public function calcularTotal()
    {
        $this->reset('total');
        foreach ($this->cart as $item) {
            if ($this->descuento) {
                $this->total += $item[4] * $item[3];
            } else {
                $this->total += $item[2] * $item[3];
            }
        }
    }

    public function addCart()
    {
        foreach ($this->productos as $item) {
            if ($item[0] == $this->itemSel) {
                $this->cart[] = array($item[0], $item[1], $item[2], $this->cantidad, $item[7]);
                break;
            }
        }
        $this->calcularTotal();
        $this->resetPedido();
    }

    public function resetPedido()
    {
        $this->reset('itemSel', 'cantidad');
    }

    public function eliminar($i)
    {
        unset($this->cart[$i]);
        $this->cart = array_values($this->cart);
        $this->calcularTotal();
    }
}
