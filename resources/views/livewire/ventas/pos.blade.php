<div>
    @section('template_title')
        Punto de Venta
    @endsection
    @if (cajaCerradaPOS(Auth::user()->id, Auth::user()->sucursale_id))
        <div class="alert alert-warning" role="alert">
            <i class="dripicons-warning me-2"></i> <strong>Atención - </strong> La caja se encuentra
            cerrada.
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">PUNTO DE VENTA</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12" wire:ignore>
            <div class="card">
                <div class="card-header bg-primary text-white">
                    PRODUCTOS DISPONIBLES
                </div>
                <div class="card-body">
                    <div class="content table-responsive" data-simplebar>
                        <table class="table table-bordered table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <td class="text-secondary" align="center"><strong>COD.</strong></td>
                                    <td class="text-secondary" align="center"><strong>PRODUCTO</strong></td>
                                    <td class="text-secondary" align="center"><strong>CANTIDAD</strong></td>
                                    <td class="text-secondary" align="center"><strong>SUBTOTAL</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!is_null($productos))
                                    @if (count($productos) > 0)
                                        @foreach ($productos as $producto)
                                            <tr style="vertical-align: middle">

                                                <td style="width: 50px;" align="center">{{ $producto[0] }}</td>

                                                <td>
                                                    {{ $producto[1] }} <br>
                                                    <small class="me-2 mt-2"><strong>Detalle:
                                                        </strong>{{ $producto[6] }}</small>
                                                </td>

                                                <td align="center" style="width: 100px;">
                                                    <input type="number"
                                                        style="text-align: center; width:70px; float: center"
                                                        class="form-control bg-white cantidades" min="0"
                                                        value="{{ $producto[3] }}"
                                                        onchange="calcular({{ $producto[5] }},this.value);">
                                                </td>

                                                <td style="width: 70px;"><input type="text"
                                                        id="st{{ $producto[5] }}"
                                                        style="width: 70px; text-align: right"
                                                        class="form-control bg-white" readonly min="0"
                                                        step="0.00" value="{{ $producto[4] }}" class="form-control">
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endif
                                @endif



                            </tbody>
                        </table>
                    </div>
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
                    <div class="row">
                        <div class="col-8 col-md-9 text-end mt-2">
                            <p class="fs-3">Total Bs.</p>
                        </div>
                        <div class="col-4 col-md-3 ">
                            <input type="text" class="form-control fs-3 text-end bg-white" id="itotal"
                                wire:model='total' readonly>
                        </div>
                        @if (!is_null($productos))
                            @if (count($productos) > 0)
                                <div class="col-8 col-md-9 text-end mt-0">
                                    <p class="fs-3">Forma Pago:</p>
                                </div>
                                <div class="col-4 col-md-3 ">
                                    <select class="form-select" wire:model="selTipo">
                                        @foreach ($tipopagos as $tipo)
                                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6 col-md-3 d-grid">
                                    <a href="{{ route('ventas.pos') }}" class="btn btn-secondary mt-2 fs-3"><i
                                            class="uil-ban"></i> Cancelar</a>
                                </div>
                                <div class="col-6 col-md-3 d-grid">
                                    @if (!cajaCerradaPOS(Auth::user()->id, Auth::user()->sucursale_id))
                                        <button class="btn btn-success mt-2 fs-3" wire:click='registrar'><i
                                                class="uil-money-withdrawal"></i> Procesar </button>
                                    @endif

                                </div>
                                <div class="col-12 col-md-6 mt-1">
                                    <div class="row">
                                        <div class="col-6 text-end mt-2">
                                            <p class="fs-3 text-success">Efectivo: </p>
                                        </div>
                                        <div class="col-6">
                                            <input type="number" id="efectivo" step="0.00"
                                                onkeyup="calcularVuelto()" class="form-control text-end fs-3">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 d-grid">
                                </div>
                                <div class="col-6 col-md-3 d-grid">
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="row">
                                        <div class="col-6 text-end mt-1">
                                            <p class="fs-3 text-warning">Cambio: </p>
                                        </div>
                                        <div class="col-6">
                                            <input type="number" id="vuelto"
                                                class="form-control bg-white text-end fs-3" readonly>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@section('js')
    @if ($indicador)
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Venta registrada correctamente.',
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @endif
    <script>
        function calcularVuelto() {
            var total = $('#itotal').val();
            var efectivo = $('#efectivo').val();
            if (efectivo == "" || efectivo == 0) {
                $('#vuelto').val("");
            } else {
                var vuelto = (efectivo - total);
                $('#vuelto').val(vuelto);
            }

        }
    </script>
    <script>
        function calcular(i, cantidad) {
            if ($('.cantidades').val() == "") {
                $('.cantidades').val("0");
            } else {
                Livewire.emit('calcular', i, cantidad);
            }
        };
        Livewire.on('subtotal', arr => {
            $('#st' + arr[0]).val(arr[1]);
        })

        Livewire.on('borar', msg => {
            $('#vuelto').val("");
            $('#efectivo').val("");
        });
    </script>
@endsection
