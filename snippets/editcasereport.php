<?php
$errmsg="NO VALID SESSION DETECTED, LOGOUT THEN LOGIN AGAIN TO OBTAIN ACCESS.";
$clientid=$_SESSION['clientimysalvus'.$_SESSION['clienthmysalvus'].''];
$udata=getSingleUserPlain($clientid);
// get current case data
$biznature=$udata['businessnature'];
$bizstate=$udata['state'];
$cquery="SELECT * FROM cases WHERE id='$iid'";
$qdata=briefquery($cquery,__LINE__,"mysqli");
$caseid=$qdata['resultdata'][0]['id'];
$casecount=$qdata['numrows'];
// get the total number of reports for the current case
$cquery="SELECT * FROM casereports WHERE caseid='$caseid'";
$crdata=briefquery($cquery,__LINE__,"mysqli");
// var_dump($qdata);
if($casecount==0){
	$errmsg="NO VALID case entry detected.";

}

if(isset($userset)&&$userset=="true"&&$casecount>0&&$crdata['numrows']>0){
	$iid=$qdata['resultdata'][0]['incidentid'];
	$type[0]="clientcaseview";
	$type[1]=$clientid;
	$incdata=getSingleIncident($iid);
	$caseid=$qdata['resultdata'][0]['id'];
	// $outsdata=getAllIncidents("viewer","",$type,"adminoutputtwo2","seletclientcase");
	$formtruetype="caseeditform";
	for($i=0;$i<$crdata['numrows'];$i++){
		$currow=$crdata['resultdata'][$i];
		$t=$i+1;
		$curuser=getSingleUserPlain($currow['spid']);
		$entryid=$currow['id'];
		$reporttitle=$currow['title'];
		$reporttime=$currow['reportdate'];
		$reportdetails=$currow['details'];
?>

		<div class="box">
		    <div class="box-body ">
		    	<div class="box-group " id="accordion_<?php echo $t;?>">
		    		<div class="panel box box-success">
		    			<div class="box-header with-border">
	                        <h4 class="box-title">
		                        <a data-toggle="collapse" data-parent="#accordion_<?php echo $t;?>" href="#collapseThree_<?php echo $t;?>" class="collapsed" aria-expanded="false">
		            				<i class="fa fa-file-text"></i> Edit Case Report Entry #<?php echo $t?>
		                        </a>
	                        </h4>
	                    </div>
				    	<div id="collapseThree_<?php echo $t;?>" class="panel-collapse collapse">
						    <div class="box-body overflowhidden">
						    	<form name="<?php echo $formtruetype."_$t";?>" method="POST" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/edit.php">
									<input type="hidden" name="entryvariant" value="editcasereport"/>
									<input type="hidden" name="entryid" value="<?php echo $entryid;?>"/>
									

						    		<div class="col-md-12">
								    	<div class="form-group">
								          <label>Report Title</label>
								          <div class="input-group">
								              <div class="input-group-addon">
								                <i class="fa fa-file-text"></i>
								              </div>
											  <input type="hidden" name="cid" value="<?php echo $t;?>"/>
								          	  <input name="reporttitle" class="form-control" value="<?php echo $reporttitle;?>" placeholder="The report title."/>
								          </div>
								        </div>
								    </div>
								    <div class="col-md-12">
								    	<div class="form-group">
								          <label>Report Details</label>
								          <div class="input-group">
								              <div class="input-group-addon">
								                <i class="fa fa-file-text"></i>
								              </div>
								          	  <textarea name="reportdetails" data-mce="true" id="reportdetails<?php echo "_$t"?>" class="form-control" placeholder="The Report details."><?php echo $reportdetails;?></textarea>
								          </div>
								        </div>
								    </div>
								    <div class="col-md-12">
							        	<div class="form-group">
							              <label>Report Date</label>
							              <div class="input-group">
							                  <div class="input-group-addon">
							                    <i class="fa fa-clock-o"></i>
							                  </div>
							              	  <input class="form-control" name="reporttime" value="<?php echo $reporttime;?>" data-datetimepicker="true" placeholder="Specify the date of the current report."/>
							              </div>
							            </div>
							        </div>
									<input type="hidden" name="formdata" value="<?php echo $formtruetype."_$t";?>"/>

									<input type="hidden" name="extraformdata" value="reporttime-:-input<|>reporttitle-:-input<|>reportdetails-:-textarea"/>

									<input type="hidden" name="errormap" value="reporttime-:-Please specify the date and time for this report entry<|>reporttitle-:-NA<|>reportdetails-:-Please provide a detailed report entry"/>

									<div class="col-md-12">
										<div class="box-footer">
							                <input type="button" class="btn btn-danger" name="updatecase" data-formdata="<?php echo $formtruetype."_$t";?>" onclick="submitCustom('<?php echo $formtruetype."_$t";?>','complete')" value="Update">
							            </div>
							    	</div>
						        </form>
						    </div>
						</div>
				    </div>
			    </div>
		    </div>
			<script>
				$(document).ready(function(){
					var curmceadminposter=[];
					curmceadminposter['width']="100%";
					curmceadminposter['height']="500px";
					curmceadminposter['toolbar1']="undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect";
					curmceadminposter['toolbar2']="| link unlink anchor | image media | forecolor backcolor  | print preview code ";
					callTinyMCEInit("textarea#reportdetails<?php echo "_$t"?>",curmceadminposter);
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
				})
			</script>
		</div>


<?php
	}
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