<?php

namespace App\Http\Livewire\Ventas;

use App\Exports\VentasExport;
use App\Models\Estadopago;
use App\Models\Tipopago;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Listado extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $busqueda = "", $fecInicio = "", $fecFin = "", $estadoPago = "", $tipoPago = "",$contenedor=null;

    public function mount()
    {
        $this->fecInicio = date('Y-m-d');
        $this->fecFin = date('Y-m-d');
    }
    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    public function render()
    {
        $estadoPagos = Estadopago::all();
        $tipoPagos = Tipopago::all();
        $ventas = null;
            $ventas = DB::table('ventas')
                ->join('pagos', 'pagos.venta_id', '=', 'ventas.id')
                ->join('estadopagos', 'estadopagos.id', '=', 'ventas.estadopago_id')
                ->join('tipopagos', 'tipopagos.id', '=', 'pagos.tipopago_id')
                ->where('ventas.sucursale_id', Auth::user()->sucursale_id)
                ->where('pagos.user_id', Auth::user()->id)
                ->whereBetween('ventas.fecha', [$this->fecInicio, $this->fecFin])
                ->orderBy('ventas.id', 'ASC')
                ->select('ventas.id', 'ventas.fecha', 'ventas.cliente', 'estadopagos.abreviatura as estadopago', 'ventas.importe', 'tipopagos.abreviatura as tipopago')
                ->get();

            $this->emit('datatableRender');
            $this->contenedor = $ventas;

        return view('livewire.ventas.listado', compact('ventas', 'estadoPagos', 'tipoPagos'));
    }

    public function exportar()
    {
        $usuario = array(Auth::user()->name,Auth::user()->sucursale->nombre);
        $fecInicio = $this->fecInicio;
        $fecFin = $this->fecFin;
        $contenedor = $this->contenedor;
        $pdf = Pdf::loadView('reports.ventas', compact('contenedor', 'usuario', 'fecInicio', 'fecFin'))->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "Ventas_" . $this->fecInicio . "_".$this->fecFin.".pdf"
        );
    }

    public function excel(){

        return Excel::download(new VentasExport($this->contenedor, $this->fecInicio, $this->fecFin), 'Ventas' . date('His') . '.xlsx');
    }
}
