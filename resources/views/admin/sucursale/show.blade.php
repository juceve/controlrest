@extends('layouts.app')

@section('template_title')
Info Sucursal
@endsection

@section('content')
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Información de Sucursal
                    <div style="float:right;">
                        <a class="btn btn-primary btn-sm" href="{{ route('admin.sucursales.index') }}"><i
                                class="uil-arrow-left"></i> Volver</a>
                    </div>
                </div>

                <div class="card-body">

                    <div class="form-group mb-2">
                        <strong>Nombre:</strong>
                        {{ $sucursale->nombre }}
                    </div>
                    <div class="form-group mb-2">
                        <strong>Direccion:</strong>
                        {{ $sucursale->direccion }}
                    </div>
                    <div class="form-group mb-2">
                        <strong>Telefono:</strong>
                        {{ $sucursale->telefono }}
                    </div>
                    <div class="form-group mb-2">
                        <strong>Estado:</strong>
                        @if ($sucursale->estado)
                        <span class="badge badge-success-lighten rounded-pill">Activo</span>
                        @else
                        <span class="badge badge-secondary-lighten rounded-pill">Inactivo</span>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection