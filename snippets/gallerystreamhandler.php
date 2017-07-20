<?php
	
	$contentName = array(
						'portfoliogallerystream' => 'Portfolio Gallery'
					);
	$formtruetype="gallerystream";
	!isset($pagetype)?$pagetype="portfoliogallerystream":$pagetype=$pagetype;
	if(isset($entrytype)){
		$pagetype=$entrytype;
	}
	$contentname=isset($contentName["$pagetype"])?$contentName["$pagetype"]:"";
	;
	$newcontentname=isset($contentName["$pagetype"])?substr($contentname, 0,strlen($contentname)-1):"";
	// $datatype[0]=!isset($pagetype)?"hometopcollageboxintro":$pagetype."intro";  
	/*if(isset($contentgroupdatasingle)){
	    unset($contentgroupdatasingle);
	}
	if(isset($contentgroupdatageneral)){
	    unset($contentgroupdatageneral);
	}*/


  	// $contentgroupdatageneral['evaldata']['general']=array();

	
	// get the general data for the current entry
	$outsdata=getGalleryStream("admin","","$pagetype");
	  
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
			              <input type="hidden" name="entryvariant" value="creategallerystream"/>
						  <input type="hidden" name="type" value="<?php echo $pagetype;?>"/>
			              <?php
				              if ($pagetype=="portfoliogallerystream") {
				              	# code...
			              ?>
				              <div class="col-md-12" name="surveysliderpoint">
				              		<div class="col-md-12 dogalleryslides multi_content_hold_generic">
			                        	<h3>Maximum of 10 images at a time</h3>
			                        	<div class="col-md-3 multi_content_hold" data-type="triggerprogenitor" data-cid="1" data-name="galleryslides">
			                        		<h4 class="multi_content_countlabels">Portfolio Gallery (Entry 1)</h4>
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
			                        	<input name="galleryslidescount" type="hidden" value="1" data-valset="1,2" data-valcount="1" data-counter="true"/>
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
			                        <!-- form control section -->
				                    <input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>
				                    <input type="hidden" name="extraformdata" value="egroup|data-:-[galleryslidescount>|<
				                      				galimage-|-input|image>|<
				                      				caption-|-input>|<
				                      				details-|-textarea
				                      				]-:-groupfall[1-2,2-1,1-3]"/>
				                      <!--  -->
				                    <input type="hidden" name="errormap" value="egroup|data-:-[
					                      				Please provide an image>|<
					                      				The caption for the image is required>|<
					                      				Provide a brief description of the image
					                      			]"/>
				                    
				              </div>
			              <?php
			          		}
			          	  ?>
			          	  	<div class="col-md-12 clearboth">
				                <div class="box-footer">
				                    <input type="button" class="btn btn-danger" name="gallerystream" data-formdata="<?php echo $formtruetype;?>" value="Create"/>
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