tinyMCE.init({
        theme : "advanced",
        selector: "textarea#poster",
        skin:"o2k7",
        skin_variant:"black",
        width:"100%",
        external_image_list_url : ""+host_addr+"snippets/mceexternalimages.php",
        plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",
        content_css:""+host_addr+"stylesheets/mce.css",
        theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,fontselect,fontsizeselect,formatselect",
        theme_advanced_buttons2 : "cut,copy,paste,pasteWord|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,image,|,preview,|insertdate,inserttime,|,spellchecker,advhr,removeformat,|,sub,sup,|,charmap,emotions",
        theme_advanced_buttons3 : "",      
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_resizing : true
});
tinyMCE.init({
        theme : "advanced",
        selector: "textarea#adminposter",
        skin:"o2k7",
        skin_variant:"black",
        width:"100%",
        external_image_list_url : ""+host_addr+"snippets/mceexternalimages.php",
        plugins : "autolink,advlink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",
        content_css:""+host_addr+"stylesheets/mce.css",
        theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,fontselect,fontsizeselect,formatselect",
        theme_advanced_buttons2 : "cut,copy,paste,pasteWord|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,image,|,preview,|insertdate,inserttime,|,spellchecker,advhr,removeformat,|,sub,sup,|,charmap,emotions",
        theme_advanced_buttons3 : "",      
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_resizing : true
});
tinyMCE.init({
        theme : "advanced",
        selector:"textarea#postersmall",
	 	plugins : "autolink,advlink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",
        width:"100%",
        skin:"o2k7",
        skin_variant:"black",
        theme_advanced_buttons1 : "bold,italic,underline,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,fontselect,fontsizeselect,formatselect,|,link,unlink,outdent,indent,|,undo,redo,|,emotions",
        content_css:host_addr+"/stylesheets/mce.css",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "center",

});
tinyMCE.init({
        theme : "advanced",
        selector:"#postersmalltwo",
        plugins : "autolink,advlink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",
        width:"300px",
        skin:"o2k7",
        skin_variant:"black",
        theme_advanced_buttons1 : "bold,italic,underline,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,fontselect,fontsizeselect,formatselect,|,link,unlink,outdent,indent,|,undo,redo,|,emotions",
        content_css:""+host_addr+"stylesheets/mce.css",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "center",

});