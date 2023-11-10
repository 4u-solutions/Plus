{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form id="eventos_form" method="POST" action="/admin/agregar_mesa/{{$id_evento}}{{isset($data->id) ? '/'.$data->id : ''}}" onsubmit="event.preventDefault(); realizarAccion('eventos_form')">
          @csrf
          @method('PUT')

          <div class="card-header bg-dark">
            <h3 class="card-title w-100">
              @if (@$data->id)
                EDITAR RESERVACIÓN: {{$data->nombre}}
              @else
                CREAR RESERVACIÓN
              @endif
              
              <a href="{{route('admin.reservas.mesas', ['id_evento' => $id_evento])}}" class="btn btn-light w-100 m-auto d-block fs-3 mt-1"><i style="height: 1.8rem; width: 1.8rem;" data-feather="arrow-left"></i> REGRESAR </a>
            </h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="row">
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Nombre </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-4" id="nombre" name="nombre" type="text" value="{{@$data->nombre}}" />
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Fecha </label>
                  </div>
                  <div class="col-8 border py-1">
                    <select name="id_evento" id="id_evento" class="form-control select2 w-100 my-1 fs-4" >
                      @foreach ($fechas as $key => $item)
                        <option value="{{$item->id}}" {{$item->id == $id_evento ? 'selected' : ($item->id == @$data->id_evento ? 'selected' : '')}}>{{$item->nombre}}  ({{$item->fecha}})</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Área </label>
                  </div>
                  <div class="col-8 border py-1">
                    <select name="id_area" id="id_area" class="form-control select2 w-100 my-1 fs-4" >
                        <option value="1" {{@$data->id_area == 1 ? 'selected' : ''}}>Mesa</option>
                        <option value="2" {{@$data->id_area == 2 ? 'selected' : ''}}>Barra</option>
                    </select>
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Pax </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-4" id="pax" name="pax" type="text" value="{{@$data->pax}}" />
                  </div>
                  @if (@$data->id)
                    <div class="col-4 border bg-dark">
                      <label class="text-light py-1 fs-4"> Líder </label>
                    </div>
                    <div class="col-8 border">
                      <a href="#" onclick="agregarLider({{$data->id}})" class="d-block text-center py-1 text-dark fw-bold" title="Compartir link">
                        <i style="height: 1.8rem; width: 1.8rem;" data-feather="plus"></i> 
                      </a>
                      <div id="lideres-contenedor">
                        @foreach ($mesas_lideres as $key => $item)
                          <div class="row border-top p-1" id="mesa_lider_{{$item->id_lider}}">
                            <div class="col-8">
                              <label class="fs-4"> {{$item->name}} </label>
                            <input type="hidden" name="lideres_mesas[]" value="{{$item->id_lider}}" />
                            </div>
                            <div class="col-2">
                              <a href="#" onclick="borrarLider({{$item->id_lider}}, {{$data->id}})" class="d-inline-block text-dark" title="Borrar">
                                <i style="height: 1.8rem; width: 1.8rem;" data-feather="trash"></i> 
                              </a>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>

                    <div class="col-4 border bg-dark params_mesa" {!!@$data->id_area == 2 ? 'style="display: none;"' : ''!!}>
                      <label class="text-light py-1 fs-4"> Jefe de área #1</label>
                    </div>
                    <div class="col-8 border py-1 params_mesa" {!!@$data->id_area == 2 ? 'style="display: none;"' : ''!!}>
                      <select name="id_jefe_1" id="id_jefe_1" class="form-control select2 w-100 my-1 fs-4" >
                        <option value="0">Elegir jefe</option>
                        @foreach ($jefes as $key => $item)
                          <option value="{{$item->id}}" {!!$item->id == @$data->id_jefe_1 ? 'selected' : ''!!}>{{$item->name}}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-4 border bg-dark params_mesa" {!!@$data->id_area == 2 ? 'style="display: none;"' : ''!!}>
                      <label class="text-light py-1 fs-4"> Jefe de área #2</label>
                    </div>
                    <div class="col-8 border py-1 params_mesa" {!!@$data->id_area == 2 ? 'style="display: none;"' : ''!!}>
                      <select name="id_jefe_2" id="id_jefe_2" class="form-control select2 w-100 my-1 fs-4" >
                        <option value="0">Elegir jefe</option>
                        @foreach ($jefes as $key => $item)
                          <option value="{{$item->id}}" {!!$item->id == @$data->id_jefe_2 ? 'selected' : ''!!}>{{$item->name}}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-4 border bg-dark params_mesa" {!!@$data->id_area == 2 ? 'style="display: none;"' : ''!!}>
                      <label class="text-light py-1 fs-4"> Cobrador #1</label>
                    </div>
                    <div class="col-8 border py-1 params_mesa" {!!@$data->id_area == 2 ? 'style="display: none;"' : ''!!}>
                      <select name="id_cobrador_1" id="id_cobrador_1" class="form-control select2 w-100 my-1 fs-4" >
                        <option value="0">Elegir cobrador</option>
                        @foreach ($cobradores as $key => $item)
                          <option value="{{$item->id}}" {!!$item->id == @$data->id_cobrador_1 ? 'selected' : ''!!}>{{$item->name}}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-4 border bg-dark params_mesa" {!!@$data->id_area == 2 ? 'style="display: none;"' : ''!!}>
                      <label class="text-light py-1 fs-4"> Cobrador #2</label>
                    </div>
                    <div class="col-8 border py-1 params_mesa" {!!@$data->id_area == 2 ? 'style="display: none;"' : ''!!}>
                      <select name="id_cobrador_2" id="id_cobrador_2" class="form-control select2 w-100 my-1 fs-4" >
                        <option value="0">Elegir cobrador</option>
                        @foreach ($cobradores as $key => $item)
                          <option value="{{$item->id}}" {!!$item->id == @$data->id_cobrador_2 ? 'selected' : ''!!}>{{$item->name}}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-4 border bg-dark params_mesa" {!!@$data->id_area == 2 ? 'style="display: none;"' : ''!!}>
                      <label class="text-light py-1 fs-4"> Mesero #1 </label>
                    </div>
                    <div class="col-8 border py-1 params_mesa" {!!@$data->id_area == 2 ? 'style="display: none;"' : ''!!}>
                      <select name="id_mesero" id="id_mesero" class="form-control select2 w-100 my-1 fs-4" >
                        <option value="0">Elegir mesero</option>
                        @foreach ($meseros as $key => $item)
                          <option value="{{$item->id}}" {!!$item->id == @$data->id_mesero ? 'selected' : ''!!}>{{$item->name}}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-4 border bg-dark params_mesa" {!!@$data->id_area == 2 ? 'style="display: none;"' : ''!!}>
                      <label class="text-light py-1 fs-4"> Mesero #2 </label>
                    </div>
                    <div class="col-8 border py-1 params_mesa" {!!@$data->id_area == 2 ? 'style="display: none;"' : ''!!}>
                      <select name="id_mesero_2" id="id_mesero_2" class="form-control select2 w-100 my-1 fs-4" >
                        <option value="0">Elegir mesero</option>
                        @foreach ($meseros as $key => $item)
                          <option value="{{$item->id}}" {!!$item->id == @$data->id_mesero_2 ? 'selected' : ''!!}>{{$item->name}}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-4 border bg-dark params_mesa" {!!@$data->id_area == 2 ? 'style="display: none;"' : ''!!}>
                      <label class="text-light py-1 fs-4"> Asignar Mesa </label>
                    </div>
                    <div class="col-8 border params_mesa" {!!@$data->id_area == 2 ? 'style="display: none;"' : ''!!}>
                      <a href="#" onclick="asignarMesa({{$data->id}})" class="d-block text-center py-1 text-dark fw-bold" title="Compartir link">
                        <i style="height: 1.8rem; width: 1.8rem;" data-feather="plus"></i> 
                      </a>
                      <div id="mesas-contenedor">
                        @foreach ($sectores_mesas as $key => $item)
                          <div class="row border-top p-1" id="sector_mesa_{{$item->id}}">
                            <div class="col-8">
                              <label class="fs-4"> {{$item->nombre}}{{$item->no_mesa}} </label>
                            <input type="hidden" name="lideres_mesas[]" value="{{$item->id}}" />
                            </div>
                            <div class="col-2">
                              <a href="#" onclick="borrarSectorMesa({{$item->id}})" class="d-inline-block text-dark" title="Borrar">
                                <i style="height: 1.8rem; width: 1.8rem;" data-feather="trash"></i> 
                              </a>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  @endif
                  <div class="col-12 border">
                    <button class="btn btn-dark my-1 w-100 fs-3" style="">
                        Guardar
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
    var sectores_mesas = @php echo json_encode($json_sm); @endphp;

    function realizarAccion(formulario){
      $('#' + formulario).attr('onsubmit', '').submit();
    }

    function cambiarVersion(id_version) {
      $('div.tipos_mesa').hide()
    }

    function agregarLider(id_mesa) {
      var nombre_lider = '';
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-dark fs-1',
          cancelButton: 'btn btn-secondary fs-1'
        },
        reverseButtons: true,
        showCancelButton: true,
        confirmButtonText: 'Agregar',
        cancelButtonText: 'Cancelar',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h1 class="modal-title" id="verifyModalContent_title">SELECCIONAR LÍDER</h1>
              </div>`,
        html:`
          <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
            <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
              <div class="modal-body">
                <div class="row">
                  <div class="col-12" id="mostrar_lider">
                    <select class="w-100 fs-1 h-100 select2" id="lideres" name="lideres" onchange="agregarDatosLider(this.value)">
                      <option value="0">Seleccionar lider</option>
                      <option value="+">Nuevo Lider</option>
                      @foreach($lideres as $key => $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-12" id="no_mostrar_lider" style="display: none;">
                    <label class="fw-bold"> Nombre </label>
                    <input class="form-control w-100 text-center fs-3" name="nlider" id="nlider" type="text" onClick="this.select();" autocomplete="off" value="" />
                  </div>
                  <div class="col-12 mt-1" id="no_mostrar_lider" style="display: none;">
                    <label class="fw-bold"> Sexo </label>
                    <select name="sexo" id="sexo" class="form-control select2 w-100 my-1 fs-4" >
                      <option value="0">Mujer</option>
                      <option value="1">Hombre</option>
                    </select>
                  </div>
                  <div class="col-12 mt-1" id="no_mostrar_lider" style="display: none;">
                    <label class="fw-bold mt-1"> ¿Es mayor de 25 años? </label>
                    <input type="checkbox" class="form-check-input mt-1 ms-1" name="mayor_edad" id="mayor_edad" value="1">
                  </div>
                </div>
              </div>
            </div>
          </div>`,
        didOpen: function(){
          $('select.select2').select2();
          window.setTimeout(function () {
            $('#lideres').select2('open');
          }, 500);
          $(document).on('select2:open', () => {
              document.querySelector('.select2-search__field').focus();
          });
        }
      }).then(result => {
        if (result.isConfirmed) {
          var id_lider = $('#lideres').val();
          if (id_lider == '+') {
            var nlider = $('#nlider').val()
            var sexo   = $('#sexo').val()
            var mayor  = $('#mayor_edad').val()
            
            var ruta = "/admin/agregar_lider/0/" + encodeURIComponent(nlider) + "/" + sexo + "/" + mayor
          } else {
            var ruta = "/admin/agregar_lider_a_mesa/" + id_mesa + "/" + id_lider + "/0"
          }
          console.log(ruta)
          $.ajax({
              type: "GET",
              url: ruta,
              dataType: "JSON",
              success: function(respuesta){
                var html = `
                  <div class="row border-top p-1" id="mesa_lider_` + respuesta.id + `">
                    <div class="col-8">
                      <label class="fs-4"> ` + respuesta.nombre + ` </label>
                      <input type="hidden" name="lideres_mesas[]" value="` + respuesta.id + `" />
                    </div>
                    <div class="col-2">
                      <a href="#" onclick="borrarLider(` + respuesta.id + `, ` + id_mesa + `)" class="d-inline-block text-dark" title="Borrar">
                        <i style="height: 1.8rem; width: 1.8rem;" data-feather="trash"></i> 
                      </a>
                    </div>
                  </div>`;
                $('#lideres-contenedor').prepend(html);
                feather.replace();

                if (id_lider == '+') {
                  var ruta = "/admin/agregar_lider_a_mesa/" + id_mesa + "/" + respuesta.id + "/1"
                  console.log(ruta)
                  $.ajax({
                      type: "GET",
                      url: ruta,
                      dataType: "JSON",
                      success: function(respuesta){
                        console.log(respuesta);
                      }
                  }).fail( function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                      icon: 'error',
                      title: 'ERROR: INTENTA DE NUEVO',
                      timer: 2000
                    });
                  });
                }
              }
          }).fail( function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
              icon: 'error',
              title: 'ERROR: INTENTA DE NUEVO',
              timer: 2000
            });
          });
        }
      });
    }

    function asignarMesa(id_mesa) {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-dark fs-1',
          cancelButton: 'btn btn-secondary fs-1'
        },
        reverseButtons: true,
        showCancelButton: true,
        confirmButtonText: 'Agregar',
        cancelButtonText: 'Cancelar',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h1 class="modal-title" id="verifyModalContent_title">ASIGNAR MESA</h1>
              </div>`,
        html:`
          <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
            <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
              <div class="modal-body">
                <div class="row">
                  <div class="col-12" id="mostrar_lider">
                    <label class="fw-bold"> Sector </label>
                    <select class="w-100 fs-1 h-100 select2" id="id_sector" name="id_sector" onchange="cargarNoMesas()">
                      @foreach($sectores as $key => $item)
                        <option value="{{$item->id}}" data-cantidad="{{$item->cantidad_mesas}}">{{$item->nombre}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-12 mt-1">
                    <label class="fw-bold"> No. de mesa </label>
                    <select name="no_mesa" id="no_mesa" class="form-control select2 w-100 my-1 fs-4" >
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>`,
        didOpen: function(){
          var ruta = "/admin/cargar_mesas_asignadas/" + id_mesa
          $.ajax({
            type: "GET",
            url: ruta,
            dataType: "JSON",
            success: function(respuesta){
              sectores_mesas = JSON.parse(respuesta.json_sm)
            }
          }).fail( function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
              icon: 'error',
              title: 'ERROR: INTENTA DE NUEVO',
              timer: 2000
            });
          });

          $('select.select2').select2();
          cargarNoMesas()
        }
      }).then(result => {
        if (result.isConfirmed) {
          var id_sector = $('#id_sector').val();
          var no_mesa   = $('#no_mesa').val();
          var ruta      = "/admin/asignar_mesa_lider/" + id_mesa + '/' + id_sector + '/' + no_mesa
          $.ajax({
            type: "GET",
            url: ruta,
            dataType: "JSON",
            success: function(respuesta){
              var html = `
                <div class="row border-top p-1" id="sector_mesa_` + respuesta.id + `">
                  <div class="col-8">
                    <label class="fs-4"> ` + respuesta.nombre + no_mesa + ` </label>
                  </div>
                  <div class="col-2">
                    <a href="#" onclick="borrarSectorMesa(` + respuesta.id + `)" class="d-inline-block text-dark" title="Borrar">
                      <i style="height: 1.8rem; width: 1.8rem;" data-feather="trash"></i> 
                    </a>
                  </div>
                </div>`;
              $('#mesas-contenedor').append(html);
              feather.replace();
            }
          }).fail( function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
              icon: 'error',
              title: 'ERROR: INTENTA DE NUEVO',
              timer: 2000
            });
          });
        }
      });
    }

    function cargarNoMesas() {
      $('#no_mesa').empty();

      var no_mesas = parseInt($('option:selected', $('#id_sector')).attr('data-cantidad'));
      var sector   = $('option:selected', $('#id_sector')).text();
      for (i = 1; i <= no_mesas; i++) {
        if (sectores_mesas[sector] != undefined) {
          repetido = false;
          for (j = 1; j <= no_mesas; j++) {
            if (sectores_mesas[sector][(j-1)] == i) {
              repetido = true;
              break;
            } 
          }
          if (!repetido) {
            $("#no_mesa").append(new Option('Mesas número ' + i, i));
          }
        } else {
          $("#no_mesa").append(new Option('Mesas número ' + i, i));
        }
      }
    }

    function borrarSectorMesa(id) {
      var ruta = "/admin/borrar_mesas_asignadas/" + id
      $.ajax({
          type: "GET",
          url: ruta,
          dataType: "JSON",
          success: function(respuesta){
            $('#sector_mesa_' + id).slideUp().remove();
          }
      }).fail( function(jqXHR, textStatus, errorThrown) {
        Swal.fire({
          icon: 'error',
          title: 'ERROR: INTENTA DE NUEVO',
          timer: 2000
        });
      });
    }

    function agregarDatosLider(index_value) {
      if (index_value == '+') {
        $('div#mostrar_lider').hide();
        $('div#no_mostrar_lider').show();
      } else {
        $('div#no_mostrar_lider').hide();
        $('div#mostrar_lider').show();
      }
    }

    function borrarLider(id_lider, id_mesa) {
      var ruta = "/admin/borrar_lider_de_mesa/" + id_lider + '/' + id_mesa
      $.ajax({
          type: "GET",
          url: ruta,
          dataType: "JSON",
          success: function(respuesta){
            $('#mesa_lider_' + id_lider).slideUp().remove();
          }
      }).fail( function(jqXHR, textStatus, errorThrown) {
        Swal.fire({
          icon: 'error',
          title: 'ERROR: INTENTA DE NUEVO',
          timer: 2000
        });
      });
    }

    $(document).ready(function(){
      $('select.select2').select2();

      $('#id_version, #ubicacion_area').change(function(){
        var id_version = $('#id_version').val();
        var id_area    = $('#ubicacion_area').val();

        $('div.tipos_mesa').hide();
        $('#m_v' + id_version + '_' + id_area).show();
      });

      $('#id_area').change(function(){
        if ($(this).val() == '2') {
          $('div.params_mesa').hide();
        } else {
          $('div.params_mesa').show();
        }
      })
    })
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
