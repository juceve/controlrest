@extends('layouts.app')

@section('template_title')
Info Empresa
@endsection

@section('content')
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Información de la Empresa
                    <div style="float: right">
                        <a class="btn btn-sm btn-primary" href="{{ route('admin.empresas.index') }}"> Atras</a>
                    </div>
                </div>

                <div class="card-body">

                    <div class="form-group mb-2">
                        <strong>Razon social:</strong>
                        {{ $empresa->razonsocial }}
                    </div>
                    <div class="form-group mb-2">
                        <strong>Dirección:</strong>
                        {{ $empresa->direccion }}
                    </div>
                    <div class="form-group mb-2">
                        <strong>Teléfono:</strong>
                        {{ $empresa->telefono }}
                    </div>
                    <div class="form-group mb-2">
                        <strong>Email:</strong>
                        {{ $empresa->email }}
                    </div>
                    <div class="form-group mb-2">
                        <strong>Nit:</strong>
                        {{ $empresa->nit }}
                    </div>
                    <div class="form-group mb-2">
                        <strong>Avatar:</strong>
                        {{ $empresa->avatar }}
                    </div>
                    <div class="form-group mb-2">
                        <strong>Responsable:</strong>
                        {{ $empresa->responsable }}
                    </div>
                    <div class="form-group mb-2">
                        <strong>Teléfono Responsable:</strong>
                        {{ $empresa->telefono_responsable }}
                    </div>
                    <div class="form-group mb-2">
                        <strong>Estado:</strong>
                        {{ $empresa->estado?'Activo':'Inactivo' }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection