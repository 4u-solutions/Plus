{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form id="eventos_form" method="POST" action="/admin/agregar_colaborador{{isset($data->id) ? '/'.$data->id : ''}}" onsubmit="event.preventDefault(); realizarAccion('eventos_form')" enctype="multipart/form-data">
          @csrf
          @if($edit)
              @method('PUT')
          @endif

          <div class="card-header">
            <h3 class="card-title">
              @if (@$data->id)
                COLABORADOR: {{$data->name}}
              @else
                AGREGAR COLABORADOR
              @endif
            </h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="row">
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Nombre </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-3" id="nombre" name="nombre" type="text" value="{{@$data->name}}" />
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Cargo </label>
                  </div>
                  <div class="col-8 border py-1">
                    <select name="id_rol" id="id_rol" class="form-control select2 w-100 my-1 fs-4" >
                      @foreach ($cargos as $key => $item)
                        <option value="{{$item->id}}" {{$item->id == @$data->id}}>{{$item->nameRole}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Foto </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-3" id="foto" name="foto" type="file" />
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

    $(document).ready(function(){
      $('select.select2').select2();
    })
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
