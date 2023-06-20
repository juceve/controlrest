<?php

namespace App\Http\Livewire\Reportes;

use App\Models\Bonoanuale;
use App\Models\Bonofecha;
use App\Models\Curso;
use App\Models\Detallelonchera;
use App\Models\Licencia;
use App\Models\Tipomenu;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Diario extends Component
{
    public $selFecha = "", $tabla1 = array(), $tabla11 = array(), $tabla2 = array(), $tabla3 = array(), $tabla4 = array(), $licenciasHoy = null;


    public function render()
    {
        // $this->reportexnivel();
        return view('livewire.reportes.diario')->extends('layouts.app');
    }

    public function generar()
    {

        if ($this->selFecha != "") {
            $this->emit('loading');
            $this->pagadosvsservidos();
            $this->reportexnivel();
        } else {
            $this->emit('warning', 'Debe seleccionar una fecha');
        }
    }

    public function pagadosvsservidos()
    {
        $this->reset(['tabla1']);
        $hoy = date('Y-m-d');
        $sql1 = "SELECT m.tipomenu_id, tm.nombre tipomenu, COUNT(*) cantidad FROM entregalounches el
        INNER JOIN menus m on m.id = el.menu_id
        INNER JOIN tipomenus tm on tm.id = m.tipomenu_id
        WHERE DATE(el.fechaentrega) = '" . $this->selFecha . "'
        AND el.observaciones != 'AUSENCIA INJUSTIFICADA'
        AND el.observaciones != 'ENTREGA PLATAFORMA'
        GROUP BY m.tipomenu_id, tm.nombre";
        $servidos = DB::select($sql1);

        $sql2 = "SELECT tipomenu_id, tipomenu, SUM(cantidad) cantidad FROM 
        (SELECT 'LONCHERA' tipo, dl.tipomenu_id,tm.nombre tipomenu,count(*) cantidad from detalleloncheras dl
        INNER JOIN loncheras l on l.id = dl.lonchera_id
        INNER JOIN tipomenus tm on tm.id = dl.tipomenu_id
        WHERE l.habilitado = 1
        AND dl.fecha = '" . $this->selFecha . "'        
        GROUP BY dl.tipomenu_id,tm.nombre
        UNION
        SELECT 'BONOANUAL' tipo, ba.tipomenu_id, tm.nombre tipomenu, count(*) cantidad FROM bonoanuales ba
        INNER JOIN tipomenus tm on tm.id = ba.tipomenu_id
        WHERE ba.estado = 1
        AND ba.gestion = '" . date('Y') . "'
        GROUP BY ba.tipomenu_id, tm.nombre
        UNION
        SELECT 'BONOFECHA' tipo,bf.tipomenu_id, tm.nombre tipomenu, count(*) cantidad FROM bonofechas bf
        INNER JOIN tipomenus tm on tm.id = bf.tipomenu_id
        WHERE bf.fechainicio <= '" . $this->selFecha . "'
        AND bf.fechafin >= '" . $this->selFecha . "'
        GROUP BY bf.tipomenu_id, tm.nombre
        UNION
        SELECT 'POS' tipo,tipomenu_id, tm.nombre tipomenu ,SUM(cantidad) cantidad FROM ventas v
        INNER JOIN detalleventas dv on dv.venta_id = v.id
        INNER JOIN tipomenus tm on tm.id = dv.tipomenu_id
        WHERE dv.producto_id = 4
        AND v.fecha = '" . $this->selFecha . "'
        GROUP BY tipomenu_id, tm.nombre) AS resultado
        GROUP BY  tipomenu_id, tipomenu";
        $pagados = DB::select($sql2);

        $sql3 = "SELECT m.tipomenu_id, tm.nombre tipomenu, COUNT(*) cantidad FROM entregalounches el
        INNER JOIN menus m on m.id = el.menu_id
        INNER JOIN tipomenus tm on tm.id = m.tipomenu_id
        WHERE DATE(el.fechaentrega) = '" . $this->selFecha . "'
        AND el.observaciones = 'AUSENCIA INJUSTIFICADA'
        GROUP BY m.tipomenu_id, tm.nombre";
        $ausencias = DB::select($sql3);

        $sql4 = "SELECT tipomenu_id, COUNT(*) cantidad from licencias
        WHERE fecha = '" . $this->selFecha . "'
        GROUP BY tipomenu_id";
        $licencias = DB::select($sql4);

        $sql5 = "SELECT 'COMPRAS Y RESERVAS' tipo, count(*) cantidad from detalleloncheras dl
        INNER JOIN loncheras l on l.id = dl.lonchera_id
        INNER JOIN tipomenus tm on tm.id = dl.tipomenu_id
        WHERE l.habilitado = 1
        AND dl.fecha = '" . $this->selFecha . "'        
        GROUP BY tipo
        UNION
        SELECT 'BONOS ANUALES' tipo, count(*) cantidad FROM bonoanuales ba
        INNER JOIN tipomenus tm on tm.id = ba.tipomenu_id
        WHERE ba.estado = 1
        AND ba.gestion = '" . date('Y') . "'
        GROUP BY tipo
        UNION
        SELECT 'BONOS POR FECHA' tipo, count(*) cantidad FROM bonofechas bf
        INNER JOIN tipomenus tm on tm.id = bf.tipomenu_id
        WHERE bf.fechainicio <= '" . $this->selFecha . "'
        AND bf.fechafin >= '" . $this->selFecha . "'
        GROUP BY tipo
        UNION
        SELECT 'PUNTO DE VENTA' tipo,SUM(cantidad) cantidad FROM ventas v
        INNER JOIN detalleventas dv on dv.venta_id = v.id
        INNER JOIN tipomenus tm on tm.id = dv.tipomenu_id
        WHERE dv.producto_id = 4
        AND v.fecha = '" . $this->selFecha . "'
        GROUP BY tipo
				UNION
				SELECT 'PROFESORES' tipo,SUM(cantidad) cantidad FROM detalleventas dv
				INNER JOIN ventas v on v.id = dv.venta_id
        INNER JOIN tipomenus tm on tm.id = dv.tipomenu_id
        WHERE dv.producto_id = 5
        AND v.fecha = '" . $this->selFecha . "'
        GROUP BY tipo";

        $tabla11 = DB::select($sql5);
       foreach ($tabla11 as $tab) {
        $this->tabla11[] = array($tab->tipo,$tab->cantidad);
       }

        // dd($servidos);
        $resultados = [];
        $tipomenus = Tipomenu::all();
        foreach ($tipomenus as $tipomenu) {

            $cpagados = 0;
            $cservidos = 0;
            $causencias = 0;
            $clicencias = 0;
            foreach ($pagados as $item) {
                if ($item->tipomenu_id == $tipomenu->id) {
                    $cpagados = $item->cantidad;
                }
            }

            foreach ($servidos as $item2) {
                if ($item2->tipomenu_id == $tipomenu->id) {
                    $cservidos = $item2->cantidad;
                }
            }
            foreach ($ausencias as $item3) {
                if ($item3->tipomenu_id == $tipomenu->id) {
                    $causencias = $item3->cantidad;
                }
            }
            foreach ($licencias as $item4) {
                if ($item4->tipomenu_id == $tipomenu->id) {
                    $clicencias = $item4->cantidad;
                }
            }
            $resultados[] = array($tipomenu->id, $tipomenu->nombre, $cpagados, $cservidos, $causencias, $clicencias);
        }
        $this->tabla1 = $resultados;
    }

    public function reportexnivel()
    {
        $this->reset(['tabla2', 'tabla3', 'tabla4']);
        // $hoy = date('Y-m-d');
        $sql1 = "SELECT e.curso_id, m.tipomenu_id, tm.nombre tipomenu, COUNT(*) cantidad FROM entregalounches el
        INNER JOIN estudiantes e on e.id = el.estudiante_id
        INNER JOIN menus m on m.id = el.menu_id
        INNER JOIN tipomenus tm on tm.id = m.tipomenu_id
        WHERE DATE(el.fechaentrega) = '" . $this->selFecha . "'
        AND el.observaciones <> 'AUSENCIA INJUSTIFICADA'
        GROUP BY e.curso_id,m.tipomenu_id, tm.nombre";
        $servidos = DB::select($sql1);

        // dd($servidos);

        $sql2 = "SELECT curso_id, tipomenu_id, tipomenu, SUM(cantidad) cantidad FROM 
        (SELECT 'LONCHERA' tipo, e.curso_id,dl.tipomenu_id,tm.nombre tipomenu,count(*) cantidad from detalleloncheras dl				
        INNER JOIN loncheras l on l.id = dl.lonchera_id
				INNER JOIN estudiantes e on e.id = l.estudiante_id
        INNER JOIN tipomenus tm on tm.id = dl.tipomenu_id
        WHERE l.habilitado = 1
        AND dl.fecha = '" . $this->selFecha . "'
        GROUP BY e.curso_id, dl.tipomenu_id,tm.nombre
        UNION
        SELECT 'BONOANUAL' tipo, e.curso_id, ba.tipomenu_id, tm.nombre tipomenu, count(*) cantidad FROM bonoanuales ba
				INNER JOIN estudiantes e on e.id = ba.estudiante_id
        INNER JOIN tipomenus tm on tm.id = ba.tipomenu_id
        WHERE ba.estado = 1
        AND ba.gestion = '" . substr($this->selFecha, 0, 4) . "'
        GROUP BY e.curso_id, ba.tipomenu_id, tm.nombre
        UNION
        SELECT 'BONOFECHA' tipo, e.curso_id, bf.tipomenu_id, tm.nombre tipomenu, count(*) cantidad FROM bonofechas bf
				INNER JOIN estudiantes e on e.id = bf.estudiante_id
        INNER JOIN tipomenus tm on tm.id = bf.tipomenu_id
        WHERE bf.fechainicio <= '" . $this->selFecha . "'
        AND bf.fechafin >= '" . $this->selFecha . "'
        GROUP BY e.curso_id, bf.tipomenu_id, tm.nombre) AS resultado
        GROUP BY  curso_id, tipomenu_id, tipomenu";
        $pagados = DB::select($sql2);

        $cursosPRI = Curso::where('nivelcurso_id', 1)->get();
        $cursosSEC = Curso::where('nivelcurso_id', 2)->get();
        $cursosPRO = Curso::where('nivelcurso_id', 3)->get();
        $tipomenus = Tipomenu::all();
        $licenciasHoy = Licencia::where('fecha', $this->selFecha)->get();

        $datatabla2 = [];

        foreach ($cursosPRI as $curso) {
            $licencias = 0;
            foreach ($licenciasHoy as $licencia) {
                if ($licencia->estudiante->curso_id == $curso->id) {
                    $licencias++;
                }
            }
            $resultados = [];
            foreach ($tipomenus as $tipomenu) {

                $cpagados = 0;
                $cservidos = 0;
                foreach ($pagados as $item) {
                    if ($item->tipomenu_id == $tipomenu->id && $item->curso_id == $curso->id) {
                        $cpagados = $item->cantidad;
                    }
                }

                foreach ($servidos as $item2) {
                    if ($item2->tipomenu_id == $tipomenu->id && $item2->curso_id == $curso->id) {
                        $cservidos = $item2->cantidad;
                    }
                }
                $resultados[] = array($tipomenu->id, $tipomenu->nombre, $cpagados, $cservidos);
            }
            $this->tabla2[] = array($curso->id, $curso->nombre, $resultados, $licencias);
        }

        foreach ($cursosSEC as $curso) {
            $licencias = 0;
            foreach ($licenciasHoy as $licencia) {
                if ($licencia->estudiante->curso_id == $curso->id) {
                    $licencias++;
                }
            }
            $resultados = [];
            foreach ($tipomenus as $tipomenu) {

                $cpagados = 0;
                $cservidos = 0;
                foreach ($pagados as $item) {
                    if ($item->tipomenu_id == $tipomenu->id && $item->curso_id == $curso->id) {
                        $cpagados = $item->cantidad;
                    }
                }

                foreach ($servidos as $item2) {
                    if ($item2->tipomenu_id == $tipomenu->id && $item2->curso_id == $curso->id) {
                        $cservidos = $item2->cantidad;
                    }
                }
                $resultados[] = array($tipomenu->id, $tipomenu->nombre, $cpagados, $cservidos);
            }
            $this->tabla3[] = array($curso->id, $curso->nombre, $resultados, $licencias);
        }

        foreach ($cursosPRO as $curso) {
            $licencias = 0;
            foreach ($licenciasHoy as $licencia) {
                if ($licencia->estudiante->curso_id == $curso->id) {
                    $licencias++;
                }
            }
            $resultados = [];
            foreach ($tipomenus as $tipomenu) {

                $cpagados = 0;
                $cservidos = 0;
                foreach ($pagados as $item) {
                    if ($item->tipomenu_id == $tipomenu->id && $item->curso_id == $curso->id) {
                        $cpagados = $item->cantidad;
                    }
                }

                foreach ($servidos as $item2) {
                    if ($item2->tipomenu_id == $tipomenu->id && $item2->curso_id == $curso->id) {
                        $cservidos = $item2->cantidad;
                    }
                }
                $resultados[] = array($tipomenu->id, $tipomenu->nombre, $cpagados, $cservidos);
            }
            $this->tabla4[] = array($curso->id, $curso->nombre, $resultados, $licencias);
        }
        $this->emit('unLoading');
    }

    public function pdf()
    {
        $tabla1 = $this->tabla1;
        $tabla11 = $this->tabla11;
        $tabla2 = $this->tabla2;
        $tabla3 = $this->tabla3;
        $tabla4 = $this->tabla4;
        $fecha = $this->selFecha;
        $pdf = Pdf::loadView('reports.rptdiario', compact('tabla1', 'tabla11','tabla2', 'tabla3','tabla4','fecha'))->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "Ctr_Pagado_Servido_" . $this->selFecha . ".pdf"
        );
    }
}
