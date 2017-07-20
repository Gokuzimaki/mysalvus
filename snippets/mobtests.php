<?php 
	include('connection.php');
?>
<!DOCTYPE html>
<html>
	<head>
	    <meta charset="UTF-8">
	    <title>Admin Dashboard</title>
	    <!-- Bootstrap 3.3.2 -->
    	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<!-- Theme style -->
    	<link href="../dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
		<!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    	<link href="../dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    	<link rel="shortcut icon" href="../favicon.ico"/>

	</head>
  	<body class="skin-yellow" data-name="admin">
  		<img src="../images/pokemondance.gif">
  		<div class="content_image_loader hidden" data-id="<?php echo $i;?>">
			<img class="loadermini" src="<?php echo $host_addr;?>images/waiting.gif"/>
		</div>
		<form name="appuserform" action="../snippets/mobpoint.php" method="POST" enctype="multipart/form-data">
			<input name="displaytype" type="hidden" value="appuserlogin"/>
			<input name="entryvariant" type="hidden" value="appuserlogin"/>
			<input name="entrypoint" type="hidden" value="mobileapp"/>
			<div class="box-group" id="contentaccordion">
				<div class="panel box box-primary">
	              <div class="box-header with-border">
	                <h4 class="box-title">
	                  <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock">
	                    <i class="fa fa-"></i>  login test
	                  </a>
	                </h4>
	              </div>
	              <div id="headBlock" class="panel-collapse collapse in">
	                    <div class="box-body">
			                <div class="col-md-6">
	                        	<div class="form-group">
			                      <label>Email Address</label>
			                      <div class="input-group">
				                      <div class="input-group-addon">
				                        <i class="fa fa-at"></i>
				                      </div>
			                      	  <input type="email" class="form-control" name="username" placeholder="User email address"/>
			                      </div>
			                    </div>
			                </div><div class="col-md-6">
	                        	<div class="form-group">
			                      <label>Password</label>
			                      <div class="input-group">
				                      <div class="input-group-addon">
				                        <i class="fa fa-lock"></i>
				                      </div>
			                      	  <input type="password" class="form-control" name="password" placeholder="The user Password here"/>
			                      </div>
			                    </div>
			                </div>

	                        <div class="col-md-12">
			        			<div class="box-footer">
				                    <input type="submit" class="btn btn-danger" name="createappuser" value="Test"/>
				                </div>
			            	</div>
	                    </div>
	              </div>
	            </div>
			</div>
	    </form>
  		<form name="appuserform" action="../snippets/basicsignup.php" method="POST" enctype="multipart/form-data">
			<input name="entryvariant" type="hidden" value="createappuser"/>
			<input name="entrypoint" type="hidden" value="mobileapp"/>
			<div class="box-group" id="contentaccordion">
				<div class="panel box box-primary">
	              <div class="box-header with-border">
	                <h4 class="box-title">
	                  <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock">
	                    <i class="fa fa-"></i>  The ultimate test zone
	                  </a>
	                </h4>
	              </div>
	              <div id="headBlock" class="panel-collapse collapse in">
	                    <div class="box-body">
			                <!-- <div class="col-md-1 hiddentwo">
	                        	<div class="form-group ">
			                      <label>First Name</label>
			                      <div class="input-group">
				                      <div class="input-group-addon">
				                        <i class="fa fa-file-text"></i>
				                      </div>
	 				                  <input type="file" class="form-control" name="profpic" placeholder="First Name"/>
			                      </div>
			                    </div>
			                </div> -->
		                    <div class="col-md-4">
	                        	<div class="form-group">
			                      <label>First Name</label>
			                      <div class="input-group">
				                      <div class="input-group-addon">
				                        <i class="fa fa-file-text"></i>
				                      </div>
			                      	  <input type="text" class="form-control" name="firstname" placeholder="First Name"/>
			                      </div>
			                    </div>
			                </div>
			                <div class="col-md-4">
	                        	<div class="form-group">
			                      <label>Middle Name</label>
			                      <div class="input-group">
				                      <div class="input-group-addon">
				                        <i class="fa fa-file-text"></i>
				                      </div>
			                      	  <input type="text" class="form-control" name="middlename" placeholder="Middle Name"/> 
			                      </div>
			                    </div>
			                </div><div class="col-md-4">
	                        	<div class="form-group">
			                      <label>Last Name</label>
			                      <div class="input-group">
				                      <div class="input-group-addon">
				                        <i class="fa fa-file-text"></i>
				                      </div>
			                      	  <input type="text" class="form-control" name="lastname" placeholder="Last Name"/>
			                      </div>
			                    </div>
			                </div>
			                <div class="col-md-6">
	                        	<div class="form-group">
			                      <label>Email Address</label>
			                      <div class="input-group">
				                      <div class="input-group-addon">
				                        <i class="fa fa-at"></i>
				                      </div>
			                      	  <input type="email" class="form-control" name="email" placeholder="User email address"/>
			                      </div>
			                    </div>
			                </div><div class="col-md-6">
	                        	<div class="form-group">
			                      <label>Password</label>
			                      <div class="input-group">
				                      <div class="input-group-addon">
				                        <i class="fa fa-lock"></i>
				                      </div>
			                      	  <input type="password" class="form-control" name="pword" placeholder="The user Password here"/>
			                      </div>
			                    </div>
			                </div>

	                        <div class="col-md-12">
			        			<div class="box-footer">
				                    <input type="button" class="btn btn-danger" name="createappuser" value="Create App User"/>
				                </div>
			            	</div>
	                    </div>
	              </div>
	            </div>
			</div>
	    </form>
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
		<!-- jQuery 2.1.3 -->
	    <script src="../plugins/jQuery/jQuery-2.1.3.min.js"></script>
	    <!-- Bootstrap 3.3.2 JS -->
	    <script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="../scripts/mylib.js" type="text/javascript"></script>
		<script src="../scripts/lightbox.js" type="text/javascript"></script>
		<script src="../scripts/mobtest.js" type="text/javascript"></script>
	</body>
</html>



