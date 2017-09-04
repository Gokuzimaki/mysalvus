<?php  
session_start();
include('snippets/connection.php');
// include('login.php');
$activepage3="active";
// queue up bootstrap datetimepicker plugin to be loaded for the form
$mpagelibstyleextras='
	<!-- Bootstrap time Picker -->
	<link href="'.$host_addr.'plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet"/>
	<!-- Bootstrap datetime Picker -->
	<link href="'.$host_addr.'plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet"/>
';
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
		        	})
		        	$(\'[data-datepicker]\').datetimepicker({
			            format:"YYYY-MM-DD",
			            keepOpen:true
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
$formtruetype="serviceprovideraccform";
$bizarr=getAllBusinessTypes();
?>
	<body class="">
		<?php
			include($host_tpathplain.'themesnippets/mysalvussnippets/toplinksmysalvus.php');
		?>
	    <!-- Content start -->
	    <div class="container">
	  		<div class="row content" id="signup">
	  			<div class="col-md-12 _salvus-intro-container ">
	  				
	  				<div class="salvus-intro-content text-left">
	  					By filling the form below, your organization will be added to our 
	  					directory of sexual violence support service providers who are 
	  					willing and able to render one or more crucial service for 
	  					survivors of sexual violence. 
	  					Please note that you will be able to render help through this 
	  					platform after your organization has been verified.<br> 
						Please join the platform by filling the information boxes below:

	  				</div>
				</div>
				<h2 class="page-header">Become a Salvus Service Provider 
				<i class="fa fa-briefcase"></i></h2>	

				<form name="<?php echo $formtruetype;?>" method="POST" onSubmit="return false;" enctype="multipart/form-data" 
					action="<?php echo $host_addr?>snippets/basicsignup.php">
					<input type="hidden" name="entryvariant" value="createserviceprovideracc"/>
					<input type="hidden" name="returl" value="<?php echo $host_addr?>completion.php?t=spaccountreg"/>
					<div class="col-md-3">
	                	<div class="form-group">
	                      <label>Organization Name</label>
	                      <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-file-text"></i>
		                      </div>
	                      	  <input type="text" class="form-control" name="businessname" placeholder="Organization Name"/>
	                      </div>
	                    </div>
	                </div>
	                <div class="col-md-3">
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
	                
	                <div class="col-md-3">
	                	<div class="form-group">
	                      <label>Username</label>
	                      <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-user-circle"></i>
		                      </div>
	                      	  <input type="text" class="form-control" name="username" 
	                      	  		data-feverify="true" data-fev-state="inactive" 
		                      	   data-fev-tbl="users" data-fev-col="username" 
		                      	   data-fev-map='{"logic":[2],"column":["usertype"],"value":["serviceprovider"]}' 
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
	                <div class="col-md-3">
	                	<div class="form-group">
	                      <label>Account Password</label>
	                      <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-lock"></i>
		                      </div>
	                      	  <input type="password" data-sa="true" data-type="password" class="form-control" 
	                      	  name="pword" data-pvalidate="true" data-pvtype="nlcus" data-pvforce="" 
	                      	  placeholder="Provide password"/>
	                      	  <div class="input-group-addon pshow" title="show">
		                        <i class="fa fa-eye-slash"></i>
		                      </div>
	                      </div>
	                    </div>
	                </div>
	                <div class="col-md-3">
	                	<div class="form-group">
	                      <label>Organization Phone Number</label>
	                      <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-phone"></i>
		                      </div>
	                      	  <input type="text" class="form-control" 
	                      	  name="phonenumber" data-telvalidate="true" 
	                      	  placeholder="Phone Number"/>
	                      </div>
	                    </div>
	                </div>
	                <div class="col-md-3">
	                	<div class="form-group">
	                      <label>Organization Hotline <small>For emergencies</small></label>
	                      <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-phone"></i>
		                      </div>
	                      	  <input type="text" class="form-control" 
	                      	  name="phonetwo" data-telvalidate="true" 
	                      	  placeholder="Phone Number"/>
	                      </div>
	                    </div>
	                </div>
	                <div class="col-md-3">
	                	<div class="form-group">
	                      <label>Contact Name</label>
	                      <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-file-text"></i>
		                      </div>
	                      	  <input type="text" class="form-control" name="fullname" placeholder="Contact's full name"/> 
	                      </div>
	                    </div>
	                </div>
	                <div class="col-md-3">
	                	<div class="form-group">
	                      <label>Contact Email</label>
	                      <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-at"></i>
		                      </div>
	                      	  <input type="email" class="form-control" name="contactemail" data-evalidate="true" placeholder="Email address"/>
	                      </div>
	                    </div>
	                </div>

	                <div class="col-md-3">
	                	<div class="form-group">
	                      <label>Contact Phone Number</label>
	                      <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-phone"></i>
		                      </div>
	                      	  <input type="text" data-telvalidate="true" class="form-control" name="contactphonenumber" placeholder="Contact Phone Number"/>
	                      </div>
	                    </div>
	                </div>
	                <div class="col-md-3">
	                	<div class="form-group">
	                      <label>Main Support Service</label>
	                      <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-file-text"></i>
		                      </div>
	                      	  <select type="text" class="form-control" name="businessnature" >
		                      	  	<option value="">--Choose--</option>
		                      	  	<?php echo $bizarr['selectiontwo'];?>
		                      	  	
	                      	  </select>
	                      </div>
	                    </div>
	                </div>
	                <div class="col-md-3">
	                	<div class="form-group">
	                      <label>Secondary Support Service(Optional)</label>
	                      <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-file-text"></i>
		                      </div>
	                      	  <select type="text" class="form-control" name="businessnature2" >
		                      	  	<option value="">--Choose--</option>
		                      	  	<?php echo $bizarr['selectiontwo'];?>
	                      	  </select>
	                      </div>
	                    </div>
	                </div>
	                <div class="col-md-3">
	                	<div class="form-group">
	                      <label>Service Duration</label>
	                      <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-file-text"></i>
		                      </div>
	                      	  <input type="text" class="form-control" name="spduration" 
	                      	  placeholder="Duration  e.g '1 year'"/> 
	                      </div>
	                    </div>
	                </div>
	                <div class="col-md-6">
	                	<div class="form-group">
	                      <label>Organization Address</label>
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
	                <div class="col-md-6">
	                	<div class="form-group">
	                      <label>Organization Bio(max of 100 words)</label>
	                      <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-history"></i>
		                      </div>
	                      	  <textarea class="form-control" rows="5" name="bio" 
	                      	  placeholder="Organization Bio" data-wMAX-type="word"
	                      	  data-wMax="100"></textarea>
	                      </div>
	                    </div>
	                </div>
	                
	                <div class="col-md-4 clear-both">
	                	<div class="form-group">
	                      <label>Organization Profile</label>
	                      <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-folder"></i>
		                      </div>
	                      	  <input type="file" class="form-control" name="orgprofile" 
	                      	  placeholder="Organization Profile"/>
	                      </div>
	                    </div>
	                </div>
	                <div class="col-md-4">
	                	<div class="form-group">
	                      <label>Organization Profile URL (if Available)</label>
	                      <div class="input-group">
		                      <div class="input-group-addon">
		                        http://
		                      </div>
	                      	  <input type="url" class="form-control" 
	                      	  name="dataurl" placeholder="Profile URL"/>
	                      </div>
	                    </div>
	                </div>
	                <div class="col-md-4">
	                	<div class="form-group">
	                      <label>Organization CAC File * </label>
	                      <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-file-text"></i>
		                      </div>
	                      	  <input type="file" class="form-control" name="orgcac" placeholder="Organization CAC"/>
	                      </div>
	                    </div>
	                </div>

	                <div class="col-md-12">
	                	<div class="col-md-6">
	                		<input type="hidden" name="refereedatacount" value="2"/>
		                	<div class="form-group">
		                      	<label>References Data(1)</label>
		                      	<div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-users"></i>
			                      </div>
			                      <input name="refereeorgname1" type="text" class="form-control" placeholder="Orgainsation Name">
			                      <input name="refereemail1" type="text" data-evalidate="true" class="form-control" placeholder="Email Address">
			                      <input name="refereephone1" type="text" class="form-control" data-telvalidate="true" placeholder="Organisation Phone">
			                      <input name="refereename1" type="text" class="form-control" placeholder="Contact Name">
		                      	</div>
		                	</div>
	                    </div>
	                    <div class="col-md-6">
		                	<div class="form-group">
		                      	<label>References Data(2)</label>
		                      	<div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-users"></i>
			                      </div>
			                      <input name="refereeorgname2" type="text" class="form-control" placeholder="Orgainsation Name">
			                      <input name="refereemail2" type="text" data-evalidate="true" class="form-control" placeholder="Email Address">
			                      <input name="refereephone2" type="text" class="form-control" data-telvalidate="true" placeholder="Organisation Phone">
			                      <input name="refereename2" type="text" class="form-control" placeholder="Contact Name">
		                      	</div>
		                	</div>
	                    </div>
	                </div>
	                <input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>
	                <input type="hidden" name="extraformdata" value="businessname-:-input<|>
	                  email-:-input<|>
	                  pword-:-input<|>
	                  phonenumber-:-input<|>
	                  fullname-:-input<|>
	                  contactemail-:-input<|>
	                  contactphonenumber-:-input<|>
	                  businessnature-:-select<|>
	                  spduration-:-input<|>
	                  state-:-select<|>
	                  address-:-textarea<|>
	                  bio-:-textarea<|>
	                  orgprofile-:-input|image,office,pdf-:-[group-|-businessnature-|-select-|-*any*]<|>
	                  orgcac-:-input|image,office,pdf-:-[group-|-businessnature-|-select-|-*any*]<|>
	                  egroup|data-:-[refereedatacount>|<
					  refereeorgname-|-input>|<
					  refereeemail-|-input>|<
					  refereephone-|-input>|<
					  refereename-|-input]-:-groupfall[1,2,3,4]"/>
	                <input type="hidden" name="errormap" value="businessname-:-Please provide Organization name<|>
	                    email-:-Provide a valid email address<|>
	                    pword-:-Provide a good password of at least 8 characters in length.<|>
	                    phonenumber-:-Please provide a phone number<|>
	                    fullname-:-Please give the name of a contact readily available for communication<|>
	                    contactemail-:-Provide contact email address<|>
	                    phonenumber-:-Provide contact phonenumber<|>
	                    businessnature-:-Choose the main support service the organization performs<|>
	                    spduration-:-Pleace specify how long this support service has been provided<|>
	                    state-:-Select a state<|>
	                    address-:-Please Provide an address.<|>
	                    bio-:-Please give a short detailed description of the organization<|>
	                    orgprofile-:-NA<|>
	                    orgcac-:-Choose a valid image, document or pdf file containing the organization cac certificate<|>
	                    egroup|data-:-[Please Provide the reference organisation name>|<
	                    Please Provide the reference organisation email>|<
	                    Please Provide the reference organisation phone number>|<
	                    Please Provide the reference organisation contact Person]"/>
	                <div class="col-md-12 clearboth">
		                <div class="box-footer text-center">
		                    <input type="button" class="btn btn-success _salvsubmit" name="user" data-formdata="<?php echo $formtruetype;?>" onclick="submitCustom('<?php echo $formtruetype;?>','complete')" value="Create Service Provider Account"/>
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