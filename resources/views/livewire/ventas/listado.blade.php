<div>
    <label for=""><strong>Filtros:</strong></label>
    <div class="row mb-1 mt-2">
        <div class="col-12 col-md-4 mb-2 ">
            <div class="input-group flex-nowrap">
                <span class="input-group-text" id="basic-addon1">Inicio</span>
                <input wire:model='fecInicio' type="date" class="form-control">
            </div>
        </div>
        <div class="col-12 col-md-4 mb-2">
            <div class="input-group flex-nowrap">
                <span class="input-group-text" id="basic-addon1">Fin</span>
                <input wire:model='fecFin' type="date" class="form-control">
            </div>
        </div>
        @if (count($ventas) > 0)
            <div class="col-12 col-md-2 mb-2 d-grid">
                <button class="btn btn-danger" wire:click='exportar'>PDF <i class="mdi mdi-file-pdf-outline"></i></button>
            </div>
            <div class="col-12 col-md-2 mb-2 d-grid">
                <button class="btn btn-success" wire:click='excel'>Excel <i class="mdi mdi-file-excel-outline"></i></button>
            </div>
        @endif

    </div>



    <div class="table-responsive" data-simplebar>
        <table class="table table-striped table-hover table-bordered dataTable">
            <thead class="thead table-primary">
                <tr>
                    <td align="center"><strong> ID</strong></td>
                    <td align="center"><strong> Fecha</strong></td>
                    <td><strong> Cliente</strong></td>
                    <td align="center"><strong> Tipo Pago</strong></td>
                    <td align="center"><strong> Estado</strong></td>
                    <td align="right"><strong> Importe</strong></td>

                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ventas as $venta)
                    <tr>
                        <td align="center">{{ $venta->id }}</td>
                        <td align="center">{{ $venta->fecha }}</td>
                        <td>{{ $venta->cliente }}</td>
                        <td align="center">{{ $venta->tipopago }}</td>
                        <td align="center">{{ $venta->estadopago }}</td>
                        <td align="right">{{ $venta->importe }}</td>

                        <td align="right">
                            <form action="{{ route('ventas.destroy', $venta->id) }}" onsubmit="return false"
                                method="POST" class="delete">
                                <a class="btn btn-sm btn-primary " href="{{ route('ventas.show', $venta->id) }}"
                                    title="Ver info"><i class="uil-eye"></i></a>
                                @can('ventas.destroy')
                                    <a class="btn btn-sm btn-success" href="{{ route('ventas.destroy', $venta->id) }}"
                                        title="Editar"><i class="uil-edit"></i></a>
                                @endcan


                                @csrf
                                @method('DELETE')
                                {{-- <button type="submit" class="btn btn-danger btn-sm" title="Anular Venta"><i
                                    class="uil-trash"></i></button> --}}
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3" style="float: right">
        {{-- {!! $ventas->links() !!} --}}
    </div>
</div>
