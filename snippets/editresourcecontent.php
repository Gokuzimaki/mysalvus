<?php
	// make sure the connection.php file is accessible
	// by testing if a function required by the edit form is available
	if(!function_exists('pullFontAwesomeClasses')){
		include("connection.php");
	}
	!isset($maintype)?$pagetype="resourcearticles":$pagetype=$maintype;
	// get groupscount i.e total number of group based results
	$gc=$row['edresultset']['groupscount'];
	// get fieldcount
	$fc=$row['edresultset']['fieldscount'];

	// sort through the normal single fields
	if($fc>0){
		
		for ($j=0; $j < $fc; $j++) { 
			$cfnamecount=$row['edresultset']['fieldscount'];
			$cdata=$row['edresultset'][$j]['fieldname'];
			$ctype=$row['edresultset'][$j]['fieldtype'];
			$$cdata="";
			// prepare a corresponding _filedata variable for the name
			$fdataout=$cdata."_filedata";
			// echo "$ctype - ctype<br>";
			if($ctype=="file"&&$row['edresultset']["$cdata"]['id']>0){
				if(isset($cfiledata)){
					unset($cfiledata);	
				}
				$cfiledata=array();
				$cfiledata['id']=$row['edresultset']["$cdata"]['id'];
				$cfiledata['mediatype']=$row['edresultset']["$cdata"]['mediatype'];
				$cfiledata['location']=$row['edresultset']["$cdata"]['location'];
				$cfiledata['medsizes']=$row['edresultset']["$cdata"]['medsizes'];
				$cfiledata['thumbnail']=$row['edresultset']["$cdata"]['thumbnail'];
				$cfiledata['title']=$row['edresultset']["$cdata"]['title'];
				$cfiledata['preview']=$row['edresultset']["$cdata"]['preview'];
				$cfiledata['details']=$row['edresultset']["$cdata"]['details'];
				$cfiledata['width']=$row['edresultset']["$cdata"]['width'];
				$cfiledata['height']=$row['edresultset']["$cdata"]['height'];
				$cfiledata['filesize']=$row['edresultset']["$cdata"]['filesize'];
				// echo $row['edresultset']["$cdata"]['thumbnail']." cur data ".$cfiledata['thumbnail']." <br>";
			}else if($ctype=="file"&&$row['edresultset']["$cdata"]['id']==0){
				if(isset($cfiledata)){
					unset($cfiledata);	
				}
				$cfiledata=array();
				$cfiledata['id']="";
				$cfiledata['mediatype']="";
				$cfiledata['location']="";
				$cfiledata['medsizes']="";
				$cfiledata['thumbnail']="";
				$cfiledata['title']="";
				$cfiledata['preview']="";
				$cfiledata['details']="";
				$cfiledata['width']="";
				$cfiledata['height']="";
				$cfiledata['filesize']="";
			}else{
				$cfiledata="";
			}
			$$fdataout=$cfiledata;
			$$cdata=$row['edresultset']["$cdata"]['value']!=="NA"&&$row['edresultset']["$cdata"]['value']!==""?$row['edresultset']["$cdata"]['value']:"";
		}
	}
	// var_dump($coverimage_filedata);
	
	
	  
	// get the content scripts for working with the current entry
	$contentscripts=$row['edresultset']['totalscripts'];
	$multipletest="";
	// groupresult is an array that carries the final result output for a groupset
	$groupresult=array();
	// echo "$gc";
	if($gc>0){
		for($i=0;$i<$gc;$i++){
			$ix=$i+1;
			// create group output variables
			$gdat="group$ix";
			$$gdat="";
			// get current sentinel and set value in variable
			// burst the value set into individual file elements
			$goutput="";
			$cgmonitor="";
			$cglink="";
			$cgdatamap="";
			$cginsertion="";
			// this section handles the creation and setup for groupset content
			// sentinels and options, per group
			$groupsentinel=$row['edresultset'][''.$gdat.'']['sentinel'];
			$sentisplit=explode("count", $groupsentinel);
			$tsent=$sentisplit[0];
			$itype="";
			$grouplimit="";
			$grouptitletext="";
			$gdeminimum=isset($row['edresultset'][''.$gdat.'']['gdeminimum'])?$row['edresultset'][''.$gdat.'']['gdeminimum']:0;
			$groupdatamap="";
			// represents the executable script contents for the current group
			$curgroupsetscripts="";
			// represents the current set of group entries 
			$curgroupset="";
			$mingsdetails="";
			// represents the minimum number of entries that accrues to the current group
			$demcount="";
			$groupentryminimum="";
			$curgroupresultcount=0;
			$ctestmult=1;
			$tci=0;
			//condition block for setting up variable values per group set
			if($pagetype=="resourcearticles"){
				$gallery_images=array();
				if($ix==1){
					$groupdatamap='galimage-:-input<|>caption-:-input<|>details-:-textarea';
					$grouplimit="10";
					// $gdeminimum=2;
					// $groupentryminimum='<input type="hidden" name="'.$gdat.'_entryminimum" value="'.$gdeminimum.'"/>';
					// $mingsdetails="<small>(Minimum group Entry: $gdeminimum)</small>";
				}
			}else if ($pagetype=="resourcecasestudy") {
				# code...
			}else if ($pagetype=="resourcevideos") {
				# code...
			}else if ($pagetype=="resourceseminars") {
				# code...
				$speaker_sets=array();
				$groupdatamap='fullname-:-input<|>speakerimage-:-input<|>position-:-input<|>website-:-input<|>linkedin-:-input<|>bio-:-textarea';
				$grouplimit="10";
				$gdeminimum=1;
				$groupentryminimum='<input type="hidden" name="'.$gdat.'_entryminimum" value="'.$gdeminimum.'"/>';
				$mingsdetails="<small>(Minimum group Entry: $gdeminimum)</small>";
			}

			$cgmonitor='<input type="hidden" name="'.$groupsentinel.'"  value="'.$ctestmult.'" data-counter="true"/>';
			$cglink='<a href="##'.$gdat.'_ADD" 
							data-type="triggerformadd" 
							data-name="'.$groupsentinel.'_addlink" 
							data-i-type="'.$itype.'" 
							data-limit="'.$grouplimit.'" 
							data-sentineltype="'.$curgroupresultcount.'"
							class="generic_addcontent_trigger"><i class="fa fa-plus"></i> Add More?</a>';
			$cgdatamap='<input name="'.$tsent.'datamap" type="hidden" data-map="true" value="'.$groupdatamap.'"/>';
			$cginsertion='<div name="'.$tsent.'entrypoint" data-marker="true"></div>';
			$curgroupset='<input type="hidden" name="'.$gdat.'_cedit" value="'.$curgroupresultcount.'"/>';

			if(isset($row['edresultset'][''.$gdat.''])
				&&isset($row['edresultset'][''.$gdat.'']['fieldcount'])
				&&$row['edresultset'][''.$gdat.'']['fieldcount']>0){

				$curgroup=$row['edresultset'][''.$gdat.''];
				$cfnamecount=$curgroup['fieldcount'];

				$curgroupresultcount=$row['edresultset'][''.$gdat.'']['groupcount'];
				$ctestmult=$curgroupresultcount+1;
				$groupfilevalueset=$row['edresultset'][''.$gdat.'']['filesetvalues'];
				if($groupfilevalueset!==""){
					$gdatfileset=explode(",", $groupfilevalueset);
					if(count($gdatfileset)>0){
						$goutput.='<input type="hidden" name="'.$gdat.'_filecount" value="'.count($gdatfileset).'"/>';
						for ($gk=0; $gk < count($gdatfileset); $gk++) { 
							# code...
							$goutput.='<input type="hidden" name="'.$gdat.'_fileid_'.$gk.'" value="'.$gdatfileset[$gk].'"/>';

						}
					}
				}
				
				 
				$cgmonitor='<input type="hidden" name="'.$groupsentinel.'"  value="'.$ctestmult.'" data-counter="true"/>';
				/*<div name="galleryslidesentrypoint" data-marker="true"></div>
			            	<input name="galleryslidescount" type="hidden" value="'.$tci.'" data-counter="true"/>
			            	<input name="galleryslidesdatamap" type="hidden" data-map="true" value="galimage-:-input<|>caption-:-input<|>details-:-textarea"/>
			            	<a href="##" class="generic_addcontent_trigger" data-type="triggerformadd" data-name="galleryslidescount_addlink" data-i-type="" data-limit="10"> <i class="fa fa-plus"></i> Add More?</a>*/
				$cglink='<a href="##'.$gdat.'_ADD" 
							data-type="triggerformadd" 
							data-name="'.$groupsentinel.'_addlink" 
							data-i-type="'.$itype.'" 
							data-limit="'.$grouplimit.'" 
							data-sentineltype="'.$curgroupresultcount.'"
							class="generic_addcontent_trigger"><i class="fa fa-plus"></i> Add More?</a>';
				$cgdatamap='<input name="'.$tsent.'datamap" type="hidden" data-map="true" value="'.$groupdatamap.'"/>';
				$cginsertion='<div name="'.$tsent.'entrypoint" data-marker="true"></div>';
				$curgroupset='<input type="hidden" name="'.$gdat.'_cedit" value="'.$curgroupresultcount.'"/>';
				// echo "<br>$curgroupresultcount - $cfnamecount<br>";

				// create the sentinel number variable used in differentiating
				// each element name in the group from the others
				// eg linktitlea - element name generic to a group
				// linktitlea.$tci => linktitle1, - specific name for 
				// single entry in the group
				$tci=0;
				// if the current group has results in it
				if($curgroupresultcount>0){
					$max_lenght=6;
					for ($j=0; $j < $curgroupresultcount; $j++) { 
						// echo "in j here";
						for ($k=0; $k < $cfnamecount; $k++) { 
							# code...
							// get the field name, check to see if its a media type of field
							// or not, then proceed to create associate values for them
							$cdata=$curgroup[0][$k]['fieldname'];
							$ctype=$curgroup[0][$k]['fieldtype'];
							// turn the field name into an accessible variable
							$$cdata="";
							if(isset($cfiledata)){
								unset($cfiledata);	
							}

							// echo "<br> $j - curcount<br>";
							// var_dump($curgroup[$j]["$cdata"]);
							// get the current value
							$cdataout=isset($curgroup[$j]["$cdata"]['value'])&&$curgroup[$j]["$cdata"]['value']!=="NA"&&$curgroup[$j]["$cdata"]['value']!==""?$curgroup[$j]["$cdata"]['value']:"";
							$$cdata=$cdataout;
							// prepare a corresponding _filedata variable for the name
							$fdataoutg=$cdata."_filedata";
							// echo "<br> $ctype - ctype $cdata - cur fieldname $gdat - group<br>";
							if($ctype=="file"&&$curgroup[$j]["$cdata"]['id']>0){
								$cfiledata=array();
								$cfiledata['id']=$curgroup[$j]["$cdata"]['id'];
								$cfiledata['mediatype']=$curgroup[$j]["$cdata"]['mediatype'];
								$cfiledata['location']=$curgroup[$j]["$cdata"]['location'];
								// echo "<br> $cdata - curcdata, ".$curgroup[$j]["$cdata"]['location']." - curvalue <br>";
								$cfiledata['medsizes']=$curgroup[$j]["$cdata"]['medsizes'];
								$cfiledata['thumbnail']=$curgroup[$j]["$cdata"]['thumbnail'];
								$cfiledata['title']=$curgroup[$j]["$cdata"]['title'];
								$cfiledata['preview']=$curgroup[$j]["$cdata"]['preview'];
								$cfiledata['details']=$curgroup[$j]["$cdata"]['details'];
								$cfiledata['width']=$curgroup[$j]["$cdata"]['width'];
								$cfiledata['height']=$curgroup[$j]["$cdata"]['height'];
								$cfiledata['filesize']=$curgroup[$j]["$cdata"]['filesize'];
							}else if($ctype=="file"&&$curgroup[$j]["$cdata"]['id']==0){

								$cfiledata=array();
								$cfiledata['id']="";
								$cfiledata['mediatype']="";
								$cfiledata['location']="";
								$cfiledata['medsizes']="";
								$cfiledata['thumbnail']="";
								$cfiledata['title']="";
								$cfiledata['preview']="";
								$cfiledata['details']="";
								$cfiledata['width']="";
								$cfiledata['height']="";
								$cfiledata['filesize']="";
							}else{
								$cfiledata="";
							}
							$$fdataoutg=$cfiledata;
							// var_dump($cfiledata);
							// echo "<br> $cdataout - curcdata single<br>";

							// make the variable value
							// $tval=$$cdata;
							// echo "$cdata - cdata | $linktitlea - data value<br>";
						}
						// proceed to create all necessary variables
						// $curvalueset=$curgroup[];
						$tci=$j+1;
						// create the removal portion for the current entry data

						$entryremoval='
							<div class="col-sm-12"> 
				              <div class="form-group">
				                <label>Remove Entry '.$tci.':</label>
				                <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-file-text"></i>
				                  </div>
				                  <select class="form-control" name="'.$gdat.'_status'.$tci.'">
				                    <option value="">Delete this?</option> 
				                    <option value="inactive">Yes</option> 
				                  </select>

				                </div><!-- /.input group -->
				              </div><!-- /.form group -->
				            </div>
						';
						# code...  
						// proceed to handle group related information accordingly.
						// condition blocks of code can be inserted  here to carry out
						// group entries data in the event of multiple groups, where
						// the variable 'ix' represents the group counter
						if($ix==1){
							// this is group 1
							if($pagetype=="resourcearticles"){
								if($galimage!==""||$caption!==""||$details!==""){
								    $curmultiple='
							        	<div class="col-md-3 multi_content_hold">
						            		<h4 class="multi_content_countlabels">Gallery (Entry '.$tci.')</h4>
						            		<div class="col-sm-12">
						            			<div class="form-group">
						            				<div class="edit_image_mini_display">
						                              	<a href="'.$galimage.'" data-lightbox="subgallery'.$ix.'">
						                              		<img src="'.$host_addr.''.$galimage_filedata['thumbnail'].'"/>
						                              	</a>
						                            </div>
						                            <label>Image</label>
						                            <div class="input-group">
						                              <div class="input-group-addon">
						                                <i class="fa fa-file-text"></i>
						                              </div>
						                              <input type="hidden" class="form-control" name="galimage'.$tci.'_id" value="'.$galimage_filedata['id'].'"/>
						                              <input type="file" class="form-control" name="galimage'.$tci.'" data-edittype="true" Placeholder="Choose file"/>
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
						                              <input type="text" class="form-control" name="caption'.$tci.'" Placeholder="Image Caption" value="'.$caption.'"/>
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
						                              <textarea class="form-control" rows="3" name="details'.$tci.'" Placeholder="Give details if any">'.$details.'</textarea>
						                            </div><!-- /.input group -->
						                        </div><!-- /.form group -->
						            		</div>
							            	'.$entryremoval.'
						            	</div>     
								    ';      
								    $multipletest.=$curmultiple;
								    $gallery_images[$j]['thumbnail']=$galimage_filedata['thumbnail']; 
								    $gallery_images[$j]['location']=$galimage; 
								    $gallery_images[$j]['caption']=str_replace('"',"&#34;",$caption); 
								    $gallery_images[$j]['details']=str_replace('"',"&#34;",$details); 
								}	

							
							}else if ($pagetype=="resourcecasestudy") {
								# code...
							}else if ($pagetype=="resourcevideos") {
								# code...
							}else if ($pagetype=="resourceseminars") {
								# code...
								if($fullname!==""||$speakerimage!==""||$website!==""||$bio!==""){
									if($website!==""){
										$htmd=checkUrlHttpString($website);
										if($htmd['matchdata']===false){
											$website="http://".$website;
										}
									}
									if($linkedin!==""){
										$htmd=checkUrlHttpString($linkedin);
										if($htmd['matchdata']===false){
											$linkedin="http://".$linkedin;
										}
									}
									$curmultiple='
										<div class="col-md-12 multi_content_hold">
			                        		<h4 class="multi_content_countlabels">Speakers (Entry '.$tci.')</h4>
			                        		<div class="col-sm-6">
			                        			<div class="form-group">
						                            <label>Fullname</label>
						                            <div class="input-group">
						                              <div class="input-group-addon">
						                                <i class="fa fa-file-text"></i>
						                              </div>
						                              <input type="text" class="form-control" name="fullname'.$tci.'" value="'.$fullname.'" Placeholder="The Speakers fullname"/>
						                            </div><!-- /.input group -->
						                        </div><!-- /.form group -->
			                        		</div>
			                        		<div class="col-sm-6">
			                        			<div class="form-group">
				                        			<div class="edit_image_mini_display">
						                              	<a href="'.$speakerimage.'" data-lightbox="subgallery'.$ix.'">
						                              		<img src="'.$host_addr.''.$speakerimage_filedata['thumbnail'].'"/>
						                              	</a>
						                            </div>
						                            <label>Photograph</label>
						                            <div class="input-group">
						                              <div class="input-group-addon">
						                                <i class="fa fa-file-image-o"></i>
						                              </div>
						                              <input type="hidden" class="form-control" name="speakerimage'.$tci.'_id" value="'.$speakerimage_filedata['id'].'"/>
						                              <input type="file" class="form-control" data-form-edit="true" name="speakerimage'.$tci.'" Placeholder="Choose file"/>
						                            </div><!-- /.input group -->
						                        </div><!-- /.form group -->
			                        		</div>
			                        		<div class="col-sm-4">
			                        			<div class="form-group">
						                            <label>Company/Position</label>
						                            <div class="input-group">
						                              <div class="input-group-addon">
						                                <i class="fa fa-file-text"></i>
						                              </div>
						                              <input type="text" class="form-control" name="position'.$tci.'" value="'.$position.'" Placeholder="The speakers company, position or both"/>
						                            </div><!-- /.input group -->
						                        </div><!-- /.form group -->
			                        		</div>
			                        		<div class="col-sm-4">
			                        			<div class="form-group">
						                            <label>Website</label>
						                            <div class="input-group">
						                              <div class="input-group-addon">
						                                <!-- <i class="fa fa-file-text"></i> -->
						                                <span class="text-addon">http://</span>
						                              </div>
						                              <input type="text" class="form-control" name="website'.$tci.'" value="'.$website.'" Placeholder="The Speakers website address"/>
						                            </div><!-- /.input group -->
						                        </div><!-- /.form group -->
			                        		</div>
			                        		<div class="col-sm-4">
			                        			<div class="form-group">
						                            <label>Social(LinkedIn)</label>
						                            <div class="input-group">
						                              <div class="input-group-addon">
						                                <i class="fa fa-linkedin"></i>
						                                <!-- <span class="text-addon">http://</span> -->
						                              </div>
						                              <input type="text" class="form-control" name="linkedin'.$tci.'" value="'.$linkedin.'" Placeholder="The Speakers LinkedIn address"/>
						                            </div><!-- /.input group -->
						                        </div><!-- /.form group -->
			                        		</div>
			                        		<div class="col-sm-12">
			                        			<div class="form-group">
						                            <label>Biography</label>
						                            <div class="input-group">
						                              <div class="input-group-addon">
						                                <i class="fa fa-file-text"></i>
						                              </div>
						                              <textarea class="form-control" rows="3" cols="3" name="bio'.$tci.'" data-mce="true" id="postersmallthree'.$tci.'" data-type="tinymcefield" Placeholder="Provide the Speakers Biography">'.$bio.'</textarea>
						                            </div><!-- /.input group -->
						                        </div><!-- /.form group -->
			                        		</div>
						            		'.$entryremoval.'
			                        	</div>
									';
									$multipletest.=$curmultiple;
									$speaker_sets[$j]['thumbnail']=$speakerimage_filedata['thumbnail']; 
									$speaker_sets[$j]['medsizes']=$speakerimage_filedata['medsizes']; 
								    $speaker_sets[$j]['location']=$speakerimage; 
								    $speaker_sets[$j]['fullname']=$fullname; 
								    $speaker_sets[$j]['position']=$position; 
								    $speaker_sets[$j]['website']=$website; 
								    $speaker_sets[$j]['linkedin']=$linkedin; 
								    $speaker_sets[$j]['bio']=$bio; 

									$curgroupsetscripts.='
											var curmcethreeposter'.$tci.'=[];
											callTinyMCEInit("form[name=edit'.$formdata.'] textarea[id=postersmallthree'.$tci.']",curmcethreeposter'.$tci.');
									';
								}
							}
						}
					}
				}
				// add the final open space fields for new group content
				/*$groupresult[$i]='
					'.$cgmonitor.'
					'.$multipletest.'
					'.$cginsertion.'
					'.$cglink.'
				';*/
			}
			// $tcc=$tci+2;
			$tci=$tci+1;

			// mulitple group content data handling condition blocks
			// for post current content coalation
			if($multipletest==""){
				$multipletest="<p>No Content Found</p>";
			}
			// for carrying the new content section
			$ngesection="";
			if($ix==1){
				if($pagetype=="resourcearticles"){
					$grouptitletext="Article Gallery ";
					$ngesection='
						<h4 class="newgroupadditionheadingclass">
							Add New Entries Maximum of 10 images at a time
						</h4>  
						<div class="col-md-3 multi_content_hold" data-type="triggerprogenitor" data-cid="'.$tci.'" data-name="'.$tsent.'">
		            		<h4 class="multi_content_countlabels">Gallery (Entry '.$tci.')</h4>
		            		<div class="col-sm-12">
		            			<div class="form-group">
		                            <label>Image</label>
		                            <div class="input-group">
		                              <div class="input-group-addon">
		                                <i class="fa fa-file-text"></i>
		                              </div>
		                              <input type="file" class="form-control" name="galimage'.$tci.'"  Placeholder="Choose file"/>
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
		                              <input type="text" class="form-control" name="caption'.$tci.'" Placeholder="Caption"/>
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
		                              <textarea class="form-control" rows="3" name="details'.$tci.'" Placeholder="Give details if any"/></textarea>
		                            </div><!-- /.input group -->
		                        </div><!-- /.form group -->
		            		</div>
		            	</div>
				    ';
				}else if ($pagetype=="resourcecasestudy") {
					# code...
				}else if ($pagetype=="resourcevideos") {
					# code...
				}else if ($pagetype=="resourceseminars") {
					# code...
					$ngesection='
						<div class="col-md-12 multi_content_hold" data-type="triggerprogenitor" data-cid="'.$tci.'" data-name="speakerentries">
                    		<h4 class="multi_content_countlabels">Speakers (Entry '.$tci.')</h4>
                    		<div class="col-sm-6">
                    			<div class="form-group">
		                            <label>Fullname</label>
		                            <div class="input-group">
		                              <div class="input-group-addon">
		                                <i class="fa fa-file-text"></i>
		                              </div>
		                              <input type="text" class="form-control" name="fullname'.$tci.'" Placeholder="The Speakers fullname"/>
		                            </div><!-- /.input group -->
		                        </div><!-- /.form group -->
                    		</div>
                    		<div class="col-sm-6">
                    			<div class="form-group">
		                            <label>Photograph</label>
		                            <div class="input-group">
		                              <div class="input-group-addon">
		                                <i class="fa fa-file-image-o"></i>
		                              </div>
		                              <input type="file" class="form-control" name="speakerimage'.$tci.'" Placeholder="Choose file"/>
		                            </div><!-- /.input group -->
		                        </div><!-- /.form group -->
                    		</div>
                    		<div class="col-sm-4">
                    			<div class="form-group">
		                            <label>Company/Position</label>
		                            <div class="input-group">
		                              <div class="input-group-addon">
		                                <i class="fa fa-file-text"></i>
		                              </div>
		                              <input type="text" class="form-control" name="position'.$tci.'" Placeholder="The speakers company, position or both"/>
		                            </div><!-- /.input group -->
		                        </div><!-- /.form group -->
                    		</div>
                    		<div class="col-sm-4">
                    			<div class="form-group">
		                            <label>Website</label>
		                            <div class="input-group">
		                              <div class="input-group-addon">
		                                <!-- <i class="fa fa-file-text"></i> -->
		                                <span class="text-addon">http://</span>
		                              </div>
		                              <input type="text" class="form-control" name="website'.$tci.'" Placeholder="The Speakers website address"/>
		                            </div><!-- /.input group -->
		                        </div><!-- /.form group -->
                    		</div>
                    		<div class="col-sm-4">
                    			<div class="form-group">
		                            <label>Social(LinkedIn)</label>
		                            <div class="input-group">
		                              <div class="input-group-addon">
		                                <i class="fa fa-linkedin"></i>
		                                <!-- <span class="text-addon">http://</span> -->
		                              </div>
		                              <input type="text" class="form-control" name="linkedin'.$tci.'" Placeholder="The Speakers LinkedIn address"/>
		                            </div><!-- /.input group -->
		                        </div><!-- /.form group -->
                    		</div>
                    		<div class="col-sm-12">
                    			<div class="form-group">
		                            <label>Biography</label>
		                            <div class="input-group">
		                              <div class="input-group-addon">
		                                <i class="fa fa-file-text"></i>
		                              </div>
		                              <textarea class="form-control" rows="3" cols="3" name="bio'.$tci.'" data-mce="true" id="postersmallthree'.$tci.'" data-type="tinymcefield" Placeholder="Provide the Speakers Biography"/></textarea>
		                            </div><!-- /.input group -->
		                        </div><!-- /.form group -->
                    		</div>
                    	</div>
					';
					$curgroupsetscripts.='
							var curmcethreeposter'.$tci.'=[];
							callTinyMCEInit("form[name=edit'.$formdata.'] textarea[id=postersmallthree'.$tci.']",curmcethreeposter'.$tci.');
					';
				}
			}
			$$gdat='

				'.$cgmonitor.'
				'.$groupentryminimum.'
				'.$curgroupset.'
				<div class="box-group" id="'.$pagetype.''.$gdat.'">
					<div class="panel box overflowhidden box-primary">
					    <div class="box-header with-border">
					        <h4 class="box-title">
					          <a data-toggle="collapse" data-parent="#'.$pagetype.''.$gdat.'" href="#'.$gdat.'">
					            <i class="fa fa-gear fa-spin"></i> Edit Previous '.$grouptitletext.'Content '.$mingsdetails.'
					          </a>
					        </h4>
					    </div>
					    <div id="'.$gdat.'" class="panel-collapse collapse overflowhidden">
							'.$multipletest.'
					    </div>
					</div>
				</div>
				'.$ngesection.'
				'.$goutput.'
				'.$cginsertion.'
				'.$cgdatamap.'
				'.$cglink.'
				<script>
					$(document).ready(function(){
						'.$curgroupsetscripts.'
					})
				</script>
				';
		}
		// make the current set of data entries collapsible to leave 
		// only the new ones in view

	}
	// get the form extra data and errormaps here due to conflict in variable handling
	// form data injection into generalinfo function
	$extraformdetails='
		<!-- form control section -->
        <input type="hidden" name="formdata" value="'.$formdata.'"/>
        <input type="hidden" name="extraformdata" value="'.$extraformdata.'"/>
        <input type="hidden" name="errormap" value="'.$errormap.'"/>
	';
	$maindayout=date('D, d F, Y h:i:s A', strtotime($date));
	$mm=date('M', strtotime($date));
	$md=date('d', strtotime($date));

	$extraformdata="";
	$extraformscript="";
	$extraformscriptdataspecific="";
	if ($pagetype=="resourcearticles") {
		if($doarticlefiles!==""){
			$attach_showclass="";
		}else{
			$attach_showclass="hidden";
		}
		if($dogallery!==""){
			$gallery_showclass="";
		}else{
			$gallery_showclass="hidden";
		}
		# code...
		$extraformdata='
			<div class="col-md-12">                        
	            <div class="col-sm-6"> 
	              <div class="form-group">
	                <label>Article Title</label>
	                <div class="input-group">
	                  <div class="input-group-addon">
	                    <i class="fa fa-file-text"></i>
	                  </div>
	                  <input type="text" class="form-control" name="contenttitle" value="'.$contenttitle.'" Placeholder="The Article title"/>
	                </div><!-- /.input group -->
	              </div><!-- /.form group -->
	            </div>
	            <div class="col-sm-6"> 
	              <div class="form-group">
	                <label>Cover Image</label>
	                <div class="input-group">
	                  <div class="input-group-addon">
	                    <i class="fa fa-file-text"></i>
	                  </div>
	                  <input type="hidden" class="form-control" name="coverimage_id" value="'.$coverimage_filedata['id'].'"/>
	                  <input type="file" class="form-control" name="coverimage" data-edittype="true" Placeholder="Choose file"/>
	                </div><!-- /.input group -->
	              </div><!-- /.form group -->
	            </div>
	            <div class="col-sm-4"> 
	              <div class="form-group">
	                <label>Attach Files?</label>
	                <div class="input-group">
	                  <div class="input-group-addon">
	                    <i class="fa fa-file-text"></i>
	                  </div>
	                  <select name="doarticlefiles" class="form-control">
	                    <option value="">Choose </option>
	                    <option value="yes">YES</option>
	                  </select>
	                </div><!-- /.input group -->
	              </div><!-- /.form group -->
	            </div>
	            <div class="col-md-12 '.$attach_showclass.' articles_files">    
	                <div class="col-sm-6"> 
	                  <div class="form-group">
	                    <label>Attach Word Document</label>
	                    <div class="input-group">
	                      <div class="input-group-addon">
	                        <i class="fa fa-file-word-o"></i>
	                      </div>
	                      <input type="hidden" class="form-control" name="worddoc_id" value="'.$worddoc_filedata['id'].'"/>
	                      <input type="file" class="form-control" name="worddoc" data-edittype="true" Placeholder="Choose file"/>
	                      <select class="form-control" name="worddoc_delete">
		                    <option value="">Delete this?</option> 
		                    <option value="inactive">Yes</option> 
		                  </select>
	                    </div><!-- /.input group -->
	                  </div><!-- /.form group -->
	                </div>
	                <div class="col-sm-6"> 
	                  <div class="form-group">
	                    <label>Attach PDF Document</label>
	                    <div class="input-group">
	                      <div class="input-group-addon">
	                        <i class="fa fa-file-pdf-o"></i>
	                      </div>
	                      <input type="hidden" class="form-control" name="pdfdoc_id" value="'.$pdfdoc_filedata['id'].'"/>
	                      <input type="file" class="form-control" name="pdfdoc" data-edittype="true" Placeholder="Choose file"/>
	                      <select class="form-control" name="pdfdoc_delete">
		                    <option value="">Delete this?</option> 
		                    <option value="inactive">Yes</option> 
		                  </select>
	                    </div><!-- /.input group -->
	                  </div><!-- /.form group -->
	                </div>
	            </div>
	            <div class="col-md-12">
	                <label>Article Details Content(brief content)</label>
	                <textarea class="form-control" rows="3" name="contentpost" id="postersmallfiveedit" placeholder="Article details">'.$contentpost.'</textarea>
	            </div>

	            <div class="col-sm-6"> 
	              <div class="form-group">
	                <label>Attach Gallery?</label>
	                <div class="input-group">
	                  <div class="input-group-addon">
	                    <i class="fa fa-file-text"></i>
	                  </div>
	                  <select name="dogallery" class="form-control">
	                    <option value="">Choose </option>
	                    <option value="yes">YES</option>
	                  </select>
	                </div><!-- /.input group -->
	              </div><!-- /.form group -->
	            </div>
	            <div class="col-md-12 '.$gallery_showclass.' dogalleryslides multi_content_hold_generic">
	            	'.$group1.'
	            </div> 
	        </div>
		';
		// $extraformscript='';
	}else if ($pagetype=="resourcecasestudy") {
		# code...
		$extraformdata='
			<div class="col-md-12">                        
	            <div class="col-sm-6"> 
	              <div class="form-group">
	                <label>Case Study Title</label>
	                <div class="input-group">
	                  <div class="input-group-addon">
	                    <i class="fa fa-file-text"></i>
	                  </div>
	                  <input type="text" class="form-control" name="contenttitle" value="'.$contenttitle.'" Placeholder="The Case Study title"/>
	                </div><!-- /.input group -->
	              </div><!-- /.form group -->
	            </div>
	            <div class="col-sm-6"> 
	              <div class="form-group">
	                <label>Cover Image</label>
	                <div class="input-group">
	                  <div class="input-group-addon">
	                    <i class="fa fa-file-text"></i>
	                  </div>
	                  <input type="hidden" class="form-control" name="coverimage_id" value="'.$coverimage_filedata['id'].'"/>
	                  <input type="file" class="form-control" name="coverimage" data-edittype="true" Placeholder="Choose file"/>
	                </div><!-- /.input group -->
	              </div><!-- /.form group -->
	            </div>
	            
	            <div class="col-md-12">    
	                <div class="col-sm-6"> 
	                  <div class="form-group">
	                    <label>Attach Word Document</label>
	                    <div class="input-group">
	                      <div class="input-group-addon">
	                        <i class="fa fa-file-word-o"></i>
	                      </div>
	                      <input type="hidden" class="form-control" name="worddoc_id" value="'.$worddoc_filedata['id'].'"/>
	                      <input type="file" class="form-control" name="worddoc" data-edittype="true" Placeholder="Choose file"/>
	                      <!--<select class="form-control" name="worddoc_delete">
		                    <option value="">Delete this?</option> 
		                    <option value="inactive">Yes</option> 
		                  </select>-->
	                    </div><!-- /.input group -->
	                  </div><!-- /.form group -->
	                </div>
	                <div class="col-sm-6"> 
	                  <div class="form-group">
	                    <label>Attach PDF Document</label>
	                    <div class="input-group">
	                      <div class="input-group-addon">
	                        <i class="fa fa-file-pdf-o"></i>
	                      </div>
	                      <input type="hidden" class="form-control" name="pdfdoc_id" value="'.$pdfdoc_filedata['id'].'"/>
	                      <input type="file" class="form-control" name="pdfdoc" data-edittype="true" Placeholder="Choose file"/>
	                      <!--<select class="form-control" name="pdfdoc_delete">
		                    <option value="">Delete this?</option> 
		                    <option value="inactive">Yes</option> 
		                  </select>-->
	                    </div><!-- /.input group -->
	                  </div><!-- /.form group -->
	                </div>
	            </div>
	            <div class="col-md-12">
	                <label>Case Study Introductory Content(brief content</label>
	                <textarea class="form-control" rows="3" name="contentpost" id="postersmallfiveedit" placeholder="Case Study details">'.$contentpost.'</textarea>
	            </div>
	        </div>
		';
	}else if ($pagetype=="resourcevideos") {
		# code...
		$vidcoverout=$host_addr.'images/fvtimages/fvtvideodefaultposter.jpg';
		$local_showclass="";
		$online_showclass="";
		if($dovideouploads=="localvideo"){
			$local_showclass="";
			$online_showclass="hidden";
		}else{
			$local_showclass="hidden";
			$online_showclass="";
		}
		if($coverimage!==""){
			$vidcoverout=$host_addr.$coverimage_filedata['medsizes'];
		}

		$localvideoout='';
		$onlinevideoout='';
		$webmout="";
		$webmmark="";
		$webmextradata="";
		$webmdelete="";
		if ($videowebm!=="") {
			# code...
			$webmout='<source src="'.$videowebm.'"/>';
			$webmmark='<i class="fa fa-check color-green text-shadow-color-white"></i>';
			$webmextradata='
				<input type="hidden" class="form-control" name="videowebm_id" value="'.$videowebm_filedata['id'].'"/>
                <select class="form-control" name="videowebm_delete">
                    <option value="">Delete this?</option> 
                    <option value="inactive">Yes</option> 
                </select>
			';
		}

		$flvout="";
		$flvmark="";
		$flvextradata="";
		$flvdelete="";
		if ($videoflv!=="") {
			# code...
			$flvout='<source src="'.$videoflv.'"/>';
			$flvmark='<i class="fa fa-check color-green text-shadow-color-white"></i>';
			$flvextradata='
				<input type="hidden" class="form-control" name="videoflv_id" value="'.$videoflv_filedata['id'].'"/>
                <select class="form-control" name="videoflv_delete">
                    <option value="">Delete this?</option> 
                    <option value="inactive">Yes</option> 
                </select>
			';
		}

		$mgpout="";
		$mgpmark="";
		$mgpextradata="";
		$mgpdelete="";
		if ($video3gp!=="") {
			# code...
			$mgpout='<source src="'.$video3gp.'"/>';
			$mgpmark='<i class="fa fa-check color-green text-shadow-color-white"></i>';
			$mgpextradata='
				<input type="hidden" class="form-control" name="videomgp_id" value="'.$video3gp_filedata['id'].'"/>
                <select class="form-control" name="videomgp_delete">
                    <option value="">Delete this?</option> 
                    <option value="inactive">Yes</option> 
                </select>
			';
		}

		if($videoflv!==""||$videowebm!==""||$video3gp!==""){
			$localvideoout='
				<video  class="frame-video" controls="" preload="false" poster="'.$vidcoverout.'">
					'.$webmout.'
					'.$flvout.'
					'.$mgpout.'
				</video>
			';
		}

		$extraformdata='
			<div class="col-md-12">               
                <div class="col-sm-6"> 
                  <div class="form-group">
                    <label>Video Title</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-file-text"></i>
                      </div>
                      <input type="text" class="form-control" name="contenttitle" Placeholder="The Video title" value="'.$contenttitle.'"/>
                    </div><!-- /.input group -->
                  </div><!-- /.form group -->
                </div>
                <div class="col-sm-6"> 
                  <div class="form-group">
                    <label>Cover Image(Works only on Local Videos)</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-file-text"></i>
                      </div>
					  <input type="hidden" class="form-control" name="coverimage_id" value="'.$coverimage_filedata['id'].'"/>
                      <input type="file" class="form-control" data-edittype="true" name="coverimage" Placeholder="Choose file"/>
                    </div><!-- /.input group -->
                  </div><!-- /.form group -->
                </div>
                <div class="col-sm-12"> 
                  <div class="form-group">
                    <label>Video Type?</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-file-text"></i>
                      </div>
                      <select name="dovideouploads" class="form-control">
                        <option value="localvideo">Local Video</option>
                        <option value="embedvideo">Embed Video</option>
                      </select>
                    </div><!-- /.input group -->
                  </div><!-- /.form group -->
                </div>
                <div class="col-md-12 localvideos '.$local_showclass.'"> 
					<div class="edit_video_mini_display">
                		'.$localvideoout.'
                	</div>
                    <h4>Upload direct Videos(Max Combined Upload is 32MB)<small>If individual video size is large, consider uploading them one at a time</small></h4>   
                    <div class="col-sm-4"> 
                      <div class="form-group">
                        <label>WEBM Video '.$webmmark.'</label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-file-movie-o"></i>
                          </div>
                          <input type="file" class="form-control" data-edittype="true" name="videowebm" Placeholder="Choose file"/>
                          '.$webmextradata.'
                        </div><!-- /.input group -->
                      </div><!-- /.form group -->
                    </div>
                    <div class="col-sm-4"> 
                      <div class="form-group">
                        <label>FLV Video '.$flvmark.'</label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-file-movie-o"></i>
                          </div>
                          <input type="file" class="form-control" data-edittype="true" name="videoflv" Placeholder="Choose file"/>
                          '.$flvextradata.'
                        </div><!-- /.input group -->
                      </div><!-- /.form group -->
                    </div>
                    <div class="col-sm-4"> 
                      <div class="form-group">
                        <label>3GP Video '.$mgpmark.'</label>
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-file-movie-o"></i>
                          </div>
                          <input type="file" class="form-control" data-edittype="true" name="video3gp" Placeholder="Choose file"/>
                          '.$mgpextradata.'
                        </div><!-- /.input group -->
                      </div><!-- /.form group -->
                    </div>
                </div>
                <div class="col-md-12 embedvideo '.$online_showclass.'">
                	<div class="edit_video_mini_display">
                		'.$embedcode.'
                	</div>
                    <label>Video Embed code</label>
                    <textarea class="form-control" rows="3" name="embedcode" placeholder="Place Youtube embed code here">'.$embedcode.'</textarea>
                </div>
                <div class="col-md-12">
                    <label>Video Description Content(brief content)</label>
                    <textarea class="form-control" rows="3" name="contentpost" placeholder="Place a short description here">'.$contentpost.'</textarea>
                </div>
            </div>   
		';
	}else if ($pagetype=="resourceseminars") {
		# code...
		$extraformdata='
			<div class="col-md-12">                    
                <div class="col-sm-6"> 
                  <div class="form-group">
                    <label>Title</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-file-text"></i>
                      </div>
                      <input type="text" class="form-control" name="contenttitle" value="'.$contenttitle.'" Placeholder="Seminar Title"/>
                    </div><!-- /.input group -->
                  </div><!-- /.form group -->
                </div>
                <div class="col-sm-6"> 
                  <div class="form-group">
                    <label>Cover Image</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-file-text"></i>
                      </div>
	                  <input type="hidden" class="form-control" name="coverimage_id" value="'.$coverimage_filedata['id'].'"/>
                      <input type="file" class="form-control" name="coverimage" data-edittype="true" Placeholder="Choose file"/>
                    </div><!-- /.input group -->
                  </div><!-- /.form group -->
                </div>
                <div class="col-md-12">
        			<div class="form-group">
                        <label>Seminar Introductory Content(brief content)</label>
                        <textarea class="form-control" rows="3" name="contentintro" data-mce="true" id="postersmallfiveedit" placeholder="Seminar Intro">'.$contentintro.'</textarea>
                    </div>
                </div>
                <div class="col-md-12 marginized">
        			<div class="form-group">
                        <label>Seminar Details(Full content)</label>
                        <textarea class="form-control" rows="3" name="contentpost" data-mce="true" id="adminposteredit" placeholder="Seminar Full details">'.$contentpost.'</textarea>
                    </div>
                </div>
				<div class="col-md-12 multi_content_hold_generic">
	            	'.$group1.'
                </div> 
                
            </div>
		';
	}
	$extraformdata.=$extraformdetails;
	// hide unwanted content
	$contentName = array(
						'resourcearticles' => 'Articles',
						'resourcecasestudy' => 'Case Studies',
						'resourceseminars' => 'Seminars',
						'resourcevideos' => 'Resource Videos',
					);
	$contentformtitle=$contentName[$maintype];
	$showhidetitle="display:none;";
    $showhidesubtitle="display:none;";
    $showhideimage="display:none;";
    $showhideintro="display:none;";
    $showhidecontent="display:none;";
    $formdataname='edit'.$formdata;
    $formsubmitname="submitcustomtwo";
    $formsubmittitle="Update $contentformtitle";
    $formsubmittype="button";
    $curvariant="contententryupdate";
    $formsubmitdataattr="data-formdata='$formdataname'";
    $contenttextheaderout="Edit $contentformtitle Entry";
	$wholescript="";
    $extraformscript='
		var curmceadminposter=[];
		curmceadminposter[\'width\']="100%";
		curmceadminposter[\'height\']="500px";
		curmceadminposter[\'toolbar1\']="undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect";
		curmceadminposter[\'toolbar2\']="| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ";
		callTinyMCEInit("textarea#adminposteredit",curmceadminposter);
		tinyMCE.init({
		      theme : "modern",
		      selector:"textarea#postersmallfiveedit",
		      menubar:false,
		      statusbar: false,
		      plugins : [
		       "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
		       "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
		       "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
		      ],
		      width:"100%",
		      height:"250px",
		      toolbar1: "undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
		      toolbar2: "| code | link unlink anchor | emoticons ",
		      image_advtab: true ,
		      editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
		      content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
		      external_filemanager_path:""+host_addr+"scripts/filemanager/",
		      filemanager_title:"Content Filemanager" ,
		      external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
		});
    	tinyMCE.init({
          theme : "modern",
          selector:"#postersmallsixedit",
          menubar:false,
          statusbar: false,
          plugins : [
           "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
           "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
           "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
          ],
          width:"100%",
          height:"200px",
          toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
          toolbar2: " | link unlink anchor | emoticons",
          image_advtab: true ,
          editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
          content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
          external_filemanager_path:""+host_addr+"scripts/filemanager/",
          filemanager_title:"Content Filemanager" ,
          external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
  		});
    '.$totalscripts;
	// check to see if the view is admin or viewer based
    // if its a viewer`
    // create output eval for singlegeneralinfo
    // echo $viewer;
    if (isset($viewertype)&&$viewertype=="viewer") {
    	# code...
    	if($pagetype=="resourcearticles"){
			$contentgroupdata['evaldata']['single']['postoutputeval']='
					$dicon="fa-file-text-o";
					$ricon="fa-arrow-circle-right";
					$rtext="Read More";
					$rattr="";
					$rlinkdata=$host_addr."resourceentries.php?p=".$id;
					$dbit="";
					$frametype="";
					$framecontent=\'<a href="##">
                                    <img src="'.$coverimage_filedata['medsizes'].'" alt="'.$contenttitle.'">
                                </a>\';
					if($doarticlefiles!==""){
						$dicon="fa-download";
						$worddoc==""?$wordbit="##":$wordbit=$worddoc_filedata[\'location\'];
						$pdfdoc==""?$pdfbit="##":$pdfbit=$pdfdoc_filedata[\'location\'];
						$dbit=\'<div class="attachment-section" data-name="resourcedownload" style="display: none;">
	                            	<a href="##Word" class="color-blue" data-src=".\'.$wordbit.\'" data-params="downloadtype=resource" data-name="file-download">
	                            		<i class="fa fa-file-word-o"></i>
	                            	</a>
	                            	<a href="##PDF" class="color-red" data-src=".\'.$pdfbit.\'" data-params="downloadtype=resource" data-name="file-download">
	                            		<i class="fa fa-file-pdf-o"></i>
	                            	</a>
	                            </div>\';
	                    $rattr=\'data-download="true"\';
	                    $rtext="Download";
	                    $rlinkdata="##";
					}
					if($dogallery!==""){
						$frametype=\' frame-gallery\';

						if(isset($gallery_images)&&count($gallery_images)>0){
							$framecontent="";
							for ($il=0; $il < count($gallery_images); $il++) { 
								# code...
								$curthumb=$gallery_images[$il][\'thumbnail\'];
								$curfile=$gallery_images[$il][\'location\'];
								$curcaption=$gallery_images[$il][\'caption\'];
								$curdetails=$gallery_images[$il][\'details\'];
								if($il<8){
									$framecontent.=\'<div class="col-sm-3 gallery-img">
			                                            <a href="\'.$curfile.\'" class="slide-thumb-holder" data-lightbox="gallerymain_'.$id.'" data-src="\'.$curfile.\'" data-title="<h4 class=\\\'galimgdetailshigh\\\'>\'.$curcaption.\'</h4>: \'.$curdetails.\'">
			                                                <img src="\'.$curthumb.\'" alt="img">
			                                            </a>
		                                        	</div>\';
									
								}else{
									$framecontent.=\'<a href="\'.$curfile.\'" class="slide-thumb-holder" data-lightbox="gallerymain_'.$id.'" data-src="\'.$curfile.\'" data-title="<h4 class=\\\'galimgdetailshigh\\\'>\'.$curcaption.\'</h4>: \'.$curdetails.\'"></a>\';
								}
							}
						}
					}
					$row[\'vieweroutputmini\']=\'
						<div class="blog-box">
                            <div class="frame\'.$frametype.\'">
                                \'.$framecontent.\'
                            </div>
                            <div class="text-box">
                                <h2>'.$contenttitle.'</h2>
                                <strong>
                                    <a href="#" class="mnt">
                                        <i class="fa fa-clock-o"></i>
                                        '.$maindayout.'
                                    </a>
                                </strong>
                                <p>\'.$intro.\'</p>
                                <div class="btn-row">
                                    <a href="##" class="pic">
                                        <i class="fa \'.$dicon.\'"></i>
                                    </a>
                                    <a href="##" class="date">
                                        '.$mm.' <br>'.$md.'
                                    </a>
                                    <a href="\'.$rlinkdata.\'" class="btn-readmore" \'.$rattr.\'>
                                        <i class="fa fa-arrow-circle-right"></i>
                                        \'.$rtext.\'
                                    </a>
                                    \'.$dbit.\'
                                </div>
                            </div>
                        </div>
						
					\';
					$row[\'vieweroutputmaxi\']=\'
						<div class="blog-box">
                            <div class="frame\'.$frametype.\'">
                                \'.$framecontent.\'
                            </div>
                            <div class="text-box">
                                <h2>'.$contenttitle.'</h2>
                                <strong>
                                    <a href="#" class="mnt">
                                        <i class="fa fa-clock-o"></i>
                                        '.$maindayout.'
                                    </a>
                                </strong>
                                <div>\'.$contentpost.\'</div>
                            </div>
                        </div>
						
					\';
			';
    	}else if ($pagetype=="resourcecasestudy") {
			# code...
    		$contentgroupdata['evaldata']['single']['postoutputeval']='
					$dbit="";
					$dbittwo="";
					$frametype=" casestudy_frame";
					$framecontent=\'<a href="##">
                                    <img src="'.$coverimage_filedata['medsizes'].'" alt="'.$contenttitle.'">
                                </a>\';
					$dicon="fa-download";
					$worddoc==""?$wordbit="##":$wordbit=$worddoc_filedata[\'location\'];
					$pdfdoc==""?$pdfbit="##":$pdfbit=$pdfdoc_filedata[\'location\'];
					$dbit=\'<div class="attachment-section" data-name="resourcedownload" style="display: none;">
                            	<a href="##Word" class="color-blue" data-src=".\'.$wordbit.\'" data-params="downloadtype=resource" data-name="file-download">
                            		<i class="fa fa-file-word-o"></i>
                            	</a>
                            	<a href="##PDF" class="color-red" data-src=".\'.$pdfbit.\'" data-params="downloadtype=resource" data-name="file-download">
                            		<i class="fa fa-file-pdf-o"></i>
                            	</a>
                            </div>\';
                    $dbittwo=\'<div class="attachment-section" data-name="resourcedownload">
                            	<a href="##Word" class="color-blue" data-src=".\'.$wordbit.\'" data-params="downloadtype=resource" data-name="file-download">
                            		<i class="fa fa-file-word-o"></i>
                            	</a>
                            	<a href="##PDF" class="color-red" data-src=".\'.$pdfbit.\'" data-params="downloadtype=resource" data-name="file-download">
                            		<i class="fa fa-file-pdf-o"></i>
                            	</a>
                            </div>\';
                    $rattr=\'data-download="true"\';
                    $rtext="Download";
                    $rlinkdata="##";
					
					
					$row[\'vieweroutputmini\']=\'
						<div class="blog-box">
                            <div class="frame\'.$frametype.\'">
                                \'.$framecontent.\'
                            </div>
                            <div class="text-box casestudy_text-box">
                                <h2>'.$contenttitle.'</h2>
                                <strong>
                                    <a href="#" class="mnt">
                                        <i class="fa fa-clock-o"></i>
                                        '.$maindayout.'
                                    </a>
                                </strong>
                                <div class="post-content-preview">\'.$intro.\'</div>
                                <div class="btn-row">
                                    <a href="##" class="pic">
                                        <i class="fa \'.$dicon.\'"></i>
                                    </a>
                                    <a href="##" class="date">
                                        '.$mm.' <br>'.$md.'
                                    </a>
                                    <a href="\'.$rlinkdata.\'" class="btn-readmore" \'.$rattr.\'>
                                        <i class="fa fa-arrow-circle-right"></i>
                                        \'.$rtext.\'
                                    </a>
                                    \'.$dbit.\'
                                </div>
                            </div>
                        </div>
						
					\';
					$row[\'vieweroutputmaxi\']=\'
						<div class="blog-box">
                            <div class="frame\'.$frametype.\'">
                                \'.$framecontent.\'
                            </div>
                            <div class="text-box casestudy_text-box">
                                 <strong>
                                    <a href="#" class="mnt">
                                        <i class="fa fa-clock-o"></i>
                                        '.$maindayout.'
                                    </a>
                                </strong>
                                <div class="post-content-preview">\'.$contentpost.\'</div>
                                <div class="btn-row">
                                    
                                    \'.$dbittwo.\'
                                </div>
                            </div>
                        </div>
						
					\';
			';
		}else if ($pagetype=="resourcevideos") {
			# code...
			$contentgroupdata['evaldata']['single']['postoutputeval']='
					
					if($dovideouploads=="embedvideo"){
						$videoout=\'<div class="frame-video">\'.$embedcode.\'</div>\';
					}else if($dovideouploads=="localvideo"){
						$videoout=$localvideoout;
					}

					$row[\'vieweroutputmini\']=\'
						<div class="blog-box video">
                            <div class="frame">
                                \'.$videoout.\'
                            </div>
                            <div class="text-box">
                                <h2>'.$contenttitle.'</h2>
                                <strong>
                                    <a href="#" class="mnt">
                                        <i class="fa fa-clock-o"></i>
                                        '.$maindayout.'
                                    </a>
                                </strong>
                                <div class="post-content-preview">\'.$contentpost.\'</div>

                            </div>
                        </div>
						
					\';
					$row[\'vieweroutputmaxi\']=\'
						<div class="blog-box video">
                            <div class="frame">
                                \'.$videoout.\'
                            </div>
                            <div class="text-box">
                                
                                <strong>
                                    <a href="#" class="mnt">
                                        <i class="fa fa-clock-o"></i>
                                        '.$maindayout.'
                                    </a>
                                </strong>
                                <div class="post-content-preview">\'.$contentpost.\'</div>

                            </div>
                        </div>
						
					\';


			';
		}else if ($pagetype=="resourceseminars") {
			# code...
			$contentgroupdata['evaldata']['single']['postoutputeval']='
					$framecontent=\'<a href="##">
                                    <img src="'.$coverimage_filedata['medsizes'].'" alt="'.$contenttitle.'">
                                </a>\';
					$rlinkdata=$host_addr."resourceentries.php?p=".$id;
					$speakercontent="";
					$speakerfullcontent="";
					if(isset($speaker_sets)&&count($speaker_sets)>0){
						for ($il=0; $il < count($speaker_sets); $il++) { 
							# code...
							$curthumb=$speaker_sets[$il][\'thumbnail\'];
							$curmedsize=$speaker_sets[$il][\'medsizes\'];
							$curfile=$speaker_sets[$il][\'location\'];
							$curfullname=$speaker_sets[$il][\'fullname\'];
							$curposition=$speaker_sets[$il][\'position\'];
							$curwebsite=$speaker_sets[$il][\'website\'];
							$curwebsite==""?$curwebsite="##":$curwebsite=$curwebsite;
							$curlinkedin=$speaker_sets[$il][\'linkedin\'];
							$curlinkedin==""?$curlinkedin="##":$curlinkedin=$curlinkedin;
							$curbio=$speaker_sets[$il][\'bio\'];
							$curcontent=\'
									<div class=" col-sm-6 speaker_minidata_hold">
                                		<div class="col-md-2 speaker_photo_point">
                                			<a href="\'.$curfile.\'" data-lightbox="speakergallery'.$id.'" data-src="\'.$curfile.\'" data-title="<h4 class=\\\'galimgdetailshigh\\\'>\'.$curfullname.\'</h4>: \'.$curposition.\'"><img class="speaker_photo" src="\'.$curthumb.\'"/></a>
                                		</div>
                                		<div class="col-md-10">
                                            <h4 class="speaker_fullname">\'.$curfullname.\'</h4>
											<h4 class="speaker_position">\'.$curposition.\'</h4>
											<div class="web_social">
												<p><b>Website:</b> <a href="\'.$curwebsite.\'" target="_blank">Click to view</a></p>
												<p><b>LinkedIn:</b> <a href="\'.$curlinkedin.\'" target="_blank">Click to view</a></p>
											</div> 
											<a href="##" data-name="speakerbio" class="speakerbio">View Bio</a>
                                		</div>
                            			<div class="speaker_bio hidden">
                            				\'.$curbio.\'
                            			</div>
                                	</div>\';
                            $curfullcontent=\'
									<div class=" col-sm-12 speaker_fulldata_hold">
                                		<div class="col-md-4 speaker_photo_point">
                                			<a href="\'.$curfile.\'" data-lightbox="speakergallery'.$id.'" data-src="\'.$curfile.\'" 
                                				data-title="
                                					<h4 class=\\\'galimgdetailshigh\\\'>
                                					\'.$curfullname.\'
                                					</h4>: \'.$curposition.\'">
                                				<img class="speaker_photo" src="\'.$curmedsize.\'"/>
                                			</a>
                                		</div>
                                		<div class="col-md-8">
                                            <h4 class="speaker_fullname">\'.$curfullname.\'</h4>
											<h4 class="speaker_position">\'.$curposition.\'</h4>
											<div class="web_social">
												<p><b>Website:</b> <a href="\'.$curwebsite.\'" target="_blank">Click to view</a></p>
												<p><b>LinkedIn:</b> <a href="\'.$curlinkedin.\'" target="_blank">Click to view</a></p>
											</div> 
											<a href="##" data-name="speakerbio" class="speakerbio">View Bio</a>
                                		</div>
                            			<div class="speaker_bio hidden">
                            				\'.$curbio.\'
                            			</div>
                                	</div>\';
							if($il<8){
								$speakercontent.=$curcontent;
							}
							$speakerfullcontent.=$curfullcontent;
						}
					}
					

					$row[\'vieweroutputmini\']=\'
						<div class="blog-box">
							<div class="frame">
                                \'.$framecontent.\'
                            </div>
                            <div class="text-box seminarbox">
                                
                                <h2 class="sector_heading_main">\'.$contenttitle.\'</h2>
                                <div class="seminardata">
                                	\'.$intro.\'
                                	<h3 class="sector_heading min_speakers_heading">Participating speakers</h3>
                                	\'.$speakercontent.\'
                                </div>
                                <strong>
                                    
                                </strong>
                                <div class="btn-row">
                                    <a href="##" class="pic">
                                        <i class="fa fa-file-text"></i>
                                    </a>
                                    <a href="#" class="date">
                                        '.$mm.' <br>'.$md.'
                                    </a>
                                    <a href="\'.$rlinkdata.\'" target="_blank" class="btn-readmore">
                                        <i class="fa fa-arrow-circle-right"></i>
                                        Learn More
                                    </a>
                                </div>
                            </div>
                        </div>
					\';
					$row[\'vieweroutputmaxi\']=\'
						<div class="blog-box">
							<div class="frame">
                                \'.$framecontent.\'
                            </div>
                            <div class="text-box seminarbox"> 
                                <div class="seminardata">
                                	\'.$contentpost.\'
                                	<div class="our-event-accordion">
                                        <div class="accordion" id="accordion'.$id.'">
                                            <div class="accordion-group">
                                                <div class="accordion-heading active">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion'.$id.'" href="#collapseOne">
                                                        <h3 class="speaker_set_trigger">Participating speakers (\'.count($speaker_sets).\') <small>Click to view</small></h3>
                                                    </a>
                                                </div>
                                                <div id="collapseOne" class="accordion-body collapse collapse">
                                                    <div class="accordion-text-box">
                                                        <div class="accordion-inner">
					                                		\'.$speakerfullcontent.\'
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <strong>
                                    
                                </strong>
                                
                            </div>
                        </div>
					\';

			';
		}

    	// var_dump($contentgroupdata);
    }
?>