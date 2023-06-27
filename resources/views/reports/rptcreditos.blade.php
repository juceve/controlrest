<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CREDITO PROFESORES</title>
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
    <img src="{{ asset('img/logoAR.png') }}" style="width: 80px;">

    <div class="container">
        <div class="content">
            <div class="row">
                <div class="col-6">
                    <h6>A&R Catering Service</h6>
                </div>

            </div>
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <h6 class="text-center">ALMUERZOS A CREDITOS PARA PROFESORES</h6>
                    <div class="content text-center">
                        <h6><small>Del: {{ $fechaI }} al {{ $fechaF }}</small></h6>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm " style="font-size: 5px; width: 100%">
                            <thead style="background-color: lightgray">
                                <tr style="vertical-align: middle;">
                                    <td><strong>CLIENTE</strong></td>
                                    <td align="center"><strong>AREA</strong></td>
                                    @foreach ($dias as $dia)
                                        <td align="center"><strong>{{ fechacorta($dia[0]) }} <br>
                                                {{ substr(nombreDiaESP($dia[0]), 0, 3) }}</strong></td>
                                    @endforeach
                                    <td align="center"><strong>TOTAL Bs.</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resultados as $items)
                                    <tr>
                                        @foreach ($items as $item)
                                            <td align="{{ $item[1] }}" style="{{ $item[2] }}">
                                                {{ $item[0] }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background-color: #cef5ea;">
                                <tr>
                                    <td colspan="2" align="right"><strong>TOTAL</strong></td>
                                    @foreach ($dias as $item)
                                        <td align="center"><strong>{{ number_format($item[1], 2, '.', ',') }}</strong>
                                        </td>
                                    @endforeach
                                    <td align="center"><strong>{{ number_format($ttotal, 2, '.', ',') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>



                </div>

            </div>

        </div>
    </div>

</body>

</html>
