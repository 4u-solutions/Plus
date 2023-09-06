// var patron2 = new Array(4,4);
var cntt,to,vri='',radiochk=0,pase='',auxi='',valdis;
var cad='',titlemodal='';
var objls;
var target=getParameterByName('target',window.location.href);

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}


function loadingmig(ti,tx){

    let pathi = "/images/cristales_p_f.gif",
        tit=(ti==undefined?"Procesando":ti),
        txt=(tx==undefined?"Un momento por favor":tx);
        swal.fire({
        title: tit,
        text: txt,
        imageUrl: pathi,
        showConfirmButton: false,
        allowOutsideClick: false
      });
        //return true;
    }
function mascara(d,sep,pat,nums,min,max)
{
    if(d.valant != d.value){
	val = d.value;
	largo = val.length;
	val = val.split(sep);
	val2 = '';
	for(r=0;r<val.length;r++){
	    val2 += val[r];
	}
	if(nums){
	    for(z=0;z<val2.length;z++){
		if(isNaN(val2.charAt(z))){
		    letra = new RegExp(val2.charAt(z),"g");
		    val2 = val2.replace(letra,"");
		}
	    }
	}
	val = '';
	val3 = new Array();
	for(s=0; s<pat.length; s++){
	    val3[s] = val2.substring(0,pat[s]);
	    val2 = val2.substr(pat[s]);
	}
	for(q=0;q<val3.length; q++){
	    if(q ==0){
            val = val3[q];
	    }
	    else{
            if(val3[q] != ""){
                val += sep + val3[q];
            }
	    }
	}
	if (max!=undefined&&min!=undefined&&val>=min&&val<=max||
        max==undefined&&min==undefined) {
        //console.log(max);
	    d.value  = val;
	}else{
	    //alert("Cantidad incorrecta");
	    d.value  = "";
	}
	d.valant = val;
    }
}


function maxmin(d,min,max)
{
    if(d.value>=min&&d.value<=max){
    }else{
        d.value="";
        d.focus();
    }
}



function newfloatv2(cadena,editobjet,url,nobots,litUrl)
{
    var ckd=func=wte='';
    let $typeForm='POST',edit;
    var APP_URL = window.location.pathname.split( '/' );
    // console.log(url+'_',nobots,'_'+litUrl);
    // console.log(APP_URL,window.location);
    $(".fndwindow").show();
    $(".windowed").show();
    edit=(editobjet!=undefined?true:false);
    if (typeof litUrl === 'string'&&nobots=='literalUrl'){
        turl =litUrl;
        // console.log(turl);
    }
    // else
    // if(APP_URL.length>4&&edit==true&&nobots!='literal'){
    //   turl=window.location.origin+'/'+APP_URL[1]+'/'+APP_URL[2]+'/'+APP_URL[3]
    //   +'/'+(nobots!=undefined?nobots+'/':'')+url;
    // }
    else if (url==undefined&&edit==false){
        turl='';
    }
    else if (typeof url === 'string'&&edit==true){
      turl=window.location.href+'/'+url;
    }
    else if (typeof url === 'string'&&edit==true){
      turl =window.location.href+'?cod='+url;
    }
    else if (typeof url === 'string'){
        turl =url;
    }
    // console.log(turl,nobots,litUrl);
    if (cadena!=undefined&&(typeof cadena)!='object'&&(typeof cadena)=='string'){
            ckd=cadena;
    }
    else if((typeof cadena)=='object')
    {
        elems=(cadena.form!=undefined?cadena.form:cadena);
        $typeForm=(editobjet!=undefined&&editobjet!=null?'PUT':'POST');
        func=(cadena.ff!=undefined?cadena.ff:"return validar(valdis);");
        ckd=formgenerator(elems,editobjet);
        // console.log(editobjet,(typeof cadena),turl,$typeForm);
    }
    swal.fire({title: '',
               html:'<div class="row formss">\
                        <div class="col-md-12">\
                            <div class="card border-0 border-shadow-0 m-0" style="box-shadow:0px 0px 0px;">' +
                                '<div class="card-body">\
                                    <form action="'+turl+'" id="formflotant" method="POST" role="form" enctype="multipart/form-data" ><div class="row">'
                                    +'<input type="hidden" name="_method" value="'+$typeForm+'">'+
                                    ckd
                                    +'</form>\
                                </div>\
                            </div>\
                        </div>\
                    </div>',
              title:'<h4 class="card-title mb-0">'+
                    (editobjet!=undefined?'Editar':'Agregar')+'</h4>',
              showCloseButton: true,
              showCancelButton: true,
              confirmButtonClass: 'btn btn-success waves-effect waves-float waves-light me-1',
              cancelButtonClass: 'btn btn-danger waves-effect waves-float waves-light',
              confirmButtonText: 'Guardar',
              cancelButtonText: 'Cancelar',
              buttonsStyling: false,
              width: 600,
        })
        .then((result) =>{
            // console.log(result);
            // loadingmig();
            // let pass = false;
            if(result.value==true&&cadena.function==undefined){
              $("#formflotant").submit();
              // if(valdis!=undefined){
              //   validar(valdis);
              // }
            }else if (result.value==true&&typeof ownFunction === "function"){
              ownFunction();
            }else{
              return false;
            }

            return false;
          })
    ;
      $('.titlewindow').html((editobjet==undefined?'Agregar ':'Editar '));
}
// +
// (nobots!=0?'<div class="col-lg-12 col-md-12 col-sm-12"><input type ="submit" class="btn btn-teal" onclick="'+func+'" value="Guardar"/>\
//  <input type ="button" value="Cancelar" class="btn btn-pinterest" onclick="swal.close(); $(\'body\').css(\'overflow\',\'auto\');"/></div>':'')

function formgenerator(elems,$objed)
{
    var cad='',trfinal='',cont=0;
    for (var ii in elems)
    {
        var input=elems[ii];
        var nxtet=elems[parseInt(ii)+1];
        var dis=0;
        addx=(input.add!=undefined&&input.add!='')?input.add:'';
        ids=(input.id!=undefined&&input.id!='')?'id="'+input.id+'"':'';
        //console.log(nxtet.nxt);
        classe=(input.clss!=undefined&&input.clss!='')?input.clss:'';
        namess=(input.nm==undefined?'':'name="'+input.nm+'"');
        // console.log($objed);
        switch (input.type)
        {
            case 'lbl':{
                cad='<label>'+input.tl+'</label>';
            }break;
            case 'txt':{

                $vald=($objed!=undefined&&input.elv!=undefined)?'value="'+$objed[input.elv]+'"':($objed==undefined&&input.vl!=undefined?'value="'+input.vl+'"':'');
                cad='<input class="form-control '+classe+'" type="text" '+namess+' '+ids+' '+$vald+' '+addx+'/>';
            }break;
            case 'clr':{
                $vald=($objed!=undefined&&input.elv!=undefined)?'value="'+$objed[input.elv]+'"':($objed==undefined&&input.vl!=undefined?'value="'+input.vl+'"':'');
                cad='<input class="form-control '+classe+'" type="color" '+namess+' '+ids+' '+$vald+' '+addx+'/>';
            }break;
            case 'nbr':{
                $vald=($objed!=undefined&&input.elv!=undefined)?'value="'+$objed[input.elv]+'"':($objed==undefined&&input.vl!=undefined?'value="'+input.vl+'"':'');
                cad='<input class="form-control '+classe+'" type="number" '+namess+' '+ids+' '+$vald+' '+addx+'/>';
            }break;
            case 'hddn':{
                $vald='value="'+($objed!=undefined&&$objed[input.elv]!=undefined?$objed[input.elv]:input.vl)+'"';
                cad='<input type="hidden" '+namess+' '+ids+' '+$vald+' '+addx+'/>';
            }break;
            case 'txtarea':{
                $vald=($objed!=undefined&&input.elv!=undefined)?($objed[input.elv]!=undefined?$objed[input.elv]:''):($objed==undefined&&input.vl!=undefined?input.vl:'');
                cad='<textarea class="form-control '+classe+'" '+namess+' '+ids+' '+addx+' >'+$vald+'</textarea>';
            }break;
            case 'pss':{
                cad='<input class="form-control '+classe+'" type="password" '+namess+' '+ids+' '+addx+'/>';
            }break;
            case 'dtmpckr':{
              $vald='value="'+($objed!=undefined&&$objed[input.elv]!=undefined?$objed[input.elv]:input.vl)+'"';
                cad='<div class="input-group date">\
                      <div class="input-group-prepend">\
                        <div class="input-group-text br-tl-3 br-bl-3">\
                          <i class="fa fa-calendar"></i>\
                        </div>\
                      </div>\
                      <input type="text" class="form-control pull-right  '+classe+'" '+$vald+' '+namess+' '+ids+' '+addx+'>\
                    </div>';
            }break;
            case 'fl':{
                //alert((typeof input.chkfl));
                verifile=(input.chkfl!=undefined)?'onchange="valfile('+input.chkfl+',this);"':'';
                //alert(verifile);
                cad='<span class="btn btn-default btn-round btn-file">\
								<span class="fileinput-new">Cambiar o subir archivo</span>\
								<input type="file" '+namess+'  '+ids+verifile+' '+addx+'/>\
							</span>';
            }break;
            case 'btn':{
                cad='<button class="'+classe+'" type="button" '+ids+' '+addx+'>'+input.nm+'</button>';
            }break;
            case 'rd':{
                cad='<div class="custom-controls-stacked">';
                for (i=0;i<input.vl.length;i++) {
                    $vald=($objed!=undefined&&input.elv!=undefined&&$objed[input.elv]==input.vl[i][1])?'checked':'';
                    cad+='<label class="custom-control custom-radio">\
                    <input type="radio" '+namess+' id="'+ids[i]+'"\
                    value="'+input.vl[i][1]+'" '+$vald+" "+addx+'\
                    class="custom-control-input"/>\
                    <span class="custom-control-label">'+
                    input.vl[i][0]+(input.$brl!=undefined?'':
                    '</span></label>');
                }
                cad+='</div>';
            }break;
            case 'chkbxstyl':{
                $vald=($objed!=undefined&&input.elv!=undefined?$objed[input.elv]:input.vl[0][0]);
                check=($objed!=undefined&&input.vl!=undefined&&input.vl[0][0]==$objed[input.elv]?'checked="true"':'');
                cad='<label class="custom-switch" style="display: block;">\
                    <input type="checkbox" '+ids+' '+namess+
                    'value="'+$vald+'" '+check+' '
                    +addx+' class="custom-switch-input">\
                    <span class="custom-switch-indicator"></span>\
                    <span class="custom-switch-description">' + (check ? 'Activado' : 'Desactivado') + '</span>\
                   </label>';
            }break;
            case 'chckbx':{
                cad='';
                console.log(input.vl.length);
                cad+='<div class="demo-inline-spacing">';
                for (i=0;i<input.vl.length;i++) {
                    if($objed!=undefined&&input.elv!=undefined){
                        for (jj=0;jj<($objed[input.elv].length);jj++) {
                            if($objed[input.elv][jj]==input.vl[i][0]){
                                $vald='checked="checked"';
                                break;
                            }else{
                                $vald='';
                            }
                        }
                    }

                    $nomb=(input.nm!=undefined &&(typeof input.nm=='array'))?input.nm[i]:input.nm;
                    $id=(ids!=undefined)?ids:'';
                    cad+='<div class="form-check form-check-inline w-100 text-start">\
                              <input type="checkbox" name="'+$nomb+'" '+$id+'\
                                  class="form-check-input"\
                                  value="'+input.vl[i][0]+'" '+addx+' '+$vald+'/>\
                            <label class="form-check-label" >\
                              '+input.vl[i][1]+'\
                            </label>\
                         </div>';
                }
                cad+='</div>';
            }break;
            case 'slct':{
                cad='<select '+namess+' '+ids+' '+addx+' class="form-control">';
                if (input.vl!==undefined) {
                    for (i=0;i<input.vl.length;i++)
                    {
                        //for (jj=0;jj<($objed[input.elv].length);jj++) {
                        $vald=($objed!=undefined&&input.elv!=undefined&&$objed[input.elv]==input.vl[i][0])?'selected':'';
                        //}
                        cad+=(input.vl[i][0]=='optgrupolb')?'<optgroup label="'+input.vl[i][1]+'">':
                        '<option value="'+input.vl[i][0]+'" '+$vald+'>'+input.vl[i][1]+'</option>';
                    }
                }
                cad+='</select>';
            }break;
            case 'img':{
                $vald=($objed!=undefined&&input.elv!=undefined)?$objed[input.elv]:'';
                cad='<img src="'+$vald+'" '+ids+' '+addx+' class="'+classe+'"/>';
            }break;
            case 'lnk':{
                $vald=($objed!=undefined&&input.elv!=undefined)?$objed[input.elv]:'';
                $filne=($objed!=undefined&&input.fln!=undefined)?$objed[input.fln]:
                    (input.altname!=undefined)?input.altname:'';
                cad='<a href="'+$vald+'" '+ids+' '+addx+' class="'+classe+'">'+$filne+'</a>';
            }break;
            case 'dfl':{
                cad=input.cont;
            }break;
            case 'tltwnd':{
                cad='';

                if ($objed==undefined) {
                    kedit='nuevo';
                }else{
                    kedit='editar';
                }
                document.getElementsByClassName('titlewindow')[0].innerHTML=kedit+" "+(input.vl==undefined?'':input.vl);
            }break;
        }
        if (input.type=='img'&&$objed==undefined
          ||input.type=='lnk'&&$objed==undefined&&input.type=='tltwnd'
          ||input.nonew!=undefined&&$objed==undefined) {
            dis=1;
        }

        tittle=(input.tl==undefined?'':input.tl);
        trfinal+=(dis==0?(input.type!='dfl'&&input.type!='tltwnd'?
         (input.type!='hddn'?'<div class="'+(input.nxt!=undefined?'col-'+input.nxt
         :'col-12')+'">'+
         (input.type==='chckbx'?'<label class="form-label">'+tittle+'</label>':'')+
         (input.type==='rd'||input.type==='dtmpckr'?'<label>'+tittle+'</label>':'')+
         (input.type==='chckbx'||input.type==='btn'||
          input.type==='rd'||input.type==='dtmpckr'?'':
         '<label class="form-label">'+tittle+'</label>')+
         (input.ttl!=undefined?input.ttl:''):'')+cad+(input.type!='hddn'?
         '</div>':
         ''):'<div class="'+classe+'">'+cad+'</div>'):'');
        cad='';
        $vald='';
        addx='';
        ids='';
    }
    return trfinal;
}

//CODIGO,OBJETODEFORMULARIO,OBJETODEDATOS,url
function modifyfloat(codrx,formobj,objcont,targetref,addUrl)
{
  // console.log(objcont);
    if (objcont!=undefined) {
    	var dta=objcont[codrx];
    	newfloatv2(formobj,dta,codrx,targetref,addUrl);
    }else{
        alert("no funciona");
    }
}
function username(nme,codm)
{
    var palabras = nme.value.split(' ').length;
    var pala = nme.value.split(' '),useer="";
    switch(palabras){
        case 1: {
                useer+=pala[0].substr(0,1);
                usesr=useer;
        }break;
        case 2: {
            if(nme.value!=''){
                useer+=usesr+pala[1];
                carta=useer;
            }
        }break;
        case 3: {
            if(nme.value!=''){
                useer+=usesr+pala[2];
                carta=useer;
            }
        }break;
        default:{
            useer=carta;
        }break;
    }
    $('#userex').val(useer.toLowerCase());
    $('#passwx').val(nem);
}
function deleteD(code,token,urlx)
{
  let APP_URL = window.location;

  if(urlx!=undefined&&isFinite(urlx))
  {
    // console.log(urlx.indexOf("https"));
    let altern = APP_URL.pathname.split( '/' );
    formurl='/';
    for(let il=1;il<=urlx;il++){
      formurl+=altern[il]+(il<urlx?'/':'');
    }
    url=window.location.origin+formurl+'/'+code;
    // console.log(url);
  }
  else if(urlx!=undefined&&typeof urlx === 'string'&&urlx.indexOf("http")>=0)
  {
        url=urlx;
  }
  else if(urlx!=undefined&&typeof urlx === 'string'){
    url=APP_URL.origin+APP_URL.pathname+"/"+urlx+"/"+code;
  }else{
    url=APP_URL.origin+APP_URL.pathname+"/"+code;
  }
  swal.fire({
      title: 'Confirma que desea borrar',
      text: "Esta operación no puede ser revertida",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00b8d9',
      cancelButtonColor: '#ef4a4a',
      confirmButtonText: 'Si',
      cancelButtonText: 'Cancelar'
  }).then((result) =>{
      if(result.value==true){
        $form = $('<form method="post" action="'+url+'"></form>');
        $form.append('<input type="hidden" name="_token" value="'+token+'">');
        $form.append('<input type="hidden" name="_method" value="DELETE">');
        $(document.body).append($form);
        console.log($form);
        $form.submit();
        loadingmig();
      }
  });
}

