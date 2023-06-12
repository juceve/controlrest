@extends('layouts.app')

@section('template_title')
    Feriado
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Feriado') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('feriados.create') }}" class="btn btn-info btn-sm float-right"  data-placement="left">
                                  <i class="uil-plus"></i> Nuevo
                                </a>
                              </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dataTable">
                                <thead class="thead table-dark">
                                    <tr>
                                        <th>ID</th>
                                        
										<th>Fecha</th>
										<th>Motivo</th>
										<th>Sucursal</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($feriados as $feriado)
                                        <tr>
                                            <td>{{ $feriado->id }}</td>
                                            
											<td>{{ $feriado->fecha }}</td>
											<td>{{ $feriado->motivo }}</td>
											<td>{{ $feriado->sucursale->nombre }}</td>

                                            <td align="right">
                                                <form action="{{ route('feriados.destroy',$feriado->id) }}" onsubmit="return false" class="delete" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('feriados.show',$feriado->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('feriados.edit',$feriado->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
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
