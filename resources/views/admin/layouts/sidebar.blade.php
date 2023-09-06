<!-- BEGIN: Main Menu-->
<div class="col-12 col-md-3 main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">
              <span class="brand-logo" >
                <img src="{{ asset('img_admin/icono_pagina.png')}}" width="40px" style="margin-top:10px;" >
              </span>
            </li>
        </ul>
    </div>
    <!--
    <div class="shadow-bottom">dddddd</div>
    -->
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
          @if(count($menubar["groups"])>0)
           @foreach ($menubar["groups"] as $key=>$permissions)
             <li class=" nav-item"><a class="d-flex align-items-center" href="index.html">
               <i class="ficon" data-feather="{{$permissions["info"]["icon"]}}"></i><span class="menu-title text-truncate" data-i18n="Dashboards">
                 {{$key}}
               </span></a>
                @if(count($permissions["access"])>0)
                  <ul class="menu-content">
                    @foreach($permissions["access"] AS $keys=>$values)

                      <li><a class="d-flex align-items-center" href="{{route('admin.'.$values["perm"])}}"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Analytics">{{$values["name"]}}</span></a>
                      </li>
                    @endforeach
                  </ul>
                @endif
             </li>
           @endforeach
          @endif
          {{-- <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="pie-chart"></i><span class="menu-title text-truncate" data-i18n="Invoice">Ventas</span></a>
              <ul class="menu-content">
                  <li><a class="d-flex align-items-center" href="app-invoice-list.html"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Ventas generales</span></a>
                  </li>
                  <li><a class="d-flex align-items-center" href="app-invoice-preview.html"><i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="Preview">Ventas por promotor</span></a>
                  </li>
              </ul>
          </li> --}}
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
