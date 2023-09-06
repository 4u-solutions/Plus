{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            PRODUCTOS VENDIDOS
          </h3>

          <h3 class="card-title">
            <label class="d-inline-block mx-1">Fecha:</label>
            <input type="date" class="form-control d-inline-block w-auto" id="fecha" name="fecha" value="{{$fecha}}" onchange="cambioDeFecha(this.value);" />
            </a>
          </h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-4">
              <div class="row">
                <div class="col-9 border bg-dark">
                  <label class="text-light py-1 fs-5"> Producto <br><br class="d-block d-sm-none"> </label>
                </div>
                <div class="col-3 border bg-dark">
                  <label class="text-light py-1 fs-5"> Precio <br><br class="d-block d-sm-none"> </label>
                </div>

                @foreach ($inventario as $item)
                  <div class="col-9 border">
                    <label class="fs-5 pt-1"> {{$item->nombre}} </label>
                  </div>
                  <div class="col-3 border">
                    <label class="fs-5 pt-1"> {{$item->precio}} </label>
                  </div>
                @endforeach

                <div class="col-12 border bg-dark">
                  <label class="fs-5 pt-1 text-light text-end d-block"> Totales: </label>
                </div>
              </div>
            </div>

            <div class="col-8" style="overflow-x: scroll;">
              <div class="row" style="width: 250%;" id="panel-derecho">
                <div class="col-2 border bg-dark">
                  <label class="text-light py-1 fs-5"> Total según meseros </label>
                </div>
                <div class="col-2 border bg-dark">
                  <label class="text-light py-1 fs-5"> Total según cuadre físico </label>
                </div>
                <div class="col-2 border bg-dark">
                  <label class="text-light py-1 fs-5"> Diferencia de unidades </label>
                </div>
                <div class="col-2 border bg-dark">
                  <label class="text-light py-1 fs-5"> Vendido según meseros </label>
                </div>
                <div class="col-2 border bg-dark">
                  <label class="text-light py-1 fs-5"> Vendido según cuadre físico </label>
                </div>
                <div class="col-2 border bg-dark">
                  <label class="text-light py-1 fs-5"> Diferencia de dinero </label>
                </div>
                @foreach ($inventario as $item)
                  <div class="col-2 border">
                    @php @$total_meseros += $item->meseros @endphp
                    <label class="fs-5 pt-1"> {{$item->meseros ?: 0}} </label>
                  </div>
                  <div class="col-2 border">
                    @php @$total_bodega += $item->bodega @endphp
                    <label class="fs-5 pt-1"> {{$item->bodega}} </label>
                  </div>
                  <div class="col-2 border">
                    @php @$total_diferencia += ($item->meseros - $item->bodega) @endphp
                    <label class="fs-5 pt-1"> {{$item->meseros - $item->bodega}} </label>
                  </div>
                  <div class="col-2 border">
                    @php @$total_meseros_vendido += ($item->meseros * $item->precio) @endphp
                    <label class="fs-5 pt-1"> Q. {{number_format($item->meseros * $item->precio, 2)}} </label>
                  </div>
                  <div class="col-2 border">
                    @php @$total_bodega_vendido += ($item->bodega * $item->precio) @endphp
                    <label class="fs-5 pt-1"> Q. {{number_format($item->bodega * $item->precio, 2)}} </label>
                  </div>
                  <div class="col-2 border">
                    @php @$total_diferencia_vendido += (($item->meseros - $item->bodega) * $item->precio) @endphp
                    <label class="fs-5 pt-1"> Q. {{number_format(($item->meseros - $item->bodega) * $item->precio, 2)}} </label>
                  </div>
                @endforeach

                  <div class="col-2 border">
                    <label class="fs-5 pt-1"> {{@$total_meseros}} </label>
                  </div>
                  <div class="col-2 border">
                    <label class="fs-5 pt-1"> {{@$total_bodega}} </label>
                  </div>
                  <div class="col-2 border">
                    <label class="fs-5 pt-1"> {{@$total_diferencia}} </label>
                  </div>
                  <div class="col-2 border">
                    <label class="fs-5 pt-1"> Q. {{number_format(@$total_meseros_vendido, 2)}} </label>
                  </div>
                  <div class="col-2 border">
                    <label class="fs-5 pt-1"> Q. {{number_format(@$total_bodega_vendido, 2)}} </label>
                  </div>
                  <div class="col-2 border">
                    <label class="fs-5 pt-1"> Q. {{number_format(@$total_diferencia_vendido, 2)}} </label>
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
              <label class="fs-5 pt-1"> Q. {{number_format($pagos->efectivo, 2)}} </label>
            </div>
            <div class="col-12 col-sm-3 border bg-dark text-end">
              <label class="text-light py-1 fs-5"> Tarjeta: </label>
            </div>
            <div class="col-12 col-sm-9 border">
              <label class="fs-5 pt-1"> Q. {{number_format($pagos->tarjeta, 2)}} </label>
            </div>
            <div class="col-12 col-sm-3 border bg-dark text-end">
              <label class="text-light py-1 fs-5"> Totales: </label>
            </div>
            <div class="col-12 col-sm-9 border">
              <label class="fs-5 pt-1"> Q. {{number_format($pagos->efectivo + $pagos->tarjeta, 2)}} </label>
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
        window.location = "/admin/resumen/" + encodeURIComponent(fecha)
      }
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
