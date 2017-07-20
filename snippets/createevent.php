			<div id="form" style="background-color:#fefefe;">
				<form action="../snippets/basicsignup.php" name="eventform" enctype="multipart/form-data" method="post">
					<input type="hidden" name="entryvariant" value="createevent"/>
					<div id="formheader">Create Event.</div>
					* means the field is required.
					<div id="formend">
							Event Type *<br>
							<select name="eventtype" class="curved2">
								<option value="">--Choose--</option>
								<option value="fc">Frontiers Consulting</option>
								<option value="fr">Frontiers Radio</option>
								<option value="pfn">Frankly Speaking Africa</option>
								<option value="csi">Christ Society International Outreach</option>
								<option value="fs">Frankly Speaking With Muyiwa Afolabi.</option>
							</select>
					</div>
					<div id="formend">
						Event Title *<br>
						<input type="text" name="eventtitle" Placeholder="The event title here." class="curved"/>
					</div>
					<div id="formend" data-name="eventcoverphoto">
						Event Cover Photo *<br>
						<input type="file" name="eventcoverphoto" class="curved"/>
					</div>
					<div id="formend" data-name="eventtime">
						Event Time *<br>
						<input type="text" name="eventtime" Placeholder="e.g HH:mm:ss Locale(GMT+01 e.t.c)." class="curved"/>
					</div>

					<div id="formend">
						Date *<br>
						<input type="text" name="dateholder" readonly="true" placeholder="Click Calender below to choose Date" value=""class="curved"/>
						<br>
						<?php
							$currentday=date('d');
							$currentmonth=date('m');
							$currentyear=date('Y');
							$outs=calenderOut($currentday,$currentmonth,$currentyear,'admin','dateholder','','');
							echo $outs['formoutput'];
						?>
					</div>
					<div id="formend">
						Event details*<br>
						<textarea name="eventdetails" id="" placeholder="Place all details of the event here, including more information such as its duration" class="curved3" style="width:447px;height:206px;"></textarea>
					</div>
					<div id="formend">
						<input type="button" name="createevent" value="Submit" class="submitbutton"/>
					</div>
				
				</form>
			</div>