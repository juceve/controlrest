<div>
    @section('template_title')
        Menu Programado
    @endsection
    <h4 class="text-center">PROGRAMACIÓN DE EVENTOS</h4>
    <hr>
    <div class="container d-none d-md-block text-center">
        <small class="text-info">(Seleccione una fecha)</small>
        <div id='calendar' wire:ignore></div>
    </div>

    <div class="form-group d-md-none">

        <div class="form-group mb-2">
            <label for="">Fecha:</label>
            <input type="date" id="fecMovil" class="form-control" value="{{ date('Y-m-d') }}">
        </div>
        <div class="d-grid gap-2">
            <button class="btn btn-info" type="button" onclick="newEvent(fecMovil.value)">Seleccionar</button>
        </div>
    </div>


    <!-- MODAL EVENTOS DEL DIA  -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel" aria-hidden="true"
        wire:ignore.self data-bs-backdrop="static" id="modalEventDay">
        <div class="modal-dialog modal-full-width">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="fullWidthModalLabel">PROGRAMACION DE EVENTO</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"
                        wire:click="cancelar"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="fecha" class="control-label col-form-label">Fecha:</label>
                        <input wire:model="fecha" type="date" class="form-control" name="fecha" id="fecha"
                            style="width:100%" required>
                        @error('fecha')
                            <span class="text-danger">{{ message }}</span>
                        @enderror

                    </div>

                    <div class="row mt-2 gx-1">
                        <div class="col-12 col-md-6 mb-2">
                            <div class="card" wire:ignore>
                                <div class="card-header bg-info text-white">
                                    <span>Elija los Productos</span>
                                </div>
                                <div class="card-body">

                                    <div class="content">
                                        <div class="accordion" id="tipomenuItems">
                                            @if ($tipomenus)
                                                @php
                                                    $colores = ['d5d8fc', 'b6f1e0', 'c4e7f1', 'feced8', 'ffebb3', 'd3d6d8'];
                                                    $i = 0;
                                                @endphp
                                                @foreach ($tipomenus as $tipomenu)
                                                    @if ($tipomenu->menuses->count() > 0)
                                                        <div class="card mb-0">
                                                            <div class="card-header" id="headingOne"
                                                                style="background-color: #{{ $colores[$i] }};">
                                                                <h5 class="m-0">
                                                                    <a class="custom-accordion-title d-block pt-0 pb-0"
                                                                        data-bs-toggle="collapse"
                                                                        href="#clp{{ $tipomenu->id }}"
                                                                        aria-expanded="true"
                                                                        aria-controls="clp{{ $tipomenu->id }}">
                                                                        <p class="text-secondary">
                                                                            {{ $tipomenu->nombre }}</p>
                                                                    </a>
                                                                </h5>
                                                            </div>

                                                            {{-- <div id="clp{{$tipomenu->id}}" class="collapse"
                                                    aria-labelledby="headingOne" data-bs-parent="#tipomenuItems">
                                                    <div class="card-body">
                                                        <ul class="list-group">
                                                            @foreach ($tipomenu->menuses as $menu)
                                                            <li
                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                {{ $menu->nombre }}
                                                                <button class="btn btn-sm btn-success"
                                                                    wire:click="seleccionarMenu({{$menu->id}})">
                                                                    <i class="uil-arrow-down"></i> Seleccionar
                                                                </button>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div> --}}

                                                            <div id="clp{{ $tipomenu->id }}" class="collapse"
                                                                aria-labelledby="headingOne"
                                                                data-bs-parent="#tipomenuItems">
                                                                <div class="card-body">
                                                                    <table class="table table-bordered dataTable">
                                                                        <thead>
                                                                            <th>PRODUCTO</th>
                                                                            <th></th>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($tipomenu->menuses as $menu)
                                                                                <tr>
                                                                                    <td>
                                                                                        {{ $menu->nombre }}
                                                                                    </td>
                                                                                    <td align="right">
                                                                                        <button
                                                                                            class="btn btn-sm btn-success"
                                                                                            wire:click="seleccionarMenu({{ $menu->id }})">
                                                                                            <i
                                                                                                class="uil-arrow-right"></i>
                                                                                            Seleccionar
                                                                                        </button>
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
                                    <span>MENUS SELECCIONADOS</span>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive" style="font-size: 12px;">
                                        <table class="table table-bordered table-sm table-striped">
                                            <thead class="table-success">
                                                <tr>
                                                    <th>MENU</th>
                                                    <th>DESCRIPCION</th>
                                                    <th>TIPO</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!is_null($arrMenus))
                                                    @php
                                                        $i = 0;
                                                    @endphp
                                                    @foreach ($arrMenus as $item)
                                                        <tr>
                                                            <td>{{ $item['nombre'] }}</td>
                                                            <td>{{ $item['descripcion'] }}</td>
                                                            <td>{{ $item['tipomenu'] }}</td>
                                                            <td>
                                                                <button class="btn btn-sm btn-danger"
                                                                    title="Eliminar de la lista"><i class="uil-trash"
                                                                        wire:click='unsetArray({{ $i }})'></i></button>
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
                <div class="container">
                    <div class="row mb-3 mr-3">
                        <div class="col-12 text-end">
                            <button class="btn btn-secondary" data-bs-dismiss="modal" style="width: 200px"
                                data-bs-backdrop="false" wire:click="cancelar">Cancelar</button>
                            <button class="btn btn-danger d-none" id="eliminar" style="width: 200px"
                                wire:click="destroy" onclick="loading()">Eliminar</button>
                            <button class="btn btn-primary" style="width: 200px" wire:click="save"
                                onclick="loading()">Registrar
                                Evento</button>
                        </div>
                    </div>
                </div>

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>




