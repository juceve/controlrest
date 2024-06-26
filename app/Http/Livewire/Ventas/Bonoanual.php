<?php

namespace App\Http\Livewire\Ventas;

use App\Models\Bonoanuale;
use App\Models\Detalleventa;
use App\Models\Estudiante;
use App\Models\Moneda;
use App\Models\Pago;
use App\Models\Tipobonoanuale;
use App\Models\Tipomenu;
use App\Models\Tipopago;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\Reader\Xls\Color\BIFF5;

use function GuzzleHttp\Promise\all;

class Bonoanual extends Component
{
    use WithFileUploads;
    public $estudiantes = null, $busqueda = "", $checks = [], $arrEstudiantes = [], $tutor = "";
    public $formapagos = null, $moneda = null, $arrPedidos = array(), $browseMobile;

    protected $listeners = ['quitarE', 'selectProducto', 'generaPagosXEstudiante', 'calculaPrecio', 'updBrowse'];

    public function mount()
    {
        $this->moneda = Moneda::first();
        $this->formapagos = Tipopago::all();
        $this->gestion = date('Y');
    }

    public function resetAll()
    {
        $this->reset(['busqueda', 'estudiantes']);
    }
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
        AND e.codigo LIKE '%" . $this->busqueda . "%'  AND e.tutore_id <> '0' AND e.verificado = 1
        OR e.nombre LIKE '%" . $this->busqueda . "%'  AND e.tutore_id <> '0' AND e.verificado = 1
        OR e.cedula LIKE '%" . $this->busqueda . "%'  AND e.tutore_id <> '0' AND e.verificado = 1
        OR t.nombre LIKE '%" . $this->busqueda . "%'  AND e.tutore_id <> '0' AND e.verificado = 1
        OR c.nombre LIKE '%" . $this->busqueda . "%' AND e.tutore_id <> '0' AND e.verificado = 1";
            $this->estudiantes = DB::select($sql);
        }

        return view('livewire.ventas.bonoanual')->extends('layouts.app');
    }

    public function updBrowse($status)
    {
        $this->browseMobile = $status;
    }

    public function selEstudiantes()
    {
        // ============ AREA CLIENTES ===============================================================================
        $html = "";

        $tipomenus = Tipobonoanuale::join('tipomenus', 'tipomenus.id', '=', 'tipobonoanuales.tipomenu_id')
            ->where('tipomenus.sucursale_id', Auth::user()->sucursale_id)
            ->select('tipomenus.id', 'tipomenus.nombre', 'tipobonoanuales.precio')->get();

        // FIN DE LINEA
        $htmlProductos = "";
        $i = 0;
        $this->reset(['arrEstudiantes', 'busqueda', 'estudiantes']);
        foreach ($this->checks as $id) {
            $estudiante = Estudiante::find($id);
            $this->arrEstudiantes[] = $estudiante;
            $this->tutor = $estudiante->tutore->nombre;
            $button = '<button class="btn btn-sm btn-warning" title="Quitar" onclick="quitarE(' . $i . ')"><i class="uil-trash"></i></button>';
            $i++;
            $html = $html . "<tr style='vertical-align: middle'>
                <td>" . $estudiante->codigo . "</td>
                <td>" . $estudiante->nombre . "</td>
                <td>" . $estudiante->tutore->nombre . "</td>
                <td>" . $estudiante->curso->nombre . "</td>
                <td>" . $button . "</td>
            </tr>";

            $htmlProductos = $htmlProductos .
                "<tr style='vertical-align: middle'>
            <td>" . $estudiante->nombre . "</td>";
            $class = 1;


            $htmlProductos = $htmlProductos .
                "<td><select id='select" . $class . "' class='form-select form-select-sm sl" . $class . "' onchange='selProducto($estudiante->id,this.value)'>";
            $htmlProductos = $htmlProductos . "<option value=''>Seleccione...</option>";
            foreach ($tipomenus as $tipomenu) {
                $htmlProductos = $htmlProductos .
                    "<option value='" . $tipomenu->id . "' >" . $tipomenu->nombre . "</option>";
            }
            $htmlProductos = $htmlProductos . "</select></td>";
            $class++;

            $htmlProductos = $htmlProductos . "</tr>";
        }
        $this->emit('htmlEstudiantes', $html);
        $this->emit('htmlProductos', $htmlProductos);
    }
    public function quitarE($i)
    {
        unset($this->checks[$i]);
        $this->checks = array_values($this->checks);
        $this->selEstudiantes();
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

    public function selectProducto($estudiante_id, $tipomenu_id)
    {
        $posicion = 0;
        foreach ($this->arrPedidos as $pedido) {
            if ($pedido[0] == $estudiante_id) {
                unset($this->arrPedidos[$posicion]);
                $this->arrPedidos = array_values($this->arrPedidos);
            }
            $posicion++;
        }
        if ($tipomenu_id != "") {
            $this->arrPedidos[] = array($estudiante_id, $tipomenu_id);
        }

        $htmlClientePagos = $this->generaHtmlBodyPagos();
        $this->emit('htmlPagoClientes', $htmlClientePagos);
    }
    public $importeTotal = 0, $observaciones = "", $cfp = 1, $importeTotalFloat = 0, $comprobante, $detalleventa = array(), $tipobono;

    public function generaHtmlBodyPagos()
    {
        $tbodyPagoClientes = "";
        $this->reset(['importeTotal']);
        $this->reset(['detalleventa']);
        foreach ($this->arrPedidos as $pedido) {
            $estudiante = Estudiante::find($pedido[0]);


            if ($pedido[1] != "") {
                $tipomenu = Tipomenu::find($pedido[1]);
                $tipobonoanual = Tipobonoanuale::where('tipomenu_id', $tipomenu->id)->first();
                $this->tipobono = $tipobonoanual;
                $tbodyPagoClientes = $tbodyPagoClientes . "<tr>
            <td>" . $estudiante->nombre . "</td>
            <td>" . $estudiante->tutore->nombre . "</td>
            <td align='center'>" . $this->gestion . "</td>
            <td align='center'>" . $tipomenu->nombre . "</td>
            <td align='right'>" . number_format($tipobonoanual->precio, 2, ',', '.') . "</td>
            </tr>";
                $this->importeTotal = $this->importeTotal + $tipobonoanual->precio;
            }

            $this->detalleventa[] = array('BONO ANUAL ' . $this->gestion . ' - ' . $tipomenu->nombre, 1, $tipobonoanual->precio, $tipobonoanual->precio, $tipomenu->id);
        }

        $tbodyPagoClientes = $tbodyPagoClientes . "<tr style='background-color: #cef5ea;'>
        <td colspan='3'></td>
        <td align='right'><strong>TOTAL " . $this->moneda->abreviatura . ":</strong></td>
        <td align='right'><strong>" . number_format($this->importeTotal, 2, ',', '.') . "</strong></td>
        </tr>";

        return $tbodyPagoClientes;
    }

    public function generaPagosXEstudiante()
    {
        $this->emit('loading');

        $htmlClientePagos = $this->generaHtmlBodyPagos();
        $this->emit('htmlPagoClientes', $htmlClientePagos);
    }


    public $arrPrecios = array();

    public function calculaPrecio($estudiante_id, $precio)
    {
        $posicion = 0;
        $this->reset(['importeTotal']);
        foreach ($this->arrPrecios as $precio) {
            if ($precio[0] == $estudiante_id) {
                unset($this->arrPrecios[$posicion]);
                $this->arrPrecios = array_values($this->arrPrecios);
            }
            $posicion++;
        }
        if ($precio != "") {
            $this->arrPrecios[] = array($estudiante_id, $precio);
        }
        foreach ($this->arrPrecios as $precio) {
            $this->importeTotal = $this->importeTotal + $precio[1];
        }
    }

    public $gestion = "", $contenedor = array(), $datosVentaRecibo = "";

    public function registrarCompra()
    {
        if (count($this->arrPedidos) > 0) {
            if ($this->cfp == 2 || $this->cfp == 3) {
                if (is_null($this->comprobante)) {
                    $this->emit('warning', 'Debe adjuntar un comprobante.');
                    return "";
                }
            }

            $this->emit('loading');
            DB::beginTransaction();
            try {
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
                foreach ($this->detalleventa as $dventa) {

                    $detalleventa = Detalleventa::create([
                        'venta_id' => $venta->id,
                        'descripcion' => $dventa[0],
                        'producto_id' => 1,
                        'tipomenu_id' => $dventa[4],
                        'cantidad' => $dventa[1],
                        'preciounitario' => $dventa[2],
                        'subtotal' => $dventa[3],
                    ]);
                }


                $this->datosVentaRecibo = $venta->id . "|" . date('Y-m-d') . "|" . Auth::user()->name . "|" . $tipopago->abreviatura;

                foreach ($this->arrPedidos as $pedido) {

                    $bonoanual = Bonoanuale::create([
                        "gestion" => $this->gestion,
                        "estudiante_id" => $pedido[0],
                        "tipomenu_id" => $pedido[1],
                        "venta_id" => $venta->id,
                        "sucursale_id" => Auth::user()->sucursale_id,
                        "estado" => 0
                    ]);

                    $tipobono = Tipobonoanuale::where('tipomenu_id', $bonoanual->tipomenu_id)->first();

                    $this->contenedor[] = array($bonoanual->estudiante_id, $bonoanual->estudiante->codigo, $bonoanual->estudiante->nombre, $bonoanual->estudiante->curso->nombre, "Bono " . $bonoanual->tipomenu->nombre . ' ' . $bonoanual->gestion, 1, $tipobono->precio, 'NO', $tipobono->precio);

                    switch ($tipopago->nombre) {
                        case 'CREDITO':
                            $bonoanual->estado = 1;
                            $bonoanual->save();
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
                                $bonoanual->estado = 1;
                                $bonoanual->venta_id = $venta->id;
                                $bonoanual->save();
                                $venta->estadopago_id = 2;
                                $venta->save();
                            }

                            break;
                        case 'GASTO ADMINISTRATIVO':
                            $pago = Pago::create([
                                "fecha" => date('Y-m-d'),
                                "recibo" => 0,
                                "tipopago_id" => $tipopago->id,
                                "tipopago" => $tipopago->nombre,
                                "sucursal_id" => Auth::user()->sucursale_id,
                                "sucursal" => Auth::user()->sucursale->nombre,
                                "importe" => $this->importeTotal * $tipopago->factor,
                                "venta_id" => $venta->id,
                                "estadopago_id" => 1,
                                "user_id" => Auth::user()->id,
                                "tipoinicial" => $tipopago->nombre,
                            ]);
                            $bonoanual->estado = 1;
                            $bonoanual->venta_id = $venta->id;
                            $venta->estadopago_id = 2;
                            $venta->save();
                            $pago->estadopago_id = 2;
                            $pago->save();
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
                                    "estadopago_id" => 1,
                                    "user_id" => Auth::user()->id,
                                    "tipoinicial" => $tipopago->nombre,
                                ]);
                                $file = $this->comprobante->storeAs('depositos/' . $tipopago->abreviatura, $pago->id . "." . $this->comprobante->extension());
                                $comprobante = 'storage/depositos/' . $tipopago->abreviatura . '/' . $pago->id . "." . $this->comprobante->extension();

                                $bonoanual->estado = 1;
                                $bonoanual->venta_id = $venta->id;
                                $venta->estadopago_id = 2;
                                $venta->save();
                                $pago->estadopago_id = 2;
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
                                    "estadopago_id" => 1,
                                    "user_id" => Auth::user()->id,
                                    "tipoinicial" => $tipopago->nombre,
                                ]);
                                $file = $this->comprobante->storeAs('depositos/' . $tipopago->abreviatura, $pago->id . "." . $this->comprobante->extension());
                                $comprobante = 'storage/depositos/' . $tipopago->abreviatura . '/' .  $pago->id . "." . $this->comprobante->extension();


                                $bonoanual->estado = 1;
                                $bonoanual->venta_id = $venta->id;
                                $venta->estadopago_id = 2;
                                $venta->save();
                                $pago->estadopago_id = 2;
                                $pago->comprobante = $comprobante;
                                $pago->save();
                            }
                            break;
                    }
                }

                DB::commit();

                if ($this->browseMobile) {
                    $detalleRecibo = "";
                    foreach ($this->contenedor as $item) {
                        $detalleRecibo .= implode("|", $item);
                        $detalleRecibo .= "^";
                    }
                    $detalleRecibo = substr($detalleRecibo, 0, -1);

                    $this->emit('imprimir', $this->datosVentaRecibo . "~" . $detalleRecibo);
                } else {
                    foreach ($this->contenedor as $item) {
                        $recibo = implode('|', $item);
                        $this->emit('imprimir', $this->datosVentaRecibo . "~" . $recibo);
                    }
                }
                return redirect()->route('ventas.bonoanual')
                    ->with('finishTransaction', 'Los datos fueron registrados correctamente');
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->emit(('unLoading'));
                $this->emit('error', $th->getMessage());
            }
        } else {
            $this->emit(('unLoading'));
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
