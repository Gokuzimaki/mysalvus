<?php
/*
This is a series of utility functions that affect a range of useful actions
that can be carried out, from file uploads, directory management, database insertion
retrieval and entry creation and a host of others.
*/

function getExtension($str) {
     $i = strrpos($str,".");
     if (!$i) { return false; }
     $l = strlen($str) - $i;
     $ext = substr($str,$i+1,$l);
     return $ext;
}
function getExtensionAdvanced($str){
    $row=array();
    $row['imageexts']=["jpg" , "jpeg" , "png" , "gif", "bmp", "ico", "svg"];
    $row['videoexts']=["mp4" , "3gp" , "flv" , "swf" , "webm"];
    $row['audioexts']=["mp3" , "ogg" , "wav" , "amr"];
    $row['officeexts']=["doc" , "docx" , "xls" , "xlsx" , "ppt" , "pptx"];
    $row['pdfexts']=["pdf"];
    $row['compressedexts']=["tar" , "gz" , "zip" , "7z" , "rar"];
    $i = strrpos($str,".");
    if (!$i) { return false; }
    $l = strlen($str) - $i;
    $ext = strtolower(substr($str,$i+1,$l));
    $row['ext']=$ext;
    if (in_array($ext, $row['imageexts'])) {
        $entrytype = "image";
    } else if (in_array($ext, $row['videoexts'])) {
        $entrytype = "video";
    } else if (in_array($ext, $row['officeexts'])) {
        $entrytype = "office";
    } else if ($ext == "pdf") {
        $entrytype = "pdf";
    } else if (in_array($ext, $row['audioexts'])) {
        $entrytype = "audio";

    } else if (in_array($ext, $row['compressedexts'])) {
        $entrytype = "compressed";

    } else {
        $entrytype = "others";
    }
    $fname=getFilename($str);
    $row['filename']=$fname;
    $row['type']=$entrytype;
    return $row;
}

