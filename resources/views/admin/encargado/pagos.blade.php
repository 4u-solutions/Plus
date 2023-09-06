{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form id="ingresos_form" method="POST" action="/admin/ingresos{{isset($data->id) ? '/'.$data->id : ''}}" onsubmit="event.preventDefault(); realizarAccion('ingresos_form')">

          <input type="hidden" name="action" value="1">
          <div class="card-header">
            <h3 class="card-title">
              PAGOS DE MESEROS DEL {{$fecha_inicial}} AL {{$fecha_final}}
            </h3>

            <h3 class="card-title">
              <label class="d-inline-block mx-1">Fecha:</label>
              <input type="week" class="form-control d-inline-block w-auto" id="semana" name="semana" value="{{$semana}}" onchange="cambioDeFecha(this.value);" />
              </a>
            </h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-3">
                <div class="row">
                  <div class="col-12 border bg-dark">
                    <label class="text-light py-1 fs-3"> Mesero <br><br></label>
                  </div>
                  @foreach ($data as $key => $item)
                    <div class="col-12 border">
                      <label class="fs-3 pt-1"> {{$item['nombre']}} </label>
                    </div>
                  @endforeach
                </div>
              </div>

              <div class="col-9" style="overflow-x: scroll;">
                <div class="row" style="width: 175%;">
                  <div class="row">
                    @foreach ($arrDias as $key => $item)
                      <div class="col-1 border bg-dark" style="width: {{$col_width}}% !important;">
                        <label class="text-light py-1 fs-3"> Venta <br> {{$item}} </label>
                      </div>
                    @endforeach

                    <div class="col-1 border bg-dark" style="width: {{$col_width}}% !important;">
                      <label class="text-light py-1 fs-3"> Venta <br> Total </label>
                    </div>

                    @foreach ($arrDias as $key => $item)
                      <div class="col-1 border bg-dark" style="width: {{$col_width}}% !important;">
                        <label class="text-light py-1 fs-3"> Pago <br> {{$item}} </label>
                      </div>
                    @endforeach

                    <div class="col-1 border bg-dark" style="width: {{$col_width}}% !important;">
                      <label class="text-light py-1 fs-3"> Pago <br> Total </label>
                    </div>
                  </div>

                  @foreach ($data as $key => $item)
                    <div class="row">
                      @foreach ($arrDias as $keyd => $itemd)
                        <div class="col-1 border" style="width: {{$col_width}}% !important;">
                          <label class="fs-3 pt-1"> Q. {{@number_format($item['vendio_' . $keyd], 2)}} </label>
                        </div>
                      @endforeach

                      <div class="col-1 border" style="width: {{$col_width}}% !important;">
                        <label class="fs-3 pt-1"> Q. {{number_format($item['monto'], 2)}} </label>
                      </div>

                      @php $total = 0 @endphp
                      @foreach ($arrDias as $keyd => $itemd)
                        <div class="col-1 border" style="width: {{$col_width}}% !important;">
                          @php
                            $pago = $item['vendio_' . $keyd]  * ($porcentaje_pago / 100);
                            $pago = $item['asignado_' . $keyd] ? ($pago > $item['pago_minimo'] ? $pago : $item['pago_minimo']) : 0;
                            $total += $pago;
                          @endphp
                          <label class="fs-3 pt-1"> Q. {{@number_format($pago, 2)}} </label>
                        </div>
                      @endforeach

                      <div class="col-1 border" style="width: {{$col_width}}% !important;">
                        <label class="fs-3 pt-1"> Q. {{number_format($total, 2)}} </label>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    function cambioDeFecha(fecha) {
      window.location = "/admin/pagos/" + encodeURIComponent(fecha);
    }
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
