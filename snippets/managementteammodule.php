<?php
	$slideractive="";
	function getSingleManagementTeam($id){
		global $host_addr,$slideractive;
		$row=array();
		$row=getSingleMediaDataTwo($id);
		$id=$row['id'];
		$detailsdata=explode("[|><|]",$row['details']);
		$fullname=$detailsdata[0];
		$row['fullname']=$fullname;
		$position=$detailsdata[1];
		$row['position']=$position;
		$details=$detailsdata[2];
		$row['details']=$details;
		$qualifications=$detailsdata[3];
		$row['qualifications']=$qualifications;
		$coverphoto=$row['location'];
		$coverout=file_exists('.'.$coverphoto.'')?'<img src="'.$host_addr.''.$coverphoto.'"/>':"No image set";
		$coverpathout=$row['location']!==""?''.$host_addr.''.$coverphoto.'':"";
		$imgid=$row['id'];
		$row['coverout']=$coverout;
		
		$row['coverpath']=$coverpathout;
		$status=$row['status'];
		$row['adminoutput']='
			<tr data-id="'.$id.'">
				<td class="tdimg">'.$coverout.'</td><td>'.$fullname.'</td><td>'.$position.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsinglemanagementteam" data-divid="'.$id.'">Edit</a></td>
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
				<div class="row">
				    <form name="homebannerform" method="POST" enctype="multipart/form-data" action="../snippets/edit.php">
				  		<input type="hidden" name="entryvariant" value="editteammember"/>
				  		<input type="hidden" name="entryid" value="'.$id.'"/>
				  		<div class="col-md-12">
				          	<h4>Edit '.$fullname.'</h4>
				          	<div class="col-md-12">
				                  <div class="col-md-12">
				    				 <div class="form-group">
				                      <label>Member Image:</label>
				                      <div class="input-group">
				                        <div class="input-group-addon">
				                          <i class="fa fa-image"></i>
				                        </div>
				                        <input type="file" class="form-control" name="slide'.$id.'" Placeholder=""/>
				                      </div><!-- /.input group -->
				                    </div><!-- /.form group -->
				                    <div class="form-group">
				                      <label>Members Name</label>
				                      <div class="input-group">
				                        <div class="input-group-addon">
				                          <i class="fa fa-image"></i>
				                        </div>
				                        <input type="text" class="form-control" name="fullname'.$id.'" value="'.$fullname.'" Placeholder=""/>
				                      </div><!-- /.input group -->
				                    </div><!-- /.form group -->
				                    <div class="form-group">
				                      <label>Qualifications</label>
				                      <div class="input-group">
				                        <div class="input-group-addon">
				                          <i class="fa fa-image"></i>
				                        </div>
				                        <input type="text" class="form-control" name="qualifications'.$id.'" value="'.$qualifications.'" Placeholder=""/>
				                      </div><!-- /.input group -->
				                    </div><!-- /.form group -->
				                    <div class="form-group">
				                      <label>Position</label>
				                      <div class="input-group">
				                        <div class="input-group-addon">
				                          <i class="fa fa-file-text"></i>
				                        </div>
				                        <input type="text" class="form-control" name="position'.$id.'" value="'.$position.'" Placeholder=""/>
				                      </div><!-- /.input group -->
				                    </div><!-- /.form group -->
				                    <div class="form-group">
				                      <label>Member Details</label>
				                      <div class="input-group">
				                        <div class="input-group-addon">
				                          <i class="fa fa-file-text"></i>
				                        </div>
				                        <textarea rows="4"  class="form-control" id="postersmallfive" name="details'.$id.'"  Placeholder="">'.$details.'</textarea>
				                      </div><!-- /.input group -->
				                    </div><!-- /.form group -->
				                    <div class="form-group">
				                      <label>Delete This?:</label>
				                      <div class="input-group">
				                        <div class="input-group-addon">
				                          <i class="fa fa-trash"></i>
				                        </div>
				                        <select name="status'.$id.'" class="form-control">
											<option value="">Delete?</option>
											<option value="inactive">Yes</option>
										</select>
				                      </div><!-- /.input group -->
				                    </div><!-- /.form group -->
				                    
				                  </div>
				              </div>
				          </div>
				          <div class="col-md-12">
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
	                                        filemanager_title:"Adsbounty Admin Blog Content Filemanager" ,
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
	                                        filemanager_title:"NYSC Admin Blog Content Filemanager" ,
	                                        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
	                                  });   
	                          </script>
				          </div>
				          <div class="col-md-12">
				  		        <div class="box-footer">
				                  <input type="submit" class="btn btn-danger" name="homebannersubmit" value="Update"/>
				              </div>
				      	</div>
					  </form>
				</div>
		';
		
		$row['tabout']='

			';
		
		$row['vieweroutput']='
			<div class="col-md-3 col-sm-6">
                <div class="managementImg">
                    <div class="managementCaption">
                        <h4>'.$fullname.'</h4>
                        <p>'.$position.'</p>
                        <p>
                            <a href="team.php?page='.$id.'" class="label label-default" rel="tooltip" title="View More">More</a>
                        </p>
                    </div>
                    <img class="img-responsive" src="'.$coverpathout.'" />
                </div>
            </div>		
		';
		$row['vieweroutputtwo']='

		';
		
		
		return $row;
	}
	function getAllManagementTeam($viewer,$type,$limit){
		global $host_addr;
		$row=array();
		$outputtype="managementteam";
		$testit=strpos($limit,"-");
		$testit!==false?$limit="":$limit=$limit;
		$frameout="WHERE id=0";
		$mainmsgout=''.strtoupper($type).' Page Manager';
		$mainmsgintroout='Edit '.strtoupper($type).' Page Intro/Create New Content';
		$mainmsgcontentout='Edit '.strtoupper($type).' Page Contents';
		$showhidetitle="";
		$showhideimage="";
		$count=0;
		$row=array();
		$rowmonitor['chiefquery']="SELECT * FROM media WHERE ownertype='teammember' AND status='active' order by id desc";
		if($limit!==""&&$viewer=="admin"){
			$query="SELECT * FROM media WHERE ownertype='teammember' AND status='active' order by id desc ".$limit."";
		}else if($limit==""&&$viewer=="admin"){
			$query="SELECT * FROM media WHERE ownertype='teammember' AND status='active' order by id desc LIMIT 0,15";		
		}elseif($limit!==""&&$viewer=="viewer"){
			$query="SELECT * FROM media WHERE ownertype='teammember' AND status='active' order by id desc ".$limit."";
			$rowmonitor['chiefquery']="SELECT * FROM media WHERE ownertype='teammember' AND status='active' order by id desc";
		}else if($limit==""&&$viewer=="viewer"){
			$query="SELECT * FROM media WHERE ownertype='teammember' AND status='active' order by id desc LIMIT 0,15";		
			$rowmonitor['chiefquery']="SELECT * FROM media WHERE ownertype='teammember' AND status='active' order by id desc";
		}
		/*if($typeid=="active"){
			$query="SELECT * FROM faq WHERE status='active'";
		}*/
		$selection="";
		$run=mysql_query($query)or die(mysql_error()." Line 77");
		$numrows=mysql_num_rows($run);
		$top='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>CoverImage</th><th>Fullname</th><th>Position</th><th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		$bottom='	</tbody>
				</table>';
		$adminoutput="<td>No entries</td><td></td><td></td><td></td><td></td>";
		$vieweroutput='';
		$selection="";
		$carouselindicators="";
		if($numrows>0){
			$vieweroutput="";
			$adminoutput="";
			while($row=mysql_fetch_assoc($run)){
				// end
				
				$outvar=getSingleManagementTeam($row['id']);
				$adminoutput.=$outvar['adminoutput'];
				$vieweroutput.=str_replace("../", "$host_addr",$outvar['vieweroutput']);
				$selection.='<option value="'.$outvar['id'].'">'.$outvar['title'].'</option>';
				
				$count++;
			}

		}
		
		$defbanner='
			No entries Yet.
		';

		$row['numrows']=$numrows;
		$adminoutput=$adminoutput==""?"<td>No Content entries for this</td><td></td><td></td><td></td><td></td>":$adminoutput;
		
		$outs=paginatejavascript($rowmonitor['chiefquery']);
		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
				<input type="hidden" name="outputtype" value="managementteam"/>
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
				<div class="loadback"><div class="loadholder"><img src="'.$host_addr.'images/loading.gif" class="loadpointnot"></div></div>
				<div class="meneametwo">
					<input type="hidden" name="curquery" data-target="managementteamtwo" value="'.$rowmonitor['chiefquery'].'"/>
					<input type="hidden" name="outputtype" data-target="managementteamtwo" value="managementteamtwo"/>
					<input type="hidden" name="currentview" data-target="managementteamtwo" data-ipp="15" data-page="1" value="1"/>
					<div class="pagination" data-name="paginationpagesholdtwo" data-target="managementteamtwo">'.$outs['pageout'].'</div>
					<div class="pagination" data-target="managementteamtwo">
						  '.$outs['usercontrols'].'
					</div>
				</div>
			</div>
		';

		$row['paginatebottom']='
			<div id="paginationhold">
				<div class="loadback"><div class="loadholder"><img src="'.$host_addr.'images/loading.gif" class="loadpointnot"></div></div>
				<div class="meneametwo">
					<div class="pagination" data-name="paginationpagesholdtwo" data-target="managementteamtwo">'.$outs['pageout'].'</div>
				</div>
			</div>
		';
		$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
		$row['adminoutputtwo']=$top.$adminoutput.$bottom;
		if($vieweroutput==""){
			$row['vieweroutput']=$defbanner;
		}else{
			$row['vieweroutput']=$vieweroutput;
			
		}

		$row['selection']=$selection;


		return $row;
	}
?>