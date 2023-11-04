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
                <option value="{{$item->id}}" {{$item->id == $evento->id ? 'selected' : ''}}>{{$array_mes[substr($item->fecha, 5, 2)]}}  {{$item->nombre}}</option>
              @endforeach
            </select>
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
          <div class="col-4 border bg-dark">
            <label class="text-light py-1 fs-5"> Total Pax </label>
          </div>
          <div class="col-2 border">
            <label class="py-1 fs-4"> {{$total_pax}} </label>
          </div>
          <div class="col-4 border bg-dark">
            <label class="text-light py-1 fs-5"> Mesas disponibles </label>
          </div>
          <div class="col-2 border">
            <label class="py-1 fs-4"> {{$tot_areas}} </label>
          </div>
        </div>

        <div class="row" id="panel-principal">
          <div class="col-12" style="overflow-x: scroll;">
            div class="row" style="width: 200%;" id="panel-informativo">
              <div class="col-4 border bg-dark">
                <label class="text-light py-1 fs-5"> Líder </label>
              </div>
              <div class="col-2 border bg-dark">
                <label class="text-light py-1 fs-5"> Invitados </label>
              </div>
              <div class="col-1 border bg-dark">
                <label class="text-light py-1 fs-5"> Pagados </label>
              </div>
              <div class="col-2 border bg-dark">
                <label class="text-light py-1 fs-5"> Por pagar </label>
              </div>
              <div class="col-2 border bg-dark">
                <label class="text-light py-1 fs-5"> Aprox. a lograr </label>
              </div>
              <div class="col-1 border bg-dark">
                <label class="text-light py-1 fs-5"> Mesas </label>
              </div>

              @foreach($reporte_pax as $key => $item)
                <div class="col-4 border">
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
                <div class="col-2 border">
                  <input class="form-control fs-4" id="aprox" name="aprox" type="number" value="{{@$data->aprox}}" />
                </div>
                <div class="col-1 border">
                  <label class="py-1 fs-5"> </label>
                </div>
              @endforeach

              <div class="col-4 border bg-dark text-end">
                <label class="text-light fs-1"> Totales: </label>
              </div>
              <div class="col-3 border">
                <label class="fs-1"> {{$total_invitados}} </label>
              </div>
              <div class="col-2 border">
                <label class="fs-1"> {{$total_pagados}} </label>
              </div>
              <div class="col-2 border">
                <label class="fs-1"> {{$total_por_pagar}} </label>
              </div>
              <div class="col-5 border">
              </div>
            </div>
          </div>
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
    $(document).ready(function(){
      $(window).resize(function(){
        var ancho = $(window).width();
        if (ancho < 550) {
          $('div#panel-alerta').show();
          $('div#panel-principal').hide();
        } else {
      console.log(ancho)
          if (ancho >= 550 && ancho <= 1000) {
            $('#panel-informativo').css('width', '200%')
          } else {
            $('#panel-informativo').removeAttr('style')
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
        $('#panel-informativo').css('width', '200%')
      } else {
        $('#panel-informativo').removeAttr('style')
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