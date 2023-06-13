<div>
    @section('template_title')
        Pedidos no Entregados
    @endsection
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">PEDIDOS NO ENTREGADOS DEL DIA</h4>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-info text-white">
            Listado de Pedidos
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-3 d-grid">
                    <button class="btn btn-primary" wire:click="generaListado">Buscar Pedidos <i
                            class="uil-search"></i></button>
                </div>
            </div>
            @if ($arrPedidos)
                <h2 class="h4 text-center">PEDIDOS NO ENTREGADOS - {{ date('Y-m-d') }}</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable">
                        <thead class="table-primary">
                            <tr align="center">
                                <th>CODIGO</th>
                                <th>ESTUDIANTE</th>
                                <th>CURSO</th>
                                <th>MENU</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($arrPedidos as $pedido)
                                <tr>
                                    <td align="center">{{ $pedido['codigo'] }}</td>
                                    <td>{{ $pedido['estudiante'] }}</td>
                                    <td align="center">{{ $pedido['curso'] }}</td>
                                    <td>{{ $pedido['menu'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="content text-center">
                    <button class="btn btn-success" onclick="finalizar();">FINALIZAR DIA <i class="uil-users-alt"></i><i
                            class="uil-check"></i></button>
                </div>
            @endif
        </div>
    </div>

</div>
@section('js')
    <script>
        function finalizar(){
            Swal.fire({
            title: 'FINALIZAR PEDIDOS',
            text: "Está seguro de realizar esta operación?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, continuar!',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('finalizar');
            }
        })
        }
    </script>
@endsection
