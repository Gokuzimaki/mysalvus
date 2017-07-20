<?php 
session_start();
include('../snippets/connection.php');
if(isset($_GET['p'])&&is_numeric($_GET['p'])){
	$pageid=$_GET['p'];
	$c="";
	if(isset($_GET['c'])&&$_GET['c']){
		$c="?c=true";
	}
$outs=getSingleBlogEntry($pageid);
$location=$outs['pagelink'];
header('location:'.$location.''.$c.'');
}else{
header('location:../index.php');
}
// echo $outpage['outputpageone'];
?>