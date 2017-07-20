<?php
	!isset($activepage1)?$activepage1="":$activepage1=$activepage1;
	!isset($activepage2)?$activepage2="":$activepage2=$activepage2;
	!isset($activepage3)?$activepage3="":$activepage3=$activepage3;
	!isset($activepage4)?$activepage4="":$activepage4=$activepage4;
	!isset($activepage5)?$activepage5="":$activepage5=$activepage5;
	!isset($activepage6)?$activepage6="":$activepage6=$activepage6;
	!isset($activepage7)?$activepage7="":$activepage7=$activepage7;
	!isset($activepage8)?$activepage8="":$activepage8=$activepage8;
?>
				

<!-- Header start -->
  	<header>
  	<!-- Toolbar start -->
  		<div class="row toolbar">
  			<div class="container">
            	<div class="col-sm-6 col-xs-6">
            		<a href="<?php echo $host_addr;?>logincenter.php">
                 		<span class="glyphicon glyphicon-lock"></span>
                 	 	Login</a>
                 	<span class="separator"></span>
                 	<a href="mailto:contact@mysalvus.org">
                 		<span class="glyphicon glyphicon-envelope"></span>
                 	 	contact@mysalvus.org</a>
      			</div>
	            <div class="col-sm-6 hidden-xs">
              		<div class="social-media-icons pull-right">
	                    <a href="#" title="Facebook" class="tooltip-bottom" data-toggle="tooltip"><span aria-hidden="true" class="mk-social-facebook"></span></a>
	                    <a href="#" title="Twitter" class="tooltip-bottom" data-toggle="tooltip"><span aria-hidden="true" class="mk-social-twitter-alt"></span></a>
	                    <a href="#" title="Linkedin" class="tooltip-bottom" data-toggle="tooltip"><span aria-hidden="true" class="mk-social-linkedin"></span></a>
	                </div>
	            </div>
    		</div>
  		</div>
  	<!-- Toolbar end -->
  	<!-- header-main start -->            
      <div class="row header-main">
          <div class="container">
              <!-- Navbar Start -->
              <nav class="navbar navbar-default ">
                <div class="navbar-header">
                  <button type="button" data-toggle="collapse" data-target="#navbar-collapse-2" class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                  <a href="<?php echo $host_addr;?>" class="navbar-brand">
                  	<img src="<?php echo $host_logo_imagemidi;?>" class="img-responsive" alt="Logo">
                  </a>
                </div>
                <!-- links section -->
                <div id="navbar-collapse-2" class="navbar-collapse collapse pull-right">
                  <ul class="nav navbar-nav">
                    <!-- MegaMenu Start -->
                      <li class=" <?php echo $activepage1;?>">
                      	<a href="<?php echo $host_addr;?>" class="">
                      		Home
                      	</a>
                        
                      </li>
                    <!-- MegaMenu End -->
                    <!-- Pages Menu Start -->
                          <li class=" <?php echo $activepage2;?>"> 
                          	<a href="<?php echo $host_addr;?>about.php" class="" >
                          		About</a>
                          </li>
                    <!-- Pages Menu End -->
                    
                    <!-- Portfolio Menu Start -->
                          <li class=" <?php echo $activepage3;?>"> 
                          	<a href="<?php echo $host_addr;?>joinus.php" class="" data-toggle="">
                          		Get Involved </a>
                          </li>
                    <!-- service provider reg Menu End -->
                    
                    <!-- Blog Menu Start -->
                          <li class=" <?php echo $activepage4;?>"> 

                          	<a href="<?php echo $host_addr;?>ireport.php" 
                          		class="" data-toggle="">
                          		Get Help </a>
                            
                          </li>
                    <!-- Blog Menu End -->
                    
                    <!-- Features Menu Start -->
                          <!-- <li class=" <?php echo $activepage5;?>"> 
                          	<a href="<?php echo $host_addr;?>resources.php" 
                          		class="" data-toggle="">
                          		Resources</a>
                            
                          </li> -->
                    <!-- Features Menu End -->

                    <!-- Features Menu Start -->
                          <li class=" <?php echo $activepage7;?>"> 
                          	<a href="<?php echo $host_addr;?>faq.php" 
                          		class="" data-toggle="">
                          		FAQs</a>
                            
                          </li>
                    <!-- Features Menu End -->
                    
                    <!-- Contact Us Menu Start -->
                          <li class=" <?php echo $activepage6;?>"> 
                          	<a href="<?php echo $host_addr;?>blog.php" 
                          		class="" data-toggle="">
                          		Blog
                          	</a>
                            
                          </li>
                    <!-- Contact Us Menu End -->
                    
                  </ul>
                </div>
              </nav>
              <!-- Navbar end -->
          </div>
      </div>
     <!-- header-main end -->           
   </header>
   <!-- Header end -->
<?php echo $mpagecrumbpath;?>