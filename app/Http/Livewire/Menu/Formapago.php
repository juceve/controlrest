<?php

namespace App\Http\Livewire\Menu;

use App\Models\Cartmenu;
use App\Models\Detallelonchera;
use App\Models\Detalleventa;
use App\Models\Estadopago;
use App\Models\Estudiante;
use App\Models\Logsid;
use App\Models\Lonchera;
use App\Models\Pago;
use App\Models\Preciomenu;
use App\Models\Sucursale;
use App\Models\Tipopago;
use App\Models\Tutore;
use App\Models\Venta;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use PHPUnit\Exception as PHPUnitException;

class Formapago extends Component
{
    use WithFileUploads;
    public $bCedula, $bNombre, $bColegio, $bCurso, $busquedaEst, $estudiante = null, $arrEstudiantes = array(), $dnone = "d-none";
    public $busquedatutor, $tipoBusqueda, $tutor, $totaltutor = 0, $arrPedidos = array(), $selEstudiantes = [], $dnone2 = "d-none";
    public $total_estudiante = 0, $tipoBusquedaEstudiante = "estudiante", $checkEstudiante;
    public $cartMenu, $countCart, $formapago = '', $comprobante;

    public $logErrorPagos, $logErrorVentas, $logErrorLoconchera, $sucursal,$sucursal_id;

    public function mount($tipoBusqueda)
    {
        $this->tipoBusqueda = $tipoBusqueda;
        $this->cartMenu = Cartmenu::where('session', session('idCarrito'))->orderBy('fecha', 'asc')->get();
        $this->countCart = $this->cartMenu->count();
    }

    public function updatedBusquedaEst()
    {
        $this->resetForms();
    }
    public function render()
    {
        $tipoPagos = Tipopago::all();
        return view('livewire.menu.formapago', compact('tipoPagos'))->extends('layouts.web2');
    }

    public function buscarEstudiante()
    {
        if ($this->busquedaEst != "") {
            $estudiantes = Estudiante::where('codigo', str_pad($this->busquedaEst, 10, "0", STR_PAD_LEFT))->get();
            if ($estudiantes->count() > 0) {
                foreach ($estudiantes as $item) {
                    $this->estudiante = $item;
                    $this->busquedaEst = $item->codigo;
                    $this->bCedula = $item->cedula;
                    $this->bNombre = $item->nombre;
                    $this->bColegio = $item->curso->nivelcurso->sucursale->nombre;
                    $this->bCurso = $item->curso->nivelcurso->nombre . '-' . $item->curso->nombre;
                    $this->dnone = "";
                    $this->estudiante = $item;
                    $precio = 0;
                    foreach ($this->cartMenu as $cart) {
                        $precio = Preciomenu::where('sucursale_id', $item->curso->nivelcurso->sucursale_id)
                            ->where('nivelcurso_id', $item->curso->nivelcurso_id)
                            ->where('tipomenu_id', $cart->menu->tipomenu_id)
                            ->first();
                        $this->total_estudiante = ($this->total_estudiante + $precio->precio);
                    }
                    if ($this->total_estudiante > 0) {
                        $this->dnone2 = "";
                    }
                }
            } else {
                $this->resetForms();
                $this->emit('ocultarEstPago');
                $this->emit('alertDanger');
            }
        }
    }

