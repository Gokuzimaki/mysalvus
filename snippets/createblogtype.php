			<div id="form" style="background-color:#fefefe;">
				<form action="../snippets/basicsignup.php" name="blogtype" method="post">
					<input type="hidden" name="entryvariant" value="createblogtype"/>
					<div id="formheader">Create Blog Type</div>
					* means the field is required.
						<div id="formend">
							Blog Name *<br>
							<input type="text" placeholder="Enter Blog Name" name="name" class="curved"/>
						</div>
						<div id="formend">
							Blog Description *<br>
							<textarea name="description" placeholder="Enter Blog Description" class=""></textarea>
						</div>
					<div id="formend">
						<input type="button" name="createblogtype" value="Submit" class="submitbutton"/>
					</div>
				</form>
			</div>