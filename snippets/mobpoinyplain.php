<?php
include('connection.php');
/*if(isset()){

}*/
// header("Access-Control-Allow-Origin: *");
session_start();

$displaytype="";
$test="";
$dhash="true";
$extraval="admin";
$todaynow=date("Y-m-d H:i:s");

if(isset($_GET['displaytype'])){
	$displaytype=$_GET['displaytype'];
}

if(isset($_GET['extraval'])){
	$extraval=$_GET['extraval'];
}
if(isset($_POST['extraval'])){
	$extraval=$_POST['extraval'];
}
if (isset($_POST['displaytype'])) {
	$displaytype=$_POST['displaytype'];	
}

if (isset($_GET['test'])) {
	$test=$_GET['test'];	
}

if (isset($_POST['test'])) {
	$test=$_POST['test'];	
}
if (isset($_GET['dhash'])) {
	$dhash=$_GET['dhash'];	
}

if (isset($_POST['dhash'])) {
	$dhash=$_POST['dhash'];	
}
if($dhash!==""){
	$orderfield[0]='actionhash';
	$orderfield[1]='usertype';
	$orderfield[2]='action';
	$ordervalue[0]=$dhash;
	$ordervalue[1]='users';
	$ordervalue[2]='login';
	$order=" ORDER BY id DESC";
	$limit=" LIMIT 0,1";
	$outlogprev=getNotifications('notifications',$orderfield,$ordervalue,$order,$limit);
	if($outlogprev['numrows']>0){
		$userid=$outlogprev['userid'];
		// get log out information
		$orderfield[0]='userid';
		$orderfield[1]='usertype';
		$orderfield[2]='action';
		$ordervalue[0]=$userid;
		$ordervalue[1]='users';
		$ordervalue[2]='logout';
		$order=" ORDER BY id DESC";
		$limit=" LIMIT 0,1";
		$outlogprev2=getNotifications('',$orderfield,$ordervalue,$order,$limit);
		if($outlogprev['id']<$outlogprev2['id']){
			$dhash="false";
			// this means the current user is on a device that hasn't been logged in
			$msg="User has logged out from sent hash";
			// createNotification($userid,"users","login","$msg","","","$hashvar");
			$dhasherrout=json_encode(array(	"success"=>"false",
									"msg"=>"$msg",
									"userid"=>"$userid",
									"status"=>"$status"));
		}else{
			$dhash="true";
		}
	}
}else{
	$dhash="false";
	$msg="No devicehash detected for this request";
	$dhasherrout=json_encode(array(	"success"=>"false",
									"msg"=>"$msg",
									"userid"=>"$userid",
									"status"=>"$status"));
}
if($dhash=="true"||$displaytype=="forceuserreset"){

	if($displaytype==""){
	// echo $displaytype;

	}elseif($displaytype=="appuserlogin"){
		$logtype=$displaytype;
		// echo $logtype;
		$dologin="true";
		if($test!==""){
			$username=mysql_real_escape_string($_GET['username']);
			$password=mysql_real_escape_string($_GET['password']);
			$logtype=mysql_real_escape_string($_GET['logtype']);
			
		}else if ($test=="") {
			# code...
			$username=mysql_real_escape_string($_POST['username']);
			$password=mysql_real_escape_string($_POST['password']);
			$logtype=mysql_real_escape_string($_POST['logtype']);

		}
		$iniquery="SELECT * FROM users WHERE email='$username' AND pword='$password' AND usertype='appuser'";
		$inirun=mysql_query($iniquery)or die(mysql_error()." ".__LINE__);
		$numrows=mysql_num_rows($inirun);
		if($numrows>0){
			$row=mysql_fetch_assoc($inirun);
			$userid=$row['id'];
			$orderfield[0]='actionhash';
			$orderfield[1]='usertype';
			$orderfield[2]='action';
			$ordervalue[0]=$devicehash;
			$ordervalue[1]='users';
			$ordervalue[2]='login';
			$order=" ORDER BY id DESC";
			$limit=" LIMIT 0,1";
			$outlogprev=getNotifications('notifications',$orderfield,$ordervalue,$order,$limit);
			if($outlogprev['numrows']>0){
				// get log out information
				$orderfield[0]='userid';
				$orderfield[1]='usertype';
				$orderfield[2]='action';
				$ordervalue[0]=$userid;
				$ordervalue[1]='users';
				$ordervalue[2]='logout';
				$order=" ORDER BY id DESC";
				$limit=" LIMIT 0,1";
				$outlogprev2=getNotifications('',$orderfield,$ordervalue,$order,$limit);
				if($outlogprev['id']<$outlogprev2['id']){
					$dologin="true";
				}else{
					$dologin="false";
					// this means the current user is on a device that hasn't been logged in
					$msg="User is still logged in on a device";
					// createNotification($userid,"users","login","$msg","","","$hashvar");
					echo json_encode(array(	"success"=>"false",
											"msg"=>"$msg",
											"userid"=>"$userid",
											"status"=>"$status",
											"firstname"=>"",
											"middlename"=>"",
											"lastname"=>""));
					
				}
			}else{
				$dologin="true";
			}
			if($dologin=="true"){
				$userdata=getSingleUser($userid);
				$firstname=$row['firstname'];
				$middlename=$row['middlename'];
				$lastname=$row['lastname'];
				$status=$row['status'];
				$msg="Logged in Successfully";
				// notificationtable entry
				// $notificationdetails="Logged into napstand";
				if($status=="active"){
					// get a login hash for this user
					$hashvar=$userid."_".date("Y-m-d H:i:s");
					$hashvar=md5($hashvar);
					// get previous login information
					$orderfield[0]='userid';
					$orderfield[1]='usertype';
					$orderfield[2]='action';
					$ordervalue[0]=$userid;
					$ordervalue[1]='users';
					$ordervalue[2]='login';
					$order=" ORDER BY id DESC";
					$limit=" LIMIT 0,1";
					$outlogprev=getNotifications('notifications',$orderfield,$ordervalue,$order,$limit);
					$lastloginid=0;
					$lastloghash="";
					// obtain last login id
					if($outlogprev['numrows']>0){
						// $lastloginid=$outlogprev['numrows']>1?$outlogprev[0]['id']:$outlogprev['id'];
						$lastloginid=$outlogprev['id'];
						// get last loginhash
						$lastloghash=$outlogprev['actionhash'];
					}
					// get log out information
					$orderfield[0]='userid';
					$orderfield[1]='usertype';
					$orderfield[2]='action';
					$ordervalue[0]=$userid;
					$ordervalue[1]='users';
					$ordervalue[2]='logout';
					$order=" ORDER BY id DESC";
					$limit=" LIMIT 0,1";
					$outlogprev2=getNotifications('',$orderfield,$ordervalue,$order,$limit);
					$lastlogoutid=0;
					// obtain last logout date
					if($outlogprev2['numrows']>0){
						// $lastlogoutid=$outlogprev2['numrows']>1?$outlogprev2[0]['id']:$outlogprev2['id'];				
						$lastlogoutid=$outlogprev2['id'];				
					}
					// echo "lastlogin:$lastloginid  lastlogout:$lastlogoutid <br>";
					if(($lastlogoutid==0&&$lastloginid==0)||($lastlogoutid>$lastloginid)||($lastloginid>$lastlogoutid&&$lastloghash=="")){
						// this means the current user is on a device that hasn't been logged in
						// or they are first timers
						createNotification($userid,"users","login","$msg","","","$hashvar");
			 			echo json_encode(array(	"success"=>"true",
			 									"msg"=>"$msg",
			 									"userid"=>"$userid",
			 									"firstname"=>"$firstname",
			 									"lastname"=>"$lastname",
			 									"middlename"=>"$middlename",
			 									"devicehash"=>"$hashvar",
			 									"status"=>"$status"));
					}else{
						$devicehash="";
						if(isset($_GET['devicehash'])){
							$devicehash=$_GET['devicehash'];
						}
						if(isset($_POST['devicehash'])){
							$devicehash=$_POST['devicehash'];
						}
						// $devicehash="b395107a236cd564a1ce82ef25f642b9";
						if($devicehash!==""&&$devicehash==$lastloghash){
							genericSingleUpdate('notifications',"entrydate","$todaynow",'id',"$lastloginid");
							// createNotification($userid,"users","login",$msg,"","","$hashvar");
				 			echo json_encode(array(	"success"=>"true","msg"=>"$msg","userid"=>"$userid","firstname"=>"$firstname","lastname"=>"$lastname","middlename"=>"$middlename","devicehash"=>"$lastloghash","status"=>"$status"));					
						}else{
							$msg="Login Failure, Account is still active on a device";
							// $curhashvar=$outlogprev['numrows']>1?$outlogprev[0]['actionhash']:$outlogprev['actionhash'];
							// $curhashvar=$outlogprev['actionhash'];
							if($test==""){
								// createNotification($userid,"users","loginfailure",$msg);
				 			}
				 			echo json_encode(array("success"=>"false","msg"=>"$msg","userid"=>"$userid","firstname"=>"$firstname","lastname"=>"$lastname","middlename"=>"$middlename","devicehash"=>"","status"=>"$status"));

						}

					}
					if($test!==""){

					}
				}else{
					$msg="Account has been disabled";

		 			echo json_encode(array("success"=>"false","msg"=>"$msg","userid"=>"","firstname"=>"","middlename"=>"","lastname"=>"","status"=>""));
				}
			}
		}else{
			$msg="Log in failure";
			// createNotification($userid,"users","login",$msg);
	 		echo json_encode(array("success"=>"false","msg"=>"$msg","userid"=>"","firstname"=>"","middlename"=>"","lastname"=>"","status"=>""));
		}
	}else if ($displaytype=="fetchcategories") {
		# code...
		if($test!==""){
			$page=mysql_real_escape_string($_GET['page']);
			$userid=mysql_real_escape_string($_GET['userid']);
			
		}else if ($test=="") {
			# code...
			$page=mysql_real_escape_string($_POST['page']);
			$userid=mysql_real_escape_string($_POST['userid']);
		}
		$prevlimit=($page*$host_app_pull_limit)-$host_app_pull_limit>0?($page*$host_app_pull_limit)-$host_app_pull_limit:0;
		$nextlimit=$page*$host_app_pull_limit;
		$limit="LIMIT $prevlimit,$nextlimit";
		// echo $limit."<br>";
		$outs=getAllContentCategory('viewer',$limit);
		$totalpages=$outs['num_pages'];
		$numrows=$outs['numrows'];
		if($numrows>0){
			$msg="Retrieval Successful";
		 	echo json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages"));
		 	if($test!==""){
				$scriptout='
				<script>
					var sdata='.json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages")).';
				</script>
				';
				echo $scriptout;
			}	
		}else{
			$msg="Retrieval fail";
			echo json_encode(array("success"=>"false","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"totalpages"=>"0"));

		}
	}else if ($displaytype=="fetchcategoryusers") {
		# code...
		if($test!==""){
			$page=mysql_real_escape_string($_GET['page']);
			$userid=mysql_real_escape_string($_GET['userid']);
			$catid=mysql_real_escape_string($_GET['catid']);
			
		}else if ($test=="") {
			# code...
			$page=mysql_real_escape_string($_POST['page']);
			$userid=mysql_real_escape_string($_POST['userid']);
			$catid=mysql_real_escape_string($_POST['catid']);
		}
		$prevlimit=($page*$host_app_pull_limit)-$host_app_pull_limit>0?($page*$host_app_pull_limit)-$host_app_pull_limit:0;
		$nextlimit=$page*$host_app_pull_limit;
		$limit="LIMIT $prevlimit,$nextlimit";
		// echo $limit."<br>";
		$type="catidtwo";
		$typeid=array();
		$typeid[0]=$userid;
		$typeid[1]=$catid;
		$outs=getAllParentContent('viewer',$type,$typeid,$limit);
		$totalpages=$outs['num_pages'];
		$numrows=$outs['numrows'];
		if($numrows>0){
			$msg="Retrieval Successful";
		 	echo json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages"));
		 	if($test!==""){
				$scriptout='
				<script>
					var sdata='.json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages")).';
				</script>
				';
				echo $scriptout;
			}	
		}else{
			$msg="Retrieval fail";
			echo json_encode(array("success"=>"false","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"totalpages"=>"0"));

		}
	}else if ($displaytype=="fetchcategoryuserentries") {
		# code...
		if($test!==""){
			$page=mysql_real_escape_string($_GET['page']);
			$catid=mysql_real_escape_string($_GET['catid']);
			$userid=mysql_real_escape_string($_GET['userid']);
			$webuserid=mysql_real_escape_string($_GET['webuserid']);
			
		}else if ($test=="") {
			# code...
			$page=mysql_real_escape_string($_POST['page']);
			$userid=mysql_real_escape_string($_POST['userid']);
			$catid=mysql_real_escape_string($_POST['catid']);
			$webuserid=mysql_real_escape_string($_POST['webuserid']);
		}
		$prevlimit=($page*$host_app_pull_limit)-$host_app_pull_limit>0?($page*$host_app_pull_limit)-$host_app_pull_limit:0;
		$nextlimit=$page*$host_app_pull_limit;
		$limit="LIMIT $prevlimit,$nextlimit";
		// echo $limit."<br>";
		$type="usertypeout";
		$typeid=array();
		$typeid[0]=$webuserid;
		$typeid[1]=$catid;
		$outs=getAllParentContent('viewer',$type,$typeid,$limit);
		$totalpages=$outs['num_pages'];
		$numrows=$outs['numrows'];
		if($numrows>0){
			$msg="Retrieval Successful";
		 	echo json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages"));
		 	if($test!==""){
				$scriptout='
				<script>
					var sdata='.json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages")).';
				</script>
				';
				echo $scriptout;
			}	
		}else{
			$msg="Retrieval fail";
			echo json_encode(array("success"=>"false","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"totalpages"=>"0"));

		}
	}else if ($displaytype=="pullfromnextentrysetparentcontent"||$displaytype=="pullfromlastentrysetparentcontent") {
		# code...	
		// the extraval parameter is to make this endpoint flexible enough to accomodate any
		// other data set requirements that need to make use of pagination pull.
		if($test!==""){
			$page=mysql_real_escape_string($_GET['page']);
			$catid=mysql_real_escape_string($_GET['catid']);
			$userid=mysql_real_escape_string($_GET['userid']);
			$webuserid=mysql_real_escape_string($_GET['webuserid']);
			$lastid=isset($_GET['lastid'])?mysql_real_escape_string($_GET['lastid']):0;
			$nextid=isset($_GET['nextid'])?mysql_real_escape_string($_GET['nextid']):0;
			$extraval=isset($_GET['extraval'])?mysql_real_escape_string($_GET['extraval']):"";
		}else if ($test=="") {
			# code...
			$page=mysql_real_escape_string($_POST['page']);
			$userid=mysql_real_escape_string($_POST['userid']);
			$catid=mysql_real_escape_string($_POST['catid']);
			$webuserid=mysql_real_escape_string($_POST['webuserid']);
			$lastid=isset($_POST['lastid'])?mysql_real_escape_string($_POST['lastid']):0;
			$nextid=isset($_POST['nextid'])?mysql_real_escape_string($_POST['nextid']):0;
			$extraval=isset($_POST['extraval'])?mysql_real_escape_string($_POST['extraval']):"";
		}
		$prevlimit=($page*$host_app_pull_limit)-$host_app_pull_limit>0?($page*$host_app_pull_limit)-$host_app_pull_limit:0;
		$nextlimit=$page*$host_app_pull_limit;
		$limit="LIMIT $prevlimit,$nextlimit";
		// echo $limit."<br>";
		$type="$displaytype";
		$typeid=array();
		$typeid[0]=$webuserid;
		$typeid[1]=$catid;
		$typeid[2]=$lastid;
		$typeid[3]=$nextid;
		$typeid[4]=$extraval;
		$outs=getAllParentContent('viewer',$type,$typeid,$limit);
		$totalpages=$outs['num_pages'];
		$numrows=$outs['numrows'];
		if($numrows>0){
			$msg="Retrieval Successful";
		 	echo json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages"));
		 	if($test!==""){
				$scriptout='
				<script>
					var sdata='.json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages")).';
				</script>
				';
				echo $scriptout;
			}	
		}else{
			$msg="Retrieval fail";
			echo json_encode(array("success"=>"false","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"totalpages"=>"0"));

		}
	}else if ($displaytype=="fetchmurals") {
		# code...
		if($test!==""){
			$page=mysql_real_escape_string($_GET['page']);
			$userid=mysql_real_escape_string($_GET['userid']);
			
		}else if ($test=="") {
			# code...
			$page=mysql_real_escape_string($_POST['page']);
			$userid=mysql_real_escape_string($_POST['userid']);
		}
		$prevlimit=($page*$host_app_pull_limit)-$host_app_pull_limit>0?($page*$host_app_pull_limit)-$host_app_pull_limit:0;
		$nextlimit=$page*$host_app_pull_limit;
		$limit="LIMIT $prevlimit,$nextlimit";
		// echo $limit."<br>";
		$outs=getMurals('viewer',"$limit");
		// $totalpages=$outs['num_pages'];
		$totalpages=$outs['numrows'];
		$numrows=$outs['numrows'];
		if($numrows>0){
			$msg="Retrieval Successful";
		 	echo json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalmurals"=>"$totalpages"));
		 	if($test!==""){
				$scriptout='
				<script>
					var sdata='.json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalmurals"=>"$totalpages")).';
				</script>
				';
				echo $scriptout;
			}	
		}else{
			$msg="Retrieval fail";
			echo json_encode(array("success"=>"false","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"totalpages"=>"0"));

		}
	}else if ($displaytype=="fetchparentcontentlist") {
		# code...
		if($test!==""){
			$page=mysql_real_escape_string($_GET['page']);
			$userid=mysql_real_escape_string($_GET['userid']);
			$catid=mysql_real_escape_string($_GET['catid']);
			$extraval=isset($_GET['extraval'])?mysql_real_escape_string($_GET['extraval']):"latestparentsetincat";
			
		}else if ($test=="") {
			# code...
			$page=mysql_real_escape_string($_POST['page']);
			$userid=mysql_real_escape_string($_POST['userid']);
			$catid=mysql_real_escape_string($_POST['catid']);
			$extraval=isset($_POST['extraval'])?mysql_real_escape_string($_POST['extraval']):"";
		}
		$prevlimit=($page*$host_app_pull_limit)-$host_app_pull_limit>0?($page*$host_app_pull_limit)-$host_app_pull_limit:0;
		$nextlimit=$page*$host_app_pull_limit;
		$limit="LIMIT $prevlimit,$nextlimit";
		// echo $limit."<br>";
		$type=$displaytype;
		$typeid=array();
		$typeid[0]=$catid;
		$typeid[1]="";
		$typeid[2]="";
		$typeid[3]=$userid;
		$typeid[4]="";
		$typeid[5]="";
		$typeid[6]=$extraval;
		$outs=getAllContentEntries('viewer',$type,$typeid,$limit,"","",$userid);
		$totalpages=$outs['num_pages'];
		$numrows=$outs['numrows'];
		if($numrows>0){
			$msg="Retrieval Successful";
		 	echo json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages"));
		 	if($test!==""){
				$scriptout='
				<script>
					var sdata='.json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages")).';
				</script>
				';
				echo $scriptout;
			}	
		}else{
			$msg="Retrieval fail";
			echo json_encode(array("success"=>"false","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"totalpages"=>"0"));

		}
	}else if ($displaytype=="fetchparentcontentuserslist") {
		# code...
		if($test!==""){
			$page=mysql_real_escape_string($_GET['page']);
			$catid=mysql_real_escape_string($_GET['catid']);
			$userid=mysql_real_escape_string($_GET['userid']);
			$webuserid=mysql_real_escape_string($_GET['webuserid']);
			
		}else if ($test=="") {
			# code...
			$page=mysql_real_escape_string($_POST['page']);
			$userid=mysql_real_escape_string($_POST['userid']);
			$catid=mysql_real_escape_string($_POST['catid']);
			$webuserid=mysql_real_escape_string($_POST['webuserid']);
		}
		$prevlimit=($page*$host_app_pull_limit)-$host_app_pull_limit>0?($page*$host_app_pull_limit)-$host_app_pull_limit:0;
		$nextlimit=$page*$host_app_pull_limit;
		$limit="LIMIT $prevlimit,$nextlimit";
		// echo $limit."<br>";
		$type="catcontentout";
		$typeid=array();
		$typeid[0]=$catid;
		$typeid[1]="";
		$typeid[2]="";
		$typeid[3]=$webuserid;
		$outs=getAllContentEntries('viewer',$type,$typeid,$limit,"","",$userid);
		$totalpages=$outs['num_pages'];
		$numrows=$outs['numrows'];
		if($numrows>0){
			$msg="Retrieval Successful";
		 	echo json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages"));
		 	if($test!==""){
				$scriptout='
				<script>
					var sdata='.json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages")).';
				</script>
				';
				echo $scriptout;
			}	
		}else{
			$msg="Retrieval fail";
			echo json_encode(array("success"=>"false","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"totalpages"=>"0"));

		}
	}else if ($displaytype=="fetchparentcontententries") {
		# code...
		if($test!==""){
			$page=mysql_real_escape_string($_GET['page']);
			$pcid=mysql_real_escape_string($_GET['pcid']);
			$userid=mysql_real_escape_string($_GET['userid']);
			
		}else if ($test=="") {
			# code...
			$page=mysql_real_escape_string($_POST['page']);
			$userid=mysql_real_escape_string($_POST['userid']);
			$pcid=mysql_real_escape_string($_POST['pcid']);
		}
		$prevlimit=($page*$host_app_pull_limit)-$host_app_pull_limit>0?($page*$host_app_pull_limit)-$host_app_pull_limit:0;
		$nextlimit=$page*$host_app_pull_limit;
		$limit="LIMIT $prevlimit,$nextlimit";
		// echo $limit."<br>";
		$type="parentidtwo";
		$typeid=array();
		$typeid[0]=$pcid;
		$typeid[1]="published";
		$typeid[2]="";
		$typeid[3]="";
		$outs=getAllContentEntries('viewer',$type,$typeid,$limit,"","",$userid);
		$totalpages=$outs['num_pages'];
		$numrows=$outs['numrows'];
		if($numrows>0){
			$msg="Retrieval Successful";
		 	echo json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages"));
		 	if($test!==""){
				$scriptout='
				<script>
					var sdata='.json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages")).';
				</script>
				';
				echo $scriptout;
			}	
		}else{
			$msg="Retrieval fail";
			echo json_encode(array("success"=>"false","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"totalpages"=>"0"));

		}
	}else if ($displaytype=="pullfromnextentryset"||$displaytype=="pullfromlastentryset") {
		# code...
		if($test!==""){
			$page=mysql_real_escape_string($_GET['page']);
			$pcid=mysql_real_escape_string($_GET['pcid']);
			$userid=mysql_real_escape_string($_GET['userid']);
			$lastid=isset($_GET['lastid'])?mysql_real_escape_string($_GET['lastid']):0;
			$nextid=isset($_GET['nextid'])?mysql_real_escape_string($_GET['nextid']):0;
			$extraval=isset($_GET['extraval'])?mysql_real_escape_string($_GET['extraval']):"";
		}else if ($test=="") {
			# code...
			$page=mysql_real_escape_string($_POST['page']);
			$userid=mysql_real_escape_string($_POST['userid']);
			$pcid=mysql_real_escape_string($_POST['pcid']);
			$lastid=isset($_POST['lastid'])?mysql_real_escape_string($_POST['lastid']):0;
			$nextid=isset($_POST['nextid'])?mysql_real_escape_string($_POST['nextid']):0;
			$extraval=isset($_POST['extraval'])?mysql_real_escape_string($_POST['extraval']):"";
		}
		$prevlimit=($page*$host_app_pull_limit)-$host_app_pull_limit>0?($page*$host_app_pull_limit)-$host_app_pull_limit:0;
		$nextlimit=$page*$host_app_pull_limit;
		$limit="LIMIT $prevlimit,$nextlimit";
		// echo $limit."<br>";
		$type="parentid";
		$typeid=array();
		$typeid[0]=$pcid;
		$typeid[1]="$displaytype";
		$typeid[2]="";
		$typeid[3]="";
		$typeid[4]=$lastid;
		$typeid[5]=$nextid;
		$typeid[6]=$extraval;
		$outs=getAllContentEntries('viewer',$type,$typeid,$limit,"","",$userid);
		$totalpages=$outs['num_pages'];
		$numrows=$outs['numrows'];
		if($numrows>0){
			$msg="Retrieval Successful";
		 	echo json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages"));
		 	if($test!==""){
				$scriptout='
				<script>
					var sdata='.json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages")).';
				</script>
				';
				echo $scriptout;
			}	
		}else{
			$msg="Retrieval fail";
			echo json_encode(array("success"=>"false","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"totalpages"=>"0"));

		}
	}else if ($displaytype=="fetchcontententry") {
		# code...
		if($test!==""){
			$page=mysql_real_escape_string($_GET['page']);
			$userid=mysql_real_escape_string($_GET['userid']);
			$cid=mysql_real_escape_string($_GET['cid']);
			$transactionview=isset($_GET['transactionview'])?mysql_real_escape_string($_GET['transactionview']):"";			
		}else if ($test=="") {
			# code...
			$page=mysql_real_escape_string($_POST['page']);
			$userid=mysql_real_escape_string($_POST['userid']);
			$cid=mysql_real_escape_string($_POST['cid']);
			$transactionview=isset($_POST['transactionview'])?mysql_real_escape_string($_POST['transactionview']):"";
		}
		$prevlimit=($page*$host_app_pull_limit)-$host_app_pull_limit>0?($page*$host_app_pull_limit)-$host_app_pull_limit:0;
		$nextlimit=$page*$host_app_pull_limit;
		$limit="LIMIT $prevlimit,$nextlimit";
		// echo $limit."<br>";
		$outs=getSingleContentEntry($cid,"",$userid);
		$totalpages=1;
		$numrows=1;
		if($numrows>0){
			$msg="Retrieval Successful";
		 	echo json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdatatwo'],"page"=>"$page","totalpages"=>"$totalpages"));
		 	if($test!==""){
				$scriptout='
				<script>
					var sdata='.json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdatatwo'],"page"=>"$page","totalpages"=>"$totalpages")).';
				</script>
				';
				echo $scriptout;
			}	
		}else{
			$msg="Retrieval fail";
			echo json_encode(array("success"=>"false","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdatatwo'],"totalpages"=>"0"));

		}
	}else if ($displaytype=="fetchclientdata"||$displaytype=="fetchuserdata"||$displaytype=="fetchappuserdata") {
		# code...
		if($test!==""){
			$page=mysql_real_escape_string($_GET['page']);
			$userid=mysql_real_escape_string($_GET['userid']);		
		}else if ($test=="") {
			# code...
			$page=mysql_real_escape_string($_POST['page']);
			$userid=mysql_real_escape_string($_POST['userid']);
		}
		$prevlimit=($page*$host_app_pull_limit)-$host_app_pull_limit>0?($page*$host_app_pull_limit)-$host_app_pull_limit:0;
		$nextlimit=$page*$host_app_pull_limit;
		$limit="LIMIT $prevlimit,$nextlimit";
		// echo $limit."<br>";
		$usertestout="";
		$outs=getSingleUserPlain($userid);
		$numval=0;
		if($displaytype=="fetchappuserdata"&&$outs['usertype']=='appuser'){
			$numval=1;
		}else if($displaytype=="fetchuserdata"&&$outs['usertype']=='user'){
			$numval=1;

		}else if($displaytype=="fetchclientdata"&&$outs['usertype']=='client'){
			$numval=1;
		}
		$totalpages=$numval;
		$numrows=$numval;
		if($numrows>0){
			$msg="Retrieval Successful";
		 	echo json_encode(array("success"=>"true","msg"=>"$msg","catdata"=>$outs,"page"=>"$page","totalpages"=>"$totalpages"));
		 	if($test!==""){
				$scriptout='
				<script>
					var sdata='.json_encode(array("success"=>"true","msg"=>"$msg","catdata"=>$outs,"page"=>"$page","totalpages"=>"$totalpages")).';
				</script>
				';
				echo $scriptout;
			}	
		}else{
			$msg="Retrieval fail";
			echo json_encode(array("success"=>"false","msg"=>"$msg","userid"=>"$userid","catdata"=>"","totalpages"=>"0"));

		}
	}else if ($displaytype=="transactions") {
		# code...
		if($test!==""){
			$page=mysql_real_escape_string($_GET['page']);
			$userid=mysql_real_escape_string($_GET['userid']);
			
		}else if ($test=="") {
			# code...
			$page=mysql_real_escape_string($_POST['page']);
			$userid=mysql_real_escape_string($_POST['userid']);
		}
		$prevlimit=($page*$host_app_pull_limit)-$host_app_pull_limit>0?($page*$host_app_pull_limit)-$host_app_pull_limit:0;
		$nextlimit=$page*$host_app_pull_limit;
		$limit="LIMIT $prevlimit,$nextlimit";
		// echo $limit."<br>";
		$outs=getAllTransactions('viewer','all','appusertransactions',$userid);
		$totalpages=$outs['num_pages'];
		$numrows=$outs['numrows'];
		if($numrows>0){
			$msg="Retrieval Successful";
		 	echo json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages"));
		 	if($test!==""){
				$scriptout='
				<script>
					var sdata='.json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages")).';
				</script>
				';
				echo $scriptout;
			}	
		}else{
			$msg="Retrieval fail";
			echo json_encode(array("success"=>"false","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"totalpages"=>"0"));

		}
	}else if ($displaytype=="paymentstatus") {
		# code...
		if($test!==""){
			$page=mysql_real_escape_string($_GET['page']);
			$userid=mysql_real_escape_string($_GET['userid']);
			$cid=mysql_real_escape_string($_GET['cid']);
			
		}else if ($test=="") {
			# code...
			$page=mysql_real_escape_string($_POST['page']);
			$userid=mysql_real_escape_string($_POST['userid']);
			$cid=mysql_real_escape_string($_POST['cid']);
		}
		$prevlimit=($page*$host_app_pull_limit)-$host_app_pull_limit>0?($page*$host_app_pull_limit)-$host_app_pull_limit:0;
		$nextlimit=$page*$host_app_pull_limit;
		$limit="LIMIT $prevlimit,$nextlimit";
		// echo $limit."<br>";
		$outs=getAllTransactions('viewer','all','appuserpaymentstatus',$userid,$cid);
		$totalpages=$outs['num_pages'];
		$numrows=$outs['numrows'];
		if($numrows>0){
			$msg="Retrieval Successful";
		 	echo json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages"));
		 	if($test!==""){
				$scriptout='
				<script>
					var sdata='.json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$outs['catdata'],"page"=>"$page","totalpages"=>"$totalpages")).';
				</script>
				';
				echo $scriptout;
			}	
		}else{
			$totalpages=0; 
			$msg="Retrieval fail";
			$catdata[0]['transactionstatus']="no payment made";
			echo json_encode(array("success"=>"false","msg"=>"$msg","userid"=>"$userid","catdata"=>$catdata,"totalpages"=>"0"));
			if($test!==""){
				$scriptout='
				<script>
					var sdata='.json_encode(array("success"=>"true","msg"=>"$msg","userid"=>"$userid","catdata"=>$catdata,"page"=>"$page","totalpages"=>"$totalpages")).';
				</script>
				';
				echo $scriptout;
			}	
		}
	}else if ($displaytype=="editappuser") {
		# code...
		if($test!==""){
			$page=mysql_real_escape_string($_GET['page']);
			$userid=mysql_real_escape_string($_GET['userid']);
			$entrypoint=isset($_GET['entrypoint'])?$_GET['entrypoint']:"";
		}else if ($test=="") {
			# code...
			$page=mysql_real_escape_string($_POST['page']);
			$userid=mysql_real_escape_string($_POST['userid']);
			$entrypoint=isset($_POST['entrypoint'])?$_POST['entrypoint']:"";
		}
		$entryid=$userid;
		$uid=$entryid;
		$uhash=md5($uid);
		$userdata=getSingleUser($entryid);

		$catid=0;
		$entrypoint=isset($_POST['entrypoint'])?$_POST['entrypoint']:"";
		// $catid=mysql_real_escape_string($_POST['catid']);
		$firstname=isset($_POST['firstname'])?mysql_real_escape_string($_POST['firstname']):"";
		genericSingleUpdate("users","firstname",$firstname,"id",$entryid);
		/*$middlename=mysql_real_escape_string($_POST['middlename']);
		genericSingleUpdate("users","middlename",$middlename,"id",$entryid);*/
		$lastname=isset($_POST['firstname'])?mysql_real_escape_string($_POST['lastname']):"";
		genericSingleUpdate("users","lastname",$lastname,"id",$entryid);
		// $nickname=mysql_real_escape_string($_POST['nickname']);
		$fullname=$firstname." ".$lastname;
		genericSingleUpdate("users","fullname",$fullname,"id",$entryid);
		if($firstname!==""){
		 	createNotification($entryid,"users","update","Profile firstname was updated");

		}
		if($lastname!==""){
		 	createNotification($entryid,"users","update","Profile lastname was updated");

		}
		if($fullname!==""){
		 	createNotification($entryid,"users","update","Profile fullname was updated");

		}
		/*$status=isset($_POST['status'])?mysql_real_escape_string($_POST['status']):"active";
		genericSingleUpdate("users","status",$status,"id",$entryid);*/
		$email=isset($_POST['email'])?mysql_real_escape_string($_POST['email']):"";
		if($email!==""){
			$emaildata['email']="$email";
			$emaildata['fieldcount']=1;
			$emaildata['logic'][0]="AND";
			$emaildata['column'][0]="usertype";
			$emaildata['value'][0]="user";
			$emaildata=checkEmail($emaildata,"users","email");
			$password=$_POST['password'];
			$prevpassword=$_POST['prevpassword'];
			if($prevpassword!==""&&$prevpassword==$userdata['pword']&&$password!==""){
				genericSingleUpdate("users","pword",$password,"id",$entryid);
				// clear out user content, basically log them out and send em to the login page
			    // unset($_SESSION['useri']);
			    // unset($_SESSION['userh']);
			    // header('location:../signupin.php');
			}
		}
		// upload user profile image
		$bizlogo=isset($_FILES['profpic']['tmp_name'])?$_FILES['profpic']['tmp_name']:"";
		if($bizlogo!==""){
		  $image="profpic";
		  $imgpath[]='../files/originals/';
		  $imgpath[]='../files/medsizes/';
		  $imgpath[]='../files/thumbnails/';
		  $imgsize[]="default";
		  $imgsize[]=",240";
		  $imgsize[]=",150";
		  $acceptedsize="";
		  $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
		  $len=strlen($imgouts[0]);
		  $imagepath=substr($imgouts[0], 1,$len);
		  // medium size
		  $len2=strlen($imgouts[1]);
		  $imagepath2=substr($imgouts[1], 1,$len2);
		  //  thumbnail size
		  $len3=strlen($imgouts[2]);
		  $imagepath3=substr($imgouts[2], 1,$len3);
		  // get image size details
		  list($width,$height)=getimagesize($imgouts[0]);
		  $imagesize=$_FILES[''.$image.'']['size'];
		  $filesize=$imagesize/1024;
		  //// echo $filefirstsize;
		  $filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
		  if(strlen($filesize)>3){
		    $filesize=$filesize/1024;
		    $filesize=round($filesize,2); 
		    $filesize="".$filesize."MB";
		  }else{
		    $filesize="".$filesize."KB";
		  }
		  
		  if($imgid<1){
		    $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,details,filesize,width,height)VALUES
		    ('$entryid','appuser','coverphoto','image','$imagepath','$imagepath2','$filesize','$width','$height')";
		    $mediarun=mysql_query($mediaquery)or die(mysql_error()." ".__LINE__);
		    
		  }else{
		    $imgdata=getSingleMediaDataTwo($imgid);
		    $prevpic=$imgdata['location'];
		    $prevthumb=$imgdata['details'];
		    $realprevpic=".".$prevpic;
		    $realprevthumb=".".$prevthumb;
		    if(file_exists($realprevpic)&&$realprevpic!=="."){
		      unlink($realprevpic);
		    }
		    if(file_exists($realprevthumb)&&$realprevthumb!=="."){
		      unlink($realprevthumb);
		    }
		    genericSingleUpdate("media","location",$imagepath,"id",$imgid);
		    // genericSingleUpdate("media","details",$imagepath2,"id",$imgid);
		    genericSingleUpdate("media","filesize",$filesize,"id",$imgid);
		    genericSingleUpdate("media","width",$width,"id",$imgid);
		    genericSingleUpdate("media","height",$height,"id",$imgid);
		    // echo "in here";
		  }
		}
		$successstatus="true";
		$msg="Updated Successfully";
		if($email!==""){
			// verify email once more and proceed only when the email is umatched
			if($emaildata['testresult']=="unmatched"||($emaildata['testresult']=="matched"&&$emaildata['usertype']!=="appuser")){    
				genericSingleUpdate("users","email",$email,"id",$entryid);	
				if($entrypoint=="webapp"){

				}else {
				  	// echo json response here
				  	$successstatus="true";
				  	$msg="Updated Successfully";
		 			createNotification($entryid,"users","update","Profile email was updated");
				  
				}
			} else{
				if($entrypoint=="webapp"){
				    echo "The email address you attempted registering is invalid";
				}else {

				  	// echo json response here
				  	$successstatus="false";
					$msg="The email address you attempted changing to is invalid or taken";

				}

			}
		}
		// end of email mark section
		if($entrypoint=="webapp"){
			header('location:../admin/adminindex.php?compid=4&type=0&v=admin&ctype='.$entryvariant.'');  
		}else {

		  // echo json response here
		  echo json_encode(array("success"=>"$successstatus","msg"=>"$msg"));

		}

	}else if ($displaytype=="resetpassword") {
		# code...

		if($test!==""){
			$page=mysql_real_escape_string($_GET['page']);
			$entrypoint=isset($_GET['entrypoint'])?$_GET['entrypoint']:"";
			$email=isset($_GET['email'])?mysql_real_escape_string($_GET['email']):"";
		}else if ($test=="") {
			# code...
			$page=mysql_real_escape_string($_POST['page']);
			$entrypoint=isset($_POST['entrypoint'])?$_POST['entrypoint']:"";
			$email=isset($_POST['email'])?mysql_real_escape_string($_POST['email']):"";
		}
	    $linkout="";

		if($email!==""){
			$emaildata['email']="$email";
			$emaildata['fieldcount']=1;
			$emaildata['logic'][0]="AND";
			$emaildata['column'][0]="usertype";
			$emaildata['value'][0]="appuser";
			$cmail=checkEmail($emaildata,"users","email");
		    if($cmail['testresult']=="matched"){
		        $uid=$cmail['id'];
		        $udata=getSingleUserPlain($uid);
		        $userh=$udata['uhash'];
		        $fullname=$udata['fullname'];
		        $checksum=md5(date("Y-m-d H:i:s").$userh);
		        if($udata['status']=="active"){

			        // store the current entry in the resetpassword table
			        $query="INSERT INTO resetpassword (userid,checksum,entrydate)VALUES('$uid','$checksum',CURRENT_DATE())";
			        $run=mysql_query($query)or die(mysql_error()." ".__LINE__);
			        // send the link
			        $link=''.$host_addr.'reset.php?h='.$userh.'&t=reset&checksum='.$checksum.'';
			        $title="Your Reset Link";
			        $content='
			          <p style="text-align:left;">Hello '.$fullname.',<br>
			          You just made a password reset request so we have your link below, <br>
			          Here it is, just follow it and perform the reset:<br>
			          <a href="'.$link.'">Reset Password</a>
			          </p>
			          <p style="text-align:right;">Thank You.</p>
			        ';
			        $footer='
			            <ul>
					        <li><strong>Phone 1: </strong>0807-207-6302</li>
					        <li><strong>Email: </strong><a href="mailto:info@napstand.com">info@napstand.com</a></li>
					    </ul>
			        ';
			        $emailout=generateMailMarkUp("napstand.com","$email","$title","$content","$footer","");
			        // echo $emailout['rowmarkup'];
			        $toemail=$email;
			        $headers = "MIME-Version: 1.0" . "\r\n";
			        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			        $headers .= 'From: <no-reply@napstand.com>' . "\r\n";
			        $subject="Password Reset";
			        if($host_email_send===true){
			          if(mail($toemail,$subject,$emailout['rowmarkup'],$headers)){
			          	$successstatus="true";
			          	$msg="Successfully sent mail";
			          }else{
			          	$successstatus="false";
			          	$msg="could not send Your email";
			            // die('could not send Your email, something went wrong and we are handling it, meantime you could click the back button in your browser to get you out of here, we are really sorry');
			          }
			        }else{
			        	$successstatus="true";
			          	$msg="Local test environment recognized";
			        }
					if($test!==""){
						$linkout=$link;
			        }   
		  			echo json_encode(array("success"=>"$successstatus","msg"=>"$msg","link"=>"$linkout"));
					if($test!==""){
						$linkout=$link;
			  			$scriptout='
						<script>
							var sdata='.json_encode(array("success"=>"$successstatus","msg"=>"$msg","link"=>"$linkout")).';
						</script>
						';
						// echo $scriptout;
			        }

		        }
		    }else{
			    $successstatus="false";

			    $msg="Local test environment recognized but email varification failed";

		  		echo json_encode(array("success"=>"$successstatus","msg"=>"$msg","link"=>"$linkout"));
		  		if($test!==""){
		  			$scriptout='
					<script>
						var sdata='.json_encode(array("success"=>"$successstatus","msg"=>"$msg","link"=>"$linkout")).';
					</script>
					';
					// echo $scriptout;
		  		}
		    }
		}
		
	}else if ($displaytype=="forceuserreset") {
		# code...
		if($test!==""){
			$userid=mysql_real_escape_string($_GET['userid']);
			$page=mysql_real_escape_string($_GET['page']);		
		}else if ($test=="") {
			# code...
			$userid=mysql_real_escape_string($_POST['userid']);
			$page=mysql_real_escape_string($_POST['page']);
			
		}
		$userdata=getSingleUser($userid);
		$status=$userdata['status'];
		if($status=="active"){
			// get a login hash for this user
			$hashvar=$userid."_".date("Y-m-d H:i:s");
			$hashvar=md5($hashvar);
			// get previous login information
			$orderfield[0]='userid';
			$orderfield[1]='usertype';
			$orderfield[2]='action';
			$ordervalue[0]=$userid;
			$ordervalue[1]='users';
			$ordervalue[2]='login';
			$order=" ORDER BY id DESC";
			$limit=" LIMIT 0,1";
			$outlogprev=getNotifications('notifications',$orderfield,$ordervalue,$order,$limit);
			$lastloginid=0;
			$lastloghash="";
			// obtain last login id
			if($outlogprev['numrows']>0){
				// $lastloginid=$outlogprev['numrows']>1?$outlogprev[0]['id']:$outlogprev['id'];
				$lastloginid=$outlogprev['id'];
				// get last loginhash
				$lastloghash=$outlogprev['actionhash'];
			}
			// get log out information
			$orderfield[0]='userid';
			$orderfield[1]='usertype';
			$orderfield[2]='action';
			$ordervalue[0]=$userid;
			$ordervalue[1]='users';
			$ordervalue[2]='logout';
			$order=" ORDER BY id DESC";
			$limit=" LIMIT 0,1";
			$outlogprev2=getNotifications('',$orderfield,$ordervalue,$order,$limit);
			$lastlogoutid=0;
			// obtain last logout date
			if($outlogprev2['numrows']>0){
				// $lastlogoutid=$outlogprev2['numrows']>1?$outlogprev2[0]['id']:$outlogprev2['id'];				
				$lastlogoutid=$outlogprev2['id'];				
			}
			// echo "lastlogin:$lastloginid  lastlogout:$lastlogoutid <br>";
			if(($lastlogoutid==0&&$lastloginid==0)||($lastlogoutid>$lastloginid)||($lastloginid>$lastlogoutid&&$lastloghash=="")){
				// this means the current user is on a device that hasn't been logged in
				// or they are first timers
				$msg="User has no valid login record to reset";
				// createNotification($userid,"users","login","$msg","","","$hashvar");
	 			echo json_encode(array(	"success"=>"false",
	 									"msg"=>"$msg",
	 									"userid"=>"$userid",
	 									"status"=>"$status"));
			}else{
				$msg="Successfully Logged out";
				genericSingleUpdate('notifications',"entrydate","$todaynow",'id',"$lastloginid");
				createNotification($userid,"users","logout",$msg);
	 			echo json_encode(array(	"success"=>"true","msg"=>"$msg","userid"=>"$userid","status"=>"$status"));					
				

			}
			if($test!==""){

			}
		}else{
			$msg="Account has been disabled";

			echo json_encode(array("success"=>"false","msg"=>"$msg","userid"=>"","firstname"=>"","middlename"=>"","lastname"=>"","status"=>""));
		}

	}else if ($displaytype=="accountactivation") {
		# code...
		if(isset($_GET['uh'])){
			// echo "test two";
			$uhashdata=$_GET['uh'];
			$email=isset($_GET['utm_email'])?$_GET['utm_email']:"";
			$uhashdata=explode(".", $uhashdata);
			$uhash=$uhashdata[0];
			$uid=$uhashdata[1];
			$userdata=getSingleUser($uid);
			if(md5($uid)==$uhash){
				genericSingleUpdate("users","activationstatus","active","id","$uid");
				genericSingleUpdate("users","activationdeadline","0000-00-00","id","$uid");
			}
			header('location:../index.php?t=actiavtionsuccess');
		}else{
			header('location:../index.php?t=actiavtionerror');
		}
	}
}else if($dhash=="false"){
	echo $dhasherrout;
}
?>