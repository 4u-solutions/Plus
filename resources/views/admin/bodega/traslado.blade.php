{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp

  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header bg-dark">
          <h3 class="card-title">
            SELECCIONAR PRODUCTOS
          </h3>
        </div>
        <div class="card-body">
          <div class="row" id="contenedor-productos">
            @foreach ($data as $item)
              @if ($item->stock > 0)
                <div class="col-6 border bg-{{$item->color}} shadow rounded">
                  <a href="javascript: return false;" rel="{{$item->id}}" title="{{$item->nombre}}" onclick="agregarBotellas({{$id_pedido}}, {{$item->id}})">
                    <div class="row align-items-center h-100 py-1 px-0 text-center">
                      <label class="fs-2 text-{{$item->color == 'light' ? 'dark' : ($item->color == 'white' ? 'dark' : 'light')}}">{{$item->nombre}}</label>
                    </div>
                  </a>
                </div>
              @endif
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            TOTALES
          </h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 border shadow p-1 text-end">
              <div class="mb-1 overflow-hidden" id="totales">
                @php $cobrar  = 0; @endphp
                @foreach ($detalle as $item)
                  <div class="row" id="detalle_{{$item->id}}">
                    <div class="col-10 text-start pe-0 border-bottom">
                      <label class="fs-5 d-block">{{$item->nombre}}</label>
                      <label class="fs-5 d-block">Cantidad: {{$item->cantidad}}</label>
                    </div>
                    <div class="col-2 ps-0 border-bottom">
                      <a href="javascript: return false;" onclick="borrarBotella({{$item->id}});" class="btn btn-danger"><i data-feather="trash"></i></a>
                    </div>
                  </div>
                  @php $cobrar += 1; @endphp
                @endforeach
              </div>

              <div class="row {{$cobrar ? 'd-block' : 'd-none'}}" id="boton-cobrar">
                <a href="{{route('enviar_traslado', ['id_pedido' => $id_pedido])}}" class="btn btn-dark w-100 m-auto d-block fs-1"><i style="height: 1.8rem; width: 1.8rem;" data-feather="check"></i> TRASLADAR </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">

    function agregarBotellas(id_pedido, id_producto) {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-success fs-1',
          cancelButton: 'btn btn-secondary fs-1'
        },
        showCancelButton: true,
        confirmButtonText: 'Agregar',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h1 class="modal-title" id="verifyModalContent_title">AGREGAR PRODUCTO</h1>
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
                    <input class="form-control h-100 w-100 text-center" id="cantidad-botellas" name="cantidad-botellas" type="text" value="1" style="font-size: 2rem;" />
                  </div>
                  <div class="col-3">
                    <a href="#" id="cantidad-mas" class="btn btn-dark fs-1" style="font-size: 4rem;">+</a>
                  </div>
                </div>
              </div>
            </div>
          </div>`
      }).then(result => {
        if (result.isConfirmed) {
          var cantidad = parseInt($('#cantidad-botellas').val());
          var ruta     = "/admin/cargar_traslado/" + id_pedido + "/" + id_producto + "/" + cantidad
          $.ajax({
              type: "GET",
              url: ruta,
              dataType: "JSON",
              success: function(respuesta){
                console.log(respuesta)
                html = `
                  <div class="row" id="detalle_` + respuesta.id_det + `">
                    <div class="col-10 text-start pe-0 border-bottom">
                      <label class="fs-5 d-block">` + respuesta.nombre + `</label>
                      <label class="fs-5 d-block">Cantidad: ` + respuesta.cantidad + `</label>
                    </div>
                    <div class="col-2 ps-0 border-bottom">
                      <a href="javascript: return false;" onclick="borrarBotella(` + respuesta.id_det + `, '` + respuesta.subtotal + `');" class="btn btn-danger"><i data-feather="trash"></i></a>
                    </div>
                  </div>`;

                $('#totales').prepend(html);
                $('div#boton-cobrar').removeClass('d-none').addClass('d-block');
                feather.replace();

                $('#contenedor-productos a').each(function(index, val){
                  var id_producto = $(this).attr('rel');
                  $(this).attr('onclick', 'agregarBotellas(' + respuesta.id_pedido + ', ' + id_producto + ');')
                })

                var ruta = "/admin/enviar_traslado/" + respuesta.id_pedido
                $('#boton-cobrar a').attr('href', ruta)

                if (!respuesta.guardado) {
                  Swal.fire({
                    icon: 'danger',
                    text: 'ERROR: INTENTA DE NUEVO',
                    timer: 2000
                  })
                };
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

    function borrarBotella(id_detalle, subtotal) {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-success fs-1',
          cancelButton: 'btn btn-secondary fs-1'
        },
        showCancelButton: true,
        confirmButtonText: 'Borrar',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h1 class="modal-title" id="verifyModalContent_title">BORRAR PRODUCTO</h1>
              </div>`,
        text: '¿Está seguro de borrar el producto?'
      }).then(result => {
        if (result.isConfirmed) {
          var ruta     = "/admin/borrar_productos/" + id_detalle
          $.ajax({
              type: "GET",
              url: ruta,
              dataType: "JSON",
              success: function(respuesta){
                if (respuesta) {
                  $('#detalle_' + id_detalle).slideUp(function(){
                    $(this).remove();
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

    function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    $(document).ready(function(){
      $('body').on('click', '#cantidad-mas', function(){
        cantidadBotellas = parseInt($('#cantidad-botellas').val()) + 1;
        $('#cantidad-botellas').val(cantidadBotellas)

        if (cantidadBotellas > 1) {
          $('#cantidad-menos').removeAttr('style');
        }

        return false;
      });

      $('body').on('click', '#cantidad-menos', function(){
        cantidadBotellas = parseInt($('#cantidad-botellas').val()) - 1;
        $('#cantidad-botellas').val(cantidadBotellas)

        if (cantidadBotellas == 1) {
          $('#cantidad-menos').css('pointer-events', 'none');
        } else {
          $('#cantidad-menos').removeAttr('style');
        }

        return false;
      });
    })
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
