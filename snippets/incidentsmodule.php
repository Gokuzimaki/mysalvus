<?php
	// this module handles MySalvus Incidents
	// get the incident case reports
	function getAllCaseReports($viewer,$limit,$type="",$outt="",$eotype="editsinglecasereport"){
		$casereportq="SELECT * FROM casereports WHERE caseid='$cid' AND status='active'";
		$qdata=briefquery($casereportq,__LINE__,"mysqli",true);
		if($type!==""&&is_array($type)){
			$mtype=$type;
			if(isset($mtype['lastid'])||isset($mtype['nextid'])){
				$callpage="true";
				if(isset($mtype['lastid'])){
					$addq=" AND id>".$mtype['lastid'];
				}
				if(isset($mtype['nextid'])){
					$addq=" AND id<".$mtype['nextid'];
				}
			}
			$type=$mtype[0];
			$typeval=$mtype[1];
			$realoutputdata="$type][$typeval";
		}else{
			$realoutputdata=$type;
		}
		$realoutputdata.="|$outt|$eotype";
		str_replace("-", "", $limit);
		$testit=strpos($limit,"-");

		$testit?$limit="":$limit=$limit;
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
		// outputtype variable 
		$outputtype="casereports";
		$orderout="order by id desc";
		$queryorder="";
		$qout="";
		$qcat="WHERE";
		// array element for detecting and storing last active transfer entry
		if($type!==""&&!is_array($type)){
		}
		if($type=="spid"){
			$casetransferq="SELECT * FROM casereports WHERE spid='$typeval' AND status='active' $limit";
			$rowmonitor['chiefquery']="SELECT * FROM casereports WHERE spid='$typeval' AND status='active'";
		}else if($type=="case"){
			$casereportsq="SELECT * FROM casereports WHERE caseid='$typeval' AND status='active' $limit";
			$rowmonitor['chiefquery']="SELECT * FROM casereports WHERE caseid='$typeval' AND status='active'";

		}else if($type=="single"){
			$casereportsq="SELECT * FROM casereports WHERE id='$typeval' $limit";
			$rowmonitor['chiefquery']="SELECT * FROM casereports WHERE id='$typeval'";

		}else if ($type=="pendingactive") {
			# code...
			$casereportsq="SELECT * FROM casereports WHERE caseid='$typeval' AND status='active' ORDER BY id desc $limit";
			$rowmonitor['chiefquery']="SELECT * FROM casereports WHERE caseid='$typeval' AND status='active' ORDER BY id desc";
			// echo $casereportsq;
		}else{
			$casereportsq="SELECT * FROM casereports WHERE status='active' $limit";
			$rowmonitor['chiefquery']="SELECT * FROM casereports WHERE status='active'";
		}
		$qdata=briefquery($casereportsq,__LINE__,"mysqli");
		$numrows=$qdata['numrows'];
		$top='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>Reporttype</th><th>FullName</th><th>Gender</th><th>Nature</th><th>Age</th><th>IncidentDate</th><th>Entry Date</th><th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		$top2='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>Reporttype</th><th>FullName</th><th>Gender</th><th>Nature</th><th>IncidentDate</th><th>Entry Date</th><th>CaseStatus</th><th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		
		$bottom='	</tbody>
				</table>';
		$adminoutput='<tr><td colspan="100%">No entries found</td></tr>';
		$adminoutput2='<tr><td colspan="100%">No entries found</td></tr>';
		$adminoutput3='<tr><td colspan="100%">No entries found</td></tr>';
		if($numrows>0){
			$adminoutput="";
			$adminoutput2="";
			$adminoutput3="";
			for($i=0;$i<$numrows;$i++){
				// data key for cases table
				/*
					id
					caseid
					spid
					entrydate
					title
					details
					status
				*/
				$row=$qdata['resultdata'][$i];
				$cid=$row['caseid'];
				// get original case data
				$cquery="SELECT * FROM cases WHERE id='$cid'";
				$qdata=briefquery($cquery,__LINE__,"mysqli");
				$iid=$qdata['resultdata'][0]['incidentid'];
				$idata=getSingleIncident($iid);
				$rout=$idata['reporttype'];
				if($idata['reporttype']=="thirdparty"){
					$rout="Witness";
				}else if($idata['reporttype']=="self"){
					$rout="Self";
				}
				// reporttype - fullname - gender - Nature - Age - Incidentdate - entrydate - resolutionstatus - status
				$adminoutput.='<tr data-id="'.$row['id'].'">
									<td>'.$rout.'</td><td>'.$idata['rfullname'].'</td><td>'.$idata['rgender'].'</td><td>'.$idata['incidentnature'].'</td><td>'.$idata['sage'].'</td><td>'.$idata['datefancy'].'</td><td>'.$idata['entrydate'].'</td><td>'.$row['status'].'</td>
									<td name="trcontrolpoint">
										<a href="#&id='.$row['id'].'" name="edit" data-type="'.$eotype.'" data-divid="'.$row['id'].'">Edit</a>
									</td>
								</tr>
								<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$row['id'].'">
									<td colspan="100%">
										<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$row['id'].'">
											<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$row['id'].'">
												
											</div>
										</div>
									</td>
								</tr>';
				// reporttype - fullname - gender - Nature - Incidentdate - entrydate - status
				$adminoutput2.='<tr data-id="'.$row['id'].'">
									<td>'.$rout.'</td><td>'.$idata['rfullname'].'</td><td>'.$idata['rgender'].'</td><td>'.$idata['incidentnature'].'</td><td>'.$idata['datefancy'].'</td><td>'.$idata['eddatefancy'].'</td><td>'.$row['acceptancestatus'].'</td><td>'.$row['status'].'</td>
									<td name="trcontrolpoint">
										<a href="#&id='.$row['id'].'" name="edit" data-type="'.$eotype.'" data-divid="'.$row['id'].'">Edit</a>
									</td>
								</tr>
								<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$row['id'].'">
									<td colspan="100%">
										<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$row['id'].'">
											<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$row['id'].'">
												
											</div>
										</div>
									</td>
								</tr>';
				// reporttype - fullname - gender - state - Incidentdate - entrydate - status
				$adminoutput3.='<tr data-id="'.$row['id'].'">
									<td>'.$rout.'</td><td>'.$idata['rfullname'].'</td><td>'.$idata['rgender'].'</td><td>'.$idata['rstate'].'</td><td>'.$idata['datefancy'].'</td><td>'.$idata['entrydate'].'</td><td>'.$row['status'].'</td>
									<td name="trcontrolpoint">
										<a href="#&id='.$row['id'].'" name="edit" data-type="'.$eotype.'" data-divid="'.$row['id'].'">Edit</a>
									</td>
								</tr>
								<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$row['id'].'">
									<td colspan="100%">
										<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$row['id'].'">
											<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$row['id'].'">
												
											</div>
										</div>
									</td>
								</tr>';	
			}
		}
		// pagination portion
		$outs=paginatejavascript($rowmonitor['chiefquery']);
		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.str_replace("*", "_asterisk_", $rowmonitor['chiefquery']).'"/>
				<input type="hidden" name="outputtype" value="'.$outputtype.'|'.$realoutputdata.'"/>
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
		$row['numrows']=$numrows;
		$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
		$row['adminoutputtwo']=$top.$adminoutput.$bottom;
		// user view
		$row['adminoutput2']=$paginatetop.$top2.$adminoutput2.$bottom.$paginatebottom;
		$row['adminoutputtwo2']=$top2.$adminoutput2.$bottom;
		// client view
		$row['adminoutput3']=$paginatetop.$top.$adminoutput3.$bottom.$paginatebottom;
		$row['adminoutputtwo3']=$top.$adminoutput3.$bottom;
		$row['casetransfer']=$qdata['resultdata'];
		$row['casetransfercount']=$qdata['numrows'];
		return $row;
		$row['casereports']=$qdata['resultdata'];
		$row['casereportcount']=$qdata['numrows'];
		return $row;
	}
	function getAllCaseTransfers($viewer,$limit,$type="",$outt="",$eotype="editsinglecasetransfer"){
		if($type!==""&&is_array($type)){
			$mtype=$type;
			if(isset($mtype['lastid'])||isset($mtype['nextid'])){
				$callpage="true";
				if(isset($mtype['lastid'])){
					$addq=" AND id>".$mtype['lastid'];
				}
				if(isset($mtype['nextid'])){
					$addq=" AND id<".$mtype['nextid'];
				}
			}
			$type=$mtype[0];
			$typeval=$mtype[1];
			$realoutputdata="$type][$typeval";
		}else{
			$realoutputdata=$type;
		}
		$realoutputdata.="|$outt|$eotype";
		str_replace("-", "", $limit);
		$testit=strpos($limit,"-");

		$testit?$limit="":$limit=$limit;
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
		// outputtype variable 
		$outputtype="casetransfer";
		$orderout="order by id desc";
		$queryorder="";
		$qout="";
		$qcat="WHERE";
		// array element for detecting and storing last active transfer entry
		if($type!==""&&!is_array($type)){
		}
		if($type=="spid"){
			$casetransferq="SELECT * FROM casetransfer WHERE spid='$typeval' AND acceptancestatus='pending' AND status='active' $limit";
			$rowmonitor['chiefquery']="SELECT * FROM casetransfer WHERE spid='$typeval' AND acceptancestatus='pending' AND status='active'";
		}elseif($type=="spidactive"){
			$casetransferq="SELECT * FROM casetransfer WHERE spid='$typeval' AND acceptancestatus='pending' AND status='active' $limit";
			$rowmonitor['chiefquery']="SELECT * FROM casetransfer WHERE spid='$typeval' AND acceptancestatus='pending' AND status='active'";
		}else if($type=="ispid"){
			$casetransferq="SELECT * FROM casetransfer WHERE ispid='$typeval' AND acceptancestatus='pending' AND status='active' $limit";
			$rowmonitor['chiefquery']="SELECT * FROM casetransfer WHERE ispid='$typeval' AND acceptancestatus='pending' AND status='active'";

		}else if($type=="single"){
			$casetransferq="SELECT * FROM casetransfer WHERE id='$typeval' $limit";
			$rowmonitor['chiefquery']="SELECT * FROM casetransfer WHERE id='$typeval'";

		}else if ($type=="pendingactive") {
			# code...
			$casetransferq="SELECT * FROM casetransfer WHERE caseid='$typeval' AND status='active' ORDER BY id desc $limit";
			$rowmonitor['chiefquery']="SELECT * FROM casetransfer WHERE caseid='$typeval' AND status='active' ORDER BY id desc";
			// echo $casetransferq;
		}else{
			$casetransferq="SELECT * FROM casetransfer WHERE status='active' $limit";
			$rowmonitor['chiefquery']="SELECT * FROM casetransfer WHERE status='active'";
		}
		$qdata=briefquery($casetransferq,__LINE__,"mysqli");
		$numrows=$qdata['numrows'];
		$top='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>Reporttype</th><th>FullName</th><th>Gender</th><th>Nature</th><th>Age</th><th>IncidentDate</th><th>Entry Date</th><th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		$top2='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>Reporttype</th><th>FullName</th><th>Gender</th><th>Nature</th><th>IncidentDate</th><th>Entry Date</th><th>CaseStatus</th><th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		
		$bottom='	</tbody>
				</table>';
		$adminoutput='<tr><td colspan="100%">No entries found</td></tr>';
		$adminoutput2='<tr><td colspan="100%">No entries found</td></tr>';
		$adminoutput3='<tr><td colspan="100%">No entries found</td></tr>';
		if($numrows>0){
			$adminoutput="";
			$adminoutput2="";
			$adminoutput3="";
			for($i=0;$i<$numrows;$i++){
				// data key for cases table
				$row=$qdata['resultdata'][$i];
				$iid=$row['incidentid'];
				$idata=getSingleIncident($iid);
				// reporttype - fullname - gender - Nature - Age - Incidentdate - entrydate - resolutionstatus - status
				$adminoutput.='<tr data-id="'.$row['id'].'">
									<td>'.$idata['reporttypeout'].'</td><td>'.$idata['rfullname'].'</td><td>'.$idata['rgender'].'</td><td>'.$idata['incidentnature'].'</td><td>'.$idata['sage'].'</td><td>'.$idata['datefancy'].'</td><td>'.$idata['entrydate'].'</td><td>'.$row['status'].'</td>
									<td name="trcontrolpoint">
										<a href="#&id='.$row['id'].'" name="edit" data-type="'.$eotype.'" data-divid="'.$row['id'].'">Edit</a>
									</td>
								</tr>
								<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$row['id'].'">
									<td colspan="100%">
										<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$row['id'].'">
											<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$row['id'].'">
												
											</div>
										</div>
									</td>
								</tr>';
				// reporttype - fullname - gender - Nature - Incidentdate - entrydate - status
				$adminoutput2.='<tr data-id="'.$row['id'].'">
									<td>'.$idata['reporttypeout'].'</td><td>'.$idata['rfullname'].'</td><td>'.$idata['rgender'].'</td><td>'.$idata['incidentnature'].'</td><td>'.$idata['datefancy'].'</td><td>'.$idata['eddatefancy'].'</td><td>'.$row['acceptancestatus'].'</td><td>'.$row['status'].'</td>
									<td name="trcontrolpoint">
										<a href="#&id='.$row['id'].'" name="edit" data-type="'.$eotype.'" data-divid="'.$row['id'].'">Edit</a>
									</td>
								</tr>
								<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$row['id'].'">
									<td colspan="100%">
										<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$row['id'].'">
											<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$row['id'].'">
												
											</div>
										</div>
									</td>
								</tr>';
				// reporttype - fullname - gender - state - Incidentdate - entrydate - status
				$adminoutput3.='<tr data-id="'.$row['id'].'">
									<td>'.$idata['reporttypeout'].'</td><td>'.$idata['rfullname'].'</td><td>'.$idata['rgender'].'</td><td>'.$idata['rstate'].'</td><td>'.$idata['datefancy'].'</td><td>'.$idata['entrydate'].'</td><td>'.$row['status'].'</td>
									<td name="trcontrolpoint">
										<a href="#&id='.$row['id'].'" name="edit" data-type="'.$eotype.'" data-divid="'.$row['id'].'">Edit</a>
									</td>
								</tr>
								<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$row['id'].'">
									<td colspan="100%">
										<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$row['id'].'">
											<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$row['id'].'">
												
											</div>
										</div>
									</td>
								</tr>';	
			}
		}
		// pagination portion
		$outs=paginatejavascript($rowmonitor['chiefquery']);
		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.str_replace("*", "_asterisk_", $rowmonitor['chiefquery']).'"/>
				<input type="hidden" name="outputtype" value="'.$outputtype.'|'.$realoutputdata.'"/>
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
		$row['numrows']=$numrows;
		$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
		$row['adminoutputtwo']=$top.$adminoutput.$bottom;
		// user view
		$row['adminoutput2']=$paginatetop.$top2.$adminoutput2.$bottom.$paginatebottom;
		$row['adminoutputtwo2']=$top2.$adminoutput2.$bottom;
		// client view
		$row['adminoutput3']=$paginatetop.$top.$adminoutput3.$bottom.$paginatebottom;
		$row['adminoutputtwo3']=$top.$adminoutput3.$bottom;
		$row['casetransfer']=$qdata['resultdata'];
		$row['casetransfercount']=$qdata['numrows'];
		return $row;
		
	}
	function getAllCases($viewer,$limit,$type,$outt="",$eotype="editsinglecaseadmin"){
		$row=array();
		if($type!==""&&is_array($type)){
			$mtype=$type;
			if(isset($mtype['lastid'])||isset($mtype['nextid'])){
				$callpage="true";
				if(isset($mtype['lastid'])){
					$addq=" AND id>".$mtype['lastid'];
				}
				if(isset($mtype['nextid'])){
					$addq=" AND id<".$mtype['nextid'];
				}
			}
			$type=$mtype[0];
			$typeval=$mtype[1];
			$realoutputdata="$type][$typeval";
		}else{
			$realoutputdata=$type;
		}
		$realoutputdata.="|$outt|$eotype";
		str_replace("-", "", $limit);
		$testit=strpos($limit,"-");

		$testit?$limit="":$limit=$limit;
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
		// outputtype variable 
		$outputtype="cases";
		$orderout="order by id desc";
		$queryorder="";
		$qout="";
		$qcat="WHERE";

		if($type=="spview"){
			// echo $type;
			$qout="$qcat spid='$typeval'";
		}
		if($type=="spidinactive"){
			// echo $type;
			// $orderout=" GROUP BY caseid";
			$qout="$qcat spid='$typeval' AND resolutionstatus='ongoing'";
		}
		if($type=="single"){
			$qout="$qcat id='$typeval'";

		}
		$queryorder=$qout;
		if($queryorder==""&&$viewer=="viewer"){
			$queryorder="WHERE";
		}else if($queryorder!==""&&$viewer=="viewer"){
			$queryorder.=" AND ";
		}
		if($viewer=="admin"){
			$query="SELECT * FROM cases $queryorder $orderout $limit";
			$rowmonitor['chiefquery']="SELECT * FROM cases $queryorder $orderout";
		}else if($viewer=="viewer"){
			$query="SELECT * FROM cases $queryorder status='active' $orderout $limit";
			$rowmonitor['chiefquery']="SELECT * FROM cases $queryorder status='active' $orderout";
		}
		// echo $query;
		$qdata=briefquery($query,__LINE__,"mysqli",true);
		$numrows=$qdata['numrows'];
		$top='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>Reporttype</th><th>FullName</th><th>Gender</th><th>Nature</th><th>Age</th><th>IncidentDate</th><th>Entry Date</th><th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		$top2='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>Reporttype</th><th>FullName</th><th>Gender</th><th>Nature</th><th>IncidentDate</th><th>Entry Date</th><th>CaseStatus</th><th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		
		$bottom='	</tbody>
				</table>';
		$adminoutput='<tr><td colspan="100%">No entries found</td></tr>';
		$adminoutput2='<tr><td colspan="100%">No entries found</td></tr>';
		$adminoutput3='<tr><td colspan="100%">No entries found</td></tr>';
		$selection="";
		if($numrows>0){
			$adminoutput="";
			$adminoutput2="";
			$adminoutput3="";
			for($i=0;$i<$numrows;$i++){
				$row=$qdata['resultdata'][$i];
				$iid=$row['incidentid'];
				$idata=getSingleIncident($iid);
				/*
					incidentid
					spid
					spnature
					entrydate
					resolutiondate
					resolutionstatus
					resolutionstatus
					status
				*/
				// reporttype - fullname - gender - Nature - Age - Incidentdate - entrydate - resolutionstatus - status
				$adminoutput.='<tr data-id="'.$row['id'].'">
									<td>'.$idata['reporttypeout'].'</td><td>'.$idata['rfullname'].'</td><td>'.$idata['rgender'].'</td><td>'.$idata['incidentnature'].'</td><td>'.$idata['sage'].'</td><td>'.$idata['datefancy'].'</td><td>'.$idata['entrydate'].'</td><td>'.$row['status'].'</td>
									<td name="trcontrolpoint">
										<a href="#&id='.$row['id'].'" name="edit" data-type="'.$eotype.'" data-divid="'.$row['id'].'">Edit</a>
									</td>
								</tr>
								<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$row['id'].'">
									<td colspan="100%">
										<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$row['id'].'">
											<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$row['id'].'">
												
											</div>
										</div>
									</td>
								</tr>';
				// reporttype - fullname - gender - Nature - Incidentdate - entrydate - status
				$adminoutput2.='<tr data-id="'.$row['id'].'">
									<td>'.$idata['reporttypeout'].'</td><td>'.$idata['rfullname'].'</td><td>'.$idata['rgender'].'</td><td>'.$idata['incidentnature'].'</td><td>'.$idata['datefancy'].'</td><td>'.$idata['entrydate'].'</td><td>'.$row['resolutionstatus'].'</td><td>'.$row['status'].'</td>
									<td name="trcontrolpoint">
										<a href="#&id='.$row['id'].'" name="edit" data-type="'.$eotype.'" data-divid="'.$row['id'].'">Edit</a>
									</td>
								</tr>
								<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$row['id'].'">
									<td colspan="100%">
										<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$row['id'].'">
											<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$row['id'].'">
												
											</div>
										</div>
									</td>
								</tr>';
				// reporttype - fullname - gender - state - Incidentdate - entrydate - status
				$adminoutput3.='<tr data-id="'.$row['id'].'">
									<td>'.$idata['reporttypeout'].'</td><td>'.$idata['rfullname'].'</td><td>'.$idata['rgender'].'</td><td>'.$idata['rstate'].'</td><td>'.$idata['datefancy'].'</td><td>'.$idata['entrydate'].'</td><td>'.$row['status'].'</td>
									<td name="trcontrolpoint">
										<a href="#&id='.$row['id'].'" name="edit" data-type="'.$eotype.'" data-divid="'.$row['id'].'">Edit</a>
									</td>
								</tr>
								<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$row['id'].'">
									<td colspan="100%">
										<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$row['id'].'">
											<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$row['id'].'">
												
											</div>
										</div>
									</td>
								</tr>';	
			}
		}
		// pagination portion
		$outs=paginatejavascript($rowmonitor['chiefquery']);
		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.str_replace("*", "_asterisk_", $rowmonitor['chiefquery']).'"/>
				<input type="hidden" name="outputtype" value="'.$outputtype.'|'.$realoutputdata.'"/>
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
		$row['numrows']=$numrows;
		$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
		$row['adminoutputtwo']=$top.$adminoutput.$bottom;
		// user view
		$row['adminoutput2']=$paginatetop.$top2.$adminoutput2.$bottom.$paginatebottom;
		$row['adminoutputtwo2']=$top2.$adminoutput2.$bottom;
		// client view
		$row['adminoutput3']=$paginatetop.$top.$adminoutput3.$bottom.$paginatebottom;
		$row['adminoutputtwo3']=$top.$adminoutput3.$bottom;
		return $row;
	}
	function getSingleIncident($id,$type=""){
		include('globalsmodule.php');
		$row=array();
		$query="SELECT * FROM incident WHERE id=$id";
		$qdata=briefquery($query,__LINE__,"mysqli",true);
		$row=$qdata['resultdata'][0];
		/*// column keys
			id
			userid
			reporttype
			incidentfrequency - select
			incidentstarttime
			incidentreporttime
			creporttime
			incidentlocation
			incidentnature - select
			incidentnaturedetails
			weaponuse - select
			threats - select
			restraints - select
			weapons - select
			physicalinjury - select
			ireported - select
			ireporteddate
			ireportedlocation
			ireportedaid - select
			details
			abusercount
			aidentifiable
			survivorsname
			sgender - select
			sage
			saddress
			sphonesemail
			sstate - select
			spcmethod - select
			sdisability - select
			sdisabilitydetails
			admode
			entrydate
			status - values are 'active','inactive', 'saved'
		*/


		$row['rfullname']=$row['survivorsname'];
		$row['rincidentnature']=$row['incidentnature'];
		$row['rdetails']=$row['details'];
		if($row['incidentnature']=="other"){
			$row['rincidentnature']=$row['incidentnaturedetails'];

		}
		$row['reporttypeout']=$row['reporttype'];
		if($row['reporttype']=="thirdparty"){
			$row['reporttypeout']="Witness";
			$row['rdetails']=$row['admode'];

		}else if($row['reporttype']=="self"){
			$row['reporttypeout']="Self";

		}
		$ndata=explode(" ",$row['survivorsname']);
		$firstname=$ndata[0];
		$middlename=isset($ndata[1])?$ndata[1]:"";
		$lastname=isset($ndata[2])?$ndata[2]:"";
		$row['firstname']=$firstname;
		$row['middlename']=$middlename;
		$row['lastname']=$lastname;
		$row['rphone']=$row['sphone'];
		$row['remail']=$row['semail'];
		$row['rgender']=$row['sgender'];
		$row['rpcmethod']=$row['spcmethod'];

		if($row['userid']>0&&$row['reporttype']=="self"){
			// get the main user information since the report was posted by them
			$udata=getSingleUserPlain($row['userid']);
			$row['rfullname']=$udata['fullname'];
			$row['firstname']=$udata['firstname'];
			$row['middlename']=$udata['middlename'];
			$row['lastname']=$udata['lastname'];
			$row['rphone']=$udata['phonenumber'];
			$row['remail']=$udata['email'];
			$row['rgender']=$udata['gender'];
			$row['rpcmethod']=$udata['pcmethod'];
		}
		$sd=produceStates("",$row['sstate']);
		$row['rstate']=$sd['statename'];
		$numrows=$qdata['numrows'];
	  	$maindayout=date('D, d F, Y', strtotime($row['incidentreporttime']));
	  	$row['datefancy']=$maindayout;
	  	// incident start time fancy
	  	$maindayout=date('D, d F, Y', strtotime($row['incidentstarttime']));
	  	$row['istdatefancy']=$maindayout;
	  	// entry date fancy
	  	$maindayout=date('D, d F, Y', strtotime($row['entrydate']));
	  	$row['eddatefancy']=$maindayout;
		// get the abusers
		$query="SELECT * FROM abusers WHERE incidentid=$id";
		$qdata=briefquery($query,__LINE__,"mysqli",true);
		$row['abuserdata']=$qdata['resultdata'];
		// create string output of abuser information
		$abuserstrdata="";
		$abuserstrdata2="";
		for($i=0;$i<$qdata['numrows'];$i++){
			$t=$i+1;
			$fullname=$qdata['resultdata'][$i]['fullname'];
			$gender=$qdata['resultdata'][$i]['gender'];
			$aidentifiable=$qdata['resultdata'][$i]['aidentifiable'];
			$ar=$qdata['resultdata'][$i]['abuserrelationship'];
			$ardetails=$qdata['resultdata'][$i]['arelationshipdetails'];
			$abuserstrdata.="Abuser:\n
							Fullname: $fullname\n
							Gender: $gender\n
							Identifiable: $aidentifiable\n
							Relationship: $ar\n
							RelationshipDetails: $ardetails\n
			";
			$abuserstrdata2.="<b>Abuser Entry: $t</b><br>
							Fullname: $fullname<br>
							Gender: $gender<br>
							Identifiable: $aidentifiable<br>
							Relationship: $ar<br>
							RelationshipDetails: $ardetails<br>
			";
		}
		$row['abuserstrdata']=$abuserstrdata;
		$row['abuserstrdata2']=$abuserstrdata2;
		// support case queries
		$scasequery="";
		$mtype=array();
		if(is_array($type)){
			$mtype=$type;
			$type=$mtype[0];
			$typeval=$mtype[1];
			$realoutputdata="$type][$typeval";
			$scasequery=" AND $type ='$typeval' ";
		}else if($type!==""){
			$scasequery=" AND spnature ='$type' ";
		}
		// process cases/case requests/case reports and case transfers
		// get all cases running for this incident
		
		// pending requests
		$caserequestq="SELECT * FROM caserequests WHERE incidentid='$id' AND (acceptancestatus='pending' OR acceptancestatus='declined') AND status='active' $scasequery";
		$qdata=briefquery($caserequestq,__LINE__,"mysqli",true);
		$row['caserequests']=$qdata['resultdata'];
		$row['caserequestrows']=$qdata['numrows'];
		// case status
		$casesq="SELECT * FROM cases WHERE incidentid='$id' AND status='active' $scasequery";
		$qdata=briefquery($casesq,__LINE__,"mysqli",true);
		$row['cases']=$qdata['resultdata'];
		$row['caserows']=$qdata['numrows'];
		/*if($qdata['numrows']>0){

			// get reports
			$casereportq="SELECT * FROM casereports WHERE caseid='$' AND status='active' $scasequery";
			$qdata=briefquery($casereportq,__LINE__,"mysqli",true);
			$row['casereports']=$qdata['resultdata'];
			$row['casereportrows']=$qdata['numrows'];
		}*/
		// case transfer status
		$casetransferq="SELECT * FROM casetransfer WHERE incidentid='$id' AND status='active' $scasequery";
		$qdata=briefquery($casetransferq,__LINE__,"mysqli",true);
		$row['casetransfer']=$qdata['resultdata'];
		$row['casetransferrows']=$qdata['numrows'];
		return $row;
	}


	function getAllIncidents($viewer,$limit,$type="",$outt="",$eotype="editsingleincidentadmin"){
		include('globalsmodule.php');
		$typeval="";
		// eotype argument is for editoutputtype this is a value that controls
		// the single edit format to be displayed per entry

		// variable for capturing the total content data of the type argument
		// when it is an array
		$mtype=array();
		$realoutputdata="";
		$callpage="";
		$lastid=0;
		$nextid=0;
		$addq="";
		if($type!==""&&is_array($type)){
			$mtype=$type;
			if(isset($mtype['lastid'])||isset($mtype['nextid'])){
				$callpage="true";
				if(isset($mtype['lastid'])){
					$addq=" AND id>".$mtype['lastid'];
				}
				if(isset($mtype['nextid'])){
					$addq=" AND id<".$mtype['nextid'];
				}
			}
			$type=$mtype[0];
			$typeval=$mtype[1];
			$realoutputdata="$type][$typeval";
		}else{
			$realoutputdata=$type;
		}
		$realoutputdata.="|$outt|$eotype";
		str_replace("-", "", $limit);
		$testit=strpos($limit,"-");

		$testit?$limit="":$limit=$limit;
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
		// outputtype variable 
		$outputtype="incidents";

		// the td control link name data
		$onamedata="";
		$onameout="Edit";

		//control the initial nature of user query 
		$userstatdata="status='active'";

		$orderout="order by id desc";
		$queryorder="";
		$qout="";
		$qcat="WHERE";

		if($type=="userview"){
			// echo $type;
			$qout="$qcat userid='$typeval'";
		}
		if($type=="clientview"){
			// echo $type;
			$udata=getSingleUserPlain($typeval);
			$biznature=$udata['businessnature'];
			$bizstate=$udata['state'];
			$qout="$qcat id>0 AND sstate='$bizstate' AND NOT EXISTS (SELECT * FROM cases WHERE incidentid=`incident`.`id` AND spnature='$biznature' AND (resolutionstatus='ongoing' OR resolutionstatus='transfer' OR resolutionstatus='resolved') AND status='active') 
			AND NOT EXISTS (SELECT * FROM caserequests WHERE incidentid=`incident`.`id` AND spid='$typeval' AND (acceptancestatus='pending' OR acceptancestatus='declined'))"; 
		}
		if($type=="clientcaserequests"){
			// echo $type;
			$udata=getSingleUserPlain($typeval);
			$biznature=$udata['businessnature'];
			$bizstate=$udata['state'];
			$qout="$qcat id>0 AND EXISTS (SELECT * FROM caserequests WHERE incidentid=`incident`.`id` AND spnature='$biznature' AND spid='$typeval' AND acceptancestatus='pending') "; 
		}

		if($type=="clientcaseview"){
			// echo $type;
			$onamedata='data-oname="Open"';
			$onameout='Open';
			$udata=getSingleUserPlain($typeval);
			$biznature=$udata['businessnature'];
			$bizstate=$udata['state'];
			$qout="$qcat id>0 AND EXISTS (SELECT * FROM cases WHERE incidentid=`incident`.`id` AND spnature='$biznature' AND spid='$typeval' AND resolutionstatus='ongoing') "; 
		}

		// control for the admin account to view only active inactive saved entries
		if($type=="nodisabled"){
			$qout.=" $qcat (status='active' OR status='inactive' OR status='saved') ";
		}
		if($type=="nosaved"){
			$qout.=" $qcat (status='active' OR status='inactive' OR status='disabled') ";
		}
		if($type=="saved"){
			$userstatdata="";
			$qout.=" $qcat status='saved' ";
		}
		if($type=="state"){
			$queryorder=$typeval==""?"ORDER BY sstate,id DESC":"WHERE sstate='$typeval' ORDER BY id DESC";
		}else if($type=="gender"){
			$queryorder=$typeval!==""?"WHERE sgender='$typeval' OR EXISTS(SELECT * FROM `users` WHERE usertype='user' AND id=`incident`.`userid` AND gender='$typeval')":"";
		}else if($type=="day"){
			$orderout="ORDER BY id DESC";
			$qout.=" $qcat incidentreporttime=$typeval ";

		}else if($type=="incidentnature"){
			$orderout="ORDER BY id DESC";
			$qout.=" $qcat incidentnature=$typeval ";

		}else if($type=="available"){
			$qout.=" $qcat NOT EXISTS(SELECT * FROM `cases` WHERE id=`incident`.`id` AND status='active')";

		}else if($type=="availablenature"){
			$tp=$typeval;
			$exp=explode(":*:", $typeval);
			$typeval="AND (";
			for($i=0;$i<count($exp);$i++){
				$typeval.='spnature=\''.$exp[$i].'\' ';
			}
			$typeval.=")";
			if($tp==""){
				$typeval="";
			}
			$qout.=" $qcat NOT EXISTS(SELECT * FROM `cases` WHERE id=`incident`.`id` AND status='active' $typeval)";

		}else if($type=="daterange"){
			$cqvd=explode("-*-", $typeval);
			$d1=isset($cqvd[0])?$cqvd[0]:"";
			$d2=isset($cqvd[1])?$cqvd[1]:"";
			$td1=new DateTime($d1);
			$td2=new DateTime($d2);
			if($td1>$td2){
				$dd=$d2;
				$d2=$d1;
				$d1=$dd;
			}
			if($a1!==""&& $a2!==""){
				$aout="age>=$a1 AND age<=$a2";
			}
			if($a1!==""&& $a2==""){
				$aout="age<=$a1";
			}
			if($a1==""&& $a2!==""){
				$aout="age>=$a2";
			}
			$qout.=" $qcat $aout";
			$orderout="ORDER BY id DESC";
		}else if($type=="combo"){
			// break the typeval variable into properties and values set
			// delimiter between properties and values are :
			// delimiter between each property set **
			$propvaldata=explode(":", $typeval);
			$propdata=explode("**", $propvaldata[0]);
			$valdata=explode("**", $propvaldata[1]);
			$qout="";
			$orderout="";
			$qcat="WHERE";
			for($i=0;$i<count($propdata);$i++){
				// current querytype and queryvalue
				$cqtype=$propdata[$i];
				$cqval=isset($valdata[$i])?$valdata[$i]:"";
				if($qout!==""){
					$qcat="AND";
				}
				
				if($type=="clientview"){
					// echo $type;
					$udata=getSingleUserPlain($cqval);
					$biznature=$udata['businessnature'];
					$bizstate=$udata['state'];
					$qout.="$qcat id>0 AND sstate='$bizstate' AND NOT EXISTS (SELECT * 
						FROM cases WHERE incidentid=`incident`.`id` AND 
						spnature='$biznature' AND (resolutionstatus='ongoing' OR 
						resolutionstatus='transfer' OR resolutionstatus='resolved') AND 
						status='active') AND NOT EXISTS (SELECT * FROM caserequests 
						WHERE incidentid=`incident`.`id` AND spid='$typeval' AND 
						(acceptancestatus='pending' OR acceptancestatus='declined'))"; 
				}
				if($type=="clientcaserequests"){
					// echo $type;
					$udata=getSingleUserPlain($cqval);
					$biznature=$udata['businessnature'];
					$bizstate=$udata['state'];
					$qout.="$qcat id>0 AND EXISTS (SELECT * FROM caserequests WHERE incidentid=`incident`.`id` AND spnature='$biznature' AND spid='$typeval' AND acceptancestatus='pending') "; 
				}

				if($type=="clientcaseview"){
					// echo $type;
					$onamedata='data-oname="Open"';
					$onameout='Open';
					$udata=getSingleUserPlain($cqval);
					$biznature=$udata['businessnature'];
					$bizstate=$udata['state'];
					$qout.="$qcat id>0 AND EXISTS (SELECT * FROM cases WHERE 
						incidentid=`incident`.`id` AND spnature='$biznature' AND 
						spid='$typeval' AND resolutionstatus='ongoing') "; 
				}

				// control for the admin account to view only active inactive saved entries
				if($cqtype=="nodisabled"){
					$qout.=" $qcat (status='active' OR status='inactive' OR status='saved') ";
				}
				if($cqtype=="nosaved"){
					$qout.=" $qcat (status='active' OR status='inactive' OR status='disabled') ";
				}
				if($cqtype=="usersafe"){
					$qout.=" $qcat (status='active' OR status='inactive' OR status='disabled') ";
				}
				if($cqtype=="saved"){
					$userstatdata="";
					$qout.=" $qcat status='saved' ";
				}
				if($cqtype=="state"&&$cqval!==""){
					$orderout="ORDER BY sstate,id";
					$qout.=" $qcat sstate=$cqval ";
				}
				if($cqtype=="gender"&&$cqval!==""){
					// $orderout="ORDER BY id DESC";
					$qout.=" $qcat sgender='$cqval' OR EXISTS(SELECT * FROM `users` WHERE usertype='user' AND id=`incident`.`userid` AND gender='$cqval') ";
				}
				if($cqtype=="day"&&$cqval!==""){
					// $orderout="ORDER BY id DESC";
					$qout.=" $qcat incidentreporttime='$cqval' ";

				}
				if($cqtype=="userview"){
					// echo $type;
					$qout.="$qcat userid='$cqval'";
				}
				if($cqtype=="incidentnature"&&$cqval!==""){
					// $orderout="ORDER BY id DESC";
					$qout.=" $qcat incidentnature='$cqval' ";

				}
				if($cqtype=="ireported"&&$cqval!==""){
					// $orderout="ORDER BY id DESC";
					$qout.=" $qcat ireported=$cqval ";
				}
				if($cqtype=="age"&&$cqval!==""){
					// $orderout="ORDER BY id DESC";
					$qout.=" $qcat sage=$cqval ";

				}
				if($cqtype=="agerange"&&$cqval!==""){
					$cqvd=explode("-*-", $cqval);
					$a1=$cqvd[0];
					$a2=$cqvd[1];
					if($a1!==""||$a2!==""){
						if($a1>$a2){
							$ad=$a2;
							$a2=$a1;
							$a1=$ad;
						}
						if($a1!==""&& $a2!==""){
							$aout="age>=$a1 AND age<=$a2";
						}
						if($a1!==""&& $a2==""){
							$aout="age<=$a1";
						}
						if($a1==""&& $a2!==""){
							$aout="age>=$a2";
						}
						// $orderout="ORDER BY id DESC";
						$qout.=" $qcat $aout";
					}

				}
				if($cqtype=="daterange"&&$cqval!==""){
					$cqvd=explode("-*-", $cqval);
					$d1=$cqvd[0];
					$d2=$cqvd[1];
					if($d1!==""||$d2!==""){
						if($d1>$d2){
							$dd=$d2;
							$d2=$d1;
							$d1=$dd;
						}
						if($d1!==""&& $d2!==""){
							$dout="incidentreporttime>=$d1 AND incidentreporttime<=$d2";
						}
						if($d1!==""&& $d2==""){
							$dout="incidentreporttime<=$d1";
						}
						if($d1==""&& $d2!==""){
							$dout="incidentreporttime>=$d1";
						}
						$qout.=" $qcat $dout";
						$orderout="ORDER BY id DESC";
					}
				}
				if($cqtype=="available"&&$cqval!==""){
					$qout.=" $qcat NOT EXISTS(SELECT * FROM `cases` WHERE id=`incident`.`id` AND status='active')";
				} 
				if($cqtype=="availablenature"&&$cqval!==""){
					$tp=$cqval;
					$exp=explode(":*:", $cqval);
					$typcqval="AND (";
					for($i=0;$i<count($exp);$i++){
						$cqval.='spnature=\''.$exp[$i].'\' ';
					}
					$cqval.=")";
					if($tp==""){
						$cqval="";
					}
					$qout.=" $qcat NOT EXISTS(SELECT * FROM `cases` WHERE id=`incident`.`id` AND status='active' $cqval)";					
				}
			}
		}
		$queryorder=$qout.$addq;
		// $realoutputdata=$rodata;
		if($queryorder==""&&$viewer=="viewer"){
			$queryorder="WHERE";
		}else if($queryorder!==""&&$viewer=="viewer"&&$userstatdata!==""){
			$queryorder.=" AND ";
		}
		// echo "$queryorder<br>";
		$row=array();
		if($viewer=="admin"){
			$query="SELECT * FROM incident $queryorder $orderout ".$limit."";
			$rowmonitor['chiefquery']="SELECT * FROM incident $queryorder $orderout";
		}elseif($viewer=="viewer"){
			$query="SELECT * FROM incident $queryorder $userstatdata
			$orderout ".$limit."";
			$rowmonitor['chiefquery']="SELECT * FROM incident $queryorder $userstatdata 
			$orderout";
		}else if(is_array($viewer)){
			$viewer=$viewer[0];
			$subtype=$viewer[1];
			$searchval=$viewer[2];

 		  	$outputtype="incidentsearch|$viewer";
 		  	$realoutputdata="$subtype][$searchval|$outt|$eotype";
			if($subtype=="username"&&$viewer=="admin"){
				$query="SELECT * FROM incident WHERE id>0 AND EXISTS(SELECT * FROM `users` WHERE usertype='user' AND fullname LIKE '%$searchval%' AND id=`incident`.`id` AND status='active') OR (survivorsname LIKE '%$searchval%') $limit";
		    	$rowmonitor['chiefquery']="SELECT * FROM incident WHERE id>0 AND EXISTS (SELECT * FROM `users` WHERE usertype='user' AND fullname LIKE '%$searchval%' AND id=`incident`.`id` AND status='active') OR (survivorsname LIKE '%$searchval%')";
			}elseif($subtype=="username"&&$viewer=="viewer"){
				$query="SELECT * FROM incident WHERE id>0 AND EXISTS(SELECT * FROM `users` WHERE usertype='user' AND fullname LIKE '%$searchval%' AND id=`incident`.`id` AND status='active') OR (survivorsname LIKE '%$searchval%') ORDER BY fullname" ;
		    	$rowmonitor['chiefquery']="SELECT * FROM incident WHERE id>0 AND EXISTS(SELECT * FROM `users` WHERE usertype='user' AND fullname LIKE '%$searchval%' AND id=`incident`.`id` AND status='active') OR (survivorsname LIKE '%$searchval%') ORDER BY fullname";
			}elseif($subtype=="incidenttatus"&&$viewer=="admin"){
				$query="SELECT * FROM incident WHERE status ='$searchval' $limit";
		    	$rowmonitor['chiefquery']="SELECT * FROM incident WHERE status ='$searchval'";
			}elseif($subtype=="useremail"&&$viewer=="admin"){
				$query="SELECT * FROM incident WHERE semail ='$searchval' OR EXISTS(SELECT * FROM `users` WHERE usertype='user' AND EMAIL LIKE '%$searchval%' AND id=`incident`.`id` AND status='active') $limit";
		    	$rowmonitor['chiefquery']="SELECT * FROM incident WHERE semail ='$searchval' OR EXISTS(SELECT * FROM `users` WHERE usertype='user' AND EMAIL LIKE '%$searchval%' AND id=`incident`.`id` AND status='active')";
			}elseif($subtype=="advancedincidentsearch"&&$viewer=="admin"){
				$query= $searchval." ".$limit;
		    	$rowmonitor['chiefquery']=$searchval;
			}else{
				echo "search parameters unrecognized, contact your developer";
			}
		}

		// echo $query;
		$qdata=briefquery($query,__LINE__,"mysqli");
		$numrows=$qdata['numrows'];
		$top='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>Reporttype</th><th>FullName</th><th>Gender</th><th>State</th><th>Age</th><th>IncidentDate</th><th>PhoneNumber</th><th>Email</th><th>Entry Date</th><th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		$top2='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>Reporttype</th><th>FullName</th><th>Gender</th><th>Nature</th><th>State</th><th>IncidentDate</th><th>Entry Date</th><th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		
		$bottom='	</tbody>
				</table>';
		$adminoutput='<tr><td colspan="100%">No entries found</td></tr>';
		$adminoutput2='<tr><td colspan="100%">No entries found</td></tr>';
		$adminoutput3='<tr><td colspan="100%">No entries found</td></tr>';
		$selection="";
		if($numrows>0){
			$adminoutput="";
			$adminoutput2="";
			$adminoutput3="";
			for($i=0;$i<$numrows;$i++){
				$id=$qdata['resultdata'][$i]['id'];
				$row=getSingleIncident($id);
				// reporttype - fullname - gender - state - Age - Incidentdate - Phonenumber - Email - entrydate - status
				$adminoutput.='<tr data-id="'.$row['id'].'">
									<td>'.$row['reporttypeout'].'</td><td>'.$row['rfullname'].'</td><td>'.$row['rgender'].'</td><td>'.$row['rstate'].'</td><td>'.$row['sage'].'</td><td>'.$row['datefancy'].'</td><td>'.$row['rphone'].'</td><td>'.$row['remail'].'</td><td>'.$row['entrydate'].'</td><td>'.$row['status'].'</td>
									<td name="trcontrolpoint">
										<a href="#&id='.$row['id'].'" name="edit" 
										data-type="'.$eotype.'" data-divid="'.$row['id'].'" 
										'.$onamedata.'>'.$onameout.'</a>
									</td>
								</tr>
								<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$row['id'].'">
									<td colspan="100%">
										<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$row['id'].'">
											<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$row['id'].'">
												
											</div>
										</div>
									</td>
								</tr>';
				// reporttype - fullname - gender - nature- state - 
				// Incidentdate - entrydate - status
				$adminoutput2.='<tr data-id="'.$row['id'].'">
									<td>'.$row['reporttypeout'].'</td>
									<td>'.$row['rfullname'].'</td>
									<td>'.$row['rgender'].'</td>
									<td>'.$row['rincidentnature'].'</td>
									<td>'.$row['rstate'].'</td>
									<td>'.$row['datefancy'].'</td>
									<td>'.$row['entrydate'].'</td>
									<td>'.$row['status'].'</td>
									<td name="trcontrolpoint">
										<a href="#&id='.$row['id'].'" name="edit" 
										data-type="'.$eotype.'" '.$onamedata.' 
										data-divid="'.$row['id'].'">'.$onameout.'</a>
									</td>
								</tr>
								<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$row['id'].'">
									<td colspan="100%">
										<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$row['id'].'">
											<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$row['id'].'">
												
											</div>
										</div>
									</td>
								</tr>';
				// reporttype - fullname - gender - state - Incidentdate - entrydate - status
				$adminoutput3.='<tr data-id="'.$row['id'].'">
									<td>'.$row['reporttypeout'].'</td><td>'.$row['rfullname'].'</td><td>'.$row['rgender'].'</td><td>'.$row['rstate'].'</td><td>'.$row['datefancy'].'</td><td>'.$row['entrydate'].'</td><td>'.$row['status'].'</td>
									<td name="trcontrolpoint">
										<a href="#&id='.$row['id'].'" name="edit" data-type="'.$eotype.'" data-divid="'.$row['id'].'">Edit</a>
									</td>
								</tr>
								<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$row['id'].'">
									<td colspan="100%">
										<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$row['id'].'">
											<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$row['id'].'">
												
											</div>
										</div>
									</td>
								</tr>';
			}
		}
		// pagination portion
		$outs=paginatejavascript($rowmonitor['chiefquery']);
		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.str_replace("*", "_asterisk_", $rowmonitor['chiefquery']).'"/>
				<input type="hidden" name="outputtype" value="'.$outputtype.'|'.$realoutputdata.'"/>
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
		$row['numrows']=$numrows;
		$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
		$row['adminoutputtwo']=$top.$adminoutput.$bottom;
		// view two
		$row['adminoutput2']=$paginatetop.$top2.$adminoutput2.$bottom.$paginatebottom;
		$row['adminoutputtwo2']=$top2.$adminoutput2.$bottom;
		// view three
		$row['adminoutput3']=$paginatetop.$top.$adminoutput3.$bottom.$paginatebottom;
		$row['adminoutputtwo3']=$top.$adminoutput3.$bottom;
		return $row;
	}
	function exportIncidents($id=0,$type=""){
		// variable for capturing the total content data of the type argument
		// when it is an array
		$mtype=array();
		$realoutputdata="";
		if($type!==""&&is_array($type)){
			$mtype=$type;
			$type=isset($mtype[0])?$mtype[0]:"";
			$typeval=isset($mtype[1])?$mtype[1]:"";
			$realoutputdata="$type][$typeval";
		}else{
			$realoutputdata=$type;
		}
		$orderout="order by id desc";
		$queryorder="";
		$qout="";
		$qcat="WHERE";
		if($type=="userview"){
			$queryorder="WHERE userid='$typeval' ORDER BY id DESC";
		}
		if($type=="state"){
			$queryorder=$typeval==""?"ORDER BY sstate,id DESC":"WHERE sstate='$typeval' ORDER BY id DESC";
		}else if($type=="gender"){
			$queryorder=$typeval!==""?"WHERE sgender='$typeval' OR EXISTS(SELECT * FROM `users` WHERE usertype='user' AND id=`incident`.`userid` AND gender='$typeval')":"";
		}else if($type=="day"){
			$orderout="ORDER BY id DESC";
			$qout.=" $qcat incidentreporttime=$typeval ";

		}else if($type=="incidentnature"){
			$orderout="ORDER BY id DESC";
			$qout.=" $qcat incidentnature=$typeval ";

		}else if($type=="available"){
			$qout.=" $qcat NOT EXISTS(SELECT * FROM `cases` WHERE id=`incident`.`id` AND status='active')";

		}else if($type=="availablenature"){
			$tp=$typeval;
			$exp=explode(":*:", $typeval);
			$typeval="AND (";
			for($i=0;$i<count($exp);$i++){
				$typeval.='spnature=\''.$exp[$i].'\' ';
			}
			$typeval.=")";
			if($tp==""){
				$typeval="";
			}
			$qout.=" $qcat NOT EXISTS(SELECT * FROM `cases` WHERE id=`incident`.`id` AND status='active' $typeval)";

		}else if($type=="daterange"){
			$cqvd=explode("-*-", $typeval);
			$d1=isset($cqvd[0])?$cqvd[0]:"";
			$d2=isset($cqvd[1])?$cqvd[1]:"";
			$td1=new DateTime($d1);
			$td2=new DateTime($d2);
			if($td1>$td2){
				$dd=$d2;
				$d2=$d1;
				$d1=$dd;
			}
			if($a1!==""&& $a2!==""){
				$aout="age>=$a1 AND age<=$a2";
			}
			if($a1!==""&& $a2==""){
				$aout="age<=$a1";
			}
			if($a1==""&& $a2!==""){
				$aout="age>=$a2";
			}
			$qout.=" $qcat $aout";
			$orderout="ORDER BY id DESC";
		}else if($type=="combo"){
			// break the typeval variable into properties and values set
			// delimiter between properties and values are :
			// delimiter between each property set **
			$propvaldata=explode(":", $typeval);
			$propdata=explode("**", $propvaldata[0]);
			$valdata=explode("**", $propvaldata[1]);
			$qout="";
			$orderout="";
			$qcat="WHERE";
			for($i=0;$i<count($propdata);$i++){
				// current querytype and queryvalue
				$cqtype=$propdata[$i];
				$cqval=$valdata[$i];
				if($qout!==""){
					$qcat="AND";
				}
				
				if($cqtype=="state"&&$cqval!==""){
					$orderout="ORDER BY sstate,id";
					$qout.=" $qcat sstate=$cqval ";
				}
				if($cqtype=="gender"&&$cqval!==""){
					// $orderout="ORDER BY id DESC";
					$qout.=" $qcat sgender='$cqval' OR EXISTS(SELECT * FROM `users` WHERE usertype='user' AND id=`incident`.`userid` AND gender='$cqval') ";
				}
				if($cqtype=="day"&&$cqval!==""){
					// $orderout="ORDER BY id DESC";
					$qout.=" $qcat incidentreporttime='$cqval' ";

				}
				if($cqtype=="incidentnature"&&$cqval!==""){
					// $orderout="ORDER BY id DESC";
					$qout.=" $qcat incidentnature='$cqval' ";

				}
				if($cqtype=="ireported"&&$cqval!==""){
					// $orderout="ORDER BY id DESC";
					$qout.=" $qcat ireported=$cqval ";
				}
				if($cqtype=="age"&&$cqval!==""){
					// $orderout="ORDER BY id DESC";
					$qout.=" $qcat sage=$cqval ";

				}
				if($cqtype=="agerange"&&$cqval!==""){
					$cqvd=explode("-*-", $cqval);
					$a1=$cqvd[0];
					$a2=$cqvd[1];
					if($a1!==""||$a2!==""){
						if($a1>$a2){
							$ad=$a2;
							$a2=$a1;
							$a1=$ad;
						}
						if($a1!==""&& $a2!==""){
							$aout="sage>=$a1 AND sage<=$a2";
						}
						if($a1!==""&& $a2==""){
							$aout="sage<=$a1";
						}
						if($a1==""&& $a2!==""){
							$aout="sage>=$a2";
						}
						// $orderout="ORDER BY id DESC";
						$qout.=" $qcat $aout";
					}

				}
				if($cqtype=="daterange"&&$cqval!==""){
					$cqvd=explode("-*-", $cqval);
					$d1=$cqvd[0];
					$d2=$cqvd[1];
					if($d1!==""||$d2!==""){
						if($d1>$d2){
							$dd=$d2;
							$d2=$d1;
							$d1=$dd;
						}
						if($d1!==""&& $d2!==""){
							$dout="incidentreporttime>=$d1 AND incidentreporttime<=$d2";
						}
						if($d1!==""&& $d2==""){
							$dout="incidentreporttime<=$d1";
						}
						if($d1==""&& $d2!==""){
							$dout="incidentreporttime>=$d1";
						}
						$qout.=" $qcat $dout";
						$orderout="ORDER BY id DESC";
					}
				}
				if($cqtype=="available"&&$cqval!==""){
					$qout.=" $qcat NOT EXISTS(SELECT * FROM `cases` WHERE id=`incident`.`id` AND status='active')";
				} 
				if($cqtype=="availablenature"&&$cqval!==""){
					$tp=$cqval;
					$exp=explode(":*:", $cqval);
					$typcqval="AND (";
					for($i=0;$i<count($exp);$i++){
						$cqval.='spnature=\''.$exp[$i].'\' ';
					}
					$cqval.=")";
					if($tp==""){
						$cqval="";
					}
					$qout.=" $qcat NOT EXISTS(SELECT * FROM `cases` WHERE id=`incident`.`id` AND status='active' $cqval)";					
				}
			}
		}
		// var_dump($mtype['anonymous']);
		$queryorder=$qout;
		if($id==0){
			$query="SELECT * FROM incident $queryorder $orderout";
		}else{
			$query="SELECT * FROM incident WHERE id='$id'";

		}
		// echo $query;
		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
		$numrows=mysql_num_rows($run);
		if($numrows>0){

			$adminoutput="";
			$adminoutputtwo="";
			$vieweroutput="";
			// reporttype - fullname - gender - state - Age - Incidentdate - Phonenumber - Email - entrydate - status

			require_once 'phpexcel/Classes/PHPExcel.php';
		    $objPHPExcel = new PHPExcel();
		    // initialise setup variable
		     // Set properties
		    $objPHPExcel->getProperties()->setCreator("MySalvus Administrator")
		                ->setLastModifiedBy("Admin Section User")
		                ->setTitle("Incident List")
		                ->setSubject("Generated on ".date("Y-m-d H:i:s")."")
		                ->setDescription("Document contains MySalvus incidents data.")
		                ->setKeywords("office 2007 openxml php")
		                ->setCategory("Incident Files");
		    // $objPHPExcel->getActiveSheet()->setTitle("Course Transactions List");
				if($mtype['anonymous']=="anonymous"){
					// echo "anonymous";
					$objPHPExcel->setActiveSheetIndex(0)
		                ->setCellValue('A1', 'Fullname')
		                ->setCellValue('B1', 'Gender')
		                ->setCellValue('C1', 'State')
		                ->setCellValue('D1', 'Age')
		                ->setCellValue('E1', 'ReportType')
		                ->setCellValue('F1', 'Incident Nature')
		                ->setCellValue('G1', 'Incident Frequency')
		                ->setCellValue('H1', 'Incident StartTime')
		                ->setCellValue('I1', 'Incident Date')
		                ->setCellValue('J1', 'Entry Date')
		                ->setCellValue('K1', 'Incident NatureDetails')
		                ->setCellValue('L1', 'Incident Location')
		                ->setCellValue('M1', 'Weapon/Threats/Restraint')
		                ->setCellValue('N1', 'Weapon')
		                ->setCellValue('O1', 'Threats')
		                ->setCellValue('P1', 'Restraint')
		                ->setCellValue('Q1', 'PhysicalInjury')
		                ->setCellValue('R1', 'Incident Reported Before')
		                ->setCellValue('S1', 'PreviousReportDate')
		                ->setCellValue('T1', 'PreviousReportLocation')
		                ->setCellValue('U1', 'Help Given For PreviousReport')
		                ->setCellValue('V1', 'Incident Details')
		                ->setCellValue('W1', 'Disability')
		                ->setCellValue('X1', 'Disability Details')
		                ->setCellValue('Y1', 'Abuse Discovery Mode')
		                ->setCellValue('Z1', 'Abuser Data');
				}else{
					$objPHPExcel->setActiveSheetIndex(0)
		                ->setCellValue('A1', 'Fullname')
		                ->setCellValue('B1', 'Gender')
		                ->setCellValue('C1', 'State')
		                ->setCellValue('D1', 'Age')
		                ->setCellValue('E1', 'Phone')
		                ->setCellValue('F1', 'Email')
		                ->setCellValue('G1', 'Preferred Contact Method')
		                ->setCellValue('H1', 'ReportType')
		                ->setCellValue('I1', 'Incident Nature')
		                ->setCellValue('J1', 'Incident Frequency')
		                ->setCellValue('K1', 'Incident StartTime')
		                ->setCellValue('L1', 'Incident Date')
		                ->setCellValue('M1', 'Entry Date')
		                ->setCellValue('N1', 'Incident NatureDetails')
		                ->setCellValue('O1', 'Incident Location')
		                ->setCellValue('P1', 'Weapon/Threats/Restraint')
		                ->setCellValue('Q1', 'Weapon')
		                ->setCellValue('R1', 'Threats')
		                ->setCellValue('S1', 'Restraint')
		                ->setCellValue('T1', 'PhysicalInjury')
		                ->setCellValue('U1', 'Incident Reported Before')
		                ->setCellValue('V1', 'PreviousReportDate')
		                ->setCellValue('W1', 'PreviousReportLocation')
		                ->setCellValue('X1', 'Help Given For PreviousReport')
		                ->setCellValue('Y1', 'Incident Details')
		                ->setCellValue('Z1', 'Disability')
		                ->setCellValue('AA1', 'Disability Details')
		                ->setCellValue('AB1', 'Abuse Discovery Mode')
		                ->setCellValue('AC1', 'Abuser Data');
				}
		    
            $count=2;
			while($row=mysql_fetch_assoc($run)){
				$outs=getSingleIncident($row['id']);
				if($mtype['anonymous']=="anonymous"){
					// echo "anonymous";
					$objPHPExcel->setActiveSheetIndex(0)
		                ->setCellValue("A".$count."", "".$outs["rfullname"]."")
		                ->setCellValue("B".$count."", "".$outs["rgender"]."")
		                ->setCellValue("C".$count."", "".$outs["rstate"]."")
		                ->setCellValue("D".$count."", "".$outs["sage"]."")
		                ->setCellValue("E".$count."", "".$outs["reporttype"]."")
		                ->setCellValue("F".$count."", "".$outs["incidentnature"]."")
		                ->setCellValue("G".$count."", "".$outs["incidentfrequency"]."")
		                ->setCellValue("H".$count."", "".$outs["incidentstarttime"]."")
		                ->setCellValue("I".$count."", "".$outs["incidentreporttime"]."")
		                ->setCellValue("J".$count."", "".$outs["entrydate"]."")
		                ->setCellValue("K".$count."", "".$outs["incidentnaturedetails"]."")
		                ->setCellValue("L".$count."", "".$outs["incidentlocation"]."")
		                ->setCellValue("M".$count."", "".$outs["weaponuse"]."")
		                ->setCellValue('N'.$count.'', ''.$outs['weapons'].'')
		                ->setCellValue('O'.$count.'', ''.$outs['threats'].'')
		                ->setCellValue('P'.$count.'', ''.$outs['restraints'].'')
		                ->setCellValue('Q'.$count.'', ''.$outs['physicalinjury'].'')
		                ->setCellValue('R'.$count.'', ''.$outs['ireported'].'')
		                ->setCellValue('S'.$count.'', ''.$outs['ireporteddate'].'')
		                ->setCellValue('T'.$count.'', ''.$outs['ireportedlocation'].'')
		                ->setCellValue('U'.$count.'', ''.$outs['ireportedaid'].'')
		                ->setCellValue('V'.$count.'', ''.$outs['details'].'')
		                ->setCellValue('W'.$count.'', ''.$outs['sdisability'].'')
		                ->setCellValue('X'.$count.'', ''.$outs['sdisabilitydetails'].'')
		                ->setCellValue('Y'.$count.'', ''.$outs['admode'].'')
		                ->setCellValue('Z'.$count.'', ''.$outs['abuserstrdata'].'');
				}else{	
					$objPHPExcel->setActiveSheetIndex(0)
		                ->setCellValue("A".$count."", "".$outs["rfullname"]."")
		                ->setCellValue("B".$count."", "".$outs["rgender"]."")
		                ->setCellValue("C".$count."", "".$outs["rstate"]."")
		                ->setCellValue("D".$count."", "".$outs["sage"]."")
		                ->setCellValue("E".$count."", "".$outs["rphone"]."")
		                ->setCellValue("F".$count."", "".$outs["remail"]."")
		                ->setCellValue("G".$count."", "".$outs["rpcmethod"]."")
		                ->setCellValue("H".$count."", "".$outs["reporttype"]."")
		                ->setCellValue("I".$count."", "".$outs["incidentnature"]."")
		                ->setCellValue("J".$count."", "".$outs["incidentfrequency"]."")
		                ->setCellValue("K".$count."", "".$outs["incidentstarttime"]."")
		                ->setCellValue("L".$count."", "".$outs["incidentreporttime"]."")
		                ->setCellValue("M".$count."", "".$outs["entrydate"]."")
		                ->setCellValue("N".$count."", "".$outs["incidentnaturedetails"]."")
		                ->setCellValue("O".$count."", "".$outs["incidentlocation"]."")
		                ->setCellValue("P".$count."", "".$outs["weaponuse"]."")
		                ->setCellValue('Q'.$count.'', ''.$outs['weapons'].'')
		                ->setCellValue('R'.$count.'', ''.$outs['threats'].'')
		                ->setCellValue('S'.$count.'', ''.$outs['restraints'].'')
		                ->setCellValue('T'.$count.'', ''.$outs['physicalinjury'].'')
		                ->setCellValue('U'.$count.'', ''.$outs['ireported'].'')
		                ->setCellValue('V'.$count.'', ''.$outs['ireporteddate'].'')
		                ->setCellValue('W'.$count.'', ''.$outs['ireportedlocation'].'')
		                ->setCellValue('X'.$count.'', ''.$outs['ireportedaid'].'')
		                ->setCellValue('Y'.$count.'', ''.$outs['details'].'')
		                ->setCellValue('Z'.$count.'', ''.$outs['sdisability'].'')
		                ->setCellValue('AA'.$count.'', ''.$outs['sdisabilitydetails'].'')
		                ->setCellValue('AB'.$count.'', ''.$outs['admode'].'')
		                ->setCellValue('AC'.$count.'', ''.$outs['abuserstrdata'].'');			
				}	

                $count++;
				
			}
			$objPHPExcel->setActiveSheetIndex(0);
			// require_once 'phpexcel/Classes/PHPExcel/IOFactory.php';
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="Incident_Data_.xlsx"');
			header('Cache-Control: max-age=0');
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			exit;
		}else{
			$row['donout']="There are currently no Approved";
		}
	}
?>