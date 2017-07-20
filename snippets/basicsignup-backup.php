<?php
include('connection.php');
$entryvariant=$_POST['entryvariant'];
$rurladmin=isset($_SESSION['rurladmin'])&&$_SESSION['rurladmin']!==""?$_SESSION['rurladmin']:"";
if($entryvariant=="createbooking"){
$orgname=mysql_real_escape_string($_POST['name']);
$orgaddress=mysql_real_escape_string($_POST['address']);
$themetitle=mysql_real_escape_string($_POST['themetitle']);
$contactperson=mysql_real_escape_string($_POST['contactperson']);
$datefrom=mysql_real_escape_string($_POST['from']);
$dateto=mysql_real_escape_string($_POST['to']);
$typeofevent=mysql_real_escape_string($_POST['eventtype']);
$language=mysql_real_escape_string($_POST['language']);
$participants=mysql_real_escape_string($_POST['expectedattendance']);
$phone1=mysql_real_escape_string($_POST['phone1']);
$phone2=mysql_real_escape_string($_POST['phone2']);
$email=mysql_real_escape_string($_POST['email']);
$additionalinfo=mysql_real_escape_string($_POST['additionalinfo']);
$datetime= date("D, d M Y H:i:s")."";
$query="INSERT INTO bookings(orgname,orgaddress,contactperson,eattendance,eventtype,language,phoneone,phonetwo,email,eventstart,eventstop,additionalinfo,datetime)VALUES(
'$orgname','$orgaddress','$contactperson','$participants','$typeofevent','$language','$phone1','$phone2','$email','$datefrom','$dateto','$additionalinfo','$datetime')";
echo $query;
$run=mysql_query($query)or die(mysql_error()." Line 23");
$message='
<html>
<head>
<title>
New Booking From Muyiwa Afolabi\'s website</title>
</title>
</head>
<body>
New Booking From Muyiwa Afolabi\'s website<br>
Submitted On:'.$datetime.'<br>
  <b>Theme/Title:</b> '.$themetitle.'<br>
  <b>Organisation Name:</b> '.$orgname.'<br>
  <b>Organisation Address:</b> '.$orgaddress.'<br>
  <b>Contact Person:</b> '.$contactperson.'<br>
  <b>Email:</b> '.$email.'<br>
<b>Event Type:</b> '.$typeofevent.'<br>
  <b>Language:</b> '.$language.'<br>
  <b>Start Period:</b> '.$datefrom.'<br>
  <b>End Period:</b> '.$dateto.'<br>
<b>Expected Attendance:</b> '.$participants.'<br>
  <b>Phone One:</b> '.$phone1.'<br>
  <b>Phone Two:</b> '.$phone2.'<br>
