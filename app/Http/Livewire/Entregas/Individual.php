<?php

namespace App\Http\Livewire\Entregas;

use App\Models\Bonoanuale;
use App\Models\Bonofecha;
use App\Models\Detalleevento;
use App\Models\Detallelonchera;
use App\Models\Entregalounch;
use App\Models\Estudiante;
use App\Models\Evento;
use App\Models\Feriado;
use App\Models\Licencia;
use App\Models\Menu;
use App\Models\Tipomenu;
use App\Models\Utilitario;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Individual extends Component
{
    public $estudiantes = null, $estudiante = null, $busqueda = "", $productos = null, $buscaCodigo = "", $indicador = "", $bonoanual = null, $bonofecha = null, $entregas = null, $detalle = null;

    public function mount()
    {
        $this->indicador = request()->indicador;
    }

    public function render()
    {
        if ($this->busqueda == "") {
            $this->reset(['estudiantes']);
        } else {
            $sucursale_id = Auth::user()->sucursale_id;
            $sql = "SELECT e.*, c.nombre curso, n.nombre nivel, t.nombre tutor FROM estudiantes e
        INNER JOIN cursos c on c.id = e.curso_id
        INNER JOIN nivelcursos n on n.id = c.nivelcurso_id
        INNER JOIN tutores t on t.id = e.tutore_id
        WHERE n.id = $sucursale_id 
        AND e.codigo LIKE '%" . $this->busqueda . "%'  AND e.tutore_id <> '0'
        OR e.nombre LIKE '%" . $this->busqueda . "%'  AND e.tutore_id <> '0'
        OR e.cedula LIKE '%" . $this->busqueda . "%'  AND e.tutore_id <> '0'
        OR t.nombre LIKE '%" . $this->busqueda . "%'  AND e.tutore_id <> '0'
        OR c.nombre LIKE '%" . $this->busqueda . "%' AND e.tutore_id <> '0'";
            $this->estudiantes = DB::select($sql);
        }

        return view('livewire.entregas.individual')->extends('layouts.app');
    }

    public function updatedBuscaCodigo()
    {
        $this->buscaXCodigo();
    }

    public function resetAll()
    {
        $this->reset(['estudiantes', 'estudiante', 'busqueda', 'productos', 'bonoanual', 'bonofecha', 'buscaCodigo', 'detalle']);
    }

    public function buscaXCodigo()
    {
        $this->reset(['estudiante', 'productos', 'bonoanual', 'bonofecha', 'entregas',  'licencia', 'detalle']);
        if ($this->buscaCodigo != "") {
            $codigo = str_pad($this->buscaCodigo, 10, "0", STR_PAD_LEFT);
            $this->estudiante = Estudiante::where('codigo', $codigo)->first();
            // dd($this->estudiante);
            if ($this->estudiante) {
                $this->buscarDatos($this->estudiante->id);
            }
        }
    }

    public function seleccionaEstudiante($estudiante_id)
    {
        $this->reset(['estudiante', 'productos', 'bonoanual', 'bonofecha', 'entregas', 'buscaCodigo', 'licencia']);
        $this->estudiante = Estudiante::find($estudiante_id);
        $this->buscarDatos($estudiante_id);
    }



    public $menu = null, $licencia;
    public $menudeldia = null;
    public function buscarDatos($estudiante_id)
    {
        $this->licencia = Licencia::where('estudiante_id', $estudiante_id)->where('fecha', date('Y-m-d'))->first();
        if (!$this->licencia) {

            $this->bonoanual = Bonoanuale::where('estudiante_id', $estudiante_id)
                ->where('gestion', date('Y'))
                ->where('estado', 1)
                ->first();

            if ($this->bonoanual) {
                $this->detalle = DB::table('detalleeventos')
                    ->join('eventos', 'eventos.id', '=', 'detalleeventos.evento_id')
                    ->join('menus', 'menus.id', '=', 'detalleeventos.menu_id')
                    ->join('tipomenus', 'tipomenus.id', '=', 'menus.tipomenu_id')
                    ->where('eventos.fecha', date('Y-m-d'))
                    ->where('tipomenus.id', $this->bonoanual->tipomenu_id)
                    ->select('detalleeventos.*', 'menus.nombre AS menu', 'menus.id AS menu_id')->get();

                $this->menu = Menu::find($this->detalle[0]->menu_id);


                $sql = "SELECT * FROM entregalounches el
                where DATE(fechaentrega) = '" . date('Y-m-d') . "'
                AND estudiante_id = " . $this->bonoanual->estudiante_id . "
                AND venta_id  = " . $this->bonoanual->venta_id;
                $this->entregas = DB::select($sql);
            } else {
                $this->bonofecha = Bonofecha::where('estudiante_id', $estudiante_id)
                    ->whereDate('fechainicio', '<=', date('Y-m-d'))
                    ->whereDate('fechafin', '>=', date('Y-m-d'))
                    ->where('estado', 1)
                    ->first();

                if ($this->bonofecha) {
                    $this->detalle = DB::table('detalleeventos')
                        ->join('eventos', 'eventos.id', '=', 'detalleeventos.evento_id')
                        ->join('menus', 'menus.id', '=', 'detalleeventos.menu_id')
                        ->join('tipomenus', 'tipomenus.id', '=', 'menus.tipomenu_id')
                        ->where('eventos.fecha', date('Y-m-d'))
                        ->where('tipomenus.id', $this->bonofecha->tipomenu_id)
                        ->select('detalleeventos.*', 'menus.nombre AS menu', 'menus.id AS menu_id')->get();
                    // dd($this->detalle);
                    if ($this->bonofecha->tipomenu_id == 3) {
                        $this->menu = Menu::where('tipomenu_id', $this->bonofecha->tipomenu_id)->get();
                    } else {
                        $this->menu = Menu::find($this->detalle[0]->menu_id);
                    }

                    $sql = "SELECT * FROM entregalounches el
                where DATE(fechaentrega) = '" . date('Y-m-d') . "'
                AND estudiante_id = " . $this->bonofecha->estudiante_id . "
                AND venta_id  = " . $this->bonofecha->venta_id;
                    $this->entregas = DB::select($sql);
                } else {
                    $hoy = date('Y-m-d');
                    $sql = "SELECT dl.tipomenu_id, tm.nombre tipomenu, dl.id detalle_id,v.id venta_id, m.nombre menu, dl.menu_id, dl.entregado,el.fechaentrega FROM estudiantes e
                INNER JOIN loncheras l on l.estudiante_id = e.id
                INNER JOIN detalleloncheras dl on dl.lonchera_id = l.id
                INNER JOIN menus m on m.id = dl.menu_id
                INNER JOIN tipomenus tm on tm.id = dl.tipomenu_id
                INNER JOIN ventas v on v.id = l.venta_id   
                LEFT JOIN entregalounches el on el.estudiante_id = l.estudiante_id AND date(el.fechaentrega) = '" . $hoy . "'
                        WHERE e.id = $estudiante_id
                        AND dl.fecha = '" . $hoy . "'
                        AND l.habilitado = 1
                        ORDER BY tm.id ASC";
                    $this->productos = DB::select($sql);
                }
            }
        }
    }

    public function entregaProducto($detalle_id, $venta_id, $menu_id)
    {
        DB::beginTransaction();
        $row = "";
        $menu = Menu::find($menu_id);
        try {
            if ($this->bonoanual) {
                $this->reset(['detalle']);

                $entrega = Entregalounch::create([
                    "fechaentrega" => date('Y-m-d H:i:s'),
                    "menu_id" => $menu_id,
                    "producto_id" => 1,
                    "venta_id" => $this->bonoanual->venta_id,
                    "user_id" => Auth::user()->id,
                    "sucursale_id" => Auth::user()->sucursale_id,
                    "estudiante_id" => $this->estudiante->id,
                    "observaciones" => "ENTREGA INDIVIDUAL"
                ]);

                $row = $entrega->id . "|" . $menu->tipomenu->nombre . "|" . $this->estudiante->nombre . "|" . $entrega->fechaentrega . "|" . $menu->nombre . "|" . Auth::user()->name;
            } else {
                if ($this->bonofecha) {
                    $this->reset(['detalle']);
                    $entrega = Entregalounch::create([
                        "fechaentrega" => date('Y-m-d H:i:s'),
                        "menu_id" => $menu->id,
                        "producto_id" => 2,
                        "venta_id" => $this->bonofecha->venta_id,
                        "user_id" => Auth::user()->id,
                        "sucursale_id" => Auth::user()->sucursale_id,
                        "estudiante_id" => $this->estudiante->id,
                        "observaciones" => "ENTREGA INDIVIDUAL"
                    ]);

                    $row = $entrega->id . "|" . $menu->tipomenu->nombre . "|" . $this->estudiante->nombre . "|" . $entrega->fechaentrega . "|" . $menu->nombre . "|" . Auth::user()->name;
                } else {

                    $detalle = Detallelonchera::find($detalle_id);
                    $detalle->entregado = true;
                    $detalle->save();
                    $entrega = Entregalounch::create([
                        "fechaentrega" => date('Y-m-d H:i:s'),
                        "detallelonchera_id" => $detalle_id,
                        "menu_id" => $menu->id,
                        "producto_id" => 3,
                        "venta_id" => $venta_id,
                        "user_id" => Auth::user()->id,
                        "sucursale_id" => Auth::user()->sucursale_id,
                        "estudiante_id" => $this->estudiante->id,
                        "observaciones" => "ENTREGA INDIVIDUAL"
                    ]);
                    $row = $entrega->id . "|" . $menu->tipomenu->nombre . "|" . $this->estudiante->nombre . "|" . $entrega->fechaentrega . "|" . $menu->nombre . "|" . Auth::user()->name;
                }
            }
            //INCLUIR CODIGO PARA ENVIO DE MENSAJE DE ENTREGA AL CORREO

            DB::commit();
            redirect('http://127.0.0.1/gprinter/public/printPOS2/' . $row);
            // return redirect()->route('entregas.individual')
            // ->with('success', 'Entrega Realizada con Exito.');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->resetAll();
            $this->emit('error', $th->getMessage());
        }
    }
}
