<?php

namespace App\Http\Livewire\Menu;

use Livewire\Component;

class Finish extends Component
{
    public $ids;

    public function mount($id){
        $this->ids = $id;
    }

    public function render()
    {
        $id = $this->ids;
        return view('livewire.menu.finish',compact('id'))->extends('layouts.web2');
    }
}
