<?php
	function getSingleUnit($id){
		global $host_addr;
		$row=array();
		$query="SELECT * FROM generalinfo where id=$id";
		$run=mysql_query($query)or die(mysql_error()." Line 7");
		$numrows=mysql_num_rows($run);
		$row=mysql_fetch_assoc($run);
		global $host_addr;
		$row=array();
		$query="SELECT * FROM generalinfo where id=$id";
		$run=mysql_query($query)or die(mysql_error()." Line 7");
		$numrows=mysql_num_rows($run);
		$row=mysql_fetch_assoc($run);
		$id=$row['id'];
		$maintype=$row['maintype'];
		$subtype=$row['subtype'];
		$title=$row['title'];
		$intro=$row['intro'];
		$content=str_replace("../../", "$host_addr",$row['content']);
		$coverphoto="";
		// check for cover photo
		$mediaquery="SELECT * FROM media WHERE ownertype='$maintype' AND ownerid='$id' AND maintype='coverphoto' AND status='active' ORDER BY id DESC";
		$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 2846");
		$coverdata=mysql_fetch_assoc($mediarun);
		$coverphoto=$coverdata['details'];
		$row['coverid']=$coverdata['id'];
		// echo $mediaquery;	
		$medianumrows=mysql_num_rows($mediarun);

		$coverout=file_exists('.'.$coverphoto.'')?'<img src="'.$host_addr.''.$coverphoto.'"/>':"No image set";
		$pleatoout='<div class="propdataimg2 pull-left"><img src="'.$host_addr.''.$coverphoto.'" height="100%"/> </div>';
		$imgid=$coverdata['id'];
		$row['coverout']=$coverout;
		$coverpathout=$coverdata['location']!==""?''.$host_addr.''.$coverphoto.'':"";
		if($medianumrows<1){
			$coverphoto=$host_addr."images/default.gif";
			$coverout="No Image Set";
			$coverpathout=$coverout;
			$imgid=0;
			$pleatoout="";
			$row['coverid']=$imgid;
		}
		$row['coverpath']=$coverpathout;
		$date=$row['entrydate'];
		$status=$row['status'];
		$row['adminoutput']='
			<tr data-id="'.$id.'">
				<td class="tdimg">'.$coverout.'</td><td>'.$title.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsingleunit" data-divid="'.$id.'">Edit</a></td>
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
            		<input type="hidden" name="entryvariant" value="contententry"/>
            		<input type="hidden" name="maintype" value="'.$maintype.'"/>
            		<input type="hidden" name="subtype" value="'.$subtype.'"/>
            		<input type="hidden" name="entryid" value="'.$id.'"/>
            		<input type="hidden" name="coverid" value="'.$imgid.'"/>
					<div class="col-md-12">
                    	<h4>Content Entry</h4>
                    	<div class="col-md-6">
                    		<div class="form-group">
			                  <label>Unit Title</label>
			                  <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-bars"></i>
			                      </div>
			                      <input type="text" class="form-control" name="contenttitle" value="'.str_replace('"', "'",$title).'" Placeholder=""/>
			                   </div><!-- /.input group -->
			                </div>
			            </div>
			            <div class="col-md-6">
                    		<div class="form-group">
			                  <label>Cover Photo</label>
			                  <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-image"></i>
			                      </div>
			                      <input type="file" class="form-control" name="contentpic" Placeholder=""/>
			                   </div><!-- /.input group -->
			                </div>
			            </div>
                    	<div class="col-md-12">
                    		<div class="form-group">
			                  <label>Unit Details</label>
			                  <textarea class="form-control" rows="3" name="contentpost" id="postersmallfour" placeholder="Provide information concerning this">'.$content.'</textarea>
			                </div>
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
							        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
							        toolbar2: "| link unlink anchor | emoticons",
							        image_advtab: true ,
							        content_css:""+host_addr+"stylesheets/mce.css",
							        external_filemanager_path:""+host_addr+"scripts/filemanager/",
							        filemanager_title:"Site Content Filemanager" ,
							        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
							});
							tinyMCE.init({
							        theme : "modern",
							        selector:"textarea#postersmallfour",
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
							        toolbar2: "| responsivefilemanager | link unlink anchor | emoticons | code",
							        image_advtab: true ,
							        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
							        content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
									external_filemanager_path:""+host_addr+"scripts/filemanager/",
								   	filemanager_title:"NYSC Admin Blog Content Filemanager" ,
								   	external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
							});   
					</script>
					<div class="col-md-12">
	        			<div class="box-footer">
		                    <input type="submit" class="btn btn-danger" name="submitcontent" value="Create/Update"/>
		                </div>
	            	</div>
	            </form>
	        </div>
		';
		$row['vieweroutput']='
			<div class="trenddatahold">
				<div class="branchunit" data-id="'.$id.'" data-name="branchunit">	
	        		'.$content.'
				</div>
				<a href="##'.$title.'" data-name="branchunit" data-id="'.$id.'">
					<div class="propdataimg2"><img src="'.$coverpathout.'" height="100%"/> </div>
					<span style="">'.$title.'</span><br>
				</a>
			</div>
		';
		return $row;
	}
	function getAllUnits($viewer,$type,$limit){
		global $host_addr;
		$row=array();
		$outputtype="units";
		$testit=strpos($limit,"-");
		$testit!==false?$limit="":$limit=$limit;
		$row=array();
		if($limit!==""&&$viewer=="admin"){
			$query="SELECT * FROM generalinfo WHERE maintype='units' AND subtype='content' order by title desc ".$limit."";
		}else if($limit==""&&$viewer=="admin"){
			$query="SELECT * FROM generalinfo WHERE maintype='units' AND subtype='content' order by title desc LIMIT 0,15";		
		}elseif($limit!==""&&$viewer=="viewer"){
			$query="SELECT * FROM generalinfo WHERE maintype='units' AND subtype='content' AND status='active' order by title desc ".$limit."";
		}else if($limit==""&&$viewer=="viewer"){
			$query="SELECT * FROM generalinfo WHERE maintype='units' AND subtype='content' AND status='active' order by title desc";		
		}
		/*if($typeid=="active"){
			$query="SELECT * FROM faq WHERE status='active'";
		}*/
		$selection="";
		$run=mysql_query($query)or die(mysql_error()." Line 77");
		$numrows=mysql_num_rows($run);
		$top='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>Cover Image</th><th>Unit Title</th><th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		$bottom='	</tbody>
				</table>';
		$adminoutput="No entries";
		$vieweroutput='<br>No Entries Yet, Sorry, we are working on it';
		$monitorpoint="";
		if($numrows>0){
			$vieweroutput="";
			$adminoutput="";
		while($row=mysql_fetch_assoc($run)){
		$outvar=getSingleUnit($row['id']);
		$adminoutput.=$outvar['adminoutput'];
		$vieweroutput.=str_replace("../", "$host_addr",$outvar['vieweroutput']);
		$selection.='<option value="'.$outvar['id'].'">'.$outvar['title'].'</option>';

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
		$row['vieweroutput']=''.$vieweroutput.'';
		$row['selection']=$selection;
		return $row;
	}

?>