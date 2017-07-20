<?php
	$outsdata=getAllEventEntries("admin","");
?>
<div class="box">
	<div class="box-body clearboth">
	    <div class="box-group" id="generaldataaccordion">
			<div class="panel box box-primary">
			    <div class="box-header with-border">
			        <h4 class="box-title">
			          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#NewPageManagerBlock">
			            <i class="fa fa-sliders"></i> New Event
			          </a>
			        </h4>
			    </div>
			    <div id="NewPageManagerBlock" class="panel-collapse collapse">
			        <div class="row">
			            <form action="../snippets/basicsignup.php" name="eventform" onSubmit="return false;" enctype="multipart/form-data" method="post">
							<input type="hidden" name="entryvariant" value="createevent"/>
							<!-- form control section -->
		                    <div class="col-md-12">
		                    	<div class="col-sm-3"> 
		                          <div class="form-group">
		                            <label>Event Title</label>
		                            <div class="input-group">
		                              <div class="input-group-addon">
		                                <i class="fa fa-file-text"></i>
		                              </div>
		                              <input type="text" class="form-control" name="eventtitle" Placeholder="The Event title"/>
		                            </div><!-- /.input group -->
		                          </div><!-- /.form group -->
		                        </div>
		                        <div class="col-sm-3"> 
		                          <div class="form-group">
		                            <label>Event Cover Image</label>
		                            <div class="input-group">
		                              <div class="input-group-addon">
		                                <i class="fa fa-file-image-o"></i>
		                              </div>
		                              <input type="file" class="form-control" name="eventcoverimage" Placeholder="The Cover Image"/>
		                            </div><!-- /.input group -->
		                          </div><!-- /.form group -->
		                        </div>
		                        <div class="col-md-3">
									<!-- Date range -->
						            <div class="form-group">
						                <label>Start Date&Time:</label>
						                <div class="input-group">
						                  <input type="text" name="eventstartdate" id="eventstartdate" placeholder="click/tap to set date and time" class="form-control pull-right"/>
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                </div><!-- /.input group -->
						            </div><!-- /.form group -->	
								</div>
								<div class="col-md-3">
									<!-- Date range -->
						            <div class="form-group">
						                <label>End Date&Time:</label>
						                <div class="input-group">
						                  <input type="text" name="eventenddate" id="eventenddate" placeholder="click/tap to set date and time" class="form-control pull-right"/>
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                </div><!-- /.input group -->
						            </div><!-- /.form group -->	
								</div>
								<div class="col-md-12 marginbottom">
		                            <label>Event Details</label>
		                            <textarea class="form-control" rows="3" name="eventdetails" id="postersmallfive" data-mce="true" placeholder="Event details"></textarea>
		                        </div>
		                        <div class="col-md-12">
			                        <div class="col-sm-4"> 
			                          <div class="form-group">
			                            <label>Contact Person(s)<br><small>Use a comma to seperate each person</small></label>
			                            <div class="input-group">
			                              <div class="input-group-addon">
			                                <i class="fa fa-user"></i>
			                              </div>
			                              <input type="text" class="form-control" name="contactperson" Placeholder="The contact person data for information supply"/>
			                            </div><!-- /.input group -->
			                          </div><!-- /.form group -->
			                        </div>
			                        <div class="col-md-4">
							            <div class="form-group">
							                <label>Contact Phonenumber(s)<br><small>Use a comma to seperate each number group per persons</small></label>
							                <div class="input-group">
							                  <div class="input-group-addon">
							                    <i class="fa fa-phone"></i>
							                  </div>
							                  <input type="text" name="contactnumber" placeholder="Phonenumber(s)" class="form-control pull-right"/>
							                </div><!-- /.input group -->
							            </div><!-- /.form group -->	
									</div>
									<div class="col-md-4">
							            <div class="form-group">
							                <label>Contact Email(s)<br><small>Use a comma to seperate each email group per persons</small></label>
							                <div class="input-group">
							                  <div class="input-group-addon">
							                    <i class="fa fa-at"></i>
							                  </div>
							                  <input type="text" name="contactemail" placeholder="Provide email address(es)" class="form-control pull-right"/>
							                </div><!-- /.input group -->
							            </div><!-- /.form group -->	
									</div>
		                        </div>
		                    </div>
		                    <div class="col-md-12 dogalleryslides multi_content_hold_generic">
	                        	<h3>Maximum of 10 entries at a time</h3>
	                        	<div class="col-md-12 multi_content_hold" data-type="triggerprogenitor" data-cid="1" data-name="addressslides">
	                        		<h4 class="multi_content_countlabels">Event Location Information (Entry 1)</h4>
	                        		<div class="col-sm-3">
	                        			<div class="form-group">
				                            <label>Location Title <br><small>e.g "Ikeja City Mall"</small></label>
				                            <div class="input-group">
				                              <div class="input-group-addon">
				                                <i class="fa fa-file-text"></i>
				                              </div>
				                              <input type="text" class="form-control" name="locationtitle1" Placeholder="Specify the location title"/>
				                            </div><!-- /.input group -->
				                        </div><!-- /.form group -->
	                        		</div>
	                        		<div class="col-sm-3">
	                        			<div class="form-group">
				                            <label>Address<br><small>Provide the full address for the current location</small></label>
				                            <div class="input-group">
				                              <div class="input-group-addon">
				                                <i class="fa fa-file-text"></i>
				                              </div>
				                              <textarea class="form-control" rows="3" name="address1" Placeholder="Give the Full Address"/></textarea>
				                            </div><!-- /.input group -->
				                        </div><!-- /.form group -->
	                        		</div>
	                        		<div class="col-sm-3">
	                        			<div class="form-group">
				                            <label>Map Latitude value <br><small>must be up to 6 decimal places e.g "30.837462"</small></label>
				                            <div class="input-group">
				                              <div class="input-group-addon">
				                                <i class="fa fa-file-text"></i>
				                              </div>
				                              <input type="text" class="form-control" name="lat1" Placeholder="Specify the location title"/>
				                            </div><!-- /.input group -->
				                        </div><!-- /.form group -->
	                        		</div>
	                        		<div class="col-sm-3">
	                        			<div class="form-group">
				                            <label>Map Longitude value <br><small>must be up to 6 decimal places e.g "30.837462"</small></label>
				                            <div class="input-group">
				                              <div class="input-group-addon">
				                                <i class="fa fa-file-text"></i>
				                              </div>
				                              <input type="text" class="form-control" name="lng1" Placeholder="Specify the location title"/>
				                            </div><!-- /.input group -->
				                        </div><!-- /.form group -->
	                        		</div>
	                        	</div>
	                        	<div name="addressslidesentrypoint" data-marker="true"></div>
	                        	<input name="addressslidescount" type="hidden" value="1" data-valset="1,2" data-valcount="1" data-counter="true"/>
	                        	<input name="addressslidesdatamap" type="hidden" data-map="true" value="locationtitle-:-input<|>
	                        	address-:-textarea<|>
	                        	lat-:-input<|>
	                        	lng-:-input"/>
	                        	<a href="##" 
	                        	   class="generic_addcontent_trigger"
	                        	   data-type="triggerformadd" 
	                        	   data-name="addressslidescount_addlink"
	                        	   data-i-type=""
	                        	   data-limit="10"> 
	                        		<i class="fa fa-plus"></i>Add More?
	                        	</a>
	                        </div> 
		                    <div class="col-md-12">
		                    	<div class="form-group">
					                <label>Is event Bookable<br><small>Specify if site visitors can make bookings for this event</small></label>
					                <div class="input-group">
					                  <div class="input-group-addon">
					                    <i class="fa fa-book"></i>
					                  </div>
					                  <select name="isbookable" class="form-control">
		                                <option value="">Choose</option>
		                                <option value="yes">YES</option>
		                              </select>
					                </div><!-- /.input group -->
					            </div><!-- /.form group -->
		                    </div>
		                    <div class="col-md-12 hidden bookingsrequirements">
		                    	<div class="form-group">
					                <label>Bookings<br><small>Provide bookings requirements for, such as costs and other information</small></label>
					                <div class="input-group">
					                  <div class="input-group-addon">
					                    <i class="fa fa-book"></i>
					                  </div>
		                              <textarea class="form-control" rows="3" name="bookingsrequirement" id="postersmallfivetwo" data-mce="true" placeholder="Booking details"></textarea>
					                </div><!-- /.input group -->
					            </div><!-- /.form group -->
		                    </div>
		                    <input type="hidden" name="formdata" value="eventform"/>
		                    <input type="hidden" name="extraformdata" value="eventtitle-:-input<|>
		                      eventcoverimage-:-input|image<|>
		                      eventstartdate-:-input<|>
		                      eventenddate-:-input<|>
		                      eventdetails-:-textarea<|>
		                      contactperson-:-input<|>
		                      contactnumber-:-input<|>
		                      contactemail-:-input<|>
		                      egroup|data-:-[addressslidescount>|<
		                      				locationtitle-|-input>|<
		                      				address-|-textarea>|<
		                      				lat-|-input>|<
		                      				lng-|-input
		                      				]-:-groupfall[1-2,2-1,1-3-4,3-4,4-3]<|>
		                      isbookable-:-select<|>
		                      bookingsrequirement-:-textarea-:-[group-|-
                      								isbookable-|-select-|-*any*>|<]"/>
		                      <!--  -->
		                    <input type="hidden" name="errormap" value="
		                        eventtitle-:-Please provide the title for this event<|>
			                    eventcoverimage-:-NA<|>
			                    eventstartdate-:-Choose a start date and time for this event<|>
			                    eventenddate-:-Choose an end date and time for this event<|>
			                    eventdetails-:-Provide the details for this event<|>
			                    contactperson-:-Specify the contact person(s) for this event<|>
			                    contactnumber-:-Specify the contact phonenumber(s) for this event<|>
			                    contactemail-:-Specify the contact email(s) for this event<|>
			                    egroup|data-:-[
			                      				Please provide the title for the events venue>|<
			                      				Give the full address of the events venue>|<
			                      				Provide the latitude value for the map display>|<
			                      				Provide the longitude value for the map display
			                      			]<|>
			                    isbookable-:-NA<|>
			                    bookingsrequirement-:-Please provide the bookings requirement information.
			                    "/>
							<div class="col-md-12 clearboth">
				                <div class="box-footer">
				                    <input type="button" class="btn btn-danger" name="createevent" data-formdata="eventform" value="Create Event"/>
				                </div>
				            </div>
						</form>
			        </div>
			    </div>
			    <script data-type="multiscript">
			    	$(document).ready(function(){
			    		$('#eventstartdate').datetimepicker({
				            format:"YYYY-MM-DD HH:mm",
				            keepOpen:true
			        	})
			        	$('#eventenddate').datetimepicker({
				            format:"YYYY-MM-DD HH:mm",
				            keepOpen:true
			        	})
			        	var curmcethreeposter=[];
						callTinyMCEInit("textarea[id*=postersmallfive]",curmcethreeposter);
			    	});
				</script>
			</div>
			<div class="panel box overflowhidden box-primary">
			    <div class="box-header with-border">
			      <h4 class="box-title">
			        <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#EditBlock">
			          <i class="fa fa-gear"></i> Edit Events
			        </a>
			      </h4>
			    </div>
			    <div id="EditBlock" class="panel-collapse collapse in">
			      <div class="box-body">
			          <div class="row">
			            <div class="col-md-12">
			              <?php
			                echo $outsdata['adminoutput'];
			              ?>
			            </div>
			        </div>
			      </div>
			    </div>
			</div>
		</div>
	</div>
	

</div>