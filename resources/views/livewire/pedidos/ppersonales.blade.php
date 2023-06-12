<div>
    @section('template_title')
    Pedidos Estudiante
    @endsection
    {{-- <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">PEDIDOS SIN ENTREGAS</h4>



            </div>

        </div>
    </div> --}}

    <div class="card">
        <div class="card-header bg-primary text-white">
            <div style="display: flex; justify-content: space-between; align-items: center;">

                <span id="card_title">
                    PEDIDOS SIN ENTREGA
                </span>

                <div class="float-right">
                    <button class="btn btn-sm btn-primary" onclick="history.back()"><i class="uil-arrow-left"></i>
                        Volver</button>

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Estudiante:</label>
                <input type="text" class="form-control bg-white mb-2" readonly value="{{$estudiante->nombre}}">
            </div>
            <div class="form-group">
                <label>Curso:</label>
                <input type="text" class="form-control bg-white mb-2" readonly value="{{$estudiante->curso->nombre}}">
            </div>
            <hr>


            <div class="table-responsive mt-2 " data-simplebar>
                @if ($bonofecha)
                <div class="row">
                    <h5 class="text-center mb-2"><strong>Bono activo del: {{$bonofecha->fechainicio}} al {{$bonofecha->fechafin}}</strong></h5>
      <div class="col-12 col-md-6 mb-2">
                        <label><strong>Licencias registradas:</strong></label>
                    </div>
                    <div class="col-12 col-md-6 mb-2 text-end">
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                        data-bs-target="#licenciaBono"><i class="uil-plus" ></i> Nueva Licencia</button>
                    </div>
                </div>

                <table class="table table-bordered table-striped dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>FECHA LICENCIA</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($licencias as $licencia)
                        <tr>
                            <td>{{$licencia->id}}</td>
                            <td>{{$licencia->fecha}}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                @else
                @if ($pedidos)
                <label><strong>Pedidos sin entrega:</strong></label>
                <table class="table table-bordered table-striped dataTable">
                    <thead class="table-primary">
                        <tr>
                            <td><strong>ID</strong></td>
                            <td><strong>FECHA</strong></td>
                            <td><strong>MENU</strong></td>
                            <td><strong></strong></td>
                        </tr>
                    </thead>

                    @foreach ($pedidos as $pedido)
                    <tr>
                        <td>{{$pedido->id}}</td>
                        <td>
                            @if (!$pedido->licencia)
                            {{$pedido->fecha}}
                            @else
                            <span class="badge badge-success-lighten">Licencia</span>
                            @endif
                        </td>
                        <td>
                            @if ($pedido->tipo)
                            {{$pedido->tipo}}
                            @else
                            <span class="badge badge-success-lighten">Licencia</span>
                            @endif
                        </td>
                        <td align="right">
                            <button class="btn btn-warning btn-sm" title="Licencia" @php if($pedido->licencia){
                                echo "disabled";
                                }
                                @endphp
                                onclick="question('{{$pedido->fecha}}',{{$pedido->id}})"><i
                                    class="uil-file-redo-alt"></i>
                            </button>
                            {{-- <button class="btn btn-sm btn-primary" title="Reprogramar Fecha"
                                wire:click="$set('selID', {{$pedido->id}})" data-bs-toggle="modal"
                                data-bs-target="#reprogramacion">
                                <i class="uil-calendar-alt"></i>
                            </button> --}}
                        </td>
                    </tr>
                    @endforeach

                </table>
                @endif
                @endif



            </div>


        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="licenciaBono" tabindex="-1" role="dialog"
    aria-labelledby="Licencia" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title" id="Licencia">Licencia</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"
                    wire:click='resetear'></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label>Fecha Inicial</label>
                    <input type="date" class="form-control" wire:model='fechaI'>
                </div>
                <div class="form-group mb-3">
                    <label>Fecha Final</label>
                    <input type="date" class="form-control" wire:model='fechaF'>
                </div>
                <div class="d-grid">
                    <button class="btn btn-success" wire:click='licenciaBono'> Guardar <i
                            class="mdi mdi-content-save"></i></button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


    <div wire:ignore.self class="modal fade" id="reprogramacion" tabindex="-1" role="dialog"
        aria-labelledby="reprograma" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title" id="reprograma">Reprogramar Pedido</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"
                        wire:click='resetear'></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Seleccione una nueva fecha</label>
                        <input type="date" class="form-control" wire:model='selFecha'>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-success" wire:click='reprogramar'> Guardar <i
                                class="mdi mdi-content-save"></i></button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>
@section('js')
<script>
    function question(fecha,id){
        if(fecha){
            Swal.fire({
            title: 'Solicitud de Licencia',
            html: "Esta seguro de realizar esta operación? <br><b>Fecha: "+fecha+"</b>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, continuar!',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('licencia',id);
            }
        }) 
        }
       
    }

    Livewire.on('hideModal',()=>{
        $('#reprogramacion').modal('hide');
    });
    Livewire.on('hideModal2',()=>{
        $('#licenciaBono').modal('hide');
    });
</script>
@endsection