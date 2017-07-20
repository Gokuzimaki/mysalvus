<?php
	// this section handles the default notification update and redirection 
	// actions for the basicsignup and edit.php files
	
	// this controls the return path for redirection
	
	if((isset($_POST['returl'])&&!isset($returl))||
		(isset($returl)&&$returl=="")){
		$returl=$_POST['returl'];
	}else if(isset($returl)&&$returl!==""){
		$returl=$returl;
	}else{
		$returl="";
	}
	$notid=isset($notid)?$notid:0;
	// echo $returl;
	if($returl!==""){
		$returl=str_replace("$host_addr", "../", $returl);
			// echo "we r here";
		if(strpos($returl, "?")){
			$prms="&";
			if(strpos($returl, "?compid")){
				// remove the compid extra
				$returl=substr($returl, 0,strpos($returl, "?compid"));
				$prms="?";

			}
			if(strpos($returl, "&compid")){
				// remove the compid extra
				$returl=substr($returl, 0,strpos($returl, "&compid"));

			}
			// echo "we r here";
		}
	}

	$utype=isset($_POST['utype'])?$_POST['utype']:"";

	$ttype="";

	// setting a value for this variable stops a redirect from being carried out
	$notrdtype=isset($notrdtype)?$notrdtype:"";//redirection control

	// setting a value for this variable stops a notification update 
	// from being done
	$nonottype=isset($nonottype)?$nonottype:"";//redirection control
	$logpart=md5($host_addr);
	$notuid=isset($_SESSION['aduid'.$logpart.''])&&$_SESSION['aduid'.$logpart.'']!==""?$_SESSION['aduid'.$logpart.'']:"1";
	$parentheader=isset($_SESSION['parentheader'.$logpart.''])&&$_SESSION['parentheader'.$logpart.'']!==""?$_SESSION['parentheader'.$logpart.'']:"1";
	
	if($utype!==""){
		if($utype=="user"){
			$notuid=isset($_SESSION['useri'.$host_name.''.$_SESSION['userh'.$host_name.''].''])?
					$_SESSION['useri'.$host_name.''.$_SESSION['userh'.$host_name.''].'']:"";
			// change the parent header value to suit the current usertype
			// for the purpose of redirecting them properly after the update has been 
			// done
			$parentheader="user";
		}else if($utype=="business"||$utype=="serviceprovider"||$utype=="client"){
			$notuid=isset($_SESSION['clienti'.$host_name.''.$_SESSION['clienth'.$host_name.''].''])?
					$_SESSION['clienti'.$host_name.''.$_SESSION['clienth'.$host_name.''].'']:"";
			$parentheader="client";
		}

		// associated tablename stored in $ttype -tabletype variable
		$ttype="users";
	}else{

		// associated tablename stored in $ttype -tabletype variable
		$utype="admin";
		$ttype="admin";
	}

	// this defines the url parameter seperators to use when redirecting
	$prms=isset($prms)?$prms:"?";

	$compid=isset($compid)?$compid:0;
	$appendedparams=isset($appendedparams)?"$appendedparams":"";
	$action=isset($action)?$action:"";
	$actiontype=isset($actiontype)?$actiontype:"";
	$actionid=isset($notid)?$notid:"";
	$actiondetails=isset($actiondetails)?$actiondetails:"";
	$actionhash=isset($actionhash)?$actionhash:"";
	$viewlevelid=isset($viewlevelid)?$viewlevelid:"";
	$viewleveltype=isset($viewleveltype)?$viewleveltype:"";
	
	/*createNotification function parameters
	*userid
	*usertype -the table where this user can be found
	*action - [create,retrieve,update,delete,login,loginattempt,logout,accountactivation]
	*actionid - id of the target of an action when it occurs
	*actiontype - the table name of the affected target
	*actiondetails - miniature details on an action carried out
	*entrydate
	*
	*viewlevelid - [0,.....n] 0 represents the Super user only, 1 represents any other 
	*						admin account
	*
	*viewleveltype - represents the level of viewer that can see the notification
	*
	*
	*
	*/
	/*echo out the current batch of parameters */
	$triptest="1 $notuid"." 2 $ttype"." 3 $action"." 4 $actiondetails"." 5 $notid"." 6 $actiontype"." 7 $actionhash"." 8 $viewlevelid"." 9 $viewleveltype";
	// echo $triptest;
	if($nonottype==""&&$notid>0){
		createNotification("$notuid","$ttype","$action","$actiondetails","$notid",
							"$actiontype","$actionhash","$viewlevelid","$viewleveltype");
	}
	// creates the session activity success variable which will be tested against and
	// then destroyed by the redirection page
	$_SESSION['lastsuccess']=0;
	
	if($notrdtype==""){

		if($returl==""){
			if($parentheader=="rootadmin"||strtolower($parentheader)=="superuser"){
	    		header('location:../admin/adminindex.php?compid='.$compid.'&type='.$entryvariant.'&v=admin&d='.$notid.'');
			}else if($parentheader=="business"||$parentheader=="serviceprovider"||$parentheader=="client"){
	    		header('location:../clientdashboard.php?compid='.$compid.'&type='.$entryvariant.'&v=admin&d='.$notid.'');

			}else if($parentheader=="user"){
	    		header('location:../userdashboard.php?compid='.$compid.'&type='.$entryvariant.'&v=admin&d='.$notid.'');

			}
		}else{
			// echo $triptest;
	    	header('location:'.$returl.''.$prms.'compid='.$compid.'&type='.$entryvariant.'&d='.$notid.''.$appendedparams.'');

		}
	}
?>
