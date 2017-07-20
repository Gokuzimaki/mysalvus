<?php
	
	function writeRssData($blogid,$blogcatid){
		include('globalsmodule.php');
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
		include('globalsmodule.php');
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
		$mail->Username = 'no-reply@fromijaywithlove.com';
		$mail->Password = 'noreply';
		$mail->From = ''.$host_email_addr.'';
		$mail->FromName = ''.$host_default_blog_name.' '.$outs['blogtypename'].'';
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
			 <img src="'.$host_logo_image.'" alt="'.$host_admin_title_name.'" width="182" height="206" style="display: inline-block;" /><br>
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
			&copy; '.$host_admin_title_name.' '.$y.' Developed by Okebukola Olagoke.<br>
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
		$mail->AltBody = 'A new blog post from Ijay\'s website\n
						'.stripslashes($outs['title']).'
						Please visit '.$outs['pagelink'].' or '.$host_addr.'unsubscribe.php?t=1&tp='.$blogtypeid.'" to unsubscribe.
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
		 echo('Message could not be sent.'. $mail->ErrorInfo);
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
		 <img src="'.$host_target_addr.'images/muyiwalogo5.png" alt="'.$host_admin_title_name.'" width="182" height="206" style="display: inline-block;" /><br>
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
		&copy; '.$host_admin_title_name.' '.$y.' Developed by Okebukola Olagoke.<br>
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
		$mail->AltBody = 'A new blog post from '.$host_admin_title_name.'\'s website\n
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
		
		include('globalsmodule.php');
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
									<input type="text" placeholder="Enter Blog Name" name="name" class="curved" value="'.$name.'"/>
								</div>
								<div id="formend">
									Blog Description *<br>
									<textarea name="description" class="form-control" placeholder="Enter Blog Description" class="">'.$description.'</textarea>
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
		global $host_addr,$host_target_addr,$host_admin_title_name;
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
	  global $host_addr,$host_target_addr,$host_admin_title_name;
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
		$row['linkout']='<a href="'.$host_addr.'category.php?cid='.$id.'" title="Click to view the category '.$catname.'">'.$catnamemini.'</a>';
		$row['csilinkout']='<li><a href="'.$host_addr.'csicategories.php?cid='.$id.'" title="Click to view the category '.$catname.'">'.$catnamemini.'</a></li>';
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
		global $host_addr,$host_target_addr,$host_admin_title_name;
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
	  	if($outs['name']=="Project Fix Nigeria"||$outs['name']=="Frankly Speaking Africa"){
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
		global $host_addr,$host_target_addr,$host_admin_title_name;
		$row=array();
	  $query="SELECT * FROM comments WHERE id=$commentid";
	  $run=mysql_query($query)or die(mysql_error()." Line 1166");
	  $row=mysql_fetch_assoc($run);
	    //include gravatar
	  // include('Gravatar.php');
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
	  // Love langugage and ijays blog
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
		global $host_addr,$host_target_addr,$host_admin_title_name,$logpart;
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
	  include('globalsmodule.php');
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
	  $seometakeywords=isset($row['seometakeywords'])?$row['seometakeywords']:""; // for retrieving the prefered meta key words for a post
	  $scheduledpost=isset($row['scheduledpost'])?$row['scheduledpost']:""; // for retrieving the prefered meta key words for a post
	  $postperiod=isset($row['postperiod'])?$row['postperiod']:""; // for retrieving the prefered meta key words for a post
	  $spacedpostperiod=explode(" ", $postperiod);
	  $title=$row['title'];
	  $introparagraph=$row['introparagraph'];
	  // create the header description information portion
	  if($blogentrytype!=="gallery" && $blogentrytype!=="banner"){
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
	  $editvideocontentout=""; // for holding the video form section for editting
	  $editaudiocontentout=""; // for holding the audio form section for editting
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
	  $mediaquery="SELECT * FROM media WHERE ownerid=$id AND ownertype='blogentry' AND maintype='coverphoto' AND status='active' ORDER BY id DESC";
	  $mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 2846");
	  $coverdata=mysql_fetch_assoc($mediarun);
	  $coverphoto=$coverdata['location'];
	  $medianumrows=mysql_num_rows($mediarun);
	  if($coverid<1){
		    $coverphoto="".$host_default_poster."";

	  }
	  //control variables for varying editable type content, for video audio and schedule information
	  $schedulescriptout='';
	  $selectvidscripts='';
	  $selectaudioscripts='';
	  $scheduleedit="";
	  $editvideocontentout="";
	  $editaudiocontentout="";
	  $localaudioid=0;
	  $localvideoflvid=0;
	  $localvideomp4id=0;
	  $localvideo3gpid=0;
	  $localvideowebmid=0;
	  $localvideoflvup="";
	  $localvideomp4up="";
	  $localvideo3gpup="";
	  $localvideowebmup="";
	  $localvideoflvurls="";
	  $localvideomp4urls="";
	  $localvideo3gpurls="";
	  $localvideowebmurls="";
	  $videooutone="";
	  $videoouttwo="";
	  $audiooutone="";
	  $audioouttwo="";
	  $statusstyle="";
	  if($postperiod!=="0000-00-00 00:00:00" &&($scheduledpost=="yes"||$scheduledpost=="on")&&$row['status']=="schedule"){
		    $datetime1 = new DateTime("$postperiod"); // specified scheduled time
		    $datetime2 = new DateTime(); // current time 
		    if($datetime2<$datetime1){
		      	// the current time is less than the specified time for activating the post
		      	$postdateparts=explode(" ", $postperiod);
				$scheduleedit='
					<div class="col-md-12 emu-row" data-name="schedulesection">
					  <h4 class="emu-row section-marker-header text-center"><i class="fa fa-clock-o"></i> Schedule Section</h4>
					  <div class="col-md-12" style="height:46px;margin-bottom:46px;">
					       <!-- <div class="bootstrap-timepicker"> -->
				              <div class="form-group bootstrap-timepicker">
				                <label>Schedule Status:(putting this on disables the post until the time specified is reached)</label>
				                <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-clock-o"></i>
				                  </div>
				                  <select name="schedulestatus" class="form-control">
							          <option value="">--Choose--</option>
							          <option value="publish">Publish Now (Choosing this activates this post after submission)</option>
							          <option value="no">No</option>
							          <option value="yes">Yes</option>
							      </select>
				                </div><!-- /.input group -->
				              </div><!-- /.form group -->
				            <!-- </div> -->
				      </div>
					  <div class="col-md-6">
					    <!-- Date range -->
			            <div class="form-group">
			                <label>Post Date:</label>
			                <div class="input-group">
			                  <div class="input-group-addon">
			                    <i class="fa fa-calendar"></i>
			                  </div>
			                  <input type="text" name="scheduledate" class="form-control pull-right" id="reservation" value="'.$postdateparts[0].'"/>
			                </div><!-- /.input group -->
			            </div><!-- /.form group --> 
					  </div>
					  <div class="col-md-6" style="height:46px;margin-bottom:46px;">
					      <!-- <div class="bootstrap-timepicker"> -->
					        <div class="form-group bootstrap-timepicker">
					          <label>Post Time(default is 08:00:00, 24 hour clock):</label>
					          <div class="input-group">
					            <div class="input-group-addon">
					              <i class="fa fa-clock-o"></i>
					            </div>
					            <input type="text" name="scheduletime" data-inputmask="\'alias\': \'h:s:s\'" class="form-control timepicker" value="'.$postdateparts[1].'"/>
					          </div><!-- /.input group -->
					        </div><!-- /.form group -->
					      <!-- </div> -->
					  </div>
					</div>
				';
				$schedulescriptout='$("select[name=schedulestatus]").val("'.$scheduledpost.'")';
		    }
		    $statusstyle="hidden";
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
		    // for ijaysblog
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
		    $fiwlalbum="<li>No Gallery Photos Available for this post</li>";
		    $fiwlfullalbum="<li>No Gallery Photos Available for this post</li>";
		    $fiwlminialbum="<li>No Gallery Photos Available for this post</li>";
		    $maincoverphoto="";
			if($medianumrows>0){
				$count=0;
				$album="";
				$valbum="";
				$vfalbum="";
				$csialbum="";
				$csiminialbum="";
				$fiwlalbum="";
				$fiwlminialbum="";
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
				            <a href="'.$host_addr.''.$realimg.'" data-lightbox="admingallery" name="viewpic" data-id="'.$imgid.'" data-galleryid="'.$id.'" data-arraypoint="'.$count.'" data-src="'.$host_addr.''.$realimg.'"><img name="viewpic" src="../images/viewpicfirst.png" title="View full image" class="total"></a>                
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
				    // ijays albumdata
				    $fiwlalbum.='
				    	<li class="">
				            <img src="'.$host_addr.''.$mediarow['medsize'].'" alt="'.$title.' - Gallery Slide'.$countextra.'" />
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
				      $csiminialbum='
				        <li class="">
				            <img src="'.$host_addr.''.$mediarow['details'].'" alt="'.$title.' - Gallery Slide'.$countextra.'" />
				        </li>
				      ';
				      $fiwlminialbum.='
				        <li class="">
				            <img src="'.$host_addr.''.$mediarow['thumbnail'].'" alt="'.$title.' - Gallery Slide'.$countextra.'" />
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
				$fiwlalbum='
				  <ul class="bxslider">
				        '.$fiwlalbum.'
				  </ul>
				';
				$fiwlminialbum='
				  <ul class="bxslider">
				        '.$fiwlminialbum.'
				  </ul>
				';
				// csi album
				$row['csialbum']=''.$csialbum.'';
				$row['csiminialbum']=''.$csiminialbum.'';
				// fromijaywithlove album
				$row['fiwlalbum']=''.$fiwlalbum.'';
				$row['fiwlminialbum']=''.$fiwlminialbum.'';
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
	  		$extraformdata='
		      <input type="hidden" name="blogentrytype" value="audio"/>
		    ';
	  		$editcoverphotostyle="";
		    $editcoverphotofloatstyle="";
		    $editintroparagraphstyle="";
		    $editblogpoststyle="";
		    $defaultcsipostflaticon="flaticon-speaker100";
		    if($betype=="local"){
		      $mediaquery="SELECT * FROM media WHERE ownerid=$id AND ownertype='blogentry' AND maintype='blogentryaudio' AND status='active' ORDER BY id DESC";
		      $mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 2087");
		      $medianumrows=mysql_num_rows($mediarun);
		      if($medianumrows>0){
		        $audiofileout="";
		        $mediarow=mysql_fetch_assoc($mediarun);
		        $audiofileout='<audio src="'.$host_addr.''.$mediarow['location'].'" class="post-audio" preload="none" controls>No support detected Download <a href="'.$host_addr.''.$mediarow['location'].'">Audio</a> instead</audio>';
		      }else{
		        $audiofileout='<p>No Audio file</p>';
		      }
		      $audiooutone=$audiofileout;
		    }elseif ($betype=="embed") {
		      $audiofileout=$becode;
		      $audioouttwo=$audiofileout;
		    }
		    $selectaudioscripts='$("select[name=audiotype]").val("'.$betype.'");';
		    $editaudiocontentout='
		      <div class="col-md-12 emu-row">
		        <h4 class="emu-row section-marker-header text-center"><i class="fa fa-volume-up"></i> Audio Section</h4>
		        <div class="col-md-12 emu-row text-center">
		          Audio Type <br>
		          <select name="audiotype">
		            <option value="">--Choose--</option>
		            <option value="local">Local Audio</option>
		            <option value="embed">Embedded</option>
		          </select>
		        </div>
		        <div class="col-md-6" data-name="local" >
		          Audio file *<br>
		          '.$audiooutone.'
		          <input type="file" placeholder="Choose a mp3 file" name="audio" class="form-control"/>
		        </div>
		        <div class="col-md-6" data-name="embed" >
		          Audio Embed Code <br>
		          <textarea placeholder="Provide the audio embed code" name="audioembed"  class="form-control">'.$audioouttwo.'</textarea>
		          '.$audioouttwo.'
		        </div>
		      </div>
		    ';
		    if(strtolower($blogtypedata['name'])=="from ijay with love"){
		    	$row['blogpostout']="$blogpostout";
		    }
	  }else if($blogentrytype=="video"){
	  		$extraformdata='
		      <input type="hidden" name="blogentrytype" value="video"/>
		    ';
	  		$editcoverphotostyle="";
		    $editcoverphotofloatstyle="";
		    $editintroparagraphstyle="";
		    $editblogpoststyle="";
		    $defaultcsipostflaticon="fa fa-video-camera";
		    if($betype=="local"){
		      $mediaquery="SELECT * FROM media WHERE ownerid=$id AND ownertype='blogentry' AND maintype='blogentryvideo' AND status='active' ORDER BY id DESC";
		      $mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 2087");
		      $medianumrows=mysql_num_rows($mediarun);
		      if($medianumrows>0){
		        $videofileout="";
		        while ($mediarow=mysql_fetch_assoc($mediarun)) {
		          # code...
		          if($mediarow['mediatype']=="videowebm"){
		            $localvideowebmid=$mediarow['id'];
		            $localvideowebmup='<i class="fa fa-check"></i>';
		        	$localvideowebmurls='<source src="'.$host_addr.''.$mediarow['location'].'"/>';
		          }
		          if($mediarow['mediatype']=="videoflv"){
		            $localvideoflvid=$mediarow['id'];
		            $localvideoflvup='<i class="fa fa-check"></i>';
		        	$localvideoflvurls='<source src="'.$host_addr.''.$mediarow['location'].'"/>';
		          }
		          if($mediarow['mediatype']=="videomp4"){
		            $localvideomp4id=$mediarow['id'];
		            $localvideomp4up='<i class="fa fa-check"></i>';
		        	$localvideomp4urls='<source src="'.$host_addr.''.$mediarow['location'].'"/>';
		          }
		          if($mediarow['mediatype']=="video3gp"){
		            $localvideo3gpid=$mediarow['id'];
		            $localvideo3gpup='<i class="fa fa-check"></i>';
		        	$localvideo3gpurls='<source src="'.$host_addr.''.$mediarow['location'].'"/>';
		          }
		        }
		        $videofileout="
		        	$localvideowebmurls
		        	$localvideoflvurls
		        	$localvideomp4urls
		        	$localvideo3gpurls
		        ";
		        $videoscript="";
		        if(isset($host_blog_play_limit)){
			        $videoscript='
			        	<script>
			            	$(document).ready(function(){
			            		var video_'.$id.' = document.querySelector(\'video.blog-video-style._'.$id.'\');
								video_'.$id.'.addEventListener(\'timeupdate\', function() {
								  // don\'t have set the startTime yet? set it to our currentTime
								  if (!this._startTime) this._startTime = this.currentTime;

								  var playedTime = this.currentTime - this._startTime;

								  if (playedTime >= '.$host_blog_play_limit.'){
								  		this.pause();
									  	$(this).removeAttr("controls");
								  } 
								});

								video_'.$id.'.addEventListener(\'seeking\', function() {
								  // reset the timeStart
								  this._startTime = undefined;

								  $(this).removeAttr("controls");
								  console.log($(this));
								});
			            	});
			            </script>
			        ';
		        }

		        $videofileout='
		            <video title="" id="example_video_1" class="video-js vjs-default-skin blog-video-style _'.$id.'" controls preload="true" height="" style="" poster="'.$host_addr.''.$coverphoto.'" data-setup="{}">
		                '.$videofileout.'
		            </video>
		            '.$videoscript.'
		        ';
		      }else{
		        $videofileout='<p>No Video File </p>';
		      }
		      $videooutone=$videofileout;
		    }elseif($betype=="embed"){
		      $videofileout=$becode;
		      $videoouttwo=$videofileout;
		    }
		    $selectvidscripts='$("select[name=videotype]").val("'.$betype.'");';
		    $editvideocontentout='
		      <div class="col-md-12 emu-row" data-name="">
		        <h4 class="emu-row section-marker-header text-center"><i class="fa fa-film"></i> Video Section</h4>
				<div class="col-md-12 emu-row text-center">
					Video Type <br>
					<select name="videotype">
					  <option value="">--Choose--</option>
					  <option value="local">Local Video</option>
					  <option value="embed">Embedded</option>
					</select>
				</div>
		          <div class="col-md-12 emu-row" data-name="localvideo" >
		            Video Files *(you can upload more than one video codec type as specified, but it is advisable you do your video uploads one at a time i.e upload first, edit and upload more later)<br>
		            <p class="col-md-3">
		              FLV '.$localvideoflvup.'<br>
		              <input type="file" placeholder="Choose a video file" name="videoflv" class="curved"/>
		            </p>
		            <p class="col-md-3">
		              MP4 '.$localvideomp4up.'<br>
		              <input type="file" placeholder="Choose a video file" name="videomp4" class="curved"/>
		            </p>
		            <p class="col-md-3">
		              3GP '.$localvideo3gpup.'<br>
		              <input type="file" placeholder="Choose a video file" name="video3gp" class="curved"/>
		            </p>
		            <p class="col-md-3">
		              WEBM '.$localvideowebmup.'<br>
		              <input type="file" placeholder="Choose a video file" name="videowebm" class="curved"/>
		            </p>
		            '.$videooutone.'
		          </div>
		          <div class="col-md-12 emu-row" data-name="embedvideo" >
		            Video Embed Code *<br>
		            <textarea placeholder="Place your embed code here" name="videoembed"  class="form-control">'.$becode.'</textarea>
		            '.$videoouttwo.'
		          </div>
		        </div>
		    ';
		    $row['blogpostout']="$blogpostout";
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
	  $postmediaextra="";
	  if($blogentrytype==""||$blogentrytype=="normal"){
		    $viewerbodyoutone='   <img src="'.$absolutecover.'"/>'.$introparagraph.'';
		    $linkcontentout="Continue Reading";
		    if($row['blogtypeid']==1){
		      // $viewerbodyoutone='   <img src="'.$absolutecover.'" class="element-post-default-image" alt="'.$title.'"/>';
		      // $viewerbodyouttwo=$viewerbodyoutone;
		    	$linkcontentout='Read More';
		    }
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
		    
		    // fiwl gallery post
		    if($row['blogtypeid']==1){
		      $viewerbodyoutone=$fiwlminialbum;
		      $viewerbodyouttwo=$fiwlalbum;
		      $postmediaextra='
		      	<!-- POST GALLERY START -->
				<div class="post-gallery">
					'.$viewerbodyouttwo.'
				</div>
				<!-- POST GALLERY END -->
		      ';
		    }
		    $linkcontentout="View Photos";
	  }elseif ($blogentrytype=="video") {
		    # code...
		    // $viewerbodyoutone=$videofileout;
		    $linkcontentout="View Full Video";
		    $viewerbodyoutone='   <img src="'.$absolutecover.'"/>'.$introparagraph.'';
		    if($row['blogtypeid']==1){
		      $viewerbodyoutone=$videofileout;
		      $viewerbodyouttwo=$viewerbodyoutone;
		      $postmediaextra='
		      		<!-- POST VIDEO START -->
					<div class="post-video">
						'.$videofileout.'
					</div>
					<!-- POST VIDEO END -->
		      ';
		      $llseperatecontent='
		    		<div class="desc full-width">
			          <div class="post-desc-top text-center">
			            <ul>
			              <li>'.$host_admin_title_name.'</li>
			              <li class="separate"></li>
			              <li>'.$maindayout.'</li>
			            </ul>
			            <h3><a href="'.$pagelink.'">'.$title.'</a></h3>
			          </div>
			          <div class="post-desc-bottom">
			            <div class="text-center">
			            	'.$videofileout.'
			            </div>
			            <div>
			            	'.$introparagraph.'
			            </div>
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
			            <a class="more-button" href="'.$pagelink.'"><span>'.$linkcontentout.'</span></a>
			          </div>
			        </div>';
		      $llseperatecontentformat='video';
		    }
	  }elseif ($blogentrytype=="audio") {
		    # code...
		    // $viewerbodyoutone='<img src="'.$absolutecover.'"/>'.$introparagraph.'';
		    $linkcontentout="Listen";
		    $viewerbodyoutone=$audiofileout;
		    $viewerbodyouttwo=$viewerbodyoutone;
		    $postmediaextra='
		      		<!-- POST AUDIO START -->
					<div class="post-audio">
						'.$viewerbodyouttwo.'
					</div>
					<!-- POST AUDIO END -->
		    ';
	  }
	  // for handling normal posted content
	  $row['adminoutput']='
		    <tr data-id="'.$id.'">
		    	<td class="tdimg"><img src="'.$host_addr.''.$coverphoto.'"/></td><td>'.$link.'</td><td>'.$blogtypedata['name'].'</td><td>'.$blogcategorydata['catname'].'</td><td>'.$commentdata['commentcount'].'</td><td>'.$views.'</td><td>'.$entrydate.'</td><td>'.$modifydate.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsingleblogpost" data-divid="'.$id.'">Edit</a></td>
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
	  //for handling scheduled post content
	  $row['adminoutputthree']='
		    <tr data-id="'.$id.'">
		      <td class="tdimg"><img src="'.$host_addr.''.$coverphoto.'"/></td><td>'.$title.'</td><td>'.$blogtypedata['name'].'</td><td>'.$blogcategorydata['catname'].'</td><td>'.$postperiod.'</td><td>'.$scheduledpost.'</td><td>'.$entrydate.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsingleblogpost" data-divid="'.$id.'">Edit</a></td>
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
	  $selectscripts='
		  <script>
		  	$(document).ready(function(){
			  	if(typeof($(document).datepicker)!=="undefined"){
					$(\'#reservation\').datepicker();
				}
			  	if(typeof($(document).inputmask)!=="undefined"){
			      $("#datemask").inputmask("dd-mm-yyyy", {"placeholder": "dd-mm-yyyy"});
			      //Datemask2 mm/dd/yyyy
			      $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
			      //Money Euro
			      $("[data-mask]").inputmask();
				}
				$(".timepicker").inputmask("hh:mm:ss", {
			        placeholder: "HH:MM:SS", 
			        insertMode: false, 
			        showMaskOnHover: false,
			        hourFormat: 12
			    })
			  	'.$selectvidscripts.'
			  	'.$selectaudioscripts.'
			  	'.$schedulescriptout.'
		  	})
		  </script>
	  ';
	  //blog post edit form
	  $row['adminoutputtwo']='
		    <script>
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
		                filemanager_title:"'.$host_admin_title_name.'\'s Admin Blog Content Filemanager" ,
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
		                filemanager_title:"'.$host_admin_title_name.'\'s Admin Blog Content Filemanager" ,
		                external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
		        });          
		    </script>
		  	<div id="form" style="background-color:#fefefe;">
		  		<form action="../snippets/edit.php" name="editblogpost" method="post" enctype="multipart/form-data">
		  			<input type="hidden" name="entryvariant" value="editblogpost"/>
		  			<input type="hidden" name="entryid" value="'.$id.'"/>
			        <input type="hidden" name="localaudioid" value="'.$localaudioid.'"/>
			        <input type="hidden" name="localvideoflvid" value="'.$localvideoflvid.'"/>
			        <input type="hidden" name="localvideomp4id" value="'.$localvideomp4id.'"/>
			        <input type="hidden" name="localvideo3gpid" value="'.$localvideo3gpid.'"/>
			        <input type="hidden" name="localvideowebmid" value="'.$localvideowebmid.'"/>
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
	    				Manually share this post, this is done as a site user so if you want to post to '.$host_admin_title_name.'\'s account be sure to log in as him/her first then share, don\'t worry the dialog box for sharing will have a login interface for you to do so unless you have logged in as someone else to any of these social networks, in that case you would have to log out then login as the social account you want to share this under, then you can post. <br>
	    				<div id="elementholder" style="float:none; margin:auto;">
	    				  '.$blogplatformminishares.'
	    				</div>
	  				</div>
	  				<div id="formend">
	  					Change Title<br>
	  					<input type="text" style="max-width:98%;width:87%;" placeholder="Blog Title" name="title" style="width:90%;" value="'.$title.'" class="curved"/>
	  				</div>
					'.$extraformdata.'
					'.$editvideocontentout.'
					'.$editaudiocontentout.'
	  				<div id="formend" style="'.$editintroparagraphstyle.'">
	  					<span style="font-size:18px;">Change Introductory Paragraph:</span><br>
	  					<textarea name="introparagraph" id="postersmalltwo" Placeholder="" class="">'.$introparagraph.'</textarea>
	  				</div>
	  				<div id="formend" style="'.$editblogpoststyle.'">
	  					<span style="font-size:18px;">Change The Blog Post:</span><br>
	  					<textarea name="blogentry" id="adminposter" Placeholder="" class="curved3">'.$blogpost.'</textarea>
	  				</div>
	  				'.$scheduleedit.'
	  				<div class="col-md-12 emu-row" data-name="seosection">
						<h4 class="emu-row section-marker-header text-center"><i class="fa fa-site-map"></i> SEO Section</h4>
						<div class="col-md-12 text-center">
						 	SEO key words(comma seperated list of keywords internet user could use in searching for this post)<br>
							<textarea placeholder="Provide the keywords" name="seometakeywords"  class="form-control">'.$seometakeywords.'</textarea>
						</div>
					</div>
	  				'.$commentcontent.'

	  				'.$selectscripts.'
	  				<div id="formend" class="'.$statusstyle.'">
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
	  //plain blog post sharing option, no need for facebook sdk
	  $row['blogpageshareplain']='
		    <div class="mainblogshare">
		    	<a class="mainblogsharelink" title="Share this on Facebook" href="http://www.facebook.com/sharer/sharer.php?&u='.$pagelink.'&p[images][0]='.$absolutecover.'&p[title]='.$title.'&p[summary]='.$headerdescription.'" target="_blank"><img class="mainblogshareimg" src="'.$host_addr.'images/facebookshareimg.png"/></a>
		    </div>
		    <div class="mainblogshare">
		    	<a class="mainblogsharelink" title="Share this on LinkedIn" href="http://www.linkedin.com/shareArticle?mini=true&amp;url='.$pagelink.'&amp;title='.$title.'&amp;summary='.$headerminidescription.'" target="_blank"><img class="mainblogshareimg" src="'.$host_addr.'images/linkedinshareimg.png"/></a>
		    </div>
		    <div class="mainblogshare">
		    	<a class="mainblogsharelink" title="Share this on Twitter" href="http://twitter.com/intent/tweet/?text='.$title.'&url='.$pagelink.'" target="_blank"><img class="mainblogshareimg" src="'.$host_addr.'images/twittershareimg.png"/></a>
		    </div>
		    <div class="mainblogshare">
		    	<a class="mainblogsharelink" title="Share this on GooglePlus" href="https://plus.google.com/share?url='.$pagelink.'" target="_blank"><img class="mainblogshareimg" src="'.$host_addr.'images/googleplusshareimg.png"/></a>
		    </div>
	  ';
	  $row['blogpagesharreshare']='
			<script src="'.$host_addr.'scripts/jquery.sharrre.min.js"></script>
	  		<script>
				$(document).ready(function(){
					$(\'#sharemapageme\').sharrre({
					  share: {
					    googlePlus: true,
					    facebook: true,
					    twitter: true,
					    linkedin: true
					  },
					  enableTracking: true,
					  buttons: {
					    googlePlus: {size: \'tall\', annotation:\'bubble\'},
					    facebook: {layout: \'box_count\'},
					    twitter: {count: \'vertical\'},
					    digg: {type: \'DiggMedium\'},
					    delicious: {size: \'tall\'}
					  },
					  hover: function(api, options){
					    $(api.element).find(\'.buttons\').show();
					  },
					  hide: function(api, options){
					    $(api.element).find(\'.buttons\').hide();
					  }
					});
				})
			</script>
			<div id="sharemapageme" data-url="'.$pagelink.'" data-text="'.$headerminidescription.'" data-title="'.$title.'" class="sharrre">
				<div class="box">
					<a class="count" href="#">0</a>
					<a class="share" href="#">'.$title.'</a>
				</div>
				<div class="buttons" style="display: none;">
					<div class="button facebook">
						<div id="fb-root"></div>
						<div class="fb-like" data-href="'.$pagelink.'" data-send="false" data-layout="box_count" data-width="" data-show-faces="false" data-action="share" data-colorscheme="" data-font="" data-via="undefined"></div>
					</div>
					<div class="button twitter"
						>
						<a href="https://twitter.com/share" class="twitter-share-button" data-url="'.$pagelink.'" data-count="vertical" data-text="Frontiers Job-Connect is a career platform connecting business organisations with quality employees for..." data-via="" data-hashtags="" data-related="" data-lang="en">Tweet</a>
					</div>
					<div class="button googleplus">
						<div class="g-plusone" data-size="tall" data-href="'.$pagelink.'" data-annotation="bubble"></div>
					</div>
					<div class="button linkedin">
						<script type="in/share" data-url="'.$pagelink.'" data-counter=""></script>
					</div>
				</div>
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
	  $row['blogminioutput']='
		    <div id="miniblogposthold">
		    	<a href="'.$pagelink.'" title="'.$headerdescription.'">
	    		<img src="'.$absolutecover.'"/>
	    		'.$title.'.</a>
	    		<span name="miniblogviewshold">Views '.$views.'</span><br><span name="miniblogviewshold">From '.$blogtypedata['name'].' under:<br> <span style="color:#F08D8D;">'.$blogcategorydata['catname'].'</span>
		    </div>
	  ';   
	  // Love Language and From Ijay with love
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
	              <div class="item-content">'.$headerdescription.'</div>
	              <a href="'.$pagelink.'" class="button" target="_blank">More</a>
	          </div>
	      </div><!-- /.item -->
	  ';
	  $row['blogminioutputcsitwo']='
	      <div class="item clearfix">
	          <div class="medium-4 columns">
	              <img alt="" src="'.$absolutecover.'" class="blog-cover-photo"/>
	          </div>
	          <div class="medium-8 columns">
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
	              <div class="item-content">'.$headerdescription.'</div>
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
	  $row['blogtinyoutputcsitwo']='
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
		    				By '.$host_admin_title_name.', On '.$entrydateout.', in the Category <a href="'.$host_addr.'category.php?cid='.$blogtypeid.'" target="_blank"><span name="titletype">'.$blogcategorydata['catname'].'</span></a>.Total Views '.$views.'.
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
		    		'.$row['blogpageshareplain'].'
		    	</div>
		    </div>
	  ';
	  $row['vieweroutputtwo']='
		    <div id="bloghold">
		    	<div id="blogheader">
		    		<span name="title">'.$title.'.</span>
		    		<div id="blogheaderdetailshold">
		    			<div id="blogheaderdetailsleft">
		    				By '.$host_admin_title_name.', On '.$entrydate.'. Total Views '.$views.'.
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
	  // for love language
	  // this section to handle varying final outputs for mini displays
	  // with videos and banners, e.t.c
	  isset($llseperatecontent)?$llseperatecontent:$llseperatecontent='
	  	  	<div class="image">
	          <a href="'.$pagelink.'"><img src="'.$absolutecover.'" alt="'.$blogcategorydata['catname'].'" /></a>
	          <div class="category">'.$blogcategorydata['catname'].'</div>
	        </div>
	        <div class="desc">
	          <div class="post-desc-top">
		            <ul>
		              <li>'.$host_admin_title_name.'</li>
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
		            <a class="more-button" href="'.$pagelink.'"><span>'.$linkcontentout.'</span></a>
	          </div>
	        </div>
	  ';
	  isset($llseperatecontentformat)?$llseperatecontentformat:$llseperatecontentformat='standard';
	  $row['vieweroutputfour']='
	      <article class="post format-'.$llseperatecontentformat.'">
	        '.$llseperatecontent.'
	      </article>
	  ';
	  /*// for love language
	  $row['vieweroutputfour']='
	      <article class="post format-standard">
	        <div class="image">
	          <a href="'.$pagelink.'"><img src="'.$absolutecover.'" alt="'.$blogcategorydata['catname'].'" /></a>
	          <div class="category">'.$blogcategorydata['catname'].'</div>
	        </div>
	        <div class="desc">
	          <div class="post-desc-top">
	            <ul>
	              <li>'.$host_admin_title_name.'</li>
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
	            <a class="more-button" href="'.$pagelink.'"><span>'.$linkcontentout.'</span></a>
	          </div>
	        </div>
	      </article>
	  ';*/
	  $bgworthy=str_replace(" ", "%20",$absolutecover);
	  // $bgworthy=str_replace("(", "\(",$bgworthy);
	  $bgworthy=str_replace(")", "\)",$bgworthy);

	  $row['vieweroutputllpostcarousel']='
			<!-- CAROUSEL ITEM START -->
			<div class="item">
				<!-- CAROUSEL CONTENT START -->
				<div class="carousel-content" style="background-image:url('.$bgworthy.');">
					<!-- CATEGORY START -->
					<div class="category">'.$blogcategorydata['catname'].'</div>
					<!-- CATEGORY END -->
					<!-- DESC START -->
					<div class="carousel-desc">
						<div class="carousel-desc-top">
							<div class="carousel-desc-top-left">
								<ul>
									<li>'.$host_admin_title_name.'</li>
									<li class="separate"></li>
									<li>'.$maindayout.'</li>
								</ul>
							</div>
							<div class="carousel-desc-top-right">
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
							</div>
						</div>
			            <h3><a href="'.$pagelink.'">'.$title.'</a></h3>
						<a class="more-button" href="'.$pagelink.'"><span>'.$linkcontentout.'</span></a>
					</div>
					<!-- END START -->
				</div>
				<!-- CAROUSEL CONTENT START -->
			</div>
			<!-- CAROUSEL ITEM END -->
	  ';
	  //for holding the extra display data for 
	  $row['vieweroutputpostmedia']=$postmediaextra;
	  return $row;
	}

	function getAllBlogEntries($viewer,$limit,$typeid,$type){
	  include('globalsmodule.php');
	  $testit=strpos($limit,"-");
	  $testit!==false?$limit="":$limit=$limit;
	  // echo $testit."testitval".$limit;
	  $row=array();
	  if(!is_array($viewer)&&$viewer=="admin"){	
		  if($type=="category"){
			  if($limit==""){
				  $query="SELECT * FROM blogentries WHERE blogcatid=$typeid  AND status!='schedule' order by id desc LIMIT 0,15";
				  $rowmonitor['chiefquery']="SELECT * FROM blogentries WHERE blogcatid=$typeid AND status!='schedule' order by id desc";
				  // echo $query.$rowmonitor['chiefquery'];
			  }else{
				  $query="SELECT * FROM blogentries WHERE blogcatid=$typeid AND status!='schedule' order by id desc $limit";
				  $rowmonitor['chiefquery']="SELECT * FROM blogentries WHERE blogcatid=$typeid AND status!='schedule' order by id desc";	
			  }
		  }elseif ($type=="blogtype") {
		  	  # code...
			  if($limit==""){
				  $query="SELECT * FROM blogentries WHERE blogtypeid=$typeid AND status!='schedule' order by id desc LIMIT 0,15";
				  $rowmonitor['chiefquery']="SELECT * FROM blogentries WHERE blogtypeid=$typeid AND status!='schedule' order by id desc";
			  }else{
				  $query="SELECT * FROM blogentries WHERE blogtypeid=$typeid AND status!='schedule' order by id desc $limit";
				  $rowmonitor['chiefquery']="SELECT * FROM blogentries WHERE blogtypeid=$typeid AND status!='schedule' order by id desc";	
			  }
		  }elseif ($type=="scheduledposts") {
		  	  # code...
			  if($limit==""){
				  $query="SELECT * FROM blogentries WHERE blogtypeid=$typeid AND status='schedule' order by id desc LIMIT 0,15";
				  $rowmonitor['chiefquery']="SELECT * FROM blogentries WHERE blogtypeid=$typeid AND status='schedule' order by id desc";
			  }else{
				  $query="SELECT * FROM blogentries WHERE blogtypeid=$typeid AND status='schedule' order by id desc $limit";
				  $rowmonitor['chiefquery']="SELECT * FROM blogentries WHERE blogtypeid=$typeid AND status='schedule' order by id desc";	
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
		    }elseif($rviewer=="admin"&&$limit!==""){
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
	  $row['adminoutputthree']="No Entries yet here";
	  $row['vieweroutput']="No Entries yet here";
	  $row['tinyoutput']="No more entries here";
	  $row['popularposts']="<br>No posts here yet";
	  $row['recentposts']="<br>No Posts here yet";
	  $row['adminoutputtwo']="";
	  $row['vieweroutputtwo']="";
	  $row['vieweroutputthree']="No Entries yet here";
	  $row['vieweroutputfour']="No Entries yet here";
	  $row['vieweroutputfive']="No Entries yet here";
	  $row['vieweroutputllpostcarousel']="No Entries yet here";
	  $row['chiefquery']=$rowmonitor['chiefquery'];
	  $row['numrows']=$numrows;
	  if($numrows>0){
	  	$adminoutput="";
	  	$adminoutputthree="";
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
		    $adminoutputthree.=$blogpostdata['adminoutputthree'];
		    $vieweroutput.=$blogpostdata['vieweroutput'];
		    $vieweroutputtwo.=$blogpostdata['vieweroutputtwo'];
		    // $vieweroutputthree.=$blogpostdata['vieweroutputthree'];
		    $vieweroutputfour.=$blogpostdata['vieweroutputfour'];
		    // $vieweroutputfive.=$blogpostdata['vieweroutputfive'];
			$tinyoutput.=$blogpostdata['blogtinyoutput'];
		}
	  	$top='<table id="resultcontenttable" cellspacing="0">
	  			<thead><tr><th>Coverphoto</th><th>PageAddress</th><th>Blogtype</th><th>Category</th><th><img src="'.$host_addr.'images/comments.png" style="height:20px;margin:auto;"></th><th>Views</th><th>PostDate</th><th>LastModified</th><th>Status</th><th>View/Edit</th></tr></thead>
	  			<tbody>';
  	  	$toptwo='<table id="resultcontenttable" cellspacing="0">
	  			<thead><tr><th>Coverphoto</th><th>Title</th><th>Blogtype</th><th>Category</th><th>Scheduled Date</th><th>Schedule Status</th><th>PostDate</th><th>Status</th><th>View/Edit</th></tr></thead>
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
		  <div id="paginateddatahold" data-name="contentholder">
		';

		$paginatebottom='
		  </div><div id="paginationhold">
		  	<div class="meneame">
		  		<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
		  	</div>
		  </div>
		';
		$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
		$row['adminoutputtwo']=$top.$adminoutput.$bottom;
		// for scheduled posts
		$row['adminoutputthree']=$paginatetop.$toptwo.$adminoutputthree.$bottom.$paginatebottom;
		$row['adminoutputfour']=$toptwo.$adminoutputthree.$bottom;
		// end
		$row['vieweroutput']=$vieweroutput;
		$row['vieweroutputtwo']=$vieweroutputtwo;
		$row['vieweroutputthree']=$vieweroutputthree;
		$row['vieweroutputfour']=$vieweroutputfour;
		$row['vieweroutputfive']=$vieweroutputfive;
		$row['chiefquery']=$rowmonitor['chiefquery'];
		$row['tinyoutput']=$tinyoutput;
		$row['numrows']=$numrows;

	  }
	  // off the usual outputs sections, this portion takes care of other unique behaviour
	  // for the blog post function
	  $recents="No Posts yet";
	  $recentsfjc="No Posts yet";
	  $recentfc="<li>No Posts yet</li>";
	  $recentspecific="No Posts yet";
	  $recentspecificll="<li>No Posts yet</li>";
	  $recentspecificllcarousel='<div class="item">No Posts yet</li>';


	  $popular="No posts yet";
	  $popularspecific="No posts yet";
	  $popularspecificll="<li>No Posts yet</li>";
	  $popularspecificllcarousel='<div class="item">No Posts yet</li>';
	  $popularspecificcsi="<p>No Posts yet</p>";
	  $popularspecificcsitwo="<li>No Posts yet</li>";
	  global $host_minipagecount; // control variable for amount of miniature blog post outputs to produce
	  if($viewer=="viewer"){
	    // produce recent blog posts
	    $recquery="SELECT * FROM blogentries WHERE status='active' order by id desc LIMIT 0,$host_minipagecount";
	    $recrun=mysql_query($recquery)or die(mysql_error()." Line 1835");
	    $recnumrows=mysql_num_rows($recrun);
	    if($recnumrows>0){
	      $recents="";
	      $recentsfjc="";
	      $recentsll="";
	      $recentsllcarousel="";
	      while($recrow=mysql_fetch_assoc($recrun)){
		      $outrec=getSingleBlogEntry($recrow['id']);
		      $recents.=$outrec['blogminioutput'];
		      $recentsll.=$outrec['blogminioutputll'];
		      $recentsllcarousel.=$outrec['vieweroutputllpostcarousel'];

	      }
	    }
	    // produce popular blog posts general
	    $popquery="SELECT * FROM blogentries WHERE status='active' order by views desc LIMIT 0,$host_minipagecount";
	    $poprun=mysql_query($popquery)or die(mysql_error()." Line 1835");
	    $popnumrows=mysql_num_rows($poprun);
	    $popular="";
	    $popularfjc="";
	    $popularll="";
	    $popularllcarousel="";
	    $popularcsi="";
	    if($popnumrows>0){
	      while($poprow=mysql_fetch_assoc($poprun)){
	        $outpop=getSingleBlogEntry($poprow['id']);
	        $popular.=$outpop['blogminioutput'];
	        $popularll.=$outpop['blogminioutputll'];
	        $popularllcarousel.=$outpop['vieweroutputllpostcarousel'];
	      }
	    }
	    $tipq=$type=="blogtype"?"WHERE blogtypeid=$typeid AND status='active'":"WHERE blogcatid=$typeid AND status='active'";
	    // recentspceific blogposts
	    $recquery="SELECT * FROM blogentries $tipq order by id desc LIMIT 0,$host_minipagecount";
	    $recrun=mysql_query($recquery)or die(mysql_error()." Line 1835");
	    $recnumrows=mysql_num_rows($recrun);
	    if($recnumrows>0){
	      $recentspecific="";
	      $recentspecificll="";
	      $recentspecificllcarousel="";
	      $recount=0;
	      while($recrow=mysql_fetch_assoc($recrun)){
	        $outrec=getSingleBlogEntry($recrow['id']);
	        $recentspecific.=$outrec['blogminioutput'];
	        $recentspecificll.=$outrec['blogminioutputll'];
	        $recentspecificllcarousel.=$outrec['vieweroutputllpostcarousel'];
	        $recount++;
	      }
	    }
	    // produce popular specific blog posts general
	    $popquery="SELECT * FROM blogentries $tipq order by views desc LIMIT 0,$host_minipagecount";
	    $poprun=mysql_query($popquery)or die(mysql_error()." Line 1835");
	    $popnumrows=mysql_num_rows($poprun);
	    if($popnumrows>0){
	      $popularspecific="";
	      $popularspecificll="";
	      $popularspecificllcarousel="";
	      $pcount=0;
	      while($poprow=mysql_fetch_assoc($poprun)){
	        $outpop=getSingleBlogEntry($poprow['id']);
	        $popularspecific.=$outpop['blogminioutput'];
	        $popularspecificll.=$outpop['blogminioutputll'];
	        $popularspecificllcarousel.=$outpop['vieweroutputllpostcarousel'];
	        $pcount++;
	      }
	    }
	  }
	      $row['popularposts']=$popular;
	      $row['recentposts']=$recents;
	      $row['popularpostspecific']=$popularspecific;
	      $row['popularpostspecificll']=$popularspecificll;
	      $row['popularpostspecificllcarousel']=$popularspecificllcarousel;

	      $row['recentpostspecific']=$recentspecific;
	      $row['recentpostspecificll']=$recentspecificll;
	      $row['recentpostspecificllcarousel']=$recentspecificllcarousel;

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
		          // $mostcvieweroutput=$mostcdata['blogminioutputcsi'];
		        }
	      }
	      $row['mostcommentedpostviewer']=$mostcvieweroutput;
	      $row['mostcommentedpostadmin']=$mostcadminoutput;

	      return $row;
	}

	function blogPageCreate($blogentryid){
		global $host_addr,$host_target_addr,$host_admin_title_name;
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
		$footer='&copy; '.$host_admin_title_name.' '.date('Y').' Developed by Okebukola Olagoke.';
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
		$blogsidebarposition='';
		$pagetag="";
		$descbanner="";
		$feedjitsidebar="";
		$quoteout="";
		$pagetypemini="";
		$topaudio="";
		$mpageactive3="";
		if($outs2['name']==""||$outs2['id']==1){
			$pagetag='';
			$pagetypemini="fiwl";
			$mpageactive3="activemainlink";
			$subimgpos='
				<div id="subimglogo" class="subimgpostwo">
					<img src="../../images/franklyspeakingthree.png" style="width: 100%;position: relative;left: 0px;top: 0px;" class="">
				</div>
			';
			$descbanner='
				<div id="formend">
				      <img src="'.$host_addr.'images/muyiwasblog.jpg" style="width:100%;"/>
				</div>
			  ';
			$blogsidebarposition='
				<!--<div id="adcontentholdlong" class="imgonly" name="csievent">
					<img src="'.$host_addr.'images/csiimages/olai 3.jpg"/>
					<img src="'.$host_addr.'images/csisidebar.jpg"/>
				</div>-->
			';
		  $feedjitsidebar=file_get_contents('../../snippets/feedjit.php');
		  $outsquote=getAllQuotes('viewer','','general');
		  $quoteout=$outsquote['randomoutput'];
		  $topaudioout=array();
		  $topaudioout['vieweroutput']="";
		  $topaudioout=function_exists("getAllTopAudio")?getAllTopAudio("viewer",""):"";
		  $topaudioout==""?$topaudioout['vieweroutput']="":$topaudioout;
		  $topaudio=$topaudioout['vieweroutput'];
		}
		$pagesidecontent='
			<div id="adcontentholdlong" style="">
				Recent Posts<br>
				'.$recents.'
			</div>
			<div id="adcontentholdshort" name="subscription">
				Subscribe
					<form name="franklyspeakingblogsubscription" method="POST" onSubmit="" action="'.$host_addr.'snippets/basicsignup.php">
						<input type="hidden" name="entryvariant" value="franklyspeakingblogsubscription"/>
						<div id="formend"><input type="text" style="text-align:center;"name="email" placeholder="Enter email here..." value=""class="curved"/></div>
						<div id="formend"><input type="button" class="submitbutton" name="franklyspeakingblogsubscription" value="Subscribe"/></div>
					</form>
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
		$toplinks='
			<a href="'.$host_addr.'index.php" name="home" title="Welcome to '.$host_admin_title_name.'\'S Website"><li class="">Home</li></a>
			<a href="'.$host_addr.'frontiersconsulting.php" name="frontiers" title="Frontiers International Services" class=""><li>Frontiers Consulting</li></a>
			<a href="'.$host_addr.'blog.php" name="blog" title="Check Out Muyiwa\'s Blog to get at his business and career radio talkshow content" class="'.$mpageactive3.'"><li>Muyiwa\'s Blog</li></a>
  			<a href="http://frontiersjobconnect.com" name="frontiersjobconnect" title="" class=""><li>Frontiers Job-Connect</li></a>
			<a href="'.$host_addr.'csioutreach.php" name="csi" title="Click to learn more about this social reformation project" class=""><li>CSI Outreach</li></a>
			<a href="'.$host_addr.'lovelanguage.php" name="lovelanguage" class=""><li>Love Language</li></a>
  			<a href="'.$host_addr.'ownyourowntwo.php" name="ownyourown" title="" class=""><li>Own Your Own</li></a>
			<a href="'.$host_addr.'onlinestore.php" name="onlinestore" title="Get all of '.$host_admin_title_name.'\'s posts in audio form and more" class=""><li>Online Store</li></a>
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
		$blogdisplayoutput="";
		/*New section for replacing legacy content slowly*/
		// hold the bread crumb social buttons
		$mpagecrumbsocial='
			<div class="section-title-social">
				<a class="social-pointer fa fa-twitter sociallink" target="_blank" href="http://www.twitter.com/franklyafolabi"></a>
				<a class="social-pointer fa fa-linkedin sociallink" target="_blank" href="http://www.linkedin.com/profile/view?id=37212987"></a>
				<a class="social-pointer fa fa-facebook sociallink" target="_blank" href="http://www.facebook.com/franklyspeakingwithmuyiwaafolabi"></a>
				<a class="social-pointer fa fa-google sociallink" target="_blank" href="http://www.facebook.com/franklyspeakingwithmuyiwaafolabi"></a>
				<a class="social-pointer fa fa-youtube sociallink" target="_blank" href="https://www.youtube.com/channel/UCcWNemsF-FuiWNVhwt9fEzQ"></a>
			</div>
		';
		$mpagecrumbpath='
			<section id="headline">
				<div class="container">
					<div class="section-title clearfix">
						<h2 class="crumb-heading fl-l">'.$outs['title'].'</h2>

						<ul id="breadcrumbs" class="fl-r">
							<li><a href="'.$host_addr.'">Home</a></li>
							<li><a href="'.$host_addr.'blog.php">Frankly speaking Blog</a></li>
							<li>'.$outs['title'].'</li>
						</ul>
					</div>
					'.$mpagecrumbsocial.'
				</div>
			</section>';
		//page mobile panel
		$mpagemobilepanel='
			<!-- mobile menu -->
			<div class="mobile-panel">
				<div class="mobile-panel-content-hold">
					<div class="mobile-panel-content">
						<ul class="mobile-links">
							
						</ul>
					</div>
				</div>

			</div>
		';
		if($outs['blogentrytype']==""||$outs['blogentrytype']=="normal"){
			$blogdisplayoutput='
			  <img class="blogcoverphoto" '.$coverstyle.' src="'.$outs['absolutecover'].'"/>
			  '.$outs['blogpostout'].'
			';
		}else if($outs['blogentrytype']=="video"){
			$blogdisplayoutput='
			  '.$outs['blogpostout'].'
			';
		}else if($outs['blogentrytype']=="audio"){
			$blogdisplayoutput='
			  '.$outs['blogpostout'].'
			';
		}elseif ($outs['blogentrytype']=="gallery") {
		# code...
			$blogdisplayoutput=$outs['fiwlalbum'];
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

		ga('create', 'UA-78709673-1', 'auto');
		ga('send', 'pageview');

		</script>
		";
		$ogimage=str_replace(" ","%20",$outs['absolutecover']);
		/*CONTROL BLOCK for holding critical inline style content for the blog page*/
			$inlinestyles='
				<style type="text/css">
					#example3{
					  float:left;
					  margin:63px 33% 0 33%;
					}
					#sharemapageme .box{
					  float:left;
					  margin:5% 8% 0 8%;
					  width:100%;
					}
					#sharemapageme .box a{
					  color:#F7F9FD;
					  text-align:left;
					  text-shadow: 0 1px 1px rgba(167,167,167,.4);
					}
					#sharemapageme .box a:hover{
					  text-decoration:none;
					}
					#sharemapageme .count {
					  font-weight:bold;
					  font-size:50px;
					  float:left;
					  border-right:2px solid #57b8d1;
					  line-height:40px;
					  padding-right:10px
					}
					#sharemapageme .share {
				        float: left;
					    margin-left: 10px;
					    font-size: 20px;
					    min-width: 82px;
					    max-width: 80%;
					}
					#sharemapageme .buttons {
					    position: absolute;
					    width: 294px;
					    background-color: #D10000;
					    border: 1px solid rgba(0,0,0,.2);
					    border-radius: 3px;
					    box-shadow: 0px 0px 3px 1px;
					    padding: 10px;
					    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.1);
					    -moz-box-shadow: 0 1px 2px rgba(0,0,0,.1);
					    box-shadow: 0px 0px 12px 1px rgb(0, 0, 0) inset;
					}
					#sharemapageme .button {
					  float:left;
					  max-width:50px;
					  margin:0 10px 0 0;
					}
					#sharemapageme .facebook {
					  margin:0 4px 0 0;
					}
				</style>
			';
		/*END*/

		/*CONTROL BLOCK for changing blog page output*/
		$switch="";
		if($outs2['name']=="Frontiers Job-Connect"){
			$switch="on";
			include('fjcblogpagemodule.php');
			$outputs=fjcblogPageCreate($blogentryid);
		}
		// own your own
		/*if($blogtypeid=="4"||$outs2['name']=="Own Your Own"){
			$switch="on";
			include('oyoblogpagemodule.php');
			$outputs=oyoblogPageCreate($blogentryid);
		}*/
		// csi switch
		if($blogtypeid=="2"){
			$switch="on";
			include('csiblogpagemodule.php');
			$outputs=csiblogPageCreate($blogentryid);
		}
		//From Ijay with love switch
		if($blogtypeid=="1"){
			$switch="on";
			include('lovelanguageblogpagemodule.php');
			$outputs=llblogPageCreate($blogentryid);
		}
		$outs['seometakeywords']!==""?$outs['seometakeywords'].=",":$outs['seometakeywords']="";
		if($outs['status']=="active"&&$switch==""){
			$outputs['outputpageone']='
				<!DOCTYPE html>
				<html>
					<head prefix="og:http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
						<title>'.stripslashes($outs['title']).'</title>
						<meta http-equiv="Content-Type" content="text/html;">
						<meta http-equiv="Content-Language" content="en-us">
						<meta charset="utf-8">
					    <meta http-equiv="X-UA-Compatible" content="IE=edge">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						<meta property="fb:app_id" content="223614291144392">
						<meta property="og:title" content="'.stripslashes($outs['title']).'">
						<meta property="og:type" content="website">
						<meta property="og:site_name" content="'.$host_admin_title_name.'\'s Website">
						<meta property="og:locale" content="en_US">
					    <meta property="fb:admins" content="">
						<meta property="og:author" content="'.$host_admin_title_name.'">
						<meta property="og:description" content="'.$headerdescription.'">
						<meta property="og:url" content="'.$outs['pagelink'].'">
						<meta property="og:image" content="'.$ogimage.'">
						<meta name="keywords" content="'.stripslashes($outs['seometakeywords']).''.stripslashes($outs['title']).', '.$host_admin_title_name.', '.$host_admin_title_name.', frontiers consulting, frankly speaking with '.$host_admin_title_name.', frankly speaking, motivational speaker in nigeria, business strategists in the world, reformation packages, Christ Society International Outreach, Project Fix Nigeria, Own Your Own, Nigerian career radio talk show"/>
						<meta name="description" content="'.stripslashes($outs['title']).' '.$headerdescription.'">
						<link rel="stylesheet" href="'.$host_addr.'bootstrap/css/bootstrap.css"/>
						<link rel="stylesheet" href="../../stylesheets/font-awesome/css/font-awesome.css"/>
						<link rel="stylesheet" href="../../stylesheets/muyiwamain.css"/>
						<link rel="stylesheet" href="../../stylesheets/responsive.css"/>
						<link rel="shortcut icon" type="image/png" href="../../images/muyiwaslogo.png"/>
						'.$inlinestyles.'
						'.$ga.'
				    	<script src="../../scripts/jquery.js"></script>
					</head>
					<body '.$pagetag.'>
					  <div id="main">
					    <div id="fb-root"></div>
					   
					  <div id="toppanel">
					    <div id="mainlogopanel">
					      <div id="mainimglogo" style="">
					        '.$logocontrol.'
					      </div>
					      
					      '.$subimgpos.'
					    </div>
					    <div id="linkspanel">
					      <ul>
					      '.$toplinks.'
					      </ul>
					    </div>
					    '.$mpagecrumbpath.'
					  </div>
					  <div id="ABdev_menu_toggle" data-name="navbartoggle" data-state="inactive" data-target=".mobile-panel">
						<i class="fa fa-bars"></i>
					  </div>
					  '.$mpagemobilepanel.'
								
						<div id="contentpanel">
						  <div id="contentmiddle">
						    <div id="maincontenthold" '.$maincontentstyle.'>
						        <div class="mainblogsharehold">
						        Share this Post<br>
						        <!--<div class="desktopshareplane">
						        	'.$outs['blogpagesharreshare'].'
						        </div>-->
						        <div class="mobileshareplane">
							        '.$outs['blogpageshareplain'].'
						        </div>
						        <div id="formend" class="blog-formend-style-one">
						        Subscribe to this Category\'s <a href="'.$host_addr.'feeds/rss/'.$outs3['rssname'].'.xml" style="display:inline;"><img src="'.$host_addr.'images/rssimage.png" style="width:20px;height:20px;"></a><br> 
						        Posted on '.$outs['entrydate'].' in the category '.$outs3['catname'].'<br>
						        Views: '.$outs['views'].'<br>
						        LastModified: '.$outs['modifydate'].'<br>
						        </div>
						      </div>
						      '.$descbanner.'
						      '.$topaudio.'
						    <div name="specialheader" class="blog-page-special-header"><h1>'.$outs['title'].'</h1></div><br>  
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
						    '.$blogsidebarposition.'
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
						        Book '.$host_admin_title_name.'.
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
					      <div id="closecontainer" name="closefullcontenthold" data-id="theid" class="altcloseposfour"><img src="'.$host_addr.'images/closefirst.png" title="Close"class="total"/></div>
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
						<script src="../../scripts/mylib.js"></script>
						<script src="../../scripts/js/jquery.jplayer.min.js"></script>
						<script src="../../scripts/muyiwasblog.js"></script>
						<script src="../../scripts/formchecker.js"></script>
						<script language="javascript" type="text/javascript" src="../../scripts/js/tinymce/tinymce.min.js"></script>
						<script language="javascript" type="text/javascript" src="../../scripts/js/tinymce/basic_config.js"></script>
						
						  <script>
						  	$(document).ready(function(){
						  		var subscribercontent=\'<div id="closecontainer" name="closefullcontenthold" data-id="theid" class="altcloseposfour"><img src="'.$host_addr.'images/closefirst.png" title="Close"class="total"/></div><div class="subcribe-display">  <div class="minilogo"><img class="minilogo-logo" src="'.$host_addr.'images/muyiwalogo5.png"/></div>  <h2 class="subscribe-heading">Subscribe</h2>  <p class="subscribe-text">    Hope you are Enjoying your reading?    <br>If you want more simply drop your email address below and we will    add you to our list.<br>    You\\\'ll get instant access to our latest Frankly Speaking Content.<br>  </p>  <form name="franklyspeakingblogsubscriptiontwo" method="POST" onSubmit="" action="'.$host_addr.'snippets/basicsignup.php">    <input type="hidden" name="returnurl" value="'.$outs['pagelink'].'"/><input type="hidden" name="entryvariant" value="franklyspeakingblogsubscriptiontwo"/>    <div id="formend"><input type="text" style="text-align:center;" name="email" placeholder="Enter email here..." value=""class="curved"/></div>    <div id="formend"><input type="button" class="submitbutton two" name="franklyspeakingblogsubscriptiontwo" value="Subscribe"/></div>  </form> <p class="subscribe-text">  Already Subscribed? Close this by clicking/tapping the "<b>X</b>" button at the top right</p></div>\';
						  		loadFullDisplayAfterLoad(60000,subscribercontent);
						  	})
						  </script>
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
				<meta property="og:site_name" content="'.$host_admin_title_name.'\'s Website">
				<meta property="og:image" content="'.$outs['absolutecover'].'">
				<meta name="keywords" content="'.$outs['title'].', '.$host_admin_title_name.', '.$host_admin_title_name.', frontiers consulting, frankly speaking with '.$host_admin_title_name.', frankly speaking, motivational speaker in nigeria, business strategists in the world, reformation packages, Christ Society International Outreach, Project Fix Nigeria, Own Your Own, Nigerian career radio talk show"/>
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
	  $testit!==false?$limit="":$limit=$limit;
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
?>