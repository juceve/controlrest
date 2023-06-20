<?php

namespace App\Http\Controllers;

use App\Models\Bonoanuale;
use App\Models\Bonofecha;
use App\Models\Entregalounch;
use App\Models\Menu;
use App\Models\Moneda;
use App\Models\Tipomenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sucursale_id = Auth::user()->sucursale_id;
        if (Auth::user()->estado == 0) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Consulte con el Admnistrador del Sistema');
        }

        if ($sucursale_id) {
            $bonosAnuales = $this->pedidosAnual();

            $bonosFecha = $this->bonosFecha();

            $loncheras = $this->loncheras();

            $ventadirectas = $this->ventasDirectas();

            $tipomenus = Tipomenu::all();

            $resultados = array();
            $extras = array();



            foreach ($tipomenus as $tipo) {
                $contador = 0;
                $contadorI = 0;
                $contadorVD = 0;
                foreach ($bonosAnuales as $banual) {
                    if ($banual->tipomenu_id == $tipo->id) {
                        $contador++;
                    }
                    if ($banual->tipomenu_id == 3) {
                        $extras[] = $banual->menu_id;
                    }
                }

                foreach ($bonosFecha as $bfecha) {
                    if ($bfecha->tipomenu_id == $tipo->id) {
                        $contador++;
                    }
                    if ($bfecha->tipomenu_id == 3) {
                        $extras[] = $bfecha->menu_id;
                    }
                }

                foreach ($loncheras as $lonchera) {
                    if ($lonchera->tipomenu_id == $tipo->id) {
                        if ($lonchera->habilitado) {
                            $contador++;
                        } else {
                            $contadorI++;
                        }

                        if ($lonchera->tipomenu_id == 3) {
                            $extras[] = $lonchera->menu_id;
                        }
                    }
                }

                foreach ($ventadirectas as $venta) {
                    if ($venta->menu->tipomenu_id == $tipo->id) {
                        $contadorVD++;

                        if ($venta->menu->tipomenu_id == 3) {
                            $extras[] = $venta->menu_id;
                        }
                    }
                }
                $total = $contador + $contadorI;
                $resultados[] = array(
                    "tipomenu_id" => $tipo->id,
                    "tipomenu" => $tipo->nombre,
                    "reservas" => $contador,
                    "reservasi" => $contadorI,
                    "ventadirecta" => $contadorVD,
                    "total" => $total
                );
            }
            $menuExtras = array_unique($extras);
            $menuExtras = array_values($menuExtras);
            $detalleExtra = array();
            if ($menuExtras) {
                foreach ($menuExtras as $menuextra) {
                    $contador = 0;
                    $menu = Menu::find($menuextra);
                    if ($menu) {
                        foreach ($extras as $extra) {
                            if ($menuextra == $extra) {
                                $contador++;
                            }
                        }
                        $detalleExtra[] = array(
                            "menu_id" => $menuextra,
                            "menu" => $menu->nombre,
                            "cantidad" => $contador
                        );
                    }
                }
            }


            $reservas = array();
            foreach ($resultados as $resultado) {
                $reservas[] = array($resultado['tipomenu_id'], $resultado['tipomenu'], $resultado['reservas']);
            }

            $puntoventas = array();
            foreach ($resultados as $resultado) {
                $puntoventas[] = array($resultado['tipomenu_id'], $resultado['tipomenu'], $resultado['ventadirecta']);
            }
            // dd($puntoventas);
            $pendientepagos = array();
            foreach ($resultados as $resultado) {
                $pendientepagos[] = array($resultado['tipomenu_id'], $resultado['tipomenu'], $resultado['reservasi']);
            }

            // ENTREGAS

            $entregas = DB::select("SELECT DATE(el.fechaentrega),tm.id tipomenu_id, tm.nombre tipomenu,COUNT(*) cantidad from entregalounches el
        INNER JOIN menus m on m.id = el.menu_id
        INNER JOIN tipomenus tm on tm.id = m.tipomenu_id
        WHERE DATE(fechaentrega) = '" . date('Y-m-d') . "'
        AND el.sucursale_id = " . Auth::user()->sucursale_id . "
        GROUP BY date(el.fechaentrega),tm.id, tm.nombre");

            $moneda = Moneda::all()->first();

            return view('home', compact('moneda', 'resultados', 'extras', 'detalleExtra', 'reservas', 'puntoventas', 'pendientepagos', 'entregas'));
        } else {
            return view('home');
        }
    }

    public function pedidosAnual()
    {
        $hoy = date('Y-m-d');
        $bonos = Bonoanuale::where('gestion', date('Y'))
            ->where('estado', 1)
            ->get();
        return $bonos;
    }

    public function bonosFecha()
    {
        $hoy = date('Y-m-d');
        $bonos = Bonofecha::whereDate('fechainicio', '<=',  $hoy)
            ->whereDate('fechafin', '>=',  $hoy)
            ->where('estado', 1)
            ->get();
        return $bonos;
    }

    public function loncheras()
    {
        $hoy = date('Y-m-d');
        $loncheras = DB::table('loncheras')
            ->join('detalleloncheras', 'detalleloncheras.lonchera_id', '=', 'loncheras.id')
            ->where('detalleloncheras.fecha', $hoy)
            ->select('detalleloncheras.*',  'loncheras.habilitado')
            ->get();

        return $loncheras;
    }

    public function ventasDirectas()
    {
        $hoy = date('Y-m-d');
        $ventasdirectas = Entregalounch::join('ventas', 'ventas.id', '=', 'entregalounches.venta_id')
            ->whereDate('fechaentrega', $hoy)
            ->whereNull('detallelonchera_id')
            ->where('ventas.cliente', 'VENTA POS')
            ->get();
        return $ventasdirectas;
    }
}
