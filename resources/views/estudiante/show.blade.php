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
                                <a href="javascript:history.back()" class="btn btn-primary btn-sm float-right"
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
                                <a class="" href="{{ route('vinculosestudiantes', $estudiante->tutore_id) }}"
                                    title="Opciones">{{ $estudiante->tutore->nombre }}</a>
                            @else
                                No asignado
                            @endif


                        </div>
                        <div class="form-group mb-3">
                            <strong>Curso:</strong>
                            @if ($estudiante->curso_id)
                                {{ $estudiante->curso->nombre . ' - ' . $estudiante->curso->nivelcurso->nombre }}
                            @else
                                Desafiliado
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <strong>Establecimiento:</strong>
                            @if ($estudiante->curso_id)
                                {{ $estudiante->curso->nivelcurso->sucursale->nombre }}
                            @else
                                Desafiliado
                            @endif

                        </div>
                        <div class="form-group mb-3 d-none">
                            <strong>Verificado:</strong>
                            {{ $estudiante->verificado }}
                        </div>

                        <h5 class="text-info mt-5">VENTAS VINCULADAS</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm dataTable"
                                style="vertical-align: middle">
                                <thead class="table-info">
                                    <tr>
                                        <th>ID</th>
                                        <th>FECHA</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ventas as $venta)
                                        <tr>
                                            <td>{{ $venta->venta_id }}</td>
                                            <td>{{ $venta->fecha }}</td>
                                            <td> <a href="{{ route('ventas.show', $venta->venta_id) }}" target="_blank"
                                                    rel="noopener" class="btn btn-sm btn-info"><i class="uil-eye"></i>
                                                    Ver</a> </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
