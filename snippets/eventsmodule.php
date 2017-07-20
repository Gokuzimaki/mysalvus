<?php
function getSingleEventEntry($eventid,$type=""){
	include('globalsmodule.php');
	$row=array();
	$today=date("Y-m-d H:i:s");
	$query="SELECT * FROM events WHERE id=$eventid";
	if($type=="last"){
		$query="SELECT * FROM events ORDER BY id";
	}else if($type=="lastupcoming"){
		$query="SELECT * FROM events WHERE startdate>'$today' ORDER BY id";

	}
	$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
	$row=mysql_fetch_assoc($run);
	$numrows=mysql_num_rows($run);
	if($numrows>0){


		$id=$row['id'];
		$type=$row['type'];

		$eventtitle=$row['eventtitle'];
		$eventdetails=$row['eventdetails'];
		$eventdetailsmini=$eventdetails;
		if($eventdetails!==""){
			$headerdescription = convert_html_to_text(stripslashes($eventdetails));
			$headerdescription=html2txt($headerdescription);
			$monitorlength=strlen($headerdescription);
			$headerdescription=$monitorlength<400?$headerdescription."...":substr($headerdescription, 0,400)."...";
			$eventdetailsmini=$headerdescription;
		}
		$date=$row['dateperiod'];
		$attendancecount=$row['attendancecount'];
		$address=$row['address'];
		$contactperson=$row['contactperson'];
		$contactnumber=$row['contactnumber'];
		$contactemail=$row['contactemail'];
		// check to see if there are multiple contact persons
		$personsdata=explode(",",$contactperson);
		$numbersdata=explode(",",$contactnumber);
		$emailssdata=explode(",",$contactemail);
		$isbookable=$row['isbookable'];
		$usemulti="";
		$rpersonsdata=array();
		if(count($personsdata)>0){
			$usemulti="true";
			for ($i=0; $i < count($personsdata); $i++) { 
				# code...
				$rpersonsdata[$i]['contactperson']=$personsdata[$i];
				$rpersonsdata[$i]['contactnumber']=isset($numbersdata[$i])?$numbersdata[$i]:"";
				$rpersonsdata[$i]['contactemail']=isset($emailsdata[$i])?$emailsdata[$i]:"";
			}
		}else{
			$rpersonsdata[0]['contactperson']=$contactperson;
			$rpersonsdata[0]['contactnumber']=$contactnumber;
			$rpersonsdata[0]['contactemail']=$contactemail;
		}
		$row['contactsdatacount']=count($personsdata);
		$row['contactsdata']=$rpersonsdata;
		$bookingrequirements=$row['bookingrequirements'];
		// $addresslngnlat=$row['addresslngnlat'];
		$longitude="";
		$latitude="";
		$mapdisplay="hidden";
		$nomap="no-map";
		$mapscriptout="";
		$multiple="";
		/*$msplit=explode(",", $addresslngnlat);
		$mapdisplay="hidden";
		$longitude=$msplit[0];
		$latitude=$msplit[1];*/
		$eventaddressesset="";
		$prevaddrcontent="";
		$mquery=briefquery("SELECT * FROM eventaddresses WHERE eventid='$id'");
		if($mquery['numrows']>0){
			$multiple="yes";
		}
		if($mquery['numrows']>0){

			// sort through event entries
			$jsarrsetup='var curdata=[];';
			$t=0;
			$mc=0;
			for($i=0;$i<$mquery['numrows'];$i++){
				$t=$i+1;
				$curesultset=$mquery['resultdata'][$i];
				if($curesultset['status']=='active'){
					$eventaddressesset.='
						<div class="col-md-12 multi_content_hold">
	                		<h4 class="multi_content_countlabels">Event Location Information (Entry '.$t.')</h4>
	                		<div class="col-sm-3">
	                			<div class="form-group">
		                            <label>Location Title <br><small>e.g "Ikeja City Mall"</small></label>
		                            <div class="input-group">
		                              <div class="input-group-addon">
		                                <i class="fa fa-file-text"></i>
		                              </div>
		                              <input type="hidden" class="form-control" name="addrid'.$t.'" value="'.$curesultset['id'].'"/>
		                              <input type="text" class="form-control" name="locationtitle'.$t.'" value="'.$curesultset['locationtitle'].'" Placeholder="Specify the location title"/>
		                            </div><!-- /.input group -->
		                        </div><!-- /.form group -->
	                		</div>
	                		<div class="col-sm-3">
	                			<div class="form-group">
		                            <label>Address<br><small>Provide the full address for the current location</small></label>
		                            <div class="input-group">
		                              <div class="input-group-addon">
		                                <i class="fa fa-file-text"></i>
		                              </div>
		                              <textarea class="form-control" rows="3" name="address'.$t.'" Placeholder="Give the Full Address">'.$curesultset['address'].'</textarea>
		                            </div><!-- /.input group -->
		                        </div><!-- /.form group -->
	                		</div>
	                		<div class="col-sm-3">
	                			<div class="form-group">
		                            <label>Map Latitude value <br><small>must be up to 6 decimal places e.g "30.837462"</small></label>
		                            <div class="input-group">
		                              <div class="input-group-addon">
		                                <i class="fa fa-file-text"></i>
		                              </div>
		                              <input type="text" class="form-control" name="lat'.$t.'" value="'.$curesultset['lat'].'"Placeholder="Specify the location title"/>
		                            </div><!-- /.input group -->
		                        </div><!-- /.form group -->
	                		</div>
	                		<div class="col-sm-3">
	                			<div class="form-group">
		                            <label>Map Longitude value <br><small>must be up to 6 decimal places e.g "30.837462"</small></label>
		                            <div class="input-group">
		                              <div class="input-group-addon">
		                                <i class="fa fa-file-text"></i>
		                              </div>
		                              <input type="text" class="form-control" name="lng'.$t.'" value="'.$curesultset['lng'].'" Placeholder="Specify the location title"/>
		                            </div><!-- /.input group -->
		                        </div><!-- /.form group -->
	                		</div>
	                	</div>
					';
					$address.='<div class="addr_location"><b><h4>'.$curesultset['locationtitle'].':</h4></b><br>'.$curesultset['address'].'</div><br>';
					if($curesultset['lng']!==""&&$curesultset['lat']&&is_numeric($curesultset['lat'])&&is_numeric($curesultset['lng'])){
						$nomap="";
						$mapdisplay="";
						if($latitude==""&&$longitude==""){
							$latitude=$curesultset['lat'];
							$longitude=$curesultset['lng'];
						}
						$jsarrsetup.='
							curdata['.$mc.']=[];
							curdata['.$mc.'][0]="'.$curesultset['locationtitle'].'";
							curdata['.$mc.'][1]="'.$curesultset['lat'].'";
							curdata['.$mc.'][2]="'.$curesultset['lng'].'";
							curdata['.$mc.'][3]="'.$curesultset['address'].'";
							curdata['.$mc.'][4]=\'<div id="content">\' + \'<div id="siteNotice">\' + \'<p>Venue:</p><h4><b>'.$curesultset['locationtitle'].'</b></h4>\' + \'</div>\' + \'<div id="bodyContent">\' + \'<p><i class="icon-map-marker"></i> '.str_replace("'", "\'", $curesultset['address']).'\' + \'</p>\' + \'</div>\' + \'</div>\';
							curdata['.$mc.'][5]=host_addr+\'images/fvtimages/map-icon-2.png\';
						';
						$mc++;
					}
					
				}
			}
			$t=$t+1;
			$prevaddrcontent='
				<div class="box-group" id="eventaddresses'.$id.'">
					<div class="panel box overflowhidden box-primary">
					    <div class="box-header with-border">
					        <h4 class="box-title">
					          <a data-toggle="collapse" data-parent="#eventaddresses'.$id.'" href="#evententries">
					            <i class="fa fa-gear fa-spin"></i> Edit Previous Event Address Data
					          </a>
					        </h4>
					    </div>
					    <div id="evententries" class="panel-collapse collapse overflowhidden">
							'.$eventaddressesset.'
					    </div>
					</div>
				</div>
				<div class="col-md-12 marginbottom multi_content_hold_generic">
	            	<h3>Maximum of 10 entries at a time</h3>
	            	<div class="col-md-12 multi_content_hold" data-type="triggerprogenitor" data-cid="'.$t.'" data-name="addressslides">
	            		<h4 class="multi_content_countlabels">Event Location Information (Entry '.$t.')</h4>
	            		<div class="col-sm-3">
	            			<div class="form-group">
	                            <label>Location Title <br><small>e.g "Ikeja City Mall"</small></label>
	                            <div class="input-group">
	                              <div class="input-group-addon">
	                                <i class="fa fa-file-text"></i>
	                              </div>
	                              <input type="text" class="form-control" name="locationtitle'.$t.'" Placeholder="Specify the location title"/>
	                            </div><!-- /.input group -->
	                        </div><!-- /.form group -->
	            		</div>
	            		<div class="col-sm-3">
	            			<div class="form-group">
	                            <label>Address<br><small>Provide the full address for the current location</small></label>
	                            <div class="input-group">
	                              <div class="input-group-addon">
	                                <i class="fa fa-file-text"></i>
	                              </div>
	                              <textarea class="form-control" rows="3" name="address'.$t.'" Placeholder="Give the Full Address"/></textarea>
	                            </div><!-- /.input group -->
	                        </div><!-- /.form group -->
	            		</div>
	            		<div class="col-sm-3">
	            			<div class="form-group">
	                            <label>Map Latitude value <br><small>must be up to 6 decimal places e.g "30.837462"</small></label>
	                            <div class="input-group">
	                              <div class="input-group-addon">
	                                <i class="fa fa-file-text"></i>
	                              </div>
	                              <input type="text" class="form-control" name="lat'.$t.'" Placeholder="Specify the location title"/>
	                            </div><!-- /.input group -->
	                        </div><!-- /.form group -->
	            		</div>
	            		<div class="col-sm-3">
	            			<div class="form-group">
	                            <label>Map Longitude value <br><small>must be up to 6 decimal places e.g "30.837462"</small></label>
	                            <div class="input-group">
	                              <div class="input-group-addon">
	                                <i class="fa fa-file-text"></i>
	                              </div>
	                              <input type="text" class="form-control" name="lng'.$t.'" Placeholder="Specify the location title"/>
	                            </div><!-- /.input group -->
	                        </div><!-- /.form group -->
	            		</div>
	            	</div>
	            	<div name="addressslidesentrypoint" data-marker="true"></div>
	            	<input name="addressslidescount" type="hidden" value="'.$t.'" data-valset="1,2" data-valcount="1" data-counter="true"/>
	            	<input name="addressslidesdatamap" type="hidden" data-map="true" value="locationtitle-:-input<|>
	            	address-:-textarea<|>
	            	lat-:-input<|>
	            	lng-:-input"/>
	            	<a href="##" 
	            	   class="generic_addcontent_trigger"
	            	   data-type="triggerformadd" 
	            	   data-name="addressslidescount_addlink"
	            	   data-i-type=""
	            	   data-limit="10"> 
	            		<i class="fa fa-plus"></i>Add More?
	            	</a>
	            </div> 
			';
			if($longitude!==""&&$latitude!==""){
				$mapscriptout='
					<script>
				        $(document).ready(function(){
				        	'.$jsarrsetup.'
				        	var data=[];
				        	data["elid"]="real_map_contact_'.$id.'";
				        	data["zoom"]="18";
				        	data["zoomcontrol"]="true";
				        	data["styles"]=[{stylers: [{hue: \'#FF8000\'}, {saturation: -10}, ] }];
				        	// console.log("typeof google - ", typeof(google))
				    		if(typeof(google)!=="undefined"){
					        	if ($(\'#real_map_contact_'.$id.'\').length>0) {
					        		infos=[];
							        initializeGmap('.$latitude.','.$longitude.',data,"'.$multiple.'",curdata);
							    }
							}
				        })
			        </script>
				';
			}
		}else{
			$nomap="no-map";
		}
		$dateperiod=$row['dateperiod'];
		$startdate=$row['startdate'];
		$startdateout=date('D, d F, Y h:i:s A', strtotime($startdate));
		$stopdate=$row['stopdate'];
		$stopdateout=date('D, d F, Y h:i:s A', strtotime($stopdate));
		// $maindayout=date('D, d F, Y h:i:s A', strtotime($date));
		$fancystartdayout=date('d, F, Y', strtotime($startdate));
		$starttime=date('h:i:s A', strtotime($startdate));
		$endtime=date('h:i:s A', strtotime($stopdate));
		$status=$row['status'];

		// sort event image if available
		$qdata=briefquery("SELECT * FROM media WHERE ownertype='event' AND ownerid='$id' AND maintype='coverphoto'"); 
		$coverimageout='<img src="'.$host_addr.'images/fvtimages/fvtdefaultcover.jpg"/>';
		$imgid=0;
		if($qdata['numrows']>0){
			$medcover=$qdata['resultdata'][0]['medsize'];
			$coverimageout='<img src="'.$host_addr.''.$medcover.'"/>';
			$imgid=$qdata['resultdata'][0]['id'];
		}
		$bookablelink="";
		$bookingstyle="hidden";
		if($isbookable=="yes"){
			$bookingstyle="";
			$bookablelink='<a href="'.$host_addr.'evententries.php?e='.$id.'&t=register" class="btn-register event-register">REGISTER</a>';
		}
		$mapout='
			<div class="map-box '.$nomap.'">
	            <h2 class="heading">Event Venue</h2>
	            <div id="real_map_contact_'.$id.'" class="map_canvas active '.$mapdisplay.'"></div>
	            <div class="caption '.$nomap.'">
					<h3 class="sub-heading">Venue(s)</h3>
					<div class="addr-content">
						'.$address.'
					</div>
	                
	            </div>
	        </div>
	        '.$mapscriptout.'
		';
		$row['adminoutput']='
		    <tr data-id="'.$id.'">
		    	<td class="tdimg">'.$coverimageout.'</td><td>'.$eventtitle.'</td><td>'.$startdateout.'</td><td>'.$stopdateout.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsingleevententry" data-divid="'.$id.'">Edit</a></td>
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
		    	<form action="../snippets/edit.php" name="editeventform" onsubmit="return:false;" method="post" enctype="multipart/form-data">
		    		<input type="hidden" name="entryvariant" value="editevent"/>
		    		<input type="hidden" name="entryid" value="'.$id.'"/>
		    		<div id="formheader">Edit '.$eventtitle.'</div>
		    		<div class="col-md-12">
	                	<div class="col-sm-3"> 
	                      <div class="form-group">
	                        <label>Event Title</label>
	                        <div class="input-group">
	                          <div class="input-group-addon">
	                            <i class="fa fa-file-text"></i>
	                          </div>
	                          <input type="text" class="form-control" name="eventtitle" Placeholder="The Event title" value="'.$eventtitle.'"/>
	                        </div><!-- /.input group -->
	                      </div><!-- /.form group -->
	                    </div>
	                    <div class="col-sm-3"> 
	                      <div class="form-group">
	                        <label>Event Cover Image</label>
	                        <div class="input-group">
	                          <div class="input-group-addon">
	                            <i class="fa fa-file-image-o"></i>
	                          </div>
	                          <input type="hidden" class="form-control" name="eventcoverimageid" value="'.$imgid.'"/>
	                          <input type="file" class="form-control" name="eventcoverimage" Placeholder="The Cover Image"/>
	                        </div><!-- /.input group -->
	                      </div><!-- /.form group -->
	                    </div>
	                    <div class="col-md-3">
							<!-- Date range -->
				            <div class="form-group">
				                <label>Start Date&Time:</label>
				                <div class="input-group">
				                  <input type="text" name="eventstartdate" value="'.$startdate.'" id="eventstartdateedit" placeholder="click/tap to set date and time" class="form-control pull-right"/>
				                  <div class="input-group-addon">
				                    <i class="fa fa-calendar"></i>
				                  </div>
				                </div><!-- /.input group -->
				            </div><!-- /.form group -->	
						</div>
						<div class="col-md-3">
							<!-- Date range -->
				            <div class="form-group">
				                <label>End Date&Time:</label>
				                <div class="input-group">
				                  <input type="text" name="eventenddate" value="'.$stopdate.'" id="eventenddateedit" placeholder="click/tap to set date and time" class="form-control pull-right"/>
				                  <div class="input-group-addon">
				                    <i class="fa fa-calendar"></i>
				                  </div>
				                </div><!-- /.input group -->
				            </div><!-- /.form group -->	
						</div>
						<div class="col-md-12 marginbottom">
	                        <label>Event Details</label>
	                        <textarea class="form-control" rows="3" name="eventdetails" id="postersmallfiveedit" data-mce="true" placeholder="Event details">'.$eventdetails.'</textarea>
	                    </div>
	                    <div class="col-md-12">
	                        <div class="col-sm-4"> 
	                          <div class="form-group">
	                            <label>Contact Person(s)<br><small>Use a comma to seperate each person</small></label>
	                            <div class="input-group">
	                              <div class="input-group-addon">
	                                <i class="fa fa-user"></i>
	                              </div>
	                              <input type="text" class="form-control" name="contactperson" value="'.$contactperson.'" Placeholder="The contact person data for information supply"/>
	                            </div><!-- /.input group -->
	                          </div><!-- /.form group -->
	                        </div>
	                        <div class="col-md-4">
					            <div class="form-group">
					                <label>Contact Phonenumber(s)<br><small>Use a comma to seperate each number group per persons</small></label>
					                <div class="input-group">
					                  <div class="input-group-addon">
					                    <i class="fa fa-phone"></i>
					                  </div>
					                  <input type="text" name="contactnumber" value="'.$contactnumber.'" placeholder="Phonenumber(s)" class="form-control pull-right"/>
					                </div><!-- /.input group -->
					            </div><!-- /.form group -->	
							</div>
							<div class="col-md-4">
					            <div class="form-group">
					                <label>Contact Email(s)<br><small>Use a comma to seperate each email group per persons</small></label>
					                <div class="input-group">
					                  <div class="input-group-addon">
					                    <i class="fa fa-at"></i>
					                  </div>
					                  <input type="text" name="contactemail" value="'.$contactemail.'" placeholder="Provide email address(es)" class="form-control pull-right"/>
					                </div><!-- /.input group -->
					            </div><!-- /.form group -->	
							</div>
	                    </div>
	                </div>
	                '.$prevaddrcontent.'
	                <div class="col-md-12">
	                	<div class="form-group">
			                <label>Is event Bookable<br><small>Specify if site visitors can make bookings for this event</small></label>
			                <div class="input-group">
			                  <div class="input-group-addon">
			                    <i class="fa fa-book"></i>
			                  </div>
			                  <select name="isbookable" class="form-control">
	                            <option value="">Choose</option>
	                            <option value="yes">YES</option>
	                          </select>
			                </div><!-- /.input group -->
			            </div><!-- /.form group -->
	                </div>
	                <div class="col-md-12 '.$bookingstyle.' bookingsrequirements">
	                	<div class="form-group">
			                <label>Bookings Requirement<br><small>Provide bookings requirements for, such as costs and other information</small></label>
			                <div class="input-group">
			                  <div class="input-group-addon">
			                    <i class="fa fa-book"></i>
			                  </div>
	                          <textarea class="form-control" rows="3" name="bookingsrequirement" id="postersmallfiveedittwo" data-mce="true" placeholder="Booking details">'.$bookingrequirements.'</textarea>
			                </div><!-- /.input group -->
			            </div><!-- /.form group -->
	                </div>
	                <div class="col-md-12">
	                	<div class="form-group">
			                <label>Status<br><small>Enable or Disable this event</small></label>
			                <div class="input-group">
			                  <div class="input-group-addon">
			                    <i class="fa fa-trash"></i>
			                  </div>
			                  <select name="status" class="form-control">
	                            <option value="">Change Status</option>
		    					<option value="active">Active</option>
		    					<option value="inactive">Inactive</option>
	                          </select>
			                </div><!-- /.input group -->
			            </div><!-- /.form group -->
	                </div>

	                <input type="hidden" name="formdata" value="editeventform"/>
	                <input type="hidden" name="extraformdata" value="eventtitle-:-input<|>
	                  eventcoverimage-:-input|image<|>
	                  eventstartdate-:-input<|>
	                  eventenddate-:-input<|>
	                  eventdetails-:-textarea<|>
	                  contactperson-:-input<|>
	                  contactnumber-:-input<|>
	                  contactemail-:-input<|>
	                  egroup|data-:-[addressslidescount>|<
	                  				locationtitle-|-input>|<
	                  				address-|-textarea>|<
	                  				lat-|-input>|<
	                  				lng-|-input
	                  				]-:-groupfall[1-2,2-1,1-3-4,3-4,4-3]<|>
	                  isbookable-:-select<|>
	                  bookingsrequirement-:-textarea-:-[group-|-
	          								isbookable-|-select-|-*any*>|<]"/>
	                  <!--  -->
	                <input type="hidden" name="errormap" value="
	                    eventtitle-:-Please provide the title for this event<|>
	                    eventcoverimage-:-NA<|>
	                    eventstartdate-:-Choose a start date and time for this event<|>
	                    eventenddate-:-Choose an end date and time for this event<|>
	                    eventdetails-:-Provide the details for this event<|>
	                    contactperson-:-Specify the contact person(s) for this event<|>
	                    contactnumber-:-Specify the contact phonenumber(s) for this event<|>
	                    contactemail-:-Specify the contact email(s) for this event<|>
	                    egroup|data-:-[
	                      				Please provide the title for the events venue>|<
	                      				Give the full address of the events venue>|<
	                      				Provide the latitude value for the map display>|<
	                      				Provide the longitude value for the map display
	                      			]<|>
	                    isbookable-:-NA<|>
	                    bookingsrequirement-:-Please provide the bookings requirement information.
	                    "/>
		    		<div class="col-md-12 clearboth">
		                <div class="box-footer">
		                    <input type="button" class="btn btn-danger" name="editevent" data-formdata="editeventform" value="Update Event"/>
		                </div>
		            </div>
		    		<script>
				    	$(document).ready(function(){
				    		$(\'form[name=editeventform] select[name=isbookable]\').val("'.$isbookable.'");
				    		$(\'#eventstartdateedit\').datetimepicker({
					            format:"YYYY-MM-DD HH:mm",
					            keepOpen:true
				        	})
				        	$(\'#eventenddateedit\').datetimepicker({
					            format:"YYYY-MM-DD HH:mm",
					            keepOpen:true
				        	})
				        	var curmcethreeposter=[];
							callTinyMCEInit("textarea[id*=postersmallfiveedit]",curmcethreeposter);
				    	});
					</script>
		    	</form>
		    </div>
		';
		$row['vieweroutputmini']='
			<div class="col-md-4 col-sm-6 col-xs-12 event_default_container">
	        	<div class="next-event">
	                <div class="frame event_'.$id.'">
	                    <a href="'.$host_addr.'evententries.php?e='.$id.'">
	                        '.$coverimageout.'
	                    </a>
	                    <div class="caption-2">
	                        <strong class="title">'.$eventtitle.'</strong>
	                    </div>
	                    <div class="caption-3 event-start-date">
	                        <strong class="title">Our Time Left:</strong>
	                    </div>
	                    <div class="caption">
	                        <a href="'.$host_addr.'evententries.php?e='.$id.'">
	                            <i class="fa fa-plus"></i>
	                        </a>
	                    </div>
	                </div>
	                <div class="time-box">
	                    <div class="eventCountdown event_countdown_'.$id.'" data-datetime="'.$startdate.'" data-datetimestop="'.$stopdate.'" data-id="'.$id.'"></div>
	                    <a href="'.$host_addr.'evententries.php?e='.$id.'" class="plus">
	                        <i class="fa fa-plus"></i>
	                    </a>
	                </div>
	            </div>
	        </div>
		';
		$row['vieweroutputminisb']='
			<div class="next-event">
                <div class="frame event_'.$id.'">
                    <a href="'.$host_addr.'evententries.php?e='.$id.'">
                        '.$coverimageout.'
                    </a>
                    <div class="caption-2">
                        <strong class="title">'.$eventtitle.'</strong>
                    </div>
                    <div class="caption-3 event-start-date">
                        <strong class="title">Our Time Left:</strong>
                    </div>
                    <div class="caption">
                        <a href="'.$host_addr.'evententries.php?e='.$id.'">
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="time-box">
                    <div class="eventCountdown event_countdown_'.$id.'" data-datetime="'.$startdate.'" data-datetimestop="'.$stopdate.'" data-id="'.$id.'"></div>
                    <a href="'.$host_addr.'evententries.php?e='.$id.'" class="plus">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
            </div>
		';
		$row['vieweroutputminitwo']='
			<li>
				<div class="top">
	                <div class="frame">
	                    '.$coverimageout.'
	                </div>
	                <div class="text-box">
	                    <h2 class="mini_special_title">'.$eventtitle.'</h2>
	                    
	                    <div class="event-details">
	                    	'.$eventdetailsmini.'
	                    </div>
	                    <a href="'.$host_addr.'evententries.php?e='.$id.'">
	                        more<i class="fa fa-plus-circle"></i>
	                    </a>
	                </div>
	            </div>
	            <div class="bottom">
	                <div class="time-area">
	                    <strong class="time">'.$starttime.'</strong>
	                    <strong class="date">'.$fancystartdayout.'</strong>
	                </div>
	                <div class="event-time-box">                                                	
	                    <div class="eventCountdown event_countdown_'.$id.'" data-datetime="'.$startdate.'" data-datetimestop="'.$stopdate.'" data-id="'.$id.'">
	                    </div>
	                </div>
	                '.$bookablelink.'
	            </div>
	        </li>
		';
		$row['vieweroutput']='
		    <ul class="gallery">
	            <li>
	                <div class="top full_display">
	                    <div class="frame">
	                        '.$coverimageout.'
	                    </div>
	                    <div class="text-box text-center">
	                    	<h2>'.$eventtitle.'</h2>
	                    </div>
	                    <div class="text-area">
	                        '.$eventdetails.'
	                    </div>
	                </div>
	                <div class="bottom full_display">
	                    <div class="time-area">
	                        <strong class="time">'.$starttime.'</strong>
	                        <strong class="date">'.$fancystartdayout.'</strong>
	                    </div>
	                    <div class="event-time-box">
	                    	<div class="event_'.$id.'">
	                        	<div class="caption-3 event-start-date">
	                        		<strong class="title">Time till Event Starts:</strong>
	                        	</div>                                                	
	                        </div>
	                        <div class="eventCountdown event_countdown_'.$id.'" data-datetime="'.$startdate.'" data-datetimestop="'.$stopdate.'" data-id="'.$id.'">
	                        </div>
	                    </div>
	                    <!-- <div class="event-time-box">
	                    	<span class="countdown-row countdown-show4"><span class="countdown-section"><span class="countdown-amount">444</span><span class="countdown-period">Day</span></span><span class="countdown-section"><span class="countdown-amount">23</span><span class="countdown-period">Hrs</span></span><span class="countdown-section"><span class="countdown-amount">16</span><span class="countdown-period">Mins</span></span><span class="countdown-section"><span class="countdown-amount">22</span><span class="countdown-period">Sec</span></span></span>
	                    </div> -->
	                    <!-- <a href="#" class="btn-register event-register">REGISTER</a> -->
	                </div>
	            </li>
	        </ul>
	        '.$mapout.'
		';
	}
	$row['numrows']=$numrows;
 	return $row;
}

function getAllEventEntries($viewer,$limit,$type="",$data=array()){
	include('globalsmodule.php');
	$row=array();
	$testit=strpos($limit,"-");
	$testit!==false?$limit="":$limit=$limit;
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
	$subquery="";
	$catdata=$viewer=='admin'?"WHERE":"AND";
	$type=mysql_real_escape_string($type);
	if($type!==""){
		$subquery="$catdata type='$type' ";
		$catdata="AND";
	}
	if(count($data)>0){
		$startdate=$data[0];
		$stopdate=isset($data[1])?$data[1]:"";
		$trip="";
		if($stopdate=="matchdown"||$stopdate=="matchup"){
			$trip=$stopdate;
			$stopdate="";
		}
		if($startdate!==""&&$stopdate!==""){
			//verify that the set dates are in the right order
			$datetime1 = new DateTime("$startdate"); // specified scheduled time
			$datetime2 = new DateTime("$stopdate");
			if($datetime1>$datetime2){
				$sd=$startdate;
				$startdate=$stopdate;
				$stopdate=$sd;
			}
			$subquery="$catdata startdate>='$startdate' AND stopdate<='$stopdate'";
		}else if($trip=="matchdown"){
			$subquery="$catdata startdate>='$startdate'";
		}else if($trip=="matchup"){
			$subquery="$catdata startdate<='$startdate'";
		}else{
			$subquery="$catdata startdate='$startdate'";

		}
	}

	if($viewer=="admin"){
		$query="SELECT * FROM events $subquery ORDER BY startdate,id DESC $limit";
		$rowmonitor['chiefquery']="SELECT * FROM events  $subquery ORDER BY startdate,id DESC";
	}elseif($viewer=="viewer"){
		$query="SELECT * FROM events WHERE status='active' $subquery ORDER BY startdate DESC $limit";
		$rowmonitor['chiefquery']="SELECT * FROM events WHERE status='active' $subquery ORDER BY startdate DESC";	
	}
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
	$numrows=mysql_num_rows($run);
	$adminoutput="<td colspan=\"100%\">No entries</td>";
	$adminoutputtwo="";
	$vieweroutput='<font color="#fefefe">Sorry, No Events have been posted for this date</font>';
	$vieweroutputtwo='<font color="#fefefe">Sorry, No Events have been posted for this date</font>';
	$vieweroutputmini='<font color="#fefefe">Sorry, No Events have been posted for this date</font>';
	$vieweroutputminitwo='<font color="#fefefe">Sorry, No Events have been posted for this date</font>';
	$singleoutput="";
	$validdates="";
	$validevents="";
	$validarr="";
    $vieweroutputminiarr=array();
    $vieweroutputminitwoarr=array();
	if($numrows>0){
	  	// echo'inhere';
	    $adminoutput="";
	    $adminoutputtwo="";
	    $vieweroutput="";
	    $vieweroutputmini="";
	    $vieweroutputminitwo="";
	    $validevents="";
	    $curdate="";
	    $count=0;
		while($row=mysql_fetch_assoc($run)){

			$outs=getSingleEventEntry($row['id']);
			$adminoutput.=$outs['adminoutput'];
			$adminoutputtwo.=$outs['adminoutputtwo'];
			$vieweroutput.=$outs['vieweroutput'];
			$vieweroutputmini.=$outs['vieweroutputmini'];
			$vieweroutputminitwo.=$outs['vieweroutputminitwo'];
			$vieweroutputminiarr[]=$outs['vieweroutputmini'];
			$vieweroutputminitwoarr[]=$outs['vieweroutputminitwo'];
			$count++;
		}
	      // echo $validarr['2015-09-22']."valid sample<br>";
	}
  	$top='<table id="resultcontenttable" cellspacing="0">
        <thead><tr><th>CoverImage</th><th>Title</th><th>Start Date</th><th>End Date</th><th>status</th><th>View/Edit</th></tr></thead>
        <tbody>';
  	$bottom='	</tbody>
  		</table>';
  	$row['numrows']=$numrows;
  	$row['chiefquery']=$rowmonitor['chiefquery'];
	$outs=paginatejavascript($rowmonitor['chiefquery']);
	$outsviewer=paginate($rowmonitor['chiefquery']);
	$row['chiefquery']=$rowmonitor['chiefquery'];
	if($viewer=="admin"){
		$row['num_pages']=$outs['num_pages'];
		$row['num_pages_rows']=$outs['numrows'];
	}else{
		$row['num_pages']=$outsviewer['num_pages'];
		$row['num_pages_rows']=$outsviewer['numrows'];
	}
	$paginatetop='
	<div id="paginationhold">
		<div class="meneame">
			<input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
			<input type="hidden" name="outputtype" value="events'.$type.'"/>
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
  $row['validdates']=$validdates;
  $row['validevents']=$validevents;
  $row['validarr']=$validarr;
  $row['vieweroutput']=$vieweroutput;
  $row['vieweroutputmini']=$vieweroutputmini;
  $row['vieweroutputminitwo']=$vieweroutputminitwo;
  $row['vieweroutputminiarr']=$vieweroutputminiarr;

  $row['vieweroutputminitwoarr']=$vieweroutputminitwoarr;

  return $row;
}
?>