{{-- <div wire:ignore.self class="modal fade" id="modalEventDay" tabindex="-1" aria-labelledby="exampleModalLabel"
        data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">DATOS DEL EVENTO</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="cancelar"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="fecha" class="control-label col-form-label">Fecha:</label>
                        <input wire:model="fecha" type="date" class="form-control" name="fecha" id="fecha"
                            style="width:100%" required>
                        @error('fecha')
                        <span class="text-danger">{{ message }}</span>
                        @enderror

                    </div>
                    <h2 class="h5 text-primary">MENUS DISPONIBLES</h2>

                    <div class="row mt-2 gx-1">
                        <div class="col-12 col-md-6 mb-2">
                            <div class="card" wire:ignore>
                                <div class="card-header bg-info text-white">
                                    <span>Elija los Productos</span>
                                </div>
                                <div class="card-body">

                                    <div class="content">
                                        <div class="accordion" id="tipomenuItems">
                                            @if ($tipomenus)
                                            @php
                                            $colores = array('d5d8fc','b6f1e0','c4e7f1','feced8','ffebb3','d3d6d8');
                                            $i = 0;
                                            @endphp
                                            @foreach ($tipomenus as $tipomenu)
                                            @if ($tipomenu->menuses->count() > 0)
                                            <div class="card mb-0">
                                                <div class="card-header" id="headingOne"
                                                    style="background-color: #{{$colores[$i]}};">
                                                    <h5 class="m-0">
                                                        <a class="custom-accordion-title d-block pt-0 pb-0"
                                                            data-bs-toggle="collapse" href="#clp{{$tipomenu->id}}"
                                                            aria-expanded="true" aria-controls="clp{{$tipomenu->id}}">
                                                            <p class="text-secondary">{{$tipomenu->nombre}}</p>
                                                        </a>
                                                    </h5>
                                                </div>

                                                <div id="clp{{$tipomenu->id}}" class="collapse"
                                                    aria-labelledby="headingOne" data-bs-parent="#tipomenuItems">
                                                    <div class="card-body">
                                                        <ul class="list-group">
                                                            @foreach ($tipomenu->menuses as $menu)
                                                            <li
                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                {{ $menu->nombre }}
                                                                <button class="btn btn-sm btn-success"
                                                                    wire:click="seleccionarMenu({{$menu->id}})">
                                                                    <i class="uil-arrow-down"></i> Seleccionar
                                                                </button>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            @php
                                            if($i<5){ $i++; }else{ $i=0; } @endphp @endif @endforeach @endif </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mb-2">
                                <div class="card">
                                    <div class="card-header bg-success text-white text-center">
                                        <span>MENUS SELECCIONADOS</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive" style="font-size: 12px;">
                                            <table class="table table-bordered table-sm table-striped">
                                                <thead class="table-success">
                                                    <tr>
                                                        <th>MENU</th>
                                                        <th>DESCRIPCION</th>
                                                        <th>TIPO</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!is_null($arrMenus))
                                                    @php
                                                    $i=0;
                                                    @endphp
                                                    @foreach ($arrMenus as $item)
                                                    <tr>
                                                        <td>{{$item['nombre']}}</td>
                                                        <td>{{$item['descripcion']}}</td>
                                                        <td>{{$item['tipomenu']}}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-danger"
                                                                title="Eliminar de la lista"><i class="uil-trash"
                                                                    wire:click='unsetArray({{$i}})'></i></button>
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
                    <hr>
                    <div class="row">
                        <div class="col-12 text-end">
                            <button class="btn btn-secondary" data-bs-dismiss="modal" style="width: 200px"
                                data-bs-backdrop="false" wire:click="cancelar">Cancelar</button>
                            <button class="btn btn-danger d-none" id="eliminar" style="width: 200px"
                                wire:click="destroy" onclick="loading()">Eliminar</button>
                            <button class="btn btn-primary" style="width: 200px" wire:click="save"
                                onclick="loading()">Registrar
                                Evento</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> --}}

