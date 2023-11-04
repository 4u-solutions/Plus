{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form id="ingresos_form" method="POST" action="{{ route('admin.gerencia.rep_ventas') }}" onsubmit="event.preventDefault(); realizarAccion('ingresos_form')">
          @csrf
          <div class="card-header bg-dark">
            <h3 class="card-title">
              REPORTE DE VENTAS
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
            <div class="col-3">
              <div class="row">
                <div class="col-8 border bg-dark">
                  <label class="text-light py-1 fs-5"> Producto <br><br class="d-block d-sm-none"> </label>
                </div>
                <div class="col-4 border bg-dark">
                  <label class="text-light py-1 fs-5"> Vendido <br><br class="d-block d-sm-none"> </label>
                </div>

                @foreach ($inventario as $item)
                  <div class="col-8 border">
                    <label class="fs-5 pt-1"> {{$item->nombre}} </label>
                  </div>
                  <div class="col-4 border text-center">
                    <label class="fs-5 pt-1"> {{$item->vendido ?: 0}} </label>
                  </div>
                @endforeach

                <div class="col-12 border bg-dark">
                  <label class="fs-5 pt-1 text-light text-end d-block"> Totales: </label>
                </div>
              </div>
            </div>

            <div class="col-9" style="overflow-x: scroll;">
              <div class="row" style="width: 125%;" id="panel-derecho">
                <div class="col-2 border bg-dark">
                  <label class="text-light py-1 fs-5"> Precio </label>
                </div>
                <div class="col-2 border bg-dark">
                  <label class="text-light py-1 fs-5"> Costo </label>
                </div>
                <div class="col-3 border bg-dark">
                  <label class="text-light py-1 fs-5"> Total vendido </label>
                </div>
                <div class="col-2 border bg-dark">
                  <label class="text-light py-1 fs-5"> Total costo </label>
                </div>
                <div class="col-3 border bg-dark">
                  <label class="text-light py-1 fs-5"> Ganancia </label>
                </div>
                @foreach ($inventario as $item)
                  <div class="col-2 border">
                    <label class="fs-5 pt-1"> Q. {{number_format($item->precio, 2)}} </label>
                  </div>
                  <div class="col-2 border">
                    <label class="fs-5 pt-1"> Q. {{number_format($item->costo, 2)}} </label>
                  </div>
                  <div class="col-3 border">
                    @php @$subtotal_vendido = $item->precio * $item->vendido @endphp
                    @php @$total_vendido   += $subtotal_vendido @endphp
                    <label class="fs-5 pt-1"> Q. {{number_format($subtotal_vendido, 2)}} </label>
                  </div>
                  <div class="col-2 border">
                    @php @$subtotal_costo = $item->costo * $item->vendido @endphp
                    @php @$total_costo   += $subtotal_costo @endphp
                    <label class="fs-5 pt-1"> Q. {{number_format($subtotal_costo, 2)}} </label>
                  </div>
                  <div class="col-3 border">
                    @php @$subtotal_ganancia = ($subtotal_vendido - $subtotal_costo) @endphp
                    @php @$total_ganancia   += $subtotal_ganancia @endphp
                    <label class="fs-5 pt-1"> Q. {{number_format($subtotal_ganancia, 2)}} </label>
                  </div>
                @endforeach

                  <div class="col-2 border">
                    <label class="fs-5 pt-1"></label>
                  </div>
                  <div class="col-2 border">
                    <label class="fs-5 pt-1"></label>
                  </div>
                  <div class="col-3 border">
                    <label class="fs-5 pt-1"> Q. {{number_format(@$total_vendido, 2)}} </label>
                  </div>
                  <div class="col-2 border">
                    <label class="fs-5 pt-1"> Q. {{number_format(@$total_costo, 2)}} </label>
                  </div>
                  <div class="col-3 border">
                    <label class="fs-5 pt-1"> Q. {{number_format(@$total_ganancia, 2)}} </label>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if (@count($cortesias) > 0)
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              CORTESÍAS
            </h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-3 border bg-dark">
                <label class="text-light py-1 fs-5"> Producto </label>
              </div>
              <div class="col-3 border bg-dark">
                <label class="text-light py-1 fs-5"> Precio </label>
              </div>
              <div class="col-3 border bg-dark">
                <label class="text-light py-1 fs-5"> Cantidad </label>
              </div>
              <div class="col-3 border bg-dark">
                <label class="text-light py-1 fs-5"> Total </label>
              </div>
            </div>

            <div class="row">
            @foreach ($cortesias as $item)
                <div class="col-3 border">
                  <label class="fs-5 pt-1"> {{$item->nombre}} </label>
                </div>
                <div class="col-3 border">
                  <label class="fs-5 pt-1"> {{$item->precio}} </label>
                </div>
                <div class="col-3 border">
                  @php @$total_cantidad += $item->cantidad @endphp
                  <label class="fs-5 pt-1"> {{$item->cantidad}} </label>
                </div>
                <div class="col-3 border">
                  @php @$total += ($item->cantidad * $item->precio) @endphp
                  <label class="fs-5 pt-1"> Q. {{number_format($item->cantidad * $item->precio, 2)}} </label>
                </div>
              @endforeach
            </div>

            <div class="row">
              <div class="col-6 border bg-dark text-end">
                <label class="text-light py-1 fs-5"> Totales: </label>
              </div>
              <div class="col-3 border">
                <label class="fs-5 pt-1"> {{$total_cantidad}} </label>
              </div>
              <div class="col-3 border">
                <label class="fs-5 pt-1"> Q. {{number_format($total, 2)}} </label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            TOTALES
          </h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-sm-3 border bg-dark text-end">
              <label class="text-light py-1 fs-5"> Efectivo: </label>
            </div>
            <div class="col-12 col-sm-9 border">
              <label class="fs-5 pt-1"> Q. {{number_format(@$pagos->efectivo, 2)}} </label>
            </div>
            <div class="col-12 col-sm-3 border bg-dark text-end">
              <label class="text-light py-1 fs-5"> Tarjeta: </label>
            </div>
            <div class="col-12 col-sm-9 border">
              <label class="fs-5 pt-1"> Q. {{number_format(@$pagos->tarjeta, 2)}} </label>
            </div>
            <div class="col-12 col-sm-3 border bg-dark text-end">
              <label class="text-light py-1 fs-5"> Totales: </label>
            </div>
            <div class="col-12 col-sm-9 border">
              <label class="fs-5 pt-1"> Q. {{number_format(@$pagos->efectivo + @$pagos->tarjeta, 2)}} </label>
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

    function cambioDeFecha(fecha) {
      if (!isNaN(Number(new Date(fecha)))) {
        window.location = "/admin/reporte-ventas/" + encodeURIComponent(fecha)
      }
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
