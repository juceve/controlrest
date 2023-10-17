<div>
    @section('template_title')
        Entregas por Día
    @endsection
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">ENTREGAS POR DIA</h4>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-info text-white">
            Listado de Entregas
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-12 col-md-3 d-grid">
                    <input type="date" class="form-control" wire:model='fecha'>
                </div>
                <div class="col-12 col-md-3 d-grid">
                    <button class="btn btn-primary" wire:click="generaListado">Buscar Entregas <i
                            class="uil-search"></i></button>
                </div>
            </div>
            @if ($arrPedidos)
                <h2 class="h4 text-center">ENTREGAS REALIZADAS EL {{ $fecha }}</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable">
                        <thead class="table-primary">
                            <tr align="center">
                                <th>FECHA ENTREGA</th>
                                <th>ESTUDIANTE</th>
                                <th>CURSO</th>
                                <th>MENU</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($arrPedidos as $pedido)
                                <tr>
                                    <td align="center">{{ $pedido->fechaentrega }}</td>
                                    <td>{{ $pedido->estudiante->nombre }}</td>
                                    <td align="center">{{ $pedido->estudiante->curso->nombre }}</td>
                                    <td>{{ $pedido->menu->nombre }}</td>
                                    <td>
                                        <button class="btn btn-outline-danger btn-sm" id="delete"
                                            onclick="eliminar({{ $pedido->id }})" title="Eliminar">
                                            <i class="uil-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
@section('js')
    <script>
        function eliminar(id) {
            Swal.fire({
                title: 'ELIMINAR ENTREGA',
                text: "Está seguro de realizar esta operación?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, continuar',
                cancelButtonText: 'No, cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                   Livewire.emit('eliminar',id);
                }
            })
        }
    </script>
@endsection
