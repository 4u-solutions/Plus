{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
          <input type="hidden" name="action" value="1">
          <div class="card-header bg-dark">
            <h3 class="card-title">
              LISTADO DE ORDENES DE CLIENTES
            </h3>
          </div>
          <div class="card-body">
            <div class="row">
              @foreach ($data as $item)
                <div class="col-12 col-lg-6 border bg-{{$item->color}} mb-2 shadow rounded position-relative">
                  <a href="#" class="fs-3 py-1 px-2 bg-danger text-light position-absolute end-0" onclick="borrarOrden({{$item->id}})">
                    <i style="height: 1.8rem; width: 1.8rem;" data-feather="trash"></i>
                  </a>

                  <div class="row align-items-center h-100 p-1">
                    <div class="col-12">
                      <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block">
                          Cuenta: {{$item->tipo}}
                      </label>

                      @if ($item->id_tipo != 6)
                        <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block">Cliente: {{$item->cliente}}</label>
                        <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block">
                            Estado: {{$item->estado}}
                        </label>
                      @endif

                      @if ($item->id_tipo == 1)
                        <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block"><b>Saldo: Q. {{number_format($item->saldo, 2)}}</b></label>
                      @endif

                      @if ($item->id_tipo == 2 || $item->id_tipo == 6)
                        <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block"><b>Cobrado: Q. {{number_format($item->id_tipo == 2 ? $item->pagado : $item->monto, 2)}}</b></label>
                      @endif

                      @if ($item->id_tipo == 3)
                        <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block"><b>Pendiente de cobro: Q. {{number_format($item->saldo, 2)}}</b></label>
                      @endif
                    </div>

                    @if (($item->id_tipo == 1 && $item->id_estado > 1) || ($item->id_tipo > 1 && $item->id_estado == 2) || ($item->id_tipo == 3 && $item->id_estado > 1))
                      <div class="col-12 mt-1">
                        <a href="{{ route('agregar_productos', ['id' => $item->id]) }}" class="fs-3 py-1 bg-{{$item->color == 'dark' ? 'light' : 'dark'}} text-{{$item->color == 'dark' ? 'dark' : 'light'}} d-block text-center rounded">
                          <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit"></i> ORDENAR
                        </a>
                      </div>
                    @endif

                    @if ($item->id_tipo != 6)
                      <div class="col-12 mt-1">
                        <a href="{{ route('detalle_pedido', ['id' => $item->id]) }}" class="fs-3 py-1 bg-{{$item->color == 'dark' ? 'light' : 'dark'}} text-{{$item->color == 'dark' ? 'dark' : 'light'}} d-block text-center rounded">
                          <i style="height: 1.8rem; width: 1.8rem;" data-feather="list"></i> DETALLE / MODIFICAR
                        </a>
                      </div>
                    @endif

                    @if ($item->saldo > 0 && $item->id_tipo == 3)
                      <div class="col-12 mt-1">
                        <a href="{{ route(('pedido_detallado'), ['id' => $item->id]) }}" class="fs-3 py-1 bg-{{$item->color == 'dark' ? 'light' : 'dark'}} text-{{$item->color == 'dark' ? 'dark' : 'light'}} d-block text-center rounded">
                          <i style="height: 1.8rem; width: 1.8rem;" data-feather="credit-card"></i> COBRAR
                        </a>
                      </div>
                    @endif

                    @if ($item->id_tipo == 1 && $item->id_estado != 7)
                      @if ($item->id_tipo == 1 && $item->id_estado != 4)
                        <div class="col-12 mt-1">
                          <a href="{{ route('cargar_pull', ['id_pedido' => $item->id]) }}" class="fs-3 py-1 bg-{{$item->color == 'dark' ? 'light' : 'dark'}} text-{{$item->color == 'dark' ? 'dark' : 'light'}} d-block text-center rounded">
                            <i style="height: 1.8rem; width: 1.8rem;" data-feather="credit-card"></i> RECARGAR
                          </a>
                        </div>
                      @endif
                    @endif 
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
    // setInterval(function(){
    //   location.reload();  
    // }, 12500)
    
    function cambiarMesero() {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-success fs-1',
          cancelButton: 'btn btn-secondary fs-1'
        },
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancelar',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h1 class="modal-title" id="verifyModalContent_title">SELECCIONAR MESERO</h1>
              </div>`,
        html:`
          <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
            <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
              <div class="modal-body">
                <div class="row">
                  <div class="col-12">
                    <select class="w-100 fs-1 h-100 select2" id="meseros" name="meseros">
                      @foreach($meseros as $key => $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>`,
        didOpen: function(){
          $('select.select2').select2();
          window.setTimeout(function () {
            $('#meseros').select2('open');
          }, 500);
          $(document).on('select2:open', () => {
              document.querySelector('.select2-search__field').focus();
              console.log('entro')
          });
        }
      }).then(result => {
        if (result.isConfirmed) {
          var ruta = "/admin/cambiar_mesero/" + $('#meseros').val()
          window.location.href = ruta;
        }
      });
    }

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
                  <h12 class="modal-title" id="verifyModalContent_title">¿DESEAS BORRAR ESTA ORDEN?</h2>
              </div>`,
      }).then(result => {
        if (result.isConfirmed) {
          window.location.href = "/admin/borrar_orden/" + id_pedido;
        }
      });
    }

    $(document).ready(function(){
      @if ($session_id_mesero == 0)
        cambiarMesero();
      @endif
    });
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
