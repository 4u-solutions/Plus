{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card mb-1">
        <div class="card-header bg-dark ">
          <h5 class="card-title">
            REPORTES
          </h5>

          <h3 class="card-title">
            <label class="d-inline-block">Evento:</label>
            <select name="eventos" id="eventos" class="form-control select2 w-100 fs-1" onchange="cambioDeFecha(this.value);">
              @foreach ($eventos as $key => $item)
                <option value="{{$item->id}}" {{$item->id == $id_evento ? 'selected' : ''}}>{{$item->nombre}}</option>
              @endforeach
            </select>
          </h3>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="row">
          <div class="col-12">
            <div id="grafica-invitados" class="mb-2" style="height: 400px;"></div>
            <div id="grafica-pagados" class="mb-2" style="height: 400px;"></div>
            <div id="grafica-pendientes" class="mb-2" style="height: 400px;"></div>
            <div id="grafica-abiertas" style="height: 400px;"></div>
          </div>
        </div>
      </div>

      <div class="card-body pt-0 tab-contenedor">
        <div class="row">
          <div class="col-12">
            <div class="row">
              @php $por_mujeres_invitados = @$total_invitados ? round((1 - ((@$total_invitados - @$total_mujeres) / @$total_invitados)) * 100) : 0; @endphp
              @php $por_hombres_invitados = @$total_invitados ? round((1 - ((@$total_invitados - @$total_hombres) / @$total_invitados)) * 100) : 0; @endphp

              @php $por_mujeres_pagados = @$total_pagados ? round((1 - ((@$total_pagados - @$total_mujeres_pagado) / @$total_pagados)) * 100) : 0; @endphp
              @php $por_hombres_pagados = @$total_pagados ? round((1 - ((@$total_pagados - @$total_hombres_pagado) / @$total_pagados)) * 100) : 0; @endphp

              @php $por_mujeres_no_pagado = @$total_no_pagados ? round((1 - ((@$total_no_pagados - @$total_mujeres_no_pagado) / @$total_no_pagados)) * 100) : 0; @endphp
              @php $por_hombres_no_pagado = @$total_no_pagados ? round((1 - ((@$total_no_pagados - @$total_hombres_no_pagado) / @$total_no_pagados)) * 100) : 0; @endphp

              @php $por_pagados_vs_invitados = @$total_invitados ? round((1 - ((@$total_invitados - @$total_pagados) / @$total_invitados)) * 100) : 0; @endphp
            </div>
          </div>

          <div class="col-12 text-start">
            <label class="py-1 fs-1">Total duplicados: <strong>{{$total_duplicados}}</strong></label>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    function cambioDeFecha(id_evento) {
      window.location = "/admin/reporteria_general/" + id_evento
    }

    $(document).ready(function(){
      @if ($total_hombres || $total_mujeres)
        var options = {
          exportEnabled: false,
          animationEnabled: true,
          title:{
            text: "Invitados en lista: {{$total_invitados}}"
          },
          data: [{
            type: "pie", 
            showInLegend: true,
            toolTipContent: "<b>{name}</b>: (#percent%)",
            indexLabel: "{name}",
            legendText: "{name} (#percent%)",
            indexLabelPlacement: "inside",
            dataPoints: [
              { y: {{$total_hombres}}, name: "{{$total_hombres}} Hombres", color: "#01a79d", indexLabelFontSize: 20 },
              { y: {{$total_mujeres}}, name: "{{$total_mujeres}} Mujeres", color: "#ef2b7d", indexLabelFontSize: 20 },
            ]
          }]
        };
        $("#grafica-invitados").CanvasJSChart(options);
      @endif

      @if ($total_hombres_pagado || $total_mujeres_pagado)
        var options = {
          exportEnabled: false,
          animationEnabled: true,
          title:{
            text: "Pagados en lista: {{$total_pagados}}"
          },
          data: [{
            type: "pie",
            showInLegend: true,
            toolTipContent: "<b>{name}</b>: (#percent%)",
            indexLabel: "{name}",
            legendText: "{name} (#percent%)",
            indexLabelPlacement: "inside",
            dataPoints: [
              { y: {{$total_hombres_pagado}}, name: "{{$total_hombres_pagado}} Hombres", color: "#59b83a", indexLabelFontSize: 20 },
              { y: {{$total_mujeres_pagado}}, name: "{{$total_mujeres_pagado}} Mujeres", color: "#006844", indexLabelFontSize: 20},
            ]
          }]
        };
        $("#grafica-pagados").CanvasJSChart(options);
      @endif

      @if ($total_hombres_no_pagado || $total_mujeres_no_pagado)
        var options = {
          exportEnabled: false,
          animationEnabled: true,
          title:{
            text: "Pendientes de pago: {{$total_no_pagados}}"
          },
          data: [{
            type: "pie",
            showInLegend: true,
            toolTipContent: "<b>{name}</b>: (#percent%)",
            indexLabel: "{name}",
            legendText: "{name} (#percent%)",
            indexLabelPlacement: "inside",
            dataPoints: [
              { y: {{$total_hombres_no_pagado}}, name: "{{$total_hombres_no_pagado}} Hombres", indexLabelFontSize: 20 },
              { y: {{$total_mujeres_no_pagado}}, name: "{{$total_mujeres_no_pagado}} Mujeres", indexLabelFontSize: 20 },
            ]
          }]
        };
        $("#grafica-pendientes").CanvasJSChart(options);
      @endif

      @if ($total_abiertas || $total_cerradas)
      var options = {
          exportEnabled: false,
          animationEnabled: true,
          title:{
            text: "Mesas en lista: {{$total_mesas}}"
          },
          data: [{
            type: "pie",
            showInLegend: true,
            toolTipContent: "<b>{name}</b>: (#percent%)",
            indexLabel: "{name}",
            legendText: "{name} (#percent%)",
            indexLabelPlacement: "inside",
            dataPoints: [
              { y: {{$total_abiertas}}, name: "{{$total_abiertas}} Abiertas", indexLabelFontSize: 20 },
              { y: {{$total_cerradas}}, name: "{{$total_cerradas}} Cerradas", indexLabelFontSize: 20 },
            ]
          }]
        };
        $("#grafica-abiertas").CanvasJSChart(options);
      @endif
    })   
  </script>

  <style>
    .canvasjs-chart-credit { display: none !important; }
  </style>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
