{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp

@php $public_path = (strpos(getcwd(), 'themanorgt') ? getcwd() :  (substr(getcwd(), 0, strrpos(getcwd(), '/')) . '/public')) . '/'; @endphp

  <div class="row">
    <div class="col-md-12">
      <div class="card">
          <div class="card-header bg-dark">
            <h3 class="card-title">
              MI EQUIPO
            </h3>

            <h3 class="card-title w-100">
                <input class="form-control w-100 mt-1 fs-3" id="busqueda" type="text" value="" placeholder="Buscar miembro" />
            </h3>

            <!--
            <h3 class="card-title w-100 pt-1">
              <select name="filtro" id="filtro" class="form-control select2 w-100\">
                <option value="0">Todos</option>
                <option value="acreditado-1">Acreditado</option>
                <option value="acreditado-0">No creditado</option>
                <option value="radio_1-1">Radio entregado</option>
                <option value="radio_1-0">Radio no entregado</option>
                <option value="radio_2-1">Radio recibido</option>
                <option value="radio_2-0">Radio no recibido</option>
                <option value="hfree_1-1">Hans free entregado</option>
                <option value="hfree_1-0">Hans free no entregado</option>
                <option value="hfree_2-1">Hans free recibido</option>
                <option value="hfree_2-0">Hans free no recibido</option>
                <option value="uniforme_1-1">Uniforme entregado</option>
                <option value="uniforme_1-0">Uniforme no entregado</option>
                <option value="uniforme_2-1">Uniforme recibido</option>
                <option value="uniforme_2-0">Uniforme no recibido</option>
                <option value="disfraz_1-1">Disfraz entregado</option>
                <option value="disfraz_1-0">Disfraz no entregado</option>
                <option value="disfraz_2-1">Disfraz recibido</option>
                <option value="disfraz_2-0">Disfraz no recibido</option>
              </select>
            </h3>
            -->
          </div>

          @php $foto_ancho  = 17.5; @endphp
          @php $campo_ancho = 7.50; @endphp
          <div class="card-body">
            <div class="row">
              <div class="col-12" style="overflow: hidden; overflow-x: scroll;">
                <div style="width: 450% !important;">
                <div class="row">
                  <div class="col-1 border bg-dark" style="width: {{$foto_ancho}}% !important;">
                    <label class="text-light py-1 fs-3"> Nombre </label>
                  </div>
                  <div class="col-1 border bg-dark" style="width: {{$campo_ancho}}% !important;">
                    <label class="text-light py-1 fs-3"> Asistió </label>
                  </div>
                  <div class="col-1 border bg-dark" style="width: {{$campo_ancho}}% !important;">
                    <label class="text-light py-1 fs-3"> Radio Entrega </label>
                  </div>
                  <div class="col-1 border bg-dark" style="width: {{$campo_ancho}}% !important;">
                    <label class="text-light py-1 fs-3"> Hans Free Entrega </label>
                  </div>
                  <div class="col-1 border bg-dark" style="width: {{$campo_ancho}}% !important;">
                    <label class="text-light py-1 fs-3"> Playera Entrega </label>
                  </div>
                  <div class="col-1 border bg-dark" style="width: {{$campo_ancho}}% !important;">
                    <label class="text-light py-1 fs-3"> Disfraz Entrega </label>
                  </div>
                  <div class="col-1 border bg-dark" style="width: {{$campo_ancho}}% !important;">
                    <label class="text-light py-1 fs-3"> Sticker Entrega </label>
                  </div>
                  <div class="col-1 border bg-dark" style="width: {{$campo_ancho}}% !important;">
                    <label class="text-light py-1 fs-3"> Radio Recibido </label>
                  </div>
                  <div class="col-1 border bg-dark" style="width: {{$campo_ancho}}% !important;">
                    <label class="text-light py-1 fs-3"> Hans Free Recibido </label>
                  </div>
                  <div class="col-1 border bg-dark" style="width: {{$campo_ancho}}% !important;">
                    <label class="text-light py-1 fs-3"> Playera Recibido </label>
                  </div>
                  <div class="col-1 border bg-dark" style="width: {{$campo_ancho}}% !important;">
                    <label class="text-light py-1 fs-3"> Disfraz Recibido </label>
                  </div>
                  <div class="col-1 border bg-dark" style="width: {{$campo_ancho}}% !important;">
                    <label class="text-light py-1 fs-3"> Sticker Recibido </label>
                  </div>
                </div>
                @foreach ($acreditaciones as $key => $item)
                  <div class="row invitado_contenedor" rel="{{$item->name}} {{$item->nombre_sin_acento}}" id="colaborador_{{$item->id}}">
                    <div class="col-1 border" style="width: {{$foto_ancho}}% !important;">
                      @if (File::exists($public_path . 'colaboradores/' . $item->id . '.jpg'))
                        <img src="{{asset('colaboradores/' . $item->id . '.jpg')}}" class="w-100">
                      @endif
                    </div>
                    <div class="col-1 border form-check text-center px-1" style="width: {{$campo_ancho}}% !important;">
                      <input type="checkbox" style="border: 2px solid #000; box-shadow: 2px 2px black;" class="form-check-input float-none mt-1 ms-0 {{$item->acreditado ? '' : ''}}" name="equipo_check" id="equipo_check" rel="acreditado" data-invitado="{{$item->id}}" value="1" {{$item->acreditado ? 'checked disabled' : ''}}/>
                    </div>
                    <div class="col-1 border form-check text-center px-1" style="width: {{$campo_ancho}}% !important;">
                      <input type="checkbox" style="border: 2px solid #000; box-shadow: 2px 2px black;" class="form-check-input float-none mt-1 ms-0 {{$item->radio_1 ? '' : ''}}" name="equipo_check" id="equipo_check" rel="radio_1" data-invitado="{{$item->id}}" value="1" {{$item->radio_1 ? 'checked disabled' : ''}}/>

                      <label class="text-dark fs-2 d-block mt-1"> No. Radio </label>
                      <select name="radio_no" id="radio_1_no_{{$item->id}}" rel="radio_1_no" data-invitado="{{$item->id}}" class="form-control select2 w-100 fs-4 text-center" >
                        @for ($i = 1; $i <= 210; $i++)
                          <option value="{{$i}}" {{$i == $item->radio_1_no ? 'selected' : ''}}>{{$i}}</option>
                        @endfor
                      </select>
                    </div>
                    <div class="col-1 border form-check text-center px-1" style="width: {{$campo_ancho}}% !important;">
                      <input type="checkbox" style="border: 2px solid #000; box-shadow: 2px 2px black;" class="form-check-input float-none mt-1 ms-0 {{$item->hfree_1 ? '' : ''}}" name="equipo_check" id="equipo_check" rel="hfree_1" data-invitado="{{$item->id}}" value="1"  {{$item->hfree_1 ? 'checked disabled' : ''}}/>

                      <label class="text-dark fs-2 d-block mt-1"> No. Radio </label>
                      <select name="radio_no" id="radio_1_no_{{$item->id}}" rel="hfree_1_no" data-invitado="{{$item->id}}" class="form-control select2 w-100 fs-4 text-center" >
                        @for ($i = 1; $i <= 210; $i++)
                          <option value="{{$i}}" {{$i == $item->hfree_1_no ? 'selected' : ''}}>{{$i}}</option>
                        @endfor
                      </select>
                    </div>
                    <div class="col-1 border form-check text-center px-1" style="width: {{$campo_ancho}}% !important;">
                      <input type="checkbox" style="border: 2px solid #000; box-shadow: 2px 2px black;" class="form-check-input float-none mt-1 ms-0 {{$item->uniforme_1 ? '' : ''}}" name="equipo_check" id="equipo_check" rel="uniforme_1" data-invitado="{{$item->id}}" value="1" {{$item->uniforme_1 ? 'checked disabled' : ''}}/>
                    </div>
                    <div class="col-1 border form-check text-center px-1" style="width: {{$campo_ancho}}% !important;">
                      <label class="text-dark fs-2 d-block" mt-1> Si </label>
                      <input type="radio" style="border: 2px solid #000; box-shadow: 2px 2px black;" class="form-check-input float-none ms-0 {{$item->disfraz_1 == 1 ? '' : ''}}" name="equipo_check_{{$item->id}}" id="equipo_check_{{$item->id}}" rel="disfraz_1" data-invitado="{{$item->id}}" value="1" {{$item->disfraz_1 == 1 ? 'checked' : ''}}/>

                      <label class="text-dark fs-2 d-block mt-1"> No </label>
                      <input type="radio" style="border: 2px solid #000; box-shadow: 2px 2px black;" class="form-check-input float-none ms-0 {{$item->disfraz_1 == 0 ? '' : ''}}" name="disfraz_check_{{$item->id}}" id="disfraz_check_{{$item->id}}" rel="disfraz_1" data-invitado="{{$item->id}}" value="0" {{$item->disfraz_1 == 0 ? 'checked' : ''}}/>
                    </div>
                    <div class="col-1 border form-check text-center px-1" style="width: {{$campo_ancho}}% !important;">
                      <input type="checkbox" style="border: 2px solid #000; box-shadow: 2px 2px black;" class="form-check-input float-none mt-1 ms-0 {{$item->sticker_1 ? '' : ''}}" name="equipo_check" id="equipo_check" rel="sticker_1" data-invitado="{{$item->id}}" value="1" {{$item->sticker_1 ? 'checked disabled' : ''}}/>
                    </div>
                    <div class="col-1 border form-check text-center px-1" style="width: {{$campo_ancho}}% !important;">
                      <input type="checkbox" style="border: 2px solid #000; box-shadow: 2px 2px black;" class="form-check-input float-none mt-1 ms-0 {{$item->radio_2 ? '' : ''}}" name="equipo_check" id="equipo_check" rel="radio_2" data-invitado="{{$item->id}}" value="1" {{$item->radio_2 ? 'checked disabled' : ''}}/>

                      <label class="text-dark fs-2 d-block mt-1"> No. Radio </label>
                      <select name="radio_no" id="radio_2_no_{{$item->id}}" rel="radio_2_no" data-invitado="{{$item->id}}" class="form-control select2 w-100 fs-4 text-center" >
                        @for ($i = 1; $i <= 210; $i++)
                          <option value="{{$i}}" {{$i == $item->radio_2_no ? 'selected' : ''}}>{{$i}}</option>
                        @endfor
                      </select>
                    </div>
                    <div class="col-1 border form-check text-center px-1" style="width: {{$campo_ancho}}% !important;">
                      <input type="checkbox" style="border: 2px solid #000; box-shadow: 2px 2px black;" class="form-check-input float-none mt-1 ms-0 {{$item->hfree_2 ? '' : ''}}" name="equipo_check" id="equipo_check" rel="hfree_2" data-invitado="{{$item->id}}" value="1"  {{$item->hfree_2 ? 'checked disabled' : ''}}/>

                      <label class="text-dark fs-2 d-block mt-1"> No. Radio </label>
                      <select name="radio_no" id="radio_1_no_{{$item->id}}" rel="hfree_2_no" data-invitado="{{$item->id}}" class="form-control select2 w-100 fs-4 text-center" >
                        @for ($i = 1; $i <= 210; $i++)
                          <option value="{{$i}}" {{$i == $item->hfree_2_no ? 'selected' : ''}}>{{$i}}</option>
                        @endfor
                      </select>
                    </div>
                    <div class="col-1 border form-check text-center px-1" style="width: {{$campo_ancho}}% !important;">
                      <input type="checkbox" style="border: 2px solid #000; box-shadow: 2px 2px black;" class="form-check-input float-none mt-1 ms-0 {{$item->uniforme_2 ? '' : ''}}" name="equipo_check" id="equipo_check" rel="uniforme_2" data-invitado="{{$item->id}}" value="1" {{$item->uniforme_2 ? 'checked disabled' : ''}}/>
                    </div>
                    <div class="col-1 border form-check text-center px-1" style="width: {{$campo_ancho}}% !important;">
                      <label class="text-dark fs-2 d-block" mt-1> Si </label>
                      <input type="radio" style="border: 2px solid #000; box-shadow: 2px 2px black;" class="form-check-input float-none ms-0 {{$item->disfraz_2 == 1 ? '' : ''}}" name="disfraz_check_{{$item->id}}" id="disfraz_check_{{$item->id}}" rel="disfraz_2" data-invitado="{{$item->id}}" value="1" {{$item->disfraz_2 == 1 ? 'checked' : ''}}/>

                      <label class="text-dark fs-2 d-block mt-1"> No </label>
                      <input type="radio" style="border: 2px solid #000; box-shadow: 2px 2px black;" class="form-check-input float-none ms-0 {{$item->disfraz_2 == 0 ? '' : ''}}" name="disfraz_check_{{$item->id}}" id="disfraz_check_{{$item->id}}" rel="disfraz_2" data-invitado="{{$item->id}}" value="0" {{$item->disfraz_2 == 0 ? 'checked' : ''}}/>
                    </div>
                    <div class="col-1 border form-check text-center px-1" style="width: {{$campo_ancho}}% !important;">
                      <input type="checkbox" style="border: 2px solid #000; box-shadow: 2px 2px black;" class="form-check-input float-none mt-1 ms-0 {{$item->sticker_2 ? '' : ''}}" name="equipo_check" id="equipo_check" rel="sticker_2" data-invitado="{{$item->id}}" value="1" {{$item->sticker_2 ? 'checked disabled' : ''}}/>
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
    $(document).ready(function(){
      $('select.select2').select2();

      $('#busqueda').keyup(function(){
        var valor_busqueda = $(this).val();
        valor_busqueda = valor_busqueda.toLowerCase();

        if (valor_busqueda != '') {
          $('div.invitado_contenedor').hide();
        } else {
          $('div.invitado_contenedor').show();
        }

        $("div.invitado_contenedor[rel*='" + valor_busqueda + "']").show();
      })

      $('input#equipo_check').click(function(){
        valor = $(this).is(':checked') ? 1 : 0;
        campo = $(this).attr('rel');
        idc   = $(this).attr('data-invitado');
        var ruta = "/admin/marcar_equipo_usado/" + idc + '/' + valor + '/' + campo;
        console.log(ruta)
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

      $('input[type="radio"]').click(function(){
        valor = $('input[name="' + $(this).attr('name') + '"]:checked').val();
        campo = $(this).attr('rel');
        idc   = $(this).attr('data-invitado');
        var ruta = "/admin/marcar_equipo_usado/" + idc + '/' + valor + '/' + campo;
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

      $('select[name="radio_no"]').change(function(){
        valor = $(this).val();
        campo = $(this).attr('rel');
        idc   = $(this).attr('data-invitado');
        var ruta = "/admin/marcar_equipo_usado/" + idc + '/' + valor + '/' + campo;
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
    })
  </script>

  <style type="text/css">
    .vertical-layout.vertical-menu-modern.menu-collapsed .navbar.floating-nav { left: 0 !important }
  </style>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
