<?php

namespace App\Http\Livewire\Ventas;

use App\Models\Cierrereservabono;
use App\Models\Detallecierrereservabono;
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
        $this->reset(['totalpr', 'totalpp']);
        foreach ($ingresosHOY as $ingreso) {
            $this->totalpr = $this->totalpr + $ingreso->subtotal;
        }

        $cierres = Cierrereservabono::where('user_id', Auth::user()->id)
            ->where('sucursale_id', Auth::user()->sucursale_id)
            ->orderBy('id', 'DESC')
            ->get();

        $this->emit('datatableRender');
        return view('livewire.ventas.cierrebonoreserva', compact('ingresosHOY', 'cierres'))->extends('layouts.app');
    }




    public $totalpr = 0, $totalpp = 0, $encaja = 0, $faltante = 0, $ingresos;

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
                    'cierrecaja_id' => $cierre->id,
                    'descripcion' => $ingreso['descripcion'],
                    'tipopago' => $ingreso['abreviatura'],
                    'descuento' => $ingreso['descuento'],
                    'cantidad' => $ingreso['cantidad'],
                    'preciounitario' => $ingreso['preciounitario'],
                    'importe' => $ingreso['subtotal'],
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
        $cierre = Cierrereservabono::find($id);
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
        $pdf = Pdf::loadView('reports.cierrecaja', compact('cierre', 'detalles', 'totalEfectivo', 'totalQr', 'totalTr','totalGa'))->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "Reporte_CierreCaja_" . $cierre->fecha . ".pdf"
        );
    }

    public function ventasPDF($id)
    {
        $cierre = Cierrereservabono::find($id);

        $tipopagos = Tipopago::all();
        $resultados = array();
        foreach ($tipopagos as $tipopago) {
            $sql = "SELECT v.fecha, dv.descripcion, tp.id tipopago_id,tp.nombre tipopago, ep.id estadopago_id, ep.nombre estadopago, count(*) cantidad,SUM(dv.subtotal) subtotal FROM ventas v
            INNER JOIN pagos p on p.venta_id = v.id
            INNER JOIN detalleventas dv on dv.venta_id = v.id
            INNER JOIN estadopagos ep on ep.id = v.estadopago_id
            INNER JOIN tipopagos tp on tp.id = p.tipopago_id            
            WHERE v.fecha = '" . $cierre->fecha . "'
            AND p.tipopago_id = " . $tipopago->id . "
            AND p.user_id = " . $cierre->user_id . "
            AND v.sucursale_id = " . $cierre->sucursale_id . "
            GROUP BY v.fecha, dv.descripcion, tp.id, tp.nombre, ep.id, estadopago_id, ep.nombre";
            $ventas = DB::select($sql);
            // if($ventas){
            $resultados[] = array($tipopago->nombre, $ventas);
            // }

        }

        $pdf = Pdf::loadView('reports.ventas', compact('resultados', 'cierre'))->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "Reporte_Ventas_" . $cierre->fecha . ".pdf"
        );
    }
}
