<?php

	
	// first, we make sure key variables are made available to us
	if(isset($displaytype)||isset($_gdunitdisplaytype)){

		if($viewtype=="create"){
			// create data content array
			$data=array();	

			// manage the datamap for ar
			if(isset($datamap)){
				$data['single']['datamap']=$contentgroupdatageneral['datamap'];
			}
			// set the form type for the edit section
			$data['single']['formtruetype']="edit_".$formtruetype;

			// check if there are currently entries first and prepare the 
			// edit table section
			$outsdata=getAllPortfolioCategories('admin','','',$data);

			// initialise content variables for the form below 
			$newin="in";
			$editin="";	
			if($outsdata['numrows']>0){
				// entries are available
				$editin="in";
				$newin="";
			}
			

?>
<div class="box">
	<div class="box-body">
	    <div class="box-group" id="generaldataaccordion">
			<div class="panel box overflowhidden box-primary">
			    <div class="box-header with-border">
			        <h4 class="box-title">
			          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#NewPageManagerBlock">
			            <i class="fa fa-sliders"></i> Create Portfolio Categories
			          </a>
			        </h4>
			    </div>
			    <div id="NewPageManagerBlock" class="panel-collapse collapse <?php echo $newin;?>">
			        <div class="row">
			            <form name="<?php echo $formtruetype;?>" method="POST" data-type="create" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/basicsignup.php">
							<input type="hidden" name="entryvariant" value="createportfoliocategory"/>
	            			<div class="col-sm-12"> 
                                <div class="form-group">
                                    <label>Category Name</label>
                                    <div class="input-group">
	                                      <div class="input-group-addon">
	                                        <i class="fa fa-file-text"></i>
	                                      </div>
	                                      <input type="text" class="form-control" name="catname" Placeholder="Category Name"/>
	                                </div><!-- /.input group -->
                              	</div><!-- /.form group -->
                            </div>
                            <div class="col-md-12">
                                <label>Category Details:</label>
                                <textarea class="form-control" rows="3" name="catdetails" data-mce="true" id="postersmallthree_1" placeholder="The category Details"></textarea>
                            </div>
                                
                            <input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>
		                    <input type="hidden" name="extraformdata" value="catname-:-input<|>catdetails-:-textarea"/>
		                    <input type="hidden" name="errormap" value="catname-:-Please provide the category name<|>
		                    catdetails-:-Provide a brief description for this category"/>
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
                      <i class="fa fa-gear fa-spin"></i> Edit Portfolio Categories
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
			$data['single']['formtruetype']="edit_$formtruetype2";
			$dataset=getSinglePortfolioCategory($editid,"",$data);
			$catname=$dataset['catname'];
			$catdetails=$dataset['catdetails'];
			$totalscripts=$dataset['totalscripts'];
			
?>
			<!-- Edit section -->
			<div class="row">
	            <form name="edit_<?php echo $formtruetype2;?>" method="POST" data-type="edit" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/edit.php">
					<input type="hidden" name="entryvariant" value="editportfoliocategory"/>
            		<input type="hidden" name="entryid" value="<?php echo $editid;?>"/>
            		<div class="col-sm-12"> 
                        <div class="form-group">
                            <label>Category Name</label>
                            <div class="input-group">
                                  <div class="input-group-addon">
                                    <i class="fa fa-file-text"></i>
                                  </div>
                                  <input type="text" class="form-control" name="catname" Placeholder="Category Name" value="<?php echo $catname;?>"/>
                            </div><!-- /.input group -->
                      	</div><!-- /.form group -->
                    </div>
                    <div class="col-md-12">
                        <label>Category Details:</label>
                        <textarea class="form-control" rows="3" name="catdetails" data-mce="true" id="postersmallthree_2" placeholder="The members Details"><?php echo $catdetails;?></textarea>
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
                    <input type="hidden" name="formdata" value="edit_<?php echo $formtruetype2;?>"/>
                    <input type="hidden" name="extraformdata" value="catname-:-input<|>catdetails-:-textarea"/>
		                    <input type="hidden" name="errormap" value="catname-:-Please provide the category name<|>
		                    catdetails-:-Provide a brief description for this category"/>
	                <div class="col-md-12 clearboth">
		                <div class="box-footer">
		                    <input type="button" class="btn btn-danger" name="createentry" data-formdata="edit_<?php echo $formtruetype2;?>" onclick="submitCustom('edit_<?php echo $formtruetype2;?>','complete')" value="Update"/>
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
					
				});
			</script>	
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
			$pagout=getAllPortfolioCategories($viewer,$limit,$type,$data);
			echo $pagout['adminoutputtwo'];
		}
	}
?>

