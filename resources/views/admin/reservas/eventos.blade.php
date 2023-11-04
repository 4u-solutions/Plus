{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form id="eventos_form" method="POST" action="/admin/agregar_evento{{isset($data->id) ? '/'.$data->id : ''}}" onsubmit="event.preventDefault(); realizarAccion('eventos_form')">
          @csrf
          @if($edit)
              @method('PUT')
          @endif

          <div class="card-header bg-dark">
            <h3 class="card-title">
              LISTADO DE EVENTOS
            </h3>

            <h3 class="card-title mt-1 mt-sm-0 w-100">
              <a href="{{route('agregar_evento')}}" class="btn btn-light w-100 m-auto d-block fs-1"><i style="height: 1.6rem; width: 1.6rem;" data-feather="calendar"></i> AGREGAR EVENTO </a>

            <h3 class="card-title mt-1 mt-sm-0 w-100">
              <input type="month" class="form-control d-inline-block w-100 fs-3" id="fecha" name="fecha" value="{{$fecha}}" onchange="cambioDeFecha(this.value);" />
              </a>
            </h3>

            <h3 class="card-title w-100">
                <input class="form-control w-100 mt-1 fs-3" id="busqueda" type="text" value="" placeholder="Buscar evento" />
            </h3>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-12">
                @foreach ($data as $key => $item)
                  <div class="row mb-1" id="evento_contenedor" rel="{{strtolower($item->nombre)}}">
                    <div class="col-12 border bg-dark">
                      <label class="text-light py-1 fs-4"> {{$item->nombre}} </label>
                    </div>
                    <div class="col-8 border bg-dark">
                      <label class="text-light py-1 fs-3"> ¿Es pagado? </label>
                    </div>
                    <div class="col-4 border form-check" bis_skin_checked="1">
                      <label class="py-1 fs-4"> {{@$item->pagado ? 'Si' : 'No'}} </label>
                    </div>
                    <div class="col-4 border bg-dark">
                      <label class="text-light py-1 fs-4"> Fecha </label>
                    </div>
                    <div class="col-8 border">
                      <label class="py-1 fs-4"> {{$item->fecha}} </label>
                    </div>
                    <div class="col-4 border bg-dark">
                      <label class="text-light py-1 fs-4"> Detalle </label>
                    </div>
                    <div class="col-8 border">
                      <label class="py-1 fs-4 d-block pb-0"> Mesas: {{$item->mesas ?: 0}} </label>
                      <label class="fs-4 d-block pb-1"> Barras: {{$item->barras ?: 0}} </label>
                    </div>
                    <div class="col-4 border bg-dark">
                      <label class="text-light py-1 fs-4"> Acciones </label>
                    </div>
                    <div class="col-8 border">
                      <label class="py-1 fs-4">
                        <a href="{{route('agregar_evento', ['id' => $item->id])}}" title="Editar" class="d-inline-block text-dark">
                          <i style="height: 1.6rem; width: 1.6rem;" data-feather="edit"></i> 
                        </a>
                        <a href="#" onclick="borrarEvento({{$item->id}})" class="d-inline-block text-dark" title="Borrar">
                          <i style="height: 1.6rem; width: 1.6rem;" data-feather="trash"></i> 
                        </a>
                        <a href="{{route('admin.reservas.mesas', ['id' => $item->id])}}" class="d-inline-block text-dark" title="Ver mesas">
                          <i style="height: 1.6rem; width: 1.6rem;" data-feather="grid"></i> 
                        </a>
                      </label>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    function realizarAccion(formulario){
      $('#' + formulario).attr('onsubmit', '').submit();
    }

    function cambioDeFecha(fecha) {
      if (!isNaN(Number(new Date(fecha)))) {
        window.location = "/admin/eventos/" + encodeURIComponent(fecha)
      }
    }

    $(document).ready(function(){
      $('#busqueda').keyup(function(){
        var valor_busqueda = $(this).val();
        valor_busqueda = valor_busqueda.toLowerCase();

        if (valor_busqueda != '') {
          $('div#evento_contenedor').hide();
        } else {
          $('div#evento_contenedor').show();
        }

         $("div#evento_contenedor[rel*='" + valor_busqueda + "']").show();
      })
    })
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
