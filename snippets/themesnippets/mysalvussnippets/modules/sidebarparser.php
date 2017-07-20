<?php
	// this file contains sidebar content for the pages using this theme
	// absolute urls must be used as required for handling assets
	// here widgets are defined and knitted together to form sidebar contents 
	// for varying purposes.
	// usually, most of the b_options variable settings array could be defined 
	// before including this file
		
	// or the options can be set based on the preset sidebartype variable
	
	// clear all previous options
	unset($b_options);

	$sidebartype=!isset($sidebartype)||(isset($sidebartype)&&$sidebartype=="")?"default"
	:$sidebartype;

	$blogtypeid=!isset($blogtypeid)||(isset($blogtypeid)&&$blogtypeid=="")?""
	:$blogtypeid;
	// use this section to init the b_options array variables for each 
	// widget type to be used
	if($sidebartype=="default"){
		// $b_options['querydebug']=true;
		$widget_options['theme_bppwidget']=true;
		$widget_options['theme_bmcpwidget']=true;

		$b_options['pagination']=false;

		// adjust the blog most commented post data output
		$b_options['bmcptype']="mainsidebarmini2";
		
		// handle the number of comment posts to be displayed
		$b_options['bmcplimit']=1;

	}else if($sidebartype=="faqdefault"){
		$widget_options['theme_rfwidget']=true;

	}
	// widget titles the defaultwidgets bring in
	// theme_blpwidget: for blog latest posts
	// theme_bppwidget: for blog popular posts(mostviewed)
	// theme_bmcpwidget: for blog most commented posts
	// theme_bfpwidget: for blog featured posts
	if($sidebartype=="default"){
		// prepare the widgets that are needed

	}
	// bring in the system default widget set
	include($host_tpathplain.'modules/defaultwidgets.php');



	//  begin widget definitions as per theme
	// include sorted widget definitions file
	include('widgets.php');
	
	if($sidebartype=="default"){
		
		// blog search widget
		echo $theme_bswidget;

		// single advert space mini
		// echo $theme_sasmwidget;

		// blogsubscription
		// echo $theme_bsubwidget;

		// popular blog post
		// echo $theme_pbpwidget;


		// quad advert
		// echo $theme_qaswidget;

		// most commented blog post
		// echo $theme_mcbpwidget;

		// single advert space large
		// echo $theme_saslwidget;		
		$theme_outputwidget="
			$theme_bswidget
			$theme_sasmwidget
			$theme_bsubwidget
			$theme_pbpwidget
			$theme_mcbpwidget
			$theme_saslwidget
		";
		// unset the widgets you use afterwards;
		// to decrease chance of memory use error
		unset($GLOBALS['theme_bswidget']);
		unset($GLOBALS['theme_sasmwidget']);
		unset($GLOBALS['theme_bsubwidget']);
		unset($GLOBALS['theme_pbpwidget']);
		unset($GLOBALS['theme_mcbpwidget']);
		unset($GLOBALS['theme_saslwidget']);
	}else if($sidebartype=="faqdefault"){
		echo $theme_fswidget;
	}
?>
		