function seleccionarUsuario(token,urlx, perfilesJson)
{
  let APP_URL = window.location;

  var url=APP_URL.origin+urlx;
  console.log(url)

  selectPerfiles = '<select name="idPerfil" id="idPerfil" class="form-control">'
  $.each(perfilesJson, function(i, item) {
    selectPerfiles += '<option value="' + item.id + '">' + item.nombres + ' ' + item.apellidos + '</option>'
  })
  selectPerfiles += '</select>';

  swal.fire({
        title: 'Confirmar que desea utilizar este perfil',
        html:'<div class="row formss">\
                <div class="col-md-12">\
                    <div class="card border-0 border-shadow-0 m-0" style="box-shadow:0px 0px 0px;">' +
                        '<div class="card-body">\
                            <h5>Seleccionar perfil para generar Examen Psicológico</h5>\
                            <form action="'+url+'" id="formflotant" method="POST" role="form" enctype="multipart/form-data" ><div class="row">'
                                + selectPerfiles
                                + '<input type="hidden" name="_method" value="'+url+'" />' +
                            '</form>\
                        </div>\
                    </div>\
                </div>\
            </div>',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00b8d9',
        cancelButtonColor: '#ef4a4a',
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar'
  }).then((result) =>{
        let valId = $('#idPerfil').val();
        url += valId;
      if(result.value==true){
        $form = $('<form method="post" action="'+url+'"></form>');
        $form.append('<input type="hidden" name="_token" value="'+token+'">');
        $form.append('<input type="hidden" name="_method" value="POST">');
        $(document.body).append($form);
        console.log($form);
        $form.submit();
        loadingmig();
      }
  });
}

