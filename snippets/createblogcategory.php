				<?php
					$outs=getAllBlogTypes("admin","");
				?>
			<div id="form" style="background-color:#fefefe;">
				<form action="../snippets/basicsignup.php" name="blogcategory" method="post" enctype="multipart/form-data">
					<input type="hidden" name="entryvariant" value="createblogcategory"/>

					<div id="formheader">Create Blog Category</div>
					* means the field is required, make sure you choose a blog type first
						<div id="formend">
							Choose a blog type *<br>
							<select name="categoryid" class="curved2">
								<option value="">--Choose--</option>
								<?php echo $outs['selection'];?>
							</select>
						</div>
						<div id="formend">
							Category Name *<br>
							<input type="text" placeholder="Enter Category Name" name="name" class="curved"/>
						</div>
						<!-- <div id="formend">
							Category Image (* for Frankly Speaking Africa)<br>
							<input type="file" placeholder="Choose image" name="profpic" class="curved"/>
						</div>
						<div id="formend">
							Category Subtext (* for Frankly Speaking Africa)<br>
							<input type="text" placeholder="Enter Subtext here" name="subtext" class="curved"/>
						</div> -->
					<div id="formend">
						<input type="button" name="createblogcategory" value="Submit" class="submitbutton"/>
					</div>
				</form>
			</div>