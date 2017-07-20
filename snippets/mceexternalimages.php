<?php // this must be the very first line in your PHP file!
include("connection.php");

$output = ''; // Here we buffer the JavaScript code we want to send to the browser.
$output .= 'var tinyMCEImageList = new Array(';
$directory = "../media"; // Use your correct (relative!) path here
// Since TinyMCE3.x you need absolute image paths in the list...
$abspath = $host_addr;
$files2=array();
$dhandle2= opendir($directory);
if($dhandle2){
while(false !== ($fname2 = readdir($dhandle2))){
if(($fname2!='.') && ($fname2!='..') && ($fname2!= basename($_SERVER['PHP_SELF']))){
$files2[]=(is_dir("./$fname2")) ? "(Dir) {$fname2}":$fname2;
}
}
foreach($files2 as $fname2){
$extensions=getExtension($fname2);
$extension=strtolower($extensions);
//get file extension and make sure its an image
if($extension=="jpg"||$extension=="jpeg"||$extension=="png"||$extension=="gif"){
    //produce the image in display format for page
                $output .= '["'
                    . utf8_encode($fname2)
                    . '", "'
                    . utf8_encode("$abspath/$directory/$fname2")
                    . '"],';
}
}
    closedir($dhandle2);

}
    $output = substr($output, 0, -1); // remove last comma from array item list (breaks some browsers)


// Finish code: end of array definition. Now we have the JavaScript code ready!
$output .= ');';

// Make output a real JavaScript file!
header('Content-type: text/javascript'); // browser will now recognize the file as a valid JS file

// prevent browser from caching
header('pragma: no-cache');
header('expires: 0'); // i.e. contents have already expired

// Now we can send data to the browser because all headers have been set!
echo $output;

?>