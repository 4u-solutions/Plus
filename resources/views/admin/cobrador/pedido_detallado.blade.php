{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
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
                      <div class="col-8 text-start pe-0 border-bottom">
                        <label class="fs-5 d-block">{{$item->nombre}}</label>
                        <label class="fs-5 d-block">Cantidad: {{$item->cantidad}}</label>
                      </div>
                      <div class="col-4 ps-0 border-bottom">
                        <label class="d-block">&nbsp;</label>
                        <label class="fs-5">Q. {{number_format($item->subtotal, 2)}}</label>
                      </div><!-- 
                      <div class="col-3 ps-0 border-bottom">
                        <a href="javascript: return false;" onclick="borrarBotella({{$item->id}}, '{{$item->subtotal}}', {{$item->mixers}}, 1);" class="btn btn-danger"><i data-feather="trash"></i></a>
                      </div> -->
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
                        <!-- <div class="col-2 ps-0 border-bottom">
                          <a href="javascript: return false;" onclick="borrarBotella({{$item->id}}, '{{$item->subtotal}}', {{$item->cantidad}}, 0);" class="btn btn-danger"><i data-feather="trash"></i></a>
                        </div> -->
                      </div>
                    @endforeach
                  </div>
                </div>

                  <div class="row mb-1">
                    <div class="col-12">
                      <label class="fs-1 d-block border-top" >Total: <b>Q. <label id="monto_total">{{number_format($total, 2)}}</label> </b></label>
                    </div>
                  </div>

                @if ($pedido->id_estado < 7)
                  <div class="row mb-1">
                    <div class="col-6">
                      <h1 class="d-block text-center">EFECTIVO</h1>
                      <input class="form-control w-100 text-center" name="pago-efectivo" id="pago-efectivo" type="number" value="{{@number_format($pagos[0]->monto ? ($pedido->id_estado == 7 ? 0 : $pagos[0]->monto) : 0, 2)}}" style="font-size: 2rem;" onClick="this.select();" autocomplete="off" />
                    </div>
                    <div class="col-6">
                      <h1 class="d-block text-center">TARJETA</h1>
                      <input class="form-control w-100 text-center" name="pago-tarjeta" id="pago-tarjeta" type="number" value="{{@number_format($pagos[1]->monto ? ($pedido->id_estado == 7 ? 0 : $pagos[1]->monto) : 0, 2)}}" style="font-size: 2rem;" onClick="this.select();" autocomplete="off" />
                    </div>
                  </div>
                @endif
                @php $pagado = @$pagos[1]->monto + @$pagos[0]->monto; @endphp

                @if ($pedido->id_estado < 7)
                  <div class="row mb-1">
                    <div class="col-12">
                      <label class="fs-1 d-block border-top text-danger" >{{$pedido->id_tipo == 1 ? 'Saldo' : 'Pagado'}}: <b>Q. <span id="pagado">{{number_format($pedido->id_tipo == 1 ? $pedido->saldo : $pagado, 2)}}</span></b></label>
                      <input type="hidden" id="pagado_total" value ="{{$pedido->id_tipo == 1 ? $pedido->saldo : $pagado}}" />

                      @if ($pedido->id_tipo > 1)
                        <label class="fs-1 d-block border-top text-danger" >Pendiente: <b>Q. <span id="pendiente">0</span></b></label>
                      @endif
                    </div>
                  </div>
                @endif

                @if ($pedido->id_estado < 7)
                  <div class="row">
                    @if ($pedido->id_tipo == 1 && $pedido->id_estado == 1 && count($pagos))
                      <button class="btn btn-dark w-100 m-auto d-block fs-1"><i style="height: 1.8rem; width: 1.8rem;" data-feather="check"></i> APROBAR</button>
                    @else
                      <button class="btn btn-dark w-100 m-auto d-block fs-1" {{$pedido->id_tipo > 1 ? 'disabled' : ''}} id="btn-pago"><i style="height: 1.8rem; width: 1.8rem;" data-feather="credit-card"></i> PAGAR</button>
                    @endif
                  </div>

                  @if (count($pagos) > 0)
                    <div class="row mt-1">
                      <a href="/admin/editar_pedido/{{$pedido->id}}" class="btn btn-dark w-100 m-auto d-block fs-1">
                        <i style="height: 1.8rem; width: 1.8rem;" data-feather="trash"></i> MODIFICAR ORDEN
                      </a>
                    </div>
                  @endif
                @endif
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    var porPagar = {{$total}};
    console.log(porPagar)
    function realizarAccion(formulario){
      $('#' + formulario).attr('onsubmit', '').submit();
    }

    function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    $(document).ready(function(){
      pagadoTotal = parseFloat($('#pagado_total').val());

      @if ($pedido->id_estado < 7)
        @if (count($pagos) > 0)
          efectivo  = parseInt($('#pago-efectivo').val());
          tarjeta   = parseInt($('#pago-tarjeta').val());
          pagado    = efectivo + tarjeta;
          pendiente = porPagar - pagado;

          if (pagado == porPagar) {
            $('#pagado').parent().parent().removeClass('text-danger').addClass('text-success');
            $('#pendiente').parent().parent().removeClass('d-block').addClass('d-none');
            $('#btn-pago').removeAttr('disabled')
          }

          pagado   = numberWithCommas(pagado.toFixed(2))
          $('#pagado').html(pagado)

          pendiente   = numberWithCommas(pendiente.toFixed(2))
          $('#pendiente').html(pendiente)
        @endif
      @endif

      $('#pago-efectivo, #pago-tarjeta').keyup(function(){
          if ($(this).val() == '') {
            $(this).val('0.00')
          }
          efectivo  = parseInt($('#pago-efectivo').val());
          tarjeta   = parseInt($('#pago-tarjeta').val());
          pagado    = efectivo + tarjeta;
          pendiente = porPagar - pagado;

          if (pagado == porPagar) {
            $('#pagado').parent().parent().removeClass('text-danger').addClass('text-success');
            $('#pendiente').parent().parent().removeClass('d-block').addClass('d-none');
            $('#btn-pago').removeAttr('disabled')
          }

          pagado   = numberWithCommas(pagado.toFixed(2))
          $('#pagado').html(pagado)

          pendiente   = numberWithCommas(pendiente.toFixed(2))
          $('#pendiente').html(pendiente)
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
