@extends('layouts.app')

@section('template_title')
Info Producto
@endsection

@section('content')
<section class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="float-left">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                INFORMACIÓN DE PRODUCTO
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
                </div>

                <div class="card-body">

                    <div class="form-group mb-3">
                        <strong>Nombre:</strong>
                        {{ $item->nombre }}
                    </div>
                    <div class="form-group mb-3">
                        <strong>Descripcion:</strong>
                        {{ $item->descripcion }}
                    </div>
                    <div class="form-group mb-3">
                        <strong>Categoria:</strong>
                        {{ $item->catitem->nombre }}
                    </div>
                    <div class="form-group mb-3">
                        <strong>Estado:</strong>
                        @if ($item->estado)
                        <span class="badge bg-success">ACTIVO</span>
                        @else
                        <span class="badge bg-danger">INACTIVO</span>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <strong>Imagen:</strong><br>
                        <img src="{{Storage::url($item->imagen)}}" style="width: 150px" class="img-thumbnail">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection