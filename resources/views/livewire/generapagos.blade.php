<div>
    @section('template_title')
    GENERA PAGOS NO REGISTRADOS
    @endsection

    <div class="card">
        <div class="card-header">
            <h2 class="h5">GENERA PAGOS NO REGISTRADOS DE ESTUDIANTES </h2>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-4 mb-3">
                    <label for="archivo" class="form-label">Subir archivo TXT <small>(Separado por salto de
                            linea)</small></label>
                    <input type="file" id="archivo" class="form-control" wire:model='file'><br>
                    <button class="btn btn-primary" wire:click='ejecutar'
                        wire:loading.attr="disabled">Ejecutar!</button>
                </div>

                <div class="col-12 col-md-4 mb-2">

                </div>
            </div>

        </div>
    </div>
</div>