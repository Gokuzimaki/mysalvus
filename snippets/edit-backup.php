<?php
include('connection.php');
require_once 'html2text.class.php';
$entryvariant=mysql_real_escape_string($_POST['entryvariant']);
$rurladmin=isset($_SESSION['rurladmin'])?$_SESSION['rurladmin']:"";
$entryid=$_POST['entryid'];
$userrequest="";
if(isset($_POST['userrequest'])&&$_POST['userrequest']!==""){
	$userrequest=$_POST['userrequest'];
}
if($entryvariant=="editbooking"){

}elseif ($entryvariant=="editblogtype") {
	# code...
	$outs=getSingleBlogType($entryid);	
	$blogname=mysql_real_escape_string($_POST['name']);
	$blogdescription=mysql_real_escape_string($_POST['description']);
	$rssname=$outs['rssname'];
	$pattern2='/[\n\$!#\%\^<>@\(\),\'.\"\/\%\*\{\}\&\[\]\?\_\-\+\=:;’]/';
	$pagename=preg_replace($pattern2,"", $blogname);
	$pattern='/[\s]/';
	$pagename=preg_replace($pattern,"-", $pagename);
	$pagename=stripslashes($pagename);
	$foldername=$pagename;
	if($blogname!==""){
	genericSingleUpdate("blogtype","name",$blogname,"id",$entryid);
	genericSingleUpdate("blogtype","foldername",$foldername,"id",$entryid);
	genericSingleUpdate("blogtype","rssname",$rssname,"id",$entryid);
	// $pattern='/[\s]/';
	$pattern='/[\n\$!#\%\^<>@\(\),\'.\"\/\%\*\{\}\&\[\]\?\_\s\-\+\=:;’]/';
	$rssname=preg_replace($pattern,"", $blogname);
	$rssname=strtolower($rssname);
	$targetfeed="../feeds/rss/".$rssname.".xml";
	rename("../feeds/rss/".$outs['rssname'].".xml","../feeds/rss/".$rssname.".xml");
	/*uodateblog posts with new folder name info*/
	// $popquery="SELECT * FROM blogentries WHERE status='active' order by views desc LIMIT 0,5";
	// $poprun=mysql_query($popquery)or die(mysql_error()." Line 1835");
	// while($poprow=mysql_fetch_assoc($poprun)){
	// $outpop=getSingleBlogEntry($poprow['id']);
	// $popular.=$outpop['blogminioutput'];
	// }
	/*end*/
	if($entryid==1){
	$title='Frankly Speaking | Muyiwa Afolabi\'s Website';
	$page='blog.php';
	}elseif ($entryid==2) {
		# code...
	$title='Christ Society International Outreach | Muyiwa Afolabi\'s Website';
	$page='csi.php';
	}elseif ($entryid==3) {
		# code...
	$title='Frankly Speaking Africa | Muyiwa Afolabi\'s Website';
	$page='franklyspeakingafrica.php';
	}else{
		$title=''.$blogname.' | Muyiwa Afolabi\'s Website';

		$page=''.$blogname.'.php';
	}
	$title=mysql_real_escape_string($title);
	$landingpage=$host_target_addr.$page;
	$rssheader='<?xml version="1.0" encoding="utf-8"?>
	<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
	<channel>
	<title>'.$title.'</title>
	<atom:link href="'.$host_target_addr.'feeds/rss/'.$rssname.'.xml" rel="self" type="application/rss+xml"/>
	<description>
	'.$outs['description'].'
	</description>
	<link>'.$landingpage.'</link>
	';
	genericSingleUpdate("rssheaders","headerdetails",$rssheader,"blogtypeid",$entryid);
	rename("../blog/".$outs['foldername']."","../blog/".$foldername."");
		}
	//if the blogs description has changed
	if($blogdescription!==""&&$blogdescription!==$outs['description']){
	if($entryid==1){
	$title='Frankly Speaking With Muyiwa Afolabi | Muyiwa Afolabi\'s Website';
	$page='blog.php';
	}elseif ($entryid==2) {
		# code...
	$title='Christ Society International Outreach | Muyiwa Afolabi\'s Website';
	$page='csi.php';
	}elseif ($entryid==3) {
		# code...
	$title='Frankly Speaking Africa | Muyiwa Afolabi\'s Website';
	$page='franklyspeakingafrica.php';
	}else{
		$title=''.$outs['name'].' | Muyiwa Afolabi\'s Website';
		$page=''.$outs['name'].'.php';
	}
	$landingpage=$host_target_addr.$page;
	$title=mysql_real_escape_string($title);
	$blogdescription=mysql_real_escape_string($blogdescription);
	$rssheader='<?xml version="1.0" encoding="utf-8"?>
	<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
	<channel>
	<title>'.$title.'</title>
	<atom:link href="'.$host_target_addr.'feeds/rss/'.$rssname.'.xml" rel="self" type="application/rss+xml"/>
	<description>
	'.$blogdescription.'
	</description>
	<link>'.$landingpage.'</link>
	';
	genericSingleUpdate("rssheaders","headerdetails",$rssheader,"blogtypeid",$entryid);
	}
	$status=$_POST['status'];
	genericSingleUpdate("blogtype","status",$status,"id",$entryid);
	writeRssData($entryid,0);

	if($rurladmin==""){
		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');
	}
}elseif ($entryvariant=="editblogcategory") {
	$outs=getSingleBlogCategory($entryid);
	$blogid=$outs['blogtypeid'];
	$outstwo=getSingleBlogType($outs['blogtypeid']);
	$blogcategoryname=mysql_real_escape_string($_POST['name']);
	$title=''.$outstwo['name'].' | Muyiwa Afolabi\'s Website';
	$title=mysql_real_escape_string($title);
	/*if(!file_exists("".$host_addr."feeds/rss/".$outs['rssname'].".xml")){

		$query2="INSERT INTO rssheaders (blogtypeid,blogcatid,headerdetails,footerdetails)VALUES('$blogid','$entryid','$rssheader','$rssfooter')";
		$run2=mysql_query($query2)or die(mysql_error()."Line 116");
	}*/
	if($blogcategoryname!==""&&$blogcategoryname!==$outs['catname']){
		$landingpage=$host_target_addr."category.php?cid=".$entryid;
		$pattern2='/[\n\s\$!#\%\^<>@\(\),\'\"\/\%\*\{\}\&\[\]\?\_\-\+\=:;’]/';
		$rssname=preg_replace($pattern2,"", $blogcategoryname);
		$rssname=$outs['blogtypeid'].strtolower($rssname);
		$rssname=mysql_real_escape_string($rssname);	

	$rssheader='<?xml version="1.0" encoding="utf-8"?>
	<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
	<channel>
	<title>'.$title.'</title>
	<atom:link href="'.$host_target_addr.'feeds/rss/'.$rssname.'.xml" rel="self" type="application/rss+xml"/>
	<description>
	Category: '.mysql_real_escape_string($outs['catname']).'
	</description>
	<link>'.$landingpage.'</link>
	';
	$rsstest=stripslashes($rssname);
	echo $rssname."<br>";
	echo $outs['catname']."<br>";
	rename("../feeds/rss/".$outs['rssname'].".xml","../feeds/rss/".$rssname.".xml");
	/*$rssheader='
	<?xml version="1.0" encoding="utf-8"?>
	<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
	<channel>
	<title>'.$title.'</title>
	<atom:link href="'.$host_target_addr.'feeds/rss/'.$rssname.'.xml" rel="self" type="application/rss+xml"/>
	<description>
	Category: '.$blogcategoryname.'
	</description>
	<link>'.$landingpage.'</link>
	';*/
		genericSingleUpdate("rssheaders","headerdetails",$rssheader,"blogcatid",$entryid);
		genericSingleUpdate("blogcategories","rssname",$rssname,"id",$entryid);
		genericSingleUpdate("blogcategories","catname",$blogcategoryname,"id",$entryid);
		writeRssData(0,$entryid);
	}
	$status=mysql_real_escape_string($_POST['status']);
	genericSingleUpdate("blogcategories","status",$status,"id",$entryid);

	if($outstwo['name']=="Project Fix Nigeria"){
	$subtext=mysql_real_escape_string($_POST['subtext']);
	genericSingleUpdate("blogcategories","subtext",$subtext,"id",$entryid);
	$prevpic=$outs['profpic'];
	$imgid=$outs['profpicid'];
	$realprevpic=".".$prevpic;
	$profpic=$_FILES['profpic']['tmp_name'];
	if($_FILES['profpic']['tmp_name']!==""){
	$image="profpic";
	$imgpath[]='../images/categoryimages/';
	$imgsize[]="default";
	$acceptedsize="";
	$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
	$len=strlen($imgouts[0]);
	$imagepath=substr($imgouts[0], 1,$len);
	// get image size details
	list($width,$height)=getimagesize($imgouts[0]);
	$imagesize=$_FILES[''.$image.'']['size'];
	$filesize=$imagesize/1024;
	//echo $filefirstsize;
	$filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
	if(strlen($filesize)>3){
	$filesize=$filesize/1024;
	$filesize=round($filesize,2);	
	$filesize="".$filesize."MB";

	}else{
		$filesize="".$filesize."KB";
	}
	genericSingleUpdate("media","location",$imagepath,"id",$imgid);
	genericSingleUpdate("media","filesize",$filesize,"id",$imgid);
	genericSingleUpdate("media","width",$width,"id",$imgid);
	genericSingleUpdate("media","height",$height,"id",$imgid);
	if(file_exists($realprevpic)){
	unlink($realprevpic);
	}
	}

	}
	if($rurladmin==""){

		if($rurladmin==""){

		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');

	}
	}else{
		header('location:'.$rurladmin.'');

	}

}elseif ($entryvariant=="editblogpost") {
	# code...
	$outs=getSingleBlogEntry($entryid);
	$introparagraph=mysql_real_escape_string($_POST['introparagraph']);
	$blogentry=mysql_real_escape_string($_POST['blogentry']);
	$blogentrytype=mysql_real_escape_string($_POST['blogentrytype']);
	// echo $blogentrytype."<br>";
	$title=mysql_real_escape_string($_POST['title']);
	$status=mysql_real_escape_string($_POST['status']);
	$coverstyle=mysql_real_escape_string($_POST['coverstyle']);
	genericSingleUpdate("blogentries","coverphotoset",$coverstyle,"id",$entryid);
	$newpath="";
	$modified="";
	/*	echo $title."<br>";
		echo $introparagraph."<br>";
		echo $blogentry."<br>";
		echo $outs['title']."<br>";
		echo $outs['introparagraph']."<br>";*/
		stripslashes($introparagraph)== $outs['title']? $echo="true":$echo="false";
		// echo $echo;
		$pattern2='/[\n\$!#\%\^<>@\(\),\'\"\/\%\*\{\}\&\[\]\?\_\-\+\=:;’]/';
		$pagename=preg_replace($pattern2,"", $_POST['title']);
		$pattern='/[\s]/';
		$pagename=preg_replace($pattern,"-", $pagename);
		$pagename=stripslashes($pagename);
		if(stripslashes($title)!==$outs['title']){
			// genericSingleUpdate("blogentries","title",$title,"id",$entryid);
			$oldpath="".$outs['rellink']."";
			$newpath="".$outs['reldirectory']."".$pagename.".php";
			echo $oldpath."<br>";
			echo $newpath."<br>";
			rename(".".$outs['rellink']."",".".$outs['reldirectory']."".$pagename.".php");
			$pagename=mysql_real_escape_string($pagename);
			genericSingleUpdate("blogentries","title",$title,"id",$entryid);
			genericSingleUpdate("blogentries","pagename",$pagename,"id",$entryid);
			$modified="yes";

		}
		 if (stripslashes($blogentry)!==$outs['blogpost']) {
			# code...
			genericSingleUpdate("blogentries","blogpost",$blogentry,"id",$entryid);
			$modified="yes";

		}
		 if(stripslashes($introparagraph)!==$outs['introparagraph']){
			genericSingleUpdate("blogentries","introparagraph",$introparagraph,"id",$entryid);
				// echo "in here";
			$modified="yes";

		}
	if($blogentrytype=="banner"){
		$bannerpic=$_FILES['bannerpic']['tmp_name'];
		$realprevpic=$outs['bannermain'];
		$realprevpicthumb=$outs['bannerthumb'];
		genericSingleUpdate("blogentries","introparagraph",$title,"id",$entryid);

		if($_FILES['bannerpic']['tmp_name']!==""){
			$bannerid=$_POST['bannerid'];
			$image="bannerpic";
			$imgpath[0]='../files/';
			$imgpath[1]='../files/thumbnails';
			$imgsize[0]="default";
			$imgsize[1]="660,";
			$acceptedsize="";
			$imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
			$len=strlen($imgouts[0]);
			$imagepath=substr($imgouts[0], 1,$len);
			$len2=strlen($imgouts[1]);
			$imagepath2=substr($imgouts[1], 1,$len2);
			// get image size details
			$filedata=getFileDetails($imgouts[0],"image");
			$filesize=$filedata['size'];
			$width=$filedata['width'];
			$height=$filedata['height'];
			// echo '<img src="'.$imgouts[0].'"> '.$filesize.' '.$width.' '.$height.'';
			// get the cover photo's media table id for storage with the blog entry
			genericSingleUpdate("media","location",$imagepath,"id",$bannerid);
			genericSingleUpdate("media","details",$imagepath2,"id",$bannerid);
			genericSingleUpdate("media","filesize",$filesize,"id",$bannerid);
			genericSingleUpdate("media","width",$width,"id",$bannerid);
			genericSingleUpdate("media","height",$height,"id",$bannerid);	
			if(file_exists($realprevpic)){
				unlink($realprevpic);
			}
			if(file_exists($realprevpicthumb)){
				unlink($realprevpicthumb);
			}
			$modified="yes";

		}
	}

	if($blogentrytype=="gallery"){
		// for csi
		if($outs['blogtypeid']==2){
			genericSingleUpdate("blogentries","introparagraph",$introparagraph,"id",$entryid);

		}else{
			genericSingleUpdate("blogentries","introparagraph",$title,"id",$entryid);

		}
		genericSingleUpdate("blogentries","introparagraph",$title,"id",$entryid);

		$piccount=$_POST['piccount'];
		//echo $piccount;
		if($piccount>0){
			for($i=1;$i<=$piccount;$i++){
				$albumpic=$_FILES['defaultpic'.$i.'']['tmp_name'];
				if($albumpic!==""){
				$image="defaultpic".$i."";
				if(isset($imagepath)){
				unset($imagepath);
				unset($imagesize);
				}
				$imagepath=array();
				$imagesize=array();
				$imgpath[0]='../files/medsizes/';
				$imgpath[1]='../files/thumbnails/';
				$imgsize[0]="default";
				$imgsize[1]=",225";
				// echo count($imgsize);
				$acceptedsize="";
				$imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
				$len=strlen($imgouts[0]);
				// echo $imgouts[0]."<br>";
				$imagepath=substr($imgouts[0], 1,$len);
				$len2=strlen($imgouts[1]);
				// echo $imgouts[1]."<br>";
				$imagepath2=substr($imgouts[1], 1,$len2);
				// get image size details
				$filedata=getFileDetails($imgouts[0],"image");
				$filesize=$filedata['size'];
				$width=$filedata['width'];
				$height=$filedata['height'];
				// insert current blog gallery content into database as original image and thumbnail
				$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,details,filesize,width,height)VALUES('$entryid','blogentry','gallerypic','image','$imagepath','$imagepath2','$filesize','$width','$height')";
				$mediarun=mysql_query($mediaquery)or die(mysql_error());
				$modified="yes";
				}
			}

		}
	}

	//change the cover photo of a new one is available
	$profpic=$_FILES['profpic']['tmp_name'];
	$realprevpic=$outs['absolutecover'];
	if($_FILES['profpic']['tmp_name']!==""){
		$image="profpic";
		$imgpath[]='../files/';
		$imgsize[]="default";
		$acceptedsize=",200";
		$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
		$len=strlen($imgouts[0]);
		$imagepath=substr($imgouts[0], 1,$len);
		// get image size details
		$filedata=getFileDetails($imgouts[0],"image");
		$filesize=$filedata['size'];
		$width=$filedata['width'];
		$height=$filedata['height'];
		if($outs['coverphoto']>0){
			genericSingleUpdate("media","location",$imagepath,"id",$outs['coverphoto']);
			genericSingleUpdate("media","filesize",$filesize,"id",$outs['coverphoto']);
			genericSingleUpdate("media","width",$width,"id",$outs['coverphoto']);
			genericSingleUpdate("media","height",$height,"id",$outs['coverphoto']);	
		}else{
			$coverid=getNextId("media");
			$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize,width,height)VALUES('$entryid','blogentry','coverphoto','image','$imagepath','$filesize','$width','$height')";
			$mediarun=mysql_query($mediaquery)or die(mysql_error());
			genericSingleUpdate("blogentries","coverid",$coverid,"id",$entryid);
		}
		if(file_exists($realprevpic)){
			unlink($realprevpic);
		}
		$modified="yes";
	}
	$pagepath=".".$outs['reldirectory']."".$pagename.".php";
	$handle = fopen($pagepath, 'w') or die('Cannot open file:  '.$pagepath);
	//set the new page up
	$pagesetup = '<?php 
	session_start();
	include(\'../../snippets/connection.php\');
	$outpage=blogPageCreate('.$entryid.');
	 echo $outpage[\'outputpageone\'];
	$blogdata=getSingleBlogEntry('.$entryid.');
	$newview=$blogdata[\'views\']+1;
	genericSingleUpdate("blogentries","views",$newview,"id",'.$entryid.');
	?>
	';
	fwrite($handle, $pagesetup);
	fclose($handle);
	if($modified=="yes"){
	$outs2=getSingleBlogEntry($entryid);
	$datetime= "".date("D, d M Y H:i:s")." +0100";
	if($outs['feeddate']==""){
	$feedout=$datetime;
	genericSingleUpdate("blogentries","feeddate",$feedout,"id",$entryid);
	}else{
	$feedout=$outs['feeddate'];
	}
	$rssentry='<item>
	<title>'.$outs2['title'].'</title>
	<link>'.$outs2['pagelink'].'</link>
	<pubDate>'.$feedout.'</pubDate>
	<guid isPermaLink="false">'.$host_addr.'blog/?p='.$entryid.'</guid>
	<description>
	<![CDATA['.$outs2['introparagraph'].']]>
	</description>
	</item>
	';
	// echo'in here';
	$rssentry=mysql_real_escape_string($rssentry);
	//update feed database
	genericSingleUpdate("rssentries","rssentry",$rssentry,"blogentryid",$entryid);
	//update feeds
	writeRssData($outs2['blogtypeid'],0);
	writeRssData(0,$outs2['blogcatid']);
	}
	if($_FILES['profpic']['tmp_name']!==""){
	$modified="yes";
	}
	$modifydate=date("d, F Y H:i:s");
	//update last modified date here of any changes occured
	$modified=="yes"?genericSingleUpdate("blogentries","modifydate",$modifydate,"id",$entryid):$modifydate="";
	genericSingleUpdate("blogentries","status",$status,"id",$entryid);
	if($rurladmin==""){

		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');

	}
}elseif($entryvariant=="unsubscribeblogtype"){
	$email=mysql_real_escape_string($_POST['email']);
		$typeid=$_POST['typeid'];
	$query="SELECT * FROM subscriptionlist WHERE email='$email' AND blogtypeid=$typeid";
		$run=mysql_query($query)or die(mysql_error()." line 301");
		$numrows=mysql_num_rows($run);
		if($numrows>0){
	$entryid=$row['id'];
	genericSingleUpdate("subscriptionlist","status","inactive",'id',$entryid);
		}
	header('location:../index.php');
}elseif($entryvariant=="unsubscribeblogcategory"){
	$typeid=mysql_real_escape_string($_POST['typeid']);
	$orderfield=array();
	$ordervalues=array();
	$orderfield[]="blogcatid";
	$orderfield[]="id";
	$ordervalues[]=$typeid;
	$ordervalues[]=$entryid;
	genericSingleUpdate("subscriptionlist","status","inactive",$orderfield,$ordervalues);
	header('location:../index.php');
}elseif($entryvariant=="editqotd"){
	$quotetype=mysql_real_escape_string($_POST['quotetype']);
	genericSingleUpdate("qotd","type",$quotetype,"id",$entryid);
		$quotedperson=mysql_real_escape_string($_POST['quotedperson']);
	genericSingleUpdate("qotd","quotedperson",$quotedperson,"id",$entryid);
		$quoteoftheday=mysql_real_escape_string($_POST['quoteoftheday']);
	genericSingleUpdate("qotd","quote",$quoteoftheday,"id",$entryid);
		$status=mysql_real_escape_string($_POST['status']);
	genericSingleUpdate("qotd","status",$status,"id",$entryid);
	if(isset($_FILES['profpic']['tmp_name'])&&$_FILES['profpic']['tmp_name']!==""){
		$image="profpic";
		$imgpath[]='../files/';
		$imgsize[]="default";
		$acceptedsize=",200";
		$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
		$len=strlen($imgouts[0]);
		$imagepath=substr($imgouts[0], 1,$len);
		// get image size details
		$filedata=getFileDetails($imgouts[0],"image");
		$filesize=$filedata['size'];
		$width=$filedata['width'];
		$height=$filedata['height'];
		$picid=$_POST['fid'];
		if($fid>0){
			genericSingleUpdate("media","location",$imagepath,'id',$picid);
			genericSingleUpdate("media","filesize",$filesize,'id',$picid);
			genericSingleUpdate("media","width",$width,'id',$picid);
			genericSingleUpdate("media","height",$height,'id',$picid);
		}else{
			// get the cover photo's media table id for storage with the blog entry
			// $coverid=getNextId("media");
			$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize,width,height)VALUES('$entryid','qotd','coverphoto','image','$imagepath','$filesize','$width','$height')";
			$mediarun=mysql_query($mediaquery)or die(mysql_error());
		}
	}
	if(isset($_FILES['bannerpic']['tmp_name'])&&$_FILES['bannerpic']['tmp_name']!==""){
		$image="profpic";
		$imgpath[]='../files/';
		$imgsize[]="default";
		$acceptedsize=",450";
		$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
		$len=strlen($imgouts[0]);
		$imagepath=substr($imgouts[0], 1,$len);
		// get image size details
		$filedata=getFileDetails($imgouts[0],"image");
		$filesize=$filedata['size'];
		$width=$filedata['width'];
		$height=$filedata['height'];
		// echo '<img src="'.$imgouts[0].'"> '.$filesize.' '.$width.' '.$height.'';
		// get the cover photo's media table id for storage with the blog entry
		$picid=$_POST['bid'];
		if($picid>0){
			genericSingleUpdate("media","location",$imagepath,'id',$picid);
			genericSingleUpdate("media","filesize",$filesize,'id',$picid);
			genericSingleUpdate("media","width",$width,'id',$picid);
			genericSingleUpdate("media","height",$height,'id',$picid);
		}else{
			// get the cover photo's media table id for storage with the blog entry
			// $coverid=getNextId("media");
			$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize,width,height)VALUES('$entryid','qotd','bannerphoto','image','$imagepath','$filesize','$width','$height')";
			$mediarun=mysql_query($mediaquery)or die(mysql_error());
		}
	}
	if($rurladmin==""){

		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');

	}
}elseif ($entryvariant=="editevent") {
	# code...
	$type=mysql_real_escape_string($_POST['eventtype']);
	genericSingleUpdate("events","type",$type,"id",$entryid);
		$eventtitle=mysql_real_escape_string($_POST['eventtitle']);
	genericSingleUpdate("events","eventtitle",$eventtitle,"id",$entryid);
		$date=mysql_real_escape_string($_POST['dateholder']);
		$sep=preg_split("/-/",$date);
		$d=$sep[0];
	genericSingleUpdate("events","d",$d,"id",$entryid);
		$m=(int)$sep[1];
	    $m<10&&strlen($m)<2?$m="0$m":$m=$m;
	genericSingleUpdate("events","m",$m,"id",$entryid);
		$y=$sep[2];
	genericSingleUpdate("events","y",$y,"id",$entryid);
		$date="$d-$m-$y";
	genericSingleUpdate("events","dateperiod",$date,"id",$entryid);
		$eventdetails=mysql_real_escape_string($_POST['eventdetails']);
	genericSingleUpdate("events","eventdetails",$eventdetails,"id",$entryid);
			$status=mysql_real_escape_string($_POST['status']);
	genericSingleUpdate("events","status",$status,"id",$entryid);
	if($rurladmin==""){

		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');

	}
}elseif ($entryvariant=="editgallery") {
	# code...
	// echo "in here";
	$gallerytitle=mysql_real_escape_string($_POST['gallerytitle']);
	genericSingleUpdate("gallery","gallerytitle",$gallerytitle,"id",$entryid);
	$gallerydetails=mysql_real_escape_string($_POST['gallerydetails']);
	genericSingleUpdate("gallery","gallerydetails",$gallerydetails,"id",$entryid);
	$status=mysql_real_escape_string($_POST['status']);
	genericSingleUpdate("gallery","status",$status,"id",$entryid);
	$date=date("d, F Y H:i:s");
	$piccount=$_POST['piccount'];
	//echo $piccount;
	if($piccount>0){
		for($i=1;$i<=$piccount;$i++){
		$albumpic=$_FILES['defaultpic'.$i.'']['tmp_name'];
	if($albumpic!==""){
	$image="defaultpic".$i."";
	if(isset($imagepath)){
	unset($imagepath);
	unset($imagesize);
	}
	$imagepath=array();
	$imagesize=array();
	$imgpath[0]='../files/medsizes/';
	$imgpath[1]='../files/thumbnails/';
	$imgsize[0]="default";
	$imgsize[1]=",107";
	// echo count($imgsize);
	$acceptedsize="";
	$imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
	$len=strlen($imgouts[0]);
	$imagepath=substr($imgouts[0], 1,$len);
	echo $imgouts[0]."<br>".$len."<br>".$imagepath."<br>";
	$len2=strlen($imgouts[1]);
	$imagepath2=substr($imgouts[1], 1,$len2);
	echo $imgouts[1]."<br>".$len2."<br>".$imagepath2."<br>";
	// get image size details
	$filedata=getFileDetails($imgouts[0],"image");
	$filesize=$filedata['size'];
	$width=$filedata['width'];
	$height=$filedata['height'];
	// get the cover photo's media table id for storage with the blog entry
	$mediaquery="INSERT INTO media(ownerid,ownertype,details,mediatype,location,filesize,width,height)VALUES('$entryid','gallery','$imagepath2','image','$imagepath','$filesize','$width','$height')";
	$mediarun=mysql_query($mediaquery)or die(mysql_error());
	}
	}
	}
	if($rurladmin==""){

		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');

	}
}elseif ($entryvariant=="edittrendingtopic") {
	# code...
	$outs=getSingleTrendingTopic($entryid);
	$prevpic=$outs['profpic'];
	$realprevpic=".".$prevpic;
	$profpic=$_FILES['profpic']['tmp_name'];
	if($_FILES['profpic']['tmp_name']!==""){
	$image="profpic";
	$imgpath[0]='../files/';
	$imgsize[0]="default";
	$acceptedsize=",150";
	$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
	$len=strlen($imgouts[0]);
	$imagepath=substr($imgouts[0], 1,$len);
	// get image size details
	$filedata=getFileDetails($imgouts[0],"image");
	$filesize=$filedata['size'];
	$width=$filedata['width'];
	$height=$filedata['height'];
	$orderfield[]="ownerid";
	$orderfield[]="ownertype";
	$orderfield[]="maintype";
	$ordervalues[]=$entryid;
	$ordervalues[]="trendingtopic";
	$ordervalues[]="coverphoto";
	genericSingleUpdate("media","location",$imagepath,$orderfield,$ordervalues);
	genericSingleUpdate("media","filesize",$filesize,$orderfield,$ordervalues);
	genericSingleUpdate("media","width",$width,$orderfield,$ordervalues);
	genericSingleUpdate("media","height",$height,$orderfield,$ordervalues);
	if(file_exists($realprevpic)){
	unlink($realprevpic);
	}
	}
	// echo 'here';
	$details=mysql_real_escape_string($_POST['name']);
	genericSingleUpdate("trendingtopics","details",$details,"id",$entryid);
	$status=mysql_real_escape_string($_POST['status']);
	genericSingleUpdate("trendingtopics","status",$status,"id",$entryid);
	if($rurladmin==""){

		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');

	}
}elseif ($entryvariant=="edittoptenplaylist") {
	# code...
	$title=mysql_real_escape_string($_POST['title']);
	genericSingleUpdate("toptenplaylist","title",$title,"id",$entryid);
	$artist=mysql_real_escape_string($_POST['artist']);	
	genericSingleUpdate("toptenplaylist","artist",$artist,"id",$entryid);
	$status=mysql_real_escape_string($_POST['status']);
	genericSingleUpdate("toptenplaylist","status",$status,"id",$entryid);
	$outs=getSinglePlaylist($entryid);
	$prevaudio=$outs['audiofilepath'];
	$realprevaudio=".".$prevaudio;
	$prevpic=$outs['profpic'];
	$realprevpic=".".$prevpic;
	$profpic=$_FILES['profpic']['tmp_name'];
	if($_FILES['profpic']['tmp_name']!==""){
	$image="profpic";
	$imgpath[0]='../files/';
	$imgsize[0]="default";
	$acceptedsize=",150";
	$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
	$len=strlen($imgouts[0]);
	$imagepath=substr($imgouts[0], 1,$len);
	// get image size details
	$filedata=getFileDetails($imgouts[0],"image");
	$filesize=$filedata['size'];
	$width=$filedata['width'];
	$height=$filedata['height'];
	$orderfield[]="ownerid";
	$orderfield[]="ownertype";
	$orderfield[]="maintype";
	$ordervalues[]=$entryid;
	$ordervalues[]="toptenplaylist";
	$ordervalues[]="coverphoto";
	genericSingleUpdate("media","location",$imagepath,$orderfield,$ordervalues);
	genericSingleUpdate("media","filesize",$filesize,$orderfield,$ordervalues);
	genericSingleUpdate("media","width",$width,$orderfield,$ordervalues);
	genericSingleUpdate("media","height",$height,$orderfield,$ordervalues);
	if(file_exists($realprevpic)){
	unlink($realprevpic);
	}
	}

	$music=$_FILES['music']['tmp_name'];
	if($_FILES['music']['tmp_name']!==""){
	$outsaudio=simpleUpload('music','../files/audio/');
	$audiofilepath=$outsaudio['filelocation'];
	$len=strlen($audiofilepath);
	$audiofilepath=substr($audiofilepath, 1,$len);
	$audiofilesize=$outsaudio['filesize'];
	$orderfield[]="ownerid";
	$orderfield[]="ownertype";
	$orderfield[]="maintype";
	$ordervalues[]=$entryid;
	$ordervalues[]="toptenplaylist";
	$ordervalues[]="musicfile";
	genericSingleUpdate("media","location",$audiofilepath,$orderfield,$ordervalues);
	genericSingleUpdate("media","filesize",$audiofilesize,$orderfield,$ordervalues);
	if(file_exists($realprevaudio)){
	unlink($realprevaudio);
	}
	}
	if($rurladmin==""){

		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');

	}

}elseif($entryvariant=="editcsisermon"){
	// $nextid=getNextIdExplicit('sermons');
	$title=mysql_real_escape_string($_POST['title']);
	genericSingleUpdate("sermons","title",$title,"id",$entryid);
	$sermondate=mysql_real_escape_string($_POST['sermondate']);
	genericSingleUpdate("sermons","exactday",$sermondate,"id",$entryid);
	$intro=mysql_real_escape_string($_POST['intro']);
	genericSingleUpdate("sermons","intro",$intro,"id",$entryid);
	$audiotype=mysql_real_escape_string($_POST['audiotype']);
	genericSingleUpdate("sermons","audiotype",$audiotype,"id",$entryid);
	$videotype=mysql_real_escape_string($_POST['videotype']);
	genericSingleUpdate("sermons","videotype",$videotype,"id",$entryid);
	$audioembedcode=mysql_real_escape_string($_POST['audioembed']);
	genericSingleUpdate("sermons","audiocode",$audioembedcode,"id",$entryid);
	$videoembedcode=mysql_real_escape_string($_POST['videoembed']);
	genericSingleUpdate("sermons","videocode",$videoembedcode,"id",$entryid);
	$status=mysql_real_escape_string($_POST['status']);
	genericSingleUpdate("toptenplaylist","status",$status,"id",$entryid);

	if(isset($_FILES['profpic']['tmp_name'])&&$_FILES['profpic']['tmp_name']!==""){
		$imgid=$_POST['imgid'];
		$image="profpic";
		$imgpath[]='../files/';
		$imgsize[]="default";
		$acceptedsize="400,";
		$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
		$len=strlen($imgouts[0]);
		$imagepath=substr($imgouts[0], 1,$len);
		// get image size details
		$filedata=getFileDetails($imgouts[0],"image");
		$filesize=$filedata['size'];
		$width=$filedata['width'];
		$height=$filedata['height'];
		// echo '<img src="'.$imgouts[0].'"> '.$filesize.' '.$width.' '.$height.'';
		// get the cover photo's media table id for storage with the blog entry
		if($imgid<1){

		$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize,width,height)VALUES('$entryid','sermon','coverphoto','image','$imagepath','$filesize','$width','$height')";
		$mediarun=mysql_query($mediaquery)or die(mysql_error());
		}else{
			$orderfield[]="ownerid";
			$orderfield[]="ownertype";
			$orderfield[]="maintype";
			$ordervalues[]=$entryid;
			$ordervalues[]="sermon";
			$ordervalues[]="coverphoto";
			genericSingleUpdate("media","location",$imagepath,$orderfield,$ordervalues);
			genericSingleUpdate("media","filesize",$filesize,$orderfield,$ordervalues);
			genericSingleUpdate("media","width",$width,$orderfield,$ordervalues);
			genericSingleUpdate("media","height",$height,$orderfield,$ordervalues);
		}
	}
	if (isset($_FILES['audio']['tmp_name'])&&$_FILES['audio']['tmp_name']!=="") {
		# code...
		$localaudioid=$_POST['localaudioid'];
		$outsaudio=simpleUpload('audio','../files/audio/');
		$audiofilepath=$outsaudio['filelocation'];
		$len=strlen($audiofilepath);
		$audiofilepath=substr($audiofilepath, 1,$len);
		$audiofilesize=$outsaudio['filesize'];
		if($localaudioid>0){
			$orderfield[]="ownerid";
			$orderfield[]="ownertype";
			$orderfield[]="maintype";
			$ordervalues[]=$entryid;
			$ordervalues[]="sermon";
			$ordervalues[]="sermonaudio";
			genericSingleUpdate("media","location",$audiofilepath,$orderfield,$ordervalues);
			genericSingleUpdate("media","filesize",$audiofilesize,$orderfield,$ordervalues);
		}else{		
			$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)VALUES('$entryid','sermon','sermonaudio','audio','$audiofilepath','$audiofilesize')";
			$mediarun2=mysql_query($mediaquery2)or die(mysql_error());
		}
	}
	if (isset($_FILES['videoflv']['tmp_name'])&&$_FILES['videoflv']['tmp_name']!=="") {
		# code...
		$localaudioid=$_POST['localvideoflvid'];
		$outsaudio=simpleUpload('videoflv','../files/videos/');
		$audiofilepath=$outsaudio['filelocation'];
		$len=strlen($audiofilepath);
		$audiofilepath=substr($audiofilepath, 1,$len);
		$audiofilesize=$outsaudio['filesize'];
		if($localvideoflvid>0){
			$orderfield[]="ownerid";
			$orderfield[]="ownertype";
			$orderfield[]="maintype";
			$orderfield[]="mediatype";
			$ordervalues[]=$entryid;
			$ordervalues[]="sermon";
			$ordervalues[]="sermonvideo";
			$ordervalues[]="videoflv";
			genericSingleUpdate("media","location",$imagepath,$orderfield,$ordervalues);
			genericSingleUpdate("media","filesize",$filesize,$orderfield,$ordervalues);
		}else{
			$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)VALUES('$entryid','sermon','sermonvideo','videoflv','$audiofilepath','$audiofilesize')";
			$mediarun2=mysql_query($mediaquery2)or die(mysql_error());
		}
	}
	if (isset($_FILES['videomp4']['tmp_name'])&&$_FILES['videomp4']['tmp_name']!=="") {
		# code...
		$localvideomp4id=$_POST['localvideomp4id'];
		$outsaudio=simpleUpload('videomp4','../files/videos/');
		$audiofilepath=$outsaudio['filelocation'];
		$len=strlen($audiofilepath);
		$audiofilepath=substr($audiofilepath, 1,$len);
		$audiofilesize=$outsaudio['filesize'];
		if($localvideomp4id>0){
			$orderfield[]="ownerid";
			$orderfield[]="ownertype";
			$orderfield[]="maintype";
			$orderfield[]="mediatype";
			$ordervalues[]=$entryid;
			$ordervalues[]="sermon";
			$ordervalues[]="sermonvideo";
			$ordervalues[]="videomp4";
			genericSingleUpdate("media","location",$imagepath,$orderfield,$ordervalues);
			genericSingleUpdate("media","filesize",$filesize,$orderfield,$ordervalues);
		}else{
			$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)VALUES('$entryid','sermon','sermonvideo','videomp4','$audiofilepath','$audiofilesize')";
			$mediarun2=mysql_query($mediaquery2)or die(mysql_error());			
		}
	}
	if (isset($_FILES['video3gp']['tmp_name'])&&$_FILES['video3gp']['tmp_name']!=="") {
		# code...
		$localaudioid=$_POST['localvideo3gpid'];
		$outsaudio=simpleUpload('video3gp','../files/videos/');
		$audiofilepath=$outsaudio['filelocation'];
		$len=strlen($audiofilepath);
		$audiofilepath=substr($audiofilepath, 1,$len);
		$audiofilesize=$outsaudio['filesize'];
		if($localvideo3gpid>0){
			$orderfield[]="ownerid";
			$orderfield[]="ownertype";
			$orderfield[]="maintype";
			$orderfield[]="mediatype";
			$ordervalues[]=$entryid;
			$ordervalues[]="sermon";
			$ordervalues[]="sermonvideo";
			$ordervalues[]="video3gp";
			genericSingleUpdate("media","location",$imagepath,$orderfield,$ordervalues);
			genericSingleUpdate("media","filesize",$filesize,$orderfield,$ordervalues);
		}else{
			$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)VALUES('$entryid','sermon','sermonvideo','video3gp','$audiofilepath','$audiofilesize')";
			$mediarun2=mysql_query($mediaquery2)or die(mysql_error());
		}
	}
	/*$query="INSERT INTO (title,intro,audiotype,audiocode,videotype,videocode,exactday,date)VALUES('$title','$intro','$audiotype','$videotype','$videocode','$sermondate',CURRENT_DATE())";
	$run=mysql_query($query)or die(mysql_error());*/	
	if($rurladmin==""){
		header('location:../admin/adminindex.php?compid=4&t=1&v=admin');
	}else{
		header('location:'.$rurladmin.'?compid=4&t=1&v=admin');
	}
}elseif ($entryvariant=="edittopaudio") {
	# code...
	$title=mysql_real_escape_string($_POST['title']);
	genericSingleUpdate("toptenplaylist","title",$title,"id",$entryid);
	$status=mysql_real_escape_string($_POST['status']);
	genericSingleUpdate("toptenplaylist","status",$status,"id",$entryid);
	$outs=getSingleTopAudio($entryid);
	$prevaudio=$outs['audiofilepath'];
	$realprevaudio=".".$prevaudio;
	$music=$_FILES['music']['tmp_name'];
	if($_FILES['music']['tmp_name']!==""){
	$outsaudio=simpleUpload('music','../files/audio/');
	$audiofilepath=$outsaudio['filelocation'];
	$len=strlen($audiofilepath);
	$audiofilepath=substr($audiofilepath, 1,$len);
	$audiofilesize=$outsaudio['filesize'];
	$orderfield[]="ownerid";
	$orderfield[]="ownertype";
	$orderfield[]="maintype";
	$ordervalues[]=$entryid;
	$ordervalues[]="topaudio";
	$ordervalues[]="musicfile";
	genericSingleUpdate("media","location",$audiofilepath,$orderfield,$ordervalues);
	genericSingleUpdate("media","filesize",$audiofilesize,$orderfield,$ordervalues);
	if(file_exists($realprevaudio)){
	unlink($realprevaudio);
	}
	}
	if($rurladmin==""){

		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');

	}

}elseif ($entryvariant=="editadvert") {
	# code...
	$outs=getSingleAdvert($entryid);

	$prevfile=$outs['filepath'];
	$testit=strpos($prevfile,"http://localhost/");
	$testit2=strpos($prevfile,"http://");
	// echo "<br>here".$testpath['testfile']."<br>".$testit."<br>";
	if($testit===0){
	$testpath=getRelativePathToSnippets($prevfile);
	$realprevfile=$testpath['testfile'];
	}elseif ($testit2===0) {
		# code...
	$testpath=getRelativePathToSnippets($prevfile);
	$realprevfile=$testpath['testfile'];
	}else{
	$realprevfile=".".$prevfile;
	}
	$profpic=$_FILES['profpic']['tmp_name'];
	if($outs['type']=="banneradvert"||$outs['type']=="miniadvert"){
	if($_FILES['profpic']['tmp_name']!==""){
	$image="profpic";
	$outs['type']=="banneradvert"?$imgpath[0]="../images/adverts/banners/":$imgpath[0]="../images/adverts/miniadverts/";
	$imgsize[0]="default";
	$outs['type']=="banneradvert"?$acceptedsize=",250":$acceptedsize=",200";
	$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
	$len=strlen($imgouts[0]);
	$imagepath=substr($imgouts[0], 1,$len);
	// get image size details
	$filedata=getFileDetails($imgouts[0],"image");
	$filesize=$filedata['size'];
	$width=$filedata['width'];
	$height=$filedata['height'];
	$orderfield[]="ownerid";
	$orderfield[]="ownertype";
	$orderfield[]="maintype";
	$ordervalues[]=$entryid;
	$ordervalues[]="advert";
	$ordervalues[]=$outs['type'];
	genericSingleUpdate("media","location",$imagepath,$orderfield,$ordervalues);
	genericSingleUpdate("media","filesize",$filesize,$orderfield,$ordervalues);
	genericSingleUpdate("media","width",$width,$orderfield,$ordervalues);
	genericSingleUpdate("media","height",$height,$orderfield,$ordervalues);

	if(file_exists($realprevfile)){
	unlink($realprevfile);
	}
	}
	}elseif ($outs['type']=="videoadvert") {
	# code...
	if($_FILES['profpic']['tmp_name']!==""){
	$outsvideo=simpleUpload('profpic','../files/video/');
	$videofilepath=$outsvideo['filelocation'];
	$len=strlen($videofilepath);
	$videofilepath=substr($videofilepath, 1,$len);
	$videofilesize=$outsvideo['filesize'];
	$orderfield[]="ownerid";
	$orderfield[]="ownertype";
	$orderfield[]="maintype";
	$ordervalues[]=$entryid;
	$ordervalues[]="advert";
	$ordervalues[]=$outs['type'];
	genericSingleUpdate("media","location",$videofilepath,$orderfield,$ordervalues);
	genericSingleUpdate("media","filesize",$videofilesize,$orderfield,$ordervalues);
	if(file_exists($realprevfile)){
	unlink($realprevfile);
	}
	}
	}

	$adverttitle=mysql_real_escape_string($_POST['adverttitle']);
	genericSingleUpdate("adverts","title",$adverttitle,"id",$entryid);
	$advertowner=mysql_real_escape_string($_POST['advertowner']);	
	genericSingleUpdate("adverts","owner",$advertowner,"id",$entryid);
	$advertpage=mysql_real_escape_string($_POST['advertpage']);	
	genericSingleUpdate("adverts","activepage",$advertpage,"id",$entryid);
	$advertlandingpage=mysql_real_escape_string($_POST['advertlandingpage']);	
	genericSingleUpdate("adverts","landingpage",$advertlandingpage,"id",$entryid);
	$status=mysql_real_escape_string($_POST['status']);
	genericSingleUpdate("adverts","status",$status,"id",$entryid);
	if($rurladmin==""){

		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');

	}	
}elseif ($entryvariant=="edittestimony") {
	# code...
	$outs=getSingleTestimony($entryid);
	// echo "here";
	$displaytitle=mysql_real_escape_string($_POST['displaytitle']);
	genericSingleUpdate("testimony","displaytitle",$displaytitle,"id",$entryid);
	$testimony=mysql_real_escape_string($_POST['testimony']);	
	genericSingleUpdate("testimony","testimony",$testimony,"id",$entryid);
	if($outs['displaytitle']==""&&$displaytitle!==""){
	$status=mysql_real_escape_string($_POST['status']);
	genericSingleUpdate("testimony","status",$status,"id",$entryid);
	}elseif($outs['displaytitle']!==""){
	$status=mysql_real_escape_string($_POST['status']);
	genericSingleUpdate("testimony","status",$status,"id",$entryid);
	}
	if($rurladmin==""){

		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');

	}	
}else if($entryvariant=="editstoreaudio"){
	$storeaudiodata=getSingleStoreAudio($entryid);
	$aid=mysql_real_escape_string($_POST['aid']);
	$coverid=mysql_real_escape_string($_POST['coverid']);
	$audiodata=getSingleMediaDataTwo($aid);
	$realprevaudio=".".$audiodata['location'];
	$realprevaudiominiclip=".".$audiodata['details'];
	$coverpicdata=getSingleMediaDataTwo($coverid);
	$realprevpic=".".$coverpicdata['location'];
	$type=mysql_real_escape_string($_POST['type']);
	if($type==""){
		$type==$storeaudiodata['typeid'];
	}
	// write album art to mp3 files
	$album="";
	$albumart="../images/frontierslogoalbumart.jpg";
	if($type==1){
		$album="Frankly Speaking With Muyiwa Afolabi";
		$albumart='../images/frontierslogoalbumart.jpg';
	}else if ($type==2) {
		# code...
		$album="Christ Society International Outreach";
		$albumart='../images/csi.png';
	}else if($type==3){
		$album="The Ultimate State Of Mind";
		$albumart='../images/frontierslogoalbumart.jpg';
	}

	genericSingleUpdate("onlinestoreentries","typeid",$type,"id",$entryid);
	$title=mysql_real_escape_string($_POST['title']);
	genericSingleUpdate("onlinestoreentries","title",$title,"id",$entryid);
	$minititle=mysql_real_escape_string($_POST['minititle']);
	genericSingleUpdate("onlinestoreentries","minititle",$minititle,"id",$entryid);
	$minidescription=mysql_real_escape_string($_POST['minidescription']);
	genericSingleUpdate("onlinestoreentries","minidescription",$minidescription,"id",$entryid);
	$price=mysql_real_escape_string($_POST['price']);
	$prevstart=mysql_real_escape_string($_POST['prevstart']);
	$prevstartprev=mysql_real_escape_string($_POST['prevstartprev']);
	$prevstop=mysql_real_escape_string($_POST['prevstop']);
	$prevstopprev=mysql_real_escape_string($_POST['prevstopprev']);
	genericSingleUpdate("onlinestoreentries","price",$price,"id",$entryid);
	$status=mysql_real_escape_string($_POST['status']);
	genericSingleUpdate("onlinestoreentries","status",$status,"id",$entryid);

	$defstrt=20;
	$defstop=300;
	$pstrt=$_POST['prevstart'];
	$pstop=$_POST['prevstop'];
	if(isset($_POST['prevstart'])&&isset($_POST['prevstop'])){
		if($_POST['prevstart']!==""){
			$pstrt=explode(":",$pstrt);
			$pmantis=$pstrt[0]*60;
			isset($pstrt[0])&&$pstrt[0]>=0?$pmantis=$pstrt[0]*60:$pmantis=20;
			isset($pstrt[1])&&$pstrt[1]!==""?$pmantis2=$pstrt[1]:$pmantis2=0;
			$pstrt=$pmantis+$pmantis2;
		}
		if($_POST['prevstop']!==""){
			$pstop=explode(":",$pstop);
			isset($pstop[0])&&$pstop[0]>=0?$pmantis=$pstop[0]*60:$pmantis=300;
			isset($pstop[1])&&$pstop[1]!==""?$pmantis2=$pstop[1]:$pmantis2=0;
			$pstop=$pmantis+$pmantis2;
		}
		$pstrt>$pstop?$defstop=$pstrt:$defstop=$pstop;
		$pstrt<$pstop?$defstart=$pstrt:$defstart=$pstop;
		$defstop=$defstop-$defstart;
	}
	if(isset($_FILES['audio']['tmp_name'])&&$_FILES['audio']['tmp_name']!==""){	
		require_once 'phpmp3.php';
		$outsaudio=simpleUpload('audio','../files/audio/');
		$teepy=explode(".",$_FILES['audio']['name']);
		$filerealname=$teepy[0];
		$audiofilepath=$outsaudio['filelocation'];
		$mp3 = new PHPMP3($audiofilepath);
		$mp3=$mp3->extract($defstart, $defstop);
		$mp3->save('../files/audio/'.$filerealname.''.md5($entryid).'.mp3');
		$audiofilepath2='../files/audio/'.$filerealname.''.md5($entryid).'.mp3';
		require_once("../getid3/getid3.php");
		$TaggingFormat = 'UTF-8';
		// Initialize getID3 engine
		$getID3 = new getID3;
		$getID3->setOption(array('encoding'=>$TaggingFormat));
		$Filename = (isset($_REQUEST['Filename']) ? $_REQUEST['Filename'] : $audiofilepath);
		$TagFormatsToWrite = (isset($_POST['TagFormatsToWrite']) ? $_POST['TagFormatsToWrite'] : array());
		$TagFormatsToWrite['Tags']="id3v2.4";
		getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'write.php', __FILE__, true);
		$tagwriter = new getid3_writetags;
		$tagwriter->filename       = $Filename;
		$tagwriter->tagformats     = $TagFormatsToWrite;
		$tagwriter->overwrite_tags = true;
		$tagwriter->tag_encoding   = $TaggingFormat;
		$TagData=array();
		$commonkeysarray = array('Title', 'Artist', 'Album', 'Year', 'Comment');
		$TagData['title'][] = "$title";
		$TagData['artist'][] = "Muyiwa Afolabi";
		$TagData['album'][] = "$album";
		$TagData['year'][] = "2015";
		$TagData['comment'][] = "";
		$TagData['genre'][] = "MOTIVATION";
		$TagData['genre'][] = "Inspirational";
		$TagData['track'][] = isset($_POST['Track'])&&$_POST['Track'].(!empty($_POST['TracksTotal']) ? '/'.$_POST['TracksTotal'] : '01');

		if (!empty($audiofilepath)) {
			if (in_array('id3v2.4', $tagwriter->tagformats) || in_array('id3v2.3', $tagwriter->tagformats) || in_array('id3v2.2', $tagwriter->tagformats)|| in_array('id3v2', $tagwriter->tagformats)||in_array('id3v2.0', $tagwriter->tagformats)) {
				if (file_exists($audiofilepath)) {
					ob_start();
					if ($fd = fopen(''.$albumart.'', 'rb')) {
						ob_end_clean();
						$APICdata = fread($fd, filesize(''.$albumart.''));
						fclose ($fd);
						list($APIC_width, $APIC_height, $APIC_imageTypeID) = GetImageSize(''.$albumart.'');
						$imagetypes = array(1=>'gif', 2=>'jpeg', 3=>'png');
						if (isset($imagetypes[$APIC_imageTypeID])) {
							$TagData['attached_picture'][0]['data']          = $APICdata;
							$TagData['attached_picture'][0]['picturetypeid'] = "2";
							$TagData['attached_picture'][0]['description']   = "Cover Art";
							$TagData['attached_picture'][0]['mime']          = 'image/'.$imagetypes[$APIC_imageTypeID];
						} else {
							echo '<b>invalid image format (only GIF, JPEG, PNG)</b><br>';
						}
					} else {
						$errormessage = ob_get_contents();
						ob_end_clean();
						echo '<b>cannot open '.$audiofilepath.'</b><br>';
					}
				} else {
					echo '<b>!is_uploaded_file('.$audiofilepath.')</b><br>';
				}
			} else {
				echo '<b>WARNING:</b> Can only embed images for ID3v2<br>';
			}
		}

		$tagwriter->tag_data = $TagData;
		if ($tagwriter->WriteTags()) {
			echo 'Successfully wrote tags<BR>';
			if (!empty($tagwriter->warnings)) {
				echo 'There were some warnings:<BLOCKQUOTE STYLE="background-color:#FFCC33; padding: 10px;">'.implode('<br><br>', $tagwriter->warnings).'</BLOCKQUOTE>';
			}
		} else {
			echo 'Failed to write tags!<BLOCKQUOTE STYLE="background-color:#FF9999; padding: 10px;">'.implode('<br><br>', $tagwriter->errors).'</BLOCKQUOTE>';
		}
		/*end*/
		$len=strlen($audiofilepath);
		$audiofilepath=substr($audiofilepath, 1,$len);
		//mini audio file
		$len2=strlen($audiofilepath2);
		$audiofilepath2=substr($audiofilepath2, 1,$len2);
		$audiofilesize=$outsaudio['filesize'];
		// delete the prev files
		if(file_exists($realprevaudio)){
			unlink($realprevaudio);
		}
		$pcontrol=".".$audiofilepath2;
		if(file_exists($realprevaudiominiclip)&&$realprevaudiominiclip!==$pcontrol){
			// unlink($realprevaudiominiclip);
		}
		// echo $audiofilepath."<br>";
		// echo $audiofilepath2."<br>";
		// update with new ones
		// genericSingleUpdate("media","location",$audiofilepath,"id",$aid);
		// genericSingleUpdate("media","details",$audiofilepath2,"id",$aid);
		// update start and stoptime entry
		// genericSingleUpdate("onlinestoreentries","starttime",$prevstart,"id",$entryid);
		// genericSingleUpdate("onlinestoreentries","stoptime",$prevstop,"id",$entryid);

		// echo $mediaquery2;
	}else if(isset($_FILES['audio2']['tmp_name'])&&$_FILES['audio2']['tmp_name']!==""){

		$outsaudio=simpleUpload('audio2','../files/audio/');
		$teepy=explode(".",$_FILES['audio2']['name']);
		$filerealname=$teepy[0];
		$audiofilepath2=$outsaudio['filelocation'];
		$len2=strlen($audiofilepath2);
		$audiofilepath2=substr($audiofilepath2, 1,$len2);
		$audiofilesize=$outsaudio['filesize'];
		$pcontrol=".".$audiofilepath2;
		if(file_exists($realprevaudiominiclip)&&$realprevaudiominiclip!==$pcontrol){
			unlink($realprevaudiominiclip);
		}
		genericSingleUpdate("media","details",$audiofilepath2,"id",$aid);
	}else{
		require_once 'phpmp3.php';
		if(($prevstart!==""&&$prevstart!==$prevstartprev)||($prevstop!==""&&$prevstop!==$prevstopprev)){
			$defstrt=20;
			$defstop=300;
			$pstrt=$_POST['prevstart'];
			$pstop=$_POST['prevstop'];
			if(isset($_POST['prevstart'])&&isset($_POST['prevstop'])){
				if($_POST['prevstart']!==""){
					$pstrt=explode(":",$pstrt);
					$pmantis=$pstrt[0]*60;
					isset($pstrt[0])&&$pstrt[0]>=0?$pmantis=$pstrt[0]*60:$pmantis=20;
					isset($pstrt[1])&&$pstrt[1]!==""?$pmantis2=$pstrt[1]:$pmantis2=0;
					echo "<br>pstart[0]: ".$pstrt[0]."<br>pstart[2]: ".$pstrt[1]."<br>";
					$pstrt=$pmantis+$pmantis2;
				}
				if($_POST['prevstop']!==""){
					$pstop=explode(":",$pstop);
					isset($pstop[0])&&$pstop[0]>=0?$pmantis=$pstop[0]*60:$pmantis=300;
					isset($pstop[1])&&$pstop[1]!==""?$pmantis2=$pstop[1]:$pmantis2=0;
					$pstop=$pmantis+$pmantis2;
				}
				$pstrt>$pstop?$defstop=$pstrt:$defstop=$pstop;
				$pstrt<$pstop?$defstart=$pstrt:$defstart=$pstop;
			}
			echo "i here $defstart - $defstop";
			$defstop=$defstop-$defstart;
			$audiofile=$audiodata['location'];
			$teepy=explode("/",$audiodata['details']);
			$tco=$teepy[count($teepy)-1];
			$tpstandin=$teepy[count($teepy)-1];
			// echo "<br> teepy: $tpstandin";
			$teepy=explode(".",$tco);
			$step="";
			for ($t=0;$t<count($teepy);$t++) {
				# code...
				if($t!==count($teepy)-1){
					$step.=$teepy[$t];
				}

			}
			// echo "<br> tco: $tco<br> teepy2: $teepy[0]";
			$filerealname=$step;
			$audiofilepath=".".$audiofile;
			echo "<br>$defstop <br>$audiofilepath <br>$filerealname";

			$mp3 = new PHPMP3($audiofilepath);

			$mp3=$mp3->extract($defstart, $defstop);

			$mp3->save('../files/audio/'.$filerealname.'.mp3');

			$audiofilepath2='../files/audio/'.$filerealname.'.mp3';

			$len2=strlen($audiofilepath2);

			$audiofilepath2=substr($audiofilepath2, 1,$len2);
			genericSingleUpdate("media","details",$audiofilepath2,"id",$aid);
			// update start and stoptime entry
			genericSingleUpdate("onlinestoreentries","starttime",$prevstart,"id",$entryid);
			genericSingleUpdate("onlinestoreentries","stoptime",$prevstop,"id",$entryid);
		}
	}
	if(isset($_FILES['profpic']['tmp_name'])&&$_FILES['profpic']['tmp_name']!==""){	
		$image="profpic";
		$imgpath[0]='../files/';
		$imgsize[0]="default";
		$acceptedsize=",250";
		$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
		$len=strlen($imgouts[0]);
		$imagepath=substr($imgouts[0], 1,$len);
		// get image size details
		$filedata=getFileDetails($imgouts[0],"image");
		$filesize=$filedata['size'];
		$width=$filedata['width'];
		$height=$filedata['height'];
		if(file_exists($realprevpic)){
			unlink($realprevpic);
		}
		genericSingleUpdate("media","location",$imagepath,"id",$coverid);
		genericSingleUpdate("media","filesize",$filesize,"id",$coverid);
		genericSingleUpdate("media","width",$width,"id",$coverid);
		genericSingleUpdate("media","height",$height,"id",$coverid);
	}
	if($rurladmin==""){

		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');

	}	

}elseif ($entryvariant=="introentryupdate") {
	# code...
	$introtitle=isset($_POST['contenttitle'])?mysql_real_escape_string($_POST['contenttitle']):"";
	$intro=mysql_real_escape_string(str_replace("../", "$host_addr",$_POST['intro']));
	$maintype=mysql_real_escape_string($_POST['maintype']);
	$subtype=mysql_real_escape_string($_POST['subtype']);
	if($entryid!==""&&$entryid>0){
		// $status=mysql_real_escape_string($_POST['status']);
		genericSingleUpdate("generalinfo","title",$introtitle,"id",$entryid);
		genericSingleUpdate("generalinfo","intro",$intro,"id",$entryid);
		// genericSingleUpdate("generalinfo","content",$content,"id",$entryid);
		genericSingleUpdate("generalinfo","status",$status,"id",$entryid);
	}else{
    // check to see if there is a match
    $querytest="SELECT * FROM generalinfo WHERE maintype='$maintype' AND subtype='$subtype'";
    $runtest=mysql_query($querytest)or die(mysql_error()." ".__LINE__);
    $numrows=mysql_num_rows($runtest);
    if($numrows<1){
    		$entrydate=date("Y-m-d H:i:s");
        $query="INSERT INTO generalinfo(maintype,subtype,title,intro,entrydate)VALUES
    	   ('$maintype','$subtype','$introtitle','$intro','$entrydate')";
        $run=mysql_query($query)or die(mysql_error()." ".__LINE__);
    }
    // // echo $numrows;
	}
	if($rurladmin==""){

		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');

	}
}elseif ($entryvariant=="contententryupdate") {
  # code... this section is for pages that have a singular content
  $introtitle=isset($_POST['contenttitle'])?mysql_real_escape_string($_POST['contenttitle']):"";
  $intro=isset($_POST['intro'])?mysql_real_escape_string(str_replace("../", "$host_addr",$_POST['intro'])):"";
  $headerdescription = convert_html_to_text($intro);
  $headerdescription=html2txt($headerdescription);
  $monitorlength=strlen($headerdescription);
  $headerdescription=$monitorlength<600?$headerdescription."...":substr($headerdescription, 0,600)."...";
  $maintype=mysql_real_escape_string($_POST['maintype']);
  $subtype=mysql_real_escape_string($_POST['subtype']);
  $status=isset($_POST['status'])?mysql_real_escape_string($_POST['status']):"";
  if($entryid!==""&&$entryid>0){
    // $status=mysql_real_escape_string($_POST['status']);
    genericSingleUpdate("generalinfo","title",$introtitle,"id",$entryid);
    genericSingleUpdate("generalinfo","intro",$headerdescription,"id",$entryid);
    genericSingleUpdate("generalinfo","content",$intro,"id",$entryid);
    genericSingleUpdate("generalinfo","status",$status,"id",$entryid);
  }else{
    $entryid=getNextId('generalinfo');
    // check to see if there is a match
    $querytest="SELECT * FROM generalinfo WHERE maintype='$maintype' AND subtype='$subtype'";
    $runtest=mysql_query($querytest)or die(mysql_error()." ".__LINE__);
    $numrows=mysql_num_rows($runtest);
    if($numrows<1){
        $entrydate=date("Y-m-d H:i:s");
        $query="INSERT INTO generalinfo(maintype,subtype,title,intro,content,entrydate)VALUES
         ('$maintype','$subtype','$introtitle','$headerdescription','$intro','$entrydate')";
        $run=mysql_query($query)or die(mysql_error()." ".__LINE__);
    }
    
    // // echo $numrows;
  }
    $contentpic=isset($_FILES['contentpic']['tmp_name'])?$_FILES['contentpic']['tmp_name']:"";
    if($contentpic!==""){
      $imgid=isset($_POST['coverid'])?mysql_real_escape_string($_POST['coverid']):0;
      $image="contentpic";
      $imgpath[]='../files/medsizes/';
      $imgpath[]='../files/thumbnails/';
      $imgsize[]="default";
      if($maintype=="about"){
        $imgsize[]="374,";
        $acceptedsize="";
      }elseif($maintype=="productservices"){
        $imgsize[]="default";
        $acceptedsize="";
        
      }elseif($maintype=="ceoprofile"){
        $imgsize[1]=",950";
        $acceptedsize="";
      }else{
        $imgsize[]=",300";
        $acceptedsize="";
      }
      $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
      $len=strlen($imgouts[0]);
      $imagepath=substr($imgouts[0], 1,$len);
      // medsize
      $len2=strlen($imgouts[1]);
       $imagepath2=substr($imgouts[1], 1,$len2);
      // get image size details
      list($width,$height)=getimagesize($imgouts[0]);
      $imagesize=$_FILES[''.$image.'']['size'];
      $filesize=$imagesize/1024;
      //// echo $filefirstsize;
      $filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
      if(strlen($filesize)>3){
      $filesize=$filesize/1024;
      $filesize=round($filesize,2); 
      $filesize="".$filesize."MB";
      }else{
      $filesize="".$filesize."KB";
      }
      //$coverpicid=getNextId("media"); 
      //maintype variants are original, medsize, thumb for respective size image.
      $cid=$entryid;
      if($imgid<1){
        $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,details,filesize,width,height)VALUES
        ('$cid','$maintype','coverphoto','image','$imagepath','$imagepath2','$filesize','$width','$height')";
        $mediarun=mysql_query($mediaquery)or die(mysql_error()." ".__LINE__);
        
      }else{
        $imgdata=getSingleMediaDataTwo($imgid);
        $prevpic=$imgdata['location'];
        $prevthumb=$imgdata['details'];
        $realprevpic=".".$prevpic;
        $realprevthumb=".".$prevthumb;
        if(file_exists($realprevpic)&&$realprevpic!=="."){
          unlink($realprevpic);
        }
        if(file_exists($realprevthumb)&&$realprevthumb!=="."){
          unlink($realprevthumb);
        }
        genericSingleUpdate("media","location",$imagepath,"id",$imgid);
        genericSingleUpdate("media","details",$imagepath2,"id",$imgid);
        genericSingleUpdate("media","filesize",$filesize,"id",$imgid);
        genericSingleUpdate("media","width",$width,"id",$imgid);
        genericSingleUpdate("media","height",$height,"id",$imgid);
        // echo "in here";
      }
    }
    /*extra data section for handling specific or rare cases as per maintype or subtype data nature*/
      if($maintype=="productservices"){
          // check for the banner image for the product tab
          $contentpic=$_FILES['prodbannerimg']['tmp_name'];
          if($contentpic!==""){
            $image="prodbannerimg";
            $imgpath[0]='../files/medsizes/';
            $imgpath[1]='../files/thumbnails/';
            $imgsize[0]="default";
            $imgsize[1]=",200";
            $acceptedsize="";
            $imgid=isset($_POST['prodbannerimgid'])?mysql_real_escape_string($_POST['prodbannerimgid']):0;
            $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
            $len=strlen($imgouts[0]);
            $imagepath=substr($imgouts[0], 1,$len);
            // medsize
            $len2=strlen($imgouts[1]);
             $imagepath2=substr($imgouts[1], 1,$len2);
            // get image size details
            list($width,$height)=getimagesize($imgouts[0]);
            $imagesize=$_FILES[''.$image.'']['size'];
            $filesize=$imagesize/1024;
            //// echo $filefirstsize;
            $filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
            if(strlen($filesize)>3){
              $filesize=$filesize/1024;
              $filesize=round($filesize,2); 
              $filesize="".$filesize."MB";
            }else{
              $filesize="".$filesize."KB";
            }
            //$coverpicid=getNextId("media"); 
            //maintype variants are original, medsize, thumb for respective size image.
            if($imgid<1){
              $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,details,filesize,width,height)VALUES
              ('$entryid','$maintype','productbanner','image','$imagepath','$imagepath2','$filesize','$width','$height')";
              $mediarun=mysql_query($mediaquery)or die(mysql_error()." ".__LINE__);
              
            }else{
              $imgdata=getSingleMediaDataTwo($imgid);
              $prevpic=$imgdata['location'];
              $prevthumb=$imgdata['details'];
              $realprevpic=".".$prevpic;
              $realprevthumb=".".$prevthumb;
              if(file_exists($realprevpic)&&$realprevpic!=="."){
                unlink($realprevpic);
              }
              if(file_exists($realprevthumb)&&$realprevthumb!=="."){
                unlink($realprevthumb);
              }
              genericSingleUpdate("media","location",$imagepath,"id",$imgid);
              genericSingleUpdate("media","details",$imagepath2,"id",$imgid);
              genericSingleUpdate("media","filesize",$filesize,"id",$imgid);
              genericSingleUpdate("media","width",$width,"id",$imgid);
              genericSingleUpdate("media","height",$height,"id",$imgid);
              // // echo "in here";
            }
          }
          // proceed to check update or insert subproducts for this entry
          if(isset($_POST['cursubproductcount'])){
            $spcount=$_POST['cursubproductcount'];
            for ($i=1; $i <=$spcount ; $i++) { 
              # code...
              $subcontentid=$_POST['subcontentid'.$i.''];
              $subcontenttitle=mysql_real_escape_string($_POST['subcontenttitle'.$i.'']);
              if($subcontentid>0){
                $subprodstatus=$_POST['subprodstatus'.$i.''];
                genericSingleUpdate("generalinfo","title",$subcontenttitle,"id",$subcontentid);
                genericSingleUpdate("generalinfo","status",$subprodstatus,"id",$subcontentid);
                // // echo $subprodstatus." statsout";
              }else{
                if($subcontenttitle!==""){
                  $entrydate=date("Y-m-d H:i:s");
                  $query="INSERT INTO generalinfo(maintype,subtype,title,entrydate)VALUES
                   ('subproductservices','$entryid','$subcontenttitle','$entrydate')";
                  $run=mysql_query($query)or die(mysql_error()." ".__LINE__);
                }
              }
            }
          }
      }
    /*end*/
    if($rurladmin==""){

		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');

	}

}else if ($entryvariant=="contententry") {
  # code...
  $maintype=mysql_real_escape_string($_POST['maintype']);
  $subtype=mysql_real_escape_string($_POST['subtype']);
  $title=isset($_POST['contenttitle'])?mysql_real_escape_string($_POST['contenttitle']):"";
  $intro=isset($_POST['contentintro'])?mysql_real_escape_string($_POST['contentintro']):"";
  $imgid=mysql_real_escape_string($_POST['coverid']);
  $content=isset($_POST['contentpost'])?mysql_real_escape_string(str_replace("../", "$host_addr",$_POST['contentpost'])):"";
  $status=isset($_POST['status'])?mysql_real_escape_string($_POST['status']):"active";
  if($intro!==""){
    $headerdescription = $intro;    
  }else{
    if($content!==""){
      $headerdescription = convert_html_to_text($content);
      $headerdescription=html2txt($headerdescription);
      $monitorlength=strlen($headerdescription);
      $headerdescription=$monitorlength<600?$headerdescription."...":substr($headerdescription, 0,600)."...";
    }else{
      $headerdescription="";
    }
  }
  genericSingleUpdate("generalinfo","title",$title,"id",$entryid);
  genericSingleUpdate("generalinfo","intro",$headerdescription,"id",$entryid);
  genericSingleUpdate("generalinfo","content",$content,"id",$entryid);
  genericSingleUpdate("generalinfo","status",$status,"id",$entryid);
  $contentpic=$_FILES['contentpic']['tmp_name'];
  if($contentpic!==""){
    $image="contentpic";
    $imgpath[]='../files/medsizes/';
    $imgpath[]='../files/thumbnails/';
    $imgsize[]="default";
    if($maintype=="about"){
      $imgsize[]="374,";
      $acceptedsize="";
      
    }elseif($maintype=="productservices"){
      $imgsize[]=",20";
      $acceptedsize="";
      
    }elseif($maintype=="ceoprofile"){
        $imgsize[1]=",950";
        $acceptedsize="";
    }else{
      $imgsize[]=",300";
      $acceptedsize="";
    }
    $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
    $len=strlen($imgouts[0]);
    $imagepath=substr($imgouts[0], 1,$len);
    // medsize
    $len2=strlen($imgouts[1]);
     $imagepath2=substr($imgouts[1], 1,$len2);
    // get image size details
    list($width,$height)=getimagesize($imgouts[0]);
    $imagesize=$_FILES[''.$image.'']['size'];
    $filesize=$imagesize/1024;
    //// echo $filefirstsize;
    $filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
    if(strlen($filesize)>3){
    $filesize=$filesize/1024;
    $filesize=round($filesize,2); 
    $filesize="".$filesize."MB";
    }else{
    $filesize="".$filesize."KB";
    }
    //$coverpicid=getNextId("media");	
    //maintype variants are original, medsize, thumb for respective size image.
    if($imgid<1){
      $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,details,filesize,width,height)VALUES
      ('$entryid','$maintype','coverphoto','image','$imagepath','$imagepath2','$filesize','$width','$height')";
      $mediarun=mysql_query($mediaquery)or die(mysql_error()." ".__LINE__);
    	
    }else{
      $imgdata=getSingleMediaDataTwo($imgid);
      $prevpic=$imgdata['location'];
      $prevthumb=$imgdata['details'];
      $realprevpic=".".$prevpic;
      $realprevthumb=".".$prevthumb;
      if(file_exists($realprevpic)&&$realprevpic!=="."){
        unlink($realprevpic);
      }
      if(file_exists($realprevthumb)&&$realprevthumb!=="."){
        unlink($realprevthumb);
      }
    	genericSingleUpdate("media","location",$imagepath,"id",$imgid);
		genericSingleUpdate("media","details",$imagepath2,"id",$imgid);
		genericSingleUpdate("media","filesize",$filesize,"id",$imgid);
		genericSingleUpdate("media","width",$width,"id",$imgid);
		genericSingleUpdate("media","height",$height,"id",$imgid);
    // echo "in here";
    }
  }
  /*extra data section for handling specific or rare cases as per maintype or subtype data nature*/
    if($maintype=="productservices"){
        // check for the banner image for the product tab
        $contentpic=isset($_FILES['prodbannerimg']['tmp_name'])?$_FILES['prodbannerimg']['tmp_name']:"";
        if($contentpic!==""){
          $image="prodbannerimg";
          $imgpath[0]='../files/medsizes/';
          $imgpath[1]='../files/thumbnails/';
          $imgsize[0]="default";
          $imgsize[1]=",200";
          $acceptedsize="";
          $imgid=isset($_POST['prodbannerimgid'])?mysql_real_escape_string($_POST['prodbannerimgid']):0;
          $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
          $len=strlen($imgouts[0]);
          $imagepath=substr($imgouts[0], 1,$len);
          // medsize
          $len2=strlen($imgouts[1]);
           $imagepath2=substr($imgouts[1], 1,$len2);
          // get image size details
          list($width,$height)=getimagesize($imgouts[0]);
          $imagesize=$_FILES[''.$image.'']['size'];
          $filesize=$imagesize/1024;
          //// echo $filefirstsize;
          $filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
          if(strlen($filesize)>3){
          $filesize=$filesize/1024;
          $filesize=round($filesize,2); 
          $filesize="".$filesize."MB";
          }else{
          $filesize="".$filesize."KB";
          }
          //$coverpicid=getNextId("media"); 
          //maintype variants are original, medsize, thumb for respective size image.
          if($imgid<1){
            $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,details,filesize,width,height)VALUES
            ('$entryid','$maintype','productbanner','image','$imagepath','$imagepath2','$filesize','$width','$height')";
            $mediarun=mysql_query($mediaquery)or die(mysql_error()." ".__LINE__);
            
          }else{
            $imgdata=getSingleMediaDataTwo($imgid);
            $prevpic=$imgdata['location'];
            $prevthumb=$imgdata['details'];
            $realprevpic=".".$prevpic;
            $realprevthumb=".".$prevthumb;
            if(file_exists($realprevpic)&&$realprevpic!=="."){
              unlink($realprevpic);
            }
            if(file_exists($realprevthumb)&&$realprevthumb!=="."){
              unlink($realprevthumb);
            }
            genericSingleUpdate("media","location",$imagepath,"id",$imgid);
            genericSingleUpdate("media","details",$imagepath2,"id",$imgid);
            genericSingleUpdate("media","filesize",$filesize,"id",$imgid);
            genericSingleUpdate("media","width",$width,"id",$imgid);
            genericSingleUpdate("media","height",$height,"id",$imgid);
            // // echo "in here";
          }
        }
        // proceed to check update or insert subproducts for this entry
        if(isset($_POST['cursubproductcountedit'])){
          $spcount=$_POST['cursubproductcountedit'];
          for ($i=1; $i <=$spcount ; $i++) { 
            # code...
            $subcontentid=$_POST['subcontentid'.$i.''];
            $subcontenttitle=mysql_real_escape_string($_POST['subcontenttitle'.$i.'']);
            if($subcontentid>0){
              $subprodstatus=$_POST['subprodstatus'.$i.''];
              genericSingleUpdate("generalinfo","title",$subcontenttitle,"id",$subcontentid);
              genericSingleUpdate("generalinfo","status",$subprodstatus,"id",$subcontentid);
                // // echo $subprodstatus." statsout";
              
            }else{
              if($subcontenttitle!==""){
                  $entrydate=date("Y-m-d H:i:s");
                  $query="INSERT INTO generalinfo(maintype,subtype,title,entrydate)VALUES
                   ('subproductservices','$entryid','$subcontenttitle','$entrydate')";
                  $run=mysql_query($query)or die(mysql_error()." ".__LINE__);
              }
            }
          }
        }
    }
  /*end*/
  if($rurladmin==""){

		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');

	}
}else if($entryvariant=="edituser"){
    $maintype="";
    $fullname=mysql_real_escape_string($_POST['fullname']);
    genericSingleUpdate("admin","fullname",$fullname,"id",$entryid);
    $username=mysql_real_escape_string($_POST['username']);
    genericSingleUpdate("admin","username",$username,"id",$entryid);
    $password=mysql_real_escape_string($_POST['password']);
    genericSingleUpdate("admin","password",$password,"id",$entryid);
    $accesslevel=$_POST['accesslevel'];
    genericSingleUpdate("admin","accesslevel",$accesslevel,"id",$entryid);
    $status=isset($_POST['status'])?mysql_real_escape_string($_POST['status']):"";
    genericSingleUpdate("admin","status",$status,"id",$entryid);

    $imgid=mysql_real_escape_string($_POST['coverid']);
    $contentpic=$_FILES['contentpic']['tmp_name'];
    if($contentpic!==""){
      $image="contentpic";
      $imgpath[]='../files/medsizes/';
      $imgpath[]='../files/thumbnails/';
      $imgsize[]="default";
      if($maintype=="about"){
        $imgsize[]="374,";
        $acceptedsize="";
        
      }else{
        $imgsize[]=",300";
        $acceptedsize="";
      }
      $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
      $len=strlen($imgouts[0]);
      $imagepath=substr($imgouts[0], 1,$len);
      // medsize
      $len2=strlen($imgouts[1]);
       $imagepath2=substr($imgouts[1], 1,$len2);
      // get image size details
      list($width,$height)=getimagesize($imgouts[0]);
      $imagesize=$_FILES[''.$image.'']['size'];
      $filesize=$imagesize/1024;
      //// echo $filefirstsize;
      $filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
      if(strlen($filesize)>3){
      $filesize=$filesize/1024;
      $filesize=round($filesize,2); 
      $filesize="".$filesize."MB";
      }else{
      $filesize="".$filesize."KB";
      }
      //$coverpicid=getNextId("media"); 
      //maintype variants are original, medsize, thumb for respective size image.
      if($imgid<1){
        $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,details,filesize,width,height)VALUES
        ('$entryid','adminuser','coverphoto','image','$imagepath','$imagepath2','$filesize','$width','$height')";
        $mediarun=mysql_query($mediaquery)or die(mysql_error()." ".__LINE__);
        
      }else{
        $imgdata=getSingleMediaDataTwo($imgid);
        $prevpic=$imgdata['location'];
        $prevthumb=$imgdata['details'];
        $realprevpic=".".$prevpic;
        $realprevthumb=".".$prevthumb;
        if(file_exists($realprevpic)&&$realprevpic!=="."){
          unlink($realprevpic);
        }
        if(file_exists($realprevthumb)&&$realprevthumb!=="."){
          unlink($realprevthumb);
        }
        genericSingleUpdate("media","location",$imagepath,"id",$imgid);
        // genericSingleUpdate("media","details",$imagepath2,"id",$imgid);
        genericSingleUpdate("media","filesize",$filesize,"id",$imgid);
        genericSingleUpdate("media","width",$width,"id",$imgid);
        genericSingleUpdate("media","height",$height,"id",$imgid);
        // // echo "in here";
      }
    }
    if($rurladmin==""){

		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');

	}
}else if($entryvariant=="edithomebanner"){
       // update/delete old slides    
        $cursurveyslidedelete=$_POST['status'.$entryid.''];

          $picout=$_FILES['slide'.$entryid.'']['tmp_name'];
          if ($cursurveyslidedelete!=="inactive") {
            # code...
                $headercaption=$_POST['headercaption'.$entryid.''];
                $minicaption=$_POST['minicaption'.$entryid.''];
                $linkaddress=$_POST['linkaddress'.$entryid.''];
                $linktitle=$_POST['linktitle'.$entryid.''];
                $captioncombo=$headercaption.'[|><|]'.$minicaption.'[|><|]'.$linkaddress.'[|><|]'.$linktitle;
                genericSingleUpdate("media","details","$captioncombo","id","$entryid");
                // // echo $captioncombo;F
            if($picout!=="") {
                # code...
                $image="slide$entryid";
                $imgpath[]='../files/medsizes/';
                $imgsize[]="default";
                $acceptedsize=",460";
                $imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
                $len=strlen($imgouts[0]);
                $imagepath=substr($imgouts[0], 1,$len);
                // get image size details
                list($width,$height)=getimagesize($imgouts[0]);
                $imagesize=$_FILES[''.$image.'']['size'];
                $filesize=$imagesize/1024;
                //// echo $filefirstsize;
                $filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
                if(strlen($filesize)>3){
                $filesize=$filesize/1024;
                $filesize=round($filesize,2); 
                $filesize="".$filesize."MB";
                }else{
                $filesize="".$filesize."KB";
                }
                //$coverpicid=getNextId("media");
                //maintype variants are original, medsize, thumb for respective size image.
                genericSingleUpdate("media","location","$imagepath","id","$entryid");
                genericSingleUpdate("media","filesize","$filesize","id","$entryid");
                genericSingleUpdate("media","width","$width","id","$entryid");
                genericSingleUpdate("media","height","$height","id","$entryid");

            }
          }else if($cursurveyslidedelete=="inactive"){
            // make sure there is at least one slide available, in case an admin goes into a delete frenzy
            // by making use of sentinel control variables "newcount"-for ensuring there is a new entry 
            // and slidedeletecount for ensuring the total number iof deleted entries are within a reasonable range 

            /*$dquery="UPDATE media SET status='inactive' AND maintype='none' AND ownertype='' AND  WHERE id='$picoutid'";
            $drun=mysql_query($dquery)or die(mysql_error()." Line 722");*/
            deleteMedia($entryid);
        }
        if($rurladmin==""){

			header('location:../admin/adminindex.php');
		}else{
			header('location:'.$rurladmin.'');

		}
}else if($entryvariant=="editteammember"){
     // update/delete old slides    
      $cursurveyslidedelete=$_POST['status'.$entryid.'']; 
      $picout=$_FILES['slide'.$entryid.'']['tmp_name'];
      if ($cursurveyslidedelete!=="inactive") {
        # code...
            $fullname=mysql_real_escape_string($_POST['fullname'.$entryid.'']);
            $position=mysql_real_escape_string($_POST['position'.$entryid.'']);
            $details=mysql_real_escape_string($_POST['details'.$entryid.'']);
            $qualifications=mysql_real_escape_string($_POST['qualifications'.$entryid.'']);
            if($fullname!==""&&$position!==""&&$details!==""&&$qualifications!==""){
              $captioncombo=$fullname.'[|><|]'.$position.'[|><|]'.$details.'[|><|]'.$qualifications;
              genericSingleUpdate("media","details","$captioncombo","id","$entryid");
              
            }
            // // echo $captioncombo;
        if($picout!=="") {
            # code...
            $image="slide$entryid";
            $imgpath[]='../files/medsizes/';
            $imgsize[]="default";
            $acceptedsize=",460";
            $imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
            $len=strlen($imgouts[0]);
            $imagepath=substr($imgouts[0], 1,$len);
            // get image size details
            list($width,$height)=getimagesize($imgouts[0]);
            $imagesize=$_FILES[''.$image.'']['size'];
            $filesize=$imagesize/1024;
            //// echo $filefirstsize;
            $filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
            if(strlen($filesize)>3){
            $filesize=$filesize/1024;
            $filesize=round($filesize,2); 
            $filesize="".$filesize."MB";
            }else{
            $filesize="".$filesize."KB";
            }
            //$coverpicid=getNextId("media");
            //maintype variants are original, medsize, thumb for respective size image.
            genericSingleUpdate("media","location","$imagepath","id","$entryid");
            genericSingleUpdate("media","filesize","$filesize","id","$entryid");
            genericSingleUpdate("media","width","$width","id","$entryid");
            genericSingleUpdate("media","height","$height","id","$entryid");

        }
      }else if($cursurveyslidedelete=="inactive"){
        // make sure there is at least one slide available, in case an admin goes into a delete frenzy
        // by making use of sentinel control variables "newcount"-for ensuring there is a new entry 
        // and slidedeletecount for ensuring the total number iof deleted entries are within a reasonable range 

        /*$dquery="UPDATE media SET status='inactive' AND maintype='none' AND ownertype='' AND  WHERE id='$picoutid'";
        $drun=mysql_query($dquery)or die(mysql_error()." Line 722");*/
        deleteMedia($entryid);
      }
      if($rurladmin==""){

			header('location:../admin/adminindex.php');
		}else{
			header('location:'.$rurladmin.'');

		}
}else if ($entryvariant=="editbranch") {
  # code... 
  $location=mysql_real_escape_string($_POST['locationtitle']);
  genericSingleUpdate("branches","location","$location","id","$entryid");
  $address=mysql_real_escape_string($_POST['address']);
  genericSingleUpdate("branches","address","$address","id","$entryid");
  $phonenumbers=mysql_real_escape_string($_POST['phonenumbers1']);
  genericSingleUpdate("branches","phonenumbers","$phonenumbers","id","$entryid");
  $email=mysql_real_escape_string($_POST['email1']);
  genericSingleUpdate("branches","email","$email","id","$entryid");
  $contactpersons=mysql_real_escape_string($_POST['contactpersons1']);
  genericSingleUpdate("branches","contactname","$contactpersons","id","$entryid");
  $status=mysql_real_escape_string($_POST['status']);
  genericSingleUpdate("branches","status","$status","id","$entryid");
  $extracontacts=mysql_real_escape_string($_POST['curcontactcountedit']);
  if($extracontacts>1){
    for($i=2;$i<=$extracontacts;$i++){
      $scontactsid=isset($_POST['scontactsid'.$i.''])?mysql_real_escape_string($_POST['scontactsid'.$i.'']):0;
      $sphonenumbers=mysql_real_escape_string($_POST['phonenumbers'.$i.'']);
      $semail=mysql_real_escape_string($_POST['email'.$i.'']);
      $scontactpersons=mysql_real_escape_string($_POST['contactpersons'.$i.'']);
      $sstatus=mysql_real_escape_string($_POST['subcontactstatus'.$i.'']);
      if($scontactsid>0){
        genericSingleUpdate("branchsubcontacts","phonenumbers","$sphonenumbers","id","$scontactsid");
        genericSingleUpdate("branchsubcontacts","email","$semail","id","$scontactsid");
        genericSingleUpdate("branchsubcontacts","contactname","$scontactpersons","id","$scontactsid");
        genericSingleUpdate("branchsubcontacts","status","$sstatus","id","$scontactsid");
      }else{
        if($sphonenumbers!==""&&$semail!==""&&$scontactpersons!==""){
            $subquery="INSERT INTO branchsubcontacts(bid,contactname,phonenumbers,email)VALUES('$entryid','$scontactpersons','$sphonenumbers','$semail')";
            $subrun=mysql_query($subquery)or die(mysql_error()." Line ".__LINE__);        
        }
      }
    }
  }
/*  $query="INSERT INTO branches(location,address,contactname,phonenumbers,email)VALUES('$location','$address','$contactpersons','$phonenumbers','$email')";
  $run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);*/
  if($rurladmin==""){

		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');

	}
}else if ($entryvariant=="recommendation"||$entryvariant=="clientelle"||$entryvariant=="testimonial"||$entryvariant=="editcnrnt") {
  # code...
  $type=$entryvariant;
    // $cnrid=getNextId('clientnrec');
    $picout=$_FILES['slide']['tmp_name'];
    $fullname=mysql_real_escape_string($_POST['fullname']);
    genericSingleUpdate("clientnrec","fullname","$fullname","id","$entryid");
    $position=mysql_real_escape_string($_POST['position']);
    genericSingleUpdate("clientnrec","position","$position","id","$entryid");
    $personalwebsite=mysql_real_escape_string($_POST['personalwebsite']);
    genericSingleUpdate("clientnrec","personalwebsite","$personalwebsite","id","$entryid");
    $companyname=mysql_real_escape_string($_POST['companyname']);
    genericSingleUpdate("clientnrec","companyname","$companyname","id","$entryid");
    $companywebsite=mysql_real_escape_string($_POST['companywebsite']);
    genericSingleUpdate("clientnrec","companywebsite","$companywebsite","id","$entryid");
    $details=mysql_real_escape_string($_POST['details']);
    genericSingleUpdate("clientnrec","content","$details","id","$entryid");
    $status=mysql_real_escape_string($_POST['status']);
    genericSingleUpdate("branches","status","$status","id","$entryid");
    // $captioncombo=$fullname.'[|><|]'.$position.'[|><|]'.$details.'[|><|]'.$qualifications;
    if($picout!=="") {
        # code...
            $image="slide";
            $imgpath[]='../files/medsizes/';
            $imgsize[]="default";
            $acceptedsize=",460";
            $imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
            $len=strlen($imgouts[0]);
            $imagepath=substr($imgouts[0], 1,$len);
            // get image size details
            list($width,$height)=getimagesize($imgouts[0]);
            $imagesize=$_FILES[''.$image.'']['size'];
            $filesize=$imagesize/1024;
            //// echo $filefirstsize;
            $filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
            if(strlen($filesize)>3){
            $filesize=$filesize/1024;
            $filesize=round($filesize,2); 
            $filesize="".$filesize."MB";
            }else{
            $filesize="".$filesize."KB";
            }
            $imgid=mysql_real_escape_string($_POST['imgid']);
            if($imgid<1){
              $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,details,filesize,width,height)VALUES
              ('$entryid','adminuser','coverphoto','image','$imagepath','$imagepath2','$filesize','$width','$height')";
              $mediarun=mysql_query($mediaquery)or die(mysql_error()." ".__LINE__);
              
            }else{
              $imgdata=getSingleMediaDataTwo($imgid);
              $prevpic=$imgdata['location'];
              $prevthumb=$imgdata['details'];
              $realprevpic=".".$prevpic;
              $realprevthumb=".".$prevthumb;
              if(file_exists($realprevpic)&&$realprevpic!=="."){
                unlink($realprevpic);
              }
              if(file_exists($realprevthumb)&&$realprevthumb!=="."){
                unlink($realprevthumb);
              }
            
              genericSingleUpdate("media","location",$imagepath,"id",$imgid);
              // genericSingleUpdate("media","details",$imagepath2,"id",$imgid);
              genericSingleUpdate("media","filesize",$filesize,"id",$imgid);
              genericSingleUpdate("media","width",$width,"id",$imgid);
              genericSingleUpdate("media","height",$height,"id",$imgid);
              // // echo "in here";
            }
    }
    /*$query="INSERT INTO clientnrec(type,companyname,fullname,position,officialwebsite,personalwebsite,content)VALUES('$type','$companyname','$fullname','$position','$companywebsite','$personalwebsite','$details')";
    // echo $query."<br>";
    $run=mysql_query($query)or die(mysql_error()." ".__LINE__);*/
  	if($rurladmin==""){
		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');
	}
}elseif ($entryvariant=="usereditform"||$entryvariant=="editrecruitadmin") {
	# code...
	$userdata=getSingleUser($entryid);
	$today=date("Y-m-d");
	$firstname=mysql_real_escape_string($_POST['firstname']);
	$middlename=mysql_real_escape_string($_POST['middlename']);
	$lastname=mysql_real_escape_string($_POST['lastname']);
	$fullname=$firstname." ".$middlename." ".$lastname;
	genericSingleUpdate("recruits","fullname",$fullname,"id",$entryid);
	$gender=isset($_POST['gender'])?mysql_real_escape_string($_POST['gender']):"";
		genericSingleUpdate("recruits","gender",$gender,"id",$entryid);
	$maritalstatus=isset($_POST['maritalstatus'])?mysql_real_escape_string($_POST['maritalstatus']):"";
		genericSingleUpdate("recruits","maritalstatus",$maritalstatus,"id",$entryid);
	$dob=isset($_POST['dob'])?mysql_real_escape_string($_POST['dob']):"";
		genericSingleUpdate("recruits","dob",$dob,"id",$entryid);
	$state=isset($_POST['state'])?mysql_real_escape_string($_POST['state']):"";
		genericSingleUpdate("recruits","state",$state,"id",$entryid);
	$phoneone=mysql_real_escape_string($_POST['phoneone']);
	$phonetwo=mysql_real_escape_string($_POST['phonetwo']);
	$phonethree=mysql_real_escape_string($_POST['phonethree']);
	$careerambition=mysql_real_escape_string($_POST['careerambition']);
		genericSingleUpdate("recruits","careerambition",$careerambition,"id",$entryid);
	$preferredjobtype=mysql_real_escape_string($_POST['preferredjobtype']);
		genericSingleUpdate("recruits","preferredjobtype",$preferredjobtype,"id",$entryid);
	$preferredjoblocation=mysql_real_escape_string($_POST['preferredjoblocation']);
		genericSingleUpdate("recruits","preferredjoblocation",$preferredjoblocation,"id",$entryid);
	$hobbies=mysql_real_escape_string($_POST['hobbies']);
		genericSingleUpdate("recruits","hobbies",$hobbies,"id",$entryid);
	$skills=mysql_real_escape_string($_POST['skills']);
		genericSingleUpdate("recruits","skills",$skills,"id",$entryid);
	$address=mysql_real_escape_string($_POST['addressone']);
		genericSingleUpdate("recruits","address",$address,"id",$entryid);
	if($phoneone=="(234) ___-___-____"){
	    $phoneone="";
	  }
	if($phonetwo=="(234) ___-___-____"){
	    $phonetwo="";
	  }
	if($phonethree=="(234) ___-___-____"){
	    $phonethree="";
	  }
	$phoneout=$phoneone."[|><|]".$phonetwo."[|><|]".$phonethree;
	genericSingleUpdate("recruits","phonenumbers",$phoneout,"id",$entryid);
	$email=mysql_real_escape_string($_POST['useremail']);
 	$emaildata=checkEmail($email,"recruits","email");
 	if($emaildata['testresult']=="unmatched"&&$email!==""&&str_replace(" ", "", $email)!==""){
		genericSingleUpdate("recruits","email",$email,"id",$entryid);	
 	}
	if($userdata['email']!==$email&&str_replace(" ", "", $email)!==""){
		genericSingleUpdate("recruits","status","inactive","id",$entryid);
		$confirmationlink=$host_addr."fjcregister.php?t=activate&uh=".md5($entryid).".".$entryid."&utm_email='.$email.'";
	  $title="Change of Account Email";
	  $content='
	      <p>Hello there '.$fullname.',<br>
	      	It seems you have changed your email address for your account on Adsbounty, please endeavour to reconfirm your account by <a href="'.$confirmationlink.'">Clicking here</a><br>
	      </p>
	      <p style="text-align:right;">Thank You.</p>
	  ';
	  $footer='
	   
	  ';
	  $emailout=generateMailMarkUp("muyiwaafolabi.com","$email","$title","$content","$footer","fjc");
	    // // echo $emailout['rowmarkup'];
	  if($host_email_send==true){
	  	if(mail($email,$title,$emailout['rowmarkup'],$headers)){

	  	}else{
	  		die("Confirmation Mail not sent, sorry!!!. Try using the back arrow in yout browser and trying what you were doing before again, it might resolve the issue, or hold on a few minutes and let us check it out.");
	  	}
	  }
	}
	$status=isset($_POST['status'])?mysql_real_escape_string($_POST['status']):"active";
	genericSingleUpdate("recruits","status",$status,"id",$entryid);
	/*$query="INSERT INTO recruits(usertype,state,lga,email,pword,phonenumber,businessname,businessdescription,businessaddress,regdate)
	VALUES('user','$state','$lga','$email','$password','$phoneout','$businessname','$businessdescription','$businessaddress',CURRENT_DATE())";
	$run=mysql_query($query)or die(mysql_error()." Line 58");*/
	// // echo $query;
    $bizlogo=$_FILES['profpic']['tmp_name'];
	if($bizlogo!==""){
		$image="profpic";
		$imgpath[]='../files/medsizes/';
		$imgsize[]="default";
		$acceptedsize=",240";
		$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
		$len=strlen($imgouts[0]);
		$imagepath=substr($imgouts[0], 1,$len);
		// get image size details
		list($width,$height)=getimagesize($imgouts[0]);
		$imagesize=$_FILES[''.$image.'']['size'];
		$filesize=$imagesize/1024;
		//// echo $filefirstsize;
		$filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
		if(strlen($filesize)>3){
		$filesize=$filesize/1024;
		$filesize=round($filesize,2);	
		$filesize="".$filesize."MB";
		}else{
		$filesize="".$filesize."KB";
		}
		if(str_replace(" ", "",$userdata['faceid'])!==""){
			$bizid=$userdata['faceid'];
			$prevpic=$userdata['facefile2'];
			$realprevpic=".".$prevpic;
			if(file_exists($realprevpic)&&$realprevpic!=="."){
				unlink($realprevpic);
			}
			genericSingleUpdate("media","location",$imagepath,"id",$bizid);
			genericSingleUpdate("media","filesize",$filesize,"id",$bizid);
			genericSingleUpdate("media","width",$width,"id",$bizid);
			genericSingleUpdate("media","height",$height,"id",$bizid);	
		}else{
			//maintype variants are original, medsize, thumb for respective size image.
			$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize,width,height)VALUES('$entryid','user','profpic','image','$imagepath','$filesize','$width','$height')";
			$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 722");
		}
		//$coverpicid=getNextId("media");
		//maintype variants are original, medsize, thumb for respective size image.
		/*$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize,width,height)VALUES('$cid','user','bizlogo','image','$imagepath','$filesize','$width','$height')";
		$mediarun=mysql_query($mediaquery)or die(mysql_error());*/
	}
	$password=$_POST['password'];
	$prevpassword=$_POST['prevpassword'];
	/*Educational and professional history data collection point*/
		$educationeditcount=mysql_real_escape_string($_POST['curqualificationeditcount']);
		$educationcount=mysql_real_escape_string($_POST['curqualificationcount']);
		// $type="educational";
		// for editting previous entries
		for($i=1;$i<=$educationeditcount;$i++){
			$qualificationid=mysql_real_escape_string($_POST['qualificationid'.$i.'']);
			$qualificationtype=mysql_real_escape_string($_POST['qualificationtype'.$i.'']);
			genericSingleUpdate("recruitacademicprohistory","type",$qualificationtype,"id",$qualificationid);
			$equalification=mysql_real_escape_string($_POST['qualification'.$i.'']);
			genericSingleUpdate("recruitacademicprohistory","qualification",$equalification,"id",$qualificationid);
			$einstitution=mysql_real_escape_string($_POST['institution'.$i.'']);
			genericSingleUpdate("recruitacademicprohistory","institution",$einstitution,"id",$qualificationid);
			$grade=$_POST['grade'.$i.'']!==""?mysql_real_escape_string($_POST['grade'.$i.'']):"N/A";
			genericSingleUpdate("recruitacademicprohistory","grade",$grade,"id",$qualificationid);
			$year=mysql_real_escape_string($_POST['oyear'.$i.'']);
			genericSingleUpdate("recruitacademicprohistory","year",$year,"id",$qualificationid);
			$attachid=0;
			
		}
		// for creating new entries
		$educationalout="";
		if($entryvariant!=="editrecruitadmin"){
			for($i>$educationeditcount;$i<=$educationcount;$i++){
				$qualificationtype=mysql_real_escape_string($_POST['qualificationtype'.$i.'']);
				$equalification=mysql_real_escape_string($_POST['qualification'.$i.'']);
				$einstitution=mysql_real_escape_string($_POST['institution'.$i.'']);
				$grade=$_POST['grade'.$i.'']!==""?mysql_real_escape_string($_POST['grade'.$i.'']):"N/A";
				$year=mysql_real_escape_string($_POST['oyear'.$i.'']);
				$attachid=0;
				if($year!==""&&$einstitution!==""&&$equalification!==""){
					$query="INSERT INTO recruitacademicprohistory (recruitid,type,year,institution,qualification,date,grade)
					VALUES('$entryid','$qualificationtype','$year','$einstitution','$equalification',CURRENT_DATE(),'$grade')";
					// echo $query."<br>";
					$run=mysql_query($query)or die(mysql_error()."Line 627");
					$educationalout.='
						<div class="paddsection">
		    				<p>Type: '.$qualificationtype.'</p>
		    				<p>Qualification: '.$equalification.'</p>
			            	<p>Institution: '.$einstitution.'</p>
		    				<p>Grade: '.$grade.'</p>
							<p>Year: '.$year.'</p>
						</div>
					';
					/*<p>Qualification Type: </p>
						<p>Qualification: <b></b></p>
						<p>Institution: </p>
						<p>Grade: ;</p>
						<p>Year: ;<br><br></p>*/
				}
			}
		}
		// $educationalout.="</table>";
	/*end*/
	/*Collect employment records*/
		$employmenteditcount=mysql_real_escape_string($_POST['curworkexperienceeditcount']);
		$employmentcount=mysql_real_escape_string($_POST['curworkexperiencecount']);
		for($i=1;$i<=$employmenteditcount;$i++){
			$wid=mysql_real_escape_string($_POST['workexperienceid'.$i.'']);
			$companyname=mysql_real_escape_string($_POST['companyname'.$i.'']);
			genericSingleUpdate("recruitemploymenthistory","companyname",$companyname,"id",$wid);
			$field=isset($_POST['field'.$i.''])?mysql_real_escape_string($_POST['field'.$i.'']):"";
			genericSingleUpdate("recruitemploymenthistory","field",$field,"id",$wid);
			$jobposition=mysql_real_escape_string($_POST['jobposition'.$i.'']);
			genericSingleUpdate("recruitemploymenthistory","field",$field,"id",$wid);
			$from=mysql_real_escape_string($_POST['jobfrom'.$i.'']);	
			genericSingleUpdate("recruitemploymenthistory","fromdate",$from,"id",$wid);
			$to=mysql_real_escape_string($_POST['jobto'.$i.'']);
			genericSingleUpdate("recruitemploymenthistory","todate",$to,"id",$wid);
		}
		// for creating new entries
		$workhistoryout="";
		if($entryvariant!=="editrecruitadmin"){
			for($i>$employmenteditcount;$i<=$employmentcount;$i++){
				$companyname=mysql_real_escape_string($_POST['companyname'.$i.'']);
				$field=mysql_real_escape_string($_POST['field'.$i.'']);
				$jobposition=mysql_real_escape_string($_POST['jobposition'.$i.'']);
				$from=mysql_real_escape_string($_POST['jobfrom'.$i.'']);	
				$to=mysql_real_escape_string($_POST['jobto'.$i.'']);
				if($companyname!==""/*&&$businessaddress!==""*/&&$jobposition!==""/*&&$rfl!==""&&$remuneration!==""*/&&$from!==""/*&&$to!==""*/){
					$query="INSERT INTO recruitemploymenthistory (recruitid,companyname,field,lastjobtitle,fromdate,todate)
					VALUES('$entryid','$companyname','$field','$jobposition','$from','$to')";
					// echo $query."<br>";
					$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
					$to=$_POST['jobto'.$i.'']!==""?mysql_real_escape_string($_POST['jobto'.$i.'']):"Ongoing";
					$workhistoryout.="
						<p>Company Name: $companyname;</p>
						<p>Field/Industry: $field;</p>
						<p>Job Position: $jobposition</p>
						<p>From: $from;</p>
						<p>To: $to;</p>

					";
				}
			}
		}
	/*end*/
	if($prevpassword!==""&&$prevpassword==$userdata['pword']&&$password!==""){
		genericSingleUpdate("recruits","pword",$password,"id",$entryid);
		// genericSingleUpdate("recruits","status","inactive","id",$entryid);
		// clear out user content, basically log them out and send em to the login page
	    unset($_SESSION['recruiti']);
	    unset($_SESSION['recruith']);
	    header('location:../frontiersjobconnect.php?t=passwordchange');
	}
	if($entryvariant=="usereditform"){
    	header('location:../fjcrecruits.php');
	}else if($entryvariant=="editrecruitadmin"){
		if($rurladmin==""){
			header('location:../admin/adminindex.php');
		}else{
			header('location:'.$rurladmin.'');

		}
	}

}
?>