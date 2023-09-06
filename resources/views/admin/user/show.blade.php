{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
<script type="text/javascript">

var kad={ 0:{type:"hddn",nm:"_token",vl:'{{ csrf_token() }}'},
          1:{type:"txt",tl:"Nombre y apellido",id:"nomb",nm:"name",
              add:'onkeyup="username(this);"',elv:"0",nxt:6},
          2:{type:"txt",tl:"Pago mínimo",id:"pago_minimo",nm:"pago_minimo",elv:"1",nxt:6},
          3:{type:"txt",tl:"Usuario",id:"userex",nm:"usersys",elv:"2",nxt:6},
          4:{type:"txt",tl:"Password",nm:"password",elv:"3",add:'maxlength="15"',id:"passw",nxt:6},
          5:{type:"slct",tl:"Tipo de usuario",nm:"roleUS",vl:@json($roleUsers),elv:"4",nxt:6},
          6:{type:"chkbxstyl",tl:"Esta actualmente",id:"activ",nm:"statusUs", vl:[["1",'<span id="stat"></span>']],elv:"5", add:'onclick="checar(this,0);"',nxt:6},

       }
    valdis={clase:"red",text:1,checkbox:1};
</script>
   <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            USUARIOS
          </h3>

          <h3 class="card-title">
            <a href="javascript:"class="btn btn-primary waves-effect waves-float waves-light" onclick="newfloatv2(kad);">
              <i data-feather="plus"></i>&nbsp;&nbsp;Nuevo usuario
            </a>
          </h3>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table i class="table table-dark" >
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Rol</th>
                  <th>Estado</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>

                @foreach ($users as $user)
                  <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td >{{$user->name}}</td>
                    <td >{{$user->role->nameRole}}</td>
                    <td class="visible-lg">{{($user->statusUs==1?'Activado':'Desactivado')}}</td>
                    <td class="td-actions  text-left">
                      <a  href="javascript:" onclick="modifyfloat('{{$user->id}}',kad,criteria);">
                        <i data-feather="edit"></i>
                      </a>
                      <a href="javascript:" onclick="deleteD('{{$user->id}}','{{ csrf_token() }}');">
                        <i data-feather="trash-2"></i>
                      </a>
                    </td>
                  </tr>
                  <?php
                    $criti[$user->id]=array(
                      $user->name,
                      $user->pago_minimo,
                      $user->usersys,
                      '',
                      $user->roleUS,
                      array($user->statusUs)
                    );
                  ?>
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
    @component('admin.components.messagesForm')
    @endcomponent
@endsection
