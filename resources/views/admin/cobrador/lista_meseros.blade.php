{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form id="enviar_pago_form" method="POST" action="/admin/lista_meseros" onsubmit="event.preventDefault(); realizarAccion('enviar_pago_form')">
          <div class="card-header bg-dark">
            <h3 class="card-title">
              LISTADO DE MESEROS
            </h3>

            <h3 class="card-title">
              <label class="d-inline-block mx-1">Fecha:</label>
              <input type="date" class="form-control d-inline-block w-auto" id="fecha" name="fecha" value="{{$fecha}}" onchange="cambioDeFecha(this.value);" />
              </a>
            </h3>
          </div>
          <div class="card-body">
            <div class="row">
              @csrf
              @if($edit)
                  @method('PUT')
              @endif

              @foreach($meseros as $item)
                <div class="col-6 col-sm-3 form-check mb-2">
                  <input type="checkbox" name="meseros[]" id="meseros-{{$item->id}}" class="form-check-input" value="{{$item->id}}" {{$item->id_asignacion ? 'checked' : ''}} />
                  <label class="form-check-label" for="meseros-{{$item->id}}">
                    <h1 class="d-inline-block ms-0 my-0" style="{{$item->id_asignacion ? 'text-decoration: underline' : ''}}">{{$item->name}}</h1>
                  </label>
                </div>
              @endforeach

              <div class="col-12 border">
                <button type="submit" class="btn btn-secondary fs-1 my-1 w-100" style=""> 
                  <i style="height: 1.8rem; width: 1.8rem;" data-feather="save"></i>
                  Guardar 
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    function cambioDeFecha(fecha) {
      if (!isNaN(Number(new Date(fecha)))) {
        window.location = "/admin/lista_meseros/" + encodeURIComponent(fecha)
      }
    }

    function realizarAccion(formulario){
      $('#' + formulario).attr('onsubmit', '').submit();
    }
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
