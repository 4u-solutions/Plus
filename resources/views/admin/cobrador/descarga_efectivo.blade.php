{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form id="enviar_pago_form" method="POST" action="/admin/descarga_efectivo/{{isset($data->id) ? '/'.$data->id : ''}}" onsubmit="event.preventDefault(); realizarAccion('enviar_pago_form')">
          @csrf
          @if($edit)
              @method('PUT')
          @endif

          <input type="hidden" name="action" value="{{$action}}" />
          <div class="card-header">
            <h3 class="card-title">
              DESCARGA DE EFECTIVO
            </h3>

            <h3 class="card-title">
              <label class="d-inline-block mx-1">Fecha:</label>
              <input type="date" class="form-control d-inline-block w-auto" id="fecha" name="fecha" value="{{$fecha}}" onchange="cambioDeFecha(this.value);" />
              </a>
            </h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12 border shadow p-1 text-end">
                <div class="mb-1 overflow-hidden" >

                <div class="row mb-1">
                  <div class="col-12 text-center">
                    <h1 class="text-center d-inline-block">CANTIDAD DE EFECTIVO</h1>
                    <a class="fs-1 btn btn-success d-inline-block ms-1" href="#" onclick="agrgarDescarga();">
                      <i style="height: 2.5rem; width: 2.5rem;" data-feather="plus"></i>
                    </a>
                    <div id="descargas-de-efectivo">
                      <input class="form-control w-100 text-center mt-1" name="descarga-efectivo[]" id="descarga-efectivo" type="number" value="0" style="font-size: 2rem;" onClick="this.select();" autocomplete="off" />

                      @php $total = 0; @endphp
                      @foreach ($descargas as $item)
                        <input class="form-control w-100 text-center mt-1" name="descarga-efectivo[]" id="descarga-efectivo" type="number" value="{{number_format($item->monto)}}" style="font-size: 2rem;" readonly />
                        @php $total += $item->monto; @endphp
                      @endforeach
                    </div>
                  </div>
                </div>

                <div class="row mb-1">
                  <div class="col-12">
                    <label class="fs-1 d-block border-top" >Total descarga de efectivo: <b>Q. <span id="descarga-total">{{number_format($total, 2)}}</span></b></label>
                  </div>
                </div>

                <button class="btn btn-dark w-100 m-auto d-block fs-1" id="btn-pago">
                  <i style="height: 1.8rem; width: 1.8rem;" data-feather="save"></i> GUARDAR
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    var total = {{$total}};
    total = numberWithCommas(total.toFixed(2))
    console.log(total)
    $('#descarga-total').html(total)

    function realizarAccion(formulario){
      $('#' + formulario).attr('onsubmit', '').submit();
    }
    
    function cambioDeFecha(fecha) {
      if (!isNaN(Number(new Date(fecha)))) {
        window.location = "/admin/descarga_efectivo/" + encodeURIComponent(fecha)
      }
    }

    function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function agrgarDescarga() {
      html = `<input class="form-control w-100 text-center mt-1" name="descarga-efectivo[]" id="descarga-efectivo" type="number" value="0" style="font-size: 2rem;" onClick="this.select();" autocomplete="off" />`;
      $('#descargas-de-efectivo').prepend(html);
    }

    $(document).ready(function(){
      $('body').on('keyup', 'input#descarga-efectivo', function(){
        var efectivo = 0;
        $('input#descarga-efectivo').each(function(){
          efectivo += parseFloat($(this).val());
        });
        total = numberWithCommas(efectivo.toFixed(2))
        $('#descarga-total').html(total)
      });
    });
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
