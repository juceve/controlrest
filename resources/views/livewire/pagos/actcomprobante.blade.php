<div>
    @section('template_title')
        Actualizar Comprobante de Pago
    @endsection
    <div class="card">
        <div class="card-header bg-info text-white">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span id="card_title">
                    ACTUALIZA COMPROBANTE DE PAGO
                </span>

                <a href="{{ route('pagos.sincomprobante') }}" class="btn btn-info btn-sm float-right"
                    data-placement="left">
                    <i class="uil-arrow-left"></i>
                    Volver
                </a>
            </div>



        </div>
        <div class="card-body">
            <h5>DATOS DEL PAGO</h5>
            <div class="row">
                <div class="col-12 col-md-5">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td><strong>ID</strong></td>
                                <td>{{ $pago->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>FECHA</strong></td>
                                <td>{{ $pago->fecha }}</td>
                            </tr>
                            <tr>
                                <td><strong>CLIENTE</strong></td>
                                <td>{{ $pago->venta->cliente }}</td>
                            </tr>
                            <tr>
                                <td><strong>IMPORTE</strong></td>
                                <td>{{ $pago->importe }}</td>
                            </tr>
                            <tr>
                                <td><strong>COMPROBANTE</strong></td>
                                <td></td>
                            </tr>
                        </table>
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label"></label>
                            <input type="file" id="example-fileinput" class="form-control mb-2" accept="image/*"
                                wire:model="comprobante">
                            @if ($comprobante)
                            <div class="d-grid mt-1 mb-2">
                                <button class="btn btn-success" wire:click='guardar'>Guardar <i class="fas fa-save"></i></button>
                            </div>
                                <span>Vista previa:</span>

                                <img class="img-thumbnail" src="{{ $comprobante->temporaryUrl() }}">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
