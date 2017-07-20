//set this variable for new projects to work
var host_addr="http://"+location.hostname+"/";
var host_env="live";
if(host_addr.indexOf('localhost/')>-1){
    host_addr="http://localhost/mysalvus/";
}   host_env="local";
if(host_addr.indexOf('ngrok.io/')>-1){
  host_addr="http://"+location.hostname+"/mysalvus/";
}
// console.log(host_addr);
//variable for holding filemangerheader title
var host_admin_title_name="MySalvus Admin";
//get userid and usertype
$(document).ready(function(){
$('body').addClass('loaded');
userid=$('input[name=userdata]').attr('data-userid');
usertype=$('input[name=userdata]').attr('data-usertype');
 // convert resource content links into selection box options
  if($('.resource-content-link').length>0){
      var optlnt=$('.resource-content-link').length;
      var options='<option value="">Choose Resource Option</option>';
      var defaultactive=0;
      for(var i=0;i<optlnt;i++){
          var curid=$('.resource-content-link')[i].getAttribute('data-id');
          var fullclass=$('.resource-content-link')[i].getAttribute('class');
          if(fullclass.indexOf('active')>0){
              defaultactive=curid;
          };
          var text=$('.resource-content-link')[i].text;
          // console.log($('.resource-content-link')[i])
          options+='<option value="'+curid+'">'+text+'</option>';
      }  
      $('select[name=resource-content-selection]').html(options); 
      if(defaultactive>0){
          $('select[name=resource-content-selection]').val(defaultactive);
      }
  }
  // enable all event-count down elements on the page
    if($('.eventCountdown').length>0){
        
        //run a loop on all the current event counters on the page
        var totallenght=$('.eventCountdown').length;
        
        for(var i=0; i<totallenght;i++){
            
            // get the current time 
            var curDay = new Date();
            var curtimespan=curDay.getTime();
            // console.log("Current Timespan value - ",curtimespan);
            var $this=$('.eventCountdown')[i];
            // console.log($this);
            var curid=$this.getAttribute("data-id");
            var $trueelem=$('div.event_countdown_'+curid);
            
            if(curid!=="undefined"&&typeof($('.eventCountdown')[i].getAttribute("data-id"))!=="undefined"){
        
                // event start time
                var targetdate=$this.getAttribute('data-datetime');
                var targetdatetime=new Date(""+targetdate+"");
                    // console.log("targettimeframe - ",targetdatetime);
                    // targetdatetime=new Date(targetdatetime.getFullYear(),targetdatetime.getMonth(),targetdatetime.getDay(),targetdatetime.getHours(),targetdatetime.getMinutes(),targetdatetime.getSeconds());
                    // console.log("targettimeframetwo - ",targetdatetime);
                // event stop time
                var targetdateend=$this.getAttribute('data-datetimestop');
                var targetdatetimestop=new Date(targetdateend);
                    // console.log("targettimeframeend - ",targetdatetimestop);
                    // targetdatetimestop=new Date(targetdatetimestop.getFullYear(),targetdatetimestop.getMonth(),targetdatetimestop.getDay(),targetdatetimestop.getHours(),targetdatetimestop.getMinutes(),targetdatetimestop.getSeconds());
                    // console.log("targettimeframeendtwo - ",targetdatetimestop);
        
                // event id
                var targetid=$this.getAttribute('data-id');
                // division that shows the markup for "Our time left"
                var $timeleftholder=$('.event_'+targetid+' .event-start-date');
        
                // holds the markup, if any for ongoing or completed texts for events
                var outmark="";
        
                // test to see if current date period is less than the event date to make
                // sure the event is ongoing
                // console.log("curtime:",curtimespan," starttime:",targetdatetime.getTime()," stoptime", targetdatetimestop.getTime());
                // console.log("targetdate:",targetdate," targetdateend:",targetdateend);
                if(curtimespan<targetdatetime.getTime()){
                    outmark='<strong class="title">Time till Event Starts:</strong>';
                    // the event hasnt started so the countdown can be implemented
                    $trueelem.countdown({
                        until: targetdatetime
                    });
                    // console.log("not yet");
                }else if(curtimespan>=targetdatetime.getTime()&&curtimespan<targetdatetimestop.getTime()){
        
                    // the event has started but it is still ongoing
                    outmark='<strong class="title">Time till Event Ends:(<span class="green">Ongoing</span>)</strong>';
                    // the event hasnt started so the countdown can be implemented
                    $trueelem.countdown({
                        until: targetdatetimestop
                    });
                    // console.log("ongoing");
                
                }else if(curtimespan>targetdatetimestop.getTime()||(typeof(targetdatetimestop.getTime())=="NaN"&&typeof(targetdatetime.getTime())=="NaN")){
                            
                    // the event has ended
                    console.log("ended", typeof(targetdatetimestop.getTime()));
                    outmark='<h3 class="title text-center text-shadow-color-blue color-white">Event has ended</h3>';
                    $('div.event_'+curid+' .caption').css("display","none");
                    var endmarkup='<span class="countdown-row countdown-show"><span class="countdown-section"><span class="countdown-amount">0</span><span class="countdown-period">Day</span></span><span class="countdown-section"><span class="countdown-amount">0</span><span class="countdown-period">Hrs</span></span><span class="countdown-section"><span class="countdown-amount">0</span><span class="countdown-period">Mins</span></span><span class="countdown-section"><span class="countdown-amount">0</span><span class="countdown-period">Sec</span></span></span></div>';
                    $trueelem.html(endmarkup);
                }
                // change the markup of the time left div display in the event display 
                // container
                $('div.event_'+curid+' .event-start-date').html(outmark);

            }
        }
    }   

  
  


jQuery.cachedScript = function( url, options ) {
 
  // Allow user to set any option except for dataType, cache, and url
  options = $.extend( options || {}, {
    dataType: "script",
    cache: true,
    url: url
  });
 
  // Use $.ajax() since it is more flexible than $.getScript
  // Return the jqXHR object so we can chain callbacks
  return jQuery.ajax( options );
};
 
// Usage
/*$.cachedScript( "../scripts/jscripts/tiny_mce/tiny_mce.js" ).done(function( script, textStatus ) {
  // console.log( textStatus );
});*/
});
// // console.log(usertype);
$(document).on("click","div#menulinkcontainer a[data-type=sublink]",function(){
$("div#menulinkcontainer a[data-type=sublink]").attr("data-state","inactive");
$(this).attr("data-state","active");
  
});
/*admin lte control, mainlink clicks*/
// this code hides the notification contents in the menu treeview that is clicked
$(document).on("click", "li.treeview a[appdata-otype=mainlink]", function() {
    $(this).children("small.mainsmall").fadeOut(300).remove();
    // // console.log($(this).children("small.mainsmall"));
});

// this section makes sure that other treeviews in the list are closed when one is
// clicked
$(document).on("click", "a[appdata-type=menulinkitem][appdata-otype=mainlink]", function() {
    $("ul.sidebar-menu ul").removeClass("menu-open").attr("style", " ");
    $("ul.sidebar-menu li").removeClass("active");
    // console.log($(this).children("small.mainsmall"));
})

// this section handles the breadcrumb displays at the top of the page for the 
// backend when menu options are clicked, it relies on data attributes
// on the clicked elements to obtain the breadcrumb titles and icons
// the ajax requests are also made from here
$(document).on("click", "ul li a[appdata-type=menulinkitem]", function() {
    var pcrumb = $(this).attr("appdata-pcrumb");
    $("ul.sidebar-menu ul li:not(.treeparent-child)").removeClass("active");
    $(this).parent().addClass("active");
    $(this).children("small").fadeOut(200).remove();
    //   $(this).getElementsByTagName("small").fadeOut(200).remove();
    var pcrumb = $(this).attr("appdata-pcrumb");

    // // console.log($(this).children("small"));
    var crumb = $(this).html();
    var fa = $(this).attr("appdata-fa");
    var notifylinkval = "";
    var crumbout = "";
    if (pcrumb !== "undefined" && typeof (pcrumb) !== "undefined") {
        fa !== "undefined" && typeof (fa) !== "undefined" ? fa = fa.replace(/a\|\|/g, '"') + " " : fa = "";
        crumbout += "<li><a href=\"#" + pcrumb + "\">" + fa + "" + pcrumb + "</a></li>";
        notifylinkval = pcrumb;
        $('h1[appdata-name=notifylinkheader]').text(notifylinkval);

    }
    if (crumb !== "undefined" && typeof (crumb) !== "undefined") {
        crumbdata = crumb.split(">");
        crumbout += "&nbsp;&nbsp; >&nbsp;&nbsp; " + crumb;
    }
    if (crumbout == "") {
        crumbout = '<li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>';
    } else {}
    /*create bread crumb*/
    $('ol[appdata-name="breadcrumb"]').html(crumbout);
    /*end*/
    /*make ajax request to for clicked option*/
        var linkname = $(this).attr("appdata-name");
        var datamap = $(this).attr("appdata-datamap");
        if (datamap === null || typeof datamap == "undefined" || datamap === NaN) {
            datamap = "";
        }
        if(datamap!==""){
          // datamap=encodeURIComponent(datamap);
        }
        var maincontainer="section.content";
        
        //$('section.content').html(help[''+linkname+'']);
        var url='' + host_addr + 'snippets/display.php?displaytype=' + linkname + '&datamap=' + datamap + '&extraval=admin';
        // console.log("datamap: ",datamap," url: ",url);
        var sublinkreq = new Request();
        sublinkreq.generate('section.content', true);
        //enter the url
        sublinkreq.url('' + host_addr + 'snippets/display.php?displaytype=' + linkname + '&datamap=' + datamap + '&extraval=admin');
        //send request

        sublinkreq.opensend('GET');
        //update dom when finished, takes four params targetElement,entryType,entryEffect,period
        sublinkreq.update('section.content', 'html', 'fadeIn', 1000);
        
        /*
        var target=$(''+maincontainer+'');
        $(''+maincontainer+'').html('<img src="'+host_addr+'images/waiting.gif" class="total2"/>');
        var url = '' + host_addr + 'snippets/display.php';
        var opts = {
            type: 'GET',
            url: url,
            data: {
                displaytype: linkname,
                datamap: datamap,
                extraval: "admin"
            },
            success: function(output) {
                // console.log(endtarget);
                console.log(output);
                // item_loader.className += ' hidden';
                // item_loader.addClass('hidden');
                // item_loader.remove();
                target.html(output);
                
            },
            error: function(error) {
                if (typeof (error) == "object") {
                    console.log(error.responseText);
                }else{
                    console.log("Error: ",error);
                }
                var errmsg = "Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again";
                // item_loader.remove();
                // item_loader.addClass('hidden');
                // item_loader.className += ' hidden';
                raiseMainModal('Failure!!', '' + errmsg + '', 'fail');
                // alert("Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again ");
            }
        };
        $.ajax(opts);*/
    /*end*/
});
/*end*/
/*LTE custom options controller*/
/*$(document).on("click", "ul li a[appdata-type=menulinkitem]", function() {
});*/

// code for handling old backend ui sidebar menu clicks
// this code ensures to hide open menus and only expand the current menu clicked
$(document).on("click", "div#menulinkcontainer a[data-type=mainlink]", function() {
    var parentcontrol = this.parentNode
    // // console.log($(parentcontrol));
    $(parentcontrol).find("div#menunotification").fadeOut(1000);

    var datastate = $(parentcontrol).attr("data-state");

    var thelength = $(parentcontrol).find("a").length;
    var newheight = 0;
    if (datastate == "inactive") {
        $("div#menulinkcontainer").attr({
            "data-state": "inactive"
        }).css("height", "");
        $("div#menulinkcontainer a[data-type=sublink]").attr("data-state", "inactive");
        $("a[data-type=sublink]").attr("style", "");
        $(parentcontrol).attr("data-state", "active");
        if (thelength > 2) {
            for (var i = 0; i < thelength; i++) {
                newheight += $(parentcontrol).find("a")[i].clientHeight;
            }
        } else {
            newheight = $(parentcontrol).find("a")[1].clientHeight + 31;
        }
        if (newheight > 0) {
            newheight = newheight + 3;
            if (thelength > 2) {
                $(parentcontrol).animate({
                    height: "" + newheight + ""
                }, 500, function() {
                    $("div#menulinkcontainer[data-state=inactive]").css("height", "");
                });
            } else {
                $(parentcontrol).animate({
                    height: "" + newheight + ""
                }, 500, function() {
                    $("div#menulinkcontainer[data-state=inactive]").css("height", "");
                });
            }

        }
        // // console.log($(parentcontrol).find("a")[1].clientHeight,newheight,parentcontrol.clientHeight);
    } else if (datastate == "active") {}
});
//for backend data output tables edit content display 
$(document).on("click", "td[name=trcontrolpoint] a", function() {
    // the name of the link, used to describe what clicking the link would do
    var linkname = $(this).attr("name");
    
    // check if the current link has a 'oname' attribute
    // this attribute is used to control the text displayed by default for the 
    // current link
    var linkoname= $(this).attr("data-oname");
    if (linkoname === null || linkoname === undefined || linkoname === NaN) {
        linkoname = "";
    }
    // check if the current link has a 'ename' attribute
    // this attribute is used to control the text displayed by when the 
    // current link is expanded, the default value is 'Hide'
    var linkename= $(this).attr("data-ename");
    if (linkename === null || linkename === undefined || linkename === NaN) {
        linkename = "Hide";
    }

    // console.log(linkname);

    // extra data in json format.
    var linkedata = $(this).attr("data-edata");
    if (linkedata === null || linkedata === undefined || linkedata === NaN) {
        linkedata = "";
    }
    // the type of the link, later used as the displaytype, in display.php
    var linktype = $(this).attr("data-type");
    // the identifying id for the 'tr' element and div display elements
    // that are used for displaying retrieved information
    var controlid = $(this).attr("data-divid");
    if (linkname == "edit" || linkname == "view") {

    } else if (linkname == "remove" || linkname == "delete") {
        $('tr[data-id=' + controlid + ']').fadeOut(500);
    }

    var loadstate = $('tr[name=tableeditcontainer][data-divid=' + controlid + '] div[data-type=editmodal]').attr("data-load");
    var presentcontent = $('tr[name=tableeditcontainer][data-divid=' + controlid + '] div[data-type=editdisplay]').text();
    presentcontent = presentcontent.replace(/\s\s*/g, "");
    // // console.log(linkname,linktype,controlid,loadstate,presentcontent,$('tr[name=tableeditcontainer][data-divid='+controlid+'] div[data-type=editmodal]'));
    var datastate = $('tr[name=tableeditcontainer][data-divid=' + controlid + ']').attr("data-state");

    if (datastate == "inactive") {
        // console.log('inactive  zone');
        $('tr[name=tableeditcontainer] td div[data-type=editmodal]').css({
            "height": "0"
        });
        $('tr[name=tableeditcontainer] td div[data-type=editmodal]').css({
            "min-height": ""
        });
        $('tr[name=tableeditcontainer] td').css("padding", "0px");
        $('tr[name=tableeditcontainer]').attr("data-state", "inactive");
        $('tr[name=tableeditcontainer] td div[data-type=editdisplay]').html("");
        if (linkname !== "disablecomment" && linkname !== "activatecomment" && linkname !== "reactivatecomment" && linkname !== "activatesubscriber" && linkname !== "disablesubscriber") {
            // replace the edit links text with appropriate value based on the linkname
            // this variable defaults to Edit as it is the main/expected text in this
            // type of user interaction

            var textdata = "Edit";
            if (linkname == "view") {
                textdata = "View";
            }
            if(linkoname!==""){
                textdata=linkoname;
            }

            $('td[name=trcontrolpoint] a').text(textdata);
            $('td[name=trcontrolpoint] a[data-divid=' + controlid + ']').text(linkename);
            $('tr[name=tableeditcontainer][data-divid=' + controlid + '] td').css("padding", "8px 5px 8px");
        }

        // control point for static table behaviour, i.e interactions with, the backend
        // data output tables that dont involve any page change or ajax loading.
        // good for javascript only editing
        if (linkname !== "disablecomment" && linkname !== "activatecomment" && linkname !== "reactivatecomment" && linkname !== "activatesubscriber" && linkname !== "disablesubscriber") {
            $(this).text(linkename);
            $('tr[name=tableeditcontainer][data-divid=' + controlid + '] td').css("padding", "8px 5px 8px");
        }
        var targetheight = $('tr[name=tableeditcontainer][data-divid=' + controlid + '] div[data-type=editdisplay]').height();
        if (loadstate == "unloaded" || presentcontent == "") {
            // control point for static table behaviour, good for javascript only editing
            // this section handles the ajax request that usually accompanies the admin
            // data output table content link clicks
            if (linkname !== "disablecomment" && linkname !== "activatecomment" && linkname !== "reactivatecomment" && linkname !== "activatesubscriber" && linkname !== "disablesubscriber") {
                $('div[data-type=editmodal][data-divid=' + controlid + ']').animate({
                    height: "" + targetheight + ""
                }, 500, function() {
                    $(this).css({
                        "min-height": "" + targetheight + "",
                        "height": "auto"
                    });
                    var editreq = new Request();
                    editreq.generate('tr[name=tableeditcontainer][data-divid=' + controlid + '] div[data-type=editdisplay]', true);
                    //enter the url
                    editreq.url('' + host_addr + 'snippets/display.php?displaytype=' + linktype + '&editid=' + controlid + '&extraval=admin&datamap=' + linkedata + '');
                    //send request
                    editreq.opensend('GET');
                    //update dom when finished, takes four params targetElement,entryType,entryEffect,period
                    editreq.update('tr[name=tableeditcontainer][data-divid=' + controlid + '] div[data-type=editdisplay]', 'html', 'fadeIn', 1000);
                    $('tr[name=tableeditcontainer][data-divid=' + controlid + '] div[data-type=editdisplay]').attr("data-load", "loaded");
                });
            } else {
                var editreq = new Request();
                editreq.generate('tr[name=tableeditcontainer][data-divid=' + controlid + '] div[data-type=editdisplay]', false);
                //enter the url
                editreq.url('' + host_addr + 'snippets/display.php?displaytype=' + linktype + '&editid=' + controlid + '&extraval=admin');
                //send request
                editreq.opensend('GET');
                // // console.log('in here');
                //update dom when finished, takes four params targetElement,entryType,entryEffect,period
                editreq.update('tr[name=tableeditcontainer][data-divid=' + controlid + '] div[data-type=editdisplay]', 'nothing', '', 0);
            }
        } else if (loadstate == "loaded" || presentcontent !== "") {
            // control point for static table behaviour, good for javascript only editing

            $('tr[name=tableeditcontainer][data-divid=' + controlid + '] div[data-type=editmodal]').animate({
                height: "" + targetheight + ""
            }, 500, function() {
                $(this).css({
                    "min-height": "" + targetheight + ""
                });
            });

        }
        if (linkname !== "disablecomment" & linkname !== "activatecomment" && linkname !== "reactivatecomment" && linkname !== "activatesubscriber" && linkname !== "disablesubscriber") {
            $('tr[name=tableeditcontainer][data-divid=' + controlid + ']').attr("data-state", "active");
        }
    } else if (datastate == "active") {
        // console.log('inactive  zone');    
        // replace the 
        // replace the edit links text with appropriate value based on the linkname
        // this variable defaults to Edit as it is the main/expected text in this
        // type of user interaction
        var textdata = "Edit";
        if (linkname == "view") {
            textdata = "View";
        }
        if(linkoname!==""){
            textdata=linkoname;
        }
        $(this).text(textdata);
        $('tr[name=tableeditcontainer] td div#completeresultdisplaycontent').html("");
        $('div[data-type=editmodal][data-divid=' + controlid + ']').css({
            "min-height": "",
            "height": "0"
        });
        $('tr[name=tableeditcontainer][data-divid=' + controlid + '] td').css("padding", "0px");
        $('tr[name=tableeditcontainer][data-divid=' + controlid + ']').attr("data-state", "inactive");
        $('div[data-type=editmodal]').css({
            "min-height": ""
        });
        $('tr[name=tableeditcontainer] td').css("padding", "0px");
        $('tr[name=tableeditcontainer]').attr("data-state", "inactive");
        if (linkname !== "disablecomment" && linkname !== "activatecomment" && linkname !== "reactivatecomment" && linkname !== "activatesubscriber" && linkname !== "disablesubscriber") {
            $('td[name=trcontrolpoint] a').text(textdata);
        }
    }
    ;
});

$(document).on("click", "div.meneame div[data-name=paginationpageshold] a", function() {
    $("div.meneame div[data-name=paginationpageshold] a").removeClass("current");
    $(this).addClass("current");
    var page = $(this).attr("data-page");
    var ipp = $(this).attr("data-ipp");
    var curquery = $("input[name=curquery]").val();
    var datamap = $("input[name=datamap]").val();
    if (datamap === null || datamap === undefined || datamap === NaN) {
        datamap = "";
    }
    var outputtype = $("input[name=outputtype]").val();
    var curstamp = $("input[name=curstamp]") ? $("input[name=curstamp]").val() : "";
    var url = "" + host_addr + "snippets/display.php?displaytype=paginationpages&curquery=" + curquery + "&ipp=" + ipp + "&page=" + page + "&curstamp=" + curstamp + "&datamap=" + datamap + "&extraval=admin";
    // // console.log("achieved",page,ipp,curquery,outputtype,url);
    var pagesreq = new Request();
    pagesreq.generate('div.meneame div[data-name=paginationpageshold]', false);
    //enter the url
    pagesreq.url("" + url + "");
    //send request
    pagesreq.opensend('GET');
    //update dom when finished, takes four params targetElement,entryType,entryEffect,period
    pagesreq.update('div.meneame div[data-name=paginationpageshold]', 'html', '', 0);
    $("div.meneame input[name=currentview]").attr({
        "data-ipp": "" + ipp + "",
        "data-page": "" + page + "",
        "value": "" + page + ""
    }).trigger('change');
});

$(document).on("change", "div.meneame input[name=currentview]", function() {
    // console.log("in here");
    var page = $("div.meneame input[name=currentview]").attr("data-page");
    var ipp = $("div.meneame input[name=currentview]").attr("data-ipp");
    var curquery = $("input[name=curquery]").val();
    var outputtype = $("input[name=outputtype]").val();
    var curstamp = $("input[name=curstamp]") ? $("input[name=curstamp]").val() : "";
    var datamap = $("input[name=datamap]").val();
    if (datamap === null || datamap === undefined || datamap === NaN) {
        datamap = "";
    }
    // var url=''+host_addr+'snippets/display.php?displaytype=paginationpagesout&curquery='+curquery+'&outputtype='+outputtype+'&ipp='+ipp+'&page='+page+'&extraval=admin';
    var url = "" + host_addr + "snippets/display.php?displaytype=paginationpagesout&curquery=" + curquery + "&outputtype=" + outputtype + "&ipp=" + ipp + "&page=" + page + "&curstamp=" + curstamp + "&datamap=" + datamap + "&extraval=admin";
    var pagesoutreq = new Request();
    pagesoutreq.generate('div#contentdisplayhold div[data-name=contentholder],section.content div[data-name=contentholder]', true);
    //enter the url
    pagesoutreq.url('' + url + '');
    //send request
    pagesoutreq.opensend('GET');
    //update dom when finished, takes four params targetElement,entryType,entryEffect,period
    pagesoutreq.update('div#contentdisplayhold div[data-name=contentholder],section.content div[data-name=contentholder]', 'html', 'fadeIn', 500);
});

$(document).on("blur", "div.meneame  select[name=entriesperpage]", function() {
    var parent=$(this).parent().parent().parent();
    var ipp = $(this).val();
    var cview=parent.find("input[name=currentview]");
    var ipp2 = cview.attr("data-ipp");
    // console.log(typeof ipp,ipp2);

    /*ipp=Math.floor(ipp);
    ipp2=Math.floor(ipp2);*/
    if (ipp !== ipp2) {
        var page = 1;
        var curquery = parent.find("input[name=curquery]").val();
        var outputtype = parent.find("input[name=outputtype]").val();
        var curstamp = parent.find("input[name=curstamp]").length>0 ? parent.find("input[name=curstamp]").val() : "";
        var datamap = parent.find("input[name=datamap]").val();
        if (datamap === null || datamap === undefined || datamap === NaN) {
            datamap = "";
        }
        var url = "" + host_addr + "snippets/display.php?displaytype=paginationpages&curquery=" + curquery + "&ipp=" + ipp + "&page=" + page + "&curstamp=" + curstamp + "&datamap=" + datamap + "&extraval=admin";
        // // console.log("achieved",page,ipp,curquery,outputtype);
        var pagesreq = new Request();
        pagesreq.generate('div.meneame div[data-name=paginationpageshold]', false);
        //enter the url
        pagesreq.url('' + url + '');
        //send request
        pagesreq.opensend('GET');
        //update dom when finished, takes four params targetElement,entryType,entryEffect,period
        pagesreq.update('div.meneame div[data-name=paginationpageshold]', 'html', '', 0);
        $("div.meneame input[name=currentview]").attr({
            "data-ipp": "" + ipp + "",
            "data-page": "" + page + "",
            "value": "" + page + ""
        }).trigger('change');
    }
});






//for generic calender control, relies on connection.php function and display.php control
//the php function is calenderOut(date,time,year), refer to the connection.php page 

$(document).on("click",'div#calmonthpointer',function(){
  var months = new Array();
  months[1] = "January"; months[2] = "Feburary";
  months[3] = "March"; months[4] = "April";
  months[5] = "May"; months[6] = "June";
  months[7] = "July"; months[8] = "August";
  months[9] = "September"; months[10] = "October";
  months[11] = "November"; months[12] = "December";
  var curviewmonth=$('div#calDispDetails').attr("data-curmonth");
  var curyear=Math.floor($('div#calDispDetails').attr("data-year"));
  // var popmonth=months[nextmonth];
  var pointname=$(this).attr("name");
  var datatheme=$(this).attr("data-theme");
  var datacontrol=$(this).attr("data-control");
  var dataviewtype=$(this).attr("data-viewtype");
  var datapop=new Array();
  var datedata= new Date();
  var curmonth=datedata.getMonth();
  // var curyear=Math.floor(datedata.getFullYear());
  var nextyear=curyear;
  var prevyear=curyear;
  var prevmonth=Math.floor(curviewmonth)-1;
  var nextmonth=Math.floor(curviewmonth)+1;
  var data_target=$(this).attr('data-target');
  // data_target==""?data_target="":data_target=""+data_target+"";
  prevmonth<1? prevyear= curyear-1: prevyear;
  prevmonth<1? prevmonth=12: prevmonth;
  nextmonth>12? nextyear= curyear+1: nextyear;
  nextmonth>12? nextmonth=1: nextmonth;
  if(pointname=="calpointleft"){
    var requireddate="1-:-"+prevmonth+"-:-"+prevyear+"-:-"+data_target+"-:-"+datatheme+"-:-"+datacontrol+"-:-"+dataviewtype+"";
    var data_pop=""+months[prevmonth]+", "+prevyear+"";
    $('div#calDispDetails').html(data_pop).attr({"data-curmonth":""+prevmonth+"","data-year":""+prevyear+""});
  }else if(pointname=="calpointright"){
    var requireddate="1-:-"+nextmonth+"-:-"+nextyear+"-:-"+data_target+"-:-"+datatheme+"-:-"+datacontrol+"-:-"+dataviewtype+"";
    var data_pop=""+months[nextmonth]+", "+nextyear+"";
    $('div#calDispDetails').html(data_pop).attr({"data-curmonth":""+nextmonth+"","data-year":""+nextyear+""});
  }
  // // console.log(datatheme,requireddate);
  var calFullreq=new Request();
  //enter the url 
  if(usertype!=="admin"){
    calFullreq.generatetwo('#calHold #calDaysHold',true);
    calFullreq.url(''+host_addr+'snippets/display.php?displaytype=calenderout&extraval='+requireddate+'');
  }else{
    calFullreq.generate('#calHold #calDaysHold',true);
    calFullreq.url(''+host_addr+'snippets/display.php?displaytype=calenderout&extraval='+requireddate+'');
  }
    
  //send request
  calFullreq.opensend('GET');
  //update dom when finished, takes four params targetElement,entryType,entryEffect,period
  calFullreq.update('#calHold #calDaysHold','html','fadeIn',1000);
  // console.log(pointname,data_pop,requireddate);
});
// legacy modal background overlay control
$(document).on("click","div#fullbackground",function(){
  // console.log("clicked fullbackground");
  event.stopPropagation();
  // $(this).fadeOut(500);
  $('#fullbackground').fadeOut(500);
  $('#fullcontenthold').fadeOut(500);
});

$(document).on("click",'div#calDaysHold div#calDay',function(){
  $('div#calDaysHold #calDay').removeClass("activedate");
  $(this).addClass("activedate");
  var date=$(this).attr('name');
  var data_target=$(this).attr('data-target');
  if(data_target!==""){
  $('input[name='+data_target+']').attr("value",""+date+"");
  }
});


function getPageName(){
  var pagedata=document.URL;

  if(pagedata.indexOf('www.')>-1||pagedata.indexOf('com')>-1||pagedata.indexOf('co.uk')>-1||pagedata.indexOf('org')>-1||pagedata.indexOf('co.')>-1){
    if(pagedata.indexOf('/')>-1){
    var pagedatatwo=pagedata.split(".");
   var totalsplitone=pagedatatwo.length - 2;
   var realclone=pagedatatwo[totalsplitone];
   var realpage=pagedatatwo[totalsplitone];
   if(typeof(realpage)!=="undefined"){
   var nexthalve=realpage.split("/");
   var totalsplit=nexthalve.length - 1;
   for(var i=0;i<pagedatatwo.length;i++){
    // // console.log(pagedatatwo[i],i); 
   }
   var realpage=nexthalve[totalsplit];
   realclone.indexOf('http:')>-1||realclone.indexOf('https:')>-1?realpage="index":realpage=realpage;
   }else{
    realpage="index";
   }
      // // // console.log(realpage);
    }else{
      var realpage="index";
    }
  }else{
    var pagedatatwo=pagedata.split(".");
   var realpage=pagedatatwo[0];
   var testval="/";
   var nexthalve=realpage.split("/");
   var totalsplit=nexthalve.length - 1;
   var realpage=nexthalve[totalsplit];
  }
 return realpage;
}

function colorChanger(targetElement,originalcolor,targetcolor,monitor,textocolor,textflipcolor){

  var colorChangerFunc;
  var prevcolor=$(''+targetElement+'').css("background-color");

  if(monitor==1){
  monitor=0;
  $(''+targetElement+'').css("background-color",""+targetcolor+"");
  $(''+targetElement+'').css("color",""+textflipcolor+"");
  }else if(monitor==0){
  monitor=1;
  $(''+targetElement+'').css("background-color",""+originalcolor+"");
  $(''+targetElement+'').css("color",""+textocolor+"");

  }
  // // console.log(targetElement,originalcolor,prevcolor);
  colorChangerFunc=window.setTimeout("colorChanger('"+targetElement+"','"+originalcolor+"','"+targetcolor+"',"+monitor+",'"+textocolor+"','"+textflipcolor+"')",2000);
}

function getExtension(entry="") {
    var outs = new Array();
    outs['imageexts']=["jpg" , "jpeg" , "png" , "gif", "bmp", "ico", "svg"];
    outs['videoexts']=["mp4" , "3gp" , "flv" , "swf" , "webm"];
    outs['audioexts']=["mp3" , "ogg" , "wav" , "amr"];
    outs['officeexts']=["doc" , "docx" , "xls" , "xlsx" , "ppt" , "pptx"];
    outs['pdfexts']=["pdf"];
    outs['compressedexts']=["tar" , "gz" , "zip" , "7z" , "rar"];

    if (typeof (entry) == "string"&& entry !== "") {
        var extension = entry.split('.');
        var alength = extension.length;
        var realposition = alength - 1;
        extension = extension[realposition].toLowerCase();
        outs['rextension'] = "" + extension + "";
        var entrytype = "";
        if (outs['imageexts'].indexOf(extension)>-1) {
            entrytype = "image";
        } else if (outs['videoexts'].indexOf(extension)>-1) {
            entrytype = "video";
        } else if (outs['officeexts'].indexOf(extension)>-1) {
            entrytype = "office";
        } else if (extension == "pdf") {
            entrytype = "pdf";
        } else if (outs['audioexts'].indexOf(extension)>-1) {
            entrytype = "audio";

        } else if (outs['compressedexts'].indexOf(extension)>-1) {
            entrytype = "compressed";

        } else {
            entrytype = "others";
        }
        outs['imageerrormsg'] = "Please select a valid image file in the accepted format";
        outs['videoerrormsg'] = "Please select a valid video file in the accepted format e.g .webm, .flv or .3gp";
        outs['officeerrormsg'] = "Please select a office document file in the accepted format";
        outs['pdferrormsg'] = "Please choose a valid pdf document";
        outs['audioerrormsg'] = "Please select a valid audio file in the accepted format";
        outs['otherserrormsg'] = "An error occured";

        outs['type'] = entrytype;
        outs['extension'] = extension;
        return outs;
    } else {
        return outs['type']="";
        return outs['extension']="";
    }

}

