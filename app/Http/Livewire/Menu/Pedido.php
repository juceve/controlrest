<?php

namespace App\Http\Livewire\Menu;

use App\Models\Cartmenu;
use App\Models\Evento;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Pedido extends Component
{
    public $semana,$cartMenu,$countCart, $cart;
    public function mount()
    {
        $this->cartMenu = Cartmenu::where('session', session('idCarrito'))->orderBy('fecha', 'asc')->get();
        $this->countCart = $this->cartMenu->count();
    }
    public function render()
    {
        $hoy = now();
        $hoyL = "";
        $fechaSegundos = strtotime($hoy);
        switch (date('w', $fechaSegundos)) {
            case 0:
                $hoyL = "Domingo";
                break;
            case 1:
                $hoyL = "Lunes";
                break;
            case 2:
                $hoyL = "Martes";
                break;
            case 3:
                $hoyL = "Miercoles";
                break;
            case 4:
                $hoyL = "Jueves";
                break;
            case 5:
                $hoyL = "Viernes";
                break;
            case 6:
                $hoyL = "Sabado";
                break;
        }
        $semana = date('W', $fechaSegundos) . '-' . date('Y');
        if ($hoyL == 'Sabado') {
            $semana = date('W', $fechaSegundos) + 1;
            $semana = str_pad($semana, 2, "0", STR_PAD_LEFT) . '-' . date('Y');
            $this->semana = $semana;
        }
        $fechaSegundosViernes = strtotime($hoy . ' 21:00:00:00');
        if (($hoyL == 'Viernes') && ($fechaSegundos > $fechaSegundosViernes)) {
            $semana = date('W', $fechaSegundos) + 1;
            $semana = str_pad($semana, 2, "0", STR_PAD_LEFT) . '-' . date('Y');
            $this->semana = $semana;
        }

        $eventos = Evento::where('semana', $semana)->get();
        return view('livewire.menu.pedido',compact('eventos'))->extends('layouts.web2');
    }   

    public function addCart($menu_id, $fecha)
    {

        DB::beginTransaction();
        try {
            $this->cart = Cartmenu::create([
                "session" => session('idCarrito'),
                "fecha" => $fecha,
                "menu_id" => $menu_id,
            ]);
            $this->cartMenu = Cartmenu::where('session', session('idCarrito'))
                ->orderBy('fecha', 'asc')
                ->get();
            $this->countCart = $this->cartMenu->count();
            $this->emit('actCon', $this->countCart);
            // $this->resetForms();
            // $this->emit('mostrarProcesoPago');
            DB::commit();
            $this->emit('alertSuccess');
            $this->emit('selectButton', $fecha . '-' . $menu_id);
        } catch (\Throwable $th) {
            DB::rollback();
            $this->emit('alertError');
        }
    }

    
}
