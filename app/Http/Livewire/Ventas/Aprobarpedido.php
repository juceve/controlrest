<?php

namespace App\Http\Livewire\Ventas;

use App\Models\Bonoanuale;
use App\Models\Bonofecha;
use App\Models\Creditoprofesore;
use App\Models\Lonchera;
use App\Models\Pago;
use App\Models\Sucursale;
use App\Models\Tipopago;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Aprobarpedido extends Component
{
    use WithFileUploads;
    public $venta, $pago, $sucursale, $tipopago, $comprobante, $loncheras,  $selTipo = "";

    public function mount($venta_id)
    {
        $this->venta = Venta::find($venta_id);
        // $pago = Pago::where('venta_id', $this->venta->id)->get();
        // foreach ($pago as $item) {
        //     $this->pago = $item;
        // }
        $this->tipopago = Tipopago::find($this->venta->tipopago_id);
        
        // ->where('nombre', '!=', 'GASTO ADMINISTRATIVO')
        // ->get();
    }
    public function render()
    {
        // $this->loncheras = Lonchera::where('pago_id', $this->pago->id)->get();
        $tipopagos = Tipopago::where('nombre', '!=', 'CREDITO')->get();
        return view('livewire.ventas.aprobarpedido',compact('tipopagos'))->extends('layouts.app');
    }

    protected $listeners = ['aprobarPedido'];

    public function habilitarlonchera($id)
    {
        $lonchera = Lonchera::find($id);
        $lonchera->habilitado = true;
        $lonchera->save();
    }

    public function aprobarPedido()
    {
        switch ($this->tipopago->id) {

            case 2: {
                    if ($this->comprobante) {
                        $this->validate([
                            'comprobante' => 'image|max:1024', // 1MB Max
                        ]);
                        $this->emit('loading');
                        
                        $this->aprobar();
                    } else {
                        $this->emit('warning', 'Debe adjuntar un comprobante');
                    }
                }
                break;
            case 3: {
                    if ($this->comprobante) {
                        $this->validate([
                            'comprobante' => 'image|max:1024', // 1MB Max
                        ]);
                        $this->emit('loading');
                        
                        $this->aprobar();
                    } else {
                        $this->emit('warning', 'Debe adjuntar un comprobante');
                    }
                }
                break;
            case 4:
                if ($this->selTipo != "") {
                    if ($this->selTipo == 2 || $this->selTipo == 3) {
                        if ($this->comprobante) {
                            $this->validate([
                                'comprobante' => 'image|max:1024', // 1MB Max
                            ]);
                            $this->emit('loading');

                            $this->aprobar();
                        } else {
                            $this->emit('warning', 'Debe adjuntar un comprobante');
                        }
                    } else {
                        $this->aprobar("");
                    }
                } else {
                    $this->emit('warning', 'Debe seleccionar una FORMA DE PAGO.');
                }
                break;
        }
    }

    public $tipoSecundario = null;

    public function updatedSelTipo()
    {
        $this->tipoSecundario = Tipopago::find($this->selTipo);
    }


    public function aprobar()
    {
        DB::beginTransaction();
        try {
            $pago = Pago::create([
                "fecha" => date('Y-m-d'),
                "recibo" => 0,
                "tipopago_id" => $this->tipopago->id,
                "tipopago" => $this->tipopago->nombre,
                "sucursal_id" => Auth::user()->sucursale_id,
                "sucursal" => Auth::user()->sucursale->nombre,
                "importe" => $this->venta->importe,
                "venta_id" => $this->venta->id,
                "estadopago_id" => 2,
                "user_id" => Auth::user()->id,
                "tipoinicial" => $this->tipopago->nombre,
            ]);
            $this->pago = $pago;
            $this->venta->estadopago_id = 2;
            $this->venta->save();
            $comprobante = "";
            if ($this->tipopago->id == 4) {

                if ($this->comprobante) {
                    $file = $this->comprobante->storeAs('depositos/' . $this->tipoSecundario->abreviatura, $pago->id . "." . $this->comprobante->extension());
                    $comprobante = 'depositos/' . $this->tipoSecundario->abreviatura . '/' . $pago->id . "." . $this->comprobante->extension();
                }
                $this->venta->importe = $this->venta->importe * $this->tipoSecundario->factor;
                $this->venta->save();
                $pago->tipopago_id = $this->tipoSecundario->id;
                $pago->tipopago = $this->tipoSecundario->nombre;
                $pago->importe = $pago->importe * $this->tipoSecundario->factor;         
            } else {
                if ($this->comprobante) {
                    $file = $this->comprobante->storeAs('depositos/' . $this->tipopago->abreviatura, $pago->id . "." . $this->comprobante->extension());
                    $comprobante = 'depositos/' . $this->tipopago->abreviatura . '/' . $pago->id . "." . $this->comprobante->extension();
                }
            }
            $pago->comprobante = $comprobante;
            // FALTA GENERAR EL RECIBO QUE VENDRÁ A SER LA FACTURA
            $pago->save();

            $loncheras = Lonchera::where('venta_id', $this->venta->id)->get();
            $bonoanuales = Bonoanuale::where('venta_id', $this->venta->id)->get();
            $bonofechas = Bonofecha::where('venta_id', $this->venta->id)->get();
            $creditos = Creditoprofesore::where('venta_id', $this->venta->id)->get();

            if ($loncheras) {
                foreach ($loncheras as $item) {
                    $item->habilitado = true;
                    $item->save();
                }
            }

            if ($bonoanuales) {
                foreach ($bonoanuales as $item) {
                    $item->estado = true;
                    $item->save();
                }
            }

            if ($bonofechas) {
                foreach ($bonofechas as $item) {
                    $item->estado = true;
                    $item->save();
                }
            }

            if ($creditos) {
                foreach ($creditos as $item) {
                    $item->pagado = true;
                    $item->save();
                }
            }

            DB::commit();

            //enviar correo con confirmacion//

            return redirect()->route('ventas.vpagos')->with('success', 'Se efectuó la habilitación del Pedido.');
        } catch (\Throwable $th) {
            $this->emit('unLoading');
            // return redirect()->route('ventas.appedido', $this->venta->id)->with('error', 'Ha ocurrido un error, se no realizaron las acciones solicitadas.');
            return redirect()->route('ventas.appedido', $this->venta->id)->with('error', $th->getMessage());
            DB::rollback();
        }
    }

}
