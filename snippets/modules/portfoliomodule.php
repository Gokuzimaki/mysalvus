<?php
	//Portfolio module, 
	/*
		This module carries functions that interface with portfolio related tables in
		the database.
		The module is used to create most of the features pertaining to the upcomming 
		homonculus cms and defines the common features for function
	*/
	
	// porfoliocategories
	/*table data
		id
		catname
		status
	*/
	/**
	*
	*
	*
	*/
	function getSinglePortfolioCategory($id,$type="",$data=array()){
		global $host_tpathplain;
		include($host_tpathplain.'globalsmodule.php');
		$row=array();
		$query="SELECT * FROM portfoliocategories WHERE id='$id'";

		$qdata=briefquery($query,__LINE__,"mysqli");
		$numrows=$qdata['numrows'];
		$adminoutput='<tr><td colspan="100%">No entries found</td></tr>';
		// init important variable values pertaining to content in the $data array 
		$datamapout="";// caries the datamap for current operations
		$tdataoutput="";// carries the td dataoutput for the tr data cells 
		$formtruetype="";// carries the name of the formtruetype
		$processroute=""; 
		$editroute=""; 
		$totalscripts="";
		$blockdeeprun="";

		if(isset($data['single'])){
			if(isset($data['single']['datamap'])&&$data['single']['datamap']!==""){
				$datamap=$data['single']['datamap'];
				$gd_testdata=JSONtoPHP($datamap);
				$gd_dataoutput=$gd_testdata['arrayoutput'];
				// var_dump($gd_dataoutput);

				$processroute=isset($gd_dataoutput['pr'])?$gd_dataoutput['pr']:"";

				$editroute=isset($gd_dataoutput['er'])?$gd_dataoutput['er']:$processroute;
				$datamapout='data-edata=\''.$datamap.'\'';
			}
			if(isset($data['single']['rowdefaults'])&&$data['single']['rowdefaults']!==""){
				$tdataoutput=$data['single']['rowdefaults'];
			}
			if(isset($data['single']['formtruetype'])&&$data['single']['formtruetype']!==""){
				$formtruetype=$data['single']['formtruetype'];
				// echo $formtruetype;
			}
			if(isset($data['single']['blockdeeprun'])&&$data['single']['blockdeeprun']!==""){
				$blockdeeprun=$data['single']['blockdeeprun'];
				// echo $blockdeeprun;
			}
		}


		$selectionscripts="";
		if($numrows>0){
			$adminoutput="";
			for($i=0;$i<$numrows;$i++){
				$outs=$qdata['resultdata'][$i];
				$row=$outs;
				$id=$outs['id'];
				$catname=$outs['catname'];
				$catdetails=$outs['catdetails'];
				$status=$outs['status'];
				if($formtruetype!==""){
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=status]").val("'.$status.'");';
				}
				$tddataoutput=isset($tdataoutput)&&$tdataoutput!==""?$tdataoutput:'
					<td>'.$catname.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editgeneraldata" data-divid="'.$id.'" '.$datamapout.'>Edit</a></td>
				';

				$adminoutput.='
					<tr data-id="'.$id.'">
						'.$tddataoutput.'
					</tr>
					<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'" '.$datamapout.'>
						<td colspan="100%">
							<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
								<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
									
								</div>
							</div>
						</td>
					</tr>
				';
			}
			
		}
		$row['adminoutput']=$adminoutput;
		$row['totalscripts']=$selectionscripts;
		$initvar[0]="editid";
		$initvar[1]="viewtype";
		$initval[0]=$id;
		$initval[1]="edit";
		$row['adminoutputwo']="";
		
		if($blockdeeprun==""){
			$row['adminoutputtwo']=get_include_contents("../forms/portfoliocategories.php",$initvar,$initval);
		}
		
		return $row;
	} 

	/**
	*	
	*
	*
	*/
	function getAllPortfolioCategories($viewer,$limit,$type="",$data=array()){
		global $host_tpathplain;
		include($host_tpathplain.'globalsmodule.php');
		$row=array();
		
		// this block handles the content of the limit data
		str_replace("-", "", $limit);
		$testit=strpos($limit,"-");
		$testit?$limit="":$limit=$limit;
		$testittwo=strpos($limit,",");
		if($testittwo===0||$testittwo===true||$testittwo>0){
			$limit=$limit;
		}else{
			if(strtolower($limit)=="all"){
				$limit="";
			}else{
				$limit="LIMIT 0,15";				
			}
		}

		$realoutputdata="";
		$mtype="";
		if($type!==""){
			$mtype=$type;
			if (is_array($type)) {
				# code...
				if(isset($mtype['lastid'])||isset($mtype['nextid'])){
					$callpage="true";
					if(isset($mtype['lastid'])){
						$addq=" AND id>".$mtype['lastid'];
					}
					if(isset($mtype['nextid'])){
						$addq=" AND id<".$mtype['nextid'];
					}
				}
				$type=$mtype[0];
				$typeval=$mtype[1];
				$realoutputdata="$type][$typeval";
			
				
			}else{
				$realoutputdata=$type;
			}
		}

		// prep the datamap element
		$mapelement="";
		if(isset($data['datamap'])&&$data['datamap']!==""){
			// array map element map data for handling custom gd request
			// echo "maptrick<br>";
			$curdatamap=JSONtoPHP($data['datamap']);
			if($curdatamap['error']=="No error"){
				$mapelement='
					<input type="hidden" name="datamap" value=\''.$data['datamap'].'\'/>
				';
			}
		}

		// run through the data array and obtain only the 'single' index
		// of it
		$singletype="";
		if(isset($data['single']['type'])&&$data['single']['type']!==""){
			$singletype=$data['single']['type'];
		}

		// check to see if there is an entry for the 'type' parameter in the single
		// selection version of the current entry.
		$outputtype="generalpages|portfoliocategories|".$viewer;
		$queryextra="";
		$ordercontent="order by id desc";
		
		if(isset($data['queryorder'])&&$data['queryorder']!==""){
			$ordercontent=$data['queryorder'];
		}
		
		if($viewer=="admin"){
			$query="SELECT * FROM portfoliocategories $queryextra $ordercontent $limit";
			$rowmonitor['chiefquery']="SELECT * FROM portfoliocategories $queryextra $ordercontent";
		}else if ($viewer=="viewer") {
			# code...
			$query="SELECT * FROM portfoliocategories WHERE status='active' $queryextra $ordercontent $limit";
			$rowmonitor['chiefquery']="SELECT * FROM portfoliocategories WHERE status='active' $queryextra $ordercontent";
		}else if(is_array($viewer)){
			$viewer=$viewer[0];
			$searchtype=$viewer[1];
			$searchval=$viewer[2];
 		  	$outputtype="generalpages|portfoliocategorysearch|$viewer";
 		  	$realoutputdata="$searchtype][$searchval";
		}

		// unique hash per data transaction call
		$rmd5=md5($rowmonitor['chiefquery'].date("Y-m-d H:i:s"));

		// create the $_SESSION['generalpagesdata'] array value to ensure continuity
		// when paginating content. This is done by checking the sessionuse 
		if((!isset($data['sessionuse'])&&!isset($data['chash']))||
			($data['sessionuse']==""&&$data['chash']=="")){

			// store current output type
			$_SESSION['generalpagesdata']["$rmd5"]['outputtype']=$outputtype;
			
			// store current data array
			$_SESSION['generalpagesdata']["$rmd5"]['data']=$data;
			
			// store current type entry
			$_SESSION['generalpagesdata']["$rmd5"]['type']=$mtype;
			
			// set the value for the pagination handler file path
			// using the 'snippets' folder as the root. 
			$_SESSION['generalpagesdata']["$rmd5"]['pagpath']="forms/portfoliocategories.php";
			
			// use the 'curqueries' session index to secure this query
			$_SESSION['cq']["$rmd5"]=$rowmonitor['chiefquery'];
		}

		$qdata=briefquery($query,__LINE__,"mysqli");
		
		$adminoutput='<tr><td colspan="100%">No entries found</td></tr>';
		
		$row['resultdataset']=array();
		$numrows=$qdata['numrows'];
    	$selectiondata='<option value="">--Choose Category--</option>';
		
		if($qdata['numrows']>0){
			
			$adminoutput="";
			
			for($i=0;$i<$qdata['numrows'];$i++){

				$id=$qdata['resultdata'][$i]['id'];
				$outs=getSinglePortfolioCategory($id,$singletype,$data);
				$selectiondata.='<option value="'.$id.'">'.$outs['catname'].'</option>';
				$row['resultdataset'][$i]=$outs;
				$adminoutput.=$outs['adminoutput'];
				
			}
		}


		$outs=paginatejavascript($rowmonitor['chiefquery']);
		$row['selectiondata']=$selectiondata;
		$row['chiefquery']=$rowmonitor['chiefquery'];
		$row['num_pages']=$outs['num_pages'];

		$adminheadings='
					<thead><tr><th>Category Name</th><th>Status</th><th>View/Edit</th></tr></thead>
		';
		if(isset($data['group']['adminheadings'])&&$data['group']['adminheadings']=""){
			$adminheadings=$data['group']['adminheadings'];
		}
		$top='<table id="resultcontenttable" cellspacing="0">
					'.$adminheadings.'
					<tbody>';

		$bottom='	</tbody>
				</table>';

		

		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.$rmd5.'"/>
				<input type="hidden" name="outputtype" value="'.$outputtype.'|'.$realoutputdata.'"/>
				'.$mapelement.'
				<input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
				<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
				<div class="pagination">
					  '.$outs['usercontrols'].'
				</div>
			</div>
		</div>
		<div id="paginateddatahold" data-name="contentholder">';
		$paginatebottom='
		</div><div id="paginationhold">
			<div class="meneame">
				<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
			</div>
		</div>';
		$row['numrows']=$numrows;
		$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
		$row['adminoutputtwo']=$top.$adminoutput.$bottom;


		return $row;
	}


	// porfolio
	/*table data
		id,
		catid,
		type,
		gattach,
		vidattach,
		audattach,
		companyname,
		position,
		projecttitle,
		periodstart,
		periodend,
		entrydate,
		shorttext,
		details,
		projectsocial,
		featured,
		fdtype,
		pwrdd,
		pwd,
		tags,
		status
	*/
	/**
	*
	*
	*
	*/
	function getSinglePortfolioEntry($id,$type="",$data=array()){
		global $host_tpathplain;
		include($host_tpathplain.'globalsmodule.php');
		$row=array();
		$query="SELECT * FROM portfolio WHERE id='$id'";

		$qdata=briefquery($query,__LINE__,"mysqli");
		$numrows=$qdata['numrows'];
		$adminoutput='<tr><td colspan="100%">No entries found</td></tr>';
		$adminsuboutput="";
		// init important variable values pertaining to content in the $data array 
		$datamapout="";// caries the datamap for current operations
		$tdataoutput="";// carries the td dataoutput for the tr data cells 
		$tdsubataoutput="";// carries the div dataoutput for the div table data cells 
		$formtruetype="";// carries the name of the formtruetype
		$processroute=""; 
		$editroute=""; 
		$totalscripts="";
		//specifies if an edit file data retrieval is to be done for 
		// the editting the current entry being retrieved.  
		$blockdeeprun="";

		$row['featureddata']=array();
		$row['featureddata']["featuredadlaptop"]=array();
		$row['featureddata']["featuredadiphone"]=array();
		$row['featureddata']["featuredadandroid"]=array();
		$row['featureddata']["featuredtablet"]=array();

		$row['gallerydata']=array();
		$row['videodata']=array();
		$row['audiodata']=array();

		if(isset($data['single'])){
			if(isset($data['single']['datamap'])&&$data['single']['datamap']!==""){
				$datamap=$data['single']['datamap'];
				$gd_testdata=JSONtoPHP($datamap);
				$gd_dataoutput=$gd_testdata['arrayoutput'];
				// var_dump($gd_dataoutput);

				$processroute=isset($gd_dataoutput['pr'])?$gd_dataoutput['pr']:"";

				$editroute=isset($gd_dataoutput['er'])?$gd_dataoutput['er']:$processroute;
				$datamapout='data-edata=\''.$datamap.'\'';
			}
			if(isset($data['single']['rowdefaults'])&&$data['single']['rowdefaults']!==""){
				$tdataoutput=$data['single']['rowdefaults'];
			}
			if(isset($data['single']['formtruetype'])&&$data['single']['formtruetype']!==""){
				$formtruetype=$data['single']['formtruetype'];
				// echo $formtruetype;
			}
			// get the block deep run value, for controlling the edit form processing
			// section.
			if(isset($data['single']['blockdeeprun'])&&$data['single']['blockdeeprun']!==""){
				$blockdeeprun=$data['single']['blockdeeprun'];
				// echo $blockdeeprun;
			}
		}


		$selectionscripts="";
		if($numrows>0){
			$adminoutput="";
			$adminosubutput="";

			for($i=0;$i<$numrows;$i++){
				$outs=$qdata['resultdata'][$i];
				$row=$outs;
				$id=$outs['id'];
				$catid=$outs['catid'];
				// get the category name
				$catq="SELECT * FROM portfoliocategories WHERE id='$catid'";
				$catqdata=briefquery($catq,__LINE__,"mysqli");
				$catname="";
				if($catqdata['numrows']>0){
					$catname=$catqdata['resultdata'][0]['catname'];
				}

				$type=$outs['type'];
				$gattach=$outs['gattach'];
				$vidattach=$outs['vidattach'];
				$audattach=$outs['audattach'];
				$companyname=$outs['companyname'];
				$position=$outs['position'];
				$projecttitle=$outs['projecttitle'];
				$periodstart=$outs['periodstart'];
				$periodend=$outs['periodend'];
				$endout=$periodend;
				if($periodend=="0000-00-00"){
					$endout="ongoing";
				}
				$entrydate=$outs['entrydate'];
				$shorttext=$outs['shorttext'];
				$details=$outs['details'];
				$projectsocial=$outs['projectsocials'];
				$featured=$outs['featured'];
				$fdtype=$outs['fdtype'];
				$pwrdd=$outs['pwrdd'];
				$pwd=$outs['pwd'];
				$tags=$outs['tags'];
				$status=$outs['status'];
				
				// begin collation of extra associated data to this entry  
				
				// check for coverimage entry
				$mediaquery="SELECT * FROM media WHERE ownerid=$id AND 
								ownertype='portfolio' AND maintype='coverimage' AND status='active'";
				$mqdata=briefquery($mediaquery,__LINE__,"mysqli");
				$mrows=$mqdata['numrows'];
				$covertotal=$mrows;
				if($mrows>0){

					$mqrows=$mqdata['resultdata'][0];
					$maintype=$mqrows['maintype'];
					$fid=$mqrows['id'];
					$caption=$mqrows['title'];
					$details=$mqrows['details'];
					$location=$host_addr.$mqrows['location'];
					$medsize=$host_addr.$mqrows['medsize'];
					$thumbnail=$host_addr.$mqrows['thumbnail'];
					$row['coverimagedata'][]=array("location"=>"$location",
												"medsize"=>"$medsize",
												"thumbnail"=>"$thumbnail",
												"caption"=>"$caption",
												"details"=>"$details",
												"id"=>"$fid"
											);
					
				}
				$row['coverimagedata']['total']=$covertotal;

				// check for bannerimage entry
				$mediaquery="SELECT * FROM media WHERE ownerid=$id AND 
								ownertype='portfolio' AND maintype='bannerimage' AND status='active'";
				$mqdata=briefquery($mediaquery,__LINE__,"mysqli");
				$mrows=$mqdata['numrows'];
				$bannertotal=$mrows;
				if($mrows>0){

					$mqrows=$mqdata['resultdata'][0];
					$maintype=$mqrows['maintype'];
					$fid=$mqrows['id'];
					$caption=$mqrows['title'];
					$details=$mqrows['details'];
					$location=$host_addr.$mqrows['location'];
					$medsize=$host_addr.$mqrows['medsize'];
					$thumbnail=$host_addr.$mqrows['thumbnail'];
					$row['bannerimagedata'][]=array("location"=>"$location",
												"medsize"=>"$medsize",
												"thumbnail"=>"$thumbnail",
												"caption"=>"$caption",
												"details"=>"$details",
												"id"=>"$fid"
											);
					
				}
				$row['bannerimagedata']['total']=$bannertotal;

				// check for featured entry attachment images
				if($featured=="yes"){
					
					$mediaquery="SELECT * FROM media WHERE ownerid=$id AND 
									ownertype='portfolio' AND maintype LIKE '%featuredad%'
									AND status='active'";
					$mqdata=briefquery($mediaquery,__LINE__,"mysqli");
					$mrows=$mqdata['numrows'];
					if($mrows>0){
						for($mq=0;$mq<$mrows;$mq++){
							$mqrows=$mqdata['resultdata'][$mq];
							$fid=$mqrows['id'];
							$ctype=$mqrows['maintype'];
							$location=$host_addr.$mqrows['location'];
							$medsize=$host_addr.$mqrows['medsize'];
							$thumbnail=$host_addr.$mqrows['thumbnail'];
							$row['featureddata']["$ctype"]=array("location"=>"$location",
																"medsize"=>"$medsize",
																"thumbnail"=>"$thumbnail",
																"id"=>"$fid"
															);
						}
					}
				}
				$row['featureddata']["total"]=$mrows;

				// check for gallery attachment
				$row['gallerydata']=array();
				$gallerytotal=0;
				if($gattach=="yes"){ 

					$mediaquery="SELECT * FROM media WHERE ownerid=$id AND 
									ownertype='portfolio' AND maintype='portgallery' 
									AND status='active'";
					$mqdata=briefquery($mediaquery,__LINE__,"mysqli");
					$mrows=$mqdata['numrows'];
					$gallerytotal=$mrows;
					if($mrows>0){
						for($mq=0;$mq<$mrows;$mq++){
							$mqrows=$mqdata['resultdata'][$mq];
							$maintype=$mqrows['maintype'];
							$fid=$mqrows['id'];
							$caption=$mqrows['title'];
							$details=$mqrows['details'];
							$location=$host_addr.$mqrows['location'];
							$medsize=$host_addr.$mqrows['medsize'];
							$thumbnail=$host_addr.$mqrows['thumbnail'];
							$row['gallerydata'][]=array("location"=>"$location",
														"medsize"=>"$medsize",
														"thumbnail"=>"$thumbnail",
														"caption"=>"$caption",
														"details"=>"$details",
														"id"=>"$fid"
													);
						}
					}
				}
				$row['gallerydata']['total']=$gallerytotal;

				// check for video file attachements
				$row['viddata']=array();
				$vidtotal=0;
				if($vidattach=="yes"){ 

					$mediaquery="SELECT * FROM media WHERE ownerid=$id AND 
								ownertype='portfolio' AND maintype='portfoliovideo' 
								AND status='active'";
					$mqdata=briefquery($mediaquery,__LINE__,"mysqli");
					$mrows=$mqdata['numrows'];
					$vidtotal=$mrows;
					if($mrows>0){
						for($mq=0;$mq<$mrows;$mq++){
							$t=$mq+1;
							$mqrows=$mqdata['resultdata'][$mq];
							$caption=$mqrows['title'];
							$jsondata=$mqrows['details'];
							if(str_replace(" ", "",$jsondata)!==""){

								$portvdata=JSONtoPHP($jsondata);
								$portvdata=$portvdata['arrayoutput'];
								// var_dump($portvdata);
								$fid=$mqrows['id'];
								
								// the displaytype for the video  data
								// its either 'local' or 'embed'
								$disptype=$portvdata['portvtype'];
								$portvwebm=$portvdata['portvwebm']['location'];
								$portv3gp=$portvdata['portv3gp']['location'];
								$portvflv=$portvdata['portvflv']['location'];
								$portvembed=$portvdata['portvembed'];

								// create absolute url values for entries that have
								// content
								$portvwebm=$portvwebm!==""?$host_addr.$portvwebm:$portvwebm;
								$portv3gp=$portv3gp!==""?$host_addr.$portv3gp:$portv3gp;
								$portvflv=$portvflv!==""?$host_addr.$portvflv:$portvflv;

								
								// get the data output
								$selectionscripts.='$("form[name='.$formtruetype.'] select[name=portvtype'.$t.']").val("'.$disptype.'");';
								$row['viddata'][$mq]=array("disptype"=>"$disptype",
														"portvwebm"=>"$portvwebm",
														"portvflv"=>"$portvflv",
														"portv3gp"=>"$portv3gp",
														"portvembed"=>"$portvembed",
														"caption"=>"$caption",
														"details"=>"$details",
														"originalmap"=>"$jsondata",
														"id"=>"$fid"
													);
							}else{
								$row['viddata'][$mq]=array("disptype"=>"local",
														"portvwebm"=>"",
														"portvflv"=>"",
														"portv3gp"=>"",
														"portvembed"=>"",
														"caption"=>"$caption",
														"details"=>"$details",
														"originalmap"=>"",
														"id"=>"$fid"
													);
							}

						}
					}
				}
				$row['viddata']['total']=$vidtotal;


				// check for audio file attachments
				$row['audiodata']=array();
				$audiototal=0;
				if($audattach=="yes"){ 

					$mediaquery="SELECT * FROM media WHERE ownerid=$id AND 
									ownertype='portfolio' AND maintype='portfolioaudio' AND status='active'";
					$mqdata=briefquery($mediaquery,__LINE__,"mysqli");
					$mrows=$mqdata['numrows'];
					$audiototal=$mrows;
					if($mrows>0){
						for($mq=0;$mq<$mrows;$mq++){
							$t=$mq+1;
							$mqrows=$mqdata['resultdata'][$mq];
							$fid=$mqrows['id'];
							$maintype=$mqrows['maintype'];
							$mtype=$mqrows['mediatype'];
							$caption=$mqrows['title'];
							$details=$mqrows['details'];
							$location=$host_addr.$mqrows['location'];					
							$selectionscripts.='$("form[name='.$formtruetype.'] select[name=portatype'.$t.']").val("'.$mtype.'");';
							$row['audiodata'][$mq]=array("location"=>"$location",
														"portacaption"=>"$caption",
														"portaembed"=>"$details",
														"disptype"=>"$mtype",
														"id"=>"$fid"
													);
						}
					}
				}
				$row['audiodata']['total']=$audiototal;

				// create selection value scripts 
				if($formtruetype!==""){
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=catid]").val("'.$catid.'");';
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=portfoliotype]").val("'.$type.'");';
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=galleryattach]").val("'.$gattach.'");';
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=vidattach]").val("'.$vidattach.'");';
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=audioattach]").val("'.$audattach.'");';
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=featured]").val("'.$featured.'");';
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=pwrdd]").val("'.$pwrdd.'");';
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=fdoption]").val("'.$fdtype.'");';
					$selectionscripts.='$("form[name='.$formtruetype.'] select[name=status]").val("'.$status.'");';
				}
				$tddataoutput=isset($tdataoutput)&&$tdataoutput!==""?$tdataoutput:'
					<td>'.$projecttitle.'</td>
					<td>'.$type.'</td>
					<td>'.$catname.'</td>
					<td>'.$periodstart.'</td>
					<td>'.$endout.'</td>
					<td>'.$gattach.'</td>
					<td>'.$vidattach.'</td>
					<td>'.$audattach.'</td>
					<td>'.$pwrdd.'</td>
					<td>'.$featured.'</td>
					<td>'.$status.'</td>
					<td name="trcontrolpoint">
						<a href="#&id='.$id.'" name="edit" data-type="editgeneraldata" 
						data-divid="'.$id.'" '.$datamapout.'>
							Edit
						</a>
					</td>
				';

				$adminoutput.='
					<tr data-id="'.$id.'">
						'.$tddataoutput.'
					</tr>
					<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'" '.$datamapout.'>
						<td colspan="100%">
							<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
								<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
									
								</div>
							</div>
						</td>
					</tr>
				';
			}
			
		}

		$row['numrows']=$numrows;
		$row['adminoutput']=$adminoutput;
		$row['adminsuboutput']=$adminsuboutput;
		$row['totalscripts']=$selectionscripts;
		$initvar[0]="editid";
		$initvar[1]="viewtype";
		$initvar[1]="row";
		$initval[0]=$id;
		$initval[1]="edit";
		$initval[2]=$row;
		$row['adminoutputwo']="";
		if($blockdeeprun==""){
			$row['adminoutputtwo']=get_include_contents("../forms/portfolioentries.php",$initvar,$initval);
		}
		
		return $row;
	} 

	/**
	*
	*
	*
	*/
	function getAllPortfolioEntries($viewer,$limit,$type="",$data=array()){
		global $host_tpathplain;
		include($host_tpathplain.'globalsmodule.php');
		$row=array();
		str_replace("-", "", $limit);
		$testit=strpos($limit,"-");
		$testit?$limit="":$limit=$limit;
		$testittwo=strpos($limit,",");
		if($testittwo===0||$testittwo===true||$testittwo>0){
			$limit=$limit;
		}else{
			if(strtolower($limit)=="all"){
				$limit="";
			}else{
				$limit="LIMIT 0,15";				
			}
		}

		$realoutputdata="";
		$mtype="";
		if($type!==""){
			$mtype=$type;
			if (is_array($type)) {
				# code...
				if(isset($mtype['lastid'])||isset($mtype['nextid'])){
					$callpage="true";
					if(isset($mtype['lastid'])){
						$addq=" AND id>".$mtype['lastid'];
					}
					if(isset($mtype['nextid'])){
						$addq=" AND id<".$mtype['nextid'];
					}
				}
				$type=$mtype[0];
				$typeval=$mtype[1];
				$realoutputdata="$type][$typeval";
			
				
			}else{
				$realoutputdata=$type;
			}
		}

		// prep the datamap element
		$mapelement="";
		if(isset($data['datamap'])&&$data['datamap']!==""){
			// array map element map data for handling custom gd request
			// echo "maptrick<br>";
			$curdatamap=JSONtoPHP($data['datamap']);
			if($curdatamap['error']=="No error"){
				$mapelement='
					<input type="hidden" name="datamap" value=\''.$data['datamap'].'\'/>
				';
			}else{
				echo"<br>Map error<br>";
			}
		}
		
		// generic $row output index, can be used to explicitly control the output 
		// of the current query being run. Prior knowledge of functions output
		// index values is required.
		$pcoutput="";
		if(isset($data['pcoutput'])&&$data['pcoutput']!==""){
			$pcoutput=$data['pcoutput'];
		}
		// check to see if the 'displaytype' index is present and assign it a variable
		$displaytype="";
		if(isset($data['displaytype'])&&$data['displaytype']!==""){
			$displaytype=$data['displaytype'];

		}		

		// run through the data array and obtain only the 'single' index
		// of it
		$singletype="";
		if(isset($data['single']['type'])&&$data['single']['type']!==""){
			$singletype=$data['single']['type'];
		}

		// check to see if there is an entry for the 'type' parameter in the single
		// selection version of the current entry.
		$outputtype="generalpages|portfolioentry|".$viewer;
		$queryextra="";
		$ordercontent="order by id desc";
		$qcat=$viewer=="admin"?"WHERE":"AND";

		// chect to see if extra query data are to be appended
		if(isset($data['queryextra'])&&$data['queryextra']!==""){
			if($queryextra==""){
				$queryextra.=" $qcat ".$data['queryextra'];
			}else{
				$queryextra.=" AND ".$data['queryextra'];
			}
		}
		if(isset($data['queryorder'])&&$data['queryorder']!==""){
			$ordercontent=$data['queryorder'];
		}

		
		if($viewer=="admin"){
			$query="SELECT * FROM portfolio $queryextra $ordercontent $limit";
			$rowmonitor['chiefquery']="SELECT * FROM portfolio $queryextra $ordercontent";
		}else if ($viewer=="viewer") {
			# code...
			$query="SELECT * FROM portfolio WHERE status='active' $queryextra $ordercontent $limit";
			$rowmonitor['chiefquery']="SELECT * FROM portfolio WHERE status='active' $queryextra $ordercontent";
		}else if(is_array($viewer)){
			$viewer=$viewer[0];
			$searchtype=$viewer[1];
			$searchval=$viewer[2];
 		  	$outputtype="generalpages|portfolioentrysearch|$viewer";
 		  	$realoutputdata="$searchtype][$searchval";
		}
		// echo $viewer;
		// echo $query;
		// return the query, only for tests with Ajax json 
		$row['cqtdata']=$query;

		// unique hash per data transaction call
		$rmd5=md5($rowmonitor['chiefquery'].date("Y-m-d H:i:s"));
		
		// overide variable for ipp
		// $ipparr_overide=array();
		$ipparr_overide=array(10,20,50,80,100,"All");
		$paginationdataout['ipparr_overide']=$ipparr_overide;

		// create the $_SESSION['generalpagesdata'] array value to ensure continuity
		// when paginating content. This is done by checking the sessionuse 
		if((!isset($data['sessionuse'])&&!isset($data['chash']))||
			($data['sessionuse']==""&&$data['chash']=="")){

			// store current output type
			$_SESSION['generalpagesdata']["$rmd5"]['outputtype']=$outputtype;
			

			// store current data array
			$_SESSION['generalpagesdata']["$rmd5"]['data']=$data;

			// store custom ipp array
			$_SESSION['generalpagesdata']["$rmd5"]['ipparr_overide']=$ipparr_overide;
			
			// store current type entry
			$_SESSION['generalpagesdata']["$rmd5"]['type']=$mtype;
			
			// store current type entry
			$_SESSION['generalpagesdata']["$rmd5"]['pcoutput']=$pcoutput;
			
			// store current type entry
			$_SESSION['generalpagesdata']["$rmd5"]['realoutputdata']="$realoutputdata|$rmd5";

			// set the value for the pagination handler file path
			// using the 'snippets' folder as the root. 
			$_SESSION['generalpagesdata']["$rmd5"]['pagpath']="forms/portfolioentries.php";
			
			// use the 'curqueries' session index to secure this query
			$_SESSION['cq']["$rmd5"]=$rowmonitor['chiefquery'];
		}

		$qdata=briefquery($query,__LINE__,"mysqli");
		
		$adminoutput='<tr><td colspan="100%">No entries found</td></tr>';
		$adminsuboutput='<div class="rTableRow"><div class="rTableCell colspan" colspan="100%">No entries found</div></div>';
		
		$row['resultdataset']=array();
		$numrows=$qdata['numrows'];
    	$selectiondata='<option value="">--Choose--</option>';
		$formoutput="";
		$totalscripts="";
		if($qdata['numrows']>0){
			
			$adminoutput="";
			$adminsuboutput="";
			
			for($i=0;$i<$qdata['numrows'];$i++){

				$id=$qdata['resultdata'][$i]['id'];
				$outs=getSinglePortfolioEntry($id,$singletype,$data);
				$selectiondata.='<option value="'.$id.'" data-title="'.$outs['projecttitle'].'">'.$outs['projecttitle'].' </option>';
				$row['resultdataset'][$i]=$outs;
				$adminoutput.=$outs['adminoutput'];
				$adminsuboutput.=$outs['adminsuboutput'];
				// $formoutput.=$outs['formoutput'];
				$totalscripts.=$outs['totalscripts'];
			}
		}


		$outs=paginatejavascript($rowmonitor['chiefquery'],"",$paginationdataout);
		$row['formoutput']='<input name="entrycount" type="hidden" value="'.$qdata['numrows'].'">'.$formoutput;
		$row['totalscripts']=$totalscripts;
		$row['selectiondata']=$selectiondata;
		$row['chiefquery']=$rowmonitor['chiefquery'];
		$row['num_pages']=$outs['num_pages'];

		// admin table heading variables for the table element and div 'tableelement'
		// can be altered using the data['group']['adminheadings/adminsubheadings']
		// index

		// prepare the defaults
		$adminheadings='
			<thead>
				<tr>
					<th>Title</th>
					<th>Type</th>
					<th>Category</th>
					<th>Date Started</th>
					<th>Date Completed</th>
					<th><i class="fa fa-file-image-o" title="Gallery"></i></th>
					<th><i class="fa fa-video-camera" title="Video"></i></th>
					<th><i class="fa fa-volume-up" title="Audio"></i></th>
					<th><i class="fa fa-lock" title="Passworded"></i></th>
					<th>Featured</th>
					<th>Status</th>
					<th>View/Edit</th>
				</tr>
			</thead>
		';
		$adminsubheadings='
			<div class="rTableHeading">
				<div class="rTableRow">
					<div  class="rTableHead">Title</div>
					<div  class="rTableHead">Type</div>
					<div  class="rTableHead">Category</div>
					<div  class="rTableHead">Date Started</div>
					<div  class="rTableHead">Date Completed</div>
					<div  class="rTableHead"><i class="fa fa-file-image-o" title="Gallery"></i></div>
					<div  class="rTableHead"><i class="fa fa-video-camera" title="Video"></i></div>
					<div  class="rTableHead"><i class="fa fa-volume-up" title="Audio"></i></div>
					<div  class="rTableHead"><i class="fa fa-lock" title="Passworded"></i></div>
					<div  class="rTableHead">Featured</div>
					<div class="rTableHead">Status</div>
					<div class="rTableHead">View/Edit</div>
				</div>
			</div>
		';

		$admintableclass='';
		$adminsubtableclass='';
		
		// assign control values from data array index if available
		if(isset($data['group']['adminheadings'])&&$data['group']['adminheadings']!==""){
			$adminheadings=$data['group']['adminheadings'];
		}
		if(isset($data['group']['adminsubheadings'])&&$data['group']['adminsubheadings']!==""){
			$adminsubheadings=$data['group']['adminsubheadings'];
		}
		if(isset($data['group']['admintableclass'])&&$data['group']['admintableclass']!==""){
			$admintableclass='class="'.$data['group']['admintableclass'].'"';
		}
		if(isset($data['group']['adminsubtableclass'])&&$data['group']['adminsubtableclass']!==""){
			$adminsubtableclass='class="'.$data['group']['adminsubtableclass'].'"';
		}

		$top='<table id="resultcontenttable" '.$admintableclass.' cellspacing="0">
					'.$adminheadings.'
					<tbody>';

		$bottom='	</tbody>
				</table>';

		$subtop='<div class="rTable" id="resultcontenttable" '.$adminsubtableclass.' cellspacing="0">
					'.$adminsubheadings.'
					<div class="rTableBody">';

		$subbottom='	</div>
				</div>';

		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.$rmd5.'"/>
				<input type="hidden" name="outputtype" value="'.$outputtype.'|'.$realoutputdata.'|'.$rmd5.'"/>
				'.$mapelement.'
				<input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
				<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
				<div class="pagination">
					  '.$outs['usercontrols'].'
				</div>
			</div>
		</div>
		<div id="paginateddatahold" data-name="contentholder">';
		$paginatebottom='
		</div><div id="paginationhold">
			<div class="meneame">
				<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
			</div>
		</div>';
		$row['paginatetop']=$paginatetop;
		$row['paginatebottom']=$paginatebottom;
		$row['numrows']=$numrows;
		$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
		$row['adminsuboutput']=$paginatetop.$subtop.$adminsuboutput.$subbottom.$paginatebottom;
		$row['adminoutputtwo']=$top.$adminoutput.$bottom;
		$row['adminsuboutputtwo']=$subtop.$adminsuboutput.$subbottom;
		$row['adminsuboutputplain']=$adminsuboutput;

		$row['genericout']=$pcoutput!==""?$row[''.$pcoutput.'']:"";

		return $row;
	}
?>