function seleccionarDobleOpcion(token,urlx, primerJson, segundoJson, primerId, segundoId, primerTitulo, segundoTitulo)
{
  let APP_URL = window.location;

  var url=APP_URL.origin+urlx;

  primerSelect = '<select name="' + primerId + '" id="' + primerId + '" class="form-control">'
  $.each(primerJson, function(i, item) {
    primerSelect += '<option value="' + item.id + '">' + item.valor + '</option>'
  })
  primerSelect += '</select>';

  segundoSelect = '<select name="' + segundoId + '" id="' + segundoId + '" class="form-control">'
  $.each(segundoJson, function(i, item) {
    segundoSelect += '<option value="' + item.id + '">' + item.valor + '</option>'
  })
  segundoSelect += '</select>';

  swal.fire({
        title: 'Confirmar que desea utilizar los siguientes datos',
        html:'<div class="row formss">\
                <div class="col-md-12">\
                    <div class="card border-0 border-shadow-0 m-0" style="box-shadow:0px 0px 0px;">' +
                        '<div class="card-body">\
                            <form action="'+url+'" id="formflotant" method="POST" role="form" enctype="multipart/form-data" ><div class="row">'
                                + '<h5 class="mt-50">' + segundoTitulo + '</h5>'
                                + segundoSelect
                                + '<h5>' + primerTitulo + '</h5>'
                                + primerSelect
                                + '<input type="hidden" name="_method" value="'+url+'" />' +
                            '</form>\
                        </div>\
                    </div>\
                </div>\
            </div>',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00b8d9',
        cancelButtonColor: '#ef4a4a',
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar'
  }).then((result) =>{
        let valId = $('#' + primerId).val();
        let valPr = $('#' + segundoId).val();
        url += valId + '/' + valPr;
      if(result.value==true){
        $form = $('<form method="post" action="'+url+'"></form>');
        $form.append('<input type="hidden" name="_token" value="'+token+'">');
        $form.append('<input type="hidden" name="_method" value="POST">');
        $(document.body).append($form);
        console.log($form);
        $form.submit();
        loadingmig();
      }
  });
}