<b>Additional Information</b><br>
<p align="left">'.$additionalinfo.'</p>
</body>
</html>
';
$toemail="franklyspeakingwithm.afolabi@gmail.com";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: <bookings@muyiwaafolabi.com>' . "\r\n";
// $headers .= 'Cc: chichiagbenro@yahoo.com' . "\r\n";
$subject="New Booking Entry From Muyiwa Afolabi's Website";
if($host_email_send===true){
if(mail($toemail,$subject,$message,$headers)){

}else{
	die('could not send Your email, something went wrong and we are handling it, meantime you could click the back button in your browser to get you out of here, we are really sorry');
}
}
/*if(mail($email,$toemail,$message,"From: ".$email)){

}else{

}*/
header('location:../completion.php?t=booking');
}elseif($entryvariant=="createoyo"){
	$fullname=mysql_real_escape_string($_POST['name']);
	$gender=mysql_real_escape_string($_POST['gender']);
	$age=mysql_real_escape_string($_POST['age']);
	$maritalstatus=mysql_real_escape_string($_POST['maritalstatus']);
	$religion=mysql_real_escape_string($_POST['religion']);
	$religion==""?$religion="not specified":$religion=$religion;
	$otherreligion=mysql_real_escape_string($_POST['otherreligion']);
	$otherreligion==""?$otherreligion="not specified":$otherreligion=$otherreligion;
	$stateid=mysql_real_escape_string($_POST['state']);
	$stateout=produceStates(0,$stateid);
	$state=$stateout['statename'];
	$tribe=mysql_real_escape_string($_POST['tribe']);
	$lga=mysql_real_escape_string($_POST['lga']);
	$phonenumber=mysql_real_escape_string($_POST['phone1']);
	$email=mysql_real_escape_string($_POST['email']);
	$occupation=mysql_real_escape_string($_POST['occupation']);
	$qualification=mysql_real_escape_string($_POST['qualification']);
	$curempstatus=mysql_real_escape_string($_POST['curempstatus']);
	$pastworkexperience=mysql_real_escape_string($_POST['pastworkexperience']);
	$areaofbusiness=mysql_real_escape_string($_POST['areaofbusiness']);
	$starttime=mysql_real_escape_string($_POST['starttime']);
	$location=mysql_real_escape_string($_POST['location']);
	$businesstype=mysql_real_escape_string($_POST['businesstype']);
	$businessruntype=mysql_real_escape_string($_POST['businessruntype']);
	$occupation=mysql_real_escape_string($_POST['occupation']);
	$additionalinfo=mysql_real_escape_string($_POST['additionalinfo']);
	$additionalinfo==""?$additionalinfo="not specified":$additionalinfo=$additionalinfo;
	$datetime= date("D, d M Y H:i:s")."";
	/*$query="INSERT INTO bookings(orgname,orgaddress,contactperson,eattendance,eventtype,language,phoneone,phonetwo,email,eventstart,eventstop,additionalinfo,datetime)VALUES(
	'$orgname','$orgaddress','$contactperson','$participants','$typeofevent','$language','$phone1','$phone2','$email','$datefrom','$dateto','$additionalinfo','$datetime')";
	echo $query;*/
	// $run=mysql_query($query)or die(mysql_error()." Line 23");
	$message='
	<html>
	<head>
	<title>
	Own Your Own From registration From Muyiwa Afolabi\'s website</title>
	</title>
	</head>
	<body>
	Submitted On:'.$datetime.'<br>
	<b>Fullname:</b> '.$fullname.'<br>
	  <b>Gender:</b> '.$gender.'<br>
	  <b>Age:</b> '.$age.'<br>
	<b>Marital Status:</b> '.$maritalstatus.'<br>
	  <b>Religion:</b> '.$religion.'<br>
	  <b>Other Religion:</b> '.$otherreligion.'<br>
	  <b>State:</b> '.$state.'<br>
	<b>Tribe:</b> '.$tribe.'<br>
	  <b>Lga:</b> '.$lga.'<br>
	  <b>Phone Number:</b> '.$phonenumber.'<br>
	  <b>Email:</b> '.$email.'<br>
	  <b>Occupation:</b> '.$occupation.'<br>
	  <b>Qualification</b><br>
	<p align="left">'.$qualification.'</p><br>
	  <b>Current Employment Status:</b> '.$curempstatus.'<br>
	  <b>Past Work Experience:</b><br>
	 <p align="left">'.$pastworkexperience.'</p><br>
	  <b>Area Of Business:</b> '.$areaofbusiness.'<br>
	  <b>Start Off Time:</b> '.$starttime.'<br>
	  <b>Preferred Business Location:</b> '.$location.'<br>
	  <b>Nature of Business:</b> '.$businesstype.'<br>
	  <b>Business Status:</b> '.$businessruntype.'<br>
	<b>Additional Information</b><br>
	<p align="left">'.$additionalinfo.'</p>
	</body>
	</html>
	';
	$toemail="ownyourown2014@gmail.com";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: <ownyourown@muyiwaafolabi.com>' . "\r\n";
	// $headers .= 'Cc: chichiagbenro@yahoo.com' . "\r\n";
	$subject="New Own Your Own Request Entry From Muyiwa Afolabi's Website";
	if($host_email_send===true){
	if(mail($toemail,$subject,$message,$headers)){
	//response email
	$subject2="Thank You For Your Interest.";
	$message2="Thank you for your interest in our business capacity building seminar. A manager will call and speak with you soon. Thank you";	
	$headers2 = "MIME-Version: 1.0" . "\r\n";
	$headers2 .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers2 .= 'From: <no-reply@muyiwaafolabi.com>' . "\r\n";
	mail($email,$subject2,$message2,$headers2);
	}else{
		die('could not send Your email, something went wrong and we are handling it, meantime you could click the back button in your browser to get you out of here, we are really sorry');
	}
	}
	/*if(mail($email,$toemail,$message,"From: ".$email)){
		
	}else{
	
	}*/
	header('location:../completion.php?t=oyo');
}elseif($entryvariant=="createbusinessidea"){
	$fullname=mysql_real_escape_string($_POST['name']);
	$gender=mysql_real_escape_string($_POST['gender']);
	$age=mysql_real_escape_string($_POST['age']);
	$maritalstatus=mysql_real_escape_string($_POST['maritalstatus']);
	$stateid=mysql_real_escape_string($_POST['state']);
	$stateout=produceStates(0,$stateid);
	$state=$stateout['statename'];
	$phonenumber=mysql_real_escape_string($_POST['phone1']);
	$phonenumbertwo=mysql_real_escape_string($_POST['phone2']);
	$email=mysql_real_escape_string($_POST['email']);
	$occupation=mysql_real_escape_string($_POST['profession']);
	$areaofbusiness=mysql_real_escape_string($_POST['areaofbusiness']);
	$personaldream=mysql_real_escape_string($_POST['personaldream']);
	$industry=mysql_real_escape_string($_POST['industry']);
	$motive=mysql_real_escape_string($_POST['motive']);
	$otherdetails=mysql_real_escape_string($_POST['otherdetails']);
	$additionalinfo=mysql_real_escape_string($_POST['additionalinfo']);
	$additionalinfo==""?$additionalinfo="not specified":$additionalinfo=$additionalinfo;
	$datetime= date("D, d M Y H:i:s")."";
	/*$query="INSERT INTO bookings(orgname,orgaddress,contactperson,eattendance,eventtype,language,phoneone,phonetwo,email,eventstart,eventstop,additionalinfo,datetime)VALUES(
	'$orgname','$orgaddress','$contactperson','$participants','$typeofevent','$language','$phone1','$phone2','$email','$datefrom','$dateto','$additionalinfo','$datetime')";
	echo $query;*/
	// $run=mysql_query($query)or die(mysql_error()." Line 23");
	$message='
	<html>
	<head>
	<title>
		Own Your Own (Business Idea) form registration From Muyiwa Afolabi\'s website</title>
	</title>
	</head>
	<body>
		Submitted On:'.$datetime.'<br>
		<b>Fullname:</b> '.$fullname.'<br>
	  	<b>Gender:</b> '.$gender.'<br>
	  	<b>Age:</b> '.$age.'<br>
		<b>Marital Status:</b> '.$maritalstatus.'<br>
	  	<b>State:</b> '.$state.'<br>
	  	<b>City:</b> '.$City.'<br>
	  	<b>Address:</b> '.$address.'<br>
	  	<b>Phone Number:</b> '.$phonenumber.'<br>
	  	<b>Phone Number Two:</b> '.$phonenumbertwo.'<br>
	  	<b>Email:</b> '.$email.'<br>
	  	<b>Profession:</b> '.$occupation.'<br>
	  	<b>Personal Dream:</b> '.$personaldream.'<br>
	  	<b>Industry:</b> '.$industry.'<br>
	  	<b>Motive:</b> '.$motive.'<br>
	  	<b>Area Of Business:</b> '.$areaofbusiness.'<br>
		<b>Additional Information</b><br>
		<p align="left">'.$additionalinfo.'</p>
	</body>
	</html>
	';
	$toemail="ownyourown2014@gmail.com";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: <ownyourown@muyiwaafolabi.com>' . "\r\n";
	// $headers .= 'Cc: chichiagbenro@yahoo.com' . "\r\n";
	$subject="New Business Idea Request Entry From Muyiwa Afolabi's Website";
	if($host_email_send===true){
	if(mail($toemail,$subject,$message,$headers)){
		//response email
		$subject2="Thank You For Your Interest.";
		$message2="Thank you for your interest in our business capacity building seminar. A manager will call and speak with you soon. Thank you";	
		$headers2 = "MIME-Version: 1.0" . "\r\n";
		$headers2 .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers2 .= 'From: <no-reply@muyiwaafolabi.com>' . "\r\n";
		mail($email,$subject2,$message2,$headers2);
	}else{
		die('could not send Your email, something went wrong and we are handling it, meantime you could click the back button in your browser to get you out of here, we are really sorry');
	}
	}
	/*if(mail($email,$toemail,$message,"From: ".$email)){
		
	}else{
	
	}*/
	header('location:../completion.php?t=oyo');
}elseif($entryvariant=="createtrainingandempowerment"){
	$fullname=mysql_real_escape_string($_POST['name']);
	$contactperson=mysql_real_escape_string($_POST['contactperson']);
	$themetitle=mysql_real_escape_string($_POST['themetitle']);
	$stateid=mysql_real_escape_string($_POST['state']);
	$stateout=produceStates(0,$stateid);
	$state=$stateout['statename'];
	$city=mysql_real_escape_string($_POST['city']);
	$otherdetails=mysql_real_escape_string($_POST['otherdetails']);
	$phonenumber=mysql_real_escape_string($_POST['phone1']);
	$phonenmbertwo=mysql_real_escape_string($_POST['phone2']);
	$expectedattendance=mysql_real_escape_string($_POST['expectedattendance']);
	$starttime=mysql_real_escape_string($_POST['from']);
	$endtime=mysql_real_escape_string($_POST['to']);
	$businessruntype=mysql_real_escape_string($_POST['businessruntype']);
	$occupation=mysql_real_escape_string($_POST['occupation']);
	$email=mysql_real_escape_string($_POST['email']);
	$eventtype=mysql_real_escape_string($_POST['eventtype']);
	$industry=mysql_real_escape_string($_POST['industry']);
	$additionalinfo=mysql_real_escape_string($_POST['additionalinfo']);
	$additionalinfo==""?$additionalinfo="not specified":$additionalinfo=$additionalinfo;
	$datetime= date("D, d M Y H:i:s")."";
	/*$query="INSERT INTO bookings(orgname,orgaddress,contactperson,eattendance,eventtype,language,phoneone,phonetwo,email,eventstart,eventstop,additionalinfo,datetime)VALUES(
	'$orgname','$orgaddress','$contactperson','$participants','$typeofevent','$language','$phone1','$phone2','$email','$datefrom','$dateto','$additionalinfo','$datetime')";
	echo $query;*/
	// $run=mysql_query($query)or die(mysql_error()." Line 23");
	$message='
	<html>
	<head>
	<title>
		Own Your Own From registration From Muyiwa Afolabi\'s website</title>
	</title>
	</head>
	<body>
		Submitted On:'.$datetime.'<br>
		<b>Fullname:</b> '.$fullname.'<br>
	  <b>Contact Person:</b> '.$contactperson.'<br>
	  <b>Theme/Title:</b> '.$themetitle.'<br>
	  <b>Business Status:</b> '.$businessruntype.'<br>
	  <b>State:</b> '.$state.'<br>
	  <b>Phone Number:</b> '.$phonenumber.'<br>
	  <b>Phone Number Two:</b> '.$phonenumbertwo.'<br>
	  <b>Email:</b> '.$email.'<br>
	  <b>Industry:</b> '.$industry.'<br>
	  <b>Event Type:</b> '.$eventtype.'<br>
	  <b>Number of Participants:</b> '.$expectedattendance.'<br>
	  <b>Start Time:</b> '.$starttime.'<br>
	  <b>End Time:</b> '.$starttime.'<br>
		<b>Other Details</b><br>
		<p align="left">'.$otherdetails.'</p>
		<b>Additional Information</b><br>
		<p align="left">'.$additionalinfo.'</p>
	</body>
	</html>
	';
	$toemail="ownyourown2014@gmail.com";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: <ownyourown@muyiwaafolabi.com>' . "\r\n";
	// $headers .= 'Cc: chichiagbenro@yahoo.com' . "\r\n";
	$subject="New Own Your Own Request Entry From Muyiwa Afolabi's Website";
	if($host_email_send===true){
	if(mail($toemail,$subject,$message,$headers)){
	//response email
	$subject2="Thank You For Your Interest.";
	$message2="Thank you for your interest in our business capacity building seminar. A manager will call and speak with you soon. Thank you";	
	$headers2 = "MIME-Version: 1.0" . "\r\n";
	$headers2 .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers2 .= 'From: <no-reply@muyiwaafolabi.com>' . "\r\n";
	mail($email,$subject2,$message2,$headers2);
	}else{
		die('could not send Your email, something went wrong and we are handling it, meantime you could click the back button in your browser to get you out of here, we are really sorry');
	}
	}
	/*if(mail($email,$toemail,$message,"From: ".$email)){
		
	}else{
	
	}*/
	header('location:../completion.php?t=oyo');
}else if($entryvariant=="createpartnershiprequest"){
	$organisationname=mysql_real_escape_string($_POST['name']);
	$country=mysql_real_escape_string($_POST['country']);
	$city=mysql_real_escape_string($_POST['city']);
	$address=mysql_real_escape_string($_POST['address']);
	$industry=mysql_real_escape_string($_POST['industry']);
	$interest=mysql_real_escape_string($_POST['interest']);
	$productservicetype=mysql_real_escape_string($_POST['productservicetype']);
	$contactperson=mysql_real_escape_string($_POST['contactperson']);
	$designation=mysql_real_escape_string($_POST['designation']);
	$phone1=mysql_real_escape_string($_POST['phone1']);
	$phone2=mysql_real_escape_string($_POST['phone2']);
	$email=mysql_real_escape_string($_POST['email']);
	$officelocation=mysql_real_escape_string($_POST['officelocation']);
	$otherdetails=mysql_real_escape_string($_POST['otherdetails']);
	$topcontent='
		<b>Organisation Name:</b> '.$organisationname.'<br>
		<b>Country:</b> '.$country.'<br>
		<b>City</b> '.$city.'<br>
		<b>Address:</b> '.$address.'<br>
		<b>industry:</b> '.$industry.'<br>
		<b>Interest:</b> '.$interest.'<br>
		<b>Product/Service Type:</b> '.$productservicetype.'<br>
		<b>Contact Person:</b> '.$contactperson.'<br>
		<b>Designation:</b> '.$designation.'<br>
		<b>Phone One:</b> '.$phone1.'<br>
		<b>Phone Two:</b> '.$phone2.'<br>
		<b>Email:</b> '.$email.'<br>
		<b>Office Location:</b> '.$officelocation.'<br>
		<b>Additional Information</b><br>
		<p align="left">'.$otherdetails.'</p>
	';
	$title="A new International Partnership Request";
	$content='
		'.$topcontent.'
	';
	  $content=stripslashes($content);
	  $footer='
	    <!--<ul>
	        <li><strong>Phone 1: </strong>0701-682-9254</li>
	        <li><strong>Phone 2: </strong>0802-916-3891</li>
	        <li><strong>Phone 3: </strong>0803-370-7244</li>
	        <li><strong>Email: </strong><a href="mailto:info@muyiwaafolabi.com">info@muyiwaafolabi.com</a></li>
	    </ul>-->
	  ';
	  $emailout=generateMailMarkUp("muyiwaafolabi.com","$email","$title","$content","$footer","fs");
	  // // echo $emailout['rowmarkup'];
	  $toemail="franklyspeakingwithm.afolabi@gmail.com";
	  $headers = "MIME-Version: 1.0" . "\r\n";
	  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	  $headers .= 'From: <no-reply@muyiwaafolabi.com>' . "\r\n";
	  $subject="$title";
    if($host_email_send===true){
      if(mail($toemail,$subject,$emailout['rowmarkup'],$headers)){

      }else{
        die('could not send Your email, something went wrong and we are handling it, meantime you could click the back button in your browser to get you out of here, we are really sorry');
      }
    }
 	header('location:../completion.php?t=partnershiprequest');
}elseif($entryvariant=="createservicerequest"){
	$fullname=mysql_real_escape_string($_POST['name']);
	$orgname=mysql_real_escape_string($_POST['organizationname']);
	$team=mysql_real_escape_string($_POST['team']);
	$datefrom=mysql_real_escape_string($_POST['from']);
	$dateto=mysql_real_escape_string($_POST['to']);
	$typeofevent=mysql_real_escape_string($_POST['eventtype']);
	$participants=mysql_real_escape_string($_POST['expectedattendance']);
	$phone1=mysql_real_escape_string($_POST['phone1']);
	// $phone2=mysql_real_escape_string($_POST['phone2']);
	$phone2="";
	$venue=mysql_real_escape_string($_POST['venue']);
	$questioncomments=mysql_real_escape_string($_POST['questioncomments']);
	$datetime= date("D, d M Y H:i:s");
	$query="INSERT INTO servicerequest(name,organizationname,team,eventtype,startdateperiod,enddateperiod,expectedattendance,phoneone,phonetwo,venue,extrainfo,datetime)VALUES(
	'$fullname','$orgname','$team','$typeofevent','$datefrom','$dateto','$participants','$phone1','$phone2','$venue','$questioncomments','$datetime')";
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line 40");
	$message='
		<html>
		<head>
		<title>
		New Service Request From Muyiwa Afolabi\'s website</title>
		</title>
		</head>
		<body>
		New Service Request From Muyiwa Afolabi\'s website<br>
		Submitted On:'.$datetime.'<br>
		<b>Fullname:</b> '.$fullname.'<br>
		  <b>Organisation Name:</b> '.$orgname.'<br>
		  <b>Team:</b> '.$team.'<br>
		<b>Event Type:</b> '.$typeofevent.'<br>
		  <b>Start Period:</b> '.$datefrom.'<br>
		  <b>End Period:</b> '.$dateto.'<br>
		<b>Expected Attendance:</b> '.$participants.'<br>
		  <b>Phone One:</b> '.$phone1.'<br>
		  <b>Phone Two:</b> '.$phone2.'<br>
		<b>Venue:</b><br>
		<p align="left">
		'.$venue.'
		</p>
		<b>Additional Information:</b><br>
		<p align="left">'.$questioncomments.'</p>
		</body>
		</html>
	';
	$toemail="franklyspeakingwithm.afolabi@gmail.com";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: <servicerequest@muyiwaafolabi.com>' . "\r\n";
	// $headers .= 'Cc: chichiagbenro@yahoo.com' . "\r\n";
	$subject="New Service Request Entry From Muyiwa Afolabi's Website";
	if($host_email_send===true){
	if(mail($toemail,$subject,$message,$headers)){

	}else{
		die('could not send Your email, something went wrong and we are handling it, meantime you could click the back button in your browser to get you out of here, we are really sorry');
	}
	}
	header('location:../completion.php?t=servicerequest');
}elseif($entryvariant=="createtestimony"){
	$nextid=getNextId('testimony');
	$fullname=mysql_real_escape_string($_POST['name']);
	$email=mysql_real_escape_string($_POST['email']);
	$testimony=mysql_real_escape_string($_POST['testimony']);
	$date=date("D, d M Y H:i:s");
	$query="INSERT INTO testimony(fullname,email,testimony,entrydate)VALUES('$fullname','$email','$testimony','$date')";
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line 42");
	header('location:../completion.php?t=testimony');
}elseif ($entryvariant=="franklyspeakingblogsubscription") {
	# code...
	$email=mysql_real_escape_string($_POST['email']);
	$catid=1;	
	$testquery="SELECT * FROM subscriptionlist WHERE email='$email' AND blogtypeid=$catid";
	$testrun=mysql_query($testquery)or die(mysql_error()." Line 226");
	$testnumrows=mysql_num_rows($testrun);
	if($testnumrows<1){	
	$query="INSERT INTO subscriptionlist(blogtypeid,blogcatid,email)VALUES('$catid','0','$email')";
	$run=mysql_query($query)or die(mysql_error()." Line 42");
}
header('location:../blog.php');
}elseif ($entryvariant=="csiblogsubscription") {
	# code...
	$email=mysql_real_escape_string($_POST['email']);
	$catid=2;	
	$testquery="SELECT * FROM subscriptionlist WHERE email='$email' AND blogtypeid=$catid";
	$testrun=mysql_query($testquery)or die(mysql_error()." Line 226");
	$testnumrows=mysql_num_rows($testrun);
	if($testnumrows<1){	
		$query="INSERT INTO subscriptionlist(blogtypeid,blogcatid,email)VALUES('$catid','0','$email')";
		$run=mysql_query($query)or die(mysql_error()." Line 49");
		header('location:../csi.php');
	}
}elseif ($entryvariant=="csiblogsubscriptiontwo") {
	# code...
	$email=mysql_real_escape_string($_POST['email']);
	$catid=2;	
	$testquery="SELECT * FROM subscriptionlist WHERE email='$email' AND blogtypeid=$catid";
	$testrun=mysql_query($testquery)or die(mysql_error()." ".__LINE__);
	$testnumrows=mysql_num_rows($testrun);
	if($testnumrows<1){	
		$query="INSERT INTO subscriptionlist(blogtypeid,blogcatid,email)VALUES('$catid','0','$email')";
		$run=mysql_query($query)or die(mysql_error()." Line 49");
		// header('location:../csi.php');
  		echo json_encode( array( "entrystat"=>"OK","message"=>"Thank you for subscribing to the CSIO Blog, you will begin receiving emails shortly") );
	}else{
  		echo json_encode( array( "entrystat"=>"Failrepeat","message"=>"It seems you already subscribed to this blog, if you are having any problems, do contact us") );
	}
}elseif ($entryvariant=="projectfixnigeriablogsubscription") {
	# code...
	// echo "inhere"; 
	$email=mysql_real_escape_string($_POST['email']);
	$catid=3;	
$testquery="SELECT * FROM subscriptionlist WHERE email='$email' AND blogtypeid=$catid";
	$testrun=mysql_query($testquery)or die(mysql_error()." Line 226");
	$testnumrows=mysql_num_rows($testrun);
	if($testnumrows<1){	
	$query="INSERT INTO subscriptionlist(blogtypeid,blogcatid,email)VALUES('$catid','0','$email')";
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line 58");
}
	header('location:../projectfixnigeria.php');
}elseif ($entryvariant=="ownyourownblogsubscription") {
	# code...
	$email=mysql_real_escape_string($_POST['email']);
	$catid=4;	
	$testquery="SELECT * FROM subscriptionlist WHERE email='$email' AND blogtypeid=$catid";
	$testrun=mysql_query($testquery)or die(mysql_error()." Line 226");
	$testnumrows=mysql_num_rows($testrun);
	if($testnumrows<1){	
		$query="INSERT INTO subscriptionlist(blogtypeid,blogcatid,email)VALUES('$catid','0','$email')";
		$run=mysql_query($query)or die(mysql_error()." Line 42");
	}
	header('location:../ownyourown.php');
}elseif ($entryvariant=="categorysubscription") {
	# code...
	$email=mysql_real_escape_string($_POST['email']);
	$pageid=mysql_real_escape_string($_POST['pageid']);
	$catid=$_POST['categoryid'];
	$testquery="SELECT * FROM blogcategories WHERE id=$catid";
	$testrun=mysql_query($testquery)or die(mysql_error()." Line 91");
	$testnumrows=mysql_num_rows($testrun);
	if($testnumrows>0){
		$testquery="SELECT * FROM subscriptionlist WHERE email='$email' AND blogtypeid=$catid";
		$testrun=mysql_query($testquery)or die(mysql_error()." Line 226");
		$testnumrows=mysql_num_rows($testrun);
		if($testnumrows<1){	
			$query="INSERT INTO subscriptionlist(blogtypeid,blogcatid,email)VALUES('0','$catid','$email')";
			$run=mysql_query($query)or die(mysql_error()." Line 58");
			echo "Success";
		}else{
			echo "Subscribed";
		}
		// $testnumrows=mysql_num_rows($testrun);
	}else{
		header('location:../index.php');
	}
}elseif ($entryvariant=="createblogtype") {
	# code...
	$blogname=$_POST['name'];
	$pattern='/[\s]/';
	$foldername=preg_replace($pattern,"-", $blogname);
	$rssname=preg_replace($pattern,"", $blogname);
	$rssname=mysql_real_escape_string($rssname);
	$rssname=strtolower($rssname);
	$foldername=preg_replace('/[\'.\"$]/',"",$foldername);
	
	// echo $foldername;
	$blogname=mysql_real_escape_string($blogname);
	$testquery="SELECT * FROM blogtype WHERE name='$blogname' OR rssname='$rssname'";
	// echo $testquery;
	$testrun=mysql_query($testquery)or die(mysql_error()." Line 91");
	$testnumrows=mysql_num_rows($testrun);
	if($testnumrows>0){
		// echo "in here";
		if($rurladmin==""){
			header('location:../admin/adminindex.php?compid=0&type=404&v=admin&error=true');
		}else{
			header('location:'.$rurladmin.'?compid=0&t=404&v=admin&error=true');
		}
	}
	$blogdescription=mysql_real_escape_string($_POST['description']);
	$blogid=getNextId("blogtype");
	$query="INSERT INTO blogtype (name,foldername,rssname,description)VALUES('$blogname','$foldername','$rssname','$blogdescription')";
	$run=mysql_query($query)or die(mysql_error()."Line 56");
	mkdir('../blog/'.$foldername.'/',0777);
	$title=''.$blogname.' | Muyiwa Afolabi\'s Website';
$title=mysql_real_escape_string($title);
	$page=''.$blogname.'.php';
	if($blogname=="Frankly Speaking With Muyiwa Afolabi"){

	$page='blog.php';
}elseif($blogname=="Christ Society International Outreach"){
	$page='csi.php';

}elseif($blogname=="Project Fix Nigeria"){
	$page='projectfixnigeria.php';

}
	$landingpage=$host_target_addr.$page;
$rssheader='<?xml version="1.0" encoding="utf-8"?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
<channel>
<title>'.$title.'</title>
<atom:link href="'.$host_target_addr.'feeds/rss/'.$rssname.'.xml" rel="self" type="application/rss+xml"/>
<description>
'.$blogdescription.'
</description>
<link>'.$landingpage.'</link>
';
$rssfooter='</channel></rss>';
$rsscontent=$rssheader.$rssfooter;
	// $handle=fopen("../".$blogname.".php","w")or die('cant open the file');
	$handle2=fopen("../feeds/rss/".$rssname.".xml","w")or die('cant open the file');
fwrite($handle2,$rsscontent);	
	// fclose($handle);
	fclose($handle2);
		// echo $query;
	$query2="INSERT INTO rssheaders (blogtypeid,headerdetails,footerdetails)VALUES('$blogid','$rssheader','$rssfooter')";
	$run2=mysql_query($query2)or die(mysql_error()."Line 85");	
	// echo $query;
	if($rurladmin==""){

		header('location:../admin/adminindex.php?compid=1&type=0&v=admin');
	}else{
		header('location:'.$rurladmin.'?compid=1&t=0&v=admin');
	}
}elseif ($entryvariant=="createblogcategory") {
$categoryid=$_POST['categoryid'];
$blogcategory=mysql_real_escape_string($_POST['name']);
	$pattern2='/[\n\s\$!#\%\^<>@\(\),\'\"\/\%\*\{\}\&\[\]\?\_\-\+\=:;’]/';
	$rssname=preg_replace($pattern2,"", $blogcategory);
$rssname=mysql_real_escape_string($rssname);
	$rssname=$categoryid.strtolower($rssname);
$testquery="SELECT * FROM blogcategories WHERE blogtypeid=$categoryid AND catname='$blogcategory' OR blogtypeid=$categoryid AND rssname='$rssname'";
echo $testquery;
$testrun=mysql_query($testquery)or die(mysql_error()." Line 91");
$testnumrows=mysql_num_rows($testrun);
if($testnumrows>0){
	echo "in here";
	if($rurladmin==""){
		header('location:../admin/adminindex.php?compid=0&type=404&v=admin&error=true');
	}else{
		header('location:'.$rurladmin.'?compid=0&t=404&v=admin&error=true');
	}
}
$curid=getNextId("blogcategories");
$outs=getSingleBlogType($categoryid);
$subtext="";
if($outs['name']=="Project Fix Nigeria"){
$image="profpic";
$imgpath[]='../images/categoryimages/';
$imgsize[]="default";
$acceptedsize="";
$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
$len=strlen($imgouts[0]);
$imagepath=substr($imgouts[0], 1,$len);
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
//$coverpicid=getNextId("media");
//maintype variants are original, medsize, thumb for respective size image.
$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize,width,height)VALUES('$curid','blogcategory','original','image','$imagepath','$filesize','$width','$height')";
$mediarun=mysql_query($mediaquery)or die(mysql_error());
$subtext=mysql_real_escape_string($_POST['subtext']);
}
$query="INSERT INTO blogcategories (blogtypeid,catname,rssname,subtext)VALUES('$categoryid','$blogcategory','$rssname','$subtext')";
// echo $query;
$run=mysql_query($query)or die(mysql_error()."Line 97");
		$title=''.$outs['name'].' | Muyiwa Afolabi\'s Website';
