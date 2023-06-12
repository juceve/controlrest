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
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}"  role="form" enctype="multipart/form-data">
            {{ method_field('PATCH') }}
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" name="name" class="form-control" placeholder="Nombre del Usuario" value="{{$user->name}}">
                @error("name")
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Correo</label>
                <p class="form-control">{{$user->email}}</p>            
                
            </div>
            <div class="mb-3">
                <label for="sucursale_id" class="form-label">Sucursal</label>
                {!! Form::select('sucursale_id', $sucursales, $user->sucursale_id?$user->sucursale_id:null, ["class"=>"form-control"]) !!}
                @error("sucursle_id")
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
                <label for="estado" class="form-label">Estado</label>
                {!! Form::select('estado', ["0"=>"Inactivo","1"=>"Activo"], $user->estado?$user->estado:null, ["class"=>"form-control"]) !!}
                @error("sucursle_id")
                    <small class="text-danger">{{$message}}</small>
                @enderror
            </div>

            <div class="mb-3">
            <button type="submit" class="btn btn-primary">Editar Usuario</button>
            </div>
        </form>
    </div>
</div>
@endsection
