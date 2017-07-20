<?php
	if(isset($displaytype)){
		$formtruetype="aboutcontent";
		$contentName = array(
						'trustees' => 'Trustees',
						'charter' => 'FVT Charter',
					);
		$pagetype=$displaytype;
		$contentname=isset($contentName["$pagetype"])?$contentName["$pagetype"]:"";

		$newcontentname=isset($contentName["$pagetype"])?substr($contentname, 0,strlen($contentname)-1):"";
		// $datatype[0]=!isset($pagetype)?"hometopcollageboxintro":$pagetype."intro";  
		if(isset($contentgroupdatasingle)){
		    unset($contentgroupdatasingle);
		}
		if(isset($contentgroupdatageneral)){
		    unset($contentgroupdatageneral);
		}
		$hidenewsection="hidden";
		if($pagetype!=="chartersection"){
			$hidenewsection="";

		}
		if($pagetype=="directorsection"){
			$contentgroupdatageneral['contentgdset']['mainmsgout']="Create or Update Executive Director Profile";
			$contentgroupdatageneral['contentgdset']['mainmsgintroout']="Create/Update Profile";
			$contentgroupdatageneral['contentgdset']['formmonitor']='<input type="hidden" name="monitorcustom" value="1:2:3"/>';
			$contentgroupdatageneral['contentgdset']['itsubmitbtnname']="submitcustom";
			$contentgroupdatageneral['contentgdset']['itsubmitbtnattr']='data-formdata="introform"';
			$contentgroupdatageneral['contentgdset']['showhidesubtitle']="display:none;";
			$contentgroupdatageneral['contentgdset']['contenttextimageout']="Executive Directors Photograph";
			$contentgroupdatageneral['contentgdset']['contentattrimageout']='data-errmsg="Please provide the Director\'s Photograph"';
			$contentgroupdatageneral['contentgdset']['contenttexttitleout']="Executive Directors Fullname";
			$contentgroupdatageneral['contentgdset']['contentattrtitleout']='data-errmsg="Please provide the Director\'s Fullname"';
			$contentgroupdatageneral['contentgdset']['contentattrintroout']='data-mce="true" data-errmsg="Please provide the Director\'s details"';
			$contentgroupdatageneral['contentgdset']['contentplaceholdertitleout']="Director's Fullname";
			$contentgroupdatageneral['contentgdset']['contenttextsubtitleout']="Position<small>e.g Trustee</small>";
			$contentgroupdatageneral['contentgdset']['contenttextcontentout']="Director's Profile";
			$outs=getAllGeneralInfo("admin","$displaytype","","",$contentgroupdatageneral);
			echo $outs['fullviewthree'];
		}else {
			$formtypeouttwo="submitcustom";

?>
<div class="box">
	<div class="box-body">
	    <div class="box-group" id="generaldataaccordion">
			<div class="panel box overflowhidden box-primary <?php echo $hidenewsection;?>">
			    <div class="box-header with-border">
			        <h4 class="box-title">
			          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#NewPageManagerBlock">
			            <i class="fa fa-sliders"></i> New <?php echo $contentname;?>
			          </a>
			        </h4>
			    </div>
			    <div id="NewPageManagerBlock" class="panel-collapse collapse ">
			        <div class="row">
			            
						<?php
							if($pagetype=="trustees"){
								// set up the generaldtatmodule information to handle form display
								$contentgroupdatageneral['contentgdset']['showhideintro']="display:none;";
								$contentgroupdatageneral['contentgdset']['showhidestatus']="";
								$contentgroupdatageneral['contentgdset']['contenttexttitleout']="Trustees Full Name";
								$contentgroupdatageneral['contentgdset']['contenttextimageout']="Trustees Photograph";
								$contentgroupdatageneral['contentgdset']['contentplaceholdertitleout']="Fullname";
								$contentgroupdatageneral['contentgdset']['contenttextsubtitleout']="Position <small>e.g \"Trustee\"</small>";
								$contentgroupdatageneral['contentgdset']['contentplaceholdersubtitleout']="Provide trustees position";
								$contentgroupdatageneral['contentgdset']['contenttextcontentout']="Trustee Profile";
								$contentgroupdatageneral['contentgdset']['contentattrcontentout']='data-mce="true" data-errmsg="Please provide the Trustees\'s details/Bio"';
								$contentgroupdatageneral['contentgdset']['contentplaceholdercontentout']="Trustee full bio";
								$contentgroupdatageneral['adminheadings']['rowdefaults']='<tr><th>Image</th><th>Title</th><th>Position</th><th>Status</th><th>View/Edit</th></tr>';

								$contentgroupdatageneral['adminheadings']['output']=5;
								$contentgroupdatageneral['evaldata']['single']['initeval']='
									$positionout=$subtitle==""?"Trustee":$subtitle;
									$row[\'subtitle\']=$positionout;
									$tddataoutput=\'<td class="tdimg"><img src="\'.$coverpathout.\'"/></td><td>\'.$title.\'</td><td>\'.$positionout.\'</td><td>\'.$status.\'</td><td name="trcontrolpoint"><a href="#&id=\'.$id.\'" name="edit" data-type="editsinglegeneraldata" data-divid="\'.$id.\'">Edit</a></td>\';				
								';

								$contentgroupdatageneral['contentgdset']['formtypeout']="submitcustom";
								$contentgroupdatageneral['contentgdset']['formmonitor']='<input type="hidden" name="monitorcustom" value="1:2:4"/>';
								$outsdata=getAllGeneralInfo("admin","$pagetype","","",$contentgroupdatageneral);
								echo $outsdata['newcontentoutput'];
						?>
						<script>
							$(document).ready(function(){
								
								var curmcethreeposter=[];
								callTinyMCEInit("textarea[id*=postersmallfour]",curmcethreeposter);
								
							});
						</script>							

						<?php
							}	
						?>
							
			        </div>
			    </div>
			</div>
			<?php
				if($pagetype=="trustees"){

			?>
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
			<?php
				}else if($pagetype=="chartersection"){
					$contentgroupdatageneral['contentgdset']['formmonitor']='<input type="hidden" name="monitorcustom" value="3"/>';
					$contentgroupdatageneral['contentgdset']['itsubmitbtnname']="submitcustom";
					$contentgroupdatageneral['contentgdset']['itsubmitbtnattr']='data-formdata="introform"';
					$contentgroupdatageneral['contentgdset']['itformname']='introform';
					$contentgroupdatageneral['contentgdset']['showhidesubtitle']="display:none;";
					$contentgroupdatageneral['contentgdset']['showhideimage']="display:none;";
					$contentgroupdatageneral['contentgdset']['showhidetitle']="display:none;";
					$contentgroupdatageneral['contentgdset']['showhidecontent']="display:none;";
					$contentgroupdatageneral['contentgdset']['showhidestatus']="display:none;";
					$contentgroupdatageneral['contentgdset']['contentattrintroout']='data-mce="true" data-errmsg="Please provide the vision Statement"';
					$contentgroupdatageneral['contentgdset']['mainmsgout']="Create or Update Vision Statement";
					$contentgroupdatageneral['contentgdset']['mainmsgintroout']="Create/Update Vision";
					$contentgroupdatageneral['contentgdset']['contenttextcontentout']="Entry Details";
					$contentgroupdatageneral['contentgdset']['mceidtriptwo']="1";
					$contentgroupdatageneral['contentgdset']['extraformscript']='
							$(document).ready(function(){
								var curmcethreeposter=[];
								curmcethreeposter[\'toolbar2\']="| link unlink anchor | code";

								callTinyMCEInit("textarea[id*=postersmallthree1]",curmcethreeposter);			
							});
					';
					$outsvision=getAllGeneralInfo("admin","fvtvision","","",$contentgroupdatageneral);

					$contentgroupdatageneral['contentgdset']['itsubmitbtnattr']='data-formdata="introform1"';
					$contentgroupdatageneral['contentgdset']['itformname']='introform1';
					$contentgroupdatageneral['contentgdset']['contentattrintroout']='data-mce="true" data-errmsg="Please provide the mission Statement"';
					$contentgroupdatageneral['contentgdset']['mainmsgout']="Create or Update Mission Statement";
					$contentgroupdatageneral['contentgdset']['mainmsgintroout']="Create/Update Mission";
					$contentgroupdatageneral['contentgdset']['mceidtriptwo']="2";
					$contentgroupdatageneral['contentgdset']['extraformscript']='
							$(document).ready(function(){
								var curmcethreeposter=[];
								curmcethreeposter[\'toolbar2\']="| link unlink anchor | code";
								callTinyMCEInit("textarea[id=postersmallthree2]",curmcethreeposter);
							});
					';
					$outsmission=getAllGeneralInfo("admin","fvtmission","","",$contentgroupdatageneral);
					
					$contentgroupdatageneral['contentgdset']['itsubmitbtnattr']='data-formdata="introform2"';
					$contentgroupdatageneral['contentgdset']['itformname']='introform2';
					$contentgroupdatageneral['contentgdset']['contentattrintroout']='data-mce="true" data-errmsg="Please provide the values Statement"';
					$contentgroupdatageneral['contentgdset']['mainmsgout']="Create or Update Values Statement";
					$contentgroupdatageneral['contentgdset']['mainmsgintroout']="Create/Update Values";
					$contentgroupdatageneral['contentgdset']['mceidtriptwo']="3";
					$contentgroupdatageneral['contentgdset']['extraformscript']='
							$(document).ready(function(){
								
								var curmcethreeposter=[];
								curmcethreeposter[\'toolbar2\']="| link unlink anchor | code";
								callTinyMCEInit("textarea[id=postersmallthree3]",curmcethreeposter);
								
								
							});
						
					';
					$outsvalues=getAllGeneralInfo("admin","fvtvalues","","",$contentgroupdatageneral);	

					$contentgroupdatageneral['contentgdset']['itsubmitbtnattr']='data-formdata="introform3"';
					$contentgroupdatageneral['contentgdset']['itformname']='introform3';
					$contentgroupdatageneral['contentgdset']['contentattrintroout']='data-mce="true" data-errmsg="Please provide the Core Objectives Information"';
					$contentgroupdatageneral['contentgdset']['mainmsgout']="Create or Update Core Objectives";
					$contentgroupdatageneral['contentgdset']['mainmsgintroout']="Create/Update Core Objectives";
					$contentgroupdatageneral['contentgdset']['mceidtriptwo']="4";
					$contentgroupdatageneral['contentgdset']['extraformscript']='
						
							$(document).ready(function(){
								var curmcethreeposter=[];
								curmcethreeposter[\'toolbar2\']="| link unlink anchor | code";
								callTinyMCEInit("textarea[id*=postersmallthree4]",curmcethreeposter);
							});
					
					';
					$outsobjectives=getAllGeneralInfo("admin","fvtobjectives","","",$contentgroupdatageneral);
					echo $outsvision['fullviewthree'];
					echo $outsmission['fullviewthree'];
					echo $outsvalues['fullviewthree'];
					echo $outsobjectives['fullviewthree'];
			?>
				
			<?php
				}
			?>
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
<?php
		}
	}
?>