<?php

use App\Models\Cierrecaja;
use App\Models\Cierrereservabono;
use App\Models\Entregalounch;
use App\Models\Feriado;
use App\Models\Licencia;

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
    $dias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
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
    if($licencia){
        return true;
    }else{
        return false;
    }
}

function tieneEntrega($estudiante_id,$fecha){
    $entrega = Entregalounch::where('estudiante_id', $estudiante_id)
    ->whereDate('fechaentrega', $fecha)->first();
    if($entrega){
        return true;
    }else{
        return false;
    }
}