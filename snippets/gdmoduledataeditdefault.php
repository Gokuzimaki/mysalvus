<?php
	// The purpose here is to parse through the data content already obtained from
	// the gdmoduledataparser.php file and turn it into outputable content
	// for the edit form handling the maintype entry or the user output
	// the output of this is a multidimensional array comprising of sections
	// $gd_vars[0...n]=target variable name 
	// $gd_vals[0...n]=corresponding variable value
	// the above set of variables are used in conjunction with the 
	// get_include_contents() function and represents the variables
	// to be initialised for the file being included.

	// $gd_general[0...n]['var']=target variable name
	// $gd_general[0...n]['val']=target variable value
	// $gd_general[0...n]['script']=target variable script, for setting 
	// selection box values mainly

	// Note
	// make sure the connection.php file is accessible
	// by testing if a function required by the edit form is available
	// this type of edit form is only accessible and useful in the 
	// getSinglGeneralInfo function

	if(!function_exists('pullFontAwesomeClasses')){
		include("connection.php");
	}

	!isset($maintype)?$pagetype="contentdata":$pagetype=$maintype;
	// init the variable and values collection arrays
	$gd_vars=array();
	$gd_vals=array();
	$gd_general=array();
	$gd_general_array=array();
	// this variable acts as a sentinel for managing 
	// entries into the gd_ arrays
	$gd_globalx=0;
	// handle _gdunit content
	// prep variables that need to be available to the editroute file

	// this variable is a substitute for the viewtype requested in the 
	// editroute file, it basically has the same function
	$gd_vars[$gd_globalx]='_gdunit_viewtype';
	$gd_vals[$gd_globalx]="edit";
	$gd_general[$gd_globalx]['var']='_gdunit_viewtype';
	$gd_general[$gd_globalx]['val']="edit";
	$gd_globalx++;
	$gd_vars[$gd_globalx]='viewtype';
	$gd_vals[$gd_globalx]="edit";
	$gd_general[$gd_globalx]['var']='viewtype';
	$gd_general[$gd_globalx]['val']="edit";
	$gd_globalx++;

	$gd_vars[$gd_globalx]='gdunit_id';
	$gd_vals[$gd_globalx]=isset($row['id'])?$row['id']:0;
	$gd_general[$gd_globalx]['var']='gdunit_id';
	$gd_general[$gd_globalx]['val']=isset($row['id'])?$row['id']:0;
	$gd_globalx++;
	$gd_vars[$gd_globalx]='formtruetype';
	$gd_vals[$gd_globalx]=isset($row['formdata'])?$row['formdata']:"";
	$gd_general[$gd_globalx]['var']='formtruetype';
	$gd_general[$gd_globalx]['val']=isset($row['formdata'])?$row['formdata']:"edit";
	$gd_globalx++;
	
	$gd_vars[$gd_globalx]='maintype';
	$gd_vals[$gd_globalx]="$maintype";
	$gd_general[$gd_globalx]['var']='maintype';
	$gd_general[$gd_globalx]['val']="$maintype";
	$gd_globalx++;

	$gd_vars[$gd_globalx]='subtype';
	$gd_vals[$gd_globalx]="$subtype";
	$gd_general[$gd_globalx]['var']='subtype';
	$gd_general[$gd_globalx]['val']="$subtype";
	$gd_globalx++;

	$gd_vars[$gd_globalx]='status';
	$gd_vals[$gd_globalx]=isset($row['status'])?$row['status']:"active";
	$gd_general[$gd_globalx]['var']='status';
	$gd_general[$gd_globalx]['val']=isset($row['status'])?$row['status']:"active";
	$gd_globalx++;

	$gd_vars[$gd_globalx]='_gdunitdisplaytype';
	$gd_vals[$gd_globalx]="true";
	$gd_general[$gd_globalx]['var']='_gdunitdisplaytype';
	$gd_general[$gd_globalx]['val']="true";
	$gd_globalx++;
	// get fieldcount
	$fc=$row['edresultset']['fieldscount'];
	// init the total scripts variable
	$placebo='totalscripts';
	$$placebo=$row['edresultset']['totalscripts'];
	$gd_vars[$gd_globalx]='totalscripts';
	$gd_vals[$gd_globalx]=$row['edresultset']['totalscripts'];
	$gd_general[$gd_globalx]['var']='totalscripts';
	$gd_general[$gd_globalx]['val']=$row['edresultset']['totalscripts'];
	$gd_globalx++;
	$placebo='edittotalscripts';
	$$placebo=$row['edresultset']['edittotalscripts'];
	$gd_vars[$gd_globalx]='edittotalscripts';
	$gd_vals[$gd_globalx]=$row['edresultset']['edittotalscripts'];
	$gd_general[$gd_globalx]['var']='edittotalscripts';
	$gd_general[$gd_globalx]['val']=$row['edresultset']['edittotalscripts'];
	$gd_globalx++;
	// sort through the normal single fields
	if($fc>0){
		
		for ($j=0; $j < $fc; $j++) { 
			$cfnamecount=$row['edresultset']['fieldscount'];
			$cdata=$row['edresultset'][$j]['fieldname'];
			$ctype=$row['edresultset'][$j]['fieldtype'];
			// initialise a variable according to the name fo the field
			$$cdata="";
			// prepare a corresponding _filedata variable for the name
			$fdataout=$cdata."_filedata";
			// echo "$ctype - ctype<br>";
			if($ctype=="file"){
				// echo "$cdata - cdata, fdataout - $fdataout<br>";
				if($ctype=="file"&&$row['edresultset']["$cdata"]['id']>0){
					if(isset($cfiledata)){
						unset($cfiledata);	
					}
					// currentfiledata = cfiledata
					$cfiledata=array();
					$cfiledata['id']=$row['edresultset']["$cdata"]['id'];
					$cfiledata['mediatype']=$row['edresultset']["$cdata"]['mediatype'];
					$cfiledata['location']=$row['edresultset']["$cdata"]['location'];
					$cfiledata['medsizes']=$row['edresultset']["$cdata"]['medsizes'];
					$cfiledata['thumbnail']=$row['edresultset']["$cdata"]['thumbnail'];
					$cfiledata['title']=$row['edresultset']["$cdata"]['title'];
					$cfiledata['preview']=$row['edresultset']["$cdata"]['preview'];
					$cfiledata['details']=$row['edresultset']["$cdata"]['details'];
					$cfiledata['width']=$row['edresultset']["$cdata"]['width'];
					$cfiledata['height']=$row['edresultset']["$cdata"]['height'];
					$cfiledata['filesize']=$row['edresultset']["$cdata"]['filesize'];
					$cfiledata['idoutput']='<input type="hidden" class="form-control" name="'.$cdata.'_id" value="'.$cfiledata['id'].'"/>';
					$cfiledata['deleteoutput']='
						<select class="form-control" name="'.$cdata.'_delete">
		                    <option value="">Delete this?</option> 
		                    <option value="inactive">Yes</option> 
		                </select>';
					$cfiledata['manageoutput']='
						<input type="hidden" class="form-control" name="'.$cdata.'_id" value="'.$cfiledata['id'].'"/>
		                <select class="form-control" name="'.$cdata.'_delete">
		                    <option value="">Delete this?</option> 
		                    <option value="inactive">Yes</option> 
		                </select>
					';
					// echo $row['edresultset']["$cdata"]['thumbnail']." cur data ".$cfiledata['thumbnail']." <br>";
				}else if($ctype=="file"&&$row['edresultset']["$cdata"]['id']==0){
					if(isset($cfiledata)){
						unset($cfiledata);	
					}
					$cfiledata=array();
					$cfiledata['id']="";
					$cfiledata['mediatype']="";
					$cfiledata['location']="";
					$cfiledata['medsizes']="";
					$cfiledata['thumbnail']="";
					$cfiledata['title']="";
					$cfiledata['preview']="";
					$cfiledata['details']="";
					$cfiledata['width']="";
					$cfiledata['height']="";
					$cfiledata['filesize']="";
					$cfiledata['idoutput']="";
					$cfiledata['deleteoutput']="";
					$cfiledata['manageoutput']="";
				}else{
					$cfiledata="";
				}
				// init the variable
				$placebo=$fdataout;
				$$placebo=$cfiledata;
				$gd_vars[$gd_globalx]=$fdataout;
				$gd_vals[$gd_globalx]=$cfiledata;
				$gd_general[$gd_globalx]['var']=$fdataout;
				$gd_general[$gd_globalx]['val']=$cfiledata;
				$gd_general_array[$fdataout]=$cfiledata;
			}else{

				$$fdataout=$cdata;
				$gd_vars[$gd_globalx]=$cdata;
				$gd_vals[$gd_globalx]=$row['edresultset']["$cdata"]['value']!=="NA"&&$row['edresultset']["$cdata"]['value']!==""?$row['edresultset']["$cdata"]['value']:"";
				$gd_general[$gd_globalx]['var']=$cdata;
				$gd_general[$gd_globalx]['val']=$row['edresultset']["$cdata"]['value']!=="NA"&&$row['edresultset']["$cdata"]['value']!==""?$row['edresultset']["$cdata"]['value']:"";
				$gd_general_array[$cdata]=$row['edresultset']["$cdata"]['value']!=="NA"&&$row['edresultset']["$cdata"]['value']!==""?$row['edresultset']["$cdata"]['value']:"";
			}
			$$cdata=$row['edresultset']["$cdata"]['value']!=="NA"&&$row['edresultset']["$cdata"]['value']!==""?$row['edresultset']["$cdata"]['value']:"";
			// handle non file related outputs
			$gd_vars[$gd_globalx]=$cdata;
			$gd_vals[$gd_globalx]=$$cdata;
			$gd_general[$gd_globalx]['var']=$cdata;
			$gd_general[$gd_globalx]['val']=$$cdata;
			$gd_general_array[$cdata]=$$cdata;
			$gd_globalx++;
		}
	}
	// var_dump($coverimage_filedata);
	
	
	  
	// get the content scripts for working with the current entry
	$contentscripts=$row['edresultset']['totalscripts'];
	$multipletest="";
	// groupresult is an array that carries the final result output for a groupset
	// a groupset is a combined set of related fields in an entry
	// for example, a common groupset for a gallery entry would comprise of
	// imagetitle-textfield,imagefile- filefield and imagedetails - textarea
	// this above groupset is available for every image uploaded into the 
	// database
	$groupresult=array();
	// echo "$gc";
	// get groupscount i.e total number of group based results
	$gc=$row['edresultset']['groupscount'];
	if($gc>0){
		for($i=0;$i<$gc;$i++){
			$ix=$i+1;
			// create group output variables
			$gdat="group$ix";
			$gdatcount="group$ix"."_ecount";
			$$gdatcount=0;
			$$gdat="";
			// get current sentinel and set value in variable
			// burst the value set into individual file elements
			$goutput="";
			$cgmonitor="";
			$cglink="";
			$cgdatamap="";
			$cginsertion="";
			// this section handles the creation and setup for groupset content
			// sentinels and options, per group
			$groupsentinel=$row['edresultset'][''.$gdat.'']['sentinel'];
			$sentisplit=explode("count", $groupsentinel);
			// get the original groupdataname
			$tsent=$sentisplit[0];
			$itype="";
			// group limit refers to the min number of entries per group
			$grouplimit="";
			$grouptitletext="";
			$gdeminimum=isset($row['edresultset'][''.$gdat.''][''.$tsent.'_entryminimum'])?$row['edresultset'][''.$gdat.''][''.$tsent.'_entryminimum']:0;
			
			// init variable
			$placebo='gdeminimum'.$ix;
			$$placebo=$gdeminimum;
			$groupdatamap="";
			// represents the executable script contents for the current group
			$curgroupsetscripts="";
			// represents the current set of group entries 
			$curgroupset="";
			// used to hold display text specifying if the current group set requires
			// requires a minimum nuber of entries
			$mingsdetails="";
			// represents the minimum number of entries that accrues to the current group
			// $demcount="";
			// the minimum amount of entries allowed for the current group
			$groupentryminimum="";
			// the total amount of previous entries for the current group
			$curgroupresultcount=0;
			// the current amount of displayed form entries for the current group
			$ctestmult=1;

			//condition block for setting up variable values per group set
			

			$cgmonitor='<input type="hidden" name="'.$groupsentinel.'"  value="'.$ctestmult.'" data-counter="true"/>';
			$cglink='<a href="##'.$gdat.'_ADD" 
							data-type="triggerformadd" 
							data-name="'.$groupsentinel.'_addlink" 
							data-i-type="'.$itype.'" 
							data-limit="'.$grouplimit.'" 
							data-sentineltype="'.$curgroupresultcount.'"
							class="generic_addcontent_trigger"><i class="fa fa-plus"></i> Add More?</a>';
			$cgdatamap='<input name="'.$tsent.'datamap" type="hidden" data-map="true" value="'.$groupdatamap.'"/>';
			$cginsertion='<div name="'.$tsent.'entrypoint" data-marker="true"></div>';
			$curgroupset='<input type="hidden" name="'.$gdat.'_cedit" value="'.$curgroupresultcount.'"/>';

			if(isset($row['edresultset'][''.$gdat.''])
				&&isset($row['edresultset'][''.$gdat.'']['fieldcount'])
				&&$row['edresultset'][''.$gdat.'']['fieldcount']>0){
				
				$curgroup=$row['edresultset'][''.$gdat.''];
				$cfnamecount=$curgroup['fieldcount'];
				
				$curgroupresultcount=$row['edresultset'][''.$gdat.'']['groupcount'];

				$$gdatcount=$curgroupresultcount;
				
				$ctestmult=$curgroupresultcount+1;
				$groupfilevalueset=$row['edresultset'][''.$gdat.'']['filesetvalues'];
				if($groupfilevalueset!==""){
					$gdatfileset=explode(",", $groupfilevalueset);
					if(count($gdatfileset)>0){
						$goutput.='<input type="hidden" name="'.$gdat.'_filecount" value="'.count($gdatfileset).'"/>';
						for ($gk=0; $gk < count($gdatfileset); $gk++) { 
							# code...
							$goutput.='<input type="hidden" name="'.$gdat.'_fileid_'.$gk.'" value="'.$gdatfileset[$gk].'"/>';
						}
					}
				}
				// init variable
				$placebo='gfileoutput'.$ix;
				$$placebo=$goutput;
				// assign the group of input elements holding the id for file entries
				// that have been uploaded to the gd_ arrays
				$gd_vars[$gd_globalx]='gfileoutput'.$ix;
				$gd_vals[$gd_globalx]=$goutput;
				$gd_general[$gd_globalx]['var']='gfileoutput'.$ix;
				$gd_general[$gd_globalx]['val']=$goutput;
				$gd_general_array[$gdat]['gfileoutput'.$ix]=$goutput;
				$gd_globalx++;

				// init variable
				$placebo='cedit'.$ix;
				$$placebo=$curgroupresultcount;
				// assign curgroupresultcount to the gd_ arrays
				// this count is the number of previous entries carried out for the current group
				$gd_vars[$gd_globalx]='cedit'.$ix;
				$gd_vals[$gd_globalx]=$curgroupresultcount;
				$gd_general[$gd_globalx]['var']='cedit'.$ix;
				$gd_general[$gd_globalx]['val']=$curgroupresultcount;
				$gd_general_array[$gdat]['cedit'.$ix]=$curgroupresultcount;
				$gd_globalx++;
				// init variable
				$placebo='itype'.$ix;
				$$placebo=$itype;
				// init variable
				$placebo='grouplimit'.$ix;
				$$placebo=$grouplimit;
				$cgmonitor='<input type="hidden" name="'.$groupsentinel.'"  value="'.$ctestmult.'" data-counter="true"/>';
				/*<div name="galleryslidesentrypoint" data-marker="true"></div>
			            	<input name="galleryslidescount" type="hidden" value="'.$tci.'" data-counter="true"/>
			            	<input name="galleryslidesdatamap" type="hidden" data-map="true" value="galimage-:-input<|>caption-:-input<|>details-:-textarea"/>
			            	<a href="##" class="generic_addcontent_trigger" data-type="triggerformadd" data-name="galleryslidescount_addlink" data-i-type="" data-limit="10"> <i class="fa fa-plus"></i> Add More?</a>*/
				$cglink='<a href="##'.$gdat.'_ADD" 
							data-type="triggerformadd" 
							data-name="'.$groupsentinel.'_addlink" 
							data-i-type="'.$itype.'" 
							data-limit="'.$grouplimit.'" 
							data-sentineltype="'.$curgroupresultcount.'"
							class="generic_addcontent_trigger"><i class="fa fa-plus"></i> Add More?</a>';
				$cgdatamap='<input name="'.$tsent.'datamap" type="hidden" data-map="true" value="'.$groupdatamap.'"/>';
				$cginsertion='<div name="'.$tsent.'entrypoint" data-marker="true"></div>';
				$curgroupset='<input type="hidden" name="'.$gdat.'_cedit" value="'.$curgroupresultcount.'"/>';
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
						// variable to hold the actual 
						$tci=$j+1;
						for ($k=0; $k < $cfnamecount; $k++) { 
							# code...
							// get the field name, check to see if its a media type of field
							// or not, then proceed to create associate values for them
							$cdata=$curgroup[0][$k]['fieldname'];
							$ctype=$curgroup[0][$k]['fieldtype'];
							// turn the field name into an accessible variable
							$$cdata="";
							if(isset($cfiledata)){
								unset($cfiledata);	
							}

							// echo "<br> $j - curcount<br>";
							// var_dump($curgroup[$j]["$cdata"]);
							// get the current value
							$cdataout=isset($curgroup[$j]["$cdata"]['value'])&&$curgroup[$j]["$cdata"]['value']!=="NA"&&$curgroup[$j]["$cdata"]['value']!==""?$curgroup[$j]["$cdata"]['value']:"";
							$$cdata=$cdataout;
							// init variable
							$placebo=$cdata.$ix;
							$$placebo=$$cdata;

							$gd_vars[$gd_globalx]=$cdata."$tci";
							$gd_vals[$gd_globalx]=$$cdata;
							$gd_general[$gd_globalx]['var']=$cdata."$tci";
							$gd_general[$gd_globalx]['val']=$$cdata;
							$gd_general_array[$gdat][$cdata.$tci]=$$cdata;
							$gd_globalx++;
							// prepare a corresponding _filedata variable for the name
							$fdataoutg=$cdata.$tci."_filedata";
							// echo "<br> $ctype - ctype $cdata - cur fieldname $gdat - group<br>";
							if($ctype=="file"){
								if($ctype=="file"&&$curgroup[$j]["$cdata"]['id']>0){
									$cfiledata=array();
									$cfiledata['id']=$curgroup[$j]["$cdata"]['id'];
									$cfiledata['mediatype']=$curgroup[$j]["$cdata"]['mediatype'];
									$cfiledata['location']=$curgroup[$j]["$cdata"]['location'];
									// echo "<br> $cdata - curcdata, ".$curgroup[$j]["$cdata"]['location']." - curvalue <br>";
									$cfiledata['medsizes']=$curgroup[$j]["$cdata"]['medsizes'];
									$cfiledata['thumbnail']=$curgroup[$j]["$cdata"]['thumbnail'];
									$cfiledata['title']=$curgroup[$j]["$cdata"]['title'];
									$cfiledata['preview']=$curgroup[$j]["$cdata"]['preview'];
									$cfiledata['details']=$curgroup[$j]["$cdata"]['details'];
									$cfiledata['width']=$curgroup[$j]["$cdata"]['width'];
									$cfiledata['height']=$curgroup[$j]["$cdata"]['height'];
									$cfiledata['filesize']=$curgroup[$j]["$cdata"]['filesize'];
									$cfiledata['idoutput']='<input type="hidden" class="form-control" name="'.$cdata.''.$tci.'_id" value="'.$cfiledata['id'].'"/>';
									$cfiledata['deleteoutput']='<select class="form-control" name="'.$cdata.''.$tci.'_delete">
						                    <option value="">Delete this?</option> 
						                    <option value="inactive">Yes</option> 
						                </select>';
									$cfiledata['manageoutput']='
										<input type="hidden" class="form-control" name="'.$cdata.''.$tci.'_id" value="'.$cfiledata['id'].'"/>
						                <select class="form-control" name="'.$cdata.''.$tci.'_delete">
						                    <option value="">Delete this?</option> 
						                    <option value="inactive">Yes</option> 
						                </select>
									';
								}else if($ctype=="file"&&$curgroup[$j]["$cdata"]['id']==0){

									$cfiledata=array();
									$cfiledata['id']="";
									$cfiledata['mediatype']="";
									$cfiledata['location']="";
									$cfiledata['medsizes']="";
									$cfiledata['thumbnail']="";
									$cfiledata['title']="";
									$cfiledata['preview']="";
									$cfiledata['details']="";
									$cfiledata['width']="";
									$cfiledata['height']="";
									$cfiledata['filesize']="";
									$cfiledata['idoutput']="";
									$cfiledata['deleteoutput']="";
									$cfiledata['manageoutput']="";
								}else{
									$cfiledata="";
								}
								$$fdataoutg=$cfiledata;
								$gd_vars[$gd_globalx]=$fdataoutg;
								$gd_vals[$gd_globalx]=$cfiledata;
								$gd_general[$gd_globalx]['var']=$fdataoutg;
								$gd_general[$gd_globalx]['val']=$cfiledata;
								$gd_general_array[$gdat][$fdataoutg]=$cfiledata;
								$gd_globalx++;
							}
							// var_dump($cfiledata);
							// echo "<br> $cdataout - curcdata single<br>";

							// make the variable value
							// $tval=$$cdata;
							// echo "$cdata - cdata | $linktitlea - data value<br>";
						}
						// proceed to create all necessary variables
						// $curvalueset=$curgroup[];
						// create the removal portion for the current entry data
						$rmvar='entryremoval'.$tci;
						$$rmvar='
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
						$gd_vars[$gd_globalx]=$rmvar;
						$gd_vals[$gd_globalx]=$$rmvar;
						$gd_general[$gd_globalx]['var']=$rmvar;
						$gd_general[$gd_globalx]['val']=$$rmvar;
						$gd_general_array[$gdat][$rmvar]=$$rmvar;
						$gd_globalx++;
						# code...  
						// proceed to handle group related information accordingly.
						// condition blocks of code can be inserted  here to carry out
						// group entries data in the event of multiple groups, where
						// the variable 'ix' represents the group counter
						if($ix==1){
							
						}
					}
				}
				// add the final open space fields for new group content
				/*$groupresult[$i]='
					'.$cgmonitor.'
					'.$multipletest.'
					'.$cginsertion.'
					'.$cglink.'
				';*/
			}
			// $tcc=$tci+2;
			$tci=isset($tci)?$tci+1:1;

			// mulitple group content data handling condition blocks
			// for post current content coalation
			if($multipletest==""){
				$multipletest="<p>No Content Found</p>";
			}
			// for carrying the new content section
			// ngesection -> newgroupentrysection
			$ngesection="";
			if($ix==1){
				
			}
			$$gdat='

				'.$cgmonitor.'
				'.$groupentryminimum.'
				'.$curgroupset.'
				<div class="box-group" id="'.$pagetype.''.$gdat.'">
					<div class="panel box overflowhidden box-primary">
					    <div class="box-header with-border">
					        <h4 class="box-title">
					          <a data-toggle="collapse" data-parent="#'.$pagetype.''.$gdat.'" href="#'.$gdat.'">
					            <i class="fa fa-gear fa-spin"></i> Edit Previous '.$grouptitletext.'Content '.$mingsdetails.'
					          </a>
					        </h4>
					    </div>
					    <div id="'.$gdat.'" class="panel-collapse collapse overflowhidden">
							'.$multipletest.'
					    </div>
					</div>
				</div>
				'.$ngesection.'
				'.$goutput.'
				'.$cginsertion.'
				'.$cgdatamap.'
				'.$cglink.'
				<script>
					$(document).ready(function(){
						'.$curgroupsetscripts.'
					})
				</script>
				';
		}
		// make the current set of data entries collapsible to leave 
		// only the new ones in view


	}
	// assign all the values of the gd_general_array to a gd_vars local variable
	$gd_vars[$gd_globalx]="gd_general_array";
	$gd_vals[$gd_globalx]=$gd_general_array;
	$gd_globalx++;
?>