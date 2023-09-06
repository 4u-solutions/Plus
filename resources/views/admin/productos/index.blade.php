{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
<script type="text/javascript">

var kad={ 0:{type:"hddn",nm:"_token",vl:'{{ csrf_token() }}'},
          1:{type:"txt",tl:"Nombre",id:"nombre",nm:"nombre",elv:"0",nxt:6},
          2:{type:"txt",tl:"Precio",id:"precio",nm:"precio",elv:"1",nxt:6},
          3:{type:"txt",tl:"Mixers",id:"mixers",nm:"mixers",elv:"2",nxt:6},
          4:{type:"slct",tl:"Categoría",nm:"id_tipo",vl:@json($tipoWaro),elv:"3",nxt:6},
          5:{type:"slct",tl:"Producto relacionado",nm:"id_producto",vl:@json($productos),elv:"4",nxt:6},
          6:{type:"chkbxstyl",tl:"Esta actualmente",id:"activ",nm:"statusUs", vl:[["1",'<span id="stat"></span>']],elv:"5", add:'onclick="checar(this,0);"',nxt:6},

       }
    valdis={clase:"red",text:1,checkbox:1};
</script>
   <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            LISTADO DE PRODUCTOS DISPONIBLES
          </h3>

          <h3 class="card-title">
            <a href="javascript:"class="btn btn-primary waves-effect waves-float waves-light" onclick="newfloatv2(kad);">
              <i data-feather="plus"></i>&nbsp;&nbsp;Agregar nuevo producto
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
                  <th>Precio</th>
                  <th>Mixers</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>

                @foreach ($data as $item)
                  <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td >{{$item->nombre}}</td>
                    <td >Q. {{number_format($item->precio, 2)}}</td>
                    <td >{{$item->mixers}}</td>
                    <td class="td-actions  text-left">
                      <a  href="javascript:" onclick="modifyfloat('{{$item->id}}',kad,criteria);">
                        <i data-feather="edit"></i>
                      </a>
                      <a href="javascript:" onclick="deleteD('{{$item->id}}','{{ csrf_token() }}');">
                        <i data-feather="trash-2"></i>
                      </a>
                    </td>
                  </tr>
                  <?php
                    $criti[$item->id]=array(
                      $item->nombre,
                      $item->precio,
                      $item->mixers,
                      $item->id_tipo,
                      $item->id_producto,
                      array($item->estado)
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
