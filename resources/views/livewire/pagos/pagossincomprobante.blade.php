<div>
    @section('template_title')
        Transacciones sin Comprobantes
    @endsection
    <div class="card mt-1">
        <div class="card-header bg-primary text-white">
            TRANSACCIONES SIN COMPROBANTES REGISTRADOS
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered dataTable">
                    <thead class="table-primary">
                        <tr>
                            <td align="center"><strong>ID</strong></td>
                            <td><strong>FECHA</strong></td>
                            <td><strong>CLIENTE</strong></td>
                            <td><strong>TIPO</strong></td>
                            <td align="right"><strong>IMPORTE</strong></td>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($pagos)
                            @foreach ($pagos as $pago)
                                <tr>
                                    <td align="center">{{ $pago->id }}</td>
                                    <td>{{ $pago->fecha }}</td>
                                    <td>{{ $pago->cliente }}</td>
                                    <td>{{ $pago->tipopago }}</td>
                                    <td  align="right">{{ $pago->importe }}</td>
                                    <td align="right"> <a href="{{route('pagos.actcomprobante',$pago->id)}}" class="btn btn-primary btn-sm">Actualizar</a> </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
