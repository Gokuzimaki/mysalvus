<?php  
session_start();
include('snippets/connection.php');
// include('login.php');
$activepage2="active";

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
	  				<h2 class="page-header salvus-intro-header">
	  					About MySalvus 
	  				</h2>
	  				<div class="salvus-intro-content">
	  					MySalvus is a project of HACEY HEALTH INITIATIVE dedicated to helping 
	  					victims of sexual violence report incidents in safety and confidentiality 
	  					and also have access to comprehensive support services. 
	  				</div>
				</div>

				<div class="col-md-12">
					<h2 class="text-center page-header _1">
						Our platform makes things easy for you. 
						<small>Here's how</small>
					</h2>
					<div class="col-md-12 _salvus-img-container">
	        
				        <div class="col-sm-4">
				          <img class="img-responsive img-rounded _salvus-img-group" 
				          src="<?php echo $host_addr;?>images/mysalvusimages/infoaccess.png" 
				          alt="Information Access MySalvus">
				          <h5 class="text-center top30 mod1">Information Access</h5>
				          <p class="text-center concrete">
				          	Ease of access and secure transmission of information to 
				          	service providers; dissemination of information on sexual 
				          	violence and support services to users
				          </p>
				        </div>

				        <div class="col-sm-4">
				          <img class="img-responsive img-rounded _salvus-img-group" 
				          src="<?php echo $host_addr;?>images/mysalvusimages/userreq.png" 
				          alt="MySalvus Record">
				          <h5 class="text-center top30 mod1">Support/Aid Requests</h5>
				          <p class="text-center concrete">
				          	Access to service providers required by users by sending 
				          	requests to as many as needed on any number of support 
				          	services they are in need of.</p>
				        </div>

				        <div class="col-sm-4">
				          <img class="img-responsive img-rounded _salvus-img-group" 
				          src="<?php echo $host_addr;?>images/mysalvusimages/ssinteractions.png" 
				          alt="MySalvus Feedback">
				          <h5 class="text-center top30 mod1">Service Provider Interaction</h5>
				          <p class="text-center concrete">
				          	Service providers connecting and collaborating with each other 
				          	to provide comprehensive care to survivors on the platform and 
				          	share knowledge.</p>
				        </div>
				    </div>
				</div>
				<div class="col-md-12">
					<div class="col-sm-6 clear-both _howitworksvid margin-auto text-center bg-darkgrey-gradient">

					</div>
					<h2 class="text-center page-header _1">
						<small>How MySalvus works for Users</small> 
					</h2>
					<div class="col-md-12">

						<ul class="horizontal-process-builder fixed">
		                    <li>

		                        <!-- <i class="ifc-business"></i> -->

								  <i class="fa fa-user-plus"></i>

		                        <div class="process-description">
		                            Register account
		                        </div><!-- end .process-description -->
		                        
		                    </li>
		                    <li>
		                        <i class="fa fa-pencil-square-o"></i>
		                        <div class="process-description">
		                            Record Incident for you or someone you know
		                        </div><!-- end .process-description -->
		                    </li>
		                    <li>
		                        <i class="fa fa-share-square"></i>
		                        <div class="process-description">
		                            Submit report to Service providers (Optional) 
		                        </div><!-- end .process-description -->
		                    </li>
		                    <li>
		                    
		                        <i class="fa fa-users"></i>
		                        
		                        <div class="process-description">
		                        	Access needed support.
		                        </div><!-- end .process-description -->
		                    </li>
		                    
		            	</ul>
					</div>
				</div>
				<div class="col-md-12">
					<h2 class="text-center page-header _1">
						<small>MySalvus for Service Providers </small>
					</h2>
					<div class="col-md-12">

						<ul class="horizontal-process-builder five-items fixed">
		                    <li>

		                        <!-- <i class="ifc-business"></i> -->

								  <i class="fa fa-briefcase"></i>

		                        <div class="process-description">
		                            Register account
		                        </div><!-- end .process-description -->
		                        
		                    </li>
		                    <li>
		                        <i class="fa fa-file-text-o"></i>
		                        <div class="process-description">
		                            Complete Your company profile information
		                        </div><!-- end .process-description -->
		                    </li>
		                    <li>
		                        <i class="fa fa-check"></i>
		                        <div class="process-description">
		                            Get verified and activated on the platform
		                        </div><!-- end .process-description -->
		                    </li>
		                    <li>
		                    
		                        <i class="fa fa-gear fa-spin"></i>
		                        
		                        <div class="process-description">
		                        	Undertand the tools provided by the platform.
		                        </div><!-- end .process-description -->
		                        
		                    </li>
		                    <li>
		                    
		                        <i class="fa fa-universal-access"></i>
		                        
		                        <div class="process-description">
		                        	Provide help to victims
		                        </div><!-- end .process-description -->
		                        
		                    </li>
		            	</ul>
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