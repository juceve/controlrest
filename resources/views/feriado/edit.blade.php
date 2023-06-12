@extends('layouts.app')

@section('template_title')
    Editar Feriado
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <label for="">Editar Feriado</label>
                        <div style="float:right">
                            <a class="btn btn-sm btn-info" href="{{ route('feriados.index') }}"> <i class="uil-arrow-left"></i> Volver</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('feriados.update', $feriado->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('feriado.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
