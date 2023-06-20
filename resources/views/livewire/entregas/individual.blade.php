<div>
    @section('template_title')
    Entregas Individuales
    @endsection
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">ENTREGAS INDIVIDUALES</h4>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h2 class="h4 text-primary">SELECCIÓN DE CLIENTES </h2>
            <div class="row">
                <div class="col-12 col-md-5">
                    <div class="mb-2">
                        <div class="input-group">
                            <input type="search" id="codigo" style="text-transform: uppercase;"
                                wire:model.debounce.1000ms='buscaCodigo' class="form-control"
                                placeholder="Buscar por Codigo" aria-label="Recipient's username">
                            <button class="btn btn-primary" type="button"><i class="uil-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-5">
                    <div class="form-group d-grid gap-2">
                        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#estudiantes"
                            wire:click='resetAll'>Busqueda Avanzada <i class="uil-search-plus"></i></button>
                    </div>
                </div>
                <div class="col-12">
                    <div class="table-responsive mt-2" data-simplebar style="max-height: 300px;">
                        <table class="table table-bordered table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <td>CODIGO</td>
                                    <td>ESTUDIANTE</td>
                                    <td>TUTOR</td>
                                    <td>CURSO</td>

                                </tr>
                            </thead>
                            <tbody id="tbody">
                                @if (!is_null($estudiante))
                                <tr>
                                    <td>{{$estudiante->codigo}}</td>
                                    <td>{{$estudiante->nombre}}</td>
                                    <td>{{$estudiante->tutore->nombre}}</td>
                                    <td>{{$estudiante->curso->nombre}}</td>
                                </tr>
                                @endif
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($licencia)
    <div class="card">
        <div class="card-body">
            <div class="alert alert-warning text-center" role="alert">
                <strong>ESTUDIANTE CON LICENCIA PARA HOY!</strong>
            </div>
        </div>
    </div>
    @endif
    @if (!is_null($bonoanual))
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5>PRODUCTOS DISPONIBLES PARA HOY</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered" style="width: 100%; vertical-align: middle">
                <tbody>
                    @if ($detalle)
                    @foreach ($detalle as $item)
                    <tr>
                        <td>
                            <div class="form-group">
                                <h2 class="h4">{{$item->tipo}}</h2>
                                <span>{{$item->menu}}</span>
                            </div>
                        </td>
                        <td align="right">
                            @if (count($entregas) == 0)
                            <button class="btn btn-primary" style="font-size: 16px;"
                                wire:click="entregaProducto({{0}},{{$bonoanual->venta_id}},{{$item->menu_id}})">Entregar
                                <i class="uil-arrow-circle-right"></i></button>
                            @else
                            <span class="text-primary"><strong>Producto Entregado</strong></span><br>
                            <small class="text-warning">{{$entregas[0]->fechaentrega}}</small>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif

                </tbody>
            </table>
        </div>
    </div>
    @endif
    @if (!is_null($bonofecha))
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5>PRODUCTOS DISPONIBLES PARA HOY</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered" style="width: 100%; vertical-align: middle">
                <tbody>
                    @if ($detalle)
                    @foreach ($detalle as $item)
                    <tr>
                        <td>
                            <div class="form-group">
                                <h2 class="h4">{{$item->tipo}}</h2>
                                <span>{{$item->menu}}</span>
                            </div>
                        </td>
                        <td align="right">
                            @if (count($entregas) == 0)
                            <button class="btn btn-primary" style="font-size: 16px;"
                                wire:click="entregaProducto({{0}},{{$bonofecha->venta_id}}, {{$item->menu_id}})">Entregar
                                <i class="uil-arrow-circle-right"></i></button>
                            @else
                            <span class="text-primary"><strong>Producto Entregado</strong></span><br>
                            <small class="text-warning">{{$entregas[0]->fechaentrega}}</small>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif

                </tbody>
            </table>
        </div>
    </div>
    @endif
    @if (!is_null($productos) && count($productos)>0)
    
    <div class="card">
        {{-- @dd($productos) --}}
        <div class="card-header bg-success text-white">
            <h5>PRODUCTOS DISPONIBLES PARA HOY</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered" style="width: 100%; vertical-align: middle">
                <tbody>
                    @foreach ($productos as $producto)
                    <tr>
                        <td>
                            <div class="form-group">
                                <h2 class="h4">{{$producto->tipomenu}} - {{$producto->menu}}</h2>
                                {{-- <span>{{$menudeldia->descripcion}}</span> --}}
                            </div>
                        </td>
                        <td align="right">
                            @if ($producto->entregado == 0)
                            <button class="btn btn-primary" style="font-size: 16px;"
                                wire:click="entregaProducto({{$producto->detalle_id}},{{$producto->venta_id}},{{$producto->menu_id}})">Entregar
                                <i class="uil-arrow-circle-right"></i></button>
                            @else
                            <span class="text-primary"><strong>Producto Entregado</strong></span><br>
                            <small class="text-warning">{{$producto->fechaentrega}}</small>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    @endif



    {{-- MODAL --}}
    <div id="estudiantes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="estudiantesLabel"
        aria-hidden="true" wire:ignore.self data-bs-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-success">
                    <h4 class="modal-title" id="estudiantesLabel">Seleccione un cliente</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Busqueda</label>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="basic-addon1"><i class="uil-search"></i></span>
                            <input type="search" class="form-control"
                                placeholder="Busqueda por nombre, codigo, cedula, curso"
                                wire:model.debounce.500ms='busqueda'>
                        </div>
                    </div>
                    <div class="table-responsive" data-simplebar style="max-height: 250px;">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="table-primary">
                                <tr>
                                    <th>COD.</th>
                                    <th>NOMBRE</th>
                                    <th>CEDULA</th>
                                    <th>TUTOR</th>
                                    <th>CURSO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!is_null($estudiantes))
                                @if (count($estudiantes) > 0)
                                @foreach ($estudiantes as $estudiante)
                                <tr style="vertical-align: middle">
                                    <td>{{$estudiante->codigo}}</td>
                                    <td>{{$estudiante->nombre}}</td>
                                    <td>{{$estudiante->cedula}}</td>
                                    <td>{{$estudiante->tutor}}</td>
                                    <td>{{$estudiante->curso .' - '. $estudiante->nivel}}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" title="Seleccionar Estudiante"
                                            data-bs-dismiss="modal"
                                            wire:click="seleccionaEstudiante({{$estudiante->id}})"><i
                                                class="uil-check"></i> </button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6" align="center">No se encontraron registros</td>
                                </tr>
                                @endif
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#account-2" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    {{-- ENDMODAL --}}
</div>
@section('js')
@if ($indicador)
<script>
    Swal.fire("Excelente!", 'Entrega registrada correctamente.','success');
</script>
@endif
@endsection