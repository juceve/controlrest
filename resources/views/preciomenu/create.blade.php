@extends('layouts.app')

@section('template_title')
    Registrar Precio Menu
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header bg-primary text-white">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                REGISTRA PRECIOS DE MENU
                            </span>     
                            <div class="float-right">
                                <a href="{{route('precios.index')}}" class="btn btn-primary btn-sm float-right"
                                    data-placement="left">
                                    <i class="uil-arrow-left"></i>
                                    Volver
                                </a>
                            </div>                            
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('precios.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('preciomenu.form')
                            <div class="box-footer mt-3">
                                <a href="{{route('precios.index')}}" class="btn btn-secondary mb-2" style="width: 200px">Cancelar</a>
                                <button type="submit" class="btn btn-primary mb-2" style="width: 200px">REGISTRAR</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
