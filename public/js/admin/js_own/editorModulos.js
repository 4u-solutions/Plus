elementsList = [];

currSelected = {};
currSelected.style = {};
currSelected.selected = false;

status = "";

urlMedia = "../gt01/" + actualTypoend + "/images/";
audiosPath = "../uploaded_files/audios/";

$(document).ready(function(){
    toolBoxDisplay('fondo', 'fondo');
    displayPreview();
    
    $('#side_toolbox').draggable({
        drag: function(event, ui) {
            var leftPosition = ui.position.left;
            var topPosition = ui.position.top;
            console.log(topPosition);
            var maxleft=695,maxtop=60;
            if (leftPosition > maxleft) {
              ui.position.left = maxleft;
            }else if (leftPosition < 0) {
              ui.position.left = 10;  
            }
            //else if (topPosition > maxtop) {
            //  ui.position.top = maxtop;
            //}
            else if (topPosition < 10) {
              ui.position.top = 10;  
            }
        }
    });
    
});

/*-------------------------------------TOOLBOX-------------------------------------*/

function toolBoxDisplay(stat, actbutton) {
    status = stat;
    toolBSPro();
    
    $('#toolbox_container').children().each(function(ind, elm){
        chid = $(elm).attr('id');
        $(this).removeClass("showdmenu");
        if (chid == stat){
            $(elm).addClass("showdmenu");
            
            if (stat == 'instr'){
                //$("#instr").addClass("showdmenu");
                currSelected = selectFromList('instr', true);
                
                if (currSelected != null) {
                    $('#instrsi').val( currSelected.DOMstylelm['font-size'] );
                    $('#instrpa').val( currSelected.DOMstylelm.padding );
                    $('#instrli-he').val( currSelected.DOMstylelm['line-height'] );
                    $('#instrcont').val(currSelected.textcontlm);
                    $('#instrcont').val(currSelected.textcontlm);
                }
            }
            
        }
        
        else{
        //$(elm).hide();
       }
    });
    
    if ($('#side_toolbox').attr('status')=='closed'){
        toolBoxOpen();
    }
    
    if (stat == "narra"){
        open_help_panel();
        toolBoxClose();
    }else{
        close_help_panel();
    }
    
    if (stat == "elemv"){
        $('#toolboxorg').show();
    }else{
        $('#toolboxorg').hide();
    }
    
    
    $('.selector_button').removeClass('sel_button_selected');
    if (actbutton == 'fondo'){
        $('#sel_butt_fondo').addClass('sel_button_selected');
    }else if (actbutton == 'elemv'){
        $('#sel_butt_elemv').addClass('sel_button_selected');
    }else{
        $(actbutton).addClass('sel_button_selected');
    }
    
}


function toolBoxOpen(){
    
    if ($('#side_toolbox').attr('status') == "closed"){
        //show("slide", { direction: "left" }, 1000);
        $('#side_toolbox').show("slide", { direction: "left" }, 800);
        $("#showvar").css({"background-color":"#35465c"});
        //.animate({
        //    width : '400px'
        //});
        $('#side_toolbox').attr('status', 'open');
        $('#side_toolbox').attr('onclick', '');
        $('.close_toolbox .material-icons').html("cancel");
    }
}


function toolBoxClose(){
    
    if ($('#side_toolbox').attr('status') == "open"){
        //$('#side_toolbox').hide({width : '20px'}, function (){});
        //$('#side_toolbox').attr('onclick', 'toolBoxOpen()');
        $('#side_toolbox').hide("slide", { direction: "left" }, 800);
        $("#showvar").css({"background-color":"#ff4500"});
        $('#side_toolbox').attr('status', 'closed');
        //$('.close_toolbox .material-icons').html("play_circle_filled_white");
    }
}


function toolBSOrg(){
    $('#toolboxorg').addClass('pesta_toolbox_selected');
    $('#toolboxpro').removeClass('pesta_toolbox_selected');
    $('#toolbox_container_order').addClass("showdmenu");
    $('#toolbox_container').removeClass("showdmenu");
    $('#instr,#narra,#elemv,#fondo').removeClass("showdmenu");
    
    configureSortable();
    
}


function toolBSPro(){
    $('#toolboxpro').addClass('pesta_toolbox_selected');
    $('#toolboxorg').removeClass('pesta_toolbox_selected');
    $('#toolbox_container').addClass("showdmenu");
    $('#toolbox_container_order').removeClass('showdmenu');
}

