<?php

namespace App\Http\Livewire\Menu;

use App\Models\Cartmenu;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Resumen extends Component
{
    public $cartMenu,$countCart;

    public function mount()
    {
        $this->cartMenu = Cartmenu::where('session', session('idCarrito'))->orderBy('fecha', 'asc')->get();
        $this->countCart = $this->cartMenu->count();
    }
    public function render()
    {
        return view('livewire.menu.resumen')->extends('layouts.web2');
    }
    protected $listeners = ['remCart'];

    public function remCart($id)
    {
        DB::beginTransaction();
        try {
            $cart = Cartmenu::find($id);
            $fecha = $cart->fecha;
            $menu_id = $cart->menu_id;
            $cart->delete();
            $this->cartMenu = Cartmenu::where('session', session('idCarrito'))
                ->orderBy('fecha', 'asc')
                ->get();
            $this->countCart = $this->cartMenu->count();
            $this->emit('actCon', $this->countCart);
            // $this->resetForms();
            DB::commit();
            $this->emit('enableButton', $fecha . '-' . $menu_id);
            $this->emit('alertDelete');
            if ($this->countCart == 0) {
                return redirect()->route('menusemanal');
            }
        } catch (\Throwable $th) {
            $this->emit('alertError');
            DB::rollback();
        }
    }

    public function next(){
        if($this->countCart > 0){
            return redirect()->route('formapago','t');
        }else{
            $this->emit('alertWarning','Debe seleccionar algun producto');
        }
    }
}
