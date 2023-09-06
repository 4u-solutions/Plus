{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
<script type="text/javascript">
</script>
   <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
			     <h3 class="card-title">
              <a href="javascript:"class="btn btn-primary waves-effect waves-float waves-light" onclick="newfloatv2(kad);">
                <i data-feather="file-plus"></i>
                &nbsp;&nbsp;
                Nuevo Usuario
              </a>
           </h3>
           <div class="card-options">
             <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
             <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
             <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
           </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table i class="table table-dark" >
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Rol</th>
                  <th>Sede</th>
                  <th>Estado</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
						    </tbody>
               </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    <script type="text/javascript">
      var criteria = @json($criti);
    </script>
    @component('admin.components.messagesForm')
    @endcomponent
@endsection
