<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group mb-2">
            {{ Form::label('Nombre Curso') }}
            {{ Form::text('nombre', $curso->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre curso']) }}
            {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-2">
            {{ Form::label('Nivel') }}
            {{ Form::select('nivelcurso_id', $niveles,$curso->nivelcurso_id?$curso->nivelcurso_id:null, ['class' => 'form-select' . ($errors->has('nivelcurso_id') ? ' is-invalid' : '')]) }}
            {!! $errors->first('nivelcurso_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Registrar</button>
    </div>
</div>