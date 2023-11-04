{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header bg-dark">
          <h3 class="card-title">
            LISTADO DE LÍDERES
          </h3>

          <h3 class="card-title mt-1 mt-sm-0">
            <a href="{{route('agregar_lider')}}" class="btn btn-light w-100 m-auto d-block fs-1"><i style="height: 1.8rem; width: 1.8rem;" data-feather="plus"></i> AGREGAR LÍDER </a>
          </h3>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-12">
              @foreach ($data as $key => $item)
                <div class="row mb-1">
                  <div class="col-12 border bg-dark">
                    <label class="text-light py-1 fs-4"> {{$item->name}} </label>
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Sexo </label>
                  </div>
                  <div class="col-8 border py-1">
                    <label class="py-1 fs-4"> {{$item->sexo == 1 ? 'Hombre' : 'Mujer'}} </label>
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Teléfono </label>
                  </div>
                  <div class="col-8 border">
                    <label class="py-1 fs-4"> {{$item->telefono}} </label>
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Correo </label>
                  </div>
                  <div class="col-8 border">
                    <label class="py-1 fs-4"> {{$item->mail}} </label>
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Fecha nacimiento </label>
                  </div>
                  <div class="col-8 border">
                    <label class="py-1 fs-4"> {{substr($item->fecha_nacimiento, 8, 2)}}/{{substr($item->fecha_nacimiento, 5, 2)}}/{{substr($item->fecha_nacimiento, 0, 4)}} </label>
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Acciones </label>
                  </div>
                  <div class="col-8 border">
                    <label class="py-1 fs-4">
                      <a href="{{route('agregar_lider', ['id' => $item->id])}}" title="Editar" class="d-inline-block text-dark">
                        <i style="height: 1.8rem; width: 1.8rem;" data-feather="edit"></i> 
                      </a>
                      <a href="#" onclick="borrarEvento({{$item->id}})" class="d-inline-block text-dark" title="Borrar">
                        <i style="height: 1.8rem; width: 1.8rem;" data-feather="trash"></i> 
                      </a>
                    </label>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    $(document).ready(function(){
      $('select.select2').select2();
    })
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
