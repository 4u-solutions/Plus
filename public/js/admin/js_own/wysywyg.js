function wysywyg(wd,hg,idclass,noimage){
    if ( typeof CKEDITOR == "undefined" ){
	    document.write(
		    '<strong><span style="color: #ff0000">Error</span>: CKEditor not found</strong>.' +
		    "This sample assumes that CKEditor (not included with CKFinder) is installed in" +
		    'the "/ckeditor/" path. If you have it installed in a different place, just edit' +
		    'this file, changing the wrong paths in the &lt;head&gt; (line 5) and the "BasePath"' +
		    "value (line 32)." ) ;
    }else{
	if ($("#"+idclass).length>0) {
                var optionss=[["PasteText"],["Bold","Italic","Underline","Subscript","Superscript"],["SelectAll","RemoveFormat"],
			["Link","Unlink"],["NumberedList","BulletedList",(noimage!=undefined?"Image":"-"),"-","Undo","Redo","-",
			"JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock", "-"]];
		var editor = CKEDITOR.replace( idclass,{
		    filebrowserUploadUrl : "js/ckeditor/upload.php",
		    toolbar: optionss,
		    skin : "office2013",
		    width : wd ,
		    height: hg,
		    ForcePasteAsPlainText:true,
		     pasteFromWordRemoveFontStyles:true,
		     removePlugins: "elementspath",
		     resize_enabled : false
		});
		CKFinder.setupCKEditor(editor,{ basePath:"js/ckfinder/"});	
	}
    }
}
function wysilectop(wd,hg,idclass,noimage){
    if ( typeof CKEDITOR == "undefined" ){
	    document.write(
		    '<strong><span style="color: #ff0000">Error</span>: CKEditor not found</strong>.' +
		    "This sample assumes that CKEditor (not included with CKFinder) is installed in" +
		    'the "/ckeditor/" path. If you have it installed in a different place, just edit' +
		    'this file, changing the wrong paths in the &lt;head&gt; (line 5) and the "BasePath"' +
		    "value (line 32)." ) ;
    }else{
	if ($(idclass).length>0) {
                var optionss=[["PasteText"],["Bold","Italic","Underline","Subscript","Superscript",(noimage!=undefined?"Image":"-")],["SelectAll","RemoveFormat"],
			["Undo","Redo"]];
		var editor = CKEDITOR.replace( idclass,{
		    filebrowserUploadUrl : "js/ckeditor/upload.php",
		    toolbar: optionss,
		    skin : "office2013",
		    width : wd ,
		    height: hg,
		    ForcePasteAsPlainText:true,
		     pasteFromWordRemoveFontStyles:true,
		     removePlugins: "elementspath",
		     resize_enabled : false
		});
		CKFinder.setupCKEditor(editor,{ basePath:"js/ckfinder/"});	
	}
    }
}