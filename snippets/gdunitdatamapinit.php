<?php
// the purpose here is to initialise and process the datamap/JSON map
// for the _gdunit generaldatamodule handler
//  the variable $datamap must be available for this to work
// check if a control variable is availble
if(isset($_gdunit_viewtype)&&isset($datamap)){
	$_crt=strpos($_gdunit_viewtype, "_crt");
	// var_dump($_crt);
	// echo "we in $_gdunit_viewtype $_crt <br>";
	// var_dump($datamap);
	if($_gdunit_viewtype=="create"||($_crt===true||$_crt>0||$_crt==1)){
		$gd_data=JSONtoPHP($datamap);
		// echo "we in $_gdunit_viewtype $_crt <br>";
		if(strtolower($gd_data['error'])=="no error"){
			// store the final array content in local variable
			// the list of compulsory basic variables are shown below
			// $gd_dataoutput, $datamap,viewtype, variant,maintype and subtype.
			$gd_dataoutput=$gd_data['arrayoutput'];
			// var_dump($gd_dataoutput);
			$viewtype=$gd_dataoutput['vt']?$gd_dataoutput['vt']:"";
			$maintype=isset($gd_dataoutput['mt'])?$gd_dataoutput['mt']:"";
			$preinit=isset($gd_dataoutput['preinit'])?$gd_dataoutput['preinit']:true;
			$processroute=isset($gd_dataoutput['pr'])?$gd_dataoutput['pr']:"";
			// $editroute=isset($gd_dataoutput['er'])?$gd_dataoutput['er']:"";
			$subtype=isset($gd_dataoutput['st'])?$gd_dataoutput['st']:"content";
			$actionpath=isset($gd_dataoutput['actionpath'])?$gd_dataoutput['actionpath']:"snippets/edit.php";
			$ugi=isset($gd_dataoutput['ugi'])?$gd_dataoutput['ugi']:true;
			$variant=$gd_dataoutput['vnt'];
			$formtruetype=!is_array($maintype)?$maintype."_gdunitform":"";
			if($gd_dataoutput['vt']=="create"||$_crt===true||$_crt>0||$_crt==0){
				// change the value of the current vt datamap index to reflect the next
				// wanted action in the form 
				$gd_dataoutput['vt']="edit";
			}
			$gd_nset=json_encode($gd_dataoutput);
			// setup entry array for generaldata functions
			$contentgroupdatageneral=array();
			$contentgroupdatageneral['datamap']=$gd_nset;
			if($maintype!==""&&$processroute!==""){
				
				// init all actionpaths
				if(is_array($actionpath)&&is_array($maintype)){
					
					// the variables to be used for housing initial generalinfo 
					// initialised here
					$testactionpath=$actionpath;
					// var_dump($testactionpath);
					for($i=0;$i<count($testactionpath);$i++){
						
						$t=$i+1;
						$placebo=$i==0?"actionpath":"actionpath$t";
						// echo $placebo." Current placebo<br>";
						// echo $testactionpath[$i]."<br>";
						// check to see if the index has a value otherwise set it to the default
						$$placebo=isset($testactionpath[$i])&&$testactionpath[$i]!==""?$testactionpath[$i]:"snippets/edit.php";
					}
				}
				
				// sort the maintype in case its an array
				if(is_array($maintype)){
					// the variables to be used for housing initial generalinfo 
					// initialised here
					$testmaintype=$maintype;
					for($i=0;$i<count($testmaintype);$i++){
						$t=$i+1;
						$placebo=$i==0?"outsdata":"outsdata$t";
						$cmaintype=$testmaintype[$i];
						// echo $cmaintype;

						$cpreinit=isset($preinit[$i])?$preinit[$i]:true;
						// echo $cpreinit;
						if($cpreinit==true&&$ugi==true){
							$$placebo=getAllGeneralInfo("admin","$cmaintype","","",$contentgroupdatageneral);
						}
						$placebo2=$i==0?"maintype":"maintype$t";
						$$placebo2=$cmaintype;
						$placebo3=$i==0?"formtruetype":"formtruetype$t";
						$$placebo3=$cmaintype."_gdunitform";

					}
				}else{
				
					// this variable is used to start of checks in the processing file
					if($preinit==true&&$ugi==true){
						$outsdata=getAllGeneralInfo("admin","$maintype","","",$contentgroupdatageneral);
					}
				}
				
				// init all the variants if the value is an array
				if(is_array($variant)){
				
					// the variables to be used for housing initial generalinfo 
					// initialised here
					$testvariant=$variant;
					for($i=0;$i<count($testvariant);$i++){
						$t=$i+1;
						$placebo=$i==0?"variant":"variant$t";
						$$placebo=$testvariant[$i];
					}
				}
			}else{
				echo "<div>An error occured, invalid or no Process Route defined.<br> Or <br> 
							An error occured, invalid or no 'mt' _gdunit json index
 						</div>";

			}	
		}else{
			echo "<div>An error occured, the error is ".$gd_data['error']."</div>";

		}
	}else if($_gdunit_viewtype=="edit"){
		$gd_data=JSONtoPHP($datamap);
		// var_dump($gd_data);
		// echo "ghsjhdskg shkg sjk gsjg sjg".$gd_data['error'];
		if(strtolower($gd_data['error'])=="no error"){
			// store the final array content in local variable
			$gd_dataoutput=$gd_data['arrayoutput'];
			// $gd_dataoutput, $datamap,viewtype, variant,maintype and subtype.
			$gd_dataoutput=$gd_data['arrayoutput'];
			// var_dump($gd_dataoutput);
			$viewtype=$gd_dataoutput['vt']?$gd_dataoutput['vt']:"";
			$maintype=isset($gd_dataoutput['mt'])?$gd_dataoutput['mt']:"";
			$preinit=isset($gd_dataoutput['preinit'])?$gd_dataoutput['preinit']:true;
			$processroute=isset($gd_dataoutput['pr'])?$gd_dataoutput['pr']:"";
			// echo $processroute;
			$editroute=isset($gd_dataoutput['er'])?$gd_dataoutput['er']:$processroute;
			$subtype=isset($gd_dataoutput['st'])?$gd_dataoutput['st']:"content";
			$ugi=isset($gd_dataoutput['ugi'])?$gd_dataoutput['ugi']:true;
			$actionpath=isset($gd_dataoutput['actionpath'])?$gd_dataoutput['actionpath']:"snippets/edit.php";
			$variant=$gd_dataoutput['vnt'];
			$formtruetype=!is_array($maintype)?$maintype."_gdunitform":"";
			// check to see if the current entry has a 'create' attachment for 
			// sub binding the edit map value to its final map output

			if($gd_dataoutput['vt']=="create"||$_crt===true||$_crt>0||$_crt==0){
				// change the value of the current vt datamap index to reflect the next
				// wanted action in the form 
				$gd_dataoutput['vt']="edit";
			}
			$gd_nset=json_encode($gd_dataoutput);
			// setup entry array for generaldata functions
			$contentgroupdatageneral=array();
			$contentgroupdatageneral['datamap']=$datamap;
			if($maintype!==""&&$processroute!==""){
				
				// init all actionpaths if array
				if(is_array($actionpath)&&is_array($maintype)){
					
					// the variables to be used for housing initial generalinfo 
					// initialised here
					$testactionpath=$actionpath;
					// var_dump($testactionpath);
					for($i=0;$i<count($testactionpath);$i++){
						
						$t=$i+1;
						$placebo=$i==0?"actionpath":"actionpath$t";
						// echo $placebo." Current placebo<br>";
						// echo $testactionpath[$i]."<br>";
						// check to see if the index has a value otherwise set it to the default
						$$placebo=isset($testactionpath[$i])&&$testactionpath[$i]!==""?$testactionpath[$i]:"snippets/edit.php";
					}
				}
				
				// sort the maintype in case its an array
				if(is_array($maintype)){
					// the variables to be used for housing initial generalinfo 
					// initialised here
					$testmaintype=$maintype;
					for($i=0;$i<count($testmaintype);$i++){
						$t=$i+1;
						$placebo=$i==0?"outsdata":"outsdata$t";
						$cmaintype=$testmaintype[$i];
						// echo $cmaintype;

						$cpreinit=isset($preinit[$i])?$preinit[$i]:true;
						// echo $cpreinit;
						if($cpreinit==true){
							$$placebo=getAllGeneralInfo("admin","$cmaintype","","",$contentgroupdatageneral);
						}
						$placebo2=$i==0?"maintype":"maintype$t";
						$$placebo2=$cmaintype;
						$placebo3=$i==0?"formtruetype":"formtruetype$t";
						$$placebo3=$cmaintype."_gdunitform";

					}
				}else{
				
					// this variable is used to start of checks in the processing file
					if($preinit==true){
						$outsdata=getAllGeneralInfo("admin","$maintype","","",$contentgroupdatageneral);
					}
				}
				
				// init all the variants if the value is an array
				if(is_array($variant)){
				
					// the variables to be used for housing initial generalinfo 
					// initialised here
					$testvariant=$variant;
					for($i=0;$i<count($testvariant);$i++){
						$t=$i+1;
						$placebo=$i==0?"variant":"variant$t";
						$$placebo=$testvariant[$i];
					}
				}
				
			}else{
				echo "<div>An error occured, invalid or no <b>Process Route</b> defined.<br>
							 Or <br> 
							An error occured, invalid or no <b>'mt'</b> _gdunit json index
 					  </div>";
			}
		}else{
			echo "<div>An error occured, the error is ".$gd_data['error']."</div>";

		}
	}
}

?>