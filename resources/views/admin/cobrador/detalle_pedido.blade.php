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
                      <div class="col-6 text-start pe-0 border-bottom">
                        <label class="fs-5 d-block">{{$item->nombre}}</label>
                        <label class="fs-5 d-block">Cantidad: {{$item->cantidad}}</label>
                      </div>
                      <div class="col-4 ps-0 border-bottom">
                        <label class="d-block">&nbsp;</label>
                        <label class="fs-5">Q. {{number_format($item->subtotal, 2)}}</label>
                      </div>
                      <div class="col-2 ps-0 border-bottom text-center">
                        <a href="#" class="text-danger mt-1 d-block" onclick="borrarOrden({{$item->id}})">
                          <i style="height: 1.8rem; width: 1.8rem;" data-feather="trash"></i>
                        </a>
                      </div>
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
                  
                  <div class="row mb-1">
                    <div class="col-12">
                      <label class="fs-1 d-block border-top" >Total: <b>Q. <label id="monto_total">{{number_format($total, 2)}}</label> </b></label>
                    </div>
                  </div>

                  @php $pagado = @$pagos[1]->monto + @$pagos[0]->monto; @endphp
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
                    <a href="{{route('admin.mesero.pedidos')}}" class="btn btn-dark w-100 m-auto d-block fs-1 rounded"><i style="height: 1.8rem; width: 1.8rem;" data-feather="arrow-left-circle"></i> REGRESAR </a>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    function borrarOrden(id_pedido) {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-success fs-1',
          cancelButton: 'btn btn-secondary fs-1'
        },
        showCancelButton: true,
        confirmButtonText: 'SI',
        cancelButtonText: 'NO',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h2 class="modal-title" id="verifyModalContent_title">¿DESEAS CANCELAR ESTE PRODUCTO?</h2>
                </div>`,
          html:`
            <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
              <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
                <div class="modal-body p-0">
                  <div class="row">
                    <div class="col-6">
                      <h3 class="d-inline-block">Cambio</h3>
                      <div class="form-check d-inline-block m-0 p-0">
                        <input type="radio" class="form-check-input ms-1 mt-1" name="tipo_devolucion" value="1" checked/>
                      </div>
                    </div>
                    <div class="col-6">
                      <h3 class="d-inline-block">Devolucion</h3>
                      <div class="form-check d-inline-block m-0 p-0">
                        <input type="radio" class="form-check-input ms-1 mt-1" name="tipo_devolucion" value="2"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>`
      }).then(result => {
        if (result.isConfirmed) {
          if ($("input[name='tipo_devolucion']:checked").val() == '2') {
            Swal.fire({
              customClass: {
                confirmButton: 'btn btn-success fs-1',
              },
              confirmButtonText: 'OK',
              title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                        <h2 class="modal-title" id="verifyModalContent_title">¿CÓMO SE REALIZARÁ LA DEVOLUCIÓN DEL DINERO?</h2>
                      </div>`,
                html:`
                  <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
                    <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
                      <div class="modal-body p-0">
                        <div class="row">
                          <div class="col-6">
                            <h3 class="d-block text-center">Efectivo</>
                            <input class="form-control h-100 w-100 text-center" id="efectivo" name="efectivo" type="text" value="0" style="font-size: 2rem;" onClick="this.select();" autocomplete="off" />
                          </div>
                          <div class="col-6">
                            <h3 class="d-block text-center">Tarjeta</>
                            <input class="form-control h-100 w-100 text-center" id="tarjeta" name="tarjeta" type="text" value="0" style="font-size: 2rem;" onClick="this.select();" autocomplete="off" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>`
            }).then(result => {
              if (result.isConfirmed) {
                if ($("input[name='tipo_devolucion']:checked").val() == '2') {
                  
                }
                // window.location.href = "/admin/borrar_orden/" + id_pedido;
              }
            });
          }
        }
      });
    }
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
