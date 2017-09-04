<?php
	if(isset($host_target_addr) && (strpos($host_target_addr, "localhost/")||strpos($host_target_addr, "wamp"))){
	  	// for local server, connection file will already be included
	}else{
		include('connection.php');
	}
	$today=date("Y-m-d");
	$timenow=date("H:i:s");
	$fullperiod=$today." ".$timenow;
	$query1="SELECT * FROM blogentries WHERE (postperiod<'$fullperiod' OR 
		postperiod='$fullperiod') AND status='schedule' 
		AND scheduledpost='yes' 
		AND entrydate!='25, January 1970 00:00:00'";
	$run1=mysql_query($query1)or die(mysql_error()." Line".__LINE__);
	$numrows1=mysql_num_rows($run1);
	if($numrows1>0){
		while ($row1=mysql_fetch_assoc($run1)) {
			# code...
			$postid=$row1['id'];
			publishPost($postid);
		}
	}
	// next delete duplicate entries from users table that are users
	// first test if the table exists
	if(mysql_num_rows(mysql_query("SHOW TABLES LIKE 'users'"))==1) {
		$queryuserd="DELETE email1 FROM users email1, users email2 WHERE 
		(email1.id < email2.id AND email1.email = email2.email AND 
		email1.usertype=email2.usertype)";
		$runuserd=mysql_query($queryuserd)or die(mysql_error()." Real number: ".__LINE__);		
	}
?>