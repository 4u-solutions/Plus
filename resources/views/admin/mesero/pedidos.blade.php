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
              LISTADO DE PEDIDOS
            </h3>
          </div>
          <div class="card-body">
            @foreach ($data as $item)
              <div class="row">
                <div class="col-12 border bg-{{$item->color}} mb-2 shadow rounded">
                  <div class="row align-items-center h-100 p-1">
                    <div class="col-6">
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
                    </div>

                    <div class="col-{{$item->id_tipo == 1 ? 2 : 3}} h-100">
                      @if ($item->id_estado == 5)
                        <div class="embed-responsive embed-responsive-1by1 h-100">
                          <div class="embed-responsive-item h-100">
                            <a href="{{ route('pedido_recibido', ['id_pedido' => $item->id]) }}" class="w-100 h-100 bg-light d-block text-center rounded">
                              <div class="row align-items-center h-100">
                                <div class="col-12">
                                  <h1 class="text-dark m-0">R</h1>
                                </div>
                              </div>
                            </a>
                          </div>
                        </div>
                      @endif 
                    </div>

                    @if ($item->id_tipo == 1)
                      <div class="col-{{$item->id_tipo == 1 ? 2 : 3}} h-100">
                        @if ($item->id_estado > 1)
                          <div class="embed-responsive embed-responsive-1by1 h-100">
                            <div class="embed-responsive-item h-100">
                              <a href="{{ route('cargar_pull', ['id_pedido' => $item->id]) }}" class="w-100 h-100 bg-{{$item->color == 'dark' ? 'light' : 'dark'}} d-block text-center rounded">
                                <div class="row align-items-center h-100">
                                  <div class="col-12">
                                    <h1 class="text-{{$item->color}} m-0">C</h1>
                                  </div>
                                </div>
                              </a>
                            </div>
                          </div>
                        @endif 
                      </div>
                    @endif 

                    @if ($item->id_estado > 1)
                      <div class="col-{{$item->id_tipo == 1 ? 2 : 3}} h-100">
                        <div class="embed-responsive embed-responsive-1by1 h-100">
                          <div class="embed-responsive-item h-100">
                            <a href="{{ route('agregar_productos', ['id' => $item->id]) }}" class="aw-100 h-100 bg-{{$item->color == 'dark' ? 'light' : 'dark'}} d-block text-center rounded">
                              <div class="row align-items-center h-100">
                                <div class="col-12">
                                  <h1 class="text-{{$item->color}} m-0">D</h1>
                                </div>
                              </div>
                            </a>
                          </div>
                        </div>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
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
