<div class="row">
	<form name="userform" method="POST" enctype="multipart/form-data" action="../snippets/basicsignup.php">
		<input type="hidden" name="entryvariant" value="createnewuser"/>
        <div class="col-md-12">
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
        <div class="col-md-12">
            <label>Access Level</label>
            <select name="accesslevel" id="surveycategory" class="form-control">
            	<option value="0">NYSC Lagos Admin</option>
            	<option value="1">EKO Kopa Admin</option>
            	<option value="2">CDS Admin</option>
            	<option value="3">SAED Admin</option>
	  	    </select>
        </div>
        <div class="col-md-12" style="">
    		<div class="form-group">
              <label>Fullname</label>
              <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-bars"></i>
                  </div>
				   <input type="text" class="form-control" name="fullname" value="" Placeholder="Provide the fullname"/>
               </div><!-- /.input group -->
            </div>
        </div>
        <div class="col-md-12" style="">
    		<div class="form-group">
              <label>Username</label>
              <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-bars"></i>
                  </div>
                  <input type="text" class="form-control" name="username" value="" Placeholder="Provide the username"/>
               </div><!-- /.input group -->
            </div>
        </div>
        <div class="col-md-12" style="">
    		<div class="form-group">
              <label>Password</label>
              <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-bars"></i>
                  </div>
                  <input type="password" class="form-control" name="password" value="" Placeholder="Place user password here"/>
               </div><!-- /.input group -->
            </div>
        </div>
<!-- 	                <div class="col-md-12">
            <label> Content</label>
            <textarea class="form-control" rows="3" name="intro" id="postersmallthree" placeholder="Provide information concerning what this page is about">'.$contentdata.'</textarea>
        </div> -->
        <div class="col-md-12">
			<div class="box-footer">
                <input type="button" class="btn btn-danger" name="submituser" value="Create/Update"/>
            </div>
    	</div>
    </form>
</div>