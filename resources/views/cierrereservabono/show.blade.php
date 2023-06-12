@extends('layouts.app')

@section('template_title')
    {{ $cierrereservabono->name ?? "{{ __('Show') Cierrereservabono" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Cierrereservabono</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('cierrereservabonos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Fecha:</strong>
                            {{ $cierrereservabono->fecha }}
                        </div>
                        <div class="form-group">
                            <strong>Hora:</strong>
                            {{ $cierrereservabono->hora }}
                        </div>
                        <div class="form-group">
                            <strong>User Id:</strong>
                            {{ $cierrereservabono->user_id }}
                        </div>
                        <div class="form-group">
                            <strong>Sucursale Id:</strong>
                            {{ $cierrereservabono->sucursale_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
