<?php

namespace App\Http\Livewire\Ventas;

use App\Models\Cierrecaja as ModelsCierrecaja;
use App\Models\Cierrereservabono;
use App\Models\Detallecierre;
use App\Models\Detallecierrereservabono;
use App\Models\Detallemontocierre;
use App\Models\Tipopago;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class Cierrecaja extends Component
{
    public $totalpr = 0, $totalpp = 0, $encaja = 0, $faltante = 0, $ingresos, $montos;

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


    public function render()
    {
        $sql = "SELECT v.fecha, dv.descripcion,  tp.abreviatura, IF(dv.observacion='','NO','SI') descuento,
        SUM(dv.cantidad) cantidad, dv.preciounitario, SUM(dv.subtotal) subtotal,
        p.user_id,p.sucursal_id,p.estado
        from detalleventas dv
        INNER JOIN ventas v on v.id = dv.venta_id
        INNER JOIN pagos p ON p.venta_id = v.id
        INNER JOIN tipopagos tp ON tp.id = p.tipopago_id
        WHERE dv.producto_id = 4
        AND p.user_id = " . Auth::user()->id . "
        AND p.sucursal_id = " . Auth::user()->sucursale_id . "
        AND p.fecha = '" . date('Y-m-d') . "'
        AND p.estado = 1
        GROUP BY v.fecha,dv.descripcion,tp.abreviatura, dv.preciounitario, dv.observacion,p.user_id,p.sucursal_id,p.estado";
        $ingresosHOY = DB::select($sql);
        $this->ingresos = $ingresosHOY;



        $sql2 = "SELECT p.tipopago_id, p.tipopago, count(*) cantidad, SUM(p.importe) importe from pagos p
        INNER JOIN ventas v on v.id = p.venta_id
        INNER JOIN detalleventas dv ON dv.venta_id=v.id
        WHERE p.fecha = '" . date('Y-m-d') . "'
        AND dv.producto_id = 4
        AND p.user_id = " . Auth::user()->id . "
        AND p.estado = 1
        GROUP BY p.tipopago_id, p.tipopago";
        $montosHOY = DB::select($sql2);

        $this->montos = $montosHOY;
        $this->reset(['totalpr', 'totalpp']);
        foreach ($montosHOY as $ingreso) {
            $this->totalpr = $this->totalpr + $ingreso->importe;
        }
        if (Auth::user()->roles[0]->name == "Admin" || Auth::user()->roles[0]->name == "SUPERVISOR") {
            $cierres = ModelsCierrecaja::where('sucursale_id', Auth::user()->sucursale_id)
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            $cierres = ModelsCierrecaja::where('user_id', Auth::user()->id)
                ->where('sucursale_id', Auth::user()->sucursale_id)
                ->orderBy('id', 'DESC')
                ->get();
        }



        $this->emit('datatableRender');
        return view('livewire.ventas.cierrecaja', compact('ingresosHOY', 'montosHOY', 'cierres'))->extends('layouts.app');
    }
    protected $listeners = ['cerrarCaja'];

    public function cerrarCaja()
    {
        $this->emit('loading');
        DB::beginTransaction();
        try {
            $cierre = ModelsCierrecaja::create([
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i'),
                'user_id' => Auth::user()->id,
                'sucursale_id' => Auth::user()->sucursale_id,
            ]);
            foreach ($this->ingresos as $ingreso) {
                $detallecierre = Detallecierre::create([
                    'cierrecaja_id' => $cierre->id,
                    'descripcion' => $ingreso['descripcion'],
                    'tipopago' => $ingreso['abreviatura'],
                    'descuento' => $ingreso['descuento'],
                    'cantidad' => $ingreso['cantidad'],
                    'preciounitario' => $ingreso['preciounitario'],
                    'importe' => $ingreso['subtotal'],
                ]);
            }

            foreach ($this->montos as $monto) {
                $detallemontos = Detallemontocierre::create([
                    "cierrecaja_id" => $cierre->id,
                    "tipopago_id" => $monto['tipopago_id'],
                    "tipopago" => $monto['tipopago'],
                    "cantidad" => $monto['cantidad'],
                    "importe" => $monto['importe'],
                ]);
            }
            DB::commit();
            return redirect()->route('ventas.cierrecaja')->with('success', 'Cierre generado correctamente.');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->emit('unLoading');
            $this->emit('error', $th->getMessage());
        }
    }

    public function pdf($id)
    {
        $cierre = ModelsCierrecaja::find($id);
        if ($cierre) {
            $detalles = $cierre->detallemontocierres;
        }
        $pdf = Pdf::loadView('reports.cierrecajamontos', compact('cierre', 'detalles'))->output();
        return response()->streamDownload(
            fn() => print($pdf),
            "Reporte_CierreCaja_" . $cierre->fecha . ".pdf"
        );
    }

    public function ventasPDF($id)
    {
        $cierre = ModelsCierrecaja::find($id);
        if ($cierre) {
            $detalles = $cierre->detallecierres;
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
        $pdf = Pdf::loadView('reports.cierrecaja', compact('cierre', 'detalles', 'totalEfectivo', 'totalQr', 'totalTr', 'totalGa'))->output();
        return response()->streamDownload(
            fn() => print($pdf),
            "Reporte_Operaciones_" . $cierre->fecha . ".pdf"
        );
    }

    public function regenerar($cierre_id)
    {
        DB::beginTransaction();
        try {
            $cierre = ModelsCierrecaja::find($cierre_id);
            $sql = "SELECT v.fecha, dv.descripcion,  tp.abreviatura, IF(dv.observacion='','NO','SI') descuento,
        SUM(dv.cantidad) cantidad, dv.preciounitario, SUM(dv.subtotal) subtotal,
        p.user_id,p.sucursal_id,p.estado
        from detalleventas dv
        INNER JOIN ventas v on v.id = dv.venta_id
        INNER JOIN pagos p ON p.venta_id = v.id
        INNER JOIN tipopagos tp ON tp.id = p.tipopago_id
        WHERE dv.producto_id = 4
        AND p.user_id = " . $cierre->user_id . "
        AND p.sucursal_id = " . $cierre->user->sucursale_id . "
        AND p.fecha = '" . $cierre->fecha . "'
        AND p.estado = 1
        GROUP BY v.fecha,dv.descripcion,tp.abreviatura, dv.preciounitario, dv.observacion,p.user_id,p.sucursal_id,p.estado";
            $ingresosHOY = DB::select($sql);


            $sql2 = "SELECT p.tipopago_id, p.tipopago, count(*) cantidad, SUM(p.importe) importe from pagos p
        INNER JOIN ventas v on v.id = p.venta_id
        INNER JOIN detalleventas dv ON dv.venta_id=v.id
        WHERE p.fecha = '" . $cierre->fecha . "'
        AND dv.producto_id = 4
        AND p.user_id = " . $cierre->user_id . "
        AND p.estado = 1
        GROUP BY p.tipopago_id, p.tipopago";
            $montosHOY = DB::select($sql2);

            $detallecierre = Detallecierre::where('cierrecaja_id', $cierre->id)->get();
            foreach ($detallecierre as $item) {
                $item->delete();
            }

            foreach ($ingresosHOY as $ingreso) {
                $detallecierre = Detallecierre::create([
                    'cierrecaja_id' => $cierre->id,
                    'descripcion' => $ingreso->descripcion,
                    'tipopago' => $ingreso->abreviatura,
                    'descuento' => $ingreso->descuento,
                    'cantidad' => $ingreso->cantidad,
                    'preciounitario' => $ingreso->preciounitario,
                    'importe' => $ingreso->subtotal,
                ]);
            }

            $detallemontos = Detallemontocierre::where('cierrecaja_id', $cierre->id)->get();
            foreach ($detallemontos as $item) {
                $item->delete();
            }

            foreach ($montosHOY as $monto) {
                $detallemontos = Detallemontocierre::create([
                    "cierrecaja_id" => $cierre->id,
                    "tipopago_id" => $monto->tipopago_id,
                    "tipopago" => $monto->tipopago,
                    "cantidad" => $monto->cantidad,
                    "importe" => $monto->importe,
                ]);
            }

            DB::commit();
            $this->emit('success', 'Regenerado Correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->emit('error', $th->getMessage());
        }


        // dd($montosHOY);
    }
}
