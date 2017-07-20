<?php
	// Here, the contentgroupdata for this entry is created
	// mainly for admin processing, if the data is for the user,
	// changes to the related edit file would suffice 
	$dogeneraldata=isset($dogeneraldata)?$dogeneraldata:"";
	// echo "$dogeneraldata - general data type<br>";
	if($dogeneraldata=="multiple"){
		// for general 
		$contentgroupdata['adminheadings']['rowdefaults']='<tr><th>Title</th><th>Colour</th><th>Icon</th><th>Link</th><th>Status</th><th>View/Edit</th></tr>';
		$contentgroupdata['adminheadings']['output']=6;
		// create init eval for singlegeneralinfo segment;
		$contentgroupdata['evaldata']['single']['initeval']='
			$iconoutput="";
			if ($icontitle!=="") {
				# code...
				$iconoutput=\'<i class="fa \'.$icontitle.\'"></i>\';
			}
			$linkoutputdata=\'<a href="##">No link data</a>\';
			$linkaddress=$linkaddress!==""?$linkaddress:"##";
			if($linkaddress!=="##"){
				$sortee=strpos($linkaddress, "http://");
				if($sortee!==false&&$sortee===true){

				}else{
					$linkaddress="http://".$linkaddress;
				}
			}
			if($linktitle!==""){
				$linkoutputdata=\'<a href="\'.$linkaddress.\'" target="_blank">\'.$linktitle.\'</a>\';
			}
			$collagecolourtypeout=$collagecolourtype;
			if($collagecolourtype=="purple"){
				$collagecolourtypeout="indigo";
			}
			$tddataoutput="";
			if(isset($collagecolourtype)&&$collagecolourtype!==""){
				$tddataoutput=\'<td>\'.$contenttitle.\'</td><td>\'.strtoupper($collagecolourtypeout).\'</td><td>\'.$iconoutput.\'</td><td>\'.$linkoutputdata.\'</td><td>\'.$status.\'</td><td name="trcontrolpoint"><a href="#&id=\'.$id.\'" name="edit" data-type="editsinglegeneraldata" data-divid="\'.$id.\'">Edit</a></td>\';				
			}
			$showhideall="statusonly";
		';
		
	}else if($dogeneraldata=="single"){
		// for single
	}else{

	}
?>