@extends('layouts.app')

@section('template_title')
    {{ $nivelcurso->name ?? 'Show Nivelcurso' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Nivelcurso</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('nivelcursos.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Nombre:</strong>
                            {{ $nivelcurso->nombre }}
                        </div>
                        <div class="form-group">
                            <strong>Sucursale Id:</strong>
                            {{ $nivelcurso->sucursale_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
