<div>
    @section('template_title')
    PAGOS PROFESORES
    @endsection
    <div class="card">
        <div class="card-header bg-primary text-white">
            <label>CREDITOS PENDIENTES - PLANTEL DOCENTE</label>
        </div>
        <div class="card-body">
            <span>Seleccione un rango de fecha</span>
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
                        <button class="btn btn-info" wire:click='buscar'><i class="uil-search"></i> Buscar</button>
                    </div>

                </div>
                <div class="col-12 col-md-3"></div>
            </div>

            <div class="content">
                @if ($resultados)
                <table>
                    <thead>
                        <tr>
                            <td>CLIENTE</td>
                            <td>PRODUCTO</td>
                            <td>CANTIDAD</td>
                            <td>IMPORTE</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($creaditos as $credito)
                        <tr>
                            <td>{{$credito->id}}</td>
                            <td>{{$credito->id}}</td>
                            <td>{{$credito->id}}</td>
                            <td>{{$credito->id}}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                @endif
            </div>

        </div>
    </div>
</div>