<?php
	// default html email format
	// $type=isset($type)&&$type!==""?$type:"blog";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 <title><?php echo stripslashes($outs['title'])?></title>
 <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0; background-color:#550101;font-family: \'Microsoft Tai Le\';">
 <table border="" style="border:0px;" cellpadding="0" cellspacing="0" width="100%">
 <tr>
 <td align="center" style="text-align: center;border:0px; font-size: 32px;color: #FFA500;">
 <img src="<?php echo $host_default_cover_image;?>" alt="<?php echo $host_website_name;?>" style="display: inline-block;max-width:580px;"/><br>
 A new post in: <?php echo $blogtypename;?>.
 </td>
 <table align="center" border="" cellpadding="0" cellspacing="0" style="max-width:580px;">
 <tr>
 <td style="padding: 40px 30px 40px 30px;background-color:#fefefe; color:#1a1a1a;">
 <table border="" cellpadding="0" cellspacing="0" width="100%">
 <tr>
 <td style="font-size: 22px;text-align: center;color:#B800FF;border: 0px;border-bottom: 1px solid #979797;">
 <?php echo stripslashes($outs['title']);?>
 </td>
 </tr>
 <tr>
 <td style="font-size:13px;border: 0px;border-bottom: 1px solid #979797;">
 <img src="<?php echo $coverphoto;?>" height="112px"style="float:left;"/>'.stripslashes($outs['introparagraph']);?>
 </td>
 </tr>
 <tr>
 <td style="border: 0px;border-bottom: 1px solid #979797;font-size: 12px;">
 Posted under <?php echo $blogcatname;?>, on <?php echo $outs['entrydate'];?> 
<a href="<?php echo $host_addr;?>blog/?p=<?php echo $outs['id'];?>" target="_blank" title="Continue reading this post">Continue Reading</a>
 </td>
 </tr>
 </table>
 </td>
 </tr>
 <tr>
 <td style="text-align:center; font-size:13px;background: #2E0505;color: #FFAD00;">
&copy; <?php echo $host_website_name;?> <?php echo $y;?> Developed by Okebukola Olagoke.<br>
<a href="<?php echo $host_addr;?>unsubscribe.php?t=1&tp=<?php echo $blogtypeid;?>" style="color: #FD9D9D;">Unsubscribe</a>
 </td>
 </tr>
</table>
 </tr>
 </table>
</body>
</html>