<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('gestion') }}
            {{ Form::text('gestion', $bonoanuale->gestion, ['class' => 'form-control' . ($errors->has('gestion') ? ' is-invalid' : ''), 'placeholder' => 'Gestion']) }}
            {!! $errors->first('gestion', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('estudiante_id') }}
            {{ Form::text('estudiante_id', $bonoanuale->estudiante_id, ['class' => 'form-control' . ($errors->has('estudiante_id') ? ' is-invalid' : ''), 'placeholder' => 'Estudiante Id']) }}
            {!! $errors->first('estudiante_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('tipomenu_id') }}
            {{ Form::text('tipomenu_id', $bonoanuale->tipomenu_id, ['class' => 'form-control' . ($errors->has('tipomenu_id') ? ' is-invalid' : ''), 'placeholder' => 'Tipomenu Id']) }}
            {!! $errors->first('tipomenu_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('pago_id') }}
            {{ Form::text('pago_id', $bonoanuale->pago_id, ['class' => 'form-control' . ($errors->has('pago_id') ? ' is-invalid' : ''), 'placeholder' => 'Pago Id']) }}
            {!! $errors->first('pago_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>