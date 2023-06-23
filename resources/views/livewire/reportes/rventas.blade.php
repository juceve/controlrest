<div>
    @section('template_title')
        Reporte de Ventas
    @endsection
    <div class="card">
        <div class="card-header bg-primary text-white">
            REPORTE DE VENTAS
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="mb-3">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="basic-addon1">INICIO</span>
                            <input type="date" class="form-control" aria-describedby="basic-addon1"
                                wire:model='fechaI'>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="mb-3">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="basic-addon1">FINAL</span>
                            <input type="date" class="form-control" aria-describedby="basic-addon1"
                                wire:model='fechaF'>
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
                <h4 class="text-center">REPORTE DE VENTAS</h4>
                <div class="content text-center mb-2"><small>Del: <strong>{{ $this->fechaI }}</strong> al
                        <strong>{{ $this->fechaF }}</strong></small></div>
                <div class="row">
                    <hr>
                    <h4 class="text-center text-success">POR TIPO DE PAGO</h4>
                    <div class="col-12 col-md-6">
                        <label for=""><strong>PAGOS REALIZADOS</strong></label>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-hover" style="font-size: 12px;">
                                <thead class="table-primary">
                                    <tr align="center">
                                        <th>NRO</th>
                                        <th>TIPO PAGO</th>
                                        <th>CANT. OPERACIONES</th>
                                        <th>SUBTOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $total = 0;
                                        $cantidad = 0;
                                    @endphp
                                    @foreach ($t1 as $item)
                                        <tr>
                                            <td align="center">{{ $i }}</td>
                                            <td>{{ $item[1] }}</td>
                                            <td align="center">{{ $item[2] }}</td>
                                            <td align="right">{{ $item[3] }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                            $cantidad = $cantidad + $item[2];
                                            $total = $total + $item[3];
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot class="table-success">
                                    <tr>
                                        <td colspan="2" align="right"><strong>TOTAL</strong></td>
                                        <td align="center"><strong>{{ $cantidad }}</strong></td>
                                        <td align="right"><strong>{{ number_format($total,2,'.',',') }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for=""><strong>POR PAGAR</strong></label>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-hover" style="font-size: 12px;">
                                <thead class="table-primary">
                                    <tr align="center">
                                        <th>NRO</th>
                                        <th>TIPO PAGO</th>
                                        <th>CANT. OPERACIONES</th>
                                        <th>SUBTOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $total = 0;
                                        $cantidad = 0;
                                    @endphp
                                    @foreach ($t2 as $item)
                                    <tr>
                                        <td align="center">{{ $i }}</td>
                                        <td>{{ $item[1] }}</td>
                                        <td align="center">{{ $item[2] }}</td>
                                        <td align="right">{{ $item[3] }}</td>
                                    </tr>
                                    @php
                                        $i++;
                                        $cantidad = $cantidad + $item[2];
                                        $total = $total + $item[3];
                                    @endphp
                                    @endforeach
                                </tbody>
                                <tfoot class="table-success">
                                    <tr>
                                        <td colspan="2" align="right"><strong>TOTAL</strong></td>
                                        <td align="center"><strong>{{ $cantidad }}</strong></td>
                                        <td align="right"><strong>{{ number_format($total,2,'.',',') }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @if (count($t3) > 0)
                    <hr>
                    <h4 class="text-center text-success">POR PLATAFORMAS</h4>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label for=""><strong>PAGOS REALIZADOS</strong></label>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered"
                                    style="font-size: 12px; width: 100%">
                                    <thead class="table-info">
                                        <tr align="center">
                                            <th>NRO</th>
                                            <th>PLATAFORMA</th>
                                            <th>CANTIDAD</th>
                                            <th>SUBTOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                            $total = 0;
                                            $cantidad = 0;
                                        @endphp
                                        @foreach ($t3 as $item)
                                            <tr>
                                                <td align="center">{{ $i }}</td>
                                                <td>{{ $item[1] }}</td>
                                                <td align="center">{{ $item[2] }}</td>
                                                <td align="right">{{ $item[3] }}</td>
                                            </tr>
                                            @php
                                                $i++;
                                                $cantidad = $cantidad + $item[2];
                                                $total = $total + $item[3];
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-success">
                                        <tr>
                                            <td colspan="2" align="right"><strong>TOTAL</strong></td>
                                            <td align="center"><strong>{{ $cantidad }}</strong></td>
                                            <td align="right"><strong>{{ number_format($total,2,'.',',') }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for=""><strong>POR PAGAR</strong></label>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered"
                                    style="font-size: 12px; width: 100%">
                                    <thead class="table-info">
                                        <tr align="center">
                                            <th>NRO</th>
                                            <th>PLATAFORMA</th>
                                            <th>CANTIDAD</th>
                                            <th>SUBTOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                            $total = 0;
                                            $cantidad = 0;
                                        @endphp
                                        @foreach ($t4 as $item)
                                        <tr>
                                            <td align="center">{{ $i }}</td>
                                            <td>{{ $item[1] }}</td>
                                            <td align="center">{{ $item[2] }}</td>
                                            <td align="right">{{ $item[3] }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                            $cantidad = $cantidad + $item[2];
                                            $total = $total + $item[3];
                                        @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-success">
                                        <tr>
                                            <td colspan="2" align="right"><strong>TOTAL</strong></td>
                                            <td align="center"><strong>{{ $cantidad }}</strong></td>
                                            <td align="right"><strong>{{ number_format($total,2,'.',',') }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        {{-- <div class="col-12 col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover table-bordered" style="font-size: 12px;">
                                        <thead class="table-info">
                                            <tr align="center">
                                                <th>NRO</th>
                                                <th>PRODUCTO</th>
                                                <th>DESCUENTO</th>
                                                <th>CANTIDAD</th>
                                                <th>SUBTOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                                $total = 0;
                                                $cantidad = 0;
                                            @endphp
                                            @foreach ($tabla4 as $item)
                                                @foreach ($item[2] as $detalle)
                                                    @if ($detalle[2] > 0)
                                                        <tr>
                                                            <td align="center">{{ $i }}</td>
                                                            <td>{{ $item[1] }}</td>
                                                            <td align="center">{{ $detalle[0] }}</td>
                                                            <td align="center">{{ $detalle[1] }}</td>
                                                            <td align="right">{{ $detalle[2] }}</td>
                                                        </tr>
                                                        @php
                                                            $i++;
                                                            $cantidad = $cantidad + $detalle[1];
                                                            $total = $total + $detalle[2];
                                                        @endphp
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-success">
                                            <tr>
                                                <td colspan="3" align="right"><strong>TOTAL</strong></td>
                                                <td align="center"><strong>{{ $cantidad }}</strong></td>
                                                <td align="right"><strong>{{ $total }}</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div> --}}
                    </div>

                @endif
                @if (count($tabla5) > 0)
                <hr>
                <h4 class="text-success">GASTOS ADMINISTRATIVOS</h4>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-bordered"
                                style="font-size: 12px; width: 100%">
                                <thead class="table-info">
                                    <tr align="center">
                                        <th>NRO</th>
                                        <th>PRODUCTO</th>
                                        <th>CANTIDAD</th>
                                        <th>PRECIO UNI.</th>
                                        <th>SUBTOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $total = 0;
                                        $cantidad = 0;
                                    @endphp
                                    @foreach ($t5 as $item)
                                        <tr>
                                            <td align="center">{{ $i }}</td>
                                            <td>{{ $item[0] }}</td>
                                            <td align="center">{{ $item[1] }}</td>
                                            <td align="right">{{ number_format($item[2],2,'.',',') }}</td>
                                            <td align="right">0.00</td>
                                        </tr>
                                        @php
                                            $i++;
                                            $cantidad = $cantidad + $item[1];
                                            $total = $total + $item[2];
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot class="table-success">
                                    <tr>
                                        <td colspan="2" align="right"><strong>TOTAL</strong></td>
                                        <td align="center"><strong>{{ $cantidad }}</strong></td>
                                        <td align="right"><strong>{{ number_format($total,2,'.',',') }}</strong></td>
                                        <td align="right"><strong>0.00</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            @endif
            </div>
        </div>
    @endif

</div>
</div>
