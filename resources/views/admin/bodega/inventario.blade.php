{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form id="inventario_form" method="POST" action="/admin/inventario{{isset($data->id) ? '/'.$data->id : ''}}" onsubmit="event.preventDefault(); realizarAccion('inventario_form')">
          @csrf
          @if($edit)
              @method('PUT')
          @endif

          <input type="hidden" name="action" value="{{$action}}">
          <div class="card-header bg-dark">
            <h3 class="card-title">
              INVENTARIO DE PRODUCTOS
            </h3>

            <h3 class="card-title">
              <label class="d-inline-block mx-1">Fecha:</label>
              <input type="date" class="form-control d-inline-block w-auto" id="fecha" name="fecha" value="{{$fecha}}" onchange="cambioDeFecha(this.value);" />
              </a>
            </h3>
          </div>
          <div class="card-body">
            <div class="row" id="panel-alerta">
              <div class="col-12 bg-warning p-2 text-center">
                <h1>Gira el dispositivo para tener una mejor visualización</h1>
              </div>
            </div>
            <div class="row" id="panel-principal">
              <div class="col-6 col-sm-6 col-md-5">
                <div class="row">
                  <div class="col-8 col-sm-9 border bg-dark">
                    <label class="text-light py-1 fs-3"> Producto </label>
                  </div>
                  <div class="col-4 col-sm-3 border bg-dark">
                    <label class="text-light py-1 fs-3"> Inicial </label>
                  </div>
                  @foreach ($productos as $item)
                    @php $actual = ($item->cantidad_inicial ?: $item->inicial) + $item->recarga; @endphp
                    @php $final = $actual - $item->vendido; @endphp
                    <div class="col-8 col-sm-9 border {{$final == 0 ? 'bg-danger text-light' : ''}}">
                      <label class="fs-3 pt-1"> {{$item->nombre}} </label>
                    </div>
                    <div class="col-4 col-sm-3 border {{$final == 0 ? 'bg-danger text-light' : ''}}">
                      <input class="d-inline-block form-control my-1 text-center {{$final == 0 ? 'bg-danger text-light' : ''}}" id="inicial_{{$item->id}}" name="inicial_{{$item->id}}" type="text" value="{{$item->inicial ?: 0}}" disabled />
                      <input type="hidden" id="precio_{{$item->id}}" class="precio_producto" rel="{{$item->id}}" value="{{$item->precio ?: 0}}"/>
                    </div>
                  @endforeach

                  <div class="col-12 border">
                      <button class="btn btn-primary my-1 w-100" style="">
                          {{ @$edit ? 'Actualizar datos' : 'Guardar'}}
                      </button>
                  </div>
                </div>
              </div>

              <div class="col-6 col-sm-6 col-md-7" style="overflow-x: scroll;">
                <div class="row" style="width: 165%;" id="panel-derecho">
                  <div class="col-2 border bg-dark">
                    <label class="text-light py-1 fs-3"> Inicial físico </label>
                  </div>
                  <div class="col-2 border bg-dark">
                    <label class="text-light py-1 fs-3"> Recargas </label>
                  </div>
                  <div class="col-1 border bg-dark">
                    <label class="text-light py-1 fs-3"> Actual </label>
                  </div>
                  <div class="col-2 border bg-dark">
                    <label class="text-light py-1 fs-3"> Despachado </label>
                  </div>
                  <div class="col-1 border bg-dark">
                    <label class="text-light py-1 fs-3"> Final </label>
                  </div>
                  <div class="col-2 col-sm-2 border bg-dark">
                    <label class="text-light py-1 fs-3"> Final físico </label>
                  </div>
                  <div class="col-2 col-sm-2 border bg-dark">
                    <label class="text-light py-1 fs-3"> Total venta </label>
                  </div>
                  @foreach ($productos as $item)
                    @php $actual = ($item->cantidad_inicial ?: $item->inicial) + $item->recarga; @endphp
                    @php $final = $actual - $item->vendido; @endphp
                    <div class="col-2 border {{$final == 0 ? 'bg-danger text-light' : ''}}">
                      <input class="d-inline-block form-control my-1 text-center {{$item->inicial == $item->cantidad_inicial ? '' : 'bg-danger border-danger text-light'}}" rel="{{$item->id}}" id="inventario_inicial" name="inventario_inicial[{{$item->id}}][]" data-titulo="{{$item->nombre}}" type="text" value="{{$item->cantidad_inicial ?: 0}}" onClick="this.select();" autocomplete="off" />
                    </div>
                    <div class="col-2 border {{$final == 0 ? 'bg-danger text-light' : ''}}">
                      <div class="row">
                        <div class="col-6 p-0">
                          <input disabled="true" class="d-inline-block form-control my-1 ms-1 text-center" rel="{{$item->id}}" id="recarga_{{$item->id}}" name="recarga[{{$item->id}}][]" type="text" value="{{$item->recarga ?: 0}}" onClick="this.select();" />
                          <input type="hidden" id="recarga_{{$item->id}}" value="{{$item->recarga ?: 0}}"/>
                        </div>
                        <div class="col-6  p-0 my-1">
                          <button type="button" class="d-inline-block btn btn-success ms-2" onclick="recargaProducto({{$item->id}})">
                            <i data-feather="plus"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="col-1 border {{$final == 0 ? 'bg-danger text-light' : ''}}">
                      <input class="d-inline-block form-control my-1 text-center" id="actual_{{$item->id}}" name="actual_{{$item->id}}" type="text" value="{{($actual) ?: 0}}" disabled />
                    </div>
                    <div class="col-2 border {{$final == 0 ? 'bg-danger text-light' : ''}}">
                      <input class="d-inline-block form-control my-1 text-center" id="ventas_{{$item->id}}" name="ventas_{{$item->id}}" type="text" value="{{($item->vendido) ?: 0}}" disabled />
                    </div>
                    <div class="col-1 border {{$final == 0 ? 'bg-danger text-light' : ''}}">
                      <input class="d-inline-block form-control my-1 text-center" id="final_{{$item->id}}" name="final_{{$item->id}}" type="text" value="{{($final) ?: 0}}" disabled />
                    </div>
                    <div class="col-2 border {{$final == 0 ? 'bg-danger text-light' : ''}}">
                      <input class="form-control my-1 text-center {{($actual - $item->vendido) == $item->cantidad_final ? '' : 'bg-danger border-danger text-light'}}" rel="{{$item->id}}" id="inventario_final" name="inventario_final[{{$item->id}}][]" type="text" value="{{$item->cantidad_final ?: 0}}" data-titulo="{{$item->nombre}}" onClick="this.select();" autocomplete="off" />
                    </div>
                    <div class="col-2 border {{$final == 0 ? 'bg-danger text-light' : ''}}">
                      @php @$subtotal = ((isset($item->cantidad_final) ? $item->cantidad_final : (isset($final) ? $final : $actual)) * $item->precio) ?: 0; @endphp

                      @php @$subtotal = $item->cantidad_final ? (($item->cantidad_inicial - $item->cantidad_final) * $item->precio) : 0; @endphp
                      @php @$total    += $subtotal; @endphp
                      <input class="d-inline-block form-control my-1 text-center" id="tot_precio_{{$item->id}}" name="tot_precio_{{$item->id}}" type="text" value="Q. {{number_format($subtotal)}}" disabled />
                    </div>
                  @endforeach

                  <div class="col-2 border">
                    <a class="btn btn-primary my-1 w-100" href="#" onclick="CopiarConteoFisico(1, this)">Copiar</a>
                  </div>
                  <div class="col-6 border"></div>
                  <div class="col-2 border">
                    <a class="btn btn-primary my-1 w-100" href="#" onclick="CopiarConteoFisico(2, this)">Copiar</a>
                  </div>
                  <div class="col-2 border">
                    <h1 class="d-block text-center mt-1" id="precio_total">Q. {{number_format($total)}}</h1>
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
    function cambioDeFecha(fecha) {
      if (!isNaN(Number(new Date(fecha)))) {
        window.location = "/admin/inventario/" + encodeURIComponent(fecha)
      }
    }

    function realizarAccion(formulario){
      $('#' + formulario).attr('onsubmit', '').submit();
    }

    function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function recargaProducto(id_producto) {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-success fs-1',
          cancelButton: 'btn btn-secondary fs-1'
        },
        showCancelButton: true,
        confirmButtonText: 'Recargar',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h1 class="modal-title" id="verifyModalContent_title">RECARGAR PRODUCTO</h1>
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
                    <input class="form-control h-100 w-100 text-center" id="cantidad-botellas" name="cantidad-botellas" type="text" value="1" style="font-size: 2rem;" autocomplete="off" />
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
          var ruta     = "/admin/recarga_inventario/" + id_producto + "/" + cantidad + "/" + $('#fecha').val()
          $.ajax({
            type: "GET",
            url: ruta,
            dataType: "JSON",
            success: function(respuesta){
              if (respuesta.actualizado) {
                actual = parseInt($('#actual_' + id_producto).val()) + cantidad
                $('#recarga_' + id_producto).val(respuesta.recarga)
                $('#actual_' + id_producto).val(actual)

                final  = parseInt($('#actual_' + id_producto).val()) - parseInt($('#ventas_' + id_producto).val());
                $('#final_' + id_producto).val(final)
                
                total_vendido = 0;
                $('input.precio_producto').each(function(){
                  id_producto = $(this).attr('rel');
                  actual      = parseInt($('#actual_' + id_producto).val());
                  final       = parseInt($('#final_' + id_producto).val());
                  final_fis   = parseInt($('input[name="inventario_final[' + id_producto + '][]"]').val());
                  cantidad    = final_fis ? final_fis : (final ? final : actual);
                  precio      = parseInt($(this).val())

                  total_vendido += cantidad * precio;
                  $('#tot_precio_' + id_producto).val('Q. ' + numberWithCommas(cantidad * precio));
                })

                $('#precio_total').html('Q. ' + numberWithCommas(total_vendido))
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

    $(document).ready(function(){
      $('input#inventario_inicial').keyup(function() {
        var id = $(this).attr('rel');
        var inicial = parseInt($('#inicial_' + id).val());
        var final   = parseInt($(this).val());

        if (inicial != final) {
          $(this).addClass('bg-danger').addClass('border-danger').addClass('text-light');
        } else {
          $(this).removeClass('bg-danger').removeClass('border-danger').removeClass('text-light');
        }
      });

      $('input#inventario_final').keyup(function() {
        var id = $(this).attr('rel');
        var inicial = parseInt($('#final_' + id).val());
        var final   = parseInt($(this).val());

        if (inicial != final) {
          $(this).addClass('bg-danger').addClass('border-danger').addClass('text-light');
        } else {
          $(this).removeClass('bg-danger').removeClass('border-danger').removeClass('text-light');
        }
      });

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

      $(window).resize(function(){
        ancho = $(window).width();
        if (ancho < 425) {
          $('#panel-derecho').css('width', (ancho / 0.7) + '%')
          $('#panel-alerta').show();
          $('#panel-principal').hide();
        } else {
          $('#panel-derecho').css('width', (ancho / 2.5) + '%')
          $('#panel-alerta').hide();
          $('#panel-principal').show();
        }
      })
    });

    function CopiarConteoFisico(tipo, elemento) {
      $(elemento).html('Copiado...')

      var texto  = "";
      var id = tipo == 1 ? 'inventario_inicial' : 'inventario_final';
      $('input#' + id).each(function(){
        texto += $(this).attr('data-titulo') + ': ' + $(this).val() + ' \n';
      })

      var sampleTextarea = document.createElement("textarea");
      document.body.appendChild(sampleTextarea);
      sampleTextarea.value = texto;
      sampleTextarea.select();
      document.execCommand("copy");
      document.body.removeChild(sampleTextarea);

      setTimeout(function(){
        $(elemento).html('Copiar')
      }, 1000)
    }

    var ancho = $(window).width();
    if (ancho < 550) {
      $('#panel-derecho').css('width', (ancho / 0.7) + '%')
      $('#panel-alerta').show();
      $('#panel-principal').hide();
    } else {
      if (ancho > 1000) { 
        $('#panel-derecho').css('width', (ancho / 6) + '%')
      } else {
        $('#panel-derecho').css('width', (ancho / 2.5) + '%')
      }
      $('#panel-alerta').hide();
      $('#panel-principal').show();
    }
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
