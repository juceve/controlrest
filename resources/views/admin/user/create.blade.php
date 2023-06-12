@extends('layouts.app')

@section('template_title')
Nuevo Usuario
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">NUEVO USUARIO</h4>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        Datos del Usuario
        <div style="float: right">
            <a href="{{route('admin.users.index')}}" class="btn btn-sm btn-primary">
                <i class="uil-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{route('admin.users.store')}}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" name="name" class="form-control" placeholder="Nombre del Usuario" value="{{ old('name') }}">
                @error("name")
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Correo</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Correo Electrónico" value="{{ old('email') }}">
                @error("email")
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="sucursale_id" class="form-label">Sucursal</label>
                <select name="sucursale_id" class="form-control">
                    <option value="">Seleccione una sucursal</option>
                    @foreach ($sucursales as $sucursale)
                        <option value="{{$sucursale->id}}">{{$sucursale->nombre}}</option>
                    @endforeach
                    
                </select>
                @error("email")
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="avatar" class="form-label">Avatar</label>
                <input type="file" id="avatar" name="avatar" class="form-control" accept="image/*" value="{{ old('avatar') }}">
                @error("avatar")
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>

            <div class="mb-3">
            <button type="submit" class="btn btn-primary">Registrar Usuario</button>
            </div>
        </form>
    </div>
</div>
@endsection
