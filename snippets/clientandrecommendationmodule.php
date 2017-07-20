<?php
	function getSingleClientRecommendation($id){
		include("globalsmodule.php");
		$row=array();
		$query="SELECT * FROM clientnrec where id=$id";
		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
		$numrows=mysql_num_rows($run);
		$row=mysql_fetch_assoc($run);
		$id=$row['id'];
		$type=$row['type'];
		$ownertype=$row['ownertype'];
		$ownerid=$row['ownerid'];
		$fullname=$row['fullname'];
		$pwebsite=$pwebsiteout=$row['personalwebsite'];
		$companyname=$row['companyname'];
		$cwebsite=$cwebsiteout=$row['officialwebsite'];
		$position=$row['position'];
		$details=$row['content'];
		$status=$row['status'];
		$nexttypewebone=false;
		$nexttypewebtwo=false;
		$nexttypewebthree=false;
		$nexttypewebfour=false;
		if($pwebsite!==""){
			$nexttypewebone=strpos($pwebsite,"http://");
			$nexttypewebthree=strpos($pwebsite,"https://");
		}
		if($cwebsite!==""){
			$nexttypewebtwo=strpos($cwebsite,"http://");
			$nexttypewebfour=strpos($cwebsite,"https://");
		}
		if($nexttypewebone===false||$nexttypewebthree<true){
			$pwebsitetwo="http://".$pwebsite;
		}
		if($nexttypewebtwo!==true&&$nexttypewebfour!==true){
			$cwebsiteout="http://".$cwebsite;
		}
		if ($pwebsite=="") {
			# code...
			$pwebsiteout="##";
		}
		if ($cwebsite=="") {
			# code...
			$cwebsiteout="##";
		}
		$row['personalsite']=$pwebsiteout;
		$row['companysite']=$cwebsiteout;
		// check for image for the current entry
		// check for cover photo
		$mediaquery="SELECT * FROM media WHERE ownertype='$type' AND ownerid='$id' AND mediatype='image' AND status='active' ORDER BY id DESC";
		$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 2846");
		$coverdata=mysql_fetch_assoc($mediarun);
		$coverphoto=$coverdata['details']!==""?$coverdata['details']:$coverdata['location'];
		$row['coverid']=$coverdata['id'];
		// echo $mediaquery;	
		$medianumrows=mysql_num_rows($mediarun);
		$coverout=file_exists('.'.$coverphoto.'')?'<img src="'.$host_addr.''.$coverphoto.'"/>':"No image set";
		$pleatoout='<div class="propdataimg2 pull-left"><img src="'.$coverphoto.'" height="100%"/> </div>';
		$imgid=$coverdata['id'];
		$row['coverout']=$coverout;
		$coverpathout=$coverdata['location']!==""?''.$host_addr.''.$coverdata['location'].'':"";
		$coverphoto=$host_addr.$coverdata['thumbnail'];
		if($medianumrows<1){
			$coverphoto=$host_addr."images/default.gif";
			$coverout="No Image Set";
			$coverpathout=$coverphoto;
			$imgid=0;
			$pleatoout="";
			$row['coverid']=$imgid;
		}else{
			// $coverout='<img src="'.$host_addr.''.$coverphoto.'"/>';
		}
		$row['coverphotomain']=$coverpathout;
		$row['coverphotothumb']=$coverphoto;

		$contenttypeout='<td class="tdimg">'.$coverout.'</td><td><a href="'.$cwebsiteout.'" target="_blank">'.$companyname.'</a></td><td><a href="'.$pwebsiteout.'" target="_blank">'.$fullname.'</a></td><td>'.$position.'</td>';
		$fullnamedisplay="";
		$contentpicdisplay="";
		$pwebsitedisplay="";
		$companynamedisplay="";
		$cwebsitedisplay="";
		$positiondisplay="";
		$contentdisplay="";
		$formelemcountertitle="currecommendationslidecount";
		$formelemcountertrigger="addextrarecommendationslide";
		$formelemsubmittrigger="recommendationsubmit";
		$formelemcountermarker="entryrecommendationslidepoint";
		if($type=="clientelle"){
			$contenttypeout='<td class="tdimg">'.$coverout.'</td><td><a href="'.$cwebsiteout.'" target="_blank">'.$companyname.'</a></td>';
			$fullnamedisplay="display:none;";
		    $contentpicdisplay="";
		    $pwebsitedisplay="display:none;";
		    $companynamedisplay="";
		    $cwebsitedisplay="";
		    $positiondisplay="display:none;";
		    $contentdisplay="";
		    $formelemcountertitle="curclientellelslidecount";
		    $formelemcountertrigger="addextraclientelleslide";
		    $formelemcountermarker="entryclientelleslidepoint";
		    $formelemsubmittrigger="clientsubmit";
		}elseif ($type=="testimonial") {
			# code...
			$contenttypeout='<td class="tdimg">'.$coverout.'</td><td><a href="'.$cwebsiteout.'" target="_blank">'.$companyname.'</a></td><td><a href="'.$pwebsiteout.'" target="_blank">'.$fullname.'</a></td><td>'.$position.'</td>';
			$fullnamedisplay="";
		    $contentpicdisplay="";
		    $pwebsitedisplay="";
		    $companynamedisplay="";
		    $cwebsitedisplay="";
		    $positiondisplay="";
		    $contentdisplay="";
		    $formelemcountertitle="curtestimonialslidecount";
		    $formelemcountertrigger="addextratestimonialslide";
		    $formelemcountermarker="entrytestimonialslidepoint";
		    $formelemsubmittrigger="testimonialsubmit";
		}
		$row['adminoutput']='
			<tr data-id="'.$id.'">
				'.$contenttypeout.'<td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsinglecnrnt" data-divid="'.$id.'">Edit</a></td>
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
			<form action="../snippets/edit.php" name="editclientnrec" enctype="multipart/form-data" method="post">
				<input type="hidden" name="entryvariant" value="editclientnrec"/>
				<input type="hidden" name="type" value="'.$type.'"/>
				<input type="hidden" name="entryid" value="'.$id.'"/>
				<div id="formheader">Edit '.strtoupper($type).'</div>
					<div class="col-md-12">
                      <div class="col-md-12">
                        <div class="form-group" style="'.$contentpicdisplay.'">
                          <label>Select Image</label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-file-text"></i>
                            </div>
							<input type="hidden" name="imgid" value="'.$imgid.'"/>
                            <input type="file" class="form-control" name="slide" Placeholder=""/>
                          </div><!-- /.input group -->
                        </div><!-- /.form group -->
                        <div class="form-group" style="'.$fullnamedisplay.'">
                          <label>Fullname:</label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-file-text"></i>
                            </div>
                            <input type="text" class="form-control" name="fullname" Placeholder="Fullname" value="'.$fullname.'"/>
                          </div><!-- /.input group -->
                        </div><!-- /.form group -->
                        <div class="form-group" style="'.$companynamedisplay.'">
                          <label>Company Name:</label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-file-text"></i>
                            </div>
                            <input type="text" class="form-control" name="companyname" Placeholder="Company Name" value="'.$companyname.'"/>
                          </div><!-- /.input group -->
                        </div><!-- /.form group -->
                        <div class="form-group" style="'.$cwebsitedisplay.'">
                          <label>Company Website:</label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-file-text"></i>
                            </div>
                            <input type="text" class="form-control" name="companywebsite" Placeholder="Company website format:(thewebsitename.com)" value="'.$cwebsite.'"/>
                          </div><!-- /.input group -->
                        </div><!-- /.form group -->
                        <div class="form-group" style="'.$positiondisplay.'">
                          <label>Position:</label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-file-text"></i>
                            </div>
                            <input type="text" class="form-control" name="position" Placeholder="Position at Company" value="'.$position.'"/>
                          </div><!-- /.input group -->
                        </div><!-- /.form group -->
                        <div class="form-group" style="'.$pwebsitedisplay.'">
                          <label>Personal Website:</label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-file-text"></i>
                            </div>
                            <input type="text" class="form-control" name="personalwebsite" Placeholder="Personal website format:(thewebsitename.com)" value="'.$pwebsite.'"/>
                          </div><!-- /.input group -->
                        </div><!-- /.form group -->
                        <div class="form-group" style="'.$contentdisplay.'">
                          <label>Details:</label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-file-text"></i>
                            </div>
                            <textarea rows="4" class="form-control" name="details" id="postersmallfifteen" Placeholder="Place the details for this entry here">'.$details.'</textarea>
                          </div><!-- /.input group -->
                        </div><!-- /.form group -->
                      </div>
                </div>
					<div id="formend">
						Change Status<br>
						<select name="status" class="curved2">
							<option value="">Change Status</option>
							<option value="active">Active</option>
							<option value="inactive">Inactive</option>
						</select>
					</div>
				<div id="formend">
					<input type="submit" name="Update" value="Submit" class="submitbutton"/>
				</div>
			</form>
			<script>
                  tinymce.init({
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
                        filemanager_title:"Max-Migold Admin Blog Content Filemanager" ,
                        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
                  });
                  tinymce.init({
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
                  tinymce.init({
                          theme : "modern",
                          selector:"textarea#postersmallfifteen",
                          menubar:false,
                          statusbar: false,
                          plugins : [
                           "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                           "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                           "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
                          ],
                          width:"100%",
                          height:"300px",
                          toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                          toolbar2: "| responsivefilemanager | link unlink anchor | emoticons",
                          image_advtab: true ,
                          editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
                          content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
                      external_filemanager_path:""+host_addr+"scripts/filemanager/",
                        filemanager_title:"Max-Migold Content Filemanager" ,
                        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
                  });   
            </script>
		';
		$printout=''.$position.' ('.$companyname.')';
		if($position==""){
			$printout=$companyname;
		}
		if($companyname==""){
			$printout="";
		}
		$row['vieweroutput']='
			<li>
				<div class="testimonial-slide-item-holder">
            		<div class="col-sm-8 testimonial-slide">
            			<div class="testimonial-text">
            				<div class="testimony">
								'.$details.'
            				</div>
            			</div>
            		</div>
            		<div class="col-sm-4 testimonial-slide-user-img">
            			<img src="'.$coverpathout.'"/>
            			<h3><a href="'.$cwebsiteout.'" class="url">'.$printout.'</a></h3>
            			<p class="name">'.$fullname.'</p>
            		</div>
            	</div>
			</li>
		';
		$animation="fadeInLeftBig";
		$animation=$cnrcount<4?"fadeInLeftBig":"fadeInRightBig";
		$curmod=$cnrcount%6;
		if($cnrcount%6!==0&&$cnrcount>6){
			$animation=$curmod<4?"fadeInLeftBig":"fadeInRightBig";
		}
		$row['vieweroutputtwo']='
			<li>
	            <div class="testimonial-mini-hold">
	            	<div class="testimonial-mini">
	            		<div class="testimony">
	            			<blockquote>
	            				<q>
	                            <i class="fa fa-quote-left"></i>
	                            '.$details.'
	            			</blockquote>
	            		</div>
	            		<div class="testimoner">
	            			<div class="user-detail">
	                            <a href="'.$cwebsiteout.'" class="user">
	                				<img src="'.$coverpathout.'"/>
	                            </a>
	                            <strong class="name">'.$fullname.'</strong>
	                            <a href="#" class="web">'.$printout.'</a>
	                        </div>
	            		</div>
	            	</div>
	            </div>
	    	</li>
		';

		return $row;
	}
	function getAllClientRecommendations($viewer,$type,$limit){
		include("globalsmodule.php");
		$row=array();
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
		$typeout="AND type='$type'";
		$typeout2="WHERE type='$type'";

		$adminoutput="No entries";
		$vieweroutput='<li>No Entries Yet.</li>';
		$vieweroutputtwo=$vieweroutput;
		if($viewer=="admin"){
			$query="SELECT * FROM clientnrec $typeout2 order by id desc ".$limit."";
		}elseif($viewer=="viewer"){
			$query="SELECT * FROM clientnrec WHERE status='active' $typeout ".$limit."";
		}
		$curoutarr=array();
		if(is_array($viewer)){
			$type=$viewer[0];
			if($type=="search"){
				$searchterm=mysql_real_escape_string($viewer[1]);
				$viewer="viewer";
				$searchdata=explode(" ",$searchterm);
				$searchdataout="";
				for($i=0;$i<count($searchdata);$i++){
					$searchdataout==""?$searchdataout.='(title LIKE \'%'.$searchdata[$i].'%\' OR content LIKE \'%'.$searchdata[$i].'%\') ':$searchdataout.='OR (title LIKE \'%'.$searchdata[$i].'%\' OR content LIKE \'%'.$searchdata[$i].'%\') ';
				}
				$query="SELECT * FROM clientnrec WHERE $searchdataout AND status='active'";		
				$rowmonitor['chiefquery']="SELECT * FROM clientnrec WHERE $searchdataout AND status='active'";
				$vieweroutput='No entries matched <b>"'.$searchterm.'"</b>';
				$vieweroutputtwo=$vieweroutput;
			}
		}else{
			$rowmonitor['chiefquery']="SELECT * FROM clientnrec order by id desc";

		}
		
		$selection="";
		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
		$cnrout='<thead><tr><th>CoverImage</th><th>CompanyName</th><th>Fullname</th><th>Position</th><th>Status</th><th>View/Edit</th></tr></thead>';
		if($type=="clientelle"){
			$cnrout='<thead><tr><th>CoverImage</th><th>CompanyName</th><th>Status</th><th>View/Edit</th></tr></thead>';
		}elseif ($type=="testimonial") {
			# code...
			$cnrout='<thead><tr><th>CoverImage</th><th>CompanyName</th><th>Fullname</th><th>Position</th><th>Status</th><th>View/Edit</th></tr></thead>';
		}

		$numrows=mysql_num_rows($run);
		$top='<table id="resultcontenttable" cellspacing="0">
					'.$cnrout.'
					<tbody>';
		$bottom='	</tbody>
				</table>';

		$monitorpoint="";
		$counter=0;
		if($numrows>0){
			$vieweroutput="";
			$vieweroutputtwo="";
			$adminoutput="";
			while($row=mysql_fetch_assoc($run)){
				$cnrcount++;
				$outvar=getSingleClientRecommendation($row['id']);
				$curoutarr[$counter]=$outvar;
				$counter++;
				$counter>0?$curactive="active":$curactive="";
				$adminoutput.=$outvar['adminoutput'];
				$vieweroutput.=str_replace("../", "$host_addr",$outvar['vieweroutput']);
				$vieweroutputtwo.=str_replace("../", "$host_addr",$outvar['vieweroutputtwo']);
				$selection.='<option value="'.$outvar['id'].'">'.$outvar['companyname'].'  '.$outvar['fullname'].'</option>';
			}
		}
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
				<input type="hidden" name="outputtype" value="'.$type.'"/>
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
			<div class="ABt_testimonials_wrapper picture_middle">
				<ul class="ABt_testimonials_slide" data-play="1" data-fx="crossfade" data-easing="swing" data-direction="left" data-duration="1000" data-pauseonhover="immediate" data-timeoutduration="5000">
					'.$vieweroutput.'
				</ul>
				<div class="ABt_pagination">
				</div>
			</div>
		';
		$row['resultdataset']=$curoutarr;
		$row['vieweroutputtwo']=''.$vieweroutputtwo.'';
		$row['selection']=$selection;
		$row['numrows']=$numrows;
		return $row;
	}
?>