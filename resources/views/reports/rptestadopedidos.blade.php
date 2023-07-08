<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Estado de Pedidos</title>
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
            
            <h4 class="text-center">ESTADO DE PEDIDOS POR ALUMNOS</h4>
            <h5 class="text-center">Curso : {{$curso}}</h5>
            <h5 class="text-center">Fecha : {{date('Y-m-d')}}</h5>
                
                <div class="">
                    <table class="table table-bordered table-sm table-striped dataTable" style="width: 100%;vertical-align: middle; font-size: 11px;">
                        <thead>
                            <tr align="center" style="background-color: lightgray; ">
                                <td><strong>NRO</strong></td>
                                <td><strong>NOMBRE</strong></td>
                                <td><strong>PAGADOS</strong></td>
                                <td><strong>ENTREGADOS</strong></td>
                                <td><strong>RESTANTES</strong></td>
                            </tr>
                        </thead>
                        <tbody> @php
                                $i=1;
                            @endphp
                            @foreach ($tabla as $item)          
                                <tr>
                                    <td align="center">{{$i++}}</td>
                                    <td>{{$item['estudiante']}}</td>
                                    <td align="center">{{$item['pagados']}}</td>
                                    <td align="center">{{$item['entregas']}}</td>
                                    <td align="center">{{$item['restantes']}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </div>
    </div>

</body>

</html>
