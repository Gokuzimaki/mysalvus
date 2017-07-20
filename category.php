<?php 
session_start();
include('./snippets/connection.php');
if(isset($_GET['cid'])&&is_numeric($_GET['cid'])){
	$blogcategoryid=$_GET['cid'];
	$testquery="SELECT * FROM blogcategories WHERE id=$blogcategoryid";
	$testrun=mysql_query($testquery)or die(mysql_error()." Line 91");
	$testnumrows=mysql_num_rows($testrun);
if($testnumrows<1){
	header('location:index.php');
}
$blogsingle=getSingleBlogCategory($blogcategoryid);
$blogtype=getSingleBlogType($blogsingle['blogtypeid']);
$blogname=$blogtype['name'];
unset($_SESSION['cid']);
$_SESSION['cid']=$blogcategoryid;

$blogout=getAllBlogEntries("viewer","",$blogcategoryid,"category");
// echo $blogcategoryid;
// echo "<br>".$blogout['chiefquery'];
$outs=paginate($blogout['chiefquery']);
}elseif(isset($_SESSION['cid'])&&$_SESSION['cid']!==""&&is_numeric($_SESSION['cid'])){
$blogcategoryid=$_SESSION['cid'];
$blogsingle=getSingleBlogCategory($blogcategoryid);
$blogtype=getSingleBlogType($blogsingle['blogtypeid']);
$blogname=$blogtype['name'];
$blogout=getAllBlogEntries("viewer","",$blogcategoryid,"category");
$outs=paginate($blogout['chiefquery']);
}else{
	header('location:index.php');
}
$subimgpos='';
$blogbanner="";
$blogthemestyle="";
$topdisp="";
$maincontentstyle="";
$descriptor="";
$subscribe='
Subscribe to the category <b>'.$blogsingle['catname'].'</b> of the <b>'.$blogname.'</b> blog.
<form name="csiblogsubscription" method="POST" onSubmit="return false;" action="./snippets/basicsignup.php">
	<input type="hidden" name="entryvariant" value="categorysubscription"/>
	<input type="hidden" name="categoryid" value="'.$blogcategoryid.'"/>
	<div id="formend"><input type="text" style="text-align:center;"name="email" placeholder="Enter email here..." value=""class="curved"/></div>
	<div id="formend"><input type="button" class="submitbutton" name="categorysubscription" value="Subscribe"/></div>
</form>
';
if($blogsingle['blogtypeid']==3){
	#for project fix nigeria styling control.
	$maincontentstyle='style=""';
	$blogthemestyle='name="mainbodyholdtwo"';
	$blogbanner="./images/fsabannermini.jpg";
	$subimgpos='
<div id="subimglogo" class="subimgposfour">
	<img src="./images/fsasmall.PNG" style="width: 100%;position: relative;left: 5px;top: 3px;" class="">
</div>
	';
$descriptor=" ".$blogsingle['catname']." | Frankly Speaking Africa is a social reformation project enacted to transform the mindset and thinking of Africans towards value creation, patriotism and discipline A national call to stop the blame game and take responsibility for nation building";
}elseif ($blogsingle['blogtypeid']==2) {
	# for csi styling control
	$subimgpos='
<div id="subimglogo" class="subimgposthree">
	<img src="./images/csi2.png" style="width: 100%;position: relative;left: 5px;top: 3px;" class="">
</div>
	';
	$topdisp='
<div id="boastit">
				The No. 1 Christian Blog in Nigeria!
			</div>
			<div id="tenethold">
				Purpose - Prayer - Praise - Power - Perfection - Purity - Prosperity - Peace.
			</div>
	';
	$maincontentstyle="";
	$blogthemestyle='name="mainbodycsihold"';
$blogbanner="./images/CSI bannera.jpg";
$descriptor=" ".$blogsingle['catname']." | Welcome to Christ Society International Outreach; a social reformation project packaged to effect physical, mental and spiritual transformation for mankind.";
}elseif ($blogsingle['blogtypeid']==1) {
	# code...
$subimgpos='
<div id="subimglogo" class="subimgpostwo">
	<img src="./images/franklyspeakingtwo.png" style="width: 100%;position: relative;left: 5px;top: 3px;" class="">
</div>
';
	$maincontentstyle='style="background-color: #EBEBEB;"';
	$blogthemestyle='';
$blogbanner="./images/franklyspeakingbannera.jpg";
$descriptor=" ".$blogsingle['catname']." | Visit this exciting blog for daily acccess to Muyiwa's business and career radio and television talk-show content that give you Insights, ideas and strategies for superior";
}elseif ($blogsingle['blogtypeid']==4) {
	# code...
$subimgpos='
<div id="subimglogo" class="subimgposfive">
  <img src="./images/ownyourownthree.png" style="width: 91%;position: relative;left: 3px;top: 0px;" class=""/>
</div>
';
	$maincontentstyle='style=""';
	$blogthemestyle='name="mainblogoyohold"';
$blogbanner="./images/Own Your Own Webbannera.jpg";
$descriptor=" ".$blogsingle['catname']." | Visit this exciting blog for detailed entrepreneurship workshop information that will teach you how to Start, Build, Manage or Turn-Around any Business for excellent performance and profit in Nigeria";
}
?>
<!DOCTYPE html/>
<html>
<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $blogsingle['catname']." | ".$blogname?></title>
<meta name="keywords" content="<?php echo $blogsingle['catname'].", ".$blogname?>?>"/>
<meta name="description" content="<?php echo $descriptor?>">
<link rel="stylesheet" href="./stylesheets/muyiwamain.css"/>
<link rel="shortcut icon" type="image/" href="./images/muyiwaslogo.png"/>
<script src="./scripts/jquery.js"></script>
<script src="./scripts/mylib.js"></script>
<script src="./scripts/muyiwasblog.js"></script>
<script src="./scripts/formchecker.js"></script>
<?php include('./snippets/tinymce.php');?>
<?php include_once("./snippets/ga.php");?>

