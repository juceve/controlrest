<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group mb-2">
            {{ Form::label('nombre') }}
            {{ Form::text('nombre', $sucursale->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre']) }}
            {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-2">
            {{ Form::label('direccion') }}
            {{ Form::text('direccion', $sucursale->direccion, ['class' => 'form-control' . ($errors->has('direccion') ? ' is-invalid' : ''), 'placeholder' => 'Direccion']) }}
            {!! $errors->first('direccion', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-2">
            {{ Form::label('telefono') }}
            {{ Form::text('telefono', $sucursale->telefono, ['class' => 'form-control' . ($errors->has('telefono') ? ' is-invalid' : ''), 'placeholder' => 'Telefono']) }}
            {!! $errors->first('telefono', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-2">
            {{ Form::label('horalimitepedidos') }}
            {{ Form::time('horalimitepedidos', $sucursale->horalimitepedidos, ['class' => 'form-control' . ($errors->has('horalimitepedidos') ? ' is-invalid' : '')]) }}
            {!! $errors->first('horalimitepedidos', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-2 d-none">
            {{ Form::label('estado') }}
            {{ Form::text('estado', $sucursale->estado?$sucursale->estado:'1', ['class' => 'form-control' . ($errors->has('estado') ? ' is-invalid' : ''), 'placeholder' => 'Estado']) }}
            {!! $errors->first('estado', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Registrar') }}</button>
    </div>
</div>