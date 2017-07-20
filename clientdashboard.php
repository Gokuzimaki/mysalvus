<?php  
session_start();
include('./snippets/connection.php');
if(strpos($host_target_addr, "localhost/")||strpos($host_target_addr, "wamp")){
  	// for local server
	include('./snippets/cronit.php');
}
/*$logpart=md5($host_addr);
if (isset($_SESSION['logcheck'.$logpart.''])&&$_SESSION['logcheck'.$logpart.'']=="on"||$_SESSION['logcheck'.$logpart.'']==""||!isset($_SESSION['logcheck'.$logpart.''])) {
	header('location:index.php?error=true');
}
$uid=$_SESSION['aduid'.$logpart.'']?$_SESSION['aduid'.$logpart.'']:header('location:index.php?error=nosession');
$alevel=$_SESSION['accesslevel'.$logpart.''];*/
// echo $_SESSION['logcheck'.$logpart.''];
$mview="";
$sview="";
if(isset($_GET['sview'])&&isset($_GET['mview'])){
				$sview=$_GET['sview'];
				$mview=$_GET['mview'];
				// echo $sview." ".$mview;
}
$userdataout="";
$userdata="";
if(isset($_SESSION['clienthmysalvus'])){
	$uid=$_SESSION['clientimysalvus'.$_SESSION['clienthmysalvus'].''];
	$userdata=getSingleUserPlain($uid);
}else{
	header('location:clientlogin.php');
}
// include('./snippets/useraccessleveloutput.php');
if(!isset($panelcontrolstyle)){
	$panelcontrolstyle=array();
	$panelcontrolstyle['options']="<h4>No priviledges Attached</h4>";
}
	// produce Notification data
	$mnotoutput="";
	$fnotoutput="";
	if(function_exists('notificationsPlainArray')){
		if(isset($_GET['compid'])&&is_numeric($_GET['compid'])){
			$i=$_GET['compid'];
			$rtype=isset($_GET['rtype'])&&$_GET['rtype']?$_GET['rtype']:"";
			$type=isset($_GET['type'])&&$_GET['type']?$_GET['type']:"";
			$ut=isset($_GET['v'])&&$_GET['v']?$_GET['v']:"";
			$d=isset($_GET['d'])&&$_GET['d']?$_GET['d']:0;
			$curnotification=notificationsPlainArray($i,$rtype,$type,$ut,$d);
			// for one time notifications
			$_SESSION['lastsuccess']=isset($_SESSION['lastsuccess'])&&$_SESSION['lastsuccess']==0&&$_SESSION['lastsuccess']!=="null"?1:"null";
			if($_SESSION['lastsuccess']==1){
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
		                    <p>Last action was carried out successfully on </p>
		                  </div>
		                  <div class="modal-footer">
		                  	<p>Click/tap the close button in the dialog box to dismiss</p>
		                  </div>
		                </div><!-- /.modal-content -->
		              </div><!-- /.modal-dialog -->
		            </div><!-- /.modal -->
				';
				if (!isset($_SESSION['notification'.$uid.''])){
					$_SESSION['notification'.$uid.'']=array();	
				}
				$_SESSION['notification'.$uid.''][count($_SESSION['notification'.$uid.''])]=$curnotification['output'];
				unset($_SESSION['lastsuccess']);
			}
		}
		if (isset($_SESSION['notification'.$uid.''])){

			if(count($_SESSION['notification'.$uid.''])>0){
				for ($i = count($_SESSION['notification'.$uid.'']); $i > count($_SESSION['notification'.$uid.''])-6; $i--) {
					if(isset($_SESSION['notification'.$uid.''][$i])&&$_SESSION['notification'.$uid.''][$i]!==""){
						// echo $i;
						$coutput="<p>".$_SESSION['notification'.$uid.''][$i]."</p>";
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

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Client Dashboard</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- <link href="<?php echo $host_addr;?>stylesheets/napstandmain.css" rel="stylesheet" type="text/css" /> -->
    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->

    <!-- Bootstrap 3.3.2 -->
    <link href="<?php echo $host_addr;?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href='<?php echo $host_addr;?>plugins/select2/dist/css/select2-bootstrap.css' type="text/css"/>
	<link async href="<?php echo $host_addr;?>stylesheets/lightbox.css" rel="stylesheet"/>
    <link href="<?php echo $host_addr;?>stylesheets/my-color.css" rel="stylesheet" type="text/css"/>
	<!-- daterange picker -->
	<link href="<?php echo $host_addr;?>plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
	<!-- daterange picker -->
	<link href="<?php echo $host_addr;?>plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
	<!-- Bootstrap time Picker -->
	<link href="<?php echo $host_addr;?>plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet"/>
	<!-- Bootstrap Date-time Picker -->
	
    <!-- Font Awesome Icons -->
    <link href="<?php echo $host_addr;?>icons/font-awesome/css/font-awesome.min.css" rel="stylesheet"  />
    <!-- Ionicons -->
    <link href="<?php echo $host_addr;?>icons/ionicons/css/ionicons.min.css" rel="stylesheet"  />
    <!-- Select2 (Selcetion customizer) -->
    <link href='<?php echo $host_addr;?>plugins/select2/dist/css/select2.min.css' rel="stylesheet" />
    <!-- Bootstrap datetimepicker -->
    <link href='<?php echo $host_addr;?>plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css' rel="stylesheet" />
    <!-- Jquery Sortable -->
    <!-- <link href='<?php echo $host_addr;?>plugins/jquery-sortable/css/jquery-sortable.css' rel="stylesheet" /> -->
    <!-- Theme style -->
    <link href="<?php echo $host_addr;?>admin/dist/css/AdminLTE.min.css" rel="stylesheet"  />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?php echo $host_addr;?>admin/dist/css/skins/_all-skins.min.css" rel="stylesheet"  />
    <link rel="shortcut icon" href="<?php echo $host_addr;?>favicon.ico"/>
    <!-- jQuery 2.1.3 -->
    <script src="<?php echo $host_addr;?>plugins/jQuery/jQuery-2.1.3.min.js"></script>
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
		    <a href="index.php" class="logo"><b>MySalvus</b></a>
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
			                  <img src="<?php echo $userdata['thumbimage'];?>" class="user-image" alt="User Image"/>
			                  <span class="hidden-xs">
			                      <?php echo $userdata['nameout'];?>
			                  </span>
			                </a>
			                <ul class="dropdown-menu">
			                  <!-- User image -->
			                  <li class="user-header">
			                    <img src="<?php echo $userdata['thumbimage'];?>" class="img-circle" alt="User Image"/>
			                    <p>
			                      <?php echo $userdata['nameout'];?>
			                      <!-- <small>Member since Nov. 2012</small> -->
			                    </p>
			                  </li>
			                  
			                  <!-- Menu Footer-->
			                  <li class="user-footer">
			                    <div class="pull-right">
			                      <a href="<?php echo $host_addr;?>snippets/logout.php?type=<?php echo $userdata['usertype'];?>" class="btn btn-default btn-flat">Sign out</a>
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
	              <img src="<?php echo $userdata['thumbimage'];?>" class="img-circle" alt="User Image" />
	            </div>
	            <div class="pull-left info">
	              <p>
						<?php echo $userdata['nameout'];?>
	              </p>
	            </div>
	          </div>
	          <!-- search form -->
	          
	          <!-- /.search form -->
	          <!-- sidebar menu: : style can be found in sidebar.less -->
	          <ul class="sidebar-menu">
		            <li class="header">MENU</li>
	          		<?php //echo $panelcontrolstyle['options']?>
		            <li class="treeview">
		              <a href="#" appdata-otype="mainlink" appdata-type="menulinkitem" appdata-name="editsingleclientacc" appdata-fa='<i class="fa fa-user"></i>' appdata-pcrumb="Profile">
		                <i class="fa fa-briefcase"></i> <span>Profile</span>
		              </a>
		            </li>
		            <li class="treeview">
		              <a href="#" appdata-otype="mainlink">
		                <i class="fa fa-sticky-note"></i> <span>Cases</span> <i class="fa fa-angle-left pull-right"></i>
		              </a>
		              <ul class="treeview-menu plain">
		                <li><a href="#Incidents" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="clientincidents" appdata-fa='<i class="fa fa-user-circle"></i>' appdata-pcrumb="Cases  > Latest Incidents "><i class="fa fa-user-circle"></i> Latest Incidents</a></li>
		                <li><a href="#Case Requests" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="caserequests" appdata-fa='<i class="fa fa-user-circle"></i>' appdata-pcrumb="Cases  > Case Requests "><i class="fa fa-user-circle"></i> Case Requests</a></li>
		                <li><a href="#Ongoing Cases" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="ongoingcases" appdata-fa='<i class="fa fa-user-circle"></i>' appdata-pcrumb="Cases  > Ongoing Cases "><i class="fa fa-user-circle"></i> Ongoing Cases</a></li>
		                <li><a href="#Cases transfer" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="casetransfers" appdata-fa='<i class="fa fa-user-circle"></i>' appdata-pcrumb="Cases  > Cases Transfers"><i class="fa fa-user-circle"></i> Cases Transfer</a></li>
		                <li>
		                	<a href="#Inbound Case Transfer" appdata-otype="sublink" 
		                	appdata-type="menulinkitem" appdata-name="inboundtransfers" 
		                	appdata-fa='<i class="fa fa-user-circle"></i>' 
		                	appdata-pcrumb="Cases > Transfer Request">
		                	<i class="fa fa-user-circle"></i> Transfer Requests</a>
		                </li>
		              </ul>
		            </li>
		            <li class="treeview">
		              <a href="#" appdata-otype="mainlink">
		                <i class="fa fa-universal-access"></i> <span>Reports</span> <i class="fa fa-angle-left pull-right"></i>
		              </a>
		              <ul class="treeview-menu plain">
		                <li><a href="#New Report" appdata-otype="sublink" appdata-type="menulinkitem" appdata-name="newcasereports" appdata-fa='<i class="fa fa-universal-access"></i>' appdata-pcrumb="Reports > New "><i class="fa fa-search"></i> New Case Report Entry</a></li>
		                <li><a href="#Edit Previous Reports" appdata-otype="sublink" 
		                	appdata-type="menulinkitem" appdata-name="editcasereports" 
		                	appdata-fa='<i class="fa fa-universal-access"></i>' 
		                	appdata-pcrumb="Reports > View ">
		                	<i class="fa fa-search"></i> View Case Report Entries</a></li>
		              </ul>
		            </li>
		            <li class="treeview">
		              <a href="<?php echo $host_addr;?>snippets/logout.php?type=serviceprovider" appdata-otype="mainlink" appdata-type="menulinkitem" appdata-name="logout">
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
	              <h3 class="box-title">Welcome</h3>
	              <div class="box-tools pull-right">
	                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
	                <!-- <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button> -->
	              </div>
	            </div>
	            <div class="box-body">	
	            	Welcome to your admin panel, please use the options to your left to carry out actions here
	            	<?php
						$outsusers=getAllUsers("viewer","","user");
						$userid="";
						$formtruetype="caseform";
						$userset="true";
						$clientid="";
						$incid="";
						if(isset($userset)&&$userset=="true"){
							$clientid=$_SESSION['clientimysalvus'.$_SESSION['clienthmysalvus'].''];
							$type[0]="clientview";
							$type[1]=$clientid;
							$outsdata=getAllIncidents("viewer","",$type,"adminoutputtwo2","seletclientcase");
						}else{
							$userset="";
						}

	            		
	            	?>
	            </div><!-- /.box-body -->
	            <div class="box-footer">

	            </div><!-- /.box-footer-->
        		<?php echo $fnotoutput;?>
          </div><!-- /.box -->
          <!-- Stats Box end -->
          <!-- form box start -->
          	
			
          <!-- form box end -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <!-- General Modal display section -->
      <div id="mainPageModal" class="modal fade" data-backdrop="false" data-show="true" data-role="dialog">
      	<div class="modal-dialog">
      		<div class="modal-content">
		      	<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
					<h4 class="modal-title">Message</h4>
		        </div>
		      	<div class="modal-body">

		      	</div>
		      	<div class="modal-footer">
		      		<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
		      	</div>
		    </div>
      	</div>
      </div>
      <!-- end modal display -->
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Administrator Central.</b>
        </div>
        <strong>Copyright &copy; 2014-<?php echo date('Y');?> <a href="index.php">MySalvus</a>.</strong> All rights reserved. Developed by Okebukola Olagoke.
      </footer>
    </div><!-- <?php echo $host_addr;?>wrapper -->

    
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo $host_addr;?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo $host_addr;?>scripts/mylib.js" type="text/javascript"></script>
    <script src="<?php echo $host_addr;?>scripts/formchecker.js" type="text/javascript"></script>
    <!-- Select2 (Selcetion customizer) -->
    <script src='<?php echo $host_addr;?>plugins/select2/dist/js/select2.full.min.js'></script>
    <!-- Bootpag (oostrap paginator) -->
    <script src='<?php echo $host_addr;?>plugins/bootpag/jquery.bootpag.min.js'></script>
    <!-- SlimScroll -->
    <script src="<?php echo $host_addr;?>plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='<?php echo $host_addr;?>plugins/fastclick/fastclick.min.js'></script>
    <!-- InputMask -->
    <script src="<?php echo $host_addr;?>plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
    <script src="<?php echo $host_addr;?>plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
    <!-- date-range-picker -->
    <script src="<?php echo $host_addr;?>plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
    <script src="<?php echo $host_addr;?>plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <!-- date-picker -->
    <script src="<?php echo $host_addr;?>plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
    <!-- bootstrap time picker -->
    <script src="<?php echo $host_addr;?>plugins/timepicker/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <!-- Moment js -->
    <script src="<?php echo $host_addr;?>plugins/moment/moment.js" type="text/javascript"></script>
    <!-- bootstrap Date-time picker -->
    <script src="<?php echo $host_addr;?>plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js" type="text/javascript"></script>
    <!-- iCheck 1.0.1 -->
    <script src="<?php echo $host_addr;?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>

    <script src="<?php echo $host_addr;?>plugins/knob/jquery.knob.js" type="text/javascript"></script>
    <!-- Sparkline -->
    <script src="<?php echo $host_addr;?>plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>

    
	
	<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
	<!--[if (gte IE 8)&(lt IE 10)]>
	<script src="js/cors/jquery.xdr-transport.js"></script>
	<![endif]-->
	<!-- end -->
    <!-- AdminLTE App -->
    <script src="<?php echo $host_addr;?>admin/dist/js/app.js" type="text/javascript"></script>
    <script src="<?php echo $host_addr;?>scripts/lightbox.js" type="text/javascript"></script>
    <script src="<?php echo $host_addr;?>scripts/homonculusadmin.js" type="text/javascript"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $host_addr;?>scripts/lib/tinymce/jquery.tinymce.min.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $host_addr;?>scripts/lib/tinymce/tinymce.min.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $host_addr;?>scripts/lib/tinymce/basic_config.js"></script>
  </body>
</html>