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
		
		// end
		// initialise content variables for the form below 
		$newin="in";
		$editin="";	
		$newh="";
		$edith="";
		if($viewtype=="create"||(($_vcrt===true||$_vcrt>0||$_vcrt==1)&&$_vcrt!==-1)){
			// create data content array
			$ctype=$_gdunit_viewtype;
			$fhtitle="Blog Comments";
						

			$data['queryorder']="ORDER by id DESC";
			// set the form type for the edit section
			$data['single']['formtruetype']="edit_".$formtruetype;

			// check if there are currently entries first and prepare the 
			// edit table section

			$outs=getAllBlogTypes("viewer","all");
			$ctype="$";
			if($viewtype=="active_crt"){
				$outsdata=getAllComments('admin','','',$data);
				
				$viewdata="blogfeatured";
				$viewtype="create";
				$newh="hidden";
				$editin="in";
				$fhtitle="";
			}else if($viewtype=="inactive_crt"){
				$viewtype="create";
				$newh="hidden";
				$editin="in";
			}else if($viewtype=="disabled_crt"){
				$outsdata=getAllComments('admin','','',$data);

			}else{
				$viewdata="blogplain";
				$outsdata=getAllComments('admin','','',$data);

			}
			// var_dump($data);


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
	                        <div class="col-md-12 render-field-hold" name="<?php echo $viewdata;?>">
									<input type="hidden" name="datamap" value='<?php echo $localdatamap;?>'/>
		                        	<div class="col-sm-6"> 
		                                <div class="form-group">
		                                    <label>Change Blog Type <small>Default view is the first blog type created</small></label>
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
		                            <div class="col-md-6">
		                            	<div class="form-group">
											<label>Choose a category</label>
											<div class="input-group">
												<div class="input-group-addon">
													<i class="fa fa-file-text-o"></i>
												</div>
												<select name="blogcategoryid" 
												data-name="select2plain"
												class="form-control">
													<option value=""
													>--Choose A Blog Type First--</option>
												</select>
											</div>
		                            	</div>
									</div>
		                            <div class="col-md-12 clearboth">
						                <div class="box-footer margin-auto text-center">
						                    <input type="button" class="btn btn-danger" name="viewblogcomments" value="Load/Refresh View"/>
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
			if($vtype=="blogpostsearch"){
				$viewer[0]=$viewer;
				$viewer[1]=$type[0];
				$viewer[2]=$type[1];
				unset($type);
				$type="";
			}
			// var_dump($data);
			
			$pagout=getAllComments($viewer,$limit,$type,$data);
			echo $pagout['adminoutputtwo'];
		}
	}
?>