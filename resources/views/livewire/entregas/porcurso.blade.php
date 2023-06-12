<div>
    @section('template_title')
        Entregas Por Curso
    @endsection
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">ENTREGAS POR CURSO</h4>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-primary text-white">
            Seleccione los Parametros
        </div>
        <div class="card-body">
            <label for="">CURSOS</label>
            <div class="row">
                <div class="col-12 col-md-4 form-group mb-2">
                    <select name="" id="" class="form-select" wire:model='selCurso'>
                        <option value="">Seleccione un Curso</option>
                        @foreach ($cursos as $curso)
                            <option value="{{ $curso->id }}">{{ $curso->nivel }} - {{ $curso->curso }}</option>
                        @endforeach

                    </select>

                </div>
                <div class="col-12 col-md-3 d-grid mb-2">
                    <button class="btn btn-info" wire:click='buscarCurso'><i class="uil-search"></i> Buscar </button>
                </div>
            </div>

        </div>
    </div>

    @if ($html)
        <div class="card" wire:ignore>
            <div class="card-body">
                <h4 class="text-center">LISTADO DE ALUMNOS</h4>
                <h2 class="h5 text-center">Curso: {{ $pedidocurso->nombre }}</h2>
                <h4 class="h5 text-center">FECHA: {{ date('Y-m-d') }}</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="listaCurso">
                        <thead class="table-primary">
                            <tr>
                                <td style="width: 50px;">NRO</td>
                                <td>ESTUDIANTE</td>
                                <td>PRODUCTO</td>
                                <td style="width: 100px;" align="center">FALTA</td>
                                <td style="width: 100px;" align="center">ENTREGA</td>
                                <td style="width: 100px;" align="center">LICENCIA</td>
                            </tr>
                        </thead>
                        <tbody id="htmlBody" style="height: 10px !important; overflow: scroll; ">

                        </tbody>
                    </table>
                </div>


            </div>
            <div class="container text-center d-grid mt-2">
                <button class="btn btn-success" onclick="revision();" >FINALIZA REVISIÓN DE CURSO</button>
            </div>
        </div>
    @endif

</div>
@section('js')
    <script>
        function revision() {
            Swal.fire({
                title: 'FINALIZAR REVISIÓN DE CURSO',
                text: "Esta seguro de realizar esta operación?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, continuar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    finalizarCurso();
                }
            });

        }

        function finalizarCurso() {
            $.blockUI({
                message: '<h1 class="text-success"><div class="spinner-grow text-success" role="status"></div> Espere por favor...</h1>'
            });
            var tabla = $("#listaCurso");
            tabla.find("tbody tr").each(function() {
                var estudiante_id = $(this).find("td:eq(1) input").val();
                var menu_id = $(this).find("td:eq(2) input").val();
                var falta = $(this).find("td:eq(3) input").is(':checked');
                var entrega = $(this).find("td:eq(4) input").is(':checked');
                var licencia = $(this).find("td:eq(5) input").is(':checked');
                Livewire.emit('cargaPedidos', estudiante_id, menu_id, falta, entrega, licencia);
            });
            // Livewire.emit('prueba');
            Livewire.emit('entregar');
        }

        Livewire.on('htmlBody', html => {
            $('#htmlBody').empty();
            $('#htmlBody').html(html);
        });

        function entregar(estudiante_id) {
            Livewire.emit('entregar', estudiante_id);
        }

        Livewire.on('ocultaBtn', id => {
            $('#btn' + id).empty();
            $('#btn' + id).html('<small class="text-success">Entregado!</small>');
        });
    </script>
@endsection
