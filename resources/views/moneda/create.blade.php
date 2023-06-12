@extends('layouts.app')

@section('template_title')
   Registrar Moneda
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Registrar Moneda</span>
                        <div style="float:right">
                            <a href="{{ route('monedas.index') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                              <i class="uil-arrow-left"></i> Volver
                            </a>
                          </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('monedas.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('moneda.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
