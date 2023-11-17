  {{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
          <div class="card-header bg-dark p-1">
            <h3 class="card-title w-100 d-block">
              CHECKPOINT

              <a href="#" id="mostrar_info" class="text-light ms-1 float-end fs-3 px-0">
                <i style="height: 3rem; width: 3rem;" data-feather="info"></i>
              </a>
              <a href="#" id="ingreso_sin_lista" class="text-light float-end fs-3 px-0">
                <i style="height: 3rem; width: 3rem;" data-feather="user-x"></i>
              </a>
            </h3>

            <h3 class="card-title w-100">
              <div class="row">
                <div class="col-10 pd-1">
                  <input class="form-control w-100 mt-1 fs-3" id="busqueda" type="text" value="" placeholder="Buscar invitado" autocomplete="off" />
                </div>
                <div class="col-2 px-0">
                  <a href="#" id="limpiar_busqueda" class="btn btn-dark d-block m-auto d-block fs-3 px-0">
                    <i style="height: 3rem; width: 3rem;" data-feather="x-circle"></i>
                  </a>
                </div>
              </div>
            </h3>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-12" id="busqueda_contenedor">
                @foreach ($data as $key => $item)
                  <div class="row invitado_contenedor {{$item->ingreso ? 'bg-success text-light' : ''}}" id="ingreso_{{$item->id}}">
                    <div class="col-12 border">
                      <div class="row">
                        <div class="col-10">
                          <label class="pt-1 fs-5 d-block"> 
                            {{$item->nombre}}
                          </label>
                          @if ($item->id_area == 1)
                            <label class="pb-1 fs-5 d-block"> Mesa: {{$item->mesa}} ({{$item->mesas}}) / {{$item->mesero}} </label>
                          @else
                            <label class="pb-1 fs-5 d-block"> Barra: {{$item->mesa}} </label>
                          @endif
                        </div>
                        <div class="col-2 form-check pt-2">
                          <input type="checkbox" class="form-check-input" name="ingreso" id="ingreso" value="1" rel="{{$item->id}}" style="border: 2px solid #000; box-shadow: 2px 2px black; height: 2.5rem; width: 2.5rem;" {{$item->ingreso ? 'checked' : ''}}/>
                        </div>
                      </div>
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
      $('#limpiar_busqueda').click(function(){
        $('#busqueda_contenedor').html('')
        $('#busqueda').val('').focus();
      })

      $('#busqueda').keyup(function(oEvent){
        var valor_busqueda = $(this).val();
        valor_busqueda = valor_busqueda.toLowerCase();
        if (valor_busqueda != '') {
          var key = event.which || event.keyCode || event.charCode;
          if (key == 8 || key == 46)  {
           return false;
          }


          var ruta = "/admin/auto_complete_ingreso/" + encodeURIComponent(valor_busqueda);
          console.log(ruta)
          $.ajax({
            type: "GET",
            url: ruta,
            dataType: "JSON",
            success: function(respuesta){
              html = '';
              $('#busqueda_contenedor').html('')
              $('#busqueda_contenedor').html(respuesta.html);
            }
          }).fail( function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
              icon: 'error',
              title: 'ERROR: INTENTA DE NUEVO',
              timer: 2000
            });
          });
        } else {
          $('#busqueda_contenedor').html('')
        }
      })

      $('body').on('click', 'input#ingreso', function(){
        id_invitado = $(this).attr('rel')
        if( $(this).is(':checked') ){
          valor = 1;
        } else {
          valor = 0;
        }

        var ruta = "/admin/marcar_ingreso/" + id_invitado + '/' + valor;

        if (valor) {
          $('#ingreso_' + id_invitado).addClass('bg-success text-light')
        } else {
          $('#ingreso_' + id_invitado).removeClass('bg-success text-light')
        }
        $.ajax({
          type: "GET",
          url: ruta,
          dataType: "JSON",
          success: function(respuesta){
          }
        }).fail( function(jqXHR, textStatus, errorThrown) {
          Swal.fire({
            icon: 'error',
            title: 'ERROR: INTENTA DE NUEVO',
            timer: 2000
          });
        });
      })

      $('#mostrar_info').click(function(){
        Swal.fire({
          customClass: {
            confirmButton: 'btn btn-dark fs-1',
          },
          confirmButtonText: 'Cerrar',
          title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                    <h1 class="modal-title" id="verifyModalContent_title">INFORMACIÓN DE CHECKPOINT</h1>
                </div>`,
          html:`
            <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
              <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-12 text-start">
                      <span class="text-dark fs-3">Total en lista: <label id="data_en_lista"></label></span>
                    </div>
                    <div class="col-12 text-start">
                      <span class="text-dark fs-3">Ingresaron en lista: <label id="data_ingresaron"></label></span>
                    </div>
                    <div class="col-12 text-start">
                      <span class="text-dark fs-3">Total pendientes: <label id="data_pendientes"></label></span>
                    </div>
                    <div class="col-12 text-start">
                      <span class="text-dark fs-3">Porcentaje de ingreso: <label id="porcentaje_ingreso"></label>%</span>
                    </div>
                    <div class="col-12 text-start mb-1">
                      <span class="text-lidark fs-3">Porcentaje pendiente: <label id="porcentaje_pendiente"></label>%</span>
                    </div>
                    <hr>
                    <div class="col-12 text-start mb-1">
                      <span class="text-lidark fs-3">Ingresos sin lista: <label id="ingresos_sin_lista"></label></span>
                    </div>
                    <hr>
                    <div class="col-12 text-start">
                      <b><span class="text-lidark fs-3">Total ingresos: <label id="total_ingresos"></label></span></b>
                    </div>
                  </div>
                </div>
              </div>
            </div>`,
          didOpen: () => {
            var ruta = '/admin/info_control_de_ingreso';
            $.ajax({
                type: "GET",
                url: ruta,
                dataType: "JSON",
                success: function(respuesta){
                  console.log(respuesta)
                  $('#data_en_lista').html(respuesta.total_invitados);
                  $('#data_ingresaron').html(respuesta.ingresados);
                  $('#data_pendientes').html(respuesta.pendientes_ingreso);
                  $('#porcentaje_ingreso').html(respuesta.porcentaje_ingreso);
                  $('#porcentaje_pendiente').html(respuesta.porcentaje_pendientes);
                  $('#ingresos_sin_lista').html(respuesta.sin_lista);
                  $('#total_ingresos').html(respuesta.sin_lista + respuesta.ingresados);
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
            var cantidad = parseInt($('#cantidad-botellas').val());
            ejectuarAgregar(id_pedido, id_producto, contable, cantidad, 0);
          }
        });
      });

      $('#ingreso_sin_lista').click(function(){
        Swal.fire({
          customClass: {
            confirmButton: 'btn btn-dark fs-1',
          },
          confirmButtonText: 'Cerrar',
          title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                    <h1 class="modal-title" id="verifyModalContent_title">MARCAR INGRESOS SIN LISTA</h1>
                </div>`,
          html:`
            <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
              <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-3">
                      <a href="#" id="cantidad-menos" class="btn btn-dark fs-1 text-dark" style="pointer-events: none;">-</a>
                    </div>
                    <div class="col-6">
                      <input class="form-control h-100 w-100 text-center" id="cantidad-ingresos" name="cantidad-ingresos" type="text" value="0" style="font-size: 2rem;" onClick="this.select();" autocomplete="off" />
                    </div>
                    <div class="col-3">
                      <a href="#" id="cantidad-mas" class="btn btn-dark fs-1" style="font-size: 4rem;">+</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>`
        }).then(result => {});
      });

      $('body').on('click', '#cantidad-mas', function(){
        cantidadBotellas = parseInt($('#cantidad-ingresos').val()) + 1;
        $('#cantidad-ingresos').val(cantidadBotellas)

        if (cantidadBotellas > 0) {
          $('#cantidad-menos').removeAttr('style');
        }

        var ruta = '/admin/ingreso_sin_lista/{{$evento->id}}';
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

        return false;
      });


      $('body').on('click', '#cantidad-menos', function(){
        cantidadBotellas = parseInt($('#cantidad-ingresos').val()) - 1;
        $('#cantidad-ingresos').val(cantidadBotellas)

        if (cantidadBotellas == 0) {
          $('#cantidad-menos').css('pointer-events', 'none');
        } else {
          $('#cantidad-menos').removeAttr('style');
        }

        return false;
      });
    })
  </script>

  <style type="text/css">
    .vertical-layout.vertical-menu-modern.menu-collapsed .navbar.floating-nav { left: 0 !important }
  </style>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
