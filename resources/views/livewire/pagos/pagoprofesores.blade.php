<div>
    @section('template_title')
        CREDITO PROFESORES
    @endsection
    <div class="card">
        <div class="card-header bg-primary text-white">
            <label>CREDITOS PENDIENTES - PLANTEL DOCENTE</label>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="input-group mb-3">
                        <label class="input-group-text">Inicio</label>
                        <input type="date" class="form-control" wire:model='fechainicio'>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="input-group mb-3">
                        <label class="input-group-text">Final</label>
                        <input type="date" class="form-control" wire:model='fechafin'>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="d-grid">
                        <button class="btn btn-info" wire:click='buscar'>
                            <i class="uil-search"></i> Buscar
                            <div wire:loading wire:target="buscar">
                                <div class="spinner-border spinner-border-sm" role="status"></div>
                            </div>
                        </button>
                    </div>

                </div>
                <div class="col-12 col-md-3">
                    @if ($resultados)
                        <div class="d-grid">
                            <button class="btn btn-danger" wire:click='pdf'>
                                <i class="mdi mdi-file-pdf"></i> Exportar PDF
                                <div wire:loading wire:target="pdf">
                                    <div class="spinner-border spinner-border-sm" role="status"></div>
                                </div>
                            </button>
                        </div>
                    @endif

                </div>
            </div>
            @if ($resultados)
                <div class="content">
                    <h4 class="text-center">LISTADO DE ALMUERZOS A CREDITOS PARA PROFESORES</h4>
                    <div class="content text-center"><small>Del: {{ $fechainicio }} al {{ $fechafin }}</small>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm " style="font-size: 11px;">
                                <thead class="table-secondary">
                                    <tr style="vertical-align: middle;">
                                        <td><strong>CLIENTE</strong></td>
                                        <td align="center"><strong>AREA</strong></td>
                                        @foreach ($dias as $dia)
                                            <td align="center" style="width: 150px;"><strong>{{ fechacorta($dia[0]) }}
                                                    <br> {{ substr(nombreDiaESP($dia[0]), 0, 3) }}</strong></td>
                                        @endforeach
                                        <td align="center"><strong>TOTAL Bs.</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resultados as $items)
                                        <tr>
                                            @foreach ($items as $item)
                                                <td align="{{ $item[1] }}" style="{{ $item[2] }}">
                                                    {{ $item[0] }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-success">
                                    <tr>
                                        <td colspan="2" align="right"><strong>TOTAL</strong></td>
                                        @foreach ($dias as $item)
                                            <td align="center">
                                                <strong>{{ number_format($item[1], 2, '.', ',') }}</strong>
                                            </td>
                                        @endforeach
                                        <td align="center"><strong>{{ number_format($ttotal, 2, '.', ',') }}</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
            @endif

            <div class="content">
                @if ($creditos)

                    <div class="table-responsive d-none" data-simplebar wire:ignore>
                        <table class="table table-bordered table-striped table-sm dataTable">
                            <thead class="table-secondary">
                                <tr>
                                    <td align="center"><strong>NRO</strong></td>
                                    <td><strong> CLIENTE</strong></td>
                                    <td><strong>AREA</strong></td>
                                    <td align="center"><strong> CANTIDAD</strong></td>
                                    <td align="right"><strong> IMPORTE</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $curso = '';
                                    $cantidad = 0;
                                    $total = 0;
                                    $i = 1;
                                @endphp
                                @foreach ($creditos as $credito)
                                    @php
                                        $total += $credito[4];
                                        $cantidad += $credito[3];
                                        // if ($curso != $credito->curso) {
                                        //     $curso = $credito->curso;
                                        //     echo "<tr class='table-info'><td colspan='4'><strong>$curso</strong></td></tr>";
                                        // }
                                    @endphp
                                    <tr>
                                        <td align="center">{{ $i++ }}</td>
                                        <td>{{ $credito[2] }}</td>
                                        <td>{{ $credito[0] }}</td>
                                        <td align="center">{{ $credito[3] }}</td>
                                        <td align="right">{{ $credito[4] }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <h4>CONSOLIDADO</h4>
                    <div class="row">
                        <div class="col-12 col-md-4 table-responsive ">
                            <table class="table table-bordered">
                                <tr>
                                    <td>CANTIDAD DE ALMUERZOS</td>
                                    <td align="right"><strong>{{ $cantidad }}</strong></td>
                                </tr>
                                <tr>
                                    <td>TOTAL IMPORTE</td>
                                    <td align="right"><strong>{{ $total }}</strong></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-12 col-md-3 ">
                            <table style="width: 100%">
                                <tr style="vertical-align: bottom">
                                    <td>
                                        <div class="d-grid"><button class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#pagos-modal">GESTIONAR PAGOS <i
                                                    class="uil-money-withdrawal"></i></button></div>
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>

                @endif
            </div>

        </div>
    </div>

    {{-- MODAL PAGO CREDITOS --}}
    <div id="pagos-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
        aria-hidden="true" data-bs-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">REALIZAR PAGOS DE CREDITOS</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-center">Detalles de la Transacción</h5>
                    <table class="table">
                        <tr style="vertical-align: middle">
                            <td>MONTO A PAGAR Bs.</td>
                            <td><input type="text" readonly class="form-control bg-white" value="{{number_format($ttotal, 2, '.', ',')}}"></td>
                        </tr>
                        <tr>
                            <td>CLIENTE</td>
                            <td><input type="text" class="form-control" wire:model='clientePago'></td>
                        </tr>
                        <tr style="vertical-align: middle">
                            <td>TIPO DE PAGO</td>
                            <td>
                                <select class="form-select" wire:model='selTipo'>
                                    <option value="">Seleccione un Tipo</option>
                                    @foreach ($tipopagos as $tipo)
                                        <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                                    @endforeach                                    
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="confirmar()">Generar Pagos</button>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
<script>
    function confirmar() {
        Swal.fire({
            title: 'FINALIZAR CREDITOS SELECCIONADOS',
            text: "Está seguro de realizar esta operación?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, continuar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('gestionarPagos');
            }
        });
    }

    Livewire.on('cerrarmodal',()=>{
        $('#pagos-modal').modal('hide');
    })
</script>
@endsection