@extends('admin.layouts.app')
@section('main-content')
<script type="text/javascript">
  var kad={0:{type:"hddn",nm:"_token",vl:'{{ csrf_token() }}'},
          1:{type:"txt",tl:"Nombre",nm:"naccess",elv:"0",nxt:6},
          2:{type:"txt",tl:"Url",nm:"archaccess",elv:"1",nxt:6},
          3:{type:"chkbxstyl",tl:"Publico",nm:"publc",vl:[["1",'<span id="stat"></span>']],
             elv:"2",add:'onclick="checar(this,0);" omit="T"',nxt:6},
          4:{type:"txt",tl:"Icono",id:"imag",nm:"iconaccess",elv:"3",
            add:'onclick="flotingblank(this);" readonly',nxt:6},
          5:{type:"txt",tl:"Grupo",nm:"groupacc",elv:"4"}};
  var conform={action:"{{route('admin.permissions.store')}}"}
    valdis={clase:"red",text:1,checkbox:1,radio:1};
</script>
   <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            ACCESOS
          </h3>

          <h3 class="card-title">
            <a href="javascript:"class="btn btn-primary waves-effect waves-float waves-light" onclick="newfloatv2(kad);">
              <i data-feather="plus"></i>&nbsp;&nbsp;Nuevo acceso
            </a>
          </h3>
				</div>

        <div class="card-body">
          <div class="table-responsive">
            <table i class="table table-dark" >
              <thead>
                <tr>
                  <th>#</th>
                  <th>Grupo</th>
                  <th>Título</th>
                  <th>Ícono</th>
                  <th>Archivo</th>
                  <th>Publicado</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($permissions as $permission)
                  <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td class="hidden-xs">{{$permission->groupacc}}</td>
                    <td >{{$permission->naccess}}</td>
                    <td ><i class="material-icons">{{$permission->iconaccess}}</i></td>
                    <td class="hidden-xs">{{$permission->archaccess}}</td>
                    <td class="visible-lg">{{$permission->publc}}</td>
                    <td class="td-actions  text-left">
                      <a href="javascript:"
                      onclick="modifyfloat('{{$permission->id}}',kad,criteria);">
                      <i class="ficon" data-feather="edit"></i>
                      {{-- <i class="pe-7s-note "></i> --}}
                      </a>
                      <a href="javascript:"
                      onclick="deleteD('{{$permission->id}}','{{ csrf_token() }}');">
                      <i class="ficon" data-feather="trash-2"></i>
                    </a>
                    </td>
                  <?php
                  $criti[$permission->id]=array($permission->naccess,
                                                       $permission->archaccess,
                                                       array($permission->publc),
                                                      $permission->iconaccess,
                                                       $permission->groupacc);?>
                @endforeach
						    </tbody>
               </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    <script type="text/javascript">
      var criteria = <?=json_encode($criti)?>;
      function fillimg(sto)
      {
        $("#imag").val(sto);
        flotantecloseblank();
      }
    </script>
    @component('admin.components.messagesForm')
    @endcomponent
@endsection
