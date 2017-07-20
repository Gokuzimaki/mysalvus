<?php
	
	$contentName = array(
						'resourcearticles' => 'Articles',
						'resourcecasestudy' => 'Case Studies',
						'resourceseminars' => 'Seminars',
						'resourcevideos' => 'Resource Videos',
					);
	$formtruetype="resourcecontent";
	!isset($pagetype)?$pagetype="resourcearticles":$pagetype=$pagetype;
	if(isset($entrytype)){
		$pagetype=$entrytype;
	}
	$contentname=isset($contentName["$pagetype"])?$contentName["$pagetype"]:"";
	;
	$newcontentname=isset($contentName["$pagetype"])?substr($contentname, 0,strlen($contentname)-1):"";
	// $datatype[0]=!isset($pagetype)?"hometopcollageboxintro":$pagetype."intro";  
	if(isset($contentgroupdatasingle)){
	    unset($contentgroupdatasingle);
	}
	if(isset($contentgroupdatageneral)){
	    unset($contentgroupdatageneral);
	}


  	// $contentgroupdatageneral['evaldata']['general']=array();

	/*if($pagetype=="resourcearticles"){
		
	}*/
	// get the general data for the current entry
	  if(isset($cursubtype)&&$cursubtype!==""){
	    $processfile="process$cursubtype.php";
	    $dogeneraldata="multiple";
	    if(file_exists("$processfile")){
	      include("$processfile");
	    }
	    // var_dump($contentgroupdata);
	    $outsdata=getAllGeneralInfo("admin",$pagetype,'','',$contentgroupdata);
	  }else{
	    // echo "here";
	    $outsdata=getAllGeneralInfo("admin",$pagetype,'','',$contentgroupdatageneral);
	    
	  }
