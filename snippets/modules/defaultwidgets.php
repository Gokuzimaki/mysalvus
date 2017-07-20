<?php
	// ini_set("memory_limit", "160M");
	// this file handles siderbar and footer widgets it carries all the widgets 
	// associated to the cms system
	$theme_bppwidget="";

	$theme_blpwidget="";

	$theme_bfpwidget="";

	$theme_brpwidget="";

	$theme_bmcpwidget="";

	$widget_type=true;
	
	###BEGIN WIDGET SET CREATION

	// widget: blog popular posts (bpp)
	// this widget launches a search and produces popular post results
	// unset the b_options variable and reassign it suitable values to funtion with
	// after which, include the blog parser module for the site
	if(isset($widget_options['theme_bppwidget'])&&
		$widget_options['theme_bppwidget']==true){
		if(isset($b_options)){
			$b_options['queryextra']=NULL;
			$b_options['limit']=NULL;
			$b_options['queryorder']=NULL;
			$b_options['type']=NULL;
		}

		$b_options['queryextra']="";
		
		$b_options['limit']=!isset($b_options['bpplimit'])?"LIMIT 0,5":
		"LIMIT 0,".$b_options['bpplimit'];

		$b_options['showtoppages']=!isset($b_options['bppshowtoppages'])?true:
		$b_options['bppshowtoppages'];

		$b_options['showbottompages']=!isset($b_options['bppshowbottompages'])?
		true:$b_options['bppshowbottompages'];

		$b_options['queryorder']="ORDER BY views DESC";
		
		$b_options['type']=!isset($b_options['bpptype'])?'mainsidebarmini':
		
		$b_options['bpptype'];
		
		$path=$host_tpathplain.'modules/blogpagemultipleparser.php';

		$initvar[0]="b_options";
		$initval[0]=$b_options;
		$initvar[1]="blogtypeid";
		$initval[1]=$blogtypeid;
		

		// refer to $host/snippets/utitlites.php,'get_include_contents' function for more info
		// on the function used here
		unset($widget_type);
		$theme_bppwidget=get_include_contents($path,$initvar,$initval);
		// $widget_type=true;
		// include($path);
		// $theme_bppwidget=isset($output)?$output:"";
	}

	


	// widget: blog latest posts (blp)
	// this widget launches a search and produces popular post results
	// unset the b_options variable and reassign it suitable values to funtion with
	// after which, include the blog parser module for the site
	if(isset($widget_options['theme_blpwidget'])&&
		$widget_options['theme_blpwidget']==true){

		if(isset($b_options)){
			// unset($b_options);
			unset($b_options['queryextra']);
			unset($b_options['limit']);
			unset($b_options['queryorder']);
			unset($b_options['type']);
		}

		$b_options['queryextra']="";
		// $b_options['querydebug']=true;
		$b_options['limit']=!isset($b_options['blplimit'])||
		(isset($b_options['blplimit'])&&$b_options['blplimit']=="")
		?"LIMIT 0,5":"LIMIT 0,".$b_options['blplimit'];
		// echo $b_options['limit'];

		$b_options['showtoppages']=!isset($b_options['blpshowtoppages'])?true:
		$b_options['blpshowtoppages'];

		$b_options['showbottompages']=!isset($b_options['blpshowbottompages'])?
		true:$b_options['blpshowbottompages'];

		$b_options['queryorder']="ORDER BY date DESC";
		// echo $b_options['queryorder'];
		$path=$host_tpathplain.'modules/blogpagemultipleparser.php';
		$b_options['type']=!isset($b_options['blptype'])?'mainsidebarmini':
		$b_options['blptype'];

		$initvar[0]="b_options";
		$initval[0]=$b_options;
		$initvar[1]="blogtypeid";
		$initval[1]=$blogtypeid;
		/*$initvar[2]="host_tpath";

		$initval[2]=$host_tpath;*/

		// refer to $host/snippets/utitlites.php get_include_contents() for more info
		// on the function used here
		// $widget_type=true;
		unset($widget_type);
		$theme_blpwidget= get_include_contents($path,$initvar,$initval);
		// include($path);
		// $theme_blpwidget=isset($output)?$output:"";
		// unset($b_options['queryextra']);
	}


	// widget: blog featured posts (bfp)
	// this widget launches a search and produces popular post results
	// unset the b_options variable and reassign it suitable values to funtion with
	// after which, include the blog parser module for the site
	if(isset($widget_options['theme_bfpwidget'])&&
		$widget_options['theme_bfpwidget']==true){

		if(isset($b_options)){
			$b_options['queryextra']=NULL;
			$b_options['limit']=NULL;
			$b_options['queryorder']=NULL;
			$b_options['type']=NULL;
		}
		$b_options['queryextra']=" featuredpost='on'";
		$b_options['limit']=!isset($b_options['bfplimit'])?"LIMIT 0,5":
		"LIMIT 0,".$b_options['bfplimit'];

		$b_options['showtoppages']=!isset($b_options['bfpshowtoppages'])?true:
		$b_options['bfpshowtoppages'];

		$b_options['showbottompages']=!isset($b_options['bfpshowbottompages'])?
		true:$b_options['bfpshowbottompages'];

		// $b_options['queryorder']="ORDER BY views DESC";
		$path=$host_tpathplain.'modules/blogpagemultipleparser.php';
		$b_options['type']=!isset($b_options['bfptype'])?'mainsidebarmini':$b_options['bfptype'];;

		$initvar[0]="b_options";
		$initvar[1]="blogtypeid";
		$initval[0]=$b_options;
		$initval[1]=$blogtypeid;

		// refer to $host/snippets/utitlites.php get_include_contents() for more info
		// on the function used here
		// $widget_type=true;
		unset($widget_type);
		$theme_bfpwidget=get_include_contents($path,$initvar,$initval);
		// include "$path";
		// $theme_bfpwidget=isset($output)?$output:"";
	}

	

	// widget: blog related posts (brp)
	// this widget launches a search and produces related post results
	// it is only activated when the prerequisite curblogdata variable
	// carrying retrieved blog post data is available.
	// the main indexes this widget is concerned with are the
	// 'blogcatid','tags','entrydate', 'id'
	$theme_brpwidget=""; 
	if(isset($curblogdata)){
		// unset the b_options variable and reassign it suitable values to funtion with
		// after which, include the blog parser module for the site
		$catid=$curblogdata['blogcatid'];
		$tags=$curblogdata['tags'];
		$id=$curblogdata['id'];
		if(isset($b_options)){
			$b_options['queryextra']=NULL;
			$b_options['limit']=NULL;
			$b_options['queryorder']=NULL;
			$b_options['type']=NULL;
			$b_options['showtoppages']=NULL;
			$b_options['showbottompages']=NULL;
		}
		// var_dump($curblogdata);
		$b_options['queryextra']=" blogcatid='$catid' AND tags LIKE '%$tags%' 
		AND id<$id";
		$b_options['limit']=!isset($b_options['brplimit'])?"LIMIT 0,15":
		"LIMIT 0,".$b_options['brplimit'];
		$b_options['queryorder']=" ORDER by date DESC";
		$b_options['showtoppages']=!isset($b_options['brpshowtoppages'])?false:
		$b_options['brpshowtoppages'];
		$b_options['showbottompages']=false;
		$b_options['defaulttext']=isset($b_options['brpdefaulttext'])
		?$b_options['brpdefaulttext']:"";
		$b_options['defaulttag']=isset($b_options['brpdefaulttag'])
		?$b_options['brpdefaulttag']:"";
		$b_options['defaultclass']=isset($b_options['brpdefaultclass'])
		?$b_options['brpdefaultclass']:"";
		// echo $b_options['showbottompages']." bottom pages";

		// $b_options['queryorder']="ORDER BY views DESC";
		$path=$host_tpathplain.'modules/blogpagemultipleparser.php';
		$b_options['type']=!isset($b_options['brptype'])?'maincontent2':
		$b_options['brptype'];

		$initvar[0]="b_options";
		$initvar[1]="blogtypeid";
		$initval[0]=$b_options;
		$initval[1]=$blogtypeid;

		// refer to $host/snippets/utitlites.php get_include_contents() for more info
		// on the function used here
		// include($path);
		$output="";
		/*if(function_exists('theme_Widget_Admin')){
			$opts['type_blog']['blogdata']=$curblogdata;
			$opts['blogtypeid']=$blogtypeid;
			$output=theme_Widget_Admin($b_options,$opts);
		}
		$theme_brpwidget=isset($output)?$output:"";*/
		// $widget_type=true;
		unset($widget_type);
		$theme_brpwidget=get_include_contents($path,$initvar,$initval);
	}
	
