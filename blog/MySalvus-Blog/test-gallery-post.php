<?php 
			$pageid=2;			
			if(session_id()==''){
				session_start();
			}
			if(!function_exists('getExtension')){
				include('../../snippets/connection.php');
			}
			
			include($host_tpathplain."modules/blogtemp.php");
			include($host_tpathplain."modules/blogpagecreate.php");
		?>