<div>
    @section('template_title')
        Estado de Saldos
    @endsection
    <div class="card">
        <div class="card-header bg-info text-white">
            <label>Estado de Saldos por Cursos</label>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <label class="input-group-text" for="inputGroupSelect01">Cursos</label>
                        <select class="form-select" wire:model='selCurso'>
                            <option value="">Elija una opción</option>
                            @if ($cursos)
                                @foreach ($cursos as $curso)
                                    <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-3 d-grid">
                    <button class="btn btn-primary" wire:click='buscar'><i class="uil-search"></i> Buscar <div
                            wire:loading wire:target="buscar">
                            <div class="spinner-border spinner-border-sm" role="status"></div>
                        </div></button>
                </div>
                @if ($tabla)
                    <div class="col-12 col-md-3 d-grid">

                        <button class="btn btn-danger" wire:click='pdf'><i class="mdi mdi-file-pdf"></i> PDF <div
                                wire:loading wire:target="pdf">
                                <div class="spinner-border spinner-border-sm" role="status"></div>
                            </div></button>

                    </div>
                @endif
                <div class="col-12 col-md-2 d-grid">
                    <button class="btn btn-success" wire:click='totalSaldos'><i class="uil-search"></i> Saldo
                        Total <div wire:loading wire:target="totalSaldos">
                            <div class="spinner-border spinner-border-sm" role="status"></div>
                        </div></button>
                </div>
            </div>
        </div>
    </div>

    @if ($totalSaldos)
        <div class="card">
            <div class="card-body">
                <label>Saldo Total: </label>
                <input type="text" class="form-control" wire:model='totalSaldos' readonly>
            </div>
        </div>
    @endif

    @if ($tabla)

        <div class="card">
            <div class="card-body">
                <h4 class="text-center">ESTADO DE PEDIDOS POR ALUMNOS</h4>
                <hr>
                <div class="table-responsive">
                    <div class="">
                        <label class="text-warning"><i>Cantidad de reservas pendientes de entrega</i></label>
                    </div>

                    <table class="table table-bordered table-sm table-striped dataTable">
                        <thead class="table-primary">
                            <tr align="center">
                                <th>CODIGO</th>
                                <th>NOMBRE</th>
                                <th>ALMUERZOS PAGADOS</th>
                                <th>ENTREGAS</th>
                                <th>RESTANTES</th>
                            </tr>
                        </thead>
                        <tbody> @php
                            $i = 1;
                        @endphp
                            @foreach ($tabla as $item)
                                <tr>
                                    <td align="center">{{ $item['codigo'] }}</td>
                                    <td>{{ $item['estudiante'] }}</td>
                                    <td align="center">{{ $item['pagados'] }}</td>
                                    <td align="center">{{ $item['entregas'] }}</td>
                                    <td align="center">{{ $item['restantes'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
