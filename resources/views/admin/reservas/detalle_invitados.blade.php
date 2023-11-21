{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header bg-dark">
          <h3 class="card-title w-100">
            LISTADO DE INVITADOS MESA: {{$mesa->nombre}}

            <label class="text-light pt-1 fs-5 d-block"> Total invitados: {{$invitados}} </label>
            <label class="text-light fs-5 d-block"> Total pagados: {{$pagados}} </label>
            <label class="text-light fs-5 d-block"> Total no pagados: {{$no_pagados}} </label>
            <label class="text-light fs-5 d-block"> Total mujeres: {{$mujeres_total}} </label>
            <label class="text-light fs-5 d-block"> Total hombres: {{$hombres_total}} </label>
            <label class="text-light fs-5 d-block"> Total mujeres pagadas: {{$mujeres_pagado}} </label>
            <label class="text-light fs-5 d-block"> Total homres pagados: {{$hombres_pagado}} </label>

            <a href="{{route('admin.reservas.mesas', ['id_evento' => $id_evento])}}" class="btn btn-light w-100 m-auto d-block fs-3 mt-1"><i style="height: 1.8rem; width: 1.8rem;" data-feather="arrow-left"></i> REGRESAR </a>

            <a href="#" class="btn btn-light w-100 m-auto d-block fs-3 mt-1" onclick="cambiarInvitado(0, {{$mesa->id}})">
              <i style="height: 1.8rem; width: 1.8rem;" data-feather="plus"></i> AGREGAR INVITADO 
            </a>

            <input class="form-control w-100 mt-1 fs-3" id="busqueda" type="text" value="" placeholder="Buscar invitado" />

            <h3 class="card-title w-100 mt-1">
              <label class="d-inline-block">Filtrar por:</label>
              <select name="filtro" id="filtro" class="form-control select2 w-100 fs-3 text-center">
                <option value="1" {{$filtro == 1 ? 'selected' : ''}}>Activos</option>
                <option value="2" {{$filtro == 2 ? 'selected' : ''}}>Inactivos</option>
                <option value="3" {{$filtro == 3 ? 'selected' : ''}}>Todos</option>
              </select>
            </h3>
          </h3>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-12">
              @if (count($mujeres) > 0)
                <h1 class="d-block text-center">MUJERES</h1>
              @endif
              @foreach ($mujeres as $key => $item)
                <div class="row mb-1 invitado_contenedor_{{$item->id}}" id="invitado_contenedor" rel="{{strtolower($item->nombre)}}">
                  <div class="col-12 border bg-dark">
                    <label class="text-light py-1 fs-4" id="nombre_invitado_{{$item->id}}"> {{$item->nombre}} </label>
                  </div>

                  @if ($item->evento_pagado)
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
                  @endif

                  @if ($item->pull)
                    <div class="col-4 border bg-dark">
                      <label class="text-light py-1 fs-4"> {{$item->pull_pagado == 2 ? 'Aprobar p' : 'P'}}ull </label>
                    </div>
                    <div class="col-2 border form-check">
                      <input type="checkbox" class="form-check-input ms-0 mt-1" rel="{{$item->id}}" name="aprobar_pull" id="aprobar_pull" value="1" {{$item->pull_pagado == 1 ? 'checked' : ''}} />
                    </div>

                    <div class="col-6 border">
                      @if ($item->pull_pagado == 2)
                        <a href="#" onclick="verBoleta({{$item->id_pago}})" title="Ver boleta" class="d-inline-block text-dark mt-1">
                          <i style="height: 1.8rem; width: 1.8rem;" data-feather="image"></i> 
                        </a>
                      @endif
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
                      <a href="#" onclick="editarInvitado({{$item->id}})" title="Editar" class="d-inline-block text-dark">
                        <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit"></i> 
                      </a>

                      <a href="#" onclick="cambiarInvitado({{$item->id}}, {{$item->id_mesa}})" title="Cambiar invitado" class="d-inline-block text-dark">
                        <i style="height: 1.8rem; width: 1.8rem;" data-feather="repeat"></i> 
                      </a>

                      <a href="#" onclick="borrarInvitado({{$item->id}})" title="Borrar" class="d-inline-block text-dark">
                        <i style="height: 1.8rem; width: 1.8rem;" data-feather="trash"></i> 
                      </a>
                    </label>
                  </div>
                </div>
              @endforeach

              @if (count($mujeres) > 0)
                <h1 class="d-block text-center mt-1">HOMBRES</h1>
              @endif
              @foreach ($hombres as $key => $item)
                <div class="row mb-1 invitado_contenedor_{{$item->id}}" id="invitado_contenedor" rel="{{strtolower($item->nombre)}}">
                  <div class="col-12 border bg-dark">
                    <label class="text-light py-1 fs-4" id="nombre_invitado_{{$item->id}}"> {{$item->nombre}} </label>
                  </div>

                  @if ($item->evento_pagado)
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
                  @endif

                  @if ($item->pull)
                    <div class="col-4 border bg-dark">
                      <label class="text-light py-1 fs-4"> {{$item->pull_pagado == 2 ? 'Aprobar p' : 'P'}}ull </label>
                    </div>
                    <div class="col-2 border form-check">
                      <input type="checkbox" class="form-check-input ms-0 mt-1" rel="{{$item->id}}" name="aprobar_pull" id="aprobar_pull" value="1" {{$item->pull_pagado == 1 ? 'checked' : ''}} />
                    </div>

                    <div class="col-6 border">
                      @if ($item->pull_pagado == 2)
                        <a href="#" onclick="verBoleta({{$item->id_pago}})" title="Ver boleta" class="d-inline-block text-dark mt-1">
                          <i style="height: 1.8rem; width: 1.8rem;" data-feather="image"></i> 
                        </a>
                      @endif
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
                      <a href="#" onclick="editarInvitado({{$item->id}})" title="Editar" class="d-inline-block text-dark">
                        <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit"></i> 
                      </a>

                      <a href="#" onclick="cambiarInvitado({{$item->id}}, {{$item->id_mesa}})" title="Cambiar invitado" class="d-inline-block text-dark">
                        <i style="height: 1.8rem; width: 1.8rem;" data-feather="repeat"></i> 
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

      $('#busqueda').keyup(function(){
        var valor_busqueda = $(this).val();
        valor_busqueda = valor_busqueda.toLowerCase();

        if (valor_busqueda != '') {
          $('div#invitado_contenedor').hide();
        } else {
          $('div#invitado_contenedor').show();
        }

         $("div#invitado_contenedor[rel*='" + valor_busqueda + "']").show();
      })
    });

    function verBoleta(id_pago) {
      Swal.fire({
        customClass: {
          cancelButton: 'btn btn-dark fs-1'
        },
        cancelButtonText: 'Cerrar',
        html:`
          <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
            <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
              <div class="modal-body">
                <div class="row">
                  <div class="col-12">
                    <img src="{{asset('boleta-pago/` + id_pago + `.jpg')}}" class="w-100" />
                  </div>
                </div>
              </div>
            </div>
          </div>`
      });
    }

    function agregarInvitado(id_mesa) {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-dark fs-1',
          cancelButton: 'btn btn-secondary fs-1'
        },
        reverseButtons: true,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h1 class="modal-title" id="verifyModalContent_title">AGREGAR INVITADO</h1>
              </div>`,
        html:`
          <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
            <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
              <div class="modal-body">
                <div class="row">
                  <div class="col-12">
                    <label class="fw-bold"> Nombre </label>
                    <input class="form-control w-100 text-center fs-5" id="nombre" name="nombre" type="text" onClick="this.select();" autocomplete="off" />
                  </div>
                  <div class="col-12 pt-1">
                    <label class="fw-bold"> Sexo </label>
                    <select name="sexo" id="sexo" class="form-control select2 w-100 fs-4 text-center" >
                      <option value="0">Mujer</option>
                      <option value="1">Hombre</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>`
      }).then(result => {
        if (result.isConfirmed) {
          var nombre = $('#nombre').val();
          var sexo = $('#sexo').val();
          var ruta     = "/admin/agregar_invitado/" + id_mesa + "/" + encodeURIComponent(nombre) + "/" + sexo + "/1/1";
          $.ajax({
              type: "GET",
              url: ruta,
              dataType: "JSON",
              success: function(respuesta){
                location.reload();
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

    function editarInvitado(id) {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-dark fs-1',
          cancelButton: 'btn btn-secondary fs-1'
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
                  <div class="col-12 pt-1">
                    <label class="fw-bold"> Sexo </label>
                    <select name="sexo" id="sexo" class="form-control select2 w-100 fs-4 text-center" >
                      <option value="0">Mujer</option>
                      <option value="1">Hombre</option>
                    </select>
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
                $('#sexo').val(respuesta.sexo)
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
          var sexo = $('#sexo').val();
          var ruta     = "/admin/invitado_actualizado/" + id + "/" + encodeURIComponent(nombre) + '/' + sexo;
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

    function cambiarInvitado(id, id_mesa) {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-dark fs-1',
          cancelButton: 'btn btn-secondary fs-1'
        },
        reverseButtons: true,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h1 class="modal-title" id="verifyModalContent_title">` + (id == 0 ? 'AGREGAR INVITADO' : 'EDITAR INVITADO') + `</h1>
              </div>`,
        html:`
          <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
            <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
              <div class="modal-body">
                <div class="row">
                  <div class="col-12">
                    <label class="fw-bold"> Nombre </label>
                    <select name="id_invitado" id="id_invitado" class="form-control select2 w-100 fs-4 text-center" >
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>`,
        didOpen: () => {
          $('select.select2').select2({
            placeholder: 'Cambio de invitado por:',
            ajax: {
              url: '/admin/cargar_invitados/' + id + '/' + id_mesa,
              dataType: 'json',
              delay: 250,
              processResults: function (data) {
                return {
                  results:  $.map(data.data, function (item) {
                    return {
                      text: item.nombre + (item.telefono != null ?  (' / ' + item.telefono) : ''),
                      id: item.id
                    }
                  })
                };
              },
              cache: true
            }
          }).on("select2:select", function(e) { 
             if ($(this).val() == '+') {
              agregarInvitado(id_mesa)

             }
          });
        },
      }).then(result => {
        if (result.isConfirmed) {
          var id_invitado = $('#id_invitado').val();
          var ruta     = "/admin/cambio_invitado/" + id + "/" + id_invitado + "/" + id_mesa;
          $.ajax({
              type: "GET",
              url: ruta,
              dataType: "JSON",
              success: function(respuesta){
                location.reload();
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
