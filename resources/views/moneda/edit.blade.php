@extends('layouts.app')

@section('template_title')
    Editar Moneda
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Editar Moneda</span>
                        <div style="float:right">
                            <a href="{{ route('monedas.index') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                              <i class="uil-arrow-left"></i> Volver
                            </a>
                          </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('monedas.update', $moneda->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('moneda.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
