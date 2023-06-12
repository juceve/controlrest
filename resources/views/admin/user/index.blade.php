@extends('layouts.app')

@section('template_title')
Usuarios
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">USUARIOS DEL SISTEMA</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            Listado de usuarios
            <div style="float: right">
                @can('admin.users.create')
                <a href="{{route('admin.users.create')}}" class="btn btn-sm btn-info">
                    <i class="uil-plus"></i> Nuevo
                </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered dataTable">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>CORREO</th>
                            <th>SUCURSAL</th>
                            <th>ESTADO</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($users->count())
                        @foreach ($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->sucursale_id?$user->sucursale->nombre:'No asignado'}}</td>
                            <td>
                                @if ($user->estado)
                                <span class="badge badge-success-lighten rounded-pill">Activo</span>
                                @else
                                <span class="badge badge-danger-lighten rounded-pill">Inactivo</span>
                                @endif
                            </td>
                            <td align="right">
                                @can('admin.users.edit')
                                <form action="{{route('admin.users.resetPassword',$user->id)}}" onsubmit="return false"
                                    class="reset" method="POST">
                                    @csrf
                                    <a href="{{route('admin.users.edit',$user)}}"
                                        class="btn btn-sm btn-warning" title="Editar"><i class="uil-edit"></i> </a>
                                    <a href="{{route('admin.users.asignaRol',$user->id)}}"
                                        class="btn btn-sm btn-primary" title="Asignar Rol"><i class="uil-shield-check"></i> </a>
                                    <button type="submit" class="btn btn-sm btn-info" title="Reset Password"><i
                                            class="uil-key-skeleton"></i> </button>
                                </form>

                                @endcan
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4" align="center">No existen registros para mostrar.</td>
                        </tr>
                        @endif


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection