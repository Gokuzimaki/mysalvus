<?php
include('connection.php');
// so we can get @ session variables
if(session_id() == ''){
	session_start();
}
// so we can get @ session variables
if(session_id() == ''){
	session_start();
}
// set up communication variables
$displaytype="";
$displaytest="get";
$test="";
$dhash="true";
$extraval="admin";
$datamap="";
$todaynow=date("Y-m-d H:i:s");
$reqcurstamp="";
if(isset($_GET['displaytype'])){
	$displaytype=$_GET['displaytype'];
	$displaytest="get";
}
if (isset($_POST['displaytype'])) {
	$displaytype=$_POST['displaytype'];	
	$displaytest="post";
}
if(isset($_GET['curstamp'])){
	$reqcurstamp=$_GET['curstamp'];
}
if(isset($_POST['curstamp'])){
	$reqcurstamp=$_POST['curstamp'];
}
if(isset($_GET['extraval'])){
	$extraval=$_GET['extraval'];
}
if(isset($_POST['extraval'])){
	$extraval=$_POST['extraval'];
}
if(isset($_GET['editid'])){
	$editid=$_GET['editid'];
}
if(isset($_POST['editid'])){
	$editid=$_POST['editid'];
}
if(isset($_GET['datamap'])){
	
	$datamap=$_GET['datamap'];
}
	// echo "the data map: $datamap<br>";

if(isset($_POST['datamap'])){
	$datamap=$_POST['datamap'];
}
	// echo "the data map: $datamap<br>";

