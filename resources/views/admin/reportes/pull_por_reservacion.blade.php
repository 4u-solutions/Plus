{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card mb-1">
        <div class="card-header bg-dark ">
          <h5 class="card-title">
            REPORTE DE PULL
          </h5>

          <h3 class="card-title">
            <label class="d-inline-block">Evento:</label>
            <select name="eventos" id="eventos" class="form-control select2 w-100 fs-3" onchange="cambioDeFecha(this.value);">
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

        <div class="row">
          <div class="col-4 border bg-dark">
            <label class="text-light py-1 fs-5"> Reservación </label>
          </div>
          <div class="col-2 border bg-dark">
            <label class="text-light py-1 fs-5"> Total </label>
          </div>
          <div class="col-2 border bg-dark">
            <label class="text-light py-1 fs-5"> Pagado </label>
          </div>
          <div class="col-2 border bg-dark">
            <label class="text-light py-1 fs-5"> Mujeres </label>
          </div>
          <div class="col-2 border bg-dark">
            <label class="text-light py-1 fs-5"> Hombres </label>
          </div>
        </div>

        <div class="row">
          @foreach($data as $key => $item)
            <div class="col-4 border">
              <label class="py-1 fs-5"> {{$item->nombre}} </label>
            </div>
            <div class="col-2 border">
              @php @$total_pull += $item->total_pull_mujeres + $item->total_pull_hombres; @endphp
              <label class="py-1 fs-5"> Q. {{number_format($item->total_pull_mujeres + $item->total_pull_hombres)}} </label>
            </div>
            <div class="col-2 border">
              @php @$total_pull_pagado += $item->pull_mujeres + $item->pull_hombres; @endphp
              <label class="py-1 fs-5"> Q. {{number_format($item->pull_mujeres + $item->pull_hombres)}} </label>
            </div>
            <div class="col-2 border">
              @php @$total_pull_mujeres += $item->pull_mujeres; @endphp
              <label class="py-1 fs-5"> Q. {{number_format($item->pull_mujeres)}} </label>
            </div>
            <div class="col-2 border">
              @php @$total_pull_hombres += $item->pull_hombres; @endphp
              <label class="py-1 fs-5"> Q. {{number_format($item->pull_hombres)}} </label>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    function cambioDeFecha(id_evento) {
      window.location = "/admin/reporte_pull/" + id_evento
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
