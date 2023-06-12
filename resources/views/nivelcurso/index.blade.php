@extends('layouts.app')

@section('template_title')
Niveles de Curso
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            Niveles de Curso
                        </span>

                        <div class="float-right">
                            @can('nivelcursos.create')
                            <a href="{{ route('nivelcursos.create') }}" class="btn btn-info btn-sm float-right"
                                data-placement="left">
                                <i class="uil-plus"></i> Nuevo
                            </a>
                            @endcan

                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="table-info">
                                <tr>
                                    <th>No</th>

                                    <th>Nombre</th>
                                    <th>Sucursal</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nivelcursos as $nivelcurso)
                                <tr>
                                    <td>{{ ++$i }}</td>

                                    <td>{{ $nivelcurso->nombre }}</td>
                                    <td>{{ $nivelcurso->sucursale->nombre}}</td>

                                    <td align="right">
                                        <form action="{{ route('nivelcursos.destroy',$nivelcurso->id) }}" method="POST">
                                            {{-- <a class="btn btn-sm btn-primary "
                                                href="{{ route('nivelcursos.show',$nivelcurso->id) }}"><i
                                                    class="fa fa-fw fa-eye"></i> Show</a> --}}
                                            @can('nivelcursos.edit')
                                            <a class="btn btn-sm btn-success"
                                                href="{{ route('nivelcursos.edit',$nivelcurso->id) }}"><i
                                                    class="fa fa-fw fa-edit"></i> Editar</a>
                                            @endcan

                                            @csrf
                                            @method('DELETE')
                                            @can('nivelcursos.destroy')
                                            <button type="submit" class="btn btn-danger btn-sm"><i
                                                    class="fa fa-fw fa-trash"></i> Eliminar</button>
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
            {!! $nivelcursos->links() !!}
        </div>
    </div>
</div>
@endsection