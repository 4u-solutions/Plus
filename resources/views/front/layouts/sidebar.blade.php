<!-- Sidebar menu-->
{{-- {{dd(Auth::user()->superuser)}} --}}
{{-- {{dd(Route::has('readings.index'))}} --}}
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
  <div class="side-tab-body p-0 border-0" id="sidemenu-Tab">
    <div class="first-sidemenu">
      <div class="line-animations">
        <ul class="resp-tabs-list hor_1">
          @if(count($menubar["groups"])>0)
           @foreach ($menubar["groups"] as $permissions)
             <li class="">
                <span class="side-menu__icon"
                data-toggle="tooltip" data-placement="right"
                title="" data-original-title="{{$permissions['name']}}">
                  <i class="fa fa-{{$permissions['icon']}}"></i>
                </span>
             </li>
           @endforeach
          @endif
        </ul>
      </div>
    </div>
    <div class="second-sidemenu">
      <div class="resp-tabs-container hor_1">
        @if(count($menubar["access"])>0)
         @foreach ($menubar["access"] as $accedes)
            <div class="resp-tab-content-active">
              <div class="row">
                <div class="col-md-12">
                  <div class="panel sidetab-menu">test
                    <div class="panel-body tabs-menu-body p-0 border-0">
                      <div class="tab-content">
                        <div class="tab-pane active " id="side1">
                          <h5 class="mt-2 fs-15 font-weight-semibold mb-3">{{$accedes[0]["group"]}}</h5>

                          @foreach ($accedes as $key => $page)
                            {{-- {!! Route::has($page["perm"].'.index')?' --}}
                              {!!'<a class="slide-item" href="'.route('admin.'.$page["perm"].'.'.'index').'">'.
                                $page["name"].'</a>'!!}
                                                                    {{-- '.$page["name"].'</a>':'' !!} --}}
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
         @endif
      </div>
    </div>
  </div>
  <style media="screen">
    .popover {
      background: green;
    }
  </style>
</aside>
<!-- Sidemenu closed -->
