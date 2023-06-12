<div class="box box-info padding-1">
    <div class="box-body">

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group mb-3">
                    {{ Form::label('nombre') }}
                    {{ Form::text('nombre', $item->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? '
                    is-invalid'
                    : ''), 'placeholder' => 'Nombre']) }}
                    {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <label for="exampleInputIconRight">Categoria</label>
                {{ Form::select('catitem_id', $categorias,$item->catitem_id, ['class' => 'form-select' .
                ($errors->has('catitem_id') ? ' is-invalid' : ''), 'placeholder' => 'Seleccione una categoria']) }}
                {!! $errors->first('catitem_id', '<div class="invalid-feedback">:message</div>') !!}

                @error('catitem_id')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
            <div class="col-12">
                <div class="form-group mb-3">
                    {{ Form::label('descripcion') }}
                    {{ Form::text('descripcion', $item->descripcion, ['class' => 'form-control' .
                    ($errors->has('descripcion') ? ' is-invalid' : ''), 'placeholder' => 'Descripcion']) }}
                    {!! $errors->first('descripcion', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group mb-3">
                    {{ Form::label('imagen') }}
                    <div class="mb-3">
                        <input class="form-control" type="file" id="imagen" name="imagen" accept="image/*"
                            onchange="preview_image(event)">
                    </div>
                    <div class="mb-3">
                        <img id="output_image" style="max-height: 100px" />
                    </div>
                    {!! $errors->first('imagen', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12">
                <div class="form-group mb-3 d-none">
                    {{ Form::text('sucursale_id', $item->sucursale_id?$item->sucursale_id:Auth::user()->sucursale_id, ['class' => 'form-control' .
                    ($errors->has('sucursale_id') ? ' is-invalid' : ''), 'placeholder' => 'sucursale_id']) }}
                    {!! $errors->first('sucursale_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6 d-none">
                <div class="form-group mb-3">
                    {{ Form::label('estado') }}
                    {{ Form::text('estado', $item->estado?$item->estado:true, ['class' => 'form-control' .
                    ($errors->has('estado') ? ' is-invalid' : ''), 'placeholder' => 'Estado']) }}
                    {!! $errors->first('estado', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>
    </div>

</div>