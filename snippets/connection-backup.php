<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true" 
require_once 'paginator.class.php';
require_once "gifresizer.class.php";//Including our class $host_addr="http://localhost/muyiwasblog/";
require_once 'html2text.class.php';
require_once('php_image_magician.php');
require_once "SocialAutoPoster/SocialAutoPoster.php";
require_once('tmhOAuth-master/tmhOAuth.php');
require 'PHPMailer-master/PHPMailerAutoload.php';
date_default_timezone_set("Africa/Lagos");
$host_target_addr="http://".$_SERVER['HTTP_HOST']."/";
$host_addr="http://localhost/muyiwasblog/";
$host_email_addr="no-reply@muyiwaafolabi.com";
$host_keywords="";
// basic global variable for controlling redirects due to multiple administrators
// $rurladmin="nourl";
//set to true on upload
$host_email_send=false;
$hostname_pvmart = "localhost";
$db = "muyiwasblog";
$username_pvmart = "root";
//change pword when uploading to server
$password_pvmart = "";
/*controlblock*/
if(strpos($host_target_addr, "localhost/")){
  // for local server
  $host_addr="http://localhost/muyiwasblog/";
}else{
  // for remote server
  $host_addr="http://".$_SERVER['HTTP_HOST']."/";
  $host_email_send=true;
  $hostname_pvmart = "50.63.244.139";
  $db = "muyiwasblog";
  $username_pvmart = "muyiwasblog";
  //change pword when uploading to server
  $password_pvmart = "Mablog!123";
}
$logpart=md5($host_addr);
$host_minipagecount=5;
$wasl = mysql_connect($hostname_pvmart, $username_pvmart, $password_pvmart) or die(mysql_error());
mysql_select_db($db) or die ("Unable to select database!");
function getExtension($str) {
$i = strrpos($str,".");
if (!$i) { return false; }
$l = strlen($str) - $i;
$ext = substr($str,$i+1,$l);
return $ext;
}
function getFilename($filepath){
	$i = strrpos($filepath,"/");
	if (!$i) { return $filepath; }
 	$filename=explode("/",$filepath);
 	$tot=count($filename);
 	return $filename[$tot-2];
}

function getFileDetails($filepath,$typeoffile){
 	$retvals=array();
file_exists($filepath)?$filesize=filesize($filepath):$filesize="0B";
 	if($typeoffile=="image"){
if($filesize!=="0B"&&$filesize>0){
list($width,$height)=getimagesize($filepath);
$filesize=$filesize/1024;
$filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
if(strlen($filesize)>3){
	$filesize=$filesize/1024;
	$filesize=round($filesize,2);	
	$filesize="".$filesize."MB";
	}else{
	$filesize="".$filesize."KB";
	}
}
$retvals['width']=$width;
$retvals['height']=$height;
$retvals['size']=$filesize;
}else{
if($filesize!=="0B"&&$filesize>0){
list($width,$height)=getimagesize($filepath);
$filesize=$filesize/1024;
$filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
if(strlen($filesize)>3){
	$filesize=$filesize/1024;
	$filesize=round($filesize,2);	
	$filesize="".$filesize."MB";
	}else{
	$filesize="".$filesize."KB";
	}
}
$retvals['size']=$filesize;
}

 	return $retvals;
 }
//for performing targeted single update functions
function genericSingleUpdate($tablename,$updateField,$updateValue,$orderfield,$ordervalue){
$ordervalues="";
if($tablename!==""&&$updateField!==""&&$orderfield!==""&&$ordervalue!==""){
if(is_array($orderfield) && is_array($ordervalue)){
$orderfieldvals=count($orderfield)-1;
for($i=0;$i<=$orderfieldvals;$i++){
	if($i!==$orderfieldvals){
	$ordervalues.="".$orderfield[$i]."='".$ordervalue[$i]."' AND ";
	}else{
	$ordervalues.=" ".$orderfield[$i]."='".$ordervalue[$i]."'";
	}
}
$query="UPDATE $tablename SET $updateField='$updateValue' WHERE $ordervalues";
}else{
$query="UPDATE $tablename SET $updateField='$updateValue' WHERE $orderfield=$ordervalue";
}
//// echo $query;
if($updateValue!==""){
$run=mysql_query($query)or die(mysql_error());
}
}else{
die('cant Update with empty value in critical column');	
}
}
function genericMultipleInsert($tablename,$colname,$colval){
 $totalcolnamecount=count($colname)-1;
 $totalcolvalscount=count($colval)-1;
// // echo $totalcolvalscount;
$columnnames="";
$columnvalues="";
for($i=0;$i<=$totalcolnamecount;$i++){
if ($i==0) {
//	// echo $colname[$i];
$columnnames.="".$colname[$i]."";
//// echo $columnnames.'<br>';
}else{
//		// echo $colname[$i];
$columnnames.=",".$colname[$i]."";

}
}
//// echo '<br>'.$totalcolvalscount.'<br><br>';
$increment=$totalcolnamecount+1;
for($i=0;$i<=$totalcolvalscount;$i+=$increment){
$nextsize=$i+$totalcolnamecount;
//// echo $nextsize.'<br>';
//// echo $i.'<br>';
$columnvalues="";
for($t=$i;$t<=$nextsize;$t++){

//// echo $t.'<br>'.$i.'<br>';
if ($t==$i) {
$columnvalues.=''.$colval[$t].'';
//// echo $columnvalues.'<br>';
}else{
$columnvalues.=','.$colval[$t].'';
}
}
$query="INSERT INTO $tablename ($columnnames)VALUES($columnvalues)";
// // echo $query.'<br>';
$run=mysql_query($query)or die(mysql_error());
}
};
function image_check_memory_usage($img, $max_breedte, $max_hoogte){
 if(file_exists($img)){
	$K64 = 65536; // number of bytes in 64K
	$memory_usage = memory_get_usage();
	$memory_limit = abs(intval(str_replace('M','',ini_get('memory_limit'))*1024*1024));
	$image_properties = getimagesize($img);
	$image_width = $image_properties[0];
	$image_height = $image_properties[1];
	$image_bits = $image_properties['bits'];
	$image_memory_usage = $K64 + ($image_width * $image_height * ($image_bits ) * 2);
	$thumb_memory_usage = $K64 + ($max_breedte * $max_hoogte * ($image_bits ) * 2);
	$memory_needed = intval($memory_usage + $image_memory_usage + $thumb_memory_usage);
 
 if($memory_needed > $memory_limit){
 ini_set('memory_limit',(intval($memory_needed/1024/1024)+5) . 'M');
 if(ini_get('memory_limit') == (intval($memory_needed/1024/1024)+5) . 'M'){
 return true;
 }else{
 return false;
 }
 }else{
 return true;
 }
	 }else{
	 return false;
 }
}
function fix_dirname($str){
 return str_replace('~',' ',dirname(str_replace(' ','~',$str)));
}

function fix_strtoupper($str){
 if( function_exists( 'mb_strtoupper' ) )
	return mb_strtoupper($str);
 else
	return strtoupper($str);
}


function fix_strtolower($str){
 if( function_exists( 'mb_strtoupper' ) )
	return mb_strtolower($str);
 else
	return strtolower($str);
}
function getSizeByFixedWidth($newWidth,$newHeight,$width,$height,$forceStretch)
 {
 // *** If forcing is off...
 if ($forceStretch===false) {

 // *** ...check if actual width is less than target width
 if ($width < $newWidth) {
 return array('optimalWidth' => $width, 'optimalHeight' => $height);
 }
 }

 $ratio = $height / $width;

 $newHeight = $newWidth * $ratio;

 //return $newHeight;
 return array('optimalWidth' => $newWidth, 'optimalHeight' => $newHeight);
 }
 function getSizeByFixedHeight($newWidth,$newHeight,$width,$height,$forceStretch)
 {
 // *** If forcing is off...
 if ($forceStretch===false) {

 // *** ...check if actual height is less than target height
 if ($height < $newHeight) {
 return array('optimalWidth' => $width, 'optimalHeight' => $height);
 }
 }

 $ratio = $width / $height;

 $newWidth = $newHeight * $ratio;

 //return $newWidth;
 return array('optimalWidth' => $newWidth, 'optimalHeight' => $newHeight);
 }
 function getSizeByAuto($newWidth,$newHeight,$width,$height,$forceStretch)
 # Purpose: Depending on the height, choose to resize by 0, 1, or 2
 # Param in: The new height and new width
 # Notes:
 #
 {
 // *** If forcing is off...
 if ($forceStretch===false) {

 // *** ...check if actual size is less than target size
 if ($width < $newWidth && $height < $newHeight) {
 return array('optimalWidth' => $width, 'optimalHeight' => $height);
 }
 }

 if ($height < $width)
 // *** Image to be resized is wider (landscape)
 {
 //$optimalWidth = $newWidth;
 //$optimalHeight= $getSizeByFixedWidth($newWidth);

 $dimensionsArray = $getSizeByFixedWidth($newWidth, $newHeight);
 $optimalWidth = $dimensionsArray['optimalWidth'];
 $optimalHeight = $dimensionsArray['optimalHeight'];
 }
 elseif ($height > $width)
 // *** Image to be resized is taller (portrait)
 {
 //$optimalWidth = $getSizeByFixedHeight($newHeight);
 //$optimalHeight= $newHeight;

 $dimensionsArray = $getSizeByFixedHeight($newWidth, $newHeight);
 $optimalWidth = $dimensionsArray['optimalWidth'];
 $optimalHeight = $dimensionsArray['optimalHeight'];
 }
 else
 // *** Image to be resizerd is a square
 {

 if ($newHeight < $newWidth) {
 //$optimalWidth = $newWidth;
 //$optimalHeight= $getSizeByFixedWidth($newWidth);
 $dimensionsArray = $getSizeByFixedWidth($newWidth, $newHeight);
 $optimalWidth = $dimensionsArray['optimalWidth'];
 $optimalHeight = $dimensionsArray['optimalHeight'];
 } else if ($newHeight > $newWidth) {
 //$optimalWidth = $getSizeByFixedHeight($newHeight);
 //$optimalHeight= $newHeight;
 $dimensionsArray = $getSizeByFixedHeight($newWidth, $newHeight);
 $optimalWidth = $dimensionsArray['optimalWidth'];
 $optimalHeight = $dimensionsArray['optimalHeight'];
 } else {
 // *** Sqaure being resized to a square
 $optimalWidth = $newWidth;
 $optimalHeight= $newHeight;
 }
 }

 return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
 }
function getDimensions($newWidth, $newHeight, $width,$height,$forceStretch,$option)
 #
 # To clarify the $option input:
 # 0 = The exact height and width dimensions you set.
 # 1 = Whatever height is passed in will be the height that
 # is set. The width will be calculated and set automatically
 # to a the value that keeps the original aspect ratio.
 # 2 = The same but based on the width.
 # 3 = Depending whether the image is landscape or portrait, this
 # will automatically determine whether to resize via
 # dimension 1,2 or 0.
 {

 switch (strval($option))
 {
 case '0':
 case 'exact':
 $optimalWidth = $newWidth;
 $optimalHeight= $newHeight;
 break;
 case '1':
 case 'portrait':
 $dimensionsArray = getSizeByFixedHeight($newWidth, $newHeight,$width,$height,$forceStretch);
 $optimalWidth = round($dimensionsArray['optimalWidth'],0, PHP_ROUND_HALF_UP);
 $optimalHeight = round($dimensionsArray['optimalHeight'],0, PHP_ROUND_HALF_UP);
 break;
 case '2':
 case 'landscape':
 $dimensionsArray = getSizeByFixedWidth($newWidth,$newHeight,$width,$height,$forceStretch);
 $optimalWidth = round($dimensionsArray['optimalWidth'],0, PHP_ROUND_HALF_UP);
 $optimalHeight = round($dimensionsArray['optimalHeight'],0, PHP_ROUND_HALF_UP);
 break;
 case '3':
 case 'auto':
 $dimensionsArray = getSizeByAuto($newWidth, $newHeight,$width,$height,$forceStretch);
 $optimalWidth = round($dimensionsArray['optimalWidth'],0, PHP_ROUND_HALF_UP);
 $optimalHeight = round($dimensionsArray['optimalHeight'],0, PHP_ROUND_HALF_UP);
 break;
 }

 return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
 }
function imageResize($imagefile,$newwidth,$newheight,$imgextension,$watermarkval,$watermarkimg,$imgpath){
/*
My image Resizer
resizes images based on the above passed parameters
####First Parameter $imagefile
-the image itself 
####Second Parameter $newwidth
-the new width of the image
####Third Parameter $newheight
-the new height of the image
####Fourth Parameter $imgextension
-the type of the image
####Fifth Parameter $watermarkvalue
-the watermark value for the image, values are"true" if water mark is
to be added to the image or "false" if not.
####Sixth Parameter $watermarkimg
-watermark image file path
####Seventh Parameter $imgpath
-path to place the new file in, must have the name of the new file
present in it
*/
if(is_array($imagefile)){
	// $imagefilename=$imagefile[2];
	$originalimage=$imagefile[0];
	$imagefile2=$imagefile[1];
list($width,$height)=getimagesize($originalimage);
$forceStretch=true;
if ($newwidth==""&&$newheight!=="") {
$option=1;$newwidth=0;

}elseif($newwidth!==""&&$newheight==""){
$option=2;
$newheight=0;	
}elseif ($newwidth!==""&&$newwidth>0&&$newheight!==""&&$newheight>0) {
	# code...
	$option=0;
}else{
	$option=3;
}
}else{
list($width,$height)=getimagesize($imagefile);
}

$tmp="oops something went wrong";

if($imgextension=="jpeg"||$imgextension=="jpg"){
if($watermarkval===true){
if(is_array($imagefile)){

}else{

}
}else{
if(is_array($imagefile)){
	// $matchtwo=checkExistingFile($uploadimgpaths[$i],$imagename2);
if(image_check_memory_usage($originalimage,$newwidth,$newheight)){
	$magicianObj = new imageLib($originalimage);
	// echo $newwidth.$newheight.$option."<br>here";
	$magicianObj -> resizeImage($newwidth, $newheight, "".$option."");
	$magicianObj -> saveImage($imagefile2,80);
	return true;
 }
}else{
	$src = imagecreatefromjpeg($imagefile);
	$tmp=imagecreatetruecolor($newwidth,$newheight);
	imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
	imagejpeg($tmp,$imgpath,100);
	imagedestroy($src);
	imagedestroy($tmp);
	$tmp="successful";	
}




}
}elseif ($imgextension=="gif") {
if($watermarkval===true){

}else{
if(is_array($imagefile)){
$gr = new gifresizer; //New Instance Of GIFResizer 
$gr->temp_dir = "frames"; //Used for extracting GIF Animation Frames
$dimensionwork=getDimensions($newwidth,$newheight,$width,$height,$forceStretch,$option); 
$cwidth=$dimensionwork['optimalWidth'];
$cheight=$dimensionwork['optimalHeight'];
$gr->resize($originalimage,$imagefile2,$cheight,$cheight); //Resizing the animation into a new file. 
}else{
$src = imagecreatefromgif($imagefile);
	$tmp=imagecreatetruecolor($newwidth,$newheight);
	imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
	imagegif($tmp,$imgpath);
	imagedestroy($src);
	imagedestroy($tmp);
	$tmp="successful";	
}



}
	

}elseif ($imgextension=="png") {
if($watermarkval===true){

}else{
if(is_array($imagefile)){
if(image_check_memory_usage($originalimage,$newwidth,$newheight)){

	$magicianObj = new imageLib($originalimage);
	$magicianObj -> resizeImage($newwidth, $newheight, "".$option."");
	$magicianObj -> saveImage($imagefile2,80);
	return true;
 }
}else{
	$src = @imagecreatefrompng($imagefile);
	$tmp=imagecreatetruecolor($newwidth,$newheight);
	imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
	imagePNG($tmp,$imgpath);
	imagedestroy($src);
	imagedestroy($tmp);
	$tmp="successful";
}
}
}
return $tmp;
}
function checkExistingFile($filepath,$filename){
$retvals=array();
$files=array();
$dhandle= opendir($filepath);
if($dhandle){
while(false !== ($fname = readdir($dhandle))){
if(($fname!='.') && ($fname!='..') && ($fname!= basename($_SERVER['PHP_SELF']))){
//check if the file in mention is a directory , if no add it to the files array
$files[]=(is_dir("./$fname")) ? "":$fname;
}
$totalfiles=count($files);
$retvals['totalfilecount']=$totalfiles;
$arraycount=$totalfiles-1;
$match="false";
for($i=0;$i<=$arraycount;$i++){
if($filename==$files[$i]){
$match="true";
}
}
}
$testfile=$filepath.$filename;
$httppresent=strpos($filepath,"http://localhost/");
$httppresent2=strpos($filepath,"http://");
$httppresent3=strpos($filepath,"ssl://");
if($httppresent===0){
	$patharr=explode("/",$filepath);
	$count=count($patharr);
	$end=$count-1;
	$start=3;
	$construct="";
	for($i=4;$i<=$end;$i++){
		$construct==""?$construct.="../".$patharr[$i]."/":($construct!==""&&$i!==$end?$construct.=$patharr[$i]."/":($construct!==""&&$i==$end?$construct.=$patharr[$i]:$construct=$construct));
	}
	echo $construct;
	$testfile=$construct;
}elseif ($httppresent===0) {
	# code...
	$patharr=explode("/",$filepath);
	$count=count($patharr);
	$end=$count-1;
	$start=3;
	$construct="";
	for($i=3;$i<=$end;$i++){
		$construct==""?$construct.="../".$patharr[$i]."/":($construct!==""&&$i!==$end?$construct.=$patharr[$i]."/":($construct!==""&&$i==$end?$construct.=$patharr[$i]:$construct=$construct));
	}
	// echo $construct;
	$testfile=$construct;
}
if(file_exists($testfile)){
$match="true";	
// echo $match;
}
$retvals['matchval']=$match;
$extensions=getExtension($filename);
$extension=strtolower($extensions);
closedir($dhandle);
return $retvals;
}
}
function getRelativePathToSnippets($filepath){
$retvals=array();
$httppresent=strpos($filepath,"http://localhost/");
$httppresent2=strpos($filepath,"http://");
$httppresent3=strpos($filepath,"ssl://");
if($httppresent===0){
	$patharr=explode("/",$filepath);
	$count=count($patharr);
	$end=$count-1;
	$start=3;
	$construct="";
	for($i=4;$i<=$end;$i++){
		$construct==""?$construct.="../".$patharr[$i]."/":($construct!==""&&$i!==$end?$construct.=$patharr[$i]."/":($construct!==""&&$i==$end?$construct.=$patharr[$i]:$construct=$construct));
	}
	echo $construct;
	$retvals['testfile']=$construct;
}elseif ($httppresent===0) {
	# code...
	$patharr=explode("/",$filepath);
	$count=count($patharr);
	$end=$count-1;
	$start=3;
	$construct="";
	for($i=3;$i<=$end;$i++){
		$construct==""?$construct.="../".$patharr[$i]."/":($construct!==""&&$i!==$end?$construct.=$patharr[$i]."/":($construct!==""&&$i==$end?$construct.=$patharr[$i]:$construct=$construct));
	}
	// echo $construct;
	$retvals['testfile']=$construct;
}else{
	$retvals['testfile']=$filepath;
}
return $retvals;
}
function getFileType($filename){
	$entrytype="";
	$filerealname=$_FILES[''.$filename.'']['name'];
	//get img binaries
	$file=$_FILES[''.$filename.'']['tmp_name'];
	//get image type
	$filetype=$_FILES[''.$filename.'']['type'];
	//get image data size
	$filesize=$_FILES[''.$filename.'']['size'];
	$extension=getExtension($filerealname);
	$extension=strtolower($extension);
	if ($extension=="jpg"||$extension=="jpeg"||$extension=="png"||$extension=="gif") {
		# code...
		$entrytype="image";
	} elseif($extension=="mp4"||$extension=="3gp"||$extension=="flv"||$extension=="swf"||$extension=="webm") {
		$entrytype="video";		
	}elseif ($extension=="doc"||$extension=="docx"||$extension=="xls"||$extension=="xlsx"||$extension=="ppt"||$extension=="pptx") {
		# code...
		$entrytype="office";
	}elseif ($extension=="pdf") {
		# code...
		$entrytype="pdf";

	}elseif($extension=="mp3"||$extension=="ogg"||$extension=="wav"||$extension=="amr"){
		$entrytype="audio";
	}else{
		$entrytype="others";
	}
	return $entrytype;
}

function simpleUpload($filename,$path){
	$outs=array();
	$fileoutnormal="";
	$filerealname=$_FILES[''.$filename.'']['name'];
	//get img binaries
	$file=$_FILES[''.$filename.'']['tmp_name'];
	//get image type
	$filetype=$_FILES[''.$filename.'']['type'];
	//get image data size
	$anothersize="";
	$filefirstsize=$_FILES[''.$filename.'']['size'];
	$filesize=$filefirstsize/1024;
//// echo $filefirstsize;
$filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
if(strlen($filesize)>3){
$filesize=$filesize/1024;
$filesize=round($filesize,2);	
$filesize="".$filesize."MB";
}else{
	$filesize="".$filesize."KB";
}
	$filename2 = stripslashes($filerealname);
	$extension=getExtension($filename2);
	$extension=strtolower($extension);
	$realimage=explode(".",$filename2);
	$dataname=$realimage[0];
	$match=checkExistingFile($path,$filerealname);
				if($match['matchval']=="true"){
					$nextentry=md5($match['totalfilecount']+1);
					$filerealname2=$dataname.$nextentry.".".$extension;
				}else{
					$filerealname2=$filerealname;
				}
			$filelocation=$path.$filerealname2;

move_uploaded_file("$file","$filelocation");
			$fileoutnormal=str_replace("../", "./", $filelocation);
			$fileoutnormal==""?$fileoutnormal=$filelocation:$fileoutnormal=$fileoutnormal;
$outs['filelocation']=$filelocation;
$outs['fileoutnormal']=$fileoutnormal;
$outs['filesize']=$filesize;
$outs['realsize']=$filefirstsize;
return $outs;
}

function genericImageUpload($imagefile,$uploadtype,$uploadimgpaths,$uploadimgsizes,$acceptedsize){
/*
My upload manager.
-uploads image to server and returns the upload path of the image in an array for database storage
####First parameter $imagefile
-the image file to be uploaded obviously this.
####Second parameter $uploadtype
-type of upload, values are "single" and "varying",
*single=simple image migration to a folder using move_uploaded file 
function, end of story
*varying=for image upload in multiple sizes...this is inclusive of the 
original images size.
####Third parameter $uploadimgpaths
-this is an array containing the path to which to uplaod the image to,
it can also contain the paths for the varying image sizes if varying is
specified.
####Fourth parameter $uploadimgsizes
-this is an array of values in the form "width,height"..i.e "400,300"
it can also hold the value of "default" meaning that for that entry the 
original size of the image is to be used for that entry
####Fifth parameter $acceptedsize
-The accepted default size of the image when in multiples or otherwise
*/
//get image name
	$imagename=$_FILES[''.$imagefile.'']['name'];
	//get img binaries
	$image=$_FILES[''.$imagefile.'']['tmp_name'];
	//get image type
	$imagetype=$_FILES[''.$imagefile.'']['type'];
	//get image data size
	$imagesize=$_FILES[''.$imagefile.'']['size'];

	if($imagename!==""){

	list($curimgwidth,$curimgheight)=getimagesize($image);
		
	$filename = stripslashes($_FILES[''.$imagefile.'']['name']);
	$extension=getExtension($filename);
	$extension=strtolower($extension);
	$realimage=explode(".",$filename);
	$imgname=$realimage[0];
	$imgpathcount=count($uploadimgpaths);
	$imgsizecount=count($uploadimgsizes);
	$defaultimglocation=$uploadimgpaths[0];
	$defaultsize=$uploadimgsizes[0];
	$path=array();
	if ($uploadtype=="varying") {
		if($imgpathcount>1&&$imgpathcount==$imgsizecount){
			$match=checkExistingFile($defaultimglocation,$imagename);
				if($match['matchval']=="true"){
					$nextentry=md5($match['totalfilecount']+1);
					$imagename2=$imgname.$nextentry.".".$extension;
				}else{
					$imagename2=$imagename;
				}
				//upload original image
			$imagelocation=$defaultimglocation.$imagename2;
			move_uploaded_file("$image","$imagelocation");
			list($testwidth,$testheight)=getimagesize($imagelocation);
			$path[]=$imagelocation;
			$reallength=$imgpathcount-1;
				$locationentry=array();
			for($i=0;$i<=$reallength;$i++){
				if($i!==0&&$uploadimgsizes[$i]!=="default"){
				//check to make sure no conflict in next directory folder using the name
				//of the currently uploaded file to maintain consistency
				$match=checkExistingFile($uploadimgpaths[$i],$imagename);
				// echo $match['matchval']."<br>".$uploadimgpaths[$i].$imagename2;
				if($match['matchval']=="true"){
					$nextentry=md5($match['totalfilecount']+1);
					$imagename2=$imgname.$nextentry.".".$extension;
					// echo "in here";
				}else{
					$imagename2=$imagename2;
				}

				$curpath=$uploadimgpaths[$i].$imagename2;
				// echo $uploadimgsizes[$i]."the one".$curpath;
				$cursize=explode(",",$uploadimgsizes[$i]);
				$newwidth=$cursize[0];
				$newheight=$cursize[1];
				unset($locationentry);
				$locationentry[]=$imagelocation;
				$locationentry[]=$curpath;
				imageResize($locationentry,$newwidth,$newheight,$extension,false,"",$curpath);
				$path[]=$curpath;
				}
			}
			// echo "<br>".$reallength." thereallengthafter<br>";

// move_uploaded_file("$image","$imagelocation");
		}else{
			$match=checkExistingFile($defaultimglocation,$imagename);
				if($match['matchval']=="true"){
					$nextentry=md5($match['totalfilecount']+1);
					$imagename2=$imgname.$nextentry.".".$extension;
				}else{
					$imagename2=$imagename;
				}
			$imagelocation=$defaultimglocation.$imagename2;
			move_uploaded_file("$image","$imagelocation");
			$path[]=$imagelocation;
		}		
	}else{
$match=checkExistingFile($defaultimglocation,$imagename);
				if($match['matchval']=="true"){
					$nextentry=md5($match['totalfilecount']+1);
					$imagename2=$imgname.$nextentry.".".$extension;
					echo "in here";
				}else{
					$imagename2=$imagename;
				}
		$imagelocation=$defaultimglocation.$imagename2;
		move_uploaded_file("$image","$imagelocation");
		$exists=file_exists($imagelocation);
		$exists===true?$exist="true":$exist="false";
		echo $exist;
		if($acceptedsize!==""){
			$match=checkExistingFile($defaultimglocation,$imagename2);
			if($match['matchval']=="true"){
				$nextentry=md5($match['totalfilecount']+1);
				$imagename2=$imgname.$nextentry.".".$extension;
			}else{
				$imagename2=$imagename;
			}
			$curpath=$defaultimglocation.$imagename2;
			echo $curpath;
			$acceptedsize=explode(",",$acceptedsize);
			$acceptedwidth=$acceptedsize[0];
			$acceptedheight=$acceptedsize[1];
			$locationentry=array();
			$locationentry[]=$imagelocation;
			$locationentry[]=$curpath;
			imageResize($locationentry,$acceptedwidth,$acceptedheight,$extension,false,"",$curpath);
			unlink($imagelocation);			
			$imagelocation=$curpath;
		}
	$path[]=$imagelocation;
	}
}else{
	$path="no image";
}
			return $path;
	
}
function produceImageFitSize($location,$contwidth,$contheight,$auto){
$output=array();
$output['width']="20px";
$output['height']="20px";
$output['style']="";
$output['truewidth']="";
$output['trueheight']="";
if($location!==""&&$contwidth!==""&&$contheight!==""){
	$imagefile=".".$location;
list($width,$height)=getimagesize($imagefile);
$output['truewidth']=$width;
$output['trueheight']=$height;
if($contwidth>$contheight){

	if($width>$height&&$height>=$contheight){
		$width=$contwidth;
$style='cursor:pointer;height:'.$contheight.'px;margin:auto;';
}elseif($width<$height&&$height>=$contheight){
	$width=$width/2;
	if($width>$contwidth){
		$width=$contheight-10;
	}elseif($width<$contwidth && $width>$contheight){
		$width=$width-120;
	}
$style='cursor:pointer;width:'.$width.'px;height:'.$contheight.'px;margin:auto;';	
}elseif ($width>$height&&$height<$contheight) {
		# code...
		$difference=$contheight-$height;
		$margintop=$difference/2;
		if($auto=="on"){
$style='cursor:pointer;width:'.$width.'px;height:'.$height.'px;margin-top:auto;';	
		}else{			
$style='cursor:pointer;width:'.$width.'px;height:'.$height.'px;margin-top:'.$margintop.';';	
		}
}

}elseif ($contwidth<$contheight) {
	# code...
}
}
$output['width']=$width;
$output['height']=$height;
$output['style']=$style;
return $output;
}
function getNextId($tablename){
$query="SELECT * FROM $tablename";
$run=mysql_query($query);
$numrows=mysql_num_rows($run);
$nextid=$numrows+1;
return $nextid;
}
function getNextIdExplicit($tablename){
$query="SELECT * FROM $tablename ORDER BY id DESC";
$run=mysql_query($query);
$row=mysql_fetch_assoc($run);
// $numrows=mysql_num_rows($run);
$nextid=$row['id']+1;
return $nextid;
}
function getSingleMediaData($partid,$parttype){
$ordervalues="";
if(is_array($partid) && is_array($parttype)){
//proceed to generate combined test params for valid entry
$orderfieldvals=count($parttype)-1;
for($i=0;$i<=$orderfieldvals;$i++){
	if($i!==$orderfieldvals){
$ordervalues.="".$parttype[$i]."='".$partid[$i]."' AND ";
	}else{
$ordervalues.=" ".$parttype[$i]."='".$partid[$i]."'";
	}
$query="SELECT * FROM media WHERE $ordervalues";
}
}else{
$query="SELECT * FROM media WHERE $parttype=$partid";
}
$run=mysql_query($query)or die(mysql_error());
$numrows=mysql_num_rows($run);
$row=array();
$row=mysql_fetch_assoc($run);
return $row;
}


function getSingleMediaDataTwo($partid){
		$numrows=0;
	
	$query="SELECT * FROM media WHERE id=$partid";
	
	$run=mysql_query($query)or die(mysql_error());
	$numrows=mysql_num_rows($run);
	
	
$row=array();
$row['adminoutput']="";
$row['vieweroutput']="";
	if($numrows>0){
$row=mysql_fetch_assoc($run);
$id=$row['id'];
$ownerid=$row['ownerid'];
$maintype=$row['maintype'];
$mediatype=$row['mediatype'];
$category=$row['categoryid'];
$location=$row['location'];
$details=$row['details'];
$filesize=$row['filesize'];
$width=$row['width'];
$height=$row['height'];
$title=$row['title'];
$status=$row['status'];
	}
return $row;
}

