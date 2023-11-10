{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card mb-1">
        <div class="card-header bg-dark ">
          <h5 class="card-title">
            REPORTE LÍDERES Y LISTAS DE MESAS
          </h5>

          <h3 class="card-title">
            <label class="d-inline-block">Evento:</label>
            <select name="eventos" id="eventos" class="form-control select2 w-100 fs-1" onchange="cambioDeFecha(this.value);">
              @foreach ($eventos as $key => $item)
                <option value="{{$item->id}}" {{$item->id == $evento->id ? 'selected' : ''}}>{{$array_mes[(int)substr($item->fecha, 5, 2)]}}  {{$item->nombre}}</option>
              @endforeach
            </select>
          </h3>

          <h3 class="card-title">
            <a href="{{route('admin.reservas.reporte')}}" class="btn btn-light d-block m-auto d-block fs-3 mt-1">
              <i style="height: 1.6rem; width: 1.6rem;" data-feather="arrow-left"></i> REGRESAR
            </a>
          </h3>
        </div>
      </div>

      <div class="card-body py-0 px-1">
        <div class="row" id="panel-alerta" style="display: none;">
          <div class="col-12 bg-warning p-2 text-center">
            <h1>Gira el dispositivo para tener una mejor visualización</h1>
          </div>
        </div>

        <div class="row" id="panel-principal">
          <div class="col-8 col-md-2 border bg-dark text-end">
            <label class="text-light py-1 fs-5"> Aforo: </label>
          </div>
          <div class="col-4 col-md-2 border">
            <label class="py-1 fs-4"> {{$total_pax}} </label>
          </div>
          <div class="col-8 col-md-2 border bg-dark text-end">
            <label class="text-light py-1 fs-5"> Total pax: </label>
          </div>
          <div class="col-4 col-md-2 border">
            <label class="py-1 fs-4"> {{$tot_invitados}} </label>
          </div>
          <div class="col-8 col-md-2 border bg-dark text-end">
            <label class="text-light py-1 fs-5"> Disponibilidad pax: </label>
          </div>
          <div class="col-4 col-md-2 border">
            <label class="py-1 fs-4"> {{$total_pax - $tot_invitados}} </label>
          </div>
          <div class="col-8 col-md-2 border bg-dark text-end">
            <label class="text-light py-1 fs-5"> Mesas totales: </label>
          </div>
          <div class="col-4 col-md-2 border">
            <label class="py-1 fs-4"> {{$data[1][0]->tot_ubicaciones}} </label>
          </div>
          <div class="col-8 col-md-2 border bg-dark text-end">
            <label class="text-light py-1 fs-5"> Total pax mesas: </label>
          </div>
          <div class="col-4 col-md-2 border">
            <label class="py-1 fs-4"> {{$invitados_mesas}} </label>
          </div>  
          <div class="col-8 col-md-2 border bg-dark text-end">
            <label class="text-light py-1 fs-5"> Mesas ocupadas: </label>
          </div>
          <div class="col-4 col-md-2 border">
            <label class="py-1 fs-4"> {{$data[1][0]->tot_ubicaciones - $tot_mesas}} </label>
          </div>
          <div class="col-8 col-md-2 border bg-dark text-end">
            <label class="text-light py-1 fs-5"> Mesas disponibles: </label>
          </div>
          <div class="col-4 col-md-2 border">
            <label class="py-1 fs-4"> {{$tot_mesas}} </label>
          </div>
          <div class="col-8 col-md-2 border bg-dark text-end">
            <label class="text-light py-1 fs-5"> Total barras: </label>
          </div>
          <div class="col-4 col-md-2 border">
            <label class="py-1 fs-4"> {{$tot_barras}} </label>
          </div>
          <div class="col-8 col-md-2 border bg-dark text-end">
            <label class="text-light py-1 fs-5"> Total pax barras: </label>
          </div>
          <div class="col-4 col-md-2 border">
            <label class="py-1 fs-4"> {{$invitados_barras}} </label>
          </div>
        </div>

        <div class="row" id="panel-principal">
          @for ($id_area = 1; $id_area <= 2; $id_area++)
              @if (isset($data[$id_area]))
              <div class="col-12" style="overflow-x: scroll;">
                <h1 class="mt-1">{{$id_area == 1 ? 'MESAS' : 'BARRAS'}}
                <div class="row" style="width: 200%;" id="panel-informativo">
                  <div class="col-1 border bg-dark"></div>
                  <div class="col-3 border bg-dark">
                    <label class="text-light py-1 fs-5"> Líder </label>
                  </div>
                  <div class="col-2 border bg-dark">
                    <label class="text-light py-1 fs-5"> En lista </label>
                  </div>
                  <div class="col-1 border bg-dark">
                    <label class="text-light py-1 fs-5"> Pagados </label>
                  </div>
                  <div class="col-2 border bg-dark">
                    <label class="text-light py-1 fs-5"> Por pagar </label>
                  </div>
                  <div class="col-{{$id_area == 1 ? '2' : 3;}} border bg-dark">
                    <label class="text-light py-1 fs-5"> Aprox. a lograr </label>
                  </div>
                  @if ($id_area == 1)
                    <div class="col-1 border bg-dark">
                      <label class="text-light py-1 fs-5"> Mesas </label>
                    </div>
                  @endif

                  @php @$total_invitados = 0; @endphp
                  @php @$total_pagados   = 0; @endphp
                  @php @$total_por_pagar = 0; @endphp
                  @php @$total_mesas     = 0; @endphp
                  @php @$total_mesas     = 0; @endphp
                  @php @$no_lider        = 1; @endphp
                  @foreach($data[$id_area] as $key => $item)
                    <div class="col-1 border">
                      <label class="py-1 fs-5"> {{$no_lider}} </label>
                    </div>
                    <div class="col-3 border">
                      <label class="py-1 fs-5"> {{$item->lider}} </label>
                    </div>
                    <div class="col-2 border">
                      @php @$total_invitados += $item->invitados; @endphp
                      <label class="py-1 fs-5"> {{$item->invitados}} </label>
                    </div>
                    <div class="col-1 border">
                      @php @$total_pagados += $item->pagados; @endphp
                      <label class="py-1 fs-5"> {{$item->pagados}} </label>
                    </div>
                    <div class="col-2 border">
                      @php @$por_pagar = $item->invitados - $item->pagados @endphp
                      @php @$total_por_pagar += $por_pagar; @endphp
                      <label class="py-1 fs-5"> {{$por_pagar}} </label>
                    </div>
                    <div class="col-{{$id_area == 1 ? '2' : 3;}} border">
                      <input class="form-control fs-4" id="aprox" name="aprox" type="number" value="{{@$data->aprox}}" />
                    </div>
                    @if ($id_area == 1)
                      <div class="col-1 border">
                        @php @$total_mesas += $item->tot_mesas; @endphp
                        <label class="py-1 fs-5"> {{$item->tot_mesas}} </label>
                      </div>
                    @endif
                    @php $no_lider++; @endphp
                  @endforeach

                  <div class="col-4 border bg-dark text-end">
                    <label class="text-light fs-1"> Totales: </label>
                  </div>
                  <div class="col-2 border">
                    <label class="fs-1"> {{$total_invitados}} </label>
                  </div>
                  <div class="col-1 border">
                    <label class="fs-1"> {{$total_pagados}} </label>
                  </div>
                  <div class="col-2 border">
                    <label class="fs-1"> {{$total_por_pagar}} </label>
                  </div>
                  <div class="col-{{$id_area == 1 ? '2' : 3;}} border"></div>
                  @if ($id_area == 1)
                    <div class="col-1 border">
                      <label class="fs-1"> {{$total_mesas}} </label>
                    </div>
                  @endif
                </div>
              </div>
            @endif
          @endfor
        </div>
      </div>

      <div class="card-body pt-0 tab-contenedor">
        <div class="row">
          <div class="col-12">
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    function cambioDeFecha(id_evento) {
      window.location = "/admin/reporte_por_pax/" + id_evento
    }
    
    $(document).ready(function(){
      $(window).resize(function(){
        var ancho = $(window).width();
        if (ancho < 550) {
          $('div#panel-alerta').show();
          $('div#panel-principal').hide();
        } else {
      console.log(ancho)
          if (ancho >= 550 && ancho <= 1000) {
            $('div#panel-informativo').css('width', '200%')
          } else {
            $('div#panel-informativo').removeAttr('style')
          }
          $('div#panel-alerta').hide();
          $('div#panel-principal').show();
        }
      });
    });

    var ancho = $(window).width();
    if (ancho < 550) {
      $('div#panel-alerta').show();
      $('div#panel-principal').hide();
    } else {
      console.log(ancho)
      if (ancho >= 550 && ancho <= 1000) {
        $('div#panel-informativo').css('width', '200%')
      } else {
        $('div#panel-informativo').removeAttr('style')
      }
      $('div#panel-alerta').hide();
      $('div#panel-principal').show();
    }
  </script>

  <style>
    .canvasjs-chart-credit { display: none !important; }
  </style>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