if(isset($_GET['test'])){
	$test=$_GET['test'];
}
if(isset($_POST['test'])){
	$test=$_POST['test'];
}
// for custom generaldatamodule
$test_gdunit=strpos($displaytype, "_gdunit");
if($test_gdunit===true||$test_gdunit>-1){
	// $displaytype="_gdunit";
	// echo $displaytype;
	// important, a data map must be present
	// datamap is a json string to be converted into phpjson 
	// via the jsontophp function
	// echo "the data map: $datamap<br>";
	if($datamap!==""){
		$gdtest_data=JSONtoPHP($datamap);
		$gd_testoutput=$gdtest_data['arrayoutput'];
		$_gdunit_viewtype=$gd_testoutput['vt']?$gd_testoutput['vt']:"create";
		
		include('gdunitdatamapinit.php');
		// there must be a valid process file path stored with an index of
		// "pr","processroute" e.g "pr" => "relative path from root to include file"
		// the path will have the 
		if(isset($processroute)&&file_exists("../".$processroute)){
			include("../".$processroute);
		}else{
			echo "<div>An error occured, invalid Process Route $processroute $_gdunit_viewtype".__LINE__."</div>";
		}
	}else{
		echo "<div>Missing data map for handling content</div>";
	}

}else if($displaytype=="pullfontawesomelist"){
	$outfa=pullFontAwesomeClasses();
	sort($outfa['faiconnames']);
	sort($outfa['famatches']);
	if(isset($_GET['fatype'])){
		if($_GET['fatype']=="list"){
			$list="";
			if($outfa['numrows']>0){
				for ($x = 0;$x < $outfa['numrows'];$x++) 
				{ 
					if(isset($outfa['faiconnames'][$x])&&isset($outfa['famatches'][$x])){

						$omid=$outfa['famatches']['namematch'][$outfa['faiconnames'][$x]];
						$outname=$outfa['famatches'][$omid];
					    $list.='
					    		<a href="##" 
		    						data-type="fapicker" 
		    						data-toggle="tooltip" 
		    						data-original-title="'.$outfa['faiconnames'][$x].'"
		    						title="'.$outfa['faiconnames'][$x].'" 
		    						value="'.$outname.'">
			    					<li class="">
		    							<i class="fa '.$outname.'"></i>
		    						</li>
				    			</a>
				    			';
				    	$selection.='<option value="'.$outname.'">'.$outfa['faiconnames'][$x].'</option>';
    					// echo "<br>cur-dataout: ".$outfa['faiconnames'][$x]." \t cur-rdata: ".$outname."<br>";

					}
				}
			}
			json_encode(array("success"=>"true","list"=>"$list","faiconnames"=>$outfa['faiconnames'],"famatches"=>$outfa['famatches'],"arraylength"=>count($outfa['famatches'])));

		}
	}else{
		// return the outputs in json form
		json_encode(array("success"=>"true","faiconnames"=>$outfa['faiconnames'],"famatches"=>$outfa['famatches'],"arraylength"=>count($outfa['famatches'])));
	}
}elseif($displaytype=="createbooking"){
	// echo $displaytype;
	include('createbooking.php');
}else if($displaytype=="testextradata"){
	// perform geparser module test
	$datatype[0]="hometopcollagebox";
	// $datatype[0]="testsingleparsedata";
	// $datatype[0]="testgroupparsedata";
	// $datatype[3]="rundouble";

	$testdata=getSingleGeneralInfo("",$datatype);
	if(isset($testdata['edresultset'])){
		// var_dump($testdata['edresultset']);
		// echo "<br>".$testdata['edresultset'][0]['fieldname']." - a field name<br>";
		// echo "<br>".$testdata['edresultset']['group1'][0][0]['fieldname']." - a group fieldname<br>";
		// echo "<br>".$testdata['edresultset']['group1'][0]['linktitlea']['value']." - Linktitlea<br>";
		// echo "<br>".$testdata['edresultset']['collagecolourtype']['value']." - Collage Colour box<br>";
	}
}elseif($displaytype=="servicerequest"){
// echo $displaytype;
include('createservicerequest.php');
}elseif($displaytype=="testimonyrequest"){
// echo $displaytype;
include('createtestimony.php');

}elseif($displaytype=="photogallery"){
	$outs=getAllGeneralInfo("admin","$displaytype","");
	echo $outs['fullviewtwo'];
}elseif($displaytype=="homegallery"){
	echo $displaytype;
}elseif($displaytype=="users"||$displaytype=="usersnew"||$displaytype=="usersedit"){
	// echo $displaytype;
	include('createuseracc.php');
}elseif($displaytype=="serviceproviders"||$displaytype=="serviceprovidersnew"||$displaytype=="serviceprovidersedit"){
	// echo $displaytype;
	include('createclientacc.php');
}elseif($displaytype=="incidents"||$displaytype=="incidentsnew"
	||$displaytype=="incidentsedit"||$displaytype=="incidentseditsaved"){
	// echo $displaytype;
	include('createincident.php');
}elseif($displaytype=="cases"||$displaytype=="usercases"){
	// echo $displaytype;
	$outsdata=getAllIncidents("admin","","","","editsingleincidentcaseadmin");
	$admindata="adminoutput";
	if($displaytype=="usercases"){
		$userid=$_SESSION['userimysalvus'.$_SESSION['userhmysalvus'].''];
		$type[0]="userview";
		$type[1]=$userid;
		$outsdata=getAllIncidents("viewer","",$type,"","editsingleincidentcase");
		$admindata="adminoutput2";
	}
	echo $outsdata[''.$admindata.''];

}elseif($displaytype=="incidents"||$displaytype=="userincidents"
	||$displaytype=="edituserincidents"||$displaytype=="editsaveduserincidents"){
	// echo $displaytype;
	$userset="";
	if($displaytype=="userincidents"||$displaytype=="edituserincidents"
		||$displaytype=="editsaveduserincidents"){
		$userset="true";
	}
	include('createincident.php');
}elseif($displaytype=="expincident"){
	// echo $displaytype;
	include('export.php');

}elseif($displaytype=="exportincidents"){
	// echo $displaytype;
	$type="";
	$exporttype=mysql_real_escape_string($_GET['exporttype']);
	$asettings=mysql_real_escape_string($_GET['asettings']);
	if($exporttype=="exportrangeincidents"){
		$gender=mysql_real_escape_string($_GET['gender']);
		$state=mysql_real_escape_string($_GET['state']);
		$incnature=mysql_real_escape_string($_GET['incnature']);
		$ageold=mysql_real_escape_string($_GET['ageold']);
		$agenew=mysql_real_escape_string($_GET['agenew']);
		$dpold=mysql_real_escape_string($_GET['dpold']);
		$dpnew=mysql_real_escape_string($_GET['dpnew']);
		$props="gender**state**incidentnature**agerange**daterange";
		$vals="$gender**$state**$incnature**$ageold-*-$agenew**$dpold-*-$dpnew";
		$typevals="$props:$vals";
		$type[0]="combo";
		$type[1]=$typevals;
		
	}else if($exporttype=="exportallincidents"){
		// $type['single']=$exporttype;
	}
	$type['anonymous']=$asettings;
	// exportAllIncidents("",$type);
	// echo var_dump($type);
	$outs=exportIncidents(0,$type);

}elseif(strpos($displaytype,"_gdmodule")){
	$splitdata=explode("_gdmodule", $displaytype);
	$entrytype=$splitdata[0];
	// check to see if the current entry is in the test array for 
	// maintypes and subtypes that use the extradata system
	$testdata=getExtradataModuleTypes();
	if(in_array("$entrytype", $testdata['maintype'])||in_array("$entrytype", $testdata['subtype'])){
		$dogeneraldata="multiple";
		$key="";
		$key = array_search("$entrytype", $testdata['maintype']);
		$cursubtype="";
		if($key!==""){
			$cursubtype=$testdata['maintype']["$entrytype"];
		}

		$processfile="process$cursubtype.php";
		if(file_exists("$processfile")){
			include("$processfile");
			
		}
	}else{
		$outs=getAllGeneralInfo("admin","$entrytype","");
		echo $outs['fullviewtwo'];
		
	}
}elseif(strpos($displaytype,"_gdmcreate")){
	$splitdata=explode("_gdmcreate", $displaytype);
	$entrytype=$splitdata[0];
	// check to see if the current entry is in the test array for 
	// maintypes and subtypes that use the extradata system
	$testdata=getExtradataModuleTypes();
	$key="";
	$cursubtype="";
	if(in_array("$entrytype", $testdata['maintype'])){
		$dogeneraldata="multiple";
		$key = array_search("$entrytype", $testdata['maintype']);
		if($key!==""){
			$cursubtype=$testdata['maintype']["$entrytype"];

		}
		$formtruetype=$cursubtype;
		$createfile="create$cursubtype.php";
		$dogeneraldata="multiple";
		if(file_exists("$createfile")){
			include("$createfile");
		}
	}else{
		echo $displaytype;
		
	}
}elseif(strpos($displaytype,"_gdmgallerystream")){
	$splitdata=explode("_gdmgallerystream", $displaytype);
	$entrytype=$splitdata[0];
	// check to see if the current entry is in the test array for 
	// maintypes and subtypes that use the extradata system
	
	include('gallerystreamhandler.php');
}elseif($displaytype=="defaultinfo"){
	$outs=getAllGeneralInfo("admin","$displaytype","");
	echo $outs['fullviewtwo'];
}elseif($displaytype=="defaultsocial"){
	$outs=getAllGeneralInfo("admin","$displaytype","");
	echo $outs['fullviewtwo'];
}elseif($displaytype=="productservices"){
	include('createservicescontent.php');
}elseif($displaytype=="directorsection"||
	$displaytype=="trustees"||
	$displaytype=="chartersection"){
	include('createaboutcontent.php');
}elseif($displaytype=="homevision"||
	$displaytype=="homemission"||
	$displaytype=="resourcewelcomemsg"||
	$displaytype=="homewelcomemsg"||
	$displaytype=="aboutwelcomemsg"||
	$displaytype=="servicewelcomemsg"||
	$displaytype=="infodeskwelcomemsg"||
	$displaytype=="contactwelcomemsg"||
	$displaytype=="portfoliogallerywelcomemsg"||
	$displaytype=="homevalues"){
	$outs=getAllGeneralInfo("admin","$displaytype","");
	echo $outs['fullviewthree'];
}elseif($displaytype=="donationdatasection"){
	$outs=getAllGeneralInfo("admin","$displaytype","");
	echo $outs['fullviewthree'];
}elseif($displaytype=="homebanners"){
	// echo $displaytype;
	include('createhomebanner.php');
}elseif($displaytype=="homebanner"){
	// echo $displaytype;
	include('createhomebanners.php');
}elseif($displaytype=="editsinglehomebanner"){
	// echo $displaytype;
	// get prevdetailsdata
	$editid=$_GET['editid'];
	$slideindex=0;
	$sq="SELECT * FROM media WHERE ownertype='homebanner'";
	$sqdata=briefquery($sq,__LINE__);
	if($sqdata['numrows']>0){
		for($i=0;$i<=$sqdata['numrows'];$i++){
			if($sqdata['resultdata'][$i]['id']==$editid){
				$slideindex=$sqdata['resultdata'][$i]['mainid'];
				break;
			}			
		}
	}
	$outs=getSingleHomeBanner($editid,$slideindex,$sqdata['numrows']);
	echo $outs['adminoutputtwo'];
}elseif(strpos($displaytype, "collagebox")){
	// echo $displaytype;
	$pagetype=$displaytype;
	include('createcollagebox.php');
}elseif($displaytype=="editgeneraldata"){
	$gd_data=JSONtoPHP($datamap);	
	$gdtest_data=JSONtoPHP($datamap);
	$gd_testoutput=$gdtest_data['arrayoutput'];
	$_gdunit_viewtype=$gd_testoutput['vt']?$gd_testoutput['vt']:"create";
	// echo $_gdunit_viewtype;
	// var_dump($gdtest_data);
	include('gdunitdatamapinit.php');
	// there must be a valid process file path stored with an index of
	// "pr","processroute" e.g "pr" => "relative path from root to include file"
	// the path will have the 
	if(file_exists("../".$editroute)){
		// $contentgroupdata['editmap']="";
			include("../".$editroute);
	}else{
		echo "edit route file invalid";
	}
}elseif($displaytype=="editsinglegeneraldata"){
	// echo "datamap: ".$datamap;

	$verify=getSingleGeneralInfoPlain($editid);
	if($datamap!==""){
		$gd_data=JSONtoPHP($datamap);
		
		$gdtest_data=JSONtoPHP($datamap);
		$gd_testoutput=$gdtest_data['arrayoutput'];
		$_gdunit_viewtype=$gd_testoutput['vt']?$gd_testoutput['vt']:"create";

		include('gdunitdatamapinit.php');
		// there must be a valid process file path stored with an index of
		// "pr","processroute" e.g "pr" => "relative path from root to include file"
		// the path will have the 
		if(file_exists("../".$editroute)){
			// $contentgroupdata['editmap']="";
			if(isset($ugi)&&$ugi==true){
				$outs=getSingleGeneralInfo($editid,"",$contentgroupdatageneral);
				// var_dump($contentgroupdatageneral);
				echo $outs['adminoutputtwo'];
			}else{
				include("../".$editroute);
			}
		}else{
			echo "<div>An error occured, invalid Edit Route</div>";
		}
	}else{
		if($verify['extradata']!==""){
			$datatype[3]="rundouble";
			$outs=getSingleGeneralInfo("$editid","",$datatype);
			echo $outs['adminoutputtwo'];
		}else{
			$data[6]=$verify['maintype'];
			// echo $verify['maintype'];
			$outs=getSingleGeneralInfo($editid,$data);
			echo $outs['adminoutputtwo'];
		}
		
	}
}else if($displaytype=="verifyemail"){
 $email=$_GET['email'];
 $emaildata=checkEmail($email,"recruits","email");
 if($emaildata['testresult']=="unmatched"){
 	echo "OK";
 }else {
 	echo "Your email address already exists for another user, are you the same one? Did you forget your Password?, try login in or contacting us if your problem persists";
 }
	// include("createsurvey.php");
}else if($displaytype=="verifyemaildefault"){
	if($displaytest=="post"){
		$email=$_POST['email'];
		$tblname=isset($_POST['tablename'])&&$_POST['tablename']!==""?
		$_POST['tablename']:"users";
		$tblfield=isset($_POST['tablefield'])&&$_POST['tablefield']!==""?
		$_POST['tablefield']:"email";
		$retval=isset($_POST['retval'])&&$_POST['retval']!==""?$_POST['retval']:"";
		$extradata=isset($_POST['extradata'])&&$_POST['extradata']!==""?
		$_POST['extradata']:"";
	}else{
		$email=$_GET['email'];
		$tblname=isset($_GET['tablename'])&&$_GET['tablename']!==""?
		$_GET['tablename']:"users";
		$tblfield=isset($_GET['tablefield'])&&$_GET['tablefield']!==""?
		$_GET['tablefield']:"email";
		$retval=isset($_GET['retval'])&&$_GET['retval']!==""?$_GET['retval']:"";
		$extradata=isset($_GET['extradata'])&&$_GET['extradata']!==""?
		$_GET['extradata']:"";
		
	}
	$extradataout="";
	if($extradata!==""){
		// parse the extra data as key value pairs representing db table column and 
		// table column value
		$extradataout=json_decode($extradata,true);
		if(json_last_error_msg()=="No error"){
			// echo "no error";
			$et=$email;
			$email=$extradataout;
			$email['email']=$et;
		}
	}
	$msg="";
	$success="";
	$emaildata=checkEmail($email,"$tblname","$tblfield",$extradataout);
	if($emaildata['testresult']=="unmatched"){
		$msg= "OK";
		$success="true";
	}else {
		$success="false";
		$msg="Your email address already exists for another entry, are you the same one? Did you forget your Password?, try to login or contact us if your problem persists";
	}
	if($retval!==""){
		if($retval=="json"){
			echo json_encode(array("success"=>"$success","msg"=>"$msg"));
		}else{
			echo $msg;
		}
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
}else if($displaytype=="defaultdata"){
	// basic section for tending to ajax data requests
	$subtype=$_GET['subtype'];
	$curval=isset($_GET['curval'])?$_GET['curval']:"";
	$success="true";
	$msg="";
	if($subtype=="spstate"){
		$biznature=$_GET['cspnature'];
		$typearr[0]="combo";
		$typearr[1]="businessnature**state:$biznature**$curval";

		$csps=getAllUsers("viewer","","serviceprovider",'',$typearr);
		$msg=$csps['selectiontwo'];
	}else if($subtype=="crrequests"||$subtype=="crcases"||$subtype=="crstatus"||
		$subtype=="crtransfer"||$subtype=="crreport"){
		$incid=$_GET['incid'];
		$userset=isset($_GET['userset'])?$_GET['userset']:"";
		$spnature=$_GET['spnature'];
		$vars[0]="incid";
		$vars[1]="spnature";
		$vars[2]="subtype";
		$vars[3]="userset";
		$vals[0]=$incid;
		$vals[1]=$spnature;
		$vals[2]=$subtype;
		$vals[3]=$userset;
		$op=get_include_contents('caseoptionsadmin.php',$vars,$vals);
		$msg=$op;
		unset($op);
	}else if($subtype=="inittransfer"){
		// get transferdata
		$incid=$_GET['incidentid'];
		$caseid=$_GET['caseid'];
		$spnature=$_GET['spnature'];
		// oldservice provider id
		$oldspid=$_GET['oldspid'];
		$newspid=$_GET['newspid'];
		$details=$_GET['details'];

		$query="INSERT INTO casetransfer (caseid,incidentid,spid,spnature,ispid,details,entrydate)
				VALUES
				('$caseid','$incid','$oldspid','$spnature','$newspid','$details',CURRENT_DATE())";
		// make sure there are no pending transfer with the exact data details for
		// current set
		$testq="SELECT * FROM casetransfer WHERE caseid='$caseid' 
		AND incidentid='$incid' AND spid='$oldspid' AND acceptancestatus='pending'";
		$tqdata=briefquery($testq,__LINE__,"mysqli");
		if($tqdata['numrows']<1){
			$qdata=briefquery($query,__LINE__,"mysqli",true);
			// update cases table
			$upq="UPDATE cases SET resolutionstatus='transfer'";
			$upqdata=briefquery($upq,__LINE__,"mysqli",true);
			$msg="Successfully initiated case transfer";
		}else{
			$msg="Transfer already in progress";
			$success="false";
		}
		

	}else if($subtype=="acceptedtransfer"){
		$id=$_GET['tranferid'];
		$spid=$_GET['spid'];
		$caseid=$_GET['caseid'];
		$incid=$_GET['incidentid'];
		// update transfer table
		$upq="UPDATE casetransfer SET acceptancestatus='accepted' AND acceptancedate=CURRENT_DATE() WHERE id='$id'";
		$upqdata=briefquery($upq,__LINE__,"mysqli",true);
		// update case table table
		$upq="UPDATE cases SET resolutionstatus='ongoing' AND spid='$spid' WHERE id='$caseid'";
		$upqdata=briefquery($upq,__LINE__,"mysqli",true);
	}
	echo json_encode(array("success"=>"$success","msg"=>"$msg"));

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
	$data['blogtypeid']=$blogtypeid;
	$outs=getAllBlogCategories("viewer","all","",$data);
	$select="";
	$select.=$outs['selectiondata'];
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

}elseif ($displaytype=="editscheduledposts") {
	# code...
	// echo $displaytype;
	$displaytype="scheduledposts";
	$outs=getAllBlogTypes("admin","");
	echo'
		<div id="formend" style="background:#fefefe;">
			<input type="hidden" name="varianttype" value="'.$displaytype.'"/>
			Select a Blog type first then click away from the selection box, the second selection box will be populated with the categories under the chosen blog type, now you can choose to either edit all blog posts under a category or under a blog type by chosing a type then a category (view category) or only a type(view all posts under the blog), click the go button when done to view your output.<br>
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
				<input type="button" name="viewblogpostsoptional" value="GO" class="submitbutton"/>
			</div>
		</div>
	';

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
}elseif ($displaytype=="viewblogpostsoptional") {
	# code...
	// echo $displaytype;
	$blogtypeid=mysql_real_escape_string($_GET['blogtypeid']);
	$blogcategoryid=mysql_real_escape_string($_GET['blogcategoryid']);
	$typeentry=$extraval;
	if($blogtypeid!==""&&$blogcategoryid==""){
		$typeentry==""?$typeentry=="blogtype":$typeentry;
		$outs=getAllBlogEntries("admin","",$blogtypeid,''.$typeentry.'');
		if ($typeentry=="scheduledposts") {
			# code...
			echo $outs['adminoutputthree'];
		}
	}elseif ($blogcategoryid!=="") {
		# code...
		$typeentry==""?$typeentry=="category":$typeentry;
		$outs=getAllBlogEntries("admin","",$blogcategoryid,''.$typeentry.'');
		if ($typeentry=="scheduledposts") {
			# code...
			echo $outs['adminoutputthree'];
		}
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
}elseif ($displaytype=="eventdata") {
	include('evententrieshandler.php');
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
}elseif ($displaytype=="editsingleevententry") {
	$editid=$_GET['editid'];
	$outs=getSingleEventEntry($editid);
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
}elseif($displaytype=="newbranch"){
	// echo $displaytype;
	include('createnewbranch.php');
}elseif ($displaytype=="editsinglebranch") {
	# code...
	$editid=mysql_real_escape_string($_GET['editid']);
	$outs=getSingleBranch($editid);
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
}elseif ($displaytype=="editsingleuseraccadmin"||$displaytype=="editsingleuseracc") {
	# code...
	// echo $displaytype;
	$edittype=$displaytype=="editsingleuseraccadmin"?"edituseraccadmin":"edituseracc";
	if($edittype=="edituseraccadmin"){
		$oid=$_GET['editid'];
	}else{
		$oid=$_SESSION['userimysalvus'.$_SESSION['userhmysalvus'].''];
		$douser="true";
	}
	$formtruetype="edituseraccform";
	include('editsingleuseracc.php');
}elseif ($displaytype=="editsingleclientaccadmin"||$displaytype=="editsingleclientacc") {
	# code...
	// echo $displaytype;
	$edittype=$displaytype=="editsingleclientaccadmin"?"editclientaccadmin":"editclientacc";
	if($edittype=="editclientaccadmin"){
		$oid=$_GET['editid'];
		$douser="";
	}else{
		$oid=$_SESSION['clientimysalvus'.$_SESSION['clienthmysalvus'].''];
		$douser="true";
	}
	$formtruetype="editserviceprovideraccform";
	include('editsingleclientacc.php');
}elseif ($displaytype=="editsingleincidentadmin"||$displaytype=="editsingleincident") {
	# code...
	// echo $displaytype;
	$edittype="editincidentadmin";
	if($displaytype=="editsingleincident"){
		$edittype="editincident";

	}
	$oid=$_GET['editid'];
	$formtruetype="editincidentform";
	include('editsingleincident.php');
}elseif ($displaytype=="editsingleincidentcaseadmin"||$displaytype=="editsingleincidentcase") {
	# code...
	// echo $displaytype;
	$edittype=$displaytype=="editsingleincidentcaseadmin"?"editincidentcaseadmin":"editincidentcase";
	$iid=$_GET['editid'];
	$formtruetype="editincidentcaseform";
	include('caseformadmin.php');
}elseif ($displaytype=="clientincidents") {
	# code...
	// echo $displaytype;
	// $iid=$_GET['editid'];
	if(isset($_SESSION['clienthmysalvus'])&&$_SESSION['clienthmysalvus']!==""){
		$clientid=$_SESSION['clientimysalvus'.$_SESSION['clienthmysalvus'].''];

		$type[0]="clientview";
		$type[1]=$clientid;
		$cdata=getSingleUserPlain($clientid);
		// verify that the current client user is uising a validated account
		if($cdata['activationstatus']=="active"){
			
			$outsdata=getAllIncidents("viewer","",$type,"adminoutputtwo2","createclientcase");
			echo $outsdata['adminoutput2'];

		}else{
			echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" 
                    aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Access Denied.</h4>
                    <p>Sorry, your account has not been verified on the platform, 
                    therefore you cannot view cases or attend to anything on the 
                    platform yet. When your account is verified a message would be 
                    sent to your account\'s email to alert you. Thank You.
                    </p>
                </div>';
		}
		
	}else{
		echo "No session detected, logout then login again to get access";
	}
}elseif ($displaytype=="createclientcase") {
	# code...
	// echo $displaytype;
	$iid=$_GET['editid'];
	if(isset($_SESSION['clienthmysalvus'])&&$_SESSION['clienthmysalvus']!==""){
		$userset="true";
	}else{
		$userset="";
		// echo "No session detected, logout then login again to get access";
	}
	include('createclientcaseform.php');
}elseif ($displaytype=="caserequests") {
	# code...
	// echo $displaytype;
	// $iid=$_GET['editid'];
	if(isset($_SESSION['clienthmysalvus'])&&$_SESSION['clienthmysalvus']!==""){
		$clientid=$_SESSION['clientimysalvus'.$_SESSION['clienthmysalvus'].''];

		$type[0]="clientcaserequests";

		$type[1]=$clientid;
		$cdata=getSingleUserPlain($clientid);
		// verify that the current client user is uising a validated account
		if($cdata['activationstatus']=="active"){
			$outsdata=getAllIncidents("viewer","",$type,"adminoutputtwo2","editclientrequest");
			echo $outsdata['adminoutput2'];
		
		}else{
			echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" 
                    aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Access Denied.</h4>
                    <p>Sorry, your account has not been verified on the platform, 
                    therefore you cannot view cases or attend to anything on the 
                    platform yet. When your account is verified a message would be 
                    sent to your account\'s email to alert you. Thank You.
                    </p>
                </div>';
		}
		
	}else{
		echo "No session detected, logout then login again to get access";
	}
}elseif ($displaytype=="editclientrequest") {
	# code...
	// echo $displaytype;
	$iid=$_GET['editid'];
	if(isset($_SESSION['clienthmysalvus'])&&$_SESSION['clienthmysalvus']!==""){
		$userset="true";
	}else{
		$userset="";
		// echo "No session detected, logout then login again to get access";
	}
	include('editclientcaserequest.php');
}elseif ($displaytype=="ongoingcases") {
	# code...
	// echo $displaytype;
	// $iid=$_GET['editid'];
	if(isset($_SESSION['clienthmysalvus'])&&$_SESSION['clienthmysalvus']!==""){
		$clientid=$_SESSION['clientimysalvus'.$_SESSION['clienthmysalvus'].''];

		$type[0]="clientcaseview";

		$type[1]=$clientid;
		$cdata=getSingleUserPlain($clientid);
		// verify that the current client user is uising a validated account
		if($cdata['activationstatus']=="active"){
			$outsdata=getAllIncidents("viewer","",$type,"adminoutputtwo2","editclientcase");
			echo $outsdata['adminoutput2'];
		}else{
			echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" 
                    aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Access Denied.</h4>
                    <p>Sorry, your account has not been verified on the platform, 
                    therefore you cannot view cases or attend to anything on the 
                    platform yet. When your account is verified a message would be 
                    sent to your account\'s email to alert you. Thank You.
                    </p>
                </div>';
		}
		
	}else{
		echo "No session detected, logout then login again to get access";
	}
}elseif ($displaytype=="editclientcase") {
	# code...
	// echo $displaytype;
	$iid=$_GET['editid'];
	if(isset($_SESSION['clienthmysalvus'])&&$_SESSION['clienthmysalvus']!==""){
		$userset="true";
	}else{
		$userset="";
		// echo "No session detected, logout then login again to get access";
	}
	include('incidentdetailscomplete.php');
}elseif ($displaytype=="casetransfers") {
	# code...
	// echo $displaytype;
	// $iid=$_GET['editid'];
	if(isset($_SESSION['clienthmysalvus'])&&$_SESSION['clienthmysalvus']!==""){
		$clientid=$_SESSION['clientimysalvus'.$_SESSION['clienthmysalvus'].''];

		$type[0]="spidinactive";

		$type[1]=$clientid;
		$cdata=getSingleUserPlain($clientid);
		// verify that the current client user is uising a validated account
		if($cdata['activationstatus']=="active"){
			$outsdata=getAllCases("viewer","",$type,"adminoutputtwo2","editcasetransfer");
			echo $outsdata['adminoutput2'];
		}else{
			echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" 
                    aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Access Denied.</h4>
                    <p>Sorry, your account has not been verified on the platform, 
                    therefore you cannot view cases or attend to anything on the 
                    platform yet. When your account is verified a message would be 
                    sent to your account\'s email to alert you. Thank You.
                    </p>
                </div>';
		}
		
	}else{
		echo "No session detected, logout then login again to get access";
	}
}elseif ($displaytype=="editcasetransfer") {
	# code...
	// echo $displaytype;
	$iid=$_GET['editid'];
	if(isset($_SESSION['clienthmysalvus'])&&$_SESSION['clienthmysalvus']!==""){
		$userset="true";
	}else{
		$userset="";
		echo "No session detected, logout then login again to get access";
	}
	include('casetransferform.php');
}elseif ($displaytype=="inboundtransfers") {
	# code...
	// echo $displaytype;
	// $iid=$_GET['editid'];
	if(isset($_SESSION['clienthmysalvus'])&&$_SESSION['clienthmysalvus']!==""){
		$clientid=$_SESSION['clientimysalvus'.$_SESSION['clienthmysalvus'].''];

		$type[0]="ispid";

		$type[1]=$clientid;
		$cdata=getSingleUserPlain($clientid);
		// verify that the current client user is uising a validated account
		if($cdata['activationstatus']=="active"){
			$outsdata=getAllCaseTransfers("viewer","",$type,"adminoutputtwo2","editinboundtransfer");
			echo $outsdata['adminoutput2'];
		}else{
			echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" 
                    aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Access Denied.</h4>
                    <p>Sorry, your account has not been verified on the platform, 
                    therefore you cannot view cases or attend to anything on the 
                    platform yet. When your account is verified a message would be 
                    sent to your account\'s email to alert you. Thank You.
                    </p>
                </div>';
		}
		
	}else{
		echo "No session detected, logout then login again to get access";
	}
}elseif ($displaytype=="editinboundtransfer") {
	# code...
	// echo $displaytype;
	$iid=$_GET['editid'];
	if(isset($_SESSION['clienthmysalvus'])&&$_SESSION['clienthmysalvus']!==""){
		$userset="true";
	}else{
		$userset="";
		echo "No session detected, logout then login again to get access";
	}
	include('caseinboundtransferform.php');
}elseif ($displaytype=="newcasereports"||$displaytype=="editcasereports") {
	# code...
	// echo $displaytype;
	// $iid=$_GET['editid'];
	$eotype=$displaytype=="newcasereports"?"newreportform":"editreportform";
	if(isset($_SESSION['clienthmysalvus'])&&$_SESSION['clienthmysalvus']!==""){
		$clientid=$_SESSION['clientimysalvus'.$_SESSION['clienthmysalvus'].''];

		$type[0]="spidinactive";
		$type[1]=$clientid;
		$cdata=getSingleUserPlain($clientid);
		// verify that the current client user is uising a validated account
		if($cdata['activationstatus']=="active"){
			$outsdata=getAllCases("viewer","",$type,"adminoutputtwo2","$eotype");
			echo $outsdata['adminoutput2'];
		}else{
			echo '<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" 
                    aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Access Denied.</h4>
                    <p>Sorry, your account has not been verified on the platform, 
                    therefore you cannot view cases or attend to anything on the 
                    platform yet. When your account is verified a message would be 
                    sent to your account\'s email to alert you. Thank You.
                    </p>
                </div>';
		}
	}else{
		echo "No session detected, logout then login again to get access";
	}
}elseif ($displaytype=="newreportform"||$displaytype=="editreportform") {
	# code...
	// echo $displaytype;
	$iid=$_GET['editid'];
	if(isset($_SESSION['clienthmysalvus'])&&$_SESSION['clienthmysalvus']!==""){
		$userset="true";
	}else{
		$userset="";
		// echo "No session detected, logout then login again to get access";
	}
	if($displaytype=="newreportform"){
		include('createcasereport.php');
	}else{
		include('editcasereport.php');

	}
}elseif ($displaytype=="editreportform") {
	# code...
	// echo $displaytype;
	$iid=$_GET['editid'];
	if(isset($_SESSION['clienthmysalvus'])&&$_SESSION['clienthmysalvus']!==""){
		$userset="true";
	}else{
		$userset="";
		// echo "No session detected, logout then login again to get access";
	}
	include('createcasereport.php');
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
}elseif ($displaytype=="allcomments"||$displaytype=="all_comments") {
	# code...
	// echo $displaytype;
	$typeout="all";
	$data=array();
	if(strpos($displaytype, "_")===true||
		strpos($displaytype, "_")>=0){
		$data['queryextra']="";	
		$outs=getAllComments("admin","",$typeout,$data);
	}else{
		$outs=getAllComments("admin","",$typeout);
	}
	
	echo $outs['adminoutput'];
}elseif ($displaytype=="activecomments"||$displaytype=="active_comments") {
	# code...
	// echo $displaytype;
	$typeout="active";
	$data=array();
	if(strpos($displaytype, "_")===true||
		strpos($displaytype, "_")>=0){
		$data['queryextra']=" status='active'";	
		$outs=getAllComments("admin","",$typeout,$data);
	}else{
		$outs=getAllComments("admin","",$typeout);
	}
	echo $outs['adminoutput'];
}elseif ($displaytype=="inactivecomments"||$displaytype=="inactive_comments") {
	# code...
	// echo $displaytype;
	$typeout="inactive";
	$data=array();
	if(strpos($displaytype, "_")===true||
		strpos($displaytype, "_")>=0){
		$data['queryextra']=" status='inactive'";	
		$outs=getAllComments("admin","",$typeout,$data);
	}else{
		$outs=getAllComments("admin","",$typeout);
	}
	echo $outs['adminoutput'];
}elseif ($displaytype=="disabledcomments"||$displaytype=="disabled_comments") {
	# code...
	// echo $displaytype;
	$typeout="disabled";	
	$data=array();
	if(strpos($displaytype, "_")===true||
		strpos($displaytype, "_")>=0){
		$data['queryextra']=" status='disabled'";	
		$outs=getAllComments("admin","",$typeout,$data);
	}else{
		$outs=getAllComments("admin","",$typeout);
	}

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
}elseif($displaytype=="recommendation"||$displaytype=="clientelle"||$displaytype=="testimonial"){
	// echo $displaytype;
	$formcontentdisplaycontrol=$displaytype;
	include('createclientnrecommendation.php');
}elseif($displaytype=="editsinglecnrnt"){
	// echo $displaytype;
	$editid=$_GET['editid'];
	$outs=getSingleClientRecommendation($editid);
	echo $outs['adminoutputtwo'];
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
	$typeout=isset($_GET['loadtype'])?$_GET['loadtype']:"";
	$typeout=isset($_POST['loadtype'])?$_POST['loadtype']:"";
	$curquery="";
	$curquery=isset($_GET['curquery'])?$_GET['curquery']:$curquery;
	$curquery=isset($_POST['curquery'])?$_POST['curquery']:$curquery;
	$curquery=str_replace("_asterisk_","*",$curquery);
	$testq=strpos($curquery,"%'");
	$data=array();
	$data['ipparr_override']="";
	if($testq===0||$testq===true||$testq>0){
	// $curquery=str_replace("%'","%",$curquery);
	}
	// $curquery=stripslashes($curquery);
	$ippdata="";
	$ippdata=isset($_GET['ippdata'])?$_GET['ippdata']:$ippdata;
	$ippdata=isset($_POST['ippdata'])?$_POST['ippdata']:$ippdata;
	if($ippdata!==""){
		// ippdata is usually a comma seperated list of integers defining 
		// the custom instance per page
		$data['ipparr_override']=explode(",", $ippdata);
	}
	if(isValidMD5($curquery)==true&&isset($_SESSION['cq'][$curquery])){
		$data['ipparr_override']=isset($_SESSION['ipparr_override'][$curquery])&&
								$_SESSION['ipparr_override'][$curquery]!==""?
								$_SESSION['ipparr_override'][$curquery]:
								$data['ipparr_override'];
		$curquery=$_SESSION['cq'][$curquery]!==""?$_SESSION['cq']["$curquery"]:"SELECT * FROM admin WHERE id=0";
	}else if(isValidMD5($curquery)==true&&!isset($_SESSION['cq'][$curquery])){
		$curquery="SELECT * FROM admin WHERE id=0";
	}
	$outs=paginatejavascript($curquery,$typeout,$data);
	if($typeout==""){
		echo $outs['pageout'];
		
	}else if($typeout=="bootpag"){
		$resultcount=$outs['num_pages'];
		$msg="";
	 	echo json_encode(array("success"=>"true","msg"=>"$msg","resultcount"=>"$resultcount"));
	}
}elseif ($displaytype=="paginationpagesout") {
	# code...
	// echo $displaytype;
	$typeout=isset($_GET['loadtype'])?$_GET['loadtype']:"";
	$typeout=isset($_POST['loadtype'])?$_POST['loadtype']:"";
	$curquery="";
	$curquery=isset($_GET['curquery'])?$_GET['curquery']:$curquery;
	$curquery=isset($_POST['curquery'])?$_POST['curquery']:$curquery;
	$curquery=str_replace("_asterisk_","*",$curquery);
	$testq=strpos($curquery,"%'");
	if($testq===0||$testq===true||$testq>0){
		// $curquery=str_replace("%'","%",$curquery);
	}
	$chash="";
	$ippdata="";
	$data=array();
	$data['ipparr_override']="";
	$ippdata=isset($_GET['ippdata'])?$_GET['ippdata']:$ippdata;
	$ippdata=isset($_POST['ippdata'])?$_POST['ippdata']:$ippdata;
	if($ippdata!==""){
		// ippdata is usually a comma seperated list of integers defining 
		// the custom instances per page
		$data['ipparr_override']=explode(",", $ippdata);
	}
	
	// check if the 'curqueries' $_SESSION index is being used
	if(isValidMD5($curquery)==true&&isset($_SESSION['cq'][$curquery])){
		$chash=$curquery;
		$data['ipparr_override']=
		isset($_SESSION['generalpagesdata'][$curquery]['ipparr_override'])&&
		$_SESSION['generalpagesdata'][$curquery]['ipparr_override']!==""?
		$_SESSION['generalpagesdata'][$curquery]['ipparr_override']:"";
		$curquery=$_SESSION['cq'][$curquery]!==""?$_SESSION['cq']["$curquery"]:"SELECT * FROM admin WHERE id=0";
		// var_dump($curquery);
	}else if(isValidMD5($curquery)==true&&!isset($_SESSION['cq'][$curquery])){
		$curquery="SELECT * FROM admin WHERE id=0";
	}

 	// echo json_encode(array("success"=>"true","msg"=>"$msg","scripts"=>"$scripts","resultcount"=>"$resultcount"));
	// echo $curquery;

	$outs=paginatejavascript($curquery,$typeout,$data);
	$limit=$outs['limit'];
	$type=$_GET['outputtype'];
	$curstamp=isset($_GET['curstamp'])?$_GET['curstamp']:"";
	$query2=$curquery." ".str_replace("all","",$outs['limit']);
	// echo $query2;
	// echo $type;
	$run=mysql_query($query2)or die(mysql_error());
	$numrows=mysql_num_rows($run);
	$otype=$type;
	$ptype=initPaginationTypes($type);
	$type=$ptype['type'];
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
		}elseif ($type=="blogtype") {
			# code...
			$outs=getAllBlogTypes("admin",$limit);
			echo $outs['adminoutputtwo'];
		}elseif ($type=="blogcategory") {
			# code...
			$row=mysql_fetch_assoc($run);
			$blogtypeid=$row['blogtypeid'];
			$data['blogtypeid']=$blogtypeid;
			$outs=getAllBlogCategories("admin",$limit,"",$data);
			echo $outs['adminoutputtwo'];
		}elseif ($type=="blogpostcategory") {
			# code...
			$row=mysql_fetch_assoc($run);
			$blogcatid=$row['blogcatid'];
			$data['blogcatid']=$blogcatid;
			$outs=getAllBlogEntries("admin",$limit,'category',$data);
			echo $outs['adminoutputtwo'];
		}elseif ($type=="blogpostblogtype") {
			# code...
			$row=mysql_fetch_assoc($run);
			$blogtypeid=$row['blogtypeid'];
			$data['blogtypeid']=$blogtypeid;
			$outs=getAllBlogEntries("admin",$limit,'blogtype',$data);
			echo $outs['adminoutputtwo'];
		}elseif ($type=="blogpostscheduledposts") {
			# code...
			$row=mysql_fetch_assoc($run);
			$blogtypeid=$row['blogtypeid'];
			$data['blogtypeid']=$blogtypeid;
			$outs=getAllBlogEntries("admin",$limit,'scheduledposts',$data);
			echo $outs['adminoutputfour'];
		}elseif ($type=="blogpostsearch") {
			# code...
			// echo $type;
			$inquery=array();
			$inquery[0]=$extraval;
			$inquery[1]=$curquery;
			// echo $curquery;
			$data['queryextra']=" $curquery";
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
		}elseif ($type=="events"||$type=="eventsgeneral"||$type=="eventsfc"||$type=="eventspfn"||$type=="eventscsi"||$type=="eventsfs") {
			# code...
			$row=mysql_fetch_assoc($run);
			$categorytype=$row['type'];
			$outs=getAllEventEntries("admin",$limit,$categorytype);
			echo $outs['adminoutputtwo'];
		}elseif ($type=="faq") {
			# code...
			$outs=getAllFAQ("admin","",$limit);
			echo $outs['adminoutputtwo'];
		}elseif ($type=="gallery") {
			# code...
			$row=mysql_fetch_assoc($run);
			$outs=getGalleryStream("admin",$limit);
			echo $outs['adminoutputtwo'];
		}elseif ($type=="gallerystream") {
			# code...
			$data=explode('_gallerystream',$otype);
			$type=$data[0];
			$row=mysql_fetch_assoc($run);
			$outs=getGalleryStream("admin",$limit,$type);
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
		}elseif ($type=="recommendation"||$type=="clientelle"||$type=="testimonial") {
			# code...
			$row=mysql_fetch_assoc($run);
			$outs=getAllClientRecommendations("admin","$type",$limit);
			echo $outs['adminoutputtwo'];
		}elseif ($type=="generalinfo") {
			# code...
			$data=explode('-',$otype);
			$viewer=$data[1];
			$etype=isset($data[2])?$data[2]:"";
			$entrydataset=array();
			if($viewer=="viewer" &&($typeout=="jsonloadalt"||$typeout=="bootpag")){
				// $data[4]="paginate";
				// echo $viewer." the viewertype $typeout - the typeout ";
				$entrydataset[4]="paginate";
			}
			$outtype="$viewer-$etype";
			// $page=$data[2];
				// echo "in here ";
			$row=mysql_fetch_assoc($run);
			$contentgroupdatapaginate=array();
			if(isset($_SESSION['gd_contentgroup'][''.$outtype.''])){
				$contentgroupdatapaginate=$_SESSION['gd_contentgroup'][''.$outtype.''];
				// var_dump($_SESSION['gd_contentgroup'][''.$outtype.'']);
				// echo "in here ";
			}
			$outs=getAllGeneralInfo("$viewer",$entrydataset,$limit,"$etype");
			if($etype=="photogallerytwo"){
					
			}else if($viewer=="viewer"){
				if($typeout==""){
					echo $outs['vieweroutputmini'];
				}else if ($typeout=="jsonloadalt"||$typeout=="bootpag"){
					$msg=$outs['vieweroutputmini'];
					// echo $outs['vieweroutputmini'];
					$resultcount=$outs['numrows'];
	 				echo json_encode(array("success"=>"true","msg"=>"$msg","resultcount"=>"$resultcount"));
				}
			}else{
				// echo "im here";
				echo $outs['adminoutputtwo'];

			}
		}else if($type=="generalpages"){
			// this is a generic handler for pagination related information
			// regardless of the nature
			$generalpagesdata=array();
			if(isset($_SESSION['generalpagesdata'][''.$chash.''])
				&&$_SESSION['generalpagesdata'][''.$chash.'']){
				$generalpagesdata=$_SESSION['generalpagesdata'][''.$chash.''];
				// var_dump($generalpagesdata);
				// echo "in here ";
				// init the default viewtype for the pagination file handler
				$viewtype="paginate";
				if(isset($generalpagesdata['pagpath'])&&
					$generalpagesdata['pagpath']!==""){
					if(file_exists($generalpagesdata['pagpath'])){
						// view the pagpathe value
						// echo $generalpagesdata['pagpath'];

						$data=$generalpagesdata['data'];
						
						// tell the receiving function that the current data array
						// is obtained from the session variable and no new one needs to
						// be created
						$data['sessionuse']="true";

						$outputtype=$generalpagesdata['outputtype'];
						
						// break off the generalpagesportion
						$outputtype=str_replace("generalpages|", "", $outputtype);

						// bring in the pagination handler file
						include($generalpagesdata['pagpath']);
						
					}else{
						echo "Invalid pagination path detected. No file found";
					}	
				}else{
					echo "No Valid Process route found";
				}
			}else{
				echo "No valid session detected, hash expired.";
			}
		}elseif ($type=="homebanner") {
			# code...
			$outs=getAllHomeBanners("admin","",$limit);
			echo $outs['adminoutputtwo'];
		}elseif ($type=="contactbranches") {
			# code...
			$outs=getAllBranches("admin","",$limit);
			echo $outs['adminoutputtwo'];
		}elseif ($type=="managementteam") {
			# code...
			$outs=getAllManagementTeam("admin","",$limit);
			echo $outs['adminoutputtwo'];
		}elseif ($type=="recruitsearch") {
			# code...
			$data=explode('|',$otype);
			$edatain[]=$data[1]; // subtype
			$edatain[]=$data[2]; // searchval
			$edatain[]=$data[3]; // viewer
			$row=mysql_fetch_assoc($run);
			$outs=getAllUsers($edatain,$limit);
			echo $outs['adminoutputtwo'];
		}elseif ($type=="users") {
			# code...
			$viewer="admin";
			$data=explode('|',$otype);
			$usertype=$data[0]; // USER
			$outputtype=$data[2]; // realoutputdata
			$row=mysql_fetch_assoc($run);
			if($outputtype==""){
				$outputtype="adminoutputtwo";
			}
			if($usertype=="usersearch"){
				$viewer=array();
				$viewer[0]=$data[1]; // subtype
				$searchval=$data[2]; // searchval
				$type=$data[3]; // viewer
				$nt=strpos($type, "][");
				if ($nt===0||$nt===true||$nt>0) {
					# code...
					$rdata=explode("][", $type);
				}else{
					$rdata=$type;
				}
				$viewer[1]=$rdata[0]; // subtype
				$viewer[2]=$rdata[1]; // searchval
				$outputtype=$data[3]; // outt
			}else{
				$type=$data[1]; // realoutputdata
				$outt=$data[2]; // outt
				$nt=strpos($type, "][");
				if ($nt===0||$nt===true||$nt>0) {
					# code...
					$rdata=explode("][", $type);
				}else{
					$rdata=$type;
				}
			}

			$outs=getAllUsers($viewer,$limit,$usertype,$outputtype,$rdata);
			echo $outs[''.$outputtype.''];
		}elseif ($type=="incidents") {
			# code...
			$data=explode('|',$otype);
			$viewer="admin";
			$outputtype=$data[0]; // outputtype
			if($outputtype=="incidentsearch"){
				$viewer=array();
				$viewer[0]=$data[1]; // viewer
				$realoutputdata=$data[2]; // realoutputdata
				$nt=strpos($realoutputdata, "][");
				if ($nt===0||$nt===true||$nt>0) {
					# code...
					$rdata=explode("][", $realoutputdata);
				}
				$viewer[1]=$rdata[0]; // subtype
				$viewer[2]=$rdata[1]; // searchval
				$outt=$data[3]; // outt
				$eotype=$data[4]; // eotype
			}else{
				$realoutputdata=$data[1]; // realoutputdata
				$outt=$data[2]; // outt
				$eotype=$data[3]; // eotype
				$nt=strpos($realoutputdata, "][");
				if ($nt===0||$nt===true||$nt>0) {
					# code...
					$rdata=explode("][", $realoutputdata);
				}else{
					$rdata=$realoutputdata;
				}
				
			}
			$row=mysql_fetch_assoc($run);
			$outs=getAllIncidents($viewer,$limit,$rdata,$outt,$eotype);
			if($outt==""){
				$outt="adminoutputtwo";
			}
			echo $outs[''.$outt.''];
		}elseif ($type=="cases") {
			# code...
			$data=explode('|',$otype);
			$viewer="admin";
			$outputtype=$data[0]; // outputtype
			if($outputtype=="casesearch"){
				$viewer=array();
				$viewer[0]=$data[1]; // viewer
				$realoutputdata=$data[2]; // realoutputdata
				$nt=strpos($realoutputdata, "][");
				if ($nt===0||$nt===true||$nt>0) {
					# code...
					$rdata=explode("][", $realoutputdata);
				}
				$viewer[1]=$rdata[0]; // subtype
				$viewer[2]=$rdata[1]; // searchval
				$outt=$data[3]; // outt
				$eotype=$data[4]; // eotype
			}else{
				$realoutputdata=$data[1]; // realoutputdata
				$outt=$data[2]; // outt
				$eotype=$data[3]; // eotype
				$nt=strpos($realoutputdata, "][");
				if ($nt===0||$nt===true||$nt>0) {
					# code...
					$rdata=explode("][", $realoutputdata);
				}else{
					$rdata=$realoutputdata;
				}
				
			}
			$row=mysql_fetch_assoc($run);
			$outs=getAllCases($viewer,$limit,$rdata,$outt,$eotype);
			if($outt==""){
				$outt="adminoutputtwo";
			}
			echo $outs[''.$outt.''];
		}elseif ($type=="casetransfer") {
			# code...
			$data=explode('|',$otype);
			$viewer="admin";
			$outputtype=$data[0]; // outputtype
			if($outputtype=="casetransfersearch"){
				$viewer=array();
				$viewer[0]=$data[1]; // viewer
				$realoutputdata=$data[2]; // realoutputdata
				$nt=strpos($realoutputdata, "][");
				if ($nt===0||$nt===true||$nt>0) {
					# code...
					$rdata=explode("][", $realoutputdata);
				}
				$viewer[1]=$rdata[0]; // subtype
				$viewer[2]=$rdata[1]; // searchval
				$outt=$data[3]; // outt
				$eotype=$data[4]; // eotype
			}else{
				$realoutputdata=$data[1]; // realoutputdata
				$outt=$data[2]; // outt
				$eotype=$data[3]; // eotype
				$nt=strpos($realoutputdata, "][");
				if ($nt===0||$nt===true||$nt>0) {
					# code...
					$rdata=explode("][", $realoutputdata);
				}else{
					$rdata=$realoutputdata;
				}
				
			}
			$row=mysql_fetch_assoc($run);
			if($outt==""){
				$outt="adminoutputtwo";
			}
			$outs=getAllCaseTransfers($viewer,$limit,$rdata,$outt,$eotype);
			echo $outs[''.$outt.''];
		}elseif ($type=="casereports") {
			# code...
			$data=explode('|',$otype);
			$viewer="admin";
			$outputtype=$data[0]; // outputtype
			if($outputtype=="casereportssearch"){
				$viewer=array();
				$viewer[0]=$data[1]; // viewer
				$realoutputdata=$data[2]; // realoutputdata
				$nt=strpos($realoutputdata, "][");
				if ($nt===0||$nt===true||$nt>0) {
					# code...
					$rdata=explode("][", $realoutputdata);
				}
				$viewer[1]=$rdata[0]; // subtype
				$viewer[2]=$rdata[1]; // searchval
				$outt=$data[3]; // outt
				$eotype=$data[4]; // eotype
			}else{
				$realoutputdata=$data[1]; // realoutputdata
				$outt=$data[2]; // outt
				$eotype=$data[3]; // eotype
				$nt=strpos($realoutputdata, "][");
				if ($nt===0||$nt===true||$nt>0) {
					# code...
					$rdata=explode("][", $realoutputdata);
				}else{
					$rdata=$realoutputdata;
				}
				
			}
			$row=mysql_fetch_assoc($run);
			if($outt==""){
				$outt="adminoutputtwo";
			}
			$outs=getAllCaseReports($viewer,$limit,$rdata,$outt,$eotype);
			echo $outs[''.$outt.''];
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
		if($typeout==""){
			echo'No database entries ';

		}else{
			echo json_encode(array("success"=>"true","msg"=>"No database Entries","resultcount"=>"0"));
		}
	}

}elseif ($displaytype=="refreshpagination") {
	# code...
	// for js call single refresh content
	// to support traversing upward and downward a result set
	// expects paginationtype, id, flowtype[next|prev],limit
	// flowtype refers to the result set that is required for display
	// has value of 'lastentryset' and 'nextentryset'
	// next will pull new entries after the 'id' specified
	// prev will pull old entries before the 'id' specified
	// limit output specifies the result set to pull
	// the result sets will be given and echoed as markup(where applicable) or simply
	// json object and a new 'id' will be returned to be stored for making the 
	// next request based on the current specified flowtype
	$paginationtype="";
	$flowtype="";
	$id=0;
	$entrytype="";
	$limit="";
	if(isset($_GET['paginationtype'])){
		$paginationtype=isset($_GET['paginationtype'])&&(!isset($pagination)||$pagination=="")?$_GET['paginationtype']:"";
		$id=isset($_GET['id'])&&(!isset($id)||$id==0)?$_GET['id']:0;
		$entrytype=isset($_GET['entrytype'])&&(!isset($entrytype)||$entrytype==0)?$_GET['entrytype']:0;
		$flowtype=isset($_GET['flowtype'])&&(!isset($flowtype)||$flowtype=="")?$_GET['flowtype']:"";
		$limit=isset($_GET['limit'])&&(!isset($limit)||$limit=="")?$_GET['limit']:"";

	}
	if(isset($_POST['paginationtype'])){
		$paginationtype=isset($_POST['paginationtype'])&&(!isset($pagination)||$pagination=="")?$_POST['paginationtype']:"";
		$id=isset($_POST['id'])&&(!isset($id)||$id==0)?$_POST['id']:0;
		$entrytype=isset($_POST['entrytype'])&&(!isset($entrytype)||$entrytype==0)?$_POST['entrytype']:0;
		$flowtype=isset($_POST['flowtype'])&&(!isset($flowtype)||$flowtype=="")?$_POST['flowtype']:"";
		$limit=isset($_POST['limit'])&&(!isset($limit)||$limit=="")?$_POST['limit']:"";
	}
	// sort out limit max set
	$rmlim=str_replace("LIMIT ", "", $limit);
	$rmdata=explode(",", $rmlim);
	$curmax=15;
	$nextmax="";
	$curmin=0;
	if(count($rmdata)>1){
		$llim=$rmdata[0];
		$ulim=$rmdata[1];
		if($ulim<$llim){
			$sd=$ulim;
			$ulim=$llim;
			$llim=$sd;
		}
		$curmax=$ulim;
		$limit="LIMIT $llim , $ulim";
	}
	if($paginationtype==""){

	}else if($paginationtype=="gallerytest"){
		// only for test purposes
		
		// filepath variable relative from directory the function is being run 
		$fpath='../images/fvtimages/masonry/';
		$outextract=sortThroughDir("$fpath",'jpg|.jpg|jpeg|.jpeg|png|.png|.svg|svg|gif|.gif',"plainsort");
		$nextmax=$curmax+15;

	    for($i=0;$i<$outextract['totalmatches'];$i++){
	    	$len=strlen($outextract['matchedfilespath'][$i]);
	        $imgs[]=$host_addr.substr($outextract['matchedfilespath'][$i], 3,$len); // image path (img data)
	    }
		$captionsone[0]="<h2>Topsy turvy</h2><strong title='title'> stop the whining and dont</strong>";
		$captionsone[1]="<h2>Serendipity</h2><strong title='title'> warpath mind, bring the pain</strong>";
		$captionsone[2]="<h2>Salient</h2><strong title='title'>too bright to touch baring down on others</strong>";
		$captionsone[3]="<h2>Sentient</h2><strong title='title'>a dip in the blue seeing its depth</strong>";
		$captionsone[4]="<h2>We lift your name up</h2><strong title='title'>Shout unto God with a voice of triumph</strong>";
		$captionsone[5]="<h2>Rejoicing in new life from God</h2><strong title='title'>Only in Jesus is my hope</strong>";
		$captions[0]="<h4 class='galimgdetailshigh'>Topsy turvy</h4>: stop the whining and dont";
		$captions[1]="<h4 class='galimgdetailshigh'>Serendipity</h4>: warpath mind, bring the pain";
		$captions[2]="<h4 class='galimgdetailshigh'>Salient</h4>:  too bright to touch baring down on others";
		$captions[3]="<h4 class='galimgdetailshigh'>Sentient</h4>: a dip in the blue seeing its depth";
		$captions[4]="<h4 class='galimgdetailshigh'>We lift your name up</h4>: Shout unto God with a voice of triumph";
		$captions[5]="<h4 class='galimgdetailshigh'>Rejoicing in new life from God</h4>: Only in Jesus is my hope";
		$styletype[]='right';
		$styletype[]='left';
		$styletype[]='top';
		$styletype[]='bottom';
		$output="";
		$outputtwo="";
		$scriptoutput="";
		$rlayout=rand(1,2);
		if($rlayout==1){	
			$dimdefault[]="3,5";
			$dimdefault[]="2,3";
			$dimdefault[]="1,2";
			$dimdefault[]="1,2";
			$dimdefault[]="2,3";
			$dimdefault[]="3,3";
			$dimdefault[]="1,2";
			$dimdefault[]="1,2";
			$dimdefault[]="1,2";
			$dimdefault[]="1,2";
			$dimdefault[]="1,2";
			$dimdefault[]="3,3";
			$dimdefault[]="2,3";
			$dimdefault[]="2,4";
			$dimdefault[]="3,4";
		}else if($rlayout==2){
			$dimdefault[]="3,5";
			$dimdefault[]="2,3";
			$dimdefault[]="1,2";
			$dimdefault[]="1,2";
			$dimdefault[]="1,2";
			$dimdefault[]="1,2";
			$dimdefault[]="2,4";
			$dimdefault[]="1,2";
			$dimdefault[]="2,2";
			$dimdefault[]="1,2";
			$dimdefault[]="3,2";
			$dimdefault[]="2,4";
			$dimdefault[]="2,2";
			$dimdefault[]="1,2";
			$dimdefault[]="5,5";
			
		}
		for ($i=1; $i <= 15; $i++) { 
			# code...
			$curbgcloud=convertNumber(rand(1,10));
			$curdim=$dimdefault[$i-1];
			$curdim=explode(",",$curdim);
			$curw=$curdim[0];
			$curh=$curdim[1];
			// perform pre-isotope five grid calculations
			$w=$extraval;
			$columnNum=1;
			$columnWidth=0;
			$columnHeight=0;
			if($w>0){
				$columnNum=5;
			}else if($w>900){
				$columnNum=4;
			}else if($w>600){
				$columnNum=3;
			}else if($w>300){
				$columnNum=2;
			}
			$columnWidth=floor($w/$columnNum);
			$width=$columnWidth*$curw;
			$height=$columnWidth*$curh*0.5;

			// control the isotope-force class for the
			// current entry, based on the affected
			// combinations of width and height
			$classout="";
			if(($curw==2&&$curh==2)||
			   ($curw==3&&$curh==3)||
			   ($width>$height)||
			   ($curw>$curh&&$curw%$curh>=1)||
			   ($curw==5&&$curh==5)){
				$classout=" isotope-force";
			}
			$curimg=rand(0,count($imgs)-1);
			$curimg=$imgs[$curimg];
			$curcaptionone=rand(0,count($captionsone)-1);
			$curcaptionone=$captionsone[$curcaptionone];
			$curcaption=rand(0,count($captions)-1);
			$curcaption=$captions[$curcaption];	
			$curstyle=$styletype[rand(0,count($styletype)-1)];
			$fstyleout=' style="width:'.$width.'px;height:'.$height.'px;"';
			$output.='
				<figure class="item item-w'.$curw.' item-h'.$curh.' hover-style grid'.$classout.'" '.$fstyleout.'>
				    <div class="imgholder">        <img src="'.$curimg.'" alt="img">
				            <div class="prod-layer bg-color-cloud-'.$curbgcloud.'"></div>
				    </div>	
				    <a href="'.$curimg.'" data-lightbox="testgallerymain" data-src="'.$curimg.'" data-title="'.str_replace("'", "'", $curcaption).'" class="caption-point '.$curstyle.' caption-text">	
				    	'.str_replace("'", "'", $curcaptionone).'
				    </a>
				</figure>
			';
			$outputtwo.='<figure class="item item-w'.$curw.' item-h'.$curh.' hover-style grid'.$classout.'" '.$fstyleout.'>    <div class="imgholder">        <img src="'.$curimg.'" alt="img">        <div class="prod-layer bg-color-cloud-'.$curbgcloud.'"></div>    </div>	<a href="'.$curimg.'" data-lightbox="testgallerymain" data-src="'.$curimg.'" data-title="'.str_replace("'", "\'", $curcaption).'" class="caption-point '.$curstyle.' caption-text">		'.str_replace("'", "\'", $curcaptionone).'    </a></figure>';
		}
		// perform an isotope arrangement strategy
		if($entrytype=="insertbefore"){
			$isotopeentry='.prepend( newItems).isotope( \'reloadItems\' ).isotope({ sortBy: \'original-order\' });';

		}else if($entrytype=="insertafter"){
			$isotopeentry='.isotope( \'insert\', newItems )';
		}
		// load refresh script with content
		$output.='
				<script> 
					$(document).ready(function(){
						/*$(\'.cp-gallery\').isotope({
							resizable:false,
							itemSelector:\'.item\',
							masonry:{columnWidth:'.$columnWidth.',gutterWidth:0}
						});*/
						$(\'.cp-gallery\').isotope(\'reLayout\')
					})
				</script>
		';
		$outputtwo='
				<script> 
					$(document).ready(function(){
						var newItems=$(\''.$outputtwo.'\');
						/*$(\'.cp-gallery\').append( newItems ).delay(1000).isotope( \'addItems\', newItems ).isotope({
							itemSelector:\'.item\',
							masonry:{columnWidth:colWidth(),gutterWidth:0}
						})*/
						$(\'.cp-gallery\')'.$isotopeentry.'
					})
				</script>
		';
		$msg="OK";
		// echo $output;
	 	echo json_encode(array("success"=>"true","msg"=>"$msg","catdata"=>"$output","currentmax"=>$curmax,"nextmax"=>$nextmax,"resultpages"=>150,"resultcount"=>1000));

	}else if($paginationtype=="portfoliogallerystream"){
		$output="";
		$msg="OK";
		$outsdata=getGalleryStream("viewer","$limit","portfoliogallerystream");
		if($outsdata['numrows']>0){
			$nextmax=$curmax+15;
			$rlayout=rand(1,2);
			if($rlayout==1){	
				$dimdefault[]="3,5";
				$dimdefault[]="2,3";
				$dimdefault[]="1,2";
				$dimdefault[]="1,2";
				$dimdefault[]="2,3";
				$dimdefault[]="3,3";
				$dimdefault[]="1,2";
				$dimdefault[]="1,2";
				$dimdefault[]="1,2";
				$dimdefault[]="1,2";
				$dimdefault[]="1,2";
				$dimdefault[]="3,3";
				$dimdefault[]="2,3";
				$dimdefault[]="2,4";
				$dimdefault[]="3,4";
			}else if($rlayout==2){
				$dimdefault[]="3,5";
				$dimdefault[]="2,3";
				$dimdefault[]="1,2";
				$dimdefault[]="1,2";
				$dimdefault[]="1,2";
				$dimdefault[]="1,2";
				$dimdefault[]="2,4";
				$dimdefault[]="1,2";
				$dimdefault[]="2,2";
				$dimdefault[]="1,2";
				$dimdefault[]="3,2";
				$dimdefault[]="2,4";
				$dimdefault[]="2,2";
				$dimdefault[]="1,2";
				$dimdefault[]="5,5";
			}
			$displaycount=$outsdata['datasetcount'];
			
			$styletype[]='right';
			$styletype[]='left';
			$styletype[]='top';
			$styletype[]='bottom';
			for($i=0;$i<$displaycount;$i++){
				# code...
				$curbgcloud=convertNumber(rand(1,10));
				$curdim=$dimdefault[$i];
				$curdim=explode(",",$curdim);
				$curw=$curdim[0];
				$curh=$curdim[1];
				// perform pre-isotope five grid calculations
				$w=$extraval;
				$columnNum=1;
				$columnWidth=0;
				$columnHeight=0;
				if($w>0){
					$columnNum=5;
				}else if($w>900){
					$columnNum=4;
				}else if($w>600){
					$columnNum=3;
				}else if($w>300){
					$columnNum=2;
				}
				$columnWidth=floor($w/$columnNum);
				$width=$columnWidth*$curw;
				$height=$columnWidth*$curh*0.5;
				// control the isotope-force class for the
				// current entry, based on the affected
				// combinations of width and height
				$classout="";
				if(($curw==2&&$curh==2)||
				   // ($curh%$curw>1)||
				   ($curw>$curh&&$curw%$curh>=1)||
				   ($curw==3&&$curh==5)||
				   ($curw==1&&$curh==2)||
				   ($curw==5&&$curh==5)){
					$classout=" isotope-force";
				}
				
				$curimg=$outsdata['resultdataset'][$i]['medsize'];
				$mainimg=$outsdata['resultdataset'][$i]['location'];

				$curcaptionone="<h2>".$outsdata['resultdataset'][$i]['title']."</h2><strong class=\"title\"> ".$outsdata['resultdataset'][$i]['details']."</strong>";	
				$curcaption="<h4 class='galimgdetailshigh'>".$outsdata['resultdataset'][$i]['title']."</h4>: ".$outsdata['resultdataset'][$i]['details'];	
				$curstyle=$styletype[rand(0,count($styletype)-1)];
				$fstyleout=' style="width:'.$width.'px;height:'.$height.'px;"';

				$output.='<figure class="item item-w'.$curw.' item-h'.$curh.' hover-style grid'.$classout.'" '.$fstyleout.'><div class="imgholder"><img src="'.$curimg.'" alt="img"><div class="prod-layer bg-color-cloud-'.$curbgcloud.'"></div></div><a href="'.$mainimg.'" data-lightbox="portfoliogallery" data-src="'.$mainimg.'" data-title="'.str_replace("'", "\'", $curcaption).'" class="caption-point '.$curstyle.' caption-text">'.str_replace("'", "\'", $curcaptionone).'</a></figure>';
			}
			// perform an isotope arrangement strategy
			if($entrytype=="insertbefore"){
				$isotopeentry='.prepend( newItems).isotope( \'reloadItems\' ).isotope({ sortBy: \'original-order\' });';

			}else if($entrytype=="insertafter"){
				$isotopeentry='.isotope( \'insert\', newItems )';
			}
			$output='
				<script> 
					$(document).ready(function(){
						var newItems=$(\''.$output.'\');
						/*$(\'.cp-gallery\').append( newItems ).delay(1000).isotope( \'addItems\', newItems ).isotope({
							itemSelector:\'.item\',
							masonry:{columnWidth:colWidth(),gutterWidth:0}
						})*/
						$(\'.cp-gallery\')'.$isotopeentry.'
					})
				</script>
			';
		}else{
			$msg="No more Entries available";
		}
	 	echo json_encode(array("success"=>"true","msg"=>"$msg","catdata"=>"$output","currentmax"=>$curmax,"nextmax"=>$nextmax,"resultpages"=>$outsdata['num_pages'],"resultcount"=>$outsdata['num_pages_rows']));
	}
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
}else if($displaytype=="gd_download"){
	$type=isset($_GET['type'])?$_GET['type']:"default";
	$file=$_GET['datapath'];
	$contenttype=isset($_GET['ctype'])?$_GET['ctype']:
	"application/octet-stream";
	// for spread sheets
	// application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
	if($file!==""&&file_exists($file)){
		$ft=getExtensionAdvanced($file);
		$title=isset($_GET['title'])&&$_GET['title']!==""?$_GET['title']:
				"File_".substr(md5(date("Y-m-d H:i:s")), 0,8).".".$ft['ext'];

		header('Content-Type: '.$contenttype.'');
		header('Content-Disposition: attachment;filename="'.$title.'"');
		header('Cache-Control: max-age=0');
		header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($file));
		readfile("$file");
		exit;
	}

}
?>