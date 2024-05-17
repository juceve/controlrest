@extends('layouts.app')

@section('template_title')
Estudiante
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            LISTADO DE ESTUDIANTES
                        </span>
                        @can('estudiantes.create')
                        <div class="float-right">
                            {{-- <a href="{{ route('estudiantes.create') }}"
                                class="btn btn-secondary btn-sm float-right" data-placement="left">
                                <i class="fas fa-plus"></i>
                                Nuevo
                            </a> --}}
                        </div>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTable" style="font-size: 12px;">
                            <thead class="thead">
                                <tr>
                                    <th>CÓDIGO</th>
                                    <th>NOMBRE</th>
                                    <th>TUTOR</th>
                                    <th>CURSO</th>
                                    <th style="width: 150px;"></th>
                                </tr>
                            </thead>
                            <tbody>                                
                                @foreach ($estudiantes as $estudiante)
                                <tr style="vertical-align: middle">
                                    <td>{{ $estudiante->codigo }}</td>
                                    <td>{{ $estudiante->nombre }}</td>
                                    <td>
                                        @if (!is_null($estudiante->tutore_id))
                                        {{$estudiante->tutore->nombre}}
                                        @else
                                        No asignado
                                        @endif
                                    </td>
                                    <td>
                                        @if ($estudiante->curso_id)
                                        {{ $estudiante->curso->nombre.' - '.$estudiante->curso->nivelcurso->nombre }}
                                        @else
                                            Desafiliado
                                        @endif
                                    </td>
                                    <td align="right">
                                        <form action="{{ route('estudiantes.destroy',$estudiante->id) }}" method="POST"
                                            class="delete">
                                            <a class="btn btn-sm btn-primary "
                                                href="{{ route('estudiantes.show',$estudiante->id) }}" title="Info"><i
                                                    class="uil-eye"></i></a>
                                            @can('estudiantes.edit')
                                            @if (!is_null($estudiante->tutore_id))
                                            <a class="btn btn-sm btn-success"
                                                href="{{ route('vinculosestudiantes',$estudiante->tutore_id) }}"
                                                title="Opciones"><i class="uil-edit"></i></a>
                                                <a href="{{route('pedidos.personales',$estudiante->id)}}" class="btn btn-sm btn-warning" title="Licencias - Reprogramaciones">
                                                    <i class="uil-history"></i>
                                                </a>
                                            @endif                                            
                                            @endcan
                                            @csrf
                                            @method('DELETE')
                                            {{-- @can('estudiantes.destroy')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                title="Eliminar de la BD"><i class="uil-trash"></i></button>
                                            @endcan --}}
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