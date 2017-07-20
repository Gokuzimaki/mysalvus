			<div id="form" style="background-color:#fefefe;">
				<form action="../snippets/basicsignup.php" name="advertform" enctype="multipart/form-data" method="post">
					<input type="hidden" name="entryvariant" value="createadvert"/>
					<div id="formheader">Create Advert.</div>
					* means the field is required.
					<div id="formend">
							Advert Page *<br>
							<select name="advertpage" class="curved2">
								<option value="">--Choose--</option>
								<!-- <option value="fc">Frontiers Consulting</option> -->
								<option value="all">All Blog Pages</option>
								<option value="pfn">Project Fix Nigeria Blog Page</option>
								<option value="csi">Christ Society International Outreach Blog Page</option>
								<option value="fs">Frankly Speaking With Muyiwa Afolabi Blog Page.</option>
							</select>
					</div>
					<div id="formend">
						Advert Type *<br>
						<select name="adverttype" class="curved2">
							<option value="">--Choose--</option>
							<!-- <option value="videoadvert">Video</option> -->
							<option value="banneradvert">Banner Advert (width wide image should be used)</option>
							<option value="miniadvert">Mini Advert(for side pane)</option>
						</select>
					</div>
					<div id="formend">
							Advert owner *<br>
							<input type="text" name="advertowner" Placeholder="The advert owner." class="curved"/>
					</div>
					<div id="formend">
							Advert title (Make this short and descriptive)*<br>
							<input type="text" name="adverttitle" Placeholder="The advert title." class="curved"/>
					</div>
					<div id="formend">
							Advert Landing Page *<br>
							<input type="text" name="advertlandingpage" Placeholder="The landing page." class="curved"/>
					</div>
					<div id="formend">
						Advert Image<!--/ File(Video file less than 15MB please in mp4 format) --> *<br>
						<input type="file" placeholder="Choose image" name="profpic" class="curved"/>
					</div>
					<div id="formend">
						<input type="button" name="createadvert" value="Submit" class="submitbutton"/>
					</div>
				
				</form>
			</div>