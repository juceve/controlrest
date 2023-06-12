<?php

namespace App\Http\Livewire\Ventas;

use App\Models\Bonofecha as ModelsBonofecha;
use App\Models\Detalleventa;
use App\Models\Estudiante;
use App\Models\Moneda;
use App\Models\Pago;
use App\Models\Preciomenu;
use App\Models\Tipobonoanuale;
use App\Models\Tipomenu;
use App\Models\Tipopago;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

use function GuzzleHttp\Promise\all;

class Bonofecha extends Component
{
    use WithFileUploads;
    public $estudiantes = null, $busqueda = "", $checks = [], $arrEstudiantes = [], $tutor = "";
    public $formapagos = null, $moneda = null, $arrPedidos = array();

    protected $listeners = ['quitarE', 'selectProducto', 'generaPagosXEstudiante', 'calculaPrecio'];

    public function mount()
    {
        $this->moneda = Moneda::first();
        $this->formapagos = Tipopago::all();
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
        AND e.codigo LIKE '%" . $this->busqueda . "%'  AND e.tutore_id <> '0'
        OR e.nombre LIKE '%" . $this->busqueda . "%'  AND e.tutore_id <> '0'
        OR e.cedula LIKE '%" . $this->busqueda . "%'  AND e.tutore_id <> '0'
        OR t.nombre LIKE '%" . $this->busqueda . "%'  AND e.tutore_id <> '0'
        OR c.nombre LIKE '%" . $this->busqueda . "%' AND e.tutore_id <> '0'";
            $this->estudiantes = DB::select($sql);
        }

