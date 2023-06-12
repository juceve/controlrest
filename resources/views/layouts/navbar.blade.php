<div class="navbar-custom ">

    <ul class="list-unstyled topbar-menu float-end mb-0">

        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#"
                role="button" aria-haspopup="false" aria-expanded="false">
                <span class="account-user-avatar">
                    <img src="{{asset('img/admin/avatar.png')}}" alt="user-image" class="rounded-circle">
                </span>
                <span>
                    <span class="account-user-name text-success">{{Auth::user()->name}}</span>
                    @php
                    $roles = Auth::user()->roles->first();
                    @endphp
                    @if (!is_null($roles))
                    <span class="account-position">{{Auth::user()->roles->pluck('name')[0];}}</span>
                    @endif

                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                <!-- item-->
                <div class=" dropdown-header noti-title">
                    <h6 class="text-overflow m-0 d-none d-lg-block">Bienvenido!</h6>
                    <span class="d-block d-lg-none">
                        <span class="account-user-name text-success">{{Auth::user()->name}}</span><br>
                        @if (!is_null($roles))
                        <span class="account-position">{{Auth::user()->roles->pluck('name')[0];}}</span>
                        @endif

                    </span>
                </div>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="mdi mdi-account-circle me-1"></i>
                    <span>Mi Perfil</span>
                </a>

                <!-- item-->
                <a class="dropdown-item notify-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                    <i class="mdi mdi-logout me-1"></i>
                    <span>Salir</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>

    </ul>
    <button class="button-menu-mobile open-left">
        <i class="mdi mdi-menu"></i>
    </button>
    @if (!is_null(Auth::user()->sucursale_id))
    <div class="mt-3">
        <small>
            <strong>Sucursal</strong>: {{Auth::user()->sucursale->nombre}}
        </small>
    </div>

    @endif
</div>