    public function buscarTutor()
    {
        if ($this->busquedatutor != "" && $this->busquedatutor != "0") {
            $tutores = Tutore::where('cedula', $this->busquedatutor)->get();
            if ($tutores->count() > 0) {
                foreach ($tutores as $tutor) {
                    $this->tutor = $tutor;
                    $estudiantes = Estudiante::where('tutore_id', $tutor->id)->get();
                    foreach ($estudiantes as $estudiante) {
                        $subtotal = 0;
                        foreach ($this->cartMenu as $cart) {
                            $precio = Preciomenu::where('sucursale_id', $estudiante->curso->nivelcurso->sucursale_id)
                                ->where('nivelcurso_id', $estudiante->curso->nivelcurso_id)
                                ->where('tipomenu_id', $cart->menu->tipomenu_id)
                                ->first();
                            $subtotal = ($subtotal + $precio->precio);
                        }
                        $this->sucursal_id = $estudiante->curso->nivelcurso->sucursale_id;
                        $this->sucursal = $estudiante->curso->nivelcurso->sucursale->nombre;
                        $est = array([
                            "id" => $estudiante->id,
                            "codigo" => $estudiante->codigo,
                            "nombre" => $estudiante->nombre,
                            "sucursal_id" => $estudiante->curso->nivelcurso->sucursale_id,
                            "sucursal" => $estudiante->curso->nivelcurso->sucursale->nombre,
                            "curso" => $estudiante->curso->nombre . " - " . strtoupper(substr($estudiante->curso->nivelcurso->nombre, 0, 4)),
                            "datos" => "COD: " . str_replace('0', '', $estudiante->codigo) . " - " . $estudiante->nombre . " - " . $estudiante->curso->nombre . " - " . strtoupper(substr($estudiante->curso->nivelcurso->nombre, 0, 3)),
                            "subtotal" => $subtotal,


                        ]);
                        $this->arrEstudiantes[] = $est;
                    }
                }
                $this->dnone = "";
            } else {
                $this->resetFormsT();
                $this->emit('alertDanger');
            }
        }
    }

    public function updatedSelEstudiantes()
    {
        $this->totaltutor = 0;
        foreach ($this->arrEstudiantes as $item) {
            foreach ($this->selEstudiantes as $sel) {
                if ($sel == $item[0]["id"]) {
                    $this->totaltutor = $this->totaltutor + $item[0]["subtotal"];
                }
            }
        }
        if ($this->totaltutor == 0) {
            $this->dnone2 = "d-none";
        } else {
            $this->dnone2 = "";
        }
    }

    public function resetForms()
    {
        $this->reset(['bCedula', 'bNombre', 'bColegio', 'bCurso',  'estudiante', 'arrEstudiantes', 'total_estudiante', 'dnone',]);
    }
    public function resetFormsT()
    {
        $this->reset(['arrEstudiantes', 'dnone', 'busquedatutor']);
    }

