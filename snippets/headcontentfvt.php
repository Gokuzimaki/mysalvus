<?php
	// the default header, change the variables to what suits you in order to effect the final output of the content
	$mpagedescription=isset($mpagedescription)?$mpagedescription:" FVT provides a platform for people who will engage with the leadership of the political class and other stakeholder groups, to push for a change of direction. The glorious future we dream for our nation can only be sustained on a firm foundation of empowering values and citizen engagement.";
	$mpagekeywords=isset($mpagekeywords)?$mpagekeywords:"fvt, foundation for value creation, fvt africa, transforming africa, helping others to grow, charity, donations, how to help africans be better, foundation for value creation, fvt, stakeholders, African citizenry transformation, aiding africa, changing africa, how to change africa, helping africans realize their potential, eliminating limiting african values, how to eliminate limiting african cultures";
	$mpageimage=isset($mpageimage)?$mpageimage:$host_logo_image;
	$mpagetitle=isset($mpagetitle)?$mpagetitle:"Welcome | FVT Africa";
	$mpageurl=isset($mpageurl)?$mpageurl:$host_addr;
	$mpageblogid=isset($mpageblogid)?$mpageblogid:1;
	$mpagefbappid=isset($mpagefbappid)?$mpagefbappid:"";
	$mpagefbadmins=isset($mpagefbadmins)?$mpagefbadmins:"";
	$mpagesitename=isset($mpagesitename)?$mpagesitename:"FVT Africa Official Website";
	$mpagecrumbclass=isset($mpagecrumbclass)?$mpagecrumbclass:"hidden";
	$mpagecrumbtitle=isset($mpagecrumbtitle)?$mpagecrumbtitle:"";
	$mpagecrumbpath=isset($mpagecrumbpath)?$mpagecrumbpath:"";
	$mpageheaderclass=isset($mpageheaderclass)?$mpageheaderclass:"";
	$mpagelinkclass=isset($mpagelinkclass)?$mpagelinkclass:"";
	$mpagelinkclass2=isset($mpagelinkclass2)?$mpagelinkclass2:"";
	$mpagelinkclass3=isset($mpagelinkclass3)?$mpagelinkclass3:"";
	/*      <a class="twitter-timeline"  href="https://twitter.com/franklyafolabi" data-widget-id="653559116712046592">Tweets by @franklyafolabi</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
          */
    
    // google analytics
    $mpagega="";
	/*$mpagega="
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-78709673-1', 'auto');
		  ga('send', 'pageview');

		</script>
	";*/
	// set default play duration in seconds for any blog video or audio in a preview state
	$host_blog_play_limit=180;//default is 3mins i.e 60 * 3
	// visitors Now
	$mpagefeedjitsidebar='
		<!-- WIDGET START -->
			<div class="sidebar-right-wrap widget-box widget_vintauge_social_media_widget">
				<div class="widget-title">
					<h4>Visitors Now</h4>
				</div>
				<div class="widget-content">
					<script type="text/javascript" src="http://feedjit.com/serve/?vv=1515&amp;tft=3&amp;dd=0&amp;wid=&amp;pid=0&amp;proid=0&amp;bc=FFFFFF&amp;tc=000000&amp;brd1=FFFFFF&amp;lnk=E62E78&amp;hc=FFFFFF&amp;hfc=858502&amp;btn=C99700&amp;ww=340&amp;wne=10&amp;srefs=1"></script><noscript><a href="http://feedjit.com/">Live Traffic Stats</a></noscript>
				</div>
			</div>
			<!-- WIDGET END -->
	';
	// control the load point for the styles on the page
    $mpagestyleload="top";
	$mpagemergedscripts="";
	$mpagemergedstyles="";
	if(!isset($mpagemaps)){
		$mpagemaps='
			<script src="http://maps.google.com/maps/api/js?key=AIzaSyCA7H4Akqm2e2FpcsOVg4L6dppS7bmETJQ"></script>
			<script src="'.$host_addr.'scripts/js/maplace.min.js"></script>
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
	$mpagetopbannersplain[0]=$host_addr.'images/fvtimages/backgrounds/topbanners/fvttopbanner1.jpg';
	$mpagetopbannersplain[1]=$host_addr.'images/fvtimages/backgrounds/topbanners/fvttopbanner2.jpg';
	$mpagetopbannersplain[2]=$host_addr.'images/fvtimages/backgrounds/topbanners/fvttopbanner3.jpg';
	$mpagetopbannersplain[3]=$host_addr.'images/fvtimages/backgrounds/topbanners/fvttopbanner4.jpg';
	$mpagetopbanners='
		<div class="inner-banner"><img src="'.$mpagetopbannersplain[rand(0,count($mpagetopbannersplain)-1)].'" alt="img"></div>
	';
	// for holding sidebarcontent that sits at the top of the sidebar widget
	$mpagemidsidebarcontent=isset($mpagemidsidebarcontent)?$mpagemidsidebarcontent:"";
	$mpagetopsidebarcontent=isset($mpagetopsidebarcontent)?$mpagetopsidebarcontent:"";
	$mpagesidebarextras=isset($mpagesidebarextras)?$mpagesidebarextras:"";

	// get default social account info
	include('defaultsmodule.php');
	/*Active page variable sections
	*$activepage1=index.php
	*$activepage2=resources.php
	*$activepage3=infodesk.php
	*$activepage4=services.php
	*$activepage5=about.php
	*$activepage6=portfolio.php
	*$activepage7=contact.php
	*/
	$mpagesearchclass="hidden"; // control variable for displaying header search panel
	// handle sidebar content
	include('pagesidebarshandler.php');
	$data=array();
	$hwdata=array();
	$rweldata=array();
	$iweldata=array();
	if(isset($activepage1)){
		$mpagemergedscripts="true";
		$mpagemergedstyles="true";
		// $mpagestyleload="bottom";
		$mpagelinkclass3="hidden";
		$mpagemaps="";
		$mpagehomeheaderclass="home-page";
		// check to see if there are any slider content for the home page
		$outbanners=array();
		$outbanners=getAllHomeBanners("viewer",'','','','true');
		if($outbanners['numrows']>0){
			$mpagetopbanner='
				<div id="banner2">
			        <ul id="home-banner-2" class="">
			        	'.$outbanners['home_bxslider'].'
			        </ul>
			        <div id="bx-pager">
			            '.$outbanners['home_bxthumbslider'].'
			        </div>
			    </div>
			';
		}else{
			$mpagetopbanner='
				<div id="banner">
			        <ul id="home-banner-2">
			            <li>
			                <img src="'.$host_addr.'images/fvtimages/slides/slide1.jpg" alt="img"/>
			                <div class="caption">
			                    <div class="holder">
			                        <h1>
			                            We are all asking <span class="color-green">Questions</span>
			                            about our dear <span class="color-green">Country</span>
			                            
			                        </h1>

			                        <strong class="title">
			                            such as
			                            "<span>What is really happening in <span class="color-green">Nigeria</span>,
			                             will we keep watching her become a failed state?</span>"
			                        </strong>
			                    </div>
			                </div>
			            </li>
			            <li>
			                <img src="'.$host_addr.'images/fvtimages/slides/slide2.jpg" alt="img"/>
			                <div class="caption">
			                    <div class="holder">
			                        <h1>
			                            We at <span>FVT</span> believe a difference 
			                            can be made if we <span class="color-green">Try</span> 
			                            
			                        </h1>
			                        <strong class="title">This led us to ask ourselves "How do we make this change?"</strong>
			                    </div>
			                </div>
			            </li>
			            <li>
			                <img src="'.$host_addr.'images/fvtimages/slides/slide3.jpg" alt="img"/>
			                <div class="caption">
			                    <div class="holder">
			                        <h1>
			                            Our answer to <span class="color-green">HOW?</span> was found in changing our shared values
			                        </h1>
			                        <strong class="title">and now we see 
			                            <span class="color-green">Hope</span> 
			                            and a <span class="color-green">Bright</span> future ahead</strong>
			                    </div>
			                </div>
			            </li>
			            <li>
			                <img src="'.$host_addr.'images/fvtimages/slides/slide4.jpg" alt="img"/>
			                <div class="caption">
			                    <div class="holder">
			                        <h1>
			                            Join us at <span>FVT</span>
			                            and lets <span>redefine</span> our shared <span class="color-green">values<span>
			                        </h1>
			                        <strong class="title">Together we can make the difference</strong>
			                    </div>
			                </div>
			            </li>
			        </ul>
			        <div id="bx-pager">
			            <a data-slide-index="0" href="##" class="rollIn animated slide-thumb-holder">
			                <img src="'.$host_addr.'images/fvtimages/slidethumb/slide1.jpg" class="lazy" alt="img"/>
			            </a>
			            <a data-slide-index="1" href="##" class="rollIn animated slide-thumb-holder">
			                <img src="'.$host_addr.'images/fvtimages/slidethumb/slide2.jpg" class="lazy" alt="img"/>
			            </a>
			            <a data-slide-index="2" href="##" class="rollIn animated slide-thumb-holder">
			                <img src="'.$host_addr.'images/fvtimages/slidethumb/slide3.jpg" class="lazy" alt="img"/>
			            </a>
			            <a data-slide-index="3" href="##" class="rollIn animated slide-thumb-holder">
			                <img src="'.$host_addr.'images/fvtimages/slidethumb/slide4.jpg" class="lazy" alt="img"/>
			            </a>
			        </div>
			    </div>
			';
			
		}
		$mpagetopbanners="";
		// prepdata variables for the home page
		// get the home page welcome message
		$data[0]="homewelcomemsg";
		$data[1]="viewer";
		$hwdata=getSingleGeneralInfo("",$data);
		// var_dump($hwdata);
		// destroy data array when done with it
		// unset($data);

		// get top and bottom collage boxes

		$vtype[0]="hometopcollagebox";
		$vtype['order']="order by id asc";
		// $vtype[2]="rundouble";
		$tcbdata=getAllGeneralInfo("viewer",$vtype,'');
		$vtype[0]="homebottomcollagebox";
		// $vtype[2]="rundouble";
		$bcbdata=getAllGeneralInfo("viewer",$vtype,'');
		// var_dump($tcbdata);
		$tcbintrodata=getAllGeneralInfo("viewer","hometopcollageboxintro",'');
		// var_dump($tcbintrodata);
		$bcbintrodata=getAllGeneralInfo("viewer","homebottomcollageboxintro",'');

	}
	// current_page_item current-menu-ancestor current-menu-parent current_page_parent current_page_ancestor
	if(isset($activepage2)){
		$mpagedescription="get access to the past and present information about FVT also presented in multimedia format, from our articles to case studies";
		$mpagekeywords.="fvt resources, fvt, resources, resource content, multimedia resources, fvt case studies, fvt articles, seminars, articles, videos, case studies";
		// value test done for header variables due to varying content displayed on the same
		// page e.g page for resourse also has subsections for articles , videos e.t.c
		$mpagetitle==""?$mpagetitle="Resources | FVT Africa":$mpagetitle;
		$data[0]="resourcewelcomemsg";
		$data[1]="viewer";
		$rweldata=getSingleGeneralInfo("",$data);
		$mpageurl==""?$host_addr."resources.php":$mpageurl;
		$mpagecrumbclass="";
		$mpagecrumbtitle==""?$mpagecrumbtitle="Resources":$mpagecrumbtitle;
		$mpagecrumbpath='
			<div class="header-page-title">
				<div class="container">
					<h1>Resources</h1>
					<ul class="breadcrumbs">
						<li><a href="'.$host_addr.'">Home</a></li>
						<li><span class="current">'.$mpagecrumbtitle.'</span></li>
					</ul>
				</div>
			</div>
		';
		$mpagescriptextras.='<script src="'.$host_addr.'plugins/bootpag/jquery.bootpag.min.js"></script>';	
		$mpagelibstyleextras.='<link rel="stylesheet" href="'.$host_addr.'plugins/bootpag/css/bootpag.css"/>';	
		$mpagemaps="";

	}
	if(isset($activepage3)){
		$mpagedescription=" Learn how you can aid FVT in achieving its noble goals amongst other useful information";
		$mpagekeywords="";
		$mpagetitle==""?$mpagetitle="Info Desk | FVT Africa":$mpagetitle;
		$mpageurl==""?$host_addr."infodesk.php":$mpageurl;
		$mpagecrumbtitle==""?$mpagecrumbtitle="Info Desk":$mpagecrumbtitle;
		$mpagecrumbclass="";
		$mpagecrumbpath='
			<div class="header-page-title">
				<div class="container">
					<h1>'.$mpagecrumbtitle.'</h1>

					<ul class="breadcrumbs">
						<li><a href="'.$host_addr.'">Home</a></li>
						<li><span class="current">'.$mpagecrumbtitle.'</span></li>
					</ul>
				</div>
			</div>';
		$mpageflexout='';
		$mpagemaps="";
		$data[0]="infodeskwelcomemsg";
		$data[1]="viewer";
		$iweldata=getSingleGeneralInfo("",$data);
		// $blogoutcarousel=getAllBlogEntries("viewer",'',$mpageblogid,"blogtype");

		// $mpageblogcarousel=$blogoutcarousel['recentpostspecificllcarousel'];
		$mpagescriptextras.='<script src="'.$host_addr.'plugins/bootpag/jquery.bootpag.min.js"></script>';	
		$mpagelibstyleextras.='<link rel="stylesheet" href="'.$host_addr.'plugins/bootpag/css/bootpag.css"/>';
	}
	if(isset($activepage4)){
		$mpagedescription="Our Services";
		$mpagekeywords="See how we help and effect positive change to the society at large via the services we offer";
		$mpagetitle="Our Services | FVT Africa";
		$mpageurl=$host_addr."services.php";
		$data[0]="aboutwelcomemsg";
		$data[1]="viewer";
		$sweldata=getSingleGeneralInfoPlain("servicewelcomemsg");
		$data[0]="productservices";
		// $data[1]="viewer";
		$servicesdata=getAllGeneralInfo("viewer",$data,"all");
		$mpagecrumbclass="";
		$mpagecrumbtitle="Our Services";
		$mpagecrumbpath='
			<div class="header-page-title">
				<div class="container">
					<h1>'.$mpagecrumbtitle.'</h1>

					<ul class="breadcrumbs">
						<li><a href="'.$host_addr.'">Home</a></li>
						<li><span class="current">'.$mpagecrumbtitle.'</span></li>
					</ul>
				</div>
			</div>
		';
		$mpageflexout='';
		$mpagemaps="";
		$mpagescriptextras.='
		<script src="'.$host_addr.'plugins/timeliner/js/timeliner.js"></script>
		<script>
			$(document).ready(function() {
                $.timeliner({});
        	});
		</script>

		';	
		$mpagelibstyleextras='
			<link rel="stylesheet" href="'.$host_addr.'plugins/timeliner/css/timeliner.css"/>
			<link rel="stylesheet" href="'.$host_addr.'plugins/timeliner/css/responsive.css"/>
		';	
	}
	
	if(isset($activepage5)){
		$mpagedescription="Get more information about who we are, what we do and what we stand for";
		$mpagekeywords.="about FVT, about Foundation for value transformation, about us, who is FVT, what is FVT, what does FVT stand for";
		$mpagetitle==""?$mpagetitle="About Us | FVT":$mpagetitle;
		$data[0]="aboutwelcomemsg";
		$data[1]="viewer";
		$aweldata=getSingleGeneralInfoPlain("aboutwelcomemsg");
		$data[0]="directorsection";
		$data[1]="viewer";
		$directordata=getSingleGeneralInfoPlain("directorsection");
		$data[0]="trustees";
		// $data[1]="viewer";
		$trusteesdata=getAllGeneralInfo("viewer",$data,"all");
		$visiondata=getSingleGeneralInfoPlain("fvtvision");
		$missiondata=getSingleGeneralInfoPlain("fvtmission");
		$valuesdata=getSingleGeneralInfoPlain("fvtvalues");
		$objectivesdata=getSingleGeneralInfoPlain("fvtobjectives");

		/*$data[0]="directorsection";
		$data[1]="viewer";
		$directordata=getSingleGeneralInfo("",$data);*/
		$mpageurl==""?$host_addr."about.php":$mpageurl;
		$mpagecrumbtitle==""?$mpagecrumbtitle="About Us":$mpagecrumbtitle;
		$mpagecrumbclass="";
		$mpagecrumbpath='
			<div class="header-page-title">
				<div class="container">
					<h1>'.$mpagecrumbtitle.'</h1>

					<ul class="breadcrumbs">
						<li><a href="'.$host_addr.'">Home</a></li>
						<li><span class="current">'.$mpagecrumbtitle.'</span></li>
					</ul>
				</div>
			</div>
		';
		// remove path crumb
		// $mpagecrumbpath="";
		// $mpageflexout='';
		$mpagemaps="";
	}
	if(isset($activepage6)){
		$mpagedescription="View all our past achievements and impact to our society";
		$mpagekeywords.="FVT gallery portfolio, gallery, gallery Portfolio, our portfolio, our gallery, portfolio of africas top influencers, africa transformation companies portfolio";
		$mpagetitle="Gallery Portfolio | FVT Africa";
		$mpageurl=$host_addr."portfolio.php";
		$mpagecrumbclass="";
		$mpagecrumbtitle="Gallery Portfolio";
		$data[0]="portfoliogallerywelcomemsg";
		$data[1]="viewer";
		$pweldata=getSingleGeneralInfo("",$data);
		// var_dump($pweldata);
		// echo $pweldata['numrows'];

		$mpagecrumbpath='
			<div class="header-page-title">
				<div class="container">
					<h1>'.$mpagecrumbtitle.'</h1>

					<ul class="breadcrumbs">
						<li><a href="'.$host_addr.'">Home</a></li>
						<li><span class="current">'.$mpagecrumbtitle.'</span></li>
					</ul>
				</div>
			</div>
		';
		$mpageflexout='';
		$mpagemaps="";
		$mpagelibscriptextras='<script src="'.$host_addr.'scripts/fvtscripts/jquery.isotope.min.js"></script>';
		$mpagescriptextras='
			<script src="'.$host_addr.'scripts/fvtscripts/fvtisotope.js"></script>
			<script>
				$(document).ready(function(){
					doIsotopeGalleryHover(".cp-gallery","figure.item",4000,7);
					
				})
			</script>
		';	
	}
	if(isset($activepage7)){
		$mpagedescription="We are completely reachable at fvt, simply view all our contact details and get in touch whenever and how ever you want.";
		$mpagekeywords.="Contact us, contact, Foundation for value transformation contact information, fvt contact details, fvtafrica contact details, contacts at fvt";
		$mpagetitle="Contact Us | FVT Africa";
		$mpageurl=$host_addr."contact.php";
		$mpagecrumbclass="";
		$mpagecrumbtitle="Contact Us";
		$data[0]="contactwelcomemsg";
		$data[1]="viewer";
		$cweldata=getSingleGeneralInfo("",$data);
		$mpagecrumbpath='
			<div class="header-page-title">
				<div class="container">
					<h1>'.$mpagecrumbtitle.'</h1>

					<ul class="breadcrumbs">
						<li><a href="'.$host_addr.'">Home</a></li>
						<li><span class="current">'.$mpagecrumbtitle.'</span></li>
					</ul>
				</div>
			</div>
		';
		$mpageflexout='';
		// $mpagemaps="";
	}
	// get some specific popular posts
	$mpageblogoutone=getAllBlogEntries("viewer","",$mpageblogid,"blogtype");
	// create the blog carousel output
	$mpageblogcarouselout='';
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

	// for holding my leagacy fullbackground markup
	$mpagelegacyfullbackground='
		<div id="fullbackground"></div>
		<div id="fullcontenthold">
			<div id="fullcontent">
				<span name="specialheader">28-09-2014 Events</span><br>
				<div id="eventhold">
					<div id="eventtitle">The  Event title</div>
					<div id="eventdetails">The full event details</div>
				</div>

				<div id="closecontainer" name="closefullcontenthold" data-id="theid" class="altcloseposfour"><img src="'.$host_addr.'images/closefirst.png" title="Close"class="total"/></div>
	 			<img src="" name="galleryimgdisplay" title="gallerytitle" />
	 			<img src="'.$host_addr.'images/waiting.gif" name="fullcontentwait"/>
			</div>
			<div id="fullcontentheader">
				<input type="hidden" name="gallerycount" value="0"/>
				<input type="hidden" name="currentgalleryview" value="0"/>			
			</div>
			<div id="fullcontentdetails">
			</div>

			<div id="fullcontentpointerhold">
				<!--<div id="fullcontentpointerholdholder">
					<div id="fullcontentpointerleft">
						<img src="'.$host_addr.'images/pointerleft.png" name="pointleft" id="" data-pointer="" class="total"/>
					</div>
					<div id="fullcontentpointerright">
						<img src="'.$host_addr.'images/pointerright.png" name="pointright" id="" data-pointer="" class="total"/>
					</div>
				</div>-->
			</div>
		</div>
	';
	// for holding the default close content
	$mpagehidelegacyfullbackground='
		<div id="closecontainer" name="closefullcontenthold" data-id="theid" class="altcloseposfour"><img src="'.$host_addr.'images/closefirst.png" title="Close"class="total"/></div>
	';
	// for footer widget content
	$mpagefooterstylemarker=0; // for holding footer widget manipulation, combination of values for this determines what other content the foooter has
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
	$mpagefooterlatestposts='
		<!-- WIDGET START -->
		<div class="col-sm-4 col-xs-12">
			<div class="sidebar-right-wrap widget-box widget_jupios_latest_latest_posts_widget">
				<div class="widget-title">
					<h4>Latest Posts</h4>
				</div>
				<div class="widget-content">
					<ul class="latest-posts-widget">
						'.$mpageblogoutone['recentpostspecificll'].'
					</ul>
				</div>
			</div>
		</div>
		<!-- WIDGET END -->
	';
	$mpagedosinglescriptload="off";


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
	<meta property="fb:app_id" content=""/>
	<meta property="fb:admins" content=""/>
	<meta property="og:type" content="website"/>
	<meta property="og:image" content="<?php echo $mpageimage;?>"/>
	<meta property="og:title" content="<?php echo $mpagetitle;?>"/>
	<meta property="og:description" content="<?php echo $mpagedescription;?>"/>
	<meta property="og:url" content="<?php echo $mpageurl;?>"/>
	<meta property="og:site_name" content="<?php echo $mpagesitename;?>"/>
	<?php echo $host_favicon;?>
	<link rel="canonical" href="<?php echo $mpageurl;?>"/>
	
	<?php 
		if((isset($mpagestyleload)&&$mpagestyleload=="top")||!isset($mpagestyleload)||$mpagestyleload==""){	
			// echo "<link rel='Test here'/>"; 
			echo $mpagelibstyleextras;
			include('./snippets/themestylesdumpfvt.php');
		} 
	?>

	<?php
		if((isset($mpagemergedscripts)&&$mpagemergedscripts=="")||!isset($mpagemergedscripts)){

	?>
		<script src="<?php echo $host_addr;?>scripts/jquery.js"></script>
		<script src="<?php echo $host_addr;?>bootstrap/js/bootstrap.js"></script>
		<script src="<?php echo $host_addr;?>scripts/lightbox.js"></script>
		<script src="<?php echo $host_addr;?>scripts/formchecker.js"></script>
		<script src="<?php echo $host_addr;?>scripts/jquery.lazyload.min.js"></script>
		<script src="<?php echo $host_addr;?>scripts/mylib.js"></script>
		<script src="<?php echo $host_addr;?>scripts/fvtscripts/html5.js"></script>
		<?php echo $mpagelibscriptextras;?>
	<?php 
		
		}
	?>
	<?php echo $mpagega;?>
</head>