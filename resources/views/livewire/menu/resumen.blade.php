<div>
    <div class="container-fluid text-center py-3 text-warning" style="background-color: #10620093">
        <h5>MENU SEMANAL</h5>
    </div>
    <div class="content p-2">
        <div class="container py-3" style="background-color: #ffffffc9">
            <div class="row d-flex justify-content-center align-items-center ">
                <div class="col-12 col-md-7">
                    @if(!empty($successMessage))
                    <div class="alert alert-success">
                        {{ $successMessage }}
                    </div>
                    @endif

                    <div class="text-center">
                        <!-- progressbar -->
                        <ul class="progressbar">
                            <li></li>
                            <li class="active"></li>
                            <li></li>
                            <li></li>
                        </ul>
                    </div>
                    <div class="row setup-content" id="step-2">
                        <div class="col-xs-12">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-8">
                                        <h3>Resumen de Pedido</h3>
                                    </div>
                                    <div class="col-4 text-end" style="vertical-align: middle">
                                        <button type="button" class="btn btn-sm btn-warning position-relative">
                                            <i class="bi bi-cart4"></i> <span
                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">{{$countCart}}
                                            </span>
                                        </button>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover fontSm">
                                        <thead>
                                            <tr class="bg-primary text-white" style="background-color: #4070d8">
                                                <th width="75">FECHA</th>
                                                <th>PRODUCTO</th>
                                                <th width="12"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($cartMenu->count()>0)
                                            @foreach ($cartMenu as $cart)
                                            @php
                                            $fechaNum = strtotime($cart->fecha);
                                            $mes = date('m',$fechaNum);
                                            $dia = date('d',$fechaNum);
                                            $mes = mesEs($mes);
                                            @endphp
                                            <tr style="vertical-align: middle;">
                                                <td>
                                                    <span>{{$dia.'-'.$mes}}</span>
                                                </td>
                                                <td>
                                                    <strong>{{$cart->menu->nombre}}</strong>
                                                    <br>
                                                    <small>
                                                        {{$cart->menu->descripcion}}
                                                    </small>

                                                </td>
                                                <td>
                                                    <button class="btn btn-outline-danger" title="Eliminar"
                                                        onclick="event.preventDefault(); remCartMenu({{$cart->id}},'{{$cart->menu->nombre}}');">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="3" align="center">No existen pedidos!</td>
                                            </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                </div>
                                <div style="float: right">
                                    <a href="{{route('menusemanal')}}"
                                        class="btn btn-secondary nextBtn btn-lg pull-right" type="button">
                                        <i class="bi bi-chevron-left"></i> Pre
                                    </a>
                                    <button wire:click="next" class="btn btn-success nextBtn btn-lg pull-right"
                                        type="button">
                                        Sig <i class="bi bi-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    @section('css')
    <link rel="stylesheet" href="{{asset('web2/css/wizard.css')}}">
    @endsection
    @section('js')
    <script src="{{asset('web2/js/resumenpedido.js')}}"></script>
    @endsection
    @php
    function mesEs($mes){
    switch ($mes) {
    case '01':
    return 'Ene';
    break;
    case '02':
    return 'Feb';
    break;
    case '03':
    return 'Mar';
    break;
    case '04':
    return 'Abr';
    break;
    case '05':
    return 'May';
    break;
    case '06':
    return 'Jun';
    break;
    case '07':
    return 'Jul';
    break;
    case '08':
    return 'Ago';
    break;
    case '09':
    return 'Sep';
    break;
    case '10':
    return 'Oct';
    break;
    case '11':
    return 'Nov';
    break;
    case '12':
    return 'Dic';
    break;
    }
    }

    @endphp