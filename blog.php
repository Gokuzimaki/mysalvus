<?php  
session_start();
include('snippets/connection.php');
// include('login.php');
$activepage6="active";
include($host_tpathplain.'themesnippets/mysalvussnippets/headcontentmysalvus.php');

?>
	<body class="">
		<?php
			include($host_tpathplain.'themesnippets/mysalvussnippets/toplinksmysalvus.php');
		?>
	    <!-- Content start -->
  		<div class="container">
  			<div class="row content top20 hold bottom20">
  				<div class="col-md-12 blog-item">

					<?php
						include($host_tpathplain.'modules/blogpagemultipleparser.php');
					?>  					
  				</div>
  				<!-- <div class="col-md-3 col-sm-4">

  				</div> -->
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