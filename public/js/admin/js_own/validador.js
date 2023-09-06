var classdefault='',radconter=0;
function validar(allobj) {
  if (allobj==undefined) {
    allobj={'text':1};
  }
  var num=0,elem,arrad=new Array(),arradm=new Array(),arch=0,archm=0,chk='';
      elem = (allobj.form!=undefined)?document.getElementById(allobj.form):document.getElementById('formflotant');
  var classwarn= (allobj.clase!=undefined)?allobj.clase:'is-invalid state-invalid';
  var txt=elem.getElementsByTagName("input"),
      tarea=elem.getElementsByTagName("textarea"),
      sele=elem.getElementsByTagName("select"),
      txtcnt=0,rd=0,rady=0;
  for (i=0;i<txt.length;i++) {
       if (txt[i].type.toLowerCase()=="text"&&txt[i].getAttribute("omit")!='T'&&txt[i].value==''&&allobj.text==1) {
           num++;
           changecolor(txt[i],classwarn);
       }
        if (txt[i].type.toLowerCase()=="hidden"&&txt[i].getAttribute("omit")!='T'&&txt[i].value==''&&allobj.hidden==1) {
           num++;
       }
       if (txt[i].type.toLowerCase()=="password"&&txt[i].getAttribute("omit")!='T'&&txt[i].value==''&&allobj.pass==1) {
           num++;
           changecolor(txt[i],classwarn);
       }
       if (txt[i].type.toLowerCase()=="file"&&txt[i].getAttribute("omit")!='T'&&txt[i].value==''&&allobj.filex==1) {
           num++;
           changecolor(txt[i],classwarn);
       }
       if (txt[i].type.toLowerCase()=="radio"&&txt[i].getAttribute("omit")!='T'&&
            allobj.radio==1) {
            arrad[txt[i].getAttribute("name")]=(arrad[txt[i].getAttribute("name")]==undefined?1:arrad[txt[i].getAttribute("name")]+1);
            if (txt[i].checked == false) {
               arradm[txt[i].getAttribute("name")]=(arradm[txt[i].getAttribute("name")]==undefined?1:arradm[txt[i].getAttribute("name")]+1);
            }
       }
       if (txt[i].type.toLowerCase()=="checkbox"&&txt[i].getAttribute("omit")!='T'&&allobj.checkbox==1) {
            arch++;
            if (txt[i].checked == false) {
               archm++;
               chk=txt[i];
            }
       }
  }
  if (allobj.radio==1) {
               for (var mm in arrad) {
                            if (arrad[mm]==arradm[mm]) {
                                         num++;
                                         spelm=document.getElementsByName(mm)[0].parentNode
                                         changecolor(spelm,classwarn);
                            }
                            spelm=null;
               }
  }

  if (arch>0&&archm>0&&allobj.checkbox==1&&arch==archm) {
               num++;
               spelm=chk.parentNode
               changecolor(spelm,classwarn);
  }
  if (allobj.textarea==1) {
               for (ta=0;ta<tarea.length;ta++) {
                   if (tarea[ta].value==""&&tarea[ta].getAttribute("omit")!='T') {
                       num++;
                       changecolor(tarea[ta],classwarn)
                   }
               }
  }
  console.log(allobj.select);
  if (allobj.select==1) {
               for (sl=0;sl<sele.length;sl++) {
                   if (sele[sl].value==""&&sele[sl].getAttribute("omit")!='T') {
                       changecolor(sele[sl],classwarn);
                       num++;
                   }
               }
  }
  if (num==0&&elem.length>0) {

               // return true;
                 elem.submit();
                swal.fire({
               title: "Procesando",
               text: "Un momento por favor",
               imageUrl: "../images/cristales_p_f.gif",
               showConfirmButton: false,
               allowOutsideClick: false
             });


  }else{
    swal.fire({
         title: "Error",
         text: "Complete toda la informacíon, por favor.",
         showConfirmButton: true,
         allowOutsideClick: false
    });
      // console.log("Complete toda la informacíon, por favor.");
      return false;
  }
}
function  changecolor(elemnt,bno) {
 if (elemnt!=undefined&&elemnt.getAttribute("class")==null) {
  elemnt.className=bno;
  elemnt.addEventListener('change', function() {this.className =this.className.replace(bno,"")}, false);
 }else if (elemnt!=undefined&&elemnt.getAttribute("class")!=null) {
  elemnt.className = elemnt.className.replace(bno,"");
  elemnt.className = elemnt.className +" "+ bno;
  elemnt.addEventListener('change', function() {this.className =this.className.replace(bno,"")}, false);
 }
}
function valmail(vl) {
    var dos=vl.value
    if (/^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,4}$/.test(dos)){
        //preg(dos,'1');
    } else {
        jAlert("La dirección de email es incorrecta.",'Anfora');
        vl.focus();
        vl.value="";
    }
}
function checar(diss,mrk){
    var fst=new Array('Activado','Publicado','Si');
    var scd=new Array('Desactivado','No publicado','No');
	if(diss.checked==true){
        // document.getElementById('stat').innerHTML=fst[mrk];
        document.getElementById(diss.id).parentElement.getElementsByClassName('custom-switch-description')[0].innerHTML='Activado';
	}else{
	    document.getElementById(diss.id).parentElement.getElementsByClassName('custom-switch-description')[0].innerHTML='Desactivado';
	}
}
function valfile(tipoval,dis){
    var archivo1=dis.value,tipos='',summ=0,stops=0;
    var extension1 = (archivo1.substring(archivo1.lastIndexOf("."))).toLowerCase();
    for (var ii in tipoval) {
             if (extension1==tipoval[ii]&&stops==0){
                          tipos=tipoval[ii];
                          stops=1;
                          summ++;
             }
             tipos+=tipoval[ii]+', ';
    }
    if (summ==0){
        alert('Error de archivo, solo se permiten archivos  '+tipos);
        dis.value="";
        return false;
    }else{
        return true;
    }

}
