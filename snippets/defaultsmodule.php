<?php
	$outemailaddress=getAllGeneralInfo("viewer",'defaultemailaddress','');
	$outbusinesshours=getAllGeneralInfo("viewer",'businesshours','');
	$outphonenumbers=getAllGeneralInfo("viewer",'defaultphonenumbers','');
	$outmainaddress=getAllGeneralInfo("viewer",'defaultmainaddress','');
	$defaultmainaddress=$outmainaddress['vieweroutputmini'];
	$defaultemailaddress=$outemailaddress['vieweroutputmini'];
	$defaultphonenumbers=$outphonenumbers['vieweroutputmini'];
	$defaultbusinesshours=$outbusinesshours['vieweroutputmini'];
	
	// init default variables that hold information concerning data from the defaults 
	// section
	$defaultshorttext="";
	$defaultsitetrailer="";
	$defaultfootertrailer="";
	
	// get default social account info
	$defaultfacebook="##";
	$defaulttwitter="##";
	$defaultlinkedin="##";
	$defaultgoogleplus="##";
	$defaultpinterest="##";
	$defaultinstagram="##";
	$defaultskype="##";

	$outsdefault=getAllGeneralInfo("viewer",'defaultdata','');
		
	$defaultsitetrailer="";
	$defaultfootertrailer="";
	$defaultdisqusname="";
	$defaultgatrackcode="";
	if($outsdefault['numrows']>0){
		$curyear= date('Y');
		$searchar[]="[|current_date|]";
		$replacear[]=date('Y');

		$searchar[]="&#91;&#124;current_date&#124;&#93;";
		$replacear[]=date('Y');
		
		$searchar[]="../";
		$replacear[]=$host_addr;

		$defaultdata=$outsdefault['resultdataset'][0]['edresultset'];
		$defaultsitetrailer=$defaultdata['contentsubtitle']['value'];
		$defaultfootertrailer=isset($defaultdata['content_footer']['value'])?
		$defaultdata['content_footer']['value']:"";
		// for disqus code
		$defaultdisqusname=isset($defaultdata['content_disqus_name']['value'])?
		$defaultdata['content_disqus_name']['value']:"";
		// for google analytics track code
		$defaultgatrackcode=isset($defaultdata['content_ga']['value'])?
		$defaultdata['content_ga']['value']:"";
		
		if($defaultfootertrailer!==""){
			$defaultfootertrailer=str_replace($searchar, 
				$replacear, $defaultfootertrailer);
		}

		// business hours
		$defaultbusinesshours=$defaultdata['contentpost_1']['value'];

		// short text description, usually for as a social section
		$defaultshorttext=str_replace($searchar, $replacear, 
			$defaultdata['contentpost_2']['value']);
		// social urls
		$socialset=$outsdefault['resultdataset'][0]['edresultset']['group1'];
		// var_dump($socialset);
		for($i=0;$i<$socialset['groupcount'];$i++){
			if($socialset[$i]['socialname']['value']!==""){
				$cname="default".str_replace(" ", '', strtolower($socialset[$i]['socialname']['value']));
				
				$tname=$socialset[$i]['socialurl']['value']!==""?$socialset[$i]['socialurl']['value']:$$cname;
				// echo $$cname."<br>";
				if($tname!== "##"&&!strpos($tname, "http://")&&!strpos($tname, "https://")){
					$tname="http://".$tname;
				}

				$$cname=$tname;
			}
		}
	}

	// get default site logo
	// get default site details
?>