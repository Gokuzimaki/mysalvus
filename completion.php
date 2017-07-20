<?php  
session_start();
include('./snippets/connection.php');
$activemainlink="current activemainlink";
$activepage="active";
// obtain data
// obtain data
$mpageurl=$host_addr."completion.php";
$mpagetitle="Completion | $host_website_name";
$mpagecrumbtitle="Completion";
$message="Your action was successful";
$title="Completed!!!";
$style="success";
$lt="";
// for testing purposes, set the value of last success to 0
// $_SESSION['lastsuccess']=0;

if(isset($_SESSION['lastsuccess'])&&$_SESSION['lastsuccess']==0){
	$lt="valid";
}
$mpagecrumbclass="";
if(isset($_GET['t'])&&$_GET['t']!==""){
	$t=strtolower($_GET['t']);
	if(strtolower($t)=="spaccountreg"&&$lt=="valid"){
		$mpagetitle="Service Provider | Completion";
		$mpagecrumbtitle="Completion - Service Provider Account";
		$title="Account Created!!!";
		$message='<p>Thank you for completing our service provider form, an email has been 
		sent to you to verify your account and a respresentative of ours will contact you soon 
		for further verification and subsequent activation for access on the Salvus 
		platform, meanwhile, you can <a href="clientlogin.php">login</a> to your account';
	}else if(strtolower($t)=="uaccountreg"&&$lt=="valid"){
		$mpagetitle="User Account | Completion";
		$mpagecrumbtitle="Completion - User Account";
		$title="Account Created!!!";
		$message='<p>Thank you for completing our user registration form, please note that your personal information is confidential and will not be shared with a third party without your consent, meanwhile, you can 
		<a href="login.php">login</a> to your account and make your report on the platform.';
	}else if(strtolower($t)=="spconfirm"){
		$hashdata=$_GET['h'];
		$exp=explode(".", $hashdata);
		$hash=$exp[0];
		$id=$exp[1];
		$curd=date("Y-m-d H:i:s");
		$qt="SELECT * FROM users WHERE usertype='serviceprovider' AND id='$id' AND 
		uhash='$hash' AND status='active'";
		$rt=briefquery($qt,__LINE__,"mysqli");
		if($rt['numrows']>0){
			$udata=$rt['resultdata'][0];
			$already="";
			if(isset($udata['ecdone'])&&$udata['ecdone']=='yes'){
				$already="Already";
			}
			if(isset($udata['ecdone'])){
				genericSingleUpdate("users","ecdone","yes","id","$id");
				genericSingleUpdate("users","ecdate","$curd","id","$id");
			}

			$mpagetitle="Email Confirmation | Completion";
			$mpagecrumbtitle="Completion - Email Confirmation";
			$title="Email$already Verified!!!";
			$message='<p>Hello '.$udata['businessname'].', Thank you for verifying your 
			email account with our platform.</p>
			';
		}else{
			$mpagetitle="Email Confirmation Failure ";
			$mpagecrumbtitle="Completion - Email Confirmation Failure";
			$title="Confirmation Failure!!!";
			$message='<p>Apologies but there seems to be a problem verifying the 
			organisation email address for your account.</p>
			';
			$style="failure";
		}

	}else if(strtolower($t)=="eventsubscription"){
		$mpagetitle="Email Exists | Completion";
		$mpagecrumbtitle="Completion - Events";
		$title="Subscription Completed!!!";
		$message='<p>Thank you for subscribing to our events, you will get direct access to our events
			as they come up on the platform, we hope this in turn spurs you to participate actively in our programs</p>
		';
	}else if(strtolower($t)=="contactform"){
		$mpagetitle="Message Sent | Completion";
		$mpagecrumbtitle="Completion - Contact Form";
		$title="Message Sent!!!";
		$message='<p>Thank you for reaching us via our contact form, we will respond to you in the shortest possible time.</p>
		';
	}else if(strtolower($t)=="eventemailexists"){
		$mpagetitle="Email Exists | Completion";
		$mpagecrumbtitle="Completion - Events";
		$title="Email Exists!!!";
		$message='<p>Thank you for subscribing, we noticed you had already subscribed to our events with the email 
					address you used, please use another one</p>
		';
	}else if(strtolower($t)=="eventreg"){
		$mpagetitle="Event Registration/Information | Completion";
		$mpagecrumbtitle="Completion - Events";
		$title="Event Message has been Sent!!!";
		$message='<p>Thank you for showing interest in our event, we will respond shortly 
		to your email</p>
		';
	}else if($t=="resetlink"&&$lt=="valid"){
		$mpagetitle="Password Reset Link| Completion";
		$mpagecrumbtitle="Completion - Password Reset Link";
		$title="Reset link Generated!!!";
	    $message='Your password reset link was successfully created and has been sent to 
	    your email address, it is valid for the next hour, thank you.
	    .';

	}else if($t=="resetdone"&&$lt=="valid"){
		$mpagetitle="Password Reset | Completion";
		$mpagecrumbtitle="Completion - Password Reset";
		$title="Your Password was changed!!!";
	    $message='Password reset was successfully done, please go to your login view and 
	    try getting into your account with your new password.';

	}else if($t=="resetemailfail"&&$lt=="valid"){
		$mpagetitle="Reset Failure | Completion";
		$mpagecrumbtitle="Completion - Password Reset Failure";
		$title="Reset Failed!!!";
	    $message="<p>Your Password reset link was not generated.<br>
	            The email address you put in did not belong to any 
	            registered account on the platform, please use a correct email or if 
	            you made a mistake with your email when registering, contact us.</p>";
	}else if($t=="resetfail"&&$lt=="valid"){
		$mpagetitle="Reset Failure | Completion";
		$mpagecrumbtitle="Completion - Password Reset Failure";
		$title="Reset Failed!!!";
	    $message="<p>Your Password and its confirmation did not matcheach other
	    or they were both empty.</p>";
	}else{
		$mpagetitle="Welcome | Completion";
		$mpagecrumbtitle="Completion Page";
		$mpagecrumbpath='';
		$title="Hi There!!!";
		$message='<p>This is our completion page. We show you how well an action you 
		carried out on the platform has gone here. Apparently theres nothing for us to 
		show you but if you <a href="ireport.php">report an incident</a> or 
		<a href="joinus.php">become a Service Provider</a> we would have something for
		you here.
		</p>
		';
	}
}else{
	// $mpagetitle="";
}
$totalstyle="alert alert-$style";
if(isset($_SESSION['lastsuccess'])&&$_SESSION['lastsuccess']==0){
	unset($_SESSION['lastsuccess']);
}

$mpagemaps="";
include('snippets/themesnippets/mysalvussnippets/headcontentmysalvus.php');
?>
	<body class="">
		<?php
			include($host_tpathplain.'themesnippets/mysalvussnippets/toplinksmysalvus.php');
		?>
	    <!-- Content start -->
	    <div class="container">
	  		<div class="row content">
				<div class="container <?php echo $totalstyle;?>">
	  				<h2 class="page-header"><?php echo $title?></h2>	
	  				<div class="col-md-12">
	  					<?php echo $message;?>
	  				</div>
	  			</div>
		    </div>
  		</div>
	    <!-- Content End -->
	    <?php
			include($host_tpathplain.'themesnippets/mysalvussnippets/footermysalvus.php');
		?>
		<?php
			include($host_tpathplain.'themesnippets/mysalvussnippets/themescriptsdumpmysalvus.php');
		?>
	</body>
</html>
</html>