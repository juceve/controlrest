@extends('layouts.app')

@section('template_title')
Sucursales
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">SUCURSALES</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            Listado de Sucursales
                        </span>

                        <div class="float-right">
                            @can('admin.sucursales.index')
                            <a href="{{ route('admin.sucursales.create') }}" class="btn btn-info btn-sm float-right"
                                data-placement="left">
                                <i class="uil-plus"></i> Nuevo
                            </a>
                            @endcan

                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover dataTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nombre</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i=0;
                                @endphp
                                @foreach ($sucursales as $sucursale)
                                <tr>
                                    <td>{{ ++$i }}</td>

                                    <td>{{ $sucursale->nombre }}</td>
                                    <td>
                                        @if ($sucursale->estado)
                                        <span class="badge badge-success-lighten rounded-pill">Activo</span>
                                        @else
                                        <span class="badge badge-secondary-lighten rounded-pill">Inactivo</span>
                                        @endif
                                    </td>

                                    <td align="right">
                                        <form action="{{ route('admin.sucursales.destroy',$sucursale->id) }}"
                                            method="POST" onsubmit="return false" class="delete">
                                            <a class="btn btn-sm btn-primary "
                                                href="{{ route('admin.sucursales.show',$sucursale->id) }}"><i
                                                    class="fa fa-fw fa-eye"></i> {{ __('Ver') }}</a>
                                            @can('admin.sucursales.edit')
                                            <a class="btn btn-sm btn-success"
                                                href="{{ route('admin.sucursales.edit',$sucursale->id) }}"><i
                                                    class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                            @endcan

                                            @csrf
                                            @method('DELETE')
                                            @can('admin.sucursales.destroy')
                                            <button type="submit" class="btn btn-danger btn-sm"><i
                                                    class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
                                            @endcan

                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection