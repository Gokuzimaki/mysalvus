			<div id="closecontainer" name="closefullcontenthold" data-id="theid" class="altcloseposfour"><img src="./images/closefirst.png" title="Close"class="total"/></div>
			<div id="form" style="background-color:#fefefe;">
				<form action="./snippets/basicsignup.php" name="servicerequestform" method="post">
					<input type="hidden" name="entryvariant" value="createservicerequest"/>
					<div id="formheader">Service Request Form</div>
					* means the field is required.
					<div id="formend">
						<div id="elementholder">
							Full Name *
							<input type="text" name="name" Placeholder="Firstname Lastname" class="curved"/>
						</div>
						<div id="elementholder">
							Organization name 
							<input type="text" name="organizationname" class="curved"/>
						</div>
						<div id="elementholder">
							Team 
							<input type="text" name="team" class="curved"/>
						</div>
					</div>
					<div id="formend">
						<div id="elementholder">
							Dates For Event *
							<input type="text" name="from" title="(Date DD-MM-YYYY, Duration e.g '8:30AM to 5:40PM')" placeholder="From (Date DD-MM-YYYY, Duration e.g '8:30AM to 5:40PM')" class="curved"/>
							<input type="text" name="to" title="(Date DD-MM-YYYY, Duration e.g '8:30AM to 5:40PM')" placeholder="To (Date- DD-MM-YYYY, Duration e.g '8:30AM to 5:40PM')" class="curved"/>
						</div>
						<div id="elementholder">
							Type Of Event *
							<select name="eventtype" class="curved2">
								<option value="">--Choose--</option>
								<option value="Business Speaking">Business Speaking</option>
								<option value="Seminar">Seminar</option>
								<option value="Training">Training</option>
								<option value="Workshop">Workshop</option>
							</select>
						</div>
						<div id="elementholder">
							No of Participants *
							<input type="text" name="expectedattendance" Placeholder="Expected Attendance" class="curved"/>
						</div>

					</div>
					<div id="formend">
						<div id="elementholder">
							Phone*
							<input type="text" name="phone1" class="curved"/>
						</div>
						<div id="elementholder">
							Phone Two
							<input type="text" name="phone2" class="curved"/>
						</div>
						<div id="elementholder">
							Venue*
							<textarea name="venue" id="" class="curved3"></textarea>
						</div>
						<div id="elementholder">
							Question/Comments:
							<textarea name="questioncomments" id="" Placeholder="" class="curved3"></textarea>
						</div>
					</div>
					<div id="formend">
						<input type="button" name="createservicerequest" value="Submit" class="submitbutton"/>
					</div>
					<div id="bottomlabel"><img src="./images/muyiwalogo5.png" class="total"></div>
				</form>
			</div>