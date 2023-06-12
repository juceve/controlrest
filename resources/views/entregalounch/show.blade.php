@extends('layouts.app')

@section('template_title')
    {{ $entregalounch->name ?? "{{ __('Show') Entregalounch" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Entregalounch</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('entregalounches.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Fechaentrega:</strong>
                            {{ $entregalounch->fechaentrega }}
                        </div>
                        <div class="form-group">
                            <strong>Detallelonchera Id:</strong>
                            {{ $entregalounch->detallelonchera_id }}
                        </div>
                        <div class="form-group">
                            <strong>Menu Id:</strong>
                            {{ $entregalounch->menu_id }}
                        </div>
                        <div class="form-group">
                            <strong>Venta Id:</strong>
                            {{ $entregalounch->venta_id }}
                        </div>
                        <div class="form-group">
                            <strong>User Id:</strong>
                            {{ $entregalounch->user_id }}
                        </div>
                        <div class="form-group">
                            <strong>Sucursale Id:</strong>
                            {{ $entregalounch->sucursale_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
