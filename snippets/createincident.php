<?php 

	$formtruetype="incidentformadmin";

	$typeadmin[0]="nosaved";
	$typeadmin[1]="";
	$userid=0;
	$outsusers="";
	$userid="";
	$hidenew="";
	$newin="";
	$hideedit="";
	$editin="";
	if(isset($userset)&&$userset=="true"){
		// displays only the incident entries by the current user account 
		$userid=$_SESSION['userimysalvus'.$_SESSION['userhmysalvus'].''];
		$type[0]="userview";
		$type[1]=$userid;
		// echo $userid;
	}
	$varianttype="createincidentadmin";
	if($displaytype=="userincidents"||$displaytype=="incidentsnew"){
		$hideedit="hidden";
		$newin="in";
	}else if($displaytype=="edituserincidents"||
		$displaytype=="incidentsedit"||$displaytype=="editsaveduserincidents"||
		$displaytype=="incidentseditsaved"){
		$hidenew="hidden";
		$editin="in";
		// for viewing saved incidents in both admin and user profiles
		if($displaytype=="incidentseditsaved"){
			$typeadmin[0]="saved";
			$typeadmin[1]="";
		}

		if($displaytype=="editsaveduserincidents"){
			// echo $userid;
			$props="userview**saved";
			$vals="$userid**";
			$typevals="$props:$vals";
			// echo $typevals;

			$type[0]="combo";
			$type[1]=$typevals;
		}
	}

	if(isset($userset)&&$userset=="true"){
		// displays only the incident entries by the current user account 

		$outsdata=getAllIncidents("viewer","",$type,"adminoutputtwo2","editsingleincident");
		$varianttype="createincidentuser";
	}else{
		$userset="";
		// this is the administrator
		$outsdata=getAllIncidents("admin","",$typeadmin,"");
		$outsusers=getAllUsers("viewer","","user");
	}
	

	$disclaimer="<b>SALVUS</b> is safe platform that serves 
	    			mainly as a referral portal that connects 
	    			those who need help with service providers, 
	    			we do not provide any direct support services. 
	    			By submitting this form you agree to have your 
	    			details shared with our verified service 
	    			providers who are registered under the 
	    			Federal republic of Nigeria who may be able 
	    			to help. <b>SALVUS</b> is committed to 
	    			protecting the confidentiality of information 
	    			shared with us; we are not liable for protecting 
	    			information shared between you and the service 
	    			provider.";
	$disclaimer_title="Disclaimer";
