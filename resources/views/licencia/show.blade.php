@extends('layouts.app')

@section('template_title')
    {{ $licencia->name ?? "{{ __('Show') Licencia" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Licencia</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('licencias.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Estudiante Id:</strong>
                            {{ $licencia->estudiante_id }}
                        </div>
                        <div class="form-group">
                            <strong>Fecha:</strong>
                            {{ $licencia->fecha }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
