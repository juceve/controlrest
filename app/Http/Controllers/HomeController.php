<?php

namespace App\Http\Controllers;

use App\Models\Bonoanuale;
use App\Models\Bonofecha;
use App\Models\Entregalounch;
use App\Models\Estudiante;
use App\Models\Menu;
use App\Models\Moneda;
use App\Models\Producto;
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
        $productos = Producto::all();

        $sucursale_id = Auth::user()->sucursale_id;
        if (Auth::user()->estado == 0) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Consulte con el Admnistrador del Sistema');
        }
        $hoy = date('Y-m-d');
        $gestion = date('Y');

        if ($sucursale_id) {

            $sql1 = "SELECT bf.* from bonofechas bf
            INNER JOIN estudiantes e on e.id = bf.estudiante_id
            INNER JOIN cursos c on c.id = e.curso_id
            INNER JOIN nivelcursos nc on nc.id = c.nivelcurso_id
            WHERE fechainicio <= '$hoy'
            AND fechafin >= '$hoy'
            AND bf.estado = 1
            AND nc.sucursale_id = $sucursale_id";

            $sql2 = "SELECT ba.* from bonoanuales ba
            INNER JOIN ventas v on v.id = ba.venta_id
            WHERE gestion <= '$gestion'
            AND ba.estado = 1
            AND v.sucursale_id =$sucursale_id";

            $sql3 = "SELECT dl.* from detalleloncheras dl
            INNER JOIN loncheras l on l.id = dl.lonchera_id
            WHERE dl.fecha = '$hoy'
            AND l.estado = 1
            AND dl.estado = 1";

            $bonoanual = DB::select($sql2);
            $bonofechas = DB::select($sql1);
            $loncheras = DB::select($sql3);

            $reservasS = 0;
            $reservasC = 0;
            $reservasE = 0;
            $totalReservas = 0;

            foreach ($bonoanual as $item) {
                switch ($item->tipomenu_id) {
                    case '1':
                        $reservasC++;
                        break;
                    case '2':
                        $reservasS++;
                        break;
                    case '3':
                        $reservasE++;
                        break;
                }
                $totalReservas++;
            }
            foreach ($bonofechas as $item) {
                switch ($item->tipomenu_id) {
                    case '1':
                        $reservasC++;
                        break;
                    case '2':
                        $reservasS++;
                        break;
                    case '3':
                        $reservasE++;
                        break;
                }
                $totalReservas++;
            }
            foreach ($loncheras as $item) {
                switch ($item->tipomenu_id) {
                    case '1':
                        $reservasC++;
                        break;
                    case '2':
                        $reservasS++;
                        break;
                    case '3':
                        $reservasE++;
                        break;
                }
                $totalReservas++;
            }
            $reservas = array("COMPLETOS", $reservasC, "SIMPLES", $reservasS, "EXTRAS", $reservasE, $totalReservas);


            // PUNTO DE VENTA

            $sql4 = "SELECT tipomenu_id, SUM(dv.cantidad) cantidad FROM detalleventas dv
            INNER JOIN ventas v on dv.venta_id = v.id
            WHERE fecha = '$hoy'
            AND producto_id = 4
            AND estado = 1
            AND v.sucursale_id = $sucursale_id
            GROUP BY dv.tipomenu_id";

            $pos = DB::select($sql4);
            $cantC = 0;
            $cantS = 0;
            $cantE = 0;
            $totalPos = 0;
            foreach ($pos as $item) {
                switch ($item->tipomenu_id) {
                    case '1':
                        $cantC += $item->cantidad;
                        break;
                    case '2':
                        $cantS += $item->cantidad;
                        break;
                    case '3':
                        $cantE += $item->cantidad;
                        break;
                }
                $totalPos += $item->cantidad;
            }

            $arrPos = array("COMPLETOS", $cantC, "SIMPLES", $cantS, "EXTRAS", $cantE, $totalPos);


            // PENDIENTES DE PAGO

            $sqlpp = "SELECT tipomenu_id, SUM(cantidad) cantidad FROM
            (SELECT dv.tipomenu_id, count(*) cantidad FROM detalleventas dv
            INNER JOIN ventas v on v.id = dv.venta_id
            WHERE v.estadopago_id = 1
            AND v.fecha = '$hoy'
            AND v.sucursale_id = $sucursale_id
            AND v.estado = 1
            AND dv.producto_id = 4
            GROUP BY dv.tipomenu_id
            UNION
            SELECT bf.tipomenu_id, count(*) cantidad FROM bonofechas bf
            INNER JOIN ventas v on v.id = bf.venta_id
            WHERE v.estadopago_id = 1
            AND fechainicio <= '$hoy'
            AND fechafin >= '$hoy'
            AND v.sucursale_id = $sucursale_id
            AND v.estado = 1
            GROUP BY bf.tipomenu_id
            UNION
            SELECT ba.tipomenu_id, count(*) cantidad from bonoanuales ba
            INNER JOIN ventas v on v.id = ba.venta_id
            WHERE v.estadopago_id = 1
            AND gestion = '$gestion'
            AND v.sucursale_id = $sucursale_id
            AND v.estado = 1
            GROUP BY ba.tipomenu_id
            UNION
            SELECT dl.tipomenu_id, count(*) cantidad FROM detalleloncheras dl
            INNER JOIN loncheras l on l.id = dl.lonchera_id
            INNER JOIN ventas v on v.id = l.venta_id
            WHERE dl.fecha = '$hoy'
            AND v.sucursale_id = $sucursale_id
            AND v.estado = 1
            GROUP BY dl.tipomenu_id) AS res1
            GROUP BY tipomenu_id;";

            $pendientespago = DB::select($sqlpp);
            $cantC = 0;
            $cantS = 0;
            $cantE = 0;
            $totalPP = 0;
            foreach ($pendientespago as $item) {
                switch ($item->tipomenu_id) {
                    case '1':
                        $cantC += $item->cantidad;
                        break;
                    case '2':
                        $cantS += $item->cantidad;
                        break;
                    case '3':
                        $cantE += $item->cantidad;
                        break;
                }
                $totalPP += $item->cantidad;
            }

            $arrPP = array("COMPLETOS", $cantC, "SIMPLES", $cantS, "EXTRAS", $cantE, $totalPP);


            // Profesores

            $sql5 = "SELECT dv.* from detalleventas dv
            INNER JOIN ventas v on v.id = dv.venta_id
            WHERE v.fecha = '$hoy'
            AND v.estado = 1
            AND v.sucursale_id = $sucursale_id
            AND dv.producto_id = 5";

            $profesores = DB::select($sql5);
            $cantC = 0;
            $cantS = 0;
            $cantE = 0;
            $totalprofesores = 0;
            foreach ($profesores as $item) {
                switch ($item->tipomenu_id) {
                    case '1':
                        $cantC += $item->cantidad;
                        break;
                    case '2':
                        $cantS += $item->cantidad;
                        break;
                    case '3':
                        $cantE += $item->cantidad;
                        break;
                }
                $totalprofesores += $item->cantidad;
            }

            $arrProf = array("COMPLETOS", $cantC, "SIMPLES", $cantS, "EXTRAS", $cantE, $totalprofesores);


            // Entregas
            $sql6 = "SELECT m.tipomenu_id FROM entregalounches el
            INNER JOIN menus m on m.id = el.menu_id
            WHERE DATE(fechaentrega) = '$hoy'
            AND el.estado = 1
            AND el.sucursale_id = $sucursale_id";

            $entregas = DB::select($sql6);
            $cantC = 0;
            $cantS = 0;
            $cantE = 0;
            $totalentregas = 0;
            foreach ($entregas as $item) {
                switch ($item->tipomenu_id) {
                    case '1':
                        $cantC++;
                        break;
                    case '2':
                        $cantS++;
                        break;
                    case '3':
                        $cantE++;
                        break;
                }
                $totalentregas++;
            }

            $arrEntregas = array("COMPLETOS", $cantC, "SIMPLES", $cantS, "EXTRAS", $cantE, $totalentregas);

            $misVentas = null;
            $moneda = Moneda::all()->first();
            if (Auth::user()->roles[0]->name == "VENTAS") {
                $misVentas = misVentasHoyTotales();
            }
            if (Auth::user()->roles[0]->name == "Admin" || Auth::user()->roles[0]->name == "SUPERVISOR") {
                $misVentas = ventasHoy();
            }


            return view('home', compact('moneda',  'reservas', 'arrPos', 'arrProf', 'arrPP', 'arrEntregas', 'misVentas'));
        } else {
            return view('home');
        }
    }
}
