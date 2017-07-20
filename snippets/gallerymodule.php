<?php
function getSingleGalleryStream($mediaid,$ccount=0){

}
function getGalleryStream($viewer,$limit,$type,$data=array()){
	include('globalsmodule.php');
	$row=array();
	$outputtype="$type"."_gallerystream";
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
	$subquery="";
	if($type!==""){

	}
	if($limit!==""&&$viewer=="admin"){
		$query="SELECT * FROM media WHERE ownertype='$type' ORDER BY id DESC $limit";
		$rowmonitor['chiefquery']="SELECT * FROM media WHERE ownertype='$type' ORDER BY id DESC";
	}elseif($viewer=="viewer"){
		$query="SELECT * FROM media WHERE ownertype='$type' AND status='active' $subquery ORDER BY id DESC $limit";
		$rowmonitor['chiefquery']="SELECT * FROM media WHERE ownertype='$type' AND status='active' $subquery";	
	}
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
	$numrows=mysql_num_rows($run);
	$adminoutput="<td colspan=\"100%\">No entries</td>";
	$adminoutputtwo="";
	$vieweroutput='<font color="#fefefe">Sorry, No Content has been created Yet</font>';
	$vieweroutputtwo='<font color="#fefefe">Sorry, No Content has been created Yet</font>';
	$returndataset=array();
	// represents the current set of data entries
	$ccount=0;
	$cfdata="";
	$cedata="";
	if($numrows>0){
		$adminoutput="";
		$adminoutputtwo="";
		$vieweroutput="";
		$cfdata="";
		$cedata="";
		while($row=mysql_fetch_assoc($run)){
			$outs=getSingleMediaDataTwo($row['id']);
			$returndataset[$ccount]=$outs;
			$ccount++;
			$endcat="<|>";
			if($ccount==$numrows){
				$endcat="";
			}
			$coutput='
				<div class="col-md-3 multi_content_hold">
            		<div class="col-sm-12">
            			<div class="form-group">
            				<div class="img_prev_hold _'.$ccount.'">
            					<a href="'.$host_addr.''.$outs['location'].'" data-lightbox="streamgallery" data-src="'.$host_addr.''.$outs['location'].'">
            						<img src="'.$host_addr.''.$outs['thumbnail'].'"/>
            					</a>
            				</div>
                            <label>Image</label>
                            <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-file-text"></i>
                              </div>
                              <input type="hidden" class="form-control" name="galimage'.$ccount.'_id" value="'.$outs['id'].'"/>
                              <input type="file" class="form-control" name="galimage'.$ccount.'" data-form-edit="true" Placeholder="Choose file"/>
                            </div><!-- /.input group -->
                        </div><!-- /.form group -->
            		</div>
            		<div class="col-sm-12">
            			<div class="form-group">
                            <label>Caption</label>
                            <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-file-text"></i>
                              </div>
                              <input type="text" class="form-control" name="caption'.$ccount.'" value="'.$outs['title'].'" Placeholder="Image Caption"/>
                            </div><!-- /.input group -->
                        </div><!-- /.form group -->
            		</div>
            		<div class="col-sm-12">
            			<div class="form-group">
                            <label>Details</label>
                            <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-file-text"></i>
                              </div>
                              <textarea class="form-control" rows="3" name="details'.$ccount.'" Placeholder="Give details if any">'.$outs['details'].'</textarea>
                            </div><!-- /.input group -->
                        </div><!-- /.form group -->
            		</div>
            		<div class="col-sm-12"> 
		              <div class="form-group">
		                <label>Remove Entry '.$ccount.':</label>
		                <div class="input-group">
		                  <div class="input-group-addon">
		                    <i class="fa fa-file-text"></i>
		                  </div>
		                  <select class="form-control" name="entry_status'.$ccount.'">
		                    <option value="">Delete this?</option> 
		                    <option value="yes">Yes</option> 
		                  </select>

		                </div><!-- /.input group -->
		              </div><!-- /.form group -->
		            </div>
            	</div>
			';
			$cfdata.="galimage$ccount-:-input-:-[single-|-caption$ccount-|-input-|-*any*]<|>
				caption$ccount-:-input-:-[single-|-galimage$ccount-|-input-|-*any*]<|>
				details$ccount-:-textarea$endcat";
			$cedata.="galimage$ccount-:-Please provide an image<|>
  				caption$ccount-:-The caption for the image is required<|>details$ccount-:-NA$endcat";
			$adminoutput.=$coutput;
			// $vieweroutput.=$outs['vieweroutput'];
		}
	}
	$adminoutput='
		<form name="editgallerystream" method="POST" onSubmit="return false" enctype="multipart/form-data" action="../snippets/edit.php">
			<input type="hidden" name="entryvariant" value="editgallerystream"/>
			<input type="hidden" name="type" value="'.$type.'"/>
			<input type="hidden" name="entryid" value="0"/>
			<input name="galleryslidescount" type="hidden" value="'.$ccount.'"/>
			 <div class="col-md-12 clearboth">
                <div class="box-footer text-center">
                    <input type="button" class="btn btn-danger marginauto" name="editgallerystream" data-formdata="editgallerystream" value="Update"/>
                </div>
            </div>
			<div class="col-md-12 dogalleryslides multi_content_hold_generic">
				<a href="##delete_all" class="delete_all" 
					data-name="deleteset" 
					data-type="select" 
					data-type-name="entry_status" 
					data-type-value="yes" 
					data-del-state="" 
					data-state=""><i class="fa fa-trash"></i> Delete Batch</a>
				'.$adminoutput.'
            </div> 		
            <!-- form control section -->
            <input type="hidden" name="extraformdata" value="'.$cfdata.'"/>
              <!--  -->
            <input type="hidden" name="errormap" value="'.$cedata.'"/>				              
            <div class="col-md-12 clearboth">
                <div class="box-footer text-center">
                    <input type="button" class="btn btn-danger marginauto" name="editgallerystream" data-formdata="editgallerystream" value="Update"/>
                </div>
            </div>
        </form>
	';
	$top='<table id="resultcontenttable" cellspacing="0">
				<thead><tr><th>Title</th><th>Details</th><th>Photos</th><th>Date</th><th>status</th><th>View/Edit</th></tr></thead>
				<tbody>';
	$bottom='	</tbody>
			</table>';
	$row['numrows']=$numrows;
	$row['datasetcount']=$ccount;
	$row['resultdataset']=$returndataset;
	$row['chiefquery']=$rowmonitor['chiefquery'];
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

	$row['adminoutput']=$paginatetop.$adminoutput.$paginatebottom;
	$row['adminoutputtwo']=$adminoutput;
	$row['vieweroutput']=$vieweroutput;

	return $row;
}
function getSingleGalleryEntry($galleryid){
	global $host_addr,$host_target_addr;
	$row=array();
	if($query!==""){
		if(is_numeric($galleryid)){
			$query="SELECT * FROM gallery WHERE id=$galleryid";
			
		}else{
			$gtext=mysql_real_escape_string($galleryid);
			$query="SELECT * FROM gallery WHERE title='$gtext'";
		}
		
	}else{
		$query="SELECT * FROM gallery WHERE id=0";

	}
	$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
	$row=mysql_fetch_assoc($run);
	$id=$row['id'];
	$gallerytitle=$row['gallerytitle'];
	$gallerydetails=$row['gallerydetails'];
	$date=$row['entrydate'];
	$status=$row['status'];
	$outselect="";
	for($i=1;$i<=10;$i++){
		$pic="";
		$i>1?$pic="photos":$pic="photo";		
		$outselect.='<option value="'.$i.'">'.$i.''.$pic.'</option>';
	}
	//get complete gallery images and create thumbnail where necessary;	$mediaquery="SELECT * FROM media WHERE ownerid=$galleryid AND ownertype='gallery' AND status='active' ORDER BY id DESC";
	$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line ".__LINE__);
	$medianumrows=mysql_num_rows($mediarun);
	$album="No album photos yet.";
	$cover='<div id="bottomgalleryholders" title="'.$gallerytitle.'" data-mainimg="" data-images="" data-sizes="" data-details="'.$gallerydetails.'">
		No Photos Yet.
		</div>';
	$thumbstack="";
	$locationstack="";
	$dimensionstack="";
	$mediadata=array();
	$mediacount=$medianumrows;
	if($medianumrows>0){
		$count=0;
		$album="";
		while ($mediarow=mysql_fetch_assoc($mediarun)) {
			# code...
			$mediadata[]=$mediarow;
			if($count==0){
				$coverphoto=$mediarow['details'];
				$maincoverphoto=$mediarow['location'];
			}
			$imgid=$mediarow['id'];
			$realimg=$mediarow['location'];
			$thumb=$mediarow['details'];
			$width=$mediarow['width'];
			$height=$mediarow['height'];
			$locationstack==""?$locationstack.=$host_addr.$realimg:$locationstack.=">".$host_addr.$realimg;
			$dimensionstack==""?$dimensionstack.=$width.",".$height:$dimensionstack.="|".$width.",".$height;
			$album.='
			<div id="editimgs" name="albumimg'.$imgid.'">
				<div id="editimgsoptions">
					<div id="editimgsoptionlinks">
						<a href="##" name="deletepic" data-id="'.$imgid.'"data-galleryid="'.$id.'"data-arraypoint="'.$count.'" data-src="'.$host_addr.''.$realimg.'"><img name="deletepic" src="../images/trashfirst.png" title="Delete this photo?" class="total"></a>
						<a href="##" name="viewpic" data-id="'.$imgid.'" data-galleryid="'.$id.'" data-arraypoint="'.$count.'" data-src="'.$host_addr.''.$realimg.'"><img name="viewpic" src="../images/viewpicfirst.png" title="View full image" class="total"></a>								
					</div>
				</div>	
				<img src=".'.$mediarow['details'].'" name="realimage" data-width="'.$width.'" data-height="'.$height.'" style="height:100%;margin:auto;">
			</div>
			';
			$count++;
		}
		$cover='
		<div id="bottomgalleryholders" title="'.$gallerytitle.'" data-mainimg="'.$host_addr.''.$maincoverphoto.'" data-images="'.$locationstack.'" data-sizes="'.$dimensionstack.'" data-details="'.$gallerydetails.'">
			<img src="'.$host_addr.''.$coverphoto.'" height="100%" class=""/>
		</div>';	
	}
	
	$row['adminoutput']='
		<tr data-id="'.$id.'">
			<td>'.$gallerytitle.'</td><td>'.$gallerydetails.'</td><td>'.$mediacount.'</td><td>'.$date.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsinglegallery" data-divid="'.$id.'">Edit</a></td>
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
	<div id="form" style="background-color:#fefefe;">
		<form action="../snippets/edit.php" name="editgallery" method="post" enctype="multipart/form-data">
			<input type="hidden" name="entryvariant" value="editgallery"/>
			<input type="hidden" name="entryid" value="'.$id.'"/>
			<div id="formheader">Edit '.$gallerytitle.'</div>
			<div id="formend">
					Gallery Title *<br>
					<input type="text" name="gallerytitle" Placeholder="The album title here." class="curved"/>
			</div>
			<div id="formend">
				 Gallery Details*<br>
				<textarea name="gallerydetails" id="" placeholder="Place all details of the album here." class="curved3" style="width:447px;height:206px;">'.$gallerydetails.'</textarea>
			</div>
			<div id="formend">
				Upload some more album photos to this Gallery:<br>
				<input type="hidden" name="piccount" value=""/>
				<select name="photocount" class="curved2" title="Choose the amount of photos you want to upload, max of 10, then click below the selection to continue">
				<option value="">--choose amount--</option>
					'.$outselect.'
				</select>							
			</div>
			<div id="formend" name="galleryfullholder'.$id.'">
			Gallery Images, click the target icon to view, click the trash icon to.....trash it, its that simple.<br>
			<input type="hidden" name="gallerydata'.$id.'" data-title="'.$gallerytitle.'" data-mainimg="'.$host_addr.''.$maincoverphoto.'" data-images="'.$locationstack.'" data-sizes="'.$dimensionstack.'" data-details="'.$gallerydetails.'"/>
				'.$album.'
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
				<input type="submit" name="updategallery" value="Update" class="submitbutton"/>
			</div>
		</form>
	</div>
	';
	$row['adminoutputthree']=$album;
	$row['vieweroutput']=$cover;
	return $row;
}
function getAllGalleryEntries($viewer,$limit,$type="",$data=array()){
	include('globalsmodule.php');
	$row=array();
	$outputtype="gallery";
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
	$subquery="";
	if($type!==""){

	}
	if($limit!==""&&$viewer=="admin"){
		$query="SELECT * FROM gallery $subquery ORDER BY id DESC $limit";
		$rowmonitor['chiefquery']="SELECT * FROM gallery $subquery ORDER BY id DESC";
	}elseif($viewer=="viewer"){
		$query="SELECT * FROM gallery WHERE status='active' $subquery ORDER BY id DESC $limit";
		$rowmonitor['chiefquery']="SELECT * FROM gallery WHERE status='active' $subquery";	
	}
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
	$numrows=mysql_num_rows($run);
	$adminoutput="<td colspan=\"100%\">No entries</td>";
	$adminoutputtwo="";
	$vieweroutput='<font color="#fefefe">Sorry, No Galleries have been created Yet</font>';
	$vieweroutputtwo='<font color="#fefefe">Sorry, No Galleries have been created Yet</font>';
	$returndataset=array();
	if($numrows>0){
		$adminoutput="";
		$adminoutputtwo="";
		$vieweroutput="";
		while($row=mysql_fetch_assoc($run)){
			$outs=getSingleGallery($row['id']);
			$returndataset[]=$outs;
			$adminoutput.=$outs['adminoutput'];
			$adminoutputtwo.=$outs['adminoutputtwo'];
			$vieweroutput.=$outs['vieweroutput'];
		}

	}
	$top='<table id="resultcontenttable" cellspacing="0">
				<thead><tr><th>Title</th><th>Details</th><th>Photos</th><th>Date</th><th>status</th><th>View/Edit</th></tr></thead>
				<tbody>';
	$bottom='	</tbody>
			</table>';
	$row['numrows']=$numrows;
	$row['resultdataset']=$returndataset;
	$row['chiefquery']=$rowmonitor['chiefquery'];
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

	$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
	$row['adminoutputtwo']=$top.$adminoutput.$bottom;
	$row['vieweroutput']=$vieweroutput;

	return $row;
}

?>