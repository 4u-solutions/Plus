{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
@php $public_path = (strpos(getcwd(), 'themanorgt') ? getcwd() :  (substr(getcwd(), 0, strrpos(getcwd(), '/')) . '/public')) . '/'; @endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header bg-dark">
          <h3 class="card-title">
            BALANCE DIARIO
          </h3>

          <h3 class="card-title">
            <label class="d-inline-block mx-1">Fecha:</label>
            <input type="date" class="form-control d-inline-block w-auto" id="fecha" name="fecha" value="{{$fecha}}" onchange="cambioDeFecha(this.value);" />
            </a>
          </h3>
        </div>
        <div class="card-body">
          <div class="row" id="panel-alerta">
            <div class="col-12 bg-warning p-2 text-center">
              <h1>Gira el dispositivo para tener una mejor visualización</h1>
            </div>
          </div>
          <div class="row" id="panel-principal">

            @if (File::exists($public_path . 'colaboradores/' . $mesero->id . '.jpg'))
              <img src="{{asset('colaboradores/' . $mesero->id . '.jpg')}}" class="w-100 mb-1">
            @endif

            <div class="col-4 border bg-dark">
              <label class="text-light py-1 fs-3"> Producto </label>
            </div>
            <div class="col-2 border bg-dark">
              <label class="text-light py-1 fs-3"> Cantidad </label>
            </div>
            <div class="col-2 border bg-dark">
              <label class="text-light py-1 fs-3"> Precio </label>
            </div>
            <div class="col-2 border bg-dark">
              <label class="text-light py-1 fs-3"> Subtotal </label>
            </div>
            <div class="col-2 border bg-dark">
              <label class="text-light py-1 fs-3"> Pago </label>
            </div>

            @php $total  = 0; @endphp
            @php $totalP = 0; @endphp
            @php $pago  = 0; @endphp
            @foreach ($balance as $item)
              <div class="col-4 border">
                <label class="py-1 fs-4"> {{$item->nombre}} </label>
              </div>
              <div class="col-2 border">
                <label class="py-1 fs-4"> {{$item->cantidad}} </label>
              </div>
              <div class="col-2 border">
                <label class="py-1 fs-4"> Q. {{number_format($item->precio, 2)}} </label>
              </div>
              <div class="col-2 border bg-dark">
                @php $subtotal = ($item->cantidad * $item->precio); @endphp
                @php $total += $subtotal; @endphp
                <label class="py-1 text-light fs-4"> Q. {{number_format($subtotal, 2)}} </label>
              </div>
              <div class="col-2 border bg-dark">
                @php $pago    = $subtotal * $porcentaje_pago; @endphp
                @php $totalP += $pago; @endphp
                <label class="py-1 text-light fs-4"> Q. {{number_format($pago, 2)}} </label>
              </div>
            @endforeach

            @foreach ($propinas as $item)
              <div class="col-4 border">
                <label class="py-1 fs-4"> Propina </label>
              </div>
              <div class="col-2 border">
                <label class="py-1 fs-4"> 1 </label>
              </div>
              <div class="col-2 border">
                <label class="py-1 fs-4"> Q. {{number_format($item->monto, 2)}} </label>
              </div>
              <div class="col-2 border bg-dark">
                <label class="py-1 text-light fs-4"> Q. {{number_format($item->monto, 2)}} </label>
              </div>
              <div class="col-2 border bg-dark">
                @php $pago    = $item->monto * $porcentaje_propina; @endphp
                @php $totalP += $pago; @endphp
                <label class="py-1 text-light fs-4"> Q. {{number_format($pago, 2)}} </label>
              </div>
            @endforeach
          </div>
          <div class="row" id="panel-principal">
            <div class="col-6 border"></div>
            <div class="col-2 border">
              <label class="py-1 fs-3 text-end d-block"> Total: </label>
            </div>
            <div class="col-2 border bg-dark">
              <label class="py-1 text-light fs-4"> Q. {{number_format($total, 2)}} </label>
            </div>
          </div>
          @if ($mesero->roleUS != 3)
          @endif
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    function cambioDeFecha(fecha) {
      if (!isNaN(Number(new Date(fecha)))) {
        window.location = "/admin/balance/" + encodeURIComponent(fecha)
      }
    }

    $(document).ready(function(){
      $(window).resize(function(){
        ancho = $(window).width();
        if (ancho < 425) {
          $('div#panel-alerta').show();
          $('div#panel-principal').hide();
        } else {
          $('div#panel-alerta').hide();
          $('div#panel-principal').show();
        }
      })
    });

    var ancho = $(window).width();
    if (ancho < 425) {
      $('div#panel-alerta').show();
      $('div#panel-principal').hide();
    } else {
      $('div#panel-alerta').hide();
      $('div#panel-principal').show();
    }
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
