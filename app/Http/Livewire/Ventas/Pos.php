<?php

namespace App\Http\Livewire\Ventas;

use App\Models\Detalleventa;
use App\Models\Entregalounch;
use App\Models\Evento;
use App\Models\Menu;
use App\Models\Pago;
use App\Models\Preciomenu;
use App\Models\Tipopago;
use App\Models\Venta;
use App\Models\Ventasconfig;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Livewire\WithFileUploads;

class Pos extends Component
{
    use WithFileUploads;
    public $productos = [], $total = 0, $tipopagos = null, $selTipo = 1, $condescuento = false, $indicador = 0, $browseMobile, $comprobante = null;

    protected $listeners = ['calcular', 'updBrowse'];

    public function updatedCondescuento()
    {
        $this->recalcular();
    }

    public function recalcular()
    {
        $this->reset(['total']);
        $subtotal = 0;
        foreach ($this->productos as $producto) {
            if ($producto[3] > 0) {
                if ($this->condescuento) {
                    $subtotal = $producto[7] * $producto[3];
                } else {
                    $subtotal = $producto[2] * $producto[3];
                }
                $this->productos[$producto[5]][4] = $subtotal;
                $this->total = number_format($this->total + $subtotal, 2);
                $this->emit('subtotal', array($producto[5], number_format($subtotal, 2)));
            }
        }
        $this->emit('borrar');
    }

    public function mount()
    {
        $this->indicador = request()->indicador;
        // if (strtotime(date('H:i')) <= strtotime(Auth::user()->sucursale->horalimitepedidos)) {
        $evento = Evento::where('fecha', date('Y-m-d'))
            ->where('sucursale_id', Auth::user()->sucursale_id)
            ->first();
        $i = 0;
        $configPOS = Ventasconfig::where('sucursale_id', Auth::user()->sucursale_id)->first();
        if ($evento) {
            foreach ($evento->detalleeventos as $detalle) {
                $preciomenu = Preciomenu::where('tipomenu_id', $detalle->menu->tipomenu_id)
                    ->where('nivelcurso_id', $configPOS->nivelcurso_id)
                    ->first();
                if ($preciomenu) {
                    $this->productos[] = array($detalle->menu->id, $detalle->menu->nombre, $preciomenu->precio, 0, 0, $i, $detalle->menu->descripcion, $preciomenu->preciopm, $preciomenu->tipomenu->nombre);
                }

                $i++;
            }
        }
        $this->tipopagos = Tipopago::whereIn('id', [1, 2, 3])->get();


        // }
    }


    public function updBrowse($status)
    {
        $this->browseMobile = $status;
        // dd($this->browseMobile);
    }

    public function render()
    {
        return view('livewire.ventas.pos')->extends('layouts.app');
    }

    public function calcular($i, $cantidad)
    {
        if ($cantidad == "") {
            $cantidad = 0;
        }
        $this->productos[$i][4] = $this->productos[$i][2] * $cantidad;
        $this->productos[$i][3] = $cantidad;
        $this->reset(['total']);
        foreach ($this->productos as $item) {

            $aux = $this->total + $item[4];

            $this->total = number_format($aux, 2);
        }
        $this->emit('subtotal', array($i, $this->productos[$i][4]));

        $this->condescuento = false;
        $this->recalcular();
        $this->emit('borrar');
    }