    public function next()
    {
        // if ($this->tipoBusqueda == "e") {
        //     if (($this->countCart > 0)) {
        //         if ($this->formapago != '') {
        //             $formapago = Tipopago::find($this->formapago);
        //             switch ($this->formapago) {
        //                 case '1': {
        //                         DB::beginTransaction();
        //                         try {
        //                             $venta = $this->guardaVenta(1);
        //                             $this->logErrorVentas = $venta->id;

        //                             $pago = $this->guardaPago(1, $this->formapago, $venta->id);
        //                             $this->logErrorPagos = $pago->id;

        //                             $lonchera = Lonchera::create([
        //                                 "fecha" => now(),
        //                                 "estudiante_id" => $this->estudiante->id,
        //                                 "pago_id" => $pago->id,
        //                                 "habilitado" => false,
        //                             ]);
        //                             $this->logErrorLoconchera = $lonchera->id;
        //                             foreach ($this->cartMenu as $cart) {
        //                                 $detalle = Detallelonchera::create([
        //                                     "fecha" => $cart->fecha,
        //                                     "menu_id" => $cart->menu_id,
        //                                     "lonchera_id" => $lonchera->id,
        //                                     "entregado" => false,
        //                                 ]);
        //                             }
        //                             DB::commit();
        //                             $this->finalizaTransaccion();
        //                             return redirect()->route('fintransc', $venta->id);
        //                         } catch (\Throwable $th) {
        //                             DB::rollBack();
        //                             if ($this->logErrorVentas) {
        //                                 $log = $this->guardaLogError($this->logErrorVentas, 'ventas', $th->getMessage());
        //                             }
        //                             $this->emit('alertError', 'logID: ' . $log->id);
        //                         }
        //                     }
        //                     break;
        //                 case '2': {
        //                         if (!is_null($this->comprobante)) {
        //                             DB::beginTransaction();
        //                             try {
        //                                 $venta = $this->guardaVenta(1);
        //                                 $this->logErrorVentas = $venta->id;
        //                                 $pago = $this->guardaPago(1, $this->formapago, $venta->id);
        //                                 $this->logErrorPagos = $pago->id;

        //                                 $this->validate([
        //                                     'comprobante' => 'image|max:1024', // 1MB Max
        //                                 ]);
        //                                 $file = $this->comprobante->storeAs('depositos/' . $formapago->abreviatura, $venta->id . "." . $this->comprobante->extension());

        //                                 $lonchera = Lonchera::create([
        //                                     "fecha" => now(),
        //                                     "estudiante_id" => $this->estudiante->id,
        //                                     "pago_id" => $pago->id,
        //                                     "habilitado" => false,
        //                                 ]);
        //                                 $this->logErrorLoconchera = $lonchera->id;
        //                                 foreach ($this->cartMenu as $cart) {
        //                                     $detalle = Detallelonchera::create([
        //                                         "fecha" => $cart->fecha,
        //                                         "menu_id" => $cart->menu_id,
        //                                         "lonchera_id" => $lonchera->id,
        //                                         "entregado" => false,
        //                                     ]);
        //                                 }

        //                                 DB::commit();
        //                                 $this->finalizaTransaccion();
        //                                 return redirect()->route('fintransc', $venta->id);
        //                             } catch (\Throwable $th) {
        //                                 DB::rollBack();
        //                                 if ($this->logErrorVentas) {
        //                                     $log = $this->guardaLogError($this->logErrorVentas, 'ventas', $th->getMessage());
        //                                 }
        //                                 if ($this->logErrorPagos) {
        //                                     $log = $this->guardaLogError($this->logErrorPagos, 'pagos', $th->getMessage());
        //                                 }

        //                                 $this->emit('alertError', 'logID: ' . $log->id);
        //                             }
        //                         } else {
        //                             $this->emit('alertComprobante');
        //                         }
        //                     }
        //                     break;
        //                 case '3': {
        //                         if (!is_null($this->comprobante)) {
        //                             DB::beginTransaction();
        //                             try {
        //                                 $venta = $this->guardaVenta(1);
        //                                 $this->logErrorVentas = $venta->id;
        //                                 $pago = $this->guardaPago(1, 3, $venta->id);
        //                                 $this->logErrorPagos = $pago->id;

        //                                 $this->validate([
        //                                     'comprobante' => 'image|max:1024', // 1MB Max
        //                                 ]);
        //                                 $file = $this->comprobante->storeAs('depositos/' . $formapago->abreviatura, $venta->id . "." . $this->comprobante->extension());

        //                                 $lonchera = Lonchera::create([
        //                                     "fecha" => now(),
        //                                     "estudiante_id" => $this->estudiante->id,
        //                                     "pago_id" => $pago->id,
        //                                     "habilitado" => false,
        //                                 ]);
        //                                 $this->logErrorLoconchera = $lonchera->id;
        //                                 foreach ($this->cartMenu as $cart) {
        //                                     $detalle = Detallelonchera::create([
        //                                         "fecha" => $cart->fecha,
        //                                         "menu_id" => $cart->menu_id,
        //                                         "lonchera_id" => $lonchera->id,
        //                                         "entregado" => false,
        //                                     ]);
        //                                 }

        //                                 DB::commit();
        //                                 $this->finalizaTransaccion();
        //                                 return redirect()->route('fintransc', $venta->id);
        //                             } catch (\Throwable $th) {
        //                                 DB::rollBack();
        //                                 if ($this->logErrorVentas) {
        //                                     $log = $this->guardaLogError($this->logErrorVentas, 'ventas', $th->getMessage());
        //                                 }
        //                                 if ($this->logErrorPagos) {
        //                                     $log = $this->guardaLogError($this->logErrorPagos, 'pagos', $th->getMessage());
        //                                 }

        //                                 $this->emit('alertError', 'logID: ' . $log->id);
        //                             }
        //                         } else {
        //                             $this->emit('alertComprobante');
        //                         }
        //                     }
        //                     break;
        //             }
        //         } else {
        //             $this->emit('alertWarning', 'Debe seleccionar una forma de pago');
        //         }
        //     }
        // }

        if ($this->tipoBusqueda == "t") {
            if (!is_null($this->comprobante) || $this->formapago == 1 || $this->formapago == 4) {
                if (($this->countCart > 0)) {
                    if ($this->formapago != '') {

                        DB::beginTransaction();
                        try {
                            $venta = Venta::create([
                                'fecha' => now(),
                                'cliente' => $this->tutor->nombre,
                                'estadopago_id' => 1,
                                'importe' => $this->totaltutor,
                                'sucursale_id' => 1,
                                'plataforma' => 'web',
                                'observaciones' => 'S/N'

                            ]);
                            $this->logErrorVentas = $venta->id;
                            $tipopago = Tipopago::find($this->formapago);
                            $pago = Pago::create([
                                'recibo' => 0,
                                'sucursal_id' => $this->sucursal_id,
                                'sucursal' => $this->sucursal,
                                'tipopago_id' => $tipopago->id,
                                'tipopago' => $tipopago->nombre,
                                'importe' => $venta->importe,
                                'venta_id' => $venta->id,
                                'estadopago_id' => 1,
                            ]);
                            $this->logErrorPagos = $pago->id;

                            foreach ($this->arrEstudiantes as $estudiante) {
                                foreach ($this->selEstudiantes as $selEst) {
                                    if($estudiante[0]['id'] == $selEst){
                                        $detalleventa = Detalleventa::create([
                                            'venta_id' => $venta->id,
                                            'descripcion' => $estudiante[0]['nombre'],
                                            'subtotal' => $estudiante[0]['subtotal'],
                                        ]);
                                        $this->logErrorVentas = $venta->id;
        
        
                                        if ($this->formapago == 2 || $this->formapago == 3) {
                                            $this->validate([
                                                'comprobante' => 'image|max:1024', // 1MB Max
                                            ]);
                                            $file = $this->comprobante->storeAs('depositos/' . $tipopago->abreviatura, $pago->id . "." . $this->comprobante->extension());
                                        }
        
                                        $lonchera = Lonchera::create([
                                            "fecha" => now(),
                                            "estudiante_id" => $estudiante[0]['id'],
                                            "pago_id" => $pago->id,
                                            "habilitado" => false,
                                        ]);
                                        $this->logErrorLoconchera = $lonchera->id;
                                        foreach ($this->cartMenu as $cart) {
                                            $detalle = Detallelonchera::create([
                                                "fecha" => $cart->fecha,
                                                "menu_id" => $cart->menu_id,
                                                "lonchera_id" => $lonchera->id,
                                                "entregado" => false,
                                            ]);
                                        }
                                    }
                                }
                                
                            }                          
                            

                            $this->finalizaTransaccion();
                            DB::commit();
                            return redirect()->route('fintransc', $venta->id);
                        } catch (\Throwable $th) {
                            DB::rollBack();
                            if ($this->logErrorVentas) {
                                $log = $this->guardaLogError($this->logErrorVentas, 'ventas', $th->getMessage());
                            }
                            if ($this->logErrorPagos) {
                                $log = $this->guardaLogError($this->logErrorPagos, 'pagos', $th->getMessage());
                            }
                            if ($this->logErrorLoconchera) {
                                $log = $this->guardaLogError($this->logErrorLoconchera, 'lonchera', $th->getMessage());
                            }
                            $this->emit('alertError', $th->getMessage());
                        }
                    } else {
                        $this->emit('alertWarning', 'Debe seleccionar una forma de pago');
                    }
                }
            } else {
                $this->emit('alertComprobante');
            }
        }
    }

