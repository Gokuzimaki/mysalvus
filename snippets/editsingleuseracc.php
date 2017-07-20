<?php 

	// get the current edit data and use the capture variable to populate the edit form below
	// this data is created when this snippet is called or directly within it to be rendered
	$doeditform="no";
	$genderdisabled="";
	if(isset($edittype)&&$edittype!==""){

	}else{
		$edittype="edituseraccadmin";
		
	}
	if(!isset($rowdata)){
		if(isset($oid)&&is_numeric($oid)&&$oid>0){
			$doeditform="yes";
			$edata=getSingleUserPlain($oid);
			if($edata['genderchangedate']!=="0000-00-00"){
				$genderdisabled='disabled="true" title="A one time change is allowed for errors in the gender option"';
			}
			$selectionscripts='
						$("form[name='.$formtruetype.'] select[name=gender]").val("'.$edata['gender'].'");
						$("form[name='.$formtruetype.'] select[name=pcmethod]").val("'.$edata['pcmethod'].'");
						$("form[name='.$formtruetype.'] select[name=state]").val("'.$edata['statename'].'");
						$("form[name='.$formtruetype.'] select[name=status]").val("'.$edata['status'].'");
					
			';
 		}else{
			$doeditform="no";
			$errblock="1";			
		}
	}else{
		$doeditform="yes";
	}

	if(!isset($formtruetype)){
		$doeditform="no";
		$errblock="2";			

	}
	if(!isset($host_addr)){
		$doeditform="no";
		$errblock="3";			

	}
	$hidestatus="";
	if($doeditform=="yes"){
		if(isset($_SESSION['userhmysalvus'])&&isset($douser)){
			$hidestatus="hidden";
		}
?>

<div id="form" class="background-color-white">
	<form name="<?php echo $formtruetype;?>" method="POST" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/edit.php">
		<input type="hidden" name="entryvariant" value="<?php echo $edittype?>"/>
		<input type="hidden" name="entryid" value="<?php echo $oid?>"/>
		<div class="col-md-12">
            <h4>Edit Profile</h4>
		</div>
		<div class="col-md-4">
	    	<div class="form-group">
	          <label>First Name</label>
	          <div class="input-group">
	              <div class="input-group-addon">
	                <i class="fa fa-file-text"></i>
	              </div>
	          	  <input type="text" class="form-control" name="firstname" value="<?php echo $edata['firstname'];?>" placeholder="First Name"/>
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
	          	  <input type="text" class="form-control" name="middlename" value="<?php echo $edata['middlename'];?>" placeholder="Middle Name"/> 
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
	          	  <input type="text" class="form-control" name="lastname" value="<?php echo $edata['lastname'];?>" placeholder="Last Name"/>
	          </div>
	        </div>
	    </div>
	    <div class="col-md-4">
	    	<div class="form-group">
	          <label>Cover Photo</label>
	          <div class="input-group">
	              <div class="input-group-addon">
	                <i class="fa fa-file-image-o"></i>
	              </div>
	          	  <input type="hidden" class="form-control" name="coverid" value="<?php echo $edata['faceid'];?>"/>
	          	  <input type="file" class="form-control" name="contentpic"/>
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
	          	  <select type="text" class="form-control" name="gender" <?php echo $genderdisabled;?>placeholder="First Name">
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
	          	  <input type="text" class="form-control" data-datepicker="true" value="<?php echo $edata['dob'];?>" name="dob" placeholder="Date of Birth"/> 
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
	          	  <input type="text" class="form-control" name="phonenumber" value="<?php echo $edata['phonenumber'];?>" placeholder="Phone Number"/>
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
	                   data-feverify="true" data-fev-state="done" 
	                   data-fev-tbl="users" data-fev-col="email" 
	                   data-fev-map='{"logic":[2],"column":["usertype"],"value":["user"]}'
	                   data-evalidate="true" data-fev-lval=""
	                   data-fev-elval="<?php echo $edata['email'];?>" 
	                    data-edittype="true" value="<?php echo $edata['email'];?>" 
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
	              <label>Username(Optional)</label>
	              <div class="input-group">
	                <div class="input-group-addon">
	                  <i class="fa fa-user-circle"></i>
	                </div>

	                  <input type="text" class="form-control" name="username" 
	                      data-feverify="true" data-fev-state="done" 
	                     data-fev-tbl="users" data-fev-col="username" 
	                     data-fev-map='{"logic":[2],"column":["usertype"],"value":["user"]}' 
	                     data-fev-lval=""
	                   data-fev-elval="<?php echo $edata['username'];?>" 
	                      value="<?php echo $edata['username'];?>"
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
	          	  <input type="password" value="<?php echo $edata['pword'];?>" data-sa="true" data-type="password" class="form-control" name="pword" data-pvalidate="true" data-pvtype="nlcus" data-pvforce="" placeholder="The user Password here"/>
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
	          	  <textarea class="form-control" name="address" placeholder="Full address"><?php echo $edata['address'];?></textarea>
	          </div>
	        </div>
	    </div>

	    <div class="col-md-12 <?php echo $hidestatus;?>">
	    	<div class="form-group">
	          <label>Status<?php 
	          	  		if($hidestatus=="hidden"){
	          	  	?>
	          	  <?php 
	          	  		}
	          	  	?>
	          	  	(Delete/Disable account)
	          	  </label>
	          <div class="input-group">
	              <div class="input-group-addon">
	                <i class="fa fa-trash"></i>
	              </div>
	          	  <select type="text" class="form-control" name="status">
	          	  	<option value="">--Choose--</option>
	          	  	<?php 
	          	  		if($hidestatus!=="hidden"){
	          	  	?>
	          	  	<option value="active">Active</option>
	          	  	<?php 
	          	  		}
	          	  	?>
	          	  	<?php 
	          	  		if($hidestatus=="hidden"){
	          	  	?>
	          	  	<option value="disabled">Delete</option>
	          	  	<?php 
	          	  		}else{
	          	  	?>
	          	  	<option value="disabled">Disable(This stops public access to the account)</option>
	          	  	<?php 
	          	  		}
	          	  	?>
	          	  </select>
	          </div>
	        </div>
	    </div>
	    
	    <input type="hidden" name="formdata" value="useraccform"/>
	    <input type="hidden" name="extraformdata" value="firstname-:-input<|>
	      middlename-:-input<|>
	      lastname-:-input<|>
	      contentpic-:-input|image<|>
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
	        contentpic-:-NA<|>
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
        	<?php echo $selectionscripts;?>
    	})
	</script>
</div>
<?php
	}else{
?>
<div id="form" class="background-color-white">
	<form name="<?php echo $formtruetype;?>" method="POST" onSubmit="return false" enctype="multipart/form-data" action="../snippets/basicsignup.php">
		<input type="hidden" name="entryvariant" value="createuseraccadmin"/>
		<div class="col-md-12">
			<div class="callout callout-danger">
	            <h4>An error!</h4>
	            <p>
	            	There was a problem creating the edit segment you requested.<br>
	            	The failing block in the edit snippet is: Block<?php echo $errblock;?>
	            </p>
	        </div>
	    </div>

</div>

<?php
	}
?>