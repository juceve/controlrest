@extends('layouts.app')

@section('template_title')
ROLES
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">ROLES DE USUARIO</h4>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        Listado de Roles
        <div style="float: right">
            @can('admin.roles.create')
            <a href="{{route('admin.roles.create')}}" class="btn btn-sm btn-info">
                <i class="uil-plus"></i> Nuevo
            </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered dataTable">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>NOMBRE</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                <tr>
                    <td>{{$role->id}}</td>
                    <td>{{$role->name}}</td>
                    <td align="right">
                        <form action="{{route('admin.roles.destroy',$role)}}" onsubmit="return false" method="POST"
                            class="delete">
                            @csrf
                            @method('DELETE')
                            @can('admin.roles.edit')
                                <a href="{{route('admin.roles.edit',$role->id)}}" class="btn btn-sm btn-primary">Editar</a>
                            @endcan
                            @can('admin.roles.destroy')
                                 <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{
                                __('Borrar') }}</button>
                            @endcan                            
                           
                        </form>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
@endsection