    public function registrar()
    {

        if ($this->selTipo == 2 || $this->selTipo == 3) {
            if (is_null($this->comprobante)) {
                $this->emit('warning', 'Debe adjuntar un comprobante.');
                return "";
            }
        }

        $this->emit('loading');
        if ($this->total > 0) {
            DB::beginTransaction();
            $tipopago = Tipopago::find($this->selTipo);
            $observaciones = null;
            if ($this->condescuento) {
                $observaciones = "Aplica Descuento";
            }
            try {
                $tipo = Tipopago::find($this->selTipo);
                $venta = Venta::create([
                    "fecha" => date('Y-m-d'),
                    "cliente" => 'VENTA POS',
                    "estadopago_id" => 1,
                    "tipopago_id" => $tipo->id,
                    "importe" => ($this->total * $tipo->factor),
                    "sucursale_id" => Auth::user()->sucursale_id,
                    "plataforma" => "admin",
                    "observaciones" => $observaciones,
                    "user_id" => Auth::user()->id,
                ]);



                switch ($tipopago->nombre) {

                    case 'EFECTIVO - LOCAL': {
                            $pago = Pago::create([
                                "fecha" => date('Y-m-d'),
                                "recibo" => '0',
                                "tipopago_id" => $tipo->id,
                                "tipopago" => $tipo->nombre,
                                "sucursal_id" => Auth::user()->sucursale_id,
                                "sucursal" => Auth::user()->sucursale->nombre,
                                "importe" => ($this->total * $tipo->factor),
                                "venta_id" => $venta->id,
                                "estadopago_id" => 2,
                                "tipoinicial" => $tipo->nombre,
                                "user_id" => Auth::user()->id,
                            ]);


                            $venta->estadopago_id = 2;
                            $venta->save();
                        }

                        break;
                    case 'GASTO ADMINISTRATIVO':
                        $pago = Pago::create([
                            "fecha" => date('Y-m-d'),
                            "recibo" => '0',
                            "tipopago_id" => $tipo->id,
                            "tipopago" => $tipo->nombre,
                            "sucursal_id" => Auth::user()->sucursale_id,
                            "sucursal" => Auth::user()->sucursale->nombre,
                            "importe" => ($this->total * $tipo->factor),
                            "venta_id" => $venta->id,
                            "estadopago_id" => 2,
                            "tipoinicial" => $tipo->nombre,
                            "user_id" => Auth::user()->id,
                        ]);
                        $venta->estadopago_id = 2;
                        $venta->save();
                        $pago->estadopago_id = 2;
                        $pago->save();
                        break;
                    case "PAGO QR": {
                            $pago = Pago::create([
                                "fecha" => date('Y-m-d'),
                                "recibo" => '0',
                                "tipopago_id" => $tipo->id,
                                "tipopago" => $tipo->nombre,
                                "sucursal_id" => Auth::user()->sucursale_id,
                                "sucursal" => Auth::user()->sucursale->nombre,
                                "importe" => ($this->total * $tipo->factor),
                                "venta_id" => $venta->id,
                                "estadopago_id" => 2,
                                "tipoinicial" => $tipo->nombre,
                                "user_id" => Auth::user()->id,
                            ]);
                            $file = $this->comprobante->storeAs('depositos/' . $tipopago->abreviatura, $pago->id . "." . $this->comprobante->extension());
                            $comprobante = 'storage/depositos/' . $tipopago->abreviatura . '/' . $pago->id . "." . $this->comprobante->extension();

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
                                "recibo" => '0',
                                "tipopago_id" => $tipo->id,
                                "tipopago" => $tipo->nombre,
                                "sucursal_id" => Auth::user()->sucursale_id,
                                "sucursal" => Auth::user()->sucursale->nombre,
                                "importe" => ($this->total * $tipo->factor),
                                "venta_id" => $venta->id,
                                "estadopago_id" => 2,
                                "tipoinicial" => $tipo->nombre,
                                "user_id" => Auth::user()->id,
                            ]);
                            $file = $this->comprobante->storeAs('depositos/' . $tipopago->abreviatura, $pago->id . "." . $this->comprobante->extension());
                            $comprobante = 'storage/depositos/' . $tipopago->abreviatura . '/' .  $pago->id . "." . $this->comprobante->extension();

                            $venta->estadopago_id = 2;
                            $venta->save();
                            $pago->estadopago_id = 2;
                            $pago->comprobante = $comprobante;
                            $pago->save();
                        }
                        break;
                }

                // $pago = Pago::create([
                //     "fecha" => date('Y-m-d'),
                //     "recibo" => '0',
                //     "tipopago_id" => $tipo->id,
                //     "tipopago" => $tipo->nombre,
                //     "sucursal_id" => Auth::user()->sucursale_id,
                //     "sucursal" => Auth::user()->sucursale->nombre,
                //     "importe" => ($this->total * $tipo->factor),
                //     "venta_id" => $venta->id,
                //     "estadopago_id" => 2,
                //     "tipoinicial" => $tipo->nombre,
                //     "user_id" => Auth::user()->id,
                // ]);

                //configurar impresion de ticket
                //redirect('http://127.0.0.1/gprinter/public/print/' . $datos); //IMPRESION MEDIANTE LOCALHOST DEL CLIENTE
                // $this->productos[] = array($detalle->menu->id, $detalle->menu->nombre, $preciomenu->precio, cantidad, 0, $i, $detalle->menu->descripcion, $preciomenu->preciopm);
                $row = "";
                $detalleventa = array();
                $observacion = "";
                foreach ($this->productos as $producto) {
                    $menu = Menu::find($producto[0]);

                    if ($menu->tipomenu_id == 1 && $this->condescuento == true) {
                        $observacion = "DESCUENTO";
                    } else {
                        $observacion = "";
                    }
                    if ($producto[3] > 0) {
                        for ($i = 0; $i < $producto[3]; $i++) {
                            $entrega = Entregalounch::create([
                                "fechaentrega" => date('Y-m-d H:i:s'),
                                "menu_id" => $producto[0],
                                "producto_id" => 4,
                                "venta_id" => $venta->id,
                                "user_id" => Auth::user()->id,
                                "sucursale_id" => Auth::user()->sucursale_id,
                                'observaciones' => 'VENTA POS'
                            ]);
                            $preciou = $producto[4] / $producto[3];

                            $row = $row . $entrega->id . "|" . $producto[8] . "|Venta POS|" . $entrega->fechaentrega . "|" . $producto[1] . "|" . Auth::user()->name . "|" . $preciou . "~";
                        }
                        $detalleventa[] = array($menu->tipomenu->nombre, $producto[3], $preciou, $producto[4], $observacion, $menu->tipomenu_id);
                    }
                }

                foreach ($detalleventa as $dventa) {
                    $detalleventa = Detalleventa::create([
                        'venta_id' => $venta->id,
                        'descripcion' => $dventa[0],
                        'producto_id' => 4,
                        'tipomenu_id' => $dventa[5],
                        'cantidad' => $dventa[1],
                        'preciounitario' => $dventa[2],
                        'subtotal' => $dventa[3],
                        'observacion' => $dventa[4],

                    ]);
                }




                $row =  substr($row, 0, -1);
                DB::commit();
                // dd($row);
                // $this->alert('success', 'Hello!', [
                //     'position' => 'center',
                //     'timer' => 3000,
                //     'toast' => true,
                //    ]);

                // $datos = explode('~',$row);
                // foreach ($datos as $roww) {
                //     $this->emit('imprimir', $roww);
                // }
                // redirect('http://127.0.0.1/gprinter/public/printPOS1/' . $row);

                if ($this->browseMobile) {

                    $this->emit('imprimir', $row);
                    redirect()->route('ventas.pos')->with('success2', 'Venta registrada correctamente.');
                } else {
                    //redirect('http://127.0.0.1/gprinter/public/printPOS1/' . $row); //IMPRESION MEDIANTE LOCALHOST DEL CLIENTE
                    $this->emit('imprimir', $row);
                    redirect()->route('ventas.pos')->with('success2', 'Venta registrada correctamente.'); //impresion temporal
                }


                // return redirect()->route('ventas.pos')->with('success', 'Venta registrada correctamente.');
            } catch (\Throwable $th) {
                $this->emit('unLoading');
                DB::rollback();
                $this->emit('error', $th->getMessage());
            }
        } else {
            $this->emit('warning', 'No se ha seleccionado ningun producto.');
        }
    }
    public function updatedSelTipo()
    {
        if ($this->selTipo == 2 || $this->selTipo == 3) {
            $this->emit('comprobante', 'show');
        } else {
            $this->emit('comprobante', 'hide');
        }
    }
}
