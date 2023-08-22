<div>
    @section('template_title')
        Reporte Entregas a Profesores
    @endsection

    <div class="card">
        <div class="card-header bg-primary text-white">
            REPORTE ENTREGAS A PROFESORES
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="mb-3">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="basic-addon1">FECHA</span>
                            <input type="date" class="form-control" aria-describedby="basic-addon1"
                                wire:model='selFecha'>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3 d-grid mb-3">
                    <button class="btn btn-info" wire:click='generar'><i class="uil-cog"></i> GENERAR <div wire:loading
                            wire:target="generar">
                            <div class="spinner-border spinner-border-sm" role="status"></div>
                        </div></button>

                </div>
                @if ($tabla1)
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

    @if ($tabla1)
        <div class="card">
            <div class="card-body">
                <h2 class="h4 text-center">ALMUERZOS ENTREGADOS A PROFESORES</h2>
                <h2 class="h5 text-center"><strong>Fecha: </strong>{{ $selFecha }}</h2>
                <hr>
            </div>
        </div>
        <table class="table table-striped table-bordered table-sm table-hover" style="font-size: 11px;">
            <thead class="table-secondary">

                <tr>
                    <td align="center"><strong> NRO</strong></td>
                    <td><strong> NOMBRE</strong></td>
                    <td align="center"><strong> PAGADO</strong></td>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($tabla1 as $item)
                    <tr>
                        <td align="center">{{ $i++ }}</td>
                        <td>{{ $item->estudiante->nombre }}</td>
                        <td align="center">{{ $item->pagado ? 'SI' : 'NO' }}</td>
                    </tr>
                @endforeach

            </tbody>
            {{-- <tfoot class="table-success">
                                    <tr>
                                        <td colspan="2" align="right"><strong>TOTAL</strong></td>
                                        <td align="center"><strong>{{ $tpagados }}</strong></td>
                                        <td align="center"><strong>{{ $tservidos }}</strong></td>
                                        <td align="center"><strong>{{ $tausencias }}</strong></td>
                                        <td align="center"><strong>{{ $tlicencias }}</strong></td>
                                    </tr>
                                </tfoot> --}}
        </table>
    @endif
</div>
