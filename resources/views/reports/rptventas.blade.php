<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Ventas</title>
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
                    <h4>REPORTE DE VENTAS</h4>
                    <small><strong>Del:</strong> {{ $fechaI }} <strong>al</strong> {{ $fechaF }}</small>
                </div>
            </div><br>
            <h5 class="text-center">POR TIPO DE PAGO</h5>
            <div class="row mb-5">
                <div class="col-12">
                    <div class="container">
                        <div class="mb-3">
                            <label for=""><strong>PAGOS REALIZADOS</strong></label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-hover" style="font-size: 12px;">
                                    <thead class="table-primary">
                                        <tr align="center" style="background-color: lightgray">
                                            <th>NRO</th>
                                            <th>TIPO PAGO</th>
                                            <th>CANT. OPERACIONES</th>
                                            <th>SUBTOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                            $total = 0;
                                            $cantidad = 0;
                                        @endphp
                                        @foreach ($tabla1 as $item)
                                            <tr>
                                                <td align="center">{{ $i }}</td>
                                                <td>{{ $item[1] }}</td>
                                                <td align="center">{{ $item[2] }}</td>
                                                <td align="right">{{ $item[3] }}</td>
                                            </tr>
                                            @php
                                                $i++;
                                                $cantidad = $cantidad + $item[2];
                                                $total = $total + $item[3];
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-success">
                                        <tr>
                                            <td colspan="2" align="right"><strong>TOTAL</strong></td>
                                            <td align="center"><strong>{{ $cantidad }}</strong></td>
                                            <td align="right"><strong>{{ number_format($total, 2, '.', ',') }}</strong>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        @if (count($tabla2))
                          <div class="mb-3">
                            <label for=""><strong>POR PAGAR</strong></label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-hover" style="font-size: 12px;">
                                    <thead class="table-primary">
                                        <tr align="center" style="background-color: lightgray">
                                            <th>NRO</th>
                                            <th>TIPO PAGO</th>
                                            <th>CANT. OPERACIONES</th>
                                            <th>SUBTOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                            $total = 0;
                                            $cantidad = 0;
                                        @endphp
                                        @foreach ($tabla2 as $item)
                                        <tr>
                                            <td align="center">{{ $i }}</td>
                                            <td>{{ $item[1] }}</td>
                                            <td align="center">{{ $item[2] }}</td>
                                            <td align="right">{{ $item[3] }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                            $cantidad = $cantidad + $item[2];
                                            $total = $total + $item[3];
                                        @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-success">
                                        <tr>
                                            <td colspan="2" align="right"><strong>TOTAL</strong></td>
                                            <td align="center"><strong>{{ $cantidad }}</strong></td>
                                            <td align="right"><strong>{{ number_format($total,2,'.',',') }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>  
                        @endif
                        
                        <hr>
                        <h5 class="text-center">POR PLATAFORMAS</h5>
                        <div class="mb-3">
                            <label for=""><strong>PAGOS REALIZADOS</strong></label>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered"
                                    style="font-size: 12px; width: 100%">
                                    <thead class="table-info">
                                        <tr align="center" style="background-color: lightgray">
                                            <th>NRO</th>
                                            <th>PLATAFORMA</th>
                                            <th>CANTIDAD</th>
                                            <th>SUBTOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                            $total = 0;
                                            $cantidad = 0;
                                        @endphp
                                        @foreach ($tabla3 as $item)
                                            <tr>
                                                <td align="center">{{ $i }}</td>
                                                <td>{{ $item[1] }}</td>
                                                <td align="center">{{ $item[2] }}</td>
                                                <td align="right">{{ $item[3] }}</td>
                                            </tr>
                                            @php
                                                $i++;
                                                $cantidad = $cantidad + $item[2];
                                                $total = $total + $item[3];
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-success">
                                        <tr>
                                            <td colspan="2" align="right"><strong>TOTAL</strong></td>
                                            <td align="center"><strong>{{ $cantidad }}</strong></td>
                                            <td align="right"><strong>{{ number_format($total,2,'.',',') }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        @if (count($tabla4))
                           <div class="mb-3">
                            <label for=""><strong>POR PAGAR</strong></label>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered"
                                    style="font-size: 12px; width: 100%">
                                    <thead class="table-info">
                                        <tr align="center" style="background-color: lightgray">
                                            <th>NRO</th>
                                            <th>PLATAFORMA</th>
                                            <th>CANTIDAD</th>
                                            <th>SUBTOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                            $total = 0;
                                            $cantidad = 0;
                                        @endphp
                                        @foreach ($tabla4 as $item)
                                        <tr>
                                            <td align="center">{{ $i }}</td>
                                            <td>{{ $item[1] }}</td>
                                            <td align="center">{{ $item[2] }}</td>
                                            <td align="right">{{ $item[3] }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                            $cantidad = $cantidad + $item[2];
                                            $total = $total + $item[3];
                                        @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-success">
                                        <tr>
                                            <td colspan="2" align="right"><strong>TOTAL</strong></td>
                                            <td align="center"><strong>{{ $cantidad }}</strong></td>
                                            <td align="right"><strong>{{ number_format($total,2,'.',',') }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div> 
                        @endif
                        
                        <div class="mb-3">
                            <hr>
                            <h5>GASTOS ADMINISTRATIVOS</h5>
                            <div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover table-bordered"
                                        style="font-size: 12px; width: 100%">
                                        <thead class="table-info">
                                            <tr align="center" style="background-color: lightgray">
                                                <th>NRO</th>
                                                <th>PRODUCTO</th>
                                                <th>CANTIDAD</th>
                                                <th>PRECIO UNI.</th>
                                                <th>SUBTOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                                $total = 0;
                                                $cantidad = 0;
                                            @endphp
                                            @foreach ($tabla5 as $item)
                                                <tr>
                                                    <td align="center">{{ $i }}</td>
                                                    <td>{{ $item[0] }}</td>
                                                    <td align="center">{{ $item[1] }}</td>
                                                    <td align="right">{{ number_format($item[2],2,'.',',') }}</td>
                                                    <td align="right">0.00</td>
                                                </tr>
                                                @php
                                                    $i++;
                                                    $cantidad = $cantidad + $item[1];
                                                    $total = $total + $item[2];
                                                @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-success">
                                            <tr>
                                                <td colspan="2" align="right"><strong>TOTAL</strong></td>
                                                <td align="center"><strong>{{ $cantidad }}</strong></td>
                                                <td align="right"><strong>{{ number_format($total,2,'.',',') }}</strong></td>
                                                <td align="right"><strong>0.00</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
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
