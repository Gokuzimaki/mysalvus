			<form name="appuserform" action="../snippets/basicsignup.php" method="POST" enctype="multipart/form-data">
        		<input name="entryvariant" type="hidden" value="createappuser"/>
        		<input name="entrypoint" type="hidden" value="webapp"/>
        		<div class="box-group" id="contentaccordion">
        			<div class="panel box box-primary">
                      <div class="box-header with-border">
                        <h4 class="box-title">
                          <a data-toggle="collapse" data-parent="#contentaccordion" href="#headBlock">
                            <i class="fa fa-"></i>  Create an App User Account
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