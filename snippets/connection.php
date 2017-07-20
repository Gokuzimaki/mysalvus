<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
require_once 'paginator.class.php';
require_once "gifresizer.class.php";    //Including our class $host_addr="http://localhost/muyiwasblog/";
require_once 'html2text.class.php';
require_once('php_image_magician.php');
require_once "SocialAutoPoster/SocialAutoPoster.php";
// require_once('tmhOAuth-master/tmhOAuth.php');
require 'PHPMailer-master/PHPMailerAutoload.php';
date_default_timezone_set("Africa/Lagos");
$host_target_addr="http://".$_SERVER['HTTP_HOST']."/";
$host_addr="http://localhost/mysalvus/";
$host_email_addr="no-reply@homonculus.com";
$host_keywords="";
$host_env="local";
$host_blog_catpost_max=6;
// setup smtp default values for sending blog emails out
// via the server provided details
$host_smtp="relay-hosting.secureserver.net";
$host_smtp_email="no-reply@dreambench.com";
$host_smtp_username="no-reply@dreambench.com";
$host_smtp_pwrd="noreply";

// filemanager for blog upload section
$host_website_name="MySalvus ";  //variable for holding filemanager header title
$host_default_blog_name="MySalvus Blog";  //variable for holding filemanager header title
$host_admin_title_name="MySalvus Admin";  //variable for holding filemanager header title
$host_title_name="MySalvus";  //variable for holding default name of the site owner
$host_relative_upload_dir='/mysalvus/media/multimedia/'; //variable for holding the host default filemanager upload dir
$host_current_path='../../media/multimedia/'; // relative path to the fileupload section  
$host_thumbs_base_path='../../media/thumbs/';
/*Social block*/
// links to social address
$host_social_instagram='##';

// plain file manager config variables for website media
$host_media_content_dir='';
// basic global variable for controlling redirects due to multiple administrators
// $rurladmin="nourl";
//set to true on upload
$host_email_send=false;
$hostname_pvmart = "localhost";
$db = "mysalvus";
$username_pvmart = "root";
//change pword when uploading to server
$password_pvmart = "";
// &#8358; - naira symbol
/*controlblock*/
if(strpos($host_target_addr, "localhost/")){
  // for local server
  $host_addr="http://localhost/mysalvus/";
}else if(strpos($host_target_addr, "ngrok.io/mysalvus")){
  $host_test="ngrok1";
  $host_addr=$host_target_addr!=="http://"&&$host_target_addr!=="https://"?$host_target_addr."":"http://mysalvus.com/";
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header('Access-Control-Allow-Credentials: true');
  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
          header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
          header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

      exit(0);
  }
}else if(strpos($host_target_addr, "ngrok.io")){
  $host_test="ngrok2";
  $host_addr=$host_target_addr!=="http://"&&$host_target_addr!=="https://"?$host_target_addr."mysalvus/":"http://mysalvus.com/";
  header("X-Developed-By: 'Okebukola Olagoke'");         
  header("Access-Control-Allow-Origin: *");
  header('Access-Control-Allow-Credentials: true');
  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
          header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
          header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

      exit(0);
  }
}else if(strpos($host_target_addr, "ngrok")){
  $host_test="ngrok3";
  $host_addr=$host_target_addr!=="http://"&&$host_target_addr!=="https://"?$host_target_addr."mysalvus/":"http://mysalvus.com/";
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header('Access-Control-Allow-Credentials: true');
  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
          header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
          header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
      exit(0);
  }

}else{
  // header("Access-Control-Allow-Origin: *");
  $host_env="online";
  $host_relative_upload_dir='/media/multimedia/'; //variable for holding the host default filemanager upload dir
  $host_current_path='../../media/multimedia/'; // relative path to the fileupload section  
  $host_thumbs_base_path='../../media/thumbs/';
  // for remote server
  $host_addr=$_SERVER['HTTP_HOST']!==""&&isset($_SERVER['HTTP_HOST'])?$host_target_addr:"http://mysalvus.org/";
  $host_email_send=true;
  $hostname_pvmart = "localhost";
  if(strpos($host_target_addr, "mysalvus.hynstein.com")){
    // for the test server
    $db = "hynstlec_mysalvus";
    $username_pvmart = "hynstlec_salvusr";
  }else{
    // for the real server
    $db = "mysalvus_mysalvus";
    $username_pvmart = "mysalvus_salvusr";
  }
  //change pword when uploading to server
  $password_pvmart = 'Admin!p@55';
}
// echo $host_test;
$host_servertype="windows";
// check if server is windows or linux
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $host_servertype="windows";
} else {
    $host_servertype="linux";
}
// background: #ECE9E6;  /* fallback for old browsers */
// background: -webkit-linear-gradient(to right, #FFFFFF, #ECE9E6);  /* Chrome 10-25, Safari 5.1-6 */
// background: linear-gradient(to right, #FFFFFF, #ECE9E6); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
if($host_servertype=="windows"){
  $host_tpathplain=dirname(__FILE__).'\\';
  $host_tpath=dirname(__FILE__)."\..\\";
  
}else{
  $host_tpathplain=dirname(__FILE__)."/";
  $host_tpath=dirname(__FILE__)."/../";
}
$host_logo_image=$host_addr."images/site_logo.png";
$host_logo_imagemidi=$host_addr."images/site_logo_midi.png";
$host_logo_imagemini=$host_addr."images/site_logo_mini.png";
$host_favicon='<link rel="shortcut icon" href="'.$host_addr.'images/favicon.ico" />';
$host_default_cover_image=$host_addr."images/fvtlogo2.jpg";
$logpart=md5($host_addr);
// default counter variable for mini content, e.g blog displays in sidebars etc
$host_minipagecount=5;
// mysql_connect
$host_conn = @mysql_connect($hostname_pvmart, $username_pvmart, $password_pvmart) or die(mysql_error());
mysql_select_db($db) or die ("Unable to select database!");
// mysqli_connect
$host_connli = mysqli_connect($hostname_pvmart, $username_pvmart, $password_pvmart, $db)or die(mysqli_error());

include_once('utilities.php');
include_once('Gravatar.php');
include_once('adminusersmodule.php');
include('usermodule.php');
include('incidentsmodule.php');
include('casemodule.php');
include_once('storemodule.php');
include_once('generaldatamodule.php');
// include_once('blogpagemodule.php');
// include_once('modules/shortcodes.php');
include_once('modules/blogpagemoduleadvanced.php');

// include_once('modules/notificationsmodule.php');
include('notificationsmodule.php');
include_once('homebannermodule.php');
include_once('managementteammodule.php');
include_once('clientandrecommendationmodule.php');
include_once('eformparsermodule.php');
include_once('contactsmodule.php');
include_once('eventsmodule.php');
include_once('gallerymodule.php');
include_once('faqmodule.php');



?>