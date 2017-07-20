<?php
include('connection.php');
$entryvariant=$_POST['entryvariant'];
if($entryvariant=="createschool"){
$schoolname=mysql_real_escape_string($_POST['schoolname']);
$schooltype=mysql_real_escape_string($_POST['schooltype']);
$partimage="profpic";
$imgpath[]="../images/schools/";
$imgsize[]="default";
$acceptedsize="";
$imgouts=genericImageUpload($partimage,"single",$imgpath,$imgsize,$acceptedsize);
$len=strlen($imgouts[0]);
$imagepath=substr($imgouts[0], 1,$len);
$tablename="schools";
$nextid=getNextId($tablename);
$query="INSERT INTO $tablename (schoolname,schooltype)VALUES('$schoolname','$schooltype')";
$run=mysql_query($query)or die(mysql_error());
$query2="INSERT INTO media (ownerid,type,mediapath,mediadata)VALUES('$nextid','school','$imagepath','schoollogo')";
$run2=mysql_query($query2)or die(mysql_error());
if(isset($_POST['userrequest'])&&$_POST['userrequest']!==""){

}else{
header('location:../admin/adminindex.php');	
}
/*echo $query.'<br>';
echo $query2;*/
}elseif ($entryvariant=="createdepartment") {
$departmentname=mysql_real_escape_string($_POST['departmentname']);
$schoolfaculty=mysql_real_escape_string($_POST['schoolfaculty']);
$school=mysql_real_escape_string($_POST['school']);
$query="INSERT INTO department (schoolid,schoolfacultyid,departmentname)VALUES('$school','$schoolfaculty','$departmentname')";
$run=mysql_query($query)or die(mysql_error());
if(isset($_POST['userrequest'])&&$_POST['userrequest']!==""){
header('location:../login.php');
}else{
header('location:../admin/adminindex.php');	
}

}elseif ($entryvariant=="createcourse") {
$coursetitle=mysql_real_escape_string($_POST['coursetitle']);
$coursecode=mysql_real_escape_string($_POST['coursecode']);
$school=mysql_real_escape_string($_POST['school']);	
$schooldata=getSingleSchool($school,"");
$schooltype=$schooldata['schooltype'];
$schoolfaculty=mysql_real_escape_string($_POST['schoolfaculty']);
$department=mysql_real_escape_string($_POST['department']);

$level=mysql_real_escape_string($_POST['level'.$schooltype.'']);

$semester=mysql_real_escape_string($_POST['semester']);
//COME BACK AND CHANGE THIS WHEN LECTURERS ARE UP AND RUNNING
//$lecturer=mysql_real_escape_string($_POST['lecturer']);
$lecturer=0;
$query="INSERT INTO courses (coursetitle,coursecode,lecturerid,schoolid,schoolfacultyid,departmentid,level,semester)VALUES('$coursetitle','$coursecode','$lecturer','$school','$schoolfaculty','$department','$level','$semester')";
$run=mysql_query($query)or die(mysql_error());
if(isset($_POST['userrequest'])&&$_POST['userrequest']!==""){

}else{
header('location:../admin/adminindex.php');	
}

}elseif ($entryvariant=="createcourseoutline"||$entryvariant=="lecturercourseoutline") {
	$courseid=mysql_real_escape_string($_POST['course']);
	$outline=mysql_real_escape_string($_POST['outlinetitle']);
	$overview=mysql_real_escape_string($_POST['overview']);
//	$courseid=mysql_real_escape_string($_POST['courseid']);
	$query1="SELECT * FROM courseoutlines WHERE courseid='$courseid'";
	$run1=mysql_query($query1)or die(mysql_error());
	$numrows1=mysql_num_rows($run1);
	$outlinepoint=$numrows1+1;
	$nextid=getNextId("courseoutlines");
	$foldhash=md5($nextid);
mkdir('../materials/'.$foldhash.'',0777);
$query="INSERT INTO courseoutlines (courseid,outlinepoint,outline,overview,foldhash)VALUES('$courseid','$outlinepoint','$outline','$overview','$foldhash')";
$run=mysql_query($query)or die(mysql_error());
if(isset($_POST['userrequest'])&&$_POST['userrequest']!==""){
$lectid=$_POST['lectid'];
header('location:../lecturer.php?id='.$lectid.'');
}else{
header('location:../admin/adminindex.php');	
}

}elseif ($entryvariant=="createlecturer") {
	$schoolid=mysql_real_escape_string($_POST['school']);
	$schoolfacultyid=mysql_real_escape_string($_POST['schoolfaculty']);
	$lecturername=mysql_real_escape_string($_POST['lecturername']);
	$email=mysql_real_escape_string($_POST['email']);
	$testemail=checkEmail($email,"lecturers","email");
	if($testemail['testresult']=="matched"){
		die('The email address "'.$email.'" already exists for a lecturer please choose another one, to return to the registration please use the back navigation button in you browser');
	}
	$phonenumber=mysql_real_escape_string($_POST['phonenumber']);
	$password=mysql_real_escape_string($_POST['password']);
	$nextid=getNextId("lecturers");
	$foldhash=md5($nextid);
mkdir('../images/lecturers/'.$foldhash.'',0777);
if(isset($_FILES['profpic']['name'])&&$_FILES['profpic']['name']!==""){
$image="profpic";
$imgpath[]='../images/lecturers/'.$foldhash.'/';
$imgsize[]="default";
$acceptedsize="";
$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
$len=strlen($imgouts[0]);
$imagepath=substr($imgouts[0], 1,$len);
}else{
$imagepath="./images/default.gif";
}
$query="INSERT INTO lecturers (schoolid,schoolfacultyid,name,photo,foldhash,phonenumber,email,password)VALUES('$schoolid','$schoolfacultyid','$lecturername','$imagepath','$foldhash','$phonenumber','$email','$password')";
echo $query;
$run=mysql_query($query)or die(mysql_error());
if(isset($_POST['userrequest'])&&$_POST['userrequest']!==""){
header('location:../login.php');
}else{
header('location:../admin/adminindex.php');	
}

}elseif ($entryvariant=="createstudent") {
	$schoolid=mysql_real_escape_string($_POST['school']);
	$schoolfacultyid=mysql_real_escape_string($_POST['schoolfaculty']);
	$departmentid=mysql_real_escape_string($_POST['department']);
	$schooldata=getSingleSchool($schoolid,"");
	$schooltype=$schooldata['schooltype'];
	$level=mysql_real_escape_string($_POST['level'.$schooltype.'']);
	$studentname=mysql_real_escape_string($_POST['studentname']);
	$phonenumber=mysql_real_escape_string($_POST['phonenumber']);
	$password=mysql_real_escape_string($_POST['password']);
	$email=mysql_real_escape_string($_POST['email']);
	$testemail=checkEmail($email,"students","email");
	if($testemail['testresult']=="matched"){
		die('The email address "'.$email.'" already exists for a lecturer please choose another one, to return to the registration please use the back navigation button in you browser');
	}
	$nextid=getNextId("students");
	$foldhash=md5($nextid);
mkdir('../images/students/'.$foldhash.'',0777);
if(isset($_FILES['profpic']['name'])&&$_FILES['profpic']['name']!==""){
$image="profpic";
$imgpath[]='../images/students/'.$foldhash.'/';
$imgsize[]="default";
$acceptedsize="";
$imgouts=genericImageUpload($image,"single",$imgpath,$imgsize,$acceptedsize);
$len=strlen($imgouts[0]);
$imagepath=substr($imgouts[0], 1,$len);
}else{
$imagepath="./images/default.gif";
}
$query="INSERT INTO students (schoolid,schoolfacultyid,departmentid,name,level,photo,foldhash,phonenumber,email,password)VALUES('$schoolid','$schoolfacultyid','$departmentid','$studentname','$level','$imagepath','$foldhash','$phonenumber','$email','$password')";
//echo $query;
$run=mysql_query($query)or die(mysql_error());
if(isset($_POST['userrequest'])&&$_POST['userrequest']!==""){
header('location:../login.php');
}else{
header('location:../admin/adminindex.php');	
}

}elseif($entryvariant=="createquiz"){
	echo "in here";
	# code...
	$courseid=mysql_real_escape_string($_POST['course']);
	$curcount=mysql_real_escape_string($_POST['curcount']);
	for($i=1;$i<=$curcount;$i++){
	$question=mysql_real_escape_string($_POST['question'.$i.'']);
	echo $question;
	if($question!==""){
		$optionone=mysql_real_escape_string($_POST['option1'.$i.'']);
		$optiontwo=mysql_real_escape_string($_POST['option2'.$i.'']);
		$optionthree=mysql_real_escape_string($_POST['option3'.$i.'']);
		$optionfour=mysql_real_escape_string($_POST['option4'.$i.'']);
		$answer=mysql_real_escape_string($_POST['answer'.$i.'']);
		$query="INSERT INTO quiz (courseid,question,optionone,optiontwo,optionthree,optionfour,answer)VALUES('$courseid','$question','$optionone','$optiontwo','$optionthree','$optionfour','$answer')";
		echo $query;
		$run=mysql_query($query)or die(mysql_error());
	}
	}
if(isset($_POST['userrequest'])&&$_POST['userrequest']!==""){
$lecturerid=$_POST['lectid'];
header('location:../lecturer.php?id='.$lecturerid.'');
}else{
header('location:../admin/adminindex.php');	
}
}
?>