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

                <li class="nav-item d-lg-block me-1">
                    <a href="#" title="Cambiar mesero" class="btn btn-secondary"> 
                        CME
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <div class="row m-0">
        <div class="col-12">
            <h4>BIENVENIDO {{Config::get('nombre_usuario')}}</h4>
        </div>
    </div>
</nav>


<!-- END: Header-->
