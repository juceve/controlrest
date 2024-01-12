<div>
    @section('template_title')
        PROMOCION DE ALUMNOS
    @endsection

    <div class="card mb-2">
        <div class="card-header bg-primary text-white">
            PROMOCION DE ESTUDIANTES
        </div>
        <div class="card-body">
            <label for="">Seleccione un Curso</label>
            <div class="row">
                <div class="col-12 col-md-3">
                    <select class="form-select" id="select1" wire:model="selCurso">
                        <option value="">Seleccione un Curso</option>
                        @foreach ($cursos as $curso)
                            <option value="{{ $curso->id }}">{{ $curso->nivel }} - {{ $curso->curso }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($estudiantes)
                    <div class="col-12 col-md-3 d-grid">
                        <button class="btn btn-primary" wire:click='selTodo'>Seleccionar Todo</button>
                    </div>
                    <div class="col-12 col-md-3 d-grid" wire:click='deseleccionar'>
                        <button class="btn btn-secondary">Quitar selecciones</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @if ($estudiantes)
        <div class="card">
            <div class="card-header ">
                <h6>Seleccione los estudiantes que seran promovidos o desafiliados</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="table-secondary fw-bold">
                                <td style="width: 20px;">Seleccione</td>
                                <td>Estudiante</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($estudiantes as $item)
                                <tr>
                                    <td class="text-center">
                                        <input class="form-check-input" type="checkbox" value="{{ $item->id }}"
                                            wire:model="selEstudiantes" />
                                    </td>
                                    <td>{{ $item->nombre }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <label class="text-success">Seleccione el curso a Promover</label>
                <div class="row">
                    <div class="col-12 col-md-3">
                        <select class="form-select" id="select2" wire:model="selCursoPromo">
                            <option value="">Seleccione un Curso</option>
                            @foreach ($cursos as $curso)
                                <option value="{{ $curso->id }}">{{ $curso->nivel }} - {{ $curso->curso }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3 d-grid">
                        <button class="btn btn-success" id="promover" onclick="promover()">Promover <i
                                class="uil-check-circle"></i></button>
                    </div>
                    <div class="col-12 col-md-3 d-grid">
                        <button class="btn btn-danger" id="desafiliar" onclick="desafiliar()">Desafiliar <i
                                class="uil-folder-times"></i></button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@section('js')
    <script>
        function promover() {
            var select1 = document.getElementById('select1');
            var select2 = document.getElementById('select2');
            var textSelect1 = select1.options[select1.selectedIndex].text;
            var textSelect2 = select2.options[select2.selectedIndex].text;
            // console.log(select2.value);
            if (select2.value != "") {
                Swal.fire({
                    title: "PROMOCIÓN DE ESTUDIANTES",
                    text: "Promover de: " + textSelect1 + " a " + textSelect2 + "?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Promover",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('promover');
                    }
                });
            } else {
                Swal.fire({
                    title: "Atención!",
                    text: "Debe seleccionar el curso donde seran promovido los estudiantes",
                    icon: "error"
                });
            }
        }

        function desafiliar() {           
                Swal.fire({
                    title: "DESAFIALIAR ESTUDIANTES",
                    text: "Esta seguro de Desafiliar los estudiantes seleccionados?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Desafiliar",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('desafiliar');
                    }
                });
            
        }
    </script>
@endsection