</head>
<body <?php echo $blogthemestyle;?>>
	<div id="main">
	<?php include("./snippets/facebooksdk.php");?>
		<div id="toppanel">
				<div id="mainlogopanel">
			<div id="mainimglogo">
				<?php include('./snippets/muyiwalogocontrol.php');?>
				
			</div>

		<?php echo $subimgpos;?>
		</div>
		<div id="linkspanel">
			<ul>
				<?php include("./snippets/toplinks.php");?>
			</ul>
		</div>
	</div>
<div id="contentpanel">
	<div id="contenttop">
		<div id="contenttopportraithold" style="width:100%;">
			<a href=""><img src="<?php echo $blogbanner;?>" class="total"/></a>
		</div>
	</div>
	<div id="contentmiddle">
		<?php echo $topdisp;?>
		<div id="maincontenthold" <?php echo $maincontentstyle;?>>
			<div id="paginationhold">
				<div class="meneame">
					<div class="pagination"><?php  echo $outs['pageout']; ?></div>
					<div class="pagination">
						<?php echo $outs['usercontrols'];?>
					</div>
				</div>
			</div>
			<?php
			if($blogout['numrows']>0){
				$blogout2=getAllBlogEntries("viewer",$outs['limit'],$blogcategoryid,"category");
				echo $blogout2['vieweroutput'];
			}else{
				echo "No Entries for this category yet";
			}				
			?>
			<div id="paginationhold">
				<div class="meneame">
					<div class="pagination"><?php echo $outs['pageout']; ?></div>
				</div>

			</div>
		</div>
		<div id="adcontentholder">
			<div id="adcontentholdlong" name="popularposts">
				Popular Posts
				<?php echo $blogout['popularposts'];?>
			</div>
			<div id="adcontentholdlong" name="feedjit">
				Visitors
				<?php include './snippets/feedjit.php';?>  
			</div>
<!-- 			<div id="adcontentholdshort" name="subscription">
				Subscribe<br>
					<?php echo $subscribe;?>
			</div> -->
			<div id="adcontentholdlong" name="jumiaadvert">
				Advert
				<a href="http://www.jumia.com.ng/best-sellers-for-books/?wt_af=ng.affiliate.cj.sale.books.banner&utm_source=cj&utm_medium=affiliate&utm_campaign=books&utm_term=banner" target="_blank"><img src="./images/adverts/jumiabookad.jpg"/></a>				
			</div>
			<div id="adcontentholdshort" name="currencyconvert">
				CurrencyConverter
				<?php include './snippets/currencyconverter.php';?> 
			</div>
			<div id="adcontentholdlong" name="jumiaadvert">
				Advert
				<a href="http://www.jumia.com.ng/samsung/?wt_af=ng.affiliate.cj.sale.electronics.banner&utm_source=cj&utm_medium=affiliate&utm_campaign=electronics&utm_term=banner" target="_blank"><img src="./images/adverts/juiasamsung.jpg"/></a>
			</div>
			<div id="adcontentholdlong" name="jumiaadvert">
				Listen
				<img src="./images/franklyspeakingtime.png"/>
			</div>
			<div id="adcontentholdlong" name="facebooklikebox" style="padding-top: 12px;background: #FFF;">
				<div class="fb-like-box" data-href="https://www.facebook.com/FranklySpeakingWithMuyiwaAfolabi" data-width="250" data-height="250" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>
			</div>
		</div>
	</div>
</div>
	<div id="footerpanel">
		<div id="footerpanelcontent">
			<div id="copyright">
	<?php include('./snippets/footer.php');?>
			</div>
		</div>
	</div>
	</div>
</body>
</html>