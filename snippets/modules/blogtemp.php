<?php
	
	// this file is called by every blog post
	// for running certain test scenarios
	$test=isset($_GET["test"])?$_GET["test"]:"";
	$test=$test==""&&isset($_POST["test"])?$_POST["test"]:"";

	// this variable is an accompanying set of JSON parameters 
	$dt=isset($_GET["dt"])?$_GET["dt"]:"";
	$dt=$dt==""&&isset($_POST["dt"])?$_POST["dt"]:$dt;

	// get the blogdata
	$bdata["single"]["viewer"]="viewer";
	// echo "testing";
	$blogdata=getSingleBlogEntry("$pageid","blockdeeprun",$bdata);
	$newview=$blogdata["views"]+1;
	genericSingleUpdate("blogentries","views",$newview,"id",$pageid);

?>