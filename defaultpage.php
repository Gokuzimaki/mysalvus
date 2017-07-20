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