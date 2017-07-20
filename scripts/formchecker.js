function forceForm(formObj){
	console.log(formObj,fomrObj.getAttribute('name'));
	$(document).ready(function(){
		var usertype=$('input[name=usertype]').attr('id');
		var userid=$('input[name=usertype]').val();
		var usermode="<input type=\"hidden\"name=\"usermode\" value=\""+usertype+"\">";
		var uid="<input type=\"hidden\"name=\"uid\" value=\""+userid+"\">";	
		var userdata=''+usermode+''+uid+'';
		$(userdata).insertBefore('form[name='+formObj+'] input[name=entryvariant]').val();
	});
}

$(document).ready(function(){
  	var pagedata=document.URL;
  	var pagedatatwo=pagedata.split(".");
 	var realpage=pagedatatwo[0];
	// var usertype=$('input[name=userdata]').attr('data-type');
	// var userid=$('input[name=userdata]').attr('value');
	$(document).on("blur",'input[type!=hidden][type!=button][type!=submit]',function(){
		$(this).removeClass('error-class');
	});
	$(document).on("blur",'select',function(){
		$(this).removeClass('error-class');
	});
	$(document).on("blur",'textarea',function(){
		$(this).removeClass('error-class');
	});
	$(document).on("mouseenter",'form #elementholder',function(){
		$(this).removeClass('error-class');
	});
	$(document).on("blur",'form[name^=edit] input[type!="file"][type!="button"][type!="submit"][type!="hidden"],form[name^=edit] textarea',function(){
		// tinymce.triggerSave();
		// console.log($(this));
		// curval=$(this).val();
		/*if(curval==""){
			// $(this).val("");
			// console.log("No Value");
			// $(this).get(0).defaultValue="";
		}*/
	})
$(document).on("click",'input[type=button]',function(){
	var viewwindow=$('#viewneditcontent');
	var buttonname=$(this).attr('name');
	var buttonid=$(this).attr('id');
	var tester="";
	
	if(buttonname=="adminloginsubmit"){
		var formstatus=true;
		var inputname1=$('input[name=username]').attr('value');
		var inputname2=$('input[name=password]').attr('value');
		if(inputname1==""){
			window.alert('Please enter the username number');
			$("input[name=username]").addClass('error-class').focus();
			formstatus= false;
		}else if(inputname2==""){
			window.alert('Please enter your password');
			$("input[name=password]").addClass('error-class').focus();
			formstatus= false;
		}
		if(formstatus==true){
			$('form[name=adminloginform]').submit();
		}		
	}



if(buttonname=="createbooking"){
var formstatus=true;
var inputname1=$('form[name=bookingform] input[name=name]');
var testervala=$('form[name=bookingform] input[name=name]').val();
	console.log(realpage,buttonname,testervala);
var inputname2=$('textarea[name=address]');
var inputname11=$('input[name=themetitle]');
var inputname3=$('input[name=contactperson]');
var inputname4=$('input[name=from]');
var inputname5=$('input[name=to]');
var inputname6=$('select[name=eventtype]');
var inputname7=$('select[name=language]');
var inputname8=$('input[name=expectedattendance]');
var inputname9=$('input[name=phone1]');
var inputname10=$('input[name=email]');
if(inputname1.val()==""){
	window.alert('Please give the organization\'s name');
	$(inputname1).addClass('error-class').focus();
	formstatus= false;
}else if(inputname2.val()==""){
	window.alert('Please give the address of the organization');
	$(inputname2).addClass('error-class').focus();
	formstatus= false;
}else if(inputname11.val()==""){
	window.alert('Please give the Theme/Title for the program');
	$(inputname11).addClass('error-class').focus();
	formstatus= false;
}else if(inputname3.val()==""){
	window.alert('Please fill the Contact Person field');
	$(inputname3).addClass('error-class').focus();
	formstatus= false;
}else if(inputname4.val()==""){
	window.alert('Please give the start date of the event');
	$(inputname4).addClass('error-class').focus();
	formstatus= false;
}else if(inputname5.val()==""){
	window.alert('Please give the ending date of the event');
	$(inputname5).addClass('error-class').focus();
	formstatus= false;
}else if(inputname6.val()==""){
	window.alert('Kindly Choose an Event Type in the presented categories');
	$(inputname6).addClass('error-class').focus();
	formstatus= false;
}else if(inputname7.val()==""){
	window.alert('Please choose a language from the selection');
	$(inputname7).addClass('error-class').focus();
	formstatus= false;
}else if(inputname8.val()==""){
	window.alert('Please state the expected attendance at the event');
	$(inputname8).addClass('error-class').focus();
	formstatus= false;
}else if(inputname9.val()==""){
	window.alert('Please give a contact number we can call when necessary');
	$(inputname9).addClass('error-class').focus();
	formstatus= false;
}else if(inputname10.val()==""){
	window.alert('Email address missing');
	$(inputname10).addClass('error-class').focus();
	formstatus= false;
}else if(inputname10!==""){
		var status=emailValidator(inputname10.val());
		status!==""?formstatus=status:status=status;
	}

if(formstatus==true){
	// window.alert('The form is ready to be submitted');
$('form[name=bookingform]').submit();
}
}


if(buttonname=="createservicerequest"){
	var formstatus=true;
	var inputname1=$('input[name=name]');
	var inputname2=$('input[name=organizationname]');
	var inputname3=$('input[name=team]');
	var inputname4=$('input[name=from]');
	var inputname5=$('input[name=to]');
	var inputname6=$('select[name=eventtype]');
	var inputname7=$('input[name=expectedattendance]');
	var inputname8=$('input[name=phone1]');
	var inputname9=$('textarea[name=venue]');
	if(inputname1.val()==""){
		window.alert('Please provide your fullname');
		$(inputname1).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname2.val()==""&&inputname3.val()==""){
		window.alert('Please give the name of the organization or team');
		$(inputname2).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname4.val()==""){
		window.alert('Please give the start date and duration of the event');
		$(inputname4).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname5.val()==""){
		window.alert('Please give the ending date and duration of the event');
		$(inputname5).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname6.val()==""){
		window.alert('Kindly Choose an Event Type in the presented categories');
		$(inputname6).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname7.val()==""){
		window.alert('Please state the expected attendance at the event');
		$(inputname7).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname8.val()==""){
		window.alert('Please give a contact number we can call when necessary');
		$(inputname8).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname9.val()==""){
		window.alert('Please give the address of the venue the event is expected to hold at.');
		$(inputname9).addClass('error-class').focus();
		formstatus= false;
	}

	if(formstatus==true){
		// window.alert('The form is ready to be submitted');
	 $('form[name=servicerequestform]').submit();
	}

}


if(buttonname=="fvtblogsubscriptiontwo"||buttonname=="fvtblogsubscription"){
	var formstatus=true;
	var inputname1=$('form[name='+buttonname+'] input[name=email]');
	if(inputname1.val()==""){
		window.alert('Please fill the email field with your email address then subscribe to this blog.');
		$(inputname1).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname1.val()!==""){
			var status=emailValidator(inputname1.val());
			status!==""?formstatus=status:status=status;
	}
	if(formstatus==true){
		window.alert('The form is ready to be submitted');
		$('form[name='+buttonname+']').submit();
	}
}
if(buttonname=="categorysubscription"){
	var formstatus=true;
	var inputname1=$('input[name=email]');
	if(inputname1.val()==""){
		window.alert('Please fill the email field with your email address then subscribe to this blog.');
		$(inputname1).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname1.val()!==""){
		var status=emailValidator(inputname1.val());
		status!==""?formstatus=status:status=status;
	}
	if(formstatus==true){
		// window.alert('The form is ready to be submitted');
		$('form[name=categorysubscription]').submit();
	}
}
if(buttonname=="createtestimony"){
	var formstatus=true;
	var inputname1=$('input[name=name]');
	var inputname2=$('form[name=testimonyform] input[name=email]');
	var inputname3=$('textarea[name=testimony]');
	if(inputname1.val()==""){
		window.alert('Please provide your fullname');
		$(inputname1).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname2.val()==""){
		window.alert('Please fill the email address field.');
		$(inputname2).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname3.val()==""){
		window.alert('Please give your testimony');
		$(inputname3).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname2.val()!==""){
			var status=emailValidator(inputname2.val());
			formstatus=status;
	}
		// console.log(status);
	if(formstatus==true){
		var tester=window.confirm('The form is ready to be submitted click ok to continue or cancel to review');
	if(tester===true){
	$('form[name=testimonyform]').submit();
	}
	}
}

if(buttonname=="createblogcomment"){
	var formstatus=true;
	var inputname1=$('input[name=name]');
	var inputname2=$('input[name=email]');
	var inputname4=$('input[name=sectester]');
	var inputname5=$('input[name=secnumber]');

	tinyMCE.triggerSave();
	var inputname3=$('textarea[name=comment]');
	console.log(inputname3.val());
	if(inputname1.val()==""){
		window.alert('Please provide your fullname');
		$(inputname1).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname2.val()==""){
		window.alert('Please fill the email address field.');
		$(inputname2).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname3.val()==""){
		window.alert('You haven\'t given any comment');
		$(inputname3).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname5.val()==""){
		window.alert('Please enter the security number');
		$(inputname5).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname4.val()!==inputname5.val()){
		window.alert('Sorry the security number is not correct.');
		$(inputname5).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname2.val()!==""){
			var status=emailValidator(inputname2.val());
			formstatus=status;
	}
		// console.log(status);
	if(formstatus==true){
		var confirmed=window.confirm('Your comment is ready to be submitted, it would be reviewed before being activated for this blog post, if you dont want to comment click "Cancel" otherwise click "OK"');
		console.log(confirmed);
		if(confirmed===true){
			$('form[name=blogcommentform]').submit();
		}
	}
}

if(buttonname=="createblogtype"){
	var formstatus=true;
	var inputname1=$('input[name=name]');
	var inputname2=$('textarea[name=description]');
	if(inputname1.val()==""){
		window.alert('Please fill the name field.');
		$(inputname1).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname2.val()==""){
		window.alert('Please give a description for the blog.');
		$(inputname1).addClass('error-class').focus();
		formstatus= false;
	}
		// console.log(status);
	if(formstatus==true){
		var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
		console.log(confirmed);
		if(confirmed===true){
			$('form[name=blogtype]').submit();
		}
	}
}

if(buttonname=="createblogcategory"){
	var formstatus=true;
	var inputname1=$('select[name=categoryid]');
	var inputname2=$('input[name=name]');
	var inputname3=$('input[name=profpic]');
	var inputname4=$('input[name=subtext]');

	if(inputname1.val()==""){
		window.alert('Please select a blog type.');
		$(inputname1).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname2.val()==""){
		window.alert('Please fill the category name field.');
		$(inputname2).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname1.val()=="3"&&inputname3.val()==""){
		window.alert('Please select a category image for this, endeavour to make sure your image dimension is not too large i.e greater than 1280 on both width and length.');
		$(inputname3).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname1.val()=="3"&&inputname4.val()==""){
		window.alert('Please state the subtext of the category.');
		$(inputname4).addClass('error-class').focus();
		formstatus= false;
	}
		// console.log(status);
	if(formstatus==true){
		var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
		console.log(confirmed);
		if(confirmed===true){
			$('form[name=blogcategory]').submit();
		}
	}
}
if(buttonname=="createblogpost"){
	var formstatus=true;
	var monitor="off";
	tinyMCE.triggerSave();
	var inputname1=$('select[name=blogtypeid]');
	var inputname2=$('select[name=blogcategoryid]');
	var inputname3=$('input[name=profpic]');
	var inputname4=$('input[name=title]');
	var inputname5=$('textarea[name=introparagraph]');
	var inputname6=$('textarea[name=blogentry]');
	var inputname7=$('select[name=blogentrytype]');
	var inputname8=$('input[name=bannerpic]');
	var inputname9=$('select[name=audiotype]');
	var inputname10=$('input[name=audio]');
	var inputname11=$('textarea[name=audioembed]');
	var inputname12=$('select[name=videotype]');
	var inputname13=$('input[name=videoflv]');
	var inputname14=$('input[name=videomp4]');
	var inputname15=$('input[name=video3gp]');
	var inputname17=$('input[name=videowebm]');
	var inputname16=$('textarea[name=videoembed]');

	if(inputname1.val()==""){
		window.alert('Please select a blog type.');
		$(inputname1).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname2.val()==""){
		window.alert('Please select the category for this blog post.');
		$(inputname2).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname3.val()==""&&(inputname7.val()==""||inputname7.val()=="normal"||inputname7.val()=="audio"||inputname7.val()=="video")){
		window.alert('Please select a cover image for this post, endeavour to make sure your image dimension is not too large i.e greater than 1280 on both width and length.');
		$(inputname3).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname4.val()==""){
		window.alert('Please the title of the blog post, adviceably, make the title as close to what a web user would search for when looking for the content you want to post.');
		$(inputname4).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname5.val()==""&&(inputname7.val()==""||inputname7.val()=="normal"||inputname7.val()=="audio"||inputname7.val()=="video")){
		window.alert('Please give the introductory part of this blog, this could be a short summary of the contents of the blog, something to make your reader have an understanding of your post there in or to make them actually want to continue reading. click cancel if you want to continue');
		$(inputname5).addClass('error-class').focus();
		formstatus= false;		
		monitor="on";
	}else if(inputname6.val()==""&&(inputname7.val()==""||inputname7.val()=="normal"||inputname7.val()=="audio"||inputname7.val()=="video")){
		window.alert('Please give the blog post some meaning, its empty');
		$(inputname6).addClass('error-class').focus();
		formstatus= false;	
		monitor="on";	
	}else if(inputname8.val()==""&&inputname7.val()=="banner"){
		window.alert('Please give the banner image');
		$(inputname8).addClass('error-class').focus();
		formstatus= false;
		monitor="on";
	}else if (inputname9.val()==""&&inputname7.val()=="audio") {
		window.alert('Please select the nature of the audio entry you wnat dislplayed for this post.');
		$(inputname9).addClass('error-class').focus();
		formstatus= false;
		monitor="on";
	}else if (inputname12.val()==""&&inputname7.val()=="video") {
		window.alert('Please select the nature of the video entry you wnat dislplayed for this post.');
		$(inputname12).addClass('error-class').focus();
		formstatus= false;
		monitor="on";
	}


	// check mp3 audio
	if(monitor=="off" && inputname10.val()!==""){
		var videoout=getExtension(inputname10.val());
		if(videoout['extension']!=="mp3"){
			window.alert('Please select a valid mp3 audio');
			$(inputname10).addClass('error-class').focus();
			formstatus= false;
			monitor="on";
		}
	}
	// check flv video
	if(monitor=="off" && inputname13.val()!==""){
		var videoout=getExtension(inputname13.val());
		if(videoout['extension']!=="flv"){
			window.alert('Please select a valid flv video');
			$(inputname13).addClass('error-class').focus();
			formstatus= false;
			monitor="on";
		}
	}

	// check mp4 video
	if(monitor=="off" && inputname14.val()!==""){
		var videoout=getExtension(inputname14.val());
		if(videoout['extension']!=="mp4"){
			window.alert('Please select a valid mp4 video');
			$(inputname14).addClass('error-class').focus();
			formstatus= false;
			monitor="on";
		}
	}
	// check 3gp video
	if(monitor=="off" && inputname15.val()!==""){
		var videoout=getExtension(inputname15.val());
		if(videoout['extension']!=="3gp"&&videoout['extension']!=="3gpp"){
			window.alert('Please select a valid 3gp video');
			$(inputname15).addClass('error-class').focus();
			formstatus= false;
			monitor="on";
		}
	}
	// check webm video
	if(monitor=="off" && inputname17.val()!==""){
		var videoout=getExtension(inputname17.val());
		if(videoout['extension']!=="webm"){
			window.alert('Please select a valid webm video');
			$(inputname17).addClass('error-class').focus();
			formstatus= false;
			monitor="on";
		}
	}
	if(monitor=="off"&&inputname7.val()=="video"&&inputname13.val()==""&&inputname14.val()==""&&inputname15.val()==""&&inputname16.val()==""&&inputname17.val()==""){
		window.alert('No file or embed code detected');
		$(inputname13).addClass('error-class').focus();
		formstatus= false;
		monitor="on";
	}
	if(monitor=="off"&&inputname7.val()=="audio"&&inputname10.val()==""&&inputname11.val()==""){
		window.alert('No file or embed code detected');
		$(inputname10).addClass('error-class').focus();
		formstatus= false;
		monitor="on";
	}
		// console.log(status);
	if(formstatus==true){
		var confirmed=window.confirm('The post is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
		console.log(confirmed);
		if(confirmed===true){
			// console.log(confirmed,$('form[name=blogpost]'));
			$('form[name=blogpost]').submit();
		}
	}
}
if(buttonname=="unsubscribe"){
var formstatus=true;
var inputname1=$('input[name=email]');
if(inputname1.val()==""){
window.alert('Please fill the email address field.');
	$(inputname1).addClass('error-class').focus();
	formstatus= false;
}else if(inputname1.val()!==""){
var status=emailValidator(inputname1.val());
		status!==""?formstatus=status:status=status;
}
if(formstatus==true){
	var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check or are unsure you want to cancel your subscription, click "Cancel" otherwise click "OK"');
	console.log(confirmed);
	if(confirmed===true){
	// console.log(confirmed,$('form[name=blogpost]'));
$('form[name=unsubscribe]').submit();
	}
}
}

if(buttonname=="createqotd"){
	var formstatus=true;
	var inputname1=$('select[name=quotetype]');
	var inputname2=$('input[name=quotedperson]');
	var inputname3=$('textarea[name=quoteoftheday]');
	if(inputname1.val()==""){
		window.alert('Please Select the Quotetype to help the application know where this quote can show.');
		$(inputname1).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname3.val()==""){
	window.alert('Please give the quote information the field is empty .');
		$(inputname3).addClass('error-class').focus();
		formstatus= false;
	}
	if(formstatus==true){
		var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check or are unsure you want to cancel your subscription, click "Cancel" otherwise click "OK"');
		console.log(confirmed);
		if(confirmed===true){
		// console.log(confirmed,$('form[name=qotd]'));
	$('form[name=qotdform]').submit();
		}
	}
}

if(buttonname=="createevent"||buttonname=="editevent"){
	var formname=$(this).attr("data-formdata");
	console.log("the form data - ",formname);
	var formit=submitCustom(formname);
	var formstatus=formit['formstatus'];
	var pointmonitor=formit['pointmonitor'];

	if(formstatus==true&&pointmonitor==false){
	    var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
	    // console.log(confirmed);
	    if(confirmed===true){
	      $('form[name='+formname+']').attr("onSubmit","return true").submit();
	    }else{
	      $('form[name='+formname+']').attr("onSubmit","return false");
	    }
	}
}

if(buttonname=="gallerystream"||buttonname=="editgallerystream"){
	var formname=$(this).attr("data-formdata");
	console.log("the form data - ",formname);
	var formit=submitCustom(formname);
	var formstatus=formit['formstatus'];
	var pointmonitor=formit['pointmonitor'];

	if(formstatus==true&&pointmonitor==false){
	    var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
	    // console.log(confirmed);
	    if(confirmed===true){
	      $('form[name='+formname+']').attr("onSubmit","return true").submit();
	    }else{
	      $('form[name='+formname+']').attr("onSubmit","return false");
	    }
	}
}

if(buttonname=="creategallery"){
var formstatus=true;
var inputname1=$('input[name=gallerytitle]');
var inputname2=$('textarea[name=gallerydetails]');

if(inputname1.val()==""){
window.alert('Please give the gallery title.');
	$(inputname1).addClass('error-class').focus();
	formstatus= false;
}else if(inputname2.val()==""){
window.alert('Please give the gallery details.');
	$(inputname2).addClass('error-class').focus();
	formstatus= false;
}
if(formstatus==true){
	var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
	console.log(confirmed);
	if(confirmed===true){
	$('form[name=galleryform]').submit();
	}
}
}

if(buttonname=="createtrendingtopic"){
var formstatus=true;
var inputname1=$('input[name=name]');
var inputname2=$('input[name=profpic]');
if(inputname1.val()==""){
window.alert('Please give the trending topic title.');
	$(inputname1).addClass('error-class').focus();
	formstatus= false;
}else if(inputname2.val()==""){
window.alert('Please give the topic cover photo.');
	$(inputname2).addClass('error-class').focus();
	formstatus= false;
}
if(formstatus==true){
	var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
	console.log(confirmed);
	if(confirmed===true){
	$('form[name=trendingtopicform]').submit();
	}
}
}

if(buttonname=="createtoptenplaylist"){
var formstatus=true;
var inputname1=$('input[name=title]');
var inputname2=$('input[name=artist]');
var inputname3=$('input[name=profpic]');
var inputname4=$('input[name=music]');
if(inputname1.val()==""){
window.alert('Please give the song title.');
	$(inputname1).addClass('error-class').focus();
	formstatus= false;
}else if(inputname2.val()==""){
window.alert('Please give the artiste\'s name.');
	$(inputname2).addClass('error-class').focus();
	formstatus= false;
}else if(inputname3.val()==""){
window.alert('Please give the album art/album cover photo.');
	$(inputname3).addClass('error-class').focus();
	formstatus= false;
}else if(inputname4.val()==""){
window.alert('Choose a mp3 audio file to upload.');
	$(inputname4).addClass('error-class').focus();
	formstatus= false;
}else if(inputname4.val()!==""){
var output=getExtension(inputname4.val());
console.log(output['type'],output['extension']);
if(output['extension']!=="mp3"||output['type']!=="audio"){
window.alert('Choose a valid mp3 audio file to upload.');
$(inputname4).addClass('error-class').focus();
formstatus= false;
}
}
if(formstatus==true){
	var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
	console.log(confirmed);
	if(confirmed===true){
	$('form[name=toptenplaylistform]').submit();
	}
}
}

if(buttonname=="createtopaudio"){
var formstatus=true;
var inputname1=$('input[name=title]');
var inputname4=$('input[name=music]');
if(inputname1.val()==""){
window.alert('Please give the episode title.');
	$(inputname1).addClass('error-class').focus();
	formstatus= false;
}/*else if(inputname2.val()==""){
window.alert('Please give the artiste\'s name.');
	$(inputname2).addClass('error-class').focus();
	formstatus= false;
}else if(inputname3.val()==""){
window.alert('Please give the album art/album cover photo.');
	$(inputname3).addClass('error-class').focus();
	formstatus= false;
}*/else if(inputname4.val()==""){
window.alert('Choose a mp3 audio file to upload.');
	$(inputname4).addClass('error-class').focus();
	formstatus= false;
}else if(inputname4.val()!==""){
var output=getExtension(inputname4.val());
console.log(output['type'],output['extension']);
if(output['extension']!=="mp3"||output['type']!=="audio"){
window.alert('Choose a valid mp3 audio file to upload.');
$(inputname4).addClass('error-class').focus();
formstatus= false;
}
}
if(formstatus==true){
	var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
	console.log(confirmed);
	if(confirmed===true){
	$('form[name=topaudioform]').submit();
	}
}
}

if(buttonname=="createadvert"){
var formstatus=true;
var inputname1=$('select[name=advertpage]');
var inputname2=$('select[name=adverttype]');
var inputname3=$('input[name=advertowner]');
var inputname4=$('input[name=adverttitle]');
var inputname5=$('input[name=advertlandingpage]');
var inputname6=$('input[name=profpic]');
if(inputname1.val()==""){
	window.alert('Please select the page this advert will show up on.');
	$(inputname1).addClass('error-class').focus();
	formstatus= false;
}else if(inputname2.val()==""){
window.alert('Choose an advert type');
	$(inputname2).addClass('error-class').focus();
	formstatus= false;
}else if(inputname3.val()==""){
	window.alert('State the owner of the advert');
	$(inputname3).addClass('error-class').focus();
	formstatus= false;
}else if(inputname4.val()==""){
	window.alert('Give the title for the advert');
	$(inputname4).addClass('error-class').focus();
	formstatus= false;
}else if(inputname5.val()==""){
	window.alert('Please give the complete url of the landing page for this advert');
	$(inputname5).addClass('error-class').focus();
	formstatus= false;
}else if(inputname6.val()==""){
window.alert('Choose a file to upload for this advert.');
	$(inputname6).addClass('error-class').focus();
	formstatus= false;
}else if(inputname6.val()!==""){
var output=getExtension(inputname6.val());
if(inputname2.val()=="videoadvert"){
if(output['extension']!=="mp4"||output['type']!==""){
window.alert('Choose a valid mp4 video file to upload.');
$(inputname6).addClass('error-class').focus();
formstatus= false;
}
}
// console.log(output['type'],output['extension']);
}
if(formstatus==true){
	var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
	console.log(confirmed);
	if(confirmed===true){
	$('form[name=advertform]').submit();
	}
}
}



if(buttonname=="submitintro"){
	var formstatus=true;
	tinyMCE.triggerSave();
	var inputname1=$('textarea[name=intro]');
	var inputname2=$('textarea[name=maintitle]');

	if(inputname1.val()==""){
	window.alert('Please provide the introductory part of this page.');
		$(inputname1).addClass('alerterror').focus();
		formstatus= false;
	}/*else if(inputname2.val()==""){
	window.alert('Please give the gallery details.');
		$(inputname2).addClass('alerterror').focus();
		formstatus= false;
	}*/
	if(formstatus==true){
		var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
		// console.log(confirmed);
		if(confirmed===true){
		$('form[name=introform]').attr("onSubmit","return true").submit();
		}
	}
}
/*generaldata module section*/
if(buttonname=="submituser"){
	var formstatus=true;
	tinyMCE.triggerSave();
	var inputname1=$('input[name=username]');
	var inputname2=$('input[name=password]');
	var inputname3=$('select[name=accesslevel]');
	var inputname4=$('input[name=fullname]');

	if(inputname1.val()==""){
	window.alert('Please provide the username.');
		$(inputname1).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname2.val()==""){
	window.alert('Please give the password.');
		$(inputname2).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname3.val()==""){
	window.alert('Please select the access level.');
		$(inputname2).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname4.val()==""){
	window.alert('Provide the name of this user.');
		$(inputname4).addClass('error-class').focus();
		formstatus= false;
	}
	if(formstatus==true){
		var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
		// console.log(confirmed);
		if(confirmed===true){
		$('form[name=userform]').attr("onSubmit","return true").submit();
		}
	}
}

if(buttonname=="submitcontent"){
	var formstatus=true;
	tinyMCE.triggerSave();
	var inputname1=$('form[name=contentform] input[name=contenttitle]');
	var inputname2=$('form[name=contentform] textarea[name=contentpost]');
	var inputname3=$('form[name=contentform] input[name=maintype]');

	if(inputname1.val()==""&&inputname3.val()!=="productservices"){
		window.alert('Please provide the introductory part of this page/post.');
		$(inputname1).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname2.val()==""&&inputname3.val()!=="productservices"){
	window.alert('Please provide the content for this entry.');
		$(inputname2).addClass('error-class').focus();
		formstatus= false;
	}
	if(formstatus==true){
		var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
		// console.log(confirmed);
		if(confirmed===true){
		$('form[name=contentform]').attr("onSubmit","return true").submit();
		}
	}
}
if(buttonname=="submitcontent2"){
	var formstatus=true;
	tinyMCE.triggerSave();
	var inputname1=$('form[name=contentform] input[name=contenttitle]');
	var inputname2=$('form[name=contentform] textarea[name=contentpost]');
	var inputname3=$('form[name=contentform] input[name=contentpic]');

	if(inputname1.val()==""){
	window.alert('Please provide the title of the unit/branch.');
		$(inputname1).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname2.val()==""){
	window.alert('Please provide the details for this unit/branch.');
		$(inputname2).addClass('error-class').focus();
		formstatus= false;
	}else if(inputname3.val()==""){
		window.alert('Please provide the image you want to use to represent this unit.');
		$(inputname3).addClass('error-class').focus();
		formstatus= false;
	}
	if(formstatus==true){
		var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
		// console.log(confirmed);
		if(confirmed===true){
		$('form[name=contentform]').attr("onSubmit","return true").submit();
		}
	}
}
if(buttonname=="submitcustom"){
	var formstatus=true;
	tinyMCE.triggerSave();
	// var parentel=$(this).parent().parent().attr("name");
	var truename=$(this).attr("data-formdata");
	if(truename===null||truename===undefined||truename===NaN){
		var truename="contentform";
	}
	var inputname0=$('form[name='+truename+'] input[name=errormap]');
	var inputname01=$('form[name='+truename+'] input[name=coverid]');
	var inputname1=$('form[name='+truename+'] input[name=contenttitle]');
	var inputname2=$('form[name='+truename+'] input[name=contentpic]');
	var inputname3=$('form[name='+truename+'] textarea[name=contentintro]');
	var inputname7=$('form[name='+truename+'] textarea[name=intro]');
	var inputname4=$('form[name='+truename+'] textarea[name=contentpost]');
	var inputname5=$('form[name='+truename+'] input[name=monitorcustom]');
	var inputname6=$('form[name='+truename+'] input[name=contentsubtitle]');
	if(inputname5.val()!==""&&inputname5.val()!=="nomonitor"&&typeof(inputname5)!=="undefined"){
		var pdata=inputname5.val();
		var pdataout=pdata.split(":");
		for(var i=0;i<pdataout.length;i++){
			if(pdataout[i]==1){
				if(inputname1.val()==""){

					// window.alert('Please provide the title of the entry.');
					if(typeof(inputname1.attr('data-errmsg'))!=="undefined"){
						errmsg=inputname1.attr('data-errmsg');
					}else{
						errmsg="Please provide the title of the entry."
					}
					raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
			        $("#mainPageModal").on("hidden.bs.modal", function () {
			            $(inputname1).addClass('error-class').focus();
			        });
					// $(inputname1).addClass('error-class').focus();
					formstatus= false;
					break;
				}
			}else if(pdataout[i]==5){
				if(inputname6.val()==""){
					if(typeof(inputname6.attr('data-errmsg'))!=="undefined"){
						errmsg=inputname6.attr('data-errmsg');
					}else{
						errmsg="Please provide the sub-title of the entry."
					}
					raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
			        $("#mainPageModal").on("hidden.bs.modal", function () {
			            $(inputname6).addClass('error-class').focus();
			        });
					formstatus= false;
					break;
				}
			}else if (pdataout[i]==2) {
				if(inputname2.val()==""&&inputname01.val()<1){
					if(typeof(inputname2.attr('data-errmsg'))!=="undefined"){
						errmsg=inputname2.attr('data-errmsg');
					}else{
						errmsg="Please provide the image of the entry."
					}
					raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
			        $("#mainPageModal").on("hidden.bs.modal", function () {
			            $(inputname2).addClass('error-class').focus();
			        });
					formstatus= false;
					break;
				}else if(inputname2.val()!==""){
					var slideout=getExtension(inputname2.val());
					if(slideout['type']!=="image"){
						var errmsg="Provide a valid Image file";
						raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
				        $("#mainPageModal").on("hidden.bs.modal", function () {
				            $(inputname2).addClass('error-class').focus();
				        });
						formstatus= false;
						break;
					}
				}
			}else if (pdataout[i]==3) {
				if(inputname3.val()==""){
					if(typeof(inputname3.attr('data-errmsg'))!=="undefined"){
						errmsg=inputname3.attr('data-errmsg');
					}else{
						errmsg="Please provide the intro of the entry."
					}
					raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
			        $("#mainPageModal").on("hidden.bs.modal", function () {
			            $(inputname3).addClass('error-class').focus();
			            var mcetester=$(inputname3).attr("data-mce");
			 			if(mcetester===null||mcetester===undefined||mcetester===NaN){ var mcetester="";}
			 			if(mcetester=="true"){
				            var mcid=$(inputname3).attr("id");
							tinyMCE.get(mcid).focus();
			 			}
			        });
					formstatus= false;
					break;
				}else if(inputname7.val()==""){
					if(typeof(inputname7.attr('data-errmsg'))!=="undefined"){
						errmsg=inputname7.attr('data-errmsg');
					}else{
						errmsg="Please provide the intro of the entry."
					}
					raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
			        $("#mainPageModal").on("hidden.bs.modal", function () {
			            $(inputname7).addClass('error-class').focus();
			            var mcetester=$(inputname7).attr("data-mce");
			 			if(mcetester===null||mcetester===undefined||mcetester===NaN){ var mcetester="";}
			 			if(mcetester=="true"){
				            var mcid=$(inputname7).attr("id");
							tinyMCE.get(mcid).focus();
			 			}
			        });
					formstatus= false;
					break;
				}
			}else if (pdataout[i]==4) {
				if(inputname4.val()==""){
					if(typeof(inputname4.attr('data-errmsg'))!=="undefined"){
						errmsg=inputname4.attr('data-errmsg');
					}else{
						errmsg="Please provide the content details of the entry."
					}
					raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
			        $("#mainPageModal").on("hidden.bs.modal", function () {
			            $(inputname4).addClass('error-class').focus();
			            var mcetester=$(inputname4).attr("data-mce");
			 			if(mcetester===null||mcetester===undefined||mcetester===NaN){ var mcetester="";}
			 			if(mcetester=="true"){
				            var mcid=$(inputname4).attr("id");
							tinyMCE.get(mcid).focus();
			 			}
			        });
					formstatus= false;
					break;
				}
			}

		}
	}else if(inputname5.val()=="nomonitor"){
		formstatus=true;
	}else /*if(inputname5.val()=="")*/{
		if(inputname1.val()==""){
			window.alert('Please provide the title of the entry.');
			$(inputname1).addClass('error-class').focus();
			formstatus= false;
		}if(inputname6.val()==""){
			window.alert('Please provide the sub-title of the entry.');
			$(inputname6).addClass('error-class').focus();
			formstatus= false;
		}else if(inputname3.val()==""){
			window.alert('Please provide the little details for this entry.');
			$(inputname3).addClass('error-class').focus();
			formstatus= false;
		}else if(inputname2.val()==""){
			window.alert('Please provide the image for this entry.');
			$(inputname2).addClass('error-class').focus();
			formstatus= false;
		}else if(inputname4.val()==""){
			window.alert('Please provide the content for this entry.');
			$(inputname4).addClass('error-class').focus();
			formstatus= false;
		}
	}
	if(formstatus==true){
		var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
		// console.log(confirmed);
		if(confirmed===true){
		$('form[name='+truename+']').attr("onSubmit","return true").submit();
		}
	}
}
if(buttonname=="createevent"){
	
}
if(buttonname=="submitcustomtwo"){
	// var tester="[1,2,3,4,5,6]";
	// get the current values in the square brackets
    // var out=/[^\[\]]+/i.exec(tester);
	var formstatus=true;
	var pointmonitor=false;
	tinyMCE.triggerSave();
	var formname=typeof($(this).attr('data-formdata'))!=="undefined"?$(this).attr('data-formdata'):"contentform";
	// var formname=typeof($('input[name=formdata]'))!=="undefined"?$('input[name=formdata]').val():"contentform";
	// obtain
	var errormap=$('form[name='+formname+'] input[name=errormap]');
	var multierror=$('form[name='+formname+'] input[name=multierrormap]');
	var extraformdata=$('form[name='+formname+'] input[name=extraformdata]');
	var inputname1=$('form[name='+formname+'] input[name=contenttitle]');
	var inputname2=$('form[name='+formname+'] input[name=contentpic]');
	var inputname3=$('form[name='+formname+'] textarea[name=contentintro]');
	var inputname4=$('form[name='+formname+'] textarea[name=contentpost]');
	var inputname5=$('form[name='+formname+'] input[name=monitorcustom]');
	// if(){}


	console.log("Running validator."," Running eformdata",extraformdata.val()," Running errormap",errormap.val());	
	if(typeof(extraformdata.val())!=="undefined"&&extraformdata.val()!==""&&typeof(errormap.val())!=="undefined"&&errormap.val()!==""){
		console.log("Running First steps..."," Form:- ",formname);	
		var efdstepone=extraformdata.val().split("<|>");
		var emstepone=errormap.val().split("<|>");
		if(efdstepone.length==emstepone.length){
			// group counter variable keeping track of disabled content
			var groupcounter=0;
			for(var i=0;i<efdstepone.length;i++){
				// begin division of current values
				// get current extraformdata
				var curgroup=efdstepone[i];
				
				// get current errormap
				var errgroup=emstepone[i];
				var errcontentout="";
				var finalgroup=[];
				var finalreqgroup=[];
				if(curgroup.indexOf("egroup|data")>-1){
					groupcounter++;					
					// multiple data focus section, here validation is done
					// on a grouped set of elements
	 				var subgroup=curgroup.split("egroup|data-:-");
	 				var suberrgroup=errgroup.split("egroup|data-:-");
	 				// console.log("Suberror group before: ",suberrgroup);
	 				var suberrgroupdata=/[^\[\]]+/i.exec(suberrgroup[1]);
	 				// suberrgroupdata=suberrgroupdata[1];
	 				// divide the subgroups further into fielddata and requirement data
	 				// also extradata block
	 				// for error checking content
	 				// console.log("Subgroup before split: ",subgroup," Replaced data", subgroup[0].replace(/\n*/g,""));
	 				subgroup=subgroup[1].split("-:-");
	 				var reqdata=subgroup[1];
	 				// console.log("Subgroup after split: ",subgroup);

	 				var fielddata=/[^\[\]]+/ig.exec(subgroup[0]);
	 				// console.log("Field data: ",fielddata[0].replace(/[\n\r]+/g,""));
	 				fielddata=fielddata[0].replace(/[\n\r]+/g,"").replace(/\s{1,}/g, '').split(">|<");
	 				
	 				// trap a third layer of content from the field data obtained
	 				// this represents the sub content that defines that the 
	 				// current entry is related to the value of another field
	 				var subfielddataone=[];
	 				if(subgroup.length>2){
	 					subfielddataone=/[^\[\]]+/ig.exec(subgroup[2]);
	 					subfielddataone=subfielddataone[0].replace(/[\n\r]+/g,"").replace(/\s{1,}/g, '').split(">|<");
	 				}
	 				// get the name of the current counter element from the array
					var ccelem=fielddata.shift();
					var valset="";
					var compulsoryoutput='';
					var valcount="";
					var ccount=0;
					
					// console.log("Countelement value: ",ccount,"Countelement: ",ccelem);
					if(ccelem!=="" &&isNumber($('form[name='+formname+'] input[name='+ccelem+']').val())){
						ccount=$('form[name='+formname+'] input[name='+ccelem+']').val();
			            valset=$('form[name='+formname+'] input[name='+ccelem+']').attr("data-valset");
			            valcount=$('form[name='+formname+'] input[name='+ccelem+']').attr("data-valcount");
						if(valset===null||valset===undefined||valset===NaN){
							valset="";
			            }
			            
			            if(valcount===null||valcount===undefined||valcount===NaN){
							valcount=1;
			            }else{
			            	valcount=Math.floor(valcount);
			            }
					}	
					
	 				
	 				// get the fieldata groups in the form of fieldname-|-fieldtype
	 				// fielddata=fielddata.split(">|<");
	 				// get the errormsg data
	 				suberrgroupdata=suberrgroupdata[0].split(">|<");
	 				// console.log("Suberror group after further split: ",suberrgroupdata);	 				
	 				// verify the nature of the validation requirements for the group

	 				// loop through each field set and get the fieldname seperate of its
	 				// type
	 				var dogroupfall="";
	 				var evalvardefns="";
	 				var evalcontent="";
	 				for(var x=0;x<fielddata.length;x++){
	 					// put current value in a local easy to use variable
	 					var curfielddata=fielddata[x].split("-|-");
	 					var fieldname=curfielddata[0];
	 					var fieldtype=curfielddata[1];
	 					// for edit forms, this will be used to test if the entry
	 					// being validated should be ignored or not
	 					// by default, the delete element has only one possible value hence
	 					// validation can commence when it has no value
	 					var conditionstatusblock="";
	 					
	 					//tells of the field expects a valid kind of input
	 					// e.g 'image' would signify any valid image is passed
	 					// office for valid office files , video, audio
	 					// pdf for pdf e.t.c 
	 					var fieldentrytype="";
	 					// tests against a valid extension for the fieldentrytype 
	 					var fieldextension="";
	 					// variable holds further validation content for current field
	 					var extendvalidationblock="";

	 					var vcblock_main="";
	 					var vcinit_main="";
	 					
	 					// variable for holding filetype check and extension validation data
	 					var preinit="";
	 					if(fieldtype.indexOf("|")>-1){
	 						// this is done for mainly file based fields that need content checked
	 						var fieldtypedata=fieldtype.split("|");
	 						fieldtype=fieldtypedata[0];
	 						fieldentrytype=fieldtypedata[1];
	 						var fecblock="";
	 						if(fieldtypedata.length>2){
		 						fieldextension=fieldtypedata[2];
		 						if(fieldextension.indexOf(",")>-1){
		 							var efetype=fieldextension.split(",");
		 							for(var tt=0;tt<efetype.length;tt++){
		 								curfetype=efetype[tt];
			 							fecblock+=fecblock==""?'||(checkout[\'extension\']!=="'+curfetype+'"':'&&checkout[\'extension\']!=="'+curfetype+'"';
			 							if (tt==efetype.length-1) {
			 								fecblock+=")";
			 							};
		 							}
		 						}else{
		 							fecblock='||checkout[\'extension\']!=="'+fieldextension+'"';
		 						}
		 						// console.log(fieldextension,fecblock);

	 						}
	 						// create attachment condition block
	 						preinit='if('+fieldname+'.val()!==""&&formstatus==true&&pointmonitor==false){'+
	 								'var checkout=getExtension('+fieldname+'.val());'+
									'if(checkout[\'type\']!=="'+fieldentrytype+'"'+fecblock+'){'+
									'	window.alert(checkout[\''+fieldentrytype+'errormsg\']);'+
									'	$('+fieldname+').addClass(\'error-class\').focus();'+
									'	formstatus= false;'+
									'	pointmonitor=true;'+
									'}'+
 								'}';
	 					}
	 					var errmsgout=suberrgroupdata[x].replace(/[\n\r]+/g,"").replace(/\s{2,}/g, ' ');
	 					finalgroup[x]=[];
	 					// store the key as a value with the field name as the value
	 					finalgroup[''+fieldname+'']=x;
	 					finalgroup[x]['fieldname']=fieldname;
	 					finalgroup[x]['fieldtype']=fieldtype;
	 					finalgroup[x]['fieldentrytype']=fieldentrytype;
	 					finalgroup[x]['fieldextension']=fieldextension;
	 					finalgroup[x]['fieldcextra']=preinit;
	 					finalgroup[x]['errmsg']=errmsgout;
	 					finalgroup[x]['errtestdata']="";
	 					if(fieldentrytype!==""){
	 						// check to see if the entrype is a file
	 						vcinit_main+='var '+fieldname+'_edittype=$('+fieldname+').attr("data-edittype");\r\n'+
	 							'if('+fieldname+'_edittype===null||'+fieldname+'_edittype===undefined||'+fieldname+'_edittype===NaN){\r\n'+
								'	'+fieldname+'_edittype="";\r\n'+
					            '}'; 
 							finalgroup[x]['errtestdata']='&&'+fieldname+'_edittype==""';
	 					}
	 					// carries validation content at the errtestmsg level

	 					// carries validation variable initalisation and condition 
	 					// block based information
	 					finalgroup[x]['vcblock']=vcblock_main;
	 					finalgroup[x]['vcinit']=vcinit_main;

	 				}

	 				// create compulsory data set and force them into the vcinit portion
	 				// of the finalgroup data set
	 				if(valset!==""){
	 					var valcontent=valset.split(",");
		 				var fc=1;
		 				var cvaltotalset='';
						cvaltotalset+='var compulsorymsgout="Please, there is a group set of data with expected number of entries \\"'+valcount+'\\" that has not been completed.'+
 						'<br> After you close this error field the group would be focused on. Please make sure you fill in data in the group-set accordingly.<br>'+
 						'For example, if the expected number of entries is \'2\', this means that you must provide at least 2 entries for the set, and they must be provided '+
 						'in direct order in the form, so skipping say set 2 to fill set 3 will create an error'+
 						'even though 2 entries (Set 1 and Set 3) have been provided.<br> Hope this helps, if you do not understand, contact the developer of this application for '+
 						'clarification";'+
 						'var cparelemcount=$("form[name='+formname+'] input[name='+ccelem+']").val();';
	 					
		 				var cvalinitset='';
		 				var brco='';
	 					for(var wi = 0; wi < Math.floor(valcount); wi++){
				 			var pt=wi+1;
		 					var cvalcoset='';
			 				for (var vi = 0; vi < valcontent.length; vi++) {
			 					if(isNumber(Math.floor(valcontent[vi]))&&Math.floor(valcontent[vi])>0){
				 					var cgd=Math.floor(valcontent[vi])-1;
				 					var tv=vi+1;
				 					if(vi==0){
				 						// set the focus group element counter
				 						fc=tv;
				 					}
				 					cvalinitset+='var cvalinitset'+tv+''+pt+'=$("form[name='+formname+'] '+finalgroup[cgd]['fieldtype']+'[name='+finalgroup[cgd]['fieldname']+''+pt+']");var cvalinitsetcval'+tv+''+pt+'="";if(cvalinitset'+tv+''+pt+'===null||cvalinitset'+tv+''+pt+'===undefined||cvalinitset'+tv+''+pt+'===NaN){ cvalinitsetcval="";}else{ cvalinitsetcval'+tv+''+pt+'=cvalinitset'+tv+''+pt+'.val();}';
				 					cvalcoset+=vi==0?'cvalinitsetcval'+tv+''+pt+'==""':'&&cvalinitsetcval'+tv+''+pt+'==""';

			 					}
			 				};
			 				// console.log("Initset - ",cvalinitset," Condition set - ",cvalcoset," Counter - ",pt);
			 				if((wi==0&&brco==""&&cvalcoset!=="")||(wi>0&&brco==""&&cvalcoset!=="")){
			 					brco="on";
		 						cvaltotalset+='if('+cvalcoset+'){'+
			 						'	formstatus=false;pointmonitor=true; raiseMainModal(\'Form error!!\', compulsorymsgout, \'fail\');'+
			 						'	$("#mainPageModal").on("hidden.bs.modal", function () {'+
			 						'		var mcetester=$(cvalinitset'+fc+''+pt+').attr("data-mce");'+
			 						'		if(mcetester===null||mcetester===undefined||mcetester===NaN){ mcetester="";}'+
			 						'		 if(mcetester=="true"){'+
									'			var mcid=$(cvalinitset'+fc+''+pt+').attr("id");'+
									'			tinyMCE.get(mcid).focus();/*tinymce.execCommand(\'mceFocus\',false,mcid);*/'+
									'		}else{'+
									'			$(cvalinitset'+fc+''+pt+').addClass(\'error-class\').focus();'+
									'		}'+
									'	});	'+
			 						'}';
			 				}else if(wi>0&&brco=="on"&&cvalcoset!==""){
			 					cvaltotalset+='else if('+cvalcoset+'){'+
			 						'	formstatus=false;pointmonitor=true; raiseMainModal(\'Form error!!\', compulsorymsgout, \'fail\');'+
			 						'	$("#mainPageModal").on("hidden.bs.modal", function () {'+
			 						'		var mcetester=$(cvalinitset'+fc+''+pt+').attr("data-mce");'+
			 						'		if(mcetester===null||mcetester===undefined||mcetester===NaN){ mcetester="";}'+
			 						'		 if(mcetester=="true"){'+
									'			var mcid=$(cvalinitset'+fc+''+pt+').attr("id");'+
									'			tinyMCE.get(mcid).focus();/*tinymce.execCommand(\'mceFocus\',false,mcid);*/'+
									'		}else{'+
									'			$(cvalinitset'+fc+''+pt+').addClass(\'error-class\').focus();'+
									'		}'+
									'	});	'+
			 						'}';
			 				}
		 					var penultm8=pt-1;
	 					}
	 					// final cvaltotalset, here, the count of entries is tallied and 
	 					// an error raised if the count doesnt match the expected number of entries
	 					if(cvaltotalset!==""){
	 						cvaltotalset=''+cvalinitset+''+cvaltotalset+'';
				 			cvaltotalset+='else if(cparelemcount<'+valcount+'){'+
			 						'	var curerror="Sorry, the minimum number of expected entries is: '+valcount+' current detected is: \"+cparelemcount+\" Please add more entries"; formstatus=false;pointmonitor=true; raiseMainModal(\'Form error!!\', curerror, \'fail\');'+
			 						'	$("#mainPageModal").on("hidden.bs.modal", function () {'+
									'			$(cvalinitset'+fc+''+penultm8+').parent().focus();'+
									'	});	'+
			 						'}';
				 			// console.log("cvaltotalset - ",cvaltotalset);
	 						
	 					}

		 				// add the compulsory section to the vcinit portion of the
		 				// first fielddata element in the finalgroup array
	 					finalgroup[0]['vcinit']+=cvaltotalset;
	 				}


	 				// test for subfield data and proceed to craete array of condition
	 				// add-on content for the validation fields, using the target fields 
	 				// name or group data
	 				var subtests=[];
	 				if(subfielddataone!==""&&subfielddataone.length>0){
	 					for(var l=0;l<subfielddataone.length;l++){
	 						var subfielddata=subfielddataone[l].replace(/[\n\r]+/g,"").replace(/\s{2,}/g, ' ').split("-|-");
	 						// get the type for the current group set
	 						var type=subfielddata.shift();
	 						if(type!=="group"){
		 						var telemname=subfielddata[0];
		 						var telemtype=subfielddata[1];
		 						var telemvalue=subfielddata[2];
		 						var tarelemname="";
		 						var curcondition="";
		 						if(type==""||type.toLowerCase()=="all"){
		 							for (var m = 0; m < finalgroup.length; m++) {
		 								finalgroup[m]['vcinit']+='var '+telemname+'=$("form[name='+formname+'] '+telemtype+'[name='+telemname+']");';
		 								var c_all=telemvalue==""||telemvalue.indexOf('*any*')>-1?"!":"";
		 								// makes sure that the telemvalue field equates empty on
		 								// encountering *null* keyword as its value
			 							telemvalue==""||telemvalue.indexOf('*any*')>-1?telemvalue="":telemvalue=telemvalue;
		 								telemvalue=="*null*"?telemvalue="":telemvalue=telemvalue;
		 								finalgroup[m]['vcblock']+="&&"+telemname+".val()"+c_all+"==\""+telemvalue+"\"";
		 								// console.log("current count - ",m," curvblock - ",finalgroup[m]['vcblock'],"curvcinit - ",finalgroup[m]['vcinit']);
		 							};
		 						}else if(type=="single"){
			 						tarelemname=subfielddata[3];
			 						if(tarelemname!==""){
			 							var ckey="";
			 							ckey=finalgroup[''+tarelemname+''];
			 							if(finalgroup[ckey][''+tarelemname+'']){
			 								finalgroup[ckey]['vcinit']+='var '+telemname+'=$("form[name='+formname+'] '+telemtype+'[name='+telemname+']");';
			 								var c_all=telemvalue==""||telemvalue.indexOf('*any*')>-1?"!":"";
			 								telemvalue==""||telemvalue.indexOf('*any*')>-1?telemvalue="":telemvalue=telemvalue;
			 								telemvalue=="*null*"?telemvalue="":telemvalue=telemvalue;
			 								finalgroup[ckey]['vcblock']+="&&"+telemname+".val()"+c_all+"==\""+telemvalue+"\"";
		 									// console.log("current key - ",ckey," curvblock - ",finalgroup[ckey]['vcblock'],"curvcinit - ",finalgroup[ckey]['vcinit']);
			 							}
			 						}else{
			 							var tarelemerr='Sub-validation group error discovered where type is "Single", and validation element is "<b>'+telemname+'</b>", in error map';
			 							raiseMainModal('ED System Failure!!', tarelemerr, 'fail');
			 							formstatus=false;
			 							break;
			 						}
		 						}

	 						}else if(type=="group"){
	 							for(var l2=3;l2<subfielddata.length;l2+=3){
	 								var telemname=subfielddata[l2];
			 						var telemtype=subfielddata[l2+1];
			 						var telemvalue=subfielddata[l2+2];
			 						var tarelemname="";
			 						var curcondition="";
			 						for (var m = 0; m < finalgroup.length; m++) {
		 								finalgroup[m]['vcinit']+='var '+telemname+'=$("form[name='+formname+'] '+telemtype+'[name='+telemname+']");';
		 								var c_all=telemvalue==""||telemvalue.indexOf('*any*')>-1?"!":"";
		 								// makes sure that the telemvalue field equates empty on
		 								// encountering *null* keyword as its value
			 							telemvalue==""||telemvalue.indexOf('*any*')>-1?telemvalue="":telemvalue=telemvalue;
		 								telemvalue=="*null*"?telemvalue="":telemvalue=telemvalue;
		 								finalgroup[m]['vcblock']+="&&"+telemname+".val()"+c_all+"==\""+telemvalue+"\"";
		 							};
	 							}
	 						}
	 					}
	 				}
	 				// sort requirements based on group fall data
	 				// console.log("Required data: ",reqdata);
	 				if(reqdata.indexOf("groupfall")>-1){
	 					dogroupfall="true";
	 					reqdata=reqdata.split("groupfall");//remove groupfall
	 					reqdata=/[^\[\]]+/ig.exec(reqdata[1]);
	 					// console.log("the new req data: ",reqdata);
	 					reqdata=reqdata[0].split(",");// get inidividual data groups
	 					// console.log("the new req data after split: ",reqdata," length: ",reqdata.length);
	 				}else{
	 					reqdata=/[^\[\]]+/ig.exec(reqdata);
	 					// console.log("the new req data: ",reqdata);
	 					reqdata=reqdata[0].split(",");// get inidividual data groups
	 					// console.log("the new req data after split: ",reqdata);

	 				}

		 			
	 				
	 				// create block control for the multiple validation entries
	 				if(ccount>0){
	 					var extendederrorblock="";
	 					var extendedtestblock="";
	 					// allows the current set of entries to fail validation
	 					var gstatus=$('form[name='+formname+'] select[name=group'+groupcounter+'_status'+x+']');
	 					// console.log("cur status test- ",gstatus);
	 					if(typeof(gstatus)!=="undefined"&&(gstatus.val()=="inactive"||gstatus.val()=="yes")){
	 						// create an expression that always evaluates as false
	 						extendederrorblock='&&1<0';
	 					}else{

	 					}

	 					// create condition blocks for handling multiple form element validation
		 				for(var c=0;c<reqdata.length;c++){
		 					var conditionblock="";
		 					var conderrorblock="";
		 					// for initialisation of sub validation field section variables
		 					//  and corresponding condition block output
		 					var preinit="";
		 					var vcinit="";
		 					var vcblock="";
		 					var compulsoryblock="";
		 					if(dogroupfall=="true"){
								var curreq=reqdata[c].split('-');
								// console.log("Current requirements: ",curreq);
			 					for(var ct=0;ct<curreq.length;ct++){
			 						id=curreq[ct]>0?curreq[ct]-1:curreq[ct];
			 						vcblock=finalgroup[id]['vcblock'];
			 						if(vcinit==""){
			 							vcinit=finalgroup[id]['vcinit'];

			 						}else if(finalgroup[id]['vcinit']!==""&&vcinit.indexOf(''+finalgroup[id]['vcinit']+'')<0){
			 							// avoid repeating content in the init section
			 							vcinit+=finalgroup[id]['vcinit'];
			 						}
			 						if(valset!==""){

			 						}
			 						preinit=finalgroup[id]['fieldcextra'];

		 							// console.log("curvblock valpoint- ",finalgroup[0]['vcblock'],"curvcinit valpoint - ",finalgroup[0]['vcinit']);
									// console.log("Current id: ",id)," Current ct: ",curreq[ct];

			 						if(ct==0){
			 							conditionblock=''+finalgroup[id]['fieldname']+'.val()==""&&formstatus==true&&pointmonitor==false&&curselect==""'+vcblock+'';
			 							conderrorblock='var edittype='+finalgroup[id]['fieldname']+'.attr("data-form-edit");if(edittype===null||edittype===undefined||edittype===NaN){var edittype=""};/*console.log("Edittype1 - ",edittype," Current Value - ",'+finalgroup[id]['fieldname']+'.val());*/var errtestmsg="'+finalgroup[id]['errmsg']+'";if(errtestmsg!=="NA"&&edittype!=="true"'+finalgroup[id]['errtestdata']+'){formstatus=false; pointmonitor=true; raiseMainModal(\'Form error!!\', \''+finalgroup[id]['errmsg']+'\', \'fail\');'+
									        '$("#mainPageModal").on("hidden.bs.modal", function () {'+
								            	'		var mcetester=$('+finalgroup[id]['fieldname']+').attr("data-mce");'+
					 							'		if(mcetester===null||mcetester===undefined||mcetester===NaN){ mcetester="";}'+
									            '		 if(mcetester=="true"){'+
												'			var mcid=$('+finalgroup[id]['fieldname']+').attr("id");'+
												'			tinyMCE.get(mcid).focus();/*tinymce.execCommand(\'mceFocus\',false,mcid);*/'+
												'		}else{'+
												'			$('+finalgroup[id]['fieldname']+').addClass(\'error-class\').focus();'+
												'		}'
									        +'});}';
			 						}else if(ct==1&&Math.floor(curreq.length)<=2){
			 							// in the event there are only two entries
			 							conditionblock+='&&'+finalgroup[id]['fieldname']+'.val()!==""&&formstatus==true&&pointmonitor==false&&curselect==""'+vcblock+'';

			 						}else if(ct==1&&Math.floor(curreq.length)>2){
			 							// in the event of more than two entries, open the bracket for the entries
			 							conditionblock+='&&('+finalgroup[id]['fieldname']+'.val()!==""&&formstatus==true&&pointmonitor==false&&curselect==""'+vcblock+'';

			 						}else if(Math.floor(curreq.length)>Math.floor(ct)+1&&Math.floor(curreq.length)>2){
			 							conditionblock+='||'+finalgroup[id]['fieldname']+'.val()!==""&&formstatus==true&&pointmonitor==false&&curselect==""'+vcblock+'';

			 						}else if(Math.floor(curreq.length)==Math.floor(ct)+1&&Math.floor(curreq.length)>2){
			 							conditionblock+='||'+finalgroup[id]['fieldname']+'.val()!=="")&&formstatus==true&&pointmonitor==false&&curselect==""'+vcblock+'';
			 						}else{

			 						}
			 					}
		 					}else{
		 						// do plain waterfall check on requireddata array content
			 					id=reqdata[c]-1>0?reqdata[c]-1:0;
			 					vcblock=finalgroup[id]['vcblock'];
		 						if(vcinit==""){
		 							vcinit=finalgroup[id]['vcinit'];

		 						}else if(finalgroup[id]['vcinit']!==""&&vcinit.indexOf(''+finalgroup[id]['vcinit']+'')<0){
		 							// avoid repeating content in the init section
		 							vcinit+=finalgroup[id]['vcinit'];
		 						}
		 						// console.log("the final group value: ",finalgroup[id]," the type of final group",typeof(finalgroup[id]));
			 					if(typeof(finalgroup[id])!=="undefined"&&finalgroup[id]['fieldname']!==""&&finalgroup[id]['fieldtype']!==""){
			 						conditionblock=''+finalgroup[id]['fieldname']+'.val()==""&&formstatus==true&&pointmonitor==false&&curselect==""'+vcblock+'';
		 							conderrorblock='var edittype='+finalgroup[id]['fieldname']+'.attr("data-form-edit");if(edittype===null||edittype===undefined||edittype===NaN){var edittype=""};/*console.log("Edittype2 - ",edittype," Current Value - ",'+finalgroup[id]['fieldname']+'.val())*/;var errtestmsg="'+finalgroup[id]['errmsg']+'";if(errtestmsg.toLowerCase()!=="na"&&edittype!=="true"){formstatus=false; pointmonitor=true; console.log(formstatus,'+finalgroup[id]['fieldname']+');raiseMainModal(\'Form error!!\', \''+finalgroup[id]['errmsg']+'\', \'fail\');'+
								        '$("#mainPageModal").on("hidden.bs.modal", function () {'+
								        '		var mcetester=$('+finalgroup[id]['fieldname']+').attr("data-mce");'+
					 					'		if(mcetester===null||mcetester===undefined||mcetester===NaN){ mcetester="";}'+
							            '		 if(mcetester).attr("data-mce")=="true"){'+
										'			var mcid=$('+finalgroup[id]['fieldname']+').attr("id");'+
										'			tinyMCE.get(mcid).focus();/*tinymce.execCommand(\'mceFocus\',false,mcid);*/'+
										'		}else{'+
										'			$('+finalgroup[id]['fieldname']+').addClass(\'error-class\').focus();'+
										'		}'
							        +'});}';
			 					}
		 					}
		 					// var valtotal='if(){}';
		 					// console.log("errcontentout value: ",errcontentout," cur block: ",conditionblock," condition error: ",conderrorblock," cur count: ",c," reqdata length: ",reqdata.length," errcontentout typeof", typeof(errcontentout))
		 					if(errcontentout==""){
		 						if(conditionblock!==""&&conderrorblock!==""){
			 						errcontentout=''+vcinit+'if('+conditionblock+'){'+
			 						''+conderrorblock+''+
			 						'}'+preinit+'';
		 						}
		 					}else if(errcontentout!==""){
		 						// console.log("conderrorblock value: ",conderrorblock);
		 						// console.log("errcontentout value: ",errcontentout);
		 						if(conditionblock!==""&&conderrorblock!==""){
		 							errcontentout+='else if('+conditionblock+'){'+
			 						''+conderrorblock+''+
			 						'}'+preinit+'';
			 					}
		 					}
		 				}
	 					// create the formelment variable definitions
		 				for(var cx=0;cx<ccount;cx++){
		 					evalvardefns="";
		 					var cto=cx+1;
	 						var gstatus=$('form[name='+formname+'] select[name=group'+groupcounter+'_status'+cto+']');
		 					// console.log("cur status test- ",gstatus);
		 					if(typeof(gstatus)!=="undefined"&&(gstatus.val()=="inactive"||gstatus.val()=="yes")){
		 						// create an expression that always evaluates as false
		 						extendederrorblock=' var curselect=$(\'form[name='+formname+'] select[name=group'+groupcounter+'_status'+cto+']\').val();';
		 					}else{
		 						extendederrorblock='var curselect="";';
		 					}
		 					evalvardefns+=extendederrorblock;
		 					for(var v=0;v<finalgroup.length;v++){
		 						var p=cx+1;
		 						// create the variable instances for the eval section
		 						evalvardefns+=" var "+finalgroup[v]['fieldname']+"="
		 						+"$('form[name="+formname+"] "+finalgroup[v]['fieldtype']+"[name="+finalgroup[v]['fieldname']+""+p+"]');";
		 					}
	 						evalcontent=''+evalvardefns+''+errcontentout+'';
	 						// console.log("Eval count group data: ",cx," Eval Data", evalcontent);
		 					eval(evalcontent);
		 					// this ensures the loop stops running completely
		 					// when a condition is not met
		 					if(formstatus==false){
		 						break;
		 					}
		 				}
		 				
	 				}

				}else{
					// console.log(typeof(curgroup));
					if(typeof(curgroup)!=="undefined"&&curgroup!==""){
			 			var errcontentout="";
			 			var evalcontent="";
			 			var evalvardefns="";
			 			var vcinit="";
			 			var vcblock="";
			 			var preinit="";
						var fielddata=curgroup.split("-:-");
						var fieldname=fielddata[0].replace(/[\n\r]*/g,"").replace(/\s{1,}/g, '');
						var fieldtype=fielddata[1].replace(/[\n\r]*/g,"").replace(/\s{1,}/g, '');
						var fieldentrytype="";
						var fieldextension="";
						var errtestdata="";

						if(fieldtype.indexOf("|")>-1){
	 						var fieldtypedata=fieldtype.split("|");
	 						fieldtype=fieldtypedata[0];
	 						fieldentrytype=fieldtypedata[1];
	 						var fecblock="";
	 						if(fieldtypedata.length>2){
		 						fieldextension=fieldtypedata[2];
		 						if(fieldextension.indexOf(",")>-1){
		 							var efetype=fieldextension.split(",");
		 							console.log(efetype);
		 							for(var tt=0;tt<efetype.length;tt++){
		 								curfetype=efetype[tt];
			 							fecblock+=fecblock==""?'||(checkout[\'extension\']!=="'+curfetype+'"':'&&checkout[\'extension\']!=="'+curfetype+'"';
			 							if (tt==efetype.length-1) {
			 								fecblock+=")";
			 							};
		 							}
		 						}else{
		 							fecblock='||checkout[\'extension\']!=="'+fieldextension+'"';
		 						}
	 						}
		 					// console.log(fieldextension,fecblock);
	 						if(fieldentrytype!==""){
		 						// check to see if the entrype is a file
		 						vcinit+='var '+fieldname+'_edittype=$('+fieldname+').attr("data-edittype");\r\n'+
		 							'if('+fieldname+'_edittype===null||'+fieldname+'_edittype===undefined||'+fieldname+'_edittype===NaN){\r\n'+
									'	'+fieldname+'_edittype="";\r\n'+
						            '}'; 
	 							errtestdata='&&'+fieldname+'_edittype==""';
		 					}
	 						// create attachment condition block
	 						preinit='if('+fieldname+'.val()!==""&&formstatus==true&&pointmonitor==false){'+
	 								'var checkout=getExtension('+fieldname+'.val());'+
									'if(checkout[\'type\']!=="'+fieldentrytype+'"'+fecblock+'){'+
									'	window.alert(checkout[\''+fieldentrytype+'errormsg\']);'+
									'	$('+fieldname+').addClass(\'error-class\').focus();'+
									'	formstatus= false;'+
									'	pointmonitor= true;'+
									'}'+
 								'}';
	 					}
		 				
		 				// trap a third layer of content from the field data obtained
		 				// this represents the sub content that defines that the 
		 				// current entry is related to the value of another field
		 				var subfielddataone=[];
		 				if(fielddata.length>2){
		 					subfielddataone=/[^\[\]]+/ig.exec(fielddata[2]);
		 					subfielddataone=subfielddataone[0].replace(/[\n\r]+/g,"").replace(/\s{1,}/g, '').split(">|<");
		 					// console.log("subfielddataparent - ",fielddata[2]," subfielddataone - ",subfielddataone);
		 				}

		 				// test for subfield data and proceed to craete array of condition
		 				// add-on content for the validation fields, using the target fields 
		 				// name or group data
		 				var subtests=[];
		 				if(subfielddataone!==""&&subfielddataone.length>0){
		 					for(var l=0;l<subfielddataone.length;l++){
		 						var subfielddata=subfielddataone[l].replace(/[\n\r]+/g,"").replace(/\s{2,}/g, ' ').split("-|-");
		 						// get the type for the current group set
		 						var type=subfielddata.shift();
		 						if(type!=="group"){
			 						var telemname=subfielddata[0];
			 						var telemtype=subfielddata[1];
			 						var telemvalue=subfielddata[2];
			 						var tarelemname="";
			 						var curcondition="";
			 						if(type==""||type.toLowerCase()=="all"){
			 							for (var m = 0; m < finalgroup.length; m++) {
			 								vcinit+='var '+telemname+'=$("form[name='+formname+'] '+telemtype+'[name='+telemname+']");';
			 								var c_all=telemvalue==""||telemvalue.indexOf('*any*')>-1?"!":"";
			 								// makes sure that the telemvalue field equates empty on
			 								// encountering *null* keyword as its value
			 								telemvalue==""||telemvalue.indexOf('*any*')>-1?telemvalue="":telemvalue=telemvalue;
			 								telemvalue=="*null*"?telemvalue="":telemvalue=telemvalue;
			 								vcblock+="&&"+telemname+".val()"+c_all+"==\""+telemvalue+"\"";
			 							};
			 						}else if(type=="single"){
				 						tarelemname=subfielddata[3];
				 						if(tarelemname!==""){
				 							/*var ckey="";
				 							ckey=finalgroup[''+tarelemname+''];*/

				 								vcinit+='var '+telemname+'=$("form[name='+formname+'] '+telemtype+'[name='+telemname+']");';
				 								var c_all=telemvalue==""||telemvalue.indexOf('*any*')>-1?"!":"";
				 								telemvalue=="*null*"?telemvalue="":telemvalue=telemvalue;
				 								vcblock+="&&"+telemname+".val()"+c_all+"==\""+telemvalue+"\"";
				 							
				 						}else{
				 							var tarelemerr='Sub-validation group error discovered where type is "Single", and validation element is "<b>'+telemname+'</b>", in error map';
				 							raiseMainModal('ED System Failure!!', tarelemerr, 'fail');
				 							formstatus=false;
				 							break;
				 						}
			 						}
			 						// console.log("curvblock - ",vcblock," curvinit - ",vcinit);
		 						}else if(type=="group"){
			 						// console.log("subfielddata length - ",subfielddata.length," subfielddata - ",subfielddata);
		 							for(var l2=0;l2<subfielddata.length;l2+=3){
		 								var telemname=subfielddata[l2];
				 						var telemtype=subfielddata[l2+1];
				 						var telemvalue=subfielddata[l2+2];
				 						var tarelemname="";
				 						var curcondition="";
			 								vcinit+='var '+telemname+'=$("form[name='+formname+'] '+telemtype+'[name='+telemname+']");';
			 								var c_all=telemvalue==""||telemvalue.indexOf('*any*')>-1?"!":"";
			 								// makes sure that the telemvalue field equates empty on
			 								// encountering *null* keyword as its value
			 								telemvalue==""||telemvalue.indexOf('*any*')>-1?telemvalue="":telemvalue=telemvalue;
			 								telemvalue=="*null*"?telemvalue="":telemvalue=telemvalue;
			 								vcblock+="&&"+telemname+".val()"+c_all+"==\""+telemvalue+"\"";
			 								// console.log("curvblock - ",vcblock," curvinit - ",vcinit);
		 							}
		 						}
		 					}
		 				}
						var errdata=errgroup.split("-:-");
			            var errdata1=errdata[1];
			            if(errdata[1]===null||errdata[1]===undefined||errdata[1]===NaN){
			              var errdata1="";
			            }
			            var errmsgout=typeof(errdata1)!=="undefined"&&errdata1!==""?errdata[1].replace(/[\n\r]*/g,"").replace(/\s{1,}/g, ' '):"";
						// create the variable instances for the eval section
						// and make sure the field is not chosen if the NA
						// errmsg is present meaning that the field is not required
						if(errmsgout.toLowerCase()!=="na"||errmsgout!=="NA"||errmsgout!==" NA"||errmsgout!==" NA "||errmsgout!=="NA "){
							evalvardefns+="var "+fieldname+"="
							+"$('form[name="+formname+"] "+fieldtype+"[name="+fieldname+"]');/*console.log(\" Current Value - \","+fieldname+".val());*/";
							conditionblock=''+fieldname+'.val()==""&&formstatus==true&&pointmonitor==false'+vcblock+'';
							conderrorblock='var edittype='+fieldname+'.attr("data-form-edit");if(edittype===null||edittype===undefined||edittype===NaN){var edittype=""};'+
							'console.log("Element - ",$(fieldname));/*console.log("Edittype3 - ",edittype);*/var errtestmsg="'+errmsgout+'";if(errtestmsg!=="NA"&&edittype!=="true"'+errtestdata+'){formstatus=false; pointmonitor=true; raiseMainModal(\'Form error!!\', \''+errmsgout+'\', \'fail\');'+
						        '$("#mainPageModal").on("hidden.bs.modal", function () {'+
						        '		var mcetester=$('+fieldname+').attr("data-mce");'+
					 			'		if(mcetester===null||mcetester===undefined||mcetester===NaN){ mcetester="";}'+
					            '		 if(mcetester=="true"){'+
								'			var mcid=$('+fieldname+').attr("id");/*console.log("tmcid - ",tinyMCE.get(mcid),"mcid - ",mcid);*/'+
								'			tinyMCE.get(mcid).focus();/*tinymce.execCommand(\'mceFocus\',false,mcid);*/'+
								'			/*tinyMCE.getInstanceById(mcid).focus();*/'+
								'		}else{'+
								'			$('+fieldname+').addClass(\'error-class\').focus();'+
								'		}'+
						        '});}';
						}
						if(errcontentout==""){
							if(conditionblock!==""&&conderrorblock!==""){
	 						errcontentout=''+vcinit+'if('+conditionblock+'){'+
	 						''+conderrorblock+''+
	 						'}'+preinit+'';
								
							}
						}
						evalcontent=''+evalvardefns+''+errcontentout+'';
						// console.log("Eval Data single", evalcontent);
						eval(evalcontent);
						// this ensures the loop stops running completely
	 					// when a condition is not met
	 					if(formstatus==false){
	 						break;
	 					}
					}else{
						errmsg='Missing form data detected, possible malformed validation triggers.';
						raiseMainModal('Parse error!!', ''+errmsg+'', 'fail');
						formstatus=false;
						break;
					}
				}

			}
		}else{
			errmsg='Extra form data and errormap do not match in length.';
			raiseMainModal('Parse error!!', ''+errmsg+'', 'fail');
			formstatus=false;
			// break;
		}

	};





	if(formstatus==true&&pointmonitor==false){
		var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
		// console.log(confirmed);
		if(confirmed===true){
			$('form[name='+formname+']').attr("onSubmit","return true").submit();
		}else{
			$('form[name='+formname+']').attr("onSubmit","return false");
		}
	}
}
if(buttonname=="teamentrysubmit"){
	var formstatus=true;	
	var inputname1=$('input[name=curbannerslidecount]');
	var inputname2=$('input[name=slide1]');
	var inputname3=$('input[name=fullname1]');
	var inputname4=$('input[name=position1]');
	var inputname5=$('input[name=details1]');
	var inputname11=$('input[name=qualifications1]');
	if(inputname2.val()==""){
		window.alert('Please provide the photograph.');
		$(inputname2).addClass('alerterror').focus();
		formstatus= false;
		// pointmonitor=true;
	}else if(inputname3.val()==""){
		window.alert('Please provide the fullname.');
		$(inputname3).addClass('alerterror').focus();
		formstatus= false;
		// pointmonitor=true;
	}else if(inputname4.val()==""){
		window.alert('Please provide the position.');
		$(inputname4).addClass('alerterror').focus();
		formstatus= false;
		// pointmonitor=true;
	}else if(inputname5.val()==""){
		window.alert('Please provide the details.');
		$(inputname5).addClass('alerterror').focus();
		formstatus= false;
		// pointmonitor=true;
	}else if(inputname11.val()==""){
		window.alert('Qualifications.');
		$(inputname11).addClass('alerterror').focus();
		formstatus= false;
		// pointmonitor=true;
	}else if(inputname2.val()!==""){
		var slideout=getExtension(inputname2.val());
		if(slideout['type']!=="image"){
			window.alert('Please provide a valid slide image.');
			$(inputname2).addClass('alerterror').focus();
			formstatus= false;
			pointmonitor=true;
		}
	}else if(inputname1.val()>1){
		for (var i = 2; i <= inputname1.val(); i++) {
			var inputname6=$('input[name=slide'+i+']');
			var inputname7=$('input[name=fullname'+i+']');
			var inputname8=$('input[name=position'+i+']');
			var inputname9=$('input[name=details'+i+']');
			var inputname10=$('input[name=qualifications'+i+']');
			if(inputname6.val()!==""){
				var slideout=getExtension(inputname6.val());
				if(inputname3.val()==""){
					window.alert('Please provide the fullname.');
					$(inputname3).addClass('alerterror').focus();
					formstatus= false;
					break;
				}else if(inputname4.val()==""){
					window.alert('Please provide the position.');
					$(inputname4).addClass('alerterror').focus();
					formstatus= false;
					break;
				}else if(inputname5.val()==""){
					window.alert('Please provide the details.');
					$(inputname5).addClass('alerterror').focus();
					formstatus= false;
					break;
				}else if(slideout['type']!=="image"){
					window.alert('Please provide a valid slide image.');
					$(inputname6).addClass('alerterror').focus();
					formstatus= false;
					break;
				}

			}
		};
	}
	if(formstatus==true){
		var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
		// console.log(confirmed);
		if(confirmed===true){
			$('form[name=contentform]').attr({"onSubmit":"return true;"}).submit();
		}
	}
}
if(buttonname=="newbranchsubmit"){
	var formstatus=true;
	tinyMCE.triggerSave();
	var inputname1=$('form[name=branchform] input[name=locationtitle]');
	var inputname2=$('form[name=branchform] textarea[name=address]');
	var inputname3=$('form[name=branchform] input[name=phonenumbers1]');
	var inputname4=$('form[name=branchform] input[name=email1]');
	var inputname5=$('form[name=branchform] input[name=contactpersons1]');
	var inputname6=$('form[name=branchform] input[name=curcontactcount]');
	var inputname7=$('form[name=branchform] input[name=latitude]');
	var inputname8=$('form[name=branchform] input[name=longitude]');
	var inputname9=$('form[name=branchform] select[name=mainbranch]');
	if(inputname1.val()==""){
		window.alert('Please provide the title of the branch.');
		$(inputname1).addClass('alerterror').focus();
		formstatus= false;
	}else if(inputname2.val()==""){
		window.alert('Please provide the address for this branch.');
		$(inputname2).addClass('alerterror').focus();
		formstatus= false;
	}else if(inputname3.val()==""){
		window.alert('Please provide the phone number.');
		$(inputname3).addClass('alerterror').focus();
		formstatus= false;
	}else if(inputname4.val()==""){
		window.alert('Please provide the email address.');
		$(inputname4).addClass('alerterror').focus();
		formstatus= false;
	}else if(inputname5.val()==""){
		window.alert('Please provide the contact person.');
		$(inputname5).addClass('alerterror').focus();
		formstatus= false;
	}else if(inputname6.val()>1){
		for (var i = 2; i <= inputname6.val() ; i++) {
			var inputname7=$('form[name=branchform] input[name=phonenumbers'+i+']');
			var inputname8=$('form[name=branchform] input[name=email'+i+']');
			var inputname9=$('form[name=branchform] input[name=contactpersons'+i+']');
			if(inputname7.val()!==""&&inputname8.val()==""){
				window.alert('Please provide the email address(s).');
				$(inputname8).addClass('alerterror').focus();
				formstatus= false;
				break;
			}else if(inputname8.val()!==""&&inputname9.val()==""){
				window.alert('Please provide the contact person(s).');
				$(inputname9).addClass('alerterror').focus();
				formstatus= false;
				break;

			}else if(inputname9.val()!==""&&inputname7.val()==""){
				window.alert('Please provide the phone number.');
				$(inputname7).addClass('alerterror').focus();
				formstatus= false;
				break;

			}
		};
	}
	if(inputname9.val()!==""&&formstatus==true&&inputname7.val()==""&&inputname8==""){
		formstatus=false;
		errmsg='Please provide the latitude and longitude value for this Main Branch Entry.';
		raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
        $("#mainPageModal").on("hidden.bs.modal", function () {
            $(inputname7).addClass('error-class').focus();
        });
	}
	if(formstatus==true&&inputname7.val()!==""&&inputname8==""){
		formstatus=false;
		errmsg='Please provide the longitude value.';
		raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
        $("#mainPageModal").on("hidden.bs.modal", function () {
            $(inputname8).addClass('error-class').focus();
        });
	}else if(formstatus==true&&inputname8.val()!==""&&inputname7==""){
		formstatus=false;
		errmsg='Please provide the latitude value.';
		raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
        $("#mainPageModal").on("hidden.bs.modal", function () {
            $(inputname7).addClass('error-class').focus();
        });
	}
	if(formstatus==true){
		var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
		// console.log(confirmed);
		if(confirmed===true){
		$('form[name=branchform]').attr("onSubmit","return true").submit();
		}
	}
}
if(buttonname=="recommendationsubmit"||buttonname=="testimonialsubmit"||buttonname=="clientellesubmit"){
	tinyMCE.triggerSave();
	var formstatus=true;	
	var inputname1=buttonname=="recommendationsubmit"?$('input[name=currecommendationslidecount]'):(buttonname=="testimonialsubmit"?$('input[name=curtestimonialslidecount]'):buttonname=="clientellesubmit"?$('input[name=curclientelleslidecount]'):$('input[name=currecommendaionslidecount]'));
	var inputname2=$('input[name=slide1]');
	var inputname3=$('input[name=fullname1]');
	var inputname4=$('input[name=position1]');
	var inputname5=$('input[name=personalwebsite1]');
	var inputname6=$('input[name=companyname1]');
	var inputname7=$('input[name=companywebsite1]');
	var inputname8=$('textarea[name=details1]');
	if(inputname2.val()==""&&buttonname=="clientellesubmit"){
		window.alert('Please provide an image.');
		$(inputname2).addClass('alerterror').focus();
		formstatus= false;
		// pointmonitor=true;
	}else if(inputname3.val()==""&&(buttonname=="recommendationsubmit"||buttonname=="testimonialsubmit")){
		window.alert('Please provide the fullname.');
		$(inputname3).addClass('alerterror').focus();
		formstatus= false;
		// pointmonitor=true;
	}else if(inputname4.val()==""&&(buttonname=="recommendationsubmit"||buttonname=="testimonialsubmit")){
		window.alert('Please provide the position.');
		$(inputname4).addClass('alerterror').focus();
		formstatus= false;
		// pointmonitor=true;
	}else if(inputname6.val()==""&&(buttonname=="recommendationsubmit"||buttonname=="clientellesubmit")){
		window.alert('Please provide the company name, this field must not be empty, if there is no company simply put in "none" or "null".');
		$(inputname6).addClass('alerterror').focus();
		formstatus= false;
		// pointmonitor=true;
	}else if(inputname8.val()==""){
		window.alert('Please provide the details.');
		$(inputname8).addClass('alerterror').focus();
		formstatus= false;
		// pointmonitor=true;
	}else if(inputname6.val()==""){
		window.alert('Qualifications.');
		$(inputname6).addClass('alerterror').focus();
		formstatus= false;
		// pointmonitor=true;
	}else if(inputname2.val()!==""){
		var slideout=getExtension(inputname2.val());
		if(slideout['type']!=="image"){
			window.alert('Please provide a valid image.');
			$(inputname2).addClass('alerterror').focus();
			formstatus= false;
			pointmonitor=true;
		}
	}else if(inputname1.val()>1){
		for (var i = 2; i <= inputname1.val(); i++) {
			var inputname9=$('input[name=slide'+i+']');
			var inputname10=$('input[name=fullname'+i+']');
			var inputname11=$('input[name=position'+i+']');
			var inputname12=$('input[name=personalwebsite'+i+']');
			var inputname13=$('input[name=companyname'+i+']');
			var inputname14=$('input[name=companywebsite'+i+']');
			var inputname15=$('textarea[name=details'+i+']');
			var inputname16=$('input[name=qualifications'+i+']');
			if(inputname10.val()!==""||inputname9.val()!==""||inputname11.val()!==""||inputname12.val()!==""||inputname13.val()!==""||inputname15.val()!==""){
				var slideout=inputname9.val()!==""?getExtension(inputname9.val()):"";
				if(inputname9.val()==""&&buttonname=="clientellesubmit"){
					window.alert('Please provide an image.');
					$(inputname9).addClass('alerterror').focus();
					formstatus= false;
					// pointmonitor=true;
				}else if(inputname10.val()==""&&(buttonname=="recommendationsubmit"||buttonname=="clientellesubmit")){
					window.alert('Please provide the fullname.');
					$(inputname10).addClass('alerterror').focus();
					formstatus= false;
					break;
				}else if(inputname11.val()==""&&(buttonname=="recommendationsubmit"||buttonname=="clientellesubmit")){
					window.alert('Please provide the position.');
					$(inputname11).addClass('alerterror').focus();
					formstatus= false;
					break;
				}else if(inputname13.val()==""&&(buttonname=="recommendationsubmit"||buttonname=="clientellesubmit")){
					window.alert('Please provide the Company name.');
					$(inputname13).addClass('alerterror').focus();
					formstatus= false;
					break;
				}else if(inputname15.val()==""){
					window.alert('Please provide the details.');
					$(inputname15).addClass('alerterror').focus();
					formstatus= false;
					break;
				}else if(slideout!==""&&slideout['type']!=="image"){
					window.alert('Please provide a valid image.');
					$(inputname13).addClass('alerterror').focus();
					formstatus= false;
					break;
				}
			}
		};
	}
	if(formstatus==true){
		var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
		// console.log(confirmed);
		if(confirmed===true){
			$('form[name=contentform]').attr({"onSubmit":"return true;"}).submit();
		}
	}
}
if(buttonname=="homebannersubmit"){
	var formstatus=true;
	tinyMCE.triggerSave();
	var inputname1=$('form[name=homebanners] input[name=curbannerslidecount]');
	for(var i=1; i<=inputname1.val();i++){ 
		var inputname2=$('form[name=homebanners] input[name=slide'+i+']');
		var inputname3=$('form[name=homebanners] input[name=headercaption'+i+']');
		var inputname4=$('form[name=homebanners] input[name=minicaption'+i+']');
		if(inputname2.val()==""&&(inputname3.val()!==""||inputname4.val()!=="")){
			window.alert('Please provide the banner image.');
			$(inputname2).addClass('alerterror').focus();
			formstatus= false;
			break;
		}
	}
	if(formstatus==true){
		var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
		console.log(confirmed);
		if(confirmed===true){
		$('form[name=homebanners]').submit();
		}
	}
}
/*end*/

if(buttonname=="createfvtmember"){
	var formstatus=true;
	// for managing broken condition block validation
	// e.g for emails, or verifying multiple content via ajax
	// it basically stops the validation from continuing in said instances and more
	var pointmonitor="false";
	
    var formselector='form[name=membershipform] ';
	var inputname1=$(''+formselector+'input[name=fullname]');
	var inputname2=$(''+formselector+'input[name=organisation]');
	var inputname3=$(''+formselector+'input[name=occupation]');
	var inputname4=$(''+formselector+'input[name=address]');
	var inputname5=$(''+formselector+'input[name=email]');
	var inputname6=$(''+formselector+'input[name=phonenumber]');
	var inputname7=$(''+formselector+'select[name=memberregcat]');
	var inputname8=$(''+formselector+'select[name=subscriptioncat]');
	var inputname9=$(''+formselector+'select[name=modeofpayment]');
	var inputname10=$(''+formselector+'textarea[name=comments]');
	var errmsg="";
	if(inputname1.val()==""){
		errmsg='Please give your fullname.';
		raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
        $("#mainPageModal").on("hidden.bs.modal", function () {
            $(inputname1).addClass('error-class').focus();
        });
		formstatus= false;
		pointmonitor="true";
	}else if(inputname3.val()==""){
		errmsg='Please state your occupation e.g "Student" , "Entrepreneur" e.t.c .';
		raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
        $("#mainPageModal").on("hidden.bs.modal", function () {
            $(inputname3).addClass('error-class').focus();
        });
		formstatus= false;
		pointmonitor="true";
	}else if(inputname4.val()==""){
		errmsg='Please provide an address you are reachable from, this is important in the event we need to send documents accross to you.';
		raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
        $("#mainPageModal").on("hidden.bs.modal", function () {
            $(inputname4).addClass('error-class').focus();
        });
		formstatus= false;
		pointmonitor="true";
	}else if(inputname5.val()==""){
		errmsg='Please a valid email address.';
		raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
        $("#mainPageModal").on("hidden.bs.modal", function () {
            $(inputname5).addClass('error-class').focus();
        });
		formstatus= false;
		pointmonitor="true";
	}else if(inputname6.val()==""){
		errmsg='Please give your phone number(s).';
		raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
        $("#mainPageModal").on("hidden.bs.modal", function () {
            $(inputname6).addClass('error-class').focus();
        });
		formstatus= false;
		pointmonitor="true";
	}else if(inputname7.val()==""){
		errmsg='Please choose a membership registration type from the list provided.';
		raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
        $("#mainPageModal").on("hidden.bs.modal", function () {
            $(inputname7).addClass('error-class').focus();
        });
		formstatus= false;
		pointmonitor="true";
	}else if(inputname8.val()==""){
		errmsg='Please select an annual subscription plan type.';
		raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
        $("#mainPageModal").on("hidden.bs.modal", function () {
            $(inputname8).addClass('error-class').focus();
        });
		formstatus= false;
		pointmonitor="true";
	}else if(inputname9.val()==""){
		errmsg='Please select a subscription payment mode from the list provided.';
		raiseMainModal('Form error!!', ''+errmsg+'', 'fail');
        $("#mainPageModal").on("hidden.bs.modal", function () {
            $(inputname9).addClass('error-class').focus();
        });
		formstatus= false;
		pointmonitor="true";
	}
	if(inputname5.val()!==""&&pointmonitor=="false"){
        var outit = emailValidatorReturnableTwo(inputname5.val());
        if (outit['status'] == "false") {
            window.alert(outit['errormsg']);
            inputname5.addClass('error-class').focus();
            formstatus=false;
            pointmonitor="true";
        }
    }

	console.log(errmsg);
	if(formstatus==true&&pointmonitor=="false"){
		var confirmed=window.confirm('The form is ready to be submitted, if you want to cross check, click "Cancel" otherwise click "OK"');
		// console.log(confirmed);
		if(confirmed===true){
			$('form[name=membershipform]').attr("onSubmit","return true").submit();
		}
	}
}

});
});