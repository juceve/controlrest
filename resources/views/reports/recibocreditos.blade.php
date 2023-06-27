<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RECIBOS</title>
    <link href="{{public_path('invoice/bootstrap.min.css')}}" rel="stylesheet" id="bootstrap-css">
    <script src="{{public_path('invoice/bootstrap.min.js')}}"></script>
    <script src="{{public_path('invoice/jquery-1.11.1.min.js')}}"></script>
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
    <img src="{{asset('img/logoAR.png')}}" style="width: 120px;">

    <div class="container">
        <div class="content">
            <div class="row">
                <div class="col-6">
                    <h5>A&R Catering Service</h5>
                </div>

            </div>
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <h2>RECIBO</h2>
                    <small><strong>Fecha:</strong> {{date('Y-m-d')}}</small>
                </div>
            </div><br>
            <div class="row ">
                <div class="col-12">
                    <div class="container">
                        <div class="">
                            <table class="table table-bordered" style="font-size: 12px; width: 40%;text-transform: uppercase;">
                                <tr>
                                    <td><b>CLIENTE:</b> </td>
                                    <td>{{$cliente}}</td>
                                </tr>
                                <tr>
                                    <td><b>SUCURSAL:</b> </td>
                                    <td>{{Auth::user()->sucursale->nombre}}</td>
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
                                        <td>DETALLE</td>
                                        <td>CANTIDAD</td>
                                        <td>SUBTOTAL</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=1;
                                        $total = 0;
                                    @endphp
                                    @if ($detalles)
                                        @foreach ($detalles as $detalle)
                                            <tr>
                                                <td align="center">{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}</td>
                                                <td>{{ $detalle[0] }}</td>
                                                <td align="center">{{ $detalle[1] }}</td>
                                                <td align="right">{{ number_format($detalle[2], 2, '.', ',') }}</td>

                                            </tr>
                                            @php
                                                $i++;
                                                $total = $total + $detalle[2];
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td align="right">TOTAL Bs.:</td>
                                        <td align="right">{{number_format($total,2,'.',',')}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td align="right">TIPO PAGO:</td>
                                        <td align="right">{{$tipopago}}</td>
                                    </tr>
                                </tfoot>
                            </table>                            
                        </div>
                       

                        <div class="mb-3">
                       
                            <br><br><br>
                            <table style="width: 100%">
                                <tr>
                                    <td align="center">
                                        ______________________ <br>USUARIO
                                    </td>
                                    <td align="center">
                                        ______________________ <br>CLIENTE
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