@extends('layouts.app')

@section('template_title')
    {{ $cierrecaja->name ?? "{{ __('Show') Cierrecaja" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Cierrecaja</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('cierrecajas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Fecha:</strong>
                            {{ $cierrecaja->fecha }}
                        </div>
                        <div class="form-group">
                            <strong>Hora:</strong>
                            {{ $cierrecaja->hora }}
                        </div>
                        <div class="form-group">
                            <strong>User Id:</strong>
                            {{ $cierrecaja->user_id }}
                        </div>
                        <div class="form-group">
                            <strong>Sucursale Id:</strong>
                            {{ $cierrecaja->sucursale_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
