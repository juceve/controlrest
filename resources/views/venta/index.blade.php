@extends('layouts.app')

@section('template_title')
    Ventas
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                LISTADO DE VENTAS
                            </span>

                             <div class="float-right">
                                <strong>Usuario: </strong>{{Auth::user()->name}}
                              </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        @livewire('ventas.listado')
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection
