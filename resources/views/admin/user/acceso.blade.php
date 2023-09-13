{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <form id="acceso_form" method="POST" action="/admin/acceso" onsubmit="event.preventDefault(); realizarAccion('acceso_form')">
        @csrf

        <input type="hidden" name="action" value="1">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              CAMBIO DE CONTRASEÑA
            </h3>
            </h3>
          </div>
          <div class="card-body">
            <div class="row mb-2">
              <div class="col-12 col-sm-6 text-center m-auto">
                <label class="fs-1"> Bienvenido {{$usuario->name}} </label>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-12 col-sm-4 bg-dark text-left m-auto">
                <label class="text-light py-1 fs-3"> Contraseña: </label>
              </div>
              <div class="col-12 col-sm-8 m-auto mt-1">
  				      <input class="form-control w-100" name="pass" id="pass" type="password" value="" style="font-size: 2rem;" onClick="this.select();" autofocus required />
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-sm-4 bg-dark text-left m-auto">
                <label class="text-light py-1 fs-3"> Repetir contraseña: </label>
              </div>
              <div class="col-12 col-sm-8 m-auto mt-1">
  				      <input class="form-control w-100" name="rpass" id="rpass" type="password" value="" style="font-size: 2rem;" onClick="this.select();" required />
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                  <button class="btn btn-secondary my-1 w-100" id="boton" disabled> CAMBIAR CONTRASEÑA </button>
              </div>
            </div>
            <div class="row d-none" id="mensaje">
              <div class="col-12 bg-danger text-center">
                <label class="text-light py-1 fs-1"> Las contraseñas no coinciden </label>
              </div>
            </div>
            @if ($accion)
              <div class="row" id="mensaje">
                <div class="col-12 bg-success text-center">
                  <label class="text-light py-1 fs-1"> Contraseña actualizada satisfactoriamente </label>
                </div>
              </div>
            @endif
          </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script>
  	$(document).ready(function(){
  		$('#rpass').keyup(function(){
        if ($('#rpass').val() != '') {
          if ($('#rpass').val() != $('#pass').val()) {
            $('#mensaje').removeClass('d-none').addClass('d-block');
            $('#boton').addAttr('disabled');
          } else {
            $('#mensaje').addClass('d-none').removeClass('d-block');
            $('#boton').removeAttr('disabled');
          }
        }
      });
  	})

    function realizarAccion(formulario){
      $('#' + formulario).attr('onsubmit', '').submit();
    }
  </script>

  @component('admin.components.messagesForm')
  @endcomponent
@endsection