function produceOptionDates($from,$to,$display){
$output='<option value="">--'.$display.'--</option>';
if($to=="current"){
$to=date('Y');
for($i=$from;$i<=$to;$i++){
	$output.='<option value="'.$i.'">'.$i.'</option>';
}
}else{
for($i=$from;$i<=$to;$i++){
	$output.='<option value="'.$i.'">'.$i.'</option>';
}	
}
return $output;
}
function produceStates($countryid,$stateid){
if(($countryid==""||$countryid==0)&&($stateid==""||$stateid==0)){ 
$query="SELECT * FROM states";
}
if(($countryid!==""&&$countryid!==0)&&($stateid==""||$stateid==0)){
$query="SELECT * FROM states where cid=$countryid"; 
}
if(($countryid==""||$countryid==0)&&($stateid!==""&&$stateid!==0)){
$query="SELECT * FROM states where id=$stateid"; 
}

$run=mysql_query($query)or die(mysql_error().'line 472');

$statetotal="";
$state="";
$row=array();
while ($row=mysql_fetch_assoc($run)) {
 # code...
 $statetotal.='<option value="'.$row['id'].'">'.$row['state'].'</option>';
 $state=$row['state'];
}
// $row2=mysql_fetch_array($run);

$row['statename']=$state;
$row['selectionoutput']='
<select name="state" class="curved2">
<option value="">--State--</option>
'.$statetotal.'
</select>
';
$row['selectionoptions']='
'.$statetotal.'
';
return $row;
}
function calenderOut($day,$month,$year,$viewer,$targetcontainermain,$theme,$controlquery){
  $occurencedates=array();
  $controloutputarr=array();
  global $host_addr,$host_target_addr;
    $viewtype="";
    //get current date value.
    $currentday=date('d');
    $currentmonth=date('m');
    $currentyear=date('Y');
    $currentdate="".$currentday."-".$currentmonth."-".$currentyear."";
  if(is_array($targetcontainermain)){
    // echo "in here";
    $targetcontainer=$targetcontainermain[0];
    //for miscellaneous customization for any other entry you want to customize
    // get occurencedates
    $controlviewtype=$targetcontainermain[1];
    // echo $controlviewtype;
    $occurencedates=explode(",",$controlviewtype);
    $occurencedata="";
    if(isset($targetcontainermain[2])){
      $controlviewtypetwo=$targetcontainermain[2];
      $occurencedata=explode(">|<",$targetcontainermain[2]);
    }
    if(isset($targetcontainermain['viewtype'])&&!isset($targetcontainermain[2])){
      $viewtype=$targetcontainermain['viewtype'];
      $outsevent=getAllEvents("viewer",'All',"$viewtype",$currentmonth,$currentyear);
      $controlviewtypetwo=$outsevent['validevents'];
      $controloutputarr=$outsevent['validarr'];
      $occurencedata=explode(">|<",$outsevent['validevents']);
      // echo $controloutputarr['2015-06-18']." a valid sample tpo<br>";
    }
		// echo "<br>".$occurencedates[1];
	}else{
		$controlviewtype="";
		$targetcontainer=$targetcontainermain;
	}
	$controloption='data-control="'.$controlviewtype.'" data-viewtype="'.$viewtype.'"';
  $row=array();
  $calHold="";
  $caltop="";
  $calDaynameholdcalday="";
  $calDaysHoldcalday="";
  $calDaysHoldweekend="";
  $calInfobox="";
  $fullstyleout="";
  if($theme=="green"){
    $calHold='style="background:#053307;"';
    $caltop='style="color:#05D558;"';
    $calDaynameholdcalday='style="color:#05D558;"';
    $calDaysHoldcalday='style="color:#18FA7B;"';
    $calDaysHoldweekend='style="background:#D59D28;color:#fff;text-shadow:0px 0px 3px #DA2020;"';
    $calInfobox='style="color:#05D558;"';
    $fullstyleout='';
  }elseif($theme=="red"){
    $calHold='style="background:#630202;"';
    $caltop='style="color:#fffac3;"';
    $calDaynameholdcalday='style="color:#fffac3;"';
    $calDaysHoldcalday='style="color:#fff500;"';
    $calDaysHoldweekend='style="background:#C41010;color:#fff;text-shadow:1px 1px 0px #222;"';
    $calInfobox='style="color:#fff;"';
    $fullstyleout='
    <style type="text/css">
    #calDaysHold div#calDay:hover {
    background-color: #DB7733;
    }
    </style>';
  }elseif($theme=="grey"){
    $calHold='style="background:#a7a7a7;"';
    $caltop='style="color:#fffac3;"';
    $calDaynameholdcalday='style="color:#fffac3;"';
    $calDaysHoldcalday='style="color:#fff500;"';
    $calDaysHoldweekend='style="background:#666667;color:#fff;text-shadow:1px 1px 0px #222;"';
    $calInfobox='style="color:#fff;"';
    $fullstyleout='';
  }
  $row['errorout']="Sorry you seem to have either left a value empty, or entered the wrong type of required data";

  if($day!=="" && $month!=="" && $year!==""){
    //convert the month value into a numeric type if it is not already numeric
  	$entrymonth=$month;
    //control values exceeding or less than the total number of months in the year
  	if($entrymonth>12){
  		$entrymonth=12;

  	}else if ($entrymonth<1) {
  		# code...
  		$entrymonth=1;
  	}
  	if($entrymonth>0 && $entrymonth<13){
    $firstdate="1-".$entrymonth."-".$year."";
    $firstdate=strtotime($firstdate);
  	$entrymonth2=date('F',$firstdate);
  	}
  $row['errorout']="no error";
  $monthcount=31;
  $firstdate="1-".$entrymonth."-".$year."";
  $firstdate=strtotime($firstdate);
  $msd=date('D',$firstdate);
  $lst=0;
  $ledt="nada";
  $monthcount=date('t',$firstdate);
  //get number of days that
  $msd=="Mon"?$lst=1:($msd=="Tue"?$lst=2:($msd=="Wed"?$lst=3:($msd=="Thu"?$lst=4:($msd=="Fri"?$lst=5:($msd=="Sat"?$lst=6:$ledt)))));
  $excessdays="";
  $realdays="";
  if($lst>0){
    for($i=1;$i<=$lst;$i++){
      if($i==1){
        $excessdays.='
        <div id="calDay" name="" '.$calDaysHoldweekend.'title=""></div>
        ';  
      }else{
        $excessdays.='
        <div id="calDay" name="" title=""></div>
        ';  
      }
    }
  }


  for($t=1;$t<=$monthcount;$t++){
    $testdate=''.$t.'-'.$entrymonth.'-'.$year.'';
    $entrymonth<10&&strlen($entrymonth)<2?$testdatemonitor=''.$t.'-0'.$entrymonth.'-'.$year.'':$testdatemonitor=$testdate;
    // create acceptable date for valid arr entry
    $entrymonth<10&&strlen($entrymonth)<2?$emtwo='0'.$entrymonth:$emtwo=$entrymonth;
    $t<10&&strlen($t)<2?$tt='0'.$t:$tt=$t;
    $tdtwo=''.$year.'-'.$emtwo.'-'.$tt.'';
    // end
    $daytype="".date("l",mktime(0,0,0,$entrymonth,$t,$year))."";
    $today="";
    // echo $testdatemonitor." ".$occurencedates[0]." ".$occurencedates[1]." $testdate<br>";
    // echo " $tdtwo - tdtwo <br>";
    $calDaysHoldweekend2=$calDaysHoldweekend;
    $datapoint="";
    $dataevent="";
    if($t==$currentday&&$entrymonth==$currentmonth&&$year==$currentyear){
      $today="today";
      $datapoint=$today;
    }
      // echo $testdatemonitor."- testdate<br>";
      // array_key_exists(key, search)
      // echo array_key_exists("$tdtwo", $controloutputarr)." controloutputarr<br>";
      // echo $occurencedates["$testdatemonitor"];
    if(false!== $key=array_search($testdatemonitor, $occurencedates)){
      // echo $testdatemonitor;
      $datapoint="eventdate";
      // echo $tdtwo." - tdtwo<br>";
      if(isset($controloutputarr[''.$tdtwo.''])){
        $eventcontentouttwo=explode("<|>",$controloutputarr[''.$tdtwo.'']);   
      }else{
        $eventcontentouttwo=array();
      }
      // $eventcontentout=explode("<|>",$occurencedata[$key]);
      $eventdetails="";
      /*for($e=0;$e<count($eventcontentout);$e++){
        $eventdetails.=$eventcontentout[$e];
      }*/
      for($e=0;$e<count($eventcontentouttwo);$e++){
        $eventdetails.=$eventcontentouttwo[$e].PHP_EOL;
      }
      $dataevent=':'.PHP_EOL.' '.$eventdetails;
    }

    if($daytype=="Sunday") {
    	$teser=0;
      $today=="today"?$realdays.='<div id="calDay" name="'.$testdate.'" data-point="'.$datapoint.'" data-toggle="tooltip" title="'.$t.'-'.$entrymonth.'-'.$year.' '.$dataevent.'" data-target="'.$targetcontainer.'">'.$t.'</div>':$realdays.='<div id="calDay" name="'.$testdate.'" '.$calDaysHoldweekend.' data-point="'.$datapoint.'" title="'.$t.'-'.$entrymonth.'-'.$year.' '.$dataevent.'" data-target="'.$targetcontainer.'">'.$t.'</div>';

    }

    if($daytype!=="Sunday"){
      $realdays.='
        <div id="calDay" name="'.$testdate.'" '.$calDaysHoldcalday.' data-point="'.$datapoint.'" data-toggle="tooltip" title="'.$t.'-'.$entrymonth.'-'.$year.' '.$dataevent.'" data-target="'.$targetcontainer.'">'.$t.'</div>
      ';	
    }
  }
  $totaldays=$excessdays.$realdays;
  //outputs
  $row['totaldaysout']=$totaldays;
  $admindisplay="";
  $adminstyle="";
  if($viewer=="admin"){
  $admindisplay=".";
  $adminstyle='style="float:none;"';
  }
  $row['formoutput']='
      '.$fullstyleout.'
  		<div id="calHold" '.$calHold.' '.$adminstyle.'>
  			<div id="caltop" '.$caltop.'>
  				<div id="calmonthpointer" name="calpointleft" data-target="'.$targetcontainer.'" data-theme="'.$theme.'" '.$controloption.'>
  					<img src="'.$admindisplay.'./images/leftarrow.png" class="total"/>
  				</div>
  				<div id="calDispDetails" data-curmonth="'.$entrymonth.'" data-year="'.$year.'">
  					'.$entrymonth2.', '.$year.'
  				</div>
  				<div id="calmonthpointer" name="calpointright"data-target="'.$targetcontainer.'" data-theme="'.$theme.'" '.$controloption.'>
  					<img src="'.$admindisplay.'./images/rightarrow.png" class="total"/>
  				</div>
  			</div>			
  			<div id="calBody">
  				<div id="calDaynamehold">
  					<div id="calDay" '.$calDaynameholdcalday.'>Sun</div>
  					<div id="calDay" '.$calDaynameholdcalday.'>Mon</div>
  					<div id="calDay" '.$calDaynameholdcalday.'>Tue</div>
  					<div id="calDay" '.$calDaynameholdcalday.'>Wed</div>
  					<div id="calDay" '.$calDaynameholdcalday.'>Thu</div>
  					<div id="calDay" '.$calDaynameholdcalday.'>Fri</div>
  					<div id="calDay" '.$calDaynameholdcalday.'>Sat</div>
  				</div>
  				<div id="calDaysHold"name="'.$targetcontainer.'">
  					<!--<div id="calDay" name="theday-themonth-theyear" title="The date goes here:-12 Appointments">1</div>-->
  					'.$totaldays.'
  				</div>
  			</div>
  			<div id="calInfobox" '.$calInfobox.'>
  				Click on a day to choose or view it.
  			</div>
  		</div>
  ';
  $row['totaloutput']='
      '.$fullstyleout.'
  		<div id="calHold">
  			<div id="caltop">
  				<div id="calmonthpointer" name="calpointleft" data-target="'.$targetcontainer.'" '.$controloption.'>
  					<img src="'.$admindisplay.'./images/leftarrow.png" class="total"/>
  				</div>
  				<div id="calDispDetails" data-curmonth="'.$entrymonth.'" data-year="'.$year.'">
  					'.$entrymonth2.', '.$year.'
  				</div>
  				<div id="calmonthpointer" name="calpointright" data-target="'.$targetcontainer.'" '.$controloption.'>
  					<img src="'.$admindisplay.'./images/rightarrow.png" class="total"/>
  				</div>
  			</div>			
  			<div id="calBody">
  				<div id="calDaynamehold">
  					<div id="calDay">Sun</div>
  					<div id="calDay">Mon</div>
  					<div id="calDay">Tue</div>
  					<div id="calDay">Wed</div>
  					<div id="calDay">Thu</div>
  					<div id="calDay">Fri</div>
  					<div id="calDay">Sat</div>
  				</div>
  				<div id="calDaysHold" name="'.$targetcontainer.'">
  					<!--<div id="calDay" name="theday-themonth-theyear" title="The date goes here:-12 Appointments">1</div>-->
  					'.$totaldays.'
  				</div>
  			</div>
  			<div id="calInfobox" '.$calInfobox.'>
  				Click on the day to view schedule for choosen date.
  			</div>
  		</div>
  ';
  }
  return $row;
}
function autoPost($postdata){


$autoposter = new SocialAutoPoster();

$facebook = $autoposter->getApi('facebook',array(
 'page_id' => 'PAGE_ID',
 'appid' => '578838318855511',
 'appsecret' => '293d89590646f6417c028fc0161d183d'
));

$facebook->getLoginBox();
$facebook->postToWall('Hello FB');
$facebook->getErrors();

$twitter = $autoposter->getApi('twitter',array(
 'consumer_key' => 'Xze6tCJkHiIh0y01WXg',
 'consumer_secret' => '1Fk8GCNcX9NLePCqP9e9RbgFRUCDqThoS8o6G4Lo',
 'access_token' => '522331943-jM11ekc6ud5shTnEIYKzFgUDadhr6F5SgBRFrWfx',
 'access_secret' => 'NRVPprxY5HfSGQz8W0T4uZxKjhHAd7uJa1CdQBhvhVJIt'
));

$twitter->postToWall('Hello Twitter, this is a test post from Social Auto Poster tool');
$twitter->getErrors();

$googleplus = $autoposter->getApi('googleplus',array(
 'email' => 'franklyspeakingwithm.afolabi@gmail.com',
 'pass' => 'gotjesus1'
));

$googleplus->begin('115702519121823219689');
$googleplus->postToWall('First test of the Social Auto Poster v1.1');
$googleplus->postToWall('Second test of the Social Auto Poster v1.1');
$googleplus->end();
$googleplus->isHaveErrors();
// // echo $postdata;
}
// autoPost("something");
function postTwitter($twitter_message) {
 // Use Matt Harris' OAuth library to make the connection
 // This lives at: [url]https://github.com/themattharris/tmhOAuth[/url]
 
 // Authorisation values required for posting to your personal twitter account. Replace * with your details but remain inside the ''
	 	// user_token may be referred to as Access Token 
 		// user_secret may be referred to as Access Token Secret
 	$connection = new tmhOAuth(array(
 	'consumer_key' => 'Xze6tCJkHiIh0y01WXg',
 'consumer_secret' => '1Fk8GCNcX9NLePCqP9e9RbgFRUCDqThoS8o6G4Lo',
 'user_token' => '522331943-jM11ekc6ud5shTnEIYKzFgUDadhr6F5SgBRFrWfx',
 'user_secret' => 'NRVPprxY5HfSGQz8W0T4uZxKjhHAd7uJa1CdQBhvhVJIt',
 	)); 
 
 // Connect to Twitter and post message
 	$connection->request('POST', 
 	 $connection->url('1/statuses/update'), 
 	 array('status' => $twitter_message));
 //Return success or not
 	return $connection->response['code'];
}
// postTwitter("Hello Twitter, posted using social auto poster, this is just a test tweet");
function paginate($query){
$pages = new Paginator; 
$run=mysql_query($query)or die(mysql_error()."Line 646");
$numrows=mysql_num_rows($run);
$pages->items_total = $numrows; 
$pages->mid_range = 9; 
$pages->paginate(); 
$pages->display_pages();
$row['limitout']=$pages->limit;
$query2=$query.$row['limitout'];
// // echo $pages;
$row=array();

$row['outputcount']=$numrows;
$row['pageout']=$pages->display_pages();
$row['usercontrols']="<br><span> ".$pages->display_jump_menu()." ".$pages->display_items_per_page()."</span>";
$row['limit']=$pages->limit;
return $row;
}
function paginateCustom($query,$param){
  require_once 'paginator.class.php';
  $pages = new Paginator;  
  if(!is_numeric($query)){
    $run=mysql_query($query)or die(mysql_error()."Line 646");
    $numrows=mysql_num_rows($run);
  }else if(is_numeric($query)){
    $numrows=$query;
  }
  // // echo$query;
  $pages->items_total = $numrows; 
  $pages->pagetype=$param; 
  $pages->mid_range = 9;  
  $pages->paginate();  
  $pages->display_pages();
  $row['limitout']=$pages->limit;
  $query2=$query.$row['limitout'];
  // // // echo$pages;
  $row=array();
  $row['outputcount']=$numrows;
  $row['pageout']=$pages->display_pages();
  $row['usercontrols']="<br><span> ".$pages->display_jump_menu()." ".$pages->display_items_per_page()."</span>";
  $row['limit']=$pages->limit;
  return $row;
}
function paginatejavascript($query){
$pages = new Paginator; 
$run=mysql_query($query)or die(mysql_error()."Line 861");
$numrows=mysql_num_rows($run);
$pages->items_total = $numrows; 
$pages->mid_range = 9; 
$pages->paginatejavascript(); 
$pages->display_pages();
$row['limitout']=$pages->limit;
$query2=$query.$row['limitout'];
// // echo $pages;
$row=array();

$row['outputcount']=$numrows;
$row['pageout']=$pages->display_pages();
$row['usercontrols']="<br><span> ".$pages->display_items_per_page_javascript()."</span>";
$row['limit']=$pages->limit;
return $row;
}
function socialTools($url,$type,$network){
$outs="";
if($network=="facebook"&& $type=="like"){
$outs='
<div class="fb-like" data-href="" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
';
}
}
/*Converts numbers to english word equivalent*/
function convertNumber($number)
{
    list($integer, $fraction) = explode(".", (string) $number);

    $output = "";

    if ($integer{0} == "-")
    {
        $output = "negative ";
        $integer    = ltrim($integer, "-");
    }
    else if ($integer{0} == "+")
    {
        $output = "positive ";
        $integer    = ltrim($integer, "+");
    }

    if ($integer{0} == "0")
    {
        $output .= "zero";
    }
    else
    {
        $integer = str_pad($integer, 36, "0", STR_PAD_LEFT);
        $group   = rtrim(chunk_split($integer, 3, " "), " ");
        $groups  = explode(" ", $group);

        $groups2 = array();
        foreach ($groups as $g)
        {
            $groups2[] = convertThreeDigit($g{0}, $g{1}, $g{2});
        }

        for ($z = 0; $z < count($groups2); $z++)
        {
            if ($groups2[$z] != "")
            {
                $output .= $groups2[$z] . convertGroup(11 - $z) . (
                        $z < 11
                        && !array_search('', array_slice($groups2, $z + 1, -1))
                        && $groups2[11] != ''
                        && $groups[11]{0} == '0'
                            ? " and "
                            : ", "
                    );
            }
        }

        $output = rtrim($output, ", ");
    }

    if ($fraction > 0)
    {
        $output .= " point";
        for ($i = 0; $i < strlen($fraction); $i++)
        {
            $output .= " " . convertDigit($fraction{$i});
        }
    }

    return $output;
}

function convertGroup($index)
{
    switch ($index)
    {
        case 11:
            return " decillion";
        case 10:
            return " nonillion";
        case 9:
            return " octillion";
        case 8:
            return " septillion";
        case 7:
            return " sextillion";
        case 6:
            return " quintrillion";
        case 5:
            return " quadrillion";
        case 4:
            return " trillion";
        case 3:
            return " billion";
        case 2:
            return " million";
        case 1:
            return " thousand";
        case 0:
            return "";
    }
}

function convertThreeDigit($digit1, $digit2, $digit3)
{
    $buffer = "";

    if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0")
    {
        return "";
    }

    if ($digit1 != "0")
    {
        $buffer .= convertDigit($digit1) . " hundred";
        if ($digit2 != "0" || $digit3 != "0")
        {
            $buffer .= " and ";
        }
    }

    if ($digit2 != "0")
    {
        $buffer .= convertTwoDigit($digit2, $digit3);
    }
    else if ($digit3 != "0")
    {
        $buffer .= convertDigit($digit3);
    }

    return $buffer;
}

function convertTwoDigit($digit1, $digit2)
{
    if ($digit2 == "0")
    {
        switch ($digit1)
        {
            case "1":
                return "ten";
            case "2":
                return "twenty";
            case "3":
                return "thirty";
            case "4":
                return "forty";
            case "5":
                return "fifty";
            case "6":
                return "sixty";
            case "7":
                return "seventy";
            case "8":
                return "eighty";
            case "9":
                return "ninety";
        }
    } else if ($digit1 == "1")
    {
        switch ($digit2)
        {
            case "1":
                return "eleven";
            case "2":
                return "twelve";
            case "3":
                return "thirteen";
            case "4":
                return "fourteen";
            case "5":
                return "fifteen";
            case "6":
                return "sixteen";
            case "7":
                return "seventeen";
            case "8":
                return "eighteen";
            case "9":
                return "nineteen";
        }
    } else
    {
        $temp = convertDigit($digit2);
        switch ($digit1)
        {
            case "2":
                return "twenty-$temp";
            case "3":
                return "thirty-$temp";
            case "4":
                return "forty-$temp";
            case "5":
                return "fifty-$temp";
            case "6":
                return "sixty-$temp";
            case "7":
                return "seventy-$temp";
            case "8":
                return "eighty-$temp";
            case "9":
                return "ninety-$temp";
        }
    }
}

function convertDigit($digit)
{
    switch ($digit)
    {
        case "0":
            return "zero";
        case "1":
            return "one";
        case "2":
            return "two";
        case "3":
            return "three";
        case "4":
            return "four";
        case "5":
            return "five";
        case "6":
            return "six";
        case "7":
            return "seven";
        case "8":
            return "eight";
        case "9":
            return "nine";
    }
}
/*end*/
function writeRssData($blogid,$blogcatid){
	$row=array();
	if($blogid!==""&&$blogid>0){
		$outs=getSingleBlogType($blogid);
		$feedpath="../feeds/rss/".$outs['rssname'].".xml";
		$query="SELECT * FROM rssentries WHERE blogtypeid=$blogid order by id desc";
		$query2="SELECT * FROM rssheaders WHERE blogtypeid=$blogid";
	}elseif ($blogcatid!==""&&$blogcatid>0) {
		# code...
		$outs=getSingleBlogCategory($blogcatid);
		$feedpath="../feeds/rss/".$outs['rssname'].".xml";
		$blogmainid=$outs['blogtypeid'];
		$outs=getSingleBlogType($blogmainid);
		$query="SELECT * FROM rssentries WHERE blogcategoryid=$blogcatid order by id desc";
		$query2="SELECT * FROM rssheaders WHERE blogcatid=$blogcatid";
	}else{
		return false;
	}
	$run=mysql_query($query)or die(mysql_error()." Line 896");
	$numrows=mysql_num_rows($run);
	$run2=mysql_query($query2)or die(mysql_error()." Line 897");
	$numrows2=mysql_num_rows($run2);
	$feedentries="";
	if($numrows2>0){
		$row2=mysql_fetch_assoc($run2);
		$header=stripslashes($row2['headerdetails']);
		$footer=$row2['footerdetails'];
	}
	if($numrows>0){
		//get rss entries
		while ($row=mysql_fetch_assoc($run)) {
			# code...
			$feedentries.=stripslashes($row['rssentry']);
		}

	}
	if ($numrows>0||$numrows2>0) {
		# code...
		$content=$header.$feedentries.$footer;
		// echo $content;
		$handle=fopen($feedpath,"w");
		fwrite($handle,$content);
		fclose($handle);
	}
	return $row;
}

function sendSubscriberEmail($blogpostid){
	global $host_addr,$host_target_addr,$host_email_addr,$host_email_send;
	$outs=getSingleBlogEntry($blogpostid);
	$blogtypeid=$outs['blogtypeid'];
	$blogcategoryid=$outs['blogcatid'];
 	$blogtypename=$outs['blogtypename'];
	$blogcatname=$outs['blogcatname'];
	$query="SELECT * FROM subscriptionlist WHERE blogtypeid=$blogtypeid AND status='active'";
	$query2="SELECT * FROM subscriptionlist WHERE blogcatid=$blogcategoryid AND status='active'";
	$run=mysql_query($query)or die(mysql_error()." Line 929");
	$run2=mysql_query($query2)or die(mysql_error()." Line 930");
	$numrows=mysql_num_rows($run);
	$numrows2=mysql_num_rows($run2);
	$coverphoto=$outs['absolutecover'];
	$y=date('Y');
	$mail = new PHPMailer;
	$mail->Mailer='smtp';
	$mail->isSMTP(); // Set mailer to use SMTP
	$mail->Host = 'relay-hosting.secureserver.net';
	$mail->Username = 'no-reply@muyiwaafolabi.com';
	$mail->Password = 'noreply';
	$mail->From = ''.$host_email_addr.'';
	$mail->FromName = 'Muyiwa Afolabi\'s website '.$outs['name'].'';
	// echo $numrows."<br>the numrows";
	$message='
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	 <head>
	 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	 <title>'.stripslashes($outs['title']).'</title>
	 <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	</head>
	<body style="margin: 0; padding: 0; background-color:#550101;font-family: \'Microsoft Tai Le\';">
	 <table border="" style="border:0px;" cellpadding="0" cellspacing="0" width="100%">
	 <tr>
	 <td align="center" style="text-align: center;border:0px; font-size: 32px;color: #FFA500;">
	 <img src="'.$host_target_addr.'images/muyiwalogo5.png" alt="Muyiwa Afolabi" width="182" height="206" style="display: inline-block;" /><br>
	 A new blog post in '.$blogtypename.' Blog
	 </td>
	 <table align="center" border="" cellpadding="0" cellspacing="0" width="600">
	 <tr>
	 <td style="padding: 40px 30px 40px 30px;background-color:#fefefe; color:#1a1a1a;">
	 <table border="" cellpadding="0" cellspacing="0" width="100%">
	 <tr>
	 <td style="font-size: 22px;text-align: center;color:#B800FF;border: 0px;border-bottom: 1px solid #979797;">
	 '.stripslashes($outs['title']).'
	 </td>
	 </tr>
	 <tr>
	 <td style="font-size:13px;border: 0px;border-bottom: 1px solid #979797;">
	 <img src="'.$coverphoto.'" height="112px"style="float:left;"/>'.stripslashes($outs['introparagraph']).'
	 </td>
	 </tr>
	 <tr>
	 <td style="border: 0px;border-bottom: 1px solid #979797;font-size: 12px;">
	 Posted under '.$blogcatname.', on '.$outs['entrydate'].' 
	<a href="'.$host_addr.'blog/?p='.$outs['id'].'" target="_blank" title="Continue reading this post">Continue Reading</a>
	 </td>
	 </tr>
	 </table>
	 </td>
	 </tr>
	 <tr>
	 <td style="text-align:center; font-size:13px;background: #2E0505;color: #FFAD00;">
	&copy; Muyiwa Afolabi '.$y.' Developed by Okebukola Olagoke.<br>
	<a href="'.$host_target_addr.'unsubscribe.php?t=1&tp='.$blogtypeid.'" style="color: #FD9D9D;">Unsubscribe</a>
	 </td>
	 </tr>
	</table>
	 </tr>
	 </table>
	</body>
	</html>
	';
	$mail->WordWrap = 50;
	$mail->isHTML(true);

	$mail->Subject = ''.stripslashes($outs['title']).'';
	$mail->Body = ''.$message.'';
	$mail->AltBody = 'A new blog post from Muyiwa Afolabi\'s website\n
	'.stripslashes($outs['title']).'
	Please visit '.$outs['pagelink'].' or '.$host_target_addr.'unsubscribe.php?t=1&tp='.$blogtypeid.'" to unsubscribe.
	';
	if($numrows>0){
	 $count=0;
	 //try to break the emails into packets of 300
	while($row=mysql_fetch_assoc($run)){
	$userid=$row['id'];
	$useremail=$row['email'];
	/* if($count>0){
	$mail->addBCC(''.$useremail.'');
	}else{*/
	$mail->AddAddress(''.$useremail.'');
	// }
	//send the email to th
	if($count==10){
	if($host_email_send===true){ 
	if(!$mail->send()) {
	 die('Message could not be sent.'. $mail->ErrorInfo);
	/* echo 'Message could not be sent.';
	 echo 'Mailer Error: ' . $mail->ErrorInfo;*/
	 // exit;
	}else{
	}
	}
	$mail->ClearAllRecipients(); // reset the `To:` list to empty
	$count=-1;
	}
	$count++;
	}
	if($count<10){
	if($host_email_send===true){ 
	if(!$mail->send()) {
	 die('Message could not be sent.'. $mail->ErrorInfo);
	/* echo 'Message could not be sent.';
	 echo 'Mailer Error: ' . $mail->ErrorInfo;*/
	 // exit;
	}
	}
	}

	}
	/*if($numrows2>0){
		$count=0;
		//try to break the emails into packets of 300
	while($row2=mysql_fetch_assoc($run2)){
	$userid=$row2['id'];
	$useremail=$row2['email'];
	$mail->addAddress(''.$useremail.'');
	$mail->WordWrap = 50;
	$mail->isHTML(true);
	$message='
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	 <head>
	 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	 <title>'.stripslashes($outs['title']).'</title>
	 <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	</head>
	<body style="margin: 0; padding: 0; background-color:#550101;font-family: \'Microsoft Tai Le\';">
	 <table border="" style="border:0px;" cellpadding="0" cellspacing="0" width="100%">
	 <tr>
	 <td align="center" style="text-align: center;border:0px; font-size: 32px;color: #FFA500;">
	 <img src="'.$host_target_addr.'images/muyiwalogo5.png" alt="Muyiwa Afolabi" width="182" height="206" style="display: inline-block;" /><br>
	 A new blog post in '.$blogtypename.' Blog
	 </td>
	 <table align="center" border="" cellpadding="0" cellspacing="0" width="600">
	 <tr>
	 <td style="padding: 40px 30px 40px 30px;background-color:#fefefe; color:#1a1a1a;">
	 <table border="" cellpadding="0" cellspacing="0" width="100%">
	 <tr>
	 <td style="font-size: 22px;text-align: center;color:#B800FF;border: 0px;border-bottom: 1px solid #979797;">
	 '.stripslashes($outs['title']).'
	 </td>
	 </tr>
	 <tr>
	 <td style="font-size:13px;border: 0px;border-bottom: 1px solid #979797;">
	 <img src="'.$coverphoto.'" height="112px"style="float:left;"/>'.stripslashes($outs['introparagraph']).'
	 </td>
	 </tr>
	 <tr>
	 <td style="border: 0px;border-bottom: 1px solid #979797;font-size: 12px;">
	 Posted under '.$blogcatname.', on '.$outs['entrydate'].' 
	<a href="'.$outs['pagelink'].'" target="_blank" title="Continue reading this post">Continue Reading</a>
	 </td>
	 </tr>
	 </table>
	 </td>
	 </tr>
	 <tr>
	 <td style="text-align:center; font-size:13px;background: #2E0505;color: #FFAD00;">
	&copy; Muyiwa Afolabi '.$y.' Developed by Okebukola Olagoke.<br>
	<a href="'.$host_target_addr.'unsubscribe.php?t=1&tp='.$blogtypeid.'" style="color: #FD9D9D;">Unsubscribe</a>
	 </td>
	 </tr>
	</table>
	 </tr>
	 </table>
	</body>
	</html>
	';
	$mail->Subject = ''.stripslashes($outs['title']).'';
	$mail->Body = ''.$message.'';
	$mail->AltBody = 'A new blog post from Muyiwa Afolabi\'s website\n
	'.stripslashes($outs['title']).'
	Please visit '.$outs['pagelink'].' or '.$host_target_addr.'unsubscribe.php?t=2&tp='.$blogtypeid.'&eu='.$userid.'" to unsubscribe.
	';
	if($host_email_send===true){
	if(!$mail->send()) {
	 echo 'Message could not be sent.';
	 echo 'Mailer Error: ' . $mail->ErrorInfo;
	 exit;
	}
	}
	//end of message send
	}
	// end of while loop for numrows2
	}*/
}

function getSingleBlogType($blogtypeid){
	global $host_addr,$host_target_addr;
	$query="SELECT * FROM blogtype where id=$blogtypeid";
	$row=array();
	$run=mysql_query($query)or die(mysql_error()." Line 926");
	$numrows=mysql_num_rows($run);
	/*$query2="SELECT * FROM rssheaders where blogtypeid=$blogtypeid";
	$run2=mysql_query($query2)or die(mysql_error()." Line 899");
	$row2=mysql_fetch_assoc($run2);*/

	$row=mysql_fetch_assoc($run);
	$id=$row['id'];
	$name=$row['name'];
	$foldername=$row['foldername'];
	$description=$row['description'];
	$status=$row['status'];

	$row['adminoutput']='
		<tr data-id="'.$id.'">
			<td>'.$name.'</td><td>'.$foldername.'</td><td>'.$description.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsingleblogtype" data-divid="'.$id.'">Edit</a></td>
		</tr>
		<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'">
			<td colspan="100%">
				<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
					<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
						
					</div>
				</div>
			</td>
		</tr>
	';
	$row['adminoutputtwo']='
				<div id="form" style="background-color:#fefefe;">
					<form action="../snippets/edit.php" name="editblogtype" method="post">
						<input type="hidden" name="entryvariant" value="editblogtype"/>
						<input type="hidden" name="entryid" value="'.$id.'"/>
						<div id="formheader">Edit '.$name.'</div>
							<div id="formend">
								Change Blog Name <br>
								<input type="text" placeholder="Enter Blog Name" name="name" class="curved"/>
							</div>
							<div id="formend">
								Blog Description *<br>
								<textarea name="description" placeholder="Enter Blog Description" value="'.$description.'" class=""></textarea>
							</div>
							<div id="formend">
								Change Status<br>
								<select name="status" class="curved2">
									<option value="">Change Status</option>
									<option value="active">Active</option>
									<option value="inactive">Inactive</option>
								</select>
							</div>
						<div id="formend">
							<input type="submit" name="Update" value="Submit" class="submitbutton"/>
						</div>
					</form>
				</div>
	';
	return $row;
}

