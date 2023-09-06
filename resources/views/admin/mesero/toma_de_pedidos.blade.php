{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            TOMA DE PEDIDOS
          </h3>
        </div>
        <div class="card-body">
          @foreach ($data as $item)
            <div class="row">
              <form id="pedido_form_{{$item->id}}" method="POST" action="/admin/toma_de_pedidos" enctype="multipart/form-data" onsubmit="event.preventDefault();">
                @csrf
                <input type="hidden" name="id_tipo" value="{{$item->id}}">
                <a  href="javascript: return false;" onclick="crearPedido('{{$item->id}}');" title="Tipo de pedido">
                  <div class="col-12 border bg-dark mb-2 shadow rounded">
                    <div class="embed-responsive embed-responsive-1by1">
                        <div class="embed-responsive-item h-100">
                          <div class="row align-items-center h-100 p-5">
                            <label class="fs-1 text-light">{{$item->nombre}}</label>
                          </div>
                        </div>
                      </div>
                  </div>
                </a>
              </form>
            </div>
          @endforeach
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

      // $('#pedido_form_' + id_tipo + ' a').css('pointer-events', 'none');
      // let cad = `<input type="hidden" id="nombre-cliente" name="nombre-cliente" value="" />`;
      // $('#pedido_form_' + id_tipo).append(cad).attr('onsubmit', '').submit();
      // return true;
    }
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
