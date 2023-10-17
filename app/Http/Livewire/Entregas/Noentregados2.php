<?php

namespace App\Http\Livewire\Entregas;

use App\Models\Bonoanuale;
use App\Models\Bonofecha;
use App\Models\Detallelonchera;
use App\Models\Entregalounch;
use App\Models\Evento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Noentregados2 extends Component
{
    public $arrPedidos = array(), $fecha = "";

    public function render()
    {
        return view('livewire.entregas.noentregados2')->extends('layouts.app');
    }

    protected $listeners = ['finalizar','finalizar2'];

    public function generaListado()
    {
        $this->emit('loading');
        $this->arrPedidos = $this->noentregados2();
        $this->emit('datatableRender');
        if (!$this->arrPedidos) {
            $this->emit('warning', 'No se encontraron resultados');
        }
        $this->emit('unLoading');
    }

    public function noentregados()
    {
        $hoy = date('Y-m-d');
        $arrPedidos = array();
        $evento = Evento::where('fecha', $hoy)->first();
        if ($evento) {
            $detalleevento = $evento->detalleeventos;

            $bonosanuales = Bonoanuale::where('gestion', date('Y'))->where('estado', true)->get();

            $bonosfechas = Bonofecha::where('fechainicio', '<=', $hoy)->where('fechafin', '>=', $hoy)
                ->where('estado', true)->get();


            $reservas = Detallelonchera::where('fecha', $hoy)->where('entregado', false)
                ->where('estado', 1)->get();

            foreach ($bonosanuales as $bonoanual) {
                if (!tieneLicencia($bonoanual->estudiante_id, $hoy) && !tieneEntrega($bonoanual->estudiante_id, $hoy)) {
                    $menu = null;
                    foreach ($detalleevento as $detalle) {
                        if ($detalle->menu->tipomenu_id == $bonoanual->tipomenu_id) {
                            $menu = $detalle->menu;
                        }
                    }
                    if ($menu) {
                        $arrPedidos[] = array(
                            "curso_id" => $bonoanual->estudiante->curso_id,
                            "detallelonchera_id" => null,
                            "menu_id" => $menu->id,
                            "producto_id" => 1,
                            "menu" => $bonoanual->tipomenu->nombre,
                            "venta_id" => $bonoanual->venta_id,
                            "curso" => $bonoanual->estudiante->curso->nombre,
                            "estudiante_id" => $bonoanual->estudiante_id,
                            "estudiante" => $bonoanual->estudiante->nombre,
                            "codigo" => $bonoanual->estudiante->codigo,
                        );
                    }
                }
            }

            foreach ($bonosfechas as $bonofecha) {
                if (!tieneLicencia($bonofecha->estudiante_id, $hoy) && !tieneEntrega($bonofecha->estudiante_id, $hoy)) {
                    $menu = null;
                    foreach ($detalleevento as $detalle) {
                        if ($detalle->menu->tipomenu_id == $bonofecha->tipomenu_id) {
                            $menu = $detalle->menu;
                        }
                    }
                    if ($menu) {
                        $arrPedidos[] = array(
                            "curso_id" => $bonofecha->estudiante->curso_id,
                            "detallelonchera_id" => null,
                            "menu_id" => $menu->id,
                            "producto_id" => 2,
                            "menu" => $bonofecha->tipomenu->nombre,
                            "venta_id" => $bonofecha->venta_id,
                            "curso" => $bonofecha->estudiante->curso->nombre,
                            "estudiante_id" => $bonofecha->estudiante_id,
                            "estudiante" => $bonofecha->estudiante->nombre,
                            "codigo" => $bonofecha->estudiante->codigo,
                        );
                    }
                }
            }

            foreach ($reservas as $reserva) {
                if (!tieneLicencia($reserva->lonchera->estudiante_id, $hoy) && !tieneEntrega($reserva->lonchera->estudiante_id, $hoy)) {
                    if ($reserva->lonchera->habilitado = true && $reserva->lonchera->estado == true) {
                        $menu = null;
                        foreach ($detalleevento as $detalle) {
                            if ($detalle->menu->tipomenu_id == $reserva->tipomenu_id) {
                                $menu = $detalle->menu;
                            }
                        }
                        $arrPedidos[] = array(
                            "curso_id" => $reserva->lonchera->estudiante->curso_id,
                            "detallelonchera_id" => $reserva->id,
                            "menu_id" => $menu->id,
                            "producto_id" => 3,
                            "menu" => $reserva->tipomenu->nombre,
                            "venta_id" => $reserva->lonchera->venta_id,
                            "curso" => $reserva->lonchera->estudiante->curso->nombre,
                            "estudiante_id" => $reserva->lonchera->estudiante_id,
                            "estudiante" => $reserva->lonchera->estudiante->nombre,
                            "codigo" => $reserva->lonchera->estudiante->codigo,
                        );
                    }
                }
            }
        }
        return $arrPedidos;
    }

    public function noentregados2()
    {
        $hoy = $this->fecha;
        $arrPedidos = array();
        $evento = Evento::where('fecha', $hoy)->first();
        if ($evento) {
            $detalleevento = $evento->detalleeventos;

            $bonosanuales = Bonoanuale::where('gestion', date('Y'))->where('estado', true)->get();

            $bonosfechas = Bonofecha::where('fechainicio', '<=', $hoy)->where('fechafin', '>=', $hoy)
                ->where('estado', true)->get();


            $reservas = Detallelonchera::where('fecha', $hoy)->where('entregado', false)
                ->where('estado', 1)->get();

            foreach ($bonosanuales as $bonoanual) {
                if (!tieneLicencia($bonoanual->estudiante_id, $hoy) && !tieneEntrega($bonoanual->estudiante_id, $hoy)) {
                    $menu = null;
                    foreach ($detalleevento as $detalle) {
                        if ($detalle->menu->tipomenu_id == $bonoanual->tipomenu_id) {
                            $menu = $detalle->menu;
                        }
                    }
                    if ($menu) {
                        $arrPedidos[] = array(
                            "curso_id" => $bonoanual->estudiante->curso_id,
                            "detallelonchera_id" => null,
                            "menu_id" => $menu->id,
                            "producto_id" => 1,
                            "menu" => $bonoanual->tipomenu->nombre,
                            "venta_id" => $bonoanual->venta_id,
                            "curso" => $bonoanual->estudiante->curso->nombre,
                            "estudiante_id" => $bonoanual->estudiante_id,
                            "estudiante" => $bonoanual->estudiante->nombre,
                            "codigo" => $bonoanual->estudiante->codigo,
                        );
                    }
                }
            }

            foreach ($bonosfechas as $bonofecha) {
                if (!tieneLicencia($bonofecha->estudiante_id, $hoy) && !tieneEntrega($bonofecha->estudiante_id, $hoy)) {
                    $menu = null;
                    foreach ($detalleevento as $detalle) {
                        if ($detalle->menu->tipomenu_id == $bonofecha->tipomenu_id) {
                            $menu = $detalle->menu;
                        }
                    }
                    if ($menu) {
                        $arrPedidos[] = array(
                            "curso_id" => $bonofecha->estudiante->curso_id,
                            "detallelonchera_id" => null,
                            "menu_id" => $menu->id,
                            "producto_id" => 2,
                            "menu" => $bonofecha->tipomenu->nombre,
                            "venta_id" => $bonofecha->venta_id,
                            "curso" => $bonofecha->estudiante->curso->nombre,
                            "estudiante_id" => $bonofecha->estudiante_id,
                            "estudiante" => $bonofecha->estudiante->nombre,
                            "codigo" => $bonofecha->estudiante->codigo,
                        );
                    }
                }
            }

            foreach ($reservas as $reserva) {
                if (!tieneLicencia($reserva->lonchera->estudiante_id, $hoy) && !tieneEntrega($reserva->lonchera->estudiante_id, $hoy)) {
                    if ($reserva->lonchera->habilitado = true && $reserva->lonchera->estado == true) {
                        $menu = null;
                        foreach ($detalleevento as $detalle) {
                            if ($detalle->menu->tipomenu_id == $reserva->tipomenu_id) {
                                $menu = $detalle->menu;
                            }
                        }
                        $arrPedidos[] = array(
                            "curso_id" => $reserva->lonchera->estudiante->curso_id,
                            "detallelonchera_id" => $reserva->id,
                            "menu_id" => $menu->id,
                            "producto_id" => 3,
                            "menu" => $reserva->tipomenu->nombre,
                            "venta_id" => $reserva->lonchera->venta_id,
                            "curso" => $reserva->lonchera->estudiante->curso->nombre,
                            "estudiante_id" => $reserva->lonchera->estudiante_id,
                            "estudiante" => $reserva->lonchera->estudiante->nombre,
                            "codigo" => $reserva->lonchera->estudiante->codigo,
                        );
                    }
                }
            }
        }
        return $arrPedidos;
    }

    public function finalizar2()
    {
        // dd('Ingreso');
        $this->emit('loading');
        if ($this->arrPedidos) {
            DB::beginTransaction();
            try {
                foreach ($this->arrPedidos as $pedido) {
                    $entrega = Entregalounch::create([
                        "fechaentrega" => $this->fecha . date(' H:i:s'),
                        "detallelonchera_id" => $pedido['detallelonchera_id'],
                        "menu_id" => $pedido['menu_id'],
                        "producto_id" => $pedido['producto_id'],
                        "venta_id" => $pedido['venta_id'],
                        "user_id" => Auth::user()->id,
                        "sucursale_id" => Auth::user()->sucursale_id,
                        "estudiante_id" => $pedido['estudiante_id'],
                        "observaciones" => "AUSENCIA INJUSTIFICADA",
                    ]);

                    if ($pedido['detallelonchera_id']) {
                        $detalle = Detallelonchera::find($pedido['detallelonchera_id']);
                        $detalle->entregado = true;
                        $detalle->save();
                    }
                }
                DB::commit();
                $this->reset(['arrPedidos']);
                $this->emit('unLoading');
                $this->emit('success', 'Pedidos Finalizados!.');
            } catch (\Throwable $th) {
                DB::rollBack();
                // $this->emit('error','Ha ocurrido un error, no se guardaron los cambios.');
                $this->emit('unLoading');
                $this->emit('error', $th->getMessage());
            }
        }
    }

    public function finalizar()
    {
        $this->emit('loading');
        if ($this->arrPedidos) {
            DB::beginTransaction();
            try {
                foreach ($this->arrPedidos as $pedido) {
                    $entrega = Entregalounch::create([
                        "fechaentrega" => date('Y-m-d H:i:s'),
                        "detallelonchera_id" => $pedido['detallelonchera_id'],
                        "menu_id" => $pedido['menu_id'],
                        "producto_id" => $pedido['producto_id'],
                        "venta_id" => $pedido['venta_id'],
                        "user_id" => Auth::user()->id,
                        "sucursale_id" => Auth::user()->sucursale_id,
                        "estudiante_id" => $pedido['estudiante_id'],
                        "observaciones" => "AUSENCIA INJUSTIFICADA",
                    ]);

                    if ($pedido['detallelonchera_id']) {
                        $detalle = Detallelonchera::find($pedido['detallelonchera_id']);
                        $detalle->entregado = true;
                        $detalle->save();
                    }
                }
                DB::commit();
                $this->reset(['arrPedidos']);
                $this->emit('unLoading');
                $this->emit('success', 'Pedidos Finalizados!.');
            } catch (\Throwable $th) {
                DB::rollBack();
                // $this->emit('error','Ha ocurrido un error, no se guardaron los cambios.');
                $this->emit('unLoading');
                $this->emit('error', $th->getMessage());
            }
        }
    }
}
