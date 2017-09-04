<?php
$errmsg="NO VALID SESSION DETECTED, LOGOUT THEN LOGIN AGAIN TO OBTAIN ACCESS.";
$clientid=$_SESSION['clientimysalvus'.$_SESSION['clienthmysalvus'].''];
$udata=getSingleUserPlain($clientid);
// get current case data
$biznature=$udata['businessnature'];
$bizstate=$udata['state'];
$cquery="SELECT * FROM caserequests WHERE incidentid='$iid' AND spid='$clientid' AND spnature='$biznature' AND acceptancestatus='pending'";
$qdata=briefquery($cquery,__LINE__,"mysqli");
$casercount=$qdata['numrows'];
// var_dump($qdata);
if($casercount==0){
	$errmsg="NO VALID case request entry detected.";

}
if(isset($userset)&&$userset=="true"&&$casercount>0){
	$type[0]="clientcaserequests";
	$type[1]=$clientid;
	$incdata=getSingleIncident($iid);
	$crid=$qdata['resultdata'][0]['id'];
	// $outsdata=getAllIncidents("viewer","",$type,"adminoutputtwo2","seletclientcase");
	$formtruetype="caseeditform";


?>

<div class="box">
    <div class="box-header with-border">
        <h4 class="box-title">
            <i class="fa fa-sliders"></i> View/Update Case Requests
        </h4>
    </div>
    <div class="box-body box-body-padded">
    	<form name="<?php echo $formtruetype;?>" method="POST" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/edit.php">
			<input type="hidden" name="entryvariant" value="updateclientrequest"/>
			<input type="hidden" name="entryid" value="<?php echo $crid;?>"/>
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

	                    <dt>Preferred Contact Method</dt>
	                    <dd><?php echo $incdata['rpcmethod'];?></dd>
	                    <?php 
	                    	if($incdata['reporttype']=="self"&&$incdata['incidentfrequency']!=="once"){
	                    ?>
	                    <dt>Incident Frequency</dt>
	                    <dd><?php echo $incdata['incidentfrequency'];?></dd>
	                    <dt>Date Abuse Began</dt>
	                    <dd><?php echo $incdata['istdatefancy'];?></dd>
	                    <?php 
	                		}
	                    ?>
	                    <dt>Date Incident Occured</dt>
	                    <dd><?php echo $incdata['datefancy'];?></dd>

	                    <dt>Date Incident Reported on Platform</dt>
	                    <dd><?php echo $incdata['eddatefancy'];?></dd>

	                    <dt>Incident Nature</dt>
	                    <dd><?php echo $incdata['incidentnature'];?></dd>
	                    
	                    <dt>Number of Abusers</dt>
	                    <dd><?php echo $incdata['abusercount'];?></dd>


	                    <dt>Incident Details</dt>
	                    <dd><?php echo $incdata['rdetails'];?></dd>

	                  </dl>
	                </div><!-- /.box-body -->
	              </div>
			</div>
	        <div class="col-md-12">
				<div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i> Information.</h4>
                    <p>After accepting this case, you would have complete access to more information on this incident</p>
                </div>
	        	<div class="form-group">
                    <label>Would you like to take up this case?</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-file-text"></i>
                      </div>
                      <select type="text" class="form-control" name="caserequestresponse">
              			<option value="">--Choose--</option>
		          	  	<option value="accepted">Accept</option>
		          	  	<option value="declined">Decline</option>
		          	  </select>
                    </div><!-- /.input group -->
                </div><!-- /.form group -->
				<input type="hidden" name="spid" value="<?php echo $clientid;?>"/>
				<input type="hidden" name="incidentid" value="<?php echo $iid;?>"/>
	        </div>
			<input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>

			<input type="hidden" name="extraformdata" value="caserequestresponse-:-select"/>

			<input type="hidden" name="errormap" value="caserequestresponse-:-Please Specify if you are accepting or declining this case request"/>

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