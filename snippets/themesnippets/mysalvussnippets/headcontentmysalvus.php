<?php
	// make default site information available
	include(''.$host_tpathplain.'defaultsmodule.php');

	// the default header, change the variables to what suits you in order to 
	// effect the final output of the content
	$mpagedescription=isset($mpagedescription)?$mpagedescription:
	"MySalvus is an interactive sexual violence reporting platform where cases of sexual 
	violence can be reported anonymously and survivors can access available 
	support services.";

	$mpagekeywords=isset($mpagekeywords)?$mpagekeywords:"Domestic violence, domestic violence in Nigeria,
	molestation, aid for rape victims in africa, aid for rape victims in Nigeria";
	$mpageimage=isset($mpageimage)?$mpageimage:$host_addr."images/favicon.ico";
	$mpagetitle=isset($mpagetitle)?$mpagetitle:"Welcome | $host_website_name";
	$mpageurl=isset($mpageurl)?$mpageurl:$host_addr;
	$mpagecanonicalurl=isset($mpagecanonicalurl)?$mpagecanonicalurl:$host_addr;
	$mpageogtype=isset($mpageogtype)?$mpageogtype:"website";
	$mpageextrametas=isset($mpageextrametas)?$mpageextrametas:"";
	$mpagefbappid=isset($mpagefbappid)?$mpagefbappid:"";
	$mpagefbadmins=isset($mpagefbadmins)?$mpagefbadmins:"";
	$mpagesitename=isset($mpagesitename)?$mpagesitename:"$host_website_name";
	$mpagecrumbclass=isset($mpagecrumbclass)?$mpagecrumbclass:"hidden";
	$mpagecrumbtitle=isset($mpagecrumbtitle)?$mpagecrumbtitle:"";
	$mpagecrumbpath=isset($mpagecrumbpath)?$mpagecrumbpath:"";
	$mpagecrumbdata=isset($mpagecrumbdata)?$mpagecrumbdata:"";
	$mpageheaderclass=isset($mpageheaderclass)?$mpageheaderclass:"";
	$mpagelinkclass=isset($mpagelinkclass)?$mpagelinkclass:"";
	$mpageblogid=isset($mpageblogid)?$mpageblogid:1;
	$mpagelinkclass2=isset($mpagelinkclass2)?$mpagelinkclass2:"";
	$mpagelinkclass3=isset($mpagelinkclass3)?$mpagelinkclass3:"";
	$mpage_lsb=isset($mpage_lsb)?$mpage_lsb:"true";
	$mpagecolorstylesheet="";
	// google analytics
	$mpagega="
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	      ga('create', 'auto', '$defaultgatrackcode');
		  ga('send', 'pageview');
		</script>
	";
	
	
	// control the load point for the styles on the page
    $mpagestyleload="top";
	$mpagemergedscripts="";
	$mpagemergedstyles="";
	if(!isset($mpagemaps)){
		$mpagemaps='
			<script src="http://maps.google.com/maps/api/js?key=AIzaSyCA7H4Akqm2e2FpcsOVg4L6dppS7bmETJQ"></script>
			<!-- // <script src="'.$host_addr.'scripts/js/maplace.min.js"></script>-->
		';
	}
	// for holding socialnetwork initiation scripts
	$mpagesdksmarkuproot='
		<div id="fb-root"></div>
	';
	$mpagesdks='
		<script type="text/javascript">
		(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=223614291144392";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, \'script\', \'facebook-jssdk\'));</script>
		<!-- For google plus -->
		<script type="text/javascript">
		  (function() {
		    var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true;
		    po.src = \'https://apis.google.com/js/platform.js\';
		    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s);
		  })();
		</script>

		<!-- For twitter -->
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, \'script\', \'twitter-wjs\');</script>

		<!-- For LinkedIn -->
		<script src="//platform.linkedin.com/in.js" type="text/javascript">
		 lang: en_US
		</script>
	';
	// generate link for clients to job post, only show when there is a user logged in
	$clientlink='';
	
	$mpagebannerone='
		<div class="site-content-banner">
	    	<img src="'.$host_addr.'images/fiwl/slide2.jpg"/>
	    </div>
	';
	$mpagebannermobileone='
		
	';
	$mpagevegasone='
		
	';
	
	//for holding extra lib scripts to be imported,loads above page
	//action handling scripts
	$mpagelibscriptextras=isset($mpagelibscriptextras)?$mpagelibscriptextras:'';
	
	// for holding extra lib styles in a per page scenario. also loads in page header
	$mpagelibstyleextras=isset($mpagelibstyleextras)?$mpagelibstyleextras:'';

	//for holding extra scripts to be imported in the themescripts file
	$mpagescriptextras=isset($mpagescriptextras)?$mpagescriptextras:'';
	
	//for holding extra styles to be imported themestylesheets file
	$mpagestylesextras='';
	
	//for adding extra styling to the logo when necessary, using class
	//names
	$mpagelogostyle='';
	// get career and quote content
	// $careeradvice=getAllGeneralInfo("viewer","businessadvice","");
	// $quoteout=getAllGeneralInfo("viewer","oyoquote","");
	$mpageblogcarousel='';
	$mpageblogcarouselrecent='';

	// for carrying home page banner or background images for oage header
	$mpagetopbanner="";
	// for carrying top banners for plain pages
	$mpagetopbannersplain[0]=$host_addr.'images/mysalvusimages/backgrounds/topbanners/mysalvustopbanner1.jpg';
	$mpagetopbannersplain[1]=$host_addr.'images/mysalvusimages/backgrounds/topbanners/mysalvustopbanner2.jpg';
	$mpagetopbannersplain[2]=$host_addr.'images/mysalvusimages/backgrounds/topbanners/mysalvustopbanner3.jpg';
	$mpagetopbannersplain[3]=$host_addr.'images/mysalvusimages/backgrounds/topbanners/mysalvustopbanner4.jpg';
	$mpagetopbanners='
		<div class="inner-banner"><img src="'.$mpagetopbannersplain[rand(0,count($mpagetopbannersplain)-1)].'" alt="img"></div>
	';
	// for holding sidebarcontent that sits at the top of the sidebar widget
	$mpagemidsidebarcontent=isset($mpagemidsidebarcontent)?$mpagemidsidebarcontent:"";
	$mpagetopsidebarcontent=isset($mpagetopsidebarcontent)?$mpagetopsidebarcontent:"";
	$mpagesidebarextras=isset($mpagesidebarextras)?$mpagesidebarextras:"";


	/*Active page variable sections
	*$activepage1=index.php
	*$activepage2=about.php
	*$activepage3=joinus.php
	*$activepage4=ireport.php incident report page
	*$activepage5=resources.php
	*$activepage6=blog.php
	*$activepage7=faq.php
	*
	*/
	$mpagesearchclass="hidden"; // control variable for displaying header search panel
	
	$data=array();
	$hwdata=array();
	$rweldata=array();
	$iweldata=array();
	if(isset($activepage1)){
		if(isset($host_env)&&$host_env=="online"){
			// $mpagemergedscripts="true";
			// $mpagemergedstyles="true";
		}
		// $mpage_lsb="";
		// $mpagestyleload="bottom";
		$mpagelinkclass3="hidden";
		$mpagemaps="";
		$mpagehomeheaderclass="home-page";
		
		$mpagetopbanners="";
		
	}

	if(isset($activepage2)){
		$mpagedescription="Learn more about MySalvus, who we are what we represent";
		$mpagekeywords.="About MySalvus, MySalvus, Who is MySalvus, Domestic and human rights crimes in Nigeria,
		 MySalvus Support, who is MySalvus, what is MySalvus";
		$mpagetitle="About Us | MySalvus";
		$mpageurl=$host_addr."about.php";
		$mpagecrumbclass="_defaultcrumb";
		$mpagecrumbtitle="About";
		
		// var_dump($pweldata);
		// echo $pweldata['numrows'];
		
		$mpagecrumbdata='
            <li><a href="'.$host_addr.'">Home</a></li>
            <li class="active">About Us</li>

		';
		$mpageflexout='';
		$mpagemaps="";
		$mpagelibscriptextras='';
		$mpagescriptextras='
		';	

	}

	if(isset($activepage3)){
		$mpagedescription="Join us, and lets help stop, prevent and aid those subject to 
		sexual harassment and abuse.";
		$mpagekeywords="";
		$mpagetitle="Get Involved | MySalvus";
		$mpageurl==""?$host_addr."joinus.php":$mpageurl;
		$mpagecrumbtitle="Get Involved";
		$mpagecrumbclass="";
		$mpagecrumbpath='
			
		';
		$mpagecrumbdata='
		<li><a href="'.$host_addr.'">Home</a></li>
            <li class="active">'.$mpagecrumbtitle.'</li>';
		$mpageflexout='';
		$mpagemaps="";
		$data[0]="joinuswelcomemsg";
		$data[1]="viewer";
		$iweldata=getSingleGeneralInfo("",$data);
		// $blogoutcarousel=getAllBlogEntries("viewer",'',$mpageblogid,"blogtype");

		// $mpageblogcarousel=$blogoutcarousel['recentpostspecificllcarousel'];
		$mpagescriptextras.='<script src="'.$host_addr.'plugins/bootpag/jquery.bootpag.min.js"></script>';	
		$mpagelibstyleextras.='<link rel="stylesheet" href="'.$host_addr.'plugins/bootpag/css/bootpag.css"/>';
	}

	if(isset($activepage4)){
		$mpagedescription="Get the help you need from sexual harassment and abuse.";
		$mpagekeywords="See how we help and effect positive change to victims of violent sexual harrassment";
		$mpagetitle="Get Help | MySalvus";
		$mpageurl=$host_addr."services.php";
		$data[0]="servicewelcomemsg";
		$data[1]="viewer";
		$sweldata=getSingleGeneralInfoPlain("servicewelcomemsg");
		$data[0]="productservices";
		// $data[1]="viewer";
		$servicesdata=getAllGeneralInfo("viewer",$data,"all");
		$mpagecrumbclass="";
		$mpagecrumbtitle="Get Help";
		$mpagecrumbdata='
		<li><a href="'.$host_addr.'">Home</a></li>
            <li class="active">'.$mpagecrumbtitle.'</li>';
		$mpageflexout='';
		$mpagemaps="";
		

	}
	
	if(isset($activepage5)){
		$mpagedescription="";
		$mpagekeywords.="mysalvus resources, mysalvus, resources, resource content, multimedia resources, mysalvus case studies, mysalvus articles, seminars, articles, videos, case studies";
		$mpagetitle="Resources | MySalvus";
		$data[0]="blogwelcomemsg";
		$data[1]="viewer";
		$rweldata=getSingleGeneralInfo("",$data);
		$mpageurl==""?$host_addr."blog.php":$mpageurl;
		$mpagecrumbclass="";
		$mpagecrumbtitle==""?$mpagecrumbtitle="Blog":$mpagecrumbtitle;
		$mpagecrumbpath='
			<div class="breadcrumb-bar">
				<ul class="breadcrumb">
					<li><a href="'.$host_addr.'">Home</a></li>
					<li class="active">'.$mpagecrumbtitle.'</li>
				</ul>
			</div>
			<div class="default-text-banner">
				<div class="container">
					<h1 class="banner-text">Our Blog</h1>
				</div>
			</div>
		';
		// $mpagescriptextras.='<script src="'.$host_addr.'scripts/themescripts/owl.carousel.min.js"></script>';	
		// $mpagelibstyleextras.='<link rel="stylesheet" href="'.$host_addr.'plugins/bootpag/css/bootpag.css"/>';	
		$mpagemaps="";
		$mpagedescription="Get more information about who we are, and what our team comprises individually";
		$mpagekeywords.="MySalvus team, developers in Nigeria, Business Managers in Nigeria, who is mysalvus, what is mysalvus, what does mysalvus stand for, ";
		$mpagetitle="Meet The Team | MySalvus";
		/*$data[0]="aboutwelcomemsg";
		$data[1]="viewer";
		$aweldata=getSingleGeneralInfoPlain("aboutwelcomemsg");
		$data[0]="directorsection";
		$data[1]="viewer";
		$directordata=getSingleGeneralInfoPlain("directorsection");
		$data[0]="trustees";*/
		// $data[1]="viewer";
		

		/*$data[0]="directorsection";
		$data[1]="viewer";
		$directordata=getSingleGeneralInfo("",$data);*/
		$mpageurl==""?$host_addr."team.php":$mpageurl;
		$mpagecrumbtitle==""?$mpagecrumbtitle="About Us":$mpagecrumbtitle;
		$mpagecrumbclass="";

		$mpagecrumbpath='
			<div class="breadcrumb-bar">
				<ul class="breadcrumb">
					<li><a href="'.$host_addr.'">Home</a></li>
					<li class="active">'.$mpagecrumbtitle.'</li>
				</ul>
			</div>
			<div id="slider" class="top-banner white-right-skew">
				<img alt="img" src="'.$host_addr.'images/mysalvusimages/career-banner.jpg">
				<div class="banner-content banner-career">
					<p class="banner-head">Enamoured with technology</p>
					<p class="banner-text">And Excellence Driven</p>
				</div>
			</div>
		';
		// remove path crumb
		// $mpagecrumbpath="";
		// $mpageflexout='';
		$mpagemaps="";
	}

	if(isset($activepage6)){
		$mpagedescription="Get access to the MySalvus Blog postsgiving you the latest
		in handling yourself in harassment situations and other useful info.";
		$mpagekeywords.="mysalvus blog, news on sexual violence, ";
		$mpagetitle="Blog | MySalvus";
		
		$mpageurl==""?$host_addr."blog.php":$mpageurl;
		$mpagecrumbclass="";
		$mpagecrumbtitle==""?$mpagecrumbtitle="Blog":$mpagecrumbtitle;
		$mpagecrumbdata='
					<li><a href="'.$host_addr.'">Home</a></li>
					<li class="active">'.$mpagecrumbtitle.'</li>
		';
	}

	if(isset($activepage7)){
		$mpagedescription="Victim of sexual Violence? let us help you, go through our
		FAQ page and learn how or have your questions answered";
		$mpagekeywords.="Frequently asked questions on harrassment and sexual violence, 
		contact, MySalvus faq information, mysalvus FAQ details, 
		MySalvus FAQ details, FAQs at MySalvus, Help for sexual harassment  or violence";
		$mpagetitle="FAQs | MySalvus";
		$mpageurl=$host_addr."faq.php";
		$mpagecrumbclass="";
		$mpagecrumbtitle="Frequently Asked Questions";

		// $cweldata=getSingleGeneralInfo("",$data);
		$mpagecrumbdata='
			<li><a href="'.$host_addr.'">Home</a></li>
			<li class="active">'.$mpagecrumbtitle.'</li>
		';
		$mpageflexout='';
		// $mpagemaps="";
	}
	// final crumbpath data
	if($mpagecrumbtitle!==""||$mpagecrumbdata!==""){
		$mpagecrumbpath='
		
			<div class="row subheader2 '.$mpagecrumbclass.' ">
				<div class="container">
			        <div class="col-lg-6 col-md-6 col-sm-6">
			            <div class="custom-page-header">
			            	<h1>'.$mpagecrumbtitle.'</h1>
			            </div>
			        </div>
			        <div class="col-lg-6 col-md-6 col-sm-6">
			            <ol class="breadcrumb pull-right">
			                '.$mpagecrumbdata.'
			            </ol>
					</div>
				</div>
		    </div>
		';
		
	}
	// get some specific popular posts
	// $mpageblogoutone=getAllBlogEntries("viewer","",$mpageblogid,"blogtype");

	// create the blog carousel output
	$mpageblogcarouselout='';
	$mpageblogoutone=array();
	if ($mpageblogcarousel!==""&&$mpageblogoutone['numrows']>2) {
		# code...
		$mpageblogcarouselout='
			<!-- CAROUSEL START -->
			<div class="blog-carousel-area">
				<div class="blog-carousel">
					'.$mpageblogcarousel.'
				</div>
			</div>
		';
		
	}else{
		$mpageblogcarouselout=$mpagevegasone;
	}
	// for holding default sidebar
	$mpagesidebar='';

	
	// for footer widget content
	// for holding footer widget manipulation, combination of values for this determines 
	// what other content the foooter has
	$mpagefooterstylemarker=0; 
	$mpagefootermorelinks='
		<!-- WIDGET START -->
			<div class="col-sm-2 col-xs-12">
				<div class="sidebar-right-wrap widget-box widget_tags">
					<div class="widget-title">
						<h4>Links</h4>
					</div>
					<div class="widget-content">
						<ul class="bottom-links-widget">
							<li><a href="'.$host_addr.'blog.php">Blog</a></li>
							<li><a href="'.$host_addr.'profile.php">Profile</a></li>
						</ul>
					</div>
				</div>
			</div>
		<!-- WIDGET END -->
	';
	
	$mpagedosinglescriptload="off";
	$theme_data['renderpath']=$host_tpathplain."themesnippets/mysalvussnippets";
	// block google analytics 
	// if not on live server o no tracking code available or 
	// no 
	if((isset($defaultgatrackcode)&&$defaultgatrackcode=="")
		||($host_env=="online"&&isset($host_test))){
		$mpagega="";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $mpagetitle;?></title>
	<meta http-equiv="Content-Language" content="en-us">
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta http-equiv="Content-Type" content="text/html;"/>
	<meta name="keywords" content="<?php echo $mpagekeywords;?>"/>
	<meta name="description" content="<?php echo $mpagedescription;?>">
	<meta property="og:locale" content="en_US"/>
	<meta property="fb:app_id" content="<?php echo $mpagefbappid;?>"/>
	<meta property="fb:admins" content="<?php echo $mpagefbadmins;?>"/>
	<meta property="og:type" content="<?php echo $mpageogtype;?>"/>
	<meta property="og:image" content="<?php echo $mpageimage;?>"/>
	<meta property="og:title" content="<?php echo $mpagetitle;?>"/>
	<meta property="og:description" content="<?php echo $mpagedescription;?>"/>
	<meta property="og:url" content="<?php echo $mpageurl;?>"/>
	<meta property="og:site_name" content="<?php echo $mpagesitename;?>"/>
	<?php echo $mpageextrametas;?>
	<?php echo $host_favicon;?>
	<link rel="canonical" href="<?php echo $mpagecanonicalurl;?>"/>
	
	<?php 
		if((isset($mpagestyleload)&&$mpagestyleload=="top")||!isset($mpagestyleload)||$mpagestyleload==""){	
			// echo "<link rel='Test here'/>"; 
			echo $mpagelibstyleextras;
			
			include(''.$host_tpathplain.'themesnippets/mysalvussnippets/themestylesdumpmysalvus.php');
		} 
	?>

	<?php
		if((isset($mpagemergedscripts)&&$mpagemergedscripts=="")||!isset($mpagemergedscripts)){
			if(!isset($mpage_lsb)||(isset($mpage_lsb)&&$mpage_lsb=="")){


	?>
				<script src="<?php echo $host_addr;?>scripts/lib/jquery.js"></script>
				<script src="<?php echo $host_addr;?>bootstrap/js/bootstrap.js"></script>
				<script src="<?php echo $host_addr;?>scripts/lightbox.js"></script>
				<script src="<?php echo $host_addr;?>scripts/formchecker.js"></script>
				<script src="<?php echo $host_addr;?>plugins/wow/js/wow.js"></script>
				<script src="<?php echo $host_addr;?>scripts/lib/jquery.lazyload.min.js"></script>
				<script src="<?php echo $host_addr;?>scripts/mylib.js"></script>
				<?php echo $mpagelibscriptextras;?>
	<?php 
			}
		
		}
	?>
	<?php echo $mpagega;?>
</head>