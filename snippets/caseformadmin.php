<?php 
	// this code snippet handles the assingment /request of cases to service providers
	// by users or t
	$caseentrytype="createcaseadmin";
	// echo $displaytype;
	if($displaytype=="editsingleincidentcase"){
		$caseentrytype="createcase";
		$userset="true";
	}
	if(isset($userset)&&$userset=="true"){
		$userid=$_SESSION['userimysalvus'.$_SESSION['userhmysalvus'].''];
		$type[0]="userview";
		$type[1]=$userid;
		$userdata=getSingleUserPlain($userid);
		// $outsdata=getAllIncidents("viewer","",$type,"");
	}else{
		$userid="";
		$userset="";
	}

?>
<div class="box">
	<div class="box-body clearboth">
	    <div class="box-group" id="generaldataaccordion">
			<div class="panel box box-primary">
			    <div class="box-header with-border">
			        <h4 class="box-title">
			          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#NewPageManagerBlock">
			            <i class="fa fa-sliders"></i> Case 
			            <?php if ($userset!=="true"){
			            	?>
			            	Assignment/<?php
			        		}
			        	?>
			            Request
			          </a>
			        </h4>
			    </div>
			    <div id="NewPageManagerBlock" class="panel-collapse collapse in">
			        <div class="row">
			            <form name="<?php echo $formtruetype;?>" data-name="caseassignment" method="POST" class="" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr?>snippets/basicsignup.php">
							<input type="hidden" name="entryvariant" value="<?php echo $caseentrytype?>"/>
							<input type="hidden" name="incidentid" value="<?php echo $iid;?>"/>
							<?php if ($userset=="true"){
										    ?>
								<input type="hidden" name="spuser" value="true"/>

							<?php 
                  	  			}
			            	?>
			                <div class="col-md-12 ">
			                	<?php 
			                		if ($userset=="true"){
			                			// this is specifically shown to users
			            		?>
			                	<div class="callout callout-info">
				                    <h4>Information.</h4>
				                    <p>This form allows you make requests of 
				                    	service providers for the current incident.</p>
				                </div>
				                <?php
					        		}
					        	?>
			                	<?php 
			                		$bizarr=getAllBusinessTypes();
			                		
			                		$divlock='<div class="col-md-12">';
			                		$divlock2='</div><div class="col-md-12">';
			                		$divlock3='</div>';
		                			// get the total number of assigned cases 
			                		$incidenttest=getSingleIncident($iid);
		                			if($incidenttest['caserows']>0){
			                			$edittype='data-edittype="true"';
		                				$valset="";
										// echo '<input type="hidden" name="naturecount" value="'.$bizarr['numrows'].'"/>';
		                			}else{
										// echo '<input type="hidden" name="naturecount" value="'.$bizarr['numrows'].'" data-valset="1,3" data-valcount="1" data-counter="true"/>';
		                				
		                			}
		                			if($userset=="true"){
		                				// echo '<div class=""></div>';
		                			}
									echo '<input type="hidden" name="naturecount" value="'.$bizarr['numrows'].'"/>';
			                		for($i=0;$i<$bizarr['numrows'];$i++){
			                			$t=$i+1;
			                			if($i==0){
			                				echo $divlock;
			                			}
			                			if($i%3==0){
			                				echo $divlock2;
			                			}
			                			$curnature=$bizarr['resultdata'][$i]['businessnature'];
			                			$curicon=$bizarr['resultdata'][$i]['faicon'];
			                			$id=$bizarr['resultdata'][$i]['id'];
			                			$incidentdata=getSingleIncident($iid,"$curnature");
										$curstate=$incidentdata['sstate'];
										$csd=produceStates("",$curstate);
										$curstatename=$csd['statename'];
										// run a table update reflecting any changes to the business nature name
										$cq="UPDATE users SET businessnature='$curnature' WHERE bnid='$id'";
										$cqrun=mysql_query($cq)or die(mysql_error()." Line ".__LINE__);
			                			// get current reports
			                			// the status of the current incident
			                			$statusout="Not Assigned";
			                			$viewclass="";
			                			$cspview="";
			                			$sdview="";
			                			$ctrlview="hidden";
			                			$coptview="hidden";
			                			$coptviewr="hidden";
			                			$coptviewf="hidden";
			                			if($incidentdata['caserequestrows']>0){
			                				$statusout='Request(s) Sent';
			                				$coptviewr="";
				                			$coptviewf="";
				                			$cspview="";
				                			$ctrlview="";
				                			$edittype='data-edittype="true"';
			                			}	
			                			// if the incident already has a case created for it then the following
			                			// ensues
			                			if($incidentdata['caserows']>0){
			                				// text display for service provider type status
			                				$statusout='Assigned';
			                				// control variable for handling the Options
			                				// link when case is available
			                				$viewclass="hidden";
			                				$cspview="hidden";
				                			$ctrlview="";
			                				$sdview="hidden";
			                				$ap=getSingleUserPlain($incidentdata['cases'][0]['spid']);
			                				$aprov=$ap['businessname'];
			                				$coptviewr="";
			                				$coptview="";
				                			$coptviewf="";
			                			}
			                	?>
					                	<div class="col-md-4 col-md--4 nature-content-hold _<?php echo $t;?> ">
					                		<h2><i class="fa <?php echo $curicon?>"></i> <?php echo $curnature;?> <small>Status: <?php echo $statusout;?></small></h2>
					                		<?php 
					                			if($userset=="true"){
					                				$showsalvuscontact="";
													$typearr[0]="combo";
													$typearr[1]="businessnature**state:$curnature**$curstate";
													$csps=getAllUsers("viewer","","serviceprovider",'',$typearr);
													// $msg=$csps['selectiontwo'];
						                			// echo "$msg msg".$csps['numrows'];
						                			$sdata=briefquery("SELECT * FROM state WHERE id_no='$curstate'",__LINE__,"mysqli");
													$cdata=$sdata['resultdata'][0];
													$numrows=$sdata['numrows'];
													$rhash=md5($iid);
													// if there are any results then set the state id to the current id
													$statename=$numrows>0?$cdata['state']:$curstate;
						                			if($csps['numrows']<1){
						                				$showsalvuscontact="true";
						                				$salvuscontact='
						                				<div class="input-group ">
									                      <div class="input-group-addon">
									                        <i class="fa fa-at"></i>
									                      </div>
								                      	  
							                      	  		<a href="mailto:info@mysalvus.org?subject=NO SERVICE PROVIDER FOR '.strtoupper($curnature).' in '.strtoupper($statename).'&'.
							                      	  		'body=Leave this line as is, '.
							                      	  		'User Fullname: '.$userdata['fullname'].', '.
							                      	  		'Report Type: '.$incidenttest['reporttype'].', '.
							                      	  		'Incident Nature: '.$incidenttest['incidentnature'].', '.
							                      	  		'Report Date: '.$incidenttest['entrydate'].', '.
							                      	  		'Incident: '.$rhash.' '.
							                      	  		'" 
							                      	  		class="salvuscontact">Send Message to MySalvus</a>

									                  </div>
							                				';

						                				$csps['selectiontwo']='<option value="">No Service Provider For "'.$curnature.'" registered in '.$curstatename.'</option>';
						                			}
					                			}else{
						                			$typearr[0]="businessnature";
						                			$typearr[1]="$curnature";
						                			$csps=getAllUsers("viewer","","serviceprovider",'',$typearr);
					                				
					                			}
					                			if($sdview=="hidden"){
					                				echo '<h4 class="">Assigned Provider: '.$aprov.'</h4>';
					                			}
					                		?>
											<input type="hidden" name="spnature_<?php echo $t;?>" value="<?php echo $curnature;?>"/>
											
				                        	<div class="form-group col-md--4 nature-content-left col-md-4 ">
						                      <div class="input-group <?php echo $viewclass;?>">
							                      <div class="input-group-addon">
							                        <i class="fa fa-random"></i>
							                      </div>
							                      <select class="form-control" <?php echo $edittype;?> name="casetype_<?php echo $t;?>">
							                      	  	<?php if ($userset!=="true"){
										            	?>
							                      	  	<option value="">--Assign/Request--</option>
			            								<option value="Assign">Assign</option>
							                      	  		<?php 
							                      	  			}else{
											            	?>
							                      	  	<option value="">--Choose to Request Help?--</option>

											            	<?php
											            		}
											            	?>
							                      	  	<option value="Request">Request</option>

						                      	  </select>
						                      </div>
						                      <?php if ($userset!=="true"){
								            	?>
						                      <div class="input-group <?php echo $viewclass;?>">
							                      <div class="input-group-addon">
							                        <i class="fa fa-map-pin"></i>
							                      </div>
						                      	  <select class="form-control " <?php echo $edittype;?> name="spstate_<?php echo $t;?>">
						                      	  	<option value="">Current State: ALL</option>
						                      	  	<?php $ps=produceStates("",""); echo $ps['selectionoptions']?>
						                      	  </select>
							                      <div class="input-group-addon nopadding">
						                      	  	<a href="##" data-name="sortbusinesses" data-id="<?php echo $t;?>" class="bs-igicon blockdisplay bg-gradient-darkgreen background-color-darkgreen background-color-orangehover bg-orange-gradienthover color-white color-darkredhover">
								                        <i class="fa fa-arrow-right"></i>	
								                    </a>
							                      </div>
							                  </div>
							                  <?php 
							              			}
								            	?>
							                  <div class="input-group <?php echo $viewclass;?>">
							                      <div class="input-group-addon">
							                        <i class="fa fa-briefcase"></i>
							                      </div>
						                      	  <select class="form-control <?php echo $cspview;?>" <?php echo $edittype?> name="sp_<?php echo $t;?>">
							                      	  	<option value="">Choose Service Provider</option>
						                      	  		<?php echo $csps['selectiontwo'];?>
						                      	  </select>

							                  </div>
						                      <div class="loadmask hidden">
						                      	<img class="loadermini" src="<?php echo $host_addr?>images/loading.gif"/>
						                      </div>
						                      <div class="case_extra_options <?php echo $ctrlview;?>" data-id="<?php echo $t;?>">
							                      	<a href="##" data-name="caseoption" data-value="crrequests" class="caseoption <?php echo $coptviewr;?>">Requests</a>
							                      	<a href="##" data-name="caseoption" data-value="crcases" class="caseoption <?php echo $coptview;?>">Case Information</a>
							                      	<a href="##" data-name="caseoption" data-value="crtransfer" class="caseoption <?php echo $coptview;?>">Transfer</a>
							                      	<?php 
							                      		if ($userset!=="true"){
							                      	?>
							                      	<a href="##" data-name="caseoption" data-value="crreport" class="caseoption <?php echo $coptview;?>">Reports</a>
							                      	<?php 
							                      		}
							                      	?>
							                      	<a href="##" data-name="loadmorecasedata" class="caseoption <?php echo $coptviewf;?>">View Case Options</a>
						                      </div>
						                      	  <?php
						                      	  	if(isset($showsalvuscontact)&&$showsalvuscontact=="true"){
						                      	  		echo $salvuscontact;
						                      		}
						                      	  ?>
						                    </div>

						                    <div class="col-md--8 nature-content-right hidden">
						                    	<div class="col-md-12 ncr-details">
						                    		
						                    	</div>
						                    	<div class="loadmask hidden">
						                      		<img class="loadermini" src="<?php echo $host_addr?>images/loading.gif"/>
						                      	</div>
						                    </div>
						                </div>
				                <?php
				                		if($i==$bizarr['numrows']-1){
			                				echo $divlock3;
			                			}
			                		}
				                ?>

					               
			                </div>
			               
			                <input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>
		                    <input type="hidden" name="extraformdata" value="egroup|data-:-[naturecount>|<
                      				casetype_-|-select>|<
                      				spstate_-|-select>|<
                      				sp_-|-select]-:-groupfall[1-3,3-1]"/>
		                    <input type="hidden" name="errormap" value="egroup|data-:-[<?php if($userset!=="true"){
		                    	?>Please Specify if this is a case Request or a Direct Assignment<?php }else{ 
		                    		?>Please Change the Request option for the Service you need. <?php 
		                    	} ?>>|<NA>|<Specify the service provider]"/>
			                <div class="col-md-12 clearboth">
				                <div class="box-footer">
				                    <input type="button" class="btn btn-danger" name="casesubmit" data-formdata="<?php echo $formtruetype;?>" onclick="submitCustom('<?php echo $formtruetype;?>','complete')" value="Create"/>
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
	                        theme: "bootstrap"
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
		</div>
	</div>
</div>