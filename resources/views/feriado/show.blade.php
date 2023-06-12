@extends('layouts.app')

@section('template_title')
    Info Feriado
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <label for="">Información de Feriado</label>
                        <div style="float:right">
                            <a class="btn btn-sm btn-info" href="{{ route('feriados.index') }}"> <i class="uil-arrow-left"></i> Volver</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group mb-2">
                            <strong>Fecha: </strong>
                            {{ $feriado->fecha }}
                        </div>
                        <div class="form-group mb-2">
                            <strong>Motivo: </strong>
                            {{ $feriado->motivo }}
                        </div>
                        <div class="form-group mb-2">
                            <strong>Sucursal: </strong>
                            {{ $feriado->sucursale->nombre }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