/*-------------------------------------------PREVIEW---------------------------------------*/
function displayPreview(){
    
    $.post("control/editorModulosAjax.php", 
        {"typ" : actualTypoend}, 
        null
        ,"json"
        )
    .done(
        function(data, textStatus, jqXHR){
            generateDOMs(data);
    }
    )
    .fail(
        function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR, textStatus, errorThrown);
    }
    );

}


function refreshChanges(args){
    args.typ = actualTypoend;
    args.mode = "refresh";

    $.post("control/editorModulosAjax.php", 
        args,
        null
        ,"json"
        )
    .done(
        function(data, textStatus, jqXHR){
            generateDOMs(data);
    }
    )
    .fail(
        function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR, textStatus, errorThrown);
    }
    );

}

function refreshAllChanges(){
    var args = {};
    args.typ = actualTypoend;
    args.mode = "refreshAll";
    args.multiple = elementsList;

    $.post("control/editorModulosAjax.php", 
        args,
        null
        ,"json"
        )
    .done(
        function(data, textStatus, jqXHR){
            generateDOMs(data);
    }
    )
    .fail(
        function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR, textStatus, errorThrown);
    }
    );

}



/*-------------------------------------ELEMENTS MODIFICATION-----------------------------*/
function selectFromList(id, category=false){
    
    if (category){
        for (var i = 0; i< elementsList.length; i++){
            if (elementsList[i].categlm == id){
                elementsList[i].selected = true;
                return elementsList[i];
                break;
            }
        }
        return null;
    
    } else {
        for (var i = 0; i<elementsList.length; i++){
            if (elementsList[i].DOMidlm == id){
                elementsList[i].selected = true;
                return elementsList[i];
                break;
            }
        }
    }
    
}


function saveChanges(elm){
    console.log(elm);
    elm.typ = actualTypoend;
    elm.mode = "save";
    
    $.post("control/editorModulosAjax.php", 
        elm, 
        null
        //,"json"
    )
    .fail(
        function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR, textStatus, errorThrown);
    }
    );

}


function selectBg(elm){
    elmid = $(elm).attr('id');
    
    if (elmid == 'fimage'){
        data = {categlm:"fondo", DOMconfiglm:"imagen"};
    }else{
        data = {categlm:"fondo", DOMconfiglm:"color"};
        //$("#colorPickerFondo").trigger("colorpickersliders.updateColor", currSelected.DOMstylelm.color);
        
    }
    data.scenelm = actualScene;
    refreshChanges(data);
}


function newTextElement(){
    var id = randId();
    var typ = actualTypoend;
    var sce = actualScene;
    var data = {categlm:"texto",
                scenelm:sce,
                DOMidlm:id,
                DOMstylelm : {"font-size": 20,
                            "padding-top" : 0,
                            "padding-left" : 0,
                            "padding-right" : 0,
                            "padding-bottom" : 0,
                            "line-height" : 14,
                            "border-radius" : 0,
                            top: 200,
                            left: 400,
                            width: 200,
                            "z-index":listSize},
                textcontlm : "Nuevo texto " + listSize
                };
        console.log(listSize);
    if (currSelected == null){
        currSelected = {};
        currSelected.DOMstylelm = {};
    }
    
    currSelected.changeMe = id;
    refreshChanges(data);
}

function newImageElement(){
    $('#image').show();
    $('#image_new').show();
    $('#image_prop').hide();
    $('#texto').hide();
    
    $('#newimagefileinput').trigger('click');
}

function newAudioElement(){
    console.log('this is audio');
    var id = randId();
    var typ = actualTypoend;
    var sce = actualScene;
    var data = {categlm:"audio",
                scenelm:sce,
                DOMidlm:id,
                DOMstylelm : {width: '69',
                            height: '69',
                            top: '150',
                            left: '400',
                            "z-index":listSize}
                };
    if (currSelected == null){
        currSelected = {};
        currSelected.DOMstylelm = {};
    }
    
    currSelected.changeMe = id;
    refreshChanges(data);
}


function deleteElement(){
    args = currSelected;
    args.typ = actualTypoend;
    args.mode = "delete";

    $.post("control/editorModulosAjax.php", 
        args,
        null
        ,"json"
        )
    .done(
        function(data, textStatus, jqXHR){
            generateDOMs(data);
    }
    )
    .fail(
        function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR, textStatus, errorThrown);
    }
    );
}


