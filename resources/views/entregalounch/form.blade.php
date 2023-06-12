<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('fechaentrega') }}
            {{ Form::text('fechaentrega', $entregalounch->fechaentrega, ['class' => 'form-control' . ($errors->has('fechaentrega') ? ' is-invalid' : ''), 'placeholder' => 'Fechaentrega']) }}
            {!! $errors->first('fechaentrega', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('detallelonchera_id') }}
            {{ Form::text('detallelonchera_id', $entregalounch->detallelonchera_id, ['class' => 'form-control' . ($errors->has('detallelonchera_id') ? ' is-invalid' : ''), 'placeholder' => 'Detallelonchera Id']) }}
            {!! $errors->first('detallelonchera_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('menu_id') }}
            {{ Form::text('menu_id', $entregalounch->menu_id, ['class' => 'form-control' . ($errors->has('menu_id') ? ' is-invalid' : ''), 'placeholder' => 'Menu Id']) }}
            {!! $errors->first('menu_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('venta_id') }}
            {{ Form::text('venta_id', $entregalounch->venta_id, ['class' => 'form-control' . ($errors->has('venta_id') ? ' is-invalid' : ''), 'placeholder' => 'Venta Id']) }}
            {!! $errors->first('venta_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('user_id') }}
            {{ Form::text('user_id', $entregalounch->user_id, ['class' => 'form-control' . ($errors->has('user_id') ? ' is-invalid' : ''), 'placeholder' => 'User Id']) }}
            {!! $errors->first('user_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('sucursale_id') }}
            {{ Form::text('sucursale_id', $entregalounch->sucursale_id, ['class' => 'form-control' . ($errors->has('sucursale_id') ? ' is-invalid' : ''), 'placeholder' => 'Sucursale Id']) }}
            {!! $errors->first('sucursale_id', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>