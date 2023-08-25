<?php

namespace App\Http\Livewire\Pagos;

use App\Models\Pago;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Actcomprobante extends Component
{
    use WithFileUploads;
    public $pago, $comprobante;

    public function mount($pago_id)
    {
        $this->pago = Pago::find($pago_id);
    }

    public function render()
    {
        return view('livewire.pagos.actcomprobante')->extends('layouts.app');
    }

    public function guardar()
    {
        DB::beginTransaction();
        try {
            if ($this->comprobante) {
                $this->emit('loading');
                $file = $this->comprobante->storeAs('depositos/' . $this->pago->venta->tipopago->abreviatura, $this->pago->id . "." . $this->comprobante->extension());
                $comprobante = 'depositos/' . $this->pago->venta->tipopago->abreviatura . '/' . $this->pago->id . "." . $this->comprobante->extension();
                $this->pago->comprobante = $comprobante;
                $this->pago->save();
            }
            DB::commit();
            return redirect()->route('pagos.sincomprobante')->with('success', 'Comprobante actualizado con exito.');
        } catch (\Throwable $th) {
            $this->emit('unLoading');
            DB::rollBack();
            $this->emit('error', 'Ha ocurrido un error');
        }
    }
}
