<?php

namespace App\Http\Livewire\Menu;

use App\Models\Catitem;
use App\Models\Evento;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Cartelera extends Component
{
    public $semana;
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
        // $fechaSegundosViernes = strtotime($hoy . ' 21:00:00:00');
        // if (($hoyL == 'Viernes') && ($fechaSegundos > $fechaSegundosViernes)) {
        //     $semana = date('W', $fechaSegundos) + 1;
        //     $semana = str_pad($semana, 2, "0", STR_PAD_LEFT) . '-' . date('Y');
        //     $this->semana = $semana;
        // }

        $eventos = Evento::where('semana', $semana)->get();

        ///////////////// GRILLA MENU //////////////////////////////
        $catitems = Catitem::where('id', '>', 3)->get();
        $arrXCategorias = array();
        $cartelera = array();

        $resultados = DB::table('vw_cartelera')
            ->where('semana', $semana)
            ->where('sucursale_id', 1)
            ->where('tipomenu_id', 1)
            ->select('catitem', 'fecha', 'menu', 'producto')
            ->get();

        // dd($resultados);
        foreach ($catitems as $catitem) {
            foreach ($resultados as $resultado) {
                if ($catitem->nombre == $resultado->catitem) {
                    $arrXCategorias[] = array("fecha" => $resultado->fecha, "catitem" => $resultado->catitem, "producto" => $resultado->producto);
                }
            }
        }

        $resultados2 = Menu::where('sucursale_id', 1)->where('tipomenu_id', 3)->get();

        return view('livewire.menu.cartelera', compact('eventos', 'resultados2', 'arrXCategorias'));
    }
}
