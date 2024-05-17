<?php

namespace App\Http\Livewire\Ventas;

use App\Exports\VentasExport;
use App\Models\Bonoanuale;
use App\Models\Bonofecha;
use App\Models\Detallelonchera;
use App\Models\Detalleventa;
use App\Models\Entregalounch;
use App\Models\Estadopago;
use App\Models\Lonchera;
use App\Models\Pago;
use App\Models\Tipopago;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Listado extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $busqueda = "", $fecInicio = "", $fecFin = "", $estadoPago = "", $tipoPago = "", $contenedor = null, $tp = "", $tps = null, $browseMobile;

    public function updBrowse($status)
    {
        $this->browseMobile = $status;
    }

    public function mount()
    {
        $this->fecInicio = date('Y-m-d');
        $this->fecFin = date('Y-m-d');
        $this->tps = Tipopago::all();
    }
    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->emit('loading');
        $estadoPagos = Estadopago::all();
        $tipoPagos = Tipopago::all();
        $ventas = null;

        if (Auth::user()->roles[0]->name == "VENTAS") {
            if ($this->tp != "") {
                $ventas = DB::table('ventas')
                    // ->leftJoin('pagos', 'pagos.venta_id', '=', 'ventas.id')
                    ->leftJoin('estadopagos', 'estadopagos.id', '=', 'ventas.estadopago_id')
                    ->leftJoin('tipopagos', 'tipopagos.id', '=', 'ventas.tipopago_id')
                    ->where('ventas.sucursale_id', Auth::user()->sucursale_id)
                    ->where('ventas.user_id', Auth::user()->id)
                    ->where('ventas.estado', 1)
                    ->whereBetween('ventas.fecha', [$this->fecInicio, $this->fecFin])
                    ->where('ventas.tipopago_id', $this->tp)
                    ->orderBy('ventas.id', 'ASC')
                    ->select('ventas.id', 'ventas.fecha', 'ventas.cliente', 'estadopagos.abreviatura as estadopago', 'ventas.importe', 'tipopagos.abreviatura as tipopago')
                    ->distinct('ventas_id')
                    ->get();
            } else {
                $ventas = DB::table('ventas')
                    // ->leftJoin('pagos', 'pagos.venta_id', '=', 'ventas.id')
                    ->leftJoin('estadopagos', 'estadopagos.id', '=', 'ventas.estadopago_id')
                    ->leftJoin('tipopagos', 'tipopagos.id', '=', 'ventas.tipopago_id')
                    ->where('ventas.sucursale_id', Auth::user()->sucursale_id)
                    ->where('ventas.user_id', Auth::user()->id)
                    ->where('ventas.estado', 1)
                    ->whereBetween('ventas.fecha', [$this->fecInicio, $this->fecFin])
                    ->orderBy('ventas.id', 'ASC')
                    ->select('ventas.id', 'ventas.fecha', 'ventas.cliente', 'estadopagos.abreviatura as estadopago', 'ventas.importe', 'tipopagos.abreviatura as tipopago')
                    ->distinct('ventas_id')
                    ->get();
            }
        }
        if (Auth::user()->roles[0]->name == "Admin" || Auth::user()->roles[0]->name == "SUPERVISOR") {
            if ($this->tp != "") {
                $ventas = DB::table('ventas')
                    // ->leftJoin('pagos', 'pagos.venta_id', '=', 'ventas.id')
                    ->leftJoin('estadopagos', 'estadopagos.id', '=', 'ventas.estadopago_id')
                    ->leftJoin('tipopagos', 'tipopagos.id', '=', 'ventas.tipopago_id')
                    ->where('ventas.sucursale_id', Auth::user()->sucursale_id)
                    // ->where('ventas.user_id', Auth::user()->id)
                    ->where('ventas.estado', 1)
                    ->whereBetween('ventas.fecha', [$this->fecInicio, $this->fecFin])
                    ->where('ventas.tipopago_id', $this->tp)
                    ->orderBy('ventas.id', 'ASC')
                    ->select('ventas.id', 'ventas.fecha', 'ventas.cliente', 'estadopagos.abreviatura as estadopago', 'ventas.importe', 'tipopagos.abreviatura as tipopago')
                    ->distinct('ventas_id')
                    ->get();
            } else {
                $ventas = DB::table('ventas')
                    // ->leftJoin('pagos', 'pagos.venta_id', '=', 'ventas.id')
                    ->leftJoin('estadopagos', 'estadopagos.id', '=', 'ventas.estadopago_id')
                    ->leftJoin('tipopagos', 'tipopagos.id', '=', 'ventas.tipopago_id')
                    ->where('ventas.sucursale_id', Auth::user()->sucursale_id)
                    // ->where('ventas.user_id', Auth::user()->id)
                    ->where('ventas.estado', 1)
                    ->whereBetween('ventas.fecha', [$this->fecInicio, $this->fecFin])
                    ->orderBy('ventas.id', 'ASC')
                    ->select('ventas.id', 'ventas.fecha', 'ventas.cliente', 'estadopagos.abreviatura as estadopago', 'ventas.importe', 'tipopagos.abreviatura as tipopago')
                    ->distinct('ventas_id')
                    ->get();
            }
        }
        $this->emit('unLoading');
        $this->emit('datatableRender');
        $this->contenedor = $ventas;

        return view('livewire.ventas.listado', compact('ventas', 'estadoPagos', 'tipoPagos'))->extends('layouts.app');
    }

    protected $listeners = ['anular'];

    public function exportar()
    {
        $usuario = array(Auth::user()->name, Auth::user()->sucursale->nombre);
        $fecInicio = $this->fecInicio;
        $fecFin = $this->fecFin;
        $contenedor = $this->contenedor;
        $pdf = Pdf::loadView('reports.ventas', compact('contenedor', 'usuario', 'fecInicio', 'fecFin'))->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "Ventas_" . $this->fecInicio . "_" . $this->fecFin . ".pdf"
        );
    }

    public function reimprimir($venta_id)
    {
        // 
        $venta = Venta::find($venta_id);
        $detalleventa = $venta->detalleventas;
        $producto_id = "";
        $observacion = "";
        foreach ($detalleventa as $item) {
            $producto_id = $item->producto_id;
            $observacion = $item->observacion;
            break;
        }
        switch ($producto_id) {
            case 1:

                break;
            case 2: {
                    $bonos = Bonofecha::where('venta_id', $venta->id)->get();
                    $detalleRecibo = "";
                    foreach ($bonos as $bono) {
                        $estudiante = $bono->estudiante;
                        $cantidadDias = contarDiasSemana($bono->fechainicio, $bono->fechafin);
                        $precios = precioTipoMenu($bono->tipomenu_id);
                        $preciounitario = "";
                        $descuento = "";
                        // dd($observacion);
                        if ($observacion == "DESCUENTO") {
                            $preciounitario = $precios->preciopm;
                            $descuento = "SI";
                            $importeEstudiante = $precios->preciopm * $cantidadDias;
                        } else {
                            $preciounitario = $precios->precio;
                            $descuento = "NO";
                            $importeEstudiante = $precios->precio * $cantidadDias;
                        }

                        // dd($precios);
                        $row = $estudiante->id . "|" . $estudiante->codigo . "|" . $estudiante->nombre . "|" . $estudiante->curso->nombre . "|" . $bono->fechainicio . "|" . $bono->fechafin . "|" . $bono->tipomenu->nombre . "|" . $cantidadDias . "|" . $preciounitario . "|" . $descuento . "|" . $importeEstudiante;
                        $detalleRecibo .= $row;
                        $detalleRecibo .= "^";
                    }

                    $detalleRecibo = substr($detalleRecibo, 0, -1);
                    $datosVentaRecibo = $venta->id . "|" . $venta->fecha . "|" . $venta->user->name . "|" . $venta->tipopago->abreviatura;

                    $this->emit('imprimirbf', $datosVentaRecibo . "~" . $detalleRecibo);
                }
                break;
            case 3:

                break;
            case 4:

                break;
        }
    }

    public function excel()
    {

        return Excel::download(new VentasExport($this->contenedor, $this->fecInicio, $this->fecFin), 'Ventas' . date('His') . '.xlsx');
    }

    public function anular($id)
    {
        DB::beginTransaction();
        try {
            $venta = Venta::find($id);
            $pago = Pago::where('venta_id', $id)->first();

            $venta->estado = 0;
            $venta->save();
            if ($pago) {
                $pago->estado = 0;
                $pago->save();
            }


            $bonoanual = Bonoanuale::where('venta_id', $id)->first();
            $bonomensual = Bonofecha::where('venta_id', $id)->first();
            $lonchera = Lonchera::where('venta_id', $id)->first();
            $entregalounche = Entregalounch::where('venta_id', $id)->first();

            if ($bonoanual) {
                $bonoanual->estado = 0;
                $bonoanual->save();
            }

            if ($bonomensual) {
                $bonomensual->estado = 0;
                $bonomensual->save();
            }

            if ($lonchera) {
                $lonchera->estado = 0;
                $lonchera->save();

                $detallelonchera = Detallelonchera::where('lonchera_id', $lonchera->id)->get();
                foreach ($detallelonchera as $item) {
                    $item->estado = 0;
                    $item->save();
                }
            }

            if ($entregalounche) {
                $entregalounche->estado = 0;
                $entregalounche->save();
            }

            DB::commit();
            return redirect()->route('ventas.index')
                ->with('success', 'Venta anulada correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('ventas.index')
                ->with('error', 'Ha ocurrido un error');
        }
    }
}