?>
<div class="box">
	<div class="box-body clearboth">
	    <div class="box-group" id="generaldataaccordion">
			<div class="panel box box-primary <?php echo $hidenew;?>">
			    <div class="box-header with-border">
			        <h4 class="box-title">
			          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#NewPageManagerBlock">
			            <i class="fa fa-sliders"></i> New Incident
			          </a>
			        </h4>
			    </div>
			    <div id="NewPageManagerBlock" class="panel-collapse collapse <?php echo $newin;?>">
			        <div class="row">
						<div class="col-md-12">
                        	<div class="form-group">
                        	<?php 
                        		if ($userset!=="true") {
                        	?>
		                      <label>Incident Type</label>
		                     <?php
		                 		}else{
		                 	?>
		                      <label>Is this report about you or someone else?</label>
		                     <?php
		                 		}
		                 	 ?>
		                      <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-file-text"></i>
			                      </div>
		                      	  <select class="form-control" name="reporttype">
			                      	  	<option value="self">Self</option>
			                      	  	<option value="thirdparty">Witness</option>
		                      	  </select>
		                      	  <?php
		                      	  	if ($userset!=="true") {
		                      	  		# code...
		                      	  		// echo "$userset :userset";
		                      	  ?>
			                      	  <select name="userid" class="form-control select ">
				                      	  	<option value="">--Select the Reporting User--</option>
				                      	  	<?php echo $outsusers['selection'];?>
				                      </select>
			                      <?php
			                  		}
		                      	  ?>
		                      </div>
		                    </div>
		                </div>
			            <form name="<?php echo $formtruetype;?>" method="POST" class="selfincident" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/basicsignup.php">
							<input type="hidden" name="entryvariant" value="<?php echo $varianttype;?>"/>
							<input type="hidden" name="reporttype" value="self"/>
							<input type="hidden" name="userid" value="<?php echo $userid;?>"/>
							<?php
								if ($userset=="true") {
                      	  		# code...
							?>
							<input type="hidden" name="retval" value="<?php echo $host_addr;?>userdashboard.php"/>
							<?php
								}
							?>
							<div class="col-md-12 ">
								<div class="col-md-12">
									<div class="col-md-6">
							        	<div class="form-group">
							              <label>How often did the incident occur</label>
							              <div class="input-group">
							                  <div class="input-group-addon">
							                    <i class="fa fa-clock-o"></i>
							                  </div>
							              	  <select class="form-control" name="incidentfrequency">
							                  	  	<option value="">--Choose--</option>
							                  	  	<option value="once">Once</option>
							                  	  	<option value="more than once">More than once</option>
							                  	  	<option value="ongoing">Ongoing</option>
							              	  </select>
							              	  <input class="form-control hidden" name="incidentstarttime" data-datepicker="true" placeholder="Specify the day the incident started"/>
							              	  <input class="form-control" name="incidentreporttime" data-datepicker="true" placeholder="Specify the date of the current incident."/>
							              </div>
							            </div>
							        </div>
							        <div class="col-md-6">
							        	<div class="form-group">
							              <label>Describe where it happened.(Provide the address if possible)</label>
							              <div class="input-group">
							                  <div class="input-group-addon">
							                    <i class="fa fa-map-marker"></i>
							                  </div>
							              	  <textarea class="form-control" name="incidentlocation" placeholder="Provide incident location"></textarea>
							              </div>
							            </div>
							        </div>
							    </div>
							    <div class="col-md-12">

							        <div class="col-md-3">
								    	<div class="form-group">
								          <label>Nature of Incident</label>
								          <div class="input-group">
								              <div class="input-group-addon">
								                <i class="fa fa-street-view"></i>
								              </div>
								          	  <select class="form-control" name="incidentnature">
								          	  	<option value="">--Choose--</option>
								          	  	<option value="rape">Rape</option>
								          	  	<option value="attempted rape">Attempted Rape</option>
								          	  	<option value="stalking">Stalking</option>
								          	  	<option value="sexual harassment">Sexual Harassment</option>
								          	  	<option value="other">Other</option>
								          	  </select>
								          	  <input name="incidentnaturedetails" type="text" class="form-control hidden" placeholder="Specify 'Other' details" />
								          </div>
								        </div>
								    </div>
								    <div class="col-md-3">
								    	<div class="form-group">
								          <label>Use of threats, restraints, weapons</label>
								          <div class="input-group">
								              <div class="input-group-addon">
								                <i class="fa fa-street-view"></i>
								              </div>
								          	  <select type="text" class="form-control" name="weaponuse">
							                  	<option value="">--Choose--</option>
								          	  	<option value="no">No</option>
								          	  	<option value="yes">Yes</option>
								          	  </select>
								          </div>
								        </div>
								    </div>
								    
								    <div class="col-md-3">
								    	<div class="form-group">
								          <label>Incident Reported Before?</label>
								          <div class="input-group">
								              <div class="input-group-addon">
								                <i class="fa fa-street-view"></i>
								              </div>
								          	  <select type="text" class="form-control" name="ireported">
							                  	<option value="">--Choose--</option>
								          	  	<option value="no">No</option>
								          	  	<option value="yes">Yes</option>
								          	  </select>
								          </div>
								        </div>
								    </div>
								    <div class="col-md-3">
								    	<div class="form-group">
								          <label>Help Obtained after incident occured?</label>
								          <div class="input-group">
								              <div class="input-group-addon">
								                <i class="fa fa-street-view"></i>
								              </div>
								          	  <select type="text" class="form-control" name="ireportedaid">
							                  	<option value="">--Choose--</option>
								          	  	<option value="no">No</option>
								          	  	<option value="yes">Yes</option>
								          	  </select>
								          </div>
								        </div>
								    </div>
								    <div class="col-md-12">
									    <div class="col-md-4 hidden weapons-details">
									    	<div class="form-group">
									          <label>Use of threats/weapons/restraints details</label>
									          <div class="input-group">
									              <div class="input-group-addon">
									                <i class="fa fa-warning color-darkred"></i>
									              </div>
									          	  <select type="text" title="Were Weapons Used?" class="form-control" name="weapon">
							                      	<option value="">--Were Weapons Used?--</option>
									          	  	<option value="no">No</option>
									          	  	<option value="yes">Yes</option>
									          	  </select>
									          	  <select type="text" title="Were Threats Used?" class="form-control" name="threat">
							                      	<option value="">--Were Threats Used?--</option>
									          	  	<option value="no">No</option>
									          	  	<option value="yes">Yes</option>
									          	  </select>
									          	  <select type="text" title="Were Restraints Used?" class="form-control" name="restraint">
							                      	<option value="">--Were Restraints Used?--</option>
									          	  	<option value="no">No</option>
									          	  	<option value="yes">Yes</option>
									          	  </select>
									          </div>
									        </div>
									    </div>
									    <div class="col-md-4">
									    	<div class="form-group">
									          <label>Was there Physical Injury</label>
									          <div class="input-group">
									              <div class="input-group-addon">
									                <i class="fa fa-plus-square"></i>
									              </div>
									          	  <select type="text" class="form-control" name="physicalinjury">
							                      	<option value="">--Choose--</option>
									          	  	<option value="no">No</option>
									          	  	<option value="yes">Yes</option>
									          	  </select>
									          </div>
									        </div>
									    </div>
									    <div class="col-md-4 hidden ireported-details">
									    	<div class="form-group">
									          <label>Date & Location of last incident report date</label>
									          <div class="input-group">
									              <div class="input-group-addon">
									                <i class="fa fa-clock-o"></i>
									              </div>
									          	  <input name="ireporteddate" type="text" data-datepicker="true" class="form-control" placeholder="Prior report date"/>
									          	  <textarea name="ireportedlocation" class="form-control" Placeholder="Where was the prior report made"></textarea>
									          </div>
									        </div>
									    </div>
									</div>
							    </div>
							    <div class="col-md-12">
							    	<div class="form-group">
							          <label>Incident Description</label>
							          <div class="input-group">
							              <div class="input-group-addon">
							                <i class="fa fa-street-view"></i>
							              </div>
							          	  <textarea name="incidentdetails" class="form-control" 
							          	  placeholder="The incident details."
							          	  rows="8"></textarea>
							          </div>
							        </div>
							    </div>
							    <h4 class="clear-both">Abuser Section</h4>
							    <div class="col-md-12">
							    	<div class="form-group">
							          <label>How many abusers <small>Change the number in the field below then click/tap the arrow link to the right to produce or reduce the number of abuser field displayed</small></label>
							          <div class="input-group">
							              <div class="input-group-addon">
							                <i class="fa fa-street-view"></i>
							              </div>
							              <input class="form-control" name="abusercount" type="number" value="1" min="1" max="20" data-valset="1,2,3,4" data-valcount="1" data-counter="true"/>
							          	  <div class="input-group-addon nopadding">
							      														
							      				<a href="##" data-name="abusercount_addlink" data-i-type data-limit="20" 
							      				onclick="multipleElGenerator('form[name=<?php echo $formtruetype;?>] a[data-name=abusercount_addlink]','','form[name=<?php echo $formtruetype;?>] div.abuser-field-hold',$('form[name=<?php echo $formtruetype;?>] div.abuser-field-hold .multi_content_hold').length,$('form[name=<?php echo $formtruetype;?>] input[name=abusercount]').val(),'form[name=<?php echo $formtruetype;?>] input[name=abusercount]')" class="bs-igicon blockdisplay bg-gradient-darkgreen background-color-darkgreen background-color-orangehover bg-orange-gradienthover color-white color-darkredhover">
							                    	<i class="fa fa-arrow-right"></i>
							      				</a>
							              </div>
							          </div>
							        </div>
							    </div>
							    <div class="col-md-12 abuser-field-hold">
							    	<div class="col-md-12 multi_content_hold" data-type="triggerprogenitor" data-cid="1" data-name="abuser">
							    		<h4 class="multi_content_countlabels">Abuser (Entry 1)</h4>
							    		<div class="col-sm-3">
							    			<div class="form-group">
							                    <label>Abuser Fullname</label>
							                    <div class="input-group">
							                      <div class="input-group-addon">
							                        <i class="fa fa-file-text"></i>
							                      </div>
							                      <input type="text" class="form-control" name="abuserfullname1" placeholder="Abuser fullname">
							                    </div><!-- /.input group -->
							                </div><!-- /.form group -->
							    		</div>
							    		<div class="col-sm-3">
							    			<div class="form-group">
							                    <label>Abuser Gender</label>
							                    <div class="input-group">
							                      <div class="input-group-addon">
							                        <i class="fa fa-file-text"></i>
							                      </div>
							                      <select type="text" class="form-control" name="abusergender1">
									          	  	<option value="">--Choose Gender--</option>
									          	  	<option value="male">Male</option>
									          	  	<option value="female">Female</option>
									          	  </select>
							                    </div><!-- /.input group -->
							                </div><!-- /.form group -->
							    		</div>
							    		<div class="col-sm-3">
							    			<div class="form-group">
							                    <label>Abuser Identifiable?</label>
							                    <div class="input-group">
							                      <div class="input-group-addon">
							                        <i class="fa fa-file-text"></i>
							                      </div>
							                      <select type="text" class="form-control" name="aidentifiable1">
							              			<option value="">--Choose--</option>
									          	  	<option value="no">No</option>
									          	  	<option value="yes">Yes</option>
									          	  </select>
							                    </div><!-- /.input group -->
							                </div><!-- /.form group -->
							    		</div>
							    		<div class="col-sm-3">
							    			<div class="form-group">
							                    <label>Abuser Relationship?</label>
							                    <div class="input-group">
							                      <div class="input-group-addon">
							                        <i class="fa fa-file-text"></i>
							                      </div>
							                      <select type="text" class="form-control" name="abuserrelation1">
							              			<option value="">--Choose--</option>
									          	  	<option value="Lover/Partner">Lover/Partner</option>
									          	  	<option value="Friend/Aquaintance">Friend/Aquaintance</option>
									          	  	<option value="Neighbour">Neighbour</option>
									          	  	<option value="Stranger">Stranger</option>
									          	  	<option value="Boss/Senior Colleague">Boss/Senior Colleague</option>
									          	  	<option value="Teacher/Lecturer">Teacher/Lecturer</option>
									          	  	<option value="Co-worker/Fellow Colleague">Co-worker/Fellow Colleague</option>
									          	  	<option value="Parent/Guardian">Parent/Guardian</option>
									          	  	<option value="Siblings">Siblings</option>
									          	  	<option value="uncle/aunty/other family members ">Uncle/Aunty/Other family members </option>
									          	  	<option value="Other">Other</option>
									          	  </select>
									          	  <input class="form-control hidden" type="text" data-hdeftype="hidden" name="abuserrelationdetails1" placeholder="Please Specify nature of relationship"/>
							                    </div><!-- /.input group -->
							                </div><!-- /.form group -->
							    		</div>
							    	</div>
							    	<?php
							    		if(isset($userset)&&$userset=="true"){
							    	?>
							    	<div class="col-md-12 disclaimer-section">
							    		<h4 class="disclaimer-heading">
						    				<?php echo $disclaimer_title;?>
							    		</h4>
							    		<p class="disclaimer-text">
					    					<?php echo $disclaimer;?>
							    		</p>
							    	</div>
							    	<?php
							    		}
							    	?>
							    	<input name="abuserdatamap" type="hidden" data-map="true" value="abuserfullname-:-input<|>
							      				abusergender-:-select<|>aidentifiable-:-select<|>abuserrelation-:-select<|>abuserrelationdetails-:-input">
							        <div name="abuserentrypoint" data-marker="true"></div>

							    </div>
							</div>
							<div class="col-sm-12">
				    			<div class="form-group">
				                    <label>Would you like to save this for later
				                    	editting? <small>it wont be submitted to the 
				                    	salvus platform till you're done with it</small>
				                    </label>
				                    <div class="input-group bg-gradient-darkgreen color-white">
				                      <div class="input-group-addon">
				                        <i class="fa fa-save"></i>
				                      </div>
				                      <select type="text" class="form-control" name="save">
				              			<option value="">Save this incident??</option>
						          	  	<option value="saved">Yes</option>
						          	  </select>
				                    </div><!-- /.input group -->
				                </div><!-- /.form group -->
				    		</div>
			                <input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>
		                    <input type="hidden" name="extraformdata" value="reporttype-:-input<|>
		                      userid-:-input<|>
		                      incidentfrequency-:-select<|>
		                      incidentstarttime-:-input-:-[group-|-incidentfrequency-|-select-|-more_than_once:*:ongoing]<|>
		                      incidentreporttime-:-input<|>
		                      incidentlocation-:-textarea<|>
		                      incidentnature-:-select<|>
		                      incidentnaturedetails-:-input-:-[group-|-incidentnature-|-select-|-other]<|>
		                      weaponuse-:-select<|>
		                      weapon-:-select-:-[group-|-weaponuse-|-select-|-yes-|-threat-|-select-|-*null*:*:no-|-restraint-|-select-|-*null*:*:no]<|>
		                      threat-:-select-:-[group-|-weaponuse-|-select-|-yes-|-weapon-|-select-|-*null*:*:no-|-restraint-|-select-|-*null*:*:no]<|>
		                      restraint-:-select-:-[group-|-weaponuse-|-select-|-yes-|-threat-|-select-|-*null*:*:no-|-weapon-|-select-|-*null*:*:no]<|>
		                      physicalinjury-:-select<|>
		                      ireported-:-select<|>
		                      ireporteddate-:-input-:-[group-|-ireported-|-select-|-yes]<|>
		                      ireportedlocation-:-textarea-:-[group-|-ireported-|-select-|-yes]<|>
		                      ireportedaid-:-select<|>
		                      incidentdetails-:-textarea<|>
		                      egroup|data-:-[abusercount>|<
                      				abuserfullname-|-input>|<
                      				abusergender-|-select>|<
                      				aidentifiable-|-select>|<
                      				abuserrelation-|-select>|<
                      				abuserrelationdetails-|-input-|-(group-*-abuserrelation-*-select-*-Other)]-:-groupfall[1-2,2-3,3-4,4-1,5]"/>
		                    <input type="hidden" name="errormap" value="reporttype-:-Please Specify the nature of tthe incident report<|>
			                    userid-:-Specify the user reporting the incident<|>
			                    incidentfrequency-:-Specify the frequency of this incident<|>
			                    incidentstarttime-:-Specify the date the incident first occured<|>
			                    incidentreporttime-:-Specify the day the current incident being reported happened<|>
			                    incidentlocation-:-Please provide the location information or address of where the incident being reported occured<|>
			                    incidentnature-:-Choose the nature of the incident from the list.<|>
			                    incidentnaturedetails-:-Please provide more details on the nature of the incident, since its not on the list presented<|>
			                    weaponuse-:-Specify if threats, weapons or restraints were used<|>
			                    weapon-:-Specify if a weapon was used<|>
			                    threat-:-Specify if threats were used<|>
			                    restraint-:-Specify if restraints were used<|>
			                    physicalinjury-:-Specify if there was physical injury<|>
			                    ireported-:-Specify if this incident has been reported before<|>
			                    ireporteddate-:-Please Provide the date the last report was made.<|>
			                    ireportedlocation-:-Please give the location where the last report was made<|>
			                    ireportedaid-:-Specify if help was given since the incident has been reported<|>
			                    incidentdetails-:-Please provide a detailed description of the ordeal.<|>
			                    egroup|data-:-[Please Provide the abusers fullname>|<Choose the gender>|<Specify if the abuser is identifiable>|<Specify relationship to abuser>|<Please provide more details about the relationship]"/>
			                <div class="col-md-12 clearboth">
				                <div class="box-footer">
				                    <input type="button" class="btn btn-danger" name="incidentsubmit" data-formdata="<?php echo $formtruetype;?>" onclick="submitCustom('<?php echo $formtruetype;?>','complete')" value="Create Entry"/>
				                </div>
				            </div>
			            </form>

			            <form name="<?php echo $formtruetype."tp";?>" class="hidden thirdpartyincident" method="POST" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/basicsignup.php">
							<input type="hidden" name="entryvariant" value="<?php echo $varianttype;?>"/>
							<input type="hidden" name="reporttype" value="thirdparty"/>
							<input type="hidden" name="userid" value="<?php echo $userid;?>"/>
							<?php
								if ($userset=="true") {
		                      	  		# code...
							?>
							<input type="hidden" name="retval" value="<?php echo $host_addr;?>userdashboard.php"/>
							<?php
								}
							?>
							<h3>Third Party Incident Report</h3>
							<div class="col-md-12">
							    <div class="col-md-12">
							    	<div class="col-md-4">
							        	<div class="form-group">
							              <label> Survivor First Name</label>
							              <div class="input-group">
							                  <div class="input-group-addon">
							                    <i class="fa fa-file-text"></i>
							                  </div>
							              	  <input type="text" class="form-control" name="firstname" placeholder="First Name"/>
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
							              	  <input type="text" class="form-control" name="middlename" placeholder="Middle Name"/> 
							              </div>
							            </div>
							        </div>
									<div class="col-md-4">
							        	<div class="form-group">
							              <label>Last Name</label>
							              <div class="input-group">
							                  <div class="input-group-addon">
							                    <i class="fa fa-file-text"></i>
							                  </div>
							              	  <input type="text" class="form-control" name="lastname" placeholder="Last Name"/>
							              </div>
							            </div>
							        </div>
							    </div>
							    <div class="col-md-12">
									<div class="col-md-4">
							        	<div class="form-group">
							              <label>Survivors Gender</label>
							              <div class="input-group">
							                  <div class="input-group-addon">
							                    <i class="fa fa-file-text"></i>
							                  </div>
							              	  <select type="text" class="form-control" name="gender" placeholder="First Name">
							              	  	<option value="">--Choose Gender--</option>
							              	  	<option value="male">Male</option>
							              	  	<option value="female">Female</option>
							              	  	<option value="other">Other</option>
							              	  </select>
							              </div>
							            </div>
							        </div>
							        <div class="col-md-4">
							        	<div class="form-group">
							              <label>Survivor Living with Disability?</label>
							              <div class="input-group">
							                  <div class="input-group-addon">
							                    <i class="fa fa-wheelchair"></i>
							                  </div>
							              	  <select class="form-control" name="sdisability">
							              	  	<option value="">--Choose--</option>
							              	  	<option value="yes">Yes</option>
							              	  	<option value="no">No</option>
							              	  </select>
										      <input name="sdisabilitydetails" type="text" class="form-control hidden" placeholder="Specify disability."/>
							              </div>
							            </div>
							        </div>
							        <div class="col-md-4">
							        	<div class="form-group">
							              <label>Survivors Age</label>
							              <div class="input-group">
							                  <div class="input-group-addon">
							                    <i class="fa fa-file-text"></i>
							                  </div>
							              	  <input type="number" class="form-control" name="age" min="1"max="120" placeholder="The Age (number only)"/> 
							              </div>
							            </div>
							        </div>
							    </div>
							    <div class="col-md-12">
							        <div class="col-md-4">
							        	<div class="form-group">
							              <label>Survivor Phone Number</label>
							              <div class="input-group">
							                  <div class="input-group-addon">
							                    <i class="fa fa-phone"></i>
							                  </div>
							              	  <input type="text" class="form-control" name="phonenumber" placeholder="Phone Number"/>
							              </div>
							            </div>
							        </div>
							        <div class="col-md-4">
							        	<div class="form-group">
							              <label>Email Address</label>
							              <div class="input-group">
							                  <div class="input-group-addon">
							                    <i class="fa fa-at"></i>
							                  </div>
							              	  <input type="email" class="form-control" name="email" data-evalidate="true" placeholder="Email address"/>
							              </div>
							            </div>
							        </div>
									<div class="col-md-4">
							        	<div class="form-group">
							              <label>Best way to reach survivor</label>
							              <div class="input-group">
							                  <div class="input-group-addon">
							                    <i class="fa fa-street-view"></i>
							                  </div>
							              	  <select type="text" class="form-control" name="pcmethod">
							              	  	<option value="">--Choose--</option>
							              	  	<option value="email">Email</option>
							              	  	<option value="phone">Telephone</option>
							              	  </select>
							              </div>
							            </div>
							        </div>
							    </div>
							    <div class="col-md-12">

							    	<div class="form-group">
							          <label>Survivor Guardian Contact Address</label>
							          <div class="input-group">
							              <div class="input-group-addon">
							                <i class="fa fa-map-marker"></i>
							              </div>
							              <select name="state" id="state" class="form-control">
												<option value="">Select State</option>
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
							          	  <textarea class="form-control" name="address" placeholder="Full address"></textarea>
							          </div>
							        </div>
							    </div>
							    <div class="col-md-6">
							    	<div class="form-group">
							          <label>When did the incident occur</label>
							          <div class="input-group">
							              <div class="input-group-addon">
							                <i class="fa fa-clock-o"></i>
							              </div>
							          	  <input class="form-control" name="incidentreporttime" data-datepicker="true" placeholder="Specify the date of the current incident."/>
							          </div>
							        </div>
							    </div>
							    <div class="col-md-6">
							    	<div class="form-group">
							          <label>Nature of Incident</label>
							          <div class="input-group">
							              <div class="input-group-addon">
							                <i class="fa fa-street-view"></i>
							              </div>
							          	  <select class="form-control" name="incidentnature">
							          	  	<option value="">--Choose--</option>
							          	  	<option value="rape">Rape</option>
							          	  	<option value="attempted rape">Attempted Rape</option>
							          	  	<option value="stalking">Stalking</option>
							          	  	<option value="sexual harassment">Sexual Harassment</option>
							          	  	<option value="other">Other</option>
							          	  </select>
							          	  <input name="incidentnaturedetails" type="text" class="form-control hidden" placeholder="Specify 'Other' details" />
							          </div>
							        </div>
							    </div>
							    <div class="col-md-12">
							    	<div class="form-group">
							          <label>How was the abuse discovered</label>
							          <div class="input-group">
							              <div class="input-group-addon">
							                <i class="fa fa-street-view"></i>
							              </div>
							          	  <textarea name="incidentdetails" class="form-control" placeholder="The incident details."></textarea>
							          </div>
							        </div>
							    </div>
							    <h4 class="clear-both">Abuser Section</h4>
							    <div class="col-md-12">
							    	<div class="form-group">
							          <label>How many abusers <small>Change the number in the field below then click/tap the arrow link to the right to produce or reduce the number of abuser field displayed</small></label>
							          <div class="input-group">
							              <div class="input-group-addon">
							                <i class="fa fa-street-view"></i>
							              </div>
							              <input class="form-control" name="abusercount" type="number" value="1" min="1" max="20" data-valset="1,2,3,4" data-valcount="1" data-counter="true"/>
							          	  <div class="input-group-addon nopadding">
							      														
							      				<a href="##" data-name="abusercount_addlink" data-i-type data-limit="20" 
							      				onclick="multipleElGenerator('form[name=<?php echo $formtruetype."tp";?>] a[data-name=abusercount_addlink]','','form[name=<?php echo $formtruetype."tp";?>] div.abuser-field-hold',$('form[name=<?php echo $formtruetype."tp";?>] div.abuser-field-hold .multi_content_hold').length,$('form[name=<?php echo $formtruetype."tp";?>] input[name=abusercount]').val(),'form[name=<?php echo $formtruetype."tp";?>] input[name=abusercount]')" class="bs-igicon blockdisplay bg-gradient-darkgreen background-color-darkgreen background-color-orangehover bg-orange-gradienthover color-white color-darkredhover">
							                    	<i class="fa fa-arrow-right"></i>
							      				</a>
							              </div>
							          </div>
							        </div>
							    </div>
							    <div class="col-md-12 abuser-field-hold">
							    	<div class="col-md-12 multi_content_hold" data-type="triggerprogenitor" data-cid="1" data-name="abuser">
							    		<h4 class="multi_content_countlabels">Abuser (Entry 1)</h4>
							    		<div class="col-sm-3">
							    			<div class="form-group">
							                    <label>Abuser Fullname</label>
							                    <div class="input-group">
							                      <div class="input-group-addon">
							                        <i class="fa fa-file-text"></i>
							                      </div>
							                      <input type="text" class="form-control" name="abuserfullname1" placeholder="Abuser fullname">
							                    </div><!-- /.input group -->
							                </div><!-- /.form group -->
							    		</div>
							    		<div class="col-sm-3">
							    			<div class="form-group">
							                    <label>Abuser Gender</label>
							                    <div class="input-group">
							                      <div class="input-group-addon">
							                        <i class="fa fa-file-text"></i>
							                      </div>
							                      <select type="text" class="form-control" name="abusergender1">
									          	  	<option value="">--Choose Gender--</option>
									          	  	<option value="male">Male</option>
									          	  	<option value="female">Female</option>
									          	  </select>
							                    </div><!-- /.input group -->
							                </div><!-- /.form group -->
							    		</div>
							    		<div class="col-sm-3">
							    			<div class="form-group">
							                    <label>Abuser Identifiable?</label>
							                    <div class="input-group">
							                      <div class="input-group-addon">
							                        <i class="fa fa-file-text"></i>
							                      </div>
							                      <select type="text" class="form-control" name="aidentifiable1">
							              			<option value="">--Choose--</option>
									          	  	<option value="no">No</option>
									          	  	<option value="yes">Yes</option>
									          	  </select>
							                    </div><!-- /.input group -->
							                </div><!-- /.form group -->
							    		</div>
							    		<div class="col-sm-3">
							    			<div class="form-group">
							                    <label>Abuser Relationship?</label>
							                    <div class="input-group">
							                      <div class="input-group-addon">
							                        <i class="fa fa-file-text"></i>
							                      </div>
							                      <select type="text" class="form-control" name="abuserrelation1">
							              			<option value="">--Choose--</option>
									          	  	<option value="Lover/Partner">Lover/Partner</option>
									          	  	<option value="Friend/Aquaintance">Friend/Aquaintance</option>
									          	  	<option value="Neighbour">Neighbour</option>
									          	  	<option value="Stranger">Stranger</option>
									          	  	<option value="Boss/Senior Colleague">Boss/Senior Colleague</option>
									          	  	<option value="Teacher/Lecturer">Teacher/Lecturer</option>
									          	  	<option value="Co-worker/Fellow Colleague">Co-worker/Fellow Colleague</option>
									          	  	<option value="Parent/Guardian">Parent/Guardian</option>
									          	  	<option value="Siblings">Siblings</option>
									          	  	<option value="uncle/aunty/other family members ">Uncle/Aunty/Other family members </option>
									          	  	<option value="Other">Other</option>
									          	  </select>
									          	  <input class="form-control hidden" type="text" data-hdeftype="hidden" name="abuserrelationdetails1" placeholder="Please Specify nature of relationship"/>
							                    </div><!-- /.input group -->
							                </div><!-- /.form group -->
							    		</div>
							    	</div>
							    	<input name="abuserdatamap" type="hidden" data-map="true" value="abuserfullname-:-input<|>
							      				abusergender-:-select<|>aidentifiable-:-select<|>abuserrelation-:-select<|>abuserrelationdetails-:-input">
							        <div name="abuserentrypoint" data-marker="true"></div>

							    </div>

							</div>
							<?php
					    		if(isset($userset)&&$userset=="true"){
					    	?>
					    	<div class="col-md-12 disclaimer-section">
					    		<h4 class="disclaimer-heading">
					    			<?php echo $disclaimer_title;?>
					    		</h4>
					    		<p class="disclaimer-text">
					    			<?php echo $disclaimer;?>
					    		</p>
					    	</div>
					    	<?php
					    		}
					    	?>
							<input type="hidden" name="formdata" value="<?php echo $formtruetype."tp";?>"/>
							<input type="hidden" name="extraformdata" value="reporttype-:-input<|>
							  	userid-:-input<|>
							  	firstname-:-input<|>
								middlename-:-input<|>
								lastname-:-input<|>
								gender-:-select<|>
								sdisability-:-select<|>
								sdisabilitydetails-:-input-:-[group-|-sdisability-|-select-|-yes]<|>
								age-:-input<|>
								phonenumber-:-input<|>
								email-:-input<|>
								spcmethod-:-select<|>
								state-:-select<|>
								address-:-textarea<|>
								incidentreporttime-:-input<|>
			                    incidentnature-:-select<|>
			                    incidentnaturedetails-:-input-:-[group-|-incidentnature-|-select-|-other]<|>
								incidentdetails-:-textarea<|>
							  	egroup|data-:-[abusercount>|<
										abuserfullname-|-input>|<
										abusergender-|-select>|<
										aidentifiable-|-select>|<
										abuserrelation-|-select>|<
										abuserrelationdetails-|-input-|-(group-*-abuserrelation-*-select-*-Other)]-:-groupfall[1-2,2-3,3-4,4-1,5]"/>
							<input type="hidden" name="errormap" value="reporttype-:-Please Specify the nature of tthe incident report<|>
							    userid-:-Specify the user reporting the incident<|>
							    firstname-:-Please provide Firstname<|>
							    middlename-:-NA<|>
							    lastname-:-Please Provide last name<|>
							    gender-:-Choose gender<|>
							    sdisability-:-Specify if the survivor has a disability<|>
							    sdisabilitydetails-:-Specify disability details<|>
							    age-:-Specify the age<|>
							    phonenumber-:-Please provide a phone number<|>
							    email-:-Provide a valid email address<|>
							    pcmethod-:-NA<|>
							    state-:-Select a state<|>
							    address-:-Please Provide an address.<|>
							    incidentreporttime-:-Specify the date when the incident occured<|>
			                    incidentnature-:-Choose the nature of the incident from the list.<|>
			                    incidentnaturedetails-:-Please provide more details on the nature of the incident, since its not on the list presented<|>
							    incidentdetails-:-State how the abuse was discovered<|>
							    egroup|data-:-[Please Provide the abusers fullname>|<Choose the gender>|<Specify if the abuser is identifiable>|<Specify relationship to abuser>|<Please provide more details about the relationship]"/>
			                <div class="col-md-12 clearboth">
				                <div class="box-footer">
				                    <input type="button" class="btn btn-danger" name="incidentsubmit" data-formdata="<?php echo $formtruetype."tp";?>" onclick="submitCustom('<?php echo $formtruetype."tp";?>','complete')" value="Create Entry"/>
				                </div>
				            </div>
			            </form>
			        </div>
			    </div>
			    <script data-type="multiscript">
			    	$(document).ready(function(){
				    	$('[data-datetimepicker]').datetimepicker({
				            format:"YYYY-MM-DD HH:mm",
				            keepOpen:true
			        	})
			        	$('[data-datepicker]').datetimepicker({
				            format:"YYYY-MM-DD",
				            keepOpen:true
				            // showClose:true
				            // debug:true
			        	});
			        	$('[data-timepicker]').datetimepicker({
				            format:"HH:mm",
				            keepOpen:true
			        	});
			        	$('select[name=userlist]').select2({
	                        theme: "bootstrap",
	                        ajax: {
							    url: "<?php echo $host_addr?>/snippets/display.php",
							    dataType: 'json',
							    delay: 250,
							    data: function (params) {
							      return {
							        q: params.term, // search term
							        page: params.page
							      };
							    },
							    processResults: function (data, params) {
							      // parse the results into the format expected by Select2
							      // since we are using custom formatting functions we do not need to
							      // alter the remote JSON data, except to indicate that infinite
							      // scrolling can be used
							      params.page = params.page || 1;

							      return {
							        results: data.items,
							        pagination: {
							          more: (params.page * 30) < data.total_count
							        }
							      };
							    },
							    cache: true
							  },
							  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
							  minimumInputLength: 1
	                    });
			    	})
				</script>
			</div>
			<div class="panel box overflowhidden box-primary <?php echo $hideedit;?>">
			    <div class="box-header with-border">
			      <h4 class="box-title">
			        <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#EditBlock">
			          <i class="fa fa-gear"></i> Edit Incident
			        </a>
			      </h4>
			    </div>
			    <div id="EditBlock" class="panel-collapse collapse <?php echo $editin;?>">
			      <div class="box-body">
			          <div class="row">
			          	
			            <div class="col-md-12">

			              <?php
			                echo $outsdata['adminoutput2'];
			              ?>
			            </div>
			        </div>
			      </div>
			    </div>
			</div>
		</div>
	</div>
	<script>
	
	</script>
			</div>