function areasedit(idapply,x,y){
    var editor = CKEDITOR.replace( idapply,{
	toolbar: [["PasteText"],["Bold","Italic","Underline"],
		  ["SelectAll","RemoveFormat"],
		  ["Link","Unlink"],["NumberedList","BulletedList"],
		  ["Undo","Redo"]],
	skin : "office2013",
	width : (x===undefined)?450:x ,
	height: (y===undefined)?250:y,
	ForcePasteAsPlainText:true,
	 pasteFromWordRemoveFontStyles:true,
	 removePlugins: "elementspath",
	 resize_enabled : false
    });
}
function areamedia(idapply,x,y) {
    CKEDITOR.disableAutoInline = true;
    var editor = CKEDITOR.inline( idapply,{
       toolbar: [
                 ['PasteText'],['Bold','Italic','Underline','Subscript','Superscript'],['SelectAll','RemoveFormat'],
                 ['Link','Unlink'],['NumberedList','BulletedList','Image'],
                 ['Undo','Redo']
              ],
        skin : 'office2013',
        width : (x===undefined)?450:x ,
        height: (y===undefined)?250:y,
        filebrowserUploadUrl : "js/ckeditor/upload.php",
        ForcePasteAsPlainText:true,
        pasteFromWordRemoveFontStyles:true,
        removePlugins: 'elementspath',
        resize_enabled : false
   });
   CKFinder.setupCKEditor(editor,{ basePath:"js/ckfinder/"});
 }
 function showhide(idclss) {
    (idclss.offsetParent === null?$(idclss).show():$(idclss).hide());
 }
function evaluadores(stosx,itemm,adsub){
    var $eval=false;
    //console.log(itemm);
    if ( $('.'+stosx).css("display")=='table-row') {
        $eval=true;
    }
    if (itemm=='0') {
        //alert("asdf");
        $('.subpartwo').hide();
    }else{
        $('.subparone').hide();
    }

    ($eval==false?$('.'+stosx).show():'');
}

function evaluadoresNew(stosx, itemm){
    var eval = false;
    if ( $('.' + stosx).css("display") == 'table-row') {
        eval = true;
    }
    if (itemm == 1) {
      $('.subparone').hide();
    }else if (itemm == 2){
      $('.subpartwo').hide();
    }else if (itemm == 3){
      $('.subparthree').hide();
    }

    eval == false ? $('.' + stosx).show() : '';
}

function closesub(dishide){
    $(dishide).hide();
}
function theadertable(trval,trp,vll)
{
    var tds,tdss='',spncls='';
    $('.theder').remove();
    switch (trp) {
	case 'listfistchild':{
	    tds=vll.f;
	    spncls='class="blanko"';
	}break;
	case 'listsecondchild':{
	    tds=vll.s;
	    spncls='class="blanko"';
	}break;
	default:{
	    tds=vll.z;
	}break;
    }
    for (var n in tds.thcnt) {
        colsp=(tds.cols!=undefined)?'colspan='+tds.cols[n]:'';
        tdss+='<th '+colsp+'><span '+spncls+'>'+tds.thcnt[n]+'</span></th>';
    }
    $(trval).before('<tr class="theder"><th></th>'+tdss+'</tr>');
}

function showinfocoll(cod,tis,yrmt) {
    $.post('control/ajax.php',{codever:cod,wre:tis,yrmts:yrmt},
	function(data){
        $codig=$.parseJSON(data);
        console.log(data);
        modifyfloat(cod,kad,$codig,"none");
        $('#coe').val($codig[cod][7]);
	}
	//,"json"
    );
}

function deletedi(jtl,tis,rr){
   var res=confirm('Confirmar eliminación');
   if(res==true){
    $('.loadinfndk').show();
        $.post('control/ajax.php',
           {tippst:'dlm',coddel:jtl,ondxr:rr},
           function(data){
            console.log(data);
                //$codig=$.parseJSON(data);
                if (data=='true'){
                    window.location.reload();
                }else{
                   if (data.search('quiniela_local_students')) {
                          alert('Este usuario ya tiene lecturas realizadas, no se puede eliminar.');
                      }else{
                          alert(data);
                      }
                }
        });
   }

}

