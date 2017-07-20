<?php
	// make sure the connection.php file is accessible
	// by testing if a function required by the edit form is available
	if(!function_exists('pullFontAwesomeClasses')){
		include("connection.php");
	}
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
	// for form elemenets whose data needs to be ignored, add the data attribute
	// data-edittype="true" to push the systems "ignore button"
	// get groupscount i.e total number of group based results
	$gc=$row['edresultset']['groupscount'];
	// get fieldcount
	$fc=$row['edresultset']['fieldscount'];
	// get the content scripts for working with the current entry
	$contentscripts=$row['edresultset']['totalscripts'];
	$multipletest="";
	// groupresult is an array that carries the final result output for a groupset
	$groupresult=array();
	// echo "$gc";
	if($gc>0){
		for($i=0;$i<$gc;$i++){
			$ix=$i+1;
			// create group output variables
			$gdat="group$ix";
			$$gdat="";
			// get current sentinel and set value in variable
			if(isset($row['edresultset'][''.$gdat.''])
				&&isset($row['edresultset'][''.$gdat.'']['fieldcount'])
				&&$row['edresultset'][''.$gdat.'']['fieldcount']>0){
				$curgroup=$row['edresultset'][''.$gdat.''];
				$cfnamecount=$curgroup['fieldcount'];

				$curgroupresultcount=$row['edresultset'][''.$gdat.'']['groupcount'];
				$groupsentinel=$row['edresultset'][''.$gdat.'']['sentinel'];
				$ctestmult=$curgroupresultcount+2;
				$cgmonitor='<input type="hidden" name="'.$groupsentinel.'" value="'.$ctestmult.'"/>';
				$cglink='<a href="##'.$gdat.'_ADD" data-type="triggerformadd" data-name="'.$groupsentinel.'_addlink" data-i-type="default" data-t-insertion="entrypoint'.$gdat.''.$groupsentinel.'" name="add'.$groupsentinel.'"
				 class="add_trigger_link">Add More?</a>';
				 $cginsertion='<div name="entrypoint'.$gdat.''.$groupsentinel.'"></div>';
				// echo "<br>$curgroupresultcount - $cfnamecount<br>";

				// create the sentinel number variable used in differentiating
				// each element name in the group from the others
				// eg linktitlea - element name generic to a group
				// linktitlea.$tci => linktitle1, - specific name for 
				// single entry in the group
				$tci=0;
				// if the current group has results in it
				if($curgroupresultcount>0){
					$max_lenght=6;
					for ($j=0; $j < $curgroupresultcount; $j++) { 
						// echo "in j here";
						for ($k=0; $k < $cfnamecount; $k++) { 
							# code...
							// get the field name, check to see if its a media type of field
							// or not, the proceed to create associate values for them
							$cdata=$curgroup[0][$k]['fieldname'];
							// turn the field name into an accessible variable
							$$cdata="";
							$$cdata=$curgroup[$j]["$cdata"]['value']!=="NA"?$curgroup[$j]["$cdata"]['value']:"";

							// make the variable value
							// $tval=$$cdata;
							// echo "$cdata - cdata | $linktitlea - data value<br>";
						}
						// proceed to create all necessary variables
						// $curvalueset=$curgroup[];
						$tci=$j+1;
						// create the removal portion for the current entry data

						$entryremoval='

							<div class="col-sm-12"> 
				              <div class="form-group">
				                <label>Remove Entry '.$tci.':</label>
				                <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-file-text"></i>
				                  </div>
				                  <select class="form-control" name="'.$gdat.'_status'.$tci.'">
				                    <option value="">Delete this?</option> 
				                    <option value="inactive">Yes</option> 
				                  </select>

				                </div><!-- /.input group -->
				              </div><!-- /.form group -->
				            </div>
						';
						# code...  
					    $multipletest.='  
				            <div class="col-sm-4"> 
				              <div class="form-group">
				                <label>Link Title A '.$tci.':</label>
				                <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-file-text"></i>
				                  </div>
				                  <input type="text" class="form-control" name="linktitlea'.$tci.'" Placeholder="display title for the link" value="'.$linktitlea.'"/>
				                </div><!-- /.input group -->
				              </div><!-- /.form group -->
				            </div>
				            <div class="col-sm-4"> 
				              <div class="form-group">
				                <label>Link Title B '.$tci.'</label>
				                <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-file-text"></i>
				                  </div>
				                  <input type="text" class="form-control" name="linktitleb'.$tci.'" Placeholder="display title for the link" value="'.$linktitleb.'"/>
				                </div><!-- /.input group -->
				              </div><!-- /.form group -->
				            </div>
				            <div class="col-sm-4"> 
				              <div class="form-group">
				                <label>Link Title C: '.$tci.'</label>
				                <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-file-text"></i>
				                  </div>
				                  <select class="form-control" name="linktitlec'.$tci.'">
				                    <option value="">Choose Something</option> 
				                    <option value="Opt 1">Opt 1</option> 
				                    <option value="Opt 2">Opt 2</option> 
				                    <option value="Opt 3">Opt 3 Something</option> 
				                  </select>
				                  <!-- <input type="text" class="form-control" name="linktitlec" Placeholder="display title for the link"/> -->
				                </div><!-- /.input group -->
				              </div><!-- /.form group -->
				            </div>  
				            '.$entryremoval.'
					    ';       
					}
				}
				$tcc=$tci+2;
				$tci=$tci+1;
				// add the final open space fields for new group content
				$multipletest.='
							<h4 class="newgroupadditionheadingclass">
								Add New Entries
							</h4>  
				            <div class="col-sm-4"> 
				              <div class="form-group">
				                <label>Link Title A:</label>
				                <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-file-text"></i>
				                  </div>
				                  <input type="text" class="form-control" name="linktitlea'.$tci.'" Placeholder="display title for the link" value=""/>
				                </div><!-- /.input group -->
				              </div><!-- /.form group -->
				            </div>
				            <div class="col-sm-4"> 
				              <div class="form-group">
				                <label>Link Title B</label>
				                <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-file-text"></i>
				                  </div>
				                  <input type="text" class="form-control" name="linktitleb'.$tci.'" Placeholder="display title for the link" value=""/>
				                </div><!-- /.input group -->
				              </div><!-- /.form group -->
				            </div>
				            <div class="col-sm-4"> 
				              <div class="form-group">
				                <label>Link Title C:</label>
				                <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-file-text"></i>
				                  </div>
				                  <select class="form-control" name="linktitlec'.$tci.'">
				                    <option value="">Choose Something</option> 
				                    <option value="Opt 1">Opt 1</option> 
				                    <option value="Opt 2">Opt 2</option> 
				                    <option value="Opt 3">Opt 3 Something</option> 
				                  </select>
				                  <!-- <input type="text" class="form-control" name="linktitlec" Placeholder="display title for the link"/> -->
				                </div><!-- /.input group -->
				              </div><!-- /.form group -->
				            </div> 
				            <div class="col-sm-4"> 
				              <div class="form-group">
				                <label>Link Title A:</label>
				                <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-file-text"></i>
				                  </div>
				                  <input type="text" class="form-control" name="linktitlea'.$tcc.'" Placeholder="display title for the link" value=""/>
				                </div><!-- /.input group -->
				              </div><!-- /.form group -->
				            </div>
				            <div class="col-sm-4"> 
				              <div class="form-group">
				                <label>Link Title B</label>
				                <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-file-text"></i>
				                  </div>
				                  <input type="text" class="form-control" name="linktitleb'.$tcc.'" Placeholder="display title for the link" value=""/>
				                </div><!-- /.input group -->
				              </div><!-- /.form group -->
				            </div>
				            <div class="col-sm-4"> 
				              <div class="form-group">
				                <label>Link Title C:</label>
				                <div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-file-text"></i>
				                  </div>
				                  <select class="form-control" name="linktitlec'.$tcc.'">
				                    <option value="">Choose Something</option> 
				                    <option value="Opt 1">Opt 1</option> 
				                    <option value="Opt 2">Opt 2</option> 
				                    <option value="Opt 3">Opt 3 Something</option> 
				                  </select>
				                  <!-- <input type="text" class="form-control" name="linktitlec" Placeholder="display title for the link"/> -->
				                </div><!-- /.input group -->
				              </div><!-- /.form group -->
				            </div>  
					    ';
				$$gdat='
					'.$cgmonitor.'
					'.$multipletest.'
					'.$cginsertion.'
					'.$cglink.'';
				/*$groupresult[$i]='
					'.$cgmonitor.'
					'.$multipletest.'
					'.$cginsertion.'
					'.$cglink.'
				';*/
			}
		}
		// make the current set of data entries collapsible to leave 
		// only the new ones in view

	}
	if(!isset($group1)){
		$group1="";
	}
	// sort through the normal single fields
	if($fc>0){
		
		for ($j=0; $j < $fc; $j++) { 
			$cfnamecount=$row['edresultset']['fieldscount'];
			$cdata=$row['edresultset'][$j]['fieldname'];
			$$cdata="";
			$$cdata=$row['edresultset']["$cdata"]['value']!=="NA"?$row['edresultset']["$cdata"]['value']:"";
			/*// echo "in j here";
			for ($k=0; $k < $cfnamecount; $k++) { 
				# code...
				// get the field name, check to see if its a media type of field
				// or not, the proceed to create associate values for them
				// turn the field name into an accessible variable

				// make the variable value
				// $tval=$$cdata;
				// echo "$cdata - cdata | $linktitlea - data value<br>";
			}*/
		}
	}
	// get all related variables
	// SETUP icon
	$iconoutput="";
	if ($icontitle!=="") {
		# code...
		$iconoutput='fa '.$icontitle.'';
	}
	$extraformdata='
		<div class="col-md-12" name="surveysliderpoint">
		        <div class="col-md-12">
		            <div class="col-sm-6"> 
		              <div class="form-group">
		                <label>Choose Color Group</label>
		                <div class="input-group">
		                  <div class="input-group-addon">
		                    <i class="fa fa-file-text"></i>
		                  </div>
		                  <select name="collagecolourtype" class="form-control">
		                    <option value="">Choose Collage Color Group</option>
		                    <option value="red">Red</option>
		                    <option value="orange">Orange</option>
		                    <option value="yellow">Yellow</option>
		                    <option value="green">Green</option>
		                    <option value="blue">Blue</option>
		                    <option value="purple">Indigo</option>
		                    <option value="maroon">Violet</option>
		                  </select>
		                </div><!-- /.input group -->
		              </div><!-- /.form group -->
		            </div>
		            <div class="col-sm-6"> 
		              <div class="form-group">
		                <label>Collage Box Title
		                </label>
		                <div class="input-group">
		                  <div class="input-group-addon">
		                    <i class="fa fa-file-text"></i>
		                  </div>
		                  <input type="text" class="form-control" name="contenttitle" Placeholder="The collage box title" value="'.$contenttitle.'"/>
		                </div><!-- /.input group -->
		              </div><!-- /.form group -->
		            </div>
		            <div class="col-md-12">
		                <label>Collage Box Content(brief content)</label>
		                <textarea class="form-control" rows="3" name="contentpost" data-mce="true" id="postersmallsix" placeholder="Collage box details">'.$contentpost.'</textarea>
		            </div>
		            <div class="col-sm-6"> 
		              <div class="form-group">
		                <label>link Address(if this collage box leads to another page):</label>
		                <div class="input-group">
		                  <div class="input-group-addon">
		                    <i class="fa fa-file-text"></i>
		                  </div>
		                  <input type="text" class="form-control" name="linkaddress" Placeholder="Full Link address " value="'.$linkaddress.'"/>
		                </div><!-- /.input group -->
		              </div><!-- /.form group -->

		            </div>
		            <div class="col-sm-6"> 
		              <div class="form-group">
		                <label>Link Title(The text the link displays):</label>
		                <div class="input-group">
		                  <div class="input-group-addon">
		                    <i class="fa fa-file-text"></i>
		                  </div>
		                  <input type="text" class="form-control" name="linktitle" value="'.$linktitle.'" Placeholder="display title for the link"/>
		                </div><!-- /.input group -->
		              </div><!-- /.form group -->
		            </div>  
		        </div>
		        '.$group1.'
		        <div class="col-md-12 faselectsectionhold">
		          <div class="col-md-12 fadisplaypoint">
		            <div class="col-sm-12"><h3 class="labelheading">Change Collage Icon <small>(To remove icon simply click/tap the current selected icon)</small></h3></div>
		            <div class="col-md-1 currentfa">
		              <i class="'.$iconoutput.'"></i>
		            </div>
		            <div class="col-md-4 textfieldfa">
		                <div class="form-group">
		                  <input type="text" class="form-control" name="icontitle" value="'.$icontitle.'" readonly Placeholder="Selected icon code displays here"/>
		                </div>
		            </div>  
		          </div>
		          <div class="col-md-12 fadisplaylisthold">
		            <ul class="fadisplaylist">
		              '.$list.'
		            </ul>
		          </div>
		        </div>
		        <!-- form control section -->
		        <input type="hidden" name="formdata" value="'.$formdata.'"/>
		        <input type="hidden" name="extraformdata" value="'.$extraformdata.'"/>
		        <input type="hidden" name="errormap" value="'.$errormap.'"/>

		          <!--  -->
		        	
		</div>
    ';
    /*echo "<script src='../scripts/jquery.js'></script>
    		$extraformdata<br>
			<script>
				$(document).ready(function(){
					$totalscripts
				})
			</script>
	";*/

	// hide unwanted content
	$showhidetitle="display:none;";
    $showhidesubtitle="display:none;";
    $showhideimage="display:none;";
    $showhideintro="display:none;";
    $showhidecontent="display:none;";
    $formdataname='edit'.$formdata;
    $formsubmitname="submitcustomtwo";
    $formsubmittitle="Update Collage Box";
    $formsubmittype="button";
    $curvariant="contententryupdate";
    $formsubmitdataattr="data-formdata='$formdataname'";
    $contenttextheaderout="Edit Collage Box Entry";
	$wholescript="";
    $extraformscript='
    	tinyMCE.init({
          theme : "modern",
          selector:"#postersmallsix",
          menubar:false,
          statusbar: false,
          plugins : [
           "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
           "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
           "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
          ],
          width:"100%",
          height:"200px",
          toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
          toolbar2: " | link unlink anchor | emoticons",
          image_advtab: true ,
          editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
          content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
          external_filemanager_path:""+host_addr+"scripts/filemanager/",
          filemanager_title:"Content Filemanager" ,
          external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
  		});
    '.$totalscripts;
	// check to see if the view is admin or viewer based
    // if its a viewer
    // create output eval for singlegeneralinfo
    // echo $viewer;
    if (isset($viewertype)&&$viewertype=="viewer") {
    	# code...
		$contentgroupdata['evaldata']['single']['postoutputeval']='
				$viewericonout="";
	            if(isset($icontitle)&&$icontitle!==""){
	            	$viewericonout=\'<span class="heading-icon"><i class="\'.$iconoutput.\'"></i></span>\';
	            }
				$row[\'vieweroutputmini\']=\'
					<div class="col-md-6 briefsub-section bg-\'.$collagecolourtype.\'-gradient">
	    				<div class="generic-heading">
	                        <h2>
	                        	\'.$viewericonout.\'
	                        	\'.$contenttitle.\'
	                        </h2>
	                    </div>
	                    <div class="generic-content-holder">
	                    	<div class="generic-content-paragraph">
	                        	\'.$contentpost.\'
	                        </div>
	                    	<a href="\'.$linkaddress.\'" target="blank" class="readmore lead-on-btn color-\'.$collagecolourtype.\'">\'.$linktitle.\'</a>
	                    </div>
	    			</div>
				\';
		';

    	// var_dump($contentgroupdata);
    }
?>
