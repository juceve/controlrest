<div>
    @section('template_title')
    IMPORTACION MASIVA
    @endsection

    <div class="card">
        <div class="card-header">
            <h2 class="h5">IMPORTACION MASIVA DE ESTUDIANTES </h2>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-4 mb-3">
                    <label for="archivo" class="form-label">Subir archivo TXT <small>(Separado por salto de
                            linea)</small></label>
                    <input type="file" id="archivo" class="form-control" wire:model='file'>
                </div>
                <div class="col-12 col-md-4 mb-3">
                    <label for="curso" class="form-label">Seleccione Curso</label>
                    <select class="form-select" wire:model='selCurso'>
                        <option value="">Seleccione un curso</option>
                        @foreach ($cursos as $curso)
                         <option value="{{$curso->id}}">{{$curso->nivelcurso->nombre ." - ". $curso->nombre}}</option>   
                        @endforeach                        
                    </select>
                </div>
                <div class="col-12 col-md-4 mb-3">
                    <label for="curso" class="form-label">Seleccione Tutor</label>
                    <select class="form-select" wire:model='selTutor'>
                        <option value="">Seleccione un tutor</option>
                        @foreach ($tutores as $tutor)
                         <option value="{{$tutor->id}}">{{$tutor->nombre}}</option>   
                        @endforeach                        
                    </select>
                </div>
                <div class="col-12 col-md-4 mb-2 d-grid gap-2">
                    <button class="btn btn-primary" wire:click='ejecutar' wire:loading.attr="disabled">Ejecutar!</button>
                </div>
            </div>

        </div>
    </div>
</div>