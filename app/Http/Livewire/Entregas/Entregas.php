<?php

namespace App\Http\Livewire\Entregas;

use App\Models\Bonoanuale;
use App\Models\Bonofecha;
use App\Models\Creditoprofesore;
use App\Models\Detallelonchera;
use App\Models\Detalleventa;
use App\Models\Entregalounch;
use App\Models\Evento;
use App\Models\Lonchera;
use App\Models\Pago;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Entregas extends Component
{
    public $arrPedidos = array(), $fecha = "";

    public function render()
    {
        return view('livewire.entregas.entregas')->extends('layouts.app');
    }

    protected $listeners = ['eliminar'];

    public function generaListado()
    {
        $this->emit('loading');
        $this->arrPedidos = Entregalounch::whereDate('fechaentrega', substr($this->fecha, 0, 10))
            ->where('producto_id', '<>', 4)
            ->where('estado', 1)
            ->get();
        $this->emit('datatableRender');
        if (!$this->arrPedidos) {
            $this->emit('warning', 'No se encontraron resultados');
        }
        $this->emit('unLoading');
    }

    public function eliminar($id)
    {
        DB::beginTransaction();
        try {
            $entrega = Entregalounch::find($id);

            switch ($entrega->producto_id) {
                case 3: {
                        $detallelonchera = Detallelonchera::find($entrega->detallelonchera_id);
                        $detallelonchera->entreado = 0;
                        $detallelonchera->estado = 1;
                        $detallelonchera->save();
                    }
                    break;
                case 5: {
                        $credito = Creditoprofesore::where([
                            ['venta_id', $entrega->venta_id],
                            ['fecha', substr($entrega->fechaentrega, 0, 10)],
                            ['estudiante_id', $entrega->estudiante_id]
                        ])->first();
                        $credito->delete();                        
                        $venta = Venta::find($entrega->venta_id)->update([
                            'estado' => 0
                        ]);
                    }
                    break;
            }

            $entrega->estado = 0;
            $entrega->save();
            $this->generaListado();


            DB::commit();
            $this->render();
            $this->emit('success', 'Entrega eliminada correctamente.');
        } catch (\Throwable $th) {
            // $this->emit('error', 'Ha ocurrido un error');
            $this->emit('datatableRender');
            $this->emit('error',  $th->getMessage());

            DB::rollBack();
        }
    }
}
