{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card mb-1">
        <div class="card-header bg-dark ">
          <h5 class="card-title">
            REPORTE DE RESERVACIONES
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

          <h3 class="card-title">
            <a href="{{route('reporte_por_pax', ['id_evento' => $evento->id, 'reporte' => 1])}}" class="btn btn-light d-block m-auto d-block fs-3 mt-1">
              <i style="height: 1.6rem; width: 1.6rem;" data-feather="download"></i> DESCARGAR
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

        @php @$total_invitados = 0; @endphp
        @php @$total_pagados   = 0; @endphp
        @php @$total_por_pagar = 0; @endphp
        @php @$total_mesas     = 0; @endphp
        @php @$total_mujeres   = 0; @endphp
        @php @$total_hombres   = 0; @endphp
        @php @$total_celebra   = 0; @endphp

        @for ($id_area = 1; $id_area <= 2; $id_area++)
          @php @$sub_total_invitados = 0; @endphp
          @php @$sub_total_pagados   = 0; @endphp
          @php @$sub_total_por_pagar = 0; @endphp
          @php @$sub_total_mesas     = 0; @endphp
          @php @$sub_total_mujeres   = 0; @endphp
          @php @$sub_total_hombres   = 0; @endphp
          @php @$sub_total_celebra   = 0; @endphp
          @if (isset($data[$id_area]))
            <div class="row" id="panel-principal_{{$id_area}}">
              <h1 class="mt-1">{{$id_area == 1 ? 'MESAS' : 'BARRAS'}}</h1>
              <div class="col-4">
                <div class="row">
                  <div class="col-2 border bg-dark"></div>
                  <div class="col-10 border bg-dark">
                    <label class="text-light py-1 fs-5"> Reservación </label>
                  </div>
                  @php $no_lider = 1; @endphp
                  @foreach($data[$id_area] as $key => $item)
                    <div class="col-2 border">
                      <label class="py-1 fs-5"> {{$no_lider}} </label>
                    </div>
                    <div class="col-10 border">
                      <label class="py-1 fs-5"> {{$item->lider}} </label>
                    </div>
                    @php $no_lider++; @endphp
                  @endforeach

                  <div class="col-12 border bg-dark text-end">
                    <label class="text-light fs-1"> Subtotales: </label>
                  </div>
                </div>
              </div>

              <div class="col-8" style="overflow-x: scroll;">
                @php $widthR = 165; @endphp
                @php $numCol = ($id_area == 1 ? 5 : 4) + ($data[$id_area][0]->de_pago ? 2 : 0); @endphp
                @php $widthC = (1 - (($widthR - ($widthR / $numCol)) / $widthR)) * 100; @endphp

                <style type="text/css">
                  .widthC_{{$id_area}} { width: {{$widthC}}% !important; }
                </style>

                <div class="row" style="width: {{$widthR}}%;" id="panel-derecho_{{$id_area}}">
                  <div class="col-1 border bg-dark widthC_{{$id_area}}">
                    <label class="text-light py-1 fs-5"> En lista </label>
                  </div>

                  @if ($data[$id_area][0]->de_pago)
                    <div class="col-1 border bg-dark widthC_{{$id_area}}">
                      <label class="text-light py-1 fs-5"> Pagados </label>
                    </div>
                    <div class="col-1 border bg-dark widthC_{{$id_area}}">
                      <label class="text-light py-1 fs-5"> Por pagar </label>
                    </div>
                  @endif

                  <div class="col-1 border bg-dark widthC_{{$id_area}}">
                    <label class="text-light py-1 fs-5"> Mujeres </label>
                  </div>
                  <div class="col-1 border bg-dark widthC_{{$id_area}}">
                    <label class="text-light py-1 fs-5"> Hombres </label>
                  </div>

                  @if ($id_area == 1)
                    <div class="col-1 border bg-dark widthC_{{$id_area}}">
                      <label class="text-light py-1 fs-5"> Mesas </label>
                    </div>
                  @endif

                  <div class="col-1 border bg-dark widthC_{{$id_area}}">
                    <label class="text-light py-1 fs-5"> Celebración </label>
                  </div>

                  @php @$no_lider        = 1; @endphp
                  @foreach($data[$id_area] as $key => $item)
                    <div class="col-2 border widthC_{{$id_area}}">
                      @php @$sub_total_invitados += $item->invitados; @endphp
                      <label class="py-1 fs-5"> {{$item->invitados}} </label>
                    </div>

                    @if ($item->de_pago)
                      <div class="col-1 border widthC_{{$id_area}}">
                        @php @$sub_total_pagados += $item->pagados; @endphp
                        <label class="py-1 fs-5"> {{$item->pagados}} </label>
                      </div>
                      <div class="col-2 border widthC_{{$id_area}}">
                        @php @$por_pagar = $item->invitados - $item->pagados @endphp
                        @php @$sub_total_por_pagar += $por_pagar; @endphp
                        <label class="py-1 fs-5"> {{$por_pagar}} </label>
                      </div>
                    @endif

                    <div class="col-1 border widthC_{{$id_area}}">
                      @php @$sub_total_mujeres += $item->mujeres; @endphp
                      <label class="py-1 fs-5"> {{$item->mujeres}} </label>
                    </div>

                    <div class="col-1 border widthC_{{$id_area}}">
                      @php @$sub_total_hombres += $item->hombres; @endphp
                      <label class="py-1 fs-5"> {{$item->hombres}} </label>
                    </div>

                    @if ($id_area == 1)
                      <div class="col-1 border widthC_{{$id_area}}">
                        @php @$sub_total_mesas += $item->tot_mesas; @endphp
                        <label class="py-1 fs-5"> {{$item->tot_mesas}} </label>
                      </div>
                    @endif

                    <div class="col-1 border widthC_{{$id_area}}">
                        @php @$sub_total_celebra += $item->celebracion ? 1 : 0; @endphp
                      <label class="py-1 fs-5"> {{$item->celebracion ?: 'Ninguna'}} </label>
                    </div>
                    @php $no_lider++; @endphp
                  @endforeach

                  <div class="col-1 border widthC_{{$id_area}}">
                    <label class="fs-1"> {{$sub_total_invitados}} </label>
                  </div>

                  @if ($data[$id_area][0]->de_pago)
                    <div class="col-1 border widthC_{{$id_area}}">
                      <label class="fs-1"> {{$sub_total_pagados}} </label>
                    </div>
                    <div class="col-2 border widthC_{{$id_area}}">
                      <label class="fs-1"> {{$sub_total_por_pagar}} </label>
                    </div>
                  @endif

                  <div class="col-1 border widthC_{{$id_area}}">
                    <label class="fs-1"> {{$sub_total_mujeres}} </label>
                  </div>
                  <div class="col-1 border widthC_{{$id_area}}">
                    <label class="fs-1"> {{$sub_total_hombres}} </label>
                  </div>

                  @if ($id_area == 1)
                    <div class="col-1 border widthC_{{$id_area}}">
                      <label class="fs-1"> {{$sub_total_mesas}} </label>
                    </div>
                  @endif

                  <div class="col-1 border widthC_{{$id_area}}">
                      <label class="fs-1"> {{$sub_total_celebra}} </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endif

        @php @$total_invitados += $sub_total_invitados; @endphp
        @php @$total_pagados   += $sub_total_pagados; @endphp
        @php @$total_por_pagar += $sub_total_por_pagar; @endphp
        @php @$total_mesas     += $sub_total_mesas; @endphp
        @php @$total_mujeres   += $sub_total_mujeres; @endphp
        @php @$total_hombres   += $sub_total_hombres; @endphp
        @php @$total_celebra   += $sub_total_celebra; @endphp
      @endfor

        
      <div class="row mt-3" id="panel-principal_{{$id_area}}">
        <div class="col-4">
          <div class="row">
            <div class="col-12 text-end">
              <label class="text-dar border-light pt-1 fs-5"> <h1 class="mb-0">RESUMEN</h1> </label>
            </div>
            <div class="col-12 border bg-dark text-end">
              <label class="text-light fs-1"> Totales: </label>
            </div>
          </div>
        </div>

        <div class="col-8" style="overflow-x: scroll;">
          @php $widthR = 165; @endphp
          @php $numCol = 5 + ($data[1][0]->de_pago ? 2 : 0); @endphp
          @php $widthC = (1 - (($widthR - ($widthR / $numCol)) / $widthR)) * 100; @endphp

          <style type="text/css">
            .widthC_1 { width: {{$widthC}}% !important; }
          </style>

          <div class="row" style="width: {{$widthR}}%;" id="panel-derecho">
            <div class="col-1 border bg-dark widthC_1">
              <label class="text-light py-1 fs-5"> En lista </label>
            </div>

            @if ($data[1][0]->de_pago)
              <div class="col-1 border bg-dark widthC_1">
                <label class="text-light py-1 fs-5"> Pagados </label>
              </div>
              <div class="col-1 border bg-dark widthC_1">
                <label class="text-light py-1 fs-5"> Por pagar </label>
              </div>
            @endif

            <div class="col-1 border bg-dark widthC_1">
              <label class="text-light py-1 fs-5"> Mujeres </label>
            </div>
            <div class="col-1 border bg-dark widthC_1">
              <label class="text-light py-1 fs-5"> Hombres </label>
            </div>

            <div class="col-1 border bg-dark widthC_1">
              <label class="text-light py-1 fs-5"> Mesas </label>
            </div>

            <div class="col-1 border bg-dark widthC_1">
              <label class="text-light py-1 fs-5"> Celebraciones </label>
            </div>


            <div class="col-1 border widthC_1">
              <label class="fs-1"> {{$total_invitados}} </label>
            </div>

            @if ($data[1][0]->de_pago)
              <div class="col-1 border widthC_1">
                <label class="fs-1"> {{$total_pagados}} </label>
              </div>
              <div class="col-2 border widthC_1">
                <label class="fs-1"> {{$total_por_pagar}} </label>
              </div>
            @endif

            <div class="col-1 border widthC_1">
              <label class="fs-1"> {{$total_mujeres}} </label>
            </div>
            <div class="col-1 border widthC_1">
              <label class="fs-1"> {{$total_hombres}} </label>
            </div>

            <div class="col-1 border widthC_1">
              <label class="fs-1"> {{$total_mesas}} </label>
            </div>

            <div class="col-1 border widthC_1">
              <label class="fs-1"> {{$total_celebra}} </label>
            </div>
          </div>
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

    // var ancho = $(window).width();
    // if (ancho < 550) {
    //   $('div#panel-alerta').show();
    //   $('div#panel-principal').hide();
    // } else {
    //   console.log(ancho)
    //   if (ancho >= 550 && ancho <= 1000) {
    //     $('div#panel-informativo').css('width', '200%')
    //   } else {
    //     $('div#panel-informativo').removeAttr('style')
    //   }
    //   $('div#panel-alerta').hide();
    //   $('div#panel-principal').show();
    // }
  </script>

  <style>
    .canvasjs-chart-credit { display: none !important; }
  </style>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
