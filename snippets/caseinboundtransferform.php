<?php
// this file handles inbound case transfers
$errmsg="NO VALID SESSION DETECTED, LOGOUT THEN LOGIN AGAIN TO OBTAIN ACCESS.";
$clientid=$_SESSION['clientimysalvus'.$_SESSION['clienthmysalvus'].''];
$udata=getSingleUserPlain($clientid);
// get current case data
$biznature=$udata['businessnature'];
$bizstate=$udata['state'];
$cquery="SELECT * FROM casetransfer WHERE id='$iid'";
$qdata=briefquery($cquery,__LINE__,"mysqli");
$casecount=$qdata['numrows'];
// var_dump($qdata);
if($casecount==0){
	$errmsg="NO VALID case entry detected.";

}else{
	$incdata=getSingleIncident($qdata['resultdata'][0]['incidentid']);
	$transferdetails=$qdata['resultdata'][0]['details'];
	// var_dump($qdata);
	$pudata=getSingleUserPlain($qdata['resultdata'][0]['spid']);
	$ospdata=$pudata;
	$ispid=$qdata['resultdata'][0]['ispid'];
	// get the currentcase information
	$caseid=$qdata['resultdata'][0]['caseid'];
	$cctype[0]="single";
	$cctype[1]=$caseid;
	$cdata=getAllCases("admin","",$cctype);
}
if(isset($userset)&&$userset=="true"&&$casecount>0){
	
	$casetransferid=$qdata['resultdata'][0]['id'];
	// $outsdata=getAllIncidents("viewer","",$type,"adminoutputtwo2","seletclientcase");
	$typearr[0]="businessnature";
	$typearr[1]="$biznature";
	$csps=getAllUsers("viewer","","serviceprovider",'',$typearr);

	$formtruetype="caseeditform";


?>

<div class="box">
    <div class="box-header with-border">
        <h4 class="box-title">
            <i class="fa fa-sliders"></i> Case Inbound Transfer
        </h4>
    </div>
    <div class="box-body box-body-padded">
    	<form name="<?php echo $formtruetype;?>" method="POST" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/edit.php">
			<input type="hidden" name="entryvariant" value="inboundtransfercase"/>
			<input type="hidden" name="entryid" value="<?php echo $casetransferid?>"/>
			<input type="hidden" name="caseid" value="<?php echo $caseid?>"/>
			<input type="hidden" name="spid" value="<?php echo $ispid?>"/>
			<div class="col-md-12">
				<div class="box box-solid">
	                <div class="box-header with-border">
	                  <i class="fa fa-info-circle"></i>
	                  <h3 class="box-title">Transfer Information</h3>
	                </div><!-- /.box-header -->
	                <div class="box-body">
		                <h4 class="clear-both">Organisation Information</h4>

	                	<dl class="dl-horizontal">
		                    <dt>Organisation Name</dt>
	                    	<dd><?php echo $ospdata['businessname'];?></dd>
	                    	<dt>Contact Person</dt>
	                    	<dd><?php echo $ospdata['fullname'];?></dd>
	                    	<dt>Contact Email</dt>
	                    	<dd><?php echo $ospdata['contactemail'];?></dd>
	                    	<dt>Contact Phonenumber</dt>
	                    	<dd><?php echo $ospdata['contactphone'];?></dd>
		                    <dt>Transfer Details</dt>
	                    	<dd><?php echo $transferdetails;?></dd>
		                </dl>
		                <h4 class="clear-both">Incident Information</h4>
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
							<dt>Incident Nature</dt>
							<dd><?php echo $incdata['incidentnature'];?></dd>
							<dt>Abuser Details</dt>
							<dd><?php echo $incdata['abuserstrdata2'];?></dd>
							
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
                    <p>Accepting this case gives you access to reports carried out by the previous organization handling.</p>
                </div>
                
                <div class="col-md-12">
                	<div class="form-group">
	                    <label>Would you like to take up this case?</label>
	                    <div class="input-group">
	                      <div class="input-group-addon">
	                        <i class="fa fa-file-text"></i>
	                      </div>
	                      <select type="text" class="form-control" name="casetransferresponse">
	              			<option value="">--Choose--</option>
			          	  	<option value="accepted">Accept</option>
			          	  	<option value="declined">Decline</option>
			          	  </select>
	                    </div><!-- /.input group -->
	                </div><!-- /.form group -->
                </div>
	        </div>
			<input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>

			<input type="hidden" name="extraformdata" value="casetransferresponse-:-select"/>

			<input type="hidden" name="errormap" value="newspid-:-Please Specify if you are acceptingg or declining the current transfer."/>

			<div class="col-md-12">
				<div class="box-footer">
	                <input type="button" class="btn btn-danger" name="updatecase" data-formdata="<?php echo $formtruetype;?>" onclick="submitCustom('<?php echo $formtruetype;?>','complete')" value="Update">
	            </div>
	    	</div>
        </form>
    </div>
</div>
<script>
	$(document).ready(function(){
		$('select[name=newspid]').find('option[value='+<?php echo $clientid?>+']').remove();
		
	})
</script>
<?php
}else{
?>
<div class="box">
    <div class="box-header with-border">
        <h4 class="box-title">
            <i class="fa fa-sliders"></i> Case Transfer
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