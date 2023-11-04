<div class="row selcets">
  <div class="col-md-3">
    <div class="form-group overflow-hidden">
      <select class="form-control select2 w-100"
      id="country"
      onchange="filter()">
      <option value="">--País--</option>
    @foreach ($countlist AS $country)
      <option value="{{$country["value"]}}"
       {{(isset($_GET["cont"])&&
         $_GET["cont"]==$country["value"]?'selected':'')}}
      >{{$country["text"]}}</option>
    @endforeach
      </select>
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-group overflow-hidden">
      <select class="form-control select2 w-100"
        id="prefix"
        onchange="filter()">
        <option value="">--Prefijo--</option>
        @foreach ($prefix AS $pref)
          <option value="{{$pref["value"]}}"
          {{(isset($_GET["pref"])&&
            $_GET["pref"]==$pref["value"]?'selected':'')}}
          >{{$pref["text"]}}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-group overflow-hidden">
      <input type="text" id="findme"
      value="{{(isset($_GET["text"])?$_GET["text"]:'')}}"
      placeholder="buscar (enter para encontrar)"
      class="form-control">

    </div>
  </div>
</div>
@php
$urlKeep = '';
$urlKeep.=(isset($_GET["probId"])?'&probId='.$_GET["probId"]:'');
$urlKeep.=(isset($_GET["probName"])?'&probName='.$_GET["probName"]:'');
$urlKeep.=(isset($_GET["probType"])?'&probType='.$_GET["probType"]:'');
$urlKeep.=(isset($_GET["idAb"])?'&idAb='.$_GET["idAb"]:'');
$urlKeep.=(isset($_GET["lesson"])?'&lesson='.$_GET["lesson"]:'');
$urlKeep.=(isset($_GET["wd"])?'&wd='.$_GET["wd"]:'');


@endphp
<script type="text/javascript">
  function filter(){
    let url='{{route($urlEs.'.index')}}'+'?pref='+
    $('#prefix').val()+'&cont='+$('#country').val()+'&text='+
    $('#findme').val()+
    '{!!(isset($_GET["probId"])&&isset($_GET["probName"])||isset($_GET["wd"]))?$urlKeep:''!!}'
    linkTo(url);
  }
  function viewLec(esT){
    let linK = '{{route($urlEs.'.show',':id')}}'.replace(":id",esT);
    console.log(linK);
    @if(isset($_GET["probId"])&&isset($_GET["probName"]))
      window.location = linK+'?{!!$urlKeep!!}';
    @else
      linkTo(linK);
    @endif
  }
</script>