function vrv2(act,code,codeaux,actaux)
{
    var ves='',go='allow';
    $confe=act.substring(0, 5);
     ves=(code!=''&&code!=undefined)?'&cod='+code:'';
    ves+=(codeaux!=''&&codeaux!=undefined)?'&cdaux='+codeaux:'';
    ves+=(actaux!=''&&actaux!=undefined)?'&wh='+actaux.replace(' ', '+'):'';

        window.location='?target='+target+'&act='+act+ves;
}
function linkTo(link)
{
  window.location=link;
}
function locat(uere)
{
    window.location=uere;
}
function asignIcon(ic)
{
    $("#imag").val(ic);
}
function getWord(numChars)
{
    var word = "",
        i;
    for (i = 0; i < numChars; i++)
        word += chars.charAt(Math.floor(Math.random() * chars.length));
    return word;
}
function getWords(numWords, numCharsPerWord)
{
    var words = [],
        i;
    for (i = 0; i < numWords; i++)
        words.push(getWord(numCharsPerWord));
    return words.join(" ");
}
function imgError(image)
{
    image.onerror = "";
    image.src = "./images/not-available.jpg";
    return true;
}
function flotingblank(estu)
{
  let icons=['shield','archive','calendar','grid','hexagon',
            'home','layers','package','server','credit-card',
            'pie-chart','bar-chart',
             'clipboard','book','cloud','camera','columns',
             'video','database','bookmark','box','feather'];
  let $icons='';
  for(i=0;i<icons.length;i++){
    $icons+='<i class="ficon" data-feather="'+icons[i]+'" style="margin-right:5px;" onclick="asignIcon(\''+icons[i]+'\')"></i>';
  }
  // console.log($icons);
  $(estu).parent().parent().parent().append('<div class="iconsdib" style="">\
    <div class="row">\
      <div class="col-md-12">\
        <div class="card">\
          <div class="card-header">\
            <h4 class="card-title">Seleccionar icono</h4>\
            <div class="heading-elements">\
                <ul class="list-inline mb-0">\
                    <li>\
                        <a onclick="$(\'.iconsdib\').remove()"><i data-feather="x"></i></a>\
                    </li>\
                </ul>\
            </div>\
          </div>\
          <div class="card-content collapse show"><div class="card-body">'
          +$icons+'</div>\
        </div>\
     </div>\
    </div>');
    feather.replace({
        width: 14,
        height: 14
    });
}
function randomString(length, chars)
{
    var mask = '';
    if (chars.indexOf('a') > -1) mask += 'abcdefghijklmnopqrstuvwxyz';
    if (chars.indexOf('A') > -1) mask += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if (chars.indexOf('#') > -1) mask += '0123456789';
    if (chars.indexOf('!') > -1) mask += '~`!@#$%^&*()_+-={}[]:";\'<>?,./|\\';
    var result = '';
    for (var i = length; i > 0; --i) result += mask[Math.floor(Math.random() * mask.length)];
    return result;
}
