<?php  
session_start();
include('snippets/connection.php');
// include('login.php');
$activepage1="active";
include($host_tpathplain.'themesnippets/mysalvussnippets/headcontentmysalvus.php');

?>
	<body class="">
		<?php
			include($host_tpathplain.'themesnippets/mysalvussnippets/toplinksmysalvus.php');
		?>
	    <!-- Content start -->
  		<div class="container">
  			<div class="row content">
				<div class="col-md-12">
                    <h2 class="page-header">Page Not Found </h2>
                    
                    <!-- Error Template Start -->
                        <div class="error-template">
                            <p class="error-404 wow fadeInUp">
                            	<i class="fa fa-chain-broken fa-2x"></i></p>
                            <p class="lead wow fadeInUp" >Apologies we could not find 
                            	what you wanted.</p>
                            <p class="wow fadeInUp" >
                            	Though we have these helpful links:</p>
                            <div class="error-actions top40">
                                <a href="<?php echo $host_addr;?>" 
                                	class="btn btn-primary btn-lg wow fadeInUp" 
                                ><span class="glyphicon glyphicon-home"></span>
                                    Take Me Home </a>
                                <a href="<?php echo $host_addr;?>ireport.php" 
                                	class="btn btn-default btn-lg wow fadeInUp" >
                                	<span class="glyphicon glyphicon-user"></span> 
                                	Report An Incident</a>
                            </div>
                        </div>
                    <!-- Error Template End -->
                    
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