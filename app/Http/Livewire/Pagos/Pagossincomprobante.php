<?php

namespace App\Http\Livewire\Pagos;

use App\Models\Pago;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Pagossincomprobante extends Component
{
    public function render()
    {
        $sucursale_id = Auth::user()->sucursale_id;
        $sql = "SELECT p.id, p.fecha, v.cliente, p.tipopago, p.importe from pagos p
        INNER JOIN ventas v on v.id = p.venta_id
        WHERE p.comprobante = 'img/admin/noImagen.jpg'
        AND v.sucursale_id = $sucursale_id
        AND v.estadopago_id = 2";
        $pagos = DB::select($sql);
        return view('livewire.pagos.pagossincomprobante',compact('pagos'))->extends('layouts.app');
    }
}
