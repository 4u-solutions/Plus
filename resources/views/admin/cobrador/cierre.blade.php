{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form id="enviar_pago_form" method="POST" action="/admin/cierre/{{isset($data->id) ? '/'.$data->id : ''}}" onsubmit="event.preventDefault(); realizarAccion('enviar_pago_form')">
          @csrf
          @if($edit)
              @method('PUT')
          @endif

          <input type="hidden" name="action" value="{{$action}}" />
          <div class="card-header bg-dark">
            <h3 class="card-title">
              CIERRE DE CAJA DE COBRADOR
            </h3>

            <h3 class="card-title">
              <label class="d-inline-block mx-1">Fecha:</label>
              <input type="date" class="form-control d-inline-block w-auto" id="fecha" name="fecha" value="{{$fecha}}" onchange="cambioDeFecha(this.value);" />
              </a>
            </h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12 border shadow p-1 text-end">
                <div class="mb-1 overflow-hidden" >

                <div class="row my-1 mb-2">
                  <div class="col-12">
                    @php $monto = $monto - $descarga_efectivo; @endphp
                    <label class="fs-1 d-block" >Total cobrado: <b>Q. {{number_format($monto, 2)}}</b></label>
                    @if ($descarga_efectivo > 0)
                      <label class="fs-4 d-block" >Descarga de efectivo: <b>Q. {{number_format($descarga_efectivo, 2)}}</b></label>
                    @endif
                  </div>
                </div>

                @php $pagado = @$cierre[0]->tarjeta + @$cierre[0]->efectivo; @endphp
                <div class="row mb-1">
                  <div class="col-6">
                    <h1 class="d-block text-center">EFECTIVO</h1>
                      <input class="form-control w-100 text-center" name="pago-efectivo" id="pago-efectivo" type="text" value="{{@number_format($pagado > 0 ? $cierre[0]->efectivo : 0)}}" style="font-size: 2rem;" onClick="this.select();" autocomplete="off" {{$pagado <= 0 ? '' : ($pagado == $monto ? 'disabled' : '')}} />
                      <label class="fs-4 d-block text-center text-primary">Cobrado: Q. {{@number_format($efectivo - $descarga_efectivo)}}</label>
                  </div>
                  <div class="col-6">
                    <h1 class="d-block text-center">TARJETA</h1>
                      <input class="form-control w-100 text-center" name="pago-tarjeta" id="pago-tarjeta" type="text" value="{{@number_format($pagado > 0 ? $cierre[0]->tarjeta : 0)}}" style="font-size: 2rem;" onClick="this.select();" autocomplete="off" {{$pagado <= 0 ? '' : ($pagado == $monto ? 'disabled' : '')}} />
                      <label class="fs-4 d-block text-center text-primary">Cobrado: Q. {{@number_format($tarjeta)}}</label>
                  </div>
                </div>

                <div class="row mb-1">
                  <div class="col-12">
                    <label class="fs-1 d-block border-top {{$pagado == $monto ? 'text-success' : 'text-danger'}}" >Pagado: <b>Q. <span id="pagado">{{number_format($pagado, 2)}}</span></b></label>
                    @if ($pagado < $monto)
                      <label class="fs-1 d-block border-top text-danger" >Pendiente: <b>Q. <span id="pendiente">{{$pagado > 0 ? ($monto - ($cierre[0]->efectivo + $cierre[0]->tarjeta)) : 0}}</span></b></label>
                    @endif
                  </div>
                </div>

                <button class="btn btn-dark w-100 m-auto d-block fs-1" id="btn-pago*" {{$pagado > 0 ? 'disabled' : ''}} >
                  <i style="height: 1.8rem; width: 1.8rem;" data-feather="save"></i> GUARDAR
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    var porPagar = {{$monto ?: 0}};

    function realizarAccion(formulario){
      $('#' + formulario).attr('onsubmit', '').submit();
    }
    
    function cambioDeFecha(fecha) {
      if (!isNaN(Number(new Date(fecha)))) {
        window.location = "/admin/cierre/" + encodeURIComponent(fecha)
      }
    }

    function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    $(document).ready(function(){
      $('#pago-efectivo, #pago-tarjeta').keyup(function(){
        console.log(1)
        efectivo  = parseInt($('#pago-efectivo').val());
        tarjeta   = parseInt($('#pago-tarjeta').val());
        pagado    = efectivo + tarjeta;
        pendiente = porPagar - pagado;

        console.log(pagado)
        if (pagado == porPagar) {
          // $('#pagado').parent().parent().removeClass('text-danger').addClass('text-success');
          // $('#pendiente').parent().parent().removeClass('d-block').addClass('d-none');
          $('#btn-pago').removeAttr('disabled')
        }

        pagado   = numberWithCommas(pagado.toFixed(2))
        $('#pagado').html(pagado)

        pendiente   = numberWithCommas(pendiente.toFixed(2))
        $('#pendiente').html(pendiente)
      });
    });
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
