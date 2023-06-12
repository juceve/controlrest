<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group mb-2">
            {{ Form::label('fecha') }}
            {{ Form::date('fecha', $feriado->fecha, ['class' => 'form-control' . ($errors->has('fecha') ? ' is-invalid' : ''), 'placeholder' => 'Fecha']) }}
            {!! $errors->first('fecha', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-2">
            {{ Form::label('motivo') }}
            {{ Form::text('motivo', $feriado->motivo, ['class' => 'form-control' . ($errors->has('motivo') ? ' is-invalid' : ''), 'placeholder' => 'Motivo']) }}
            {!! $errors->first('motivo', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-2 d-none">
            {{ Form::label('sucursale_id') }}
            {{ Form::text('sucursale_id', $feriado->sucursale_id?$feriado->sucursale_id:Auth::user()->sucursale_id, ['class' => 'form-control' . ($errors->has('sucursale_id') ? ' is-invalid' : ''), 'placeholder' => 'Sucursale Id']) }}
            {!! $errors->first('sucursale_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</div>