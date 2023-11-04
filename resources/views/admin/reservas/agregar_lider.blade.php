{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form id="eventos_form" method="POST" action="/admin/agregar_lider{{isset($data->id) ? '/'.$data->id : ''}}" onsubmit="event.preventDefault(); realizarAccion('eventos_form')">
          @csrf
          @if($edit)
              @method('PUT')
          @endif

          <div class="card-header bg-dark">
            <h3 class="card-title w-100">
              @if (@$data->id)
                EDITAR LÍDER: {{$data->name}}
              @else
                AGREGAR LÍDER
              @endif

              <a href="{{route('admin.reservas.mesas')}}" class="btn btn-light w-100 m-auto d-block fs-1 mt-1"><i style="height: 1.8rem; width: 1.8rem;" data-feather="arrow-left"></i> REGRESAR </a>
            </h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="row">
                  <div class="col-10 border bg-dark">
                    <label class="text-light py-1 fs-3"> ¿Es mayor de 25 años? </label>
                  </div>
                  <div class="col-2 border form-check px-0" bis_skin_checked="1">
                    <input type="checkbox" class="form-check-input mt-1 ms-1" name="mayor_edad" id="mayor_edad" value="1">
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Nombre </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-3" id="nombre" name="nombre" type="text" value="{{@$data->name}}" />
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Sexo </label>
                  </div>
                  <div class="col-8 border py-1">
                    <select name="sexo" id="sexo" class="form-control select2 w-100 my-1 fs-4" >
                      <option value="0" {{@$data->sexo == 0 ? 'selected' : ''}}>Mujer</option>
                      <option value="1" {{@$data->sexo == 1 ? 'selected' : ''}}>Hombre</option>
                    </select>
                  </div>
                  <!-- <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Teléfono </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-3" id="telefono" name="telefono" type="text" value="{{@$data->telefono}}" />
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Correo </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-3" id="mail" name="mail" type="text" value="{{@$data->mail}}" />
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Fecha Nacimiento </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-3" id="fecha" name="fecha" type="date" value="{{@$data->fecha_nacimiento}}" /> -->
                  </div>

                  <div class="col-12 border">
                      <button class="btn btn-dark my-1 w-100 fs-3" style="">
                          {{ @$edit ? 'Actualizar datos' : 'Guardar'}}
                      </button>
                  </div>
                </div>
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
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
