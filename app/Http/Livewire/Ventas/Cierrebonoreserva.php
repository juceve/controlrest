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
        $sql = "SELECT v.fecha, dv.descripcion,  tp.abreviatura, IF(dv.observacion='','NO','SI') descuento, 
        SUM(dv.cantidad) cantidad, dv.preciounitario, SUM(dv.subtotal) subtotal,
        p.user_id,p.sucursal_id,p.estado
        from detalleventas dv
        INNER JOIN ventas v on v.id = dv.venta_id
        INNER JOIN pagos p ON p.venta_id = v.id
        INNER JOIN tipopagos tp ON tp.id = p.tipopago_id
        WHERE cliente != 'VENTA POS'        
        AND p.user_id = " . Auth::user()->id . "
        AND p.sucursal_id = " . Auth::user()->sucursale_id . "
        AND v.fecha = '" . date('Y-m-d') . "'
        AND v.estado = 1
        GROUP BY v.fecha,dv.descripcion,tp.abreviatura, dv.preciounitario, dv.observacion,p.user_id,p.sucursal_id,p.estado";
        $ingresosHOY = DB::select($sql);
        $this->ingresos = $ingresosHOY;
        

        $sql2 = "SELECT p.tipopago_id, p.tipopago, count(*) cantidad, SUM(p.importe) importe from pagos p
        INNER JOIN ventas v on v.id = p.venta_id
        WHERE p.fecha = '" . date('Y-m-d') . "'
        AND v.cliente <> 'VENTA POS'
        AND p.user_id = " . Auth::user()->id . "
        AND p.estado = 1
        GROUP BY p.tipopago_id, p.tipopago";
        $montosHOY = DB::select($sql2);
        $this->montos = $montosHOY;
        $this->reset(['totalpr', 'totalpp']);
        foreach ($montosHOY as $ingreso) {
            $this->totalpr = $this->totalpr + $ingreso->importe;
        }

        $cierres = Cierrereservabono::where('user_id', Auth::user()->id)
            ->where('sucursale_id', Auth::user()->sucursale_id)
            ->orderBy('id', 'DESC')
            ->get();

        $this->emit('datatableRender');
        return view('livewire.ventas.cierrebonoreserva', compact('ingresosHOY', 'cierres','montosHOY'))->extends('layouts.app');
    }




    public $totalpr = 0, $totalpp = 0, $encaja = 0, $faltante = 0, $ingresos,$montos;

    public function updatedEncaja()
    {
        if (is_numeric($this->encaja)) {
            $this->faltante = $this->totalpr - $this->encaja;
        }
        if ($this->encaja == "") {
            $this->encaja = 0;
            $this->faltante = $this->totalpr - $this->encaja;
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
                    'descripcion' => $ingreso['descripcion'],
                    'tipopago' => $ingreso['abreviatura'],
                    'descuento' => $ingreso['descuento'],
                    'cantidad' => $ingreso['cantidad'],
                    'preciounitario' => $ingreso['preciounitario'],
                    'importe' => $ingreso['subtotal'],
                ]);
            }

            foreach ($this->montos as $monto) {
                $detallemontos = Detallemontocierreresbono::create([
                    "cierrereservabono_id" => $cierre->id,
                    "tipopago_id" => $monto['tipopago_id'],
                    "tipopago" => $monto['tipopago'],
                    "cantidad" => $monto['cantidad'],
                    "importe" => $monto['importe'],
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
                case 'TR':
                    $totalTr = $totalTr + $detalle->importe;
                    break;
                case 'GA':
                    $totalGa = $totalGa + $detalle->importe;
                    break;
            }
        }
        $pdf = Pdf::loadView('reports.cierrecaja2', compact('cierre', 'detalles', 'totalEfectivo', 'totalQr', 'totalTr','totalGa'))->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "Reporte_OperacionesBonoReservas_" . $cierre->fecha . ".pdf"
        );
        
    }
}
