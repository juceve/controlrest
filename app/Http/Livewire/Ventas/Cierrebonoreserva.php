<?php

namespace App\Http\Livewire\Ventas;

use App\Models\Cierrereservabono;
use App\Models\Detallecierrereservabono;
use App\Models\Detallemontocierreresbono;
use App\Models\Tipopago;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Cierrebonoreserva extends Component
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

        $this->ingresos = $ingresosHOY;

        $sql2 = "SELECT tp.nombre, tp.id tipopago_id, COUNT(DISTINCT(v.id)) cantidad, SUM(dv.subtotal) total FROM ventas v
        INNER JOIN tipopagos tp on tp.id = v.tipopago_id
        INNER JOIN detalleventas dv on v.id = dv.venta_id
        WHERE user_id = $user
        AND fecha = '$hoy'
        AND estadopago_id = 2
        AND v.estado = 1
        AND v.sucursale_id = $sucursale
        AND dv.producto_id <> 4
        GROUP BY tp.nombre,tp.id";
        $montosHOY = DB::select($sql2);
        $this->montos = $montosHOY;
        // dd($montosHOY);
        $this->reset(['totalpr', 'totalpp']);
        foreach ($montosHOY as $ingreso) {
            $this->totalpr = $this->totalpr + $ingreso->total;
        }

        $cierres = Cierrereservabono::where('user_id', Auth::user()->id)
            ->where('sucursale_id', Auth::user()->sucursale_id)
            ->orderBy('id', 'DESC')
            ->get();

        $this->emit('datatableRender');
        return view('livewire.ventas.cierrebonoreserva', compact('ingresosHOY', 'cierres', 'montosHOY'))->extends('layouts.app');
    }




    public $totalpr = 0, $totalpp = 0, $encaja = 0, $faltante = 0, $ingresos, $montos;

    public function updatedEncaja()
    {
        if (is_numeric($this->encaja)) {
            $this->faltante = $this->encaja - $this->totalpr;
        }
        if ($this->encaja == "") {
            $this->encaja = 0;
            $this->faltante = $this->encaja - $this->totalpr;
        }
    }


    protected $listeners = ['cerrarCaja'];

    public function cerrarCaja()
    {
        $this->emit('loading');
        DB::beginTransaction();
        try {
            $cierre = Cierrereservabono::create([
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i'),
                'user_id' => Auth::user()->id,
                'sucursale_id' => Auth::user()->sucursale_id,
            ]);
            foreach ($this->ingresos as $ingreso) {
                $detallecierre = Detallecierrereservabono::create([
                    'cierrereservabono_id' => $cierre->id,
                    'descripcion' => $ingreso['tipo'],
                    'tipopago' => $ingreso['tipopago'],
                    'descuento' => $ingreso['descuento'],
                    'cantidad' => $ingreso['cantidad'],
                    // 'preciounitario' => $ingreso['preciounitario'],
                    'importe' => $ingreso['subtotal'],
                ]);
            }

            foreach ($this->montos as $monto) {
                $detallemontos = Detallemontocierreresbono::create([
                    "cierrereservabono_id" => $cierre->id,
                    "tipopago_id" => $monto['tipopago_id'],
                    "tipopago" => $monto['nombre'],
                    "cantidad" => $monto['cantidad'],
                    "importe" => $monto['total'],
                ]);
            }
            DB::commit();
            return redirect()->route('ventas.cierres')->with('success', 'Cierre generado correctamente.');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->emit('unLoading');
            $this->emit('error', $th->getMessage());
        }
    }

    public function pdf($id)
    {
        $cierre = Cierrereservabono::find($id);
        // dd($cierre->detallemontocierreresbonos);
        if ($cierre) {
            $detalles = $cierre->detallemontocierreresbonos;
        }
        $pdf = Pdf::loadView('reports.cierrecajamontos2', compact('cierre', 'detalles'))->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "Reporte_CierreBonoReservas_" . $cierre->fecha . ".pdf"
        );
    }

    public function ventasPDF($id)
    {
        $cierre = Cierrereservabono::find($id);
        if ($cierre) {
            $detalles = $cierre->detallecierrereservabonos;
        }
        $totalEfectivo = 0;
        $totalQr = 0;
        $totalTr = 0;
        $totalGa = 0;
        foreach ($detalles as $detalle) {
            switch ($detalle->tipopago) {
                case 'EF':
                    $totalEfectivo = $totalEfectivo + $detalle->importe;
                    break;
                case 'QR':
                    $totalQr = $totalQr + $detalle->importe;
                    break;
                case 'TB':
                    $totalTr = $totalTr + $detalle->importe;
                    break;
                case 'GA':
                    $totalGa = $totalGa + $detalle->importe;
                    break;
            }
        }
        $pdf = Pdf::loadView('reports.cierrecaja2', compact('cierre', 'detalles', 'totalEfectivo', 'totalQr', 'totalTr', 'totalGa'))->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "Reporte_OperacionesBonoReservas_" . $cierre->fecha . ".pdf"
        );
    }
}
