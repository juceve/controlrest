@extends('layouts.app')

@section('template_title')
    Info Precios | 
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                INFORMACION DE PRECIO
                            </span>     
                            <div class="float-right">
                                <a href="{{route('precios.index')}}" class="btn btn-primary btn-sm float-right"
                                    data-placement="left">
                                    <i class="uil-arrow-left"></i>
                                    Volver
                                </a>
                            </div>                            
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group mb-3">
                            <strong>Nivel de curso: </strong>
                            {{ $preciomenu->nivelcurso->nombre }}
                        </div>
                        <div class="form-group mb-3">
                            <strong>Tipo de menu: </strong>
                            {{ $preciomenu->tipomenu->nombre }}
                        </div>
                        <div class="form-group mb-3">
                            <strong>Precio {{$moneda->abreviatura}}: </strong>
                            {{ $preciomenu->precio }}
                        </div>
                        <div class="form-group mb-3">
                            <strong>Precio {{$moneda->abreviatura}} al por mayor: </strong>
                            {{ $preciomenu->preciopm }}
                        </div>
                        <div class="form-group mb-3">
                            <strong>Cantidad Min. Mayoreo: </strong>
                            {{ $preciomenu->cantmin }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
