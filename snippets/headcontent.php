<?php
	// the default header, change the variables to what suits you in order to effect the final output of the content
	$mpagedescription=isset($mpagedescription)?$mpagedescription:"Frontiers International Services limited is a world class Commercial Capacity Development and Work-Attitude Upgrade firm,supporting and equipping Businesses and Managers with commercial abilities, attitudes and behaviors required for sustainable superlative growth.Our service quality and diagnostic approach to delivery has sustained  partnerships and growing relationships with major clients in Nigeria and overseas.";
	$mpagekeywords=isset($mpagekeywords)?$mpagekeywords:"Frontiers Consulting, Muyiwa Afolabi, muyiwa afolabi, frontiers consulting, frankly speaking with muyiwa afolabi, frankly speaking, get muyiwa afolabi's audio content,frankly speaking audio,motivational speaker in nigeria, business strategists in the world, reformation packages, Christ Society International Outreach, Project Fix Nigeria, frankly speaking africa, Own Your Own, Nigerian career radio talk show, career coach, career coach in nigeria, career coach in Africa, business and career coach, business coach in Nigeria, Business and career coach, Business coach, life-coach, social reforms, talk show host, on air personality, Career coach in Nigeria, Career coach in Africa, Business coach in Nigeria, Business coach in Africa, Life coach, No 1 career coach in Nigeria, No 1 career coach in Africa, Marketing consultant, Mind set coach, Public figure, Public commentator, Social reformer, Sales and Marketing Trainer, Professional speaker, Professional speaker in Nigeria, Professional speaker in Africa, Radio Talk show Host, Counsellor";
	$mpageimage=isset($mpageimage)?$mpageimage:$host_addr."images/favicon.png";
	$mpagetitle=isset($mpagetitle)?$mpagetitle:"Welcome to Muyiwa Afolabi's website";
	$mpageurl=isset($mpageurl)?$mpageurl:$host_addr;
	$mpagefbappid=isset($mpagefbappid)?$mpagefbappid:"";
	$mpagefbadmins=isset($mpagefbadmins)?$mpagefbadmins:"";
	$mpagesitename=isset($mpagesitename)?$mpagesitename:"Frontiers Consulting";
	$mpagecrumbclass=isset($mpagecrumbclass)?$mpagecrumbclass:"hidden";
	$mpagecrumbtitle=isset($mpagecrumbtitle)?$mpagecrumbtitle:"";
	$mpagecrumbpath=isset($mpagecrumbpath)?$mpagecrumbpath:"";
	$mpageheaderclass=isset($mpageheaderclass)?$mpageheaderclass:"";
	$mpagelinkclass=isset($mpagelinkclass)?$mpagelinkclass:"";
	$mpagelinkclass2=isset($mpagelinkclass2)?$mpagelinkclass2:"";
	$mpagelinkclass3=isset($mpagelinkclass3)?$mpagelinkclass3:"";
	$mpagecolorstylesheet="";
	$mpagebanner=""; // for holding the page banner value if one is present
	$mpageforcescriptasync="async"; //for holding a default async script value
	// google analytics
	$mpagega="
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	      ga('create', 'UA-49730962-1', 'muyiwaafolabi.com');
		  ga('send', 'pageview');
		</script>
	";
	$mpagelibscriptextras='';//for holding extra lib scripts to be imported,loads above page
							 //action handling scripts, in this case muyiwasblog.js
	$mpagescriptextras='';//for holding extra scripts to be imported
	$mpagestylesextras='';//for holding extra styles to be imported
	$mpagelogostyle='';//for adding extra styling to the logo when necessary, using class
					   //names
	$mpagemaps='
		<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
		<script src="'.$host_addr.'scripts/js/maplace.min.js"></script>
	';
	$mpagecontactpanel=""; //stores the contact panel section information
	// generate link for clients to job post, only show when there is a user logged in
	$clientlink='';
	// for holding default flexslider
	$mpageflexout='
		<div class="header-banner">
			<div class="flexslider header-slider">
				<ul class="slides">
					<li>
						<img src="'.$host_addr.'images/transparent.png" alt="">
						<div data-image="'.$host_addr.'images/content/slide1.jpg"></div>
					</li>

					<li>
						<img src="'.$host_addr.'images/transparent.png" alt="">
						<div data-image="'.$host_addr.'images/content/slide4.jpg"></div>
					</li>

					<li>
						<img src="'.$host_addr.'images/transparent.png" alt="">
						<div data-image="'.$host_addr.'images/content/slide7.jpg"></div>
					</li>
				</ul>
			</div
		</div><!-- end .header-banner -->
	';
	// get career and quote content
	$careeradvice=getAllGeneralInfo("viewer","careeradvice","");
	$quoteout=getAllGeneralInfo("viewer","fjcquote","");
	// for holding sidebarcontent that sits at the top of the sidebar widget
	$mpagemidsidebarcontent=isset($mpagemidsidebarcontent)?$mpagemidsidebarcontent:"";
	$mpagetopsidebarcontent=isset($mpagetopsidebarcontent)?$mpagetopsidebarcontent:"";
	$mpagesidebarextras=isset($mpagesidebarextras)?$mpagesidebarextras:"";
	/*Active page variable sections
	*$activepage1=index.php
	*$activepage2=frontiersconsultingtwo.php (headcontentfc.php)
	*$activepage3=blog.php
	*$activepage4=frontiersjobconnect.php (headcontentfjc.php hosted on a secondary domain).
	*$activepage5=franklyspeakingafrica.php
	*$activepage6=owyourowntwo.php (headcontentoyo.php);
	*$activepage7=onlinestore.php
	*$activepage8=completion.php
	*/
	// hold the bread crumb social buttons
	$mpagecrumbsocial='
		<div class="section-title-social">
			<a class="social-pointer fa fa-twitter sociallink" href="http://www.twitter.com/franklyafolabi" target="_blank"></a>
			<a class="social-pointer fa fa-linkedin sociallink" target="_blank" href="http://www.linkedin.com/profile/view?id=37212987"></a>
			<a class="social-pointer fa fa-facebook sociallink" target="_blank" href="http://www.facebook.com/franklyspeakingwithmuyiwaafolabi"></a>
			<a class="social-pointer fa fa-google sociallink" target="_blank" href="http://www.facebook.com/franklyspeakingwithmuyiwaafolabi"></a>
			<a class="social-pointer fa fa-youtube sociallink" target="_blank" href="https://www.youtube.com/channel/UCcWNemsF-FuiWNVhwt9fEzQ"></a>
		</div>
	';

	if(isset($activepage1)){
		$mpagecontactpanel='
			<section id="contact-panel">
				<div class="container">
					<div id="contact-panel_social">
						<a class="icon-twitter-bird" href="index_3.html#"></a>
						<a class="icon-linkedin-rect" href="index_3.html#"></a>
						<a class="icon-facebook-rect" href="index_3.html#"></a>
					</div>

					<p><i class="icon-phone base-text-color"></i>Call: 8 800 625 32 48</p>

					<p><i class="icon-mail base-text-color"></i> <a href="index_3.html">Contact us via email</a></p>
				</div>
			</section>
		';
		$mpagelinkclass3="hidden";
		$mpagemaps="";
		$mpagecrumbclass="";
		$mpagecrumbtitle="Jobs";
		$mpagecrumbpath='
			<section id="headline">
				<div class="container">
					<div class="section-title clearfix">
						<h2 class="crumb-heading fl-l">'.$mpagecrumbtitle.'</h2>

						<ul id="breadcrumbs" class="fl-r">
							<li><a href="'.$host_addr.'">Home</a></li>
							<li>'.$mpagecrumbtitle.'</li>
							<li><a class="icon-twitter-bird sociallink" href="http://www.twitter.com/franklyafolabi"></a></li>
							<li><a class="icon-linkedin-rect sociallink" href="http://www.linkedin.com/profile/view?id=37212987"></a></li>
							<li><a class="icon-facebook-rect sociallink" href="http://www.facebook.com/franklyspeakingwithmuyiwaafolabi"></a></li>
						</ul>
					</div>
				</div>
			</section>
		';
	}
	// current_page_item current-menu-ancestor current-menu-parent current_page_parent current_page_ancestor
	if(isset($activepage2)){
		$mpagedescription="Frontiers International Services limited is a world class Commercial Capacity Development and Work-Attitude Upgrade firm,supporting and equipping Businesses and Managers with commercial abilities, attitudes and behaviors required for sustainable superlative growth. Our service quality and diagnostic approach to delivery has sustained  partnerships and growing relationships with major clients in Nigeria and overseas.";
		$mpagekeywords="Frontiers Consulting, Muyiwa Afolabi, muyiwa afolabi, frontiers consulting, frankly speaking with muyiwa afolabi, frankly speaking, motivational speaker in nigeria, business strategists in the world, reformation packages, Christ Society Internationa Outreach, Project Fix Nigeria, Own Your Own, Nigerian career radio talk show";
		$mpagetitle="Frontiers Consulting | Muyiwa Afolabi's Website";
		$mpageurl=$host_addr."frontiersconsulting.php";
		$mpagecrumbclass="";
		$mpagecrumbtitle="Frontiers Consulting";
		$mpagecrumbpath='
			<section id="headline">
				<div class="container">
					<div class="section-title clearfix">
						<h2 class="crumb-heading fl-l">'.$mpagecrumbtitle.'</h2>
						<ul id="breadcrumbs" class="fl-r">
							<li><a href="'.$host_addr.'">Home</a></li>
							<li>'.$mpagecrumbtitle.'</li>
							<li><a class="icon-twitter-bird sociallink" href="http://www.twitter.com/franklyafolabi"></a></li>

							<li><a class="icon-linkedin-rect sociallink" href="http://www.linkedin.com/profile/view?id=37212987"></a></li>

							<li><a class="icon-facebook-rect sociallink" href="http://www.facebook.com/franklyspeakingwithmuyiwaafolabi"></a></li>
						</ul>
					</div>
				</div>
			</section>
		';
		$mpageflexout='';
		$mpagemaps="";
	}
	if(isset($activepage3)){
		$mpagedescription="Muyiwa Afolabi's Blog. Visit this exciting blog for daily access to Muyiwa's business and career radio and television talk-show content";
		$mpagekeywords=$mpagekeywords;
		$mpagetitle="Frankly Speaking Blog | Frankly Speaking With Muyiwa Afolabi";
		$mpageurl=$host_addr."blog.php";
		$mpagebanner='
			<div id="contenttop">
				<div id="contenttopportraithold" class="">
					<a href="blog.php"><img src="./images/muyiwasblog.jpg" class="total"/></a>
				</div>
				<!-- <div id="contenttopportraithold" style="width:100%;">
						<a href="blog.php"><img src="./images/arpacombo.jpg" name="arpacarousel" style="position:relative;"/></a>
						<script>
							// slideMotion(\'img[name=arpacarousel]\',"bottom",333,2000,20000,0);
						</script>
					</div> -->
			</div>
		';
		$mpagecrumbclass="";
		$mpagecrumbtitle="Frankly Speaking Blog";
		$mpagecrumbpath='
			<section id="headline">
				<div class="container">
					<div class="section-title clearfix">
						<h2 class="crumb-heading fl-l">'.$mpagecrumbtitle.'</h2>

						<ul id="breadcrumbs" class="fl-r">
							<li><a href="'.$host_addr.'">Home</a></li>
							<li>'.$mpagecrumbtitle.'</li>
						</ul>
					</div>
					'.$mpagecrumbsocial.'
				</div>
			</section>';
		$mpageflexout='';
		$mpagemaps="";
		$mpagelogostyle='storelogo';
		$mpagescriptextras='
			
		';
	}
	if(isset($activepage4)){
		$mpagedescription="News on Frontiers Job-Connect, keeping you informed on what you need to know about the Nigerian Job market";
		$mpagekeywords="Best News on latest available jobs, best jobs, kickstart my career, stepping stone jobs in Nigeria";
		$mpagetitle="News | Frontiers Job Connect";
		$mpageurl=$host_addr."fjcblog.php";
		$mpagecrumbclass="";
		$mpagecrumbtitle="News";
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
	}
	if(isset($activepage5)){
		$mpagedescription="Frankly speaking Africa is a social reformation project enacted to transform the mindset and thinking of Africans towards value creation, patriotism and discipline...";
		$mpagekeywords="Frankly speaking Africa, Project Fix Nigeria, Frontiers Consulting, Muyiwa Afolabi, muyiwa afolabi, frontiers consulting, frankly speaking with muyiwa afolabi, frankly speaking, motivational speaker in nigeria, business strategists in the world, reformation packages, Christ Society Internationa Outreach, Project Fix Nigeria, Own Your Own, Nigerian career radio talk show, career coach, career coach in nigeria, career coach in Africa, business and career coach, business coach in Nigeria, Business and career coach, Business coach, live-coach, social reforms, talk show host, on air personality";
		$mpagetitle="Frankly Speaking Africa | Muyiwa Afolabi's Website";
		$mpageurl=$host_addr."franklyspeakingafrica.php";
		$mpagecrumbclass="";
		$mpagecrumbtitle="Frankly Speaking Africa";
		$mpagecrumbpath='
			<section id="headline">
				<div class="container">
					<div class="section-title clearfix">
						<h2 class="crumb-heading fl-l">'.$mpagecrumbtitle.'</h2>
						<ul id="breadcrumbs" class="fl-r">
							<li><a href="'.$host_addr.'">Home</a></li>
							<li>'.$mpagecrumbtitle.'</li>
						</ul>
					</div>
				</div>
				'.$mpagecrumbsocial.'
			</section>
		';
		$mpageflexout='';
		$mpagemaps="";
	}
	if(isset($activepage6)){
		$mpagedescription="Get in touch with us, we are availlable to receive your calls from 8am - 5pm weekdays";
		$mpagekeywords="get in touch, Frontiers Consulting, contact us frontiers job-connect, reach frontiers consulting";
		$mpagetitle="Contact Us | Frontiers Job Connect";
		$mpageurl=$host_addr."contact.php";
		$mpagecrumbclass="";
		$mpagecrumbtitle="Contact Us";
		$mpagecrumbpath='
			<section id="headline">
				<div class="container">
					<div class="section-title clearfix">
						<h2 class="crumb-heading fl-l">'.$mpagecrumbtitle.'</h2>

						<ul id="breadcrumbs" class="fl-r">
							<li><a href="'.$host_addr.'">Home</a></li>
							<li>'.$mpagecrumbtitle.'</li>
						</ul>
					</div>
				</div>
				'.$mpagecrumbsocial.'
			</section>
		';
		$mpageflexout='';
	}
	if(isset($activepage7)){
		$mpagedescription="Welcome to Muyiwa AFOLABI's online store, get instant access to past broadcasts of his popular radio talkshow Frankly Speaking with Muyiwa Afolabi";
		$mpagekeywords=$mpagekeywords;
		$mpagetitle="Online Store | Muyiwa Afolabi's Website";
		$mpageurl=$host_addr."onlinestore.php";
		$mpagecrumbclass="";
		$mpagecrumbtitle="Online Store";
		$mpageforcescriptasync="";//stop jquery loading asynchronously
		$mpagecrumbpath='
			<section id="headline">
				<div class="container">
					<div class="section-title clearfix">
						<h2 class="crumb-heading fl-l">'.$mpagecrumbtitle.'</h2>
						<ul id="breadcrumbs" class="fl-r">
							<li><a href="'.$host_addr.'">Home</a></li>
							<li>'.$mpagecrumbtitle.'</li>
						</ul>
					</div>
				</div>
				'.$mpagecrumbsocial.'
			</section>
		';
		$mpagelibscriptextras='
			<script src="'.$host_addr.'scripts/js/jquery.jplayer.min.js"></script>
			<script src="'.$host_addr.'jssor/jssor.slider.mini.js"></script>
			<script src="'.$host_addr.'scripts/jssorinit.js"></script>
		';
		$mpagelogostyle='storelogo';
		$mpagescriptextras='
			<script>
				initSliderMain("contenttopportraithold","",".contentportraitslides");
				$(document).ready(function(){
				});
			</script>
			<script language="javascript" type="text/javascript" src="'.$host_addr.'scripts/js/tinymce/tinymce.min.js"></script>
			<script language="javascript" type="text/javascript" src="'.$host_addr.'scripts/js/tinymce/basic_config.js"></script>
		';
		$mpagestylesextras='
			<link rel="stylesheet" async href="'.$host_addr.'stylesheets/jssorskins.css"/>
		';
		$mpageflexout='';
		$mpagemaps="";
	}
	if(isset($activepage8)){
		// $mpagedescription="";
		// $mpagekeywords="Login, Login Frontiers Conculting, frontiers job-connect Login, Login FJC";
		$mpagetitle="Completion | Muyiwa Afolabi's Website";
		$mpageurl=$host_addr."completion.php";
		$mpagecrumbclass="";
		$mpagecrumbtitle="Completion";
		$mpagecrumbpath='
			<section id="headline">
				<div class="container">
					<div class="section-title clearfix">
						<h2 class="crumb-heading fl-l">'.$mpagecrumbtitle.'</h2>
						<ul id="breadcrumbs" class="fl-r">
							<li><a href="'.$host_addr.'">Home</a></li>
							<li>'.$mpagecrumbtitle.'</li>
						</ul>
					</div>
				</div>
				'.$mpagecrumbsocial.'
			</section>
		';
		$mpageflexout='';
		$mpagemaps="";
	}
	// for holding default sidebar
	$mpagesidebar='
		
	';
	// for holding my leagacy fullbackground markup
	$mpagelegacyfullbackground='
		<div id="fullbackground"></div>
		<div id="fullcontenthold">
			<div id="fullcontent">
				<span name="specialheader"></span><br>
				<div id="eventhold">
					<div id="eventtitle"></div>
					<div id="eventdetails"></div>
				</div>

				<div id="closecontainer" name="closefullcontenthold" data-id="theid" class="altcloseposfour"><img src="./images/closefirst.png" title="Close"class="total"/></div>
	 			<img src="'.$host_addr.'/images/waiting.gif" name="galleryimgdisplay" title="gallerytitle" />
	 			<img src="'.$host_addr.'/images/waiting.gif" name="fullcontentwait"/>
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
	<meta name="author" content="Muyiwa Afolabi">
    <meta http-equiv="Content-Type" content="text/html;"/>
    <meta property="fb:app_id" content="578838318855511"/>
    <meta property="fb:admins" content="en_US"/>
    <meta property="og:locale" content="en_US">
	<meta property="og:type" content="website"/>
	<meta property="og:image" content="<?php echo $mpageimage;?>"/>
	<meta property="og:title" content="<?php echo $mpagetitle;?>"/>
	<meta property="og:description" content="<?php echo $mpagedescription;?>"/>
	<meta property="og:url" content="<?php echo $mpageurl;?>"/>
	<meta property="og:site_name" content="<?php echo $mpagesitename;?>"/>
	<link rel="canonical" async href="<?php echo $mpageurl;?>"/>
	<!-- <link rel="stylesheet" async href="<?php echo $host_addr;?>bootstrap/css/bootstrap.css"/> -->
	<!-- Bootstrap Core CSS -->
    <link async href="<?php echo $host_addr?>stylesheets/bootstrap.min.css" rel="stylesheet">
	<!-- Light Box -->
	<link rel="stylesheet" async href="<?php echo $host_addr;?>stylesheets/font-awesome/css/font-awesome.min.css"/>
	<link rel="stylesheet" async href="./stylesheets/muyiwamain.css"/>
	<link rel="stylesheet" async href="./stylesheets/responsive.css"/>
	<!-- <link async href="<?php echo $host_addr;?>stylesheets/lightbox.css" rel="stylesheet"/> -->
	<!-- <link rel="stylesheet" href="./stylesheets/jquery.zrssfeed.css"/> -->
	<!-- <link rel="stylesheet" href="<?php echo $host_addr;?>stylesheets/jquery.raty.css"/> -->
	<link rel="icon" async href="<?php echo $host_addr;?>images/muyiwaslogo.png" type="image/png"/>
    <!-- Custom CSS -->
    <!-- <link async href="<?php echo $host_addr?>stylesheets/style.css" rel="stylesheet"> -->
    <?php 
		echo $mpagega;
    ?>
	<script <?php echo $mpageforcescriptasync;?> src="<?php echo $host_addr;?>scripts/jquery.js"></script>
	<?php 
		// include('./snippets/fcthemestylesdump.php');
		echo $mpagestylesextras;
	?>

   
</head>