<?php
	$formtruetype="faqform";
?>
			<div id="form" style="background-color:#fefefe;">
				<form action="../snippets/basicsignup.php" 
				name="<?php echo $formtruetype;?>" method="post">
					<input type="hidden" name="entryvariant" 
					onsubmit="return false;" value="createfaq"/>
					<div id="formheader">Create FAQ</div>
						<div class="col-sm-4"> 
                          	<div class="form-group">
	                            <label>
	                            	FAQ Title *
	                            </label>
	                            <div class="input-group">
		                            <div class="input-group-addon">
		                                <i class="fa fa-file-text"></i>
		                            </div>
									<input type="text" placeholder="Enter FAQ Title" 
									name="title" class="form-control"/>

	                            </div><!-- /.input group -->
                          	</div><!-- /.form group -->
                        </div>
						<div class="col-md-12">
							<label>FAQ Details:</label>
							<textarea name="content" 
							id="adminposter" 
							Placeholder="Details" 
							class="form-control"
							data-mce="true"></textarea>
						</div>

					<input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>
                    <input type="hidden" name="extraformdata" 
                    value="title-:-input<|>
                      content-:-textarea"/>
                    <input type="hidden" name="errormap" 
                    value="title-:-Please provide the title for the faq entry<|>
                      content-:-Provide the details for the faq title"/>

	                <div class="col-md-12 clearboth">
		                <div class="box-footer">
		                    <input type="button" class="btn btn-danger" 
		                    name="createentry" 
		                    data-formdata="<?php echo $formtruetype;?>" 
		                    onclick="submitCustom('<?php echo $formtruetype;?>','complete')" 
		                    value="Create/Update"/>
		                </div>
		            </div>
				</form>
			</div>
			<script>
				$(document).ready(function(){
					var curmceadminposter=[];
					curmceadminposter['width']="100%";
					curmceadminposter['height']="650px";
					curmceadminposter['toolbar1']="undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect";
					curmceadminposter['toolbar2']="| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ";
					callTinyMCEInit("textarea#adminposter",curmceadminposter);   
					
				})
			</script>