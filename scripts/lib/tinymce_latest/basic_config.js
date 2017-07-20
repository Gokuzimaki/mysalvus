// var host_addr="http://localhost/muyiwasblog/";
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
        theme : "modern",
        selector: "textarea#adminposter",
        skin:"inlite",
        width:"94%",
        height:"650px",
        external_image_list_url : ""+host_addr+"snippets/mceexternalimages.php",
        plugins : [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager codesample"
        ],
        // toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
        toolbar1: "undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
        toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code | codesample",
        image_advtab: true ,
        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
        content_css:[
            ""+host_addr+"stylesheets/fvtcss/color.css?"+ new Date().getTime(),
            ""+host_addr+"stylesheets/fvtcss/color-5.css?"+ new Date().getTime(),
            ""+host_addr+"stylesheets/fvtcss/custom.css?"+ new Date().getTime(),
            ""+host_addr+"stylesheets/mce.css?"+ new Date().getTime()
        ],
        external_filemanager_path:""+host_addr+"scripts/filemanager/",
        filemanager_title:"FVT's Admin Blog Content Filemanager" ,
        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
});
tinyMCE.init({
        theme : "modern",
        selector:"textarea#postersmall",
        menubar:false,
        statusbar: false,
        plugins : [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
        ],
        width:"100%",
        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
        toolbar2: "| link unlink anchor | emoticons",
        image_advtab: true ,
        content_css:""+host_addr+"stylesheets/mce.css",
        external_filemanager_path:""+host_addr+"scripts/filemanager/",
        filemanager_title:""+host_admin_title_name+" Content Filemanager" ,
        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
});

tinyMCE.init({
        theme : "modern",
        selector:"textarea#postersmalltwo",
        menubar:false,
        statusbar: false,
        plugins : [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
        ],
        width:"80%",
        height:"300px",
        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
        toolbar2: "| link unlink anchor | emoticons",
        image_advtab: true ,
        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
        content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
        external_filemanager_path:""+host_addr+"scripts/filemanager/",
        filemanager_title:""+host_admin_title_name+" Content Filemanager" ,
        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
});