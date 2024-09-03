<?php

namespace App\Http\Livewire\Ventas;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Cierrebonoreserva2 extends Component
{
    public function render()
    {
        $hoy = date('Y-m-d');
        $user = Auth::user()->id;
        $sucursale = Auth::user()->sucursale_id;
        $sql = "SELECT p.nombre as tipo, COUNT(DISTINCT(v.id)) cantidad,tp.abreviatura tipopago,IF(dv.observacion='DESCUENTO','SI','NO') descuento,SUM(dv.subtotal) subtotal
        from detalleventas dv
        INNER JOIN ventas v on v.id = dv.venta_id
        INNER JOIN tipopagos tp on tp.id = v.tipopago_id
        INNER JOIN productos p on p.id = dv.producto_id
        WHERE v.user_id = $user
        AND v.fecha = '$hoy'
        AND v.estadopago_id = 2
        AND v.estado = 1
        AND dv.producto_id <> 4
        AND v.sucursale_id = $sucursale
        GROUP BY p.nombre,dv.observacion,tp.abreviatura";
        $ingresosHOY = DB::select($sql);
dd($ingresosHOY);

        return view('livewire.cierrebonoreserva2');
    }
}
