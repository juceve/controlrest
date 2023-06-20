<?php

namespace App\Http\Livewire\Pedidos;

use App\Models\Bonofecha;
use App\Models\Detallelonchera;
use App\Models\Estudiante;
use App\Models\Licencia;
use App\Models\Lonchera;
use DateTime;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Ppersonales extends Component
{
    public $estudiante = null, $selID = null, $selFecha = "", $bono;

    protected $listeners = ['licencia', 'reprogramar'];

    public function mount($estudiante_id)
    {
        $this->estudiante = Estudiante::find($estudiante_id);
        $this->fechaI = date('Y-m-d');
        $this->fechaF = date('Y-m-d');
    }

    public function render()
    {
        $sql = "SELECT dl.id, dl.lonchera_id, dl.fecha,  tp.id tipomenu_id, tp.nombre tipo, IF(li.fecha,1,0) licencia FROM detalleloncheras dl
        INNER JOIN loncheras l on l.id = dl.lonchera_id
        LEFT JOIN tipomenus tp on tp.id = dl.tipomenu_id
        LEFT JOIN   licencias li on li.estudiante_id = l.estudiante_id AND li.fecha = dl.fecha				
        WHERE l.estudiante_id = " . $this->estudiante->id . "
        AND l.habilitado = 1
        AND l.estado = 1
        AND dl.estado = 1
        AND dl.entregado = 0";
        $this->emit('datatableRender');
        $pedidos = DB::select($sql);

        $bonofecha = Bonofecha::where('fechainicio', '<=', date('Y-m-d'))->where('fechafin', '>=', date('Y-m-d'))->where('estudiante_id', $this->estudiante->id)->first();
        $licencias = null;
        if ($bonofecha) {
            $licencias = Licencia::where('estudiante_id', $this->estudiante->id)->where('fecha', '>=', $bonofecha->fechainicio)->where('fecha', '<=', $bonofecha->fechafin)->get();
            $this->bono = $bonofecha->id;
        }
        $this->emit('datatableRender');

        return view('livewire.pedidos.ppersonales', compact('pedidos', 'bonofecha', 'licencias'))->extends('layouts.app');
    }

    public function licencia($detallelonchera_id)
    {
        
        DB::beginTransaction();
        try {
            $detalle = Detallelonchera::find($detallelonchera_id);

            $licencia = Licencia::create([
                "estudiante_id" => $this->estudiante->id,
                "fecha" => $detalle->fecha,
                "detallelonchera_id" => $detallelonchera_id,
                "tipomenu_id" => $detalle->tipomenu_id,
            ]);
            
            $this->reprogramacionautomatica($detallelonchera_id);
            DB::commit();
            $this->emit('success', 'Licencia aplicada correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', 'Ha ocurrido un error, no se hizo cambios.');
        }
    }

    public function resetear()
    {
        $this->reset(['selID', 'selFecha']);
    }

    public function reprogramar()
    {
        if ($this->selID && $this->selFecha) {
            DB::beginTransaction();
            try {
                $detallelonchera = Detallelonchera::find($this->selID);
                if ($detallelonchera->fecha == $this->selFecha) {
                    $licencia = Licencia::where('detallelonchera_id', $this->selID)->first();
                    $licencia->delete();
                } else {
                    $detallelonchera->fecha = $this->selFecha;
                    $detallelonchera->save();
                }

                DB::commit();
                $this->reset(['selID', 'selFecha']);
                $this->emit('hideModal');
                $this->emit('success', 'Pedido reprogramado correctamente.');
            } catch (\Throwable $th) {
                DB::rollback();
                // $this->emit('error','Ha ocurrido un error, no se registro el cambio.');
                $this->emit('error', $th->getMessage());
            }
        }
    }

    public $fechaI = "", $fechaF = "";

    public function licenciaBono()
    {
        DB::beginTransaction();
        try {

            $fechaActual = new DateTime($this->fechaI);
            $fechaFinalDT = new DateTime($this->fechaF);
            $c = 0;

            $bono = Bonofecha::find($this->bono);

            while ($fechaActual <=  $fechaFinalDT) {
                $diaSemana = $fechaActual->format('N');
                if ($diaSemana >= 1 && $diaSemana <= 5) {
                    if (!esFeriado(date_format($fechaActual, 'Y-m-d'))) {
                        $licencia = Licencia::create([
                            "estudiante_id" => $this->estudiante->id,
                            "fecha" => $fechaActual,
                            "tipomenu_id" => $bono->tipomenu_id,
                        ]);
                        $c++;
                    }
                }
                $fechaActual->modify('+1 day');
            }
            $i = 0;
            
            $fechafin = new DateTime($bono->fechafin);
            while ($i < $c) {

                $fechafin->modify('+1 day');
                $dia = $fechafin->format('N');
                if ($dia >= 1 && $dia <= 5) {
                    if (!esFeriado(date_format($fechafin, 'Y-m-d'))) {
                        $bono->fechafin = date_format($fechafin, 'Y-m-d');
                        $bono->save();
                        $i++;
                    }
                }
            }
            DB::commit();
            $this->emit('hideModal2');
            $this->emit('success', 'Licencia(s) registrada(s) correctamente.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', $th->getMessage());
        }
    }

    public function reprogramacionautomatica($detallelonchera_id)
    {
        // $sql = "SELECT dl.* FROM loncheras l
        // INNER JOIN detalleloncheras dl on dl.lonchera_id = l.id
        // WHERE l.estudiante_id = " . $this->estudiante->id . "
        // AND l.habilitado = 1
        // AND l.estado = 1
        // AND dl.estado = 1
        // ORDER BY fecha DESC";

        $ultimalonchera = DB::table('loncheras')->join('detalleloncheras', 'detalleloncheras.lonchera_id', '=', 'loncheras.id')
            ->where('loncheras.estudiante_id', $this->estudiante->id)
            ->where('loncheras.habilitado', 1)
            ->where('loncheras.estado', 1)
            ->where('detalleloncheras.estado', 1)
            ->orderBy('detalleloncheras.fecha', 'DESC')
            ->first();
        $ultimaFecha = new DateTime($ultimalonchera->fecha);
        $i = 0;
        while ($i < 1) {
            $ultimaFecha->modify('+1 day');
            $dia = $ultimaFecha->format('N');
            if ($dia >= 1 && $dia <= 5) {
                if (!esFeriado(date_format($ultimaFecha, 'Y-m-d'))) {
                    $detallelonchera = Detallelonchera::find($detallelonchera_id);
                    $detallelonchera->estado = 0;                    
                    $detallelonchera->save();
                    $detalle = Detallelonchera::create([
                        "fecha" => date_format($ultimaFecha, 'Y-m-d'),
                        "tipomenu_id" => $detallelonchera->tipomenu_id,
                        "lonchera_id" => $detallelonchera->lonchera_id,
                        "entregado" => 0,
                    ]);
                    $i++;
                }
            }
        }
        $ultimaFecha->modify('+ 1 day');        
    }
}
