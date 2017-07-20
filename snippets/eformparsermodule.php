<?php
	// this form is used to parse form post data using the extraformdata system
	// it basically takes the current form data present the proceeds to 

	// make sure the connection.php file is accessible
	// by testing if a function required by the edit form is available
	
	function parseEFormData($data){
		include('globalsmodule.php');
		$row=array();
		$fulltally="";
		$extraformdata=$data['extraformdata'];
		$errormap=$data['errormap'];
		$entryid=$data['entryid'];
		$formdata=$data['formdata'];
		// handling extra data media content that require an id from the media table
		if($entryid==""||$entryid==0){
			$cexdataid=getNextId('generalinfo');
		}else{
			$cexdataid=$entryid;
		}
		$extraformdata=isset($data['extraformdata'])?mysql_real_escape_string($data['extraformdata']):"";
		$errormap=isset($data['errormap'])?mysql_real_escape_string($data['errormap']):"";
		$formdata=isset($data['formdata'])?mysql_real_escape_string($data['formdata']):"";
		$maintype=isset($data['maintype'])?mysql_real_escape_string($data['maintype']):"";
		$subtype=isset($data['subtype'])?mysql_real_escape_string($data['subtype']):"";
		$finaltally="";
		// create data replacement array for taking care of system reserved 
		// character sets used as delimiters
		// obtained from http://www.ascii.cl/htmlcodes.htm
		$fulldatasearch= array('[',']','-|-','|','-:-','&#34;');
		$fulldatareplace= array('&#91;','&#93;','&#45;&#124;&#45;','&#124;','&#45;&#58;&#45;','"');
		if($extraformdata!==""){
			// trim(preg_replace('/\t+/'

		  	$extraformdata=stripslashes(trim(preg_replace('/\t+/',"",$extraformdata)));
		  	$extraformdata=preg_replace("/(\n\r)|(\r\n)|(\\\\r\\\\n)|(\\\\\\r\\\\\\n)/","",$extraformdata);
		  	$extraformdata=str_replace(array("\\r\\n","\\n\\r","\n\r", "\r\n",  "\r", "\n", "\r"," "), "", $extraformdata);
		  	/*$extraformdata=str_replace("\r\n", "", $extraformdata);
		  	$extraformdata=str_replace("\n", "", $extraformdata);
		  	$extraformdata=str_replace("\r", "", $extraformdata);
		  	$extraformdata=str_replace(" ", "", $extraformdata);*/
		  	// echo str_replace("<", "&lt;", $extraformdata)." - extraformdata eformparsed<br><br>";
		  
		  
		  	// try to get a list of the current entries in the form
			$fdata=explode("<|>", $extraformdata);
			// string of K:V pairs for storing extra form data
			$finaltally="";
			$concat="<|>";
			$curgroupcount=0;
			if(count($fdata)>0){
				$curgroupdata="";
				for($i=0;$i<count($fdata);$i++){
					$curdata=$fdata[$i];
					// echo str_replace("<", "&lt;", $curdata)." - curdata<br>";
					
					$sortdata=strpos($curdata, "egroup|data");
					if($sortdata!==false||$sortdata===true){
						$curgroupcount++;
						$curgroupdata="";
						$fielddata=array();
						$subgroup=explode("egroup|data-:-", $curdata);
						
						// break off the requirements portion of the map
						$subgroup=explode("-:-",$subgroup[1]);
						
						// store requirements data seperate
						$reqdata=$subgroup[1];
						
						// get the contents in the current group
						preg_match_all("~([^\[\]]{1,})~im", $subgroup[0], $subgroup);
						$subgroup=$subgroup[0];
						// echo str_replace("<", "&lt;", $subgroup[0])." - subgroup<br>";
						// split the subgroup
						$subgroup=explode(">|<", $subgroup[0]);
						
						// shift the sentinel counter content from the array
						$sentinel=array_shift($subgroup);

						// get the original name for the current group
		  				// $sentinelname=str_replace("count", "", $sentinel);
		  				// echo $sentinel." - sentinel <br>";
		  				// $sentinel=str_replace("\\r\\n", "", $sentinel);
						// store the sentinel name
		  				$sentinel=str_replace("\\r\\n", "", $sentinel);
						// check to see if a _entryminimum value is present
						$gdtest=strpos($sentinel, ":*:");
						$eminimum=0;
						if($gdtest===0||$gdtest===true||$gdtest>0){
							$gdexp=explode(":*:", $sentinel);
							$sentinel=$gdexp[0];
						}
						// get the groups original name
						$groupd=explode("count", $sentinel);
						$groupname=$groupd[0];

						// get the group minimum number of entries
						$entryminimum=0;
						if(isset($_POST['group'.$curgroupcount.'_entryminimum'])&&$_POST['group'.$curgroupcount.'_entryminimum']>0){
							$entryminimum=$_POST['group'.$curgroupcount.'_entryminimum'];
							// echo "<br>$entryminimum <br>".$_POST['group'.$curgroupcount.'_entryminimum']."<br>";
						}else
						// get the group minimum number of entries using the non generic set groupname
						if(isset($_POST[''.$groupname.'_entryminimum'])&&$_POST[''.$groupname.'_entryminimum']>0){
							$entryminimum=$_POST[''.$groupname.'_entryminimum'];
							echo "<br>$entryminimum <br>".$_POST[''.$groupname.'_entryminimum']."<br>";
						}
						
						$cedit=0;
						// get the current number of edittable entries
						if(isset($_POST['group'.$curgroupcount.'_cedit'])&&$_POST['group'.$curgroupcount.'_cedit']!==""){
							$cedit=$_POST['group'.$curgroupcount.'_cedit'];
							// echo "<br>$cedit <br>".$_POST['group'.$curgroupcount.'_cedit']."<br>";
						}else if(isset($_POST[''.$groupname.'_cedit'])&&$_POST[''.$groupname.'_cedit']!==""){
							$cedit=$_POST[''.$groupname.'_cedit'];
							// echo "<br>$cedit <br>".$_POST[''.$sentinelname.''.$curgroupcount.'_cedit']."<br>";
						}
						
						// get the value for the current number of entries in the grouped data
						$sentinel=isset($_POST[''.$sentinel.''])?$_POST[''.$sentinel.'']:0;
						
						if($sentinel>0){
							// split the content into a new array based on the -:- delimiter
							for($t=0;$t<count($subgroup);$t++){
								// get the name of the form element that carries groupdata 
								// content
								$splitcurgroup=explode("-|-", $subgroup[$t]);
								$fieldname=$splitcurgroup[0];
								$fieldtype=$splitcurgroup[1];
								$sortfieldtype=strpos($fieldtype,"|");
								$fieldentrytype="";
								$fieldextension="";
								if($sortfieldtype!==false||$sortfieldtype===true){
									$sftexplode=explode("|", $fieldtype);
									// echo "<br>ran here<br>";
									$fieldtype=$sftexplode[0];
									$fieldentrytype=$sftexplode[1];
									if(count($sftexplode)>2){
										$fieldextension=$sftexplode[2];
									}
								}
								$fielddata[$t]['fieldname']=$splitcurgroup[0];
								$fielddata[$t]['fieldtype']=$fieldtype;
								$fielddata[$t]['fieldentrytype']=$fieldentrytype;
								$fielddata[$t]['fieldextension']=$fieldextension;
								// echo "<br>groupset $fieldtype $fieldentrytype $fieldextension<br>";
							}
							// prep storage variables for batch sets of grouped form data
							$batcharr=array();
							$nbatcharr=array();
							$batchvalues="";
							$curdodelete="";
							
							// this variable holds the current amount of valid
							// new entries, it increments when new entries are succesfully
							// created
							$mainscud=0;

							// Sectionfor performing delete monitoring on groupdata if entryminimum
							// is provided
							if($cedit>0&&$sentinel>=$cedit&&$entryminimum>0){
								// this means there are new entries present
								// run through latest entry for loop
								for ($x=$cedit; $x < $sentinel; $x++) { 
									// reset the curgroupdata string variable to nothing
									$curgroupdata="";
									# code...
									// add one to the increment variable to get the name appended numeric count
									// value of the relative form element name in the $_POST array
									$xt=$x+1;
									$batchvalues="";
									// create the batch storage string 
									// use a sentinel variable to perform basic data test for each entryset
									// ensuring at least one value is present
									$scud=0;
									$excludecurdata="";
									$curentrysetvcount=0;
									

									for ($z=0; $z < count($fielddata); $z++) { 
										# code...
										// use the field name of the multiple content accompanied by
										// the corresponding xt - count value form above to get the current
										// multiple data content POSt element in the set
										$noupload="true";
										// echo "<br>cur field entry<br>".$fielddata[$z]['fieldentrytype'];
										
										if($fielddata[$z]['fieldentrytype']!==""){
											if ($fielddata[$z]['fieldentrytype']=="image"||
												$fielddata[$z]['fieldentrytype']=="video"||
												$fielddata[$z]['fieldentrytype']=="office"||
												$fielddata[$z]['fieldentrytype']=="audio"||
												$fielddata[$z]['fieldentrytype']=="pdf") {
												$noupload="false";
												//proceed to carryout the upload and obtain
												// transaction id which would be used to set up the data entry
												// in the form 'media|id' where id is the media table value for
												// the entry.
												if($fielddata[$z]['fieldentrytype']=="image"){
												    $contentpic=isset($_FILES[''.$fielddata[$z]['fieldname'].''.$xt.'']['tmp_name'])?$_FILES[''.$fielddata[$z]['fieldname'].''.$xt.'']['tmp_name']:"";
													if($contentpic!==""){
														$genericmainttype=$fielddata[$z]['fieldname'];
													    $image=''.$fielddata[$z]['fieldname'].''.$xt.'';
													    $imgpath[0]='../files/originals/';
													    $imgpath[1]='../files/medsizes/';
													    $imgpath[2]='../files/thumbnails/';
													    $imgsize[0]="default";
													    // check for height and width management variables
													    $mimgwidth=isset($_POST[''.$fielddata[$z]['fieldname'].'_groupsizemw'])?$_POST[''.$fielddata[$z]['fieldname'].'_groupsizemw']:"";
													    $mimgheight=isset($_POST[''.$fielddata[$z]['fieldname'].'_groupsizemh'])?$_POST[''.$fielddata[$z]['fieldname'].'_groupsizemh']:"300";
													    $imgsize[1]="$mimgwidth,$mimgheight";
													    if($mimgwidth=="default"||$mimgheight=="default"){
													    	$imgsize[1]="default";
													    }
													    $timgwidth=isset($_POST[''.$fielddata[$z]['fieldname'].'_groupsizetw'])?$_POST[''.$fielddata[$z]['fieldname'].'_groupsizetw']:"";
													    $timgheight=isset($_POST[''.$fielddata[$z]['fieldname'].'_groupsizeth'])?$_POST[''.$fielddata[$z]['fieldname'].'_groupsizeth']:"100";
													    $imgsize[2]="$timgwidth,$timgheight";
													    $acceptedsize="";
													    if(isset($_POST['img_default_medsizes'])){
													    	$imgsize[1]=$_POST['img_default_medsizes'];
													    }else if(isset($_POST['img_default_thumbsizes'])){
													    	$imgsize[2]=$_POST['img_default_thumbsizes'];
													    }
													    $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
													    $len=strlen($imgouts[0]);
													    $imagepath=substr($imgouts[0], 1,$len);
													    // medsize
													    $p2=isset($imgouts[1])?
													    $imgouts[1]:$imgouts[0];
													    $len2=strlen($p2);
													    $imagepath2=substr($p2, 1,$len2);
													    // thumbnail
													    $p3=isset($imgouts[2])?
													    $imgouts[2]:$imgouts[0];
													    $len3=strlen($p3);
													    $imagepath3=substr($p3, 1,$len3);
													    // get image size details
													    list($width,$height)=getimagesize($imgouts[0]);
													    $imagesize=$_FILES[''.$image.'']['size'];
													    $filesize=$imagesize/1024;
													    //echo $filefirstsize;
													    $filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
													    if(strlen($filesize)>3){
														    $filesize=$filesize/1024;
														    $filesize=round($filesize,2); 
														    $filesize="".$filesize."MB";
													    }else{
													    	$filesize="".$filesize."KB";
													    }
													    $coverpicid=getNextIdExplicit("media");
													    
												    	$cvalue="$coverpicid";
													    //maintype variants are original, medsize, thumb for respective size image.
													    $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,medsize,thumbnail,filesize,width,height)
													    VALUES
													    ('$entryid','$maintype','$genericmainttype','image','$imagepath','$imagepath2','$imagepath3','$filesize','$width','$height')";
														// echo "<br> $mediaquery <br>";
													    $mediarun=mysql_query($mediaquery)or die(mysql_error());
													    
														$fielddata[$z]['fieldvalue']="media|$cvalue";
													}else{
														$fielddata[$z]['fieldvalue']="media|0";
													}
												}else{
												    $contentfile=isset($_FILES[''.$fielddata[$z]['fieldname'].''.$xt.'']['tmp_name'])?$_FILES[''.$fielddata[$z]['fieldname'].''.$xt.'']['tmp_name']:"";
													if ($contentfile!=="") {
														# code...
														$cextension=getExtension($contentfile);
														$ccontenttype=$fielddata[$z]['fieldentrytype'].$cextension;
														if($fielddata[$z]['fieldentrytype']=="video"){
															$cfolder='/videos/';
														}else if ($fielddata[$z]['fieldentrytype']=="audio") {
															# code...
															$cfolder='/audio/';
														}else{
															$cfolder="/";
														}
														$outsaudio=simpleUpload(''.$fielddata[$z]['fieldname'].''.$xt.'','../files'.$cfolder.'');
														$audiofilepath=$outsaudio['filelocation'];
														$len=strlen($audiofilepath);
														$audiofilepath=substr($audiofilepath, 1,$len);
														$audiofilesize=$outsaudio['filesize'];
													    $contentfileid=getNextIdExplicit("media");		
													    // echo "<br>".$contentid." - content id";												
														
														$cvalue="media|$contentfileid";
														$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)
														VALUES
														('$entryid','$maintype','$subtype','$ccontenttype','$audiofilepath','$audiofilesize')";
														// echo "<br>$mediaquery2 <br>";
														$mediarun2=mysql_query($mediaquery2)or die(mysql_error()." Line ".__LINE__);			
														
														$fielddata[$z]['fieldvalue']="$cvalue";
													}else{
														$fielddata[$z]['fieldvalue']="media|0";

													}
												}
											}
										}
										if($noupload=="true"){
											if($fielddata[$z]['fieldtype']!=="textarea"){
												$fielddata[$z]['fieldvalue']=isset($_POST[''.$fielddata[$z]['fieldname'].''.$xt.''])?str_replace($fulldatasearch, $fulldatareplace, mysql_real_escape_string($_POST[''.$fielddata[$z]['fieldname'].''.$xt.''])):"NA";
											}else{
												$fielddata[$z]['fieldvalue']=isset($_POST[''.$fielddata[$z]['fieldname'].''.$xt.''])?str_replace($fulldatasearch, $fulldatareplace, mysql_real_escape_string($_POST[''.$fielddata[$z]['fieldname'].''.$xt.''])):"NA";
											}
										}

										// increment the sentinel control variable to allow 
										// monitoring of batch entries with no values
										if($fielddata[$z]['fieldvalue']==""||$fielddata[$z]['fieldvalue']=="media|0"){
											$scud++;
										}
										// concatenate the values obtained into 
										//  a single variable for array storage and 
										// later parsing
										if($batchvalues==""){
											$batchvalues=$fielddata[$z]['fieldname']?$fielddata[$z]['fieldvalue']."-|-".$fielddata[$z]['fieldname']:"";
												
										}else{
											$batchvalues.=$fielddata[$z]['fieldname']?">|<".$fielddata[$z]['fieldvalue']."-|-".$fielddata[$z]['fieldname']:"";
										}
										
									}

									// remove the entry if no single valid value was found or a
									// deletion occured
									// echo "<br>curbatch values - $batchvalues<br>";
									if($scud==count($fielddata)){
										$batchvalues="";
										$scud=0;
									}

									// store the batch storage string in the batcharr variable array
									// this array is later used to compile all entries into one delimited string
			  						// echo $batchvalues." - batchvalues<br><br>";
									if($batchvalues!==""){
										// count the new entries
										$mainscud++;
										$nbatcharr[]=$batchvalues;	
									}
								}

								// get the current available amount of deletable
								// content by adding the total number of edittable
								// content and the total number of new entries
								// then subtracting the entryminimum from them

								$deletecount=($cedit+$mainscud)-$entryminimum;
								echo "Delete Count - $deletecount";
								// run through the entire value set of the group data
								// sentinel with a forloop
								// for previous entries
								for ($x=0; $x < $cedit; $x++) { 
									// reset the curgroupdata string variable to nothing
									$curgroupdata="";
									# code...
									// add one to the increment variable to get the name appended numeric count
									// value of the relative form element name in the $_POST array
									$xt=$x+1;
									$batchvalues="";
									// create the batch storage string 
									// use a sentinel variable to perform basic data test for each entryset
									// ensuring at least one value is present
									$scud=0;
									$excludecurdata="";
									// $groupentryminimum=0;
									$curentrysetvcount=0;
									// remove content group that has been deleted
									if(isset($_POST['group'.$curgroupcount.'_status'.$xt.''])&&$_POST['group'.$curgroupcount.'_status'.$xt.'']!==""){
										$excludecurdata="true";
										// echo "<br>$excludecurdata <br>".$_POST['group'.$curgroupcount.'_status'.$xt.'']."<br>";
									}
									echo "<br>Cur Exclude data: ".$excludecurdata."<br>";
									if($excludecurdata==""||($excludecurdata!==""&&$deletecount==0)){
										for ($z=0; $z < count($fielddata); $z++) { 
											# code...
											// use the field name of the multiple content accompanied by
											// the corresponding xt - count value form above to get the current
											// multiple data content POSt element in the set
											$noupload="true";
											// echo "<br>No delete cur field entrytype<br>".$fielddata[$z]['fieldentrytype'];
											if($fielddata[$z]['fieldentrytype']!==""){
												if ($fielddata[$z]['fieldentrytype']=="image"||
													$fielddata[$z]['fieldentrytype']=="video"||
													$fielddata[$z]['fieldentrytype']=="office"||
													$fielddata[$z]['fieldentrytype']=="audio"||
													$fielddata[$z]['fieldentrytype']=="pdf") {
													$noupload="false";
													// proceed to carryout the upload and obtain
													// transaction id which would be used to set up the data entry
													// in the form 'media|id' where id is the media table value for
													// the entry.
													if($fielddata[$z]['fieldentrytype']=="image"){
													    $contentpic=isset($_FILES[''.$fielddata[$z]['fieldname'].''.$xt.'']['tmp_name'])?$_FILES[''.$fielddata[$z]['fieldname'].''.$xt.'']['tmp_name']:"";
														$imgid=isset($_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_id'])?mysql_real_escape_string($_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_id']):0;
														$contentdel=isset($_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_delete'])?mysql_real_escape_string($_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_delete']):"";

														if($contentdel==""){
															if($contentpic!==""){
																$genericmainttype=$fielddata[$z]['fieldname'];
															    $image=''.$fielddata[$z]['fieldname'].''.$xt.'';
															    $imgpath[0]='../files/originals/';
															    $imgpath[1]='../files/medsizes/';
															    $imgpath[2]='../files/thumbnails/';
															    $imgsize[0]="default";
															    $mimgwidth=isset($_POST[''.$fielddata[$z]['fieldname'].'_groupsizemw'])?$_POST[''.$fielddata[$z]['fieldname'].'_groupsizemw']:"";
															    $mimgheight=isset($_POST[''.$fielddata[$z]['fieldname'].'_groupsizemh'])?$_POST[''.$fielddata[$z]['fieldname'].'_groupsizemh']:"300";
															    $imgsize[1]="$mimgwidth,$mimgheight";
															    if($mimgwidth=="default"||$mimgheight=="default"){
															    	$imgsize[1]="default";
															    }
																$timgwidth=isset($_POST[''.$fielddata[$z]['fieldname'].'_groupsizetw'])?$_POST[''.$fielddata[$z]['fieldname'].'_groupsizetw']:"";
															    $timgheight=isset($_POST[''.$fielddata[$z]['fieldname'].'_groupsizeth'])?$_POST[''.$fielddata[$z]['fieldname'].'_groupsizeth']:"100";
															    $imgsize[2]="$timgwidth,$timgheight";
															    if($timgwidth=="default"||$timgheight=="default"){
															    	$imgsize[2]="default";
															    }
															    $acceptedsize="";
															    if(isset($_POST['img_default_medsizes'])){
															    	$imgsize[1]=$_POST['img_default_medsizes'];
															    }else if(isset($_POST['img_default_thumbsizes'])){
															    	$imgsize[2]=$_POST['img_default_thumbsizes'];
															    }
															    $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
															    $len=strlen($imgouts[0]);
															    $imagepath=substr($imgouts[0], 1,$len);
															    // medsize
															    $p2=isset($imgouts[1])?
															    $imgouts[1]:$imgouts[0];
															    $len2=strlen($p2);
															    $imagepath2=substr($p2, 1,$len2);
															    // thumbnail
															    $p3=isset($imgouts[2])?
															    $imgouts[2]:$imgouts[0];
															    $len3=strlen($p3);
															    $imagepath3=substr($p3, 1,$len3);
															    // get image size details
															    list($width,$height)=getimagesize($imgouts[0]);
															    $imagesize=$_FILES[''.$image.'']['size'];
															    $filesize=$imagesize/1024;
															    //echo $filefirstsize;
															    $filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
															    if(strlen($filesize)>3){
																    $filesize=$filesize/1024;
																    $filesize=round($filesize,2); 
																    $filesize="".$filesize."MB";
															    }else{
															    	$filesize="".$filesize."KB";
															    }
															    $coverpicid=getNextIdExplicit("media");
															    
															        $imgdata=getSingleMediaDataTwo($imgid);
															        $prevpic=$imgdata['location'];
															        $prevmedsize=$imgdata['medsize'];
															        $prevthumb=$imgdata['thumbnail'];
															        $realprevpic=".".$prevpic;
															        $realprevmedsize=".".$prevmedsize;
															        $realprevthumb=".".$prevthumb;
															        if(file_exists($realprevpic)&&$realprevpic!=="."){
															          unlink($realprevpic);
															        }
															        if(file_exists($realprevmedsize)&&$realprevmedsize!=="."){
															          unlink($realprevmedsize);
															        }
															        if(file_exists($realprevthumb)&&$realprevthumb!=="."){
															          unlink($realprevthumb);
															        }
															        genericSingleUpdate("media","location",$imagepath,"id",$imgid);
															        genericSingleUpdate("media","medsize",$imagepath2,"id",$imgid);
															        genericSingleUpdate("media","thumbnail",$imagepath3,"id",$imgid);
															        genericSingleUpdate("media","filesize",$filesize,"id",$imgid);
															        genericSingleUpdate("media","width",$width,"id",$imgid);
															        genericSingleUpdate("media","height",$height,"id",$imgid);
															        // echo "in here";
															        $cvalue=$imgid;
																
															    
																$fielddata[$z]['fieldvalue']="media|$cvalue";
															}else{
																$fielddata[$z]['fieldvalue']=$imgid>0?"media|$imgid":"media|0";
															}
														}else{
															$cvalue=0;
															if($imgid>0){
																deleteMedia($imgid);
															}
															$fielddata[$z]['fieldvalue']="media|0";
														}
													}else{
													    $contentfile=isset($_FILES[''.$fielddata[$z]['fieldname'].''.$xt.'']['tmp_name'])?$_FILES[''.$fielddata[$z]['fieldname'].''.$xt.'']['tmp_name']:"";
														$contentid=isset($_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_id'])?mysql_real_escape_string($_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_id']):0;
														// check if there is a delete option for the current content
														$contentdel=isset($_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_delete'])?mysql_real_escape_string($_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_delete']):"";
														if($contentdel==""){
															if ($contentfile!=="") {
																# code...
																$cextension=getExtension($contentfile);
																$ccontenttype=$fielddata[$z]['fieldentrytype'].$cextension;
																if($fielddata[$z]['fieldentrytype']=="video"){
																	$cfolder='/videos/';
																}else if ($fielddata[$z]['fieldentrytype']=="audio") {
																	# code...
																	$cfolder='/audio/';
																}else{
																	$cfolder="/";
																}
																$outsaudio=simpleUpload(''.$fielddata[$z]['fieldname'].''.$xt.'','../files'.$cfolder.'');
																$audiofilepath=$outsaudio['filelocation'];
																$len=strlen($audiofilepath);
																$audiofilepath=substr($audiofilepath, 1,$len);
																$audiofilesize=$outsaudio['filesize'];
															    $contentfileid=getNextIdExplicit("media");		
															    // echo "<br>".$contentid." - content id";												
																if($contentid>0){
																		$filedata=getSingleMediaDataTwo($contentid);
																		$realprevpic=".".$filedata['location'];
																		if(file_exists($realprevpic)&&$realprevpic!=="."){
																          unlink($realprevpic);
																        }
																		$orderfield="id";
																		$ordervalues=$contentid;
																		genericSingleUpdate("media","location",$audiofilepath,$orderfield,$ordervalues);
																		genericSingleUpdate("media","filesize",$audiofilesize,$orderfield,$ordervalues);
																		$cvalue="media|$contentid";
																}else{
																	$cvalue="media|$contentfileid";
																	$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)
																	VALUES
																	('$entryid','$maintype','$subtype','$ccontenttype','$audiofilepath','$audiofilesize')";
																	// echo "<br>$mediaquery2 <br>";
																	$mediarun2=mysql_query($mediaquery2)or die(mysql_error()." Line ".__LINE__);			
																}
																$fielddata[$z]['fieldvalue']="$cvalue";
															}else{
																$fielddata[$z]['fieldvalue']=$contentid>0?"media|$contentid":"media|0";

															}
														}else{
															$cvalue="media|0";
															if($contentid>0){
																deleteMedia($contentid);
															}
															$fielddata[$z]['fieldvalue']="media|0";
														}
													}
												}
											}
											if($noupload=="true"){
												if($fielddata[$z]['fieldtype']!=="textarea"){
													$fielddata[$z]['fieldvalue']=isset($_POST[''.$fielddata[$z]['fieldname'].''.$xt.''])?str_replace($fulldatasearch, $fulldatareplace, mysql_real_escape_string($_POST[''.$fielddata[$z]['fieldname'].''.$xt.''])):"NA";
												}else{
													$fielddata[$z]['fieldvalue']=isset($_POST[''.$fielddata[$z]['fieldname'].''.$xt.''])&&str_replace(" ", "", $_POST[''.$fielddata[$z]['fieldname'].''.$xt.''])!==""?str_replace($fulldatasearch, $fulldatareplace, mysql_real_escape_string($_POST[''.$fielddata[$z]['fieldname'].''.$xt.''])):"NA";
												}
											}
											// echo "<br>cur field value<br>".$fielddata[$z]['fieldvalue']." Fieldname-".$fielddata[$z]['fieldname']."<br>";

											// increment the sentinel control variable to allow 
											// monitoring of batch entries with no values
											if($fielddata[$z]['fieldvalue']==""||$fielddata[$z]['fieldvalue']=="media|0"){
												$scud++;
											}
											// concatenate the values obtained into a single variable for
											// array storage and later parsing
											if($batchvalues==""){
												$batchvalues=$fielddata[$z]['fieldname']?$fielddata[$z]['fieldvalue']."-|-".$fielddata[$z]['fieldname']:"";
													
											}else{
												$batchvalues.=$fielddata[$z]['fieldname']?">|<".$fielddata[$z]['fieldvalue']."-|-".$fielddata[$z]['fieldname']:"";
											}
										}
									}else{
										// make sure the number of allowed deletable
										// entries is greater than zero 
										if($deletecount>0){
											$deletecount-=1;
											// $scud=count($fielddata);
											// echo "<br>Delete Count - $deletecount   Cur SCUD - $scud<br>";
											$batchvalues="";
											// proceed to identify the filesetvalue entries for the current entry
											// set the scud variable to the counter variable to ensure complete
											// removal of the entry at the end of the sort
											if(isset($_POST['group'.$curgroupcount.'_filecount'])){
												$gfcount=$_POST['group'.$curgroupcount.'_filecount'];
												if($gfcount!==""&&$gfcount>0){
													for ($gfc=0; $gfc < $gfcount; $gfc++) { 
														# code...isset()
														$cugfdata=isset($_POST['group'.$curgroupcount.'_fileid_'.$gfc.''])?$_POST['group'.$curgroupcount.'_fileid_'.$gfc.'']:"";
														if($cugfdata!==""){
															for ($z=0; $z < count($fielddata); $z++) { 
																$curelemid=$cugfdata==$fielddata[$z]["fieldname"]&&isset($_POST[''.$fielddata[$z]["fieldname"].''.$xt.''])?$_POST[''.$fielddata[$z]["fieldname"].''.$xt.'_id']:0;
																if($curelemid>0){
																	// get rid of the content group file data
																	deleteMedia($curelemid);
																}
															}
														}
													}
												}
											}
										}
									}
									// remove the entry if no single valid value was found or a
									// deletion occured
									// echo "<br>curbatch values - $batchvalues<br>";
									if($scud==count($fielddata)){

										$batchvalues="";
										$scud=0;
									}else{
										$scud=0;
									}
									// store the batch storage string in the batcharr variable array
									// this array is later used to compile all entries into one delimited string
			  						// echo $batchvalues." - batchvalues<br><br>";
									if($batchvalues!==""){
										$batcharr[]=$batchvalues;
										
									}
								}
								// merge nbatcharr and batcharr to get the proper order 
								// of the current set of entries
								if($mainscud>0){
									// $batcharr=array_reverse($batcharr);
									$batcharr=array_merge($batcharr,$nbatcharr);
								}
							}else{

								// run through the entire value set of the group data
								// sentinel with a forloop
								// for previous entries
								for ($x=0; $x < $sentinel; $x++) { 
									// reset the curgroupdata string variable to nothing
									$curgroupdata="";
									# code...
									// add one to the increment variable to get the name appended numeric count
									// value of the relative form element name in the $_POST array
									$xt=$x+1;
									$batchvalues="";
									// create the batch storage string 
									// use a sentinel variable to perform basic data test for each entryset
									// ensuring at least one value is present
									$scud=0;
									$excludecurdata="";
									$curentrysetvcount=0;
									
									// remove content group that has been deleted
									if(isset($_POST['group'.$curgroupcount.'_status'.$xt.''])&&$_POST['group'.$curgroupcount.'_status'.$xt.'']!==""){
										$excludecurdata="true";
										// echo "<br>$excludecurdata <br>".$_POST['group'.$curgroupcount.'_status'.$xt.'']."<br>";
									}

									if($excludecurdata==""){
										for ($z=0; $z < count($fielddata); $z++) { 
											# code...
											// use the field name of the multiple content accompanied by
											// the corresponding xt - count value form above to get the current
											// multiple data content POSt element in the set
											$noupload="true";
											// echo "<br>cur field entry<br>".$fielddata[$z]['fieldentrytype'];
											if($fielddata[$z]['fieldentrytype']!==""){
												if ($fielddata[$z]['fieldentrytype']=="image"||
													$fielddata[$z]['fieldentrytype']=="video"||
													$fielddata[$z]['fieldentrytype']=="office"||
													$fielddata[$z]['fieldentrytype']=="audio"||
													$fielddata[$z]['fieldentrytype']=="pdf") {
													$noupload="false";
													//proceed to carryout the upload and obtain
													// transaction id which would be used to set up the data entry
													// in the form 'media|id' where id is the media table value for
													// the entry.
													if($fielddata[$z]['fieldentrytype']=="image"){
													    $contentpic=isset($_FILES[''.$fielddata[$z]['fieldname'].''.$xt.'']['tmp_name'])?$_FILES[''.$fielddata[$z]['fieldname'].''.$xt.'']['tmp_name']:"";
														$imgid=isset($_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_id'])?mysql_real_escape_string($_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_id']):0;
														$contentdel=isset($_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_delete'])?mysql_real_escape_string($_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_delete']):"";
														if($contentdel==""){

															if($contentpic!==""){
																$genericmainttype=$fielddata[$z]['fieldname'];
															    $image=''.$fielddata[$z]['fieldname'].''.$xt.'';
															    $imgpath[0]='../files/originals/';
															    $imgpath[1]='../files/medsizes/';
															    $imgpath[2]='../files/thumbnails/';
															    $imgsize[0]="default";
															    $mimgwidth=isset($_POST[''.$fielddata[$z]['fieldname'].'_groupsizemw'])?$_POST[''.$fielddata[$z]['fieldname'].'_groupsizemw']:"";
															    $mimgheight=isset($_POST[''.$fielddata[$z]['fieldname'].'_groupsizemh'])?$_POST[''.$fielddata[$z]['fieldname'].'_groupsizemh']:"300";
															    $imgsize[1]="$mimgwidth,$mimgheight";
															    if($mimgwidth=="default"||$mimgheight=="default"){
										    						$imgsize[1]="default";
										    					}
																$timgwidth=isset($_POST[''.$fielddata[$z]['fieldname'].'_groupsizetw'])?$_POST[''.$fielddata[$z]['fieldname'].'_groupsizetw']:"";
															    $timgheight=isset($_POST[''.$fielddata[$z]['fieldname'].'_groupsizeth'])?$_POST[''.$fielddata[$z]['fieldname'].'_groupsizeth']:"80";
															    $imgsize[2]="$timgwidth,$timgheight";
															    if($timgwidth=="default"||$timgheight=="default"){
															    	$imgsize[2]="default";
															    }

															    $acceptedsize="";
															    if(isset($_POST['img_default_medsizes'])){
															    	$imgsize[1]=$_POST['img_default_medsizes'];
															    }else if(isset($_POST['img_default_thumbsizes'])){
															    	$imgsize[2]=$_POST['img_default_thumbsizes'];
															    }
															    $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
															    $len=strlen($imgouts[0]);
															    $imagepath=substr($imgouts[0], 1,$len);
															    // medsize
															    $p2=isset($imgouts[1])?
															    $imgouts[1]:$imgouts[0];
															    $len2=strlen($p2);
															    $imagepath2=substr($p2, 1,$len2);
															    // thumbnail
															    $p3=isset($imgouts[2])?
															    $imgouts[2]:$imgouts[0];
															    $len3=strlen($p3);
															    $imagepath3=substr($p3, 1,$len3);
															    // get image size details
															    list($width,$height)=getimagesize($imgouts[0]);
															    $imagesize=$_FILES[''.$image.'']['size'];
															    $filesize=$imagesize/1024;
															    //echo $filefirstsize;
															    $filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
															    if(strlen($filesize)>3){
																    $filesize=$filesize/1024;
																    $filesize=round($filesize,2); 
																    $filesize="".$filesize."MB";
															    }else{
															    	$filesize="".$filesize."KB";
															    }
															    $coverpicid=getNextIdExplicit("media");
															    if($imgid<1){
															    	$cvalue="$coverpicid";
																    //maintype variants are original, medsize, thumb for respective size image.
																    $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,medsize,thumbnail,filesize,width,height)
																    VALUES
																    ('$entryid','$maintype','$genericmainttype','image','$imagepath','$imagepath2','$imagepath3','$filesize','$width','$height')";
																	// echo "<br> $mediaquery <br>";
																    $mediarun=mysql_query($mediaquery)or die(mysql_error());
															    }else{
																        $imgdata=getSingleMediaDataTwo($imgid);
																        $prevpic=$imgdata['location'];
																        $prevmedsize=$imgdata['medsize'];
																        $prevthumb=$imgdata['thumbnail'];
																        $realprevpic=".".$prevpic;
																        $realprevmedsize=".".$prevmedsize;
																        $realprevthumb=".".$prevthumb;
																        if(file_exists($realprevpic)&&$realprevpic!=="."){
																          unlink($realprevpic);
																        }
																        if(file_exists($realprevmedsize)&&$realprevmedsize!=="."){
																          unlink($realprevmedsize);
																        }
																        if(file_exists($realprevthumb)&&$realprevthumb!=="."){
																          unlink($realprevthumb);
																        }
																        genericSingleUpdate("media","location",$imagepath,"id",$imgid);
																        genericSingleUpdate("media","medsize",$imagepath2,"id",$imgid);
																        genericSingleUpdate("media","thumbnail",$imagepath3,"id",$imgid);
																        genericSingleUpdate("media","filesize",$filesize,"id",$imgid);
																        genericSingleUpdate("media","width",$width,"id",$imgid);
																        genericSingleUpdate("media","height",$height,"id",$imgid);
																        // echo "in here";
																        $cvalue=$imgid;
															    }
																$fielddata[$z]['fieldvalue']="media|$cvalue";
															}else{
																$fielddata[$z]['fieldvalue']=$imgid>0?"media|$imgid":"media|0";
															}
														}else{
															$cvalue=0;
															if($imgid>0){
																deleteMedia($imgid);
															}
															$fielddata[$z]['fieldvalue']="media|0";
														}
													}else{
													    $contentfile=isset($_FILES[''.$fielddata[$z]['fieldname'].''.$xt.'']['tmp_name'])?$_FILES[''.$fielddata[$z]['fieldname'].''.$xt.'']['tmp_name']:"";
														$contentid=isset($_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_id'])?mysql_real_escape_string($_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_id']):0;
														// check if there is a delete option for the current content
														$contentdel=isset($_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_delete'])?mysql_real_escape_string($_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_delete']):"";
														if($contentdel==""){
															if ($contentfile!=="") {
																# code...
																$cextension=getExtension($contentfile);
																$ccontenttype=$fielddata[$z]['fieldentrytype'].$cextension;
																if($fielddata[$z]['fieldentrytype']=="video"){
																	$cfolder='/videos/';
																}else if ($fielddata[$z]['fieldentrytype']=="audio") {
																	# code...
																	$cfolder='/audio/';
																}else{
																	$cfolder="/";
																}
																$outsaudio=simpleUpload(''.$fielddata[$z]['fieldname'].''.$xt.'','../files'.$cfolder.'');
																$audiofilepath=$outsaudio['filelocation'];
																$len=strlen($audiofilepath);
																$audiofilepath=substr($audiofilepath, 1,$len);
																$audiofilesize=$outsaudio['filesize'];
															    $contentfileid=getNextIdExplicit("media");		
															    // echo "<br>".$contentid." - content id";												
																if($contentid>0){
																		$filedata=getSingleMediaDataTwo($contentid);
																		$realprevpic=".".$filedata['location'];
																		if(file_exists($realprevpic)&&$realprevpic!=="."){
																          unlink($realprevpic);
																        }
																		$orderfield="id";
																		$ordervalues=$contentid;
																		genericSingleUpdate("media","location",$audiofilepath,$orderfield,$ordervalues);
																		genericSingleUpdate("media","filesize",$audiofilesize,$orderfield,$ordervalues);
																		$cvalue="media|$contentid";
																	
																}else{
																	$cvalue="media|$contentfileid";
																	$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)
																	VALUES
																	('$entryid','$maintype','$subtype','$ccontenttype','$audiofilepath','$audiofilesize')";
																	// echo "<br>$mediaquery2 <br>";
																	$mediarun2=mysql_query($mediaquery2)or die(mysql_error()." Line ".__LINE__);			
																}
																$fielddata[$z]['fieldvalue']="$cvalue";
															}else{
																$fielddata[$z]['fieldvalue']=$contentid>0?"media|$contentid":"media|0";
															}
														}else{
															$cvalue="media|0";
															if($contentid>0){
																deleteMedia($contentid);
															}
															$fielddata[$z]['fieldvalue']="media|0";

														}
													}
												}
											}
											if($noupload=="true"){
												if($fielddata[$z]['fieldtype']!=="textarea"){
													$fielddata[$z]['fieldvalue']=isset($_POST[''.$fielddata[$z]['fieldname'].''.$xt.''])?str_replace($fulldatasearch, $fulldatareplace, mysql_real_escape_string($_POST[''.$fielddata[$z]['fieldname'].''.$xt.''])):"NA";
												}else{
													$fielddata[$z]['fieldvalue']=isset($_POST[''.$fielddata[$z]['fieldname'].''.$xt.''])?str_replace($fulldatasearch, $fulldatareplace, mysql_real_escape_string($_POST[''.$fielddata[$z]['fieldname'].''.$xt.''])):"NA";
												}	
											}

											// increment the sentinel control variable to allow 
											// monitoring of batch entries with no values
											if($fielddata[$z]['fieldvalue']==""||$fielddata[$z]['fieldvalue']=="media|0"){
												$scud++;
											}
											// concatenate the values obtained into a single variable for
											// array storage and later parsing
											if($batchvalues==""){
												$batchvalues=$fielddata[$z]['fieldname']?$fielddata[$z]['fieldvalue']."-|-".$fielddata[$z]['fieldname']:"";
													
											}else{
												$batchvalues.=$fielddata[$z]['fieldname']?">|<".$fielddata[$z]['fieldvalue']."-|-".$fielddata[$z]['fieldname']:"";
											}
										}
									}else{
										// proceed to identify the filesetvalue entries for the
										// set the scud variable to the counter variable to ensure complete
										// removal of the entry at the end of the sort
										$scud=count($fielddata);
										if(isset($_POST['group'.$curgroupcount.'_filecount'])){
											$gfcount=$_POST['group'.$curgroupcount.'_filecount'];
											if($gfcount!==""&&$gfcount>0){
												for ($gfc=0; $gfc < $gfcount; $gfc++) { 
													# code...isset()
													$cugfdata=isset($_POST['group'.$curgroupcount.'_fileid_'.$gfc.''])?$_POST['group'.$curgroupcount.'_fileid_'.$gfc.'']:"";
													if($cugfdata!==""){
														for ($z=0; $z < count($fielddata); $z++) { 
															$curelemid=$cugfdata==$fielddata[$z]['fieldname']&&isset($_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_id'])?$_POST[''.$fielddata[$z]['fieldname'].''.$xt.'_id']:0;
															if($curelemid>0){
																// get rid of the content group file data
																deleteMedia($curelemid);
															}/*else{
																break;
															}*/
														}
													}
												}
											}
										}
									}
									// remove the entry if no single valid value was found or a
									// deletion occured
									if($scud==count($fielddata)){
										$batchvalues="";
										$scud=0;
									}
									// store the batch storage string in the batcharr variable array
									// this array is later used to compile all entries into one delimited string
			  						// echo $batchvalues." - batchvalues<br><br>";
									if($batchvalues!==""){
										$batcharr[]=$batchvalues;
									}
								}
							}
							// segregate the multiple data content for the current group
							// with a |_| delimiter 
							for ($y=0; $y < count($batcharr) ; $y++) { 
								# code...
								if($curgroupdata==""){
									$curgroupdata=isset($batcharr[$y])?$batcharr[$y]:"";
								}else{
									$curgroupdata.=isset($batcharr[$y])?"|_|".$batcharr[$y]:"";
								}						
							}
						}

						$finaltally.=$finaltally==""?"egroup|data-:-[$curgroupdata]":$concat."egroup|data-:-[$curgroupdata]";
						// start sorting through the remaining content for the groupdata
					}else{
						// explode the current content and proceed to join their values to the 
						// K:V string output
						$subgroup=explode("-:-",$curdata);
						// get rid of unnecessary spacing and newlines.
						// they tend to mess the post array value up
						$fieldname=$subgroup[0];
						$fieldtype=$subgroup[1];
						$sortfieldtype=strpos($fieldtype,"|");
						$fieldentrytype="";
						$fieldextension="";
						if($sortfieldtype!==false||$sortfieldtype===true){
							$sftexplode=explode("|", $fieldtype);
							$fieldtype=$sftexplode[0];
							$fieldentrytype=$sftexplode[1];
							if(count($sftexplode)>2){
								$fieldextension=$sftexplode[2];
							}
						}
						$noupload="true";
						if($fieldentrytype!==""){
							if ($fieldentrytype=="image"||
								$fieldentrytype=="video"||
								$fieldentrytype=="office"||
								$fieldentrytype=="audio"||
								$fieldentrytype=="pdf") {
								$noupload="false";
								// proceed to carryout the upload and obtain
								// transaction id which would be used to set up the data entry
								// in the form 'media|id' where id is the media table value for
								// the entry.
								if($fieldentrytype=="image"){
								    $contentpic=isset($_FILES[''.$fieldname.'']['tmp_name'])?$_FILES[''.$fieldname.'']['tmp_name']:"";
									$imgid=isset($_POST[''.$fieldname.'_id'])?mysql_real_escape_string($_POST[''.$fieldname.'_id']):0;
									$contentdel=isset($_POST[''.$fieldname.'_delete'])?mysql_real_escape_string($_POST[''.$fieldname.'_delete']):"";

									if($contentdel==""){
										if($contentpic!==""){
											$genericmainttype=$fieldname;
										    $image=''.$fieldname.'';
										    $imgpath[0]='../files/originals/';
										    $imgpath[1]='../files/medsizes/';
										    $imgpath[2]='../files/thumbnails/';
										    $imgsize[0]="default";
										    $mimgwidth=isset($_POST[''.$fieldname.'_sizemw'])?$_POST[''.$fieldname.'_sizemw']:"";
											$mimgheight=isset($_POST[''.$fieldname.'_sizemh'])?$_POST[''.$fieldname.'_sizemh']:"300";
										    $imgsize[1]="$mimgwidth,$mimgheight";
										    if($mimgwidth=="default"||$mimgheight=="default"){
										    	$imgsize[1]="default";
										    }
											$timgwidth=isset($_POST[''.$fieldname.'_sizetw'])?$_POST[''.$fieldname.'_sizetw']:"";
										    $timgheight=isset($_POST[''.$fieldname.'_sizeth'])?$_POST[''.$fieldname.'_sizeth']:"100";
										    $imgsize[2]="$timgwidth,$timgheight";
										    if($timgwidth=="default"||$timgheight=="default"){
										    	$imgsize[2]="default";
										    }

											$acceptedsize="";
										    if(isset($_POST['img_default_medsizes'])){
										    	$imgsize[1]=$_POST['img_default_medsizes'];
										    }else if(isset($_POST['img_default_thumbsizes'])){
										    	$imgsize[2]=$_POST['img_default_thumbsizes'];
										    }
										    $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
										    $len=strlen($imgouts[0]);
										    $imagepath=substr($imgouts[0], 1,$len);
										    
										    // medsize
										    $p2=isset($imgouts[1])?
										    $imgouts[1]:$imgouts[0];
										    $len2=strlen($p2);
										    $imagepath2=substr($p2, 1,$len2);
										    
										    // thumbnail
										    $p3=isset($imgouts[2])?
										    $imgouts[2]:$imgouts[0];
										    $len3=strlen($p3);
										    $imagepath3=substr($p3, 1,$len3);

										    // get image size details
										    list($width,$height)=getimagesize($imgouts[0]);
										    $imagesize=$_FILES[''.$image.'']['size'];
										    $filesize=$imagesize/1024;
										    //echo $filefirstsize;
										    $filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
										    if(strlen($filesize)>3){
											    $filesize=$filesize/1024;
											    $filesize=round($filesize,2); 
											    $filesize="".$filesize."MB";
										    }else{
										    	$filesize="".$filesize."KB";
										    }
										    $coverpicid=getNextIdExplicit("media");
										    if($imgid<1){
										    	$cvalue="$coverpicid";
											    //maintype variants are original, medsize, thumb for respective size image.
											    $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,medsize,thumbnail,filesize,width,height)
											    VALUES
											    ('$entryid','$maintype','$genericmainttype','image','$imagepath','$imagepath2','$imagepath3','$filesize','$width','$height')";
												// echo "<br> $mediaquery <br>";
											    $mediarun=mysql_query($mediaquery)or die(mysql_error());
										    }else{
											        $imgdata=getSingleMediaDataTwo($imgid);
											        $prevpic=$imgdata['location'];
											        $prevmedsize=$imgdata['medsize'];
											        $prevthumb=$imgdata['thumbnail'];
											        $realprevpic=".".$prevpic;
											        $realprevmedsize=".".$prevmedsize;
											        $realprevthumb=".".$prevthumb;
											        if(file_exists($realprevpic)&&$realprevpic!=="."){
											          unlink($realprevpic);
											        }
											        if(file_exists($realprevmedsize)&&$realprevmedsize!=="."){
											          unlink($realprevmedsize);
											        }
											        if(file_exists($realprevthumb)&&$realprevthumb!=="."){
											          unlink($realprevthumb);
											        }
											        genericSingleUpdate("media","location",$imagepath,"id",$imgid);
											        genericSingleUpdate("media","medsize",$imagepath2,"id",$imgid);
											        genericSingleUpdate("media","thumbnail",$imagepath3,"id",$imgid);
											        genericSingleUpdate("media","filesize",$filesize,"id",$imgid);
											        genericSingleUpdate("media","width",$width,"id",$imgid);
											        genericSingleUpdate("media","height",$height,"id",$imgid);
											        // echo "in here";
											        $cvalue=$imgid;
												
										    }
											$fieldvalue="media|$cvalue";
										}else{
											$fieldvalue=$imgid>0?"media|$imgid":"media|0";
										}
									}else{
										$cvalue=0;
										if($imgid>0){
											deleteMedia($imgid);
										}
										$cvalue="media|0";
										$fieldvalue=$cvalue;
									}
								}else{
								    $contentfile=isset($_FILES[''.$fieldname.'']['tmp_name'])?$_FILES[''.$fieldname.'']['tmp_name']:"";
									$contentid=isset($_POST[''.$fieldname.'_id'])?mysql_real_escape_string($_POST[''.$fieldname.'_id']):0;
									// check if there is a delete option for the current content
									$contentdel=isset($_POST[''.$fieldname.'_delete'])?mysql_real_escape_string($_POST[''.$fieldname.'_delete']):"";
									if($contentdel==""){
										if ($contentfile!=="") {
											# code...
											$cextension=getExtension($contentfile);
											echo "<br>cfile - $contentfile<br>";
											if($fieldentrytype==$cextension){
												$ccontenttype=$fieldentrytype;
											}else{
												$ccontenttype=$fieldentrytype.$cextension;
											}
											if($fieldentrytype=="video"){
												$cfolder='/videos/';
											}else if ($fieldentrytype=="audio") {
												# code...
												$cfolder='/audio/';
											}else{
												$cfolder="/";
											}
											$outsaudio=simpleUpload("$fieldname",'../files'.$cfolder.'');
											$audiofilepath=$outsaudio['filelocation'];
											$len=strlen($audiofilepath);
											$audiofilepath=substr($audiofilepath, 1,$len);
											$audiofilesize=$outsaudio['filesize'];
											$contentfileid=getNextIdExplicit("media");														

											if($contentid>0){
												echo "<br>".$contentdel." - contentdel";												
													$filedata=getSingleMediaDataTwo($contentid);
													$realprevpic=".".$filedata['location'];
													if(file_exists($realprevpic)&&$realprevpic!=="."){
											          unlink($realprevpic);
											        }
													$orderfield="id";
													$ordervalues=$contentid;
													genericSingleUpdate("media","location",$audiofilepath,$orderfield,$ordervalues);
													genericSingleUpdate("media","filesize",$audiofilesize,$orderfield,$ordervalues);
													$cvalue="media|$contentid";
												
											}else{
												$cvalue="media|$contentfileid";
												$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)
												VALUES
												('$entryid','$maintype','$subtype','$ccontenttype','$audiofilepath','$audiofilesize')";
												echo "<br>$mediaquery2 <br>";
												$mediarun2=mysql_query($mediaquery2)or die(mysql_error()." Line ".__LINE__);			
											}

											$fieldvalue="$cvalue";
										}else{
											$fieldvalue=$contentid>0?"media|$contentid":"media|0";
										}
									}else{
										if($contentid>0){
											deleteMedia($contentid);
										}
										$cvalue="media|0";
										$fieldvalue=$cvalue;
									}
								}
							}
						}
						if($noupload=="true"){
							// echo $_POST["icontitle"]."- $fieldname <br>";
							if($fieldtype!=="textarea"){
								$fieldvalue=isset($_POST[''.$fieldname.''])&&$_POST[''.$fieldname.'']!==""?str_replace($fulldatasearch, $fulldatareplace, $_POST[''.$fieldname.'']):"NA";
							}else{
								$fieldvalue=isset($_POST[''.$fieldname.''])&&$_POST[''.$fieldname.'']!==""?str_replace($fulldatasearch, $fulldatareplace, $_POST[''.$fieldname.'']):"NA";
							}
							
						}else{

						}
						$finaltally.=$finaltally==""?"$fieldvalue-:-$fieldname":$concat."$fieldvalue-:-$fieldname";
					}

				}
			}
			
			// $fd="testmultiplevaliddata";
			// echo $_POST[''.$fd.'']."<br>";
			// test to see the final output
			// echo str_replace("<", "&lt;",$finaltally)." - final tally<br>";
		}
		$row['extraformdata']=$extraformdata;
		$row['errormap']=$errormap;
		$row['finaltally']=$finaltally;
		return $row;
	}
?>