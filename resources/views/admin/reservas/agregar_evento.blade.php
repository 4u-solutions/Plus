{{-- @inject('helper', 'App\Http\Helpers\helpers') --}}
@extends('admin.layouts.app')
@section('main-content')
@php $criti=[];@endphp
@php $public_path = substr(getcwd(), 0, strrpos(getcwd(), '/')); @endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form id="eventos_form" method="POST" action="/admin/agregar_evento{{isset($data->id) ? '/'.$data->id : ''}}" onsubmit="event.preventDefault(); realizarAccion('eventos_form')" enctype="multipart/form-data">
          @csrf
          @if($edit)
              @method('PUT')
          @endif

          <div class="card-header">
            <h3 class="card-title">
              @if (@$data->id)
                EDITAR EVENTO: {{$data->nombre}}
              @else
                AGREGAR EVENTO
              @endif
            </h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="row">
                  <div class="col-10 border bg-dark">
                    <label class="text-light py-1 fs-3"> ¿Es pagado? </label>
                  </div>
                  <div class="col-2 border form-check px-0" bis_skin_checked="1">
                    <input type="checkbox" class="form-check-input mt-1 ms-1" name="pagado" id="pagado" value="1" {{@$data->pagado ? 'checked' : ''}}>
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-4"> Venue </label>
                  </div>
                  <div class="col-8 border py-1">
                    <select name="id_venue" id="id_venue" class="form-control select2 w-100 my-1 fs-4" >
                      @foreach ($venues as $key => $item)
                        <option value="{{$item->id}}" {{$item->id == @$data->id_venue ? 'selected' : ''}}>{{$item->nombre}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Nombre </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-3" id="nombre" name="nombre" type="text" value="{{@$data->nombre}}" />
                  </div>
                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Fecha </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-3" id="fecha" name="fecha" type="date" value="{{@$data->fecha}}" />
                  </div>

                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Fondo </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-3" id="fondo" name="fondo" type="file" />
                    @if (File::exists($public_path . 'mapas/' . $data->id . '.jpg'))
                      <img src="{{asset('mapas/' . $data->id . '.jpg')}}" class="w-100">
                    @endif
                    @if (File::exists(public_path('fondo/' . @$data->id . '.jpg')))
                      <img src="{{asset('fondo/' . @$data->id . '.jpg')}}" class="w-100">
                    @endif
                  </div>

                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Isométrico </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-3" id="isometrico" name="isometrico" type="file" />
                    @if (File::exists(public_path('eventos/iso_' . @$data->id . '.jpg')))
                      <img src="{{asset('eventos/iso_' . @$data->id . '.jpg')}}" class="w-100">
                    @endif
                  </div>

                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Mapa </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-3" id="mapa" name="mapa" type="file" />
                    @if (File::exists(public_path('mapas/' . @$data->id . '.jpg')))
                      <img src="{{asset('mapas/' . @$data->id . '.jpg')}}" class="w-100">
                    @endif
                  </div>

                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Ingreso </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-3" id="ingreso" name="ingreso" type="file" />
                    @if (File::exists(public_path('ingreso/' . @$data->id . '.jpg')))
                      <img src="{{asset('ingreso/' . @$data->id . '.jpg')}}" class="w-100">
                    @endif
                  </div>

                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Ubicación </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-3" id="ubicacion" name="ubicacion" type="file" />
                    @if (File::exists(public_path('waze/' . @$data->id . '.jpg')))
                      <img src="{{asset('waze/' . @$data->id . '.jpg')}}" class="w-100">
                    @endif
                  </div>

                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Link </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-3" id="link_waze" name="link_waze" type="text" value="{{@$data->link_waze}}" />
                  </div>

                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Boleta </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-3" id="boleta" name="boleta" type="file" />
                    @if (File::exists(public_path('boleta/' . @$data->id . '.jpg')))
                      <img src="{{asset('boleta/' . @$data->id . '.jpg')}}" class="w-100">
                    @endif
                  </div>

                  @for ($i = 1; $i <= 5; $i++)
                    <div class="col-4 border bg-dark">
                      <label class="text-light py-1 fs-3"> Menu #{{$i}} </label>
                    </div>
                    <div class="col-8 border">
                      <input class="form-control my-1 fs-3" id="menu_{{$i}}" name="menu_{{$i}}" type="file" />
                      @if (File::exists(public_path('menu/' . $i . '_' . @$data->id . '.jpg')))
                        <img src="{{asset('menu/' . $i . '_' . @$data->id . '.jpg')}}" class="w-100">
                      @endif
                    </div>
                  @endfor

                  <div class="col-4 border bg-dark">
                    <label class="text-light py-1 fs-3"> Video </label>
                  </div>
                  <div class="col-8 border">
                    <input class="form-control my-1 fs-3" id="video" name="video" type="file" />
                    @if (File::exists(public_path('video/' . @$data->id . '.jpg')))
                      <img src="{{asset('video/' . @$data->id . '.jpg')}}" class="w-100">
                    @endif
                  </div>

                  @for ($i = 1; $i <= 3; $i++)
                    <div class="col-4 border bg-dark">
                      <label class="text-light py-1 fs-3"> DJ #{{$i}} </label>
                    </div>
                    <div class="col-8 border">
                      <input class="form-control my-1 fs-3" id="dj_{{$i}}" name="dj_{{$i}}" type="file" />
                      @if (File::exists(public_path('dj/' . $i . '_' . @$data->id . '.jpg')))
                        <img src="{{asset('dj/' . $i . '_' . @$data->id . '.jpg')}}" class="w-100">
                      @endif
                    </div>
                  @endfor

                  @for ($i = 1; $i <= 10; $i++)
                    <div class="col-4 border bg-dark">
                      <label class="text-light py-1 fs-3"> Sponsor #{{$i}} </label>
                    </div>
                    <div class="col-8 border">
                      <input class="form-control my-1 fs-3" id="patrocinador_{{$i}}" name="patrocinador_{{$i}}" type="file" />
                      @if (File::exists(public_path('patrocinadores/' . $i . '_' . @$data->id . '.jpg')))
                        <img src="{{asset('patrocinadores/' . $i . '_' . @$data->id . '.jpg')}}" class="w-100">
                      @endif
                    </div>
                  @endfor

                  <div class="col-12 border">
                      <button class="btn btn-dark my-1 w-100 fs-3" style="">
                          {{ @$edit ? 'Actualizar datos' : 'Guardar'}}
                      </button>
                  </div>
                  @if (File::exists(public_path('patrocinadores/' . @$data->id . '.jpg')))
                    <div class="col-12 border">
                      <img src="{{asset('patrocinadores/' . @$data->id . '.jpg')}}" class="w-100">
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    function realizarAccion(formulario){
      $('#' + formulario).attr('onsubmit', '').submit();
    }

    function cambioDeFecha(fecha) {
      if (!isNaN(Number(new Date(fecha)))) {
        window.location = "/admin/eventos/" + encodeURIComponent(fecha)
      }
    }

    $(document).ready(function(){
      $('select.select2').select2();
    });
  </script>
  @component('admin.components.messagesForm')
  @endcomponent
@endsection
