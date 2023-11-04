{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header bg-dark">
          <h3 class="card-title">
            LISTADO DE VENUES
          </h3>

          <h3 class="card-title mt-1 mt-sm-0 w-100">
            <a href="{{route('agregar_venue')}}" class="btn btn-light w-100 m-auto d-block fs-1"><i style="height: 1.6rem; width: 1.6rem;" data-feather="calendar"></i> AGREGAR VENUE </a>
          </h3>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-12">
              @foreach ($data as $key => $item)
                <div class="row mb-1" id="evento_contenedor" rel="{{strtolower($item->nombre)}}">
                  <div class="col-12 border bg-dark">
                    <label class="text-light py-1 fs-4"> {{$item->nombre}} </label>
                  </div>
                  <div class="col-8 border bg-dark">
                    <label class="text-light py-1 fs-3"> Aforo máximo </label>
                  </div>
                  <div class="col-4 border form-check" bis_skin_checked="1">
                    <label class="py-1 fs-4"> {{@$item->max_pax}} </label>
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Acciones </label>
                  </div>
                  <div class="col-8 border">
                    <label class="py-1 fs-4">
                      <a href="{{route('agregar_venue', ['id' => $item->id])}}" title="Editar" class="d-inline-block text-dark">
                        <i style="height: 1.6rem; width: 1.6rem;" data-feather="edit"></i> 
                      </a>
                      <a href="#" onclick="borrarVenue({{$item->id}})" class="d-inline-block text-dark" title="Borrar">
                        <i style="height: 1.6rem; width: 1.6rem;" data-feather="trash"></i> 
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
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
