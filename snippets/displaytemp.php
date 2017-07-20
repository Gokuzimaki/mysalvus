<?php
include('connection.php');
if(isset($_GET['displaytype'])){
$displaytype=$_GET['displaytype'];
$extraval=$_GET['extraval'];
}elseif (isset($_POST['displaytype'])) {
$displaytype2=$_GET['displaytype'];	
}
if(isset($_GET['usertype'])&&isset($_GET['userid'])) {
$usertype=$_GET['usertype'];	
$userid=$_GET['userid'];	
$ut="";
session_start();
	if($userid!==""){
			$md5id=md5($userid);
		}
		if($usertype=="student"){
			$ut="s";
		}elseif($usertype=="lecturer"){
			$ut="l";
		}
	if (isset($_SESSION[''.$ut.''.$md5id.''])&&$_SESSION[''.$ut.''.$md5id.'']!==""&&$_SESSION[''.$ut.''.$md5id.'']==$userid) {
			
	}else{
		echo 'Sorry Please log out of this account and login again your session has expired.. thank you';
		die('Expired Session');
		//header('location:../login.php?error=true');

	}
}

if($displaytype=="createschool"){
// echo $displaytype;
include('createschool.php');

}elseif ($displaytype=="createdepartment") {
include('createdepartment.php');

}elseif ($displaytype=="createcourse") {
// echo $displaytype;	
include('createcourse.php');

}elseif ($displaytype=="createcourseoutline") {
// echo $displaytype;	
include('createcourseoutline.php');

}elseif ($displaytype=="createquiz") {
// echo $displaytype;	
include('createquiz.php');

}elseif ($displaytype=="createlecturer") {
// echo $displaytype;
include('createlecturer.php');	

}elseif ($displaytype=="createstudent") {
// echo $displaytype;	
include('createstudent.php');	

}elseif($displaytype=="editschool"){
// echo $displaytype;
	getAllSchools($extraval,"");

}elseif ($displaytype=="editschoolfaculty") {
$partid=$_GET['entryid'];
$parttype="id";
$row=getSingleSchoolFaculty($partid,$parttype,"");
echo $row['adminoutput'];

}elseif ($displaytype=="editmaterial") {
$partid=$_GET['entryid'];
$parttype="id";
$row=getSingleMaterial($partid,$parttype,"");
if($extraval=="admin"){
echo $row['adminoutput'];
}else{
echo $row['vieweroutput'];	
}

}elseif ($displaytype=="getschoolfaculty") {
$partid=$_GET['entryid'];
$parttype="schoolid";
$row=getSingleSchoolFaculty($partid,$parttype,"");
echo $row['dataoptionoutput'];

}elseif ($displaytype=="getdepartments") {
$partid=$_GET['entryid'];
$parttype="schoolfacultyid";
$row=getSingleDepartment($partid,$parttype,"");
echo $row['dataoptionoutput'];

}elseif ($displaytype=="getlevels") {
$partid=$_GET['entryid'];
$row=getSingleSchool($partid,"");
$schooltype=$row['schooltype'];
$optionout=getLevels($schooltype);
echo $optionout;

}elseif ($displaytype=="getcourses") {
$partid=$_GET['entryid'];
$parttype="departmentid";
$row=getSingleCourse($partid,$parttype,"");
echo $row['dataoptionoutput'];

}elseif ($displaytype=="getlecturers") {
$partid=$_GET['entryid'];
$parttype="schoolfacultyid";
$row=getSingleLecturer($partid,$parttype,"");
//echo "in here";
echo $row['dataoptionoutput'];

}elseif ($displaytype=="editdepartment") {
	getAllDepartments($extraval,"");

}elseif ($displaytype=="editcourse") {
getAllCourses($extraval,"");	

}elseif ($displaytype=="editcourseoutline") {
	getAllCourseOutlines($extraval,"");

}elseif ($displaytype=="editquiz") {
// echo $displaytype;	
	getAllQuizzes($extraval,"");
}elseif ($displaytype=="editlecturer") {
// echo $displaytype;	
getAllLecturers($extraval,'');
}elseif ($displaytype=="editstudent") {
// echo $displaytype;	
getAllStudents($extraval,'');
}elseif($displaytype=="deletematerial"){
$delid=$_GET['delid'];
$checker=deleteMaterial($delid);
echo $checker;

}elseif($displaytype=="search"){
	$searchval=$_GET['searchval'];
	$searchby=$_GET['searchby'];
	searchThrough($searchval,$searchby,$extraval,"");
}elseif ($displaytype=="getform") {
	$formtype=$_GET['formname'];
	// echo $formtype;
	if($formtype=="student"){
include('createstudent.php');
	}elseif($formtype=="lecturer"){
include('createlecturer.php');
	}
}elseif($displaytype=="studentcourse"){
$rowout=getSingleStudent($userid,'','');
$level=mysql_real_escape_string($_GET['level']);
$semester=mysql_real_escape_string($_GET['semester']);
$schoolid=$rowout['schoolid'];
$query="SELECT * FROM courses WHERE level='$level' AND semester='$semester' AND  schoolid='$schoolid' ORDER BY coursecode,coursetitle";
$run=mysql_query($query)or die(mysql_error());
$numrows=mysql_num_rows($run);
if($numrows>0){
echo '<div id="displaycontentleft">';
	while($row=mysql_fetch_assoc($run)){
$rowout=getSingleCourse($row['id'],'','');
echo $rowout['vieweroutput'];
	}
echo'</div><div id="displaycontentright"></div>
';
}else{
	echo 'Sorry no courses are available for this level and semester yet';
}
}elseif($displaytype=="viewoutline"){
if($usertype=="student"){
 $outlineid=$_GET['datavalue'];
 $rowout=getSingleCourseOutline($outlineid,'','');
 echo $rowout['vieweroutputone'];
}elseif ($usertype=="lecturer") {
	# code...

}
}elseif($displaytype=="studentedit"){
$rowout=getSingleStudent($userid,'','');
echo '<div id="displaycontentleft">
 '.$rowout['vieweroutput'].'	
</div>
<div id="displaycontentright">
 '.$rowout['viewerform'].'
</div>
';


}elseif($displaytype=="createmessage"){

}elseif($displaytype=="sendmail"){

}elseif($displaytype=="viewmessages"){

}elseif($displaytype=="coursematerials"){
	$outlineid=mysql_real_escape_string($_GET['outlineid']);
$query="SELECT * FROM materials WHERE outlineid=$outlineid";
$run=mysql_query($query)or die(mysql_error());
$numrows=mysql_num_rows($run);
if($numrows>0){
	echo'
<div id="closecontainer" name="closeviewport" class="altcloseone"><img src="./images/closebutton.ico" class="total"/></div>
<div id="form">
	';
while ($row=mysql_fetch_array($run)) {
	# code...
	$rowout=getSingleMaterial($row['id'],"","");
	echo $rowout['dataoutstudent'];
}
echo "</div>";
}else{
	echo '<div id="closecontainer" name="closeviewport" class="altcloseone"><img src="./images/closebutton.ico" class="total"/></div><br>Sorry but this course outline has no resources attached to it yet';
}
}elseif($displaytype=="studentquiz"){
$courseid=mysql_real_escape_string($_GET['courseid']);
$query="SELECT * FROM quiz WHERE courseid=$courseid";
$run=mysql_query($query)or die(mysql_error());
$numrows=mysql_num_rows($run);
if($numrows>0){
echo'
<div id="closecontainer" name="closeviewport" class="altcloseone"><img src="./images/closebutton.ico" class="total"/></div>
<div id="form" style="margin-bottom:32px;">
<form name="editquiz" action="./snippets/edit.php"  method="POST" onSubmit="return false;" enctype="multipart/form-data">
	<input type="hidden" name="usertype" value="'.$usertype.'"/>
	<input type="hidden" name="userid" value="'.$userid.'"/>
';
$rowout=getSingleQuiz($courseid,'','');
echo $rowout['studentquiz'];
echo'
<script>
//console.log(result);
//var realouts=answer.split(",");
</script>
<div id="formend">
	 <input type="button" style="width: 197px;" name="submitquiz" class="submitbutton" value="Submit Quiz"/>
	</div>
</form>
</div>
';
}else{
	echo '<div id="closecontainer" name="closeviewport" class="altcloseone"><img src="./images/closebutton.ico" class="total"/></div><br>Sorry but this course has no quiz attached to it yet';

}

}elseif ($displaytype=="newoutline") {
	# code...
$query="SELECT * FROM courses WHERE lecturerid='$userid'";
$run=mysql_query($query)or die(mysql_error());
$numrows=mysql_num_rows($run);
$courseselection='<option value="">--Choose--</option>';
if($numrows>0){
while($row=mysql_fetch_assoc($run)){
$courseselection.='<option value="'.$row['id'].'">'.$row['coursetitle'].'</option>';
}
$input='<input type="hidden" name="courseselection" value="'.$courseselection.'">';
include('lecturercourseoutline.php');
echo'
<script>
var courseselect=\''.$courseselection.'\';
$("select[name=course]").live("click",function(){
var thevalue=$(this).attr(\'value\');
if(thevalue==""){
$(this).html(courseselect);
}
});
console.log(courseselect);
</script>';
}else{
	echo "Sorry no course has been assigned to you yet, this would be resolved by the main administrator";
}
}elseif ($displaytype=="editoutline") {
	# code...
$query="SELECT * FROM courses WHERE lecturerid='$userid'";
$run=mysql_query($query)or die(mysql_error());
$numrows=mysql_num_rows($run);

if($numrows>0){
while($row=mysql_fetch_assoc($run)){
$id=$row['id'];
$query10="SELECT * FROM courseoutlines WHERE courseid='$id'";
$run10=mysql_query($query10)or die(mysql_error());
$numrows10=mysql_num_rows($run10);
if($numrows10>0){
while($row10=mysql_fetch_assoc($run10)){
	$id2=$row10['id'];
$rowout=getSingleCourseOutline($id2,"","");
echo $rowout['viewerform'];
}
}else{
echo"You have not created any course outliines yet please endeavour to do so";
}
}

}else{
	echo "Sorry no course has been assigned to you yet, this would be resolved by the main administrator";
}
}elseif ($displaytype=="lectureredit") {
	# code...
$rowout=getSingleLecturer($userid,'','');
echo $rowout['vieweroutput'];
}elseif ($displaytype=="lecturercreatequiz") {
	# code...
$query="SELECT * FROM courses WHERE lecturerid='$userid'";
$run=mysql_query($query)or die(mysql_error());
$numrows=mysql_num_rows($run);
$courseselection='<option value="">--Choose--</option>';
if($numrows>0){
while($row=mysql_fetch_assoc($run)){
	$id=$row['id'];
$query10="SELECT * FROM quiz WHERE courseid='$id'";
$run10=mysql_query($query10)or die(mysql_error());
$numrows10=mysql_num_rows($run10);	
if($numrows10<1){
$courseselection.='<option value="'.$row['id'].'">'.$row['coursetitle'].'</option>';
}
}
$input='<input type="hidden" name="courseselection" value="'.$courseselection.'">';
include('lecturerquiz.php');
echo'
<script>
var courseselect=\''.$courseselection.'\';
$("select[name=course]").live("click",function(){
var thevalue=$(this).attr(\'value\');
if(thevalue==""){
$(this).html(courseselect);
}
});
console.log(courseselect);
</script>';
}else{
	echo "Sorry no course has been assigned to you yet, this would be resolved by the main administrator";
}
}elseif ($displaytype=="lecturereditquiz") {
	# code... 
$query="SELECT * FROM courses WHERE lecturerid='$userid'";
$run=mysql_query($query)or die(mysql_error());
$numrows=mysql_num_rows($run);
$outputs=0;
if($numrows>0){
while($row=mysql_fetch_assoc($run)){
$id=$row['id'];
$query10="SELECT * FROM quiz WHERE courseid='$id'";
$run10=mysql_query($query10)or die(mysql_error());
$numrows10=mysql_num_rows($run10);
if($numrows10>0){
$rowout=getSingleQuiz($id,"","");
echo $rowout['lecturereditquiz'];
$outputs+=1;
}
}
if($outputs<1){
echo "Please try and create quizzes for the courses you are taking there is none here";
}
}else{
	echo "Sorry no course has been assigned to you yet, this would be resolved by the main administrator";
}
}elseif ($displaytype=="usersearch") {
	# code... 
$level=mysql_real_escape_string($_GET['level']);
$semester=mysql_real_escape_string($_GET['semester']);
$searchval=mysql_real_escape_string($_GET['searchval']);
$searchby=mysql_real_escape_string($_GET['searchby']);
userSearch($searchval,$searchby,$usertype,$userid,$level,$semester);
}
?>