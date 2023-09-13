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
                <div class="col-12 col-lg-6 border bg-{{$item->color}} mb-2 shadow rounded">
                  <div class="row align-items-center h-100 p-1">
                    <div class="col-12">
                      @if ($item->id_tipo == 4)
                        <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block">Cortesía</label>
                      @else
                        <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block">Mesero: {{@$item->mesero}}</label>
                      @endif
                      <label class="fs-3 text-{{$item->color == 'primary' ? 'light' : ($item->color == 'dark' ? 'light' : 'dark')}} d-block">Estado: {{@$item->estado}}</label>
                    </div>

                    <div class="col-12 mt-1">
                      <a href="{{ route('pedido_para_despachar', ['id' => $item->id]) }}" class="fs-3 py-1 bg-{{$item->color == 'dark' ? 'light' : 'dark'}} text-{{$item->color == 'dark' ? 'dark' : 'light'}} d-block text-center rounded">
                        <i style="height: 1.8rem; width: 1.8rem;" data-feather="list"></i> DETALLE
                      </a>
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
