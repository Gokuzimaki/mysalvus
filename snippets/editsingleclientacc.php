<?php 

  // get the current edit data and use the capture variable to populate the edit form below
  // this data is created when this snippet is called or directly within it to be rendered
  $doeditform="no";
  $genderdisabled="";
  if(isset($edittype)&&$edittype!==""){

  }else{
    $edittype="editserviceprovideraccadmin";
    
  }
  if(!isset($rowdata)){
    if(isset($oid)&&is_numeric($oid)&&$oid>0){
      $doeditform="yes";
      $edata=getSingleUserPlain($oid);
      $bizprofileimgtotal="<span>None Detected</span>";
      $bizcacimgtotal="<span>None Detected</span>";
      $profileurl="<span>None Detected</span>";
      if($edata['bizprofileid']>0){
        if($edata['bizporiginalimage']!==""){
          $ft=getExtensionAdvanced($edata['bizporiginalimage']);
          if($ft['type']=="image"){
            $bizprofileimgtotal='<a href="'.$edata['bizporiginalimage'].'" data-lightbox="default_gallery_'.$edata['id'].'" data-src="'.$edata['bizporiginalimage'].'" data-title="<h4 class=\'galimgdetailshigh\'>'.$edata['businessname'].' Profile</h4>: Right click the image and click \'Save as\' to download.">
                                    <img src="'.$edata['bizpthumbimage'].'" alt="img">
                    </a>';
            
          }else{
            $downloadurl=$host_addr.'/snippets/display.php?displaytype=gd_download&datapath='.str_replace($host_addr,".",$edata['bizporiginalimage']).'';
            $bizprofileimgtotal='<a href="##profile_download" 
                    title="Click to download" data-gddownload="true"
                    data-gd-url="'.$downloadurl.'"
                    >
                                    Download Profile ('.$ft['ext'].');
                    </a>';
          }
        }else if($edata['websiteurl']!==""){
          $bizprofileimgtotal='<a href="'.$edata['websiteurl'].'" target="_blank">
                                    View Profile (Web Address);
                    </a>';

        }

        if($edata['websiteurl']!==""){
          $profileurl='<a href="'.$edata['websiteurl'].'" target="_blank">
                          View Profile (Web Address);
                      </a>';
          
        }
        /*$bizprofileimgtotal='<a href="'.$edata['bizporiginalimage'].'" 
          data-lightbox="default_gallery_'.$edata['id'].'" 
          data-src="'.$edata['bizporiginalimage'].'" 
          data-title="<h4 class=\'galimgdetailshigh\'>'.$edata['businessname'].' 
          Profile</h4>: Right click the image and click \'Save as\' to download.">
                        <img src="'.$edata['bizpthumbimage'].'" alt="img">
                    </a>';*/
      }
      if($edata['bizcacid']>0){
        $ft=getExtensionAdvanced($edata['bizcacoriginalimage']);
        if($ft['type']=="image"){
          
          $bizcacimgtotal='<a href="'.$edata['bizcacoriginalimage'].'" data-lightbox="default_gallery_'.$edata['id'].'" data-src="'.$edata['bizcacoriginalimage'].'" data-title="<h4 class=\'galimgdetailshigh\'>'.$edata['businessname'].' CAC</h4>: Right click the image and click \'Save as\' to download.">
                    <img src="'.$edata['bizcacthumbimage'].'" alt="img">
                </a>';
          
        }else{
          $downloadurl=$host_addr.'/snippets/display.php?displaytype=gd_download&datapath='.str_replace($host_addr,".",$edata['bizcacoriginalimage']).'';
          $bizcacimgtotal='<a href="##cac_download" 
                  title="Click to download" data-gddownload="true"
                  data-gd-url="'.$downloadurl.'"
                  >
                                  Download Profile ('.$ft['ext'].');
                  </a>';
        }

      }
      if($edata['genderchangedate']!=="0000-00-00"){
				$genderdisabled='disabled="true" title="A one time change is allowed for errors in the gender option"';
			}
			$selectionscripts='
						$("form[name='.$formtruetype.'] select[name=gender]").val("'.$edata['gender'].'");
            $("form[name='.$formtruetype.'] select[name=businessnature]").val("'.$edata['bnid'].'");
						$("form[name='.$formtruetype.'] select[name=businessnature2]").val("'.$edata['sbnid'].'");
						$("form[name='.$formtruetype.'] select[name=pcmethod]").val("'.$edata['pcmethod'].'");
            $("form[name='.$formtruetype.'] select[name=state]").val("'.$edata['statename'].'");
						$("form[name='.$formtruetype.'] select[name=bntype]").val("'.$edata['bntype'].'");
						$("form[name='.$formtruetype.'] select[name=activationstatus]").val("'.$edata['activationstatus'].'");
						$("form[name='.$formtruetype.'] select[name=status]").val("'.$edata['status'].'");
					
			';
			
			$profileactivation='<div class="col-md-12">
							    	<div class="form-group">
							          <label>Activation Status<small>(for verified/vetted/validated entries)</small></label>
							          <div class="input-group">
							              <div class="input-group-addon">
							                <i class="fa fa-trash"></i>
							              </div>
							          	  <select type="text" class="form-control" name="activationstatus">
							          	  	<option value="">--Choose--</option>
							          	  	<option value="active">Active</option>
							          	  	<option value="inactive">Disabled</option>
							          	  </select>
							          </div>
							        </div>
							    </div>
			';
			$statusmanagement='
				<div class="col-md-12">
			    	<div class="form-group">
			          <label>Status</label>
			          <div class="input-group">
			              <div class="input-group-addon">
			                <i class="fa fa-trash"></i>
			              </div>
			          	  <select type="text" class="form-control" name="status">
			          	  	<option value="">--Choose--</option>
			          	  	<option value="active">Active</option>
			          	  	<option value="disabled">Disabled</option>
			          	  </select>
			          </div>
			        </div>
			    </div>
			';

			if($edittype=="editclientacc"){
				$profileactivation="";
				$statusmanagement="";
        $edittype="editclientacc";
			}
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
	if($doeditform=="yes"){
    if(isset($_SESSION['clienthmysalvus'])&&isset($douser)){
      $hidestatus="hidden";
  }
  $bizarr=getAllBusinessTypes();
  $bizarrsec=getAllBusinessTypes("","WHERE type='secondary'");
?>
  <?php if($douser=="true"){
  ?>
  <div class="box">
    <div class="box-header with-border">
        <h4 class="box-title">
            <i class="fa fa-sliders"></i> Update Profile
        </h4>
    </div>
    <div class="box-body box-body-padded">
  <?php 
      } 
  ?>
<div id="form" class="background-color-white">
	<form name="<?php echo $formtruetype;?>" method="POST" onSubmit="return false" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/edit.php">
		<input type="hidden" name="entryvariant" value="<?php echo $edittype?>"/>
		<input type="hidden" name="entryid" value="<?php echo $oid?>"/>
    
		    <div class="col-md-3">
        	<div class="form-group">
              <label>Organisation Name</label>
              <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-file-text"></i>
                  </div>
              	  <input type="text" class="form-control" 
                  name="businessname" value="<?php echo $edata['businessname'];?>" 
                  placeholder="Organisation Name"/>
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
                   data-feverify="true" data-fev-state="done" 
                   data-fev-tbl="users" data-fev-col="email" 
                   data-fev-map='{"logic":[2],"column":["usertype"],"value":["serviceprovider"]}'
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
        <div class="col-md-3">
          <div class="form-group">
              <label>Username(Optional)</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-user-circle"></i>
                </div>

                  <input type="text" class="form-control" name="username" 
                      data-feverify="true" data-fev-state="done" 
                     data-fev-tbl="users" data-fev-col="username" 
                     data-fev-map='{"logic":[2],"column":["usertype"],"value":["serviceprovider"]}' 
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
        <div class="col-md-3">
        	<div class="form-group">
              <label>Account Password</label>
              <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-lock"></i>
                  </div>
              	  <input type="password" data-sa="true" data-type="password" value="<?php echo $edata['pword'];?>" class="form-control" name="pword" data-pvalidate="true" data-pvtype="nlcus" data-pvforce="" placeholder="The user Password here"/>
              	  <div class="input-group-addon pshow" title="show">
                    <i class="fa fa-eye-slash"></i>
                  </div>
              </div>
            </div>
        </div>
        <div class="col-md-4">
        	<div class="form-group">
              <label>Organisation Phone Number</label>
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
              <label>Contact Name</label>
              <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-file-text"></i>
                  </div>
              	  <input type="text" class="form-control" name="fullname" value="<?php echo $edata['fullname'];?>" placeholder="Contact's full name"/> 
              </div>
            </div>
        </div>
        <div class="col-md-4">
        	<div class="form-group">
              <label>Contact Email</label>
              <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-at"></i>
                  </div>
              	  <input type="email" class="form-control" name="contactemail" value="<?php echo $edata['contactemail'];?>" data-evalidate="true" placeholder="Email address"/>
              </div>
            </div>
        </div>
        <div class="col-md-4">
        	<div class="form-group">
              <label>Contact Phone Number</label>
              <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-phone"></i>
                  </div>
              	  <input type="text" class="form-control" name="contactphonenumber" value="<?php echo $edata['contactphone'];?>" placeholder="Contact Phone Number"/>
              </div>
            </div>
        </div>
        <div class="col-md-4">
        	<div class="form-group">
              <label>Main Support Service</label>
              <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-file-text"></i>
                  </div>
              	  <select type="text" class="form-control" name="businessnature">
                  	  	<option value="">--Choose--</option>
                        <?php echo $bizarr['selectiontwo'];?>
              	  </select>
              </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Secondary Support Service(Optional)</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-file-text"></i>
                  </div>
                    <select type="text" class="form-control" name="businessnature2">
                        <option value="">--Choose--</option>
                        <?php echo $bizarrsec['selectiontwo'];?>
                    </select>
                </div>
              </div>
          </div>

        <div class="col-md-4">
        	<div class="form-group">
              <label>Service Duration</label>
              <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-file-text"></i>
                  </div>
              	  <input type="text" class="form-control" name="spduration" value="<?php echo $edata['spduration'];?>" placeholder="How long the main service has been provided"/> 
              </div>
            </div>
        </div>
        <div class="col-md-12">
        	<div class="form-group">
              <label>Organisation Address</label>
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
        <div class="col-md-12">
        	<div class="form-group">
              <label>Organisation Bio(50-100 words)</label>
              <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-history"></i>
                  </div>
              	  <textarea class="form-control" name="bio" placeholder="Organisation Bio"><?php echo $edata['businessdescription'];?></textarea>
              </div>
            </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
              <label>References</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-users"></i>
                </div>
                  <textarea class="form-control" rows="4" name="references" 
                  placeholder="References" ><?php echo $edata['referees'];?></textarea>
              </div>
            </div>
        </div>
        <div class="col-md-4">
        	<div class="form-group">
              <label>Organisation Profile</label>
              <?php echo $bizprofileimgtotal;?>
              <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-folder"></i>
                  </div>
              	  <input type="hidden" class="form-control" name="orgprofileid" value="<?php echo $edata['bizprofileid']?>"/>
              	  <input type="file" class="form-control" name="orgprofile" data-edittype="true" placeholder="Organisation Profile"/>
              </div>
            </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
              <label>Organization Profile URL (if Available)
              </label><?php echo $profileurl;?>
              <div class="input-group">
                <div class="input-group-addon">
                  http://
                </div>
                  <input type="url" class="form-control" 
                  name="dataurl" value="<?php echo $edata['websiteurltwo'];?>" placeholder="Profile URL"/>
              </div>
            </div>
        </div>
        <div class="col-md-4">
        	<div class="form-group">
              <label>Organisation CAC File</label>
              <?php echo $bizcacimgtotal;?>
              <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-file-text"></i>
                  </div>
              	  <input type="hidden" class="form-control" name="orgcacid" value="<?php echo $edata['bizcacid']?>"/>
              	  <input type="file" class="form-control" name="orgcac" data-edittype="true" placeholder="Organisation CAC"/>
              </div>
            </div>
        </div>
        <?php echo $profileactivation;echo $statusmanagement;?>
        <input type="hidden" name="formdata" value="<?php echo $formtruetype;?>"/>
        <input type="hidden" name="extraformdata" value="businessname-:-input<|>
          email-:-input<|>
          pword-:-input<|>
          phonenumber-:-input<|>
          contactname-:-input<|>
          contactemail-:-input<|>
          contactphonenumber-:-input<|>
          businessnature-:-select<|>
          spduration-:-input<|>
          state-:-select<|>
          address-:-textarea<|>
          bio-:-textarea<|>
          orgprofile-:-input|image,office,pdf<|>
          orgcac-:-input|image,office,pdf"/>
        <input type="hidden" name="errormap" value="businessname-:-Please provide Organisation name<|>
            email-:-Provide a valid email address<|>
            pword-:-Provide a good password of at least 8 characters in length.<|>
            phonenumber-:-Please provide a phone number<|>
            contactname-:-Please give the name of a contact readily available for communication<|>
            contactemail-:-Provide contact email address<|>
            contactphonenumber-:-Provide contact phonenumber<|>
            businessnature-:-Choose the main support service the organisation performs<|>
            spduration-:-Pleace specify how long this support service has been provided<|>
            state-:-Select a state<|>
            address-:-Please Provide an address.<|>
            bio-:-Please give a short detailed description of the organisation<|>
            orgprofile-:-Choose a valid image file containing the organisation profile<|>
            orgcac-:-Choose a valid image file containing the organisation cac certificate"/>
        <div class="col-md-12 clearboth">
            <div class="box-footer">
                <input type="button" class="btn btn-danger" name="user" data-formdata="<?php echo $formtruetype;?>" onclick="submitCustom('<?php echo $formtruetype;?>','complete')" value="Update"/>
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
<?php if($douser=="true"){
  ?>
    </div>
  </div>
  <?php 
      } 
  ?>
<?php
	}else{
?>
<div id="form" class="background-color-white">
	<form name="<?php echo $formtruetype;?>" method="POST" onSubmit="return false" enctype="multipart/form-data" action="../snippets/basicsignup.php">
		<div class="col-md-12">
			<div class="callout callout-danger">
	            <h4>An error!</h4>
	            <p>
	            	There was a problem creating the edit segment you requested.<br>
	            	The failing block in the edit snippet is: Block<?php echo $errblock;?>
	            </p>
	        </div>
	    </div>
  </form>
</div>

<?php
	}
?>