{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
@php $public_path = substr(getcwd(), 0, strrpos(getcwd(), '/')) . '/archivos/'; @endphp
@php $public_path = (strpos(getcwd(), 'themanorgt') ? getcwd() :  (substr(getcwd(), 0, strrpos(getcwd(), '/')) . '/public')) . '/'; @endphp

@php $por_mujeres_invitados = @$total_invitados ? round((1 - ((@$total_invitados - @$total_mujeres) / @$total_invitados)) * 100) : 0; @endphp
@php $por_hombres_invitados = @$total_invitados ? round((1 - ((@$total_invitados - @$total_hombres) / @$total_invitados)) * 100) : 0; @endphp

@php $por_mujeres_pagados = @$total_pagados ? round((1 - ((@$total_pagados - @$total_mujeres_pagado) / @$total_pagados)) * 100) : 0; @endphp
@php $por_hombres_pagados = @$total_pagados ? round((1 - ((@$total_pagados - @$total_hombres_pagado) / @$total_pagados)) * 100) : 0; @endphp

@php $por_mujeres_no_pagado = @$total_no_pagados ? round((1 - ((@$total_no_pagados - @$total_mujeres_no_pagado) / @$total_no_pagados)) * 100) : 0; @endphp
@php $por_hombres_no_pagado = @$total_no_pagados ? round((1 - ((@$total_no_pagados - @$total_hombres_no_pagado) / @$total_no_pagados)) * 100) : 0; @endphp

@php $por_pagados_vs_invitados = @$total_invitados ? round((1 - ((@$total_invitados - @$total_pagados) / @$total_invitados)) * 100) : 0; @endphp

@php $por_pagados_vs_invitados = @$total_invitados ? round((1 - ((@$total_invitados - @$total_pagados) / @$total_invitados)) * 100) : 0; @endphp

  <div class="row">
    <div class="col-12 col-sm-6 col-md-6 col-lg-5 mx-auto">
      <div class="card mb-1">

        @if (File::exists($public_path . 'covers/' . substr($evento->fecha, 5, 5) . '.mp4'))
          <video id="video-cover" width="100%" height="auto" loop="loop" autoplay controls>
            <source src="{{asset('covers/' . substr($evento->fecha, 5, 5) . '.mp4')}}" type="video/mp4">
            Your browser does not support the video tag.
          </video>
        @elseif (File::exists($public_path . 'covers/' . substr($evento->fecha, 5, 5) . '.jpg'))
          <img src="{{asset('covers/' . substr($evento->fecha, 5, 5) . '.jpg')}}" class="w-100">
        @endif

        <div class="row p-1 pb-1">
          <!--
          <div class="col-12 px-1">
            <a href="#" id="copiar_texto" class="btn btn-danger d-block m-auto d-block fs-3 text-start px-1" style="border: 5px solid #ea5455 !important;">
              <i style="height: 1.6rem; width: 1.6rem;" data-feather="alert-triangle"></i> 
              No olvides copiar y pasar este link a tus invitados
            </a>
          </div>
          -->

          <div class="col-12 px-1">
            @php $ruta = str_replace('https', 'http', route('informacion_para_evento', ['id_mesa' => @$mesa->id])) @endphp
            <a href="#" id="copiar_texto" onclick="copiarTexto('Revise la información del evento: (Mesa, jefe de area, coordinador y mesero), botón de *INFO* para menú, etc. \r\n\r\Hacer click para verlo y poderlo compartir con su grupo de amistades: {{$ruta}}', 'copiar_texto')" class="btn btn-dark d-block px-1 fs-5" style="background: #fde54d !important; border-color: #fde54d !important; color: #000 !important;">
              <i style="height: 1.6rem; width: 1.6rem;" data-feather="share-2"></i> 
              Haz click para copiar el link y compartir con el grupo de amistades la información del evento: (Mesa, jefe de area, coordinador y mesero), botón de <b>INFO</b> para menú, etc. 
            </a>
          </div>

          <div class="col-12 px-1" id="texto_copiado_alerta" style="display: none;">
            <a href="#" class="btn btn-dark d-block px-1 d-block fs-5 mt-1" style="background: #fde54d !important; border-color: #fde54d !important; color: #000 !important;">
              <i style="height: 1.2rem; width: 1.2rem;" data-feather="info"></i> El link se copió, ahora puedes compartirlo. </a>
          </div>

          @if (!$mesa->pull && !$mesa->id_pull)
            <div class="col-12 px-1">
              <a href="#" onclick="pagarPull(true);" class="btn btn-dark d-block px-1 d-block fs-3 mt-1 btn-temporada" style="border: 5px solid #c60000 !important;">
                <i style="height: 1.6rem; width: 1.6rem;" data-feather="plus"></i> ¿Deseas pagar pull? 
              </a>
            </div>
          @endif

          <div class="col-12 px-1">
            <a href="{{route('admin.reservas.lista_eventos')}}" class="btn btn-dark d-block px-1 d-block fs-3 mt-1 btn-temporada" style="border: 5px solid #c60000 !important;"><i style="height: 1.6rem; width: 1.6rem;" data-feather="calendar"></i> Click para ver todos los eventos </a>
          </div>

          @if (@count($mesas_asignadas) >= 1)
            <div class="col-12 px-1 mb-0">
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
                      <img src="{{asset('mesas/' . $item->id_venue . '/' . $item->nombre . $item->no_mesa . '.' . ($evento->id == 11 ? 'gif' : 'jpg'))}}" class="w-100 mb-1">
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
                    <h1 class="my-1">Tu{{($mesa->id_mesero && $mesa->id_mesero_2) ? 's' : ''}} mesero{{($mesa->id_cobrador_1 && $mesa->id_mesero_2) ? 's' : ''}}</h1>
                    <img src="{{asset('colaboradores/' . $mesa->id_cobrador_1 . '.jpg')}}" class="w-100">

                    @if (File::exists($public_path . 'colaboradores/' . $mesa->id_mesero_2 . '.jpg'))
                      <img src="{{asset('colaboradores/' . $mesa->id_cobrador_2 . '.jpg')}}" class="w-100">
                    @endif
                  @endif
                </div>
              </div>
            </div>
          @endif

          @if ($evento->id == 20)
            <div class="col-12 px-1">
              <a href="http://www.jblgt.shop" target="_blank" class="btn btn-jbl d-block fs-3 mt-1">
                <br><br>
              </a>
            </div>
          @endif
        </div>

        <div class="row">
          <div class="col-4 pe-0 border-w cursor-pointer" data-tab="contenedor-lista" id="tab-pane">
            <div class="bg-secondary p-1 border-white text-light text-center"> MI LISTA </div>
          </div>
          
          <div class="col-4 ps-0 pe-0 cursor-pointer" data-tab="contenedor-info" id="tab-pane">
            <div class="bg-dark p-1 border-white text-light text-center"> INFO </div>
          </div>
          
          <div class="col-4 ps-0 cursor-pointer" data-tab="contenedor-dashboard" id="tab-pane">
            <div class="bg-dark p-1 border-white text-light text-center"> STATS </div>
          </div>

          <div class="col-6 pe-0 cursor-pointer" data-tab="contenedor-dj" id="tab-pane">
            <div class="bg-dark p-1 border-white text-light text-center"> DJ's </div>
          </div>

          <div class="col-6 ps-0 cursor-pointer" data-tab="contenedor-sataff" id="tab-pane">
            <div class="bg-dark p-1 border-white text-light text-center"> STAFF </div>
          </div>
        </div>

        <div class="card-body pt-0 tab-contenedor" id="contenedor-lista">
          <div class="row">
            <div class="col-12">
              <div class="row mt-1">
                <div class="col-12 text-center">
                  <label class="pb-1 fs-1 fw-bold"> Tu lista de {{$mesa->id_area == 1 ? 'Mesa' : 'Barra'}} </label>
                </div>
                
                @if ($mesa->pull && $mesa->id_pull)
                  <div class="col-12 text-center mb-1">
                    <label class="fs-1 fw-bold d-block pb-0"> PULL DE LA MESA </label>
                    <label class="fs-5 d-block fw-bold text-center">{{$mesa->pull}}</label>
                    <label class="fs-5 d-block">
                      <b>Total</b>: Q. <span id="pull_total">{{(($total_mujeres ?: 0) * $pull->monto_mujeres) + (($total_hombres ?: 0) * $pull->monto_hombres)}}</span>
                    </label>
                    <label class="fs-5 d-block">
                      <b>Pagado</b>: Q. <span id="pull_pagado">{{(($pull_pagado_mujeres ?: 0) * $pull->monto_mujeres) + (($pull_pagado_hombres ?: 0) * $pull->monto_hombres)}}</span>
                    </label>
                  </div>
                @endif

                <div class="col-6 text-center">
                  <label class="pb-1 fs-1 fw-bold"> Mujeres </label>
                </div>
                <div class="col-6 text-center">
                  <label class="pb-1 fs-1 fw-bold"> Hombres </label>
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

                  @if (@isset($data_m[$i.'-0']))
                    @if (!@$item_m->telefono || !@$item_m->correo || !@$item_m->fecha_nacimiento)
                      <div class="col-6 border celda_{{$i}}_0 {{$bg_invitado}}" data-pagado="{{@$item_m->pagado}}" data-repetido="{{@$item->repetido}}">
                    @else
                      <div class="col-6 border celda_{{$i}}_0 {{$bg_invitado}}" data-pagado="{{@$item_m->pagado}}" data-repetido="{{@$item->repetido}}" style="background: url('/img_admin/fuegos.gif') top center; background-size: cover;">
                    @endif
                      @if (@$item_m->id)
                        <div class="row">
                          <div class="col-2">
                            <label class="py-1 fs-5">{{$i}}</label>
                          </div>
                          <div class="col-{{@$item_m->pagado ? '9 text-' . (@$item_m->repetido ? 'primary' : 'dark') . ' fw-bold' : (@$item_m->repetido ? '9' : '7')}} text-center">
                            <label class="py-1 fs-5 {{$item_m->repetido ? 'pb-0' : ''}}" id="nombre_invitado_{{$item_m->id}}"> {!! html_entity_decode($item_m->nombre) !!} </label>
                          </div>

                          @if (!@$item_m->pagado && !@$item_m->repetido)
                            <div class="col-2">
                              @if (!@$item_m->telefono || !@$item_m->correo || !@$item_m->fecha_nacimiento)
                                <a href="#" onclick="editarInvitado({{$item_m->id}}, {{$mesa->id}}, 0, {{$i}})" class="d-block text-center pt-1 text-{{@$item_m->repetido ? 'light' : 'dark'}} fw-bold" title="Editar">
                                  <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit"></i> 
                                </a>
                              @endif
                              <a href="#" onclick="borrarInvitado({{$item_m->id}}, {{$item_m->fila}}, {{$item_m->sexo}})" class="d-block text-center py-1 text-{{@$item_m->repetido ? 'light' : 'dark'}} fw-bold" title="Borrar">
                                <i style="height: 1.8rem; width: 1.8rem;" data-feather="trash"></i> 
                              </a>
                            </div>
                          @endif

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
                              <span class="d-block text-center mb-1 bg-light text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="completar_informacion">
                                Ya ingresó información
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
                                <span class="d-block text-center mb-1 bg-success text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="pago_pull_realizado">
                                  Pull pagado
                                </span>
                              @endif
                            @endif

                            @if (@$item_m->repetido)
                              <span class="d-block text-center mb-1 bg-light text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="alerta_duplicado">
                                Invitado duplicado
                              </span>
                            @endif
                          </div>
                            
                          @if (@$item_m->repetido)
                            <div class="col-12 text-center">
                              <label class="mb-1 fs-5" style="font-weight: bold; "> 
                                Ya está en otra lista, ¿Se queda en tu lista?  
                                <br>
                                <a href="#" class="text-dark fs-1" style="text-decoration: underline;" onclick="mantenerInvitado({{$item_m->id}});">
                                  Si
                                </a>

                                <a href="#" class="text-dark ms-2 fs-1" style="text-decoration: underline;" onclick="borrarInvitado({{$item_m->id}}, {{$item_m->fila}}, {{$item_m->sexo}});">
                                  No
                                </a>
                              </label>
                            </div>
                          @endif
                        </div>
                      @else
                        <div class="row">
                          <div class="col-2">
                            <label class="py-1 fs-5">{{$i}}</label>
                          </div>
                          <div class="col-10">
                            @if (!$mesa->listas_cerradas)
                              <a href="#" onclick="editarInvitado({{0}}, {{$mesa->id}}, 0, {{$i}})" class="d-block text-center py-1 text-dark fw-bold" title="Agregar invitado">
                                <i style="height: 1.8rem; width: 1.8rem;" data-feather="plus"></i> 
                              </a>
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

                  @if (@isset($data_h[$i.'-1']))
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
                          <div class="col-{{@$item_h->pagado ? '9 text-' . (@$item_h->repetido ? 'primary' : 'dark') . ' fw-bold' : (@$item_h->repetido ? '9' : '7')}} text-center">
                            <label class="py-1 fs-5 {{$item_h->repetido ? 'pb-0' : ''}}" id="nombre_invitado_{{$item_h->id}}"> {!! html_entity_decode($item_h->nombre) !!} </label>
                          </div>

                          @if (!@$item_h->pagado && !@$item_h->repetido)
                            <div class="col-2">
                              @if (!@$item_h->telefono || !@$item_h->correo || !@$item_h->fecha_nacimiento)
                                <a href="#" onclick="editarInvitado({{$item_h->id}}, {{$mesa->id}}, 1, {{$i}})" class="d-block text-center pt-1 text-{{@$item_h->repetido ? 'light' : 'dark'}} fw-bold" title="Editar">
                                  <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit"></i> 
                                </a>
                              @endif

                              <a href="#" onclick="borrarInvitado({{$item_h->id}}, {{$item_h->fila}}, {{$item_h->sexo}})" class="d-block text-center py-1 text-{{@$item_h->repetido ? 'light' : 'dark'}} fw-bold" title="Borrar">
                                <i style="height: 1.8rem; width: 1.8rem;" data-feather="trash"></i> 
                              </a>
                            </div>
                          @endif

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
                              <span class="d-block text-center mb-1 bg-light text-dark fw-bold text-center rounded" style="padding: 5px !important; font-size: 12px !important;" id="completar_informacion">
                                Ya ingresó información
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
                                <span class="d-block text-center mb-1 bg-success text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="pago_pull_realizado">
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
                            
                          @if (@$item_h->repetido)
                            <div class="col-12 text-center">
                              <label class="mb-1 fs-5" style="font-weight: bold; "> 
                                Ya está en otra lista, ¿Se queda en tu lista?  
                                <br>
                                <a href="#" class="text-dark fs-1" style="text-decoration: underline;" onclick="mantenerInvitado({{$item_h->id}});">
                                  Si
                                </a>

                                <a href="#" class="text-dark ms-2 fs-1" style="text-decoration: underline;" onclick="borrarInvitado({{$item_h->id}}, {{$item_h->fila}}, {{$item_h->sexo}});">
                                  No
                                </a>
                              </label>
                            </div>
                          @endif
                        </div>
                      @else
                        <div class="row">
                          <div class="col-2">
                            <label class="py-1 fs-5">{{$i}}</label>
                          </div>
                          <div class="col-10">
                            @if (!$mesa->listas_cerradas)
                              <a href="#" onclick="editarInvitado({{0}}, {{$mesa->id}}, 1, {{$i}})" class="d-block text-center py-1 text-dark fw-bold" title="Agregar invitado">
                                <i style="height: 1.8rem; width: 1.8rem;" data-feather="plus"></i> 
                              </a>
                            @endif
                          </div>
                        </div>
                      @endif
                    </div>
                  @else
                    <div class="col-6 border"></div>
                  @endif
                @endfor

                <div class="col-12">
                  @if (!$mesa->abierta)
                    <a href="#" id="lista_0" onclick="cerrarLista({{$mesa->id}}, 0)" class="btn btn-dark text-center py-1 mt-1 w-100 fs-1" title="Cerrar lista">
                      <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit-2"></i> 
                      CERRAR LISTA
                    </a>
                  @else
                    <a href="#" id="lista_1" onclick="cerrarLista({{$mesa->id}}, 1)" class="btn btn-dark text-center py-1 mt-1 w-100 fs-1" title="Cerrar lista">
                      <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit-2"></i> 
                      ABRIR LISTA
                    </a>
                  @endif

                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card-body p-0 tab-contenedor" id="contenedor-dashboard" style="display: none;">
          <div class="row mt-1">
            <div class="col-12">

              <div id="grafica-invitados" class="mb-2 contenedor-graficas" style="height: 400px;"></div>
              @if ($evento->pagado)
                <div id="grafica-pagados" class="mb-2 contenedor-graficas" style="height: 400px;"></div>
                <div id="grafica-pendientes" class="contenedor-graficas" style="height: 400px;"></div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-body p-0 tab-contenedor" id="contenedor-dj" style="display: none;">
          <div class="row mt-1">
            @for ($i = 1; $i <= 5; $i++)
              <div class="col-12">
                @if (File::exists($public_path . 'dj/' . $i . '_' . $evento->id . '.jpg'))
                  <img src="{{asset('dj/' . $i . '_' . $evento->id . '.jpg')}}" class="w-100">
                @else
                  @if (File::exists($public_path . 'dj/' . $i . '_0.jpg'))
                    <img src="{{asset('dj/' . $i . '_0.jpg')}}" class="w-100">
                  @endif
                @endif
              </div>
            @endfor
          </div>
        </div>

        <!--
        <div class="card-body p-0 tab-contenedor" id="contenedor-sponsors" style="display: none;">
          <div class="row mt-1">
            @for ($i = 1; $i <= 10; $i++)
              <div class="col-12">
                @if (File::exists($public_path . 'patrocinadores/' . $i . '_' . $evento->id . '.jpg'))
                  <img src="{{asset('patrocinadores/' . $i . '_' . $evento->id . '.jpg')}}" class="w-100">
                @else
                  @if (File::exists($public_path . 'dj/' . $i . '_0.jpg'))
                    <img src="{{asset('patrocinadores/' . $i . '_0.jpg')}}" class="w-100">
                  @endif
                @endif
              </div>
            @endfor
          </div>
        </div>
          -->

        <div class="card-body p-0 tab-contenedor" id="contenedor-info" style="display: none;">
          <div class="row mt-1">
            <div class="col-12 text-center">
              <div class="col-12">
                @if (File::exists($public_path . 'boleta/' . $evento->id . '.jpg'))
                  <img src="{{asset('boleta/' . $evento->id . '.jpg')}}" class="w-100">
                @endif
              </div>

              <div class="col-12">
                @if (File::exists($public_path . 'ingreso/' . $evento->id . '.jpg'))
                  <img src="{{asset('ingreso/' . $evento->id . '.jpg')}}" class="w-100">
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
                  <img src="{{asset('eventos/iso_' . $evento->id . '.jpg')}}" class="w-100">
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
                    <img src="{{asset('mapas/' . $i . '_' . $evento->id . '.jpg')}}" class="w-100">
                  @else
                      @if ($evento->id_venue != 2)
                        @if (File::exists($public_path . 'mapas/' . $i . '_' . '_0.jpg'))
                          <img src="{{asset('mapas/' . $i . '_0.jpg')}}" class="w-100">
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
                        <img src="{{asset('menu/' . $i . '_0.jpg?')}}" class="w-100">
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
                  <i style="height: 1.6rem; width: 1.6rem;" data-feather="users"></i> MESEROS 
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
    function realizarAccion(formulario){
      $('#' + formulario).attr('onsubmit', '').submit();
    }

    function cambioDeFecha(fecha) {
      if (!isNaN(Number(new Date(fecha)))) {
        window.location = "/admin/eventos/" + encodeURIComponent(fecha)
      }
    }

    function agregarInvitado(id_mesa, sexo, fila, accion, id) {
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
                  <h1 class="modal-title" id="verifyModalContent_title">AGREGAR INVITADO</h1>
              </div>`,
        html:`
          <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
            <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
              <div class="modal-body">
                <div class="row">
                  <div class="col-12">
                    <label class="fw-bold"> Nombre </label>
                    <input class="form-control w-100 text-center fs-5" id="nombre" name="nombre" type="text" onClick="this.select();" autocomplete="off" />
                  </div>
                </div>
              </div>
            </div>
          </div>`
      }).then(result => {
        if (result.isConfirmed) {
          var nombre = $('#nombre').val();
          var ruta   = "/admin/agregar_invitado/" + id_mesa + "/" + encodeURIComponent(nombre) + "/" + sexo + "/" + fila + '/' + accion + '/' + id;
          console.log(ruta)
          $.ajax({
              type: "GET",
              url: ruta,
              dataType: "JSON",
              success: function(respuesta){
                if (!respuesta.duplicado) {
                  html = `<div class="row">
                            <div class="col-2">
                              <label class="py-1 fs-5">` + fila + `</label>
                            </div>
                            <div class="col-7 text-center">
                              <label class="py-1 fs-5 " id="nombre_invitado_` + respuesta.id + `"> ` + respuesta.nombre + ` </label>
                            </div>

                            <div class="col-2">
                              <a href="#" onclick="editarInvitado(` + respuesta.id + `, ` + respuesta.id_mesa + `, ` + respuesta.sexo + `, ` + respuesta.fila + `)" class="d-block text-center pt-1 text-dark fw-bold" title="Editar">
                                <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit"></i> 
                              </a>
                                
                              <a href="#" onclick="borrarInvitado(` + respuesta.id + `, ` + respuesta.fila + `, ` + respuesta.sexo + `)" class="d-block text-center py-1 text-dark fw-bold" title="Borrar">
                                <i style="height: 1.8rem; width: 1.8rem;" data-feather="trash"></i> 
                              </a>
                            </div>
                            
                            <div class="col-12 text-center">
                              <span class="d-block text-center mb-1 bg-light text-dark fw-bold" style="padding: 5px !important; font-size: 12px !important;" id="completar_informacion">
                                  No ha ingresado información
                              </span>
                            </div>    
                          </div>`;
                  $('.celda_' + fila + '_' + sexo).html(html);
                  feather.replace();

                  var total_pagados = parseInt($('#total_invitados').html());
                  $('#total_invitados').html(total_pagados + 1)
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: respuesta.idd_mesa == 0 ? 'El nombre del invitado ya existe, ingresa otro nombre o agrega un apellido extra' : respuesta.nombre + ' ya se encuentra en la reservación de ' + respuesta.idd_nombre,
                    timer: 5000
                  }).then(result => {
                    agregarInvitado(id_mesa, sexo, fila, 0, id)
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

    function editarInvitado(id, id_mesa, sexo, fila) {
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
                  <h1 class="modal-title" id="verifyModalContent_title">` + (id == 0 ? 'AGREGAR INVITADO' : 'EDITAR INVITADO') + `</h1>
              </div>`,
        html:`
          <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
            <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
              <div class="modal-body">
                <div class="row">
                  <div class="col-12">
                    <label class="fw-bold"> Nombre </label>
                    <select name="id_invitado" id="id_invitado" class="form-control select2 w-100 fs-4 text-center" >
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>`,
        didOpen: () => {
          $('select.select2').select2({
            placeholder: 'Cambio de invitado por:',
            ajax: {
              url: '/admin/cargar_invitados/' + id + '/' + id_mesa,
              dataType: 'json',
              delay: 250,
              processResults: function (data) {
                return {
                  results:  $.map(data.data, function (item) {
                    return {
                      text: item.nombre + (item.telefono != null ?  (' / ' + item.telefono) : ''),
                      id: item.id
                    }
                  })
                };
              },
              cache: true
            }
          }).on("select2:select", function(e) { 
             if ($(this).val() == '+') {
              agregarInvitado(id_mesa, sexo, fila, 0, id)
             }
          });
        },
      }).then(result => {
        if (result.isConfirmed) {
          var id_invitado = $('#id_invitado').val();
          var ruta     = "/admin/cambio_invitado/" + id + "/" + id_invitado + "/" + id_mesa;
          $.ajax({
              type: "GET",
              url: ruta,
              dataType: "JSON",
              success: function(respuesta){
                console.log(respuesta)
                if (respuesta.id == 0) {
                  var invitado = respuesta.invitado;
                  html = `<div class="row">
                            <div class="col-2">
                              <label class="py-1 fs-5">` + fila + `</label>
                            </div>
                            <div class="col-7 text-center">
                              <label class="py-1 fs-5 " id="nombre_invitado_` + invitado.id + `"> ` + invitado.nombre + ` </label>
                            </div>

                            <div class="col-2">
                              <a href="#" onclick="editarInvitado(` + invitado.id + `, ` + invitado.id_mesa + `, ` + id_invitado.sexo + `, ` + id_invitado.fila + `)" class="d-block text-center pt-1 text-dark fw-bold" title="Editar">
                                <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit"></i> 
                              </a>
                                
                              <a href="#" onclick="borrarInvitado(` + id_invitado.id + `, ` + id_invitado.fila + `, ` + id_invitado.sexo + `)" class="d-block text-center py-1 text-dark fw-bold" title="Borrar">
                                <i style="height: 1.8rem; width: 1.8rem;" data-feather="trash"></i> 
                              </a>
                            </div>  
                          </div>`;
                  console.log('.celda_' + fila + '_' + sexo)
                  $('.celda_' + fila + '_' + sexo).html(html);
                  feather.replace();

                  var total_pagados = parseInt($('#total_invitados').html());
                  $('#total_invitados').html(total_pagados + 1)
                } else {
                  $('#nombre_invitado_' + respuesta.id).html(respuesta.invitado.nombre)
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

    function borrarInvitado(id, fila, sexo) {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-dark fs-1',
          cancelButton: 'btn btn-secondary fs-1'
        },
        reverseButtons: true,
        showCancelButton: true,
        confirmButtonText: 'SI',
        cancelButtonText: 'NO',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h12 class="modal-title" id="verifyModalContent_title">¿DESEAS BORRAR EL INVITADO?</h2>
              </div>`,
      }).then(result => {
        if (result.isConfirmed) {
          var ruta = "/admin/borrar_invitado_de_mesa/" + id;
          $.ajax({
              type: "GET",
              url: ruta,
              dataType: "JSON",
              success: function(respuesta){
                var html = `
                  <a href="#" onclick="agregarInvitado(` + respuesta.id + `, ` + sexo + `, ` + fila + `)" class="d-block text-center py-1 text-dark fw-bold" title="Compartir link">
                    <i style="height: 1.8rem; width: 1.8rem;" data-feather="plus"></i> 
                  </a>`;

                  $('.celda_' + fila + '_' + sexo).html(html).removeClass('bg-warning').removeClass('bg-success');
                  feather.replace();

                  var total_pagados = parseInt($('#total_invitados').html());
                  $('#total_invitados').html(total_pagados - 1)
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

    function mantenerInvitado(id) {
      var ruta = "/admin/mantenerInvitado/" + id;
      $.ajax({
          type: "GET",
          url: ruta,
          dataType: "JSON",
          success: function(respuesta){
            var html = `<div class="row">`;
            if (respuesta.sexo == 1) {
              html += `
              <div class="col-9 text-center">
                <label class="py-1 fs-5 text-dark" id="nombre_invitado_` + respuesta.id + `"> ` + respuesta.nombre + ` </label>
              </div>
              <div class="col-3">
                <a href="#" onclick="editarInvitado(` + respuesta.id + `)" class="d-block text-center pt-1 text-dark fw-bold" title="Editar">
                  <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit"></i> 
                </a>
                <a href="#" onclick="borrarInvitado(` + respuesta.id + `,` + respuesta.fila + `,` + respuesta.sexo + `)" class="d-block text-center py-1 text-dark fw-bold" title="Borrar">
                  <i style="height: 1.8rem; width: 1.8rem;" data-feather="trash"></i> 
                </a>
              </div>`;
            } else {
              html += `
              <div class="col-3">
                <a href="javascript:editarInvitado(` + respuesta.id + `)" class="d-block text-center pt-1 text-dark fw-bold" title="Editar">
                  <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit"></i> 
                </a>
                <a href="javascript:borrarInvitado(` + respuesta.id + `,` + respuesta.fila + `,` + respuesta.sexo + `)" class="d-block text-center py-1 text-dark fw-bold" title="Borrar">
                  <i style="height: 1.8rem; width: 1.8rem;" data-feather="trash"></i> 
                </a>
              </div>
              <div class="col-9 text-center">
                <label class="py-1 fs-5 text-dark" id="nombre_invitado_` + respuesta.id + `"> ` + respuesta.nombre + ` </label>
              </div>`;
            }
            html += `</div>`;

            if (respuesta.pagado) {
              $('.celda_' + respuesta.fila + '_' + respuesta.sexo).html(html).removeClass('bg-warning').removeClass('bg-success').addClass('bg-success');
            } else if (respuesta.repetido) {
              $('.celda_' + respuesta.fila + '_' + respuesta.sexo).html(html).removeClass('bg-warning').removeClass('bg-success').addClass('bg-warning');
            } else {
              $('.celda_' + respuesta.fila + '_' + respuesta.sexo).html(html).removeClass('bg-warning').removeClass('bg-success');
            }
            feather.replace();

            var total_pagados = parseInt($('#total_invitados').html());
            $('#total_invitados').html(total_pagados - 1)
          }
      }).fail( function(jqXHR, textStatus, errorThrown) {
        Swal.fire({
          icon: 'error',
          title: 'ERROR: INTENTA DE NUEVO',
          timer: 2000
        });
      });
    }

    function modificarPerfil(popTitle = 'EDITAR PERFIL') {
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
                  <h1 class="modal-title" id="verifyModalContent_title">` + popTitle + `</h1>
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
                  <input type="hidden" id="id_lider" name="id_lider" value="" />
                </div>
              </div>
            </div>
          </div>`,
        didOpen: () => {
          var ruta = "/admin/lider_info/";
          $.ajax({
              type: "GET",
              url: ruta,
              dataType: "JSON",
              success: function(respuesta){
                $('#nombre').val(respuesta.nombre)
                $('#telefono').val(respuesta.telefono)
                $('#correo').val(respuesta.correo)
                $('#fecha_nacimiento').val(respuesta.fecha_nacimiento)
                $('#id_lider').val(respuesta.id_lider)
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
          var id_lider = $('#id_lider').val() ? $('#id_lider').val() : 0;
          var fecha = $('#fecha_nacimiento').val() ? $('#fecha_nacimiento').val() : 0;
          var ruta     = "/admin/lider_actualizado/" + id_lider + "/" + encodeURIComponent(nombre) + "/" + encodeURIComponent(correo) + "/" + encodeURIComponent(telefono) + "/" + encodeURIComponent(fecha);
          $.ajax({
              type: "GET",
              url: ruta,
              dataType: "JSON",
              success: function(respuesta){
                $('#nombre_lider').html(respuesta.nombre.toUpperCase())
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

    function copiarTexto(texto, elementl) {
      $('#' + elementl).attr('disabled')

      var sampleTextarea = document.createElement("textarea");
      document.body.appendChild(sampleTextarea);
      sampleTextarea.value = texto;
      sampleTextarea.select();
      document.execCommand("copy");
      document.body.removeChild(sampleTextarea);

      $('#texto_copiado_alerta').slideDown(function(){
        setTimeout(function(){
          $('#texto_copiado_alerta').slideUp();
        }, 3000);
      });
    }

    function cerrarLista(id_mesa, accion) {
      var ruta = "/admin/cerrar_lista/" + id_mesa + "/" + accion;
      $.ajax({
          type: "GET",
          url: ruta,
          dataType: "JSON",
          success: function(respuesta){
            Swal.fire({
              icon: 'success',
              title: 'LISTA ' + (accion == 1 ? 'ABIERTA' : 'CERRADA'),
              timer: 2000
            });

            $('#lista_1').hide();
            $('#lista_0').hide();
            if (accion == 1) {
              $('#lista_0').show();
            } else {
              $('#lista_1').show();
            }
            
            feather.replace();
          }
      }).fail( function(jqXHR, textStatus, errorThrown) {
        Swal.fire({
          icon: 'error',
          title: 'ERROR: INTENTA DE NUEVO',
          timer: 2000
        });
      });
    }

    var decodeEntities = (function() {
      // this prevents any overhead from creating the object each time
      var element = document.createElement('div');

      function decodeHTMLEntities (str) {
        if(str && typeof str === 'string') {
          // strip script/html tags
          str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
          str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
          element.innerHTML = str;
          str = element.textContent;
          element.textContent = '';
        }

        return str;
      }

      return decodeHTMLEntities;
    })();

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


      @if ($por_hombres_invitados > 40 && $mayor_edad == 0 && ($total_invitados > 5))
        // Swal.fire({
        //   customClass: {
        //     confirmButton: 'btn btn-dark',
        //   },
        //   icon: 'warning',
        //   title: 'LLEVAS AGREGADOS MÁS DEL 40% DE HOMBRES RESPECTO A MUJERES, PUEDES AGREGAR MÁS MUJERES O BORRAR HOMBRES PARA HABILITAR TU LISTA.',
        // });
      @endif

      @if (@$por_hombres_pagados > 40 && $evento->pagado)
        // Swal.fire({
        //   customClass: {
        //     confirmButton: 'btn btn-dark',
        //   },
        //   icon: 'warning',
        //   title: 'EL PORCENTAJE DE HOMBRES PAGADOS DEBE SER MENOR AL 40% EN RELACIÓN A LAS MUJERES, LLEVAS {{$por_hombres_pagados}}% DE HOMBRES PAGADOS, SOLICITA EL PAGO A MÁS MUJERES PARA HABILITAR TU LISTA',
        //   html:`
        //     <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
        //       <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
        //         <div class="modal-body">
        //           <div class="row">
        //             <div class="col-3"></div>
        //             <div class="col-6">
        //               <img src="{{asset('img_admin/icono-mujer.gif')}}" class="w-100">
        //             </div>
        //           </div>
        //         </div>
        //       </div>
        //     </div>`
        // });
      @endif

      @if ($por_pagados_vs_invitados > 85 && $evento->pagado)
        Swal.fire({
          customClass: {
            confirmButton: 'btn btn-dark',
          },
          icon: 'success',
          title: '¡YA LLEVAS EL {{$por_pagados_vs_invitados}}% DE TU LISTA PAGADA, GRACIAS!',
          html:`
            <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
              <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-12">
                      <img src="{{asset('img_admin/gracias.gif')}}" class="w-100">
                    </div>
                  </div>
                </div>
              </div>
            </div>`
        });
      @endif

      @if (!@$mesa->pax)
        pagarPull(false);
      @else
        @if (!$lider->telefono || !$lider->mail || !$lider->fecha_nacimiento)
          modificarPerfil('AGRADECEREMOS SI NOS APOYAS ACTUALIZANDO TU PERFIL')
        @endif
      @endif

      @if ($total_hombres && $total_mujeres)
        var options = {
          exportEnabled: false,
          animationEnabled: true,
          title:{
            text: "Invitados en lista: {{$total_invitados}}"
          },
          width: '400',
          data: [{
            type: "pie", 
            showInLegend: true,
            toolTipContent: "<b>{name}</b>: (#percent%)",
            indexLabel: "{name}",
            legendText: "{name} (#percent%)",
            indexLabelPlacement: "inside",
            dataPoints: [
              { y: {{$total_hombres}}, name: "{{$total_hombres}} Hombres", color: "#c60000", indexLabelFontColor: "#fff", indexLabelFontSize: 20 },
              { y: {{$total_mujeres}}, name: "{{$total_mujeres}} Mujeres", color: "#740000", indexLabelFontColor: "#fff", indexLabelFontSize: 20 },
            ]
          }]
        };
        $("#grafica-invitados").CanvasJSChart(options);
      @endif

      @if ($evento->pagado)
        @if ($total_hombres_pagado && $total_mujeres_pagado)
          var options = {
            exportEnabled: false,
            animationEnabled: true,
            title:{
              text: "Pagados en lista: {{$total_pagados}}"
            },
            width: '400',
            data: [{
              type: "pie",
              showInLegend: true,
              toolTipContent: "<b>{name}</b>: (#percent%)",
              indexLabel: "{name}",
              legendText: "{name} (#percent%)",
              indexLabelPlacement: "inside",
              dataPoints: [
                { y: {{$total_hombres_pagado}}, name: "{{$total_hombres_pagado}} Hombres", color: "#c60000", indexLabelFontColor: "#fff", indexLabelFontSize: 20 },
                { y: {{$total_mujeres_pagado}}, name: "{{$total_mujeres_pagado}} Mujeres", color: "#740000", indexLabelFontColor: "#fff", indexLabelFontSize: 20},
              ]
            }]
          };
          $("#grafica-pagados").CanvasJSChart(options);
        @endif

        @if ($total_hombres_no_pagado && $total_mujeres_no_pagado)
          var options = {
            exportEnabled: false,
            animationEnabled: true,
            title:{
              text: "Pendientes de pago: {{$total_no_pagados}}"
            },
            width: '400',
            data: [{
              type: "pie",
              showInLegend: true,
              toolTipContent: "<b>{name}</b>: (#percent%)",
              indexLabel: "{name}",
              legendText: "{name} (#percent%)",
              indexLabelPlacement: "inside",
              dataPoints: [
                { y: {{$total_hombres_no_pagado}}, name: "1 Hombres", color: "#c60000", indexLabelFontColor: "#fff", indexLabelFontSize: 20 },
                { y: {{$total_mujeres_no_pagado}}, name: "1 Mujeres", color: "#740000", indexLabelFontColor: "#fff", indexLabelFontSize: 20 },
              ]
            }]
          };
          $("#grafica-pendientes").CanvasJSChart(options);
        @endif    
      @endif

    });

    function validarPull(cantidad_pull) {
      if (cantidad_pull > 30) {
        $('#mesa_pull').show();
      } else {
        $('#mesa_pull').hide();
        $('#mesa_pull_cantidad').hide();
        $("input[name=pull][value=0]").prop('checked', true);
      }
    }

    function cargarPull() {
      if ($('input[name="pull"]:checked').val() == 1) {
        $('#mesa_pull_cantidad').show();
      } else {
        $('#mesa_pull_cantidad').hide();
      }
    }

    function pagarPull(pedir_pull = false) {
      var htmlVar = `
        <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
          <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
            <div class="modal-body">
              <div class="row">`;
      if (!pedir_pull) {
        htmlVar += `
          <div class="col-12">
            <label class="fw-bold"> ¿Cuántas invitados llegarána tu mesa? </label>
            <input class="form-control w-100 text-center fs-4" id="cantidad" name="cantidad" type="text" onClick="this.select();" onkeyup="validarPull(this.value)" autocomplete="off" />
          </div>
          <div class="col-12 pt-1" id="mesa_pull" style="display: none;">
            <label class="fw-bold"> ¿Deseas que tu mesa sea PULL? </label>
            <div class="row">
              <div class="col-4"></div>
              <div class="form-check d-inline-block m-0 p-0 col-3 text-start">
                <label class="d-inline-block mt-1"> No </label>
                <input type="radio" class="form-check-input mt-1 d-inline-block" onclick="cargarPull()" name="pull" id="pull" value="0" checked/>
              </div>
              <div class="form-check d-inline-block m-0 p-0 col-3 text-start">
                <label class="d-inline-block mt-1"> Si </label>
                <input type="radio" class="form-check-input mt-1 d-inline-block" onclick="cargarPull()" name="pull" id="pull" value="1"/>
              </div>
            </div>
          </div>
          <div class="col-12 pt-1">
            <label class="fw-bold"> ¿Celebras una ocación especial? </label>
            <select name="evento" id="evento" class="form-control select2 w-100 fs-4" >
              <option value="0">No</option>
              <option value="1">Cumpleaños</option>
              <option value="2">Despedida de soltero</option>
              <option value="3">Pedida de mano</option>
              <option value="4">Graduación</option>
            </select>
          </div>`;
      } else {
        htmlVar += `
          <div class="col-12">
            <img src="{{asset('img_admin/pull.jpg')}}" class="w-100" />
          </div>
          <div class="col-12 pt-1" id="mesa_pull">
            <label class="fw-bold"> ¿Deseas que tu mesa sea PULL? </label>
            <div class="row">
              <div class="col-4"></div>
              <div class="form-check d-inline-block m-0 p-0 col-3 text-start">
                <label class="d-inline-block mt-1"> No </label>
                <input type="radio" class="form-check-input mt-1 d-inline-block" onclick="cargarPull()" name="pull" id="pull" value="0" checked/>
              </div>
              <div class="form-check d-inline-block m-0 p-0 col-3 text-start">
                <label class="d-inline-block mt-1"> Si </label>
                <input type="radio" class="form-check-input mt-1 d-inline-block" onclick="cargarPull()" name="pull" id="pull" value="1"/>
              </div>
            </div>
          </div>`;
      }

      htmlVar += `
                <div class="col-12 pt-1" id="mesa_pull_cantidad" style="display: none;">
                  <label class="fw-bold"> ¿De cuánto será tu PULL? </label>
                  <select name="id_pull" id="id_pull" class="form-control w-100 fs-4" >
                    <option value="1">Mujeres: Q. 150 - Hombres: Q. 200</option>
                    <option value="2">Mujeres: Q. 200 - Hombres: Q. 250</option>
                    <option value="3">Mujeres: Q. 250 - Hombres: Q. 300</option>
                  </select>
                </div>
                <input type="hidden" id="id_lider" name="id_lider" value="" />
              </div>
            </div>
          </div>
        </div>`;

      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-dark fs-1 m-0',
          cancelButton: 'btn btn-secondary fs-1'
        },
        allowOutsideClick: pedir_pull,
        allowEscapeKey: pedir_pull,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        showCancelButton: pedir_pull,
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h1 class="modal-title" id="verifyModalContent_title">INFORMACIÓN DE MI MESA</h1>
              </div>`,
        html: htmlVar,
        didOpen: () => {
          $('select.select2').select2();
        },
      }).then(result => {
        if (result.isConfirmed) {
          var evento = $('#evento').val() == undefined ? 0 : $('#evento').val();
          var cantidad = $('#cantidad').val() == undefined ? 0 : $('#cantidad').val();
          var pull = $('input[name="pull"]:checked').val();
          var id_pull = pull == '0' ? 0 : $('#id_pull').val();
          var ruta     = "/admin/actualizar_mesa/" + {{$mesa->id}} + "/" + cantidad + "/" + evento + "/" + pull + "/" + id_pull;

          $.ajax({
              type: "GET",
              url: ruta,
              dataType: "JSON",
              success: function(respuesta){
                @if (!$lider->telefono || !$lider->mail || !$lider->fecha_nacimiento)
                  if (!pedir_pull) {
                    modificarPerfil('AGRADECEREMOS SI NOS APOYAS ACTUALIZANDO TU PERFIL')
                  }
                @endif

                location.reload();
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
      .navbar-container, .canvasjs-chart-credit, .header-navbar-shadow { display: none !important; }
      .header-navbar.floating-nav { position: relative; }
      .header-navbar .row { width: 100% !important; }
      .app-content.content { padding-top: 20px !important; }
      .vertical-layout.vertical-menu-modern.menu-collapsed .navbar.floating-nav { left: 0 !important }
      @if (File::exists($public_path . 'fondo/' . $evento->id . '.jpg'))
        @php $background_url = asset('fondo/' . ($evento->id) . '.jpg') @endphp
        body { background: black url('{{$background_url}}') repeat top center; }
      @else
        @php $background_url = asset('fondo/0.jpg?' . date('YmdHis')) @endphp
        body { background: black url('{{$background_url}}') repeat top center; }
      @endif

      @php $background_url = asset('img_admin/btn-jbl.jpg?' . date('YmdHis')) @endphp
      .btn-jbl {
        border: none !important;
        background-image: url('{{$background_url}}') !important;
        color: #fff !important;
        padding-left: 35% !important;
        background-position: center;
      }

      .contenedor-graficas { overflow: hidden; }

      .btn-temporada { background-image: linear-gradient(to right, #c60000 , #740000); border-color: #c60000 !important; }
      .canvasjs-chart-container { width: 400px; margin: 0 auto; }

      .modal-dialog .modal-content { background: transparent !important; }
      .gtc-container .swal2-popup { background-size: cover !important; }
      
      .vertical-layout.vertical-menu-modern.menu-collapsed .app-content, .vertical-layout.vertical-menu-modern.menu-collapsed .footer { margin-left: 0 !important; }
  </style>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection

<!-- 
                    <a href="#" onclick="editarInvitado(` + respuesta.id + `)" class="d-block text-center pt-1 text-dark fw-bold" title="Editar">
                      <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit"></i> 
                    </a> -->
