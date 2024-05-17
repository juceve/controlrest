<div class="row">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="text-center fw-bold">IMPORTES POR TIPO DE PRODUCTOS</h4>
                <div>
                    <canvas id="myChart"></canvas>
                </div>

                <div class="table-responsive mt-2" style="font-size: 11px;">
                    <table class="table table-sm table-bordered">
                        <thead class="table-primary">
                            <tr class="fw-bold">
                                <td>PRODUCTO</td>
                                <td align="center">CANT.</td>
                                <td align="right">IMPORTE Bs.</td>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $x = 0;
                            @endphp
                            @foreach ($tabla as $item)
                                <tr>
                                    <td><i class="fas fa-circle" style="color: {{ $colores[$x] }}"></i>
                                        {{ $item[0] }}</td>
                                    <td align="center">{{ $item[1] }}</td>
                                    <td align="right">{{ number_format($item[2], 2, '.') }}</td>
                                </tr>
                                @php
                                    $x++;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" align="right"><b>TOTAL Bs.:</b></td>
                                <td align="right"><b>{{ number_format($totalBs, 2, '.') }}</b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="text-center fw-bold">IMPORTES POR TIPO DE PAGO</h4>
                <div>
                    <canvas style="width: 50%" id="myChart2"></canvas>
                </div>

                <div class="table-responsive mt-2" style="font-size: 11px;">
                    <table class="table table-sm table-bordered">
                        <thead class="table-primary">
                            <tr class="fw-bold">
                                <td>TIPO PAGO</td>
                                <td align="center">CANT.</td>
                                <td align="right">IMPORTE Bs.</td>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $x = 0;
                            @endphp
                            @foreach ($tabla2 as $item)
                                <tr>
                                    <td><i class="fas fa-circle" style="color: {{ $colores[$x] }}"></i>
                                        {{ $item[0] }}</td>
                                    <td align="center">{{ $item[1] }}</td>
                                    <td align="right">{{ number_format($item[2], 2, '.') }}</td>
                                </tr>
                                @php
                                    $x++;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" align="right"><b>TOTAL Bs.:</b></td>
                                <td align="right"><b>{{ number_format($totalBs2, 2, '.') }}</b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>


</div>
@section('js')
    <script></script>
    <script>
        var resultado = "{{ $resultado }}";


        var myA = resultado.split('^');
        var data = [];
        var productos = [];
        var importes = [];
        var barColors = [
            "rgba(0,0,255,1.0)",
            "rgba(0,0,255,0.8)",
            "rgba(0,0,255,0.6)",
            "rgba(0,0,255,0.4)",
            "rgba(0,0,255,0.2)",
        ];
        myA.forEach(item => {
            var fila = item.split('|');
            productos.push(fila[0]);
            console.log(fila[2]);
            importes.push(fila[2]);
        });

        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: productos,
                datasets: [{
                    label: "Importe",
                    data: importes,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
        });


        var resultado2 = "{{ $resultado2 }}";


        var myA2 = resultado2.split('^');
        var data2 = [];
        var tipopagos2 = [];
        var importes2 = [];

        myA2.forEach(item => {
            var fila = item.split('|');
            tipopagos2.push(fila[0]);
            importes2.push(fila[2]);
        });
        const ctx2 = document.getElementById('myChart2');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: tipopagos2,
                datasets: [{
                    label: "Importe",
                    data: importes2,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
        });
    </script>
@endsection
