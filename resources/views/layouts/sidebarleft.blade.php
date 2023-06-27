<!-- ========== Left Sidebar Start ========== -->
<div class="leftside-menu">

    <!-- LOGO -->
    <a href="{{route('home')}}" class="logo text-center logo-light text-white">
        <span class="logo-lg">

            <img src="{{asset('img/admin/favicon_food.png')}}" alt="" height="30">
            <strong>{{config('app.name')}}</strong>
        </span>
        <span class="logo-sm">
            <img src="{{asset('img/admin/favicon_food.png')}}" alt="" height="30">
        </span>
    </a>

    <div class="h-100" id="leftside-menu-container" data-simplebar="">

        <!--- Sidemenu -->
        <ul class="side-nav">
            @can('home')
            <li class="side-nav-item">
                <a href="{{route('home')}}" class="side-nav-link">
                    <i class="uil-dashboard"></i>
                    <span> Dashboard </span>
                </a>
            </li>
            @endcan
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#entregas" aria-expanded="false" aria-controls="entregas"
                    class="side-nav-link">
                    <i class="uil-restaurant"></i>
                    <span> Entregas </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="entregas">
                    <ul class="side-nav-second-level">
                        @can('entregas.individual')
                        <li>
                            <a href="{{route('entregas.individual')}}"><i class="uil-chat-bubble-user"></i>
                                Individual</a>
                        </li>
                        @endcan
                        @can('entregas.porcurso')
                        <li>
                            <a href="{{route('entregas.porcurso')}}"><i class="uil-trees"></i>
                                Por Curso</a>
                        </li>
                        @endcan
                        @can('entregas.profesores')
                        <li>
                            <a href="{{route('entregas.profesores')}}"><i class="uil-book-reader"></i>
                                Profesores</a>
                        </li>
                        @endcan
                        @can('entregas.porcurso')
                        <li>
                            <a href="{{route('entregas.noentregados')}}"><i class="uil-stopwatch-slash"></i>
                                No entregados</a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#ventas" aria-expanded="false" aria-controls="ventas"
                    class="side-nav-link">
                    <i class="uil-shop"></i>
                    <span> Ventas </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="ventas">
                    <ul class="side-nav-second-level">
                        @can('ventas.pos')
                        <li>
                            <a href="{{route('ventas.pos')}}"><i class="uil-shopping-basket"></i> Punto de Venta</a>
                        </li>
                        @endcan
                        @can('reservas.nueva')
                        <li>
                            <a href="{{route('reservas.nueva')}}"><i class="uil-phone"></i> Compras - Reservas</a>
                        </li>
                        @endcan
                        @can('ventas.bonos')
                        <li>
                            <a href="{{route('ventas.bonoanual')}}"><i class="uil-briefcase"></i> Bonos Anuales</a>
                        </li>
                        @endcan

                        @can('ventas.bonos')
                        <li>
                            <a href="{{route('ventas.bonofecha')}}"><i class="uil-schedule"></i> Bonos por Fecha</a>
                        </li>
                        @endcan                       
                        @can('ventas.index')
                        <li>
                            <a href="{{route('ventas.index')}}"><i class="uil-list-ul"></i> Listado</a>
                        </li>
                        @endcan
                        @can('ventas.cierrecaja')
                        <li>
                            <a href="{{route('ventas.cierrecaja')}}"><i class=" uil-money-withdraw"></i> Cierres de
                                Caja POS</a>                                
                        </li>
                        <li>
                            <a href="{{route('ventas.cierres')}}"><i class=" uil-money-withdraw"></i> Cierres de
                                Caja</a>                                
                        </li>
                        @endcan
                        
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#pagos" aria-expanded="false" aria-controls="pagos"
                    class="side-nav-link">
                    <i class="uil-money-withdrawal"></i>
                    <span> Pagos </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="pagos">
                    <ul class="side-nav-second-level">
                        @can('ventas.vpagos')
                        <li>
                            <a href="{{route('ventas.vpagos')}}"><i class="uil-stopwatch"></i> Pagos Pendientes</a>
                        </li>
                        @endcan
                        @can('pagos.profesores')
                        <li>
                            <a href="{{route('pagos.profesores')}}"><i class="uil-receipt"></i> Credito Profesores</a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#clientes" aria-expanded="false" aria-controls="clientes"
                    class="side-nav-link">
                    <i class="uil-folder-lock"></i>
                    <span> Clientes </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="clientes">
                    <ul class="side-nav-second-level">
                        @can('tutores.index')
                        <li>
                            <a href="{{route('tutores')}}"><i class="uil-user-square"></i> Tutores</a>
                        </li>
                        @endcan
                        @can('estudiantes.index')
                        <li>
                            <a href="{{route('estudiantes.index')}}"><i class="uil-book-reader"></i> Estudiantes</a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#menus" aria-expanded="false" aria-controls="menus"
                    class="side-nav-link">
                    <i class="uil-box"></i>
                    <span> Menus </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="menus">
                    <ul class="side-nav-second-level">
                        @can('menus.eventos')
                        <li>
                            <a href="{{route('programarmenu')}}"><i class="uil-calendar-alt"></i> Programa Semanal</a>
                        </li>
                        @endcan
                        @can('menus.index')
                        <li>
                            <a href="{{route('menus.index')}}"><i class="uil-list-ul"></i> Listado</a>
                        </li>
                        @endcan
                        @can('menus.create')
                        <li>
                            <a href="{{route('elaborarmenu',["id"=>0,"dup"=>0])}}"><i class="uil-plus"></i> Nuevo</a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#productos" aria-expanded="false" aria-controls="productos"
                    class="side-nav-link">
                    <i class="uil-box"></i>
                    <span> Productos </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="productos">
                    <ul class="side-nav-second-level">
                        @can('items.index')
                        <li>
                            <a href="{{route('items.index')}}"><i class="uil-list-ul"></i> Listado</a>
                        </li>
                        @endcan
                        @can('items.create')
                        <li>
                            <a href="{{route('items.create')}}"><i class="uil-plus"></i> Nuevo</a>
                        </li>
                        @endcan
                        @can('catitems.index')
                        <li>
                            <a href="{{route('catitems.index')}}"><i class="uil-tag-alt"></i> Categorias</a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#reportes" aria-expanded="false" aria-controls="reportes"
                    class="side-nav-link">
                    <i class="uil-file-bookmark-alt"></i>
                    <span> Reportes </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="reportes">
                    <ul class="side-nav-second-level">
                        {{-- @can('reportes.diario') --}}
                        <li>
                            <a href="{{route('reportes.diario')}}"><i class="uil-file-alt"></i> Control Almuerzos</a>
                        </li>
                        {{-- @endcan --}}
                        {{-- @can('ventas.vpagos') --}}
                        <li>
                            <a href="{{route('reportes.ventas')}}"><i class="uil-receipt"></i> Reporte Ventas</a>
                        </li>
                        {{-- @endcan --}}
                    </ul>
                </div>
            </li>
            @can('conf.parametros')
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#Parametros" aria-expanded="false" aria-controls="Parametros"
                    class="side-nav-link">
                    <i class="uil-sliders-v-alt"></i>
                    <span> Parametros </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="Parametros">
                    <ul class="side-nav-second-level">
                        @can('cursos.index')
                        <li>
                            <a href="{{route('cursos.index')}}"><i class="uil-books"></i> Cursos</a>
                        </li>
                        @endcan
                        @can('nivelcursos.index')
                        <li>
                            <a href="{{route('nivelcursos.index')}}"><i class="uil-notebooks"></i> Niveles Curso</a>
                        </li>
                        @endcan
                        @can('precios.index')
                        <li>
                            <a href="{{route('precios.index')}}"><i class="uil-bill"></i> Precio Menu</a>
                        </li>
                        @endcan
                        @can('ventasconfigs.index')
                        <li>
                            <a href="{{route('ventasconfigs.index')}}"><i class="uil-tag-alt"></i> Configs POS</a>
                        </li>
                        @endcan
                        @can('feriados.index')
                        <li>
                            <a href="{{route('feriados.index')}}"><i class="uil-star"></i> Feriados</a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
            @endcan
            @can('conf.configuraciones')
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#configuraciones" aria-expanded="false"
                    aria-controls="configuraciones" class="side-nav-link">
                    <i class="uil-cog"></i>
                    <span> Configuraciones </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="configuraciones">
                    <ul class="side-nav-second-level">
                        @can('admin.empresas.index')
                        <li>
                            <a href="{{route('admin.empresas.index')}}"><i class="uil-building"></i> Empresas</a>
                        </li>
                        @endcan
                        @can('admin.users.index')
                        <li>
                            <a href="{{route('admin.users.index')}}"><i class="uil-users-alt"></i> Usuarios</a>
                        </li>
                        @endcan
                        @can('admin.roles.index')
                        <li>
                            <a href="{{route('admin.roles.index')}}"><i class="uil-shield-check"></i> Roles y
                                Permisos</a>
                        </li>
                        @endcan
                        @can('admin.sucursales.index')
                        <li>
                            <a href="{{route('admin.sucursales.index')}}"><i class="uil-cell"></i> Sucursales</a>
                        </li>
                        @endcan
                        @can('monedas.index')
                        <li>
                            <a href="{{route('monedas.index')}}"><i class="uil-usd-circle"></i> Moneda</a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
            @endcan
            <li class="side-nav-item">
                <a href="/" class="side-nav-link">
                    <i class="uil-arrow-circle-left"></i>
                    <span> Volver al Inicio </span>
                </a>
            </li>

        </ul>
        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->