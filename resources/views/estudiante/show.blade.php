@extends('layouts.app')

@section('template_title')
Info Estudiante
@endsection

@section('content')
<section class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            INFORMACIÓN DEL ESTUDIANTE
                        </span>

                        <div class="float-right">
                            <a href="{{ route('estudiantes.index') }}" class="btn btn-primary btn-sm float-right"
                                data-placement="left">
                                <i class="uil-arrow-left"></i>
                                Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="form-group mb-3">
                        <strong>Código:</strong>
                        {{ $estudiante->codigo }}
                    </div>
                    <div class="form-group mb-3">
                        <strong>Nombre:</strong>
                        {{ $estudiante->nombre }}
                    </div>
                    <div class="form-group mb-3">
                        <strong>Cédula:</strong>
                        {{ $estudiante->cedula }}
                    </div>
                    <div class="form-group mb-3">
                        <strong>Teléfono:</strong>
                        {{ $estudiante->telefono }}
                    </div>
                    <div class="form-group mb-3">
                        <strong>Tutor:</strong>
                        @if (!is_null($estudiante->tutore_id))
                        <a class="" href="{{ route('vinculosestudiantes',$estudiante->tutore_id) }}" title="Opciones">{{
                            $estudiante->tutore->nombre }}</a>
                        @else
                        No asignado
                        @endif
                        

                    </div>
                    <div class="form-group mb-3">
                        <strong>Curso:</strong>
                        {{ $estudiante->curso->nombre.' - '.$estudiante->curso->nivelcurso->nombre }}
                    </div>
                    <div class="form-group mb-3">
                        <strong>Establecimiento:</strong>
                        {{ $estudiante->curso->nivelcurso->sucursale->nombre }}
                    </div>
                    <div class="form-group mb-3 d-none">
                        <strong>Verificado:</strong>
                        {{ $estudiante->verificado }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection