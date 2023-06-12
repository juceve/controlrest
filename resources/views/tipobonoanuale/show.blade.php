@extends('layouts.app')

@section('template_title')
    {{ $tipobonoanuale->name ?? "{{ __('Show') Tipobonoanuale" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Tipobonoanuale</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('tipobonoanuales.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Tipomenu Id:</strong>
                            {{ $tipobonoanuale->tipomenu_id }}
                        </div>
                        <div class="form-group">
                            <strong>Precio:</strong>
                            {{ $tipobonoanuale->precio }}
                        </div>
                        <div class="form-group">
                            <strong>Sucursale Id:</strong>
                            {{ $tipobonoanuale->sucursale_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
