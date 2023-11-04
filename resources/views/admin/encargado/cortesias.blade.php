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
              LISTADO DE CORTESÍAS
            </h3>

            <a href="{{ route('admin.gerencia.agregar_cortesia', ['id_pedido' => 0]) }}" class="btn btn-light">
              NUEVA CORTESÍA
            </a>
          </div>
          <div class="card-body">
            @foreach ($data as $item)
              <div class="row">
                <div class="col-12 border bg-{{$item->id_estado == 6 ? 'success' : 'dark'}} mb-2 shadow rounded">
                  <div class="row align-items-center h-100 p-1">
                    <div class="col-12">
                      <label class="fs-3 text-light d-block">Cuenta: {{$item->tipo}}</label>
                      <label class="fs-3 text-light d-block">Cliente: {{$item->cliente}}</label>
                      <label class="fs-3 text-light d-block">Estado: {{$item->estado}}</label>
                      @if ($item->id_tipo == 1)
                        <label class="fs-3 text-light d-block">Saldo: Q. {{number_format($item->saldo, 2)}}</label>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </form>
      </div>
    </div>
  </div>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
