@extends('layouts.app')

@section('template_title')
Empresa
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">DATOS DE LA EMPRESA</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            {{ __('Empresa') }}
                        </span>

                        <div class="float-right">
                            @can('admin.empresas.create')
                                <a href="{{ route('admin.empresas.create') }}" class="btn btn-info btn-sm float-right"
                                data-placement="left">
                                {{ __('Nuevo') }}
                            </a>
                            @endcan
                            
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table dt-responsive nowrap w-100 dataTable">
                            <thead class="thead table-dark">
                                <tr>
                                    <th>No</th>

                                    <th>Razon Social</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($empresas as $empresa)
                                <tr>
                                    <td>{{ ++$i }}</td>

                                    <td>{{ $empresa->razonsocial }}</td>
                                    <td>{{ $empresa->direccion }}</td>
                                    <td>{{ $empresa->telefono }}</td>

                                    <td align="right">
                                        <form class="delete" action="{{ route('admin.empresas.destroy',$empresa->id) }}" onsubmit="return false" method="POST">
                                            <a class="btn btn-sm btn-primary "
                                                href="{{ route('admin.empresas.show',$empresa->id) }}"><i
                                                    class="fa fa-fw fa-eye"></i> {{ __('Info') }}</a>
                                                    @can('admin.empresas.edit')
                                                        <a class="btn btn-sm btn-success"
                                                href="{{ route('admin.empresas.edit',$empresa->id) }}"><i
                                                    class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                    @endcan
                                            
                                            @csrf
                                            @method('DELETE')
                                            @can('admin.empresas.destroy')
                                               <button type="submit" class="btn btn-danger btn-sm"><i
                                                    class="fa fa-fw fa-trash"></i> {{ __('Borrar') }}</button> 
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
            {!! $empresas->links() !!}
        </div>
    </div>
</div>
@endsection
