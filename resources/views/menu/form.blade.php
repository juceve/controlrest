<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group mb-3">
                    <label for="">Nombre de Menu</label>
                    {{ Form::text('nombre', $menu->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? '
                    is-invalid' : ''), 'placeholder' => 'Nombre']) }}
                    {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group mb-3">
                    <label for="">Tipo de Menu</label>
                    {{ Form::select('tipomenu_id', $tipos ,$menu->tipomenu_id, ['class' => 'form-select' .
                    ($errors->has('tipomenu_id') ? ' is-invalid' : ''), 'placeholder' => 'Seleccione un tipo']) }}
                    {!! $errors->first('tipomenu_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>


        <div class="form-group d-none">
            {{ Form::label('descripcion') }}
            {{ Form::text('descripcion', $menu->descripcion, ['class' => 'form-control' . ($errors->has('descripcion') ?
            ' is-invalid' : ''), 'placeholder' => 'Descripcion']) }}
            {!! $errors->first('descripcion', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>