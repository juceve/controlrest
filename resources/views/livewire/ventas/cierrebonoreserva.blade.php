<div>
    @section('template_title')
        Cierres de Caja
    @endsection
    <div class="card">
        <div class="card-header bg-info text-white">
            CIERRES DE CAJA BONOS Y RESERVAS
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for=""><strong>USUARIO</strong></label>
                        <p class="form-control">{{ Auth::user()->name }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for=""><strong>SUCURSAL</strong></label>
                        <p class="form-control">{{ Auth::user()->sucursale ? Auth::user()->sucursale->nombre : '' }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 ">
                    @if (cajaCerrada(Auth::user()->id, Auth::user()->sucursale_id))
                        <div class="alert alert-warning" role="alert">
                            <i class="dripicons-warning me-2"></i> <strong>Atención - </strong> La caja se encuentra
                            cerrada.
                        </div>
                    @else
                        <div class="row">
                            <div class="col-12 col-md-6 d-grid">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalData">
                                    Operaciones del Día
                                </button>
                            </div>
                            <div class="col-12 col-md-6 d-grid">
                                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalCierre">
                                    Cierre de Caja
                                </button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
            <hr>
            <table class="table table-bordered table-hover table-sm dataTable" style="vertical-align: middle">
                <thead class="table-success">
                    <tr class="text-center">
                        <th>ID</th>
                        <th>FECHA</th>
                        <th>SUCURSAL</th>
                        <th>REPORTES</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($cierres)
                        @foreach ($cierres as $cierre)
                            <tr>
                                <td align="center">{{ $cierre->id }}</td>
                                <td>{{ $cierre->fecha }}</td>
                                <td>{{ $cierre->sucursale->nombre }}</td>
                                <td align="right">
                                    <button class="btn btn-info btn-sm" title="Reimprimir Comprobante"
                                        wire:click='pdf({{ $cierre->id }})'><i class="uil-print"></i>
                                        Cierre</button>
                                    <button class="btn btn-primary btn-sm" title="Reimprimir Comprobante"
                                        wire:click='ventasPDF({{ $cierre->id }})'><i class="uil-print"></i>
                                        Ventas</button>
                                    {{-- <button class="btn btn-success btn-sm" title="Reporte de Ventas"
                                        wire:click='ventasPDF({{ $cierre->id }})'><i class="uil-usd-square"></i>
                                        Ventas</button> --}}
                                </td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modalCierre" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h4 class="modal-title" id="myLargeModalLabel">Cierre de Caja</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-2"><strong>OPERADOR:</strong></div>
                        <div class="col-12 col-md-3">{{ Auth::user()->name }}</div>
                    </div>
                    <div class="row mt-1 mb-2">
                        <div class="col-12 col-md-2"><strong>FECHA:</strong></div>
                        <div class="col-12 col-md-3">{{ date('Y-m-d') }}</div>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="table-info">
                                <tr align="center">
                                    <th>Nro.</th>
                                    <th>TIPO PAGO</th>
                                    <th>CANTIDAD</th>
                                    <th>IMPORTE BS.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                    $total = 0;
                                @endphp
                                @foreach ($montosHOY as $item)
                                    <tr>
                                        <td align="center">{{ $i }}</td>
                                        <td>{{ $item->nombre }}</td>
                                        <td align="center">{{ $item->cantidad }}</td>
                                        <td align="right">{{ number_format($item->total,2,'.',',') }}</td>
                                    </tr>
                                    @php
                                        $i++;
                                        $total = $total + $item->total;
                                    @endphp
                                @endforeach

                                <tr class="table-info">
                                    <td colspan="2"></td>
                                    <td align="right"><strong>TOTAL</strong></td>
                                    <td align="right"><strong>{{ number_format($total, 2, '.', ',') }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td align="right"><strong>EN CAJA:</strong></td>
                                    <td><input type="number" step="0.00" min="0"
                                            class="form-control form-control-sm text-end" id="encaja"
                                            wire:model='encaja'>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td align="right"><strong>DIFERENCIA:</strong></td>
                                    <td><input type="number" readonly step="0.00" min="0"
                                            class="form-control form-control-sm text-end bg-white" id="faltante"
                                            wire:model='faltante'></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    @if (!cajaCerrada(Auth::user()->id, Auth::user()->sucursale_id))
                        <button type="button" class="btn btn-info" onclick="registrar()">GENERAR CIERRE DE
                            CAJA</button>
                    @endif
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    @php
        $i = 1;
        $ttotal1 = 0;
    @endphp

    <div wire:ignore.self id="modalData" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="info-header-modalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="info-header-modalLabel">Operaciones Realizadas</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    @if (cajaCerrada(Auth::user()->id, Auth::user()->sucursale_id))
                        <div class="alert alert-warning" role="alert">
                            <i class="dripicons-warning me-2"></i> <strong>Atención - </strong> La caja se encuentra
                            cerrada.
                        </div>
                    @else
                        <div class="row">
                            <div class="col-12 col-md-2"><strong>OPERADOR:</strong></div>
                            <div class="col-12 col-md-3">{{ Auth::user()->name }}</div>
                        </div>
                        <div class="row mt-1 mb-2">
                            <div class="col-12 col-md-2"><strong>FECHA:</strong></div>
                            <div class="col-12 col-md-3">{{ date('Y-m-d') }}</div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-striped "
                                style="vertical-align: middle;font-size: 12px;">
                                <thead class="table-success">
                                    <tr align="center">
                                        <th>NRO</th>
                                        <th>ITEM</th>
                                        <th>TIPO PAGO</th>
                                        <th>DESCUENTO</th>
                                        <th>CANTIDAD</th>
                                        <th style="width: 100px;">SUBTOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($ingresosHOY)

                                        @foreach ($ingresosHOY as $ingreso)
                                            <tr>
                                                <td align="center">{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}</td>
                                                <td>{{ $ingreso->tipo }}</td>
                                                <td align="center">{{ $ingreso->tipopago }}</td>
                                                <td align="center">{{ $ingreso->descuento }}</td>
                                                <td align="center">{{ $ingreso->cantidad }}</td>
                                                <td align="right">{{ number_format($ingreso->subtotal, 2, '.', ',') }}
                                                </td>

                                            </tr>
                                            @php
                                                $ttotal1 += $ingreso->subtotal;
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endif
                                    <tr class="table-primary">
                                        <td colspan="5" align="right"><strong>TOTAL:</strong></td>
                                        <td align="right">{{ number_format($ttotal1, 2, '.', ',') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        @if ($montosHOY)
                        @foreach ($montosHOY as $item)
                             <div class="row">
                                <div class="col-12 col-md-5"><strong>{{$item->nombre}}</strong></div>
                                <div class="col-12 col-md-3">{{ number_format($item->total, 2, '.', ',') }}</div>
                            </div>
                        @endforeach
                        <div class="row border">
                            <div class="col-12 col-md-5"><strong>TOTAL BS.:</strong></div>
                            <div class="col-12 col-md-3"><strong>{{ number_format($ttotal1, 2, '.', ',') }}</strong></div>
                        </div>
                        @endif
                    @endif


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CERRAR</button>

                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
    <script>
        function registrar() {
            $('#modalData').modal('hide');
            Swal.fire({
                title: 'REGISTRAR CIERRE DE CAJA',
                text: "Está seguro de realizar esta operación?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {

                    Livewire.emit('cerrarCaja');
                }
            });
        }
    </script>
@endsection