function getAllBlogTypes($viewer,$limit){
	global $host_addr,$host_target_addr;
 	$testit=strpos($limit,"-");
	$testit===0||$testit===true||$testit>0?$limit="":$limit=$limit;
	$row=array();
	if($limit!==""&&$viewer=="admin"){
		$query="SELECT * FROM blogtype order by id desc ".$limit."";
	}else if($limit==""&&$viewer=="admin"){
		$query="SELECT * FROM blogtype order by id desc LIMIT 0,15";		
	}elseif($limit!==""&&$viewer=="specialsingle"){
		$query="SELECT * FROM blogtype WHERE id='$limit' OR name LIKE '%$limit%' order by id desc";
	}/*elseif($limit!==""&&$viewer=="viewer"){
		$query="SELECT * FROM blogtype ".$limit." order by id desc";
	}else if($limit==""&&$viewer=="viewer"){
		$query="SELECT * FROM blogtype order by id desc";		
	}*/
	$selection="";
	$run=mysql_query($query)or die(mysql_error()." Line 998");
	$numrows=mysql_num_rows($run);
	$top='<table id="resultcontenttable" cellspacing="0">
				<thead><tr><th>Name</th><th>FolderName</th><th>Description</th><th>Status</th><th>View/Edit</th></tr></thead>
				<tbody>';
	$bottom='	</tbody>
			</table>';
	$adminoutput="";
	$monitorpoint="";
	if($numrows>0){
		while($row=mysql_fetch_assoc($run)){
			$outvar=getSingleBlogType($row['id']);
			$adminoutput.=$outvar['adminoutput'];
			$selection.='<option value="'.$outvar['id'].'">'.$outvar['name'].'</option>';

		}
	}
	$rowmonitor['chiefquery']="SELECT * FROM blogtype order by id desc";
	$outs=paginatejavascript($rowmonitor['chiefquery']);
	$paginatetop='
	<div id="paginationhold">
		<div class="meneame">
			<input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
			<input type="hidden" name="outputtype" value="blogtype"/>
			<input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
			<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
			<div class="pagination">
				 '.$outs['usercontrols'].'
			</div>
		</div>
	</div>
	<div id="paginateddatahold" data-name="contentholder">';

	$paginatebottom='
	</div><div id="paginationhold">
		<div class="meneame">
			<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
		</div>
	</div>';
	$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
	$row['adminoutputtwo']=$top.$adminoutput.$bottom;
	$row['selection']=$selection;
	return $row;
}
function getSingleBlogCategory($blogtypeid){
	global $host_addr,$host_target_addr;
  $query="SELECT * FROM blogcategories WHERE id=$blogtypeid";
  $run=mysql_query($query)or die(mysql_error()." Line 799");
  $numrows=mysql_num_rows($run);
  $row=array();
  if($numrows>0){
  $row=mysql_fetch_assoc($run);
  $id=$row['id'];
  $blogtypeid=$row['blogtypeid'];
  $catname=$row['catname'];
  $subtext=$row['subtext'];
  $status=$row['status'];
  $outs=getSingleBlogType($blogtypeid);
  $postquery="SELECT * FROM blogentries WHERE blogcatid=$id AND status='active' order by id desc";
  $postrun=mysql_query($postquery)or die(mysql_error()." Line 1594");
  $postcount=mysql_num_rows($postrun);
  $postcountmain=$postcount;
  if($postcount>1000){
  $postcountmain=$postcount/1000;
  $postcountmain=round($postcountmain, 0, PHP_ROUND_HALF_UP);
  $postcountmain=$postcountmain."K";
  }elseif ($postcount>1000000) {
  	# code...
  	$postcountmain=$postcount/1000000;
  $postcountmain=round($postcountmain, 0, PHP_ROUND_HALF_UP);
  $postcountmain=$postcountmain."M";
  }
  $subtextout="";
  $coverout="";
  }
  $row['completeoutput']='
  <div id="bloghold">
  Sorry but there is no entry here.
  </div>
  ';
  $row['pfncatminoutput']="";
  if($outs['name']=="Project Fix Nigeria"||$outs['name']=="Frankly Speaking Africa"){
  	//for page type latest post content
  	$theme=array();
  	$theme[]="pfntoppurple";
  	$theme[]="pfntoporange";
  	$theme[]="pfntopred";
  	$theme[]="pfntopblue";
  	$theme[]="pfntopgreen";
  	$theme[]="pfntopyellow";
	$random=rand(0,5);
	$curtheme=$theme[$random];
	$curtheme2="";
	$cattotquery="SELECT * FROM blogcategories WHERE blogtypeid=$blogtypeid AND status='active' order by id desc";
	$cattotrun=mysql_query($cattotquery)or die(mysql_error()." Line 1629");
	$catcurcount=mysql_num_rows($cattotrun);
	$catrowouts=array();
	$count=0;
	if($catcurcount>0){
	while($cattotrow=mysql_fetch_assoc($cattotrun)){
	$catrowouts[]=$cattotrow['id'];
	if($count<6){
	if($id==$cattotrow['id']){
	$curtheme2=$theme[$count];
	}
	$count++;
	}else{
		$count=0;
		if($id==$cattotrow['id']){
	$random2=rand(0,5);		
	$curtheme2=$theme[$random2];
	}
	$count++;
	}
	}
	}
		//for miniature type previous posts
	$pfncattop='<div id="bottomcatdetailhold">
					<a href="category.php?cid='.$id.'" data-id="cattitle" title="'.$subtext.'">'.$catname.'</a>';
	$pfncattopentries='<div id="microbloghold">No posts under this yet.</div>';
	$pfncatbottom="</div>";
	$catmainpost='
	<div id="bloghold">
	Sorry but there is no entry here.
	</div>
	';
	if($postcount>0){
	$count=0;
		$pfncattopentries="<div id=\"microbloghold\">No extra posts here</div>";
	while ($postrows=mysql_fetch_assoc($postrun)) {
		# code...
	$postid=$postrows['id'];
	$postdata=getSingleBlogEntry($postid);
	if($count==0){
	$catmainpost=$postdata['vieweroutput'];
	}
	if($count>0&&$count<6){
	$pfncattopentries=="<div id=\"microbloghold\">No extra posts here</div>"?$pfncattopentries="":$testpfn="holding";
	$introparagraph=stripslashes($postdata['introparagraph']);
	$headerdescription = convert_html_to_text($introparagraph);
	$headerdescription=html2txt($headerdescription);
	$monitorlength=strlen($headerdescription);
	$headerminidescription=$headerdescription;
	$pfncattopentries.='
		<a href="'.$postdata['pagelink'].'"title="'.$headerminidescription.'"><div id="microbloghold">'.$postdata['title'].'<br><span class="microblogdatehold">'.$postdata['entrydate'].'</span> <span class="microblogviewcommenthold">Views: '.$postdata['views'].' <img src="./images/comments.png"/> <font color="orange">'.$postdata['commentcount'].'</font></span></div></a>
	';	
	}
	$count++;
	}
	}
	$pfncatmindispcontent=$pfncattop.$pfncattopentries.$pfncatbottom;
	$row['pfncatminoutput']=$pfncatmindispcontent;
	$mediaquery="SELECT * FROM media WHERE ownerid=$id AND ownertype='blogcategory' AND maintype='original'";
	$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 1683");
	$mediarow=mysql_fetch_assoc($mediarun);
	/*echo $mediaquery;
	echo $mediarow['location']."here";*/
	$row['profpic']=$mediarow['location'];
	$row['profpicid']=$mediarow['id'];
	if($mediarow['id']>0){
	$coverphoto='<img src=".'.$mediarow['location'].'" title="'.$catname.'"/>';
	}else{
	$coverphoto="";
	}
	$postcount==1?$s="":$s='s';

	$pfnmaincattop='
	  <div id="pfndisplayhold" name="'.$curtheme2.'" data-id="'.$id.'">
	  	<div id="pfnprevcatcontent" title="Click to see the latest post under "'.$catname.'"" data-targetid="'.$id.'" data-state="inactive">
	  		<img src="'.$mediarow['location'].'"/>
	  		<div id="postcounthold">
	  			'.$postcountmain.'<br>
	  			Post'.$s.'.
	  		</div>
	  		<div id="pfnprevcatcontentdetailsmini">'.$row['subtext'].'</div>
	  		<div id="pfnprevcatcontentdetails">'.$catname.'</div>
	  	</div>
	  	<div id="pfnlatestposthold" data-value="'.$id.'">
	';
	$pfnmaincatentry='
	'.$catmainpost.'
	';	
	$pfnmaincatbottom='
		</div>
	</div>
	';
	$row['completeoutput']=$pfnmaincattop.$pfnmaincatentry.$pfnmaincatbottom;
	$coverout='<td>'.$coverphoto.'</td>';
	$subtext='<td>'.$row['subtext'].'</td>';
	$subtextout='
	<div id="formend">
		Change Sub text<br>
		<input type="text" placeholder="'.$row['subtext'].'" name="subtext" class="curved"/>
	</div>
	<div id="formend">
		Change Image<br>
		<input type="file" name="profpic" class="curved"/>
	</div>
	';
	}
	$catnamelength=strlen($catname);
	$catnamemini=$catname;
	if($catname>48){
	$catnamemini=substr($catname,0,45)."...";
	}
	$row['linkout']='<a href="category.php?cid='.$id.'" title="Click to view the category '.$catname.'">'.$catnamemini.'</a>';
	$row['csilinkout']='<li><a href="csicategories.php?cid='.$id.'" title="Click to view the category '.$catname.'">'.$catnamemini.'</a></li>';
	$row['adminoutput']='
	    <tr data-id="'.$id.'">
	    	'.$coverout.'<td>'.$outs['name'].'</td><td>'.$catname.'</td>'.$subtext.'<td>'.$postcount.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsingleblogcategory" data-divid="'.$id.'">Edit</a></td>
	    </tr>
	    <tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'">
	    	<td colspan="100%">
	    		<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
	    			<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
	    				
	    			</div>
	    		</div>
	    	</td>
	    </tr>
	';
	$row['adminoutputtwo']='
				<div id="form" style="background-color:#fefefe;">
					<form action="../snippets/edit.php" name="editblogcategory" method="post" enctype="multipart/form-data">
						<input type="hidden" name="entryvariant" value="editblogcategory"/>
						<input type="hidden" name="entryid" value="'.$id.'"/>
						<div id="formheader">Edit '.$catname.'</div>
							<div id="formend">
								Change Category Name<br>
								<input type="text" placeholder="'.$catname.'" name="name" class="curved"/>
							</div>
							'.$subtextout.'
							<div id="formend">
								Change Status<br>
								<select name="status" class="curved2">
									<option value="">Change Status</option>
									<option value="active">Active</option>
									<option value="inactive">Inactive</option>
								</select>
							</div>
						<div id="formend">
							<input type="submit" name="updateblogcategory" value="Update" class="submitbutton"/>
						</div>
					</form>
				</div>
	';
	$row['pfnpageout']='';
	return $row;
}
function getAllBlogCategories($viewer,$limit,$blogtypeid){
	global $host_addr,$host_target_addr;
  $testit=strpos($limit,"-");
  $testit!==false?$limit="":$limit=$limit;
  	if($limit!==""&&$viewer=="admin"){
  	$query="SELECT * FROM blogcategories WHERE blogtypeid=$blogtypeid order by id desc ".$limit."";
    $rowmonitor['chiefquery']="SELECT * FROM blogcategories WHERE blogtypeid=$blogtypeid order by id desc";
  	}else if($limit==""&&$viewer=="admin"){
  $query="SELECT * FROM blogcategories WHERE blogtypeid=$blogtypeid order by id desc LIMIT 0,15";		
  $rowmonitor['chiefquery']="SELECT * FROM blogcategories WHERE blogtypeid=$blogtypeid order by id desc";
  	}else if($viewer=="viewer"){
  $query="SELECT * FROM blogcategories where blogtypeid=$blogtypeid and status='active' order by id desc";		
  $rowmonitor['chiefquery']="SELECT * FROM blogcategories WHERE blogtypeid=$blogtypeid and status='active' order by id desc";
  	}
  $row=array();
  $selection="";
  $run=mysql_query($query)or die(mysql_error()." Line 1156");
  $numrows=mysql_num_rows($run);
  $adminoutput="";
  $monitorpoint="";
  	$outs=getSingleBlogType($blogtypeid);
  	$coverout="";
  	$subtext="";
  	if($outs['name']=="Project Fix Nigeria"||$outs['name']=="Frankly Speaking Africa"||$outs['id']==3){
  		$coverout='<th>Cover Image</th>';		
  		$subtext='<th>Subtext</th>';		

  	}

  	$top='<table id="resultcontenttable" cellspacing="0">
  			<thead><tr>'.$coverout.'<th>Blogtype</th><th>Category Name</th>'.$subtext.'<th>Posts</th><th>Status</th><th>View/Edit</th></tr></thead>
  			<tbody>';
    $bottom='	</tbody>
    		</table>';
  		$completeoutput="No categories created yet";
  		$catminoutput="";
      $csicatout='<li><a href="##">No categories created yet</a><li>';
  		$allcatlinkouts="No categories created yet";
  if($numrows>0){
  	$completeoutput="";
  	$allcatlinkouts="";
    $csicatout="";
    while($row=mysql_fetch_assoc($run)){
      $outvar=getSingleBlogCategory($row['id']);
      $adminoutput.=$outvar['adminoutput'];
      $completeoutput.=$outvar['completeoutput'];
      $catminoutput.=$outvar['pfncatminoutput'];
    }
    $queryselect="SELECT * FROM blogcategories where blogtypeid=$blogtypeid and status='active' order by id desc";    
    $runselect=mysql_query($queryselect)or die(mysql_error()." Line 1156");
    while($rowselect=mysql_fetch_assoc($runselect)){
      $outvar=getSingleBlogCategory($rowselect['id']);
      $selection.='<option value="'.$outvar['id'].'">'.$outvar['catname'].'</option>';
      $allcatlinkouts.=$outvar['linkout'];
      $csicatout.=$outvar['csilinkout'];

    }
  }
  $outs=paginatejavascript($rowmonitor['chiefquery']);
  $paginatetop='
  <div id="paginationhold">
  	<div class="meneame">
  		<input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
  		<input type="hidden" name="outputtype" value="blogcategory"/>
  		<input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
  		<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
  		<div class="pagination">
  			  '.$outs['usercontrols'].'
  		</div>
  	</div>
  </div>
  <div id="paginateddatahold" data-name="contentholder">';

  $paginatebottom='
  </div><div id="paginationhold">
  	<div class="meneame">
  		<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
  	</div>
  </div>';
  $row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
  $row['completeoutput']=$completeoutput;
  $row['pfncatminoutput']=$catminoutput;
  $row['adminoutputtwo']=$top.$adminoutput.$bottom;
  $row['chiefquery']=$rowmonitor['chiefquery'];
  $row['selection']=$selection;
  $row['linkout']=$allcatlinkouts;
  $row['csilinkout']=$csicatout;
  return $row;
}
function getSingleComment($commentid){
	global $host_addr,$host_target_addr;
	$row=array();
	$query="SELECT * FROM comments WHERE id=$commentid";
	$run=mysql_query($query)or die(mysql_error()." Line 1166");
	$row=mysql_fetch_assoc($run);
	//include gravatar
	include("Gravatar.php");
	$gravatar=new Gravatar();
	$gravatar->setAvatarSize(115);
  $id=$row['id'];
  $fullname=$row['fullname'];
  $email=$row['email'];
  $avatar = $gravatar->buildGravatarURL(''.$email.'');
  $blogpostid=$row['blogentryid'];
  $blogquery="SELECT * FROM blogentries where id=$blogpostid";
  $blogrun=mysql_query($blogquery)or die(mysql_error()." Line 1145");
  $blognumrows=mysql_num_rows($blogrun);
  $blogrow=mysql_fetch_assoc($blogrun);
  $blogtypeid=$blogrow['blogtypeid'];
  $blogtypedata=getSingleBlogType($blogtypeid);
  $pagename=$blogrow['pagename'];
  $pagelink=''.$host_addr.'blog/'.$blogtypedata['foldername'].'/'.$pagename.'.php';
  $link='<a href="'.$pagelink.'" target="_blank" title="click to view this blog post">'.$blogrow['title'].'</a>';
  $rellink='./blog/'.$blogtypedata['foldername'].'/'.$pagename.'.php';
  $comment=$row['comment'];
  $comment=str_replace("../../",$host_addr,$comment);
  $date=$row['datetime'];
  $status=$row['status'];
  $tableout='';
  if($status=="active"){
  $tableout='<a href="#&id='.$id.'" name="disablecomment" data-type="disablecomment" data-divid="'.$id.'">Disable</a>';
  }elseif($status=="inactive"){
  $tableout='<a href="#&id='.$id.'" name="activatecomment" data-type="activatecomment" data-divid="'.$id.'">Activate</a>';
  }elseif($status=="disabled"){
  $tableout='<a href="#&id='.$id.'" name="reactivatecomment" data-type="reactivatecomment" data-divid="'.$id.'">Reactivate</a>';
  }
  $row['vieweroutput']='
    <div id="commentholder" data-id="'.$id.'">
    	<div id="commentimg">
    		<img src="'.$host_addr.'images/default.gif" class="total">
    	</div>
    	<div id="commentdetails">
    		<div id="commentdetailsheading">
    			'.$fullname.'&nbsp;&nbsp;&nbsp;&nbsp;<span>'.$date.'</span>
    		</div>
    		'.$comment.'
    	</div>
    </div>
  ';
  $datetwo=str_replace(",", "", $date);
  $dateday=date("d",strtotime($datetwo));
  $datemon=date("M",strtotime($datetwo));
  $dateyear=date("Y",strtotime($datetwo));
  $datetime=date("h:i:m A",strtotime($datetwo));
  $maindayout=date('D, d F, Y h:i:s A', strtotime($datetwo));
  $row['vieweroutputfjc']='
    <div class="commentsingle jobs-item with-thumb">
      <div class="thumb">
        <img width="auto" height="115" class="avatar avatar-32 photo" src="'.$avatar.'" alt="">     
      </div>
      <div class="clearfix visible-xs"></div>
      <div class="date">'.$dateday.' <span>'.$datemon.'</span> '.$dateyear.'</div>
      <h6 class="title"><a href="##">'.$fullname.'</a></h6>
      <span class="meta">At: '.$datetime.' <br> Commented:</span>

      <div class="description">'.$comment.'</div>

    </div>
  ';
  $row['vieweroutputll']='
    <!-- COMMENT ITEM START -->
    <li class="comment">
      <div class="comment-body">
        <div class="comment-author vcard">
          
          <img alt="" src="'.$avatar.'" class="avatar">
          <cite class="fn"><a href="##">'.$fullname.'</a></cite>
        </div>
        <div>'.$comment.'</div>
        <div class="comment-meta commentmetadata">
          '.$maindayout.'
        </div>
      </div>
    </li>
    <!-- COMMENT ITEM END -->
  ';
  // csi comment output
  $row['vieweroutputcsi']='
     <li class="item">
          <div class="clearfix">
              <div class="medium-2 columns photo">
                  <img src="'.$avatar.'" alt="'.$fullname.'">
              </div>
              <div class="medium-10 columns">
                  <div class="info">
                      <h3 class="comment-name">'.$fullname.'</h3>
                      <span class="date"><i class="fa fa-calendar"></i> '.$maindayout.'</span>
                      <div class="comment-text">'.$comment.'</div>
                  </div><!-- /.info -->
              </div><!-- /.columns -->
          </div><!-- /.clearfix -->
      </li><!-- /.item -->
  ';
  $row['adminoutput']='
    <div id="commentholder" data-id="'.$id.'">
    	<div id="commentimg">
    		<img src="'.$host_addr.'images/default.gif" class="total">
    	</div>
    	<div id="commentdetails">
    		<div id="commentdetailsheading">
    			'.$fullname.'&nbsp;&nbsp;&nbsp;&nbsp;<span>'.$date.'</span>
    		</div>
    		'.$comment.'
    		<a href="##removeComment" class="adminremoval" name="removecomment" title="click here to remove this comment" data-cid="'.$id.'">Click to remove</a>
    		<div id="bulkoperation"><input type="checkbox" data-state="off" data-parent="" value="'.$id.'"></div>
    	</div>
    </div>
  ';
  $row['adminoutputtwo']='
    <tr data-id="'.$id.'">
    	<td>'.$fullname.'</td><td>'.$email.'</td><td>'.$date.'</td><td>'.$comment.'</td><td>'.$link.'</td><td name="commentstatus'.$id.'">'.$status.'</td><td name="trcontrolpoint">'.$tableout.'</td>
    </tr>
    <tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'">
    	<td colspan="100%">
    		<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
    			<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
    				
    			</div>
    		</div>
    	</td>
    </tr>
  ';
  return $row;
}
function getAllComments($viewer,$limit,$blogpostid){
	global $host_addr,$host_target_addr,$logpart;
    $testit=strpos($limit,"-");
    $testit===0||$testit===true||$testit>0?$limit=" LIMIT 0,15":$limit=$limit;
    $row=array();
    $paginateout="";
    $extraqdata="";
    $alevel=isset($_SESSION['accesslevel'.$logpart.''])?$_SESSION['accesslevel'.$logpart.'']:0;
    if(is_numeric($blogpostid)){
    if($viewer=="admin"){
    if($limit=="" && $blogpostid==""){
    $query="SELECT * FROM comments WHERE status!='disabled' order by id,status desc LIMIT 0,15";
    $rowmonitor['chiefquery']="SELECT * FROM comments WHERE status!='disabled' order by id,status desc";
    }else if($limit!==""&& $blogpostid==""){
    $query="SELECT * FROM comments WHERE status!='disabled' order by id,status desc $limit";
    $rowmonitor['chiefquery']="SELECT * FROM comments WHERE status!='disabled' order by id,status desc";
    }elseif ($limit==""&&$blogpostid!=="") {
     # code...
    $query="SELECT * FROM comments WHERE blogentryid=$blogpostid AND status!='disabled' order by id,status desc";
    $rowmonitor['chiefquery']="SELECT * FROM comments WHERE blogentryid=$blogpostid AND status!='disabled' order by id,status desc";
    }elseif ($limit!==""&&$blogpostid!=="") {
     # code...
    $query="SELECT * FROM comments WHERE blogentryid=$blogpostid AND status!='disabled' order by id,status desc $limit";
    $rowmonitor['chiefquery']="SELECT * FROM comments WHERE blogentryid=$blogpostid AND status!='disabled' order by id,status desc $limit";
    }
    }elseif ($viewer=="viewer") {
     # code...
    $query="SELECT * FROM comments WHERE blogentryid=$blogpostid AND status!='disabled' AND status!='inactive'";
    $rowmonitor['chiefquery']="SELECT * FROM comments WHERE blogentryid=$blogpostid AND status!='disabled' AND status!='inactive'";
    }
    }else{
    $limit==""?$limit="LIMIT 0,15":$limit=$limit;
     if($blogpostid=='all'){
      $query="SELECT * FROM comments order by id desc $limit";
      $rowmonitor['chiefquery']="SELECT * FROM comments order by id,status desc";
     }elseif($limit=="" && $blogpostid!==""){
      $query="SELECT * FROM comments WHERE status='$blogpostid' order by id,status desc LIMIT 0,15";
      $rowmonitor['chiefquery']="SELECT * FROM comments WHERE status='$blogpostid' order by id,status desc";
     }else if($limit!==""&& $blogpostid!==""){
      $query="SELECT * FROM comments WHERE status='$blogpostid' order by id,status desc $limit";
      $rowmonitor['chiefquery']="SELECT * FROM comments WHERE status='$blogpostid' order by id,status desc";
    }else if($viewer=="inactivecount"){
      $query="SELECT * FROM comments WHERE status='inactive' $extraqdata order by id,status desc $limit";
      $rowmonitor['chiefquery']="SELECT * FROM comments WHERE status='inactive' $extraqdata order by id,status desc $limit";
     }
     $paginateout=$blogpostid;

     }
     // echo $query;
    $run=mysql_query($query)or die(mysql_error()." Line 1189");
    $numrows=mysql_num_rows($run);
    $adminoutput='<td>No comments yet</td>';
    $vieweroutput='No comments yet';
    $vieweroutputfjc='No comments yet';
    $vieweroutputll='<li>No comments yet</li>';
    $vieweroutputcsi='<li><p>No comments yet</p></li>';
    $commentoutput="";
    $row['commentout']="No comments here yet.<br>";
    $countout=0;
    if ($numrows>0) {
      # code...
      $adminoutput="";
      $vieweroutput="";
      $vieweroutputfjc="";
      $vieweroutputll="";
      $vieweroutputcsi="";
    while($row=mysql_fetch_assoc($run)){
    $commentout=getSingleComment($row['id']);
    $id=$row['id'];
    $commentoutput.=$commentout['adminoutput'];
      // echo $alevel;
    if($alevel==3){
        // check for eko kopa valid comments only
        $bdataid=$row['blogentryid'];
        $cquery="SELECT * FROM blogentries WHERE id=$bdataid";
        $crun=mysql_query($cquery)or die(mysql_error()." line ".__LINE__);
        $cnumrows=mysql_num_rows($crun);
        $cnumrows>0?$crow=mysql_fetch_assoc($crun):$crow['id']=0;
        $typeid=$crow['blogtypeid'];
        $btypedata=getSingleBlogType($typeid);
        if(strtolower($btypedata['name'])=="frontiers job-connect"){
            // echo $btypedata['name']." level";
          $adminoutput.=$commentout['adminoutputtwo'];
          $countout++;
        }
      }else{
          $adminoutput.=$commentout['adminoutputtwo'];
          $countout=$numrows;
      }
      $vieweroutput.=$commentout['vieweroutput'];
      $vieweroutputfjc.=$commentout['vieweroutputfjc'];
      $vieweroutputll.=$commentout['vieweroutputll'];
      $vieweroutputcsi.=$commentout['vieweroutputcsi'];
    }
    $row['commentout']=$commentoutput;
    }
    $row['countout']=$countout;
    $row['commentcount']=$numrows;
    $top='<table id="resultcontenttable" cellspacing="0">
          <thead><tr><th>Fullname</th><th>Email</th><th>Date</th><th>Comment Entry</th><th>InBlogPost</th><th>Status</th><th>View/Edit</th></tr></thead>
          <tbody>';
    $bottom=' </tbody>
        </table>';
    $outs=paginatejavascript($rowmonitor['chiefquery']);
    $paginatetop='
    <div id="paginationhold">
      <div class="meneame">
        <input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
        <input type="hidden" name="outputtype" value="comments|'.$paginateout.'"/>
        <input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
        <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
        <div class="pagination">
           '.$outs['usercontrols'].'
        </div>
      </div>
    </div>
    <div id="paginateddatahold" data-name="contentholder">';

    $paginatebottom='
    </div><div id="paginationhold">
      <div class="meneame">
        <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
      </div>
    </div>';
    $row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
    $row['adminoutputtwo']=$top.$adminoutput.$bottom;
    $row['vieweroutput']=$vieweroutput;
    $row['vieweroutputfjc']=$vieweroutputfjc;
    $row['vieweroutputll']='
      <h2 class="comments-title">'.$numrows.' Comments</h2>
      <!-- COMMENT LIST START -->
      <ol class="comment-list">
        '.$vieweroutputll.'
      </ol>
      <!-- COMMENT LIST END -->
    ';
    $row['vieweroutputcsi']='
      <h3 class="text-center"><span class="light">Comments ('.$numrows.')</span></h3>
      <!-- COMMENT LIST START -->
      <ul class="comment-list">
        '.$vieweroutputcsi.'
      </ul>
      <!-- COMMENT LIST END -->
    ';

    return $row;
}
function getSingleBlogEntry($blogentryid){
  global $host_addr,$host_target_addr;
  $row=array();
  $query="SELECT * FROM blogentries where id=$blogentryid";
  $run=mysql_query($query)or die(mysql_error()." Line 1145");
  $numrows=mysql_num_rows($run);
  $row=mysql_fetch_assoc($run);
  $id=$row['id'];
  $row['numrows']=$numrows;
  $commentdata=getAllComments("admin","",$id);
  $commentdatatwo=getAllComments("viewer","",$id);
  $blogtypeid=$row['blogtypeid'];
  $blogtypedata=getSingleBlogType($blogtypeid);
  $blogtypeid=$row['blogcatid'];
  // $blogtypedata=getSingleBlogType($blogtypeid);
  $blogcatquery="SELECT * FROM blogcategories WHERE id=$blogtypeid";
  $blogcatrun=mysql_query($blogcatquery)or die(mysql_error()." Line 1642");
  $blogcategorydata=mysql_fetch_assoc($blogcatrun);
  $blogentrytype=$row['blogentrytype'];
  $betype=isset($row['betype'])?$row['betype']:""; // the blog entry file type, used for audio and video entries
  $becode=isset($row['becode'])?$row['becode']:"";  // the embedded code of the audio or video entries
  $title=$row['title'];
  $introparagraph=$row['introparagraph'];
  // create the header description information portion
  if($blogentrytype!=="gallery" && $blogentrytype!=="banner"&& $blogentrytype!=="audio"&& $blogentrytype!=="video"){
    $headerdescription = convert_html_to_text($introparagraph);
    $headerdescription=html2txt($headerdescription);
    $monitorlength=strlen($headerdescription);
    $headerminidescription=$headerdescription;
    if($monitorlength>140){
      $headerminidescription=substr($headerdescription,0,137);
      $headerminidescription=$headerminidescription."...";
    }
  }else{
    $headerdescription=$title;
    $monitorlength=strlen($headerdescription);
    $headerminidescription=$headerdescription;
    if($monitorlength>140){
      $headerminidescription=substr($headerdescription,0,137);
      $headerminidescription=$headerminidescription."...";
    } 
  }

  $blogpost=$row['blogpost'];
  $blogpostout=str_replace("../", $host_addr, $blogpost);
  $row['blogpostout']=$blogpostout;
  $entrydate=$row['entrydate'];
  // get the blog audio if any
  $audiofileout="No audio"; 
  // get the blog video if any
  $videofileout="No video"; 
  // produce the enddate and endtime in a slightly easier to read format
  $entrydatem=str_replace(",", "", $entrydate);
  $maindayout=date('D, d F, Y h:i:s A', strtotime($entrydatem));
  $mainday=date('d', strtotime($entrydatem));
  $mainmonth=date('M', strtotime($entrydatem)); // three letter month name
  $mainyear=date('Y', strtotime($entrydatem));
  $schemadayout=date('c', strtotime($entrydatem));
  $row['maindayout']=$maindayout;
  $row['schemadayout']=$schemadayout;
  $entrydateout=$maindayout;
  $modifydate=$row['modifydate'];
  if($modifydate==""){
  	$modifydate="never";
  }
  $row['modifydate']=$modifydate;
  $views=$row['views'];
  $coverid=$row['coverphoto'];
  // work om images based on blog entry types
  $row['valbum']=""; // viewer mini album
  $row['vfalbum']=""; // viewer full album
  $row['bannerthumb']="";
  $row['bannermain']="";
  $defaultcsipostflaticon="flaticon-pencil125";
  //get complete gallery images and create thumbnail where necessary;
  $mediaquery="SELECT * FROM media WHERE ownerid=$id AND ownertype='blogentry' AND status='active' ORDER BY id DESC";
  $mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 2846");
  $coverdata=mysql_fetch_assoc($mediarun);
  $coverphoto=$coverdata['location'];
  $medianumrows=mysql_num_rows($mediarun);
  if($coverid<1){
    $coverphoto="".$host_addr."images/mablogdefault.png";
    // if csi
    if ($row['blogtypeid']==2) {
      # code...
      $coverphoto="".$host_addr."images/csiblogdefault.png";
      
    }
  }
  if ($blogentrytype==""||$blogentrytype=="normal") {
    # code...
    $extraformdata='
      <input type="hidden" name="blogentrytype" value="normal"/>
    ';
    $editcoverphotostyle="";
    $editcoverphotofloatstyle="";
    $editintroparagraphstyle="";
    $editblogpoststyle="";
  }elseif($blogentrytype=="banner"){
    $defaultcsipostflaticon="flaticon-picture13";
    $mediaquery="SELECT * FROM media WHERE ownerid=$id AND ownertype='blogentry' AND maintype='bannerpic' AND status='active' ORDER BY id DESC";
    $mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 2846");
    $coverdata=mysql_fetch_assoc($mediarun);
    $coverphoto=$coverdata['location'];
    $row['bannermain']=$host_addr.$coverphoto;
    $coverphotothumb=$coverdata['details'];
    $row['bannerthumb']=$host_addr.$coverphotothumb;
    $coverphotothumb==""?$coverphoto=$coverphoto:$coverphoto=$coverphotothumb;
    $medianumrows=mysql_num_rows($mediarun);
    $extraformdata='
    <div id="formend">
      Change Banner Image<br>
      <input type="file" placeholder="Choose image" name="bannerpic" class="curved"/>
    </div>
    <input type="hidden" name="blogentrytype" value="banner"/>
    <input type="hidden" name="bannerid" value="'.$coverdata['id'].'"/>
    ';
    $editcoverphotostyle="display:none;";
    $editcoverphotofloatstyle="display:none;";
    $editintroparagraphstyle="display:none;";
    $editblogpoststyle="display:none;";
  }elseif($blogentrytype=="gallery"){
    $defaultcsipostflaticon="flaticon-picture13";
    $editcoverphotostyle="display:none;";
    $editcoverphotofloatstyle="display:none;";
    $editintroparagraphstyle="display:none;";
    // for csi
    if($row['blogtypeid']==2){
        $editintroparagraphstyle="display:;";
    }
    $editblogpoststyle="display:none;";
    $outselect="";
    for($i=1;$i<=10;$i++){
      $pic="";
      $i>1?$pic="photos":$pic="photo";    
      $outselect.='<option value="'.$i.'">'.$i.''.$pic.'</option>';
    }
    $mediaquery="SELECT * FROM media WHERE ownerid=$id AND ownertype='blogentry' AND maintype='gallerypic' AND status='active' ORDER BY id DESC";
    $mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 2087");
    $medianumrows=mysql_num_rows($mediarun);
    $thumbstack="";
    $locationstack="";
    $dimensionstack="";
    $locationstack2="";
    $dimensionstack2="";
    $mediacount=$medianumrows;
    //get the blog gallery
    $album="No Gallery Photos Available";
    $valbum="No Gallery Photos Available for this post";
    $vfalbum="No Gallery Photos Available for this post";
    $csialbum="<li>No Gallery Photos Available for this post</li>";
    $csifullalbum="<li>No Gallery Photos Available for this post</li>";
    if($medianumrows>0){
      $count=0;
      $album="";
      $valbum="";
      $vfalbum="";
      $csialbum="";
      $csiminialbum="";
      while ($mediarow=mysql_fetch_assoc($mediarun)) {
        # code...
        $countextra=$count+1;
        if($count==0){
          // $coverphoto=$mediarow['details'];
          $coverphoto=$mediarow['location'];
          $coverphotothumb=$mediarow['details'];
          $coverphotothumb==""?$coverphoto=$coverphoto:$coverphoto=$coverphotothumb;
          $coverphoto==""?$coverphoto="./images/muyiwalogo5.png":$coverphoto=$coverphoto;
          $maincoverphoto=$mediarow['location'];
        }

        $imgid=$mediarow['id'];
        $realimg=$mediarow['location'];
        $thumb=$mediarow['details'];
        $width=$mediarow['width'];
        $height=$mediarow['height'];
        $locationstack==""?$locationstack.=$host_addr.$realimg:$locationstack.=">".$host_addr.$realimg;
        $dimensionstack==""?$dimensionstack.=$width.",".$height:$dimensionstack.="|".$width.",".$height;
        $album.='
          <div id="editimgs" name="albumimg'.$imgid.'">
            <div id="editimgsoptions">
              <div id="editimgsoptionlinks">
                <a href="##" name="deletepic" data-id="'.$imgid.'"data-galleryid="'.$id.'"data-arraypoint="'.$count.'" data-src="'.$host_addr.''.$realimg.'"><img name="deletepic" src="../images/trashfirst.png" title="Delete this photo?" class="total"></a>
                <a href="##" name="viewpic" data-id="'.$imgid.'" data-galleryid="'.$id.'" data-arraypoint="'.$count.'" data-src="'.$host_addr.''.$realimg.'"><img name="viewpic" src="../images/viewpicfirst.png" title="View full image" class="total"></a>                
              </div>
            </div>  
            <img src=".'.$mediarow['details'].'" name="realimage" data-width="'.$width.'" data-height="'.$height.'" style="height:100%;margin:auto;">
          </div>
        ';
        $vfalbum.='
          <div class="blogpostgalleryimgholdtwo">
              <img src="'.$host_addr.''.$mediarow['details'].'" data-id="'.$imgid.'" data-galleryid="'.$id.'" data-galleryname="bloggallerydata" data-arraypoint="'.$count.'" data-src="'.$host_addr.''.$realimg.'" style="height:100%;margin:auto;float:none;">
          </div>
        ';
        // csi album data
        $csialbum.='
            <li class="">
                <img src="'.$host_addr.''.$mediarow['details'].'" alt="'.$title.' - Gallery Slide'.$countextra.'" />
            </li>
        ';
        if($count<8){
          $locationstack2==""?$locationstack2.=$host_addr.$realimg:$locationstack2.=">".$host_addr.$realimg;
          $dimensionstack2==""?$dimensionstack2.=$width.",".$height:$dimensionstack2.="|".$width.",".$height;
          $valbum.='
          <div class="blogpostgalleryimgholdone">
              <img src="'.$mediarow['details'].'" data-id="'.$imgid.'" data-galleryid="'.$id.'" data-galleryname="bloggallerydata" data-arraypoint="'.$count.'" data-src="'.$host_addr.''.$realimg.'" style="height:100%;margin:auto;float:none;">
          </div>
          ';
          $csiminialbum.='
            <li class="">
                <img src="'.$host_addr.''.$mediarow['details'].'" alt="'.$title.' - Gallery Slide'.$countextra.'" />
            </li>
        ';
        }
        $count++;
      }
      $valbum.='<input type="hidden" name="bloggallerydata'.$id.'" data-title="'.$title.'" data-mainimg="'.$host_addr.''.$maincoverphoto.'" data-images="'.$locationstack2.'" data-sizes="'.$dimensionstack2.'" data-details="">';
      $vfalbum.='<input type="hidden" name="bloggallerydata'.$id.'" data-title="'.$title.'" data-mainimg="'.$host_addr.''.$maincoverphoto.'" data-images="'.$locationstack.'" data-sizes="'.$dimensionstack.'" data-details="">';
      $csialbum='
          <ul class="example-orbit" data-orbit>
                '.$csialbum.'
          </ul>
      ';
      $csiminialbum='
          <ul class="example-orbit" data-orbit>
                '.$csiminialbum.'
          </ul>
      ';
    // csi album
    $row['csialbum']=''.$csialbum.'';
    $row['csiminialbum']=''.$csiminialbum.'';
    $row['valbum']=$valbum;
    $row['vfalbum']=$vfalbum;
    }
    $extraformdata='
      <div id="formend" >
          <input type="hidden" name="gallerydata'.$id.'" data-title="'.$title.'" data-mainimg="'.$host_addr.''.$maincoverphoto.'" data-images="'.$locationstack.'" data-sizes="'.$dimensionstack.'" data-details=""/>
          Edit Photos from this blog post album.<br>
        '.$album.'
        <div id="formend">
          Add Gallery Photos for this post:<br>
          <input type="hidden" name="piccount" value=""/>
          <select name="photocount" class="curved2" title="Choose the amount of photos you want to upload, max of 10, then click below the selection to continue">
          <option value="">--choose amount--</option>
          '.$outselect.'
          </select>             
        </div>
      </div>
      <input type="hidden" name="blogentrytype" value="gallery"/>
    ';
  }else if($blogentrytype=="audio"){
    $defaultcsipostflaticon="flaticon-speaker100";
    if($becode=="local"){
      $mediaquery="SELECT * FROM media WHERE ownerid=$id AND ownertype='blogentry' AND maintype='audio' AND status='active' ORDER BY id DESC";
      $mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 2087");
      $medianumrows=mysql_num_rows($mediarun);
      if($medianumrows>0){
        $audiofileout="";
        $mediarow=mysql_fetch_assoc($mediarun);
        $audiofileout='<audio src="'.$host_addr.''.$mediarow['location'].'" class="post-audio" preload="none" controls>No support detected Download <a href="'.$host_addr.''.$mediarow['location'].'">Audio</a> instead</audio>';
      }else{
        $audiofileout='<p>No Audio file</p>';
      }
    }else{
      $audiofileout=$becode;
    }
  }else if($blogentrytype=="video"){
    $defaultcsipostflaticon="fa fa-video-camera";
    if($becode=="local"){
      $mediaquery="SELECT * FROM media WHERE ownerid=$id AND ownertype='blogentry' AND maintype='video' AND status='active' ORDER BY id DESC";
      $mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 2087");
      $medianumrows=mysql_num_rows($mediarun);
      if($medianumrows>0){
        $videofileout="";
        while ($mediarow=mysql_fetch_assoc($mediarow)) {
          # code...
          $mediarow=mysql_fetch_assoc($mediarun);
          $videofileout.='<source src="'.$host_addr.''.$mediarow['location'].'"/>';
        }
        $videofileout='
            <video title="" id="example_video_1" class="video-js vjs-default-skin" controls preload="true" height="" style="" poster="" data-setup="{}">
                '.$videofileout.'
            </video>
        ';
      }else{
        $videofileout='<p>No Video File </p>';
      }
    }else{
      $videofileout=$becode;
    }
    
  }
  // $coverdata=getSingleMediaData($coverid,'id');

  $row['maincoverphoto']=$coverphoto;
  $admincover="../".$coverphoto;
  // echo "$admincover<br>";
  $row['admincover']=$admincover;
  $absolutecover="".$host_addr."".$coverphoto;
  $row['absolutecover']=$absolutecover;
  $pagename=$row['pagename'];
  $status=$row['status'];
  $pagelink=''.$host_addr.'blog/'.$blogtypedata['foldername'].'/'.$pagename.'.php';
  $rellink='./blog/'.$blogtypedata['foldername'].'/'.$pagename.'.php';
  $row['pagelink']=$pagelink;
  $row['rellink']=$rellink;
  $row['pagedirectory']=''.$host_addr.'blog/'.$blogtypedata['foldername'].'/';
  $row['reldirectory']='./blog/'.$blogtypedata['foldername'].'/';
  $link='<a href="'.$pagelink.'" target="_blank" title="click to view this blog post">'.$title.'</a>';
  $commentcontent='
    <div id="formend" name="minicommentsearchhold" style="">
    <font style="font-size:18px;">Comments</font><br>
    	<div id="formend" name="commentsearchpanehold">
    	After a search, if you want to view all comments again simply type in "<b>*fullcommentsview*</b>" to do so.<br>
    	<input type="text" class="curved" name="minisearch'.$id.'" data-id="'.$id.'" title="Use this search bar to search by comment word, i.e offensive or in appropriate words or by comment poster name" Placeholder="Search for words within comments..."/>
    	</div>
    	 <input type="button" name="updateblogentry" style="max-width:150px;" value="Search" onClick="searchComment('.$id.')" class="submitbutton"/>
    	<div id="formend" name="commentfullhold'.$id.'">
    	'.$commentdata['commentout'].'
    	</div>
    </div>
  ';
  $row['viewercomment']=$commentdatatwo['vieweroutput'];
  // fjc
  $row['viewercommenttwo']=$commentdatatwo['vieweroutputfjc'];
  // ll
  $row['viewercommentll']=$commentdatatwo['vieweroutputll'];
  //csi
  $row['viewercommentcsi']=$commentdatatwo['vieweroutputcsi'];
  $row['commentcount']=$commentdatatwo['commentcount'];
  $row['blogtypename']=$blogtypedata['name'];
  $row['blogcatname']=$blogcategorydata['catname'];
  //configure viewer output 
  $viewerbodyoutone="";
  $viewerbodyouttwo="";
  if($blogentrytype==""||$blogentrytype=="normal"){
    $viewerbodyoutone='   <img src="'.$absolutecover.'"/>'.$introparagraph.'';
    if($row['blogtypeid']==2){
      $viewerbodyoutone='   <img src="'.$absolutecover.'" class="element-post-default-image" alt="'.$title.'"/>';
      $viewerbodyouttwo=$viewerbodyoutone;
    }
    $linkcontentout="Continue Reading";
  }elseif ($blogentrytype=="banner") {
    # code...
    $viewerbodyoutone='<img src="'.$absolutecover.'" style="width:98%;max-height:660px;"/>';
    if($row['blogtypeid']==2){
      $viewerbodyoutone='<img src="'.$absolutecover.'" style="width:98%;max-height:660px;"/>';
      $viewerbodyouttwo=$viewerbodyoutone;
    }
    $linkcontentout="View Banner";
  }elseif ($blogentrytype=="gallery") {
    # code...
    $viewerbodyoutone=$valbum;
    // csi gallerry post
    if($row['blogtypeid']==2){
      $viewerbodyoutone=$csiminialbum;
      $viewerbodyouttwo=$csialbum;
    }
    $linkcontentout="View All Photos";
  }elseif ($blogentrytype=="video") {
    # code...
    $viewerbodyoutone=$videofileout;
    if($row['blogtypeid']==2){
      $viewerbodyoutone=$videofileout;
      $viewerbodyouttwo=$viewerbodyoutone;
    }
    $linkcontentout="View Video";
  }elseif ($blogentrytype=="audio") {
    # code...
    $viewerbodyoutone=$valbum;
    $viewerbodyouttwo=$viewerbodyoutone;
    $linkcontentout="View Video";
  }
  $row['adminoutput']='
    <tr data-id="'.$id.'">
    	<td><img src="'.$host_addr.''.$coverphoto.'"style="height:150px;"/></td><td>'.$link.'</td><td>'.$blogtypedata['name'].'</td><td>'.$blogcategorydata['catname'].'</td><td>'.$commentdata['commentcount'].'</td><td>'.$views.'</td><td>'.$entrydate.'</td><td>'.$modifydate.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsingleblogpost" data-divid="'.$id.'">Edit</a></td>
    </tr>
    <tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'">
    	<td colspan="100%">
    		<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
    			<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
    								
    			</div>
    		</div>
    	</td>
    </tr>
  ';
  $blogplatformshares='
    <div id="blogplatformshares">
    	<div class="fb-like" data-href="'.$pagelink.'" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
    </div>
    <div id="blogplatformshares">
    	<div class="g-plus" data-action="share" data-annotation="bubble" data-href="'.$pagelink.'"></div>
    </div>
    <div id="blogplatformshares">
    	<a href="https://twitter.com/share" class="twitter-share-button" data-url="'.$pagelink.'">Tweet</a>
    </div>
    <div id="blogplatformshares">
    	<script type="IN/Share" data-url="'.$pagelink.'" data-counter="right"></script>
    </div>
  ';
  $blogplatformminishares='
  		<div class="minisharecontainers">
  			<a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]='.$pagelink.'&p[images][0]='.$absolutecover.'&p[title]='.$title.'&p[summary]='.$headerdescription.'" target="_blank"><img src="../images/facebookshareimg.jpg"/></a>
  		</div>
  		<div class="minisharecontainers">
  			<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url='.$pagelink.'&amp;title='.$title.'&amp;summary='.$headerminidescription.'" target="_blank"><img alt="" src="'.$host_addr.'/images/linkedinshareimg.jpg" /></a>
  		</div>
      <div class="minisharecontainers">
        <a href="http://twitter.com/home?status='.$pagelink.'" target="_blank"><img src="../images/twittershareimg.jpg"></a>
      </div>
      <div class="minisharecontainers">
      <a href="https://plus.google.com/share?url='.$pagelink.'"target="_blank">
        <img src="../images/googleshareimg.jpg">
      </a>
      </div>
  ';
  $row['coverphotoset']==0?$floatsetout="Left":($row['coverphotoset']==1?$floatsetout="New Line":($row['coverphotoset']==2?$floatsetout="Right":$floatsetout=""));


  $row['adminoutputtwo']='
    <script src="'.$host_addr.'scripts/js/tinymce/jquery.tinymce.min.js"></script>
    <script src="'.$host_addr.'scripts/js/tinymce/tinymce.min.js"></script>
    <script>
        //reload tinymce to see this DOM entry
        $(document).ready(function(){
        /*$.cachedScript( "'.$host_addr.'scripts/js/tinymce/jquery.tinymce.min.js" ).done(function( script, textStatus ) {
          console.log( textStatus );
        });
        $.cachedScript( "'.$host_addr.'scripts/js/tinymce/tinymce.min.js" ).done(function( script, textStatus ) {
          console.log( textStatus );
        });
        $.cachedScript( "'.$host_addr.'scripts/js/tinymce/basic_config.js" ).done(function( script, textStatus ) {
          console.log( textStatus );
        });
        */
            });
        tinyMCE.init({
                theme : "modern",
                selector: "textarea#adminposter",
                skin:"lightgray",
                width:"94%",
                height:"650px",
                external_image_list_url : ""+host_addr+"snippets/mceexternalimages.php",
                plugins : [
                 "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                 "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                 "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
                ],
                // toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
                toolbar1: "undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
                image_advtab: true ,
                editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
                content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
                external_filemanager_path:""+host_addr+"scripts/filemanager/",
                filemanager_title:"Muyiwa Afolabi\'s Admin Blog Content Filemanager" ,
                external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
        });
        tinyMCE.init({
                theme : "modern",
                selector:"textarea#postersmalltwo",
                menubar:false,
                statusbar: false,
                plugins : [
                 "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                 "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                 "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
                ],
                width:"80%",
                height:"300px",
                toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                toolbar2: "| link unlink anchor | emoticons",
                image_advtab: true ,
                editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
                content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
           external_filemanager_path:""+host_addr+"scripts/filemanager/",
           filemanager_title:"Muyiwa Afolabi\'s Admin Blog Content Filemanager" ,
           external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
        });          
    </script>
  	<div id="form" style="background-color:#fefefe;">
  		<form action="../snippets/edit.php" name="editblogpost" method="post" enctype="multipart/form-data">
  			<input type="hidden" name="entryvariant" value="editblogpost"/>
  			<input type="hidden" name="entryid" value="'.$id.'"/>
  			<div id="formheader">Edit '.$title.'</div>
  				<div id="formend" style="'.$editcoverphotostyle.'">
  					Change Cover Photo <br>
  					<input type="file" placeholder="Choose image" name="profpic" class="curved"/>
  				</div>
  				<div id="formend" style="'.$editcoverphotofloatstyle.'">
  				Cover Photo Float(Controls the position of the cover photo image, currently set to "'.$floatsetout.'")<br>
  					<select name="coverstyle" class="curved2">
  						<option value="">Change Status</option>
  						<option value="0" title="The Blog text starts inline beside the cover photo on it\'s left">Left</option>
  						<option value="1" title="The Blog text starts underneath the cover photo">New Line</option>
  						<option value="2" title="The Blog text starts inline beside the cover photo on it\'s right">Right</option>
  					</select>
  				</div>
  				<div id="formend">
  				Manually share this post, this is done as a site user so if you want to post to muyiwa afolabi\'s account be sure to log in as him first then share, don\'t worry the dialog box for sharing will have a login interface for you to do so unless you have logged in as someone else to any of these social networks, in that case you would have to log out then login as the social account you want to share this under, then you can post. <br>
  				<div id="elementholder" style="float:none; margin:auto;">
  				'.$blogplatformminishares.'
  				</div>
  				</div>
  				<div id="formend">
  					Change Title<br>
  					<input type="text" style="max-width:98%;width:87%;" placeholder="Blog Title" name="title" style="width:90%;" value="'.$title.'" class="curved"/>
  				</div>
          '.$extraformdata.'
  				<div id="formend" style="'.$editintroparagraphstyle.'">
  					<span style="font-size:18px;">Change Introductory Paragraph:</span><br>
  					<textarea name="introparagraph" id="postersmalltwo" Placeholder="" class="">'.$introparagraph.'</textarea>
  				</div>
  				<div id="formend" style="'.$editblogpoststyle.'">
  					<span style="font-size:18px;">Change The Blog Post:</span><br>
  					<textarea name="blogentry" id="adminposter" Placeholder="" class="curved3">'.$blogpost.'</textarea>
  				</div>

  				'.$commentcontent.'
  				<div id="formend">
  					Change Status<br>
  					<select name="status" class="curved2">
  						<option value="">Change Status</option>
  						<option value="active">Active</option>
  						<option value="inactive">Inactive</option>
  					</select>
  				</div>
  			<div id="formend">
  				<input type="submit" name="updateblogentry" value="Update" class="submitbutton"/>
  			</div>
  		</form>
  	</div>
  ';
  $row['blogpageshare']='
    <div class="mainblogshare">
    	<div class="fb-like" data-href="'.$pagelink.'" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
    </div>
    <div class="mainblogshare">
    	<div class="g-plus" data-action="share" data-annotation="bubble" data-href="'.$pagelink.'"></div>
    </div>
    <div class="mainblogshare">
    	<a href="https://twitter.com/share" class="twitter-share-button" data-url="'.$pagelink.'">Tweet</a>
    </div>
    <div class="mainblogshare">
    	<script type="IN/Share" data-url="'.$pagelink.'" data-counter="right"></script>
    </div>
  ';
  // for love language share
  $row['blogpagesharell']='
    <li>
      <a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]='.$pagelink.'&p[images][0]='.$absolutecover.'&p[title]='.$title.'&p[summary]='.$headerdescription.'" title="Share to Facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
    <li>
      <a href="http://twitter.com/home?status='.$pagelink.'" title="Share to Twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>
    <li>
      <a href="https://plus.google.com/share?url='.$pagelink.'" title="Share to Google+" target="_blank"><i class="fa fa-google-plus"></i></a>
    </li>
  ';
  // for CSI share
  $row['blogpagesharecsi']='
    <li>
      <a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]='.$pagelink.'&p[images][0]='.$absolutecover.'&p[title]='.$title.'&p[summary]='.$headerdescription.'" title="Share to Facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
    <li>
      <a href="http://twitter.com/home?status='.$pagelink.'" title="Share to Twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>
    <li>
      <a href="https://plus.google.com/share?url='.$pagelink.'" title="Share to Google+" target="_blank"><i class="fa fa-google-plus"></i></a>
    </li>
  ';
  $row['blogminioutput']='
    <div id="miniblogposthold">
    	<a href="'.$pagelink.'" title="'.$headerdescription.'">
    		<img src="'.$absolutecover.'"/>
    		'.$title.'.</a>
    		<span name="miniblogviewshold">Views '.$views.'</span><br><span name="miniblogviewshold">From '.$blogtypedata['name'].' under:<br> <span style="color:#F08D8D;">'.$blogcategorydata['catname'].'</span>
    </div>
  ';  
  $row['blogminioutputfjc']='
    <li>
      <div id="miniblogposthold">
        <a href="'.$pagelink.'" title="'.$headerdescription.'">
          <img src="'.$absolutecover.'"/>
          '.$title.'.</a><br>
          <span name="miniblogviewshold">Views '.$views.'</span><br><span name="miniblogviewshold">From '.$blogtypedata['name'].' under:<br> <span style="color:#F08D8D;">'.$blogcategorydata['catname'].'</span>
      </div>
    </li>
  '; 
  $row['blogminioutputfc']='
    <li>
        <a href="'.$pagelink.'" class="minilink" title="'.$headerdescription.'">
          <!--<img src="'.$absolutecover.'" class="miniimgout"/>-->
          '.$title.'.</a><br>
          <span name="miniblogviewshold">Views '.$views.'</span><!--<span name="miniblogviewshold">From '.$blogtypedata['name'].' under:<br> <span class="spspan">'.$blogcategorydata['catname'].'</span>-->
    </li>
  ';  
  $row['blogminioutputll']='
      <li>
        <div class="image">
          <a href="'.$pagelink.'">
            <img src="'.$absolutecover.'" alt="'.$blogcategorydata['catname'].'">
          </a>
        </div>
        <h3><a href="'.$pagelink.'">'.$title.'</a></h3>
        <time datetime="'.$entrydate.'">'.$maindayout.'</time>
      </li>
  ';
  $row['blogminioutputcsi']='
      <div class="item clearfix">
          <div class="medium-12 columns">
              <img alt="" src="'.$absolutecover.'" class="blog-cover-photo"/>
          </div>
          <div class="medium-12 columns">
              <h1 class="title">'.$title.'</h1>
              <span class="date"><i class="fa fa-calendar"></i> on '.date("F",strtotime($entrydatem)).' '.date("d",strtotime($entrydatem)).', '.date("Y",strtotime($entrydatem)).'.</span>
              <ul class="small-block-grid-2">
                  <li>
                      <ul class="socials">
                          <li>
                            <a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]='.$pagelink.'&p[images][0]='.$absolutecover.'&p[title]='.$title.'&p[summary]='.$headerdescription.'" title="Share to Facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
                          <li>
                            <a href="http://twitter.com/home?status='.$pagelink.'" title="Share to Twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>
                          <li>
                            <a href="https://plus.google.com/share?url='.$pagelink.'" title="Share to Google+" target="_blank"><i class="fa fa-google-plus"></i></a>
                          </li>
                      </ul>
                      <ul class="socials">
                          <li class="share-list-point-small"><span><i class="fa fa-comments"></i> '.$commentdata['commentcount'].'</span></li>
                          <li class="share-list-point-small"><span><i class="fa fa-eye"></i> '.$views.'</span></li>
                      </ul>
                  </li>
              </ul>
              <div class="item-content">'.substr($headerdescription, 0,200).'</div>
              <a href="'.$pagelink.'" class="button" target="_blank">More</a>
          </div>
      </div><!-- /.item -->
      
  ';
  $row['blogtinyoutput']='
  <div id="postundercat">
  	<div id="postundercatleft"><a href="'.$pagelink.'">'.$title.'</a></div>
  	<div id="postundercatright">'.$entrydate.'</div>
  </div>
  ';
  $row['blogtinyoutputcsi']='
    <div class="row item">
        <div class="medium-4 columns photo">
            <img alt="" src="'.$absolutecover.'">
        </div>
        <div class="medium-8 columns">
            <h1 class="title">'.$title.'</h1>
            <span class="date"><i class="fa fa-calendar"></i> on '.date("F",strtotime($entrydatem)).' '.date("d",strtotime($entrydatem)).', '.date("Y",strtotime($entrydatem)).'</span><br>
            <a href="'.$pagelink.'" target="_blank" class="button">more</a>
        </div>
    </div><!-- /.item -->
  ';
  $row['vieweroutput']='
    <div id="bloghold">
    	<div id="blogheader">
    		<span name="title">'.$title.'.</span>
    		<div id="blogheaderdetailshold">
    			<div id="blogheaderdetailsleft">
    				By Muyiwa Afolabi, On '.$entrydateout.', in the Category <a href="'.$host_addr.'category.php?cid='.$blogtypeid.'" target="_blank"><span name="titletype">'.$blogcategorydata['catname'].'</span></a>.Total Views '.$views.'.
    			</div>
    			<div id="blogheaderdetailsright"><img src="./images/comments.png"/> '.$commentdatatwo['commentcount'].' Comments</div>
    		</div>
    	</div>
    	<div id="blogbody">
          '.$viewerbodyoutone.'
    		<div id="blogreadermorehold">
    			<a href="'.$pagelink.'" target="_blank" title="Continue reading this post">'.$linkcontentout.'</a>
    		</div>
    	</div>
    	<div id="blogfooter">
    		'.$blogplatformshares.'
    	</div>
    </div>
  ';
  $row['vieweroutputtwo']='
    <div id="bloghold">
    	<div id="blogheader">
    		<span name="title">'.$title.'.</span>
    		<div id="blogheaderdetailshold">
    			<div id="blogheaderdetailsleft">
    				By Muyiwa Afolabi, On '.$entrydate.'. Total Views '.$views.'.
    			</div>
    			<div id="blogheaderdetailsright"><img src="'.$host_addr.'images/comments.png"/> '.$commentdata['commentcount'].' Comments</div>
    		</div>
    	</div>
    	<div id="blogbody">
    		<img src="'.$absolutecover.'"/>'.$introparagraph.'
    		<div id="blogreadermorehold">
    			<a href="'.$pagelink.'" target="_blank" title="Continue reading this post">Continue Reading</a>
    		</div>
    	</div>
    	<div id="blogfooter">
    		'.$blogplatformshares.'
    	</div>
    </div>
  ';
  // for Frontiers Job Connect FJC
  $row['vieweroutputthree']='
      <div class="postholder">
        <div class="image">
          <img src="'.$absolutecover.'" alt="">
          <div class="minisocialfjc">
            <a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]='.$pagelink.'&p[images][0]='.$absolutecover.'&p[title]='.$title.'&p[summary]='.$headerdescription.'" target="_blank" class="btn btn-default fa fa-facebook minisocial fbookshare"></a>
            <a href="http://twitter.com/home?status='.$pagelink.'" target="_blank" class="btn btn-default fa fa-twitter minisocial twshare"></a>
            <a href="https://plus.google.com/share?url='.$pagelink.'" target="_blank" class="btn btn-default fa fa-google minisocial gshare"></a>
            <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url='.$pagelink.'&amp;title='.$title.'&amp;summary='.$headerminidescription.'" target="_blank" class="btn btn-default fa fa-linkedin minisocial lishare"></a>
          </div>
        </div>
        <div class="content">
          <h6>'.$title.'</h6>
          <span class="location">By Frontiers Consulting on '.$entrydateout.'. <br>Total Views '.$views.'. Comments: '.$commentdatatwo['commentcount'].'</span>
          <div>'.$introparagraph.'<a href="'.$pagelink.'" target="_blank" class="read-more">Read More</a></div>
        </div>
      </div>
  ';
  // for love language

  $row['vieweroutputfour']='
      <article class="post format-standard">
        <div class="image">
          <a href="'.$pagelink.'"><img src="'.$absolutecover.'" alt="'.$blogcategorydata['catname'].'" /></a>
          <div class="category">'.$blogcategorydata['catname'].'</div>
        </div>
        <div class="desc">
          <div class="post-desc-top">
            <ul>
              <li>Muyiwa Afolabi</li>
              <li class="separate"></li>
              <li>'.$maindayout.'</li>
            </ul>
            <h3><a href="'.$pagelink.'">'.$title.'</a></h3>
          </div>
          <div class="post-desc-bottom">
            <div>'.$introparagraph.'</div>
            <ul class="post-social-icons">
              <li class="like"><a href="##"><i class="fa fa-eye"></i> '.$views.'</a></li>
              <li class="comment"><a href="##"><i class="fa fa-comment"></i> '.$commentdata['commentcount'].'</a></li>
              <li class="share">
                <a><i class="fa fa-share-alt"></i></a>
                <div class="share-social-media-links">
                  <ul>
                    <li>
                    <a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]='.$pagelink.'&p[images][0]='.$absolutecover.'&p[title]='.$title.'&p[summary]='.$headerdescription.'" title="Share to Facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    <li>
                    <a href="http://twitter.com/home?status='.$pagelink.'" title="Share to Twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>
                    <li>
                    <a href="https://plus.google.com/share?url='.$pagelink.'" title="Share to Google+" target="_blank"><i class="fa fa-google-plus"></i></a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
            <a class="more-button" href="'.$pagelink.'"><span>Read More</span></a>
          </div>
        </div>
      </article>
  ';
  // for csi outreach blog multiples
  $row['vieweroutputfive']='
    <div class="blog-item">
        <div class="element">'.$viewerbodyoutone.'</div>
        <div class="info">
            <div class="date">
                <span class="year">'.date("d",strtotime($entrydatem)).'</span>
                <span class="month">'.date("M",strtotime($entrydatem)).'</span>
            </div>
            <div class="format">
                <i class="'.$defaultcsipostflaticon.'"></i>
            </div>
            <h3 class="title"><a href="'.$pagelink.'">'.$title.'</a></h3>
            <div class="meta">
                <ul>
                    <li>By <a href="http://muyiwaafolabi.com/profile.php" title="Posts by Muyiwa Afolabi" rel="author">Muyiwa Afolabi</a></li>
                    <li>In <a href="'.$host_addr.'csicategory.php?cid='.$blogcategorydata['id'].'" rel="category tag">'.$blogcategorydata['catname'].'</a></li>
                    <li class="share-list-point">Share:</li>
                    <li class="share-list">
                      <a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]='.$pagelink.'&p[images][0]='.$absolutecover.'&p[title]='.$title.'&p[summary]='.$headerdescription.'" title="Share to Facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    </li>
                    <li class="share-list">
                      <a href="http://twitter.com/home?status='.$pagelink.'" title="Share to Twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>
                    </li>
                    <li class="share-list">
                      <a href="https://plus.google.com/share?url='.$pagelink.'" title="Share to Google+" target="_blank"><i class="fa fa-google-plus"></i></a>
                    </li>


                    <li class="share-list-point"><span><i class="fa fa-eye"></i> '.$views.'</span></li>
                    <li class="share-list-point"><span><i class="fa fa-comments"></i> '.$commentdata['commentcount'].'</span></li>
                </ul>
            </div>
            <div>
                '.$introparagraph.'
                <a href="'.$pagelink.'" target="_blank" class="button">'.$linkcontentout.'</a>
            </div>
        </div><!-- /.info -->
    </div><!-- /.blog-item -->
  ';
  // for csi outreach blog single
  $row['vieweroutputcsiblogsingle']='
    <div class="blog-item">
        <div class="element">'.$viewerbodyoutone.'</div>
        <div class="info">
            <div class="date">
                <span class="year">'.date("d",strtotime($entrydatem)).'</span>
                <span class="month">'.date("M",strtotime($entrydatem)).'</span>
            </div>
            <div class="format">
                <i class="'.$defaultcsipostflaticon.'"></i>
            </div>
            <h3 class="title"><a href="'.$pagelink.'">'.$title.'</a></h3>
            <div class="meta">
                <ul>
                    <li>By <a href="http://muyiwaafolabi.com/profile.php" title="Posts by Muyiwa Afolabi" rel="author">Muyiwa Afolabi</a></li>
                    <li>In <a href="'.$host_addr.'csicategory.php?cid='.$blogcategorydata['id'].'" rel="category tag">'.$blogcategorydata['catname'].'</a></li>
                    <li class="share-list-point">Share:</li>
                    <li class="share-list">
                      <a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]='.$pagelink.'&p[images][0]='.$absolutecover.'&p[title]='.$title.'&p[summary]='.$headerdescription.'" title="Share to Facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    </li>
                    <li class="share-list">
                      <a href="http://twitter.com/home?status='.$pagelink.'" title="Share to Twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>
                    </li>
                    <li class="share-list">
                      <a href="https://plus.google.com/share?url='.$pagelink.'" title="Share to Google+" target="_blank"><i class="fa fa-google-plus"></i></a>
                    </li>


                    <li class="share-list-point"><span><i class="fa fa-eye"></i> '.$views.'</span></li>
                    <li class="share-list-point"><span><i class="fa fa-comments"></i> '.$commentdata['commentcount'].'</span></li>
                </ul>
            </div>
            <div>
                '.$blogpost.'
            </div>
        </div><!-- /.info -->
    </div><!-- /.blog-item -->
  ';
  return $row;
}
function getAllBlogEntries($viewer,$limit,$typeid,$type){
  global $host_addr,$host_target_addr;
  $testit=strpos($limit,"-");
  $testit!==false?$limit="":$limit=$limit;
  // echo $testit."testitval".$limit;
  $row=array();
  if(!is_array($viewer)&&$viewer=="admin"){	
  if($type=="category"){
  if($limit==""){
  $query="SELECT * FROM blogentries WHERE blogcatid=$typeid order by id desc LIMIT 0,15";
  $rowmonitor['chiefquery']="SELECT * FROM blogentries WHERE blogcatid=$typeid order by id desc";
  // echo $query.$rowmonitor['chiefquery'];
  }else{
  $query="SELECT * FROM blogentries WHERE blogcatid=$typeid order by id desc $limit";
  $rowmonitor['chiefquery']="SELECT * FROM blogentries WHERE blogcatid=$typeid order by id desc";	
  }
  }elseif ($type=="blogtype") {
  	# code...
  if($limit==""){
  $query="SELECT * FROM blogentries WHERE blogtypeid=$typeid order by id desc LIMIT 0,15";
  $rowmonitor['chiefquery']="SELECT * FROM blogentries WHERE blogtypeid=$typeid order by id desc";
  }else{
  $query="SELECT * FROM blogentries WHERE blogtypeid=$typeid order by id desc $limit";
  $rowmonitor['chiefquery']="SELECT * FROM blogentries WHERE blogtypeid=$typeid order by id desc";	
  }
  }
  }else if(!is_array($viewer)&&$viewer=="viewer"){
  if($type=="category"){
    if($limit==""){
      $query="SELECT * FROM blogentries WHERE blogcatid=$typeid AND status='active' order by id desc LIMIT 0,15";
      $rowmonitor['chiefquery']="SELECT * FROM blogentries WHERE blogcatid=$typeid AND status='active' order by id desc";
      // echo $query.$rowmonitor['chiefquery'];
    }else{
      $query="SELECT * FROM blogentries WHERE blogcatid=$typeid AND status='active' order by id desc $limit";
      $rowmonitor['chiefquery']="SELECT * FROM blogentries WHERE blogcatid=$typeid AND status='active' order by id desc";	
    }
  }elseif ($type=="blogtype") {
  	# code...
    if($limit==""){
      $query="SELECT * FROM blogentries WHERE blogtypeid=$typeid AND status='active' order by id desc LIMIT 0,15";
      $rowmonitor['chiefquery']="SELECT * FROM blogentries WHERE blogtypeid=$typeid AND status='active' order by id desc";
    }else{
      $query="SELECT * FROM blogentries WHERE blogtypeid=$typeid AND status='active' order by id desc $limit";
      $rowmonitor['chiefquery']="SELECT * FROM blogentries WHERE blogtypeid=$typeid AND status='active' order by id desc";	
    }
  }
  }elseif (is_array($viewer)) {
    # code...
    $rviewer=$viewer[0];
    if($rviewer=="admin"&&$limit==""){
      $query=$viewer[1]." LIMIT 0,15";
      $rowmonitor['chiefquery']=$viewer[1];
    }if($rviewer=="admin"&&$limit!==""){
      $query=$viewer[1].$limit;
      $rowmonitor['chiefquery']=$viewer[1];
    }elseif($rviewer=="viewer"&&$limit==""){
      $query=$viewer[1]." AND status='active' LIMIT 0,15";
      $rowmonitor['chiefquery']=$viewer[1];
    }elseif ($rviewer=="viewer"&&$limit!=="") {
      # code...
      $query=$viewer[1]." AND status='active'$limit";
      $rowmonitor['chiefquery']=$viewer[1];
    }
    $type="search";
  }
  $run=mysql_query($query)or die(mysql_error()." Line 1384");
  $numrows=mysql_num_rows($run);
  $row['adminoutput']="No Entries yet here";
  $row['vieweroutput']="No Entries yet here";
  $row['tinyoutput']="No more entries here";
  $row['popularposts']="<br>No posts here yet";
  $row['recentposts']="<br>No Posts here yet";
  $row['adminoutputtwo']="";
  $row['vieweroutputtwo']="";
  $row['vieweroutputthree']="No Entries yet here";
  $row['vieweroutputfour']="No Entries yet here";
  $row['vieweroutputfive']="No Entries yet here";
  $row['chiefquery']=$rowmonitor['chiefquery'];
  $row['numrows']=$numrows;
  if($numrows>0){
  	$adminoutput="";
  	$vieweroutput="";
    $vieweroutputtwo="";
    $vieweroutputthree="";
    $vieweroutputfour="";
  	$vieweroutputfive="";
  	$tinyoutput="";
  $row['adminoutput']="";
  $row['vieweroutput']="";
  $row['tinyoutput']="";

  while($row=mysql_fetch_assoc($run)){
  	$id=$row['id'];
    $blogpostdata=getSingleBlogEntry($id);
    $adminoutput.=$blogpostdata['adminoutput'];
    $vieweroutput.=$blogpostdata['vieweroutput'];
    $vieweroutputtwo.=$blogpostdata['vieweroutputtwo'];
    $vieweroutputthree.=$blogpostdata['vieweroutputthree'];
    $vieweroutputfour.=$blogpostdata['vieweroutputfour'];
    $vieweroutputfive.=$blogpostdata['vieweroutputfive'];
    $tinyoutput.=$blogpostdata['blogtinyoutput'];
  }
  $top='<table id="resultcontenttable" cellspacing="0">
  			<thead><tr><th>Coverphoto</th><th>PageAddress</th><th>Blogtype</th><th>Category</th><th><img src="'.$host_addr.'images/comments.png" style="height:20px;margin:auto;"></th><th>Views</th><th>PostDate</th><th>LastModified</th><th>Status</th><th>View/Edit</th></tr></thead>
  			<tbody>';
  $bottom='	</tbody>
  		</table>';
  $outs=paginatejavascript($rowmonitor['chiefquery']);
  $outsviewer=paginate($rowmonitor['chiefquery']);
    $testq=strpos($rowmonitor['chiefquery'],"%'");
    if($testq===0||$testq===true||$testq>0){
  $rowmonitor['chiefquery']=str_replace("%","%'",$rowmonitor['chiefquery']);
    }
  $paginatetop='
  <div id="paginationhold">
  	<div class="meneame">
  		<input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
  		<input type="hidden" name="outputtype" value="blogpost'.$type.'"/>
  		<input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
  		<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
  		<div class="pagination">
  			  '.$outs['usercontrols'].'
  		</div>
  	</div>
  </div>
  <div id="paginateddatahold" data-name="contentholder">';

  $paginatebottom='
  </div><div id="paginationhold">
  	<div class="meneame">
  		<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
  	</div>
  </div>';
  $row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
  $row['adminoutputtwo']=$top.$adminoutput.$bottom;
  $row['vieweroutput']=$vieweroutput;
  $row['vieweroutputtwo']=$vieweroutputtwo;
  $row['vieweroutputthree']=$vieweroutputthree;
  $row['vieweroutputfour']=$vieweroutputfour;
  $row['vieweroutputfive']=$vieweroutputfive;
  $row['chiefquery']=$rowmonitor['chiefquery'];
  $row['tinyoutput']=$tinyoutput;
  $row['numrows']=$numrows;

  }

  $recents="No Posts yet";
  $recentsfjc="No Posts yet";
  $recentfc="<li>No Posts yet</li>";
  $popular="No posts yet";
  $popularfjc="No posts yet";
  $popularfc="<li>No Posts yet</li>";
  $recentspecific="No Posts yet";
  $recentspecificfjctwo="No Posts yet";
  $recentspecificfjc="No Posts yet";
  $recentspecificfc="<li>No Posts yet</li>";
  $recentspecificll="<li>No Posts yet</li>";
  $recentspecificcsi="<p>No Posts yet</p>";
  $recentspecificcsitwo="<li>No Posts yet</li>";

  $popularspecificfjc="No posts yet";
  $popularspecific="No posts yet";
  $popularspecificfjc="No posts yet";
  $popularspecificfc="<li>No Posts yet</li>";
  $popularspecificll="<li>No Posts yet</li>";
  $popularspecificcsi="<p>No Posts yet</p>";
  $popularspecificcsitwo="<li>No Posts yet</li>";
  $popularspecificfjc="No posts yet";
  $popularspecificfjctwo="No posts yet";
  global $host_minipagecount;
  if($viewer=="viewer"){
    // produce recent blog posts
    $recquery="SELECT * FROM blogentries WHERE status='active' order by id desc LIMIT 0,$host_minipagecount";
    $recrun=mysql_query($recquery)or die(mysql_error()." Line 1835");
    $recnumrows=mysql_num_rows($recrun);
    if($recnumrows>0){
      $recents="";
      $recentsfjc="";
      $recentsll="";
    	$recentscsi="";
      while($recrow=mysql_fetch_assoc($recrun)){
      $outrec=getSingleBlogEntry($recrow['id']);
      $recents.=$outrec['blogminioutput'];
      $recentsfjc.=$outrec['blogminioutputfjc'];
      $recentsll.=$outrec['blogminioutputll'];
      $recentscsi.=$outrec['blogtinyoutputcsi'];
      }
    }
    // produce popular blog posts general
    $popquery="SELECT * FROM blogentries WHERE status='active' order by views desc LIMIT 0,$host_minipagecount";
    $poprun=mysql_query($popquery)or die(mysql_error()." Line 1835");
    $popnumrows=mysql_num_rows($poprun);
    $popular="";
    $popularfjc="";
    $popularll="";
    $popularcsi="";
    if($popnumrows>0){
      while($poprow=mysql_fetch_assoc($poprun)){
        $outpop=getSingleBlogEntry($poprow['id']);
        $popular.=$outpop['blogminioutput'];
        $popularfjc.=$outpop['blogminioutputfjc'];
        $popularll.=$outpop['blogminioutputll'];
        $popularcsi.=$outpop['blogtinyoutputcsi'];
      }
    }
    $tipq=$type=="blogtype"?"WHERE blogtypeid=$typeid AND status='active'":"WHERE blogcatid=$typeid AND status='active'";
    // recentspceific blogposts
    $recquery="SELECT * FROM blogentries $tipq order by id desc LIMIT 0,$host_minipagecount";
    $recrun=mysql_query($recquery)or die(mysql_error()." Line 1835");
    $recnumrows=mysql_num_rows($recrun);
    if($recnumrows>0){
      $recentspecific="";
      $recentspecificfjc="";
      $recentspecificfc="";
      $recentspecificll="";
      $recentspecificcsi="";
      $recentspecificcsitwo="";
      $recentspecificfjctwo="";
      $recount=0;
      while($recrow=mysql_fetch_assoc($recrun)){
        $outrec=getSingleBlogEntry($recrow['id']);
        $recentspecific.=$outrec['blogminioutput'];
        $recentspecificfjc.=$outrec['blogminioutputfjc'];
        $recentspecificll.=$outrec['blogminioutputll'];
        $recentspecificcsi.=$outrec['blogminioutputcsi'];
        $recentspecificcsitwo.=$outrec['blogtinyoutputcsi'];
        $recount<2?$recentspecificfc.=$outrec['blogminioutputfc']:$noval="";
        $recentspecificfjctwo.=$outrec['vieweroutputthree'];
        $recount++;
      }
    }
    // produce popular specific blog posts general
    $popquery="SELECT * FROM blogentries $tipq order by views desc LIMIT 0,$host_minipagecount";
    $poprun=mysql_query($popquery)or die(mysql_error()." Line 1835");
    $popnumrows=mysql_num_rows($poprun);
    if($popnumrows>0){
      $popularspecific="";
      $popularspecificfjc="";
      $popularspecificfc="";
      $popularspecificll="";
      $popularspecificcsi="";
      $popularspecificcsitwo="";
      $popularspecificfjctwo="";
      $pcount=0;
      while($poprow=mysql_fetch_assoc($poprun)){
        $outpop=getSingleBlogEntry($poprow['id']);
        $popularspecific.=$outpop['blogminioutput'];
        $popularspecificfjc.=$outpop['blogminioutputfjc'];
        $popularspecificll.=$outpop['blogminioutputll'];
        $popularspecificcsi.=$outpop['blogminioutputcsi'];
        $popularspecificcsitwo.=$outpop['blogtinyoutputcsi'];
        $pcount<2?$popularspecificfc.=$outpop['blogminioutputfc']:$noval="";
        $pcount++;
      }
    }
  }
      $row['popularposts']=$popular;
      $row['popularpostsfjc']=$popularfjc;
      $row['recentposts']=$recents;
      $row['recentpostsfjc']=$recentsfjc;
      $row['popularpostspecific']=$popularspecific;
      $row['popularpostspecificfjc']=$popularspecificfjc;
      $row['popularpostspecificfc']=$popularspecificfc;
      $row['popularpostspecificll']=$popularspecificll;
      $row['popularpostspecificcsi']=$popularspecificcsi;
      $row['popularpostspecificcsitwo']=$popularspecificcsitwo;

      $row['recentpostspecific']=$recentspecific;
      $row['recentpostspecificfjc']=$recentspecificfjc;
      $row['recentpostspecificfc']=$recentspecificfc;
      $row['recentpostspecificll']=$recentspecificll;
      $row['recentpostspecificcsi']=$recentspecificcsi;
      $row['recentpostspecificcsitwo']=$recentspecificcsitwo;
      $row['recentpostspecificfjctwo']=$recentspecificfjctwo;
      // get most commented post
      $mostcquery="SELECT * , COUNT(`blogentryid` ) AS  `count` FROM comments WHERE status ='active' AND EXISTS( SELECT * FROM `blogentries` WHERE id=`comments`.`blogentryid` AND blogtypeid=2) GROUP BY blogentryid ORDER BY count DESC"; 
      $mostcrun=mysql_query($mostcquery)or die(mysql_error()." Line ".__LINE__);
      $mostcnumrows=mysql_num_rows($mostcrun);
      $mostcvieweroutput="<p>Nothing comments yet</p>";
      $mostcadminoutput="<p>Nothing comments yet</p>";
      if($mostcnumrows>0){
        $mostcvieweroutput="";
        $mostcadminoutput="";
        $mostcrow=mysql_fetch_assoc($mostcrun);
        $mostcdata=getSingleBlogEntry($mostcrow['blogentryid']);
        $mostcvieweroutput=$mostcdata['vieweroutput'];
        $mostcadminoutput=$mostcdata['adminoutput'];
        if($mostcdata['blogtypeid']==2){
          // for csi
          // echo "got through";
          $mostcvieweroutput=$mostcdata['blogminioutputcsi'];
        }
      }
      $row['mostcommentedpostviewer']=$mostcvieweroutput;
      $row['mostcommentedpostadmin']=$mostcadminoutput;

      return $row;
}
function blogPageCreate($blogentryid){
	global $host_addr,$host_target_addr;
	$outs=getSingleBlogEntry($blogentryid);
  $c="";
  if(isset($_GET['c'])&&$_GET['c']){
      $c=$_GET['c'];
    }
    $alerter="";
  if($c=="true"){
  $alerter="Thank you for dropping your comment, it will be made visible shortly<br>";
  }
  $securitynumber=rand(0,10).rand(1,8).rand(0,5).rand(10,30).rand(50,80).rand(34,55).rand(46,57);
  $blogtypeid=$outs['blogtypeid'];
  $blogcatid=$outs['blogcatid'];
  $outs2=getSingleBlogType($outs['blogtypeid']);
  $outs3=getSingleBlogCategory($outs['blogcatid']);
  $coverset=$outs['coverphotoset'];
  $coverstyle="";
  if($coverset==1){
  $coverstyle='style="float:none; display:block; margin:auto;"';
  }else if($coverset==2){$coverstyle='style="float:right;"';
  }
  $logocontrol='<img src="'.$host_addr.'images/muyiwalogo5.png" class="total">';
  $sociallinks='
  	<div id="sociallinks">
  		<div id="socialholder" name="socialholdfacebook"><a href="http://www.facebook.com/franklyspeakingwithmuyiwaafolabi" target="_blank"><img src="../../images/Facebook-Icon.png" class="total"/></a></div>
  		<div id="socialholder" name="socialholdlinkedin"><a href="http://www.linkedin.com/profile/view?id=37212987" target="_blank"><img src="../../images/Linkedin-Icon.png" class="total"/></a></div>
  		<div id="socialholder" name="socialholdtwitter"><a href="http://www.twitter.com/franklyafolabi" target="_blank"><img src="../../images/Twitter-Icon.png" class="total"/></a></div>
  		<div id="socialholder" name="socialholdgoogleplus"><a href="https://plus.google.com/u/0/115702519121823219689/posts" target="_blank"><img src="../../images/google-plus-icon.png" class="total"/></a></div>
  		<div id="socialholder" name="socialholdyoutube"><a href="http://www.youtube.com/channel/UCYIZaonCbNxdLBrKpTQdYXA" target="_blank"><img src="../../images/YouTube-Icon.png" class="total"/></a></div>
  	</div>
  ';
  $footer='&copy; Muyiwa Afolabi '.date('Y').' Developed by Okebukola Olagoke.';
  $toplinks='
  				<a href="../../index.php" name="home" title="Welcome to Muyiwa AFOLABI\'S Website"><li class="">Home</li></a>
  				<a href="../../frontiersconsulting.php" name="frontiers" title="Frontiers International Services" class=""><li>Frontiers Consulting</li></a>
  				<a href="../../blog.php" name="blog" title="Check Out Muyiwa\'s Blog to get at his business and career radio talkshow content" class=""><li>Muyiwa\'s Blog</li></a>
          		<a href="http://frontiersjobconnect.com" name="frontiersjobconnect" title="" class=""><li>Frontiers Job-Connect</li></a>
  				<a href="http://csioutreach.org/" name="csi" title="Click to learn more about this social reformation project" class=""><li>CSI Outreach</li></a>
  				<a href="../../lovelanguage.php" name="lovelanguage" class=""><li>Love Language</li></a>
          		<a href="../../ownyourowntwo.php" name="ownyourown" title="" class=""><li>Own Your Own</li></a>
				<a href="../../onlinestore.php" name="onlinestore" title="Get all of Muyiwa Afolabi\'s posts in audio form and more" class="<?php echo $activemainlink8;?>"><li>Online Store</li></a>
  ';
  $headerdescription = convert_html_to_text($outs['introparagraph']);
  $headerdescription=html2txt($headerdescription);
  $nextblogout="End of posts here";
  $nextlink="##";
  $nextquery="SELECT * FROM blogentries WHERE blogtypeid=$blogtypeid AND id>$blogentryid AND status='active'";
  $nextrun=mysql_query($nextquery)or die(mysql_error()." Line 1847");
  $nextnumrows=mysql_num_rows($nextrun);
  if($nextnumrows>0){
  	$nextrow=mysql_fetch_assoc($nextrun);
  $nextouts=getSingleBlogEntry($nextrow['id']);
  	$nextblogout=$nextouts['title'];
  	$nextlink=$nextouts['pagelink'];
  }

  $prevblogout="End of posts here";
  $prevlink="##";
  $prevquery="SELECT * FROM blogentries WHERE blogtypeid=$blogtypeid AND id<$blogentryid AND status='active' ORDER BY id DESC";
  // $previd=$blogentryid-1;
  $prevrun=mysql_query($prevquery)or die(mysql_error()." Line 1847");
  $prevnumrows=mysql_num_rows($prevrun);
  if($prevnumrows>0){
  	$prevrow=mysql_fetch_assoc($prevrun);	
  $prevouts=getSingleBlogEntry($prevrow['id']);
  	$prevblogout=$prevouts['title'];
  	$prevlink=$prevouts['pagelink'];
  }

  // produce recent blog posts
  $recents="";
  $recquery="SELECT * FROM blogentries WHERE status='active' order by id desc LIMIT 0,5";
  $recrun=mysql_query($recquery)or die(mysql_error()." Line 1835");
  while($recrow=mysql_fetch_assoc($recrun)){
  $outrec=getSingleBlogEntry($recrow['id']);
  $recents.=$outrec['blogminioutput'];
  }
  // produce recent SPECIFIC blog posts
  $recentspecific="";
  $recquery="SELECT * FROM blogentries WHERE blogtypeid=$blogtypeid AND status='active' order by id desc LIMIT 0,5";
  $recrun=mysql_query($recquery)or die(mysql_error()." Line 1835");
  while($recrow=mysql_fetch_assoc($recrun)){
  $outrec=getSingleBlogEntry($recrow['id']);
  $recentspecific.=$outrec['blogminioutput'];
  }
  // produce popular blog posts
  $popular="";
  $popquery="SELECT * FROM blogentries WHERE status='active' order by views desc LIMIT 0,5";
  $poprun=mysql_query($popquery)or die(mysql_error()." Line 1835");
  while($poprow=mysql_fetch_assoc($poprun)){
  $outpop=getSingleBlogEntry($poprow['id']);
  $popular.=$outpop['blogminioutput'];
  }
  // produce popular SPECIFIC blog posts
  $popularspecific="";
  $popquery="SELECT * FROM blogentries WHERE blogtypeid=$blogtypeid AND status='active' order by views desc LIMIT 0,5";
  $poprun=mysql_query($popquery)or die(mysql_error()." Line 1835");
  while($poprow=mysql_fetch_assoc($poprun)){
  $outpop=getSingleBlogEntry($poprow['id']);
  $popularspecific.=$outpop['blogminioutput'];
  }
  //
  $catpostsquery="SELECT * FROM blogentries WHERE blogcatid=$blogcatid AND blogtypeid=$blogtypeid AND id<$blogentryid AND status='active' order by id desc";
  $catpostrun=mysql_query($catpostsquery)or die(mysql_error()." Line 1867");
  $catnumrow=mysql_num_rows($catpostrun);
  $tinyoutput="No more posts for this ";
  $count=0;
  $catids=array();
  $curlastid="";
  if($catnumrow>0){
    // echo $catnumrow;
  $tinyoutput="";
  while($catpostrow=mysql_fetch_assoc($catpostrun)){
  $outcatpost=getSingleBlogEntry($catpostrow['id']);
  $catids[]=$catpostrow['id'];
  if($count<15){
    // echo "inhere";
  $tinyoutput.=$outcatpost['blogtinyoutput'];
  $curlastid=$catpostrow['id'];
  }
  $count++;
  }
  }
  $lastvalidkey=array_search($curlastid,$catids);
  if($lastvalidkey<1||$lastvalidkey==""){
  $nextcatpostentryid=0;
  }else{
  $catpostnextid=$lastvalidkey+1;
  if(array_key_exists($catpostnextid,$catids)){

  $nextcatpostentryid=$catids[$catpostnextid];
  }else{
  	$nextcatpostentryid="";
  }
  if(!in_array($nextcatpostentryid,$catids)){
  $nextcatpostentryid=0;
  }
  }
  $commentcount=$outs['commentcount'];
  if($commentcount>0){
  $comments=$outs['viewercomment'];
  }else{
  	$comments="No comments yet";
  }
  $subimgpos='';
  $pagetag="";
  $descbanner="";
  $feedjitsidebar="";
  $quoteout="";
  $pagetypemini="";
  $topaudio="";
  if($outs2['name']=="Frankly Speaking With Muyiwa Afolabi"||$outs2['id']==1){
  	$pagetag='';
  	$pagetypemini="fs";
  $subimgpos='
  <div id="subimglogo" class="subimgpostwo">
  	<img src="../../images/franklyspeakingtwo.png" style="width: 100%;position: relative;left: 5px;top: 3px;" class="">
  </div>
  ';
  $descbanner='
    <div id="formend">
          <img src="'.$host_addr.'images/muyiwasblog.jpg" style="width:100%;"/>
    </div>
      ';
	  $feedjitsidebar=file_get_contents('../../snippets/feedjit.php');
	  $outsquote=getAllQuotes('viewer','','general');
	  $quoteout=$outsquote['randomoutput'];
	  $topaudioout=getAllTopAudio("viewer","");
	  $topaudio=$topaudioout['vieweroutput'];
  }elseif ($outs2['name']=="Christ Society International Outreach"||$outs2['id']==2) {
  	# code...
  	$pagetag='name="mainbodycsihold"';	
  	$pagetypemini="csi";
  $descbanner='';
  	$subimgpos='
  <div id="subimglogo" class="subimgposthree">
  	<img src="../../images/csi2.png" style="width: 100%;position: relative;left: 5px;top: 3px;" class="">
  </div>
  	';
  $feedjitsidebar=file_get_contents('../../snippets/feedjit.php');
  $outsquote=getAllQuotes('viewer','','general');
  $quoteout=$outsquote['randomoutput'];
  }elseif ($outs2['name']=="Project Fix Nigeria"||$outs2['id']==3) {
  	# code...
  	$pagetag='name="mainbodyholdtwo"';
  	$pagetypemini="pfn";
  $descbanner='';
  	$subimgpos='
  <div id="subimglogo" class="subimgposfour">
  	<img src="../../images/fsasmall.PNG" style="width: 100%;position: relative;left: 5px;top: 3px;" class=""/>
  </div>
  	';
  $feedjitsidebar=file_get_contents('../../snippets/feedjit.php');
  $outsquote=getAllQuotes('viewer','','pfn');
  $quoteout=$outsquote['randomoutputthree'];
  }else if($outs2['id']==4){
    $pagetag='name="mainblogoyohold"';
    $pagetypemini="oyo";
  $descbanner='';
    $subimgpos='
  <div id="subimglogo" class="subimgposfive">
    <img src="../../images/ownyourownthree.png" style="width: 91%;position: relative;left: 3px;top: 0px;" class=""/>
  </div>
    ';
  $feedjitsidebar=file_get_contents('../../snippets/feedjitblue.php');
  $outsquote=getAllQuotes('viewer','','general');
  $quoteout=$outsquote['randomoutput'];

  }
  $pagesidecontent='
  <div id="adcontentholdlong" style="">
  	Recent Posts<br>
  	'.$recents.'
  </div>
  <div id="adcontentholdlong" style="">
  	Popular Posts<br>
  	'.$popular.'
  </div>
  <div id="adcontentholdlong" style="" name="feedjit">
  	Visitors
  	  '.$feedjitsidebar.'  
  </div>
  ';
  $advertsidecontent='';
  $advertbottomcontent='';
  $adbannerquery="SELECT * FROM adverts WHERE type='banneradvert' and activepage='$pagetypemini' AND status='active' OR type='banneradvert' and activepage='all' AND status='active' order by id desc";
  $adbannerrun=mysql_query($adbannerquery)or die(mysql_error()."Line 2497");
  $adbannernumrow=mysql_num_rows($adbannerrun);
  if($adbannernumrow>0){
  while($adbannerrow=mysql_fetch_assoc($adbannerrun)){
  $adid=$adbannerrow['id'];
  $outbanner=getSingleAdvert($adid);
  $advertbottomcontent.=$outbanner['vieweroutput'];
  }
  }
  $adminiquery="SELECT * FROM adverts WHERE type='miniadvert' and activepage='$pagetypemini' AND status='active' OR type='videoadvert' and activepage='$pagetypemini' AND status='active' OR type='miniadvert' and activepage='all' AND status='active' OR type='videoadvert' and activepage='all' AND status='active' order by id desc";
  $adminirun=mysql_query($adminiquery)or die(mysql_error()."Line 2497");
  $admininumrow=mysql_num_rows($adminirun);
  if($admininumrow>0){
  while($adminirow=mysql_fetch_assoc($adminirun)){
  $adid=$adminirow['id'];
  $outmini=getSingleAdvert($adid);
  $advertsidecontent.=$outmini['vieweroutput'];
  }
  }
  $outgallery=getAllGalleries("viewer","");
  // echo $headerdescription;
  $maincontentstyle="";
  $adcontentholdstyle="";
  $adcontentholdlongstyle="";
  if($outs['blogentrytype']==""||$outs['blogentrytype']=="normal"){
  $blogdisplayoutput='
      <img class="blogcoverphoto" '.$coverstyle.' src="'.$outs['absolutecover'].'"/>
      '.$outs['blogpostout'].'
  ';
  }else if($outs['blogentrytype']=="video"){
  $blogdisplayoutput='
      '.$outs['blogpostout'].'
  ';
  }elseif ($outs['blogentrytype']=="gallery") {
    # code...
    $blogdisplayoutput=$outs['vfalbum'];
  }elseif ($outs['blogentrytype']=="banner") {
    # code...
  $blogdisplayoutput='
  <img src="'.$outs['bannermain'].'" style="width:100%;"/>
  ';
  $maincontentstyle='style="width:100%;"';
  $adcontentholdstyle='style="width:100%;"';
  $adcontentholdlongstyle='
  <style type="text/css">
    #adcontentholdlong{
    float: left;
  margin-left: 6%;
  max-width: 258px;
  }
  </style>
  ';
  }
  $outputs=array();
  $outputs['outputpageone']="";
  $ga="
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-49730962-1', 'muyiwaafolabi.com');
    ga('send', 'pageview');

  </script>
  ";
  $ogimage=str_replace(" ","%20",$outs['absolutecover']);
  /*CONTROL BLOCK for changing blog page output*/
  $switch="";
  if($blogtypeid=="5"){
    $switch="on";
    include('fjcblogpagemodule.php');
    $outputs=fjcblogPageCreate($blogentryid);
  }
  // csi switch
  if($blogtypeid=="2"){
    $switch="on";
    include('csiblogpagemodule.php');
    $outputs=csiblogPageCreate($blogentryid);
  }
  //love language switch
  if($blogtypeid=="6"){
    $switch="on";
    include('lovelanguageblogpagemodule.php');
    $outputs=llblogPageCreate($blogentryid);
  }
  if($outs['status']=="active"&&$switch==""){
  	$outputs['outputpageone']='
  	<!DOCTYPE html>
  	<html>
  	<head>
  	<title>'.stripslashes($outs['title']).'</title>
  	<meta http-equiv="Content-Language" content="en-us">
  	<meta charset="utf-8"/>
  	<meta http-equiv="Content-Type" content="text/html;"/>
  	<meta property="fb:app_id" content="578838318855511"/>
  	<meta property="fb:admins" content=""/>
  	<meta property="og:locale" content="en_US">
  	<meta property="og:type" content="website">
  	<meta property="og:title" content="'.stripslashes($outs['title']).'">
  	<meta property="og:description" content="'.$headerdescription.'">
  	<meta property="og:url" content="'.$outs['pagelink'].'">
  	<meta property="og:site_name" content="Muyiwa Afolabi\'s Website">
  	<meta property="og:image" content="'.$ogimage.'">
  	<meta name="keywords" content="'.stripslashes($outs['title']).', Muyiwa Afolabi, muyiwa afolabi, frontiers consulting, frankly speaking with muyiwa afolabi, frankly speaking, motivational speaker in nigeria, business strategists in the world, reformation packages, Christ Society International Outreach, Project Fix Nigeria, Own Your Own, Nigerian career radio talk show"/>
  	<meta name="description" content="'.stripslashes($outs['title']).''.$headerdescription.'">
    <link rel="stylesheet" href="../../stylesheets/font-awesome/css/font-awesome.css"/>
  	<link rel="stylesheet" href="../../stylesheets/muyiwamain.css"/>
  	<link rel="shortcut icon" type="image/png" href="../../images/muyiwaslogo.png"/>
  	<script src="../../scripts/jquery.js"></script>
  	<script src="../../scripts/mylib.js"></script>
    <script src="../../scripts/js/jquery.jplayer.min.js"></script>
  	<script src="../../scripts/muyiwasblog.js"></script>
  	<script src="../../scripts/formchecker.js"></script>
  	<script language="javascript" type="text/javascript" src="../../scripts/js/tinymce/tinymce.min.js"></script>
  	<script language="javascript" type="text/javascript" src="../../scripts/js/tinymce/basic_config.js"></script>
    </head>
    <body '.$pagetag.'>
      <div id="main">
        <div id="fb-root"></div>
       <script src="../../scripts/facebooksdk.js"></script>
       <script src="//platform.linkedin.com/in.js" type="text/javascript">
       lang: en_US
      </script>
      <div id="toppanel">
        <div id="mainheaderdesigndisplayhold">
          <div id="mainheaderdesigndisplay">
          </div>
        </div>
        <div id="mainlogopanel">
          <div id="mainimglogo" style="position:relative;">
            '.$logocontrol.'
          </div>
          '.$subimgpos.'
        </div>
        <div id="linkspanel">
          <ul>
          '.$toplinks.'
          </ul>
        </div>
      </div>

    <div id="contentpanel">
      <div id="contentmiddle">
        <div id="maincontenthold" '.$maincontentstyle.'>
            <div class="mainblogsharehold">
            Share this Post<br>
            '.$outs['blogpageshare'].'<br>
            <div id="formend" style="color: #F08D8D;font-size: 13px;text-align: left;">
            Subscribe to this Category\'s <a href="'.$host_addr.'feeds/rss/'.$outs3['rssname'].'.xml" style="display:inline;"><img src="'.$host_addr.'images/rssimage.png" style="width:20px;height:20px;"></a><br> 
            Posted on '.$outs['entrydate'].' in the category '.$outs3['catname'].'<br>
            Views: '.$outs['views'].'<br>
            LastModified: '.$outs['modifydate'].'<br>
            </div>
          </div>
          '.$descbanner.'
          '.$topaudio.'
        <div name="specialheader" style="font-family:ebrima;font-size:40px;clear:both;">'.$outs['title'].'</div><br>  
          <div class="blogfulldetails unselectable" unselectable="on">
            '.$blogdisplayoutput.'
          </div>
          <div id="formend">
        <div id="prevblogpointer">
          Previous Post:
          <a href="'.$prevlink.'"title="'.$prevblogout.'">'.$prevblogout.'</a>
        </div>
        <div id="nextblogpointer">
          Up Next:
          <a href="'.$nextlink.'" title="'.$nextblogout.'">'.$nextblogout.'</a>
        </div>
        </div>
          <div name="specialheader" style="font-family:ebrima;font-size:40px;clear:both;">Comments</div>
    <div id="commentsholder">
    '.$alerter.'
    '.$outs['viewercomment'].'
    </div>

          <div id="form" style="background-color:#fefefe;">
            <form action="'.$host_addr.'snippets/basicsignup.php" name="blogcommentform" method="post">
              <input type="hidden" name="entryvariant" value="createblogcomment"/>
              <input type="hidden" name="sectester" value="'.$securitynumber.'"/>
              <input type="hidden" name="blogentryid" value="'.$blogentryid.'"/>
              <div id="formheader">Add a Comment</div>
              * means the field is required.
              <div id="formend">
                <div id="elementholder">
                  Name *
                  <input type="text" name="name" Placeholder="Firstname Lastname" class="curved"/>
                </div>
                <div id="elementholder">
                  Email *
                  <input type="text" name="email" Placeholder="Your email here" class="curved"/>
                </div>
                <div id="elementholder">
                  Security('.$securitynumber.');
                  <input type="text" name="secnumber" Placeholder="The above digits here" class="curved"/>
                </div>
                <div id="formend">
                  Comment:
                  <textarea name="comment" id="postersmall" Placeholder="" class="curved3"></textarea>
                </div>
              </div>

              <div id="formend">
                <input type="button" name="createblogcomment" value="Submit" class="submitbutton"/>
                <br><!--By clicking the submit button, you are agreeing to:-->
              </div>
            </form>
          </div>
          <div name="specialheader" style="font-family:ebrima;font-size:40px;clear:both;">Previous Posts Under '.$outs3['catname'].'</div>
            <div id="postundercathold">
              <div name="postundercathold">
                '.$tinyoutput.'
              </div>
              <div id="formend" name="waitinghold"style="min-height:0px;"></div>
              <a href="'.$host_addr.'category.php?cid='.$blogcatid.'" name="morecatposts" data-catid="'.$blogtypeid.'">View More Posts</a>
            </div>
            '.$advertbottomcontent.'
        </div>
        <div id="adcontentholder"'.$adcontentholdstyle.'>
        '.$adcontentholdlongstyle.'
        '.$pagesidecontent.'
        '.$advertsidecontent.'
        </div>
      </div>
      <div id="contentbottom">
        <div id="quotehold">
          <font style="font-size:44px;font-style:;">Quote Of The Day</font><br>
          '.$quoteout.'
        </div>
        <div id="bottomgalleryhold">
          Gallery<br>
            <div id="gallerypreviewhold">
              <div id="galleryhold">
                <div id="galleryholder">
                  '.$outgallery['vieweroutput'].'
                </div>
              </div>  
            </div>
        </div>
        <div id="adspacehold">
          Contact<br>
          <!--<div id="bookingspace">
            Book Muyiwa Afolabi.
          </div>-->
          Telephone:<br>
          +2347063496599
          +2348107615145
          +2348079483193
        </div>
      </div>
    </div>
      <div id="footerpanel">
        <div id="footerpanelcontent">
          <div id="copyright">
      '.$footer.'
          </div>
        </div>
      </div>
      </div>
      '.$sociallinks.'
      <div id="fullbackground"></div>
      <div id="fullcontenthold">
        <div id="fullcontent">
          <div id="closecontainer" name="closefullcontenthold" data-id="theid" class="altcloseposfour"><img src="../../images/closefirst.png" title="Close"class="total"/></div>
          <img src="" name="galleryimgdisplay" title="gallerytitle" />
        </div>
        <div id="fullcontentheader">
          <input type="hidden" name="gallerycount" value="0"/>
          <input type="hidden" name="currentgalleryview" value="0"/>      
        </div>
        <div id="fullcontentdetails">
        </div>

        <div id="fullcontentpointerhold">
          <div id="fullcontentpointerholdholder">
            <div id="fullcontentpointerleft">
              <img src="../../images/pointerleft.png" name="pointleft" id="" data-pointer="" class="total"/>
            </div>
            <div id="fullcontentpointerright">
              <img src="../../images/pointerright.png" name="pointright" id="" data-pointer="" class="total"/>
            </div>
          </div>
        </div>
      </div>
  	 '.$ga.'
    </body>
    </html>
  ';
  }else if($outs['status']=="inactive"){
  $outputs['outputpageone']='
  <!DOCTYPE html>
  <html>
  <head>
  <title>Post Disabled</title>
  <meta http-equiv="Content-Language" content="en-us">
  <meta charset="utf-8"/>
  <meta http-equiv="Content-Type" content="text/html;"/>
  <meta property="fb:app_id" content="578838318855511"/>
  <meta property="fb:admins" content=""/>
  <meta property="og:locale" content="en_US">
  <meta property="og:type" content="website">
  <meta property="og:title" content="'.$outs['title'].'">
  <meta property="og:description" content="'.$headerdescription.'">
  <meta property="og:url" content="'.$outs['pagelink'].'">
  <meta property="og:site_name" content="Muyiwa Afolabi\'s Website">
  <meta property="og:image" content="'.$outs['absolutecover'].'">
  <meta name="keywords" content="'.$outs['title'].', Muyiwa Afolabi, muyiwa afolabi, frontiers consulting, frankly speaking with muyiwa afolabi, frankly speaking, motivational speaker in nigeria, business strategists in the world, reformation packages, Christ Society International Outreach, Project Fix Nigeria, Own Your Own, Nigerian career radio talk show"/>
  <meta name="description" content="'.$outs['title'].'">
  <link rel="stylesheet" href="../../stylesheets/font-awesome/css/font-awesome.css"/>
  <link rel="stylesheet" href="../../stylesheets/muyiwamain.css"/>
  <link rel="shortcut icon" type="image/png" href="../../images/muyiwaslogo.png"/>
  </head>
  <body>
  	<div id="main">
  	<div id="toppanel">
  		<div id="mainheaderdesigndisplayhold">
  			<div id="mainheaderdesigndisplay">
  			</div>
  		</div>
  		<div id="mainlogopanel">
  			<div id="mainimglogo" style="position:relative;">
  				'.$logocontrol.'
  			</div>
  			'.$subimgpos.'
  		</div>
  		<div id="linkspanel">
  			<ul>
  				'.$toplinks.'
  			</ul>
  		</div>
  	</div>

  <div id="contentpanel">
  	<div id="contentmiddle">
  		<div id="maincontenthold">
  		<div name="specialheader" style="font-family:ebrima;font-size:40px;clear:both;">Post Disabled</div><br>	
  			<div class="blogfulldetails">
  			We are sorry but this blog post for some reason or the other has been disabled.
  			</div>
  		</div>
  		<div id="adcontentholder">
  		'.$pagesidecontent.'
  		'.$advertsidecontent.'
  		</div>
  	</div>
  	
  </div>
  	<div id="footerpanel">
  		<div id="footerpanelcontent">
  			<div id="copyright">
  	'.$footer.'
  			</div>
  		</div>
  	</div>
  	</div>
  </body>
  </html>
  ';
  }
  // echo $outs['status'];
  return $outputs;
}


