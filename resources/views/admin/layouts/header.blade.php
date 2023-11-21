<!-- BEGIN: Header-->
<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a></li>
            </ul>
            <ul class="nav navbar-nav bookmark-icons d-none d-sm-block">
              <img src="{{ asset('img_admin/icono_pagina.png')}}" height="22px">
            </ul>
        </div>
    </div>

    @if (@isset($eb_data))
        <div class="row m-0 my-1">
            <div class="col-12 px-0">
                <ul class="nav navbar-nav align-items-center ms-auto">
                    @if (Config::get('extra_button'))
                        @foreach($eb_data as $key => $item)
                            <li class="nav-item d-lg-block me-1">
                                @if (@count($item['params']) > 0)
                                    <a href="{{ route(@$item['route']) }}/{{implode('/', $item['params'])}}" title="{{$item['tooltip']}}" class="px-1 btn btn-{{@$item['color'] ?: 'dark';}}"> 
                                @else
                                    @if (isset($item['attr']))
                                        <a href="#" title="{{$item['tooltip']}}" {{$item['attr']['attr']}}="{{$item['attr']['value']}}" class="px-1 btn btn-{{@$item['color'] ?: 'dark';}}">
                                    @else
                                        <a href="{{ route(@$item['route'])}}" title="{{$item['tooltip']}}" class="px-1 btn btn-{{@$item['color'] ?: 'dark';}}">
                                    @endif 
                                @endif

                                <i style="height: 1.3rem; width: 1.3rem;" data-feather="{{$item['feather']}}"></i>
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    @endif

    <div class="row m-0 w-100">
        <div class="col-12">
            <h5 class="d-inline-block" style="font-size: 14px;">BIENVENIDO </h5>
            <h5 class="d-inline-block" style="font-size: 14px;">
                <a href="#" onclick="modificarPerfil()" class="d-inline-block text-center p-0 text-dark fw-bold" title="Editar">
                    <label id="nombre_lider">{{Config::get('nombre_usuario')}}</label>
                    @if (Config::get('nombre_mesero') != '')
                        <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit"></i> 
                    @endif
                </a>
                @if (Config::get('nombre_mesero'))
                    / MESERO: {{Config::get('nombre_mesero')}}
                @endif
            </h5>
        </div>
    </div>
</nav>

<style>
    .header-navbar .row { width: 80%; }
    .navbar-container { display: inline-block !important; flex-basis: 0 !important; }
</style>


<!-- END: Header-->
