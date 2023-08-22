@extends('layouts.app')
@section('template_title')
    Dashboard
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">BIENVENIDO A CONTROL-REST</h4>
                    {{-- {{cantidadDiasMes(02,date('Y'))}} --}}
                </div>
            </div>
        </div>
        @if (Auth::user()->sucursale_id)
            <h4>Pedidos para Hoy:</h4>
            <div class="row">

                @php
                    $colores = ['primary', 'success', 'info', 'warning', 'danger', 'secondary', 'dark'];
                    $imagen = ['uil-food', 'uil-life-ring', 'uil-coffee', 'uil-utensils'];
                    $i = 0;
                    $total = 0;
                @endphp
                @if ($reservas)
                    <div class="col-lg-4">
                        <div class="card text-white bg-{{ $colores[0] }} overflow-hidden">
                            <div class="card-body">
                                <div class="toll-free-box text-end">
                                    <h3> <i class="{{ $imagen[0] }}"></i> RESERVAS</h3>
                                </div>
                                <div class="text-end">

                                    <span>{{ $reservas[0] }}: {{ $reservas[1] }}</span> <br>
                                    <span>{{ $reservas[2] }}: {{ $reservas[3] }}</span> <br>
                                    <span>{{ $reservas[4] }}: {{ $reservas[5] }}</span> <br>
                                    <hr>
                                    <span><strong>TOTAL: {{ $reservas[6] }}</strong></span>


                                </div>
                            </div> <!-- end card-body-->
                        </div>
                    </div> <!-- end col-->
                @endif

                @if ($arrPos)
                    <div class="col-lg-4">
                        <div class="card text-white bg-{{ $colores[1] }} overflow-hidden">
                            <div class="card-body">
                                <div class="toll-free-box text-end">
                                    <h3> <i class="{{ $imagen[1] }}"></i> PUNTO DE VENTA</h3>
                                </div>
                                <div class="text-end">

                                    <span>{{ $arrPos[0] }}: {{ $arrPos[1] }}</span> <br>
                                    <span>{{ $arrPos[2] }}: {{ $arrPos[3] }}</span> <br>
                                    <span>{{ $arrPos[4] }}: {{ $arrPos[5] }}</span> <br>
                                    <hr>
                                    <span><strong>TOTAL: {{ $arrPos[6] }}</strong></span>


                                </div>
                            </div> <!-- end card-body-->
                        </div>
                    </div> <!-- end col-->
                @endif



                {{-- <div class="col-lg-4">
            <div class="card text-white bg-{{$colores[1]}} overflow-hidden">
                <div class="card-body">
                    <div class="toll-free-box text-end">
                        <h3> <i class="{{$imagen[1]}}"></i> PUNTO DE VENTA</h3>
                    </div>
                    <div class="text-end">
                        @php
                        $total = 0;
                        @endphp
                        @foreach ($puntoventas as $reserva)
                        <span>{{$reserva[1]}}: {{$reserva[2]}}</span> <br>
                        @php
                        $total = $total + $reserva[2];
                        @endphp
                        @endforeach

                        <hr>
                        <span><strong>TOTAL: {{$total}}</strong></span>


                    </div>
                </div> <!-- end card-body-->
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card text-white bg-{{$colores[2]}} overflow-hidden">
                <div class="card-body">
                    <div class="toll-free-box text-end">
                        <h3> <i class="{{$imagen[2]}}"></i> PENDIENTE PAGO</h3>
                    </div>
                    <div class="text-end">
                        @php
                        $total = 0;
                        @endphp
                        @foreach ($pendientepagos as $reserva)
                        <span>{{$reserva[1]}}: {{$reserva[2]}}</span> <br>
                        @php
                        $total = $total + $reserva[2];
                        @endphp
                        @endforeach

                        <hr>
                        <span><strong>TOTAL: {{$total}}</strong></span>


                    </div>
                </div> <!-- end card-body-->
            </div>
        </div> --}}

            </div>

            <h4>Entregas del día:</h4>
            <div class="row">
                @if ($arrEntregas)
                    <div class="col-lg-4">
                        <div class="card text-white bg-{{ $colores[3] }} overflow-hidden">
                            <div class="card-body">
                                <div class="toll-free-box text-end">
                                    <h3> <i class="{{ $imagen[3] }}"></i> ENTREGAS</h3>
                                </div>
                                <div class="text-end">

                                    <span>{{ $arrEntregas[0] }}: {{ $arrEntregas[1] }}</span> <br>
                                    <span>{{ $arrEntregas[2] }}: {{ $arrEntregas[3] }}</span> <br>
                                    <span>{{ $arrEntregas[4] }}: {{ $arrEntregas[5] }}</span> <br>
                                    <hr>
                                    <span><strong>TOTAL: {{ $arrEntregas[6] }}</strong></span>


                                </div>
                            </div> <!-- end card-body-->
                        </div>
                    </div> <!-- end col-->
                @endif

                @if ($arrProf)
                    <div class="col-lg-4">
                        <div class="card text-white bg-{{ $colores[2] }} overflow-hidden">
                            <div class="card-body">
                                <div class="toll-free-box text-end">
                                    <h3> <i class="{{ $imagen[2] }}"></i> ENTREGA PROFES</h3>
                                </div>
                                <div class="text-end">

                                    <span>{{ $arrProf[0] }}: {{ $arrProf[1] }}</span> <br>
                                    <span>{{ $arrProf[2] }}: {{ $arrProf[3] }}</span> <br>
                                    <span>{{ $arrProf[4] }}: {{ $arrProf[5] }}</span> <br>
                                    <hr>
                                    <span><strong>TOTAL: {{ $arrProf[6] }}</strong></span>


                                </div>
                            </div> <!-- end card-body-->
                        </div>
                    </div> <!-- end col-->
                @endif
            </div>
        @endif

        {{-- <div class="row">
        <div class="col-xl-5 col-lg-6">

            <div class="row">
                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">

                            @if ($ventasMes)
                            <div class="float-end">
                                <i class="mdi mdi-currency-usd widget-icon"></i>
                            </div>
                            @php
                            $meses =
                            ["01"=>'Enero',"02"=>'Febrero',"03"=>'Marzo',"04"=>'Abril',"05"=>'Mayo',"06"=>'Junio',"07"=>'Julio',"08"=>'Agosto',"09"=>'Septiembre',"10"=>'Octubre',"11"=>'Noviembre',"12"=>'Diciembre'];
                            @endphp
                            <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Ventas - {{$meses[$mes]}}
                            </h5>
                            <h3 class="mt-3 mb-3">{{$ventasMes[0]->importes}} {{$moneda->abreviatura}}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">{{$ventasMes[0]->cantidad}} producto(s)
                                    vendido(s)</span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-cart-plus widget-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Orders</h5>
                            <h3 class="mt-3 mb-3">5,543</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-danger me-2"><i class="mdi mdi-arrow-down-bold"></i> 1.08%</span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-currency-usd widget-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Average Revenue">Revenue</h5>
                            <h3 class="mt-3 mb-3">$6,254</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-danger me-2"><i class="mdi mdi-arrow-down-bold"></i> 7.00%</span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-pulse widget-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Growth">Growth</h5>
                            <h3 class="mt-3 mb-3">+ 30.56%</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 4.87%</span>
                                <span class="text-nowrap">Since last month</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-xl-7 col-lg-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">

                            <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>

                            <a href="javascript:void(0);" class="dropdown-item">Export Report</a>

                            <a href="javascript:void(0);" class="dropdown-item">Profit</a>

                            <a href="javascript:void(0);" class="dropdown-item">Action</a>
                        </div>
                    </div>
                    <h4 class="header-title mb-3">Projections Vs Actuals</h4>

                    <div dir="ltr">
                        <div id="high-performing-product" class="apex-charts" data-colors="#727cf5,#e3eaef"></div>
                    </div>

                </div>
            </div>

        </div>
    </div> --}}
    </div>
@endsection