function recorduno(cude,tip) {
   var arrdata=new Array(),vlrr=0,vol=0;
   $('.texts').each(function(){
         arrdata.push($(this).val());
   });
   if ($('.windowchild select').length>0) {
      $('.windowchild select').each(function(){
         arrdata.push($(this).val());
      });
   }
   $('.windowchild input[type="radio"]').each(function(){
      if ($(this).is(":checked")) {
         vlrr=$(this).val();
      }
   });

   $.post('control/ajax.php',
      {txts:arrdata,status:vlrr,tpe:tip,cd:cude},
      function(data){
         if (data!=''){
            alert("Hubo un error, vuelva a intentarlo"+ data);
            vol=1;
         }
      });
   //alert(vol);
   if(tip=='master'&&vol==0) {

      var grdos=new Array(),nvls=new Array(),sccns=new Array();
      $('.grds').each(function(){
         if ($(this).val()!='') {
            grdos.push($(this).val());
         }
      });
       $('.nvl').each(function(){
         if ($(this).val()!='') {
            nvls.push($(this).val());
         }
      });
       $('.sccn').each(function(){
         if ($(this).val()!='') {
            sccns.push($(this).val());
         }
      });
      $.post('control/ajax.php',
         {grado:grdos,nivel:nvls,section:sccns,maes:cude},
         function(data){
            if (data!=''){
               vol=1;
               alert("Hubo un error, vuelva a intentarlo"+ data);
            }
         }
      );
   }
   if (vol==0) {
      alert("Guardado");
      flotanteclose();
   }
}
function addgradus(){
    var grads=document.getElementById("grd"),
	levl=document.getElementById("lvl"),
	sect=document.getElementById("sctn"),
	gradscls=document.getElementsByClassName("grd"),
	levlcls=document.getElementsByClassName("lvl"),
	sectcls=document.getElementsByClassName("sctn"),
	nopass=0;

    for (ii=0;ii<gradscls.length;ii++) {
	//console.log(gradscls[ii]);
	//console.log(gradscls[ii].value);
	//if (gradscls[ii].value==grads.value&&levlcls[ii].value==levl.value&&sectcls[ii].value==sectcls.value) {
	//    nopass=1;
	//    alert('No pasa');
	//    break;
	//}
    }
    if(grads.value!=''&&levl.value!=''&&sect.value!=''&&nopass==0){
	$(".gradoma").append('\
	    <tr>\
	       <td><input type="text" class="grd"name="grd[]" value="'+grads.options[grads.selectedIndex].text+'"maxlength="2" readonly/></td>\
	       <td><input type="text" class="lvl"name="lvl[]" value="'+levl.options[levl.selectedIndex].text+'" readonly/></td>\
	       <td><input type="text"class="sctn"name="sctn[]"value="'+sect.value+'" class="twodigits" readonly/></td>\
	       <td><input type="button" value="-" onclick="$(this).parent().parent().remove();"></td>\
	   </tr>');
    }
}
function directcoord(tkpo) {
   //alert(tkpo);
   if (tkpo=='dr'&&$('#namemd').val()!=''
       ||tkpo=='coor'&&$('#namemd').val()!=''
       ||tkpo=='master'&&$('#namemd').val()!=''
       ||tkpo=='alumn'&&$('#namemd').val()!=''){
      if ($('#grado').length>0&&$('#grado').val()!=''&&$('#nivel').val()!=''&&$('#seccion').val()!='') {
         var jun=new Array($('#grado').val(),$('#nivel').val(),$('#seccion').val());
      }else{
         var jun=new Array();
      }
      $.post('control/ajax.php',
            {nameadd:$('#namemd').val(),codeadd:corm,ttipo:tkpo,addse:jun},
            function(data){
                if (data=='true') {
                    window.location.reload();
                }else{
                    alert(data);
                }
      });
   }
}
function addtag(va,idc,separator){
             if ($(idc).val()=='') {
                          ll=$(idc).val()+va.value;
             }else{
                          ll=$(idc).val()+separator+va.value;
             }
             $(idc).val(ll);
}


function delusersx(cods,estu,colna,$url,$idgrad){
    var ced='<tr><td><input type="button" name="alums" value="Elliminar de grado" class="btn btn-warning" onclick="locat(\'?act=delgradal&cod='+cods+'&url=?'+$url+'&gradm='+$idgrad+'\');" /></td>\
            <td><input type="button" name="maestrs" value="Eliminar alumno" class="btn btn-danger" onclick="deletedi(\''+cods+'\',this,\'alumn\');"/></td></tr>';
    newfloatv2(''+cods,ced,0,0);
    $('.titlewindow').html('Eliminar <i>'+colna+'</i>');
}


function delgradmaes(cods,estu,colna,$url,$idgrad){
    var ced='<tr><td><input type="button" name="alums" value="Elliminar de grado" onclick="locat(\'?act=delgradteach&cod='+cods+'&url=?'+$url+'&gradm='+$idgrad+'\');" /></td>';
    newfloatv2(''+cods,ced);
    $('.titlewindow').html('Eliminar <i>'+colna+'</i>');
}