function getSingleQuote($quoteid){
  global $host_addr;
  $row=array();
  $query="SELECT * FROM qotd WHERE id=$quoteid";
  $run=mysql_query($query)or die(mysql_error()." Line 2509");
  $row=mysql_fetch_assoc($run);
  $id=$row['id'];
  $type=$row['type'];
  $typeout="Unknown";
  if($type=="general"){
    $typeout="General";
  }elseif ($type=="pfn") {
  	# code...
    $typeout="Project Fix Nigeria";
  }elseif ($type=="csi") {
    # code...
    $typeout="Christ Society International";
  }
  $quote=$row['quote'];
  $status=$row['status'];
  // check the database for media image
  $mediaquery="SELECT * FROM media WHERE ownerid=$quoteid AND ownertype='qotd' AND maintype='coverphoto' AND status='active' ORDER BY id DESC";
  $mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 2846");
  $coverdata=mysql_fetch_assoc($mediarun);
  $coverphoto=$coverdata['location'];
  $fid=$coverdata['id'];
  $bid=0;
  $medianumrows=mysql_num_rows($mediarun);
  if($medianumrows<1){
    $coverphoto="".$host_addr."images/csiimages/macsi.jpg";
    $fid=0;
    $bid=0;
  }else{

    $coverphoto=''.$host_addr.''.$coverphoto.'';
  }
  // $quotedperson=$row['quotedperson'];
  $row['quotedperson']==""?$quotedperson="Muyiwa Afolabi":$quotedperson=$row['quotedperson'];
  $row['adminoutput']='
    <tr data-id="'.$id.'">
    	<td class="tdimg"><img src="'.$coverphoto.'"/></td><td>'.$typeout.'</td><td>'.$quote.'</td><td>'.$quotedperson.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsingleqotd" data-divid="'.$id.'">Edit</a></td>
    </tr>
    <tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'">
    	<td colspan="100%">
    		<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
    			<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
    								
    			</div>
    		</div>
    	</td>
    </tr>
  ';
  $row['adminoutputtwo']='
  			<div id="form" style="background-color:#fefefe;">
  				<form action="../snippets/edit.php" name="editqotd" method="post" enctype="multipart/form-data">
  					<input type="hidden" name="entryvariant" value="editqotd"/>
            <input type="hidden" name="entryid" value="'.$id.'"/>
            <input type="hidden" name="coverid" value="'.$fid.'"/>
  					<input type="hidden" name="bannerid" value="'.$bid.'"/>
  					<div id="formheader">Edit '.$quotedperson.'\'s Quote</div>
  					<div id="formend">
  					Quotetype *<br>
  					<select name="quotetype" class="curved2">
  						<option value="">--Choose--</option>
  						<option value="general">General</option>
              <option value="pfn">Project Fix Nigeria</option>
              <option value="csi">CSI Outreach</option>
  					</select>
  					</div>
  					<div id="formend">
  							Quoted Person<br>
  							<input type="text" name="quotedperson" Placeholder="The name of the person you are quoting." class="curved"/>
  					</div>
  					<div id="formend">
  						Quote of the Day *<br>
  						<textarea name="quoteoftheday" id="" placeholder="Put the quote text here without any quotes please the quote will be added automatically when this entry is displayed" class="curved3">'.$quote.'</textarea>
  					</div>
            <div id="formend" data-name="image">
              Quoted Person Image(Leave this to use the default Muyiwa Afolabi image)<br>
              <input type="file" name="profpic" Placeholder="The name of the person you are quoting." class="curved"/>
            </div>
  					<div id="formend">
  							Change Status<br>
  							<select name="status" class="curved2">
  								<option value="">Change Status</option>
  								<option value="active">Active</option>
  								<option value="inactive">Inactive</option>
  							</select>
  						</div>
  					<div id="formend">
  						<input type="submit" name="updateblogentry" value="Update" class="submitbutton"/>
  					</div>
  				</form>
  			</div>
  ';
  $row['vieweroutput']='
  <font style="font-size:20px;">"</font><font style="font-size:15px;font-family: \'Khmer UI\';color:;">'.$quote.'</font><font style="font-size:20px;">"</font>
  <br>By - '.$quotedperson.'
  ';
  $row['vieweroutputtwo']='
  "<font style="font-size:;font-family: \'Khmer UI\';color:;">'.$quote.'</font>"
  <br>By - '.$quotedperson.'
  ';
  $row['vieweroutputthree']='
  "<font style="font-size:;font-family: \'Khmer UI\';color:#00AA8F;">'.$quote.'"
  <br>By - '.$quotedperson.'</font>
  ';
  // for csi
    // mini sidebar
    $row['vieweroutputfour']='
        <blockquote class="quote-content">
            <div class="quote-text">'.$quote.'</div>
            <!-- <span class="author">Jeremiah 29:11</span>-->
            <span class="quote-decor"><i class="fa fa-quote-right"></i></span>
        </blockquote>
        <div class="clearfix">
            <div class="photo">
                <img alt="Muyiwa Afolabi" src="'.$coverphoto.'">
            </div>
        </div>
    ';
    //for full width flexslider
    $row['vieweroutputfive']='
      <li class="item">
          <div class="table">
              <div class="text-center title">
                  <h1>"'.$quote.'"</h1>
                  <span>'.$quotedperson.'</span>
              </div>
          </div><!-- .table -->
          <img alt="" src="'.$coverphoto.'">
      </li><!-- .item -->
    ';

  

  $row['quotesingleout']=$quote;
  $row['quotee']=$quotedperson;

  return $row;
}

