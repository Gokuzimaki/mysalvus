<?php
	if(isset($mpagelegacyfullbackground)){
		// echo $mpagelegacyfullbackground;
	}
	$mpagefooteroutput="";
	if(isset($mpagefooterstylemarker)){
		if($mpagefooterstylemarker==0){
			$mpagefooteroutput=$mpagefootermorelinks;
		}else if($mpagefooterstylemarker==1){
			$mpagefooteroutput=$mpagefooterlatestposts;
		}
	}
    // holds an iframe leading to the download page
    if(isset($mpagedownloadframe)){

    }
    // test to see if we are currently on the contact page
    $hidereachus="";
    if(isset($activepage7)&&$activepage7=="active"){
    	$hidereachus="hidden";
    }
    // bring in the footer widget for the current theme
    // include("modules/footerparser.php");
?>


<!-- Main end -->
    <!-- MainBottom start -->
    <div class="row mainbottom">
        <div class="container top20">
        
            <?php
    			include("modules/footerparser.php");

            ?>

        </div>
    </div>
 	<!-- MainBottom end -->
 	<!-- Footer start -->
 		<div class="row footer">
 			<div class="container">
            	<div class="col-md-6 col-sm-6 col-xs-4">
            		
            		<?php 
						if(isset($defaultfootertrailer)&&$defaultfootertrailer!==""
							&&strtolower($defaultfootertrailer)!=="na"){
							echo "<p>".$defaultfootertrailer."</p>";
						}else{
					?>
							<p class="copyright">
								&copy;  <?php echo date('Y');?> 
								<a href="http://hacey.org">HACEY Health Initiative.</a>
								All Rights Reserved</p>
					<?php
						}
					?>

            </div>
            <div class="col-md-6 col-sm-6 col-xs-8">
            	<ul class="nav nav-pills pull-right">
                  <li><a href="http://hynstein.com">Powered by Hynstein,</a></li>
                  <li><a href="http://dreambench.io">DreamBench Technologies</a></li>
                </ul>
            </div>
 		</div>
 	<!-- Footer end -->
	<!-- scroll-to-top start -->
    <span id="scroll-to-top">
      <a class="scroll-up">
        <span class="glyphicon glyphicon-chevron-up"></span>
      </a>
	</span>
    <!-- scroll-to-top end -->