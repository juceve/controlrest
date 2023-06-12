<div>
    @section('template_title')
    Tutores
    @endsection

    <div class="card">
        <div class="card-header bg-primary text-white">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span id="card_title">
                    LISTADO DE TUTORES
                </span>
                @can('tutores.create')
                <div class="float-right">
                    <button class="btn btn-primary btn-sm" data-placement="left" data-bs-toggle="modal"
                        data-bs-target="#modalTutor" onclick="store()">
                        <i class="uil-plus"></i>
                        Nuevo
                    </button>
                </div>
                @endcan

            </div>
        </div>
        <div class="card-body">
            <div class="mb-3" style="overflow-x: hidden;">
                <form class="form-horizontal mb-3">
                    <div class="form-group row">
                        <div class="col-md-2 text-md-right col-xl-1 text-lg-left">
                            <label for="form-label">Ordenar</label>
                        </div>
                        <div class="col-3 col-sm-2  col-md-2 col-lg-2 col-xl-1">
                            <select class="form-control form-control-sm" wire:model="paginate" style="width: 100%">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="col-2 col-xl-5"></div>
                        <label class="col-md-2 col-xl-1 form-label text-lg-right">Buscar:</label>
                        <div class="col-md-4 ">
                            <input type="search" class="form-control form-control-sm"
                                placeholder="Buscar por nombre, cedula o telefono" wire:model="search"
                                style="width: 100%">
                        </div>


                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-stripped table-sm">
                        <thead class="thead">
                            <tr class="table-primary">
                                <th style="cursor: pointer;" wire:click="order('id')">
                                    ID
                                    {{-- Sorts --}}
                                    @if ($sort == 'id')
                                    @if ($direction == 'asc')
                                    <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                    <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif
                                    @else
                                    <i class="fas fa-sort float-right mt-1"></i>
                                    @endif
                                </th>

                                <th style="cursor: pointer;" wire:click="order('nombre')">
                                    NOMBRE
                                    {{-- Sorts --}}
                                    @if ($sort == 'nombre')
                                    @if ($direction == 'asc')
                                    <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                    <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif
                                    @else
                                    <i class="fas fa-sort float-right mt-1"></i>
                                    @endif

                                </th>
                                <th style="cursor: pointer;" wire:click="order('cedula')">
                                    CEDULA
                                    {{-- Sorts --}}
                                    @if ($sort == 'cedula')
                                    @if ($direction == 'asc')
                                    <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                    <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif
                                    @else
                                    <i class="fas fa-sort float-right mt-1"></i>
                                    @endif
                                </th>
                                <th style="cursor: pointer;" wire:click="order('telefono')">
                                    TELEFONO
                                    {{-- Sorts --}}
                                    @if ($sort == 'telefono')
                                    @if ($direction == 'asc')
                                    <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                    <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif
                                    @else
                                    <i class="fas fa-sort float-right mt-1"></i>
                                    @endif
                                </th>
                                <th style="width: 150px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($tutores->count()>0)
                            @foreach ($tutores as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->nombre}}</td>
                                <td>{{$item->cedula}}</td>
                                <td>{{$item->telefono}}</td>
                                <td align="right">
                                    @can('tutores.index')
                                        <a href="{{route('vinculosestudiantes',$item->id)}}"
                                        class="btn btn-primary btn-sm e" title="Vinculos">
                                        <i class="uil-sitemap"></i>
                                    </a> 
                                    @endcan
                                   
                                    @can('tutores.edit')
                                    <button class="btn btn-warning btn-sm e" title="Editar" onclick="update()"
                                        wire:click="edit({{ $item->id }})" data-bs-toggle="modal"
                                        data-bs-target="#modalTutor">
                                        <i class="uil-edit"></i>
                                    </button>
                                    @endcan
                                    @can('tutores.destroy')
                                    <button class="btn btn-danger btn-sm text-white" title="Eliminar"
                                        onclick="eliminar({{$item->id}})">
                                        <i class="uil-trash"></i>
                                    </button>
                                    @endcan



                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" align="center"><span>No se encontraron registros</span></td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                </div>

            </div>
            <div style="float: right">
                {{ $tutores->links() }}
            </div>

        </div>
        <div class="card-footer"></div>
    </div>
    <!-- Modal -->
    <div wire:ignore class="modal fade" id="modalTutor" tabindex="-1" aria-labelledby="modalTutorLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEstadoPagoLabel">FORMULARIO DE TUTORES</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="resetear"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-4 mb-2">
                            <label for="">NOMBRE</label>
                        </div>
                        <div class="col-8 mb-2">
                            <input type="text" class="form-control" wire:model.defer="nombre">
                        </div>
                        <div class="col-4 mb-2">
                            <label for="">CEDULA</label>
                        </div>
                        <div class="col-8 mb-2">
                            <input type="text" class="form-control" wire:model.defer="cedula">
                        </div>
                        <div class="col-4 mb-2">
                            <label for="">CORREO</label>
                        </div>
                        <div class="col-8 mb-2">
                            <input type="text" class="form-control" wire:model.defer="correo">
                        </div>
                        <div class="col-4 mb-2">
                            <label for="">TELEFONO</label>
                        </div>
                        <div class="col-8 mb-2">
                            <input type="text" class="form-control" wire:model.defer="telefono">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="resetear">Cerrar</button>
                    <button type="button" class="btn btn-primary store" wire:click="store"
                        data-bs-dismiss="modal">Registrar</button>
                    <button type="button" class="btn btn-primary update" data-bs-dismiss="modal"
                        wire:click="update">Guardar</button>
                </div>

            </div>
        </div>
    </div>
    <script>
        function update(){
        $('.store').addClass('d-none');
        $('.update').removeClass('d-none');
    }
    function store(){
        $('.store').removeClass('d-none');
        $('.update').addClass('d-none');
    }

    function eliminar(id){
        const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'Eliminar tutor!',
        text: "Esta seguro de realizar la operación?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, continuar!',
        cancelButtonText: 'No, cancelar!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            @this.emit('destroy',id);
        } else if (
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Operación cancelada',
                'No se modificó ningún registro',
                'error'
            )
        }
    })
    }
    </script>
</div>