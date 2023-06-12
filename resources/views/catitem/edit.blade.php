@extends('layouts.app')

@section('template_title')
    Editar Categoria
@endsection

@section('content')
    <section class="container-fluid">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header bg-primary text-white">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                EDITAR CATEGORIA
                            </span>

                             <div class="float-right">
                                <a href="{{ route('catitems.index') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  <i class="uil-arrow-left"></i>
                                  Volver
                                </a>
                              </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('catitems.update', $catitem->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('catitem.form')
                            <div class="box-footer mt20">
                                <a href="{{route('catitems.index')}}" class="btn btn-secondary" style="width: 200px">Cancelar</a>
                                <button type="submit" class="btn btn-primary" style="width: 200px">Guardar cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
