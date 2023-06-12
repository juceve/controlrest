<div>
    @section('css')
        <style>
            .dataTables_length {
                display: none;
            }

            .dataTables_info {
                display: none;
            }

            .DataTables_Table_0_filter {
                float: right;
            }
        </style>
    @endsection
    @section('template_title')
        Elaborar Menu
    @endsection

    <div class="card">
        <div class="card-header bg-primary text-white">
            <div style="display: flex; justify-content: space-between; align-items: center;">

                <span id="card_title">
                    ELABORACIÓN DE MENÚ
                </span>
                <div class="float-right">
                    <a href="{{ route('menus.index') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                        <i class="uil-arrow-left"></i>
                        Volver
                    </a>
                </div>

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-4 mb-2">

                    <input type="text" class="form-control {{ $errors->has('tipomenu_id') ? ' is-invalid' : '' }}"
                        wire:model.defer="nombre" placeholder="Nombre del Menu">
                    @error('nombre')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-12 col-md-4 mb-2">

                    {!! Form::select('tipomenu_id', $tipos, null, [
                        'class' => 'form-select' . ($errors->has('tipomenu_id') ? ' is-invalid' : ''),
                        'placeholder' => 'Seleccione un tipo de Menu',
                        'wire:model.defer' => 'tipomenu_id',
                    ]) !!}
                    @error('tipomenu_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <input type="hidden" wire:model="descripcion">
                <div class="col-12 col-md-4 mb-2 d-grid gap-2" style="vertical-align: bottom">
                    @if ($menu_id == 0)
                        <button class="btn btn-success text-white" wire:click="save"><i class="fas fa-save"></i>
                            Registrar
                            Menu</button>
                    @else
                        @if ($dup == 1)
                            <button class="btn btn-success text-white" wire:click="save"><i class="fas fa-save"></i>
                                Registrar
                                Menu</button>
                        @else
                            <button class="btn btn-info text-white" wire:click="update"><i class="fas fa-save"></i>
                                Editar
                                Menu</button>
                        @endif

                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2 gx-1">
        <div class="col-12 col-md-6 mb-2">
            <div class="card" wire:ignore>
                <div class="card-header bg-info text-white">
                    <span>Elija los Productos</span>
                </div>
                <div class="card-body">

                    <div class="content">
                        <div class="accordion" id="categoriaItems">
                            @if ($categorias)
                                @php
                                    $colores = ['d5d8fc', 'b6f1e0', 'c4e7f1', 'feced8', 'ffebb3', 'd3d6d8'];
                                    $i = 0;
                                @endphp
                                @foreach ($categorias as $categoria)
                                    @if ($categoria->items->count() > 0)
                                        <div class="card mb-0">
                                            <div class="card-header" id="headingOne"
                                                style="background-color: #{{ $colores[$i] }};">
                                                <h5 class="m-0">
                                                    <a class="custom-accordion-title d-block pt-0 pb-0"
                                                        data-bs-toggle="collapse" href="#clp{{ $categoria->id }}"
                                                        aria-expanded="true" aria-controls="clp{{ $categoria->id }}">
                                                        <p class="text-secondary">{{ $categoria->nombre }}</p>
                                                    </a>
                                                </h5>
                                            </div>

                                            <div id="clp{{ $categoria->id }}" class="collapse"
                                                aria-labelledby="headingOne" data-bs-parent="#categoriaItems">
                                                <div class="card-body">
                                                    <table class="table table-striped dataTable">
                                                        <thead>
                                                            <tr>
                                                                <th>PRODCUTO</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($categoria->items as $item)
                                                                <tr>
                                                                    <td>
                                                                        {{ $item->nombre }}
                                                                    </td>
                                                                    <td align="right">
                                                                        <button class="btn btn-sm btn-outline-success"
                                                                            title="Agregar al Menu"
                                                                            wire:click="agregar({{ $item->id }})"><i
                                                                                class="uil-arrow-right"></i></button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>


                                                </div>
                                            </div>
                                        </div>
                                        @php
                                            if ($i < 5) {
                                                $i++;
                                            } else {
                                                $i = 0;
                                        } @endphp
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-2">
            <div class="card">
                <div class="card-header bg-success text-white text-center">
                    <span>Lista de Productos del Menu</span>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>CATEGORIA</th>
                                    <th>PRODUCTO</th>
                                    <th width='10'></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!is_null($itemsMenu))

                                    @php $i=0; @endphp
                                    @foreach ($itemsMenu as $item)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>
                                                {{ $item['categoria'] }}
                                            </td>
                                            <td>
                                                {{ $item['producto'] }}
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-danger" title="Eliminar"
                                                    wire:click="eliminar({{ $i }})"><i
                                                        class="uil-trash"></i></button>
                                            </td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
