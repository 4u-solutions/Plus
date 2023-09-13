{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
          <input type="hidden" name="action" value="1">
          <div class="card-header">
            <h3 class="card-title">
              LISTADO DE PEDIDOS POR COBRAR
            </h3>
          </div>
          <div class="card-body">
            <div class="row">
              @foreach ($data as $item)
                <div class="col-12 col-lg-6 border bg-{{$item->color}} mb-2 shadow rounded">
                  <div class="row align-items-center h-100 p-1">
                    <div class="col-12">
                      <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block">
                        @if ($item->id_tipo == 1)
                          <b>Cuenta: {{$item->tipo}}</b>
                        @else
                          Cuenta: {{$item->tipo}}
                        @endif
                      </label>
                      <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block">
                        @if ($item->id_tipo == 1)
                          <b>Mesero: {{$item->mesero}}</b>
                        @else
                          Mesero: {{$item->mesero}}
                        @endif
                      </label>
                      <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block">Cliente: {{$item->cliente}}</label>
                      <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block">
                        @if ($item->id_tipo == 1)
                          <b>Estado: {{$item->estado}}</b>
                        @else
                          Estado: {{$item->estado}}
                        @endif
                      </label>
                      @if ($item->id_tipo == 1)
                        <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block"><b>Saldo: Q. {{number_format($item->saldo, 2)}}</b></label>
                      @endif
                    </div>

                    <div class="col-12">
                      <label class="text-{{$item->color == 'primary' ? 'light' : 'dark'}} fs-3">{{$item->id_tipo == 2 ? 'Cobrar' : 'Aprobar'}}: Q. {{number_format($item->id_tipo == 1 ? $item->aprobar : $item->monto, 2)}}</label>
                    </div>

                    <div class="col-12 mt-1">
                      @if ($item->id_estado > 3 && $item->id_estado < 7)
                        <a href="#" onclick="editarPedido({{$item->id}})" class="fs-3 py-1 bg-{{$item->color == 'dark' ? 'light' : 'dark'}} text-{{$item->color == 'dark' ? 'dark' : 'light'}} d-block text-center rounded">
                          <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit"></i> EDITAR
                        </a>
                      @else
                        <a href="#" onclick="aceptarCobro({{$item->id}}, {{$item->id_tipo}})" class="fs-3 py-1 bg-{{$item->color == 'dark' ? 'light' : 'dark'}} text-{{$item->color == 'dark' ? 'dark' : 'light'}} d-block text-center rounded">
                          <i style="height: 1.8rem; width: 1.8rem;" data-feather="credit-card"></i> 
                          @if ($item->id_tipo == 2 || ($item->id_tipo == 1 && $item->id_estado == 3))
                            COBRAR
                          @else
                            APROBAR
                          @endif
                        </a>
                      @endif
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    setInterval(function(){
      location.reload();  
    }, 12500)

    function aceptarCobro(id_pedido, id_tipo) {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-success fs-1',
          cancelButton: 'btn btn-secondary fs-1'
        },
        showCancelButton: true,
        confirmButtonText: 'SI',
        cancelButtonText: 'NO',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h12 class="modal-title" id="verifyModalContent_title">¿DESEAS ` + (id_tipo == 2 ? 'COBRAR' : 'APROBAR') + ` ESTA ORDEN?</h2>
              </div>`,
      }).then(result => {
        if (result.isConfirmed) {
          window.location.href = "/admin/pedido_detallado/" + id_pedido;
        }
      });
    }

    function editarPedido(id_pedido) {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-success fs-1',
          cancelButton: 'btn btn-secondary fs-1'
        },
        showCancelButton: true,
        confirmButtonText: 'SI',
        cancelButtonText: 'NO',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h12 class="modal-title" id="verifyModalContent_title">¿DESEAS PERMITIR AL MESERO EDITAR EL PEDIDO?</h2>
              </div>`,
      }).then(result => {
        if (result.isConfirmed) {
          window.location.href = "/admin/editar_pedido/" + id_pedido;
        }
      });
    }
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
