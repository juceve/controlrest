<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Cierre de Caja</title>
    <link href="{{ public_path('invoice/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
    <script src="{{ public_path('invoice/bootstrap.min.js') }}"></script>
    <script src="{{ public_path('invoice/jquery-1.11.1.min.js') }}"></script>
    <style>
        .invoice-title h2,
        .invoice-title h3 {
            display: inline-block;
        }

        /* .table>tbody>tr>.no-line {
            border-top: none;
        }

        .table>thead>tr>.no-line {
            border-bottom: none;
        }

        .table>tbody>tr>.thick-line {
            border-top: 2px solid;
        } */

        .fuentesm {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <img src="{{ asset('img/logoAR.png') }}" style="width: 120px;">

    <div class="container">
        <div class="content">
            <div class="row">
                <div class="col-6">
                    <h5>A&R Catering Service</h5>
                </div>

            </div>
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <h4>CONTROL DE ALMUERZOS PAGADOS Y SERVIDOS</h4>
                    <small><strong>Fecha:</strong> {{ $fecha }}</small>
                </div>
            </div><br>

            <div class="row mb-5">
                <div class="col-12">
                    <div class="container">
                        <div class="mb-3">
                            <h6>RESUMEN GENERAL <small>(No incluye Profesores)</small></h6>
                            <table class="table table-bordered" style="font-size: 12px">
                                <thead>
                                    <tr align="center" style="background-color: lightgray">
                                        <td>NRO</td>
                                        <td>ITEM</td>
                                        <td>PAGADOS</td>
                                        <td>SERVIDOS</td>
                                        <td>AUSENCIAS</td>
                                        <td>LICENCIAS</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $tpagados = 0;
                                        $tservidos = 0;
                                        $tausencias = 0;
                                        $tlicencias = 0;
                                    @endphp
                                    @foreach ($tabla1 as $item)
                                        <tr>
                                            <td align="center">{{ $i }}</td>
                                            <td>{{ $item[1] }}</td>
                                            <td align="center">{{ $item[2] }}</td>
                                            <td align="center">{{ $item[3] }}</td>
                                            <td align="center">{{ $item[4] }}</td>
                                            <td align="center">{{ $item[5] }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                            $tpagados = $tpagados + $item[2];
                                            $tservidos = $tservidos + $item[3];
                                            $tausencias = $tausencias + $item[4];
                                            $tlicencias = $tlicencias + $item[5];
                                        @endphp
                                    @endforeach

                                </tbody>
                                <tfoot class="table-success">
                                    <tr>
                                        <td colspan="2" align="right"><strong>TOTAL</strong></td>
                                        <td align="center"><strong>{{ $tpagados }}</strong></td>
                                        <td align="center"><strong>{{ $tservidos }}</strong></td>
                                        <td align="center"><strong>{{ $tausencias }}</strong></td>
                                        <td align="center"><strong>{{ $tlicencias }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="mb-3">
                            <h6>RESUMEN POR PLATAFORMA</h6>
                            <div class="table-responsive">
                                @if ($tabla11)
                                    <table class="table table-striped table-bordered table-sm table-hover"
                                        style="font-size: 12px;">
                                        <thead class="table-secondary" style="background-color: lightgray">
                                            <tr align="center">
                                                <td>NRO</td>
                                                <td>PLATAFORMA</td>
                                                <td>CANTIDAD</td>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                                $total = 0;
                                            @endphp
                                            @foreach ($tabla11 as $item)
                                                <tr>
                                                    <td align="center">{{ $i }}</td>
                                                    <td>{{ $item[0] }}</td>
                                                    <td align="center">{{ $item[1] }}</td>
                                                </tr>
                                                @php
                                                    $i++;
                                                    $total = $total + $item[1];
                                                @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-success">
                                                <td colspan="2" align="right"><strong>TOTAL</strong></td>
                                                <td align="center"><strong>{{ $total }}</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <h5 class="text-center">ALMUERZO PAGADOS VS SERVIDOS POR CURSOS</h5>
                        <div class="mb-3">
                            <h5>PRIMARIA</h5>
                            <div>
                                <table class="table table-bordered table-striped table-hover table-sm">
                                    <thead class="table-info" style="font-size: 10px; width: 100%">
                                        <tr align="center" style="background-color: lightgray">
                                            <th>No.</th>
                                            <th>CURSO</th>
                                            <th>LICENCIA</th>
                                            <th>A. COMP. PAGADOS</th>
                                            <th>A. COMP. SERVIDOS</th>
                                            {{-- <th>SUBTOTAL</th> --}}
                                            <th>A. SIMPLE PAGADOS</th>
                                            <th>A. SIMPLE SERVIDOS</th>
                                            {{-- <th>SUBTOTAL</th> --}}
                                            <th>EXTRA PAGADO</th>
                                            <th>EXTRA SERVIDO</th>
                                            {{-- <th>SUBTOTAL</th> --}}
                                            <th>TOTAL PAGADOS</th>
                                            <th>TOTAL SERVIDOS</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 12px;">
                                        @php
                                            $licencias = 0;
                                            $completoPagado = 0;
                                            $completoServido = 0;
                                            $simplePagado = 0;
                                            $simpleServido = 0;
                                            $extraPagado = 0;
                                            $extraServido = 0;
                                            $i = 1;
                                        @endphp
                                        @foreach ($tabla2 as $item)
                                            @php
                                                $totalpagados = 0;
                                                $totalservidos = 0;
                                                $data = $item[2];
                                                $completoPagado = $completoPagado + $data[0][2];
                                                $completoServido = $completoServido + $data[0][3];
                                                $simplePagado = $simplePagado + $data[1][2];
                                                $simpleServido = $simpleServido + $data[1][3];
                                                $extraPagado = $extraPagado + $data[2][2];
                                                $extraServido = $extraServido + $data[2][3];
                                            @endphp
                                            @if ($data[0][2] || $data[0][3] || $data[1][2] || $data[1][3] || $data[2][2] || $data[2][3])
                                                @php
                                                    $totalpagados = $totalpagados + $data[0][2] + $data[1][2] + $data[2][2];
                                                    $totalservidos = $totalservidos + $data[0][3] + $data[1][3] + $data[2][3];
                                                    $licencias = $licencias + $item[3];
                                                @endphp
                                                <tr align="center">
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $item[1] }}</td>
                                                    <td>{{ $item[3] }}</td>
                                                    <td>{{ $data[0][2] }}</td>
                                                    <td>{{ $data[0][3] }}</td>
                                                    {{-- <td>6</td> --}}
                                                    <td>{{ $data[1][2] }}</td>
                                                    <td>{{ $data[1][3] }}</td>
                                                    {{-- <td>9</td> --}}
                                                    <td>{{ $data[2][2] }}</td>
                                                    <td>{{ $data[2][3] }}</td>
                                                    {{-- <td>112</td> --}}
                                                    <td>{{ $totalpagados }}</td>
                                                    <td>{{ $totalservidos }}</td>
                                                </tr>
                                                @php
                                                    $i++;
                                                @endphp
                                            @endif
                                        @endforeach

                                    </tbody>
                                    <tfoot class="table-success text-center" style="font-size: 12px;">
                                        <tr>
                                            <td colspan="2">TOTAL</td>
                                            <td>{{ $licencias }}</td>
                                            <td>{{ $completoPagado }}</td>
                                            <td>{{ $completoServido }}</td>
                                            {{-- <td>2</td> --}}
                                            <td>{{ $simplePagado }}</td>
                                            <td>{{ $simpleServido }}</td>
                                            {{-- <td>6</td> --}}
                                            <td>{{ $extraPagado }}</td>
                                            <td>{{ $extraServido }}</td>
                                            {{-- <td>7</td> --}}
                                            @php
                                                $totalpagados1 = $completoPagado + $simplePagado + $extraPagado;
                                                $totalservidos1 = $completoServido + $simpleServido + $extraServido;
                                            @endphp
                                            <td>{{ $totalpagados1 }}</td>
                                            <td>{{ $totalservidos1 }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h5>SECUNDARIA</h5>
                            <div>
                                <table class="table table-bordered table-striped table-hover table-sm">
                                    <thead class="table-info" style="font-size: 10px; width: 100%">
                                        <tr align="center" style="background-color: lightgray">
                                            <th>No.</th>
                                            <th>CURSO</th>
                                            <th>LICENCIA</th>
                                            <th>A. COMP. PAGADOS</th>
                                            <th>A. COMP. SERVIDOS</th>
                                            {{-- <th>SUBTOTAL</th> --}}
                                            <th>A. SIMPLE PAGADOS</th>
                                            <th>A. SIMPLE SERVIDOS</th>
                                            {{-- <th>SUBTOTAL</th> --}}
                                            <th>EXTRA PAGADO</th>
                                            <th>EXTRA SERVIDO</th>
                                            {{-- <th>SUBTOTAL</th> --}}
                                            <th>TOTAL PAGADOS</th>
                                            <th>TOTAL SERVIDOS</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 12px;">
                                        @php
                                            $licencias = 0;
                                            $completoPagado = 0;
                                            $completoServido = 0;
                                            $simplePagado = 0;
                                            $simpleServido = 0;
                                            $extraPagado = 0;
                                            $extraServido = 0;
                                            $i = 1;
                                        @endphp
                                        @foreach ($tabla3 as $item)
                                            @php
                                                $totalpagados = 0;
                                                $totalservidos = 0;
                                                $data = $item[2];
                                                $completoPagado = $completoPagado + $data[0][2];
                                                $completoServido = $completoServido + $data[0][3];
                                                $simplePagado = $simplePagado + $data[1][2];
                                                $simpleServido = $simpleServido + $data[1][3];
                                                $extraPagado = $extraPagado + $data[2][2];
                                                $extraServido = $extraServido + $data[2][3];
                                            @endphp
                                            @if ($data[0][2] || $data[0][3] || $data[1][2] || $data[1][3] || $data[2][2] || $data[2][3])
                                                @php
                                                    $totalpagados = $totalpagados + $data[0][2] + $data[1][2] + $data[2][2];
                                                    $totalservidos = $totalservidos + $data[0][3] + $data[1][3] + $data[2][3];
                                                    $licencias = $licencias + $item[3];
                                                @endphp
                                                <tr align="center">
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $item[1] }}</td>
                                                    <td>{{ $item[3] }}</td>
                                                    <td>{{ $data[0][2] }}</td>
                                                    <td>{{ $data[0][3] }}</td>
                                                    {{-- <td>6</td> --}}
                                                    <td>{{ $data[1][2] }}</td>
                                                    <td>{{ $data[1][3] }}</td>
                                                    {{-- <td>9</td> --}}
                                                    <td>{{ $data[2][2] }}</td>
                                                    <td>{{ $data[2][3] }}</td>
                                                    {{-- <td>112</td> --}}
                                                    <td>{{ $totalpagados }}</td>
                                                    <td>{{ $totalservidos }}</td>
                                                </tr>
                                                @php
                                                    $i++;
                                                @endphp
                                            @endif
                                        @endforeach

                                    </tbody>
                                    <tfoot class="table-success text-center" style="font-size: 12px;">
                                        <tr>
                                            <td colspan="2">TOTAL</td>
                                            <td>{{ $licencias }}</td>
                                            <td>{{ $completoPagado }}</td>
                                            <td>{{ $completoServido }}</td>
                                            {{-- <td>2</td> --}}
                                            <td>{{ $simplePagado }}</td>
                                            <td>{{ $simpleServido }}</td>
                                            {{-- <td>6</td> --}}
                                            <td>{{ $extraPagado }}</td>
                                            <td>{{ $extraServido }}</td>
                                            {{-- <td>7</td> --}}
                                            @php
                                                $totalpagados1 = $completoPagado + $simplePagado + $extraPagado;
                                                $totalservidos1 = $completoServido + $simpleServido + $extraServido;
                                            @endphp
                                            <td>{{ $totalpagados1 }}</td>
                                            <td>{{ $totalservidos1 }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h5>PROFESORES</h5>
                            <div>
                                <table class="table table-bordered table-striped table-hover table-sm">
                                    <thead class="table-info" style="font-size: 10px; width: 100%">
                                        <tr align="center" style="background-color: lightgray">
                                            <th>No.</th>
                                            <th>CURSO</th>
                                            <th>LICENCIA</th>
                                            <th>A. COMP. PAGADOS</th>
                                            <th>A. COMP. SERVIDOS</th>
                                            {{-- <th>SUBTOTAL</th> --}}
                                            <th>A. SIMPLE PAGADOS</th>
                                            <th>A. SIMPLE SERVIDOS</th>
                                            {{-- <th>SUBTOTAL</th> --}}
                                            <th>EXTRA PAGADO</th>
                                            <th>EXTRA SERVIDO</th>
                                            {{-- <th>SUBTOTAL</th> --}}
                                            <th>TOTAL PAGADOS</th>
                                            <th>TOTAL SERVIDOS</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 12px;">
                                        @php
                                            $licencias = 0;
                                            $completoPagado = 0;
                                            $completoServido = 0;
                                            $simplePagado = 0;
                                            $simpleServido = 0;
                                            $extraPagado = 0;
                                            $extraServido = 0;
                                            $i = 1;
                                        @endphp
                                        @foreach ($tabla4 as $item)
                                            @php
                                                $totalpagados = 0;
                                                $totalservidos = 0;
                                                $data = $item[2];
                                                $completoPagado = $completoPagado + $data[0][2];
                                                $completoServido = $completoServido + $data[0][3];
                                                $simplePagado = $simplePagado + $data[1][2];
                                                $simpleServido = $simpleServido + $data[1][3];
                                                $extraPagado = $extraPagado + $data[2][2];
                                                $extraServido = $extraServido + $data[2][3];
                                            @endphp
                                            @if ($data[0][2] || $data[0][3] || $data[1][2] || $data[1][3] || $data[2][2] || $data[2][3])
                                                @php
                                                    $totalpagados = $totalpagados + $data[0][2] + $data[1][2] + $data[2][2];
                                                    $totalservidos = $totalservidos + $data[0][3] + $data[1][3] + $data[2][3];
                                                    $licencias = $licencias + $item[3];
                                                @endphp
                                                <tr align="center">
                                                    <td>{{ $i }}</td>
                                                    <td>{{ substr($item[1], 0, 4) }}</td>
                                                    <td>{{ $item[3] }}</td>
                                                    <td>{{ $data[0][2] }}</td>
                                                    <td>{{ $data[0][3] }}</td>
                                                    {{-- <td>6</td> --}}
                                                    <td>{{ $data[1][2] }}</td>
                                                    <td>{{ $data[1][3] }}</td>
                                                    {{-- <td>9</td> --}}
                                                    <td>{{ $data[2][2] }}</td>
                                                    <td>{{ $data[2][3] }}</td>
                                                    {{-- <td>112</td> --}}
                                                    <td>{{ $totalpagados }}</td>
                                                    <td>{{ $totalservidos }}</td>
                                                </tr>
                                                @php
                                                    $i++;
                                                @endphp
                                            @endif
                                        @endforeach

                                    </tbody>
                                    <tfoot class="table-success text-center" style="font-size: 12px;">
                                        <tr>
                                            <td colspan="2">TOTAL</td>
                                            <td>{{ $licencias }}</td>
                                            <td>{{ $completoPagado }}</td>
                                            <td>{{ $completoServido }}</td>
                                            {{-- <td>2</td> --}}
                                            <td>{{ $simplePagado }}</td>
                                            <td>{{ $simpleServido }}</td>
                                            {{-- <td>6</td> --}}
                                            <td>{{ $extraPagado }}</td>
                                            <td>{{ $extraServido }}</td>
                                            {{-- <td>7</td> --}}
                                            @php
                                                $totalpagados1 = $completoPagado + $simplePagado + $extraPagado;
                                                $totalservidos1 = $completoServido + $simpleServido + $extraServido;
                                            @endphp
                                            <td>{{ $totalpagados1 }}</td>
                                            <td>{{ $totalservidos1 }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="mb-3">

                            <br><br><br>
                            {{-- <table style="width: 100%">
                                <tr>
                                    <td align="center">
                                        ______________________ <br>CAJERO
                                    </td>
                                    <td align="center">
                                        ______________________ <br>SUPERVISOR
                                    </td>
                                </tr>
                            </table> --}}
                        </div>
                    </div>
                </div>

            </div>
            <br><br><br>


        </div>
    </div>

</body>

</html>
