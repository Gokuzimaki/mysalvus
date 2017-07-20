			<div id="form" style="background-color:#fefefe;">
				<form action="../snippets/basicsignup.php" name="galleryform" enctype="multipart/form-data" method="post">
					<input type="hidden" name="entryvariant" value="creategallery"/>
					<div id="formheader">Create an Album.</div>
					* means the field is required.					
					<div id="formend">
							Gallery Title *<br>
							<input type="text" name="gallerytitle" Placeholder="The event title here." class="curved"/>
					</div>
					<div id="formend">
						Gallery details*<br>
						<textarea name="gallerydetails" id="" placeholder="Place all details of the event here, including more information such as its duration" class="curved3" style="width:447px;height:206px;"></textarea>
					</div>
<div id="formend">
			Choose Gallery Photos(More can be uploaded later):<br>
			<input type="hidden" name="piccount" value=""/>
			<select name="photocount" class="curved2" title="Choose the amount of photos you want to upload, max of 10, then click below the selection to continue">
			<option value="">--choose amount--</option>
			<?php
			for($i=1;$i<=10;$i++){
				$pic="";
				$i>1?$pic="photos":$pic="photo";		
				echo'<option value="'.$i.'">'.$i.''.$pic.'</option>';
			}
			?>
			</select>							
		</div>
					<div id="formend">
						<input type="button" name="creategallery" value="Submit" class="submitbutton"/>
					</div>
				
				</form>
			</div>