function selectMe(objm, directId = false){
    
    $('.global_element').removeClass('selected');
    $('.sortableElementsListli').removeClass('sortableElementsListSelected');
    
    if (!directId){
        var id = $(objm).attr('id');
    } else {
        var id = objm;
    }
    
    $('#' + id).addClass('selected');
    $('#li_' + id).addClass('sortableElementsListSelected');
    
    currSelected = selectFromList(id);
    
    if (currSelected.categlm == "texto"){
        $('#texto').show();
        
        $('#image').hide();
        $('#audio').hide();
        
        
        $('#txtcont').val(currSelected.textcontlm);
        $('#txtsi').val( currSelected.DOMstylelm['font-size'] );
        
        $('#txtpat').val( currSelected.DOMstylelm["padding-top"] );
        $('#txtpal').val( currSelected.DOMstylelm["padding-left"] );
        $('#txtpar').val( currSelected.DOMstylelm["padding-right"] );
        $('#txtpab').val( currSelected.DOMstylelm["padding-bottom"] );
        
        $('#txtli-he').val( currSelected.DOMstylelm['line-height'] );
        $('#txtbo-ra').val( currSelected.DOMstylelm['border-radius'] );
        
        $("#colorSelectorTextCl").trigger("colorpickersliders.updateColor", currSelected.DOMstylelm.color);
        $("#colorSelectorTextBg").trigger("colorpickersliders.updateColor", currSelected.DOMstylelm["background-color"]);
        
        
    }else if (currSelected.categlm == "image"){
        $('#image').show();
        $('#image_prop').show();
        
        $('#image_new').hide();
        $('#texto').hide();
        $('#audio').hide();
        
        
        $('#imgno').val(currSelected.textcontlm);
        $('#hiddenInputIdImage').val(id);

        $('#elementsOfMultipleImage').html('');
        
        if (currSelected.specslm == "multiple"){
            
            for (key in currSelected.namefilelm){
                num = Number(key) + 1;
                $('#elementsOfMultipleImage').append('<span onclick="selectMiniMe(' + key + ', \'' + id + '\');">Imagen:' + num + '</span>');
            }
            
        }
        
    }else if (currSelected.categlm == "audio"){
        $('#audio').show();

        $('#texto').hide();
        $('#image').hide();

        console.log(id);
        $('#hiddenInputIdAudio').val(id);
        $('#audiodesc').val(currSelected.textcontlm);

        if (currSelected.namefilelm != null) {
            $('#sourceAudio').attr('src', audiosPath + currSelected.namefilelm);
            $('#audioControl')[0].load();
        }else{
            $('#sourceAudio').attr('src', '');
            $('#audioControl')[0].load();
        }
    }
    
}

function selectMeAndProperties(objm){
    selectMe(objm);
    toolBoxDisplay('elemv', 'elemv');
}

function listSelectMe(id){
    $('.sortableElementsListli').removeClass('sortableElementsListSelected');
    $('#li_' + id).addClass('sortableElementsListSelected');
    selectMe(id, true)
}


function selectMiniMe(pos, id){
    url = urlMedia + currSelected.namefilelm[pos]
    $('#' + id).css('background-image', 'url(\'' + url + '\')');
}

function refreshMe(id){
    
    currSelected.DOMstylelm.width  = $('#' + id).css('width').replace(/[^-\d\.]/g, '');
    currSelected.DOMstylelm.height = $('#' + id).css('height').replace(/[^-\d\.]/g, '');
    currSelected.DOMstylelm.top  = $('#' + id).css('top').replace(/[^-\d\.]/g, '');
    currSelected.DOMstylelm.left = $('#' + id).css('left').replace(/[^-\d\.]/g, '');
}


