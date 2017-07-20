<?php 

	$formtruetype="useraccform";
	$hidenew="";
	$newin="";
	$hideedit="";
	$editin="";
	$outsdata=getAllUsers("admin","","user","adminoutputtwo2");
	if(!isset($evar)){
		$evar="createuseraccadmin";	
	}
	if($displaytype=="usersnew"){
		$hideedit="hidden";
		$newin="in";
	}else if($displaytype=="usersedit"){
		$hidenew="hidden";
		$editin="in";
	}
?>
<div class="box-body background-color-white clearboth">
    <div class="box-group" id="generaldataaccordion">
		<div class="panel box box-primary <?php echo $hidenew;?>">
		    <div class="box-header with-border">
		        <h4 class="box-title">
		          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#NewPageManagerBlock">
		            <i class="fa fa-sliders"></i> New 
		          </a>
		        </h4>
		    </div>
		    <div id="NewPageManagerBlock" class="panel-collapse collapse <?php echo $newin;?>">
		        <div class="row">
		            <form name="<?php echo $formtruetype;?>" method="POST" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/basicsignup.php">
						<input type="hidden" name="entryvariant" value="<?php echo $evar;?>"/>
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
		                      	  <input type="text" class="form-control" name="phonenumber" placeholder="Phone Number"/>
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
	                    <input type="hidden" name="errormap" value="firstname-:-Please provide Firstname<|>
		                    middlename-:-NA<|>
		                    lastname-:-Please Provide last name<|>
		                    gender-:-Choose gender<|>
		                    dob-:-Specify date of birth<|>
		                    phonenumber-:-Please provide a phone number<|>
		                    email-:-Provide a valid email address<|>
		                    pword-:-Provide a good password of at least 8 characters in length.<|>
		                    pcmethod-:-NA<|>
		                    state-:-Select a state<|>
		                    address-:-Please Provide an address."/>
		                <div class="col-md-12 clearboth">
			                <div class="box-footer">
			                    <input type="button" class="btn btn-danger" name="user" data-formdata="<?php echo $formtruetype;?>" onclick="submitCustom('<?php echo $formtruetype;?>','complete')" value="Save"/>
			                </div>
			            </div>
		            </form>
		        </div>
		    </div>
		    <script data-type="multiscript">
		    	$(document).ready(function(){
			    	$('[data-datetimepicker]').datetimepicker({
			            format:"YYYY-MM-DD HH:mm",
			            keepOpen:true
		        	})
		        	$('[data-datepicker]').datetimepicker({
			            format:"YYYY-MM-DD",
			            keepOpen:true
		        	});
		        	$('[data-timepicker]').datetimepicker({
			            format:"HH:mm",
			            keepOpen:true
		        	});
		    	})
			</script>
		</div>
		<div class="panel box overflowhidden box-primary <?php echo $hideedit;?>">
		    <div class="box-header with-border">
		      <h4 class="box-title">
		        <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#EditBlock">
		          <i class="fa fa-gear"></i> Edit 
		        </a>
		      </h4>
		    </div>
		    <div id="EditBlock" class="panel-collapse collapse <?php echo $editin;?>">
		      <div class="box-body">
		          <div class="row">
		            <div class="col-md-12">
		              <?php
		                echo $outsdata['adminoutput2'];
		              ?>
		            </div>
		        </div>
		      </div>
		    </div>
		</div>
	</div>
</div>