<?php

namespace App\Http\Livewire\Reportes;

use App\Models\Tipomenu;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Rventas extends Component
{

    public $fechaI = "", $fechaF = "", $tabla1 = null, $tabla2 = null, $tabla3 = null, $tabla4 = null, $tabla5 = null;
    public $t1= array(), $t2= array(), $t3= array(), $t4= array(), $t5= array();
    public $tipomenus = null, $sucursale_id;

    public function mount()
    {
        $this->fechaI = date('Y-m-d');
        $this->fechaF = date('Y-m-d');
        $this->tipomenus = Tipomenu::all();
        $this->sucursale_id = Auth::user()->sucursale_id;
    }

    public function render()
    {
        return view('livewire.reportes.rventas')->extends('layouts.app');
    }

    public function generar()
    {
        $sql1 = "SELECT v.tipopago_id, tp.nombre tipopago,count(*) cantidad, SUM(importe) total FROM ventas v
        INNER JOIN tipopagos tp on tp.id = v.tipopago_id
        WHERE v.fecha BETWEEN '" . $this->fechaI . "' AND '" . $this->fechaF . "'
        AND estadopago_id = 2
        AND v.sucursale_id = " . $this->sucursale_id . "
        GROUP BY tipopago_id,tp.nombre";
        $this->tabla1 = DB::select($sql1);
        foreach ($this->tabla1 as $item) {
            $this->t1[] = array($item->tipopago_id,$item->tipopago,$item->cantidad,$item->total);
        }

        $sql2 = "SELECT v.tipopago_id, tp.nombre tipopago,count(*) cantidad, SUM(importe) total FROM ventas v
        INNER JOIN tipopagos tp on tp.id = v.tipopago_id
        WHERE v.fecha BETWEEN '" . $this->fechaI . "' AND '" . $this->fechaF . "'
        AND estadopago_id = 1
        AND v.sucursale_id = " . $this->sucursale_id . "
        GROUP BY tipopago_id,tp.nombre";
        $this->tabla2 = DB::select($sql2);
        foreach ($this->tabla2 as $item) {
            $this->t2[] = array($item->tipopago_id,$item->tipopago,$item->cantidad,$item->total);
        }

        $sql3 = "SELECT producto_id, plataforma, count(*) cantidad, SUM(importe) importe FROM 
        (SELECT DISTINCT(v.id) ,dv.producto_id, p.nombre plataforma, v.importe FROM ventas v
        INNER JOIN detalleventas dv on dv.venta_id = v.id
        INNER JOIN productos p on p.id = dv.producto_id
        WHERE v.estadopago_id = 2
        AND v.fecha BETWEEN '" . $this->fechaI . "' AND '" . $this->fechaF . "'
        AND v.sucursale_id = " . $this->sucursale_id . ") as resultado
        GROUP BY  producto_id, plataforma";

        $this->tabla3 = DB::select($sql3);
        foreach ($this->tabla3 as $item) {
            $this->t3[] = array($item->producto_id,$item->plataforma,$item->cantidad,$item->importe);
        }

        $sql4 = "SELECT producto_id, plataforma, count(*) cantidad, SUM(importe) importe FROM 
        (SELECT DISTINCT(v.id) ,dv.producto_id, p.nombre plataforma, v.importe FROM ventas v
        INNER JOIN detalleventas dv on dv.venta_id = v.id
        INNER JOIN productos p on p.id = dv.producto_id
        WHERE v.estadopago_id = 1
        AND v.fecha BETWEEN '" . $this->fechaI . "' AND '" . $this->fechaF . "'
        AND v.sucursale_id = " . $this->sucursale_id . ") as resultado
        GROUP BY  producto_id, plataforma";

        $this->tabla4 = DB::select($sql4);
        foreach ($this->tabla4 as $item) {
            $this->t4[] = array($item->producto_id,$item->plataforma,$item->cantidad,$item->importe);
        }

        $sql5 = "SELECT dv.* FROM ventas v
        INNER JOIN detalleventas dv on dv.venta_id = v.id
        WHERE v.fecha BETWEEN '" . $this->fechaI . "' AND '" . $this->fechaF . "'
        AND v.sucursale_id = " . $this->sucursale_id . "
        AND tipopago_id = 5";

        $this->tabla5 = DB::select($sql5);

        foreach ($this->tabla5 as $item) {
            $this->t5[] = array($item->descripcion,$item->cantidad,$item->subtotal);
        }

        // $resultado3 = Venta::where([['fecha', '>=', $this->fechaI], ['fecha', '<=', $this->fechaF]])
        //     ->where('estadopago_id', 2)->get();
        //     $matriz = array();
        //     foreach ($this->tipomenus as $tipo) {
        //         $arrTipo = array();
        //         $cantdescuento = 0;
        //         $cantsindescuento = 0;
        //         $totalcondesc = 0;
        //         $totalsindesc = 0;
        //         foreach ($resultado3 as $item) {
        //             $detalles = $item->detalleventas->where('tipomenu_id',$tipo->id);
        //             foreach ($detalles as $detalle) {
        //                 if($detalle->observacion == "DESCUENTO"){
        //                     $cantdescuento++;
        //                     $totalcondesc = ($totalcondesc + ($detalle->subtotal * $item->tipopago->factor));
        //                 }else{
        //                     $cantsindescuento++;
        //                     $totalsindesc = ($totalsindesc + ($detalle->subtotal * $item->tipopago->factor));
        //                 }
        //             }
        //         }    
        //             $arrTipo[] = array("SI",$cantdescuento,$totalcondesc);
        //             $arrTipo[] = array("NO",$cantsindescuento,$totalsindesc);
        //             $matriz[] = array($tipo->id,$tipo->nombre,$arrTipo);
        //     }

        // $this->tabla3 = $matriz;

        // $resultado4 = Venta::where([['fecha', '>=', $this->fechaI], ['fecha', '<=', $this->fechaF]])
        //     ->where('estadopago_id', 1)->get();
        //     $matriz2 = array();
        //     foreach ($this->tipomenus as $tipo) {
        //         $arrTipo = array();
        //         $cantdescuento = 0;
        //         $cantsindescuento = 0;
        //         $totalcondesc = 0;
        //         $totalsindesc = 0;
        //         foreach ($resultado4 as $item) {
        //             $detalles = $item->detalleventas->where('tipomenu_id',$tipo->id);
        //             foreach ($detalles as $detalle) {
        //                 if($detalle->observacion == "DESCUENTO"){
        //                     $cantdescuento++;
        //                     $totalcondesc = ($totalcondesc + ($detalle->subtotal * $item->tipopago->factor));
        //                 }else{
        //                     $cantsindescuento++;
        //                     $totalsindesc = ($totalsindesc + ($detalle->subtotal * $item->tipopago->factor));
        //                 }
        //             }
        //         }    
        //             $arrTipo[] = array("SI",$cantdescuento,$totalcondesc);
        //             $arrTipo[] = array("NO",$cantsindescuento,$totalsindesc);
        //             $matriz2[] = array($tipo->id,$tipo->nombre,$arrTipo);
        //     }

        // $this->tabla4 = $matriz2;
    }

    public function pdf()
    {
        $tabla1 = $this->t1;
        $tabla2 = $this->t2;
        $tabla3 = $this->t3;
        $tabla4 = $this->t4;
        $tabla5 = $this->t5;
        $fechaI = $this->fechaI;
        $fechaF = $this->fechaF;
        $pdf = Pdf::loadView('reports.rptventas', compact('tabla1',  'tabla2', 'tabla3', 'tabla4', 'tabla5', 'fechaI', 'fechaF'))->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "Rpt_Ventas_" . $this->fechaI . "_" . $this->fechaF . ".pdf"
        );
    }
}
