<div>
    @section('template_title')
        Reporte de Pagos de Creditos
    @endsection
    <div class="card">
        <div class="card-header bg-primary text-white">
            REPORTE DE PAGOS DE CREDITOS
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="mb-3">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="basic-addon1">INICIO</span>
                            <input type="date" class="form-control" aria-describedby="basic-addon1"
                                wire:model='fechaI'>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="mb-3">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="basic-addon1">FINAL</span>
                            <input type="date" class="form-control" aria-describedby="basic-addon1"
                                wire:model='fechaF'>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3 d-grid mb-3">
                    <button class="btn btn-info" wire:click='generar'><i class="uil-cog"></i> GENERAR <div wire:loading
                            wire:target="generar">
                            <div class="spinner-border spinner-border-sm" role="status"></div>
                        </div></button>

                </div>
                @if ($pagos)
                    <div class="col-12 col-md-3 d-grid mb-3">
                        <button class="btn btn-danger" wire:click='pdf'><i class="mdi mdi-file-pdf"></i> PDF <div
                                wire:loading wire:target="pdf">
                                <div class="spinner-border spinner-border-sm" role="status"></div>
                            </div></button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @if ($pagos)
        <div class="card">
            <div class="card-header text-center">
                <strong>PAGO DE CREDITOS</strong><br><small>Del {{ $fechaI }} al {{ $fechaF }}</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-striped dataTable" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Fecha Pago</th>
                                <th>Fecha Venta</th>
                                <th>Cliente</th>
                                <th>Forma Pago</th>
                                <th>Usuario</th>
                                <th align="right">Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i=1;
                            @endphp
                            @foreach ($pagos as $pago)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>
                                        {{ $pago->fecha }}
                                    </td>
                                    <td>
                                        {{ $pago->venta->fecha }}
                                    </td>
                                    <td>
                                        {{ $pago->venta->cliente }}
                                    </td>
                                    <td>
                                        {{ $pago->tipopago }}
                                    </td>
                                    <td>
                                        {{ $pago->user->name }}
                                    </td>
                                    <td align="right">
                                        {{ $pago->importe }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    @endif

</div>
</div>
