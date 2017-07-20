<?php  
	session_start();
	include('../snippets/connection.php');
	// echo "$rurladmin<br>";
	// $_SESSION['rurladmin']="../fjcadmin/adminindex.php";
	/*echo $rurladmin;*/
	$logpart=md5($host_addr);
	
	if (isset($_SESSION['logcheck'.$logpart.''])&&$_SESSION['logcheck'.$logpart.'']=="on"||$_SESSION['logcheck'.$logpart.'']==""||!isset($_SESSION['logcheck'.$logpart.''])) {
		header('location:index.php?error=true');
	}
	if(strpos($host_target_addr, "localhost/")||strpos($host_target_addr, "wamp")){
	  	// for local server
		include('../snippets/cronit.php');
	}
	$uid=$_SESSION['aduid'.$logpart.'']?$_SESSION['aduid'.$logpart.'']:header('location:index.php?error=nosession');
	// $uid=1;
	if($_SESSION['accesslevel'.$logpart.'']!==0&&$_SESSION['accesslevel'.$logpart.'']!==3){
		// header('location:index.php?error=accessdenied');
	}
	// create notification set entries
	// unset($_SESSION['notification'.$uid.''.$logpart.'']);
	if (!isset($_SESSION['notification'.$uid.''.$logpart.''])){
		$_SESSION['notification'.$uid.''.$logpart.'']=array();
	}
	// produce Notification data
	$mnotoutput="";
	$fnotoutput="";
	$soutput="";
	$emsg="";
	if(function_exists('notificationsPlainArray')){
		if(isset($_GET['compid'])&&is_numeric($_GET['compid'])){
			$i=$_GET['compid'];
			$rtype=isset($_GET['rtype'])&&$_GET['rtype']?$_GET['rtype']:"";
			$type=isset($_GET['type'])&&$_GET['type']?$_GET['type']:"";
			$ut=isset($_GET['v'])&&$_GET['v']?$_GET['v']:"";
			$d=isset($_GET['d'])&&$_GET['d']?$_GET['d']:0;
			if($type=="createincidentadmin"){
				$emsg='The incident has been created on the platform and service 
					providers can proceed to attend to it.';
			}else if($type=="edituseraccadmin"){
				$emsg='All changes to the specified user account have been saved';
			}else if($type=="editincidentadmin"){
				$emsg='The incident has been updated.';
			}else if($type=="createcaseadmin"){
				$emsg='A case entry was created for the specified service provider.';
			}
			$curnotification=notificationsPlainArray($i,$rtype,$type,$ut,$d);
			// for one time notifications
			// $_SESSION['lastsuccess']=isset($_SESSION['lastsuccess'])&&$_SESSION['lastsuccess']==0&&$_SESSION['lastsuccess']!=="null"?1:"null";
			if(isset($_SESSION['lastsuccess'])&&$_SESSION['lastsuccess']==0){
				$soutput='
		            <script>
			            $(document).ready(function(){
			            	$("#notificationModal").modal("show");
			            });
		            </script>
					<div id="notificationModal" class="modal modal-success" role="dialog">
		              <div class="modal-dialog" role="document">
		                <div class="modal-content">
		                  <div class="modal-header">
		                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                    <h4 class="modal-title">Success</h4>
		                  </div>
		                  <div class="modal-body">
		                    <p>Last action was carried out successfully. '.$emsg.'</p>
		                  </div>
		                  
		                </div><!-- /.modal-content -->
		              </div><!-- /.modal-dialog -->
		            </div><!-- /.modal -->
				';
				$_SESSION['notification'.$uid.''.$logpart.''][count($_SESSION['notification'.$uid.''.$logpart.''])]=$curnotification['output'];
				unset($_SESSION['lastsuccess']);
			}
		}
		if (isset($_SESSION['notification'.$uid.''.$logpart.''])){

			if(count($_SESSION['notification'.$uid.''.$logpart.''])>0){
				for ($i = count($_SESSION['notification'.$uid.''.$logpart.'']); $i > count($_SESSION['notification'.$uid.''.$logpart.''])-6; $i--) {
					if(isset($_SESSION['notification'.$uid.''.$logpart.''][$i])&&$_SESSION['notification'.$uid.''.$logpart.''][$i]!==""){
						// echo $i;
						$coutput="<p>".$_SESSION['notification'.$uid.''.$logpart.''][$i]."</p>";
						$mnotoutput=$coutput.$mnotoutput;
					}
				}
				$fnotoutput='
					<div class="callout callout-success _nogreen _border_dark clearboth marginbottom">
	                    <h2>Notifications <small>Last Five</small></h2>
	                    '.$mnotoutput.'
	                  </div>
				';
			}
		}
	}
	$alevel=$_SESSION['accesslevel'.$logpart.''];
	// echo $alevel."<br>";
	// echo $_SESSION['logcheck'.$logpart.'']." logcheck<br>";
	// echo $_SESSION['accesslevel'.$logpart.'']." accesslevel<br>";
	// echo $_SESSION['aduid'.$logpart.'']." aduid<br>";
	$mview="";
	$sview="";
	if(isset($_GET['sview'])&&isset($_GET['mview'])){
		$sview=$_GET['sview'];
		$mview=$_GET['mview'];
		// echo $sview." ".$mview;
	}
	$uservice="none";
	$ubookings="none";
	$utestimony="none";
	$umessages="none";
	$ucomments="none";
	$comrows=0;
	// echo md5(3);
	// $comdata=getAllComments("inactivecount","","");
	// $comrows=$comdata['countout'];
	// $comrows>0?$ucomments="":$ucomments=$ucomments;
	$fullcomout=$comrows>0?'<small class="label pull-right bg-red mainsmall">'.$comrows.'</small>':"";
	$admindata=getAdmin($uid);
	// $userdataadmin=getAllUsers("viewer","");
	// $userdatatwoadmin=getAllUsers("inactiveviewer","");

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    
    <!-- Bootstrap 3.3.2 -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- daterange picker -->
	<link href="../plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
	<!-- daterange picker -->
	<link href="../plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
	<!-- Bootstrap time Picker -->
	<link href="../plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet"/>
	<!-- Bootstrap datetime Picker -->
	<link href="../plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet"/>
    <!-- Font Awesome Icons -->
    <link href="../icons/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="../icons/ionicons/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <!-- Select2 (Selcetion customizer) -->
    <link href='<?php echo $host_addr;?>plugins/select2/dist/css/select2.min.css' rel="stylesheet" type="text/css"/>
    <link href='<?php echo $host_addr;?>plugins/select2/dist/css/select2-bootstrap.min.css' rel="stylesheet" type="text/css"/>
        <!-- bootstrap slider -->
    <link href="../plugins/bootstrap-slider/slider.css" rel="stylesheet" type="text/css" />
    <link href="../stylesheets/my-color.css" rel="stylesheet" type="text/css"/>
	<link async href="<?php echo $host_addr;?>stylesheets/lightbox.css" rel="stylesheet"/>
	<link rel="shortcut icon" href="<?php echo $host_addr;?>/favicon.ico" />
	<!-- jQuery 2.1.3 -->
    <script src="../plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-yellow">
    <!-- Site wrapper -->
    <div class="wrapper">
		<header class="main-header">
		    <a href="index.php" class="logo"><b>MySalvus ADMIN</b></a>
		    <!-- Header Navbar: style can be found in header.less -->
		    <nav class="navbar navbar-static-top" role="navigation">
		      <!-- Sidebar toggle button-->
		      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </a>
		      <div class="navbar-custom-menu">
		            <ul class="nav navbar-nav">
		              
		              <!-- User Account: style can be found in dropdown.less -->
		              <li class="dropdown user user-menu">
			                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
			                  <img src="../images/default.gif" class="user-image" alt="User Image"/>
			                  <span class="hidden-xs">
			                      <?php echo $admindata['fullname'];?>
			                  </span>
			                </a>
			                <ul class="dropdown-menu">
			                  <!-- User image -->
			                  <li class="user-header">
			                    <img src="../images/default.gif" class="img-circle" alt="User Image" />
			                    <p>
			                      <?php echo $admindata['fullname'];?>
			                      <!-- <small>Member since Nov. 2012</small> -->
			                    </p>
			                  </li>
			                  
			                  <!-- Menu Footer-->
			                  <li class="user-footer">
			                    <div class="pull-right">
			                      <a href="../snippets/logout.php?type=admin" class="btn btn-default btn-flat">Sign out</a>
			                    </div>
			                  </li>
			                </ul>
		              </li>
		            </ul>
		      </div>
		    </nav>
		</header>

      <!-- =============================================== -->

      <!-- Left side column. contains the sidebar -->
       <aside class="main-sidebar">
	        <!-- sidebar: style can be found in sidebar.less -->
	        <section class="sidebar">
	          <!-- Sidebar user panel -->
	          <div class="user-panel">
	            <div class="pull-left image">
	              <img src="../images/default.gif" class="img-circle" alt="User Image" />
	            </div>
	            <div class="pull-left info">
	              <p>
						<?php echo $admindata['fullname'];?>
	              </p>
	            </div>
	          </div>
	          <!-- search form -->
	          <!-- <form action="#" method="get" name="mainsearchform" class="sidebar-form">
		            <div class="input-group">
		              <input type="text" name="q" class="form-control" placeholder="Search..."/>
		              <span class="input-group-btn">
		                <button type='button' name='mainsearch' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
		              </span>
		            </div>
		            <div class="input-group">
	                    <div class="input-group-btn">
	                      <button type="button" class="btn btn-warning dropdown-toggle customsearchbtn" data-name="searchbyspace" data-toggle="dropdown" aria-expanded="false">Search By <span class="fa fa-caret-down"></span></button>
	                      <input type="hidden" name="searchby" value=""/>
	                      <ul class="dropdown-menu customdrop" data-type="appsearchbyoption">
	                        <li><a href="##Search" data-name="mainsearchbyoption" data-value="recruitname" data-placeholder="Firstname Othernames">Recruit Name</a></li>
	                        <li><a href="##Search" data-name="mainsearchbyoption" data-value="recruitstatus" data-placeholder="active or inactive">Recruit Status</a></li>
	                        <li><a href="##Search" data-name="mainsearchbyoption" data-value="orgname" data-placeholder="Organisation Name">Organisation Name</a></li>
	                        <li><a href="##Search" data-name="mainsearchbyoption" data-value="blogtitle" data-placeholder="Blog title Search...">Blog title</a></li>
	                        <li><a href="##Search" data-name="mainsearchbyoption" data-value="blogintro" data-placeholder="Intro Paragraph Search...">Blog Intro</a></li>                        
	                        <li><a href="##Search" data-name="mainsearchbyoption" data-value="blogentry" data-placeholder="Blog Post Search...">Blog Post</a></li>                        
	                        <li class="divider"></li>
	                        <li><a href="#">Separated link</a></li>
	                      </ul>
	                    </div>
	                </div>
	          </form> -->
	          <!-- /.search form -->
	          <!-- sidebar menu: : style can be found in sidebar.less -->
	          <ul class="sidebar-menu">
		            <li class="header">MENU</li>
		            <!-- <li class="treeview">
		              <a href="#" appdata-otype="mainlink" appdata-type="">
		              <i class="fa fa-user"></i> <span>Clients</span> <i class="fa fa-angle-left pull-right"></i>
		              </a>
		              <ul class="treeview-menu">
		                <li><a href="#New" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="newclient" appdata-crumb='New Client' appdata-fa='<i class="fa fa-user"></i>' appdata-pcrumb="Client"><i class="fa fa-plus"></i> New</a></li>
		                <li><a href="#Edit" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="editclient" appdata-crumb='Edit Client' appdata-fa='<i class="fa fa-user"></i>' appdata-pcrumb="Client"><i class="fa fa-gear"></i> Edit</a></li>
		              </ul>
		            </li> -->
					<li class="treeparent treeview">
						<a href="#">
			                <i class="fa fa-object-group"></i> <span>MySalvus Profiles</span> <i class="fa fa-angle-left pull-right"></i>
			            </a>
						<ul class="treeview-menu treeview-menu-parent">
							<li class="treeview treeparent-child">
				              <a href="#" appdata-otype="mainlink">
				                <i class="fa fa-sticky-note-o"></i> <span>Users</span> <i class="fa fa-angle-left pull-right"></i>
				              </a>
				              <ul class="treeview-menu">
				                <li><a href="#Create Salvus User" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="usersnew" appdata-fa='<i class="fa fa-user-plus"></i>' appdata-pcrumb="MySalvus Profiles > Users "><i class="fa fa-user-plus"></i> New</a></li>
				                <li><a href="#View Salvus User" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="usersedit" appdata-fa='<i class="fa fa-users"></i>' appdata-pcrumb="MySalvus Profiles > Users "><i class="fa fa-gear"></i> View</a></li>
				              </ul>
				            </li>
				            <li class="treeview treeparent-child">
				              <a href="#" appdata-otype="mainlink">
				                <i class="fa fa-sticky-note-o"></i> <span>Service Providers</span> <i class="fa fa-angle-left pull-right"></i>
				              </a>
				              <ul class="treeview-menu">
				                <li><a href="#Create Salvus ServiceProvider" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="serviceprovidersnew" appdata-fa='<i class="fa fa-briefcase"></i>' appdata-pcrumb="MySalvus Profiles > ServiceProviders "><i class="fa fa-plus"></i> New</a></li>
				                <li><a href="#Edit Salvus ServiceProvider" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="serviceprovidersedit" appdata-fa='<i class="fa fa-briefcase"></i>' appdata-pcrumb="MySalvus Profiles > ServiceProviders "><i class="fa fa-gear"></i> View</a></li>
				              </ul>
				            </li>
						</ul>
					</li>
					<li class="treeparent treeview">
						<a href="#">
			                <i class="fa fa-object-group"></i> <span>Incidents&Cases</span> <i class="fa fa-angle-left pull-right"></i>
			            </a>
						<ul class="treeview-menu treeview-menu-parent">
							<li class="treeview treeparent-child">
				              	<a href="#" appdata-otype="mainlink">
				                	<i class="fa fa-user-circle"></i> <span>Incidents</span> <i class="fa fa-angle-left pull-right"></i>
				              	</a>
				              	<ul class="treeview-menu">
					                <li><a href="#Create Incidents" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="incidentsnew" appdata-fa='<i class="fa fa-user-circle"></i>' appdata-pcrumb="Incidents & Cases > Incidents "><i class="fa fa-plus"></i> New</a></li>
					                <li><a href="#Edit Incidents" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="incidentsedit" appdata-fa='<i class="fa fa-user-circle"></i>' appdata-pcrumb="Incidents & Cases > Incidents "><i class="fa fa-gear"></i> Edit</a></li>
					                <li><a href="#Edit Saved Incidents" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="incidentseditsaved" appdata-fa='<i class="fa fa-user-circle"></i>' appdata-pcrumb="Incidents & Cases > Incidents "><i class="fa fa-save"></i> Edit Saved</a></li>
					                <li><a href="#Export Incidents" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="expincident" appdata-fa='<i class="fa fa-user-circle"></i>' appdata-pcrumb="Incidents & Cases > Export Incidents "><i class="fa fa-share"></i> Export</a></li>
				              	</ul>
				            </li>
				            <li class="treeview treeparent-child">
				              	<a href="#" appdata-otype="mainlink">
				                	<i class="fa fa-search"></i> <span>Cases</span> <i class="fa fa-angle-left pull-right"></i>
				              	</a>
				              	<ul class="treeview-menu">
				               		<li><a href="#Create/Edit Cases" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="cases" appdata-fa='<i class="fa fa-search"></i>' appdata-pcrumb="Incidents & Cases > Cases Assignment/Request "><i class="fa fa-search"></i> Case Assignment/Request</a></li>
				              	</ul>
				            </li>
						</ul>
					</li>
					<li class="treeparent treeview">
						<a href="#">
			                <i class="fa fa-object-group"></i> <span>Pages</span> <i class="fa fa-angle-left pull-right"></i>
			            </a>
						<ul class="treeview-menu treeview-menu-parent">
				            <li class="treeview treeparent-child">
					          <a href="#" appdata-otype="mainlink" appdata-type="">
					            <i class="fa fa-gift"></i> <span>Defaults</span> <i class="fa fa-angle-left pull-right"></i>
					          </a>
					          <ul class="treeview-menu">
					            <li><a href="#Default Site Data" appdata-otype="sublink" appdata-type="menulinkitem" 
					            	appdata-name="_gdunit"
					            	appdata-datamap='{"vnt":"contententryupdate","mt":"defaultdata","vt":"create",
				                	"pr":"snippets/defaultcontents.php","preinit":true}' 
					            	appdata-crumb="Social Accounts" appdata-fa="<i class='fa fa-gift'></i>" appdata-pcrumb="Defaults/Social"><i class="fa fa-street-view"></i> Default Data</a></li>
					            <!-- <li><a href="#Defaultinformation" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="defaultinfo" appdata-crumb="DefaultInfo" appdata-fa="<i class='fa fa-gear fa-spin'></i>" appdata-pcrumb="Defaults/Social"><i class="fa fa-plus"></i> Default Information</a></li> -->
					            <!-- <li><a href="#Business Hours" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="businesshours" appdata-crumb="Business Hours" appdata-fa="<i class='fa fa-clock-o'></i>" appdata-pcrumb="Defaults/Social"><i class="fa fa-plus"></i> Business Hours</a></li> -->
					          </ul>
					        </li>
							<li class="treeview treeparent-child">
								<a href="#" appdata-otype="mainlink">
									<i class="fa fa-question"></i> <span>FAQ</span> <i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu menu-open">
									<li><a href="#New" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="newfaq" appdata-crumb="New FAQ" appdata-fa="<i class=&quot;fa fa-question&quot;></i>" appdata-pcrumb="Frequently Asked Questions"><i class="fa fa-plus"></i> New FAQ</a></li>
									<li><a href="#Edit" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="editfaq" appdata-crumb="Edit FAQ" appdata-fa="<i class=&quot;fa fa-question&quot;></i>" appdata-pcrumb="Frequently Asked Questions"><i class="fa fa-gear"></i> Edit FAQ</a></li>
								</ul>
					        </li>
						</ul>
					<li class="treeparent treeview">
                        <a href="#">
                            <i class="fa fa-sliders"></i> <span>Blog</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu treeview-menu-parent">
                            <li class="treeview treeparent-child">
                              <a href="#" appdata-otype="mainlink" >
                                <i class="fa fa-sliders"></i> <span>Blog Type</span> <i class="fa fa-angle-left pull-right"></i>
                              </a>
                              <ul class="treeview-menu">
                                <li><a href="#New" appdata-otype="sublink" appdata-type="menulinkitem" 
                                	appdata-name="_gdunit" 
				                	appdata-datamap='{"vnt":["createblogtype","editblogtype"],
				                	"mt":["blogtype","blogtype"],
				                	"vt":"blogtype_crt",
				                	"pr":"snippets/forms/blogmetadatas.php","preinit":false}'   
                                	appdata-fa='<i class="fa fa-sliders"></i>' 
                                	appdata-pcrumb="Blog Type">
                                	<i class="fa fa-plus"></i> Create/Edit</a></li>
                                <!-- <li><a href="#Edit" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="editblogtype" appdata-fa='<i class="fa fa-sliders"></i>' appdata-pcrumb="Blog Type"><i class="fa fa-gear"></i> Edit</a></li> -->
                              </ul>
                            </li>
                            <li class="treeview treeparent-child">
                              <a href="#" appdata-otype="mainlink" >
                                <i class="fa fa-sitemap"></i> <span>Blog Category</span> <i class="fa fa-angle-left pull-right"></i>
                              </a>
                              <ul class="treeview-menu">

                                <li><a href="#New" appdata-otype="sublink" appdata-type="menulinkitem" 
                                	appdata-name="_gdunit" 
				                	appdata-datamap='{"vnt":["createblogcategory","editblogcategory"],
				                	"mt":["blogcategory","blogcategory"],
				                	"vt":"blogcategory_crt",
				                	"pr":"snippets/forms/blogmetadatas.php","preinit":false}'
				                	appdata-fa='<i class="fa fa-sitemap"></i>' 
				                	appdata-pcrumb="Blog Category">
				                	<i class="fa fa-plus"></i> Create/Edit</a></li>
                                <!-- <li><a href="#Edit" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="editblogcategory" appdata-fa='<i class="fa fa-sitemap"></i>' appdata-pcrumb="Blog Category"><i class="fa fa-gear"></i> Edit</a></li> -->
                              </ul>
                            </li>
                            <li class="treeview treeparent-child">
                              <a href="#" appdata-otype="mainlink" >
                                <i class="fa fa-newspaper-o"></i> <span>Blog Post</span> <i class="fa fa-angle-left pull-right"></i>
                              </a>
                              <ul class="treeview-menu">
                                <!-- <li><a href="#New" appdata-otype="sublink" appdata-type="menulinkitem" 
                                	appdata-name="_gdunit" 
				                	appdata-datamap='{"vnt":["createblogpost","editblogpost"],
				                	"mt":["blogentries","blogentries"],
				                	"vt":"create",
				                	"pr":"snippets/forms/blogentries.php","preinit":false}' 
                                	appdata-fa='<i class="fa fa-text"></i>' 
                                	appdata-pcrumb="Blog Post"><i class="fa fa-plus"></i> Create/Edit Posts</a></li> -->

                                <li><a href="#New" appdata-otype="sublink" appdata-type="menulinkitem" 
                                	appdata-name="_gdunit" 
				                	appdata-datamap='{"vnt":["createblogpost","editblogpost"],
				                	"mt":["blogentries","blogentries"],
				                	"vt":"newpost_crt",
				                	"pr":"snippets/forms/blogentries.php","preinit":false}' 
                                	appdata-fa='<i class="fa fa-text"></i>' 
                                	appdata-pcrumb="Blog Post"><i class="fa fa-plus"></i> Create Post</a></li>
                                	
                                <li><a href="#New" appdata-otype="sublink" appdata-type="menulinkitem" 
                                	appdata-name="_gdunit" 
				                	appdata-datamap='{"vnt":["createblogpost","editblogpost"],
				                	"mt":["blogentries","blogentries"],
				                	"vt":"editpost_crt",
				                	"pr":"snippets/forms/blogentries.php","preinit":false}' 
                                	appdata-fa='<i class="fa fa-text"></i>' 
                                	appdata-pcrumb="Blog Post"><i class="fa fa-gear"></i> Edit Posts</a></li>
                                <li><a href="#Edit Scheduled Posts" appdata-otype="sublink" appdata-type="menulinkitem" 
                                	appdata-name="_gdunit" 
				                	appdata-datamap='{"vnt":["createblogpost","editblogpost"],
				                	"mt":["blogentries","blogentries"],
				                	"vt":"blogscheduled_crt",
				                	"pr":"snippets/forms/blogentries.php","preinit":false}' 
                                	appdata-fa='<i class="fa fa-text"></i>' 
                                	appdata-pcrumb="Scheduled Blog Post"><i class="fa fa-clock-o"></i> Scheduled Posts</a></li>
                                <li><a href="#Edit Featured Posts" appdata-otype="sublink" appdata-type="menulinkitem" 
                                	appdata-name="_gdunit" 
				                	appdata-datamap='{"vnt":["createblogpost","editblogpost"],
				                	"mt":["blogentries","blogentries"],
				                	"vt":"blogfeatured_crt",
				                	"pr":"snippets/forms/blogentries.php","preinit":false}' 
                                	appdata-fa='<i class="fa fa-text"></i>' 
                                	appdata-pcrumb="Featured Blog Post"><i class="fa fa-list"></i> Featured Posts</a></li>
                              </ul>
                            </li>
                            <li class="treeview treeparent-child">
                              <a href="#" appdata-otype="mainlink" >
                                <i class="fa fa-comment-o"></i> <span>Comments</span> 
                                    <?php echo $fullcomout;?>
                                <i class="fa fa-angle-left pull-right"></i>
                              </a>
                              <ul class="treeview-menu">
                                <li><a href="#AllComments" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="allcomments" appdata-fa='<i class="fa fa-comments-o"></i>' appdata-pcrumb="Comments"><i class="fa fa-cubes"></i> All</a></li>
                                <li><a href="#ActiveComments" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="activecomments" appdata-fa='<i class="fa fa-comments-o"></i>' appdata-pcrumb="Comments"><i class="fa fa-asterisk"></i> Active</a></li>
                                <li><a href="#PendingComments" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="inactivecomments" appdata-fa='<i class="fa fa-comments-o"></i>' appdata-pcrumb="Comments"><i class="fa fa-clock-o"></i> Pending</a></li>
                                <li><a href="#Disabledcomments" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="disabledcomments" appdata-fa='<i class="fa fa-comments-o"></i>' appdata-pcrumb="Comments"><i class="fa fa-ban"></i> Disabled</a></li>
                              </ul>
                            </li>
                        </ul>
                    </li>
					<li class="divider"></li>

					
		            <li class="treeview">
		              <a href="../snippets/logout.php?type=admin" appdata-otype="mainlink" appdata-type="menulinkitem" appdata-name="logout">
		                <i class="fa fa-sign-out"></i> <span>Logout</span>
		              </a>
		            </li>
	          </ul>
	        </section>
	        <!-- /.sidebar -->
      </aside>

      <!-- =============================================== -->

      <!-- Right side column. Contains the navbar and content of the page -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1 appdata-name="notifylinkheader">
            Welcome
            <small>Administrator</small>
          </h1>
          <ol class="breadcrumb" appdata-name="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- end last surveys taken box -->
          <!-- sTATS box -->
          <div class="box">
	            <div class="box-header with-border">
	              <h3 class="box-title">Admin Panel</h3>
	              <div class="box-tools pull-right">
	                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
	                <!-- <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button> -->
	              </div>
	            </div>
	            <div class="box-body">
	            	<?php
	            		$contentName = array(
							'portfoliogallerystream' => 'Portfolio Gallery'
						);
						$formtruetype="incidentformadmin";
						!isset($pagetype)?$pagetype="portfoliogallerystream":$pagetype=$pagetype;
						if(isset($entrytype)){
							$pagetype=$entrytype;
						}
						$contentname=isset($contentName["$pagetype"])?$contentName["$pagetype"]:"";
						$newcontentname=isset($contentName["$pagetype"])?substr($contentname, 0,strlen($contentname)-1):"";
						// $outsdata=getAllIncidents("admin","","","");
						// $outsusers=getAllUsers("viewer","","serviceprovider");
						// $typearr[0]="combo";
						// $typearr[1]="businessnature**state:Counselling**25";

						// $cspst=getAllUsers("admin","","serviceprovider",'',$typearr);

						// echo '<select>'.$cspst['selectiontwo'].'</select>';
						// $outsuser=getSingleUserPlain(2);
						// echo $outsuser['age'];
						// $pstrtest="";
						// $flooz2=base64_decode('TSYqLzdTL-9MtUFglIFBaPzIsTDwuMlooLzEqRy0+KzYlSCgyNEdMSTsmSk46NihCKE4gR0dGUTU4USs1SQpNSCktQ1IqUjI4LlxTTDBQNF9LOzJIWkAqLjs6IUc+LEpDOlg2QyhOI0lQVVBeSlY1XFBNTzdQV0EtOldMCjJdTEkmWFw5MUVdNyc6WFNYWExITlJPUyIK');

	            	?>
	            	<div class="row">
	            		<div class="col-lg-12">
	            			Welcome to your admin panel, please use the options to your left to carry out actions here
			            </div>
	            	</div>
        			<?php 
        				echo $fnotoutput;
        			?>
	            </div><!-- /.box-body -->
	            <div class="box-footer">
	              
	            </div><!-- /.box-footer-->
          </div><!-- /.box -->
          <!-- Stats Box end -->
          <!-- form test box start -->
          	
			
          <!-- form test box end -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Administrator Central.</b>
        </div>
        <strong>Copyright &copy; 2014-<?php echo date('Y');?> <a href="##">MySalvus</a>.</strong> All rights reserved. Developed by Okebukola Olagoke, Dream Bench Technologies.
      </footer>
    </div><!-- ../wrapper -->

    
    <!-- Bootstrap 3.3.2 JS -->
    <script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- jplayer -->
	<script src="../plugins/jPlayer/jquery.jplayer.min.js"></script>
	<!-- jquery ui widget -->
	<script src="../plugins/jQueryUI/jquery.ui.widget.js"></script>
    <!-- SlimScroll -->
    <script src="../plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='../plugins/fastclick/fastclick.min.js'></script>
    <!-- InputMask -->
    <script src="../plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
    <script src="../plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
    <script src="../plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
	<!-- bootstrap time picker -->
    <script src="../plugins/timepicker/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <!-- date-range-picker -->
	<script src="../plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
	<!-- // <script src="../plugins/daterangepicker/daterangepicker.js"></script> -->
    <!-- iCheck 1.0.1 -->
    <script src="../plugins/iCheck/icheck.min.js" type="text/javascript"></script>

    <script src="../plugins/knob/jquery.knob.js" type="text/javascript"></script>
    <!-- Bootstrap Slider -->
    <script src="../plugins/bootstrap-slider/bootstrap-slider.js" type="text/javascript"></script>
    <!-- Sparkline -->
    <script src="../plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
    <!-- Select2 (Selcetion customizer) -->
    <script src='<?php echo $host_addr;?>plugins/select2/dist/js/select2.full.min.js'></script>
    <!-- Moment js -->
    <script src="<?php echo $host_addr;?>plugins/moment/moment.js" type="text/javascript"></script>
	<!-- bootstrap Date-time picker -->
    <script src="<?php echo $host_addr;?>plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.js" type="text/javascript"></script>
    <script src="../scripts/lightbox.js" type="text/javascript"></script>
	<script language="javascript" type="text/javascript" src="../scripts/lib/tinymce/jquery.tinymce.min.js"></script>
    <script src="../scripts/mylib.js" type="text/javascript"></script>
    <script src="../scripts/formchecker.js" type="text/javascript"></script>
    <script src="../scripts/homonculusadmin.js" type="text/javascript"></script>
    <script language="javascript" type="text/javascript" src="../scripts/lib/tinymce/tinymce.min.js"></script>
    <script language="javascript" type="text/javascript" src="../scripts/lib/tinymce/basic_config.js"></script>
  	<?php echo $soutput;?>
	
	<!-- end -->
  </body>
</html>