function changeElement(args){

    if (args.categlm == "fondo"){
        args.typ = actualTypoend;
        args.scenelm = actualScene;
        saveChanges(args);
    }

    else if (args.categlm == "texto"){
        if (currSelected.selected){
            
            if (args.style_prop == "txtcont"){
                $('#' + currSelected.DOMidlm).children('#textc').html(args.value);
                currSelected.textcontlm = args.value;
            }
            else if (args.style_prop == "txtsi"){
                $('#' + currSelected.DOMidlm).css('font-size', args.value + "px");
                currSelected.DOMstylelm['font-size'] = args.value;
            }
            else if (args.style_prop == "txtpat"){
                $('#' + currSelected.DOMidlm).css('padding-top', args.value + "px");
                currSelected.DOMstylelm['padding-top'] = args.value;
            }
            else if (args.style_prop == "txtpal"){
                $('#' + currSelected.DOMidlm).css('padding-left', args.value + "px");
                currSelected.DOMstylelm['padding-left'] = args.value;
            }
            else if (args.style_prop == "txtpar"){
                $('#' + currSelected.DOMidlm).css('padding-right', args.value + "px");
                currSelected.DOMstylelm['padding-right'] = args.value;
            }
            else if (args.style_prop == "txtpab"){
                $('#' + currSelected.DOMidlm).css('padding-bottom', args.value + "px");
                currSelected.DOMstylelm['padding-bottom'] = args.value;
            }
            else if (args.style_prop == "txtli-he"){
                $('#' + currSelected.DOMidlm).css('line-height', args.value + "px");
                currSelected.DOMstylelm["line-height"] = args.value;
            }
            else if (args.style_prop == "txtbo-ra"){
                $('#' + currSelected.DOMidlm).css('border-radius', args.value + "px");
                currSelected.DOMstylelm["border-radius"] = args.value;
            }
            else if (args.style_prop == "txtcolor"){
                //$("#" + currSelected.DOMidlm).css("color", "#" + args.value);
                currSelected.DOMstylelm.color = args.value;
            }
            else if (args.style_prop == "txtbgcl"){
                //$("#" + currSelected.DOMidlm).css("background-color", "#" + args.value);
                currSelected.DOMstylelm["background-color"] = args.value;
            }
            
            saveChanges(currSelected);
        }
    }
        
    else if (args.categlm == "instr"){
        
        currSelected = selectFromList('instr', true);
        
        if (currSelected == null){
            currSelected = {scenelm:actualScene, categlm:"instr"};
            currSelected.DOMstylelm = {"font-size":20, padding:0, "line-height":20, color:"ffffff"}
        }
        
        if (args.style_prop == "instrsi"){
            $('#instructions_text').css('font-size', args.value + "px");
            currSelected.DOMstylelm['font-size'] = args.value;
        }
        else if (args.style_prop == "instrpa"){
            $('#instructions_text').css('padding', args.value + "px");
            currSelected.DOMstylelm.padding = args.value;
        }
        else if (args.style_prop == "instrli-he"){
            $('#instructions_text').css('line-height', args.value + "px");
            currSelected.DOMstylelm["line-height"] = args.value;
        }
        else if (args.style_prop == "instrcont"){
            $('#instructions_text').html(args.value);
            currSelected.textcontlm = args.value;
        }
        else if (args.style_prop == "instrcolor"){
            $('#instructions_text').css("color", "#" + args.value);
            currSelected.DOMstylelm.color = args.value;
        }
        
        saveChanges(currSelected);
        
    }
    
    else if (args.categlm == "narra"){
        
        currSelected = selectFromList('narra', true);
        
        if (currSelected == null){
            currSelected = {scenelm:actualScene, categlm:"narra"};
            currSelected.DOMstylelm = {}
        }
        
        currSelected.textcontlm = args.value;
        
        saveChanges(currSelected);
        
    }
    
    else if (args.categlm == "image"){
        if (currSelected.selected){
            if (args.style_prop == "imgno"){
                currSelected.textcontlm = args.value;
            }else if (args.style_prop == "imgcl"){
                currSelected.DOMClass = args.value;
            }
        }
        
        saveChanges(currSelected);
    }

    else if (args.categlm == "audio"){
        if (currSelected.selected){
            if (args.style_prop == "audiodesc"){
                currSelected.textcontlm = args.value;
                console.log(args.value);
            }
        }
        
        saveChanges(currSelected);
    }

}


function changeText(objm){
    
    if (currSelected.selected){
        var id = $(objm).attr('id');
        var val = $(objm).val();
        
        var args = {categlm:"texto", style_prop: id, value:val};
        
        changeElement(args);
    }
}


function changeImage(objm){
    
    if (currSelected.selected){
        var id = $(objm).attr('id');
        var val = $(objm).val();
        
        var args = {categlm:"image", style_prop: id, value:val};
        
        changeElement(args);
    }
}


function changeInstr(objm){
    
    var id = $(objm).attr('id');
    var val = $(objm).val();
    
    var args = {categlm:"instr", style_prop: id, value:val};
    
    changeElement(args);
}

