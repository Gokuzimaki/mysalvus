<?php

	
	// first, we make sure key variables are made available to us
	if(isset($displaytype)||isset($_gdunitdisplaytype)){
    	$pcdata=getAllPortfolioCategories('admin','all');
		$inimaxupload=ini_get('post_max_size');

		if($viewtype=="create"||
			$viewtype=="featured_crt"){
			// create data content array
			$data=array();	

			// manage the datamap for ar
			if(isset($datamap)){
				$data['single']['datamap']=$contentgroupdatageneral['datamap'];
			}
			// set the form type for the edit section
			$data['single']['formtruetype']="edit_".$formtruetype;
			// initialise content variables for the form below 
			$newin="in";
			$editin="";	
			$newh="";
			$edith="";

			// for featured entries 
			if($viewtype=="featured_crt"){
				$data['queryextra']=" featured='yes'";
				// hide the create section
				$newh="hidden";
			}
			// check if there are currently entries first and prepare the 
			// edit table section
			$outsdata=getAllPortfolioEntries('admin','','',$data);

			if($outsdata['numrows']>0){
				// entries are available
				$editin="in";
				$newin="";
			}
			

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
			            <i class="fa fa-sliders"></i> Create Portfolio Entry
			          </a>
			        </h4>
			    </div>
			    <div id="NewPageManagerBlock" class="panel-collapse collapse <?php echo $newin;?>">
			        <div class="row">
						<form name="<?php echo $formtruetype;?>" method="POST" data-type="create" data-fdgen="true" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/basicsignup.php">
							<input type="hidden" name="entryvariant" value="createportfolioentry"/>
	            			<div class="col-sm-6"> 
                                <div class="form-group">
                                    <label>Entry Type</label>
                                    <div class="input-group">
                                      	<div class="input-group-addon">
                                        	<i class="fa fa-file-text"></i>
	                                    </div>
	                                    <select name="portfoliotype" class="form-control">
                                      		<option value="">--Choose--</option>
                                      		<option value="client">Client</option>
                                      		<option value="business">Business(Proprietary Software/Service)</option>
                                      		<option value="opensource">Open Source</option>
                                      		<option value="personal">Personal</option>
                                      	</select>
	                                </div><!-- /.input group -->
                              	</div><!-- /.form group -->
                            </div>
                            <div class="col-sm-6"> 
                                <div class="form-group">
                                    <label>Entry Title</label>
                                    <div class="input-group">
	                                      <div class="input-group-addon">
	                                        <i class="fa fa-file-text"></i>
	                                      </div>
	                                      <input type="text" class="form-control" name="portfoliotitle" Placeholder="Entry Title"/>
	                                </div><!-- /.input group -->
                              	</div><!-- /.form group -->
                            </div>
	            			<div class="col-sm-4"> 
                                <div class="form-group">
                                    <label>Category</label>
                                    <div class="input-group">
	                                      <div class="input-group-addon">
	                                        <i class="fa fa-file-text"></i>
	                                      </div>
	                                      <select name="catid" class="form-control">
	                                      		<?php echo $pcdata['selectiondata'];?>
	                                      </select>
	                                </div><!-- /.input group -->
                              	</div><!-- /.form group -->
                            </div>
                            
                            <div class="col-sm-8 client-name hidden"> 
                                <div class="form-group">
                                    <label>Client Name</label>
                                    <div class="input-group">
	                                      <div class="input-group-addon">
	                                        <i class="fa fa-file-text"></i>
	                                      </div>
	                                      <input type="text" class="form-control" name="clientname" Placeholder="Client Name"/>
	                                </div><!-- /.input group -->
                              	</div><!-- /.form group -->
                            </div>
                            <div class="col-md-4">
                        		<div class="form-group">
                        			<label>Cover Image </label>
                        			<div class="input-group">
                        				<div class="input-group-addon">
                                        	<i class="fa fa-file-image-o"></i>
                        				</div>
                            			<input type="file" class="form-control" name="coverimage"/>

                        			</div>
                        		</div>
                        	</div>
                        	<div class="col-md-4">
                        		<div class="form-group">
                        			<label>Banner Image </label>
                        			<div class="input-group">
                        				<div class="input-group-addon">
                                        	<i class="fa fa-file-image-o"></i>
                        				</div>
                            			<input type="file" class="form-control" name="bannerimage"/>

                        			</div>
                        		</div>
                        	</div>
                            <div class="col-md-12">
	                            <div class="col-sm-6"> 
	                                <div class="form-group">
	                                    <label>Project Start Date</label>
	                                    <div class="input-group">
		                                      <div class="input-group-addon">
		                                        <i class="fa fa-file-text"></i>
		                                      </div>
					              	  		  <input class="form-control" name="startdate" data-datepicker="true" placeholder="Specify start date."/>
		                                      
		                                </div><!-- /.input group -->
	                              	</div><!-- /.form group -->
	                            </div>
	                            <div class="col-sm-6"> 
	                                <div class="form-group">
	                                    <label>Project End Date <small>Leave empty if entry is ongoing</small></label>
	                                    <div class="input-group">
		                                      <div class="input-group-addon">
		                                        <i class="fa fa-file-text"></i>
		                                      </div>
					              	  		  <input class="form-control" name="enddate" data-datepicker="true" placeholder="Specify End/Completion date."/>
		                                      
		                                </div><!-- /.input group -->
	                              	</div><!-- /.form group -->
	                            </div>
                            </div>
                            
                            <div class="col-sm-6"> 
                                <div class="form-group">
                                    <label>Featured Entry? <small>Marks the entry to be featured for sections of the website.</small></label>
                                    <div class="input-group">
                                      	<div class="input-group-addon">
                                        	<i class="fa fa-file-text-o"></i>
	                                    </div>
	                                    <select name="featured" class="form-control">
                                      		<option value="">No</option>
                                      		<option value="yes">Yes</option>
                                      	</select>
	                                </div><!-- /.input group -->
                              	</div><!-- /.form group -->
                            </div>
                            <div class="col-md-12 featured-details hidden">
                            	<input type="hidden" name="featuretype" value="appdisplay"/>
                            	<!-- Start Portfolio featured details section accordion -->
	                        	<div class="box-group top_pad high_bottom_margin" id="contentaccordion">
									<div class="panel box box-primary overflowhidden">
								    	<div class="box-header with-border">
									        <h4 class="box-title">
									          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock_portfeatured">
									            <i class="fa fa-folder-o"></i>
									            	Portfolio Featured Details <small>(.jpeg,jpg images only)</small>
									          </a>
									        </h4>
								    	</div>
								      	<div id="headBlock_portfeatured" class="panel-collapse collapse in">
			                            	<div class="col-md-12">
			                            		<div class="form-group">
			                            			<label>Display Type <small>Specifies which featured image set is used for full section displays</small></label>
			                            			<div class="input-group">
			                            				<div class="input-group-addon">
				                                        	<i class="fa fa-file-image-o"></i>
			                            				</div>
				                            			<select class="form-control" name="fdoption">
				                            				<option value="">--Choose--</option>
				                            				<option value="laptop">Laptop</option>
				                            				<option value="iphone">Apple Iphone</option>
				                            				<option value="android">Android Mobile</option>
				                            				<option value="tablet">Tablet</option>
				                            			</select>
			                            			</div>
			                            		</div>
			                            	</div>
			                            	<div class="col-md-12">
				                            	<div class="col-md-3">
				                            		<div class="form-group">
				                            			<label>Laptop Image <small>(Best Dimension W - 463px H - 268px)</small></label>
				                            			<div class="input-group">
				                            				<div class="input-group-addon">
					                                        	<i class="fa fa-file-image-o"></i>
				                            				</div>
					                            			<input type="file" class="form-control" name="featuredadlaptop"/>

				                            			</div>
				                            		</div>
				                            	</div>
				                            	<div class="col-md-3">
				                            		<div class="form-group">
				                            			<label>Apple Image <small>(Best W - 122px H - 198px)</small></label>
				                            			<div class="input-group">
				                            				<div class="input-group-addon">
					                                        	<i class="fa fa-file-image-o"></i>
				                            				</div>
					                            			<input type="file" class="form-control" name="featuredadiphone"/>
				                            			</div>
				                            		</div>
				                            	</div>
				                            	<div class="col-md-3">
				                            		<div class="form-group">
				                            			<label>Android Image <small>(Best W - 154px H - 275px)</small></label>
				                            			<div class="input-group">
				                            				<div class="input-group-addon">
					                                        	<i class="fa fa-file-image-o"></i>
				                            				</div>
					                            			<input type="file" class="form-control" name="featuredadandroid"/>
				                            			</div>
				                            		</div>
				                            	</div>
				                            	<div class="col-md-3">
				                            		<div class="form-group">
				                            			<label>Tablet Image <small>(Best W - 463px H - 268px)</small></label>
				                            			<div class="input-group">
				                            				<div class="input-group-addon">
					                                        	<i class="fa fa-file-image-o"></i>
				                            				</div>
					                            			<input type="file" class="form-control" name="featuredadtablet"/>
				                            			</div>
				                            		</div>
				                            	</div>
			                            	</div>
										</div>
	                        		</div>
	                        	</div>		
	                        	<!-- end edit section accordion -->
                            </div>

                            <div class="col-sm-6"> 
                                <div class="form-group">
                                    <label>Attach gallery?</label>
                                    <div class="input-group">
                                      	<div class="input-group-addon">
                                        	<i class="fa fa-file-image-o"></i>
	                                    </div>
	                                    <select name="galleryattach" class="form-control">
                                      		<option value="">No</option>
                                      		<option value="yes">Yes</option>
                                      	</select>
	                                </div><!-- /.input group -->
                              	</div><!-- /.form group -->
                            </div>
                            <!-- Start gallery entry section accordion -->
                        	<div class="box-group top_pad portgallery high_bottom_margin hidden" id="contentaccordion">
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
			                            <div class="col-md-12 portgallery-field-hold ">
			                            	<input type="hidden" name="portgallerycount" class="form-control" value="1" data-counter="true"/>
				                        	<h3>Maximum of 10 images at a time</h3>
				                        	<div class="col-md-3 multi_content_hold" data-type="triggerprogenitor" data-cid="1" data-name="portgallery">
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
				                        	<div name="portgalleryentrypoint" data-marker="true"></div>
				                        	<input name="portgallerydatamap" type="hidden" data-map="true" value="galimage-:-input<|>
				                        	caption-:-input<|>
				                        	details-:-textarea">
				                        	<a href="##" class="generic_addcontent_trigger" 
				                        		data-type="triggerformaddlib" 
				                        		data-name="portgallerycount_addlink" 
				                        		data-i-type="" 
				                        		data-limit="10"> 
				                        		<i class="fa fa-plus"></i>Add More?
				                        	</a>
			                            </div>
		                        	</div>
                        		</div>
                        	</div>		
                        	<!-- end edit section accordion -->

                            <div class="col-sm-6"> 
                                <div class="form-group">
                                    <label>Attach Video? <small>Add video(s) to this portfolio entry.</small></label>
                                    <div class="input-group">
                                      	<div class="input-group-addon">
                                        	<i class="fa fa-file-text-o"></i>
	                                    </div>
	                                    <select name="vidattach" class="form-control">
                                      		<option value="">No</option>
                                      		<option value="yes">Yes</option>
                                      	</select>
	                                </div><!-- /.input group -->
                              	</div><!-- /.form group -->
                            </div>
                            <!-- Start video entry section accordion -->
                        	<div class="box-group top_pad portvideos high_bottom_margin hidden" id="contentaccordion">
								<div class="panel box box-primary overflowhidden">
							    	<div class="box-header with-border">
								        <h4 class="box-title">
								          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock_group4">
								            <i class="fa fa-file-image-o"></i>
								            	Video Entries
								          </a>
								        </h4>
							    	</div>
							      	<div id="headBlock_group4" class="panel-collapse collapse in">
			                            <div class="col-md-12 portvideo-field-hold ">
			                            	<input type="hidden" name="portvideocount" class="form-control" value="1" data-counter="true"/>
				                        	<h3>Maximum of 1 video at a time</h3>
				                        	<div class="col-md-12 multi_content_hold" data-type="triggerprogenitor" data-cid="1" data-name="portvideo">
				                        		<h4 class="multi_content_countlabels">Video (Entry 1)</h4>
				                        		<div class="col-sm-12">
				                        			<div class="form-group">
							                            <label>Entry Type</label>
							                            <div class="input-group">
							                              	<div class="input-group-addon">
							                                	<i class="fa fa-file-text"></i>
							                              	</div>
							                              	<select name="portvtype1" class="form-control">
					                                      		<option value="local">Local</option>
					                                      		<option value="embed">Embed</option>
					                                      	</select>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-4 portvideolocal">
				                        			<div class="form-group">
							                            <label>Video WEBM</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="file" class="form-control" name="portvwebm1" placeholder="Choose file">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-4 portvideolocal">
				                        			<div class="form-group">
							                            <label>Video FLV</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="file" class="form-control" name="portvflv1" placeholder="Choose File">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-4 portvideolocal">
				                        			<div class="form-group">
							                            <label>Video 3GP</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="file" class="form-control" name="portv3gp1" placeholder="">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-12 portvideolocal">
				                        			<div class="form-group">
							                            <label>Video Caption</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="text" class="form-control" name="portvcaption1" placeholder="Specify a caption for the video">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-9 portvideoembed hidden">
				                        			<div class="form-group">
							                            <label>Embed</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <textarea class="form-control" rows="3" name="portvembed1" placeholder="Embed Code"></textarea>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        	</div>
				                        	<div name="portvideoentrypoint" data-marker="true"></div>
				                        	<input name="portvideodatamap" type="hidden" data-map="true" value="portvtype-:-select<|>
				                        	portvwebm-:-input<|>
				                        	portvflv-:-input<|>
				                        	portv3gp-:-input<|>
				                        	portvcaption-:-input<|>
				                        	portvdetails-:-input">
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

                        	<div class="col-sm-6"> 
                                <div class="form-group">
                                    <label>Attach Audio? <small>Add Audio(s) to this portfolio entry.</small></label>
                                    <div class="input-group">
                                      	<div class="input-group-addon">
                                        	<i class="fa fa-file-text-o"></i>
	                                    </div>
	                                    <select name="audioattach" class="form-control">
                                      		<option value="">No</option>
                                      		<option value="yes">Yes</option>
                                      	</select>
	                                </div><!-- /.input group -->
                              	</div><!-- /.form group -->
                            </div>
                            
                        	<!-- Start audio entry section accordion -->
                        	<div class="box-group top_pad portaudio high_bottom_margin hidden" id="contentaccordion">
								<div class="panel box box-primary overflowhidden">
							    	<div class="box-header with-border">
								        <h4 class="box-title">
								          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock_group5">
								            <i class="fa fa-file-image-o"></i>
								            	Audio Entries
								          </a>
								        </h4>
							    	</div>
							      	<div id="headBlock_group5" class="panel-collapse collapse in">
			                            <div class="col-md-12 portaudio-field-hold ">
			                            	<input type="hidden" name="portaudiocount" class="form-control" value="1" data-counter="true"/>
				                        	<h3>Maximum of 1 Audio entry at a time</h3>
				                        	<div class="col-md-12 multi_content_hold" data-type="triggerprogenitor" data-cid="1" data-name="portaudio">
				                        		<h4 class="multi_content_countlabels">Audio (Entry 1)</h4>
				                        		<div class="col-sm-4">
				                        			<div class="form-group">
							                            <label>Entry Type</label>
							                            <div class="input-group">
							                              	<div class="input-group-addon">
							                                	<i class="fa fa-file-text"></i>
							                              	</div>
							                              	<select name="portatype1"  class="form-control">
					                                      		<option value="">--Choose--</option>
					                                      		<option value="local">Local</option>
					                                      		<option value="embed">Embed</option>
					                                      	</select>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-4 portaudiolocal">
				                        			<div class="form-group">
							                            <label>Audio File</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="file" class="form-control" name="portaudio1" placeholder="">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-4 portaudioembed hidden">
				                        			<div class="form-group">
							                            <label>Embed</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <textarea class="form-control" rows="3" name="portaembed1" placeholder="Embed Code"></textarea>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-4 portaudiolocal">
				                        			<div class="form-group">
							                            <label>Audio Caption</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="text" class="form-control" name="portacaption1" placeholder="Specify a caption for the audio file">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        	</div>
				                        	<div name="portaudioentrypoint" data-marker="true"></div>
				                        	<input name="portaudiodatamap" type="hidden" data-map="true" value="portatype-:-select<|>
				                        	portaudio-:-input<|>
				                        	portacaption-:-input<|>
				                        	portaembed-:-input">
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

                            <div class="col-md-12">
	                            <div class="col-md-6">
	                                <label>Short Details <small>Text here is used in featured displays as well</small>:</label>
	                                <textarea class="form-control" rows="3" name="shorttext" data-wMax="40" data-wMax-fname="<?php echo $formtruetype;?>" data-wMax-type="word" placeholder="Short Description For the current entry"></textarea>
	                            </div>
	                            <div class="col-md-6">
	                                <label>Entry Tags <small>Comma seperated list of descriptive related categories.</small>:</label>
	                                <textarea class="form-control" rows="3" name="tags" placeholder="Tags. e.g Fun,Video game, role Playing"></textarea>
	                            </div>
                            </div>
                            <div class="col-md-12">
                                <label>Full Entry Details:</label>
                                <textarea class="form-control" rows="3" name="entrydetails" data-mce="true" id="postersmallthree_1" placeholder="The category Details"></textarea>
                            </div>
                            
                            <!-- Start Website/Social section accordion -->
                        	<div class="box-group top_pad" id="contentaccordion">
								<div class="panel box box-primary overflowhidden">
							    	<div class="box-header with-border">
								        <h4 class="box-title">
								          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock_group1">
								            <i class="fa fa-facebook"></i>
								            <i class="fa fa-twitter"></i>
								            <i class="fa fa-google"></i>
								            <i class="fa fa-linkedin"></i>
								            <i class="fa fa-gear fa-spin"></i> 
								            	Portfolio Entry Website/Socials
								          </a>
								        </h4>
							    	</div>
							      	<div id="headBlock_group1" class="panel-collapse collapse in">
		                                <div class="col-md-12 social-field-hold">
		                                	<!-- data-valcount="2" data-valset="1,2,3" -->
		                                    <input name="socialcount" type="hidden" value="7" data-counter="true" class="form-control"/>
		                                    <?php 
			                                    $sdarr=array();
			                                    $sdicon=array();
			                                    // prep the default social media info
			                                    $sdarr[]="Website";$sdicon[]="fa-chrome";
			                                    $sdarr[]="Facebook";$sdicon[]="fa-facebook";
			                                    $sdarr[]="Twitter";$sdicon[]="fa-twitter";
			                                    $sdarr[]="Googleplus";$sdicon[]="fa-google-plus";
		                                    	$sdarr[]="Linkedin";$sdicon[]="fa-linkedin";
			                                    $sdarr[]="Pinterest";$sdicon[]="fa-pinterest";
			                                    $sdarr[]="Instagram";$sdicon[]="fa-instagram";
			                                    $sdarr[]="Github";$sdicon[]="fa-github";
			                                    // $sdarr[]="Skype";$sdicon[]="fa-skype";
		                                    	for ($i = 1; $i <=7 ; $i++) {
		                                    		$t=$i-1	;
		                                    		$ttype="triggerprogeny";
		                                    		if($t==0){
		                                    			$ttype="triggerprogenitor";
		                                    		}
		                                    		$dname=$sdarr[$t];
		                                    		$icon=$sdicon[$t];
		                                    		$csurl="";
		                                    		
		                                    ?>
				                                	<div class="col-md-3 multi_content_hold " data-name="social" data-type="<?php echo $ttype;?>" data-cid="<?php echo $i?>">
						                                <div class="form-group">
						                                    <label><?php echo $dname;?> Url</label>
						                                    <div class="input-group">
							                                    <div class="input-group-addon">
							                                        <i class="fa <?php echo $icon;?>"></i>
							                                    </div>
				                                    			<input name="socialname<?php echo $i;?>" type="hidden" value="<?php echo $dname;?>" class="form-control"/>
				                                    			<input name="socialicon<?php echo $i;?>" type="hidden" value="<?php echo $icon;?>" class="form-control"/>
							                                    <input name="socialurl<?php echo $i;?>" class="form-control" type="url" value="<?php if(isset($csurl)){echo $csurl;}?>" Placeholder="The web address."/>				                                    
							                                </div><!-- /.input group -->
						                                </div><!-- /.form group -->
				                                	</div>
		                                	<?php
		                                		}
		                                	?>
		                                	<div name="socialentrypoint" data-marker="true"></div>
		                                	<input name="socialdatamap" type="hidden" value="socialname-:-input<|>
							                      socialicon-:-input<|>
					                      		  socialurl-:-input" class="form-control"/>
		                                </div>
		                            </div>
                        		</div>
                        	</div>		
                        	<!-- end edit section accordion -->
                            <div class="col-sm-6"> 
                                <div class="form-group">
                                    <label>Password Lock this entry?</label>
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
                            <input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>
				            <input type="hidden" name="fdgen" value="32M<?php //echo $inimaxupload;?>" data-fdgen="true"/>
				            <!-- <input type="hidden" name="fdgentotal" value="0" data-fdgen-total="true"/> -->
		                    <input type="hidden" name="extraformdata" value="portfoliotype-:-select<|>
			                    portfoliotitle-:-input<|>
			                    catid-:-select<|>
			                    clientname-:-input-:-[group-|-portfoliotype-|-select-|-client]<|>
			                    coverimage-:-input|image<|>
			                    bannerimage-:-input|image<|>
			                    startdate-:-input<|>
			                    enddate-:-input<|>
			                    galleryattach-:-select<|>
			                    egroup|data-:-[portgallerycount>|<
			                    galimage-|-input|image>|<
			                    caption-|-input>|<
			                    details-|-textarea]-:-groupfall[1-2,2-1,1-3,1]-:-[single-|-galleryattach-|-select-|-yes-|-galimage]<|>
			                    featured-:-select<|>
			                    fdoption-:-select-:-[group-|-featured-|-select-|-yes]<|>
			                    featuredadlaptop-:-input|image|jpeg,jpg-:-[group-|-featured-|-select-|-yes-|-fdoption-|-select-|-laptop]<|>
			                    featuredadiphone-:-input|image|jpeg,jpg-:-[group-|-featured-|-select-|-yes-|-fdoption-|-select-|-apple]<|>
			                    featuredadandroid-:-input|image|jpeg,jpg-:-[group-|-featured-|-select-|-yes-|-fdoption-|-select-|-android]<|>
			                    featuredadtablet-:-input|image|jpeg,jpg-:-[group-|-featured-|-select-|-yes-|-fdoption-|-select-|-tablet]<|>
			                    vidattach-:-select<|>
			                    egroup|data-:-[portvideocount>|<
				                    portvtype-|-select>|<
				                    portvwebm-|-input|video|webm-|-(group-*-portvtype-*-select-*-local-*-portvflv-*-input-*-*null*-*-portv3gp-*-input-*-*null*)>|<
				                    portvflv-|-input|video|flv-|-(group-*-portvtype-*-select-*-local-*-portvwebm-*-input-*-*null*-*-portv3gp-*-input-*-*null*)>|<
				                    portv3gp-|-input|video|3gp-|-(group-*-portvtype-*-select-*-local-*-portvwebm-*-input-*-*null*-*-portvflv-*-input-*-*null*)>|<
				                    portvcaption-|-input-|-(group-*-portvtype-*-select-*-local)>|<
				                    portvembed-|-textarea-|-(group-*-portvtype-*-select-*-embed)
			                    ]-:-groupfall[2,3,4,6]-:-[single-|-vidattach-|-select-|-yes-|-portvwebm]<|>
			                    audioattach-:-select<|>
			                    egroup|data-:-[portaudiocount>|<
				                    portatype-|-select>|<
				                    portaudio-|-input|audio|mp3-|-(group-*-portatype-*-select-*-local)>|<
				                    portacaption-|-input-|-(group-*-portatype-*-select-*-local)>|<
				                    portaembed-|-textarea-|-(group-*-portatype-*-select-*-embed)
			                    ]-:-groupfall[2,4]-:-[single-|-audioattach-|-select-|-yes-|-portaudio]<|>
			                    shorttext-:-textarea<|>
			                    tags-:-textarea<|>
			                    entrydetails-:-textarea<|>
			                    egroup|data-:-[socialcount>|<
		                      	socialname-|-input>|<
		                      	socialicon-|-input>|<
		                      	socialurl-|-input]-:-groupfall[1-2]<|>
		                      	pwrdd-:-select<|>
		                      	pwrd-:-input-:-[group-|-pwrdd-|-select-|-yes]"/>
		                    
		                    <input type="hidden" name="errormap" value="portfoliotype-:-Please Specify the type for the current entry<|>
			                    portfoliotitle-:-Provide the title of this portfolio entry<|>
			                    catid-:-Choose a category for the current entry.<|>
			                    clientname-:-Specify the name of the client the entry was done for<|>
			                    coverimage-:-Please provide a cover image for this entry<|>
			                    bannerimage-:-NA<|>
			                    startdate-:-Specify the start date<|>
			                    enddate-:-NA<|>
			                    galleryattach-:-NA<|>
			                    egroup|data-:-[Please provide a valid image file>|<
			                    Specify the caption text>|<
			                    NA]<|>
			                    featured-:-NA<|>
			                    fdoption-:-Specify the feature type Display<|>
			                    featuredadlaptop-:-Please provide the laptop demo image<|>
			                    featuredadiphone-:-Please provide the Iphone demo image<|>
			                    featuredadandroid-:-Please provide the android demo image<|>
			                    featuredadtablet-:-Please provide the tablet demo image<|>
			                    vidattach-:-NA<|>
			                    egroup|data-:-[Specify the type for this entry>|<
				                    Please provide a valid webm video>|<
				                    Please provide a valid flv video>|<
				                    Please provide a valid 3gp video>|<
				                    NA>|<
				                    Please provide the embed code
			                    ]<|>
			                    audioattach-:-NA<|>
			                    egroup|data-:-[Specify the type for this entry>|<
				                    Please provide a valid mp3 audio file>|<
				                    NA>|<
				                    Please provide the audio embed code
			                    ]<|>
			                    shorttext-:-Please give a short description for this entry, less than 300 characters<|>
			                    tags-:-Provide tags describing this project<|>
			                    entrydetails-:-provide the full details for the entry<|>
			                    egroup|data-:-[NA>|<
		                      	NA>|<
		                      	NA]<|>
		                      	pwrdd-:-NA<|>
		                      	pwrd-:-Please provide a password."/>

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
				if($edith==""){


			?>
			<div class="panel box overflowhidden box-primary">
                <div class="box-header with-border">
                  <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#EditBlock">
                      <i class="fa fa-gear fa-spin"></i> Edit Portfolio Entries
                    </a>
                  </h4>
                </div>
                <div id="EditBlock" class="panel-collapse collapse <?php echo $editin;?>">
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
            <?php 
				}	
			?>
		</div>
	</div>
	<script>
		$(document).ready(function(){
			var curmceadminposter=[];
			curmceadminposter['width']="100%";
			curmceadminposter['height']="300px";
			curmceadminposter['toolbar1']="undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect";
			curmceadminposter['toolbar2']="| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ";
			callTinyMCEInit("textarea#adminposter",curmceadminposter);
			var curmcethreeposter=[];
			curmcethreeposter['width']="100%";

			curmcethreeposter['height']="300px";
			curmcethreeposter['toolbar2addon']=" | preview code ";
			callTinyMCEInit("textarea[id*=postersmallthree]",curmcethreeposter);
			
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
			//Date range picker
			if($(document).datepicker){
				$('[data-datetimepicker]').datetimepicker({
				    format:"YYYY-MM-DD HH:mm",
				    keepOpen:true
				    // showClose:true
				    // debug:true
				})
				$('[data-datepicker]').datetimepicker({
				    format:"YYYY-MM-DD",
				    keepOpen:true
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
</div>
<?php
		}else if($viewtype=="edit"){
			// echo $viewtype;
			// $row variable is being used here
			$formtruetype="edit_$formtruetype2";
			$data['single']['formtruetype']="$formtruetype";
			$data['single']['blockdeeprun']="true";

			$row=getSinglePortfolioEntry($editid,"",$data);
			if($row['numrows']>0){
			/*  
				id
				catid -3
				type -2
				gattach -6
				vidattach -7
				audattach -8
				companyname
				position
				projecttitle -1
				periodstart -4
				periodend  -5
				entrydate
				shorttext
				details
				projectsocial
				featured -10
				fdtype
				pwrdd -9
				pwd
				tags
				status
			*/
			// setup hidden section control variables and assign them appropriate
			// values based on the content of their database counter parts
			$clienthid="hidden"; // client name  section
			$fdhid="hidden"; // featured display section
			$fdtotal="0"; // featured display section total entries
			$galhid="hidden"; // gallery  section
			$galtotal="0"; // gallery  section total entries
			$vidhid="hidden"; // video  section
			$vidtotal="0"; // video  section total entries
			$audhid="hidden"; // audio  section
			$audtotal="0"; // audio  section total entries
			$pwrdhid="hidden"; // password  section
			if(strtolower($row['featured'])=="yes"){
				$fdhid="";
			}
			$fdtotal=$row['featureddata']['total'];
			
			if(strtolower($row['gattach'])=="yes"){
				$galhid="";
			}
			$galtotal=$row['gallerydata']['total'];

			if(strtolower($row['vidattach'])=="yes"){
				$vidhid="";
			}
			$vidtotal=$row['viddata']['total'];
			
			if(strtolower($row['audattach'])=="yes"){
				$audhid="";
			}
			$audtotal=$row['audiodata']['total'];
			if(strtolower($row['type'])=="client"){
				$clienthid="";
			}
			
			if(strtolower($row['pwrdd'])=="yes"){
				$pwrdhid="";
			}
			$totalscripts=$row['totalscripts'];
			
?>
			<!-- Edit section -->
			<div class="row">
				<form name="<?php echo $formtruetype;?>" method="POST" data-type="create" data-fdgen="true" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/edit.php">
					<input type="hidden" name="entryvariant" value="editportfolioentry"/>
					<input type="hidden" name="entryid" value="<?php echo $editid;?>"/>
        			<div class="col-sm-6"> 
                        <div class="form-group">
                            <label>Entry Type</label>
                            <div class="input-group">
                              	<div class="input-group-addon">
                                	<i class="fa fa-file-text"></i>
                                </div>
                                <select name="portfoliotype" class="form-control">
                              		<option value="">--Choose--</option>
                              		<option value="client">Client</option>
                              		<option value="business">Business(Proprietary Software/Service)</option>
                              		<option value="opensource">Open Source</option>
                              		<option value="personal">Personal</option>
                              	</select>
                            </div><!-- /.input group -->
                      	</div><!-- /.form group -->
                    </div>
                    <div class="col-sm-6"> 
                        <div class="form-group">
                            <label>Entry Title</label>
                            <div class="input-group">
                                  <div class="input-group-addon">
                                    <i class="fa fa-file-text"></i>
                                  </div>
                                  <input type="text" class="form-control" name="portfoliotitle" value="<?php echo $row['projecttitle'];?>" Placeholder="Entry Title"/>
                            </div><!-- /.input group -->
                      	</div><!-- /.form group -->
                    </div>
        			<div class="col-sm-4"> 
                        <div class="form-group">
                            <label>Category</label>
                            <div class="input-group">
                                  <div class="input-group-addon">
                                    <i class="fa fa-file-text"></i>
                                  </div>
                                  <select name="catid" class="form-control">
                                  		<?php echo $pcdata['selectiondata'];?>
                                  </select>
                            </div><!-- /.input group -->
                      	</div><!-- /.form group -->
                    </div>
                    
                    <div class="col-sm-8 client-name <?php echo $clienthid;?>"> 
                        <div class="form-group">
                            <label>Client Name</label>
                            <div class="input-group">
                                  <div class="input-group-addon">
                                    <i class="fa fa-file-text"></i>
                                  </div>
                                  <input type="text" class="form-control" name="clientname" value="<?php echo $row['companyname'];?>" Placeholder="Client Name"/>
                            </div><!-- /.input group -->
                      	</div><!-- /.form group -->
                    </div>
                    <div class="col-md-4">
                		<div class="form-group">
                			<label>Cover Image </label>
            				<?php 
            					if($row['coverimagedata']['total']>0){
            						$coverdata=$row['coverimagedata'][0];
            				?>
            					<div class="contentpreview _image">
	                            	<a href="<?php echo $coverdata['location'];?>" data-lightbox="general_coverimage" data-src="<?php echo $coverdata['location'];?>">
	                            		<img src="<?php echo $coverdata['thumbnail'];?>">
	                            	</a>
	                            </div>

	                            <input type="hidden" class="form-control" name="coverimage_id" value="<?php echo $coverdata['id'];?>">
            				<?php 
            					}
            				?>
                			<div class="input-group">
                				<div class="input-group-addon">
                                	<i class="fa fa-file-image-o"></i>
                				</div>
                    			<input type="file" data-edittype="true" class="form-control" name="coverimage"/>
                			</div>
                		</div>
                	</div>
                	<div class="col-md-4">
                		<div class="form-group">
                			<label>Banner Image </label>
            				<?php 
            					if($row['bannerimagedata']['total']>0){
            						$bannerdata=$row['bannerimagedata'][0];
            				?>
            					<div class="contentpreview _image">
	                            	<a href="<?php echo $bannerdata['location'];?>" data-lightbox="general_bannerimage" data-src="<?php echo $bannerdata['location'];?>">
	                            		<img src="<?php echo $bannerdata['thumbnail'];?>">
	                            	</a>
	                            </div>
	                            <input type="hidden" class="form-control" name="bannerimage_id" value="<?php echo $bannerdata['id'];?>">
	                            <select class="form-control" name="bannerimage_delete">
				                    <option value="">Delete this?</option> 
				                    <option value="inactive">Yes</option> 
				                </select>
            				<?php 
            					}
            				?>
                			<div class="input-group">
                				<div class="input-group-addon">
                                	<i class="fa fa-file-image-o"></i>
                				</div>
                    			<input type="file" class="form-control" name="bannerimage"/>

                			</div>
                		</div>
                	</div>
                    <div class="col-md-12">
                        <div class="col-sm-6"> 
                            <div class="form-group">
                                <label>Project Start Date</label>
                                <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-file-text"></i>
                                      </div>
			              	  		  <input class="form-control" name="startdate" data-datepicker="true" value="<?php echo $row['periodstart'];?>" placeholder="Specify start date."/>
                                      
                                </div><!-- /.input group -->
                          	</div><!-- /.form group -->
                        </div>
                        <div class="col-sm-6"> 
                            <div class="form-group">
                                <label>Project End Date <small>Leave empty if entry is ongoing</small></label>
                                <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-file-text"></i>
                                      </div>
			              	  		  <input class="form-control" name="enddate" data-datepicker="true" value="<?php echo $row['periodend'];?>" placeholder="Specify End/Completion date."/>
                                      
                                </div><!-- /.input group -->
                          	</div><!-- /.form group -->
                        </div>
                    </div>
                    
                    <div class="col-sm-6"> 
                        <div class="form-group">
                            <label>Featured Entry? <small>Marks the entry to be featured for sections of the website.</small></label>
                            <div class="input-group">
                              	<div class="input-group-addon">
                                	<i class="fa fa-file-text-o"></i>
                                </div>
                                <select name="featured" class="form-control">
                              		<option value="">No</option>
                              		<option value="yes">Yes</option>
                              	</select>
                            </div><!-- /.input group -->
                      	</div><!-- /.form group -->
                    </div>

                    <div class="col-md-12 featured-details <?php echo $fdhid;?>">
                    	<input type="hidden" name="featuretype" value="appdisplay"/>
                    	<!-- Start Portfolio featured details section accordion -->
                    	<div class="box-group top_pad high_bottom_margin" id="contentaccordion">
							<div class="panel box box-primary overflowhidden">
						    	<div class="box-header with-border">
							        <h4 class="box-title">
							          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock_portfeaturededit">
							            <i class="fa fa-folder-o"></i>
							            	Portfolio Featured Details (<?php echo $fdtotal;?>) <small>(.jpeg,jpg images only) </small>
							          </a>
							        </h4>
						    	</div>
						      	<div id="headBlock_portfeaturededit" class="panel-collapse collapse">
	                            	<div class="col-md-12">
	                            		<div class="form-group">
	                            			<label>Display Type <small>Specifies which featured image set is used for full section displays</small></label>
	                            			<div class="input-group">
	                            				<div class="input-group-addon">
		                                        	<i class="fa fa-file-image-o"></i>
	                            				</div>
		                            			<select class="form-control" name="fdoption">
		                            				<option value="">--Choose--</option>
		                            				<option value="laptop">Laptop</option>
		                            				<option value="iphone">Apple Iphone</option>
		                            				<option value="android">Android Mobile</option>
		                            				<option value="tablet">Tablet</option>
		                            			</select>
	                            			</div>
	                            		</div>
	                            	</div>

	                            	<div class="col-md-12">
		                            	<div class="col-md-3">
		                            		<div class="form-group">
		                            			<label>Laptop Image <small>(Best Dimension W - 463px H - 268px)</small></label>
	                            				<?php 
	                            					$edittype="";
				                					if(isset($row['featureddata']['featuredadlaptop'])){
				                						$fadlaptopdata=$row['featureddata']['featuredadlaptop'];
				                						$edittype='data-edittype="true"';
				                				?>
				                					<div class="contentpreview _image">
						                            	<a href="<?php echo $fadlaptopdata['location'];?>" data-lightbox="general" data-src="<?php echo $fadlaptopdata['location'];?>">
						                            		<img src="<?php echo $fadlaptopdata['thumbnail'];?>">
						                            	</a>
						                            </div>
						                            <input type="hidden" class="form-control" name="fadlaptop_id" value="<?php echo $fadlaptopdata['id'];?>">
						                            <select class="form-control" name="fadlaptop_delete">
									                    <option value="">Delete this?</option> 
									                    <option value="inactive">Yes</option> 
									                </select>
				                				<?php 
				                					}
				                				?>
		                            			<div class="input-group">
		                            				<div class="input-group-addon">
			                                        	<i class="fa fa-file-image-o"></i>
		                            				</div>
			                            			<input type="file" <?php echo $edittype;?> class="form-control" name="featuredadlaptop"/>
		                            			</div>
		                            		</div>
		                            	</div>
		                            	<div class="col-md-3">
		                            		<div class="form-group">
		                            			<label>Apple Image <small>(Best W - 122px H - 198px)</small></label>
	                            				<?php 
	                            					$edittype="";
				                					if(isset($row['featureddata']['featuredadiphone'])&&$row['featureddata']['featuredadiphone']['id']!==""){
				                						$edittype='data-edittype="true"';
				                						$fadiphonedata=$row['featureddata']['featuredadiphone'];
				                				?>
				                					<div class="contentpreview _image">
						                            	<a href="<?php echo $fadiphonedata['location'];?>" data-lightbox="general" data-src="<?php echo $fadiphonedata['location'];?>">
						                            		<img src="<?php echo $fadiphonedata['thumbnail'];?>">
						                            	</a>
						                            </div>
						                            <input type="hidden" class="form-control" name="fadiphone_id" value="<?php echo $fadiphonedata['id'];?>">
						                            <select class="form-control" name="fadiphone_delete">
									                    <option value="">Delete this?</option> 
									                    <option value="inactive">Yes</option> 
									                </select>
				                				<?php 
				                					}
				                				?>
		                            			<div class="input-group">
		                            				<div class="input-group-addon">
			                                        	<i class="fa fa-file-image-o"></i>
		                            				</div>
			                            			<input type="file" <?php echo $edittype;?> class="form-control" name="featuredadiphone"/>
		                            			</div>
		                            		</div>
		                            	</div>
		                            	<div class="col-md-3">
		                            		<div class="form-group">
		                            			<label>Android Image <small>(Best W - 154px H - 275px)</small></label>
	                            				<?php 
	                            					$edittype="";
				                					if(isset($row['featureddata']['featuredadandroid'])&&$row['featureddata']['featuredadandroid']['id']!==""){
				                						$edittype='data-edittype="true"';
				                						$fadandroiddata=$row['featureddata']['featuredadandroid'];
				                				?>
				                					<div class="contentpreview _image">
						                            	<a href="<?php echo $fadandroiddata['location'];?>" data-lightbox="general" data-src="<?php echo $fadandroiddata['location'];?>">
						                            		<img src="<?php echo $fadandroiddata['thumbnail'];?>">
						                            	</a>
						                            </div>
						                            <input type="hidden" class="form-control" name="fadandroid_id" value="<?php echo $fadandroiddata['id'];?>">
						                            <select class="form-control" name="fadandroid_delete">
									                    <option value="">Delete this?</option> 
									                    <option value="inactive">Yes</option> 
									                </select>
				                				<?php 
				                					}
				                				?>
		                            			<div class="input-group">
		                            				<div class="input-group-addon">
			                                        	<i class="fa fa-file-image-o"></i>
		                            				</div>
			                            			<input type="file" <?php echo $edittype;?> class="form-control" name="featuredadandroid"/>
		                            			</div>
		                            		</div>
		                            	</div>
		                            	<div class="col-md-3">
		                            		<div class="form-group">
		                            			<label>Tablet Image <small>(Best W - 463px H - 268px)</small></label>
	                            				<?php 
													$edittype="";
				                					if(isset($row['featureddata']['featuredadtablet'])&&$row['featureddata']['featuredadtablet']['id']!==""){
				                						$edittype='data-edittype="true"';
				                						$fadtabletdata=$row['featureddata']['featuredadtablet'];
				                				?>
				                					<div class="contentpreview _image">
						                            	<a href="<?php echo $fadtabletdata['location'];?>" data-lightbox="general" data-src="<?php echo $fadtabletdata['location'];?>">
						                            		<img src="<?php echo $fadtabletdata['thumbnail'];?>">
						                            	</a>
						                            </div>
						                            <input type="hidden" class="form-control" name="fadtablet_id" value="<?php echo $fadtabletdata['id'];?>">
						                            <select class="form-control" name="fadtablet_delete">
									                    <option value="">Delete this?</option> 
									                    <option value="inactive">Yes</option> 
									                </select>
				                				<?php 
				                					}
				                				?>
		                            			<div class="input-group">
		                            				<div class="input-group-addon">
			                                        	<i class="fa fa-file-image-o"></i>
		                            				</div>
			                            			<input type="file" <?php echo $edittype;?> class="form-control" name="featuredadtablet"/>
		                            			</div>
		                            		</div>
		                            	</div>
	                            	</div>
								</div>
                    		</div>
                    	</div>		
                    	<!-- end edit section accordion -->
                    </div>


                    <div class="col-sm-6"> 
                        <div class="form-group">
                            <label>Attach gallery?</label>
                            <div class="input-group">
                              	<div class="input-group-addon">
                                	<i class="fa fa-file-image-o"></i>
                                </div>
                                <select name="galleryattach" class="form-control">
                              		<option value="">No</option>
                              		<option value="yes">Yes</option>
                              	</select>
                            </div><!-- /.input group -->
                      	</div><!-- /.form group -->
                    </div>

                    <!-- Start gallery entry section accordion -->
                	<div class="box-group top_pad portgallery high_bottom_margin <?php echo $galhid;?>" id="contentaccordion">
						<div class="panel box box-primary overflowhidden">
					    	<div class="box-header with-border">
						        <h4 class="box-title">
						          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock_group2edit">
						            <i class="fa fa-file-image-o"></i>
						            	Create/Edit Gallery Images (<?php echo $galtotal;?>)
						          </a>
						        </h4>
					    	</div>
					      	<div id="headBlock_group2edit" class="panel-collapse collapse">
	                            <div class="col-md-12 editportgallery-field-hold ">
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
			                            		data-src="<?php echo $gallerydata['location'];?> " 
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
				                              <input type="text" class="form-control" name="caption<?php echo $t;?>" value="<?php echo $gallerydata['caption'];?>" placeholder="Provide Caption text">
				                            </div><!-- /.input group -->
				                        </div><!-- /.form group -->
										
										<div class="form-group coptionpoint _hold">
				                            <label>Details</label>
				                            <div class="input-group">
					                            <div class="input-group-addon">
					                                <i class="fa fa-file-text"></i>
					                            </div>
					                            <textarea class="form-control" rows="3" name="details<?php echo $t;?>" placeholder="Give details if any">
					                            	<?php echo $gallerydata['details'];?>
					                            </textarea>
					                            <select class="form-control" name="galimage<?php echo $t;?>_delete">
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
	                            <div class="col-md-12 portgallery-field-hold ">
		                        	<h3>Maximum of 10 images at a time</h3>
		                            <input type="hidden" name="portgallerycount" class="form-control" value="<?php echo $ft;?>" data-counter="true"/>
		                        	<div class="col-md-3 multi_content_hold" data-type="triggerprogenitor" data-cid="<?php echo $ft;?>" data-name="portgallery">
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
		                        	<div name="portgalleryentrypoint" data-marker="true"></div>
		                        	<input name="portgallerydatamap" type="hidden" data-map="true" value="galimage-:-input<|>
		                        	caption-:-input<|>
		                        	details-:-textarea">
		                        	<a href="##" class="generic_addcontent_trigger" 
		                        		data-type="triggerformaddlib" 
		                        		data-name="portgallerycount_addlink" 
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

                    


                    <div class="col-sm-6"> 
                        <div class="form-group">
                            <label>Attach Video? <small>Add video(s) to this portfolio entry.</small></label>
                            <div class="input-group">
                              	<div class="input-group-addon">
                                	<i class="fa fa-file-text-o"></i>
                                </div>
                                <select name="vidattach" class="form-control">
                              		<option value="">No</option>
                              		<option value="yes">Yes</option>
                              	</select>
                            </div><!-- /.input group -->
                      	</div><!-- /.form group -->
                    </div>
                    <!-- Start video entry section accordion -->
                	<div class="box-group top_pad portvideos high_bottom_margin <?php echo $vidhid;?>" id="contentaccordion">
						<div class="panel box box-primary overflowhidden">
					    	<div class="box-header with-border">
						        <h4 class="box-title">
						          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock_group4edit">
						            <i class="fa fa-file-image-o"></i>
						            	Creat/Edit Video Entries (<?php echo $vidtotal;?>)
						          </a>
						        </h4>
					    	</div>
					      	<div id="headBlock_group4edit" class="panel-collapse collapse">
                            	<?php 
                					if($row['viddata']['total']>0){
                				
                				?>
                            	<div class="col-md-12 editportvideo-field-hold ">
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
                							if($viddata['portvwebm']!==""){
                								$sourcetotal.='<source src="'.$viddata['portvwebm'].'" type="video/webm"/>';
                							}
                							if($viddata['portv3gp']!==""){
                								$sourcetotal.='<source src="'.$viddata['portv3gp'].'" type="video/3gp"/>';
                							}
                							if($viddata['portvflv']!==""){
                								$sourcetotal.='<source src="'.$viddata['portvflv'].'" type="video/flv"/>';
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
	                							$vidout=$viddata['portvembed'];
                							}
                							$cmap=json_encode($viddata);
                				?>
	                						<div class="col-md-4 multi_content_hold" data-type="triggerprogeny" data-cid="<?php echo $ft;?>">
		                        				<h4 class="multi_content_countlabels">Video (Entry <?php echo $t;?>)</h4>
				                        		<div class="col-sm-12 coptionpoint _hold">
				                        			<div class="form-group">
							                            <label>Entry Type</label>
							                            <div class="input-group">
							                              	<div class="input-group-addon">
							                                	<i class="fa fa-file-text"></i>
							                              	</div>
							                              	<select name="portvtype<?php echo $t;?>" class="form-control">
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
				                        		<div class="col-sm-12 coptionpoint _hold portvideolocal <?php echo $lhid;?>">
				                        			<div class="form-group">
							                            <label>Video WEBM</label>
							                            <div class="input-group">
								                            <div class="input-group-addon">
								                                <i class="fa fa-file-text"></i>
								                            </div>
								                            <input type="file" <?php echo $vet;?> class="form-control" name="portvwebm<?php echo $t;?>" placeholder="Choose file">
								                            <?php 
								                              	if($viddata['portvwebm']!==""){

								                            ?>
									                            <select class="form-control" name="portvwebm<?php echo $t;?>_delete">
												                    <option value="">Delete this Entry?</option> 
												                    <option value="inactive">Yes</option> 
												                </select>
								                            <?php 
							                              		}
							                              	?>
								                            <div class="input-group-addon">
								                              	<?php 
								                              		if($viddata['portvwebm']!==""){
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
							                            <label>Video FLV</label>
							                            <div class="input-group">
								                            <div class="input-group-addon">
								                                <i class="fa fa-file-text"></i>
								                            </div>
								                            <input type="file" <?php echo $vet;?> class="form-control" name="portvflv<?php echo $t;?>" placeholder="Choose File">
								                            <?php 
								                              	if($viddata['portvflv']!==""){
								                            ?>
									                            <select class="form-control" name="portvflv<?php echo $t;?>_delete">
												                    <option value="">Delete this Entry?</option> 
												                    <option value="inactive">Yes</option> 
												                </select>
								                            <?php 
							                              		}
							                              	?>
								                            <div class="input-group-addon">
								                              	<?php 
								                              		if($viddata['portvflv']!==""){
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
							                            <label>Video 3GP</label>
							                            <div class="input-group">
								                            <div class="input-group-addon">
								                                <i class="fa fa-file-text"></i>
								                            </div>
								                            <input type="file" <?php echo $vet;?> class="form-control" name="portv3gp<?php echo $t;?>" placeholder="">
								                            <?php 
								                              	if($viddata['portv3gp']!==""){
								                            ?>
									                            <select class="form-control" name="portv3gp<?php echo $t;?>_delete">
												                    <option value="">Delete this Entry?</option> 
												                    <option value="inactive">Yes</option> 
												                </select>
								                            <?php 
							                              		}
							                              	?>
								                            <div class="input-group-addon">

								                              	<?php 
								                              		if($viddata['portv3gp']!==""){

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

				                        		<div class="col-sm-12 coptionpoint _hold portvideolocal <?php echo $lhid;?>">
				                        			<div class="form-group">
							                            <label>Video Caption</label>
							                            <div class="input-group">
								                            <div class="input-group-addon">
								                                <i class="fa fa-file-text"></i>
								                            </div>
								                            <input type="text" class="form-control" name="portvcaption<?php echo $t;?>" value="<?php echo $viddata['caption'];?>" placeholder="Specify a caption for the video">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-12 coptionpoint _hold portvideoembed <?php echo $ehid;?>">
				                        			<div class="form-group">
							                            <label>Embed</label>
							                            <div class="input-group">
								                            <div class="input-group-addon">
								                                <i class="fa fa-file-text"></i>
								                            </div>
								                            <textarea class="form-control" 
								                            		rows="3" 
								                            		name="portvembed<?php echo $t;?>" 
								                            		placeholder="Embed Code"
								                            ><?php echo $viddata['portvembed'];?></textarea>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-12 coptionpoint _hold">
				                        			<div class="form-group">
							                            <div class="input-group">
								                            <div class="input-group-addon">
								                                <i class="fa fa-trash"></i>
								                            </div>
									                        <input type="hidden" class="form-control" name="portvideo<?php echo $t;?>_id" value="<?php echo $viddata['id'];?>"/>
									                        <input type="hidden" class="form-control" name="portvideo<?php echo $t;?>_map" value='<?php echo $viddata['originalmap'];?>'/>
							                        		<select class="form-control" name="portvideo<?php echo $t;?>_delete">
											                    <option value="">Delete this Video?</option> 
											                    <option value="inactive">Yes</option> 
											                </select>
											            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        	</div>
								<?php 								
                						}	
                				?>
                            	</div>
                            	<?php 
                					}
                					$ft=$row['viddata']['total']+1;
                					// $vet='data-edittype="true"';
                					$vet='';
                					if($ft==1){
                						$vet="";
                					}
                				?>
	                            <div class="col-md-12 portvideo-field-hold ">
	                            	<input type="hidden" name="portvideocount" class="form-control" value="<?php echo $ft;?>" data-counter="true"/>
		                        	<h3>Maximum of 1 video at a time</h3>
		                        	<div class="col-md-12 multi_content_hold" data-type="triggerprogenitor" data-cid="<?php echo $ft;?>" data-name="portvideo">
		                        		<h4 class="multi_content_countlabels">Video (Entry <?php echo $ft;?>)</h4>
		                        		<div class="col-sm-12">
		                        			<div class="form-group">
					                            <label>Entry Type</label>
					                            <div class="input-group">
					                              	<div class="input-group-addon">
					                                	<i class="fa fa-file-text"></i>
					                              	</div>
					                              	<select name="portvtype<?php echo $ft;?>" class="form-control">
			                                      		<option value="">--Choose--</option>
			                                      		<option value="local">Local</option>
			                                      		<option value="embed">Embed</option>
			                                      	</select>
					                            </div><!-- /.input group -->
					                        </div><!-- /.form group -->
		                        		</div>
		                        		<div class="col-sm-4 portvideolocal hidden">
		                        			<div class="form-group">
					                            <label>Video WEBM</label>
					                            <div class="input-group">
					                              <div class="input-group-addon">
					                                <i class="fa fa-file-text"></i>
					                              </div>
					                              <input type="file" <?php echo $vet;?> class="form-control" name="portvwebm<?php echo $ft;?>" placeholder="Choose file">
					                            </div><!-- /.input group -->
					                        </div><!-- /.form group -->
		                        		</div>
		                        		<div class="col-sm-4 portvideolocal hidden">
		                        			<div class="form-group">
					                            <label>Video FLV</label>
					                            <div class="input-group">
					                              <div class="input-group-addon">
					                                <i class="fa fa-file-text"></i>
					                              </div>
					                              <input type="file"  class="form-control" name="portvflv<?php echo $ft;?>" placeholder="Choose File"></textarea>
					                            </div><!-- /.input group -->
					                        </div><!-- /.form group -->
		                        		</div>
		                        		<div class="col-sm-4 portvideolocal hidden">
		                        			<div class="form-group">
					                            <label>Video 3GP</label>
					                            <div class="input-group">
					                              <div class="input-group-addon">
					                                <i class="fa fa-file-text"></i>
					                              </div>
					                              <input type="file"  class="form-control" name="portv3gp<?php echo $ft;?>" placeholder=""></textarea>
					                            </div><!-- /.input group -->
					                        </div><!-- /.form group -->
		                        		</div>
		                        		<div class="col-sm-12 portvideolocal hidden">
		                        			<div class="form-group">
					                            <label>Video Caption</label>
					                            <div class="input-group">
					                              <div class="input-group-addon">
					                                <i class="fa fa-file-text"></i>
					                              </div>
					                              <input type="text" class="form-control" name="portvcaption<?php echo $ft;?>" placeholder="Specify a caption for the video">
					                            </div><!-- /.input group -->
					                        </div><!-- /.form group -->
		                        		</div>
		                        		<div class="col-sm-9 portvideoembed hidden">
		                        			<div class="form-group">
					                            <label>Embed</label>
					                            <div class="input-group">
					                              <div class="input-group-addon">
					                                <i class="fa fa-file-text"></i>
					                              </div>
					                              <textarea class="form-control" rows="3" name="portvembed<?php echo $ft;?>" placeholder="Embed Code"></textarea>
					                            </div><!-- /.input group -->
					                        </div><!-- /.form group -->
		                        		</div>
		                        	</div>
		                        	<div name="portvideoentrypoint" data-marker="true"></div>
		                        	<input name="portvideodatamap" type="hidden" data-map="true" value="portvtype-:-select<|>
		                        	portvwebm-:-input<|>
		                        	portvflv-:-input<|>
		                        	portv3gp-:-input<|>
		                        	portvcaption-:-input<|>
		                        	portvdetails-:-input">
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

                	<div class="col-sm-6"> 
                        <div class="form-group">
                            <label>Attach Audio? <small>Add Audio(s) to this portfolio entry.</small></label>
                            <div class="input-group">
                              	<div class="input-group-addon">
                                	<i class="fa fa-file-text-o"></i>
                                </div>
                                <select name="audioattach" class="form-control">
                              		<option value="">No</option>
                              		<option value="yes">Yes</option>
                              	</select>
                            </div><!-- /.input group -->
                      	</div><!-- /.form group -->
                    </div>
                	<!-- Start audio entry section accordion -->
                	<div class="box-group top_pad portaudio high_bottom_margin <?php echo $audhid;?>" id="contentaccordion">
						<div class="panel box box-primary overflowhidden">
					    	<div class="box-header with-border">
						        <h4 class="box-title">
						          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock_group5edit">
						            <i class="fa fa-file-image-o"></i>
						            	Create/Edit Audio Entries (<?php echo $audtotal;?>)
						          </a>
						        </h4>
					    	</div>
					      	<div id="headBlock_group5edit" class="panel-collapse collapse ">
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
		                							$audioout=$audiodata['portaembed'];
	                							}
	                				?>
	                						<div class="col-md-4 multi_content_hold" data-type="triggerprogeny" data-cid="<?php echo $t;?>" data-name="portaudio">
				                        		<h4 class="multi_content_countlabels">Audio (Entry <?php echo $t;?>)</h4>
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
							                              	<select name="portatype<?php echo $t;?>" class="form-control">
					                                      		<option value="">--Choose--</option>
					                                      		<option value="local">Local</option>
					                                      		<option value="embed">Embed</option>
					                                      	</select>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-12 coptionpoint _hold portaudiolocal <?php echo $lhid?>">
				                        			<div class="form-group">
							                            <label>Audio File</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="file" class="form-control" data-edittype="true" name="portaudio<?php echo $t;?>" placeholder="">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-12 coptionpoint _hold portaudioembed <?php echo $ehid?>">
				                        			<div class="form-group">
							                            <label>Embed</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <textarea class="form-control" rows="3" name="portaembed<?php echo $t;?>" placeholder="Embed Code"
							                              	><?php echo $audiodata['portaembed'];?></textarea>
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-12 coptionpoint _hold portaudiolocal <?php echo $lhid?>">
				                        			<div class="form-group">
							                            <label>Audio Caption</label>
							                            <div class="input-group">
							                              <div class="input-group-addon">
							                                <i class="fa fa-file-text"></i>
							                              </div>
							                              <input type="text" class="form-control" name="portacaption<?php echo $t;?>" value="<?php echo $audiodata['portacaption'];?>" placeholder="Specify a caption for the audio file">
							                            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        		<div class="col-sm-12 coptionpoint _hold">
				                        			<div class="form-group">
							                            <div class="input-group">
								                            <div class="input-group-addon">
								                                <i class="fa fa-trash"></i>
								                            </div>
								                            <input type="hidden" class="form-control" name="portaudio<?php echo $t;?>_id" value="<?php echo $audiodata['id'];?>"/>
							                        		<select class="form-control" name="portaudio<?php echo $t;?>_delete">
											                    <option value="">Delete Audio Entry?</option> 
											                    <option value="inactive">Yes</option> 
											                </select>
											            </div><!-- /.input group -->
							                        </div><!-- /.form group -->
				                        		</div>
				                        	</div>
									<?php 								
	                						}	
	                				?>
	                            		</div>
	                            	<?php 
	                					}
	                					$ft=$row['audiodata']['total']+1;
	                					// $aet='data-edittype="true"';
	                					$aet="";
	                					if($ft==1){
	                						$aet="";
	                					}
	                				?>
	                            <div class="col-md-12 portaudio-field-hold ">
	                            	<input type="hidden" name="portaudiocount" class="form-control" value="<?php echo $ft;?>" data-counter="true"/>
		                        	<h3>New Entries Maximum of 1 Audio entry at a time</h3>
		                        	<div class="col-md-12 multi_content_hold" data-type="triggerprogenitor" data-cid="<?php echo $ft;?>" data-name="portaudio">
		                        		<h4 class="multi_content_countlabels">Audio (Entry <?php echo $ft;?>)</h4>
		                        		<div class="col-sm-4">
		                        			<div class="form-group">
					                            <label>Entry Type</label>
					                            <div class="input-group">
					                              	<div class="input-group-addon">
					                                	<i class="fa fa-file-text"></i>
					                              	</div>
					                              	<select name="portatype<?php echo $ft;?>" <?php echo $aet;?> class="form-control">
			                                      		<option value="">--Choose--</option>
			                                      		<option value="local">Local</option>
			                                      		<option value="embed">Embed</option>
			                                      	</select>
					                            </div><!-- /.input group -->
					                        </div><!-- /.form group -->
		                        		</div>
		                        		<div class="col-sm-4 portaudiolocal hidden">
		                        			<div class="form-group">
					                            <label>Audio File</label>
					                            <div class="input-group">
					                              <div class="input-group-addon">
					                                <i class="fa fa-file-text"></i>
					                              </div>
					                              <input type="file" <?php echo $aet;?> class="form-control" name="portaudio<?php echo $ft;?>" placeholder="">
					                            </div><!-- /.input group -->
					                        </div><!-- /.form group -->
		                        		</div>
		                        		<div class="col-sm-4 portaudioembed hidden">
		                        			<div class="form-group">
					                            <label>Embed</label>
					                            <div class="input-group">
					                              <div class="input-group-addon">
					                                <i class="fa fa-file-text"></i>
					                              </div>
					                              <textarea class="form-control" rows="3" name="portaembed<?php echo $ft;?>" placeholder="Embed Code"></textarea>
					                            </div><!-- /.input group -->
					                        </div><!-- /.form group -->
		                        		</div>
		                        		<div class="col-sm-4 portaudiolocal hidden">
		                        			<div class="form-group">
					                            <label>Audio Caption</label>
					                            <div class="input-group">
					                              <div class="input-group-addon">
					                                <i class="fa fa-file-text"></i>
					                              </div>
					                              <input type="text" class="form-control" name="portacaption<?php echo $ft;?>" placeholder="Specify a caption for the audio file">
					                            </div><!-- /.input group -->
					                        </div><!-- /.form group -->
		                        		</div>
		                        	</div>
		                        	<div name="portaudioentrypoint" data-marker="true"></div>
		                        	<input name="portaudiodatamap" type="hidden" data-map="true" value="portatype-:-select<|>
		                        	portaudio-:-input<|>
		                        	portacaption-:-input<|>
		                        	portaembed-:-input">
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

                    <div class="col-md-12">
                        <div class="col-md-6">
                            <label>Short Details <small>Text here is used in featured displays as well</small>:</label>
                            <textarea class="form-control" rows="3" name="shorttext" 
                            		data-wMax="40" 
                            		data-wMax-fname="<?php echo $formtruetype;?>" 
                            		data-wMax-type="word" 
                            		placeholder="Short Description For the current entry"
                            ><?php echo $row['shorttext'];?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Entry Tags <small>Comma seperated list of descriptive related categories.</small>:</label>
                            <textarea class="form-control" 
                            		rows="3" name="tags" 
                            		placeholder="Tags. e.g Fun,Video game, role Playing"
                            ><?php echo $row['tags'];?></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Full Entry Details:</label>
                        <textarea class="form-control" rows="3" name="entrydetails" data-mce="true" id="postersmallthree_1edit" placeholder="The Entry Details"><?php echo $row['details'];?></textarea>
                    </div>
                    
                    <!-- Start Website/Social section accordion -->
                	<div class="box-group top_pad" id="contentaccordion">
						<div class="panel box box-primary overflowhidden">
					    	<div class="box-header with-border">
						        <h4 class="box-title">
						          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock_group1edit">
						            <i class="fa fa-facebook"></i>
						            <i class="fa fa-twitter"></i>
						            <i class="fa fa-google"></i>
						            <i class="fa fa-linkedin"></i>
						            <i class="fa fa-gear fa-spin"></i> 
						            	Portfolio Entry Website/Socials
						          </a>
						        </h4>
					    	</div>
					      	<div id="headBlock_group1edit" class="panel-collapse collapse in">
                                <div class="col-md-12 social-field-hold">
                                	<!-- data-valcount="2" data-valset="1,2,3" -->
                                    <input name="socialcount" type="hidden" value="7" data-counter="true" class="form-control"/>
                                    <?php 
	                                    $sdarr=array();
	                                    $sdicon=array();
	                                    // the social data for the current entry
	                                    // if any.
	                                    $socialdata=array();
	                                    if($row['projectsocials']!==""){
	                                    	$socialdata=JSONtoPHP($row['projectsocials']);
	                                    }
	                                    // prep the default social media info
	                                    $sdarr[0]="Website";$sdicon[0]="fa-chrome";
	                                    $sdarr[1]="Facebook";$sdicon[1]="fa-facebook";
	                                    $sdarr[2]="Twitter";$sdicon[2]="fa-twitter";
	                                    $sdarr[3]="Googleplus";$sdicon[3]="fa-google-plus";
                                    	$sdarr[4]="Linkedin";$sdicon[4]="fa-linkedin";
	                                    $sdarr[5]="Pinterest";$sdicon[5]="fa-pinterest";
	                                    $sdarr[6]="Instagram";$sdicon[6]="fa-instagram";
	                                    $sdarr[7]="Github";$sdicon[7]="fa-github";
	                                    // $sdarr[]="Skype";$sdicon[]="fa-skype";
                                    	for ($i = 1; $i <=7 ; $i++) {
                                    		$t=$i-1	;
                                    		$ttype="triggerprogeny";
                                    		if($t==0){
                                    			$ttype="triggerprogenitor";
                                    		}
                                    		$dname=$sdarr[$t];
                                    		$icon=$sdicon[$t];
                                    		$csurl="";
                                    		if(isset($socialdata['socialentry'.$i.''])){
                                    			$csurl=$socialdata['socialentry'.$i.'']['socialurl'];
                                    		}
                                  ?>
		                                	<div class="col-md-3 multi_content_hold " data-name="social" data-type="<?php echo $ttype;?>" data-cid="<?php echo $i?>">
				                                <div class="form-group">
				                                    <label><?php echo $dname;?> Url</label>
				                                    <div class="input-group">
					                                    <div class="input-group-addon">
					                                        <i class="fa <?php echo $icon;?>"></i>
					                                    </div>
		                                    			<input name="socialname<?php echo $i;?>" type="hidden" value="<?php echo $dname;?>" class="form-control"/>
		                                    			<input name="socialicon<?php echo $i;?>" type="hidden" value="<?php echo $icon;?>" class="form-control"/>
					                                    <input name="socialurl<?php echo $i;?>" class="form-control" type="url" value="<?php if(isset($csurl)){echo $csurl;}?>" Placeholder="The web address."/>				                                    
					                                </div><!-- /.input group -->
				                                </div><!-- /.form group -->
		                                	</div>
                                	<?php
                                		}
                                	?>
                                	<div name="socialentrypoint" data-marker="true"></div>
                                	<input name="socialdatamap" type="hidden" value="socialname-:-input<|>
					                      socialicon-:-input<|>
			                      		  socialurl-:-input" class="form-control"/>
                                </div>
                            </div>
                		</div>
                	</div>		
                	<!-- end edit section accordion -->

                    <div class="col-sm-6"> 
                        <div class="form-group">
                            <label>Password Lock this entry?</label>
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
                            <label>Create/Change Password</label>
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
                    <div class="col-md-12">
                    	<div class="form-group">
	                        <label>Enable / Disable</label>
	                        <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-exclamation-triangle"></i>
                                </div>
		                        <select name="status" class="form-control">
		                        	<option value="">--Choose--</option>
		                        	<option value="active">Active</option>
		                        	<option value="inactive">Inactive</option>
						  	    </select>
						  	</div>
					  	</div>
                    </div>
                    <input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>
		            <input type="hidden" name="fdgen" value="32M<?php //echo $inimaxupload;?>" data-fdgen="true"/>
		            <!-- <input type="hidden" name="fdgentotal" value="0" data-fdgen-total="true"/> -->
                    <input type="hidden" name="extraformdata" value="portfoliotype-:-select<|>
	                    portfoliotitle-:-input<|>
	                    catid-:-select<|>
	                    clientname-:-input-:-[group-|-portfoliotype-|-select-|-client]<|>
	                    coverimage-:-input|image<|>
	                    bannerimage-:-input|image<|>
	                    startdate-:-input<|>
	                    enddate-:-input<|>
	                    galleryattach-:-select<|>
	                    egroup|data-:-[portgallerycount>|<
	                    galimage-|-input|image>|<
	                    caption-|-input>|<
	                    details-|-textarea]-:-groupfall[1-2,2-1,1-3]<|>
	                    featured-:-select<|>
	                    fdoption-:-select-:-[group-|-featured-|-select-|-yes]<|>
	                    featuredadlaptop-:-input|image|jpeg,jpg-:-[group-|-featured-|-select-|-yes-|-fdoption-|-select-|-laptop]<|>
	                    featuredadiphone-:-input|image|jpeg,jpg-:-[group-|-featured-|-select-|-yes-|-fdoption-|-select-|-apple]<|>
	                    featuredadandroid-:-input|image|jpeg,jpg-:-[group-|-featured-|-select-|-yes-|-fdoption-|-select-|-android]<|>
	                    featuredadtablet-:-input|image|jpeg,jpg-:-[group-|-featured-|-select-|-yes-|-fdoption-|-select-|-tablet]<|>
	                    vidattach-:-select<|>
	                    egroup|data-:-[portvideocount>|<
		                    portvtype-|-select>|<
		                    portvwebm-|-input|video|webm-|-(group-*-portvtype-*-select-*-local-*-portvflv-*-input-*-*null*-*-portv3gp-*-input-*-*null*)>|<
		                    portvflv-|-input|video|flv-|-(group-*-portvtype-*-select-*-local-*-portvwebm-*-input-*-*null*-*-portv3gp-*-input-*-*null*)>|<
		                    portv3gp-|-input|video|3gp-|-(group-*-portvtype-*-select-*-local-*-portvwebm-*-input-*-*null*-*-portvflv-*-input-*-*null*)>|<
		                    portvcaption-|-input-|-(group-*-portvtype-*-select-*-local)>|<
		                    portvembed-|-textarea-|-(group-*-portvtype-*-select-*-embed)
	                    ]-:-groupfall[1-2,2-1,3,4,6-1]-:-[single-|-vidattach-|-select-|-yes-|-portvtype]<|>
	                    audioattach-:-select<|>
	                    egroup|data-:-[portaudiocount>|<
		                    portatype-|-select>|<
		                    portaudio-|-input|audio|mp3-|-(group-*-portatype-*-select-*-local)>|<
		                    portacaption-|-input-|-(group-*-portatype-*-select-*-local)>|<
		                    portaembed-|-textarea-|-(group-*-portatype-*-select-*-embed)
	                    ]-:-groupfall[1-2,2-1,4-1,1-4,2-3]-:-[single-|-audioattach-|-select-|-yes-|-portatype]<|>
	                    shorttext-:-textarea<|>
	                    tags-:-textarea<|>
	                    entrydetails-:-textarea<|>
	                    egroup|data-:-[socialcount>|<
                      	socialname-|-input>|<
                      	socialicon-|-input>|<
                      	socialurl-|-input]-:-groupfall[1-2]<|>
                      	pwrdd-:-select<|>
                      	pwrd-:-input-:-[group-|-pwrdd-|-select-|-yes]"/>
                    
                    <input type="hidden" name="errormap" value="portfoliotype-:-Please Specify the type for the current entry<|>
	                    portfoliotitle-:-Provide the title of this portfolio entry<|>
	                    catid-:-Choose a category for the current entry.<|>
	                    clientname-:-Specify the name of the client the entry was done for<|>
	                    coverimage-:-Please provide a cover image for this entry<|>
	                    bannerimage-:-NA<|>
	                    startdate-:-Specify the start date<|>
	                    enddate-:-NA<|>
	                    galleryattach-:-NA<|>
	                    egroup|data-:-[Please provide a valid image file>|<
	                    Specify the caption text>|<
	                    NA]<|>
	                    featured-:-NA<|>
	                    fdoption-:-Specify the feature type Display<|>
	                    featuredadlaptop-:-Please provide the laptop demo image<|>
	                    featuredadiphone-:-Please provide the Iphone demo image<|>
	                    featuredadandroid-:-Please provide the android demo image<|>
	                    featuredadtablet-:-Please provide the tablet demo image<|>
	                    vidattach-:-NA<|>
	                    egroup|data-:-[Specify the type for this entry>|<
		                    Please provide a valid webm video>|<
		                    NA>|<
		                    NA>|<
		                    NA>|<
		                    Please provide the embed code
	                    ]<|>
	                    audioattach-:-NA<|>
	                    egroup|data-:-[Specify the type for this entry>|<
		                    Please provide a valid mp3 audio file>|<
		                    NA>|<
		                    Please provide the audio embed code
	                    ]<|>
	                    shorttext-:-Please give a short description for this entry, less than 300 characters<|>
	                    tags-:-Provide tags describing this project<|>
	                    entrydetails-:-provide the full details for the entry<|>
	                    egroup|data-:-[NA>|<
                      	NA>|<
                      	NA]<|>
                      	pwrdd-:-NA<|>
                      	pwrd-:-Please provide a password."/>

	                <div class="col-md-12 clearboth">
		                <div class="box-footer">
		                    <input type="button" class="btn btn-danger" name="createentry" data-formdata="<?php echo $formtruetype;?>" onclick="submitCustom('<?php echo $formtruetype;?>','complete')" value="Create/Update"/>
		                     <small>Maximum file upload size for server is:<?php echo $inimaxupload;?></small>
		                </div>
		            </div>
            	</form>	
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
					curmcethreeposter['width']="100%";

					curmcethreeposter['height']="300px";
					curmcethreeposter['toolbar2addon']=" | preview code ";
					callTinyMCEInit("textarea[id*=postersmallthree]",curmcethreeposter);
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
					//Date range picker
					if($(document).datepicker){
						$('[data-datetimepicker]').datetimepicker({
						    format:"YYYY-MM-DD HH:mm",
						    keepOpen:true
						    // showClose:true
						    // debug:true
						})
						$('[data-datepicker]').datetimepicker({
						    format:"YYYY-MM-DD",
						    keepOpen:true
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
<?php		
		}else{	
?>
			<div class="row">
				<div class="callout callout-danger _nogreen _border_dark clearboth marginbottom">
	                <h2>Unavailable</h2>
	                The entry to be viewed is either unavailable or has been deleted.
	            </div>
	        </div>
		
<?php		
		}	
?>
<?php			
		}else if($viewtype=="paginate"){
			// for pagination there are variables available which are common
			// to the 'paginationpagesout' displaytype in the display.php
			// file
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
			$vtype=$cdata[0];
			$viewer=$cdata[1];
			$type=isset($cdata[2])?$cdata[2]:"";
			
			// check to see if the type section is in its compound state
			$nt=strpos($type, "][");
			if ($nt===0||$nt===true||$nt>0) {
				# code...
				$rdata=explode("][", $type);
				// convert $type into an array
				$type[0]=$rdata[0];
				$type[1]=$rdata[1];
			}
			
			// handle the data in case its a search
			if($vtype=="portfoliocategorysearch"){
				$viewer[0]=$viewer;
				$viewer[1]=$type[0];
				$viewer[2]=$type[1];
				unset($type);
				$type="";
			}
			$pagout=getAllPortfolioEntries($viewer,$limit,$type,$data);
			echo $pagout['adminoutputtwo'];
		}
	}
?>

