@extends('layouts.app')

@section('template_title')
   Nuevo Feriado
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <label for="">Nuevo Feriado</label>
                        <div style="float:right">
                            <a class="btn btn-sm btn-info" href="{{ route('feriados.index') }}"> <i class="uil-arrow-left"></i> Volver</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('feriados.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('feriado.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
