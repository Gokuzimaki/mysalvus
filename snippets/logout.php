<?php
session_start();
include('connection.php');
if(isset($_GET['type'])){
	$type=$_GET['type'];
	$subadmin=isset($_POST['sp'])?mysql_real_escape_string($_POST['sp']):"";
	$retp=isset($_POST['retp'])?mysql_real_escape_string($_POST['retp']):"";

	if($type=="admin"){
		$logpart=md5($host_addr);
		$_SESSION['logcheck'.$logpart.'']="on";
		unset($_SESSION['logcheck'.$logpart.'']);	
		unset($_SESSION['rurladmin']);	
		if($retp==""){
			header('location:../admin/?l=true');

		}else{
			$arrs=array("tu_","tl_");
			$arrp=array("../","./");
			$retp=str_replace($arrs, $arrp, $retp).".php";
			header('location:'.$retp.'?l=true');
			
		}
		// $type=="admin"?header('location:../admin/index.php'):header('location:../fjcadmin/index.php');
	}elseif($type=="user"){
		if(isset($_SESSION['userhmysalvus'])){
			unset($_SESSION['userhmysalvus']);
			unset($_SESSION['userimysalvus'.$_SESSION['userhmysalvus'].'']);
		}
		if(file_exists("../index.html")){
			header('location:../index.html?dologout=done');

		}else{
			header('location:../login.php?dologout=done');
			
		}
	}elseif($type=="business"||$type=="client"||$type=="serviceprovider"){
		if(isset($_SESSION['clienthmysalvus'])){
			unset($_SESSION['clienthmysalvus']);
			unset($_SESSION['clientimysalvus'.$_SESSION['clienthmysalvus'].'']);
		}

		header('location:../clientlogin.php?dologout=done');
	}
}else{
	header('location:../index.php');
}
?>