?>
<div class="box">
	<div class="box-body">
	    <div class="box-group" id="generaldataaccordion">
			<div class="panel box overflowhidden box-primary">
			    <div class="box-header with-border">
			        <h4 class="box-title">
			          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#NewPageManagerBlock">
			            <i class="fa fa-sliders"></i> New <?php echo $contentname;?>
			          </a>
			        </h4>
			    </div>
			    <div id="NewPageManagerBlock" class="panel-collapse collapse ">
			        <div class="row">
			            <form name="<?php echo $formtruetype;?>" method="POST" onSubmit="return false" enctype="multipart/form-data" action="../snippets/basicsignup.php">
			              <input type="hidden" name="entryvariant" value="contententry"/>
			              <input type="hidden" name="maintype" value="<?php echo $pagetype?>"/>
			              <input type="hidden" name="subtype" value="<?php echo $formtruetype;?>"/>
			              <?php
				              if ($pagetype=="resourcearticles") {
				              	# code...
			              
			              ?>
			              <div class="col-md-12" name="surveysliderpoint">

			                    <div class="col-md-12">
			                        
			                        <div class="col-sm-6"> 
			                          <div class="form-group">
			                            <label>Article Title</label>
			                            <div class="input-group">
			                              <div class="input-group-addon">
			                                <i class="fa fa-file-text"></i>
			                              </div>
			                              <input type="text" class="form-control" name="contenttitle" Placeholder="The Article title"/>
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
			                              <input type="file" class="form-control" name="coverimage" Placeholder="Choose file"/>
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
				                    <div class="col-md-12 hidden articles_files">    
				                        <div class="col-sm-6"> 
				                          <div class="form-group">
				                            <label>Attach Word Document</label>
				                            <div class="input-group">
				                              <div class="input-group-addon">
				                                <i class="fa fa-file-word-o"></i>
				                              </div>
				                              <input type="file" class="form-control" name="worddoc" Placeholder="Choose file"/>
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
				                              <input type="file" class="form-control" name="pdfdoc" Placeholder="Choose file"/>
				                            </div><!-- /.input group -->
				                          </div><!-- /.form group -->
				                        </div>
				                    </div>
			                        <div class="col-md-12">
			                            <label>Article Details Content(brief content)</label>
			                            <textarea class="form-control" rows="3" name="contentpost" id="postersmallfive" placeholder="Article details"></textarea>
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
			                        <div class="col-md-12 hidden dogalleryslides multi_content_hold_generic">
			                        	<h3>Maximum of 10 images at a time</h3>
			                        	<div class="col-md-3 multi_content_hold" data-type="triggerprogenitor" data-cid="1" data-name="galleryslides">
			                        		<h4 class="multi_content_countlabels">Gallery (Entry 1)</h4>
			                        		<div class="col-sm-12">
			                        			<div class="form-group">
						                            <label>Image</label>
						                            <div class="input-group">
						                              <div class="input-group-addon">
						                                <i class="fa fa-file-text"></i>
						                              </div>
						                              <input type="file" class="form-control" name="galimage1" Placeholder="Choose file"/>
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
						                              <input type="text" class="form-control" name="caption1" Placeholder="Choose file"/>
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
						                              <textarea class="form-control" rows="3" name="details1" Placeholder="Give details if any"/></textarea>
						                            </div><!-- /.input group -->
						                        </div><!-- /.form group -->
			                        		</div>
			                        	</div>
			                        	<div name="galleryslidesentrypoint" data-marker="true"></div>
			                        	<input name="galleryslidescount" type="hidden" value="1" data-counter="true"/>
			                        	<input name="galleryslidesdatamap" type="hidden" data-map="true" value="galimage-:-input<|>
			                        	caption-:-input<|>
			                        	details-:-textarea"/>
			                        	<a href="##" 
			                        	   class="generic_addcontent_trigger"
			                        	   data-type="triggerformadd" 
			                        	   data-name="galleryslidescount_addlink"
			                        	   data-i-type=""
			                        	   data-limit="10"> 
			                        		<i class="fa fa-plus"></i>Add More?
			                        	</a>
			                        </div> 
			                    </div>
			                    
			                    <!-- form control section -->
			                    <input type="hidden" name="formdata" value="resourcecontent"/>
			                    <input type="hidden" name="extraformdata" value="contenttitle-:-input<|>
			                      coverimage-:-input|image<|>
			                      doarticlefiles-:-select<|>
			                      worddoc-:-input|office|docx,doc-:-[group-|-
			                      								doarticlefiles-|-select-|-*any*-|-
			                      								pdfdoc-|-input-|-*null*>|<]<|>
			                      pdfdoc-:-input|pdf-:-[group-|-
	                      								doarticlefiles-|-select-|-*any*-|-
	                      								worddoc-|-input-|-*null*]<|>
			                      contentpost-:-textarea<|>
			                      dogallery-:-select<|>
			                      egroup|data-:-[galleryslidescount>|<
			                      				galimage-|-input|image>|<
			                      				caption-|-input>|<
			                      				details-|-textarea
			                      				]-:-groupfall[1-2,2-1,1-3,2-3]-:-[single-|-dogallery-|-select-|-*any*-|-galimage]"/>
			                      <!--  -->
			                    <input type="hidden" name="errormap" value="
			                        contenttitle-:-Please provide the title for this article<|>
				                    coverimage-:-Please choose a valid cover image for this<|>
				                    doarticlefiles-:-NA<|>
				                    worddoc-:-Please Provide a word document<|>
				                    pdfdoc-:-Please provide a pdf Document<|>
				                    contentpost-:-Please provide the content for this article<|>
				                    dogallery-:-NA<|>
				                    egroup|data-:-[
				                      				Please provide an image>|<
				                      				The caption for the image is required>|<
				                      				NA
				                      			]"/>
			              </div>
			              <?php
			          		}else if($pagetype=="resourcevideos"){
			          	  ?>
			          	  <div class="col-md-12" name="surveysliderpoint">
			                    <div class="col-md-12">
			                        
			                        <div class="col-sm-6"> 
			                          <div class="form-group">
			                            <label>Video Title</label>
			                            <div class="input-group">
			                              <div class="input-group-addon">
			                                <i class="fa fa-file-text"></i>
			                              </div>
			                              <input type="text" class="form-control" name="contenttitle" Placeholder="The Video title"/>
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
			                              <input type="file" class="form-control" name="coverimage" Placeholder="Choose file"/>
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
				                    <div class="col-md-12 localvideos"> 
				                        <h4>Upload direct Videos(Max Combined Upload is 32MB)<small>If individual video size is large, consider uploading them one at a time</small></h4>   
				                        <div class="col-sm-4"> 
				                          <div class="form-group">
				                            <label>WEBM Video</label>
				                            <div class="input-group">
				                              <div class="input-group-addon">
				                                <i class="fa fa-file-movie-o"></i>
				                              </div>
				                              <input type="file" class="form-control" name="videowebm" Placeholder="Choose file"/>
				                            </div><!-- /.input group -->
				                          </div><!-- /.form group -->
				                        </div>
				                        <div class="col-sm-4"> 
				                          <div class="form-group">
				                            <label>FLV Video</label>
				                            <div class="input-group">
				                              <div class="input-group-addon">
				                                <i class="fa fa-file-movie-o"></i>
				                              </div>
				                              <input type="file" class="form-control" name="videoflv" Placeholder="Choose file"/>
				                            </div><!-- /.input group -->
				                          </div><!-- /.form group -->
				                        </div>
				                        <div class="col-sm-4"> 
				                          <div class="form-group">
				                            <label>3GP Video</label>
				                            <div class="input-group">
				                              <div class="input-group-addon">
				                                <i class="fa fa-file-movie-o"></i>
				                              </div>
				                              <input type="file" class="form-control" name="video3gp" Placeholder="Choose file"/>
				                            </div><!-- /.input group -->
				                          </div><!-- /.form group -->
				                        </div>
				                    </div>
				                    <div class="col-md-12 hidden embedvideo">
			                            <label>Video Embed code</label>
			                            <textarea class="form-control" rows="3" name="embedcode" placeholder="Place Youtube embed code here"></textarea>
			                        </div>
			                        <div class="col-md-12">
			                            <label>Video Description Content(brief content)</label>
			                            <textarea class="form-control" rows="3" name="contentpost" placeholder="Place a short description here"></textarea>
			                        </div>
			                    </div>    
			                    <!-- form control section -->
			                    <input type="hidden" name="formdata" value="resourcecontent"/>
			                    <input type="hidden" name="extraformdata" value="contenttitle-:-input<|>
			                      coverimage-:-input|image-:-[single-|-dovideouploads-|-select-|-localvideo]<|>
			                      dovideouploads-:-select<|>
			                      videowebm-:-input|video|webm-:-[group-|-
			                      								dovideouploads-|-select-|-localvideo-|-
			                      								videoflv-|-input-|-*null*-|-video3gp-|-input-|-*null*>|<]<|>
			                      videoflv-:-input|video|flv-:-[group-|-
			                      								dovideouploads-|-select-|-localvideo-|-
			                      								video3gp-|-input-|-*null*-|-videowebm-|-input-|-*null*>|<]<|>
			                      video3gp-:-input|video|3gp-:-[group-|-
			                      								dovideouploads-|-select-|-localvideo-|-
			                      								videowebm-|-input-|-*null*-|-videoflv-|-input-|-*null*>|<]<|>
			                      embedcode-:-textarea-:-[single-|-dovideouploads-|-select-|-embedvideo]<|>
			                      contentpost-:-textarea
			                      "/>
			                      <!--  -->
			                    <input type="hidden" name="errormap" value="
				                    contenttitle-:-Provide a title for this video<|>
				                    coverimage-:-Please choose a valid cover image for this video<|>
				                    dovideouploads-:-NA<|>
				                    videowebm-:-Please Provide a webm video file<|>
				                    videoflv-:-Please provide a flv video file<|>
				                    video3gp-:-Please provide a 3gp video file<|>
				                    embedcode-:-Please paste the embed code obtained from youtube<|>
				                    contentpost-:-Please provide a brief description for this video"/>
			              </div>
			          	  <?php
			          		}else if ($pagetype=="resourcecasestudy") {
			          			# code...
			          		
			          	  ?>
			          	  <div class="col-md-12" name="surveysliderpoint">

			                    <div class="col-md-12">
			                        
			                        <div class="col-sm-6"> 
			                          <div class="form-group">
			                            <label>Title</label>
			                            <div class="input-group">
			                              <div class="input-group-addon">
			                                <i class="fa fa-file-text"></i>
			                              </div>
			                              <input type="text" class="form-control" name="contenttitle" Placeholder="The Case Study title"/>
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
			                              <input type="file" class="form-control" name="coverimage" Placeholder="Choose file"/>
			                            </div><!-- /.input group -->
			                          </div><!-- /.form group -->
			                        </div>
				                    <div class="col-md-12 articles_files">    
				                        <div class="col-sm-6"> 
				                          <div class="form-group">
				                            <label>Attach Word Document</label>
				                            <div class="input-group">
				                              <div class="input-group-addon">
				                                <i class="fa fa-file-word-o"></i>
				                              </div>
				                              <input type="file" class="form-control" name="worddoc" Placeholder="Choose file"/>
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
				                              <input type="file" class="form-control" name="pdfdoc" Placeholder="Choose file"/>
				                            </div><!-- /.input group -->
				                          </div><!-- /.form group -->
				                        </div>
				                    </div>
			                        <div class="col-md-12">
			                            <label>Case Study Introductory Content(brief content)</label>
			                            <textarea class="form-control" rows="3" name="contentpost" id="postersmallfive" placeholder="Collage box details"></textarea>
			                        </div>
 
			                    </div>
			                    
			                    
			                    <!-- form control section -->
			                    <input type="hidden" name="formdata" value="resourcecontent"/>
			                    <input type="hidden" name="extraformdata" value="contenttitle-:-input<|>
			                      coverimage-:-input|image<|>
			                      worddoc-:-input|office|docx-:-[single-|-pdfdoc-|-input-|-*null*>|<]<|>
			                      pdfdoc-:-input|pdf-:-[single-|-worddoc-|-input-|-*null*]<|>
			                      contentpost-:-textarea
			                      "/>
			                      <!--  -->
			                    <input type="hidden" name="errormap" value="
			                        contenttitle-:-Please provide the title for this case study<|>
				                    coverimage-:-Please choose a valid cover image for this<|>
				                    worddoc-:-Please Provide a word document<|>
				                    pdfdoc-:-Please Provide a pdf Document<|>
				                    contentpost-:-Please provide the introductory text for this case study
			                        "/>
			              </div>
			          	  <?php
							}else if ($pagetype=="resourceseminars") {
								# code...
							
			          	  ?>
			          	  <div class="col-md-12" name="surveysliderpoint">

			                    <div class="col-md-12">
			                        
			                        <div class="col-sm-6"> 
			                          <div class="form-group">
			                            <label>Title</label>
			                            <div class="input-group">
			                              <div class="input-group-addon">
			                                <i class="fa fa-file-text"></i>
			                              </div>
			                              <input type="text" class="form-control" name="contenttitle" Placeholder="Seminar Title"/>
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
			                              <input type="file" class="form-control" name="coverimage" Placeholder="Choose file"/>
			                            </div><!-- /.input group -->
			                          </div><!-- /.form group -->
			                        </div>
			                        <div class="col-md-12">
	                        			<div class="form-group">
				                            <label>Seminar Introductory Content(brief content)</label>
				                            <textarea class="form-control" rows="3" name="contentintro" data-mce="true" id="postersmallfive" placeholder="Seminar Intro"></textarea>
				                        </div>
			                        </div>
			                        <div class="col-md-12 marginized">
	                        			<div class="form-group">
				                            <label>Seminar Details(Full content)</label>
				                            <textarea class="form-control" rows="3" name="contentpost" data-mce="true" id="adminposter" placeholder="Seminar Full details"></textarea>
				                        </div>
			                        </div>
	 								<div class="col-md-12 multi_content_hold_generic">
			                        	<!-- <h3>Maximum of 10 images at a time</h3> -->
			                        	<div class="col-md-12 multi_content_hold" data-type="triggerprogenitor" data-cid="1" data-name="speakerentries">
			                        		<h4 class="multi_content_countlabels">Speakers (Entry 1)</h4>
			                        		<div class="col-sm-6">
			                        			<div class="form-group">
						                            <label>Fullname</label>
						                            <div class="input-group">
						                              <div class="input-group-addon">
						                                <i class="fa fa-file-text"></i>
						                              </div>
						                              <input type="text" class="form-control" name="fullname1" Placeholder="The Speakers fullname"/>
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
						                              <input type="file" class="form-control" name="speakerimage1" Placeholder="Choose file"/>
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
						                              <input type="text" class="form-control" name="position1" Placeholder="The speakers company, position or both"/>
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
						                              <input type="text" class="form-control" name="website1" Placeholder="The Speakers website address"/>
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
						                              <input type="text" class="form-control" name="linkedin1" Placeholder="The Speakers LinkedIn address"/>
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
						                              <textarea class="form-control" rows="3" cols="3" name="bio1" data-mce="true" id="postersmallthree" data-type="tinymcefield" Placeholder="Provide the Speakers Biography"/></textarea>
						                            </div><!-- /.input group -->
						                        </div><!-- /.form group -->
			                        		</div>
			                        	</div>
			                        	<div name="speakerentriesentrypoint" data-marker="true"></div>
			                        	<input name="speakerentriescount" type="hidden" value="1" data-valset="1,3,4" data-valcount="1" data-counter="true"/>
			                        	<input name="speakerentriesdatamap" type="hidden" data-map="true" value="
				                        	fullname-:-input<|>
				                        	speakerimage-:-input<|>
				                        	position-:-input<|>
				                        	website-:-input<|>
				                        	linkedin-:-input<|>
				                        	bio-:-textarea
			                        	"/>
			                        	<a href="##" 
			                        	   class="generic_addcontent_trigger"
			                        	   data-type="triggerformadd" 
			                        	   data-name="speakerentriescount_addlink"
			                        	   data-i-type=""
			                        	   data-limit=""> 
			                        		<i class="fa fa-plus"></i>Add More?
			                        	</a>
			                        </div>   
			                    </div>
			                    <!-- form control section -->
			                    <input type="hidden" name="formdata" value="resourcecontent"/>
			                    <input type="hidden" name="extraformdata" value="contenttitle-:-input<|>
			                      coverimage-:-input|image<|>
			                      contentintro-:-textarea<|>
			                      contentpost-:-textarea<|>
			                      egroup|data-:-[speakerentriescount>|<
													fullname-|-input>|<
													speakerimage-|-input|image>|<
													position-|-input>|<
													website-|-input>|<
													linkedin-|-input>|<
													bio-|-textarea
												]-:-groupfall[1-2,2-3,3-6,6-1]
			                      "/>
			                      <!--  -->
			                    <input type="hidden" name="errormap" value="
			                        contenttitle-:-Please provide the title of this Seminar<|>
				                    coverimage-:-Please choose a valid cover image for the seminar<|>
				                    contentintro-:-Please Provide introductory text for this seminar, this would be shown first on the website<|>
				                    contentpost-:-Please Provide the complete details for this seminar entry<|>
				                    egroup|data-:-[Please give the fullname of the speaker>|<
				                    				Provide an Image for the speaker>|<
				                    				Please state the Company, position or both of the speaker>|<
				                    				NA>|<
				                    				NA>|<
				                    				Please give a biography for the speaker]
			                        "/>
			              </div>
			          	  <?php
				          	}
				          	?>
			              <div class="col-md-12">
			                  <div class="box-footer">
			                      <input type="button" class="btn btn-danger" name="submitcustomtwo" data-formdata="<?php echo $formtruetype;?>" value="Create"/>
			                  </div>
			              </div>
			            </form>
			        </div>
			    </div>
			</div>
			<div class="panel box overflowhidden box-primary">
			    <div class="box-header with-border">
			      <h4 class="box-title">
			        <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#EditBlock">
			          <i class="fa fa-gear"></i> Edit <?php echo $contentname?>
			        </a>
			      </h4>
			    </div>
			    <div id="EditBlock" class="panel-collapse collapse in">
			      <div class="box-body">
			          <div class="row">
			            <div class="col-md-12">
			              <?php
			                echo $outsdata['adminoutput'];
			              ?>
			            </div>
			        </div>
			      </div>
			    </div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function(){
			var curmceadminposter=[];
			curmceadminposter['width']="100%";
			curmceadminposter['height']="500px";
			curmceadminposter['toolbar1']="undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect";
			curmceadminposter['toolbar2']="| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ";
			callTinyMCEInit("textarea#adminposter",curmceadminposter);
			var curmcethreeposter=[];
			callTinyMCEInit("textarea[id*=postersmallthree]",curmcethreeposter);
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
		});
	</script>
</div>