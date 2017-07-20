<?php
	// Here, the contentgroupdata for this entry is created
	// mainly for admin processing, if the data is for the user,
	// changes to the related edit file would suffice 
	$dogeneraldata=isset($dogeneraldata)?$dogeneraldata:"";
	if(!isset($contentgroupdata)){
		$contentgroupdata=array();
	}
	// echo "$dogeneraldata - general data type<br>";
	if($dogeneraldata=="multiple"){
		if(isset($pagetype)&&!isset($maintype)){
			$maintype=$pagetype;
		}
		if(isset($displaytype)&&!isset($maintype)){
			$maintype=$displaytype;
		}
		
	}else if($dogeneraldata=="single"){
		// for single
	}else{

	}
?>