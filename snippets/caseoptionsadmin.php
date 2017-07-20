<?php
	// this code snippet is responsible for handling cases information as regards 
	// transfers, requests, resolution status/details, and reports
	// among others for a single incident
	if(isset($subtype)){


		$couts=getSingleIncident($incid,$spnature);
		// $caserequestsdataset=$couts['cases'][0];
		// case requests
		$caserequestsdataset="";
		if($couts['caserequestrows']>0){
			// caserequest keys
			/*
				incidentid
				spid
				spnature
				entrydate
				acceptancestatus
				acceptancedate
				status
			*/
			 
			$caserequestsdataset=$couts['caserequests'];
		}

		// case transfers
		$casetranferdataset="";
		if($couts['casetransferrows']>0){
			$casetransferdataset=$couts['casetransfer'];
			// case keys
			/*
				spid
				spnature
				ispid
				details
				entrydate
				incidentid
				acceptancestatus
				adate
				status
			*/
		}
		// cases
		$casedataset="";
		if($couts['caserows']>0){
			// case keys
			/*
				incidentid
				spid
				spnature
				entrydate
				resolutiondate
				resolutionstatus
				resolutiondetails
				status
			*/
			$casedataset=$couts['cases'];
		}
		// get reports
		$casereports="";
		$casereportcount=0;
		if($couts['caserows']>0){
			$cid=$casedataset[0]['id'];
			$casereportq="SELECT * FROM casereports WHERE caseid='$cid' AND status='active'";
			$qdata=briefquery($casereportq,__LINE__,"mysqli",true);
			$casereportcount=$qdata['numrows'];
			if($casereportcount>0){
				// report key
				/*
					caseid
					spid
					entrydate
					title
					details
					status
				*/
				$casereports=$qdata['resultdata'];
			}	
		}
		if($subtype=="crrequests"){
			if($couts['caserequestrows']>0){
				echo'
					<h3 class="text-center">Case Requests made for '.$spnature.' Service Provider(s). Total: <span class="color-darkgreen">'.$couts['caserequestrows'].'</span></h3>
				';
				for($i=0;$i<$couts['caserequestrows'];$i++){
					$id=$couts['caserequests'][$i]['spid'];
					$acceptancestatus=$couts['caserequests'][$i]['acceptancestatus'];
					$maindayout=date('D, d F, Y', strtotime($couts['caserequests'][$i]['entrydate']));
				  	// $row['datefancy']=$maindayout;
				  	$ocolor="green";
				  	if($acceptancestatus=="pending"){
					  	$ocolor="lightorange";

				  	}else if($acceptancestatus=="declined"){
					  	$ocolor="lightred";

				  	}
					$udata=getSingleUserPlain($id);
?>					
					<?php if ($userset=="true"){		
					?>
	                	<div class="alert alert-info alert-dismissable">
		                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		                    <h4><i class="icon fa fa-info"></i> Information.</h4>
		                    <p>When a service provider accepts your case you wont see them here and any other requests sent previously would be declined.</p>
		                </div>
	                <?php
		        		}
		        	?>
            		<div class="col-md-12">
                		<div class="col-md-7 text-center">
                			<h4><?php echo $udata['businessname'];?> <br><small>Status: <span class="color-<?php echo $ocolor;?>"><?php echo $acceptancestatus;?></span></small></h4>
                		</div>
                		<div class="col-md-5 liney">Request Date: <?php echo $maindayout;?></div>
            		</div>
<?php
				}
			}else{
				echo '<h4> No case requests found.</h4>';
			}
		}else if ($subtype=="crtransfer") {
			if($couts['casetransferrows']>0){
				echo'
					<h3 class="text-center">Case Transfers for '.$spnature.' Service Provider(s).</h3>
				';
				for($i=0;$i<$couts['casetransferrows'];$i++){
					$id=$couts['casetransfer'][$i]['spid'];
					$id2=$couts['casetransfer'][$i]['ispid'];
					$maindayout=date('D, d F, Y', strtotime($couts['casetransfer'][$i]['entrydate']));
				  	// $row['datefancy']=$maindayout;
					$udata=getSingleUserPlain($id);
					$udatatwo=getSingleUserPlain($id2);
?>
					<div class="box">
						<div class="box-body clearboth">
						    <div class="box-group" id="sgeneraldataaccordion_<?php echo $i;?>">
								<div class="panel box box-primary">
								    <div class="box-header with-border">
								        <h4 class="box-title">
								          <a data-toggle="collapse" data-parent="#sgeneraldataaccordion_<?php echo $i;?>" href="#sNewPageManagerBlock_<?php echo $i;?>">
								            <i class="fa fa-exchange"></i> <?php echo $udata['businessname']?> Transfering to <?php echo $udatatwo['businessname']?>
								          </a>
								        </h4>
								    </div>
								    <div id="sNewPageManagerBlock_<?php echo $i;?>" class="panel-collapse collapse">
								        <div class="row overflowhidden">
											<div class="col-md-12 overflowhidden">
												<?php
													echo '<p>'.$couts['casetransfer'][$i]['details'].'</p>'; 
													echo'<p>Transfer Request Date: '.date('D, d F, Y', strtotime($couts['casetransfer'][$i]['entrydate'])).'</p>';
													echo'<div><p>Transfer Acceptance status</p> <h4>'.$couts['casetransfer'][$i]['acceptancestatus'].'</h4></div>';
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
<?php
				}
			}else{
				echo '<h4> No ongoing case transfers found for this incident under this Service Provider.</h4>';
			}
		}else if ($subtype=="crcases") {
			if($couts['caserows']>0){
				echo'
					<h3 class="text-center">Case selection for '.$spnature.' Service Provider(s).</h3>
				';
				$typearr[0]="businessnature";
    			$typearr[1]="$spnature";
    			$csps=getAllUsers("viewer","","serviceprovider",'',$typearr);
				for($i=0;$i<$couts['caserows'];$i++){
					$id=$couts['cases'][$i]['spid'];
					$cid=$couts['cases'][$i]['id'];
					$maindayout=date('D, d F, Y', strtotime($couts['cases'][$i]['entrydate']));
				  	// $row['datefancy']=$maindayout;
					$udata=getSingleUserPlain($id);

?>
					<div class="col-md-12">
                		<div class="col-md-7 text-center">
                			<h4>Service Provider:<?php echo $udata['businessname'];?><br>  <small>Case Status: <?php echo $couts['cases'][$i]['resolutionstatus'];?></small></h4>	
                			<?php 
                				$cstatdata=getAllCaseTransfers($cid,"pendingactive");
                				// var_dump($cstatdata);
                				// echo $cstatdata['casetransfercount'].": numrows";
                				// echo $cstatdata['casetransfer'][0]['acceptancestatus'].": acceptancestatus";
                				if($couts['cases'][$i]['resolutionstatus']!=="transfer"){
                			?>
					        	<?php if ($userset!=="true"){
			            		?>
	            				<div class="col-sm-12">
	            					<div class="form-group">
	            						<input type="hidden" name="spnature_0" value="<?php echo $spnature;?>"/>
	            						<input type="hidden" name="caseid_0" value="<?php echo $cid;?>"/>
	            						<input type="hidden" name="cspid_0" value="<?php echo $id;?>"/>
	            						<input type="hidden" name="incid_0" value="<?php echo $incid;?>"/>
							          	<label><h4>Initiate Case Transfer</h4></label>
											<div class="input-group">
											  <div class="input-group-addon">
											    <i class="fa fa-map-pin"></i>
											  </div>
											  <select class="form-control" name="spstate_0">
												  	<option value="">Current State: ALL</option>
												  	<?php $ps=produceStates("",""); echo $ps['selectionoptions']?>
											  </select>
											  <div class="input-group-addon nopadding">
												<a href="##" data-name="sortbusinesses" data-id="0" class="bs-igicon blockdisplay bg-gradient-darkgreen background-color-darkgreen background-color-orangehover bg-orange-gradienthover color-white color-darkredhover">
											        <i class="fa fa-arrow-right"></i>	
											    </a>
											  </div>
											</div>
											<div class="input-group">
												<div class="input-group-addon">
													<i class="fa fa-briefcase"></i>
												</div>
											  <select class="form-control" name="sp_0">
												  	<option value="">Choose Service Provider</option>
											  		<?php echo $csps['selectiontwo'];?>
											  </select>
											</div>
											<div class="input-group">
												<div class="input-group-addon">
													<i class="fa fa-file-text"></i>
												</div>
												<textarea class="form-control" name="details_0" placeholder="Details, Including the reason for the transfer"></textarea>
											</div>
											<div class="loadmask hidden">
												<img class="loadermini" src="<?php echo $host_addr?>images/loading.gif"/>
											</div>
											<input type="button" class="btn btn-danger" name="inittransfer" data-id="0" value="Initialize Transfer"/>
	            						
	            					</div>
	            				</div>
	            				<?php
					        		}else{
					        	?>
					        		<div class="col-sm-12">
					        			<dl class="dl-horizontal">
						                    <dt>Organisation Name</dt>
					                    	<dd><?php echo $udata['businessname'];?></dd>
					                    	<dt>Contact Person</dt>
					                    	<dd><?php echo $udata['fullname'];?></dd>
					                    	<dt>Contact Email</dt>
					                    	<dd><?php echo $udata['contactemail'];?></dd>
						                    <dt>Address</dt>
					                    	<dd><?php echo $udata['address'];?></dd>
						                </dl>
					        		</div>
	            			<?php
	            					}
	            				}
	            			?>
                		</div>
                		<div class="col-md-5 liney">Case Start: <?php echo $maindayout;?></div>
            		</div>				
<?php
				}
			}else{
				echo '<h4>No Case entries found for this service provider.</h4>';
			}
		}else if ($subtype=="crreport") {
			if($casereportcount>0){
				echo'
					<h3 class="text-center">Case Reports for '.$spnature.' Service Provider(s).</h3>
				';
				for($t=0;$t<$casereportcount;$t++){
					$i=$t+1;
					$id=$casereports[$t]['spid'];
					$maindayout=date('D, d F, Y', strtotime($casereports[$t]['entrydate']));
				  	// $row['datefancy']=$maindayout;
				  	$title="Report Details";
				  	if($casereports[$t]['title']!==""){
				  		$title=$casereports[$t]['title'];
				  	}
					$udata=getSingleUserPlain($id);
?>
					<div class="box">
						<div class="box-body clearboth">
						    <div class="box-group" id="sgeneraldataaccordion_<?php echo $i;?>">
								<div class="panel box box-primary">
								    <div class="box-header with-border">
								        <h4 class="box-title">
								          <a data-toggle="collapse" data-parent="#sgeneraldataaccordion_<?php echo $i;?>" href="#sNewPageManagerBlock_<?php echo $i;?>">
								            <i class="fa fa-file-text"></i> Report #<?php echo "$i - "; echo "Organisation: ".$udata['businessname'];?>
								          </a>
								        </h4>
								    </div>
								    <div id="sNewPageManagerBlock_<?php echo $i;?>" class="panel-collapse collapse">
								        <div class="row overflowhidden">
											<div class="col-md-12">
												<?php
													echo '<h4>'.$title.'</h4>'; 
													echo'<p>'.$casereports[$t]['details'].'</p>';
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
	
<?php
				}
			}else{
				echo '<h4>No Case Reports found for this service provider case.</h4>';
			}
		}
	}
?>