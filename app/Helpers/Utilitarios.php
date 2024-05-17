<?php

use App\Models\Bonoanuale;
use App\Models\Bonofecha;
use App\Models\Cierrecaja;
use App\Models\Cierrereservabono;
use App\Models\Entregalounch;
use App\Models\Estudiante;
use App\Models\Feriado;
use App\Models\Licencia;
use App\Models\Menu;
use App\Models\Moneda;
use App\Models\Preciomenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

function contarDiasSemana($fechaInicio, $fechaFin)
{
    $diasSemana = array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes');
    $contador = 0;
    $fechaActual = new DateTime($fechaInicio);

    while ($fechaActual <= new DateTime($fechaFin)) {
        $diaSemana = $fechaActual->format('N');
        if ($diaSemana >= 1 && $diaSemana <= 5) {
            if (!esFeriado(date_format($fechaActual, 'Y-m-d'))) {
                $contador++;
            }
        }
        $fechaActual->modify('+1 day');
    }

    return $contador;
}
function contarDias($fechaInicio, $fechaFin, $estudiante_id)
{
    $diasSemana = array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes');
    $contador = 0;
    $fechaActual = new DateTime($fechaInicio);

    while ($fechaActual <= new DateTime($fechaFin)) {
        $diaSemana = $fechaActual->format('N');
        if ($diaSemana >= 1 && $diaSemana <= 5) {
            if (!esFeriado(date_format($fechaActual, 'Y-m-d'))) {
                if (!tieneLicencia($estudiante_id, date_format($fechaActual, 'Y-m-d'))) {
                    $contador++;
                }
            }
        }
        $fechaActual->modify('+1 day');
    }

    return $contador;
}

function esFeriado($fecha)
{
    $feriado = Feriado::where('fecha', $fecha)->first();
    if ($feriado) {
        return true;
    } else {
        return false;
    }
}

function nombreDiaESP($fecha)
{
    $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
    $nombre_dia = $dias[date('w', strtotime($fecha))];
    return $nombre_dia;
}

function nombreMesESP($fecha)
{
    $meses = array(
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    );
    $nombre_mes = $meses[date('n', strtotime($fecha)) - 1];

    return $nombre_mes;
}

function fechaCorta($fecha)
{
    $dia = substr($fecha, 8, 2);
    $mes = nombreMesESP($fecha);
    return $dia . '-' . substr($mes, 0, 3);
}

function cantidadDiasMes($mes, $anio)
{
    $numeroDias = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
    return $numeroDias;
}

function cajaCerrada($user_id, $sucursale_id)
{
    $cierre = Cierrereservabono::where('fecha', date('Y-m-d'))
        ->where('user_id', $user_id)
        ->where('sucursale_id', $sucursale_id)
        ->first();
    if ($cierre) {
        return true;
    } else {
        return false;
    }
}
function cajaCerradaPOS($user_id, $sucursale_id)
{
    $cierre = Cierrecaja::where('fecha', date('Y-m-d'))
        ->where('user_id', $user_id)
        ->where('sucursale_id', $sucursale_id)
        ->first();
    if ($cierre) {
        return true;
    } else {
        return false;
    }
}

function tieneLicencia($estudiante_id, $fecha)
{
    $licencia = Licencia::where('estudiante_id', $estudiante_id)
        ->where('fecha', $fecha)->first();
    if ($licencia) {
        return true;
    } else {
        return false;
    }
}

function tieneEntrega($estudiante_id, $fecha)
{
    $entrega = Entregalounch::where('estudiante_id', $estudiante_id)
        ->whereDate('fechaentrega', $fecha)->where('estado', 1)->first();
    if ($entrega) {
        return true;
    } else {
        return false;
    }
}

function monedaCorto()
{
    $moneda = Moneda::first();
    return $moneda->abreviatura;
}

function monedaLargo()
{
    $moneda = Moneda::first();
    return $moneda->nombre;
}

function precioMenu($menu_id)
{
    $menu = Menu::find($menu_id);
    $precio = Preciomenu::where('tipomenu_id', $menu->tipomenu_id)->where('sucursale_id', Auth::user()->sucursale_id)->first();
    return $precio;
}

function precioTipoMenu($tipomenu_id)
{
    $precio = Preciomenu::where('tipomenu_id', $tipomenu_id)->where('sucursale_id', Auth::user()->sucursale_id)->first();
    return $precio;
}

