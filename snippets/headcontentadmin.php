<?php
    include('headcontentparentdefault.php');
    // echo $mpage
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php echo $mpagetitle;?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta http-equiv="Content-Type" content="text/html;"/>
    <meta name="keywords" content="<?php echo $mpagekeywords;?>"/>
    <meta name="description" content="<?php echo $mpagedescription;?>">
    <meta name="author" content="<?php echo $mpageauthor;?>">
    <meta http-equiv="Content-Type" content="text/html;"/>
    <meta property="fb:app_id" content="<?php echo $mpagefbappid;?>"/>
    <meta property="fb:admins" content="<?php echo $mpagefbadmins;?>"/>
    <meta property="og:locale" content="<?php echo $mpagefbadmins;?>">
    <meta property="og:type" content="website"/>
    <meta property="og:image" content="<?php echo $mpageimage;?>"/>
    <meta property="og:title" content="<?php echo $mpagetitle;?>"/>
    <meta property="og:description" content="<?php echo $mpagedescription;?>"/>
    <meta property="og:url" content="<?php echo $mpageurl;?>"/>
    <meta property="og:site_name" content="<?php echo $mpagesitename;?>"/>
    <link rel="canonical" async href="<?php echo $mpageurl;?>"/>
    <link rel="shortcut icon" href="<?php echo $mpageicon;?>"/>
    <?php 
        echo $mpageheadtagextras;

        include('themestylesdumpadmin.php');
        if(isset($mpagestylesextras)){
            echo $mpagestylesextras;
        }
        if(isset($mpagelibscriptextras)){
            echo $mpagelibscriptextras;
        }
    ?>
    
  </head>