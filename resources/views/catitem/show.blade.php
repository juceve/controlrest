@extends('layouts.app')

@section('template_title')
    Info Categoria | 
@endsection

@section('content')
    <section class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                DATOS DE CATEGORIA
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
                        
                        <div class="form-group">
                            <strong>Nombre:</strong>
                            {{ $catitem->nombre }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
