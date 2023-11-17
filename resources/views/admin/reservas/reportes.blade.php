{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card mb-1">
        <div class="card-header bg-dark ">
          <h5 class="card-title">
            REPORTES
          </h5>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <a href="{{route('reporte_por_pax')}}" class="btn btn-dark d-block m-auto d-block fs-3 mt-1">
              <i style="height: 1.6rem; width: 1.6rem;" data-feather="align-justify"></i> REPORTE DETALLADO
            </a>

            <a href="{{route('reporte_estadisticas')}}" class="btn btn-dark d-block m-auto d-block fs-3 mt-1">
              <i style="height: 1.6rem; width: 1.6rem;" data-feather="pie-chart"></i> REPORTE GENERAL CON GRÁFICAS
            </a>

            <a href="{{route('reporte_pull')}}" class="btn btn-dark d-block m-auto d-block fs-3 mt-1">
              <i style="height: 1.6rem; width: 1.6rem;" data-feather="pie-chart"></i> REPORTE PULLS
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
