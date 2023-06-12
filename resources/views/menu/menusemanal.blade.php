@extends('layouts.web2')
@livewireStyles
@section('content')
<div class="container-fluid text-center py-3 text-warning" style="background-color: #10620093">
  <h5>MENU SEMANAL</h5>
</div>
<div class="mt-3">
  @livewire('menu.pedido')
</div>
@endsection

@livewireScripts