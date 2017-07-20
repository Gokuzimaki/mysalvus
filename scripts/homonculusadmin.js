function searchComment(blogid){
  var searchval=$('input[name=minisearch'+blogid+']').val(); 
  var presentcontent=searchval.replace(/\s\s*/g,"");
  if(searchval!==""&&presentcontent!==""){
    searchval=="*fullcommentsview*"?searchval="gwolcomments":searchval=searchval;
    console.log(searchval,presentcontent);
    var searchcomreq=new Request();
    searchcomreq.generate('div#formend div[name=commentfullhold'+blogid+']',true);
    //enter the url
    searchcomreq.url('../snippets/display.php?displaytype=searchcomments&searchval='+searchval+'&blogid='+blogid+'&extraval=admin');
    //send request
    searchcomreq.opensend('GET');
    //update dom when finished, takes four params targetElement,entryType,entryEffect,period
    searchcomreq.update('div#formend div[name=commentfullhold'+blogid+']','html','fadeIn',1000);
  }
}
$(document).ready(function(){
hideBind("div[name=closefullcontenthold]","#fullbackground","fadeOut",1000,"","");
hideBind("div[name=closefullcontenthold]","#fullcontenthold","fadeOut",1000,"","");
hideBind("div[name=closefullcontenthold]","#fullcontent","html",1000,"","");
hideBind("div[name=closefullcontenthold]","#fullcontentheader","html",1000,"","");
hideBind("div[name=closefullcontenthold]","#fullcontentdetails","html",1000,"","");
hideBind("div[name=closefullcontenthold]","#fullcontentheader","fadeOut",1000,"","");
hideBind("div[name=closefullcontenthold]","#fullcontentdetails","fadeOut",1000,"","");
hideBind("div[name=closefullcontenthold]","#fullcontentpointerhold","fadeOut",1000,"","");
$('div[name=closefullcontenthold]').on("click",function(){
$('#fullcontent').attr("style","");
});
  var help=new Array();
  help['blogtype']='Create/Edit a new Blog variant, please endeavour to create associated categories. For complete web integration, contact your developer';
  help['blogcategory']='Create/Edit blog categories for created blog types here, default category associated with a blog type is (ALL) project fix nigeria blog categories are unique as they allow you upload images along with them, CSI Outreach has no business here as all its categories are placed under(Teachings)';
  help['blogpost']='Create/Edit Blog entries, here you can create a blog post, after creating it you can then upload seperate media content for them';
  help['media']='Create/Edit blog post media, these media are what will be made available to you in the course of posting a new blog entry using the giving text area editor tool, uploaded media here will be available for insertion directly into your blog post, but be careful, when you delete a media here ensure to check the blogs it has been used in and remove the media from there too, in order to avoid........wahala!!!';
  help['qotd']='Create/Edit Quote of The Day entries here, bare in mind you can create for the home page and the project fix nigeria page seperately';
  help['booking']='Check out who/which bookings have been made here, click on the Edit Bookings sublink to get at the details';
  help['gallery']='Create/Edit photo galleries here';
  help['events']='Create/Edit events here, the events are created per blog and also for the frontiers consulting page, simply click the new event sublink to view the form.';
  help['trendingtopics']='Create/Edit Trending Topics for the Frontiers Consulting page ';
  help['toptenplaylist']='Create/Edit music on the top ten playlist for the CSI outreach page here, you can only upload one audio file at a time, do ensure that it is in mp3 format';
  help['servicerequests']='View service requests for frontiers consulting';
  help['subscriptions']='Check out the number of subscribers and their email details here on a blog basis';
  help['testimonies']='View and approve testimonies submitted via the CSI Outreach page, to approve a testimony simply give it an appropriate display title';
  help['comments']='View and Edit all comments posted on the website here, you can search and make sure swear words or in appropriate content are not on the website';
  help['adverts']='View and Edit the adverts that will show up on the website blog post pages when they are viewed';
  help['onlineradio']='Setup and manage program schedules, events, and the radio online status for Frontiers Radio ';
$(document).on("click","div#menulinkcontainer a[data-type=mainlink]",function(){
var linkname=$(this).attr("data-name");
$('div#contentdisplayhold').html(help[''+linkname+'']);
});
$(document).on("blur","select[name=editblogcategory]",function(){
var theval=$(this).val();
console.log(theval);
if(theval!==""){
var editcatreq=new Request();
  editcatreq.generate('#contentdisplayhold,section.content',true);
  //enter the url
  var url='../snippets/display.php?displaytype=editblogcategorymain&blogtypeid='+theval+'&extraval=admin';
  editcatreq.url('../snippets/display.php?displaytype=editblogcategorymain&blogtypeid='+theval+'&extraval=admin');
  //send request
  editcatreq.opensend('GET');
  //update dom when finished, takes four params targetElement,entryType,entryEffect,period
  editcatreq.update('div#contentdisplayhold,section.content','html','fadeIn',1000);
}else{
  
}

});
/*$(document).on("click","div#menulinkcontainer a[data-type=sublink]",function(){
var linkname=$(this).attr("data-name");
//$('div#contentdisplayhold').html(help[''+linkname+'']);
var sublinkreq=new Request();
  sublinkreq.generate('#contentdisplayhold,section.content',true);
  //enter the url
  sublinkreq.url('../snippets/display.php?displaytype='+linkname+'&extraval=admin');
  //send request
  sublinkreq.opensend('GET');
  //update dom when finished, takes four params targetElement,entryType,entryEffect,period
  sublinkreq.update('div#contentdisplayhold,section.content','html','fadeIn',1000);
});*/



$(document).on("click","input[name=viewblogposts]",function(){
  var theid=$('select[name=blogtypeid]').val();
  var secondid=$('select[name=blogcategoryid]').val();
  if(theid!==""){
  var blogpostreq=new Request();
    blogpostreq.generate('#contentdisplayhold,section.content',false);
    //enter the url
    blogpostreq.url('../snippets/display.php?displaytype=viewblogposts&blogtypeid='+theid+'&blogcategoryid='+secondid+'&extraval=admin');
    //send request
    blogpostreq.opensend('GET');
    //update dom when finished, takes four params targetElement,entryType,entryEffect,period
    blogpostreq.update('div#contentdisplayhold,section.content','html','fadeIn',1000);

  }else{
    
  }
});

$(document).on("change","select[name=blogtypeid]",function(){
  var theid=$(this).val();
  var parent=$(this).parent().parent().parent().parent();
  
  var target=parent.find('select[name=blogcategoryid]');
  var item_loader=target.parent().find('.loadermask');
  // console.log("target: ",target," loader: ",item_loader);
  item_loader.removeClass('hidden');
  if(theid==""){
    target.val("");
    target.html("<option value=''>--Choose a Blog Type First--</option>");
    target.select2({
      theme:'bootstrap',
      placeholder: '--Choose a Blog Type First--'
    });
    item_loader.addClass('hidden');
  }
  // send ajax request to verify email existing in database
  var url = '' + host_addr + 'snippets/display.php';
  var opts = { 
      type: 'GET',
      url: url,
      data: {
          displaytype: 'getblogcategories',
          blogtypeid: theid,
          retval: "json",
          extraval: "admin"
      },
      // dataType: 'json',
      success: function(output) {
          // console.log(endtarget);
          console.log(output);
          // item_loader.className += ' hidden';
          item_loader.addClass('hidden');
          target.html(output);
          // item_loader.remove();
          /*if (output.success == "true") {
            
          }else if(output.success == "false"){
            console.log(output);
          }
          target.val("");
          target.select2({
            theme:'bootstrap',
            placeholder: '--Choose a Blog Type First--'
          });*/
      },
      error: function(error) {
          if (typeof (error) == "object") {
              console.log(error.responseText);
          }else{
              console.log("Error: ",error);
          }
          var errmsg = "Sorry, something went wrong, possibl&&&& your internet connect is inactive, we apologise if this is from our end. Try the action again";
          // item_loader.remove();
          item_loader.addClass('hidden');
          // item_loader.className += ' hidden';
          raiseMainModal('Failure!!', '' + errmsg + '', 'fail');
          // alert("Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again ");
      }
  };
  // console.log("In here");
  if(theid>0){
    $.ajax(opts);
  } else{
    item_loader.addClass('hidden');

  } 
});

$(document).on("click","input[data-type=submitcrt]",function(){
      // console.log(this);
      var formname='div[name='+this.name+']';
      
      var displaytype=$(this).attr("name");

      var datamap=$(''+formname+' input[name=datamap]').val();

      var cursmap=JSON.parse(datamap.replace(/'{1,}/g,'"'));

      // ensure to attach the '_crt' to any custom viewtype you want in your 
      // map to allow the system function accurately with it
      if(displaytype=="create"||displaytype=="edit"){
        cursmap.vt=displaytype;
      }else{
        cursmap.vt=displaytype+"_crt";
        
      }
      // only for the categroies section
      // proceed to add all data-crt elements present in the form to the cursmap
      // json object
      var tlen=$(''+formname+'').find('input[data-crt=true],select[data-crt=true],textarea[data-crt=true]').length;
      var tdata=$(''+formname+'').find('input[data-crt=true], select[data-crt=true], textarea[data-crt=true]');
      var str="";
      if(tlen>0){
        for(var i=0; i<tlen ; i++){
          var curdata=tdata.get(i);
          str+='cursmap.'+curdata.name+'=$("'+formname+' '+curdata.localName+'[name='+curdata.name+']").val();';
          // console.log(str);
        }
        eval(str);
      }

      var doajax="true";
      
      var datamap=JSON.stringify(cursmap);


      var renderpoint=$(''+formname+' div.renderpoint');
      var item_loader=$(''+formname+' div.loadmask');
      var target=$(''+formname+' div.renderpoint');
      item_loader.removeClass('hidden');

      // console.log("itemloader: ",item_loader);
      // console.log("datamap: ",datamap);
      // send ajax request to verify email existing in database
      var url = '' + host_addr + 'snippets/display.php';
      var opts = {
          type: 'GET',
          url: url,
          data: {
              displaytype: "_gdunit",
              datamap: datamap,
              retval: "json",
              extraval: "admin"
          },
          dataType: 'json',
          success: function(output) {
              // console.log(endtarget);
              // console.log(output);
              // item_loader.className += ' hidden';
              item_loader.addClass('hidden');
              // item_loader.remove();
              if (output.success == "true") {
                
                target.html(output.catdata);
              }else if(output.success == "false"){
                console.log(output);
                var errmsg = "Apologies, no results were found for you current request. Either no data exists or you have not provided necessary information to the application";
                raiseMainModal('Failure!!', '' + errmsg + '', 'fail');
                target.html("");
              }
          },
          error: function(error) {
              if (typeof (error) == "object") {
                  console.log(error.responseText);
              }else{
                  console.log("Error: ",error);
              }
              var errmsg = "Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again";
              // item_loader.remove();
              item_loader.addClass('hidden');
              // item_loader.className += ' hidden';
              raiseMainModal('Failure!!', '' + errmsg + '', 'fail');
              // alert("Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again ");
          }
      };
      if(doajax=="true"){
        $.ajax(opts);
        
      }else{
        item_loader.addClass('hidden');

      }

      // console.log("In here");
})

$(document).on("click","input[name=viewblogpostsoptional]",function(){
  var theid=$('select[name=blogtypeid]').val();
  var secondid=$('select[name=blogcategoryid]').val();
  var typeout=$('input[name=varianttype]').val();
  if(typeout==""||typeout=="undefined"){
    typeout="viewblogposts";
  }
  if(theid!==""){
  var blogpostreq=new Request();
    blogpostreq.generate('#contentdisplayhold,section.content',false);
    //enter the url
    blogpostreq.url('../snippets/display.php?displaytype=viewblogpostsoptional&blogtypeid='+theid+'&blogcategoryid='+secondid+'&extraval='+typeout+'');
    //send request
    blogpostreq.opensend('GET');
    //update dom when finished, takes four params targetElement,entryType,entryEffect,period
    blogpostreq.update('div#contentdisplayhold,section.content','html','fadeIn',1000);

  }else{
    
  }
});
$(document).on("click","input[name=viewsubscribers]",function(){

  var theid=$('select[name=blogtypeid]').val();
  var secondid=$('select[name=blogcategoryid]').val();
  if(theid!==""){
    var blogpostreq=new Request();
    blogpostreq.generate('#contentdisplayhold,section.content',false);
    //enter the url
    blogpostreq.url('../snippets/display.php?displaytype=viewsubscribers&blogtypeid='+theid+'&blogcategoryid='+secondid+'&extraval=admin');
    //send request
    blogpostreq.opensend('GET');
    //update dom when finished, takes four params targetElement,entryType,entryEffect,period
    blogpostreq.update('div#contentdisplayhold,section.content','html','fadeIn',1000);

  }else{
    
  }
});
$(document).on("click","input[name=viewqotd]",function(){

var theid=$('select[name=qotdcat]').val();
// var secondid=$('select[name=blogcategoryid]').val();
if(theid!==""){
var qotdcatreq=new Request();
  qotdcatreq.generate('#contentdisplayhold,section.content',false);
  //enter the url
  qotdcatreq.url('../snippets/display.php?displaytype=viewqotd&qotdcat='+theid+'&extraval=admin');
  //send request
  qotdcatreq.opensend('GET');
  //update dom when finished, takes four params targetElement,entryType,entryEffect,period
  qotdcatreq.update('div#contentdisplayhold,section.content','html','fadeIn',1000);
}else{
  
}
});
$(document).on("click","input[name=viewadverts]",function(){
var theid=$('select[name=advertcat]').val();
// var secondid=$('select[name=blogcategoryid]').val();
if(theid!==""){
var advertscatreq=new Request();
  advertscatreq.generate('#contentdisplayhold,section.content',false);
  //enter the url
  advertscatreq.url('../snippets/display.php?displaytype=viewadverts&advertcat='+theid+'&extraval=admin');
  //send request
  advertscatreq.opensend('GET');
  //update dom when finished, takes four params targetElement,entryType,entryEffect,period
  advertscatreq.update('div#contentdisplayhold,section.content','html','fadeIn',1000);
}else{

}
});

$(document).on("click","input[name=viewadverts]",function(){
var theid=$('select[name=advertcat]').val();
// var secondid=$('select[name=blogcategoryid]').val();
if(theid!==""){
var advertscatreq=new Request();
  advertscatreq.generate('#contentdisplayhold,section.content',false);
  //enter the url
  advertscatreq.url('../snippets/display.php?displaytype=viewadverts&advertcat='+theid+'&extraval=admin');
  //send request
  advertscatreq.opensend('GET');
  //update dom when finished, takes four params targetElement,entryType,entryEffect,period
  advertscatreq.update('div#contentdisplayhold,section.content','html','fadeIn',1000);
}else{

}
});
$(document).on("click","input[name=viewstores]",function(){
var theid=$('select[name=storecat]').val();
// var secondid=$('select[name=blogcategoryid]').val();
if(theid!==""){
var advertscatreq=new Request();
  advertscatreq.generate('#contentdisplayhold,section.content',true);
  //enter the url
  advertscatreq.url('../snippets/display.php?displaytype=viewstores&storecat='+theid+'&extraval=admin');
  //send request
  advertscatreq.opensend('GET');
  //update dom when finished, takes four params targetElement,entryType,entryEffect,period
  advertscatreq.update('div#contentdisplayhold,section.content','html','fadeIn',1000);
}else{

}
});
$(document).on("click","input[name=viewevent]",function(){
var theid=$('select[name=eventcat]').val();
// var secondid=$('select[name=blogcategoryid]').val();
if(theid!==""){
var eventcatreq=new Request();
  eventcatreq.generate('#contentdisplayhold,section.content',false);
  //enter the url
  eventcatreq.url('../snippets/display.php?displaytype=viewevent&eventcat='+theid+'&extraval=admin');
  //send request
  eventcatreq.opensend('GET');
  //update dom when finished, takes four params targetElement,entryType,entryEffect,period
  eventcatreq.update('div#contentdisplayhold,section.content','html','fadeIn',1000);

}else{
  
}
});
$(document).on("click","a[name=morecatposts]",function(){
var catid=$(this).attr("data-catid");
var nextid=$(this).attr("data-next");
if(nextid>0){
var blogcatpostreq=new Request();
  blogcatpostreq.generate('div[name=waitinghold]',true);
  //enter the url
  blogcatpostreq.url('../snippets/display.php?displaytype=viewblogposts&blogtypeid='+theid+'&blogcategoryid='+secondid+'&extraval=admin');
  //send request
  blogcatpostreq.opensend('GET');
  //update dom when finished, takes four params targetElement,entryType,entryEffect,period
  blogcatpostreq.update('div#contentdisplayhold div:last,section.content div:last','insertAfter','fadeIn',1000);
  
}else{
  
}
});
$(document).on("click","a[name=removecomment]",function(){
var cid=$(this).attr("data-cid");
var comremreq=new Request();
  comremreq.generate('div[name=minicommentsearchhold] div[data-id='+cid+']',false);
  //enter the url
  comremreq.url('../snippets/display.php?displaytype=removecomment&cid='+cid+'&extraval=admin');
  //send request
  comremreq.opensend('GET');
  //update dom when finished, takes four params targetElement,entryType,entryEffect,period
  comremreq.update('div[name=minicommentsearchhold] div[data-id='+cid+']','html','',0);
  $('div[name=minicommentsearchhold] div[data-id='+cid+']').fadeOut(500); 
  console.log($('div[name=minicommentsearchhold] div[data-id='+cid+']'));
  });
$(document).on("click","td[name=trcontrolpoint] a",function(){
  if(tinymce){
    /*tinymce.EditorManager.execCommand('mceRemoveEditor',true, "adminposter");
    tinymce.EditorManager.execCommand('mceRemoveEditor',true, "postersmall");
    tinymce.EditorManager.execCommand('mceRemoveEditor',true, "postersmallone");
    tinymce.EditorManager.execCommand('mceRemoveEditor',true, "postersmalltwo");
    tinymce.EditorManager.execCommand('mceRemoveEditor',true, "postersmallthree");
    tinymce.EditorManager.execCommand('mceRemoveEditor',true, "postersmallfour");
    tinymce.EditorManager.execCommand('mceRemoveEditor',true, "postersmallfive");*/
    /*tinymce.EditorManager.execCommand('mceRemoveEditor',true, "postersmallsix");
    tinymce.EditorManager.execCommand('mceRemoveEditor',true, "postersmallseven");
    tinymce.EditorManager.execCommand('mceRemoveEditor',true, "postersmalleight");
    tinymce.EditorManager.execCommand('mceRemoveEditor',true, "postersmallnine");
    tinymce.EditorManager.execCommand('mceRemoveEditor',true, "postersmallten");
    tinymce.EditorManager.execCommand('mceRemoveEditor',true, "postersmalleleven");
    tinymce.EditorManager.execCommand('mceRemoveEditor',true, "postersmalltwelve");*/
    
  }
var linkname=$(this).attr("name");
var linkid=$(this).attr("data-divid");
// console.log(linkid,linkname);
if(linkname=="disablecomment"){
$(this).attr({"name":"reactivatecomment","data-type":"reactivatecomment"}).text('Reactivate');
$("td[name=commentstatus"+linkid+"]").text("disabled");
}else if(linkname=="activatecomment"||linkname=="reactivatecomment"){
$(this).attr({"name":"disablecomment","data-type":"disablecomment"}).text('Disable');
$("td[name=commentstatus"+linkid+"]").text("active");

}else if(linkname=="editsingleservicerequest"||linkname=="editsinglebooking"){
$("td[name=servicestatus"+linkid+"]").text("active");
$("td[name=bookingstatus"+linkid+"]").text("active");
}else if(linkname=="disablesubscriber"){
  $(this).attr({"name":"activatesubscriber","data-type":"activatesubscriber"}).text('activate');
$("td[name=subscriptionstatus"+linkid+"]").text("inactive");
}else if(linkname=="activatesubscriber"){
$(this).attr({"name":"disablesubscriber","data-type":"disablesubscriber"}).text('disable');
$("td[name=subscriptionstatus"+linkid+"]").text("active");
}
});

$(document).on("click","input[type=checkbox]",function(){
var datastate=$(this).attr("data-state");
if(datastate=="off"){
$(this).attr("data-state","on");
}else{
$(this).attr("data-state","off");
}
});

$(document).on("click",'#editimgsoptionlinks a',function(){
var linkname=$(this).attr('name');
var linkid=$(this).attr('data-id');
   var albumreq=new Request();
  var albumlinkreq=new Request();
if(linkname=="deletepic"){
//   $('div[name=profimg'+;linkid+']').css("display","none");
var confirm=window.confirm('Are you sure you want to delete this, click "OK" to delete this or "Cancel" to stop')
if(confirm===true){
  albumlinkreq.generate('#fullcontent',false);
  albumlinkreq.url(''+host_addr+'snippets/display.php?displaytype='+linkname+'&extraval='+linkid+'');
  //send request
  albumlinkreq.opensend('GET');
  albumlinkreq.update('#fullcontent','none','none',0);  
  $('div[name=albumimg'+linkid+']').fadeOut(500).html("");
var galid=$(this).attr("data-galleryid");
var thesrc=$(this).attr("data-src");
var galname=$(this).attr("data-galleryname");
galname==""||typeof(galname)=="undefined"?galname="gallerydata":galname=galname;
var galleryimgsrcs=$('input[name='+galname+''+galid+']').attr('data-images');
var galleryimgsizes=$('input[name='+galname+''+galid+']').attr('data-sizes');
var posterpoint=$(this).attr('data-arraypoint');
galleryimgsrcsarray=galleryimgsrcs.split(">");
galleryimgsizesarray=galleryimgsizes.split("|");
var id=$.inArray(thesrc,galleryimgsrcsarray);
var dlength=galleryimgsrcsarray.length;
var newimgsrcs="";
var newsizes="";
for(var t=0;t<dlength;t++){
if(t!==id){
  newimgsrcs==""?newimgsrcs+=galleryimgsrcsarray[t]:newimgsrcs+="]"+galleryimgsrcsarray[t];
  newsizes==""?newsizes+=galleryimgsizesarray[t]:newsizes+="|"+galleryimgsizesarray[t];
}
}
/*$('input[name='+galname+''+galid+']').attr('data-images',''+newimgsrcs+'');
$('input[name='+galname+''+galid+']').attr('data-sizes',''+newsizes+'');*/
var galname=$(this).attr("data-galleryname");
galname==""||typeof(galname)=="undefined"?galname="gallerydata":galname=galname;
var galleryimgsrcs=$('input[name='+galname+''+galid+']').attr('data-images');
var galleryimgsizes=$('input[name='+galname+''+galid+']').attr('data-sizes');
var posterpoint=$(this).attr('data-arraypoint');
var galleryimgsrcsarray=galleryimgsrcs.split(">");
var galleryimgsizesarray=galleryimgsizes.split("|");
var dlength=galleryimgsrcsarray.length;
$('input[name='+galname+''+galid+']').attr({'data-images':''+newimgsrcs+'','data-sizes':''+newsizes+''});
/*$('input[name=gallerycount]').attr('value',''+dlength+'');
$('input[name=currentgalleryview]').attr('value','');
$('input[name=curgallerydata]').attr('data-images',''+newimgsrcs+'');
$('input[name=curgallerydata]').attr('data-sizes',''+newsizes+'');*/
var tlength=$('div[name=galleryfullholder'+galid+']').find("a[name=deletepic]").length;
console.log(id,tlength);
for(var i=0;i<tlength;i++){
var curpoint=$('div[name=galleryfullholder'+galid+']').find("a[name=deletepic]")[i].attributes[4].value;
if(curpoint>posterpoint){
var newpoint=curpoint-1;
$('div[name=galleryfullholder'+galid+']').find("a[name=deletepic]")[i].attributes[4].value=newpoint;
$('div[name=galleryfullholder'+galid+']').find("a[name=viewpic]")[i].attributes[4].value=newpoint;
}
}  
}

}else if (linkname=="viewpic") {
 $('#fullcontent img[name=fullcontentwait]').show();
// var gallery_name=$('input[name=bloggallerydata]').attr('title');
var gallery_name="";
// var gallery_details=$('input[name=bloggallerydata]').attr('data-details');
var posterpoint=$(this).attr('data-arraypoint');
var galleryimgsrcsarray=new Array();
var galleryimgsizesarray=new Array();
var galid=$(this).attr("data-galleryid");
var galname=$(this).attr("data-galleryname");
galname==""||typeof(galname)=="undefined"?galname="gallerydata":galname=galname;
console.log($('input[name='+galname+''+galid+']'),'input[name='+galname+''+galid+']');
var galleryimgsrcs=$('input[name='+galname+''+galid+']').attr('data-images');
var galleryimgsizes=$('input[name='+galname+''+galid+']').attr('data-sizes');
var galleryimgsrcsarray=galleryimgsrcs.split(">");
var galleryimgsizesarray=galleryimgsizes.split("|");
var posterimg=galleryimgsrcsarray[posterpoint];
var gallerydata="";
var gallerytotal=galleryimgsrcsarray.length-1;
gallery_name+='<input type="hidden" name="gallerycount" value="'+gallerytotal+'"/><input type="hidden" name="currentgalleryview" value="'+posterpoint+'"/><input type="hidden" name="curgallerydata" data-images="'+galleryimgsrcs+'" data-sizes="'+galleryimgsizes+'" data-galleryname="'+galname+'" value=""/>';
if(galleryimgsrcsarray.length>1){
for(var i=0;i<galleryimgsrcsarray.length;i++){
// console.log(galleryimgsrcsarray[i],galleryimgsizesarray[i],posterimg);
}
var prevpoint=Math.floor(posterpoint)-1;
var nextpoint=Math.floor(posterpoint)+1;
prevpoint<0?prevpoint=0:prevpoint=prevpoint;
console.log(prevpoint,nextpoint);
nextpoint>=galleryimgsrcsarray.length?nextpoint=galleryimgsrcsarray.length-1:nextpoint=nextpoint;
$('img[name=pointleft]').attr("data-point",""+prevpoint+"");
$('img[name=pointright]').attr("data-point",""+nextpoint+"");
}
var cursize=galleryimgsizesarray[posterpoint].split(',');
var imgwidth=Math.floor(cursize[0]);
var imgheight=Math.floor(cursize[1]);
var contwidth=$('#fullcontent').width();
var contheight=$('#fullcontent').height();
contwidth=Math.floor(contwidth);
contheight=Math.floor(contheight);
var outs= new Array();
outs=produceImageFitSize(imgwidth,imgheight,960,700,"off");
var firstcontent='<div id="closecontainer" name="closefullcontenthold" data-id="theid" class="altcloseposfour"><img src="'+host_addr+'images/closefirst.png" title="Close"class="total"/></div>'+
'<img src="'+posterimg+'" name="galleryimgdisplay" style="'+outs['style']+'" title="'+gallery_name+'"/>'+
'<img src="'+host_addr+'images/waiting.gif" name="fullcontentwait" style="margin-top:285px;margin-left:428px;z-index:80;">'
;
$('#fullcontent').html(""+firstcontent+"");
$('#fullcontentheader').html(gallery_name);
// $('#fullcontentdetails').html(gallery_details);
var topdistance=$(window).scrollTop();
// console.log(topdistance);
if(topdistance>100){
  var pointerpos=topdistance+100;
$('#fullcontent').css("margin-top",""+topdistance+"px");
$('#fullcontentpointerhold').css("margin-top",""+topdistance+"px");
}else{
$('#fullcontent').css("margin-top","0px");
$('#fullcontentpointerhold').css("margin-top","0px");
}

$('#fullbackground').fadeIn(1000);
$('#fullcontenthold').fadeIn(1000);
$('#fullcontent').fadeIn(1000);
$('#fullcontentheader').fadeIn(1000);
$('#fullcontentdetails').fadeIn(1000);
$('#fullcontentpointerhold').fadeIn(1000);
$('img[name=galleryimgdisplay]').load(function(){
$('#fullcontent img[name=fullcontentwait]').hide();
});

}else{
  
}
});

 $(document).on("blur",'div#formend select[name=photocount]',function(){
    //window.alert('event called');
    var photocount=$(this).val();
    var photocountmain=photocount;
    var curpics=$('input[name=piccount]').val();
    console.log(curpics,this,photocount)
    var totoptions='<option value="">--add More?--</option>';
    if(curpics==""||curpics<1){
    while(photocount>0){
      $('<br><br><input type="file" class="curved" name="defaultpic'+photocount+'"/>').insertAfter('#formend select[name=photocount]');
      photocount--;
    }
  //update the current number of photo fields displayed
    $('input[name=piccount]').attr('value',''+photocountmain+'');
  //update selection options
  var totpics=10-Math.floor(photocountmain);
  var rempics=Math.floor(totpics);
  console.log(rempics,photocount);
   if(rempics>0){
    //updates the selection box for he remainning possible photo uploads
    while(rempics>0){
    totoptions+='<option value="'+rempics+'">'+rempics+' Photos</option>';   
    // $('<option value="'+rempics+'">'+rempics+' Photos</option>').insertBefore('select[name=photocount] option');      
    rempics--;
    }
    $(this).html(totoptions);
  }else{
  totoptions='<option value="">Max Of 10</option>';
  $(this).html(totoptions);    
  }
}else{
//
var photoentry;
while(photocount>0){
photoentry=Math.floor(photocount)+Math.floor(curpics);
      $('<br><br><input type="file" class="curved" name="defaultpic'+photoentry+'"/>').insertAfter('select[name=photocount]');
    photocount--;
}
console.log("In here");
  var totpics=Math.floor(curpics)+Math.floor(photocountmain);
  var checkpicleft=10-totpics;
  var rempics=checkpicleft;
  console.log(rempics,totpics);
  $('input[name=piccount]').attr('value',''+totpics+'');
  if(rempics>0){
    while(rempics>0){
    totoptions+='<option value="'+rempics+'">'+rempics+' Photos</option>';   
    // $('<option value="'+rempics+'">'+rempics+' Photos</option>').insertBefore('select[name=photocount] option');      
    rempics--;
    }
    $(this).html(totoptions);
  }else{
  totoptions='<option value="">Max Of 10</option>';
  $(this).html(totoptions);
  }
}
  });
$(document).on("click","#formend a, a[data-content-type=addcontent]",function(){
  var linkname=$(this).attr('name');
  var targetcontainer=$(this).attr('data-target');
  var branchcount=$('input[name=containercount]').val();
  var nextcount=Math.floor(branchcount)+1;
  if(linkname=="childadd"){
    var branchcount=$('input[name=childcount]').val();
    var nextcount=Math.floor(branchcount)+1;
    // console.log(nextcount,branchcount);
    if(nextcount<=5){

    var outs='<div class="small-6 column">'+
    '                Childs Name and Birthday('+nextcount+') *'+
    '                <input name="child'+nextcount+'"  placeholder="Childs name and birthday ('+nextcount+')" class="curved"/>'+
    '             </div>';
    $(outs).insertAfter('div[name=childrenhold] div:last');
    //selection.appendTo(outs);
    $('input[name=childcount]').attr('value',''+nextcount+'');
    }else{
      window.alert('Max of five entries, thank you');
    }
  }
})
$(document).on("blur","form[name*=sermon] select[name=audiotype],form[name*=sermon] select[name=audiotype]",function(){
})

$('#fullcontenthold img[name=pointleft]').on("click",function(){
var totalcount=$('#fullcontentheader input[name=gallerycount]').attr("value");
var currentview= $('#fullcontentheader input[name=currentgalleryview]').attr("value");
var galleryimgsrcsarray=new Array();
var galleryimgsizesarray=new Array();
var galleryimgsrcs=$('#fullcontentheader input[name=curgallerydata]').attr('data-images');
var galleryimgsizes=$('#fullcontentheader input[name=curgallerydata]').attr('data-sizes');
/*var galname=$('#fullcontentheader input[name=curgallerydata]').attr('data-galleryname');
galname==""||typeof(galname)=="undefined"?galname="gallerydata":galname=galname;*/
galleryimgsrcsarray=galleryimgsrcs.split(">");
galleryimgsizesarray=galleryimgsizes.split("|");
var nextview;
console.log(currentview,totalcount);
if(Math.floor(currentview)<=Math.floor(totalcount)){
nextview=Math.floor(currentview)-1;
console.log(nextview);
//nextview works in array index format meaning 0 holds a valid position
if(nextview>-1&&nextview<=totalcount){
  $('#fullcontent img[name=fullcontentwait]').show();
  $('div#fullcontent img[name=galleryimgdisplay]').attr("src","").hide();
var nextimg=galleryimgsrcsarray[nextview];
console.log(nextview,nextimg);
var cursize=galleryimgsizesarray[nextview].split(',');
var imgwidth=Math.floor(cursize[0]);
var imgheight=Math.floor(cursize[1]);
var contwidth=$('#fullcontent').width();
var contheight=$('#fullcontent').height();
contwidth=Math.floor(contwidth);
contheight=Math.floor(contheight);
var outs= new Array();
outs=produceImageFitSize(imgwidth,imgheight,960,700,"off");

$('#fullcontent img[name=galleryimgdisplay]').attr({"src":""+nextimg+"","style":""+outs['style']+""}).load(function(){
$(this).fadeIn(1000);
$('#fullcontent img[name=fullcontentwait]').hide();
});
$('#fullcontentheader input[name=currentgalleryview]').attr("value",""+nextview+"");
}
}
});

$('#fullcontentpointerright img[name=pointright]').on("click",function(){
var totalcount=Math.floor($('#fullcontentheader input[name=gallerycount]').attr("value"));
var currentview=Math.floor($('#fullcontentheader input[name=currentgalleryview]').attr("value"));
var galleryimgsrcsarray=new Array();
var galleryimgsizesarray=new Array();
var galleryimgsrcs=$('#fullcontentheader input[name=curgallerydata]').attr('data-images');
var galleryimgsizes=$('#fullcontentheader input[name=curgallerydata]').attr('data-sizes');
galleryimgsrcsarray=galleryimgsrcs.split(">");
galleryimgsizesarray=galleryimgsizes.split("|");
var nextview;
console.log($(this).attr("name"),totalcount);
if(currentview<=totalcount){
nextview=Math.floor(currentview)+1;
//nextview works in array index format meaning 0 holds a valid position
if(nextview>-1&&nextview<=totalcount){
$('#fullcontent img[name=fullcontentwait]').show();
$('div#fullcontent img[name=galleryimgdisplay]').attr("src","").hide();
$('#fullcontent img[name=galleryimgdisplay]').attr({"src":""+host_addr+"images/waiting.gif","style":"margin-top:285px;margin-left:428px;"});
var nextimg=galleryimgsrcsarray[nextview];
console.log(nextview,nextimg);
var cursize=galleryimgsizesarray[nextview].split(',');
var imgwidth=Math.floor(cursize[0]);
var imgheight=Math.floor(cursize[1]);
var contwidth=$('#fullcontent').width();
var contheight=$('#fullcontent').height();
contwidth=Math.floor(contwidth);
contheight=Math.floor(contheight);
var outs= new Array();
outs=produceImageFitSize(imgwidth,imgheight,960,700,"off");
$('#fullcontent img[name=galleryimgdisplay]').attr({"src":""+nextimg+"","style":""+outs['style']+""}).load(function(){
$('#fullcontent img[name=fullcontentwait]').hide();
$(this).fadeIn(1000);
});
$('#fullcontentheader input[name=currentgalleryview]').attr("value",""+nextview+"");
}
}

});

$(document).on("click","#contentdisplayhold table td audio",function(){
// console.log($('#contentdisplayhold table td audio'),$('#contentdisplayhold table td audio').length);
for(var i=0;i<=$('#contentdisplayhold table td audio').length;i++){
  // check if the current audio element has buffered information
  var loadtest=$('#contentdisplayhold table td audio')[i].buffered.length;
  if(loadtest==1 && $('#contentdisplayhold table td audio')[i]!==this){
    $('#contentdisplayhold table td audio')[i].pause();
    $('#contentdisplayhold table td audio')[i].currentTime = 0;
  }
}
// $(this).addClass('activeaudio');

});
/*LTE custom options controller*/
/**/
/*end*/
// multiple markup entries point
    $(document).on("click", "a[name=addextrabannerslide]", function() {
        var branchcount = $('input[name=curbannerslidecount]').val();
        var nextcount = Math.floor(branchcount) + 1;
        // var nextcountout=nextcount-3;
        // var nextcountmain=nextcount-1;
        if (nextcount <= 10) {
            var outs = '<div class="col-xs-6">' 
            + '  <div class="form-group">' 
            + '    <label>Select Slide Image(Preferrably 1920x460 or 1170x460):</label>' 
            + '    <div class="input-group">' 
            + '      <div class="input-group-addon">' 
            + '        <i class="fa fa-image"></i>' 
            + '      </div>' 
            + '      <input type="file" class="form-control" name="slide' + nextcount + '" Placeholder=""/>' 
            + '    </div><!-- /.input group -->' 
            + '  </div><!-- /.form group -->' 
            + '<div class="form-group">' 
            + '  <label>Header Caption(Large size caption):</label>' 
            + '  <div class="input-group">' 
            + '  <div class="input-group-addon">' 
            + '        <i class="fa fa-image"></i>' 
            + '      </div>' 
            + '      <input type="text" class="form-control" name="headercaption' + nextcount + '" Placeholder=""/>' 
            + '    </div><!-- /.input group -->' 
            + '  </div><!-- /.form group -->' 
            + '  <div class="form-group">' 
            + '    <label>Mini Caption(Small size caption):</label>' 
            + '    <div class="input-group">' 
            + '      <div class="input-group-addon">' 
            + '        <i class="fa fa-image"></i>' 
            + '      </div>' 
            + '      <input type="text" class="form-control" name="minicaption' + nextcount + '" Placeholder=""/>' 
            + '    </div><!-- /.input group -->' 
            + '  </div><!-- /.form group -->' 
            + '<div class="form-group">' 
            + '   <label>link Address(for links in the caption):</label>' 
            + '    <div class="input-group">' 
            + '      <div class="input-group-addon">' 
            + '        <i class="fa fa-file-text"></i>' 
            + '      </div>' 
            + '      <input type="text" class="form-control" name="linkaddress' + nextcount + '" Placeholder=""/>' 
            + '    </div><!-- /.input group -->' 
            + '  </div><!-- /.form group -->' 
            + '  <div class="form-group">' 
            + '    <label>link Title(The text the link displays):</label>' 
            + '    <div class="input-group">' 
            + '      <div class="input-group-addon">' 
            + '        <i class="fa fa-file-text"></i>' 
            + '      </div>' 
            + '      <input type="text" class="form-control" name="linktitle' + nextcount + '" Placeholder=""/>' 
            + '    </div><!-- /.input group -->' 
            + '  </div><!-- /.form group -->' 
            + '</div>';
            // console.log(nextcount, outs);
            $(outs).insertBefore('div[name=entrybannerslidepoint]');
            //selection.appendTo(outs);
            $('input[name=curbannerslidecount]').attr('value', '' + nextcount + '');
        } else {
            window.alert("maximum of ten slides please");
        }
    })  
    $(document).on("click", "a[name=addextrateamslide]", function() {
        var branchcount = $('input[name=curteamslidecount]').val();
        var nextcount = Math.floor(branchcount) + 1;
        // var nextcountout=nextcount-3;
        // var nextcountmain=nextcount-1;
        if (nextcount <= 10) {
            var outs = '<div class="col-xs-6">' 
            + '  <div class="form-group">' 
            + '    <label>Select Slide Image(Preferrably 1920x460 or 1170x460):</label>' 
            + '    <div class="input-group">' 
            + '      <div class="input-group-addon">' 
            + '        <i class="fa fa-image"></i>' 
            + '      </div>' 
            + '      <input type="file" class="form-control" name="slide' + nextcount + '" Placeholder=""/>' 
            + '    </div><!-- /.input group -->' 
            + '  </div><!-- /.form group -->' 
            + '<div class="form-group">' 
            + '  <label>Member Name:</label>' 
            + '  <div class="input-group">' 
            + '  <div class="input-group-addon">' 
            + '        <i class="fa fa-image"></i>' 
            + '      </div>' 
            + '      <input type="text" class="form-control" name="fullname' + nextcount + '" Placeholder=""/>' 
            + '    </div><!-- /.input group -->' 
            + '  </div><!-- /.form group -->' 
            + '  <div class="form-group">' 
            + '    <label>Member Position</label>' 
            + '    <div class="input-group">' 
            + '      <div class="input-group-addon">' 
            + '        <i class="fa fa-image"></i>' 
            + '      </div>' 
            + '      <input type="text" class="form-control" name="position' + nextcount + '" Placeholder=""/>' 
            + '    </div><!-- /.input group -->' 
            + '  </div><!-- /.form group -->' 
            + '  <div class="form-group">' 
            + '    <label>Qualifications:</label>' 
            + '    <div class="input-group">' 
            + '      <div class="input-group-addon">' 
            + '        <i class="fa fa-image"></i>' 
            + '      </div>' 
            + '      <input type="text" class="form-control" name="qualifications' + nextcount + '" Placeholder=""/>' 
            + '    </div><!-- /.input group -->' 
            + '  </div><!-- /.form group -->' 
            + '<div class="form-group">' 
            + '   <label>Member Details</label>' 
            + '    <div class="input-group">' 
            + '      <div class="input-group-addon">' 
            + '        <i class="fa fa-file-text"></i>' 
            + '      </div>' 
            + '      <textarea rows="4" class="form-control" name="details' + nextcount + '" id="postersmallthree" Placeholder="Place the team members details here"/>' 
            + '    </div><!-- /.input group -->' 
            + '  </div><!-- /.form group -->' 
            + '</div>';
            // console.log(nextcount, outs);
            $(outs).insertBefore('div[name=entryteamslidepoint]');
            //selection.appendTo(outs);
            $('input[name=curteamslidecount]').attr('value', '' + nextcount + '');
        } else {
            window.alert("maximum of ten slides please");
        }
    })
    $(document).on("click", "a[name=addextraclientelleslide],a[name=addextrarecommendationslide],a[name=addextratestimonialslide]", function() {
        var entrypointtype = "entryrecommendationslidepoint";
        var fullnamedisplay = "";
        var contentpicdisplay = "";
        var pwebsitedisplay = "";
        var companynamedisplay = "";
        var cwebsitedisplay = "";
        var positiondisplay = "";
        var contentdisplay = "";
        var formelemcountertitle = "currecommendationslidecount";
        var formelemcountertrigger = "addextrarecommendationslide";
        var formelemsubmittrigger = "recommendationsubmit";
        var branchcounttype = $('input[name=currecommendationslidecount]');
        if ($(this).attr("name") == "addextraclientelleslide") {
            fullnamedisplay = "display:none;";
            contentpicdisplay = "";
            pwebsitedisplay = "display:none;";
            companynamedisplay = "";
            cwebsitedisplay = "";
            positiondisplay = "display:none;";
            contentdisplay = "";
            formelemcountertitle = "curclientelelslidecount";
            formelemcountertrigger = "addextraclientelleslide";
            formelemsubmittrigger = "clientsubmit";
            branchcounttype = $('input[name=curclientelleslidecount]');
            entrypointtype = "entryclientelleslidepoint";
        } else if ($(this).attr("name") == "addextratestimonialslide") {
            fullnamedisplay = "";
            contentpicdisplay = "";
            pwebsitedisplay = "";
            companynamedisplay = "";
            cwebsitedisplay = "";
            positiondisplay = "";
            contentdisplay = "";
            formelemcountertitle = "curtestimonialslidecount";
            formelemcountertrigger = "addextratestimonialslide";
            formelemsubmittrigger = "testimonialsubmit";
            branchcounttype = $('input[name=curtestimonialslidecount]');
            entrypointtype = "entrytestimonialslidepoint";
        }
        var branchcount = branchcounttype.val();
        var nextcount = Math.floor(branchcount) + 1;
        // var nextcountout=nextcount-3;
        // var nextcountmain=nextcount-1;
        console.log(nextcount, branchcounttype, branchcount, this.name);
        if (nextcount <= 10) {
            var outs = '<div class="col-md-6">' 
            + '  <div class="form-group" style="' + contentpicdisplay + '">' 
            + '    <label>Select Image:</label>' 
            + '    <div class="input-group">' 
            + '      <div class="input-group-addon">' 
            + '        <i class="fa fa-file-text"></i>' 
            + '      </div>' 
            + '      <input type="file" class="form-control" name="slide' + nextcount + '" Placeholder=""/>' 
            + '    </div><!-- /.input group -->' 
            + '  </div><!-- /.form group -->' 
            + '<div class="form-group" style="' + fullnamedisplay + '">' 
            + '  <label>Fullname:</label>' 
            + '  <div class="input-group">' 
            + '  <div class="input-group-addon">' 
            + '        <i class="fa fa-image"></i>' 
            + '      </div>' 
            + '      <input type="text" class="form-control" name="fullname' + nextcount + '" Placeholder="Fullname"/>' 
            + '    </div><!-- /.input group -->' 
            + '  </div><!-- /.form group -->' 
            + '  <div class="form-group" style="' + positiondisplay + '">' 
            + '    <label>Position</label>' 
            + '    <div class="input-group">' 
            + '      <div class="input-group-addon">' 
            + '        <i class="fa fa-file-text"></i>' 
            + '      </div>' 
            + '      <input type="text" class="form-control" name="position' + nextcount + '" Placeholder="Position"/>' 
            + '    </div><!-- /.input group -->' 
            + '  </div><!-- /.form group -->' 
            + '  <div class="form-group" style="' + pwebsitedisplay + '">' 
            + '    <label>Personal Website</label>' 
            + '    <div class="input-group">' 
            + '      <div class="input-group-addon">' 
            + '        <i class="fa fa-file-text"></i>' 
            + '      </div>' 
            + '      <input type="text" class="form-control" name="personalwebsite' + nextcount + '" Placeholder="Personal Website"/>' 
            + '    </div><!-- /.input group -->' 
            + '  </div><!-- /.form group -->' 
            + '  <div class="form-group" style="' + companynamedisplay + '">' 
            + '    <label>Company Name</label>' 
            + '    <div class="input-group">' 
            + '      <div class="input-group-addon">' 
            + '        <i class="fa fa-image"></i>' 
            + '      </div>' 
            + '      <input type="text" class="form-control" name="companyname' + nextcount + '" Placeholder="Company name"/>' 
            + '    </div><!-- /.input group -->' 
            + '  </div><!-- /.form group -->' 
            + '  <div class="form-group" style="' + cwebsitedisplay + '">' 
            + '    <label>Company Website</label>' 
            + '    <div class="input-group">' 
            + '      <div class="input-group-addon">' 
            + '        <i class="fa fa-image"></i>' 
            + '      </div>' 
            + '      <input type="text" class="form-control" name="companywebsite' + nextcount + '" Placeholder="Company Website"/>' 
            + '    </div><!-- /.input group -->' 
            + '  </div><!-- /.form group -->' 
            + '<div class="form-group" style="' + contentdisplay + '">' 
            + '   <label>Details</label>' 
            + '    <div class="input-group">' 
            + '      <div class="input-group-addon">' 
            + '        <i class="fa fa-file-text"></i>' 
            + '      </div>' 
            + '      <textarea rows="4" class="form-control" name="details' + nextcount + '" id="' + entrypointtype + 'postersmall' + nextcount + '" Placeholder="Place the team members details here"/>' 
            + '    </div><!-- /.input group -->' 
            + '  </div><!-- /.form group -->' 
            + '<script>' 
            + '    tinyMCE.init({' 
            + '        theme : "modern",' 
            + '        selector:"textarea#' + entrypointtype + 'postersmall' + nextcount + '",' 
            + '        menubar:false,' 
            + '        statusbar: false,' 
            + '        plugins : [' 
            + '         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",' 
            + '         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",' 
            + '         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"' 
            + '        ],' 
            + '        width:"100%",' 
            + '        height:"auto",' 
            + '        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",' 
            + '        toolbar2: "| responsivefilemanager | link unlink anchor | emoticons",' 
            + '        image_advtab: true ,' 
            + '        editor_css:"' + host_addr + 'stylesheets/mce.css?"+ new Date().getTime(),' 
            + '        content_css:"' + host_addr + 'stylesheets/mce.css?"+ new Date().getTime(),' 
            + '        external_filemanager_path:"' + host_addr + 'scripts/filemanager/",' 
            + '        filemanager_title:"NYSC Admin Blog Content Filemanager" ,' 
            + '        external_plugins: { "filemanager" : "' + host_addr + 'scripts/filemanager/plugin.min.js"}' 
            + '  });   ' 
            + '</script>' 
            + '</div>';
            $(outs).insertBefore('div[name=' + entrypointtype + ']');
            //selection.appendTo(outs);
            branchcounttype.attr('value', '' + nextcount + '');
        } else {
            window.alert("maximum of ten slides please");
        }
    })
    $(document).on("click", "a[name=addextracontact],a[name=addextracontactedit]", function() {
        var thename = $(this).attr("name");
        var edit = thename == "addextracontactedit" ? "edit" : "";
        var branchcount = $('input[name=curcontactcount' + edit + ']').val();
        var nextcount = Math.floor(branchcount) + 1;
        // var nextcountout=nextcount-3;
        // var nextcountmain=nextcount-1;
        if (nextcount <= 10) {
            var outs = '<div class="col-md-4">' 
            + '    <div class="form-group">' 
            + '          <label>Contact Persons (' + nextcount + ')</label>' 
            + '          <div class="input-group">' 
            + '              <div class="input-group-addon">' 
            + '                <i class="fa fa-bars"></i>' 
            + '              </div>' 
            + '              <input type="text" class="form-control" name="contactpersons' + nextcount + '" Placeholder="Contact Persons e.g Segun Ibrahim"/>' 
            + '          </div><!-- /.input group -->' 
            + '        </div>' 
            + '    </div>' 
            + '    <div class="col-md-4">' 
            + '      <div class="form-group">' 
            + '          <label>Phone Numbers</label>' 
            + '          <div class="input-group">' 
            + '              <div class="input-group-addon">' 
            + '                <i class="fa fa-bars"></i>' 
            + '              </div>' 
            + '              <input type="text" class="form-control" name="phonenumbers' + nextcount + '" Placeholder="Phone Numbers (' + nextcount + ')"/>' 
            + '          </div><!-- /.input group -->' 
            + '        </div>' 
            + '    </div>' 
            + '    <div class="col-md-4">' 
            + '      <div class="form-group">' 
            + '          <label>Email </label>' 
            + '          <div class="input-group">' 
            + '              <div class="input-group-addon">' 
            + '                <i class="fa fa-at"></i>' 
            + '              </div>' 
            + '              <input type="text" class="form-control" name="email' + nextcount + '" Placeholder="Email Address (' + nextcount + ')"/>' 
            + '          </div><!-- /.input group -->' 
            + '        </div>' 
            + '    </div>';
            // console.log(nextcount, outs);
            $(outs).insertBefore('div[name=entrycontactpoint' + edit + ']');
            //selection.appendTo(outs);
            $('input[name=curcontactcount' + edit + ']').attr('value', '' + nextcount + '');
        } else {
            window.alert("maximum of ten please");
        }
    })
    $(document).on("click", "a[name=addextrasubproducts],a[name=addextrasubproductsedit]", function() {
        var thename = $(this).attr("name");
        var edit = thename == "addextrasubproductsedit" ? "edit" : "";
        var branchcount = $('input[name=cursubproductcount' + edit + ']').val();
        var nextcount = Math.floor(branchcount) + 1;
        // var nextcountout=nextcount-3;
        // var nextcountmain=nextcount-1;
        if (nextcount <= 10) {
            var outs = '<div class="col-md-6">' 
            + '    <div class="form-group">' 
            + '          <label>SUB product/service Title (<b>' + nextcount + '</b>)</label>' 
            + '          <div class="input-group">' 
            + '              <div class="input-group-addon">' 
            + '                <i class="fa fa-bars"></i>' 
            + '              </div>' 
            + '              <input type="hidden" class="form-control" name="subcontentid' + nextcount + '" Placeholder="" value="0"/>' 
            + '              <input type="text" class="form-control" name="subcontenttitle' + nextcount + '" Placeholder="Product/Service Title"/>' 
            + '          </div><!-- /.input group -->' 
            + '        </div>' 
            + '    </div>';
            // console.log(nextcount, outs);
            $(outs).insertBefore('div[name=entrysubproductpoint' + edit + ']');
            //selection.appendTo(outs);
            $('input[name=cursubproductcount' + edit + ']').attr('value', '' + nextcount + '');
        } else {
            window.alert("maximum of ten please");
        }
    })

    $(document).on("blur","select[name=dogallery]",function(){
        var curval=$(this).val();
        if(curval==""){
          $(this).parent().parent().parent().parent().find('div.dogalleryslides.multi_content_hold_generic').addClass("hidden");
          // reset the values for all form content within the fields
          $(this).parent().parent().parent().parent().find('div.dogalleryslides.multi_content_hold_generic input:not(input[type=hidden])').val("");
          $(this).parent().parent().parent().parent().find('div.dogalleryslides.multi_content_hold_generic select').val("");
          $(this).parent().parent().parent().parent().find('div.dogalleryslides.multi_content_hold_generic textarea').val("");
        }else{
          $(this).parent().parent().parent().parent().find('div.dogalleryslides.multi_content_hold_generic').removeClass("hidden");

        }
    });
    $(document).on("blur","select[name=dovideouploads]",function(){
        var curval=$(this).val();
        // console.log(curval);
        if(curval=="localvideo"){
          // console.log("localvideos");
          $(this).parent().parent().parent().parent().find('div.localvideos').removeClass("hidden");
          $(this).parent().parent().parent().parent().find('div.embedvideo').addClass("hidden");
          // reset the values for all form content within the fields
          $(this).parent().parent().parent().parent().find('div.embedvideo input[type!=hidden]').val("");
          $(this).parent().parent().parent().parent().find('div.embedvideo select').val("");
          // $(this).parent().parent().parent().parent().find('div.embedvideo textarea').val("");
        }else{
          // console.log("embedvideo");
          $(this).parent().parent().parent().parent().find('div.embedvideo').removeClass("hidden");
          $(this).parent().parent().parent().parent().find('div.localvideos').addClass("hidden");
          // reset the values for all form content within the fields
          $(this).parent().parent().parent().parent().find('div.localvideos input[type!=hidden]').val("");
          $(this).parent().parent().parent().parent().find('div.localvideos select').val("");
          $(this).parent().parent().parent().parent().find('div.localvideos textarea').val("");

        }
    });
    $(document).on("blur","select[name=doarticlefiles]",function(){
        var curval=$(this).val();
        if(curval==""){
          $('div.articles_files').addClass("hidden");
          // reset the values for all form content within the fields
          $('div.articles_files input[type!=hidden]').val("");
          $('div.articles_files select').val("");
          $('div.articles_files textarea').val("");
        }else{
          $('div.articles_files').removeClass("hidden");

        }
    });
    $(document).on("blur","select[name=isbookable]",function(){
        var curval=$(this).val();
        if(curval==""){
          $('div.bookingsrequirements').addClass("hidden");
        }else{
          $('div.bookingsrequirements').removeClass("hidden");

        }
    });
    // for general form addition
    $(document).on("click", "a[data-type=triggerformadd]", function() {
        var formname=$(this).attr("data-form-name");
        var curname=$(this).attr("data-name");
        var codata=curname.split("_addlink");
        var countername=codata[0];
        var coredata=countername.split("count");
        var corename=coredata[0];
        var insertiontype=$(this).attr("data-i-type");
        // console.log("insertiontype: ",insertiontype, typeof(insertiontype));
        if(typeof(insertiontype) =="undefined"||insertiontype==null||!insertiontype||insertiontype==""||insertiontype=="undefined"){
          var insertiontype="default";
        }

        var sentineltype=$(this).attr("data-sentineltype");
        // console.log("sentineltype: ",sentineltype, typeof(sentineltype));
        if(typeof(sentineltype) =="undefined"||sentineltype==null||!sentineltype||sentineltype==""||sentineltype=="undefined"){
          var sentineltype="";
        }
        // console.log("After modify insertiontype: ",insertiontype, typeof(insertiontype));

        var mainparent=$(this).parent();
        var shadowlimit=0;
        var branchcount = mainparent.find('input[name='+countername+']').val();
        // get the entry point div
        var entrypoint=mainparent.find('[name='+corename+'entrypoint][data-marker=true]');
        // console.log(entrypoint);
        // get the limit to the entries
        var branchlimit=$(this).attr("data-limit");
        if(typeof(branchlimit)=="undefined"){
          branchlimit="";
        }else{
          branchlimit=Math.floor(branchlimit);
        }

        // console.log("branchlimit - ",branchlimit);
        var nextcounttrue=0;
        var nextcount = Math.floor(branchcount) + 1;
        if(sentineltype!==""&&Math.floor(sentineltype)>0){
          nextcounttrue=Math.floor(nextcount)-Math.floor(sentineltype);
        }else{
          nextcounttrue=nextcount;
        }
        // console.log("nextcounttrue - ",nextcounttrue," branchlimit - ",branchlimit);
        // get the base element and clone it
        var elgroup=$(this).parent().find('[data-type=triggerprogenitor]').clone(true);
        // console.log(elgroup);
        // reset the values for all form content within the fields
          elgroup.find('input[type!=hidden]').val("");
          // console.log(elgroup.find('input[type!=hidden]'));
          elgroup.find('select').val("");
          elgroup.find('textarea').val("");

        // check for tinymce elements in the elgroup and proceed to augment them as appropriate
        var mceelements=elgroup.find('[data-type=tinymcefield]');

        // runa for loop on the mce elements to process them for the new content
        // this array holds the new set of ids for tinymce initialization
        var strinnerset=[];
        // these arrays carry the config for new toolbar components in tinymce initialization
        var mceoptions=[];
        var domce="";
        var cmcecount=0;
        if(mceelements.length>0){
          
          domce="true";
          cmcecount=mceelements.length;
          // remove all tiny mce cloned content
          elgroup.find('div.mce-container').remove();
          for (var i = 0; i < mceelements.length; i++) {
            // convert to multidimensional array
            mceoptions[i]=[];
            var curparent="";
            curparent=elgroup.find('[data-type=tinymcefield]').parent()[i];

            // console.log("Cur match set - ",elgroup.find('[data-type=tinymcefield]').parent().find('input[type=hidden][name=width][data-type=tinymce]'),"Element Parent - ", curparent," Element Parent with JQ - ",$(curparent));
            var curelem=mceelements[i];
            // var tpinner=tp[0].innerText;
            // get the current id
            var curid=curelem.getAttribute("id");
            var nxtid=''+curid+''+nextcount+'';
            // change the element id to match the new one
            curelem.removeAttribute("style");
            curelem.removeAttribute("aria-hidden");
            curelem.setAttribute("id",nxtid);
           
            var nxtstrfunc='textarea#'+curid+''+nextcount+'';
            strinnerset[i]=nxtstrfunc;

            // get the tinymce options if they exist, they are in hidden elements
            // with the data-type attribute of tinymce and name associated with their
            // settings
            var wt="";
            wt=curparent.querySelector('input[type=hidden][name=width][data-type=tinymce]');
            if(wt===null||wt===undefined||wt===NaN){
              wt="";
            }
            mceoptions[i]['width']=wt!==""&&typeof wt!=="undefined"?wt.value:"";
            // console.log("width test 1");
           
            var tb1="";
            tb1=curparent.querySelector('input[type=hidden][name=toolbar1][data-type=tinymce]');
            if(tb1===null||tb1===undefined||tb1===NaN){
              tb1="";
            }
            mceoptions[i]['toolbar1']=tb1!==""&&typeof tb1!=="undefined"?tb1.value:"";
           
            var tb2="";
            tb2=curparent.querySelector('input[type=hidden][name=toolbar2][data-type=tinymce]');
            if(tb2===null||tb2===undefined||tb2===NaN){
              tb2="";
            }
            mceoptions[i]['toolbar2']=tb2!==""&&typeof tb2!=="undefined"?tb2.value:"";
            var ht="";
            ht=curparent.querySelector('input[type=hidden][name=height][data-type=tinymce]');
            if(ht===null||ht===undefined||ht===NaN){
              ht="";
            }
            mceoptions[i]['height']=ht!==""&&typeof ht!=="undefined"?ht.value:"";
           
            var th="";
            th=curparent.querySelector('input[type=hidden][name=theme][data-type=tinymce]');
            if(th===null||th===undefined||th===NaN){
              th="";
            }
            mceoptions[i]['theme']=th!==""&&typeof th!=="undefined"?th.value:"";
           
            var fmt="";
            fmt=curparent.querySelector('input[type=hidden][name=filemanagertitle][data-type=tinymce]');
            if(fmt===null||fmt===undefined||fmt===NaN){
              fmt="";
            }
            mceoptions[i]['filemanagertitle']=fmt!==""&&typeof fmt!=="undefined"?fmt.value:"";

          };
        }
        // console.log("script elements - ", scriptelements);
        // console.log("Real group first- ", elgroup," Parent element", $(this).parent()," corename - ",corename);        
        
        // remove any progenitor details from the cloned element
        var cid=$(this).parent().find('div[data-type=triggerprogenitor]').attr("data-cid");
        elgroup.removeAttr("data-cid");
        elgroup.attr("data-type","triggerprogeny");
        
        var hlabeltext=elgroup.find('h4.multi_content_countlabels').html();

        // console.log("Real group - ", elgroup," ",elgroup.find('h4.multi_content_countlabels'));
        
        // change the h4 label content if necessary to reflect new content present
        hlabeltext=hlabeltext.replace('(Entry '+cid+')','(Entry '+nextcount+')');
        
        elgroup.find('h4.multi_content_countlabels').html(hlabeltext);
        // get element map for form element name manipulation
        var cmap=mainparent.find('input[name='+corename+'datamap]');
        // console.log("cmap: ",cmap);
        var efdstepone=cmap.val().split("<|>");
        for (var i = 0; i < efdstepone.length; i++) {
            if(efdstepone[i]!==""){
                var curdata=efdstepone[i].replace(/[\n\r]*/g,"").replace(/\s{1,}/g, '').split("-:-");
                var fieldname=curdata[0];
                var fieldtype=curdata[1];
                // console.log("curfieldname - ", ''+fieldname+''+cid+''," curfieldtype - ", ''+fieldtype+'');
                // run through the clone set and replace every instance of the current
                // element set with their new values
                // you can also change other values as well
                if(fieldname!==""&&fieldtype!==""){
                  elgroup.find(''+fieldtype+'[name='+fieldname+''+cid+']').attr("name",''+fieldname+''+nextcount+'');               
                }
            }
        };
        var doentry="true";
        // console.log("elgroup modified: ",elgroup);

        // var nextcountout=nextcount-3;
        // var nextcountmain=nextcount-1;
        if(isNumber(branchlimit)&&branchlimit>0){

          if (nextcounttrue <= Math.floor(branchlimit)) {
            doentry="true";
          } else {
            doentry="false";
            window.alert("Maximum allowed entries reached");
          }
        }

        // console.log("Nextcount: ",nextcount," nextcounttrue - ", nextcounttrue," entrypoint - ", entrypoint);
        if(doentry=="true"){
          if(insertiontype=="default"||insertiontype=="before"){
            $(elgroup).insertBefore(entrypoint);
          }else if(insertiontype=="after"){
            $(elgroup).insertAfter(entrypoint);

          }
          //selection.appendTo(outs);
          mainparent.find('input[name='+countername+']').val('' + nextcount + '');
          // for tinymce init
          if(domce=="true"){
            for (var i = 0; i < cmcecount; i++) {
              callTinyMCEInit(strinnerset[i],mceoptions[i]);
            };
          }
        }
    })
// end

  $(document).on("click","input[data-gddownload],button[data-gddownload],a[data-gddownload]",function(){
      // the url parameter has the following expected attributes
      // displaytype=gd_download
      // datapath=the relative/absolute url to the file to be downloaded;
      // title=the name for the download file (optional)
      // contenttype= the manner in which the browser should read the file, defaults
      // to office related content type values on the server so its safe to ignore
      // this in the event of downloading only office documents
      var url =$(this).attr("data-gd-url");

      var subf =$(this).attr("data-gd-subf");
      if(typeof subf=="undefined" || subf === null || subf === undefined || subf === NaN){
        var subf="";
      } 
      var esmap="";
      if(subf!==""){
        var cursmap=JSON.parse(subf.replace(/'{1,}/g,'"'));

        esmap=JSON.stringify(cursmap);
      }
      url=url+"&extras="+esmap;
      var iframe = document.createElement("iframe");
      if(url!=="" &typeof url!=="undefined"){
        iframe.src = url;
        // console.log(iframe);
        if($('div.event_horizon').length<1){
          $('<div class="event_horizon"></div>').appendTo('body');
        }
        $('div.event_horizon').html(iframe);
      }
      // console.log($('div.event_horizon'));
  })


$(document).on("click","a[data-name=mainsearchbyoption]",function(){
  var thetype=$(this).attr("data-value");
  var theplaceholder=$(this).attr("data-placeholder");
  var thetext=$(this).text();
  $('input[name=searchby]').val(""+thetype+"");
  $('input[name=q]').attr("placeholder",""+theplaceholder+"");
  $('button[data-name=searchbyspace]').html(""+thetext+" <span class=\"fa fa-caret-down\"></span>");
});
$(document).on("click","input[type=button][name=mainsearch]",function(){
  var searchby=$('form[name=mainsearchform] select[name=searchby').val();
  var searchval=$('form[name=mainsearchform] input[name=mainsearch').val();
  if(searchby!==""&&searchval!==""){
  var searchreq=new Request();
    searchreq.generate('#contentdisplayhold,section.content',true);
    //enter the url
    searchreq.url('../snippets/display.php?displaytype=mainsearch&searchby='+searchby+'&mainsearch='+searchval+'&extraval=admin');
    //send request
    searchreq.opensend('GET');
    //update dom when finished, takes four params targetElement,entryType,entryEffect,period
    searchreq.update('div#contentdisplayhold,section.content','html','fadeIn',1000);
    
  }else{
    window.alert("To use the search feature you must choose a 'Search By' option first then enter your search value next, then you can search, if any is empty you would keep seeing this.....until you follow the simple instruction.");
  }
});
$(document).on("click","a[data-type=fapicker]",function(){
  // console.log("it was clicked");
  curval=$(this).attr("value");
  icontitle=$(this).attr("title");

  var target_input=$(this).parent().parent().parent().parent().find('input[data-name=icontitle]');
  var target_display=$(this).parent().parent().parent().parent().find('.currentfa i');
  var prevval=target_input.val();
  // console.log(target_input,target_display);
  if(prevval!==curval){
    target_input.val(curval);
    target_display.attr("class","fa "+curval);
    
  }else{
    target_input.removeAttr("value");
    target_input.val("");
    target_display.attr("class","");
  }

});
$(document).on("click","div.currentfa i",function(){
  // console.log("it was clicked");
  curval=$(this).attr("class","");
  icontitle=$(this).attr("title");

  var target_input=$(this).parent().parent().find('input[name=icontitle]');
  var prevval=target_input.val();
  target_input.val("");
  /*// console.log(target_input,target_display);
  if(prevval!==curval){
    target_input.val(curval);
    target_display.attr("class","fa "+curval);
    
  }else{
    target_display.attr("class","");
  }*/

});
/*$(document).on("focus","a[data-type=fapicker]",function(){
    var thisval=$(this);
    doFAPicker(thisval);

});*/

$(document).on("click","a[data-su=true]",function(){});
$(document).on("blur","select[name=incidentfrequency]",function(){
  var cval=$(this).val();
  if(cval=="more than once"||cval=="ongoing"){
    $(this).parent().find('input[name=incidentstarttime]').removeClass('hidden');
  }else{
    $(this).parent().find('input[name=incidentstarttime]').addClass('hidden');

  }
});

$(document).on("blur","select[name=incidentnature]",function(){
  var cval=$(this).val();
  if(cval=="other"){
    $(this).parent().find('input[name=incidentnaturedetails]').removeClass('hidden');
  }else{
    $(this).parent().find('input[name=incidentnaturedetails]').addClass('hidden');

  }
});

$(document).on("blur","select[name=weaponuse]",function(){
  var cval=$(this).val();
  if(cval=="yes"){
    $('div.weapons-details').removeClass('hidden');
  }else{
    $('div.weapons-details').addClass('hidden');
  }
});


$(document).on("blur","select[name=weapon],select[name=threat],select[name=restraint]",function(){
  var cval="";
  vc=0;
  var l=$(this).parent().find('select').length;
  // console.log(l);
  for (var i = 0; i < l; i++) {
    var val=$(this).parent().find('select')[i].value;
    if(val=="no"){
      vc++;
    }
    // console.log(" VC: ",vc);
  };
  // console.log(" CI: ",i+1);
  if(vc==i){
    for (var i = 0; i < l; i++) {
      var val=$(this).parent().find('select')[i].value="";
    };
    $(this).parent().parent().parent().parent().parent().find('select[name=weaponuse]').val("no")
    $('div.weapons-details').addClass('hidden');
  }
  /*if(cval=="yes"){
    $('div.weapons-details').removeClass('hidden');
  }else{
    $('div.weapons-details').addClass('hidden');
  }*/
});
$(document).on("blur","select[name=ireported]",function(){
  var cval=$(this).val();
  if(cval=="yes"){
    $('div.ireported-details').removeClass('hidden');
  }else{
    $('div.ireported-details').addClass('hidden');

  }
});
$(document).on("blur","select[name=reporttype]",function(){
  var cval=$(this).val();
  if(cval=="self"){
    $('form.selfincident').removeClass('hidden');
    $('form.thirdpartyincident').addClass('hidden');
  }else{
    $('form.thirdpartyincident').removeClass('hidden');
    $('form.selfincident').addClass('hidden');

  }
});
$(document).on("blur","select[name=caseresolution]",function(){
  var cval=$(this).val();
  if(cval=="yes"){
    $(this).parent().find('textarea[name=resolutiondetails]').removeClass('hidden').focus();
  }else{
    $(this).parent().find('textarea[name=resolutiondetails]').addClass('hidden').val("");
  }
});
$(document).on("blur","select[name=userid]",function(){
  var cval=$(this).val();
  if(cval==""){
    $('form.selfincident').removeClass('hidden');
    $('form.thirdpartyincident').addClass('hidden');
    var tarelemerr='Please specify the user account';
    raiseMainModal('Error!!', tarelemerr, 'fail');
    $("#mainPageModal").on("hidden.bs.modal", function () {
        // $(this).addClass('error-class').focus();
    });
  }else{
    $('form.thirdpartyincident input[name=userid]').val(cval);
    $('form.selfincident input[name=userid]').val(cval);

  }
});

$(document).on("blur","select[name*=abuserrelation]",function(){
  var cval=$(this).val();
  // console.log("in abuser relation");
  if(cval=="Other"){
    // console.log("in reveal section");
    $(this).parent().find('input[name*=abuserrelationdetails]').removeClass('hidden').focus();
  }else{
    // console.log("in conceal section");
    $(this).parent().find('input[name*=abuserrelationdetails]').addClass('hidden');

  }
});

$(document).on("blur","select[name=sdisability]",function(){
  var cval=$(this).val();
  console.log("in disable relation");
  if(cval=="yes"){
    // console.log("in reveal section");
    $(this).parent().find('input[name=sdisabilitydetails]').removeClass('hidden').focus();
  }else{
    // console.log("in conceal section");
    $(this).parent().find('input[name=sdisabilitydetails]').addClass('hidden');

  }
});

/*$(document).on("blur","input[name*=abuserrelationdetails]",function(){
  var cval=$(this).val();
  if(cval==""){
    var errmsg="Provide needed information concerning relationship with abuser or change the relationship type from 'Other' to anything else";
    raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
    $("#mainPageModal").on("hidden.bs.modal", function () {
        $(this).addClass('error-class');
        $(this).parent().find('select[name*=abuserrelation]').focus();
    });
  };
});*/
/*$('input[data-daterangepicker]').daterangepicker({
    format: 'YYYY-MM-DD'
});*/
$(document).on("click","a[data-name=loadmorecasedata]",function(){
    var state=$(this).attr("data-state");
    var mainparent=$(this).parent().parent().parent();
    if(state===null||state===undefined||state===NaN){
        var state="";
    }
    if(state==""||state=="inactive"){
      $(this).attr("data-state","active").text("Hide Options");
      $(this).parent().addClass("show_all");
      mainparent.removeClass("col-md-4").addClass("col-md-12");
      mainparent.find(".col-md--4").addClass("col-md-4");
      mainparent.find(".col-md--8").addClass("col-md-8").removeClass('hidden');
    }else{
      $(this).attr("data-state","inactive").text("View Case Options");
      $(this).parent().removeClass("show_all");
      mainparent.addClass("col-md-4").removeClass("col-md-12");
      mainparent.find(".col-md--4").removeClass("col-md-4");
      mainparent.find(".col-md--8").removeClass("col-md-8").addClass('hidden');
    }
});

$(document).on("click","a[data-name=caseoption]",function(){
  $(this).parent().find('a[data-name=caseoption]').removeClass('active');
  $(this).addClass('active');
  var curval=$(this).attr("data-value");
  var elid=$(this).parent().attr("data-id");
  var formparent=$(this).parent().parent().parent().parent().parent().parent().attr("name");
  var incidentid=$('form[name='+formparent+'] input[name=incidentid]').val();
  var spuser=$('form[name='+formparent+'] input[name=spuser]').val();
  if(spuser===null||spuser===undefined||spuser===NaN){
        var spuser="";
    }
    // console.log("spuser: ",spuser);
  var spnature=$('form[name='+formparent+'] input[name=spnature_'+elid+']').val();
  var outel=$(this).parent().parent().parent().find('.nature-content-right .ncr-details');
  var loadersect=$(this).parent().parent().parent().find('.nature-content-right .loadmask');
  loadersect.removeClass('hidden');
  var item_loader=loadersect;
  var url=''+host_addr+'snippets/display.php';
  var opts = {
              type: 'GET',
              url: url,
              data: {
                displaytype:'defaultdata',
                subtype:curval,
                incid:incidentid,         
                spnature:spnature,         
                userset:spuser,         
                extraval:"admin"
              },
              dataType:"json",
              success: function(output) {
                // console.log(endtarget);
                // console.log(output);
                // item_loader.className +=' hidden';
                // item_loader.addClass('hidden').css("display","");
                // item_loader.remove();
                loadersect.addClass('hidden');
                if(output.success=="true"){
                      // endtarget.innerHTML=output.msg;
                      
                      // console.log("mout: ",mout);
                      outel.html(output.msg);
                      

                }
              },
              error: function(error) {
                  if(typeof(error)=="object"){
                      console.log(error.responseText,error);
                  }
                  var errmsg="Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again";
                  // item_loader.remove();
                  item_loader.className +=' hidden';
                  raiseMainModal('Failure!!', ''+errmsg+'', 'fail');
                  // alert("Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again ");
              }
      };
      $.ajax(opts);

});

$(document).on("click","a[data-name=sortbusinesses]",function(){
  var elid=$(this).attr("data-id");
  var cspel=$('select[name=spstate_'+elid+']');
  // get the service providers list for the current view
  var cspl=$(this).parent().parent().parent().find('select[name=sp_'+elid+']');
  var cspnature=$('input[name=spnature_'+elid+']').val();
  var curvalue=$(this).parent().parent().parent().find('select[name=spstate_'+elid+']').val();
  var curstatename=curvalue!==""?$(this).parent().parent().parent().find('select[name=spstate_'+elid+'] option[value='+curvalue+']').text():"";
  var loadersect=$(this).parent().parent().parent().find('.loadmask');
  loadersect.removeClass('hidden');
  var item_loader=loadersect;
  var url=''+host_addr+'snippets/display.php';
  var opts = {
              type: 'GET',
              url: url,
              data: {
                displaytype:'defaultdata',
                subtype:'spstate',
                curval:curvalue,         
                cspnature:cspnature,         
                extraval:"admin"
              },
              dataType:"json",
              success: function(output) {
                // console.log(endtarget);
                // console.log(output);
                // item_loader.className +=' hidden';
                // item_loader.addClass('hidden').css("display","");
                // item_loader.remove();
                loadersect.addClass('hidden');
                if(output.success=="true"){
                      // endtarget.innerHTML=output.msg;
                      var mout="";
                      if(output.msg!==""){
                        mout='<option value="">Choose Service Provider</option>'+output.msg+'';
                      }else{
                        mout='<option value="">Choose Service Provider</option><option value="">No Results found for Service Provider in '+curstatename+' State </option>'
                      }
                      // console.log("mout: ",mout);
                      cspl.html(mout);
                      

                }
              },
              error: function(error) {
                  if(typeof(error)=="object"){
                      console.log(error.responseText,error);
                  }
                  var errmsg="Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again";
                  // item_loader.remove();
                  item_loader.className +=' hidden';
                  raiseMainModal('Failure!!', ''+errmsg+'', 'fail');
                  // alert("Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again ");
              }
      };
      $.ajax(opts)
});
$(document).on("blur", "select[name=rangetype]", function() {
    var theval = $(this).val();
    // console.log(theval);
    if (theval == "grouped") {
        $('div.optioncontent').removeClass('hidden').fadeIn(500);
    } else {
        $('div.col-md-12.optioncontent').hide(500).addClass('hidden');
        $('div.col-md-12.optioncontent select').val("");
        $('div.col-md-12.optioncontent input').val("");
    
    }

});
$(document).on("click", "input[name=exportincidents]", function() {
    var typeval=$('select[name=rangetype]').val();
    var gender="";
    var state="";
    var incnature="";
    var ageold="";
    var agenew="";
    var dpold="";
    var dpnew="";
    var asetval=$('select[name=anonymitytype]').val();
    // var iframe=$('iframe[name=exportpoint]');
    var ringtype="";
    var loadersect=$(this).parent().parent().parent().find('.loadmask');
    // loadersect.removeClass('hidden');
    var status="ok";
    if(typeval=="grouped"){
        ringtype="exportrangeincidents";
        gender=$('select[name=gender]').val();
        state=$('select[name=state]').val();
        incnature=$('select[name=incidentnature]').val();
        ageold=$('input[name=ageold]').val();
        agenew=$('input[name=agenew]').val();
        dpold=$('input[name=dateperiodold]').val();
        dpnew=$('input[name=dateperiodnew]').val();
        // loadersect.addClass('hidden');
    }else{
        ringtype='exportallincidents';
    }
    var urldata='&gender='+gender+'&state='+state+'&incnature='+incnature+'&ageold='+ageold+'&agenew='+agenew+'&dpold='+dpold+'&dpnew='+dpnew+'';
    if(status=="ok"){
        var url = '../snippets/display.php?displaytype=exportincidents&exporttype=' + ringtype + '&asettings='+asetval+''+urldata+'&extraval=';
        var iframe = document.createElement("iframe");
        // console.log(iframe);
        iframe.src = url;
        $('div.event_horizon').html(iframe);
        loadersect.delay(1000).addClass('hidden');
        // console.log("url : ",url);   
    }
});
$(document).on("click","td[data-type=subtablelink] a",function(){
  var cura=$(this);
  var linkname=$(this).attr('name');
  var editid=$(this).attr('data-divid');
  var item_loader=$(this).parent().find('.loadmask');
  item_loader.removeClass('hidden');
  var dotarget="";
  if(linkname=="disablecomment"){
    dotarget="none";
  }else if(linkname=="activatecomment"||linkname=="reactivatecomment"){
    dotarget="none";
  }
  console.log(linkname,editid,dotarget,item_loader);

  // send ajax request to verify email existing in database
  var url = '' + host_addr + 'snippets/display.php';
  var opts = { 
      type: 'GET',
      url: url,
      data: {
          displaytype: linkname,
          editid: editid,
          extraval: "admin"
      },
      // dataType: 'json',
      success: function(output) {
          // item_loader.className += ' hidden';
          item_loader.addClass('hidden');
          // console.log(endtarget);
          // console.log(output);
          // if(dotarget!=="none"){

            // target.html(output);
          // }
          
          if(linkname=="disablecomment"){
            cura.attr({"name":"reactivatecomment","data-type":"reactivatecomment"}).text('Reactivate');
            $("td[name=commentstatus"+editid+"]").text("disabled");
          }else if(linkname=="activatecomment"||linkname=="reactivatecomment"){
            cura.attr({"name":"disablecomment","data-type":"disablecomment"}).text('Disable');
            $("td[name=commentstatus"+editid+"]").text("active");
          }
          if($.fn.dataTable){
            $("table[data-dTable=true]").dataTable();
          }

      },
      error: function(error) {
          if (typeof (error) == "object") {
              console.log(error.responseText);
          }else{
              console.log("Error: ",error);
          }
          var errmsg = "Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again";
          // item_loader.remove();
          item_loader.addClass('hidden');
          // item_loader.className += ' hidden';
          raiseMainModal('Failure!!', '' + errmsg + '', 'fail');
          // alert("Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again ");
      }
  };
  // console.log("In here");
  if(editid>0){
    $.ajax(opts);
  }  
});
// init transfer
$(document).on("click","input[name=inittransfer]",function(){
  var elid=$(this).attr("data-id");
  // var cstate=$('select[name=spstate_'+elid+']');
  var cspel=$('select[name=spstate_'+elid+']');
  // get the service providers list for the current view
  var cspl=$(this).parent().parent().parent().find('select[name=sp_'+elid+']').val();
  var curspnature=$(this).parent().parent().parent().find('input[name=spnature_'+elid+']').val();
  var curcaseid=$(this).parent().parent().parent().find('input[name=caseid_'+elid+']').val();
  var cspid=$(this).parent().parent().parent().find('input[name=cspid_'+elid+']').val();
  var incid=$(this).parent().parent().parent().find('input[name=incid_'+elid+']').val();
  var details=$(this).parent().parent().parent().find('textarea[name=details_'+elid+']').val();
  var curstatename=cspel.val()!==""?$(this).parent().parent().parent().find('select[name=spstate_'+elid+'] option[value='+cspel.val()+']').text():"";
  var loadersect=$(this).parent().parent().parent().find('.loadmask');
  var entryel=$(this).parent().parent().parent();
  loadersect.removeClass('hidden');
  var item_loader=loadersect;
  var url=''+host_addr+'snippets/display.php';

  var opts = {
              type: 'GET',
              url: url,
              data: {
                displaytype:'defaultdata',
                subtype:'inittransfer',
                oldspid:cspid,         
                caseid:curcaseid,         
                incidentid:incid,         
                spnature:curspnature,         
                newspid:cspl,         
                details:details,         
                extraval:"admin"
              },
              dataType:"json",
              success: function(output) {
                // console.log(endtarget);
                // console.log(output);
                // item_loader.className +=' hidden';
                // item_loader.addClass('hidden').css("display","");
                // item_loader.remove();
                loadersect.addClass('hidden');
                if(output.success=="true"){
                      // endtarget.innerHTML=output.msg;
                      // console.log("mout: ",mout);
                      entryel.html(output.msg);
                      

                }else if(output.success=="false"){
                    var errmsg=output.msg;
                    raiseMainModal('Submission Error!!', ''+errmsg+'', 'fail');
                                 
                }
              },
              error: function(error) {
                  if(typeof(error)=="object"){
                      console.log(error.responseText,error);
                  }
                  var errmsg="Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again";
                  // item_loader.remove();
                  loadersect.addClass('hidden');
                  // item_loader.className +=' hidden';
                  raiseMainModal('Failure!!', ''+errmsg+'', 'fail');
                  // alert("Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again ");
              }
      };
  if(cspl!==""&&cspl!==cspid&&details!==""){
    var tester=window.confirm('Click "Ok" if you are sure you want to initiate the transfer this case?\n\r This case wont be transferable till the receiving service provider accepts/rejects the transfer.');
    if(tester===true){
      $.ajax(opts);
    }else{
      loadersect.addClass('hidden');

    }
    
    
  }else{
    loadersect.addClass('hidden');
    var errmsg='Provide the details/reason for the transfer.<br>Make sure to Select the Receiving Service Provider. <br> Do not select the same Service Provider.';
    raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
    $("#mainPageModal").on("hidden.bs.modal", function () {
        $(this).parent().parent().parent().find('select[name=sp_'+elid+']').addClass('error-class').focus();
    });
  }
});

$(document).on("click","form[name=mainsearchform].sidebar-form button[type=button][name=mainsearch]",function(){
  var searchby=$('form[name=mainsearchform].sidebar-form input[name=searchby]').val();
  var searchval=$('form[name=mainsearchform].sidebar-form input[name=q]').val();
  if(searchby!==""&&searchval!==""){
  var searchreq=new Request();
    searchreq.generate('section.content',true);
    //enter the url
    searchreq.url('../snippets/display.php?displaytype=mainsearch&searchby='+searchby+'&mainsearch='+searchval+'&extraval=admin');
    //send request
    searchreq.opensend('GET');
    //update dom when finished, takes four params targetElement,entryType,entryEffect,period
    searchreq.update('section.content','html','fadeIn',1000);
    
  }else{
    window.alert("To use the search feature you must choose a 'Search By' option first then enter your search value next, then you can search, if any is empty you would keep seeing this.....until you follow the simple instruction.");
    $('form[name=mainsearchform].sidebar-form input[name=q]').focus();
  }
});

  /*blog/portfolio post form related data*/
  $(document).on("change","select[name=blogentrytype]",function(){
    var curtype=$(this).val();
    if($(this).parent().parent().find('div[data-name=introparagraph]').length>0){
      var parent=$(this).parent().parent();
      console.log("Parent 1: ",parent);
    }else{
      var parent=$(this).parent().parent().parent().parent();
      // console.log("Parent 2: ",parent);
    }
    if(curtype=="normal"||curtype==""){
      parent.find('div[data-name=introparagraph]').show(300);
      parent.find('div[data-name=blogentry]').show(300);
      parent.find('div[data-name=coverphoto]').show(300);
      parent.find('div[data-name=coverphotofloat]').show(300);
      parent.find('div[data-name=bannerpicentry]').hide(300);
      parent.find('div[data-name=galleryentry]').hide(300);
      parent.find('div[data-name=videosection]').hide(300);
      parent.find('div[data-name=audiosection]').hide(300);
    }else if(curtype=="gallery"){
      parent.find('div[data-name=introparagraph]').show(300);
      parent.find('div[data-name=blogentry]').show(300);
      parent.find('div[data-name=coverphoto]').hide(300);
      parent.find('div[data-name=coverphotofloat]').hide(300);
      parent.find('div[data-name=bannerpicentry]').hide(300);
      parent.find('div[data-name=galleryentry]').show(300);
      parent.find('div[data-name=videosection]').hide(300);
      parent.find('div[data-name=audiosection]').hide(300);
    }else if(curtype=="banner"){
      parent.find('div[data-name=introparagraph]').show(300);
      parent.find('div[data-name=blogentry]').show(300);
      parent.find('div[data-name=coverphoto]').hide(300);
      parent.find('div[data-name=coverphotofloat]').hide(300);
      parent.find('div[data-name=bannerpicentry]').show(300);
      parent.find('div[data-name=galleryentry]').hide(300);
      parent.find('div[data-name=videosection]').hide(300);
      parent.find('div[data-name=audiosection]').hide(300);
    }else if(curtype=="video"){
      parent.find('div[data-name=introparagraph]').show(300);
      parent.find('div[data-name=blogentry]').show(300);
      parent.find('div[data-name=coverphoto]').show(300);
      parent.find('div[data-name=coverphotofloat]').show(300);
      parent.find('div[data-name=bannerpicentry]').hide(300);
      parent.find('div[data-name=galleryentry]').hide(300);
      parent.find('div[data-name=videosection]').show(300);
      parent.find('div[data-name=audiosection]').hide(300);
    }else if(curtype=="audio"){
      parent.find('div[data-name=introparagraph]').show(300);
      parent.find('div[data-name=blogentry]').show(300);
      parent.find('div[data-name=coverphoto]').show(300);
      parent.find('div[data-name=coverphotofloat]').show(300);
      parent.find('div[data-name=bannerpicentry]').hide(300);
      parent.find('div[data-name=galleryentry]').hide(300);
      parent.find('div[data-name=videosection]').hide(300);
      parent.find('div[data-name=audiosection]').show(300);
    }else if(curtype=="poll"){
      parent.find('div[data-name=introparagraph]').show(300);
      parent.find('div[data-name=blogentry]').show(300);
      parent.find('div[data-name=coverphoto]').show(300);
      parent.find('div[data-name=coverphotofloat]').show(300);
      parent.find('div[data-name=bannerpicentry]').hide(300);
      parent.find('div[data-name=galleryentry]').hide(300);
      parent.find('div[data-name=videosection]').hide(300);
      parent.find('div[data-name=audiosection]').hide(300);
    }

  });
  if($.fn.datepicker){
    //bootstrap Date range picker
    // var datepicker = $.fn.datepicker.noConflict(); 
    // $.fn.bootstrapDP = datepicker;
    $('[data-datetimepicker]').datetimepicker({
        format:"YYYY-MM-DD HH:mm",
        keepOpen:true
    })
    // for disabling previous dates 
    $('[data-datetimepickerf]').datetimepicker({
        format:"YYYY-MM-DD HH:mm",
        keepOpen:true,
        minDate: moment(1, 'h')
    });
    $('[data-datepicker]').datetimepicker({
        format:"YYYY-MM-DD",
        keepOpen:true
        // showClose:true
        // debug:true
    });
    $('[data-datepickerf]').datetimepicker({
        format:"YYYY-MM-DD",
        keepOpen:true,
        minDate: moment(1, 'h')
        // showClose:true
        // debug:true
    });
    $('[data-timepicker]').datetimepicker({
        format:"HH:mm",
        keepOpen:true
    });
    // $('#reservation').datepicker();
    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  }
  if($.fn.daterangepicker){

    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    $('[data-datetimerange]').daterangepicker({format: 'YYYY-MM-DD HH:mm:ss'});
    
    $('[data-datetimerangef]').daterangepicker({format: 'YYYY-MM-DD'});
    if($(document).inputmask){
        $("#datemask").inputmask("dd-mm-yyyy", {"placeholder": "dd-mm-yyyy"});
        //Datemask2 mm/dd/yyyy
        $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
        //Money Euro
        $("[data-mask]").inputmask();
        $(".timemask").inputmask("hh:mm", {"placeholder": "hh:mm"});

    }
  }
  $(document).on("blur","select[name=portfoliotype]",function(){
    var theval=$(this).val();
    if(theval=="client"){
      $('div.client-name').removeClass('hidden');
      // $('div.office-position').addClass('hidden');
    }else{
      // $('div.office-position').addClass('hidden');
      $('div.client-name').addClass('hidden');

    }
  });

  $(document).on("blur","select[name=pwrdd]",function(){
    // console.log('Pwrdd');
    var theval=$(this).val();
    var mainparent=$(this).parent().parent().parent().parent();
    if(theval=="yes"){
      mainparent.find('div.portfolio-pwrd').removeClass('hidden');
    }else{
      mainparent.find('div.portfolio-pwrd').addClass('hidden');

    }
  });

  $(document).on("blur","select[name=galleryattach]",function(){
    // console.log('Gallery Attach');
    var theval=$(this).val();
    if(theval=="yes"){
      $('div.portgallery').removeClass('hidden');
    }else{
      $('div.portgallery').addClass('hidden');
      resetValues('div.portgallery');
    }
  });
  $(document).on("blur","select[name=featured]",function(){
    // console.log('Gallery Attach');
    var theval=$(this).val();
    if(theval=="yes"){
      $('div.featured-details').removeClass('hidden');
    }else{
      $('div.featured-details').addClass('hidden');
      resetValues('div.featured-details');

    }
  });

  $(document).on("blur","select[name=vidattach]",function(){
    // console.log('Gallery Attach');
    var theval=$(this).val();
    var elgroup=$('div.portvideos');
    if(theval=="yes"){
      $('div.portvideos').removeClass('hidden');
    }else{
      $('div.portvideos').addClass('hidden');
      // reset all fields in 
      resetValues('div.portvideos');
      
    }
  });

  $(document).on("blur","select[name=audioattach]",function(){
    // console.log('Gallery Attach');
    var theval=$(this).val();
    if(theval=="yes"){
      $('div.portaudio').removeClass('hidden');
    }else{
      $('div.portaudio').addClass('hidden');
      resetValues('div.portaudio');

    }
  });
  
  $(document).on("blur","select[name*=portvtype],select[name*=videotype]",function(){
    // console.log('Gallery Attach');
    var theval=$(this).val();
    var parent=$(this).parent().parent().parent().parent();
    console.log('The parent: ',parent);
    if(theval=="local"){
      parent.find('div.portvideolocal, div.blogvideolocal').removeClass('hidden');
      parent.find('div.portvideoembed,div.blogvideoembed').addClass('hidden');
      parent.find('div.contentpreview._video .local_video');
    }else if(theval=="embed"){
      parent.find('div.portvideoembed,div.blogvideoembed').removeClass('hidden');
      parent.find('div.portvideolocal,div.blogvideolocal').addClass('hidden');
      parent.find('div.portvideolocal input[type=file],div.blogvideolocal input[type=file]').val('');
      parent.find('div.contentpreview._video .embed_video');
    }else if(theval==""){
      parent.find('div.portvideoembed,div.blogvideoembed').addClass('hidden');
      parent.find('div.portvideolocal,div.blogvideolocal').addClass('hidden');
      parent.find('div.portvideolocal input[type=file],div.blogvideolocal input[type=file]').val('');
    }
  });
  $(document).on("blur","select[name*=portatype],select[name*=audiotype]",function(){
    var theval=$(this).val();
    var parent=$(this).parent().parent().parent().parent();
    // console.log('The parent: ',parent);
    if(theval=="local"){
      parent.find('div.portaudiolocal,div.blogaudiolocal').removeClass('hidden');
      parent.find('div.portaudioembed,div.blogaudioembed').addClass('hidden');
      parent.find('div.contentpreview._audio .embed_audio');
    }else if(theval=="embed"){
      parent.find('div.portaudioembed, div.blogaudioembed').removeClass('hidden');
      parent.find('div.portaudiolocal, div.blogaudiolocal').addClass('hidden');
      parent.find('div.portaudiolocal input[type=file], div.blogaudiolocal input[type=file]').val('');
      parent.find('div.contentpreview._video .embed_video');
    }else if(theval==""){
      parent.find('div.portaudioembed').addClass('hidden');
      parent.find('div.portaudiolocal,div.blogaudiolocal').addClass('hidden');
      parent.find('div.portaudiolocal input[type=file], div.blogaudiolocal input[type=file]').val('');
    }
  });

  $(document).on("change","select[name=schedulestatus]",function(){
    console.log('Gallery Attach');
    var theval=$(this).val();
    var parent=$(this).parent().parent().parent().parent();
    console.log(parent);
    if(theval=="yes"){
      parent.find('div.scheduled').removeClass('hidden');
    }else{
      parent.find('div.scheduled').addClass('hidden');
      // reset all fields in 
      // resetValues('div.scheduled');
      
    }
  });

  /*end of blog post related entries*/

  $(document).on("click","input[name=advancedrecruitsearch]",function(){
    var params=$('form[name=advancedrecruitsearch]').serialize();
    console.log(params);
    // post parameters
      $('section.results div[data-name=rowresults]').html('<img src="'+host_addr+'images/waiting.gif" class="total2"/>')
          $.ajax({
              type: "GET",
              data:params,
              url: ""+host_addr+"snippets/display.php",
              success: function(result) {
              // console.log(result);
              $('section.results div[data-name=rowresults]').html(result);
                  
                    // console.log(inputname1.val(),inputname2.val(),inputname3.val(),inputname3.val().length);
      
              },
              error: function() {
      
                /*result = '<div class="alert error"><i class="fa fa-times-circle"></i>There was an error sending the message!</div>';
                $("#formstatus-3").html(result);*/
      
              }
          });
  });

if($.fn.CSearch){
  $('input[data-name=iconsearch]').CSearch('ul.fadisplaylist a[data-type=fapicker]','html');
}
if($.fn.wordMAX){
  // console.log("functional");
  $('input[type="text"][data-wMax],textarea[data-wMax]').wordMAX(); 
  $(document).on("click",'input[type="text"][data-wMax],textarea[data-wMax]',function(){
    $(this).wordMAX();
    /*if(!$(this).wordMAX()){
    }*/
  })
}

});

