<div>
    @php
        $colores = ['primary','success',  'info', 'warning', 'danger', 'secondary'];
        $i = 0;
    @endphp
    {{-- @dump($resultados) --}}
    <div class="content">
        <table class="table table-bordered table-striped" style="background-color: #ffffffbb; font-size: 13px;">
            <thead>
                <tr style="vertical-align: middle">
                    <th class="table-secondary">PRODUCTO</th>
                    @foreach ($eventos as $evento)
                    <td align="center" class="table-{{$colores[$i]}}"><strong>{{nombreDiaESP($evento->fecha)}} <br> {{substr($evento->fecha,8,2)}}-{{substr(nombreMesESP($evento->fecha),0,3)}}</strong></td>
                    @php
                        $i++;
                    @endphp
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $b = 0;
                    $i = 0;
                @endphp
                @foreach ($arrXCategorias as $item)
                
                @if ($b == 0)
                <tr>
                    <td class="table-secondary"><strong>{{$item['catitem']}}</strong></td>
                    @php
                        $b=1;
                    @endphp
                @endif
                <td align="center">{{$item['producto']}}</td>
                @php
                    if ($i < $eventos->count()-1) {
                        $i++;
                    }else{
                        echo "</tr>";
                        $b = 0;
                        $i = 0;
                    }
                    
                @endphp
                
                @endforeach
            </tbody>
        </table>
    </div>
    <h5 class="text-success text-center"><strong>PLATOS EXTRAS</strong></h5>
    <div class="content">
        <table class="table table-bordered"  style="background-color: #ffffffbb; font-size: 13px;">
            <tbody>
                @if ($resultados2)
                @foreach ($resultados2 as $item)
                    <tr >
                        <td class="table-success"><strong>{{$item->nombre}}</strong></td>
                        <td>{{$item->descripcion}}</td>
                    </tr>
                @endforeach
                    
                @endif
            </tbody>
        </table>
    </div>











    {{-- <div class="container-fluid">
        @if ($eventos->count() > 0)
            <div class="row justify-content-center">
                @php
                    $extras = [];
                @endphp
                @foreach ($eventos as $item)
                    <div class="col-12 col-md-4  mb-2">
                        <div class="card" style="height: 100%">
                            <div class="card-header text-white bg-{{ $colores[$i] }} text-center">
                                <small>{{ fechaes($item->fecha) }}</small>
                            </div>
                            <div class="card-body">
                                @php
                                    $detalles = $item->detalleeventos;
                                    $i++;
                                @endphp
                                @foreach ($detalles as $detalle)
                                    @if ($detalle->menu->tipomenu->nombre == 'EXTRA')
                                        @php
                                            $extras[] = $detalle->menu;
                                        @endphp
                                    @else
                                        <h6 class="text-center text-success" style="font-size: 13px;">
                                            <strong>{{ $detalle->menu->tipomenu->nombre }}</strong>
                                        </h6>
                                        @php
                                            $detallemenus = $detalle->menu->detallemenuses;
                                        @endphp
                                        @foreach ($detallemenus as $dmenu)
                                            <span style="font-size: 12px;"><strong>{{ $dmenu->item->catitem->nombre }}:
                                                </strong>
                                                {{ $dmenu->item->nombre }}</span><br>
                                        @endforeach
                                        <hr>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <hr>
            <h3 class="text-center">PLATOS EXTRA</h3>
            <div class="row justify-content-center">
                @php
                    $x = 0;
                @endphp
                @foreach ($extras as $extra)
                    @if ($x < 2)
                        <div class="col-12 col-md-4  mb-3">
                            <div class="card" style="height: 100%">
                                <div class="card-header bg-success text-white text-center">
                                    <small>{{ $extra->nombre }}</small>
                                </div>
                                <div class="card-body">
                                    @php
                                        $dms = $extra->detallemenuses;
                                        
                                        foreach ($dms as $dm) {
                                            echo "<small class='card-text'><strong>" .
                                                $dm->item->catitem->nombre .
                                                ": </strong>
                            " .
                                                $dm->item->nombre .
                                                '</small> <br>';
                                        }
                                    @endphp
                                </div>
                            </div>
                        </div>
                    @endif
                    @php
                        $x++;
                    @endphp
                @endforeach
            </div>

        @endif
    </div> --}}
</div>

@php
    function get_nombre_dia($fecha)
    {
        $fechats = strtotime($fecha);
    
        switch (date('w', $fechats)) {
            case 0:
                return 'Domingo';
                break;
            case 1:
                return 'Lunes';
                break;
            case 2:
                return 'Martes';
                break;
            case 3:
                return 'Miercoles';
                break;
            case 4:
                return 'Jueves';
                break;
            case 5:
                return 'Viernes';
                break;
            case 6:
                return 'Sabado';
                break;
        }
    }
    
    function fechaEs($fecha)
    {
        $fecha = substr($fecha, 0, 10);
        $numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));
        $dias_ES = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
        $dias_EN = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $nombredia = str_replace($dias_EN, $dias_ES, $dia);
        $meses_ES = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $meses_EN = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
        return $nombredia . ' ' . $numeroDia . ' de ' . $nombreMes;
    }
    
@endphp
