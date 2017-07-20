<?php
	/**
	*	Blogmetadata.php. 
	*	this file holds the views for creating/editting blogtypes and blogcategories
	*	its has both the create sections and edit sections for the two modules
	*	merged into one.
	*
	*	
	*/
	
	
	// first, we make sure key variables are made available to us
	if(isset($displaytype)||isset($_gdunitdisplaytype)){
		// unset($_SESSION['generalpagesdata']);
		// check to ensure the type of
		$_vcrt=strpos($viewtype, "_crt");

		!isset($data)?$data=array():$data;	
		$localdatamap="";	

		// manage the datamap to be passed into called function
		if(isset($datamap)&&isset($contentgroupdatageneral['datamap'])){

			$data['single']['datamap']=$contentgroupdatageneral['datamap'];
			$localdatamap=$data['single']['datamap'];
		}

		// seperate data set for displaying only the editsection of blog category entries
		// when particular requests are made with the following viewtypes
		if($viewtype=="blogcategoriesview_crt"){
			// attack the _vcrt variable and make sure its value does not evaluate to
			// true so the main blocks of this section are not accessed.
			// This would limit the scope of actions to the current block of code
			$_vcrt=-1;

			$data['blogtypeid']=isset($gd_dataoutput['blogtypeid'])?$gd_dataoutput['blogtypeid']:"";
			// var_dump($gd_dataoutput);
			if($data['blogtypeid']!==""){
				$outsdata=getAllBlogCategories('admin','','',$data);
				echo json_encode(array("success"=>"true","msg"=>"Transaction Successful",
					"catdata"=>$outsdata['adminoutput']));
				
			}else{
				echo json_encode(array("success"=>"false","msg"=>"Transaction Failure",
					"catdata"=>$outsdata['adminoutput']));
			}
		}
		if($viewtype=="create"||(($_vcrt===true||$_vcrt>0||$_vcrt==1)&&$_vcrt!==-1)){
			// create data content array
			$ctype=$_gdunit_viewtype;
			
			

			$data['queryorder']="ORDER by id DESC";
			// set the form type for the edit section
			$data['single']['formtruetype']="edit_".$formtruetype;

			// check if there are currently entries first and prepare the 
			// edit table section

			
			if($viewtype=="blogtype_crt"){
				$outsdata=getAllBlogTypes('admin','','',$data);
				$title="Blog Type";
				// $formtrutype="budgetcodes_gdunitform";
			}else if($viewtype=="blogcategory_crt"){
				$outsdata=getAllBlogCategories('admin','','',$data);
				// get all total blogtypes
				$outs=getAllBlogTypes("admin","all");
				$title="Blog Category";
			}
			// var_dump($data);


			// initialise content variables for the form below 
			$newin="in";
			$editin="";	
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
			<div class="panel box overflowhidden box-primary">
			    <div class="box-header with-border">
			        <h4 class="box-title">
			          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#NewPageManagerBlock">
			            <i class="fa fa-sliders"></i> Create <?php echo $title;?>
			          </a>
			        </h4>
			    </div>
			    <div id="NewPageManagerBlock" class="panel-collapse collapse <?php echo $newin;?>">
			        <div class="row">
			            <form name="<?php echo $formtruetype;?>" method="POST" data-type="create" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/basicsignup.php">
							<input type="hidden" name="entryvariant" value="<?php echo $variant;?>"/>

                            <?php
                            	if($viewtype=="blogtype_crt"){
                            ?>
	                            <div class="col-sm-12"> 
	                                <div class="form-group">
	                                    <label>Blog Name</label>
	                                    <div class="input-group">
		                                      <div class="input-group-addon">
		                                        <i class="fa fa-file-text"></i>
		                                      </div>
		                                      
											  <input type="text" placeholder="Enter Blog Name"  name="name" class="form-control"/>
		                                </div><!-- /.input group -->
	                              	</div><!-- /.form group -->
	                            </div>
	                            <div class="col-sm-12"> 
	                                <div class="form-group">
	                                    <label>Blog Description</label>
	                                    <div class="input-group">
		                                      <div class="input-group-addon">
		                                        <i class="fa fa-file-text"></i>
		                                      </div>
											  <textarea name="description" rows="5" placeholder="Enter Blog Description" class="form-control"></textarea>

		                                </div><!-- /.input group -->
	                              	</div><!-- /.form group -->
	                            </div>
	                            <input type="hidden" name="extraformdata" value="name-:-input<|>description-:-textarea"/>
			                    <input type="hidden" name="errormap" value="name-:-Please fill the name field<|>description-:-Please give a description for this Blog"/>
                            <?php
                        		}else if($viewtype=="blogcategory_crt"){
                            ?>
	                            <div class="col-sm-6"> 
	                                <div class="form-group">
	                                    <label>Blog Type</label>
	                                    <div class="input-group select2-bootstrap-prepend">
		                                      <div class="input-group-addon">
		                                        <i class="fa fa-file-text"></i>
		                                      </div>
											  	<select name="categoryid" data-name="select2plain" class="form-control">
													
													<?php echo $outs['selectiondata'];?>
												</select>
		                                </div><!-- /.input group -->
	                              	</div><!-- /.form group -->
	                            </div>
	                            <div class="col-sm-6"> 
	                                <div class="form-group">
	                                    <label>Category Name</label>
	                                    <div class="input-group">
		                                      <div class="input-group-addon">
		                                        <i class="fa fa-file-text"></i>
		                                      </div>
											  <input type="text" placeholder="Enter Category Name"  name="name" class="form-control"/>
		                                </div><!-- /.input group -->
	                              	</div><!-- /.form group -->
	                            </div>
	                            <div class="col-sm-6"> 
	                                <div class="form-group">
	                                    <label>Category Image</label>
	                                    <div class="input-group">
		                                      <div class="input-group-addon">
		                                        <i class="fa fa-file-image-o"></i>
		                                      </div>
												<input type="file" placeholder="Choose image" name="profpic" class="form-control"/>
		                                </div><!-- /.input group -->
	                              	</div><!-- /.form group -->
	                            </div>
	                            <div class="col-sm-6"> 
	                                <div class="form-group">
	                                    <label>Description <small>Optional and Short</small></label>
	                                    <div class="input-group">
		                                      <div class="input-group-addon">
		                                        <i class="fa fa-file-image-o"></i>
		                                      </div>
											<input type="text" placeholder="Provide description" name="subtext" class="form-control"/>
		                                </div><!-- /.input group -->
	                              	</div><!-- /.form group -->
	                            </div>
	                            <input type="hidden" name="extraformdata" value="categoryid-:-select<|>
	                            name-:-input<|>
	                            profpic-:-input|image<|>
	                            subtext-:-input"/>
			                    <input type="hidden" name="errormap" value="categoryid-:-Select a blogType from the list provided.<|>name-:-Please provide the name for this category<|>
			                    profpic-:-NA<|>
			                    subtext-:-NA"/>
                            <?php
                        		}
                            ?>

                            <input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>
		                    
			                <div class="col-md-12 clearboth">
				                <div class="box-footer">
				                    <input type="button" class="btn btn-danger" name="createentry" data-formdata="<?php echo $formtruetype;?>" onclick="submitCustom('<?php echo $formtruetype;?>','complete')" value="Create/Update"/>
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
                      <i class="fa fa-gear fa-spin"></i> Edit <?php echo $title;?>
                    </a>
                  </h4>
                </div>
                <div id="EditBlock" class="panel-collapse collapse <?php echo $editin;?>">
                  	<div class="box-body">
                      	<div class="row">
                      		<?php
                            	if($viewtype=="blogtype_crt"){
                            ?>
		                        <div class="col-md-12">
		                        	<?php 
			                        	echo $outsdata['adminoutput'];
				                          
			                        ?>	
	                    		</div>

                            <?php
                        		}else if($viewtype=="blogcategory_crt"){
                            ?>
		                        <div class="col-md-12 render-field-hold" name="blogcategoriesview">
									<input type="hidden" name="datamap" data-crt="true"
									value='<?php echo $localdatamap;?>'/>
		                        	<div class="col-sm-12"> 
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
		                            <div class="col-md-12 clearboth">
						                <div class="box-footer margin-auto text-center">
						                    <input type="button" class="btn btn-danger" data-type="submitcrt" name="blogcategoriesview" value="Load/Refresh View"/>
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
                            <?php
                        		}
                            ?>
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

			if(isset($variant2)&&$variant2=="editblogtype"){
				$dataset=getSingleBlogType($editid,"",$data);
				$name=$dataset['name'];
				$foldername=$dataset['foldername'];
				$description=$dataset['description'];
				
			}else if(isset($variant2)&&$variant2=="editblogcategory"){
				$dataset=getSingleBlogCategory($editid,"",$data);
				$blogtypeid=$dataset['blogtypeid'];
				$catname=$dataset['catname'];
				$subtext=$dataset['subtext'];
				// check for coverimage
				$profpicid=$dataset['profpicdata']['id'];
				$profpic=$dataset['profpicdata']['medsize'];
				// get the list of active blog types

			}
			$totalscripts=$dataset['totalscripts'];
			
?>
			<!-- Edit section -->
			<div class="row">
		        <form name="<?php echo $formtruetype2;?>" method="POST" data-type="edit" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/edit.php">
					<input type="hidden" name="entryvariant" value="<?php echo $variant2;?>"/>

					<input type="hidden" name="entryid" value="<?php echo $editid;?>"/>

					<?php
                    	if($variant2=="editblogtype"){
                    ?>
                        <div class="col-sm-12"> 
                            <div class="form-group">
                                <label>Blog Name</label>
                                <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-file-text"></i>
                                      </div>
                                      
									  <input type="text" placeholder="Enter Blog Name"  name="name" value="<?php echo $name;?>" class="form-control"/>
                                </div><!-- /.input group -->
                          	</div><!-- /.form group -->
                        </div>
                        <div class="col-sm-12"> 
                            <div class="form-group">
                                <label>Blog Description</label>
                                <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-file-text"></i>
                                      </div>
									  <textarea name="description" rows="5" placeholder="Enter Blog Description" class="form-control"><?php echo $description;?></textarea>

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
                        <input type="hidden" name="extraformdata" value="name-:-input<|>description-:-textarea"/>
	                    <input type="hidden" name="errormap" value="name-:-Please fill the name field<|>description-:-Please give a description for this Blog"/>
                    <?php
                		}else if($variant2=="editblogcategory"){
                    ?>
                        
                        <div class="col-sm-4"> 
                            <div class="form-group">
                                <label>Category Name</label>
                                <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-file-text"></i>
                                      </div>
									  <input type="text" placeholder="Enter Category Name"  name="name" value="<?php echo $catname;?>" class="form-control"/>
                                </div><!-- /.input group -->
                          	</div><!-- /.form group -->
                        </div>
                        <div class="col-sm-4"> 
                            <div class="form-group">
                                <label>Change/Update Category Image</label>
	                            <input type="hidden" class="form-control" name="profpicid" value="<?php echo $dataset['profpicdata']['id'];?>">
                                <?php 
	                                $copt="";
	            					if($dataset['profpicdata']['id']>0){
	            						$profdata=$dataset['profpicdata'];
	            						$copt="coptionpoint _hold";
	            				?>
            					<div class="contentpreview _image">
            						<div class="contentpreviewoptions">
                        				<a href="##options" class="option">
                        					<i class="fa fa-gear fa-spin" title="Edit"></i>
                        				</a>
                        			</div>
	                            	<a href="<?php echo $profdata['location'];?>" data-lightbox="general_catimage" data-src="<?php echo $profdata['location'];?>" class="">
	                            		<img src="<?php echo $profdata['thumbnail'];?>">
	                            	</a>
	                            </div>
	                            <div class="input-group <?php echo $copt;?>">
                                      <div class="input-group-addon color-red">
                                        <i class="fa fa-trash"></i>
                                      </div>
		                            <select class="form-control coptionpoint _hold" name="profpicdelete">
					                    <option value="">Delete this?</option> 
					                    <option value="inactive">Yes</option> 
					                </select>
						        </div>
            				<?php 
            						}
            				?>
                                <div class="input-group <?php echo $copt;?>">
                                      <div class="input-group-addon">
                                        <i class="fa fa-file-image-o"></i>
                                      </div>
										<input type="file" placeholder="Choose image" name="profpic" class="form-control"/>
                                </div><!-- /.input group -->
                          	</div><!-- /.form group -->
                        </div>
                        <div class="col-sm-4"> 
                            <div class="form-group">
                                <label>Parent Blog: <small><?php echo $dataset['blogtypedata']['name'];?></small></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-file-text"></i>
                                    </div>
									  	<!-- <select name="categoryid" title=
									  	"<?php echo $dataset['blogtypedata']['name'];?>"data-name="select2plain" class="form-control">
											<option value="">--Choose Blog--</option>
											<?php echo $outs['selection'];?>
										</select> -->
									<input type="text" placeholder="" title="<?php echo $dataset['blogtypedata']['name'];?>"  name="name" disabled value="<?php echo $dataset['blogtypedata']['name'];?>" class="form-control"/>

                                </div><!-- /.input group -->
                          	</div><!-- /.form group -->
                        </div>
                        <div class="col-sm-6"> 
                            <div class="form-group">
                                <label>Description <small>Optional and Short</small></label>
                                <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-file-text-o"></i>
                                      </div>
									<input type="text" placeholder="provide description" name="subtext" value="<?php echo $subtext?>" class="form-control"/>
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
                        <input type="hidden" name="extraformdata" value="categoryid-:-select<|>
                        name-:-input<|>
                        profpic-:-input|image<|>
                        subtext-:-input"/>
	                    <input type="hidden" name="errormap" value="categoryid-:-Select a blogtype from the list provided.<|>name-:-Please provide the name for this category<|>
	                    profpic-:-NA<|>
	                    subtext-:-NA"/>
                    <?php
                		}
                    ?>

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
						
					});
				</script>	
	        </div>
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
			if($vtype=="blogtypesearch"){
				$viewer[0]=$viewer;
				$viewer[1]=$type[0];
				$viewer[2]=$type[1];
				unset($type);
				$type="";
			}
			// var_dump($data);
			if(isset($generalpagesdata['pagtype'])
				&&$generalpagesdata['pagtype']=="blogtype"){
				$pagout=getAllBlogTypes($viewer,$limit,$type,$data);
			}else if(isset($generalpagesdata['pagtype'])
				&&$generalpagesdata['pagtype']=="blogcategory"){
				$pagout=getAllBlogCategories($viewer,$limit,$type,$data);

			}

			echo $pagout['adminoutputtwo'];
		}
	}
?>

