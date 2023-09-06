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
              PEDIDOS POR DESPACHAR
            </h3>
          </div>
          <div class="card-body">
            <div class="row">
              @foreach ($data as $item)
                <div class="col-6 border bg-{{$item->color}} mb-2 shadow rounded">
                  <div class="row align-items-center h-100 p-1">
                    <div class="col-9">
                      @if ($item->id_tipo == 4)
                        <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block">Cortesía</label>
                      @else
                        <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block">Mesero: {{$item->mesero}}</label>
                      @endif
                      <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block">Estado: {{$item->estado}}</label>
                    </div>

                    <div class="col-3 h-100">
                      <div class="embed-responsive embed-responsive-1by1 h-100">
                        <div class="embed-responsive-item h-100">
                          <a href="{{ route('pedido_para_despachar', ['id' => $item->id]) }}" class="aw-100 h-100 bg-{{$item->color == 'dark' ? 'light' : 'dark'}} d-block text-center rounded">
                            <div class="row align-items-center h-100">
                              <div class="col-12">
                                <h1 class="text-{{$item->color}} m-0">D</h1>
                              </div>
                            </div>
                          </a>
                        </div>
                      </div>
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
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
