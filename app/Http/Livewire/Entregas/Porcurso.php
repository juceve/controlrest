<?php

namespace App\Http\Livewire\Entregas;

use App\Models\Bonoanuale;
use App\Models\Bonofecha;
use App\Models\Curso;
use App\Models\Detallelonchera;
use App\Models\Entregalounch;
use App\Models\Estudiante;
use App\Models\Evento;
use App\Models\Licencia;
use App\Models\Lonchera;
use App\Models\Menu;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Porcurso extends Component
{
    public $selCurso = "", $arrPedidos = array(), $detalleevento = null, $html = "", $pedidocurso = "";

    public function mount()
    {
        $evento = Evento::where('fecha', date('Y-m-d'))->first();
        if ($evento) {
            $this->detalleevento = $evento->detalleeventos;
        }
    }

    public function updatedSelCurso()
    {
        if ($this->selCurso != "") {
            $this->reset(['arrPedidos', 'html', 'pedidocurso']);
            $this->pedidocurso = Curso::find($this->selCurso);
        } else {
            $this->reset(['arrPedidos', 'html', 'pedidocurso']);
        }
    }

    public function render()
    {
        $cursos = DB::table('cursos')
            ->join('nivelcursos', 'nivelcursos.id', '=', 'cursos.nivelcurso_id')
            ->where('nivelcursos.sucursale_id', Auth::user()->sucursale_id)
            ->select('cursos.id', 'cursos.nombre as curso', 'nivelcursos.nombre as nivel')
            ->get();

        return view('livewire.entregas.porcurso', compact('cursos'))->extends('layouts.app');
    }

    protected $listeners = ['entregar', 'cargaPedidos', 'prueba'];



    public function buscarCurso()
    {
        $this->emit('loading');
        $this->reset(['arrPedidos']);
        try {
            $estudiantes = Estudiante::where('curso_id', $this->selCurso)->orderBy('nombre', 'ASC')->get();
            foreach ($estudiantes as $estudiante) {
                $licencia = Licencia::where('estudiante_id', $estudiante->id)->where('fecha', date('Y-m-d'))->first();

                if ($licencia) {
                    $this->arrPedidos[] = array(0, 0, Auth::user()->id, Auth::user()->sucursale_id, 1, $estudiante->id, 'licencia', $licencia->id);
                } else {
                    $bonoanual = Bonoanuale::where('estudiante_id', $estudiante->id)
                        ->where('estado', 1)
                        ->where('gestion', date('Y'))
                        ->first();

                    $bonofecha = Bonofecha::where('estudiante_id', $estudiante->id)
                        ->whereDate('fechainicio', '<=', date('Y-m-d'))
                        ->whereDate('fechafin', '>=', date('Y-m-d'))
                        ->where('estado', 1)
                        ->first();

                    $datas = DB::table('loncheras')
                        ->join('detalleloncheras', 'detalleloncheras.lonchera_id', '=', 'loncheras.id')
                        ->where('loncheras.estudiante_id', $estudiante->id)
                        ->where('loncheras.habilitado', 1)
                        ->where('detalleloncheras.fecha', date('Y-m-d'))
                        ->select('detalleloncheras.tipomenu_id', 'loncheras.venta_id', 'detalleloncheras.id as detalle_id')
                        ->first();
                    $entrega = Entregalounch::where('estudiante_id', $estudiante->id)->whereDate('fechaentrega', date('Y-m-d'))->first();
                    if ($bonoanual) {
                        $menu_id = "";
                        foreach ($this->detalleevento as $detalle) {
                            if ($detalle->menu->tipomenu_id == $bonoanual->tipomenu_id) {
                                $menu_id = $detalle->menu_id;
                            }
                        }
                        if ($entrega) {
                            $this->arrPedidos[] = array(0, 0, Auth::user()->id, Auth::user()->sucursale_id, 1, $estudiante->id, 'entregado', 0);
                        } else {

                            $this->arrPedidos[] = array($menu_id, $bonoanual->venta_id, Auth::user()->id, Auth::user()->sucursale_id, 1, $estudiante->id, 'bonoanual', $bonoanual->id);
                        }
                    } else {
                        if ($bonofecha) {
                            $menu_id = "";
                            foreach ($this->detalleevento as $detalle) {
                                if ($detalle->menu->tipomenu_id == $bonofecha->tipomenu_id) {
                                    $menu_id = $detalle->menu_id;
                                }
                            }
                            if ($entrega) {
                                $this->arrPedidos[] = array(0, 0, Auth::user()->id, Auth::user()->sucursale_id, 1, $estudiante->id, 'entregado', 0);
                            } else {

                                $this->arrPedidos[] = array($menu_id, $bonofecha->venta_id, Auth::user()->id, Auth::user()->sucursale_id, 1, $estudiante->id, 'bonofecha', $bonofecha->id);
                            }
                        } else {
                            if ($datas) {
                                $detalles = DB::select("SELECT m.* from eventos e
                                INNER JOIN detalleeventos de on de.evento_id = e.id
                                INNER JOIN menus m on m.id = de.menu_id
                                WHERE e.fecha = '" . date('Y-m-d') . "'
                                AND m.tipomenu_id = " . $datas->tipomenu_id . "
                                AND e.sucursale_id = " . Auth::user()->sucursale_id);
                                $detallem = null;
                                foreach ($detalles as $detalle) {
                                    $detallem = $detalle;
                                }
                                if ($datas) {
                                    if ($entrega) {
                                        $this->arrPedidos[] = array(0, 0, Auth::user()->id, Auth::user()->sucursale_id, 1, $estudiante->id, 'entregado', 0);
                                    } else {

                                        $this->arrPedidos[] = array($detallem->id, $datas->venta_id, Auth::user()->id, Auth::user()->sucursale_id, 1, $estudiante->id, 'detallelonchera', $datas->detalle_id);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            $this->emit('unLoading');
            $this->emit('error', $th->getMessage());
        }

        $this->html = $this->generarListado();
        $this->emit('htmlBody', $this->html);
        $this->emit('unLoading');
    }

    public function generarListado()
    {
        $listadoHtml = "";
        $i = 1;
        foreach ($this->arrPedidos as $pedido) {
            if ($pedido[6] == 'licencia' || $pedido[6] == 'entregado') {
                $estudiante = Estudiante::find($pedido[5]);
                if ($pedido[6] == 'licencia') {
                    $listadoHtml = $listadoHtml . '<tr>
                    <td>' . $i . '</td>
                    <td>' . $estudiante->nombre . '</td>
                    <td><span class="badge badge-outline-warning rounded-pill">Licencia</span></td>
                    <td align="center">
                    <span class="badge badge-outline-warning rounded-pill">Licencia</span>                      
                    </td>
                    <td align="center">
                    <span class="badge badge-outline-warning rounded-pill">Licencia</span>                      
                    </td>
                    <td align="center">
                    <span class="badge badge-outline-warning rounded-pill">Licencia</span>                      
                    </td>
                </tr>';
                $i++;
                } else {

                    $listadoHtml = $listadoHtml . '<tr>
                    <td>' . $i . '</td>
                    <td>' . $estudiante->nombre . '</td>
                    <td><span class="badge badge-outline-success rounded-pill">Entregado</span></td>
                    <td align="center">
                    <span class="badge badge-outline-success rounded-pill">Entregado</span>                      
                    </td>
                    <td align="center">
                    <span class="badge badge-outline-success rounded-pill">Entregado</span>                      
                    </td>
                    <td align="center">
                    <span class="badge badge-outline-success rounded-pill">Entregado</span>                      
                    </td>
                </tr>';
                }
                $i++;
            } else {
                $hoy = date('Y-m-d');

                //         $button = '<button class="btn btn-sm btn-success"  onclick="entregar(' . $pedido[5] . ')">
                //     ENTREGAR
                //     <i class="uil-check"></i>
                // </button>';
                if (!$this->entregado($pedido[5], $hoy, $pedido[0])) {
                    $button = '<small class="text-success">Entregado!</small>';
                }
                $estudiante = Estudiante::find($pedido[5]);
                $menu = Menu::find($pedido[0]);
                $listadoHtml = $listadoHtml . '<tr style="vertical-align:middle;">
                    <td>' . $i . '</td>
                    <td>' . $estudiante->nombre . ' <input type="hidden" value="' . $estudiante->id . '"></td>
                    <td>' . $menu->nombre . ' <input type="hidden" value="' . $menu->id . '"></td>
                    <td align="center"><input type="radio" id="fa' . $pedido[5] . '" name="rb' . $pedido[5] . '" class="form-check-input" checked></td>
                    <td align="center" style="background-color: #cef5ea;"><input type="radio" id="en' . $pedido[5] . '" name="rb' . $pedido[5] . '" class="form-check-input"></td>
                    <td align="center" style="background-color: #fff2cc;"><input type="radio" id="li' . $pedido[5] . '" name="rb' . $pedido[5] . '" class="form-check-input"></td>
                </tr>';

                $i++;
            }
        }
        return $listadoHtml;
    }

    public $arrListado = array();

    public function cargaPedidos($estudiante_id, $menu_id, $falta, $entrega, $licencia)
    {
        // $i = 0;
        // foreach ($this->arrListado as $fila) {
        //     if ($fila[0] == $estudiante_id) {
        //         unset($this->arrListado[$i]);
        //         $this->arrListado = array_values($this->arrListado);
        //     }
        //     $i++;
        // }
        if ($estudiante_id) {
            $this->arrListado[] = array($estudiante_id, $menu_id, $falta, $entrega, $licencia);
        }
    }

    public function prueba()
    {
        dd($this->arrListado);
    }

    public function entregar()
    {
        // $this->emit('loading');
        DB::beginTransaction();
        try {

            foreach ($this->arrListado as $fila) {
                foreach ($this->arrPedidos as $pedido) {

                    if ($pedido[5] == $fila[0]) {
                        if ($fila[2]) {
                            // $detallelonchera_id = null;
                            // if ($pedido[6] == 'detallelonchera') {
                            //     $detallelonchera_id = $pedido[7];
                            //     $detallelonchera = Detallelonchera::find($pedido[7]);
                            //     $detallelonchera->entregado = 1;
                            //     $detallelonchera->save();
                            // }
                            // $entrega = Entregalounch::create([
                            //     "fechaentrega" => date('Y-m-d H:i:s'),
                            //     "detallelonchera_id" => $detallelonchera_id,
                            //     "menu_id" => $pedido[0],
                            //     "venta_id" => $pedido[1],
                            //     "user_id" => $pedido[2],
                            //     "sucursale_id" => $pedido[3],
                            //     "estado" => $pedido[4],
                            //     "estudiante_id" => $pedido[5],
                            //     "observaciones" => "AUSENCIA INJUSTIFICADA"
                            // ]);
                        }

                        if ($fila[3]) {
                            $detallelonchera_id = null;
                            if ($pedido[6] == 'detallelonchera') {
                                $detallelonchera_id = $pedido[7];
                                $detallelonchera = Detallelonchera::find($pedido[7]);
                                $detallelonchera->entregado = 1;
                                $detallelonchera->save();
                            }
                            $entrega = Entregalounch::create([
                                "fechaentrega" => date('Y-m-d H:i:s'),
                                "detallelonchera_id" => $detallelonchera_id,
                                "menu_id" => $pedido[0],
                                "venta_id" => $pedido[1],
                                "user_id" => $pedido[2],
                                "sucursale_id" => $pedido[3],
                                "estado" => $pedido[4],
                                "estudiante_id" => $pedido[5],
                                "observaciones" => "ENTREGA ASISTIDA"
                            ]);
                        }

                        if ($fila[4]) {
                            $detallelonchera_id = null;
                            if ($pedido[6] == 'detallelonchera') {
                                $detallelonchera_id = $pedido[7];
                                $detalle = Detallelonchera::find($detallelonchera_id);

                                $licencia = Licencia::create([
                                    "estudiante_id" => $fila[0],
                                    "fecha" => $detalle->fecha,
                                    "detallelonchera_id" => $detallelonchera_id
                                ]);
                                $this->reprogramacionautomatica($detallelonchera_id, $fila[0]);
                            }

                            if ($pedido[6] == 'bonofecha') {
                                $fechaActual = new DateTime(date('Y-m-d'));
                                $fechaFinalDT = new DateTime(date('Y-m-d'));
                                $c = 0;
                                while ($fechaActual <=  $fechaFinalDT) {
                                    $diaSemana = $fechaActual->format('N');
                                    if ($diaSemana >= 1 && $diaSemana <= 5) {
                                        if (!esFeriado(date_format($fechaActual, 'Y-m-d'))) {
                                            $licencia = Licencia::create([
                                                "estudiante_id" => $fila[0],
                                                "fecha" => $fechaActual
                                            ]);
                                            $c++;
                                        }
                                    }
                                    $fechaActual->modify('+1 day');
                                }
                                $i = 0;
                                $bono = Bonofecha::find($pedido[7]);
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
                            }

                            if ($pedido[6] == 'bonoanual') {
                                $licencia = Licencia::create([
                                    "estudiante_id" => $fila[0],
                                    "fecha" => date('Y-m-d')
                                ]);
                            }
                        }
                    }
                }
            }




            DB::commit();
            // $this->emit('ocultaBtn', $estudiante_id);
            $this->emit('unLoading');
            return redirect()->route('entregas.porcurso')->with('success', 'Curso Finalizado!');
            // $this->emit('success', 'Curso Finalizado!');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->emit('unLoading');
            $this->emit('error', $th->getMessage());
        }
    }

    public function reprogramacionautomatica($detallelonchera_id, $estudiante_id)
    {
        $sql = "SELECT dl.* FROM loncheras l
        INNER JOIN detalleloncheras dl on dl.lonchera_id = l.id
        WHERE l.estudiante_id = " . $estudiante_id . "
        AND l.habilitado = 1
        AND l.estado = 1
        AND dl.estado = 1
        ORDER BY fecha DESC";

        $ultimalonchera = DB::table('loncheras')->join('detalleloncheras', 'detalleloncheras.lonchera_id', '=', 'loncheras.id')
            ->where('loncheras.estudiante_id', $estudiante_id)
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
                    $detallelonchera->fecha = date_format($ultimaFecha, 'Y-m-d');
                    $detallelonchera->save();
                    $i++;
                }
            }
        }
        $ultimaFecha->modify('+ 1 day');
    }

    public function entregado($estudiante_id, $fecha, $menu_id)
    {
        $entrega = Entregalounch::where('estudiante_id', $estudiante_id)
            ->whereDate('fechaentrega', $fecha)
            ->where('menu_id', $menu_id)
            ->first();
        if ($entrega) {
            return false;
        } else {
            return true;
        }
    }
}
