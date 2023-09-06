{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            ASIGNACIÓN DE MESEROS Y COBRADORES
          </h3>

          <h3 class="card-title">
            <label class="d-inline-block mx-1">Fecha:</label>
            <input type="date" class="form-control d-inline-block w-auto" id="fecha" name="fecha" value="{{$fecha}}" onchange="cambioDeFecha(this.value);" />
            </a>
          </h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-sm-4 border">
              <h1 class="d-block text-center">Cobrador</h1>
              <select class="w-100 fs-1" id="cobradores" rel="1" rel="cobradores" name="cobrador_1">
                @foreach($cobradores as $item)
                  <option value="{{$item->id}}" {{$item->id == $id_cobrador_1 ? 'selected' : ''}}>{{$item->name}}</option>
                @endforeach
              </select>

              <button class="btn btn-success d-block mt-1 m-auto" onclick="agregarMesero(1)">
                <i data-feather="plus"></i> Agregar
              </button>
              <hr>

              <h1 class="d-block text-center m-0">Meseros</h1>
              <div class="w-100" id="contenedor-meseros-1">
                @foreach($meseros_1 as $item)
                  <div class="row border-bottom py-1" id="mesero_{{$item->id}}">
                    <div class="col-9">
                        <label class="fs-1">{{$item->name}}</label>
                    </div>
                    <div class="col-3">
                      <button class="btn btn-danger w-100" onclick="borrarMesero({{$item->id}})">
                        <i data-feather="trash"></i>
                      </button>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            <div class="col-12 col-sm-4 border">
              <h1 class="d-block text-center">Cobrador</h1>
              <select class="w-100 fs-1" id="cobradores" rel="2" name="cobrador_2">
                @foreach($cobradores as $item)
                  <option value="{{$item->id}}" {{$item->id == $id_cobrador_2 ? 'selected' : ''}}>{{$item->name}}</option>
                @endforeach
              </select>

              <button class="btn btn-success d-block mt-1 m-auto" onclick="agregarMesero(2)">
                <i data-feather="plus"></i> Agregar
              </button>
              <hr>

              <h1 class="d-block text-center m-0">Meseros</h1>
              <div class="w-100" id="contenedor-meseros-2">
                @foreach($meseros_2 as $item)
                  <div class="row border-bottom py-1" id="mesero_{{$item->id}}">
                    <div class="col-9">
                        <label class="fs-1">{{$item->name}}</label>
                    </div>
                    <div class="col-3">
                      <button class="btn btn-danger w-100" onclick="borrarMesero({{$item->id}})">
                        <i data-feather="trash"></i>
                      </button>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
            
            <div class="col-12 col-sm-4 border">
              <h1 class="d-block text-center">Cobrador</h1>
              <select class="w-100 fs-1" id="cobradores" rel="3" name="cobrador_3">
                @foreach($cobradores as $item)
                  <option value="{{$item->id}}" {{$item->id == $id_cobrador_3 ? 'selected' : ''}}>{{$item->name}}</option>
                @endforeach
              </select>

              <button class="btn btn-success d-block mt-1 m-auto" onclick="agregarMesero(3)">
                <i data-feather="plus"></i> Agregar
              </button>
              <hr>

              <h1 class="d-block text-center m-0">Meseros</h1>
              <div class="w-100" id="contenedor-meseros-3">
                @foreach($meseros_3 as $item)
                  <div class="row border-bottom py-1" id="mesero_{{$item->id}}">
                    <div class="col-9">
                        <label class="fs-1">{{$item->name}}</label>
                    </div>
                    <div class="col-3">
                      <button class="btn btn-danger w-100" onclick="borrarMesero({{$item->id}})">
                        <i data-feather="trash"></i>
                      </button>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    function cambioDeFecha(fecha) {
      if (!isNaN(Number(new Date(fecha)))) {
        window.location = "/admin/asignacion/" + encodeURIComponent(fecha)
      }
    }

    function agregarMesero(columna) {
      Swal.fire({
        customClass: {
          confirmButton: 'btn btn-success fs-1',
          cancelButton: 'btn btn-secondary fs-1'
        },
        showCancelButton: true,
        confirmButtonText: 'Agregar',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h1 class="modal-title" id="verifyModalContent_title">SELECCIONAR MESERO</h1>
              </div>`,
        html:`
          <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
            <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
              <div class="modal-body">
                <div class="row">
                  <div class="col-12">
                    <select class="w-100 fs-1 h-100" id="meseros" name="meseros">
                      @foreach($meseros as $key => $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>`
      }).then(result => {
        if (result.isConfirmed) {
          var fecha       = $('#fecha').val();
          var id_mesero   = $('#meseros').val();
          var id_cobrador = $('select[name="cobrador_' + columna + '"]').val();
          var ruta     = "/admin/asignar_mesero/" + id_mesero + "/" + id_cobrador + "/" + columna + "/" + fecha
          $.ajax({
            type: "GET",
            url: ruta,
            dataType: "JSON",
            success: function(respuesta){
              html = `
                <div class="row border-bottom py-1" id="mesero_` + respuesta.id + `">
                  <div class="col-9">
                      <label class="fs-1">` + respuesta.nombre + `</label>
                  </div>
                  <div class="col-3">
                    <button class="btn btn-danger w-100" onclick="borrarMesero(` + respuesta.id + `)">
                      <i data-feather="trash"></i>
                    </button>
                  </div>
                </div>
              `;
              $('#contenedor-meseros-' + columna).append(html);
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
      $('select#cobradores').change(function(){
        var fecha       = $('#fecha').val();
        var id_cobrador = $(this).val();
        var columna     = $(this).attr('rel');
        var ruta        = "/admin/asignar_cobrador/" + id_cobrador + "/" + columna + "/" + fecha
        $.ajax({
          type: "GET",
          url: ruta,
          dataType: "JSON",
          success: function(respuesta){
            console.log('actualizado')
          }
        }).fail( function(jqXHR, textStatus, errorThrown) {
          Swal.fire({
            icon: 'error',
            title: 'ERROR: INTENTA DE NUEVO',
            timer: 2000
          });
        });
      });

      $('select#cobradores').each(function(){
        if ($(this).val() != 0) {
          var id = $(this).attr('rel');
          var value   = $(this).val();
          $('select#cobradores').each(function(){
            if ($(this).attr('rel') != id) {
              $(this).find('[value="' + value + '"]').remove();
            }
          });
        }
      });
    })
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
