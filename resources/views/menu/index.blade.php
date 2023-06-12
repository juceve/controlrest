@extends('layouts.app')

@section('template_title')
Listado de Menu
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            LISTADO DE MENUS REGISTRADOS
                        </span>

                        <div class="float-right">
                            @can('menus.create')
                            <a href="{{ route('elaborarmenu',["id"=>0,"dup"=>0]) }}" class="btn btn-primary btn-sm float-right"
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
                        <table class="table table-striped table-hover dataTable">
                            <thead class="thead">
                                <tr>
                                    <th>No</th>
                                    <th>NOMBRE</th>
                                    <th>TIPO MENU</th>
                                    <th ></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($menus as $menu)
                                <tr>
                                    <td>{{ $menu->id }}</td>
                                    <td>{{ $menu->nombre }}</td>
                                    <td>{{ $menu->tipomenu->nombre }}</td>
                                    <td align="right">
                                        <form action="{{ route('menus.destroy',$menu->id) }}" onsubmit="return false" method="POST"
                                            class="delete">
                                            <a class="btn btn-sm btn-primary "
                                                href="{{ route('menus.show',$menu->id) }}" title="Ver Info"><i
                                                    class="uil-eye"></i> </a>
                                            @can('menus.edit')
                                            <a class="btn btn-sm btn-success"
                                                href="{{ route('elaborarmenu',["id"=>$menu->id,"dup"=>0]) }}" title="Editar"><i
                                                    class="uil-edit"></i> </a>
                                                    <a class="btn btn-sm btn-warning"
                                                href="{{ route('elaborarmenu',["id"=>$menu->id,"dup"=>1]) }}" title="Duplicar"><i
                                                    class="uil-copy"></i> </a>
                                            @endcan
                                            @csrf
                                            @method('DELETE')
                                            @can('menus.destroy')
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