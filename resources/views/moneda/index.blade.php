@extends('layouts.app')

@section('template_title')
Moneda
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            {{ __('Monedas') }}
                        </span>

                        <div class="float-right">
                            @can('monedas.create')
                            <a href="{{ route('monedas.create') }}" class="btn btn-primary btn-sm float-right"
                                data-placement="left">
                                <i class="uil-plus"></i> Nuevo
                            </a>
                            @endcan

                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>
                                    <th>No</th>

                                    <th>Nombre</th>
                                    <th>Abreviatura</th>
                                    <th>Tasa cambio</th>
                                    <th>Predeterminado</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monedas as $moneda)
                                <tr>
                                    <td>{{ ++$i }}</td>

                                    <td>{{ $moneda->nombre }}</td>
                                    <td>{{ $moneda->abreviatura }}</td>
                                    <td>{{ $moneda->tasacambio }}</td>
                                    <td>{{ $moneda->predeterminado?"SI":"NO" }}</td>

                                    <td>
                                        <form action="{{ route('monedas.destroy',$moneda->id) }}"
                                            onsubmit="return false" method="POST" class="delete">
                                            <a class="btn btn-sm btn-primary "
                                                href="{{ route('monedas.show',$moneda->id) }}"><i class="uil-eye"></i>
                                                {{ __('Ver') }}</a>
                                            @can('monedas.edit')
                                            <a class="btn btn-sm btn-success"
                                                href="{{ route('monedas.edit',$moneda->id) }}"><i class="uil-edit"></i>
                                                {{ __('Editar') }}</a>
                                            @endcan

                                            @csrf
                                            @method('DELETE')
                                            @can('monedas.destroy')
                                            <button type="submit" class="btn btn-danger btn-sm"><i
                                                    class="uil-trash"></i> {{ __('Eliminar') }}</button>
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
            {!! $monedas->links() !!}
        </div>
    </div>
</div>
@endsection