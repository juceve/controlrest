@extends('layouts.app')

@section('template_title')
Productos
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            LISTADO DE PRODUCTOS
                        </span>

                        <div class="float-right">
                            @can('items.create')
                            <a href="{{ route('items.create') }}" class="btn btn-primary btn-sm float-right"
                                data-placement="left">
                                <i class="uil-plus"></i>
                                Nuevo
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTable ">
                            <thead class="thead">
                                <tr class="table-primary">
                                    <th>No</th>
                                    <th>NOMBRE</th>
                                    <th>CATEGORIA</th>
                                    <th>ESTADO</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $item->nombre }}</td>
                                    <td>{{ $item->catitem->nombre }}</td>
                                    <td>
                                        @if ($item->estado)
                                        <span class="badge bg-success">ACTIVO</span>
                                        @else
                                        <span class="badge bg-danger">INACTIVO</span>
                                        @endif
                                    </td>

                                    <td align="right">
                                        <form action="{{ route('items.destroy',$item->id) }}" onsubmit="return false" method="POST"
                                            class="delete">
                                            <a class="btn btn-sm btn-primary "
                                                href="{{ route('items.show',$item->id) }}" title="Info"><i
                                                    class="uil-eye"></i> </a>
                                            @can('items.edit')
                                            <a class="btn btn-sm btn-success" href="{{ route('items.edit',$item->id) }}"
                                                title="Editar"><i class="uil-edit"></i> </a>
                                            @endcan
                                            @csrf
                                            @method('DELETE')
                                            @can('items.destroy')
                                            <button type="submit" class="btn btn-danger btn-sm"><i
                                                    class="uil-trash" title="Eliminar de la BD"></i> </button>
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