@extends('layouts.app')

@section('template_title')
    Cierrecaja
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Cierrecaja') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('cierrecajas.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Fecha</th>
										<th>Hora</th>
										<th>User Id</th>
										<th>Sucursale Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cierrecajas as $cierrecaja)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $cierrecaja->fecha }}</td>
											<td>{{ $cierrecaja->hora }}</td>
											<td>{{ $cierrecaja->user_id }}</td>
											<td>{{ $cierrecaja->sucursale_id }}</td>

                                            <td>
                                                <form action="{{ route('cierrecajas.destroy',$cierrecaja->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('cierrecajas.show',$cierrecaja->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('cierrecajas.edit',$cierrecaja->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $cierrecajas->links() !!}
            </div>
        </div>
    </div>
@endsection