?>

<?php

	// widget: blog most commented posts (bmcp)
	// this widget launches a search and produces popular post results
	// unset the b_options variable and reassign it suitable values to funtion with
	// after which, include the blog parser module for the site
	if(isset($widget_options['theme_bmcpwidget'])&&
		$widget_options['theme_bmcpwidget']==true){

		if(isset($b_options)){
			$b_options['queryextra']=NULL;
			$b_options['limit']=NULL;
			$b_options['queryorder']=NULL;
			$b_options['type']=NULL;
		}
		$b_options['queryextra']="";

		$b_options['limit']=!isset($b_options['bmcplimit'])?"LIMIT 0,5":
		"LIMIT 0,".$b_options['bmcplimit'];
		
		$b_options['showtoppages']=!isset($b_options['bmcpshowtoppages'])?true:
		$b_options['bmcpshowtoppages'];

		$b_options['showbottompages']=!isset($b_options['bmcpshowbottompages'])?
		true:$b_options['bmcpshowbottompages'];

		$b_options['queryorder']="ORDER BY count DESC";
		// see if the blogtypeid variable is available other wise init it with the defult
		// value of 1
		if(!isset($blogtypeid)){
			$blogtypeid=1;
		}

		// see if the result count variable for this widget is available, if it is, assign
		// it as required
		if(isset($theme_bmcpwidget_count)&&$theme_bmcpwidget_count>0){
			$b_options['outlimit']=$theme_bmcpwidget_count;
		}
		$b_options['queryoverride']="SELECT a.*,COUNT(c.blogentryid) AS count FROM 
		blogentries a, comments c WHERE a.blogtypeid='$blogtypeid' AND a.id=c.blogentryid 
		GROUP BY c.blogentryid ";

		$b_options['type']=!isset($b_options['bmcptype'])?'mainsidebarmini':
		$b_options['bmcptype'];
		
		$path=$host_tpathplain.'modules/blogpagemultipleparser.php';

		$initvar[0]="b_options";
		$initvar[1]="blogtypeid";
		$initval[0]=$b_options;
		$initval[1]=$blogtypeid;

		

		// refer to $host/snippets/utitlites.php get_include_contents() for more info
		// on the function used here
		// $widget_type=true;
		unset($widget_type);
		$theme_bmcpwidget=get_include_contents($path,$initvar,$initval);
		// include($path);
		// $theme_bmcpwidget=isset($output)?$output:"";
		// ob_end_flush();
	}


	// widget: random faqs  (rf)
	// this widget launches a search and produces popular post results
	// unset the b_options variable and reassign it suitable values to funtion with
	// after which, include the blog parser module for the site
	if(isset($widget_options['theme_rfwidget'])&&
		$widget_options['theme_rfwidget']==true){
		// create query for selecting random number of faqs
		$themeq="SELECT * FROM faq WHERE status='active' ORDER BY rand() LIMIT 0,5 ";
		$theme_rfwidget=briefquery($themeq,__LINE__,"mysqli");

	}	

?>