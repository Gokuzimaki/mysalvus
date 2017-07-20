<div class="box">
	<div class="box-header with-border">
	    <h4 class="box-title">
	        <i class="fa fa-sliders"></i> Incidents Export
	    </h4>
	</div>
	<div class="box-body box-body-padded">
		<div class="col-md-12">
			<!-- Export Type -->
	        <div class="form-group">
	            <label>Export Type:</label>
	            <div class="input-group">
	              <div class="input-group-addon">
	                <i class="fa fa-calendar"></i>
	              </div>
	              <select name="rangetype" class="form-control">
		              	<option value="all">All</option>
		              	<option value="grouped">Option Group</option>
	              </select>
	            </div><!-- /.input group -->
	            <label>Anonymity Setting:</label>

	            <div class="input-group">
	              <div class="input-group-addon">
	                <i class="fa fa-user-secret"></i>
	              </div>
	              <select name="anonymitytype" class="form-control">
		              	<option value="unanonymous">Plain</option>
		              	<option value="anonymous">Anonymous</option>
	              </select>
	            </div><!-- /.input group -->
	        </div><!-- /.form group -->	
		</div>
		<div class="col-md-12 optioncontent hidden">
			<div class="col-md-6">
				<div class="form-group">
					<label>State:</label>
					<div class="input-group">
						  <div class="input-group-addon">
						    <i class="fa fa-map-pin"></i>
						  </div>
						  <select class="form-control" name="state">
							  	<option value="">Current State: ALL</option>
							  	<?php $ps=produceStates("",""); echo $ps['selectionoptions'];?>
						  </select>
					</div>
				</div>
			</div>
			<div class="col-md-6">
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
			<div class="col-md-6 ">
				<!-- Date range -->
			      <div class="form-group">
			        <label>Date range:</label>
			        <div class="input-group">
			          <div class="input-group-addon">
			          		<i class="fa fa-calendar"></i>
			          </div>
			          <input type="text" name="dateperiodold" class="form-control pull-right" id="reservationtime" placeholder="choose the date range" data-datepicker="true"/>
			          <input type="text" name="dateperiodnew" class="form-control pull-right" id="reservationtime" placeholder="choose the date range" data-datepicker="true"/>
			        </div><!-- /.input group -->
			      </div><!-- /.form group -->
			</div>
			<div class="col-md-6">
				<!-- Date range -->
			      <div class="form-group">
			        <label>Age range <small>filling only 'Lower age range' produces results for that age and less. The opposite occurs for 'Higher age range'</small>:</label>
			        <div class="input-group">
			          <div class="input-group-addon">
			          		<i class="fa fa-calendar"></i>
			          </div>
			          <input type="number" min="1" name="ageold" class="form-control pull-right" placeholder="Lower age range" />
			          <input type="number" min="1" name="agenew" class="form-control pull-right" placeholder="higher age range" />
			        </div><!-- /.input group -->
			      </div><!-- /.form group -->
			</div>
			<div class="col-md-6">
		    	<div class="form-group">
		          <label>Nature of Incident</label>
		          <div class="input-group">
		              <div class="input-group-addon">
		                <i class="fa fa-street-view"></i>
		              </div>
		          	  <select class="form-control" name="incidentnature">
		          	  	<option value="">--Choose--</option>
		          	  	<option value="rape">Rape</option>
		          	  	<option value="attempted rape">Attempted Rape</option>
		          	  	<option value="stalking">Stalking</option>
		          	  	<option value="sexual harassment">Sexual Harassment</option>
		          	  	<option value="other">Other</option>
		          	  </select>
		          	  <input name="incidentnaturedetails" type="text" class="form-control hidden" placeholder="Specify 'Other' details" />
		          </div>
		        </div>
		    </div>
		</div>
		<div class="loadmask hidden">
			<img class="loadermini" src="<?php echo $host_addr?>images/loading.gif"/>
		</div>
		<div class="col-md-12 event_horizon hidden"></div>
		<div class="col-md-12">
			<div class="box-footer">
	            <input type="button" class="btn btn-danger" name="exportincidents" value="Download">
	        </div>
		</div>
	</div>
</div>
<script data-type="multiscript">
	$(document).ready(function(){
    	/*$('input[data-daterangepicker]').daterangepicker({
            format: 'YYYY-MM-DD'
        });*/
    	$('[data-datetimepicker]').datetimepicker({
            format:"YYYY-MM-DD HH:mm",
            keepOpen:true
    	})

    	$('[data-datepicker]').datetimepicker({
            format:"YYYY-MM-DD",
            keepOpen:true
            // showClose:true
            // debug:true
    	});
    	$('[data-timepicker]').datetimepicker({
            format:"HH:mm",
            keepOpen:true
    	});
    	$('select[name=userlist]').select2({
            theme: "bootstrap"
        });
    	$('select[name=userlist]').select2({
            theme: "bootstrap",
            ajax: {
			    url: "<?php echo $host_addr?>/snippets/display.php",
			    dataType: 'json',
			    delay: 250,
			    data: function (params) {
			      return {
			        q: params.term, // search term
			        page: params.page
			      };
			    },
			    processResults: function (data, params) {
			      // parse the results into the format expected by Select2
			      // since we are using custom formatting functions we do not need to
			      // alter the remote JSON data, except to indicate that infinite
			      // scrolling can be used
			      params.page = params.page || 1;

			      return {
			        results: data.items,
			        pagination: {
			          more: (params.page * 30) < data.total_count
			        }
			      };
			    },
			    cache: true
			  },
			  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
			  minimumInputLength: 1
        });
	})
</script>
