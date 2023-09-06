<nav class="header-navbar navbar-expand-lg navbar navbar-fixed align-items-center navbar-shadow navbar-brand-center" data-nav="brand-center">
    <div class="navbar-container d-flex content">
      <a href="/" class="navbar-container d-flex content" style="height:57px;">
        <img src="{{asset('assetsFront/imgs/logoNM2.png')}}" width="200px" height="54px" style="margin-top:-10px;">
      </a>
        <ul class="nav navbar-nav align-items-center ms-auto">
          @guest
              @if (Route::has('login'))
                  <li >
                    <span>
                      <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </span>
                  </li>
              @endif

              @if (Route::has('register'))
                  {{-- <li class="nav-item">
                      <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                  </li> --}}
              @endif
          @else
            <li class="nav-item dropdown dropdown-user">
                <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{-- <div class="user-nav d-sm-flex d-none"><span class="user-name">{{ Auth::user()->name }}</span><i class="me-50" data-feather="arrow-down-circle"></i></div> --}}
                    <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder" style="text-transform: capitalize;">{{ Auth::user()->name }}<i class="me-50" data-feather="arrow-down-circle"></i></span></div>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                  <a class="dropdown-item" href="{{route('home')}}"><i class="me-50" data-feather="user"></i> Perfil</a>
                  <a class="dropdown-item" href="{{route('home')}}"><i class="me-50" data-feather="credit-card"></i> Comprar</a>
                  <a class="dropdown-item" href="{{route('orders')}}"><i class="me-50" data-feather="check-square"></i> Historial</a>
                  <!--
                  <a class="dropdown-item" href="app-chat.html"><i class="me-50" data-feather="message-square"></i> Chats</a>
                  -->
                    <div class="dropdown-divider"></div>
                  <!--
                    <a class="dropdown-item" href="page-account-settings-account.html"><i class="me-50" data-feather="settings"></i> Settings</a>
                    <a class="dropdown-item" href="page-pricing.html"><i class="me-50" data-feather="credit-card"></i> Pricing</a>
                    <a class="dropdown-item" href="page-faq.html"><i class="me-50" data-feather="help-circle"></i> FAQ</a>
                  -->
                  <a href="{{ route('logout') }}" class="dropdown-item"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                                     <i class="me-50" data-feather="power"></i>
                        {{ __('Logout') }}
                    </a></span>
                          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                              @csrf
                          </form>

                </div>
            </li>
          @endguest

        </ul>
    </div>
</nav>
