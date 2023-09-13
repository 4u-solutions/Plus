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
          <div class="row">
            <div class="col-12 border shadow p-1 text-end">
              <div class="mb-1 overflow-hidden" >
                @php $total  = 0; @endphp
                @foreach ($detalle as $item)
                  <div class="row">
                    <div class="col-12 text-start pe-0 border-bottom">
                      <label class="fs-1 d-block">{{$item->nombre}}</label>
                      <label class="fs-1 d-block">Cantidad: {{$item->cantidad}}</label>
                    </div>
                  </div>
                  @php $total  += $item->subtotal; @endphp
                @endforeach
              </div>

              @if ($pedido->id_estado <= 4)
                <div class="row">
                    <div class="col-12">
                      <a href="{{ route('despachar_pedido', ['id_pedido' => $id_pedido]) }}" class="btn btn-dark d-block fs-3">
                        <i style="height: 1.8rem; width: 1.8rem;" data-feather="check"></i> DESPACHAR
                      </a>
                    </div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
