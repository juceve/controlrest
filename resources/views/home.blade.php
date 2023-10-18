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
                    $imagen = ['uil-food', 'uil-life-ring', 'uil-coffee', 'uil-utensils', 'uil-food'];
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

                @if ($arrPP)
                    <div class="col-lg-4">
                        <div class="card text-white bg-{{ $colores[4] }} overflow-hidden">
                            <div class="card-body">
                                <div class="toll-free-box text-end">
                                    <a href="{{ route('ventas.vpagos') }}" class="text-white">
                                        <h3> <i class="{{ $imagen[4] }}"></i> PAGOS PENDIENTES</h3>
                                    </a>

                                </div>
                                <div class="text-end">

                                    <span>{{ $arrPP[0] }}: {{ $arrPP[1] }}</span> <br>
                                    <span>{{ $arrPP[2] }}: {{ $arrPP[3] }}</span> <br>
                                    <span>{{ $arrPP[4] }}: {{ $arrPP[5] }}</span> <br>
                                    <hr>
                                    <span><strong>TOTAL: {{ $arrPP[6] }}</strong></span>


                                </div>
                            </div> <!-- end card-body-->
                        </div>
                    </div> <!-- end col-->
                @endif


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

            @if ($misVentas)
                <h4>
                    @if (Auth::user()->roles[0]->name == 'VENTAS')
                        Mis Ventas de Hoy:
                    @endif
                    @if (Auth::user()->roles[0]->name == 'Admin')
                        Listado de Ventas:
                    @endif
                </h4>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
                                <thead class="table-primary">
                                    <tr align="center">
                                        <th class="text-start">DETALLE</th>
                                        <th>CANT. OPERACIONES</th>
                                        <th class="text-end">EFE Bs.</th>
                                        <th class="text-end">TB Bs.</th>
                                        <th class="text-end">QR Bs.</th>
                                        <th class="text-end">CR Bs.</th>
                                        <th class="text-end">GA Bs.</th>
                                        <th class="text-end">TOTAL Bs.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalOp = 0;
                                        $totalEFE = 0;
                                        $totalTB = 0;
                                        $totalQR = 0;
                                        $totalCR = 0;
                                        $totalGA = 0;
                                        $ttotal = 0;
                                        foreach ($misVentas as $item) {
                                            echo "<tr align='right'>
                                        <td align='left'>$item[0]</td>
                                        <td align='center'>" .
                                                $item[1] .
                                                "</td>
                                        <td>" .
                                                number_format($item[2], 2) .
                                                "</td>
                                        <td>" .
                                                number_format($item[3], 2) .
                                                "</td>
                                        <td>" .
                                                number_format($item[4], 2) .
                                                "</td>
                                        <td>" .
                                                number_format($item[5], 2) .
                                                "</td>
                                        <td>" .
                                                number_format($item[6], 2) .
                                                "</td>
                                        <td>" .
                                                number_format($item[7], 2) .
                                                "</td>
                                    </tr>";
                                            $totalOp += $item[1];
                                            $totalEFE += $item[2];
                                            $totalTB += $item[3];
                                            $totalQR += $item[4];
                                            $totalCR += $item[5];
                                            $totalGA += $item[6];
                                            $ttotal += $item[7];
                                        }
                                    @endphp
                                </tbody>
                                <tfoot class="table-primary">
                                    <tr align="right">
                                        <td align="center">TOTALES</td>
                                        <td align="center">{{ $totalOp }}</td>
                                        <td>{{ number_format($totalEFE, 2) }}</td>
                                        <td>{{ number_format($totalTB, 2) }}</td>
                                        <td>{{ number_format($totalQR, 2) }}</td>
                                        <td>{{ number_format($totalCR, 2) }}</td>
                                        <td>{{ number_format($totalGA, 2) }}</td>
                                        <td>{{ number_format($ttotal, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        @endif


    </div>
@endsection