<!-- FIN MODALES -->

@section('js')
    <script>
        // INICIALIZACION DEL COMPONENTE CALENDAR CON TODAS SUS CONFIGURACIONES INICIALES

        var d = new Date();
        var mes = d.getMonth() + 1;
        var anio = d.getFullYear();
        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: 'dayGridMonth',
            customButtons: {
                btnToday: {
                    text: 'Hoy',
                    click: function() {
                        calendar.today();
                        var titulo = $('#fc-dom-1').html();
                        const myArray = titulo.split(" ");
                        Livewire.emit('cambiaMes', myArray[0].toUpperCase());
                    }
                }
            },
            headerToolbar: {
                center: 'title',
                end: 'dayGridMonth,timeGridWeek',
                start: 'prev btnToday next'
            },
            buttonText: {
                'week': 'Semana',
                'month': 'Mes',
                'day': 'Dia',
            },
            timeZone: 'America/La_Paz',
            selectable: true,
            locale: 'es',
            slotDuration: "00:30:00",
            slotMinTime: "08:00:00",
            slotMaxTime: "15:00:00",
            eventSources: [{
                url: "{{ route('events') }}",
            }],

            dateClick: function(info) {

                newEvent(info.dateStr);
            },

        });
        calendar.render();



        /////////////////////////////////////////////////////////////////////////////////////////
        // FUNCIONES //////////////////////////////////
        function newEvent(dateSelected) {
            limpiaModal();
            var fecha = dateSelected.slice(0, 10);
            @this.set('fecha', fecha);
            $('#fecha').val(fecha);
            $('#modalEventDay').modal('show');
        }

        function limpiaModal() {
            $('#fecha').val('');
        }

        Livewire.on('success', message => {
            Swal.fire(
                'Exito!',
                message,
                'success'
            )
        });

        Livewire.on('error', message => {
            // $('#modalEventDay').modal('hide');
            Swal.fire(
                'Error',
                message,
                'error'
            )
        });
        Livewire.on('onDelete', function() {
            $('#eliminar').removeClass('d-none');
        });
    </script>
@endsection



</div>
