<?php
	$cnrcount=0;
	function getSingleBranch($id){
		global $host_addr,$cnrcount;
		$query="SELECT * FROM branches WHERE id=$id ";
		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
		$row=mysql_fetch_assoc($run);
		$id=$row['id'];
		$location=$row['location'];
		$branchtype=$row['branchtype'];
		$branchtype==""?$branchtype="subbranch":$branchtype=$branchtype;
		$address=$row['address'];
		$lat=$row['lat'];
		$lng=$row['lng'];
		$contactname=$row['contactname'];
		$phonenumbers=$row['phonenumbers'];
		$email=$row['email'];
		$status=$row['status'];
	    // get subcontacts
	    $subcontactsquery="SELECT * FROM branchsubcontacts WHERE bid=$id";
		$subcontactsrun=mysql_query($subcontactsquery)or die(mysql_error()." ".__LINE__);
		$numrowssubcontacts=mysql_num_rows($subcontactsrun);
		$subcontactsadminout="";
		$subcontactsviewerout="";
		$subcontactsarr=array();
		$subcontactscount=2;
		if($numrowssubcontacts>0){
			while($subcontactsrow=mysql_fetch_assoc($subcontactsrun)){
				$subcontactsarr[]=$subcontactsrow;
				$subcontactsid=$subcontactsrow['id'];
				$scontactname=$subcontactsrow['contactname'];
				$sphonenumbers=$subcontactsrow['phonenumbers'];
				$semail=$subcontactsrow['email'];
				$sstatus=$subcontactsrow['status'];
				$subcontactsadminout.='
					<div class="col-md-3">
                      <div class="form-group">
                          <label>Contact Persons ('.$subcontactscount.')</label>
                          <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-bars"></i>
                              </div>
                              <input type="hidden" class="form-control" name="scontactsid'.$subcontactscount.'"  value="'.$subcontactsid.'"/>
                              <input type="text" class="form-control" name="contactpersons'.$subcontactscount.'" Placeholder="Contact Persons e.g Segun Ibrahim" value="'.$scontactname.'"/>
                          </div><!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                          <label>Phone Numbers</label>
                          <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-bars"></i>
                              </div>
                              <input type="text" class="form-control" name="phonenumbers'.$subcontactscount.'" Placeholder="Phone Numbers" value="'.$sphonenumbers.'"/>
                          </div><!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                          <label>Email</label>
                          <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-at"></i>
                              </div>
                              <input type="text" class="form-control" name="email'.$subcontactscount.'" Placeholder="Email Address" value="'.$semail.'"/>
                          </div><!-- /.input group -->
                        </div>
                    </div>
                    <div class="form-group">
	                    <label>Disable/Enable (<b>'.$sstatus.'</b>)</label>
	                    <div class="input-group">
	                        <div class="input-group-addon">
	                          <i class="fa fa-bars"></i>
	                        </div>
	                		<select name="subcontactstatus'.$subcontactscount.'" class="form-control">
	                        	<option value="">Change</option>
	                        	<option value="active">Active</option>
	                        	<option value="inactive">Inactive</option>
					  	    </select>
	                    </div><!-- /.input group -->
	                  </div>
				';
				$subcontactsviewerout.='
					<tr>
						<td>
							<span style="padding:5px;background:transparent; display:inline-block;" class=""><i class="fa fa-user minimarkers"></i></span>
						</td>
						<td>
							'.$scontactname.'<br>
						</td>
                    </tr>
					<tr>
						<td>
							<span style="padding:5px;background:transparent; display:inline-block;" class=""><i class="fa fa-telephone minimarkers"></i></span>
						</td>
						<td>
                    		<a href="tel:'.$sphonenumbers.'">'.$sphonenumbers.'<br /></a></span><br />
                    	<td>
					</tr>
					<tr>
						<td>
							<span style="padding:5px;background:transparent; display:inline-block;" class=""><i class="fa fa-at minimarkers"></i></span>
						</td>
						<td>
                    		<a href="mailto:'.$semail.'">'.$semail.'</a><br />
						</td>
					</tr>
				';
			}
		}
		$subcontactscount2=$subcontactscount;
		$animation="fadeInLeftBig";
		$animation=$cnrcount<4?"fadeInLeftBig":"fadeInRightBig";
		$curmod=$cnrcount%6;
		if($cnrcount%6!==0&&$cnrcount>6){
			$animation=$curmod<4?"fadeInLeftBig":"fadeInRightBig";
		}
		$row['adminoutput']='
			<tr data-id="'.$id.'">
				<td>'.$location.'</td><td>'.$contactname.'</td><td>'.$phonenumbers.'</td><td>'.$branchtype.'</td><td>'.$email.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsinglebranch" data-divid="'.$id.'">Edit</a></td>
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
		$btypetitle="Main Branch";
		$btypeoption='<option value="mainbranch">Main Branch</option>';
		// echo $branchtype;
		if($branchtype=="subbranch"){
			$btypetitle="Main Branch";
			$btypeoption='<option value="mainbranch">Main Branch</option>';
		}else if($branchtype=="mainbranch"){
			$btypetitle="Sub Branch";
			$btypeoption='<option value="subbranch">Sub Branch</option>';

		}
		$row['adminoutputtwo']='
			<form name="branchform" method="POST" enctype="multipart/form-data" action="../snippets/edit.php">
                <input type="hidden" name="entryvariant" value="editbranch"/>
                <input type="hidden" name="entryid" value="'.$id.'"/>
                  <div class="col-md-3">
                    <div class="form-group">
                        <label>Location Title</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-file-text"></i>
                            </div>
                            <input type="text" class="form-control" name="locationtitle" value="'.$location.'" Placeholder="e.g LEKKI OFFICE"/>
                        </div><!-- /.input group -->
                      </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <label>Set this as '.$btypetitle.'?</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-file-text"></i>
                            </div>
                            <select name="mainbranch" class="form-control">
                              <option value="">Choose Option</option>
                              '.$btypeoption.'
                            </select>
                        </div><!-- /.input group -->
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <label>Latitude <small>value with 6 decimal places required</small></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-file-text"></i>
                            </div>
                            <input type="text" class="form-control" name="latitude" value="'.$lat.'" Placeholder="e.g 50.897678"/>
                        </div><!-- /.input group -->
                      </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <label>Longitude <small>value with 6 decimal places required</small></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-file-text"></i>
                            </div>
                            <input type="text" class="form-control" name="longitude" value="'.$lng.'" Placeholder="e.g 50.897678"/>
                        </div><!-- /.input group -->
                    </div>
                  </div>
                  <div class="col-md-12">
                      <div class="form-group">
                          <label>Address</label>
                          <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-bars"></i>
                              </div>
                              <textarea class="form-control" id="postersmallthree" name="address" Placeholder="">'.$address.'</textarea>
                          </div><!-- /.input group -->
                        </div>
                  </div>
                  <div class="col-md-12">
	                  <div class="form-group">
	                    <label>Disable/Enable</label>
	                    <div class="input-group">
	                        <div class="input-group-addon">
	                          <i class="fa fa-bars"></i>
	                        </div>
	                		<select name="status" class="form-control">
	                        	<option value="">Choose</option>
	                        	<option value="active">Active</option>
	                        	<option value="inactive">Inactive</option>
					  	    </select>
	                    </div><!-- /.input group -->
	                  </div>
                  </div>
                  <div class="col-md-12" name="projectsentry">
		            <input type="hidden" name="curcontactcountedit" value="'.$subcontactscount.'"/>

                  	<div class="col-md-4">
                      <div class="form-group">
                          <label>Contact Persons (1)</label>
                          <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-bars"></i>
                              </div>
                              <input type="text" class="form-control" name="contactpersons1" value="'.$contactname.'" Placeholder="Contact Persons e.g Segun Ibrahim"/>
                          </div><!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                          <label>Phone Numbers</label>
                          <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-bars"></i>
                              </div>
                              <input type="text" class="form-control" name="phonenumbers1" Placeholder="Phone Numbers" value="'.$phonenumbers.'"/>
                          </div><!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                          <label>Email</label>
                          <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-at"></i>
                              </div>
                              <input type="text" class="form-control" name="email1" Placeholder="Email Address" value="'.$email.'"/>
                          </div><!-- /.input group -->
                        </div>
                    </div>
                    '.$subcontactsadminout.'
                    <div name="entrycontactpointedit"></div>
					<a href="##" name="addextracontactedit">Click to add another contact person</a>
                  </div>
                  <div class="col-md-12">
                    <div class="box-footer">
                        <input type="submit" class="btn btn-danger" name="newbranchsubmit" value="Update"/>
                    </div>
                  </div>
            </form>
            <script>
		      tinyMCE.init({
		            theme : "modern",
		            selector: "textarea#adminposter",
		            skin:"lightgray",
		            width:"94%",
		            height:"650px",
		            external_image_list_url : ""+host_addr+"snippets/mceexternalimages.php",
		            plugins : [
		             "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
		             "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
		             "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
		            ],
		            // toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
		            toolbar1: "undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
		            toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
		            image_advtab: true ,
		            editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
		            content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
		            external_filemanager_path:""+host_addr+"scripts/filemanager/",
		            filemanager_title:"Admin Blog Content Filemanager" ,
		            external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
		      });
		      tinyMCE.init({
		              theme : "modern",
		              selector:"textarea#postersmallthree",
		              menubar:false,
		              statusbar: false,
		              plugins : [
		               "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
		               "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
		               "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
		              ],
		              width:"100%",
		              height:"400px",
		              toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
		              toolbar2: "| responsivefilemanager | link unlink anchor | code",
		              image_advtab: true ,
		              content_css:""+host_addr+"stylesheets/mce.css",
		              external_filemanager_path:""+host_addr+"scripts/filemanager/",
		              filemanager_title:"Site Content Filemanager" ,
		              external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
		      });
		      tinyMCE.init({
		              theme : "modern",
		              selector:"textarea#postersmallfive",
		              menubar:false,
		              statusbar: false,
		              plugins : [
		               "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
		               "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
		               "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
		              ],
		              width:"80%",
		              height:"300px",
		              toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
		              toolbar2: "| responsivefilemanager | link unlink anchor | emoticons",
		              image_advtab: true ,
		              editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
		              content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
		            external_filemanager_path:""+host_addr+"scripts/filemanager/",
		              filemanager_title:"Content Filemanager" ,
		              external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
		      });   
		    </script>
		';
		$row['vieweroutput']='';
		$row['vieweroutputtwo']='';
		$row['subcontactscount']=$numrowssubcontacts;
		$row['subcontacts']=$subcontactsarr;

		return $row;
	}
	function getAllBranches($viewer,$type,$limit){
		global $host_addr,$cnrcount;
		$row=array();
		$outputtype="contactbranches";
		$testit=strpos($limit,"-");
		$testit!==false?$limit="":$limit=$limit;
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
		$row=array();
		$brancharr=array();
		$mainbrancharr=array();
		$type=$type=="mainbranch"?"WHERE branchtype='maintype'":"";
		$typetwo=$type=="mainbranch"?"AND branchtype='maintype'":"";
		if($limit!==""&&$viewer=="admin"){
			$query="SELECT * FROM branches $type order by id desc ".$limit."";
		}elseif($limit!==""&&$viewer=="viewer"){
			$query="SELECT * FROM branches WHERE status='active' $typetwo ".$limit."";
		}
		/*if($typeid=="active"){
			$query="SELECT * FROM faq WHERE status='active'";
		}*/
		$qdata=briefquery("SELECT * FROM branches WHERE branchtype='mainbranch'");
		if($qdata['numrows']>0){
			$mainbrancharr[0]=getSingleBranch($qdata['resultdata'][0]['id']);
			// echo "main found";
		}
		$selection="";
		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
		$numrows=mysql_num_rows($run);
		$top='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>Location</th><th>Main Contact</th><th>Phonenumbers</th><th>BranchType</th><th>Email</th><th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		$bottom='	</tbody>
				</table>';
		$adminoutput="<td>No Entries</td><td></td><td></td><td></td><td></td><td></td><td></td>";
		$vieweroutput='No Branch entries, Sorry, we are working on it';
		$monitorpoint="";
		$mainbranchoutmain='
		';
		$mainbranchout="";
		$countcontent=0;
		$cmc=0;
		
		if($numrows>0){
			$vieweroutput="";
			$adminoutput="";
			// $mainbranchout="";
			while($row=mysql_fetch_assoc($run)){
				$cnrcount++;
				$outvar=getSingleBranch($row['id']);
				if($row['branchtype']!=="mainbranch"){
					$brancharr[$cmc]=$outvar;
					$cmc++;
				}
				$adminoutput.=$outvar['adminoutput'];
				/*if($countcontent==0||($countcontent%4==0&&$countcontent<$numrows)){
					$countcontent==0?$vieweroutput.='<div class="row">':$vieweroutput.='</div><div class="row">';
					$next3=$countcontent+4;
				}*/
					$vieweroutput.=str_replace("../", "$host_addr",$outvar['vieweroutput']);
				/*if($countcontent==$next3||$countcontent==$numrows-1){
				  $vieweroutput.=' 
				  </div>';
				}*/
				/*if($row['branchtype']=="mainbranch"){
					$mainbranchout=$outvar['vieweroutputtwo'];
				}*/
				$countcontent++;
				$selection.='<option value="'.$outvar['id'].'">'.$outvar['location'].'</option>';

			}
		}
		$row['numrows']=$numrows;
		$brancharr['count']=$cmc;
		$mainbranchoutmain=$mainbranchout!==""?$mainbranchout:$mainbranchoutmain;
		$rowmonitor['chiefquery']="SELECT * FROM branches $type order by id desc";
		
		$outs=paginatejavascript($rowmonitor['chiefquery']);
		$outsviewer=paginate($rowmonitor['chiefquery']);
		if($viewer=="admin"){
			$row['num_pages']=$outs['num_pages'];
			$row['num_pages_rows']=$outs['numrows'];
		}else{
			$row['num_pages']=$outsviewer['num_pages'];
			$row['num_pages_rows']=$outsviewer['numrows'];
		}
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
		$row['paginatetop']='
			<div id="paginationhold">
				<div class="loadback"><div class="loadholder"><img src="'.$host_addr.'images/img/loading.gif" class="loadpointnot"></div></div>
				<div class="meneametwo">
					<input type="hidden" name="curquery" data-target="'.$outputtype.'.'.$viewer.'.'.$type.'" value="'.$rowmonitor['chiefquery'].'"/>
					<input type="hidden" name="outputtype" data-target="'.$outputtype.'.'.$viewer.'.'.$type.'" value="'.$outputtype.'.'.$viewer.'.'.$type.'"/>
					<input type="hidden" name="currentview" data-target="'.$outputtype.'.'.$viewer.'.'.$type.'" data-ipp="15" data-page="1" value="1"/>
					<div class="pagination" data-name="paginationpagesholdtwo" data-target="'.$outputtype.'.'.$viewer.'.'.$type.'">'.$outs['pageout'].'</div>
					<div class="pagination" data-target="'.$outputtype.'.'.$viewer.'.'.$type.'">
						  '.$outs['usercontrols'].'
					</div>
				</div>
			</div>
		';

		$row['paginatebottom']='
			<div id="paginationhold">
				<div class="loadback"><div class="loadholder"><img src="'.$host_addr.'images//loading.gif" class="loadpointnot"></div></div>
				<div class="meneametwo">
					<div class="pagination" data-name="paginationpagesholdtwo" data-target="'.$outputtype.'">'.$outs['pageout'].'</div>
				</div>
			</div>
		';
		$row['mainbranchdata']=$mainbrancharr;
		$row['branchdata']=$brancharr;
		$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
		$row['adminoutputtwo']=$top.$adminoutput.$bottom;
		$row['vieweroutput']='
			'.$vieweroutput.'	
		';
		$row['vieweroutputtwo']=$mainbranchoutmain;
		$row['selection']=$selection;
		return $row;
	}
?>