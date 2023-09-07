{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp

  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            SELECCIONAR PRODUCTOS
          </h3>
        </div>
        <div class="card-body">
          <div class="row" id="contenedor-productos">
            @foreach ($data as $item)
              @if (($pedido[0]->saldo >= $item->precio && $pedido[0]->id_tipo == 1) || $pedido[0]->id_tipo >= 2)
                @if ($item->stock > 0)
                  <div class="col-6 border bg-{{$item->color}} shadow rounded">
                    <a href="#" style="{{$pedido[0]->id_estado > 2 ? ($mesero->roleUS == 5 ? 'pointer-events:none;' : '') : ''}}" title="{{$item->nombre}}" rel="{{$item->mixers}}" onclick="agregarBotellas({{$id_pedido}}, {{$item->id}}, {{$item->id_tipo == 8 ? '0' : '1'}}, {{$item->precio}})">
                      <div class="row align-items-center h-100 py-1 px-0 text-center">
                        <label class="fs-2 text-{{$item->color == 'light' ? 'dark' : ($item->color == 'white' ? 'dark' : 'light')}}">{{$item->nombre}}</label>
                        <label class="fs-2 text-{{$item->color == 'light' ? 'dark' : ($item->color == 'white' ? 'dark' : 'light')}}">Q. {{ number_format($item->precio, 2) }}</label>
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

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            TOTALES
          </h3>
        </div>
        <!-- <div class="card-body" style="position: fixed; width: 45%; top: 15%;"> -->
        <div class="card-body">
          <div class="row">
            <div class="col-12 border shadow p-1 text-end">
              <div class="mb-1 overflow-hidden" id="totales">
                @php $cobrar  = 0; @endphp
                @php $total  = 0; @endphp
                @php $maxMixers = 0; @endphp
                @foreach ($bdetalle as $item)
                  <div class="row mt-1 border-bottom pb-1" id="detalle_{{$item->id}}" rel="contable" data-id_tipo="{{$item->id_tipo}}">
                    <div class="col-6 text-start pe-0">
                      <label class="fs-5 d-block">{{$item->nombre}}</label>
                      <label class="fs-5 d-block">Cantidad: {{$item->cantidad}}</label>
                    </div>
                    <div class="col-3 ps-0">
                      <label class="d-block">&nbsp;</label>
                      <label class="fs-5">Q. {{number_format($item->subtotal, 2)}}</label>
                    </div>
                    <div class="col-3 ps-0">
                      @if ($pedido[0]->id_estado == 2)
                        <a href="#" onclick="borrarBotella({{$item->id}}, '{{$item->subtotal}}', {{$item->mixers}}, 1);" class="btn btn-danger"><i data-feather="trash"></i></a>
                      @endif
                    </div>
                  </div>
                  @php $total  += $item->subtotal; @endphp
                  @php $maxMixers += $item->mixers * $item->cantidad; @endphp
                  @php $cobrar += 1; @endphp
                @endforeach
              </div>

              <div class="mb-1 overflow-hidden" id="contenedor-mixers">
                <div class="row border-bottom pb-1">
                  <div class="col-12">
                    <label class="fs-5 text-start"> <b><span id="total-mixers"></span> Mixers gratis</b> </label>
                    <a href="#" id="agregar-mixers-gratis" class="btn btn-secondary ms-1">
                      <i data-feather="plus"></i> 
                    </a>
                  </div>
                </div>
                <div class="row px-1 border-bottom pb-1" id="mixers-gratis" style="display: none;">
                  @foreach ($mixers as $item)
                    <div class="col-6 border bg-dark shadow rounded text-center mt-1 p-1">
                      <a href="#" class="text-light fs-2" title="{{$item->nombre}}" rel="{{$item->mixers}}" onclick="agregarBotellas({{$id_pedido}}, {{$item->id}}, {{$item->id_tipo == 8 ? '0' : '1'}}, {{$item->precio}})">
                        {{$item->nombre}}
                      </a>
                    </div>
                  @endforeach
                </div>

                @php $mixers_menos = 0; @endphp
                @foreach ($mdetalle as $item)
                  <div class="row mt-1" id="detalle_{{$item->id}}">
                    <div class="col-9 text-start pe-0 border-bottom">
                      <label class="fs-5 d-block">{{$item->nombre}}</label>
                      <label class="fs-5 d-block">Cantidad: {{$item->cantidad}}</label>
                    </div>
                    <div class="col-3 ps-0 border-bottom">
                      @if ($pedido[0]->id_estado == 2)
                        <a href="#" onclick="borrarBotella({{$item->id}}, '{{$item->subtotal}}', {{$item->cantidad}}, 0);" class="btn btn-danger"><i data-feather="trash"></i></a>
                      @endif
                    </div>
                  </div>
                  @php $mixers_menos += $item->cantidad; @endphp
                @endforeach
              </div>

              <div class="row mb-1">
                <div class="col-12">
                  <label class="fs-1 d-block border-top" >Total: <b>Q. <span id="total">{{number_format($total, 2)}}</span></b></label>
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
    var maxMixers  = {{$maxMixers - $mixers_menos}}; 
    var cntMixers  = 0;
    var saldoTotal = parseInt({{$pedido[0]->saldo}})
    var cntbListo  = false;
    $('#total-mixers').html(maxMixers);

    function agregarBotellas(id_pedido, id_producto, contable, precio) {
      @if ($pedido[0]->id_tipo == 1)
        var saldo = parseFloat($('#saldo').html().replace(/,/g,''));
        if (saldo >= precio || maxMixers > 0)  {
      @endif
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
            ejectuarAgregar(id_pedido, id_producto, contable, cantidad);
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

    function ejectuarAgregar(id_pedido, id_producto, contable, cantidad) {
      contable = maxMixers == 0 ? 1 : contable;
      var ruta     = "/admin/cargar_productos/" + id_pedido + "/" + id_producto + "/" + cantidad + "/" + maxMixers+ "/" + contable
      $.ajax({
        type: "GET",
        url: ruta,
        dataType: "JSON",
        success: function(respuesta){
          console.log(contable)
          html = `
            <div class="row" id="detalle_` + respuesta.id_det + `" rel="contable" data-id_tipo="` + respuesta.id_tipo + `" data-id_producto="` + id_producto + `" data-cantidad="` + cantidad + `" data-subtotal="` + respuesta.subtotal + `">
              <div class="col-6 text-start pe-0 border-bottom">
                <label class="fs-5 d-block">` + respuesta.nombre + `</label>
                <label class="fs-5 d-block">Cantidad: ` + respuesta.cantidad + `</label>
              </div>`;
          if (respuesta.contable == '1' || (maxMixers == 0 && respuesta.contable == '0')) {
            html += `
              <div class="col-3 ps-0 border-bottom">
                <label class="d-block">&nbsp;</label>
                <label class="fs-5">Q. ` + respuesta.subtotal + `</label>
              </div>`;
          } else {
            html += '<div class="col-3 ps-0 border-bottom"></div>';
          }
          html += `
              <div class="col-3 ps-0 border-bottom">
                <a href="#" onclick="borrarBotella(` + respuesta.id_det + `, '` + respuesta.subtotal + `', ` + (contable == 0 ? (respuesta.id_tipo == 8 ? respuesta.cantidad : respuesta.mixers) : respuesta.mixers) + `, ` + respuesta.contable + `);" class="btn btn-danger"><i data-feather="trash"></i></a>
              </div>
            </div>`;

          cntMixers += respuesta.mixers;

          if (respuesta.contable == '1' || (maxMixers == 0 && respuesta.contable == '0')) {
            html = html.replace('0)', '1)');
            $('#totales').prepend(html);

            var totales = parseFloat($('#total').html().replace(/,/g,''));
            totales = totales + parseFloat(respuesta.subtotal.replace(/,/g,''));
            $('#total').html(numberWithCommas(totales.toFixed(2)))

            var saldo = saldoTotal - totales;
            $('#saldo').html(numberWithCommas(saldo.toFixed(2)))

            if (respuesta.mixers) {
              maxMixers += parseInt(respuesta.mixers) * parseInt(respuesta.cantidad)
              $('#total-mixers').html(maxMixers);
              if (parseInt(respuesta.mixers) > 0) {
                $('div#boton-mixers, #contenedor-mixers').removeClass('d-none').addClass('d-block');
              }
            }

            if (totales > 0) {
              if (maxMixers == 0) {
                $('div#boton-cobrar').removeClass('d-none').addClass('d-block');
              } else {
                $('div#boton-cobrar').removeClass('d-block').addClass('d-block');
              }
            }

            mixExtra = 0;
            arrExtra = [];
            $('div[rel="contable"]').each(function(){
              if ($(this).attr('data-id_tipo') == '8') {
                id = $(this).attr('id');
                dataExtra = [];
                dataExtra['id'] = id.substring(8);
                dataExtra['id_producto'] = $(this).attr('data-id_producto');
                dataExtra['cantidad'] = $(this).attr('data-cantidad');
                dataExtra['subtotal'] = $(this).attr('data-subtotal');
                arrExtra.push(dataExtra);
                mixExtra = mixExtra + parseInt($(this).attr('data-cantidad'));
              }
            })

            if (maxMixers > 0) {
              // console.log('maxMixers: ' + maxMixers)
              // console.log('mixExtra: ' + mixExtra)
              // console.log('cntbListo: ' + cntbListo)
              if (maxMixers >= mixExtra) {
                for (i = 0; i <= (arrExtra.length-1); i++) {
                  if (!cntbListo) {
                    ejecutarBorrar(arrExtra[i].id, arrExtra[i].subtotal, arrExtra[i].cantidad, 1)
                    ejectuarAgregar(id_pedido, arrExtra[i].id_producto, 0, arrExtra[i].cantidad);
                  }
                }
              }
            }
          } else {
            $('#contenedor-mixers').append(html);

            if (maxMixers > 0) {
              LosMixers  = maxMixers
              maxMixers -= parseInt(respuesta.cantidad)
              $('#total-mixers').html(maxMixers);


              if (parseInt(respuesta.cantidad) > LosMixers) {
                paraVender = parseInt(respuesta.cantidad) - LosMixers;
                paraMixers = parseInt(respuesta.cantidad) - paraVender;


                if (!cntbListo) {
                  ejecutarBorrar(respuesta.id_det, respuesta.subtotal, respuesta.cantidad, 0);
                }

                if (paraVender > 0) {
                  if (!cntbListo) {
                    ejectuarAgregar(id_pedido, id_producto, 1, paraVender);
                    cntbListo = true;
                  }
                }
                if (paraMixers > 0) {
                  ejectuarAgregar(id_pedido, id_producto, 0, paraMixers);
                }
              }
            }
            
            var totales = parseFloat($('#total').html().replace(/,/g,''));            
            if (totales > 0) {
              if (maxMixers == 0) {
                $('div#boton-cobrar').removeClass('d-none').addClass('d-block');
              } else {
                $('div#boton-cobrar').removeClass('d-block').addClass('d-none');
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

                var totales = parseFloat($('#total').html().replace(/,/g,''));

                if (contable) {
                  totales = totales - parseFloat(subtotal.replace(/,/g,''));
                  $('#total').html(numberWithCommas(totales.toFixed(2)));

                  var saldo = saldoTotal - totales;
                  $('#saldo').html(numberWithCommas(saldo.toFixed(2))) 

                  maxMixers -= mixers
                  $('#total-mixers').html(maxMixers);
                  if (maxMixers <= 0) {
                    $('div#boton-mixers, #contenedor-mixers').removeClass('d-block').addClass('d-none');
                  }
                } else {
                  console.log('maxMixers: ' + maxMixers)
                  console.log('mixers: ' + mixers)
                  maxMixers += parseInt(mixers)
                  $('#total-mixers').html(maxMixers);
                  if (maxMixers <= 0) {
                    $('div#boton-mixers, #contenedor-mixers').removeClass('d-block').addClass('d-none');
                  }
                }

                console.log('totales: ' + totales)
                console.log('maxMixers: ' + maxMixers)
                
                if (totales > 0) {
                  if (maxMixers == 0) {
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

      $('#agregar-mixers-gratis').click(function(){
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

      if (maxMixers > 0) {
        $('div#boton-mixers, #contenedor-mixers').removeClass('d-none').addClass('d-block');
      } else {
        if ({{$maxMixers}} == 0) {
          $('div#boton-mixers, #contenedor-mixers').removeClass('d-block').addClass('d-none');
        }
      }
    })

    navigator.serviceWorker.register("sw.js");
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
