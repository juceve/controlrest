<div>
    @section('template_title')
    Venta Individual
    @endsection
    <div class="card">
        <div class="card-header bg-info text-white">
            <h2 class="h5">VENTA INDIVIDUAL</h2>
        </div>
        <div class="card-body">
            <div class="col-12 col-md-6 mb-3">
                <label class="form-label">Cliente</label>
                <div class="input-group mb-2">
                    <input type="text" class="form-control bg-white" placeholder="Seleccione un cliente"
                        wire:model='nombreCliente' readonly>
                    <button class="btn btn-info" type="button" data-bs-toggle="modal" data-bs-target="#estudiantes"
                        wire:click='resetAll'><i class="uil-search"></i> Buscar</button>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h2 class="h5">PRODUCTOS DISPONIBLES</h2>
                </div>
                <div class="card-body">
                    <div data-simplebar style="max-height: 250px;">
                        <div class="table-responsive" style="font-size: 12px;">
                            <table class="table table-bordered table-hover table-sm">
                                <thead class="table-primary">
                                    <tr>
                                        <th>FECHA</th>
                                        <th>PRODUCTO</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($menus as $menu)
                                    <tr>
                                        <td>{{$menu->fecha}}</td>
                                        <td>{{$menu->menu}}</td>
                                        <td align="right">
                                            <button class="text-white"
                                                style="background-color: #727cf5; border-color: #ffffff00; border-radius: 4%">Agregar
                                                <i class="uil-arrow-right"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-header bg-warning text-white text-center">
                    <h2 class="h5">EXTRAS</h2>
                </div>
                <div class="card-body">
                    <div data-simplebar style="max-height: 250px;">
                        <div class="table-responsive" style="font-size: 12px;">
                            <table class="table table-bordered table-hover table-sm">
                                <thead class="table-warning">
                                    <tr>
                                        <th>COD</th>
                                        <th>PRODUCTO</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($extras as $extra)
                                    <tr>
                                        <td>{{$extra->id}}</td>
                                        <td>{{$extra->nombre}}</td>
                                        <td align="right">
                                            <button class="text-white"
                                                style="background-color: #727cf5; border-color: #ffffff00; border-radius: 4%">Agregar
                                                <i class="uil-arrow-right"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white text-center">
                    <h2 class="h5">PEDIDO ACTUAL</h2>
                </div>
                <div class="card-body">


                    <div class="table-responsive" style="font-size: 12px;">
                        <table class="table table-sm table-hover">
                            <thead class="table-success">
                                <tr>
                                    <td></td>
                                    <td>FECHA</td>
                                    <td>PRODUCTO</td>
                                    <td align="right">CANT.</td>
                                    <td align="right">SUBTOTAL</td>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <a href="javascript:void(0)" class="text-danger">
                                            Quitar
                                        </a>
                                    </td>
                                    <td>12</td>
                                    <td>MENU 1</td>
                                    <td align="right">1</td>
                                    <td align="right">20</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="estudiantes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="estudiantesLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
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
                    <div class="table-responsive" style="font-size: 12px;">
                        <table class="table table-bordered table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>COD.</th>
                                    <th>NOMBRE</th>
                                    <th>CEDULA</th>
                                    <th>CURSO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!is_null($estudiantes))
                                @foreach ($estudiantes as $estudiante)
                                <tr>
                                    <td>{{$estudiante->codigo}}</td>
                                    <td>{{$estudiante->nombre}}</td>
                                    <td>{{$estudiante->cedula}}</td>
                                    <td>{{$estudiante->curso .' - '. $estudiante->nivel}}</td>
                                    <td align="right">
                                        <button class="btn btn-sm btn-success" data-bs-dismiss="modal"
                                            wire:click='seleccionarEstudiante({{$estudiante->id}})'><i
                                                class="uil-check"></i> Seleccionar</button>
                                    </td>
                                </tr>
                                @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>