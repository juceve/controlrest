<?php

namespace App\Http\Livewire\Menu;

use App\Models\Detalleevento;
use App\Models\Detallemenu;
use App\Models\Evento;
use App\Models\Item;
use App\Models\Menu;
use App\Models\Tipomenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Events extends Component
{
    public $menus, $menu_id,  $fecha, $idEvento = 0, $arrMenus = array(),$tipomenus;

    public function mount()
    {
        $this->menus = Menu::where('sucursale_id',Auth::user()->sucursale_id)->get();
        $this->tipomenus = Tipomenu::all();
    }

    public function render()
    {
        return view('livewire.menu.events')->extends('layouts.app');
    }

    public function updatedFecha(){
        $eventosFecha = Evento::where('fecha',$this->fecha)->get();
        
        if($eventosFecha->count()>0){
            foreach ($eventosFecha as $event) {
                
                $detalles = $event->detalleeventos;
                $this->idEvento = $event->id;
                foreach ($detalles as $detalle) {
                    $this->arrMenus[] = array("id" => $detalle->menu->id, "nombre" => $detalle->menu->nombre, "descripcion" => $detalle->menu->descripcion, "tipomenu" => $detalle->menu->tipomenu->nombre);
                }
                
            }
            $this->emit('onDelete');
        }
    }

    public function cancelar()
    {
        $this->reset('idEvento', 'arrMenus');
    }

    public function unsetArray($i)
    {
        unset($this->arrMenus[$i]);
        $this->arrMenus = array_values($this->arrMenus);
    }

    public function save()
    {
        if (count($this->arrMenus) > 0) {
            DB::beginTransaction();
            try {
                if ($this->idEvento == 0) {
                    $fechaSegundos = strtotime($this->fecha);
                    $semana = date('W', $fechaSegundos);
                    $evento = Evento::create([
                        'fecha' => $this->fecha,
                        'semana' => $semana . '-' . substr($this->fecha, 0, 4),
                        'user_id' => Auth::user()->id,
                        'sucursale_id' => Auth::user()->sucursale_id,
                    ]);
                    $this->idEvento = $evento->id;
                } else {
                    Detalleevento::where('evento_id', $this->idEvento)->delete();
                }

                foreach ($this->arrMenus as $menu) {
                    $detalleEvento = Detalleevento::create([
                        'evento_id' => $this->idEvento,
                        'menu_id' => $menu['id'],
                        'tipo' => $menu['tipomenu']
                    ]);
                }

                DB::commit();
                $this->reset('idEvento');
                return redirect()->route('programarmenu')
                    ->with('success', 'Evento guardado correctamente.');
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->reset('idEvento');
                $this->emit('error',$th->getMessage());
                // $this->emit('error', 'No se registro el Evento.');
            }
        } else {
            $this->emit('error', 'Debe seleccionar al menos 1 MENU para este Evento.');
        }
    }

    public function seleccionarMenu(Menu $menu)
    {
        $this->arrMenus[] = array("id" => $menu->id, "nombre" => $menu->nombre, "descripcion" => $menu->descripcion, "tipomenu" => $menu->tipomenu->nombre);
    }

    public function destroy()
    {
        DB::beginTransaction();
        try {
            Detalleevento::where('evento_id', $this->idEvento)->delete();
            Evento::find($this->idEvento)->delete();
            DB::commit();
            return redirect()->route('programarmenu')
                ->with('success', 'Evento eliminado correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', 'No se elmino el Evento.');
        }
    }
}
