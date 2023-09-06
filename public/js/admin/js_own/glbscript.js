var filtcont=0,kk=0,heig='',ar='',mr=0,contpapel=0,docum=new Array(),venci=new Array();
var kos='',cad='',valare=null;
var chars = "0123456789abcdefghiklmnopqrstuvwxyz",nem=getWords('1','6');
/*************************************FICHAS*******************************************/                    
$(document).ready(function(){
             $('.pic tr').click(function(){
                          $('.pic tr').children('td, th').removeClass('color');
                          $(this).children('td, th').addClass('color');
             });
            
             if ($('#hours').length>0) {
                          $('#hours').timepicker({
                              showSecond: false,
                              timeFormat: 'HH:mm'
                          });
             }
             if ($('#date').length>0) {
                          $('#date').datepicker({
                              dateFormat: "dd-mm-yy",
                              showAnim: "slide",
                              numberOfMonths:2 
                          });
             }
             
             
             if ($('#hoursa').length>0) {
                          $('#hoursa').timepicker({
                              showSecond: false,
                              timeFormat: 'HH:mm'
                          });
             }
             if ($('#datesa').length>0) {
                          $('#datesa').datepicker({
                              dateFormat: "dd-mm-yy",
                              showAnim: "slide",
                              numberOfMonths:2 
                          });
             }
             $(".tableparent tr").mouseover(function(){
		    theadertable($(this),$(this).attr("class"),ovj);
             });
             $(".tableparent").mouseout(function(){
                 $(".theder").remove();
             });
             
  
});



function flotanteclose(){
             $('.close').parent().hide();
             $('.windowchild').html('');
             $('.fndwindow').hide();
             //$("body").css("overflow", "auto");
             $(".titlewindow").html("");
             $('.windowchild tfoot').show();
             document.body.style.overflow = 'auto';
}
function flotantecloseblank(){
             $('.windowedblank .windowchild').html('');
             $('.windowedblank').hide();
             $('.fndwindowblank').hide();
             //$("body").css("overflow", "auto");
             document.body.style.overflow = 'auto';
}



