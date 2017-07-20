<?php
	/**
	*	blogentries.php. 
	*	this file holds the views for creating/editting blogposts
	*	its has both the create sections and edit sections for the blogpageadvanced.php
	*	modules
	*	merged into one.
	*
	*	
	*/
	
	
	// first, we make sure key variables are made available to us
	if(isset($displaytype)||isset($_gdunitdisplaytype)){
		// unset($_SESSION['generalpagesdata']);
		// check to ensure the type of
		$_vcrt=strpos($viewtype, "_crt");
		$inimaxupload=ini_get('post_max_size');

		!isset($data)?$data=array():$data;	
		$localdatamap="";	

		// manage the datamap to be passed into called function
		if(isset($datamap)&&isset($contentgroupdatageneral['datamap'])){

			$data['single']['datamap']=$contentgroupdatageneral['datamap'];
			$localdatamap=$data['single']['datamap'];
		}

		// seperate data set for displaying only the editsection of blog entries
		// when particular requests are made with the following viewtypes
		if($viewtype=="blogplain_crt"){
			$viewdata="blogplain";
			$extra="";

			// blogtypeid
			if(isset($gd_dataoutput['blogtypeid'])&&
				$gd_dataoutput['blogtypeid']!==""){
				$qcat=$extra==""?"":" AND";
				$btidq=$gd_dataoutput['blogtypeid'];
				$data['blogtypeid']=$gd_dataoutput['blogtypeid'];
				// $extra.="$qcat blogtypeid='$btidq'";
			}
		
			// blogcategoryid
			if(isset($gd_dataoutput['blogcategoryid'])&&
				$gd_dataoutput['blogcategoryid']!==""){
				$qcat=$extra==""?"":" AND";
				$bcidq=$gd_dataoutput['blogcategoryid'];
				$extra.="$qcat blogcatid='$bcidq'";
			}
		
			// title
			if(isset($gd_dataoutput['title'])&&
				$gd_dataoutput['title']!==""){
				$qcat=$extra==""?"":" AND";
				$title=$gd_dataoutput['title'];
				$extra.="$qcat title LIKE '%$title%'";
			}

			// status
			if(isset($gd_dataoutput['status'])&&
				$gd_dataoutput['status']!==""){
				$qcat=$extra==""?"":" AND";
				$status=$gd_dataoutput['status'];
				$extra.="$qcat status='$status'";
			}

			// entryrangestart
			// entryrangeend
			if((isset($gd_dataoutput['entryrangestart'])&&
				$gd_dataoutput['entryrangestart']!=="")||
				(isset($gd_dataoutput['entryrangeend'])&&
				$gd_dataoutput['entryrangeend']!=="")){
				$qcat=$extra==""?"":" AND";
				// $col handles column name values in the event a search is being done 
				// on a scheduled item.
				$col=isset($gd_dataoutput['viewextra'])&&
				$gd_dataoutput['viewextra']=="blogscheduled_crt"?"postperiod":"date";
				$startdate="";
				$enddate="";
				if($gd_dataoutput['entryrangestart']!==""){
					$startdate=$gd_dataoutput['entryrangestart'];
				}
				if($gd_dataoutput['entryrangeend']!==""){
					$enddate=$gd_dataoutput['entryrangeend'];
				}
				if($enddate!==""&&$startdate!==""){
					// perform date comparison and reassignment
					$datetime1 = new DateTime("$startdate"); 
					$datetime2 = new DateTime("$enddate"); 
					if($datetime1<$datetime2){
						$ers="$qcat $col>='$startdate'";
						$ere="AND $col<='$enddate'";
					}else{
						$ers="$qcat $col<='$startdate'";
						$ere="AND $col>='$enddate'";
					}
				}else{
					$ers=$startdate!==""?"$qcat $col>='$startdate'":"";
					$ere=$enddate!==""?"$qcat $col<='$enddate'":"";
				}
				$extra.="$ers $ere";
			}

			// entrytype
			if(isset($gd_dataoutput['entrytype'])&&
				$gd_dataoutput['entrytype']!==""){
				$qcat=$extra==""?"":" AND";
				$entrytype=$gd_dataoutput['entrytype'];
				$extra.="$qcat blogentrytype='$entrytype'";
			}

			if(isset($gd_dataoutput['viewextra'])){
				$ve=$gd_dataoutput['viewextra'];
				$qcat=$extra==""?"":" AND";
				if($ve=="blogscheduled_crt"){
					$extra.="$qcat scheduledpost='yes'";

				}else if($ve=="blogfeatured_crt"){
					$extra.="$qcat featuredpost='on'";

				}
			}
			if((isset($ve)&&$ve!=="blogscheduled_crt")||!isset($ve)){
				$qcat=$extra==""?"":" AND";
				$extra.=" $qcat (scheduledpost='no' OR scheduledpost='')";	
			}
			
			$_vcrt=-1;
			$data['queryextra']=$extra;
			$data["single"]["type"]="blockdeeprun";
			$outsdata=getAllBlogEntries('admin','','blockdeeprun',$data);
			$newh="hidden";
			$editin="in";
			$fhtitle="";
			echo json_encode(array("success"=>"true","qt"=>$outsdata['cqtdata'],
				"msg"=>"Transaction Successful",
				"dump"=>"",
				"catdata"=>$outsdata['adminoutput']));
		}else{
			// $viewdata="viewblogposts";

		}
		
		// end
		// initialise content variables for the form below 
		$newin="in";
		$editin="";	
		$newh="";
		$edith="";
		if($viewtype=="create"||(($_vcrt===true||$_vcrt>0||$_vcrt==1)&&$_vcrt!==-1)){
			// create data content array
			$ctype=$_gdunit_viewtype;
			$fhtitle="Blog Post";
						

			$data['queryorder']="ORDER by id DESC";
			// set the form type for the edit section
			$data['single']['formtruetype']="edit_".$formtruetype;

			// check if there are currently entries first and prepare the 
			// edit table section

			$outs=getAllBlogTypes("viewer","all");
			// var_dump($data);
			// variable for setting the viewdata on the edit section
			// operations fields, this value here allows a seperate kind of request
			// be made to this file providing an extra set of functions for 
			// manipulating data results to be presented
			$viewdata="blogplain";

			if($viewtype=="blogfeatured_crt"){
				$data['queryextra']=" featuredpost='on' AND 
				(scheduledpost='no' OR scheduledpost='')";
				$outsdata=getAllBlogEntries('admin','','',$data);
				// $viewdata="blogfeat";
				$newh="hidden";
				$editin="in";
				$fhtitle="Featured Blog Posts";
			}else if($viewtype=="blogscheduled_crt"){
				$data['queryextra']=" scheduledpost='yes'";
				$outsdata=getAllBlogEntries('admin','','',$data);
				// $viewdata="blogschedule";
				$newh="hidden";
				$fhtitle="Scheduled Blog Posts";
				$editin="in";
			}else if($viewtype=="newpost_crt"){
				// $viewdata="blogschedule";
				$newh="";
				$edith="hidden";
				$newin="in";
				$editin="";
			}else if($viewtype=="editpost_crt"){
				// $viewdata="blogschedule";
				$data["single"]["type"]="blockdeeprun";
				$data['queryextra']=" (scheduledpost='no' OR scheduledpost='')";
				$outsdata=getAllBlogEntries('admin','','blockdeeprun',$data);
				$newh="hidden";
				$newin="";
				$edith="";
				$editin="in";
			}else{
				$data["single"]["type"]="blockdeeprun";
				$data['queryextra']=" (scheduledpost='no' OR scheduledpost='')";
				$outsdata=getAllBlogEntries('admin','','blockdeeprun',$data);
			}

			$lastcode="";
			if($outsdata['numrows']>0){
				// entries are available
				$editin="in";
				$newin="";
				
			}
			// check for the last code entry data value

?>
<div class="box">
	<div class="box-body">
	    <div class="box-group" id="generaldataaccordion">
	    	<?php
	    		if($newh==""){
	    	?>
			<div class="panel box overflowhidden box-primary">
			    <div class="box-header with-border">
			        <h4 class="box-title">
			          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#NewPageManagerBlock">
			            <i class="fa fa-sliders"></i> Create <?php echo $fhtitle;?>
			          </a>
			        </h4>
			    </div>
			    <div id="NewPageManagerBlock" class="panel-collapse collapse <?php echo $newin;?>">
			        <div class="row">
			            <form name="<?php echo $formtruetype;?>" method="POST" data-type="create" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/basicsignup.php">
							<input type="hidden" name="entryvariant" value="<?php echo $variant;?>"/>

	            			<div class="col-md-12">
                            	<div class="form-group">
									<label>Choose a Blog Type</label>
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-file-text-o"></i>
										</div>
										<select name="blogtypeid" class="form-control">
											<?php echo $outs['selectiondata'];?>
										</select>
									
									</div>
                            	</div>
							</div>
							<div class="col-md-4" data-name="categoryselection">
                            	<div class="form-group">
									<label>Choose a category</label>
									<div class="input-group select2-bootstrap-prepend
									select2-bootstrap-append">
										<div class="input-group-addon">
											<i class="fa fa-file-text-o"></i>
										</div>
										<select name="blogcategoryid" 
										data-name="select2plain"
										class="form-control">
											<option value=""
											>--Choose A Blog Type First--</option>
										</select>
										<div class="input-group-addon rel">
	                                        <i class="fa fa-database"></i>
	                                        <img src="<?php echo $host_addr;?>images/loading.gif" class=" loadermask loadermini _igloader _upindex hidden"/>
	                                    </div>
									</div>
                            	</div>
							</div>
							<div class="col-md-4" data-name="comments">
                            	<div class="form-group">
									<label>Comments<small>Enable/Disable Comments for this post.</small></label>
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-comments"></i>
										</div>
										<select name="commentsonoff" class="form-control">
											<option value="on">On</option>
											<option value="on">Off</option>
										</select>
									</div>
                            	</div>
							</div>
							<div class="col-md-4" data-name="featured">
                            	<div class="form-group">
									<label>Mark as Featured Post?</label>
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-newspaper-o"></i>
										</div>
										<select name="featured" class="form-control">
											<option value="off">--choose--</option>
											<option value="on">Yes</option>
										</select>
									</div>
                            	</div>
							</div>	
							<div class="col-sm-6"> 
                                <div class="form-group">
                                    <label>Password Lock this Post? 
                                    	<small>Passworded posts are only available to blog subscribers</small>
                                    </label>
                                    <div class="input-group">
                                      	<div class="input-group-addon">
                                        	<i class="fa fa-key"></i>
	                                    </div>
	                                    <select name="pwrdd" class="form-control">
                                      		<option value="">No</option>
                                      		<option value="yes">Yes</option>
                                      	</select>
	                                </div><!-- /.input group -->
                              	</div><!-- /.form group -->
                            </div>
                            <div class="col-sm-6 portfolio-pwrd hidden"> 
                                <div class="form-group">
                                    <label>Password</label>
                                    <div class="input-group">
	                                    <div class="input-group-addon">
	                                        <i class="fa fa-lock"></i>
	                                    </div>
	                                    <input type="password" data-sa="true" data-type="password" class="form-control" name="pwrd" Placeholder="password"/>
	                                    <div class="input-group-addon pshow" title="Show Password">
							                <i class="fa fa-eye-slash"></i>
							            </div>
	                                </div><!-- /.input group -->
                              	</div><!-- /.form group -->
                            </div>
                            <div class="col-md-12" data-name="blogentry">
                            	<div class="form-group">
									<label>Tags
										<small>Short comma seperated list of 
											words describing current post.</small>
									</label>
									<textarea name="tags" 
									 Placeholder="Tags for this post" 
									class="form-control"></textarea>	
                            	</div>
							</div>
                            <div class="col-md-12" data-name="seosection">
								<div class="col-md-6">
	                            	<div class="form-group">
										<label>SEO Keywords
											<small>Exhaustive comma seperated list of keywords related 
												to this post
											</small>
										</label>
										<textarea name="seokeywords" 
										Placeholder="List keywords here" 
										class="form-control"></textarea>
	                            	</div>
								</div>
								<div class="col-md-6">
	                            	<div class="form-group">
										<label>SEO Description
											<small>Short and conscise description for post content
											</small>
										</label>
										<textarea name="seodescription" data-wMax="160"
										data-wMax-type="length" 
										data-wMax-fname="<?php echo $formtruetype;?>"
										Placeholder="Put something that interests possible readers here." 
										class="form-control"></textarea>
										
	                            	</div>
								</div>
							</div>

							<div class="col-md-12 emu-row" data-name="schedulesection">
								<div class="col-md-6">
						                <div class="form-group">
						                  <label>Schedule Status
						                  		<small>putting this on disables the post 
						                  			until the time specified is reached
						                  		</small>
						                  </label>
						                  <div class="input-group">
						                    <div class="input-group-addon">
						                      <i class="fa fa-clock-o"></i>
						                    </div>
						                    <select name="schedulestatus" class="form-control">
												<option value="no">--Choose--</option>
												<option value="yes">Yes</option>
											</select>
						                  </div><!-- /.input group -->
						                </div><!-- /.form group -->
				                    <!-- </div> -->
					            </div>
								<div class="col-md-6 scheduled hidden">
									<!-- Date range -->
					                <div class="form-group">
					                    <label>Post Date(Make sure it is a future date or time):</label>
					                    <div class="input-group">
					                      <div class="input-group-addon">
					                        <i class="fa fa-calendar"></i>
					                      </div>
					                      <input type="text" name="scheduledate" data-datetimepickerf="true" class="form-control" />
					                    </div><!-- /.input group -->
					                </div><!-- /.form group -->	
								</div>
							</div>								

							<div class="col-md-4" data-name="blogentrytype">
                            	<div class="form-group">
									<label>Blog Entry Type</label>
									<div class="input-group select2-bootstrap-prepend">
										<div class="input-group-addon">
											<i class="fa fa-file-text-o"></i>
										</div>
										<select name="blogentrytype" 
										data-name="select2plain"
										class="form-control">
											<option value="normal">Normal</option>
											<option value="gallery">Gallery</option>
											<option value="banner">Banner</option>
											<option value="video">Video</option>
											<option value="audio">Audio</option>
											<option value="poll">Poll</option>
										</select>
									</div>
                            	</div>
							</div>
							<div class="col-md-4" data-name="coverphoto">
                            	<div class="form-group">
									<label>Post Cover Image.</label>
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-file-image-o"></i>
										</div>
										<input type="file" placeholder="Choose image" 
										name="profpic" class="form-control"/>
										
									</div>
                            	</div>
							</div>

							<div class="col-md-12" data-name="title">
                            	<div class="form-group">
									<label>Title</label>
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-file-image-o"></i>
										</div>
										<input type="text" placeholder="Post title" 
										name="title" class="form-control"/>
									</div>
                            	</div>
							</div>

							<div class="col-md-12" data-name="bannerpicentry">
                            	<div class="form-group">
									<label>Post banner Image.</label>
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-file-image-o"></i>
										</div>
										<input type="file" data-rnd="true" data-rnd-type="image" placeholder="Choose image" 
										name="bannerpic" class="form-control"/>
										
									</div>
									<div class="col-md-12 hidden" 
										data-rndpoint="bannerpic">

									</div>
                            	</div>
							</div>

							<!-- Start gallery entry section accordion -->
                        	<div class="box-group top_pad bloggallery high_bottom_margin " data-name="galleryentry" id="contentaccordion">
								<div class="panel box box-primary overflowhidden">
							    	<div class="box-header with-border">
								        <h4 class="box-title">
								          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock_group2">
								            <i class="fa fa-file-image-o"></i>
								            	Gallery Images
								          </a>
								        </h4>
							    	</div>
							      	<div id="headBlock_group2" class="panel-collapse collapse in">
			                            <div class="col-md-12 bloggallery-field-hold ">
			                            	<input type="hidden" name="bloggallerycount" class="form-control" value="1" data-counter="true"/>
				                        	<h3>Maximum of 10 images at a time</h3>
				                        	<div class="col-md-3 multi_content_hold" data-type="triggerprogenitor" data-cid="1" data-name="bloggallery">
				                        		<h4 class="multi_content_countlabels">Gallery (Entry 1)</h4>
				                        		<div class="col-sm-12">
				                        			<div class="form-group">
							                            <label>Image</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="file" class="form-control" name="galimage1" placeholder="Choose file">
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
							                              <input type="text" class="form-control" name="caption1" placeholder="Provide Caption text">
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
							                              <textarea class="form-control" rows="3" name="details1" placeholder="Give details if any"></textarea>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        	</div>
				                        	<div name="bloggalleryentrypoint" data-marker="true"></div>
				                        	<input name="bloggallerydatamap" type="hidden" data-map="true" value="galimage-:-input<|>
				                        	caption-:-input<|>
				                        	details-:-textarea">
				                        	<a href="##" class="generic_addcontent_trigger" 
				                        		data-type="triggerformaddlib" 
				                        		data-name="bloggallerycount_addlink" 
				                        		data-i-type="" 
				                        		data-limit="10"> 
				                        		<i class="fa fa-plus"></i>Add More?
				                        	</a>
			                            </div>
		                        	</div>
                        		</div>
                        	</div>		
                        	<!-- end edit section accordion -->

                        	<!-- Start video entry section accordion -->
                        	<div class="box-group top_pad blogvideos high_bottom_margin " data-name="videosection" id="contentaccordion">
								<div class="panel box box-primary overflowhidden">
							    	<div class="box-header with-border">
								        <h4 class="box-title">
								          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock_group4">
								            <i class="fa fa-film"></i>
								            	Video Entries
								          </a>
								        </h4>
							    	</div>
							      	<div id="headBlock_group4" class="panel-collapse collapse in">
			                            <div class="col-md-12 blogvideo-field-hold ">
			                            	<input type="hidden" name="blogvideocount" class="form-control" value="1" data-counter="true"/>
				                        	<!-- <h3>Maximum of 1 video at a time</h3> -->
				                        	<div class="col-md-12 multi_content_hold" data-type="triggerprogenitor" data-cid="1" data-name="blogvideo">
				                        		<h4 class="multi_content_countlabels">Video </h4>
				                        		<div class="col-sm-12">
				                        			<div class="form-group">
							                            <label>Entry Type</label>
							                            <div class="input-group">
							                              	<div class="input-group-addon">
							                                	<i class="fa fa-file-text"></i>
							                              	</div>
							                              	<select name="videotype" class="form-control">
					                                      		<option value="local">Local</option>
					                                      		<option value="embed">Embed</option>
					                                      	</select>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-3 blogvideolocal">
				                        			<div class="form-group">
							                            <label>Video WEBM</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="file" class="form-control" name="videowebm" placeholder="Choose file">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-3 blogvideolocal">
				                        			<div class="form-group">
							                            <label>Video FLV</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="file" class="form-control" name="videoflv" placeholder="Choose File">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-3 blogvideolocal">
				                        			<div class="form-group">
							                            <label>Video 3GP</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="file" class="form-control" name="video3gp" placeholder="">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-3 blogvideolocal">
				                        			<div class="form-group">
							                            <label>Video MP4</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="file" class="form-control" name="videomp4" placeholder="">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-12 blogvideolocal">
				                        			<div class="form-group">
							                            <label>Video Caption</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="text" class="form-control" name="videocaption" placeholder="Specify a caption for the video">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-9 blogvideoembed hidden">
				                        			<div class="form-group">
							                            <label>Embed</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <textarea class="form-control" rows="3" name="videoembed" placeholder="Embed Code"></textarea>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        	</div>
				                        	<div name="blogvideoentrypoint" data-marker="true"></div>
				                        	<input name="blogvideodatamap" type="hidden" data-map="true" value="videotype-:-select<|>
				                        	videowebm-:-input<|>
				                        	videoflv-:-input<|>
				                        	video3gp-:-input<|>
				                        	videocaption-:-input<|>
				                        	videodetails-:-input">
				                        	<!-- <a href="##" class="generic_addcontent_trigger" 
				                        		data-type="triggerformaddlib" 
				                        		data-name="blogvideocount_addlink" 
				                        		data-i-type="" 
				                        		data-limit="10"> 
				                        		<i class="fa fa-plus"></i>Add More?
				                        	</a> -->
			                            </div>
		                        	</div>
                        		</div>
                        	</div>		
                        	<!-- end edit section accordion -->

                        	<!-- Start audio entry section accordion -->
                        	<div class="box-group top_pad blogaudio high_bottom_margin " data-name="audiosection" id="contentaccordion">
								<div class="panel box box-primary overflowhidden">
							    	<div class="box-header with-border">
								        <h4 class="box-title">
								          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock_group5">
								            <i class="fa fa-volume-up"></i>
								            	Audio Entry
								          </a>
								        </h4>
							    	</div>
							      	<div id="headBlock_group5" class="panel-collapse collapse in">
			                            <div class="col-md-12 blogaudio-field-hold ">
			                            	<input type="hidden" name="blogaudiocount" class="form-control" value="1" data-counter="true"/>
				                        	<!-- <h3>Maximum of 1 Audio entry at a time</h3> -->
				                        	<div class="col-md-12 multi_content_hold" data-type="triggerprogenitor" data-cid="1" data-name="blogaudio">
				                        		<h4 class="multi_content_countlabels">Audio</h4>
				                        		<div class="col-sm-4">
				                        			<div class="form-group">
							                            <label>Entry Type</label>
							                            <div class="input-group">
							                              	<div class="input-group-addon">
							                                	<i class="fa fa-file-text"></i>
							                              	</div>
							                              	<select name="audiotype"  class="form-control">
					                                      		<option value="">--Choose--</option>
					                                      		<option value="local">Local</option>
					                                      		<option value="embed">Embed</option>
					                                      	</select>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-4 blogaudiolocal">
				                        			<div class="form-group">
							                            <label>Audio File</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="file" class="form-control" name="audio" placeholder="">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-4 blogaudioembed hidden">
				                        			<div class="form-group">
							                            <label>Embed</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <textarea class="form-control" rows="3" name="audioembed" placeholder="Embed Code"></textarea>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-4 blogaudiolocal">
				                        			<div class="form-group">
							                            <label>Audio Caption</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="text" class="form-control" name="audiocaption" placeholder="Specify a caption for the audio file">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        	</div>
				                        	<div name="blogaudioentrypoint" data-marker="true"></div>
				                        	<input name="blogaudiodatamap" type="hidden" data-map="true" value="audiotype-:-select<|>
				                        	audio-:-input<|>
				                        	audiocaption-:-input<|>
				                        	audioembed-:-input">
				                        	<!-- <a href="##" class="generic_addcontent_trigger" 
				                        		data-type="triggerformaddlib" 
				                        		data-name="portvideocount_addlink" 
				                        		data-i-type="" 
				                        		data-limit="10"> 
				                        		<i class="fa fa-plus"></i>Add More?
				                        	</a> -->
			                            </div>
		                        	</div>
                        		</div>
                        	</div>		
                        	<!-- end edit section accordion -->
							<div class="col-md-12" data-name="introparagraph">
                            	<div class="form-group">
									<label><h4>Introductory Paragraph</h4>
										<small>Short Description for preview on the blog</small>
									</label>
									<textarea name="introparagraph" 
									id="postersmalltwo" Placeholder="Introductory Paragraph" 
									class="form-control" data-mce="true"></textarea>
									
                            	</div>
							</div>
							<div class="col-md-12" data-name="blogentry">
                            	<div class="form-group">
									<label><h4>Main Blog Post</h4></label>
									<textarea name="blogentry" 
									id="adminposter" Placeholder="The blog post" 
									class="form-control" data-mce="true"></textarea>
									
                            	</div>
							</div>

                            <input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>
	            			<input type="hidden" name="fdgen" value="32M<?php //echo $inimaxupload;?>" data-fdgen="true"/>
                            <input type="hidden" name="extraformdata" value="blogtypeid-:-select<|>
                            blogcategoryid-:-select<|>
                            schedulestatus-:-select<|>
                            scheduledate-:-input-:-[group-|-schedulestatus-|-select-|-yes]<|>
                            blogentrytype-:-select<|>
	                        egroup|data-:-[bloggallerycount>|<
		                    galimage-|-input|image>|<
		                    caption-|-input>|<
		                    details-|-textarea]-:-groupfall[1-2,2-1,1-3,1]-:-[single-|-blogentrytype-|-select-|-gallery-|-galimage]<|>
                            bannerpic-:-input-:-[group-|-blogentrytype-|-select-|-banner]<|>
                            audiotype-:-select-:-[group-|-blogentrytype-|-select-|-audio]<|>
                            audio-:-input|audio|mp3-:-[group-|-blogentrytype-|-select-|-audio-|-audiotype-|-select-|-local]<|>
                            audioembed-:-textarea-:-[group-|-blogentrytype-|-select-|-audio-|-audiotype-|-select-|-embed]<|>
                            videotype-:-select-:-[group-|-blogentrytype-|-select-|-video]<|>
                            videowebm-:-input|video|webm-:-[group-|-blogentrytype-|-select-|-video-|-videotype-|-select-|-local-|-videoflv-|-input-|-*null*-|-video3gp-|-input-|-*null*-|-videomp4-|-input-|-*null*]<|>
                            videoflv-:-input|video|flv<|>
                            video3gp-:-input|video|3gp<|>
                            videomp4-:-input|video|mp4<|>
                            videoembed-:-textarea-:-[group-|-blogentrytype-|-select-|-video-|-videotype-|-select-|-embed]<|>
                            profpic-:-input|image<|>
                            title-:-input<|>
                            introparagraph-:-textarea<|>
                            blogentry-:-textarea<|>
                            pwrdd-:-select<|>
	                      	pwrd-:-input-:-[group-|-pwrdd-|-select-|-yes]"/>

		                    <input type="hidden" name="errormap" value="blogtypeid-:-Please specify the Blog you want to make a post to<|>
                            blogcategoryid-:-Please choose a category, if none is available and you have chosen a Blog Type, check and make sure categories have been created under it.<|>
                            schedulestatus-:-NA<|>
                            scheduledate-:-Choose a date and time when this post will be
                            available to your viewers<|>
                            blogentrytype-:-NA<|>
	                        egroup|data-:-[Please provide a valid image file>|<
		                    Specify the caption text>|<
		                    NA]<|>
                            bannerpic-:-Please choose an image for the banner 
                            you want displayed on the blog<|>
                            
                            audiotype-:-Specify the type of audio entry you want to make
                            \&#34;Local\&#34; means that your audio is streaming directly 
                            from a file on your server and<br>
                            \&#34;Embed\&#34; means you are using an embed code from a 
                            audio service provider like soundcloud<|>
                            
                            audio-:-Choose a valid mp3 file.<|>
                            audioembed-:-Provide an audio embed code<|>
                            
                            videotype-:-Specify the type of video entry you want to make
                            \&#34;Local\&#34; means that your video is streaming directly 
                            from a file on your server and<br>
                            \&#34;Embed\&#34; means you are using an embed code from a 
                            video service provider like youtube or vimeo<|>
                            
                            videowebm-:-Please provide a video, you can 
                            select multiple types of the same video using 
                            the fields provided but .web is most 
                            preferred <|>
                            videoflv-:-NA<|>
                            video3gp-:-NA<|>
                            videomp4-:-NA<|>
                            videoembed-:-Provide the embed code for this video. You can get this from the video service provider<|>
                            profpic-:-NA<|>
                            title-:-Provide the title for this post<|>
                            introparagraph-:-Give a short introduction to the 
                            post that would encourage readers to dig further, 
                            it can be an excerpt from the main post or just 
                            something different<|>
                            blogentry-:-Provide the complete post<|>
                            pwrdd-:-NA<|>
	                      	pwrd-:-Please provide a password"/>
		                    
			                <div class="col-md-12 clearboth">
				                <div class="box-footer">
				                    <input type="button" class="btn btn-danger" name="createentry" data-formdata="<?php echo $formtruetype;?>" onclick="submitCustom('<?php echo $formtruetype;?>','complete')" value="Create/Update"/>
				                </div>
				            </div>
		            	</form>	
			        </div>
			    </div>
			</div>
			<?php
	    		}
	    	?>
			<?php
	    		if($edith==""){
	    	?>
			<div class="panel box overflowhidden box-primary">
                <div class="box-header with-border">
                  <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#EditBlock">
                      <i class="fa fa-gear fa-spin"></i> Edit <?php echo $fhtitle;?>
                    </a>
                  </h4>
                </div>
                <div id="EditBlock" class="panel-collapse collapse <?php echo $editin;?>">
                  	<div class="box-body">
                      	<div class="row">
                      		<?php
								// create the unique viewdata variable for
								// handling entries into the database
                      			$miniviewdata="";
                      			$clc="4";
								if($viewtype=="blogscheduled_crt"||
									$viewtype=="blogfeatured_crt"){
									$viewtype=="blogscheduled_crt"?$clc="6":$clc="4";
									$miniviewdata='
										<input type="hidden" data-crt="true" name="viewextra" 
										value="'.$viewtype.'"/>
										
									';
								}

							?>
	                        <div class="col-md-12 render-field-hold" name="<?php echo $viewdata;?>">
									<input type="hidden" name="datamap" data-crt="true" value='<?php echo $localdatamap;?>'/>
									<?php
										echo $miniviewdata;
									?>

		                        	<div class="col-sm-<?php echo $clc;?>"> 
		                                <div class="form-group">
		                                    <label>Change Blog Type <small>Default view is the first blog type created</small></label>
		                                    <div class="input-group select2-bootstrap-prepend">
			                                      <div class="input-group-addon">
			                                        <i class="fa fa-file-text"></i>
			                                      </div>
												  	<select name="blogtypeid" data-crt="true" data-name="select2plain" class="form-control">
														<?php echo $outs['selectiondata'];?>
													</select>
			                                </div><!-- /.input group -->
		                              	</div><!-- /.form group -->
		                            </div>
		                            <div class="col-md-<?php echo $clc;?>">
		                            	<div class="form-group">
											<label>Choose a category</label>
											<div class="input-group select2-bootstrap-prepend select2-bootstrap-append">
												<div class="input-group-addon">
													<i class="fa fa-file-text-o"></i>
												</div>
												<select name="blogcategoryid" 
												data-name="select2plain"
												class="form-control" data-crt="true">
													<option value=""
													>--Choose A Blog Type First--</option>
												</select>
												<div class="input-group-addon rel">
			                                        <i class="fa fa-database"></i>
			                                        <img src="<?php echo $host_addr;?>images/loading.gif" class=" loadermask loadermini _igloader _upindex hidden"/>
			                                    </div>
											</div>
		                            	</div>
									</div>
									<?php
										if($viewtype!=="blogscheduled_crt"){
									?>
									<div class="col-md-4">
				                    	<div class="form-group">
					                        <label>Status</label>
					                        <div class="input-group">
				                                <div class="input-group-addon">
				                                    <i class="fa fa-exclamation-triangle 
				                                    color-red"></i>
				                                </div>
						                        <select name="status" data-crt="true" 
						                        class="form-control">
						                        	<option value="">--Choose--</option>
						                        	<option value="active">Active</option>
						                        	<option value="inactive">Inactive</option>
										  	    </select>
										  	</div>
									  	</div>
				                    </div>
									<?php
										}
									?>
									<div class="col-md-3">
		                            	<div class="form-group">
											<label>Post Title</label>
											<div class="input-group">
												<div class="input-group-addon">
													<i class="fa fa-file-text-o"></i>
												</div>
												<input name="title" 
												placeholder="Search by Post title" 
												class="form-control" data-crt="true"/>
													
											</div>
		                            	</div>
									</div>
									<div class="col-md-3">
		                            	<div class="form-group">
											<label>Date/Time Range (Start)</label>
											<div class="input-group select2-bootstrap-prepend">
												<div class="input-group-addon">
													<i class="fa fa-file-text-o"></i>
												</div>
												<input name="entryrangestart"
												data-datetimepicker 
												placeholder="Search Start date" 
												class="form-control" data-crt="true"/>
													
											</div>
		                            	</div>
									</div>
									<div class="col-md-3">
		                            	<div class="form-group">
											<label>Date/Time Range (End)</label>
											<div class="input-group select2-bootstrap-prepend">
												<div class="input-group-addon">
													<i class="fa fa-file-text-o"></i>
												</div>
												<input name="entryrangeend"
												data-datetimepicker 
												placeholder="Search End date" 
												class="form-control" data-crt="true"/>
													
											</div>
		                            	</div>
									</div>
									<div class="col-md-3">
		                            	<div class="form-group">
											<label>Entry Type</label>
											<div class="input-group select2-bootstrap-prepend">
												<div class="input-group-addon">
													<i class="fa fa-file-text-o"></i>
												</div>
												<select name="entrytype" 
												data-name="select2plain" data-crt="true"
												class="form-control">
													<option value=""
													>--Choose--</option>
													<option value="normal">Normal</option>
													<option value="gallery">Gallery</option>
													<option value="banner">Banner</option>
													<option value="video">Video</option>
													<option value="audio">Audio</option>
													<option value="poll">Poll</option>
												</select>
											</div>
		                            	</div>
									</div>
		                            <div class="col-md-12 clearboth">
						                <div class="box-footer margin-auto text-center">
						                    <input type="button" class="btn btn-danger" 
						                    data-type="submitcrt" name="<?php echo $viewdata?>" 
						                    value="Load/Refresh View"/>
						                </div>
						            </div>
		                        	<div class="col-md-12 renderpoint">
				                        <?php 
				                        	echo $outsdata['adminoutput'];
					                          
				                        ?>	
				                    </div>
									<div class="loadmask loadmask hidden">
	                                    <img src="<?php echo $host_addr;?>images/loading.gif" class="loadermini "/>
	                                </div>
	                    		</div>
                    	</div>
                  	</div>
                </div>
            </div>
            <?php
        		}
	    	?>
		</div>
	</div>
	<script>
		$(document).ready(function(){
			var curmceadminposter=[];
			curmceadminposter['width']="100%";
			curmceadminposter['height']="650px";
			curmceadminposter['toolbar1']="undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect";
			curmceadminposter['toolbar2']="| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ";
			callTinyMCEInit("textarea#adminposter",curmceadminposter);


			var curmcethreeposter=[];
			curmcethreeposter['width']="100%";

			curmcethreeposter['height']="300px";
			curmcethreeposter['toolbar2addon']=" | preview code ";
			callTinyMCEInit("textarea[id*=postersmalltwo]",curmcethreeposter);
			
			// init select2
			if($.fn.select2){
				$('select[data-name=select2plain]').select2({
				    theme: "bootstrap"
				});
				$('select[data-name=faselect]').select2({
				    theme: "bootstrap",
				    templateResult: faSelectTemplate
				});
			}
  			if($.fn.datepicker){
  				$('[data-datetimepicker]').datetimepicker({
			        format:"YYYY-MM-DD HH:mm",
			        keepOpen:true
			    })
			    // for disabling previous dates 
			    $('[data-datetimepickerf]').datetimepicker({
			        format:"YYYY-MM-DD HH:mm",
			        keepOpen:true,
			        minDate: moment(1, 'h')
			    });
			    $('[data-datepicker]').datetimepicker({
			        format:"YYYY-MM-DD",
			        keepOpen:true
			        // showClose:true
			        // debug:true
			    });
			    $('[data-datepickerf]').datetimepicker({
			        format:"YYYY-MM-DD",
			        keepOpen:true,
			        minDate: moment(1, 'h')
			        // showClose:true
			        // debug:true
			    });
			    $('[data-timepicker]').datetimepicker({
			        format:"HH:mm",
			        keepOpen:true
			    });
  			}
			
		});
	</script>
</div>
<?php
		}else if($viewtype=="edit"){
			// echo $viewtype;
			// var_dump($datamap);
			// parse current datamap[''];
			$cdmap=JSONtoPHP($datamap);
			$cmap=$cdmap['arrayoutput'];
			// var_dump($cmap);

			//check to see if the current map has the 'overriden' index and see if
			// the index value is 'true' meaning that the current query to be used
			// is not the defaule but one present in the initial datamap
			if(isset($cmap['overriden'])&&$cmap['overriden']=="true"){
				$rmd5=$cmap['rmd5'];
				// get the current data overriding queries from active session and
				// place the values into the current data array element
				$cdata=$_SESSION['generalpagesdata']["$rmd5"]['data'];
				$data=$cdata;
				// ensure the calling function knows which hash to call
				$data['rmd5']=$rmd5;
			}

			$formtruetype2="edit_$formtruetype2";
			$data['single']['formtruetype']=$formtruetype2;
			// var_dump($data);

			// echo $variant2;
			$dataset=getSingleBlogEntry($editid,"",$data);
			$row=$dataset;
			$blogentrytype=$dataset['blogentrytype'];
			$bannerhid="hidden"; // bannerlery  section
			$bannertotal="0"; // bannerlery  section total entries
			$galhid=""; // gallery  section
			$galtotal="0"; // gallery  section total entries
			$vidhid=""; // video  section
			$vidtotal="0"; // video  section total entries
			$audhid=""; // audio  section
			$audtotal="0"; // audio  section total entries
			$pwrdhid="hidden"; // password  section
			$schedulehid="hidden"; // schedule section
			
			if($blogentrytype=="gallery"){
				$galhid="fd";
			}else if($blogentrytype=="banner"){
				$bannerhid="fd";
			}else if($blogentrytype=="audio"){
				$audhid="fd";
			}else if($blogentrytype=="video"){
				$vidhid="fd"; // video  section
			}else if($blogentrytype=="poll"){

			}
			$vidtotal=$row['viddata']['total'];
			$audtotal=$row['audiodata']['total'];

			if($dataset['pwrdd']=="yes"){
				$pwrdhid="";
			}

			if($dataset['scheduledpost']=="yes"){
				$schedulehid="";
			}

			$totalscripts=$dataset['totalscripts'];
			$blog_comment_count=$dataset['admincommentcount'];
			$blog_comment_data_output=$dataset['admincomments'];
			$commentscripts="";
			if($dataset['commenttype']=="disqus"){
				include($host_tpathplain.'defaultsmodule.php');
				// handle disqus related info
				// set the scripts data to be loaded
				// set embed code to be loaded
				$blog_comment_data_output='<div id="disqus_thread"></div>';
				$blog_comment_count='<span class="disqus-comment-count" 
				data-disqus-url="'.$dataset['pagelink'].'"
				data-disqus-identifier="'.$dataset['id'].'_blog">';
				$curblogdata=& $dataset;
				// include the necessary file 
				include($host_tpathplain."modules/disqus/embed.php");
				// append the count script 
				include($host_tpathplain."modules/disqus/scripts/include.php");


			}
?>
			<!-- Edit section -->
			<div class="row">
		        <form name="<?php echo $formtruetype2;?>" method="POST" data-type="edit" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/edit.php">
					<input type="hidden" name="entryvariant" value="<?php echo $variant2;?>"/>

					<input type="hidden" name="entryid" value="<?php echo $editid;?>"/>

        			<div class="col-md-12">
                    	<div class="form-group">
							<label>Choose a Blog Type</label>
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-file-text-o"></i>
								</div>
								<input type="text" name="btypeplacebo" 
								value="<?php echo $row['blogtypedata']['name'];?>" 
								disabled class="form-control" />
								<input type="hidden" name="blogtypeid" 
								value="<?php echo $row['blogtypeid'];?>"/>
							
							</div>
                    	</div>
					</div>
					<div class="col-md-4" data-name="categoryselection">
                    	<div class="form-group">
							<label>Blog Category</label>
							<div class="input-group select2-bootstrap-prepend
							select2-bootstrap-append">
								<div class="input-group-addon">
									<i class="fa fa-file-text-o"></i>
								</div>
								<input type="text" name="becatplacebo" 
								value="<?php echo $row['blogcatdata']['catname'];?>" 
								disabled class="form-control" />
								<input type="hidden" name="blogcategoryid" 
								value="<?php echo $row['blogcatid'];?>"/>
							</div>
                    	</div>
					</div>
					<div class="col-md-4" data-name="comments">
                    	<div class="form-group">
							<label>Comments<small>Enable/Disable Comments for this post.</small></label>
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-comments"></i>
								</div>
								<select name="commentsonoff" class="form-control">
									<option value="on">On</option>
									<option value="on">Off</option>
								</select>
							</div>
                    	</div>
					</div>
					<div class="col-md-4" data-name="commenttype">
                    	<div class="form-group">
							<label>Comment Type
								<small>
									Change the comment handler for this post .
								</small>
							</label>
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-comment"></i>
								</div>
								<select name="commenttype" class="form-control">
									<option value="normal">System</option>
									<option value="disqus">
										Disqus(Make sure diqus hostname is provided under
										Pages > Default Data section)
									</option>
								</select>
							</div>
                    	</div>
					</div>
					<div class="col-md-4" data-name="featured">
                    	<div class="form-group">
							<label>Mark as Featured Post?</label>
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-newspaper-o"></i>
								</div>
								<select name="featured" class="form-control">
									<option value="off">--choose--</option>
									<option value="on">Yes</option>
								</select>
							</div>
                    	</div>
					</div>	
					<div class="col-sm-6"> 
                        <div class="form-group">
                            <label>Password Lock this Post? 
                            	<small>Passworded posts are only available to blog subscribers</small>
                            </label>
                            <div class="input-group">
                              	<div class="input-group-addon">
                                	<i class="fa fa-key"></i>
                                </div>
                                <select name="pwrdd" class="form-control">
                              		<option value="">No</option>
                              		<option value="yes">Yes</option>
                              	</select>
                            </div><!-- /.input group -->
                      	</div><!-- /.form group -->
                    </div>
                    <div class="col-sm-6 portfolio-pwrd <?php echo $pwrdhid;?>"> 
                        <div class="form-group">
                            <label>Password</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-lock"></i>
                                </div>
                                <input type="password" data-sa="true" data-type="password" 
                                class="form-control" name="pwrd" Placeholder="password
                                value="<?php echo $row['pwrd'];?>""/>
                                <div class="input-group-addon pshow" title="Show Password">
					                <i class="fa fa-eye-slash"></i>
					            </div>
                            </div><!-- /.input group -->
                      	</div><!-- /.form group -->
                    </div>
                    <div class="col-md-12" data-name="tags">
                    	<div class="form-group">
							<label>Tags
								<small>Short comma seperated list of 
									words describing current post.</small>
							</label>
							<textarea name="tags" 
							 Placeholder="Tags for this post" 
							class="form-control"><?php echo $row['tags'];?></textarea>	
                    	</div>
					</div>
                    <div class="col-md-12" data-name="seosection">
						<div class="col-md-6">
                        	<div class="form-group">
								<label>SEO Keywords
									<small>Exhaustive comma seperated list of keywords related 
										to this post
									</small>
								</label>
								<textarea name="seokeywords" 
								Placeholder="List keywords here" 
								class="form-control"
								><?php echo $row['seometakeywords'];?></textarea>
                        	</div>
						</div>
						<div class="col-md-6">
                        	<div class="form-group">
								<label>SEO Description
									<small>Short and conscise description for post content
									</small>
								</label>
								<textarea name="seodescription" data-wMax="160"
								data-wMax-type="length" 
								data-wMax-fname="<?php echo $formtruetype;?>"
								Placeholder="Put something that interests possible readers here." 
								class="form-control"><?php echo $row['seometadescription'];?></textarea>
								
                        	</div>
						</div>
					</div>

					<div class="col-md-12 emu-row <?php echo $schedulehid;?>" data-name="schedulesection">
						<div class="col-md-6">
				                <div class="form-group">
				                  <label>Schedule Status
				                  		<small>putting this on disables the post 
				                  			until the time specified is reached
				                  		</small>
				                  </label>
				                  <div class="input-group">
				                    <div class="input-group-addon">
				                      <i class="fa fa-clock-o"></i>
				                    </div>
				                    <select name="schedulestatus" class="form-control">
										<option value="">--Choose--</option>
										<option value="publish">Publish Now (Choosing 
											this activates this post after 
											submission.)
										</option>
								        <!-- <option value="no">No</option> -->
							          	<option value="yes">Yes</option>
									</select>
				                  </div><!-- /.input group -->
				                </div><!-- /.form group -->
		                    <!-- </div> -->
			            </div>
						<div class="col-md-6 scheduled <?php echo $schedulehid;?>">
							<!-- Date range -->
			                <div class="form-group">
			                    <label>Change Post Date: <small>Current:"<?php 
			                    $tp=toPeriod($row['postperiod']);
			                    echo $tp['poaddtext'];?>"</small>
			                    	<small><?php echo $row['postperiod']?></small></label>
			                    <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-calendar"></i>
			                      </div>
			                      <input type="text" name="scheduledate" 
			                      data-datetimepicker="true" class="form-control" 
			                      value="<?php echo $row['postperiod'];?>"/>
			                    </div><!-- /.input group -->
			                </div><!-- /.form group -->	
						</div>
					</div>								

					<div class="col-md-4" data-name="blogentrytype">
                    	<div class="form-group">
							<label>Blog Entry Type</label>
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-file-text-o"></i>
								</div>
								<input type="text" name="betypeplacebo" 
								value="<?php echo $row['blogentrytype'];?>" disabled
								class="form-control" />
							</div>
                    	</div>
					</div>
					<div class="col-md-4" data-name="coverphoto">
                    	<div class="form-group">
							<label>Post Cover Image.</label>
							<?php 
            					if($row['profpicdata']['id']>0){
            						$coverdata=$row['profpicdata'];
            				?>
            					<div class="contentpreview _image">
	                            	<a href="<?php echo $coverdata['location'];?>" data-lightbox="general_coverimage" data-src="<?php echo $coverdata['location'];?>">
	                            		<img src="<?php echo $coverdata['thumbnail'];?>">
	                            	</a>
	                            </div>

	                            <input type="hidden" class="form-control" name="profpic_id" value="<?php echo $coverdata['id'];?>">
            				<?php 
            					}
            				?>
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-file-image-o"></i>
								</div>
								<input type="file" placeholder="Choose image" 
								name="profpic" class="form-control"/>
								
							</div>
                    	</div>
					</div>

					<div class="col-md-12" data-name="title">
                    	<div class="form-group">
							<label>Title</label>
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-file-image-o"></i>
								</div>
								<input type="text" placeholder="Post title" 
								name="title" value="<?php echo $row['title'];?>"
								class="form-control"/>
							</div>
                    	</div>
					</div>

					<div class="col-md-12 <?php echo $bannerhid;?>" 
						data-name="bannerpicentry">
                    	<div class="form-group">
							<label>Post banner Image.</label>
								<?php 
            					if($row['bannerdata']['total']>0){
            						$bannerdata=$row['bannerdata'][0];
            				?>
            					<div class="contentpreview _image">
	                            	<a href="<?php echo $bannerdata['location'];?>" data-lightbox="general_bannerimage" data-src="<?php echo $bannerdata['location'];?>">
	                            		<img src="<?php echo $bannerdata['location'];?>">
	                            	</a>
	                            </div>
	                            <input type="hidden" class="form-control" name="bannerpic_id" value="<?php echo $bannerdata['id'];?>">
							<?php 
            					}
            				?>	
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-file-image-o"></i>
								</div>
								<input type="file" data-rnd="true" data-rnd-type="image" placeholder="Choose image" 
								name="bannerpic" class="form-control"/>
							</div>
							<div class="col-md-12 hidden" 
								data-rndpoint="bannerpic">

							</div>
                    	</div>
					</div>

					<!-- Start gallery entry section accordion -->
                	<div class="box-group top_pad bloggallery high_bottom_margin <?php echo $galhid;?>" data-name="galleryentry" id="contentaccordion">
						<div class="panel box box-primary overflowhidden">
					    	<div class="box-header with-border">
						        <h4 class="box-title">
						          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock_group2edit">
						            <i class="fa fa-file-image-o"></i>
						            	Gallery Images (<?php echo $row['gallerydata']['total'];?>)
						          </a>
						        </h4>
					    	</div>
					      	<div id="headBlock_group2edit" class="panel-collapse collapse ">
					      		<div class="col-md-12 editbloggallery-field-hold ">
	                            	<?php 
	                					if($row['gallerydata']['total']>0){
	                				
	                						for($i=0;$i<$row['gallerydata']['total'];$i++){
	                							$t=$i+1;
	                							$gallerydata=$row['gallerydata'][$i];
	                							$datatitle="";
	                							if($gallerydata['caption']!==""){
	                								$datatitle.="<h4 class='galimgdetailshigh'>".$gallerydata['caption']."</h4>";
	                							}
	                							if($gallerydata['details']!==""){
	                								$datatitle.=" ".$gallerydata['details']."";
	                							}
	                				?>
	                				<div class="col-md-3"> 
	                					<div class="contentpreview _image">
	                						<div class="contentpreviewoptions">
		                        				<a href="##options" class="option">
		                        					<i class="fa fa-gear fa-spin" title="Edit"></i>
		                        				</a>
		                        			</div>
			                            	<a href="<?php echo $gallerydata['location'];?>" data-lightbox="general_galdata" 
			                            		data-src="<?php echo $gallerydata['location'];?>" 
			                            		data-title="">
			                            		<img src="<?php echo $gallerydata['thumbnail'];?>">
			                            	</a>
			                            </div>
				                        <div class="form-group">
				                            <label>Gallery Image <?php echo $t;?></label>
				                            <div class="input-group coptionpoint _hold">
			                					<div class="input-group-addon">
					                                <i class="fa fa-file-image-o"></i>
					                            </div>
					                            <input type="hidden" class="form-control" name="galimage<?php echo $t;?>_id" value="<?php echo $gallerydata['id'];?>">
					                            <input type="file" data-edittype="true" class="form-control" name="galimage<?php echo $t;?>" placeholder="Choose file">
				                            </div><!-- /.input group -->
				                      	</div><!-- /.form group -->

				                      	<div class="form-group coptionpoint _hold">
				                            <label>Caption</label>
				                            <div class="input-group">
				                              <div class="input-group-addon">
				                                <i class="fa fa-file-text"></i>
				                              </div>
				                              <input type="text" class="form-control" 
				                              name="caption<?php echo $t;?>" 
				                              value="<?php echo $gallerydata['caption'];?>" placeholder="Provide Caption text">
				                            </div><!-- /.input group -->
				                        </div><!-- /.form group -->
										
										<div class="form-group coptionpoint _hold">
				                            <label>Details</label>
				                            <div class="input-group">
					                            <div class="input-group-addon">
					                                <i class="fa fa-file-text"></i>
					                            </div>
					                            <textarea class="form-control" rows="3" 
					                            name="details<?php echo $t;?>" placeholder="Give details if any"
					                            	><?php echo $gallerydata['details'];?></textarea>
					                            <select class="form-control" 
					                            name="galimage<?php echo $t;?>_delete">
								                    <option value="">Delete this Entry?</option> 
								                    <option value="inactive">Yes</option> 
								                </select>
				                            </div><!-- /.input group -->
				                        </div><!-- /.form group -->
				                    </div>
	                				<?php 
	                						}
	                						
	                					}
	                					$ft=$row['gallerydata']['total']+1;
	                				?>
	                            </div>
	                            <div class="col-md-12 bloggallery-field-hold ">
	                            	<input type="hidden" name="bloggallerycount" class="form-control" value="<?php echo $ft;?>" data-counter="true"/>
		                        	<h3>Maximum of 10 images at a time</h3>
		                        	<div class="col-md-3 multi_content_hold" data-type="triggerprogenitor" data-cid="<?php echo $ft;?>" data-name="bloggallery">
		                        		<h4 class="multi_content_countlabels">Gallery (Entry <?php echo $ft;?>)</h4>
		                        		<div class="col-sm-12">
		                        			<div class="form-group">
					                            <label>Image</label>
					                            <div class="input-group">
					                              <div class="input-group-addon">
					                                <i class="fa fa-file-text"></i>
					                              </div>
					                              <input type="file" class="form-control" name="galimage<?php echo $ft;?>" placeholder="Choose file">
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
					                              <input type="text" class="form-control" name="caption<?php echo $ft;?>" placeholder="Provide Caption text">
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
					                              <textarea class="form-control" rows="3" name="details<?php echo $ft;?>" placeholder="Give details if any"></textarea>
					                            </div><!-- /.input group -->
					                        </div><!-- /.form group -->
		                        		</div>
		                        	</div>
		                        	<div name="bloggalleryentrypoint" data-marker="true"></div>
		                        	<input name="bloggallerydatamap" type="hidden" data-map="true" value="galimage-:-input<|>
		                        	caption-:-input<|>
		                        	details-:-textarea">
		                        	<a href="##" class="generic_addcontent_trigger" 
		                        		data-type="triggerformaddlib" 
		                        		data-name="bloggallerycount_addlink" 
		                        		data-i-type="" 
		                        		data-sentineltype="<?php echo $ft;?>" 
		                        		data-limit="10"> 
		                        		<i class="fa fa-plus"></i>Add More?
		                        	</a>
	                            </div>
                        	</div>
                		</div>
                	</div>		
                	<!-- end edit section accordion -->

                	<!-- Start video entry section accordion -->
                	<div class="box-group top_pad blogvideos high_bottom_margin <?php echo $vidhid;?>" data-name="videosection" id="contentaccordion">
						<div class="panel box box-primary overflowhidden">
					    	<div class="box-header with-border">
						        <h4 class="box-title">
						          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock_group4edit">
						            <i class="fa fa-film"></i>
						            	Video Entries
						          </a>
						        </h4>
					    	</div>
					      	<div id="headBlock_group4edit" class="panel-collapse collapse in">
	                            <?php 
                					if($row['viddata']['total']>0){
                				
                				?>
                            	<div class="col-md-12 editblogvideo-field-hold ">
                            		<!-- edit previous videos uploaded -->
                            	<?php 
                						for($i=0;$i<$row['viddata']['total'];$i++){
                							$t=$i+1;
                							$viddata=$row['viddata'][$i];
                							// check to see if the selected type is embeded or a local 
                							// video
                							$ehid="hidden";
                							$lhid="";
                							$vet="";
                							$sourcetotal="";
                							if($viddata['videowebm']!==""){
                								$sourcetotal.='<source src="'.$viddata['videowebm'].'" type="video/webm"/>';
                							}
                							if($viddata['video3gp']!==""){
                								$sourcetotal.='<source src="'.$viddata['video3gp'].'" type="video/3gp"/>';
                							}
                							if($viddata['videoflv']!==""){
                								$sourcetotal.='<source src="'.$viddata['videoflv'].'" type="video/flv"/>';
                							}
                							if($viddata['videomp4']!==""){
                								$sourcetotal.='<source src="'.$viddata['videomp4'].'" type="video/mp4"/>';
                							}
                							if($sourcetotal!==""){
			                					$vet='data-edittype="true"';
                							}
                							$vidout='<video preload="metadata" controls>
                										'.$sourcetotal.'
                									</video>';
                							if($viddata['disptype']=="embed"){
                								$ehid="";
	                							$lhid="hidden";
	                							$vidout=$viddata['videoembed'];
                							}
                							$cmap=json_encode($viddata);
                				?>
	                						<div class="col-md-12 multi_content_hold" data-type="triggerprogeny" data-cid="<?php echo $ft;?>">
		                        				<h4 class="multi_content_countlabels">Video Entry</h4>
				                        		<div class="col-sm-12 coptionpoint _hold">
				                        			<div class="form-group">
							                            <label>Entry Type</label>
							                            <div class="input-group">
							                              	<div class="input-group-addon">
							                                	<i class="fa fa-file-text"></i>
							                              	</div>
							                              	<select name="videotype" class="form-control">
					                                      		<option value="">--choose--</option>
					                                      		<option value="local">Local</option>
					                                      		<option value="embed">Embed</option>
					                                      	</select>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="contentpreview _video">
				                        			<div class="contentpreviewoptions">
				                        				<a href="##options" class="option">
				                        					<i class="fa fa-gear fa-spin" title="Edit"></i>
				                        				</a>
				                        			</div>
					                            	<?php echo $vidout;?>
					                            </div>
				                        		<div class="col-sm-3 coptionpoint _hold blogvideolocal <?php echo $lhid;?>">
				                        			<div class="form-group">
							                            <label>Video WEBM</label>
							                            <div class="input-group">
								                            <div class="input-group-addon">
								                                <i class="fa fa-file-text"></i>
								                            </div>
								                            <input type="file" <?php echo $vet;?> class="form-control" name="videowebm" placeholder="Choose file">
								                            <?php 
								                              	if($viddata['videowebm']!==""){

								                            ?>
									                            <select class="form-control" name="videowebm_delete">
												                    <option value="">Delete this Entry?</option> 
												                    <option value="inactive">Yes</option> 
												                </select>
								                            <?php 
							                              		}
							                              	?>
								                            <div class="input-group-addon">
								                              	<?php 
								                              		if($viddata['videowebm']!==""){
								                              	?>
								                                	<i class="fa fa-check color-lightgreen" title="available"></i>
								                                <?php 
								                              		}else{
								                              	?>
								                                	<i class="fa fa-close color-lightred"></i>
								                              	<?php 
								                              		}
								                              	?>
								                            </div>
							                            </div><!-- /.input group -->
							                        </div>
							                    </div>
				                        		<div class="col-sm-3 coptionpoint _hold blogvideolocal <?php echo $lhid;?>">
				                        			<div class="form-group">

							                            <label>Video FLV</label>
							                            <div class="input-group">
								                            <div class="input-group-addon">
								                                <i class="fa fa-file-text"></i>
								                            </div>
								                            <input type="file" <?php echo $vet;?> class="form-control" name="videoflv" placeholder="Choose File">
								                            <?php 
								                              	if($viddata['videoflv']!==""){
								                            ?>
									                            <select class="form-control" name="videoflv_delete">
												                    <option value="">Delete this Entry?</option> 
												                    <option value="inactive">Yes</option> 
												                </select>
								                            <?php 
							                              		}
							                              	?>
								                            <div class="input-group-addon">
								                              	<?php 
								                              		if($viddata['videoflv']!==""){
								                              	?>
								                                	<i class="fa fa-check color-lightgreen"></i>
								                                <?php 
								                              		}else{
								                              	?>
								                                	<i class="fa fa-close color-lightred"></i>
								                              	<?php 
								                              		}
								                              	?>
								                            </div>
							                            </div><!-- /.input group -->
							                        </div>
							                    </div>

				                        		<div class="col-sm-3 coptionpoint _hold blogvideolocal <?php echo $lhid;?>">
				                        			<div class="form-group">
							                            <label>Video 3GP</label>
							                            <div class="input-group">
								                            <div class="input-group-addon">
								                                <i class="fa fa-file-text"></i>
								                            </div>
								                            <input type="file" <?php echo $vet;?> class="form-control" name="video3gp" placeholder="">
								                            <?php 
								                              	if($viddata['video3gp']!==""){
								                            ?>
									                            <select class="form-control" name="video3gp_delete">
												                    <option value="">Delete this Entry?</option> 
												                    <option value="inactive">Yes</option> 
												                </select>
								                            <?php 
							                              		}
							                              	?>
								                            <div class="input-group-addon">

								                              	<?php 
								                              		if($viddata['video3gp']!==""){

								                              	?>
								                                	<i class="fa fa-check color-lightgreen"></i>
								                                <?php 
								                              		}else{
								                              	?>
								                                	<i class="fa fa-close color-lightred"></i>
								                              	<?php 
								                              		}
								                              	?>
								                            </div>
							                            </div><!-- /.input group -->
							                        </div>
							                    </div>
				                        		<div class="col-sm-3 coptionpoint _hold blogvideolocal <?php echo $lhid;?>">
				                        			<div class="form-group">
							                            <label>Video MP4</label>
							                            <div class="input-group">
								                            <div class="input-group-addon">
								                                <i class="fa fa-file-text"></i>
								                            </div>
								                            <input type="file" <?php echo $vet;?> class="form-control" name="videomp4" placeholder="">
								                            <?php 
								                              	if($viddata['videomp4']!==""){
								                            ?>
									                            <select class="form-control" name="videomp4_delete">
												                    <option value="">Delete this Entry?</option> 
												                    <option value="inactive">Yes</option> 
												                </select>
								                            <?php 
							                              		}
							                              	?>
								                            <div class="input-group-addon">

								                              	<?php 
								                              		if($viddata['videomp4']!==""){

								                              	?>
								                                	<i class="fa fa-check color-lightgreen"></i>
								                                <?php 
								                              		}else{
								                              	?>
								                                	<i class="fa fa-close color-lightred"></i>
								                              	<?php 
								                              		}
								                              	?>
								                            </div>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>

				                        		<div class="col-sm-12 coptionpoint _hold blogvideolocal <?php echo $lhid;?>">
				                        			<div class="form-group">
							                            <label>Video Caption</label>
							                            <div class="input-group">
								                            <div class="input-group-addon">
								                                <i class="fa fa-file-text"></i>
								                            </div>
								                            <input type="text" class="form-control" name="videocaption" value="<?php echo $viddata['caption'];?>" placeholder="Specify a caption for the video">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-12 coptionpoint _hold blogvideoembed <?php echo $ehid;?>">
				                        			<div class="form-group">
							                            <label>Embed</label>
							                            <div class="input-group">
								                            <div class="input-group-addon">
								                                <i class="fa fa-file-text"></i>
								                            </div>
								                            <textarea class="form-control" 
								                            		rows="3" 
								                            		name="videoembed" 
								                            		placeholder="Embed Code"
								                            ><?php echo $viddata['videoembed'];?></textarea>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-12 coptionpoint _hold">
				                        			<div class="form-group">
							                            <div class="input-group">
								                            <!-- <div class="input-group-addon">
								                                <i class="fa fa-trash"></i>
								                            </div> -->
									                        <input type="hidden" class="form-control" name="video_id" value="<?php echo $viddata['id'];?>"/>
									                        <input type="hidden" class="form-control" name="video_map" value='<?php echo $viddata['originalmap'];?>'/>
							                        		<!-- <select class="form-control" name="video_delete">
											                    <option value="">Delete this Video?</option> 
											                    <option value="inactive">Yes</option> 
											                </select> -->
											            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        	</div>
								<?php 								
                						}	
                				?>
                            	</div>
		                        <input type="hidden" name="blogvideocount" class="form-control" value="1" data-counter="true"/>

                            	<?php 
                					}else{
                				?>
		                            <div class="col-md-12 blogvideo-field-hold ">
		                            	<input type="hidden" name="blogvideocount" class="form-control" value="1" data-counter="true"/>
			                        	<!-- <h3>Maximum of 1 video at a time</h3> -->
			                        	<div class="col-md-12 multi_content_hold" data-type="triggerprogenitor" data-cid="1" data-name="blogvideo">
			                        		<h4 class="multi_content_countlabels">Video </h4>
			                        		<div class="col-sm-12">
			                        			<div class="form-group">
						                            <label>Entry Type</label>
						                            <div class="input-group">
						                              	<div class="input-group-addon">
						                                	<i class="fa fa-file-text"></i>
						                              	</div>
						                              	<select name="videotype" class="form-control">
				                                      		<option value="local">Local</option>
				                                      		<option value="embed">Embed</option>
				                                      	</select>
						                            </div><!-- /.input group -->
						                        </div><!-- /.form group -->
			                        		</div>
			                        		<div class="col-sm-3 blogvideolocal">
			                        			<div class="form-group">
						                            <label>Video WEBM</label>
						                            <div class="input-group">
						                              <div class="input-group-addon">
						                                <i class="fa fa-file-text"></i>
						                              </div>
						                              <input type="file" class="form-control" name="videowebm" placeholder="Choose file">
						                            </div><!-- /.input group -->
						                        </div><!-- /.form group -->
			                        		</div>
			                        		<div class="col-sm-3 blogvideolocal">
			                        			<div class="form-group">
						                            <label>Video FLV</label>
						                            <div class="input-group">
						                              <div class="input-group-addon">
						                                <i class="fa fa-file-text"></i>
						                              </div>
						                              <input type="file" class="form-control" name="videoflv" placeholder="Choose File">
						                            </div><!-- /.input group -->
						                        </div><!-- /.form group -->
			                        		</div>
			                        		<div class="col-sm-3 blogvideolocal">
			                        			<div class="form-group">
						                            <label>Video 3GP</label>
						                            <div class="input-group">
						                              <div class="input-group-addon">
						                                <i class="fa fa-file-text"></i>
						                              </div>
						                              <input type="file" class="form-control" name="video3gp" placeholder="">
						                            </div><!-- /.input group -->
						                        </div><!-- /.form group -->
			                        		</div>
			                        		<div class="col-sm-3 blogvideolocal">
			                        			<div class="form-group">
						                            <label>Video MP4</label>
						                            <div class="input-group">
						                              <div class="input-group-addon">
						                                <i class="fa fa-file-text"></i>
						                              </div>
						                              <input type="file" class="form-control" name="videomp4" placeholder="">
						                            </div><!-- /.input group -->
						                        </div><!-- /.form group -->
			                        		</div>
			                        		<div class="col-sm-12 blogvideolocal">
			                        			<div class="form-group">
						                            <label>Video Caption</label>
						                            <div class="input-group">
						                              <div class="input-group-addon">
						                                <i class="fa fa-file-text"></i>
						                              </div>
						                              <input type="text" class="form-control" name="videocaption" placeholder="Specify a caption for the video">
						                            </div><!-- /.input group -->
						                        </div><!-- /.form group -->
			                        		</div>
			                        		<div class="col-sm-9 blogvideoembed hidden">
			                        			<div class="form-group">
						                            <label>Embed</label>
						                            <div class="input-group">
						                              <div class="input-group-addon">
						                                <i class="fa fa-file-text"></i>
						                              </div>
						                              <textarea class="form-control" rows="3" name="videoembed" placeholder="Embed Code"></textarea>
						                            </div><!-- /.input group -->
						                        </div><!-- /.form group -->
			                        		</div>
			                        	</div>

			                        	<div name="blogvideoentrypoint" data-marker="true"></div>
			                        	<input name="blogvideodatamap" type="hidden" data-map="true" value="videotype-:-select<|>
			                        	videowebm-:-input<|>
			                        	videoflv-:-input<|>
			                        	video3gp-:-input<|>
			                        	videocaption-:-input<|>
			                        	videodetails-:-input">
			                        	<!-- <a href="##" class="generic_addcontent_trigger" 
			                        		data-type="triggerformaddlib" 
			                        		data-name="blogvideocount_addlink" 
			                        		data-i-type="" 
			                        		data-limit="10"> 
			                        		<i class="fa fa-plus"></i>Add More?
			                        	</a> -->
		                            </div>
                				<?php 								
                					}	
                				?>
                            	<?php 

                            		// this code is meant for the event multiple 
                            		// blog video post entries are possible on a single
                            		// entry

                					/*$ft=$row['viddata']['total']+1;
                					// $vet='data-edittype="true"';
                					$vet='';
                					if($ft==1){
                						$vet="";
                					}*/
                				?>
                        	</div>
                		</div>
                	</div>		
                	<!-- end edit section accordion -->

                	<!-- Start audio entry section accordion -->
                	<div class="box-group top_pad blogaudio high_bottom_margin <?php echo $audhid;?>" data-name="audiosection" id="contentaccordion">
						<div class="panel box box-primary overflowhidden">
					    	<div class="box-header with-border">
						        <h4 class="box-title">
						          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock_group5edit">
						            <i class="fa fa-volume-up"></i>
						            	Audio Entry
						          </a>
						        </h4>
					    	</div>
					      	<div id="headBlock_group5edit" class="panel-collapse collapse in">
	                            <?php 
	                					if($row['audiodata']['total']>0){
	                						
	                				?>
							      		<!-- old audio entries -->
			                            <div class="col-md-12 editportaudio-field-hold ">
				                        	<h3>Edit Previous Entries </h3>
	                            	<?php 
	                						for($i=0;$i<$row['audiodata']['total'];$i++){
	                							$t=$i+1;
	                							$audiodata=$row['audiodata'][$i];
	                							// check to see if the selected type is embeded or a local 
	                							// audio
	                							$ehid="hidden";
	                							$lhid="";
	                							$audioout='
	                								<audio src="'.$audiodata['location'].'" controls>
	                									Your Browser does not support 
	                									HTML5 audio
	                								</audio>

	                							';
	                							if($audiodata['disptype']=="embed"){
	                								$ehid="";
		                							$lhid="hidden";
		                							$audioout=$audiodata['audioembed'];
	                							}
	                				?>
	                						<div class="col-md-12 multi_content_hold" data-type="triggerprogeny" data-cid="1" data-name="portaudio">
				                        		<!-- <h4 class="multi_content_countlabels">Audio</h4> -->
				                        		<div class="contentpreview _audio">
				                        			<div class="contentpreviewoptions">
				                        				<a href="##options" class="option">
				                        					<i class="fa fa-gear fa-spin" title="Edit"></i>
				                        				</a>
				                        			</div>
					                            	<?php echo $audioout;?>
					                            </div>
				                        		<div class="col-sm-12 coptionpoint _hold">
				                        			<div class="form-group">
							                            <label>Entry Type</label>
							                            <div class="input-group">
							                              	<div class="input-group-addon">
							                                	<i class="fa fa-file-text"></i>
							                              	</div>
							                              	<select name="audiotype" class="form-control">
					                                      		<option value="">--Choose--</option>
					                                      		<option value="local">Local</option>
					                                      		<option value="embed">Embed</option>
					                                      	</select>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-12 coptionpoint _hold blogaudiolocal <?php echo $lhid?>">
				                        			<div class="form-group">
							                            <label>Audio File</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="file" class="form-control" data-edittype="true" name="audio" placeholder="">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-12 coptionpoint _hold blogaudioembed <?php echo $ehid?>">
				                        			<div class="form-group">
							                            <label>Embed</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <textarea class="form-control" rows="3" name="audioembed" placeholder="Embed Code"
							                              	><?php echo $audiodata['audioembed'];?></textarea>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-12 coptionpoint _hold blogaudiolocal <?php echo $lhid?>">
				                        			<div class="form-group">
							                            <label>Audio Caption</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="text" class="form-control" name="audiocaption" value="<?php echo $audiodata['audiocaption'];?>" placeholder="Specify a caption for the audio file">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
								                   	<input type="hidden" class="form-control" name="localaudio_id" value="<?php echo $audiodata['id'];?>"/>
				                        		</div>
				                        		<div class="col-sm-12 coptionpoint _hold">
				                        			<div class="form-group">
							                            <div class="input-group">
								                            <!-- <div class="input-group-addon">
								                                <i class="fa fa-trash"></i>
								                            </div>
							                        		<select class="form-control" name="audio_delete">
											                    <option value="">Delete Audio Entry?</option> 
											                    <option value="inactive">Yes</option> 
											                </select> -->
											            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        	</div>
									<?php 								
	                						}	
	                				?>
	                            		</div>
	                            	<?php 
	                					}else{

	                					/*$ft=$row['audiodata']['total']+1;
	                					// $aet='data-edittype="true"';
	                					$aet="";
	                					if($ft==1){
	                						$aet="";
	                					}*/
	                				?>
			                            <div class="col-md-12 blogaudio-field-hold ">
			                            	<input type="hidden" name="blogaudiocount" class="form-control" value="1" data-counter="true"/>
				                        	<!-- <h3>Maximum of 1 Audio entry at a time</h3> -->
				                        	<div class="col-md-12 multi_content_hold" data-type="triggerprogenitor" data-cid="1" data-name="blogaudio">
				                        		<h4 class="multi_content_countlabels">Audio</h4>
				                        		<div class="col-sm-4">
				                        			<div class="form-group">
							                            <label>Entry Type</label>
							                            <div class="input-group">
							                              	<div class="input-group-addon">
							                                	<i class="fa fa-file-text"></i>
							                              	</div>
							                              	<select name="audiotype"  class="form-control">
					                                      		<option value="">--Choose--</option>
					                                      		<option value="local">Local</option>
					                                      		<option value="embed">Embed</option>
					                                      	</select>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-4 blogaudiolocal">
				                        			<div class="form-group">
							                            <label>Audio File</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="file" class="form-control" name="audio" placeholder="">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-4 blogaudioembed hidden">
				                        			<div class="form-group">
							                            <label>Embed</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <textarea class="form-control" rows="3" name="audioembed" placeholder="Embed Code"></textarea>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-4 blogaudiolocal">
				                        			<div class="form-group">
							                            <label>Audio Caption</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="text" class="form-control" name="audiocaption" placeholder="Specify a caption for the audio file">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        	</div>
				                        	<div name="blogaudioentrypoint" data-marker="true"></div>
				                        	<input name="blogaudiodatamap" type="hidden" data-map="true" value="audiotype-:-select<|>
				                        	audio-:-input<|>
				                        	audiocaption-:-input<|>
				                        	audioembed-:-input">
				                        	<!-- <a href="##" class="generic_addcontent_trigger" 
				                        		data-type="triggerformaddlib" 
				                        		data-name="portvideocount_addlink" 
				                        		data-i-type="" 
				                        		data-limit="10"> 
				                        		<i class="fa fa-plus"></i>Add More?
				                        	</a> -->
			                            </div>
	                				<?php 								
	                						}	
	                				?>
	                            		<input type="hidden" name="blogaudiocount" class="form-control" value="1" data-counter="true"/>
                        	</div>
                		</div>
                	</div>		
                	<!-- end edit section accordion -->

					<div class="col-md-12" data-name="introparagraph">
                    	<div class="form-group">
							<label><h4>Introductory Paragraph</h4>
								<small>Short Description for preview on the blog</small>
							</label>
							<textarea name="introparagraph" 
							id="postersmalltwoedit" Placeholder="Introductory Paragraph" 
							class="form-control" data-mce="true"
							><?php echo $row['introparagraph'];?></textarea>
							
                    	</div>
					</div>

					<div class="col-md-12" data-name="blogentry">
                    	<div class="form-group">
							<label><h4>Main Blog Post</h4></label>
							<textarea name="blogentry" 
							id="adminposteredit" Placeholder="The blog post" 
							class="form-control" data-mce="true"
							><?php echo $row['blogpost'];?></textarea>
							
                    	</div>
					</div>

					<!-- Start comments entry section accordion -->
                	<div class="box-group top_pad  high_bottom_margin " id="contentaccordion">
						<div class="panel box box-primary overflowhidden">
		    				<input type="hidden" name="blogentrytype" value="<?php echo $row['blogentrytype'];?>"/>
					    	<div class="box-header with-border">
						        <h4 class="box-title">
						          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock_comments">
						            <i class="fa fa-comments"></i>
						            	<?php 
						            		if($dataset['commenttype']=="normal"){

						            	?>
						            	Comments (<?php echo $blog_comment_count;?>)
						            	<?php
						            		}else{

						            	?>
						            	<?php echo $blog_comment_count;?>
						            	<?php
						            		}
						            	?>
						          </a>
						        </h4>
					    	</div>
					      	<div id="headBlock_comments" class="panel-collapse collapse">
					      		<?php echo $blog_comment_data_output;?>
					      		<div class="col-md-12 comment_section">

					      		</div>
							</div>
						</div>		
					</div>		
                	<!-- end edit section accordion -->
                	<div class="col-md-12">
                    	<div class="form-group">
                    		<?php
                        		if($dataset['status']!=="schedule"){
                        	?>

	                        <label>Enable / Disable</label>
                        	<?php
                        	}else{

                        	
                        	?>
	                        <label>The post is Scheduled.</label>
	                        <?php	
                        	}	
                        	?>
	                        <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-exclamation-triangle color-red"></i>
                                </div>
		                        <select name="status" class="form-control">
		                        	<option value="">--Choose--</option>
		                        	<?php
		                        		if(strtolower($dataset['scheduledpost'])!=="yes"){
		                        	?>
		                        	<option value="active">Active</option>
		                        	<option value="inactive">Inactive</option>
		                        	<?php
		                        	}else{
		                        	?>
		                        	<option value="schedule">Scheduled</option>
		                        	<option value="inactive">Inactive</option>
		                        	<?php	
		                        	}
		                        	?>
						  	    </select>
						  	</div>
					  	</div>
                    </div>
                    <input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>
        			<input type="hidden" name="fdgen" value="<?php echo $inimaxupload;?>" data-fdgen="true"/>
                    <input type="hidden" name="extraformdata" value="blogtypeid-:-select<|>
                    blogcategoryid-:-select<|>
                    schedulestatus-:-select<|>
                    scheduledate-:-input-:-[group-|-schedulestatus-|-select-|-yes]<|>
                    blogentrytype-:-input<|>
                    egroup|data-:-[bloggallerycount>|<
                    galimage-|-input|image>|<
                    caption-|-input>|<
                    details-|-textarea]-:-groupfall[1-2,2-1,1-3,1]-:-[single-|-blogentrytype-|-select-|-gallery-|-galimage]<|>
                    bannerpic-:-input-:-[group-|-blogentrytype-|-select-|-banner]<|>
                    audiotype-:-select-:-[group-|-blogentrytype-|-select-|-audio]<|>
                    audio-:-input|audio|mp3-:-[group-|-blogentrytype-|-select-|-audio-|-audiotype-|-select-|-local]<|>
                    audioembed-:-textarea-:-[group-|-blogentrytype-|-select-|-audio-|-audiotype-|-select-|-embed]<|>
                    videotype-:-select-:-[group-|-blogentrytype-|-select-|-video]<|>
                    videowebm-:-input|video|webm-:-[group-|-blogentrytype-|-select-|-video-|-videotype-|-select-|-local-|-videoflv-|-input-|-*null*-|-video3gp-|-input-|-*null*-|-videomp4-|-input-|-*null*]<|>
                    videoflv-:-input|video|flv<|>
                    video3gp-:-input|video|3gp<|>
                    videomp4-:-input|video|mp4<|>
                    videoembed-:-textarea-:-[group-|-blogentrytype-|-select-|-video-|-videotype-|-select-|-embed]<|>
                    profpic-:-input|image<|>
                    title-:-input<|>
                    introparagraph-:-textarea<|>
                    blogentry-:-textarea<|>
                    pwrdd-:-select<|>
                  	pwrd-:-input-:-[group-|-pwrdd-|-select-|-yes]"/>

                    <input type="hidden" name="errormap" value="blogtypeid-:-Please specify the Blog you want to make a post to<|>
                    blogcategoryid-:-Please choose a category, if none is available and you have chosen a Blog Type, check and make sure categories have been created under it.<|>
                    schedulestatus-:-NA<|>
                    scheduledate-:-Choose a date and time when this post will be
                    available to your viewers<|>
                    blogentrytype-:-NA<|>
                    egroup|data-:-[Please provide a valid image file>|<
                    Specify the caption text>|<
                    NA]<|>
                    bannerpic-:-Please choose an image for the banner 
                    you want displayed on the blog<|>
                    
                    audiotype-:-Specify the type of audio entry you want to make
                    \&#34;Local\&#34; means that your audio is streaming directly 
                    from a file on your server and<br>
                    \&#34;Embed\&#34; means you are using an embed code from a 
                    audio service provider like soundcloud<|>
                    
                    audio-:-Choose a valid mp3 file.<|>
                    audioembed-:-Provide an audio embed code<|>
                    
                    videotype-:-Specify the type of video entry you want to make
                    \&#34;Local\&#34; means that your video is streaming directly 
                    from a file on your server and<br>
                    \&#34;Embed\&#34; means you are using an embed code from a 
                    video service provider like youtube or vimeo<|>
                    
                    videowebm-:-Please provide a video, you can 
                    select multiple types of the same video using 
                    the fields provided but .web is most 
                    preferred <|>
                    videoflv-:-NA<|>
                    video3gp-:-NA<|>
                    videomp4-:-NA<|>
                    videoembed-:-Provide the embed code for this video. You can get this from the video service provider<|>
                    profpic-:-NA<|>
                    title-:-Provide the title for this post<|>
                    introparagraph-:-Give a short introduction to the 
                    post that would encourage readers to dig further, 
                    it can be an excerpt from the main post or just 
                    something different<|>
                    blogentry-:-Provide the complete post<|>
                    pwrdd-:-NA<|>
                  	pwrd-:-Please provide a password"/>
		                    					

	                <input type="hidden" name="formdata" value="<?php echo $formtruetype2;?>"/>
	                
	                <div class="col-md-12 clearboth">
		                <div class="box-footer">
		                    <input type="button" class="btn btn-danger" name="updateentry" data-formdata="<?php echo $formtruetype2;?>" onclick="submitCustom('<?php echo $formtruetype2;?>','complete')" value="Update"/>
		                </div>
		            </div>
	        	</form>	
	      

				<script>
					$(document).ready(function(){
						var curmceadminposter=[];
						curmceadminposter['width']="100%";
						curmceadminposter['height']="650px";
						curmceadminposter['toolbar1']="undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect";
						curmceadminposter['toolbar2']="| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ";
						callTinyMCEInit("textarea#adminposteredit",curmceadminposter);
												

						var curmcethreeposter=[];
						curmcethreeposter['width']="100%";

						curmcethreeposter['height']="300px";
						curmcethreeposter['toolbar2addon']=" | preview code ";
						callTinyMCEInit("textarea#postersmalltwoedit",curmcethreeposter);
						<?php echo $totalscripts;?>
						// init select2
						if($.fn.select2){
							$('select[data-name=select2plain]').select2({
							    theme: "bootstrap"
							});
							$('select[data-name=faselect]').select2({
							    theme: "bootstrap",
							    templateResult: faSelectTemplate
							});
						}
						if($.fn.datepicker){
			  				$('[data-datetimepicker]').datetimepicker({
						        format:"YYYY-MM-DD HH:mm:ss",
						        keepOpen:true
						    })
						    // for disabling previous dates 
						    $('[data-datetimepickerf]').datetimepicker({
						        format:"YYYY-MM-DD HH:mm:ss",
						        keepOpen:true,
						        minDate: moment(1, 'h')/*,
						        debug:true*/

						    });
						    $('[data-datepicker]').datetimepicker({
						        format:"YYYY-MM-DD",
						        keepOpen:true/*,
						        debug:true*/
						    });
						    $('[data-datepickerf]').datetimepicker({
						        format:"YYYY-MM-DD",
						        keepOpen:true,
						        minDate: moment(1, 'h')
						        // showClose:true
						        // debug:true
						    });
						    $('[data-timepicker]').datetimepicker({
						        format:"HH:mm",
						        keepOpen:true
						    });
			  			}
						if($.fn.wordMAX){
						  // console.log("functional");
						  $('input[type="text"][data-wMax],textarea[data-wMax]').wordMAX(); 
						}
					});
				</script>
				<?php echo $commentscripts;?>	
	        </div>
<?php
	unset($dataset);
	unset($row);
?>
<?php			
		}else if($viewtype=="paginate"){
			// for pagination there are variables available which are common
			// to the 'paginationpagesout' displaytype in the display.php
			// file
			// $generalpagesdata = the total session array carrying data for current
			// transaction all other variables available are actually gotten
			// from various indexes in this array.

			// $data=array for entry 
			// $outputtype
			// the $outputtype combination is as follows
			// viewtype|viewer|type/type][typeval
			// for search content, the viewer  value must become an array
			// of the form 
			// $viewer[0]=viewer;
			// $viewer[1]=$searchtype;
			// $viewer[2]=$searchval;
			// $limit - current limit of request
			$cdata=explode("|", $outputtype);
			// var_dump($datamap);
			$vtype=$cdata[0];
			$viewer=$cdata[1];
			$type=isset($cdata[2])?$cdata[2]:"";
			$type="blockdeeprun";
			// check to see if the type section is in its compound state
			
			// var_dump($data);
			
			$data["single"]["type"]="blockdeeprun";
			$pagout= getAllBlogEntries($viewer,$limit,$type,$data);

			echo $pagout['adminoutputtwo'];
			unset($pagout);
		}
	}
?>

