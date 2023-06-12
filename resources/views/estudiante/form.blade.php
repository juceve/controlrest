<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group mb-3 d-none">
            {{ Form::label('codigo') }}
            {{ Form::text('codigo', $estudiante->codigo, ['class' => 'form-control' . ($errors->has('codigo') ? ' is-invalid' : ''), 'placeholder' => 'Codigo']) }}
            {!! $errors->first('codigo', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-3">
            {{ Form::label('nombre') }}
            {{ Form::text('nombre', $estudiante->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre']) }}
            {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-3">
            {{ Form::label('cedula') }}
            {{ Form::text('cedula', $estudiante->cedula, ['class' => 'form-control' . ($errors->has('cedula') ? ' is-invalid' : ''), 'placeholder' => 'Cedula']) }}
            {!! $errors->first('cedula', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-3">
            {{ Form::label('correo') }}
            {{ Form::text('correo', $estudiante->correo, ['class' => 'form-control' . ($errors->has('correo') ? ' is-invalid' : ''), 'placeholder' => 'Correo']) }}
            {!! $errors->first('correo', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-3">
            {{ Form::label('telefono') }}
            {{ Form::text('telefono', $estudiante->telefono, ['class' => 'form-control' . ($errors->has('telefono') ? ' is-invalid' : ''), 'placeholder' => 'Telefono']) }}
            {!! $errors->first('telefono', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-3">
            {{ Form::label('tutore_id') }}
            <select name="tutore_id" id="curso_id" class="form-control select2" >
                <option value="">Seleccione un Tutor</option>
                @foreach ($tutores as $tutor)
                <option value="{{$tutor->id}}" @php if($tutor->id == $estudiante->turore_id) {echo 'selected';} @endphp>{{$tutor->nombre}}</option>    
                @endforeach
                
            </select>
            {!! $errors->first('tutore_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-3">
            {{ Form::label('curso_id') }}
            {{-- {{ Form::select('curso_id', $cursos,$estudiante->curso_id, ['class' => 'form-select' . ($errors->has('curso_id') ? ' is-invalid' : ''), 'placeholder' => 'Curso Id']) }} --}}
            <select name="curso_id" id="curso_id" class="form-select">
                <option value="">Seleccione un curso</option>
                @foreach ($cursos as $item)
                <option value="{{$item->id}}" @php if($item->id == $estudiante->curso_id) {echo 'selected';} @endphp>{{$item->nombre .' - '.$item->nivelcurso->nombre}}</option>    
                @endforeach
                
            </select>
            {!! $errors->first('curso_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-3 d-none">
            {{ Form::label('verificado') }}
            {{ Form::text('verificado', $estudiante->verificado?$estudiante->verificado:true, ['class' => 'form-control' . ($errors->has('verificado') ? ' is-invalid' : ''), 'placeholder' => 'Verificado']) }}
            {!! $errors->first('verificado', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    
</div>