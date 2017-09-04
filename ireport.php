<?php  
session_start();
include('snippets/connection.php');
// include('login.php');
$activepage4="active";
// queue up bootstrap datetimepicker plugin to be loaded for the form
$mpagelibstyleextras='
	<!-- Bootstrap time Picker -->
	<link href="'.$host_addr.'plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet"/>
	<!-- Bootstrap datetime Picker -->
	<link href="'.$host_addr.'plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet"/>
	<!-- daterange picker -->
	<link href="'.$host_addr.'plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
	<!-- daterange picker -->
	<link href="'.$host_addr.'plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
';
// force extra scripts to be loaded at the bottom of the page
// $mpage_lsb="true";
$mpagelibscriptextras='
<!-- Moment js -->
    <script src="'.$host_addr.'plugins/moment/moment.js" type="text/javascript"></script>
<!-- bootstrap Date-time picker -->
    <script src="'.$host_addr.'plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js" type="text/javascript"></script>
';

$mpagescriptextras='<script data-type="multiscript">
		    	$(document).ready(function(){
			    	$(\'[data-datetimepicker]\').datetimepicker({
			            format:"YYYY-MM-DD HH:mm",
			            keepOpen:true
			            // debug:true
		        	})
		        	$(\'[data-datepicker]\').datetimepicker({
			            format:"YYYY-MM-DD",
			            keepOpen:true
			            // debug:true
		        	});
		        	$(\'[data-timepicker]\').datetimepicker({
			            format:"HH:mm",
			            keepOpen:true
		        	});
					if($.fn.wordMAX){
					  // console.log("functional");
					  $(\'input[type="text"][data-wMax],textarea[data-wMax]\').wordMAX(); 
					}
		    	})
			</script>';
include($host_tpathplain.'themesnippets/mysalvussnippets/headcontentmysalvus.php');
$formtruetype="useraccform";