function getPageGetData(partname){
var pagedata=document.URL;
var getdata="nothing";
var pagedatatwo="";
var pagefol="nothing";
if(partname=="bookmark"){
 pagedatatwo=pagedata.split("#");
 pagefol=pagedatatwo[1];
 getdata=pagefol;
}else if(pagedata.indexOf(""+partname+"") > -1){
 pagedatatwo=pagedata.split(""+partname+"");
 pagefol=pagedatatwo[1];
if(pagefol!=="nothing"){
if(pagefol.indexOf("&") > -1&&pagefol.indexOf("#") < 0){
getdata=pagefol.split("&");
var totaldata=getdata.length;
getdata=getdata[0].split("=");
//getdata=getdata[1];
getdata=getdata[1];
 }else if(pagefol.indexOf("&") < 0 &&pagefol.indexOf("#") < 0){
getdata=pagefol.split("=");
getdata=getdata[1];
 }else if(pagefol.indexOf("&") > -1&&pagefol.indexOf("#") > -1){
  getdata=pagefol.split("&");
getdata=getdata[0].split("#");
var totaldata=getdata.length;
getdata=getdata[0].split("=");
getdata=getdata[1];
 }else if(pagefol.indexOf("&") < 0 &&pagefol.indexOf("#") > -1){
 getdata=pagefol.split("#");
 getdata=getdata[0].split("=");
getdata=getdata[1];
 }
}
}
 return getdata;
}


function produceImageFitSize(curwidth,curheight,contwidth,contheight,auto){
var output=new Array();
output['width']="20px";
output['height']="20px";
output['style']="";
output['truewidth']=curwidth;
output['trueheight']=curheight;
var style="";
if(curwidth!==""&&curheight!==""&&contwidth!==""&&contheight!==""){
  if(contwidth>contheight){
    if(curwidth>contwidth||curheight>contheight){

      if(curwidth>curheight&&curheight>=contheight&&curwidth>contwidth){
        curwidth=contwidth;
        style='cursor:pointer;height:'+contheight+'px;width:'+curwidth+'px;margin:auto;';
      }else if(curwidth<curheight&&curheight>contheight&&curwidth>contwidth){
        var extrawidth=Math.floor(curwidth-contheight);
        var dimensionratio=curwidth/curheight;
        // // console.log(dimensionratio);

         curwidth=Math.floor(contheight*dimensionratio);
          var widthdiff=contwidth-curwidth;
          if(widthdiff>0&&contwidth>767){
          var marginleft=Math.floor(widthdiff/2);
          }else{
            var marginleft=0;
          }
        if(extrawidth>contwidth&&extrawidth>contheight){
          extrawidth=curwidth-extrawidth;
        }/*else if (curwidth>contwidth&&curwidth>contheight) {
          curwidth=curwidth-120;
        }*/

        style='cursor:pointer;width:'+curwidth+'px;height:'+contheight+'px;';
        if(auto=="on"){
          style='cursor:pointer;width:'+curwidth+'px;height:'+contheight+'px;test:;';
        }
      }else if(curwidth<curheight&&curheight>=contheight&&curwidth<contwidth){
        var dimensionratio=curwidth/curheight;
        // // console.log(dimensionratio);

         curwidth=Math.floor(contheight*dimensionratio);
          var widthdiff=contwidth-curwidth;
          if(widthdiff>0&&contwidth>767){
          var marginleft=Math.floor(widthdiff/2);
          }else{
            var marginleft=0;
          }

          style='cursor:pointer;width:'+curwidth+'px;height:'+contheight+'px;';
      }else if(curwidth>curheight&&curheight<contheight&&curwidth>contwidth){
        var dimensionratio=curwidth/curheight;
        // // console.log(dimensionratio);
         curwidth=Math.floor(contheight*dimensionratio);
          var difference=contheight-curheight;
          var margintop=Math.floor(difference/2);
          if(auto=="on"){
            style='cursor:pointer;width:'+contwidth+'px;height:'+curheight+'px;margin-top:auto;'; 
          }else{      
            style='cursor:pointer;width:'+contwidth+'px;height:'+curheight+'px;margin-top:'+margintop+'px;'; 
          }
      }else if(curwidth<curheight&&curheight<contheight){
        var difference=contheight-curheight;
        var margintop=Math.floor(difference/2);
        curwidth=curheight-10;
        if(auto=="on"){
          style='cursor:pointer;width:'+curwidth+'px;height:'+curheight+'px;margin-top:auto;'; 
        }else{      
          // style='cursor:pointer;width:'+curwidth+'px;height:'+curheight+'px;margin-top:'+margintop+'px;'; 
            style='cursor:pointer;width:'+curwidth+'px;height:'+curheight+'px;margin-top:'+margintop+'px;';
        }
      }else if(curwidth==curheight&&curheight>contheight){
        var marginleft=Math.floor(contwidth)-Math.floor(contheight);
        marginleft=Math.floor(marginleft/2);
        style='cursor:pointer;width:'+contheight+'px;height:'+contheight+'px;'; 
      }
    }else{
        var difference=contheight-curheight;
        var margintop=Math.floor(difference/2);
        var widthdiff=contwidth-curwidth;
        var marginleft=Math.floor(widthdiff/2);
        style='cursor:pointer;width:'+curwidth+'px;height:'+curheight+'px;margin-top:'+margintop+'px;';
    }

  }else if(contwidth<contheight){
    style='cursor:pointer;width:100%;height:auto;margin:auto;';

  }

    output['width']=curwidth;
    output['height']=curheight;
    output['style']=""+style+"";
  }
    return output;
}
// ^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$.
function emailValidatorReturnable(email) {
  var outs=[];
  var inputname7=email.replace(/\s\s*/g,"");
  var pattern=/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,}(\.[a-zA-Z]{2,})?$/;
  var  statuscontrol= "true",
        errormsg="none";
        ;
    // console.log(email,inputname7);

  if(inputname7.length>0){
    var match=inputname7.search(pattern);
    if(match<0){
      statuscontrol= "false";
      errormsg="Email invalid";
    }
  }else{
    statuscontrol="false";
    errormsg="No email provided";
  }  
  outs['status']=statuscontrol;
  alert(outs['status']);
  outs['errormsg']=errormsg;
  return outs;
}
function emailValidatorReturnableTwo(email) {
    var outs = [];
    var inputname7 = email.replace(/\s\s*/g, "");
    var pattern = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,}(\.[a-zA-Z]{2,})?$/;
    var statuscontrol = "true",errormsg = "none";
    ;
    // console.log(email,inputname7);
    
    if (inputname7.length > 0) {
        var match = inputname7.search(pattern);
        if (match < 0) {
            statuscontrol = "false";
            errormsg = "Email invalid";
        }
    } else {
        statuscontrol = "false";
        errormsg = "No email provided";
    }
    outs['status'] = statuscontrol;
    // alert(outs['status']);
    outs['errormsg'] = errormsg;
    return outs;
}
function doFJCALert(obj,errorheader,errormsg,type){
  var outs=[];
  var hideit="hidden";
  if(type=="showheader"){
    hideit="";
  }

  var alertel='<div class="alert-specialv validation-error">'
              +'  <h6 class="'+hideit+'">'+errorheader+'</h6>'
            +'  <p>'+errormsg+'.</p>'
            +'</div>';
      // // console.log(alertel);
      $(obj).focus().parent().find('.alert-specialv.validation-error').remove();
      $(alertel).insertAfter(obj);
}
function emailValidator(inputname6){
  // // console.log(inputname6);
  var statuscontrol=true;
  var inputname7=inputname6.replace(/\s\s*/g,"");
  if(inputname7.length<1){
    statuscontrol= false;
    window.alert("E-mail field is empty");
    $("input[name=email]").css('border','1px solid red').focus();
  }else if (inputname7.indexOf("/") > -1) {
    statuscontrol= false;
    window.alert("E-mail address has invalid character: /");
    $("input[name=email]").css('border','1px solid red').focus();
  }else if (inputname7.indexOf(":") > -1) {
    statuscontrol= false;
    window.alert("E-mail address has invalid character: :");
    $("input[name=email]").css('border','1px solid red').focus();
    }else if (inputname7.indexOf(",") > -1) {
      statuscontrol= false;
    window.alert("E-mail address has invalid character: ,");
    $("input[name=email]").css('border','1px solid red').focus();
    }else if (inputname7.indexOf(";") > -1) {
      statuscontrol= false; 
    window.alert("E-mail address has invalid character: ;")
    $("input[name=email]").css('border','1px solid red').focus();
  }else if (inputname7.indexOf("@") < 0) {
    statuscontrol= false;
    window.alert("E-mail address is missing @");
    $("input[name=email]").css('border','1px solid red').focus();
  }else if (inputname7.indexOf("\.") < 0) {
    statuscontrol= false;
    window.alert("E-mail address is missing .");
    $("input[name=email]").css('border','1px solid red').focus();
  }else if (inputname7.indexOf("@") > -1) {
    var inputtester=inputname7.split("@");
    var firstpart=inputtester[0];
    var secondpart=inputtester[1];
    // // console.log(secondpart);
    if(secondpart.length<2&&secondpart[0]=="."){
      statuscontrol= false;
      window.alert('Complete your email address properly,server name missing');
      $("input[name=email]").css('border','1px solid red').focus();
    }else if(firstpart.length<1){
      window.alert('You seem to have...errr..left out something in your email, we cannot find anything before the @');
      $("input[name=email]").css('border','1px solid red').focus();
    }else if (inputname7.indexOf("\.") > -1) {
      var inputtester=inputname7.split(".");
      var totalsplit=inputtester.length - 1;
      var realvalue=inputtester[totalsplit];
      if(realvalue.length<1){
          statuscontrol= false;
        window.alert('Complete your email address properly,domain name missing, try .com .net e.t.c');
        $("input[name=email]").css('border','1px solid red').focus();
      }
    }
  }else{
    statuscontrol=true;
    // // console.log(statuscontrol);
  }
  // $("input[name=email]").attr("value",""+inputname7+"");
  return statuscontrol;
}

function testCharVal(string){
  var testing="false";
  if(string!==""){
  var newstring=string.toLowerCase();
  if(newstring.indexOf("a") > -1|| newstring.indexOf("b") > -1|| newstring.indexOf("c") > -1|| newstring.indexOf("d") > -1|| newstring.indexOf("e") > -1|| newstring.indexOf("f") > -1|| newstring.indexOf("g") > -1|| newstring.indexOf("h") > -1|| newstring.indexOf("i") > -1|| newstring.indexOf("j") > -1|| newstring.indexOf("k") > -1|| newstring.indexOf("l") > -1|| newstring.indexOf("m") > -1|| newstring.indexOf("n") > -1|| newstring.indexOf("o") > -1|| newstring.indexOf("p") > -1|| newstring.indexOf("q") > -1|| newstring.indexOf("r") > -1|| newstring.indexOf("s") > -1|| newstring.indexOf("t") > -1|| newstring.indexOf("u") > -1|| newstring.indexOf("v") > -1|| newstring.indexOf("w") > -1|| newstring.indexOf("x") > -1|| newstring.indexOf("y") > -1|| newstring.indexOf("/") > -1|| newstring.indexOf(",") > -1|| newstring.indexOf(".") > -1|| newstring.indexOf("<") > -1|| newstring.indexOf(">") > -1|| newstring.indexOf("?") > -1|| newstring.indexOf("\\") > -1|| newstring.indexOf("|") > -1|| newstring.indexOf("'") > -1|| newstring.indexOf("\"") > -1|| newstring.indexOf(":") > -1|| newstring.indexOf(";") > -1|| newstring.indexOf("}") > -1|| newstring.indexOf("]") > -1|| newstring.indexOf("{") > -1|| newstring.indexOf("[") > -1|| newstring.indexOf("`") > -1|| newstring.indexOf("~") > -1|| newstring.indexOf("!") > -1|| newstring.indexOf("@") > -1|| newstring.indexOf("#") > -1|| newstring.indexOf("$") > -1|| newstring.indexOf("%") > -1|| newstring.indexOf("^") > -1|| newstring.indexOf("&") > -1|| newstring.indexOf("*") > -1|| newstring.indexOf("(") > -1|| newstring.indexOf(")") > -1|| newstring.indexOf("_") > -1|| newstring.indexOf("+") > -1|| newstring.indexOf("1") > -1|| newstring.indexOf("2") > -1|| newstring.indexOf("3") > -1|| newstring.indexOf("4") > -1|| newstring.indexOf("5") > -1|| newstring.indexOf("6") > -1|| newstring.indexOf("7") > -1|| newstring.indexOf("8") > -1|| newstring.indexOf("9") > -1|| newstring.indexOf("0") > -1|| newstring.indexOf("-") > -1|| newstring.indexOf("=") > -1){
    testing="true";
  }
    
  }
  return testing;
}

function hideBind(clickElem,targetElement,effect,period,extraAttr,extraVal){
$(document).on("click",''+clickElem+'',function(){
var extradatas=extraAttr.split(".");
extraAttr=extradatas[0];
var elemType=extradatas[1];
  var testvalue="";
testvalue=$(this).attr("data-id");
if(extraAttr!==""&&extraAttr!=="multiple"){
$(''+targetElement+'').attr(""+extraAttr+"",""+extraVal+"");

}else if(extraAttr=="multiple"){

// console.log(extraAttr,elemType,testvalue,extraVal,clickElem);
$(''+targetElement+' '+elemType+'['+extraVal+'='+testvalue+']');
if(effect=="slidedown"||effect=="slideDown"){
$(''+targetElement+' '+elemType+'['+extraVal+'='+testvalue+']').animate({height:"0px"},period,function(){
  $(this).hide();
});
}else if (effect=="fadeOut"||effect=="fadeout") {
$(''+targetElement+' '+elemType+'['+extraVal+'='+testvalue+']').fadeOut(period);
}else if (effect=="hide"||effect=="Hide") {
$(''+targetElement+' '+elemType+'['+extraVal+'='+testvalue+']').hide(period);
}else if (effect=="html"||effect=="Html") {
$(''+targetElement+' '+elemType+'['+extraVal+'='+testvalue+']').hide(period).html('');
}
}else{
if(effect=="slidedown"||effect=="slideDown"){
$(''+targetElement+'').animate({height:"0px"},period,function(){
  $(this).hide();
});
}else if (effect=="fadeOut"||effect=="fadeout") {
$(''+targetElement+'').fadeOut(period);
}else if (effect=="hide"||effect=="Hide") {
$(''+targetElement+'').hide(period);
}else if (effect=="html"||effect=="Html") {
$(''+targetElement+'').hide(period).html('');
}
}  
});


}
function hoverChange(targetImg,hoverImg){
$(document).ready(function(){
  var realimg="";
// // console.log(realimg);
$(document).on("mouseenter",''+targetImg+'',function(){
 realimg=$(''+targetImg+'').attr("src");
$(this).attr("src",""+hoverImg+"");
});
$(document).on("mouseleave",''+targetImg+'',function(){
$(this).attr("src",""+realimg+"");  
});
});
}
function errorControl(countval){
$(document).ready(function(){
if(countval>0){
  $('#contentleftcontent h2').html("<font>You Have done this three times, sorry but you cant login for the next "+countval+"seconds.</font>");
countval--;
// console.log(countval)
window.setTimeout('errorControl('+countval+')', 1000);
  $('#backhold').css("display","block");
//  document.getElementById('contentleftcontent').firstChild('');

}else{
window.clearTimeout(errorControl);  
  $('#backhold').css("display","none");
  $('#contentleftcontent h2').css("display","none");
}
});
}

function effectControl(targetElement,entryType,entryEffect,period,entryVal){
  var timeoutval=period/2;
  timeoutval=Math.floor(timeoutval);
  //// console.log(timeoutval);
  if(entryType!=="insertBefore"&&entryType!=="insertAfter"){
    if(entryEffect=="fadein"||entryEffect=="fadeIn"){
          $(document).ready(function(){
          $(''+targetElement+'').hide().html(entryVal).fadeIn(period);
          });
    }else if(entryEffect=="fadeto"||entryEffect=="fadeTo"){
          $(document).ready(function(){
          $(''+targetElement+'').hide().html(entryVal).fadeTo(period,0.9,function(){});
          });
    }else if(entryEffect=="show"||entryEffect=="Show"){
          $(document).ready(function(){
          $(''+targetElement+'').hide().html(entryVal).show(period);
            $(''+targetElement+'').attr({"style":""});
          });
    }else if(entryEffect=="slidedown"||entryEffect=="slideDown"){
          $(document).ready(function(){
          $(''+targetElement+'').hide().html(entryVal).slideDown(period,function(){
              $(''+targetElement+'').attr({"style":""});
          });
          });
    }else{
          $(''+targetElement+'').html(entryVal)
    }
  }else if(entryType=="insertBefore" || entryType=="insertAfter"){
    if(entryType=="insertBefore" ||entryType=="insertbefore"){
      if(entryEffect=="fadein"||entryEffect=="fadeIn"){
        $(document).ready(function(){
        $(''+entryVal+'').insertBefore(''+targetElement+'').fadeIn(period);
        });

      }else if(entryEffect=="fadeto"||entryEffect=="fadeTo"){
      $(document).ready(function(){
      $(entryVal).insertBefore(''+targetElement+'').fadeTo(period,0.8,function(){});
      });

      }else if(entryEffect=="slidedown"||entryEffect=="slideDown"){
      $(document).ready(function(){
       $(entryVal).css("height","0px").insertBefore(''+targetElement+'').slideDown(period,function(){});
      });

      }else if(entryEffect=="show"||entryEffect=="Show"){
      $(document).ready(function(){
      $(entryVal).css("visibility","none").insertBefore(''+targetElement+'').show(period);
      });
      }
    }else if(entryType=="insertAfter" ||entryType=="insertafter"){
      if(entryEffect=="fadein"||entryEffect=="fadeIn"){
      $(document).ready(function(){
      $(entryVal).css("visibility","none").insertAfter(''+targetElement+'').fadeIn(period);
      });

      }else if(entryEffect=="fadeto"||entryEffect=="fadeTo"){
      $(document).ready(function(){
      $(entryVal).css("visibility","none").insertAfter(''+targetElement+'').fadeTo(period,0.8,function(){});
      });

      }else if(entryEffect=="slidedown"||entryEffect=="slideDown"){
      $(document).ready(function(){
       $(entryVal).insertAfter(''+targetElement+'').slideDown(period,function(){
        $(''+targetElement+'').attr({"style":""});
       });
      });

      }else if(entryEffect=="show"||entryEffect=="Show"){
      $(document).ready(function(){
      $(entryVal).insertAfter(''+targetElement+'').show(period);
        $(''+targetElement+'').attr({"style":""});
      });
      }else{
            $(''+targetElement+'').html(entryVal)
      }
    }
  }
}

