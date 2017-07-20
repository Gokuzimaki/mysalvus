<?php
$errmsg="NO VALID SESSION DETECTED, LOGOUT THEN LOGIN AGAIN TO OBTAIN ACCESS.";
$clientid=$_SESSION['clientimysalvus'.$_SESSION['clienthmysalvus'].''];
$udata=getSingleUserPlain($clientid);
// get current case data
$biznature=$udata['businessnature'];
$bizstate=$udata['state'];
$cquery="SELECT * FROM cases WHERE incidentid='$iid' AND spid='$clientid' AND spnature='$biznature' AND resolutionstatus='ongoing'";
$qdata=briefquery($cquery,__LINE__,"mysqli");
$casecount=$qdata['numrows'];
// var_dump($qdata);
if($casecount==0){
	$errmsg="NO VALID case entry detected.";

}
if(isset($userset)&&$userset=="true"&&$casecount>0){
	$type[0]="clientcaseview";
	$type[1]=$clientid;
	$incdata=getSingleIncident($iid);
	$caseid=$qdata['resultdata'][0]['id'];
	// $outsdata=getAllIncidents("viewer","",$type,"adminoutputtwo2","seletclientcase");
	$formtruetype="caseeditform";


?>

<div class="box">
    <div class="box-header with-border">
        <h4 class="box-title">
            <i class="fa fa-sliders"></i> View/Update overall case information and status
        </h4>
    </div>
    <div class="box-body box-body-padded">
    	<form name="<?php echo $formtruetype;?>" method="POST" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/edit.php">
			<input type="hidden" name="entryvariant" value="updateclientcase"/>
			<input type="hidden" name="entryid" value="<?php echo $caseid?>"/>
			<div class="col-md-12">
				<div class="box box-solid">
	                <div class="box-header with-border">
	                  <i class="fa fa-info-circle"></i>
	                  <h3 class="box-title">Incident Information</h3>
	                </div><!-- /.box-header -->
	                <div class="box-body">
	                  <dl class="dl-horizontal">
	                    <dt>Fullname</dt>
	                    <dd><?php echo $incdata['rfullname'];?></dd>
	                    <dt>Gender</dt>
	                    <dd><?php echo $incdata['rgender'];?></dd>
	                    <dt>Email Address</dt>
	                    <dd><?php echo $incdata['remail'];?></dd>
						<dt>Phone Number</dt>
	                    <dd><?php echo $incdata['rphone'];?></dd>

	                    <dt>Preferred Contact Method</dt>
	                    <dd><?php echo $incdata['rpcmethod'];?></dd>
	                    <?php 
	                    	if($incdata['reporttype']=="self"&&$incdata['incidentfrequency']!=="once"){
	                    ?>
	                    <dt>Incident Frequency</dt>
	                    <dd><?php echo $incdata['incidentfrequency'];?></dd>
	                    <dt>Date Abuse Began</dt>
	                    <dd><?php echo $incdata['istdatefancy'];?></dd>
	                    <dt>Incident Location</dt>
	                    <dd><?php echo $incdata['incidentlocation'];?></dd>
	                    <dt>Use of Weapons in abuse</dt>
	                    <dd><?php echo $incdata['weapons'];?></dd>
	                    <dt>Use of threats in abuse</dt>
	                    <dd><?php echo $incdata['threats'];?></dd>
	                    <dt>Use of restraints</dt>
	                    <dd><?php echo $incdata['restraints'];?></dd>
	                    <dt>Physical Injury</dt>
	                    <dd><?php echo $incdata['weapons'];?></dd>
	                    <dt>Incident Reported Before</dt>
	                    <dd><?php echo $incdata['weapons'];?></dd>
		                    <?php 
		                    	if($incdata['ireported']=="yes"){
		                    ?>
		                    <dt>Previous Location of report</dt>
		                    <dd><?php echo $incdata['ireportedlocation'];?></dd>
		                    <dt>Assistance obtained with last report</dt>
		                    <dd><?php echo $incdata['ireportedaid'];?></dd>
		                    <?php 
		                		}
		                    ?>
	                    <?php 
	                		}
	                    ?>
	                    <dt>Date Incident Occured</dt>
	                    <dd><?php echo $incdata['datefancy'];?></dd>

	                    <dt>Date Incident Reported on Platform</dt>
	                    <dd><?php echo $incdata['eddatefancy'];?></dd>
	                    <dt>Number of Abusers</dt>
	                    <dd><?php echo $incdata['abusercount'];?></dd>
	                    <dt>Number of Abusers</dt>
	                    <dd><?php echo $incdata['abusercount'];?></dd>
	                    <dt>Abuser Details</dt>
	                    <dd><?php echo $incdata['abuserstrdata2'];?></dd>
	                    <dt>Incident Nature</dt>
	                    <dd><?php echo $incdata['incidentnature'];?></dd>
	                    <?php 
	                    	if($incdata['incidentnature']=="other"){
	                    ?>
	                    <dt>Incident Nature Details</dt>
	                    <dd><?php echo $incdata['incidentnaturedetails'];?></dd>
	                    <?php 
	                		}
	                    ?>


	                  </dl>
	                </div><!-- /.box-body -->
	              </div>
			</div>
	        <div class="col-md-12">
				<div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i> Information.</h4>
                    <p>After Submission of this form, the case would be closed from any 
                    	more assistance in your organisation's line of work and further 
                    	checks concerning the complete resolution of the case would be 
                    	carried</p>
                </div>
	        	<div class="form-group">
                    <label>Is this case well attended to and completely resolved?</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-file-text"></i>
                      </div>
                      <select type="text" class="form-control" name="caseresolution">
              			<option value="">--Choose--</option>
		          	  	<option value="yes">Yes</option>
		          	  </select>
		          	  <textarea class="form-control hidden" name="resolutiondetails"  rows="6" placeholder="Provide brief and concise overview of the entire operation while handling this case"></textarea>
                    </div><!-- /.input group -->
                </div><!-- /.form group -->
				<input type="hidden" name="spid" value="<?php echo $clientid;?>"/>
				<input type="hidden" name="incidentid" value="<?php echo $iid;?>"/>
	        </div>
			<input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>

			<input type="hidden" name="extraformdata" value="caseresolution-:-select<|>resolutiondetails-:-textarea-:-[group-|-caseresolution-|-select-|-yes]"/>

			<input type="hidden" name="errormap" value="caseresolution-:-Please Specify if you are willing to take on this case<|>resolutiondetails-:-Please you must provide mandatory information in the form of a general overview of your organisations experience as well as methods used in addressing the incident."/>

			<div class="col-md-12">
				<div class="box-footer">
	                <input type="button" class="btn btn-danger" name="updatecase" data-formdata="<?php echo $formtruetype;?>" onclick="submitCustom('<?php echo $formtruetype;?>','complete')" value="Update">
	            </div>
	    	</div>
        </form>
    </div>
</div>
<?php
}else{
?>
<div class="box">
    <div class="box-header with-border">
        <h4 class="box-title">
            <i class="fa fa-sliders"></i> Respond to Incidents
        </h4>
    </div>
    <div class="box-body box-body-padded">
    	<div class="col-md-12">
    		<div class="callout callout-danger">
                <h4>NO SESSION!!!.</h4>
                <p><?php echo $errmsg;?></p>
            </div>
    	</div>
    </div>
</div>
<?php
}
?>