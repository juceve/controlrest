<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Pago de Creditos</title>
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

    <div class="container">
        <img src="{{ asset('img/logoAR.png') }}" style="width: 80px;">
        <div class="content">
            <div class="row">
                <div class="col-6">
                    <h5>A&R Catering Service</h5>
                </div>

            </div>
            <div class="row mb-1">
                <div class="col-12 text-center">
                    <h4>REPORTE DE PAGO DE CREDITOS</h4>
                    <small><strong>Del: {{ $fecInicio }} al {{ $fecFin }}</strong> </small>
                </div>
            </div>
            <br>
            <div class="row mb-5">
                <div class="col-12">
                    <div class="container">
                        <div class="mb-3">
                            <h4>DETALLE DE PAGOS</h4>
                            <table class="table table-bordered table-striped" style="font-size: 10px; width: 100%">
                                <thead>
                                    <tr  style="background-color: #E6E6E6">
                                        <th>Nro</th>
                                        <th>Fecha Pago</th>
                                        <th>Fecha Venta</th>
                                        <th>Cliente</th>
                                        <th>Forma Pago</th>
                                        <th>Usuario</th>
                                        <th>Importe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($pagos as $pago)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>
                                                {{ $pago->fecha }}
                                            </td>
                                            <td>
                                                {{ $pago->venta->fecha }}
                                            </td>
                                            <td>
                                                {{ $pago->venta->cliente }}
                                            </td>
                                            <td>
                                                {{ $pago->tipopago }}
                                            </td>
                                            <td>
                                                {{ $pago->user->name }}
                                            </td>
                                            <td align="right">
                                                {{ $pago->importe }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr style="background-color: #E6E6E6">
                                        <td colspan="6"><b>TOTAL</b></td>
                                        <td align="right"><b>{{number_format($pagos->sum('importe'), 2)}}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <hr>
            <h5>TOTALES POR TIPO</h5>
            <table class="table table-bordered table-condensed" style="font-size: 12px;">
                <tbody>
                    @foreach ($tipopagos as $tipo)
                        @php
                            $total = $pagos->where('tipopago_id', $tipo->id)->sum('importe');
                        @endphp
                        @if ($total > 0)
                            <tr>
                                <td>{{ $tipo->nombre }}</td>
                                <td align="right">{{ number_format($total, 2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                    <tr style="background-color: #E6E6E6">
                        <td align="right">
                            <strong>TOTAL</strong>
                        </td>
                        <td align="right">
                           <b> {{ number_format($pagos->sum('importe'), 2)  }}</b>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

</body>

</html>
