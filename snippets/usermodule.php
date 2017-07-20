<?php 
	function getAllBusinessTypes($id=0,$qe=""){
		global $host_addr;
		$row=array();
		if($qe==""){
			$qe="WHERE type='primary'";
		}
		$query="SELECT * FROM businessnature $qe AND status='active' ORDER BY businessnature";
		if($id>0){
			$query="SELECT * FROM businessnature WHERE id=$id";

		}
		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
		$numrows=mysql_num_rows($run);
		$selection="";
		$selectiontwo="";
		if($numrows>0){
			$i=0;
			while ($row=mysql_fetch_assoc($run)) {
				# code...
				$rowout[$i]=$row;
				$i++;
				$selection.='<option value="'.$row['businessnature'].'">'.$row['businessnature'].'</option>';
				$selectiontwo.='<option value="'.$row['id'].'">'.$row['businessnature'].'</option>';
			}
		}
		$row['selection']=$selection;
		$row['selectiontwo']=$selectiontwo;
		$row['resultdata']=$rowout;
		$row['numrows']=$numrows;
		return $row;
	};
	function getSingleUserPlain($id){
		global $host_addr;
		$row=array();
		$query="SELECT * FROM users where id=$id";
		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
		$numrows=mysql_num_rows($run);
		
		/*Image output section*/
			$originalimage=$host_addr."images/default.gif";
			$medimage=$host_addr."images/default.gif";
			$thumbimage=$host_addr."images/default.gif";
			$faceid=0;
			$banneroriginalimage="";
			$bannermedimage="";
			$bannerthumbimage="";
			$orgprofileimage="";
			$orgcacimage="";
			$poriginalimage='';
			$pmedimage='';
			$pthumbimage='';
			$cacoriginalimage='';
			$cacmedimage='';
			$cacthumbimage='';
			$bizprofileid=0;
			$bizcacid=0;

		/*end*/
		$nameout="";
		if($numrows>0){
			$row=mysql_fetch_assoc($run);
			$id=$row['id'];
			$fullname=$row['fullname'];
			$ndata=explode(" ",$fullname);
			$firstname=$ndata[0];
			$middlename=isset($ndata[1])?$ndata[1]:"";
			$lastname=isset($ndata[2])?$ndata[2]:"";
			$row['firstname']=$firstname;
			$row['middlename']=$middlename;
			$row['lastname']=$lastname;
			$gender=$row['gender'];
			$maritalstatus=$row['maritalstatus'];
			$state=$row['state'];
			// echo "SELECT * FROM state WHERE id_no=$state";
			$sdata=briefquery("SELECT * FROM state WHERE id_no=$state",__LINE__,"mysqli");
			$cdata=$sdata['resultdata'][0];
			$numrows=$sdata['numrows'];
			// if there are any results then set the state id to the current id
			$statename=$numrows>0?$cdata['state']:"";
			$row['statename']=$statename;
			// $state==""?$state="None specified":$state=$state;
			$lga=$row['lga'];
			$lgdata=$lga!==""?getSingleLGA($lga):"";
			// $row['lgdata']=$lgdata;
			$localgovt=isset($lgdata['local_govt'])?$lgdata['local_govt']:"";
			$row['lgdata']=$localgovt;
			$password=$row['pword'];
			$email=$row['email'];
			$phonenumber=$row['phonenumber'];
			if($phonenumber!==""){
				$phonearr=explode("[|><|]", $phonenumber);
				if(count($phonearr)>1){
					$phoneone=strlen($phonearr[0])==11?substr($phonearr[0], 1,9):$phonearr[0];
					$phonetwo=isset($phonearr[1])&&strlen($phonearr[1])==11?substr($phonearr[1], 1,9):$phonearr[1];
					$phonethree=isset($phonearr[2])&&strlen($phonearr[2])==11?substr($phonearr[2], 1,9):$phonearr[2];
				}else{
					$phoneone=$phonenumber;$phonetwo="";$phonethree="";
				}
			}else{
				$phoneone="";$phonetwo="";$phonethree="";
			}
			$row['phoneone']=$phoneone;
			$row['phonetwo']=$phonetwo;
			$row['phonethree']=$phonethree;
			$dob=$row['dob'];
			$age="";
			if($dob!=="0000-00-00"){
				$dobdata=explode("-",$dob);
				$dobyear=$dobdata[0];
				$curyear=date("Y");
				$age=$dobyear>0?$curyear-$dobyear:0;
			}
			$row['age']=$age;
			$regdate=$row['regdate'];
			$dobchangedate=$row['dobchangedate'];
			$genderchangedate=$row['genderchangedate'];
			$maritalstatuschangedate=$row['maritalstatuschangedate'];
			$today=date("Y-m-d");
			$timenow=date("H:i:s");
			$statechangedate=$row['statechangedate'];
			$pcmethod=$row['pcmethod'];
			$address=$row['address'];
			
			// comma seperated list of business properties or tags defining the business
			$bdata=$row['businessnature'];
			$bnid=$row['bnid'];
			if ($bnid>0) {
				// echo $bnid;
				$bndata=getAllBusinessTypes($bnid);
				$tbn=$bndata['resultdata'][0]['businessnature'];
				if($bdata!==$tbn){
					$bdata=$tbn;
					// run a table update reflecting the changes
					$cq="UPDATE users SET businessnature='$tbn' WHERE bnid='$bnid'";
					$cqrun=mysql_query($cq)or die(mysql_error()." Line ".__LINE__);

				}
				# code...
			}else{
				$row['bnid']="";
				$row['businessnature']="";
			}
			$row['businessnature']=$bdata;
			// for secondary service provided
			$bnid2=$row['sbnid'];
			if ($bnid2>0) {
				// echo $bnid;
				$bndata=getAllBusinessTypes($bnid2);
				$tbn=$bndata['resultdata'][0]['businessnature'];
				if($bdata!==$tbn){
					$bdata=$tbn;
					// run a table update reflecting the changes to the name
					// of the businessnature
					$cq="UPDATE users SET sbn='$tbn' WHERE sbnid='$bnid2'";
					$cqrun=mysql_query($cq)or die(mysql_error()." Line ".__LINE__);
				}
				# code...
				$row['businessnature2']=$bdata;
			}else{
				$row['sbnid']="";
				$row['businessnature2']="";
			}

			$bplode=explode(",", $bdata);
			$row['businessnaturecount']=count($bplode);
			$row['businessnaturedata']=array();
			for($i=0;$i<count($bplode);$i++){
				$row['businessnaturedata'][$i]=$bplode[$i];
			}
			// period of time the business has provided said services
			$spduration=$row['spduration'];
			$websiteurl=isset($row['websiteurl'])?$row['websiteurl']:"";
			$websiteurltwo=$websiteurl;
			if($websiteurl!==""){
				if($websiteurl!== "##"&&!strpos($websiteurl, "http://")&&
					!strpos($websiteurl, "https://")){
					$websiteurltwo="http://".$websiteurl;
				}
			}
			// forcefully ensure that a website url value is present
			$row['websiteurl']=$websiteurl;
			$row['websiteurltwo']=$websiteurltwo;
			$status=$row['status'];
			$nameout=$fullname; // variable holds default name by usertype
			if($row['usertype']=='user'){
				// get profile picture
				$facequery2="SELECT * FROM media where ownerid=$id AND ownertype='user' AND maintype='profpic'";
				$facerun2=mysql_query($facequery2)or die(mysql_error()." Line ".__LINE__);
				$facerow2=mysql_fetch_assoc($facerun2);
				$numrowface=mysql_num_rows($facerun2);
				$faceid=$facerow2['id'];
				if($numrowface>0){
					$originalimage=$host_addr.$facerow2['location'];
					$medimage=$host_addr.$facerow2['medsize'];
					$thumbimage=$host_addr.$facerow2['thumbnail'];
				}
				$nameout=$fullname;
			}
			$row['faceid']=$faceid;

			if ($row['usertype']=='client'||$row['usertype']=='serviceprovider') {
				# code...
				// get biz logo and banner
				$bizlogoquery2="SELECT * FROM media where ownerid=$id AND (ownertype='client' OR ownertype='serviceprovider') AND maintype='bizlogo'";
				$bizlogorun2=mysql_query($bizlogoquery2)or die(
					mysql_error()." Line ".__LINE__);
				
				$nameout=$row['businessname'];
				$bizlogorow2=mysql_fetch_assoc($bizlogorun2);
				$numrowbizlogo=mysql_num_rows($bizlogorun2);
				$row['bizlogoid']=$bizlogorow2['id'];
				$row['bizlogofile']=$bizlogorow2['location'];
				$bizlogoid=0;
				if($numrowbizlogo>0){
					$originalimage=$host_addr.$bizlogorow2['location'];
					$medimage=$host_addr.$bizlogorow2['medsize'];
					$thumbimage=$host_addr.$bizlogorow2['thumbnail'];
				}
				$bannerlogoquery2="SELECT * FROM media where ownerid=$id AND (ownertype='client' OR ownertype='serviceprovider') AND maintype='bannerlogo'";
				$bannerlogorun2=mysql_query($bannerlogoquery2)or die(
					mysql_error()." Line 27");
				$bannerlogorow2=mysql_fetch_assoc($bannerlogorun2);
				$numrowbannerlogo=mysql_num_rows($bannerlogorun2);
				$row['bannerlogoid']=$bannerlogorow2['id'];
				$row['bannerlogofile']=$bannerlogorow2['location'];
				$bannerlogoid=0;
				if($numrowbannerlogo>0){
					$banneroriginalimage=$host_addr.$bannerlogorow2['location'];
					$bannermedimage=$host_addr.$bannerlogorow2['medsize'];
					$bannerthumbimage=$host_addr.$bannerlogorow2['thumbnail'];
				}

				$bizprofilequery2="SELECT * FROM media where ownerid=$id AND (ownertype='client' OR ownertype='serviceprovider') AND maintype='orgprofile'";
				$bizprofilerun2=mysql_query($bizprofilequery2)or die(mysql_error()." Line ".__LINE__);
				$numrowsbizprofile=mysql_num_rows($bizprofilerun2);
				$bizprofileid=0;
				
				if($numrowsbizprofile>0){
					$bizprofilerow2=mysql_fetch_assoc($bizprofilerun2);
					$bizprofileid=$bizprofilerow2['id'];
					$poriginalimage=$host_addr.$bizprofilerow2['location'];
					$pmedimage=$host_addr.$bizprofilerow2['medsize'];
					$pthumbimage=$host_addr.$bizprofilerow2['thumbnail'];	
				}

				$bizcacquery2="SELECT * FROM media where ownerid=$id AND (ownertype='client' OR ownertype='serviceprovider') AND maintype='orgcac'";
				$bizcacrun2=mysql_query($bizcacquery2)or die(mysql_error()." Line ".__LINE__);
				$numrowsbizcac=mysql_num_rows($bizcacrun2);
				$bizcacid=0;
				
				if($numrowsbizcac>0){
					$bizcacrow2=mysql_fetch_assoc($bizcacrun2);
					$bizcacid=$bizcacrow2['id'];
					$cacoriginalimage=$host_addr.$bizcacrow2['location'];
					$cacmedimage=$host_addr.$bizcacrow2['medsize'];
					$cacthumbimage=$host_addr.$bizcacrow2['thumbnail'];	
				}

			}
			if ($row['usertype']=='appuser') {
				# code...
				// get profilepicture
				$facequery2="SELECT * FROM media where ownerid=$id AND ownertype='appuser' AND maintype='profpic'";
				$facerun2=mysql_query($facequery2)or die(mysql_error()." Line 27");
				$facerow2=mysql_fetch_assoc($facerun2);
				$numrowface=mysql_num_rows($facerun2);
				$row['faceid']=$facerow2['id'];
				if($numrowface>0){
					$originalimage=$host_addr.$facerow2['location'];
					$medimage=$host_addr.$facerow2['medsize'];
					$thumbimage=$host_addr.$facerow2['thumbnail'];
				}
				$nameout=$fullname;
			}

		}
		$row['numrows']=$numrows;
		$row['nameout']=$nameout;
		$row['originalimage']=$originalimage;
		$row['medimage']=$medimage;
		$row['thumbimage']=$thumbimage;
		$row['banneroriginalimage']=$banneroriginalimage;
		$row['bannermedimage']=$bannermedimage;
		$row['bannerthumbimage']=$bannerthumbimage;
		$row['bizprofileid']=$bizprofileid;
		$row['bizporiginalimage']=$poriginalimage;
		$row['bizpmedimage']=$pmedimage;
		$row['bizpthumbimage']=$pthumbimage;
		$row['bizcacid']=$bizcacid;
		$row['bizcacoriginalimage']=$cacoriginalimage;
		$row['bizcacmedimage']=$cacmedimage;
		$row['bizcacthumbimage']=$cacthumbimage;
		$trow=$row;
		unset($trow['pword']);
		unset($trow['email']);
		if(isset($trow['username'])){
			unset($trow['username']);
		}
		$catdata=$trow;
		// $rpword=array_shift($catdata['pword']);
		$row['catdata']=$catdata;
		return $row;
	}

	function getSingleUser($id){
		global $host_addr;
		$row=array();
		$query="SELECT * FROM users where id=$id";
		$run=mysql_query($query)or die(mysql_error()." Line  ".__LINE__);
		$numrows=mysql_num_rows($run);
		/*query2="SELECT * FROM surveys where userid=$typeid";
		$run2=mysql_query($query2)or die(mysql_error()." Line 899");
		$row2=mysql_fetch_assoc($run2);*/
		if($numrows>0){
			$row=mysql_fetch_assoc($run);
			$id=$row['id'];
			$fullname=$row['fullname'];
			$ndata=explode(" ",$fullname);
			$firstname=$ndata[0];
			$middlename=isset($ndata[1])?$ndata[1]:"";
			$lastname=isset($ndata[2])?$ndata[2]:"";
			$row['firstname']=$firstname;
			$row['middlename']=$middlename;
			$row['lastname']=$lastname;
			$nickname=isset($row['nickname'])?$row['nickname']:"";
			$details=isset($row['details'])?$row['details']:"";
			$catid=isset($row['catid'])?$row['catid']:0;
			if($catid>0){
				$catdata=getSingleContentCategory($catid);
				$catname=$catdata['catname'];
			}else{
				$catdata=array();
				$catname="";
			}
			$gender=$row['gender'];
			$maritalstatus=$row['maritalstatus'];
			$state=$row['state'];
			$sdata=briefquery("SELECT * FROM state WHERE id_no='$state'",__LINE__,"mysqli");
			$cdata=$sdata['resultdata'][0];
			$numrows=$sdata['numrows'];
			// if there are any results then set the state id to the current id
			$statename=$numrows>0?$cdata['state']:"";
			$row['statename']=$statename;
			$lga=$row['lga'];
			$lgdata=$lga!==""?getSingleLGA($lga):"";
			$row['lgdata']=$lgdata;
			$localgovt=isset($lgdata['local_govt'])?$lgdata['local_govt']:"";
			$lgaoptions='<option value="">Choose your Local Government Area</option>';
			$lgquery="SELECT local_govt,id_no FROM local_govt WHERE state_id = (select id_no from state where state='$state')";
			// echo $lgquery;
			$lgrun=mysql_query($lgquery)or die(mysql_error()." Line 50");
			$lgnumrows=mysql_num_rows($lgrun);
			if($lgnumrows>0){
				while($lgrow=mysql_fetch_assoc($lgrun)){
					$lgaoptions.='<option value="'.$lgrow['id_no'].'">'.$lgrow['local_govt'].'</option>';
				}					
			}
			$businessaddress=$row['businessaddress'];
			$password=$row['pword'];
			$email=$row['email'];
			$phonenumber=$row['phonenumber'];
			if($phonenumber!==""){
				$phonearr=explode("[|><|]", $phonenumber);
				if(count($phonearr)>1){
					$phoneone=strlen($phonearr[0])==11?substr($phonearr[0], 1,9):$phonearr[0];
					$phonetwo=isset($phonearr[1])&&strlen($phonearr[1])==11?substr($phonearr[1], 1,9):$phonearr[1];
					$phonethree=isset($phonearr[2])&&strlen($phonearr[2])==11?substr($phonearr[2], 1,9):$phonearr[2];
				}else{
					$phoneone=$phonenumber;$phonetwo="";$phonethree="";
				}
			}else{
				$phoneone="";$phonetwo="";$phonethree="";
			}
			$row['phoneone']=$phoneone;
			$row['phonetwo']=$phonetwo;
			$row['phonethree']=$phonethree;
			$dob=$row['dob'];
			$age="";
			if($dob!=="0000-00-00"){
				$dobdata=explode("-",$dob);
				$dobyear=$dobdata[0];
				$curyear=date("Y");
				$age=$dobyear>0?$curyear-$dobyear:0;
			}

			$row['age']=$age;
			$regdate=$row['regdate'];
			$dobchangedate=$row['dobchangedate'];
			$genderchangedate=$row['genderchangedate'];
			$maritalstatuschangedate=$row['maritalstatuschangedate'];
			$today=date("Y-m-d");
			$timenow=date("H:i:s");
			$statechangedate=$row['statechangedate'];
			$statedata=explode("-",$statechangedate);
			$td=date("d");
			$tm=date("m");
			$ty=date("Y");
			$sy=$statedata[0];
			$sm=$statedata[1];
			$sd=$statedata[2];
			$maritalchangedata=explode("-",$maritalstatuschangedate);
			$marcy=$maritalchangedata[0];
			$marcm=$maritalchangedata[1];
			$marcd=$maritalchangedata[2];
			$genderdata=explode("-",$genderchangedate);
			$gy=$genderdata[0];
			$gm=$genderdata[1];
			$gd=$genderdata[2];
			$dobdata=explode("-",$dobchangedate);
			$doby=$dobdata[0];
			$dobm=$dobdata[1];
			$dobd=$dobdata[2];
			$statedisabled="";
			$dobdisabled="";
			$maritalstatusdisabled="";
			$genderdisabled="";
			if($gy!=="0000"){
				$genderdisabled='disabled title="Sorry you have editted this once already, you can\'t go on with this change, if you need help you can contact the Napstand team for help"';
			}
			if($marcy>=$ty&&$tm<=$marcm){
				$maritalstatusdisabled='disabled title="Sorry you have editted this once the month change it next month"';
			}

			if($doby!=="0000"){
				$dobdisabled='disabled title="Sorry, but you are not allowed to perform anymore changes on this"';
			}

			if($sy>=$ty&&$sm<=$tm){
				$statedisabled='disabled title="Sorry you have editted this once the month change it next month"';
			}

			// get social data
  			//  link and handles data are in the form tw|fb|gp|ln|pin|tblr|ig
  			$sociallinksection="";
  			if(isset($row['socialhandles'])&&isset($row['socialurls'])){
	  			$totalhandles=explode("[|><|]",$row['socialhandles']);
	  			$totallinks=explode("[|><|]",$row['socialurls']);
	  			$row['cursocialhandles']=$totalhandles;
	  			$row['cursociallinks']=$totallinks;
	  			if(count($totalhandles)>0&&$row['socialhandles']!==""){
					// twitter
					$twhandle=$totalhandles[0];
					$twlink=$totallinks[0];
					// facebook
					$fbhandle=$totalhandles[1];
					$fblink=$totallinks[1];
					// gplus
					$gphandle=$totalhandles[2];
					$gplink=$totallinks[2];
					// Linkedin
					$inhandle=$totalhandles[3];
					$inlink=$totallinks[3];
					// Pinterest
					$pinhandle=$totalhandles[4];
					$pinlink=$totallinks[4];
					// tumblr
					$tblrhandle=$totalhandles[5];
					$tblrlink=$totallinks[5];			
					// instagram
					$ighandle=$totalhandles[6];
					$iglink=$totallinks[6];
					$sociallinksection='
						<div class="col-xs-12">
	            			<input type="hidden" name="socialcount" value="7">
	    					<div class="col-sm-4">
	          				  <div class="form-group">
			                    <label>Facebook:</label>
			                    <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-facebook"></i>
			                      </div>
			                      <input type="text" class="form-control" name="socialhandlefb" data-type="handle" data-pos="1" value="'.$totalhandles[1].'" Placeholder="Social handle, e.g Age Comics"/>
			                      <input type="text" class="form-control" name="socialhandlefblink" data-type="link" data-pos="1" value="'.$totallinks[1].'" Placeholder="Social link, e.g http://facebook.com/AgeComics"/>
			                    </div><!-- /.input group -->
			                  </div><!-- /.form group -->
		                    </div>
		                    <div class="col-sm-4">
	          				  <div class="form-group">
			                    <label>Twitter:</label>
			                    <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-twitter"></i>
			                      </div>
			                      <input type="text" class="form-control" name="socialhandletw" data-type="handle" data-pos="2" value="'.$totalhandles[0].'" Placeholder="Social handle, e.g @AgeComics"/>
			                      <input type="text" class="form-control" name="socialhandletwlink" data-type="link" data-pos="2" value="'.$totallinks[0].'" Placeholder="Social link, e.g http://twitter.com/AgeComics"/>
			                    </div><!-- /.input group -->
			                  </div><!-- /.form group -->
		                    </div>
		                    <div class="col-sm-4">
	          				  <div class="form-group">
			                    <label>Google Plus:</label>
			                    <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-google-plus"></i>
			                      </div>
			                      <input type="text" class="form-control" name="socialhandlegp" data-type="handle" data-pos="3" value="'.$totalhandles[2].'" Placeholder="Social handle, e.g AgeComics"/>
			                      <input type="text" class="form-control" name="socialhandlegplink" data-type="link" data-pos="3" value="'.$totallinks[2].'" Placeholder="Social link, e.g http://plus.google.com/thelink"/>
			                    </div><!-- /.input group -->
			                  </div><!-- /.form group -->
		                    </div>
		                    <div class="col-sm-4">
	          				  <div class="form-group">
			                    <label>LinkedIn:</label>
			                    <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-linkedin"></i>
			                      </div>
			                      <input type="text" class="form-control" name="socialhandlein" data-type="handle" data-pos="4" value="'.$totalhandles[3].'" Placeholder="Social handle, e.g AgeComics"/>
			                      <input type="text" class="form-control" name="socialhandleinlink" data-type="link" data-pos="4" value="'.$totallinks[3].'" Placeholder="Social link, e.g http://linkedin.com/thelink"/>
			                    </div><!-- /.input group -->
			                  </div><!-- /.form group -->
		                    </div>
		                    <div class="col-sm-4">
	          				  <div class="form-group">
			                    <label>Pinterest:</label>
			                    <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-pinterest"></i>
			                      </div>
			                      <input type="text" class="form-control" name="socialhandlepin" data-type="handle" data-pos="5" value="'.$totalhandles[4].'" Placeholder="Social handle, e.g @AgeComics"/>
			                      <input type="text" class="form-control" name="socialhandlepinlink" data-type="link" data-pos="5" value="'.$totallinks[4].'" Placeholder="Social link, e.g http://pinterest.com/AgeComics"/>
			                    </div><!-- /.input group -->
			                  </div><!-- /.form group -->
		                    </div>
		                    <div class="col-sm-4">
	          				  <div class="form-group">
			                    <label>Tumblr:</label>
			                    <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-tumblr"></i>
			                      </div>
			                      <input type="text" class="form-control" name="socialhandletblr" data-type="handle" data-pos="6" value="'.$totalhandles[5].'" Placeholder="Social handle, e.g @AgeComics"/>
			                      <input type="text" class="form-control" name="socialhandletblrlink" data-type="link" data-pos="6" value="'.$totallinks[5].'" Placeholder="Social link, e.g http://tumblr.com/AgeComics"/>
			                    </div><!-- /.input group -->
			                  </div><!-- /.form group -->
		                    </div>
		                    <div class="col-sm-4">
	          				  <div class="form-group">
			                    <label>Instagram:</label>
			                    <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-instagram"></i>
			                      </div>
			                      <input type="text" class="form-control" name="socialhandleig" data-type="handle" data-pos="7" value="'.$totalhandles[6].'" Placeholder="Social handle, e.g @AgeComics"/>
			                      <input type="text" class="form-control" name="socialhandleiglink" data-type="link" data-pos="7" value="'.$totallinks[6].'" Placeholder="Social link, e.g http://instagram.com/AgeComics"/>
			                    </div><!-- /.input group -->
			                  </div><!-- /.form group -->
		                    </div>
		                </div>
					';
	  			}
  			}

			

			/*edit block for irrelevant data objects that aren't currently 
			used in the module*/
				/*Date of birth*/
				/*
				<div class="col-xs-12">
	            	<div class="form-group">
	                    <label>Date Of Birth:</label>
	                    <div class="input-group">
	                      <div class="input-group-addon">
	                        <i class="fa fa-calendar"></i>
	                      </div>
	                      <input type="text" '.$dobdisabled.' name="dob" class="form-control" data-inputmask="\'alias\': \'yyyy-mm-dd\'" data-mask value="'.$dob.'"/>
	                    </div><!-- /.input group -->
	                </div><!-- /.form group -->
	            </div>
	            */
	            /*Marital status*/
	            /*
				<div class="col-xs-12">
	                <div class="form-group">
	                    <label>Marital Status:</label>
	                    <div class="input-group">
	                      <div class="input-group-addon">
	                        <i class="fa fa-venus-mars"></i>
	                      </div>
	                      <select id="maritalstatus" name="maritalstatus" '.$maritalstatusdisabled.' class="form-control">
	                    		<option value="">--Choose Status--</option>
	                    		<option value="single">Single</option>
	                    		<option value="married">Married</option>
	                    		<option value="others">Others</option>
	                      </select>
	                    </div><!-- /.input group -->
	                </div><!-- /.form group -->
	            </div>
	            */
			/*end*/
			$status=$row['status'];

			// get profilepicture
			$facequery2="SELECT * FROM media where ownerid=$id AND ownertype='user' AND maintype='profpic'";
			$facerun2=mysql_query($facequery2)or die(mysql_error()." Line ".__LINE__);
			$facerow2=mysql_fetch_assoc($facerun2);
			$numrowface=mysql_num_rows($facerun2);
			$row['faceid']=$facerow2['id'];
			if($numrowface>0){
				$face='<img src="'.$host_addr.''.$facerow2['thumbnail'].'">';
				$face2=''.$host_addr.''.$facerow2['thumbnail'].'';
			}else{
				$face2=''.$host_addr.'/images/default.gif';
				$face='<p style="text-align:center;"><i class="fa fa-user fa-3x"></i></p>';
				$row['faceid']=0;
			}
			$row['facefile']=$face2;
			$row['facefile2']=$facerow2['location'];

			$selectionscripts='
				<script>
					$(document).ready(function(){
						$("select[name=gender]").val("'.$gender.'");
						$("select[name=maritalstatus]").val("'.$maritalstatus.'");
						$("select[name=state]").val("'.$state.'");
						$("select[name=LocalGovt]").val("'.$lga.'");
						$("select[name=catid]").val("'.$catid.'");

						$("[data-mask]").inputmask();
						$(".timepicker").timepicker({
				          showInputs: true
				        });
					})
				</script>
			';
			$row['adminoutput']='
				<tr data-id="'.$id.'">
					<td class="tddisplayimg">'.$face.'</td>
					<td>'.$fullname.'</td><td>'.$catname.'</td><td>'.$state.'</td>
					<td>'.$localgovt.'</td><td>'.$phoneone.' '.$phonetwo.' '.$phonethree.'</td>
					<td>'.$gender.'</td><td>'.$regdate.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsingleuseradmin" data-divid="'.$id.'">Edit</a></td>
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
						<form action="'.$host_addr.'snippets/edit.php" name="edituser" method="post" enctype="multipart/form-data">
							<input type="hidden" name="entryvariant" value="edituser"/>
							<input type="hidden" name="entryid" value="'.$id.'"/>
							<div id="formheader">Edit <!--"'.$fullname.'"\'s--> Profile</div>
							<div class="row textcenter">
				            		<div class="miniwiddy">
					            		<img src="'.$face2.'" class="heightfull"/>
					            			<span class="btn btn-success fileinput-button absbottom" name="changeprofpic">
						                    <i class="fa fa-plus"></i>
						                    <span>Change Photo</span>
						                    <input type="file" name="profpic"  />
						                </span>
					            	</div>
				            	</div>
				            	<div class="col-md-12">

					            	<div class="col-md-6">
						            	<div class="box overflowhidden box-primary">
						            		<div class="box-header">
							            		<h4 class="box-title"><b>Personal Information</b></h4>
							            		<div class="box-tools pull-right"><i class="fa fa-user fa-2x"></i></div>
						            		</div>
						            		<div class="box-body">
						            			<div class="form-group">
								                    <div class="col-xs-4">
				                      				  <label>Firstname</label>
								                      <input type="text" class="form-control" name="firstname" placeholder="Firstname" value="'.$firstname.'"/>
								                    </div>
								                    <div class="col-xs-4">
				                      				  <label>Middlename</label>
								                      <input type="text" class="form-control" name="middlename"placeholder="Middlename" value="'.$middlename.'"/>
								                    </div>
								                    <div class="col-xs-4">
				                      				  <label>Lastname</label>
								                      <input type="text" class="form-control" name="lastname" placeholder="Lastname" value="'.$lastname.'"/>
								                    </div>
									                <div class="col-xs-6">
							                        	<div class="form-group">
									                      <label>Nickname</label>
									                      <input type="text" class="form-control" name="nickname" value="'.$nickname.'" placeholder="Nickname"/>
									                    </div>
									                </div>
						                            <div class="col-xs-6">
									                    <div class="form-group">
										                    <label>Gender:</label>
										                    <div class="input-group">
										                      <div class="input-group-addon">
										                        <i class="fa fa-transgender"></i>
										                      </div>
										                      <select id="gender" '.$genderdisabled.' name="gender" class="form-control">
								                            		<option value="">--Choose Sex--</option>
								                            		<option value="male">Male</option>
								                            		<option value="female">Female</option>
								                              </select>
										                    </div><!-- /.input group -->
									                    </div><!-- /.form group -->
								                    </div>
								                    <div class="col-xs-12">
							                        	<div class="form-group">
										                  <label>Bio</label>
										                  <textarea class="form-control" rows="3" name="bio" placeholder="Provide a bio for this profile, something witty and simple would do">'.$details.'</textarea>
										                </div>
										            </div>
								              	</div>
						                    </div>
					            		</div>
					            	</div>
					            	<div class="col-md-6">							
					            		<div class="box overflowhidden box-info">
						            		<div class="box-header">
							            		<h4 class="box-title"><b>Location Information</b></h4>
							            		<div class="box-tools pull-right"><i class="fa fa-map-marker fa-2x"></i></div>
						            		</div>
						            		<div class="box-body">
						            			<div class="form-group">
								                    <div class="col-xs-12">
								                      <label>State</label>
								                      <select name="state" id="state" '.$statedisabled.' class="form-control" onchange="showLocalGovt(this.value)">
															<option value="">Select your State</option>
															<option value="Abia">Abia</option>
															<option value="Adamawa">Adamawa</option>
															<option value="Akwa Ibom">Akwa Ibom</option>
															<option value="Anambra">Anambra</option>
															<option value="Bauchi">Bauchi</option>
															<option value="Bayelsa">Bayelsa</option>
															<option value="Benue">Benue</option>
															<option value="Borno">Borno</option>
															<option value="Cross River">Cross River</option>
															<option value="Delta">Delta</option>
															<option value="Ebonyi">Ebonyi</option>
															<option value="Edo">Edo</option>
															<option value="Ekiti">Ekiti</option>
															<option value="Enugu">Enugu</option>
															<option value="FCT">FCT</option>
															<option value="Gombe">Gombe</option>
															<option value="Imo">Imo</option>
															<option value="Jigawa">Jigawa</option>
															<option value="Kaduna">Kaduna</option>
															<option value="Kano">Kano</option>
															<option value="Kastina">Kastina</option>
															<option value="Kebbi">Kebbi</option>
															<option value="Kogi">Kogi</option>
															<option value="Kwara">Kwara</option>
															<option value="Lagos">Lagos</option>
															<option value="Nasarawa">Nasarawa</option>
															<option value="Niger">Niger</option>
															<option value="Ogun">Ogun</option>
															<option value="Ondo">Ondo</option>
															<option value="Osun">Osun</option>
															<option value="Oyo">Oyo</option>
															<option value="Plateau">Plateau</option>
															<option value="Rivers">Rivers</option>
															<option value="Sokoto">Sokoto</option>
															<option value="Taraba">Taraba</option>
															<option value="Yobe">Yobe</option>
															<option value="Zamfara">Zamfara</option>
													  </select>
								                    </div>
								                    <div class="col-xs-12">
								                      <label>LocalGovt</label>
								                      <select id="LocalGovt" name="LocalGovt" class="form-control">
					                            		'.$lgaoptions.'
					                            	  </select>
								                    </div>
								                    <div class="form-group">
									                  <label>Full Address</label>
									                  <textarea class="form-control" rows="3" name="address" placeholder="Provide an address">'.$businessaddress.'</textarea>
									                </div>
								                </div>
						                    </div>
					            		</div>
					            	</div>
				            	</div>
				            	<div class="col-md-12">
					            	<div class="col-md-6">							
					            		<div class="box overflowhidden box-primary">
						            		<div class="box-header">
							            		<h4 class="box-title"><b>Contact Information & Status</b></h4>
							            		<div class="box-tools pull-right"><i class="fa fa-telephone fa-2x"></i><i class="fa fa-envelope fa-2x"></i></div>
						            		</div>
						            		<div class="box-body">
						            			<div class="form-group">
						            				<div class="col-md-12">
									                    <div class="col-md-4">
					                      				  <div class="form-group">
										                    <label>Phone One:</label>
										                    <div class="input-group">
										                      <div class="input-group-addon">
										                        <i class="fa fa-phone"></i>
										                      </div>
										                      <input type="text" class="form-control" name="phoneone" value="'.$phoneone.'" data-inputmask=\'"mask": "(234) 999-999-9999"\' data-mask/>
										                    </div><!-- /.input group -->
										                  </div><!-- /.form group -->
									                    </div>
									                    <div class="col-md-4">
					                      				  <div class="form-group">
										                    <label>Phone Two:</label>
										                    <div class="input-group">
										                      <div class="input-group-addon">
										                        <i class="fa fa-phone"></i>
										                      </div>
										                      <input type="text" class="form-control" name="phonetwo" value="'.$phonetwo.'" data-inputmask=\'"mask": "(234) 999-999-9999"\' data-mask/>
										                    </div><!-- /.input group -->
										                  </div><!-- /.form group -->
									                    </div>
									                    <div class="col-md-4">
					                      				  <div class="form-group">
										                    <label>Phone Three:</label>
										                    <div class="input-group">
										                      <div class="input-group-addon">
										                        <i class="fa fa-phone"></i>
										                      </div>
										                      <input type="text" class="form-control" name="phonethree" value="'.$phonethree.'" data-inputmask=\'"mask": "(234) 999-999-9999"\' data-mask/>
										                    </div><!-- /.input group -->
										                  </div><!-- /.form group -->
									                    </div>
									                </div>
								                    <div class="col-xs-12">
								                    	<div class="form-group">
										                    <label>Email Address:</label>
										                    <div class="input-group">
										                      <div class="input-group-addon">
										                        <i class="fa fa-envelope-square"></i>
										                      </div>
										                      <input type="email" class="form-control" name="email" value="'.$email.'"/>
										                    </div><!-- /.input group -->
										                  </div><!-- /.form group -->
								                    </div>
								                    <div class="col-xs-12">
								                    	<div class="form-group">
										                    <label> Change Password:</label>
										                    <div class="input-group">
										                      <div class="input-group-addon">
										                        <i class="fa fa-key"></i>
										                      </div>
										                      <input type="password" placeholder="Previous Password" class="form-control" name="prevpassword"/>
										                      <input type="password" placeholder="New Password" class="form-control" name="password"/>
										                    </div><!-- /.input group -->
										                  </div><!-- /.form group -->
								                    </div>
								              	</div>
						                    </div>
					            		</div>
					            	</div>
					            	<div class="col-md-6">							

					            		<div class="panel box box-primary">
					                      <div class="box-header with-border">
					                        <h4 class="box-title">
					                          <a data-toggle="collapse" data-parent="#surveyaccordion" href="#socialBlock">
					                            <i class="fa fa-facebook-official"></i><i class="fa fa-twitter"></i>
					                            <i class="fa fa-linkedin"></i><i class="fa fa-pinterest"></i> 
					                            Social Information
					                          </a>
					                        </h4>
					                      </div>
					                      <div id="socialBlock" class="panel-collapse collapse in">
					                        <div class="box-body">
				                        		'.$sociallinksection.'
											</div>
					                      </div>
					                    </div>
					                </div>
				                </div>
								<div id="formend">
									<input type="submit" name="Update" value="Submit" class="submitbutton"/>
								</div>
						</form>
					</div>
	 				'.$selectionscripts.'
			';
			$row['adminoutputthree']='
					<div id="form" style="background-color:#fefefe;">
						<form action="../snippets/edit.php" name="edituseradmin" method="post" enctype="multipart/form-data">
							<input type="hidden" name="entryvariant" value="edituseradmin"/>
							<input type="hidden" name="entryid" value="'.$id.'"/>
							<div id="formheader">Edit "'.$fullname.'"</div>
							<div class="box-group" id="surveyaccordion">
		            			<div class="panel box box-primary">
			                      <div class="box-header with-border">
			                        <h4 class="box-title">
			                          <a data-toggle="collapse" data-parent="#surveyaccordion" href="#PersonalBlock">
			                            <i class="fa fa-user"></i>  Personal Information
			                          </a>
			                        </h4>
			                      </div>
			                      <div id="PersonalBlock" class="panel-collapse collapse in">
			                        <div class="box-body">
			                        	<div class="form-group">
				            				<div class="col-xs-12">
							                    <div class="col-xs-4">
			                      				  <label>Firstname</label>
							                      <input type="text" class="form-control" name="firstname" placeholder="Firstname" value="'.$firstname.'"/>
							                    </div>
							                    <div class="col-xs-4">
			                      				  <label>Middlename</label>
							                      <input type="text" class="form-control" name="middlename"placeholder="Middlename" value="'.$middlename.'"/>
							                    </div>
							                    <div class="col-xs-4">
			                      				  <label>Lastname</label>
							                      <input type="text" class="form-control" name="lastname" placeholder="Lastname" value="'.$lastname.'"/>
							                    </div>
							                    <div class="col-xs-12">
						                        	<div class="form-group">
								                      <label>Nickname</label>
								                      <input type="text" class="form-control" name="nickname" value="'.$nickname.'" placeholder="Nickname"/>
								                    </div>
								                </div>
							                </div>
						                    <div class="col-xs-6">
							                    <div class="form-group">
								                    <label>Profile Picture:</label>
								                    <div class="input-group">
								                      <div class="input-group-addon">
								                        <i class="fa fa-file-image-o"></i>
								                      </div>
								                      <input type="file" name="profpic" class="form-control" placeholder="Upload Photo"/>
								                    </div><!-- /.input group -->
							                    </div><!-- /.form group -->
						                    </div>
						                    <div class="col-xs-6">
							                    <div class="form-group">
								                    <label>Gender:</label>
								                    <div class="input-group">
								                      <div class="input-group-addon">
								                        <i class="fa fa-transgender"></i>
								                      </div>
								                      <select id="gender" name="gender" class="form-control">
						                            		<option value="">--Choose Sex--</option>
						                            		<option value="male">Male</option>
						                            		<option value="female">Female</option>
						                              </select>
								                    </div><!-- /.input group -->
							                    </div><!-- /.form group -->
						                    </div>

						                    <div class="col-xs-12">
					                        	<div class="form-group">
								                  <label>Bio</label>
								                  <textarea class="form-control" rows="3" name="bio" placeholder="Provide a bio for this profile, something witty and simple would do">'.$details.'</textarea>
								                </div>
								            </div>
						              	</div>
			                        </div>
			                      </div>
			                    </div>
			                    <div class="panel box box-info">
			                      <div class="box-header with-border">
			                        <h4 class="box-title">
			                          <a data-toggle="collapse" data-parent="#surveyaccordion" href="#UserLocationBlock">
			                            <i class="fa fa-map-marker"></i> Location Information
			                          </a>
			                        </h4>
			                      </div>
			                      <div id="UserLocationBlock" class="panel-collapse collapse">
			                        <div class="box-body">
			                        	<div class="form-group">
						                    <div class="col-xs-12">
						                      <label>State</label>
						                      <select name="state" id="state" class="form-control" onchange="showLocalGovt(this.value)">
													<option value="">Select your State</option>
													<option value="Abia">Abia</option>
													<option value="Adamawa">Adamawa</option>
													<option value="Akwa Ibom">Akwa Ibom</option>
													<option value="Anambra">Anambra</option>
													<option value="Bauchi">Bauchi</option>
													<option value="Bayelsa">Bayelsa</option>
													<option value="Benue">Benue</option>
													<option value="Borno">Borno</option>
													<option value="Cross River">Cross River</option>
													<option value="Delta">Delta</option>
													<option value="Ebonyi">Ebonyi</option>
													<option value="Edo">Edo</option>
													<option value="Ekiti">Ekiti</option>
													<option value="Enugu">Enugu</option>
													<option value="FCT">FCT</option>
													<option value="Gombe">Gombe</option>
													<option value="Imo">Imo</option>
													<option value="Jigawa">Jigawa</option>
													<option value="Kaduna">Kaduna</option>
													<option value="Kano">Kano</option>
													<option value="Kastina">Kastina</option>
													<option value="Kebbi">Kebbi</option>
													<option value="Kogi">Kogi</option>
													<option value="Kwara">Kwara</option>
													<option value="Lagos">Lagos</option>
													<option value="Nasarawa">Nasarawa</option>
													<option value="Niger">Niger</option>
													<option value="Ogun">Ogun</option>
													<option value="Ondo">Ondo</option>
													<option value="Osun">Osun</option>
													<option value="Oyo">Oyo</option>
													<option value="Plateau">Plateau</option>
													<option value="Rivers">Rivers</option>
													<option value="Sokoto">Sokoto</option>
													<option value="Taraba">Taraba</option>
													<option value="Yobe">Yobe</option>
													<option value="Zamfara">Zamfara</option>
											  </select>
						                    </div>
						                    <div class="col-xs-12">
						                      <label>LocalGovt</label>
						                      <select id="LocalGovt" name="LocalGovt" class="form-control">
			                            		'.$lgaoptions.'
			                            	  </select>
						                    </div>
						                    <div class="form-group">
							                  <label>Full Address</label>
							                  <textarea class="form-control" rows="3" name="address" placeholder="Provide an address">'.$businessaddress.'</textarea>
							                </div>
						                </div>
			                        </div>
			                      </div>
			                    </div>
			                    <div class="panel box box-primary">
			                      <div class="box-header with-border">
			                        <h4 class="box-title">
			                          <a data-toggle="collapse" data-parent="#surveyaccordion" href="#usercontactBlock">
			                            <i class="fa fa-telephone"></i><i class="fa fa-envelope"></i> Contact Information
			                          </a>
			                        </h4>
			                      </div>
			                      <div id="usercontactBlock" class="panel-collapse collapse">
			                        <div class="box-body">
			                        	<div class="form-group">
				            				<div class="col-xs-12">
							                    <div class="col-xs-4">
			                      				  <div class="form-group">
								                    <label>Phone One:</label>
								                    <div class="input-group">
								                      <div class="input-group-addon">
								                        <i class="fa fa-phone"></i>
								                      </div>
								                      <input type="text" class="form-control" name="phoneone"  data-inputmask=\'"mask": "(234) 999-999-9999"\' data-mask value="'.$phoneone.'"/>
								                    </div><!-- /.input group -->
								                  </div><!-- /.form group -->
							                    </div>
							                    <div class="col-xs-4">
			                      				  <div class="form-group">
								                    <label>Phone Two:</label>
								                    <div class="input-group">
								                      <div class="input-group-addon">
								                        <i class="fa fa-phone"></i>
								                      </div>
								                      <input type="text" class="form-control" name="phonetwo"  data-inputmask=\'"mask": "(234) 999-999-9999"\' data-mask value="'.$phonetwo.'"/>
								                    </div><!-- /.input group -->
								                  </div><!-- /.form group -->
							                    </div>
							                    <div class="col-xs-4">
			                      				  <div class="form-group">
								                    <label>Phone Three:</label>
								                    <div class="input-group">
								                      <div class="input-group-addon">
								                        <i class="fa fa-phone"></i>
								                      </div>
								                      <input type="text" class="form-control" name="phonethree" data-inputmask=\'"mask": "(234) 999-999-9999"\' data-mask value="'.$phonethree.'"/>
								                    </div><!-- /.input group -->
								                  </div><!-- /.form group -->
							                    </div>
							                </div>
						                    <div class="col-xs-12">
						                    	<div class="form-group">
								                    <label>Email Address:</label>
								                    <div class="input-group">
								                      <div class="input-group-addon">
								                        <i class="fa fa-envelope-square"></i>
								                      </div>
								                      <input type="email" class="form-control" name="email" value="'.$email.'"/>
								                    </div><!-- /.input group -->
								                  </div><!-- /.form group -->
						                    </div>  
							            </div>
			                        </div>
			                      </div>
			                    </div>
			                    <div class="panel box box-primary">
			                      <div class="box-header with-border">
			                        <h4 class="box-title">
			                          <a data-toggle="collapse" data-parent="#surveyaccordion" href="#useraccessBlock">
			                            <i class="fa fa-key"></i><i class="fa fa-lock"></i> Login Information & Status
			                          </a>
			                        </h4>
			                      </div>
			                      <div id="useraccessBlock" class="panel-collapse collapse">
			                        <div class="box-body">
			                        	<div class="col-xs-12">
					                    	<div class="form-group">
							                    <label> Change Password:(<b>'.$password.'</b>)</label>
							                    <div class="input-group">
							                      <div class="input-group-addon">
							                        <i class="fa fa-key"></i>
							                      </div>
							                      <input type="hidden" placeholder="Previous Password" class="form-control" name="prevpassword" value="'.$password.'"/>
							                      <input type="password" placeholder="New Password" class="form-control" name="password"/>
							                    </div><!-- /.input group -->
							                  </div><!-- /.form group -->
					                    </div>
					                    <div class="col-xs-12">
					                    	<label>Status</label>
					                    	<select id="status" name="status" class="form-control">
			                            		<option value="">Change Status</option>
			                            		<option value="active">Active</option>
			                            		<option value="inactive">Inactive</option>
			                            	</select>
					                    </div>
			                        </div>
			                      </div>
			                    </div>
			                    <div class="panel box box-primary">
			                      <div class="box-header with-border">
			                        <h4 class="box-title">
			                          <a data-toggle="collapse" data-parent="#surveyaccordion" href="#socialBlock">
			                            <i class="fa fa-facebook-official"></i> <i class="fa fa-twitter"></i>
			                            <i class="fa fa-linkedin"></i> <i class="fa fa-pinterest"></i> 
			                            Social Information
			                          </a>
			                        </h4>
			                      </div>
			                      <div id="socialBlock" class="panel-collapse collapse">
			                        <div class="box-body">
		                        		'.$sociallinksection.'
									</div>
			                      </div>
			                    </div>
			            	</div>
							<div class="col-md-12">
		            			<div class="box-footer">
				                    <input type="submit" class="btn btn-danger" name="updateuseradmin" value="Update Data"/>
				                    <div class="col-sm-3 ajax-msg-holder pull-right">
				                    	<img src="<?php echo $host_addr;?>images/loading.gif" class="loadermini hidden"/>
				                    	<div class="ajax-msg-box hidden">
				                    		<!-- Checking email data -->
				                    	</div>
				                    </div>
				                </div>
			            	</div>
						</form>
					</div>
					'.$selectionscripts.'
			';
			$row['adminoutputappuser']='
				<tr data-id="'.$id.'">
					<td class="tddisplayimg">'.$face.'</td><td>'.$fullname.'</td><td>'.$email.'</td><td>'.$regdate.'</td><td>'.$status.'</td>
					<td name="trcontrolpoint">
						<a href="#&id='.$id.'" name="edit" data-type="editsingleappuseradmin" data-divid="'.$id.'">Edit</a>
					</td>
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
			$row['adminoutputtwoappuser']='
				<form name="editappuserform" action="'.$host_addr.'snippets/edit.php" method="POST" enctype="multipart/form-data">
	        		<input name="entryvariant" type="hidden" value="editappuser"/>
	        		<input name="entryid" type="hidden" value="'.$id.'"/>
	        		<input name="entrypoint" type="hidden" value="webapp"/>
	        		<div class="box-group" id="contentaccordion">
	        			<div class="panel box box-primary">
	                      <div class="box-header with-border">
	                        <h4 class="box-title">
	                          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock">
	                            <i class="fa fa-"></i>  Edit '.$fullname.' App user account
	                          </a>
	                        </h4>
	                      </div>
	                      <div id="headBlock" class="panel-collapse collapse in">
		                        <div class="box-body">
				                    <div class="col-md-4">
			                        	<div class="form-group">
					                      <label>First Name</label>
					                      <div class="input-group">
						                      <div class="input-group-addon">
						                        <i class="fa fa-file-text"></i>
						                      </div>
					                      	  <input type="text" class="form-control" name="firstname" placeholder="First Name" value="'.$firstname.'"/>
					                      </div>
					                    </div>
					                </div>
					                <div class="col-md-4">
			                        	<div class="form-group">
					                      <label>Middle Name</label>
					                      <div class="input-group">
						                      <div class="input-group-addon">
						                        <i class="fa fa-file-text"></i>
						                      </div>
					                      	  <input type="text" class="form-control" name="middlename" placeholder="Middle Name" value="'.$middlename.'"/>
					                      </div>
					                    </div>
					                </div><div class="col-md-4">
			                        	<div class="form-group">
					                      <label>Last Name</label>
					                      <div class="input-group">
						                      <div class="input-group-addon">
						                        <i class="fa fa-file-text"></i>
						                      </div>
					                      	  <input type="text" class="form-control" name="lastname" placeholder="Last Name" value="'.$lastname.'"/>
					                      </div>
					                    </div>
					                </div>
					                <div class="col-md-6">
			                        	<div class="form-group">
					                      <label>Email Address</label>
					                      <div class="input-group">
						                      <div class="input-group-addon">
						                        <i class="fa fa-at"></i>
						                      </div>
					                      	  <input type="email" class="form-control" name="email" placeholder="User email address" value="'.$email.'"/>
					                      </div>
					                    </div>
					                </div>
					                <div class="col-md-6">
			                        	<div class="form-group">
					                      <label>Password(<b>'.$password.'</b>)</label>
					                      <div class="input-group">
						                      <div class="input-group-addon">
						                        <i class="fa fa-lock"></i>
						                      </div>
					                      	  <input type="password" class="form-control" name="pword" placeholder="The user Password here" value="'.$password.'"/>
					                      </div>
					                    </div>
					                </div>
					                <div class="col-xs-12">
					                    	<label>Status</label>
					                    	<select id="status" name="status" class="form-control">
			                            		<option value="">Change Status</option>
			                            		<option value="active">Active</option>
			                            		<option value="inactive">Inactive</option>
			                            	</select>
					                    </div>
			                        <div class="col-md-12">
					        			<div class="box-footer">
	        								<input name="prevpassword" type="hidden" value="'.$password.'"/>
						                    <input type="submit" class="btn btn-danger" name="editappuser" value="Update"/>
						                </div>
					            	</div>
		                        </div>
	                      </div>
	                    </div>
	        		</div>
	        		
			    </form>
			';
		}
		
		return $row;
	}
	
	function getAllUsers($viewer,$limit,$user='',$rodata="adminoutputtwo",$type=""){
		include('globalsmodule.php');
		$row=array();

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
		$user==""?$user="user":$user=$user;
		$queryorder="WHERE usertype='$user'";
		$orderout=" order by fullname,id,regdate desc";
		$outputtype="user";
		$user==""?$outputtype="user":$outputtype=$user;
		$realoutputdata=$rodata;
		$row=array();
		// variable for capturing the total content data of the type argument
		// when it is an array
		$mtype=array();
		// $realoutputdata="";
		if($type!==""&&is_array($type)){
			$mtype=$type;
			$type=$mtype[0];
			$typeval=$mtype[1];
			$realoutputdata.="|$type][$typeval";
		}else{
			$realoutputdata.="|$type";
		}
		// $realoutputdata.="|$rodata";
		$orderout="order by id desc";
		$qout="";
		$qcat="AND";
		if($type=="state"){
			$queryorder=$typeval==""?"ORDER BY state,id DESC":"WHERE state='$typeval' ORDER BY id DESC";
		}else if($type=="gender"){
			$queryorder="WHERE gender='$typeval' ";
		}else if($type=="day"){
			$orderout="ORDER BY id DESC";
			$qout.=" $qcat regdate='$typeval' ";

		}else if($type=="businessnature"){
			$orderout="ORDER BY businessname";
			$qout.=" $qcat businessnature='$typeval' AND activationstatus='active'";

		}else if($type=="activationstatus"){
			$orderout="ORDER BY id DESC";
			$qout.=" $qcat activationstatus='$typeval'";

		}else if($type=="daterange"){
			$cqvd=explode("-*-", $typeval);
			$d1=$cqvd[0];
			$d2=$cqvd[1];
			if($d1>$d2){
				$dd=$d2;
				$d2=$d1;
				$d1=$dd;
			}
			$qout.=" $qcat regdate>=$d1 AND regdate<=$d2";
			$orderout="ORDER BY id DESC";
		}else if($type=="combo"){
			// break the typeval variable into properties and values set
			// delimiter between properties and values are :
			// delimiter between each property set **
			$propvaldata=explode(":", $typeval);
			$propdata=explode("**", $propvaldata[0]);
			$valdata=explode("**", $propvaldata[1]);
			$orderout="";
			// $queryorder="";
			// $qcat="AND";
			for($i=0;$i<count($propdata);$i++){
				// current querytype and queryvalue
				$cqtype=$propdata[$i];
				$cqval=$valdata[$i];
				if($qout!==""){
					$qcat="AND";
				}
				if($cqtype=="state"&&$cqval!==""){
					$orderout="ORDER BY state,id";
					$qout.=" $qcat state='$cqval' ";
				}
				if($cqtype=="activationstatus"&&$cqval!==""){
					$orderout="ORDER BY state,id";
					$qout.=" $qcat usertype='serviceprovider' AND activationstatus='active' ";
				}
				if($cqtype=="gender"&&$cqval!==""){
					// $orderout="ORDER BY id DESC";
					$qout.=" $qcat gender='$cqval'";
				}
				if($cqtype=="day"&&$cqval!==""){
					// $orderout="ORDER BY id DESC";
					$qout.=" $qcat regdate='$cqval' ";

				}

				if($cqtype=="businessnature"&&$cqval!==""){
					$orderout="ORDER BY businessname";
					$qout.=" $qcat businessnature='$cqval' AND activationstatus='active'";

				}
				
				if($cqtype=="daterange"&&$cqval!==""){
					$cqvd=explode("-*-", $cqval);
					$d1=$cqvd[0];
					$d2=$cqvd[1];
					$td1=new DateTime($d1);
					$td2=new DateTime($d2);
					if($td1>$td2){
						$dd=$d2;
						$d2=$d1;
						$d1=$dd;
					}
					$qout.=" $qcat regdate>=$d1 AND regdate<=$d2";
					$orderout="ORDER BY id DESC";
				}
			}
		}
		$queryorder.=$qout;
		// $realoutputdauta=$rodata;
		if($queryorder==""&&$viewer=="viewer"){
			$queryorder="WHERE";
		}
		if($queryorder!==""&&$viewer=="viewer"){
			$queryorder.=" AND ";
		}

		if($viewer=="admin"){
			$query="SELECT * FROM users $queryorder $orderout ".$limit."";
			$rowmonitor['chiefquery']="SELECT * FROM users $queryorder $orderout";
		}else if($viewer=="viewer"){
			$query="SELECT * FROM users $queryorder status='active' $orderout  ".$limit."";
			$rowmonitor['chiefquery']="SELECT * FROM users  $queryorder status='active' $orderout";
		}else if($viewer=="inactiveviewer"){
			$query="SELECT * FROM users WHERE usertype='$user' AND status='inactive'";		
			$rowmonitor['chiefquery']="SELECT * FROM users WHERE usertype='$user' AND status='inactive'";
		}else if(is_array($viewer)){
			$subtype=$viewer[0];
			$searchval=$viewer[1];
			$viewer=$viewer[2];
 		  	$outputtype="usersearch|$subtype|$searchval|$viewer|$outputtype";
			if($subtype=="username"&&$viewer=="admin"){
				$query="SELECT * FROM users WHERE (fullname LIKE '%$searchval%' AND usertype='$user' AND status='active') OR (fullname LIKE '%$searchval%' AND usertype='$user' AND status='inactive') $limit";
		    	$rowmonitor['chiefquery']="SELECT * FROM users WHERE (fullname LIKE '%$searchval%' AND usertype='$user' AND status='active') OR (fullname LIKE '%$searchval%' AND usertype='$user' AND status='inactive')";
			}elseif($subtype=="username"&&$viewer=="viewer"){
				$query="SELECT * FROM users WHERE fullname LIKE '%$searchval%' AND usertype='$user' AND status='active' ORDER BY fullname" ;
		    	$rowmonitor['chiefquery']="SELECT * FROM users WHERE fullname LIKE '%$searchval%' AND usertype='$user' AND status='active' ORDER BY fullname";
			}elseif($subtype=="userstatus"&&$viewer=="admin"){
				$query="SELECT * FROM users WHERE status ='$searchval' AND usertype='$user' $limit";
		    	$rowmonitor['chiefquery']="SELECT * FROM users WHERE status ='$searchval' AND usertype='$user'";
			}elseif($subtype=="useremail"&&$viewer=="admin"){
				$query="SELECT * FROM users WHERE email ='$searchval' AND usertype='$user' $limit";
		    	$rowmonitor['chiefquery']="SELECT * FROM users WHERE email ='$searchval' AND usertype='$user'";
			}elseif($subtype=="advancedusersearch"&&$viewer=="admin"){
				$query= $searchval." ".$limit;
		    	$rowmonitor['chiefquery']=$searchval;
			}else{
				echo "search parameters unrecognized, contact your developer";
			}
		}
		// echo $query;
		$selection="";
		$selectiontwo="";
		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
		$numrows=mysql_num_rows($run);

		$top='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>Photo</th><th>FullName</th><th>Content Category</th><th>State</th><th>LGA</th><th>PhoneNumbers</th><th>Gender</th><th>Reg Date</th><th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		// basic user content
		$toptwo='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>FullName</th><th>Gender</th><th>Age</th><th>Email</th><th>PhoneNumber</th><th>Reg Date</th><th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		// basic client
		$topthree='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>Organisation Name</th><th>Profile</th><th>CAC</th>
					<th>Business Nature</th><th>Secondary Service</th><th>Contact Fullname</th>
					<th>Contact Email</th><th>Contact Phone</th><th>Act. Status</th>
					<th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		$bottom='	</tbody>
				</table>';
		$adminoutput="";
		$adminoutput2="";
		$adminoutput3="";
		$adminoutputappuser="";
		$monitorpoint="";
		$catdata=array();
		if($numrows>0){
			while($row=mysql_fetch_assoc($run)){
				$outvar=getSingleUser($row['id']);
				// for plain user acc
				$outvartwo=getSingleUserPlain($row['id']);

				// get client profile and cac reg
				$clientprofile='<a href="##">none</a>';
				$clientcac='<a href="##">none</a>';
				if($outvartwo['bizpmedimage']!==""){
					$ft=getExtensionAdvanced($outvartwo['bizporiginalimage']);
					if($ft['type']=="image"){
						$clientprofile='<a href="'.$outvartwo['bizporiginalimage'].'" data-lightbox="default_gallery_'.$row['id'].'" data-src="'.$outvartwo['bizporiginalimage'].'" data-title="<h4 class=\'galimgdetailshigh\'>'.$outvartwo['businessname'].' Profile</h4>: Right click the image and click \'Save as\' to download.">
				                            <img src="'.$outvartwo['bizpthumbimage'].'" alt="img">
										</a>';
						
					}else{
						$downloadurl=$host_addr.'/snippets/display.php?displaytype=gd_download&datapath='.str_replace($host_addr,".",$outvartwo['bizporiginalimage']).'';
						$clientprofile='<a href="##profile_download" 
										title="Click to download" data-gddownload="true"
										data-gd-url="'.$downloadurl.'"
										>
				                            Download Profile ('.$ft['ext'].');
										</a>';
					}
				}else if($outvartwo['websiteurl']!==""){
					$clientprofile='<a href="'.$outvartwo['websiteurltwo'].'" >
				                            View Profile (Web Address);
										</a>';
				}
				if($outvartwo['bizcacmedimage']!==""){
					$ft=getExtensionAdvanced($outvartwo['bizcacoriginalimage']);
					if($ft['type']=="image"){
					
						$clientcac='<a href="'.$outvartwo['bizcacoriginalimage'].'" data-lightbox="default_gallery_'.$row['id'].'" data-src="'.$outvartwo['bizcacoriginalimage'].'" data-title="<h4 class=\'galimgdetailshigh\'>'.$outvartwo['businessname'].' CAC</h4>: Right click the image and click \'Save as\' to download.">
		                            <img src="'.$outvartwo['bizcacthumbimage'].'" alt="img">
								</a>';
					}else{
						$downloadurl=$host_addr.'/snippets/display.php?displaytype='
						.'gd_download&datapath='.
						str_replace($host_addr,".",$outvartwo['bizcacoriginalimage']).'';
						$clientcac='<a href="##profile_download" 
										title="Click to download" data-gddownload="true"
										data-gd-url="'.$downloadurl.'">
				                            Download Profile ('.$ft['ext'].');
										</a>';
					}
				}

				// $row['resultdataset'][]=$outvartwo['catdata'];
				$catdata[]=$outvartwo['catdata'];
				$adminoutput3.='<tr data-id="'.$outvartwo['id'].'">
									<td>'.$outvartwo['businessname'].'</td>
									<td class="tdimg">'.$clientprofile.'</td>
									<td class="tdimg">'.$clientcac.'</td>
									<td>'.$outvartwo['businessnature'].'</td>
									<td>'.$outvartwo['businessnature2'].'</td>
									<td>'.$outvartwo['fullname'].'</td>
									<td>'.$outvartwo['contactemail'].'</td>
									<td>'.$outvartwo['contactphone'].'</td>
									<td>'.$outvartwo['activationstatus'].'</td>
									<td>'.$outvartwo['status'].'</td>
									<td name="trcontrolpoint">
										<a href="#&id='.$outvartwo['id'].'" name="edit" data-type="editsingleclientaccadmin" data-oname="View" data-divid="'.$outvartwo['id'].'">View</a>
									</td>
								</tr>
								<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$outvartwo['id'].'">
									<td colspan="100%">
										<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$outvartwo['id'].'">
											<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$outvartwo['id'].'">
												
											</div>
										</div>
									</td>
								</tr>';
				// for business acc
				$adminoutput2.='<tr data-id="'.$outvartwo['id'].'">
									<td>'.$outvartwo['nameout'].'</td><td>'.$outvartwo['gender'].'</td><td>'.$outvartwo['age'].'</td><td>'.$outvartwo['email'].'</td><td>'.$outvartwo['phonenumber'].'</td><td>'.$outvartwo['regdate'].'</td><td>'.$outvartwo['status'].'</td>
									<td name="trcontrolpoint">
										<a href="#&id='.$outvartwo['id'].'" data-oname="View" 
										name="edit" data-type="editsingleuseraccadmin" 
										data-divid="'.$outvartwo['id'].'">View</a>
									</td>
								</tr>
								<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$outvartwo['id'].'">
									<td colspan="100%">
										<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$outvartwo['id'].'">
											<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$outvartwo['id'].'">
												
											</div>
										</div>
									</td>
								</tr>';

				$adminoutput.=$outvar['adminoutput'];
				$adminoutputappuser.=$outvar['adminoutputappuser'];
				$selection.='<option value="'.$outvar['id'].'">'.$outvar['fullname'].'</option>';
				$selectiontwo.='<option value="'.$outvar['id'].'">'.$outvar['businessname'].'</option>';
			}
		}
		$outs=paginatejavascript($rowmonitor['chiefquery']);
		$row['num_pages']=$outs['num_pages'];
		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.str_replace("*", "_asterisk_", $rowmonitor['chiefquery']).'"/>
				<input type="hidden" name="outputtype" value="'.$outputtype.'|users|'.$realoutputdata.'"/>
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
		$row['adminoutputappuser']=$paginatetop.$toptwo.$adminoutputappuser.$bottom.$paginatebottom;
		$row['adminoutputtwoappuser']=$toptwo.$adminoutputappuser.$bottom;
		// basic user display
		$row['adminoutput2']=$paginatetop.$toptwo.$adminoutput2.$bottom.$paginatebottom;
		$row['adminoutputtwo2']=$toptwo.$adminoutput2.$bottom;

		$row['numrows']=$numrows;
		// basic organisation  details
		$row['adminoutput3']=$paginatetop.$topthree.$adminoutput3.$bottom.$paginatebottom;
		$row['adminoutputtwo3']=$topthree.$adminoutput3.$bottom;
		//define the nature of the current output;
		$row['outputtype']=$realoutputdata;
		$row['selection']=$selection;
		$row['selectiontwo']=$selectiontwo;
		$uarun=mysql_query($rowmonitor['chiefquery']);
		$numrowsactive=mysql_num_rows($uarun);
		$row['numrowsactive']=$numrowsactive;
		$row['catdata']=$catdata;
		$row['resultdataset']=$catdata;
		return $row;
	}


	function getUserGroup($viewer,$limit){
		$row=array();
		str_replace("-", "", $limit);

		$testit=strpos($limit,"-");

		$testit?$limit="":$limit=$limit;
		$testittwo=strpos($limit,",");
		if($testittwo===0||$testittwo===true||$testittwo>0){
			$limit=$limit;
		}else{
			if(strtolower($limit)=="all"){
				$limit="";
			}else{
				$limit="LIMIT 0,15";				
			}
		}
		if($viewer=="admin"){
			$query="SELECT * FROM users WHERE usertype='user' order by fullname,id desc ".$limit."";
			$rowmonitor['chiefquery']="SELECT * FROM users WHERE usertype='user' order by fullname,id desc";
		}elseif($viewer=="viewer"){
			$query="SELECT * FROM users WHERE usertype='user' AND status='active' $limit";
			$rowmonitor['chiefquery']="SELECT * FROM users WHERE usertype='user' AND status='active' order by fullname,id desc";
		}else if($viewer=="inactiveviewer"){
			$query="SELECT * FROM users WHERE usertype='user' AND status='inactive'";		
			$rowmonitor['chiefquery']="SELECT * FROM users WHERE usertype='user' AND status='inactive'order by fullname,id desc";
		}else if(is_array($viewer)){
			$prevval=$viewer;
			$subtype=$viewer[0];
			$searchval=mysql_real_escape_string($viewer[1]);
			$viewer=$viewer[2];
 		  	$outputtype="usersearch|$subtype|$searchval|$viewer";
			if($subtype=="username"&&$viewer=="admin"){
				$query="SELECT * FROM users WHERE (fullname LIKE '%$searchval%' AND usertype='user' AND status='active') OR (fullname LIKE '%$searchval%' AND usertype='user' AND status='inactive')  AND usertype='user' ORDER BY firstname,middlename,lastname,id $limit";
		    	$rowmonitor['chiefquery']="SELECT * FROM users WHERE (fullname LIKE '%$searchval%' AND usertype='user' AND status='active') OR (fullname LIKE '%$searchval%' AND usertype='user' AND status='inactive') AND usertype='user' ORDER BY fullname, id";
			}else if($subtype=="usercategorysearch"&&$viewer=="admin"){
				$catid=$prevval[3];
				$query="SELECT * FROM users WHERE catid ='$catid' AND usertype='user' AND fullname LIKE '%$searchval%' ORDER BY fullname,id $limit";
		    	$rowmonitor['chiefquery']="SELECT * FROM users WHERE catid ='$catid' AND usertype='user' AND fullname LIKE '%$searchval%' ORDER BY fullname,id";
			}elseif($subtype=="userstatus"&&$viewer=="admin"){
				$query="SELECT * FROM users WHERE status ='$searchval' AND usertype='user' $limit";
		    	$rowmonitor['chiefquery']="SELECT * FROM users WHERE status ='$searchval' AND usertype='user'";
			}elseif($subtype=="advancedusersearch"&&$viewer=="admin"){
				$query= $searchval." ".$limit;
		    	$rowmonitor['chiefquery']=$searchval;
			}else if($subtype=="userslist"&&$viewer=="admin"){
				$query="SELECT * FROM users WHERE usertype='user' ORDER BY fullname $limit";
		    	$rowmonitor['chiefquery']="SELECT * FROM users WHERE catid ='$searchval' AND usertype='user' ORDER BY fullname";
 		  		$outputtype="userslist|$subtype|$searchval|$viewer";
			}else if($subtype=="usercatlist"&&$viewer=="admin"){
				$catid=$prevval[3];
				$query="SELECT * FROM users WHERE catid =$catid AND usertype='user' ORDER BY fullname $limit";
		    	$rowmonitor['chiefquery']="SELECT * FROM users WHERE catid ='$catid' AND usertype='user' ORDER BY fullname";
 		  		$outputtype="usercatlist|$subtype|$searchval|$viewer";
			}else{
				echo "search parameters unrecognized, contact your developer";
			}
		}
		// echo $viewer."<br>";
		// echo $query;
		$selection='<option value="">Select a User</option>';
		$minisearch="";
		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
		$numrows=mysql_num_rows($run);
		// generate full user list
		if($numrows>0){
			$rowquery=mysql_query($rowmonitor['chiefquery']);
			while($fullrows=mysql_fetch_array($rowquery)){
				$selection.='<option value="'.$fullrows['id'].'">'.$fullrows['fullname'].'</option>';
				$minisearch.='<span class="username_display"><a href="##" data-id="'.$fullrows['id'].'">'.$fullrows['fullname'].'</a></span>';
			}
		}
		$row['selection']=$selection;
		$row['minisearch']=$minisearch;
		$row['numrows']=$numrows;
		return $row;
	}
?>