$title=mysql_real_escape_string($title);

	$page=''.$outs['name'].'.php';
	$landingpage=$host_target_addr."category.php?cid=".$curid;
$rssheader='<?xml version="1.0" encoding="utf-8"?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
<channel>
<title>'.$title.'</title>
<atom:link href="'.$host_target_addr.'feeds/rss/'.$rssname.'.xml" rel="self" type="application/rss+xml"/>
<description>
Category '.$blogcategory.'
</description>
<link>'.$landingpage.'</link>
';
$rssfooter='</channel></rss>';

$rsscontent=$rssheader.$rssfooter;
	$handle2=fopen("../feeds/rss/".$rssname.".xml","w")or die('cant open the file');
	fwrite($handle2,$rsscontent);
	// fclose($handle2);
		// echo $query;
	$query2="INSERT INTO rssheaders (blogtypeid,blogcatid,headerdetails,footerdetails)VALUES('$categoryid','$curid','$rssheader','$rssfooter')";
	$run2=mysql_query($query2)or die(mysql_error()."Line 85");
	if($rurladmin==""){
		header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');
	}

}elseif($entryvariant=="createblogpost"){
	$blogtypeid=$_POST['blogtypeid'];
	//the id of the blog page when itgoes into the database
	$pageid=getNextId('blogentries');
	//getting the main blog type informatoin
	$outblog=getSingleBlogType($blogtypeid);
	$foldername=$outblog['foldername'];
	$blogcategoryid=$_POST['blogcategoryid'];
	//blog cover photo management
	$blogentrytype=$_POST['blogentrytype'];
	$profpic=$_FILES['profpic']['tmp_name'];
	if($_FILES['profpic']['tmp_name']!==""&&($blogentrytype==""||$blogentrytype=="normal")){
		$image="profpic";
		$imgpath[]='../files/';
		$imgsize[]="default";
		$acceptedsize=",200";
		if($outblog['name']=="Love Language"){
			$acceptedsize=",455";
		}
		$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
		$len=strlen($imgouts[0]);
		$imagepath=substr($imgouts[0], 1,$len);
		// get image size details
		$filedata=getFileDetails($imgouts[0],"image");
		$filesize=$filedata['size'];
		$width=$filedata['width'];
		$height=$filedata['height'];
		// echo '<img src="'.$imgouts[0].'"> '.$filesize.' '.$width.' '.$height.'';
		// get the cover photo's media table id for storage with the blog entry
		$coverid=getNextId("media");
		$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize,width,height)VALUES('$pageid','blogentry','coverphoto','image','$imagepath','$filesize','$width','$height')";
		$mediarun=mysql_query($mediaquery)or die(mysql_error());
	}

	$title=mysql_real_escape_string($_POST['title']);
	//create physical pagename for the blog by using regex to remove whitespaces and replacing it with -
	$pattern2='/[\n\$!#\%\^<>@\(\),\'\"\/\%\*\{\}\&\[\]\?\_\-\+\=:;’]/';
	$pagename=preg_replace($pattern2,"", $_POST['title']);
	$pattern='/[\s]/';
	$pagename=preg_replace($pattern,"-", $pagename);
	$pagename=stripslashes($pagename);
	if(file_exists('../blog/'.$foldername.'/'.$pagename.'.php')){
		$pagename=$pagename.$pageid;
	}
	// echo "<br>".$title."<br>";
	$introparagraph=mysql_real_escape_string($_POST['introparagraph']);
	$blogentrymain=$_POST['blogentry'];
	// echo $blogentrymain;
	$blogentry=mysql_real_escape_string($_POST['blogentry']);
	// $bannerpic=$_FILES['bannerpic']['tmp_name'];
	if($_FILES['bannerpic']['tmp_name']!==""&&$blogentrytype=="banner"){
		$introparagraph=$title;
		$image="bannerpic";
		$imgpath[0]='../files/';
		$imgpath[1]='../files/thumbnails/';
		$imgsize[0]="default";
		$imgsize[1]="660,";
		$acceptedsize="";
		$imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
		$len=strlen($imgouts[0]);
		$imagepath=substr($imgouts[0], 1,$len);
		$len2=strlen($imgouts[1]);
		$imagepath2=substr($imgouts[1], 1,$len2);
		// get image size details
		$filedata=getFileDetails($imgouts[0],"image");
		$filesize=$filedata['size'];
		$width=$filedata['width'];
		$height=$filedata['height'];
		// echo '<img src="'.$imgouts[0].'"> '.$filesize.' '.$width.' '.$height.'';
		// get the cover photo's media table id for storage with the blog entry
		$coverid=getNextId("media");
		$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,details,filesize,width,height)VALUES('$pageid','blogentry','bannerpic','image','$imagepath','$imagepath2','$filesize','$width','$height')";
		$mediarun=mysql_query($mediaquery)or die(mysql_error());
	}

	if($blogentrytype=="gallery"){
		$introparagraph=$title;
		$piccount=$_POST['piccount'];
		//echo $piccount;
		if($piccount>0){
			for($i=1;$i<=$piccount;$i++){
				$albumpic=$_FILES['defaultpic'.$i.'']['tmp_name'];
				if($albumpic!==""){
					$image="defaultpic".$i."";
					if(isset($imagepath)){
						unset($imagepath);
						unset($imagesize);
					}
					$imagepath=array();
					$imagesize=array();
					$imgpath[0]='../files/medsizes/';
					$imgpath[1]='../files/thumbnails/';
					$imgsize[0]="default";
					$imgsize[1]=",225";
					// echo count($imgsize);
					$acceptedsize="";
					$imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
					$len=strlen($imgouts[0]);
					// echo $imgouts[0]."<br>";
					$imagepath=substr($imgouts[0], 1,$len);
					$len2=strlen($imgouts[1]);
					// echo $imgouts[1]."<br>";
					$imagepath2=substr($imgouts[1], 1,$len2);
					// get image size details
					$filedata=getFileDetails($imgouts[0],"image");
					$filesize=$filedata['size'];
					$width=$filedata['width'];
					$height=$filedata['height'];
					$coverid="";
					// insert current blog gallery content into database as original image and thumbnail
					$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,details,filesize,width,height)VALUES('$pageid','blogentry','gallerypic','image','$imagepath','$imagepath2','$filesize','$width','$height')";
					$mediarun=mysql_query($mediaquery)or die(mysql_error());
				}
			}
		}
	}
	// echo "<br>".$blogentry;
	//create the rss pubDate format for rss entry
	$datetime= date("D, d M Y H:i:s")." +0100";
	$date=date("d, F Y H:i:s");
	$fullpage="$pagename.php";
	echo $fullpage;
	//create the physical page based on preobtained page name
	$pagepath='../blog/'.$foldername.'/'.$pagename.'.php';
	$handle = fopen($pagepath, 'w') or die('Cannot open file:  '.$pagepath);
	//set the new page up
	$pagesetup = '<?php 
	session_start();
	include(\'../../snippets/connection.php\');
	$outpage=blogPageCreate('.$pageid.');
	 echo $outpage[\'outputpageone\'];
	$blogdata=getSingleBlogEntry('.$pageid.');
	$newview=$blogdata[\'views\']+1;
	genericSingleUpdate("blogentries","views",$newview,"id",'.$pageid.');
	?>
	';
	fwrite($handle, $pagesetup);
	fclose($handle);
	//create blog post rss entry
	$introrssentry=str_replace("../",$host_addr,$introparagraph);
	$rssentry='<item>
	<title>'.$title.'</title>
	<link>'.$host_addr.'blog/'.$foldername.'/'.$pagename.'.php</link>
	<pubDate>'.$datetime.'</pubDate>
	<guid isPermaLink="false">'.$host_addr.'blog/?p='.$pageid.'</guid>
	<description>
	<![CDATA['.$introrssentry.']]>
	</description>
	</item>
	';
	$rssentry=mysql_real_escape_string($rssentry);
	// echo $rssentry;
	$rssquery="INSERT INTO rssentries(blogtypeid,blogcategoryid,blogentryid,rssentry)VALUES('$blogtypeid','$blogcategoryid','$pageid','$rssentry')";
	$rssrun=mysql_query($rssquery)or die(mysql_error());
	//write rss information to respective blog type(for autoposting) and blog category
	writeRssData($blogtypeid,0);
	writeRssData(0,$blogcategoryid);
	//insert the new blog entry to the database
	$pagename=mysql_real_escape_string($pagename);
	$query="INSERT INTO blogentries(blogtypeid,blogcatid,blogentrytype,title,introparagraph,blogpost,entrydate,coverphoto,pagename)VALUES('$blogtypeid','$blogcategoryid','$blogentrytype','$title','$introparagraph','$blogentry','$date','$coverid','$pagename')";
	$run=mysql_query($query)or die(mysql_error()." Line 244");
	//send new email on blog post to all subscribed users
	sendSubscriberEmail($pageid);
	//end
	if($rurladmin==""){

		header('location:../admin/adminindex.php?compid=3&t=3&v=admin');
	}else{
		header('location:'.$rurladmin.'?compid=3&t=3&v=admin');
	}
}elseif ($entryvariant=="donationentry") {
	# code...
	// collect information related to donations 
	$fullname=mysql_real_escape_string($_POST['csifullname']);
	$causeid=mysql_real_escape_string($_POST['causeid']);
	$amount=mysql_real_escape_string($_POST['price_1']);
	$nextid=getNextIdExplicit("donations");
	// insert the content into the donations table;
	$query="INSERT INTO donations(fullname,causeid,amount,date)VALUES('$fullname','$causeid','$amount',CURRENT_DATE())";
	$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
  	echo json_encode( array( "entrystat"=>"OK","entryid"=>"$nextid","fullname"=>"$fullname") );
}else if ($entryvariant=="csicontact") {
	# code...
	$fullname=mysql_real_escape_string($_POST['contactFullname']);
	$email=mysql_real_escape_string($_POST['contactEmail']);
	$phone=mysql_real_escape_string($_POST['contactPhone']);
	$mesaage=mysql_real_escape_string($_POST['contactMessage']);
	$title="Contact Message from CSI Outreach";
	  $content='
			<h2 class="h2fjc">Contact Message</h2>
			<p><b>Fullname</b>:<br> '.$fullname.'</p>
			<p><b>Phone Number</b>:<br> '.$phone.'</p>
			<p><b>Email:<b> <a href="mailto:'.$email.'">'.$email.'</a></p>		
			<p><b>Message</b>:<br> '.$message.'</p>
	  ';
	  $content=stripslashes($content);
	  $footer='
	  ';
	  $emailout=generateMailMarkUp("muyiwaafolabi.com","$email","$title","$content","$footer","fjc");
	    // // echo $emailout['rowmarkup'];
	    $toemail="csiofellowship@gmail.com";
	    $headers = "MIME-Version: 1.0" . "\r\n";
	    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	    $headers .= 'From: <no-reply@muyiwaafolabi.com>' . "\r\n";
	    $subject="CSI Outreach Contact Message";
	    if($host_email_send===true){
	      if(mail($toemail,$subject,$emailout['rowmarkup'],$headers)){
  			echo json_encode( array( "entrystat"=>"OK","message"=>"Message sent successfully") );
	      }else{
  			echo json_encode( array( "entrystat"=>"Fail","message"=>"Could not send Your message, something went wrong and we are handling it, meantime you could try sending your message again, we are really sorry","fullname"=>"$fullname") );
	        // die('could not send Your email, something went wrong and we are handling it, meantime you could click the back button in your browser to get you out of here, we are really sorry');
	      }
	    }
}else if ($entryvariant=="csimembership") {
	# code...
	$fullname=mysql_real_escape_string($_POST['fullname']);
	$gender=mysql_real_escape_string($_POST['gender']);
	$phone=mysql_real_escape_string($_POST['phonenumber']);
	$email=mysql_real_escape_string($_POST['email']);
	$maritalstatus=mysql_real_escape_string($_POST['maritalstatus']);
	$bday=mysql_real_escape_string($_POST['date']);
	$weddate=$_POST['weddate']!==""?mysql_real_escape_string($_POST['weddate']):"None specified";
	$address=mysql_real_escape_string($_POST['address']);
	$religion=mysql_real_escape_string($_POST['religion']);
	$profession=mysql_real_escape_string($_POST['profession']);
	$prayerpoint=mysql_real_escape_string($_POST['prayerpoint']);
	$hobbies=mysql_real_escape_string($_POST['hobbies']);
	$personaldream=mysql_real_escape_string($_POST['personaldream']);
	$spousedata=$_POST['spousedata']!==""?mysql_real_escape_string($_POST['spousedata']):"None specified";
	$additionalinfo=$_POST['additionalinfo']!==""?mysql_real_escape_string($_POST['additionalinfo']):"None specified";
	$childcount=mysql_real_escape_string($_POST['childcount']);
	// variable for counting number of valid children entries and for holding the entries when prepared for email
	$rcount=0;
	$childout="";

	for($i=1;$i<=$childcount;$i++){
		$childdata=mysql_real_escape_string($_POST['child'.$count.'']);
		if($childdata!==""){
			$rcount++;
			$childout.='<p><b>Child ('.$i.')</b>:<br> '.$childdata.'</p>';
		}
	}
	$childoutput='<p>Child Count: <b>'.$rcount.'<b></p> '.$childout.'';
	$title="New CSI Membership";
	  $content='
			<h2 class="h2fjc">Personal Information</h2>
			<p><b>Fullname</b>:<br> '.$fullname.'</p>
			<p><b>Gender</b>:<br> '.$gender.'</p>
			<p><b>Phone Number</b>:<br> '.$phone.'</p>
			<p><b>Email:<b> <a href="mailto:'.$email.'">'.$email.'</a></p>		
			<p><b>Marital Status</b>:<br> '.$maritalstatus.'</p>
			<p><b>Birthday:<b>'.$bday.'</p>		
			<p><b>Address:<b><br>'.$address.'</p>		
			<p><b>Church/Religion:<b>'.$religion.'</p>		
			<p><b>Personal Dream:<b>'.$personaldream.'</p>		

			<h2 class="h2fjc">Life\'s Journey</h2>
			<p><b>Profession:<b>'.$profession.'</p>		
			<p><b>Prayer Point:<b>'.$prayerpoint.'</p>		
			<p><b>Hobbies:<b><br>'.$hobbies.'</p>		

			<h2 class="h2fjc">Family life</h2>
			<p><b>Wedding Anniversary:<b>'.$weddate.'</p>		
			<p>Spouse Information: '.$spousedata.'</p>
			'.$childoutput.'
			<p><b>Additional Information:</b><br> '.$additionalinfo.'</p>
	  ';	
	  $content=stripslashes($content);
	  $footer='
	  ';
	  $emailout=generateMailMarkUp("muyiwaafolabi.com","$email","$title","$content","$footer","fjc");
	    // // echo $emailout['rowmarkup'];
	    $toemail="csiofellowship@gmail.com";
	    $headers = "MIME-Version: 1.0" . "\r\n";
	    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	    $headers .= 'From: <no-reply@muyiwaafolabi.com>' . "\r\n";
	    $subject="CSI Outreach Membership Registration";
	    if($host_email_send===true){
	      if(mail($toemail,$subject,$emailout['rowmarkup'],$headers)){
  			echo json_encode( array( "entrystat"=>"OK","message"=>"Registration completed successfully, we will contact you shortly") );
	      }else{
  			echo json_encode( array( "entrystat"=>"Fail","message"=>"Could not register you, something went wrong and we are handling it, meantime you could try sending submitting again, we are really sorry","fullname"=>"$fullname") );
	        // die('could not send Your email, something went wrong and we are handling it, meantime you could click the back button in your browser to get you out of here, we are really sorry');
	      }
	    }
}elseif($entryvariant=="createcsisermon"){
	$nextid=getNextIdExplicit('sermons');
	$title=mysql_real_escape_string($_POST['title']);
	$sermondate=mysql_real_escape_string($_POST['sermondate']);
	$intro=mysql_real_escape_string($_POST['intro']);
	$audiotype=mysql_real_escape_string($_POST['audiotype']);
	$videotype=isset($_POST['videotype'])?mysql_real_escape_string($_POST['videotype']):"";
	$audioembedcode=mysql_real_escape_string($_POST['audioembed']);
	$videoembedcode=isset($_POST['videoembed'])?mysql_real_escape_string($_POST['videoembed']):"";	
	if(isset($_FILES['profpic']['tmp_name'])&&$_FILES['profpic']['tmp_name']!==""){
		$image="profpic";
		$imgpath[]='../files/';
		$imgsize[]="default";
		$acceptedsize="400,";
		$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
		$len=strlen($imgouts[0]);
		$imagepath=substr($imgouts[0], 1,$len);
		// get image size details
		$filedata=getFileDetails($imgouts[0],"image");
		$filesize=$filedata['size'];
		$width=$filedata['width'];
		$height=$filedata['height'];
		// echo '<img src="'.$imgouts[0].'"> '.$filesize.' '.$width.' '.$height.'';
		// get the cover photo's media table id for storage with the blog entry
		$coverid=getNextId("media");
		$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize,width,height)VALUES('$nextid','sermon','coverphoto','image','$imagepath','$filesize','$width','$height')";
		$mediarun=mysql_query($mediaquery)or die(mysql_error());
	}
	if (isset($_FILES['audio']['tmp_name'])&&$_FILES['audio']['tmp_name']!=="") {
		# code...
		$outsaudio=simpleUpload('audio','../files/audio/');
		$audiofilepath=$outsaudio['filelocation'];
		$len=strlen($audiofilepath);
		$audiofilepath=substr($audiofilepath, 1,$len);
		$audiofilesize=$outsaudio['filesize'];
		$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)VALUES('$nextid','sermon','sermonaudio','audio','$audiofilepath','$audiofilesize')";
		$mediarun2=mysql_query($mediaquery2)or die(mysql_error());
	}
	if (isset($_FILES['videoflv']['tmp_name'])&&$_FILES['videoflv']['tmp_name']!=="") {
		# code...
		$outsaudio=simpleUpload('videoflv','../files/videos/');
		$audiofilepath=$outsaudio['filelocation'];
		$len=strlen($audiofilepath);
		$audiofilepath=substr($audiofilepath, 1,$len);
		$audiofilesize=$outsaudio['filesize'];
		$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)VALUES('$nextid','sermon','sermonvideo','videoflv','$audiofilepath','$audiofilesize')";
		$mediarun2=mysql_query($mediaquery2)or die(mysql_error());
	}
	if (isset($_FILES['videomp4']['tmp_name'])&&$_FILES['videomp4']['tmp_name']!=="") {
		# code...
		$outsaudio=simpleUpload('videomp4','../files/videos/');
		$audiofilepath=$outsaudio['filelocation'];
		$len=strlen($audiofilepath);
		$audiofilepath=substr($audiofilepath, 1,$len);
		$audiofilesize=$outsaudio['filesize'];
		$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)VALUES('$nextid','sermon','sermonvideo','videomp4','$audiofilepath','$audiofilesize')";
		$mediarun2=mysql_query($mediaquery2)or die(mysql_error());
	}
	if (isset($_FILES['video3gp']['tmp_name'])&&$_FILES['video3gp']['tmp_name']!=="") {
		# code...
		$outsaudio=simpleUpload('video3gp','../files/videos/');
		$audiofilepath=$outsaudio['filelocation'];
		$len=strlen($audiofilepath);
		$audiofilepath=substr($audiofilepath, 1,$len);
		$audiofilesize=$outsaudio['filesize'];
		$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)VALUES('$nextid','sermon','sermonvideo','video3gp','$audiofilepath','$audiofilesize')";
		$mediarun2=mysql_query($mediaquery2)or die(mysql_error());
	}
	$query="INSERT INTO sermons(title,intro,audiotype,audiocode,videotype,videocode,exactday,date)VALUES('$title','$intro','$audiotype','$audioembedcode','$videotype','$videoembedcode','$sermondate',CURRENT_DATE())";
	$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);	
	if($rurladmin==""){
		header('location:../admin/adminindex.php?compid=4&t=1&v=admin');
	}else{
		header('location:'.$rurladmin.'?compid=4&t=1&v=admin');
	}
}elseif ($entryvariant=="createblogcomment") {
	# code...
	$blogentryid=mysql_real_escape_string($_POST['blogentryid']);
	$name=mysql_real_escape_string($_POST['name']);
	$email=mysql_real_escape_string($_POST['email']);
	$sectester=mysql_real_escape_string($_POST['sectester']);
	$secnumber=mysql_real_escape_string($_POST['secnumber']);
	$comment=mysql_real_escape_string($_POST['comment']);
	$date=date("d, F Y H:i:s");
	$query="INSERT INTO comments(fullname,email,blogentryid,comment,datetime)VALUES('$name','$email','$blogentryid','$comment','$date')";
	if($sectester==$secnumber||$comment==""){
		$run=mysql_query($query)or die(mysql_error()." Line 244");
	}
	header('location:../blog/?p='.$blogentryid.'&c=true');
}elseif ($entryvariant=="createqotd") {
	# code...
	$nextid=getNextIdExplicit('qotd');
	$quotetype=mysql_real_escape_string($_POST['quotetype']);
	$quotedperson=mysql_real_escape_string($_POST['quotedperson']);
	$quoteoftheday=mysql_real_escape_string($_POST['quoteoftheday']);
	$query="INSERT INTO qotd(type,quote,quotedperson)VALUES('$quotetype','$quoteoftheday','$quotedperson')";
	$run=mysql_query($query)or die(mysql_error()." Line 325");
	if(isset($_FILES['profpic']['tmp_name'])&&$_FILES['profpic']['tmp_name']!==""){
		$image="profpic";
		$imgpath[]='../files/';
		$imgsize[]="default";
		$acceptedsize=",200";
		$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
		$len=strlen($imgouts[0]);
		$imagepath=substr($imgouts[0], 1,$len);
		// get image size details
		$filedata=getFileDetails($imgouts[0],"image");
		$filesize=$filedata['size'];
		$width=$filedata['width'];
		$height=$filedata['height'];
		// echo '<img src="'.$imgouts[0].'"> '.$filesize.' '.$width.' '.$height.'';
		// get the cover photo's media table id for storage with the blog entry
		$coverid=getNextId("media");
		$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize,width,height)VALUES('$nextid','qotd','coverphoto','image','$imagepath','$filesize','$width','$height')";
		$mediarun=mysql_query($mediaquery)or die(mysql_error());
	}
	if(isset($_FILES['bannerpic']['tmp_name'])&&$_FILES['bannerpic']['tmp_name']!==""){
		$image="profpic";
		$imgpath[]='../files/';
		$imgsize[]="default";
		$acceptedsize=",450";
		$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
		$len=strlen($imgouts[0]);
		$imagepath=substr($imgouts[0], 1,$len);
		// get image size details
		$filedata=getFileDetails($imgouts[0],"image");
		$filesize=$filedata['size'];
		$width=$filedata['width'];
		$height=$filedata['height'];
		// echo '<img src="'.$imgouts[0].'"> '.$filesize.' '.$width.' '.$height.'';
		// get the cover photo's media table id for storage with the blog entry
		$coverid=getNextId("media");
		$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize,width,height)VALUES('$nextid','qotd','bannerphoto','image','$imagepath','$filesize','$width','$height')";
		$mediarun=mysql_query($mediaquery)or die(mysql_error());
	}
	if($rurladmin==""){
		header('location:../admin/adminindex.php?compid=4&t=1&v=admin');
	}else{
		header('location:'.$rurladmin.'?compid=4&t=1&v=admin');
	}
}elseif ($entryvariant=="createevent") {
	# code...
	$type=mysql_real_escape_string($_POST['eventtype']);
	$eventtitle=mysql_real_escape_string($_POST['eventtitle']);
	$date=mysql_real_escape_string($_POST['dateholder']);
	$sep=explode("-",$date);
	$d=$sep[0];
	$m=(int)$sep[1];
    $m<10&&strlen($m)<2?$m="0$m":$m=$m;
	// echo (int)$sep[1]." $m"."<br>";
	$y=$sep[2];
	$datetwo="$y-$m-$d";
	$date="$d-$m-$y";
	$eventdetails=mysql_real_escape_string($_POST['eventdetails']);
	$query="INSERT INTO events(type,eventtitle,eventdetails,dateperiod,d,m,y,eventdate)VALUES('$type','$eventtitle','$eventdetails','$date','$d','$m','$y','$datetwo')";
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line 325");
	if($rurladmin==""){
		header('location:../admin/adminindex.php?compid=7&t=1&v=admin');
	}else{
		header('location:'.$rurladmin.'?compid=7&t=1&v=admin');
	}
}elseif ($entryvariant=="creategallery") {
$galleryid=getNextId("gallery");
$gallerytitle=mysql_real_escape_string($_POST['gallerytitle']);
$gallerydetails=mysql_real_escape_string($_POST['gallerydetails']);
$date=date("d, F Y H:i:s");
$piccount=$_POST['piccount'];
//echo $piccount;
if($piccount>0){
	for($i=1;$i<=$piccount;$i++){
	$albumpic=$_FILES['defaultpic'.$i.'']['tmp_name'];
if($albumpic!==""){
$image="defaultpic".$i."";
if(isset($imagepath)){
unset($imagepath);
unset($imagesize);
}
$imagepath=array();
$imagesize=array();
$imgpath[0]='../files/medsizes/';
$imgpath[1]='../files/thumbnails/';
$imgsize[0]="default";
$imgsize[1]=",107";
// echo count($imgsize);
$acceptedsize="";
$imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
$len=strlen($imgouts[0]);
// echo $imgouts[0]."<br>";
$imagepath=substr($imgouts[0], 1,$len);
$len2=strlen($imgouts[1]);
// echo $imgouts[1]."<br>";
$imagepath2=substr($imgouts[1], 1,$len2);
// get image size details
$filedata=getFileDetails($imgouts[0],"image");
$filesize=$filedata['size'];
$width=$filedata['width'];
$height=$filedata['height'];
// get the cover photo's media table id for storage with the blog entry
$mediaquery="INSERT INTO media(ownerid,ownertype,details,mediatype,location,filesize,width,height)VALUES('$galleryid','gallery','$imagepath2','image','$imagepath','$filesize','$width','$height')";
$mediarun=mysql_query($mediaquery)or die(mysql_error());
}
}
}
	$query="INSERT INTO gallery(gallerytitle,gallerydetails,entrydate)VALUES('$gallerytitle','$gallerydetails','$date')";
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line 325");
	// header('location:../admin/adminindex.php?compid=5&t=1&v=admin');
	if($rurladmin==""){
		header('location:../admin/adminindex.php?compid=5&t=1&v=admin');
	}else{
		header('location:'.$rurladmin.'?compid=5&t=1&v=admin');
	}
}elseif ($entryvariant=="createtrendingtopic") {
	$trendid=getNextId("trendingtopics");
	$details=mysql_real_escape_string($_POST['name']);
	$image="profpic";
	$imgpath[0]='../files/';
	$imgsize[0]="default";
	$acceptedsize=",150";
	$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
	$len=strlen($imgouts[0]);
	$imagepath=substr($imgouts[0], 1,$len);
	// get image size details
	$filedata=getFileDetails($imgouts[0],"image");
	$filesize=$filedata['size'];
	$width=$filedata['width'];
	$height=$filedata['height'];
	// echo '<img src="'.$imgouts[0].'"> '.$filesize.' '.$width.' '.$height.'';
	// get the cover photo's media table id for storage with the blog entry
	$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize,width,height)VALUES('$trendid','trendingtopic','coverphoto','image','$imagepath','$filesize','$width','$height')";
	$mediarun=mysql_query($mediaquery)or die(mysql_error());
	# code...

	$query="INSERT INTO trendingtopics(details)VALUES('$details')";
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line 325");
	// header('location:../admin/adminindex.php?compid=9&t=1&v=admin');
	if($rurladmin==""){
		header('location:../admin/adminindex.php?compid=9&t=1&v=admin');
	}else{
		header('location:'.$rurladmin.'?compid=9&t=1&v=admin');
	}
}elseif ($entryvariant=="createtoptenplaylist") {
	# code...
	$toptenid=getNextId("toptenplaylist");
	$image="profpic";
	$imgpath[0]='../files/';
	$imgsize[0]="default";
	$acceptedsize=",150";
	$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
	$len=strlen($imgouts[0]);
	$imagepath=substr($imgouts[0], 1,$len);
	// get image size details
	$filedata=getFileDetails($imgouts[0],"image");
	$filesize=$filedata['size'];
	$width=$filedata['width'];
	$height=$filedata['height'];
	// echo '<img src="'.$imgouts[0].'"> '.$filesize.' '.$width.' '.$height.'';
	// get the cover photo's media table id for storage with the blog entry
	$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize,width,height)VALUES('$toptenid','toptenplaylist','coverphoto','image','$imagepath','$filesize','$width','$height')";
	$mediarun=mysql_query($mediaquery)or die(mysql_error());
		# code...
	$outsaudio=simpleUpload('music','../files/audio/');
	$audiofilepath=$outsaudio['filelocation'];
	$len=strlen($audiofilepath);
	$audiofilepath=substr($audiofilepath, 1,$len);
	$audiofilesize=$outsaudio['filesize'];
	$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)VALUES('$toptenid','toptenplaylist','musicfile','audio','$audiofilepath','$audiofilesize')";
	$mediarun2=mysql_query($mediaquery2)or die(mysql_error());
	$title=mysql_real_escape_string($_POST['title']);
	$artist=mysql_real_escape_string($_POST['artist']);
	// echo $title.$artist;
	$query="INSERT INTO toptenplaylist(title,artist)VALUES('$title','$artist')";
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line 437");
	// header('location:../admin/adminindex.php?compid=10&t=1&v=admin');
	if($rurladmin==""){
		header('location:../admin/adminindex.php?compid=10&t=1&v=admin');
	}else{
		header('location:'.$rurladmin.'?compid=10&t=1&v=admin');
	}
}elseif ($entryvariant=="createtopaudio") {
	# code...
	$topaudioid=getNextId("topaudio");
	# code...
$outsaudio=simpleUpload('music','../files/audio/');
$audiofilepath=$outsaudio['filelocation'];
$len=strlen($audiofilepath);
$audiofilepath=substr($audiofilepath, 1,$len);
$audiofilesize=$outsaudio['filesize'];
$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)VALUES('$topaudioid','topaudio','musicfile','audio','$audiofilepath','$audiofilesize')";
$mediarun2=mysql_query($mediaquery2)or die(mysql_error());
$title=mysql_real_escape_string($_POST['title']);
// echo $title.$artist;
	$date= date("Y-m-d")."";
	$query="INSERT INTO topaudio(title,entrydate)VALUES('$title','$date')";
	// echo $query;
	$run=mysql_query($query)or die(mysql_error()." Line 757");
	// header('location:../admin/adminindex.php?compid=10&t=1&v=admin');
	if($rurladmin==""){
		header('location:../admin/adminindex.php?compid=10&t=1&v=admin');
	}else{
		header('location:'.$rurladmin.'?compid=10&t=1&v=admin');
	}
}elseif ($entryvariant=="createadvert") {
	# code...
	echo "in here";
$advertid=getNextId("adverts");	
$advertpage=mysql_real_escape_string($_POST['advertpage']);
$adverttype=mysql_real_escape_string($_POST['adverttype']);
$adverttitle=mysql_real_escape_string($_POST['adverttitle']);
$advertowner=mysql_real_escape_string($_POST['advertowner']);
if($adverttype=="banneradvert"||$adverttype=="miniadvert"){
$image="profpic";
$adverttype=="banneradvert"?$imgpath[0]="../images/adverts/banners/":$imgpath[0]="../images/adverts/miniadverts/";
$imgsize[0]="default";
$adverttype=="banneradvert"?$acceptedsize=",250":$acceptedsize=",200";
$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
$len=strlen($imgouts[0]);
$imagepath=substr($imgouts[0], 1,$len);
// get image size details
$filedata=getFileDetails($imgouts[0],"image");
$filesize=$filedata['size'];
$width=$filedata['width'];
$height=$filedata['height'];
// echo '<img src="'.$imgouts[0].'"> '.$filesize.' '.$width.' '.$height.'';
// get the cover photo's media table id for storage with the blog entry
$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize,width,height)VALUES('$advertid','advert','$adverttype','image','$imagepath','$filesize','$width','$height')";
$mediarun=mysql_query($mediaquery)or die(mysql_error());
}else if($adverttype=="videoadvert"){
$outsvideo=simpleUpload('profpic','../files/video/');
$videofilepath=$outsvideo['filelocation'];
$len=strlen($videofilepath);
$videofilepath=substr($videofilepath, 1,$len);
$videofilesize=$outsvideo['filesize'];
$mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize)VALUES('$advertid','advert','video','video','$videofilepath','$videofilesize')";
$mediarun2=mysql_query($mediaquery2)or die(mysql_error());
}

