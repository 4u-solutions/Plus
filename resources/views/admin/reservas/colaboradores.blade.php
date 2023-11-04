{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
@php $public_path = (strpos(getcwd(), 'themanorgt') ? getcwd() :  (substr(getcwd(), 0, strrpos(getcwd(), '/')))) . '/'; @endphp
  <div class="row">
    <div class="col-md-12">
    <div class="card">
        <div class="card-header bg-dark">
          <h3 class="card-title w-100">
            COLABORADORES
          </h3>

          <h3 class="card-title w-100">
            <a href="{{route('agregar_colaborador')}}" class="btn btn-light d-block m-auto d-block fs-3 mt-1 px-0"><i style="height: 1.6rem; width: 1.6rem;" data-feather="user"></i> AGREGAR COLABORADOR </a>
          </h3>

          <h3 class="card-title w-100">
              <input class="form-control w-100 mt-1 fs-3" id="busqueda" type="text" value="" placeholder="Buscar colaborador" />
          </h3>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-12">
              @foreach ($data as $key => $item)
                <div class="row mb-1" id="mesa_contenedor" rel="{{$item->name}}">
                  <div class="col-12 border bg-dark">
                    <label class="text-light py-1 fs-4"> {{$item->name}}</label>
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Puesto </label>
                  </div>
                  <div class="col-8 border">
                    <label class="py-1 fs-4"> {{$item->puesto}}</label>
                  </div>
                  @if (File::exists($public_path . 'colaboradores/' . $item->id . '.jpg'))
                    <div class="col-12 border">
                      <img src="{{asset('colaboradores/' . $item->id . '.jpg')}}" class="w-100">
                    </div>
                  @endif
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Acciones </label>
                  </div>
                  <div class="col-8 border">
                    <label class="py-1 fs-4">
                      <a href="{{route('agregar_colaborador', ['id' => $item->id])}}" title="Editar" class="d-inline-block text-dark">
                        <i style="height: 1.6rem; width: 1.6rem;" data-feather="edit"></i> 
                      </a>
                      <a href="#" onclick="borrarMesa({{$item->id}})" class="d-inline-block text-dark" title="Borrar">
                        <i style="height: 1.6rem; width: 1.6rem;" data-feather="trash"></i> 
                      </a>
                    </label>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    function copiarTextoAcceso(usuario) {
      var texto  = "";
        texto += "Hola, te comparto el link para administrar a tus invitados: www.reservasplusgt.com\n\nLos accesos son:\n*Usuario*: " + usuario + "\n*Contraseña*: 123";

      var sampleTextarea = document.createElement("textarea");
      document.body.appendChild(sampleTextarea);
      sampleTextarea.value = texto;
      sampleTextarea.select();
      document.execCommand("copy");
      document.body.removeChild(sampleTextarea);
    }

    $(document).ready(function(){
      $('#busqueda').keyup(function(){
        var valor_busqueda = $(this).val();
        valor_busqueda = valor_busqueda.toLowerCase();

        if (valor_busqueda != '') {
          $('div#mesa_contenedor').hide();
        } else {
          $('div#mesa_contenedor').show();
        }

         $("div#mesa_contenedor[rel*='" + valor_busqueda + "']").show();
      })
    })
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
