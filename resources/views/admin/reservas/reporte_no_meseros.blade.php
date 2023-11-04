{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
    <div class="card">
        <div class="card-header bg-dark">
          <h3 class="card-title w-100">
            REPORTE DE MESEROS SIN ASIGNAR
          </h3>

          <h3 class="card-title w-100 mt-1">
            <label class="d-inline-block">Evento:</label>
            <select name="eventos" id="eventos" class="form-control select2 w-100 fs-3 text-center">
              @foreach ($eventos as $key => $item)
                <option value="{{$item->id}}" {{$item->id == $id_evento ? 'selected' : ''}}>{{$array_mes[substr($item->fecha, 5, 2)]}}  {{$item->nombre}}</option>
              @endforeach
            </select>
          </h3>

          <h3 class="card-title w-100">
            <a href="{{route('admin.reservas.reporte')}}" class="btn btn-light d-block m-auto d-block fs-3 mt-1"><i style="height: 1.6rem; width: 1.6rem;" data-feather="list"></i> REGRESAR </a>
          </h3>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="row">
                <div class="col-12 border bg-dark">
                  <label class="text-light py-1 fs-4"> NOMBRE</label>
                </div>
                @foreach ($data as $key => $item)
                  <div class="col-12 border">
                    <label class="py-1 fs-4"> {{$item->name}}</label>
                  </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    $(document).ready(function(){
      $('#eventos').change(function(){
        var fecha = $(this).val();
        window.location = "/admin/reporte_meseros_sin_asignar/" + fecha
      })
    })
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
