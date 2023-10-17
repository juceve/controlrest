<div>
    @section('template_title')
        Reporte de Ventas
    @endsection
    <div class="card">
        <div class="card-header bg-success text-white">
            Reporte de Ventas
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-12 col-md-3">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">Inicio</span>
                        <input type="date" class="form-control" placeholder="Fecha inicial" aria-label="Fecha inicial"
                            aria-describedby="basic-addon1" wire:model='fechaI'>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">Final</span>
                        <input type="date" class="form-control" placeholder="Fecha final" aria-label="Fecha final"
                            aria-describedby="basic-addon1" wire:model='fechaF'>
                    </div>
                </div>
                {{-- <div class="col-12 col-md-3 d-grid">
                    <button class="btn btn-primary"><i class="uil-search"></i> Buscar</button>
                </div> --}}
                @if ($ventas)
                    <div class="col-12 col-md-3 d-grid">
                        <button class="btn btn-danger"><i class="mdi mdi-file-pdf"></i> Exportar</button>
                    </div>
                @endif

            </div>

            @if ($ventas)
                
            @endif
        </div>
    </div>

</div>
