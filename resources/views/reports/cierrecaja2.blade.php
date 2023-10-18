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
                    <h4>OPERACIONES - BONOS Y RESERVAS</h4>
                    <small><strong>Fecha:</strong> {{ $cierre->fecha }}</small>
                </div>
            </div><br>
            <div class="row ">
                <div class="col-12">
                    <div class="container">
                        <div class="">
                            <table class="table table-bordered"
                                style="font-size: 12px; width: 40%;text-transform: uppercase;">
                                <tr>
                                    <td><b>USUARIO:</b> </td>
                                    <td>{{ $cierre->user->name }}</td>
                                </tr>
                                <tr>
                                    <td><b>SUCURSAL:</b> </td>
                                    <td>{{ $cierre->sucursale->nombre }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-12">
                    <div class="container">
                        <div class="mb-3">
                            <h6>OPERACIONES CON PAGO REALIZADO</h6>
                            <table class="table table-bordered" style="font-size: 12px">
                                <thead>
                                    <tr align="center" style="background-color: #dadada">
                                        <td>NRO</td>
                                        <td>ITEM</td>
                                        <td>TIPO PAGO</td>
                                        <td>DESCUENTO</td>
                                        <td>CANTIDAD</td>
                                        {{-- <td style="width: 100px;">PRECIO UNIT.</td> --}}
                                        <td style="width: 100px;">SUBTOTAL</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $total = 0;
                                    @endphp
                                    @if ($detalles)

                                        @foreach ($detalles as $ingreso)
                                            <tr>
                                                <td align="center">{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}</td>
                                                <td>{{ $ingreso->descripcion }}</td>
                                                <td align="center">{{ $ingreso->tipopago }}</td>
                                                <td align="center">{{ $ingreso->descuento }}</td>
                                                <td align="center">{{ $ingreso->cantidad }}</td>
                                                {{-- <td align="right"> --}}
                                                {{-- {{ number_format($ingreso->preciounitario, 2, '.', ',') }}</td> --}}
                                                <td align="right">{{ number_format($ingreso->importe, 2, '.', ',') }}
                                                </td>

                                            </tr>
                                            @php
                                                $i++;
                                                $total = $total + $ingreso->importe;
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td align="right">TOTAL Bs.:</td>
                                        <td align="right">{{ number_format($total, 2, '.', ',') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>


                        <table class="table table-bordered">
                            @if ($totalEfectivo)
                                <tr>
                                    <td>
                                        <strong>BS. PAGO EFECTIVO: </strong>
                                    </td>
                                    <td>
                                        {{ number_format($totalEfectivo, 2, '.', ',') }}
                                    </td>
                                </tr>
                            @endif
                            @if ($totalQr)
                                <tr>
                                    <td>
                                        <strong>BS. PAGO QR: </strong>
                                    </td>
                                    <td>
                                        {{ number_format($totalQr, 2, '.', ',') }}
                                    </td>
                                </tr>
                            @endif
                            @if ($totalTr)
                                <tr>
                                    <td>
                                        <strong>BS. TRANS. BANCARIA: </strong>
                                    </td>
                                    <td>
                                        {{ number_format($totalTr, 2, '.', ',') }}
                                    </td>
                                </tr>
                            @endif
                            @if ($totalGa)
                                <tr>
                                    <td>
                                        <strong>BS. GASTOS ADM.: </strong>
                                    </td>
                                    <td>
                                        {{ number_format($totalGa, 2, '.', ',') }}
                                    </td>
                                </tr>
                            @endif
                        </table>

                        {{-- <div class="content">

                        </div>

                        @if ($totalQr)
                            <div class="content">
                                <strong>BS. PAGO QR:</strong> {{ number_format($totalQr, 2, '.', ',') }}
                            </div>
                        @endif
                        @if ($totalTr)
                            <div class="content">
                                <strong>BS. TRANSF. BANCARIA: </strong>{{ number_format($totalTr, 2, '.', ',') }}
                            </div>
                        @endif
                        @if ($totalGa)
                            <div class="content">
                                <strong>BS. GASTOS ADM.: </strong>{{ number_format($totalGa, 2, '.', ',') }}
                            </div>
                        @endif --}}
                        {{-- <div class="mb-3">
                            <h6>OPERACIONES CON PAGO PENDIENTE</h6>
                            <table class="table table-bordered" style="font-size: 12px">
                                <thead>
                                    <tr style="background-color: #dadada">
                                        <td>OPERACION</td>
                                        <td align="center">TIPO DE PAGO</td>
                                        <td>ESTADO PAGO</td>
                                        <td align="center">CANTIDAD</td>
                                        <td align="right">IMPORTE</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detalles as $detalle)
                                    @if ($detalle->estadopago_id == 1)
                                    <tr>
                                        <td>{{$detalle->operacion}}</td>
                                        <td align="center">{{$detalle->abreviatura}}</td>
                                        <td>{{$detalle->estadopago}}</td>
                                        <td align="center">{{$detalle->cantidad}}</td>
                                        <td align="right">{{number_format($detalle->importe,2,'.',',')}}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td align="center">TOTAL Bs.:</td>
                                        <td align="right">{{number_format($totalpp,2,'.',',')}}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div> --}}

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
