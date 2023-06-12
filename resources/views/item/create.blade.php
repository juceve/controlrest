@extends('layouts.app')

@section('template_title')
    Registro de Producto
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
                                REGISTRO DE PRODUCTO
                            </span>
                
                            
                            <div class="float-right">
                                <a href="{{route('items.index')}}" class="btn btn-primary btn-sm float-right"
                                    data-placement="left">
                                    <i class="uil-arrow-left"></i>
                                    Volver
                                </a>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('items.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('item.form')
                            <div class="box-footer mt20">
                                <a href="{{route('items.index')}}" class="btn btn-secondary mb-2" style="width: 200px">                                    
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary mb-2" style="width: 200px">Registrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $('#nombre').focus();
        });
    </script>
@endsection
