<?php

	// this is the default script for parsing multiple blog post display related 
	// content on the platform
	// later work would include making a shortcode parsing system available to handle
	// both system and userdefined shortcode.
	// the file will be called/included in whatever blog page relys on the theme
	// basic requirements for this page to function well is an 'outs' variable
	// which is an array provided from the file calling this, if none is available
	// then it would be created here directly, 
	// N.B Pagination would also be done in the correspondingly named file located in
	// the active themes directory 
	
	// test to know if the database connection file is available and all required files 
	// are initialised
	if(!isset($host_tpath)){
		include(dirname(__FILE__)."\..\\"."globalsmodule.php");

	}

	// The $outs variable is an array carrying pagination related data
	// the indexes $outs['prev_url'], $outs['next_url'] are used for
	// carrying the previous and next pointer url values
	
	// the indexes $outs['num_url'] & $outs['pagenumbers'] are used for
	// holding the urls for the pages to be displayed and the corresponding 
	// numeric value attached to each url

	// the indexe $outs['current_page'] is used for carrying the number
	// of the current page being viewed
	
	// the index $outs['usercontrols'] is used for carrying the jump menu
	// set of options comprising of two select boxes on carrying the current
	// number of available pages and the other carrying the number of 
	// result instances per page,i.e number of results shown per view 
	
	// First off we check if the '$outs' variable is available, if it isnt, we hijack
	// the whole blog rendering process and use the default/first blogtype (blogtypeid=1) 
	// created as the basis for data retrieval.

	// code begins
	// initialise hijack variable for telling if there is no prior $outs variable
	// and as such letting us know if we should initialise one here.
	$hijack="no";
	
	// check if the blog type id is available
	if(!isset($blogtypeid)){
		$blogtypeid=1;
		
	}
	$b_data['blogtypeid']=$blogtypeid;


	if(!isset($outs)){
		$hijack="yes";

	}else if(isset($outs)&&is_array($outs)){
		if(!isset($blogdata['numrows'])){
			$hijack="yes";
		}
	}

	// create the outs variable
	if($hijack=="yes"){
		// since this view is mainly for users
		$qextra=" status='active'";

		// check if the user ran a search
		if(isset($_GET['q'])&&$_GET['q']!==""){
			$q=mysql_real_escape_string($_GET['q']);
			$qcat=$qextra==""?"":" AND";
			$qextra.=" $qcat (title LIKE '%$q%' OR blogpost LIKE '%$q%'
				OR introparagraph LIKE '%$q%' OR seometakeywords LIKE '%$q%'
				OR seometadescription LIKE '%$q%' OR tags LIKE '%$q%')";
		}

		// check if there are tags
		if(isset($_GET['tags'])&&$_GET['tags']!==""){
			$q=mysql_real_escape_string($_GET['tags']);
			$qcat=$qextra==""?"":" AND";
			$qextra.=" $qcat tags LIKE '%$q%')";
		}

		

		// check if there is a date range
		if((isset($_GET['rs'])&&$_GET['rs']!=="")||
			(isset($_GET['rs'])&&$_GET['rs']!=="")){
			$qcat=$qextra==""?"":" AND";
			if($_GET['rs']!==""){
				$startdate=mysql_real_escape_string($_GET['rs']);
			}
			if($_GET['re']!==""){
				$enddate=mysql_real_escape_string($_GET['re']);
			}
			$col="date";
			if($enddate!==""&&$startdate!==""){
				// perform date comparison and reassignment
				$datetime1 = new DateTime("$startdate"); 
				$datetime2 = new DateTime("$enddate"); 
				if($datetime1<$datetime2){
					$ers="$qcat $col>='$startdate'";
					$ere="AND $col<='$enddate'";
				}else{
					$ers="$qcat $col<='$startdate'";
					$ere="AND $col>='$enddate'";
				}
			}else{
				$ers=$startdate!==""?"$qcat $col>='$startdate'":"";
				$ere=$enddate!==""?"$qcat $col<='$enddate'":"";
			}
			$qextra.="$ers $ere";
			
		}

		// check if the current view is for a particular category;
		$catdata=array();
		$catdata['valid']=false;
		if(isset($_GET['cid'])&&$_GET['cid']!==""&&
			is_numeric($_GET['cid'])&&$_GET['cid']>0&&!isset($_GET['c'])
			){
			$cid=$_GET['cid'];
			$catdata=getSingleBlogCategory($cid);
			
			if(isset($catdata['blogtypeid'])){
				// this is a valid categoryid
				$cid=mysql_real_escape_string($_GET['cid']);
				$qcat=$qextra==""?"":" AND";
				$qextra.=" $qcat blogcatid='$cid'";
				$catdata['valid']=true;
			}

		}


		if($qextra!==""){
			$b_data['queryextra']=$qextra;
		}
		
		$b_data['rvpagination']="true";
		
		
		$qcat=$qextra==""?"":" AND";

		$qextra=" $qcat $qextra";
		$b_data['queryorder']="ORDER BY date DESC";
		// this overrides the query to be executed
		$b_data['queryoverride']="";
		// default query to execute
		$query="SELECT * FROM blogentries WHERE blogtypeid='$blogtypeid' $qextra";
		// echo "$query";

		$outs=paginate($query);
		$b_data['upag']= $outs;
		// var_dump($b_data);

		//next check if the $b_options array is setup, if it isnt create and init it
		// this array will be utitlised by any file that calls or makes inference
		// to this parser module. it is used to set options such as :
		if(!isset($b_options)){
			// init $b_options and set its 'type' index value to the default
			// 'maincontent'
			$b_options=array();
		}
		$b_options['limit']=!isset($b_options['limit'])?$outs['limit']:$b_options['limit'];
		$b_options['queryextra']=!isset($b_options['queryextra'])?"":
		$b_options['queryextra'];
		$b_options['type']=!isset($b_options['type'])?"maincontent":$b_options['type'];
		// for pagination content
		// specify that pagination be made available by default
		$b_options['pagination']=!isset($b_options['pagination'])?true:
		$b_options['pagination'];

		$b_options['paginationtype']=!isset($b_options['paginationtype'])?"default":
		$b_options['paginationtype'];
		
		// check for pagination option from the url get parameters
		if(isset($_GET['b_optpag'])&&$_GET['b_optpag']!==""){
			$b_options['paginationtype']=$_GET['b_optpag'];
		}
		if(isset($_POST['b_optpag'])&&$_POST['b_optpag']!==""){
			$b_options['paginationtype']=$_POST['b_optpag'];
			
		}

		// control whether access to the top pagination or bottom pagination is
		// allowed
		$b_options['showtoppages']=!isset($b_options['showtoppages'])?true:
		$b_options['showtoppages'];

		$b_options['showbottompages']=!isset($b_options['showbottompages'])?true:
		$b_options['showbottompages'];

		// adjust the value of the limit variable so it can be controlled via the
		// $b_options array allowing for flexibility when controlling expected output
		$limit=isset($b_options['limit'])&&$b_options['limit']!==""?$b_options['limit']:
		$outs['limit'];

		// adjust the value of the 'queryextra' and 'ordercontent' entry to the 
		// database.

		$querydebug=isset($b_options['querydebug'])&&$b_options['querydebug']!==""
		?$b_options['querydebug']:false;

		$queryextra=isset($b_options['queryextra'])&&$b_options['queryextra']!==""
		?$b_options['queryextra']:$b_data['queryextra'];

		$queryorder=isset($b_options['queryorder'])&&$b_options['queryorder']!==""?
		$b_options['queryorder']:$b_data['queryorder'];

		$queryoverride=
		isset($b_options['queryoverride'])&&$b_options['queryoverride']!==""?
		$b_options['queryoverride']:$b_data['queryoverride'];

		// assign the final values of the queryextra and queryorder to the data array
		// variable
		$b_data['queryextra']=$queryextra;
		$b_data['queryorder']=$queryorder;
		$b_data['queryoverride']=$queryoverride;
		$b_data['querydebug']=$querydebug;

		// var_dump($b_data);
		// var_dump($limit);
		// get the blogdata
		$b_data["single"]["type"]="blockdeeprun";
		$blogdata=getAllBlogEntries('viewer',$limit,"",$b_data);
		unset($b_data);
		// unset($b_options);
		// var_dump($blogdata);

	}else{
		// all the work has already been done elsewhere and this function is just
		// being called to properly provide the main theme blog parsing script.
	}
	// check to see if the category has a private render path attached to it
	// include the necessary file to be viewed
	// get the blogtype data and include its renderpath value or if the update has been
	// done use the themeid value to get at the themes 'blogrenderpath' value

	$typedata=getSingleBlogType($blogtypeid,"blockdeeprun");
	if(isset($typedata['renderpath'])&&$typedata['renderpath']!==""&&!isset($widget_type)){
		include($host_tpath.$typedata['renderpath']."modules/blogpagemultipleparser.php");
	}else if(isset($widget_type)){
		// pull in the blogpagempplain.php file instead for use with the widgets
		// echo $widget_type." :widget";
		include($host_tpath.$typedata['renderpath']."modules/blogpagempplain.php");
	}
	unset($typedata);
?>

