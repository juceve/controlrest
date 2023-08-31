@extends('layouts.app')

@section('template_title')
Info Venta 
@endsection

@section('content')
<section class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            INFORMACIÓN DE LA VENTA
                        </span>


                        <div class="float-right">
                            <a href="{{route('ventas.index')}}" class="btn btn-success btn-sm float-right"
                                data-placement="left">
                                <i class="uil-arrow-left"></i>
                                Volver
                            </a>
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-12 col-md-6 form-group mb-2">
                            <strong>ID:</strong>
                            {{ $venta->id }}
                        </div>
    
                        <div class="col-12 col-md-6 form-group mb-2">
                            <strong>Fecha:</strong>
                            {{ $venta->fecha }}
                        </div>
    
                        <div class="col-12 col-md-6 form-group mb-2">
                            <strong>Cliente:</strong>
                            {{ $venta->cliente }}
                        </div>
    
                        <div class="col-12 col-md-6 form-group mb-2">
                            <strong>Estado Pago:</strong>
                            {{ $venta->estadopago->nombre }}
                        </div>
    
                        <div class="col-12 col-md-6 form-group mb-2">
                            <strong>Tipo Pago:</strong>
                            {{ $venta->tipopago->nombre }}
                        </div>
    
                        <div class="col-12 col-md-6 form-group mb-2">
                            <strong>Importe:</strong>
                            {{ $venta->importe }}
                        </div>
                        <div class="col-12 col-md-6 form-group mb-2">
                            <strong>Usuario:</strong>
                            {{ $venta->user->name }}
                        </div>
                        <div class="col-12 col-md-6 form-group mb-2">
                            <strong>Observaciones:</strong>
                            {{ $venta->observaciones }}
                        </div>
                    </div>
                    <hr>
                    <h2 class="h5">DETALLES</h2>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr align="center">
                                    <th>NRO</th>
                                    <th>DETALLE</th>
                                    <th>CANTIDAD</th>
                                    <th>P. UNIT.</th>
                                    <th>SUBTOTAL</th>
                                    <th>OBSERVACIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i=1;
                                @endphp
                                @foreach ($venta->detalleventas as $dventa)
                                    <tr align="center">
                                        <td>{{$i}}</td>
                                        <td align="left">{{$dventa->descripcion}}</td>
                                        <td>{{$dventa->cantidad}}</td>
                                        <td>{{$dventa->preciounitario}}</td>
                                        <td>{{$dventa->subtotal}}</td>
                                        <td>{{$dventa->observaciones?$dventa->observaciones:"Sin observaciones"}}</td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
                                @endforeach
                                
                            </tbody>
                        </table>                        
                    </div>
                    @if ($detalleEstudiantes)
                    <h2 class="h5">ESTUDIANTES VINCULADOS</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-info">
                                    <tr >
                                        <th>CODIGO</th>
                                        <th>ESTUDIANTE</th>
                                        <th>DETALLE</th>
                                        <th>TIPO MENU</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=1;
                                    @endphp
                                    @foreach ($detalleEstudiantes as $de)
                                        <tr >
                                            <td>{{$de[3]}}</td>
                                            <td>{{$de[0]}}</td>
                                            <td>{{$de[1]}}</td>
                                            <td>{{$de[2]}}</td>
                                            
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                    {{-- @dump($detalleEstudiantes) --}}
                                </tbody>
                            </table>  
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection