<?php
	include 'connection.php';
	// get Stub
	$stub=$_GET['stub'];
	// get current date
	$date=date("Y-m-d");
	$dowquery="SELECT * FROM transaction WHERE stublink='$stub' AND enddate>='$date' AND downloads<5";
	// echo $dowquery;
	$rundown=mysql_query($dowquery)or die(mysql_error());
	$numrowsdown=mysql_num_rows($rundown);
	if($numrowsdown>0){
		$row=mysql_fetch_assoc($rundown);
		$id=$row['id'];
		$tid=$row['voguerefid'];
		$download=$row['downloads'];
		$fid=$row['fileid'];
		// echo $fid;
		if($download<=5){
			$mediadata=getSingleMediaDataTwo($fid);
			$audioinfo=getSingleStoreAudio($mediadata['ownerid']);
			$file=".".$mediadata['location'];
			// trigger the download
			if (file_exists($file)) {
			    header('Content-Description: File Transfer');
			    header('Content-Type: application/octet-stream');
			    header('Content-Disposition: attachment; filename='.$audioinfo['title'].".mp3");
			    header('Expires: 0');
			    header('Cache-Control: must-revalidate');
			    header('Pragma: public');
			    header('Content-Length: ' . filesize($file));
			    readfile($file);
				genericSingleUpdate("transaction","downloads",$download+1,"id",$id);
			    exit;
			}

		}else{
			echo "Sorry you have exceeded the amount of downloads available";
		}

	}else{
		echo"sorry this stublink does not exist, has expired, or the max download limit has been exceeded";
	}
?>