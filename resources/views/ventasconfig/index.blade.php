@extends('layouts.app')

@section('template_title')
Configuraciones POS
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            Configuraciones POS
                        </span>

                        <div class="float-right">
                            @can('ventasconfigs.create')
                            <a href="{{ route('ventasconfigs.create') }}" class="btn btn-primary btn-sm float-right"
                                data-placement="left">
                                <i class="uil-plus"></i> Nuevo
                            </a>
                            @endcan

                        </div>
                    </div>
                </div>
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @endif

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>
                                    <th>No</th>

                                    <th>Sucursal</th>
                                    <th>Nivel curso</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ventasconfigs as $ventasconfig)
                                <tr>
                                    <td>{{ ++$i }}</td>

                                    <td>{{ $ventasconfig->sucursale->nombre }}</td>
                                    <td>{{ $ventasconfig->nivelcurso->nombre }}</td>

                                    <td>
                                        <form action="{{ route('ventasconfigs.destroy',$ventasconfig->id) }}"
                                            method="POST">
                                            {{-- <a class="btn btn-sm btn-primary "
                                                href="{{ route('ventasconfigs.show',$ventasconfig->id) }}"><i
                                                    class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a> --}}
                                            @can('ventasconfigs.edit')
                                            <a class="btn btn-sm btn-success"
                                                href="{{ route('ventasconfigs.edit',$ventasconfig->id) }}"><i
                                                    class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                            @endcan

                                            @csrf
                                            @method('DELETE')
                                            {{-- <button type="submit" class="btn btn-danger btn-sm"><i
                                                    class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button> --}}
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {!! $ventasconfigs->links() !!}
        </div>
    </div>
</div>
@endsection