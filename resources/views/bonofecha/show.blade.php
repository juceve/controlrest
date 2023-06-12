@extends('layouts.app')

@section('template_title')
    {{ $bonofecha->name ?? "{{ __('Show') Bonofecha" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Bonofecha</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('bonofechas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Fechainicio:</strong>
                            {{ $bonofecha->fechainicio }}
                        </div>
                        <div class="form-group">
                            <strong>Fechafin:</strong>
                            {{ $bonofecha->fechafin }}
                        </div>
                        <div class="form-group">
                            <strong>Estudiante Id:</strong>
                            {{ $bonofecha->estudiante_id }}
                        </div>
                        <div class="form-group">
                            <strong>Tipomenu Id:</strong>
                            {{ $bonofecha->tipomenu_id }}
                        </div>
                        <div class="form-group">
                            <strong>Pago Id:</strong>
                            {{ $bonofecha->pago_id }}
                        </div>
                        <div class="form-group">
                            <strong>Estado:</strong>
                            {{ $bonofecha->estado }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
