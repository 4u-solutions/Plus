{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp

  <div class="row">
    <div class="col-sm-6" id="panel-principal">
      <div class="card">
        <div class="card-header bg-dark">
          <h3 class="card-title">
            SELECCIONAR PRODUCTOS
          </h3>

          <h3 class="card-title w-100">
              <input class="form-control w-100 mt-1 fs-3" id="busqueda" type="text" value="" placeholder="Buscar produto" />
          </h3>
        </div>
        <div class="card-body">
          <div class="row" id="contenedor-productos">
            @foreach ($data as $item)
              @if (($pedido[0]->saldo >= $item->precio && $pedido[0]->id_tipo == 1) || $pedido[0]->id_tipo >= 2)
                @if ($item->stock > 0)
                  <div class="col-6 border bg-{{$item->color}} shadow rounded" rel="{{$item->nombre}}" id="producto_contenedor">
                    <a href="#" title="{{$item->nombre}}" rel="{{$item->mixers}}" onclick="agregarBotellas({{$id_pedido}}, {{$item->id}}, 1, {{$item->precio}}, '{{$item->nombre}}')" class="d-block">
                      <div class="w-100 d-block text-center pt-1" style="height: 100px;">
                        <img src="{{asset('botellas/' . $item->id . '.png')}}" class="h-100" />
                      </div>
                      <div class="row align-items-center h-100 py-1 px-0 text-center">
                        <label class="fs-3 text-{{$item->color == 'light' ? 'dark' : ($item->color == 'white' ? 'dark' : 'light')}}">{{$item->nombre}}: Q. {{ number_format($item->precio, 2) }}</label>
                      </div>
                    </a>
                  </div>
                @endif
              @endif
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <!-- <div class="col-sm-6 panel-prinpal-izquierdo" id="panel-principal" style="position: fixed; width: 47%; right: 1%;"> -->
    <div class="col-sm-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            TOTALES
          </h3>
        </div>
        <div class="card-body" style="overflow-y: scroll;">
        <!-- <div class="card-body"> -->
          <div class="row">
            <div class="col-12 border shadow p-1 text-end">
              <div class="mb-1 overflow-hidden" id="totales">
                @php $cobrar  = 0; @endphp
                @php $total  = 0; @endphp
                @php $mixerGratis = 0; @endphp
                @foreach ($bdetalle as $item)
                  <div class="producto-contable row mt-1 border-bottom pb-1" id="detalle_{{$item->id}}">
                    <div class="col-6 text-start pe-0">
                      <label class="fs-5 d-block">{{$item->nombre}}</label>
                      <label class="fs-5 d-block">Cantidad: {{$item->cantidad}}</label>
                    </div>
                    <div class="col-3 ps-0">
                      <label class="d-block">&nbsp;</label>
                      <label style="font-size: 0.9rem !important;">Q. {{number_format($item->subtotal, 2)}}</label>
                    </div>
                    <div class="col-3 ps-0">
                      @if ($pedido[0]->id_estado == 2)
                        <a href="#" onclick="borrarBotella({{$item->id}}, '{{$item->subtotal}}', {{$item->mixers}}, 1);" class="btn btn-danger"><i data-feather="trash"></i></a>
                      @endif
                    </div>
                  </div>
                  @php $total  += $item->subtotal; @endphp
                  @php $cobrar += 1; @endphp
                  @php $mixerGratis += $item->mixers * $item->cantidad; @endphp
                @endforeach
              </div>

              <div class="mb-1 overflow-hidden" id="contenedor-mixers">
                <div class="row border-bottom pb-1">
                  <div class="col-12">
                    <label class="fs-5 text-start"> <b><span id="total-mixers"></span> Mixers gratis</b> </label>
                    <a href="#" id="btn-mixers-gratis" class="btn btn-secondary ms-1" style="display: none;">
                      <i data-feather="plus"></i> 
                    </a>
                  </div>
                </div>
                <div class="row px-1 border-bottom pb-1" id="mixers-gratis" style="display: none;">
                  @foreach ($mixers as $item)
                    <div class="col-6 border bg-dark shadow rounded text-center mt-1 p-1" onclick="agregarBotellas({{$id_pedido}}, {{$item->id}}, {{$item->id_tipo == 8 ? '0' : '1'}}, '{{$item->nombre}}')" style="cursor: pointer;">
                      <a href="#" class="text-light fs-2" title="{{$item->nombre}}" rel="{{$item->mixers}}">
                        {{$item->nombre}}
                      </a>
                    </div>
                  @endforeach
                </div>

                @foreach ($mdetalle as $item)
                  <div class="row mt-1 pb-1 border-bottom" id="detalle_{{$item->id}}">
                    <div class="col-9 text-start pe-0">
                      <label class="fs-5 d-block">{{$item->nombre}}</label>
                      <label class="fs-5 d-block">Cantidad: {{$item->cantidad}}</label>
                    </div>
                    <div class="col-3 ps-0">
                      @if ($pedido[0]->id_estado == 2)
                        <a href="#" onclick="borrarBotella({{$item->id}}, '{{$item->subtotal}}', {{$item->cantidad}}, 0);" class="btn btn-danger"><i data-feather="trash"></i></a>
                      @endif
                    </div>
                  </div>
                  @php $mixerGratis -= $item->cantidad; @endphp
                @endforeach
              </div>

              <div class="row mb-1">
                <div class="col-12">
                  <label class="fs-1 d-block" >Total: <b>Q. <span id="total">{{number_format($total, 2)}}</span></b></label>
                  <label class="fs-1 d-block {{$pedido[0]->id_tipo == 1 ? '' : 'd-none'}}" >Saldo Pull: <b>Q. <span id="saldo">{{number_format($pedido[0]->saldo, 2)}}</span></b></label>
                </div>
              </div>

                <div class="row d-none" id="boton-cobrar">
                  <a href="{{route('enviar_cobro', ['id_pedido' => $id_pedido])}}" class="btn btn-dark w-100 m-auto d-block fs-1"><i style="height: 1.8rem; width: 1.8rem;" data-feather="credit-card"></i> {{$pedido[0]->id_tipo == 2 ? 'COBRAR' : 'APROBAR'}} </a>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    var mixerGratis = {{$mixerGratis}}; 
    var saldoTotal  = parseInt({{$pedido[0]->saldo}})
    var prod_asoc   = '{"24":4}';
    $('#total-mixers').html(mixerGratis);

    function agregarBotellas(id_pedido, id_producto, contable, precio, nombre_producto) {
      console.log(nombre_producto)
      @if ($pedido[0]->id_tipo == 1)
        var saldo = parseFloat($('#saldo').html().replace(/,/g,''));
        if (saldo >= precio || mixerGratis > 0)  {
      @endif
        Swal.fire({
          customClass: {
            confirmButton: 'btn btn-success fs-1',
            cancelButton: 'btn btn-secondary fs-1'
          },
          showCancelButton: true,
          confirmButtonText: 'Agregar',
          cancelButtonText: 'Cancelar',
          title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                    <h1 class="modal-title" id="verifyModalContent_title">AGREGAR ` + nombre_producto + `</h1>
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
                      <input class="form-control h-100 w-100 text-center" id="cantidad-botellas" name="cantidad-botellas" type="text" value="1" style="font-size: 2rem;" onClick="this.select();" autocomplete="off" />
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
            ejectuarAgregar(id_pedido, id_producto, contable, cantidad, 0);
          }
        });
      @if ($pedido[0]->id_tipo == 1)
        } else {
          Swal.fire({
            icon: 'error',
            title: 'ALERTA: EL SALDO DISPONIBLE ES MENOR AL PRECIO DEL PRODUCTO',
            timer: 2000
          });
        }
      @endif
    }

    function ejectuarAgregar(id_pedido, id_producto, contable, cantidad, id_detalle) {
      var ruta = "/admin/cargar_productos/" + id_pedido + "/" + id_producto + "/" + cantidad + "/" + contable
      $.ajax({
        type: "GET",
        url: ruta,
        dataType: "JSON",
        success: function(respuesta){
          html = `
            <div class="detalle_` + id_detalle + ` row mt-1 border-bottom pb-1" id="detalle_` + respuesta.id_det + `" data-id="` + respuesta.id_det + `">
              <div class="col-6 text-start pe-0">
                <label class="fs-5 d-block">` + respuesta.nombre + `</label>
                <label class="fs-5 d-block">Cantidad: ` + respuesta.cantidad + `</label>
              </div>`;
          if (contable == '1') {
            html += `
              <div class="col-3 ps-0">
                <label class="d-block">&nbsp;</label>
                <label class="fs-5">Q. ` + respuesta.subtotal + `</label>
              </div>`;
          } else {
            html += '<div class="col-3 ps-0"></div>';
          }
          html += `
              <div class="col-3 ps-0">
                <a href="#" onclick="borrarBotella(` + respuesta.id_det + `, '` + respuesta.subtotal + `', ` + (contable == 0 ? (respuesta.id_tipo == 8 ? respuesta.cantidad : respuesta.mixers) : respuesta.mixers) + `, ` + respuesta.contable + `);" class="btn btn-danger"><i data-feather="trash"></i></a>
              </div>
            </div>`;

          if (contable == '1') {
            html = html.replace('0)', '1)');
            $('#totales').prepend(html);

            var totales = parseFloat($('#total').html().replace(/,/g,''));
            totales = totales + parseFloat(respuesta.subtotal.replace(/,/g,''));
            $('#total').html(numberWithCommas(totales.toFixed(2)))

            var saldo = saldoTotal - totales;
            $('#saldo').html(numberWithCommas(saldo.toFixed(2)))

            if (respuesta.mixers > 0) {
              mixerGratis += parseInt(respuesta.mixers) * cantidad;
              $('#total-mixers').html(mixerGratis);

              prod_asoc = respuesta.prod_asoc;
              prod_asoc = JSON.parse(prod_asoc);
              for (i = 0; i <= (prod_asoc.length - 1); i++) {
                ejectuarAgregar(id_pedido, prod_asoc[i]['id'], 0, prod_asoc[i]['cnt'], respuesta.id_det);
              }
            }
          } else {
            $('#contenedor-mixers').append(html);

            if (mixerGratis > 0) {
              mixerGratis -= parseInt(respuesta.cantidad)
              $('#total-mixers').html(mixerGratis);
            }
            
            var totales = parseFloat($('#total').html().replace(/,/g,''));            
            if (totales > 0) {
              if (mixerGratis == 0) {
                $('div#boton-cobrar').removeClass('d-none').addClass('d-block');
                $('#btn-mixers-gratis').hide();
                $('#mixers-gratis').slideUp();
              } else {
                $('div#boton-cobrar').removeClass('d-block').addClass('d-none');
                $('#btn-mixers-gratis').removeAttr('style');
              }
            }
          }

          feather.replace();

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

    function borrarBotella(id_detalle, subtotal, mixers, contable) {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-success fs-1',
          cancelButton: 'btn btn-secondary fs-1'
        },
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Borrar',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h1 class="modal-title" id="verifyModalContent_title">BORRAR PRODUCTO</h1>
              </div>`,
        text: '¿Está seguro de borrar el producto?'
      }).then(result => {
        if (result.isConfirmed) {
          ejecutarBorrar(id_detalle, subtotal, mixers, contable);
        }
      });
    }

    function ejecutarBorrar(id_detalle, subtotal, mixers, contable) {
      var ruta     = "/admin/borrar_productos/" + id_detalle
      $.ajax({
          type: "GET",
          url: ruta,
          dataType: "JSON",
          success: function(respuesta){
            if (respuesta) {
              $('#detalle_' + id_detalle).slideUp(function(){
                $(this).remove();
                if ($('div.detalle_' + id_detalle).length) {
                  var childIndex = $('div.detalle_' + id_detalle).attr('data-id');
                  setTimeout(function(){
                    ejecutarBorrar(childIndex, 0, 0, 0)
                  }, 50)
                }

                var totales = parseFloat($('#total').html().replace(/,/g,''));

                if (contable) {
                  totales = totales - parseFloat(subtotal.replace(/,/g,''));
                  $('#total').html(numberWithCommas(totales.toFixed(2)));

                  var saldo = saldoTotal - totales;
                  $('#saldo').html(numberWithCommas(saldo.toFixed(2))) 
                } else {
                  mixerGratis += parseInt(mixers)
                  $('#total-mixers').html(mixerGratis);
                  $('#btn-mixers-gratis').removeAttr('style');
                }
                
                if (totales > 0) {
                  if (mixerGratis == 0) {
                    $('div#boton-cobrar').removeClass('d-none').addClass('d-block');
                  } else {
                    $('div#boton-cobrar').removeClass('d-block').addClass('d-none');
                  }
                } else {
                  $('div#boton-cobrar').removeClass('d-block').addClass('d-none');
                }
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

      $('#busqueda').keyup(function(){
        var valor_busqueda = $(this).val();
        valor_busqueda = valor_busqueda.toLowerCase();

        if (valor_busqueda != '') {
          $('div#producto_contenedor').hide();
        } else {
          $('div#producto_contenedor').show();
        }

         $("div#producto_contenedor[rel*='" + valor_busqueda + "']").show();
      })

      $('#btn-mixers-gratis').click(function(){
        $('#mixers-gratis').toggle();
      })

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

      if (mixerGratis > 0) {
        $('div#boton-cobrar').removeClass('d-block').addClass('d-none');
      } else {
        $('div#boton-cobrar').removeClass('d-none').addClass('d-block');
      }
    })

    // var ancho = $(window).width();
    // var alto  = $('body').innerHeight();
    // var panel = $('.panel-prinpal-izquierdo').position()
    // alto = alto - (panel.top + 25 + ($('.panel-prinpal-izquierdo .card-header').innerHeight()));
    // $('.panel-prinpal-izquierdo .card-body').css('height', alto + 'px')
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
