<?php  
session_start();
include('snippets/connection.php');
// include('login.php');
$activepage5="active";
include($host_tpathplain.'themesnippets/mysalvussnippets/headcontentmysalvus.php');

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
								<span class="key_notes">Record</span> – 
								<span class="key_notes">Report</span> – 
								<span class="key_notes">Access Help.</span>
								<a href="<?php echo $host_addr;?>ireport.php" 
									class="tagline_link">
									Report an Incident</a>
							</div>
						</div>
			        </div>
		      
		      	</div>
		    
		    </div>

  			<div class="container">
  				
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