function getAllQuotes($viewer,$limit,$type){
  $row=array();
  $testit=strpos($limit,"-");
  $testit!==false?$limit="":$limit=$limit;
  $type=mysql_real_escape_string($type);
  if($limit==""&&$viewer=="admin"){
  $query="SELECT * FROM qotd WHERE type='$type' LIMIT 0,15";
  $rowmonitor['chiefquery']="SELECT * FROM qotd WHERE type='$type'";
  }elseif($limit!==""&&$viewer=="admin"){
  $query="SELECT * FROM qotd WHERE type='$type' $limit";
  $rowmonitor['chiefquery']="SELECT * FROM qotd WHERE type='$type'";
  }elseif($viewer=="viewer"){
  $query="SELECT * FROM qotd WHERE type='$type' AND status='active'";
  $rowmonitor['chiefquery']="SELECT * FROM qotd WHERE type='$type' AND status='active'";	
  }
  $run=mysql_query($query)or die(mysql_error()." Line 2517");
  $numrows=mysql_num_rows($run);
  $adminoutput="No entries";
  $adminoutputtwo="";
  $vieweroutput="Sorry, No Quotes yet";
  $vieweroutputtwo="Sorry, No Quotes yet";
  $vieweroutputthree="Sorry, No Quotes yet";
  $vieweroutputfour="<p>Sorry, No Quotes yet</p>";
  $vieweroutputfive="<p>Sorry, No Quotes yet</p>";
  $singleoutput="Sorry, No Quotes yet";
  $randoutput="Sorry, No Quotes yet";
  $singleoutput2="Sorry, No Quotes yet";
  $randoutput2="Sorry, No Quotes yet";
  $randoutput3="Sorry, No Quotes yet";
  $singleoutput3="Sorry, No Quotes yet";
  $randoutput4="Sorry, No Quotes yet";
  $singleoutput4="Sorry, No Quotes yet";
  $randoutput5="Sorry, No Quotes yet";
  $singleoutput5="Sorry, No Quotes yet";
  $viewcombinedoutputcsi='
    <li class="item">
        <div class="table">
            <div class="text-center title">
                <h1>"No quotes yet"</h1>
                <span>No quotes</span>
            </div>
        </div><!-- .table -->
    </li>
  ';
  $quote="";
  $quotee="";
  if($numrows>0){
  $adminoutput="";
  $adminoutputtwo="";
  $viewcombinedoutputcsi="";
  $vieweroutputmaintwo=array();
  $vieweroutputmainthree=array();
  $vieweroutputmainfour=array();
  $vieweroutputmainfive=array();
  $vieweroutputmain=array();
  $quoteoutputmain=array();
  $quoteeoutputmain=array();
  while($row=mysql_fetch_assoc($run)){
  	$outs=getSingleQuote($row['id']);
    $adminoutput.=$outs['adminoutput'];
    $adminoutputtwo.=$outs['adminoutputtwo'];
    $vieweroutputmain[].=$outs['vieweroutput'];
    $vieweroutputmaintwo[].=$outs['vieweroutputtwo'];
    $vieweroutputmainthree[].=$outs['vieweroutputthree'];
    // for csi
    $vieweroutputmainfour[].=$outs['vieweroutputfour'];
    $vieweroutputmainfive[].=$outs['vieweroutputfive'];
    // for csi slider
    $viewcombinedoutputcsi.=$outs['vieweroutputfive'];
    // end csi
    $quoteoutputmain[].=$outs['quotesingleout'];
    $quoteeoutputmain[].=$outs['quotee'];
  }
  $totentry=count($vieweroutputmain);
  $random=rand(0,$totentry-1);
  $singlechoose=$totentry-1;
  $singleoutput=$vieweroutputmain[$singlechoose];
  $randoutput=$vieweroutputmain[$random];
  $totentry=count($vieweroutputmaintwo);
  $singleoutput2=$vieweroutputmaintwo[$singlechoose];
  $randoutput2=$vieweroutputmaintwo[$random];
  $singleoutput3=$vieweroutputmainthree[$singlechoose];
  $randoutput3=$vieweroutputmainthree[$random];
  // for csi
  $singleoutput4=$vieweroutputmainfour[$singlechoose];
  $randoutput4=$vieweroutputmainfour[$random];
  $singleoutput5=$vieweroutputmainfive[$singlechoose];
  $randoutput5=$vieweroutputmainfive[$random];
  // end csi
  $quote=$quoteoutputmain[$random];
  $quotee=$quoteeoutputmain[$random];

  }
  $top='<table id="resultcontenttable" cellspacing="0">
  			<thead><tr><th>Photo</th><th>Type</th><th>Quote</th><th>QuotedPerson</th><th>status</th><th>View/Edit</th></tr></thead>
  			<tbody>';
  $bottom='	</tbody>
  		</table>';
  	$row['chiefquery']=$rowmonitor['chiefquery'];
  $outs=paginatejavascript($rowmonitor['chiefquery']);
  $outsviewer=paginate($rowmonitor['chiefquery']);
  $paginatetop='
  <div id="paginationhold">
  	<div class="meneame">
  		<input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
  		<input type="hidden" name="outputtype" value="qotd'.$type.'"/>
  		<input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
  		<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
  		<div class="pagination">
  			  '.$outs['usercontrols'].'
  		</div>
  	</div>
  </div>
  <div id="paginateddatahold" data-name="contentholder">';

  $paginatebottom='
  </div><div id="paginationhold">
  	<div class="meneame">
  		<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
  	</div>
  </div>';
  $row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
  $row['adminoutputtwo']=$top.$adminoutput.$bottom;
  $row['singleoutput']=$singleoutput;
  $row['randomoutput']=$randoutput;
  $row['singleoutput']=$singleoutput;
  $row['randomoutput']=$randoutput;
  $row['singleoutputtwo']=$singleoutput2;
  $row['randomoutputtwo']=$randoutput2;
  $row['singleoutputthree']=$singleoutput3;
  $row['randomoutputthree']=$randoutput3;
  // for csi
  $row['singleoutputfour']=$singleoutput4;
  $row['randomoutputfour']=$randoutput4;
  $row['singleoutputfive']=$singleoutput5;
  $row['randomoutputfive']=$randoutput5;
  $row['viewcombinedoutputcsi']=$viewcombinedoutputcsi;
  // end csi
  $row['quote']=$quote;
  $row['quotee']=$quotee;
  return $row;
}

