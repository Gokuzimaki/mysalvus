<?php  
session_start();
include('snippets/connection.php');
// include('login.php');
$activepage1="active";
// setup page script lib entries
$mpagelibscriptextras='<script src="'.$host_addr.'plugins/showbizpro/js/jquery.themepunch.showbizpro.min.js"></script>'; 
$mpagelibscriptextras='<script src="'.$host_addr.'plugins/showbizpro/js/jquery.themepunch.showbizpro.min.js"></script>'; 
$mpagelibscriptextras.='<script src="'.$host_addr.'plugins/showbizpro/js/jquery.themepunch.tools.min.js"></script>
					<script src="'.$host_addr.'plugins/showbizpro/fancybox/jquery.fancybox.pack.js"></script>
					<!--ACTIVATE THE SHOWBIZPRO START-->
					<script type="text/javascript">

						jQuery(document).ready(function() {

							jQuery(\'#example1\').showbizpro({
								dragAndScroll:"on",
								visibleElementsArray:[3,3,2,1],
								carousel:"off",
								entrySizeOffset:0,
								allEntryAtOnce:"off",
								rewindFromEnd:"on",
								autoPlay:"on",
								delay:5000,						
								speed:1200,
								easing:\'Back.easeOut\'
							});
							
							jQuery(\'#logos\').showbizpro({
								dragAndScroll:"on",
								visibleElementsArray:[5,3,2,1],
								carousel:"on",
								entrySizeOffset:0,
								allEntryAtOnce:"off",
								speed:1200,
								autoPlay:"on",
								rewindFromEnd:"on",
								delay:5000,
								easing:\'Back.easeOut\'
							});

							// THE FANCYBOX PLUGIN INITALISATION
							// jQuery(".fancybox").fancybox();


						});

					</script>
					<!--ACTIVATE THE SHOWBIZPRO END--> 
'; 
// $mpagelibstyleextras.=$host_addr.'plugins/showbizpro/css/fontello.css'; 
$mpagelibstyleextras='<link rel="stylesheet" href="'.$host_addr.'plugins/showbizpro/css/settings.css"/>'; 
$mpagelibstyleextras.='<link rel="stylesheet" href="'.$host_addr.'stylesheets/themestyles/mysalvuscss/style-showbizpro.css"/>'; 
$mpagelibstyleextras.='<link rel="stylesheet" href="'.$host_addr.'plugins/showbizpro/fancybox/jquery.fancybox.css"/>'; 
include($host_tpathplain.'themesnippets/mysalvussnippets/headcontentmysalvus.php');
// pull the service provider data

