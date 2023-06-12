<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('tipomenu_id') }}
            {{ Form::text('tipomenu_id', $tipobonoanuale->tipomenu_id, ['class' => 'form-control' . ($errors->has('tipomenu_id') ? ' is-invalid' : ''), 'placeholder' => 'Tipomenu Id']) }}
            {!! $errors->first('tipomenu_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('precio') }}
            {{ Form::text('precio', $tipobonoanuale->precio, ['class' => 'form-control' . ($errors->has('precio') ? ' is-invalid' : ''), 'placeholder' => 'Precio']) }}
            {!! $errors->first('precio', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('sucursale_id') }}
            {{ Form::text('sucursale_id', $tipobonoanuale->sucursale_id, ['class' => 'form-control' . ($errors->has('sucursale_id') ? ' is-invalid' : ''), 'placeholder' => 'Sucursale Id']) }}
            {!! $errors->first('sucursale_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>