function getSingleEvent($eventid){
  $row=array();
  $query="SELECT * FROM events WHERE id=$eventid";
  $run=mysql_query($query)or die(mysql_error()." Line 2627");
  $row=mysql_fetch_assoc($run);
  $id=$row['id'];
  $type=$row['type'];
  $type=="fc"?$typeout="Frontiers Consulting":($type=="pfn"?$typeout="Project Fix Nigeria":($type=="csi"?$typeout="Christ Society International Outreach":($type=="fs"?$typeout="Frankly Speaking With Muyiwa Afolabi":($type=="fr"?$typeout="Frontiers Radio":($typeout="")))));
  $eventtitle=$row['eventtitle'];
  $eventdetails=$row['eventdetails'];
  $date=$row['dateperiod'];
  $status=$row['status'];
  $currentday=date('d');
  $currentmonth=date('m');
  $currentyear=date('Y');
  $outs=calenderOut($currentday,$currentmonth,$currentyear,'admin','dateholder','','');
  $row['adminoutput']='
    <tr data-id="'.$id.'">
    	<td>'.$typeout.'</td><td>'.$date.'</td><td>'.$eventtitle.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsingleevent" data-divid="'.$id.'">Edit</a></td>
    </tr>
    <tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'">
    	<td colspan="100%">
    		<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
    			<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
    								
    			</div>
    		</div>
    	</td>
    </tr>
  ';
  $row['adminoutputtwo']='
    <div id="form" style="background-color:#fefefe;">
    	<form action="../snippets/edit.php" name="editevent" method="post" enctype="multipart/form-data">
    		<input type="hidden" name="entryvariant" value="editevent"/>
    		<input type="hidden" name="entryid" value="'.$id.'"/>
    		<div id="formheader">Edit '.$eventtitle.'</div>
    		<div id="formend">
    				Event Type<br>
    				<select name="eventtype" class="curved2">
    					<option value="">--Choose--</option>
    					<option value="fc">Frontiers Consulting</option>
    					<option value="pfn">Project Fix Nigeria</option>
    					<option value="csi">Christ Society International Outreach</option>
    					<option value="fs">Frankly Speaking With Muyiwa Afolabi.</option>
    				</select>
    		</div>
    		<div id="formend">
    			Date<br>
    			<input type="text" name="dateholder" readonly="true" placeholder="Click Calender below to choose Date" value=""class="curved"/>
    			<br>
    			'.$outs['formoutput'].'
    			</div>
    		<div id="formend">
    				Event Title<br>
    				<input type="text" name="eventtitle" Placeholder="The event title here." class="curved"/>
    		</div>
    		<div id="formend">
    			Event details<br>
    			<textarea name="eventdetails" id="" placeholder="Place all details of the event here, including more information such as its duration" class="curved3" style="width:447px;height:206px;">'.$eventdetails.'</textarea>
    		</div>
    		<div id="formend">
    				Change Status<br>
    				<select name="status" class="curved2">
    					<option value="">Change Status</option>
    					<option value="active">Active</option>
    					<option value="inactive">Inactive</option>
    				</select>
    			</div>
    		<div id="formend">
    			<input type="submit" name="updateevent" value="Update" class="submitbutton"/>
    		</div>
    	</form>
    </div>
  ';
  $row['vieweroutput']='
    <div id="eventhold">
    	<div id="eventtitle">'.$eventtitle.'</div>
    	<div id="eventdetails">'.$eventdetails.'</div>
    </div>
  ';
  $row['radiooutput']='

  ';
  return $row;
}

function getAllEvents($viewer,$limit,$type,$monthmain,$year){
  $row=array();
  $testit=strpos($limit,"-");
  $testit!==false?$limit="":$limit=$limit;
  if(is_array($monthmain)){
  $date=$monthmain[0];
  $month=$monthmain[1];
  $datequery="eventdate='$year-$month-$date' AND";
  $eventdata="";
  }else{
  	$month=$monthmain;
  	$datequery="";
    $eventdata="";
  }
  $type=mysql_real_escape_string($type);
  if($limit==""&&$viewer=="admin"&&$month!==""&&$year!==""){
  $query="SELECT * FROM events WHERE type='$type' AND '.$datequery.' m=$month AND y=$year ORDER BY eventdate,id DESC LIMIT $limit";
  $rowmonitor['chiefquery']="SELECT * FROM events WHERE type='$type' AND '.$datequery.' m=$month AND y=$year ORDER BY eventdate,id DESC";
  }elseif($limit!==""&&$viewer=="admin"&&$month!==""&&$year!==""){
  $query="SELECT * FROM events WHERE type='$type' AND '.$datequery.' m=$month AND y=$year $limit ORDER BY eventdate,id DESC";
  $rowmonitor['chiefquery']="SELECT * FROM events WHERE type='$type' AND '.$datequery.' m=$month AND y=$year ORDER BY eventdate,id DESC";
  }elseif($limit==""&&$viewer=="admin"&&$month==""&&$year==""){
  $query="SELECT * FROM events WHERE type='$type' ORDER BY eventdate,id DESC LIMIT 0,15";
  $rowmonitor['chiefquery']="SELECT * FROM events WHERE type='$type' ORDER BY eventdate,id DESC";
  }elseif($limit!==""&&$viewer=="admin"&&$month==""&&$year==""){
  $query="SELECT * FROM events WHERE type='$type' $limit";
  $rowmonitor['chiefquery']="SELECT * FROM events WHERE type='$type' ORDER BY eventdate,id DESC";
  }elseif($viewer=="viewer" AND $limit==""){
  $query="SELECT * FROM events WHERE type='$type' AND $datequery status='active' ORDER BY eventdate DESC";
  $rowmonitor['chiefquery']="SELECT * FROM events WHERE type='$type' AND m=$month AND y=$year AND status='active'";	
  }elseif($viewer=="viewer" AND $limit=="All"){
  if(isset($datequery)&&$datequery!==""){
    $datequery="d='".$date."' AND m='".$month."' AND y='".$year."' OR eventdate='$year-$month-$date'";
  }
  $query="SELECT * FROM events WHERE type='$type' AND status='active' ORDER BY id DESC";
  $rowmonitor['chiefquery']="SELECT * FROM events WHERE type='$type' AND m=$month AND y=$year AND status='active'";
  }
  $run=mysql_query($query)or die(mysql_error()." Line 2744");
  $numrows=mysql_num_rows($run);
  // echo $query;
  $adminoutput="<td colspan=\"100%\">No entries</td>";
  $adminoutputtwo="";
  $vieweroutput='<font color="#fefefe">Sorry, No Events have been posted for this date</font>';
  $vieweroutputtwo='<font color="#fefefe">Sorry, No Events have been posted for this date</font>';
  $singleoutput="";
  $validdates="";
  $validevents="";
  $validarr="";

  if($numrows>0){
  	// echo'inhere';
    $adminoutput="";
    $adminoutputtwo="";
    $vieweroutput="";
    $validevents="";
    $curdate="";
    $count=0;
    while($row=mysql_fetch_assoc($run)){
      $curdate==""||$curdate!==$row['dateperiod']?$curdate=$row['dateperiod']:$curdate=$curdate;
      $validdates==""?$validdates.=$row['dateperiod']:$validdates.=",".$row['dateperiod'];
      isset($validarr[''.$row['eventdate'].''])?$validarr[''.$row['eventdate'].''].="<|>".str_replace('"', "", $row['eventtitle']):$validarr[''.$row['eventdate'].'']=str_replace('"', "", $row['eventtitle']);
      $count==0?$pdate=$curdate:$pdate=$pdate;
      if($pdate==""||$curdate==$pdate){
        $validevents==""?$validevents.=str_replace('"', "'", $row['eventtitle']):$validevents.="<|>".str_replace('"', "", $row['eventtitle']);
      }else{
        $pdate=$curdate;
        $validevents.="<|>".str_replace('"', "", $row['eventtitle']).">|<";
      }
      $outs=getSingleEvent($row['id']);
      $adminoutput.=$outs['adminoutput'];
      $adminoutputtwo.=$outs['adminoutputtwo'];
      $vieweroutput.=$outs['vieweroutput'];
      $count++;
    }
      // echo $validarr['2015-09-22']."valid sample<br>";
  }
  $top='<table id="resultcontenttable" cellspacing="0">
        <thead><tr><th>Type</th><th>Date</th><th>Title</th><th>status</th><th>View/Edit</th></tr></thead>
        <tbody>';
  $bottom='	</tbody>
  		</table>';
  	$row['chiefquery']=$rowmonitor['chiefquery'];
  $outs=paginatejavascript($rowmonitor['chiefquery']);
  $outsviewer=paginate($rowmonitor['chiefquery']);
  $paginatetop='
  <div id="paginationhold">
  	<div class="meneame">
  		<input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
  		<input type="hidden" name="outputtype" value="events'.$type.'"/>
  		<input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
  		<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
  		<div class="pagination">
  			  '.$outs['usercontrols'].'
  		</div>
  	</div>
  </div>
  <div id="paginateddatahold" data-name="contentholder">';

  $paginatebottom='
  </div><div id="paginationhold">
  	<div class="meneame">
  		<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
  	</div>
  </div>';
  $row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
  $row['adminoutputtwo']=$top.$adminoutput.$bottom;
  $row['validdates']=$validdates;
  $row['validevents']=$validevents;
  $row['validarr']=$validarr;
  $row['vieweroutput']=$vieweroutput;

  return $row;
}
function getSingleGallery($galleryid){
	global $host_addr,$host_target_addr;
	$row=array();
	$query="SELECT * FROM gallery WHERE id=$galleryid";
	$run=mysql_query($query)or die(mysql_error()." Line 2627");
	$row=mysql_fetch_assoc($run);
	$id=$row['id'];
	$gallerytitle=$row['gallerytitle'];
	$gallerydetails=$row['gallerydetails'];
	$date=$row['entrydate'];
	$status=$row['status'];
	$outselect="";
	for($i=1;$i<=10;$i++){
		$pic="";
		$i>1?$pic="photos":$pic="photo";		
		$outselect.='<option value="'.$i.'">'.$i.''.$pic.'</option>';
	}
	//get complete gallery images and create thumbnail where necessary;
	$mediaquery="SELECT * FROM media WHERE ownerid=$galleryid AND ownertype='gallery' AND status='active' ORDER BY id DESC";
	$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 2846");
	$medianumrows=mysql_num_rows($mediarun);
	$album="No album photos yet.";
	$cover='<div id="bottomgalleryholders" title="'.$gallerytitle.'" data-mainimg="" data-images="" data-sizes="" data-details="'.$gallerydetails.'">
	 No Photos Yet.
	</div>';
	$thumbstack="";
	$locationstack="";
	$dimensionstack="";

		$mediacount=$medianumrows;
	if($medianumrows>0){
		$count=0;
		$album="";
	while ($mediarow=mysql_fetch_assoc($mediarun)) {
		# code...
		if($count==0){
			$coverphoto=$mediarow['details'];
			$maincoverphoto=$mediarow['location'];
		}
	$imgid=$mediarow['id'];
	$realimg=$mediarow['location'];
	$thumb=$mediarow['details'];
	$width=$mediarow['width'];
	$height=$mediarow['height'];
	$locationstack==""?$locationstack.=$host_addr.$realimg:$locationstack.=">".$host_addr.$realimg;
	$dimensionstack==""?$dimensionstack.=$width.",".$height:$dimensionstack.="|".$width.",".$height;
	$album.='
	<div id="editimgs" name="albumimg'.$imgid.'">
		<div id="editimgsoptions">
			<div id="editimgsoptionlinks">
				<a href="##" name="deletepic" data-id="'.$imgid.'"data-galleryid="'.$id.'"data-arraypoint="'.$count.'" data-src="'.$host_addr.''.$realimg.'"><img name="deletepic" src="../images/trashfirst.png" title="Delete this photo?" class="total"></a>
				<a href="##" name="viewpic" data-id="'.$imgid.'" data-galleryid="'.$id.'" data-arraypoint="'.$count.'" data-src="'.$host_addr.''.$realimg.'"><img name="viewpic" src="../images/viewpicfirst.png" title="View full image" class="total"></a>								
			</div>
		</div>	
		<img src=".'.$mediarow['details'].'" name="realimage" data-width="'.$width.'" data-height="'.$height.'" style="height:100%;margin:auto;">
	</div>
	';
	$count++;
	}
	$cover='
	<div id="bottomgalleryholders" title="'.$gallerytitle.'" data-mainimg="'.$host_addr.''.$maincoverphoto.'" data-images="'.$locationstack.'" data-sizes="'.$dimensionstack.'" data-details="'.$gallerydetails.'">
		<img src="'.$host_addr.''.$coverphoto.'" height="100%" class=""/>
	</div>';	
	}
	/*$album.='
	<div id="editimgsimgs" name="albumimg'.$imgid.'">
		<div id="editimgsoptions">
			<div id="editimgsoptionlinks">
				<a href="##" name="deletepic" data-id="'.$imgid.'"><img name="deletepic" src="../images/trashfirst.png" title="Delete this photo?" class="total"></a>
				<a href="##" name="viewpic" data-id="'.$imgid.'"><img name="viewpic" src="../images/viewpicfirst.png" title="View full image" class="total"></a>								
			</div>
		</div>	
		<img src=".'.$mediarow['details'].'" name="realimage" data-width="'.$width.'" data-height="'.$height.'" style="height:100%;margin:auto;">
	</div>
	';*/
	$row['adminoutput']='
	<tr data-id="'.$id.'">
		<td>'.$gallerytitle.'</td><td>'.$gallerydetails.'</td><td>'.$mediacount.'</td><td>'.$date.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsinglegallery" data-divid="'.$id.'">Edit</a></td>
	</tr>
	<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'">
		<td colspan="100%">
			<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
				<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
									
				</div>
			</div>
		</td>
	</tr>
	';
	$row['adminoutputtwo']='
	<div id="form" style="background-color:#fefefe;">
		<form action="../snippets/edit.php" name="editgallery" method="post" enctype="multipart/form-data">
			<input type="hidden" name="entryvariant" value="editgallery"/>
			<input type="hidden" name="entryid" value="'.$id.'"/>
			<div id="formheader">Edit '.$gallerytitle.'</div>
			<div id="formend">
					Gallery Title *<br>
					<input type="text" name="gallerytitle" Placeholder="The album title here." class="curved"/>
			</div>
			<div id="formend">
				 Gallery Details*<br>
				<textarea name="gallerydetails" id="" placeholder="Place all details of the album here." class="curved3" style="width:447px;height:206px;">'.$gallerydetails.'</textarea>
			</div>
			<div id="formend">
				Upload some more album photos to this Gallery:<br>
				<input type="hidden" name="piccount" value=""/>
				<select name="photocount" class="curved2" title="Choose the amount of photos you want to upload, max of 10, then click below the selection to continue">
				<option value="">--choose amount--</option>
					'.$outselect.'
				</select>							
			</div>
			<div id="formend" name="galleryfullholder'.$id.'">
			Gallery Images, click the target icon to view, click the trash icon to.....trash it, its that simple.<br>
			<input type="hidden" name="gallerydata'.$id.'" data-title="'.$gallerytitle.'" data-mainimg="'.$host_addr.''.$maincoverphoto.'" data-images="'.$locationstack.'" data-sizes="'.$dimensionstack.'" data-details="'.$gallerydetails.'"/>
				'.$album.'
			</div>
			<div id="formend">
					Change Status<br>
					<select name="status" class="curved2">
						<option value="">Change Status</option>
						<option value="active">Active</option>
						<option value="inactive">Inactive</option>
					</select>
				</div>
			<div id="formend">
				<input type="submit" name="updategallery" value="Update" class="submitbutton"/>
			</div>
		</form>
	</div>
	';
	$row['adminoutputthree']=$album;
	$row['vieweroutput']=$cover;
	return $row;
}
function getAllGalleries($viewer,$limit){
	$row=array();
	$testit=strpos($limit,"-");
	$testit===0||$testit===true||$testit>0?$limit="":$limit=$limit;
	if($limit==""&&$viewer=="admin"){
	$query="SELECT * FROM gallery ORDER BY id DESC LIMIT 0,15";
	$rowmonitor['chiefquery']="SELECT * FROM gallery ORDER BY id DESC";
	}elseif($limit!==""&&$viewer=="admin"){
	$query="SELECT * FROM gallery ORDER BY id DESC $limit";
	$rowmonitor['chiefquery']="SELECT * FROM gallery ORDER BY id DESC";
	}elseif($viewer=="viewer"){
	$query="SELECT * FROM gallery WHERE status='active' ORDER BY id DESC ";
	$rowmonitor['chiefquery']="SELECT * FROM gallery WHERE status='active'";	
	}
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line 2744");
	$numrows=mysql_num_rows($run);
	$adminoutput="<td colspan=\"100%\">No entries</td>";
	$adminoutputtwo="";
	$vieweroutput='<font color="#fefefe">Sorry, No Galleries have been created Yet</font>';
	$vieweroutputtwo='<font color="#fefefe">Sorry, No Galleries have been created Yet</font>';

	if($numrows>0){
	$adminoutput="";
	$adminoutputtwo="";
	$vieweroutput="";
	while($row=mysql_fetch_assoc($run)){
	$outs=getSingleGallery($row['id']);
	$adminoutput.=$outs['adminoutput'];
	$adminoutputtwo.=$outs['adminoutputtwo'];
	$vieweroutput.=$outs['vieweroutput'];
	}

	}
	$top='<table id="resultcontenttable" cellspacing="0">
				<thead><tr><th>Title</th><th>Details</th><th>Photos</th><th>Date</th><th>status</th><th>View/Edit</th></tr></thead>
				<tbody>';
	$bottom='	</tbody>
			</table>';
		$row['chiefquery']=$rowmonitor['chiefquery'];
	$outs=paginatejavascript($rowmonitor['chiefquery']);
	$outsviewer=paginate($rowmonitor['chiefquery']);
	$paginatetop='
	<div id="paginationhold">
		<div class="meneame">
			<input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
			<input type="hidden" name="outputtype" value="gallery"/>
			<input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
			<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
			<div class="pagination">
				 '.$outs['usercontrols'].'
			</div>
		</div>
	</div>
	<div id="paginateddatahold" data-name="contentholder">';

	$paginatebottom='
	</div><div id="paginationhold">
		<div class="meneame">
			<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
		</div>
	</div>';
	$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
	$row['adminoutputtwo']=$top.$adminoutput.$bottom;
	$row['vieweroutput']=$vieweroutput;

	return $row;
}

