<?php
include('connection.php');
$entryvariant=mysql_real_escape_string($_POST['entryvariant']);
$entryid=$_POST['entryid'];
$userrequest="";
if(isset($_POST['userrequest'])&&$_POST['userrequest']!==""){
	$userrequest=$_POST['userrequest'];
}
if($entryvariant=="editschool"){
$schoolname=mysql_real_escape_string($_POST['schoolname']);
genericSingleUpdate("schools","schoolname",$schoolname,"id",$entryid);
$schoolfaculty=mysql_real_escape_string($_POST['schoolfacultyname']);
if($schoolfaculty!==""){
$query="INSERT INTO schoolfaculty (schoolid,name)VALUES('$entryid','$schoolfaculty')";
$run=mysql_query($query)or die(mysql_error());
}
$row=getSingleSchool($entryid,"");
if(isset($_FILES['profpic']['name'])&&$_FILES['profpic']['name']!==""){
$schoolimg="profpic";
$imgpath[]="../images/schools/";
$imgsize[]="default";
$acceptedsize="";
//upload new image dont bother about name duplicate
$imgouts=genericImageUpload($schoolimg,"single",$imgpath,$imgsize,$acceptedsize);
if($imgouts!=="no image"){
//delete the previous image
$prevschoollogo=".".$row['mediaimg'];
unlink($prevschoollogo);
$len=strlen($imgouts[0]);
$imagepath=substr($imgouts[0], 1,$len);	
$orderfield=array();
$ordervalue=array();
$orderfield[]="ownerid";
$orderfield[]="type";
$orderfield[]="mediadata";
$ordervalue[]=$entryid;
$ordervalue[]="school";
$ordervalue[]="schoollogo";
genericSingleUpdate("media","mediapath",$imagepath,$orderfield,$ordervalue);
}

}
header('location:../admin/adminindex.php');
}elseif ($entryvariant=="editschoolfaculty") {
$schoolfaculty=mysql_real_escape_string($_POST['schoolfacultyname']);
genericSingleUpdate("schoolfaculty","name",$schoolfaculty,"id",$entryid);
header('location:../admin/adminindex.php');

}elseif ($entryvariant=="editdepartment") {
$departmentname=mysql_real_escape_string($_POST['departmentname']);
genericSingleUpdate("department","departmentname",$departmentname,"id",$entryid);
$schoolfaculty=mysql_real_escape_string($_POST['schoolfaculty']);
genericSingleUpdate("department","schoolfacultyid",$schoolfaculty,"id",$entryid);
header('location:../admin/adminindex.php');
}elseif ($entryvariant=="editcourse") {

$coursetitle=mysql_real_escape_string($_POST['coursetitle']);
genericSingleUpdate("courses","coursetitle",$coursetitle,"id",$entryid);
$coursecode=mysql_real_escape_string($_POST['coursecode']);
genericSingleUpdate("courses","coursecode",$coursecode,"id",$entryid);
	
$schoolfaculty=mysql_real_escape_string($_POST['schoolfaculty']);
$department=mysql_real_escape_string($_POST['department']);
if($schoolfaculty!==""&&$department!==""){
genericSingleUpdate("courses","schoolfacultyid",$schoolfaculty,"id",$entryid);
genericSingleUpdate("courses","departmentid",$department,"id",$entryid);
}
$level=mysql_real_escape_string($_POST['level']);
genericSingleUpdate("courses","level",$level,"id",$entryid);
$semester=mysql_real_escape_string($_POST['semester']);
genericSingleUpdate("courses","semester",$semester,"id",$entryid);
$lecturer=mysql_real_escape_string($_POST['lecturer']);
genericSingleUpdate("courses","lecturerid",$lecturer,"id",$entryid);
header('location:../admin/adminindex.php');

}elseif ($entryvariant=="editcourseoutline") {
// 	$courseid=mysql_real_escape_string($_POST['course']);
// genericSingleUpdate("courseoutlines","courseid",$courseid,"id",$entryid);
/*$check=md5(1);
echo $check."<br>";*/
if(isset($_POST['userrequest'])&&$_POST['userrequest']!==""){
session_start();
$lectid=mysql_real_escape_string($_POST['lectid']);
$md5id=md5($lectid);
	if (isset($_SESSION['l'.$md5id.''])&&$_SESSION['l'.$md5id.'']!=="") {
			
	}else{
		header('location:../login.php?error=true');

	}
//header('location:../lecturer.php?id='.$lectid.'');
}
	$outline=mysql_real_escape_string($_POST['outlinetitle']);
genericSingleUpdate("courseoutlines","outline",$outline,"id",$entryid);
	$overview=mysql_real_escape_string($_POST['overview']);
genericSingleUpdate("courseoutlines","overview",$overview,"id",$entryid);
if(isset($_FILES['profpic']['name'])&&$_FILES['profpic']['name']!==""){
$title=mysql_real_escape_string($_POST['resourcename']);
$outlinedata=getSingleCourseOutline($entryid,"","");
$foldhash=$outlinedata['foldhash'];
//echo $foldhash;
$filesize=0;
$filepath=array();
$courseid=getSingleCourseOutline($entryid,"","");
$courseid=$courseid['courseid'];
	$datatype=getFileType("profpic");
	$usertype=mysql_real_escape_string($_POST['user']);
//	$usertype=="admin"?$filepath[]="../materials/".$foldhash."/":$filepath[]="./materials/".$foldhash."/";
$filepath[]="../materials/".$foldhash."/";
	$entryname="profpic";
	if($datatype=="image"){
//important, both are arrays i.e filepath and filedatasize
$imagesize=$_FILES[''.$entryname.'']['size'];
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
$filedatasize[]="default";
$acceptedsize="";
//upload new image dont bother about name duplicate
$imgouts=genericImageUpload($entryname,"single",$filepath,$filedatasize,$acceptedsize);
$len=strlen($imgouts[0]);
$newfilepath=substr($imgouts[0], 1,$len);


	}elseif ($datatype=="video"||$datatype=="audio"||$datatype=="office"||$datatype==""||$datatype=="pdf"||$datatype=="others") {
		# code...
		$uploadpath=$filepath[0];
$dataouts=simpleUpload($entryname,$uploadpath);
$newfilepath=$dataouts['fileoutnormal'];
$filesize=$dataouts['filesize'];
$realsize=$dataouts['realsize'];
echo $realsize;
	}
$regdate=date("d-m-Y h:i:s A");
	$query="INSERT INTO materials(courseid,outlineid,title,type,location,filesize,currentver)VALUES('$courseid','$entryid','$title','$datatype','$newfilepath','$filesize','$regdate')";
echo $query;
$run=mysql_query($query)or die(mysql_error());
}
if (isset($_SESSION['l'.$md5id.''])&&$_SESSION['l'.$md5id.'']!=="") {
header('location:../lecturer.php?id='.$lectid.'');	
	}else{		
header('location:../admin/adminindex.php');
	}

}elseif ($entryvariant=="editmaterial") {
	if(isset($_POST['userrequest'])&&$_POST['userrequest']!==""){
session_start();
$lectid=mysql_real_escape_string($_POST['lectid']);
$md5id=md5($lectid);
	if (isset($_SESSION['l'.$md5id.''])&&$_SESSION['l'.$md5id.'']!=="") {
			
	}else{
		header('location:../login.php?error=true');

	}
//header('location:../lecturer.php?id='.$lectid.'');
}
$title=mysql_real_escape_string($_POST['resourcename']);
genericSingleUpdate("materials","title",$title,"id",$entryid);

if(isset($_FILES['profpic']['name'])&&$_FILES['profpic']['name']!==""){
$foldhash=mysql_real_escape_string($_POST['foldhash']);
$filesize=0;

$matdata=getSingleMaterial($entryid,"","");
$prevfile=".".$matdata['location'];
$prevver=$matdata['currentver'];
	$datatype=getFileType("profpic");
	$usertype=mysql_real_escape_string($_POST['user']);
//	$usertype=="admin"?$filepath[]="../materials/".$foldhash."/":$filepath[]="./materials/".$foldhash."/";
$filepath[]="../materials/".$foldhash."/";
	$entryname="profpic";
	if($datatype=="image"){
		
$filedatasize[]="default";
$acceptedsize="";
//upload new image dont bother about name duplicate
$imgouts=genericImageUpload($entryname,"single",$filepath,$filedatasize,$acceptedsize);
$len=strlen($imgouts[0]);
$newfilepath=substr($imgouts[0], 1,$len);	
	}elseif ($datatype=="video"||$datatype=="audio"||$datatype=="office"||$datatype=="pdf"||$datatype=="others") {
		# code...
		$uploadpath=$filepath[0];
$dataouts=simpleUpload($entryname,$uploadpath);
$newfilepath=$dataouts['fileoutnormal'];
$filesize=$dataouts['filesize'];
	}
unlink($prevfile);
$currentver=date("d-m-Y h:i:s A");
genericSingleUpdate("materials","location",$newfilepath,"id",$entryid);
genericSingleUpdate("materials","type",$datatype,"id",$entryid);
genericSingleUpdate("materials","filesize",$filesize,"id",$entryid);
genericSingleUpdate("materials","currentver",$currentver,"id",$entryid);
genericSingleUpdate("materials","prevver",$prevver,"id",$entryid);
}

if (isset($_SESSION['l'.$md5id.''])&&$_SESSION['l'.$md5id.'']!=="") {
header('location:../lecturer.php?id='.$lectid.'');			
	}else{
header('location:../admin/adminindex.php');
	}
}elseif ($entryvariant=="editlecturer") {
if(isset($_POST['user'])&&$_POST['user']!=="admin"){
session_start();
$lectid=mysql_real_escape_string($_POST['entryid']);
$md5id=md5($lectid);
	if (isset($_SESSION['l'.$md5id.''])&&$_SESSION['l'.$md5id.'']!=="") {
			
	}else{
		header('location:../login.php?error=true');

	}
//header('location:../lecturer.php?id='.$lectid.'');
}
	$schoolfacultyid=mysql_real_escape_string($_POST['schoolfaculty']);
genericSingleUpdate("lecturers","schoolfacultyid",$schoolfacultyid,"id",$entryid);
	$lecturername=mysql_real_escape_string($_POST['lecturername']);
genericSingleUpdate("lecturers","name",$lecturername,"id",$entryid);
	$email=mysql_real_escape_string($_POST['email']);
	$testemail=checkEmail($email,"lecturers","email");
	if($testemail['testresult']!=="matched"){
genericSingleUpdate("lecturers","email",$email,"id",$entryid);
	}
	$phonenumber=mysql_real_escape_string($_POST['phonenumber']);
genericSingleUpdate("lecturers","phonenumber",$phonenumber,"id",$entryid);
	$password=mysql_real_escape_string($_POST['password']);
genericSingleUpdate("lecturers","password",$password,"id",$entryid);
	
$usertype=mysql_real_escape_string($_POST['user']);

if(isset($_FILES['profpic']['name'])&&$_FILES['profpic']['name']!==""){
$foldhash=mysql_real_escape_string($_POST['foldhash']);
$filesize=0;
//$usertype=="admin"?$filepath[]="../materials/".$foldhash."/":$filepath[]="./materials/".$foldhash."/";
$filepath[]="../images/lecturers/".$foldhash."/";
$entryname="profpic";
$filedatasize[]="default";
$acceptedsize="";
//upload new image dont bother about name duplicate
$imgouts=genericImageUpload($entryname,"single",$filepath,$filedatasize,$acceptedsize);
$len=strlen($imgouts[0]);
$newfilepath=substr($imgouts[0], 1,$len);	
$matdata=getSingleLecturer($entryid,"","");
$prevfile=".".$matdata['photo'];
if($prevfile!=="../images/default.gif"){
unlink($prevfile);
}
genericSingleUpdate("lecturers","photo",$newfilepath,"id",$entryid);
}
if(isset($_POST['user'])&&$_POST['user']!=="admin"){
header('location:../lecturer.php?id='.$entryid.'');
}else{
header('location:../admin/adminindex.php');	
}

}elseif ($entryvariant=="editstudent") {
		$schoolfacultyid=mysql_real_escape_string($_POST['schoolfaculty']);
		$departmentid=mysql_real_escape_string($_POST['department']);
//check if it is the actual user editting
if(isset($_POST['user'])&&$_POST['user']!=="admin"){
session_start();
$studid=mysql_real_escape_string($_POST['studid']);
$md5id=md5($studid);
	if (isset($_SESSION['s'.$md5id.''])&&$_SESSION['s'.$md5id.'']!=="") {
			
	}else{
		header('location:../login.php?error=true');

	}

}

if($schoolfacultyid!==""&&$departmentid!==""){
genericSingleUpdate("students","schoolfacultyid",$schoolfacultyid,"id",$entryid);
genericSingleUpdate("students","departmentid",$departmentid,"id",$entryid);
}
	$studentname=mysql_real_escape_string($_POST['studentname']);
genericSingleUpdate("students","name",$studentname,"id",$entryid);
	$email=mysql_real_escape_string($_POST['email']);
	$testemail=checkEmail($email,"students","email");
	if($testemail['testresult']!=="matched"){
genericSingleUpdate("students","email",$email,"id",$entryid);
	}
	$phonenumber=mysql_real_escape_string($_POST['phonenumber']);
genericSingleUpdate("students","phonenumber",$phonenumber,"id",$entryid);
	$level=mysql_real_escape_string($_POST['level']);
genericSingleUpdate("students","level",$level,"id",$entryid);
	$password=mysql_real_escape_string($_POST['password']);
genericSingleUpdate("students","password",$password,"id",$entryid);
	
// $usertype=mysql_real_escape_string($_POST['user']);

if(isset($_FILES['profpic']['name'])&&$_FILES['profpic']['name']!==""){
$foldhash=mysql_real_escape_string($_POST['foldhash']);
$filesize=0;
//$usertype=="admin"?$filepath[]="../materials/".$foldhash."/":$filepath[]="./materials/".$foldhash."/";
$filepath[]="../images/students/".$foldhash."/";
$entryname="profpic";
$filedatasize[]="default";
$acceptedsize="";
//upload new image dont bother about name duplicate
$imgouts=genericImageUpload($entryname,"single",$filepath,$filedatasize,$acceptedsize);
$len=strlen($imgouts[0]);
$newfilepath=substr($imgouts[0], 1,$len);	
$matdata=getSingleStudent($entryid,"","");
$prevfile=".".$matdata['photo'];
if($prevfile!=="../images/default.gif"){
unlink($prevfile);
}
genericSingleUpdate("students","photo",$newfilepath,"id",$entryid);
}
if(isset($_POST['user'])&&$_POST['user']!=="admin"){
header('location:../student.php?id='.$entryid.'');
}else{
header('location:../admin/adminindex.php');	
}

}elseif ($entryvariant=="editquiz") {
if(isset($_POST['user'])&&$_POST['user']!=="admin"){
session_start();
$lectid=mysql_real_escape_string($_POST['lectid']);
$md5id=md5($lectid);
	if (isset($_SESSION['l'.$md5id.''])&&$_SESSION['l'.$md5id.'']!=="") {
			
	}else{
		header('location:../login.php?error=true');

	}
//header('location:../lecturer.php?id='.$lectid.'');
}
$prevcount=mysql_real_escape_string($_POST['prevcount']);
$courseid=mysql_real_escape_string($_POST['entryid']);
$curcount=mysql_real_escape_string($_POST['curcount']);
$addedcount=$curcount-$prevcount;
if($addedcount<1){
for($i=1;$i<=$curcount;$i++){
	$quizid=mysql_real_escape_string($_POST['entrydataid'.$i.'']);
	$question=mysql_real_escape_string($_POST['question'.$i.'']);
genericSingleUpdate("quiz","question",$question,"id",$quizid);	

	$optionone=mysql_real_escape_string($_POST['option1'.$i.'']);
genericSingleUpdate("quiz","optionone",$optionone,"id",$quizid);

	$optiontwo=mysql_real_escape_string($_POST['option2'.$i.'']);
genericSingleUpdate("quiz","optiontwo",$optiontwo,"id",$quizid);

	$optionthree=mysql_real_escape_string($_POST['option3'.$i.'']);
genericSingleUpdate("quiz","optionthree",$optionthree,"id",$quizid);

	$optionfour=mysql_real_escape_string($_POST['option4'.$i.'']);
genericSingleUpdate("quiz","optionfour",$optionfour,"id",$quizid);

	$answer=mysql_real_escape_string($_POST['answer'.$i.'']);
genericSingleUpdate("quiz","answer",$answer,"id",$quizid);
}
}else{
for($i=1;$i<=$prevcount;$i++){
	$quizid=mysql_real_escape_string($_POST['entrydataid'.$i.'']);
	$question=mysql_real_escape_string($_POST['question'.$i.'']);
genericSingleUpdate("quiz","question",$question,"id",$quizid);	

	$optionone=mysql_real_escape_string($_POST['option1'.$i.'']);
genericSingleUpdate("quiz","optionone",$optionone,"id",$quizid);

	$optiontwo=mysql_real_escape_string($_POST['option2'.$i.'']);
genericSingleUpdate("quiz","optiontwo",$optiontwo,"id",$quizid);

	$optionthree=mysql_real_escape_string($_POST['option3'.$i.'']);
genericSingleUpdate("quiz","optionthree",$optionthree,"id",$quizid);

	$optionfour=mysql_real_escape_string($_POST['option4'.$i.'']);
genericSingleUpdate("quiz","optionfour",$optionfour,"id",$quizid);

	$answer=mysql_real_escape_string($_POST['answer'.$i.'']);
genericSingleUpdate("quiz","answer",$answer,"id",$quizid);
}
	for($i=$prevcount+1;$i<=$curcount;$i++){
	$question=mysql_real_escape_string($_POST['question'.$i.'']);
	$optionone=mysql_real_escape_string($_POST['option1'.$i.'']);
	$optiontwo=mysql_real_escape_string($_POST['option2'.$i.'']);
	$optionthree=mysql_real_escape_string($_POST['option3'.$i.'']);
	$optionfour=mysql_real_escape_string($_POST['option4'.$i.'']);
	$answer=mysql_real_escape_string($_POST['answer'.$i.'']);
	if($question!==""&&$optionone!==""&&$optiontwo!==""&&$optionthree!==""&&$optionfour!==""&&$answer!==""){
		$query="INSERT INTO quiz (courseid,question,optionone,optiontwo,optionthree,optionfour,answer)VALUES('$courseid','$question','$optionone','$optiontwo','$optionthree','$optionfour','$answer')";
//		echo $query;
		$run=mysql_query($query)or die(mysql_error());
	}
}
}


//ALTER TABLE `quiz` AUTO_INCREMENT=1


if (isset($_POST['user'])&&$_POST['user']!=="admin") {
header('location:../lecturer.php?id='.$lectid.'');	
	}else{		
header('location:../admin/adminindex.php');
	}
}
?>