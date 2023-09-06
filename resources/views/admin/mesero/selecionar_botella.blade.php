{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            {{ strtoupper($tipo[0]->nombre) }}: AGREGAR BOTELLAS AL PEDIDO
          </h3>

          <a href="{{ route('agregar_productos', ['id' => $id_pedido]) }}" title="REGRESAR" class="btn btn-secondary"> REGRESAR </a>
        </div>
        <div class="card-body">
          <div class="row">
            @foreach ($data as $item)
              <div class="col-4 border bg-dark shadow rounded">
                <a href="{{ route('selecionar_botella', ['id_pedido' => $id_pedido, 'id_producto' => $item->id]) }}" title="{{$item->nombre}}">
                  <div class="embed-responsive embed-responsive-1by1">
                    <div class="embed-responsive-item h-100">
                      <div class="row align-items-center h-100 py-5 px-1 text-center">
                        <label class="fs-1 text-light">{{$item->nombre}}</label>
                        <label class="fs-1 text-light">Q. {{ number_format($item->precio, 2) }}</label>

                      </div>
                    </div>
                  </div>
                </a>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            DETALLE DEL PEDIDO
          </h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-4 border bg-secondary shadow rounded">
              <a href="javascript: return false;" title="Tipo de waro">
                <div class="embed-responsive embed-responsive-1by1">
                  <div class="embed-responsive-item h-100">
                    <div class="row align-items-center h-100 py-5 px-1 text-center">
                      <label class="fs-1 text-light">Mixers</label>
                    </div>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-8 border shadow py-3 text-end">
              <label class="fs-1 d-block">Total:</label>
              <label class="fs-1 d-block"><b>Q. 1,570.00</b></label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    function crearPedido(id_tipo){
        Swal.fire({
            title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                      <h1 class="modal-title" id="verifyModalContent_title">DATOS DEL CLIENTE</h1>
                  </div>`,
            html:`
                 <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
                     <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
                         <div class="modal-body">
                             <div class="row">
                                 <label class="col-form-label">NOMBRE</label>
                                 <textarea id="nombre-cliente" class="form-control"></textarea>
                             </div>
                         </div>
                     </div>
                 </div>
                 `,
             preConfirm: function(){
                 let val = $('#nombre-cliente').val(),
                     cad = `<input type="hidden" id="nombre-cliente" name="nombre-cliente" value="${val}" />`;
               $('#pedido_form_' + id_tipo).append(cad).attr('onsubmit', '').submit();
               return true
           }
        })
        return true;
    }
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
