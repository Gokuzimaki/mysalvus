<?php
	function getSingleStoreAudio($id){
		global $host_addr,$host_target_addr;
		$row=array();
		$query="SELECT * FROM onlinestoreentries WHERE id=$id";
		$run=mysql_query($query)or die(mysql_error()." Line 3085");
		$row=mysql_fetch_assoc($run);
		$id=$row['id'];
		$typeid=$row['typeid'];
		$storequery="SELECT * FROM onlinestores WHERE id=$typeid";
		$storerun=mysql_query($storequery)or die(mysql_error()." ".__LINE__);
		$storerow=mysql_fetch_assoc($storerun);
		$storename=$storerow['title'];
		$row['storename']=$storename;
		$storestyle=$storerow['style'];
		$vstoreid=$storerow['vstoreid'];
		$buttontype="make_payment_red";
			$defcoverimg="./images/muyiwalogo6.png";
			$merchant_id="6345-0028079";
		if($typeid==1){
			$buttontype="make_payment_red";
			$defcoverimg="./images/muyiwalogo6.png";
			$merchant_id="6345-0028079";
		}
		if($typeid==2){
			$buttontype="make_payment_blue";
			$defcoverimg="./images/csi.png";
			$merchant_id="2528-0026209";
		}
		if($typeid==3){
			$buttontype="make_payment_red";
			$defcoverimg="./images/muyiwalogo6.png";
			$merchant_id="6345-0028079";
		}	
		$title=$row['title'];
		$minititle=$row['minititle'];
		$minititle!==""?$minititleout='<span class="minititlehold">'.$minititle.'</span>':$minititleout='<span class="minititlehold">Store Entry</span>';
		$minidescription=$row['minidescription'];
		$price=$row['price'];
		$aid=$row['aid'];
		$coverid=$row['coverid'];
		$prevstart=$row['starttime'];
		$prevstop=$row['stoptime'];
		$status=$row['status'];
		$defrate=200;
		require_once 'currencyrateimport.php';
		$cur= new Yahoofinance;
		$curpoint=$cur->_convert("USD","NGN");
		if($curpoint!=="Cannot retrieve rate from Yahoofinance"){
			$defrate=$curpoint;
		}
		$purchasequery2="SELECT * FROM transaction WHERE fileid=$aid";
		$purchaserun2=mysql_query($purchasequery2)or die(mysql_error()." Line 3092");
		$purchasenumrows2=mysql_num_rows($purchaserun2);
		if($price>0){
			$pricemain=round($price * $defrate);
		}else{
			$pricemain=0;
		}
		/*get media info*/
		//get complete cover image;
		$mediaquery="SELECT * FROM media WHERE ownerid=$id AND ownertype='storeaudio' AND mediatype='image' AND status='active' ORDER BY id DESC";
		$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 3092");
		$medianumrows=mysql_num_rows($mediarun);
		$mediarow=mysql_fetch_assoc($mediarun);
		//get complete audio file;
		$mediaquery2="SELECT * FROM media WHERE ownerid=$id AND ownertype='storeaudio' AND maintype='audiofile' AND status='active' ORDER BY id DESC";
		$mediarun2=mysql_query($mediaquery2)or die(mysql_error()." Line 3092");
		$medianumrows2=mysql_num_rows($mediarun2);
		$mediarow2=mysql_fetch_assoc($mediarun2);
		$count=0;
		$cover="";
		$cover2="";

			# code...

				$maincoverphoto=$mediarow['location'];
				$cover='<img src="'.$host_addr.''.$defcoverimg.'"/>';
				$miniclip=$mediarow2['details'];
										$row['scriptoout']='js_audioPlayer("'.str_replace(" ", "%20", $miniclip).'",'.$id.');';
						$scriptooutmain='
								<script>
									js_audioPlayer("'.str_replace(" ", "%20", $miniclip).'",'.$id.');
								</script>
						';
						$scriptooutmain="";
				$audioouttwo=''.$scriptooutmain.'
					<div class="entryexcerpt">
						<div id="jquery_jplayer_'.$id.'" class="jp-jplayer"></div>  
						<div id="jp_container_'.$id.'" class="jp-audio">  
						  <div class="jp-type-single">    
						      <div class="jp-gui jp-interface">  
						        <ul class="jp-controls">  
						          <!-- <li><a href="javascript:;" class="jp-repeat" tabindex="1" title="Refresh stream"><i class="fa fa-refresh" style="position: relative;top: 8px;font-size: 0.7em;"></i></a></li>   -->
						          <!-- <li style="margin-left:0%;"><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="Refresh stream"><i class="fa fa-refresh" style="position: relative;top: 8px;font-size: 0.7em;"></i> -->
						          <!-- <i class="fa fa-ban fa-stack-1x" style="position:absolute;top: 18px;font-size: 0.7em;"></i></a></li>    -->
						          <li><a href="javascript:;" class="jp-play" tabindex="1" title="play radio" style="font-size: 4em;overflow: hidden;"><i class="fa fa-play-circle-o"></i></a></li>  
						          <li style="margin-left:0%;"><a href="javascript:;" class="jp-pause" tabindex="1" style="position: relative;display: none;font-size: 3em;padding: 10px;margin-left:0%;">&#61516;</a></li>  
						          <li style="margin-left:0%;height: 63px;"><a href="javascript:;" class="jp-stop" tabindex="1" title="" style="position: relative;display:block;top:10px;font-size: 2em;padding: 10px;margin-left:0%;"><i class="fa fa-stop"></i></a></li>  
						          
						           <!-- <li class="volumemax"><a href="javascript:;" class="jp-volume-max" tabindex="1" title="MaxVolume"><i class="fa fa-volume-up"></i></a></a></li>    -->
						          <li class="mute"><a href="javascript:;" class="jp-mute" tabindex="2" title="mute"><i class="fa fa-volume-up"></i></a></li>  
						          <li class="unmute"><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute"><i class="fa fa-volume-off"></i></a></a></li>  
						        </ul>  
						        <div class="jp-title">  
							      <ul>  
							        <li><p class="radiodescription">Online store audio preview</p></li>  
							      </ul>  
							    </div>
						        <div class="jp-progress">  
						          <div class="jp-seek-bar">  
						            <div class="jp-play-bar"></div>  
						          </div>  
						        </div>  
						  
						        <div class="jp-time-holder">  
						          <div class="jp-current-time currenttime"></div>  
						        </div>  
						  
						        <div class="jp-volume-bar">  
						          <div class="jp-volume-bar-value"></div>  
						        </div>  
						  
							    <div class="jp-no-solution">  
							      <span>Update Required</span>  
							      To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.  
							    </div>  
							  </div>  
							</div> 
						</div>
					</div>
				';
				$audioout='
					<audio src="'.$host_addr.''.$miniclip.'" style="height:32px;"preload="none" controls>Download <a href="'.$host_addr.''.$miniclip.'"></a></audio><br>
				';
				$audiooutthree='
					<audio src="'.$host_addr.''.$mediarow2['location'].'" style="height:32px;"preload="none" controls>Download <a href="'.$host_addr.''.$mediarow2['location'].'"></a></audio><br>
				';
				$audiooutfour='
					<audio src="'.$host_addr.''.$miniclip.'" style="height:32px;"preload="none" controls>Download <a href="'.$host_addr.''.$mediarow2['location'].'"></a></audio><br>
				';
			
		$row['adminoutput']='
			<tr data-id="'.$id.'">
				<td>'.$cover.'</td><td>'.$storename.'</td><td>'.$title.'</td><td>'.$audiooutthree.'</td><td>'.$audiooutfour.'</td><td>'.$price.'</td><td>'.$purchasenumrows2.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsinglestoreaudio" data-divid="'.$id.'">Edit</a></td>
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
				<form action="../snippets/edit.php" name="editstoreaudio" method="post" enctype="multipart/form-data">
					<input type="hidden" name="entryvariant" value="editstoreaudio"/>
					<input type="hidden" name="entryid" value="'.$id.'"/>
					<input type="hidden" name="aid" value="'.$aid.'"/>
					<input type="hidden" name="coverid" value="'.$coverid.'"/>
					<input type="hidden" name="prevstartprev" value="'.$prevstart.'"/>
					<input type="hidden" name="prevstopprev" value="'.$prevstop.'"/>
					<div id="formheader">Edit '.$title.'</div>
						<div id="formend">
								Store *<br>
								<select name="type" class="curved2">
									<option value="">--Choose--</option>
									<option value="1">Frankly Speaking Store</option>
									<option value="2">CSI Store</option>
								</select>
						</div>
						<div id="formend">
								Title<br>
								<input type="text" name="title" Placeholder="The title of the entry." value="'.$title.'" class="curved"/>
						</div>
						<div id="formend">
								Mini Title<br>
								<input type="text" name="minititle" Placeholder="The minititle of the entry." value="'.$minititle.'" class="curved"/>
						</div>
						<div id="formend">
								Price<br>
								<input type="text" name="price" Placeholder="The price, in Dollars $ e.g 5.99." value="'.$price.'" class="curved"/>
						</div>
						<div id="formend">
								Cover Photo<br>
								<input type="file" name="profpic" Placeholder="The Cover pic." class="curved"/>
						</div>
						<div id="formend">
							Mini Description *<br>
							<textarea name="minidescription" id="" placeholder="Place brief description of the file there" class="curved3">'.$minidescription.'</textarea>
						</div>
						<div id="formend">
								Audio File<br>
								<input type="file" name="audio" Placeholder="The Audio file." class="curved"/>
						</div>
						<div id="formend">
								Force audio preview<br>(use this field to upload pre-trimmed audio files to the server in the event that auto trimming did not work, when uploading here, endeavour not to change the Preview start and stop time)<br>
								<input type="file" name="audio2" Placeholder="The preview Audio file." class="curved"/>
						</div>
						<div id="formend">
							Preview Start(this works when both the start and stop times are set, otherwise it defaults to the first 5minutes of the audio entry!!)<br>
							<input type="text" name="prevstart" Placeholder="Start time in the format (mm:ss)." value="'.$prevstart.'" class="curved"/>
						</div>
						<div id="formend">
								Preview Stop<br>
								<input type="text" name="prevstop" Placeholder="Stop time in the format (mm:ss)." value="'.$prevstop.'" class="curved"/>
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
						<input type="submit" name="updatestoreaudio" value="Update" class="submitbutton"/>
					</div>
				</form>
			</div>
		';
		// 2528-0026209

		$row['vieweroutput']='
			<div class="triplewidth '.$storestyle.'">
				<div class="row hcontrol1">
					<img src="'.$defcoverimg.'" class="imgheight">
					'.$minititleout.'
				</div>
				<div class="row hcontrol2">
					<div class="entrytitle" title="'.$title.'">'.$title.'</div>
					<div class="minidesc" title="'.$minidescription.'">'.$minidescription.'</div>
					'.$audioouttwo.'
					<div class="entryprice">$'.$price.'</div>
					<div class="entrybuynow">
						<form method="POST" action="https://voguepay.com/pay/">
							<input type="hidden" name="v_merchant_id" value="'.$merchant_id.'" />
							<input type="hidden" name="memo" value="Order from '.$storename.'" />
							<input type="hidden" name="notify_url" value="'.$host_addr.'snippets/notification.php?fid='.$aid.'" />
							<input type="hidden" name="success_url" value="'.$host_addr.'success.php?fid='.$aid.'"/>
							<input type="hidden" name="fail_url" value="'.$host_addr.'failure.php?er=error&msg=Something went wrong, and you transaction was unsuccessfull" />
							<input type="hidden" name="store_id" value="'.$vstoreid.'" />
							<input type="hidden" name="item_1" value="'.$title.'" />
							<input type="hidden" name="price_1" value="'.$pricemain.'" />
							<input type="hidden" name="description_1" value="'.str_replace("\"", "\'", $minidescription).'"/><br/>
							<input type="image" class="voguebtn" src="https://voguepay.com/images/buttons/'.$buttontype.'.png" alt="PAY" />
						</form>
					</div>
				</div>
			</div>
		';
		$row['file']=$maincoverphoto;
		$row['filepath']=$host_addr.$maincoverphoto;
		$row['audio']=$mediarow2['location'];
		$row['audiopath']=$host_addr.$mediarow2['location'];
		return $row;
	}
	function getAllStoreAudio($viewer,$limit,$type){
		$row=array();
		$testit=strpos($limit,"-");
		$testit!==false?$limit="":$limit=$limit;
		$joiner='';
		$joiner2='';
		$type==""?$type="all":$type;
		if($type!==""&&$type!=="all"){
		$joiner='AND typeid='.$type.'';
		$joiner2='WHERE typeid='.$type.'';
		}
		if($limit==""&&$viewer=="admin"){
		$query="SELECT * FROM onlinestoreentries $joiner2 ORDER BY id DESC LIMIT 0,15";
		$rowmonitor['chiefquery']="SELECT * FROM onlinestoreentries $joiner2 ORDER BY id DESC";
		}elseif($limit!==""&&$viewer=="admin"){
		$query="SELECT * FROM onlinestoreentries $joiner2 ORDER BY id DESC $limit";
		$rowmonitor['chiefquery']="SELECT * FROM onlinestoreentries $joiner2 ORDER BY id DESC";
		}elseif($viewer=="viewer"){
		$limit==""?$limit="LIMIT 0,15":$limit=$limit;
		$query="SELECT * FROM onlinestoreentries WHERE status='active' $joiner ORDER BY id DESC $limit";
		$rowmonitor['chiefquery']="SELECT * FROM onlinestoreentries WHERE status='active' $joiner";	
		}
		if(is_array($viewer)){
			$search=$viewer[0];
			$viewer="search";
			$limit==""?$limit="LIMIT 0,15":$limit=$limit;
			$query="SELECT * FROM onlinestoreentries WHERE status='active' AND (title LIKE '%$search%' OR minititle LIKE '%$search%' OR minidescription LIKE '%$search%') ORDER BY id DESC $limit";
			$rowmonitor['chiefquery']="SELECT * FROM onlinestoreentries WHERE status='active' AND (title LIKE '%$search%' OR minititle LIKE '%$search%' OR minidescription LIKE '%$search%')";		
		}
		// echo $query;
		$run=mysql_query($query)or die(mysql_error()." Line 4526");
		$numrows=mysql_num_rows($run);
		$adminoutput="<td colspan=\"100%\">No entries</td>";
		$adminoutputtwo="";
		// $vieweroutput='<font color="#fefefe">Sorry No store entries have been made</font>';
		$vieweroutputtwo='<font color="#fefefe">Sorry No store entries have been made</font>';
		$vieweroutput=$viewer=="search"?'Sorry, your search on <b>'.stripslashes($search).'</b> yielded no results':'<font color="#fefefe">Sorry No store entries have been made</font>';
		$scriptoout="";
		if($numrows>0){
		$adminoutput="";
		$adminoutputtwo="";
		$vieweroutput="";
		if($numrows%3>0||$numrows%3<0){
			$monitor=true;
		}
		$count=0;
		while($row=mysql_fetch_assoc($run)){
			$outs=getSingleStoreAudio($row['id']);
			$adminoutput.=$outs['adminoutput'];
			$adminoutputtwo.=$outs['adminoutputtwo'];
			if($count==0||($count%3==0&&$count<$numrows)){
			$count==0?$vieweroutput.='<div id="formend">':$vieweroutput.='</div><div id="formend">';
			$next3=$count+3;
			}
			$vieweroutput.=$outs['vieweroutput'];
			if($count==$next3||$count==$numrows-1){
			  $vieweroutput.='</div>';
			}
			
			$scriptoout.=$outs['scriptoout'];
			$count++;
		}
		$scriptoout='<script async>$(document).ready(function(){'.$scriptoout.'});</script>';
		}
		$top='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>Cover</th><th>Store Name</th><th>Title</th><th>Audio</th><th>Preview</th><th>Price($)</th><th>Purchases</th><th>status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		$bottom='	</tbody>
				</table>';
			$row['chiefquery']=$rowmonitor['chiefquery'];
		$outs=paginatejavascript($rowmonitor['chiefquery']);
		$outsviewer=paginate($rowmonitor['chiefquery']);
		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
				<input type="hidden" name="outputtype" value="store|'.$type.'"/>
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
		$row['vieweroutput']=$vieweroutput.$scriptoout;

		return $row;
	}

	function getSingleTransaction($id){
		global $host_addr,$host_target_addr;
		$row=array();
		$query="SELECT * FROM transaction WHERE id=$id";
		$run=mysql_query($query)or die(mysql_error()." Line 3085");
		$row=mysql_fetch_assoc($run);
		$id=$row['id'];
		$voguerefid=$row['voguerefid'];
		$amountpaid=$row['amountpaid'];
		$email=$row['email'];
		$fileid=$row['fileid'];
		$downloads=$row['downloads']>5?"5":$row['downloads'];
		$startdate=$row['startdate'];
		$voguestatus=$row['voguestatus'];
		// get the main point for this transaction
		$mediadata=getSingleMediaDataTwo($fileid);
		$storedata=getSingleStoreAudio($mediadata['ownerid']);
		$storename=$storedata['storename'];
		$title=$storedata['title'];
		$row['adminoutput']='
			<tr data-id="'.$id.'">
				<td>'.$voguerefid.'</td><td>'.$storename.'</td><td>'.$title.'</td><td>'.$email.'</td><td>'.$amountpaid.'</td><td>'.$downloads.'</td><td>'.$startdate.'</td><td>'.$voguestatus.'</td><!--<td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsinglestoreaudio" data-divid="'.$id.'">Edit</a></td>-->
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

		';
		return $row;
	}
	function getAllTransactions($viewer,$limit,$type){
		$row=array();
		$testit=strpos($limit,"-");
		$testit!==false?$limit="":$limit=$limit;
		$joiner='';
		$joiner2='';
		$type==""?$type="all":$type;
		if($type!==""&&$type!=="all"){
		$joiner='AND typeid='.$type.'';
		$joiner2='WHERE typeid='.$type.'';
		}
		if($limit==""&&$viewer=="admin"){
		$query="SELECT * FROM transaction $joiner2 ORDER BY id DESC LIMIT 0,15";
		$rowmonitor['chiefquery']="SELECT * FROM transaction $joiner2 ORDER BY id DESC";
		}elseif($limit!==""&&$viewer=="admin"){
		$query="SELECT * FROM transaction $joiner2 ORDER BY id DESC $limit";
		$rowmonitor['chiefquery']="SELECT * FROM transaction $joiner2 ORDER BY id DESC";
		}elseif($viewer=="viewer"){
		$limit==""?$limit="LIMIT 0,15":$limit=$limit;
		$query="SELECT * FROM transaction WHERE status='active' $joiner ORDER BY id DESC $limit";
		$rowmonitor['chiefquery']="SELECT * FROM transaction WHERE status='active' $joiner";	
		}
		// echo $query;
		$run=mysql_query($query)or die(mysql_error()." ".__LINE__);
		$numrows=mysql_num_rows($run);
		$adminoutput="<td colspan=\"100%\">No entries</td>";
		$adminoutputtwo="";
		// $vieweroutput='<font color="#fefefe">Sorry No store entries have been made</font>';
		$vieweroutputtwo='<font color="#fefefe">Sorry No store entries have been made</font>';
		$vieweroutput=$viewer=="search"?'Sorry, your search on <b>'.$search.'</b> yielded no results':'<font color="#fefefe">Sorry No store entries have been made</font>';
		$scriptoout="";
		if($numrows>0){
		$adminoutput="";
		$adminoutputtwo="";
		$vieweroutput="";
			while($row=mysql_fetch_assoc($run)){
				$outs=getSingleTransaction($row['id']);
				$adminoutput.=$outs['adminoutput'];
				$adminoutputtwo.=$outs['adminoutputtwo'];
				
			}

		}
		$top='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>Vogueref</th><th>Store Name</th><th>Title</th><th>EmailAddress</th><th>Amount Paid(#)</th><th>Downloads</th><th>PaymentDate</th><th>VogueStatus</th><!--<th>View/Edit</th>--></tr></thead>
					<tbody>';
		$bottom='	</tbody>
				</table>';
			$row['chiefquery']=$rowmonitor['chiefquery'];
		$outs=paginatejavascript($rowmonitor['chiefquery']);
		$outsviewer=paginate($rowmonitor['chiefquery']);
		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
				<input type="hidden" name="outputtype" value="transaction|'.$type.'"/>
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
		return $row;
	}
?>