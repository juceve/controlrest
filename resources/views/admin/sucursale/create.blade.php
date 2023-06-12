@extends('layouts.app')

@section('template_title')
    Registrar Sucursal
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title"> REGISTRO DE SUCURSAL</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        Datos de la Sucursal
                        <div style="float:right;">
                            <a href="{{route('admin.sucursales.index')}}" class="btn btn-sm btn-primary"><i class="uil-arrow-left"></i> Volver</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.sucursales.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('admin.sucursale.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
