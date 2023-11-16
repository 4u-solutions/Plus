{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
@php $public_path = (strpos(getcwd(), 'themanorgt') ? getcwd() :  (substr(getcwd(), 0, strrpos(getcwd(), '/')) . '/public')) . '/'; @endphp
  <div class="row">
    <div class="col-12 col-sm-6 col-md-6 col-lg-5 mx-auto">
      <div class="card">

        @if (File::exists($public_path . 'covers/' . substr($evento->fecha, 5, 5) . '.mp4'))
          <video id="video-cover" width="100%" height="auto" loop="loop" autoplay="autoplay" controls>
            <source src="{{asset('covers/' . substr($evento->fecha, 5, 5) . '.mp4')}}" type="video/mp4">
            Your browser does not support the video tag.
          </video>
        @elseif (File::exists($public_path . 'covers/' . substr($evento->fecha, 5, 5) . '.jpg'))
          <img src="{{asset('covers/' . substr($evento->fecha, 5, 5) . '.jpg')}}" class="w-100">
        @endif

        <div class="col-12 px-2 mb-1">
          <a href="http://www.jblgt.shop" target="_blank" class="btn btn-jbl d-block fs-3 mt-1">
            <br><br>
          </a>
        </div>

        <div class="row px-1">
          @if (@count($mesas_asignadas) >= 1)
            <div class="col-12 mb-1">
              <a href="#" class="btn btn-dark d-block m-auto d-block fs-3 mt-1 btn-temporada" data-tab="contenedor-cordinadores" id="mesas-pane" style="background: #fde54d !important; border-color: #fde54d !important; color: #000 !important;">
                <i style="height: 1.6rem; width: 1.6rem;" data-feather="grid"></i> TU MESA Y MESERO ASIGNADOS 
              </a>

              <div class="row mesas-contenedor mb-1" id="contenedor-mesas" style="display: none;">
                <div class="col-12">
                  @if ($mesa->id_celebracion)
                    <img src="{{asset('celebraciones/1.jpg')}}" class="w-100">
                  @endif

                  <h1 class="my-1">Tu{{count($mesas_asignadas) > 1 ? 's' : ''}} mesa{{count($mesas_asignadas) > 1 ? 's' : ''}}</h1>
                  @foreach($mesas_asignadas as $key => $item)
                    @if (File::exists($public_path . 'mesas/' . $item->id_venue . '/' . $item->nombre . $item->no_mesa . '.' . ($evento->id == 11 ? 'gif' : 'jpg')))
                      <img src="{{asset('mesas/' . $item->id_venue . '/' . $item->nombre . $item->no_mesa . '.' . ($evento->id == 11 ? 'gif?' : 'jpg?'))}}" class="w-100 mb-1">
                    @endif
                  @endforeach

                  @if (File::exists($public_path . 'colaboradores/' . $mesa->id_jefe_1 . '.jpg'))
                    <h1 class="mb-1">Tu{{($mesa->id_jefe_1 && $mesa->id_jefe_2) ? 's' : ''}} Jefe{{($mesa->id_jefe_1 && $mesa->id_jefe_2) ? 's' : ''}} de área</h1>
                    <img src="{{asset('colaboradores/' . $mesa->id_jefe_1 . '.jpg')}}" class="w-100">

                    @if (File::exists($public_path . 'colaboradores/' . $mesa->id_jefe_2 . '.jpg'))
                      <img src="{{asset('colaboradores/' . $mesa->id_jefe_2 . '.jpg')}}" class="w-100">
                    @endif  
                  @endif

                  @if (File::exists($public_path . 'colaboradores/' . $mesa->id_cobrador_1 . '.jpg'))
                    <h1 class="my-1">Tu{{($mesa->id_cobrador_1 && $mesa->id_cobrador_2) ? 's' : ''}} coordinador{{($mesa->id_cobrador_1 && $mesa->id_cobrador_2) ? 'es' : ''}}</h1>
                    <img src="{{asset('colaboradores/' . $mesa->id_cobrador_1 . '.jpg')}}" class="w-100">

                    @if (File::exists($public_path . 'colaboradores/' . $mesa->id_cobrador_2 . '.jpg'))
                      <img src="{{asset('colaboradores/' . $mesa->id_cobrador_2 . '.jpg')}}" class="w-100">
                    @endif
                  @endif

                  @if (File::exists($public_path . 'colaboradores/' . $mesa->id_mesero . '.jpg'))
                    <h1 class="my-1">Tu{{($mesa->id_mesero && $mesa->id_mesero_2) ? 's' : ''}} mesero{{($mesa->id_mesero && $mesa->id_mesero_2) ? 's' : ''}}</h1>
                    <img src="{{asset('colaboradores/' . $mesa->id_mesero . '.jpg')}}" class="w-100">

                    @if (File::exists($public_path . 'colaboradores/' . $mesa->id_mesero_2 . '.jpg'))
                      <img src="{{asset('colaboradores/' . $mesa->id_mesero_2 . '.jpg')}}" class="w-100">
                    @endif
                  @endif
                </div>
              </div>
            </div>
          @endif
        </div>
        
        <div class="col-12 mt-1 text-center">
          <label class="pb-1 fs-1 fw-bold"> Lista de {{$mesa->nombre}} </label>
        </div>

        <div class="row">
          <div class="col-6 pe-0 border-w cursor-pointer" data-tab="contenedor-lista" id="tab-pane">
            <div class="bg-secondary p-1 border-white text-light text-center"> LISTA </div>
          </div>
          
          <div class="col-6 ps-0 cursor-pointer" data-tab="contenedor-info" id="tab-pane">
            <div class="bg-dark p-1 border-white text-light text-center"> INFO </div>
          </div>

          <div class="col-6 pe-0 cursor-pointer" data-tab="contenedor-dj" id="tab-pane">
            <div class="bg-dark p-1 border-white text-light text-center"> DJ's </div>
          </div>

          <div class="col-6 ps-0 cursor-pointer" data-tab="contenedor-sataff" id="tab-pane">
            <div class="bg-dark p-1 border-white text-light text-center"> STAFF </div>
          </div>
        </div>

        <div class="card-body pt-1 tab-contenedor" id="contenedor-lista">
          <div class="row">
            <div class="col-12">
              <div class="row">
                @if ($mesa->pull && $mesa->id_pull)
                  <div class="col-12 text-center mb-1">
                    <label class="fs-1 fw-bold d-block pb-0"> PULL DE LA MESA </label>
                    <label class="fs-5 d-block fw-bold text-center">{{$mesa->pull}}</label>
                    <label class="fs-5 d-block">
                      <b>Total</b>: Q. <span id="pull_total">{{(($total_mujeres ?: 0) * $pull->monto_mujeres) + (($total_hombres ?: 0) * $pull->monto_hombres)}}</span>
                    </label>
                    <label class="fs-5 d-block">
                      <b>Pagado</b>: Q. <span id="pull_pagado">{{(($pull_pagado_mujeres ?: 0) * $pull->monto_mujeres) + (($pull_pagado_hombres ?: 0) * $pull->monto_hombres) + ($mesa->id == 145 ? 950 : 0)}}</span>
                    </label>
                  </div>
                @endif

                <div class="col-6 text-center">
                  <label class="pb-1 fs-5 fw-bold"> Mujeres </label>
                </div>
                <div class="col-6 text-center">
                  <label class="pb-1 fs-5 fw-bold"> Hombres </label>
                </div>

                @for ($i = 1; $i <= ceil($mesa->pax * 0.6); $i++)
                  @php $item_m = @$data_m[$i.'-0']; @endphp
                  @php $item_h = @$data_h[$i.'-1']; @endphp

                  @if (@$item_m->pagado)
                    @php $bg_invitado = 'bg-success text-dark'; @endphp
                  @elseif (@$item_m->cortesia)
                    @php $bg_invitado = 'bg-secondary text-dark'; @endphp
                  @elseif (@$item_m->pagado && @$item_m->repetido)
                    @php $bg_invitado = 'bg-success text-dark'; @endphp
                  @elseif (!@$item_m->pagado && @$item_m->repetido)
                    @php $bg_invitado = 'bg-warning text-dark'; @endphp
                  @else
                    @php $bg_invitado = ''; @endphp
                  @endif

                  @if (@$data_m[$i.'-0'])
                    @if (!@$item_m->telefono || !@$item_m->correo || !@$item_m->fecha_nacimiento)
                      <div class="col-6 border celda_{{$i}}_1 {{$bg_invitado}}" data-pagado="{{@$item_m->pagado}}" data-repetido="{{@$item->repetido}}">
                    @else
                      <div class="col-6 border celda_{{$i}}_1 {{$bg_invitado}}" data-pagado="{{@$item_m->pagado}}" data-repetido="{{@$item->repetido}}" style="background: url('/img_admin/fuegos.gif') top center; background-size: cover;">
                    @endif
                      @if (@$item_m->id)
                        <div class="row">
                          <div class="col-2">
                            <label class="py-1 fs-5">{{$i}}</label>
                          </div>
                          <div class="col-7 text-center">
                            <label class="py-1 fs-5 {{$item_m->repetido ? 'pb-0' : ''}}" id="nombre_invitado_{{$item_m->id}}"> {!! html_entity_decode($item_m->nombre) !!} </label>
                          </div>
                          <div class="col-2">
                            <a href="#" onclick="modificarPerfil({{$item_m->id}})" class="d-block text-center pt-1 text-dark fw-bold" title="Editar" id="modificar_perfil">
                              <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit"></i> 
                            </a>

                            @if (($evento->pagado && !$item_m->pagado) || ($mesa->pull && $mesa->id_pull && !$item_m->pull_pagado))
                              <!--
                              <a href="#" onclick="realizarPago({{$item_m->id}})" class="d-block text-center text-dark pt-1 fw-bold" title="Pagar" id="realizar_pago">
                                <i style="height: 1.8rem; width: 1.8rem;" data-feather="credit-card"></i> 
                              </a>
                            -->
                            @endif
                          </div>

                        <div class="col-12 text-center">
                          @if (@$item_m->es_menor)
                            <span class="d-block text-center mb-1 bg-warning text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;">
                              Es menor de edad
                            </span>
                          @endif

                          @if (!@$item_m->telefono || !@$item_m->correo || !@$item_m->fecha_nacimiento)
                            <span class="d-block text-center mb-1 bg-light text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="completar_informacion">
                              No ha ingresado información
                            </span>
                          @else
                            <span class="d-block text-center mb-1 text-dark fw-bold text-center rounded" style="padding: 5px !important; font-size: 12px !important;" id="completar_informacion">
                              <i style="height: 1.8rem; width: 1.8rem;" data-feather="thumbs-up"></i> 
                              Gracias por ingresar su información
                            </span>
                          @endif

                          @if (@$evento->pagado)
                            @if (!@$item_m->pagado && !@$item_m->cortesia)
                              <span class="d-block text-center mb-1 bg-light text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="pendiente_pagar_boleto">
                                No ha pagado cover
                              </span>
                            @endif
                          @endif

                          @if ($mesa->pull && $mesa->id_pull)
                            @if ($item_m->pull_pagado == 2)
                              <span class="d-block text-center mb-1 bg-light text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="pendiente_boleta">
                                Verificando Transferencia
                              </span>
                            @elseif ($item_m->pull_pagado == 0)
                              <span class="d-block text-center mb-1 bg-light text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="pendiente_pull">
                                No ha pagado Pull
                              </span>
                            @elseif ($item_m->pull_pagado == 1)
                              <span class="d-block text-center mb-1 text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="pago_pull_realizado">
                                Pull pagado
                              </span>
                            @endif
                          @endif

                          @if (@$item_m->repetido)
                            <span class="d-block text-center mb-1 bg-warning text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="alerta_duplicado">
                              Invitado duplicado
                            </span>
                          @endif
                        </div>
                          
                        </div>
                      @endif
                    </div>
                  @else
                    <div class="col-6 border"></div>
                  @endif

                  @if (@$item_h->pagado)
                    @php $bg_invitado = 'bg-success text-dark'; @endphp
                  @elseif (@$item_h->cortesia)
                    @php $bg_invitado = 'bg-secondary text-light'; @endphp
                  @elseif (@$item_h->pagado && @$item_h->repetido)
                    @php $bg_invitado = 'bg-success text-dark'; @endphp
                  @elseif (!@$item_h->pagado && @$item_h->repetido)
                    @php $bg_invitado = 'bg-warning text-dark'; @endphp
                  @else
                    @php $bg_invitado = ''; @endphp
                  @endif

                  @if (@$data_h[$i.'-1'])
                    @if (!@$item_h->telefono || !@$item_h->correo || !@$item_h->fecha_nacimiento)
                      <div class="col-6 border celda_{{$i}}_1 {{$bg_invitado}}" data-pagado="{{@$item_h->pagado}}" data-repetido="{{@$item->repetido}}">
                    @else
                      <div class="col-6 border celda_{{$i}}_1 {{$bg_invitado}}" data-pagado="{{@$item_h->pagado}}" data-repetido="{{@$item->repetido}}" style="background: url('/img_admin/fuegos.gif') top center; background-size: cover;">
                    @endif
                      @if (@$item_h->id)
                        <div class="row">
                          <div class="col-2">
                            <label class="py-1 fs-5">{{$i}}</label>
                          </div>
                          <div class="col-7 text-center">
                            <label class="py-1 fs-5 {{$item_h->repetido ? 'pb-0' : ''}}" id="nombre_invitado_{{$item_h->id}}"> {!! html_entity_decode($item_h->nombre) !!} </label>
                          </div>
                          <div class="col-2">
                            <a href="#" onclick="modificarPerfil({{$item_h->id}})" class="d-block text-center pt-1 text-dark fw-bold" title="Editar" id="modificar_perfil">
                              <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit"></i> 
                            </a>

                            @if (($mesa->pull && $mesa->id_pull) || ($evento->pagado && !$item_h->pagado))
                              <!--
                              <a href="#" onclick="realizarPago({{$item_h->id}})" class="d-block text-center pt-1 text-dark fw-bold" title="Pagar" id="realizar_pago">
                                <i style="height: 1.8rem; width: 1.8rem;" data-feather="credit-card"></i> 
                              </a>
                              -->
                            @endif
                          </div>
                        </div>

                        <div class="col-12 text-center">
                          @if (@$item_h->es_menor)
                            <span class="d-block text-center mb-1 bg-warning text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;">
                              Es menor de edad
                            </span>
                          @endif
                          
                          @if (!@$item_h->telefono || !@$item_h->correo || !@$item_h->fecha_nacimiento)
                            <span class="d-block text-center mb-1 bg-light text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="completar_informacion">
                              No ha ingresado información
                            </span>
                          @else
                            <span class="d-block text-center mb-1 text-dark fw-bold text-center rounded" style="padding: 5px !important; font-size: 12px !important;" id="completar_informacion">
                              <i style="height: 1.8rem; width: 1.8rem;" data-feather="thumbs-up"></i> 
                              Gracias por ingresar su información
                            </span>
                          @endif

                          @if (@$evento->pagado)
                            @if (!@$item_h->pagado && !@$item_h->cortesia)
                              <span class="d-block text-center mb-1 bg-light text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="pendiente_pagar_boleto">
                                No ha pagado cover
                              </span>
                            @endif
                          @endif

                          @if ($mesa->pull && $mesa->id_pull)
                            @if ($item_h->pull_pagado == 2)
                              <span class="d-block text-center mb-1 bg-light text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="pendiente_boleta">
                                Verificando Transferencia
                              </span>
                            @elseif ($item_h->pull_pagado == 0)
                              <span class="d-block text-center mb-1 bg-light text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="pendiente_pull">
                                No ha pagado Pull
                              </span>
                            @elseif ($item_h->pull_pagado == 1)
                              <span class="d-block text-center mb-1 text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="pago_pull_realizado">
                                Pull pagado
                              </span>
                            @endif
                          @endif

                          @if (@$item_h->repetido)
                            <span class="d-block text-center mb-1 bg-light text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="alerta_duplicado">
                              Invitado duplicado
                            </span>
                          @endif
                        </div>
                      @endif
                    </div>
                  @else
                    <div class="col-6 border"></div>
                  @endif
                @endfor
              </div>
            </div>
          </div>
        </div>

        <div class="card-body p-0 tab-contenedor" id="contenedor-dashboard" style="display: none;">
          <div class="row mt-1">
            <div class="col-12">

              <div id="grafica-invitados" class="mb-2" style="height: 400px;"></div>
              @if ($evento->pagado)
                <div id="grafica-pagados" class="mb-2" style="height: 400px;"></div>
                <div id="grafica-pendientes" style="height: 400px;"></div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-body p-0 tab-contenedor" id="contenedor-dj" style="display: none;">
          <div class="row">
            @for ($i = 1; $i <= 5; $i++)
              <div class="col-12">
                @if (File::exists($public_path . 'dj/' . $i . '_' . $evento->id . '.jpg'))
                  <img src="{{asset('dj/' . $i . '_' . $evento->id . '.jpg?')}}" class="w-100">
                @else
                  @if (File::exists($public_path . 'dj/' . $i . '_0.jpg'))
                    <img src="{{asset('dj/' . $i . '_0.jpg')}}" class="w-100">
                  @endif
                @endif
              </div>
            @endfor
          </div>
        </div>

        <div class="card-body p-0 tab-contenedor" id="contenedor-info" style="display: none;">
          <div class="row">
            <div class="col-12 text-center">
              <div class="col-12">
                @if (File::exists($public_path . 'boleta/' . $evento->id . '.jpg'))
                  <img src="{{asset('boleta/' . $evento->id . '.jpg')}}" class="w-100">
                @endif
              </div>

              <div class="col-12">
                @if (File::exists($public_path . 'ingreso/' . $evento->id . '.jpg'))
                  <img src="{{asset('ingreso/' . $evento->id . '.jpg?')}}" class="w-100">
                @else
                  <img src="{{asset('ingreso/' . (date('w', strtotime($evento->fecha))) . '_0.jpg')}}" class="w-100">
                @endif
              </div>

              <div class="col-12">
                <a href="{{$mesa->link_waze}}" target="_blank">
                  @if (File::exists($public_path . 'waze/' . $evento->id . '.jpg'))
                    <img src="{{asset('waze/' . $evento->id . '.jpg')}}" class="w-100">
                  @else
                    <img src="{{asset('waze/0.jpg')}}" class="w-100">
                  @endif
                </a>
              </div>

              <div class="col-12">
                @if (File::exists($public_path . 'eventos/iso_' . $evento->id . '.jpg'))
                  <img src="{{asset('eventos/iso_' . $evento->id . '.jpg?')}}" class="w-100">
                @else
                  @if ($evento->id_venue != 2)
                      @if (File::exists($public_path . 'eventos/iso_0.jpg'))
                        <img src="{{asset('eventos/iso_0.jpg')}}" class="w-100">
                      @endif
                  @endif
                @endif
              </div>

              <div class="col-12">
                @for ($i = 1; $i <= 5; $i++)
                  @if (File::exists($public_path . 'mapas/'. $i . '_' . $evento->id . '.jpg'))
                    <img src="{{asset('mapas/' . $i . '_' . $evento->id . '.jpg?')}}" class="w-100">
                  @else
                      @if ($evento->id_venue != 2)
                        @if (File::exists($public_path . 'mapas/' . $i . '_' . '_0.jpg'))
                          <img src="{{asset('mapas/' . $i . '_0.jpg?')}}" class="w-100">
                        @endif 
                      @endif
                  @endif
                @endfor
              </div>

              <div class="col-12">
                @for ($i = 1; $i <= 9; $i++)
                  @if (File::exists($public_path . 'menu/' . $i . '_' . $evento->id . '.jpg'))
                    <img src="{{asset('menu/' . $i . '_' . $evento->id . '.jpg?')}}" class="w-100">
                  @else
                    @if ($evento->id_venue != 2)
                      @if (File::exists($public_path . 'menu/' . $i .'_0.jpg'))
                        <img src="{{asset('menu/' . $i . '_0.jpg')}}" class="w-100">
                      @endif  
                    @endif
                  @endif
                @endfor
              </div>
            </div>
          </div>
        </div>

        <div class="card-body p-0 tab-contenedor" id="contenedor-sataff" style="display: none;">
          <div class="row">
            <div class="col-12">

              @if (count($jefes) > 0)
                <a href="#" id="personal-pane" class="btn btn-dark d-block m-auto d-block fs-3 mt-1 mx-1 btn-temporada" data-tab="contenedor-jefes">
                  <i style="height: 1.6rem; width: 1.6rem;" data-feather="users"></i> JEFES DE ÁREA 
                </a>

                <div class="row personal-contenedor" id="contenedor-jefes" style="display: none;">
                  <div class="col-12">
                    @foreach ($jefes as $key => $item)
                      @if (File::exists($public_path . 'colaboradores/' . $item->id . '.jpg'))
                        <img src="{{asset('colaboradores/' . $item->id . '.jpg')}}" class="w-100">
                      @endif
                    @endforeach
                  </div>
                </div>
              @endif

              @if (count($coordinadores) > 0)
                <a href="#" class="btn btn-dark d-block m-auto d-block fs-3 mt-1 mx-1 btn-temporada" data-tab="contenedor-cordinadores" id="personal-pane">
                  <i style="height: 1.6rem; width: 1.6rem;" data-feather="eye"></i> COORDINADORES 
                </a>

                <div class="row personal-contenedor" id="contenedor-cordinadores" style="display: none;">
                  <div class="col-12">
                    @foreach ($coordinadores as $key => $item)
                      @if (File::exists($public_path . 'colaboradores/' . $item->id . '.jpg'))
                        <img src="{{asset('colaboradores/' . $item->id . '.jpg')}}" class="w-100">
                      @endif
                    @endforeach
                  </div>
                </div>
              @endif

              @if (count($meseros) > 0)
                <a href="#" id="personal-pane" class="btn btn-dark d-block m-auto d-block fs-3 mt-1 mx-1 btn-temporada" data-tab="contenedor-meseros">
                  <i style="height: 1.6rem; width: 1.6rem;" data-feather="users"></i> MESEROS PLUS
                </a>

                <div class="row personal-contenedor" id="contenedor-meseros" style="display: none;">
                  <div class="col-12">
                    @foreach ($meseros as $key => $item)
                      @if (File::exists($public_path . 'colaboradores/' . $item->id . '.jpg'))
                        <img src="{{asset('colaboradores/' . $item->id . '.jpg')}}" class="w-100">
                      @endif
                    @endforeach
                  </div>
                </div>
              @endif

              @if (count($meseros_no_plus) > 0)
                <a href="#" id="personal-pane" class="btn btn-dark d-block m-auto d-block fs-3 mt-1 mx-1 btn-temporada" data-tab="contenedor-meseros-eventos">
                  <i style="height: 1.6rem; width: 1.6rem;" data-feather="users"></i> MESEROS EVENTOS
                </a>

                <div class="row personal-contenedor" id="contenedor-meseros-eventos" style="display: none;">
                  <div class="col-12">
                    @foreach ($meseros_no_plus as $key => $item)
                      @if (File::exists($public_path . 'colaboradores/' . $item->id . '.jpg'))
                        <img src="{{asset('colaboradores/' . $item->id . '.jpg')}}" class="w-100">
                      @endif
                    @endforeach
                  </div>
                </div>
              @endif

              @if (count($bartenders) > 0)
                <a href="#" class="btn btn-dark d-block m-auto d-block fs-3 mt-1 mx-1 btn-temporada" data-tab="contenedor-bartenders" id="personal-pane">
                  <i style="height: 1.6rem; width: 1.6rem;" data-feather="life-buoy"></i> BARTENDERS 
                </a>

                <div class="row personal-contenedor" id="contenedor-bartenders" style="display: none;">
                  <div class="col-12">
                    @foreach ($bartenders as $key => $item)
                      @if (File::exists($public_path . 'colaboradores/' . $item->id . '.jpg'))
                        <img src="{{asset('colaboradores/' . $item->id . '.jpg')}}" class="w-100">
                      @endif
                    @endforeach
                  </div>
                </div>
              @endif

              @if (count($bodegas) > 0)
                <a href="#" class="btn btn-dark d-block m-auto d-block fs-3 mt-1 mx-1 btn-temporada" data-tab="contenedor-bodegas" id="personal-pane">
                  <i style="height: 1.6rem; width: 1.6rem;" data-feather="eye"></i> TEC / BODEGA 
                </a>

                <div class="row personal-contenedor" id="contenedor-bodegas" style="display: none;">
                  <div class="col-12">
                    @foreach ($bodegas as $key => $item)
                      @if (File::exists($public_path . 'colaboradores/' . $item->id . '.jpg'))
                        <img src="{{asset('colaboradores/' . $item->id . '.jpg')}}" class="w-100">
                      @endif
                    @endforeach
                  </div>
                </div>
              @endif

              @if (count($seguridad) > 0)
                <a href="#" class="btn btn-dark d-block m-auto d-block fs-3 mt-1 mx-1 btn-temporada" data-tab="contenedor-bouncers" id="personal-pane">
                  <i style="height: 1.6rem; width: 1.6rem;" data-feather="shield"></i> SEGURIDAD 
                </a>

                <div class="row personal-contenedor" id="contenedor-bouncers" style="display: none;">
                  <div class="col-12">
                    @foreach ($seguridad as $key => $item)
                      @if (File::exists($public_path . 'colaboradores/' . $item->id . '.jpg'))
                        <img src="{{asset('colaboradores/' . $item->id . '.jpg')}}" class="w-100">
                      @endif
                    @endforeach
                  </div>
                </div>
              @endif

              @if (count($banos) > 0)
                <a href="#" class="btn btn-dark d-block m-auto d-block fs-3 mt-1 mx-1 btn-temporada" data-tab="contenedor-banos" id="personal-pane">
                  <i style="height: 1.6rem; width: 1.6rem;" data-feather="trash-2"></i> BAÑOS 
                </a>

                <div class="row personal-contenedor" id="contenedor-banos" style="display: none;">
                  <div class="col-12">
                    @foreach ($banos as $key => $item)
                      @if (File::exists($public_path . 'colaboradores/' . $item->id . '.jpg'))
                        <img src="{{asset('colaboradores/' . $item->id . '.jpg')}}" class="w-100">
                      @endif
                    @endforeach
                  </div>
                </div>
              @endif

              @if (count($food) > 0)
                <a href="#" class="btn btn-dark d-block m-auto d-block fs-3 mt-1 mx-1 btn-temporada" data-tab="contenedor-foodcourt" id="personal-pane">
                  <i style="height: 1.6rem; width: 1.6rem;" data-feather="trash-2"></i> FOODCOURT 
                </a>

                <div class="row personal-contenedor" id="contenedor-foodcourt" style="display: none;">
                  <div class="col-12">
                    @foreach ($food as $key => $item)
                      @if (File::exists($public_path . 'colaboradores/' . $item->id . '.jpg'))
                        <img src="{{asset('colaboradores/' . $item->id . '.jpg')}}" class="w-100">
                      @endif
                    @endforeach
                  </div>
                </div>
              @endif

              <div class="mt-1"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    $(document).ready(function(){
      $('div#tab-pane').click(function(){
        var index = $(this).attr('data-tab');

        $('div.tab-contenedor').hide();
        $('div#tab-pane div').addClass('bg-dark').removeClass('bg-secondary');
        $('div', $(this)).removeClass('bg-dark').addClass('bg-secondary');
        $('#' + index).show();

        return false;
      });

      $('a#mesas-pane').click(function(){
        console.log($('#contenedor-mesas').is(":hidden"))
        if ($('#contenedor-mesas').is(":hidden")) {
          $('#contenedor-mesas').show();
        } else {
          $('#contenedor-mesas').hide();
        }
        return false;
      });

      $('a#personal-pane').click(function(){
        var index = $(this).attr('data-tab');

        $('div.personal-contenedor').hide();
        $('#' + index).slideDown();

        return false;
      });

      $('body').on('keyup', '#tarjeta_num', function(){
        var tarjeta = $.payment.cardType($(this).val());
        $('#tarjeta_nom').val(tarjeta)
        $(this).addClass(tarjeta)
      });

      $.fn.toggleInputError = function(erred) {
        this.parent().toggleClass('has-error', erred);
        return this;
      };
    });
                            
    function seleccionarMetodo(id_metodo){
      $('div#metodo_1, div#metodo_2').hide();
      $('div#metodo_' + id_metodo).show();
    }

    function realizarPago(id_invitado) {
      var ruta = "/admin/cargar_formulario_pago/" + id_invitado;
      $.ajax({
          type: "GET",
          url: ruta,
          dataType: "JSON",
          success: function(respuesta){
            Swal.fire({
              customClass: {
                confirmButton: 'btn btn-dark fs-1',
                cancelButton: 'btn btn-secondary fs-1'
              },
              reverseButtons: true,
              showCancelButton: true,
              confirmButtonText: respuesta.confirmButtonText,
              cancelButtonText: 'Cancelar',
              title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                        <h1 class="modal-title" id="verifyModalContent_title">` + respuesta.titulo + `</h1>
                    </div>`,
              html: respuesta.html,
              didOpen: () => {
                cargarInfoInvitado(id_invitado);

                $('#tarjeta_num').payment('formatCardNumber');
                $('#tarjeta_fv').payment('formatCardExpiry');
                $('#tarjeta_cvc').payment('formatCardCVC');
              },
              preConfirm: () => {
                if ($('input[name="metodo_pago"]:checked').val() == 3) {
                  var cardType = $.payment.cardType($('#tarjeta_num').val());
                  $('#tarjeta_num').toggleInputError(!$.payment.validateCardNumber($('#tarjeta_num').val()));
                  $('#tarjeta_fv').toggleInputError(!$.payment.validateCardExpiry($('#tarjeta_fv').payment('cardExpiryVal')));
                  $('#tarjeta_cvc').toggleInputError(!$.payment.validateCardCVC($('#tarjeta_cvc').val(), cardType));

                  if (!$.payment.validateCardNumber($('#tarjeta_num').val()) || !$.payment.validateCardExpiry($('#tarjeta_fv').payment('cardExpiryVal')) || !$.payment.validateCardCVC($('#tarjeta_cvc').val(), cardType)) {
                    Swal.showValidationMessage('Datos equivocados para tarjeta');
                  }
                } else {
                  if ($('#boleta-pago').val() == '') {
                    $('#boleta-pago').toggleInputError(true);
                    Swal.showValidationMessage('Datos equivocados para depósito');
                  }
                }
              }
            }).then(result => {
              if (result.isConfirmed) {

                if ($('#tarjeta_num').length >= 1) {
                  $.ajax({
                    type:     "POST",
                    url:      "/admin/emitir_pago",
                    data:     new FormData(document.querySelector("#tarjeta_form")),
                    processData: false,
                    contentType: false,
                    dataType: "json",

                    beforeSend: function( xhr ) {
                      Swal.fire({
                        icon: 'info',
                        title: 'PROCESANDO PAGO',
                      });
                    }
                  }).done(function (data) {
                    if (!data.denegado) {

                      for(i = 0; i <= (data.rubros_pagados.length - 1); i++) {
                        if (data.rubros_pagados[i].id == 'id_evento') {
                          $('#nombre_invitado_' + data.id_invitado).parent().parent().parent().addClass('bg-success').addClass('text-dark');

                          $('#pendiente_pagar_boleto', $('#nombre_invitado_' + data.id_invitado).parent().parent().parent()).remove();
                        } else if (data.rubros_pagados[i].id == 'id_mesa') {
                          var pull_pagado = parseInt($('#pull_pagado').html());
                          // $('#pull_pagado').html(parseInt(data.rubros_pagados[i].monto) + pull_pagado)

                          $('#pendiente_pull', $('#nombre_invitado_' + data.id_invitado).parent().parent().parent()).remove();
                          $('#nombre_invitado_' + data.id_invitado).parent().parent().parent().append('<span class="d-block text-center mb-1 bg-light text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="pendiente_boleta"> Verificando Transferencia </span>');
                        }
                        
                        // $('#completar_informacion', $('#nombre_invitado_' + data.id_invitado).parent().parent()).remove();
                        $('#realizar_pago', $('#nombre_invitado_' + data.id_invitado).parent().parent()).remove();
                      }

                      Swal.fire({
                        icon: 'success',
                        title: 'GRACIAS POR TU PAGO',
                        timer: 2000
                      });
                    } else {
                      Swal.fire({
                        icon: 'error',
                        title: 'ERROR: INTENTA DE NUEVO',
                        timer: 2000
                      });
                    }
                  }).fail( function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR)
                    Swal.fire({
                      icon: 'error',
                      title: 'ERROR: INTENTA DE NUEVO',
                      timer: 2000
                    });
                  });
                } else {
                  guardarInfoInvitado(id_invitado)
                  realizarPago(id_invitado)
                }
              }
            });
          }
      }).fail( function(jqXHR, textStatus, errorThrown) {
        Swal.fire({
          icon: 'error',
          title: 'ERROR: INTENTA DE NUEVO',
          timer: 2000
        });
      });
    }

    function cargarInfoInvitado(id_invitado) {
      var ruta = "/admin/informacion_invitado/" + id_invitado;
      $.ajax({
          type: "GET",
          url: ruta,
          dataType: "JSON",
          success: function(respuesta){
            $('#nombre').val(respuesta.invitado.nombre)
            $('#telefono').val(respuesta.invitado.telefono)
            $('#correo').val(respuesta.invitado.correo)
            $('#fecha_nacimiento').val(respuesta.invitado.fecha_nacimiento)
          }
      }).fail( function(jqXHR, textStatus, errorThrown) {
        Swal.fire({
          icon: 'error',
          title: 'ERROR: INTENTA DE NUEVO',
          timer: 2000
        });
      });
    }

    function guardarInfoInvitado(id_invitado){
      var nombre = $('#nombre').val() ? $('#nombre').val() : 0;
      var telefono = $('#telefono').val() ? $('#telefono').val() : 0;
      var correo = $('#correo').val() ? $('#correo').val() : 0;
      var fecha = $('#fecha_nacimiento').val() ? $('#fecha_nacimiento').val() : 0;
      var ruta     = "/admin/invitado_perfil_actualizado/" + id_invitado + "/" + encodeURIComponent(nombre) + "/" + encodeURIComponent(correo) + "/" + encodeURIComponent(telefono) + "/" + encodeURIComponent(fecha);
      $.ajax({
          type: "GET",
          url: ruta,
          dataType: "JSON",
          success: function(respuesta){
            console.log(respuesta)
            $('#nombre_invitado_' + respuesta.id).html(respuesta.nombre)

            if (!respuesta.perfil_completo) {
              realizarPago(id_invitado)
            }
          }
      }).fail( function(jqXHR, textStatus, errorThrown) {
        Swal.fire({
          icon: 'error',
          title: 'ERROR: INTENTA DE NUEVO',
          timer: 2000
        });
      });
    }

    function modificarPerfil(id_invitado) {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-dark fs-1',
          cancelButton: 'btn btn-secondary fs-1'
        },
        reverseButtons: true,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h1 class="modal-title" id="verifyModalContent_title">EDITAR MI INFORMACIÓN</h1>
              </div>`,
        html:`
          <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
            <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
              <div class="modal-body">
                <div class="row">
                  <div class="col-12">
                    <label class="fw-bold"> Nombre </label>
                    <input class="form-control w-100 text-center fs-3" id="nombre" name="nombre" type="text" onClick="this.select();" autocomplete="off" />
                  </div>
                  <div class="col-12 pt-1">
                    <label class="fw-bold"> Télefono </label>
                    <input class="form-control w-100 text-center fs-3" id="telefono" name="telefono" type="text" onClick="this.select();" autocomplete="off" />
                  </div>
                  <div class="col-12 pt-1">
                    <label class="fw-bold"> Correo </label>
                    <input class="form-control w-100 text-center fs-3" id="correo" name="correo" type="text" onClick="this.select();" autocomplete="off" />
                  </div>
                  <div class="col-12 pt-1">
                    <label class="fw-bold"> Cumpleaños </label>
                    <input class="form-control w-100 text-center fs-3" id="fecha_nacimiento" name="fecha_nacimiento" type="date" />
                  </div>
                </div>
              </div>
            </div>
          </div>`,
        didOpen: () => {
          var ruta = "/admin/informacion_invitado/" + id_invitado;
          console.log(ruta)
          $.ajax({
              type: "GET",
              url: ruta,
              dataType: "JSON",
              success: function(respuesta){
                $('#nombre').val(respuesta.invitado.nombre)
                $('#telefono').val(respuesta.invitado.telefono)
                $('#correo').val(respuesta.invitado.correo)
                $('#fecha_nacimiento').val(respuesta.invitado.fecha_nacimiento)
              }
          }).fail( function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
              icon: 'error',
              title: 'ERROR: INTENTA DE NUEVO',
              timer: 2000
            });
          });
        },
      }).then(result => {
        if (result.isConfirmed) {
          var nombre = $('#nombre').val() ? $('#nombre').val() : 0;
          var telefono = $('#telefono').val() ? $('#telefono').val() : 0;
          var correo = $('#correo').val() ? $('#correo').val() : 0;
          var fecha = $('#fecha_nacimiento').val() ? $('#fecha_nacimiento').val() : 0;
          var ruta     = "/admin/invitado_perfil_actualizado/" + id_invitado + "/" + encodeURIComponent(nombre) + "/" + encodeURIComponent(correo) + "/" + encodeURIComponent(telefono) + "/" + encodeURIComponent(fecha);
          $.ajax({
              type: "GET",
              url: ruta,
              dataType: "JSON",
              success: function(respuesta){
                if (!respuesta.duplicado) {
                  // $('#modificar_perfil', $('#nombre_invitado_' + respuesta.id).parent().parent()).remove();
                  $('#nombre_invitado_' + respuesta.id).html(respuesta.nombre)
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: 'El nombre del invitado ya existe, ingresa otro nombre o agrega un apellido extra',
                    timer: 5000
                  }).then(result => {
                    modificarPerfil(id_invitado)
                  });
                }
              }
          }).fail( function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
              icon: 'error',
              title: 'ERROR: INTENTA DE NUEVO',
              timer: 2000
            });
          });
        }
      });
    }
  </script>

  <style>
      .menu-toggle { display: none; }
      .navbar-container { display: none !important; }
      .header-navbar .row { width: 100% !important; }
      .header-navbar, .header-navbar-shadow { display: none !important; }
      .app-content.content { padding-top: 20px !important; }
      .vertical-layout.vertical-menu-modern.menu-collapsed .navbar.floating-nav { left: 0 !important }
      @if (File::exists($public_path . 'fondo/' . $evento->id . '.jpg'))
        @php $background_url = asset('fondo/' . ($evento->id) . '.jpg') @endphp
        body { background: black url('{{$background_url}}') repeat top center; }
      @else
        @php $background_url = asset('fondo/0.jpg?' . date('YmdHis')) @endphp
        body { background: black url('{{$background_url}}') repeat top center; }
      @endif

      @php $background_url = asset('img_admin/btn-jbl.jpg') @endphp
      .btn-jbl {
        border: none !important;
        background-image: url('{{$background_url}}') !important;
        color: #fff !important;
        padding-left: 35% !important;
        background-position: center;
        background-size: contain;
      }

      .btn-temporada { background-image: linear-gradient(to right, #c60000 , #740000); border-color: #c60000 !important; }

      .modal-dialog .modal-content { background: transparent !important; }
      .gtc-container .swal2-popup { background-size: cover !important; }
      
      .vertical-layout.vertical-menu-modern.menu-collapsed .app-content, .vertical-layout.vertical-menu-modern.menu-collapsed .footer { margin-left: 0 !important; }
  </style>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