?>
	<body class="">
		<?php
			include($host_tpathplain.'themesnippets/mysalvussnippets/toplinksmysalvus.php');
		?>
	    <!-- Content start -->
	    <div class="container">

	  		<div class="row content" id="signup">
	  			<div class="col-md-12 _salvus-intro-container">
	  				
	  				<div class="salvus-intro-content text-left">
	  					By creating a user account, you can save or report incidents of sexual violence/assault either as a survivor or as a witness of sexual violence/assault. Your story will not be shared until you want it to. You can also submit it immediately and get help if you wish.<br> 
						To record the incident, create an account by filling the form below:
	  				</div>
				</div>
				<h2 class="page-header">Create a Salvus User Account<br>
				<small> After your account is created, you can start reporting incidents.</small>
				</h2>	
				<form name="<?php echo $formtruetype;?>" method="POST" onSubmit="return false;" enctype="multipart/form-data" 
					action="<?php echo $host_addr;?>snippets/basicsignup.php">
						<input type="hidden" name="entryvariant" value="createuseracc"/>
						<input type="hidden" name="returl" 
						value="<?php echo $host_addr?>completion.php?t=uaccountreg"/>
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
		                </div>
		                <div class="col-md-4">
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
		                <div class="col-md-4">
                        	<div class="form-group">
		                      <label>Gender</label>
		                      <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-file-text"></i>
			                      </div>
		                      	  <select type="text" class="form-control" name="gender" placeholder="First Name">
		                      	  	<option value="">--Choose Gender--</option>
		                      	  	<option value="male">Male</option>
		                      	  	<option value="female">Female</option>
		                      	  	<option value="other">Other</option>
		                      	  </select>
		                      </div>
		                    </div>
		                </div>
		                <div class="col-md-4">
                        	<div class="form-group">
		                      <label>Date of Birth</label>
		                      <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-file-text"></i>
			                      </div>
		                      	  <input type="text" class="form-control" data-datepicker="true" name="dob" placeholder="Date of Birth"/> 
		                      </div>
		                    </div>
		                </div>
		                <div class="col-md-4">
                        	<div class="form-group">
		                      <label>Phone Number</label>
		                      <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-phone"></i>
			                      </div>
		                      	  <input type="text" class="form-control" 
		                      	  data-telvalidate="true" name="phonenumber" placeholder="Phone Number"/>
		                      </div>
		                    </div>
		                </div>
		                <div class="col-md-4">
                        	<div class="form-group">
		                      <label>Email Address</label>
		                      <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-at"></i>
			                      </div>
		                      	  <input type="email" class="form-control" name="email" 
		                      	   data-feverify="true" data-fev-state="inactive" 
		                      	   data-fev-tbl="users" data-fev-col="email" 
		                      	   data-fev-map='{"logic":[2],"column":["usertype"],"value":["serviceprovider"]}' 
		                      	   data-evalidate="true" data-fev-lval="" 
		                      	   placeholder="Email address"/>
		                      	    <div class="input-group-addon rel _fev-group">
		                                <i class="fa fa-database _group _default"></i>
		                                <i class="fa fa-check color-green _group 
		                                success hidden"></i>
		                                <i class="fa fa-times color-lightred 
		                                _group failure hidden"></i>
		                                <img src="<?php echo $host_addr;?>images/loading.gif" 
		                                class=" loadermask loadermini _igloader _upindex hidden">
		                            </div>
		                      </div>
		                    </div>
		                </div>
		                <div class="col-md-4">
		                	<div class="form-group">
		                      <label>Username</label>
		                      <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-user-circle"></i>
			                      </div>
		                      	  <input type="text" class="form-control" name="username" 
		                      	  		data-feverify="true" data-fev-state="inactive" 
			                      	   data-fev-tbl="users" data-fev-col="username" 
			                      	   data-fev-map='{"logic":[2],"column":["usertype"],"value":["user"]}' 
			                      	   data-fev-lval=""
			                      	   placeholder="Username"/>
			                      	<div class="input-group-addon rel _fev-group">
		                                <i class="fa fa-database _group _default"></i>
		                                <i class="fa fa-check color-green _group 
		                                success hidden"></i>
		                                <i class="fa fa-times color-lightred 
		                                _group failure hidden"></i>
		                                <img src="<?php echo $host_addr;?>images/loading.gif" 
		                                class=" loadermask loadermini _igloader _upindex hidden">
		                            </div>
		                      </div>
		                    </div>
		                </div>
		                <div class="col-md-4">
                        	<div class="form-group">
		                      <label>Account Password</label>
		                      <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-lock"></i>
			                      </div>
		                      	  <input type="password" data-sa="true" data-type="password" class="form-control" name="pword" data-pvalidate="true" data-pvtype="nlcus" data-pvforce="" placeholder="The user Password here"/>
		                      	  <div class="input-group-addon pshow" title="show">
			                        <i class="fa fa-eye-slash"></i>
			                      </div>
		                      </div>
		                    </div>
		                </div>
		                <div class="col-md-4">
                        	<div class="form-group">
		                      <label>Preferred Contact Method</label>
		                      <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-street-view"></i>
			                      </div>
		                      	  <select type="text" class="form-control" name="pcmethod">
		                      	  	<option value="">--Choose--</option>
		                      	  	<option value="email">Email</option>
		                      	  	<option value="phone">Telephone</option>
		                      	  </select>
		                      </div>
		                    </div>
		                </div>
		                <div class="col-md-12">

                        	<div class="form-group">
		                      <label>Contact Address</label>
		                      <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-map-marker"></i>
			                      </div>
			                      <select name="state" id="state" class="form-control">
										<option value="">Select State</option>
										<option value="Abia">Abia</option>
										<option value="Adamawa">Adamawa</option>
										<option value="Akwa Ibom">Akwa Ibom</option>
										<option value="Anambra">Anambra</option>
										<option value="Bauchi">Bauchi</option>
										<option value="Bayelsa">Bayelsa</option>
										<option value="Benue">Benue</option>
										<option value="Borno">Borno</option>
										<option value="Cross River">Cross River</option>
										<option value="Delta">Delta</option>
										<option value="Ebonyi">Ebonyi</option>
										<option value="Edo">Edo</option>
										<option value="Ekiti">Ekiti</option>
										<option value="Enugu">Enugu</option>
										<option value="FCT">FCT</option>
										<option value="Gombe">Gombe</option>
										<option value="Imo">Imo</option>
										<option value="Jigawa">Jigawa</option>
										<option value="Kaduna">Kaduna</option>
										<option value="Kano">Kano</option>
										<option value="Kastina">Kastina</option>
										<option value="Kebbi">Kebbi</option>
										<option value="Kogi">Kogi</option>
										<option value="Kwara">Kwara</option>
										<option value="Lagos">Lagos</option>
										<option value="Nasarawa">Nasarawa</option>
										<option value="Niger">Niger</option>
										<option value="Ogun">Ogun</option>
										<option value="Ondo">Ondo</option>
										<option value="Osun">Osun</option>
										<option value="Oyo">Oyo</option>
										<option value="Plateau">Plateau</option>
										<option value="Rivers">Rivers</option>
										<option value="Sokoto">Sokoto</option>
										<option value="Taraba">Taraba</option>
										<option value="Yobe">Yobe</option>
										<option value="Zamfara">Zamfara</option>
								  </select>
		                      	  <textarea class="form-control" name="address" placeholder="Full address"></textarea>
		                      </div>
		                    </div>
		                </div>
		                <input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>
	                    <input type="hidden" name="extraformdata" value="firstname-:-input<|>
	                      middlename-:-input<|>
	                      lastname-:-input<|>
	                      gender-:-select<|>
	                      dob-:-input<|>
	                      phonenumber-:-input<|>
	                      email-:-input<|>
	                      pword-:-input<|>
	                      pcmethod-:-select<|>
	                      state-:-select<|>
	                      address-:-textarea"/>
	                    <input type="hidden" name="errormap" value="firstname-:-Please provide your Firstname<|>
		                    middlename-:-NA<|>
		                    lastname-:-Please Provide your last name<|>
		                    gender-:-Choose your gender<|>
		                    dob-:-Specify your date of birth<|>
		                    phonenumber-:-Please provide a phone number<|>
		                    email-:-Provide a valid email address<|>
		                    pword-:-Provide a good password of at least 8 characters in length.<|>
		                    pcmethod-:-NA<|>
		                    state-:-Select a state<|>
		                    address-:-Please Provide an address."/>
		                <div class="col-md-12 clearboth">
			                <div class="box-footer">
			                    <input type="button" class="btn btn-success _salvsubmit" name="user" 
			                    data-formdata="<?php echo $formtruetype;?>" 
			                    onclick="submitCustom('<?php echo $formtruetype;?>','complete')" 
			                    value="Create User Account"/>
			                </div>
			            </div>
		            </form>
	  		</div>

	    </div>
  		
	    <!-- Content End -->
	    <?php
			include($host_tpathplain.'themesnippets/mysalvussnippets/footermysalvus.php');
		?>
		<?php
			include($host_tpathplain.'themesnippets/mysalvussnippets/themescriptsdumpmysalvus.php');
		?>
	</body>
</html>