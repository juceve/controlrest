<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group mb-2
        @if (Auth::user()->sucursale_id)
            d-none
        @endif
        ">
            {{ Form::label('sucursale_id') }}
            {{ Form::text('sucursale_id', $ventasconfig->sucursale_id?$ventasconfig->sucursale_id:Auth::user()->sucursale_id, ['class' => 'form-control' . ($errors->has('sucursale_id') ? ' is-invalid' : ''), 'placeholder' => 'Sucursale Id']) }}
            {!! $errors->first('sucursale_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-2">
            <label for="">Nivel de Referencia para los precios</label>
            {{ Form::select('nivelcurso_id', $niveles,$ventasconfig->nivelcurso_id, ['class' => 'form-select' . ($errors->has('nivelcurso_id') ? ' is-invalid' : '')]) }}
            {!! $errors->first('nivelcurso_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>