{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            BALANCE DIARIO
          </h3>
          </h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-4 border bg-dark">
              <label class="text-light py-1 fs-3"> Producto </label>
            </div>
            <div class="col-2 border bg-dark">
              <label class="text-light py-1 fs-3"> Cantidad </label>
            </div>
            <div class="col-2 border bg-dark">
              <label class="text-light py-1 fs-3"> Precio </label>
            </div>
            <div class="col-2 border bg-dark">
              <label class="text-light py-1 fs-3"> Subtotal </label>
            </div>
            <div class="col-2 border bg-dark">
              <label class="text-light py-1 fs-3"> Pago </label>
            </div>

            @php $total  = 0; @endphp
            @php $totalP = 0; @endphp
            @php $pago  = 0; @endphp
            @foreach ($balance as $item)
              <div class="col-4 border">
                <label class="py-1 fs-4"> {{$item->nombre}} </label>
              </div>
              <div class="col-2 border">
                <label class="py-1 fs-4"> {{$item->cantidad}} </label>
              </div>
              <div class="col-2 border">
                <label class="py-1 fs-4"> Q. {{number_format($item->precio, 2)}} </label>
              </div>
              <div class="col-2 border bg-dark">
                @php $total += $item->subtotal; @endphp
                <label class="py-1 text-light fs-4"> Q. {{number_format($item->subtotal, 2)}} </label>
              </div>
              <div class="col-2 border bg-dark">
                @php $pago    = $item->subtotal * ($porcentaje_pago / 100); @endphp
                @php $totalP += $pago; @endphp
                <label class="py-1 text-light fs-4"> Q. {{number_format($pago, 2)}} </label>
              </div>
            @endforeach
          </div>
          <div class="row">
            <div class="col-6 border"></div>
            <div class="col-2 border">
              <label class="py-1 fs-3 text-end d-block"> Total: </label>
            </div>
            <div class="col-2 border bg-dark">
              <label class="py-1 text-light fs-4"> Q. {{number_format($total, 2)}} </label>
            </div>
            <div class="col-2 border bg-{{$totalP > $mesero->pago_minimo ? 'success' : 'danger'}}">
              <label class="py-1 text-light fs-4"> Q. {{number_format($totalP, 2)}} </label>
            </div>
          </div>
          @if ($mesero->roleUS != 3): 
            <div class="row">
              <div class="col-8 border"></div>
              <div class="col-4 border bg-{{$totalP > $mesero->pago_minimo ? 'success' : 'danger'}}">
                <label class="py-1 fs-3 text-center d-block text-light">
                  {{$totalP > $mesero->pago_minimo ? 'Superó ' : 'No superó'}} el pago mínimo
                </label>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
