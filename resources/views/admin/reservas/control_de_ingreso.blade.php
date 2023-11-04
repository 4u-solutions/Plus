{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
    <div class="card">
        <div class="card-header bg-dark">
          <h3 class="card-title w-100">
            CONTROL DE INGRESOS
          </h3>

          <h3 class="card-title w-100">
              <input class="form-control w-100 mt-1 fs-3" id="busqueda" type="text" value="" placeholder="Buscar mesa" />
          </h3>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-12">
              @foreach ($data as $key => $item)
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
