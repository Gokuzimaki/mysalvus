<?php
	// this is a basic template for creating and managing content for the generalinfo
	// table in a customizable format
	// This particular template serves content that needs only one entry
	// such as welcome messages or section descriptions on a page
	// the kind of form here is singular for both creating and editting 
	// previously created content

	// first, we make sure key variables are made available to us
	if(isset($displaytype)){
		// the basic variables accessible here are:
		// $gd_dataoutput, $datamap,viewtype, variant,maintype and subtype.
		// The gd_ variable here is the processed version of the data map json text,
		// it is an associative array obtained from said text
		if(!isset($viewtype)){
			$viewtype=="create";
		}
		// specify that this file uses the _gdunit module to work
		$_gdunit="true";
		$formtypeouttwo="submitcustom";
		// setup entry array for generaldata functions
		$contentgroupdatageneral=array();
		// obtain necessary data to carry out display operations
		if($viewtype=="create"||$viewtype=="edit"){
			
			// set the generaldata
			// setup the admin output headers if necessary.
			// this only occurs for forms that have a seperate section for editting content

			/*$contentgroupdatageneral['adminheadings']['rowdefaults']='<tr><th>Image</th><th>Title</th><th>Position</th><th>Status</th><th>View/Edit</th></tr>';
			$contentgroupdatageneral['adminheadings']['output']=5;
			$contentgroupdatageneral['evaldata']['single']['initeval']='
				$positionout=$subtitle==""?"Trustee":$subtitle;
				$row[\'subtitle\']=$positionout;
				$tddataoutput=\'<td class="tdimg"><img src="\'.$coverpathout.\'"/></td><td>\'.$title.\'</td><td>\'.$positionout.\'</td><td>\'.$status.\'</td><td name="trcontrolpoint"><a href="#&id=\'.$id.\'" name="edit" data-type="editsinglegeneraldata" data-divid="\'.$id.\'">Edit</a></td>\';				
			';*/

			// var_dump($outsdata);			
			$entryid=isset($outsdata['resultdataset'][0]['id'])?
			$outsdata['resultdataset'][0]['id']:0;
			// initialise content variables for the form below 
			// plaincontent hide class variable. This handles the display status
			// of the plain_content section in the form below and is hidden by
			// default
			$pc_hidden="hidden";
			// dualcontent hide class variable. This handles the display status
			// of the dual_content sections
			$dc_hidden="";
			if($entryid>0){
				// echo "test entryid<br>";
				// IMPORTANT
				// include the below files only when there is a hit for previous content
				// in the database.
				// these modules parse general data and create the necessary variables
				// needed by the form. they are included manually for single enrty type contents
				// and are automatically used when the content being managed has multiple entries
				// prep the _gdoutput variable, this variable stores the entries of data actions
				// carried out on a single data set
				$_gdoutput=$outsdata;
				include('gdmoduledataparser.php');
				include('gdmoduledataeditdefault.php');
				
				// this section means there is currently a valid entry for the maintype
				// do your fancy variable creation stuff here. If this 
				// entry makes use of extradata column to store more values,
				// the variables you will need to populate the field elements
				// have already been created for you, you just have to use them
				// these variables are the names of your form elements
				// e.g if your form has an input element with name="firstinput"
				// the corresponding variable that holds its value is $name
				// other variables include 
				// $cgcount currentgroupcount - the total number of entries per groupset
				// data
				$edit_attr='data-edittype="true"';
				!isset($content_footer)?$content_footer="":
				$content_footer=$content_footer;
				!isset($content_ga)?$content_ga="":
				$content_ga=$content_ga;
				!isset($content_disqus_name)?$content_disqus_name="":
				$content_disqus_name=$content_disqus_name;
			}else{
				// this means there is no entry for the maintype 
				$contenttitle="";
				$contentsubtitle="";
				$content_footer="";
				// disqus
				$content_disqus_name="";
				// google analytics tracking code
				$content_ga="";

				$contentpost="";
				$contentpost_1="";
				$contentpost_2="";
				// these are compulsory variable to define
				$totalscripts="";
				$edit_attr="";
				
			}

?>
<div class="box">
	<div class="box-body">
	    <div class="box-group" id="generaldataaccordion">
			<div class="panel box overflowhidden box-primary">
			    <div class="box-header with-border">
			        <h4 class="box-title">
			          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#NewPageManagerBlock">
			            <i class="fa fa-sliders"></i> Create/Update Default Page data.
			          </a>
			        </h4>
			    </div>
			    <div id="NewPageManagerBlock" class="panel-collapse collapse in">
			        <div class="row">
			            <form name="<?php echo $formtruetype;?>" method="POST" data-type="create" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/edit.php">
							<input type="hidden" name="entryvariant" value="<?php echo $variant;?>"/>
		            		<input type="hidden" name="maintype" value="<?php echo $maintype;?>"/>
		            		<input type="hidden" name="subtype" value="contententry"/>
		            		<input type="hidden" name="entryid" value="<?php echo $entryid;?>"/>
		            		<div class="col-md-12">
                                <div class="col-sm-4"> 
                                  <div class="form-group">
                                    <label>Site Name
                                    </label>
                                    <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-file-text"></i>
                                      </div>
                                      <input type="text" class="form-control" name="contenttitle" value="<?php echo $contenttitle;?>" Placeholder="The message title"/>
                                    </div><!-- /.input group -->
                                  </div><!-- /.form group -->
                                </div>

                                <div class="col-sm-4"> 
                                  <div class="form-group">
                                    <label>Site Trailer
                                    </label>
                                    <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-file-text"></i>
                                      </div>
                                      <input type="text" class="form-control" 
                                      name="contentsubtitle" 
                                      value="<?php echo $contentsubtitle;?>" 
                                      Placeholder="The message subtitle"/>
                                    </div><!-- /.input group -->
                                  </div><!-- /.form group -->
                                </div>
                                <div class="col-sm-4"> 
                                  <div class="form-group">
                                    <label>Footer Trailer 
                                    	<small>Shortcodes allowed( [|current_date|] )
                                    	</small>
                                    </label>
                                    <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-file-text"></i>
                                      </div>
                                      <input type="text" class="form-control" name="content_footer" value="<?php echo $content_footer;?>" Placeholder="The message title"/>
                                    </div><!-- /.input group -->
                                  </div><!-- /.form group -->
                                </div>

                                <div class="col-sm-6"> 
                                  <div class="form-group">
                                    <label>Google Analytics</label>
                                    <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-google"></i>
                                      </div>
                                      <input type="text" class="form-control" 
                                      name="content_ga" 
                                      value="<?php echo $content_ga;?>" 
                                      Placeholder="Google analytics tracking code"/>
                                    </div><!-- /.input group -->
                                  </div><!-- /.form group -->
                                </div>
                                <div class="col-sm-6"> 
                                  <div class="form-group">
                                    <label>Disqus Comments</label>
                                    <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-comments"></i>
                                      </div>
                                      <input type="text" class="form-control" 
                                      name="content_disqus_name" 
                                      value="<?php echo $content_disqus_name;?>" 
                                      Placeholder="Disqus Host name"/>
                                    </div><!-- /.input group -->
                                  </div><!-- /.form group -->
                                </div>

                                <div class="col-sm-8">
                                    <label>Site Logo </label>
                                    <?php
                                    	if(isset($coverimage)&&$coverimage!==""){
                                    ?>
                                    <div class="contentpreview _image _dark">
                                    	<a href="<?php echo $host_addr.$coverimage_filedata['location'];?>" data-lightbox="general" data-title="Site Logo" data-src="<?php echo $host_addr.$coverimage_filedata['location'];?>">
                                    		<img src="<?php echo $host_addr.$coverimage_filedata['thumbnail'];?>"/>
                                    	</a>
                                    </div>
                                    <?php
                                    		echo $coverimage_filedata['manageoutput'];
                                    	}
                                    ?><!-- specifies the value for the upload width/height format(fieldname_size[t|m)w|h]-->
                                    <input type="hidden" class="form-control" name="coverimage_sizeth" value="150"/>
                                    <input type="hidden" class="form-control" name="coverimage_sizemh" value="380"/>
                                    <input type="file" class="form-control" name="coverimage" <?php echo $edit_attr;?>/>
                                </div>
                                <div class="col-sm-4">
                                    <label>Favicon </label>
                                    <?php
                                    	if(isset($coverimage_1)&&$coverimage_1!==""){
                                    ?>
                                    <div class="contentpreview _image _dark">
                                    	<a href="<?php echo $host_addr.$coverimage_1_filedata['location'];?>" data-lightbox="general" data-title="Favicon" data-src="<?php echo $host_addr.$coverimage_1_filedata['location'];?>">
                                    		<img src="<?php echo $host_addr.$coverimage_1_filedata['location'];?>"/>
                                    	</a>
                                    </div>
                                    <?php
                                    		echo $coverimage_1_filedata['manageoutput'];
                                    	}
                                    ?><!-- specifies the value for the upload width/height format(fieldname_size[t|m)w|h]-->
                                    <input type="hidden" class="form-control" name="coverimage_1_sizemh" value="default"/>
                                    <input type="hidden" class="form-control" name="coverimage_1_sizeth" value="default"/>
                                    <input type="file" class="form-control" name="coverimage_1" <?php echo $edit_attr;?>/>
                                </div>
                                
                                <div class="col-sm-6">
                                    <label>Business Hours <small>If applicable</small> </label>
                                    <textarea class="form-control" 
                                    rows="3" 
                                    name="contentpost_1"  
                                    id="minadmin1" 
                                    placeholder="Business hours"
                                    ><?php echo $contentpost_1;?></textarea>
                                </div>
                                <div class="col-sm-6 ">
                                    <label>Short Site Description </label>
                                    
                                    <textarea class="form-control" rows="3" 
                                    name="contentpost_2"
                                    id="minadmin" 
                                    placeholder="Mini site description"
                                    ><?php echo $contentpost_2;?></textarea>
                                </div>
                                <div class="col-md-12">
                                    <label>Full site details:</label>
                                    <textarea class="form-control" rows="3" name="contentpost" data-mce="true" id="adminposter" placeholder="The message here"><?php echo $contentpost;?></textarea>
                                </div>

                                <!-- group 2 -->
	                                <!-- Start edit section accordion -->
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
										            Update Default Social entries
										          </a>
										        </h4>
									    	</div>
									      	<div id="headBlock_group1" class="panel-collapse collapse">
				                                <div class="col-md-12 social-field-hold">
				                                	<!-- data-valcount="2" data-valset="1,2,3" -->
				                                    <input name="socialcount" type="hidden" value="7" data-counter="true" class="form-control"/>
				                                    <?php 
					                                    $sdarr=array();
					                                    $sdicon=array();
					                                    // prep the default social media info
					                                    $sdarr[]="Facebook";$sdicon[]="fa-facebook";
					                                    $sdarr[]="Twitter";$sdicon[]="fa-twitter";
					                                    $sdarr[]="Googleplus";$sdicon[]="fa-google-plus";
				                                    	$sdarr[]="Linkedin";$sdicon[]="fa-linkedin";
					                                    $sdarr[]="Pinterest";$sdicon[]="fa-pinterest";
					                                    $sdarr[]="Instagram";$sdicon[]="fa-instagram";
					                                    $sdarr[]="Skype";$sdicon[]="fa-skype";
				                                    	for ($i = 1; $i <=7 ; $i++) {
				                                    		$t=$i-1	;
				                                    		$ttype="triggerprogeny";
				                                    		if($t==0){
				                                    			$ttype="triggerprogenitor";
				                                    		}
				                                    		$dname=$sdarr[$t];
				                                    		$icon=$sdicon[$t];
				                                    		$csurl=isset($gd_general_array['group1']['socialurl'.$i])?
				                                    		$gd_general_array['group1']['socialurl'.$i]:"";
				                                    		
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
				                                	<!-- <input name="social_entryminimum" type="hidden" value="2" class="form-control"/>
				                                	<input name="socialfuncdata" type="hidden" value="{'func':['select2'],
				                                	'selectors':['div.social-field-hold select[data-name=faselect]'],
				                                	'typegd':['encapsjq'],
				                                	'params':[['theme','``bootstrap``','templateResult','faSelectTemplate']],
				                                	'dselectors':['div.social-field-hold select[data-name=faselect]'],
				                                	'dtypegd':['plainjq'],
				                                	'dparams':[['destroy']]}"> -->
				                                	
				                                	<!-- <a href="##" class="generic_addcontent_trigger" 
														data-type="triggerformaddlib"
														data-form-name="<?php echo $formtruetype;?>" 
														data-name="socialcount_addlink" 
														data-i-type="" 
														data-limit="4"> 
														<i class="fa fa-plus"></i>Add More?
													</a> -->
				                                </div>
				                            </div>
		                        		</div>
		                        	</div>		
		                        	<!-- end edit section accordion -->
                                <!-- end group 1 -->
                            </div>
                            <input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>
		                    <input type="hidden" name="extraformdata" value="contenttitle-:-input<|>
		                      contentsubtitle-:-input<|>
		                      content_footer-:-input<|>
		                      content_ga-:-input<|>
		                      content_disqus_name-:-input<|>
                      		  coverimage-:-input|image<|>
                      		  coverimage_1-:-input|image<|>
		                      contentpost-:-textarea<|>
		                      contentpost_1-:-textarea<|>
		                      contentpost_2-:-textarea<|>
		                      egroup|data-:-[socialcount>|<
		                      socialname-|-input>|<
		                      socialicon-|-input>|<
		                      socialurl-|-input]-:-groupfall[1-2,2-1]"/>
		                    <input type="hidden" name="errormap" value="contenttitle-:-NA<|>
		                      contentsubtitle-:-NA<|>
		                      content_footer-:-NA<|>
		                      content_ga-:-NA<|>
		                      content_disqus_name-:-NA<|>
		                      coverimage-:-NA<|>
		                      coverimage_1-:-NA<|>
		                      contentpost-:-NA<|>
		                      contentpost_1-:-NA<|>
		                      contentpost_2-:-NA<|>
		                      egroup|data-:-[NA>|<NA>|<NA]"/>

			                <div class="col-md-12 clearboth">
				                <div class="box-footer">
				                    <input type="button" class="btn btn-danger" name="createentry" data-formdata="<?php echo $formtruetype;?>" onclick="submitCustom('<?php echo $formtruetype;?>','complete')" value="Create/Update"/>
				                </div>
				            </div>
		            	</form>	
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
			curmcethreeposter['width']="100%";

			curmcethreeposter['height']="300px";

			callTinyMCEInit("textarea[id*=postersmallthree]",curmcethreeposter);

			var curmceminadminposter=[];
			curmceminadminposter['width']="100%";
			// curmceminadminposter['rfmanager']="";
			curmceminadminposter['height']="200px";
			curmceminadminposter['toolbar1']="undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | fontselect fontsizeselect ";
			curmceminadminposter['toolbar2']="| bullist numlist outdent indent | styleselect code | image media";
			callTinyMCEInit("textarea[id*=minadmin]",curmceminadminposter);

			$(document).on("blur","select[name=contententrytype]",function(){
				if($(this).val()=="plain"){
					$('.dual_content').addClass('hidden');
					$('.plain_content').removeClass('hidden');
				}else{
					$('.dual_content').removeClass('hidden');
					$('.plain_content').addClass('hidden');
				}
			})
			
				<?php echo $totalscripts;?>
			
		});
	</script>
</div>
<?php
			}
		}
?>
