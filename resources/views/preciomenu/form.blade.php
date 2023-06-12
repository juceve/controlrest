<div class="box box-info padding-1">
    <div class="box-body">

        <div class="form-group mb-3">
            <label for="">NIVEL DE CURSO</label>
            {{ Form::select('nivelcurso_id', $niveles,$preciomenu->nivelcurso_id, ['class' => 'form-select' .
            ($errors->has('nivelcurso_id') ? ' is-invalid' : ''), 'placeholder' => 'Seleccione un nivel']) }}
            {!! $errors->first('nivelcurso_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-3">TIPO DE MENU</label>
            {{ Form::select('tipomenu_id', $tipos,$preciomenu->tipomenu_id, ['class' => 'form-select' .
            ($errors->has('tipomenu_id') ? ' is-invalid' : ''), 'placeholder' => 'Seleccione un tipo de menu']) }}
            {!! $errors->first('tipomenu_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-3">
            <label for="">PRECIO {{$moneda->abreviatura}}</label>
            {{ Form::number('precio', $preciomenu->precio, ["step"=>".01", 'class' => 'form-control' .
            ($errors->has('precio') ? ' is-invalid' : ''), 'placeholder' => 'Precio']) }}
            {!! $errors->first('precio', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-3">
            <label for="">PRECIO {{$moneda->abreviatura}} POR MAYOR </label>
            {{ Form::number('preciopm', $preciomenu->preciopm, ['step'=>'.01', 'class' => 'form-control' .
            ($errors->has('preciopm') ? ' is-invalid' : ''), 'placeholder' => 'Precio por Mayor']) }}
            {!! $errors->first('preciopm', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group mb-3">
            <label for="">CANT. MINIMA MAYOREO</label>
            {{ Form::number('cantmin', $preciomenu->cantmin, ['class' => 'form-control' . ($errors->has('cantmin') ? '
            is-invalid' : ''), 'placeholder' => 'Cantidad mínima para mayoreo']) }}
            {!! $errors->first('cantmin', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="d-none">
            {{ Form::text('sucursale_id', Auth::user()->sucursale_id, ['class' => 'form-control' .
            ($errors->has('sucursale_id') ? ' is-invalid' : ''), 'placeholder' => 'Sucursal']) }}
        </div>
    </div>

</div>