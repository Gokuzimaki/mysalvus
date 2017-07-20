<?php 
	// This snippets carries the basic theme setup default variables that span accross
	// multiple theme based web apps.
	// the default header, change the variables to what suits you in order to effect the final output of the content
	
	// Global config variable for the session variable prefix for the current project
	!isset($host_sessionvar_prefix)?$host_sessionvar_prefix="ct_":$host_sessionvar_prefix;

	/*Page meta data and Open graph content manipulation
	*$magedescription; this sorts out the default pagecontent for the description meta tag
	*$magekeywords; for the possible keywords to locate the current page
	*$mageimage; path tothe icon for the website
	*$mageurl; path to the current page
	*$mageimage; path to the icon for the website
	*/
	$mpagedescription=isset($mpagedescription)?$mpagedescription:"Welcome to Mysalvus, The aim of this platform is to let media content providers publish them with ease with a single tool.";
	$mpagekeywords=isset($mpagekeywords)?$mpagekeywords:"Newspapers, Magazines, Comic books, Nigerian Comics, African Comics, Okebukola Olagoke, Developed by Okebukola Olagoke, Dream bench Technologies";
	$mpageicon=isset($mpageicon)?$mpageicon:$host_addr."images/favicon.ico";
	$mpageimage=isset($mpageimage)?$mpageimage:$host_addr."images/favicon.ico";
	$mpagetitle=isset($mpagetitle)?$mpagetitle:"Welcome | Napstand";
	$mpageogtype=isset($mpageogtype)?$mpageogtype:"website";
	$mpageurl=isset($mpageurl)?$mpageurl:$host_addr;
	$mpagefbappid=isset($mpagefbappid)?$mpagefbappid:"";
	$mpagefbadmins=isset($mpagefbadmins)?$mpagefbadmins:"";
	$mpagesitename=isset($mpagesitename)?$mpagesitename:"Napstand Official Website";
	$mpageauthor=isset($mpageauthor)?$mpageauthor:"Napstand";
	$mpagecrumbclass=isset($mpagecrumbclass)?$mpagecrumbclass:"hidden";
	$mpagecrumbtitle=isset($mpagecrumbtitle)?$mpagecrumbtitle:"";
	$mpagecrumbtitlepath=isset($mpagecrumbtitlepath)?$mpagecrumbtitlepath:"";
	$mpagecrumbpath=isset($mpagecrumbpath)?$mpagecrumbpath:"";
	//for holding extra detail elements in the HEAD tag
	$mpageheadtagextras=isset($mpageheadtagextras)?$mpageheadtagextras:"";
	/*end page meta defaults*/

	$mpageheadingstyledisplay="hidden"; //variable for holding headerbar style switch for the home page 
	$mpageheadingstyletwodisplay=""; //variable for holding headerbar style switch for other pages 
	
	//for holding varying color stylesheet output content
	$mpagecolorstylesheet="";
	
	// variable to hold the class profile content display on page header navbar
	$mpageuserdisplay='';

	// variable for holding the logout link for any profile type
	$mpagelogoutlink=isset($mpagelogoutlink)?$mpagelogoutlink:"##";

	// variable for holding the relative path for a users profile image
	$mpageuserimage=$host_addr."images/default.gif";
	
	// variable for telling if a user is logged in and triggering display of profile
	// content on the page header navbar, values are on and off
	$mpageuserloggedin=isset($mpageuserloggedin)?$mpageuserloggedin:"";
	
	$mpagebanner=""; // for holding the page banner value if one is present
	
	$mpageforcescriptasync="async"; //for holding a default async script value
	
	// google analytics
	$mpagega="
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	      ga('create', '$host_gacode', '$host_gaurl');
		  ga('send', 'pageview');
		</script>
	";
	
	//for holding extra lib scripts to be imported,loads above page
	$mpagelibscriptextras=isset($mpagelibscriptextras)?$mpagelibscriptextras:"";

	//for holding extra scripts to be imported
	$mpagescriptextras=isset($mpagescriptextras)?$mpagescriptextras:"";
	//for holding extra styles to be imported
	$mpagestyleextras=isset($mpagestyleextras)?$mpagestyleextras:"";
	
	//for adding extra styling to the logo when necessary, using class
	//names
	$mpagelogostyle=isset($mpagelogostyle)?$mpagelogostyle:"";
	
	$mpagemaps='
		<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
		<script src="'.$host_addr.'scripts/js/maplace.min.js"></script>
	';
	
	$mpagecontactpanel=isset($mpagecontactpanel)?$mpagecontactpanel:"##";
	$mpagecontactpanel=""; //stores the contact panel section information
	/*shopping and store based variables here */
		// Variable to carry current value of the cart
		$mpagecartcounter=isset($mpagecartcounter)?$mpagecartcounter:"0";

	/*end*/

	// for holding sidebarcontent that sits at the top of the sidebar widget
	$mpagemidsidebarcontent=isset($mpagemidsidebarcontent)?$mpagemidsidebarcontent:"";
	$mpagetopsidebarcontent=isset($mpagetopsidebarcontent)?$mpagetopsidebarcontent:"";
	$mpagesidebarextras=isset($mpagesidebarextras)?$mpagesidebarextras:"";

	$mpagefooterclass=isset($mpagefooterclass)?$mpagefooterclass:"";
	// check for active user or subadmin session
	// session variable for logged in user
	if(isset($_SESSION[''.$host_sessionvar_prefix.'useri'.$host_sessionvar_suffix.''])||isset($_SESSION[''.$host_sessionvar_prefix.'clienti'.$host_sessionvar_suffix.''])){
		$mpageuserdisplay=""; // this variable holds the class for hiding or showing the content of the user bar at the top of every page 
		if(isset($_SESSION[''.$host_sessionvar_prefix.'userh'.$host_sessionvar_suffix.''])){
			$mpageprofilepageout=".php";
			$mpageusertype="user";
		}elseif(isset($_SESSION[''.$host_sessionvar_prefix.'clienth'.$host_sessionvar_suffix.''])){
			$mpageprofilepageout=$host_addr."clientdashboard.php";
			$mpageusertype="client";
		}

		$mpageloginstyle="";
		$mpageprofileheaderdisplayclass="";
		$mpageuserloggedin='on';
		$mpagelogoutlink=$host_addr."logout.php?type=$mpageusertype";
	}else{
		$mpageuserdisplay="hidden";
		$mpageprofilepageout="##";
		$mpageusertype="";
		$mpageusernametext="Guest";
		$mpageloginstyle="";
		$mpageuserimage="images/default.gif";
		
	}

	// preloaderout variable controls if the display of the websites preloader content
	// occurs or doesn't
	$mpagepreloaderout='';
	
	//variable to control default slider display for a page
	$mpageslider='';
	//variable to control default revolution slider display for a page
	$mpagerevslider='';

	/*Section for customizing page specific meta content*/
		/*Active page variable sections
		*$activepage=[0-n] parentvariable for deciding which content course to follow

		*/
		if(isset($activepage)&&$activepage>-1){
			if($activepage_type=="admin"){
				$mpagedescription="";
				$mpagekeywords="";
				$mpagetitle="Admin Dashboard";
				$mpageurl=$host_addr."admin/adminindex.php";
				$mpagelogoutlink=$host_addr."snippets/logout.php?type=admin";
			}else if($activepage_type=="mobilecheckout"){
				$mpagetitle="Mobile Checkout | Mysalvus";
  				$mpagedescription="payment point for purchasing Napstand Content";
  				$mpagekeywords="";
  				$mpageheadtagextras='

  				';
			}else if($activepage_type=="resetpassword"){
				$mpagetitle="Password Reset | MySalvus";
  				$mpagedescription="Reset your MySalvus account password";
  				$mpagekeywords="";
  				$mpageheadtagextras='

  				';
			}

		}
?>