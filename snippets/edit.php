<?php
include('connection.php');
// so we can get @ session variables
if(session_id() == ''){
	session_start();
}
require_once 'html2text.class.php';
$entryvariant=mysql_real_escape_string($_POST['entryvariant']);
$rurladmin=isset($_SESSION['rurladmin'])&&$_SESSION['rurladmin']!==""?$_SESSION['rurladmin']:"";
if(strpos($rurladmin, "fjcadmin")){
	$rurladmin="";
}
$entryid=$_POST['entryid'];
$userrequest="";
if(isset($_POST['userrequest'])&&$_POST['userrequest']!==""){
	$userrequest=$_POST['userrequest'];
}
// echo $entryvariant;
if($entryvariant=="editbooking"){

}if ($entryvariant=="editblogtype") {
	# code...
	$compid=1;
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
		if($entryid==1){
			$title=''.$host_default_blog_name.' | '.$host_website_name.'';
			$page='blog.php';
		}else{
			$title=''.$blogname.' | '.$host_website_name.'';
			$page=''.strtolower($blogname).'.php';
		}

		if(file_exists("../feeds/rss/".$outs['rssname'].".xml")){
			if($rssname!==$outs['rssname']){
				rename("../feeds/rss/".$outs['rssname'].".xml","../feeds/rss/".$rssname.".xml");
				
			}
			
		}else{
			$landingpage=$host_target_addr.$page;
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
			$rssfooter='</channel></rss>';
			$rsscontent=$rssheader.$rssfooter;
			// $handle=fopen("../".$blogname.".php","w")or die('cant open the file');
			$handle2=fopen("../feeds/rss/".$rssname.".xml","w")or die('cant open the file');
			fwrite($handle2,$rsscontent);	
			// fclose($handle);
			fclose($handle2);
				// echo $query;
			$blogid=$outs['blogtypeid'];
			$query2="INSERT INTO rssheaders (blogtypeid,headerdetails,footerdetails)
			VALUES('$blogid','$rssheader','$rssfooter')";
			$run2=mysql_query($query2)or die(mysql_error()."Line ".__LINE__);	
		}

		/*end*/
		
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
		if(file_exists("../blog/".$outs['foldername']."")){
			rename("../blog/".$outs['foldername']."","../blog/".$foldername."");
		}else{
			mkdir('../blog/'.$foldername.'/',0777);
		}
	}
	//if the blogs description has changed
	if($blogdescription!==""&&$blogdescription!==$outs['description']){
	if($entryid==1){
		$title=''.$host_default_blog_name.' | '.$host_website_name.'';
		$page='blog.php';
	}else{
		$title=''.$outs['name'].' | '.$host_website_name.'';
		$page=''.$outs['name'].'.php';
	}
	$landingpage=$host_target_addr.$page;
	$title=mysql_real_escape_string($title);
	$blogdescription=mysql_real_escape_string($blogdescription);
	genericSingleUpdate("blogtype","description",$blogdescription,"id",$entryid);

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
		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=1&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=1&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}
}elseif ($entryvariant=="editblogcategory") {
	$compid=2;
	$outs=getSingleBlogCategory($entryid);
	$blogid=$outs['blogtypeid'];
	$outstwo=getSingleBlogType($outs['blogtypeid']);
	$blogcategoryname=mysql_real_escape_string($_POST['name']);
	$title=''.$outstwo['name'].' | '.$host_website_name.'';
	$title=mysql_real_escape_string($title);
	
	if($blogcategoryname!==""&&$blogcategoryname!==$outs['catname']){
		$landingpage=$host_target_addr."category.php?cid=".$entryid;
		$pattern2='/[\n\s\$!#\%\^<>@\(\),\'\"\/\%\*\{\}\&\[\]\?\_\-\+\=:;’]/';
		$rssname=preg_replace($pattern2,"", $blogcategoryname);
		$rssname=$outs['blogtypeid'].strtolower($rssname);
		$rssname=clean(mysql_real_escape_string($rssname));	

		$rssheader='<?xml version="1.0" encoding="utf-8"?>
		<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
		<channel>
		<title>'.$title.'</title>
		<atom:link href="'.$host_target_addr.'feeds/rss/'.$rssname.'.xml" rel="self" type="application/rss+xml"/>
		<description>
			Category: '.mysql_real_escape_string($outs['catname']).'
		</description>
		<link>'.$landingpage.'</link>';
		$rssfooter='</channel></rss>';

		$rsscontent=$rssheader.$rssfooter;

		$rsstest=stripslashes($rssname);
		// echo $rssname."<br>";
		// echo $outs['catname']."<br>";
		if(file_exists("../feeds/rss/".$outs['rssname'].".xml")){
			rename("../feeds/rss/".$outs['rssname'].".xml","../feeds/rss/".$rssname.".xml");
		}else{
			// there was no previous xml file found for the current category
			$handle2=fopen("../feeds/rss/".$rssname.".xml","w")or die('cant open the file');
			fwrite($handle2,$rsscontent);
			fclose($handle2);
		}
		
		genericSingleUpdate("rssheaders","headerdetails",$rssheader,"blogcatid",$entryid);
		genericSingleUpdate("blogcategories","rssname",$rssname,"id",$entryid);
		genericSingleUpdate("blogcategories","catname",$blogcategoryname,"id",$entryid);
		// this repopulates the currently changed category rss file contents to the new
		// name it has
		writeRssData(0,$entryid);
	}

	$status=mysql_real_escape_string($_POST['status']);
	genericSingleUpdate("blogcategories","status",$status,"id",$entryid);

	
	$subtext=mysql_real_escape_string($_POST['subtext']);
	genericSingleUpdate("blogcategories","subtext",$subtext,"id",$entryid);
	
	$imgid=isset($_POST['profpicid'])?mysql_real_escape_string($_POST['profpicid']):0;
	$profpicdelete=isset($_POST['profpicdelete'])?$_POST['profpicdelete']:"";
	
	$profpic=$_FILES['profpic']['tmp_name'];
	
	if($_FILES['profpic']['tmp_name']!==""&&$profpicdelete==""){
		$image="profpic";
		$imgpath[0]='../files/originals/';
		$imgpath[1]='../files/medsizes/';
		$imgpath[2]='../files/thumbnails/';
		$imgsize[0]="default";
		$imgsize[1]=",300";
		$imgsize[2]=",100";
		$acceptedsize="";
		
		$imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
		$imagepath=substr($imgouts[0], 1,strlen($imgouts[0]));//original

		$imagepath2=substr($imgouts[1], 1,strlen($imgouts[1]));//medsize

		$imagepath3=substr($imgouts[2], 1,strlen($imgouts[2]));//thumbnail

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

		if($imgid<1){
			// store the category image in the database
			$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,
				medsize,thumbnail,filesize,width,height)
				VALUES('$entryid','blogcategory','original','image','$imagepath','$imagepath2',
					'$imagepath3','$filesize','$width','$height')";
		
			$mediarun=mysql_query($mediaquery)or die(mysql_error()." LINE ".__LINE__);
		}else{
			
			$imgdata=getSingleMediaDataTwo($imgid);
			$prevpic=$imgdata['location'];
			$prevmed=$imgdata['medsize'];
			$prevthumb=$imgdata['thumbnail'];
			$realprevpic=".".$prevpic;
			$realprevmed=".".$prevmed;
			$realprevthumb=".".$prevthumb;
			if(file_exists($realprevpic)&&$realprevpic!=="."){
			  unlink($realprevpic);
			}
			if(file_exists($realprevmed)&&$realprevmed!=="."){
			  unlink($realprevmed);
			}
			if(file_exists($realprevthumb)&&$realprevthumb!=="."){
			  unlink($realprevthumb);
			}

			// update category image data
			genericSingleUpdate("media","location",$imagepath,"id",$imgid);
			genericSingleUpdate("media","medsize",$imagepath2,"id",$imgid);
			genericSingleUpdate("media","thumbnail",$imagepath3,"id",$imgid);
			genericSingleUpdate("media","filesize",$filesize,"id",$imgid);
			genericSingleUpdate("media","width",$width,"id",$imgid);
			genericSingleUpdate("media","height",$height,"id",$imgid);
			
			
		}
		
	}

	if($profpicdelete!==""&&$imgid>0){
		deleteMedia($imgid);
	}
	$notid=$entryid;
	// prepare content for the notification redirect portion
	$action="Update";
	$actiontype="blogcategories";
	$actiondetails="Blog category Updated";
	
	$notrdtype="none";//stops redirection

	$nonottype="none";// stops notification table update

	// bring in the nrsection.php file
	include('nrsection.php');
	

}elseif ($entryvariant=="editblogpost") {
	# code...
	$compid=3;
	$currenttime = new DateTime(); // current time 
	$outs=getSingleBlogEntry($entryid);
	$outblog=getSingleBlogType($outs['blogtypeid']);
	$introparagraph=mysql_real_escape_string($_POST['introparagraph']);
	$blogentry=mysql_real_escape_string($_POST['blogentry']);
	$blogentrytype=mysql_real_escape_string($_POST['blogentrytype']);
	$seometakeywords=isset($_POST['seokeywords'])?mysql_real_escape_string($_POST['seokeywords']):"";
	genericSingleUpdate("blogentries","seometakeywords","$seometakeywords","id",$entryid);
	$seometadescription=isset($_POST['seodescription'])?mysql_real_escape_string($_POST['seodescription']):"";
	genericSingleUpdate("blogentries","seometadescription","$seometadescription","id",$entryid);
	
	$pwrdd=isset($_POST['pwrdd'])?
	mysql_real_escape_string($_POST['pwrdd']):"nopwrd";
	genericSingleUpdate("blogentries","pwrdd","$pwrdd","id",$entryid);

	$pwrd=isset($_POST['pwrd'])?
	mysql_real_escape_string($_POST['pwrd']):"";
	genericSingleUpdate("blogentries","pwrd","$pwrd","id",$entryid);

	$commentsonoff=isset($_POST['commentsonoff'])?
	mysql_real_escape_string($_POST['commentsonoff']):"";
	genericSingleUpdate("blogentries","commentsonoff","$commentsonoff","id",$entryid);
	
	$commenttype=isset($_POST['commenttype'])?
	mysql_real_escape_string($_POST['commenttype']):"normal";
	genericSingleUpdate("blogentries","commenttype","$commenttype","id",$entryid);

	$featured=isset($_POST['featured'])?
	mysql_real_escape_string($_POST['featured']):"";
	genericSingleUpdate("blogentries","featuredpost","$featured","id",$entryid);

	$tags=isset($_POST['tags'])?
	mysql_real_escape_string($_POST['tags']):"";
	genericSingleUpdate("blogentries","tags","$tags","id",$entryid);


	$schedulestatus=isset($_POST['schedulestatus'])?mysql_real_escape_string($_POST['schedulestatus']):"";
	$scheduledate=isset($_POST['scheduledate'])?mysql_real_escape_string($_POST['scheduledate']):"";
	$scheduletime=isset($_POST['scheduletime'])?mysql_real_escape_string($_POST['scheduletime']):"";

	// sentinel control for all things schedule values are null, on, off
	// off means that no further schedule operation should be carried out as the post
	// period for the scheduled post has most likely been exceeded
	$schedulesentinel="";

	$rprev=isset($_POST['scheduletime'])?
	mysql_real_escape_string($_POST['scheduletime']):"none";
	
	//make sure schedule date and time have appropriate values
	$scheduletime!==""&&$scheduledate!==""&&
	($schedulestatus=="on"||$schedulestatus=="yes")?
	$scheduletime=$scheduletime.":00":$scheduletime="";
	
	$scheduledate==""&&($schedulestatus=="on"||$schedulestatus=="yes")?
	$scheduledate=date('Y-m-d H:i:s', strtotime('1 day')):$scheduledate;
	
	$scheduletime==""&&($schedulestatus=="on"||$schedulestatus=="yes")?
	$scheduletime="08:00:00":$scheduletime.":00";
	
	$fullschedulepostperiod="";
	
	

	if($schedulestatus=="on"||$schedulestatus=="yes"){
		// check the scheduled time to make sure there are no descrpancy
		// check if there was a previous schedule time and if this has passed ignore any 
		// new schedule time
		$oldtime="";
		if($outs['postperiod']!=="0000-00-00 00:00:00"){
			$oldtime=new DateTime($outs['postperiod']);
		}
		if($rprev!=="none"){
			$test=explode(":", $scheduletime);
			if(count($test)<3){
				$scheduletime=$scheduletime.":00";
			}
		}
		// check to see if the previous time set for the current blog post is not
		// past, otherwise set the schedulesentinel variable to off allowing the
		// post to be published
		if($oldtime!==""){
			if($oldtime<$currenttime){
				$schedulesentinel="off";
			}
		}

		//verify that the set date has not passed
		$datetime1 = new DateTime("$scheduledate"); // specified scheduled time
		if($rprev!=="none"){
			$datetime1 = new DateTime("$scheduledate $scheduletime"); // specified scheduled time
		}

		$datetime2 = new DateTime(); // current time 
		
		if($datetime2<$datetime1&&$schedulesentinel==""){
			//if the current date time is less than the one specified then the user 
			// chose a valid future date proceeed to run update
			$fullschedulepostperiod=$scheduledate;
			if($rprev!=="none"){
				$fullschedulepostperiod=$scheduledate." ".$scheduletime;
			}
			// $fullschedulepostperiod=$scheduledate." ".$scheduletime;
			// echo $fullschedulepostperiod;
			genericSingleUpdate("blogentries","postperiod","$fullschedulepostperiod",
				"id",$entryid);

		}
	}
	// $enddate=date('Y-m-d', strtotime('2 days'));
	// echo $blogentrytype."<br>";
	$title=mysql_real_escape_string($_POST['title']);
	$status=isset($_POST['status'])?mysql_real_escape_string($_POST['status']):"active";
	$coverstyle=isset($_POST['coverstyle'])?mysql_real_escape_string($_POST['coverstyle']):"";
	genericSingleUpdate("blogentries","coverphotoset",$coverstyle,"id",$entryid);

	$newpath="";
	$modified="";
	/*echo $title."<br>";
	echo $introparagraph."<br>";
	echo $blogentry."<br>";
	echo $outs['title']."<br>";
	echo $outs['introparagraph']."<br>";*/
	stripslashes($introparagraph)== $outs['title']? $echo="true":$echo="false";

	$pattern2='/[\n\$!#\%\^<>@\(\),\'\"\/\%\*\{\}\&\[\]\?\_\-\+\=:;’]/';
	$pagename=clean($_POST['title']);

	$betype="";
	$becode="";
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
		// legacy
		if($rprev!=="none"){
			$realprevpic=$outs['bannermain'];
			$realprevpicthumb=$outs['bannerthumb'];
		}else{
			// update
			$realprevpic=$outs['bannerdata'][0]['location'];
			$realprevpicmed=$outs['bannerdata'][0]['medsize'];
			$realprevpicthumb=$outs['bannerdata'][0]['thumbnail'];
		}

		// legacy behaviour
		if($rprev!=="none"){
			genericSingleUpdate("blogentries","introparagraph",$title,"id",$entryid);
		}

		if($_FILES['bannerpic']['tmp_name']!==""){
			$bannerid=$_POST['bannerid'];
			$image="bannerpic";
			$imgpath[0]='../files/originals/';
			$imgpath[1]='../files/medsizes/';
			$imgpath[2]='../files/thumbnails/';
			$imgsize[0]="default";
			$imgsize[1]="300,";
			$imgsize[2]="150,";
			$acceptedsize="";
			$imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
			$imagepath=substr($imgouts[0], 1,strlen($imgouts[0]));
			$imagepath2=substr($imgouts[1], 1,strlen($imgouts[1]));
			$imagepath3=substr($imgouts[2], 1,strlen($imgouts[2]));

			// get image size details
			$filedata=getFileDetails($imgouts[0],"image");
			$filesize=$filedata['size'];
			$width=$filedata['width'];
			$height=$filedata['height'];
			// echo '<img src="'.$imgouts[0].'"> '.$filesize.' '.$width.' '.$height.'';
			// get the cover photo's media table id for storage with the blog entry
			genericSingleUpdate("media","location",$imagepath,"id",$bannerid);
			if($rprev!=="none"){
				genericSingleUpdate("media","details",$imagepath2,"id",$bannerid);
			}else{
				genericSingleUpdate("media","medsize",$imagepath2,"id",$bannerid);
				genericSingleUpdate("media","thumbnail",$imagepath3,"id",$bannerid);
				
			}

			genericSingleUpdate("media","filesize",$filesize,"id",$bannerid);
			genericSingleUpdate("media","width",$width,"id",$bannerid);
			genericSingleUpdate("media","height",$height,"id",$bannerid);	

			if(file_exists($realprevpic)){
				unlink($realprevpic);
			}

			// update
			if($rprev=="none"){
				if(file_exists($realprevpicmed)){
					unlink($realprevpicmed);
				}
			}

			if(file_exists($realprevpicthumb)){
				unlink($realprevpicthumb);
			}

			$modified="yes";
		}
	}
	
	if($blogentrytype=="gallery"){
		
		// legacy behaviour
		if($rprev!=="none"){
			genericSingleUpdate("blogentries","introparagraph",$title,"id",$entryid);
		}
		// legacy behaviour

		if($rprev!=="none"){
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
			        $imgpath[0]='../files/originals/';
			        $imgpath[1]='../files/medsizes/';
			        $imgpath[2]='../files/thumbnails/';
			        $imgsize[0]="default";
			        $imgsize[1]=",530";
			        $imgsize[2]=",150";

			        // // echo count($imgsize);
			        $acceptedsize="";
			        $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
			        $len=strlen($imgouts[0]);
			        // // echo $imgouts[0]."<br>";
			        $imagepath=substr($imgouts[0], 1,$len);
			        $len2=strlen($imgouts[1]);
			        // // echo $imgouts[1]."<br>";
			        $imagepath2=substr($imgouts[1], 1,$len2);
			        $len3=strlen($imgouts[2]);
			        // // echo $imgouts[1]."<br>";
			        $imagepath3=substr($imgouts[2], 1,$len3);
			        // get image size details
			        $filedata=getFileDetails($imgouts[0],"image");
			        $filesize=$filedata['size'];
			        $width=$filedata['width'];
			        $height=$filedata['height'];
			        $coverid="";
			        // insert current blog gallery content into database as original image and thumbnail
			        $mediaquery="INSERT INTO media
			        (ownerid,ownertype,maintype,mediatype,location,medsize,thumbnail,details,filesize,width,height)
			        VALUES
			        ('$entryid','blogentry','gallerypic','image','$imagepath','$imagepath2','$imagepath3','$imagepath3','$filesize','$width','$height')";
			        $mediarun=mysql_query($mediaquery)or die(mysql_error());
			      }
			    }
			}
		}
		$bloggallerycount=isset($_POST['bloggallerycount'])?
		mysql_real_escape_string($_POST['bloggallerycount']):0;
		
		// update
		if($bloggallerycount>0){
			$galentrycount=0;
			for($i=1;$i<=$bloggallerycount;$i++){
				$picout=isset($_FILES['galimage'.$i.'']['tmp_name'])?
				$_FILES['galimage'.$i.'']['tmp_name']:"";
				$imgid=isset($_POST['galimage'.$i.'_id'])?$_POST['galimage'.$i.'_id']:0;
			    $coverdelete=isset($_POST['galimage'.$i.'_delete'])?
				$_POST['galimage'.$i.'_delete']:"";
				$galcaption=isset($_POST['caption'.$i.''])?
				mysql_real_escape_string($_POST['caption'.$i.'']):"";
				$galdetails=isset($_POST['details'.$i.''])?
				mysql_real_escape_string($_POST['details'.$i.'']):"";
				if($picout!==""&&$coverdelete==""){
					// perform insertion or update of uploaded image
					$caption=mysql_real_escape_string($_POST["caption$i"]);
					$details=mysql_real_escape_string($_POST["details$i"]);

					$image="galimage$i";

					$imgpath[0]='../files/originals/';
				    $imgpath[1]='../files/medsizes/';
				    $imgpath[2]='../files/thumbnails/';
				    $imgsize[0]="default";
				    $imgsize[1]=",240";
				    $imgsize[2]=",85";
				    $acceptedsize="";
				    $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);

				    $len=strlen($imgouts[0]);
				    $len2=strlen($imgouts[1]);
				    $len3=strlen($imgouts[2]);
				    $imagepath=substr($imgouts[0], 1,$len);
				    $imagepath2=substr($imgouts[1], 1,$len2);
				    $imagepath3=substr($imgouts[2], 1,$len3);

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

				    if($imgid>0){
				    	$imgdata=getSingleMediaDataTwo($imgid);
						$prevpic=$imgdata['location'];
						$prevmedsize=$imgdata['medsize'];
						$prevthumb=$imgdata['thumbnail'];
						$realprevpic=".".$prevpic;
						$realprevmedsize=".".$prevmedsize;
						$realprevthumb=".".$prevthumb;
						if(file_exists($realprevpic)&&$realprevpic!=="."){
							unlink($realprevpic);
						}
						if(file_exists($realprevmedsize)&&$realprevmedsize!=="."){
							unlink($realprevmedsize);
						}
						if(file_exists($realprevthumb)&&$realprevthumb!=="."){
							unlink($realprevthumb);
						}
						genericSingleUpdate("media","location",$imagepath,"id",$imgid);
						genericSingleUpdate("media","medsize",$imagepath2,"id",$imgid);
						genericSingleUpdate("media","thumbnail",$imagepath3,"id",$imgid);
						genericSingleUpdate("media","filesize",$filesize,"id",$imgid);
						genericSingleUpdate("media","width",$width,"id",$imgid);
						genericSingleUpdate("media","height",$height,"id",$imgid);

					}else if($imgid==0){
					    //maintype variants are original, medsize, thumb for respective size image.
					    $mediaquery="INSERT INTO media
					    (ownerid,ownertype,maintype,mediatype,location,medsize,thumbnail,
					    title,details,filesize,width,height)
						VALUES
						('$entryid','blogentry','gallerypic','image','$imagepath',
						'$imagepath2','$imagepath3','$caption','$details','$filesize',
						'$width','$height')";
					    // echo $mediaquery."<br>";
					    $mediarun=mysql_query($mediaquery)or die(mysql_error()." ".__LINE__."<br>$mediaquery<br>");
					}
				}else if($coverdelete=="inactive"){
					// delete the current gallery image
					if($imgid>0){
						deleteMedia($imgid);
					}
				}
				if($coverdelete==""){
					if($imgid>0){
						genericSingleUpdate("media","title",$galcaption,"id",$imgid);
						genericSingleUpdate("media","details",$galdetails,"id",$imgid);

					}
				}

			}
		}
		
	}

	if($blogentrytype=="video"){
		// legacy
		if($rprev!=="none"){
			if (isset($_FILES['videoflv']['tmp_name'])&&$_FILES['videoflv']['tmp_name']!=="") {
				# code...
				$localaudioid=$_POST['localvideoflvid'];
				$outsaudio=simpleUpload('videoflv','../files/videos/');
				$audiofilepath=$outsaudio['filelocation'];
				$len=strlen($audiofilepath);
				$audiofilepath=substr($audiofilepath, 1,$len);
				$audiofilesize=$outsaudio['filesize'];
				if($localaudioid>0){
					$mediadata=getSingleMediaDataTwo($localaudioid);
					$realprevfile=$host_addr.$mediadata['location'];
					if(file_exists($realprevfile)){
						unlink($realprevfile);
					}
					$orderfield[]="ownerid";
					$orderfield[]="ownertype";
					$orderfield[]="maintype";
					$orderfield[]="mediatype";
					$ordervalues[]=$localaudioid;
					$ordervalues[]="blogentry";
					$ordervalues[]="blogentryvideo";
					$ordervalues[]="videoflv";
					genericSingleUpdate("media","location",$audiofilepath,$orderfield,$ordervalues);
					genericSingleUpdate("media","filesize",$audiofilesize,$orderfield,$ordervalues);
				}else{
					$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,
						location,filesize)VALUES('$entryid','blogentry','blogentryvideo',
						'videoflv','$audiofilepath','$audiofilesize')";
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
				if($localaudioid>0){
					$mediadata=getSingleMediaDataTwo($localaudioid);
					$realprevfile=$host_addr.$mediadata['location'];
					if(file_exists($realprevfile)){
						unlink($realprevfile);
					}
					$orderfield[]="ownerid";
					$orderfield[]="ownertype";
					$orderfield[]="maintype";
					$orderfield[]="mediatype";
					$ordervalues[]=$entryid;
					$ordervalues[]="blogentry";
					$ordervalues[]="blogentryvideo";
					$ordervalues[]="videomp4";
					genericSingleUpdate("media","location",$audiofilepath,$orderfield,$ordervalues);
					genericSingleUpdate("media","filesize",$audiofilesize,$orderfield,$ordervalues);
				}else{
					$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)VALUES('$entryid','blogentry','blogentryvideo','videomp4','$audiofilepath','$audiofilesize')";
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
				if($localaudioid>0){
					$mediadata=getSingleMediaDataTwo($localaudioid);
					$realprevfile=$host_addr.$mediadata['location'];
					if(file_exists($realprevfile)){
						unlink($realprevfile);
					}
					$orderfield[]="ownerid";
					$orderfield[]="ownertype";
					$orderfield[]="maintype";
					$orderfield[]="mediatype";
					$ordervalues[]=$entryid;
					$ordervalues[]="blogentry";
					$ordervalues[]="blogentryvideo";
					$ordervalues[]="video3gp";
					genericSingleUpdate("media","location",$audiofilepath,$orderfield,$ordervalues);
					genericSingleUpdate("media","filesize",$audiofilesize,$orderfield,$ordervalues);
				}else{
					$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)VALUES('$entryid','blogentry','blogentryvideo','video3gp','$audiofilepath','$audiofilesize')";
					$mediarun2=mysql_query($mediaquery2)or die(mysql_error());
				}
			}
			if (isset($_FILES['videowebm']['tmp_name'])&&$_FILES['videowebm']['tmp_name']!=="") {
				# code...
				$localaudioid=$_POST['localvideowebmid'];
				$outsaudio=simpleUpload('videowebm','../files/videos/');
				$audiofilepath=$outsaudio['filelocation'];
				$len=strlen($audiofilepath);
				$audiofilepath=substr($audiofilepath, 1,$len);
				$audiofilesize=$outsaudio['filesize'];
				if($localaudioid>0){
					$mediadata=getSingleMediaDataTwo($localaudioid);
					$realprevfile=$host_addr.$mediadata['location'];
					if(file_exists($realprevfile)){
						unlink($realprevfile);
					}
					$orderfield[]="ownerid";
					$orderfield[]="ownertype";
					$orderfield[]="maintype";
					$orderfield[]="mediatype";
					$ordervalues[]=$entryid;
					$ordervalues[]="blogentry";
					$ordervalues[]="blogentryvideo";
					$ordervalues[]="videowebm";
					genericSingleUpdate("media","location",$audiofilepath,$orderfield,$ordervalues);
					genericSingleUpdate("media","filesize",$audiofilesize,$orderfield,$ordervalues);
				}else{
					$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)VALUES('$entryid','blogentry','blogentryvideo','videowebm','$audiofilepath','$audiofilesize')";
					$mediarun2=mysql_query($mediaquery2)or die(mysql_error());
				}
			}
			
		}else{
			// update
			$blogvideocount=mysql_real_escape_string($_POST['blogvideocount']);
			if($blogvideocount>0){
				for($i=1;$i<=$blogvideocount;$i++){
					// setup default variables first
					$videofilepath1=""; // webm
					$videofilesize1=0; 
					$videofilepath2=""; // 3gp
					$videofilesize2=0;
					$videofilepath3=""; // flv
					$videofilesize3=0;
					$videofilepath4=""; // mp4
					$videofilesize4=0;

					// get current individual ids if available for each video file type
					isset($_POST['video_id'])?$vidid=mysql_real_escape_string($_POST['video_id']):$vidid=0;
					isset($_POST['video_map'])&&str_replace(" ", "",$_POST['video_map'])?
					$jsondata=$_POST['video_map']:$jsondata="";
					$viddata=array();
					
					// decode the JSON object if any
					if($vidid>0){
						
						// a map is available
						// $jsondata=stripslashes($jsondata);
						$viddata=JSONtoPHP($jsondata);

						// get the main level array
						$viddata=$viddata['arrayoutput'];
						
					}

					isset($_POST['videotype'])?
					$betype=mysql_real_escape_string($_POST['videotype']):
					$betype="";
					// update video type
					if(isset($viddata['videotype'])){
						
						// change the content of the indexes
						$viddata['videotype']=$betype;
					}
					
					isset($_POST['videocaption'])?
					$becaption=mysql_real_escape_string($_POST['videocaption']):
					$becaption="";

					isset($_POST['videoembed'])?
					$becode=mysql_real_escape_string($_POST['videoembed']):
					$becode="";

					// update embed code
					if(isset($viddata['videoembed'])){
						
						// change the content of the indexes
						$viddata['videoembed']=$becode;
					}

					// perform a delete if necessary
					$viddelete=isset($_POST['video_delete'])?
					$_POST['video_delete']:"";
					if($viddelete=="inactive"){
						deleteMedia($vidid);
					}


					// perform a delete for individual files
					$fwebmdelete=isset($_POST['videowebm_delete'])?$_POST['videowebm_delete']:"";
					if($fwebmdelete=="inactive"){

						// check to see if the map array is available and has a valid index
						// for the video entry
						if(isset($viddata['videowebm'])){
							
							$curv=$viddata['videowebm']['location'];
							$prevvid=$viddata['videowebm']['location'];
							$realprevvid=".".$prevvid;
							if(file_exists($realprevvid)&&$realprevvid!=="."){
								unlink($realprevvid);
							}

							// change the content of the indexes
							$viddata['videowebm']['location']="";
							$viddata['videowebm']['size']="0KB";
						}
					}

					if (isset($_FILES['videowebm']['tmp_name'])
						&&$_FILES['videowebm']['tmp_name']!==""
						&&$fwebmdelete=="") {
						# code...
						$outsvideo=simpleUpload('videowebm','../files/videos/');
						$videofilepath=$outsvideo['filelocation'];
						$len=strlen($videofilepath);
						$videofilepath1=substr($videofilepath, 1,$len);
						$videofilesize1=$outsvideo['filesize'];
						
						// check to see if the map array is available and has a valid index
						// for the video entry
						if(isset($viddata['videowebm'])){
							
							$curv=$viddata['videowebm']['location'];
							$prevvid=$viddata['videowebm']['location'];
							$realprevvid=".".$prevvid;
							if(file_exists($realprevvid)&&$realprevvid!=="."){
								unlink($realprevvid);
							}

							// change the content of the indexes
							$viddata['videowebm']['location']=$videofilepath1;
							$viddata['videowebm']['size']=$videofilesize1;
						}
					}


					// perform a delete for individual files
					$f3gpdelete=isset($_POST['video3gp_delete'])?$_POST['video3gp_delete']:"";
					if($f3gpdelete=="inactive"){

						// check to see if the map array is available and has a valid index
						// for the video entry
						if(isset($viddata['video3gp'])){
							
							$curv=$viddata['video3gp']['location'];
							$prevvid=$viddata['video3gp']['location'];
							$realprevvid=".".$prevvid;
							if(file_exists($realprevvid)&&$realprevvid!=="."){
								unlink($realprevvid);
							}

							// change the content of the indexes
							$viddata['video3gp']['location']="";
							$viddata['video3gp']['size']="0KB";
						}
					}

					if (isset($_FILES['video3gp']['tmp_name'])
						&&$_FILES['video3gp']['tmp_name']!==""
						&&$f3gpdelete=="") {
						# code...
						$outsvideo=simpleUpload('video3gp','../files/videos/');
						$videofilepath=$outsvideo['filelocation'];
						$len=strlen($videofilepath);
						$videofilepath2=substr($videofilepath, 1,$len);
						$videofilesize2=$outsvideo['filesize'];

						// check to see if the map array is available and has a valid index
						// for the video entry
						if(isset($viddata['video3gp'])){
							
							$curv=$viddata['video3gp']['location'];
							$prevvid=$viddata['video3gp']['location'];
							$realprevvid=".".$prevvid;
							if(file_exists($realprevvid)&&$realprevvid!=="."){
								unlink($realprevvid);
							}

							// change the content of the indexes
							$viddata['video3gp']['location']=$videofilepath2;
							$viddata['video3gp']['size']=$videofilesize2;
						}
					}


					// perform a delete for individual files
					$fflvdelete=isset($_POST['videoflv_delete'])?$_POST['videoflv_delete']:"";
					if($fflvdelete=="inactive"){

						// check to see if the map array is available and has a valid index
						// for the video entry
						if(isset($viddata['videoflv'])){
							
							$curv=$viddata['videoflv']['location'];
							$prevvid=$viddata['videoflv']['location'];
							$realprevvid=".".$prevvid;
							if(file_exists($realprevvid)&&$realprevvid!=="."){
								unlink($realprevvid);
							}

							// change the content of the indexes
							$viddata['videoflv']['location']="";
							$viddata['videoflv']['size']="0KB";
						}
					}

					if (isset($_FILES['videoflv']['tmp_name'])
						&&$_FILES['videoflv']['tmp_name']!==""
						&&$fflvdelete=="") {
						# code...
						$outsvideo=simpleUpload('videoflv','../files/videos/');
						$videofilepath=$outsvideo['filelocation'];
						$len=strlen($videofilepath);
						$videofilepath3=substr($videofilepath, 1,$len);
						$videofilesize3=$outsvideo['filesize'];
						
						// check to see if the map array is available and has a valid index
						// for the video entry
						if(isset($viddata['videoflv'])){
							
							$curv=$viddata['videoflv']['location'];
							$prevvid=$viddata['videoflv']['location'];
							$realprevvid=".".$prevvid;
							if(file_exists($realprevvid)&&$realprevvid!=="."){
								unlink($realprevvid);
							}

							// change the content of the indexes
							$viddata['videoflv']['location']=$videofilepath1;
							$viddata['videoflv']['size']=$videofilesize1;
						}
					}


					// perform a delete for individual files
					$fmp4delete=isset($_POST['videomp4_delete'])?$_POST['videomp4_delete']:"";
					if($fmp4delete=="inactive"){

						// check to see if the map array is available and has a valid index
						// for the video entry
						if(isset($viddata['videomp4'])){
							
							$curv=$viddata['videomp4']['location'];
							$prevvid=$viddata['videomp4']['location'];
							$realprevvid=".".$prevvid;
							if(file_exists($realprevvid)&&$realprevvid!=="."){
								unlink($realprevvid);
							}

							// change the content of the indexes
							$viddata['videomp4']['location']="";
							$viddata['videomp4']['size']="0KB";
						}
					}

					if (isset($_FILES['videomp4']['tmp_name'])
						&&$_FILES['videomp4']['tmp_name']!==""
						&&$fmp4delete=="") {
						# code...
						$outsvideo=simpleUpload('videomp4','../files/videos/');
						$videofilepath=$outsvideo['filelocation'];
						$len=strlen($videofilepath);
						$videofilepath4=substr($videofilepath, 1,$len);
						$videofilesize4=$outsvideo['filesize'];
						
						// check to see if the map array is available and has a valid index
						// for the video entry
						if(isset($viddata['videomp4'])){
							
							$curv=$viddata['videomp4']['location'];
							$prevvid=$viddata['videomp4']['location'];
							$realprevvid=".".$prevvid;
							if(file_exists($realprevvid)&&$realprevvid!=="."){
								unlink($realprevvid);
							}

							// change the content of the indexes
							$viddata['videomp4']['location']=$videofilepath1;
							$viddata['videomp4']['size']=$videofilesize1;
						}
					}

					if($vidid>0){
						$jsonout=json_encode($viddata);
						genericSingleUpdate("media","details",$jsonout,"id",$vidid);
						genericSingleUpdate("media","title",$becaption,"id",$vidid);
						
						
					}else if($vidid==0){
						// convert data to json
						if($videofilepath1!==""||$videofilepath2!==""||$videofilepath3!==""
							||$videofilepath4!==""||$pecode!==""){		
							$videofiletotal='{"videotype":"'.$betype.'",
									  "videowebm":{"location":"'.$videofilepath1.'",
												  "size":"'.$videofilesize1.'"},
									  "video3gp":{"location":"'.$videofilepath2.'",
												  "size":"'.$videofilesize2.'"},
									  "videoflv":{"location":"'.$videofilepath3.'",
												  "size":"'.$videofilesize3.'"},
									  "videomp4":{"location":"'.$videofilepath4.'",
												  "size":"'.$videofilesize4.'"},
									  "videoembed":"'.$becode.'"
									}';
							$videofiletotal=mysql_real_escape_string($videofiletotal);
							$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,
							title,details)
								VALUES
								('$pid','blogentry','blogentryvideo','json_details','$becaption',
									'$videofiletotal')";
								// echo $mediaquery2;
							$mediarun2=mysql_query($mediaquery2)or die(mysql_error());
						}
					}
				}
			}
		}

		isset($_POST['videotype'])?$betype=mysql_real_escape_string($_POST['videotype']):$betype="";
		isset($_POST['videoembed'])?$becode=mysql_real_escape_string($_POST['videoembed']):$becode="";
	}

	if($blogentrytype=="audio"){
		// legacy/old code
		if($rprev!=="none"){
			isset($_POST['audiotype'])?$betype=mysql_real_escape_string($_POST['audiotype']):$betype="";
			isset($_POST['audioembed'])?$becode=mysql_real_escape_string($_POST['audioembed']):$becode="";
			if (isset($_FILES['audio']['tmp_name'])&&$_FILES['audio']['tmp_name']!=="") {
				# code...
				$localaudioid=isset($_POST['localaudioid'])?$_POST['localaudioid']:0;
				$outsaudio=simpleUpload('audio','../files/audio/');
				$audiofilepath=$outsaudio['filelocation'];
				$len=strlen($audiofilepath);
				$audiofilepath=substr($audiofilepath, 1,$len);
				$audiofilesize=$outsaudio['filesize'];
				if($localaudioid>0){
					$mediadata=getSingleMediaDataTwo($localaudioid);
					$realprevfile=$host_addr.$mediadata['location'];
					if(file_exists($realprevfile)){
						unlink($realprevfile);
					}
					$orderfield[]="ownerid";
					$orderfield[]="ownertype";
					$orderfield[]="maintype";
					$ordervalues[]=$entryid;
					$ordervalues[]="blogentry";
					$ordervalues[]="blogentryaudio";
					genericSingleUpdate("media","location",$audiofilepath,$orderfield,$ordervalues);
					genericSingleUpdate("media","filesize",$audiofilesize,$orderfield,$ordervalues);
				}else{		
					$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)VALUES('$entryid','blogentry','blogentryaudio','audio','$audiofilepath','$audiofilesize')";
					$mediarun2=mysql_query($mediaquery2)or die(mysql_error());
				}
			}
			
		}else{
			// update / New code
			$blogaudiocount=mysql_real_escape_string($_POST['blogaudiocount']);
			if($blogaudiocount>0){
				for($i=1;$i<=$blogaudiocount;$i++){
					isset($_POST['localaudio_id'])?
					$audioid=mysql_real_escape_string($_POST['localaudio_id']):
					$audioid=0;

					isset($_POST['audiotype'])?
					$betype=mysql_real_escape_string($_POST['audiotype']):$betype="";
					
					isset($_POST['audiocaption'])?
					$becaption=mysql_real_escape_string($_POST['audiocaption']):
					$becaption="";

					isset($_POST['audioembed'])?
					$becode=mysql_real_escape_string($_POST['audioembed']):$becode="";
					
					// perform a delete if necessary
					$audiodelete=isset($_POST['audio_delete'])?
					$_POST['audio_delete']:"";				
					
					if($audiodelete=="inactive"){
						deleteMedia($audioid);
					}

					if($audioid>0&&$audiodelete==""){
						// echo $pecode;
						genericSingleUpdate("media","title",$becaption,"id",$audioid);
						genericSingleUpdate("media","details",$becode,"id",$audioid);
						genericSingleUpdate("media","mediatype",$betype,"id",$audioid);
						
					}

					$audiofilepath="";
					$audiofilesize="0KB";		
					if (isset($_FILES['audio']['tmp_name'])&&
						$_FILES['audio']['tmp_name']!==""
						&&$audiodelete=="") {
						# code...
						$outsaudio=simpleUpload('audio','../files/audio/');
						$audiofilepath=$outsaudio['filelocation'];
						$len=strlen($audiofilepath);
						$audiofilepath=substr($audiofilepath, 1,$len);
						$audiofilesize=$outsaudio['filesize'];
					}

					if($audioid>0){

						genericSingleUpdate("media","location",$audiofilepath,"id",$audioid);
						genericSingleUpdate("media","filesize",$audiofilesize,"id",$audioid);

					}else if($audioid==0){
						$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,
							location,filesize,title,details)
						VALUES
						('$entryid','blogentry','blogentryaudio','$betype','$audiofilepath',
							'$audiofilesize','$becaption','$becode')";
						// echo $mediaquery2;
						$mediarun2=mysql_query($mediaquery2)or die(mysql_error());
					}
				}
			}	
		}

		isset($_POST['audiotype'])?$betype=mysql_real_escape_string($_POST['audiotype']):$betype="";
		isset($_POST['audioembed'])?$becode=mysql_real_escape_string($_POST['audioembed']):$becode="";
	}


	genericSingleUpdate("blogentries","betype",$betype,"id",$entryid);
	genericSingleUpdate("blogentries","becode",$becode,"id",$entryid);
	
	//change the cover photo if a new one is available
	if($rprev!=="none"){
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
				$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,
					location,filesize,width,height)
					VALUES('$entryid','blogentry','coverphoto','image','$imagepath',
						'$filesize','$width','$height')";
				$mediarun=mysql_query($mediaquery)or die(mysql_error());
				genericSingleUpdate("blogentries","coverid",$coverid,"id",$entryid);
			}
			if(file_exists($realprevpic)){
				unlink($realprevpic);
			}
			$modified="yes";
		}

	}else{
		$imgname=isset($_FILES['profpic']['tmp_name'])?$_FILES['profpic']['tmp_name']:"";
	    // check to get the id for the current entry
	    $imgid=isset($_POST['profpic_id'])?$_POST['profpic_id']:0;
	    $coverdelete=isset($_POST['profpic_delete'])?
		$_POST['profpic_delete']:"";

		if($imgname!==""){
			$image='profpic';
			$imgpath[0]='../files/originals/';
		    $imgpath[1]='../files/medsizes/';
		    $imgpath[2]='../files/thumbnails/';
		    $imgsize[0]="default";
		    $imgsize[1]=",300";
		    $imgsize[2]=",85";
		    $acceptedsize="";
		    $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);

		    $len=strlen($imgouts[0]);
		    $len2=strlen($imgouts[1]);
		    $len3=strlen($imgouts[2]);
		    $imagepath=substr($imgouts[0], 1,$len);
		    $imagepath2=substr($imgouts[1], 1,$len2);
		    $imagepath3=substr($imgouts[2], 1,$len3);

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
			if($imgid>0&&$coverdelete==""){
		    	$imgdata=getSingleMediaDataTwo($imgid);
				$prevpic=$imgdata['location'];
				$prevmedsize=$imgdata['medsize'];
				$prevthumb=$imgdata['thumbnail'];
				$realprevpic=".".$prevpic;
				$realprevmedsize=".".$prevmedsize;
				$realprevthumb=".".$prevthumb;
				if(file_exists($realprevpic)&&$realprevpic!=="."){
					unlink($realprevpic);
				}
				if(file_exists($realprevmedsize)&&$realprevmedsize!=="."){
					unlink($realprevmedsize);
				}
				if(file_exists($realprevthumb)&&$realprevthumb!=="."){
					unlink($realprevthumb);
				}
				genericSingleUpdate("media","location",$imagepath,"id",$imgid);
				genericSingleUpdate("media","medsize",$imagepath2,"id",$imgid);
				genericSingleUpdate("media","thumbnail",$imagepath3,"id",$imgid);
				genericSingleUpdate("media","filesize",$filesize,"id",$imgid);
				genericSingleUpdate("media","width",$width,"id",$imgid);
				genericSingleUpdate("media","height",$height,"id",$imgid);

			}else if($imgid==0){
			    //maintype variants are original, medsize, thumb for respective size image.
			    $mediaquery="INSERT INTO media
			    (ownerid,ownertype,maintype,mediatype,location,medsize,thumbnail,title,
			    details,filesize,width,height)
				VALUES
				('$entryid','blogentry','coverphoto','image','$imagepath',
					'$imagepath2','$imagepath3','','','$filesize',
					'$width','$height')";
			    // echo $mediaquery."<br>";
			    $mediarun=mysql_query($mediaquery)or die(mysql_error()." ".__LINE__."
			    	<br>$mediaquery<br>");
			}
		}
	}

	//set the new page up
	
	// legacy page setup
	if($rprev!=="none"){
		$pagesetup = '<?php 
			session_start();
			include(\'../../snippets/connection.php\');
			$outpage=blogPageCreate('.$entryid.');
			echo $outpage[\'outputpageone\'];
			$blogdata=getSingleBlogEntry('.$entryid.');
			$newview=$blogdata[\'views\']+1;
			genericSingleUpdate("blogentries","views",$newview,"id",'.$entryid.');
		?>';
	}
	// update page setup
	if($rprev=="none"){
		$pagesetup = '<?php 
			$pageid='.$entryid.';			
			if(session_id()==\'\'){
				session_start();
			}
			if(!function_exists(\'getExtension\')){
				include(\'../../snippets/connection.php\');
			}
			
			include($host_tpathplain."modules/blogtemp.php");
			include($host_tpathplain."modules/blogpagecreate.php");
		?>';
	}
	$pagepath=".".$outs['reldirectory']."".$pagename.".php";
	
	$handle = fopen($pagepath, 'w') or die('Cannot open file:  '.$pagepath);
	fwrite($handle, $pagesetup);
	fclose($handle);
	
	if($modified=="yes"&&($schedulestatus=="no"||$schedulestatus=="")&&$outs['status']!=="schedule"){
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
	$modified=="yes"?
	genericSingleUpdate("blogentries","modifydate",$modifydate,"id",$entryid):
	$modifydate="";
	genericSingleUpdate("blogentries","status",$status,"id",$entryid);
	// update the status of the current post
	// if it is not a scheduled
	if($schedulestatus=="yes"||$schedulestatus=="on"||$schedulestatus=="no"){
		genericSingleUpdate("blogentries","scheduledpost","$schedulestatus","id",$entryid);
	}
	
	
	

	// if the user wants to publish the post immediately
	if($schedulestatus=="publish"){
		if(function_exists('livePublishing')){
			livePublishing($entryid);
		}
	}

	$notid=$entryid;
	// prepare content for the notification redirect portion
	$action="Edit";
	$actiontype="blogentries";
	$actiondetails=mysql_real_escape_string("Blog Entry '$title' Updated");
	
	// $notrdtype="none";//stops redirection

	// $nonottype="none";// stops notification table update

	// bring in the nrsection.php file
	include('nrsection.php');
}elseif($entryvariant=="unsubscribeblogtype"){
	$compid=4;
	$email=mysql_real_escape_string($_POST['email']);
	$typeid=$_POST['typeid'];
	$query="SELECT * FROM subscriptionlist WHERE email='$email' AND blogtypeid=$typeid";
	$run=mysql_query($query)or die(mysql_error()." line ".__LINE__);
	$numrows=mysql_num_rows($run);
	if($numrows>0){
		$entryid=$row['id'];
		genericSingleUpdate("subscriptionlist","status","inactive",'id',$entryid);
	}
	$_SESSION['lastsuccess']=0;

	$notid=$pageid;
	// prepare content for the notification redirect portion
	$action="Edit";
	$actiontype="subscriptionlist";
	$actiondetails="Subscription Updated";
	
	// $notrdtype="none";//stops redirection

	$nonottype="none";// stops notification table update

	// bring in the nrsection.php file
	include('nrsection.php');
	
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
}elseif ($entryvariant=="editfaq") {
	# code...
	$title=mysql_real_escape_string($_POST['title']);
	$content=mysql_real_escape_string($_POST['content']);
	$status=mysql_real_escape_string($_POST['status']);
	genericSingleUpdate("faq","title",$title,"id",$entryid);
	genericSingleUpdate("faq","content",$content,"id",$entryid);
	genericSingleUpdate("faq","status",$status,"id",$entryid);
	// prepare content for the notification redirect portion
	$notid=$entryid;

	// prepare content for the notification redirect portion
	$action="Update";
	$actiontype="faq";
	$actiondetails="FAQ Entry Updated";
	// bring in the nrsection.php file
	include('nrsection.php');

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
		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=4&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=4&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}
}elseif ($entryvariant=="editevent") {
	# code...
	$type=isset($_POST['eventtype'])?mysql_real_escape_string($_POST['eventtype']):"";
	genericSingleUpdate("events","type",$type,"id",$entryid);
	$eventtitle=mysql_real_escape_string($_POST['eventtitle']);
	genericSingleUpdate("events","eventtitle",$eventtitle,"id",$entryid);
	$eventdetails=mysql_real_escape_string($_POST['eventdetails']);
	genericSingleUpdate("events","eventdetails",$eventdetails,"id",$entryid);
	$eventstartdate=mysql_real_escape_string($_POST['eventstartdate']);
	$eventenddate=mysql_real_escape_string($_POST['eventenddate']);
	//verify that the set dates are in the right order
	$datetime1 = new DateTime("$eventstartdate"); // specified scheduled time
	$datetime2 = new DateTime("$eventenddate");
	if($datetime1>$datetime2){
		$sd=$eventstartdate;
		$eventstartdate=$eventenddate;
		$eventenddate=$sd;
	}
	genericSingleUpdate("events","startdate",$eventstartdate,"id",$entryid);
	genericSingleUpdate("events","stopdate",$eventenddate,"id",$entryid);

	$coverimage=isset($_FILES['eventcoverimage']['tmp_name'])?$_FILES['eventcoverimage']['tmp_name']:"";
    if($coverimage!=="") {
        # code...
		$imgid=isset($_POST['eventcoverimageid'])?$_POST['eventcoverimageid']:0;
        $image="eventcoverimage";
        $imgpath[0]='../files/originals/';
        $imgpath[1]='../files/medsizes/';
        $imgpath[2]='../files/thumbnails/';
        $imgsize[0]="default";
        $imgsize[1]=",300";
        $imgsize[2]=",85";
        $acceptedsize="";
        $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
        $len=strlen($imgouts[0]);
        $len2=strlen($imgouts[1]);
        $len3=strlen($imgouts[2]);
        $imagepath=substr($imgouts[0], 1,$len);
        $imagepath2=substr($imgouts[1], 1,$len2);
        $imagepath3=substr($imgouts[2], 1,$len3);
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
        if($imgid==""||$imgid==0){
	        $mediaquery="INSERT INTO media
	        (ownerid,ownertype,maintype,mediatype,location,medsize,thumbnail,filesize,width,height)
			VALUES('$entryid','event','coverphoto','image','$imagepath','$imagepath2','$imagepath3','$filesize','$width','$height')";
	        // echo $mediaquery."<br>";
	        $mediarun=mysql_query($mediaquery)or die(mysql_error()." ".__LINE__."<br>$mediaquery<br>");
        }else{
        	$imgdata=getSingleMediaDataTwo($imgid);
			$prevpic=$imgdata['location'];
			$prevmedsize=$imgdata['medsize'];
			$prevthumb=$imgdata['thumbnail'];
			$realprevpic=".".$prevpic;
			$realprevmedsize=".".$prevmedsize;
			$realprevthumb=".".$prevthumb;
			if(file_exists($realprevpic)&&$realprevpic!=="."){
				unlink($realprevpic);
			}
			if(file_exists($realprevmedsize)&&$realprevmedsize!=="."){
				unlink($realprevmedsize);
			}
			if(file_exists($realprevthumb)&&$realprevthumb!=="."){
				unlink($realprevthumb);
			}
			genericSingleUpdate("media","location",$imagepath,"id",$imgid);
			genericSingleUpdate("media","medsize",$imagepath2,"id",$imgid);
			genericSingleUpdate("media","thumbnail",$imagepath3,"id",$imgid);
			genericSingleUpdate("media","filesize",$filesize,"id",$imgid);
			genericSingleUpdate("media","width",$width,"id",$imgid);
			genericSingleUpdate("media","height",$height,"id",$imgid);
        }
    }
	/*$date=mysql_real_escape_string($_POST['dateholder']);
	$sep=preg_split("/-/",$date);
	$d=$sep[0];
	genericSingleUpdate("events","d",$d,"id",$entryid);
	$m=(int)$sep[1];
	$m<10&&strlen($m)<2?$m="0$m":$m=$m;
	genericSingleUpdate("events","m",$m,"id",$entryid);
	$y=$sep[2];
	genericSingleUpdate("events","y",$y,"id",$entryid);
	$date="$d-$m-$y";
	genericSingleUpdate("events","dateperiod",$date,"id",$entryid);*/

	$contactperson=mysql_real_escape_string($_POST['contactperson']);
	genericSingleUpdate("events","contactperson",$contactperson,"id",$entryid);

	$contactnumber=mysql_real_escape_string($_POST['contactnumber']);
	genericSingleUpdate("events","contactnumber",$contactnumber,"id",$entryid);

	$contactemail=mysql_real_escape_string($_POST['contactemail']);
	genericSingleUpdate("events","contactemail",$contactemail,"id",$entryid);

	$addressslidescount=mysql_real_escape_string($_POST['addressslidescount']);
	if($addressslidescount>0){
		for ($i=1; $i <=$addressslidescount ; $i++) { 
			# code...
			$locationtitle=mysql_real_escape_string($_POST['locationtitle'.$i.'']);
			$address=mysql_real_escape_string($_POST['address'.$i.'']);
			$lat=mysql_real_escape_string($_POST['lat'.$i.'']);
			$lng=mysql_real_escape_string($_POST['lng'.$i.'']);
			$caddrid=isset($_POST['addrid'.$i.''])?$_POST['addrid'.$i.'']:0;
			if($caddrid==""||$caddrid==0){
				if($locationtitle!==""&&$address!==""){
					$addrquery="INSERT INTO eventaddresses(eventid,locationtitle,address,lat,lng)
							VALUES
							('$entryid','$locationtitle','$address','$lat','$lng')";
					// echo "<br>$addrquery<br>";
					$addrrun=mysql_query($addrquery)or die(mysql_error()." Line ".__LINE__);
				}
			}else{
				if($locationtitle!==""&&$address!==""){
					genericSingleUpdate("eventaddresses","locationtitle",$locationtitle,"id",$caddrid);
					genericSingleUpdate("eventaddresses","address",$address,"id",$caddrid);
					genericSingleUpdate("eventaddresses","lat",$lat,"id",$caddrid);
					genericSingleUpdate("eventaddresses","lng",$lng,"id",$caddrid);

				}else{
					genericSingleUpdate("eventaddresses","eventid","0","id",$caddrid);
					genericSingleUpdate("eventaddresses","locationtitle","***delete***","id",$caddrid);
					genericSingleUpdate("eventaddresses","address","***delete***","id",$caddrid);
					genericSingleUpdate("eventaddresses","lat","***delete***","id",$caddrid);
					genericSingleUpdate("eventaddresses","lng","***delete***","id",$caddrid);
					genericSingleUpdate("eventaddresses","status","inactive","id",$caddrid);

				}
			}
		}
	}
	$isbookable=mysql_real_escape_string($_POST['isbookable']);
	genericSingleUpdate("events","isbookable",$isbookable,"id",$entryid);
	$bookingsrequirement=mysql_real_escape_string($_POST['bookingsrequirement']);
	genericSingleUpdate("events","bookingrequirements",$bookingsrequirement,"id",$entryid);
	$status=mysql_real_escape_string($_POST['status']);
	genericSingleUpdate("events","status",$status,"id",$entryid);
	if($rurladmin==""){

		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=5&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=5&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');

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
		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=6&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=6&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');

	}
}elseif ($entryvariant=="editgallerystream") {
	# code...
	// echo "in here<br>";
	
	$type=$_POST['type'];
	$piccount=$_POST['galleryslidescount'];
	// echo $piccount;
	if($piccount>0){
		for($i=1;$i<=$piccount;$i++){
			$imgid=$_POST['galimage'.$i.'_id'];
			$delete=$_POST['entry_status'.$i.''];
			if($delete=="yes"){
				deleteMedia($imgid);
			}else{
				$caption=$_POST['caption'.$i.''];
				// echo "in here1 $caption $imgid<br>";
				genericSingleUpdate("media","title","$caption",'id',$imgid);
				$details=$_POST['details'.$i.''];
				genericSingleUpdate("media","details","$details",'id',$imgid);
				// echo "in here2<br>";
				$albumpic=isset($_FILES['galimage'.$i.'']['tmp_name'])?$_FILES['galimage'.$i.'']['tmp_name']:"";
				if($albumpic!==""){
					// echo"<br> Image uppy<br>";
					$image="galimage".$i."";
					if(isset($imagepath)){
						unset($imagepath);
						unset($imagesize);
					}
					$imagepath=array();
					$imagesize=array();
					$imgpath[0]='../files/originals/';
					$imgpath[1]='../files/medsizes/';
					$imgpath[2]='../files/thumbnails/';
					$imgsize[0]="default";
					$imgsize[1]=",300";
					$imgsize[2]=",85";
					// echo count($imgsize);
					$acceptedsize="";
					$imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
					// get the orignal filepath
					$len=strlen($imgouts[0]);
					$imagepath=substr($imgouts[0], 1,$len);
					// get the medsize filepath
					$len2=strlen($imgouts[1]);
					$imagepath2=substr($imgouts[1], 1,$len2);
					// get the medsize filepath
					$len3=strlen($imgouts[2]);
					$imagepath3=substr($imgouts[2], 1,$len3);
					// get image size details
					$filedata=getFileDetails($imgouts[0],"image");
					$filesize=$filedata['size'];
					$width=$filedata['width'];
					$height=$filedata['height'];
					$orderfield[]="id";
					$orderfield[]="ownertype";
					$orderfield[]="maintype";
					$ordervalues[]=$imgid;
					$ordervalues[]="$type";
					$ordervalues[]="gallery";
					genericSingleUpdate("media","location",$imagepath,$orderfield,$ordervalues);
					genericSingleUpdate("media","medsize",$imagepath2,$orderfield,$ordervalues);
					genericSingleUpdate("media","thumbnail",$imagepath3,$orderfield,$ordervalues);
					genericSingleUpdate("media","filesize",$filesize,$orderfield,$ordervalues);
					genericSingleUpdate("media","width",$width,$orderfield,$ordervalues);
					genericSingleUpdate("media","height",$height,$orderfield,$ordervalues);
				}
			}

		}
	}
	if($rurladmin==""){
		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=7&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=7&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');

	}
}else if ($entryvariant=="editbranch") {
  # code... 
  $location=mysql_real_escape_string($_POST['locationtitle']);
  genericSingleUpdate("branches","location","$location","id","$entryid");
  $lat=mysql_real_escape_string($_POST['latitude']);
  genericSingleUpdate("branches","lat","$lat","id","$entryid");
  $lng=mysql_real_escape_string($_POST['longitude']);
  genericSingleUpdate("branches","lng","$lng","id","$entryid");
  $address=mysql_real_escape_string($_POST['address']);
  genericSingleUpdate("branches","address","$address","id","$entryid");
  $mainbranch=mysql_real_escape_string($_POST['mainbranch']);
  genericSingleUpdate("branches","branchtype","$mainbranch","id","$entryid");
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
      $sphonenumbers=isset($_POST['phonenumbers'.$i.''])?mysql_real_escape_string($_POST['phonenumbers'.$i.'']):"";
      $semail=isset($_POST['email'.$i.''])?mysql_real_escape_string($_POST['email'.$i.'']):"";
      $scontactpersons=isset($_POST['contactpersons'.$i.''])?mysql_real_escape_string($_POST['contactpersons'.$i.'']):"";
      $sstatus=isset($_POST['subcontactstatus'.$i.''])?mysql_real_escape_string($_POST['subcontactstatus'.$i.'']):"";
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

		$_SESSION['lastsuccess']=0;
  header('location:../admin/adminindex.php?compid=8&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
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

		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=9&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=9&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');

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

		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=10&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=10&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');

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
		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=11&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=11&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
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

		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=12&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=12&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');

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

		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=13&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=13&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');

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

		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=14&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=14&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');

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

		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=15&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=15&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');

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

		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=16&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=16&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');

	}
}elseif ($entryvariant=="contententryupdate") {
	# code... this section is for pages that have a singular content
	$introtitle=isset($_POST['contenttitle'])?mysql_real_escape_string($_POST['contenttitle']):"";
	$title=isset($_POST['contenttitle'])?mysql_real_escape_string($_POST['contenttitle']):"";
	$subtitle=isset($_POST['contentsubtitle'])?mysql_real_escape_string($_POST['contentsubtitle']):"";
	$intro=isset($_POST['intro'])?mysql_real_escape_string(str_replace("../", "$host_addr",$_POST['intro'])):"";
	$content=isset($_POST['contentpost'])?mysql_real_escape_string(str_replace("../", "$host_addr",$_POST['contentpost'])):"";
	if($intro!==""){
		// echo $intro;
		$headerdescription = mysql_real_escape_string(convert_html_to_text(stripslashes($intro)));
		$headerdescription=html2txt($headerdescription);
		$monitorlength=strlen($headerdescription);
		$headerdescription=$monitorlength<600?$headerdescription."...":substr($headerdescription, 0,600)."...";
		
	}else{
		$headerdescription="";
	}
	if($content==""){
		$content=$intro;
	}
	$maintype=mysql_real_escape_string($_POST['maintype']);
	$subtype=mysql_real_escape_string($_POST['subtype']);
	$status=isset($_POST['status'])?mysql_real_escape_string($_POST['status']):"";
	$extraformdata=isset($_POST['extraformdata'])?$_POST['extraformdata']:"";
	// $extraformtypes=isset($_POST['extraformtypes'])?$_POST['extraformtypes']:"";
	$extradata="";
	

	$extraformdata=isset($_POST['extraformdata'])?mysql_real_escape_string($_POST['extraformdata']):"";
	$errormap=isset($_POST['errormap'])?mysql_real_escape_string($_POST['errormap']):"";
	$formdata=isset($_POST['formdata'])?mysql_real_escape_string($_POST['formdata']):"";
	if($errormap!==""){
	  	$errormap=preg_replace("/[\n\r]/","",$errormap);	
	  	$errormap=str_replace("\\r\\n", "", $errormap);

	}
	// $extraformtypes=isset($_POST['extraformtypes'])?$_POST['extraformtypes']:"";
	$extradata="";
	// handling file uploads
	/*
		if (isset($_FILES['videomp4']['tmp_name'])&&$_FILES['videomp4']['tmp_name']!=="") {
			# code...
			$localvideomp4id=$_POST['videomp4id'];
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
	*/
	// handling extra data media content that require an id from the media table
	if($entryid==""||$entryid==0){
		$cexdataid=getNextId('generalinfo');
	}else{
		$cexdataid=$entryid;
	}
	if($extraformdata!==""){
		$eformparserdata=array();
		$eformparserdata['extraformdata']=$extraformdata;
		$eformparserdata['errormap']=$errormap;
		$eformparserdata['entryid']=$cexdataid;
		$eformparserdata['formdata']=$formdata;
		$eformparserdata['maintype']=$maintype;
		$eformparserdata['subtype']=$subtype;
		$eformparsed=parseEFormData($eformparserdata);
		$extraformdata=$eformparsed['extraformdata'];
		$extradata=$eformparsed['finaltally'];

	}

	if($entryid!==""&&$entryid>0){
		genericSingleUpdate("generalinfo","title",$introtitle,"id",$entryid);
		genericSingleUpdate("generalinfo","subtitle",$subtitle,"id",$entryid);
		genericSingleUpdate("generalinfo","intro",$headerdescription,"id",$entryid);
		genericSingleUpdate("generalinfo","content",$intro,"id",$entryid);
		// genericSingleUpdate("generalinfo","formdata",$formdata,"id",$entryid);
		genericSingleUpdate("generalinfo","formerrordata",$errormap,"id",$entryid);
		genericSingleUpdate("generalinfo","extradata",$extradata,"id",$entryid);
		genericSingleUpdate("generalinfo","extraformdata",$extraformdata,"id",$entryid);
		genericSingleUpdate("generalinfo","status",$status,"id",$entryid);
	}else{
	    $entryid=getNextId('generalinfo');
	    // check to see if there is a match
	    $querytest="SELECT * FROM generalinfo WHERE maintype='$maintype' AND subtype='$subtype'";
	    $runtest=mysql_query($querytest)or die(mysql_error()." ".__LINE__);
	    $numrows=mysql_num_rows($runtest);
	    if($numrows<1){
	        $entrydate=date("Y-m-d H:i:s");
	        $query="INSERT INTO generalinfo
			  (maintype,subtype,title,subtitle,intro,content,extradata,formdata,extraformdata,formerrordata,entrydate)VALUES
			  ('$maintype','$subtype','$title','$subtitle','$headerdescription','$content','$extradata',
	  			'$formdata','$extraformdata','$errormap','$entrydate')";
			// echo $query;
	        $run=mysql_query($query)or die(mysql_error()." <br>$query ".__LINE__);
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
              $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,thumbnail,filesize,width,height)VALUES
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
              genericSingleUpdate("media","thumbnail",$imagepath2,"id",$imgid);
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
      }else if ($maintype=="") {
      	# code...

      }
    /*end*/
    if($rurladmin==""){
		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=17&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=17&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}

}else if ($entryvariant=="contententry") {
  # code...
  $maintype=mysql_real_escape_string($_POST['maintype']);
  $subtype=mysql_real_escape_string($_POST['subtype']);
  $title=isset($_POST['contenttitle'])?mysql_real_escape_string($_POST['contenttitle']):"";
  $subtitle=isset($_POST['contentsubtitle'])?mysql_real_escape_string($_POST['contentsubtitle']):"";
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
  genericSingleUpdate("generalinfo","subtitle",$subtitle,"id",$entryid);
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
      $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,thumbnail,filesize,width,height)VALUES
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
		genericSingleUpdate("media","thumbnail",$imagepath2,"id",$imgid);
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
            $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,thumbnail,filesize,width,height)VALUES
            ('$entryid','$maintype','productbanner','image','$imagepath','$imagepath2','$filesize','$width','$height')";
            $mediarun=mysql_query($mediaquery)or die(mysql_error()." ".__LINE__);
            
          }else{
            $imgdata=getSingleMediaDataTwo($imgid);
            $prevpic=$imgdata['location'];
            $prevthumb=$imgdata['thumbnail'];
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

		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=18&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=18&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');

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

		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=19&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=19&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');

	}
}else if($entryvariant=="createhomesliderbanners"||$entryvariant=="createhomebannerentry"){
  $curbannerslidecount=$entryvariant=="createhomesliderbanners"?6:($entryvariant=="createhomebannerentry"?$_POST['curbannerslidecount']:($entryvariant=="edithomebanner"?1:6));
  // this variable holds the ownertype column information for the current slide type in
  // the media table
  $curslidetype=$entryvariant=="createhomesliderbanners"?"homebanners":
				($entryvariant=="createhomebannerentry"?"homebannerentry":
				($entryvariant=="edithomebanner"?"homebanner":"banners"));
  // echo $curbannerslidecount;
  $cursurveyslidedelete=isset($_POST['status'.$entryid.''])?$_POST['status'.$entryid.'']:""; 
  // get the last id for the slidetype in the database
  if($cursurveyslidedelete!=="inactive"){
    for($i=1;$i<=$curbannerslidecount;$i++){

      $maintype=$_POST['maintype'];
      $subtype=isset($_POST['subtype'.$i.''])?$_POST['subtype'.$i.'']:$_POST['subtype'];
      $headercaption=isset($_POST['headercaption'.$i.''])?$_POST['headercaption'.$i.'']:" ";
      $minicaption=isset($_POST['minicaption'.$i.''])?$_POST['minicaption'.$i.'']:" ";
      $captionout=$headercaption."[|><|]".$minicaption;
      // echo $captionout;
      if($imgid>1){
        genericSingleUpdateTwo("media","title",$captionout,"id",$imgid);
      }
      $contentpic=isset($_FILES['contentpic'.$i.'']['tmp_name'])?$_FILES['contentpic'.$i.'']['tmp_name']:"";
      // echo $contentpic." $i";

      // perform an update
      $imgdata=getSingleMediaDataTwo($imgid);
      if($contentpic!==""){
        $image="contentpic$i";
        $imgpath[]='../files/medsizes/';
        $imgpath[]='../files/thumbnails/';
        $imgsize[]="300";
        $imgsize[]=",250";
        $acceptedsize="";
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
        //echo $filefirstsize;
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
          $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,details,filesize,width,height,title)VALUES
          ('$cid','$maintype','$subtype','image','$imagepath','$imagepath2','$filesize','$width','$height','$captionout')";
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
        }
        // echo "in here"."<br>";
      }    
    }
  }else if($cursurveyslidee=="inactive"){
    deleteMedia($entryid);
    // check and readjust th emainid count for other slides in the media table

  }
		$_SESSION['lastsuccess']=0;
    header('location:../admin/adminindex.php?compid=20&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');

}else if($entryvariant=="edithomebanner"){
       // update/delete old slides    
        $cursurveyslidedelete=$_POST['status'.$entryid.''];
		$mainid=$_POST['mainid_'.$entryid.''];
		$mediadata=getSingleMediaDataTwo("$entryid");

		$prevmainid=$mediadata['mainid'];
        
        $picout=$_FILES['slide'.$entryid.'']['tmp_name'];
        $ownertype=isset($_POST['ownertype'.$entryid.''])?$_POST['ownertype'.$entryid.'']:"homebanner";
        $maintype="slide";
        if ($cursurveyslidedelete!=="inactive") {
            	# code...
                $headercaption=$_POST['headercaption'.$entryid.''];
                $minicaption=$_POST['minicaption'.$entryid.''];
                $linkaddress=$_POST['linkaddress'.$entryid.''];
                $linktitle=$_POST['linktitle'.$entryid.''];
                $captioncombo=$headercaption.'[|><|]'.$minicaption.'[|><|]'.$linkaddress.'[|><|]'.$linktitle;
                genericSingleUpdate("media","details","$captioncombo","id","$entryid");
                // // echo $captioncombo;
	            if($picout!=="") {
	                # code...
	                $image="slide$entryid";
	                $imgpath[0]='../files/originals/';
			        $imgpath[1]='../files/medsizes/';
			        $imgpath[2]='../files/thumbnails/';
			        $imgsize[0]="default";
			        $imgsize[1]=",300";
			        $imgsize[2]=",85";
			        $acceptedsize="";
			        $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
			        $len=strlen($imgouts[0]);
			        $len2=strlen($imgouts[1]);
			        $len3=strlen($imgouts[2]);
			        $imagepath=substr($imgouts[0], 1,$len);
			        $imagepath2=substr($imgouts[1], 1,$len2);
			        $imagepath3=substr($imgouts[2], 1,$len3);
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
	                genericSingleUpdate("media","medsize","$imagepath2","id","$entryid");
	                genericSingleUpdate("media","thumbnail","$imagepath3","id","$entryid");
	                genericSingleUpdate("media","filesize","$filesize","id","$entryid");
	                genericSingleUpdate("media","width","$width","id","$entryid");
	                genericSingleUpdate("media","height","$height","id","$entryid");

	            }
	            // echo "$prevmainid prevmain $mainid mainid";
	            /*if($prevmainid!==$mainid){
		            if($prevmainid<$mainid){
						$uptq="UPDATE media SET mainid=mainid+1 WHERE mainid>$mainid AND mainid<$prevmainid AND ownertype='$ownertype'AND maintype='$maintype'";
						$uptr=mysql_query($uptq)or die(mysql_error()." Line ".__LINE__);
						echo "<br>1<br>$uptq<br>";						
					}else if($prevmainid>$mainid){
						$uptq="UPDATE media SET mainid=mainid-1 WHERE mainid<$mainid AND mainid>$prevmainid AND ownertype='$ownertype'AND maintype='$maintype'";
						$uptr=mysql_query($uptq)or die(mysql_error()." Line ".__LINE__);
						echo "<br>2<br>$uptq<br>";

					}
					genericSingleUpdate("media","mainid",$mainid,"id",$entryid);
				}*/
	            if($prevmainid!==$mainid&&$prevmainid!==""&&$mainid!==""){
	            	$subtract=$prevmainid-$mainid;
					if($prevmainid<$mainid&&$subtract<-1){
						$uptq="UPDATE media SET mainid=mainid-1 WHERE mainid<=$mainid AND mainid>$prevmainid AND ownertype='$ownertype' AND maintype='$maintype'";
						$uptr=mysql_query($uptq)or die(mysql_error()." Line ".__LINE__);
						echo "<br>1<br>$uptq<br>";

					}else if($prevmainid>$mainid&&$subtract>1){
						$uptq="UPDATE media SET mainid=mainid+1 WHERE mainid>='$mainid' AND mainid<$prevmainid AND ownertype='$ownertype' AND maintype='$maintype'";
						echo "<br>2<br>$uptq<br>";
						$uptr=mysql_query($uptq)or die(mysql_error()." Line ".__LINE__);
						
					}else{
						// perform a swap since the content being jumped is by a space
						// get the target elements data
						$sq="SELECT * FROM media WHERE ownertype='$ownertype' AND maintype='$maintype' AND mainid='$mainid'";
						$sqdata=briefquery($sq,__LINE__);
						if($sqdata['numrows']>0){
							$swapslideid=$sqdata['resultdata'][0]['id'];
							// change the main id for the previous bearer of the mainid
							genericSingleUpdate("media","mainid",$prevmainid,"id",$swapslideid);
							// set the main id for the current slide to match
							genericSingleUpdate("media","mainid",$mainid,"id",$entryid);
						}
					}
					genericSingleUpdate("media","mainid",$mainid,"id",$entryid);
				}

        }else if($cursurveyslidedelete=="inactive"){
            // make sure there is at least one slide available, in case an admin goes into a delete frenzy
            // by making use of sentinel control variables "newcount"-for ensuring there is a new entry 
            // and slidedeletecount for ensuring the total number of deleted entries are within a reasonable range 
            // get the current main id of the entry first before doing any update to
            // subsequent entries
			$mediadata=getSingleMediaDataTwo("$entryid");
			$prevmainid=$mediadata['mainid'];
            deleteMedia($entryid);
			$uptq="UPDATE media SET mainid=mainid-1 WHERE mainid>$prevmainid AND ownertype='$ownertype' AND maintype='$maintype'";
			$uptr=mysql_query($uptq)or die(mysql_error()." Line ".__LINE__);
        }

        if($rurladmin==""){

		$_SESSION['lastsuccess']=0;
			header('location:../admin/adminindex.php?compid=21&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
		}else{
			header('location:'.$rurladmin.'?compid=21&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');

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

        
        deleteMedia($entryid);
      }
      if($rurladmin==""){

		$_SESSION['lastsuccess']=0;
			header('location:../admin/adminindex.php?compid=22&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
		}else{
			header('location:'.$rurladmin.'?compid=22&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');

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
  /*$query="INSERT INTO branches(location,address,contactname,phonenumbers,email)VALUES('$location','$address','$contactpersons','$phonenumbers','$email')";
  $run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);*/
  if($rurladmin==""){

		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=23&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=23&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');

	}
}else if ($entryvariant=="recommendation"||$entryvariant=="editclientnrec"||$entryvariant=="clientelle"||$entryvariant=="testimonial"||$entryvariant=="editcnrnt") {
	# code...
	$type=mysql_real_escape_string($_POST['type']);
    // $cnrid=getNextId('clientnrec');
    // echo "in here";
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
    genericSingleUpdate("clientnrec","officialwebsite","$companywebsite","id","$entryid");
    $details=mysql_real_escape_string($_POST['details']);
    genericSingleUpdate("clientnrec","content","$details","id","$entryid");
    $status=mysql_real_escape_string($_POST['status']);
    genericSingleUpdate("branches","status","$status","id","$entryid");
    // $captioncombo=$fullname.'[|><|]'.$position.'[|><|]'.$details.'[|><|]'.$qualifications;
    if($picout!=="") {
        # code...
            $image="slide";
            $imgpath[0]='../files/originals/';
	        $imgpath[1]='../files/medsizes/';
	        $imgpath[2]='../files/thumbnails/';
	        $imgsize[0]="default";
	        $imgsize[1]=",540";
	        $imgsize[2]=",85";
	        $acceptedsize="";
	        $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
			$len=strlen($imgouts[0]);
	        $len2=strlen($imgouts[1]);
	        $len3=strlen($imgouts[2]);
	        $imagepath=substr($imgouts[0], 1,$len);
	        $imagepath2=substr($imgouts[1], 1,$len2);
	        $imagepath3=substr($imgouts[2], 1,$len3);
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
              $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,medsize,thumbnail,filesize,width,height)VALUES
              ('$entryid','$type','coverphoto','image','$imagepath','$imagepath2','$imagepath3','$filesize','$width','$height')";
              $mediarun=mysql_query($mediaquery)or die(mysql_error()." ".__LINE__);
              
            }else{
              $imgdata=getSingleMediaDataTwo($imgid);
              $prevpic=$imgdata['location'];
              $prevmed=$imgdata['medsize'];
              $prevthumb=$imgdata['thumbnail'];
              $realprevpic=".".$prevpic;
              $realprevmed=".".$prevmed;
              $realprevthumb=".".$prevthumb;
              if(file_exists($realprevpic)&&$realprevpic!=="."){
                unlink($realprevpic);
              }
              if(file_exists($realprevthumb)&&$realprevthumb!=="."){
                unlink($realprevthumb);
              }
              if(file_exists($realprevmed)&&$realprevmed!=="."){
                unlink($realprevmed);
              }
            
              genericSingleUpdate("media","location",$imagepath,"id",$imgid);
              genericSingleUpdate("media","medsize",$imagepath2,"id",$imgid);
              genericSingleUpdate("media","thumbnail",$imagepath3,"id",$imgid);
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
		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=24&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else{
		header('location:'.$rurladmin.'?compid=24&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
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
	$status=isset($_POST['recruitstatus'])?mysql_real_escape_string($_POST['recruitstatus']):"active";
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
		$_SESSION['lastsuccess']=0;
			header('location:../admin/adminindex.php?compid=25&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');
		}else{
			header('location:'.$rurladmin.'?compid=25&rtype=edit&type='.$entryvariant.'&v=admin&d='.$entryid.'');

		}
	}

}elseif ($entryvariant=="resetpassword") {
	# code...
	$password=mysql_real_escape_string($_POST['password']);
	$confirmpassword=isset($_POST['confirmpassword'])?
	mysql_real_escape_string($_POST['confirmpassword']):"";
	$tblname=isset($_POST['tblname'])?
	mysql_real_escape_string($_POST['tblname']):"users";
	$checksum=mysql_real_escape_string($_POST['checksum']);
	$success="true";$msg="Successfully reset password";

	if ($password!==""&&$confirmpassword!==""&&$password==$confirmpassword) {
		# code...
		genericSingleUpdate("$tblname","pword",$password,"id",$entryid);
		$orderfields[]="userid";
		$orderfields[]="checksum";
		$ordervalues[]=$entryid;
		$ordervalues[]=$checksum;
		// update resetpassword so the checksum cant be used again
		genericSingleUpdate("resetpassword","status","inactive",$orderfields,$ordervalues);
		$returl=isset($_POST['returl'])?
		mysql_real_escape_string($_POST['returl']):"../completion.php";	
		
		$notid=0;
		$actionid="$entryid";
		$actiontype="users";

		// $nonottype="none";// stops notification table update
		// $notrdtype="none";//stops redirection

		// prepare content for the notification redirect portion
		$action="Update";
		$actiontype="resetpassword";
		$actiondetails="Password reset Successfully done";
		$appendedparams="&t=resetdone";
		
		// for return json based responses
		if(isset($_POST['rettype'])&&$_POST['rettype']=="json"){
			$output=array("success"=>"$success","msg","$msg");
			echo json_encode($output);
		}else{

			// bring in the nrsection.php file
			include('nrsection.php');
		}
	}else{
		$success="false";$msg="Reset Failed";
		// echo "Invalid data transaction, code: 10034";
		$notid=0;
		$nonottype="none";// stops notification table update
		// $notrdtype="none";//stops redirection
		$appendedparams="&t=resetfail";

		// for return json based responses
		if(isset($_POST['rettype'])&&$_POST['rettype']=="json"){
			$output=array("success"=>"$success","msg","$msg");
			echo json_encode($output);
		}else{

			// bring in the nrsection.php file
			include('nrsection.php');
		}

	}

}else if($entryvariant=="edituseraccadmin"||$entryvariant=="edituseracc"){
	// $usertype="user";
	// echo "$entryvariant";
	// get the previous user data
	$udata=getSingleUserPlain($entryid);
	$updatedata="";
	$updata="";
	$firstname=mysql_real_escape_string($_POST['firstname']);
	$middlename=mysql_real_escape_string($_POST['middlename']);
	$lastname=mysql_real_escape_string($_POST['lastname']);
	$fullname=$firstname.' '.$middlename.' '.$lastname;
	if($fullname!==$udata['fullname']){
		$updatedata="true";
		$updata.=" \nFullname from ".$udata['fullname']." to ".$fullname;
	}
	genericSingleUpdate("users","fullname","$fullname","id","$entryid");
	$gender=mysql_real_escape_string($_POST['gender']);
	if($gender!==""&&$gender!==$udata['gender']){
		genericSingleUpdate("users","gender","$gender","id","$entryid");
		$genderchangedate=date("Y-m-d");
		genericSingleUpdate("users","genderchangedate","$genderchangedate","id","$entryid");
		$updatedata="true";
		$updata.=" \nGender from ".$udata['gender']." to ".$gender;
	}
	$dob=mysql_real_escape_string($_POST['dob']);
	genericSingleUpdate("users","dob","$dob","id","$entryid");
	if($dob!==$udata['dob']){
		$updatedata="true";
		$updata.=" \nDate Of Birth from ".$udata['dob']." to ".$dob;
	}
	$phonenumber=mysql_real_escape_string($_POST['phonenumber']);
	genericSingleUpdate("users","phonenumber","$phonenumber","id","$entryid");
	if($phonenumber!==$udata['phonenumber']){
		$updatedata="true";
		$updata.=" \nPhone Number from ".$udata['phonenumber']." to ".$phonenumber;
	}
	$email=mysql_real_escape_string($_POST['email']);
	genericSingleUpdate("users","email","$email","id","$entryid");
	if($email!==$udata['email']){
		$updatedata="true";
		$updata.=" \nEmail from ".$udata['email']." to ".$email;
		// send a new verification message to the user
	}
	$username=isset($_POST['username'])?mysql_real_escape_string($_POST['username']):"";
	if($username!==""){
		genericSingleUpdate("users","username","$username","id","$entryid");
	}

	$pword=mysql_real_escape_string($_POST['pword']);
	genericSingleUpdate("users","pword","$pword","id","$entryid");
	if($email!==$udata['email']){
		$updatedata="true";
		$updata.=" \nPassword";
	}	
	$pcmethod=mysql_real_escape_string($_POST['pcmethod']);
	genericSingleUpdate("users","pcmethod","$pcmethod","id","$entryid");
	if($pcmethod!==$udata['pcmethod']){
		$updatedata="true";
		$updata.=" \nPreferred Contact method from ".$udata['pcmethod']." to ".$pcmethod;
	}	
	$state=mysql_real_escape_string($_POST['state']);
	// echo $state;
	// get the state id
	if($state!==""){
		$sdata=briefquery("SELECT * FROM state WHERE state='$state'",__LINE__,"mysqli");
		$cdata=$sdata['resultdata'][0];
		$numrows=$sdata['numrows'];
		// if there are any results then set the state id to the current id
		$sid=$numrows>0?$cdata['id_no']:0;
		if($sid!==$udata['state']){
			$updatedata="true";
			$updata.=" \nState from ".$udata['statename']." to ".$state;
			genericSingleUpdate("users","state","$sid","id","$entryid");
		}
	}

	$address=mysql_real_escape_string($_POST['address']);
	genericSingleUpdate("users","address","$address","id","$entryid");
	if($pcmethod!==$udata['pcmethod']){
		$updatedata="true";
		$updata.=" \nPreferred Contact method from ".$udata['pcmethod']." to ".$pcmethod;
	}

    $imgid=mysql_real_escape_string($_POST['coverid']);
	$contentpic=$_FILES['contentpic']['tmp_name'];
    if($contentpic!==""){
		$image="contentpic";
		$imgpath[0]='../files/originals/';
		$imgpath[1]='../files/medsizes/';
		$imgpath[2]='../files/thumbnails/';
		$imgsize[0]="default";
		$imgsize[1]=",300";
		$imgsize[2]=",85";
		$imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
		$len=strlen($imgouts[0]);
		$imagepath=substr($imgouts[0], 1,$len);
		// medsize
		$len2=strlen($imgouts[1]);
		$imagepath2=substr($imgouts[1], 1,$len2);
		// thumbnail
		$len3=strlen($imgouts[2]);
		$imagepath3=substr($imgouts[2], 1,$len3);
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
		//$coverpicid=getNextId("media"); 
		//maintype variants are original, medsize, thumb for respective size image.
		if($imgid<1){
			$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,medsize,thumbnail,filesize,width,height)VALUES
			('$entryid','user','profpic','image','$imagepath','$imagepath2','$imagepath3','$filesize','$width','$height')";
			$mediarun=mysql_query($mediaquery)or die(mysql_error()." ".__LINE__);

		}else{
			$imgdata=getSingleMediaDataTwo($imgid);
			$prevpic=$imgdata['location'];
			$prevmed=$imgdata['medsize'];
			$prevthumb=$imgdata['thumbnail'];
			$realprevpic=".".$prevpic;
			$realprevthumb=".".$prevthumb;
			if(file_exists($realprevpic)&&$realprevpic!=="."){
			  unlink($realprevpic);
			}
			if(file_exists($realprevmed)&&$realprevmed!=="."){
			  unlink($realprevmed);
			}
			if(file_exists($realprevthumb)&&$realprevthumb!=="."){
			  unlink($realprevthumb);
			}
			genericSingleUpdate("media","location",$imagepath,"id",$imgid);
			genericSingleUpdate("media","medsize",$imagepath2,"id",$imgid);
			genericSingleUpdate("media","thumbnail",$imagepath3,"id",$imgid);
			genericSingleUpdate("media","filesize",$filesize,"id",$imgid);
			genericSingleUpdate("media","width",$width,"id",$imgid);
			genericSingleUpdate("media","height",$height,"id",$imgid);
			// echo "in here";
		}
    }

	if($updatedata=="true"){
		createNotification($entryid,"users","update","Profile was updated $updata");
	}

	// echo $query."<br>";
	// $qdata=briefquery($query,__LINE__,"mysqli",true);
	// $uid=$qdata['resultdata'][0]['id'];

	$_SESSION['lastsuccess']=0;
	if($entryvariant=="edituseraccadmin"){
		header('location:../admin/adminindex.php?compid=17&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else if($entryvariant=="edituseracc"){
		$returl=isset($_POST['returl'])?$_POST['returl']:"";
		// createNotification($entryid,"users","update","Incident entry was updated");

		$url='../userdashboard.php?compid=25&type='.$entryvariant.'&v=admin&d='.$entryid.'';
		header('location:'.$url.'');
	}

}else if($entryvariant=="editclientaccadmin"||$entryvariant=="editclientacc"){
	$usertype="serviceprovider";
	$udata=getSingleUserPlain($entryid);

	// echo $entryvariant;
	$businessname=mysql_real_escape_string($_POST['businessname']);
	genericSingleUpdate("users","businessname","$businessname","id","$entryid");
	
	$bntype=isset($_POST['bntype'])?mysql_real_escape_string($_POST['bntype']):"";
	genericSingleUpdate("users","bntype","$bntype","id","$entryid");

	$email=mysql_real_escape_string($_POST['email']);
	genericSingleUpdate("users","email","$email","id","$entryid");
	$username=isset($_POST['username'])?mysql_real_escape_string($_POST['username']):"";
	if($username!==""){
		genericSingleUpdate("users","username","$username","id","$entryid");
	}

	$pword=mysql_real_escape_string($_POST['pword']);
	genericSingleUpdate("users","pword","$pword","id","$entryid");
	$phonenumber=mysql_real_escape_string($_POST['phonenumber']);
	$phonetwo=isset($_POST['phonetwo'])?mysql_real_escape_string($_POST['phonetwo']):"";
	if($phonetwo!==""&&str_replace(" ", "", $phonetwo)!==""){
		$phonenumber.="[|><|]$phonetwo";
	}
	genericSingleUpdate("users","phonenumber","$phonenumber","id","$entryid");
	$contactname=mysql_real_escape_string($_POST['fullname']);
	genericSingleUpdate("users","fullname","$contactname","id","$entryid");
	$contactemail=mysql_real_escape_string($_POST['contactemail']);
	genericSingleUpdate("users","contactemail","$contactemail","id","$entryid");
	$contactphonenumber=mysql_real_escape_string($_POST['contactphonenumber']);
	genericSingleUpdate("users","contactphone","$contactphonenumber","id","$entryid");

	$emergency=isset($_POST['emergency'])?
	mysql_real_escape_string($_POST['emergency']):"";
	genericSingleUpdate("users","emergency","$emergency","id","$entryid");

	$businessnature=mysql_real_escape_string($_POST['businessnature']);
	$businessnature2=isset($_POST['businessnature2'])?
	mysql_real_escape_string($_POST['businessnature2']):"";
	
	$bnid=0;
	if($businessnature!==""&&$businessnature>0){
		$bndata=getAllBusinessTypes($businessnature);
		if($bndata['numrows']>0){
			// var_dump($bndata);
			$bnid=$businessnature;
			$businessnature=$bndata['resultdata'][0]['businessnature'];
		}
		genericSingleUpdate("users","bnid","$bnid","id","$entryid");
		genericSingleUpdate("users","businessnature","$businessnature","id","$entryid");
	}
	
	$bnid2=0;
	if($businessnature2!==""&&$businessnature2>0){
		$bndata=getAllBusinessTypes($businessnature2);
		if($bndata['numrows']>0){
			// var_dump($bndata);
			$bnid2=$businessnature2;
			$businessnature2=$bndata['resultdata'][0]['businessnature'];
		}
		genericSingleUpdate("users","sbnid","$bnid2","id","$entryid");
		genericSingleUpdate("users","businessnature","$businessnature2","id","$entryid");

	}

	$dataurl=isset($_POST['dataurl'])?
	mysql_real_escape_string($_POST['dataurl']):"";
	if($dataurl!==""){
		genericSingleUpdate("users","websiteurl","$dataurl","id","$entryid");

	}

	$references=isset($_POST['references'])?
	mysql_real_escape_string($_POST['references']):"";
	if(isset($_POST['refereedatacount'])&&$_POST['refereedatacount']>0){
		$refereedata=array();
		$refereedata['total']=$_POST['refereedatacount'];
		for($i=1;$i<=$_POST['refereedatacount'];$i++){
			$t=$i-1;
			if(isset($_POST['refereeorgname'.$i.''])){
				$reforgname=$_POST['refereeorgname'.$i.''];
				$reforgemail=$_POST['refereeemail'.$i.''];
				$reforgphone=$_POST['refereephone'.$i.''];
				$refcontactname=$_POST['refereename'.$i.''];

				$refereedata['data'][$t]['reforgname']=$reforgname;
				$refereedata['data'][$t]['reforgemail']=$reforgemail;
				$refereedata['data'][$t]['reforgphone']=$reforgphone;
				$refereedata['data'][$t]['refcontactname']=$refcontactname;
			}
		}
		$references=json_encode($refereedata);
	}
	if($references!==""){
		genericSingleUpdate("users","referees","$references","id","$entryid");

	}

	$spduration=mysql_real_escape_string($_POST['spduration']);
	genericSingleUpdate("users","spduration","$spduration","id","$entryid");
	$state=mysql_real_escape_string($_POST['state']);
	// get the state id
	$sdata=briefquery("SELECT * FROM state WHERE state='$state'",__LINE__,"mysqli");
	$cdata=$sdata['resultdata'][0];
	$numrows=$sdata['numrows'];
	// if there are any results then set the state id to the current id
	$sid=$numrows>0?$cdata['id_no']:0;
	genericSingleUpdate("users","state","$sid","id","$entryid");

	$address=mysql_real_escape_string($_POST['address']);
	genericSingleUpdate("users","address","$address","id","$entryid");
	$status=mysql_real_escape_string($_POST['status']);
	genericSingleUpdate("users","status","$status","id","$entryid");
	$activationstatus=mysql_real_escape_string($_POST['activationstatus']);
	genericSingleUpdate("users","activationstatus","$activationstatus","id","$entryid");
	// send verification email if activation status was changed to active
	if($udata['activationstatus']=="inactive" && $activationstatus=="active"&&
		$status=="active"){
		$title="Your account has been verified";
		$content='
			<h2 class="h2fjc">Verification Complete</h2>
			We at MySalvus are glad you want to inform you that your account has been
			Verified.<br>
			This means you now have full access our platform\'s Features so,
			<a href="'.$host_addr.'completion.php?t=spconfirm&h='.$hash.'.'.$uid.'">
			login.
			</a> to you account and get started.
						
		';
	  	$content=stripslashes($content);
	  	$footer='
	    
	  	';

	  	$emailout=generateMailMarkUp("mysalvus.org","$email","$title","$content","$footer","fjc");
	    // // echo $emailout['rowmarkup'];
	    $toemail="$email";
	    $headers = "MIME-Version: 1.0" . "\r\n";
	    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	    $headers .= 'From: <no-reply@mysalvus.org>' . "\r\n";
	    $subject="Your MySalvus Account has been verified";
	    if($host_email_send===true){
	      if(mail($toemail,$subject,$emailout['rowmarkup'],$headers)){

	      }else{
	        die('could not send Your email, something went wrong and we are handling it, meantime you could click the back button in your browser to get you out of here, we are really sorry');
	      }
	    }else{
	    	// view the email markup
	    	// echo $emailout['rowmarkup'];
	    	// stop redirection
	    	// $entryvariant="";
	    }
	}
	$bio=mysql_real_escape_string($_POST['bio']);
	genericSingleUpdate("users","businessdescription","$bio","id","$entryid");

	$uid=$entryid;
	for($i=0;$i<=1;$i++){
		$i==0?$fname="orgprofile":$fname="orgcac";
		$i==0?$fid="orgprofileid":$fid="orgcacid";
		$curid=mysql_real_escape_string($_POST[''.$fid.'']);
		$contentpic=isset($_FILES[''.$fname.'']['tmp_name'])?$_FILES[''.$fname.'']['tmp_name']:"";
	    if($contentpic!==""){
	      $image="$fname";
	      $maintype=$fname;
	      $nm=$_FILES[''.$fname.'']['name'];
	      // check the type of the file
	      $ft=getExtensionAdvanced($nm);
	      $imagepath2="";
	      $imagepath3="";
	      $width=0;
	      $height=0;
	      $ftype=$ft['type'];
		  // echo "contentpic: $contentpic  name: $nm filetype: $ftype<br>";
	      if($ftype=="image"){
		      $imgpath[0]='../files/originals/';
		      $imgpath[1]='../files/medsizes/';
		      $imgpath[2]='../files/thumbnails/';
		      $imgsize[0]="default";
		      $imgsize[1]=",150";
		      $imgsize[2]=",80";
		      $acceptedsize="";
		      $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
		      $len=strlen($imgouts[0]);
		      $imagepath=substr($imgouts[0], 1,$len);
		      // medsize
		      $len2=strlen($imgouts[1]);
		      $imagepath2=substr($imgouts[1], 1,$len2);

			  // medsize
		      $len3=strlen($imgouts[2]);
		      $imagepath3=substr($imgouts[2], 1,$len3);	      

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
	      }else{
	      	$outs=simpleUpload($image,'../files/');
			$filepath=$outs['filelocation'];
			$imagepath=substr($filepath, 1,strlen($filepath));
			$filesize=$outs['filesize'];
	      }
	      //$coverpicid=getNextId("media");
	      //maintype variants are original, medsize, thumb for respective size image.
	      if($curid<1){
		      $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,
		      	location,medsize,thumbnail,filesize,width,height)
		      VALUES
		      ('$uid','$usertype','$maintype','$ftype','$imagepath','$imagepath2',
		      	'$imagepath3','$filesize','$width','$height')";
		      // echo $mediaquery;
			  $qdata=briefquery($mediaquery,__LINE__,"mysqli",true);
	      	
	      }else{
		      	$imgdata=getSingleMediaDataTwo($curid);
		        $prevpic=$imgdata['location'];
		        $prevmed=$imgdata['medsize'];
		        $prevthumb=$imgdata['thumbnail'];
		        $realprevpic=".".$prevpic;
		        $realprevmed=".".$prevmed;
		        $realprevthumb=".".$prevthumb;
		        if(file_exists($realprevpic)&&$realprevpic!=="."){
		          unlink($realprevpic);
		        }
		        if(file_exists($realprevmed)&&$realprevmed!=="."){
		          unlink($realprevmed);
		        }
		        if(file_exists($realprevthumb)&&$realprevthumb!=="."){
		          unlink($realprevthumb);
		        }
		        genericSingleUpdate("media","location",$imagepath,"id",$curid);
		        genericSingleUpdate("media","medsize",$imagepath2,"id",$curid);
		        genericSingleUpdate("media","thumbnail",$imagepath3,"id",$curid);
		        genericSingleUpdate("media","filesize",$filesize,"id",$curid);
		        genericSingleUpdate("media","width",$width,"id",$curid);
		        genericSingleUpdate("media","height",$height,"id",$curid);
	      }

	    }
	}
	$_SESSION['lastsuccess']=0;
	$compid=26;
	if($entryvariant=="editclientaccadmin"){
		header('location:../admin/adminindex.php?compid=26&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else if($entryvariant=="editclientacc"){
		$utype="serviceprovider";
		$returl=isset($_POST['returl'])?$_POST['returl']:"";
		createNotification($entryid,"users","update","Client account entry was updated");
		$url='../clientdashboard.php?compid=26&type='.$entryvariant.'&v=admin&d='.$entryid.'';
		header('location:'.$url.'');
	}
	
}else if ($entryvariant=="editincidentadmin"||$entryvariant=="editincident") {
	# code...
	$reporttype=mysql_real_escape_string($_POST['reporttype']);
	$userid=mysql_real_escape_string($_POST['userid']);
	$incidentdetails=mysql_real_escape_string($_POST['incidentdetails']);
	genericSingleUpdate("incident","details","$incidentdetails","id","$entryid");
	
	$editabusercount=isset($_POST['editabusercount'])?mysql_real_escape_string($_POST['editabusercount']):0;
	$abusercount=mysql_real_escape_string($_POST['abusercount']);
	$status=mysql_real_escape_string($_POST['status']);
	genericSingleUpdate("incident","status","$status","id","$entryid");
	if($status=="inactive"){
		// disable any ongoing cases for the current incident
	}
	$incidentnature=mysql_real_escape_string($_POST['incidentnature']);
	genericSingleUpdate("incident","incidentnature","$incidentnature","id","$entryid");
	$incidentnaturedetails=mysql_real_escape_string($_POST['incidentnaturedetails']);
	genericSingleUpdate("incident","incidentnaturedetails","$incidentnaturedetails","id","$entryid");
	// id for current entry
	// $cid=0;
	
	// Incident report (Self)
	if($reporttype=="self"){
		$udata=getSingleUserPlain($userid);
		$ustate=$udata['state'];
		genericSingleUpdate("incident","sstate","$ustate","id","$entryid");
		$uage=$udata['age'];
		genericSingleUpdate("incident","sage","$uage","id","$entryid");
		$incidentfrequency=mysql_real_escape_string($_POST['incidentfrequency']);
		genericSingleUpdate("incident","incidentfrequency","$incidentfrequency","id","$entryid");
		$incidentstarttime=mysql_real_escape_string($_POST['incidentstarttime']);
		genericSingleUpdate("incident","incidentstarttime","$incidentstarttime","id","$entryid");
		$incidentlocation=mysql_real_escape_string($_POST['incidentlocation']);
		genericSingleUpdate("incident","incidentlocation","$incidentlocation","id","$entryid");
		$weaponuse=mysql_real_escape_string($_POST['weaponuse']);
		genericSingleUpdate("incident","weaponuse","$weaponuse","id","$entryid");
		$weapon=mysql_real_escape_string($_POST['weapon']);
		genericSingleUpdate("incident","weapons","$weapon","id","$entryid");
		$threat=mysql_real_escape_string($_POST['threat']);
		genericSingleUpdate("incident","threats","$threat","id","$entryid");
		$restraint=mysql_real_escape_string($_POST['restraint']);
		genericSingleUpdate("incident","restraints","$restraint","id","$entryid");
		$physicalinjury=mysql_real_escape_string($_POST['physicalinjury']);
		genericSingleUpdate("incident","physicalinjury","$physicalinjury","id","$entryid");
		$ireported=mysql_real_escape_string($_POST['ireported']);
		genericSingleUpdate("incident","ireported","$ireported","id","$entryid");
		$ireporteddate=mysql_real_escape_string($_POST['ireporteddate']);
		genericSingleUpdate("incident","ireporteddate","$ireporteddate","id","$entryid");
		$ireportedlocation=mysql_real_escape_string($_POST['ireportedlocation']);
		genericSingleUpdate("incident","ireportedlocation","$ireportedlocation","id","$entryid");
		$ireportedaid=mysql_real_escape_string($_POST['ireportedaid']);
		genericSingleUpdate("incident","ireportedaid","$ireportedaid","id","$entryid");
	}

	// third party form data
	if($reporttype=="thirdparty"){
		$firstname=mysql_real_escape_string($_POST['firstname']);
		$middlename=mysql_real_escape_string($_POST['middlename']);
		$lastname=mysql_real_escape_string($_POST['lastname']);
		$fullname=$firstname.' '.$middlename.' '.$lastname;
		genericSingleUpdate("incident","survivorsname","$fullname","id","$entryid");
		$gender=mysql_real_escape_string($_POST['gender']);
		genericSingleUpdate("incident","sgender","$gender","id","$entryid");
		$age=mysql_real_escape_string($_POST['age']);
		genericSingleUpdate("incident","sage","$age","id","$entryid");
		$saddress=mysql_real_escape_string($_POST['address']);
		genericSingleUpdate("incident","saddress","$saddress","id","$entryid");
		$phonenumber=mysql_real_escape_string($_POST['phonenumber']);
		genericSingleUpdate("incident","sphone","$phonenumber","id","$entryid");
		$email=mysql_real_escape_string($_POST['email']);
		genericSingleUpdate("incident","semail","$email","id","$entryid");
		$sdisability=mysql_real_escape_string($_POST['sdisability']);
		genericSingleUpdate("incident","sdisability","$sdisability","id","$entryid");
		$sdisabilitydetails=mysql_real_escape_string($_POST['sdisabilitydetails']);
		genericSingleUpdate("incident","sdisabilitydetails","$sdisabilitydetails","id","$entryid");
		$pcmethod=mysql_real_escape_string($_POST['pcmethod']);
		genericSingleUpdate("incident","spcmethod","$pcmethod","id","$entryid");
		$state=mysql_real_escape_string($_POST['state']);
		$sdata=briefquery("SELECT * FROM state WHERE state='$state'",__LINE__,"mysqli");
		$cdata=$sdata['resultdata'][0];
		$numrows=$sdata['numrows'];
		// if there are any results then set the state id to the current id
		$sid=$numrows>0?$cdata['id_no']:0;
		if($sid>0){
			genericSingleUpdate("incident","sstate","$sid","id","$entryid");

		}
		
	}

	$uid=$entryid;
	// abuser details entry
	$ncnt=0;
	for ($i=1; $i <= $abusercount ; $i++) { 
		# code...
		$abuserfullname=isset($_POST['abuserfullname'.$i.''])?mysql_real_escape_string($_POST['abuserfullname'.$i.'']):"";
		$abusergender=isset($_POST['abusergender'.$i.''])?mysql_real_escape_string($_POST['abusergender'.$i.'']):"";
		$aidentifiable=isset($_POST['aidentifiable'.$i.''])?mysql_real_escape_string($_POST['aidentifiable'.$i.'']):"";
		$abuserrelation=isset($_POST['abuserrelation'.$i.''])?mysql_real_escape_string($_POST['abuserrelation'.$i.'']):"";
		$abuserrelationdetails=isset($_POST['abuserrelationdetails'.$i.''])?mysql_real_escape_string($_POST['abuserrelationdetails'.$i.'']):"";	
		if($abuserfullname!==""&&$abusergender!==""&&$aidentifiable!==""&&$abuserrelation!==""&&$uid!==0){
			$query="INSERT INTO abusers (incidentid,fullname,gender,aidentifiable,
				abuserrelationship,arelationshipdetails)
			VALUES
			('$uid','$abuserfullname','$abusergender','$aidentifiable','$abuserrelation','$abuserrelationdetails')";
			// echo '<br>'.$query;
			$qdata=briefquery($query,__LINE__,"mysqli",true);
			$ncnt++;
		}
	}

	// edit previous abuser details
	$dcnt=$editabusercount;
	$raid=0;
	$draid=0;
	for ($i=$editabusercount; $i>0 ; $i--) { 
		# code...
		// abuserid - aid
		$aid=mysql_real_escape_string($_POST['abuserid'.$i.'']);

		
		$abuserstatus=mysql_real_escape_string($_POST['abuserstatus'.$i.'']);
		// delete the current entry if the delete val is set to do that
		if($abuserstatus=="delete"){
			if($i>1){
				$dcnt--;
				$delq="DELETE FROM abusers WHERE id=$aid;";
				// echo $delq."<br>";
				$qdata=briefquery($delq,__LINE__,"mysqli",true);
				$updq="UPDATE abusers SET id=id-1 WHERE id>$aid;";
				$qdata=briefquery($updq,__LINE__,"mysqli",true);
			}else{
				if($ncnt>0){
					$dcnt--;
					$delq="DELETE FROM abusers WHERE id=$aid;";
					// echo $delq." end point<br>";
					$qdata=briefquery($delq,__LINE__,"mysqli",true);
					$updq="UPDATE abusers SET id=id-1 WHERE id>$aid;";
					$qdata=briefquery($updq,__LINE__,"mysqli",true);
				}
			}
			// watch for overtly reduced below zero
			$dcnt<0?$dcnt=0:$dcnt=$dcnt;
			$alterq="ALTER TABLE abusers AUTO_INCREMENT=1";
			// echo $delq." end point<br>";
			$qdata=briefquery($alterq,__LINE__,"mysqli",true);					
		}else{
			$editabuserfullname=mysql_real_escape_string($_POST['editabuserfullname'.$i.'']);
			genericSingleUpdate("abusers","fullname","$editabuserfullname","id","$aid");
			$editabusergender=mysql_real_escape_string($_POST['editabusergender'.$i.'']);
			genericSingleUpdate("abusers","gender","$editabusergender","id","$aid");
			$editaidentifiable=mysql_real_escape_string($_POST['editaidentifiable'.$i.'']);
			genericSingleUpdate("abusers","aidentifiable","$editaidentifiable","id","$aid");
			$editabuserrelation=mysql_real_escape_string($_POST['editabuserrelation'.$i.'']);
			genericSingleUpdate("abusers","abuserrelationship","$editabuserrelation","id","$aid");
			$editabuserrelationdetails=isset($_POST['editabuserrelationdetails'.$i.''])?mysql_real_escape_string($_POST['editabuserrelationdetails'.$i.'']):"";	
			genericSingleUpdate("abusers","arelationshipdetails","$editabuserrelationdetails","id","$aid");			
		}
	}
	$curabusercount=$dcnt+$ncnt;
	genericSingleUpdate("incident","abusercount","$curabusercount","id","$entryid");

	$_SESSION['lastsuccess']=0;
	if($entryvariant=="editincidentadmin"){
		header('location:../admin/adminindex.php?compid=27&type='.$entryvariant.'&v=admin&d='.$entryid.'');
	}else if($entryvariant=="editincident"){
		$returl=isset($_POST['returl'])?$_POST['returl']:"";
		createNotification($entryid,"incidents","update","Incident entry was updated");

		$url='../userdashboard.php?compid=27&type='.$entryvariant.'&v=admin&d='.$entryid.'';
		header('location:'.$url.'');
	}
	
}else if($entryvariant=="updateclientrequest"){
	// echo $entryvariant;
	$incidentid=mysql_real_escape_string($_POST['incidentid']);
	$crresponse=mysql_real_escape_string($_POST['caserequestresponse']);

	$spid=isset($_POST['spid'])?mysql_real_escape_string($_POST['spid']):"";
	$udata=getSingleUserPlain($spid);
	$spnature=$udata['businessnature'];
	$bizstate=$udata['state'];
	$testq="";
	if($crresponse=="accepted"){
		// accept the case
		$cid=getNextId("cases");
		$query="INSERT INTO cases (incidentid,spid,spnature,entrydate)
		VALUES
		('$incidentid','$spid','$spnature',CURRENT_DATE())";
		// update requests for the case
		$upq="UPDATE caserequests SET acceptancestatus='declined' WHERE spid!='$spid' AND incidentid='$incidentid' AND spnature='$spnature'";
		$upqdata=briefquery($upq,__LINE__,"mysqli",true);
		$upq="UPDATE caserequests SET acceptancestatus='accepted', acceptancedate=CURRENT_DATE WHERE spid='$spid' AND incidentid='$incidentid' AND spnature='$spnature'";
		// echo $upq;
		$upqdata=briefquery($upq,__LINE__,"mysqli",true);
		// insert the new case
		$qdata=briefquery($query,__LINE__,"mysqli",true);
		createNotification($cid,"cases","Created","Service provider case entry was created");
	}else if($crresponse=="declined"){	
		// decline the case
		$upq="UPDATE caserequests SET acceptancestatus='declined' WHERE spid='$spid' AND incidentid='$incidentid' AND spnature='$spnature'";
		$upqdata=briefquery($upq,__LINE__,"mysqli",true);
		createNotification($entryid,"caserequests","Update","Service provider Request entry was updated");
	}
	$url='../clientdashboard.php?compid=28&type='.$entryvariant.'&v=admin&d='.$entryid.'';
		header('location:'.$url.'');
		// test against previous sent requests of the same data

}else if($entryvariant=="updateclientcase"){
	// echo $entryvariant;
	$incidentid=mysql_real_escape_string($_POST['incidentid']);
	$cresolution=mysql_real_escape_string($_POST['caseresolution']);
	$resolutiondetails=mysql_real_escape_string($_POST['resolutiondetails']);
	$spid=isset($_POST['spid'])?mysql_real_escape_string($_POST['spid']):"";
	$udata=getSingleUserPlain($spid);
	$spnature=$udata['businessnature'];
	$bizstate=$udata['state'];

	genericSingleUpdate("cases","resolutionstatus","$cresolution","id","$entryid");
	genericSingleUpdate("cases","resolutiondetails","$resolutiondetails","id","$entryid");
	genericSingleUpdate("cases","resolutiondate",date("Y-m-d"),"id","$entryid");

	createNotification($entryid,"cases","Update","Service provider case entry was updated, resolution status was changed");
	$url='../clientdashboard.php?compid=29&type='.$entryvariant.'&v=admin&d='.$entryid.'';
	header('location:'.$url.'');
}else if($entryvariant=="transferclientcase"){
	// echo $entryvariant;
	$incid=$entryid;

	$resolutiondetails=mysql_real_escape_string($_POST['transferdetails']);
	$caseid=mysql_real_escape_string($_POST['caseid']);
	$oldspid=isset($_POST['oldspid'])?mysql_real_escape_string($_POST['oldspid']):"";
	$newspid=isset($_POST['newspid'])?mysql_real_escape_string($_POST['newspid']):"";
	$udata=getSingleUserPlain($oldspid);
	$spnature=$udata['businessnature'];
	$bizstate=$udata['state'];
	$query="INSERT INTO casetransfer (caseid,incidentid,spid,spnature,ispid,details,entrydate)
				VALUES
			('$entryid','$incid','$oldspid','$spnature','$newspid','$resolutiondetails',CURRENT_DATE())";
	// make sure there are no pending transfer with the exact data details for
	// current set
	$testq="SELECT * FROM casetransfer WHERE caseid='$caseid' AND incidentid='$incid' AND spid='$oldspid' AND acceptancestatus='pending'";
	// echo $testq;
	$tqdata=briefquery($testq,__LINE__,"mysqli");
	// echo $query;
	if($tqdata['numrows']<1){
		$qdata=briefquery($query,__LINE__,"mysqli",true);
		// update cases table
		$upq="UPDATE cases SET resolutionstatus='transfer'WHERE id='$caseid'";
		$upqdata=briefquery($upq,__LINE__,"mysqli",true);
		// createNotification($entryid,"cases","Update","Service provider case transfer was started");
		$msg="Successfully initiated case transfer";
	}else{
		$msg="Transfer already in progress";
		$success="false";
	}
	
	$url='../clientdashboard.php?compid=30&type='.$entryvariant.'&v=admin&d='.$entryid.'';
	header('location:'.$url.'');

}else if($entryvariant=="inboundtransfercase"){
	// echo $entryvariant;
	
	$crresponse=mysql_real_escape_string($_POST['casetransferresponse']);
	$spid=isset($_POST['spid'])?mysql_real_escape_string($_POST['spid']):"";
	$caseid=isset($_POST['caseid'])?mysql_real_escape_string($_POST['caseid']):"";

	$udata=getSingleUserPlain($spid);
	$spnature=$udata['businessnature'];
	$bizstate=$udata['state'];
	if($crresponse=="accepted"){
		// accept the case
		$query="UPDATE cases SET spid='$spid',resolutionstatus='ongoing',entrydate=CURRENT_DATE() WHERE id='$caseid'";
		// echo $query;
		// update the new case
		$qdata=briefquery($query,__LINE__,"mysqli",true);
		// update the case transfer
		genericSingleUpdate("casetransfer","acceptancestatus","accepted","id","$entryid");
		genericSingleUpdate("casetransfer","acceptancedate",date('Y-m-d'),"id","$entryid");
		createNotification($entryid,"casestransfer","Update","Inter service provider case transfer completed");
		createNotification($caseid,"cases","Update","Inter service provider case transfer completed");
	}else if($crresponse=="declined"){	
		// decline the case
		$query="UPDATE cases SET resolutionstatus='ongoing' WHERE id='$caseid'";
		genericSingleUpdate("cases","resolutionstatus","ongoing","id","$caseid");

		$upq="UPDATE casetransfer SET acceptancestatus='declined' WHERE spid='$spid' AND incidentid='$incidentid' AND spnature='$spnature'";
		$upqdata=briefquery($upq,__LINE__,"mysqli",true);
		createNotification($entryid,"casetransfer","Update","Inter service provider case transfer Service provider Request entry was Declined");
	}
	
	$url='../clientdashboard.php?compid=31type='.$entryvariant.'&v=admin&d='.$entryid.'';
	header('location:'.$url.'');

}else if($entryvariant=="editcasereportadmin"||$entryvariant=="editcasereport"){
	$reporttime=mysql_real_escape_string($_POST['reporttime']);
	$reporttitle=mysql_real_escape_string($_POST['reporttitle']);
	$t=mysql_real_escape_string($_POST['cid']);
	$reportdetails=mysql_real_escape_string($_POST['reportdetails_'.$t.'']);
	genericSingleUpdate("casereports","reportdate","$reporttime","id","$entryid");
	genericSingleUpdate("casereports","title","$reporttitle","id","$entryid");
	genericSingleUpdate("casereports","details","$reportdetails","id","$entryid");

	createNotification($cid,"casereports","Created","Service provider case report entry made");
	if($entryvariant=="editcasereportadmin"){
		$_SESSION['lastsuccess']=0;
		header('location:../admin/adminindex.php?compid=20&type='.$entryvariant.'&v=admin&d='.$uid.'');
	}else if($entryvariant=="editcasereport"){
		$returl=isset($_POST['returl'])?$_POST['returl']:"";
		$_SESSION['lastsuccess']=0;
		$url='../clientdashboard.php?compid=32&type='.$entryvariant.'&v=admin&d='.$uid.'';
		header('location:'.$url.'');
	}	
}elseif ($entryvariant=="resetpassword") {
	# code...
	$password=mysql_real_escape_string($_POST['password']);
	$checksum=mysql_real_escape_string($_POST['checksum']);
	$tab=$_POST['tab']?mysql_real_escape_string($_POST['tab']):"";
	genericSingleUpdate("recruits","pword",$password,"id",$entryid);
	// genericSingleUpdate("recruits","status","active","id",$entryid);
	$orderfields[]="userid";
	$orderfields[]="checksum";
	$ordervalues[]=$entryid;
	$ordervalues[]=$checksum;
	// update resetpassword so the checksum cant be used again
	genericSingleUpdate("resetpassword","status","inactive",$orderfields,$ordervalues);
	header('location:../index.php?t=reset');

}

?>