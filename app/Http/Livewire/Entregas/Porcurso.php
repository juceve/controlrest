<?php

namespace App\Http\Livewire\Entregas;

use App\Models\Bonoanuale;
use App\Models\Bonofecha;
use App\Models\Curso;
use App\Models\Detallelonchera;
use App\Models\Detalleventa;
use App\Models\Entregalounch;
use App\Models\Estudiante;
use App\Models\Evento;
use App\Models\Licencia;
use App\Models\Lonchera;
use App\Models\Menu;
use App\Models\Venta;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Porcurso extends Component
{
    public $selCurso = "", $arrPedidos = array(), $detalleevento = null, $html = "", $pedidocurso = "", $selMenu = 3;

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
        if ($this->detalleevento) {
            $menuAC = 0;
            foreach ($this->detalleevento as $dtev) {
                if ($dtev->tipo == "ALMUERZO COMPLETO") {
                    $menuAC = $dtev->menu_id;
                }
            }
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
                            ->select('detalleloncheras.tipomenu_id', 'detalleloncheras.menu_id', 'loncheras.venta_id', 'detalleloncheras.id as detalle_id')
                            ->first();
                        $entrega = Entregalounch::where('estudiante_id', $estudiante->id)->whereDate('fechaentrega', date('Y-m-d'))->first();
                        if ($bonoanual) {
                            $menu_id = array();
                            foreach ($this->detalleevento as $detalle) {
                                if ($detalle->menu->tipomenu_id == $bonoanual->tipomenu_id) {
                                    $menu_id[] = $detalle->menu_id;
                                }
                            }
                            if ($entrega) {
                                $this->arrPedidos[] = array(0, 0, Auth::user()->id, Auth::user()->sucursale_id, 1, $estudiante->id, 'entregado', 0, $entrega->observaciones);
                            } else {

                                $this->arrPedidos[] = array($menu_id, $bonoanual->venta_id, Auth::user()->id, Auth::user()->sucursale_id, 1, $estudiante->id, 'bonoanual', $bonoanual->id, 1);
                            }
                        } else {
                            if ($bonofecha) {
                                $menu_id = array();
                                foreach ($this->detalleevento as $detalle) {

                                    if ($detalle->menu->tipomenu_id == $bonofecha->tipomenu_id) {
                                        $menu_id[] = $detalle->menu_id;
                                    }
                                }
                                if ($entrega) {
                                    $this->arrPedidos[] = array(0, 0, Auth::user()->id, Auth::user()->sucursale_id, 1, $estudiante->id, 'entregado', 0, $entrega->observaciones);
                                } else {

                                    $this->arrPedidos[] = array($menu_id, $bonofecha->venta_id, Auth::user()->id, Auth::user()->sucursale_id, 1, $estudiante->id, 'bonofecha', $bonofecha->id, 2);
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
                                    $menu = array($detallem->id);
                                    if ($datas) {
                                        if ($entrega) {
                                            $this->arrPedidos[] = array(0, 0, Auth::user()->id, Auth::user()->sucursale_id, 1, $estudiante->id, 'entregado', 0, $entrega->observaciones);
                                        } else {

                                            $this->arrPedidos[] = array($menu, $datas->venta_id, Auth::user()->id, Auth::user()->sucursale_id, 1, $estudiante->id, 'detallelonchera', $datas->detalle_id, 3);
                                        }
                                    }
                                } else {
                                    $this->arrPedidos[] = array($menuAC, 0, Auth::user()->id, Auth::user()->sucursale_id, 1, $estudiante->id, 'no_reserva', 0, '');
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
        } else {
            $this->emit('warning', 'No existen eventos para el dia de hoy.');
        }
    }

    public function generarListado()
    {
        $listadoHtml = "";
        $i = 1;
        foreach ($this->arrPedidos as $pedido) {
            if ($pedido[6] == 'licencia' || $pedido[6] == 'entregado' || $pedido[6] == 'no_reserva') {
                $estudiante = Estudiante::find($pedido[5]);
                $estado = estadoPedidoEstudiante($estudiante->id);
                if ($pedido[6] == 'licencia') {
                    $listadoHtml = $listadoHtml . '<tr>
                    <td>' . $i . '</td>
                    <td>' . $estudiante->nombre . '</td>
                    <td align="center">0</td>
                    <td align="center"><small>Finalizado</small></td>
                    <td align="center">
                    --
                    </td>
                    <td align="center">
                    --
                    </td>
                    <td align="center">
                    <span class="badge badge-outline-warning rounded-pill">Licencia</span>
                    </td>
                    <td style="display: none" align="center"><input type="text" value="licencia"></td>
                </tr>';
                    $i++;
                } else {
                    if ($pedido[6] == 'no_reserva') {
                        $listadoHtml = $listadoHtml . '<tr>
                    <td>' . $i . '</td>
                    <td>' . $estudiante->nombre . '<input type="hidden" value="' . $estudiante->id . '"></td>
                    <td align="center">0</td>';
                        if (tieneEntrega($estudiante->id, date('Y-m-d'))) {
                            $listadoHtml = $listadoHtml . '<td align="center"><small>Finalizado</small></td>
                            <td align="center">
                            --
                            </td>
                            <td align="center">
                            --
                            </td>
                            <td align="center">
                            --
                            </td>
                            <td style="display: none" align="center"><input type="text" value="credito"></td>
                        </tr>';
                        } else {
                            $listadoHtml = $listadoHtml . '<td align="center"><small class="text-warning">Credito AC</small><input type="hidden"  value="' . $pedido[0] . '"></td>
                            <td align="center">
                            <input type="radio" id="fa' . $pedido[5] . '" name="rb' . $pedido[5] . '" class="form-check-input" checked>
                            </td>                    
                            <td align="center" style="background-color: #cef5ea;"><input type="radio" id="en' . $pedido[5] . '" name="rb' . $pedido[5] . '" class="form-check-input"></td>                    
                            <td align="center" ><input type="radio" id="li' . $pedido[5] . '" name="rb' . $pedido[5] . '" class="form-check-input" disabled></td>
                            <td style="display: none" align="center"><input type="text" value="credito"></td>
                        </tr>';
                        }
                    } else {
                        if ($pedido[8] == "AUSENCIA INJUSTIFICADA") {
                            $listadoHtml = $listadoHtml . '<tr>
                    <td>' . $i . '</td>
                    <td>' . $estudiante->nombre . '</td>
                    <td align="center">0</td>
                    <td align="center"><small>Finalizado</small></td>
                    <td align="center">
                    <span class="badge badge-outline-secondary rounded-pill">Ausencia</span>
                    </td>
                    <td align="center">
                    --
                    </td>
                    <td align="center">
                    --
                    </td>
                    <td style="display: none" align="center"><input type="text" value="ausencia"></td>
                </tr>';
                        } else {
                            $listadoHtml = $listadoHtml . '<tr>
                        <td>' . $i . '</td>
                        <td>' . $estudiante->nombre . '</td>
                        <td align="center">' . $estado['restantes'] . '</td>
                        <td align="center"><small>Finalizado</small></td>
                        <td align="center">
                        --
                        </td>
                        <td align="center">
                        <span class="badge badge-outline-success rounded-pill">Entregado</span>
                        </td>
                        <td align="center">
                        --
                        </td>
                        <td style="display: none" align="center"><input type="text" value="finalizado"></td>
                    </tr>';
                        }
                    }
                }
                $i++;
            } else {
                $hoy = date('Y-m-d');

                //         $button = '<button class="btn btn-sm btn-success"  onclick="entregar(' . $pedido[5] . ')">
                //     ENTREGAR
                //     <i class="uil-check"></i>
                // </button>';
                // if (!$this->entregado($pedido[5], $hoy, $pedido[0][0])) {
                //     $button = '<small class="text-success">Entregado!</small>';
                // }
                $estudiante = Estudiante::find($pedido[5]);
                $estado = estadoPedidoEstudiante($estudiante->id);
                // dd($estado);
                $menu = Menu::whereIn('id', $pedido[0])->get();
                $listadoHtml = $listadoHtml . '<tr style="vertical-align:middle;">
                    <td>' . $i . '</td>
                    <td>' . $estudiante->nombre . ' <input type="hidden" value="' . $estudiante->id . '"></td><td align="center">' . $estado['restantes'] . '</td>';
                if ($menu->count() > 1) {
                    $listadoHtml = $listadoHtml . '<td><select id="selExtra" class="form-select">';
                    $b = 0;
                    foreach ($menu as $item) {
                        $checked = "";
                        if ($b == 0) {
                            $checked = 'selected';
                            $b++;
                        }
                        $listadoHtml = $listadoHtml . '<option value="' . $item->id . '" ' . $checked . '>' . $item->nombre . '</option>';
                    }
                    $listadoHtml = $listadoHtml . '</select></td>';
                } else {
                    foreach ($menu as $item) {
                        $listadoHtml = $listadoHtml . '<td align="center">' . $item->tipomenu->abr . ' <input type="hidden"  value="' . $item->id . '"></td>';
                    }
                }
                // $listadoHtml = $listadoHtml . '<td>' . $menu->nombre . ' <input type="hidden" value="' . $menu->id . '"></td>';
                $listadoHtml = $listadoHtml . '<td align="center"><input type="radio" id="fa' . $pedido[5] . '" name="rb' . $pedido[5] . '" class="form-check-input" checked></td>
                    <td align="center" style="background-color: #cef5ea;"><input type="radio" id="en' . $pedido[5] . '" name="rb' . $pedido[5] . '" class="form-check-input"></td>
                    <td align="center" style="background-color: #fff2cc;"><input type="radio" id="li' . $pedido[5] . '" name="rb' . $pedido[5] . '" class="form-check-input"></td>
                    <td style="display: none" align="center"><input type="text" value=""></td>
                </tr>';

                $i++;
            }
        }
        return $listadoHtml;
    }

    public $arrListado = array();

    public function cargaPedidos($estudiante_id, $menu_id, $falta, $entrega, $licencia, $tipo)
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
            $this->arrListado[] = array($estudiante_id, $menu_id, $falta, $entrega, $licencia, $tipo);
        }
    }

    public function prueba()
    {
        dd($this->arrListado);
    }

    public function entregar()
    {
        $this->emit('loading');
        DB::beginTransaction();
        try {

            foreach ($this->arrListado as $fila) {
                if ($fila[5] == "credito") {
                    // CREACION DE LA VENTA POS A CREDITO PARA AQUELLOS QUE NO TIENEN SALDO
                    if ($fila[3]) {
                        $preciomenu = precioMenu($fila[1]);
                        $estudiante = Estudiante::find($fila[0]);
                        $venta = Venta::create([
                            "fecha" => date('Y-m-d'),
                            "cliente" => $estudiante->nombre,
                            "estadopago_id" => 1,
                            "tipopago_id" => 4,
                            "importe" => $preciomenu->precio,
                            "sucursale_id" => Auth::user()->sucursale_id,
                            "plataforma" => "admin",
                            "observaciones" => "CREDITO-ESTUDIANTE",
                            "user_id" => Auth::user()->id,
                        ]);

                        $entrega = Entregalounch::create([
                            "fechaentrega" => date('Y-m-d H:i:s'),
                            "menu_id" => $fila[1],
                            "producto_id" => 4,
                            "venta_id" => $venta->id,
                            "user_id" => Auth::user()->id,
                            "sucursale_id" => Auth::user()->sucursale_id,
                            "estudiante_id" => $fila[0],
                            'observaciones' => 'ESTUDIANTE SIN SALDO'
                        ]);

                        $detalleventa = Detalleventa::create([
                            'venta_id' => $venta->id,
                            'descripcion' => "Almuerzo completo",
                            'producto_id' => 4,
                            'tipomenu_id' => 1,
                            'cantidad' => 1,
                            'preciounitario' => $preciomenu->precio,
                            'subtotal' => $preciomenu->precio,
                            'observacion' => "Credito cliente sin saldo",

                        ]);
                    }
                    // FIN VENTA POS CREDITO
                } else {
                    foreach ($this->arrPedidos as $pedido) {
                        $menu = Menu::find($fila[1]);
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
                                    "menu_id" => $fila[1],
                                    "producto_id" => $pedido[8],
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
                                        "detallelonchera_id" => $detallelonchera_id,
                                        "tipomenu_id" => $detalle->tipomenu_id
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
                                                    "fecha" => $fechaActual,
                                                    "tipomenu_id" => $menu->tipomenu_id,
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
                                        "fecha" => date('Y-m-d'),
                                        "tipomenu_id" => $menu->tipomenu_id,
                                    ]);
                                }
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
