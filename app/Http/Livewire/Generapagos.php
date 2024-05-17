<?php

namespace App\Http\Livewire;

use App\Models\Pago;
use App\Models\Tipopago;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Generapagos extends Component
{
    use WithFileUploads;
    public $file;

    public function render()
    {
        return view('livewire.generapagos')->extends('layouts.app');
    }

    public function ejecutar()
    {
        DB::beginTransaction();
        try {
            $ventas = "";
            if ($this->file) {
                if ($this->file) {
                    $extension =  $this->file->clientExtension();
                    $path = $this->file->storeAs(
                        '/ventas',
                        $ventas . ".txt"
                    );
                    $imagen = $path;
                }
            }

            $rutaArchivo = Storage::url('ventas/' . $ventas . '.txt');

            // Leer el contenido del archivo
            $contenidoArchivo = file_get_contents($rutaArchivo);
            $arrVentas = explode("\n", $contenidoArchivo);
            // dd($arrVentas);
            foreach ($arrVentas as $item) {
                $venta = Venta::find($item);
                $tipo = Tipopago::find($venta->tipopago_id);

                $pago = Pago::create([
                    "fecha" => $venta->fecha,
                    "recibo" => '0',
                    "tipopago_id" => $tipo->id,
                    "tipopago" => $tipo->nombre,
                    "sucursal_id" => $venta->user->sucursale_id,
                    "sucursal" => $venta->user->sucursale->nombre,
                    "importe" => ($venta->importe * $tipo->factor),
                    "venta_id" => $venta->id,
                    "estadopago_id" => $venta->estadopago_id,
                    "tipoinicial" => $tipo->nombre,
                    "user_id" => $venta->user_id,
                ]);
            }

            DB::commit();
            $this->reset(['file']);
            $this->emit('success', 'Se proceso la ejecutacion');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->reset(['file']);
            $this->emit('error', $th->getMessage());
        }
    }
}
