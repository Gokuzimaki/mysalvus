
<?php 
  if(isset($displaytype)){
    $typeout="User";
    if($displaytype=="createnewoperator"){
      $typeout="Operator Account";
    }else if($displaytype=="createnewsuboperator"){
      $typeout="SubOperator Account";
    }
  
?>
<div class="box">
  <div class="box-body">
    <div class="box-group" id="generaldataaccordion">
      <div class="panel box overflowhidden box-primary">
        <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#NewPageManagerBlock">
                <i class="fa fa-sliders"></i> Create Platform <?php echo $typeout;?>
              </a>
            </h4>
        </div>
        <div id="NewPageManagerBlock" class="panel-collapse collapse in">
            <div class="row">
            	<form name="userform" method="POST" enctype="multipart/form-data" action="<?php echo $host_addr;?>snippets/basicsignup.php">
            		<input type="hidden" name="entryvariant" value="<?php echo $displaytype?>"/>
                		
                    <?php
                      if(isset($displaytype)){ 
                        if($displaytype=="createnewuser"){

                    ?>
                        <div class="col-md-12">
                            <label>Access Level</label>
                            <select name="accesslevel" class="form-control">
                            	<option value="superuser">Root Admin</option>
                              <option value="bloguser">Blog</option>


                	  	      </select>
                        </div>
                    <?php
                      }else if($displaytype=="createnewoperator"){
                    ?>
                        <input name="accesslevel" type="hidden" value="operator"/>
                    <?php
                      }
                    }
                    ?>
                    <div class="col-md-4">
                        <div class="form-group">
                          <label>User Image</label>
                          <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-file-image-o"></i>
                              </div>
                              <input type="file" class="form-control" name="contentpic" value=""/>
                           </div><!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-4" style="">
              		    <div class="form-group">
                        <label>Fullname</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-user"></i>
                            </div>
            			         <input type="text" class="form-control" name="fullname" value="" Placeholder="Provide the fullname"/>
                         </div><!-- /.input group -->
                      </div>
                    </div>
                    <div class="col-md-4" style="">
                      <div class="form-group">
                        <label>Contact Number</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-phone"></i>
                            </div>
                           <input type="text" class="form-control" name="phonenumber" value="" Placeholder="Provide phone number"/>
                         </div><!-- /.input group -->
                      </div>
                    </div>
                    <div class="col-md-6" style="">
              		    <div class="form-group">
                        <label>Username <small>(valid email address)</small></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-at"></i>
                            </div>
                            <input type="text" class="form-control" name="username" value="" Placeholder="Provide the email"/>
                            <input type="hidden" class="form-control" name="susername" data-verified="false"/>
                         </div><!-- /.input group -->
                      </div>
                    </div>
                    <div class="col-md-6" style="">
              		    <div class="form-group">
                        <label>Password</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-lock"></i>
                            </div>
                            <input type="password" class="form-control" data-sa="true" data-type="password"  name="password" value="" Placeholder="Place user password here"/>
                            <div class="input-group-addon pshow" title="show">
                              <i class="fa fa-eye-slash"></i>
                            </div>
                         </div><!-- /.input group -->
                      </div>
                    </div>
                    <div class="col-md-12">
            			      <div class="box-footer">
                            <input type="button" class="btn btn-danger" name="submituser" value="Create/Update"/>
                        </div>
                	</div>
                </form>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
  }else if(isset($editview)){
      $id=$row['id'];
      $username=$row['username'];
      $fullname=$row['fullname'];
      $phonenumber=$row['phonenumber'];
      $password=$row['password'];
      $accesslevel=$row['parentheader'];
      $accesslevelid=$row['accesslevel'];
      $status=$row['status'];
      $logpart=md5($host_addr);
      // check the nature of the current viewer
      $uid=$_SESSION['aduid'.$logpart.'']?$_SESSION['aduid'.$logpart.'']:0;
      $sq="SELECT * FROM admin where id=$uid";
      $sqdata=briefquery($sq,__LINE__,'mysqli');
      $udata=$sqdata;
      if($uid>0&&$sqdata['numrows']>0){
        $caccesslevel=$udata['resultdata'][0]['parentheader'];
?>

            <div class="row">
              <div class="col-md-12">
                <form name="contentform" method="POST" enctype="multipart/form-data" onSubmit="return false;" action="<?php echo $host_addr;?>snippets/edit.php">
                  <input type="hidden" name="entryvariant" value="editadminuser"/>
                  <input type="hidden" name="entryid" value="<?php echo $id; ?>"/>
                  <input type="hidden" name="coverid" value="<?php echo $imgid; ?>"/>
                    <?php 
                    if($caccesslevel=="rootadmin"&&
                      ($accesslevel=="rootadmin"||$accesslevel=="tecom"
                        ||$accesslevel=="subcom")){
                    ?>
                      <div class="col-md-12">
                          <label>Access Level</label>
                          <select name="accesslevel"  class="form-control">
                            <option value="rootadmin">Root Admin</option>
                            <option value="tecom">Tecom</option>
                            <option value="subcom">Subcom</option>
                          </select>
                      </div>
                    <?php
                      }else if($caccesslevel=="rootadmin"&&($accesslevel=="operator"||$accesslevel=="suboperator")){
                    ?> 
                        <input name="accesslevel" type="hidden" value="<?php echo $accesslevel;?>"/>

                    <?php
                      }else if($caccesslevel!=="rootadmin"){
                    ?>  
                        <input name="accesslevel" type="hidden" value="<?php echo $accesslevel;?>"/>
                      <?php
                        if($caccesslevel=="operator"){
                      ?>
                          <input type="hidden" name="returl" value="<?php echo $host_addr;?>clientdashboard.php"/>
                      <?php
                        }else if($caccesslevel=="suboperator"){
                      ?>
                          <input type="hidden" name="returl" value="<?php echo $host_addr;?>userdashboard.php"/>
                      <?php
                        }
                      ?>
                    <?php 
                      }
                    ?>
                    <div class="col-md-4">
                        <div class="form-group">
                          <label>User Image</label>
                          <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-bars"></i>
                              </div>
                              <input type="file" class="form-control" name="contentpic" value=""/>
                           </div><!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-4" style="">
                        <div class="form-group">
                          <label>Fullname</label>
                          <div class="input-group">
                              <div class="input-group-addon">
                                <i class="fa fa-bars"></i>
                              </div>
                              <input type="text" class="form-control" name="fullname" value="<?php echo $fullname; ?>" Placeholder="Provide the fullname"/>
                           </div><!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-md-4" style="">
                      <div class="form-group">
                        <label>Contact Number</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-phone"></i>
                            </div>
                           <input type="text" class="form-control" name="phonenumber" value="<?php echo $phonenumber; ?>" Placeholder="Provide phone number"/>
                         </div><!-- /.input group -->
                      </div>
                    </div>
                    <div class="col-md-6" style="">
                      <div class="form-group">
                        <label>Username</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-bars"></i>
                            </div>
                            <input type="text" class="form-control" name="username" value="<?php echo $username; ?>" Placeholder="Provide the username"/>
                            <input type="hidden" class="form-control" name="susername" data-verified="true" value="<?php echo $username; ?>"/>
                            <input type="hidden" class="form-control" name="susernameedit" value="<?php echo $username; ?>"/>
                         </div><!-- /.input group -->
                      </div>
                    </div>
                    <div class="col-md-6" style="">
                      <div class="form-group">
                        <label>Change Password </label>
                        <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-bars"></i>
                            </div>
                            <input type="password" class="form-control" data-sa="true" data-type="password"  name="password" value="" Placeholder="Place user password here"/>
                            <div class="input-group-addon pshow" title="show">
                              <i class="fa fa-eye-slash"></i>
                            </div>
                         </div><!-- /.input group -->
                      </div>
                    </div>
                    <div class="col-md-12">
                      <label>Disable/Enable User Account</label>
                      <select name="status" id="status" class="form-control">
                        <option value="">Choose</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                      </select>
                    </div>
                  
                    <div class="col-md-12">
                      <div class="box-footer">
                          <input type="button" class="btn btn-danger" name="submituseredit" value="Create/Update"/>
                      </div>
                    </div>
                </form>
              </div>

            </div>
            <script>
              $(document).ready(function(){
                  $('form[name=contentform] select[name=status]').val('<?php echo $status?>');
              })
            </script>
<?php
      }else{
?>

<?php
      }
?>
<?php 
  }
?>