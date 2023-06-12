<?php

namespace App\Http\Livewire\Menu;

use App\Models\Cartmenu;
use Livewire\Component;

class Carritologo extends Component
{
    public $contador = 0;

    protected $listeners = ['actualizaContador'];

    public function mount()
    {
        $this->contador = Cartmenu::where('session', session('idCarrito'))->count();
        $this->emit('cargaContador', $this->contador);
    }

    public function render()
    {
        return view('livewire.menu.carritologo');
    }

    public function actualizaContador($cantidad)
    {
        $this->contador = $cantidad;
    }
}