function getSingleTrendingTopic($topicid){
	global $host_addr,$host_target_addr;
	$row=array();
	$query="SELECT * FROM trendingtopics WHERE id=$topicid";
	$run=mysql_query($query)or die(mysql_error()." Line 3085");
	$row=mysql_fetch_assoc($run);
	$id=$row['id'];
	$details=$row['details'];
	$status=$row['status'];
	//get complete gallery images and create thumbnail where necessary;
	$mediaquery="SELECT * FROM media WHERE ownerid=$id AND ownertype='trendingtopic' AND maintype='coverphoto'AND status='active' ORDER BY id DESC";
	$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 3092");
	$medianumrows=mysql_num_rows($mediarun);
		$count=0;
		$cover="";
	$mediarow=mysql_fetch_assoc($mediarun);
		# code...

			$maincoverphoto=$mediarow['location'];

	$cover='<img src="'.$host_addr.''.$maincoverphoto.'"/>';
	$row['adminoutput']='
	<tr data-id="'.$id.'">
		<td>'.$cover.'</td><td>'.$details.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsingletrendingtopic" data-divid="'.$id.'">Edit</a></td>
	</tr>
	<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'">
		<td colspan="100%">
			<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
				<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
									
				</div>
			</div>
		</td>
	</tr>
	';
	$row['adminoutputtwo']='
	<div id="form" style="background-color:#fefefe;">
		<form action="../snippets/edit.php" name="edittrendingtopic" method="post" enctype="multipart/form-data">
			<input type="hidden" name="entryvariant" value="edittrendingtopic"/>
			<input type="hidden" name="entryid" value="'.$id.'"/>
			<div id="formheader">Edit '.$details.'</div>
			<div id="formend">
				Topic<br>
				<input type="text" placeholder="Enter Title" name="name" class="curved"/>
			</div>
			<div id="formend">
				Change Cover Photo<br>
				<input type="file" placeholder="Choose image" name="profpic" class="curved"/>
			</div>
			<div id="formend">
					Change Status<br>
					<select name="status" class="curved2">
						<option value="">Change Status</option>
						<option value="active">Active</option>
						<option value="inactive">Inactive</option>
					</select>
				</div>
			<div id="formend">
				<input type="submit" name="updatrendintopic" value="Update" class="submitbutton"/>
			</div>
		</form>
	</div>
	';
	$row['profpic']=$maincoverphoto;
	$row['vieweroutput']='
	<div id="trenddatahold">
				<div id="propdataimg2"><img src="'.$maincoverphoto.'" height="100%"/> </div>
				<span style="">'.$details.'</span><br>
			</div>
	';
	return $row;
} 
function getAllTrendingTopics($viewer,$limit){
	$row=array();
	$testit=strpos($limit,"-");
	$testit===0||$testit==0||$testit===true||$testit>0?$limit="":$limit=$limit;
	if($limit==""&&$viewer=="admin"){
	$query="SELECT * FROM trendingtopics ORDER BY id DESC LIMIT 0,15";
	$rowmonitor['chiefquery']="SELECT * FROM trendingtopics ORDER BY id DESC";
	}elseif($limit!==""&&$viewer=="admin"){
	$query="SELECT * FROM trendingtopics ORDER BY id DESC $limit";
	$rowmonitor['chiefquery']="SELECT * FROM trendingtopics ORDER BY id DESC";
	}elseif($viewer=="viewer"){
	$query="SELECT * FROM trendingtopics WHERE status='active' ORDER BY id DESC ";
	$rowmonitor['chiefquery']="SELECT * FROM trendingtopics WHERE status='active'"; 
	}
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line 3166");
	$numrows=mysql_num_rows($run);
	$adminoutput="<td colspan=\"100%\">No entries</td>";
	$adminoutputtwo="";
	$vieweroutput='<font color="#fefefe">Sorry, No Topics have been added Yet</font>';
	$vieweroutputtwo='<font color="#fefefe">Sorry, No Topics been added Yet</font>';
	if($numrows>0){
	$adminoutput="";
	$adminoutputtwo="";
	$vieweroutput="";
	while($row=mysql_fetch_assoc($run)){
	$outs=getSingleTrendingTopic($row['id']);
	$adminoutput.=$outs['adminoutput'];
	$adminoutputtwo.=$outs['adminoutputtwo'];
	$vieweroutput.=$outs['vieweroutput'];
	}

	}
	$top='<table id="resultcontenttable" cellspacing="0">
	 <thead><tr><th>Coverphoto</th><th>Details</th><th>status</th><th>View/Edit</th></tr></thead>
	 <tbody>';
	$bottom=' </tbody>
	 </table>';
	 $row['chiefquery']=$rowmonitor['chiefquery'];
	$outs=paginatejavascript($rowmonitor['chiefquery']);
	$outsviewer=paginate($rowmonitor['chiefquery']);
	$paginatetop='
	<div id="paginationhold">
	 <div class="meneame">
	 <input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
	 <input type="hidden" name="outputtype" value="trendingtopic"/>
	 <input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
	 <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
	 <div class="pagination">
	 '.$outs['usercontrols'].'
	 </div>
	 </div>
	</div>
	<div id="paginateddatahold" data-name="contentholder">';

	$paginatebottom='
	</div><div id="paginationhold">
	 <div class="meneame">
	 <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
	 </div>
	</div>';
	$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
	$row['adminoutputtwo']=$top.$adminoutput.$bottom;
	$row['vieweroutput']=$vieweroutput;

	return $row;
}
function getSinglePlaylist($playlistid){
	global $host_addr,$host_target_addr;
	$row=array();
	$query="SELECT * FROM toptenplaylist WHERE id=$playlistid";
	$run=mysql_query($query)or die(mysql_error()." Line 3085");
	$row=mysql_fetch_assoc($run);
	$id=$row['id'];
	$title=$row['title'];
	$artist=$row['artist'];
	$status=$row['status'];
	//get complete cover images;
	$mediaquery="SELECT * FROM media WHERE ownerid=$id AND ownertype='toptenplaylist' AND maintype='coverphoto'AND status='active' ORDER BY id DESC";
	$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 3092");
	$medianumrows=mysql_num_rows($mediarun);
	//get music file
	$mediaquery2="SELECT * FROM media WHERE ownerid=$id AND ownertype='toptenplaylist' AND maintype='musicfile'AND status='active' ORDER BY id DESC";
	$mediarun2=mysql_query($mediaquery2)or die(mysql_error()." Line 3092");
	$medianumrows2=mysql_num_rows($mediarun2);
	$count=0;
	$cover="";
	$mediarow=mysql_fetch_assoc($mediarun);
	$mediarow2=mysql_fetch_assoc($mediarun2);
	 # code...

	 $maincoverphoto=$mediarow['location'];
	 $mainaudio=$mediarow2['location'];
	$cover='<img src="'.$host_addr.''.$maincoverphoto.'"/>';
	$audioout='
	<audio src="'.$host_addr.''.$mainaudio.'" style="height:32px;"preload="none" controls>Download <a href="'.$host_addr.''.$mainaudio.'"></a></audio><br>
	';
	$row['adminoutput']='
	<tr data-id="'.$id.'">
	 <td>'.$cover.'</td><td>'.$title.'</td><td>'.$artist.'</td><td>'.$audioout.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsingletoptenplaylist" data-divid="'.$id.'">Edit</a></td>
	</tr>
	<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'">
	 <td colspan="100%">
	 <div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
	 <div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
	 
	 </div>
	 </div>
	 </td>
	</tr>
	';
	$row['adminoutputtwo']='
	<div id="form" style="background-color:#fefefe;">
	 <form action="../snippets/edit.php" name="edittoptenplaylist" method="post" enctype="multipart/form-data">
	 <input type="hidden" name="entryvariant" value="edittoptenplaylist"/>
	 <input type="hidden" name="entryid" value="'.$id.'"/>
	 <div id="formheader">Edit '.$title.'</div>
	 <div id="formend">
	 Title<br>
	 <input type="text" placeholder="Enter Title" name="title" class="curved"/>
	 </div>
	 <div id="formend">
	 Artist<br>
	 <input type="text" placeholder="Enter the name of the artist" name="artist" class="curved"/>
	 </div>
	 <div id="formend">
	 Album Photo<br>
	 <input type="file" placeholder="Choose image" name="profpic" class="curved"/>
	 </div>
	 <div id="formend">
	 Music File<br>
	 <input type="file" placeholder="Choose a mp3 file" name="music" class="curved"/>
	 </div>
	 <div id="formend">
	 Change Status<br>
	 <select name="status" class="curved2">
	 <option value="">Change Status</option>
	 <option value="active">Active</option>
	 <option value="inactive">Inactive</option>
	 </select>
	 </div>
	 <div id="formend">
	 <input type="submit" name="updatetoptenplaylist" value="Update" class="submitbutton"/>
	 </div>
	 </form>
	</div>
	';
	$row['profpic']=$maincoverphoto;
	$row['audiofilepath']=$mainaudio;
	$row['vieweroutput']='
	<div id="playlistholder">
	 <img src="'.$maincoverphoto.'"/> 
	 '.$title.'
	 <br>Artist: '.$artist.'
	 <audio src="'.$mainaudio.'" style="height:32px;"preload="none" controls>Download <a href="'.$mainaudio.'"></a></audio><br>
	</div>
	';
	return $row;
}
function getAllPlaylist($viewer,$limit){
	$row=array();
	$testit=strpos($limit,"-");
	$testit!==false?$limit="":$limit=$limit;
	$limit==""?$limit="LIMIT 0,15":$limit=$limit;
	if($limit==""&&$viewer=="admin"){
	$query="SELECT * FROM toptenplaylist ORDER BY id DESC LIMIT 0,15";
	$rowmonitor['chiefquery']="SELECT * FROM toptenplaylist ORDER BY id DESC";
	}elseif($limit!==""&&$viewer=="admin"){
	$query="SELECT * FROM toptenplaylist ORDER BY id DESC $limit";
	$rowmonitor['chiefquery']="SELECT * FROM toptenplaylist ORDER BY id DESC";
	}elseif($viewer=="viewer"){
	$query="SELECT * FROM toptenplaylist WHERE status='active' ORDER BY id DESC LIMIT 0,10";
	$rowmonitor['chiefquery']="SELECT * FROM toptenplaylist WHERE status='active'"; 
	}
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line 3166");
	$numrows=mysql_num_rows($run);
	$adminoutput="<td colspan=\"100%\">No entries</td>";
	$adminoutputtwo="";
	$vieweroutput='<font color="#fefefe">Sorry, No Songs have been added Yet</font>';
	$vieweroutputtwo='<font color="#fefefe">Sorry, No Songs been added Yet</font>';
	if($numrows>0){
	$adminoutput="";
	$adminoutputtwo="";
	$vieweroutput="";
	while($row=mysql_fetch_assoc($run)){
	$outs=getSinglePlaylist($row['id']);
	$adminoutput.=$outs['adminoutput'];
	$adminoutputtwo.=$outs['adminoutputtwo'];
	$vieweroutput.=$outs['vieweroutput'];
	}

	}
	$top='<table id="resultcontenttable" cellspacing="0">
	 <thead><tr><th>Album Art</th><th>Title</th><th>Artist</th><th>Audio</th><th>status</th><th>View/Edit</th></tr></thead>
	 <tbody>';
	$bottom=' </tbody>
	 </table>';
	 $row['chiefquery']=$rowmonitor['chiefquery'];
	$outs=paginatejavascript($rowmonitor['chiefquery']);
	$outsviewer=paginate($rowmonitor['chiefquery']);
	$paginatetop='
	<div id="paginationhold">
	 <div class="meneame">
	 <input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
	 <input type="hidden" name="outputtype" value="playlist"/>
	 <input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
	 <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
	 <div class="pagination">
	 '.$outs['usercontrols'].'
	 </div>
	 </div>
	</div>
	<div id="paginateddatahold" data-name="contentholder">';

	$paginatebottom='
	</div><div id="paginationhold">
	 <div class="meneame">
	 <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
	 </div>
	</div>';
	$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
	$row['adminoutputtwo']=$top.$adminoutput.$bottom;
	$row['vieweroutput']=$vieweroutput;

	return $row;
}
function getSingleTopAudio($playlistid){
	global $host_addr,$host_target_addr;
	$row=array();
	$query="SELECT * FROM topaudio WHERE id=$playlistid";
	$run=mysql_query($query)or die(mysql_error()." Line 3085");
	$row=mysql_fetch_assoc($run);
	$date= date("Y-m-d");
	$id=$row['id'];
	$title=$row['title'];
	$entrydate=$row['entrydate'];
	$status=$row['status'];

	//get music file
	$mediaquery2="SELECT * FROM media WHERE ownerid=$id AND ownertype='topaudio' AND maintype='musicfile'AND status='active' ORDER BY id DESC";
	$mediarun2=mysql_query($mediaquery2)or die(mysql_error()." Line 3092");
	$medianumrows2=mysql_num_rows($mediarun2);
	$count=0;
	$cover="";
	$mediarow2=mysql_fetch_assoc($mediarun2);
	  # code...
	    $mainaudio=$mediarow2['location'];

	$audioout='
	<audio src="'.$host_addr.''.$mainaudio.'" style="height:32px;"preload="none" controls>Download <a href="'.$host_addr.''.$mainaudio.'"></a></audio><br>
	';
	$row['adminoutput']='
	<tr data-id="'.$id.'">
	  <td>'.$title.'</td><td>'.$audioout.'</td><td>'.$entrydate.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsingletopaudio" data-divid="'.$id.'">Edit</a></td>
	</tr>
	<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'">
	  <td colspan="100%">
	    <div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
	      <div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
	                        
	      </div>
	    </div>
	  </td>
	</tr>
	';
	$row['adminoutputtwo']='
	<div id="form" style="background-color:#fefefe;">
	  <form action="../snippets/edit.php" name="edittopaudio" method="post" enctype="multipart/form-data">
	    <input type="hidden" name="entryvariant" value="edittopaudio"/>
	    <input type="hidden" name="entryid" value="'.$id.'"/>
	    <div id="formheader">Edit '.$title.'</div>
	    <div id="formend">
	      Title<br>
	      <input type="text" placeholder="Enter Title" name="title" value="'.$title.'" class="curved"/>
	    </div>
	    <div id="formend">
	      Audio File<br>
	      <input type="file" placeholder="Choose a mp3 file" name="music" class="curved"/>
	    </div>
	    <div id="formend">
	        Change Status<br>
	        <select name="status" class="curved2">
	          <option value="">Change Status</option>
	          <option value="active">Active</option>
	          <option value="inactive">Inactive</option>
	        </select>
	      </div>
	    <div id="formend">
	      <input type="submit" name="updatetopaudio" value="Update" class="submitbutton"/>
	    </div>
	  </form>
	</div>
	';
	$row['audiofilepath']=$mainaudio;
	$row['vieweroutput']='
	    <script>
	          $(document).ready(function(){

	            $("#jquery_jplayer_1").jPlayer({  
	              ready: function () {  
	                $(this).jPlayer("setMedia", {    
	                  oga: ""+host_addr+"'.str_replace(" ", "%20",$mainaudio).'"  
	                });  
	              },  
	              swfPath: "./scripts/js",  
	              supplied: "oga"  
	            });
	          })
	          </script>
	      <div id="franklyaudiopanel">
	        <div id="jquery_jplayer_1" class="jp-jplayer"></div>  
	        <div id="jp_container_1" class="jp-audio">  
	          <div class="jp-type-single">    
	              <div class="jp-gui jp-interface">  
	                <ul class="jp-controls">  
	                  <!-- <li><a href="javascript:;" class="jp-repeat" tabindex="1" title="Refresh stream"><i class="fa fa-refresh" style="position: relative;top: 8px;font-size: 0.7em;"></i></a></li>  
	                  <li style="margin-left:0%;"><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="Refresh stream"><i class="fa fa-refresh" style="position: relative;top: 8px;font-size: 0.7em;"></i>
	                  <i class="fa fa-ban fa-stack-1x" style="position:absolute;top: 18px;font-size: 0.7em;"></i></a></li>   -->
	                  <li><a href="javascript:;" class="jp-play" tabindex="1" title="play radio" style="font-size: 4em;overflow: hidden;"><i class="fa fa-play-circle-o"></i></a></li>  
	                  <li style="margin-left:0%;"><a href="javascript:;" class="jp-pause" tabindex="1" style="position: relative;display: none;font-size: 3em;padding: 10px;margin-left:0%;">&#61516;</a></li>  
	                  <li style="margin-left:0%;height: 63px;"><a href="javascript:;" class="jp-stop" tabindex="1" title="" style="position: relative;display:block;top:10px;font-size: 2em;padding: 10px;margin-left:0%;"><i class="fa fa-stop"></i></a></li>  
	                  
	                  <li style="position: absolute;float: right;right: 9%;top: 19%;"><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">&#xf028;</a></li>  
	                  <li style="position: absolute;float: right;right: 12%;top: 19%;"><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">&#xf026;</a></li>  
	                  <!-- <li style="position: absolute;float: right;right: 9%;top: 19%;"><a href="javascript:;" class="jp-volume-max" tabindex="1" title="MaxVolume">&#61480;</a></li>   -->
	                </ul>  
	                 <div class="jp-title">  
	              <ul>  
	                <li><p class="radiotopic">'.$title.'</p></li>  
	              </ul>  
	            </div>
	                <div class="jp-progress">  
	                  <div class="jp-seek-bar">  
	                    <div class="jp-play-bar"></div>  
	                  </div>  
	                </div>  
	          
	                <div class="jp-time-holder">  
	                  <div class="jp-current-time"></div>  
	                </div>  
	          
	                <div class="jp-volume-bar">  
	                  <div class="jp-volume-bar-value"></div>  
	                </div>  
	          
	              <div class="jp-no-solution">  
	                <span>Update Required</span>  
	                To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.  
	              </div>  
	            </div>  
	          </div> 
	        </div> 
	      </div>
	';
	if($entrydate!==$date){
	$row['vieweroutput']='
	      <!--<div id="franklyaudiopanel">
	        Sorry there are no active audio entries for you to listen to today
	      </div>-->
	';
	}
	return $row;
}
function getAllTopAudio($viewer,$limit){
	$row=array();
	$testit=strpos($limit,"-");
	$testit!==false?$limit="":$limit=$limit;
	$limit==""?$limit="LIMIT 0,15":$limit=$limit;
	if($limit==""&&$viewer=="admin"){
	$query="SELECT * FROM topaudio ORDER BY id DESC LIMIT 0,15";
	$rowmonitor['chiefquery']="SELECT * FROM topaudio ORDER BY id DESC";
	}elseif($limit!==""&&$viewer=="admin"){
	$query="SELECT * FROM topaudio ORDER BY id DESC $limit";
	$rowmonitor['chiefquery']="SELECT * FROM topaudio ORDER BY id DESC";
	// echo $limit;
	}elseif($viewer=="viewer"){
	$query="SELECT * FROM topaudio WHERE status='active' ORDER BY id DESC LIMIT 0,1";
	$rowmonitor['chiefquery']="SELECT * FROM topaudio WHERE status='active'"; 
	}
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line 4315");
	$numrows=mysql_num_rows($run);
	$adminoutput="<td colspan=\"100%\">No entries</td>";
	$adminoutputtwo="";
	$vieweroutput='<div id="franklyaudiopanel"class="nodisplay"><font color="#fefefe">Sorry, No Audio has been created for today</font></div>';
	$vieweroutputtwo='<font color="#fefefe">Sorry, No Audio has been created for today</font>';
	if($numrows>0){
	$adminoutput="";
	$adminoutputtwo="";
	$vieweroutput="";
	while($row=mysql_fetch_assoc($run)){
	$outs=getSingleTopAudio($row['id']);
	$adminoutput.=$outs['adminoutput'];
	$adminoutputtwo.=$outs['adminoutputtwo'];
	$vieweroutput=$outs['vieweroutput']; 
	}

	}
	$top='<table id="resultcontenttable" cellspacing="0">
	      <thead><tr><th>Title</th><th>Audio</th><th>Date</th><th>status</th><th>View/Edit</th></tr></thead>
	      <tbody>';
	$bottom=' </tbody>
	    </table>';
	  $row['chiefquery']=$rowmonitor['chiefquery'];
	$outs=paginatejavascript($rowmonitor['chiefquery']);
	$outsviewer=paginate($rowmonitor['chiefquery']);
	$paginatetop='
	<div id="paginationhold">
	  <div class="meneame">
	    <input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
	    <input type="hidden" name="outputtype" value="topaudio"/>
	    <input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
	    <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
	    <div class="pagination">
	        '.$outs['usercontrols'].'
	    </div>
	  </div>
	</div>
	<div id="paginateddatahold" data-name="contentholder">';

	$paginatebottom='
	</div><div id="paginationhold">
	  <div class="meneame">
	    <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
	  </div>
	</div>';
	$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
	$row['adminoutputtwo']=$top.$adminoutput.$bottom;
	$row['vieweroutput']=$vieweroutput;

	return $row;
}
function getSingleAdvert($advertid){
	global $host_addr,$host_target_addr;
	$row=array();
	$query="SELECT * FROM adverts WHERE id=$advertid";
	$run=mysql_query($query)or die(mysql_error()." Line 3085");
	$row=mysql_fetch_assoc($run);
	$id=$row['id'];
	$owner=$row['owner'];
	$landingpage=$row['landingpage'];
	$title=$row['title'];
	$type=$row['type'];
	$page=$row['activepage'];
	$clicks=$row['clicks'];
	$status=$row['status'];
	$row['adlink']='
	<a href="'.$landingpage.'" target="_blank" title="Click to view advert landing page.">'.$owner.'</a>
	';
	$linkout='
	<a href="'.$landingpage.'" style="max-width: 300px;word-break: break-all;float: left;" target="_blank" title="Click to view advert landing page.">Click to view page.</a>
	';
	$page=="fc"?$typeout="Frontiers Consulting":($page=="pfn"?$typeout="Project Fix Nigeria":($page=="csi"?$typeout="Christ Society International Outreach":($page=="fs"?$typeout="Frankly Speaking With Muyiwa Afolabi":($page=="all"?$typeout="All Blog Pages":$typeout=""))));
	//get complete cover images;
	$mediaquery="SELECT * FROM media WHERE ownerid=$id AND ownertype='advert' AND maintype='$type' AND status='active' ORDER BY id DESC";
	$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 3092");
	$medianumrows=mysql_num_rows($mediarun);

	$count=0;
	$cover="";
	$cover2="";
	$mediarow=mysql_fetch_assoc($mediarun);

	 # code...

	 $maincoverphoto=$mediarow['location'];
	 if($type=="banneradvert"||$type=="miniadvert"){
	$cover='<img src="'.$host_addr.''.$maincoverphoto.'" name="advert" data-id="'.$id.'"/>';
	if($type=="banneradvert"){
	$cover2='
	<div id="banneradverthold">
	 <a href="'.$landingpage.'" target="_blank">
	 '.$cover.'
	 </a>
	</div>
	';
	}elseif ($type=="miniadvert") {
	 # code...
	 $cover2='
	<div id="adcontentholdlong" name="jumiaadvert">
	 Advert
	 <a href="'.$landingpage.'" target="_blank">'.$cover.'</a>
	 </div>
	 ';
	}
	 }elseif ($type=="videoadvert") {
	 # code...
	$cover='
	video title="" id="example_video_1" class="video-js vjs-default-skin" controls preload="true" width="" height="150px" poster="" data-setup="{}">
	 <source src="'.$host_addr.''.$maincoverphoto.'"/>
	</video>
	';
	$cover2='
	<div id="adcontentholdshort" name="videoadvert">
	 '.$title.'<br>
	 '.$cover.'
	</div>
	';
	 }
	$row['adminoutput']='
	<tr data-id="'.$id.'">
	 <td>'.$cover.'</td><td>'.$type.'</td><td>'.$typeout.'</td><td>'.$owner.'</td><td>'.$title.'</td><td>'.$linkout.'</td><td>'.$clicks.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsingleadvert" data-divid="'.$id.'">Edit</a></td>
	</tr>
	<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'">
	 <td colspan="100%">
	 <div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
	 <div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
	 
	 </div>
	 </div>
	 </td>
	</tr>
	';
	$row['adminoutputtwo']='
	<div id="form" style="background-color:#fefefe;">
	 <form action="../snippets/edit.php" name="edit" method="post" enctype="multipart/form-data">
	 <input type="hidden" name="entryvariant" value="editadvert"/>
	 <input type="hidden" name="entryid" value="'.$id.'"/>
	 <div id="formheader">Edit '.$owner.'</div>
	 <div id="formend">
	 Advert Page *<br>
	 <select name="advertpage" class="curved2">
	 <option value="">--Choose--</option>
	 <!-- <option value="fc">Frontiers Consulting</option> -->
	 <option value="all">All Blog Pages</option>
	 <option value="pfn">Project Fix Nigeria Blog Page</option>
	 <option value="csi">Christ Society International Outreach Blog Page</option>
	 <option value="fs">Frankly Speaking With Muyiwa Afolabi Blog Page.</option>
	 </select>
	 </div>
	 <div id="formend">
	 Advert owner<br>
	 <input type="text" name="advertowner" Placeholder="'.$owner.'" class="curved"/>
	 </div>
	 <div id="formend">
	 Advert title (Make this short and descriptive)<br>
	 <input type="text" name="adverttitle" Placeholder="The advert title." class="curved"/>
	 </div>
	 <div id="formend">
	 Advert Landing Page<br>
	 <input type="text" name="advertlandingpage" Placeholder="The landing page." class="curved"/>
	 </div>
	 <div id="formend">
	 Advert Image<!--/ File(Video file less than 15MB please in mp4 format) --> <br>
	 <input type="file" placeholder="Choose image" name="profpic" class="curved"/>
	 </div>
	 <div id="formend">
	 Change Status<br>
	 <select name="status" class="curved2">
	 <option value="">Change Status</option>
	 <option value="active">Active</option>
	 <option value="inactive">Inactive</option>
	 </select>
	 </div>
	 <div id="formend">
	 <input type="submit" name="updateadvert" value="Update" class="submitbutton"/>
	 </div>
	 </form>
	</div>
	';
	$row['file']=$cover;
	$row['filepath']=$host_addr.$maincoverphoto;
	$row['vieweroutput']=$cover2;

	return $row;
}
function getAllAdverts($viewer,$limit,$type,$page){
	$row=array();
	$testit=strpos($limit,"-");
	$testit===0||$testit==0||$testit===true||$testit>0?$limit="":$limit=$limit;
	$joiner="";
	if($type!=="" AND $page==""){
	$joiner='AND type=\''.$type.'\'';
	$joiner2='WHERE type=\''.$type.'\'';
	}elseif($type=="" AND $page!==""){
	$joiner='AND activepage=\''.$page.'\'';
	$joiner2='WHERE activepage=\''.$page.'\'';
	}elseif($type!=="" AND $page!==""){
	$joiner='AND type=\''.$type.'\' AND activepage=\''.$page.'\'';
	$joiner2='WHERE type=\''.$type.'\' AND activepage=\''.$page.'\'';
	}
	if($limit==""&&$viewer=="admin"){
	$query="SELECT * FROM adverts $joiner2 ORDER BY id DESC LIMIT 0,15";
	$rowmonitor['chiefquery']="SELECT * FROM adverts $joiner2 ORDER BY id DESC";
	}elseif($limit!==""&&$viewer=="admin"){
	$query="SELECT * FROM adverts $joiner2 ORDER BY id DESC $limit";
	$rowmonitor['chiefquery']="SELECT * FROM adverts $joiner2 ORDER BY id DESC";
	}elseif($viewer=="viewer"){
	$query="SELECT * FROM adverts WHERE status='active' $joiner ORDER BY id DESC LIMIT 0,10";
	$rowmonitor['chiefquery']="SELECT * FROM adverts WHERE status='active' $joiner"; 
	}
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line 3166");
	$numrows=mysql_num_rows($run);
	$adminoutput="<td colspan=\"100%\">No entries</td>";
	$adminoutputtwo="";
	$vieweroutput='<font color="#fefefe">This space is free for your adverts, contact us for more information</font>';
	$vieweroutputtwo='<font color="#fefefe">This space is free for your adverts, contact us for more information</font>';
	if($numrows>0){
	$adminoutput="";
	$adminoutputtwo="";
	$vieweroutput="";
	while($row=mysql_fetch_assoc($run)){
	$outs=getSingleAdvert($row['id']);
	$adminoutput.=$outs['adminoutput'];
	$adminoutputtwo.=$outs['adminoutputtwo'];
	$vieweroutput.=$outs['vieweroutput'];
	}

	}
	$top='<table id="resultcontenttable" cellspacing="0">
	 <thead><tr><th>Content</th><th>Type</th><th>Blog Page</th><th>Owner</th><th>Title</th><th>LandingPage</th><th>Clicks</th><th>status</th><th>View/Edit</th></tr></thead>
	 <tbody>';
	$bottom=' </tbody>
	 </table>';
	 $row['chiefquery']=$rowmonitor['chiefquery'];
	$outs=paginatejavascript($rowmonitor['chiefquery']);
	$outsviewer=paginate($rowmonitor['chiefquery']);
	$paginatetop='
	<div id="paginationhold">
	 <div class="meneame">
	 <input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
	 <input type="hidden" name="outputtype" value="advert|'.$type.'|'.$page.'"/>
	 <input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
	 <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
	 <div class="pagination">
	 '.$outs['usercontrols'].'
	 </div>
	 </div>
	</div>
	<div id="paginateddatahold" data-name="contentholder">';

	$paginatebottom='
	</div><div id="paginationhold">
	 <div class="meneame">
	 <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
	 </div>
	</div>';
	$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
	$row['adminoutputtwo']=$top.$adminoutput.$bottom;
	$row['vieweroutput']=$vieweroutput;

	return $row;
	}

	function getSingleTestimony($testimonyid){
	global $host_addr,$host_target_addr;
	$row=array();
	$query="SELECT * FROM testimony WHERE id=$testimonyid";
	$run=mysql_query($query)or die(mysql_error()." Line 3085");
	$row=mysql_fetch_assoc($run);
	$id=$row['id'];
	$fullname=$row['fullname'];
	$email=$row['email'];
	$testimony=$row['testimony'];
	$displaytitle=$row['displaytitle'];
	if($displaytitle==""){
	$displaytitle="No title yet, set a title";
	}
	$date=$row['entrydate'];
	$monitorlength=strlen($testimony);
	$testimonyout=$testimony;
	if($monitorlength>140){
	$testimonyout=substr($testimonyout,0,137);
	$testimonyout=$testimonyout."...";
	}
	$status=$row['status'];
	$row['adminoutput']='
	<tr data-id="'.$id.'">
	 <td>'.$fullname.'</td><td>'.$email.'</td><td>'.$testimonyout.'</td><td>'.$displaytitle.'</td><td>'.$date.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsingletestimony" data-divid="'.$id.'">Edit</a></td>
	</tr>
	<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'">
	 <td colspan="100%">
	 <div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
	 <div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
	 
	 </div>
	 </div>
	 </td>
	</tr>
	';
	$row['adminoutputtwo']='
	<div id="form" style="background-color:#fefefe;">
	 <form action="../snippets/edit.php" name="edittestimony" method="post" enctype="multipart/form-data">
	 <input type="hidden" name="entryvariant" value="edittestimony"/>
	 <input type="hidden" name="entryid" value="'.$id.'"/>
	 <div id="formheader">Edit '.$displaytitle.'</div>
	 <div id="formend">
	 The display title for the testimony(if this is a new testimony i.e "inactive" make sure you set the status to active after giving a display title to it so people that visit the csi page can see it). 
	 <br><input type="text" name="displaytitle" Placeholder="Display title here" class="curved"/>
	 </div>
	 <div id="formend">
	 Testimony <br>
	 <textarea name="testimony" id="" Placeholder="" class="curved3">'.$testimony.'</textarea>
	 </div>
	 <div id="formend">
	 Change Status<br>
	 <select name="status" class="curved2">
	 <option value="">Change Status</option>
	 <option value="active">Active</option>
	 <option value="inactive">Inactive</option>
	 </select>
	 </div>
	 <div id="formend">
	 <input type="submit" name="updatetestimony" value="Update" class="submitbutton"/>
	 </div>
	 </form>
	</div>
	';
	$row['vieweroutput']='
	<div id="miniblogposthold">
	 <a href="##'.$displaytitle.'" title="'.$testimonyout.'" name="viewtestimony" data-id="'.$id.'">
	 <img src="./images/rejoicement.jpg"/>
	 '.$displaytitle.'.</a>
	</div>
	';
	$row['vieweroutputtwo']='
	<div id="closecontainer" name="closefullcontenthold" data-id="theid" class="altcloseposfour"><img src="./images/closefirst.png" title="Close"class="total"/></div>
	<div id="eventhold">
	 <div id="eventtitle">'.$displaytitle.'</div>
	 <div id="eventdetails">'.$fullname.' on '.$date.':<br>'.$testimony.'</div>
	</div>
	';
	return $row;
}
function getAllTestimonies($viewer,$limit,$type){
	$row=array();
	$testit=strpos($limit,"-");
	$testit===0||$testit==0||$testit===true||$testit>0?$limit="":$limit=$limit;
	if($type=="all" && $limit==""){
	$query="SELECT * FROM testimony ORDER BY id DESC LIMIT 0,15";
	$rowmonitor['chiefquery']="SELECT * FROM testimony ORDER BY id DESC";
	}elseif($type=="all" && $limit!==""){
	$query="SELECT * FROM testimony ORDER BY id DESC $limit";
	$rowmonitor['chiefquery']="SELECT * FROM testimony ORDER BY id DESC";
	}else if($limit==""&&$viewer=="admin"){
	$query="SELECT * FROM testimony WHERE status='$type' ORDER BY id DESC LIMIT 0,15";
	$rowmonitor['chiefquery']="SELECT * FROM testimony WHERE status='$type' ORDER BY id DESC";
	}elseif($limit!==""&&$viewer=="admin"){
	$query="SELECT * FROM testimony WHERE status='$type' ORDER BY id DESC $limit";
	$rowmonitor['chiefquery']="SELECT * FROM testimony WHERE status='$type' ORDER BY id DESC";
	}elseif($viewer=="viewer"){
	$query="SELECT * FROM testimony WHERE status='active' ORDER BY id DESC LIMIT 0,10";
	$rowmonitor['chiefquery']="SELECT * FROM testimony WHERE status='active'"; 
	}
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line 3894");
	$numrows=mysql_num_rows($run);
	$adminoutput="<td colspan=\"100%\">No entries</td>";
	$adminoutputtwo="";
	$vieweroutput='<font color="#fefefe">No testimonies have been shared yet, share yours today</font>';
	$vieweroutputtwo='<font color="#fefefe">No testimonies have been shared yet, share yours today</font>';
	if($numrows>0){
	$adminoutput="";
	$adminoutputtwo="";
	$vieweroutput="";
	$vieweroutputtwo="";

	while($row=mysql_fetch_assoc($run)){
	$outs=getSingleTestimony($row['id']);
	$adminoutput.=$outs['adminoutput'];
	$adminoutputtwo.=$outs['adminoutputtwo'];
	$vieweroutput.=$outs['vieweroutput'];
	$vieweroutputtwo.=$outs['vieweroutputtwo'];
	}

	}
	$top='<table id="resultcontenttable" cellspacing="0">
	 <thead><tr><th>Fullname</th><th>Email</th><th>Testimony</th><th>DisplayTitle</th><th>Date</th><th>status</th><th>View/Edit</th></tr></thead>
	 <tbody>';
	$bottom=' </tbody>
	 </table>';
	 $row['chiefquery']=$rowmonitor['chiefquery'];
	$outs=paginatejavascript($rowmonitor['chiefquery']);
	$outsviewer=paginate($rowmonitor['chiefquery']);
	$paginatetop='
	<div id="paginationhold">
	 <div class="meneame">
	 <input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
	 <input type="hidden" name="outputtype" value="testimony|'.$type.'"/>
	 <input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
	 <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
	 <div class="pagination">
	 '.$outs['usercontrols'].'
	 </div>
	 </div>
	</div>
	<div id="paginateddatahold" data-name="contentholder">';

	$paginatebottom='
	</div><div id="paginationhold">
	 <div class="meneame">
	 <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
	 </div>
	</div>';
	$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
	$row['adminoutputtwo']=$top.$adminoutput.$bottom;
	$row['vieweroutput']=$vieweroutput;
	$row['vieweroutputtwo']=$vieweroutputtwo;

	return $row;
}

