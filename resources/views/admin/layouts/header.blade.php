<!-- BEGIN: Header-->
<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a></li>
            </ul>
            <ul class="nav navbar-nav bookmark-icons">
              <img src="{{ asset('img_admin/logo-plus-negro.png')}}" height="22px">
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ms-auto">

            @if (Config::get('extra_button'))
                @foreach($eb_data as $key => $item)
                    <li class="nav-item d-lg-block me-1">
                        @if (@count($item['params']) > 0)
                            <a href="{{ route(@$item['route']) }}/{{implode('/', $item['params'])}}" title="{{$item['titulo']}}" class="btn btn-secondary"> 
                        @else
                            <a href="{{ route(@$item['route'])}}" title="{{$item['titulo']}}" class="btn btn-secondary"> 
                        @endif
                            {{$item['titulo']}} 
                        </a>
                    </li>
                @endforeach
            @endif
          <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder">{{ Auth::user()->ncomplet }}</span>
                      <span class="user-status">{{Auth::user()->role->nacsuser }}</span>
                    </div><span class="avatar"><img class="round" src="{{ asset('img_admin/icono_pagina.png')}}" alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                    <a class="dropdown-item" href="{{ route('admin.users.acceso') }}">
                        <i class="me-50" data-feather="lock"></i> Cambiar contraseña
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.logout') }}">
                        <i class="me-50" data-feather="power"></i> Cerrar sesión
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<ul class="main-search-list-defaultlist-other-list d-none">
    <li class="auto-suggestion justify-content-between"><a class="d-flex align-items-center justify-content-between w-100 py-50">
            <div class="d-flex justify-content-start"><span class="me-75" data-feather="alert-circle"></span><span>No results found.</span></div>
        </a></li>
</ul>
<!-- END: Header-->
