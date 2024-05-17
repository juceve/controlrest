<?php

namespace App\Http\Livewire\Menu;

use App\Models\Catitem;
use App\Models\Detallemenu;
use App\Models\Item;
use App\Models\Menu;
use App\Models\Tipomenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Elaborarmenu extends Component
{

    public $nombre, $tipomenu_id, $descripcion, $menu, $menu_id, $dup;
    public $item_id, $items, $tipos, $itemsMenu, $categorias;

    protected $listeners = ['agregar', 'render'];

    public function mount($id, $dup)
    {
        $this->menu_id = $id;
        $this->dup = $dup;
        if ($this->menu_id > 0) {
            $this->menu = Menu::find($this->menu_id);
            if ($this->dup == 0) {
                $this->nombre = $this->menu->nombre;
                $this->tipomenu_id = $this->menu->tipomenu_id;
                $this->descripcion = $this->menu->descripcion;
            }
            $itemsMenu = Detallemenu::where('menu_id', $this->menu_id)->get();
            foreach ($itemsMenu as $item) {
                $row = array("categoria" => $item->catitem->nombre, "producto" => $item->item->nombre, "id" => $item->item_id);
                $this->itemsMenu[] = $row;
            }
        }
        $this->tipos = Tipomenu::where('status', 1)->pluck('nombre', 'id');
        $this->items = Item::all();
        $this->categorias = Catitem::all();
    }

    protected $rules = [
        'nombre' => 'required|unique:menus',
        'tipomenu_id' => 'required',
    ];

    public function render()
    {
        $menu = new Menu();

        return view('livewire.menu.elaborarmenu', compact('menu'))->extends('layouts.app');
    }

    public function save()
    {
        $this->validate();
        if (is_null($this->itemsMenu)) {
            $this->emit('warning', 'Debe seleccionar al menos 1 producto.');
        } else {

            DB::beginTransaction();
            try {
                // SE CREA EL MENU
                $menu = Menu::create([
                    "nombre" => $this->nombre,
                    "tipomenu_id" => $this->tipomenu_id,
                    "descripcion" => $this->descripcion,
                    "sucursale_id" => Auth::user()->sucursale_id,
                ]);

                // SE CARGA EL DETALLEMENU
                $descripcion = "";
                foreach ($this->itemsMenu as $item) {
                    $itemMenu = Item::find($item['id']);
                    Detallemenu::create([
                        "menu_id" => $menu->id,
                        "item_id" => $itemMenu->id,
                        "catitem_id" => $itemMenu->catitem_id,
                    ]);
                    if ($descripcion == "") {
                        $descripcion = $itemMenu->nombre;
                    } else {
                        $descripcion = $descripcion . ' - ' . $itemMenu->nombre;
                    }
                }
                $menu->descripcion = $descripcion;
                $menu->save();
                DB::commit();
                return redirect()->route('menus.index')->with('success', 'Menu elaborado con exito.');
            } catch (\Throwable $th) {
                DB::rollback();
                // $this->emit('error', $th->getMessage());
                $this->emit('error', 'No se genero ningun registro');
            }
        }
    }

    public function update()
    {
        // $this->validate();
        if (is_null($this->itemsMenu)) {
            $this->emit('warning', 'Debe seleccionar al menos 1 producto.');
        } else {

            DB::beginTransaction();
            try {
                // SE ACTUALIZA EL MENU
                $this->menu->update([
                    "nombre" => $this->nombre,
                    "tipomenu_id" => $this->tipomenu_id,
                    "descripcion" => "",
                ]);

                // SE CARGA EL DETALLEMENU
                $descripcion = "";
                Detallemenu::where('menu_id', $this->menu_id)->delete();
                foreach ($this->itemsMenu as $item) {
                    $itemMenu = Item::find($item['id']);
                    Detallemenu::create([
                        "menu_id" => $this->menu->id,
                        "item_id" => $itemMenu->id,
                        "catitem_id" => $itemMenu->catitem_id,
                    ]);
                    if ($descripcion == "") {
                        $descripcion = $itemMenu->nombre;
                    } else {
                        $descripcion = $descripcion . ' - ' . $itemMenu->nombre;
                    }
                }
                $this->menu->descripcion = $descripcion;
                $this->menu->save();
                DB::commit();
                return redirect()->route('menus.index')->with('success', 'Menu editado con exito.');
            } catch (\Throwable $th) {
                DB::rollback();
                $this->emit('error', 'No se edito ningun registro');
            }
        }
    }

    public function agregar(Item $item)
    {
        $row = array("categoria" => $item->catitem->nombre, "producto" => $item->nombre, "id" => $item->id);
        $this->itemsMenu[] = $row;
    }

    public function eliminar($indice)
    {
        array_splice($this->itemsMenu, $indice, 1);
    }
}