function getSingleServiceRequest($serviceid){
	global $host_addr,$host_target_addr;
	$row=array();
	$query="SELECT * FROM servicerequest WHERE id=$serviceid";
	$run=mysql_query($query)or die(mysql_error()." Line 4033");
	$row=mysql_fetch_assoc($run);
	$id=$row['id'];
	$fullname=$row['name'];
	$orgname=$row['organizationname'];
	$team=$row['team'];
	$eventtype=$row['eventtype'];
	$startdate=$row['startdateperiod'];
	$enddate=$row['enddateperiod'];
	$participants=$row['expectedattendance'];
	$phoneone=$row['phoneone'];
	$phonetwo=$row['phonetwo'];
	$venue=$row['venue'];
	$extrainfo=$row['extrainfo'];
	$date=$row['datetime'];

	/*$monitorlength=strlen($testimony);
	$testimonyout=$testimony;
	if($monitorlength>140){
	$testimonyout=substr($testimonyout,0,137);
	$testimonyout=$testimonyout."...";
	}*/
	$status=$row['status'];
	$row['adminoutput']='
	<tr data-id="'.$id.'">
	 <td>'.$fullname.'</td><td>'.$startdate.'</td><td>'.$enddate.'</td><td>'.$eventtype.'</td><td>'.$participants.'</td><td>'.$phoneone.'</td><td>'.$date.'</td><td name="servicestatus'.$id.'">'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="editsingleservicerequest" data-type="editsingleservicerequest" data-divid="'.$id.'">Edit</a></td>
	</tr>
	<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'">
	 <td colspan="100%">
	 <div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
	 <div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
	 
	 </div>
	 </div>
	 </td>
	</tr>
	';
	$row['adminoutputtwo']='
	<div id="form" style="background-color:#fefefe;">
	 <div id="formend">
	<div id="elementholder">
	 <b>Fullname:</b> '.$fullname.'<br>
	 <b>Organisation Name:</b> '.$orgname.'<br>
	 <b>Team:</b> '.$team.'<br>
	</div>
	<div id="elementholder">
	 <b>Event Type:</b> '.$eventtype.'<br>
	 <b>Start Period:</b> '.$startdate.'<br>
	 <b>End Period:</b> '.$enddate.'<br>
	</div>
	<div id="elementholder">
	 <b>Expected Attendance:</b> '.$participants.'<br>
	 <b>Phone One:</b> '.$phoneone.'<br>
	 <b>Phone Two:</b> '.$phonetwo.'<br>
	</div>
	 </div>
	 <div id="formend">
	 <font style="font-size:18px;font-weight:bold;">Venue</font><br>
	<p align="left">'.$venue.'</p>
	 </div>
	<div id="formend">
	 <font style="font-size:18px;font-weight:bold;">Additional Information</font><br>
	<p align="left">'.$extrainfo.'</p>
	</div>
	</div>
	';
	return $row;
} 
function getAllServiceRequests($viewer,$limit){
	$testit=strpos($limit,"-");
	$testit===0||$testit==0||$testit===true||$testit>0?$limit="":$limit=$limit;
	/*if($type=="all" && $limit==""){
	$query="SELECT * FROM servicerequest ORDER BY id DESC LIMIT 0,15";
	$rowmonitor['chiefquery']="SELECT * FROM servicerequest ORDER BY id DESC";
	}elseif($type=="all" && $limit!==""){
	$query="SELECT * FROM servicerequest ORDER BY id DESC $limit";
	$rowmonitor['chiefquery']="SELECT * FROM servicerequest ORDER BY id DESC";
	}else */if($limit==""&&$viewer=="admin"){
	$query="SELECT * FROM servicerequest ORDER BY id,status DESC LIMIT 0,15";
	$rowmonitor['chiefquery']="SELECT * FROM servicerequest ORDER BY id,status DESC";
	}elseif($limit!==""&&$viewer=="admin"){
	$query="SELECT * FROM servicerequest ORDER BY id,status DESC $limit";
	$rowmonitor['chiefquery']="SELECT * FROM servicerequest ORDER BY id DESC";
	}/*elseif($viewer=="viewer"){
	$query="SELECT * FROM servicerequest WHERE status='active' ORDER BY id DESC LIMIT 0,10";
	$rowmonitor['chiefquery']="SELECT * FROM servicerequest WHERE status='active'"; 
	}*/
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line 3894");
	$numrows=mysql_num_rows($run);
	$adminoutput="<td colspan=\"100%\">No entries</td>";
	$adminoutputtwo="";
	/*$vieweroutput='<font color="#fefefe">No testimonies have been shared yet, share yours today</font>';
	$vieweroutputtwo='<font color="#fefefe">No testimonies have been shared yet, share yours today</font>';*/
	if($numrows>0){
	$adminoutput="";
	$adminoutputtwo="";
	$vieweroutput="";
	$vieweroutputtwo="";

	while($row=mysql_fetch_assoc($run)){
	$outs=getSingleServiceRequest($row['id']);
	$adminoutput.=$outs['adminoutput'];
	$adminoutputtwo.=$outs['adminoutputtwo'];
	/*$vieweroutput.=$outs['vieweroutput'];
	$vieweroutputtwo.=$outs['vieweroutputtwo'];*/
	}

	}
	$top='<table id="resultcontenttable" cellspacing="0">
	 <thead><tr><th>Name</th><th>From</th><th>End Date</th><th>EventType</th><th>ParticipantNo</th><th>ContactNo</th><th>Date</th><th>status</th><th>View/Edit</th></tr></thead>
	 <tbody>';
	$bottom=' </tbody>
	 </table>';
	 $row['chiefquery']=$rowmonitor['chiefquery'];
	$outs=paginatejavascript($rowmonitor['chiefquery']);
	$outsviewer=paginate($rowmonitor['chiefquery']);
	$paginatetop='
	<div id="paginationhold">
	 <div class="meneame">
	 <input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
	 <input type="hidden" name="outputtype" value="servicerequests"/>
	 <input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
	 <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
	 <div class="pagination">
	 '.$outs['usercontrols'].'
	 </div>
	 </div>
	</div>
	<div id="paginateddatahold" data-name="contentholder">';

	$paginatebottom='
	</div><div id="paginationhold">
	 <div class="meneame">
	 <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
	 </div>
	</div>';
	$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
	$row['adminoutputtwo']=$top.$adminoutput.$bottom;
	/*$row['vieweroutput']=$vieweroutput;
	$row['vieweroutputtwo']=$vieweroutputtwo;*/

	return $row;
}
function getSingleBooking($bookingid){
	global $host_addr,$host_target_addr;
	global $host_addr,$host_target_addr; 
	$row=array();
	$query="SELECT * FROM bookings WHERE id=$bookingid";
	$run=mysql_query($query)or die(mysql_error()." Line 4033");
	$row=mysql_fetch_assoc($run);
	$id=$row['id'];
	$fullname=$row['orgname'];
	$orgaddress=$row['orgaddress'];
	$themetitle=$row['themetitle'];
	$contact=$row['contactperson'];
	$participants=$row['eattendance'];
	$eventtype=$row['eventtype'];
	$language=$row['language'];
	$startdate=$row['eventstart'];
	$enddate=$row['eventstop'];
	$phoneone=$row['phoneone'];
	$phonetwo=$row['phonetwo'];
	$email=$row['email'];
	$extrainfo=$row['additionalinfo'];
	$date=$row['datetime'];
	$status=$row['status'];
	$row['adminoutputtwo']='
	<div id="form" style="background-color:#fefefe;">
	 <div id="formend">
	<div id="elementholder">
	 <b>Theme/Title:</b> '.$themetitle.'<br>
	 <b>Organisation Name:</b> '.$fullname.'<br>
	 <b>Organisation Address:</b> '.$orgaddress.'<br>
	 <b>Contact Person:</b> '.$contact.'<br>
	 <b>Email:</b> '.$email.'<br>
	</div>
	<div id="elementholder">
	 <b>Event Type:</b> '.$eventtype.'<br>
	 <b>Language:</b> '.$language.'<br>
	 <b>Start Period:</b> '.$startdate.'<br>
	 <b>End Period:</b> '.$enddate.'<br>
	</div>
	<div id="elementholder">
	 <b>Expected Attendance:</b> '.$participants.'<br>
	 <b>Phone One:</b> '.$phoneone.'<br>
	 <b>Phone Two:</b> '.$phonetwo.'<br>
	</div>
	 </div>
	<div id="formend">
	 <font style="font-size:18px;font-weight:bold;">Additional Information</font><br>
	<p align="left">'.$extrainfo.'</p>
	</div>
	</div>
	';
	/*$monitorlength=strlen($testimony);
	$testimonyout=$testimony;
	if($monitorlength>140){
	$testimonyout=substr($testimonyout,0,137);
	$testimonyout=$testimonyout."...";
	}*/
	$status=$row['status'];
	$row['adminoutput']='
	<tr data-id="'.$id.'">
	 <td>'.$fullname.'</td><td>'.$startdate.'</td><td>'.$enddate.'</td><td>'.$eventtype.'</td><td>'.$language.'</td><td>'.$participants.'</td><td>'.$phoneone.'</td><td>'.$date.'</td><td name="bookingstatus'.$id.'">'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="editsinglebooking" data-type="editsinglebooking" data-divid="'.$id.'">Edit</a></td>
	</tr>
	<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'">
	 <td colspan="100%">
	 <div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
	 <div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
	 
	 </div>
	 </div>
	 </td>
	</tr>
	';

	return $row;
}
function getAllBookings($viewer,$limit){
	$testit=strpos($limit,"-");
			$testit!==false?$limit="":$limit=$limit;
	/*if($type=="all" && $limit==""){
	$query="SELECT * FROM bookings ORDER BY id DESC LIMIT 0,15";
	$rowmonitor['chiefquery']="SELECT * FROM bookings ORDER BY id DESC";
	}elseif($type=="all" && $limit!==""){
	$query="SELECT * FROM bookings ORDER BY id DESC $limit";
	$rowmonitor['chiefquery']="SELECT * FROM bookings ORDER BY id DESC";
	}else */if($limit==""&&$viewer=="admin"){
	$query="SELECT * FROM bookings ORDER BY id DESC LIMIT 0,15";
	$rowmonitor['chiefquery']="SELECT * FROM bookings ORDER BY id DESC";
	}elseif($limit!==""&&$viewer=="admin"){
	$query="SELECT * FROM bookings ORDER BY id,status DESC $limit";
	$rowmonitor['chiefquery']="SELECT * FROM bookings ORDER BY id DESC";
	}/*elseif($viewer=="viewer"){
	$query="SELECT * FROM bookings WHERE status='active' ORDER BY id DESC LIMIT 0,10";
	$rowmonitor['chiefquery']="SELECT * FROM bookings WHERE status='active'"; 
	}*/
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line 3894");
	$numrows=mysql_num_rows($run);
	$adminoutput="<td colspan=\"100%\">No entries</td>";
	$adminoutputtwo="";
	/*$vieweroutput='<font color="#fefefe">No testimonies have been shared yet, share yours today</font>';
	$vieweroutputtwo='<font color="#fefefe">No testimonies have been shared yet, share yours today</font>';*/
	if($numrows>0){
	$adminoutput="";
	$adminoutputtwo="";
	$vieweroutput="";
	$vieweroutputtwo="";

	while($row=mysql_fetch_assoc($run)){
	$outs=getSingleBooking($row['id']);
	$adminoutput.=$outs['adminoutput'];
	$adminoutputtwo.=$outs['adminoutputtwo'];
	/*$vieweroutput.=$outs['vieweroutput'];
	$vieweroutputtwo.=$outs['vieweroutputtwo'];*/
	}

	}
	$top='<table id="resultcontenttable" cellspacing="0">
	 <thead><tr><th>OrganisationName</th><th>From</th><th>End Date</th><th>EventType</th><th>Language</th><th>ParticipantNo</th><th>ContactNo</th><th>Date</th><th>status</th><th>View/Edit</th></tr></thead>
	 <tbody>';
	$bottom=' </tbody>
	 </table>';
	 $row['chiefquery']=$rowmonitor['chiefquery'];
	$outs=paginatejavascript($rowmonitor['chiefquery']);
	$outsviewer=paginate($rowmonitor['chiefquery']);
	$paginatetop='
	<div id="paginationhold">
	 <div class="meneame">
	 <input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
	 <input type="hidden" name="outputtype" value="bookings"/>
	 <input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
	 <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
	 <div class="pagination">
	 '.$outs['usercontrols'].'
	 </div>
	 </div>
	</div>
	<div id="paginateddatahold" data-name="contentholder">';

	$paginatebottom='
	</div><div id="paginationhold">
	 <div class="meneame">
	 <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
	 </div>
	</div>';
	$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
	$row['adminoutputtwo']=$top.$adminoutput.$bottom;
	/*$row['vieweroutput']=$vieweroutput;
	$row['vieweroutputtwo']=$vieweroutputtwo;*/

	return $row;
}
function getSingleSubscriber($subscriberid){
	$row=array();
	$query="SELECT * FROM subscriptionlist WHERE id=$subscriberid";
	$run=mysql_query($query)or die(mysql_error()." Line 4382");
	$row=mysql_fetch_assoc($run);
	$id=$row['id'];
	$email=$row['email'];
	$blogtypeid=$row['blogtypeid'];
	$blogcategoryid=$row['blogcatid'];
	$blogcategorydata=array();
	$blogcategorydata['catname']="No category";
	$blogcategorydata['blogtypeid']=0;
	if($blogcategoryid>0){
	$catquery="SELECT * FROM subscriptionlist WHERE id=$subscriberid";
	$catrun=mysql_query($catquery)or die(mysql_error()." Line 4382");
	$blogcategorydata=mysql_fetch_assoc($catrun);
	}
	if($blogtypeid>0){
	$blogtypedata=getSingleBlogType($blogtypeid);
	}else{
	$blogtypedata=getSingleBlogType($blogcategorydata['blogtypeid']);

	}

	$status=$row['status'];
	if($status=="disabled"||$status=="inactive"){
	$controlname='activatesubscriber';
	$controltext='activate';
	}elseif ($status=="active") {
	 # code...
	$controlname='disablesubscriber';
	$controltext='disable';
	}
	$row['adminoutput']='
	<tr data-id="'.$id.'">
	 <td>'.$email.'</td><td>'.$blogtypedata['name'].'</td><td>'.$blogcategorydata['catname'].'</td><td name="subscriptionstatus'.$id.'">'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="'.$controlname.'" data-type="'.$controlname.'" data-divid="'.$id.'">'.$controltext.'</a></td>
	</tr>
	<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'">
	 <td colspan="100%">
	 <div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
	 <div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
	 
	 </div>
	 </div>
	 </td>
	</tr>
	';
	return $row;
}
function getAllSubscribers($viewer,$limit,$typeid,$type){
	$typequery="";
	$testit=strpos($limit,"-");
	$testit===0||$testit==0||$testit===true||$testit>0?$limit="":$limit=$limit;
	$type=="blogtype"?$typequery="WHERE blogtypeid=$typeid":$typequery="WHERE blogcatid=$typeid";
	/*if($type=="all" && $limit==""){
	$query="SELECT * FROM subscriptionlist ORDER BY id DESC LIMIT 0,15";
	$rowmonitor['chiefquery']="SELECT * FROM subscriptionlist ORDER BY id DESC";
	}elseif($type=="all" && $limit!==""){
	$query="SELECT * FROM subscriptionlist ORDER BY id DESC $limit";
	$rowmonitor['chiefquery']="SELECT * FROM subscriptionlist ORDER BY id DESC";
	}else */if($limit==""&&$viewer=="admin"){
	$query="SELECT * FROM subscriptionlist $typequery ORDER BY id,status DESC LIMIT 0,15";
	$rowmonitor['chiefquery']="SELECT * FROM subscriptionlist $typequery ORDER BY id,status DESC";
	}elseif($limit!==""&&$viewer=="admin"){
	$query="SELECT * FROM subscriptionlist $typequery ORDER BY id,status DESC $limit";
	$rowmonitor['chiefquery']="SELECT * FROM subscriptionlist $typequery ORDER BY id,status DESC";
	}/*elseif($viewer=="viewer"){
	$query="SELECT * FROM bookings WHERE status='active' ORDER BY id DESC LIMIT 0,10";
	$rowmonitor['chiefquery']="SELECT * FROM bookings WHERE status='active'"; 
	}*/
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line 3894");
	$numrows=mysql_num_rows($run);
	$adminoutput="<td colspan=\"100%\">No entries</td>";
	$adminoutputtwo="";
	/*$vieweroutput='<font color="#fefefe">No testimonies have been shared yet, share yours today</font>';
	$vieweroutputtwo='<font color="#fefefe">No testimonies have been shared yet, share yours today</font>';*/
	$idarr=array();
	if($numrows>0){
	$adminoutput="";
	$adminoutputtwo="";
	$vieweroutput="";
	$vieweroutputtwo="";
	while($row=mysql_fetch_assoc($run)){
	 $idarr[]=$row['id'];
	$outs=getSingleSubscriber($row['id']);
	$adminoutput.=$outs['adminoutput'];
	// $adminoutputtwo.=$outs['adminoutputtwo'];
	/*$vieweroutput.=$outs['vieweroutput'];
	$vieweroutputtwo.=$outs['vieweroutputtwo'];*/
	}
	}

	$top='<table id="resultcontenttable" cellspacing="0">
	 <thead><tr><th>Email</th><th>BlogType</th><th>Blog Category</th><th>status</th><th>View/Edit</th></tr></thead>
	 <tbody>';
	$bottom=' </tbody>
	 </table>';
	 $row['chiefquery']=$rowmonitor['chiefquery'];
	$outs=paginatejavascript($rowmonitor['chiefquery']);
	$outsviewer=paginate($rowmonitor['chiefquery']);
	$paginatetop='
	<div id="paginationhold">
	 <div class="meneame">
	 <input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
	 <input type="hidden" name="outputtype" value="subscribers|'.$typeid.'|'.$type.'"/>
	 <input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
	 <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
	 <div class="pagination">
	 '.$outs['usercontrols'].'
	 </div>
	 </div>
	</div>
	<div id="paginateddatahold" data-name="contentholder">';

	$paginatebottom='
	</div><div id="paginationhold">
	 <div class="meneame">
	 <div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
	 </div>
	</div>';
	$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
	$row['adminoutputtwo']=$top.$adminoutput.$bottom;
	/*$row['vieweroutput']=$vieweroutput;
	$row['vieweroutputtwo']=$vieweroutputtwo;*/

	return $row;
}
function getSingleLGA($id){
  $query="SELECT * FROM local_govt WHERE id_no=$id";
  $run=mysql_query($query)or die(mysql_error()." Real number:".__LINE__." old number  1579");
  $row=mysql_fetch_assoc($run);
  return $row;
}
function generateMailMarkup($from,$to,$title,$content,$footer,$type){
  global $host_addr;
  $row=array();
  $phpmailermarkup='';
  $logoout="";
  $logoalt="";
  $parentheader="Muyiwa Afolabi's Website";
  $toplstyle="";
  $innerstyle=file_get_contents("../stylesheets/ink.css");
  if($host_addr=="http://localhost/muyiwasblog/"){
    $toplstyle='<link rel="stylesheet" href="'.$host_addr.'stylesheets/ink.css">';
     $innerstyle="";
  }
  if($type=="fjc"){
    $logoout="fjclogo.png";
    $logoalt="Frontiers Job-Connect";
    $parentheader="Frontiers Job-Connect";
  }else if($type=="fc"){
    $logoout="frontierslogoalbumart.jpg";
    $logoalt="Frontiers Consulting";
    $parentheader="Muyiwa Afolabi's Website";
  }else{

  }
  $rowmarkup='
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
          <title>'.$parentheader.' | '.$title.'</title>
          '.$toplstyle.'
          <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
          <style type="text/css">
            '.$innerstyle.'
          </style>
        </head>
        <body>
         <table class="body">
          <tr>
            <td class="center" align="center" valign="top">
              <center>
                <tr>
                 <td class="heading">
                  <img src="'.$host_addr.'images/'.$logoout.'"  alt="'.$logoalt.'" width="220" height="" style="display: inline-block;" /><br>
                 </td>
                </tr>
                <tr>
                 <td class="heading2">
                  '.$title.'
                 </td>
                </tr>
                <tr>
                 <td class="content">
                  '.$content.'
                 </td>
                </tr>
                <tr>
                 <td class="minifoot">
                  <!--<div id="sociallinks">
                    <div id="socialholder" name="socialholdfacebook"><a href="##" target="_blank"><img src="'.$host_addr.'images/Facebook-Icon.png" alt="Facebook"/></a></div>
                    <div id="socialholder" name="socialholdlinkedin"><a href="##" target="_blank"><img src="'.$host_addr.'images/Linkedin-Icon.png" alt="LinkedIn"/></a></div>
                    <div id="socialholder" name="socialholdtwitter"><a href="##" target="_blank"><img src="'.$host_addr.'images/Twitter-Icon.png" alt="Twitter"/></a></div>
                    <div id="socialholder" name="socialholdgoogleplus"><a href="##" target="_blank"><img src="'.$host_addr.'images/google-plus-icon.png" alt="Google+"/></a></div>
                  </div>-->
                  '.$footer.'
                 </td>
                </tr>
                <tr>
                  <td class="footing">
                    &copy; '.$logoalt.' '.date("Y").' Developed by Okebukola Olagoke.<br>
                  </td>
                </tr>
              </center>
            </td>
          </tr>
         </table>
        </body>
      </html>
  ';
  // echo $rowmarkup;
  $row['rowmarkup']=$rowmarkup;
  return $row;
}
function getAdmin($uid){
  $query="SELECT * FROM admin WHERE id=$uid";
  $run=mysql_query($query)or die(mysql_error()." ".__LINE__);
  $row=mysql_fetch_assoc($run);
  return $row;
}
 include('Gravatar.php');
 include('adminusersmodule.php');
 include('fjcusermodule.php');
 include('storemodule.php');
 include('generaldatamodule.php');
 include('sermonsmodule.php');
 include('faqmodule.php');
?> 