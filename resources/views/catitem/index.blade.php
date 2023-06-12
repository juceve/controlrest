@extends('layouts.app')

@section('template_title')
    Categorias
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                LISTADO DE CATEGORIAS DE PRODUCTOS
                            </span>

                             <div class="float-right">
                                <a href="{{ route('catitems.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  <i class="uil-plus"></i>
                                  Nuevo
                                </a>
                              </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dataTable">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Nombre</th>

                                        <th style="width: 170px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=0;
                                    @endphp
                                    @foreach ($catitems as $catitem)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $catitem->nombre }}</td>

                                            <td align="right">
                                                <form action="{{ route('catitems.destroy',$catitem->id) }}" onsubmit="return false" method="POST" class="delete">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('catitems.show',$catitem->id) }}" title="Ver Info"><i class="uil-eye"></i></a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('catitems.edit',$catitem->id) }}" title="Editar"><i class="uil-edit"></i></a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="uil-trash" title="Eliminar de la BD"></i></button>
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
