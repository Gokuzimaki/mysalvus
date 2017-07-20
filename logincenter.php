<?php  
session_start();
include('snippets/connection.php');

$activepage="active";
$mpagetitle="Login Center | MySalvus";
$mpagecrumbtitle="Login Center";
$mpagedescription="Login to your account";
$mpagecrumbclass="";
$mpagecrumbdata='<li><a href="'.$host_addr.'">Home</a></li>
            <li class="active">Login Center</li>';
include($host_tpathplain.'themesnippets/mysalvussnippets/headcontentmysalvus.php');

?>
	<body class="">
		<?php
			include($host_tpathplain.'themesnippets/mysalvussnippets/toplinksmysalvus.php');
		?>
	    <!-- Content start -->
		<div class="row content">
			<div class="container">
				<div class="col-md-12 _salvus-intro-container">
	  				<h2 class="page-header text-center salvus-intro-header">
	  					Sign in to your account 
	  				</h2>
					<div class="col-md-5 col-sm-5  text-center">
						<a href="<?php echo $host_addr?>login.php" class="login-link tagline_link">
							<i class="fa fa-user"></i> User Login
						</a>
					</div>
					<div class="col-md-2 col-sm-2  text-center">
						<h4 class="mid-text">OR</h4>
					</div>
					<div class="col-md-5 col-sm-5  text-center">
						<a href="<?php echo $host_addr?>clientlogin.php" 
							class="login-link  tagline_link">
							<i class="fa fa-briefcase"></i> Service Provider
						</a>
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