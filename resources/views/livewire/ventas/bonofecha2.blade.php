<div>
    @section('template_title')
        Ventas - Rango de Fecha
    @endsection
    
    @if (cajaCerrada(Auth::user()->id, Auth::user()->sucursale_id))
        <div class="alert alert-warning" role="alert">
            <i class="dripicons-warning me-2"></i> <strong>Atención - </strong> La caja se encuentra
            cerrada.
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <h4 class="header-title mb-3">VENTA POR RANGO DE FECHA</h4>
            {{-- <form> --}}

            <div id="progressbarwizard" wire:ignore>
                <ul class="nav nav-pills nav-justified form-wizard-header mb-3">
                    <li class="nav-item">
                        <a href="#account-2" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                            <i class="mdi mdi-account-circle me-1"></i>
                            <span class="d-none d-sm-inline">1. Clientes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#products-tab-2" data-bs-toggle="tab" data-toggle="tab"
                            class="nav-link rounded-0 pt-2 pb-2">
                            <i class="mdi mdi-cookie me-1"></i>
                            <span class="d-none d-sm-inline">2. Productos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#finish-2" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                            <i class="mdi mdi-checkbox-marked-circle-outline me-1"></i>
                            <span class="d-none d-sm-inline">3. Forma Pago</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content b-0 mb-0">

                    <div id="bar" class="progress mb-3" style="height: 7px;">
                        <div class="bar progress-bar progress-bar-striped progress-bar-animated bg-success">
                        </div>
                    </div>
                    <hr>
                    <div class="tab-pane" id="account-2">
                        <h2 class="h4 text-primary">SELECCIÓN DE CLIENTES </h2>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">

                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#estudiantes"
                                        wire:click='resetAll'>Buscar Clientes <i class="uil-search"></i></button>

                                </div>
                                <div class="table-responsive mt-2" data-simplebar style="max-height: 300px;"
                                    wire:ignore.self>
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-primary">
                                            <tr>
                                                <td>CODIGO</td>
                                                <td>ESTUDIANTE</td>
                                                <td>TUTOR</td>
                                                <td>CURSO</td>
                                                <td>

                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody">
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="products-tab-2">
                        {{-- <h2 class="h4 text-primary">RANGO DE FECHA </h2> --}}

                        <h2 class="h4 text-primary">SELECCIÓN DE PRODUCTOS </h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped" style="font-size: 12px;"
                                id="tablaParametros">
                                <thead class="table-info">
                                    <tr class="text-center">
                                        <th>CLIENTE</th>
                                        <th>PRODUCTO</th>
                                        <th>FECHA INICIO</th>
                                        <th style="width: 100px;">CANT. ALMUERZOS</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyProductos">
                                    <tr>
                                        {{-- <td align="center" colspan="{{$eventos->count()+1}}">Seleccione
                                                Clientes!
                                            </td> --}}
                                    </tr>
                                </tbody>
                                <tfoot id="tbodyFooter" class="table-success">
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane" id="finish-2">
                        <h2 class="h4 text-primary">FORMA DE PAGO </h2>
                        <div class="row">
                            <div class="col-12 col-md-12" style="font-size: 12px;">
                                <div class="table-responsive" data-simplebar style="max-height: 350px;"
                                    style="vertical-align: middle; min-width: 100px;">
                                    <h2 class="h5">Clientes Seleccionados</h2>
                                    <table class="table table-bordered">
                                        <thead class="table-primary">
                                            <tr align="center">
                                                <th>ESTUDIANTE</th>
                                                <th>PRODUCTO</th>
                                                <th>FECHA INICIAL</th>
                                                <th>FECHA FINAL</th>
                                                <th>CANT. DIAS</th>
                                                <th>PRECIO</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyPagoClientes">

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            <hr>
                            <div class="col-12 col-md-6 border">
                                <h2 class="h5">Elija la Forma de Pago</h2>
                                <div class="mt-3">
                                    @foreach ($formapagos as $formapago)
                                        <div class="form-check mb-2">
                                            <input type="radio" id="rb{{ $formapago->id }}" name="rFormaPago"
                                                class="form-check-input" wire:model='cfp' value="{{ $formapago->id }}">
                                            <label class="form-check-label"
                                                for="rb{{ $formapago->id }}">{{ $formapago->nombre }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3" id="divComprobante">
                                    <label for="example-fileinput" class="form-label text-warning">Finalice la
                                        Transacción adjuntado un Comprobante.</label>
                                    <input type="file" id="example-fileinput" class="form-control mb-2"
                                        accept="image/*" wire:model="comprobante" onchange="preview_image(event)">


                                    <div class="mb-3">
                                        <img id="output_image" class="img-thumbnail" />
                                    </div>

                                </div>
                            </div>
                            <div class="col-12 col-md-6 border" style="font-size: 12px; vertical-align: middle">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" align="center">
                                                <h2 class="h5">Datos de la Transacción</h2>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align: middle">CLIENTE</td>
                                            <td><input type="text" class="form-control form-control-sm"
                                                    wire:model='tutor'></td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align: middle">OBSERVACIÓN</td>
                                            <td><input type="text" class="form-control form-control-sm"
                                                    value="" wire:model='observaciones'></td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align: middle">IMPORTE {{ $moneda->abreviatura }}</td>
                                            <td><input type="number" readonly step="0.00" min="0"
                                                    class="form-control bg-white" wire:model='importeTotal'></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="row mb-2">
                                    <div class="col-12 col-md-8"></div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-check text-end">
                                            <label class="form-check-label fs-4" for="switch1">Aplica descuento</label>
                                            <input type="checkbox" id="switch1" checked data-switch="bool"
                                                wire:model='condescuento' />
                                            <label for="switch1" data-on-label="SI" data-off-label="NO"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="finish text-center mb-3 d-grid gap-2">
                                    @if (!cajaCerrada(Auth::user()->id, Auth::user()->sucursale_id))
                                        <button class="btn btn-success" wire:click="registrarCompra"
                                            wire:loading.attr="disabled">Registrar Compra Bono <i
                                                class="uil-check-circle"></i></button>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <ul class="list-inline mb-0 wizard mt-3">
                        <li class="previous list-inline-item">
                            <a href="javascript:void(0)" class="btn btn-primary"><i class="uil-arrow-left"></i>
                                Regresar</a>
                        </li>
                        <li class="next list-inline-item float-end">
                            <a href="javascript:void(0)" class="btn btn-primary">Siguiente <i
                                    class="uil-arrow-right"></i></a>
                        </li>
                    </ul>


                </div>
            </div>

        </div>
    </div>

    {{-- MODAL --}}
    <div id="estudiantes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="estudiantesLabel"
        aria-hidden="true" wire:ignore.self data-bs-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-success">
                    <h4 class="modal-title" id="estudiantesLabel">Seleccione un cliente</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"
                        wire:click='selEstudiantes'></button>
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
                                                <td>{{ $estudiante->codigo }}</td>
                                                <td>{{ $estudiante->nombre }}</td>
                                                <td>{{ $estudiante->cedula }}</td>
                                                <td>{{ $estudiante->tutor }}</td>
                                                <td>{{ $estudiante->curso . ' - ' . $estudiante->nivel }}</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input type="checkbox" wire:model="checks"
                                                            id="cb{{ $estudiante->id }}" class="form-check-input"
                                                            value="{{ $estudiante->id }}">
                                                        <label class="form-check-label"
                                                            for="cb{{ $estudiante->id }}">Seleccionar</label>
                                                    </div>
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
                    <a href="#account-2" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click='selEstudiantes'>Cerrar</a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    {{-- ENDMODAL --}}
</div>

@section('js')
    <script src="{{ asset('assets/js/pages/demo.form-wizard.js') }}"></script>

    <script>
        // TAB CLIENTES
        Livewire.on('htmlEstudiantes', html => {
            $('#tbody').empty();
            $('#tbody').html(html);
        });

        function quitarE(i) {
            Livewire.emit('quitarE', i);
        }
        // FIN TAB CLIENTES
    </script>
    <script>
        // TAB PRODUCTOS
        Livewire.on('htmlProductos', html => {
            $('#tbodyProductos').empty();
            $('#tbodyProductos').html(html);
        });



        function selProducto(estudiante_id, tipomenu_id) {
            Livewire.emit('selectProducto', estudiante_id, tipomenu_id);
        }

        function calculaPrecio(estudiante_id, precio) {
            Livewire.on('calculaPrecio', estudiante_id, precio);
        }


        Livewire.on('muestraPedidos', arra => {
            console.log(arra);
        })

        Livewire.on('comprobante', msg => {
            if (msg == 'show') {
                $('#divComprobante').show();
            } else {
                $('#divComprobante').hide();
            }

        })

        Livewire.on('error', msg => {
            Swal.fire('Error', 'Ha sucedido un error', 'error');
        });
        // FIN TAB PRODUCTOS

        //TAB FORMA PAGOS

        Livewire.on('htmlPagoClientes', html => {

            $('#tbodyPagoClientes').html(html);
            $.unblockUI();
        });

        // FIN TAB FORMA PAGOS
    </script>

    <script>
        $(document).ready(function() {
            $('.finish').hide();
            $('#divComprobante').hide();
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                var activeTabPaneId = $(e.target).attr('href');

                var isActiveTabPane = $(activeTabPaneId).is(':visible');

                if (isActiveTabPane) {
                    if (activeTabPaneId == "#finish-2") {
                        generaPedido();
                        $('.next').hide();
                        $('.finish').show();
                        $('#tbodyPagoClientes').empty();
                        Livewire.emit('generaPagosXEstudiante');                
                    } else {
                        $('.next').show();
                        $('.finish').hide();
                    }
                }
            });
        });

        function generaPedido() {
            var tabla = $("#tablaParametros");

            Livewire.emit('resetPedidos');

            tabla.find("tbody tr").each(function() {
                var estudiante_id = $(this).find("td:eq(0) input").val();
                var tipomenu_id = $(this).find("td:eq(1) select").val();
                var fechaI = $(this).find("td:eq(2) input").val();
                var cantidadDias = $(this).find("td:eq(3) input").val();
                Livewire.emit('cargaPedidos',estudiante_id, tipomenu_id, fechaI, cantidadDias)
            });
        }
    </script>
    @if (session('finishTransaction'))
        <script>
            Swal.fire("Pedido Registrado!", '{{ session('finishTransaction') }}', 'success');
        </script>
    @endif

    <script>
        Livewire.on('imprimir',data=>{  
            window.open("/impresiones/recibos/bonofecha.php?data="+data, "_blank");
        })
    
    </script>
@endsection