function changeNarrat(objm){
    
    var id = $(objm).attr('id');
    var val = $(objm).val();
    
    var args = {categlm:"narra", style_prop: id, value:val};
    
    changeElement(args);
}


function changeAudio(objm){
    
    if (currSelected.selected){
        var id = $(objm).attr('id');
        var val = $(objm).val();
        
        var args = {categlm:"audio", style_prop: id, value:val};
        
        changeElement(args);
    }
}


function elementConstructorDOM(data){
    var id = data.DOMidlm;
    var style = data.DOMstylelm;
    cntStyle = styleParser(style);
    
    let drgrzb = " drgbl rszbl ";
    
    if (data.categlm == 'texto'){
        cad = '<div id="' + id + '" ';
        cad += 'class="text_element global_element ' + drgrzb + '" ';
        cad += 'onclick="selectMe(this);" ';
        cad += 'ondblclick="selectMeAndProperties(this);" ';
        cad += 'style="' + cntStyle + '" ';
        cad += '><div id="textc">';
        
        cad += data.textcontlm;
        
        cad += '</div></div>';
    }
    else if (data.categlm == 'image'){
        
        if (editorMode === "text only"){
        	drgrzb = " ";
    	}

        if (data.specslm == "multiple"){
            data.namefilelm = JSON.parse(data.namefilelm);
            nmfl = data.namefilelm[0];
            url = 'url(\'' + urlMedia + nmfl + '\'); ';
        }else{
            url = 'url(\'' + urlMedia + data.namefilelm + '\'); ';
        }
        
        cad = '<div id="' + id + '" ';
        cad += 'class="image_element global_element ' + drgrzb + '" ';
        cad += 'onclick="selectMe(this);" ';
        cad += 'ondblclick="selectMeAndProperties(this);" ';
        cad += 'style="background-image:' + url + cntStyle + '" ';
        cad += '></div>';
    }
    else if (data.categlm == "audio"){
        cad = '<div id="' + id + '" ';
        cad += 'class="audio_element global_element drgbl" ';
        cad += 'onclick="selectMe(this);" ';
        cad += 'ondblclick="selectMeAndProperties(this);" ';
        cad += 'style="background-image:url(./images/iconoaudio.png); ' + cntStyle + '" ';
        cad += '></div>';
    }
    
    return cad;
}


function styleParser(st){
    cad = "";
    for (var key in st) {
        if (st.hasOwnProperty(key)) {
            cad += key + ":" + parseCssUnitStyle(st[key], key) + "; ";
        }
    }
    
    return cad;
}

function parseCssUnitStyle(val, key) {
    pxval = ["width", "height",  "top", "left", "font-size", "padding", "padding-top", "padding-left", "padding-right", "padding-bottom", "line-height", "border-radius"];
    //colval = ["color", "background-color"];
    
    if ( pxval.includes(key) ){
        return val + "px";
    }else {
        return val;
    }
}



function generateDOMs(data){
    $('#main_preview').html('');
    elementsList = [];
    
    for (i = 0; i < data.length; i++){
        currElem = data[i];
        
        if (currElem.scenelm == actualScene){
            
            currElem.DOMstylelm = (currElem.DOMstylelm !== '') ? JSON.parse(currElem.DOMstylelm) : {};
            
            elementsList.push(currElem);
            
            cat = currElem.categlm;
            
            switch (cat){
                case "fondo":{
                    $url = 'url("' + urlMedia + currElem.namefilelm + '")';
                    
                    if (currElem.DOMconfiglm == 'imagen'){
                        $('#main_preview').css('background-image', $url);
                        $('#main_preview').css('background-color', '');
                        $("#fimage").prop("checked", true);
                    }else{
                        $('#main_preview').css('background-image', 'none');
                        $("#fcolor").prop("checked", true);
                        
                        if (currElem.DOMstylelm != null){
                            if (typeof currElem.DOMstylelm['background-color'] != 'undefined'){
                                $('#main_preview').css('background-color', currElem.DOMstylelm['background-color']);
                                $("#colorPickerFondo").trigger("colorpickersliders.updateColor", currElem.DOMstylelm['background-color']);
                            }
                        }
                    }
                    break;
                }
                
                case "texto":
                case "image":
                case "audio":
                {
                    var tElement = elementConstructorDOM(currElem);
                    $('#main_preview').append(tElement);
                    break;
                }
                
                case "instr":{
                    var ces = currElem.DOMstylelm;
                    $('#instructions_text').html(currElem.textcontlm);
                        
                    if (ces != null){
                        for (var key in ces){
                            if (ces.hasOwnProperty(key)){
                                $('#instructions_text').css( key, parseCssUnitStyle(ces[key], key) );
                            }
                        }
                    }
                    
                    break;
                }
                
                
                case "narra":{
                    
                    $("#summernote").summernote("code", currElem.textcontlm);
                    
                    break;
                }
                
            }
        }
    }
    
    $('.drgbl').draggable({
        stop : function (event, ui){
            id = $(event.target).attr('id');
            currSelected = selectFromList(id);
            
            currSelected.selected = true;
            
            refreshMe(currSelected.DOMidlm);
            saveChanges(currSelected);
        }
    });
    
    $('.rszbl').resizable({
        stop: function (event, ui){            
            id = $(event.target).attr('id');
            currSelected = selectFromList(id);
            currSelected.selected = true;
            
            refreshMe(currSelected.DOMidlm);
            saveChanges(currSelected);
        }
    });
    
    
    //Quick fixes
    $('.global_element').css('position', 'absolute');
    $('.elemv_category').hide();
    
    if (typeof(currSelected.changeMe) != "undefined"){
        selectMe(currSelected.changeMe, true);
    }
    
    
    //sortables
    configureSortable();
}



