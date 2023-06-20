<?php

namespace App\Http\Livewire\Entregas;

use App\Http\Livewire\Ventas\Bonoanual;
use App\Models\Bonoanuale;
use App\Models\Bonofecha;
use App\Models\Creditoprofesore;
use App\Models\Detallelonchera;
use App\Models\Detalleventa;
use App\Models\Entregalounch;
use App\Models\Estudiante;
use App\Models\Evento;
use App\Models\Pago;
use App\Models\Preciomenu;
use App\Models\Tipomenu;
use App\Models\Tipopago;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Profesores extends Component
{
    public $cedula = "", $estudiante = null, $evento = null, $indicador = "";

    public $reservaP;

    public function mount()
    {
        $this->indicador = request()->indicador;
    }

    protected $listeners = ['entrega', 'entregaReserva','entregaBonos'];

    public function render()
    {

        return view('livewire.entregas.profesores')->extends('layouts.web2');
    }

    public function buscar()
    {
        $this->estudiante = Estudiante::where('cedula', $this->cedula)
            ->where('esestudiante', 0)
            ->first();
        if ($this->estudiante) {
            $hoy = date('Y-m-d');
            $entregaLounche = Entregalounch::where('estudiante_id', $this->estudiante->id)
                ->whereDate('fechaentrega', $hoy)
                ->first();
            if ($entregaLounche) {
                $this->emit('warning', 'Producto ya entregado.');
            } else {
                $sql = "SELECT e.nombre estudiante, e.codigo, dl.id detalle_id,dl.entregado, dl.tipomenu_id,tm.nombre tipomenu,el.fechaentrega, v.id venta_id
                            FROM estudiantes e
                            INNER JOIN loncheras l on l.estudiante_id = e.id
                            INNER JOIN ventas v on v.id = l.venta_id
                            INNER JOIN detalleloncheras dl on dl.lonchera_id = l.id
                            INNER JOIN tipomenus tm on tm.id = dl.tipomenu_id
                            LEFT JOIN entregalounches el on el.detallelonchera_id = dl.id      
                            WHERE e.id = " . $this->estudiante->id . "
                            AND dl.fecha = '" . $hoy . "'
                            AND l.habilitado = 1
                            ORDER BY tm.id ASC";
                $productos = DB::select($sql);
                $bonofecha = Bonofecha::where('estudiante_id', $this->estudiante->id)
                    ->where('fechainicio', '<=', $hoy)
                    ->where('fechafin', '>=', $hoy)
                    ->where('estado', 1)->first();
                $bonoanual = Bonoanuale::where('estudiante_id', $this->estudiante->id)
                    ->where('gestion', date('Y'))
                    ->where('estado', 1)->first();

                $reserva = "";
                foreach ($productos as $producto) {
                    $reserva = $producto;
                }
                $data = "";
                if ($reserva) {
                    $evento = Evento::where('fecha', date('Y-m-d'))->first();
                    $menu = "";
                    foreach ($evento->detalleeventos as $item) {
                        if ($item->menu->tipomenu->id == $reserva->tipomenu_id) {
                            $menu = $item->menu;
                        }
                    }
                    $this->reservaP = array($reserva->detalle_id, $menu->id, $reserva->venta_id, $reserva->tipomenu_id, $reserva->tipomenu);
                    $data = $this->estudiante->id . "|" . $this->estudiante->nombre . "|" . $reserva->entregado . "|" . $reserva->tipomenu;
                    $this->emit('reserva', $data);
                } else {
                    if ($bonofecha) {
                        $evento = Evento::where('fecha', date('Y-m-d'))->first();
                        $menu = "";
                        foreach ($evento->detalleeventos as $item) {
                            if ($item->menu->tipomenu->id == $bonofecha->tipomenu_id) {
                                $menu = $item->menu;
                            }
                        }
                        $this->reservaP = array($menu->id, $bonofecha->venta_id, $bonofecha->tipomenu_id, $bonofecha->tipomenu->nombre);
                        $data = $this->estudiante->id . "|" . $this->estudiante->nombre . "|" . $bonofecha->tipomenu->nombre;
                        $this->emit('bonos', $data);
                    } else {
                        if ($bonoanual) {
                            $evento = Evento::where('fecha', date('Y-m-d'))->first();
                            $menu = "";
                            foreach ($evento->detalleeventos as $item) {
                                if ($item->menu->tipomenu->id == $bonoanual->tipomenu_id) {
                                    $menu = $item->menu;
                                }
                            }
                            $this->reservaP = array($menu->id, $bonoanual->venta_id, $bonoanual->tipomenu_id, $bonoanual->tipomenu->nombre);
                            $data = $this->estudiante->id . "|" . $this->estudiante->nombre . "|" . $bonoanual->tipomenu->nombre;
                            $this->emit('bonos', $data);
                        } else {
                            $data = $this->estudiante->id . "|" . $this->estudiante->nombre;
                            $this->emit('question', $data);
                        }
                    }
                }
            }
        } else {
            $this->emit('error', 'Docente no encontrado');
            $this->cedula = "";
        }
    }

    public function entrega()
    {

        DB::beginTransaction();
        try {
            $tipo = Tipopago::find(4);
            $tipomenu = Tipomenu::find(1);

            $precioMenu = Preciomenu::where('tipomenu_id', $tipomenu->id)
                ->where('nivelcurso_id', $this->estudiante->curso->nivelcurso_id)->first();

            $venta = Venta::create([
                "fecha" => date('Y-m-d'),
                "cliente" => $this->estudiante->nombre,
                "estadopago_id" => 1,
                "importe" => $precioMenu->precio,
                "sucursale_id" => Auth::user()->sucursale_id,
                "plataforma" => "p-profesores",
                "observaciones" => "Entrega mediante Plataforma Profesores",
                "tipopago_id" => $tipo->id,
                "user_id" => Auth::user()->id,
            ]);
            $detalleventa = Detalleventa::create([
                'venta_id' => $venta->id,
                'descripcion' => "ENTREGA PROFESORES",
                'cantidad' => 1,
                'preciounitario' => $precioMenu->precio,
                'subtotal' => $precioMenu->precio,
                'producto_id' => 5,
            ]);

            // $pago = Pago::create([
            //     "fecha" => date('Y-m-d'),
            //     "recibo" => '0',
            //     "tipopago_id" => $tipo->id,
            //     "tipopago" => $tipo->nombre,
            //     "sucursal_id" => Auth::user()->sucursale_id,
            //     "sucursal" => Auth::user()->sucursale->nombre,
            //     "importe" => $precioMenu->precio,
            //     "venta_id" => $venta->id,
            //     "estadopago_id" => 1,
            //     "tipoinicial" => $tipo->nombre,
            // ]);

            $credito = Creditoprofesore::create([
                "estudiante_id" => $this->estudiante->id,
                "venta_id" => $venta->id,
                "sucursale_id" => Auth::user()->sucursale_id,
            ]);

            $evento = Evento::where('fecha', date('Y-m-d'))->first();
            $menu = "";
            foreach ($evento->detalleeventos as $item) {
                if ($item->menu->tipomenu->id == 1) {
                    $menu = $item->menu;
                }
            }

            if ($evento) {
                $entrega = Entregalounch::create([
                    "fechaentrega" => date('Y-m-d H:i:s'),
                    "estudiante_id" => $this->estudiante->id,
                    "producto_id" => 5,
                    "menu_id" => $menu->id,
                    "venta_id" => $venta->id,
                    "user_id" => Auth::user()->id,
                    "sucursale_id" => Auth::user()->sucursale_id,
                    "observaciones" => "ENTREGA PLATAFORMA"
                ]);
            }

            $row = "";
            $row = $entrega->id . "|" . $tipomenu->nombre . "|" . $this->estudiante->nombre . "|" . $entrega->fechaentrega . "|" . $tipomenu->nombre . "|Plataforma Entregas";

            DB::commit();
             redirect('http://127.0.0.1/gprinter/public/printPOS3/' . $row); //IMPRESION MEDIANTE LOCALHOST DEL CLIENTE
            // return redirect()->route('entregas.profesores')->with('success', 'Entrega registrada correctamente.');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->emit('error', $th->getMessage());
        }
    }

    public function entregaReserva()
    {

        DB::beginTransaction();
        try {
            // if ($this->reserva) {
            $dlonchera = Detallelonchera::find($this->reservaP[0]);
            $dlonchera->entregado = 1;
            $dlonchera->save();

            $entrega = Entregalounch::create([
                "fechaentrega" => date('Y-m-d H:i:s'),
                "estudiante_id" => $this->estudiante->id,
                "menu_id" => $this->reservaP[1],
                "venta_id" => $this->reservaP[2],
                "user_id" => Auth::user()->id,
                "sucursale_id" => Auth::user()->sucursale_id,
                "observaciones" => "ENTREGA PLATAFORMA"
            ]);
            $row = "";
            $row = $entrega->id . "|" . $this->reservaP[3] . "|" . $this->estudiante->nombre . "|" . $entrega->fechaentrega . "|" . $this->reservaP[4] . "|Plataforma Entregas";

            DB::commit();
            // redirect('http://127.0.0.1/gprinter/public/printPOS3/' . $row); //IMPRESION MEDIANTE LOCALHOST DEL CLIENTE
            return redirect()->route('entregas.profesores')->with('success', 'Entrega registrada correctamente.');
            // }
        } catch (\Throwable $th) {
            DB::rollback();
            $this->emit('error', $th->getMessage());
        }
    }
    public function entregaBonos()
    {
        DB::beginTransaction();
        try {
            // $this->reservaP = array( $menu->id, $bonofecha->pago->venta_id, $bonofecha->tipomenu_id, $bonofecha->tipomenu->nombre);
            $entrega = Entregalounch::create([
                "fechaentrega" => date('Y-m-d H:i:s'),
                "estudiante_id" => $this->estudiante->id,
                "menu_id" => $this->reservaP[0],
                "venta_id" => $this->reservaP[1],
                "user_id" => Auth::user()->id,
                "sucursale_id" => Auth::user()->sucursale_id,
                "observaciones" => "ENTREGA PLATAFORMA"
            ]);
            $row = "";
            $row = $entrega->id . "|" . $this->reservaP[2] . "|" . $this->estudiante->nombre . "|" . $entrega->fechaentrega . "|" . $this->reservaP[3] . "|Plataforma Entregas";

            DB::commit();
            // redirect('http://127.0.0.1/gprinter/public/printPOS3/' . $row); //IMPRESION MEDIANTE LOCALHOST DEL CLIENTE
            return redirect()->route('entregas.profesores')->with('success', 'Entrega registrada correctamente.');
            // }
        } catch (\Throwable $th) {
            DB::rollback();
            $this->emit('error', $th->getMessage());
        }
    }
}
