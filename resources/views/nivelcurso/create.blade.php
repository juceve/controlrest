@extends('layouts.app')

@section('template_title')
Crear Nivel
@endsection

@section('content')
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">

            @includeif('partials.errors')
            @php
            $sucursal = Auth::user()->sucursale_id;
            @endphp
            <div class="card card-default">
                <div class="card-header">
                    <span class="card-title">
                        Registro de Nivel de Curso
                        @if (!is_null($sucursal))
                            - Sucursal {{Auth::user()->sucursale->nombre}}
                        @endif
                    </span>
                    <div style="float: right;">
                        <a href="{{route('nivelcursos.index')}}" class="btn btn-sm btn-primary"><i class="uil-arrow-left"></i> Volver</a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('nivelcursos.store') }}" role="form"
                        enctype="multipart/form-data">
                        @csrf

                        @include('nivelcurso.form')

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection