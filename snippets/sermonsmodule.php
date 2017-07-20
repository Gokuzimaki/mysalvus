<?php
	function getSingleSermon($id){
		global $host_addr,$host_minipagecount;
		isset($host_minipagecount)?$host_minipagecount:$host_minipagecount=8;
		$row=array();
		$query="SELECT *FROM sermons WHERE id=$id";
		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
		$row=mysql_fetch_assoc($run);
		$title=$row['title'];
		$intro=$row['intro'];
		$audiotype=$row['audiotype']; // variable for checking the kind of audio entry provided for sermon
		$audiocode=$row['audiocode']; // embed code for the audio entry
		$videotype=$row['videotype']; //video type of the sermon posted
		$videocode=$row['videocode']; // video embedcode
		$exactday=$row['exactday']; // sermon date
		$date=$row['date']; // sermon data entry date
		$filecontentout="nonshareable"; //default sermon shareable content output
		$shareaudioout=''; // create the audio share button
		$status=$row['status'];
 		$entrydatem=str_replace(",", "", $date);
 		$mainday=date('d', strtotime($entrydatem));
		$mainmonth=date('M', strtotime($entrydatem)); // three letter month name
		// get the cover photo or assign a default if none was chosen
		$mediaquery="SELECT * FROM media WHERE ownerid=$id AND ownertype='sermon' AND maintype='coverphoto' AND status='active' ORDER BY id DESC";
		$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line 2846");
		$coverdata=mysql_fetch_assoc($mediarun);
		$coverphoto=$host_addr.$coverdata['location'];
		$coverid=$coverdata['id'];
		$showbackground=" show-background";
		$showbackgroundimg='style="background-image: url(\''.$coverphoto.'\');"';
		$medianumrows=mysql_num_rows($mediarun);
		if($medianumrows<1){
			$coverphoto="".$host_addr."images/csiblogdefault.png";
			$coverid=0;
			$showbackground="";
			$showbackgroundimg="";
		}
		$row['coverphoto']=$coverphoto;
		// for holding the selection scripts prompt defaults
		$scriptsout='';
		/*run audio check*/
		$audiotabout="<p class=\"background-once-white\">No audio posted</p>";
		$scriptsout.='$("form[name*=sermon] select[name=audiotype]").val("'.$audiotype.'");';
		$scriptsout.='$("form[name*=sermon] select[name=videotype]").val("'.$videotype.'");';
		// echo $scriptsout;
		$localaudioid=0;
		$audioout="No Audio";
		$audiooutone="";
		$audioouttwo="";
		$mediaquery="SELECT * FROM media where ownertype='sermon' AND ownerid='$id' AND mediatype='audio' AND status='active'";
		$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line ".__LINE__);
		$medianumrows=mysql_num_rows($mediarun);
		if($medianumrows>0){
			$audioout=="No audio"?$audioout="":$audioout;
			$audiotabout="";
			$mediarow=mysql_fetch_assoc($mediarun);
			$localaudioid=$mediarow['id'];
			$audiotabout='
                <audio src="'.$host_addr.''.$mediarow['location'].'" class="sermon-audio" preload="none" controls>Download <a href="##"></a></audio>
            ';
			$audioout="Local";
            $audiooutone='<audio src="'.$host_addr.''.$mediarow['location'].'" style="height:32px;" preload="none" controls>Download <a href="##"></a></audio>';
			/*<div class="item clearfix">
                <div class="small-2 columns">
                    <span class="day">12</span>
                    <span class="month">July</span>
                </div>
                <div class="small-10 columns">
                    <h1>Saying no to the devil</h1>
                    <span class="date">Sunday | 10:00 am</span>
                    <ul class="icons tabs" data-tab role="tablist">
                        <li class="tab-title" role="presentation"><a href="#sermoninfo1" class="hascontent custom-sermon-tab-control" role="tab" tabindex="0" aria-selected="false" aria-controls="sermoninfo1"><i class="fa fa-file-text-o"></i></a></li>
                        <li class="tab-title" role="presentation"><a href="#audiopost1" class="hascontent custom-sermon-tab-control" role="tab" tabindex="0" aria-selected="false" aria-controls="audiopost1"><i class="flaticon-speaker100"></i></a></li>
                        <li class="tab-title" role="presentation"><a href="#videopost1" class="hasnocontent custom-sermon-tab-control" role="tab" tabindex="0" aria-selected="false" aria-controls="videopost1"><i class="fa fa-film"></i></a></li>
                    </ul>
                    <div class="tabs-content">
                    	<section role="tabpanel" aria-hidden="false" class="content active" id="sermoninfo1">
                    		<div class="sermon-info"> Hello world this is information central</div>
                    	</section>
                    	<section role="tabpanel" aria-hidden="false" class="content" id="audiopost1">
                           <audio src="./files/audio/" class="sermon-audio" preload="none" controls>Download <a href="##"></a></audio><br>
                    	</section>
                    	<div role="tabpanel" aria-hidden="false" class="content" id="videopost1">
							<iframe class="sermon-video" src="https://www.youtube.com/embed/ySpefzGHn-c" frameborder="0" allowfullscreen></iframe>
                    	</div>
                    </div>
                </div>
            </div>*/
            $pagelink=$host_addr."csisermons.php?d=$id";
            $shareaudioout='
	            <div class="medium-12 columns sermon-share-point-holder">
	            	<div class="sermon-share-point">
		  				<a href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]='.$pagelink.'&p[images][0]='.$coverphoto.'&p[title]='.$title.'&p[summary]='.$intro.'" class="sermon-share-link" target="_blank"><!--<img src="'.$host_addr.'images/facebookshareimg.jpg"/>--><i class="fa fa-facebook"></i></a>
		        		<a href="http://twitter.com/home?status='.$pagelink.' - '.$title.'" class="sermon-share-link" target="_blank"><!--<img src="'.$host_addr.'images/twittershareimg.jpg">--><i class="fa fa-twitter"></i></a>
	            	</div>
	            </div>
            ';
            $filecontentout=$mediarow['location'];
		}else if($medianumrows<1&&$audiotype=="local"){
			$audiotabout="<div><b>No audio uploaded<b></div>";
			$audiooutone="<b>No audio uploaded<b>";
		}
		if($audiocode!==""){
			$audioout=="No audio"?$audioout="":$audioout;
			$audiotabout="<div class=\"cloud-audio\">$audiocode</div>"; //<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/154798838&amp;auto_play=false&amp;hide_related=true&amp;show_comments=false&amp;show_user=false&amp;show_reposts=false&amp;visual=true"></iframe>
			// check to see if audioout already has a value then determine if tthis embed entry is the second or first thing that should show up
			$audioout!=="No audio"&&$audioout!==""?$audioout.=", Embed":$audioout.="Embed";
			$audioouttwo=$audiotabout;
		}else if($audiocode==""&&$audiotype=="embed"){
			$audiotabout="<div><b>Missing embed code<b></div>";
			$audioouttwo=$audiotabout;
		}
		// final audio check for content
		$audioout!=="No audio"?$audioout="Yes ($audioout)":$audioout;
		$selectscripts='
			<script>
				$(document).ready(function(){
					'.$scriptsout.'
				});
			</script>
		';
		/*run video check*/
		$videotabout="<p class=\"background-once-white\">No video posted</p>";
		$videoout="No Video";
		$videooutone="";
		$videoouttwo="";
		$localvideoflvid=0;
		$localvideomp4id=0;
		$localvideo3gpid=0;
		$localvideoflvup="";
		$localvideomp4up="";
		$localvideo3gpup="";
		$shareout="";
		$mediaquery="SELECT * FROM media where ownertype='sermon' AND ownerid='$id' AND maintype='sermonvideo' AND status='active'";
		$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line ".__LINE__);
		$medianumrows=mysql_num_rows($mediarun);
		if($medianumrows>0){
			$videoout=="No Video"?$videoout="":$videoout;
			$videotabout="";
			$videoout.="Local";
			$srcout="";
			while($mediarow=mysql_fetch_assoc($mediarun)){
				$srcout.='<source src="'.$host_addr.''.$mediarow['location'].'"></source>';
				// assign the values for ech video type id when available
				if($mediarow['mediatype']=="videoflv"){
					$localvideoflvid=$mediarow['id'];
					$localvideoflvup='<i class="fa fa-check"></i>';
				}
				if($mediarow['mediatype']=="videomp4"){
					$localvideomp4id=$mediarow['id'];
					$localvideomp4up='<i class="fa fa-check"></i>';
				}
				if($mediarow['mediatype']=="video3gp"){
					$localvideo3gpid=$mediarow['id'];
					$localvideo3gpup='<i class="fa fa-check"></i>';
				}
			}
			$videotabout='
                <video class="sermon-video" preload="none" controls>
                	'.$srcout.'
                </video>
            ';
            $videooutone=$videotabout;
		}else if($medianumrows<1&&$videotype=="local"){	
			$videotabout="<div><b>No video uploaded<b></div>";
			$videooutone="<b>No video uploaded<b>";
		}
		if($videocode!==""){
			$videoout=="No Video"?$videoout="":$videoout;
			$videotabout="<div class=\"cloud-video\">$videocode</div>"; // <iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/154798838&amp;auto_play=false&amp;hide_related=true&amp;show_comments=false&amp;show_user=false&amp;show_reposts=false&amp;visual=true"></iframe>
			$videoout!=="No Video"&&$videoout!==""?$videoout.=", Embed":$videoout.="Embed";
			$videoouttwo=$videotabout;
		}else if($videocode==""&&$videotype=="embed"){
			$videotabout="<div><b>Missing embbed code<b></div>";
			$videoouttwo=$videotabout;
		}
		$videoout!=="No Video"?$videoout="Yes ($videoout)":$videoout;
		$row['adminoutput']='
			<tr data-id="'.$id.'">
			  	<td class="tdimg"><img src="'.$coverphoto.'"/></td><td>'.$title.'</td><td class="tdaudio">'.$audioout.'</td><td class="tdvideo">'.$videoout.'</td><td>'.$exactday.'</td><td>'.$date.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsinglecsisermon" data-divid="'.$id.'">Edit</a></td>
			</tr>
			<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'">
				<td colspan="100%">
					<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
						<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
															
						</div>
					</div>
				</td>
			</tr>
		';
		$row['adminoutputtwo']='
			<div class="row" style="background-color:#fefefe;">
				<form action="../snippets/edit.php" name="editcsisermonform" enctype="multipart/form-data" method="post">
					<input type="hidden" name="entryvariant" value="editcsisermon"/>
					<input type="hidden" name="entryid" value="'.$id.'"/>
					<input type="hidden" name="imgid" value="'.$coverid.'"/>
					<input type="hidden" name="localaudioid" value="'.$localaudioid.'"/>
					<input type="hidden" name="localvideoflvid" value="'.$localvideoflvid.'"/>
					<input type="hidden" name="localvideomp4id" value="'.$localvideomp4id.'"/>
					<input type="hidden" name="localvideo3gpid" value="'.$localvideo3gpid.'"/>
					<div id="formheader">Edit Sermon: '.$title.'.</div>
					<div class="text-center">* means the field is required.</div>	
					<div class="col-md-12">				
						<div class="col-md-4">
							Title *<br>
							<input type="text" placeholder="Enter Title" name="title" value="'.$title.'" class="form-control"/>
						</div>
						<div class="col-md-4">
							Sermon Date *<br>
							<input type="text" placeholder="Format: 18th July 2015 | 10:04 AM" name="sermondate" value="'.$exactday.'" class="form-control"/>
						</div>
						<div class="col-md-4">
							Cover Photo <br>
							<input type="file" placeholder="Choose image" name="profpic" class="form-control"/>
						</div>
					</div>
					<div class="col-md-12">
						Intro *<br>
						<textarea placeholder="Provide a very short description or introduction for this sermon" name="intro" class="form-control">'.$intro.'</textarea>
					</div>
					<div class="col-md-12 emu-row">
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
							'.$audiooutone.'
							<input type="file" placeholder="Choose a mp3 file" name="audio" class="form-control"/>
						</div>
						<div class="col-md-6" data-name="embed" >
							Audio Embed Code <br>
							<textarea placeholder="Provide the audio embed code" name="audioembed"  class="form-control">'.$audiocode.'</textarea>
							'.$audioouttwo.'
						</div>
					</div>
					<div class="col-md-12 emu-row">
						<h4 class="emu-row section-marker-header text-center"><i class="fa fa-film"></i> Video Section</h4>
						<div class="col-md-12 emu-row text-center">
							Video Type <br>
							<select name="videotype">
								<option value="">--Choose--</option>
								<option value="local">Local Video</option>
								<option value="embed">Embedded</option>
							</select>
						</div>
						<div class="col-md-12 emu-row" data-name="localvideo" >
							Video Files *(you can upload more than one video codec type as specified, but it is advisable you do your video uploads one at a time i.e upload first, edit and upload more later)<br>
							<p class="col-md-4">
								FLV '.$localvideoflvup.'<br>
								<input type="file" placeholder="Choose a video file" name="videoflv" class="curved"/>
							</p>
							<p class="col-md-4">
								MP4 '.$localvideomp4up.'<br>
								<input type="file" placeholder="Choose a video file" name="videomp4" class="curved"/>
							</p>
							<p class="col-md-4">
								3GP '.$localvideo3gpup.'<br>
								<input type="file" placeholder="Choose a video file" name="video3gp" class="curved"/>
							</p>
							'.$videooutone.'
						</div>
						<div class="col-md-12 emu-row" data-name="embedvideo" >
							Video Embed Code *<br>
							<textarea placeholder="Place your embed code here" name="videoembed"  class="form-control">'.$videocode.'</textarea>
							'.$videoouttwo.'
						</div>
					</div>
					<div class="col-md-12 emu-row">
		                <label>Enable/Disable this</label>
		                <select name="status" id="status" class="form-control">
		                	<option value="">Choose</option>
		                	<option value="active">Active</option>
		                	<option value="inactive">Inactive</option>
				  	    </select>
		            </div>
					'.$selectscripts.'
					<div class="col-md-12">
						<input type="button" name="editcsisermon" value="Submit" class="submitbutton"/>
					</div>
				</form>
			</div>
		';
		$row['vieweroutput']='
			<div class="item clearfix '.$showbackground.'"'.$showbackgroundimg.'>
                <div class="small-2 columns">
                    <span class="day">'.$mainday.'</span>
                    <span class="month">'.$mainmonth.'</span>
                </div>
                <div class="small-10 columns">
                    <h1>'.$title.'</h1>
                    <span class="date">'.$exactday.'</span>
                    <ul class="icons tabs" data-tab role="tablist">
                        <li class="tab-title" role="presentation"><a href="#sermoninfo'.$id.'" class="hascontent custom-sermon-tab-control" role="tab" tabindex="0" aria-selected="false" aria-controls="sermoninfo'.$id.'"><i class="fa fa-file-text-o"></i></a></li>
                        <li class="tab-title active" role="presentation"><a href="#audiopost'.$id.'" class="hascontent custom-sermon-tab-control" role="tab" tabindex="0" aria-selected="true" aria-controls="audiopost'.$id.'"><i class="flaticon-speaker100"></i></a></li>
                        <li class="tab-title" role="presentation"><a href="#videopost'.$id.'" class="hasnocontent custom-sermon-tab-control" role="tab" tabindex="0" aria-selected="false" aria-controls="videopost'.$id.'"><i class="fa fa-film"></i></a></li>
						<!--<li class="tab-title" role="presentation"><a href="#share'.$id.'" class="hasnocontent custom-sermon-tab-control" role="tab" tabindex="0" aria-selected="false" aria-controls="share'.$id.'"><i class="fa fa-share-alt"></i></a></li>-->
                    </ul>
                    <div class="tabs-content">
                    	<section role="tabpanel" aria-hidden="true" class="content" id="sermoninfo'.$id.'">
                    		<div class="sermon-info"> '.$intro.'</div>
                    	</section>
                    	<section role="tabpanel" aria-hidden="false" class="content active" id="audiopost'.$id.'">
	                		'.$audiotabout.'
	                	</section>
                    	<section role="tabpanel" aria-hidden="true" class="content" id="videopost'.$id.'">
                    		'.$videotabout.'
                    	</section>
                    	<!--<section role="tabpanel" aria-hidden="true" class="content" id="share'.$id.'">
							'.$shareout.'
                    	</section>-->
                    </div>
                </div>
            </div>
		';
		$row['vieweroutputtwo']='
			<div class="medium-12 columns sermon-content-hold">
				<div class="box">
	                <div class="clearfix bordered">
	                    <div class="photo">
	                        <img src="'.$coverphoto.'" alt="'.$title.'"/>
	                		'.$shareaudioout.'
	                    </div>
	                    <div class="info">
	                        <h1>'.$title.'</h1>
	                        <span class="date">'.$exactday.'</span>
	                        <div>'.$intro.'</div>
	                        <div class="clearfix">
	                            <div class="icon-wrapper">
	                                <ul class="icons tabs" data-tab role="tablist">
	                                    <li class="tab-title active" role="presentation"><a href="#audiopost'.$id.'" class="hascontent custom-sermon-tab-control active" role="tab" tabindex="0" aria-selected="true" aria-controls="audiopost'.$id.'"><i class="flaticon-speaker100"></i></a></li>
	                                    <li class="tab-title" role="presentation"><a href="#videopost'.$id.'" class="hasnocontent custom-sermon-tab-control" role="tab" tabindex="0" aria-selected="false" aria-controls="videopost'.$id.'"><i class="fa fa-film"></i></a></li>
	                                    <!--<li class="tab-title" role="presentation"><a href="#share'.$id.'" class="hasnocontent custom-sermon-tab-control" role="tab" tabindex="0" aria-selected="false" aria-controls="share'.$id.'"><i class="fa fa-share-alt"></i></a></li>-->
	                                </ul>
	                            </div>
	                            <div class="tabs-content">
	                            	<section role="tabpanel" aria-hidden="false" class="content active" id="audiopost'.$id.'">
	                                   	'.$audiotabout.'
	                            	</section>
	                            	<section role="tabpanel" aria-hidden="true" class="content" id="videopost'.$id.'">
										'.$videotabout.'
	                            	</section>
	                            	<!--<section role="tabpanel" aria-hidden="true" class="content" id="share'.$id.'">
										'.$shareout.'
	                            	</section>-->
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
		';
		$row['vieweroutputsingle']='
			<div class="medium-12 columns sermon-content-hold single-sermon-content-hold">
				<div class="box">
	                <div class="clearfix bordered">
	                    <div class="photo">
	                        <img src="'.$coverphoto.'" alt="'.$title.'"/>
	                    </div>
	                    <div class="info">
	                        <h1>'.$title.'</h1>
	                        <span class="date">'.$exactday.'</span>
	                        <div>'.$intro.'</div>
	                        <div class="clearfix">
	                            <div class="icon-wrapper">
	                                <ul class="icons tabs" data-tab role="tablist">
	                                    <li class="tab-title active" role="presentation"><a href="#audiopost'.$id.'" class="hascontent custom-sermon-tab-control active" role="tab" tabindex="0" aria-selected="true" aria-controls="audiopost'.$id.'"><i class="flaticon-speaker100"></i></a></li>
	                                    <li class="tab-title" role="presentation"><a href="#videopost'.$id.'" class="hasnocontent custom-sermon-tab-control" role="tab" tabindex="0" aria-selected="false" aria-controls="videopost'.$id.'"><i class="fa fa-film"></i></a></li>
	                                    <!--<li class="tab-title" role="presentation"><a href="#share'.$id.'" class="hasnocontent custom-sermon-tab-control" role="tab" tabindex="0" aria-selected="false" aria-controls="share'.$id.'"><i class="fa fa-share-alt"></i></a></li>-->
	                                </ul>
	                            </div>
	                            <div class="tabs-content">
	                            	<section role="tabpanel" aria-hidden="false" class="content active" id="audiopost'.$id.'">
	                                   	'.$audiotabout.'
	                            	</section>
	                            	<section role="tabpanel" aria-hidden="true" class="content" id="videopost'.$id.'">
										'.$videotabout.'
	                            	</section>
	                            	<!--<section role="tabpanel" aria-hidden="true" class="content" id="share'.$id.'">
										'.$shareout.'
	                            	</section>-->
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
		';
		//legacy audio only output, an old design from the previous csi blog
		$row['vieweroutputthree']='
			<div class="medium-12 columns legacy-sermon-disposition">
				<div class="medium-4 columns legacy-sermon-image-hold">
	                <img src="'.$coverphoto.'" alt="'.$title.'"/>
	                '.$shareaudioout.'
				</div>
				<div class="medium-8 columns legacy-sermon-content-hold">
					<span class="title">
						'.$title.'
					</span>
					<span class="date">
						'.$exactday.'
					</span>
					<div class="audio-panel">'.$audiotabout.'</div>
				</div>
			</div>
		';
		$row['filecontentout']=$filecontentout;
		return $row;
	}
	function getAllSermons($viewer,$limit){
		global $host_addr;
		$row=array();
		str_replace("-", "", $limit);
		$testit=strpos($limit,"-");
		$testit?$limit="":$limit=$limit;
		$outputtype="sermons";
		if ($viewer=="admin") {
			# code...
			$query="SELECT * FROM sermons ORDER BY id DESC $limit";
			$rowmonitor['chiefquery']="SELECT * FROM sermons ORDER BY id DESC";
		}else if ($viewer=="viewer") {
			$query="SELECT * FROM sermons WHERE status='active' ORDER BY id DESC $limit";
			$rowmonitor['chiefquery']="SELECT * FROM sermons WHERE status='active' ORDER BY id,date DESC";
		}else if(is_array($viewer)){
			$subtype=$viewer[0];
			$searchval=$viewer[1];
			$viewer=$viewer[2];
 		  	$outputtype="sermonsearch|$subtype|$searchval|$viewer";
			if($subtype=="sermonname"&&$viewer=="admin"){
				$query="SELECT * FROM sermons WHERE (title LIKE '%$searchval%' AND status='active') OR (title LIKE '%$searchval%' AND status='inactive') $limit";
		    	$rowmonitor['chiefquery']="SELECT * FROM sermons WHERE (title LIKE '%$searchval%' AND status='active') OR (title LIKE '%$searchval%' AND status='inactive')";
			}elseif($subtype=="sermonname"&&$viewer=="viewer"){
				$query="SELECT * FROM sermonss WHERE title LIKE '%$searchval%' AND status='active' $limit";
		    	$rowmonitor['chiefquery']="SELECT * FROM sermons WHERE title LIKE '%$searchval%' AND status='active'";
			}elseif($subtype=="sermonstatus"&&$viewer=="admin"){
				$query="SELECT * FROM sermons WHERE status ='$searchval' $limit";
		    	$rowmonitor['chiefquery']="SELECT * FROM sermons WHERE status ='$searchval'";
			}elseif($subtype=="advancedrecruitsearch"&&$viewer=="admin"){
				$query= $searchval." ".$limit;
		    	$rowmonitor['chiefquery']=$searchval;
			}else{
				echo "search parameters unrecognized, contact your developer";
			}
		}
		$vieweroutput='
			<div class="item clearfix">
                <div class="small-7 columns">
                    <h1>No Sermons Yet</h1>
                </div>
            </div><!-- /.item -->
        ';
        $vieweroutputtwo='<section class="latests-section"><h2>Sorry, no sermons are available.</h2></section>';
        $adminoutput="<td>No Sermons posted</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
        $selection="";
		$run=mysql_query($query)or die(mysql_error()." ".__LINE__);
		$numrows=mysql_num_rows($run);
		$top='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>CoverPhoto</th><th>Title</th><th>Audio</th><th>Video</th><th>Sermon Date</th><th>Entry Date</th><th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		$bottom='	</tbody>
				</table>';

		$monitorpoint="";
		if($numrows>0){
			$vieweroutput="";
			$vieweroutputtwo="";
			$vieweroutputthree="";
			$even="";
			$odd="";
			$adminoutput="";
			$count=1;
			while($row=mysql_fetch_assoc($run)){
				$outs=getSingleSermon($row['id']);
				$adminoutput.=$outs['adminoutput'];
				$vieweroutput.=$outs['vieweroutput'];
				if($count%2==0){
					$even.=$outs['vieweroutputtwo'];
				}else{
					$odd.=$outs['vieweroutputtwo'];
				}
				$vieweroutputtwo.=$outs['vieweroutputtwo'];
				$vieweroutputthree.=$outs['vieweroutputthree'];
				$count++;
			}
			if($count==1){
				$vieweroutputtwo='<div class="medium-12 columns">'.$vieweroutputtwo.'</div>';
			}else{
				$vieweroutputtwo='<div class="medium-6 columns">'.$even.'</div><div class="medium-6 columns">'.$odd.'</div>';
			}
		}
		$outs=paginatejavascript($rowmonitor['chiefquery']);
		$paginatetop='
			<div id="paginationhold">
				<div class="meneame">
					<input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
					<input type="hidden" name="outputtype" value="'.$outputtype.'"/>
					<input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
					<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
					<div class="pagination">
						  '.$outs['usercontrols'].'
					</div>
				</div>
			</div>
			<div id="paginateddatahold" data-name="contentholder">
		';
		$paginatebottom='
			</div><div id="paginationhold">
				<div class="meneame">
					<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
				</div>
			</div>
		';
		$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
		$row['adminoutputtwo']=$top.$adminoutput.$bottom;
		$row['vieweroutput']=$vieweroutput;
		$row['vieweroutputtwo']=$vieweroutputtwo;
		$row['vieweroutputthree']=$vieweroutputthree;
		$row['selection']=$selection;
		$row['numrowsactive']=$numrows;
		return $row;
	}
?>