<?php

namespace App\Http\Livewire\Ventas;

use App\Models\Creditoprofesore;
use App\Models\Detallelonchera;
use App\Models\Detalleventa;
use App\Models\Estudiante;
use App\Models\Evento;
use App\Models\Lonchera;
use App\Models\Menu;
use App\Models\Moneda;
use App\Models\Pago;
use App\Models\Tipopago;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Reservas extends Component
{
    use WithFileUploads;
    public $checks = [], $arrEstudiantes = [], $estudiantes = null,   $busqueda = "", $sucursale_id = "";
    public $pagoClientes = array(), $tutor = "";

    protected $listeners = ['quitarE', 'selectProducto', 'selectProductoAll', 'generaPagosXEstudiante'];

    public function render()
    {
        if ($this->busqueda == "") {
            $this->reset(['estudiantes']);
        } else {
            $sucursale_id = Auth::user()->sucursale_id;
            $sql = "SELECT e.*, c.nombre curso, n.nombre nivel, t.nombre tutor FROM estudiantes e
        INNER JOIN cursos c on c.id = e.curso_id
        INNER JOIN nivelcursos n on n.id = c.nivelcurso_id
        INNER JOIN tutores t on t.id = e.tutore_id
        WHERE n.id = $sucursale_id 
        AND e.codigo LIKE '%" . $this->busqueda . "%'  AND e.tutore_id <> '0'
        OR e.nombre LIKE '%" . $this->busqueda . "%'  AND e.tutore_id <> '0'
        OR e.cedula LIKE '%" . $this->busqueda . "%'  AND e.tutore_id <> '0'
        OR t.nombre LIKE '%" . $this->busqueda . "%'  AND e.tutore_id <> '0'
        OR c.nombre LIKE '%" . $this->busqueda . "%' AND e.tutore_id <> '0'";
            $this->estudiantes = DB::select($sql);
        }
        return view('livewire.ventas.reservas')->extends('layouts.app');
    }

    public function updatedChecks()
    {
        if (count($this->checks) > 1) {
            $tutore_id = null;
            $i = 0;
            foreach ($this->checks as $id) {
                $e = Estudiante::find($id);

                if ($i > 0 && $e->tutore_id != $tutore_id) {
                    unset($this->checks[$i]);
                    $this->emit('warning', 'Los tutores de los estudiantes seleccionados no son los mismos.');
                    break;
                }
                $tutore_id = $e->tutore_id;
                $i++;
            }
        }
    }

    public function selEstudiantes()
    {
        // ============ AREA CLIENTES ===============================================================================
        $html = "";
        // LINEA PARA TODOS LOS CLIENTES
        $htmlFooter = "";

        $htmlFooter = $htmlFooter .
            "<tr style='vertical-align: middle'>
        <td>* PARA TODOS </td>";
        $class = 1;
        foreach ($this->eventos as $evento) {
            if (!esFeriado($evento->fecha)) {
                $htmlFooter = $htmlFooter .
                    "<td><select class='form-select form-select-sm foot" . $class . "' onchange='aplicaTodos(" . $class . ",this.value, $evento->id," . strtotime($evento->fecha) . ")'>";
                $htmlFooter = $htmlFooter . "<option value=''>Seleccione...</option>";
                foreach ($evento->detalleeventos as $detalle) {
                    $htmlFooter = $htmlFooter .
                        "<option value='" . $detalle->menu_id . "'>" . $detalle->menu->nombre . "</option>";
                }
                $htmlFooter = $htmlFooter . "</select></td>";
                $class++;
            }
        }
        $htmlFooter = $htmlFooter . "</tr>";
        // FIN DE LINEA
        $htmlProductos = "";
        $i = 0;
        $this->reset(['arrEstudiantes', 'busqueda', 'estudiantes']);
        foreach ($this->checks as $id) {
            $estudiante = Estudiante::find($id);
            $this->arrEstudiantes[] = $estudiante;
            $button = '<button class="btn btn-sm btn-warning" title="Quitar" onclick="quitarE(' . $i . ')"><i class="uil-trash"></i></button>';
            $i++;
            $html = $html . "<tr style='vertical-align: middle'>
        <td>" . $estudiante->codigo . "</td>
        <td>" . $estudiante->nombre . "</td>
        <td>" . $estudiante->tutore->nombre . "</td>
        <td>" . $estudiante->curso->nombre . "</td>
        <td>" . $button . "</td>
    </tr>";



            // TABLA DE PRODUCTOS POR ESTUDIANTES

            $htmlProductos = $htmlProductos .
                "<tr style='vertical-align: middle'>
            <td>" . $estudiante->nombre . "</td>";
            $class = 1;

            foreach ($this->eventos as $evento) {
                if (!esFeriado($evento->fecha)) {
                    $htmlProductos = $htmlProductos .
                        "<td><select id='select" . $class . "' class='form-select form-select-sm sl" . $class . "' onchange='aplicaFoot(" . $class . ",$evento->id,$estudiante->id,this.value," . strtotime($evento->fecha) . ")' >";
                    $htmlProductos = $htmlProductos . "<option value=''>Seleccione...</option>";
                    foreach ($evento->detalleeventos as $detalle) {
                        $htmlProductos = $htmlProductos .
                            "<option value='" . $detalle->menu_id . "' >" . $detalle->menu->nombre . "</option>";
                    }
                    $htmlProductos = $htmlProductos . "</select></td>";
                    $class++;
                }
            }
            $htmlProductos = $htmlProductos . "</tr>";
        }
        $this->emit('htmlEstudiantes', $html);
        $this->emit('htmlProductos', $htmlProductos);
        $this->emit('htmlFooter', $htmlFooter);
    }

    public function quitarE($i)
    {
        unset($this->checks[$i]);
        $this->checks = array_values($this->checks);
        $this->selEstudiantes();
        $this->reset(['arrPedidos']);

        $this->emit('muestraPedidos', $this->arrPedidos);
    }

    public function resetAll()
    {
        $this->reset(['busqueda', 'estudiantes']);
    }
    // FIN AREA CLIENTES ============================================================================


    public $menus, $selSemana, $eventos = null, $arrPedidos = [];
    public $formapagos = null, $moneda = null;

    public function mount()
    {
        $this->moneda = Moneda::first();
        $this->formapagos = Tipopago::all();
        $this->selSemana = date('W');
        if (date("l") == "Saturday") {
            $hoy = time();
            $semana_proxima = strtotime("+1 week", $hoy);
            $this->selSemana = date("W", $semana_proxima);
        }
        $this->selSemana = $this->selSemana . '-' . date('Y');
        $sucursale_id = Auth::user()->sucursale_id;
        $fechaReferencia = date('Y-m-d');
        if (strtotime(date('H:i')) > strtotime(Auth::user()->sucursale->horalimitepedidos)) {
            $fechaReferencia = date("Y-m-d", strtotime($fechaReferencia . "+ 1 days"));
        }

        $this->eventos = Evento::where('sucursale_id', $sucursale_id)
            ->where('semana', $this->selSemana)
            ->where('fecha', '>=', $fechaReferencia)
            ->get();
    }


    // AREA DE PRODUCTOS ============================================================================



    public function nombreDiaESP($fecha)
    {
        $dias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
        $nombre_dia = $dias[date('w', strtotime($fecha))];
        return $nombre_dia;
    }

    public function selectProducto($evento_id, $estudiante_id, $menu_id, $fecha)
    {
        $posicion = 0;
        foreach ($this->arrPedidos as $pedido) {
            if ($pedido[0] == $estudiante_id && $pedido[2] == $evento_id) {
                unset($this->arrPedidos[$posicion]);
                $this->arrPedidos = array_values($this->arrPedidos);
            }
            $posicion++;
        }
        if ($menu_id != "") {


            $precios = $this->subtotalXCliente($estudiante_id, $menu_id);
            if ($precios) {
                $this->arrPedidos[] = array($estudiante_id, $menu_id, $evento_id, $precios[0], $fecha, $precios[1], $precios[2]);
            } else {
                $this->emit('warning', 'No exite precio establecido para este Producto.');
            }
        }
    }

    public function selectProductoAll($evento_id, $menu_id, $fecha)
    {
        foreach ($this->checks as $estudiante) {
            $posicion = 0;
            foreach ($this->arrPedidos as $pedido) {
                if ($pedido[0] == $estudiante && $pedido[2] == $evento_id) {
                    unset($this->arrPedidos[$posicion]);
                    $this->arrPedidos = array_values($this->arrPedidos);
                }
                $posicion++;
            }
            if ($menu_id != "") {
                $precios = $this->subtotalXCliente($estudiante, $menu_id);
                if ($precios) {
                    $this->arrPedidos[] = array($estudiante, $menu_id, $evento_id, $precios[0], $fecha, $precios[1], $precios[2]);
                } else {
                    $this->emit('warning', 'No exite precio establecido para este Producto.');
                }
            }
        }
    }


    public function subtotalXCliente($estudiante_id, $menu_id)
    {
        $estudiante = Estudiante::find($estudiante_id);
        $this->tutor = $estudiante->tutore->nombre;
        $nivelcurso_id = $estudiante->curso->nivelcurso_id;
        $sql = "SELECT pm.precio, pm.preciopm, pm.cantmin FROM menus m
        INNER JOIN tipomenus tm on tm.id = m.tipomenu_id
        INNER JOIN preciomenus pm on pm.tipomenu_id = tm.id
        WHERE m.id = $menu_id
        AND pm.nivelcurso_id = $nivelcurso_id";
        $resultado = DB::select($sql);
        $precios = [];
        foreach ($resultado as $item) {
            $precios[0] = $item->precio;
            $precios[1] = $item->preciopm;
            $precios[2] = $item->cantmin;
        }
        return $precios;
    }

    // FIN AREA PRODUCTOS ================================================================

    // AREA FORMA PAGO ===================================================================
    public $importeTotal = 0, $observaciones = "", $cfp = 1, $importeTotalFloat = 0, $comprobante, $detalleventa = array();
    public $conDescuento = array(), $sinDescuento = array();
    public function generaHtmlBodyPagos($matriz)
    {
        $detalleventa = array();
        $tbodyPagoClientes = "";
        $importeTotal = 0;
        foreach ($this->checks as $estudiante_id) {
            $estudiante = Estudiante::find($estudiante_id);
            $subtotal = 0;
            $cantidad = 0;

            foreach ($this->arrPedidos as $pedido) {

                $menu = Menu::find($pedido[1]);
                $cantidadPedido = 0;
                foreach ($matriz as $fila) {
                    if ($fila[0] == $pedido[2]) {
                        $cantidadPedido = $fila[1];
                    }
                    // break;
                }
                if ($pedido[0] == $estudiante_id) {
                    $cantidad++;
                    if ($cantidadPedido >= $pedido[6] && $this->checks > 2) {
                        // if ($this->arrPedidos > 2) {
                        $subtotal = $subtotal + $pedido[5];

                        $descuento = 'NO';
                        if ($pedido[5] != $pedido[3]) {
                            $descuento = 'SI';
                            $detalleventa[] = array($menu->tipomenu->nombre, 1, $pedido[5], $pedido[5], $menu->tipomenu_id, 1);
                        } else {
                            $detalleventa[] = array($menu->tipomenu->nombre, 1, $pedido[5], $pedido[5], $menu->tipomenu_id, 0);
                        }
                        $this->conDescuento[] = array($estudiante->id, $estudiante->codigo, $estudiante->nombre, $estudiante->curso->nombre, $menu->tipomenu_id, $pedido[5], $descuento);
                    } else {
                        $subtotal = $subtotal + $pedido[3];
                        $detalleventa[] = array($menu->tipomenu->nombre, 1, $pedido[3], $pedido[3], $menu->tipomenu_id, 0);
                        $this->sinDescuento[] = array($estudiante->id, $estudiante->codigo, $estudiante->nombre, $estudiante->curso->nombre, $menu->tipomenu_id, $pedido[3], 'NO');
                    }
                }
            }
            $this->reset(['detalleventa']);
            $this->detalleventa = $detalleventa;
            $importeTotal = $importeTotal + $subtotal;
            if ($cantidad > 0) {
                $tbodyPagoClientes = $tbodyPagoClientes . "<tr>
            <td>" . $estudiante->nombre . "</td>
            <td>" . $estudiante->tutore->nombre . "</td>
            <td>" . $estudiante->curso->nombre . " - " . $estudiante->curso->nivelcurso->nombre . "</td>
            <td align='center'>" . $cantidad . "</td>
            <td align='right'>" . number_format($subtotal, 2, ',', ' ') . "</td>
        </tr>";
            }
        }
        $this->importeTotal = $importeTotal;
        $this->importeTotalFloat = number_format($this->importeTotal, 2, ',', ' ');
        $tbodyPagoClientes = $tbodyPagoClientes . "<tr style='background-color: #cef5ea;'>
        <td colspan='3'></td>
        <td align='right'><strong>TOTAL " . $this->moneda->abreviatura . ":</strong></td>
        <td align='right'><strong>" . number_format($this->importeTotal, 2, ',', ' ') . "</strong></td>
    </tr>";

        return $tbodyPagoClientes;
    }
    // FIN AREA FORMA PAGO ===============================================================
    public function generaPagosXEstudiante()
    {
        $this->emit('loading');
        $matriz = [];
        foreach ($this->eventos as $evento) {
            $c = 0;
            foreach ($this->arrPedidos as $pedido) {
                if ($evento->id == $pedido[2]) {
                    $c++;
                }
            }
            $matriz[] = array($evento->id, $c);
        }
        $this->emit('muestraPedidos', $matriz);

        $htmlClientePagos = $this->generaHtmlBodyPagos($matriz);
        $this->emit('htmlPagoClientes', $htmlClientePagos);
    }

    public $datosVentaRecibo = "";

    public function registrarReserva()
    {
        if (count($this->arrPedidos) > 0) {
            $this->emit('loading');
            DB::beginTransaction();
            try {
                $row = "";
                $tipopago = Tipopago::find($this->cfp);
                $venta = Venta::create([
                    "fecha" => date('Y-m-d'),
                    "cliente" => $this->tutor,
                    "estadopago_id" => 1,
                    "tipopago_id" => $tipopago->id,
                    "importe" => $this->importeTotal * $tipopago->factor,
                    "sucursale_id" => Auth::user()->sucursale_id,
                    "plataforma" => "admin",
                    "observaciones" => $this->observaciones,
                    "user_id" => Auth::user()->id,
                ]);
                $this->datosVentaRecibo = $venta->id . "|" . date('Y-m-d') . "|" . Auth::user()->name . "|" . $tipopago->abreviatura;
                foreach ($this->detalleventa as $dventa) {
                    $observacion = "";
                    if ($dventa[5]) {
                        $observacion = "DESCUENTO";
                    }
                    $detalleventa = Detalleventa::create([
                        'venta_id' => $venta->id,
                        'descripcion' => $dventa[0],
                        'producto_id' => 3,
                        'tipomenu_id' => $dventa[4],
                        'cantidad' => $dventa[1],
                        'preciounitario' => $dventa[2],
                        'subtotal' => $dventa[3],
                        'observacion' => $observacion,
                    ]);
                }

                foreach ($this->checks as $estudiante_id) {
                    $lonchera = Lonchera::create([
                        "fecha" => date('Y-m-d'),
                        "estudiante_id" => $estudiante_id,
                        "venta_id" => $venta->id,
                        "habilitado" => 0,
                    ]);



                    foreach ($this->arrPedidos as $pedido) {
                        $menuX = Menu::find($pedido[1]);
                        if ($pedido[0] == $estudiante_id) {
                            $detalle = Detallelonchera::create([
                                "fecha" => date('Y-m-d', $pedido[4]),
                                "tipomenu_id" => $menuX->tipomenu_id,
                                "menu_id" =>$pedido[1],
                                "lonchera_id" => $lonchera->id,
                                "entregado" => 0
                            ]);
                        }
                    }

                    switch ($tipopago->nombre) {
                        case 'CREDITO': {
                                $lonchera->habilitado = 1;
                                $lonchera->save();
                                $estudiante = Estudiante::find($estudiante_id);
                                $credito = null;
                                if ($estudiante->esestudiante == 0) {
                                    $credito = Creditoprofesore::create([
                                        "estudiante_id" => $estudiante->id,
                                        "venta_id" => $venta->id,
                                        "sucursale_id" => Auth::user()->sucursale_id,
                                    ]);
                                }
                            }
                            break;
                        case 'EFECTIVO - LOCAL': {
                                $pago = Pago::create([
                                    "fecha" => date('Y-m-d'),
                                    "recibo" => 0,
                                    "tipopago_id" => $tipopago->id,
                                    "tipopago" => $tipopago->nombre,
                                    "sucursal_id" => Auth::user()->sucursale_id,
                                    "sucursal" => Auth::user()->sucursale->nombre,
                                    "importe" => $this->importeTotal * $tipopago->factor,
                                    "venta_id" => $venta->id,
                                    "estadopago_id" => 2,
                                    "user_id" => Auth::user()->id,
                                    "tipoinicial" => $tipopago->nombre,
                                ]);
                                $lonchera->habilitado = 1;
                                $lonchera->save();
                                $venta->estadopago_id = 2;
                                $venta->save();
                            }

                            break;
                        case 'GASTO ADMINISTRATIVO': {
                                $pago = Pago::create([
                                    "fecha" => date('Y-m-d'),
                                    "recibo" => 0,
                                    "tipopago_id" => $tipopago->id,
                                    "tipopago" => $tipopago->nombre,
                                    "sucursal_id" => Auth::user()->sucursale_id,
                                    "sucursal" => Auth::user()->sucursale->nombre,
                                    "importe" => $this->importeTotal * $tipopago->factor,
                                    "venta_id" => $venta->id,
                                    "estadopago_id" => 2,
                                    "user_id" => Auth::user()->id,
                                    "tipoinicial" => $tipopago->nombre,
                                ]);
                                $lonchera->habilitado = 1;
                                $lonchera->save();
                                $venta->estadopago_id = 2;
                                $venta->save();
                            }

                            break;
                        case "PAGO QR": {
                               
                                    $pago = Pago::create([
                                        "fecha" => date('Y-m-d'),
                                        "recibo" => 0,
                                        "tipopago_id" => $tipopago->id,
                                        "tipopago" => $tipopago->nombre,
                                        "sucursal_id" => Auth::user()->sucursale_id,
                                        "sucursal" => Auth::user()->sucursale->nombre,
                                        "importe" => $this->importeTotal * $tipopago->factor,
                                        "venta_id" => $venta->id,
                                        "estadopago_id" => 2,
                                        "user_id" => Auth::user()->id,
                                        "tipoinicial" => $tipopago->nombre,
                                    ]);
                                    $comprobante = 'img/admin/noImagen.jpg';

                                    $lonchera->habilitado = 1;
                                    $lonchera->save();
                                    $venta->estadopago_id = 2;
                                    $venta->save();
                                    $pago->comprobante = $comprobante;
                                    $pago->save();
                               
                            }
                            break;
                        case "TRANSFERENCIA BANCARIA": {
                              
                                    $pago = Pago::create([
                                        "fecha" => date('Y-m-d'),
                                        "recibo" => 0,
                                        "tipopago_id" => $tipopago->id,
                                        "tipopago" => $tipopago->nombre,
                                        "sucursal_id" => Auth::user()->sucursale_id,
                                        "sucursal" => Auth::user()->sucursale->nombre,
                                        "importe" => $this->importeTotal * $tipopago->factor,
                                        "venta_id" => $venta->id,
                                        "estadopago_id" => 2,
                                        "user_id" => Auth::user()->id,
                                        "tipoinicial" => $tipopago->nombre,
                                    ]);
                                    $comprobante = 'img/admin/noImagen.jpg';

                                    $lonchera->habilitado = 1;
                                    $lonchera->save();
                                    $venta->estadopago_id = 2;
                                    $venta->save();
                                    $pago->comprobante = $comprobante;
                                    $pago->save();
                              
                            }
                            break;
                    }

                    // PAGO CON COMPROBANTES
                    // switch ($tipopago->nombre) {
                    //     case 'CREDITO': {
                    //             $lonchera->habilitado = 1;
                    //             $lonchera->save();
                    //             $estudiante = Estudiante::find($estudiante_id);
                    //             $credito = null;
                    //             if ($estudiante->esestudiante == 0) {
                    //                 $credito = Creditoprofesore::create([
                    //                     "estudiante_id" => $estudiante->id,
                    //                     "venta_id" => $venta->id,
                    //                     "sucursale_id" => Auth::user()->sucursale_id,
                    //                 ]);
                    //             }
                    //         }
                    //         break;
                    //     case 'EFECTIVO - LOCAL': {
                    //             $pago = Pago::create([
                    //                 "fecha" => date('Y-m-d'),
                    //                 "recibo" => 0,
                    //                 "tipopago_id" => $tipopago->id,
                    //                 "tipopago" => $tipopago->nombre,
                    //                 "sucursal_id" => Auth::user()->sucursale_id,
                    //                 "sucursal" => Auth::user()->sucursale->nombre,
                    //                 "importe" => $this->importeTotal * $tipopago->factor,
                    //                 "venta_id" => $venta->id,
                    //                 "estadopago_id" => 2,
                    //                 "user_id" => Auth::user()->id,
                    //                 "tipoinicial" => $tipopago->nombre,
                    //             ]);
                    //             $lonchera->habilitado = 1;
                    //             $lonchera->save();
                    //             $venta->estadopago_id = 2;
                    //             $venta->save();
                    //         }

                    //         break;
                    //     case 'GASTO ADMINISTRATIVO': {
                    //             $pago = Pago::create([
                    //                 "fecha" => date('Y-m-d'),
                    //                 "recibo" => 0,
                    //                 "tipopago_id" => $tipopago->id,
                    //                 "tipopago" => $tipopago->nombre,
                    //                 "sucursal_id" => Auth::user()->sucursale_id,
                    //                 "sucursal" => Auth::user()->sucursale->nombre,
                    //                 "importe" => $this->importeTotal * $tipopago->factor,
                    //                 "venta_id" => $venta->id,
                    //                 "estadopago_id" => 2,
                    //                 "user_id" => Auth::user()->id,
                    //                 "tipoinicial" => $tipopago->nombre,
                    //             ]);
                    //             $lonchera->habilitado = 1;
                    //             $lonchera->save();
                    //             $venta->estadopago_id = 2;
                    //             $venta->save();
                    //         }

                    //         break;
                    //     case "PAGO QR": {
                    //             if ($this->comprobante) {
                    //                 $this->validate([
                    //                     'comprobante' => 'image|max:1024', // 1MB Max
                    //                 ]);
                    //                 $pago = Pago::create([
                    //                     "fecha" => date('Y-m-d'),
                    //                     "recibo" => 0,
                    //                     "tipopago_id" => $tipopago->id,
                    //                     "tipopago" => $tipopago->nombre,
                    //                     "sucursal_id" => Auth::user()->sucursale_id,
                    //                     "sucursal" => Auth::user()->sucursale->nombre,
                    //                     "importe" => $this->importeTotal * $tipopago->factor,
                    //                     "venta_id" => $venta->id,
                    //                     "estadopago_id" => 2,
                    //                     "user_id" => Auth::user()->id,
                    //                     "tipoinicial" => $tipopago->nombre,
                    //                 ]);
                    //                 $file = $this->comprobante->storeAs('depositos/' . $tipopago->abreviatura, $pago->id . "." . $this->comprobante->extension());
                    //                 $comprobante = 'depositos/' . $tipopago->abreviatura . '/' . $pago->id . "." . $this->comprobante->extension();

                    //                 $lonchera->habilitado = 1;
                    //                 $lonchera->save();
                    //                 $venta->estadopago_id = 2;
                    //                 $venta->save();
                    //                 $pago->comprobante = $comprobante;
                    //                 $pago->save();
                    //             }
                    //         }
                    //         break;
                    //     case "TRANSFERENCIA BANCARIA": {
                    //             if ($this->comprobante) {
                    //                 $this->validate([
                    //                     'comprobante' => 'image|max:1024', // 1MB Max
                    //                 ]);
                    //                 $pago = Pago::create([
                    //                     "fecha" => date('Y-m-d'),
                    //                     "recibo" => 0,
                    //                     "tipopago_id" => $tipopago->id,
                    //                     "tipopago" => $tipopago->nombre,
                    //                     "sucursal_id" => Auth::user()->sucursale_id,
                    //                     "sucursal" => Auth::user()->sucursale->nombre,
                    //                     "importe" => $this->importeTotal * $tipopago->factor,
                    //                     "venta_id" => $venta->id,
                    //                     "estadopago_id" => 2,
                    //                     "user_id" => Auth::user()->id,
                    //                     "tipoinicial" => $tipopago->nombre,
                    //                 ]);
                    //                 $file = $this->comprobante->storeAs('depositos/' . $tipopago->abreviatura, $pago->id . "." . $this->comprobante->extension());
                    //                 $comprobante = 'depositos/' . $tipopago->abreviatura . '/' . $pago->id . "." . $this->comprobante->extension();

                    //                 $lonchera->habilitado = 1;
                    //                 $lonchera->save();
                    //                 $venta->estadopago_id = 2;
                    //                 $venta->save();
                    //                 $pago->comprobante = $comprobante;
                    //                 $pago->save();
                    //             }
                    //         }
                    //         break;
                    // }
                }
                DB::commit();

                // GENERACION DE RECIBOS
                $recibos = array();

                foreach ($this->checks as $estudiante_id) {
                    $reciboEstudiante = "";
                    $completoD = array("", "", "", 'ALMUERZO COMPLETO', 0, 0, 0, "");
                    $simpleD = array("", "", "", 'ALMUERZO SIMPLE', 0, 0, 0, "");
                    $extraD = array("", "", "", 'EXTRA', 0, 0, 0, "");
                    foreach ($this->conDescuento as $row) {
                        if ($row[0] == $estudiante_id) {
                            switch ($row[4]) {
                                case 1: {
                                        $completoD[0] = $row[1];
                                        $completoD[1] = $row[2];
                                        $completoD[2] = $row[3];
                                        $completoD[4]++;
                                        $completoD[5] = $row[5];
                                        $completoD[6] = $completoD[6] + $row[5];
                                        $completoD[7] = $row[6];
                                    }
                                    break;
                                case 2: {
                                        $simpleD[0] = $row[1];
                                        $simpleD[1] = $row[2];
                                        $simpleD[2] = $row[3];
                                        $simpleD[4]++;
                                        $simpleD[5] = $row[5];
                                        $simpleD[6] = $simpleD[6] + $row[5];
                                        $simpleD[7] = $row[6];
                                    }
                                    break;
                                case 3: {
                                        $extraD[0] = $row[1];
                                        $extraD[1] = $row[2];
                                        $extraD[2] = $row[3];
                                        $extraD[4]++;
                                        $extraD[5] = $row[5];
                                        $extraD[6] = $extraD[6] + $row[5];
                                        $extraD[7] = $row[6];
                                    }
                                    break;
                            }
                        }
                    }
                    if ($completoD[1] > 0) {
                        $fila = implode('|', $completoD);
                        $reciboEstudiante = $reciboEstudiante . $fila . "~";
                    }
                    if ($simpleD[1] > 0) {
                        $fila = implode('|', $simpleD);
                        $reciboEstudiante = $reciboEstudiante . $fila . "~";
                    }
                    if ($extraD[1] > 0) {
                        $fila = implode('|', $extraD);
                        $reciboEstudiante = $reciboEstudiante . $fila . "~";
                    }

                    $completo = array("", "", "", 'ALMUERZO COMPLETO', 0, 0, 0, "NO");
                    $simple = array("", "", "", 'ALMUERZO SIMPLE', 0, 0, 0, "NO");
                    $extra = array("", "", "", 'EXTRA', 0, 0, 0, "NO");
                    foreach ($this->sinDescuento as $row) {
                        if ($row[0] == $estudiante_id) {
                            switch ($row[4]) {
                                case 1: {
                                        $completo[0] = $row[1];
                                        $completo[1] = $row[2];
                                        $completo[2] = $row[3];
                                        $completo[4]++;
                                        $completo[5] = $row[5];
                                        $completo[6] = $completo[6] + $row[5];
                                    }
                                    break;
                                case 2: {
                                        $simple[0] = $row[1];
                                        $simple[1] = $row[2];
                                        $simple[2] = $row[3];
                                        $simple[4]++;
                                        $simple[5] = $row[5];
                                        $simple[6] = $simple[6] + $row[5];
                                    }
                                    break;
                                case 3: {
                                        $extra[0] = $row[1];
                                        $extra[1] = $row[2];
                                        $extra[2] = $row[3];
                                        $extra[4]++;
                                        $extra[5] = $row[5];
                                        $extra[6] = $extra[6] + $row[5];
                                    }
                                    break;
                            }
                        }
                    }
                    if ($completo[1] > 0) {
                        $fila = implode('|', $completo);
                        $reciboEstudiante = $reciboEstudiante . $fila . "~";
                    }
                    if ($simple[1] > 0) {
                        $fila = implode('|', $simple);
                        $reciboEstudiante = $reciboEstudiante . $fila . "~";
                    }
                    if ($extra[1] > 0) {
                        $fila = implode('|', $extra);
                        $reciboEstudiante = $reciboEstudiante . $fila . "~";
                    }
                    $longitud = strlen($reciboEstudiante);
                    $reciboEstudiante = substr($reciboEstudiante, 0, $longitud - 1);
                    $recibos[] = $reciboEstudiante;
                }

                foreach ($recibos as $recibo) {
                    $this->emit('imprimir', $this->datosVentaRecibo . "||" . $recibo);
                }

                $this->reset(['arrPedidos', 'checks', 'arrEstudiantes', 'tutor', 'importeTotal', 'importeTotalFloat', 'observaciones']);
                return redirect()->route('reservas.nueva')
                    ->with('finishTransaction', 'Los datos fueron registrados correctamente');
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->emit('error', $th->getMessage());
            }
        } else {
            $this->emit('warning', 'Debe seleccionar Clientes y/o Productos');
        }
    }

    public function updatedCfp()
    {
        if ($this->cfp == 2 || $this->cfp == 3) {
            $this->emit('comprobante', 'show');
        } else {
            $this->emit('comprobante', 'hide');
        }
    }
}
