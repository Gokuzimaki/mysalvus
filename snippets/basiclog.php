<?php
include('connection.php');
$logtype=$_POST['logtype'];
// echo $logtype;
if($logtype=="adminlogin"){
	$username=mysql_real_escape_string($_POST['username']);
	$password=mysql_real_escape_string($_POST['password']);
	$iniquery="SELECT * FROM admin WHERE username='$username' AND password='$password' AND status='active'";
	$inirun=briefquery($iniquery,__LINE__,"mysqli");
	$numrows=$inirun['numrows'];
	if($numrows>0){
		if(session_id() == ''){
			session_start();
		}
		$row=$inirun['resultdata'][0];
		$logpart=md5($host_addr);
		$_SESSION['logcheck'.$logpart.'']="off";
		$_SESSION['aduid'.$logpart.'']=$row['id'];
		$_SESSION['accesslevel'.$logpart.'']=$row['accesslevel'];
		$_SESSION['parentheader'.$logpart.'']=$row['parentheader'];
		// echo $_SESSION['accesslevel'.$logpart.'']." accesslevel<br>";
		// echo $_SESSION['aduid'.$logpart.'']." aduid<br>";
		$logtype=="adminlogin"?header('location:../admin/adminindex.php'):header('location:../fjcadmin/adminindex.php');
	}else{
		// $_SESSION['adminerror']=$_SESSION['adminerror']+1;
		// echo $_SESSION['adminerror'];
		$logtype=="adminlogin"?header('location:../admin/index.php?error=true'):header('location:../fjcadmin/index.php?error=true');
	}
}elseif($logtype=="user"||$logtype=="serviceprovider"){
	$username=mysql_real_escape_string($_POST['username']);
	$password=mysql_real_escape_string($_POST['password']);
	$weblog=isset($_POST['weblog'])?mysql_real_escape_string($_POST['weblog']):"";
	$iniquery="SELECT * FROM users WHERE (email='$username' OR username='$username') 
	AND pword='$password' AND usertype='$logtype' AND status='active'";
	$inirun=mysql_query($iniquery)or die(mysql_error());
	$numrows=mysql_num_rows($inirun);
	if($numrows>0){
		$row=mysql_fetch_assoc($inirun);
		$id=$row['id'];
		session_start();
		$md5id=md5($id);

		if($row['usertype']=="business"||$row['usertype']=="serviceprovider"){
			$_SESSION['clientimysalvus'.$md5id.'']=$row['id'];
			$_SESSION['clienthmysalvus']=$md5id;
			if($weblog==""){
				header('location:../clientdashboard.php');
			}else{
				$success="true";
				$msg="Successful login";
				echo json_encode(array("success"=>"$success","msg"=>"$msg"));
			}
		}else{
			$_SESSION['userimysalvus'.$md5id.'']=$row['id'];
			$_SESSION['userhmysalvus']=$md5id;
			if($weblog==""){
				header('location:../userdashboard.php');
			}else{
				$success="true";
				$msg="Successful login";
				echo json_encode(array("success"=>"$success","msg"=>"$msg"));
			}
		}
	}else{
		if($row['usertype']=="business"||$row['usertype']=="serviceprovider"){
			//	echo $_SESSION['adminerror'];
			header('location:../clientlogin.php?ctype=logerror');
		}else{
			header('location:../login.php?ctype=logerror');

		}
	}
}
?>