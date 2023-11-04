{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form id="eventos_form" method="POST" action="/admin/agregar_venue{{isset($data->id) ? '/'.$data->id : ''}}" onsubmit="event.preventDefault(); realizarAccion('eventos_form')">
          @csrf
          @method('PUT')

          <div class="card-header bg-dark">
            <h3 class="card-title w-100">
              @if (@$data->id)
                @if ($data->nombre)
                  VENUE: {{$data->nombre}}
                @else
                  AGREGAR VENUE
                @endif
              @else
                AGREGAR VENUE
              @endif
              
              <a href="{{route('admin.reservas.venues')}}" class="btn btn-light w-100 m-auto d-block fs-1 mt-1"><i style="height: 1.8rem; width: 1.8rem;" data-feather="arrow-left"></i> REGRESAR </a>
            </h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-4 border bg-dark">
                <label class="text-light py-1 fs-4"> Nombre </label>
              </div>
              <div class="col-8 border">
                <input class="form-control my-1 fs-4" id="nombre" name="nombre" type="text" value="{{@$data->nombre}}" />
              </div>

              <div class="col-4 border bg-dark">
                <label class="text-light py-1 fs-4"> Capacidad Máxima </label>
              </div>
              <div class="col-8 border">
                <input class="form-control my-1 fs-4" id="max_pax" name="max_pax" type="text" value="{{@$data->max_pax}}" />
              </div>

              <div class="col-4 border bg-dark">
                <label class="text-light py-1 fs-4"> Link Waze </label>
              </div>
              <div class="col-8 border">
                <input class="form-control my-1 fs-4" id="link_waze" name="link_waze" type="text" value="{{@$data->link_waze}}" />
              </div>

              <div class="col-4 border bg-dark">
                <label class="text-light py-1 fs-4"> Áreas </label>
              </div>
              <div class="col-8 border">
                <a href="#" onclick="agregarArea({{@$data->id}})" class="d-block text-center py-1 text-dark fw-bold" title="Compartir link">
                  <i style="height: 1.8rem; width: 1.8rem;" data-feather="plus"></i> 
                </a>
                <div id="areas-contenedor">
                  @foreach ($venues_ubicaciones as $key => $item)
                    <div class="row border-top p-1" id="mesa_lider_{{$item->id}}">
                      <div class="col-12">
                        <label class="fs-4"> {{$item->nombre}} </label>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
              <div class="col-12 border">
                <button class="btn btn-dark my-1 w-100 fs-3" style="">
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
    function realizarAccion(formulario){
      $('#' + formulario).attr('onsubmit', '').submit();
    }

    function cambiarVersion(id_version) {
      console.log(id_version)
      $('div.tipos_mesa').hide()
    }

    function agregarArea(id_area) {
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
                  <h1 class="modal-title" id="verifyModalContent_title">INGRESAR INFORMACIÓN DEL ÁREA</h1>
                </div>`,
        html:`
          <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
            <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
              <div class="modal-body p-0">
                <div class="row">
                  <div class="col-12">
                    <label class="fw-bold"> Nombre </label>
                    <input class="form-control w-100 text-center fs-5 nombre" id="nombre" name="nombre" type="text" onClick="this.select();" autocomplete="off" value="" />
                  </div>
                  <div class="col-12 mt-1">
                    <label class="d-inline-block"> Tipo </label>
                    <select name="id_tipo" id="id_tipo" class="form-control select2 w-100 mb-1 fs-4" >
                      <option value="1">Mesa</option>
                      <option value="2">Barra</option>
                    </select>
                  </div>
                  <div class="col-12 mt-1">
                    <label class="fw-bold"> Máximo de áreas </label>
                    <input class="form-control w-100 text-center fs-5" id="max_ubaciones" name="max_ubaciones" type="number" onClick="this.select();" autocomplete="off" value="" />
                  </div>
                </div>
              </div>
            </div>
          </div>`,
        didOpen: () => {
          $('select.select2').select2();
        },
      }).then(result => {
        if (result.isConfirmed) {
          var ruta = "/admin/agregar_venue_ubicacion/" + id_area + "/" + $('#id_tipo').val() + "/" + $('#max_ubaciones').val() + "/" + encodeURIComponent($('.nombre').val())
          console.log(ruta)
          $.ajax({
              type: "GET",
              url: ruta,
              dataType: "JSON",
              success: function(respuesta){
                var html = `
                  <div class="row border-top p-1" id="venue_area_` + respuesta.id + `">
                    <div class="col-12">
                      <label class="fs-4"> ` + respuesta.nombre + ` </label>
                    </div>
                  </div>`;
                $('#areas-contenedor').prepend(html);
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

    $(document).ready(function(){
      $('select.select2').select2();

      $('#id_version, #ubicacion_area').change(function(){
        var id_version = $('#id_version').val();
        var id_area    = $('#ubicacion_area').val();

        $('div.tipos_mesa').hide();
        $('#m_v' + id_version + '_' + id_area).show();
      });

      $('#tipo').change(function(){
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
