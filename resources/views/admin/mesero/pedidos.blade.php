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
              LISTADO DE ORDENES DE CLIENTES
            </h3>
          </div>
          <div class="card-body">
            <div class="row">
              @foreach ($data as $item)
                @if (($item->id_tipo == 1) || ($item->id_tipo == 2 && $item->id_estado < 6))
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

                        @if ($item->id_tipo == 2)
                          <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block"><b>Cobrar: Q. {{number_format($item->monto, 2)}}</b></label>
                        @endif
                      </div>

                      <div class="col-12">
                        @if ($item->id_estado == 5)
                          <a href="{{ route('pedido_recibido', ['id_pedido' => $item->id]) }}" class="fs-3 py-1 bg-light d-block text-center rounded">
                            <i style="height: 1.8rem; width: 1.8rem;" data-feather="check"></i> RECIBIDO
                          </a>
                        @endif 
                      </div>

                      @if ($item->id_tipo == 1 && $item->id_estado != 7)
                        @if ($item->id_tipo == 1 && $item->id_estado != 4)
                          <div class="col-12 mt-1">
                            <a href="{{ route('cargar_pull', ['id_pedido' => $item->id]) }}" class="fs-3 py-1 bg-{{$item->color == 'dark' ? 'light' : 'dark'}} text-{{$item->color == 'dark' ? 'dark' : 'light'}} d-block text-center rounded">
                              <i style="height: 1.8rem; width: 1.8rem;" data-feather="credit-card"></i> RECARGAR
                            </a>
                          </div>
                        @endif
                      @endif 

                      @if ($item->id_estado > 1)
                        <div class="col-12 mt-1">
                          <div class="embed-responsive embed-responsive-1by1 h-100">
                            <div class="embed-responsive-item h-100">
                              <a href="{{ route('agregar_productos', ['id' => $item->id]) }}" class="fs-3 py-1 bg-{{$item->color == 'dark' ? 'light' : 'dark'}} text-{{$item->color == 'dark' ? 'dark' : 'light'}} d-block text-center rounded">
                                <i style="height: 1.8rem; width: 1.8rem;" data-feather="list"></i> DETALLE
                              </a>
                            </div>
                          </div>
                        </div>
                      @endif
                    </div>
                  </div>
                @endif
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
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
