<div>

    @section('template_title')
    Vinculos
    @endsection

    <div class="card">
        <div class="card-header bg-primary text-white">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span id="card_title">
                    Estudiantes vinculados
                </span>

                <a href="{{route('tutores')}}" class="btn btn-primary btn-sm float-right" data-placement="left">
                    <i class="uil-arrow-left"></i>
                    Volver
                </a>
            </div>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6 mb-2">
                    <p class="form-control"><strong>TUTOR: </strong> {{$tutor->nombre}}</p>
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <div class="form-group d-grid gap-2">
                        @can('estudiantes.create')
                        <button class="btn btn-primary" data-placement="left" data-bs-toggle="modal"
                            data-bs-target="#modalViculo" onclick="store()">
                            <i class="uil-plus"></i>
                            Nuevo Estudiante
                        </button>
                        @endcan
                    </div>
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <div class="form-group d-grid gap-2">
                        @can('estudiantes.edit')
                        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#estudiantes"><i
                                class="uil-check"></i> Asignar Existente</button>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered dataTable" style="vertical-align: middle">
                    <thead>
                        <tr class="table-primary">
                            <th>
                                CODIGO
                            </th>
                            <th>
                                NOMBRE
                            </th>
                            <th>
                                CEDULA
                            </th>
                            <th>
                                CURSO
                            </th>
                            <th style="width: 150px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($estudiantes->count()>0)
                        @foreach ($estudiantes as $item)
                        <tr>
                            <td>{{$item->codigo}}</td>
                            <td>{{$item->nombre}}</td>
                            <td>{{$item->cedula}}</td>
                            <td>{{$item->curso->nombre . ' - ' . $item->curso->nivelcurso->nombre }}</td>
                            <td align="right">
                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false" style="width: 100%">Opciones</button>
                                <div class="dropdown-menu">
                                    @can('estudiantes.edit')
                                    <button class="dropdown-item e" onclick="update()"
                                        wire:click="edit({{ $item->id }})" data-bs-toggle="modal"
                                        data-bs-target="#modalViculo">
                                        <i class="uil-edit"></i> Editar
                                    </button>
                                    <a href="{{route('pedidos.personales',$item->id)}}" class="dropdown-item">
                                        <i class="uil-history"></i> Licencias - Reprog.
                                    </a>
                                    <button class="dropdown-item" onclick="desvincular({{$item->id}})">
                                        <i class="uil-ban"></i> Desvincular del Tutor
                                    </button>
                                    @endcan
                                    @can('estudiantes.destroy')
                                    <button class="dropdown-item" onclick="eliminar({{$item->id}})">
                                        <i class="uil-trash"></i> Eliminar de DB
                                    </button>
                                    @endcan


                                </div>
                                {{-- <a href="{{route('vinculosestudiantes',$item->id)}}"
                                    class="btn btn-primary btn-sm e" title="Vinculos">
                                    <i class="fas fa-sitemap"></i>
                                </a> --}}
                                {{-- @can('estudiantes.edit')
                                <button class="btn btn-info btn-sm e" title="Editar" onclick="update()"
                                    wire:click="edit({{ $item->id }})" data-bs-toggle="modal"
                                    data-bs-target="#modalViculo">
                                    <i class="uil-edit"></i>
                                </button>
                                <button class="btn btn-warning btn-sm text-white" title="Desvincular"
                                    onclick="desvincular({{$item->id}})">
                                    <i class="uil-ban"></i>
                                </button>
                                @endcan
                                @can('estudiantes.destroy')
                                <button class="btn btn-danger btn-sm text-white" title="Eliminar"
                                    onclick="eliminar({{$item->id}})">
                                    <i class="uil-trash"></i>
                                </button>
                                @endcan --}}
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            {{-- <div style="float: right" class="mt-3">
                {{ $estudiantes->links() }}
            </div> --}}
            {{-- <div class="card-footer"></div> --}}
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="estudiantes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="estudiantesLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="estudiantesLabel">ESTUDIANTES PARA ASIGNAR</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label for="">Busqueda</label>
                        <input type="search" class="form-control" placeholder="Buscar por nombre, cedula, codigo"
                            wire:model.debounce.500ms='busquedaestudiante'>
                    </div>
                    <div class="table-responsive" data-simplebar style="font-size: 12px; max-height: 250px;">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="table-info">
                                <tr>
                                    <th>ID</th>
                                    <th>NOMBRE</th>
                                    <th>CURSO</th>
                                    <th>CEDULA</th>
                                    <th>CODIGO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!is_null($resultados))
                                @foreach ($resultados as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->nombre}}</td>
                                    <td>{{$item->curso}}</td>
                                    <td>{{$item->cedula}}</td>
                                    <td>{{$item->codigo}}</td>
                                    <td >
                                        <div class="form-check form-checkbox-success mb-2">
                                            <input type="checkbox" class="form-check-input" id="cb{{$item->id}}" value="{{$item->id}}" wire:model=checks>
                                            <label class="form-check-label" for="cb{{$item->id}}">Seleccionar</label>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6">No se encontraron registros.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    @if ($checks)
                    <button class="btn btn-success" data-bs-dismiss="modal"
                    wire:click='asignar()'>Asingar</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div wire:ignore class="modal fade" id="modalViculo" tabindex="-1" aria-labelledby="modalViculoLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEstadoPagoLabel">FORMULARIO DE REGISTRO</h5>
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
                            <label for="">ES ESTUDIANTE</label>
                        </div>
                        <div class="col-8 mb-2">
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="customCheckcolor1" wire:model='esestudiante'>
                            </div>
                        </div>

                        <div class="col-4 mb-2">
                            <label for="">CURSO</label>
                        </div>
                        <div class="col-8 mb-2">
                            <select name="curso_id" id="curso_id" class="form-select" wire:model="curso_id">
                                <option value="">Seleccione un curso</option>
                                @foreach ($cursos as $item)
                                <option value="{{$item->id}}">{{$item->nombre." - ".$item->nivelcurso->nombre}}</option>
                                @endforeach

                            </select>
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

    @section('js')
    <script>
        Livewire.on('datatable',()=>{
     $(".dataTableL").DataTable({
                 destroy:true,
         keys:!0,
         language:{
             url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
             paginate:{
                 previous:"<i class='mdi mdi-chevron-left'>",
                 next:"<i class='mdi mdi-chevron-right'>",
             }},
         drawCallback:function(){
                 $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
             }    
     });
    })
    </script>
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
             title: 'Eliminar estudiante de la BD!',
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
         function desvincular(id){
             const swalWithBootstrapButtons = Swal.mixin({
             customClass: {
                 confirmButton: 'btn btn-success',
                 cancelButton: 'btn btn-danger'
             },
             buttonsStyling: false
         })
         
         swalWithBootstrapButtons.fire({
             title: 'Desvincular estudiante del Tutor!',
             text: "Esta seguro de realizar la operación?",
             icon: 'warning',
             showCancelButton: true,
             confirmButtonText: 'Si, continuar!',
             cancelButtonText: 'No, cancelar!',
             reverseButtons: true
         }).then((result) => {
             if (result.isConfirmed) {
                 @this.emit('desvincular',id);
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
    @endsection
</div>