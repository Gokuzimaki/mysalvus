<?php 
	include('connection.php');
	$downloadtype="";
	$extraval="";
	if(isset($_GET['downloadtype'])){
		$downloadtype=$_GET['downloadtype'];
		// $extraval=$_GET['extraval'];
	}elseif (isset($_POST['downloadtype'])) {
		$downloadtype=$_POST['downloadtype'];	
	}
	if($downloadtype==""){

	}else if($downloadtype=="resource"){
		$file=$_GET['datasrc'];
		$fileext=getExtension($file);
		$i = strrpos($file,"/");
		if (!$i) { echo $file; }
		// $filename=explode("/",$file);
		// $tot=count($filename);
		// echo $filename[$tot-1];
		$filename=getFilename($file);
		// echo $file." $filename";
		if (file_exists($file)) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename='.$filename);
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($file));
		    readfile($file);
		    exit;
		}else{
			echo "File does not exist <br>File: $file";
		}
	}
?>