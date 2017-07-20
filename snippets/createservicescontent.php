<?php
	if(isset($displaytype)){
		$formtruetype="servicescontent";
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
		$hidenewsection="";
		
		
		$formtypeouttwo="submitcustom";
		$outfa=pullFontAwesomeClasses();
		sort($outfa['faiconnames']);
		sort($outfa['famatches']);

		$list="";
		if($outfa['numrows']>0){
			for ($x = 0;$x < $outfa['numrows'];$x++) 
			{ 
			    $list.='<li class=""><a href="##" data-type="fapicker" data-toggle="tooltip" data-original-title="'.$outfa['faiconnames'][$x].'" title="'.$outfa['faiconnames'][$x].'" value="'.$outfa['famatches'][$x].'"><i class="fa '.$outfa['famatches'][$x].'"></i></a></li>';
			} 
		}
		$facontent='
			<div class="col-md-12 faselectsectionhold">
			  <div class="col-md-12 fadisplaypoint">
			    <div class="col-sm-12"><h3 class="labelheading">Choose Service Icon <small>If applicable</small></h3></div>
			    <div class="col-md-1 currentfa">
			      <i class=""></i>
			    </div>
			    <div class="col-md-4 textfieldfa">
			        <div class="form-group">
			          <input type="text" class="form-control" data-name="icontitle" name="contentsubtitle" readonly Placeholder="Selected icon code displays here"/>
			        </div>
			    </div>  
			  </div>
			  <div class="col-md-12 fadisplaylisthold">
			    <ul class="fadisplaylist">
			      '.$list.'
			    </ul>
			  </div>
			</div>
		';
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
							if($pagetype=="productservices"){
								// set up the generaldtatmodule information to handle form display
								$contentgroupdatageneral['contentgdset']['contenttextheaderout']="New Service Entry";
								$contentgroupdatageneral['contentgdset']['showhideintro']="display:none;";
								$contentgroupdatageneral['contentgdset']['showhidesubtitle']="display:none;";
								$contentgroupdatageneral['contentgdset']['showhidestatus']="";
								$contentgroupdatageneral['contentgdset']['contenttexttitleout']="Service title";
								$contentgroupdatageneral['contentgdset']['contenttextimageout']="Service Image";
								$contentgroupdatageneral['contentgdset']['contentplaceholdertitleout']="Service title...";
								$contentgroupdatageneral['contentgdset']['contenttextcontentout']="Service Details";
								$contentgroupdatageneral['contentgdset']['contentattrcontentout']='data-mce="true" data-errmsg="Please provide the details of this service you offer"';
								$contentgroupdatageneral['contentgdset']['contentplaceholdercontentout']="Complete service details here";
								$contentgroupdatageneral['adminheadings']['rowdefaults']='<tr><th>Image</th><th>Title</th><th>Icon</th><th>Status</th><th>View/Edit</th></tr>';
								// $contentgroupdatageneral['contentgdset']['extraformdata']=$facontent;

								$contentgroupdatageneral['adminheadings']['output']=5;
								$contentgroupdatageneral['evaldata']['general']['initeval']='$extraformdata=\''.$facontent.'\';';
								$contentgroupdatageneral['evaldata']['single']['initeval']='
									$ffa=$subtitle==""?"":\'fa \'.$subtitle.\'\';
									$extraformdata=\'<div class="col-md-12 faselectsectionhold">
													  <div class="col-md-12 fadisplaypoint">
													    <div class="col-sm-12"><h3 class="labelheading">Choose Service Icon <small>If applicable</small></h3></div>
													    <div class="col-md-1 currentfa">
													      <i class="\'.$ffa.\'"></i>
													    </div>
													    <div class="col-md-4 textfieldfa">
													        <div class="form-group">
													          <input type="text" class="form-control" value="\'.$subtitle.\'" data-name="icontitle" name="contentsubtitle" readonly Placeholder="Selected icon code displays here"/>
													        </div>
													    </div>  
													  </div>
													  <div class="col-md-12 fadisplaylisthold">
													    <ul class="fadisplaylist">
													      '.$list.'
													    </ul>
													  </div>
														</div>\';
									$positionout=$subtitle==""?"No Icon Chosen":\'<i class="fa \'.$subtitle.\'"></i>\';
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
				if($pagetype=="productservices"){

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
?>