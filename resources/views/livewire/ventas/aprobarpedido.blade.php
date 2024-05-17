<div>
    @section('template_title')
        Aprobaciones
    @endsection
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">VENTA SIN PAGO REGISTRADO</h4>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-7 mb-3">
                    <label for="">INFORMACIÓN DE LA VENTA</label>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-hover" style="width: 100%; vertical-align: middle"
                            style="scr">
                            <tbody>
                                <tr>
                                    <td style="width: 50px">
                                        <strong>ID</strong>
                                    </td>
                                    <td>
                                        <a href="{{ route('ventas.show', $venta->id) }}" target="_blank" rel="noopener"
                                            class="btn btn-link btn-sm" title="Mas Info">{{ $venta->id }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50px">
                                        <strong>FECHA</strong>
                                    </td>
                                    <td>
                                        {{ $venta->fecha }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50px">
                                        <strong>CLIENTE</strong>
                                    </td>
                                    <td>
                                        {{ $venta->cliente }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50px">
                                        <strong>ESTADO PAGO</strong>
                                    </td>
                                    <td>
                                        {{ $venta->estadopago->nombre }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50px">
                                        <strong>IMPORTE</strong>
                                    </td>
                                    <td>
                                        {{ $venta->importe }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50px">
                                        <strong>TIPO PAGO</strong>
                                    </td>
                                    <td>
                                        {{ $venta->tipopago->nombre }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50px">
                                        <strong>ESTABLECIMIENTO</strong>
                                    </td>
                                    <td>
                                        {{ $venta->sucursale->nombre }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50px">
                                        <strong>OBSERVACIONES</strong>
                                    </td>
                                    <td>
                                        {{ $venta->observaciones }}
                                    </td>
                                </tr>
                                @if ($tipopago->id == 4)
                                    <tr class="table-primary">
                                        <td style="width: 50px">
                                            <strong>FORMA DE PAGO</strong>
                                        </td>
                                        <td>
                                            <select class="form-select" wire:model="selTipo">
                                                <option value="">Seleccione un tipo</option>
                                                @foreach ($tipopagos as $tipo)
                                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                                @endforeach
                                            </select>

                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="row text-center">
                        @if (!cajaCerrada(Auth::user()->id, Auth::user()->sucursale_id))
                            <div class="col-6 d-grid gap-2 mb-2">
                                <button class="btn btn-success text-white fs-5" style="float: center"
                                    onclick="confirmar()">

                                    REGISTRAR PAGO <i class="uil-check-circle"></i>

                                </button>
                            </div>
                        @endif

                        <div class="col-6 d-grid gap-2 mb-2">
                            <a href="{{ route('ventas.vpagos') }}" class="btn btn-danger text-white fs-5">
                                CANCELAR <i class="uil-ban"></i>
                            </a>
                        </div>
                        <div class="col-12">
                            @if (cajaCerrada(Auth::user()->id, Auth::user()->sucursale_id))
                                <div class="alert alert-warning" role="alert">
                                    <i class="dripicons-warning me-2"></i> <strong>Atención - </strong> La caja se
                                    encuentra
                                    cerrada.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-5">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h2 class="h5">DETALLES DE LA VENTA</h2>
                            <table class="table table-bordered table-sm" style="font-size: 11px;">
                                <thead>
                                    <tr align="center" class="table-secondary">
                                        <th>Nro</th>
                                        <th>DETALLE</th>
                                        <th>CANT</th>
                                        <th width="75">P. UNIT.</th>
                                        <th width="75">S.TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($venta->detalleventas as $detalle)
                                        <tr align="center">
                                            <td>{{ $i }}</td>
                                            <td align="left">{{ $detalle->descripcion }}</td>
                                            <td>{{ $detalle->cantidad }}</td>
                                            <td>{{ $detalle->preciounitario }}</td>
                                            <td>{{ $detalle->subtotal }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 mb-1">
                            @if (
                                $venta->tipopago_id == 2 ||
                                    $venta->tipopago_id == 3 ||
                                    ($venta->tipopago_id == 4 && $selTipo != 1 && $selTipo != 5 && $selTipo != ''))


                                <label for="">ADJUNTAR COMPROBANTE</label>
                                <div class="mb-3">
                                    <label for="example-fileinput" class="form-label"></label>
                                    <input type="file" id="example-fileinput" class="form-control mb-2"
                                        accept="image/*" wire:model="comprobante">
                                    @if ($comprobante)
                                        <span>Vista previa:</span>

                                        <img class="img-thumbnail" src="{{ $comprobante->temporaryUrl() }}">
                                    @endif
                                </div>
                                <hr>
                                <form action="/file-upload" class="dropzone" id="my-awesome-dropzone"></form>

                            @endif

                        </div>

                        @if ($venta->tipopago_id == 4 && $loncheras)
                            <div class="col-12">
                                <label for="">LONCHERAS VINCULADAS</label>
                                <table class="table table-striped table-sm"
                                    style="font-size: 11px; vertical-align: middle">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>ESTUDIANTE</th>
                                            <th>ESTADO LONCHERA</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($loncheras)
                                            @foreach ($loncheras as $lonchera)
                                                <tr>
                                                    <td>{{ $lonchera->estudiante->nombre }}</td>
                                                    <td>{{ $lonchera->habilitado ? 'Habilitado' : 'No habilitado' }}
                                                    </td>
                                                    <td>
                                                        @if (!$lonchera->habilitado)
                                                            <button class="btn btn-success btn-sm"
                                                                wire:click='habilitarlonchera({{ $lonchera->id }})'>Habilitar</button>
                                                        @endif

                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif



                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                </div>

                <div class="col-12 col-md-5">


                </div>
            </div>
        </div>
    </div>
</div>
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.3/dropzone.min.css" />
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.3/dropzone.min.js"></script>
    <script>
        < script >
            Dropzone.options.myAwesomeDropzone = { // camelized version of the `id`
               
                dictDefaultMessage: "Arrastre su comprobante.",
              
            };
    </script>
    </script>
    <script>
        function confirmar() {
            Swal.fire({
                title: 'FINALIZAR OPERACIÓN',
                text: "Está seguro de realizar esta operación?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, continuar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('aprobarPedido');
                }
            });
        }
    </script>
@endsection
