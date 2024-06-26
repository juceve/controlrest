<?php

namespace App\Http\Livewire\Entregas;

use App\Models\Bonoanuale;
use App\Models\Bonofecha;
use App\Models\Curso;
use App\Models\Estudiante;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Estadopedidos extends Component
{
    public $sucursale_id = null, $selCurso = "";
    public $curso = null, $tabla = null, $totalSaldos = 0;

    public function mount()
    {
        $this->sucursale_id = Auth::user()->sucursale_id;
    }
    public function render()
    {
        $cursos = DB::table('cursos')
            ->join('nivelcursos', 'nivelcursos.id', '=', 'cursos.nivelcurso_id')
            ->where('nivelcursos.sucursale_id', $this->sucursale_id)
            ->select('cursos.id', 'cursos.nombre')
            ->orderBy('cursos.nombre', 'ASC')->get();
        $this->emit('datatableRenderButton');
        return view('livewire.entregas.estadopedidos', compact('cursos'))->extends('layouts.app');
    }

    public function buscar()
    {
        $this->reset('totalSaldos', 'tabla');
        if ($this->selCurso != "") {
            $hoy = date('Y-m-d');
            $this->curso = Curso::find($this->selCurso);
            $estudiantes = Estudiante::where('curso_id', $this->curso->id)->get();
            $tabla = array();
            foreach ($estudiantes as $estudiante) {
                $tabla[] = estadoPedidoEstudiante($estudiante->id);
            }
            $this->tabla = $tabla;
        }
        // dd($tabla);
        $this->emit('datatableRenderButton');
    }

    public function totalSaldos()
    {
        $this->reset('totalSaldos', 'tabla');
        $totalsaldos = 0;
        $estudiantes = Estudiante::where('esestudiante', 1)->get();
        foreach ($estudiantes as $estudiante) {
            $saldos = estadoPedidoEstudiante($estudiante->id);
            $totalsaldos += $saldos['restantes'];
        }
        $this->totalSaldos = number_format($totalsaldos);
    }

    public function pdf()
    {
        $tabla = $this->tabla;
        $curso = $this->curso->nombre;
        $pdf = Pdf::loadView('reports.rptestadopedidos', compact('tabla', 'curso'))->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "EstadoPedidos_" . date('YmdHi') . ".pdf"
        );
        $this->emit('datatableRenderButton');
    }
}
