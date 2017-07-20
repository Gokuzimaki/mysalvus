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
			$cid=$casedataset['id'];
			$casereportq="SELECT * FROM casereports WHERE caseid='$cid' AND status='active' $scasequery";
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
					<h3 class="text-center">Case Requests made for '.$spnature.' Service Provider(s).</h3>
				';
				for($i=0;$i<$couts['caserequestrows'];$i++){
					$id=$couts['caserequest'][$i]['spid'];
					$maindayout=date('D, d F, Y', strtotime($couts['caserequest'][$i]['entrydate']));
				  	// $row['datefancy']=$maindayout;
					$udata=getSingleUserPlain($id);
?>
            		<div class="col-md-12">
                		<div class="col-md-7 text-center">
                			<h4><?php echo $udata['businessname'];?></h4>
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
					<h3 class="text-center">Case Transfers for '.$spnature.' 
					Service Provider(s).</h3>
				';
				for($i=0;$i<$couts['casetranferrows'];$i++){
					$id=$couts['casetransfer'][$i]['spid'];
					$id2=$couts['casetransfer'][$i]['ispid'];
					$maindayout=date('D, d F, Y', 
						strtotime($couts['casetransfer'][$i]['entrydate']));
				  	// $row['datefancy']=$maindayout;
					$udata=getSingleUserPlain($id);
					$udatatwo=getSingleUserPlain($id2);
?>
					<div class="box">
						<div class="box-body clearboth">
						    <div class="box-group" 
						    id="sgeneraldataaccordion_<?php echo $i;?>">
								<div class="panel box box-primary">
								    <div class="box-header with-border">
								        <h4 class="box-title">
								          <a data-toggle="collapse" 
								          data-parent="#sgeneraldataaccordion_<?php echo $i;?>" href="#sNewPageManagerBlock_<?php echo $i;?>">
								            <i class="fa fa-sliders"></i> <?php echo $udata['businessname']?> Transfering to <?php echo $udatatwo['businessname']?>
								          </a>
								        </h4>
								    </div>
								    <div id="sNewPageManagerBlock_<?php echo $i;?>" class="panel-collapse collapse">
								        <div class="row">
											<div class="col-md-12">
												<?php
													echo '<p>'.$couts['casetransfer'][$i]['details'].'</p>'; 
													echo'<p>Transfer Request Date '.$couts['casetransfer'][$i]['entrydate'].'</p>';
													echo'<p>Transfer Acceptance status '.$couts['casetransfer'][$i]['acceptancestatus'].'</p>';
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
				for($i=0;$i<$couts['caserows'];$i++){
					$id=$couts['cases'][$i]['spid'];
					$maindayout=date('D, d F, Y', strtotime($couts['cases'][$i]['entrydate']));
				  	// $row['datefancy']=$maindayout;
					$udata=getSingleUserPlain($id);

?>
					<div class="col-md-12">
                		<div class="col-md-7 text-center">
                			<h4><?php echo $udata['businessname'];?> Case status: <?php echo $couts['cases'][$i]['resolutionstatus'];?></h4>
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
				for($i=0;$i<$casereportcount;$i++){
					$id=$casereports[$i]['spid'];
					$maindayout=date('D, d F, Y', strtotime($casereports[$i]['entrydate']));
				  	// $row['datefancy']=$maindayout;
					$udata=getSingleUserPlain($id);
?>
					<div class="box">
						<div class="box-body clearboth">
						    <div class="box-group" id="sgeneraldataaccordion_<?php echo $i;?>">
								<div class="panel box box-primary">
								    <div class="box-header with-border">
								        <h4 class="box-title">
								          <a data-toggle="collapse" data-parent="#sgeneraldataaccordion_<?php echo $i;?>" href="#sNewPageManagerBlock_<?php echo $i;?>">
								            <i class="fa fa-file-text"></i> Report #<?php echo "$i - "; echo $udata['businessname'];?>
								          </a>
								        </h4>
								    </div>
								    <div id="sNewPageManagerBlock_<?php echo $i;?>" class="panel-collapse collapse">
								        <div class="row">
											<div class="col-md-12">
												<?php
													echo '<h4>'.$casereports[$i]['title'].'</h4>'; 
													echo'<p>'.$casereports[$i]['details'].'</p>';
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