{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header bg-dark">
          <h3 class="card-title">
            CIERRE TOTAL DE LA NOCHE
          </h3>

          <h3 class="card-title">
            <label class="d-inline-block mx-1">Fecha:</label>
            <input type="date" class="form-control d-inline-block w-auto" id="fecha" name="fecha" value="{{$fecha}}" onchange="cambioDeFecha(this.value);" />
            </a>
          </h3>
        </div>
        <div class="card-body">
          @php $total_pagado_efectivo = 0; @endphp
          @php $total_pagado_tarjeta = 0; @endphp
          @foreach($cierres as $key => $item)
            <form id="aprobar_form_{{$item->id}}" method="POST" action="/admin/cierre_total" onsubmit="event.preventDefault(); realizarAccion('aprobar_form_{{$item->id}}')">
              @csrf
              @method('PUT')

              <input type="hidden" name="action" value="1" />
              <input type="hidden" name="id_cierre" value="{{$item->id}}" />
              <input type="hidden" name="id_cobrador" value="{{$item->id_cobrador}}" />
              <div class="row mb-2">
                <div class="col-12 border shadow p-1 text-end">
                  <h1 class="d-block text-start">Cobrador: {{$item->name}}</h1>
                  <div class="row my-1 mb-2">
                    <div class="col-12">
                      <label class="fs-1 d-block" >Total cobrado: <b>Q. {{number_format($item->monto, 2)}}</b></label>
                    </div>
                  </div>

                  @php $pagado = @$item->tarjeta + @$item->tarjeta; @endphp
                  <div class="row mb-1">
                    <div class="col-6">
                      <h1 class="d-block text-center">EFECTIVO</h1>
                      <input class="form-control w-100 text-center" name="pago-efectivo" id="pago-efectivo" type="text" value="{{@number_format($item->efectivo ?: 0, 2)}}" style="font-size: 2rem;" autocomplete="off" />
                      @php $total_pagado_efectivo += $item->efectivo; @endphp
                    </div>
                    <div class="col-6">
                      <h1 class="d-block text-center">TARJETA</h1>
                      <input class="form-control w-100 text-center" name="pago-tarjeta" id="pago-tarjeta" type="text" value="{{@number_format($item->tarjeta ?: 0, 2)}}" style="font-size: 2rem;" autocomplete="off" />
                      @php $total_pagado_tarjeta += $item->tarjeta; @endphp
                    </div>
                  </div>

                  @if ($item->aprobado)
                    <h1 class="bg-success text-light m-auto text-center">APROBADO</h1>
                  @else
                    <button class="btn btn-dark w-100 d-block fs-1" id="btn-pago">
                      <i style="height: 1.8rem; width: 1.8rem;" data-feather="save"></i> APROBAR
                    </button>
                  @endif
                </div>
              </div>
            </form>
          @endforeach

          <div class="row">
            <div class="col-12 border shadow p-1">
              <h1>Cobrado efectivo: Q. {{number_format($total_pagado_efectivo)}}</h1>
              <h1>Cobrado tarjeta:  Q. {{number_format($total_pagado_tarjeta)}}</h1>
              <hr>
              <h1>Total:  Q. {{number_format($total_pagado_efectivo + $total_pagado_tarjeta)}}</h1>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    function realizarAccion(formulario){
      fecha = $('#fecha').val();
      html  = '<input type="hidden" name="fecha" value="' + fecha + '" />';
      $('#' + formulario).attr('onsubmit', '').append(html).submit();
    }
    
    function cambioDeFecha(fecha) {
      if (!isNaN(Number(new Date(fecha)))) {
        window.location = "/admin/cierre_total/" + encodeURIComponent(fecha)
      }
    }
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
