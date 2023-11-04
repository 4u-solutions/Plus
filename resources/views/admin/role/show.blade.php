@extends('admin.layouts.app')
@section('main-content')
@php $criti = [];@endphp
<script type="text/javascript">
var kad={0:{type:"hddn",nm:"_token",vl:'{{ csrf_token() }}'},
      1:{type:"txt",tl:"Nombre",nm:"nameRole",elv:"0"},
        2:{type:"chckbx",tl:"Rutas permitidas",nm:"acceds[]",vl:@json($permission),elv:"1"},
  };
    valdis={clase:"red",text:1};
</script>
   <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
					<h3 class="card-title">
            <a href="javascript:" class="btn btn-primary waves-effect waves-float waves-light" onclick="newfloatv2(kad);">
            <i data-feather="file-plus"></i>
            &nbsp;&nbsp;Nuevo rol de usuario
          </a>
          </h3>
					<div class="card-options">
						<a href="#" class="card-options-collapse" data-toggle="card-collapse">
              <i class="fe fe-chevron-up"></i>
            </a>
						<a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen">
              <i class="fe fe-maximize"></i>
            </a>
						<a href="#" class="card-options-remove" data-toggle="card-remove">
              <i class="fe fe-x"></i>
            </a>
					</div>
				</div>
        <div class="card-body">
          <div class="table-responsive">
            <table i class="table table-dark" >
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Permisos</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>

                @foreach ($roles as $role)
                  @php $perms=array(); @endphp
                  <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td >{{$role->nameRole}}</td>
                    <td >
                      @foreach($role->roles()->get() AS $acced)
                        @php $infoAcces = $acced->nameroles()->first();@endphp
                        {{$infoAcces->naccess}}
                        <br>
                        @php $perms[]=$infoAcces->id;
                        // dd($perms);
                        @endphp
                      @endforeach
                    </td>
                    <td class="td-actions  text-left">
                      <a  href="javascript:"
                          onclick="modifyfloat('{{$role->id}}',kad,criteria);">
                          <i data-feather="edit"></i>
                      </a>
                      <a  href="javascript:"
                          onclick="deleteD('{{$role->id}}','{{ csrf_token() }}');">
                          <i data-feather="trash-2"></i>
                      </a>
                    </td>
                  </tr>
                  @php
                  $criti[$role->id]=array($role->nameRole, $perms);
                  @endphp
                @endforeach
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

@endsection