function estadoPedidoEstudiante($estudiante_id)
{
    $estudiante = Estudiante::find($estudiante_id);
    $fechaInicio = date('Y') . '-01-01';
    $hoy = date('Y-m-d');
    $ba = Bonoanuale::where('estudiante_id', $estudiante->id)->where('gestion', date('Y'))->first();
    $bonoanual = 0;
    $bonofecha = 0;
    $reservas = 0;
    if ($ba) {
        $bonoanual = 200;
    } else {
        $bf = Bonofecha::where('estudiante_id', $estudiante->id)->where('fechafin', '>=', $fechaInicio)->where('estado', 1)->get();
        if ($bf) {
            foreach ($bf as $item) {
                $bonofecha += contarDias($item->fechainicio, $item->fechafin, $estudiante->id);
            }
        }

        $rs = DB::select("SELECT count(*) cantidad FROM detalleloncheras dt
        INNER JOIN loncheras l on l.id = dt.lonchera_id
        WHERE l.estudiante_id = " . $estudiante_id . "
        AND dt.fecha >= '" . $fechaInicio . "'
        AND l.estado = 1
        AND dt.estado = 1");

        if ($rs) {
            foreach ($rs as $item) {
                $reservas += $item->cantidad;
            }
        }
    }
    $pagado = $bonoanual + $bonofecha + $reservas;

    // ENTREGAS DE PRODUCTOS
    $entregas = 0;

    $rs2 = Entregalounch::where('estudiante_id', $estudiante_id)->where('fechaentrega', '>=', $fechaInicio)->where('estado', 1)->get();
    foreach ($rs2 as $resultado) {
        $entregas++;
    }

    $restantes = $pagado - $entregas;

    $tabla = array("estudiante" => $estudiante->nombre, "pagados" => $pagado, "entregas" => $entregas, "restantes" => $restantes, "codigo" => $estudiante->codigo, "bonoanual" => $bonoanual, "bonofecha" => $bonofecha, "reservas" => $reservas);
    return $tabla;
}

function estadoPedidoEstudianteHoy($estudiante_id)
{
    $estudiante = Estudiante::find($estudiante_id);
    $fechaInicio = date('Y') . '-01-01';
    $hoy = date('Y-m-d');
    $ba = Bonoanuale::where('estudiante_id', $estudiante->id)->where('gestion', date('Y'))->first();
    $bonoanual = 0;
    $bonofecha = 0;
    $reservas = 0;
    if ($ba) {
        $bonoanual = 200;
    } else {
        $bf = Bonofecha::where('estudiante_id', $estudiante->id)->where('fechafin', '>=', $hoy)->where('estado', 1)->get();
        if ($bf) {
            foreach ($bf as $item) {
                $bonofecha += contarDias($item->hoy, $item->fechafin, $estudiante->id);
            }
        }

        $rs = DB::select("SELECT count(*) cantidad FROM detalleloncheras dt
        INNER JOIN loncheras l on l.id = dt.lonchera_id
        WHERE l.estudiante_id = " . $estudiante_id . "
        AND dt.fecha >= '" . $hoy . "'
        AND l.estado = 1
        AND dt.estado = 1");

        if ($rs) {
            foreach ($rs as $item) {
                $reservas += $item->cantidad;
            }
        }
    }
    $pagado = $bonoanual + $bonofecha + $reservas;

    // ENTREGAS DE PRODUCTOS
    $entregas = 0;

    $rs2 = Entregalounch::where('estudiante_id', $estudiante_id)->where('fechaentrega', '>=', $hoy)->where('estado', 1)->get();
    foreach ($rs2 as $resultado) {
        $entregas++;
    }

    $restantes = $pagado - $entregas;

    $tabla = array("codigo" => $estudiante->codigo, "estudiante" => $estudiante->nombre, "restantes" => $restantes, "bonoanual" => $bonoanual, "bonofecha" => $bonofecha, "reservas" => $reservas);
    return $tabla;
}

function num2letras($num, $fem = false, $dec = true)
{
    if ($num != '') {
        $num = sprintf("%.2f", $num);
        $matuni[2]  = "dos";
        $matuni[3]  = "tres";
        $matuni[4]  = "cuatro";
        $matuni[5]  = "cinco";
        $matuni[6]  = "seis";
        $matuni[7]  = "siete";
        $matuni[8]  = "ocho";
        $matuni[9]  = "nueve";
        $matuni[10] = "diez";
        $matuni[11] = "once";
        $matuni[12] = "doce";
        $matuni[13] = "trece";
        $matuni[14] = "catorce";
        $matuni[15] = "quince";
        $matuni[16] = "dieciseis";
        $matuni[17] = "diecisiete";
        $matuni[18] = "dieciocho";
        $matuni[19] = "diecinueve";
        $matuni[20] = "veinte";
        $matunisub[2] = "dos";
        $matunisub[3] = "tres";
        $matunisub[4] = "cuatro";
        $matunisub[5] = "quin";
        $matunisub[6] = "seis";
        $matunisub[7] = "sete";
        $matunisub[8] = "ocho";
        $matunisub[9] = "nove";

        $matdec[2] = "veint";
        $matdec[3] = "treinta";
        $matdec[4] = "cuarenta";
        $matdec[5] = "cincuenta";
        $matdec[6] = "sesenta";
        $matdec[7] = "setenta";
        $matdec[8] = "ochenta";
        $matdec[9] = "noventa";
        $matsub[3]  = 'mill';
        $matsub[5]  = 'bill';
        $matsub[7]  = 'mill';
        $matsub[9]  = 'trill';
        $matsub[11] = 'mill';
        $matsub[13] = 'bill';
        $matsub[15] = 'mill';
        $matmil[4]  = 'millones';
        $matmil[6]  = 'billones';
        $matmil[7]  = 'de billones';
        $matmil[8]  = 'millones de billones';
        $matmil[10] = 'trillones';
        $matmil[11] = 'de trillones';
        $matmil[12] = 'millones de trillones';
        $matmil[13] = 'de trillones';
        $matmil[14] = 'billones de trillones';
        $matmil[15] = 'de billones de trillones';
        $matmil[16] = 'millones de billones de trillones';

        //Zi hack
        $float = explode('.', $num);
        $num = $float[0];

        $num = trim((string)@$num);
        if ($num[0] == '-') {
            $neg = 'menos ';
            $num = substr($num, 1);
        } else
            $neg = '';
        while ($num[0] == '0') $num = substr($num, 1);
        if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
        $zeros = true;
        $punt = false;
        $ent = '';
        $fra = '';
        for ($c = 0; $c < strlen($num); $c++) {
            $n = $num[$c];
            if (!(strpos(".,'''", $n) === false)) {
                if ($punt) break;
                else {
                    $punt = true;
                    continue;
                }
            } elseif (!(strpos('0123456789', $n) === false)) {
                if ($punt) {
                    if ($n != '0') $zeros = false;
                    $fra .= $n;
                } else

                    $ent .= $n;
            } else

                break;
        }
        $ent = '     ' . $ent;
        if ($dec and $fra and !$zeros) {
            $fin = ' coma';
            for ($n = 0; $n < strlen($fra); $n++) {
                if (($s = $fra[$n]) == '0')
                    $fin .= ' cero';
                elseif ($s == '1')
                    $fin .= $fem ? ' una' : ' un';
                else
                    $fin .= ' ' . $matuni[$s];
            }
        } else
            $fin = '';
        if ((int)$ent === 0) return 'Cero ' . $fin;
        $tex = '';
        $sub = 0;
        $mils = 0;
        $neutro = false;
        while (($num = substr($ent, -3)) != '   ') {
            $ent = substr($ent, 0, -3);
            if (++$sub < 3 and $fem) {
                $matuni[1] = 'una';
                $subcent = 'as';
            } else {
                $matuni[1] = $neutro ? 'un' : 'uno';
                $subcent = 'os';
            }
            $t = '';
            $n2 = substr($num, 1);
            if ($n2 == '00') {
            } elseif ($n2 < 21)
                $t = ' ' . $matuni[(int)$n2];
            elseif ($n2 < 30) {
                $n3 = $num[2];
                if ($n3 != 0) $t = 'i' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            } else {
                $n3 = $num[2];
                if ($n3 != 0) $t = ' y ' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }
            $n = $num[0];
            if ($n == 1) {
                $t = ' ciento' . $t;
            } elseif ($n == 5) {
                $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
            } elseif ($n != 0) {
                $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
            }
            if ($sub == 1) {
            } elseif (!isset($matsub[$sub])) {
                if ($num == 1) {
                    $t = ' mil';
                } elseif ($num > 1) {
                    $t .= ' mil';
                }
            } elseif ($num == 1) {
                $t .= ' ' . $matsub[$sub] . '?n';
            } elseif ($num > 1) {
                $t .= ' ' . $matsub[$sub] . 'ones';
            }
            if ($num == '000') $mils++;
            elseif ($mils != 0) {
                if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
                $mils = 0;
            }
            $neutro = true;
            $tex = $t . $tex;
        }
        $tex = $neg . substr($tex, 1) . $fin;
        $end_num = ucfirst($tex) . ' ' . $float[1] . '/100 ';
        return $end_num;
    }
}