        return view('livewire.ventas.bonofecha')->extends('layouts.app');
    }

    public function selEstudiantes()
    {
        // ============ AREA CLIENTES ===============================================================================
        $html = "";

        $tipomenus = Tipomenu::where('sucursale_id', Auth::user()->sucursale_id)->get();
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
    public $importeTotal = 0, $observaciones = "", $cfp = 1, $importeTotalFloat = 0, $comprobante, $detalleventa= array();

    public function generaHtmlBodyPagos()
    {
        if ($this->fechainicial != "" && $this->fechafinal != "") {
            $tbodyPagoClientes = "";
            $this->reset(['importeTotal','detalleventa']);
            foreach ($this->arrPedidos as $pedido) {
                $tipomenu = Tipomenu::find($pedido[1]);
                if ($pedido[1] != "") {

                    $estudiante = Estudiante::find($pedido[0]);

                    $nivelcurso = $estudiante->curso->nivelcurso->id;

                    $precioTipoMenu = Preciomenu::where('nivelcurso_id', $nivelcurso)
                        ->where('tipomenu_id', $pedido[1])
                        ->first();

                    $cantidadDias = contarDiasSemana($this->fechainicial, $this->fechafinal);
                    if ($cantidadDias >= $precioTipoMenu->cantmin && count($this->checks)>=2) {
                        $importeEstudiante = $cantidadDias * $precioTipoMenu->preciopm;
                    } else {
                        $importeEstudiante = $cantidadDias * $precioTipoMenu->precio;
                    }


                    $tbodyPagoClientes = $tbodyPagoClientes . "<tr>
                <td>" . $estudiante->nombre . "</td>
                <td align='center'>" . $tipomenu->nombre . "</td>   
                <td align='center'>" . $this->fechainicial . "</td>
                <td align='center'>" . $this->fechafinal . "</td>
                <td align='center'>" . $cantidadDias . "</td>            
                <td align='right'>" . number_format($importeEstudiante, 2, ',', '.') . "</td>         
                </tr>";
                    $this->importeTotal = $this->importeTotal + $importeEstudiante; 
                    $this->detalleventa[] = array('BONO '.$this->fechainicial.' - '.$this->fechafinal.' - '.$tipomenu->nombre,1,$importeEstudiante,$importeEstudiante);
                }
               
            }
            //<td align='center'><input type='number' onkeyup='calculaPrecio(" . $estudiante->id . ",this.value);' step='0.00' min='0' class='form-control form-control-sm text-end' id='precio" . $estudiante->id . "' value='0'></td>

            $tbodyPagoClientes = $tbodyPagoClientes . "<tr style='background-color: #cef5ea;'>
            <td colspan='4'></td>
            <td align='right'><strong>TOTAL " . $this->moneda->abreviatura . ":</strong></td>
            <td align='right'><strong>" . number_format($this->importeTotal, 2, ',', '.') . "</strong></td>
            </tr>";

            return $tbodyPagoClientes;
        }
    }

    public function generaPagosXEstudiante()
    {
        $this->emit('loading');

        $htmlClientePagos = $this->generaHtmlBodyPagos();
        $this->emit('htmlPagoClientes', $htmlClientePagos);
    }


    public $arrPrecios = array();


    public $fechainicial = "", $fechafinal = "";
    public function registrarCompra()
    {
        if (count($this->arrPedidos) > 0) {
            $this->emit('loading');
            DB::beginTransaction();
            try {
                $venta = Venta::create([
                    "fecha" => date('Y-m-d'),
                    "cliente" => $this->tutor,
                    "estadopago_id" => 1,
                    "importe" => $this->importeTotal,
                    "sucursale_id" => Auth::user()->sucursale_id,
                    "plataforma" => "admin",
                    "observaciones" => $this->observaciones,
                    "user_id" => Auth::user()->id,
                ]);

                foreach ($this->detalleventa as $dventa ) {                 

                    $detalleventa = Detalleventa::create([
                        'venta_id' => $venta->id,
                        'descripcion' => $dventa[0],
                        'cantidad' => $dventa[1],
                        'preciounitario' => $dventa[2],
                        'subtotal' => $dventa[3],
                    ]);
                }

                $tipopago = Tipopago::find($this->cfp);
                $pago = Pago::create([
                    "fecha" => date('Y-m-d'),
                    "recibo" => 0,
                    "tipopago_id" => $tipopago->id,
                    "tipopago" => $tipopago->nombre,
                    "sucursal_id" => Auth::user()->sucursale_id,
                    "sucursal" => Auth::user()->sucursale->nombre,
                    "importe" => $this->importeTotal,
                    "venta_id" => $venta->id,
                    "estadopago_id" => 1,
                    "user_id" => Auth::user()->id,
                    "tipoinicial" => $tipopago->nombre,
                ]);

                foreach ($this->arrPedidos as $pedido) {

                    $bonofecha = ModelsBonofecha::create([
                        "fechainicio" => $this->fechainicial,
                        "fechafin" => $this->fechafinal,
                        "estudiante_id" => $pedido[0],
                        "tipomenu_id" => $pedido[1],
                        "pago_id" => $pago->id,
                        "estado" => 0
                    ]);

                    switch ($tipopago->nombre) {
                        case 'CREDITO':
                            $bonofecha->estado = 1;
                            $bonofecha->save();
                            break;
                        case 'EFECTIVO - LOCAL':
                            $bonofecha->estado = 1;
                            $bonofecha->save();
                            $venta->estadopago_id = 2;
                            $venta->save();
                            $pago->estadopago_id = 2;
                            $pago->save();
                            break;
                        case 'GASTO ADMINISTRATIVO':
                            $bonofecha->estado = 1;
                            $bonofecha->save();
                            $venta->estadopago_id = 2;
                            $venta->importe = 0;
                            $venta->save();
                            $pago->estadopago_id = 2;
                            $pago->importe = 0;
                            $pago->save();
                            break;
                        case "PAGO QR": {
                                if ($this->comprobante) {
                                    $this->validate([
                                        'comprobante' => 'image|max:1024', // 1MB Max
                                    ]);
                                    $file = $this->comprobante->storeAs('depositos/' . $tipopago->abreviatura, $pago->id . "." . $this->comprobante->extension());
                                    $comprobante = 'depositos/' . $tipopago->abreviatura . '/' . $pago->id . "." . $this->comprobante->extension();

                                    $bonofecha->estado = 1;
                                    $bonofecha->save();
                                    $venta->estadopago_id = 2;
                                    $venta->save();
                                    $pago->estadopago_id = 2;
                                    $pago->comprobante = $comprobante;
                                    $pago->save();
                                }
                            }
                            break;
                        case "TRANSFERENCIA BANCARIA": {
                                if ($this->comprobante) {
                                    $this->validate([
                                        'comprobante' => 'image|max:1024', // 1MB Max
                                    ]);
                                    $file = $this->comprobante->storeAs('depositos/' . $tipopago->abreviatura, $pago->id . "." . $this->comprobante->extension());
                                    $comprobante = 'depositos/' . $tipopago->abreviatura . '/' . $pago->id . "." . $this->comprobante->extension();

                                    $bonofecha->estado = 1;
                                    $bonofecha->save();
                                    $venta->estadopago_id = 2;
                                    $venta->save();
                                    $pago->estadopago_id = 2;
                                    $pago->comprobante = $comprobante;
                                    $pago->save();
                                }
                            }
                            break;
                    }
                }
                DB::commit();
                // $this->reset(['arrPedidos', 'checks', 'arrEstudiantes', 'tutor', 'importeTotal', 'importeTotalFloat', 'observaciones']);
                // $this->generaHtmlBodyPagos();
                return redirect()->route('ventas.bonofecha')
                    ->with('finishTransaction', 'Los datos fueron registrados correctamente');
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->emit('unLoading');
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
