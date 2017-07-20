<?php
include('connection.php');
if(isset($_GET['displaytype'])){
$displaytype=$_GET['displaytype'];
$extraval=$_GET['extraval'];
}elseif (isset($_POST['displaytype'])) {
$displaytype=$_POST['displaytype'];	
}


if($displaytype=="createbooking"){
// echo $displaytype;
include('createbooking.php');
}elseif($displaytype=="createoyo"){
// echo $displaytype;
include('createoyo.php');
}elseif($displaytype=="createoyoreq"){
	// echo $displaytype;
	$reqtype=$extraval;
	if($reqtype=="oyoreg"){
		include('createoyoform.php');
	}else if($reqtype=="businessidea"){
		include('createbusinessidea.php');
	}else if($reqtype=="trainingandempowerment"){
		include('createtrainingandempowerment.php');

	}
}elseif($displaytype=="servicerequest"){
// echo $displaytype;
include('createservicerequest.php');
}elseif($displaytype=="testimonyrequest"){
// echo $displaytype;
include('createtestimony.php');
}else if($displaytype=="viewrecruits"){
	// echo $displaytype;
	$viewout="admin";
	$outs=getAllUsers($extraval,"");
	echo $outs['adminoutput'];
}else if($displaytype=="editsinglerecruit"){
	// echo $displaytype;
	$viewout="admin";
	$editid=$_GET['editid'];
	$outs=getSingleUser($editid);
	echo $outs['adminoutputtwo'];
}else if($displaytype=="searchrecruits"){
	// echo $displaytype;
	include("fjcadvancedsearch.php");
}else if($displaytype=="advancedrecruitsearch"){
	$curyear=date('Y');
	$termsofemployment=mysql_real_escape_string($_GET['termsofemployment']);
	$termsofemployment!==""?$teoquery=" AND preferredjobtype='$termsofemployment'":$teoquery="";
	// $recruitsneeded=mysql_real_escape_string($_GET['recruitsneeded']);
	// $remuneration=mysql_real_escape_string($_GET['remuneration']);
	// create query based on amount of qualifications specified
	$minqualification=mysql_real_escape_string($_GET['minqualification']);
	$minqdata=explode(";",$minqualification);
	$minqquery="";
	if($minqualification!==""){
		for($i=0;$i<count($minqdata);$i++){
			if($i==0&&count($minqdata)>1){
				$minqquery=" AND EXISTS (SELECT * FROM recruitacademicprohistory WHERE recruitid=`recruits`.`id` AND (qualification LIKE '%$minqdata[$i]%'";
			}else if($i>0&&$i<count($minqdata)-2){
				$minqquery.="OR qualification LIKE '%$minqdata[$i]%'";
			}else if($i==count($minqdata)-1&&count($minqdata)>1){
				$minqdata[$i]!==""?$minqquery.=" OR qualification LIKE '%$minqdata[$i]%'))":$minqquery.="))";
			}else if($i==0&&count($minqdata)<2){
				$minqquery=" AND EXISTS (SELECT * FROM recruitacademicprohistory WHERE recruitid=`recruits`.`id` AND qualification LIKE '%$minqdata[$i]%')";
			}
		}
	}
	$hobbies=mysql_real_escape_string($_GET['hobbies']);
	$hobbiesdata=explode(";",$hobbies);
	$hobbiesquery="";
	if($hobbies!==""){
		for($i=0;$i<count($hobbiesdata);$i++){
			if($i==0&&count($hobbiesdata)>1){
				$hobbiesquery=" AND (hobbies LIKE '%$hobbiesdata[$i]%'";
			}else if($i>0&&$i<count($hobbiesdata)-2){
				$hobbiesquery.=" OR hobbies LIKE '%$hobbiesdata[$i]%'";
			}else if($i==count($hobbiesdata)-1&&count($hobbiesdata)>1){
				$hobbiesdata[$i]!==""?$hobbiesquery.=" OR hobbies LIKE '%$hobbiesdata[$i]%')":$hobbiesquery.=")";
			}else if($i==0&&count($hobbiesdata)<2){
				$hobbiesquery=" AND hobbies LIKE '%$hobbiesdata[$i]%'";
			}

		}
	}
	$skills=mysql_real_escape_string($_GET['helpfullskills']);
	$skillsdata=explode(";",$skills);
	$skillsquery="";
	if($skills!==""){
		for($i=0;$i<count($skillsdata);$i++){
			if($i==0&&count($skillsdata)>1){
				$skillsquery=" AND (skills LIKE '%$skillsdata[$i]%'";
			}else if($i>0&&$i<count($skillsdata)-2){
				$skillsquery.=" OR skills LIKE '%$skillsdata[$i]%'";
			}else if($i==count($skillsdata)-1&&count($skillsdata)>1){
				$skillsdata[$i]!==""?$skillsquery.=" OR skills LIKE '%$skillsdata[$i]%')":$skillsquery.=")";
			}else if($i==0&&count($skillsdata)<2){
				$skillsquery=" AND skills LIKE '%$skillsdata[$i]%'";
			}
		}
	}
	// $pedate=mysql_real_escape_string($_GET['pedate']);
	// $additionalinfo=mysql_real_escape_string($_GET['additionalinfo']);

	// age restriction
	$prageminmax=mysql_real_escape_string($_GET['prageminmax']);
	$pragedata=explode(",",$prageminmax);
	$pragemin=$curyear-$pragedata[1]."-01-01";
	$pragemax=$curyear-$pragedata[0]."-01-01";
	$pragemin==$pragemax?$prageout=$pragemin:$prageout="$pragemin - $pragemax";
	// generate age restrictionquery
	$agequery="";
	if($pragemin==$pragemax){
		$outyear=$curyear-$pragedata[1]."-01-01";
		$agequery=" AND dob=$outyear";
	}else if($pragedata[0]<$pragedata[1]){
		$agequery=" AND dob>='$pragemin' AND dob<='$pragemax'";
	}
	// echo $pragemin.$pragemax;
	$jobfield=mysql_real_escape_string($_GET['jobfield']);
	$jobfield!==""?$jobquery=" AND field LIKE '%$jobfield%'":$jobquery="";
	$positionavailable=mysql_real_escape_string($_GET['positionavailable']);
	$positionavailable!==""?$paquery=" AND position LIKE '%$positionavailable%'":$paquery="";

	// work experience in industry
	$pweminmax=mysql_real_escape_string($_GET['pweminmax']);
	// echo $pweminmax."<br>";
	$pwedata=explode(",",$pweminmax);
	$pwemin=mysql_real_escape_string($pwedata[0]);
	$pwemax=mysql_real_escape_string($pwedata[1]);
	$pwemin==$pwemax?$pweout=$pwemin:$pweout="$pwemin - $pwemax";
	// echo $pweout."<br>";
	$pwequery="";
	if($pwemin==$pwemax){
		$outyear=$curyear-$pwemin."-01-01";
	}else if($pwemin<$pwemax){
		$pwequery=" AND fromdate>='$pwemin' AND todate<='$pwemax'";
	}
	// work experience in job post	
	$pwejminmax=mysql_real_escape_string($_GET['pwejminmax']);
	$pwejdata=explode(",",$pwejminmax);
	$pwejmin=$curyear-$pwejdata[0]."-01-01";
	$pwejmax=$curyear-$pwejdata[1]."-01-01";
	$pwejmin==$pwejmax?$pwejout=$pwejmin:$pwejout="$pwejmin - $pwejmax";
	// generate query for workexperience
	$workexperiencequery="";
	$workexperiencequerypart="";
	if($pwejmin==$pwejmax){
		$outyear=$curyear-$pwejmin."-01-01";
		$workexperiencequerypart=" AND fromdate=$outyear";
	}else if($pwejdata[0]<$pwejdata[1]){
		$workexperiencequerypart=" AND fromdate>='$pwejmax' AND todate<='$pwejmin'";
	}
	if($jobfield!==""||$positionavailable!==""){
		$workexperiencequery=" AND EXISTS(SELECT * FROM recruitemploymenthistory WHERE recruitid=`recruits`.`id` $jobquery $paquery $workexperiencequerypart)";
	}

	$prefgender=mysql_real_escape_string($_GET['prefgender']);
	// gender query section
	$genderquery="";
	if($prefgender!==""){
		$genderquery=" AND gender='$prefgender'";
	}
	$query="SELECT * FROM recruits WHERE id>0 $teoquery $genderquery $agequery $hobbiesquery $skillsquery $minqquery $workexperiencequery";
	// echo $query;
	$run=mysql_query($query)or die(mysql_error());
	$numrows=mysql_num_rows($run);
	if($numrows>0){
		$viewer[]='advancedrecruitsearch';
		$viewer[]=$query;
		$viewer[]="admin";
		$outs=getAllUsers($viewer,"");
		echo $outs['adminoutput'];
	}else{
		echo "Sorry the search parameters yieldewd no valid results from the database";
	}
}elseif($displaytype=="careeradvice"){
	// echo $displaytype;
	$outs=getAllGeneralInfo("admin","$displaytype","");
	echo $outs['fullviewtwo'];
}elseif($displaytype=="businessadvice"){
	// echo $displaytype;
	$outs=getAllGeneralInfo("admin","$displaytype","");
	echo $outs['fullviewtwo'];
}elseif($displaytype=="fjcquote"){
	// echo $displaytype;
	$outs=getAllGeneralInfo("admin","$displaytype","");
	echo $outs['fullviewtwo'];
}elseif($displaytype=="oyoquote"){
	// echo $displaytype;
	$outs=getAllGeneralInfo("admin","$displaytype","");
	echo $outs['fullviewtwo'];
}elseif($displaytype=="fieldnindustries"){
	// echo $displaytype;
	$outs=getAllGeneralInfo("admin","$displaytype","");
	echo $outs['fullviewtwo'];
}elseif($displaytype=="editsinglegeneraldata"){
	// echo $displaytype;
		$editid=mysql_real_escape_string($_GET['editid']);
	$outs=getSingleGeneralInfo($editid);
	echo $outs['adminoutputtwo'];
}else if($displaytype=="verifyemail"){
 $email=$_GET['email'];
 $emaildata=checkEmail($email,"recruits","email");
 if($emaildata['testresult']=="unmatched"){
 	echo "OK";
 }else {
 	echo "Your email address already exists for another user, are you the same one? Did you forget your Password?, try login in or contacting us if your problem persists";
 }
	// include("createsurvey.php");
}else if($displaytype=="verifyemailemployer"){
 $email=$_GET['email'];
 $emaildata=checkEmail($email,"employers","email");
 if($emaildata['testresult']=="unmatched"){
 	echo "OK";
 }else {
 	echo "Your email address already exists for another organisation, are you the same one? if you are you should login to carry out any new job posts or updates. Otherwise Use a different email address, thank you";
 }
	// include("createsurvey.php");
}elseif ($displaytype=="calenderout") {
	# code...
	$extraout=explode("-:-", $extraval);
	// echo $extraval." extraval<br>";
	// $theme=mysql_real_escape_string($_GET['theme']);
	$ecount=count($extraout);
	if($ecount>3){
		$day=$extraout[0];
		$month=$extraout[1];
		$year=$extraout[2];
		$data_target=$extraout[3];
		$theme=$extraout[4];
	}
	if(count($extraout)>4){
		$data_target=array();
		$data_target[0]=$extraout[3];
		$data_target[1]=$extraout[5];
		// echo $extraout[6]." viewtype<br>";
		isset($extraout[6])?$data_target['viewtype']=$extraout[6]:$data_target['viewtype']="";
	}
	// echo $day.$month.$year;
	$outs=calenderOut($day,$month,$year,'',$data_target,$theme,'');
	// echo $theme;
	echo $outs['totaldaysout'];
}else if($displaytype=="createpartnership"){
	include 'createpartnership.php';
}else if($displaytype=="newfaq"){
	// echo $displaytype;
	include 'createfaq.php';

}else if($displaytype=="editfaq"){
	$outs=getAllFAQ($extraval,"","");
	echo $outs['adminoutput'];
}else if($displaytype=="editsinglefaq"){
	$editid=$_GET['editid'];
	$outs=getSingleFAQ($editid);
	echo $outs['adminoutputtwo'];
}elseif ($displaytype=="getevent") {
	# code...
	$date=mysql_real_escape_string($_GET['date']);
	$page=mysql_real_escape_string($_GET['page']);
			
	$page=="frontiersconsulting"?$cat="fc":($page=="projectfixnigeria"?$cat="pfn":($page=="csi"?$cat="csi":($page=="blog"||$page=="index"||$page==""?$cat="fs":$cat="fs")));
	$sep=preg_split("/-/",$date);
	$d=$sep[0]<10?"".$sep[0]:$sep[0];
	$m=$sep[1];
	$y=$sep[2];
	// echo $d.$m.$y;
	$month[0]=$d;
	$month[1]=floor($m);
	// $d<10&&strlen($d)>2?$testdatemonitor=''.$t.'-'.$month.'-'.$year:;
	$eventout=getAllEvents("viewer",'',$cat,$month,$y);
	$outs='
			<div id="closecontainer" name="closefullcontenthold" data-id="theid" class="altcloseposfour"><img src="./images/closefirst.png" title="Close"class="total"/></div>
			<span name="specialheader">'.$date.' Events</span><br>
			'.$eventout['vieweroutput'].'
	';
	echo $outs;
}elseif ($displaytype=="newblogtype") {
	# code...
	// echo $displaytype;
	include 'createblogtype.php';
}elseif ($displaytype=="editblogtype") {
	# code...
	 $outs=getAllBlogTypes($extraval,"");
	echo $outs['adminoutput'];

}elseif($displaytype=="editsingleblogtype"){
$editid=$_GET['editid'];
$outs=getSingleBlogType($editid);
echo $outs['adminoutputtwo'];
}elseif ($displaytype=="newblogcategory") {
	# code...
	// echo $displaytype;

	include 'createblogcategory.php';
}elseif ($displaytype=="editblogcategory") {
	# code...
	// echo $displaytype;
	$outs=getAllBlogTypes("admin","");
	echo'
		<div id="formend" style="background:#fefefe;">
			Select a Blog type to edit categories under it.<br>
			<select name="editblogcategory" class="curved2">
				<option value="">Choose a Blog Type</option>
				'.$outs['selection'].'
			</select>
		</div>
	';
}elseif ($displaytype=="editblogcategorymain") {
// echo $displaytype;
$blogtypeid=$_GET['blogtypeid'];
$outs=getAllBlogCategories("admin","",$blogtypeid);
echo $outs['adminoutput'];
}elseif($displaytype=="editsingleblogcategory"){
$editid=$_GET['editid'];
$outs=getSingleBlogCategory($editid);
echo $outs['adminoutputtwo'];
}elseif ($displaytype=="getblogcategories") {
	# code...
$blogtypeid=mysql_real_escape_string($_GET['blogtypeid']);
$outs=getAllBlogCategories("admin","",$blogtypeid);
$select='<option value="">--Choose--</option>';
$select.=$outs['selection'];
echo $select;
}elseif ($displaytype=="newblogpost") {
	# code...
	// echo $displaytype;
	include 'createblogpost.php';
}elseif ($displaytype=="editblogposts") {
	# code...
	// echo $displaytype;
	$outs=getAllBlogTypes("admin","");
	echo'
		<div id="formend" style="background:#fefefe;">
		Select a Blog type first then click away from the selection box, the second selection box will be populated with the categories under the chosen blog type, now you can choose to either edit all blog posts under a category or under a blog type by chosing a type the a category (view category) or only a type(view all posts under the blog, click the go button when done to view your output).<br>
		<div id="formend">
			<select name="blogtypeid" class="curved2">
				<option value="">Choose a Blog Type</option>
				'.$outs['selection'].'
			</select>
		</div>
		<div id="formend">
			<select name="blogcategoryid" class="curved2">
				<option value="">Choose Category</option>
			</select>
		</div>
			<div id="formend">
				<input type="button" name="viewblogposts" value="GO" class="submitbutton"/>
			</div>
		</div>';

}elseif ($displaytype=="viewblogposts") {
	# code...
	// echo $displaytype;
	$blogtypeid=mysql_real_escape_string($_GET['blogtypeid']);
	$blogcategoryid=mysql_real_escape_string($_GET['blogcategoryid']);
	if($blogtypeid!==""&&$blogcategoryid==""){
	$outs=getAllBlogEntries("admin","",$blogtypeid,'blogtype');
			echo $outs['adminoutput'];
	}elseif ($blogcategoryid!=="") {
		# code...
		$outs=getAllBlogEntries("admin","",$blogcategoryid,'category');
			echo $outs['adminoutput'];
	}
}elseif ($displaytype=="editsingleblogpost") {
	# code...
	// echo $displaytype;
	$editid=$_GET['editid'];
	$outs=getSingleBlogEntry($editid);
	$query="SELECT * FROM comments where blogentryid=$editid";
	$run=mysql_query($query)or die(mysql_error()." Line 135");
	$numrows=mysql_num_rows($run);
	if($numrows>0){
		while($row=mysql_fetch_assoc($run)){
			if($row['status']!=="disabled"){
				genericSingleUpdate("comments","status",'active',"id",$row['id']);
			}
		}
	}
echo $outs['adminoutputtwo'];
/*echo'
	<iframe target="">

	</iframe>
';*/
}/*elseif ($displaytype=="newmedia") {
	# code...
	echo $displaytype;

}elseif ($displaytype=="editmedia") {
	# code...
	echo $displaytype;
	
}*/elseif ($displaytype=="newqotd") {
	# code...
	// echo $displaytype;
	include'createqotd.php';
}elseif ($displaytype=="editqotd") {
	# code...
	// echo $displaytype;
		echo'
		<div id="formend" style="background:#fefefe;">
		Select a type first then click away from the selection box then click the GO button.<br>
		<div id="formend">
			<select name="qotdcat" class="curved2">
				<option value="">Choose a Quote Category</option>
				<option value="general">General</option>
				<option value="pfn">Frankly Speaking Africa</option>
				<option value="csi">CSI Outreach</option>
			</select>
		</div>
		<div id="formend">
				<input type="button" name="viewqotd" value="GO" class="submitbutton"/>
			</div>
		</div>';

}elseif ($displaytype=="viewqotd") {
$quotecat=mysql_real_escape_string($_GET['qotdcat']);
$outs=getallQuotes("admin","",$quotecat);
echo $outs['adminoutput'];
}elseif ($displaytype=="editsingleqotd") {
	$editid=$_GET['editid'];
$outs=getSingleQuote($editid);
echo $outs['adminoutputtwo'];
}elseif ($displaytype=="newgallery") {
	# code...
	// echo $displaytype;
include'creategallery.php';
}elseif ($displaytype=="editgallery") {
	# code...
	// echo $displaytype;
$outs=getAllGalleries("admin","");
echo $outs['adminoutput'];
}elseif ($displaytype=="editsinglegallery") {
	# code...
	$editid=$_GET['editid'];
$outs=getSingleGallery($editid);
echo $outs['adminoutputtwo'];
	
}elseif ($displaytype=="deletepic") {
	# code...
	$imgid=$extraval;
$outs=deleteMedia($imgid);
echo $outs;
	
}elseif ($displaytype=="editbookings") {
	# code...
	// echo $displaytype;
	// $editid=$_GET['editid'];
$outs=getAllBookings("admin","");
echo $outs['adminoutput'];
}elseif ($displaytype=="editsinglebooking") {
	# code...
	// echo $displaytype;
	$editid=$_GET['editid'];
genericSingleUpdate("bookings","status","active","id",$editid);
$outs=getSingleBooking($editid);
echo $outs['adminoutputtwo'];
}elseif ($displaytype=="newevent") {
	# code...
	// echo $displaytype;
include 'createevent.php';
}elseif ($displaytype=="editevent") {
	# code...
	// echo $displaytype;
			echo'
		<div id="formend" style="background:#fefefe;">
		Select a type first then click away from the selection box then click the GO button.<br>
		<div id="formend">
			<select name="eventcat" class="curved2">
			<option value="">--Choose--</option>
			<option value="fc">Frontiers Consulting</option>
			<option value="fr">Frontiers Radio</option>
			<option value="pfn">Frankly Speaking Africa</option>
			<option value="csi">Christ Society International Outreach</option>
			<option value="fs">Frankly Speaking With Muyiwa Afolabi.</option>
			</select>
		</div>
		<div id="formend">
				<input type="button" name="viewevent" value="GO" class="submitbutton"/>
			</div>
		</div>';
}elseif ($displaytype=="viewevent") {
$eventcat=mysql_real_escape_string($_GET['eventcat']);
$currentmonth=date('m');
$currentyear=date('Y');
$outs=getAllEvents("admin","",$eventcat,'','');
// echo 'in here '.$eventcat.'';
echo $outs['adminoutput'];
}else if($displaytype=="onlineradio"){

}elseif ($displaytype=="alltestimonies") {
	# code...
	// echo $displaytype;
		$typeout="all";
	$outs=getAllTestimonies("admin","",$typeout);
	echo $outs['adminoutput'];
}elseif ($displaytype=="activetestimonies") {
	# code...
	// echo $displaytype;
	$typeout="active";
	$outs=getAllTestimonies("admin","",$typeout);
	echo $outs['adminoutput'];
}elseif ($displaytype=="inactivetestimonies") {
	# code...
	// echo $displaytype;
			$typeout="inactive";
	$outs=getAllTestimonies("admin","",$typeout);
	echo $outs['adminoutput'];
}elseif ($displaytype=="editsingletestimony") {
	# code...
	// echo $displaytype;	
	$editid=$_GET['editid'];	
	$outs=getSingleTestimony($editid);
	echo $outs['adminoutputtwo'];	
}elseif ($displaytype=="viewtestimony") {
	# code...
	// echo $displaytype;
	$editid=$_GET['cid'];
		$outs=getSingleTestimony($editid);
	echo $outs['vieweroutputtwo'];
}elseif ($displaytype=="editsingleevent") {
	$editid=$_GET['editid'];
$outs=getSingleEvent($editid);
echo $outs['adminoutputtwo'];
}elseif ($displaytype=="newblogpost") {
	# code...
	echo $displaytype;

}elseif ($displaytype=="editblogposts") {
	# code...
	echo $displaytype;
	
}elseif ($displaytype=="newtrendingtopic") {
	# code...
	// echo $displaytype;
include'createtrendingtopic.php';
}elseif ($displaytype=="edittrendingtopics") {
	# code...
	// echo $displaytype;
	$outs=getAllTrendingTopics("admin","");
// echo 'in here '.$eventcat.'';
echo $outs['adminoutput'];
}elseif ($displaytype=="editsingletrendingtopic") {
	$editid=$_GET['editid'];
$outs=getSingleTrendingTopic($editid);
echo $outs['adminoutputtwo'];
}elseif ($displaytype=="newtoptenplaylist") {
	# code...
	// echo $displaytype;
	include'createtoptenplaylist.php';
}elseif ($displaytype=="edittoptenplaylist") {
	# code...
	// echo $displaytype;
		$outs=getAllPlaylist("admin","");
// echo 'in here '.$eventcat.'';
echo $outs['adminoutput'];
}elseif ($displaytype=="editsingletoptenplaylist") {
	# code...
	$editid=$_GET['editid'];
$outs=getSinglePlaylist($editid);
echo $outs['adminoutputtwo'];
}elseif ($displaytype=="newcsisermon") {
	# code...
	// echo $displaytype;
	include'createcsisermon.php';
}elseif ($displaytype=="editcsisermon") {
	# code...
	// echo $displaytype;
	$outs=getAllSermons("admin","");
	// echo 'in here '.$eventcat.'';
	echo $outs['adminoutput'];
}elseif ($displaytype=="editsinglecsisermon") {
	# code...
	$editid=$_GET['editid'];
	$outs=getSingleSermon($editid);
	echo $outs['adminoutputtwo'];
}elseif ($displaytype=="newtopaudio") {
	# code...
	// echo $displaytype;
	include'createtopaudio.php';
}elseif ($displaytype=="edittopaudio") {
	# code...
	// echo $displaytype;
		$outs=getAllTopAudio("admin","");
// echo 'in here '.$eventcat.'';
echo $outs['adminoutput'];
}elseif ($displaytype=="editsingletopaudio") {
	# code...
	$editid=$_GET['editid'];
	$outs=getSingleTopAudio($editid);
	echo $outs['adminoutputtwo'];
}elseif ($displaytype=="editservicerequests") {
	# code...
	// echo $displaytype;
	$outs=getAllServiceRequests("admin","");
echo $outs['adminoutput'];	
}elseif ($displaytype=="editsingleservicerequest") {
	# code...
	// echo $displaytype;
		$editid=$_GET['editid'];
genericSingleUpdate("servicerequest","status","active","id",$editid);
$outs=getSingleServiceRequest($editid);
echo $outs['adminoutputtwo'];
}elseif ($displaytype=="editsubscribers") {
	# code...
	// echo $displaytype;
	$outs=getAllBlogTypes("admin","");
	echo'
		<div id="formend" style="background:#fefefe;">
		Select a Blog type first then click away from the selection box, the second selection box will be populated with the categories under the chosen blog type, or choose only a type(view all subscribers to selected blog, click the go button when done to view your output).<br>
		<div id="formend">
			<select name="blogtypeid" class="curved2">
				<option value="">Choose a Blog Type</option>
				'.$outs['selection'].'
			</select>
		</div>
		<div id="formend">
			<select name="blogcategoryid" class="curved2">
				<option value="">Choose Category</option>
			</select>
		</div>
			<div id="formend">
				<input type="button" name="viewsubscribers" value="GO" class="submitbutton"/>
			</div>
		</div>';
}elseif ($displaytype=="franklyspeakingsubscribe") {
	# code...
	// echo $displaytype;
	$outs=getAllSubscribers('admin','',1,'blogtype');
	echo $outs['adminoutput'];
}elseif ($displaytype=="csisubscribe") {
	# code...
	// echo $displaytype;
	$outs=getAllSubscribers('admin','',2,'blogtype');
	echo $outs['adminoutput'];	
}elseif ($displaytype=="pfnsubscribe") {
	# code...
	echo $displaytype;
	$outs=getAllSubscribers('admin','',3,'blogtype');
	echo $outs['adminoutput'];
}elseif ($displaytype=="viewsubscribers") {
	# code...
	// echo $displaytype;
$blogtypeid=mysql_real_escape_string($_GET['blogtypeid']);
$blogcategoryid=mysql_real_escape_string($_GET['blogcategoryid']);
if($blogtypeid!==""&&$blogcategoryid==""){
$outs=getAllSubscribers("admin","",$blogtypeid,'blogtype');
		echo $outs['adminoutput'];
}elseif ($blogcategoryid!=="") {
	# code...
	$outs=getAllSubscribers("admin","",$blogcategoryid,'category');
		echo $outs['adminoutput'];
}
}elseif ($displaytype=="activatesubscriber") {
$editid=$_GET['editid'];
genericSingleUpdate("subscriptionlist","status","active","id",$editid);
}elseif ($displaytype=="disablesubscriber") {
$editid=$_GET['editid'];
genericSingleUpdate("subscriptionlist","status","inactive","id",$editid);
}elseif ($displaytype=="allcomments") {
	# code...
	// echo $displaytype;
	$typeout="all";
	$outs=getAllComments("admin","",$typeout);
	echo $outs['adminoutput'];
}elseif ($displaytype=="activecomments") {
	# code...
	// echo $displaytype;
	$typeout="active";
	$outs=getAllComments("admin","",$typeout);
	echo $outs['adminoutput'];
}elseif ($displaytype=="inactivecomments") {
	# code...
	// echo $displaytype;
	$typeout="inactive";
	$outs=getAllComments("admin","",$typeout);
	echo $outs['adminoutput'];
}elseif ($displaytype=="disabledcomments") {
	# code...
	// echo $displaytype;
	$typeout="disabled";	
	$outs=getAllComments("admin","",$typeout);
	echo $outs['adminoutput'];
}elseif ($displaytype=="activatecomment"||$displaytype=="reactivatecomment") {
$editid=$_GET['editid'];
genericSingleUpdate("comments","status","active","id",$editid);
}elseif ($displaytype=="disablecomment") {
$editid=$_GET['editid'];
genericSingleUpdate("comments","status","disabled","id",$editid);
}elseif ($displaytype=="newadvert") {
	# code...
	// echo $displaytype;
include 'createadvert.php';
}elseif ($displaytype=="editadverts") {
	# code...
	// echo $displaytype;
		echo'
		<div id="formend" style="background:#fefefe;">
		Select a type first then click away from the selection box then click the GO button.<br>
		<div id="formend">
			<select name="advertcat" class="curved2">
				<option value="">Choose an advert page Category</option>
				<option value="all">All(adverts that show up on each blog page type)</option>
				<option value="pfn">Project Fix Nigeria Blog Page</option>
				<option value="csi">Christ Society International Outreach Blog Page</option>
				<option value="fs">Frankly Speaking With Muyiwa Afolabi Blog Page.</option>
			</select>
		</div>

		<div id="formend">
				<input type="button" name="viewadverts" value="GO" class="submitbutton"/>
			</div>
		</div>';	
}elseif ($displaytype=="viewadverts") {
	# code...
	$advertcat=mysql_real_escape_string($_GET['advertcat']);
$outs=getAllAdverts("admin","","",$advertcat);
echo $outs['adminoutput'];
}elseif ($displaytype=="editsingleadvert") {
	# code...
	$editid=mysql_real_escape_string($_GET['editid']);
$outs=getSingleAdvert($editid);
echo $outs['adminoutputtwo'];
}elseif ($displaytype=="newstoreaudio") {
	# code...
	// echo $displaytype;
include 'createstoreaudioentry.php';
}elseif ($displaytype=="editstoreaudio") {
	# code...
	// echo $displaytype;
		echo'
		<div id="formend" style="background:#fefefe;">
		Select a type first then click away from the selection box then click the GO button.<br>
		<div id="formend">
			<select name="storecat" class="curved2">
				<option value="all">All Stores</option>
				<option value="1">Frankly Speaking Store</option>
				<option value="2">CSI Store</option>
			</select>
		</div>

		<div id="formend">
				<input type="button" name="viewstores" value="GO" class="submitbutton"/>
			</div>
		</div>';	
}elseif ($displaytype=="viewstores") {
	# code...
	$storecat=mysql_real_escape_string($_GET['storecat']);
$outs=getAllStoreAudio("admin","","$storecat");
echo $outs['adminoutput'];
}elseif ($displaytype=="storetransactions") {
	# code...
	$outs=getAllTransactions("admin","","");
	echo $outs['adminoutput'];
}elseif ($displaytype=="editsinglestoreaudio") {
	# code...
	$editid=mysql_real_escape_string($_GET['editid']);
	$outs=getSingleStoreAudio($editid);
	echo $outs['adminoutputtwo'];
}elseif ($displaytype=="paginationpages") {
	# code...
	// echo $displaytype;
	$curquery=$_GET['curquery'];
	$testq=strpos($curquery,"%'");
	if($testq===0||$testq===true||$testq>0){
	$curquery=str_replace("%'","%",$curquery);
	}
	// $curquery=stripslashes($curquery);
	$outs=paginatejavascript($curquery);
	echo $outs['pageout'];
}elseif ($displaytype=="paginationpagesout") {
	# code...
	// echo $displaytype;
	$curquery=$_GET['curquery'];
	$testq=strpos($curquery,"%'");
	if($testq===0||$testq===true||$testq>0){
	$curquery=str_replace("%'","%",$curquery);
	}
	// echo $curquery;
	$outs=paginatejavascript($curquery);
	$limit=$outs['limit'];
	$type=$_GET['outputtype'];
	$query2=$curquery.$outs['limit'];
	// echo $type;
	$run=mysql_query($query2)or die(mysql_error());
	$numrows=mysql_num_rows($run);
	$otype=$type;
	$nexttype=strpos($type,'advert');
	$nexttype2=strpos($type,'comments');
	$nexttype3=strpos($type,'testimony');
	$nexttype4=strpos($type,'subscribers');
	$nexttype5=strpos($type,'store');
	$nexttype6=strpos($type,'transaction');
	$nexttype7=strpos($type,'generalinfo');
	$nexttype8=strpos($type,'recruitsearch');
	$nexttype9=strpos($type,'sermonsearch');
	if($nexttype===0||$nexttype===true||$nexttype>0){
	$type="advert";
	}elseif ($nexttype2===0||$nexttype2===true||$nexttype2>0) {
		# code...
	$type="comment";
	}elseif ($nexttype3===0||$nexttype3===true||$nexttype3>0) {
		# code...
	$type="testimony";
	}elseif ($nexttype4===0||$nexttype4===true||$nexttype4>0) {
		# code...
	$type="subscribers";
	}elseif ($nexttype5===0||$nexttype5===true||$nexttype5>0) {
		# code...
	$type="store";
	}elseif ($nexttype6===0||$nexttype6===true||$nexttype6>0) {
		# code...
		$type="transaction";
	}elseif ($nexttype7===0||$nexttype7===true||$nexttype7>0) {
		# code...
		$type="generalinfo";
	}elseif ($nexttype8===0||$nexttype8===true||$nexttype8>0) {
		# code...
		$type="recruitsearch";
	}elseif ($nexttype9===0||$nexttype9===true||$nexttype9>0) {
		# code...
		$type="sermonsearch";
	}
	// echo $type."over here";
	if($numrows>0){
	if($type==""){
		while($row=mysql_fetch_assoc($run)){
			echo'code:'.$row['code'].'&nbsp;';
			echo'description:'.$row['description'].'&nbsp;';
			echo'subclass:'.$row['subclass'].'&nbsp;';
			echo'Class:'.$row['class'].'&nbsp;<br>';
		}
		}elseif ($type=="blogtype") {
		# code...
		$outs=getAllBlogTypes("admin",$limit);
		echo $outs['adminoutputtwo'];
	}elseif ($type=="blogcategory") {
		# code...
		$row=mysql_fetch_assoc($run);
		$blogtypeid=$row['blogtypeid'];
		$outs=getAllBlogCategories("admin",$limit,$blogtypeid);
		echo $outs['adminoutputtwo'];
	}elseif ($type=="blogpostcategory") {
		# code...
		$row=mysql_fetch_assoc($run);
		$blogcatid=$row['blogcatid'];
		$outs=getAllBlogEntries("admin",$limit,$blogcatid,'category');
		echo $outs['adminoutputtwo'];
	}elseif ($type=="blogpostblogtype") {
		# code...
		$row=mysql_fetch_assoc($run);
		$blogtypeid=$row['blogtypeid'];
		$outs=getAllBlogEntries("admin",$limit,$blogtypeid,'blogtype');
		echo $outs['adminoutputtwo'];
	}elseif ($type=="blogpostsearch") {
		# code...
	// echo $type;
		$inquery=array();
		$inquery[0]=$extraval;
		$inquery[1]=$curquery;
		// echo $curquery;
		$etypeid=0;
		$etype='nil';
		$row=mysql_fetch_assoc($run);
		$outs=getAllBlogEntries($inquery,$limit,$etypeid,$etype);
		if($extraval=="admin"){			
		echo $outs['adminoutputtwo'];
		}elseif ($extraval=="viewer") {
			# code...
		echo $outs['vieweroutput'];
		}
	}elseif ($type=="comment") {
		# code...
	$data=explode('|',$otype);
	$etype=$data[1];
	$outs=getAllComments("admin",$limit,$etype);
	echo $outs['adminoutputtwo'];
	}elseif ($type=="testimony") {
		# code...
	$data=explode('|',$otype);
	$etype=$data[1];
	$outs=getAllTestimonies("admin",$limit,$etype);
	echo $outs['adminoutputtwo'];
	}elseif ($type=="qotdgeneral"||$type=="qotdpfn") {
		# code...
		$row=mysql_fetch_assoc($run);
		$categorytype=$row['type'];
		$outs=getAllQuotes("admin",$limit,$categorytype);
		echo $outs['adminoutputtwo'];
	}elseif ($type=="eventsfc"||$type=="eventspfn"||$type=="eventscsi"||$type=="eventsfs") {
		# code...
		$row=mysql_fetch_assoc($run);
		$categorytype=$row['type'];
		$outs=getAllEvents("admin",$limit,$categorytype,'','');
		echo $outs['adminoutputtwo'];
	}elseif ($type=="gallery") {
		# code...
		$row=mysql_fetch_assoc($run);
		$outs=getAllGalleries("admin",$limit);
		echo $outs['adminoutputtwo'];
	}elseif ($type=="trendingtopic") {
		# code...
		$row=mysql_fetch_assoc($run);
		$outs=getAllTrendingTopics("admin",$limit);
		echo $outs['adminoutputtwo'];
	}elseif ($type=="playlist") {
		# code...
		$row=mysql_fetch_assoc($run);
		$outs=getAllPlaylist("admin",$limit);
		echo $outs['adminoutputtwo'];
	}elseif ($type=="topaudio") {
		# code...
		$row=mysql_fetch_assoc($run);
		$outs=getAllTopAudio("admin",$limit);
		echo $outs['adminoutputtwo'];
	}elseif ($type=="servicerequests") {
		# code...
		$row=mysql_fetch_assoc($run);
		$outs=getAllServiceRequests("admin",$limit);
		echo $outs['adminoutputtwo'];
	}elseif ($type=="bookings") {
		# code...
		$row=mysql_fetch_assoc($run);
		$outs=getAllBookings("admin",$limit);
		echo $outs['adminoutputtwo'];
	}elseif ($type=="advert") {
		# code...
		$data=explode('|',$otype);
		$etype=$data[1];
		$page=$data[2];
		$row=mysql_fetch_assoc($run);
		$outs=getAllAdverts("admin",$limit,$etype,$page);
		echo $outs['adminoutputtwo'];
	}elseif ($type=="store") {
		# code...
		$data=explode('|',$otype);
		$etype=$data[1];
		// $page=$data[2];
		$row=mysql_fetch_assoc($run);
		$outs=getAllStoreAudio("admin",$limit,$etype);
		echo $outs['adminoutputtwo'];
	}elseif ($type=="transaction") {
		# code...
		$data=explode('|',$otype);
		$etype=$data[1];
		// $page=$data[2];
		$row=mysql_fetch_assoc($run);
		$outs=getAllTransactions("admin",$limit,$etype);
		echo $outs['adminoutputtwo'];
	}elseif ($type=="subscribers") {
		# code...
		$data=explode('|',$otype);
		$etypeid=$data[1];
		$etype=$data[2];
		$row=mysql_fetch_assoc($run);
		$outs=getAllSubscribers("admin",$limit,$etypeid,$etype);
		echo $outs['adminoutputtwo'];
	}elseif ($type=="generalinfo") {
		# code...
		$data=explode('-',$otype);
		$viewer=$data[1];
		$etype=isset($data[2])?$data[2]:"";
		// $page=$data[2];
		$row=mysql_fetch_assoc($run);
		$outs=getAllGeneralInfo("$viewer","$etype",$limit);
		if($etype=="photogallerytwo"){
			echo $outs['vieweroutput'].'<script>
		    $(document).ready(function () {
		        $("[rel=\'tooltip\']").tooltip();

		        $(\'.managementImg\').hover(
		            function () {
		                $(this).find(\'.managementCaption\').slideDown(250); //.fadeIn(250)
		            },
		            function () {
		                $(this).find(\'.managementCaption\').slideUp(250); //.fadeOut(205)
		            }
		        );
		    });
		    </script>';
			
		}else{
			echo $outs['adminoutputtwo'];

		}
	}elseif ($type=="recruitsearch") {
		# code...
		$data=explode('|',$otype);
		$edatain[]=$data[1]; // subtype
		$edatain[]=$data[2]; // searchval
		$edatain[]=$data[3]; // viewer
		$row=mysql_fetch_assoc($run);
		$outs=getAllUsers($edatain,$limit);
		echo $outs['adminoutputtwo'];
	}elseif ($type=="recruits") {
		# code...
		$row=mysql_fetch_assoc($run);
		$outs=getAllUsers("admin",$limit);
		echo $outs['adminoutputtwo'];
	}elseif ($type=="sermons") {
		# code...
		$row=mysql_fetch_assoc($run);
		$outs=getAllSermons("admin",$limit);
		echo $outs['adminoutputtwo'];
	}elseif ($type=="sermonsearch") {
		# code...
		$data=explode('|',$otype);
		$edatain[]=$data[1]; // subtype
		$edatain[]=$data[2]; // searchval
		$edatain[]=$data[3]; // viewer
		$row=mysql_fetch_assoc($run);
		$outs=getAllSermons($edatain,$limit);
		echo $outs['adminoutputtwo'];
	}
	}else{
			echo'No database entries ';		
	}

}elseif ($displaytype=="newpagination") {
		# code...
}elseif ($displaytype=="searchcomments") {
	# code...
	$searchval=mysql_real_escape_string($_GET['searchval']);
	$blogentryid=mysql_real_escape_string($_GET['blogid']);
	if($searchval=="gwolcomments"){
	$query="SELECT * FROM comments where blogentryid=$blogentryid order by id desc";
	$adminoutput='NO comments were found under this blog post';
	}else{
	$query="SELECT * FROM comments where fullname LIKE '%$searchval%' AND blogentryid=$blogentryid OR comment LIKE \"%$searchval%\" AND blogentryid=$blogentryid order by id desc";
	$adminoutput='NO results were found for your search "<b>'.$searchval.'</b>" under this blog post';
	}
	// echo $query."over here";
	$run=mysql_query($query)or die(mysql_error()." Line 287");
	$numrows=mysql_num_rows($run);
	if($numrows>0){
		$adminoutput="";
		while($row=mysql_fetch_assoc($run)){
			$outs=getSingleComment($row['id']);
			$adminoutput.=$outs['adminoutput'];
		}
	}
echo $adminoutput;
}elseif($displaytype=="removecomment"){
$cid=mysql_real_escape_string($_GET['cid']);
genericSingleUpdate("comments","status",'disabled',"id",$cid);
echo "removed";
}elseif ($displaytype=="mainsearch") {
	# code...
	$searchby=mysql_real_escape_string($_GET['searchby']);
	$searchval=mysql_real_escape_string($_GET['mainsearch']);
	if($searchby=="blogtitle"){
		$viewer=$extraval;
		$query="SELECT * FROM blogentries WHERE title LIKE '%$searchval%'";
		$vout=array();
		$vout[0]=$extraval;
		$vout[1]=$query;
		$type="nil";
		$typeid=0;
		$outs=getAllBlogEntries($vout,'',$typeid,$type);
		if($extraval=="admin"){			
			echo $outs['adminoutput'];
		}elseif ($extraval=="viewer") {
				# code...
			echo $outs['vieweroutput'];
		}
	}elseif($searchby=="blogentry"){
		$viewer=$extraval;
		$query="SELECT * FROM blogentries WHERE blogpost LIKE '%$searchval%'";
		$vout=array();
		$vout[0]=$extraval;
		$vout[1]=$query;
		$type="nil";
		$typeid=0;
		$outs=getAllBlogEntries($vout,'',$typeid,$type);
		if($extraval=="admin"){			
		echo $outs['adminoutput'];
		}elseif ($extraval=="viewer") {
			# code...
		echo $outs['vieweroutput'];
		}
	}elseif($searchby=="blogintro"){
		$viewer=$extraval;
		$query="SELECT * FROM blogentries WHERE introparagraph LIKE '%$searchval%'";
		$vout=array();
		$vout[0]=$extraval;
		$vout[1]=$query;
		$type="nil";
		$typeid=0;
		$outs=getAllBlogEntries($vout,'',$typeid,$type);
		if($extraval=="admin"){			
		echo $outs['adminoutput'];
		}elseif ($extraval=="viewer") {
			# code...
		echo $outs['vieweroutput'];
		}
	}elseif($searchby=="recruitname"||$searchby=="recruitstatus"){
		$viewer=$extraval;
		$vout=array();
		$vout[0]=$searchby;
		$vout[1]=$searchval;
		$vout[2]="admin";
		$type="nil";
		$typeid=0;
		$outs=getAllUsers($vout,'');
		if($extraval=="admin"){			
		echo $outs['adminoutput'];
		}elseif ($extraval=="viewer") {
			# code...
		echo $outs['vieweroutput'];
		}
	}else{
		echo "Unrecognized searchby option <b>$searchby</b>";
	}
}
?>