<?php 
			if(session_id()==''){
				session_start();
			}
			if(!function_exists('getExtension')){
				include('../../snippets/connection.php');
			}
			$pageid=6;
			
			include($host_tpathplain."modules/blogtemp.php");
			include($host_tpathplain."modules/blogpagecreate.php");
		?>