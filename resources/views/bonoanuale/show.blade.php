@extends('layouts.app')

@section('template_title')
    {{ $bonoanuale->name ?? "{{ __('Show') Bonoanuale" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Bonoanuale</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('bonoanuales.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Gestion:</strong>
                            {{ $bonoanuale->gestion }}
                        </div>
                        <div class="form-group">
                            <strong>Estudiante Id:</strong>
                            {{ $bonoanuale->estudiante_id }}
                        </div>
                        <div class="form-group">
                            <strong>Tipomenu Id:</strong>
                            {{ $bonoanuale->tipomenu_id }}
                        </div>
                        <div class="form-group">
                            <strong>Pago Id:</strong>
                            {{ $bonoanuale->pago_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
