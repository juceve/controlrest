@extends('layouts.app')

@section('template_title')
    Entregalounch
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Entregalounch') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('entregalounches.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Fechaentrega</th>
										<th>Detallelonchera Id</th>
										<th>Menu Id</th>
										<th>Venta Id</th>
										<th>User Id</th>
										<th>Sucursale Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($entregalounches as $entregalounch)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $entregalounch->fechaentrega }}</td>
											<td>{{ $entregalounch->detallelonchera_id }}</td>
											<td>{{ $entregalounch->menu_id }}</td>
											<td>{{ $entregalounch->venta_id }}</td>
											<td>{{ $entregalounch->user_id }}</td>
											<td>{{ $entregalounch->sucursale_id }}</td>

                                            <td>
                                                <form action="{{ route('entregalounches.destroy',$entregalounch->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('entregalounches.show',$entregalounch->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('entregalounches.edit',$entregalounch->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $entregalounches->links() !!}
            </div>
        </div>
    </div>
@endsection
