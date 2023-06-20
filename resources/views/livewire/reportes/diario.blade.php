<div>
    @section('template_title')
        Control de Almuerzos
    @endsection
    {{-- <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">REPORTES - DIARIO</h4>
            </div>
        </div>
    </div> --}}
    <div class="card mt-3">
        <div class="card-header bg-primary text-white">
            CONTROL DE ALMUERZOS
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="mb-3">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="basic-addon1">FECHA</span>
                            <input type="date" class="form-control" aria-describedby="basic-addon1"
                                wire:model='selFecha'>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3 d-grid mb-3">
                    <button class="btn btn-info" wire:click='generar'><i class="uil-cog"></i> GENERAR <div wire:loading
                            wire:target="generar">
                            <div class="spinner-border spinner-border-sm" role="status"></div>
                        </div></button>

                </div>
                @if ($tabla1)
                    <div class="col-12 col-md-3 d-grid mb-3">
                        <button class="btn btn-danger" wire:click='pdf'><i class="mdi mdi-file-pdf"></i> PDF <div
                                wire:loading wire:target="pdf">
                                <div class="spinner-border spinner-border-sm" role="status"></div>
                            </div></button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @if ($tabla1)
        <div class="card">
            <div class="card-body">
                <h2 class="h4 text-center">CONTROL DE ALMUERZOS PAGADOS Y SERVIDOS</h2>
                <h2 class="h5 text-center"><strong>Fecha: </strong>{{ $selFecha }}</h2>
                <hr>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h2 class="h5">RESUMEN GENERAL <small>(No incluye Profesores)</small></h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-sm table-hover"
                                style="font-size: 11px;">
                                <thead class="table-secondary">
                                    
                                    <tr align="center">
                                        <th>NRO</th>
                                        <th>ITEM</th>
                                        <th>PAGADOS</th>
                                        <th>SERVIDOS</th>
                                        <th>AUSENCIAS</th>
                                        <th>LICENCIAS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $tpagados = 0;
                                        $tservidos = 0;
                                        $tausencias = 0;
                                        $tlicencias = 0;
                                    @endphp
                                    @foreach ($tabla1 as $item)
                                        <tr>
                                            <td align="center">{{ $i }}</td>
                                            <td>{{ $item[1] }}</td>
                                            <td align="center">{{ $item[2] }}</td>
                                            <td align="center">{{ $item[3] }}</td>
                                            <td align="center">{{ $item[4] }}</td>
                                            <td align="center">{{ $item[5] }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                            $tpagados = $tpagados + $item[2];
                                            $tservidos = $tservidos + $item[3];
                                            $tausencias = $tausencias + $item[4];
                                            $tlicencias = $tlicencias + $item[5];
                                        @endphp
                                    @endforeach

                                </tbody>
                                <tfoot class="table-success">
                                    <tr>
                                        <td colspan="2" align="right"><strong>TOTAL</strong></td>
                                        <td align="center"><strong>{{ $tpagados }}</strong></td>
                                        <td align="center"><strong>{{ $tservidos }}</strong></td>
                                        <td align="center"><strong>{{ $tausencias }}</strong></td>
                                        <td align="center"><strong>{{ $tlicencias }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                    </div>
                    <div class="col-12 col-md-6">
                        <h2 class="h5">RESUMEN POR PLATAFORMA</h2>
                        <div class="table-responsive">
                            @if ($tabla11)
                                <table class="table table-striped table-bordered table-sm table-hover"
                                    style="font-size: 11px;">
                                    <thead class="table-secondary">
                                        <tr align="center">
                                            <th>NRO</th>
                                            <th>PLATAFORMA</th>
                                            <th>CANTIDAD</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                            $total = 0;
                                        @endphp
                                        @foreach ($tabla11 as $item11)
                                            <tr>
                                                <td align="center">{{ $i }}</td>
                                                <td>{{ $item11[0] }}</td>
                                                <td align="center">{{ $item11[1] }}</td>
                                            </tr>
                                            @php
                                                $i++;
                                                $total = $total + $item11[1];
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-success">
                                            <td colspan="2" align="right"><strong>TOTAL</strong></td>
                                            <td align="center"><strong>{{$total}}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($tabla2)
                    <hr>
                    <h2 class="h4 text-center">ALMUERZO PAGADOS VS SERVIDOS POR CURSOS</h2>

                    <h4 class="text-primary">PRIMARIA</h4>
                    <div class="table-responsive" data-simplebar>
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="table-info" style="font-size: 10px; width: 100%">
                                <tr align="center">
                                    <th>No.</th>
                                    <th>CURSO</th>
                                    <th>LICENCIA</th>
                                    <th>A. COMP. PAGADOS</th>
                                    <th>A. COMP. SERVIDOS</th>
                                    {{-- <th>SUBTOTAL</th> --}}
                                    <th>A. SIMPLE PAGADOS</th>
                                    <th>A. SIMPLE SERVIDOS</th>
                                    {{-- <th>SUBTOTAL</th> --}}
                                    <th>EXTRA PAGADO</th>
                                    <th>EXTRA SERVIDO</th>
                                    {{-- <th>SUBTOTAL</th> --}}
                                    <th>TOTAL PAGADOS</th>
                                    <th>TOTAL SERVIDOS</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 12px;">
                                @php
                                    $licencias = 0;
                                    $completoPagado = 0;
                                    $completoServido = 0;
                                    $simplePagado = 0;
                                    $simpleServido = 0;
                                    $extraPagado = 0;
                                    $extraServido = 0;
                                    $i = 1;
                                @endphp
                                @foreach ($tabla2 as $item)
                                    @php
                                        $totalpagados = 0;
                                        $totalservidos = 0;
                                        $data = $item[2];
                                        $completoPagado = $completoPagado + $data[0][2];
                                        $completoServido = $completoServido + $data[0][3];
                                        $simplePagado = $simplePagado + $data[1][2];
                                        $simpleServido = $simpleServido + $data[1][3];
                                        $extraPagado = $extraPagado + $data[2][2];
                                        $extraServido = $extraServido + $data[2][3];
                                    @endphp
                                    @if ($data[0][2] || $data[0][3] || $data[1][2] || $data[1][3] || $data[2][2] || $data[2][3])
                                        @php
                                            $totalpagados = $totalpagados + $data[0][2] + $data[1][2] + $data[2][2];
                                            $totalservidos = $totalservidos + $data[0][3] + $data[1][3] + $data[2][3];
                                            $licencias = $licencias + $item[3];
                                        @endphp
                                        <tr align="center">
                                            <td>{{ $i }}</td>
                                            <td>{{ $item[1] }}</td>
                                            <td>{{ $item[3] }}</td>
                                            <td>{{ $data[0][2] }}</td>
                                            <td>{{ $data[0][3] }}</td>
                                            {{-- <td>6</td> --}}
                                            <td>{{ $data[1][2] }}</td>
                                            <td>{{ $data[1][3] }}</td>
                                            {{-- <td>9</td> --}}
                                            <td>{{ $data[2][2] }}</td>
                                            <td>{{ $data[2][3] }}</td>
                                            {{-- <td>112</td> --}}
                                            <td class="table-success">{{ $totalpagados }}</td>
                                            <td class="table-success">{{ $totalservidos }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endif
                                @endforeach

                            </tbody>
                            <tfoot class="table-info text-center" style="font-size: 12px;">
                                <tr>

                                    <td colspan="2"><strong>TOTAL</strong></td>
                                    <td><strong>{{ $licencias }}</strong></td>
                                    <td><strong>{{ $completoPagado }}</strong></td>
                                    <td><strong>{{ $completoServido }}</strong></td>
                                    {{-- <td>2</td> --}}
                                    <td><strong>{{ $simplePagado }}</strong></td>
                                    <td><strong>{{ $simpleServido }}</strong></td>
                                    {{-- <td>6</td> --}}
                                    <td><strong>{{ $extraPagado }}</strong></td>
                                    <td><strong>{{ $extraServido }}</strong></td>
                                    {{-- <td>7</td> --}}
                                    @php
                                        $totalpagados1 = $completoPagado + $simplePagado + $extraPagado;
                                        $totalservidos1 = $completoServido + $simpleServido + $extraServido;
                                    @endphp
                                    <td><strong>{{ $totalpagados1 }}</strong></td>
                                    <td><strong>{{ $totalservidos1 }}</strong></td>

                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif


                @if ($tabla3)
                    <h4 class="text-info">SECUNDARIA</h4>
                    <div class="table-responsive" data-simplebar>
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="table-info" style="font-size: 10px; width: 100%">
                                <tr align="center">
                                    <th>No.</th>
                                    <th>CURSO</th>
                                    <th>LICENCIA</th>
                                    <th>A. COMP. PAGADOS</th>
                                    <th>A. COMP. SERVIDOS</th>
                                    {{-- <th>SUBTOTAL</th> --}}
                                    <th>A. SIMPLE PAGADOS</th>
                                    <th>A. SIMPLE SERVIDOS</th>
                                    {{-- <th>SUBTOTAL</th> --}}
                                    <th>EXTRA PAGADO</th>
                                    <th>EXTRA SERVIDO</th>
                                    {{-- <th>SUBTOTAL</th> --}}
                                    <th>TOTAL PAGADOS</th>
                                    <th>TOTAL SERVIDOS</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 12px;">
                                @php
                                    $licencias = 0;
                                    $completoPagado = 0;
                                    $completoServido = 0;
                                    $simplePagado = 0;
                                    $simpleServido = 0;
                                    $extraPagado = 0;
                                    $extraServido = 0;
                                    $i = 1;
                                @endphp
                                @foreach ($tabla3 as $item)
                                    @php
                                        $totalpagados = 0;
                                        $totalservidos = 0;
                                        $data = $item[2];
                                        $completoPagado = $completoPagado + $data[0][2];
                                        $completoServido = $completoServido + $data[0][3];
                                        $simplePagado = $simplePagado + $data[1][2];
                                        $simpleServido = $simpleServido + $data[1][3];
                                        $extraPagado = $extraPagado + $data[2][2];
                                        $extraServido = $extraServido + $data[2][3];
                                    @endphp
                                    @if ($data[0][2] || $data[0][3] || $data[1][2] || $data[1][3] || $data[2][2] || $data[2][3])
                                        @php
                                            $totalpagados = $totalpagados + $data[0][2] + $data[1][2] + $data[2][2];
                                            $totalservidos = $totalservidos + $data[0][3] + $data[1][3] + $data[2][3];
                                            $licencias = $licencias + $item[3];
                                        @endphp
                                        <tr align="center">
                                            <td>{{ $i }}</td>
                                            <td>{{ $item[1] }}</td>
                                            <td>{{ $item[3] }}</td>
                                            <td>{{ $data[0][2] }}</td>
                                            <td>{{ $data[0][3] }}</td>
                                            {{-- <td>6</td> --}}
                                            <td>{{ $data[1][2] }}</td>
                                            <td>{{ $data[1][3] }}</td>
                                            {{-- <td>9</td> --}}
                                            <td>{{ $data[2][2] }}</td>
                                            <td>{{ $data[2][3] }}</td>
                                            {{-- <td>112</td> --}}
                                            <td class="table-success">{{ $totalpagados }}</td>
                                            <td class="table-success">{{ $totalservidos }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endif
                                @endforeach

                            </tbody>
                            <tfoot class="table-info text-center" style="font-size: 12px;">
                                <tr>
                                    <td colspan="2"><strong>TOTAL</strong></td>
                                    <td><strong>{{ $licencias }}</strong></td>
                                    <td><strong>{{ $completoPagado }}</strong></td>
                                    <td><strong>{{ $completoServido }}</strong></td>
                                    {{-- <td>2</td> --}}
                                    <td><strong>{{ $simplePagado }}</strong></td>
                                    <td><strong>{{ $simpleServido }}</strong></td>
                                    {{-- <td>6</td> --}}
                                    <td><strong>{{ $extraPagado }}</strong></td>
                                    <td><strong>{{ $extraServido }}</strong></td>
                                    {{-- <td>7</td> --}}
                                    @php
                                        $totalpagados1 = $completoPagado + $simplePagado + $extraPagado;
                                        $totalservidos1 = $completoServido + $simpleServido + $extraServido;
                                    @endphp
                                    <td><strong>{{ $totalpagados1 }}</strong></td>
                                    <td><strong>{{ $totalservidos1 }}</strong></td>

                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif

                @if ($tabla4)
                    <h4 class="text-success">PROFESORES</h4>
                    <div class="table-responsive" data-simplebar>
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="table-info" style="font-size: 10px; width: 100%">
                                <tr align="center">
                                    <th>No.</th>
                                    <th>CURSO</th>
                                    <th>LICENCIA</th>
                                    <th>A. COMP. PAGADOS</th>
                                    <th>A. COMP. SERVIDOS</th>
                                    {{-- <th>SUBTOTAL</th> --}}
                                    <th>A. SIMPLE PAGADOS</th>
                                    <th>A. SIMPLE SERVIDOS</th>
                                    {{-- <th>SUBTOTAL</th> --}}
                                    <th>EXTRA PAGADO</th>
                                    <th>EXTRA SERVIDO</th>
                                    {{-- <th>SUBTOTAL</th> --}}
                                    <th>TOTAL PAGADOS</th>
                                    <th>TOTAL SERVIDOS</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 12px;">
                                @php
                                    $licencias = 0;
                                    $completoPagado = 0;
                                    $completoServido = 0;
                                    $simplePagado = 0;
                                    $simpleServido = 0;
                                    $extraPagado = 0;
                                    $extraServido = 0;
                                    $i = 1;
                                @endphp
                                @foreach ($tabla4 as $item)
                                    @php
                                        $totalpagados = 0;
                                        $totalservidos = 0;
                                        $data = $item[2];
                                        $completoPagado = $completoPagado + $data[0][2];
                                        $completoServido = $completoServido + $data[0][3];
                                        $simplePagado = $simplePagado + $data[1][2];
                                        $simpleServido = $simpleServido + $data[1][3];
                                        $extraPagado = $extraPagado + $data[2][2];
                                        $extraServido = $extraServido + $data[2][3];
                                    @endphp
                                    @if ($data[0][2] || $data[0][3] || $data[1][2] || $data[1][3] || $data[2][2] || $data[2][3])
                                        @php
                                            $totalpagados = $totalpagados + $data[0][2] + $data[1][2] + $data[2][2];
                                            $totalservidos = $totalservidos + $data[0][3] + $data[1][3] + $data[2][3];
                                            $licencias = $licencias + $item[3];
                                        @endphp
                                        <tr align="center">
                                            <td>{{ $i }}</td>
                                            <td>{{ $item[1] }}</td>
                                            <td>{{ $item[3] }}</td>
                                            <td>{{ $data[0][2] }}</td>
                                            <td>{{ $data[0][3] }}</td>
                                            {{-- <td>6</td> --}}
                                            <td>{{ $data[1][2] }}</td>
                                            <td>{{ $data[1][3] }}</td>
                                            {{-- <td>9</td> --}}
                                            <td>{{ $data[2][2] }}</td>
                                            <td>{{ $data[2][3] }}</td>
                                            {{-- <td>112</td> --}}
                                            <td class="table-success">{{ $totalpagados }}</td>
                                            <td class="table-success">{{ $totalservidos }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endif
                                @endforeach

                            </tbody>
                            <tfoot class="table-info text-center" style="font-size: 12px;">
                                <tr>
                                    <td colspan="2"><strong>TOTAL</strong></td>
                                    <td><strong>{{ $licencias }}</strong></td>
                                    <td><strong>{{ $completoPagado }}</strong></td>
                                    <td><strong>{{ $completoServido }}</strong></td>
                                    {{-- <td>2</td> --}}
                                    <td><strong>{{ $simplePagado }}</strong></td>
                                    <td><strong>{{ $simpleServido }}</strong></td>
                                    {{-- <td>6</td> --}}
                                    <td><strong>{{ $extraPagado }}</strong></td>
                                    <td><strong>{{ $extraServido }}</strong></td>
                                    {{-- <td>7</td> --}}
                                    @php
                                        $totalpagados1 = $completoPagado + $simplePagado + $extraPagado;
                                        $totalservidos1 = $completoServido + $simpleServido + $extraServido;
                                    @endphp
                                    <td><strong>{{ $totalpagados1 }}</strong></td>
                                    <td><strong>{{ $totalservidos1 }}</strong></td>

                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