function configureSortable(){
    //select only text and image
    var srtblList = [];
    for (i = 0; i < elementsList.length; i++) {
        cElem = elementsList[i];
        if (cElem.categlm == "texto" || cElem.categlm == "image"){
            srtblList.push(cElem);
        }
    }
    
    
    //order by z-index
    listSize = srtblList.length;
    $('#hiddenInputConfigImage').val(listSize);
    
    for (j = 0; j < listSize; j++) {
        for (i = 0; i < listSize-1; i++) {
            cElem = srtblList[i];
            nElem = srtblList[i+1];
            
            if (Number(cElem.DOMstylelm["z-index"]) > Number(nElem.DOMstylelm["z-index"])) {
                tElem = nElem
                srtblList[i+1] = cElem;
                srtblList[i] = tElem;
            }
        }
    }
    
    
    
    //establish new z-index from relative order
    var gnrlList = [];
    for (i = 0; i < listSize; i++) {
        cElem = srtblList[i];
        cElem.DOMstylelm["z-index"] = i;
        gnrlList[i] = cElem;
    }
    
    
    //inverse list for display purpose
    var displayList = [];
    for (i = listSize - 1; i >= 0; i--) {
        cElem = gnrlList[i];
        displayList.push(cElem);
    }
    
    
    $('#sortableElementsList').html('');
    
    for (i = 0; i < displayList.length; i++) {
        var currElem = displayList[i];
        
        if (currElem.categlm == "texto"){
            tipo = '<i class="material-icons">font_download</i> ' + currElem.textcontlm;
        }
        else if (currElem.categlm == "image"){
            tipo = '<i class="material-icons">image</i> ' + currElem.textcontlm;
        }
        
        listElem = '<li id="li_' + currElem.DOMidlm + '" onclick="listSelectMe(\'' + currElem.DOMidlm + '\');" class="sortableElementsListli">' + tipo + ' </li>';
        
        $('#sortableElementsList').append(listElem);
        
    }
    
    $('#sortableElementsList').sortable({
        stop:function(event, ui){
            
            idsOrder = $('#sortableElementsList').sortable("toArray");
            for (i = 0; i < idsOrder.length; i++){
                actid = idsOrder[i].substring(3);
                console.log(actid);
                currS = selectFromList(actid);
                
                currS.DOMstylelm['z-index'] = (idsOrder.length - (i+1));
            }
            refreshAllChanges();
            
            
        }
    });
    
    
    

}

/*-----------------------------------RANDOM FUNCTIONS--------------------------------------------*/
function randId(){
    var abc = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
    var id = "";

    for (i = 0; i < 5; i++){
        id += abc[randInt(0, (abc.length-1))];
    }
    
    return id;
}


function checkrand(){

    a = [];
    cont = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

    for (i=0; i<=100; i++){
        a.push (randInt(1, 10));
    }

    for (i=0; i<=100; i++){
        for (j=1; j<=10; j++){
            if (a[i] == j){
                cont[j] ++;
            }
        }
    }
    
}


function randInt(min, max){
    var rnd = Math.round( (Math.random() * (max-min)) + min ) ;
    return rnd;
}