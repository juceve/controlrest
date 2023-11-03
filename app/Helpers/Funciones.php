<?php

use App\Http\Livewire\Entregas\Profesores;
use App\Models\Bonoanuale;
use App\Models\Bonofecha;
use App\Models\Creditoprofesore;
use App\Models\Detallelonchera;
use App\Models\Detalleventa;
use App\Models\Estudiante;
use App\Models\Licencia;
use App\Models\Lonchera;
use App\Models\Nivelcurso;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

function cambiosPorFeriado($fechaFeriado)
{
    $cambios = 0;
    $resultado = array();
    $niveles = Nivelcurso::where('sucursale_id', Auth::user()->sucursale_id)->get();
    $bonofechas = null;
    DB::beginTransaction();
    try {
        foreach ($niveles as $nivel) {
            foreach ($nivel->cursos as $curso) {
                foreach ($curso->estudiantes as $estudiante) {
                    if (!tieneLicencia($estudiante->id, $fechaFeriado)) {

                        $bonofechas = Bonofecha::where('estudiante_id', $estudiante->id)
                            ->where('fechainicio', '<=', $fechaFeriado)
                            ->where('fechafin', '>=', $fechaFeriado)
                            ->get();

                        $loncheras = Lonchera::where('estudiante_id', $estudiante->id)
                            ->get();

                        $detalles = [];
                        foreach ($loncheras as $item) {
                            $detallesloncheras = Detallelonchera::where('lonchera_id', $item->id)->where('fecha', $fechaFeriado)->where('entregado', 0)->get();
                            foreach ($detallesloncheras as $detallelonchera) {
                                $detalles[] = $detallelonchera;
                            }
                        }

                        if ($bonofechas->count() > 0) {
                            foreach ($bonofechas as $bono) {
                                $resultado[] = $bono;
                                $fechafin = new DateTime($bono->fechafin);
                                $i = 0;
                                while ($i < 1) {
                                    $fechafin->modify('+1 day');
                                    $dia = $fechafin->format('N');
                                    if ($dia >= 1 && $dia <= 5) {
                                        if (!esFeriado(date_format($fechafin, 'Y-m-d'))) {
                                            $bono->fechafin = date_format($fechafin, 'Y-m-d');
                                            $bono->save();
                                            $i++;
                                            $cambios++;
                                        }
                                    }
                                }
                            }
                        }

                        if (count($detalles) > 0) {
                            foreach ($detalles as $detalle) {
                                $detalle->estado = 0;
                                $detalle->save();
                                $fechafin = new DateTime($detalle->fecha);

                                $i = 0;
                                while ($i < 1) {
                                    $fechafin->modify('+1 day');
                                    $dia = $fechafin->format('N');
                                    if ($dia >= 1 && $dia <= 5) {
                                        if (!esFeriado(date_format($fechafin, 'Y-m-d'))) {
                                            $detalle = Detallelonchera::create([
                                                "fecha" => date_format($fechafin, 'Y-m-d'),
                                                "tipomenu_id" => $detalle->tipomenu_id,
                                                "lonchera_id" => $detalle->lonchera_id,
                                                "entregado" => 0,
                                            ]);
                                            $i++;
                                            $cambios++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        DB::commit();
        return $cambios;
    } catch (\Throwable $th) {

        DB::rollBack();
        return 0;
    }
}


function traeEstudiantesVenta($venta_id)
{
    $estudiantes = "";
    $venta = Venta::find($venta_id);
    $detalleventa = $venta->detalleventas->first();
    switch ($detalleventa->producto_id) {
        case '1': {
                $bonoanual = Bonoanuale::where('venta_id', $venta_id)->get();
                if ($bonoanual) {
                    foreach ($bonoanual as $item) {
                        if ($estudiantes == "") {
                            $estudiantes = $item->estudiante->nombre;
                        } else {
                            $estudiantes .= " - " . $item->estudiante->nombre;
                        }
                    }
                }
            }
            break;
        case '2': {
                $detalle = Bonofecha::where('venta_id', $venta_id)->get();
                if ($detalle) {
                    foreach ($detalle as $item) {
                        if ($estudiantes == "") {
                            $estudiantes = $item->estudiante->nombre;
                        } else {
                            $estudiantes .= " - " . $item->estudiante->nombre;
                        }
                    }
                }
            }
            break;
        case '3': {
                $loncheras = Lonchera::where('venta_id', $venta_id)->get();
                if ($loncheras) {
                    foreach ($loncheras as $item) {
                        if ($estudiantes == "") {
                            $estudiantes = $item->estudiante->nombre;
                        } else {
                            $estudiantes .= " - " . $item->estudiante->nombre;
                        }
                    }
                }
            }
            break;
        case '4': {
                $estudiantes = "VENTA POS";
            }
            break;
        case '5': {
                $profesores = Creditoprofesore::where('venta_id', $venta_id)->get();
                if ($profesores) {
                    foreach ($profesores as $item) {
                        if ($estudiantes == "") {
                            $estudiantes = $item->estudiante->nombre;
                        } else {
                            $estudiantes .= " - " . $item->estudiante->nombre;
                        }
                    }
                }
            }
            break;
    }

    return $estudiantes;
}

function ventasHoy()
{
    $hoy = date('Y-m-d');
    $misventas = Venta::where([
        ['fecha', $hoy],
        // ['user_id', Auth::user()->id],
        ['estado', 1],
        // ['estadopago_id', 2]
    ])->get();

    $productos = Producto::where('id', '<>', 5)->get();
    $arrayVentas = [];
    foreach ($productos as $producto) {
        $totalpr = 0;
        $totalpp = 0;
        $cantOperaciones = 0;
        $totalEF =  0;
        $totalTB =  0;
        $totalQR =  0;
        $totalCR =  0;
        $totalGA =  0;

        foreach ($misventas as $venta) {
            $aVentas = $venta->detalleventas->first();
            if ($aVentas->producto_id == $producto->id) {

                if ($venta->tipopago_id == 1) {
                    $totalEF += $venta->importe;
                    if ($venta->estadopago_id == 2) {

                        $totalpr += $venta->importe;
                    } else {

                        $totalpp += $venta->importe;
                    }
                }
                if ($venta->tipopago_id == 2) {
                    $totalTB += $venta->importe;
                    if ($venta->estadopago_id == 2) {

                        $totalpr += $venta->importe;
                    } else {

                        $totalpp += $venta->importe;
                    }
                }
                if ($venta->tipopago_id == 3) {
                    $totalQR += $venta->importe;
                    if ($venta->estadopago_id == 2) {
                        $totalpr += $venta->importe;
                    } else {
                        $totalpp += $venta->importe;
                    }
                }
                if ($venta->tipopago_id == 4) {

                    $totalCR += $venta->importe;
                    if ($venta->estadopago_id == 2) {
                        $totalpr += $venta->importe;
                    } else {
                        $totalpp += $venta->importe;
                    }
                }
                if ($venta->tipopago_id == 5) {
                    $totalGA += $venta->importe;
                    if ($venta->estadopago_id == 2) {
                        $totalpr += $venta->importe;
                    } else {
                        $totalpp += $venta->importe;
                    }
                }
                $cantOperaciones++;
            }
        }
        $arrayVentas[] = array(
            $producto->nombre,
            $cantOperaciones,
            $totalEF,
            $totalTB,
            $totalQR,
            $totalCR,
            $totalGA,
            $totalpr,
            $totalpp,
        );
    }
    return $arrayVentas;
}

function misVentasHoyTotales()
{
    $hoy = date('Y-m-d');
    $misventas = Venta::where([
        ['fecha', $hoy],
        ['user_id', Auth::user()->id],
        ['estado', 1],
        // ['estadopago_id', 2]
    ])->get();

    // $misventas = DB::table('ventas')
    // ->leftJoin('pagos','ventas.id','=','pagos.venta_id')
    // ->where([
    //     ['ventas.fecha', $hoy],
    //     ['ventas.user_id', Auth::user()->id],
    //     ['ventas.estado', 1],
    //     // ['pagos.tipopago','<>','CREDITO']
    // ])
    // ->select('ventas.id','ventas.importe','pagos.tipopago_id')
    // ->get();

    $productos = Producto::where('id', '<>', 5)->get();
    $arrayVentas = [];
    foreach ($productos as $producto) {
        $totalpr = 0;
        $totalpp = 0;
        $cantOperaciones = 0;
        $totalEF =  0;
        $totalTB =  0;
        $totalQR =  0;
        $totalCR =  0;
        $totalGA =  0;

        foreach ($misventas as $venta) {
            $aVentas = $venta->detalleventas->first();
            if ($aVentas->producto_id == $producto->id) {

                if ($venta->tipopago_id == 1) {
                    $totalEF += $venta->importe;
                    if ($venta->estadopago_id == 2) {

                        $totalpr += $venta->importe;
                    } else {

                        $totalpp += $venta->importe;
                    }
                }
                if ($venta->tipopago_id == 2) {
                    $totalTB += $venta->importe;
                    if ($venta->estadopago_id == 2) {

                        $totalpr += $venta->importe;
                    } else {

                        $totalpp += $venta->importe;
                    }
                }
                if ($venta->tipopago_id == 3) {
                    $totalQR += $venta->importe;
                    if ($venta->estadopago_id == 2) {
                        $totalpr += $venta->importe;
                    } else {
                        $totalpp += $venta->importe;
                    }
                }
                if ($venta->tipopago_id == 4) {

                    $totalCR += $venta->importe;
                    if ($venta->estadopago_id == 2) {
                        $totalpr += $venta->importe;
                    } else {
                        $totalpp += $venta->importe;
                    }
                }
                if ($venta->tipopago_id == 5) {
                    $totalGA += $venta->importe;
                    if ($venta->estadopago_id == 2) {
                        $totalpr += $venta->importe;
                    } else {
                        $totalpp += $venta->importe;
                    }
                }
                $cantOperaciones++;
            }
        }
        $arrayVentas[] = array(
            $producto->nombre,
            $cantOperaciones,
            $totalEF,
            $totalTB,
            $totalQR,
            $totalCR,
            $totalGA,
            $totalpr,
            $totalpp,
        );
    }
    return $arrayVentas;
}