$advertlandingpage=mysql_real_escape_string($_POST['advertlandingpage']);
$query="INSERT INTO adverts(owner,landingpage,type,title,activepage)VALUES('$advertowner','$advertlandingpage','$adverttype','$adverttitle','$advertpage')";
// echo $query;
$run=mysql_query($query)or die(mysql_error()." Line 437");
// header('location:../admin/adminindex.php?compid=13&t=1&v=admin');
	if($rurladmin==""){
	 	header('location:../admin/adminindex.php?compid=13&t=1&v=admin');
	}else{
		header('location:'.$rurladmin.'?compid=10&t=1&v=admin');
	}
}elseif($entryvariant=="createstoreaudio"){
    $entryid=getNextId("onlinestoreentries");
    $type=mysql_real_escape_string($_POST['type']);
    $title=mysql_real_escape_string($_POST['title']);
    $minititle=mysql_real_escape_string($_POST['minititle']);
    $minidescription=mysql_real_escape_string($_POST['minidescription']);
    $price=mysql_real_escape_string($_POST['price']);
    if(isset($_FILES['audio']['tmp_name'])&&$_FILES['audio']['tmp_name']!==""){ 
      require_once 'phpmp3.php';
      $defstrt=20;
      $defstop=300;
      $pstrt=$_POST['prevstart'];
      $pstop=$_POST['prevstop'];
      $prevstartprev=$_POST['prevstart'];
      $prevstopprev=$_POST['prevstop'];
      if(isset($_POST['prevstart'])&&isset($_POST['prevstop'])){
        if($_POST['prevstart']!==""){
          $pstrt=explode(":",$pstrt);
          $pmantis=$pstrt[0]*60;
          isset($pstrt[0])&&$pstrt[0]>=0?$pmantis=$pstrt[0]*60:$pmantis=20;
          isset($pstrt[1])&&$pstrt[1]!==""?$pmantis2=$pstrt[1]:$pmantis2=0;
          $pstrt=$pmantis+$pmantis2;
        }
        if($_POST['prevstop']!==""){
          $pstop=explode(":",$pstop);
          isset($pstop[0])&&$pstop[0]>=0?$pmantis=$pstop[0]*60:$pmantis=300;
          isset($pstop[1])&&$pstop[1]!==""?$pmantis2=$pstop[1]:$pmantis2=0;
          $pstop=$pmantis+$pmantis2;
        }
        $pstrt>$pstop?$defstop=$pstrt:$defstop=$pstop;
        $pstrt<$pstop?$defstart=$pstrt:$defstart=$pstop;
        // $defstop=$defstop-$defstart;
      }
          // echo "i here $defstart - $defstop";
      $defstop=$defstop-$defstart;

      $outsaudio=simpleUpload('audio','../files/audio/');
      $filerealname=$_FILES['audio']['name'];
      $teepy=explode(".",$_FILES['audio']['name']);
      $step="";
      for ($t=0;$t<count($teepy);$t++) {
        # code...
        if($t!==count($teepy)-1&&$t>0){
          $step.=$teepy[$t];
        }

      }
      // write album art to mp3 files
      if($type==1){
        $album="Frankly Speaking With Muyiwa Afolabi";
        $albumart='../images/frontierslogoalbumart.jpg';
      }else if ($type==2) {
        # code...
        $album="Christ Society International Outreach";
        $albumart='../images/csi.png';

      }else if($type==3){
        $album="The Ultimate State Of Mind";
        $albumart='../images/frontierslogoalbumart.jpg';
      }
      // echo "<br> tco: $tco<br> teepy2: $teepy[0]";
      $filerealname=$teepy[0].$step;
      // $filerealname=$teepy[0];
      $audiofilepath=$outsaudio['filelocation'];
      $mp3 = new PHPMP3($audiofilepath);
      $mp3=$mp3->extract($defstart, $defstop);
      $mp3->save('../files/audio/'.$filerealname.''.md5($entryid).'.mp3');
      $audiofilepath2='../files/audio/'.$filerealname.''.md5($entryid).'.mp3';
      require_once("../getid3/getid3.php");
      $TaggingFormat = 'UTF-8';
      // Initialize getID3 engine
      $getID3 = new getID3;
      $getID3->setOption(array('encoding'=>$TaggingFormat));
      $Filename = (isset($_REQUEST['Filename']) ? $_REQUEST['Filename'] : $audiofilepath);
      $TagFormatsToWrite = (isset($_POST['TagFormatsToWrite']) ? $_POST['TagFormatsToWrite'] : array());
      $TagFormatsToWrite['Tags']="id3v2.4";
      getid3_lib::IncludeDependency(GETID3_INCLUDEPATH.'write.php', __FILE__, true);
      $tagwriter = new getid3_writetags;
      $tagwriter->filename       = $Filename;
      $tagwriter->tagformats     = $TagFormatsToWrite;
      $tagwriter->overwrite_tags = true;
      $tagwriter->tag_encoding   = $TaggingFormat;
      $TagData=array();
      $commonkeysarray = array('Title', 'Artist', 'Album', 'Year', 'Comment');
      $TagData['title'][] = "$title";
      $TagData['artist'][] = "Muyiwa Afolabi";
      $TagData['album'][] = "$album";
      $TagData['year'][] = "2015";
      $TagData['comment'][] = "";
      $TagData['genre'][] = "MOTIVATION";
      $TagData['genre'][] = "Inspirational";
      $TagData['track'][] = isset($_POST['Track'])&&$_POST['Track'].(!empty($_POST['TracksTotal']) ? '/'.$_POST['TracksTotal'] : '01');
  
      if (!empty($audiofilepath)) {
        if (in_array('id3v2.4', $tagwriter->tagformats) || in_array('id3v2.3', $tagwriter->tagformats) || in_array('id3v2.2', $tagwriter->tagformats)|| in_array('id3v2', $tagwriter->tagformats)||in_array('id3v2.0', $tagwriter->tagformats)) {
          if (file_exists($audiofilepath)) {
            ob_start();
            if ($fd = fopen(''.$albumart.'', 'rb')) {
              ob_end_clean();
              $APICdata = fread($fd, filesize(''.$albumart.''));
              fclose ($fd);
              list($APIC_width, $APIC_height, $APIC_imageTypeID) = GetImageSize(''.$albumart.'');
              $imagetypes = array(1=>'gif', 2=>'jpeg', 3=>'png');
              if (isset($imagetypes[$APIC_imageTypeID])) {
                $TagData['attached_picture'][0]['data']          = $APICdata;
                $TagData['attached_picture'][0]['picturetypeid'] = "2";
                $TagData['attached_picture'][0]['description']   = "Cover Art";
                $TagData['attached_picture'][0]['mime']          = 'image/'.$imagetypes[$APIC_imageTypeID];
              } else {
                echo '<b>invalid image format (only GIF, JPEG, PNG)</b><br>';
              }
            } else {
              $errormessage = ob_get_contents();
              ob_end_clean();
              echo '<b>cannot open '.$audiofilepath.'</b><br>';
            }
          } else {
            echo '<b>!is_uploaded_file('.$audiofilepath.')</b><br>';
          }
        } else {
          echo '<b>WARNING:</b> Can only embed images for ID3v2<br>';
        }
      }

      $tagwriter->tag_data = $TagData;
      if ($tagwriter->WriteTags()) {
        echo 'Successfully wrote tags<BR>';
        if (!empty($tagwriter->warnings)) {
          echo 'There were some warnings:<BLOCKQUOTE STYLE="background-color:#FFCC33; padding: 10px;">'.implode('<br><br>', $tagwriter->warnings).'</BLOCKQUOTE>';
        }
      } else {
        echo 'Failed to write tags!<BLOCKQUOTE STYLE="background-color:#FF9999; padding: 10px;">'.implode('<br><br>', $tagwriter->errors).'</BLOCKQUOTE>';
      }
      /*end*/
      $len=strlen($audiofilepath);
      $audiofilepath=substr($audiofilepath, 1,$len);
      //mini audio file
      $len2=strlen($audiofilepath2);
      $audiofilepath2=substr($audiofilepath2, 1,$len2);
      $audiofilesize=$outsaudio['filesize'];
      $aid=getNextIdExplicit("media");
      $mediaquery2="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,details,filesize)VALUES('$entryid','storeaudio','audiofile','audio','$audiofilepath','$audiofilepath2','$audiofilesize')";
      // echo $mediaquery2;
      $mediarun2=mysql_query($mediaquery2)or die(mysql_error());

    }
    if(isset($_FILES['profpic']['tmp_name'])&&$_FILES['profpic']['tmp_name']!==""){ 
      $image="profpic";
      $imgpath[0]='../files/';
      $imgsize[0]="default";
      $acceptedsize=",250";
      $imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
      $len=strlen($imgouts[0]);
      $imagepath=substr($imgouts[0], 1,$len);
      // get image size details
      $filedata=getFileDetails($imgouts[0],"image");
      $filesize=$filedata['size'];
      $width=$filedata['width'];
      $height=$filedata['height'];
      $coverid=getNextIdExplicit("media");
      $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize,width,height)VALUES('$entryid','storeaudio','','image','$imagepath','$filesize','$width','$height')";
      // echo $mediaquery;
      $mediarun=mysql_query($mediaquery)or die(mysql_error());
    }
    // echo $title.$artist;
    $date= date("Y-m-d")."";
    $query="INSERT INTO onlinestoreentries(typeid,title,minititle,minidescription,price,aid,coverid,starttime,stoptime,entrydate)VALUES('$type','$title','$minititle','$minidescription','$price','$aid','$coverid','$prevstartprev','$prevstopprev','$date')";
    // echo $query;
    $run=mysql_query($query)or die(mysql_error()." Line 757");
    // header('location:../admin/adminindex.php?compid=10&t=1&v=admin');
    if($rurladmin==""){
	 	header('location:../admin/adminindex.php?compid=10&t=1&v=admin');
	}else{
		header('location:'.$rurladmin.'?compid=10&t=1&v=admin');
	}
}else if ($entryvariant=="createrecruit") {
	# code...
	$uid=getNextIdExplicit("recruits");
	$uhash=md5($uid);
	$firstname=mysql_real_escape_string($_POST['firstname']);
	$middlename=mysql_real_escape_string($_POST['middlename']);
	$lastname=mysql_real_escape_string($_POST['lastname']);
	$fullname=$firstname." ".$middlename." ".$lastname;
	$state=mysql_real_escape_string($_POST['recruitstate']);
	$doby=mysql_real_escape_string($_POST['dobyear']);
	$dobm=mysql_real_escape_string($_POST['dobmonth']);
	$dobd=mysql_real_escape_string($_POST['dobday']);
	$dob=$doby."-".$dobm."-".$dobd;
	$maritalstatus=mysql_real_escape_string($_POST['maritalstatus']);
	$gender=mysql_real_escape_string($_POST['gender']);
	$addressone=mysql_real_escape_string($_POST['addressone']);
	$careerambition=mysql_real_escape_string($_POST['careerambition']);
	$preferredjobtype=mysql_real_escape_string($_POST['preferredjobtype']);
	$preferredjoblocation=mysql_real_escape_string($_POST['preferredjoblocation']);
	$hobbies=mysql_real_escape_string($_POST['hobbies']);
	$skills=mysql_real_escape_string($_POST['skills']);
	$address=mysql_real_escape_string($_POST['addressone']);
	$phoneone=mysql_real_escape_string($_POST['phoneone']);

	$phonetwo=mysql_real_escape_string($_POST['phonetwo']);

	$phonethree=mysql_real_escape_string($_POST['phonethree']);

	$phoneout=$phoneone."[|><|]".$phonetwo."[|><|]".$phonethree;

	$email=mysql_real_escape_string($_POST['useremail']);
	$topcontent="
		<p>Fullname: <b>$fullname</b></p>
		<p>Gender: $gender</p>
		<p>Date Of Birth: $dob</p>
		<p>State: $maritalstatus</p>
		<p>Address:<br> $address</p>
		<p>Preferred Job Type: $preferredjobtype</p>
		<p>Preferred Job Location: $preferredjoblocation</p>
		<p>Phonenumber(s): $phoneone $phonetwo $phonethree</p>
		<p>Email: <a href='mailto:$email'>$email</a></p>		
	";
	$bizlogo=$_FILES['profpic']['tmp_name'];
	if($bizlogo!==""){
		$image="profpic";
		$imgpath[]='../files/medsizes/';
		$imgsize[]="default";
		$acceptedsize=",240";
		$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
		$len=strlen($imgouts[0]);
		$imagepath=substr($imgouts[0], 1,$len);
		// get image size details
		list($width,$height)=getimagesize($imgouts[0]);
		$imagesize=$_FILES[''.$image.'']['size'];
		$filesize=$imagesize/1024;
		//// echo $filefirstsize;
		$filesize=round($filesize, 0, PHP_ROUND_HALF_UP);
		if(strlen($filesize)>3){
		$filesize=$filesize/1024;
		$filesize=round($filesize,2);	
		$filesize="".$filesize."MB";
		}else{
		$filesize="".$filesize."KB";
		}
		
		//maintype variants are original, medsize, thumb for respective size image.
		$mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,filesize,width,height)VALUES('$uid','recruit','profpic','image','$imagepath','$filesize','$width','$height')";
		// echo "$mediaquery<br>";
		$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 722");
	}
	  // for holding professional and educational output for the email
	  $educationalout='<h2 class="h2fjc">Qualifications</h2>';
	  $workhistoryout='<h2 class="h2fjc">Work History</h2>';
	  $entrydate=date("Y-m-d");
	  /*Educational and professional history data collection point*/
		$educationcount=mysql_real_escape_string($_POST['curqualificationcount']);
		// $type="educational";
		for($i=1;$i<=$educationcount;$i++){
			$qualificationtype=mysql_real_escape_string($_POST['qualificationtype'.$i.'']);
			$equalification=mysql_real_escape_string($_POST['qualification'.$i.'']);
			$einstitution=mysql_real_escape_string($_POST['institution'.$i.'']);
			$grade=$_POST['grade'.$i.'']!==""?mysql_real_escape_string($_POST['grade'.$i.'']):"N/A";
			$year=mysql_real_escape_string($_POST['oyear'.$i.'']);
			$attachid=0;
			if($year!==""&&$einstitution!==""&&$equalification!==""){
				$query="INSERT INTO recruitacademicprohistory (recruitid,type,year,institution,qualification,date,grade)
				VALUES('$uid','$qualificationtype','$year','$einstitution','$equalification','$entrydate','$grade')";
				// echo $query."<br>";
				$run=mysql_query($query)or die(mysql_error()."Line 627");
				$educationalout.='
					<div class="paddsection">
	    				<p>Type: '.$qualificationtype.'</p>
	    				<p>Qualification: '.$equalification.'</p>
		            	<p>Institution: '.$einstitution.'</p>
	    				<p>Grade: '.$grade.'</p>
						<p>Year: '.$year.'</p>
					</div>
				';
				/*<p>Qualification Type: </p>
					<p>Qualification: <b></b></p>
					<p>Institution: </p>
					<p>Grade: ;</p>
					<p>Year: ;<br><br></p>*/
			}
		}
		// $educationalout.="</table>";
	  /*end*/
	  /*Collect employment records*/
		$employmentcount=mysql_real_escape_string($_POST['curworkexperiencecount']);
		for($i=1;$i<=$employmentcount;$i++){
			$companyname=mysql_real_escape_string($_POST['companyname'.$i.'']);
			$field=mysql_real_escape_string($_POST['field'.$i.'']);
			$jobposition=mysql_real_escape_string($_POST['jobposition'.$i.'']);
			$from=mysql_real_escape_string($_POST['jobfrom'.$i.'']);	
			$to=mysql_real_escape_string($_POST['jobto'.$i.'']);
			if($companyname!==""/*&&$businessaddress!==""*/&&$jobposition!==""/*&&$rfl!==""&&$remuneration!==""*/&&$from!==""/*&&$to!==""*/){
				$query="INSERT INTO recruitemploymenthistory (recruitid,companyname,field,lastjobtitle,fromdate,todate)
				VALUES('$uid','$companyname','$field','$jobposition','$from','$to')";
				// echo $query."<br>";
				$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
				$to=$_POST['jobto'.$i.'']!==""?mysql_real_escape_string($_POST['jobto'.$i.'']):"Ongoing";
				$workhistoryout.="
					<p>Company Name: $companyname;</p>
					<p>Field/Industry: $field;</p>
					<p>Job Position: $jobposition</p>
					<p>From: $from;</p>
					<p>To: $to;</p>

				";
			}
		}
		/*end*/
	  if(isset($_POST['upassword'])){
	    $password=$_POST['upassword'];
	  }else{
	    $password=substr(md5(date("Y d m").time()),0,9);
	  }
	  // $pinpage=isset($_POST['surveyid'])?mysql_real_escape_string($_POST['surveyid']):0;
	  // $deadline=date('Y-m-d', strtotime('7 days'));
	  $_SESSION['firstlog']="true";
	  $_SESSION['userh']=$uhash;
	  $_SESSION['useri']=$uid;
	  $query="INSERT INTO recruits(fullname,uhash,address,careerambition,gender,maritalstatus,dob,state,email,pword,phonenumbers,regdate,preferredjobtype,preferredjoblocation,hobbies,skills)
	  VALUES('$fullname','$uhash','$addressone','$careerambition','$gender','$maritalstatus','$dob','$state','$email','$password','$phoneout',CURRENT_DATE(),'$preferredjobtype','$preferredjoblocation','$hobbies','$skills')";
	  // echo $query;
	  $run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
	  // $confirmationlink=$host_addr."signupin.php?t=activate&uh=".$uhash.".".$uid."&utm_email=".$toemail."";
	  $title="A new Frontiers Job-Connect Recruit Submission";
	  $content='
			<h2 class="h2fjc">Personal Information</h2>
			<img class="passportclass" src="'.$host_addr.''.$imagepath.'"/>
			'.$topcontent.'
			'.$educationalout.'
			'.$workhistoryout.'
			<h2 class="h2fjc">Other Information</h2>
			<p><b>Career Ambition</b>:<br> '.$careerambition.'</p>
			<p><b>Soft Skills</b>:<br> '.$skills.'</p>
			<p><b>Hobbies</b>:<br> '.$hobbies.'</p>
			<p>Email: <a href="mailto:'.$email.'">'.$email.'</a></p>		
	  ';
	  $content=stripslashes($content);
	  $footer='
	    <!--<ul>
	        <li><strong>Phone 1: </strong>0701-682-9254</li>
	        <li><strong>Phone 2: </strong>0802-916-3891</li>
	        <li><strong>Phone 3: </strong>0803-370-7244</li>
	        <li><strong>Email: </strong><a href="mailto:info@muyiwaafolabi.com">info@muyiwaafolabi.com</a></li>
	    </ul>-->
	  ';
	  $emailout=generateMailMarkUp("muyiwaafolabi.com","$email","$title","$content","$footer","fjc");
	    // // echo $emailout['rowmarkup'];
	    $toemail="frontiersjobconnect@gmail.com";
	    $headers = "MIME-Version: 1.0" . "\r\n";
	    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	    $headers .= 'From: <no-reply@frontiersjobconnect.com>' . "\r\n";
	    $subject="Frontiers Job-Connect Recruit Registration";
	    if($host_email_send===true){
	      if(mail($toemail,$subject,$emailout['rowmarkup'],$headers)){

	      }else{
	        die('could not send Your email, something went wrong and we are handling it, meantime you could click the back button in your browser to get you out of here, we are really sorry');
	      }
	    }
    // echo $emailout['rowmarkup'];
	    header('location:'.$host_addr.'fjcsuccess.php?ctype=recruit');
}else if($entryvariant=="createjobpost"){
	$orgname=mysql_real_escape_string($_POST['orgname']);
	$fullname=mysql_real_escape_string($_POST['fullname']);
	$cposition=mysql_real_escape_string($_POST['cposition']);
	$employerstate=mysql_real_escape_string($_POST['employerstate']);
	$address=mysql_real_escape_string($_POST['eaddressone']);
	$email=mysql_real_escape_string($_POST['employeremail']);
	$epasswordconfirm=isset($_POST['epasswordconfirm'])?mysql_real_escape_string($_POST['epasswordconfirm']):"";
	$phoneone=mysql_real_escape_string($_POST['phoneone']);
	if(isset($_POST['epassword'])){
	    $password=$epassword;
	  }else{
	    $password=substr(md5(date("Y d m").time()),0,9);
	  }
	$phoneone=mysql_real_escape_string($_POST['phoneone']);
	$phonetwo=mysql_real_escape_string($_POST['phonetwo']);
	$phonethree=mysql_real_escape_string($_POST['phonethree']);
	$phoneout=$phoneone." ".$phonetwo." ".$phonethree;
	$jobfield=mysql_real_escape_string($_POST['jobfield']);
	$positionavailable=mysql_real_escape_string($_POST['positionavailable']);
	$termsofemployment=mysql_real_escape_string($_POST['termsofemployment']);
	$recruitsneeded=mysql_real_escape_string($_POST['recruitsneeded']);
	$remuneration=mysql_real_escape_string($_POST['remuneration']);
	$minqualification=mysql_real_escape_string($_POST['minqualification']);
	$pedate=mysql_real_escape_string($_POST['pedate']);
	$additionalinfo=mysql_real_escape_string($_POST['additionalinfo']);
	$pragemin=mysql_real_escape_string($_POST['pragemin']);
	$pragemax=mysql_real_escape_string($_POST['pragemax']);
	$pragemin==$pragemax?$prageout=$pragemin:$prageout="$pragemin - $pragemax";
	$pwemin=mysql_real_escape_string($_POST['pwemin']);
	$pwemax=mysql_real_escape_string($_POST['pwemax']);
	$pwemin==$pwemax?$pweout=$pwemin:$pweout="$pwemin - $pwemax";
	$pwejmin=mysql_real_escape_string($_POST['pwejmin']);
	$pwejmax=mysql_real_escape_string($_POST['pwejmax']);
	$pwejmin==$pwejmax?$pwejout=$pwejmin:$pwejout="$pwejmin - $pwejmax";
	$prefgender=mysql_real_escape_string($_POST['prefgender']);
	$hobbies=mysql_real_escape_string($_POST['hobbies']);
	$skills=mysql_real_escape_string($_POST['helpfullskills']);
	 $query="INSERT INTO jobposts(orgname,orgaddress,state,contactperson,contactorgposition,email,contactphone,field,positionavailable,slotsavailable,minage,maxage,minimumqualification,workexperience,jobworkexperience,pedate,termofemployment,restimate,additionalinfo,softskills,hobbies,regdate)
	  VALUES('$orgname','$address','$employerstate','$fullname','$cposition','$email','$phoneout','$jobfield','$positionavailable','$recruitsneeded','$pragemin','$pragemax','$minqualification','$pweout','$pwejout','$pedate','$termsofemployment','$remuneration','$additionalinfo','$skills','$hobbies',CURRENT_DATE())";
	  // echo $query;
	  $run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
	  // $confirmationlink=$host_addr."signupin.php?t=activate&uh=".$uhash.".".$uid."&utm_email=".$toemail."";
	  $title="A new Frontiers Job-Connect Job Post";
	  $content='
			<h2 class="h2fjc">Employer Information</h2>
			<p>Organisation Name: <b>'.$orgname.'</b></p>
			<p>Organisation Contact Person: <b>'.$fullname.'</b></p>
			<p>Contact Phonenumber(s): <b>'.$phoneout.'</b></p>
			<p>Contact Position: <b>'.$cposition.'</b></p>
			<p>Organisation State: <b>'.$employerstate.'</b></p>
			<p>Organisation Address: <b>'.$address.'</b></p>

			<h2 class="h2fjc">Job Information</h2>
			<p>Field/industry: <b>'.$jobfield.'</b></p>
			<p>Job Position: <b>'.$positionavailable.'</b></p>
			<p>Employment Term: <b>'.$termsofemployment.'</b></p>
			<p>Recruits Needed: <b>'.$recruitsneeded.'</b></p>
			<p>Remuneration: <b>&#8358;'.$remuneration.'</b></p>
			<p><b>Minimum Qualification</b>:<br> <b>'.$minqualification.'</b></p>
			<p>Additional Information:<br>'.$additionalinfo.'</p>
			<p>Possible Employment Date: <b>'.$pedate.'</b></p>
			
			<h2 class="h2fjc">Employer Preference</h2>
			<p><b>Preferred Recruit Age</b>:<br> '.$prageout.'</p>
			<p><b>Preferred Recruit Gender</b>:<br> '.$prefgender.'</p>
			<p><b>Industry work Experience</b>:<br> '.$pweout.'</p>
			<p><b>Job work Experience</b>:<br> '.$pwejout.'</p>
			<p><b>Soft Skills</b>:<br> '.$skills.'</p>
			<p><b>Hobbies</b>:<br> '.$hobbies.'</p>
	  ';
	  $content=stripslashes($content);
	  $footer='
	    <!--<ul>
	        <li><strong>Phone 1: </strong>0701-682-9254</li>
	        <li><strong>Phone 2: </strong>0802-916-3891</li>
	        <li><strong>Phone 3: </strong>0803-370-7244</li>
	        <li><strong>Email: </strong><a href="mailto:info@muyiwaafolabi.com">info@muyiwaafolabi.com</a></li>
	    </ul>-->
	  ';
	  $emailout=generateMailMarkUp("frontiersjobconnect.com","$email","$title","$content","$footer","fjc");
	    // // echo $emailout['rowmarkup'];
	    $toemail=$email;
	    $headers = "MIME-Version: 1.0" . "\r\n";
	    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	    $headers .= 'From: <no-reply@frontiersjobconnect.com>' . "\r\n";
	    $subject="Frontiers Job-Connect Recruit Registration";
	    if($host_email_send===true){
	      if(mail($toemail,$subject,$emailout['rowmarkup'],$headers)){

	      }else{
	        die('could not send Your email, something went wrong and we are handling it, meantime you could click the back button in your browser to get you out of here, we are really sorry');
	      }
	    }
	  
	    header('location:'.$host_addr.'fjcsuccess.php?ctype=client');
}else if($entryvariant=="contacthelpdesk"||$entryvariant=="contacthelpdesktwo"){
  $email=mysql_real_escape_string($_POST['email']);
  $name=mysql_real_escape_string($_POST['name']);
  $subject=mysql_real_escape_string($_POST['subject']);
  $message=mysql_real_escape_string($_POST['message']);
  $title="$subject";
  $content='
    <p style="text-align:left;">Hello Admin,<br>
    An individual named '.$name.' sent/asked the following, <br>
    '.$message.'
    </p>
    <a href="mailto:'.$email.'">Click to reply</a>
    <p style="text-align:right;">Thank You.</p>
  ';
  $footer='
    <!--<ul>
        <li><strong>Phone 1: </strong>0701-682-9254</li>
        <li><strong>Phone 2: </strong>0802-916-3891</li>
        <li><strong>Phone 3: </strong>0803-370-7244</li>
        <li><strong>Email: </strong><a href="mailto:info@muyiwaafolabi.com">info@muyiwaafolabi.com</a></li>
    </ul>-->
  ';
  $emailout=generateMailMarkUp("muyiwaafolabi.com","$email","$title","$content","$footer","");
  // // echo $emailout['rowmarkup'];
  $toemail=$entryvariant=="contacthelpdesk"?"frontiersjobconnect@gmail.com":"franklyspeakingwithm.afolabi@gmail.com";
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $headers .= 'From: <no-reply@muyiwaafolabi.com>' . "\r\n";
  // $headers .= 'Cc: info@muyiwaafolabi.com' . "\r\n";
  $subject="A new contact message";
  if($host_email_send===true){
    if(mail($toemail,$subject,$emailout['rowmarkup'],$headers)){
  		echo "OK";
    }else{
      die('Could not send Your email, something went wrong and we are handling it, meantime you could click the back button in your browser to get you out of here, we are really sorry');
    }
  }
  echo "OK";
}else if ($entryvariant=="contententry") {
  # code...
  $cid=getNextId("generalinfo");
  $maintype=mysql_real_escape_string($_POST['maintype']);
  $subtype=mysql_real_escape_string($_POST['subtype']);
  $title=mysql_real_escape_string($_POST['contenttitle']);
  $intro=isset($_POST['contentintro'])?mysql_real_escape_string($_POST['contentintro']):"";
  $content=mysql_real_escape_string(str_replace("../", "$host_addr",$_POST['contentpost']));
  if($intro!==""){
    $headerdescription = $intro;    
  }else if($intro==""&&$content==""){
    $headerdescription="";
  }else{
    // $headerdescription = $content!==""?convert_html_to_text($content):"";
    $headerdescription=$content!==""?html2txt($content):"";
    $monitorlength=strlen($headerdescription);
    $headerdescription=$montorlength<600?$headerdescription."...":substr($headerdescription, 0,600)."...";
  }
  $entrydate=date("Y-m-d H:i:s");
      $query="INSERT INTO generalinfo(maintype,subtype,title,intro,content,entrydate)VALUES
    ('$maintype','$subtype','$title','$headerdescription','$content','$entrydate')";
    // echo $query;
    $run=mysql_query($query)or die(mysql_error()." ".__LINE__);
    $contentpic=$_FILES['contentpic']['tmp_name'];
    if($contentpic!==""){
      $image="contentpic";
      $imgpath[]='../files/medsizes/';
      $imgpath[]='../files/thumbnails/';
      $imgsize[]="default";
      if($maintype=="about"){
        $imgsize[]="374,";
        $acceptedsize="";
        
      }else{
        $imgsize[]=",300";
        $acceptedsize="";
      }
      $imgouts=genericImageUpload($image,"varying",$imgpath,$imgsize,$acceptedsize);
      $len=strlen($imgouts[0]);
      $imagepath=substr($imgouts[0], 1,$len);
      // medsize
      $len2=strlen($imgouts[1]);
      $imagepath2=substr($imgouts[1], 1,$len2);
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
      //$coverpicid=getNextId("media");
      //maintype variants are original, medsize, thumb for respective size image.
      $mediaquery="INSERT INTO media(ownerid,ownertype,maintype,mediatype,location,details,filesize,width,height)VALUES('$cid','$maintype','coverphoto','image','$imagepath','$imagepath2','$filesize','$width','$height')";
      $mediarun=mysql_query($mediaquery)or die(mysql_error());
    }
    if($rurladmin==""){
    	header('location:../admin/adminindex.php');
	}else{
		header('location:'.$rurladmin.'');
	}
}
?>