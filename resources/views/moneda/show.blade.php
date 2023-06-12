@extends('layouts.app')

@section('template_title')
    {{ $moneda->name ?? "{{ __('Show') Moneda" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Moneda</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('monedas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Nombre:</strong>
                            {{ $moneda->nombre }}
                        </div>
                        <div class="form-group">
                            <strong>Abreviatura:</strong>
                            {{ $moneda->abreviatura }}
                        </div>
                        <div class="form-group">
                            <strong>Tasacambio:</strong>
                            {{ $moneda->tasacambio }}
                        </div>
                        <div class="form-group">
                            <strong>Predeterminado:</strong>
                            {{ $moneda->predeterminado }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
