{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-12 col-sm-6 col-md-6 col-lg-5 mx-auto">
      @if ($vacio)
        <div class="card mb-1">
          <h3 class="card-title w-100">
            <a href="{{route('admin.reservas.lista_invitados')}}" class="btn btn-dark d-block m-auto d-block fs-3 mt-0"><i style="height: 1.8rem; width: 1.8rem;" data-feather="arrow-left"></i> REGRESAR </a>
          </h3>
        </div>
      @endif

      <div class="card-body bg-white">
        <div class="row">
          @foreach ($eventos as $mes => $item_m)
            <!-- <h1 class="d-block text-center">{{strtoupper($array_mes[(int)$mes])}}</h1> -->
            @foreach ($item_m as $dia => $item_d)
              <div class="col-12 mb-1">
                @if ($item_d->id != 1)
                  @if (@$item_d->id_mesa)
                    <a href="{{route('admin.reservas.lista_invitados', ['id_mesa' => $item_d->id_mesa])}}">
                  @else
                    <a href="#" onclick="solicitarReserva('{{strtoupper($item_d->nombre)}}', '{{$array_mes[(int)$mes]}}')">
                  @endif
                @endif

                  @if(file_exists('eventos/' . substr($item_d->fecha, 5, 5) . '.jpg'))
                    <img src="{{asset('eventos/' . substr($item_d->fecha, 5, 5) . '.jpg?' . date('YmdHis'))}}" class="w-100">
                  @else
                    <h1 class="d-block text-center"> {{$item_d->nombre}} No existe</h1>
                  @endif
                @if ($item_d->id != 1)
                  </a>
                @endif
              </div>
            @endforeach
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    function solicitarReserva(fecha, mes) {
      Swal.fire({
        customClass: {
          cancelButton: 'btn btn-secondary fs-1',
          confirmButton: 'btn btn-dark fs-1',
        },
        reverseButtons: true,
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        cancelButtonText: 'Cancelar',
        title: `<div class="modal-header" style="padding: 0; margin: auto; border:none;">
                  <h1 class="modal-title" id="verifyModalContent_title">QUIERO RESERVAR PARA EL ` + fecha + `</h1>
              </div>`,
          html:`
            <div class="modal-dialog" role="document" style="margin: auto; max-width:700px;">
              <div class="modal-content" style="border-left:none; border-right: none; border-radius:0; margin:auto;">
                <div class="modal-body p-0">
                  <div class="row">
                    <div class="col-4"></div>
                    <div class="form-check d-inline-block m-0 p-0 col-3 text-start">
                      <label class="d-inline-block mt-1"> Mesa </label>
                      <input type="radio" class="form-check-input mt-1 d-inline-block" name="mesa" value="1" checked/>
                    </div>
                    <div class="form-check d-inline-block m-0 p-0 col-3 text-start">
                      <label class="d-inline-block mt-1"> Barra </label>
                      <input type="radio" class="form-check-input mt-1 d-inline-block" name="mesa" value="0"/>
                    </div>
                  </div>
                </div>
              </div>
            </div>`
      }).then(result => {
        if (result.isConfirmed) {
          url = "https://api.whatsapp.com/send?phone=50247406902&text=Hola Cesi quiero una mesa para el " + fecha + " de " + mes;
          window.open(url, "_blank");
        }
      });
    }
  </script>

  <style>
      .menu-toggle { display: none; }
      .navbar-container, .canvasjs-chart-credit, .header-navbar-shadow { display: none !important; }
      .header-navbar .row { width: 100% !important; }
      .header-navbar.floating-nav { position: relative; }
      .header-navbar .row { width: 100% !important; }
      .app-content.content { padding-top: 20px !important; }
      .vertical-layout.vertical-menu-modern.menu-collapsed .navbar.floating-nav { left: 0 !important }
      .vertical-layout.vertical-menu-modern.menu-collapsed .app-content, .vertical-layout.vertical-menu-modern.menu-collapsed .footer { margin-left: 0 !important; }
  </style>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
