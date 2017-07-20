				<?php
					$logpart=md5($host_addr);
					$outs=getAllBlogTypes("admin","");
					$eopts="";
					$alevel=isset($_SESSION['accesslevel'.$logpart.''])?$_SESSION['accesslevel'.$logpart.'']:0;
					if($alevel==3){
						$outs=getAllBlogTypes("specialsingle","5");
						/*$eopts='<option value="gallery">Gallery</option>
								<option value="banner">Banner</option>';*/
					}
				?>
				<script>
				//reload tinymce to see this DOM entry
					$(document).ready(function(){
						/*$.cachedScript( "../scripts/js/tinymce/tinymce.min.js" ).done(function( script, textStatus ) {
						  console.log( textStatus );
						});
						$.cachedScript( "../scripts/js/tinymce/basic_config.js" ).done(function( script, textStatus ) {
						  console.log( textStatus );
						});
						*/
					});
				</script>
			<div id="form" style="background-color:#fefefe;">
				<form action="../snippets/basicsignup.php" id="formblogpost" onSubmit="// return false;" name="blogpost" method="post" enctype="multipart/form-data">
					<input type="hidden" name="entryvariant" value="createblogpost"/>
					<div id="formheader">Create Blog Post</div>
					* means the field is required, make sure you choose a blog type first, then click on the
					category selection to load the associated categories there for you to choose.
						<div id="formend">
							Choose a blog type *<br>
							<select name="blogtypeid" class="curved2">
								<option value="">--Choose--</option>
								<?php echo $outs['selection'];?>
							</select>
						</div>
						<div id="formend" data-name="categoryselection">
							Choose a category*<br>
							<select name="blogcategoryid" class="curved2">
								<option value="">--Choose--</option>
							</select>
						</div>
						<div id="formend" data-name="blogentryselection">
							Blog Entry Type<br>
							<select name="blogentrytype" class="curved2">
								<option value="normal">Normal</option>
								<option value="gallery">Gallery</option>
								<option value="banner">Banner</option>
								<option value="video">Video</option>
								<option value="audio">Audio</option>
							</select>
						</div>
						<div id="formend" data-name="galleryentry">
							<div id="formend">
								Choose Gallery Photos for this post(More can be uploaded later):<br>
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
						</div>
						<div id="formend" data-name="bannerpicentry">
							Banner Image *<br>
							<input type="file" placeholder="Choose image" name="bannerpic" class="curved"/>
						</div>
						<div id="formend" data-name="coverphoto">
							Cover Photo *<br>
							<input type="file" placeholder="Choose image" name="profpic" class="curved"/>
						</div>
						<div id="formend" data-name="coverphotofloat">
						Cover Photo Float(Controls the position of the cover photo image place your mouse on an option to see a description of what it would do)<br>
							<select name="coverstyle" class="curved2">
								<option value="">Change Style</option>
								<option value="0" title="The Blog text starts inline beside the cover photo on it's left">Left</option>
								<option value="1" title="The Blog text starts underneath the cover photo">New Line</option>
								<option value="2" title="The Blog text starts inline beside the cover photo on it's right">Right</option>
							</select>
						</div>
						<div id="formend">
							Title*<br>
							<input type="text" placeholder="Blog Title" name="title" style="width:90%;" class="curved"/>
						</div>
						<div class="col-md-12 emu-row" data-name="audiosection">
							<h4 class="emu-row section-marker-header text-center"><i class="fa fa-volume-up"></i> Audio Section</h4>
							<div class="col-md-12 emu-row text-center">
								Audio Type <br>
								<select name="audiotype">
									<option value="">--Choose--</option>
									<option value="local">Local Audio</option>
									<option value="embed">Embedded</option>
								</select>
							</div>
							<div class="col-md-6" data-name="local" >
								Audio file *<br>
								<input type="file" placeholder="Choose a mp3 file" name="audio" class="form-control"/>
							</div>
							<div class="col-md-6" data-name="embed" >
								Audio Embed Code <br>
								<textarea placeholder="Provide the audio embed code" name="audioembed"  class="form-control"></textarea>
							</div>
						</div>
						
						<div class="col-md-12 emu-row" data-name="videosection">
							<h4 class="emu-row section-marker-header text-center"><i class="fa fa-film"></i> Video Section</h4>
							<div class="col-md-12 emu-row text-center">
								Video Type <br>
								<select name="videotype">
									<option value="">--Choose--</option>
									<option value="local">Local Video</option>
									<option value="embed">Embedded</option>
								</select>
							</div>
							<div class="col-md-12 emu-row" data-name="localvideo">
								Video Files *(you can upload more than one video codec type as specified, but it is advisable you do your video uploads one at a time i.e upload first, edit and upload more later)<br>
								<p class="col-md-4">
									FLV<br>
									<input type="file" placeholder="Choose a video file" name="videoflv" class="form-control"/>
								</p>
								<p class="col-md-4">
									MP4<br>
									<input type="file" placeholder="Choose a video file" name="videomp4" class="form-control"/>
								</p>
								<p class="col-md-4">
									3GP<br>
									<input type="file" placeholder="Choose a video file" name="video3gp" class="form-control"/>
								</p>
							</div>
							<div class="col-md-12 emu-row" data-name="embedvideo">
								Video Embed Code *<br>
								<textarea placeholder="Place your embed code here" name="videoembed"  class="form-control"></textarea>
							</div>
						</div>
						<div id="formend" data-name="introparagraph">
							<span style="font-size:18px;">Introductory Paragraph*:</span><br>
							<textarea name="introparagraph" id="postersmalltwo"></textarea>
						</div>
						<div id="formend" data-name="blogentry">
							<span style="font-size:18px;">The Blog Post*:</span><br>
							<textarea name="blogentry" id="adminposter"></textarea>
						</div>
						<div class="col-md-12" data-name="seosection">
							<h4 class="emu-row section-marker-header text-center"><i class="fa fa-site-map"></i> SEO Section</h4>
							<div class="col-md-12 text-center">
							 	SEO key words(comma seperated list of keywords internet user could use in searching for this post)<br>
								<textarea placeholder="Provide the keywords" name="seometakeywords"  class="form-control"></textarea>
							</div>
						</div>
						<div class="col-md-12 emu-row" data-name="schedulesection">
							<h4 class="emu-row section-marker-header text-center"><i class="fa fa-clock-o"></i> Schedule Section</h4>
							<div class="col-md-12" style="height:46px;margin-bottom:46px;">
							    <!-- <div class="bootstrap-timepicker"> -->
					                <div class="form-group bootstrap-timepicker">
					                  <label>Schedule Status:(putting this on disables the post until the time specified is reached)</label>
					                  <div class="input-group">
					                    <div class="input-group-addon">
					                      <i class="fa fa-clock-o"></i>
					                    </div>
					                    <select name="schedulestatus" class="form-control">
											<option value="">--Choose--</option>
											<!-- <option value="no">No</option> -->
											<option value="yes">Yes</option>
										</select>
					                  </div><!-- /.input group -->
					                </div><!-- /.form group -->
			                    <!-- </div> -->
				            </div>
							<div class="col-md-6">
								<!-- Date range -->
				                <div class="form-group">
				                    <label>Post Date(Make sure it is a future date or time):</label>
				                    <div class="input-group">
				                      <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>
				                      </div>
				                      <input type="text" name="scheduledate" class="form-control pull-right" id="reservation"/>
				                    </div><!-- /.input group -->
				                </div><!-- /.form group -->	
							</div>
							<div class="col-md-6" style="height:46px;margin-bottom:46px;">
							    <!-- <div class="bootstrap-timepicker"> -->
					                <div class="form-group bootstrap-timepicker">
					                  <label>Post Time(default is 08:00, 24 hour clock):</label>
					                  <div class="input-group">
					                    <div class="input-group-addon">
					                      <i class="fa fa-clock-o"></i>
					                    </div>
					                    <input type="text" name="scheduletime" class="form-control timemask" value="08:00"/>
					                  </div><!-- /.input group -->
					                </div><!-- /.form group -->
			                    <!-- </div> -->
				            </div>
						</div>
					<div id="formend">
						<input type="button" name="createblogpost" value="Submit" class="submitbutton"/>
					</div>
				</form>
			</div>