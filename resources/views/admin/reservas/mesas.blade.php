{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
    <div class="card">
        <div class="card-header bg-dark">
          <h3 class="card-title w-100">
            RESERVASCIONES
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
            <a href="{{route('admin.reservas.reporte')}}" class="btn btn-light d-block m-auto d-block fs-3 mt-1"><i style="height: 1.6rem; width: 1.6rem;" data-feather="list"></i> REPORTES </a>
          </h3>

          <h3 class="card-title w-100">
            <a href="{{route('todos_los_invitados', ['id_evento' => $id_evento])}}" class="btn btn-light d-block m-auto d-block fs-3 mt-1"><i style="height: 1.6rem; width: 1.6rem;" data-feather="user"></i> TODOS LOS INVITADOS </a>
          </h3>

          <h3 class="card-title w-100">
            <a href="{{route('agregar_mesa', ['id_evento' => $id_evento])}}" class="btn btn-light d-block m-auto d-block fs-3 mt-1"><i style="height: 1.6rem; width: 1.6rem;" data-feather="user"></i> NUEVA RESERVACIÓN </a>
          </h3>

          <h3 class="card-title w-100">
              <input class="form-control w-100 mt-1 fs-3" id="busqueda" type="text" value="" placeholder="Buscar reservación" />
          </h3>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-12">
              @foreach ($data as $key => $item)
                <div class="row mb-1" id="mesa_contenedor" rel="{{$item->nombre_sin_acento}} ({{$item->evento}})">
                  <div class="col-12 border bg-dark">
                    <label class="text-light py-1 fs-4"> {{$item->id_area == 1 ? 'Mesa' : 'Barra'}}: {{$item->nombre}} ({{$item->evento}}) | Mesero: {{$item->mesero ?: 'Sin asignar'}}, Coordinador: {{$item->cobrador ?: 'Sin asignar'}}</label>
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Detalle </label>
                  </div>
                  <div class="col-8 border">
                    <label class="py-1 fs-4 d-block pb-0"> Pax: {{$item->pax}} </label>
                    <label class="fs-4 d-block pb-0"> En lista: {{$item->invitados ?: 0}} </label>
                    <label class="fs-4 d-block pb-0"> Duplicados: {{$item->duplicados ?: 0}} </label>
                    @if ($item->pagado)
                      <label class="fs-4 d-block pb-0"> Pagados: {{$item->pagados ?: 0}} </label>
                    @endif
                    <label class="fs-4 d-block pb-0"> 
                      @if (@$item->mujeres)
                        Mujeres: {{@$item->mujeres ?: 0}} ({{round((1 - ((@$item->invitados - $item->mujeres) / $item->invitados)) * 100, 2)}}%) 
                      @else
                        Mujeres: 0
                      @endif
                    </label>
                    <label class="mb-1 fs-4 d-block"> 
                      @if (@$item->hombres)
                        Hombres: {{$item->hombres ?: 0}} ({{round((1 - (($item->invitados - $item->hombres) / $item->invitados)) * 100, 2)}}%) 
                      @else
                        Hombres: 0
                      @endif
                    </label>
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Acciones </label>
                  </div>
                  <div class="col-8 border">
                    <label class="py-1 fs-4">
                      <a href="{{route('agregar_mesa', ['id_evento' => $id_evento, 'id' => $item->id])}}" title="Editar" class="d-inline-block text-dark">
                        <i style="height: 1.6rem; width: 1.6rem;" data-feather="edit"></i> 
                      </a>
                      <a href="#" onclick="borrarMesa({{$item->id}})" class="d-inline-block text-dark" title="Borrar">
                        <i style="height: 1.6rem; width: 1.6rem;" data-feather="trash"></i> 
                      </a>
                      <a href="{{route('detalle_invitados', ['id_mesa' => $item->id])}}" class="d-inline-block text-dark" title="Ver mesa">
                        <i style="height: 1.6rem; width: 1.6rem;" data-feather="eye"></i> 
                      </a>
                      <a href="#" onclick="compartirAcceso('{{$item->usersys}}')" class="d-inline-block text-dark" title="Link para lider" target="_blank">
                        <i style="height: 1.6rem; width: 1.6rem;" data-feather="share-2"></i> 
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
    function realizarAccion(formulario){
      $('#' + formulario).attr('onsubmit', '').submit();
    }

    function copiarTextoAcceso(usuario) {
      var texto  = "";
        texto += "Hola, le comparto el link para administrar a sus invitados: www.themanor.gt\n\nLos accesos son:\n*Usuario*: " + usuario + "\n*Contraseña*: 123";

      var sampleTextarea = document.createElement("textarea");
      document.body.appendChild(sampleTextarea);
      sampleTextarea.value = texto;
      sampleTextarea.select();
      document.execCommand("copy");
      document.body.removeChild(sampleTextarea);
    }

    function compartirAcceso(usuario) {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-dark fs-1',
        },
        confirmButtonText: 'COPIAR TEXTO',
        html:`
          <div class="modal-dialog">
            <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
              <div class="modal-body p-0">
                <div class="row">
                  <div class="col-12 p-0">
                    Hola, le comparto el link para administrar a sus invitados: www.themanor.gt<br><br>
                    Los accesos son:<br>
                    <b>Usuario</b>: ` + usuario + `<br>
                    <b>Contraseña</b>: 123
                  </div>
                </div>
              </div>
            </div>
          </div>`
      }).then(result => {
        copiarTextoAcceso(usuario)
      });
    }

    $(document).ready(function(){
      $('select.select2').select2();

      $('#eventos').change(function(){
        var fecha = $(this).val();
        window.location = "/admin/mesas/" + fecha
      })

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

  <style type="text/css">
    .selection { text-align: center; font-size: calc(1.275rem + 0.3vw) !important; text-transform: uppercase; }
    .select2-container--default .select2-selection--single { padding: 0.6rem !important; height: auto; }
  </style>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
