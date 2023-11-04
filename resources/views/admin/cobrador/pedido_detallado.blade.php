{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header bg-dark">
          <h3 class="card-title">
            DETALLE DEL PEDIDO
          </h3>
        </div>
        <div class="card-body">
          <form id="enviar_pago_form" method="POST" action="/admin/enviar_pago/{{$id_pedido}}" onsubmit="event.preventDefault(); realizarAccion('enviar_pago_form')">
            @csrf
            @if($edit)
                @method('PUT')
            @endif

            <input type="hidden" name="id_pedido" value="{{$id_pedido}}">
            <input type="hidden" name="idp_estado" value="{{$pedido->id_estado}}">
            <input type="hidden" name="idp_tipo" value="{{$pedido->id_tipo}}">
            <div class="row">
              <div class="col-12 border shadow p-1 text-end">
                <div class="mb-1 overflow-hidden" >
                  @php $total  = 0; @endphp
                  @foreach ($bdetalle as $item)
                    <div class="row">
                      <div class="col-{{$pedido->id_tipo == 3 ? 6 : 8}} text-start pe-0 border-bottom">
                        <label class="fs-5 d-block">{{$item->nombre}}</label>
                        <label class="fs-5 d-block">Cantidad: {{$item->cantidad}}</label>
                      </div>
                      <div class="col-4 ps-0 border-bottom">
                        <label class="d-block">&nbsp;</label>
                        <label class="fs-5">Q. {{number_format($item->subtotal, 2)}}</label>
                      </div>
                      @if ($pedido->id_tipo == 3)
                        <div class="col-2 ps-0 border-bottom form-check">
                          <input type="checkbox" class="form-check-input ms-1 mt-1" name="pagado[]" id="producto-pagado" value="{{$item->id}}" rel="{{$item->subtotal}}" {{$item->pagado ? 'checked disabled' : ''}} />
                        </div>
                      @endif
                    </div>
                    @php $total  += $item->subtotal; @endphp
                  @endforeach

                  <div class="col-12">
                    @foreach ($mdetalle as $item)
                      <div class="row">
                        <div class="col-12 text-start pe-0 border-bottom">
                          <label class="fs-5 d-block">{{$item->nombre}}</label>
                          <label class="fs-5 d-block">Cantidad: {{$item->cantidad}}</label>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>

                @if ($pedido->id_estado > 1 && $pedido->id_estado < 7 && $pedido->id_tipo != 6)
                  <div class="row mb-1">
                    <div class="col-12">
                      <label class="fs-1 d-block border-top" >Total: <b>Q. <label id="monto_total">{{number_format($total, 2)}}</label> </b></label>
                    </div>
                  </div>
                @endif

                @php $pagado = @$pagos[1]->monto + @$pagos[0]->monto; @endphp
                @if (($pedido->id_tipo == 2 && $pedido->id_estado < 7) || ($pedido->id_tipo == 1 && ($pedido->id_estado == 3 || $pedido->id_estado == 7)) || ($pedido->id_tipo == 3 && $pagado < $total) || ($pedido->id_tipo == 6))
                  <div class="row mb-1">
                    <div class="col-6">
                      <h1 class="d-block text-center">EFECTIVO</h1>
                      <input class="form-control w-100 text-center" name="pago-efectivo" id="pago-efectivo" type="number" value="0" style="font-size: 2rem;" onClick="this.select();" autocomplete="off" />
                    </div>
                    <div class="col-6">
                      <h1 class="d-block text-center">TARJETA</h1>
                      <input class="form-control w-100 text-center" name="pago-tarjeta" id="pago-tarjeta" type="number" value="0" style="font-size: 2rem;" onClick="this.select();" autocomplete="off" />
                    </div>
                  </div>
                @endif

                <div class="row mb-1">
                  <div class="col-12">
                    <label class="fs-1 d-block border-top text-{{$pedido->id_tipo == 6 ? 'success' : 'danger';}}" >{{$pedido->id_tipo == 1 ? 'Saldo' : ($pedido->id_tipo == 6 ? 'Propina' : 'Pagado')}}: <b>Q. <span id="pagado">{{number_format($pedido->id_tipo == 1 ? $pedido->saldo : $pagado, 2)}}</span></b></label>
                    <input type="hidden" id="pagado_total" value ="{{$pedido->id_tipo == 1 ? ($pedido->id_tipo == 3 ? $pagado : $pedido->saldo) : $pagado}}" />

                    @if ($pedido->id_tipo != 6)
                      @if ($pedido->id_tipo == 3)
                        <label class="fs-1 d-block border-top text-danger" >Por pagar: <b>Q. <span id="por_pagar">{{number_format(0, 2)}}</span></b></label>
                      @endif

                      @if ($pedido->id_tipo > 1)
                        <label class="fs-1 d-block border-top text-danger" >Pendiente: <b>Q. <span id="pendiente">{{number_format($total - ($pedido->id_tipo == 1 ? $pedido->saldo : $pagado), 2)}}</span></b></label>
                      @endif
                    @endif
                  </div>
                </div>

                <div class="row">
                  @if ($total != $pagado || $total == 0)
                    @if ($pedido->id_tipo == 1 && $pedido->id_estado == 1 && count($pagos))
                      <button class="btn btn-dark w-100 m-auto d-block fs-1" id="btn-pago"><i style="height: 1.8rem; width: 1.8rem;" data-feather="check"></i> APROBAR
                      </button>
                    @else
                      @if ($pedido->id_tipo > 1)
                        <button class="btn btn-dark w-100 m-auto d-block fs-1" id="btn-pago" {{$pedido->id_tipo != 6 ? 'disabled' : ''}}>
                      @else
                        <button class="btn btn-dark w-100 m-auto d-block fs-1">
                      @endif
                        <i style="height: 1.8rem; width: 1.8rem;" data-feather="credit-card"></i> {{$pedido->id_tipo == 6 ? 'COBRAR' : 'PAGAR'}}
                      </button>
                    @endif
                  @endif
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    var porPagar = {{$total}};
    @if ($pedido->id_estado == 7 || $pedido->id_tipo == 3)
      var saldoPagado = parseInt($('#pagado').html().replace(/,/g,''))
    @else
      var saldoPagado = 0;
    @endif
    function realizarAccion(formulario){
      $('#' + formulario).attr('onsubmit', '').submit();
    }

    function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    $(document).ready(function(){
      pagadoTotal = parseFloat($('#pagado_total').val());

      @if (($pedido->id_tipo == 2 && $pedido->id_estado < 7) || ($pedido->id_tipo == 1 && $pedido->id_estado == 3) || ($pedido->id_tipo == 3) && ($pedido->id_tipo != 6))
        @if (count($pagos) > 0)
          efectivo  = parseInt($('#pago-efectivo').val() == undefined ? 0 : $('#pago-efectivo').val());
          tarjeta   = parseInt($('#pago-tarjeta').val() == undefined ? 0 : $('#pago-tarjeta').val());
          pagado    = efectivo + tarjeta + saldoPagado;
          pendiente = porPagar - pagado;

          if (pagado == porPagar) {
            $('#pagado').parent().parent().removeClass('text-danger').addClass('text-success');
            $('#pendiente').parent().parent().removeClass('d-block').addClass('d-none');
            $('#btn-pago').removeAttr('disabled')
          } else {
            $('#pagado').parent().parent().removeClass('text-success').addClass('text-danger');
            $('#pendiente').parent().parent().removeClass('d-none').addClass('d-block');
            $('#btn-pago').attr('disabled', true)
          }
          @if ($pedido->id_tipo == 3)
            $('#btn-pago').removeAttr('disabled')
          @endif

          pagado   = numberWithCommas(pagado.toFixed(2))
          $('#pagado').html(pagado)

          pendiente   = numberWithCommas(pendiente.toFixed(2))
          $('#pendiente').html(pendiente)
        @endif
      @endif

      $('#pago-efectivo, #pago-tarjeta').keyup(function(){
        efectivo  = parseInt($('#pago-efectivo').val() == '' ? 0 : $('#pago-efectivo').val() );
        tarjeta   = parseInt($('#pago-tarjeta').val() == '' ? 0 : $('#pago-tarjeta').val() );
        pagado    = efectivo + tarjeta + saldoPagado;
        pendiente = porPagar - pagado;

        @if ($pedido->id_tipo != 6)
          if (pagado == porPagar) {
            $('#pagado').parent().parent().removeClass('text-danger').addClass('text-success');
            $('#pendiente').parent().parent().removeClass('d-block').addClass('d-none');
            $('#btn-pago').removeAttr('disabled')
          } else {
            $('#pagado').parent().parent().removeClass('text-success').addClass('text-danger');
            $('#pendiente').parent().parent().removeClass('d-none').addClass('d-block');
            $('#btn-pago').attr('disabled', true)
          }
          @if ($pedido->id_tipo == 3)
            $('#btn-pago').removeAttr('disabled')
          @endif

          pendiente   = numberWithCommas(pendiente.toFixed(2))
          $('#pendiente').html(pendiente)
        @endif

        pagado   = numberWithCommas(pagado.toFixed(2))
        $('#pagado').html(pagado)
      });

      $('input#producto-pagado').click(function(){
        var total_a_pagar = 0;

        $('input#producto-pagado').each(function(){
          if ($(this).is(':checked') && (!$(this).attr('disabled'))) {

            total_a_pagar += parseInt($(this).attr('rel'))
          }
        });

        total_a_pagar = numberWithCommas(total_a_pagar.toFixed(2))
        $('#por_pagar').html(total_a_pagar)
      });

      $('#pago-efectivo, #pago-tarjeta').focusout(function(){
        if ($(this).val() == '') {
          $(this).val('0.00')
        }

        pago = parseInt($(this).val());
        $(this).val(pago.toFixed(2));
      })
    });
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
