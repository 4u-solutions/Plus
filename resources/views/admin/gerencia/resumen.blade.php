{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form id="ingresos_form" method="POST" action="{{ route('admin.gerencia.resumen') }}" onsubmit="event.preventDefault(); realizarAccion('ingresos_form')">
          @csrf
          <div class="card-header bg-dark">
            <h3 class="card-title">
              RESUMEN DE VENTAS
            </h3>

            <h3 class="card-title">
              <label class="d-inline-block mt-1">Fecha inicial:</label>
              <input type="date" class="form-control d-inline-block w-auto" id="fecha_inicial" name="fecha_inicial" value="{{$fecha_inicial}}" />

              <label class="d-inline-block ms-1 mt-1">Fecha final:</label>
              <input type="date" class="form-control d-inline-block w-auto" id="fecha_final" name="fecha_final" value="{{$fecha_final}}" />

              <button type="submit" class="btn btn-secondary fs-1 ms-1 mt-1">
                <i style="height: 1.8rem; width: 1.8rem;" data-feather="search"></i>
                Buscar
              </button>
            </h3>
          </div>
        </form>
        <div class="card-body">
          <div class="row">
            <h1> Resumen ventas bodega</h1>
            <div class="col-12 col-sm-4 border bg-dark text-end">
              <label class="text-light py-1 fs-1"> Total vendido (+): </label>
            </div>
            <div class="col-12 col-sm-8 border">
              <label class="fs-5 pt-1 fs-1"> Q. {{number_format(@$ventas->total_vendido, 2)}} </label>
            </div>
            <div class="col-12 col-sm-4 border bg-dark text-end">
              <label class="text-light py-1 fs-1"> Total costos (-): </label>
            </div>
            <div class="col-12 col-sm-8 border">
              <label class="fs-5 pt-1 fs-1"> Q. {{number_format(@$ventas->total_costo, 2)}} </label>
            </div>
            <div class="col-12 col-sm-4 border bg-dark text-end">
              <label class="text-light py-1 fs-1"> Total cortesías (-): </label>
            </div>
            <div class="col-12 col-sm-8 border">
              <label class="fs-5 pt-1 fs-1"> Q. {{number_format(@$cortesias->monto, 2)}} </label>
            </div>
            <div class="col-12 col-sm-4 border bg-dark text-end">
              <label class="text-light py-1 fs-1"> Comisión meseros (-): </label>
            </div>
            <div class="col-12 col-sm-8 border">
              <label class="fs-5 pt-1 fs-1"> Q. {{number_format(@$comix_meseros, 2)}} </label>
            </div>
            <div class="col-12 col-sm-4 border bg-dark text-end">
              <label class="text-light py-1 fs-1"> Ganancia: </label>
            </div>
            <div class="col-12 col-sm-8 border">
              <label class="fs-5 pt-1 fs-1"> Q. {{number_format(@$ventas->total_vendido - (@$ventas->total_costo + @$cortesias->monto + @$comix_meseros), 2)}} </label>
            </div>
          </div>

          <div class="row mt-2">
            <h1> Resumen cobros bodega</h1>
            <div class="col-12 col-sm-4 border bg-dark text-end">
              <label class="text-light py-1 fs-1"> Efectivo: </label>
            </div>
            <div class="col-12 col-sm-8 border">
              <label class="fs-5 pt-1 fs-1"> Q. {{number_format(@$pagos->efectivo, 2)}} </label>
            </div>
            <div class="col-12 col-sm-4 border bg-dark text-end">
              <label class="text-light py-1 fs-1"> Tarejeta: </label>
            </div>
            <div class="col-12 col-sm-8 border">
              <label class="fs-5 pt-1 fs-1"> Q. {{number_format(@$pagos->tarjeta, 2)}} </label>
            </div>
            <div class="col-12 col-sm-4 border bg-dark text-end">
              <label class="text-light py-1 fs-1"> Total cobrado: </label>
            </div>
            <div class="col-12 col-sm-8 border">
              <label class="fs-5 pt-1 fs-1"> Q. {{number_format(@$pagos->efectivo + @$pagos->tarjeta, 2)}} </label>
            </div>
          </div>

          <div class="row mt-2">
            <h1> Resumen inventario</h1>
            <div class="col-12 col-sm-4 border bg-dark text-end">
              <label class="text-light py-1 fs-1"> Valor venta: </label>
            </div>
            <div class="col-12 col-sm-8 border">
              @php $total_venta = @$inventario->total_final ?: (@$inventario->total_actual ?: @$inventario->total_inicial); @endphp
              <label class="fs-5 pt-1 fs-1"> Q. {{number_format(@$total_venta, 2)}} </label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    var ancho = $(window).width();
    if (ancho < 550) {
      $('#panel-derecho').css('width', (ancho / 1.65) + '%')
    }

    function realizarAccion(formulario){
      $('#' + formulario).attr('onsubmit', '').submit();
    }

    $(document).ready(function(){
      $(window).resize(function(){
        ancho = $(window).width();
        if (ancho < 550) {
          $('#panel-derecho').css('width', (ancho / 1.65) + '%')
        }
      })
    });
  </script>

  @component('admin.components.messagesForm')
  @endcomponent
@endsection