function Requesttwo(maincontainer,waitdisplay,waittype,sendtype,targetElement,entryType,entryEffect,period,url){
  if(waitdisplay===true && waittype=="admin"){
    $(''+maincontainer+'').html('<img src="'+host_addr+'images/waiting.gif" class="total2"/>');
  }else if(waitdisplay===true && waittype=="viewer"){
    $(''+maincontainer+'').html('<img src="'+host_addr+'images/waiting.gif" class="total2"/>');
  }
  var requestthree=false;
  try {
    requestthree = new XMLHttpRequest();
  } catch (trymicrosoft) {
    try {
      requestthree = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (othermicrosoft) {
      try {
        requestthree = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (failed) {
        requestthree = false;
      }
    }
  }
  requestthree.open(""+sendtype+"", url, true);
  requestthree.send(null);  
  requestthree.onreadystatechange=function(){
  if(requestthree.readyState == 2||requestthree.readyState == 1 || requestthree.readyState == 0){

  }else if (requestthree.readyState == 4) {
  var response=requestthree.responseText;
  // // console.log(requestthree);
  if(targetElement!=="none"){
  effectControl(targetElement,entryType,entryEffect,period,response);
  }else if(targetElement=="none"){
    if(entryType=="reload"){
      location.reload();
    }
  }else{
    outs=response;
  }

  // // console.log(targetElement,response,url,requestthree,period);
}

}
  // requestthree.onreadystatechange=generalUpdatetwo();
}

var Request=function(){
this.requesttwo = false;
var url;
var response;
}
//creates reference request object
Request.prototype={
  //get the url
url:function(dataentry){
url=dataentry;
},
opensend:function(sentType){
      this.requesttwo.open(""+sentType+"", url, true);
      this.requesttwo.send(null);  
},
update:function(targetElement,entryType,entryEffect,period){
  outs="nothing";
  
  requesttwo=this.requesttwo.readyState;
  this.requesttwo.onreadystatechange= function(){
   if(this.readyState == 2||this.readyState == 1 || this.readyState == 0){
    
   }else if (this.readyState == 4) {
   var response=this.responseText;
   // console.log(response);
if(targetElement!=="none"){
effectControl(targetElement,entryType,entryEffect,period,response);
if(this.maincontainer!==targetElement){
$(''+this.maincontainer+'').html("");
}
}else{
  if(entryType=="reload"){
    location.reload();
  }
}

// // console.log(targetElement,response,url,this.requesttwo,period);
}

}
  // this.requesttwo.onreadystatechange=generalUpdate;

},
updatetwo:function(){
  outs="nothing";
  

this.requesttwo.onreadystatechange= function(){
   if(this.requesttwo.readyState == 2||this.requesttwo.readyState == 1 || this.requesttwo.readyState == 0){
    
   }else if (this.requesttwo.readyState == 4) {
   var response=this.requesttwo.responseText;
// // console.log(response);
return response;
}
}
  // this.requesttwo.onreadystatechange=generalUpdate;

},
  generate: function(maincontainer,waitdisplay){
    this.maincontainer=maincontainer;
  if(waitdisplay===true){
    $(''+maincontainer+'').html('<img src="'+host_addr+'images/waiting.gif" class="total2"/>');
  }
try {
  this.requesttwo = new XMLHttpRequest();
} catch (trymicrosoft) {
  try {
    this.requesttwo = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (othermicrosoft) {
    try {
      this.requesttwo = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (failed) {
      this.requesttwo = false;
    }
  }
}    

  },
  generatetwo: function(maincontainer,waitdisplay){
  if(waitdisplay===true){
    $(''+maincontainer+'').html('<img src="./images/waiting.gif" class="total2"/>');
  }
try {
  this.requesttwo = new XMLHttpRequest();
} catch (trymicrosoft) {
  try {
    this.requesttwo = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (othermicrosoft) {
    try {
      this.requesttwo = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (failed) {
      this.requesttwo = false;
    }
  }
}    

  },
  extraFunctions: function(targetElement,value){
    if(this.requesttwo.readyState == 4){
      $(""+targetElement+"").html(""+value+"");
    }
  }
}
function endSlideMotion(){
if(slideFunction){
  clearTimeout(slideFunction);
  slideFunction="";
}
}

var slideFunction="";
var slidePoint=0;
function slideMotion(targetElement,slideDir,moveval,period,timeout,statestart){
/*
Timeout is the time inbetween the slide motions while
stateStart is a value of 1 for motion firing off on pageload or
 0 for motion that waits the timeout period specified then changes to
 1 for continued motion.  
*/
 
// // console.log(targetElement,slideDir,moveval,period,timeout,statestart);
if(statestart=="stop"){
  window.clearTimeout(slideFunction);
  // slideFunction=window.setTimeout("slideMotion('"+targetElement+"','"+slideDir+"','"+moveval+"','"+period+"','"+timeout+"','stop')",timeout);
}else if(statestart==1){
  var curposleft=$(''+targetElement+'').css("left");  
  curposleft2=curposleft.split("p");
  curposleft2=Math.floor(curposleft2[0]);
  var curposright=$(''+targetElement+'').css("right");
  var curposright2=curposright.split("p");
  curposright2=curposright2[0];
  var curpostop=$(''+targetElement+'').css("top");
  curpostop2=curpostop.split("p");
  curpostop2=Math.floor(curpostop2[0]);
  var curposbottom=$(''+targetElement+'').css("bottom");
  var curposbottom2=curposbottom.split("p");
  curposbottom2=Math.floor(curposbottom2[0]);
  var totalwidth=$(''+targetElement+'').width();
  var totalheight=$(''+targetElement+'').height();
  // // console.log(curposleft,curposright,curposright2,curpostop,curposbottom,totalwidth,totalheight);
if(slideDir=="left"){
if(curposleft=="auto"||curposleft2<totalwidth-moveval){
$(''+targetElement+'').animate({left:"+="+moveval+""},period,function(){

});     
}else{
  $(''+targetElement+'').animate({left:"0"},period,function(){});
}
}else if(slideDir=="right"){
if(curposright=="auto"||curposright2<totalwidth-moveval){
$(''+targetElement+'').animate({right:"+="+moveval+""},2000,function(){
slidePoint+=1;

});  
}else{
  $(''+targetElement+'').animate({right:"0"},period,function(){});
}
}else if(slideDir=="bottom"){
  if(curposbottom=="auto"||curposbottom2<totalheight-moveval){
$(''+targetElement+'').animate({bottom:"+="+moveval+""},2000,function(){

});  
}else{
  $(''+targetElement+'').animate({bottom:"0"},period,function(){});
}
}else if(slideDir=="top"){
if(curpostop=="auto"||curpostop2<totalheight-moveval){
$(''+targetElement+'').animate({top:"+="+moveval+""},period,function(){

});  
}else{
  $(''+targetElement+'').animate({top:"0"},period,function(){});
}
}
if(statestart=="stop"){
clearTimeout(slideFunction);
}else if(timeout!==""&&timeout>0){
slideFunction=window.setTimeout("slideMotion('"+targetElement+"','"+slideDir+"',"+moveval+","+period+","+timeout+","+statestart+")",timeout);
}
}else if(statestart==0){
slideFunction=window.setTimeout("slideMotion('"+targetElement+"','"+slideDir+"',"+moveval+","+period+","+timeout+",1)",timeout);
}else{
 
// // console.log(curposleft,curposright,curposright2,curpostop,curposbottom,totalwidth,totalheight);
if(slideDir=="left"){
if(curposleft=="auto"||curposleft2<totalwidth-moveval){
$(''+targetElement+'').animate({left:"+="+moveval+""},period,function(){

});     
}else{
  $(''+targetElement+'').animate({left:"0"},period,function(){});
}
}else if(slideDir=="right"){
if(curposright=="auto"||curposright2<totalwidth-moveval){
$(''+targetElement+'').animate({right:"+="+moveval+""},2000,function(){

});  
}else{
  $(''+targetElement+'').animate({right:"0"},period,function(){});
}
}else if(slideDir=="bottom"){
  if(curposbottom=="auto"||curposbottom2<totalheight-moveval){
$(''+targetElement+'').animate({bottom:"+="+moveval+""},period,function(){

});  
}else{
  $(''+targetElement+'').animate({bottom:"0"},period,function(){});
}
}else if(slideDir=="top"){
if(curpostop=="auto"||curpostop2<totalheight-moveval){
$(''+targetElement+'').animate({top:"+="+moveval+""},period,function(){

});  
}else{
  $(''+targetElement+'').animate({top:"0"},period,function(){});
}
} 
}

}
//control mobile panel toggle
$(document).on("click","a[data-name=navbartoggle],div[data-name=navbartoggle]",function(){
    // console.log($(this)," active element");
    var state=$(this).attr("data-state");
    var target=$(this).attr("data-target");
    var pullstyle=$(this).attr("data-pullstyle");
    if(state=="inactive"){
      $(''+target+'').addClass('activepanel');
      $(this).attr("data-state","active");
      $(this).addClass("toggleactive");
    }else if(state=="active"){
      $(''+target+'').removeClass('activepanel');
      $(this).removeClass('toggleactive');
      $(this).attr("data-state","inactive");
    }
});
// control auto generation of links for the mobile option
if($('#linkspanel ul')){
  var children=$('#linkspanel ul a').clone();
  $('.mobile-panel ul.mobile-links').html(children);
}


$(document).on("click","div[data-name=dropperpoint]",function(){
var curstate=$(this).attr("data-state");
var rotatetarget=$(this).attr("data-rotatetarget");
var target=$(this).attr("data-target");
var target_height=$('div[data-targetname='+target+'] .displaydropperhold').height();
// console.log($('div[data-targetname='+target+'] .displaydropperhold'),target_height);
if(curstate=="inactive"){
$(this).css({"-webkit-transform":"rotate(90deg)","moz-transform":"rotate(90deg)","o-transform":"rotate(90deg)","-ms-transform":"rotate(90deg)"});
$('div[data-targetname='+target+']').animate({height:""+target_height+""},1000,function(){
$('div[data-targetname='+target+']').css("height","auto");
});
$(this).attr("data-state","active");
}else if(curstate=="active"){
$(this).attr("data-state","inactive");
$(this).css({"-webkit-transform":"rotate(0deg)","moz-transform":"rotate(0deg)","o-transform":"rotate(0deg)","-ms-transform":"rotate(0deg)"});

$('div[data-targetname='+target+']').animate({height:"34"},1000,function(){

});
}
});
 var contentslides=$('div#slidepointhold div#slidepoint').length;
 if(contentslides>0){
 var fullslidelength=windowwidth*contentslides;
 var percenttotal=100*contentslides;
 $('div#slidepointhold[data-name=homeslider]').attr("data-slides",""+contentslides+"").css("width",""+fullslidelength+"px");
  var slidelength=$('div#slidepointhold[data-name=homeslider] div#slidepoint').width();
  // // console.log($('div#slidepointhold[data-name=homeslider]'),percenttotal);
  slideMotionResponsive('div#slidepointhold[data-name=homeslider]',"left",100,1000,20000,0);
}else{
 $('div#slidepointhold').css("display","none");
}
function slideMotionResponsive(targetElement,slideDir,moveval,period,timeout,statestart){
/*
Timeout is the time inbetween the slide motions while
stateStart is a value of 1 for motion firing off on pageload or
 0 for motion that waits the timeout period specified then changes to
 1 for continued motion.  
*/
var parentcontent=$(''+targetElement+'').attr("data-slides");
var slidelength=$(''+targetElement+' div#slidepoint').width();
var curposleft=$(''+targetElement+'').css("left");
if(curposleft.indexOf("px")>-1){    
var curposleft2=curposleft.split("p");
curposleft2=Math.floor(curposleft2[0]);
  }else if (curposleft.indexOf("%")>-1) {
var curposleft2=curposleft.split("%");
curposleft2=Math.floor(curposleft2[0]);
  }

var curposright=$(''+targetElement+'').css("right");
var curposright2=curposright.split("p");
curposright2=curposright2[0];

var percentlast=100*parentcontent-100;
  var totpercent=parentcontent*slidelength;
  var lastpoint=slidelength-totpercent;
var totalwidth=slidelength*100;
var totalheight=$(''+targetElement+'').height();
 
// // console.log(targetElement,slideDir,moveval,period,timeout,statestart);
// // console.log(curposleft,lastpoint,slidelength,parentcontent);
if(statestart=="stop"){
window.clearTimeout(slideFunctionResponsive);
// slideFunction=window.setTimeout("slideMotion('"+targetElement+"','"+slideDir+"','"+moveval+"','"+period+"','"+timeout+"','stop')",timeout);
}else if(statestart==1){
/*var curpostop=$(''+targetElement+'').css("top");
curpostop2=curpostop.split("p");
curpostop2=Math.floor(curpostop2[0]);
var curposbottom=$(''+targetElement+'').css("bottom");
var curposbottom2=curposbottom.split("p");
curposbottom2=Math.floor(curposbottom2[0]);*/
if(slideDir=="left"){
if(curposleft2>lastpoint){
$(''+targetElement+'').animate({left:"-="+moveval+"%"},period,function(){

});     
}else{
  $(''+targetElement+'').animate({left:"0%"},period,function(){});
}
}else if(slideDir=="right"){
if(curposright2<lastpoint){
$(''+targetElement+'').animate({right:"+="+moveval+"%"},period,function(){
slidePoint+=1;

});  
}else{
  $(''+targetElement+'').animate({right:"0%"},period,function(){});
}
}/*else if(slideDir=="bottom"){
  if(curposbottom=="auto"||curposbottom2<totalheight-moveval){
$(''+targetElement+'').animate({bottom:"+="+moveval+""},period,function(){

});  
}else{
  $(''+targetElement+'').animate({bottom:"0"},period,function(){});
}
}else if(slideDir=="top"){
if(curpostop=="auto"||curpostop2<totalheight-moveval){
$(''+targetElement+'').animate({top:"+="+moveval+""},period,function(){

});  
}else{
  $(''+targetElement+'').animate({top:"0"},period,function(){});
}
}*/
if(statestart=="stop"){
clearTimeout(slideFunctionResponsive);
}else if(timeout!==""&&timeout>0){
slideFunctionResponsive=window.setTimeout("slideMotionResponsive('"+targetElement+"','"+slideDir+"',"+moveval+","+period+","+timeout+","+statestart+")",timeout);
}
}else if(statestart==0){
slideFunctionResponsive=window.setTimeout("slideMotionResponsive('"+targetElement+"','"+slideDir+"',"+moveval+","+period+","+timeout+",1)",timeout);
}else{ 
// // console.log(curposleft,curposright,curposright2,curpostop,curposbottom,totalwidth,totalheight);
if(slideDir=="left"){
if(curposleft2>lastpoint){
$(''+targetElement+'').animate({left:"-="+moveval+"%"},period,function(){

});     
}else{
  $(''+targetElement+'').animate({left:"0%"},period,function(){});
}
}else if(slideDir=="right"){
if(curposright=="auto"||curposright2<lastpoint){
$(''+targetElement+'').animate({right:"+="+moveval+"%"},period,function(){

});  
}else{
  $(''+targetElement+'').animate({right:"0%"},period,function(){});
}
}/*else if(slideDir=="bottom"){
  if(curposbottom=="auto"||curposbottom2<totalheight-moveval){
$(''+targetElement+'').animate({bottom:"+="+moveval+""},period,function(){

});  
}else{
  $(''+targetElement+'').animate({bottom:"0"},period,function(){});
}
}else if(slideDir=="top"){
if(curpostop=="auto"||curpostop2<totalheight-moveval){
$(''+targetElement+'').animate({top:"+="+moveval+""},period,function(){

});  
}else{
  $(''+targetElement+'').animate({top:"0"},period,function(){});
}
}*/ 
}

}
var blockanim=false;
//for responsiveslide drag effect
$(document).on("mousedown","div#slidepointhold",function(e){
// console.log(e.clientX,slideFunctionResponsive);
clearTimeout(slideFunctionResponsive);
});

//end
// for responsive slides pointers works with slidepoint plugin
$(document).on("click","div#slidepointleft[data-state=idle],div#slidepointright[data-state=idle]",function(){
  event.stopPropagation();
  clearTimeout(slideFunctionResponsive);
  var direction=$(this).attr("data-motion");
  $(this).attr("data-state","running");
  var target=$(this).attr("data-target");
  var parentlength=$('div#slidepointhold[data-name='+target+']').width();
  var slidelength=$('div#slidepointhold[data-name='+target+'] div#slidepoint').width();
  var parentcontent=$('div#slidepointhold[data-name='+target+']').attr("data-slides");
  var parpos=$('').css('left');
  var curposleft=$('div#slidepointhold[data-name='+target+']').css("left");
  if(curposleft.indexOf("px")>-1){    
var curposleft2=curposleft.split("p");
curposleft2=Math.floor(curposleft2[0]);
  }else if (curposleft.indexOf("%")>-1) {
var curposleft2=curposleft.split("%");
curposleft2=Math.floor(curposleft2[0]);
  };
  var percentlast=100*parentcontent-100;
  var totpercent=parentcontent*slidelength;
  var lastpoint=slidelength-totpercent;
// // console.log(curposleft,lastpoint);
var moveval=100;
var slideDir="left";
var period=3000;
var timeout=20000;
var statestart=0;
  if(direction=="left"){
  if(curposleft2>lastpoint&&blockanim==false){
    blockanim=true;
    $('div#slidepointhold[data-name='+target+']').animate({left:'-=100%'},1000,function(){
/*    var firstel=$('div#slidepointhold[data-name='+target+'] div#slidepoint')[0];
    $('div#slidepointhold[data-name='+target+'] div#slidepoint')[0].remove;
    $(firstel).insertAfter('div#slidepointhold[data-name='+target+'] div#slidepoint:last');*/
slideFunctionResponsive=window.setTimeout("slideMotionResponsive('div#slidepointhold[data-name="+target+"]','"+slideDir+"',"+moveval+","+period+","+timeout+","+statestart+")",timeout);
      blockanim=false;
      $('div#slidepointright[data-motion=left]').attr("data-state","idle");
// // console.log($('div#slidepointhold[data-name='+target+'] div[data-motion=left]'));
    });
  }else if(curposleft2<=lastpoint&&blockanim==false){
blockanim=true;
$('div#slidepointhold[data-name='+target+']').animate({left:'0%'},1000,function(){
slideFunctionResponsive=window.setTimeout("slideMotionResponsive('div#slidepointhold[data-name="+target+"]','"+slideDir+"',"+moveval+","+period+","+timeout+","+statestart+")",timeout);
      blockanim=false;
// // console.log($('div#slidepointright[data-target='+target+']'));
$('div#slidepointright[data-motion=left]').attr("data-state","idle");
    });
  }
  }else if(direction=="right"){
  var slidelast=$('div#slidepointhold[data-name='+target+'] div#slidepoint').length-1;
  var lastel=$('div#slidepointhold[data-name='+target+'] div#slidepoint')[0];
  if(curposleft2<0){
    blockanim=true;
    $('div#slidepointhold[data-name='+target+']').animate({left:'+=100%'},1000,function(){
      blockanim=false;
$('div#slidepointleft[data-motion=right]').attr("data-state","idle");
    });
  }else if(curposleft2==0){
      blockanim=true;
$('div#slidepointhold[data-name='+target+']').animate({left:'-'+percentlast+'%'},1000,function(){
      blockanim=false;
$('div#slidepointleft[data-motion=right]').attr("data-state","idle");
});
}
  }
});
//end
// instantiate multiple jplayer elements
function js_audioPlayer(file,location) {
  $(document).ready(function(){

    $("#jquery_jplayer_" + location).jPlayer( {
        ready: function () {
          jQuery(this).jPlayer("setMedia", {
        mp3: file
          });
        },
        cssSelectorAncestor: "#jp_interface_" + location,
        swfPath: "/swf"
    });
  });
  return;
}

// for handling forms that have steps 
 function doFormStepMainLib(targetElement){
  // // console.log(targetElement,typeof targetElement);
  if(typeof targetElement=="object"){
    var pstep=$(targetElement).attr("data-cmonitor"),
    datapoint=$(targetElement).attr("data-pointer");
  }else if(typeof(targetElement)=="string"){ 
    var pstep=$(''+targetElement+'').attr("data-cmonitor"),
      datapoint=$(''+targetElement+'').attr("data-pointer");
  }
  var prevdatapoint=Math.floor(datapoint)-1;
  $('div[data-smonitortype][data-cmonitor='+pstep+'] div[data-stepc]').hide();
  $('div[data-smonitortype][data-cmonitor='+pstep+'] div[data-stepc='+datapoint+']').show();
  // update the step at the top of the display i.e Step 1, Step 2 part of it
  $('ul[data-smonitortype][data-cmonitor='+pstep+'] li').removeClass("active");
  // update the previous step to show completion
  if(prevdatapoint>0){
    $('ul[data-smonitortype][data-cmonitor='+pstep+'] li[data-stepc='+prevdatapoint+']').addClass("completed");
  }
  $('ul[data-smonitortype][data-cmonitor='+pstep+'] li[data-stepc='+datapoint+']').addClass("active").removeClass("completed");
 }
function loadFullDisplayAfterLoad(timeout,content) {
  setTimeout(
    function() {
      var topdistance=$(window).scrollTop();
      //insert the content into your target div

      if(topdistance>100){
        var mainheight=$('#main').height();
        $('#fullcontenthold').css("margin-top",""+topdistance+"px");
        }else{
          $('#fullcontenthold').css("margin-top","0px");
        }
        $('#fullbackground').fadeIn(1000);
        $('#fullcontenthold').fadeIn(1000);
        $('#fullcontent').html(content).fadeIn(1000);
        // console.log("ran load display");
        clearTimeout();
    }, timeout);
}
/*
//subscriber display information
<div class="subcribe-display">  <div class="minilogo"><img class="minilogo-logo" src="./images/muyiwalogo5.png"/></div>  <h2 class="subscribe-heading">Subscribe</h2>  <p class="subscribe-text">    Hope you are Enjoying your reading?    <br>If you want more simply drop your email address below and we will    add you to our list.<br>    You'll get instant access to our latest Frankly Speaking Content.<br>  </p>  <form name="franklyspeakingblogsubscriptiontwo" method="POST" onSubmit="" action="./snippets/basicsignup.php">    <input type="hidden" name="entryvariant" value="franklyspeakingblogsubscription"/>    <div id="formend"><input type="text" style="text-align:center;"name="email" placeholder="Enter email here..." value=""class="curved"/></div>    <div id="formend"><input type="button" class="submitbutton two" name="franklyspeakingblogsubscriptiontwo" value="Subscribe"/></div>  </form></div>
*/
//for approximation to particular decimal places
(function(){

  /**
   * Decimal adjustment of a number.
   *
   * @param {String}  type  The type of adjustment.
   * @param {Number}  value The number.
   * @param {Integer} exp   The exponent (the 10 logarithm of the adjustment base).
   * @returns {Number}      The adjusted value.
   */
  function decimalAdjust(type, value, exp) {
    // If the exp is undefined or zero...
    if (typeof exp === 'undefined' || +exp === 0) {
      return Math[type](value);
    }
    value = +value;
    exp = +exp;
    // If the value is not a number or the exp is not an integer...
    if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
      return NaN;
    }
    // Shift
    value = value.toString().split('e');
    value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
    // Shift back
    value = value.toString().split('e');
    return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
  }

  // Decimal round
  if (!Math.round10) {
    Math.round10 = function(value, exp) {
      return decimalAdjust('round', value, exp);
    };
  }
  // Decimal floor
  if (!Math.floor10) {
    Math.floor10 = function(value, exp) {
      return decimalAdjust('floor', value, exp);
    };
  }
  // Decimal ceil
  if (!Math.ceil10) {
    Math.ceil10 = function(value, exp) {
      return decimalAdjust('ceil', value, exp);
    };
  }
})();

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function bootPagInit(total,curquery,ipp,selector="",data=[]){
  selector==""?selector='.generic_ajax_pages_hold._top._test, .generic_ajax_pages_hold._bottom._test':selector=selector;
  var datatarget=data['datatarget']!==null&&data['datatarget']!==undefined&&data['datatarget']!==NaN&&data['datatarget']!==""?data['datatarget']:'div.generic_ajax_pages_hold div.page_content_out_hold._test';
  var dataitemloader=data['dataitemloader']!==null&&data['dataitemloader']!==undefined&&data['dataitemloader']!==NaN&&data['dataitemloader']!==""?data['dataitemloader']:'div.content_image_loader_bootpag._test';
  var outputtype=data['outputtype']!==null&&data['outputtype']!==undefined&&data['outputtype']!==NaN&&data['outputtype']!==""?data['outputtype']:'';
    
  $(selector).bootpag({
      total: total,
      page: 1,
      maxVisible: 9,
      leaps: true,
      firstLastUse: true,
      first: '<i class="fa fa-arrow-left"></i>',
      last: '<i class="fa fa-arrow-right"></i>',
      wrapClass: 'pagination',
      dataquery:true,
      datacurquery:curquery,
      dataipp:ipp,
      dataoutputtype:outputtype,
      dataselectorset:selector,
      datavariant:true,
      datapages:[15,25,40,60],
      datatarget:datatarget,
      dataitemloader:dataitemloader,
      activeClass: 'active',
      disabledClass: 'disabled',
      nextClass: 'next',
      prevClass: 'prev',
      lastClass: 'last',
      firstClass: 'first'
  }).on("page", function(event, num){
      event.preventDefault();
      var curtimestamp=parseInt(event.timeStamp);
      var doajax="";
      var timetest=0;
      // stop bootpag from running twice for dual pagination elements
      // on the same target
      if(timestamp==0){
          timestamp=curtimestamp;
      }else{
          timetest=parseInt(curtimestamp)-parseInt(timestamp);
          if(timetest<=10){
              doajax="false";
          }
      }
      if(doajax==""){
          timestamp=curtimestamp;
          var dataparent=$(this)[0].childNodes[1];
          if(dataparent.getAttribute("class").indexOf("pagination bootpag")>-1){
              dataparent=$(this)[0].childNodes[2];
          }
          var endtarget=$(this)[0].parentNode.getElementsByClassName('page_content_out_hold')[0];
          var page=parseInt(num);
          var dipp=15;
          var curquery="";
          var outputtype="";
          // console.log($(this)[0].parentNode.getElementsByClassName('content_image_loader_bootpag'),endtarget);
          for(var i=0;i<dataparent.childNodes.length;i++){
              // console.log(dataparent.childNodes[i],dataparent.childNodes[i].name);
              if(dataparent.childNodes[i].name=="curquery"){
                  curquery=dataparent.childNodes[i].value;
              }
              if(dataparent.childNodes[i].name=="outputtype"){
                  outputtype=dataparent.childNodes[i].value;
              }
              if(dataparent.childNodes[i].name=="ipp"){
                  dipp=dataparent.childNodes[i].value;
              }

          }
          // for testing purposes only
          // outputtype="";

          // var item_loader=$(this)[0].parentNode.getElementById('content_image_loader_bootpag');
          var item_loader=$(this)[0].parentNode.getElementsByClassName('content_image_loader_bootpag')[0];
          item_loader.className=item_loader.className.replace( /(?:^|\s)hidden(?!\S)/g , '' );
          // console.log(item_loader,item_loader.className);
          // item_loader.removeClass('hidden');
          var url=''+host_addr+'snippets/display.php';
          var opts = {
                  type: 'GET',
                  url: url,
                  data: {
                    displaytype:'paginationpagesout',
                    ipp:dipp,
                    curquery:curquery,
                    outputtype:outputtype,
                    page:num,
                    loadtype:'jsonloadalt',
                    extraval:"admin"
                  },
                  dataType: 'json',
                  success: function(output) {
                    // console.log(endtarget);
                    // console.log(output);
                    item_loader.className +=' hidden';
                    // item_loader.addClass('hidden').css("display","");
                    // item_loader.remove();
                    if(output.success=="true"){
                          endtarget.innerHTML=output.msg;
                    }
                  },
                  error: function(error) {
                      if(typeof(error)=="object"){
                          console.log(error.responseText);
                      }
                      var errmsg="Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again";
                      // item_loader.remove();
                      item_loader.className +=' hidden';
                      raiseMainModal('Failure!!', ''+errmsg+'', 'fail');
                      // alert("Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again ");
                  }
          };
          $.ajax(opts)
          // console.log(event,$(this)[0].childNodes,dataparent);
          // get the datadiv refereence
          // $(".content4").html("Page " + num); // or some ajax content loading...
          // $(this).addClass("current");
          
      }

  });
}
timestamp=0;
function doBootPagReInit(total,curquery,ipp,selector="",data=[]){
  if(curquery.indexOf("|-|-|")>-1){
    // perform deobfuscation on the query entry
  }
  selector==""?selector='.generic_ajax_pages_hold._top._test, .generic_ajax_pages_hold._bottom._test':selector=selector;
  var datatarget=data['datatarget']!==null&&data['datatarget']!==undefined&&data['datatarget']!==NaN&&data['datatarget']!==""?data['datatarget']:'div.generic_ajax_pages_hold div.page_content_out_hold._test';
  var dataitemloader=data['dataitemloader']!==null&&data['dataitemloader']!==undefined&&data['dataitemloader']!==NaN&&data['dataitemloader']!==""?data['dataitemloader']:'div.content_image_loader_bootpag._test';
  var outputtype=data['outputtype']!==null&&data['outputtype']!==undefined&&data['outputtype']!==NaN&&data['outputtype']!==""?data['outputtype']:'';

  $(selector).bootpag({
      total: total,
      page: 1,
      maxVisible: 9,
      leaps: true,
      firstLastUse: true,
      first: '<i class="fa fa-arrow-left"></i>',
      last: '<i class="fa fa-arrow-right"></i>',
      wrapClass: 'pagination',
      dataquery:true,
      datacurquery:curquery,
      dataipp:ipp,
      dataoutputtype:outputtype,
      dataselectorset:selector,
      datavariant:true,
      datapages:[15,25,40,60],
      datatarget:datatarget,
      dataitemloader:dataitemloader,
      activeClass: 'active',
      disabledClass: 'disabled',
      nextClass: 'next',
      prevClass: 'prev',
      lastClass: 'last',
      firstClass: 'first'
  })
}

// bootpag demo setup test and 
$(document).ready(function(){
  if ($.fn.bootpag) {
    var timestamp=0;
    var pagsetdata="";
    $('.generic_ajax_pages_hold._top._test, .generic_ajax_pages_hold._bottom_test').bootpag({
        total: 15,
        page: 1,
        maxVisible: 9,
        leaps: true,
        firstLastUse: true,
        first: '<i class="fa fa-arrow-left"></i>',
        last: '<i class="fa fa-arrow-right"></i>',
        wrapClass: 'pagination',
        dataquery:true,
        datacurquery:"SELECT * FROM capitalexpenditure ORDER BY code desc",
        dataipp:15,
        datavariant:true,
        datapages:[15,25,40,60],
        datatarget:'.page_content_out_hold_test',
        dataitemloader:'.content_image_loader_bootpag_test',
        activeClass: 'active',
        disabledClass: 'disabled',
        nextClass: 'next',
        prevClass: 'prev',
        lastClass: 'last',
        firstClass: 'first'
    }).on("page", function(event, num, a){
        event.preventDefault();
        // console.log($(this).parent(),this,a,a.datatarget);
        var curtimestamp=parseInt(event.timeStamp);
        var doajax="";
        var timetest=0;
        // stop bootpag from running twice for dual pagination elements
        // on the same target
        if(timestamp==0){
            timestamp=curtimestamp;
        }else{
            timetest=parseInt(curtimestamp)-parseInt(timestamp);
            if(timetest<=10){
                doajax="false";
            }
        }
        if(doajax==""){
            timestamp=curtimestamp;
            var dataparent=$(this)[0].childNodes[1];
            if(dataparent.getAttribute("class").indexOf("pagination bootpag")>-1){
                dataparent=$(this)[0].childNodes[2];
            }

            // var endtarget=$(this).parent().find(a.datatarget);
            var endtarget=$(this).parent().find(a.datatarget);
            var page=parseInt(num);
            var dipp=a.dataipp;
            var curquery=a.dataquery;
            var outputtype="";
            // console.log($(this)[0].parentNode.getElementsByClassName('content_image_loader_bootpag'),endtarget);
            for(var i=0;i<dataparent.childNodes.length;i++){
                // console.log(dataparent.childNodes[i],dataparent.childNodes[i].name);
                if(dataparent.childNodes[i].name=="curquery"){
                    curquery=dataparent.childNodes[i].value;
                }
                if(dataparent.childNodes[i].name=="outputtype"){
                    outputtype=dataparent.childNodes[i].value;
                }
                if(dataparent.childNodes[i].name=="ipp"){
                    dipp=dataparent.childNodes[i].value;
                }

            }
            // for testing purposes only
            outputtype="";

            // var item_loader=$(this)[0].parentNode.getElementById('content_image_loader_bootpag');
            var item_loader=$(this).parent().find(a.dataitemloader);
            // var item_loader=$(this)[0].parent.find(a.dataitemloader);
            item_loader.removeClass('hidden');
            // item_loader.className=item_loader.className.replace( /(?:^|\s)hidden(?!\S)/g , '' );
            // console.log(item_loader,item_loader.className);
            // item_loader.removeClass('hidden');
            var url=''+host_addr+'snippets/display.php';
            var opts = {
                    type: 'GET',
                    url: url,
                    data: {
                      displaytype:'paginationpagesout',
                      ipp:dipp,
                      curquery:curquery,
                      outputtype:outputtype,
                      page:num,
                      loadtype:'jsonloadalt',
                      extraval:"admin"
                    },
                    dataType: 'json',
                    success: function(output) {
                      // console.log(endtarget);
                      // console.log(output);
                      item_loader.className +=' hidden';
                      // item_loader.addClass('hidden').css("display","");
                      // item_loader.remove();
                      if(output.success=="true"){
                            endtarget.innerHTML=output.msg;
                      }
                    },
                    error: function(error) {
                        if(typeof(error)=="object"){
                            console.log(error.responseText);
                        }
                        var errmsg="Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again";
                        // item_loader.remove();
                        // item_loader.className +=' hidden';
                        item_loader.addClass('hidden');
                        raiseMainModal('Failure!!', ''+errmsg+'', 'fail');
                        // alert("Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again ");
                    }
            };
            $.ajax(opts)
            // console.log(event,$(this)[0].childNodes,dataparent);
            // get the datadiv refereence
            // $(".content4").html("Page " + num); // or some ajax content loading...
            // $(this).addClass("current");
            
        }

    }); 
    var timestampselect=0;
    $(document).on("click","select[name=general_ipp_select]",function(){
        var ipp=$(this).val();
        var ipp2=$(this).attr("data-curipp");
        var selectorset=$(this).attr("data-selector");
        if(selectorset===null||selectorset===undefined||selectorset===NaN){
            var selectorset="";
        }
        if(selectorset==""){
            selectorset='.generic_ajax_pages_hold._top._test, .generic_ajax_pages_hold._bottom._test';
        }

        var datatarget=$(this).attr("data-target");
        if(datatarget===null||datatarget===undefined||datatarget===NaN){
            var datatarget="";
        }
        if(datatarget==""){
            datatarget='.generic_ajax_pages_hold._top._test, .generic_ajax_pages_hold._bottom._test';
        }
        console.log("Selector: ",selectorset);
        if(ipp!==ipp2&&ipp!==""){
                var dataparent=$(this).parent().parent().parent()[0].childNodes[1];
                var bootpagul=$(this).parent().parent().parent()[0].childNodes[0];
                if(dataparent.getAttribute("class").indexOf("pagination bootpag")>-1){
                    bootpagul=dataparent;
                    dataparent=$(this).parent().parent().parent()[0].childNodes[2];
                }

                var endtarget=$(this).parent().parent().parent().parent()[0].getElementsByClassName('page_content_out_hold')[0];
                var page=1;
                var dipp=parseInt(ipp);
                var curquery="";
                var outputtype="";
                // console.log(dataparent,$(this).parent().parent().parent().parent()[0].getElementsByClassName('content_image_loader_bootpag'),endtarget,bootpagul);
                for(var i=0;i<dataparent.childNodes.length;i++){
                    // console.log(dataparent.childNodes[i],dataparent.childNodes[i].name);
                    if(dataparent.childNodes[i].name=="curquery"){
                        curquery=dataparent.childNodes[i].value;
                    }
                    if(dataparent.childNodes[i].name=="outputtype"){
                        outputtype=dataparent.childNodes[i].value;
                    }
                    if(dataparent.childNodes[i].name=="datatarget"){
                        datatarget=dataparent.childNodes[i].value;
                    }
                    
                }
                // for testing purposes only
                // outputtype="";
                // var item_loader=$(this)[0].parentNode.getElementById('content_image_loader_bootpag');
                var item_loader=$(this).parent().parent().parent().parent()[0].getElementsByClassName('content_image_loader_bootpag')[0];
                item_loader.className=item_loader.className.replace( /(?:^|\s)hidden(?!\S)/g , '' );
                // console.log(item_loader,item_loader.className);
                // item_loader.removeClass('hidden');
                var url=''+host_addr+'snippets/display.php';
                var cname="."+item_loader.className;
                cname=cname.replace(/\s{2,}/g, '').replace(/\s/g,".");
                // console.log("curitemloader - ",cname," datatarget - ",datatarget," outputtype - ", outputtype);
                // reinit data
                var data=[];
                data['datatarget']=datatarget;
                data['dataitemloader']=cname;
                data['outputtype']=outputtype;
                // pulls the new result count for the ipp(Instances per page) value
                var opts = {
                        type: 'GET',
                        url: url,
                        data: {
                          displaytype:'paginationpages',
                          ipp:dipp,
                          curquery:curquery,
                          outputtype:outputtype,
                          page:page,
                          loadtype:'bootpag',
                          extraval:"admin"
                        },
                        dataType: 'json',
                        success: function(output) {
                          // console.log(endtarget);
                          // console.log(output);
                          // item_loader.className +=' hidden';
                          // item_loader.addClass('hidden').css("display","");
                          // item_loader.remove();
                          if(output.success=="true"){
                                // endtarget.innerHTML=output.msg;
                                // reinitialise the pagination fields to the new count
                                // empty previous bootpag containers and populate them
                                $(''+selectorset+'').html("");
                                doBootPagReInit(output.resultcount,curquery,ipp,selectorset,data);
                                

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
                // pulls the new content for the page display
                var opts2 = {
                        type: 'GET',
                        url: url,
                        data: {
                          displaytype:'paginationpagesout',
                          ipp:dipp,
                          curquery:curquery,
                          outputtype:outputtype,
                          page:page,
                          loadtype:'jsonloadalt',
                          extraval:"admin"
                        },
                        dataType: 'json',
                        success: function(output) {
                          console.log(endtarget);
                          console.log(output);
                          item_loader.className +=' hidden';
                          // item_loader.addClass('hidden').css("display","");
                          // item_loader.remove();
                          if(output.success=="true"){
                                endtarget.innerHTML=output.msg;
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
                $.ajax(opts2)
               
        }
    })

  }
  
})

function count(obj) {
   var count=0;
   for(var prop in obj) {
      if (obj.hasOwnProperty(prop)) {
         ++count;
      }
   }
   return count;
}

function countSingle(obj) { return Object.keys(obj).length; }
function raiseMainModal(title, content, failsuccess,show=""){
      //for content bg and color controls
      var outclass="";
      var inclass="";
      // for button bg and color controls
      var btnoutclass="";
      var btninclass="";
      if(failsuccess=="fail"){
        outclass='bg-yellow-active bg-green-active color-darkgreen bg-aqua-active';
        inclass='bg-red-active color-white';
      }else if(failsuccess=="success"){
        outclass='bg-yellow-active bg-red-active color-red bg-aqua-active';
        inclass='bg-green-active color-darkgreen';
      }else if(failsuccess=="info"){
        outclass='bg-yellow-active bg-red-active color-red bg-green-active color-darkgreen';
        inclass='bg-aqua-active';
      }else if(failsuccess=="warning"){
        outclass='bg-red-active color-red bg-green-active color-darkgreen';
        inclass='bg-yellow-active';
      }
      // check to see if the modal element exists, otherwise create it
      var mainmodal="";
      var mainmodalcontent="";
      var mainmodaltitle="";
      var mainmodalbody="";
      var mainmodalfooter="";
      var mainmodalbuttonone="";
      var mainmodalbuttontwo="";
      // console.log("modal length: ",$('body').find("#mainPageModal").length);
      if($('body').find("#mainPageModal").length==0){
        var fullmodb=$('<!-- General Modal display section -->      <div id="mainPageModal" class="modal fade" data-backdrop="false" data-show="true" data-role="dialog">        <div class="modal-dialog">            <div class="modal-content">                <div class="modal-header">                    <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>                    <h4 class="modal-title">Message</h4>                </div>                <div class="modal-body">                </div>                <div class="modal-footer">                    <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>                </div>            </div>        </div>      </div>');
        $('body').append(fullmodb);
      }

      mainmodal=$("#mainPageModal");
      mainmodaltitle=$("#mainPageModal .modal-header .modal-title");
      mainmodalbody=$("#mainPageModal .modal-body");
      mainmodalcontent=$("#mainPageModal .modal-content");
      mainmodalfooter=$("#mainPageModal .modal-content .modal-footer");
      mainmodalbuttonone=$("#mainPageModal .modal-content .modal-footer .btn");
      
      // raise the success modal
      mainmodaltitle.html(title);
      mainmodalbody.html(content);
      mainmodalcontent.removeClass(outclass).addClass(inclass);
      
      if(show=="true"||show==""){
        mainmodal.modal({show: true});
      }else if(show=="noclose"){
        
      }
        
}

function randomIntFromInterval(min,max)
{
    return Math.floor(Math.random()*(max-min+1)+min);
}
// function works with isotope gallery on the portfolio page
// triggers hover effect randomly on default for 4 elements in the 
// isotope gallery
testc=0;
function doIsotopeGalleryHover(parentitemselector,isotopitemselector,timeout=500,triggerlim=4,delay=5000,liftclass='lift'){
    // get the total num of kid objects in parentitem
    var curlength=$(''+parentitemselector+' '+isotopitemselector+'').length;
    testc+=1;
    var count=1;
    var trandout="";
    for(var i=1;i<=triggerlim;i++){
      if(testc<2){
        var trand=randomIntFromInterval(1,7);

      }else{
        var trand=randomIntFromInterval(1,curlength);
        
      }
        var trandelay=parseInt(delay)>1999?randomIntFromInterval(2000,delay):delay;
        if(!$(''+parentitemselector+' '+isotopitemselector+':nth-of-type('+trand+')').hasClass(liftclass)){
          if(count<=triggerlim){
            trandout+=trandout==""?trand:","+trand;
            $(''+parentitemselector+' '+isotopitemselector+':nth-of-type('+trand+')').addClass(liftclass).delay(trandelay).queue(function(nxt) {
                $(this).removeClass(liftclass);
                nxt();
            });
            count++;
          }else{
            count=1;
          }
        }
    }
    count=1;
    window.setTimeout(function(){doIsotopeGalleryHover(".cp-gallery","figure.item",timeout,triggerlim,delay,liftclass);},timeout);

    // console.log(trandout," ",testc);

    // console.log(Math.floor(testc++),curlength,count);
}
// infiniteload script can translate to infinte scroll
$(document).ready(function(){
    $(document).on("click","a[data-ptrigger=true]",function(){
        // this block handles the pagination trigger system or "ptrigger" system
        // it simply takes the following parameters done below into consideration

        // type: state the associated data-ptype attribute for the current pagination trigger 
        // used in obtaining the top('pnph_top') and bottom(pnph_bottom) elements
        // flowtype: determines which point the result set is being fetched from, values are 
        // 'lastentryset' or 'nextentryset' specifying what they imply..., 'old content' and
        // 'new content' or feed
        // pagelastid: the last id for whatever result set has been displayed either top result set or bottom
        // pagelim: the limit of results to pull for the current request 
        // containerclass & containertag the css class and html tag used for the container element
        // objectclass & objecttag the css class and html tag used for the object element
        // the object element is the unit element of the paginated display

        var extraval="";
        var celemdata=$(this);
        var type=$(this).attr("data-ptype")!==""&&typeof($(this).attr("data-ptype"))!=="undefined"?$(this).attr("data-ptype"):"";
        var flowtype=$(this).attr("data-pflow")!==""&&typeof($(this).attr("data-pflow"))!=="undefined"?$(this).attr("data-pflow"):"";
        var pagelastid=$(this).attr("data-plid")!==""&&typeof($(this).attr("data-plid"))!=="undefined"?$(this).attr("data-plid"):0;
        var pagelim=$(this).attr("data-picou")!==""&&typeof($(this).attr("data-picou"))!=="undefined"?"LIMIT "+$(this).attr("data-picou").replace(/\:/g,","):"LIMIT 0,15";
        var containerclass=$(this).attr("data-ptobjp")!==""&&typeof($(this).attr("data-ptobjp"))!=="undefined"?$(this).attr("data-ptobjp"):"";
        var containertag=$(this).attr("data-ptobjptype")!==""&&typeof($(this).attr("data-ptobjptype"))!=="undefined"?$(this).attr("data-ptobjptype"):"";
        var objectclass=$(this).attr("data-ptobj")!==""&&typeof($(this).attr("data-ptobj"))!=="undefined"?$(this).attr("data-ptobj"):"";
        var objecttag=$(this).attr("data-ptobjtype")!==""&&typeof($(this).attr("data-ptobjtype"))!=="undefined"?$(this).attr("data-ptobjtype"):"";
        var objectstate=$(this).attr("data-state")!==""&&typeof($(this).attr("data-state"))!=="undefined"?$(this).attr("data-state"):"inactive";
        var entrytype=$(this).attr("data-ptobje")!==""&&typeof($(this).attr("data-ptobje"))!=="undefined"?$(this).attr("data-ptobje"):"";
        extraval=$(this).attr("data-peval")!==""&&typeof($(this).attr("data-peval"))!=="undefined"?$(this).attr("data-peval"):"";

        // get itemloader
        var item_loader=$('[class*=pnph_][data-ptype='+type+'][data-pflow='+flowtype+'] .pnph_loaderimg');
        // console.log(item_loader);
        // get endtargetcontainer and other container related elements
        // and set the insertion parameters
        var endcontainer=$(''+containertag+''+containerclass+'');
        var endtarget="";
        var n_of_type="";
        if(entrytype.toLowerCase().indexOf("insertafter")>-1){
            n_of_type=":last-of-type";
        }else if(entrytype.toLowerCase().indexOf("insertbefore")>-1){
            n_of_type=":nth-of-type(1)";

        }
        endtarget=$(''+containertag+''+containerclass+' '+objecttag+''+objectclass+''+n_of_type+'');

        // error checks and trigger validity
        var errmsg="";
        var validitycheck="";
        if(flowtype==""){
            errmsg='Sorry the flowtype for this trigger is unspecified.';
            raiseMainModal('Dev error!!', ''+errmsg+'', 'fail');
            validitycheck="fail";
        }else if(pagelastid==""){
            errmsg='Corresponding "id" defined.';
            raiseMainModal('Dev error!!', ''+errmsg+'', 'fail');
            validitycheck="fail";
        }else if(entrytype==""){
            errmsg='Entry type is not defined.';
            raiseMainModal('Dev error!!', ''+errmsg+'', 'fail');
            validitycheck="fail";
        }else if(containerclass==""||containertag==""){
            errmsg='No container class or tag specified.';
            raiseMainModal('Dev error!!', ''+errmsg+'', 'fail');
            validitycheck="fail";
        }else if(objectclass==""||objecttag==""){
            errmsg='No object class or tag specified.';
            raiseMainModal('Dev error!!', ''+errmsg+'', 'fail');
            validitycheck="fail";
        }
        // code block for handling paginationtype specific extravalues
        if(type=="gallerytest"||type=="gallery"){

        }
        celemdata.attr("data-state","active");

        //proceed to main code work 
        if(validitycheck==""&&objectstate=="inactive"){
            item_loader.removeClass('hidden');
            var url=''+host_addr+'snippets/display.php';
            var opts = {
                    type: 'POST',
                    url: url,
                    data: {
                        displaytype:'refreshpagination',
                        paginationtype:type,
                        flowtype:flowtype,
                        limit:pagelim,
                        id:pagelastid,
                        entrytype:entrytype,
                        extraval:extraval,
                    },
                    dataType: 'json',
                    success: function(output) {
                      // console.log(endtarget);
                      // console.log(output);
                      // item_loader.className +=' hidden';
                      item_loader.addClass('hidden').css("display","");
                      // item_loader.remove();
                      if(output.success=="true"){
                            if(output.catdata!==""){
                                var currows=parseInt(output.resultcount);
                                var curmax=parseInt(output.currentmax);
                                var nextmax=parseInt(output.nextmax);
                                var newmax="";
                                if(nextmax!==""){
                                  newmax=curmax+":"+nextmax;
                                  celemdata.attr("data-picou",newmax);
                                  
                                }
                                // console.log("Ran Currows - ",currows," curmax - ",curmax," nextmax - ",nextmax);
                                
                                // endtarget.=output.catdat;
                                  celemdata.attr("data-state","inactive");
                                if(entrytype.toLowerCase().indexOf("insertafter")>-1){
                                    $(output.catdata).insertAfter(endtarget);
                                    
                                }else if(entrytype.toLowerCase().indexOf("insertbefore")>-1){
                                    $(output.catdata).insertBefore(endtarget);

                                }
                            }else{
                                var errmsg="Sorry, there are no more results";

                                raiseMainModal('No Results!!', ''+errmsg+'', 'info');
                                celemdata.attr("data-state","inactive");

                            }
                      }
                    },
                    error: function(error) {
                        console.log("Error Type:", typeof(error));
                        if(typeof(error)=="object"||typeof(error)==="object"){
                            console.log("Error Response: ",error.responseText);
                        }
                        var errmsg="Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again";
                        // item_loader.remove();
                        item_loader.addClass('hidden').css("display","");
                        // item_loader.className +=' hidden';
                        raiseMainModal('Failure!!', ''+errmsg+'', 'fail');
                        // alert("Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again ");
                    }
            };
            $.ajax(opts)
        }
    })
    // handle batch content delete trigger
    $(document).on("click","a[data-name=deleteset]",function(){
        var delstate=$(this).attr("data-del-state");
        if(delstate===null||delstate===undefined||delstate===NaN){
          var delstate="";
        }
        if(delstate==""){
          $(this).html('<i class="fa fa-ban"></i> Stop Delete');
        }else{
          $(this).html('<i class="fa fa-trash"></i> Delete Batch');

        }

        var datatype=$(this).attr("data-type");
        if(datatype===null||datatype===undefined||datatype===NaN){
          var datatype="";
        }
        var typename=$(this).attr("data-type-name");
        if(typename===null||typename===undefined||typename===NaN){
          var typename="";
        }
        var value=$(this).attr("data-type-value");
        if(value===null||value===undefined||value===NaN){
          var value="";
        }
        var state=$(this).attr("data-state");
        if(state===null||state===undefined||state===NaN){
          var state="";
        }

        if(datatype!==""&&typename!==""&&value!==""){

          if(state!==""){
            if(delstate==""){
              $(''+datatype+'[name*='+typename+']:'+state+'');
              $(this).attr("data-del-state","active");
            }else{
              $(''+datatype+'[name*='+typename+']:un'+state+'');
              $(this).attr("data-del-state","");
            }

          }else{
            if(delstate==""){
              $(''+datatype+'[name*='+typename+']').val(value);
              $(this).attr("data-del-state","active");
            }else{
              $(''+datatype+'[name*='+typename+']').val("");
              $(this).attr("data-del-state","");
            }
            
          }
          if(delstate==""){
            var tarelemerr='Current set of entries have been set to be deleted on update, submit the form to perform delete operations';
            raiseMainModal('Delete System Active!!', tarelemerr, 'success');
          }else{
            var tarelemerr='Current set of entries have been removed from the delete set';
            raiseMainModal('Delete System Deactivated!!', tarelemerr, 'info');
          }

        }else{
            var tarelemerr='Delete System Error, missing trigger attribute values on batch delete object';

            raiseMainModal('Delete System Failure!!', tarelemerr, 'fail');
            console.log("Datatype - ",datatype," typename - ",typename," value - ",value," state - ",state);
        }

    })
})

function goToByScroll(id,speedstyle="slow"){
    // Remove "link" from the ID
    // id = id.replace("link", "");
    // remove the # sign of its present
    id = id.replace("#", "");
      // Scroll
    $('html,body').animate({
        scrollTop: $("#"+id).offset().top},
        ''+speedstyle+'');
}
function doIsotopeFiveGrid(container=".cp-gallery",item=".item"){
  $(document).ready(function(){
    var $container=$(''+container+'');
    var colWidth=function(){
      // console.log($container);
      var w=$container.width(),
      columnNum=1,
      columnWidth=0;
      if(w>0){
        columnNum=5;
      }else if(w>900){
        columnNum=4;
      }else if(w>600){
        columnNum=3;
      }else if(w>300){
        columnNum=2;
      }
      columnWidth=Math.floor(w/columnNum);
      $container.find(''+item+'').each(function(){
        var $item=$(this),
        multiplier_w=$item.attr('class').match(/item-w(\d)/),
        multiplier_h=$item.attr('class').match(/item-h(\d)/),
        width=multiplier_w?columnWidth*multiplier_w[1]:columnWidth,
        height=multiplier_h?columnWidth*multiplier_h[1]*0.5:columnWidth*0.5;
        $item.css({width:width,height:height});
      });
      return columnWidth;
    }
    isotope=function(){
          // console.log('ran');
          $container.isotope({
            resizable:false,
            itemSelector:'.item',
            masonry:{columnWidth:colWidth(),gutterWidth:0}
          });
    };
    isotope();
    $(window).smartresize(isotope);
  })

}
function doFAPicker(thea){
  // console.log("it was clicked");
  curval=thea.attr("value");
  icontitle=thea.attr("title");

  var target_input=thea.parent().parent().parent().parent().find('input[name=icontitle]');
  var target_display=thea.parent().parent().parent().parent().find('.currentfa i');
  var prevval=target_input.val();
  // console.log(target_input,target_display);
  if(prevval!==curval){
    target_input.val(curval);
    target_display.attr("class","fa "+curval);
    
  }else{
    target_input.val("");
    target_display.attr("class","");
  }

}
function callTinyMCEInit(selector,data=[]){
  theme=data['theme']!==""&&typeof data['theme']!=="undefined"?data['theme']:"modern";
  toolbar1=data['toolbar1']!==""&&typeof data['toolbar1']!=="undefined"?data['toolbar1']:"";
  toolbar2=data['toolbar2']!==""&&typeof data['toolbar2']!=="undefined"?data['toolbar2']:"";
  width=data['width']!==""&&typeof data['width']!=="undefined"?data['width']:"100%";
  height=data['height']!==""&&typeof data['height']!=="undefined"?data['height']:"250px";
  filemanagertitle=data['filemanagertitle']!==""&&typeof data['filemanagertitle']!=="undefined"?data['filemanagertitle']:"Content Filemanager";

  toolbar1==""?toolbar1="undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect":toolbar1=toolbar1;
  toolbar2==""?toolbar2="| link unlink anchor | emoticons":toolbar2=toolbar2;
  // console.log("selector - ",selector," theme - ",theme," toolbar1 - ",toolbar1," toolbar2 - ",toolbar2," width - ",width," height - ",height," filemanagertitle - ",filemanagertitle);
  tinyMCE.init({
        theme : theme,
        selector:selector,
        menubar:false,
        statusbar: false,
        plugins : [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
        ],
        width:width,
        height:height,
        toolbar1: toolbar1,
        toolbar2: toolbar2,
        image_advtab: true ,
        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
        content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
        external_filemanager_path:""+host_addr+"scripts/filemanager/",
        filemanager_title:filemanagertitle ,
        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
  });
}

// for contentpreviewoptions options click 
$(document).on("click", ".contentpreviewoptions > a.option", function() {
    // get the parent
    var mainparent=$(this).parent().parent().parent();
    var datastate=$(this).attr("data-state");
    if (datastate === null || datastate === undefined || datastate === NaN) {
        datastate = "";
    }
    // console.log('mainparent:',mainparent);

    if(datastate==""||datastate=="inactive"){
        // search the parent for elements that have the .coptionpoint._hold class
        mainparent.find(".coptionpoint._hold").removeClass('_hold')
        $(this).attr("data-state","active");
        $(this).find('i').attr("class","fa fa-eye-slash");
        // console.log('options hold:',mainparent.find(".coptionpoint._hold"));

    }else if(datastate=="active"){
        mainparent.find(".coptionpoint").addClass('_hold');
        $(this).attr("data-state","inactive");
        $(this).find('i').attr("class","fa fa-gear fa-spin");
    }

})
// make password fields viewable 
$(document).on("click",".pshow",function(){
  // console.log(this);
  var target=$(this).parent().find('input[data-type=password]');

  if($(this).hasClass('in')){
    target.attr("type","password");
    $(this).removeClass('in');
    $(this).find('i.fa').removeClass("fa-eye").addClass("fa-eye-slash");
  }else{
    target.attr("type","text");
    $(this).addClass('in');
    $(this).find('i.fa').removeClass("fa-eye-slash").addClass("fa-eye");
  }
})

function submitCustom(formname, stype="") {
    formit = [];
    var formstatus = true;
    var pointmonitor = false;
    if (typeof (tinyMCE) !== "undefined") {
        tinyMCE.triggerSave();
    }
    // var formname=typeof($(this).attr('data-formdata'))!=="undefined"?$(this).attr('data-formdata'):"contentform";
    // var formname=typeof($('input[name=formdata]'))!=="undefined"?$('input[name=formdata]').val():"contentform";
    // obtain
    var formmain=$('form[name=' + formname + ']');
    var errormap = $('form[name=' + formname + '] input[name=errormap]');
    var multierror = $('form[name=' + formname + '] input[name=multierrormap]');
    var extraformdata = $('form[name=' + formname + '] input[name=extraformdata]');
    var inputname1 = $('form[name=' + formname + '] input[name=contenttitle]');
    var inputname2 = $('form[name=' + formname + '] input[name=contentpic]');
    var inputname3 = $('form[name=' + formname + '] textarea[name=contentintro]');
    var inputname4 = $('form[name=' + formname + '] textarea[name=contentpost]');
    var inputname5 = $('form[name=' + formname + '] input[name=monitorcustom]');
    // get the setup for the globalmodal window
    var boolmodal=false;
    if($('#mainPageModal').length>0){
        boolmodal=$('#mainPageModal').hasClass('in');
        // console.log(boolmodal);
    }

    // console.log("Running validator."," Running eformdata",extraformdata.val()," Running errormap",errormap.val());  
    if (typeof (extraformdata.val()) !== "undefined" && extraformdata.val() !== "" && typeof (errormap.val()) !== "undefined" && errormap.val() !== "") {
        // console.log("Running First steps..."," Form:- ",formname);  
        var efdstepone = extraformdata.val().split("<|>");
        var emstepone = errormap.val().split("<|>");
        // console.log("Running First split...");  
        // console.log("Running step one Form data:- ",efdstepone);  
        // console.log("Running step one Errormap..."," Form:- ",emstepone);  
        // output console.logs for final validation data, search for "Current Value", and 
        // uncomment the log content to get a preview ofthe final output
        if (efdstepone.length == emstepone.length) {
            // group counter variable keeping track of disabled content
            var groupcounter = 0;
            var singlecounter = 0;
            var edsone=efdstepone.length;
            var totalvardefns="";
            var totalerrcontentout="";
            var totalpreinits="";
            var totalvalsets="";
            var elsec="";
            for (var i = 0; i < edsone; i++) {
                if(i>1){
                    elsec="else";
                }
                // begin division of current values
                // get current extraformdata
                var curgroup = efdstepone[i];
                // get current errormap
                var errgroup = emstepone[i];
                var errcontentout = "";
                var finalgroup = [];
                var finalreqgroup = [];
                if (curgroup.indexOf("egroup|data") > -1) {
                    groupcounter++;
                    // multiple data focus section, here validation is done
                    // on a grouped set of elements
                    var subgroup = curgroup.split("egroup|data-:-");
                    var suberrgroup = errgroup.split("egroup|data-:-");
                    // console.log("Suberror group before: ",suberrgroup);
                    var suberrgroupdata = /[^\[\]]+/i.exec(suberrgroup[1]);
                    // suberrgroupdata=suberrgroupdata[1];
                    // divide the subgroups further into fielddata and requirement data
                    // also extradata block
                    // for error checking content
                    // console.log("Subgroup before split: ",subgroup," Replaced data", subgroup[0].replace(/\n*/g,""));
                    subgroup = subgroup[1].split("-:-");
                    var reqdata = subgroup[1];
                    // console.log("Subgroup after split: ",subgroup);

                    var fielddata = /[^\[\]]+/ig.exec(subgroup[0]);
                    // console.log("Field data: ",fielddata[0].replace(/[\n\r]+/g,""));
                    fielddata = fielddata[0].replace(/[\n\r]+/g, "").replace(/\s{1,}/g, '').split(">|<");

                    // trap a third layer of content from the field data obtained
                    // this represents the sub content that defines that the 
                    // current entry is related to the value of another field
                    var subfielddataone = [];
                    if (subgroup.length > 2) {
                        subfielddataone = /[^\[\]]+/ig.exec(subgroup[2]);
                        subfielddataone = subfielddataone[0].replace(/[\n\r]+/g, "").replace(/\s{1,}/g, '').split(">|<");
                    }

                    // get the name of the current counter element from the array
                    // and initialise attribute based variables to hold the values
                    // of extra attributes
                    var ccelem = fielddata.shift();
                    if(ccelem.indexOf(":*:")>-1){
                        // this section means that a gdeminimum entry is connected to the counter
                        // elementtci
                        var ccelemsplit=ccelem.split(":*:");
                        ccelem=ccelemsplit[0];
                    }

                    // get the corename of the current group
                    var corename=ccelem.split("count");
                    corename=corename[0];
                    // console.log("corename: ",corename);
                    var preinitout = "";
                    
                    var valset = "";
                    var compulsoryoutput = '';
                    var valcount = 0;
                    var ccount = 0;

                    // console.log("Countelement value: ",ccount,"Countelement: ",ccelem);
                    if (ccelem !== "" && isNumber($('form[name=' + formname + '] input[name=' + ccelem + ']').val())) {
                        ccount = $('form[name=' + formname + '] input[name=' + ccelem + ']').val();
                        valset = $('form[name=' + formname + '] input[name=' + ccelem + ']').attr("data-valset");
                        valcount = $('form[name=' + formname + '] input[name=' + ccelem + ']').attr("data-valcount");
                        if (valset === null || valset === undefined || valset === NaN) {
                            valset = "";
                        }

                        if (valcount === null || valcount === undefined || valcount === NaN) {
                            valcount = 0;
                        } else {
                            valcount = Math.floor(valcount);
                        }
                        // console.log("valset: ",valset," valcount: ",valcount," formname: ",formname," ccelem:",ccelem);
                    }

                    // get the fieldata groups in the form of fieldname-|-fieldtype
                    // fielddata=fielddata.split(">|<");
                    // get the errormsg data
                    suberrgroupdata = suberrgroupdata[0].split(">|<");
                    // console.log("Suberror group after further split: ",suberrgroupdata);         
                    // verify the nature of the validation requirements for the group

                    // loop through each field set and get the fieldname seperate of its
                    // type
                    var dogroupfall = "";
                    var evalvardefns = "";
                    var evalcontent = "";
                    var mainx = 0;
                    for (var x = 0; x < fielddata.length; x++) {
                        mainx++;
                        // put current value in a local easy to use variable
                        var curfielddata = fielddata[x].split("-|-");
                        var fieldname = curfielddata[0];
                        var fieldtype = curfielddata[1];
                        // console.log("fieldtype: ",fieldtype," curfielddata",curfielddata);
                        var dosubgroup = "";
                        var singlefielddataone = [];
                        if (curfielddata.length > 2) {
                            if (curfielddata[2] !== "") {
                                // console.log(" Curfield data: ", curfielddata[2]);
                                singlefielddataone = /[^\(\)]+/ig.exec(curfielddata[2]);
                                singlefielddataone[0] = singlefielddataone[0].replace(/[\n\r]+/g, "").replace(/\s{1,}/g, '');

                                // console.log(" singlefield data: ",singlefielddataone);              
                                dosubgroup = "true";
                            }
                        }
                        // for edit forms, this will be used to test if the entry
                        // being validated should be ignored or not
                        // by default, the delete element has only one possible value hence
                        // validation can commence when it has no value
                        var conditionstatusblock = "";

                        //tells of the field expects a valid kind of input
                        // e.g 'image' would signify any valid image is passed
                        // office for valid office files , video, audio
                        // pdf for pdf e.t.c 
                        var fieldentrytype = "";
                        // tests against a valid extension for the fieldentrytype 
                        var fieldextension = "";
                        // variable holds further validation content for current field
                        var extendvalidationblock = "";

                        var vcblock_main = "";
                        var vcinit_main = "";

                        // variable for holding filetype check and extension validation data
                        var preinit = "";
                        if (fieldtype.indexOf("|") > -1) {
                            // this is done for mainly file based fields that need content checked
                            var fieldtypedata = fieldtype.split("|");
                            fieldtype = fieldtypedata[0];
                            fieldentrytype = fieldtypedata[1];
                            // console.log(fieldentrytype," field entry type"); 

                            // check to see if there are extra field entrytype data which allow
                            // the current field carry different types of data
                            var fetblock = "";
                            var feerrset = "";
                            // this variable is used to detect if there are multiple 
                            // 'type' file data verification
                            var eft=0;
                            // console.log(fieldentrytype," field entry type"); 
                            if (fieldentrytype.indexOf(",") > -1) {
                                var efetype = fieldentrytype.split(",");
                                eft=efetype.length;
                                for (var tt = 0; tt < efetype.length; tt++) {
                                    curfetype = efetype[tt];

                                    feerrset += feerrset == ""? 'checkout[\'' + curfetype + 'errormsg\']' :(curfetype!==""?'<br>OR<br> checkout[\'' + curfetype + 'errormsg\']':"");
                                    
                                    fetblock += fetblock == "" ? '(checkout[\'type\']!=="' + curfetype + '"' : '&&checkout[\'type\']!=="' + curfetype + '"';
                                    
                                    if (tt == efetype.length - 1) {
                                        fetblock += ")";
                                    };
                                }
                            } else {
                                fetblock='checkout[\'type\']!=="' + fieldentrytype + '"';
                                feerrset = 'checkout[\'' + fieldentrytype + 'errormsg\']';
                                
                            }

                            // verify the specified extension data for the fields values
                            var fecblock = "";
                            if (fieldtypedata.length > 2) {
                                fieldextension = fieldtypedata[2];
                                if (fieldextension.indexOf(",") > -1) {
                                    var efetype = fieldextension.split(",");
                                    for (var tt = 0; tt < efetype.length; tt++) {
                                        curfetype = efetype[tt];
                                        fecblock += fecblock == "" ? '||(checkout[\'extension\']!=="' + curfetype + '"' : '&&checkout[\'extension\']!=="' + curfetype + '"';
                                        if (tt == efetype.length - 1) {
                                            fecblock += ")";
                                        };
                                    }
                                } else {
                                    fecblock = '||checkout[\'extension\']!=="' + fieldextension + '"';

                                }
                            }
                            // create attachment condition block
                            preinit = 'if(' + fieldname + '.val()!==""&&formstatus==true&&pointmonitor==false){' + 'var checkout=getExtension(' + fieldname + '.val());' + 'if(' + fetblock + '' + fecblock + '){' + ' window.alert(' +feerrset+ ');console.log("Preview");' + ' $(' + fieldname + ').addClass(\'error-class\').focus();' + ' formstatus= false;' + ' pointmonitor=true;' + '}' + '}';
                        }
                        var errmsgout = suberrgroupdata[x].replace(/[\n\r]+/g, "").replace(/\s{2,}/g, ' ');
                        finalgroup[x] = [];
                        // store the key as a value with the field name as the value
                        finalgroup['' + fieldname + ''] = x;
                        finalgroup[x]['fieldname'] = fieldname;
                        finalgroup[x]['fieldtype'] = fieldtype;
                        finalgroup[x]['fieldentrytype'] = fieldentrytype;
                        finalgroup[x]['fieldextension'] = fieldextension;
                        finalgroup[x]['fieldcextra'] = preinit;
                        finalgroup[x]['errmsg'] = errmsgout;
                        finalgroup[x]['errtestdata'] = "";
                        // console.log(" fieldentrytype group: ",fieldentrytype, " fieldname group: ",fieldname);
                        if (fieldentrytype !== "") {
                            // check to see if the entrype is a file
                            vcinit_main += 'var ' + fieldname + '_edittype=$(' + fieldname + ').attr("data-edittype");\r\n' + 'if(' + fieldname + '_edittype===null||' + fieldname + '_edittype===undefined||' + fieldname + '_edittype===NaN){\r\n' + ' ' + fieldname + '_edittype="";\r\n' + '}';
                            // console.log(" vcinit_main: ",vcinit_main);
                            finalgroup[x]['errtestdata'] = '&&' + fieldname + '_edittype==""';
                        }

                        if (dosubgroup == "true") {
                            var singletests = [];
                            if (singlefielddataone !== "" && singlefielddataone.length > 0) {
                                for (var l = 0; l < singlefielddataone.length; l++) {
                                    var subfielddata = singlefielddataone[l].replace(/[\n\r]+/g, "").replace(/\s{2,}/g, ' ').split("-*-");
                                    // get the type for the current group set
                                    var type = subfielddata.shift();
                                    // console.log(" singlefield data: ",subfielddata," type: ", type);              

                                    if (type !== "group") {
                                        var telemname = subfielddata[0];
                                        // telemname=telemname.replace(/\*n\*/g,x);
                                        var telemtype = subfielddata[1];
                                        var telemvalue = subfielddata[2];
                                        if (telemvalue.indexOf("_") > -1) {
                                            telemvalue = telemvalue.replace(/_{1,}/g, " ");

                                        }

                                        var telemarr = [];
                                        var mtelemv = "";
                                        // check for multiple values that validate
                                        // on the same fieldtest element
                                        if (telemvalue.indexOf(':*:') > -1) {
                                            telemarr = telemvalue.split(":*:");
                                            mtelemv = "domulti";
                                        }
                                        var tarelemname = "";
                                        var curcondition = "";
                                        if (type == "" || type.toLowerCase() == "all") {

                                            // vcinit_main = 'var ' + telemname + '=$("form[name=' + formname + '] ' + telemtype + '[name=' + telemname + ']");';
                                            if (mtelemv == "") {
                                            
                                                var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                                // makes sure that the telemvalue field equates empty on
                                                // encountering *null* keyword as its value
                                                telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? telemvalue = "" : telemvalue = telemvalue;
                                                telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                                vcblock_main += "&&" + telemname + "" + x + ".val()" + c_all + "==\"" + telemvalue + "\"";
                                                // console.log("current count - ",m," curvblock - ",finalgroup[m]['vcblock'],"curvcinit - ",finalgroup[m]['vcinit']);
                                            
                                            } else if (mtelemv = "domulti") {
                                            
                                                var finout = "";
                                            
                                                for (var mu = 0; mu < telemarr.length; mu++) {
                                                    var ccond = mu == 0 ? "(" : "||";
                                                    var ccend = mu == telemarr.length - 1 ? ")" : "";
                                                    telemvalue = telemarr[mu];
                                                    var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                                    // makes sure that the telemvalue field equates empty on
                                                    // encountering *null* keyword as its value
                                                    telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? telemvalue = "" : telemvalue = telemvalue;
                                                    telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                                    finout += ccond + "" + telemname + ".val()" + c_all + "==\"" + telemvalue + "\"" + ccend;
                                                }
                                                vcblock_main += "&&" + finout;
                                            
                                            }

                                        } else if (type == "single") {
                                            tarelemname = subfielddata[3];
                                            // console.log('targetElementname: ',tarelemname);
                                            if (tarelemname !== "") {
                                                var ckey = "";
                                                ckey = finalgroup['' + tarelemname + ''];
                                                if (finalgroup[ckey]) {
                                                    // vcinit_main += 'var ' + telemname + '=$("form[name=' + formname + '] ' + telemtype + '[name=' + telemname + ']");';
                                                    if (mtelemv == "") {
                                                        var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                                        telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? telemvalue = "" : telemvalue = telemvalue;
                                                        telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                                        vcblock_main += "&&" + telemname + ".val()" + c_all + "==\"" + telemvalue + "\"";
                                                        // console.log("current count - ",m," curvblock - ",finalgroup[m]['vcblock'],"curvcinit - ",finalgroup[m]['vcinit']);
                                                    } else if (mtelemv = "domulti") {
                                                        var finout = "";
                                                        for (var mu = 0; mu < telemarr.length; mu++) {
                                                            var ccond = mu == 0 ? "(" : "||";
                                                            var ccend = mu == telemarr.length - 1 ? ")" : "";
                                                            telemvalue = telemarr[mu];
                                                            var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                                            // makes sure that the telemvalue field equates empty on
                                                            // encountering *null* keyword as its value
                                                            telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? telemvalue = "" : telemvalue = telemvalue;
                                                            telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                                            finout += ccond + "" + telemname + ".val()" + c_all + "==\"" + telemvalue + "\"" + ccend;
                                                        }
                                                        vcblock_main += "&&" + finout;
                                                    }

                                                    // console.log("current key - ",ckey," curvblock - ",finalgroup[ckey]['vcblock'],"curvcinit - ",finalgroup[ckey]['vcinit']);
                                                }
                                            } else {
                                                var tarelemerr = 'Sub-validation group error discovered where type is "Single", and validation element is "<b>' + telemname + '</b>", in error map';
                                                raiseMainModal('ED System Failure!!', tarelemerr, 'fail');
                                                formstatus = false;
                                                break;
                                            }
                                        }

                                    } else if (type == "group") {
                                        for (var l2 = 0; l2 < subfielddata.length; l2 += 3) {
                                            // console.log(" singlefield data: ",subfielddata," type: ", type);              
                                            var telemname = subfielddata[l2];
                                            var elt = "";
                                            if (telemname.indexOf('*n*') > -1) {
                                                telemname = telemname;
                                                //.replace(/\*n\*/g,x);
                                                elt = "groupel";
                                            }
                                            var telemtype = subfielddata[l2 + 1];
                                            var telemvalue = subfielddata[l2 + 2];
                                            if (telemvalue.indexOf("_") > -1) {
                                                telemvalue = telemvalue.replace(/_{1,}/g, " ");

                                            }

                                            // console.log("telemvalue: ",telemvalue,"telemtype: ",telemtype," telemname: ",telemname, " cur x: ",x)
                                            var telemarr = [];
                                            var mtelemv = "";
                                            // check for multiple values that validate
                                            if (telemvalue.indexOf(':*:') > -1) {
                                                telemarr = telemvalue.split(":*:");
                                                mtelemv = "domulti";
                                            }

                                            var tarelemname = "";
                                            var curcondition = "";
                                            // vcinit_main += ' /*a test mark*/var ' + telemname + '=$("form[name=' + formname + '] ' + telemtype + '[name=' + telemname + ']");';
                                            if (mtelemv == "") {
                                                var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                                // makes sure that the telemvalue field equates empty on
                                                // encountering *null* keyword as its value
                                                telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? telemvalue = "" : telemvalue = telemvalue;
                                                telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                                vcblock_main += "&&" + telemname + ".val()" + c_all + "==\"" + telemvalue + "\"";
                                                // console.log(vcblock_main);
                                            } else if (mtelemv == "domulti") {
                                                var finout = "";
                                                // console.log("telemvalue: ",telemvalue,"telemtype: ",telemtype," telemname: ",telemname, " cur x: ",x)
                                                for (var mu = 0; mu < telemarr.length; mu++) {
                                                    var ccond = mu == 0 ? "(" : "||";
                                                    var ccend = mu == telemarr.length - 1 ? ")" : "";
                                                    telemvalue = telemarr[mu];
                                                    var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                                    // makes sure that the telemvalue field equates empty on
                                                    // encountering *null* keyword as its value
                                                    telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? telemvalue = "" : telemvalue = telemvalue;
                                                    telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                                    finout += ccond + "" + telemname + ".val()" + c_all + "==\"" + telemvalue + "\"" + ccend;
                                                }
                                                vcblock_main += "&&" + finout;
                                            }
                                            

                                        }
                                        // console.log(vcblock_main);
                                    }
                                }
                            }
                        }
                        // carries validation content at the errtestmsg level

                        // carries validation variable initalisation and condition 
                        // block based information
                        finalgroup[x]['vcblock'] = vcblock_main;
                        // console.log(finalgroup[x]['vcblock']);
                        finalgroup[x]['vcinit'] = vcinit_main;

                    }

                    // create compulsory data set and force them into the vcinit portion
                    // of the finalgroup data set
                    var valsetinit="";
                    var valsetcond="";
                    if (valset !== "") {
                        var valcontent = valset.split(",");
                        var fc = 1;
                        var cvaltotalset = '';
                        cvaltotalset += 'var compulsorymsgout="Please, there is a group set of data with expected number of entries \\"' + valcount + '\\" that has not been completed.' + '<br> After you close this error field the group would be focused on. The group is group : <b>'+groupcounter+'</b>.Please make sure you fill in data in the group-set accordingly.<br>' + 'For example, if the expected number of entries is \'2\', this means that you must provide at least 2 entries for the set, and they must be provided ' + 'in direct order in the form, so skipping say set 2 to fill set 3 will create an error ' + 'even though 2 entries (Set 1 and Set 3) have been provided.<br> Hope this helps, if you do not understand, contact the developer of this application for ' + 'clarification";' + 'var cparelemcount=$("form[name=' + formname + '] input[name=' + ccelem + ']").val();';
                        valsetinit += 'var compulsorymsgout="Please, there is a group set of data with expected number of entries \\"' + valcount + '\\" that has not been completed.' + '<br> After you close this error field the group would be focused on. The group is group : <b>'+groupcounter+'</b>.Please make sure you fill in data in the group-set accordingly.<br>' + 'For example, if the expected number of entries is \'2\', this means that you must provide at least 2 entries for the set, and they must be provided ' + 'in direct order in the form, so skipping say set 2 to fill set 3 will create an error ' + 'even though 2 entries (Set 1 and Set 3) have been provided.<br> Hope this helps, if you do not understand, contact the developer of this application for ' + 'clarification";' + 'var cparelemcount=$("form[name=' + formname + '] input[name=' + ccelem + ']").val();';
                        // obtain the current groups set data element parent and see if
                        var cvalinitset = '';
                        var brco = '';
                        for (var wi = 0; wi < Math.floor(valcount); wi++) {
                            var pt = wi + 1;
                            var cvalcoset = '';
                            for (var vi = 0; vi < valcontent.length; vi++) {
                                if (isNumber(Math.floor(valcontent[vi])) && Math.floor(valcontent[vi]) > 0) {
                                    var cgd = Math.floor(valcontent[vi]) - 1;
                                    /*if(cgd<0){
                                        cgd=0;
                                    }*/
                                    // console.log("cgd: ",cgd," finalgroup: ",finalgroup, " valcontent: ",valcontent);
                                    var tv = vi + 1;
                                    if (vi == 0) {
                                        // set the focus group element counter
                                        fc = tv;
                                    }
                                    cvalinitset += '  var cvalinitset' + tv + '' + pt + '=$("form[name=' + formname + '] ' + finalgroup[cgd]['fieldtype'] + '[name=' + finalgroup[cgd]['fieldname'] + '' + pt + ']");var cvalinitsetcval' + tv + '' + pt + '="";if(cvalinitset' + tv + '' + pt + '===null||cvalinitset' + tv + '' + pt + '===undefined||cvalinitset' + tv + '' + pt + '=="undefined"||typeof cvalinitset' + tv + '' + pt + '=="undefined"||cvalinitset' + tv + '' + pt + '===NaN){ cvalinitsetcval' + tv + '' + pt + '="";}else{ cvalinitsetcval' + tv + '' + pt + '=cvalinitset' + tv + '' + pt + '.val();}/*console.log("cvalinitsetcval: ",cvalinitsetcval' + tv + '' + pt + ');*/';
                                    cvalcoset += vi == 0 ? 'cvalinitsetcval' + tv + '' + pt + '==""' : '&&cvalinitsetcval' + tv + '' + pt + '==""';

                                }
                            }
                            // console.log("Initset - ",cvalinitset," \n Condition set - ",cvalcoset," Counter - ",pt);
                            if ((wi == 0 && brco == "" && cvalcoset !== "") || (wi > 0 && brco == "" && cvalcoset !== "")) {
                                brco = "on";
                                cvaltotalset += 'if(' + cvalcoset + '&&formstatus==true&&pointmonitor==false){' + 'formstatus=false;pointmonitor=true; if(boolmodal==false){raiseMainModal(\'Form error!!\', compulsorymsgout, \'fail\');' + ' $("#mainPageModal").on("hidden.bs.modal", function () {' + '   var mcetester=$(cvalinitset' + fc + '' + pt + ').attr("data-mce");' + '   if(mcetester===null||mcetester===undefined||mcetester===NaN){ mcetester="";}' + '     if(mcetester=="true"){' + '     var mcid=$(cvalinitset' + fc + '' + pt + ').attr("id");' + '     tinyMCE.get(mcid).focus();/*tinymce.execCommand(\'mceFocus\',false,mcid);*/' + '   }else{' + '     $(cvalinitset' + fc + '' + pt + ').addClass(\'error-class\').focus(); }' + '   ' + ' }); /*console.log(" formstatus:",formstatus);*/}' + '}';
                                valsetcond += 'else if(' + cvalcoset + '&&formstatus==true&&pointmonitor==false){' + 'formstatus=false;pointmonitor=true; if(boolmodal==false){raiseMainModal(\'Form error!!\', compulsorymsgout, \'fail\');' + ' $("#mainPageModal").on("hidden.bs.modal", function () {' + '   var mcetester=$(cvalinitset' + fc + '' + pt + ').attr("data-mce");' + '   if(mcetester===null||mcetester===undefined||mcetester===NaN){ mcetester="";}' + '     if(mcetester=="true"){' + '     var mcid=$(cvalinitset' + fc + '' + pt + ').attr("id");' + '     tinyMCE.get(mcid).focus();/*tinymce.execCommand(\'mceFocus\',false,mcid);*/' + '   }else{' + '     $(cvalinitset' + fc + '' + pt + ').addClass(\'error-class\').focus(); }' + '   ' + ' }); /*console.log(" formstatus:",formstatus);*/}' + '}';
                            } else if (wi > 0 && brco == "on" && cvalcoset !== "") {
                                cvaltotalset += 'else if(' + cvalcoset + '&&formstatus==true&&pointmonitor==false){' + ' formstatus=false;pointmonitor=true; if(boolmodal==false){raiseMainModal(\'Form error!!\', compulsorymsgout, \'fail\');' + ' $("#mainPageModal").on("hidden.bs.modal", function () {' + '   var mcetester=$(cvalinitset' + fc + '' + pt + ').attr("data-mce");' + '   if(mcetester===null||mcetester===undefined||mcetester===NaN){ mcetester="";}' + '    if(mcetester=="true"){' + '     var mcid=$(cvalinitset' + fc + '' + pt + ').attr("id");' + '     tinyMCE.get(mcid).focus();/*tinymce.execCommand(\'mceFocus\',false,mcid);*/' + '   }else{' + '     $(cvalinitset' + fc + '' + pt + ').addClass(\'error-class\').focus();}' + '  ' + ' }); /*console.log(" formstatus:",formstatus);*/}' + '}';
                                valsetcond += 'else if(' + cvalcoset + '&&formstatus==true&&pointmonitor==false){' + ' formstatus=false;pointmonitor=true; if(boolmodal==false){raiseMainModal(\'Form error!!\', compulsorymsgout, \'fail\');' + ' $("#mainPageModal").on("hidden.bs.modal", function () {' + '   var mcetester=$(cvalinitset' + fc + '' + pt + ').attr("data-mce");' + '   if(mcetester===null||mcetester===undefined||mcetester===NaN){ mcetester="";}' + '    if(mcetester=="true"){' + '     var mcid=$(cvalinitset' + fc + '' + pt + ').attr("id");' + '     tinyMCE.get(mcid).focus();/*tinymce.execCommand(\'mceFocus\',false,mcid);*/' + '   }else{' + '     $(cvalinitset' + fc + '' + pt + ').addClass(\'error-class\').focus();  }' + '  ' + ' }); /*console.log(" formstatus:",formstatus);*/}' + '}';
                            }
                            var penultm8 = pt - 1;
                        }
                        // final cvaltotalset, here, the count of entries is tallied and 
                        // an error raised if the count doesnt match the expected number of entries
                        if (cvaltotalset !== "") {
                            valsetinit+=cvalinitset;
                            // valsetcond=cvaltotalset;
                            valsetcond += 'else if(cparelemcount<' + valcount + '&&formstatus==true&&pointmonitor==false){' + ' var curerror="Sorry, the minimum number of expected entries is: ' + valcount + ' current detected is: \"+cparelemcount+\" Please add more entries for data groupset '+groupcounter+'"; formstatus=false;pointmonitor=true; raiseMainModal(\'Form error!!\', curerror, \'fail\');' + '  if($("#mainPageModal div.modal-body").html()==curerror){$("#mainPageModal").on("hidden.bs.modal", function () {/*console.log("mainmodalmarkup",$("#mainPageModal div.modal-body").html());*/' + '  goToByScroll(\'form[name='+formname+'] div.' + corename + '-field-hold\',\'fast\',\'selector\');' + ' });/*console.log(" formstatus:",formstatus);*/} ' + '}';
                            cvaltotalset = '' + cvalinitset + '' + cvaltotalset + '';
                            cvaltotalset += 'else if(cparelemcount<' + valcount + '&&formstatus==true&&pointmonitor==false){' + ' var curerror="Sorry, the minimum number of expected entries is: ' + valcount + ' current detected is: \"+cparelemcount+\" Please add more entries for data groupset '+groupcounter+'"; formstatus=false;pointmonitor=true; if(boolmodal==false){raiseMainModal(\'Form error!!\', curerror, \'fail\');' + ' /*console.log(" Current cvalinitset parent: ",$(cvalinitset' + fc + '' + penultm8 + ').parent());*/ $("#mainPageModal").on("hidden.bs.modal", function () {' + '  goToByScroll(\'form[name='+formname+'] div.' + corename + '-field-hold\',\'fast\',\'selector\');' + ' }); }/*console.log(" formstatus:",formstatus);*/' + '}';
                            // console.log("cvaltotalset - ",cvaltotalset);

                        }

                        // add the compulsory section to the vcinit portion of the
                        // first fielddata element in the finalgroup array
                        // finalgroup[0]['vcinit'] += cvaltotalset;
                    }

                    // test for subfield data and proceed to create array of condition
                    // add-on content for the validation fields, using the target fields 
                    // name or group data
                    var subtests = [];
                    var fgl=finalgroup.length;

                    if (subfielddataone !== "" && subfielddataone.length > 0) {
                        for (var l = 0; l < subfielddataone.length; l++) {
                            var subfielddata = subfielddataone[l].replace(/[\n\r]+/g, "").replace(/\s{2,}/g, ' ').split("-|-");
                            // get the type for the current group set
                            var type = subfielddata.shift();
                            // console.log("type: ",type," Subfielddata: " ,subfielddata);
                            if (type !== "group") {
                                var telemname = subfielddata[0];
                                var telemtype = subfielddata[1];
                                var telemvalue = subfielddata[2];
                                if (telemvalue.indexOf("_") > -1) {
                                    telemvalue = telemvalue.replace(/_{1,}/g, " ");

                                }


                                var telemarr = [];
                                var mtelemv = "";
                                // check for multiple values that validate
                                if (telemvalue.indexOf(':*:') > -1) {
                                    telemarr = telemvalue.split(":*:");
                                    mtelemv = "domulti";
                                }

                                var tarelemname = "";
                                var curcondition = "";
                                if (type == "" || type.toLowerCase() == "all") {
                                    for (var m = 0; m < finalgroup.length; m++) {
                                        if (mtelemv == "") {
                                            finalgroup[m]['vcinit'] += 'var ' + telemname + '=$("form[name=' + formname + '] ' + telemtype + '[name=' + telemname + ']");';
                                            var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                            // makes sure that the telemvalue field equates empty on
                                            // encountering *null* keyword as its value
                                            telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? telemvalue = "" : telemvalue = telemvalue;
                                            telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                            finalgroup[m]['vcblock'] += "&&" + telemname + ".val()" + c_all + "==\"" + telemvalue + "\"";
                                            // console.log("current count - ",m," curvblock - ",finalgroup[m]['vcblock'],"curvcinit - ",finalgroup[m]['vcinit']);
                                        } else if (mtelemv == "domulti") {
                                            var finout = "";
                                            for (var mu = 0; mu < telemarr.length; mu++) {
                                                var ccond = mu == 0 ? "(" : "||";
                                                var ccend = mu == telemarr.length - 1 ? ")" : "";
                                                telemvalue = telemarr[mu];
                                                var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                                // makes sure that the telemvalue field equates empty on
                                                // encountering *null* keyword as its value
                                                telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? telemvalue = "" : telemvalue = telemvalue;
                                                telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                                finout += ccond + "" + telemname + ".val()" + c_all + "==\"" + telemvalue + "\"" + ccend;
                                            }
                                            finalgroup[m]['vcblock'] += "&&" + finout;
                                        }
                                    }
                                    ;
                                } else if (type == "single") {
                                    tarelemname = subfielddata[3];
                                    if (tarelemname !== "") {
                                        var ckey = "";
                                        ckey = finalgroup['' + tarelemname + ''];
                                        if (finalgroup[ckey]) {
                                            finalgroup[ckey]['vcinit'] += 'var ' + telemname + '=$("form[name=' + formname + '] ' + telemtype + '[name=' + telemname + ']");';

                                            if (mtelemv == "") {
                                                var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                                telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? telemvalue = "" : telemvalue = telemvalue;
                                                telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                                // bind the current condition to every block in the set
                                                for(var si=0;si<fgl;si++){
                                                    finalgroup[si]['vcblock'] += "&&" + telemname + ".val()" + c_all + "==\"" + telemvalue + "\"";
                                                    // finalgroup[si]['vcblock'] += "&&" + finout;
                                                }
                                                // console.log("current key - ",ckey," curvblock - ",finalgroup[ckey]['vcblock'],"curvcinit - ",finalgroup[ckey]['vcinit']);
                                            } else if (mtelemv == "domulti") {
                                                var finout = "";
                                                for (var mu = 0; mu < telemarr.length; mu++) {
                                                    var ccond = mu == 0 ? "(" : "||";
                                                    var ccend = mu == telemarr.length - 1 ? ")" : "";
                                                    telemvalue = telemarr[mu];
                                                    var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                                    // makes sure that the telemvalue field equates empty on
                                                    // encountering *null* keyword as its value
                                                    telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? telemvalue = "" : telemvalue = telemvalue;
                                                    telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                                    finout += ccond + "" + telemname + ".val()" + c_all + "==\"" + telemvalue + "\"" + ccend;
                                                }
                                                // bind the current condition to every block in the set
                                                for(var si=0;si<fgl;si++){
                                                    finalgroup[si]['vcblock'] += "&&" + finout;
                                                }
                                            }
                                            ;
                                        }
                                    } else {
                                        var tarelemerr = 'Sub-validation group error discovered where type is "Single", and validation element is "<b>' + telemname + '</b>", in error map';
                                        raiseMainModal('ED System Failure!!', tarelemerr, 'fail');
                                        formstatus = false;
                                        break;
                                    }
                                }
                            } else if (type == "group") {
                                for (var l2 = 3; l2 < subfielddata.length; l2 += 3) {
                                    var telemname = subfielddata[l2];
                                    var telemtype = subfielddata[l2 + 1];
                                    var telemvalue = subfielddata[l2 + 2];
                                    if (telemvalue.indexOf("_") > -1) {
                                        telemvalue = telemvalue.replace(/_{1,}/g, " ");

                                    }

                                    var telemarr = [];
                                    var mtelemv = "";
                                    // check for multiple values that valikdate
                                    if (telemvalue.indexOf(':*:') > -1) {
                                        telemarr = telemvalue.split(":*:");
                                        mtelemv = "domulti";
                                    }
                                    var tlal=telemarr.length;
                                    var tarelemname = "";
                                    var curcondition = "";

                                    for (var m = 0; m < fgl; m++) {
                                        if (mtelemv == "") {
                                            finalgroup[m]['vcinit'] += 'var ' + telemname + '=$("form[name=' + formname + '] ' + telemtype + '[name=' + telemname + ']");';
                                            var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                            // makes sure that the telemvalue field equates empty on
                                            // encountering *null* keyword as its value
                                            telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? telemvalue = "" : telemvalue = telemvalue;
                                            telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                            finalgroup[m]['vcblock'] += "&&" + telemname + ".val()" + c_all + "==\"" + telemvalue + "\"";
                                        } else if (mtelemv == "domulti") {
                                            var finout = "";
                                            for (var mu = 0; mu < tlal; mu++) {
                                                var ccond = mu == 0 ? "(" : "||";
                                                var ccend = mu == tlal - 1 ? ")" : "";
                                                telemvalue = telemarr[mu];
                                                var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                                // makes sure that the telemvalue field equates empty on
                                                // encountering *null* keyword as its value
                                                telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? telemvalue = "" : telemvalue = telemvalue;
                                                telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                                finout += ccond + "" + telemname + ".val()" + c_all + "==\"" + telemvalue + "\"" + ccend;
                                            }
                                            finalgroup[m]['vcblock'] += "&&" + finout;
                                        }
                                    }
                                    ;
                                }
                            }
                        }
                    }

                    // sort requirements based on group fall data
                    // console.log("Required data: ",reqdata);
                    if (reqdata.indexOf("groupfall") > -1) {
                        dogroupfall = "true";
                        reqdata = reqdata.split("groupfall");
                        //remove groupfall
                        reqdata = /[^\[\]]+/ig.exec(reqdata[1]);
                        // console.log("the new req data: ",reqdata);
                        reqdata = reqdata[0].split(",");
                        // get inidividual data groups
                        // console.log("the new req data after split: ",reqdata," length: ",reqdata.length);
                    } else {
                        reqdata = /[^\[\]]+/ig.exec(reqdata);
                        // console.log("the new req data: ",reqdata);
                        reqdata = reqdata[0].split(",");
                        // get inidividual data groups
                        // console.log("the new req data after split: ",reqdata);

                    }

                    // create block control for the multiple validation entries
                    if (ccount > 0) {
                        var extendederrorblock = "";
                        var extendedtestblock = "";
                        // allows the current set of entries to fail validation
                        var gstatus = $('form[name=' + formname + '] select[name=group' + groupcounter + '_status' + x + ']');
                        // console.log("cur status test- ",gstatus);
                        if (typeof (gstatus) !== "undefined" && (gstatus.val() == "inactive" || gstatus.val() == "yes")) {
                            // create an expression that always evaluates as false
                            extendederrorblock = '&&1<0';
                        } else {}

                        // create condition blocks for handling multiple form element validation
                        var vcinit = "";
                        for (var c = 0; c < reqdata.length; c++) {
                            var conditionblock = "";
                            var conderrorblock = "";
                            // for initialisation of sub validation field section variables
                            //  and corresponding condition block output
                            var preinit = "";
                            var vcblock = "";
                            var compulsoryblock = "";
                            var rq = reqdata[c];
                            if (dogroupfall == "true") {

                                var curreq = reqdata[c].split('-');

                                // console.log("Current requirements: ",curreq);
                                for (var ct = 0; ct < curreq.length; ct++) {
                                    id = curreq[ct] > 0 ? curreq[ct] - 1 : curreq[ct];
                                    vcblock = finalgroup[id]['vcblock'];
                                    if (vcinit == "") {
                                        vcinit = finalgroup[id]['vcinit'];

                                    } else if (finalgroup[id]['vcinit'] !== "" && vcinit.indexOf('' + finalgroup[id]['vcinit'] + '') < 0) {
                                        // avoid repeating content in the init section
                                        vcinit += finalgroup[id]['vcinit'];
                                    }
                                    
                                    preinitout += finalgroup[id]['fieldcextra'];
                                    preinit = finalgroup[id]['fieldcextra'];

                                    // console.log("curvblock valpoint- ",finalgroup[0]['vcblock'],"curvcinit valpoint - ",finalgroup[0]['vcinit']);
                                    // console.log("Current id: ",id)," Current ct: ",curreq[ct];

                                    if (ct == 0) {
                                        conditionblock = '' + finalgroup[id]['fieldname'] + '.val()==""&&formstatus==true&&pointmonitor==false&&curselect==""' + vcblock + '';
                                        conderrorblock = 'var edittype=' + finalgroup[id]['fieldname'] + '.attr("data-form-edit");if(edittype===null||edittype===undefined||edittype===NaN){var edittype=""};/*console.log("Edittype1 - ",edittype," Current Value - ",' + finalgroup[id]['fieldname'] + '.val());*/var errtestmsg="' + finalgroup[id]['errmsg'] + '";if(errtestmsg!=="NA"&&edittype!=="true"' + finalgroup[id]['errtestdata'] + '){formstatus=false; pointmonitor=true; raiseMainModal(\'Form error!!\', \'' + finalgroup[id]['errmsg'] + '\', \'fail\');' + '$("#mainPageModal").on("hidden.bs.modal", function () {' + '   var mcetester=$(' + finalgroup[id]['fieldname'] + ').attr("data-mce");' + '   if(mcetester===null||mcetester===undefined||mcetester===NaN){ mcetester="";}' + '    if(mcetester=="true"){' + '     var mcid=$(' + finalgroup[id]['fieldname'] + ').attr("id");' + '     tinyMCE.get(mcid).focus();/*tinymce.execCommand(\'mceFocus\',false,mcid);*/' + '   }else{' + '     $(' + finalgroup[id]['fieldname'] + ').addClass(\'error-class\').focus();' + '   }' + '});}';
                                    } else if (ct == 1 && Math.floor(curreq.length) <= 2) {
                                        // in the event there are only two entries
                                        conditionblock += '&&' + finalgroup[id]['fieldname'] + '.val()!==""&&formstatus==true&&pointmonitor==false&&curselect==""' + vcblock + '';

                                    } else if (ct == 1 && Math.floor(curreq.length) > 2) {
                                        // in the event of more than two entries, open the bracket for the entries
                                        conditionblock += '&&(' + finalgroup[id]['fieldname'] + '.val()!==""&&formstatus==true&&pointmonitor==false&&curselect==""' + vcblock + '';

                                    } else if (Math.floor(curreq.length) > Math.floor(ct) + 1 && Math.floor(curreq.length) > 2) {
                                        conditionblock += '||' + finalgroup[id]['fieldname'] + '.val()!==""&&formstatus==true&&pointmonitor==false&&curselect==""' + vcblock + '';

                                    } else if (Math.floor(curreq.length) == Math.floor(ct) + 1 && Math.floor(curreq.length) > 2) {
                                        conditionblock += '||' + finalgroup[id]['fieldname'] + '.val()!=="")&&formstatus==true&&pointmonitor==false&&curselect==""' + vcblock + '';
                                    } else {}
                                }

                            } else {
                                // do plain waterfall check on requireddata array content
                                id = reqdata[c] - 1 > 0 ? reqdata[c] - 1 : 0;
                                vcblock = finalgroup[id]['vcblock'];
                                if (vcinit == "") {
                                    vcinit = finalgroup[id]['vcinit'];

                                } else if (finalgroup[id]['vcinit'] !== "" && vcinit.indexOf('' + finalgroup[id]['vcinit'] + '') < 0) {
                                    // avoid repeating content in the init section
                                    vcinit += finalgroup[id]['vcinit'];
                                }
                                // console.log("the final group value: ",finalgroup[id]," the type of final group",typeof(finalgroup[id]));
                                if (typeof (finalgroup[id]) !== "undefined" && finalgroup[id]['fieldname'] !== "" && finalgroup[id]['fieldtype'] !== "") {
                                    conditionblock = '' + finalgroup[id]['fieldname'] + '.val()==""&&formstatus==true&&pointmonitor==false&&curselect==""' + vcblock + '';
                                    conderrorblock = 'var edittype=' + finalgroup[id]['fieldname'] + '.attr("data-form-edit");if(edittype===null||edittype===undefined||edittype===NaN){var edittype=""};/*console.log("Edittype2 - ",edittype," Current Value - ",' + finalgroup[id]['fieldname'] + '.val());*/var errtestmsg="' + finalgroup[id]['errmsg'] + '";if(errtestmsg.toLowerCase()!=="na"&&edittype!=="true"){formstatus=false; pointmonitor=true; console.log(formstatus,' + finalgroup[id]['fieldname'] + ');raiseMainModal(\'Form error!!\', \'' + finalgroup[id]['errmsg'] + '\', \'fail\');' + '$("#mainPageModal").on("hidden.bs.modal", function () {' + '   var mcetester=$(' + finalgroup[id]['fieldname'] + ').attr("data-mce");' + '   if(mcetester===null||mcetester===undefined||mcetester===NaN){ mcetester="";}' + '    if(mcetester=="true"){' + '     var mcid=$(' + finalgroup[id]['fieldname'] + ').attr("id");' + '     tinyMCE.get(mcid).focus();/*tinymce.execCommand(\'mceFocus\',false,mcid);*/' + '   }else{' + '     $(' + finalgroup[id]['fieldname'] + ').addClass(\'error-class\').focus();' + '   }' + '});}';
                                }
                            }
                            // var valtotal='{}';
                            // console.log("errcontentout value: ",errcontentout," cur block: ",conditionblock," condition error: ",conderrorblock," cur count: ",c," reqdata length: ",reqdata.length," errcontentout typeof", typeof(errcontentout))
                            /*if (valset !== "") {
                                vcinit+=valsetinit;
                            }*/
                            if (errcontentout == "") {
                                // console.log("vcinit ",vcinit);
                                if (conditionblock !== "" && conderrorblock !== "") {
                                    errcontentout = 'if(' + conditionblock + '){$("#mainPageModal").off("hidden.bs.modal");' + '' + conderrorblock + '' + 'console.log(" formstatus:",formstatus);}';
                                }
                            } else if (errcontentout !== "") {
                                // console.log("conderrorblock value: ",conderrorblock);
                                // console.log("errcontentout value: ",errcontentout);
                                if (conditionblock !== "" && conderrorblock !== "") {
                                    errcontentout += 'else if(' + conditionblock + '){$("#mainPageModal").off("hidden.bs.modal");' + '' + conderrorblock + '' + 'console.log(" formstatus:",formstatus);}';
                                }
                            }
                        }
                        // attatch the total current vcinit value
                        errcontentout=vcinit+errcontentout;
                        if(valset!==""){
                            errcontentout=valsetinit+errcontentout+valsetcond;
                        }
                        // attach the preinit data
                        if(preinitout!==""){
                            errcontentout+=preinitout;

                        }
                        // create the formelment variable definitions
                        for (var cx = 0; cx < ccount; cx++) {
                            evalvardefns = "";
                            var cto = cx + 1;
                            var gstatus = $('form[name=' + formname + '] select[name=group' + groupcounter + '_status' + cto + ']');
                            // console.log("cur status test- ",gstatus);
                            if (typeof (gstatus) !== "undefined" && (gstatus.val() == "inactive" || gstatus.val() == "yes")) {
                                // create an expression that always evaluates as false
                                extendederrorblock = ' var curselect=$(\'form[name=' + formname + '] select[name=group' + groupcounter + '_status' + cto + ']\').val();';
                            } else {
                                extendederrorblock = 'var curselect="";';
                            }
                            evalvardefns += extendederrorblock;
                            for (var v = 0; v < finalgroup.length; v++) {
                                var p = cx + 1;
                                // create the variable instances for the eval section
                                evalvardefns += " var " + finalgroup[v]['fieldname'] + "=" + "$('form[name=" + formname + "] " + finalgroup[v]['fieldtype'] + "[name=" + finalgroup[v]['fieldname'] + "" + p + "]'); console.log('fieldname: '," + finalgroup[v]['fieldname'] + ",' formstatus:',formstatus);";
                            }
                            evalcontent = '' + evalvardefns + '' + errcontentout + '';
                            // console.log('Eval count group data: ',groupcounter," Eval Data", evalcontent);
                            eval(evalcontent);
                            /*reset the hiddenbs modal function to something harmless*/
                            /*$("#mainPageModal").on("hidden.bs.modal", function() {

                            });*/
                            // testsystem using only scripts, the script data is placed in an appended
                            // scriptelement for the current form
                            /*if($('form[name=' + formname + '] script[data-name=parse_gd').length>0){
                                $('form[name=' + formname + '] script[data-name=parse_gd').remove();
                            }
                            maintotalscripts='<script data-name="parse_gd">$(document).ready(function(){var formstatus=true; var pointmonitor=false; '+evalcontent+'});</script>';
                            $(maintotalscripts).appendTo('form[name=' + formname + ']');*/
                            // this ensures the loop stops running completely
                            // when a condition is not met
                            if (formstatus == false) {
                                break;
                            }
                        }

                    }
                    // end egroup|data section
                } else {
                    // start single field data section
                    // singlecounter++;
                    // console.log(typeof(curgroup));
                    if (typeof (curgroup) !== "undefined" && curgroup !== "") {
                        var errcontentout = "";
                        var evalcontent = "";
                        var evalvardefns = "";
                        var vcinit = "";
                        var vcblock = "";
                        var preinit = "";
                        var fielddata = curgroup.split("-:-");
                        var fieldname = fielddata[0].replace(/[\n\r]*/g, "").replace(/\s{1,}/g, '');
                        var fieldtype = fielddata[1].replace(/[\n\r]*/g, "").replace(/\s{1,}/g, '');
                        var fieldentrytype = "";
                        var fieldextension = "";
                        var errtestdata = "";

                        if (fieldtype.indexOf("|") > -1) {
                            var fieldtypedata = fieldtype.split("|");
                            fieldtype = fieldtypedata[0];
                            fieldentrytype = fieldtypedata[1];
                            
                            // check to see if there are extra field entrytype data which allow
                            // the current field carry different types of data
                            var fetblock = "";
                            var feerrset = "";
                            // this variable is used to detect if there are multiple 
                            // 'type' file data verification
                            var eft=0;
                            // console.log(fieldentrytype," field entry type"); 
                            if (fieldentrytype.indexOf(",") > -1) {
                                var efetype = fieldentrytype.split(",");
                                eft=efetype.length;
                                for (var tt = 0; tt < efetype.length; tt++) {
                                    curfetype = efetype[tt];

                                    feerrset += feerrset == ""? 'checkout[\'' + curfetype + 'errormsg\']' :(curfetype!==""?'+\'\\n OR \\n\'+ checkout[\'' + curfetype + 'errormsg\']':"");
                                    
                                    fetblock += fetblock == "" ? '(checkout[\'type\']!=="' + curfetype + '"' : '&&checkout[\'type\']!=="' + curfetype + '"';
                                    
                                    if (tt == efetype.length - 1) {
                                        fetblock += ")";
                                        feerrset+="";
                                    };
                                }
                            } else {
                                fetblock='checkout[\'type\']!=="' + fieldentrytype + '"';
                                feerrset = 'checkout[\'' + fieldentrytype + 'errormsg\']';
                                
                            }

                            var fecblock = "";
                            if (fieldtypedata.length > 2) {
                                fieldextension = fieldtypedata[2];
                                if (fieldextension.indexOf(",") > -1) {
                                    var efetype = fieldextension.split(",");
                                    for (var tt = 0; tt < efetype.length; tt++) {
                                        curfetype = efetype[tt];
                                        fecblock += fecblock == "" ? '||(checkout[\'extension\']!=="' + curfetype + '"' : '&&checkout[\'extension\']!=="' + curfetype + '"';
                                        if (tt == efetype.length - 1) {
                                            fecblock += ")";
                                        }
                                        ;
                                    }
                                } else {
                                    fecblock = '||checkout[\'extension\']!=="' + fieldextension + '"';
                                }
                            }
                            // console.log(" fieldentrytype: ",fieldentrytype)
                            if (fieldentrytype !== "") {
                                // check to see if the entryTYpe is a file
                                vcinit += 'var ' + fieldname + '_edittype=$(' + fieldname + ').attr("data-edittype");\r\n' + 'if(' + fieldname + '_edittype===null||' + fieldname + '_edittype===undefined||' + fieldname + '_edittype===NaN){\r\n' + ' ' + fieldname + '_edittype="";\r\n' + '}';
                                errtestdata = '&&' + fieldname + '_edittype==""';
                            }
                            // create attachment condition block
                            preinit = 'if(' + fieldname + '.val()!==""&&formstatus==true&&pointmonitor==false){' + 'var checkout=getExtension(' + fieldname + '.val());' + 'if(' + fetblock + '' + fecblock + '){' + ' window.alert(' + feerrset + ');console.log("Preview");' + ' $(' + fieldname + ').addClass(\'error-class\').focus();' + ' formstatus= false;' + ' pointmonitor= true;' + '}' + '/*console.log("fieldname: "," '+fieldname+' "," formstatus: ",formstatus);*/}';
                            // console.log(" cur preinit: \n",preinit);
                            totalpreinits+=preinit;
                        }

                        // trap a third layer of content from the field data obtained
                        // this represents the sub content that defines that the 
                        // current entry is related to the value of another field
                        var subfielddataone = [];
                        if (fielddata.length > 2) {
                            subfielddataone = /[^\[\]]+/ig.exec(fielddata[2]);
                            subfielddataone = subfielddataone[0].replace(/[\n\r]+/g, "").replace(/\s{1,}/g, '').split(">|<");
                            // console.log("subfielddataparent - ",fielddata[2]," subfielddataone - ",subfielddataone);
                        }

                        // test for subfield data and proceed to craete array of condition
                        // add-on content for the validation fields, using the target fields 
                        // name or group data
                        var subtests = [];
                        // console.log(subfielddataone);
                        if (subfielddataone !== "" && subfielddataone.length > 0) {
                            for (var l = 0; l < subfielddataone.length; l++) {
                                var subfielddata = subfielddataone[l].replace(/[\n\r]+/g, "").replace(/\s{2,}/g, ' ').split("-|-");
                                // get the type for the current group set
                                var type = subfielddata.shift();
                                if (type !== "group") {
                                    var telemname = subfielddata[0];
                                    var telemtype = subfielddata[1];
                                    var telemvalue = subfielddata[2];
                                    if (telemvalue.indexOf("_") > -1) {
                                        telemvalue = telemvalue.replace(/_{1,}/g, " ");

                                    }

                                    var telemarr = [];
                                    var mtelemv = "";
                                    // check for multiple values that valikdate
                                    if (telemvalue.indexOf(':*:') > -1) {
                                        // console.log("splitting the atom");
                                        telemarr = telemvalue.split(":*:");
                                        mtelemv = "domulti";
                                    }

                                    var tarelemname = "";
                                    var curcondition = "";
                                    if (type == "" || type.toLowerCase() == "all") {

                                        for (var m = 0; m < finalgroup.length; m++) {
                                            if (mtelemv == "") {
                                                vcinit += 'var ' + telemname + '=$("form[name=' + formname + '] ' + telemtype + '[name=' + telemname + ']");';
                                                var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                                // makes sure that the telemvalue field equates empty on
                                                // encountering *null* keyword as its value
                                                telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? telemvalue = "" : telemvalue = telemvalue;
                                                telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                                vcblock += "&&" + telemname + ".val()" + c_all + "==\"" + telemvalue + "\"";
                                            } else if (mtelemv == "domulti") {
                                                var finout = "";
                                                for (var mu = 0; mu < telemarr.length; mu++) {
                                                    var ccond = mu == 0 ? "(" : "||";
                                                    var ccend = mu == telemarr.length - 1 ? ")" : "";
                                                    telemvalue = telemarr[mu];
                                                    var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                                    // makes sure that the telemvalue field equates empty on
                                                    // encountering *null* keyword as its value
                                                    telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? telemvalue = "" : telemvalue = telemvalue;
                                                    telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                                    finout += ccond + "" + telemname + ".val()" + c_all + "==\"" + telemvalue + "\"" + ccend;
                                                }
                                                vcblock += "&&" + finout;
                                            }
                                        }
                                        ;
                                    } else if (type == "single") {
                                        tarelemname = subfielddata[3];
                                        if (tarelemname !== "") {
                                            /*var ckey="";
                                            ckey=finalgroup[''+tarelemname+''];*/
                                            if (mtelemv == "") {
                                                vcinit += 'var ' + telemname + '=$("form[name=' + formname + '] ' + telemtype + '[name=' + telemname + ']");';
                                                var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                                telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                                vcblock += "&&" + telemname + ".val()" + c_all + "==\"" + telemvalue + "\"";
                                            } else if (mtelemv == "domulti") {
                                                var finout = "";
                                                for (var mu = 0; mu < telemarr.length; mu++) {
                                                    var ccond = mu == 0 ? "(" : "||";
                                                    var ccend = mu == telemarr.length - 1 ? ")" : "";
                                                    telemvalue = telemarr[mu];
                                                    var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                                    // makes sure that the telemvalue field equates empty on
                                                    // encountering *null* keyword as its value
                                                    telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? telemvalue = "" : telemvalue = telemvalue;
                                                    telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                                    finout += ccond + "" + telemname + ".val()" + c_all + "==\"" + telemvalue + "\"" + ccend;
                                                }
                                                vcblock += "&&" + finout;
                                            }

                                        } else {
                                            var tarelemerr = 'Sub-validation group error discovered where type is "Single", and validation element is "<b>' + telemname + '</b>", in error map';
                                            raiseMainModal('ED System Failure!!', tarelemerr, 'fail');
                                            formstatus = false;
                                            break;
                                        }
                                    }
                                    // console.log("curvblock - ",vcblock," curvinit - ",vcinit);
                                } else if (type == "group") {
                                    // console.log("subfielddata length - ",subfielddata.length," subfielddata - ",subfielddata);
                                    for (var l2 = 0; l2 < subfielddata.length; l2 += 3) {
                                        var telemname = subfielddata[l2];
                                        var telemtype = subfielddata[l2 + 1];
                                        var telemvalue = subfielddata[l2 + 2];
                                        if (telemvalue.indexOf("_") > -1) {
                                            telemvalue = telemvalue.replace(/_{1,}/g, " ");
                                        }
                                        var telemarr = [];
                                        var mtelemv = "";
                                        // check for multiple values that valikdate
                                        if (telemvalue.indexOf(':*:') > -1) {
                                            // console.log("splitting the atom");
                                            telemarr = telemvalue.split(":*:");
                                            mtelemv = "domulti";
                                        }
                                        var tarelemname = "";
                                        var curcondition = "";
                                        vcinit += 'var ' + telemname + '=$("form[name=' + formname + '] ' + telemtype + '[name=' + telemname + ']");';
                                        if (mtelemv == "") {
                                            var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                            // makes sure that the telemvalue field equates empty on
                                            // encountering *null* keyword as its value
                                            telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? telemvalue = "" : telemvalue = telemvalue;
                                            telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                            vcblock += "&&" + telemname + ".val()" + c_all + "==\"" + telemvalue + "\"";
                                            // console.log("curvblock - ",vcblock," curvinit - ",vcinit);
                                        } else if (mtelemv == "domulti") {
                                            var finout = "";
                                            // console.log("entering the dragon");

                                            for (var mu = 0; mu < telemarr.length; mu++) {
                                                var ccond = mu == 0 ? "(" : "||";
                                                var ccend = mu == telemarr.length - 1 ? ")" : "";
                                                telemvalue = telemarr[mu];
                                                var c_all = telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? "!" : "";
                                                // makes sure that the telemvalue field equates empty on
                                                // encountering *null* keyword as its value
                                                telemvalue == "" || telemvalue.indexOf('*any*') > -1 ? telemvalue = "" : telemvalue = telemvalue;
                                                telemvalue == "*null*" ? telemvalue = "" : telemvalue = telemvalue;
                                                finout += ccond + "" + telemname + ".val()" + c_all + "==\"" + telemvalue + "\"" + ccend;
                                            }
                                            vcblock += "&&" + finout;

                                        }
                                        // console.log("curvblock - ",vcblock," curvinit - ",vcinit);

                                    }
                                }
                            }
                        }
                        var errdata = errgroup.split("-:-");
                        var errdata1 = errdata[1];
                        if (errdata[1] === null || errdata[1] === undefined || errdata[1] === NaN) {
                            var errdata1 = "";
                        }
                        var errmsgout = typeof (errdata1) !== "undefined" && errdata1 !== "" ? errdata[1].replace(/[\n\r]*/g, "").replace(/\s{1,}/g, ' ') : "";
                        // create the variable instances for the eval section
                        // and make sure the field is not chosen if the NA
                        // errmsg is present meaning that the field is not required
                        // console.log("Error msag out - ",errmsgout);
                        if (errmsgout.toLowerCase() !== "na" || errmsgout !== "NA" || errmsgout !== " NA" || errmsgout !== " NA " || errmsgout !== "NA ") {
                            evalvardefns += " var " + fieldname + "=" + "$('form[name=" + formname + "] " + fieldtype + "[name=" + fieldname + "]'); /*console.log(\" Current Value - \"," + fieldname + ".val());*/";
                            conditionblock = '' + fieldname + '.val()==""&&formstatus==true&&pointmonitor==false' + vcblock + '';
                            conderrorblock = 'var edittype=' + fieldname + '.attr("data-form-edit");if(edittype===null||edittype===undefined||edittype===NaN){var edittype=""};' + '/*console.log("Element - ",$(fieldname));*//*console.log("Edittype3 - ",edittype);*/var errtestmsg="' + errmsgout + '";if(errtestmsg!=="NA"&&edittype!=="true"' + errtestdata + '){formstatus=false; pointmonitor=true; if(boolmodal==false){raiseMainModal(\'Form error!!\', \'' + errmsgout + '\', \'fail\');' + '$("#mainPageModal").on("hidden.bs.modal", function () {' + '   var mcetester=$(' + fieldname + ').attr("data-mce");' + '   if(mcetester===null||mcetester===undefined||mcetester===NaN){ mcetester="";}' + '    if(mcetester=="true"){' + '     var mcid=$(' + fieldname + ').attr("id");/*console.log("tmcid - ",tinyMCE.get(mcid),"mcid - ",mcid);*/' + '     tinyMCE.get(mcid).focus();/*tinymce.execCommand(\'mceFocus\',false,mcid);*/' + '     /*tinyMCE.getInstanceById(mcid).focus();*/' + '   }else{' + '     $(' + fieldname + ').addClass(\'error-class\').focus();' + '   } ' + '});}/*console.log("fieldname: "," '+fieldname+' "," formstatus: ",formstatus);*/}';
                        } else {
                            evalvardefns += "var " + fieldname + "=" + "$('form[name=" + formname + "] " + fieldtype + "[name=" + fieldname + "]');";
                        }

                        totalvardefns+=evalvardefns+" "+vcinit;
                        if (errcontentout == "") {
                            if (conditionblock !== "" && conderrorblock !== "") {
                                errcontentout = '' + vcinit + 'if(' + conditionblock + '){' + '' + conderrorblock + '' + '}' + preinit + '';
                                totalerrcontentout+='if(' + conditionblock + '){' + '' + conderrorblock + '' + '}';
                            }
                        }
                        evalcontent = '' + evalvardefns + '' + errcontentout + '/*console.log("Current Point Single Test")*/';
                        // console.log("Eval Data single", evalcontent);
                        eval(evalcontent);
                        
                        
                        // this ensures the loop stops running completely
                        // when a condition is not met
                        if (formstatus == false) {
                            break;
                        }
                    } else {
                        errmsg = 'Missing form data detected, possible malformed validation triggers.';
                        raiseMainModal('Parse error!!', '' + errmsg + '', 'fail');
                        formstatus = false;
                        break;
                    }
                }

            }
        } else {
            errmsg = 'Extra form data and errormap do not match in length.';
            raiseMainModal('Parse error!!', '' + errmsg + '', 'fail');
            formstatus = false;
            // break;
        }
    }
    
    // begin password and email data verification
    var psect = $('form[name=' + formname + '] input[data-pvalidate=true]');
    if (psect === null || psect === undefined || psect === NaN) {
        var psect = "";
    }
    // console.log("psect: ",psect);

    if (psect.length > 0 && formstatus == true) {
        // for handling password fields and checking their confirmation fields
        // if available
        // the current password element
        var celemname = $('form[name=' + formname + '] [data-pvalidate=true]').attr("name");
        var celem = $('form[name=' + formname + '] input[name=' + celemname + ']');
        // console.log("Celem: ", celem);

        var pval = $('form[name=' + formname + '] [data-pvalidate=true]').val();
        var pvala = "";
        if (pval !== "") {
            pvala = " Current Password is \"<b>" + pval + "</b>\"";
        }
        // validation types
        // letters only = "l"
        // letters and underscore= "lu"
        // letter casesensitive and underscore = "lcu"
        // numbers and letters = "nl"
        // numbers and letter casesensitive = "nlc"
        // numbers and letter and underscore = "nlu"
        // numbers and letter casesensitive and underscore = "nlcu"
        var vtype = $('form[name=' + formname + '] [data-pvalidate=true]').attr('data-pvtype');

        // validation confirmation field name
        var vcname = $('form[name=' + formname + '] [data-pvalidate=true]').attr('data-pvcname');
        var rgx = "";

        // strongly enforce the type ensuring that all parameters are fulfilled
        var ftype = "";
        ftype = $('form[name=' + formname + '] [data-pvalidate=true]').attr('data-pvforce');

        // minimum amount of characters expected
        var fcount = $('form[name=' + formname + '] [data-pvalidate=true]').attr('data-pvcount');
        if (fcount === null || fcount === undefined || fcount === NaN) {
            var fcount = 8;
        }

        // verify if there is a confirmation field
        var cfieldname = $('form[name=' + formname + '] [data-pvalidate=true]').attr('data-pvcfieldname');
        if (cfieldname === null || cfieldname === undefined || cfieldname === NaN) {
            var cfieldname = "";
        }
        if (cfieldname !== "") {
            var cfdata = $('form[name=' + formname + '] input[name=' + cfieldname + ']');

        }

        if (vtype == "l") {
            rgx = new RegExp('[A-z][a-z]{' + fcount + ',}');
        } else if (vtype == "lu" || vtype == "lcu") {
            ftype !== "" ? rgx = new RegExp('^(?=.*[a-zA-Z_])(?=.*[A-Z])(?=.*[^\\d])(?=.*[_])[a-zA-Z_]{' + fcount + ',}$') : rgx = new RegExp('^[a-zA-Z_](?=[a-zA-Z_]{1,}$)');

        } else if (vtype == "nl" || vtype == "nlc" || vtype == "nlu" || vtype == "nlcu") {
            ftype !== "" ? rgx = new RegExp('^(?=.*[a-zA-Z_\\d])(?=.*[A-Z])(?=.*[\\d])(?=.*[_])[a-zA-Z_\\d]{' + fcount + ',}$') : rgx = new RegExp('^[A-Z0-9\\w\\d](?=[A-Z0-9\\w\\d]{1,}$)');
        } else if (vtype == "nlcus") {
            ftype !== "" ? rgx = new RegExp('^(?=.*[a-zA-Z_\\d\\$!%&])(?=.*[A-Z])(?=.*[\\d])(?=.*[_])(?=.*[\\$!%&])[a-zA-Z_\\d\\$!%&]{' + fcount + ',}$') : rgx = new RegExp('([A-z][0-9][\\w][\\$!%&])?(?=.*[a-z]{1,}).{' + fcount + ',}');
        }
        var errmsg = "";
        var ftypemsg = "";
        if (ftype == "true") {
            ftypemsg = " (at least one of each character set must be matched). ";

        }
        if (vtype == "lcu" || vtype == "lu") {

            errmsg = "This Password field's accepted characters are letters(Upper and Lower Case) and underscore" + ftypemsg + "" + pvala;
        } else if (vtype == "nl" || vtype == "nlc") {
            errmsg = "This Password field's accepted characters are letters(Upper and Lower Case) and numbers only" + ftypemsg + "" + pvala;

        } else if (vtype == "nlu" || vtype == "nlcu") {
            errmsg = "This Password field's accepted characters are letters(Upper and Lower Case), numbers and underscore" + ftypemsg + "" + pvala;
        } else if (vtype == "nlcus") {
            errmsg = "This Password field's accepted characters are letters, numbers, underscore, and special characters: $ ! @ % &." + ftypemsg + "" + pvala;
        }
        if (pval !== "" && pval.replace(/\s\s*/g, "").length < parseInt(fcount)) {
            var clength = pval.replace(/\s\s*/g, "").length;
            var cleft = parseInt(fcount) - parseInt(clength);
            errmsg = "Minimum password length is " + fcount + ", characters left: " + cleft;
            raiseMainModal('Failure!!', '' + errmsg + '', 'fail');
            $("#mainPageModal").on("hidden.bs.modal", function () {
                celem.focus();
            });
            formstatus = false;
            pointmonitor = true;
        } else {
            var testreg = rgx.test(pval);
            // console.log(testreg);
            if (testreg == false) {
                raiseMainModal('Failure!!', '' + errmsg + '', 'fail');
                $("#mainPageModal").on("hidden.bs.modal", function () {
                    celem.focus();
                });
                formstatus = false;
                pointmonitor = true;
            }
        }

        // compare the current value with the confirmation value
        if(cfieldname!==""&&formstatus==true){
            if(cfdata.val()!==pval){
                errmsg="Passwords do not match ";
                raiseMainModal('Failure!!', '' + errmsg + '', 'fail');
                $("#mainPageModal").on("hidden.bs.modal", function () {
                    celem.focus();
                });
                formstatus = false;
                pointmonitor = true;   
            }
        }
    }

    var esect = $('form[name=' + formname + '] input[data-evalidate=true]');
    if (esect === null || esect === undefined || esect === NaN) {
        var esect = "";
    }
    if (esect.length > 0 && formstatus == true) {
        // console.log(esect);
        // email data verification
        // check to see how many email fields for validation are present
        var elen = $('form[name=' + formname + '] input[data-evalidate=true]').length;
        for (var i = 0; i < parseInt(elen); i++) {
            var curel = $('form[name=' + formname + '] input[data-evalidate=true]')[i];

            var curval = curel.value;
            var cstat = emailValidatorReturnableTwo(curval);
            if (cstat['status'] == "false") {
                var errmsg = cstat['errormsg'];
                raiseMainModal('Form Error!!', '' + errmsg + '', 'fail');
                formstatus = false;
                pointmonitor = true;
                curel.focus();
                break;
            }
        }
    }
    // note, do not use the pvalidate or evalidate attributes on elements whose current
    // values are not being vetted for emptiness. i.e fields that are not required

    // sort through data that needs extra database vetting
    var fesect = $('form[name=' + formname + '] input[data-feverify=true]');

    if (fesect === null || fesect === undefined || fesect === NaN) {

        var fesect = "";

    }

    if (fesect.length > 0 && formstatus == true&&pointmonitor==false) {
        formstatus=false;
        pointmonitor=true;
        var elen = $('form[name=' + formname + '] input[data-feverify=true]').length;

        var errmsg="";
        for (var i = 0; i < parseInt(elen); i++) {

            var curel = $('form[name=' + formname + '] input[data-feverify=true]')[i];
            var curname=curel.getAttribute("name");
            var mainparent=$('form[name=' + formname + ']').find('input[name='+formname+']').parent().parent();
            var state=curel.getAttribute('data-fev-state');
            if(state=="processing"){
                errmsg="Sorry, a validation operation on one of the fields in this form is on going, wait for it to finish then try submitting then";
            }
            if(state=="failed"){
                errmsg="Error, a field has failed validation.";
                // break;
            }
            // console.log("Mainparent: ",mainparent);
            if(errmsg!==""){
                raiseMainModal('Form Error!!', '' + errmsg + '', 'fail');
                $("#mainPageModal").on("hidden.bs.modal", function () {
                    curel.focus();
                    // curel.parent
                });
                mainparent.find('.alert._float').removeClass('hidden');
                break;
            }
        }
        if(errmsg==""){
            formstatus=true;
            pointmonitor=false;
        }

    }

    // begin comparison field data for the current form fields
    var csect = $('form[name=' + formname + '] input[data-cvalidate=true]');

    if (csect === null || csect === undefined || csect === NaN) {

        var csect = "";

    }

    if (csect.length > 0 && formstatus == true) {

        var elen = $('form[name=' + formname + '] input[data-cvalidate=true]').length;

        for (var i = 0; i < parseInt(elen); i++) {
            var curel = $('form[name=' + formname + '] input[data-cvalidate=true]')[i];

            var curval = curel.value;
            // get the value of the field to be compared to
            var nextel=curel.getAttribute("data-element-data"); 
            var eldata=nextel.split("-:-");
            var nextval=$('form[name=' + formname + '] '+eldata[1]+'[name='+eldata[0]+']').val();
            // console.log(nextel,nextval,curval,eldata);
            if (curval !== nextval) {
                var errmsg=curel.getAttribute('data-error-output');
                // console.log("the error msg",errmsg);
                if (errmsg === null || errmsg === undefined || errmsg === NaN) {

                    var errmsg = "";

                }
                errmsg = errmsg==""?"Values do not match":errmsg;
                raiseMainModal('Form Error!!', '' + errmsg + '', 'fail');
                formstatus = false;
                pointmonitor = true;
                $("#mainPageModal").on("hidden.bs.modal", function () {
                    curel.focus();
                });
                break;
            }
        }

    }

    // begin total file size calculation for the current form file fields

    // first check if the form is a monitor type
    var fttypeform=formmain.attr("data-fdgen");
    if(typeof fttypeform=="undefined" || fttypeform === null || fttypeform === undefined || fttypeform === NaN){
        var fttypeform="noval";
    }

    if(fttypeform=="true"&& formstatus == true){
        // run through the form for each input file element
        var robj=formmain.find('input[type=file]');
        var inputlength=robj.length;
        totalsize=0;
        if(inputlength>0){
            for(var i=0;i<inputlength;i++){
                var curel=robj.get(i);
                // console.log("curel: ",curel);
                var cval=curel.value;
                var csize=curel.getAttribute('data-file-size');
                if(typeof csize=="undefined" || esect === null || csize === undefined || csize === NaN){
                    var csize="noval";
                }
                if(cval!==""&&csize!=="noval"){
                    // add the value of csize
                    totalsize+=parseInt(csize);
                }else if(cval!==""&&csize=="noval"){
                    var errmsg = "A file field in your form has a value but no file size calculated for it, please make sure the form has not been tampered with, check the file field, and try submitting again";
                    raiseMainModal('File Data Mark Error!!', '' + errmsg + '', 'fail');
                    formstatus = false;
                    pointmonitor = true;
                    $("#mainPageModal").on("hidden.bs.modal", function () {
                        curel.focus();
                    });
                    break;
                }
            }
            // convert the size to main 
            // get the expected filesize value
            var fdgen=formmain.find('input[type=hidden][data-fdgen=true]');
            if(totalsize>0){
                if(fdgen.length>0){
                    maxsize=fdgen.val();
                    // console.log("fdgen element: ",fdgen," maxsize value: ",maxsize);
                    if(maxsize!==""){
                        //  the  expected maximum value is in G,M,K
                        // so we remove the suffix and get the real maximum if they are
                        // present
                        var sttype="megabyte";
                        if(maxsize.toLowerCase().indexOf('k')>-1||maxsize.toLowerCase().indexOf('kb')>-1||maxsize.toLowerCase().indexOf('kilobytes')>-1){
                            sttype='kilobyte';
                        }else if(maxsize.toLowerCase().indexOf('g')>-1||maxsize.toLowerCase().indexOf('gb')>-1||maxsize.toLowerCase().indexOf('gigabytes')>-1){
                            sttype='gigabyte';

                        }
                        var tconvert=calculateTotalFileSize(totalsize);
                        tsize=tconvert[sttype];
                        maxsize=maxsize.replace(/\D/g,"");
                        // make sure the value obtianed is in digits first 
                        if(isNumber(maxsize)){
                            if(tsize>maxsize){
                                var errmsg = "A cumulative filesize error has occured, the current size of files to be upploaded is : "+tsize+"("+sttype+") Max allowed is "+maxsize+"";
                                raiseMainModal('File Size Error!!', '' + errmsg + '', 'fail');
                                formstatus = false;
                                pointmonitor = true;

                            }
                        }
                    }
                }else{
                    var errmsg = "There seems to be a problem, no maximum marker element set for file size data sets";
                    raiseMainModal('File Data Mark Error!!', '' + errmsg + '', 'fail');
                    formstatus = false;
                    pointmonitor = true;

                }
            }
        }else{
            // check to see if any of the file elements have a value om ot
        }
    }
    formit['formstatus'] = formstatus;
    formit['pointmonitor'] = pointmonitor;
    if (stype == "") {
        return formit;
    } else if (stype == "complete") {
        if (formstatus == true) {
            var tester = window.confirm('The form is ready to be submitted click ok to continue or cancel to review');
            if (tester === true) {
                $('form[name=' + formname + ']').attr("onSubmit", "return true;").submit();
            } else {
                $('form[name=' + formname + ']').attr("onSubmit", "return false;");
            }
        }
        /*else if(formstatus==false && ajaxtestrun==true){
      alert("There is a validation process currently being carried out on a field with provided data, please be patient as this takes a moment");
    }*/
    }
}

// check fields whose data must be validated against
/*
*   @author Olagoke Okebukola
*   
*/
function fieldValueVerification(obj){
    // the purpose for this is to run ajax queries and test if the supplied value
    // in the field is none existent against a corresponding
    // database table value or group of values
    // this system here works in conjunction with the gdvalidatoin function 
    // 'submitCustom'
    // the following attributes are valid for it to function
    // data-feverify="true"
    // data-fev-tbl="the name of the database table to view"
    // data-fev-col="the name of the database table column to view"
    // data-fev-state="the current state of operation on the field"
    // default is 'inactive' it will change to 'processing' when in operation,
    // 'failed' when action was not successful, or 'done' when action was successful
    // data-fev-lval="the current value for the field after it has been validated"
    // data-fev-elval="the current editted value for the field that has been stored"

    // data-fev-map="extra query data map in the form of logic,column,value"
    // data-fev-err="error msg"

    // Begin code
    // first of we need the parent , and associated phase element for showing 
    // visually the success or failure of an action
    // console.log("<b>Feverify Start:</b>",obj);
    var parent=obj.parent();
    var mainparent=obj.parent().parent().parent();
    var val=obj.val();
    var tbl=obj.attr('data-fev-tbl');
    var col=obj.attr('data-fev-col');
    var curstate=obj.attr('data-fev-state');
    var map=obj.attr('data-fev-map');
    if (map === null || map === undefined || map === NaN) {
        var map = "";
    }
    // check of the current element is an email type of field running validation
    var eattr=obj.attr('data-evalidate');
    if (eattr === null || eattr === undefined || eattr === NaN) {
        var eattr = "";
    }
    // check if a custom error message is present 
    var errmsg=obj.attr('data-fev-err');
    if (errmsg === null || errmsg === undefined || errmsg === NaN) {
        var errmsg = "";
    }

    // check if the field has a valid data-fev-elval field
    // showing its for an edit form 
    var elval=obj.attr('data-fev-elval');
    if (elval === null || elval === undefined || elval === NaN) {
        var elval = "";
    }

    var item_loader=mainparent.find("._fev-group .loadermini");

    // set the default error message
    var errmsg="Error, a previous data match was found for your entry, please change it.";
    // sentinel variable to specify if a check is valid to be done
    var runcheck=true; 
    
    // verify the validity of the email
    if(eattr!==""&&eattr=="true"&&val!==""){
        var cstat = emailValidatorReturnableTwo(val);
        if (cstat['status'] == "false") {
            var errmsg = cstat['errormsg'];
            raiseMainModal('Form Error!!', '' + errmsg + '', 'fail');
            formstatus = false;
            pointmonitor = true;
            runcheck=false;
            // reset the whole freaking thing 
            item_loader.addClass('hidden');
            mainparent.find("span.alert").text(" ").addClass('hidden');
            parent.find("._fev-group ._group").addClass('hidden');
            parent.find("._fev-group ._default").removeClass('hidden');
            obj.attr('data-fev-lval',"");
            obj.attr('data-fev-state',"inactive");
            // obj.focus();
        }
        
    }

    // if the form is an edit form, make sure the edit value attribute
    // 'data-fev-elval' is not equal to the current value
    if(elval!=="" &&val!=="" &val==elval){
        runcheck=false;
        // reset the fields and make sure the state of the element is set to
        // 'done'
        item_loader.addClass('hidden');
        mainparent.find("span.alert").addClass('hidden');
        mainparent.find("span.alert q").text("");
        parent.find("._fev-group ._group").addClass('hidden');
        parent.find("._fev-group ._default").removeClass('hidden');
        obj.attr('data-fev-lval',"");
        obj.attr('data-fev-state',"done");
    }

    // reset the current 
    if(runcheck==true){
        if(((curstate=="inactive"||curstate=="")&&val!=="")||
            (curstate=="done"&&val!==""&&
                val!==obj.attr('data-fev-lval'))||
            (curstate=="failed"&&val!=="")){
            obj.attr('data-fev-state',"processing");
            
            // log all entries
            // console.log("value: ",val,"table: ",tbl,"field: ",col,"map: ",map);
            // send the json request to the display.php page abnd
            var url = '' + host_addr + 'snippets/display.php';
            var opts = {
                type: 'GET',
                url: url,
                data: {
                    displaytype: 'verifyemaildefault',
                    tablename: tbl,
                    email: val,
                    tablefield: col,
                    extradata: map,
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
                        // show icon
                        parent.find("._fev-group ._group").addClass('hidden')
                        parent.find("._fev-group .success").removeClass('hidden');

                        // set the 'lval' attribute
                        obj.attr('data-fev-lval',val);

                        // set the state of the form
                        obj.attr('data-fev-state',"done");
                        // hide the error alert section
                        mainparent.find("span.alert").addClass('hidden');
                    }else if(output.success == "false"){
                        // generate the errmsg output
                        if(mainparent.find("span._float").length>0){
                            mainparent.find("span._float").removeClass('hidden');
                            mainparent.find("span.alert q").text(errmsg);
                        }else{
                            var trg=mainparent.find("label");
                            $('<span class="alert _float"><i class="close"></i><q>'+errmsg+'</q></span>').insertAfter(trg);
                        }
                        parent.find("._fev-group ._group").addClass('hidden');
                        parent.find("._fev-group .failure").removeClass('hidden');
                        obj.attr('data-fev-lval',"");
                        obj.attr('data-fev-state',"failed");
                    }  
                },
                error: function(error) {
                    if (typeof (error) == "object") {
                        console.log(error.responseText);
                    }
                    var errmsg = "Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again";
                    // item_loader.remove();
                    item_loader.addClass('hidden');
                    // generate the errmsg output
                    if(mainparent.find("span._float").length>0){
                        // console.log("hidden removed");
                        mainparent.find("span.alert q").text(errmsg);
                        mainparent.find("span._float").removeClass('hidden');
                    }else{
                        var trg=mainparent.find("label");
                        $('<span class="alert _float"><i class="close"></i><q>'+errmsg+'</q></span>').insertAfter(trg);
                    }
                    parent.find("._fev-group ._group").addClass('hidden');
                    parent.find("._fev-group ._default").removeClass('hidden');
                    obj.attr('data-fev-state',"inactive");
                    obj.val("");
                    // item_loader.className += ' hidden';
                    // raiseMainModal('Failure!!', '' + errmsg + '', 'fail');
                    // alert("Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again ");

                }
            };
            $.ajax(opts);
        }else if(val==""){
            // reset the whole freaking thing 
            item_loader.addClass('hidden');
            mainparent.find("span.alert").addClass('hidden');
            mainparent.find("span.alert q").text("");
            parent.find("._fev-group ._group").addClass('hidden');
            parent.find("._fev-group ._default").removeClass('hidden');
            obj.attr('data-fev-lval',"");
            obj.attr('data-fev-state',"inactive");

        }
    }

}
$(document).on('blur','input[data-feverify]',function(){
    fieldValueVerification($(this));   
});
$(document).on('click','._float .close',function(){
    $(this).parent().addClass('hidden');   
});

// block unwanted text from tel fields
$(document).on("keypress","input[data-telvalidate=true]",function(e){
    var cval=$(this).val();
    var curstr=String.fromCharCode(e.keyCode || e.charCode);
    if(" 0123456789".indexOf(curstr) > -1){
        // console.log("valid",curstr);
        $(this).val(cval);
    }else{
        // console.log("invalid",curstr);
        cval=cval.replace(/[A-Za-z]{1,}/,"");
        $(this).val(cval);

    }
});
$(document).on("keyup","input[data-telvalidate=true]",function(){
    // console.log("Keyup");
    var cval=$(this).val();

    if(" 0123456789".indexOf(cval) == -1){
        cval=cval.replace(/[A-Za-z]{1,}/,"");
    }
    $(this).val(cval);
});

// init fdgen form field elements for data operations
$(document).on("change","form[data-fdgen=true] input[type=file]",function(){
    // run the fileSizeGen Function
    fileSizeGen($(this),"form[data-fdgen=true]","");
});

// generic function to produce file size for selected files and total their output

function fileSizeGen(input,type="",totalsizel,stypel="megabyte") {
    // generates files size
    // attaches filesize to associated element using the data-file-size attribute
    var results_arr=[];
    // console.log("Input 1:",input,"\n Input 2:",input[0].files[0],"Input 3:",$(input[0]));
    // check for the 
    if (input[0].files[0]) {
        // create reader instance for monitoring the choosen file from the 
        // input file field
        var reader = new FileReader();
        $(input[0]).attr("data-state", "loading");
        var targetElem = $(input[0]);
        var editelemdata = targetElem.attr("data-edit");
        var filesize=totalsizel;
        
        // console.log("Editdata: ",editelemdata," Section head:", editsectionhead," readurl: ",type);
        var csize=typeof input[0].files[0].size !=="undefined"?input[0].files[0].size:0;
        if(csize==0){

            reader.onload = function(e) {
                // data list is as follows
                /*
                  * e.total=total number of bytes
                  * e.target.result=total entry
                  *
                  *
                  *
                */
                // $(''+targetElementImg+'').attr('src', e.target.result).removeClass('hidden');
                name = $(input[0]).val();
                totalname = name.split('\\');
                // console.log('name: ',name,' splitname: ',totalname);
                name = totalname[totalname.length - 1];
                // console.log(e);
                bytesize = e.total;
                // this variable carries the data value for the selected file
                // for images, this value can be used to generate a preview
                result = e.target.result;
                var ftype = getExtension(targetElem.val());
                input.attr("data-file-size", bytesize);
                var totalsize = calculateTotalFileSize(curtotal);
                if(totalsizel!==""){
                    var curtotal = Math.floor(filesize.val());
                    curtotal += bytesize;
                    filesize.val(curtotal);
                }
                /*CONTROL Block FOR varying output formats*/
                
                    // recalculate total size
                    // deals only in MB
                    var cursize = '<span class="color-green">' + totalsize[stypel] + 'MB</span>';
                    if (totalsize['megabyte'] > 30) {
                        cursize = '<span class="color-red">' + totalsize[stypel] + 'MB</span>';
                    }
                    var totalsize = calculateTotalFileSize(bytesize);
                    results_arr['cursizekb']=totalsize['kilobyte'];
                    results_arr['cursizemb']=totalsize['megabyte'];
                    results_arr['cursizegb']=totalsize['gigabyte'];

                $(input[0]).attr("data-state", "loaded");

            }
        }else{
            bytesize=csize;
            var totalsize = calculateTotalFileSize(bytesize);
            results_arr['cursizekb']=totalsize['kilobyte'];
            results_arr['cursizemb']=totalsize['megabyte'];
            results_arr['cursizegb']=totalsize['gigabyte'];
        }
        reader.readAsDataURL(input[0].files[0]);
        $(input[0]).attr({"data-state":"loaded","data-file-size":bytesize});
        // console.log(bytesize,'in here');
    } else {
        
        $(input[0]).attr({"data-state":"loaded","data-file-size":"0"});
    }
    if(type=="fdgen"){
        /*if(input[0].form.find('span.fdgen-fsoutput').length>0){
            input[0].form.find('span.fdgen-fsoutput').html();
        }*/
    }
    return results_arr;
}




// for gmaps setup with several location:resources
function initializeGmap(lat,lng,data,multiple="",locations=[]) {
  // console.log(lat, lng, locations);
  var elid=data['elid']!==""&&typeof(data['elid'])!=="undefined"?data['elid']:"default_map";
  var zoom=data['zoom']!==""&&typeof(data['zoom'])!=="undefined"?Math.floor(data['zoom']):8;
  var zoomcontrol=data['zoomcontrol']!==""&&typeof(data['zoomcontrol'])!=="undefined"?data['zoomcontrol']:true;
  var defaultui=data['defaultui']!==""&&typeof(data['defaultui'])!=="undefined"?Boolean(data['defaultui']):true;
  var styles=data['styles']!==""&&typeof(data['styles'])!=="undefined"?data['styles']:"";
  var mtypeid=data['mtypeid']!==""&&typeof(data['mtypeid'])!=="undefined"?data['mtypeid']:google.maps.MapTypeId.ROADMAP;
  // data represents the multiple map locations
  if(lat!==""&&lng!==""){
    console.log(lat, lng, locations, zoom, zoomcontrol,styles,mtypeid,defaultui);
    var myOptions = {
      center: new google.maps.LatLng(lat, lng),
      zoom: zoom,
      disableDefaultUI: defaultui,
      zoomControl: zoomcontrol,
      styles: styles,
      mapTypeId: mtypeid

    }

    var map = new google.maps.Map(document.getElementById(elid),myOptions);
    // if(multiple=="yes"){
      // console.log(location[0][0]);
        setMarkers(map,locations);

    // }

  }

}

function setMarkers(map,locations){

  var marker, i;
  console.log(locations);
  for (i = 0; i < locations.length; i++){  

     var title = locations[i][0];
     var lat = locations[i][1];
     var lng = locations[i][2];
     var add =  locations[i][3];
     var con = locations[i][4];
     var icon = locations[i][5];
     // var clatlng
     latlngset = new google.maps.LatLng(lat, lng);

      var marker = new google.maps.Marker({  
              map: map, title: title , position: latlngset  
            });
            map.setCenter(marker.getPosition());

      var content = con;     

      var infowindow = new google.maps.InfoWindow({
        position: latlngset,
        icon: icon
      });

      google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
          return function() {
              closeInfos();
              infowindow.setContent(content);
              infowindow.open(map,marker);
              /* keep the handle, in order to close it on next click event */
             infos[0]=infowindow;
          };
      })(marker,content,infowindow));  

  }
}

function closeInfos(){

 if(infos.length > 0){

    /* detach the info-window from the marker ... undocumented in the API docs */
    infos[0].set("marker", null);

    /* and close it */
    infos[0].close();

    /* blank the array */
    infos.length = 0;
 }
}

// end gmaps


bytesize=0;
result="";
function readURLTwo(input,type) {
  // var results_arr=[];
  // console.log(input,input[0].files[0],$(input[0]));
  if (input[0].files[0]) {
    var reader = new FileReader();
    $(input[0]).attr("data-state","loading");
    var targetElem=$(input[0]);
    var editelemdata=targetElem.attr("data-edit");
    if(type=="napstanduserimgupload"||type=="napstanduserimgeditupload"){
      var targetelement=targetElem.parent().parent().parent().parent().parent().parent().parent();
      
    }else if(type=="napstanduserzipupload"||type=="napstanduserzipeditupload"){
      var targetelement=targetElem.parent().parent().parent().parent().parent().parent();
      
    }
    var targetparid=targetelement.attr("data-id");
    // console.log(targetelement,targetparid);
    if(targetparid!==""&&typeof(targetelement.attr("data-id"))!=="undefined"){
        targetparid='[data-id='+targetparid+']';
        targetparidmain='div'+targetparid+'';
    }else{
        targetparid="";
        targetparidmain="";
    }
    typeof(editelemdata)=="undefined"||editelemdata=="undefined"?editelemdata="":editelemdata=editelemdata;
    if(type=="napstanduserzipupload"||type=="napstanduserzipeditupload"){
      var filesize=editelemdata!=="edit"?$('input[name=zipfilesizeout]'):$(''+targetparidmain+' input[name=zipfilesizeoutedit]');
      var editsectionhead="div[data-name=upload-zip-section"+editelemdata+"]"+targetparid+" ";
      
      $(''+editsectionhead+'.entrymarker.zip p.total-size').html('<img src="'+host_addr+'images/waiting.gif" class="loadermini">')
      $(''+editsectionhead+'input[name=zipfilesizeout'+editelemdata+']').attr("data-state","loading");
    }else if (type=="napstanduserimgupload"||type=="napstanduserimgeditupload") {
      var filesize=editelemdata!=="edit"?$('input[name=filesizeout]'):$(''+targetparidmain+' input[name=filesizeoutedit]');
      var editsectionhead="div[data-name=upload-image-section"+editelemdata+"]"+targetparid+" ";

    }
    // console.log("Editdata: ",editelemdata," Section head:", editsectionhead," readurl: ",type);

    reader.onload = function(e) {
      // data list is as follows
      /*
      * e.total=total number of bytes
      * e.target.result=total entry
      *
      *
      *
      */
      // $(''+targetElementImg+'').attr('src', e.target.result).removeClass('hidden');
      name=$(input[0]).val();
      totalname=name.split('\\');
      // console.log('name: ',name,' splitname: ',totalname);
      name=totalname[totalname.length-1];
      // console.log(e);
      bytesize=e.total;
      result=e.target.result;
      /*CONTROL Block FOR varying output formats*/
      if(type=="napstanduserimgupload"||type=="napstanduserimgeditupload"){
        var divide=input.attr("name").split('e');
        var c=divide[1];
        
        $(''+editsectionhead+'.img_prev_hold.'+c+'').html('<img src="'+result+'"/>');
        input.attr("data-file-size",bytesize);
        var curtotal=Math.floor(filesize.val());
        curtotal+=bytesize;
        filesize.val(curtotal);
        // recalculate total size
        var totalsize=calculateTotalFileSize(curtotal);
        // deal only in MB
        var cursize='<span class="color-green">'+totalsize['megabyte']+'MB</span>';
        if(totalsize['megabyte']>30){
          cursize='<span class="color-red">'+totalsize['megabyte']+'MB</span>';
        }
        $(''+editsectionhead+'.entrymarker.images p.total-size').html(cursize);
        $(''+editsectionhead+'.entrymarker.images input[name=filesizeout'+editelemdata+']').attr("data-state","loaded");
      }else if(type=="napstanduserzipupload"||type=="napstanduserzipeditupload"){
        // input.attr("data-file-size",bytesize);
        
        var ftype=getExtension(targetElem.val());
        if(ftype['extension']=="zip"){    
          filesize.val(bytesize);
          // recalculate total size
          var totalsize=calculateTotalFileSize(bytesize);
          // deal only in MB
          var cursize='<span class="color-green">'+name+' : '+totalsize['megabyte']+'MB</span>';
          if(totalsize['megabyte']>30){
            cursize='<span class="color-red">'+name+' : '+totalsize['megabyte']+'MB TOO LARGE!!!</span>';
          }
          $(''+editsectionhead+'.entrymarker.zip p.total-size').html(cursize);
          $(''+editsectionhead+'.entrymarker.zip input[name=zipfilesizeout'+editelemdata+']').attr("data-state","loaded");
          
        }else{
          cursize='<span class="color-red">WRONG FILE TYPE : 0MB</span>';
          $(''+editsectionhead+'.entrymarker.zip p.total-size').html(cursize);
          $(''+editsectionhead+'.entrymarker.zip input[name=zipfilesizeout'+editelemdata+']').val("0").attr("data-state","loaded");
        }
      }

      $(input[0]).attr("data-state","loaded");

    }
    reader.readAsDataURL(input[0].files[0]);
    // console.log(bytesize,'in here');
  }else{
    if(type=="napstanduserimgupload"){

    }else if(type=="napstanduserzipupload"){
      $(''+editsectionhead+'.entrymarker.zip p.total-size').html('<span class="color-red">0MB</span>');
      $(''+editsectionhead+'.entrymarker.zip input[name=zipfilesizeout'+editelemdata+']').attr("data-state","loaded");
    }
    $(input[0]).attr("data-state","loaded");
  }
  // return results_arr;
}
function contentImageSelect(input){
    var targetElem=$(input);
    var divide=targetElem.attr("name").split('e');
    var c=divide[1];
    var editelemdata=targetElem.attr("data-edit");
    typeof(editelemdata)=="undefined"||editelemdata=="undefined"?editelemdata="":editelemdata=editelemdata;
    // console.log("c: ",c, input)
    var curfilesize=targetElem.attr('data-file-size');
    var filesize=editelemdata!=="edit"?$('input[name=filesizeout]'):$('input[name=filesizeoutedit]');
    var editsectionhead="div[data-name=upload-image-section] ";
    var editreadurl="napstanduserimgupload";
    if(editelemdata=="edit"){
      editsectionhead="div[data-name=upload-image-sectionedit] ";
      editreadurl="napstanduserimgeditupload";
    }
    // console.log("Editdata: ",editelemdata," Section head:", editsectionhead," readurl: ",editreadurl);

    if(typeof(curfilesize)!=="undefined"&&curfilesize!==""){
      var totalfilesize=filesize.val();
      var curtotalsize=totalfilesize-curfilesize;
      filesize.val(curtotalsize);
      // recalculate total size
      var totalsize=calculateTotalFileSize(curtotalsize);
      // deal only in MB
      var cursize='<span class="color-green">'+totalsize['megabyte']+'MB</span>';
      if(totalsize['megabyte']>30){
        cursize='<span class="color-red">'+totalsize['megabyte']+'MB</span>';
      }
      $(''+editsectionhead+'.entrymarker.images p.total-size').html(cursize);
      // console.log("contentmarker: ",$(''+editsectionhead+'.entrymarker.images p.total-size'));
      targetElem.attr('data-file-size','0')
    }
    if (targetElem.val()!=="") {
        var ftype=getExtension(targetElem.val());
        if(ftype['type']=="image"){  
            readURLTwo(targetElem,""+editreadurl+"");
            // console.log("src data: ",bytesize,filesrc);
        }else{
            alert("Please Upload an image, no other file format is accepted here");
        }   
    }else{
        $(''+editsectionhead+' .img_prev_hold.'+c+'').html("");

    }

}

function calculateTotalFileSize(bytecount){
    var fileout=[];
    // calculate for KB
    var kb_count=Math.ceil10(Math.floor(bytecount)/1024,-2);
    // Calculate for MB
    var mb_count=Math.ceil10(Math.floor(kb_count)/1024,-2);
    // calculate for GB
    var gb_count=Math.ceil10(Math.floor(mb_count)/1024,-2);
    fileout['kilobyte']=kb_count;
    fileout['megabyte']=mb_count;
    fileout['gigabyte']=gb_count;
    return fileout;
}


function sortThroughSplitPoints() {
    // get all the split point markers
    var markerp = $('div.split-point-bottom, div.split-point-top');
    // console.log('Running Sort');
    // get all indent type markers
    var markeri = $('div.split-indent-bottom, div.split-indent-top');
    // console.log("Markermain: ",markerp);
    // run a for loop to get each marker, obtain its parent, then get the details for
    // the border pointer/indentation pixel parameter
    var mpl=markerp.length;
    for (var i = 0; i < mpl; i++) {
        var curmarker = markerp.get(i);
        var curmarkerp = markerp.get(i).parentElement;
        var maxw = curmarkerp.clientWidth;
        var maxh = curmarkerp.clientHeight;

        // check to see if there are spaces in the classname and get
        // the first content of the class as it would be the split marker
        var spacecheck = markerp.get(i).className.split(" ");
        var fullcheck = spacecheck[0];
        // get the type of point
        var pointcheck = fullcheck.split("-");
        var lpoint = pointcheck[pointcheck.length - 1];
        var btlr = Math.floor(parseInt(maxw) / 2);
        if (lpoint == "bottom" || lpoint == "top") {
            // markerp.get(i).css({"border-left-width":btlr+"px","border-right-width":btlr+"px"});
            markerp.get(i).style.borderLeftWidth = btlr + "px";
            markerp.get(i).style.borderRightWidth = btlr + "px";
        }
        // calculate the current left and right dimensions for top and bottom
        if (i == 0) {// console.log("Marker: ", markerp.get(i),"\n Parent el: ", curmarkerp.clientWidth," \n markerclass: ",markerp.get(i).className);
        }
    }
    var mil=markeri.length
    for (var i = 0; i < mil; i++) {
        var curmarker = markeri.get(i);
        var curmarkeri = markeri.get(i).parentElement;
        var maxw = curmarkeri.clientWidth;
        var maxh = curmarkeri.clientHeight;

        // check to see if there are spaces in the classname and get
        // the first content of the class as it would be the split marker
        var spacecheck = markeri.get(i).className.split(" ");
        var fullcheck = spacecheck[0];
        // get the type of point
        var pointcheck = fullcheck.split("-");
        var lpoint = pointcheck[pointcheck.length - 1];
        var btlr = Math.floor(parseInt(maxw) / 2);
        // btlr=maxw<=480?btlr+12:btlr;
        if (lpoint == "bottom" || lpoint == "top") {
            // markeri.get(i).css({"border-left-width":btlr+"px","border-right-width":btlr+"px"});
            markeri.get(i).style.borderLeftWidth = btlr + "px";
            markeri.get(i).style.borderRightWidth = btlr + "px";
        }
        // calculate the current left and right dimensions for top and bottom
        if (i == 0) {// console.log("Marker: ", markeri.get(i),"\n Parent el: ", curmarkeri.clientWidth," \n markerclass: ",markeri.get(i).className);
        }
    }

}




/**
    *   Create form fields in multiple element groups in repeating format
    *   This is a part of the groupset handler functions
    *   
    *   @param string&JQuery Selector 'element' represents the element that triggers the  
    *   generation/removal of extra fields in the form
    *
    *   @param string&JQuery selector 'entryel' represents the field element that determines
    *   where new generated content will be posted to, either before, or after it, this is
    *   optional
    *
    *   @param selector 'groupparent' this represents the parent element holding the current
    *   group set of elements. the values defaults to the parent of the 'element' selector
    *
    *   @param int 'curcountel' this represents the current amount of entries in the parent
    *   container element 'groupparent'
    *
    *   @param int 'curnextcount' is the new total amount of entries expected in the parent
    *   element container, the value is either more than or less than 'cucountel' and depend
    *   -ing on the value an increment or decrement in the number of entries will occur. 
    *
    *   @param selector 'countel' is the form element responsible for storing the current 
    *   amount of sets within the parent container, this value can be used to sort through
    *   the group upon submission of the form for processing.
    *
    *   refer to $HOST/snippets/gdsdocs.php group set entries section for usage sample    
*/
function multipleElGenerator(element, entryel="", groupparent="", curcountel=0, curnextcount=0, countel="") {
    // element = selector for the clickable element to trigger the generation
    // entryel = selector for the entry field dummy element where new form content
    // are posted into
    // group parent = the parent element for the multiple entry elements
    var podtype = "";
    var eldiff = 0;
    var formname = $('' + element + '').attr("data-form-name");
    var curname = $('' + element + '').attr("data-name");
    var codata = curname.split("_addlink");
    var countername = codata[0];
    var coredata = countername.split("count");
    var corename = coredata[0];
    var insertiontype = $('' + element + '').attr("data-i-type");
    
    var maintotalscripts="";

    // check if the funcdata input element exists in the current group
    var funcdatamap=$('div.' + corename + '-field-hold').find('input[name='+corename+'funcdata]');
    // console.log("funcdata map: ",funcdatamap);
    
    if(funcdatamap.length>0 && funcdatamap.val()!==""){
        if(typeof JSON.parse=="function"){
            var curfdmap=JSON.parse(funcdatamap.val().replace(/'{1,}/g,'"'));
        }else{
            var curfdmap=eval(funcdatamap.val().replace(/'{1,}/g,'"'));
        }

        var curfdmapout=parseDataFunc(curfdmap);
        // console.log("funcdata map: ",curfdmap, " curdmap: ",curfdmapout);
        // get the delete/destroy scripts if available
        if(curfdmapout['doutput']!==""){

            if($('div.' + corename + '-field-hold script[data-name=multiscript_gd').length>0){
                $('div.' + corename + '-field-hold script[data-name=multiscript_gd').remove();
            }
            // $('<script data-name="multiscript_gd">'+curfdmapout['doutput']+'</script>').appendTo('div.' + corename + '-field-hold');
                
            
        }
        // get the script equivalent
        maintotalscripts='<script data-name="multiscript_gd">$(document).ready(function(){'+curfdmapout['output']+'});</script>';
    }

    // console.log("insertiontype: ",insertiontype, typeof(insertiontype));
    if (typeof (insertiontype) == "undefined" || insertiontype == null || !insertiontype || insertiontype == "" || insertiontype == "undefined") {
        var insertiontype = "default";
    }
    if (parseInt(curcountel) > 0 && parseInt(curnextcount) > 0) {
        if (parseInt(curcountel) > parseInt(curnextcount)) {
            // decrement
            podtype = "decrement";
            eldiff = parseInt(curcountel) - parseInt(curnextcount);
        } else if (parseInt(curcountel) < parseInt(curnextcount)) {
            // increment
            podtype = "increment";
            eldiff = parseInt(curnextcount) - parseInt(curcountel);
        }
        // console.log("Podtype: ",podtype," \n eldiff: ",eldiff," \n curcountel: ",curcountel," \n curnextcount: ",curnextcount);
    }
    if (parseInt(curcountel) == parseInt(curnextcount) && groupparent !== "") {
        podtype = "noadd";
        var errmsg = "Exact field amount has been displayed, no change detected";
        raiseMainModal('Form error!!', '' + errmsg + '', 'fail');
        if (countel !== "") {
            $("#mainPageModal").on("hidden.bs.modal", function() {
                $('' + countel + '').addClass('error-class').focus();
            });
        }
    }
    // make sure that the next count is not a negative or neutral number 
    if(curnextcount>0){
        if (podtype == "" || podtype == "increment") {
            var sentineltype = $('' + element + '').attr("data-sentineltype");
            // console.log("sentineltype: ",sentineltype, typeof(sentineltype));
            if (typeof (sentineltype) == "undefined" || sentineltype == null || !sentineltype || sentineltype == "" || sentineltype == "undefined") {
                var sentineltype = "";
            }

            var mainparent = groupparent !== "" ? $('' + groupparent + '') : $('' + element + '').parent();
            var shadowlimit = 0;
            if (mainparent.find('input[name=' + countername + ']').length > 0) {
                var branchcount = mainparent.find('input[name=' + countername + ']').val();
            } else {
                var branchcount = curcountel;
            }
            // get the entry point div
            var entrypoint = mainparent.find('[name=' + corename + 'entrypoint][data-marker=true]');
            // console.log(entrypoint);
            // get the limit to the entries
            var branchlimit = $('' + element + '').attr("data-limit");
            var doentry = "true";

            if (typeof (branchlimit) == "undefined") {
                branchlimit = "";
            } else {
                branchlimit = Math.floor(branchlimit);
            }

            var eltotalgroup = "";

            if (podtype == "") {
                // console.log("After modify insertiontype: ",insertiontype, typeof(insertiontype));

                // console.log("branchlimit - ",branchlimit);
                var nextcounttrue = 0;
                var nextcount = curnextcount == "" || curnextcount == 0 ? Math.floor(branchcount) + 1 : curnextcount;
                if (sentineltype !== "" && Math.floor(sentineltype) > 0) {
                    nextcounttrue = Math.floor(nextcount) - Math.floor(sentineltype);
                } else {
                    nextcounttrue = nextcount;
                }
                if (isNumber(branchlimit) && branchlimit > 0) {
                    if (nextcounttrue <= Math.floor(branchlimit)) {
                        if(typeof curfdmapout!=="undefined"){
                            $('<script data-name="multiscript_gd">'+curfdmapout['doutput']+'</script>').appendTo('form[name='+formname+'] div.' + corename + '-field-hold');
                        }
                    }
                }
                // console.log("nextcounttrue - ",nextcounttrue," branchlimit - ",branchlimit);
                // get the base element and clone it
                var elgroup = groupparent !== "" ? $('' + groupparent + '').find('[data-type=triggerprogenitor]').clone(true) : $('' + element + '').parent().find('[data-type=triggerprogenitor]').clone(true);
                // console.log(elgroup);
                // reset the values for all form content within the fields
                elgroup.find('input[type!=hidden]').val("");
                elgroup.find('[data-hdeftype=hidden]').addClass("hidden");
                // console.log(elgroup.find('input[type!=hidden]'));
                elgroup.find('select').val("");
                elgroup.find('textarea').val("");

                // check for tinymce elements in the elgroup and proceed to augment them as appropriate
                var mceelements = elgroup.find('[data-type=tinymcefield]');

                // runa for loop on the mce elements to process them for the new content
                // this array holds the new set of ids for tinymce initialization
                var strinnerset = [];
                // these arrays carry the config for new toolbar components in tinymce initialization
                var mceoptions = [];
                var domce = "";
                var cmcecount = 0;
                if (mceelements.length > 0) {

                    domce = "true";
                    cmcecount = mceelements.length;
                    // remove all tiny mce cloned content
                    elgroup.find('div.mce-container').remove();
                    for (var i = 0; i < cmcecount; i++) {
                        // convert to multidimensional array
                        mceoptions[i] = [];
                        var curparent = "";
                        curparent = elgroup.find('[data-type=tinymcefield]').parent()[i];

                        // console.log("Cur match set - ",elgroup.find('[data-type=tinymcefield]').parent().find('input[type=hidden][name=width][data-type=tinymce]'),"Element Parent - ", curparent," Element Parent with JQ - ",$(curparent));
                        var curelem = mceelements[i];
                        // var tpinner=tp[0].innerText;
                        // get the current id
                        var curid = curelem.getAttribute("id");
                        var nxtid = '' + curid + '' + nextcount + '';
                        // change the element id to match the new one
                        curelem.removeAttribute("style");
                        curelem.removeAttribute("aria-hidden");
                        curelem.setAttribute("id", nxtid);

                        var nxtstrfunc = 'textarea#' + curid + '' + nextcount + '';
                        strinnerset[i] = nxtstrfunc;

                        // get the tinymce options if they exist, they are in hidden elements
                        // with the data-type attribute of tinymce and name associated with their
                        // settings
                        var wt = "";
                        wt = curparent.querySelector('input[type=hidden][name=width][data-type=tinymce]');
                        if (wt === null || wt === undefined || wt === NaN) {
                            wt = "";
                        }
                        mceoptions[i]['width'] = wt !== "" && typeof wt !== "undefined" ? wt.value : "";
                        // console.log("width test 1");

                        var tb1 = "";
                        tb1 = curparent.querySelector('input[type=hidden][name=toolbar1][data-type=tinymce]');
                        if (tb1 === null || tb1 === undefined || tb1 === NaN) {
                            tb1 = "";
                        }
                        mceoptions[i]['toolbar1'] = tb1 !== "" && typeof tb1 !== "undefined" ? tb1.value : "";

                        var tb2 = "";
                        tb2 = curparent.querySelector('input[type=hidden][name=toolbar2][data-type=tinymce]');
                        if (tb2 === null || tb2 === undefined || tb2 === NaN) {
                            tb2 = "";
                        }
                        mceoptions[i]['toolbar2'] = tb2 !== "" && typeof tb2 !== "undefined" ? tb2.value : "";
                        var ht = "";
                        ht = curparent.querySelector('input[type=hidden][name=height][data-type=tinymce]');
                        if (ht === null || ht === undefined || ht === NaN) {
                            ht = "";
                        }
                        mceoptions[i]['height'] = ht !== "" && typeof ht !== "undefined" ? ht.value : "";

                        var th = "";
                        th = curparent.querySelector('input[type=hidden][name=theme][data-type=tinymce]');
                        if (th === null || th === undefined || th === NaN) {
                            th = "";
                        }
                        mceoptions[i]['theme'] = th !== "" && typeof th !== "undefined" ? th.value : "";

                        var fmt = "";
                        fmt = curparent.querySelector('input[type=hidden][name=filemanagertitle][data-type=tinymce]');
                        if (fmt === null || fmt === undefined || fmt === NaN) {
                            fmt = "";
                        }
                        mceoptions[i]['filemanagertitle'] = fmt !== "" && typeof fmt !== "undefined" ? fmt.value : "";

                    }
                    ;
                }
                // console.log("script elements - ", scriptelements);
                // console.log("Real group first- ", elgroup," Parent element", $(''+element+'').parent()," corename - ",corename);        

                // remove any progenitor details from the cloned element

                var cid = groupparent !== "" ? $('' + groupparent + '').find('div[data-type=triggerprogenitor]').attr("data-cid") : $('' + element + '').parent().find('div[data-type=triggerprogenitor]').attr("data-cid");
                elgroup.removeAttr("data-cid");
                elgroup.attr("data-type", "triggerprogeny");

                var hlabeltext = elgroup.find('.multi_content_countlabels').html();

                // console.log("Real group - ", elgroup," ",elgroup.find('.multi_content_countlabels'));

                // change the h4 label content if necessary to reflect new content present
                hlabeltext = hlabeltext.replace('(Entry ' + cid + ')', '(Entry ' + nextcount + ')');

                elgroup.find('.multi_content_countlabels').html(hlabeltext);
                // get element map for form element name manipulation
                var cmap = mainparent.find('input[name=' + corename + 'datamap]');
                // console.log("cmap: ",cmap);
                var efdstepone = cmap.val().split("<|>");
                var efdl=efdstepone.length;
                for (var i = 0; i < efdl; i++) {
                    if (efdstepone[i] !== "") {
                        var curdata = efdstepone[i].replace(/[\n\r]*/g, "").replace(/\s{1,}/g, '').split("-:-");
                        var fieldname = curdata[0];
                        var fieldtype = curdata[1];
                        // console.log("curfieldname - ", ''+fieldname+''+cid+''," curfieldtype - ", ''+fieldtype+'');
                        // run through the clone set and replace every instance of the current
                        // element set with their new values
                        // you can also change other values as well
                        if (fieldname !== "" && fieldtype !== "") {
                            elgroup.find('' + fieldtype + '[name=' + fieldname + '' + cid + ']').attr("name", '' + fieldname + '' + nextcount + '');
                        }
                    }
                }
                ;// console.log("elgroup modified: ",elgroup);
                if (isNumber(branchlimit) && branchlimit > 0) {
                    if (nextcounttrue <= Math.floor(branchlimit)) {
                        doentry = "true";
                        // update the library scripts for reinitializing the content
                        // that are tied to said libraries
                        if(maintotalscripts!==""){
                            elgroup.append(maintotalscripts);
                        }
                    } else {
                        doentry = "false";
                        window.alert("Maximum allowed entries reached");
                    }
                }
                if (doentry == "true") {
                    if (insertiontype == "default" || insertiontype == "before") {
                        $(elgroup).insertBefore(entrypoint);
                    } else if (insertiontype == "after") {
                        $(elgroup).insertAfter(entrypoint);

                    }
                    //selection.appendTo(outs);
                    mainparent.find('input[name=' + countername + ']').val('' + nextcount + '');
                    // for tinymce init
                    if (domce == "true") {
                        for (var i = 0; i < cmcecount; i++) {
                            callTinyMCEInit(strinnerset[i], mceoptions[i]);
                        }
                        ;
                    }
                }
                // var nextcountout=nextcount-3;
                // var nextcountmain=nextcount-1;
            } else if (podtype == "increment") {
                var cid = groupparent !== "" ? $('' + groupparent + '').find('div[data-type=triggerprogenitor]').attr("data-cid") : $('' + element + '').parent().find('div[data-type=triggerprogenitor]').attr("data-cid");
                for (var ti = curcountel + 1; ti <= curnextcount; ti++) {
                    // console.log("Curcount", ti);
                    var nextcounttrue = 0;
                    var nextcount = ti;
                    if (sentineltype !== "" && Math.floor(sentineltype) > 0) {
                        nextcounttrue = Math.floor(nextcount) - Math.floor(sentineltype);
                    } else {
                        nextcounttrue = nextcount;
                    }
                    if (isNumber(branchlimit) && branchlimit > 0) {
                        if (nextcounttrue <= Math.floor(branchlimit)) {
                            if(typeof curfdmapout!=="undefined"){
                                $('<script data-name="multiscript_gd">'+curfdmapout['doutput']+'</script>').appendTo('form[name='+formname+'] div.' + corename + '-field-hold');
                            }
                        }
                    }
                    
                    var elgroup = groupparent !== "" ? $('' + groupparent + '').find('[data-type=triggerprogenitor]').clone(true) : $('' + element + '').parent().find('[data-type=triggerprogenitor]').clone(true);
                    // console.log("nextcounttrue - ",nextcounttrue," branchlimit - ",branchlimit);
                    // get the base element and clone it
                    // console.log(elgroup);
                    // reset the values for all form content within the fields
                    elgroup.find('input[type!=hidden]').val("");
                    elgroup.find('[data-hdeftype=hidden]').addClass("hidden");
                    // console.log(elgroup.find('input[type!=hidden]'));
                    elgroup.find('select').val("");
                    elgroup.find('textarea').val("");

                    // check for tinymce elements in the elgroup and proceed to augment them as appropriate
                    var mceelements = elgroup.find('[data-type=tinymcefield]');

                    // runa for loop on the mce elements to process them for the new content
                    // this array holds the new set of ids for tinymce initialization
                    var strinnerset = [];
                    // these arrays carry the config for new toolbar components in tinymce initialization
                    var mceoptions = [];
                    var domce = "";
                    var cmcecount = 0;
                    if (mceelements.length > 0) {

                        domce = "true";
                        cmcecount = mceelements.length;
                        // remove all tiny mce cloned content
                        elgroup.find('div.mce-container').remove();
                        for (var i = 0; i < cmcecount; i++) {
                            // convert to multidimensional array
                            mceoptions[i] = [];
                            var curparent = "";
                            curparent = elgroup.find('[data-type=tinymcefield]').parent()[i];

                            // console.log("Cur match set - ",elgroup.find('[data-type=tinymcefield]').parent().find('input[type=hidden][name=width][data-type=tinymce]'),"Element Parent - ", curparent," Element Parent with JQ - ",$(curparent));
                            var curelem = mceelements[i];
                            // var tpinner=tp[0].innerText;
                            // get the current id
                            var curid = curelem.getAttribute("id");
                            var nxtid = '' + curid + '' + nextcount + '';
                            // change the element id to match the new one
                            curelem.removeAttribute("style");
                            curelem.removeAttribute("aria-hidden");
                            curelem.setAttribute("id", nxtid);

                            var nxtstrfunc = 'textarea#' + curid + '' + nextcount + '';
                            strinnerset[i] = nxtstrfunc;

                            // get the tinymce options if they exist, they are in hidden elements
                            // with the data-type attribute of tinymce and name associated with their
                            // settings
                            var wt = "";
                            wt = curparent.querySelector('input[type=hidden][name=width][data-type=tinymce]');
                            if (wt === null || wt === undefined || wt === NaN) {
                                wt = "";
                            }
                            mceoptions[i]['width'] = wt !== "" && typeof wt !== "undefined" ? wt.value : "";
                            // console.log("width test 1");

                            var tb1 = "";
                            tb1 = curparent.querySelector('input[type=hidden][name=toolbar1][data-type=tinymce]');
                            if (tb1 === null || tb1 === undefined || tb1 === NaN) {
                                tb1 = "";
                            }
                            mceoptions[i]['toolbar1'] = tb1 !== "" && typeof tb1 !== "undefined" ? tb1.value : "";

                            var tb2 = "";
                            tb2 = curparent.querySelector('input[type=hidden][name=toolbar2][data-type=tinymce]');
                            if (tb2 === null || tb2 === undefined || tb2 === NaN) {
                                tb2 = "";
                            }
                            mceoptions[i]['toolbar2'] = tb2 !== "" && typeof tb2 !== "undefined" ? tb2.value : "";
                            var ht = "";
                            ht = curparent.querySelector('input[type=hidden][name=height][data-type=tinymce]');
                            if (ht === null || ht === undefined || ht === NaN) {
                                ht = "";
                            }
                            mceoptions[i]['height'] = ht !== "" && typeof ht !== "undefined" ? ht.value : "";

                            var th = "";
                            th = curparent.querySelector('input[type=hidden][name=theme][data-type=tinymce]');
                            if (th === null || th === undefined || th === NaN) {
                                th = "";
                            }
                            mceoptions[i]['theme'] = th !== "" && typeof th !== "undefined" ? th.value : "";

                            var fmt = "";
                            fmt = curparent.querySelector('input[type=hidden][name=filemanagertitle][data-type=tinymce]');
                            if (fmt === null || fmt === undefined || fmt === NaN) {
                                fmt = "";
                            }
                            mceoptions[i]['filemanagertitle'] = fmt !== "" && typeof fmt !== "undefined" ? fmt.value : "";

                        }
                        ;
                    }
                    // console.log("script elements - ", scriptelements);
                    // console.log("Real group first- ", elgroup," Parent element", $(''+element+'').parent()," corename - ",corename);        

                    // remove any progenitor details from the cloned element

                    elgroup.removeAttr("data-cid");
                    elgroup.attr("data-type", "triggerprogeny");

                    var hlabeltext = elgroup.find('.multi_content_countlabels').html();

                    // console.log("Real group - ", elgroup," ",elgroup.find('.multi_content_countlabels'));

                    // change the h4 label content if necessary to reflect new content present
                    hlabeltext = hlabeltext.replace('(Entry ' + cid + ')', '(Entry ' + ti + ')');

                    elgroup.find('.multi_content_countlabels').html(hlabeltext);
                    // get element map for form element name manipulation
                    var cmap = mainparent.find('input[name=' + corename + 'datamap]');
                    // console.log("cmap: ",cmap);
                    var efdstepone = cmap.val().split("<|>");
                    var efdl=efdstepone.length;
                    for (var i = 0; i < efdl; i++) {
                        if (efdstepone[i] !== "") {
                            var curdata = efdstepone[i].replace(/[\n\r]*/g, "").replace(/\s{1,}/g, '').split("-:-");
                            var fieldname = curdata[0];
                            var fieldtype = curdata[1];
                            // console.log("curfieldname - ", ''+fieldname+''+cid+''," curfieldtype - ", ''+fieldtype+'');
                            // run through the clone set and replace every instance of the current
                            // element set with their new values
                            // you can also change other values as well
                            if (fieldname !== "" && fieldtype !== "") {
                                elgroup.find('' + fieldtype + '[name=' + fieldname + '' + cid + ']').attr("name", '' + fieldname + '' + nextcount + '');
                            }
                        }
                    };
                    if (isNumber(branchlimit) && branchlimit > 0) {
                        if (nextcounttrue <= Math.floor(branchlimit)) {
                            if(maintotalscripts!==""){
                                elgroup.append(maintotalscripts);
                            }
                            doentry = "true";
                        } else {
                            doentry = "false";
                            window.alert("Maximum allowed entries reached");
                        }
                    }
                    if (doentry == "true") {
                        if (insertiontype == "default" || insertiontype == "before") {
                            $(elgroup).insertBefore(entrypoint);
                        } else if (insertiontype == "after") {
                            $(elgroup).insertAfter(entrypoint);

                        }
                        //selection.appendTo(outs);
                        mainparent.find('input[name=' + countername + ']').val('' + nextcount + '');
                        // for tinymce init
                        if (domce == "true") {
                            for (var i = 0; i < cmcecount; i++) {
                                callTinyMCEInit(strinnerset[i], mceoptions[i]);
                            }
                            ;
                        }
                    }
                }
                // reset the content of the output variable
            }
            // console.log(eltotalgroup);

            // console.log("elgroup modified: ",elgroup);

            // console.log("Nextcount: ",nextcount," nextcounttrue - ", nextcounttrue," entrypoint - ", entrypoint);

        } else if (podtype == "decrement") {
            // remove previous entries
            var gc = $('' + groupparent + ' .multi_content_hold');
            console.log(gc);
            for (var i = gc.length - 1; i > curnextcount - 1; i--) {
                console.log("Curcount: ", i, " Current entries: ", $('' + groupparent + ' .multi_content_hold')[i]);
                if (typeof ($('' + groupparent + ' .multi_content_hold')[i]) !== "undefined") {
                    $('' + groupparent + ' .multi_content_hold')[i].remove();

                }
            }
        }
    }
}

// for general form addition
$(document).on("click", "a[data-type=triggerformaddlib]", function() {
    var formname = $(this).attr("data-form-name");
    var curname = $(this).attr("data-name");
    var codata = curname.split("_addlink");
    var countername = codata[0];
    var coredata = countername.split("count");
    var corename = coredata[0];
    var insertiontype = $(this).attr("data-i-type");
    // console.log("insertiontype: ",insertiontype, typeof(insertiontype));
    if (typeof (insertiontype) == "undefined" || insertiontype == null || !insertiontype || insertiontype == "" || insertiontype == "undefined") {
        var insertiontype = "default";
    }

    var sentineltype = $(this).attr("data-sentineltype");
    // console.log("sentineltype: ",sentineltype, typeof(sentineltype));
    if (typeof (sentineltype) == "undefined" || sentineltype == null || !sentineltype || sentineltype == "" || sentineltype == "undefined") {
        var sentineltype = "";
    }
    // console.log("After modify insertiontype: ",insertiontype, typeof(insertiontype));

    var mainparent = $(this).parent();
    var maintotalscripts="";
    
    // check if the funcdata input element exists in the current group
    var funcdatamap=$('div.' + corename + '-field-hold').find('input[name='+corename+'funcdata]');
    // console.log("funcdata map: ",funcdatamap);
    
    if(funcdatamap.length>0 && funcdatamap.val()!==""){
        if(typeof JSON.parse=="function"){
            var curfdmap=JSON.parse(funcdatamap.val().replace(/'{1,}/g,'"'));
        }else{
            var curfdmap=eval(funcdatamap.val().replace(/'{1,}/g,'"'));
        }

        var curfdmapout=parseDataFunc(curfdmap);
        // console.log("funcdata map: ",curfdmap, " curdmap: ",curfdmapout);
        // get the delete/destroy scripts if available
        if(typeof curfdmapout!=="undefined"){
            if($('form[name='+formname+'] div.' + corename + '-field-hold script[data-name=multiscript_gd').length>0){
                $('form[name='+formname+'] div.' + corename + '-field-hold script[data-name=multiscript_gd').remove();
            }
            
        }
        // get the script equivalent
        maintotalscripts='<script data-name="multiscript_gd">$(document).ready(function(){'+curfdmapout['output']+'});</script>';
    }
    var branchcount = mainparent.find('input[name=' + countername + ']').val();
    // console.log("BranchCount: ",mainparent.find('input[name=' + countername + ']'));
    // get the entry point div
    var entrypoint = mainparent.find('[name=' + corename + 'entrypoint][data-marker=true]');
    // console.log(entrypoint);
    // get the limit to the entries
    var branchlimit = $(this).attr("data-limit");
    if (typeof (branchlimit) == "undefined") {
        branchlimit = "";
    } else {
        branchlimit = Math.floor(branchlimit);
    }

    // console.log("branchlimit - ",branchlimit);
    var nextcounttrue = 0;
    var nextcount = Math.floor(branchcount) + 1;
    if (sentineltype !== "" && Math.floor(sentineltype) > 0) {
        nextcounttrue = Math.floor(nextcount) - Math.floor(sentineltype);
    } else {
        nextcounttrue = nextcount;
    }
    if (isNumber(branchlimit) && branchlimit > 0) {
        if (nextcounttrue <= Math.floor(branchlimit)) {
            if(typeof curfdmapout!=="undefined"){
                $('<script data-name="multiscript_gd">'+curfdmapout['doutput']+'</script>').appendTo('form[name='+formname+'] div.' + corename + '-field-hold');
                
            }
        }
    }
    // console.log("nextcounttrue - ",nextcounttrue," branchlimit - ",branchlimit);
    // get the base element and clone it
    
    var elgroup = $(this).parent().find('div[data-type=triggerprogenitor]').clone(true);
    // destruction block for certain js libraries
    elgroup.children('select').select2("destroy");
    // console.log(elgroup);
    // reset the values for all form content within the fields
    elgroup.find('input[type!=hidden]').val("");
    // console.log(elgroup.find('input[type!=hidden]'));
    elgroup.find('select').val("");
    elgroup.find('textarea').val("");

    // check for tinymce elements in the elgroup and proceed to augment them as appropriate
    var mceelements = elgroup.find('[data-type=tinymcefield]');

    // run a for loop on the mce elements to process them for the new content
    // this array holds the new set of ids for tinymce initialization
    var strinnerset = [];
    // these arrays carry the config for new toolbar components in tinymce initialization
    var mceoptions = [];
    var domce = "";
    var cmcecount = 0;
    if (mceelements.length > 0) {

        domce = "true";
        cmcecount = mceelements.length;
        // remove all tiny mce cloned content
        elgroup.find('div.mce-container').remove();
        for (var i = 0; i < cmcecount; i++) {
            // convert to multidimensional array
            mceoptions[i] = [];
            var curparent = "";
            curparent = elgroup.find('[data-type=tinymcefield]').parent()[i];

            // console.log("Cur match set - ",elgroup.find('[data-type=tinymcefield]').parent().find('input[type=hidden][name=width][data-type=tinymce]'),"Element Parent - ", curparent," Element Parent with JQ - ",$(curparent));
            var curelem = mceelements[i];
            // var tpinner=tp[0].innerText;
            // get the current id
            var curid = curelem.getAttribute("id");
            var nxtid = '' + curid + '' + nextcount + '';
            // change the element id to match the new one
            curelem.removeAttribute("style");
            curelem.removeAttribute("aria-hidden");
            curelem.setAttribute("id", nxtid);

            var nxtstrfunc = 'textarea#' + curid + '' + nextcount + '';
            strinnerset[i] = nxtstrfunc;

            // get the tinymce options if they exist, they are in hidden elements
            // with the data-type attribute of tinymce and name associated with their
            // settings
            var wt = "";
            wt = curparent.querySelector('input[type=hidden][name=width][data-type=tinymce]');
            if (wt === null || wt === undefined || wt === NaN) {
                wt = "";
            }
            mceoptions[i]['width'] = wt !== "" && typeof wt !== "undefined" ? wt.value : "";
            // console.log("width test 1");

            var tb1 = "";
            tb1 = curparent.querySelector('input[type=hidden][name=toolbar1][data-type=tinymce]');
            if (tb1 === null || tb1 === undefined || tb1 === NaN) {
                tb1 = "";
            }
            mceoptions[i]['toolbar1'] = tb1 !== "" && typeof tb1 !== "undefined" ? tb1.value : "";

            var tb2 = "";
            tb2 = curparent.querySelector('input[type=hidden][name=toolbar2][data-type=tinymce]');
            if (tb2 === null || tb2 === undefined || tb2 === NaN) {
                tb2 = "";
            }
            mceoptions[i]['toolbar2'] = tb2 !== "" && typeof tb2 !== "undefined" ? tb2.value : "";
            var ht = "";
            ht = curparent.querySelector('input[type=hidden][name=height][data-type=tinymce]');
            if (ht === null || ht === undefined || ht === NaN) {
                ht = "";
            }
            mceoptions[i]['height'] = ht !== "" && typeof ht !== "undefined" ? ht.value : "";

            var th = "";
            th = curparent.querySelector('input[type=hidden][name=theme][data-type=tinymce]');
            if (th === null || th === undefined || th === NaN) {
                th = "";
            }
            mceoptions[i]['theme'] = th !== "" && typeof th !== "undefined" ? th.value : "";

            var fmt = "";
            fmt = curparent.querySelector('input[type=hidden][name=filemanagertitle][data-type=tinymce]');
            if (fmt === null || fmt === undefined || fmt === NaN) {
                fmt = "";
            }
            mceoptions[i]['filemanagertitle'] = fmt !== "" && typeof fmt !== "undefined" ? fmt.value : "";

        }
        ;
    }
    // console.log("script elements - ", scriptelements);
    // console.log("Real group first- ", elgroup," Parent element", $(this).parent()," corename - ",corename);        

    // remove any progenitor details from the cloned element
    var cid = $(this).parent().find('div[data-type=triggerprogenitor]').attr("data-cid");
    elgroup.removeAttr("data-cid");
    elgroup.attr("data-type", "triggerprogeny");

    var hlabeltext = elgroup.find('.multi_content_countlabels').html();
    // console.log(hlabeltext);
    // console.log("Real group - ", elgroup," ",elgroup.find('.multi_content_countlabels'));

    // change the h4 label content if necessary to reflect new content present

    hlabeltext = hlabeltext.replace('(Entry ' + cid + ')', '(Entry ' + nextcount + ')');

    elgroup.find('.multi_content_countlabels').html(hlabeltext);
    // get element map for form element name manipulation
    var cmap = mainparent.find('input[name=' + corename + 'datamap]');
    // console.log("cmap: ",cmap);
    var efdstepone = cmap.val().split("<|>");
    var efdsteponel=efdstepone.length;
    for (var i = 0; i < efdsteponel; i++) {
        if (efdstepone[i] !== "") {
            var curdata = efdstepone[i].replace(/[\n\r]*/g, "").replace(/\s{1,}/g, '').split("-:-");
            var fieldname = curdata[0];
            var fieldtype = curdata[1];
            // console.log("curfieldname - ", ''+fieldname+''+cid+''," curfieldtype - ", ''+fieldtype+'');
            // run through the clone set and replace every instance of the current
            // element set with their new values
            // you can also change other values as well
            if (fieldname !== "" && fieldtype !== "") {
                elgroup.find('' + fieldtype + '[name=' + fieldname + '' + cid + ']').attr("name", '' + fieldname + '' + nextcount + '');
                
            }
        }
    };
    var doentry = "true";
    // console.log("elgroup modified: ",elgroup);

    // var nextcountout=nextcount-3;
    // var nextcountmain=nextcount-1;

    if (isNumber(branchlimit) && branchlimit > 0) {
        
        if (nextcounttrue <= Math.floor(branchlimit)) {
            if(maintotalscripts!==""){
                elgroup.append(maintotalscripts);
            }
            doentry = "true";
        } else {
            doentry = "false";
            window.alert("Maximum allowed entries reached");
        }
    }

    // console.log("Nextcount: ",nextcount," nextcounttrue - ", nextcounttrue," entrypoint - ", entrypoint);
    if (doentry == "true") {
        if (insertiontype == "default" || insertiontype == "before") {
            $(elgroup).insertBefore(entrypoint);
        } else if (insertiontype == "after") {
            $(elgroup).insertAfter(entrypoint);

        }
        //selection.appendTo(outs);
        mainparent.find('input[name=' + countername + ']').val('' + nextcount + '');
        // for tinymce init
        if (domce == "true") {
            for (var i = 0; i < cmcecount; i++) {
                callTinyMCEInit(strinnerset[i], mceoptions[i]);
            }
            ;
        }
    }
})


/**
*   this function is a callable version for the fapicker(FontAwesome Picker system);
*   the purpose here is to allow interactable elements display the fontawesome picker
*   section.
*   
*   @param Jquery selector 'containerel' is the JQuery selector for the container
*   element holding the fontawesome display section 
*
*   
*
*
*/
function bindFAPicker(containerel){
    
    // console.log("it was clicked");
    curval=$(this).attr("value");
    icontitle=$(this).attr("title");

    var target_input=$(containerel).find('input[data-name=icontitle]');
    var target_display=$(containerel).find('.currentfa i');
    var prevval=target_input.val();
    // console.log(target_input,target_display);
    if(type=="attached"){
        // this section means the fontawesome selection list is being pulled via
        // ajax
        var load_img='<div class="faloadergauze"><img src="'+host_addr+'" class="loadermini"/></div>'
        // check to see if the container element already has a 'loadermini'
        // class element in it
        if($(containerel).find('.faloaderguaze').length==0){
            // add the loader section to the container element
            $(containerel).append(load_img);
        }
        // check if the ul.fadisplaylist
        // if one exists then clone it, otherwise load it via ajax
        var listoutput='';
        if($('ul.fadisplaylist').length>0){
            listoutput=$('ul.fadisplaylist').clone();
            var url = '' + host_addr + 'snippets/display.php';
            var opts = {
                type: 'GET',
                url: url,
                data: {
                    displaytype: 'pullfontawesomelist',
                    fatype: 'list',
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
                        endtarget.innerHTML = output.msg;
                    }
                },
                error: function(error) {
                    if (typeof (error) == "object") {
                        console.log(error.responseText);
                    }
                    var errmsg = "Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again";
                    // item_loader.remove();
                    item_loader.addClass('hidden');
                    // item_loader.className += ' hidden';
                    raiseMainModal('Failure!!', '' + errmsg + '', 'fail');
                    // alert("Sorry, something went wrong, possibly your internet connect is inactive, we apologise if this is from our end. Try the action again ");
                }
            };
            $.ajax(opts);
        }else{
            // lets go ajax hunting
        }
        // $().insertAfter();
    }
    if(prevval!==curval){
        target_input.val(curval);
        target_display.attr("class","fa "+curval);

    }else{
        target_input.removeAttr("value");
        target_input.val("");
        target_display.attr("class","");
    }
}

// select fa templating
function faSelectTemplate(faelem){
    var $fadisplay="";
    var cfv="";
    var cft="Choose an Icon";
    // console.log(faelem); 
    if (!faelem.id) { return faelem.text; }
    if(typeof(faelem)!=="undefined"&&faelem.element.value!==""){
        cfv=faelem.element.value;
        cft=faelem.element.outerText;
        // console.log("faelem: ",faelem,"\n cft:",cft,"\n cfv:",cfv);
        $fadisplay=$('<span class="fa_classet"><i class="fa '+cfv+' _classet"></i> '+cft+'</span>');

    }else{
        $fadisplay=$('<span class="fa_classet"><i class="fa '+cfv+' _classet"></i> '+cft+'</span>');
    }
    return $fadisplay;
}



//filter results based on query
function filter(selector, query,type='text',attr="",phide="") {
    query =   $.trim(query); //trim white space
    query = query.replace(/ /gi, '|'); //add OR for regex query
    // phide is the selector for a seperate element to be show or hidden
    // its mostly an object
    if(phide==""){
        if(type=="text"){
            $(selector).each(function() {
                ($(this).text().search(new RegExp(query, "i")) < 0) ? $(this).addClass('hidden') : $(this).removeClass('hidden');
            });
        }else if(type=="html"){
            $(selector).each(function() {
                ($(this).html().search(new RegExp(query, "i")) < 0) ? $(this).addClass('hidden') : $(this).removeClass('hidden');
            });
        }else if(type=="attribute"){
            $(selector).each(function() {
                ($(this).attr(attr).search(new RegExp(query, "i")) < 0) ? $(this).addClass('hidden') : $(this).removeClass('hidden');
            });
        }

    }else{
        if(type=="text"){
            $(selector).each(function() {
                ($(this).text().search(new RegExp(query, "i")) < 0) ? $(phide).addClass('hidden') : $(phide).removeClass('hidden');
            });
        }else if(type=="html"){
            $(selector).each(function() {
                ($(this).html().search(new RegExp(query, "i")) < 0) ? $(phide).addClass('hidden') : $(phide).removeClass('hidden');
            });
        }else if(type=="attribute"){
            $(selector).each(function() {
                ($(this).attr(attr).search(new RegExp(query, "i")) < 0) ? $(phide).addClass('hidden') : $(phide).removeClass('hidden');
            });
        }
    }
}

(function($){
    // CSearch is expected to be attached to an input element
    $.fn.CSearch= function(targetElement,type='text',attr="",phide=""){
        // valid type values are 'text' and attribute, of which the 'attr'
        // parameter must be provided
        if(phide=="parent"){
            // phide=$(this).parent();
        }


        // console.log("Running: true ", "This: ", $(this)," Phide: ",phide);
        $(this).keyup(function(event) {

            //if esc is pressed or nothing is entered
            if (event.keyCode == 27 || $(this).val() == '') {
              //if esc is pressed we want to clear the value of search box
              $(this).val('');
            
              //check each group of fa target elements, if nothing 
              //is entered then all targets are matched.
              $(''+targetElement+'').removeClass('hidden');
            }else{
                filter(''+targetElement+'', $(this).val(),type,attr,phide);
            }

        })        
    }
}(jQuery));

/*wordMax*/

(function($){
    // wordMax is expected to be attached to an input or textarea element
    $.fn.wordMAX= function(){
        
        // console.log("Running: true ", "This: ", $(this)," Phide: ",phide);
        var tthis=$(this);
        var theparent=$(this).parent();
        // console.log(theparent," :the parent. the el:",tthis);
        $(document).on("blur",tthis,function(){
          theparent.find('.wMax-view').addClass("hidden");
        });
        var error=false;
        var mcetester=$(this).attr("data-mce");
        if(mcetester===null||mcetester===undefined||mcetester===NaN){ 
            mcetester="";
        }   
        
        
        var maxcount = $(this).attr('data-wMAX')!==""&&isNumber(parseInt($(this).attr('data-wMAX')))?$(this).attr('data-wMAX'):0;
        
        // console.log('type',$(this).attr('data-wMAX-type'));
        var type = typeof $(this).attr('data-wMAX-type')!== "undefined"&&$(this).attr('data-wMAX-type')!==""?$(this).attr('data-wMAX-type'):"word";
        
        var formname = typeof $(this).attr('data-wMAX-fname')!== "undefined"&&$(this).attr('data-wMAX-fname')!==""?$(this).attr('data-wMAX-fname'):"";
        var checkElem=$(this).next();
        var tout=type=="word"?"Words":"Characters";
        var maxout=maxcount>0?maxcount:"none";
        var countout=0;
        // get the number of wmax-view elements for the current element
        var parent=$(this).parent();
        var mViewcount=parent.find('.wMax-view').length;

        if(mcetester==""){

            $(this).keyup(function(event) {
                var cval = $(this).val();
                // console.log("Cur val:",cval,' cur type:',type,' maxcount:',maxcount);
                if(isNumber(maxcount)){
                    maxcount=parseInt(maxcount);
                    //if esc is pressed or nothing is entered
                    // console.log(event.keyCode);
                    if (event.keyCode == 27) {
                        //if esc is pressed we want to clear the value of textbox
                        $(this).val('');
                        countout=0;
                        // next remove any excess maxview elements
                        if(mViewcount>1){
                            for(var i=1;i<mviewcount;i++){
                                parent.find('.wMax-view')[i].remove();
                            }
                        }
                        // $(''+targetElement+'').removeClass('hidden');
                    }else{
                        // process the value of the field
                        if(maxcount>0){
                            if(type=="word"){
                                // match all non white space characters
                                var wordcount=0;
                                // console.log(" tp:",typeof String(cval).match(/\S+/g).length);
                                if(cval.length>0&&String(cval).match(/\S+/g).length>0){
                                    wordcount=String(cval).match(/\S+/g).length;

                                }
                                // console.log("Word count:",wordcount);
                                var countout=wordcount;

                                if(wordcount>maxcount){
                                
                                    countout=maxcount;
                                    var diff=wordcount-maxcount;
                                    var wo=diff>1?"words":"word";
                                    var errmsg="Word count exceeded by: "+diff+" "+wo+"";
                                
                                    raiseMainModal('Important!!', '' + errmsg + '', 'warning');
                                
                                    $("#mainPageModal").on("hidden.bs.modal", function() {
                                
                                        var mainc=cval.split(" ");
                                        var subc=mainc.splice(0,maxcount+2);
                                        var orval="";
                                        for(var si=0;si<maxcount;si++){
                                            orval+=si==0?subc[si]:" "+subc[si];
                                        }
                                        // console.log("CVAL: ",cval,"\n NEW: ",orval);
                                        tthis.val(orval);
                                        tthis.focus();
                                    });
                                } 
                                // checkElem.find('.wMax-count').html(countout);
                                // console.log(checkElem.find('.wMax-count'));
                                if(error==true&&formname!==""){
                                    // $('form[name='+formname+']').attr("onSubmit","return false;");
                                }
                                
                            }else if(type=="length"){
                                var lengthcount=cval.length;
                                var countout=lengthcount;
                                // console.log("Length count:",lengthcount);
                                if(lengthcount>maxcount){
                                    countout=maxcount;
                                    var diff=lengthcount-maxcount;
                                    var errmsg="Length count exceeded by character:"+diff;
                                    raiseMainModal('Important!!', '' + errmsg + '', 'warning');
                                    $("#mainPageModal").on("hidden.bs.modal", function() {
                                        var orval=cval.slice(0,maxcount-1);
                                        // console.log(cval," new:",orval);
                                        tthis.val(orval);
                                        tthis.focus();
                                    });
                                } 
                                if(error==true&&formname!==""){
                                    // $('form[name='+formname+']').attr("onSubmit","return false;");
                                }
                            }


                        }
                    }
                    if(checkElem.is('.wMax-view')){
                        checkElem.find('.wMax-count').html(countout);
                        // next remove any excess maxview elements
                        if(mViewcount>1){
                            for(var i=1;i<mviewcount;i++){
                                parent.find('.wMax-view')[i].remove();
                            }
                        }
                        parent.find('.wMax-view').removeClass('hidden');

                    }else{
                        // create the wMax elem object and place it after the current element
                        var tval = $(this).val();
                        var chel=$('<div class="wMax-view"><span class="wMax-type">'+tout+'</span>: <span class="wMax-count">'+countout+'</span> <span class="wMax-limit">Limit: '+maxout+'</span></div>');
                        // check and make sure there are no wMax-View elements already
                        // if there are none insertion of the new element can occur
                        if(mViewcount==0){
                            $(chel).insertAfter($(this));
                        }

                        // get the wMax-view element for the entry
                        checkElem=$(this).next();
                        // console.log("check elem: ",checkElem);
                    }
                }else{
                    var errmsg="No valid data-wMAX attribute value detected, or attribute not present";
                    raiseMainModal('Important!!', '' + errmsg + '', 'warning');
                }

            });       
        }else if(mcetester=="true"){  
            var mcid=$(this).attr("id");
            var cval = $(this).val();
                // console.log("Cur val:",cval,' cur type:',type,' maxcount:',maxcount);
            $(this).on("change",function(){
                if(isNumber(maxcount)){
                    maxcount=parseInt(maxcount);
                    //if esc is pressed or nothing is entered
                    // console.log(event.keyCode);
                    
                    // process the value of the field
                    if(maxcount>0){
                        if(type=="word"){
                            // match all non white space characters
                            var wordcount=0;
                            // console.log(" tp:",typeof String(cval).match(/\S+/g).length);
                            if(cval.length>0&&String(cval).match(/\S+/g).length>0){
                                wordcount=String(cval).match(/\S+/g).length;

                            }
                            // console.log("Word count:",wordcount);
                            var countout=wordcount;

                            if(wordcount>maxcount){
                            
                                countout=maxcount;
                            
                                var diff=wordcount-maxcount;
                                var errmsg="Word count exceeded by word count: "+diff;
                            
                                raiseMainModal('Important!!', '' + errmsg + '', 'warning');
                            
                                $("#mainPageModal").on("hidden.bs.modal", function() {
                            
                                    var mainc=cval.split(" ");
                                    var subc=mainc.splice(0,maxcount+2);
                                    var orval="";
                                    for(var si=0;si<maxcount;si++){
                                        orval+=si==0?subc[si]:" "+subc[si];
                                    }
                                    // console.log("CVAL: ",cval,"\n NEW: ",orval);
                                    tthis.val(orval);
                                    tthis.focus();
                                });
                            } 
                            // checkElem.find('.wMax-count').html(countout);
                            // console.log(checkElem.find('.wMax-count'));
                            if(error==true&&formname!==""){
                                // $('form[name='+formname+']').attr("onSubmit","return false;");
                            }
                            
                        }else if(type=="length"){
                            var lengthcount=cval.length;
                            var countout=lengthcount;
                            // console.log("Length count:",lengthcount);
                            if(lengthcount>maxcount){
                                countout=maxcount;
                                var diff=lengthcount-maxcount;
                                var errmsg="Length count exceeded by character:"+diff;
                                raiseMainModal('Important!!', '' + errmsg + '', 'warning');
                                $("#mainPageModal").on("hidden.bs.modal", function() {
                                    var orval=cval.slice(0,maxcount-1);
                                    // console.log(cval," new:",orval);
                                    tthis.val(orval);
                                    tthis.focus();
                                });
                            } 
                            if(error==true&&formname!==""){
                                // $('form[name='+formname+']').attr("onSubmit","return false;");
                            }
                        }


                    }
                    
                    if(checkElem.is('.wMax-view')){
                        checkElem.find('.wMax-count').html(countout);
                    }else{
                        // create the wMax elem object and place it after the current element
                        var tval = $(this).val();
                        var chel=$('<div class="wMax-view"><span class="wMax-type">'+tout+'</span>: <span class="wMax-count">'+countout+'</span> <span class="wMax-limit">Limit: '+maxout+'</span></div>')
                        $(chel).insertAfter($(this));
                        // get the wMax-view element for the entry
                        checkElem=$(this).next();
                        // console.log("check elem: ",checkElem);
                    }
                }else{
                    var errmsg="No valid data-wMAX attribute value detected, or attribute not present";
                    raiseMainModal('Important!!', '' + errmsg + '', 'warning');
                    $("#mainPageModal").on("hidden.bs.modal", function () {
                        tinyMCE.get(mcid).focus();
                    });

                }     
            })
        }

    }
}(jQuery));

/** Obtained from Locutus library
* URL:
*/
function exit( status ) {
    // http://kevin.vanzonneveld.net
    // +   original by: Brett Zamir (http://brettz9.blogspot.com)
    // +      input by: Paul
    // +   bugfixed by: Hyam Singer (http://www.impact-computing.com/)
    // +   improved by: Philip Peterson
    // +   bugfixed by: Brett Zamir (http://brettz9.blogspot.com)
    // %        note 1: Should be considered expirimental. Please comment on this function.
    // *     example 1: exit();
    // *     returns 1: null

    var i;

    if (typeof status === 'string') {
        alert(status);
    }

    window.addEventListener('error', function (e) {e.preventDefault();e.stopPropagation();}, false);

    var handlers = [
        'copy', 'cut', 'paste',
        'beforeunload', 'blur', 'change', 'click', 'contextmenu', 'dblclick', 'focus', 'keydown', 'keypress', 'keyup', 'mousedown', 'mousemove', 'mouseout', 'mouseover', 'mouseup', 'resize', 'scroll',
        'DOMNodeInserted', 'DOMNodeRemoved', 'DOMNodeRemovedFromDocument', 'DOMNodeInsertedIntoDocument', 'DOMAttrModified', 'DOMCharacterDataModified', 'DOMElementNameChanged', 'DOMAttributeNameChanged', 'DOMActivate', 'DOMFocusIn', 'DOMFocusOut', 'online', 'offline', 'textInput',
        'abort', 'close', 'dragdrop', 'load', 'paint', 'reset', 'select', 'submit', 'unload'
    ];

    function stopPropagation (e) {
        e.stopPropagation();
        // e.preventDefault(); // Stop for the form controls, etc., too?
    }
    for (i=0; i < handlers.length; i++) {
        window.addEventListener(handlers[i], function (e) {stopPropagation(e);}, true);
    }

    if (window.stop) {
        window.stop();
        
    }

    throw '';
}

// function to make all form elements lose focus()
function loseFocus(formname=""){
    if(formname!==""){
        $('form[name='+formname+'] input,form[name='+formname+'] select,form[name='+formname+'] textarea,form[name='+formname+'] button,form[name='+formname+'] radio,form[name='+formname+'] checkbox').blur()
    }else{
       $('input,select,textarea,button,radio,checkbox').blur();    
    }
}
// resets the values of all fields in specified element
function resetValues(telem){
    var tg=$(''+telem+'');
    if(telem!==""&&tg.length){
        elgroup=tg.clone();
        elgroup.find('input[type!=hidden]').val("");
        elgroup.find('[data-hdeftype=hidden]').addClass("hidden");
        // console.log(elgroup.find('input[type!=hidden]'));
        elgroup.find('select').val("");
        elgroup.find('textarea').val("");
        tg.replaceWith(elgroup);
    }
}
/**
* Hex to rgb vice versa functions, kudos to Tim Down
* http://stackoverflow.com/users/96100/tim-down
* for the awesome work, and me for whatever extras
* muhahahahahaha
*/
function hexToRgb(hex) {
    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, function(m, r, g, b) {
        return r + r + g + g + b + b;
    });

    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}
function rgbToHex(r, g, b) {
    return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
}

function getInputSelection(el) {
    var start = 0, end = 0, normalizedValue, range,
        textInputRange, len, endRange;

    if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
        start = el.selectionStart;
        end = el.selectionEnd;
    } else {
        range = document.selection.createRange();

        if (range && range.parentElement() == el) {
            len = el.value.length;
            normalizedValue = el.value.replace(/\r\n/g, "\n");

            // Create a working TextRange that lives only in the input
            textInputRange = el.createTextRange();
            textInputRange.moveToBookmark(range.getBookmark());

            // Check if the start and end of the selection are at the very end
            // of the input, since moveStart/moveEnd doesn't return what we want
            // in those cases
            endRange = el.createTextRange();
            endRange.collapse(false);

            if (textInputRange.compareEndPoints("StartToEnd", endRange) > -1) {
                start = end = len;
            } else {
                start = -textInputRange.moveStart("character", -len);
                start += normalizedValue.slice(0, start).split("\n").length - 1;

                if (textInputRange.compareEndPoints("EndToEnd", endRange) > -1) {
                    end = len;
                } else {
                    end = -textInputRange.moveEnd("character", -len);
                    end += normalizedValue.slice(0, end).split("\n").length - 1;
                }
            }
        }
    }

    return {
        start: start,
        end: end
    };
}


// function for stopping video seek 
function seekTimeControl(vidselector,playlimit=0){
    var video = $(vidselector).get(0);
    var supposedCurrentTime = 0;
    if(parseInt(playlimit)>0){

        video.addEventListener('timeupdate', function() {
            if (!this._startTime) this._startTime = this.currentTime;
            var playedTime = this.currentTime - this._startTime;
            if (playedTime >= playlimit){
                this.pause();
                $(this).removeAttr("controls");
            } 
            if (!video.seeking) {
                supposedCurrentTime = video.currentTime;
            }
        });
        // prevent user from seeking
        video.addEventListener('seeking', function() {
          // guard agains infinite recursion:
          // user seeks, seeking is fired, currentTime is modified, seeking is fired, current time is modified, ....
          var delta = video.currentTime - supposedCurrentTime;
          if (Math.abs(delta) > 0.01) {
            console.log("Seeking is disabled");
            video.currentTime = supposedCurrentTime;
          }
        });
        // delete the following event handler if rewind is not required
        video.addEventListener('ended', function() {
          // reset state in order to allow for rewind
            supposedCurrentTime = 0;
        });
    }
}