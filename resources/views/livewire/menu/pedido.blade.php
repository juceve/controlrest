<div>
    <div class="container-fluid text-center py-3 text-warning" style="background-color: #10620093">
        <h5>MENU SEMANAL</h5>
    </div>
    <div class="content p-2">
        <div class="container py-3" style="background-color: #ffffffc9">
            <div class="row d-flex justify-content-center align-items-center ">
                <div class="col-12 col-md-7">
                    {{-- @if(!empty($successMessage))
                    <div class="alert alert-success">
                        {{ $successMessage }}
                    </div>
                    @endif --}}

                    <div class="text-center">
                        <!-- progressbar -->
                        <ul class="progressbar">
                            <li class="active"></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                    </div>
                    <div class="row setup-content" id="step-1">
                        <div class="col-xs-12">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-8">
                                        <h3 class="h5"> Selección de Productos</h3>
                                    </div>
                                    <div class="col-4 text-end" style="vertical-align: middle">
                                        <button type="button" class="btn btn-sm btn-warning position-relative">
                                            <i class="bi bi-cart4"></i> <span
                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">{{$countCart}}
                                            </span>
                                        </button>
                                    </div>
                                </div>


                                <div class="card mb-3" wire:ignore>
                                    <div class="card-body">

                                        @if ($eventos->count() > 0)
                                        <nav>
                                            <div class="nav nav-tabs" style="background-color: #039ffa27" id="nav-tab"
                                                role="tablist">
                                                @if ($eventos->count() > 0)
                                                @foreach ($eventos as $evento)
                                                @php
                                                $dia = get_nombre_dia($evento->fecha);
                                                @endphp

                                                <button class="nav-link 
                                                @php 
                                                $limite = $evento->fecha.' 12:00:00';
                                                $disabled = 0;
                                                if($evento->fecha < date('Y-m-d') || strtotime(now()) > strtotime($limite))
                                                { echo " disabled" ;} @endphp" id="nav-lun-tab" data-bs-toggle="tab"
                                                    data-bs-target="#nav-{{$dia}}" type="button" role="tab"
                                                    aria-controls="nav-{{$dia}}" aria-selected="true">
                                                    <span class="d-block d-md-none">{{strtoupper(Str::substr($dia,
                                                        0,
                                                        3))}}</span>
                                                    <span class="d-none d-md-block">{{strtoupper($dia)}}</span>
                                                </button>

                                                @endforeach
                                                @endif

                                            </div>
                                        </nav>
                                        <div class="tab-content" id="nav-tabContent">
                                            @if ($eventos->count() > 0)
                                            @foreach ($eventos as $evento)
                                            @php
                                            $dia = get_nombre_dia($evento->fecha);
                                            @endphp
                                            <div class="tab-pane fade 
                                            

                                            " id="nav-{{$dia}}" role="tabpanel" aria-labelledby="nav-{{$dia}}">
                                                <div class="container py-3">

                                                    <div class="row">
                                                        <div class="col-12 py-2 text-center">
                                                            <label><strong>{{strtoupper(fechaEs($evento->fecha))}}</strong></label>
                                                            <hr>
                                                        </div>
                                                    </div>

                                                    @foreach ($evento->detalleeventos as $detalle)

                                                    <div class="col-12">
                                                        <strong>{{$detalle->menu->tipomenu->nombre}}</strong>
                                                    </div>

                                                    <div class="col-12 mb-2">
                                                        {{$detalle->menu->descripcion}}
                                                    </div>

                                                    <div class="col-12 mb-5">
                                                        @php
                                                        $vb = "";
                                                        $vs = "d-none";
                                                        foreach ($cartMenu as $item) {
                                                        if(($item->fecha == $evento->fecha) && ($item->menu_id ==
                                                        $detalle->menu_id))
                                                        {
                                                        $vs = "";
                                                        $vb = "d-none";

                                                        break;
                                                        }
                                                        }
                                                        @endphp
                                                        <button class="btn btn-sm btn-warning {{$vb}}"
                                                            style="float: right;" onclick="event.preventDefault()"
                                                            id="{{$evento->fecha.'-'.$detalle->menu_id}}"
                                                            wire:click="addCart({{$detalle->menu_id}},'{{$evento->fecha}}')">
                                                            Añadir <i class="bi bi-cart-plus"></i>
                                                        </button>
                                                        <small id="{{$evento->fecha.'-'.$detalle->menu_id}}-sel"
                                                            class="{{$vs}} text-success"
                                                            style="float: right;">*Seleccionado</small>
                                                    </div>
                                                    <hr>
                                                    @endforeach
                                                </div>

                                            </div>
                                            @endforeach
                                            @endif
                                        </div>
                                        @else
                                        <span>No existe Menu para esta semana</span>
                                        @endif


                                    </div>
                                </div>
                                <div style="float: right">
                                    <a href="{{route('resumenpedido')}}" class="btn btn-success nextBtn btn-lg"
                                        type="button">Sig <i class="bi bi-chevron-right"></i></i></a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@section('css')
<link rel="stylesheet" href="{{asset('web2/css/wizard.css')}}">
@endsection
@section('js')
<script src="{{asset('web2/js/wizard.js')}}"></script>
@endsection
@php
function get_nombre_dia($fecha)
{
$fechats = strtotime($fecha);

switch (date('w', $fechats)) {
case 0:
return "Domingo";
break;
case 1:
return "Lunes";
break;
case 2:
return "Martes";
break;
case 3:
return "Miercoles";
break;
case 4:
return "Jueves";
break;
case 5:
return "Viernes";
break;
case 6:
return "Sabado";
break;
}
}

function fechaEs($fecha)
{
$fecha = substr($fecha, 0, 10);
$numeroDia = date('d', strtotime($fecha));
$dia = date('l', strtotime($fecha));
$mes = date('F', strtotime($fecha));
$anio = date('Y', strtotime($fecha));
$dias_ES = array("Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo");
$dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
$nombredia = str_replace($dias_EN, $dias_ES, $dia);
$meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre",
"Noviembre", "Diciembre");
$meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October",
"November", "December");
$nombreMes = str_replace($meses_EN, $meses_ES, $mes);
return $nombredia . " " . $numeroDia . " de " . $nombreMes . " de " . $anio;
}

@endphp