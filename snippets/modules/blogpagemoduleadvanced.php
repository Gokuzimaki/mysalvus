<?php

	/**
	*	@see blogpagemoduleadvanced.php 	
	*	blogpagemoduleadvanced.php is a rewrite of the blogpagemodule from its 
	*	legacy form to a new state. The purpose, make it better of course.	
	*	@author Okebukola Olagoke
	*	@version 1.0.2
	*	
	*	@todo
	*	This module contains functions that interact and provide an interface with
	*	the homonculus blog system. The functions here cover rss creation and interaction
	*	social media sharing
	*	blogtype retrieval
	*	blogcategory retrieval
	*	blog page content generation
	*	comment management and sorting
	*	
	*	
	*/	

	/**
	*	@see writeRssData()
	*
	*	@todo writeRssData is a function that carries out writes and rewrites per blog
	*	entry in the database to a corresponding rss file. It does this per blogtype 
	*	or category id depending on what is required.
	*
	*	@param int $blogid is the id for an existing blogtype   
	*
	*	@param int $blogcatid is the category id underwhich content has been written to
	*	and a rewrite or write of the rss entry for that category is required
	*	
	*	@return array $row.
	*/

	function writeRssData($blogid,$blogcatid){
		global $host_tpathplain;
		include($host_tpathplain.'globalsmodule.php');
		$row=array();
	
		if($blogid!==""&&$blogid>0){
			$outs=getSingleBlogType($blogid,'blockdeeprun');
			$feedpath="../feeds/rss/".$outs['rssname'].".xml";
			// get all blog post rssentries pertaining to this
			$query="SELECT * FROM rssentries WHERE blogtypeid=$blogid order by id desc";
			// get the rssheader for the current entry
			$query2="SELECT * FROM rssheaders WHERE blogtypeid=$blogid";
		}elseif ($blogcatid!==""&&$blogcatid>0) {
			# code...
			$rdata=array();
			$rdata['single']['doposts']="false";
			$outs=getSingleBlogCategory($blogcatid,"",$rdata);
			$feedpath="../feeds/rss/".$outs['rssname'].".xml";
			$blogmainid=$outs['blogtypeid'];
			$outs=getSingleBlogType($blogmainid,'blockdeeprun');
			// get all blog post rssentries pertaining to this
			$query="SELECT * FROM rssentries WHERE blogcategoryid=$blogcatid order by id desc";
			// get the rssheader for the current entry
			$query2="SELECT * FROM rssheaders WHERE blogcatid=$blogcatid";
		}else{
			return false;
		}

		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
		$numrows=mysql_num_rows($run);
		$run2=mysql_query($query2)or die(mysql_error()." Line ".__LINE__);
		$numrows2=mysql_num_rows($run2);
		
		// variable for holding concatenated rss feed data retrieved from the
		// rssentries table 
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
			// combine all the retrieved output together then write them to the correct
			// rss file
			$content=$header.$feedentries.$footer;
			// echo $content;
			$handle=fopen($feedpath,"w");
			fwrite($handle,$content);
			fclose($handle);
		}

		return $row;
	}



	/**
	*	@see sendSubscriberEmail()
	*
	*	sendSubscriberEmail is a function that sends out emails to subscribers  
	*	of the website's blog
	*
	*	@param int $blogpostid is the id for an existing blogpost   
	*
	*	@param array $data is an array carrying all the required content
	*	for running emails out
	*
	*/
	function sendSubscriberEmail($blogpostid,$data=array()){
		global $host_tpathplain;
		include($host_tpathplain.'globalsmodule.php');
		
		// semailid is the subscriptionemail table id, it represents
		// sent email id,
		$semailid=isset($data['semailid'])?$data['semailid']:"";
		
		// testtype forces tests on only two emails...my emails if set to only true
		$testtype=isset($data['testtype'])?$data['testtype']:"";
		
		// limit can be manipulated in conjunction with testtype to run a do over of
		// emails that were skipped
		// it is commonly used by a cron daemon to resend emails to a subscriber that
		// hasnt received them
		$limit=isset($data['limit'])?$data['limit']:"";

		// stype specifies whether smtp is to be used in sending the email or not
		$stype=isset($data['stype'])?$data['stype']:"smtp";
		
		// msgtype specifies the emailhtmlformat file to be called in for creating the
		// the mail to be sent out.
		$msgfile=isset($data['msgfile'])?$data['msgfile']
		:$host_tpathplain."emailhtmlformats/defaultblogemail.php";
		// stops the function from doing further searches on category based posts
		// in getSingleCategory function being invoked from within it.
		$rdata=array();
		$rdata['single']['doposts']="false";

		$outs=getSingleBlogEntry($blogpostid,"",$rdata);

		$todaynow=date("Y-m-d H:i:s");
		
		$blogtypeid=$outs['blogtypeid'];
		$blogcategoryid=$outs['blogcatid'];
	 	$blogtypename=$outs['blogtypedata']['name'];
		$blogcatname=$outs['blogcatdata']['catname'];
		$date=$outs['date'];
		$nextday=date('Y-m-d H:i:s',  strtotime('+1 day', strtotime($date)));
		//verify that the set date has not passed
		$domail="true";
		$datetime1 = new DateTime("$nextday"); // next day from post date
		$datetime2 = new DateTime("$todaynow"); // current date time
		if($datetime1<$datetime2){
			// if the next date from the date of the post is less than the current date
			$domail="false";
		}
		// query running for blogtype subscriptions
		$query="SELECT * FROM subscriptionlist WHERE blogtypeid=$blogtypeid AND 
				status='active'";
		// query running for category based subscriptions
		$query2="SELECT * FROM subscriptionlist WHERE blogcatid=$blogcategoryid AND 
				status='active'";
		if($semailid!==""){
			$query="SELECT * FROM subscriptionemails WHERE subscriptionid='$semailid' 
					AND contentid=$blogpostid  AND contenttype='blogpost' AND 
					status='active'";

			$query2="SELECT * FROM subscriptionemails WHERE subscriptionid='$semailid' AND 
					contentid=$blogtypeid  AND contenttype='blogtypepost' 
					AND status='active'";

		}else if($testtype=="true"){
			$query="SELECT * FROM subscriptionlist WHERE email='gokuzimaki@gmail.com' OR 
					email='okebukolaolagoke@rocketmail.com' AND blogtypeid=$blogtypeid 
					AND status='active'";

			$query2="SELECT * FROM subscriptionlist WHERE email='gokuzimaki@gmail.com' OR 
					email='okebukolaolagoke@rocketmail.com' AND blogtypeid=$blogtypeid AND 
					status='active'";
			$domail="true";

		}else if($limit!==""){
			if($testtype==""){
				$query="SELECT * FROM subscriptionlist WHERE blogtypeid=$blogtypeid 
						AND status='active' AND id>$limit";
				$query2="SELECT * FROM subscriptionlist WHERE blogcatid=$blogcategoryid 
						AND status='active' AND id>$limit";
			}else if($testtype=="boundary"){
				// boudary info has this format for limit value entry
				// startingid|| limit query addition
				// e.g 56||LIMIT 0,30
				$limdata=explode("||", $limit);
				$limid=$limdata[0];// limit id
				$limit=$limdata[1];// true limit value
				$query="SELECT * FROM subscriptionlist WHERE blogtypeid=$blogtypeid 
						AND status='active' AND id>$limid $limit";
				$query2="SELECT * FROM subscriptionlist WHERE blogcatid=$blogcategoryid 
						AND status='active' AND id>$limid $limit";
			}
		}
		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
		$run2=mysql_query($query2)or die(mysql_error()." Line ".__LINE__);
		$numrows=mysql_num_rows($run);
		$numrows2=mysql_num_rows($run2);
		$coverphoto=$outs['profpicdata']['location'];
		$y=date('Y');
		$outmsg="";
		$mail = new PHPMailer;
		$mail->Mailer='smtp';
		$mail->isSMTP(); // Set mailer to use SMTP
		$mail->Host = $host_smtp;
		$mail->Username = $host_smtp_username;
		$mail->Password = $host_smtp_pwrd;
		$mail->From = ''.$host_email_addr.'';
		$mail->FromName = $host_website_name." ".$outs['blogtypedata']['name'].'';
		// echo $numrows."<br>the numrows";
		
		// get the html message output
		$initvars=array();
		$initvals=array();
		$initvars[0]='host_website_name';
		$initvars[1]='host_default_cover_image';
		$initvars[2]='outs';
		$initvals[0]=$host_website_name;
		$initvals[1]=$host_default_cover_image;
		$initvals[2]=$outs;
		$message=get_include_contents("$msgfile",$initvars,$initvals);

		$mail->WordWrap = 50;
		$mail->isHTML(true);

		$mail->Subject = ''.stripslashes($outs['title']).'';
		$mail->Body = ''.$message.'';
		$mail->AltBody = 'New post from '.$host_website_name.'
		'.stripslashes($outs['title']).'
		Please visit '.$outs['pagelink'].' or '.$host_addr.'unsubscribe.php?t=1&tp='.$blogtypeid.'" to unsubscribe.
		';
		$insertquery="";
		if($numrows>0){
			$count=0;
			//try to break the emails into packets of 300
			while($row=mysql_fetch_assoc($run)){
				if ($semailid!=="") {
					# code...
					// get the subscribers data from the subscriptionlist table
					$subscriptiondata=getSingleSubscriber($row['subscriptionid']);
					$userid=$subscriptiondata['id'];
					$useremail=$subscriptiondata['email'];
				}else{
					$userid=$row['id'];
					$useremail=$row['email'];
				}

				// check to see if current email is in the subscription email database
				// i.e if it has been sent already
				$tq="SELECT * FROM subscriptionemails WHERE subscriptionid='$userid' 
					AND contentid=$blogpostid AND contenttype='blogpost' 
					AND sendstatus='sent'";
				$tr=mysql_query($tq)or die(mysql_error()." LINE ".__LINE__);
				$trnr=mysql_num_rows($tr);
				if($host_email_send===true&&$domail=="true"&&$trnr<1){ 
					$mail->AddAddress(''.$useremail.'');

					if(!$mail->send()) {
						// if the semailid is absent insert the current entry into the 
						// subscriptionemails table
						if($semailid==""){
							$insertquery.="INSERT INTO subscriptionemails 
							(subscriptionid,contentid,contenttype,entrydate,sendstatus)
							VALUES
							('$userid','$blogpostid','blogpost','$todaynow','failed');";
						}else{
							genericSingleUpdate("subscriptionemails","sendstatus","failed","id","$semailid");
						}
						$mail->ClearAllRecipients(); // reset the `To:` list to empty
						$outmsg.='Message could not be sent.'. $mail->ErrorInfo.'<br>';
					}else{
						// if the semailid is absent insert the current entry into the 
						// subscriptionemails table
						if($semailid==""){
							$insertquery.="INSERT INTO subscriptionemails 
							(subscriptionid,contentid,contenttype,entrydate,sendstatus)
							VALUES
							('$userid','$blogpostid','blogpost','$todaynow','sent');";
						}else{
							genericSingleUpdate("subscriptionemails","sendstatus","sent","id","$semailid");
						} 
						$mail->ClearAllRecipients(); // reset the `To:` list to empty
						$outmsg.= "sentmail to $useremail <br>";
					}
						
					
				}
				if($insertquery!==""){
					$insertarr=explode(";",$insertquery);
					for($i=0;$i<count($insertarr);$i++){
						$insertquery=$insertarr[$i];
						if($insertquery!==""){
							$insertrun=mysql_query($insertquery)or die(mysql_error()." LINE ".__LINE__);	
						}
					}
				}	
			}
		}
		$row['message']=$message;
		return $row;
	}

	/**
	*	@see getSingleBlogType() 
	*	
	*	@todo this function gets a single blog type based on id  
	*	from the 'blogtype' database table
	*	
	*	@param int $blogtypeid is the id for an existing blogtype entry   
	*
	*	@param string/array $type is a string/array carrying definitive content.
	*	used in affecting query value when executing single functions
	*
	*	@param array $data is an array carrying extra content.
	*	
	*	@return array $row is a multidimensional array that carries the results
	*	for the data retrieval based on id of the current blogtype id, amongst others
	*	var_dump($row) would give a full list if necessary
	*
	*/

	function getSingleBlogType($blogtypeid,$type="",$data=array()){
		global $host_tpathplain;
		include_once($host_tpathplain.'globalsmodule.php');
		$id=$blogtypeid;
		$row=array();
		

		$query="SELECT * FROM blogtype where id='$blogtypeid'";

		// for overriding the default query
		$queryoverride="";
		if(isset($data['single']['queryoverride'])&&$data['single']['queryoverride']!==""){
			$queryoverride=$data['single']['queryoverride'];
			$query="$queryoverride WHERE id='$id'";
			
		}


		// check to see if there already is a row index carrying the intended
		// for the current transaction 
		if(isset($data['single']['row'])&&$data['single']['row']!==""){
			$qdata['resultdata'][0]=$data['single']['row'];
			$qdata['numrows']=1;
			
		}else{
			$qdata=briefquery($query,__LINE__,"mysqli");
		}

		$numrows=$qdata['numrows'];
		$adminoutput='<tr><td colspan="100%">No entries found</td></tr>';
		// init important variable values pertaining to content in the $data array 
		$datamapout="";// caries the datamap for current operations
		$tdataoutput="";// carries the td dataoutput for the tr data cells 
		$formtruetype="edit_";// carries the name of the formtruetype
		$processroute=""; 
		$editroute=""; 
		$totalscripts="";
		$gc="";	
		$crmd5="";	
		$blockdeeprun="";
		if($type=="blockdeeprun"){
			$blockdeeprun="true";
		}
		if(isset($data['single'])){
			// var_dump($data);

			// create a data array single index entry for the rmd5 hash
			if(isset($data['single']['rmd5'])&&$data['single']['rmd5']!==""){
				$crmd5=$data['single']['rmd5'];	
			}

			if(isset($data['single']['datamap'])&&$data['single']['datamap']!==""){
				$datamap=$data['single']['datamap'];
				$gd_testdata=JSONtoPHP($datamap);
				$gd_dataoutput=$gd_testdata['arrayoutput'];
				// var_dump($gd_dataoutput);

				$processroute=isset($gd_dataoutput['pr'])?$gd_dataoutput['pr']:"";

				$editroute=isset($gd_dataoutput['er'])?$gd_dataoutput['er']:$processroute;
				if($queryoverride!==""){
					// add the session access hash and 
					// the 'overriden index to the datamap'
					$gd_dataoutput['pr']=stripslashes($processroute);
					$gd_dataoutput['er']=stripslashes($editroute);
					$gd_dataoutput['rmd5']=$crmd5;
					$gd_dataoutput['overriden']="true";
					$datamap=json_encode($gd_dataoutput);
					// var_dump($cd);
				}
				$datamapout='data-edata=\''.$datamap.'\'';
			}

			if(isset($data['single']['rowdefaults'])&&$data['single']['rowdefaults']!==""){
				$tdataoutput=$data['single']['rowdefaults'];
			}

			if(isset($data['single']['formtruetype'])&&$data['single']['formtruetype']!==""){
				$formtruetype=$data['single']['formtruetype'];
				// echo $formtruetype;
			}

			if(isset($data['single']['blockdeeprun'])&&$data['single']['blockdeeprun']!==""){
				$blockdeeprun=$data['single']['blockdeeprun'];
				// echo $blockdeeprun;
			}
			
			// get the count for the current entry in a group
			if(isset($data['single']['groupcount'])&&$data['single']['groupcount']!==""){
				$gc=$data['single']['groupcount'];
			}
		}


		$selectionscripts="";
		
		if($numrows>0){
			$adminoutput="";
			for($i=0;$i<$numrows;$i++){
				$row=$qdata['resultdata'][$i];
				$id=$row['id'];
				$name=$row['name'];
				$foldername=$row['foldername'];
				$description=$row['description'];
				$status=$row['status'];
				if($formtruetype!==""){
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=status]").val("'.$status.'");';
				}
				
				// create the table data display
				$tddataoutput=isset($tdataoutput)&&$tdataoutput!==""?$tdataoutput:'
					<td>'.$name.'</td><td>'.$foldername.'</td><td>'.$description.'</td><td>'.$status.'</td>
					<td name="trcontrolpoint">
						<a href="#&id='.$id.'" name="edit" data-type="editgeneraldata" data-divid="'.$id.'" '.$datamapout.'>
							Edit
						</a>
					</td>
				';

				$adminoutput.='
					<tr data-id="'.$id.'">
						'.$tddataoutput.'
					</tr>
					<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'" '.$datamapout.'>
						<td colspan="100%">
							<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
								<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
									
								</div>
							</div>
						</td>
					</tr>
				';
			}
		}
		$selectionscriptsout='<script>$(document).ready(function(){'.$selectionscripts.'});</script>';

		$row['adminoutput']=$adminoutput;

		$row['totalscripts']=$selectionscripts;

		$initvar[0]="editid";
		$initvar[1]="viewtype";
		$initvar[2]="row";
		$initvar[3]="maintype2";
		$initval[0]=$id;
		$initval[1]="edit";
		$initval[2]=$row;
		$initval[3]="editblogtype";
		$row['adminoutputtwo']="";
		if($blockdeeprun==""){
			// $row['adminoutputtwo']=get_include_contents("../forms/blogmetadatas.php",$initvar,$initval);
		}
		
		return $row;
	}

	function getAllBlogTypes($viewer,$limit,$type="",$data=array()){
		global $host_tpathplain;
		include_once($host_tpathplain.'globalsmodule.php');
		
		$row=array();

	 	// this block handles the content of the limit data
	 	// testing and stripping it of unnecessary/unwanted characters
		str_replace("-", "", $limit);
		$testit=strpos($limit,"-");
		$testit?$limit="":$limit=$limit;
		$testittwo=strpos($limit,",");
		// echo $testittwo;
		if($testittwo!==false||$testittwo===true||$testittwo>0){
			$limit=$limit;
		}else{
			if(strtolower($limit)=="all"){
				$limit="";
			}else{
				$limit="LIMIT 0,15";				
			}
		}

		$realoutputdata="";
		$mtype="";
		if($type!==""){
			$mtype=$type;
			if (is_array($type)) {
				# code...
				if(isset($mtype['lastid'])||isset($mtype['nextid'])){
					$callpage="true";
					if(isset($mtype['lastid'])){
						$addq=" AND id>".$mtype['lastid'];
					}
					if(isset($mtype['nextid'])){
						$addq=" AND id<".$mtype['nextid'];
					}
				}
				$type=$mtype[0];
				$typeval=$mtype[1];
				$realoutputdata="$type][$typeval";
			
				
			}else{
				$realoutputdata=$type;
			}
		}

		// run through the data array and obtain only the 'single' index
		// of it
		$singletype="";
		if(isset($data['single']['type'])&&$data['single']['type']!==""){
			$singletype=$data['single']['type'];
		}

		// check to see if there is an entry for the 'type' parameter in the single
		// selection version of the current entry.
		$outputtype="generalpages|blogtype|".$viewer;
		$queryoverride="";
		$queryextra="";
		$ordercontent="order by id desc";
		$qcat=$viewer=="admin"?"WHERE":"AND";

		// appendable subqueries 
		if(isset($data['queryextra'])&&$data['queryextra']!==""&&
			strlen(str_replace(" ", "", $data['queryextra']))>0){
			if($queryextra==""){
				$queryextra.=" $qcat ".$data['queryextra'];
			}else{
				$queryextra.=" AND ".$data['queryextra'];
			}
		}

		// controls order of query data output
		if(isset($data['queryorder'])&&$data['queryorder']!==""){
			$ordercontent=$data['queryorder'];
		}

		// completely overrides the default query 
		if(isset($data['queryoverride'])&&$data['queryoverride']!==""){
			$queryoverride=$data['queryoverride'];
		}



		if($viewer=="admin"){
			$query="SELECT * FROM blogtype $queryextra order by id desc ".$limit."";
			$rowmonitor['chiefquery']="SELECT * FROM blogtype $queryextra order by id desc";
		}elseif($viewer=="viewer"){
			$query="SELECT * FROM blogtype WHERE status='active' $queryextra order by id desc $limit";
			$rowmonitor['chiefquery']="SELECT * FROM blogtype WHERE status='active' $queryextra order by id desc";
		}

		
		// override default query if necessary
		if($queryoverride!==""){
			$query="$queryoverride $ordercontent $limit";
			$rowmonitor['chiefquery']="$queryoverride $ordercontent";
		}
		// echo $viewer;
		// echo $query;
		// unique hash per data transaction call

		$rmd5=md5($rowmonitor['chiefquery'].date("Y-m-d H:i:s"));

		// create a data array single index entry for the rmd5 hash
		if(!isset($data['single']['rmd5'])){
			$data['single']['rmd5']=$rmd5;
		}

		// return the query, only for tests with Ajax json 
		$row['cqtdata']=$query;

		// create the $_SESSION['generalpagesdata'] array value to ensure continuity
		// when paginating content. This is done by checking the sessionuse 
		if((!isset($data['sessionuse'])&&!isset($data['chash']))||
			($data['sessionuse']==""&&$data['chash']=="")){

			// store current output type
			$_SESSION['generalpagesdata']["$rmd5"]['outputtype']=$outputtype;

			// store current data array
			$_SESSION['generalpagesdata']["$rmd5"]['data']=$data;

			// store custom ipp array if available
			$_SESSION['generalpagesdata']["$rmd5"]['ipparr_override']=
			isset($ipparr_override)?$ipparr_override:"";
			
			// store current type entry
			$_SESSION['generalpagesdata']["$rmd5"]['type']=$mtype;
			
			// set the value for the pagination handler file path
			// using the 'snippets' folder as the root. 
			$_SESSION['generalpagesdata']["$rmd5"]['pagpath']="forms/blogmetadatas.php";

			// set the pagintation type variable which helps differentiate what to be
			// paginated in the case of single 'form' file handling serving 
			// several modules
			$_SESSION['generalpagesdata']["$rmd5"]['pagtype']="blogtype";

			// use the 'cq' session index to secure this query
			$_SESSION['cq']["$rmd5"]=$rowmonitor['chiefquery'];

		}else{
			// echo __LINE__;
			// var_dump($data);
			if(isset($data['single']['rmd5'])&&$data['single']['rmd5']!==""){
				$rmd5=$data['single']['rmd5'];
			}
		}

		// prep the datamap element
		$mapelement="";
		if(isset($data['datamap'])&&$data['datamap']!==""){
			// array map element map data for handling custom gd request
			// echo "maptrick<br>";
			$curdatamap=JSONtoPHP($data['datamap']);
			if($curdatamap['error']=="No error"){
				if($queryoverride!==""){
					$cd=$curdatamap['arrayoutput'];
					$cd['rmd5']=$rmd5;
					$cd['overriden']="true";
					$data=json_encode($cd);
				}

				$mapelement='
					<input type="hidden" name="datamap" value=\''.$data['datamap'].'\'/>
				';
					
			}else{
				echo"<br>Map error<br>";
			}
		}

		$qdata=briefquery($query,__LINE__,"mysqli");
		
		$adminoutput='<tr><td colspan="100%">No entries found</td></tr>';
		
		$row['resultdataset']=array();
		$numrows=$qdata['numrows'];
    	$selectiondata='<option value="">--Choose--</option>';
		$formoutput="";
		$formoutputtwo="";
		$totalscripts="";

		$monitorpoint="";
		
		if($numrows>0){

			$adminoutput="";
			
			for($i=0;$i<$numrows;$i++){

				$id=$qdata['resultdata'][$i]['id'];

				$data['single']['row']=$qdata['resultdata'][$i];
				$outs=getSingleBlogType($id,$singletype,$data);

				$row['resultdataset'][$i]=$outs;

				$totalscripts.=$outs['totalscripts'];
				
				$adminoutput.=$outs['adminoutput'];
				$selectiondata.='<option value="'.$outs['id'].'">'.$outs['name'].'</option>';

			}
		}

		$outs=paginatejavascript($rowmonitor['chiefquery']);
		$row['totalscripts']=$totalscripts;
		$row['selectiondata']=$selectiondata;
		$row['chiefquery']=$rowmonitor['chiefquery'];
		$row['num_pages']=$outs['num_pages'];

		$adminheadings='
			<thead><tr><th>Name</th><th>FolderName</th><th>Description</th><th>Status</th><th>View/Edit</th></tr></thead>
		';
		if(isset($data['group']['adminheadings'])&&$data['group']['adminheadings']=""){
			$adminheadings=$data['group']['adminheadings'];
		}

		$top='<table id="resultcontenttable" cellspacing="0">
					'.$adminheadings.'
					<tbody>';
		$bottom='	</tbody>
				</table>';
		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.$rmd5.'"/>
				<input type="hidden" name="outputtype" value="'.$outputtype.'|'.$realoutputdata.'"/>
				'.$mapelement.'
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
		
		$row['paginatetop']=$paginatetop;
		$row['paginatebottom']=$paginatebottom;
		$row['numrows']=$numrows;
		$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
		$row['adminoutputtwo']=$top.$adminoutput.$bottom;

		return $row;
	}



	/**
	*	@see getSingleBlogCategory() 
	*	
	*	@todo this function gets a single blog category based on id  
	*	from the 'blogcategories' database table, obtains other information related to
	*	that category as well including the blog the category belongs to 
	*	
	*	
	*	@param int $blogtypeid is the id for an existing blogtype  
	*
	*	@param string/array $type is a string/array carrying definitive content
	*	used in affecting query value, usually for simple operations
	*
	*	@param array $data is an array carrying extra content.
	*	
	*	@return array $row is a multidimensional array that carries the results
	*	for the data retrieval based on id of the current blogtype id, amongst others
	*	var_dump($row) would give a full list if necessary
	*
	*/

	function getSingleBlogCategory($id,$type="",$data=array()){
	  	global $host_tpathplain;
		include($host_tpathplain.'globalsmodule.php');

		$row=array();
	  
	  	$query="SELECT * FROM blogcategories WHERE id=$id";
		// for overriding the default query
		$queryoverride="";
		if(isset($data['single']['queryoverride'])&&$data['single']['queryoverride']!==""){
			$queryoverride=$data['single']['queryoverride'];
			$query="$queryoverride WHERE id='$id'";
			
		}


		// check to see if there already is a row index carrying the intended
		// for the current transaction 
		if(isset($data['single']['row'])&&$data['single']['row']!==""){
			$qdata['resultdata'][0]=$data['single']['row'];
			$qdata['numrows']=1;
			
		}else{
			$qdata=briefquery($query,__LINE__,"mysqli");
		}

		$numrows=$qdata['numrows'];
		$adminoutput='<tr><td colspan="100%">No entries found</td></tr>';
		
		// init important variable values pertaining to content in the $data array 
		
		$datamapout="";// caries the datamap for current operations
		
		$tdataoutput="";// carries the td dataoutput for the tr data cells 
		
		$formtruetype="edit_";// carries the name of the formtruetype

		$processroute=""; //carries the path to the process file for this function 

		$editroute=""; 
		$totalscripts="";
		$gc="";	
		$crmd5="";	
		$blockdeeprun="";

		//used to specify if queries on subposts should be carried out or not
		$doposts=""; 

		if($type=="blockdeeprun"){
			$blockdeeprun="true";
		}
		// maximum number of blog posts to retrieve per category
		$postlimit=$host_blog_catpost_max;

		if(isset($data['single'])){
			// var_dump($data);

			// create a data array single index entry for the rmd5 hash
			if(isset($data['single']['rmd5'])&&$data['single']['rmd5']!==""){
				$crmd5=$data['single']['rmd5'];	
			}

			if(isset($data['single']['datamap'])&&$data['single']['datamap']!==""){
				$datamap=$data['single']['datamap'];
				$gd_testdata=JSONtoPHP($datamap);
				$gd_dataoutput=$gd_testdata['arrayoutput'];
				// var_dump($gd_dataoutput);

				$processroute=isset($gd_dataoutput['pr'])?$gd_dataoutput['pr']:"";

				$editroute=isset($gd_dataoutput['er'])?$gd_dataoutput['er']:$processroute;
				if($queryoverride!==""){
					// add the session access hash and 
					// the 'overriden index to the datamap'
					$gd_dataoutput['pr']=stripslashes($processroute);
					$gd_dataoutput['er']=stripslashes($editroute);
					$gd_dataoutput['rmd5']=$crmd5;
					$gd_dataoutput['overriden']="true";
					$datamap=json_encode($gd_dataoutput);
					// var_dump($cd);
				}
				$datamapout='data-edata=\''.$datamap.'\'';
			}

			if(isset($data['single']['rowdefaults'])&&$data['single']['rowdefaults']!==""){
				$tdataoutput=$data['single']['rowdefaults'];
			}

			if(isset($data['single']['formtruetype'])&&$data['single']['formtruetype']!==""){
				$formtruetype=$data['single']['formtruetype'];
				// echo $formtruetype;
			}

			if(isset($data['single']['blockdeeprun'])&&$data['single']['blockdeeprun']!==""){
				$blockdeeprun=$data['single']['blockdeeprun'];
				// echo $blockdeeprun;
			}

			// get the count for the current entry in a group
			if(isset($data['single']['groupcount'])&&$data['single']['groupcount']!==""){
				$gc=$data['single']['groupcount'];
			}

			// strictly for the blogcategory function, this is used to manaully override 
			// the expected number of posts to be retrieved for the current category
			if(isset($data['single']['postlimit'])&&$data['single']['postlimit']!==""){
				$postlimit=$data['single']['postlimit'];
			}

			if(isset($data['single']['doposts'])&&$data['single']['doposts']!==""){
				$doposts=$data['single']['doposts'];
			}
		}

		// variable for holding script elements for selection box values and other 
		// related data setup
		$selectionscripts="";
		
		// for holding the total number of blogposts under this category
		$count=0;
		$postcount=0;
		$postcountmain=0;

		if($numrows>0){

			$adminoutput="";

			for($i=0;$i<$numrows;$i++){

				$row=$qdata['resultdata'][$i];

				$id=$row['id'];
				$blogtypeid=$row['blogtypeid'];
				$catname=$row['catname'];
				$subtext=$row['subtext'];
				$status=$row['status'];
				
				if($formtruetype!==""){
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=status]").val("'.$status.'");';
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=categoryid]").val("'.$blogtypeid.'");';
				}

				// get the parent blogtype for the current category
				$outs=getSingleBlogType($blogtypeid,'blockdeeprun');
				// return the blogtype data
				$row['blogtypedata']=$outs;

				// this block enables avoiding a max nested function level error
				// when calling this function in the getsingleblogentry
				if($doposts==""){

					// get the total number of active posts under the current category
					$postquery="SELECT * FROM blogentries WHERE blogcatid=$id AND 
					status='active' order by id desc";
					$postrun=briefquery($postquery,__LINE__,"mysqli",'',array("rr"=>false));
					$postcount=$postrun['numrows'];

					$postcountmain=$postcount;
				
					if($postcount>999){
						// convert the number into short form, 2 decimal place values 
						$postcountmain=numberSizeConvert($postcount);
					}

					$row['postcountmain']=$postcountmain;

					// if the postcount for content under the current category is greater 
					// than 0 then proceed to get some of the posts under it.

					// total number of posts to be retrieved for the current category
					$row['postlimit']=$postlimit;

					// holds the current set of retrieved posts for the current category
					$row['postresults']=array();

					$count=0;
					if($postcount>0){


						// get the result set required, as opposed to pulling all the blogposts
						// attached to the category
						$postrun=briefquery($postquery." LIMIT 0, $postlimit",__LINE__,"mysqli");

						for($ti=0;$ti<$postrun['numrows'];$ti++) {
							# code...
							// ensures that the current limit of posts is the maximum retrievable
							// amount for the post query, which could be much greater.
							if($postlimit>=$ti){

								$postrows=$postrun['resultdata'][$ti];
								$postid=$postrows['id'];
								/*$postdata=getSingleBlogEntry($postid);
								$row['postresults'][$ti]=$postdata;
								$introparagraph=stripslashes($postdata['introparagraph']);
								$headerdescription = convert_html_to_text($introparagraph);
								$headerdescription=html2txt($headerdescription);
								$monitorlength=strlen($headerdescription);
								$headerminidescription=$headerdescription;*/
								$count++;								
							}
						}
					}

					// total number of retrieved posts under the current category
					$row['posttotal']=$count;
				}

				// get the cover image for the category if any is available
				$mediaquery="SELECT * FROM media WHERE ownerid=$id AND ownertype='blogcategory' 
							AND maintype='original'";
				$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line ".__LINE__);
				$mediarow=mysql_fetch_assoc($mediarun);
				// echo $mediaquery;

				if($mediarow['id']>0){
					$row['profpicdata']['location']=$host_addr.$mediarow['location'];
					$row['profpicdata']['medsize']=$host_addr.$mediarow['medsize'];
					$row['profpicdata']['thumbnail']=$host_addr.$mediarow['thumbnail'];
					$row['profpicdata']['id']=$mediarow['id'];

					$coverphoto='<a href="'.$host_addr.''.$mediarow['location'].'" 
									data-lightbox="cat'.$id.'" 
									data-src="'.$host_addr.''.$mediarow['location'].'">
									<img src="'.$host_addr.''.$mediarow['medsize'].'" 
									title="'.$catname.'"/>
								</a>';
				}else{
					$row['profpicdata']['location']="";

					$row['profpicdata']['medsize']="";

					$row['profpicdata']['thumbnaiil']="";

					$row['profpicdata']['id']=0;

					$coverphoto='<i class="fa fa-file-image-o td-pag-fa"></i>';
				}

				// create the table data display
				$tddataoutput=isset($tdataoutput)&&$tdataoutput!==""?$tdataoutput:'
		    		<td class="tdimg">'.$coverphoto.'</td><td>'.$outs['name'].'</td><td>'.$catname.'</td>
		    		<td class="force-ellipses" 
		    			title="'.$subtext.'"
		    		>'.$subtext.'</td>
		    		<td>'.$postcount.'</td><td>'.$status.'</td>
					
					<td name="trcontrolpoint">
						<a href="#&id='.$id.'" name="edit" data-type="editgeneraldata" data-divid="'.$id.'" '.$datamapout.'>
							Edit
						</a>
					</td>
				';
				
				$adminoutput.='
					<tr data-id="'.$id.'">
						'.$tddataoutput.'
					</tr>
					<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'" '.$datamapout.'>
						<td colspan="100%">
							<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
								<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
									
								</div>
							</div>
						</td>
					</tr>
				';
			}
		}
		$selectionscriptsout='<script>$(document).ready(function(){'.$selectionscripts.'});</script>';

	  	$row['totalscripts']=$selectionscripts;
		$row['adminoutput']=$adminoutput;
		$initvar[0]="editid";
		$initvar[1]="viewtype";
		$initvar[2]="row";
		$initvar[3]="maintype2";
		$initval[0]=$id;
		$initval[1]="edit";
		$initval[2]=$row;
		$initval[3]="editblogcategory";
		$row['adminoutputtwo']="";

		if($blockdeeprun==""){
			// $row['adminoutputtwo']=get_include_contents("../forms/blogmetadatas.php",$initvar,$initval);
		}
		

		return $row;
	}

	function getAllBlogCategories($viewer,$limit,$type="",$data=array()){
		global $host_tpathplain;
		include($host_tpathplain.'globalsmodule.php');
		
		$row=array();

		// controls the blogtypeid variable value, default value is 1 which is the
		// first blogtype entry created or the default
		$blogtypeid=1;
		if(isset($data['blogtypeid'])&&$data['blogtypeid']!==""){
			$blogtypeid=$data['blogtypeid'];
		}else{
			$data['blogtypeid']=1;
		}

		
	 	// this block handles the content of the limit data
	 	// testing and stripping it of unnecessary/unwanted characters
		str_replace("-", "", $limit);

		$testittwo=strpos($limit,",");
		// echo $testittwo;
		if($testittwo!==false||$testittwo===true||$testittwo>0){
			$limit=$limit;
		}else{
			if(strtolower($limit)=="all"){
				$limit="";
			}else{
				$limit="LIMIT 0,15";				
			}
		}

		$realoutputdata="";
		$mtype="";
		if($type!==""){
			$mtype=$type;
			if (is_array($type)) {
				# code...
				if(isset($mtype['lastid'])||isset($mtype['nextid'])){
					$callpage="true";
					if(isset($mtype['lastid'])){
						$addq=" AND id>".$mtype['lastid'];
					}
					if(isset($mtype['nextid'])){
						$addq=" AND id<".$mtype['nextid'];
					}
				}
				$type=$mtype[0];
				$typeval=$mtype[1];
				$realoutputdata="$type][$typeval";
			
				
			}else{
				$realoutputdata=$type;
			}
		}

		// run through the data array and obtain only the 'single' index
		// of it
		$singletype="";
		if(isset($data['single']['type'])&&$data['single']['type']!==""){
			$singletype=$data['single']['type'];
		}

		// check to see if there is an entry for the 'type' parameter in the single
		// selection version of the current entry.
		$outputtype="generalpages|blogcategory|".$viewer;
		$queryoverride="";
		$queryextra="";
		$ordercontent="order by id desc";
		$qcat=$viewer=="admin"?"WHERE":"AND";

		$queryextra=" $qcat blogtypeid='$blogtypeid'";
		
		// appendable subqueries 
		if(isset($data['queryextra'])&&$data['queryextra']!==""){
			if($queryextra==""){
				$queryextra.=" $qcat ".$data['queryextra'];
			}else{
				$queryextra.=" AND ".$data['queryextra'];
			}
		}

		// controls order of query data output
		if(isset($data['queryorder'])&&$data['queryorder']!==""){
			$ordercontent=$data['queryorder'];
		}

		// completely overrides the default query 
		if(isset($data['queryoverride'])&&$data['queryoverride']!==""){
			$queryoverride=$data['queryoverride'];
		}

		// query generation point
	  	if($viewer=="admin"){
		  	$query="SELECT * FROM blogcategories $queryextra $ordercontent $limit";
		    $rowmonitor['chiefquery']="SELECT * FROM blogcategories $queryextra 
		    		$ordercontent";
	  	}else if($viewer=="viewer"){
		 	$query="SELECT * FROM blogcategories WHERE status='active' $queryextra  
		 			$ordercontent $limit";		
		  	$rowmonitor['chiefquery']="SELECT * FROM blogcategories WHERE status='active'
		  			$queryextra $ordercontent";
	  	}
	  	// override default query if necessary
		if($queryoverride!==""){
			$query="$queryoverride $ordercontent $limit";
			$rowmonitor['chiefquery']="$queryoverride $ordercontent";
		}

		// echo $viewer;
		// echo $query;
		
		// unique hash per data transaction call
		$rmd5=md5($rowmonitor['chiefquery'].date("Y-m-d H:i:s"));

		// create a data array single index entry for the rmd5 hash
		if(!isset($data['single']['rmd5'])){
			$data['single']['rmd5']=$rmd5;
		}

		// return the query, only for tests with Ajax json 
		$row['cqtdata']=$query;

		// create the $_SESSION['generalpagesdata'] array value to ensure continuity
		// when paginating content. This is done by checking the sessionuse 
		if((!isset($data['sessionuse'])&&!isset($data['chash']))||
			($data['sessionuse']==""&&$data['chash']=="")){

			// store current output type
			$_SESSION['generalpagesdata']["$rmd5"]['outputtype']=$outputtype;

			// store current data array
			$_SESSION['generalpagesdata']["$rmd5"]['data']=$data;

			// store custom ipp array if available
			$_SESSION['generalpagesdata']["$rmd5"]['ipparr_override']=
			isset($ipparr_override)?$ipparr_override:"";
			
			// store current type entry
			$_SESSION['generalpagesdata']["$rmd5"]['type']=$mtype;
			
			// set the value for the pagination handler file path
			// using the 'snippets' folder as the root. 
			$_SESSION['generalpagesdata']["$rmd5"]['pagpath']="forms/blogmetadatas.php";

			// set the pagintation type variable which helps differentiate what to be
			// paginated in the case of single 'form' file handling serving 
			// several modules
			$_SESSION['generalpagesdata']["$rmd5"]['pagtype']="blogcategory";

			// use the 'cq' session index to secure this query
			$_SESSION['cq']["$rmd5"]=$rowmonitor['chiefquery'];

			/*This section is for indexes that are specific to the current function*/

			// store current blogtypeid used for the query
			$_SESSION['generalpagesdata']["$rmd5"]['blogtypeid']=$blogtypeid;


		}else{
			// echo __LINE__;
			// var_dump($data);
			if(isset($data['single']['rmd5'])&&$data['single']['rmd5']!==""){
				$rmd5=$data['single']['rmd5'];
			}
		}

		// prep the datamap element
		$mapelement="";
		if(isset($data['datamap'])&&$data['datamap']!==""){
			// array map element map data for handling custom gd request
			// echo "maptrick<br>";
			$curdatamap=JSONtoPHP($data['datamap']);
			if($curdatamap['error']=="No error"){
				if($queryoverride!==""){
					$cd=$curdatamap['arrayoutput'];
					$cd['rmd5']=$rmd5;
					$cd['overriden']="true";
					$data=json_encode($cd);
				}

				$mapelement='
					<input type="hidden" name="datamap" value=\''.$data['datamap'].'\'/>
				';
					
			}else{
				echo"<br>Map error<br>";
			}
		}

		$qdata=briefquery($query,__LINE__,"mysqli");
		
		$adminoutput='<tr><td colspan="100%">No entries found</td></tr>';
		
		$row['resultdataset']=array();
		$numrows=$qdata['numrows'];
    	$selectiondata='<option value="">--Choose--</option>';
		$formoutput="";
		$formoutputtwo="";
		$totalscripts="";

		$monitorpoint="";

		if($numrows>0){
		  	
		  	$adminoutput="";

			for($i=0;$i<$numrows;$i++){

				$id=$qdata['resultdata'][$i]['id'];

				$data['single']['row']=$qdata['resultdata'][$i];

		      	$outs=getSingleBlogCategory($id,$singletype,$data);

				$row['resultdataset'][$i]=$outs;

		      	$adminoutput.=$outs['adminoutput'];

		    	$selectiondata.='<option value="'.$outs['id'].'">'.$outs['catname'].'</option>';

				$totalscripts.=$outs['totalscripts'];


		    }

		}

	  	$outs=paginatejavascript($rowmonitor['chiefquery']);
	  	$row['totalscripts']=$totalscripts;
		$row['selectiondata']=$selectiondata;
		$row['chiefquery']=$rowmonitor['chiefquery'];
		$row['num_pages']=$outs['num_pages'];

		$adminheadings='
			<thead><tr><th>CoveIimage</th><th>Blogtype</th><th>Category Name</th>
  			<th>Description</th><th>Posts</th><th>Status</th><th>View/Edit</th>
  			</tr></thead>
		';
		if(isset($data['group']['adminheadings'])&&$data['group']['adminheadings']=""){
			$adminheadings=$data['group']['adminheadings'];
		}

		$top='<table id="resultcontenttable" cellspacing="0">
					'.$adminheadings.'
					<tbody>';
		$bottom='	</tbody>
				</table>';

		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.$rmd5.'"/>
				<input type="hidden" name="outputtype" value="'.$outputtype.'|'.$realoutputdata.'"/>
				'.$mapelement.'
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
		
		$row['paginatetop']=$paginatetop;
		$row['paginatebottom']=$paginatebottom;
		$row['numrows']=$numrows;
		$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
		$row['adminoutputtwo']=$top.$adminoutput.$bottom;
	  	
		$row['chiefquery']=$rowmonitor['chiefquery'];

	  	return $row;
	}



	/**
	*	@see getSingleBlogEntry() 
	*	
	*	@todo this function gets a single blog category based on id  
	*	from the 'blogcategories' database table, obtains other information related to
	*	that category as well including the blog the category belongs to 
	*	
	*	
	*	@param int $blogtypeid is the id for an existing blogtype  
	*
	*	@param string/array $type is a string/array carrying definitive content
	*	used in affecting query value, usually for simple operations
	*
	*	@param array $data is an array carrying extra content.
	*	
	*	@return array $row is a multidimensional array that carries the results
	*	for the data retrieval based on id of the current blogtype id, amongst others
	*	var_dump($row) would give a full list if necessary
	*
	*/

	function getSingleBlogEntry($id,$type="",$data=array()){
	  	global $host_tpathplain;
		include($host_tpathplain.'globalsmodule.php');
		$row=array();
	  
	  	$query="SELECT * FROM blogentries WHERE id=$id";
		// for overriding the default query
		$queryoverride="";
		if(isset($data['single']['queryoverride'])&&$data['single']['queryoverride']!==""){
			$queryoverride=$data['single']['queryoverride'];
			$query="$queryoverride WHERE id='$id'";
			
		}


		// check to see if there already is a row index carrying the intended
		// for the current transaction 
		if(isset($data['single']['row'])&&$data['single']['row']!==""){
			$qdata['resultdata'][0]= $data['single']['row'];
			$qdata['numrows']=1;
			
		}else{
			// echo $query;
			$qdata=briefquery($query,__LINE__,"mysqli");
		}

		$numrows=$qdata['numrows'];
		$adminoutput='<tr><td colspan="100%">No entries found</td></tr>';
		
		// init important variable values pertaining to content in the $data array 
		
		$datamapout="";// caries the datamap for current operations
		
		$tdataoutput="";// carries the td dataoutput for the tr data cells 
		
		$formtruetype="edit_";// carries the name of the edit form

		$processroute=""; //carries the path to the process file for this function 
		$viewer="admin";
		$editroute=""; 
		$totalscripts="";
		$gc="";	
		$crmd5="";	
		$blockdeeprun="";
		if($type=="blockdeeprun"){
			$blockdeeprun="true";
		}
		// maximum number of blog posts to retrieve per category
		$postlimit=$host_blog_catpost_max;

		if(isset($data['single'])){
			// var_dump($data);

			// create a data array single index entry for the rmd5 hash
			if(isset($data['single']['rmd5'])&&$data['single']['rmd5']!==""){
				$crmd5=$data['single']['rmd5'];	
			}

			if(isset($data['single']['datamap'])&&$data['single']['datamap']!==""){
				$datamap=$data['single']['datamap'];
				$gd_testdata=JSONtoPHP($datamap);
				$gd_dataoutput=$gd_testdata['arrayoutput'];
				// var_dump($gd_dataoutput);

				$processroute=isset($gd_dataoutput['pr'])?$gd_dataoutput['pr']:"";

				$editroute=isset($gd_dataoutput['er'])?$gd_dataoutput['er']:$processroute;
				if($queryoverride!==""){
					// add the session access hash and 
					// the 'overriden index to the datamap'
					$gd_dataoutput['pr']=stripslashes($processroute);
					$gd_dataoutput['er']=stripslashes($editroute);
					$gd_dataoutput['rmd5']=$crmd5;
					$gd_dataoutput['overriden']="true";
					$datamap=json_encode($gd_dataoutput);
					// var_dump($cd);
				}
				$datamapout='data-edata=\''.$datamap.'\'';
			}

			if(isset($data['single']['rowdefaults'])&&$data['single']['rowdefaults']!==""){
				$tdataoutput=$data['single']['rowdefaults'];
			}

			if(isset($data['single']['formtruetype'])&&$data['single']['formtruetype']!==""){
				$formtruetype=$data['single']['formtruetype'];
				// echo $formtruetype;
			}

			if(isset($data['single']['blockdeeprun'])&&$data['single']['blockdeeprun']!==""){
				$blockdeeprun=$data['single']['blockdeeprun'];
				// echo $blockdeeprun;
			}

			// get the count for the current entry in a group
			if(isset($data['single']['groupcount'])&&$data['single']['groupcount']!==""){
				$gc=$data['single']['groupcount'];
			}

			// strictly for the getsingleblogentry function
			// get the count for the current entry in a group
			if(isset($data['single']['viewer'])&&$data['single']['viewer']!==""){
				$viewer=$data['single']['viewer'];
			}
		}

		// variable for holding script elements for selection box values and other 
		// related data setup
		$selectionscripts="";
		
		// for holding the total number of blogposts under this category
		$count=0;

		if($numrows>0){

			$adminoutput="";

			for($i=0;$i<$numrows;$i++){

				$row=$qdata['resultdata'][$i];

				/*colname list
					id
					blogtypeid
					blogcatid
					blogentrytype
					betype
					becode
					title
					introparagraph
					blogpost
					entrydate
					modifydate
					feeddate
					views
					coverphoto
					coverphotoset
					pagename
					commentsonoff
					featurepost
					scheduledpost
					postperiod
					date
					tags
					seometakywords
					seometadescription
					pwrdd
					pwrd
					posterid
					options
					status
				*/
				$id=$row['id'];
				
				$status=$row['status'];


				// get the blogtype info
				$blogtypeid=$row['blogtypeid'];
				$blogtypedata=getSingleBlogType($blogtypeid,'blockdeeprun');
				$row['blogtypedata']=$blogtypedata;
				// get the blogcat info
				$cdata['single']['doposts']="false";
				$blogcatid=$row['blogcatid'];
				$blogcatdata=getSingleBlogCategory($blogcatid,"",$cdata);
				
				$row['blogcatdata']=$blogcatdata;
				if($formtruetype!==""){
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=status]").val("'.$status.'");';
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=categoryid]").val("'.$blogtypeid.'");';
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=commentsonoff]").val("'.$row['commentsonoff'].'");';
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=commenttype]").val("'.$row['commenttype'].'");';
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=featured]").val("'.$row['featuredpost'].'");';
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=pwrdd]").val("'.$row['pwrdd'].'");';
				}
				// return the blogtype data
				$row['blogtypedata']=$blogtypedata;

				// get the poster data
				$posterid=$row['posterid'];
				if($posterid>0){
					$pdata=getSingleAdminUser($posterid);
					$row['poster']['fullname']=$pdata['fullname'];
					$row['poster']['bio']=$pdata['bio'];
				}else{
					$row['poster']['fullname']=$host_admin_title_name;
				}
				// get the cover image for the blog post if any is available
				$mediaquery="SELECT * FROM media WHERE ownerid=$id AND ownertype='blogentry' 
							AND maintype='coverphoto'";
				$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line ".__LINE__);
				$mediarow=mysql_fetch_assoc($mediarun);
				// echo $mediaquery;

				if($mediarow['id']>0){
					$row['profpicdata']['location']=$host_addr.$mediarow['location'];
					$row['profpicdata']['medsize']=$host_addr.$mediarow['medsize'];
					$row['profpicdata']['thumbnail']=$host_addr.$mediarow['thumbnail'];
					$row['profpicdata']['id']=$mediarow['id'];

					$coverphoto='<a href="'.$host_addr.''.$mediarow['location'].'" 
									data-lightbox="cat'.$id.'" 
									data-src="'.$host_addr.''.$mediarow['location'].'">
									<img src="'.$host_addr.''.$mediarow['medsize'].'" 
									/>
								</a>';
				}else{
					$row['profpicdata']['location']="";

					$row['profpicdata']['medsize']="";

					$row['profpicdata']['thumbnail']="";

					$row['profpicdata']['id']=0;

					$coverphoto='<i class="fa fa-file-image-o td-pag-fa"></i>';
				}

				

				// blogentrytype represents the nature of the current blog entry
				$blogentrytype=$row['blogentrytype'];
				
				// beticon represents the admin blogview table icon used to describe
				// the nature of the blog post
				$beticon="";
				if($blogentrytype=="normal"){
					$beticon='<i class="fa fa-file-text-o td-bticon" title="'.$blogentrytype.' post"></i>';					
				}else if($blogentrytype=="gallery"||$blogentrytype=="banner"){
					$beticon='<i class="fa fa-file-image-o td-bticon" title="'.$blogentrytype.' post"></i>';					

				}else if($blogentrytype=="audio"){
					$beticon='<i class="fa fa-volume-up td-bticon" title="'.$blogentrytype.' post"></i>';					

				}else if($blogentrytype=="video"){
					$beticon='<i class="fa fa-television td-bticon" title="'.$blogentrytype.' post"></i>';					

				}else if($blogentrytype=="poll"){
					$beticon='<i class="fa fa-reorder td-bticon" title="'.$blogentrytype.' post"></i>';					

				}

				// the blog entry file type, used for audio and video entries
				$betype=isset($row['betype'])?$row['betype']:""; 
				// the embedded code of the audio or video entries
				$becode=isset($row['becode'])?$row['becode']:"";  

				// for retrieving the prefered meta key words for a post
				$seometakeywords=isset($row['seometakeywords'])?$row['seometakeywords']:""; 
				// for retrieving the prefered meta key words for a post
				$seometadescription=isset($row['seometadescription'])?$row['seometadescription']:""; 
				
				// for retrieving the prefered meta key words for a post
				$scheduledpost=isset($row['scheduledpost'])?$row['scheduledpost']:""; 
				// for retrieving the prefered meta key words for a post
				$postperiod=isset($row['postperiod'])?$row['postperiod']:""; 
				$spacedpostperiod=explode(" ", $postperiod);
				
				$title=$row['title'];
				
				$introparagraph=$row['introparagraph'];
				
				// the meta description content for the blogpost.

				$hd=$seometadescription!==""?$seometadescription:
					($introparagraph!==""?$introparagraph:$title);
				// convert the $hd variable content to text only
				// by stripping any markup from them
				$headerdescription = convert_html_to_text($hd);
			    $headerdescription=html2txt($headerdescription);

			    $headerminidescription=strlen($headerdescription)>160?
			    					substr($headerdescription,0,157)."...":
			    					$headerdescription;

			    $row['plaindescription']=$headerminidescription;
				
				$commentsonoff=$row['commentsonoff'];

			    $introout=str_replace("../", $host_addr, $introparagraph);
				$row['introout']=$introout;

			    $blogpost=$row['blogpost'];
				// make the blogpost content embedded media filepaths to have
				// absolute paths instead
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
				$row['maindayout']=$maindayout;
				$entrydateout=$maindayout;
				
				$mainday=date('d', strtotime($entrydatem));
				// three letter month name
				$mainmonth=date('M', strtotime($entrydatem)); 
				// full month name
				$mainmonth2=date('F', strtotime($entrydatem)); 

				$mainyear=date('Y', strtotime($entrydatem));
				$row['day']=$mainday;
				$row['month']=$mainmonth;
				$row['month2']=$mainmonth2;
				$row['year']=$mainyear;
				$schemadayout=date('c', strtotime($entrydatem));
				$row['schemadayout']=$schemadayout;

				// for holding the video form section for editting
				$editvideocontentout=""; 
				// for holding the audio form section for editting
				$editaudiocontentout=""; 

				$modifydate=$row['modifydate'];
				if($modifydate==""){
					$modifydate="never";
				}
				$row['modifydate']=$modifydate;

				$views=$row['views'];

				$pagename=$row['pagename'];
				$pagelink=''.$host_addr.'blog/'.$blogtypedata['foldername'].'/'.$pagename.'.php';
				$rellink='./blog/'.$blogtypedata['foldername'].'/'.$pagename.'.php';
				$row['pagelink']=$pagelink;
				$row['rellink']=$rellink;
				$row['pagedirectory']=''.$host_addr.'blog/'.$blogtypedata['foldername'].'/';
				$row['reldirectory']='./blog/'.$blogtypedata['foldername'].'/';
				$link='<a href="'.$pagelink.'" 
				target="_blank" 
				title="click to view this blog post">'.$title.'</a>';

				// scheduled post section
				$schedulescriptout='';
				$scheduleedit="";
				if($postperiod!=="0000-00-00 00:00:00" &&
					($scheduledpost=="yes"||$scheduledpost=="on")&&
					$row['status']=="schedule"){
					$datetime1 = new DateTime("$postperiod"); // specified scheduled time
					$datetime2 = new DateTime(); // current time 
					if($datetime2<$datetime1){
					  	// the current time is less than the specified time for 
					  	// activating the post
					  	$postdateparts=explode(" ", $postperiod);
						$selectionscripts.='$("form[name='.$formtruetype.'] select[name=schedulestatus]").val("'.$scheduledpost.'")';
					}
					
				}

				// get gallery post data output
				$row['gallerydata']=array();
				$gallerytotal=0;
				if($blogentrytype=="gallery"){
					$mediaquery="SELECT * FROM media WHERE ownerid=$id AND 
								ownertype='blogentry' AND maintype='gallerypic' 
								AND status='active'";
					$mqdata=briefquery($mediaquery,__LINE__,"mysqli");
					$mrows=$mqdata['numrows'];
					$gallerytotal=$mrows;
					if($mrows>0){
						for($mq=0;$mq<$mrows;$mq++){
							$mqrows=$mqdata['resultdata'][$mq];
							$maintype=$mqrows['maintype'];
							$fid=$mqrows['id'];
							$caption=$mqrows['title'];
							$details=$mqrows['details'];
							$location=$host_addr.$mqrows['location'];
							$medsize=$host_addr.$mqrows['medsize'];
							$thumbnail=$host_addr.$mqrows['thumbnail'];
							$row['gallerydata'][]=array("location"=>"$location",
														"medsize"=>"$medsize",
														"thumbnail"=>"$thumbnail",
														"caption"=>"$caption",
														"details"=>"$details",
														"id"=>"$fid"
													);
							if($viewer=="viewer"){
								unset($row['gallerydata'][$mq]['id']);
							}
							// in case there is no cover photo for this post
							// use the last entry as the default
							if($mq==0&&isset($row['profpicdata']['id'])
								&&$row['profpicdata']['id']==0){
								$row['profpicdata']['location']=$location;
								$row['profpicdata']['medsize']=$medsize;
								$row['profpicdata']['thumbnail']=$thumbnail;
								$row['profpicdata']['id']=0;
								$coverphoto='<a href="'.$location.'" 
									data-lightbox="cat'.$id.'" 
									data-src="'.$location.'">
									<img src="'.$medsize.'" 
									title="'.$title.'"/>
								</a>';
							}
						}
					}
				}
				$row['gallerydata']['total']=$gallerytotal;

				// for banner posts
				$row['bannerdata']=array();
				$bannertotal=0;
				if($blogentrytype=="banner"){
					
					// check for bannerimage entry
					$mediaquery="SELECT * FROM media WHERE ownerid=$id AND 
									ownertype='blogentry' AND maintype='bannerpic' 
									AND status='active'";
					$mqdata=briefquery($mediaquery,__LINE__,"mysqli");
					$mrows=$mqdata['numrows'];
					$bannertotal=$mrows;
					if($mrows>0){

						$mqrows=$mqdata['resultdata'][0];
						$maintype=$mqrows['maintype'];
						$fid=$mqrows['id'];
						$caption=$mqrows['title'];
						$details=$mqrows['details'];
						$location=$host_addr.$mqrows['location'];
						$medsize=$host_addr.$mqrows['medsize'];
						$thumbnail=$host_addr.$mqrows['thumbnail'];
						$row['bannerdata'][]=array("location"=>"$location",
													"medsize"=>"$medsize",
													"thumbnail"=>"$thumbnail",
													"caption"=>"$caption",
													"details"=>"$details",
													"id"=>"$fid"
												);
						// in case there is no cover photo for this post
						// use the last entry as the default
						if(isset($row['profpicdata']['id'])
							&&$row['profpicdata']['id']==0){
							$row['profpicdata']['location']=$location;
							$row['profpicdata']['medsize']=$medsize;
							$row['profpicdata']['thumbnail']=$thumbnail;
							$row['profpicdata']['id']=0;
							$coverphoto='<a href="'.$location.'" 
								data-lightbox="cat'.$id.'" 
								data-src="'.$location.'">
								<img src="'.$medsize.'" 
								title="'.$title.'"/>
							</a>';
						}
					}
					if($viewer=="viewer"){
						unset($row['bannerdata'][0]['id']);
					}
				}
				$row['bannerdata']['total']=$bannertotal;

				// pull video data
				$row['viddata']=array();
				$vidtotal=0;
				if($blogentrytype=="video"){ 

					$mediaquery="SELECT * FROM media WHERE ownerid=$id AND 
								ownertype='blogentry' AND maintype='blogentryvideo' 
								AND status='active'";
					$mqdata=briefquery($mediaquery,__LINE__,"mysqli");
					$mrows=$mqdata['numrows'];
					$vidtotal=$mrows;
					if($mrows>0){
						for($mq=0;$mq<$mrows;$mq++){
							$t=$mq+1;
							$mqrows=$mqdata['resultdata'][$mq];
							$caption=$mqrows['title'];
							$jsondata=$mqrows['details'];
							if(str_replace(" ", "",$jsondata)!==""){

								$videodata=JSONtoPHP($jsondata);
								$videodata=$videodata['arrayoutput'];
								// var_dump($videodata);
								$fid=$mqrows['id'];
								
								// the displaytype for the video  data
								// its either 'local' or 'embed'
								$disptype=$videodata['videotype'];
								$videowebm=$videodata['videowebm']['location'];
								$video3gp=$videodata['video3gp']['location'];
								$videoflv=$videodata['videoflv']['location'];
								$videomp4=$videodata['videomp4']['location'];
								$videoembed=$videodata['videoembed'];

								// create absolute url values for entries that have
								// content
								$videowebm=$videowebm!==""?$host_addr.$videowebm:$videowebm;
								$video3gp=$video3gp!==""?$host_addr.$video3gp:$video3gp;
								$videoflv=$videoflv!==""?$host_addr.$videoflv:$videoflv;

								
								// get the data output
								$selectionscripts.='
									$("form[name='.$formtruetype.'] select[name=videotype]").val("'.$disptype.'");';
								$row['viddata'][$mq]=array("disptype"=>"$disptype",
														"videowebm"=>"$videowebm",
														"video3gp"=>"$video3gp",
														"videoflv"=>"$videoflv",
														"videomp4"=>"$videomp4",
														"videoembed"=>"$videoembed",
														"caption"=>"$caption",
														"originalmap"=>"$jsondata",
														"id"=>"$fid"
													);
							}else{
								$row['viddata'][$mq]=array("disptype"=>"",
														"videowebm"=>"",
														"videoflv"=>"",
														"video3gp"=>"",
														"videomp4"=>"",
														"videoembed"=>"",
														"caption"=>"",
														"originalmap"=>"",
														"id"=>"0"
													);
							}
							if($viewer=="viewer"){
								unset($row['viddata'][$mq]['originalmap']);
								unset($row['viddata'][$mq]['id']);
							}
						}
					}
					
				}
				$row['viddata']['total']=$vidtotal;


				$row['audiodata']=array();
				$audiototal=0;
				if($blogentrytype=="audio"){ 

					$mediaquery="SELECT * FROM media WHERE ownerid=$id AND 
									ownertype='blogentry' AND maintype='blogentryaudio' AND status='active'";
					$mqdata=briefquery($mediaquery,__LINE__,"mysqli");
					$mrows=$mqdata['numrows'];
					$audiototal=$mrows;
					if($mrows>0){
						for($mq=0;$mq<$mrows;$mq++){
							$t=$mq+1;
							$mqrows=$mqdata['resultdata'][$mq];
							$fid=$mqrows['id'];
							$maintype=$mqrows['maintype'];
							$mtype=$mqrows['mediatype'];
							$caption=$mqrows['title'];
							$details=$mqrows['details'];
							$location=$host_addr.$mqrows['location'];					
							$selectionscripts.='$("form[name='.$formtruetype.'] select[name=audiotype]").val("'.$mtype.'");';
							$row['audiodata'][$mq]=array("location"=>"$location",
														"audiocaption"=>"$caption",
														"audioembed"=>"$details",
														"disptype"=>"$mtype",
														"id"=>"$fid"
													);
							if($viewer=="viewer"){
								unset($row['audiodata'][$mq]['id']);
							}
						}
					}
				}
				$row['audiodata']['total']=$audiototal;


				$row['polldata']=array();
				
				$row['questionnairedata']=array();

				// pull the comments data if available in two fold parts, one for the
				// administrator to view, the other viewable by site visitors
				$cdata=array();
				$cdata['blogpostid']=$id; 
				$comdataone=getAllComments('admin','all',"",$cdata);
				// proceed to create the set of comments in traversable data table

				// comment datatable complete holder
				$commdt="";
				if($comdataone['numrows']>0&&$viewer=="admin"){
					$commdt='
						<table data-dTable="true" 
						class="table table-bordered table-striped">
		                    <thead>
		                      <tr>
		                        <th>Fullname</th>
		                        <th>Email</th>
		                        <th>Website</th>
		                        <th>Comment</th>
		                        <!--<th>Reply</th>-->
		                        <th>CommentStatus</th>
		                        <th>DateTime</th>
		                        <th>Edit</th>
		                      </tr>
		                    </thead>
		                    <tbody>
		                ';
					for($cc=0;$cc<$comdataone['numrows'];$cc++){
						$comdata=$comdataone['resultdataset'][$cc];
						$comsmap='{"cid":'.$comdata['id'].',
									"comment":"'.$comdata['id'].'"
									}';
						$commreply='<a href="##comment_reply"
		                        		data-action="replycomment"
		                        		data-smap=\''.$comsmap.'\'
		                        	>
		                        	Reply
		                        	</a>';
						$commdt.='
							<tr>
		                        <td>'.$comdata['fullname'].'</td>
		                        <td>'.$comdata['email'].'</td>
		                        <td>'.$comdata['website'].'</td>
		                        <td class="td-ellipsis">'.$comdata['comment'].'</td>
		                        <!--<td class="td-ellipsis">
		                        	'.$commreply.'
		                        </td>-->
		                        <td name="commentstatus'.$comdata['id'].'">'.$comdata['status'].'</td>
		                        <td>'.$comdata['datetime'].'</td>
		                        <td data-type="subtablelink" class="rel">
			                        <div class="loadmask loadmask hidden">
	                                    <img src="'.$host_addr.'images/loading.gif" 
	                                    class="loadermini _mini"/>
	                                </div>
			                        '.$comdata['tableurl'].'
		                        </td>
		                    </tr>
						';
					}
					$commdt.='</tbody>
						</table>
					';
				}

				$row['admincomments']=$commdt;;
				$row['admincommentcount']=$comdataone['numrows'];
				// include the script for handling the comments datatable for the
				// current entry
				$selectionscripts.='
					if($.fn.dataTable){
						$("table[data-dTable=true]").dataTable();
					}
				';
				if($viewer=="viewer"){
					$comdatatwo=getAllComments('viewer','all',"",$cdata);
					$row['viewercomments']=$comdatatwo['resultdataset'];
					$row['viewercommentcount']=$comdatatwo['numrows'];

					// get the next and previous blog posts
					$nbq="SELECT * FROM blogentries WHERE id>$id 
					AND blogtypeid='$blogtypeid' 
					AND status='active' ORDER BY date  LIMIT 0,1";
					$nbrun=briefquery($nbq,__LINE__,"mysqli");
					$nextblogdata=isset($nbrun['resultdata'][0])?
					$nbrun['resultdata'][0]:"";
					$nextblogdata['numrows']=$nbrun['numrows'];

					if(isset($nbrun['resultdata'][0])&&
						$nbrun['resultdata'][0]['id']>0){

						$nbdata=$nbrun['resultdata'][0];

						// get the foldername
						$nextpage=
						''.$host_addr.'blog/'.$blogtypedata['foldername'].'/'.$nbdata['pagename'].".php";
						unset($nbdata['blogentry']);
						$row['nextblogdata']=$nbdata;
						$row['nextblogdata']['pagelink']=$nextpage;

					}
					$row['nextblogdata']['numrows']=$nbrun['numrows'];
					unset($nbrun);


					$pbq="SELECT * FROM blogentries WHERE id<$id 
					AND blogtypeid='$blogtypeid' 
					AND status='active' ORDER BY date DESC LIMIT 0,1";
					$pbrun=briefquery($pbq,__LINE__,"mysqli");
					if(isset($pbrun['resultdata'][0])&&
						$pbrun['resultdata'][0]['id']>0){

						$pbdata=$pbrun['resultdata'][0];

						// get the foldername
						$prevpage=
						''.$host_addr.'blog/'.$blogtypedata['foldername'].'/'.$pbdata['pagename'].".php";

						unset($pbdata['blogentry']);
						$row['prevblogdata']=$pbdata;
						$row['prevblogdata']['pagelink']=$prevpage;
					}
					$row['prevblogdata']['numrows']=$pbrun['numrows'];
					unset($pbrun);

				}

				// this section is for treating blog posts with externally handled
				// comment systems
				$row['comment_count']="";
				if($row['commenttype']!=="normal"){
					// handle disqus based embed
					if($row['commenttype']=="disqus"){
						$row['comment_count']='
						<span class="disqus-comment-count" 
						data-disqus-url="'.$pagelink.'"></span>';
					}					
				}

				// create the table data display
				// '.$commentdata['commentcount'].'
				$tddataoutput=isset($tdataoutput)&&$tdataoutput!==""?$tdataoutput:'
		    		<td class="tdimg">'.$coverphoto.'</td>
			    	<td>'.$link.'</td>
			    	<td>'.$blogtypedata['name'].'</td>
			    	<td>'.$beticon.'</td>
			    	<td>'.$blogcatdata['catname'].'</td>
			    	<td>'.$commentsonoff.'</td>
			    	<td>'.$views.'</td>
			    	<td>'.$entrydate.'</td>
			    	<td>'.$modifydate.'</td>
			    	<td>'.$status.'</td>
					
					<td name="trcontrolpoint">
						<a href="#&id='.$id.'" name="edit" data-type="editgeneraldata" data-divid="'.$id.'" '.$datamapout.'>
							Edit
						</a>
					</td>
				';
				
				$adminoutput.='
					<tr data-id="'.$id.'">
						'.$tddataoutput.'
					</tr>
					<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'" '.$datamapout.'>
						<td colspan="100%">
							<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
								<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
									
								</div>
							</div>
						</td>
					</tr>
				';

				// this section handles sub table display data
				// the subtable comprises of div tags styled for table-like display 
				$controlled="trcontrolpoint";
				$editout='
						<a href="#&id='.$id.'" name="edit" data-type="editgeneraldata" data-divid="'.$id.'" '.$datamapout.'>
							Edit
						</a>
				';
				$tdsubdataoutput=isset($tdsubdataoutput)&&$tdsubdataoutput!==""?$tdsubdataoutput:'
					<div class="rTableCell tdimg">'.$coverphoto.'</div>
			    	
					<div class="rTableCell">'.$link.'</div>
								    	
					<div class="rTableCell">'.$blogtypedata['name'].'</div>
								    	
					<div class="rTableCell">'.$beticon.'</div>
								    	
					<div class="rTableCell">'.$blogcatdata['catname'].'</div>
								    	
					<div class="rTableCell">'.$commentsonoff.'</div>
								    	
					<div class="rTableCell">'.$views.'</div>
								    	
					<div class="rTableCell">'.$entrydate.'</div>
								    	
					<div class="rTableCell">'.$modifydate.'</div>
								    	
					<div class="rTableCell">'.$status.'</td>
					<div class="rTableCell" name="'.$controlled.'">
						'.$editout.'
					</div>

				';
				$adminsuboutput='
					<div class="rTableRow" data-id="'.$id.'">
						'.$tdsubdataoutput.'
					</div>
					<div class="rTableRow" name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'" '.$datamapout.'>
						<div class="rTableCell colspan" colspan="100%">
							<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
								<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
									
								</div>
							</div>
						</div>
					</div>
				';
			}
		}

		!isset($adminsuboutput)?$adminsuboutput="":$test="true";
		$selectionscriptsout='<script>$(document).ready(function(){'.$selectionscripts.'});</script>';

	  	$row['totalscripts']=$selectionscripts;
		$row['adminoutput']=$adminoutput;
		$row['adminsuboutput']=$adminsuboutput;
		$initvar[0]="editid";
		$initvar[1]="viewtype";
		$initvar[2]="row";
		$initvar[3]="maintype2";
		$initval[0]=$id;
		$initval[1]="edit";
		$initval[2]=$row;
		$initval[3]="editblogentry";
		$row['adminoutputtwo']="";
		if($blockdeeprun==""){
			// $row['adminoutputtwo']=get_include_contents("../forms/blogentries.php",$initvar,$initval);
		}
		

		return $row;
	}

	function getAllBlogEntries($viewer,$limit,$type="",$data=array()){
		global $host_tpathplain;
		include($host_tpathplain.'globalsmodule.php');
		
		$row=array();

		// controls the blogtypeid variable value, default value is 1 which is the
		// first blogtype entry created or the default
		$blogtypeid=1;

		if(isset($data['blogtypeid'])&&$data['blogtypeid']!==""){
			$blogtypeid=$data['blogtypeid'];
		}else{
			$data['blogtypeid']=1;
		}

		// controls order of query data output
		if(isset($data['queryorder'])&&$data['queryorder']!==""){
			$ordercontent=$data['queryorder'];
		}

		// specifies if query information should be echoed out
		$sq=false;
		if(isset($data['querydebug'])&&$data['querydebug']!==""){
			$sq=$data['querydebug'];
		}

		// specifies if non javascript pagination is to be utilised
		$rvpagination="";
		if(isset($data['rvpagination'])&&$data['rvpagination']!==""){
			$rvpagination=$data['rvpagination'];
		}

	 	// this block handles the content of the limit data
	 	// testing and stripping it of unnecessary/unwanted characters
		str_replace("-", "", $limit);

		$testittwo=strpos($limit,",");
		// echo $testittwo;
		if($testittwo!==false||$testittwo===true||$testittwo>0){
			$limit=$limit;
		}else{
			if(strtolower($limit)=="all"){
				$limit="";
			}else{
				$limit="LIMIT 0,15";				
			}
		}

		$realoutputdata="";
		$mtype="";
		if($type!==""){
			$mtype=$type;
			if (is_array($type)) {
				# code...
				if(isset($mtype['lastid'])||isset($mtype['nextid'])){
					$callpage="true";
					if(isset($mtype['lastid'])){
						$addq=" AND id>".$mtype['lastid'];
					}
					if(isset($mtype['nextid'])){
						$addq=" AND id<".$mtype['nextid'];
					}
				}
				$type=$mtype[0];
				$typeval=$mtype[1];
				$realoutputdata="$type][$typeval";
			
			}else{
				$realoutputdata=$type;
			}
		}
		// assign the viewer argument to the data['single'] array
		// so it goes down to the singleview level
		$data['single']['viewer']=$viewer;
		// run through the data array and obtain only the 'single' index
		// of it
		$singletype="";
		if(isset($data['single']['type'])&&$data['single']['type']!==""){
			$singletype=$data['single']['type'];
		}

		// check to see if there is an entry for the 'type' parameter in the single
		// selection version of the current entry.
		$outputtype="generalpages|blogentries|".$viewer;
		$queryoverride="";
		$queryextra="";
		$ordercontent="order by id desc";
		$qcat=$viewer=="admin"?"WHERE":"AND";

		$queryextra=" $qcat blogtypeid='$blogtypeid'";
		
		// appendable subqueries 
		if(isset($data['queryextra'])&&$data['queryextra']!==""){
			// make sure query extra is not empty
			if($queryextra==""){
				$queryextra.=" $qcat ".$data['queryextra'];
			}else{
				$queryextra.=" AND ".$data['queryextra'];
			}
			
		}



		// controls order of query data output
		if(isset($data['queryorder'])&&$data['queryorder']!==""){
			$ordercontent=$data['queryorder'];
		}

		// controls order of query data output
		if(isset($data['queryorder'])&&$data['queryorder']!==""){
			$ordercontent=$data['queryorder'];
		}

		// completely overrides the default query 
		if(isset($data['queryoverride'])&&$data['queryoverride']!==""){
			// get rid of new lines in the event they are presnt in the override
			// query
			$searchar[]="\r\n";
			$searchar[]="\n";
			$replacear[]=' ';
			$replacear[]=' ';
			$queryoverride=str_replace($searchar,$replacear,$data['queryoverride']);
		}

		// query generation point
	  	if($viewer=="admin"){
		  	$query="SELECT * FROM blogentries $queryextra $ordercontent $limit";
		    $rowmonitor['chiefquery']="SELECT * FROM blogentries $queryextra 
		    $ordercontent";
	  	}else if($viewer=="viewer"){
		 	$query="SELECT * FROM blogentries WHERE status='active' $queryextra  
		 			$ordercontent $limit";		
		  	$rowmonitor['chiefquery']="SELECT * FROM blogentries WHERE status='active'
		  			$queryextra $ordercontent";
	  	}

	  	if($rvpagination!==""){
	  		// get the user pagination data out
	  		$row['rvpagination']=$data['upag'];
	  	}

	  	// override default query if necessary
		if($queryoverride!==""){
			$query="$queryoverride $ordercontent $limit";
			$rowmonitor['chiefquery']="$queryoverride $ordercontent";
		}

		if($sq==true){
			// echo $viewer;
			echo $query;
			
		}
		
		// unique hash per data transaction call
		$rmd5=md5($rowmonitor['chiefquery'].date("Y-m-d H:i:s"));

		// create a data array single index entry for the rmd5 hash
		if(!isset($data['single']['rmd5'])){
			$data['single']['rmd5']=$rmd5;
		}

		// return the query, only for tests with Ajax json 
		$row['cqtdata']=$query;

		// create the $_SESSION['generalpagesdata'] array value to ensure continuity
		// when paginating content. This is done by checking the sessionuse 
		if((!isset($data['sessionuse'])&&!isset($data['chash']))||
			($data['sessionuse']==""&&$data['chash']=="")){

			// store current output type
			$_SESSION['generalpagesdata']["$rmd5"]['outputtype']=$outputtype;

			// store current data array
			$_SESSION['generalpagesdata']["$rmd5"]['data']=$data;

			// store custom ipp array if available
			$_SESSION['generalpagesdata']["$rmd5"]['ipparr_override']=
			isset($ipparr_override)?$ipparr_override:"";
			
			// store current type entry
			$_SESSION['generalpagesdata']["$rmd5"]['type']=$mtype;
			
			// set the value for the pagination handler file path
			// using the 'snippets' folder as the root. 
			$_SESSION['generalpagesdata']["$rmd5"]['pagpath']="forms/blogentries.php";

			// set the pagintation type variable which helps differentiate what to be
			// paginated in the case of single 'form' file handling serving 
			// several modules
			$_SESSION['generalpagesdata']["$rmd5"]['pagtype']="blogcategory";

			// use the 'cq' session index to secure this query
			$_SESSION['cq']["$rmd5"]=$rowmonitor['chiefquery'];

			/*This section is for indexes that are specific to the current function*/

			// store current blogtypeid used for the query
			$_SESSION['generalpagesdata']["$rmd5"]['blogtypeid']=$blogtypeid;

		}else{
			// echo __LINE__;
			// var_dump($data);
			if(isset($data['single']['rmd5'])&&$data['single']['rmd5']!==""){
				$rmd5=$data['single']['rmd5'];
			}
		}

		// prep the datamap element
		$mapelement="";
		if(isset($data['datamap'])&&$data['datamap']!==""){
			// array map element map data for handling custom gd request
			// echo "maptrick<br>";
			$curdatamap=JSONtoPHP($data['datamap']);
			if($curdatamap['error']=="No error"){
				if($queryoverride!==""){
					$cd=$curdatamap['arrayoutput'];
					$cd['rmd5']=$rmd5;
					$cd['overriden']="true";
					$data=json_encode($cd);
				}

				$mapelement='
					<input type="hidden" name="datamap" value=\''.$data['datamap'].'\'/>
				';
					
			}else{
				echo"<br>Map error<br>";
			}
		}
		// execute the current query
		$qdata=briefquery($query,__LINE__,"mysqli");
		
		$adminoutput='<tr><td colspan="100%">No entries found</td></tr>';
		
		$row['resultdataset']=array();
		$numrows=$qdata['numrows'];
    	$selectiondata='<option value="">--Choose--</option>';
		$formoutput="";
		$formoutputtwo="";
		$totalscripts="";

		$monitorpoint="";

		if($numrows>0){
		  	
		  	$adminoutput="";

			for($i=0;$i<$numrows;$i++){

				$id=$qdata['resultdata'][$i]['id'];

				$data['single']['row']=$qdata['resultdata'][$i];
				$data['single']['blockdeeprun']="true";

		      	$outs=getSingleBlogEntry($id,$singletype,$data);

				$row['resultdataset'][$i]=$outs;

		      	$adminoutput.=$outs['adminoutput'];

		    	$selectiondata.='<option value="'.$outs['id'].'">'.$outs['title'].'</option>';

				$totalscripts.=$outs['totalscripts'];

		    }

		}

	  	$outs=paginatejavascript($rowmonitor['chiefquery']);
	  	
	  	$row['totalscripts']=$totalscripts;
		$row['selectiondata']=$selectiondata;
		$row['chiefquery']=$rowmonitor['chiefquery'];
		$row['num_pages']=$outs['num_pages'];
		$row['paginatedata']=$outs;
		$adminheadings='
	  		<thead>
	  			<tr>
	  				<th>Coverphoto</th>
	  				<th>PageAddress</th>
	  				<th>Blogtype</th>
	  				<th>PostType</th>
	  				<th>Category</th>
	  				<th><i class="fa fa-comments"></i></th>
	  				<th>Views</th>
	  				<th>PostDate</th>
	  				<th>LastModified</th>
	  				<th>Status</th>
	  				<th>View/Edit</th>
	  			</tr>
	  		</thead>
		';
		if(isset($data['group']['adminheadings'])&&$data['group']['adminheadings']=""){
			$adminheadings=$data['group']['adminheadings'];
		}

		$top='<table id="resultcontenttable" cellspacing="0">
					'.$adminheadings.'
					<tbody>';
		$bottom='	</tbody>
				</table>';

		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.$rmd5.'"/>
				<input type="hidden" name="outputtype" 
				value="'.$outputtype.'|'.$realoutputdata.'"/>
				'.$mapelement.'
				<input type="hidden" name="currentview" data-ipp="15" 
				data-page="1" value="1"/>
				<div class="pagination" data-name="paginationpageshold">
				'.$outs['pageout'].'</div>
				<div class="pagination">
					  '.$outs['usercontrols'].'
				</div>
			</div>
		</div>
		<div id="paginateddatahold" data-name="contentholder">';
		$paginatebottom='
		</div><div id="paginationhold">
			<div class="meneame">
				<div class="pagination" data-name="paginationpageshold">
				'.$outs['pageout'].'
				</div>
			</div>
		</div>';
		
		$row['paginatetop']=$paginatetop;
		$row['paginatebottom']=$paginatebottom;
		$row['numrows']=$numrows;
		$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
		$row['adminoutputtwo']=$top.$adminoutput.$bottom;
	  	
		$row['chiefquery']=$rowmonitor['chiefquery'];
		$row['return_data']='
			<h42>colname list</h2>
			<p><b>id:</b><br>
				Refers to the id for the blog post
			</p>
			<p><b>blogtypeid:</b><br>
				refers to the parent blogtype for the current post
			</p>
			<p><b>blogcatid:</b><br>
				This is the category id for the current post 
			</p>
			<p><b>blogentrytype:</b><br>
				This is the type for the current post, values are:<br> 
				"normal" or "", "gallery"(has a particular series of image),
				"banner"(single image LARGE image), "video"(either a local 
					webm,3gp,mp4,flv video or embedded
				video), "audio"(either local mp3 or embedded audio);  
			</p>
			<p><b>betype:</b><br>
				for legacy data, previously used to specify if video/audio blogentrytype
				post required their local/embedded data to be dislpayed.
			</p>
			<p><b>becode:</b><br>
				for legacy data, used to hold embed code for video/audio entrytypes
			</p>

			<p><b>title:</b><br>

			</p>
			<p><b>introparagraph:</b><br>
				this is a brief intro to the blog post, usually gives an overview 
				about the main post.
			</p>
			<p><b>blogpost:</b><br>
				this is the full entry for the blog post and shows up on the next 
				page
			</p>
			<p><b>entrydate:</b><br>
				this is the date the post was made or made available for public reading
			</p>
			<p><b>modifydate:</b><br>
				this is the date the entry was last changed
			</p>
			<p><b>views:</b><br>
				Self explanatory, its a number representing the number of views made on
				a post
			</p>

			<p><b>commentsonoff:</b><br>
				specifies if the post makes use of comments or not, values are:
				"yes" or "no"
			</p>
			<p><b>featurepost:</b><br>
				tells if the post is a feautured entry
			</p>

			<p><b>tags:</b><br>
				this carries the tags for a post
			</p>
			<p><b>seometakywords:</b><br>
				carries the poster defined keywords for the header meta keyword tage
			</p>
			<p><b>seometadescription:</b><br>
				carries the poster defined description for the current page
			</p>
			<p><b>pwrdd:</b><br>
				tells if a post is passworded, values are:
				"yes" or "no"
			</p>
			<p><b>pwrd:</b><br>
				the password for viewing the passworded post
			</p>
			<p><b>posterid:</b><br>
				the id of the person that posted the content, you can use the
				getSingleAdminUser($posterid,"",$data) where $data is an array
				with the index $data["single"]["viewer"]="blogposter"; it is extremely 
				important that this parameter have a value for the function to work
				function to return keydata about the poster with the values.
			</p>
		';
		if($viewer=="viewer"){
			unset($row['options']);
			unset($row['feeddate']);
			unset($row['adminoutput']);
			unset($row['adminoutputtwo']);
		}
	  	return $row;
	}





	function getSingleComment($id,$type="",$data=array()){
		global $host_tpathplain;
		include($host_tpathplain.'globalsmodule.php');

		$row=array();
		$query="SELECT * FROM comments WHERE id=$id";
		// for overriding the default query
		$queryoverride="";
		if(isset($data['single']['queryoverride'])&&$data['single']['queryoverride']!==""){
			$queryoverride=$data['single']['queryoverride'];
			$query="$queryoverride WHERE id='$id'";
			
		}


		// check to see if there already is a row index carrying the intended
		// for the current transaction 
		if(isset($data['single']['row'])&&$data['single']['row']!==""){
			$qdata['resultdata'][0]=$data['single']['row'];
			$qdata['numrows']=1;
			
		}else{
			$qdata=briefquery($query,__LINE__,"mysqli");
		}

		$numrows=$qdata['numrows'];
		$adminoutput='<tr><td colspan="100%">No entries found</td></tr>';
		$adminoutputtwo="";
		$totalscripts="";
		
		// init important variable values pertaining to content in the $data array 
		
		$datamapout="";// caries the datamap for current operations
		
		$tdataoutput="";// carries the td dataoutput for the tr data cells 
		
		$formtruetype="edit_";// carries the name of the edit form

		$processroute=""; //carries the path to the process file for this function 

		$editroute=""; 
		$totalscripts="";
		$gc="";	
		$crmd5="";	
		$blockdeeprun="";
		if($type=="blockdeeprun"){
			$blockdeeprun="true";
		}


		if(isset($data['single'])){
			// var_dump($data);

			// create a data rray single index entry for the rmd5 hash
			if(isset($data['single']['rmd5'])&&$data['single']['rmd5']!==""){
				$crmd5=$data['single']['rmd5'];	
			}

			if(isset($data['single']['datamap'])&&$data['single']['datamap']!==""){
				$datamap=$data['single']['datamap'];
				$gd_testdata=JSONtoPHP($datamap);
				$gd_dataoutput=$gd_testdata['arrayoutput'];
				// var_dump($gd_dataoutput);

				$processroute=isset($gd_dataoutput['pr'])?$gd_dataoutput['pr']:"";

				$editroute=isset($gd_dataoutput['er'])?$gd_dataoutput['er']:$processroute;
				if($queryoverride!==""){
					// add the session access hash and 
					// the 'overriden index to the datamap'
					$gd_dataoutput['pr']=stripslashes($processroute);
					$gd_dataoutput['er']=stripslashes($editroute);
					$gd_dataoutput['rmd5']=$crmd5;
					$gd_dataoutput['overriden']="true";
					$datamap=json_encode($gd_dataoutput);
					// var_dump($cd);
				}
				$datamapout='data-edata=\''.$datamap.'\'';
			}

			if(isset($data['single']['rowdefaults'])&&$data['single']['rowdefaults']!==""){
				$tdataoutput=$data['single']['rowdefaults'];
			}

			if(isset($data['single']['formtruetype'])&&$data['single']['formtruetype']!==""){
				$formtruetype=$data['single']['formtruetype'];
				// echo $formtruetype;
			}

			if(isset($data['single']['blockdeeprun'])&&$data['single']['blockdeeprun']!==""){
				$blockdeeprun=$data['single']['blockdeeprun'];
				// echo $blockdeeprun;
			}

			// get the count for the current entry in a group
			if(isset($data['single']['groupcount'])&&$data['single']['groupcount']!==""){
				$gc=$data['single']['groupcount'];
			}

			// strictly for the getSingleComment function
			
		}

		if($numrows>0){

			$adminoutput="";
			$adminoutputtwo="";

			for($i=0;$i<$numrows;$i++){

				$row=$qdata['resultdata'][$i];
				//include gravatar
				// include('Gravatar.php');
				$gravatar=new Gravatar();
				$gravatar->setAvatarSize(115);

				$id=$row['id'];
				$type=isset($row['type'])?$row['type']:"";
				$fullname=$row['fullname'];
				$email=$row['email'];
				$website=isset($row['website'])?$row['website']:"";
				// previous comment id, in the event the current comment is a reply
				// to one
				$cid=isset($row['cid'])?$row['cid']:"";
				$pid=isset($row['pid'])?$row['pid']:0;
				$row['website']=$website;
				$avatar = $gravatar->buildGravatarURL(''.$email.'');

				$row['avatar']=$avatar!==""?$avatar:$host_addr.'images/default.gif';
				$blogpostid=$row['blogentryid'];
				
				$blogquery="SELECT * FROM blogentries where id=$blogpostid";
				$blogrun=mysql_query($blogquery)or die(mysql_error()." Line ".__LINE__);
				$blognumrows=mysql_num_rows($blogrun);
				$blogrow=mysql_fetch_assoc($blogrun);

				$blogtypeid=$blogrow['blogtypeid'];
				$blogtypedata=getSingleBlogType($blogtypeid);
				$pagename=$blogrow['pagename'];
				$pagelink=''.$host_addr.'blog/'.$blogtypedata['foldername'].'/'.$pagename.'.php';
				$link='<a href="'.$pagelink.'?ch=" target="_blank" title="click to view this blog post">'.$blogrow['title'].'</a>';
				$rellink='./blog/'.$blogtypedata['foldername'].'/'.$pagename.'.php';
				$row['pagelink']=$pagelink;
				$row['rellink']=$rellink;
				
				$comment=$row['comment'];
				$comment=str_replace("../../",$host_addr,$comment);
				
				$date=$row['datetime'];
				$dp=toPeriod($date);

				// provide the date in a more human readable manner
				$row['comment_date']=$dp['povalue']." ".$dp['potext'];
				$row['comment_datetwo']=$dp['poaddtext'];

				$status=$row['status'];
				$tableout='';

				if($status=="active"){
					$tableout='<a href="#&id='.$id.'" name="disablecomment" data-type="disablecomment" data-divid="'.$id.'">Disable</a>';
				}elseif($status=="inactive"){
					$tableout='<a href="#&id='.$id.'" name="activatecomment" data-type="activatecomment" data-divid="'.$id.'">Activate</a>';
				}elseif($status=="disabled"){
					$tableout='<a href="#&id='.$id.'" name="reactivatecomment" data-type="reactivatecomment" data-divid="'.$id.'">Reactivate</a>';
				}

				$row['tableurl']=$tableout;
				$datetwo=str_replace(",", "", $date);
				$dateday=date("d",strtotime($datetwo));
				$datemon=date("M",strtotime($datetwo));
				$dateyear=date("Y",strtotime($datetwo));
				$row['day']=$dateday;
				$row['month']=$datemon;
				$row['year']=$dateyear;

				$datetime=date("h:i:m A",strtotime($datetwo));
				$maindayout=date('D, d F, Y h:i:s A', strtotime($datetwo));
				$row['maindayout']=$maindayout;

				$adminoutput.='
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
			  	$adminoutputtwo.='
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
			}
		}
	  	$row['adminoutput']=$adminoutput;
	  	$row['adminoutputtwo']=$adminoutputtwo;
	  	$row['totalscripts']=$totalscripts;
	  	return $row;
	}

	function getAllComments($viewer,$limit,$type="",$data=array()){
		global $host_tpathplain;
		include($host_tpathplain.'globalsmodule.php');
		
		$row=array();

		// controls the blogpostid variable value, default value is 1 which is the
		// first blogtype entry created or the default
		$blogpostid="";
		if(isset($data['blogpostid'])&&$data['blogpostid']!==""){
			$blogpostid=$data['blogpostid'];
		}else{
			$data['blogpostid']="";
		}

		// controls order of query data output
		if(isset($data['queryorder'])&&$data['queryorder']!==""){
			$ordercontent=$data['queryorder'];
		}
	 	// this block handles the content of the limit data
	 	// testing and stripping it of unnecessary/unwanted characters
		str_replace("-", "", $limit);

		$testittwo=strpos($limit,",");
		// echo $testittwo;
		if($testittwo!==false||$testittwo===true||$testittwo>0){
			$limit=$limit;
		}else{
			if(strtolower($limit)=="all"){
				$limit="";
			}else{
				$limit="LIMIT 0,15";				
			}
		}

		$realoutputdata="";
		$mtype="";
		if($type!==""){
			$mtype=$type;
			if (is_array($type)) {
				# code...
				if(isset($mtype['lastid'])||isset($mtype['nextid'])){
					$callpage="true";
					if(isset($mtype['lastid'])){
						$addq=" AND id>".$mtype['lastid'];
					}
					if(isset($mtype['nextid'])){
						$addq=" AND id<".$mtype['nextid'];
					}
				}
				$type=$mtype[0];
				$typeval=$mtype[1];
				$realoutputdata="$type][$typeval";
			
				
			}else{
				$realoutputdata=$type;
			}
		}

		// run through the data array and obtain only the 'single' index
		// of it
		$singletype="";
		if(isset($data['single']['type'])&&$data['single']['type']!==""){
			$singletype=$data['single']['type'];
		}

		// check to see if there is an entry for the 'type' parameter in the single
		// selection version of the current entry.
		$outputtype="generalpages|blogcomments|".$viewer;
		$queryoverride="";
		$queryextra="";
		$ordercontent="order by datetime asc, id desc, status asc";
		$qcat=$viewer=="admin"?"WHERE":"AND";

		// adjust the queryextra variable to accomodated the blogpostid value
		$queryextra=$blogpostid!==""?" $qcat blogentryid='$blogpostid'":"";
		
		// appendable subqueries 
		if(isset($data['queryextra'])&&$data['queryextra']!==""){
			if($queryextra==""){
				$queryextra.=" $qcat ".$data['queryextra'];
			}else{
				$queryextra.=" AND ".$data['queryextra'];
			}
		}

		// controls order of query data output
		if(isset($data['queryorder'])&&$data['queryorder']!==""){
			$ordercontent=$data['queryorder'];
		}

		// completely overrides the default query 
		if(isset($data['queryoverride'])&&$data['queryoverride']!==""){
			$queryoverride=$data['queryoverride'];
		}

		// query generation point
		$rowmonitor['chiefquery']="";
	  	if($viewer=="admin"){
		  	$query="SELECT * FROM comments $queryextra $ordercontent $limit";
		    $rowmonitor['chiefquery']="SELECT * FROM comments $queryextra 
		    		$ordercontent";
	  	}else if($viewer=="viewer"){
		 	$query="SELECT * FROM comments WHERE status='active' $queryextra  
		 			$ordercontent $limit";		
		  	$rowmonitor['chiefquery']="SELECT * FROM comments WHERE status='active'
		  			$queryextra $ordercontent";
	  	}else{
	  		$query="SELECT * FROM comments WHERE id='0' $queryextra  
		 			$ordercontent $limit";		
		  	$rowmonitor['chiefquery']="SELECT * FROM comments WHERE id='0' $queryextra  
		 			$ordercontent ";
	  	}
	  	// override default query if necessary
		if($queryoverride!==""){
			$query="$queryoverride $ordercontent $limit";
			$rowmonitor['chiefquery']="$queryoverride $ordercontent";
		}

		// echo $viewer;
		// echo $query;
		
		// unique hash per data transaction call
		$rmd5=md5($rowmonitor['chiefquery'].date("Y-m-d H:i:s"));

		// create a data array single index entry for the rmd5 hash
		if(!isset($data['single']['rmd5'])){
			$data['single']['rmd5']=$rmd5;
		}

		// return the query, only for tests with Ajax json 
		$row['cqtdata']=$query;

		// create the $_SESSION['generalpagesdata'] array value to ensure continuity
		// when paginating content. This is done by checking the sessionuse 
		if((!isset($data['sessionuse'])&&!isset($data['chash']))||
			($data['sessionuse']==""&&$data['chash']=="")){

			// store current output type
			$_SESSION['generalpagesdata']["$rmd5"]['outputtype']=$outputtype;

			// store current data array
			$_SESSION['generalpagesdata']["$rmd5"]['data']=$data;

			// store custom ipp array if available
			$_SESSION['generalpagesdata']["$rmd5"]['ipparr_override']=
			isset($ipparr_override)?$ipparr_override:"";
			
			// store current type entry
			$_SESSION['generalpagesdata']["$rmd5"]['type']=$mtype;
			
			// set the value for the pagination handler file path
			// using the 'snippets' folder as the root. 
			$_SESSION['generalpagesdata']["$rmd5"]['pagpath']="forms/blogcomments.php";

			// set the pagintation type variable which helps differentiate what to be
			// paginated in the case of single 'form' file handling serving 
			// several modules
			$_SESSION['generalpagesdata']["$rmd5"]['pagtype']="blogcomments";

			// use the 'cq' session index to secure this query
			$_SESSION['cq']["$rmd5"]=$rowmonitor['chiefquery'];

			/*This section is for indexes that are specific to the current function*/

			


		}else{
			// echo __LINE__;
			// var_dump($data);
			if(isset($data['single']['rmd5'])&&$data['single']['rmd5']!==""){
				$rmd5=$data['single']['rmd5'];
			}
		}

		// prep the datamap element
		$mapelement="";
		if(isset($data['datamap'])&&$data['datamap']!==""){
			// array map element map data for handling custom gd request
			// echo "maptrick<br>";
			$curdatamap=JSONtoPHP($data['datamap']);
			if($curdatamap['error']=="No error"){
				if($queryoverride!==""){
					$cd=$curdatamap['arrayoutput'];
					$cd['rmd5']=$rmd5;
					$cd['overriden']="true";
					$data=json_encode($cd);
				}

				$mapelement='
					<input type="hidden" name="datamap" value=\''.$data['datamap'].'\'/>
				';
					
			}else{
				echo"<br>Map error<br>";
			}
		}

		$qdata=briefquery($query,__LINE__,"mysqli");
		
		$adminoutput='<tr><td colspan="100%">No entries found</td></tr>';
		$adminoutputtwo='<tr><td colspan="100%">No entries found</td></tr>';
		
		$row['resultdataset']=array();
		$numrows=$qdata['numrows'];
    	$selectiondata='<option value="">--Choose--</option>';
		$formoutput="";
		$formoutputtwo="";
		$totalscripts="";

		$monitorpoint="";

		if($numrows>0){
		  	
		  	$adminoutput="";
		  	$adminoutputtwo="";

			for($i=0;$i<$numrows;$i++){

				$id=$qdata['resultdata'][$i]['id'];

				$data['single']['row']=$qdata['resultdata'][$i];

		      	$outs=getSingleComment($id,$singletype,$data);
		      	
				$row['resultdataset'][$i]=$outs;

		      	$adminoutput.=$outs['adminoutput'];
		      	$adminoutputtwo.=$outs['adminoutputtwo'];

		    	$selectiondata.='<option value="'.$outs['id'].'">'.$outs['email'].'</option>';

				$totalscripts.=$outs['totalscripts'];


		    }

		}

	  	$outs=paginatejavascript($rowmonitor['chiefquery']);
	  	$row['totalscripts']=$totalscripts;
		$row['selectiondata']=$selectiondata;
		$row['chiefquery']=$rowmonitor['chiefquery'];
		$row['num_pages']=$outs['num_pages'];
		
		$adminheadings='
			<thead><tr><th>Fullname</th><th>Email</th><th>CommentDate</th>
  			<th>Comment</th><th>Post</th><th>Status</th><th>View/Edit</th>
  			</tr></thead>
		';
		if(isset($data['group']['adminheadings'])&&$data['group']['adminheadings']=""){
			$adminheadings=$data['group']['adminheadings'];
		}

		$top='<table id="resultcontenttable" cellspacing="0">
					'.$adminheadings.'
					<tbody>';
		$bottom='	</tbody>
				</table>';

		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.$rmd5.'"/>
				<input type="hidden" name="outputtype" value="'.$outputtype.'|'.$realoutputdata.'"/>
				'.$mapelement.'
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
		
		$row['paginatetop']=$paginatetop;
		$row['paginatebottom']=$paginatebottom;
		$row['numrows']=$numrows;
		$row['adminoutput']=$paginatetop.$top.$adminoutputtwo.$bottom.$paginatebottom;
		$row['adminoutputtwo']=$top.$adminoutputtwo.$bottom;
	  	
		$row['chiefquery']=$rowmonitor['chiefquery'];

	  	return $row;
	}


	function getSingleSubscriber($subscriberid){
	  $row=array();
	  $query="SELECT * FROM subscriptionlist WHERE id=$subscriberid";
	  $run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
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
	    $catrun=mysql_query($catquery)or die(mysql_error()." Line ".__LINE__);
	    $blogcategorydata=mysql_fetch_assoc($catrun);
	  }
	  if($blogtypeid>0){
	    $blogtypedata=getSingleBlogType($blogtypeid,'blockdeeprun');
	  }else{
	    $blogtypedata=getSingleBlogType($blogcategorydata['blogtypeid'],'blockdeeprun');
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
	  $run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
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

	// for scheduled publishing of posts
	function publishPost($postid){
		global $host_tpathplain;
		include($host_tpathplain.'globalsmodule.php');
		$postdata=getSingleBlogEntry($postid);
		$pagename=$postdata['pagelink'];
		$pagelink=$postdata['pagelink'];
		$title=$postdata['title'];
		$introparagraph=$postdata['introparagraph'];
		$blogtypeid=$postdata['blogtypeid'];
		$blogcategoryid=$postdata['blogcatid'];
		$datetime= date("D, d M Y H:i:s", strtotime($postdata['postperiod']))." +0100";
		$date=date("d, F Y H:i:s", strtotime($postdata['postperiod']));
		//create blog post rss entry
		$introrssentry=str_replace("../",$host_addr,$introparagraph);
		$rssentry='<item>
		<title>'.$title.'</title>
		<link>'.$pagelink.'</link>
		<pubDate>'.$datetime.'</pubDate>
		<guid isPermaLink="false">'.$host_addr.'blog/?p='.$postid.'</guid>
		<description>
		<![CDATA['.$introrssentry.']]>
		</description>
		</item>
		';
		$rssentry=mysql_real_escape_string($rssentry);
		// echo $rssentry;
		$rssquery="INSERT INTO rssentries(blogtypeid,blogcategoryid,blogentryid,rssentry)VALUES('$blogtypeid','$blogcategoryid','$postid','$rssentry')";
		$rssrun=mysql_query($rssquery)or die(mysql_error());
		//write rss information to respective blog type(for autoposting) and blog category
		writeRssData($blogtypeid,0);
		writeRssData(0,$blogcategoryid);
		// update the blogpost date column and satus
		$postperiod=$postdata['postperiod'];
		genericSingleUpdate("blogentries","date","$postperiod","id",$postid);
		genericSingleUpdate("blogentries","entrydate","$date","id",$postid);
		genericSingleUpdate("blogentries","status","active","id",$postid);
		genericSingleUpdate("blogentries","scheduledpost","no","id",$postid);
		if(isset($host_email_send)&&$host_email_send==true){
			sendSubscriberEmail($postid);
		}
	}

	// for on the spot publishing of posts
	function livePublishing($postid){
		global $host_tpathplain;
		include($host_tpathplain.'globalsmodule.php');

		$postdata=getSingleBlogEntry($postid);
		$pagename=$postdata['pagelink'];
		$pagelink=$postdata['pagelink'];
		$title=$postdata['title'];
		$introparagraph=$postdata['introparagraph'];
		$blogtypeid=$postdata['blogtypeid'];
		$blogcategoryid=$postdata['blogcatid'];
		$datetime= date("D, d M Y H:i:s")." +0100";
		$date=date("d, F Y H:i:s");
		//create blog post rss entry
		$introrssentry=str_replace("../",$host_addr,$introparagraph);
		$rssentry='<item>
		<title>'.$title.'</title>
		<link>'.$pagelink.'</link>
		<pubDate>'.$datetime.'</pubDate>
		<guid isPermaLink="false">'.$host_addr.'blog/?p='.$postid.'</guid>
		<description>
		<![CDATA['.$introrssentry.']]>
		</description>
		</item>
		';
		$rssentry=mysql_real_escape_string($rssentry);
		// echo $rssentry;
		$rssquery="INSERT INTO rssentries(blogtypeid,blogcategoryid,blogentryid,rssentry)VALUES('$blogtypeid','$blogcategoryid','$postid','$rssentry')";
		$rssrun=mysql_query($rssquery)or die(mysql_error());
		//write rss information to respective blog type(for autoposting) and blog category
		writeRssData($blogtypeid,0);
		writeRssData(0,$blogcategoryid);
		sendSubscriberEmail($postid);
		// update the blogpost date column and satus
		$postperiod=$postdata['postperiod'];
		genericSingleUpdate("blogentries","date","$postperiod","id",$postid);
		genericSingleUpdate("blogentries","entrydate","$date","id",$postid);
		genericSingleUpdate("blogentries","scheduledpost","no","id",$postid);
		genericSingleUpdate("blogentries","status","active","id",$postid);
	}


	/**
	*	@see importBlogData() 
	*	
	*	@todo this function takes in a query string or array and performs inserts  
	*	to the 'blogtype', 'blogcategories', 'blogentries', 'rssheaders', 'rsstables' and
	*	tables. 
	*	
	*	It is basically used to import blog related data to the database and create 
	*	the blog posts page as well as rssfeed entry  
	*	
	*	@param string/array $qstring is a string/array carrying th queries to be
	*	executed for each table
	*	
	*	@param array $data is an array carrying extra content.
	*	
	*	@return array $row is a multidimensional array that carries the results
	*	for the operations carried out during the import
	*	var_dump($row) would give a full list if necessary
	*
	*/
	function importBlogData($qstring,$data=array()){
		$row=array();
	}


	/**
	*	@see dataPageCreation() 
	*	
	*	@todo this function runs throught the blogentries database and creates
	*	corresponding pages based on the blog post 'pagename' column value, if the
	*	page itself is not found
	*	
	*	It is basically used to ensure imported data is properly intergrated with the  
	*	blog system.
	*	
	*	
	*	@return null 
	*	
	*/
	function dataPageCreation($data=array()){
		global $host_tpathplain,$host_tpath;
		include($host_tpathplain.'globalsmodule.php');

		// lets get the data from the database
		$query="SELECT * FROM blogentries WHERE status='active'";
		$qdata=briefquery($query,__LINE__,"mysqli");
		if($qdata['numrows']>0){
			for($i=0;$i<$qdata['numrows'];$i++){
				$outs=$qdata['resultdata'][$i];
				
				$datarows['single']['row']=$outs;
				
				$row=getSingleBlogEntry($outs['id'],"",$datarows);

				$blogtypeid=$row['blogtypeid'];
				
				$blogcategoryid=$row['blogcatid'];
				
				$pageid=$row['id'];

				$entrydate=$row['entrydate'];

				$entrydatem=str_replace(",", "", $entrydate);
				//create the rss pubDate format for rss entry
				// $maindayout=date('D, d F, Y h:i:s A', strtotime($entrydatem));

				$datetime= date("D, d M Y H:i:s",strtotime($entrydatem));
				$date= date("Y-m-d H:i:s",strtotime($entrydatem));
				if($row['date']=="0000-00-00 00:00:00"){
					// update the date for the current entry to match the current 
					// entrydate
					genericSingleUpdate("blogentries","date",$date,"id",$pageid);
				}

				$datatime=$row['feeddate'];

				$date=date("d, F Y H:i:s",strtotime($entrydatem));

				$title=$row['title'];
				$pagename=$row['pagename'];
				$fullpage="$pagename.php";
				$foldername=$row['blogtypedata']['foldername'];
				// echo $fullpage;
				
				// create the physical page based on preobtained page data
				$pagepath="$host_tpath".$row['rellink'];
				$pagetpath="$host_tpath".$row['rellink'];
				// check to see if the post currently has a physical page
				if(!file_exists($pagetpath)){
					// echo "$pagetpath <br>";

					$handle = fopen($pagepath, 'w') or 
					die('Cannot open file:  '.$pagepath);
					//set the new page up
					// update page setup
			
					$pagesetup = '<?php 
						$pageid='.$pageid.';
						if(session_id()==\'\'){
							session_start();
						}
						if(!function_exists(\'getExtension\')){
							include(\'../../snippets/connection.php\');
						}
						include($host_tpathplain."modules/blogtemp.php");
						include($host_tpathplain."modules/blogpagecreate.php");
					?>';
			
					fwrite($handle, $pagesetup);
					fclose($handle);

					$introparagraph=$row['introparagraph'];
					
					//create blog post rss entry
					$introrssentry=str_replace("../",$host_addr,$introparagraph);
					$rssentry='<item>
					<title>'.$title.'</title>
					<link>'.$host_addr.'blog/'.str_replace("./", "/", $row['rellink']).'</link>
					<pubDate>'.$datetime.'</pubDate>
					<guid isPermaLink="false">'.$host_addr.'blog/?p='.$pageid.'</guid>
					<description>
					<![CDATA['.$introrssentry.']]>
					</description>
					</item>
					';
					$rssentry=mysql_real_escape_string($rssentry);
					// echo $rssentry;
					$rssquery="INSERT INTO rssentries(blogtypeid,blogcategoryid,blogentryid,
						rssentry)VALUES('$blogtypeid','$blogcategoryid','$pageid','$rssentry')";
					// echo "$rssquery<br>";
					$rssrun=mysql_query($rssquery)or die(mysql_error());
					// write rss information to respective blog type(for autoposting) and blog category
					writeRssData($blogtypeid,0);
					writeRssData(0,$blogcategoryid);
				}
				// perform option based operations
				if(isset($data['option'])){
					$coption=$data['option'];
					
					// perform complete rewrite of the base blog page file to 
					// a new type
					if($coption=="baserewrite"){

						$handle = fopen($pagepath, 'w') or 
						die('Cannot open file:  '.$pagepath);
						//set the new page up
						// update page setup
				
						$pagesetup = '<?php 
							$pageid='.$pageid.';
							if(session_id()==\'\'){
								session_start();
							}
							if(!function_exists(\'getExtension\')){
								include(\'../../snippets/connection.php\');
							}
							
							
							include($host_tpathplain."modules/blogtemp.php");

							include($host_tpathplain."modules/blogpagecreate.php");
						?>';
				
						fwrite($handle, $pagesetup);
						fclose($handle);
					}
				}
			}
		}
	}
?>