<?php 

	// get the current edit data and use the capture variable to populate the edit 
	// form below
	// this data is created when this snippet is called or directly within it to be 
	// rendered
	$doeditform="no";
	$genderdisabled="";
	if(isset($edittype)&&$edittype!==""){

	}else{
		$edittype="editincidentadmin";
		
	}
	if(!isset($rowdata)){
		if(isset($oid)&&is_numeric($oid)&&$oid>0){
			$doeditform="yes";
			$edata=getSingleIncident($oid);
			
			// create previous abuser content here
			$abuserprevout='';
			$abusercount=0;
			$abuseselectoptions="";
			// echo $edata['abusercount'].'<br>';
			$p=0;
			if($edata['abusercount']>0){
				for($t=0;$t<$edata['abusercount'];$t++){
					$hidedetails="";
					$i=$t+1;
					$abuserid=isset($edata['abuserdata'][$t]['id'])?$edata['abuserdata'][$t]['id']:"";
					$abuserfullname=isset($edata['abuserdata'][$t]['fullname'])?$edata['abuserdata'][$t]['fullname']:"";
					$abusergender=isset($edata['abuserdata'][$t]['gender'])?$edata['abuserdata'][$t]['gender']:"";// select
					$aidentifiable=isset($edata['abuserdata'][$t]['aidentifiable'])?$edata['abuserdata'][$t]['aidentifiable']:"";// select
					$abuserrelation=isset($edata['abuserdata'][$t]['abuserrelationship'])?$edata['abuserdata'][$t]['abuserrelationship']:"";// select
					$abuserrelationdetails=isset($edata['abuserdata'][$t]['arelationshipdetails'])?$edata['abuserdata'][$t]['arelationshipdetails']:"";
					
					if($abuserrelation!=="Other"){
						$hidedetails="hidden";
					}
					if($abuserid!==""&&$abuserfullname!==""){
						$abuserprevout.='
							<div class="col-md-12 abuser-field-hold _edit">
						    	<div class="col-md-12 multi_content_hold">
				            		<h4 class="multi_content_countlabels"> Edit Abuser (Entry '.$i.')</h4>
				            		<input type="hidden" name="abuserid'.$i.'" value="'.$abuserid.'"/>
				            		<div class="col-sm-3">
				            			<div class="form-group">
				                      		<label>Abuser Fullname</label>
				                      		<div class="input-group">
						                        <div class="input-group-addon">
						                          <i class="fa fa-file-text"></i>
						                        </div>
						                        <input type="text" class="form-control" name="editabuserfullname'.$i.'" value="'.$abuserfullname.'" placeholder="editabuser fullname">
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
				                        		<select type="text" class="form-control" name="editabusergender'.$i.'">
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
				                        		<select type="text" class="form-control" name="editaidentifiable'.$i.'">
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
				                        		<select type="text" class="form-control" name="editabuserrelation'.$i.'">
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
									          	<input class="form-control '.$hidedetails.'" type="text" data-hdeftype="hidden" value="'.$abuserrelationdetails.'" name="editabuserrelationdetails1" placeholder="Please Specify nature of relationship"/>
				                      		</div><!-- /.input group -->
				                  		</div><!-- /.form group -->
				            		</div>
				            		<div class="col-md-12">
										<div class="form-group">
											<label>Delete this?</label>
											<div class="input-group">
												<div class="input-group-addon">
													<i class="fa fa-trash"></i>
												</div>
												<select type="text" class="form-control" name="abuserstatus'.$i.'">
													<option value="">--Choose--</option>
													<option value="delete">Delete</option>
												</select>
											</div>
										</div>
									</div>
			            		</div>
							</div>
						';
						$abuseselectoptions.='
							$("form[name='.$formtruetype.'] div.abuser-field-hold._edit select[name=editabusergender'.$i.']").val("'.$abusergender.'");
							$("form[name='.$formtruetype.'] div.abuser-field-hold._edit select[name=editaidentifiable'.$i.']").val("'.$aidentifiable.'");
							$("form[name='.$formtruetype.'] div.abuser-field-hold._edit select[name=editabuserrelation'.$i.']").val("'.$abuserrelation.'");
						';
						$p++;
					}
				}
				$abusercount=$p;
				// $abuserprevout="";
				if($p>0){
					$abuserprevout='
						<div class="box-group overflowhidden" id="editabusers">
							<div class="panel box  box-danger">
							    <div class="box-header with-border">
							        <h4 class="box-title">
							          <a data-toggle="collapse" data-parent="#editabusers" href="#editprevabusers">
							            <i class="fa fa-gear fa-spin"></i> Edit Previously inputted Abusers
							          </a>
							        </h4>
							    </div>
							    <div id="editprevabusers" class="panel-collapse collapse">
							    	<input type="hidden" name="editabusercount" value="'.$abusercount.'"/>
									'.$abuserprevout.'
							    </div>
							</div>
						</div>
					';
				}
				// $abusercount=$i+;
			}
			$edata['incidentfrequency']==""||$edata['incidentfrequency']=="once"?$ifhid="hidden":$ifhid="";
			$edata['incidentnature']==""||$edata['incidentnature']=="no"?$inhid="hidden":$inhid="";
			$edata['weaponuse']==""||$edata['weaponuse']=="no"?$whid="hidden":$whid="";
			$edata['ireported']==""||$edata['ireported']=="no"?$irhid="hidden":$irhid="";
			$edata['sdisability']==""||$edata['sdisability']=="no"?$sdhid="hidden":$sdhid="";
			$selectionscripts='
						'.$abuseselectoptions.'
						$("form[name='.$formtruetype.'] select[name=incidentfrequency]").val("'.$edata['incidentfrequency'].'");
						$("form[name='.$formtruetype.'] select[name=incidentnature]").val("'.$edata['incidentnature'].'");
						$("form[name='.$formtruetype.'] select[name=weaponuse]").val("'.$edata['weaponuse'].'");
						$("form[name='.$formtruetype.'] select[name=threat]").val("'.$edata['threats'].'");
						$("form[name='.$formtruetype.'] select[name=weapon]").val("'.$edata['weapons'].'");
						$("form[name='.$formtruetype.'] select[name=restraint]").val("'.$edata['restraints'].'");
						$("form[name='.$formtruetype.'] select[name=physicalinjury]").val("'.$edata['physicalinjury'].'");
						$("form[name='.$formtruetype.'] select[name=ireported]").val("'.$edata['ireported'].'");
						$("form[name='.$formtruetype.'] select[name=ireportedaid]").val("'.$edata['ireportedaid'].'");
						$("form[name='.$formtruetype.'] select[name=sdisability]").val("'.$edata['sdisability'].'");
						

						$("form[name='.$formtruetype.'] select[name=gender]").val("'.$edata['sgender'].'");
						$("form[name='.$formtruetype.'] select[name=pcmethod]").val("'.$edata['spcmethod'].'");
						$("form[name='.$formtruetype.'] select[name=sstate]").val("'.$edata['rstate'].'");
						$("form[name='.$formtruetype.'] select[name=state]").val("'.$edata['rstate'].'");
						$("form[name='.$formtruetype.'] select[name=status]").val("'.$edata['status'].'");
						
			';
			$estatus='
				<option value="">--Choose--</option>
				<option value="active">Active</option>
				<option value="disabled">Disabled</option>
			';
			$estext="";
			$esicon="trash";
			if($edata['status']=="saved"){
				$estatus='
					<option value="saved">Saved</option>
					<option value="active">Submit to Salvus</option>
					<option value="disabled">Disabled</option>
				';
				$esicon="save";
				$estext='';

			}
			$statusmanagement='
				<div class="col-md-12">
						<div class="form-group">
								<label>Status'.$estext.'</label>
								<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-'.$esicon.'"></i>
										</div>
										<select type="text" class="form-control" name="status">
											
											'.$estatus.'
										</select>
								</div>
							</div>
					</div>
			';

			if($edittype=="editserviceprovideracc"){
				$profileactivation="";
				$statusmanagement="";
			}
		}else{
			$doeditform="no";
			$errblock="1";			
		}
	}else{
		$doeditform="yes";
	}

	if(!isset($formtruetype)){
		$doeditform="no";
		$errblock="2";			

	}
	if(!isset($host_addr)){
		$doeditform="no";
		$errblock="3";			

	}
	if($doeditform=="yes"){

?>
<div id="form" class="background-color-white">

<?php if($edata['reporttype']=="self"){?>
	<form name="<?php echo $formtruetype;?>" method="POST" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/edit.php">
		<input type="hidden" name="entryvariant" value="<?php echo $edittype?>"/>
		<input type="hidden" name="entryid" value="<?php echo $oid?>"/>
		<input type="hidden" name="reporttype" value="<?php echo $edata['reporttype']?>"/>
		<input type="hidden" name="userid" value="<?php echo $edata['userid'];?>"/>
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
									<input class="form-control <?php echo $ifhid;?>" name="incidentstarttime" value="<?php echo $edata['incidentstarttime']?>" data-datepicker="true" placeholder="Specify the day the incident started"/>
									<input class="form-control" name="incidentreporttime" value="<?php echo $edata['incidentreporttime']?>" data-datepicker="true" placeholder="Specify the date of the current incident."/>
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
									<textarea class="form-control" name="incidentlocation" placeholder="Provide incident location"><?php echo $edata['incidentlocation']?></textarea>
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
										<input name="incidentnaturedetails" type="text" value="<?php echo $edata['incidentnaturedetails']?>" class="form-control <?php echo $inhid;?>" placeholder="Specify 'Other' details" />
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
						<div class="col-md-4 <?php echo $whid;?> weapons-details">
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
						<div class="col-md-4 <?php echo $irhid;?> ireported-details">
							<div class="form-group">
									<label>Date & Location of last incident report date</label>
									<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-clock-o"></i>
											</div>
											<input name="ireporteddate" type="text" data-datepicker="true" value="<?php echo $edata['ireporteddate']?>" class="form-control" placeholder="Prior report date"/>
											<textarea name="ireportedlocation" class="form-control" Placeholder="Where was the prior report made"><?php echo $edata['ireportedlocation']?></textarea>
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
									<textarea name="incidentdetails" class="form-control" placeholder="The incident details."><?php echo $edata['details'];?></textarea>
							</div>
						</div>
			</div>
			<?php echo $abuserprevout;?>
			<h4 class="clear-both">New Abuser Section</h4>
			<div class="col-md-12">
				<div class="form-group">
						<label>How many abusers <small>Change the number in the field below then click/tap the arrow link to the right to produce or reduce the number of abuser field displayed</small></label>
						<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-street-view"></i>
								</div>
								<input class="form-control" name="abusercount" type="number" value="1" min="1" max="20" data-counter="true"/>
								<div class="input-group-addon nopadding">
																		
								<a href="##" data-name="abusercount_addlink" data-i-type data-limit="20" 
								onclick="multipleElGenerator('form[name=<?php echo $formtruetype;?>] a[data-name=abusercount_addlink]','','form[name=<?php echo $formtruetype;?>] div.abuser-field-hold._new',$('form[name=<?php echo $formtruetype;?>] div.abuser-field-hold._new .multi_content_hold').length,$('form[name=<?php echo $formtruetype;?>] input[name=abusercount]').val(),'form[name=<?php echo $formtruetype;?>] input[name=abusercount]')" class="bs-igicon blockdisplay bg-gradient-darkgreen background-color-darkgreen background-color-orangehover bg-orange-gradienthover color-white color-darkredhover">
												<i class="fa fa-arrow-right"></i>
								</a>
								</div>
						</div>
					</div>
			</div>
			<div class="col-md-12 abuser-field-hold _new">
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
		<?php echo $statusmanagement;?>
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
						<input type="button" class="btn btn-danger" name="Incident" data-formdata="<?php echo $formtruetype;?>" onclick="submitCustom('<?php echo $formtruetype;?>','complete')" value="Update"/>
				</div>
		</div>
	</form>
<?php }else if($edata['reporttype']=="thirdparty"){?>
	<form name="<?php echo $formtruetype;?>" method="POST" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/edit.php">
		<input type="hidden" name="entryvariant" value="<?php echo $edittype?>"/>
		<input type="hidden" name="entryid" value="<?php echo $oid?>"/>
		<input type="hidden" name="reporttype" value="<?php echo $edata['reporttype']?>"/>
		<input type="hidden" name="userid" value="<?php echo $edata['userid']?>"/>
		<div class="col-md-12">
			<div class="col-md-12">
				<div class="col-md-4">
						<div class="form-group">
							<label> Survivor First Name</label>
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-file-text"></i>
								</div>
									<input type="text" class="form-control" name="firstname" value="<?php echo $edata['firstname'];?>" placeholder="First Name"/>
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
									<input type="text" class="form-control" name="middlename" value="<?php echo $edata['middlename'];?>" placeholder="Middle Name"/> 
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
									<input type="text" class="form-control" name="lastname" value="<?php echo $edata['lastname'];?>" placeholder="Last Name"/>
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
									<input name="sdisabilitydetails" type="text" value="<?php echo $edata['sdisabilitydetails'];?>" class="form-control <?php echo $sdhid;?>" placeholder="Specify disability."/>
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
									<input type="number" class="form-control" name="age" min="1"max="120" value="<?php echo $edata['sage'];?>" placeholder="The Age (number only)"/> 
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
									<input type="text" class="form-control" name="phonenumber" value="<?php echo $edata['sphone'];?>" placeholder="Phone Number"/>
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
									<input type="email" class="form-control" name="email" data-evalidate="true" value="<?php echo $edata['semail'];?>" placeholder="Email address"/>
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
						<label>Survivor Contact Address</label>
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
							<textarea class="form-control" name="address" placeholder="Full address"><?php echo $edata['saddress'];?></textarea>
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
								<input class="form-control" name="incidentreporttime" value="<?php echo $edata['incidentreporttime'];?>" data-datepicker="true" placeholder="Specify the date of the current incident."/>
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
							<input name="incidentnaturedetails" type="text" value="<?php echo $edata['incidentnaturedetails']?>" class="form-control <?php echo $inhid;?>" placeholder="Specify 'Other' details" />
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
									<textarea name="incidentdetails" class="form-control" placeholder="The incident details."><?php echo $edata['details'];?></textarea>
							</div>
					</div>
			</div>
			<?php echo $abuserprevout;?>
			<h4 class="clear-both">New Abuser Section</h4>
			<div class="col-md-12">
				<div class="form-group">
						<label>How many abusers <small>Change the number in the field below then click/tap the arrow link to the right to produce or reduce the number of abuser field displayed</small></label>
						<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-street-view"></i>
								</div>
								<input class="form-control" name="abusercount" type="number" value="1" min="1" max="20" data-counter="true"/>
								<div class="input-group-addon nopadding">                 
									<a href="##" data-name="abusercount_addlink" data-i-type data-limit="20" 
									onclick="multipleElGenerator('form[name=<?php echo $formtruetype;?>] a[data-name=abusercount_addlink]','','form[name=<?php echo $formtruetype;?>] div.abuser-field-hold._new',$('form[name=<?php echo $formtruetype;?>] div.abuser-field-hold._new .multi_content_hold').length,$('form[name=<?php echo $formtruetype;?>] input[name=abusercount]').val(),'form[name=<?php echo $formtruetype;?>] input[name=abusercount]')" class="bs-igicon blockdisplay bg-gradient-darkgreen background-color-darkgreen background-color-orangehover bg-orange-gradienthover color-white color-darkredhover">
													<i class="fa fa-arrow-right"></i>
									</a>
								</div>
						</div>
					</div>
			</div>
			<div class="col-md-12 abuser-field-hold _new">
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
		
		<?php echo $statusmanagement;?>
		<input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>
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
						<input type="button" class="btn btn-danger" name="Incident" data-formdata="<?php echo $formtruetype;?>" onclick="submitCustom('<?php echo $formtruetype;?>','complete')" value="Update"/>
				</div>
		</div>
	</form>
<?php }?>

	<script data-type="multiscript">
			$(document).ready(function(){
				$('[data-datetimepicker]').datetimepicker({
							format:"YYYY-MM-DD HH:mm",
							keepOpen:true
					})
					$('[data-datepicker]').datetimepicker({
							format:"YYYY-MM-DD",
							keepOpen:true
					});
					$('[data-timepicker]').datetimepicker({
							format:"HH:mm",
							keepOpen:true
					});
					<?php echo $selectionscripts;?>
			})
	</script>
</div>
<?php
	}else{
?>
<div id="form" class="background-color-white">
	<form name="<?php echo $formtruetype;?>" method="POST" onSubmit="return false" enctype="multipart/form-data" action="../snippets/basicsignup.php">
		<input type="hidden" name="entryvariant" value="edittype"/>
		<div class="col-md-12">
			<div class="callout callout-danger">
							<h4>An error!</h4>
							<p>
								There was a problem creating the edit segment you requested.<br>
								The failing block in the edit snippet is: Block<?php echo $errblock;?>
							</p>
					</div>
			</div>

</div>

<?php
	}
?>