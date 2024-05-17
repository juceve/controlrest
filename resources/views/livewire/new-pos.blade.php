<div>
    @section('template_title')
    Punto de Venta 2
    @endsection
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">PUNTO DE VENTA</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-7">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <strong>SELECCIÓN DE PRODUCTOS</strong>
                </div>
                <div class="card-body" style="height:500px;">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-almuerzos-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-almuerzos" type="button" role="tab" aria-controls="nav-almuerzos"
                                aria-selected="true"><strong>ALMUERZOS</strong></button>
                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                aria-selected="false">Profile</button>
                            <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                aria-selected="false">Contact</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-almuerzos" role="tabpanel"
                            aria-labelledby="nav-almuerzos-tab">
                            <div class="row mt-3">

                                @if ($productos)

                                @foreach ($productos as $item)
                                <div class="col-6 col-md-6 d-grid mb-3">
                                    <button class="btn btn-success" style="height: 100px;"
                                        wire:click="$set('itemSel', {{$item[0]}})" data-bs-toggle="modal"
                                        data-bs-target="#modalCantidad" onclick="selItem('{{$item[1]}}')">
                                        <div class="row">
                                            <div class="col-3">
                                                <img src="{{asset('img/comida.png')}}" class="img-fluid">
                                            </div>
                                            <div class="col-9 text-start">
                                                <span><strong>{{$item[1]}}</strong></span>
                                                <br>
                                                <span>Precio Bs: {{$item[2]}}</span>

                                            </div>
                                        </div>

                                    </button>
                                </div>
                                @endforeach
                                @endif



                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            ...</div>
                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                            ...</div>
                    </div>
                    {{-- {{var_export($itemSel)}} --}}

                </div>
            </div>
        </div>
        <div class="col-12 col-md-5">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <strong>PEDIDO ACTUAL</strong>
                </div>
                <div class="card-body" style="min-height:500px;">
                    @if (count($cart))
                    <div class="table-responsive" style="max-height: 350px;">
                        <table class="table table-bordered table-sm table-hover" style="vertical-align: middle; ">
                            <thead class="table-primary">
                                <tr class="text-uppercase text-center fw-bold">

                                    <td align="left">DETALLE</td>
                                    <td>CANT.</td>
                                    <td>IMPORTE</td>
                                    <td style="width: 15px"></td>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i=0;
                                @endphp
                                @foreach ($cart as $item)

                                <tr class="text-center">

                                    <td align="left">

                                        <strong>{{$item[1]}} </strong><br>
                                        <small>Precio:
                                            @if ($descuento)
                                            {{$item[4]}}
                                            @else
                                            {{$item[2]}}
                                            @endif
                                        </small>
                                    </td>
                                    <td>{{$item[3]}}</td>
                                    <td>
                                        @if ($descuento)
                                        {{number_format(($item[4] * $item[3]),2,'.')}}
                                        @else
                                        {{number_format(($item[2] * $item[3]),2,'.')}}
                                        @endif

                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-warning" title="Eliminar"
                                            wire:click='eliminar({{$i}})' wire:loading.attr="disabled">

                                            <div>
                                                <i class="fas fa-trash"></i>
                                            </div>
                                        </button>
                                    </td>
                                </tr>
                                @php
                                $i++
                                @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <hr>

                    <table class="table" style="vertical-align: middle">

                        <tr>
                            <td class="fs-4 fw-bold" align="right">TOTAL BS.</td>
                            <td><span class="form-control fs-4 fw-bold text-end">{{number_format($total,2,'.')}}</span>
                            </td>
                        </tr>

                    </table>
                    <div class="form-group mb-2">
                        <div wire:loading wire:target="descuento" class="fs-4">
                            <div class="spinner-border spinner-border-sm text-warning" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span class="text-warning">Aplicando...</span>
                        </div>
                        <div wire:loading.remove wire:target="descuento">
                            <div class="form-check form-switch fs-4">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Aplicar
                                    Descuento</label>
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault"
                                    wire:model='descuento'>

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <a href="{{route('pruebas')}}" class="btn btn-secondary fs-3"><i class="fas fa-ban"></i>
                            Cancelar</a>
                        <button class="btn btn-primary fs-3">
                            Procesar <i class="fas fa-dollar"></i>
                        </button>
                    </div>
                    @else
                    <div class="alert alert-secondary" role="alert">
                        No cuenta con elementos adicionados...
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalCantidad" data-bs-backdrop="static" tabindex="-1"
        aria-labelledby="modalCantidadLabel" aria-hidden="true" wire:ignore>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title" id="modalCantidadLabel"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="resetCantidad()" title="Cerrar"></button>
                </div>
                <div class="modal-body ">
                    <h4 class="text-center mb-2" id="nombreItem">Cantidad:</h3>
                        <div style="width: 200px; margin-left: auto;margin-right: auto">
                            <div class="form-group d-flex justify-content-center">
                                <button class="btn btn-info fs-3" onclick='addCantidad()'>
                                    <i class="fas fa-plus"></i>
                                </button>

                                <input type="number" class="form-control text-center" id="cantidad" value="1"
                                    style="font-size: 30px" style="width: 20%">

                                <button class="btn btn-info fs-3" onclick='remCantidad()'>
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-grid mt-3">
                            <button type="button" class="btn btn-primary fs-3" wire:click='addCart'
                                onclick="resetCantidad()" data-bs-dismiss="modal">Agregar <i
                                    class="fas fa-cart-plus"></i></button>
                        </div>
                </div>

            </div>
        </div>
    </div>
</div>
@section('js')
<script>
    function selItem(nombre){
        $('#modalCantidadLabel').html(nombre);
    }
    function addCantidad(){
        var cantidad = $('#cantidad').val();
        cantidad++;
        @this.set('cantidad',cantidad);
        $('#cantidad').val(cantidad);
    }

    function remCantidad(){
        
        var cantidad = $('#cantidad').val();
        if(cantidad > 1){
           cantidad--;
        @this.set('cantidad',cantidad);
        $('#cantidad').val(cantidad); 
        }      
    
    }

    function resetCantidad(){
        $('#cantidad').val('1'); 
    }
</script>
@endsection