<?php

namespace App\Http\Livewire\Pagos;

use App\Models\Creditoprofesore;
use App\Models\Estudiante;
use App\Models\Pago;
use App\Models\Sucursale;
use App\Models\Tipopago;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Pagoprofesores extends Component
{

    public $fechainicio = "", $fechafin = "", $creditos = array(), $resultados = array(), $dias = array(), $sucursale_id, $ttotal = 0;
    public $creditoparapago = null, $tipopagos = null, $selTipo = "", $clientePago = "";

    public function mount()
    {
        $this->sucursale_id = Auth::user()->sucursale_id;
        $this->tipopagos = Tipopago::whereNotIn('id', [4, 5])->get();
    }

    public function updatedFechafin()
    {
        $this->reset('creditos', 'resultados', 'dias');
    }

    public function updatedFechainicio()
    {
        $this->reset('creditos', 'resultados', 'dias');
    }

    protected $listeners = ['gestionarPagos'];

    public function render()
    {
        $profesores = Estudiante::where('esestudiante', 0)->get();
        $sucursale = Sucursale::where('id', Auth::user()->sucursale_id)->first();
        // $this->emit('datatableRender');

        return view('livewire.pagos.pagoprofesores', compact('profesores'))->extends('layouts.app');
    }

    public function buscar()
    {
        $this->reset(['creditos', 'resultados', 'dias', 'ttotal', 'selTipo', 'clientePago']);
        $this->emit('loading');
        $sql = "SELECT c.nombre curso,cp.estudiante_id,e.nombre ,COUNT(*) cantidad, SUM(v.importe) importe FROM creditoprofesores cp
        INNER JOIN estudiantes e on e.id = cp.estudiante_id
        INNER JOIN ventas v on v.id = cp.venta_id
        INNER JOIN cursos c on c.id = e.curso_id
        WHERE cp.sucursale_id = $this->sucursale_id
        AND cp.fecha BETWEEN '" . $this->fechainicio . "' AND '" . $this->fechafin . "'
        AND cp.pagado = 0
        GROUP BY c.nombre, cp.estudiante_id, e.nombre
        ORDER BY c.nombre,e.nombre";

        $this->creditoparapago = Creditoprofesore::where('pagado', 0)->where('sucursale_id', $this->sucursale_id)->whereBetween('fecha', [$this->fechainicio, $this->fechafin])->get();


        $resultados = DB::select($sql);
        foreach ($resultados as $item) {
            $this->creditos[] = array($item->curso, $item->estudiante_id, $item->nombre, $item->cantidad, $item->importe);
        }

        if ($this->creditos) {
            $dias = $this->traeDias();
            $this->dias = $dias;
            $sql2 = "SELECT DISTINCT(cp.estudiante_id) FROM creditoprofesores cp 
            WHERE cp.sucursale_id = $this->sucursale_id
            AND cp.pagado = 0
            AND fecha BETWEEN '" . $this->fechainicio . "' AND '" . $this->fechafin . "'";
            $estudiantes = DB::select($sql2);
            $creditos = array();

            $arrAux = array();
            foreach ($estudiantes as $estudiante) {
                $x = 0;
                $results = Creditoprofesore::where('estudiante_id', $estudiante->estudiante_id)->where('pagado', 0)->whereBetween('fecha', [$this->fechainicio, $this->fechafin])->get();
                $arr1 = [];
                foreach ($results as $credito) {
                    if ($x == 0) {
                        $arr1[] = $credito->estudiante->nombre;
                        $arr1[] = $credito->estudiante->curso->nombre;
                        $x++;
                    }
                    $arr1[] = array($credito->fecha, $credito->venta->importe);
                }
                $creditos[] = $arr1;
            }

            if ($creditos) {

                $tabla = array();
                $ttotal = 0;
                foreach ($creditos as $credito) {
                    $fila = array();
                    $fila[] = array($credito[0], 'left', '');
                    $fila[] = array($credito[1], 'center', '');
                    $c = count($credito);
                    $total = 0;
                    $i = 0;
                    foreach ($dias as $dia) {
                        $importe = 0;
                        for ($x = 2; $x < $c; $x++) {
                            if ($dia[0] == $credito[$x][0]) {
                                $importe = $credito[$x][1];
                            }
                        }

                        $fila[] = array($importe, 'center', '');
                        $total += $importe;
                        $this->dias[$i][1] += $importe;


                        $i++;
                    }
                    $ttotal += $total;
                    $fila[] = array(number_format($total, 2, '.', ','), 'center', 'background-color: #cef5ea;');
                    $tabla[] = $fila;
                }

                $this->ttotal = $ttotal;
                $this->resultados = $tabla;
                $this->emit('unLoading');
            }
        } else {
            $this->emit('unLoading');
            $this->emit('warning', 'No se econtraron resultados.');
        }
    }

    public function traeDias()
    {
        $fechaActual = new DateTime($this->fechainicio);
        $fechas = array();
        while ($fechaActual <= new DateTime($this->fechafin)) {
            $diaSemana = $fechaActual->format('N');
            if ($diaSemana >= 1 && $diaSemana <= 5) {
                if (!esFeriado(date_format($fechaActual, 'Y-m-d'))) {
                    $fechas[] = array(date_format($fechaActual, 'Y-m-d'), 0);
                }
            }
            $fechaActual->modify('+1 day');
        }

        return $fechas;
    }

    public function pdf()
    {
        $resultados = $this->resultados;
        $dias = $this->dias;
        $ttotal = $this->ttotal;
        $fechaI = $this->fechainicio;
        $fechaF = $this->fechafin;
        $pdf = Pdf::loadView('reports.rptcreditos', compact('resultados', 'dias', 'ttotal', 'fechaI', 'fechaF'))->setPaper('Letter', 'landscape')->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "CREDITO_PROFESORES_" . $fechaI . "_" . $fechaF . ".pdf"
        );
    }

    public function gestionarPagos()
    {
        $this->emit('loading');
        if ($this->creditoparapago) {
            if ($this->selTipo != "") {
                if ($this->clientePago != "") {
                    DB::beginTransaction();
                    try {
                        $c = 0;
                        $tipopago = Tipopago::find($this->selTipo);
                        foreach ($this->creditoparapago as $credito) {
                            $pago = Pago::create([
                                "fecha" => date('Y-m-d'),
                                "recibo" => 0,
                                "tipopago_id" => $tipopago->id,
                                "tipopago" => $tipopago->nombre,
                                "sucursal_id" => Auth::user()->sucursale_id,
                                "sucursal" => Auth::user()->sucursale->nombre,
                                "importe" => $credito->venta->importe,
                                "venta_id" => $credito->venta_id,
                                "estadopago_id" => 2,
                                "comprobante" => null,
                                "tipoinicial" => $credito->venta->tipopago->nombre,
                                "estudiante_id" => $credito->estudiante_id,
                                "user_id" => Auth::user()->id,
                                "estado" => true,
                            ]);

                            $venta = Venta::find($credito->venta_id);
                            $venta->estadopago_id = 2;
                            $venta->save();

                            $creditoactual = Creditoprofesore::find($credito->id);
                            $creditoactual->pagado = true;
                            $creditoactual->save();

                            $c++;
                        }
                        $this->emit('unLoading');
                        DB::commit();         
                        $this->emit('cerrarmodal');
                        $detalles[] = array('PAGO DE CREDITOS DEL PLANTEL DOCENTE', $c, $this->ttotal);
                        $tipopago = $tipopago->nombre;
                        $cliente = $this->clientePago;
                        $this->reset(['creditos', 'resultados', 'dias', 'ttotal', 'selTipo', 'clientePago', 'fechainicio', 'fechafin']);
                        $pdf = Pdf::loadView('reports.recibocreditos', compact('detalles', 'tipopago', 'cliente'))->output();
                        $this->emit('success', 'Operación realizada con exito!');
                        return response()->streamDownload(
                            fn () => print($pdf),
                            "RECIBOCREDITO_" . date('YmdHi') . ".pdf"
                        );
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        $this->emit('error', $th->getMessage());
                    }
                } else {
                    $this->emit('unLoading');
                    $this->emit('warning', 'Digite un cliente.');
                }
            } else {
                $this->emit('unLoading');
                $this->emit('warning', 'Debe seleccionar un tipo de pago.');
            }
        } else {
            $this->emit('unLoading');
            $this->emit('error', 'Ha ocurrido un error.');
        }
    }
}
