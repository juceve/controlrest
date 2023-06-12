@extends('layouts.app')

@section('template_title')
    Cierrereservabono
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Cierrereservabono') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('cierrereservabonos.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                    @foreach ($cierrereservabonos as $cierrereservabono)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $cierrereservabono->fecha }}</td>
											<td>{{ $cierrereservabono->hora }}</td>
											<td>{{ $cierrereservabono->user_id }}</td>
											<td>{{ $cierrereservabono->sucursale_id }}</td>

                                            <td>
                                                <form action="{{ route('cierrereservabonos.destroy',$cierrereservabono->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('cierrereservabonos.show',$cierrereservabono->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('cierrereservabonos.edit',$cierrereservabono->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $cierrereservabonos->links() !!}
            </div>
        </div>
    </div>
@endsection
