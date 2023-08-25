<?php

namespace App\Http\Controllers;

use App\Models\Bonoanuale;
use App\Models\Bonofecha;
use App\Models\Bonomensuale;
use App\Models\Creditoprofesore;
use App\Models\Detallelonchera;
use App\Models\Entregalounch;
use App\Models\Estadopago;
use App\Models\Lonchera;
use App\Models\Pago;
use App\Models\Tipopago;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class VentaController
 * @package App\Http\Controllers
 */
class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('venta.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $venta = new Venta();
        return view('venta.create', compact('venta'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Venta::$rules);

        $venta = Venta::create($request->all());

        return redirect()->route('ventas.index')
            ->with('success', 'Venta created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $venta = Venta::find($id);
        $dv = $venta->detalleventas;
        $producto_id = 0;
        $detalleEstudiantes = array();
        foreach ($dv as $item) {
            $producto_id = $item->producto_id;
        }
        if ($producto_id) {
            switch ($producto_id) {
                case '1':
                    $bonoanual = Bonoanuale::where('venta_id', $id)->get();
                    foreach ($bonoanual as $bf) {
                        $contenido = "Bono Anual - Gestion " . $bf->gestion;
                        $detalleEstudiantes[] = array($bf->estudiante->nombre, $contenido,$bf->tipomenu->nombre,$bf->estudiante->codigo);
                    }
                    break;
                case '2':
                    $bonofechas = Bonofecha::where('venta_id', $id)->get();
                    foreach ($bonofechas as $bf) {
                        $contenido = "Bono fecha del " . $bf->fechainicio . " al " . $bf->fechafin;
                        $detalleEstudiantes[] = array($bf->estudiante->nombre, $contenido,$bf->tipomenu->nombre,$bf->estudiante->codigo);
                    }
                    break;
                case '3':
                    $loncheras = Lonchera::where('venta_id',$id)->get();                    
                    foreach ($loncheras as $lonchera) {
                        $detalles = $lonchera->detalleloncheras;
                        foreach ($detalles as $detalle) {
                            $detalleEstudiantes[] = array($lonchera->estudiante->nombre, "Reserva - Loncheras",$detalle->tipomenu->nombre,$lonchera->estudiante->codigo);    
                        }
                    }
                    break;
                case '4':
                    # code...
                    break;
                case '5':
                    $creditos = Creditoprofesore::where('venta_id',$id)->get();
                    foreach ($creditos as $credito) {
                        $contenido = "Plataforma Entregas Profesores";
                        $detalleEstudiantes[] = array($credito->estudiante->nombre, $contenido,"Almuerzo Completo",$credito->estudiante->codigo);
                    }
                    break;
            }
        }
        // $pago = Pago::where('venta_id',$venta->id)->first();
        $loncheras = Lonchera::where('venta_id', $venta->id)->get();
        return view('venta.show', compact('venta', 'loncheras', 'detalleEstudiantes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $venta = Venta::find($id);

        $tipopagos = Estadopago::all()->pluck('nombre', 'id');
        return view('venta.edit', compact('venta', 'tipopagos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Venta $venta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venta $venta)
    {
        request()->validate(Venta::$rules);

        $venta->update($request->all());

        return redirect()->route('ventas.index')
            ->with('success', 'Venta editada correctamente');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $venta = Venta::find($id);
            $pago = Pago::where('venta_id', $id)->first();

            $venta->estado = 0;
            $venta->save();

            $pago->estado = 0;
            $pago->save();

            $bonoanual = Bonoanuale::where('venta_id', $id)->first();
            $bonomensual = Bonofecha::where('venta_id', $id)->first();
            $lonchera = Lonchera::where('venta_id', $id)->first();
            $entregalounche = Entregalounch::where('venta_id', $id)->first();

            if ($bonoanual) {
                $bonoanual->estado = 0;
                $bonoanual->save();
            }

            if ($bonomensual) {
                $bonomensual->estado = 0;
                $bonomensual->save();
            }

            if ($lonchera) {
                $lonchera->estado = 0;
                $lonchera->save();

                $detallelonchera = Detallelonchera::where('lonchera_id', $lonchera->id)->get();
                foreach ($detallelonchera as $item) {
                    $item->estado = 0;
                    $item->save();
                }
            }

            if ($entregalounche) {
                $entregalounche->estado = 0;
                $entregalounche->save();
            }

            DB::commit();
            return redirect()->route('ventas.index')
                ->with('success', 'Venta anulada correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('ventas.index')
                ->with('error', 'Ha ocurrido un error');
        }
    }
}
