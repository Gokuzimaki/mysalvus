<?php
	function getSingleAdminUser($id){
		global $host_addr;
		$row=array();
		$query="SELECT * FROM admin where id=$id";
		$run=mysql_query($query)or die(mysql_error()." Line 7");
		$numrows=mysql_num_rows($run);
		$row=mysql_fetch_assoc($run);	
		$id=$row['id'];
		$username=$row['username'];
		$fullname=$row['fullname'];
		$password=$row['password'];
		$username=$row['username'];
		$accesslevel=$row['accesslevel'];
		$status=$row['status'];
		$accesslevelout="SupeUser";
		if($accesslevel==0){
			$accesslevelout="Super User";
		}else if ($accesslevel==1) {
			# code...
			$accesslevelout="SubUser";
		}/*else if ($accesslevel==2) {
			# code...
			$accesslevelout="CDS Admin";
		}else if ($accesslevel==3) {
			# code...
			$accesslevelout="SAED Admin";
		}*/
		$row['accesslevelout']=$accesslevelout;
		//get complete gallery images and create thumbnail where necessary
		$mediaquery="SELECT * FROM media WHERE ownerid=$id AND ownertype='adminuser' AND status='active' ORDER BY id DESC";
		$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 2846");
		$coverdata=mysql_fetch_assoc($mediarun);
		$coverphoto=$coverdata['location'];
		$coverphotosmall=$coverdata['details'];
		$medianumrows=mysql_num_rows($mediarun);
		$coverout=file_exists('.'.$coverphoto.'')&&$coverphoto!==""&&strlen($coverphoto)>0?'<img src="'.$host_addr.''.$coverphoto.'"/>':"No image set";
		$coverpathout=$coverdata['location']!==""?''.$host_addr.''.$coverphoto.'':"";
		$imgid=$coverdata['id'];
		if($medianumrows<1){
			$coverphoto="images/default.gif";
			$coverphotosmall="images/default.gif";
			$coverpathout=$coverout;
			$imgid=0;
		}else{

		}
		$row['absolutecover']=$host_addr.$coverphoto;
		$row['adminoutput']='
			<tr data-id="'.$id.'">
				<td class="tdimg">'.$coverout.'</td><td>'.$fullname.'</td><td>'.$accesslevelout.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsingleuserdata" data-divid="'.$id.'">Edit</a></td>
			</tr>
			<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'">
				<td colspan="100%">
					<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
						<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
							
						</div>
					</div>
				</td>
			</tr>
			';
		$row['adminoutputtwo']='
				<div class="col-md-12">
					<form name="contentform" method="POST" enctype="multipart/form-data" action="../snippets/edit.php">
	            		<input type="hidden" name="entryvariant" value="edituser"/>
	            		<input type="hidden" name="entryid" value="'.$id.'"/>
	            		<input type="hidden" name="coverid" value="'.$imgid.'"/>
						<div class="col-md-12">
				    		<div class="form-group">
				              <label>User Image</label>
				              <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-bars"></i>
				                  </div>
				                  <input type="file" class="form-control" name="contentpic" value=""/>
				               </div><!-- /.input group -->
				            </div>
				        </div>
				        <div class="col-md-12">
				            <label>Access Level</label>
				            <select name="accesslevel"  class="form-control">
			            	<option value="0">Super User</option>
			            	<option value="3">FJC User</option>
				  	      </select>
				        </div>
				        <div class="col-md-12" style="">
				    		<div class="form-group">
				              <label>Fullname</label>
				              <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-bars"></i>
				                  </div>
				                  <input type="text" class="form-control" name="fullname" value="'.$fullname.'" Placeholder="Provide the fullname"/>
				               </div><!-- /.input group -->
				            </div>
				        </div>
				        <div class="col-md-12" style="">
				    		<div class="form-group">
				              <label>Username</label>
				              <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-bars"></i>
				                  </div>
				                  <input type="text" class="form-control" name="username" value="'.$username.'" Placeholder="Provide the username"/>
				               </div><!-- /.input group -->
				            </div>
				        </div>
				        <div class="col-md-12" style="">
				    		<div class="form-group">
				              <label>Password (<b>'.$password.'</b>)</label>
				              <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-bars"></i>
				                  </div>
				                  <input type="password" class="form-control" name="password" value="" Placeholder="Change Password"/>
				               </div><!-- /.input group -->
				            </div>
				        </div>
						<div class="col-md-12">
	                        <label>Disable/Enable this</label>
	                        <select name="status" id="status" class="form-control">
	                        	<option value="">Choose</option>
	                        	<option value="active">Active</option>
	                        	<option value="inactive">Inactive</option>
					  	    </select>
	                    </div>
						
						<div class="col-md-12">
		        			<div class="box-footer">
			                    <input type="submit" class="btn btn-danger" name="submitcontent" value="Create/Update"/>
			                </div>
		            	</div>
		            </form>
		        </div>
		';
		$row['vieweroutput']="";
		return $row;
	}
	function getAllAdminUsers($viewer,$type,$limit){
		global $host_addr;
		$row=array();
		$outputtype="admin";
		$testit=strpos($limit,"-");
		$testit!==false?$limit="":$limit=$limit;
		$row=array();
		if($limit!==""&&$viewer=="admin"){
			$query="SELECT * FROM admin order by id desc ".$limit."";
		}else if($limit==""&&$viewer=="admin"){
			$query="SELECT * FROM admin order by id desc LIMIT 0,15";		
		}/*elseif($limit!==""&&$viewer=="viewer"){
			$query="SELECT * FROM faq WHERE status='active' ".$limit."";
		}else if($limit==""&&$viewer=="viewer"){
			$query="SELECT * FROM faq WHERE status='active'";		
		}*/
		/*if($typeid=="active"){
			$query="SELECT * FROM faq WHERE status='active'";
		}*/
		$selection="";
		$run=mysql_query($query)or die(mysql_error()." Line 77");
		$numrows=mysql_num_rows($run);
		$top='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>Coverphoto</th><th>Fullname</th><th>AccessLevel</th><th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		$bottom='	</tbody>
				</table>';
		$adminoutput="No entries";
		$vieweroutput='No Entries Yet, Sorry, we are working on it';
		$monitorpoint="";
		if($numrows>0){
			$vieweroutput="";
			$adminoutput="";
		while($row=mysql_fetch_assoc($run)){
		$outvar=getSingleAdminUser($row['id']);
		$adminoutput.=$outvar['adminoutput'];
		// $vieweroutput.=str_replace("../", "$host_addr",$outvar['vieweroutput']);
		$selection.='<option value="'.$outvar['id'].'">'.$outvar['fullname'].' - '.$outvar['accesslevelout'].'</option>';

		}
		}
		$rowmonitor['chiefquery']="SELECT * FROM admin order by id desc";
		$outs=paginatejavascript($rowmonitor['chiefquery']);
		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
				<input type="hidden" name="outputtype" value="'.$outputtype.'"/>
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
		$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
		$row['adminoutputtwo']=$top.$adminoutput.$bottom;
		$row['vieweroutput']='
			<div class="accordion">'.$vieweroutput.'</div>	
		';
		$row['selection']=$selection;
		return $row;
	}
?>