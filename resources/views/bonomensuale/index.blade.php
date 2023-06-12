@extends('layouts.app')

@section('template_title')
    Bonomensuale
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Bonomensuale') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('bonomensuales.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
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
                                        
										<th>Fechainicio</th>
										<th>Fechafin</th>
										<th>Estudiante Id</th>
										<th>Tipomenu Id</th>
										<th>Pago Id</th>
										<th>Estado</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bonomensuales as $bonomensuale)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $bonomensuale->fechainicio }}</td>
											<td>{{ $bonomensuale->fechafin }}</td>
											<td>{{ $bonomensuale->estudiante_id }}</td>
											<td>{{ $bonomensuale->tipomenu_id }}</td>
											<td>{{ $bonomensuale->pago_id }}</td>
											<td>{{ $bonomensuale->estado }}</td>

                                            <td>
                                                <form action="{{ route('bonomensuales.destroy',$bonomensuale->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('bonomensuales.show',$bonomensuale->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('bonomensuales.edit',$bonomensuale->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $bonomensuales->links() !!}
            </div>
        </div>
    </div>
@endsection