function getFilename($filepath){
  $i = strrpos($filepath,"/");
  if (!$i) { return $filepath; }
  $filename=explode("/",$filepath);
  $tot=count($filename);
  return $filename[$tot-1];
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

function url_get_contents ($Url) {
    if (!function_exists('curl_init')){ 
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
//for performing targeted single update functions
function genericSingleUpdate($tablename,$updateField,$updateValue,$orderfield,$ordervalue){
  $ordervalues="";
  $realupdateValue=$updateValue;
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
      $sortdata=strpos($updateValue, "***delete***");
      if($updateValue=="***delete***"||($sortdata!==false||$sortdata===true)){
          // echo "empty $sortdata - $updateValue";
          $realupdateValue="";
      }
      $query="UPDATE $tablename SET $updateField='$realupdateValue' WHERE $ordervalues";
    }else{
      $sortdata=strpos($updateValue, "***delete***");
      if($updateValue=="***delete***"||($sortdata!==false||$sortdata===true)){
          // echo "empty $sortdata - $updateValue";
          $realupdateValue="";
      }
      $query="UPDATE $tablename SET $updateField='$realupdateValue' WHERE $orderfield=$ordervalue";
    }
    //// echo $query;
    if($updateValue!==""){
      
      $run=mysql_query($query)or die(mysql_error());
    }
  }else{
    // echo "$tablename"
    die('cant Update with empty value in critical column'); 
  }
}

function image_check_memory_usage($img, $max_breedte, $max_hoogte){
  if(file_exists($img)){
    $K64 = 65536;    // number of bytes in 64K
    $memory_usage = memory_get_usage();
    $memory_limit = abs(intval(str_replace('M','',ini_get('memory_limit'))*1024*1024));
    $image_properties = getimagesize($img);
    $image_width = $image_properties[0];
    $image_height = $image_properties[1];
    $image_bits = $image_properties['bits'];
    $image_memory_usage = $K64 + ($image_width * $image_height * ($image_bits )  * 2);
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

function getSizeByFixedWidth($newWidth,$newHeight,$width,$height,$forceStretch){
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
function getSizeByFixedHeight($newWidth,$newHeight,$width,$height,$forceStretch){
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
    # Purpose:    Depending on the height, choose to resize by 0, 1, or 2
    # Param in:   The new height and new width
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
{
  #
  #       To clarify the $option input:
  #               0 = The exact height and width dimensions you set.
  #               1 = Whatever height is passed in will be the height that
  #                   is set. The width will be calculated and set automatically
  #                   to a the value that keeps the original aspect ratio.
  #               2 = The same but based on the width.
  #               3 = Depending whether the image is landscape or portrait, this
  #                   will automatically determine whether to resize via
  #                   dimension 1,2 or 0.

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
function imageResize($imagefile,$newwidth,$newheight,$imgextension,$watermarkval,$watermarkimg,$imgpath,$imgquality=100){
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
  -the watermark value for the image, values are "true" if water mark is
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
          $magicianObj -> saveImage($imagefile2,100);
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
      $gr = new gifresizer;    //New Instance Of GIFResizer 
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
            $magicianObj -> saveImage($imagefile2,100);
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

function pullFontAwesomeClasses($path="../icons/font-awesome/css/font-awesome.css"){
  $row=array();
  // the path variable is the relative path to the fontawesome css file
  $rt=array();
  $ic=array();
  $count=0;
  $truecount=0;
  if(file_exists($path)){
    $stringtwo=file_get_contents($path);
    preg_match_all('~(fa(?:-[a-z0-9]{1,})+:)~im', $stringtwo, $matchall);
    for ($z = 0;$z < count($matchall);$z++) { 
          for ($x = 0;$x < count($matchall[$z]);$x++) 
          { 
            $count++;
            if (
                in_array($matchall[$z][$x], $rt)||
                $matchall[$z][$x]=="fa-pull-left:"
                ||$matchall[$z][$x]=="fa-pull-right:"
                ||$matchall[$z][$x]=="fa-usd:"
                ||$matchall[$z][$x]=="fa-eur:"
                ||$matchall[$z][$x]=="fa-inr:"
                ||$matchall[$z][$x]=="fa-cny:"
                ||$matchall[$z][$x]=="fa-rmb:"
                ||$matchall[$z][$x]=="fa-jpy:"
                ||$matchall[$z][$x]=="fa-rouble:"
                ||$matchall[$z][$x]=="fa-rub:"
                ||$matchall[$z][$x]=="fa-krw:"
                ||$matchall[$z][$x]=="fa-btc:"
                ||$matchall[$z][$x]=="fa-mail-reply:"
                ||$matchall[$z][$x]=="fa-times:"
                ||$matchall[$z][$x]=="fa-remove:"
                ||$matchall[$z][$x]=="fa-cog:"
                ||$matchall[$z][$x]=="fa-repeat:"
                ||$matchall[$z][$x]=="fa-lg"
                ||$matchall[$z][$x]=="fa-2x"
                ||$matchall[$z][$x]=="fa-3x"
                ||$matchall[$z][$x]=="fa-4x"
                ||$matchall[$z][$x]=="fa-5x"
                ||$matchall[$z][$x]=="fa-fw"
                ||$matchall[$z][$x]=="fa-ul"
                ||$matchall[$z][$x]=="fa-li"
                ||$matchall[$z][$x]=="fa-border"
                ||$matchall[$z][$x]=="fa-spin"
                ||$matchall[$z][$x]=="fa-pulse"
                ||$matchall[$z][$x]=="fa-rotate"
                ||$matchall[$z][$x]=="fa-flip-horizontal"
                ||$matchall[$z][$x]=="fa-flip-vertical"
                ||$matchall[$z][$x]=="fa-rotate-90"
                ||$matchall[$z][$x]=="fa-rotate-180"
                ||$matchall[$z][$x]=="fa-rotate-270"
                ||$matchall[$z][$x]=="fa-stack"
                ||$matchall[$z][$x]=="fa-inverse"
                ||$matchall[$z][$x]=="fa-stack-1x"
                ||$matchall[$z][$x]=="fa-stack-2x"
              ) {
              $count-=1;
            }else{
              $icondata=explode("fa-",$matchall[$z][$x]);
              // echo  $icondata[1]."<br><br>";

              if(strpos($icondata[1], "-o-")){
                $icondata=explode("-o-", $icondata[1]);
                // $icond=str_replace(":", "", $icondata[1]);
                $ctext=$icondata[0];
                $etext=isset($icondata[2])?$icondata[2]:"";
                $pusher=isset($icondata[1])?$icondata[1]:"";

                $cb=$pusher!==""?" (Alternate or Has circle design or no border on it)":"";
                $dataout=str_replace("-", " ", str_replace(":", "",$ctext.$etext.$pusher.$cb));
                if(!in_array($dataout, $ic)){
                  $ic[]=$dataout;
                }
                // echo str_replace("-", " ", str_replace(":", "",$ctext.$etext.$pusher.$cb))."<br>";
              }else if(strpos($icondata[1], "-o:")){
                $icondata=explode("-o:", $icondata[1]);
                // $icond=str_replace(":", "", $icondata[1]);
                $ctext=$icondata[0];
                // $etext=isset($icondata[2])?$icondata[2]:"";
                $pusher=isset($icondata[1])?$icondata[1]:"";
                $cb=$pusher!==""?" (Alternate or Has circle design or no border on it)":"";
                $dataout=str_replace("-", " ", str_replace(":", "",$ctext.$pusher.$cb));
                if(!in_array($dataout, $ic)){
                  $ic[]=$dataout;
                }
                // echo str_replace("-", " ", str_replace(":", "",$ctext.$pusher.$cb))."<br>";
              }else{
                $dataout=str_replace("-", " ", str_replace(":", "",$icondata[1]));
                if(!in_array($dataout, $ic)){
                  $ic[]=$dataout;
                }
                // echo str_replace("-", " ", str_replace(":", "",$icondata[1]))."<br>";
              }

              // store the original value of the 
              $rtdata=str_replace(":","",$matchall[$z][$x]);
              if(!in_array($rtdata, $rt)){

                // echo "<br>cur-dataout: $dataout \t cur-rdata: $rtdata<br>";
                $rt[$truecount]=$rtdata;
                $rt['namematch'][$dataout]=$truecount;
                $truecount++;
              }

            }
            /*if(isset($rt[$x])){
              // echo  $rt[$x]."<br><br>";
              
            }*/
          } 
    }
    // pop the classes that are not needed out of the array

  }
  $row['numrows']=count($rt);
  $row['famatches']=$rt;
  $row['faiconnames']=$ic;
  return $row;
}

function checkExistingFile($filepath,$filename){
  $retvals=array();
  $files=array();
  $dhandle= opendir($filepath);
  if($dhandle){
    while(false !== ($fname = readdir($dhandle))){
      if(($fname!=='.') && ($fname!=='..') && ($fname!== basename($_SERVER['PHP_SELF']))){
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
      // echo $construct;
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

function sortThroughDir($filepath,$filetype,$sorttype=""){
  global $host_addr;
  //this function runs through a given directory and matches all files in there
  // that fit the filetype parameter regex then returns them in a multidimensional array
  $pattern='/('.$filetype.')/i';
  $row=array();
  $files=array();
  $multicount=0;
  $totalfiles=0;
  $row['matchedfiles']="";
  $row['matchedextensions']="";
  if($filetype!==""){
    $dhandle= opendir($filepath);
    if($dhandle){

      while(false !== ($fname = readdir($dhandle))){

        if(($fname!='.') && ($fname!='..') && ($fname!= basename($_SERVER['PHP_SELF']))){
          //check if the file in mention is a directory , if no add it to the files array
          $files[]=(is_dir("./$fname")) ? "":$fname;
        }
      }
      if($sorttype=="plainsort"){
        // echo "were here";
        natcasesort($files);
        $count=0;
        foreach ($files as $value) {
          # code...
          $files[$count]=$value;
          // echo "<br>".$value."<br>";
          $count++;
        }
        // print_r($files);
      }
      $totalfiles=count($files);
      $row['totalfilecount']=$totalfiles;
      $arraycount=$totalfiles-1;
      $match="false";
      // echo "Arraycount: $arraycount<br>";
      for($i=0;$i<=$arraycount;$i++){
        // echo "<br>".$files[$i]."<br>";
        $curextension=getExtension($files[$i]);
        $matchout=preg_match($pattern, $files[$i],$matchdone);
        if(count($matchdone)>0){
          $match="true";
          $row['matchedextensions'][$multicount]=$curextension;
          $row['matchedfiles'][$multicount]=$files[$i];
          $row['matchedfilespath'][$multicount]=$filepath.$files[$i];
          $multicount++;
        }

      }
    }
  }
  
  $row['totalfilecount']=$totalfiles;
  $row['totalmatches']=$multicount;

  return $row;
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
    // echo$construct;
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
    // // echo$construct;
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
  $fsizeplain=0;
  $fsdenom="KB";
  if(strlen($filesize)>3){
    $filesize=$filesize/1024;
    $filesize=round($filesize,2); 
    $fsizeplain=$filesize;
    $filesize="".$filesize."MB";
    $fdenom="MB";
  }else{
    $fsizeplain=$filesize;
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
  $outs['fsdenom']=$fsdenom;
  $outs['filesizeplain']=$fsizeplain;
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
            // echo "in here";
          }else{
            $imagename2=$imagename;
          }
      $imagelocation=$defaultimglocation.$imagename2;
      move_uploaded_file("$image","$imagelocation");
      $exists=file_exists($imagelocation);
      $exists===true?$exist="true":$exist="false";
      // echo $exist;
      if($acceptedsize!==""){
        $match=checkExistingFile($defaultimglocation,$imagename2);
        if($match['matchval']=="true"){
          $nextentry=md5($match['totalfilecount']+1);
          $imagename2=$imgname.$nextentry.".".$extension;
        }else{
          $imagename2=$imagename;
        }
        $curpath=$defaultimglocation.$imagename2;
        // echo $curpath;
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

function fileSizeConvert($bytes,$units=""){
  $bytes = floatval($bytes);
  $arBytes = array(
      0 => array(
          "UNIT" => "TB",
          "VALUE" => pow(1024, 4)
      ),
      1 => array(
          "UNIT" => "GB",
          "VALUE" => pow(1024, 3)
      ),
      2 => array(
          "UNIT" => "MB",
          "VALUE" => pow(1024, 2)
      ),
      3 => array(
          "UNIT" => "KB",
          "VALUE" => 1024
      ),
      4 => array(
          "UNIT" => "B",
          "VALUE" => 1
      ),
  );


  foreach($arBytes as $arItem)
  {   
    if($units==""){
      // test the value in bytes against the prestored ones in the array
      // if a match is found then the byte range is valid for that unit
      if($bytes >= $arItem["VALUE"]){
          $result = $bytes / $arItem["VALUE"];
          $result = strval(round($result, 2))." ".$arItem["UNIT"];
          break;
      }else{
          $result=0;
      }
    }else{
      // check and validate if the specified unit required is available 
      // among the stored units of the array.
      if($units == $arItem["UNIT"]){
          $result = $bytes / $arItem["VALUE"];
          $result = strval(round($result, 2))." ".$arItem["UNIT"];
          break;
      }else{
          $result=0;
      }
    }
  }
  return $result;
}

function numberSizeConvert($val,$units=""){
  $val = floatval($val);
  $arBytes = array(
      0 => array(
          "UNIT" => "t",
          "VALUE" => pow(1000, 4)
      ),
      1 => array(
          "UNIT" => "b",
          "VALUE" => pow(1000, 3)
      ),
      2 => array(
          "UNIT" => "m",
          "VALUE" => pow(1000, 2)
      ),
      3 => array(
          "UNIT" => "K",
          "VALUE" => 1000
      )
  );


  foreach($arBytes as $arItem)
  {   
    if($units==""){
      // test the value in thousands against the prestored ones in the array
      // if a match is found then the value range is valid for that unit
      if($val >= $arItem["VALUE"]){
          $result = $val / $arItem["VALUE"];
          $result = strval(round($result, 2))." ".$arItem["UNIT"];
          break;
      }else{
          $result=0;
      }
    }else{
      // check and validate if the specified unit required is available 
      // among the storeds units of the array.
      if($units == $arItem["UNIT"]){
          $result = $val / $arItem["VALUE"];
          $result = strval(round($result, 2))." ".$arItem["UNIT"];
          break;
      }else{
          $result=0;
      }
    }
  }
  if($result==0){
    $result=$val;
  }
  return $result;
}
// for cleaning strings and making them suitable as filenames
function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}

// for getting the rendered content of php files.
function get_include_contents($filename,$initvars="",$initvals="",$mode="normal") {
    include("globalsmodule.php");
    if($initvars!==""&&!is_array($initvars)){
      // create the variable name and assign it the value
      $$initvars=$initvals;
    }else if(is_array($initvars)&&is_array($initvals)){
      $cvals=count($initvars);
      // create the variable names based on array values
      for($i=0;$i<$cvals;$i++){
        $$initvars[$i]=$initvals[$i];
      }
    }else if($mode=="json"){
      

    }

    if (is_file($filename)) {
        // echo "its a file";
        ob_start();
        include $filename;
        return ob_get_clean();
    }
    return false;
}
function get_include_contents_two($filename,$initvars="",$initvals="",$mode="normal") {
    include("globalsmodule.php");
    if($initvars!==""&&!is_array($initvars)){
      // create the variable name and assign it the value
      $$initvars=$initvals;
    }else if(is_array($initvars)&&is_array($initvals)){
      $cvals=count($initvars);
      // create the variable names based on array values
      for($i=0;$i<$cvals;$i++){
        $$initvars[$i]=$initvals[$i];
      }
    }else if($mode=="json"){
      

    }

    if (is_file($filename)) {
        // echo "its a file";
        // ob_start();
         $fdata=eval(file_get_contents($filename));
        return $fdata;
    }
    // return false;
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

// prime no test func
function getPrimes($no){
  $row=array();
  $row['msg']="none";
  // test if the number is numeric and make sure its value
  // is greater than 1.
  if(is_numeric($no)&&$no>1){
    // store the smallest prime in here
    // optimus prime
    $row['primes'][]=2;

    for($i=3;$i<=$no;$i++){
      // run a backward loop on the primes array and
      // start dividing the current number by the previously stored
      // primes till a hit is obtained
      $doprime="true";

      for($t=0;$t<count($row['primes']);$t++){
        if($i%$row['primes'][$t]==0){
          $doprime="false";
          // $row['submsg'][]=$i.','.$row['primes'][$t];
          break;
        }
      }
      if($doprime=="true"){
        $row['primes'][]=$i;

      }      
    }
  }else{
    $row['msg']="Your number is quite small try a large one...say 100.";
  }
  return $row;
}

function testPrime($no){
  //1 is not prime. See: http://en.wikipedia.org/wiki/Prime_number#Primality_of_one
    if($no > 1&& $no==2)
        return true;
    /**
     * if the number is divisible by two, then it's not prime and it's no longer
     * needed to check other even numbers
     */
    if($no % 2 == 0) {
        return false;
    }

    /**
     * Checks the odd numbers. If any of them is a factor, then it returns false.
     * The sqrt can be an aproximation, hence just for the sake of
     * security, one rounds it to the next highest integer value.
     */
    $ceil = ceil(sqrt($no));
    for($i = 3; $i <= $ceil; $i = $i + 2) {
        if($no % $i == 0)
            return false;
    }

    return true;
}
function getSingleMediaDataTwo($partid){
  $numrows=0;
  
  $query="SELECT * FROM media WHERE id='$partid'";
  
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
  $row['numrows']=$numrows;
  return $row;
}
function checkEmail($email,$tablename,$columnname,$extraq=""){
  $row=array();
  $extraquery="";
  if(is_array($email)){
    $contentdata=$email;
    $email=$contentdata['email'];
    $extrafieldcount=isset($contentdata['column'])?
    count($contentdata['column']):0;

    for($i=0;$i<$extrafieldcount;$i++){
        // echo json_encode(array("ef"=>"$extrafieldcount"));
        $logic=strtoupper($contentdata['logic'][$i]);// OR | AND | LIKE
        if($logic==1){
          $logic="OR";
        }else if($logic==2){
          $logic="AND";
        }
        $column=$contentdata['column'][$i];
        $value=$contentdata['value'][$i];
        $clogic=isset($contentdata['clogic'][$i])?
        strtoupper($contentdata['clogic'][$i]):"";// OR | AND | LIKE
        $extraquery.="$logic $column = '$value' $clogic";
    }

  }
  $row['testquery']="";
  // verify the email address first using regex
  $query="SELECT * FROM $tablename where $columnname='$email' $extraquery";
  // echo $query;
  
  $run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
  $numrows=mysql_num_rows($run);
  $row['testresult']="";
  if($numrows>0){
    $row=mysql_fetch_assoc($run);
    $row['testresult']="matched";
  }else{
    $row['testresult']="unmatched";
  }
  $row['testquery']="$query";
  
  return $row;
}

function produceOptionDates($from,$to,$display,$type=""){
  $output='<option value="">--'.$display.'--</option>';
  if($type==""||$type=="reversedyear"){
    if($to=="current"){
      $to=date('Y');
      for($i=$from;$i<=$to;$i++){
        $output.='<option value="'.$i.'">'.$i.'</option>';
      }
    }else{
      if($type==""){
        for($i=$from;$i<=$to;$i++){
          $output.='<option value="'.$i.'">'.$i.'</option>';
        } 
      }else if($type=="reversedyear"){
        for($i=$to;$i>=$from;$i--){
          $output.='<option value="'.$i.'">'.$i.'</option>';
        }
      }
    }
  }else if($type!==""){
    $output="";
    if($type=="month"){
      $to=date('Y');
      for($i=1;$i<=12;$i++){
        $ti=$i<10?"0$i":$i;
        $cd=$i<10?$to."-0$i-01":$to."-$i-01";
        $m=date("F",strtotime($cd));
        $output.='<option value="'.$ti.'">'.$m.'</option>';
      }
    }
  }
  return $output;
}

function produceStates($countryid,$stateid){
  if(($countryid==""||$countryid==0)&&($stateid==""||$stateid==0)){ 
    $query="SELECT * FROM state";
  }
  if(($countryid!==""&&$countryid!==0)&&($stateid==""||$stateid==0)){
    $query="SELECT * FROM state where cid=$countryid"; 
  }
  if(($countryid==""||$countryid==0)&&($stateid!==""&&$stateid!==0)){
    $query="SELECT * FROM state where id_no=$stateid";  
  }

  $run=mysql_query($query)or die(mysql_error().'line '.__LINE__);

  $statetotal="";
  $state="";
  $row=array();
  while ($row=mysql_fetch_assoc($run)) {
    # code...
    $statetotal.='<option value="'.$row['id_no'].'">'.$row['state'].'</option>';
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
 
  // Authorisation values required for posting to your personal twitter account.  Replace * with your details but remain inside the ''
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

function briefquery($query,$cline="",$type="",$fforbid=false,$data=array()){
  global $host_conn,$host_connli,$hostname_pvmart,$db,$username_pvmart,$password_pvmart;
  // function only to be used for data retrieval or insertion
  $row=array();
  
  // check if this is a multiline query for mysqli
  $multiline=isset($data['multiline'])&&$data['multiline']!==""?$data['multiline']:false;

  // $rr= 'retrievalrun' this specifies if the current queries data should be pulled 
  // or not.
  $rr=isset($data['rr'])&&$data['rr']!==""?$data['rr']:true;

  $numrows=0;
  $row['resultdata']=array();
  $noroll="run";

  // block any delete or alter query from going through
  if(strpos(strtolower($query), "alter")===true
    ||strpos(strtolower($query), "delete")===true
    ||strpos(strtolower($query), "update")===true
    ||strpos(strtolower($query), "insert")===true){
    $numrows=0;
    $noroll="norun";
    if($fforbid==true){
      // forces the forbidden queries to run
      $noroll="run";
    }
  }
  if($noroll=="run"){
    // use mysql_query if no type is specified
    if($type==""){
      
      $run=mysql_query($query)or die(mysql_error()."Line ".__LINE__." on Line $cline 
        in the calling file. The query is<br>$query");
      $numrows=mysql_num_rows($run);
      if($numrows>1&&strpos(strtolower($query),"insert")===false){
        $rescount=0;
        if($rr==true){
          while ($prow=mysql_fetch_assoc($run)) {
            $row['resultdata'][0]['resultset']="true";
            # code...
            $row['resultdata'][$rescount]=$prow;
            $rescount++;
          }
          
        }else{
          $row['resultdata'][0]['resultset']="null";
        }
      }else if($numrows<2&&strpos(strtolower($query),"insert")===false){
        $prow=mysql_fetch_assoc($run);
        $row['resultdata'][0]=$prow;
      }else if($numrows<2&&strpos(strtolower($query),"insert")===true){
       
      }
    }else if($type=="mysqli"){
      // check to see if a current
      if($multiline==true){
        $run=mysqli_multi_query($host_connli,$query)or die(mysqli_error($host_connli)." Line ".__LINE__." on Line $cline in the calling file. The query is<br>$query");
        
      }else{
        $run=mysqli_query($host_connli,$query)or die(mysqli_error($host_connli)." 
          Line ".__LINE__." on Line $cline in the calling file. The query is<br>$query");
        
      }
      $numrows=0;
      // $row['numrows']=0;
      // echo strpos(strtolower($query),"select");
      if(strpos(strtolower($query),"select")===true||strpos(strtolower($query),"select")!==false||strpos(strtolower($query),"select")>0){
        $numrows=mysqli_num_rows($run);
        // echo "cur rows : $numrows ";
      }

      if($numrows>0&&(strpos(strtolower($query),"select")===true)||strpos(strtolower($query),"select")!==false||strpos(strtolower($query),"select")>0){
        $rescount=0;
        if($rr==true){
          while ($prow=mysqli_fetch_assoc($run)) {
            # code...
            $row['resultdata'][0]['resultset']="true";
            $row['resultdata'][$rescount]=$prow;
            $rescount++;
          }
        }else{

          $row['resultdata'][0]['resultset']="null";

        }
        
        mysqli_free_result($run);
      }
      
      if(strpos(strtolower($query),"insert")===true||strpos(strtolower($query),"insert")!==false||strpos(strtolower($query),"insert")>0){
        $row['resultdata'][0]['id']=mysqli_insert_id($host_connli);
      }      
    }
  }

  $row['outputcount']=$numrows;
  $row['numrows']=$numrows;

  return $row;
}

function paginate($query,$type=""){
  require_once 'paginator.class.php';
  $row=array();
  $pages = new Paginator; 
  if(is_numeric($query)){
    $numrows=$query;
  } 
  // block any delete or alter query from going through
  if(strpos(strtolower($query), "alter")
    ||strpos(strtolower($query), "delete")
    ||strpos(strtolower($query), "update")
    ||strpos(strtolower($query), "alter")){
    $numrows=0;
  }else if(!is_numeric($query)){
    $run=mysql_query($query)or die(mysql_error()."Line ".__LINE__);
    $numrows=mysql_num_rows($run);
  }
  $pages->items_total = $numrows;  
  $pages->mid_range = 9;  
  $pages->pagetype = $type;  
  $pages->paginate();  
  $pages->display_pages();
  $row['limitout']=$pages->limit;
  $query2=$query.$row['limitout'];
  // // // echo$pages;

  $row['outputcount']=$numrows;
  $row['numrows']=$numrows;
  $row['pageout']=$pages->display_pages();
  $row['usercontrols']="<br><span> ".$pages->display_jump_menu()." ".$pages->display_items_per_page()."</span>";
  $row['limit']=$pages->limit;
  $row['num_pages']=$pages->num_pages;
  $row['num_url']=$pages->num_url;
  $row['current_page']=$pages->current_page;
  $row['pagenumbers']=$pages->num_pagenum;
  return $row;
}

function paginatejavascript($query,$type="",$data=array()){
  require_once 'paginator.class.php';
  $pages = new Paginator;  
  if(is_numeric($query)){
    $numrows=$query;
  } 
  // block any delete or alter query from going through
  if(strpos(strtolower($query), "alter")
    ||strpos(strtolower($query), "delete")
    ||strpos(strtolower($query), "update")
    ||strpos(strtolower($query), "alter")){
    $numrows=0;
  }else if(!is_numeric($query)){
    $run=mysql_query($query)or die(mysql_error()."Line ".__LINE__);
    $numrows=mysql_num_rows($run);
  }
  $pages->items_total = $numrows;  
  $pages->mid_range = 9;  
  if(isset($data['ipparr_override'])&&$data['ipparr_override']!==""&&
    is_array($data['ipparr_override'])&&count($data['ipparr_override'])>0){
    $pages->ipparr_override=$data['ipparr_override'];
    // var_dump($data);
  }
  $pages->paginatejavascript($type);  
  $pages->display_pages($type);
  $row['limitout']=$pages->limit;
  $query2=$query.$row['limitout'];
  // // // echo$pages;
  $row=array();

  $row['numrows']=$numrows;
  $row['pageout']=$pages->display_pages($type);
  $row['usercontrols']="<br><span> ".$pages->display_items_per_page_javascript($type)."</span>";
  $row['singlecontrols']=$pages->display_items_per_page_javascript($type);
  $limit=$pages->limit;
  $limit=str_replace("-", "", $limit);
  
  // echo $limit;
  $row['limit']=$limit;
  $row['num_pages']=$pages->num_pages;
  $row['num_url']=$pages->num_url;
  return $row;
}

function paginateCustom($query,$param){
  require_once 'paginator.class.php';
  $pages = new Paginator;  
  if(is_numeric($query)){
    $numrows=$query;
  } 
  // block any delete or alter query from going through
  if(strpos(strtolower($query), "alter")
    ||strpos(strtolower($query), "delete")
    ||strpos(strtolower($query), "update")
    ||strpos(strtolower($query), "alter")){
    $numrows=0;
  }else{
    $run=mysql_query($query)or die(mysql_error()."Line ".__LINE__);
    $numrows=mysql_num_rows($run);
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

function deleteMedia($partid,$fdel=""){
  global $host_addr,$host_target_addr;
  $mediadata=getSingleMediaDataTwo($partid);
  $realpath=".".$mediadata['location']."";
  $medpath=".".$mediadata['medsize']."";
  $thumbpath=".".$mediadata['thumbnail']."";
  $realpaththumb=".".$mediadata['details']."";
  if(file_exists($realpath)&&$realpath!=="."){
      unlink($realpath);
      
  }
  if(file_exists($medpath)&&$medpath!=="."){

      unlink($medpath);

  }
  if(file_exists($thumbpath)&&$thumbpath!=="."){

      unlink($thumbpath);

  }

  genericSingleUpdate("media","status","inactive","id",$partid);
  genericSingleUpdate("media","mainid","0","id",$partid);
  // check to validate content that can be reused later so you dont unset the maintype
  // value
  if($mediadata['maintype']!=="muralimage"){
    genericSingleUpdate("media","maintype","none","id",$partid);
  }
  genericSingleUpdate("media","title","***delete***","id",$partid);
  genericSingleUpdate("media","details","***delete***","id",$partid);
  genericSingleUpdate("media","ownertype","none","id",$partid);
  genericSingleUpdate("media","ownerid","0","id",$partid);
  if($fdel=="delete"){
    // full removal of current entry
  }
  $output="done";
  return $output;
}
/*Converts numbers to english word equivalent*/
function convertNumber($number)
{
    strpos(".",$number)?list($integer, $fraction) = explode(".", (string) $number):list($integer, $fraction)=[$number,0];

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
function checkUrlHttpString($string){
  $sortdata=strpos($string, "http://");
  $sortdatatwo=strpos($string, "https://");
  $row=array();
  $outdata="";
  $mtext="";
  if($sortdata===false){
    $outdata=false;

  }else{
    $outdata=true;
    $mtext="http://";
  }
  if($sortdatatwo===false&&$outdata===false){
    $outdata=false;
  }else{
    $outdata=true;
    $mtext="https://";
  }
  $row['matchdata']=$outdata;
  $row['matchtext']=$mtext;
  return $row;
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

function array_column_recursive(array $haystack, $needle) {
    $found = [];
    array_walk_recursive($haystack, function($value, $key) use (&$found, $needle) {
        if ($key == $needle)
            $found[] = $value;
    });
    return $found;
}

function getSingleLGA($id){
  $query="SELECT * FROM local_govt WHERE id_no=$id";
  $run=mysql_query($query)or die(mysql_error()." Real number:".__LINE__." old number  1579");
  $row=mysql_fetch_assoc($run);
  return $row;
}
function generateMailMarkup($from,$to,$title,$content,$footer,$type){
  include('globalsmodule.php');
  $row=array();
  $phpmailermarkup='';
  $logoout=$host_logo_image;
  $logoalt=$host_website_name;
  $parentheader=$host_website_name;
  $toplstyle="";
  $innerstyle=file_get_contents("$host_addr stylesheets/ink.css");
  if($host_env=="local"){
    $toplstyle='<link rel="stylesheet" href="'.$host_addr.'stylesheets/ink.css">';
     $innerstyle="";
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
                  <img src="'.$logoout.'"  alt="'.$logoalt.'"  style="display: inline-block;" /><br>
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
                  '.$footer.'
                 </td>
                </tr>
                <tr>
                  <td class="footing">
                    &copy; '.$logoalt.' '.date("Y").' Developed by Okebukola Olagoke. Dream Bench Technologies<br>
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
//  
/**
* function to get json error and return,recreates the json_last_error_msg
* function and returns the value for a json action
*
* @return string  returns the error from the last json operation carried out
*
*/
if (!function_exists('json_last_error_msg')) {
  function json_last_error_msg() {
      static $ERRORS = array(
          JSON_ERROR_NONE => 'No error',
          JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
          JSON_ERROR_STATE_MISMATCH => 'State mismatch (invalid or malformed JSON)',
          JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
          JSON_ERROR_SYNTAX => 'Syntax error',
          JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded',
          JSON_ERROR_DEPTH =>'The maximum stack depth has been exceeded.',
          // PHP >= 5.5.0
          JSON_ERROR_RECURSION =>'One or more recursive references in the value to be encoded.',
          // PHP >= 5.5.0
          JSON_ERROR_INF_OR_NAN =>'One or more NAN or INF values in the value to be encoded.',
          JSON_ERROR_UNSUPPORTED_TYPE =>'A value of a type that cannot be encoded was given.'
      );

      $error = json_last_error();
      return isset($ERRORS[$error]) ? $ERRORS[$error] : 'Unknown error';
  }
}
/**
* @author Okebukola Olagoke
* @version 1.0
* The purpose here is to take a normal JSON text string and convert it from 
* normal string to PHP or take a php json object, decode it then return the output
* Example: {widget:{
*                   name:"A widget"
*
*                 }
*           }
* first becomes array("widget"=>array("name"=>"A widget")); then becomes a json strin
*
* @param JSONString/PHPJSONObj/array $string is a valid JSON String/PHPJSONObject of K:V 
* pairs
*
* @return multidimensionalarray $row is an array containing the php encoded json object
* or php decoded json object depending on the value given to the $type parameter
*
*
*/
function JSONtoPHP($string){
  $row=array();
  // verify if the current entry is a string
  // $row['arrayoutput']="";

  if(is_string($string)&&$string!==""){
    // purify the string by removing key elements unnecessary 
    
    /*$search=array("{",
                  "\":\"",
                  "\":{",
                  "}",
                  "[",
                  "]");
    $replace=array("array(",
                    "\"=>\"",
                    "\"=>{",
                    ")",
                    "array(",
                    ")");
    $string=str_replace($search, $replace, $string);
    $nstring='$row[\'arrayoutput\']='.$string.';';*/
    // echo $nstring;
    // eval($nstring);
    $row['arrayoutput']=json_decode($string,true);
    $row['output']=json_encode($row['arrayoutput']);

    $row['error']=json_last_error_msg();
  }elseif(is_array($string)){
    $row['arrayoutput']=$string;
    $row['output']=json_encode($string);
    $row['error']=json_last_error_msg();

  }else{
    // launch a decode on the object
    $row['output']=json_decode($string);
    $row['arrayoutput']=$row['output'];

    $row['error']="parameter one is empty";
  }

  return $row;
}
// this function works with the pagination block, its main purpose is to search
// the 'outputtype' value for preset entries that have multiple concatenated 
// values and single out the true 'type' of the content

function initPaginationTypes($outputtype=""){
  $row=array();
  // create the presets
  $named[0]='generalpages';
  $named[1]='generalinfo';
  $named[2]='testimony';
  $named[3]='subscribers';
  $named[4]='store';
  $named[6]='comments';
  $named[5]='transaction';
  $named[7]='recruitsearch';
  $named[8]='sermonsearch';
  $named[9]='gallerystream';
  $named[10]='adminusers';
  $named[11]='incidents';
  $named[12]='casetransfer';
  $named[13]='cases';
  $named[14]='casereports';
  $named[15]='users';
  $named[16]='advert';
  $type=$outputtype;
  if($outputtype!==""){
    for($i=0;$i<count($named);$i++){
      $nexttype=strpos($outputtype,$named[$i]);
      if($nexttype===0||$nexttype===true||$nexttype>0){
        $type=$named[$i];
        break;
      }
    }
  }
  $row['type']=$type;
  return $row;  
}
// this function returns array of color shortcodes
function parseHighlights($parsecontent=""){
  $row=array();
  $row['span-darkred']['class']="color-darkred";
  $row['span-red']['class']="color-red";
  $row['span-lightred']['class']="color-lightred";
  $row['span-darkorange']['class']="color-darkorange";
  $row['span-orange']['class']="color-orange";
  $row['span-lightorange']['class']="color-ligthorange";
  $row['span-darkyellow']['class']="color-darkyellow";
  $row['span-yellow']['class']="color-yellow";
  $row['span-lightyellow']['class']="color-lightyellow";
  $row['span-darkgreen']['class']="color-darkgreen";
  $row['span-green']['class']="color-green";
  $row['span-lightgreen']['class']="color-lightgreen";
  $row['span-darkblue']['class']="color-darkblue";
  $row['span-blue']['class']="color-blue";
  $row['span-lightblue']['class']="color-lightblue";
  $row['span-darkindigo']['class']="color-darkindigo";
  $row['span-indigo']['class']="color-indigo";
  $row['span-lightindigo']['class']="color-lightindigo";
  $row['span-darkviolet']['class']="color-darkviolet";
  $row['span-violet']['class']="color-violet";
  $row['span-lightviolet']['class']="color-lightviolet";
  $row['span-ts-red']['class']="text-shadow-color-red";
  $row['span-ts-orange']['class']="text-shadow-color-orange";
  $row['span-ts-yellow']['class']="text-shadow-color-yellow";
  $row['span-ts-green']['class']="text-shadow-color-green";
  $row['span-ts-blue']['class']="text-shadow-color-blue";
  $row['span-ts-indigo']['class']="text-shadow-color-indigo";
  $row['span-ts-violet']['class']="text-shadow-color-violet";

  return $row;
}

// function to get all current HTTP in a page and return them 
// and their values
// 'emulate_getallheaders'
// similar to the php 'getallheaders' function
function emu_getallheaders() { 
  $row=array();
  $headers=array();
  $headernames=array();
  foreach ($_SERVER as $name => $value) { 
    if (substr($name, 0, 5) == 'HTTP_') { 
       $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))); 
       $headers[$name] = $value; 
       $headernames[]=$name;
    } else if ($name == "CONTENT_TYPE") { 
       $headers["Content-Type"] = $value; 
       $headernames[]='Content-Type';
    } else if ($name == "CONTENT_LENGTH") { 
       $headers["Content-Length"] = $value; 
       $headernames[]='Content-Length';
    } 
  } 
  $row=$headers;
  return $row; 
} 

// this is a function aimed at easing the validation of
// password_hashed passwords
function passwordTest($pwrd,$hash){
  $row=array();
  $matched="invalid";
  if($hash!==""&&$pwrd!==""){
    if (password_verify(''.$pwrd.'', $hash)) {
      $matched="valid";
    }
  }
  $row['matched']=$matched;
  return $row;
}

function isValidMD5($md5 ='')
{
    return preg_match('/^[a-f0-9]{32}$/', $md5);
}
/**
* @see to toPeriod
* 
* This converts the entry date to a time period in days that have gone past or 
* ahead of it
* @param $date datetime, in the format YYYY-MM-DD HH:MM:SS or Y-m-d H:i:s in php
* 
* @param $data array, used for passing in options to the function
* 
* @return $row array.
*/
function toPeriod($date,$data=array()){
  $row=array();
  $date1 = new DateTime("$date");
  $date2 = new DateTime(date("Y-m-d H:i:s"));
  $dt=" ago";
  if($date1>$date2){
    $dt=" From now";
    $date=$date2;
    $date2=$date1;
    $date1=$date;
  }
  /*date formats are 
    '%y Year 
    '%m Month
    '%d Day
    '%i Minute' 
    '%s Seconds'  
    '%h Hours'  
    '%a Day'
    '%r representing sign "-" when negative diff and " " when positive
    try %R to show "+" sign also for positive  
  */
  // day diff

  $diffy = $date2->diff($date1)->format("%y");
  $diffm = $date2->diff($date1)->format("%m");
  $diffd = $date2->diff($date1)->format("%a");
  $diffh = $date2->diff($date1)->format("%h");
  $diffi = $date2->diff($date1)->format("%i");
  $diffs = $date2->diff($date1)->format("%s");

  $row['diffy']=$diffy;
  $row['diffm']=$diffm;
  $row['diffd']=$diffd;
  $row['diffh']=$diffh;
  $row['diffmin']=$diffi;
  $row['diffs']=$diffs;

  // create the preferred output
  $potext="";
  $poaddtext="";
  $povalue=0;
  if($diffy>0){
    $potext=$diffy==1?"year":"years";
    $povalue=$diffy;
    $poaddtext=$diffh==1?"last $potext":"$povalue $potext $dt";
  }else if($diffm>0){
    $potext=$diffm==1?"month":"months";
    $povalue=$diffm;    
    $poaddtext=$diffm==1?"last $potext":"$povalue $potext $dt";
  }else if($diffd>0){
    $potext=$diffd==1?"day":"days";
    $povalue=$diffd;    
    $poaddtext=$diffd==1?"yester$potext":"$povalue $potext $dt";
  }else if($diffh>0){
    $potext=$diffh==1?"hour":"hours";
    $povalue=$diffh;    
    $poaddtext=$diffh==1?"an $potext $dt":"$povalue $potext $dt";
  }else if($diffi>0){
    $potext=$diffi==1?"minute":"minutes";
    $povalue=$diffi;    
    $poaddtext=$diffi==1?"a $potext $dt":"$povalue $potext $dt";
  }else if($diffs>0){
    $potext=$diffs==1?"second":"seconds";
    $povalue=$diffs;    
    $poaddtext=$diffs==1?"a few $potext $dt":"$povalue $potext $dt";
  }
  $row['potext']=$potext.$dt;
  $row['povalue']=$povalue;
  $row['poaddtext']=$poaddtext;

  return $row;
}
?>