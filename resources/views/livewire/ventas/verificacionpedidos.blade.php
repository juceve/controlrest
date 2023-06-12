<div>
    @section('template_title')
    Pagos Pendientes
    @endsection
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">PAGOS PENDIENTES</h4>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-primary text-white">
            PEDIDOS CON PAGOS PENDIENTES
        </div>
        <div class="card-body ">
                <label for=""><strong>Filtrar por tipo de pago:</strong> </label><br><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" wire:model="filtroBusqueda" id="rTodos" value="">
                    <label class="form-check-label" for="rTodos">Todos</label>
                </div>
                @if ($tipopagos->count() > 0)
                @foreach ($tipopagos as $tipo)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" wire:model="filtroBusqueda" id="r{{$tipo->id}}"
                        value="{{$tipo->id}}" title="{{$tipo->nombre}}">
                    <label class="form-check-label" for="r{{$tipo->id}}" title="{{$tipo->nombre}}">
                        <div class="d-none d-md-block">
                            {{$tipo->nombre}}
                        </div>
                        <div class="d-block d-md-none">
                            {{$tipo->abreviatura}}
                        </div>

                    </label>
                </div>
                @endforeach

                @endif
            <hr>
            <div class="row mb-2">
                <div class="col-12 col-md-1">
                    <label class="mt-2">Buscar:</label>
                </div>
                <div class="col-12 col-md-4">
                    <input type="search" wire:model="busqueda" class="form-control" placeholder="Nombre del cliente">
                </div>

            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered" style="vertical-align: middle">
                    <thead class="thead">
                        <tr>
                            <th>No</th>

                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Tipo Pago</th>
                            <th>Precio Bs.</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i=0;
                        @endphp
                        @if ($ventas->count() > 0)
                        @foreach ($ventas as $venta)
                        <tr>
                            <td>{{ ++$i }}</td>

                            <td>{{ $venta->fecha }}</td>
                            <td>{{ $venta->cliente }}</td>
                            <td>{{ $venta->tipopago_id?$venta->tipopago->abreviatura:"" }}</td>
                            <td><strong>{{ $venta->importe }}</strong> </td>

                            <td align="right">
                                @can('ventas.appedido')
                                    
                                   <a class="btn btn-info" 
                                    href="{{ route('ventas.appedido',$venta->id) }}"><i class="uil-eye"></i>
                                    Verificar</a> 
                                @endcan
                                

                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td class="text-center" colspan="6">
                                <span >No se encontraron registros!</span>
                            </td>
                        </tr>
                        @endif

                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $ventas->links() }}
            </div>
        </div>
    </div>
</div>