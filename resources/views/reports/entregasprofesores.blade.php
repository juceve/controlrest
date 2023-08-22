<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Entregas</title>
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
                    <h4>ALMUERZOS ENTREGADOS A PROFESORES</h4>
                    <small><strong>Fecha:</strong> {{ $fecha }}</small>
                </div>
            </div><br>

            <div class="row mb-5">
                <div class="col-12">
                    <div class="container">
                        <div class="mb-3">

                            <table class="table table-bordered" style="font-size: 12px">
                                <thead class="table-secondary">

                                    <tr>
                                        <td align="center"><strong> NRO</strong></td>
                                        <td><strong> NOMBRE</strong></td>
                                        <td align="center"><strong> PAGADO</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($tabla1 as $item)
                                        <tr>
                                            <td align="center">{{ $i++ }}</td>
                                            <td>{{ $item->estudiante->nombre }}</td>
                                            <td align="center">{{ $item->pagado ? 'SI' : 'NO' }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        <br><br><br>
                        <table style="width: 100%">
                            <tr>
                                <td align="center">
                                    ______________________ <br>CAJERO
                                </td>
                                <td align="center">
                                    ______________________ <br>SUPERVISOR
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <br><br><br>


    </div>
    </div>

</body>

</html>