function setid(idmm) {
    var idml='#'+idmm;
    window.location.hash =idml;
    //$('.listfistchild').removeClass('selem');
    //$('.listfistchild').addClass('selem');
}
function showtag() {
    var hashm=window.location.hash.substr(1).split("_");
    //console.log(window.location.hash.substr(1));
    if (window.location.hash.substr(1)!='') {
        var setm="#"+hashm[1];
        var enls="#"+hashm[0];
        if ($(enls).length>0) {
            $(enls).addClass("selem");
            $( setm ).trigger( "click" );

            if (hashm[2]=='0') {
                showinfocoll(hashm[0],'alumn',getParameterByName('yr'));
            }
        }
    }

}
function csvcompal(){
    swal({
            html:'<div class="table-responsive"><table  class="table table-striped table-hover" cellspacing="0" width="100%" style="width:100%"><tbody>\
                             <tr><td colspan="10" style="color:red;"> El archivo a subir debe estár en formato "csv", separado por comas.</td></tr>\
                    <tr><td>Usuario compartir,</td><td>Nombre,</td><td>primer apellido,</td><td>segundo apellido,</td><td>Codigo grado</td><td>;</td></tr>\
                    </tbody></table></div>',
            showCloseButton: true,
            confirmButtonText:"Aceptar",
             width:"55%"
        })

}
function csvcomptch(){
    swal({
            html:'<div class="table-responsive"><table  class="table table-striped table-hover" cellspacing="0" width="100%" style="width:100%"><tbody>\
                             <tr><td colspan="10" style="color:red;"> El archivo a subir debe estár en formato "csv", separado por comas.</td></tr>\
            <tr><td>Códio sección,</td><td>Nombre 1,</td><td>Apellido 1,</td><td>Apellido 2,</td><td>Usuario,</td><td>Nivel,</td><td>Grado,</td><td>Sección/Grupo</td><td>;</td></tr>\
                    </tbody></table></div>',
            showCloseButton: true,
            confirmButtonText:"Aceptar",
            width:"70%"
        })

}
function csvstms(){
    swal({
            html:'<div class="table-responsive"><table  class="table table-striped table-hover" cellspacing="0" width="100%" style="width:100%"><tbody>\
                    <tr><td colspan="5" style="color:red;"> El archivo a subir debe estár en formato "csv", separado por comas.</td></tr>\
                    <tr><td>Maestro1,</td><td>1ro,</td><td>Primaria,</td><td>A</td><td>;</td></tr>\
                    <tr><td>Maestro1,</td><td>2ro,</td><td>Primaria,</td><td>B</td><td>;</td></tr>\
                    <tr><td>Maestro1,</td><td>3ro,</td><td>Primaria,</td><td>B</td><td>;</td></tr>\
                    <tr><td>OtroMaestro,</td><td>1ro,</td><td>Secundaria,</td><td>A</td><td>;</td></tr>\
                    <tr><td>OtroMaestro,</td><td>2do,</td><td>Secundaria,</td><td>A</td><td>;</td></tr>\
                    <tr><td>OtroMaestro,</td><td>2do,</td><td>Secundaria,</td><td>B</td><td>;</td></tr>\
                    </tbody></table></div>',
            showCloseButton: true,
            confirmButtonText:"Aceptar",

        })

}
function csvstudn(){
    swal({	html:'<div class="table-responsive"><table  class="table table-striped table-hover" cellspacing="0" width="100%" style="width:100%"><tbody>\
                    <tr><td colspan="10" style="color:red;"> El archivo a subir debe estár en formato "csv", separado por comas.</td></tr>\
                        <tr><td>Alumno2</td><td>1ro</td><td>Primaria</td><td>A</td></tr>\
                        <tr><td>Alumno3</td><td>1ro</td><td>Primaria</td><td>A</td></tr>\
                        <tr><td>Alumno4</td><td>4to</td><td>Secundaria</td><td>C</td></tr>\
                        <tr><td>Alumno5</td><td>5to</td><td>Secundaria</td><td>C</td></tr>\
                        <tr><td>Alumno6</td><td>5to</td><td>Secundaria</td><td>B</td></tr>\
                    </tbody></table></div>',
            showCloseButton: true,
            confirmButtonText:"Aceptar"
        });
}
function exammaster(ell){
    $(".titlewindow").html("Ejemplo csv");
    flotingblank($("#"+ell+"ejem").html());
}

function clonando(){
        swal({
            position: "top-right",
            type: "success",
            title: "Procesando tarea, un momento por favor...",
            showConfirmButton: false,
            timer: 1500
          });
    }


function alertss($txt,$tl,$lnk){
    alert($tl+"\n"+$txt);
    window.location=$lnk;

}

let demo={
				showNotification(message,$type){
					types = ['info', 'success', 'warning', 'error'];
				notif({
					type: types[$type],
					msg: message,
					position: "right",
					timeout: 5000,
          multiline:true
				});
			}
		}