// $spdatamed=getAllUsers('viewer','','',''$type);
// $spdatamed=getAllUsers('viewer','','',''$type);
// pull the service provider businessnatures
$bn=getAllBusinessTypes();
$spdata=array();
if($bn['numrows']>0){
	// sort throught the result set and get the accompanying serviceproviders
	// pernature
	for($i=0;$i<$bn['numrows'];$i++){
		$curbn=$bn['resultdata'][$i];
		$type[0]="businessnature";
		$type[1]=$curbn['businessnature'];
		$spdata[$i]=getAllUsers('viewer','','serviceprovider','',$type);

		// get serviceproviders


	}
	// var_dump($spdata);
}
?>
	<body class="">
		<?php
			include($host_tpathplain.'themesnippets/mysalvussnippets/toplinksmysalvus.php');
		?>
	    <!-- Content start -->
  		<div class="row content">
			<div class="business-header2 _salvushome">

		      	<div class="container">

			        <div class="row">
						<div class="col-lg-12 _salvustextcontainer">
							<!-- The background image is set in the custom CSS -->
							<h3 class="home_text_header">
								Sexual Violence Reporting Platform
							</h3>
							<div class="home_text_content">
								Report cases of sexual violence anonymously and access available 
								support services.<br>
								<span class="key_notes">Report</span> – 
								<span class="key_notes">Record</span> – 
								<span class="key_notes">Access Help.</span>
								<a href="<?php echo $host_addr;?>ireport.php" 
									class="tagline_link">
									Report an Incident</a>
							</div>
						</div>
			        </div>
		      
		      	</div>
		    
		    </div>
  			<div class="container ">
  				<div class="row">
  					<div class="col-md-12 _salvus-intro-container">
		  				<h2 class="page-header salvus-intro-header">
		  					Welcome to MySalvus 
		  				</h2>
		  				<div class="salvus-intro-content">
		  					 Salvus, which means "<b>safe</b>", is an interactive sexual violence 
		  					 reporting platform where cases of sexual violence can be reported 
		  					 anonymously and survivors can access available support services.<br>
		  					 My Salvus is an initiative of Hacey Health Initiative with support from the 
		  					 Stars Foundation Girl Award and EMpower
		  				</div>
  					</div>
  				</div>
  			</div>
  			<div class="container _salvus-img-container">
        
		        <div class="col-sm-4">
		          <img class="img-responsive img-rounded _salvus-img-group" 
		          src="<?php echo $host_addr;?>images/mysalvusimages/24-hours-midi.png" alt="">
		          <h5 class="text-center top30 mod1">Report</h5>
		          <p class="text-center concrete">
		          	Reporting harassments as early as possible is the first 
		          	step to stopping them
		          </p>
		        </div>

		        <div class="col-sm-4">
		          <img class="img-responsive img-rounded _salvus-img-group" 
		          src="<?php echo $host_addr;?>images/mysalvusimages/male-reporter-midi.png" 
		          alt="MySalvus Record">
		          <h5 class="text-center top30 mod1">Record</h5>
		          <p class="text-center concrete">
		          	Information about the harrassment is crucial to amelioration,
		          	our platform allows detailed and secure data storage and quick 
		          	dissemination to service or aid providers to get you the help 
		          	you need.</p>
		        </div>

		        <div class="col-sm-4">
		          <img class="img-responsive img-rounded _salvus-img-group" 
		          src="<?php echo $host_addr;?>images/mysalvusimages/customer-service-midi.png" 
		          alt="MySalvus Feedback">
		          <h5 class="text-center top30 mod1">Provide Feedback</h5>
		          <p class="text-center concrete">
		          	We engage with our users to ensure they are getting the help
		          	they require from our service providers, and to see how else 
		          	we can help.</p>
		        </div>
		    </div>
  		</div>
  		<div class="row bottom50">
 			<div class="container">
                <div class="style-title">Latest <strong>Blog</strong> Posts</div>
                <div class="separator">
                    <div class="separator-style"></div>
                </div>
                <div class="divide20"></div>
                <!-- DEMO I. -->
                <div id="example1" class="showbiz-container _homeblog">

                    <!-- THE NAVIGATION -->
                    <div class="showbiz-navigation center sb-nav-grey hidden">
                      <a id="showbiz_left_1" class="sb-navigation-left"><i class="sb-icon-left-open"></i></a>
                      <a id="showbiz_play_1" class="sb-navigation-play"><i class="sb-icon-play sb-playbutton"></i><i class="sb-icon-pause sb-pausebutton"></i></a>          
                      <a id="showbiz_right_1" class="sb-navigation-right"><i class="sb-icon-right-open"></i></a>
                      <div class="sbclear"></div>

                    </div> <!-- END OF THE NAVIGATION -->

                    <div class="divide20"></div>

                    <!--  THE PORTFOLIO ENTRIES -->
                    <div class="showbiz" data-left="#showbiz_left_1" data-right="#showbiz_right_1" data-play="#showbiz_play_1">

                      <!-- THE OVERFLOW HOLDER CONTAINER, DONT REMOVE IT !! -->
                      <div class="overflowholder">
                        <!-- LIST OF THE ENTRIES -->
                        <?php 
							$b_options['pagination']=false;
							$b_options['type']="maincontenthomeblog";

							$b_options['blplimit']=10;

                        ?>
                        <ul>



                          <?php 
								include($host_tpathplain.'modules/blogpagemultipleparser.php');
                          ?>

                        </ul>
                        <div class="sbclear"></div>
                      </div> <!-- END OF OVERFLOWHOLDER -->
                      <div class="sbclear"></div>
                    </div>
                </div>
                <!-- END OF DEMO I. -->
      		</div>
 		</div>
  		<section id="content-2-7" class="content-2-7 parallax" 
  		style="background-image: url('images/mysalvusimages/helping-hand.jpg');" 
  		data-stellar-background-ratio="0.6" data-stellar-vertical-offset="20">
			<div class="overlay-07 padding-bottom80 _parallax_1">
				<section class="content-block top40 bg-trans" id="myTab">
					<div class="container">
						<h2 class="page-header salvus-intro-header _2">
		  					Emergency Services 
		  				</h2>

					    <ul class="nav nav-tabs text-center" role="tablist" id="myTab2">
					    	<?php
					    		// this array variable holds the number values for the tabs successfully
					    		// displayed as output
					    		if($bn['numrows']>0){

										// sort throught the result set and get the accompanying serviceproviders
										// pernature
										for($i=0;$i<$bn['numrows'];$i++){
											$t=$i+1;
											$curbn=$bn['resultdata'][$i];
											$curactive=$t==1?"active":"";
											if(strtolower($curbn['businessnature'])!=="referral"&&
												strtolower($curbn['businessnature'])!=="sarc"&&
												strtolower($curbn['businessnature'])!=="rehabilitation"&&
												strtolower($curbn['businessnature'])!=="safe house"){


											// first for loop for busnesstype
							?>	
									 <li class="overlay-07 <?php echo $curactive;?>">
								      	<a href="#tab<?php echo $t;?>" 
								      	role="tab" data-toggle="tab">
								      	<i class="fa <?php echo $curbn['faicon']?> fa-2x "></i>
						    			<span class="_sptext"><?php echo $curbn['businessnature']?></span>
								      </a>
								    </li>
					    	<?php
					    					}
										}
									}else{
					    	?>
						    <li class="overlay-07 active">
						      	<a href="#tab1" 
						      	role="tab" data-toggle="tab">
						      	<i class="fa fa-diamond fa-2x "></i>
						      </a>
						      </li>
						    <li class="overlay-07">
						    	<a href="#tab2" role="tab" 
						    	data-toggle="tab">
						    	<i class="fa fa-plus-square fa-2x"></i>
						    	<span class="_sptext">Medical</span>
						    	</a>
						    </li>
						    <li class="overlay-07">
						    	<a href="#tab3" role="tab" 
						    	data-toggle="tab">
						    	<i class="fa fa-line-chart fa-2x"></i></a>
						    </li>
						    <li class="overlay-07">
						    	<a href="#tab4" role="tab" 
						    	data-toggle="tab">
						    	<i class="fa fa-send-o fa-2x"></i></a>
						    </li>
						    <?php
						    	}
						    ?>
					    </ul>
					    <div class="tab-content">
					    	<?php
					    		if($bn['numrows']>0){
										// sort throught the result set and get the accompanying serviceproviders
										// pernature
							    		$tk=1;
										for($i=0;$i<$bn['numrows'];$i++){
											$t=$i+1;
											$curbn=$bn['resultdata'][$i];
											$curactive=$t==1?"active":"";
											
											// create container tabs per business nature
											// type
							?>	
								<div class="tab-pane fade in <?php echo $curactive;?> bg-trans" id="tab<?php echo $t;?>">
							      	<div class="row">
										<div class="panel-group" id="accordion_<?php echo str_replace(" ","",strtolower($curbn['businessnature']));?>_emergency">
					    	<?php
											$curspset=$spdata[$i];
											if($curspset['numrows']>0){
												// produce service providers
												for($j=0;$j<$curspset['numrows'];$j++){
													$k=$j+1;
													$curspdata=$curspset['resultdataset'][$j];

							?>	
											<div class="panel panel-default">
								                <div class="panel-heading">
								                  <h4 class="panel-title">
								                    <a class="accordion-toggle collapsed" 
								                    data-toggle="collapse" 
								                    data-parent="
								                    #accordion_<?php echo str_replace(" ","",strtolower($curbn['businessnature']));?>_emergency" 
								                    href="#collapse<?php echo $tk;?>" 
								                    aria-expanded="false">
								                      <?php echo $curspdata['businessname'];?> <small>(<?php echo ucfirst($curspdata['statename']);?> State)</small>
								                    </a>
								                  </h4>
								                </div>
								                <div id="collapse<?php echo $tk;?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
								                  <div class="panel-body">
								                      <b><span class="_col-body">
								                      	Address:</span></b><br>
								                      	<?php echo $curspdata['address'];?><br>
								                      <b><span class="_col-body">
								                      	Contact Phone:</span></b><br>
								                      	<?php echo $curspdata['contactphone'];?><br>
								                    	<b><span class="_col-body">
								                      	Contact Email:</span></b><br>
								                      	<?php echo $curspdata['contactemail'];?><br>
								                  </div>
								                </div>
								            </div>
					    	<?php
										    		$tk++;
					    						}
											}else{
												// default no service provider data
							?>	
								            <div class="panel panel-default">
								                <div class="panel-heading">
								                  <h4 class="panel-title">
								                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false">
								                      No Entries yet
								                    </a>
								                  </h4>
								                </div>
								                <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
								                  <div class="panel-body">
								                    No Valid entries to be displayed yet
								                  </div>
								                </div>
								            </div>
								            
					    	<?php
											}
											// first for loop for busnesstype
							?>	
								        </div>
								    </div>
								</div>

					    	<?php

										}
									}else{
					    	?>
						      	<div class="tab-pane fade in active bg-trans" id="tab1">
							      	<div class="row">
								        <div class="panel-group" id="accordion_emergency">
								            <div class="panel panel-default">
								                <div class="panel-heading">
								                  <h4 class="panel-title">
								                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false">
								                      No Entries yet
								                    </a>
								                  </h4>
								                </div>
								                <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
								                  <div class="panel-body">
								                    No Valid entries to be displayed yet
								                  </div>
								                </div>
								            </div>
								            <div class="panel panel-default">
								                <div class="panel-heading">
								                  <h4 class="panel-title">
								                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false">
								                      Premium Revolution Slider
								                    </a>
								                  </h4>
								                </div>
								                <div id="collapseTwo" class="panel-collapse collapse in" aria-expanded="true">
								                  <div class="panel-body">
								                    Proin feugiat, neque in auctor suscipit, augue elit consectetur est, at condimentum orci quam at erat. Fusce aliquet odio at lacinia vulputate. Phasellus fermentum magna vitae eros dignissim auctor. Donec sed feugiat ante, a fermentum urna. Nulla tincidunt risus eget risus pharetra, ac faucibus ligula placerat. Mauris in massa arcu. Etiam at egestas lorem. Aenean nec quam lectus. Proin nec nibh iaculis, egestas velit at, sodales urna. Pellentesque hendrerit lacus sit amet libero bibendum, venenatis eleifend justo feugiat. Aenean lectus, sodales rutrum lectus in, iaculis lacinia lacus. Phasellus ornare sapien odio. Proin volutpat lobortis libero, eu lacinia turpis accumsan mollis.
								                  </div>
								                </div>
								            </div>
								            <div class="panel panel-default">
								                <div class="panel-heading">
								                  <h4 class="panel-title">
								                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="features-elements.html#collapseThree" aria-expanded="false">
								                      Bootstrap Framework
								                    </a>
								                  </h4>
								                </div>
								                <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false">
								                  <div class="panel-body">
								                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable.
								                  </div>
								                </div>
								            </div>
								        </div>
							          <!-- /.col --> 
							        </div>
						      	<!-- /.row --> 
						      	</div>
						    <?php
						    	}
						    ?>
					      <a href="<?php echo $host_addr;?>joinus.php" 
					      class="tagline_link">
					      Join the Platform
						</a>
					    
					</div>
				  <!-- /.container --> 
				</section>

			</div>
		</section>

		<section id="content-1-6" class="content-1-6 content-block">
                
	        <div class="col-lg-12 bottom50 wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
	        	<h2 class="page-header text-center">
	        		Our Partners<br>
	        		<small></small>
	        	</h2>
	        </div>
	                
	        		
	        <div class="divide20"></div>
			<!-- Logos Start -->
		     <div id="logos" class="showbiz-container"> 
	 			<!-- THE NAVIGATION -->

				<div class="showbiz-navigation center sb-nav-grey"> 
				  	<a id="showbiz_left_2" class="sb-navigation-left">
				  		<!--<i class="sb-icon-left-open"></i>-->
				  	</a> 
				  	<a id="showbiz_play_2" class="sb-navigation-play">
				  		<!--<i class="sb-icon-play sb-playbutton"></i>
				  		<i class="sb-icon-pause sb-pausebutton"></i>-->
				  	</a> 
				  	<a id="showbiz_right_2" class="sb-navigation-right">
				  		<!--<i class="sb-icon-right-open"></i>-->
				  	</a>

				    <div class="sbclear"></div>

				</div>


	            <!--  THE PORTFOLIO ENTRIES -->
	            <div class="showbiz _homebiz" data-left="#showbiz_left_1" data-right="#showbiz_right_1" data-play="#showbiz_play_1">

	              <!-- THE OVERFLOW HOLDER CONTAINER, DONT REMOVE IT !! -->
	              <div class="overflowholder">
	                <!-- LIST OF THE ENTRIES -->
	                <ul>
	                  <!-- AN ENTRY HERE -->
	                    <li class="sb-light-skin">
	                        <div class="mediaholder ">
	                          <div class="mediaholder_innerwrap">
	                            <a href="http://starsfoundation.org.uk/" target="_blank"><img alt="" 
	                            	src="<?php echo $host_addr?>images/mysalvusimages/stars-foundation-logo.png"></a>
	                            <div class="hovercover" data-maxopacity="0.85">
	                              <a href="http://starsfoundation.org.uk/" target="_blank"><div class="linkicon"><i class="sb-icon-link"></i></div></a>
	                            </div>
	                          </div>

	                        </div>
	                        
	                  </li>

	                  <!-- AN ENTRY HERE -->
	                    <li class="sb-light-skin">
	                        <div class="mediaholder ">
	                          <div class="mediaholder_innerwrap">
	                            <a href="http://empowerweb.org/" target="_blank"><img alt="" 
	                            	src="<?php echo $host_addr?>images/mysalvusimages/empower-logo.jpg"></a>
	                            <div class="hovercover" data-maxopacity="0.85">
	                              <a href="http://empowerweb.org/" target="_blank"><div class="linkicon"><i class="sb-icon-link"></i></div></a>
	                            </div>
	                          </div>

	                        </div>
	                        
	                  	</li>
	                  	<!-- AN ENTRY HERE -->
	                    <li class="sb-light-skin">
	                        <div class="mediaholder ">
	                          <div class="mediaholder_innerwrap">
	                            <a href="http://dsvrtlagos.org/" target="_blank"><img alt="" 
	                            	src="<?php echo $host_addr?>images/mysalvusimages/dsvrt.jpg"></a>
	                            <div class="hovercover" data-maxopacity="0.85">
	                              <a href="http://dsvrtlagos.org/" target="_blank"><div class="linkicon"><i class="sb-icon-link"></i></div></a>
	                            </div>
	                          </div>

	                        </div>
	                        
	                  	</li>
	                  	<!-- AN ENTRY HERE -->
	                    <li class="sb-light-skin">
	                        <div class="mediaholder ">
	                          <div class="mediaholder_innerwrap">
	                            <a href="##"><img alt="" 
	                            	src="<?php echo $host_addr?>images/mysalvusimages/acces-logo.png"></a>
	                            <div class="hovercover" data-maxopacity="0.85">
	                              <a href="##"><div class="linkicon"><i class="sb-icon-link"></i></div></a>
	                            </div>
	                          </div>

	                        </div>
	                        
	                  </li>
	                  <!-- AN ENTRY HERE -->
	                    <li class="sb-light-skin">
	                        <div class="mediaholder ">
	                          <div class="mediaholder_innerwrap">
	                            <a href="##Lagos State Government"><img alt="" 
	                            	src="<?php echo $host_addr?>images/mysalvusimages/laslogo.png"></a>
	                            <div class="hovercover" data-maxopacity="0.85">
	                              <a href="##Lagos State Government" target="_blank"><div class="linkicon"><i class="sb-icon-link"></i></div></a>
	                            </div>
	                          </div>

	                        </div>
	                        
	                  </li>
	                </ul>
	                <div class="sbclear"></div>
	              </div> <!-- END OF OVERFLOWHOLDER -->
	              <div class="sbclear"></div>
	            </div>
			</div>

       	</section>
	    <!-- Content End -->
	    <?php
			include($host_tpathplain.'themesnippets/mysalvussnippets/footermysalvus.php');
		?>
		<?php
			include($host_tpathplain.'themesnippets/mysalvussnippets/themescriptsdumpmysalvus.php');
		?>
	</body>
</html>