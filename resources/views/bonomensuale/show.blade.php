@extends('layouts.app')

@section('template_title')
    {{ $bonomensuale->name ?? "{{ __('Show') Bonomensuale" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Bonomensuale</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('bonomensuales.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Fechainicio:</strong>
                            {{ $bonomensuale->fechainicio }}
                        </div>
                        <div class="form-group">
                            <strong>Fechafin:</strong>
                            {{ $bonomensuale->fechafin }}
                        </div>
                        <div class="form-group">
                            <strong>Estudiante Id:</strong>
                            {{ $bonomensuale->estudiante_id }}
                        </div>
                        <div class="form-group">
                            <strong>Tipomenu Id:</strong>
                            {{ $bonomensuale->tipomenu_id }}
                        </div>
                        <div class="form-group">
                            <strong>Pago Id:</strong>
                            {{ $bonomensuale->pago_id }}
                        </div>
                        <div class="form-group">
                            <strong>Estado:</strong>
                            {{ $bonomensuale->estado }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
