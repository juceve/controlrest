<div class="box box-info padding-1">
    <div class="box-body">

        <div class="form-group mb-2">
            {{ Form::label('nombre') }}
            {{ Form::text('nombre', $nivelcurso->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? '
            is-invalid' : ''), 'placeholder' => 'Nombre']) }}
            {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-2">
            
            @php
            $sucursal = Auth::user()->sucursale_id;
            @endphp
            @if (is_null($sucursal))
            {{ Form::label('Sucursal') }}
            {{ Form::select('sucursale_id', $sucursales,$nivelcurso->sucursale_id?$nivelcurso->sucursale_id:$sucursal,
            ['class' => 'form-select' . ($errors->has('sucursale_id') ? ' is-invalid' : ''), 'placeholder' =>
            'Seleccione una sucursal']) }}
            @else
            {{ Form::text('sucursale_id', $nivelcurso->sucursale_id?$nivelcurso->sucursale_id:$sucursal, ['class' => 'form-control d-none' . ($errors->has('sucursale_id') ? '
            is-invalid' : ''), 'placeholder' => 'Sucursal']) }}
            @endif

            {!! $errors->first('sucursale_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Registrar</button>
    </div>
</div>