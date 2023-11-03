<div>
    <label for=""><strong>Filtros:</strong></label>
    <div class="row mb-1 mt-2">
        <div class="col-12 col-md-3 mb-2 ">
            <div class="input-group flex-nowrap">
                <span class="input-group-text" id="basic-addon1">Inicio</span>
                <input wire:model='fecInicio' type="date" class="form-control">
            </div>
        </div>
        <div class="col-12 col-md-3 mb-2">
            <div class="input-group flex-nowrap">
                <span class="input-group-text" id="basic-addon1">Fin</span>
                <input wire:model='fecFin' type="date" class="form-control">
            </div>
        </div>
        <div class="col-12 col-md-2 mb-2">
            <div class="input-group flex-nowrap">
                <span class="input-group-text" id="basic-addon1">Tipo</span>
                <select wire:model='tp' class="form-select">
                    <option value="">Todos</option>
                    @foreach ($tps as $tp)
                        <option value="{{ $tp->id }}">{{ $tp->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @if ($ventas->count()>0)
            <div class="col-12 col-md-2 mb-2 d-grid">
                <button class="btn btn-danger" wire:click='exportar'>PDF <i
                        class="mdi mdi-file-pdf-outline"></i></button>
            </div>
            <div class="col-12 col-md-2 mb-2 d-grid">
                <button class="btn btn-success" wire:click='excel'>Excel <i
                        class="mdi mdi-file-excel-outline"></i></button>
            </div>
        @endif

    </div>



    <div class="table-responsive" data-simplebar>
        <table class="table table-striped table-hover table-bordered dataTable" style="font-size: 12px;">
            <thead class="thead table-primary table-sm">
                <tr>
                    <td align="center"><strong> ID</strong></td>
                    <td align="center" style="width: 80px;"><strong> Fecha</strong></td>
                    <td><strong> Cliente</strong></td>
                    <td><strong> Estudiantes</strong></td>
                    <td align="center"><strong> Tipo Pago</strong></td>
                    <td align="center"><strong> Estado</strong></td>
                    <td align="right"><strong> Importe</strong></td>

                    <th style="width: 120px;"></th>
                </tr>
            </thead>
            <tbody>
                @if ($ventas->count() > 0)
                    @foreach ($ventas as $venta)
                        <tr>
                            <td align="center">{{ $venta->id }}</td>
                            <td align="center">{{ $venta->fecha }}</td>
                            <td>{{ $venta->cliente }}</td>
                            <td>{{ traeEstudiantesVenta($venta->id) }}</td>
                            <td align="center">{{ $venta->tipopago }}</td>
                            <td align="center">{{ $venta->estadopago }}</td>
                            <td align="right">{{ $venta->importe }}</td>

                            <td align="right">
                                {{-- <form action="{{ route('ventas.destroy', $venta->id) }}" --}}
                                {{-- method="POST" class="anular"> --}}
                                <a class="btn btn-sm btn-outline-primary " href="{{ route('ventas.show', $venta->id) }}"
                                    title="Ver info"><i class="uil-eye"></i></a>
                                @can('ventas.edit')
                                    <a class="btn btn-sm btn-outline-success"
                                        href="{{ route('ventas.destroy', $venta->id) }}" title="Editar"><i
                                            class="uil-edit"></i></a>
                                @endcan


                                @csrf
                                @method('DELETE')
                                @can('ventas.destroy')
                                    <button type="button" class="btn btn-outline-danger btn-sm" title="Anular Venta"
                                        onclick="anular({{ $venta->id }})"><i class="uil-trash"></i></button>
                                @endcan

                                {{-- </form> --}}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="mt-3" style="float: right">
        {{-- {!! $ventas->links() !!} --}}
    </div>
</div>
@section('js')
    <script>
        function anular(id) {
            Swal.fire({
                title: 'Anular Venta',
                text: "Esta seguro de realizar esta operación?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, continuar!',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('anular', id);
                }
            })
        }
    </script>
@endsection
