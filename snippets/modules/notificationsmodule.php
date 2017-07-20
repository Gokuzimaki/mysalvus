<?php
	// This module is responsible for data entries into the notifications table
	// when actions are successfully carried out on the user or admin end
	// the "notifications" table columns are as follows
	/*
	*userid
	*usertype -the table where this user can be found
	*action - [create,retrieve,update,delete,login,loginattempt,logout,accountactivation]
	*actionid - id of the target of an action when it occurs
	*actiontype - the table name of the affected target
	*actiondetails - miniature details on an action carried out
	*entrydate
	*viewlevelid - [0,.....n] 0 represents the Super user only, 1 represents any other admin account
	*viewleveltype - represents the level of viewer that can see the notification
	*/
	function createNotification($userid,$usertype,$action,$actiondetails,$actionid="",$actiontype="",$actionhash="",$viewlevelid="",$viewleveltype=""){
		include('globalsmodule.php');
		// echo $actionhash;
	    $today=date("Y-m-d H:i:s");
	    $nextnot=getNextId('notifications');
        $mediaquery="INSERT INTO notifications
        (userid,usertype,action,actiondetails,actionid,actiontype,actionhash,viewlevelid,
         viewleveltype,entrydate)
        VALUES
        ('$userid','$usertype','$action','$actiondetails','$actionid','$actiontype',
         '$actionhash','$viewlevelid','$viewleveltype','$today')";
        // echo $mediaquery;
        $mediarun=mysql_query($mediaquery)or die(mysql_error()." Line ".__LINE__);
		// return $nextnot;
	};

	function getNotifications($tablename,$orderfield,$ordervalue,$order="",$limit="",$concat=""){
		include('globalsmodule.php');
		$row=array();
		$numrows=0;
		$tablename==""?$tablename="notifications":$tablename=$tablename;
		$ordervalues="id=0";
		if($tablename!==""&&$orderfield!==""&&$ordervalue!==""){
			if(is_array($orderfield) && is_array($ordervalue)&&count($orderfield)==count($ordervalue)){
				$ordervalues="";
				$orderfieldvals=count($orderfield)-1;
				for($i=0;$i<=$orderfieldvals;$i++){
					if($i!==$orderfieldvals){
						if($concat==""){
							$curconcat="AND";
						}else{
							$curconcat=is_array($concat)?$concat[$i]:$concat;

						}
						$ordervalues.="".$orderfield[$i]."='".$ordervalue[$i]."' ".$curconcat." ";
					}else{
						$ordervalues.=" ".$orderfield[$i]."='".$ordervalue[$i]."'";
					}
				}
				$query="SELECT * FROM $tablename WHERE $ordervalues";
			}else{
			  	$query="SELECT * FROM $tablename WHERE $orderfield=$ordervalue";
			}
			// echo $query.$order.$limit;
			$run=mysql_query($query.$order.$limit)or die(mysql_error()." Real number:".__LINE__." $query<br>");
			$numrows=mysql_num_rows($run);
			$prefetch=array();
			if($numrows>0){
				if($numrows==1){
					$row=mysql_fetch_assoc($run);
					// echo "in here";
				}else{
					while($prefetch=mysql_fetch_assoc($run)){
						$row[]=$prefetch;
					}
				}
			}
		}else{

			die('cant continue with missing ordervalues data'); 
		}

		$row['numrows']=$numrows;

		return $row;
	}

	// simple array of preset notification messages for action data
	function notificationsPlainArray($i,$rtype="",$type="",$ut="",$eid){
		/*
			$i= array index value
			$rtype = number or "Edit"
			$type=entryvariant
			$eid = the id for the entry
			$ut= usertype "admin" or "viewer"
		*/
		$row=array();
		$timedata=date("h:i:s A");
		$notifcationsdata=array();
		$notifications[0]="Updated entry Successfully on $timedata";
		$row['output']=isset($notifications[$i])?$notifications[$i]:"Last action completed Successfully at $timedata";
		return $row;
	}

?>