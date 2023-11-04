{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header bg-dark">
          <h3 class="card-title">
            LISTADO DE INVITADOS
          </h3>

          <h3 class="card-title w-100">
            <form id="invitados_form" method="POST" action="{{route('todos_los_invitados', ['id_evento' => $id_evento])}}" onsubmit="event.preventDefault(); realizarAccion('invitados_form')">
              @csrf
              <input class="form-control w-100 my-1 fs-3 text-center" id="busqueda" type="text" name="busqueda" value="{{$busqueda}}" placeholder="Buscar invitado" />

              <label class="d-inline-block">Filtrar por:</label>
              <select name="filtro" id="filtro" class="form-control select2 w-100 fs-3 text-center">
                <option value="1" {{$filtro == 1 ? 'selected' : ''}}>Activos</option>
                <option value="2" {{$filtro == 2 ? 'selected' : ''}}>Inactivos</option>
                <option value="3" {{$filtro == 3 ? 'selected' : ''}}>Todos</option>
              </select>

              <button class="btn btn-light mt-1 w-100 fs-3" style="">
                  <i style="height: 1.6rem; width: 1.6rem;" data-feather="search"></i> BUSCAR INVITADOS
              </button>

              <a href="{{route('admin.reservas.mesas', ['id_evento' => $id_evento])}}" class="btn btn-light w-100 m-auto d-block fs-3 mt-1"><i style="height: 1.8rem; width: 1.8rem;" data-feather="arrow-left"></i> REGRESAR </a>
          </h3>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-12">
              @if (count($mujeres) > 0)
                <h1 class="d-block text-center">MUJERES</h1>
              @endif
              @foreach ($mujeres as $key => $item)
                @if ($item->mesa)
                  <div class="row mb-1 invitado_contenedor_{{$item->id}}" id="invitado_contenedor" rel="{{strtolower($item->nombre)}}">
                    <div class="col-12 border bg-dark">
                    <label class="text-light py-1 fs-4"> {{$item->nombre}}, {{$item->id_area == 1 ? 'Mesa' : 'Barra'}}: {{$item->mesa}} ({{$item->evento}})</label>
                    </div>

                    <div class="col-4 border bg-dark">
                      <label class="text-light py-1 fs-4"> Pagado </label>
                    </div>
                    <div class="col-2 border form-check">
                      <input type="radio" class="form-check-input ms-0 mt-1" rel="{{$item->id}}" name="pagado_{{$item->id}}" id="pagado_{{$item->id}}" value="1" {{$item->pagado ? 'checked' : ($item->cortesia ? '' : '')}} />
                    </div>
                    <div class="col-4 border bg-dark">
                      <label class="text-light py-1 fs-4"> Cortesía </label>
                    </div>
                    <div class="col-2 border form-check">
                      <input type="radio" class="form-check-input ms-0 mt-1" rel="{{$item->id}}" name="pagado_{{$item->id}}" id="cortesia_{{$item->id}}" value="2" {{$item->cortesia ? 'checked' : ($item->pagado ? '' : '')}} />
                    </div>

                    @if ($item->pull)
                      <div class="col-4 border bg-dark">
                        <label class="text-light py-1 fs-4"> {{$item->pull_pagado == 2 ? 'Aprobar p' : 'P'}}ull </label>
                      </div>
                      <div class="col-8 border form-check">
                        <input type="checkbox" class="form-check-input ms-0 mt-1" rel="{{$item->id}}" name="aprobar_pull" id="aprobar_pull" value="1" {{$item->pull_pagado == 1 ? 'checked' : ''}} />
                      </div>
                    @endif

                    <div class="col-4 border bg-dark">
                      <label class="text-light py-1 fs-4"> ¿Menor de edad? </label>
                    </div>
                    <div class="col-2 border form-check">
                      <input type="checkbox" class="form-check-input ms-1 mt-1" rel="{{$item->id}}" name="es_menor" id="es_menor" value="0" {{$item->es_menor ? 'checked enabled' : ''}} />
                    </div>

                    <div class="col-4 border bg-dark">
                      <label class="text-light py-1 fs-4"> Activo </label>
                    </div>
                    <div class="col-2 border form-check">
                      <input type="checkbox" class="form-check-input ms-1 mt-1" rel="{{$item->id}}" name="estado" id="estado" value="0" {{$item->estado ? 'checked' : ''}} />
                    </div>

                    <div class="col-4 border bg-dark">
                      <label class="text-light py-1 fs-4"> Acciones </label>
                    </div>
                    <div class="col-8 border">
                      <label class="py-1 fs-4">
                        <a href="#" onclick="modificarInvitado({{$item->id}})" title="Editar" class="d-inline-block text-dark">
                          <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit"></i> 
                        </a>

                        <a href="#" onclick="borrarInvitado({{$item->id}})" title="Borrar" class="d-inline-block text-dark">
                          <i style="height: 1.8rem; width: 1.8rem;" data-feather="trash"></i> 
                        </a>
                      </label>
                    </div>
                  </div>
                @endif
              @endforeach

              @if (count($hombres) > 0)
                <h1 class="d-block text-center mt-1">HOMBRES</h1>
              @endif
              @foreach ($hombres as $key => $item)
                <div class="row mb-1 invitado_contenedor_{{$item->id}}" id="invitado_contenedor" rel="{{strtolower($item->nombre)}}">
                  <div class="col-12 border bg-dark">
                    <label class="text-light py-1 fs-4" id="nombre_invitado_{{$item->id}}">  {{$item->nombre}}, {{$item->id_area == 1 ? 'Mesa' : 'Barra'}}: {{$item->mesa}} ({{$item->evento}})</label>
                  </div>

                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Pagado </label>
                  </div>
                  <div class="col-2 border form-check">
                    <input type="radio" class="form-check-input ms-0 mt-1" rel="{{$item->id}}" name="pagado_{{$item->id}}" id="pagado_{{$item->id}}" value="1" {{$item->pagado ? 'checked' : ($item->cortesia ? '' : '')}} />
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Cortesía </label>
                  </div>
                  <div class="col-2 border form-check">
                    <input type="radio" class="form-check-input ms-0 mt-1" rel="{{$item->id}}" name="pagado_{{$item->id}}" id="cortesia_{{$item->id}}" value="2" {{$item->cortesia ? 'checked' : ($item->pagado ? '' : '')}} />
                  </div>

                  @if ($item->pull)
                    <div class="col-4 border bg-dark">
                      <label class="text-light py-1 fs-4"> {{$item->pull_pagado == 2 ? 'Aprobar p' : 'P'}}ull </label>
                    </div>
                    <div class="col-8 border form-check">
                      <input type="checkbox" class="form-check-input ms-0 mt-1" rel="{{$item->id}}" name="aprobar_pull" id="aprobar_pull" value="1" {{$item->pull_pagado == 1 ? 'checked' : ''}} />
                    </div>
                  @endif

                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> ¿Menor de edad? </label>
                  </div>
                  <div class="col-2 border form-check">
                    <input type="checkbox" class="form-check-input ms-1 mt-1" rel="{{$item->id}}" name="es_menor" id="es_menor" value="0" {{$item->es_menor ? 'checked enabled' : ''}} />
                  </div>

                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Activo </label>
                  </div>
                  <div class="col-2 border form-check">
                    <input type="checkbox" class="form-check-input ms-1 mt-1" rel="{{$item->id}}" name="estado" id="estado" value="0" {{$item->estado ? 'checked' : ''}} />
                  </div>

                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Acciones </label>
                  </div>
                  <div class="col-8 border">
                    <label class="py-1 fs-4">
                      <a href="#" onclick="modificarInvitado({{$item->id}})" title="Editar" class="d-inline-block text-dark">
                        <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit"></i> 
                      </a>

                      <a href="#" onclick="borrarInvitado({{$item->id}})" title="Borrar" class="d-inline-block text-dark">
                        <i style="height: 1.8rem; width: 1.8rem;" data-feather="trash"></i> 
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

    function cambioDeFecha(fecha) {
      if (!isNaN(Number(new Date(fecha)))) {
        window.location = "/admin/eventos/" + encodeURIComponent(fecha)
      }
    }

    $(document).ready(function(){
      $('input[type="radio"]').click(function(){
        if ($('input[name="' + $(this).attr('name') + '"]:checked').val() == 1) {
          var pagado = 1;
          var cortesia = 0;
        } else {
          var pagado = 0;
          var cortesia = 1;
        }

        var id_invitado = $(this).attr('rel');
        var ruta        = "/admin/pago_invitado/" + id_invitado + "/" + pagado;
        console.log(ruta)
        $.ajax({
          type: "GET",
          url: ruta,
          dataType: "JSON",
          success: function(respuesta){
            console.log(respuesta)
          }
        }).fail( function(jqXHR, textStatus, errorThrown) {
          Swal.fire({
            icon: 'error',
            title: 'ERROR: INTENTA DE NUEVO',
            timer: 2000
          });
        });
      });

      $('input#aprobar_pull').click(function(){
        if ($(this).is(':checked')) {
          var pull_pagado = 1;
        } else {
          var pull_pagado = 0;
        }

        var id_invitado = $(this).attr('rel');
        var ruta        = "/admin/pull_pagado/" + id_invitado + "/" + pull_pagado;
        console.log(ruta)
        $.ajax({
          type: "GET",
          url: ruta,
          dataType: "JSON",
          success: function(respuesta){
            console.log(respuesta)
          }
        }).fail( function(jqXHR, textStatus, errorThrown) {
          Swal.fire({
            icon: 'error',
            title: 'ERROR: INTENTA DE NUEVO',
            timer: 2000
          });
        });
      });

      $('input#es_menor').click(function(){
        if ($(this).is(':checked')) {
          var es_menor = 1;
        } else {
          var es_menor = 0;
        }

        var id_invitado = $(this).attr('rel');
        var ruta = "/admin/es_menor/" + id_invitado + "/" + es_menor;
        console.log(ruta)
        $.ajax({
          type: "GET",
          url: ruta,
          dataType: "JSON",
          success: function(respuesta){
            console.log(respuesta)
          }
        }).fail( function(jqXHR, textStatus, errorThrown) {
          Swal.fire({
            icon: 'error',
            title: 'ERROR: INTENTA DE NUEVO',
            timer: 2000
          });
        });
      });

      $('input#estado').click(function(){
        if ($(this).is(':checked')) {
          var estado = 1;
        } else {
          var estado = 0;
        }

        var id_invitado = $(this).attr('rel');
        var ruta = "/admin/activar_invitado/" + id_invitado + "/" + estado;
        console.log(ruta)
        $.ajax({
          type: "GET",
          url: ruta,
          dataType: "JSON",
          success: function(respuesta){
            console.log(respuesta)
          }
        }).fail( function(jqXHR, textStatus, errorThrown) {
          Swal.fire({
            icon: 'error',
            title: 'ERROR: INTENTA DE NUEVO',
            timer: 2000
          });
        });
      });
    });

    function buscarInvitados() {
      var valor_busqueda = $('#busqueda').val();
      valor_busqueda = valor_busqueda.toLowerCase();

      if (valor_busqueda != '') {
        $('div#invitado_contenedor').hide();
      } else {
        $('div#invitado_contenedor').show();
      }

       $("div#invitado_contenedor[rel*='" + valor_busqueda + "']").show();
    }

    function modificarInvitado(id) {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-dark fs-1',
          cancelButton: 'btn btn-danger fs-1'
        },
        reverseButtons: true,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h1 class="modal-title" id="verifyModalContent_title">EDITAR INVITADO</h1>
              </div>`,
        html:`
          <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
            <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
              <div class="modal-body">
                <div class="row">
                  <div class="col-12">
                    <label class="fw-bold"> Nombre </label>
                    <input class="form-control w-100 text-center fs-3" id="nombre" name="nombre" type="text" onClick="this.select();" autocomplete="off" />
                  </div>
                </div>
              </div>
            </div>
          </div>`,
        didOpen: () => {
          var ruta = "/admin/invitado_info/" + id;
          $.ajax({
              type: "GET",
              url: ruta,
              dataType: "JSON",
              success: function(respuesta){
                $('#nombre').val(respuesta.nombre)
              }
          }).fail( function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
              icon: 'error',
              title: 'ERROR: INTENTA DE NUEVO',
              timer: 2000
            });
          });
        },
      }).then(result => {
        if (result.isConfirmed) {
          var nombre = $('#nombre').val();
          var ruta     = "/admin/invitado_actualizado/" + id + "/" + encodeURIComponent(nombre);
          $.ajax({
              type: "GET",
              url: ruta,
              dataType: "JSON",
              success: function(respuesta){
                console.log(respuesta)
                $('#nombre_invitado_' + respuesta.id).html(respuesta.nombre)
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

    function borrarInvitado(id_invitado) {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-dark fs-1',
          cancelButton: 'btn btn-secondary fs-1'
        },
        reverseButtons: true,
        showCancelButton: true,
        confirmButtonText: 'SI',
        cancelButtonText: 'NO',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h1 class="modal-title" id="verifyModalContent_title">¿BORRAR INVITADO?</h1>
              </div>`,
      }).then(result => {
        if (result.isConfirmed) {
          var ruta     = "/admin/borrar_invitado/" + id_invitado;
          $.ajax({
              type: "GET",
              url: ruta,
              dataType: "JSON",
              success: function(respuesta){
                console.log(respuesta)
                $('.invitado_contenedor_' + respuesta.id).remove();
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
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
