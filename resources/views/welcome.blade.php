@extends('layouts.web2')

@section('content')
    {{-- <div class="container">
    <div class="form-group py-2 text-center animacion show">
        <img src="{{asset('img/ar_logo.png')}}" class="img-fluid">
    </div>
</div> --}}
    <div class="container">
        <div class="row justify-content-center animacion">
            {{-- <div class="col-12 col-lg-2">
        </div> --}}
            <div class="col-12 col-lg-12">
                <hr>
                <img src="{{ asset('img/menusemanal.png') }}" class="img-fluid mb-3" style="width: 100%">
                <div class="table-responsive">
                    @livewire('menu.cartelera')
                </div>
                <hr>
            </div>
            {{-- <div class="col-12 col-lg-2">
        </div> --}}
            <div class="container text-center mb-2">
                {{-- <a href="{{route('menusemanal')}}" class="myButton">Realizar Pedidos</a> --}}
            </div>
        </div>
    </div>
@endsection
{{-- @section('carrito')
@livewire('menu.carritologo')
@endsection --}}
