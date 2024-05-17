<div>
    @section('template_title')
    Cierres POS
    @endsection
    <div class="card">
        <div class="card-header bg-success text-white">
            CIERRES DE CAJA PUNTO DE VENTA
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
                    @if (cajaCerradaPOS(Auth::user()->id, Auth::user()->sucursale_id))
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
                        <th>OPERADOR</th>
                        @if (Auth::user()->roles[0]->name == "Admin" || Auth::user()->roles[0]->name == "SUPERVISOR")
                        <th>REGENERAR</th>
                        @endif

                        <th>REPORTES</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($cierres)
                    @foreach ($cierres as $cierre)
                    <tr>
                        <td align="center">{{ $cierre->id }}</td>
                        <td align="center">{{ $cierre->fecha }}</td>
                        <td>{{ $cierre->sucursale->nombre }}</td>
                        <td>{{ $cierre->user->name }}</td>
                        @if (Auth::user()->roles[0]->name == "Admin" || Auth::user()->roles[0]->name == "SUPERVISOR")
                        <td align="right">
                            <button class="btn btn-outline-danger btn-sm" wire:click='regenerar({{$cierre->id}})'>
                                Regenerar
                            </button>
                        </td>
                        @endif

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

    @php
    $i = 1;
    $totalEfectivo = 0;
    $totalQr = 0;
    $totalTr = 0;
    $totalGa = 0;
    @endphp

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
                                    <th>TIPO PAGOCANTIDAD</th>
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
                                    <td>{{ $item->tipopago }}</td>
                                    <td align="center">{{ $item->cantidad }}</td>
                                    <td align="right">{{ $item->importe }}</td>
                                </tr>
                                @php
                                $i++;
                                $total = $total + $item->importe;
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
                                    <td align="right"><strong>FALTANTE:</strong></td>
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
                    @if (!cajaCerradaPOS(Auth::user()->id, Auth::user()->sucursale_id))
                    <button type="button" class="btn btn-info" onclick="registrar()">GENERAR CIERRE DE
                        CAJA</button>
                    @endif
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div wire:ignore.self id="modalData" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="info-header-modalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="info-header-modalLabel">Operaciones Realizadas</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    @if (cajaCerradaPOS(Auth::user()->id, Auth::user()->sucursale_id))
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
                        <table class="table table-sm table-bordered table-hover "
                            style="vertical-align: middle;font-size: 12px;">
                            <thead class="table-success">
                                <tr align="center">
                                    <th>NRO</th>
                                    <th>ITEM</th>
                                    <th>TIPO PAGO</th>
                                    <th>DESCUENTO</th>
                                    <th>CANTIDAD</th>
                                    <th style="width: 100px;">PRECIO UNIT.</th>
                                    <th style="width: 100px;">SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($ingresosHOY)

                                @foreach ($ingresosHOY as $ingreso)
                                <tr>
                                    <td align="center">{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $ingreso->descripcion }}</td>
                                    <td align="center">{{ $ingreso->abreviatura }}</td>
                                    <td align="center">{{ $ingreso->descuento }}</td>
                                    <td align="center">{{ $ingreso->cantidad }}</td>
                                    <td align="right">
                                        {{ number_format($ingreso->preciounitario, 2, '.', ',') }}</td>
                                    <td align="right">
                                        {{ number_format($ingreso->subtotal, 2, '.', ',') }}
                                    </td>

                                </tr>
                                @php
                                switch ($ingreso->abreviatura) {
                                case 'EF':
                                $totalEfectivo = $totalEfectivo + $ingreso->subtotal;
                                break;
                                case 'QR':
                                $totalQr = $totalQr + $ingreso->subtotal;
                                break;
                                case 'TR':
                                $totalTr = $totalTr + $ingreso->subtotal;
                                break;
                                case 'GA':
                                $totalGa = $totalGa + $ingreso->subtotal;
                                break;
                                }
                                $i++;
                                @endphp
                                @endforeach
                                @endif
                                <tr class="table-success">
                                    <td colspan="6" align="right"><strong>TOTAL:</strong></td>
                                    <td align="right">{{ number_format($totalpr, 2, '.', ',') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    @if ($totalEfectivo)
                    <div class="row">
                        <div class="col-12 col-md-3"><strong>TOTAL BS. EFECTIVO:</strong></div>
                        <div class="col-12 col-md-3">{{ number_format($totalEfectivo, 2, '.', ',') }}</div>
                    </div>
                    @endif
                    @if ($totalQr)
                    <div class="row">
                        <div class="col-12 col-md-3"><strong>TOTAL BS. PAGO QR:</strong></div>
                        <div class="col-12 col-md-3">{{ number_format($totalQr, 2, '.', ',') }}</div>
                    </div>
                    @endif
                    @if ($totalTr)
                    <div class="row">
                        <div class="col-12 col-md-3"><strong>TOTAL BS. TRANSFER.:</strong></div>
                        <div class="col-12 col-md-3">{{ number_format($totalTr, 2, '.', ',') }}</div>
                    </div>
                    @endif
                    @if ($totalGa)
                    <div class="row">
                        <div class="col-12 col-md-3"><strong>TOTAL BS. GASTOS ADM.:</strong></div>
                        <div class="col-12 col-md-3">{{ number_format($totalGa, 2, '.', ',') }}</div>
                    </div>
                    @endif
                    @endif


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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