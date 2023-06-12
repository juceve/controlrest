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
                    <h4>REPORTE DE VENTAS</h4>
                    <small><strong>Del: {{ $fecInicio }} al {{ $fecFin }}</strong> </small>
                </div>
            </div><br>

            <div class="row ">
                <div class="col-12">
                    <div class="container">
                        <div class="">
                            <table class="table table-bordered table-sm"
                                style="font-size: 11px; width: 40%;text-transform: uppercase;">
                                <tr>
                                    <td><b>USUARIO:</b> </td>
                                    <td>{{ $usuario[0] }}</td>
                                </tr>
                                <tr>
                                    <td><b>SUCURSAL:</b> </td>
                                    <td>{{ $usuario[1] }}</td>
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
                            <h4>DETALLE DE VENTAS</h4>
                            <table class="table table-bordered" style="font-size: 10px; width: 100%">
                                <tr>
                                <tr>
                                    <td align="center"><strong> ID</strong></td>
                                    <td align="center"><strong> Fecha</strong></td>
                                    <td><strong> Cliente</strong></td>
                                    <td align="center"><strong> Tipo Pago</strong></td>
                                    <td align="center"><strong> Estado</strong></td>
                                    <td align="right"><strong> Importe</strong></td>
                                </tr>
                                </tr>
                                @foreach ($contenedor as $venta)
                                    <tr>
                                        <td align="center">{{ $venta['id'] }}</td>
                                        <td align="center">{{ $venta['fecha'] }}</td>
                                        <td>{{ $venta['cliente'] }}</td>
                                        <td align="center">{{ $venta['tipopago'] }}</td>
                                        <td align="center">{{ $venta['estadopago'] }}</td>
                                        <td align="right">{{ $venta['importe'] }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>

</body>

</html>
