<?php
	// THE PURPOSE HERE IS TO PARSE DATA CONTENT STORED IN THE k-:-v FORMAT
	// IN THE GENERALINFO TABLE, FOR THE GENERALDATAMODULE
	// AN array is returned with all content and their corresponding values
	// including grouped content
	// the final returned result sets are stored in the multidimensional array 
	// $row['edresultset']
	// 'edresultset' standing for extradata resultset.

	// these variables must be made available before this module can be used correctly
	// $row - an assocaitive array of data base result row from the 'generalinfo' table
	// $extradata - a delimited list of data K:V pairs representing fields and values
	// $formdata - the name for the final form generated to create the set of contents being parsed
	// $extraformdata - the name and description, including validation description of contents in the form
	// $errormap - the error messages to be produced for the form
	/*
		(test map monitor)
		Form data: contentform
		Extra data:
		green-:-collagecolourtype<|>
		kjkjfksjdf-:-contenttitle<|>
		<p>jksjdfkj</p>-:-contentpost
		<|>kdjfkjdkf-:-linktitle<|>
		fa-camera-:-icontitle<|>
		egroup|data-:-[
			jsjdfkjkgf-|-linktitlea>|<jfkfjg-|-linktitleb>|<Opt 2-|-linktitlec|_|
			jrjkedrjk-|-linktitlea>|<kjedkfjdks-|-linktitleb>|<Opt 3-|-linktitlec|_|
			dfkjgfkj-|-linktitlea>|<lklret-|-linktitleb>|<Opt 2-|-linktitlec]<|>
		djdskfjk-:-linkaddress

		Extra form data:
		$extraformdata="";
		collagecolourtype-:-select<|>
		contenttitle-:-input<|>
		contentpost-:-textarea<|>
		linktitle-:-input<|>
		icontitle-:-input<|>
		egroup|data-:-[
		testmultiplevaliddata>|<
		linktitlea-|-input>|<
		linktitleb-|-input>|<
		linktitlec-|-select]-:-groupfall[1-2,2-3,3-1]<|>
		linkaddress-:-input
		
		errormap
		$errormap="collagecolourtype-:-Please Select a Color<|>contenttitle-:-Please provide a title<|>contentpost-:-Please give the Collage box content details. In a brief format.<|>                                    linktitle-:-Provide the title for the link in this collage box, preferably a one or two words<|>                                    icontitle-:-NA<|>                                    egroup|data-:-[Provide some text for the field a>|<                                      Provide some text for the field b>|<                                      Provide some text for the field c]<|>                                    linkaddress-:-Give the url/web address for the link here."collagecolourtype-:-Please Select a Color<|>contenttitle-:-Please provide a title<|>contentpost-:-Please give the Collage box content details. In a brief format.<|>linktitle-:-Provide the title for the link in this collage box, preferably a one or two words<|>icontitle-:-NA<|>egroup|data-:-[Provide some text for the field a>|<Provide some text for the field b>|<Provide some text for the field c]<|>linkaddress-:-Give the url/web address for the link here.
	*/
		/*echo "in here<br> 
			  Extra data:	$extradata<br>
			  form data:	$formdata<br>
			  extra form data:	$extraformdata<br>
			  error data:	$errormap<br><br><br>
		";*/
	// perform variable checks to make sure we are a go for parsing
	if((isset($row)&&
		isset($extradata)&&
		$extradata!==""&&
		isset($formdata)&&
		isset($extraformdata)&&
		isset($errormap))||(isset($_gdunit)&&$_gdunit=="true"&&isset($_gdoutput))){	
		if(isset($_gdunit)&&$_gdunit=="true"){
			// this means the module parser is being included in another document
			// not in any of the generaldatamodule functions
			// this method
			$row=array();
			$_gdparseindex=isset($_gdparseindex)?$_gdparseindex:0;
			$extradata=$_gdoutput['resultdataset'][$_gdparseindex]['extradata'];
			$extraformdata=$_gdoutput['resultdataset'][$_gdparseindex]['extraformdata'];
			$errormap=$_gdoutput['resultdataset'][$_gdparseindex]['formerrordata'];
		}
		//commence data parsing
		// explode extra data to its single unit values
		$fdata=explode("<|>", $extradata);
		$efdata=explode("<|>", $extraformdata);
		$errdata=explode("<|>", $errormap);
		// holds the script data for populating entry values
		// for selections
		$totalscripts="";
		$edittotalscripts="";
		// initialise variable for counting number of grouped data found during 
		// data parsing
		$groupcounter=0;
		// initialise variable for counting numnber of single entries found
		$fieldnamescount=0;

		if(count($fdata)>0){	
			// launch for-loop for iterating over values in fdata array
			for ($i=0; $i < count($fdata); $i++) { 
				$curdata=$fdata[$i];
				$curefdata=$efdata[$i];
				$curerrdata=$errdata[$i];
				$sortdata=strpos($curdata, "egroup|data");
				$curgrouprescount=0;
				if($sortdata!==false||$sortdata===true){
					$groupcounter++;
					$subgroup=explode("egroup|data-:-", $curdata);
					preg_match_all("~([^\[\]]{1,})~im", $subgroup[1], $stet);
					// var_dump($stet);
					$subgroup=isset($stet[0][0])?$stet[0][0]:"";
							
					// create arrays with values that are easily accessible
					// in the rest of the function
					// sub extraformdatagroupt this is the first exploded group data
					// set
					$subefgroupt=explode("egroup|data-:-", $curefdata);
					$suberrgroupt=explode("egroup|data-:-", $curerrdata);
					preg_match_all("~([^\[\]]{1,})~im", $subefgroupt[1], $subefgroupt);
					preg_match_all("~([^\[\]]{1,})~im", $suberrgroupt[1], $suberrgroupt);
					// echo $subefgroupt[0]." -group one<br> ".$subefgroupt[1]." -two group<br>";
					// var_dump($subefgroupt);

					// this is the main exploded groupdataset
					$subefgroup=explode(">|<", $subefgroupt[0][0]);
					$suberrgroup=explode(">|<", $suberrgroupt[0][0]);
					// remove the sentinel element for the group from the array
					$sentinel=array_shift($subefgroup);
					// check to see if an _entryminimum value is present
					$gdtest=strpos($sentinel, ":*:");
					$eminimum=0;
					if($gdtest===0||$gdtest===true||$gdtest>0){
						$gdexp=explode(":*:", $sentinel);
						$sentinel=$gdexp[0];
						$eminimum=$gdexp[1];
					}
					$sentisplit=explode("count", $sentinel);
					$tsent=$sentisplit[0];
					$row['edresultset']['group'.$groupcounter.'']['sentinel']=$sentinel;
					$row['edresultset']['group'.$groupcounter.''][$tsent.'_entryminimum']=$eminimum;
					$efgrouparr=array();
					$errgrouparr=array();
					for ($k=0; $k < count($subefgroup); $k++) { 
						# code...
						$splitefdata=explode("-|-",$subefgroup[$k]);
						$spliterrdata=explode("-|-",$suberrgroup[$k]);
						$efname=$splitefdata[0];
						$eftype=$splitefdata[1];
						$fieldentrytype="";
						$fieldextension="";
						// check to see if extra details are attached to the current
						// type information
						$sortfieldtype=strpos("|",$eftype);
						if($sortfieldtype!==false&&$sortfieldtype===true){
							$sftexplode=explode("|", $eftype);
							$eftype=$sftexplode[0];
							$fieldentrytype=$sftexplode[1];
							if(count($sftexplode)>2){
								$fieldextension=$sftexplode[2];
							}
						}
						$efgrouparr[$k]['fieldname']=$efname;
						$efgrouparr[$k]['fieldtype']=$eftype;
						$efgrouparr[$k]['fieldentrytype']=$fieldentrytype;
						$efgrouparr[$k]['fieldextension']=$fieldextension;
					}
					// next up, break the group result into its individual sets
					$subresults=explode("|_|", $subgroup);
					$curgrouprescount=count($subresults);
					$row['edresultset']['group'.$groupcounter.'']['groupcount']=$curgrouprescount;
					
					// break the results into their individual parts and store them
					// in their corresponding array format
					if(count($subresults)>0&&$subgroup!==""){
						// get file related content of the current group into a comma 
						// delimited value set
						$filesetvalues="";
						for ($t=0; $t < count($subresults); $t++) { 
							# code...
							$cursubresults=explode(">|<", $subresults[$t]);
							$setcount=count($cursubresults);
							// get the total number of fields obtained in the current result
							$row['edresultset']['group'.$groupcounter.'']['fieldcount']=count($cursubresults);
							// get down with each value of the current result set
							for ($j=0; $j < count($cursubresults); $j++) { 
								# code...	
								// get the current value into a plain variable
								// echo $cursubresults[$j]." - cur subresult<br>";
								// var_dump($cursubresults[$j]);
								$curvalue=$cursubresults[$j];
								$curvalue=explode("-|-", $curvalue);
								// var_dump($curvalue);
								$cvalue=$curvalue[0]=="NA"?"":$curvalue[0];
								$ctype=$efgrouparr[$j]['fieldtype'];
								$cdata=$curvalue[1];

								// check to obtain the exact value if the cvalue
								// data is a reference id to the media table
								$sortcurvalue=strpos($cvalue, "media|");
								if($sortcurvalue!==false||$sortcurvalue===true){
									// explode the content to get the media id
									$sdata=explode("media|",$cvalue);
									$curid=$sdata[1];
									$mediadata=getSingleMediaDataTwo($curid);
									// attach current entry to the delimited value set
									// file value set variables
									if($t==0){
										$filesetvalues=$filesetvalues==""?"$cdata":",$cdata";
									}
									// valid output types
									// image:
									/*
										location
										medsizes
										thumbnail
										mediatype
										title
										preview
										details
										width
										height
										filesize
									*/
									// others:
									/*
										location
										title
										preview
										details
										filesize
									*/							
									$row['edresultset']['group'.$groupcounter.''][0][$j]['fieldname']=$cdata;
									$row['edresultset']['group'.$groupcounter.''][0][$j]['fieldtype']="file";
									$row['edresultset']['group'.$groupcounter.''][$t][''.$cdata.'']['value']=isset($mediadata['location'])?$host_addr.$mediadata['location']:"";
									$row['edresultset']['group'.$groupcounter.''][$t][''.$cdata.'']['id']=isset($mediadata['id'])?$mediadata['id']:0;
									$row['edresultset']['group'.$groupcounter.''][$t][''.$cdata.'']['location']=isset($mediadata['location'])?$mediadata['location']:"";
									$row['edresultset']['group'.$groupcounter.''][$t][''.$cdata.'']['mediatype']=isset($mediadata['mediatype'])?$mediadata['mediatype']:"";
									$row['edresultset']['group'.$groupcounter.''][$t][''.$cdata.'']['medsizes']=isset($mediadata['medsize'])?$mediadata['medsize']:"";
									$row['edresultset']['group'.$groupcounter.''][$t][''.$cdata.'']['thumbnail']=isset($mediadata['thumbnail'])?$mediadata['thumbnail']:"";
									$row['edresultset']['group'.$groupcounter.''][$t][''.$cdata.'']['title']=isset($mediadata['title'])?$mediadata['title']:"";
									$row['edresultset']['group'.$groupcounter.''][$t][''.$cdata.'']['preview']=isset($mediadata['preview'])?$mediadata['preview']:"";
									$row['edresultset']['group'.$groupcounter.''][$t][''.$cdata.'']['details']=isset($mediadata['details'])?$mediadata['details']:"";
									$row['edresultset']['group'.$groupcounter.''][$t][''.$cdata.'']['width']=isset($mediadata['width'])?$mediadata['width']:"";
									$row['edresultset']['group'.$groupcounter.''][$t][''.$cdata.'']['height']=isset($mediadata['height'])?$mediadata['height']:"";
									$row['edresultset']['group'.$groupcounter.''][$t][''.$cdata.'']['filesize']=isset($mediadata['filesize'])?$mediadata['filesize']:"";
									// echo "<br>".$curid." the cur filed".$mediadata['location']."<br> $cdata";	
								}else{
									if($ctype!=="textarea"){
										$cvalue2=str_replace('"', '&#34;', $cvalue);
									}else{
										$cvalue2=$cvalue;
									}
									$row['edresultset']['group'.$groupcounter.''][0][$j]['fieldname']=$cdata;
									$row['edresultset']['group'.$groupcounter.''][0][$j]['fieldtype']="field";
									$row['edresultset']['group'.$groupcounter.''][$t][''.$cdata.'']['value']="$cvalue2";
									$row['edresultset']['group'.$groupcounter.''][$t][''.$cdata.'']['mainvalue']="$cvalue";
									// echo "<br>$cvalue - curvalue curname $cdata<br>";
									// create and attach the script that allows the current value for the selection
									// to be chosen
									$tx=$t+1;
									if($ctype=="select"){
										$totalscripts.="$('form[name^=edit] select[name=".$cdata."".$tx."], form[name$=_gdunitform] select[name=".$cdata."".$tx."]').val(\"".$cvalue."\");";
										$edittotalscripts.="$('form[name^=edit] select[name=".$cdata."".$tx."]').val(\"".$cvalue."\");";
									}
								}
							}
						}
						// insert the filesetvalues
						$row['edresultset']['group'.$groupcounter.'']['filesetvalues']=$filesetvalues;
					}
				}else{
					// single k:v pairs present, carry out simple parse and
					// return their values to the array set
					// for single entries the order is actually
					// value-:-key v:k
					$subgroup=explode("-:-",$curdata);
					// echo $curdata." - curdata single output<br>";

					$fci=$fieldnamescount;
					$fieldnamescount++;
					$subefgroupt=explode("-:-", $curefdata);
					$cvalue=$subgroup[0]; // the value for the current entry
					$cdata=$subgroup[1]; //form element name
					// echo $cdata." - cdata output<br>";
					// get rid of unnecessary spacing and newlines.
					// they tend to mess the post array value up
					$fieldname=$subefgroupt[0];
					$eftype=$subefgroupt[1];
					$fieldentrytype="";
					$fieldextension="";
					// check to see if extra details are attached to the current
					// type information
					$sortfieldtype=strpos("|",$eftype);
					if($sortfieldtype!==false&&$sortfieldtype===true){
						$sftexplode=explode("|", $eftype);
						$eftype=$sftexplode[0];
						$fieldentrytype=$sftexplode[1];
						if(count($sftexplode)>2){
							$fieldextension=$sftexplode[2];
						}
					}
					$sortcurvalue=strpos($cvalue, "media|");
					if($sortcurvalue!==false||$sortcurvalue===true){
						// explode the content to get the media id
						$sdata=explode("media|",$cvalue);
						$curid=$sdata[1];
						$mediadata=getSingleMediaDataTwo($curid);
						// echo $curid." the cur id ".$mediadata['location']."<br>";

						$row['edresultset'][$fci]['fieldname']=$cdata;
						$row['edresultset'][$fci]['fieldtype']="file";
						$row['edresultset'][''.$cdata.'']['value']=isset($mediadata['location'])?$host_addr.$mediadata['location']:"";
						$row['edresultset'][''.$cdata.'']['id']=isset($mediadata['id'])?$mediadata['id']:0;
						$row['edresultset'][''.$cdata.'']['mediatype']=isset($mediadata['mediatype'])?$mediadata['mediatype']:"";
						$row['edresultset'][''.$cdata.'']['location']=isset($mediadata['location'])?$mediadata['location']:"";
						$row['edresultset'][''.$cdata.'']['medsizes']=isset($mediadata['medsize'])?$mediadata['medsize']:"";
						$row['edresultset'][''.$cdata.'']['thumbnail']=isset($mediadata['thumbnail'])?$mediadata['thumbnail']:"";
						$row['edresultset'][''.$cdata.'']['title']=isset($mediadata['title'])?$mediadata['title']:"";
						$row['edresultset'][''.$cdata.'']['preview']=isset($mediadata['preview'])?$mediadata['preview']:"";
						$row['edresultset'][''.$cdata.'']['details']=isset($mediadata['details'])?$mediadata['details']:"";
						$row['edresultset'][''.$cdata.'']['width']=isset($mediadata['width'])?$mediadata['width']:"";
						$row['edresultset'][''.$cdata.'']['height']=isset($mediadata['height'])?$mediadata['height']:"";
						$row['edresultset'][''.$cdata.'']['filesize']=isset($mediadata['filesize'])?$mediadata['filesize']:"";
					}else{
						if($eftype!=="textarea"){
							$cvalue2=str_replace('"', '&#34;', $cvalue);
						}else{
							$cvalue2=$cvalue;
						}
						$row['edresultset'][$fci]['fieldname']=$cdata;
						$row['edresultset'][$fci]['fieldtype']="field";
						$row['edresultset'][''.$cdata.'']['value']=$cvalue2;
						$row['edresultset'][''.$cdata.'']['mainvalue']=$cvalue;
						// create and attach the script that allows the current value for the selection
						// to be chosen
						if($eftype=="select"){
							$totalscripts.="$('form[name^=edit]  select[name=".$cdata."],form[name$=_gdunitform]  select[name=".$cdata."], form[name$=_gdunitform_edit]  select[name=".$cdata."] ').val(\"".$cvalue."\");";
							$edittotalscripts.="$('form[name^=edit]  select[name=".$cdata."]').val(\"".$cvalue."\");";
						}
					}
				}
			}
		}
		// store the total number of grouped data discovered while parsing the result
		// set
		$row['edresultset']['fieldscount']=$fieldnamescount;
		$row['edresultset']['groupscount']=$groupcounter;
		$row['edresultset']['totalscripts']=$totalscripts;
		$row['edresultset']['edittotalscripts']=$edittotalscripts;
	}else{
		$row['edresultset']="";
		$row['edresultset']['totalcount']=0;
	}
	
?>