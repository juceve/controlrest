<table class="table table-striped table-bordered">
    <tr>
        <td align="center" colspan="6">
            LISTADO DE VENTAS
        </td>
    </tr>
    <tr>
        <td align="center" colspan="6">
            Del {{ $fechai }} al {{ $fechaf }}
        </td>
    </tr>
    <thead style="background-color: #d1d1d1;">
        <tr>
            <td><strong> ID</strong></td>
            <td><strong> Fecha</strong></td>
            <td><strong> Cliente</strong></td>
            <td><strong> Tipo Pago</strong></td>
            <td><strong> Estado</strong></td>
            <td><strong> Importe</strong></td>
        </tr>
    </thead>
    <tbody>
        @if (!is_null($contenedor))
            @foreach ($contenedor as $venta)
                <tr>
                    <td>{{ $venta['id'] }}</td>
                    <td>{{ $venta['fecha'] }}</td>
                    <td>{{ $venta['cliente'] }}</td>
                    <td>{{ $venta['tipopago'] }}</td>
                    <td>{{ $venta['estadopago'] }}</td>
                    <td>{{ $venta['importe'] }}</td>
                </tr>
            @endforeach
        @endif

    </tbody>
</table>
