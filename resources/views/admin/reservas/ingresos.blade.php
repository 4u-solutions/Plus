  {{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
          <div class="card-header bg-dark">
            <h3 class="card-title">
              CONTROL DE INGRESOS
            </h3>

            <h3 class="card-title w-100">
              <div class="row">
                <div class="col-10 pd-1">
                  <input class="form-control w-100 mt-1 fs-3" id="busqueda" type="text" value="" placeholder="Buscar invitado" autocomplete="off" />
                </div>
                <div class="col-2 px-0">
                  <a href="#" id="limpiar_busqueda" class="btn btn-dark d-block m-auto d-block fs-3">
                    <i style="height: 3rem; width: 3rem;" data-feather="x-circle"></i>
                  </a>
                </div>
              </div>

              <div class="row mt-1">
                <div class="col-6">
                  <span class="text-light">En lista: {{$total_invitados}}</span>
                </div>
                <div class="col-6">
                  <span class="text-light">Pendientes: {{$pendientes_ingreso}}</span>
                </div>
                <div class="col-6">
                  <span class="text-light">% de ingreso: {{number_format(1 - (($total_invitados - $ingresados) / $total_invitados), 2)}}%</span>
                </div>
                <div class="col-6">
                  <span class="text-light">% pendiente: {{number_format(1 - (($total_invitados - $pendientes_ingreso) / $total_invitados), 2)}}%</span>
                </div>
              </div>
            </h3>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-12" id="busqueda_contenedor">
                @foreach ($data as $key => $item)
                  <div class="row invitado_contenedor {{$item->ingreso ? 'bg-success text-light' : ''}}" id="ingreso_{{$item->id_invitado}}">
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
                          <input type="checkbox" class="form-check-input" name="ingreso" id="ingreso" value="1" rel="{{$item->id_invitado}}" style="border: 2px solid #000; box-shadow: 2px 2px black; height: 2.5rem; width: 2.5rem;" {{$item->ingreso ? 'checked' : ''}}/>
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
    })
  </script>

  <style type="text/css">
    .vertical-layout.vertical-menu-modern.menu-collapsed .navbar.floating-nav { left: 0 !important }
  </style>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