    public function guardaVenta($estadopago_id)
    {
        $venta = Venta::create([
            'fecha' => now(),
            'cliente' => $this->estudiante->nombre,
            'estadopago_id' => 1,
            'importe' => $this->total_estudiante,
        ]);
        return $venta;
    }

    public function guardaLogError($id, $tabla, $observaciones)
    {
        $log = Logsid::create([
            'idgenerado' => $id,
            'tabla' => $tabla,
            'observaciones' => $observaciones
        ]);
        return $log;
    }

    public function guardaPago($estadopago_id, $tipopago_id, $venta_id)
    {
        $tipopago = Tipopago::find($tipopago_id);
        $pago = Pago::create([
            'recibo' => 0,
            'sucursal_id' => $this->estudiante->curso->nivelcurso->sucursale->id,
            'sucursal' => $this->estudiante->curso->nivelcurso->sucursale->nombre,
            'tipopago_id' => $tipopago->id,
            'tipopago' => $tipopago->nombre,
            'importe' => $this->total_estudiante,
            'venta_id' => $venta_id,
            'estadopago_id' => $estadopago_id,
        ]);
        return $pago;
    }

    public function finalizaTransaccion()
    {
        $this->cartMenu = Cartmenu::where('session', session('idCarrito'))->delete();
    }

    public function cambiarTab()
    {
        $this->dnone = "d-none";
    }
}
