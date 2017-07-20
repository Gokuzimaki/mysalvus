<?php
	$curactive="";
	// for products n services proper link creation, a global sentinel variable is created, just in case
	$prodnservcounter=0;
	// check to see if global url admin pointer session variable is active
	if(session_id() == ''){
		session_start();
		$rurladmin=isset($_SESSION['rurladmin'])?$_SESSION['rurladmin']:"";
	}else{
		$rurladmin=isset($_SESSION['rurladmin'])?$_SESSION['rurladmin']:"";
	}

	function getSingleGeneralInfo($id,$datatype="",$contentgroupdata=array(),$curstamp=""){
		include('globalsmodule.php');
		$row=array();
		$query=$datatype==""?"SELECT * FROM generalinfo where id=$id":"SELECT * FROM generalinfo where type='$datatype'";
		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
		$numrows=mysql_num_rows($run);
		$row=mysql_fetch_assoc($run);
		// set curstamp value appropriately

		$curstamp==""&&isset($contentgroupdata['curstamp'])?$curstamp=$contentgroupdata['curstamp']:$curstamp=$curstamp;
		$showhidetitle="";
		$showhideimage="";
		$showhideintro="";
		$showhidecontent="";
		$extraformdata=$rurladmin!==""?'<input type="hidden" name="rurladmin" value="'.$rurladmin.'">':"";
		// Edit form content header and output displays
		$contenttextheaderout="Edit Content Entry";
		$contenttexttitleout="Content Title";
		$contenttextimageout="Content Photo";
		$contenttextintroout="Content Intro";
		$contenttextcontentout="Content Post";
		// Edit form Placeholders  and output displays
		$contentplaceholdertitleout="The title of the entry";
		$contentplaceholderimageout="The Image for the entry";
		$contentplaceholderintroout="The introduction for the entry";
		$contentplaceholdercontentout="The content for the entry";
		// extraformdata positioning variables, each variable puts the extra data at the appropriate point in the form
		$extraformtitle="";
		$extraformimage="";
		$extraformintro="";
		$extraformcontent="";
		$id=$row['id'];
		$maintype=$row['maintype'];
		$subtype=$row['subtype'];
		$subtypestyleout='style="display:none"';
		$title=$row['title'];
		$intro=$row['intro'];
		$content=str_replace("../../", "$host_addr",$row['content']);
		$coverphoto="";
		// check for cover photo
		$mediaquery="SELECT * FROM media WHERE ownertype='$maintype' AND ownerid='$id' AND maintype='coverphoto' AND status='active' ORDER BY id DESC";
		$mediarun=mysql_query($mediaquery)or die(mysql_error()." Line ".__LINE__."<br> $mediaquery <br>");
		$coverdata=mysql_fetch_assoc($mediarun);
		$coverphoto=$coverdata['details']!==""?$coverdata['details']:$coverdata['location'];
		$row['coverid']=$coverdata['id'];
		// echo $mediaquery;	
		$medianumrows=mysql_num_rows($mediarun);

		$coverout=file_exists('.'.$coverphoto.'')?'<img src="'.$host_addr.''.$coverphoto.'" class="defcontentimage"/>':"No image set";
		$pleatoout='<div class="propdataimg2 pull-left"><img src="'.$coverphoto.'" height="100%"/> </div>';
		$imgid=$coverdata['id'];
		$row['coverout']=$coverout;
		$coverpathout=$coverdata['location']!==""?''.$host_addr.''.$coverdata['location'].'':"";
		$coverphoto=isset($coverdata['thumbnail'])?$host_addr.$coverdata['thumbnail']:$host_addr.$coverdata['details'];

		if($medianumrows<1){

			$coverphoto=$host_addr."images/default.gif";
			$coverout="No Image Set";
			$coverpathout=$coverphoto;
			$imgid=0;
			$pleatoout="";
			$row['coverid']=$imgid;
		}else{
			// $coverout='<img src="'.$host_addr.''.$coverphoto.'"/>';
		}
		$row['coverpath']=$coverpathout;
		$date=$row['entrydate'];
		$default=$row['default'];
		$defaultcontent=$row['defaultcontent'];
		$extradata=$row['extradata'];
		if ($extradata!=="") {
			$extexplode=explode("[|><|]",$extradata);
			# code...
			if(count($extexplode)>0){
				
			}
		}
		$status=$row['status'];
		if($maintype=="productservices"){
			// $showhidetitle="display:none;";
			// $showhideimage="display:none;";
			// $showhidecontent="display:none;";
			$showhideintro="display:none;";
			// check for the subservices available to this product if any
			 $subquery="SELECT * FROM generalinfo WHERE maintype='subproductservices' AND subtype='$id'";
			 $subrun=mysql_query($subquery)or die(mysql_error()." Line ".__LINE__);
			 $subnumrows=mysql_num_rows($subrun);
  		 	 $editprodoutput="";
		 	 $editprodselectoutput='';
		 	 $editcount=1;
			 if($subnumrows>0){
				 while($subrow=mysql_fetch_assoc($subrun)){
				 	$subid=$subrow['id'];
				 	$subtitle=$subrow['title'];
				 	$editprodoutput.='
					 	<div class="col-md-6">
					 		<div class="form-group">
			                  <label>Sub product/service Title (<b>'.$editcount.'</b>)</label>
			                  <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-bars"></i>
			                      </div>
			                      <input type="hidden" class="form-control" name="subcontentid'.$editcount.'" value="'.$subid.'"/>
			                      <input type="text" class="form-control" name="subcontenttitle'.$editcount.'" value="'.str_replace('"', "'",$subtitle).'" Placeholder=""/>
			                   </div><!-- /.input group -->
			                </div>
				            <div class="col-md-12">
		                        <label>Disable/Enable this(<b>'.$subrow['status'].'</b>)</label>
		                        <select name="subprodstatus'.$editcount.'" id="subprodstatus'.$editcount.'" class="form-control">
		                        	<option value="">Choose</option>
		                        	<option value="active">Active</option>
		                        	<option value="inactive">Inactive</option>
						  	    </select>
		                    </div>
			            </div>
				 	';
				 	$editcount++;
				 	if($subrow['status']=="active"){
				 		$editprodselectoutput.='<option value="'.$subtitle.'" data-id="'.$id.'">'.$subtitle.'</option>';
				 	}
				 }
			 	$editcount=$subnumrows+1;
			 }
			 $row['productselection']=$editprodselectoutput;
			 // check for a product banner image
			 $submediaquery="SELECT * FROM media WHERE maintype='productbanner' AND ownerid='$id' AND ownertype='productservices' AND status='active'";
			 $submediarun=mysql_query($submediaquery)or die(mysql_error()." Line ".__LINE__);
			 $submedianumrows=mysql_num_rows($submediarun);
			 $prodbannerimgout='No Banner Image set';
			 $prodbannerimgid=0;
			 if($submedianumrows>0){
			 	$submediarow=mysql_fetch_assoc($submediarun);
			 	$prodbannerimgout='
			 		<img src="'.$host_addr.''.$submediarow['location'].'" class="img-responsive pull-left" data-name="prodtabber'.$id.'"/>
			 	';
			 	$prodbannerimgouttwo=$prodbannerimgout;
			 	$prodbannerimgid=$submediarow['id'];
			 }else{
			 	$prodbannerimgouttwo='
				    <img class="img-responsive" data-name="prodtabber'.$id.'" src="img/about/back1.jpg" />
			 	';
			 }
		}elseif ($maintype=="awards") {
			# code...
			// $showhidetitle="display:none;"; 
			$showhideimage="display:none;";
			// $showhidecontent="display:none;";
			$showhideintro="display:none;";
		}elseif ($maintype=="photogallery") {
			# code...
			// $showhidetitle="display:none;";
			// $showhideimage="display:none;";
			$showhidecontent="display:none;";
			// $showhideintro="display:none;";
		}else if($maintype=="defaultsocial"){
			$subtypestyleout="";
			// $showhidetitle="display:none;";
			$showhideimage="display:none;";
			$showhideintro="display:none;";
			$showhidecontent="display:none;";
			$contenttextheaderout="Edit Default Social Entry</small>";
			$contentplaceholdertitleout="Place the web address for this social account here";
			$contenttexttitleout="Web address";
			$extraformtitle='
				<div class="col-md-6">
                    <div class="form-group">
                        <label>Select Social Network</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-file-text"></i>
                            </div>
                            <select name="subtype" class="form-control">
                              <option value="defaultfacebook">Facebook <i class="fa fa-facebook"></i></option>
                              <option value="defaulttwitter">Twitter <i class="fa fa-twitter"></i></option>
                              <option value="defaultlinkedin">LinkedIn <i class="fa fa-linkedin"></i></option>
                              <option value="defaultgoogleplus">Google+ <i class="fa fa-google"></i></option>
                              <option value="defaultpinterest">Pinterest <i class="fa fa-pinterest"></i></option>
                              <option value="defaultskype">Skype <i class="fa fa-skype"></i></option>
                            </select>
                        </div><!-- /.input group -->
                     </div>
                </div>
                ';
		}elseif($maintype=="defaultinfo"){
			$subtypestyleout="";
			// $showhidetitle="display:none;";
			$showhideimage="display:none;";
			$showhideintro="display:none;";
			$showhidecontent="display:none;";
			$contenttextheaderout="Edit Default Data Entry</small>";
			$contentplaceholdertitleout="Place the value for the selected default here";
			$contenttexttitleout="Default Value";
			$subtypeout="";
			$extraformtitle='
				<div class="col-md-6">
                    <div class="form-group">
                        <label>Select Default Type</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-file-text"></i>
                            </div>
                            <select name="subtype" class="form-control">
                              <option value="defaultphonenumbers">Default Phonenumbers</option>
                              <option value="defaultemailaddress">Default Email</option>
                              <option value="defaultmainaddress">Default Main Address</option>
                            </select>
                        </div><!-- /.input group -->
                     </div>
                </div>
            ';
		}else if ($maintype=="careeradvice") {
			# code...
			// $subtypestyleout="";
			// $showhidetitle="display:none;";
			$showhideimage="display:none;";
			$showhideintro="display:none;";
			// $showhidecontent="display:none;";
			$contenttextheaderout="Edit  Career Advice Entry</small>";
			$contentplaceholdertitleout="Place the value for the selected default here";
			$contenttexttitleout="";
			$subtypeout="";
		}else if ($maintype=="businessadvice") {
			# code...
			// $subtypestyleout="";
			// $showhidetitle="display:none;";
			$showhideimage="display:none;";
			$showhideintro="display:none;";
			// $showhidecontent="display:none;";
			$contenttextheaderout="Edit Business Advice Entry</small>";
			$contentplaceholdertitleout="Place the value for the selected default here";
			$contenttexttitleout="";
			$subtypeout="";
		}

		$row['adminoutput']='
			<tr data-id="'.$id.'">
				<td class="tdimg">'.$coverout.'</td><td>'.strtoupper($maintype).'</td><td '.$subtypestyleout.'>'.strtoupper($subtype).'</td><td>'.$title.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsinglegeneraldata" data-divid="'.$id.'">Edit</a></td>
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
				<div class="col-md-12">
					<form name="contentform" method="POST" enctype="multipart/form-data" action="../snippets/edit.php">
	            		<input type="hidden" name="entryvariant" value="contententry"/>
	            		<input type="hidden" name="maintype" value="'.$maintype.'"/>
	            		<input type="hidden" name="subtype" value="'.$subtype.'"/>
	            		<input type="hidden" name="entryid" value="'.$id.'"/>
	            		<input type="hidden" name="coverid" value="'.$imgid.'"/>
						<div class="col-md-12">
	                    	<h4>'.$contenttextheaderout.'</h4>
				            '.$extraformdata.'
	                    	<div class="col-md-6" style="'.$showhidetitle.'">
	                    		<div class="form-group">
				                  <label>'.$contenttexttitleout.'</label>
				                  <div class="input-group">
				                      <div class="input-group-addon">
				                        <i class="fa fa-bars"></i>
				                      </div>
				                      <input type="text" class="form-control" name="contenttitle" value="'.str_replace('"', "'",$title).'" Placeholder="'.$contentplaceholdertitleout.'"/>
				                   </div><!-- /.input group -->
				                </div>
				            </div>
				            '.$extraformimage.'
				            <div class="col-md-6" style="'.$showhideimage.'">
	                    		<div class="form-group">
				                  <label>'.$contenttextimageout.'</label>
				                  <div class="input-group">
				                      <div class="input-group-addon">
				                        <i class="fa fa-image"></i>
				                      </div>
				                      <input type="file" class="form-control" name="contentpic" Placeholder="'.$contentplaceholderimageout.'"/>
				                   </div><!-- /.input group -->
				                </div>
				            </div>
				            '.$extraformintro.'
				            <div class="col-md-12" style="'.$showhideintro.'">
	                    		<div class="form-group">
				                  <label>'.$contenttextintroout.'</label>
				                  <textarea class="form-control" rows="3" name="contentintro" id="postersmallthree" placeholder="'.$contentplaceholderintroout.'">'.$intro.'</textarea>
				                </div>
				            </div>
				            '.$extraformcontent.'
	                    	<div class="col-md-12" style="'.$showhidecontent.'">
	                    		<div class="form-group">
				                  <label>'.$contenttextcontentout.'</label>
				                  <textarea class="form-control" rows="3" name="contentpost" id="postersmallfive" placeholder="'.$contentplaceholdercontentout.'">'.$content.'</textarea>
				                </div>
				            </div>
				            '.$extraformdata.'
	                	</div>
	                	<div class="col-md-12">
	                        <label>Disable/Enable this</label>
	                        <select name="status" id="status" class="form-control">
	                        	<option value="">Choose</option>
	                        	<option value="active">Active</option>
	                        	<option value="inactive">Inactive</option>
					  	    </select>
	                    </div>
						<script>
								tinyMCE.init({
							        theme : "modern",
							        selector: "textarea#adminposter",
							        skin:"lightgray",
							        width:"94%",
							        height:"650px",
							        external_image_list_url : ""+host_addr+"snippets/mceexternalimages.php",
							        plugins : [
							         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
							         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
							         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
							        ],
							        // toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
							        toolbar1: "undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
							        toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
							        image_advtab: true ,
							        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
							        content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
							        external_filemanager_path:""+host_addr+"scripts/filemanager/",
							        filemanager_title:"Adsbounty Admin Blog Content Filemanager" ,
							        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
								});
								tinyMCE.init({
								        theme : "modern",
								        selector:"textarea#postersmallthree",
								        menubar:false,
								        statusbar: false,
								        plugins : [
								         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
								         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
								         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
								        ],
								        width:"100%",
								        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
								        toolbar2: "| link unlink anchor | emoticons | code",
								        image_advtab: true ,
								        content_css:""+host_addr+"stylesheets/mce.css",
								        external_filemanager_path:""+host_addr+"scripts/filemanager/",
								        filemanager_title:"Site Content Filemanager" ,
								        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
								});
								tinyMCE.init({
								        theme : "modern",
								        selector:"textarea#postersmallfive",
								        menubar:false,
								        statusbar: false,
								        plugins : [
								         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
								         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
								         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
								        ],
								        width:"80%",
								        height:"300px",
								        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
								        toolbar2: "| responsivefilemanager | link unlink anchor | emoticons",
								        image_advtab: true ,
								        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
								        content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
										external_filemanager_path:""+host_addr+"scripts/filemanager/",
									   	filemanager_title:"Max-Migold Admin Blog Content Filemanager" ,
									   	external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
								});   
						</script>
						<div class="col-md-12">
		        			<div class="box-footer">
			                    <input type="submit" class="btn btn-danger" name="submitcontent" value="Create/Update"/>
			                </div>
		            	</div>
		            </form>
		        </div>
		';
		// initialize default arrays
		$row['vieweroutputmaxi']='

		';
		$row['vieweroutputmini']='

		';
		$row['linkoutput']="";
		$row['linkoutputtwo']="";
		$row['tabout']='

			';
		$row['vieweroutput']='

		';
		if($maintype=="productservices"){
			$intro=strlen($intro)>300?''.substr($intro, 0,220).'...':$intro;
			$row['vieweroutputmini']='
					<div class="dnd_service_box dnd_service_box_round_text_aside " style=" ">
						<div class="dnd_service_box_header">
							<!-- <a href="services.php?t='.$prodnservcounter.'#tabber'.$id.'" target="_self" class="dnd_icon_boxed" style="border: 1px solid #e9eaec ;"><i class="icon-bank" style="color: #50a2de;background:transparent;"></i></a> -->
							<a href="services.php?t='.$prodnservcounter.'#tabber'.$id.'" target="_self">
								<h3>'.strtoupper($title).'</h3>
							</a>
						</div>
							<p>'.$intro.'</p>
							<a href="services.php?t='.$prodnservcounter.'#tabber'.$id.'" class="dnd-button dnd-button_light dnd-button_rounded dnd-button_medium ">LEARN MORE</a>
					</div>
			';
			$row['vieweroutputmaxi']='
				<h3 id="tabber'.$id.'">'.strtoupper($title).'</h3>
				<div class="dnd-accordion-body">
					 '.$content.'
				</div>
			';
			$row['linkoutput']='
            	<li class="'.$curactive.'" data-name="prodtabber'.$id.'"><a href="#tab'.$id.'" data-toggle="tab">'.$title.'<span class="fa fa-plus-circle visible-xs"></span></a></li>
			';
			$row['linkoutputtwo']=$prodbannerimgouttwo;
			$row['vieweroutput']='
					<tr>
                        <td>
                            <a href="products.php#tab'.$id.'"><img src="'.$coverpathout.'" class="maxminheight"/>&nbsp; '.$title.'</a>
                        </td>
                    </tr>';
            // chcek for banner image for this entry
		}else if ($maintype=="awards") {
			# code...
			$row['vieweroutputmini']='
				<li>
                    <a href="#'.$id.'">'.$title.'</a>
                </li>
			';
			$row['vieweroutputmaxi']='
				<article id="'.$id.'">
                    '.$content.'
                </article>
			';
		}else if ($maintype=="careeradvice") {
			# code...
			$row['vieweroutputmini']='
                    <h4 class="ctitlepost">'.$title.'</h4>
                    <p>'.$content.'</p>
			';
			$row['vieweroutputmaxi']='
				<article id="'.$id.'">
                    '.$content.'
                </article>
			';
		}else if ($maintype=="businessadvice") {
			# code...
			$row['vieweroutputmini']='
                    <h4 class="ctitlepost">'.$title.'</h4>
                    <p>'.$content.'</p>
			';
			$row['vieweroutputmaxi']='
				<article id="'.$id.'">
                    '.$content.'
                </article>
			';
		}else if ($maintype=="ceoprofile") {
			# code...
			$row['vieweroutputmini']='
			';
			$imageout=strtolower($coverout)=="no image set"?'<img src="'.$host_addr.'images/paulerubami.png" class="ceoimg" alt="Paul Erubami">':'<img src="'.$coverpathout.'" class="ceoimg" alt="'.$title.'">';
			$row['vieweroutputmaxi']='
				<div class="dnd_column_dd_span6">
						<div class="dnd-animo ceoimghold" data-animation="fadeIn" data-duration="1000" data-delay="0">
							'.$imageout.'
						</div>
					</div>
					<div class="dnd_column_dd_span6 ceotexthold">
						<h3><span>'.$title.'</span></h3>
						'.$content.'
					</div>
			';
		}else if ($maintype=="homevision"||$maintype=="homemission"||$maintype=="homevalues") {
			# code...
			$row['vieweroutputmaxi']='
				<div class="dnd_column_dd_span4 ">
					<div class="dnd-animo " data-animation="flipInX" data-duration="1000" data-delay="200">
						<img src="'.$coverphoto.'" class="missvisimg" alt="">
					</div>
					<span class="clear spacer_responsive_hide_mobile " style="height:34px;display:block;"></span>
					<h6><span>'.$title.'</span></h6>
					'.$content.'
				</div>
			';
		}else if ($maintype=="homewelcomemsg") {
			# code...
			$title==""?$title="Welcome to the Max-Migold Official Website":$title;
			$content==""?$content="<p>Max-Migold Ltd offers real estates and facility management execution and advisory services to a niche clientele of high net worth multi-national and Nigerian companies who yearn for best value creation, cost optimization,
				 sustainability and human capital development. We provide practical results-oriented FM operations, consulting and training that translates into immediate economic, social and environmental bottom line earnings for our clients.</p>":$content;
			$row['vieweroutputmaxi']='
				<h3>'.$title.'</h3>
				<div>'.$content.'</div>
			';
		}else if ($maintype=="aboutwelcomemsg") {
			# code...
			$title==""?$title="INTRODUCING MAX-MIGOLD LTD":$title;
			$content==""?$content="<p>
					Max-Migold Ltd is a physical facilities advisory and execution firm targeting a niche clientele of high net-worth Multi-national and Nigerian companies who yearn for best value-creation, cost optimization, sustainability, and human capital development. We provide practical results-oriented FM operations, consulting and training that translates into immediate economic, social and environmental bottom line earnings for our clients. Max-Migold Ltd was legally organized and incorporated in Nigeria in 2006 but commenced business in 2014. The firm currently operates a growing number of consultancy retainership agreements with medium sized companies in Nigeria and represent a few building management technology OEMs.  
					The Nigerian-owned firm which is based in Lagos and Portharcourt has an experienced FM Consultant and Trainer, Mr. Paul Erubami FMP, SFP, CBIFM, CFM at the helm as CEO. Paul is a Certified Facility Manager & Sustainability Consultant with working knowledge of the GRI G4 CSR reporting framework, an IFMA Qualified FMP & SFP Instructor, an Industrial & Business Process Engineer, an expert Physical Facilities Manager and an IOSH Managing Safely Professional. He has over 15 years’ experience working in strategy implementation, operations systems and processes deployment and change management for Oil & Gas, Telecoms as well as Corporate Real Estates sectors, both as external contract manager and also as internal budget controller.
				</p>":$content;
			$row['vieweroutputmaxi']='
				<h3>'.$title.'</h3>
				<div>'.$content.'</div>
			';
			$row['vieweroutputmini']=$intro!==""?''.substr($intro, 0,220).'...':"Max-Migold Ltd is a physical facilities advisory and execution firm targeting a niche clientele of high net-worth Multi-national and Nigerian companies who yearn for best value-creation, cost optimization, sustainability, and human capital development. We provide practical...";
		}else if ($maintype=="contactpageintro") {
			# code...
			$title==""?$title="GET IN TOUCH WITH US":$title;
			$content==""?$content="<p>
					Do you need more information about us, on a service or group of services we offer?, Simply use the form provided below to send your message to us, we will respond as soon as possible
				</p>":$content;
			$row['vieweroutputmaxi']='
				<h3>'.$title.'</h3>
				<div>'.$content.'</div>
			';
			$row['vieweroutputmini']=$intro!==""?''.substr($intro, 0,220).'...':"Max-Migold Ltd is a physical facilities advisory and execution firm targeting a niche clientele of high net-worth Multi-national and Nigerian companies who yearn for best value-creation, cost optimization, sustainability, and human capital development. We provide practical...";
		}else if ($maintype=="businesshours") {
			# code...
			$content==""?$content="Monday – Friday: 8am to 5pm<br>
							Saturday: 9am to 1 pm<br>
							Sunday: day off<br>":$content;
			$row['vieweroutputmini']='<div class="textwidget">'.$content.'</div>';
		}else if ($maintype=="servicepageintro") {
			# code...
			$content==""?$content="<p>We Offer a broad range of high quality services, some of which include but are not limited to the following:</p>":$content;
			$row['vieweroutputmini']='<div>'.$content.'</div>';
		}else if ($maintype=="photogallery"||$maintype=="photogallerytwo") {
			# code...
			$row['vieweroutput']='
				<div class="col-md-3 col-sm-6">
                        <div class="managementImg">
                            <div class="managementCaption">
                                <a href="'.$coverpathout.'" data-lightbox="roadtrip">
                                    <h4>'.$title.'</h4>
                                    <p>'.$intro.'</p>
                                </a>
                            </div>
                            <img class="img-responsive" src="'.$coverphoto.'" />
                        </div>
                    </div>
			';
		}else if ($subtype=="defaultphonenumbers"||$subtype=="defaultemailaddress"||$subtype=="defaultmainaddress") {
			# code...
			$row['vieweroutput']='
				'.$title.'               
			';
			$row['vieweroutputmini']='
				'.$title.'               
			';
		}else if ($subtype=="defaultfacebook"||
			$subtype=="defaulttwitter"||
			$subtype=="defaultlinkedin"||
			$subtype=="defaultgoogleplus"||
			$subtype=="defaultpinterest"||
			$subtype=="defaultskype") {
			# code...
			$nexttypewebone=false;
			$nexttypewebtwo=false;
			if($title!==""&&$subtype!=="defaultskype"){
				$nexttypewebone=strpos($title,"http://");
				$nexttypewebtwo=strpos($title,"https://");
				/*echo $nexttypewebtwo." webtwo<br>";
				echo $nexttypewebtwo." webone<br>";*/
				if($nexttypewebtwo===false&&$nexttypewebone===false||$nexttypewebtwo===0&&$nexttypewebone===0||$nexttypewebtwo<1&&$nexttypewebone<1){
					$title="http://".$title;
				}
			}
			if ($title=="") {
				# code...
				$title="##";
			}
			// echo $title."the title<br>";
			$row['vieweroutputmini']=$title;
		}else{
			$row['vieweroutputmaxi']='

			';
			$row['vieweroutputmini']='

			';
			$row['linkoutput']="";
			$row['tabout']='
			';
		}
		return $row;
	}


	function getAllGeneralInfo($viewer,$type,$limit,$storetype='',$contentgroupdata=array()){
		include('globalsmodule.php');
		$row=array();
		$outputtype=$viewer."-".$type;
		$testit=strpos($limit,"-");
		$testit!==false?$limit="":$limit=$limit;
		$storetype==""?$storetype=$type:$storetype=$storetype;
		$frameout="WHERE maintype='$type'";
		$ordercontent="order by id desc";
		// default content message title
		$mainmsgout=''.strtoupper($type).' Page Manager';
		$mainmsgintroout='Edit '.strtoupper($type).' Page Intro/Create New Content';
		$mainmsgcontentout='Edit '.strtoupper($type).' Page Contents';
		// hidden content map
		$showhidetitle="";
		$showhideimage="";
		$showhideintro="";
		$showhidecontent="";
		$formtypeout="submitcontent";
		$formmonitor="";
		// default values accrueable to formmonitor
		// hidden element variables are 1,2,3,4,5
		// where 1 means monitor the title
		// where 2 means monitor the image
		// where 3 means monitor the introparagraph(if present);
		// where 4 means monitor the content(if present);
		// for breaking into or removing subtype
		$subtypeout='<input type="hidden" name="subtype" value="content"/>';
		$subtypestyleout='style="display:none"';
		// new form content header and output displays
		$contenttextheaderout="New Content Entry";
		$contenttexttitleout="Content Title";
		$contenttextimageout="Content Photo";
		$contenttextintroout="Content Intro";
		$contenttextcontentout="Content Post";
		// Edit form Placeholders  and output displays
		$contentplaceholdertitleout="The title of the entry";
		$contentplaceholderimageout="The Image for the entry";
		$contentplaceholderintroout="The introduction for the entry";
		$contentplaceholdercontentout="The content for the entry";
		$extraformdata=$rurladmin!==""?'<input type="hidden" name="rurladmin" value="'.$rurladmin.'">':"";
		// extraformdata positioning variables, each variable puts the extra data at 
		// the appropriate point in the form
		$extraformtitle="";
		$extraformimage="";
		$extraformintro="";
		$extraformcontent="";
		$heading_extras="";
		$curstamp = new DateTime(); // current time 
		// prep the unique session variable for carrying content data across
		// other pages
		$_SESSION['gd_contentgroup'.$curstamp.'']="";
		if(count($contentgroupdata)>0){
			$_SESSION['gd_contentgroup'.$curstamp.'']['curstamp']=$curstamp;
			$contentgroupdata['curstamp']=$curstamp;
			if(isset($contentgroupdata[0])||isset($contentgroupdata['contentmap'])){
				// hidden content map for the default set of elements available to the
				// generaldatamodule

			}
			if(isset($contentgroupdata[1])||isset($contentgroupdata['contentlabels'])){
				// the labels for the current set of content are passed in here
				
			}
			if(isset($contentgroupdata[2])||isset($contentgroupdata['contentplaceholders'])){
				// placeholders for the default content set of the general datamodule
			}
			if(isset($contentgroup[3])||isset($contentgroup['extraformdata'])){
				// extra element data and their content position in the generaldatamodule
				// form
			}
			if(isset($contentgroup[4])||isset($contentgroup['datamap'])){
				// array map element map data for creation is parsed here

			}
			if(isset($contentgroup[5])||isset($contentgroup['editmap'])){
				// map data for the edit section of the generaldatamodule
			}
			if(isset($contentgroup[6])||isset($contentgroup['validationmap'])){
				// a set of script data validation for the current form entry
				// userful for handling extraform data validation

			}
			if(isset($contentgroup[7])||isset($contentgroup['evaldata'])){
				// for running php code directly when necessary and ONLY when
				// necessary, to be used with caution
			}

			if(isset($contentgroup[8])||isset($contentgroup['adminheadingsdata'])){
				// used for passing in content for the admin table heading and individual
				// content heading
				$adminheadings=isset($contentgroup['adminheadingsdata']['singlecontent'])?$contentgroup['adminheadingsdata']['singlecontent']:"";
				$dummiedata=explode("<td", $adminheadingsdata);
					$dummies="";
				if(count($dummiedata)>0){
					$heading_extras=$adminheadings;
					$dummies=count($dummiedata);
				}
			}
		}
		if($type=="about"){
			$frameout="WHERE maintype='about'";
			$showhidetitle="display:none;";
			$showhideimage="display:none;";
		}else if($type=="history"){
			$frameout="WHERE maintype='history'";
			$showhidetitle="display:none;";
			$showhideimage="display:none;";
		}else if($type=="historytab"){
			$frameout="WHERE maintype='historytab'";
			$showhidetitle="display:none;";
			// $showhideimage="display:none;";
		}else if($type=="mission"){
			$frameout="WHERE maintype='mission'";
			$showhidetitle="display:none;";
			$showhideimage="display:none;";
		}else if($type=="missiontab"){
			$frameout="WHERE maintype='missiontab'";
			$showhidetitle="display:none;";
			// $showhideimage="display:none;";
		}else if($type=="ceoprofile"){
			$frameout="WHERE maintype='ceoprofile'";
			// $showhidetitle="display:none;";
			// $showhideimage="display:none;";
			// $showhidecontent="display:none;";
			$showhideintro="display:none;";
		}else if($type=="productservices"){
			$curactive="active";
			$frameout="WHERE maintype='productservices'";
			$mainmsgout="Create/Update Products and Services";
			$mainmsgintroout="Create Product/Service";
			$mainmsgcontentout="Edit Product/Service";
			// $showhidetitle="display:none;";
			// $showhideimage="display:none;";
			// $showhidecontent="display:none;";
			$showhideintro="display:none;";
			/*$extraformdata='
				<div class="col-md-12">
					<div class="col-md-6">
				 		<div class="form-group">
		                  <label>Product Banner</label>
		                  <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-file-image-o"></i>
		                      </div>
						 	  <input type="hidden" name="prodbannerimgid" value="0">
		                      <input type="file" class="form-control" name="prodbannerimg" Placeholder=""/>
		                   </div><!-- /.input group -->
		                </div>
		            </div>
				</div>
				<div class="col-md-12">
					<h4>Sub Products Section</h4>
					<input type="hidden" name="cursubproductcount" value="1"/>
					<div class="col-md-6">
				 		<div class="form-group">
		                  <label>SUB product/service Title (<b>1</b>)</label>
		                  <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-file-image-o"></i>
		                      </div>
		                      <input type="hidden" class="form-control" name="subcontentid1" value="0"/>
		                      <input type="text" class="form-control" name="subcontenttitle1" value="" Placeholder="Product/Service Title"/>
		                   </div><!-- /.input group -->
		                </div>
		            </div>
	                    <div name="entrysubproductpoint"></div>
                      	<div class="col-md-12">
	                      <a href="##" class="addpoint" name="addextrasubproducts">Click to add another sub product</a>
					 	</div>	
				</div>
			';*/
		}else if ($type=="photogallery") {
			# code...
			// $showhidetitle="display:none;";
			// $showhideimage="display:none;";
			// $showhideintro="display:none;";
			$showhidecontent="display:none;";
			$formtypeout="submitcustom";
			$formmonitor='<input type="hidden" name="monitorcustom" value="1:2:3">';
		}else if ($type=="photogallerytwo") {
			# code...
			$frameout="WHERE maintype='photogallery'";
			// $showhidetitle="display:none;";
			// $showhideimage="display:none;";
			// $showhideintro="display:none;";
			$showhidecontent="display:none;";
			$formtypeout="submitcustom";
			$formmonitor='<input type="hidden" name="monitorcustom" value="1:2:3">';
		}else if($type=="ceoprofile"){
			$frameout="WHERE maintype='$type'";
			$showhidetitle="display:none;";
			$showhideimage="display:none;";
		}else if($type=="homewelcomemsg"){
			$frameout="WHERE maintype='$type'";
			$mainmsgout="Create/Update Home Page Welcome Msg";
			$mainmsgintroout="Create/Update Message";
			$mainmsgcontentout="Create/Update Message";
			// $showhidetitle="display:none;";
			$showhideimage="display:none;";
			$showhideintro="display:none;";
			// $showhidecontent="display:none;";
		}else if($type=="contactpageintro"){
			$frameout="WHERE maintype='$type'";
			$mainmsgout="Create/Update Contact Page Welcome Msg";
			$mainmsgintroout="Create/Update Message";
			$mainmsgcontentout="Create/Update Message";
			// $showhidetitle="display:none;";
			$showhideimage="display:none;";
			$showhideintro="display:none;";
			// $showhidecontent="display:none;";
		}else if($type=="businesshours"){
			$frameout="WHERE maintype='$type'";
			$mainmsgout="Business Hours";
			$mainmsgintroout="Create/Update Business Hours Info";
			// $mainmsgcontentout="Create/Update Message";
			$showhidetitle="display:none;";
			$showhideimage="display:none;";
			$showhideintro="display:none;";
			// $showhidecontent="display:none;";
		}else if($type=="servicepageintro"){
			$frameout="WHERE maintype='$type'";
			$mainmsgout="Service Page Intro";
			$mainmsgintroout="Create/Update Services Page Welcome Msg";
			// $mainmsgcontentout="Create/Update Message";
			$showhidetitle="display:none;";
			$showhideimage="display:none;";
			$showhideintro="display:none;";
			// $showhidecontent="display:none;";
		}else if($type=="aboutwelcomemsg"){
			$frameout="WHERE maintype='$type'";
			$mainmsgout="Create/Update About Page Welcome Msg";
			$mainmsgintroout="Create/Update Message";
			$mainmsgcontentout="Create/Update Message";
			// $showhidetitle="display:none;";
			$showhideimage="display:none;";
			$showhideintro="display:none;";
			// $showhidecontent="display:none;";
		}else if($type=="defaultsocial"){
			$frameout="WHERE maintype='$type'";
			$subtypestyleout="";
			$mainmsgout="Create or Update website default Social Data";
			$mainmsgintroout="Create";
			$mainmsgcontentout="Update";
			$subtypeout="";
			// $showhidetitle="display:none;";
			$showhideimage="display:none;";
			$showhideintro="display:none;";
			$showhidecontent="display:none;";
			$contenttextheaderout="New Default Social Entry(<small>if social account exists, update will be done instead)</small>";
			$contentplaceholdertitleout="Place the web address for this social account here";
			$contenttexttitleout="Web address(Skype ID if skype)";
			$extraformtitle='
				<div class="col-md-6">
                    <div class="form-group">
                        <label>Select Social Network</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-file-text"></i>
                            </div>
                            <select name="subtype" class="form-control">
                              <option value="defaultfacebook">Facebook <i class="fa fa-facebook"></i></option>
                              <option value="defaulttwitter">Twitter <i class="fa fa-twitter"></i></option>
                              <option value="defaultlinkedin">LinkedIn <i class="fa fa-linkedin"></i></option>
                              <option value="defaultgoogleplus">Google+ <i class="fa fa-google"></i></option>
                              <option value="defaultpinterest">Pinterest <i class="fa fa-pinterest"></i></option>
                              <option value="defaultskype">Skype <i class="fa fa-skype"></i></option>
                            </select>
                        </div><!-- /.input group -->
                     </div>
                </div>
                ';
            $formtypeout="submitcustom";
			$formmonitor='<input type="hidden" name="monitorcustom" value="nomonitor">';
		}else if($type=="defaultfacebook"||$type=="defaulttwitter"||$type=="defaultlinkedin"||$type=="defaultgoogleplus"||$type=="defaultpinterest"||$type=="defaultskype"){
			$frameout="WHERE subtype='$type'";
		}elseif($type=="defaultphonenumbers"||$type=="defaultemailaddress"||$type=="defaultmainaddress"){
			$frameout="WHERE subtype='$type'";
		}elseif($type=="defaultinfo"){
			$frameout="WHERE maintype='$type'";
			$subtypestyleout="";
			$mainmsgout="Create or Update website default data";
			$mainmsgintroout="Create";
			$mainmsgcontentout="Update";
			// $showhidetitle="display:none;";
			$showhideimage="display:none;";
			$showhideintro="display:none;";
			$showhidecontent="display:none;";
			$contenttextheaderout="New Default Data Entry(<small>if entry exists, update will be done instead)</small>";
			$contentplaceholdertitleout="Place the value for the selected default here";
			$contenttexttitleout="Default Value";
			$subtypeout="";
			$extraformtitle='
				<div class="col-md-6">
                    <div class="form-group">
                        <label>Select Default Type</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-file-text"></i>
                            </div>
                            <select name="subtype" class="form-control">
                              <option value="defaultphonenumbers">Default Phonenumbers</option>
                              <option value="defaultemailaddress">Default Email</option>
                              <option value="defaultmainaddress">Default Main Address</option>
                            </select>
                        </div><!-- /.input group -->
                     </div>
                </div>
            ';
            $formtypeout="submitcustom";
			$formmonitor='<input type="hidden" name="monitorcustom" value="nomonitor">';
		}else{
			$frameout="WHERE maintype='$type'";
			$showhidetitle="";
			$showhideimage="";

		}

		$row=array();
		$rowmonitor['chiefquery']="SELECT * FROM generalinfo $frameout $ordercontent";
		if($limit!==""&&$viewer=="admin"){
			$query="SELECT * FROM generalinfo $frameout $ordercontent ".$limit."";
		}else if($limit==""&&$viewer=="admin"){
			$query="SELECT * FROM generalinfo $frameout $ordercontent LIMIT 0,15";		
		}elseif($limit!==""&&$viewer=="viewer"){
			$query="SELECT * FROM generalinfo $frameout AND status='active' $ordercontent $limit";
			$rowmonitor['chiefquery']="SELECT * FROM generalinfo $frameout AND status='active' $ordercontent";
		}else if($limit==""&&$viewer=="viewer"){
			$query="SELECT * FROM generalinfo $frameout AND status='active'";		
			$rowmonitor['chiefquery']="SELECT * FROM generalinfo $frameout AND status='active' $ordercontent";
		}
		/*if($typeid=="active"){
			$query="SELECT * FROM faq WHERE status='active'";
		}*/
		$selection="";
		// echo $query;
		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
		$numrows=mysql_num_rows($run);
		$top='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>CoverImage</th><th>maintype</th><th '.$subtypestyleout.'>subtype</th><th>Title</th>'.$heading_extras.'<th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		$bottom='	</tbody>
				</table>';
		$adminoutput="<td>No entries</td><td></td><td $subtypestyleout></td><td></td>'.$dummies.'<td></td><td></td>";
		$vieweroutput='Nothing posted yet, Sorry, we are working on it';
		$introdata='Nothing posted yet, Sorry, we are working on it';
		$maintitle='';
		$vieweroutputmaxi='';
		$vieweroutputmini='';
		$linkdata='';
		$linkdatatwo='';
		$linkdatatwofull='';
		$tabout='';
		$monitorpoint="";
		$introdata="";
		$contentintro="";
		$contentdata="";
		$contenttitle="";
		$coverimgpath="";
		$introoutputviewer="No introduction has been made for this section";
		$introid=0;
		$contentid=0;
		$typeoutcontroller="";
		$coverid=0;
		$counttocont=0;
		$countcontent=0;
		$selection2="";
		if($numrows>0){
			$vieweroutput="";
			$adminoutput="";
			$introdata="";
			while($row=mysql_fetch_assoc($run)){
				$prodnservcounter++;
				if($counttocont>0){
					$curactive="";
				}
				if($row['subtype']=="intro"){
					$introid=$row['id'];
					$introdata=$row['intro'];
					$maintitle=$row['title']!==""?'<div class="largeheader">'.$row['title'].'<br><img src="'.$host_addr.'./images/headingborder.png" style="width:50%;"></div>':'<div class="largeheader">Max-Migold.<br><img src="'.$host_addr.'./images/headingborder.png" style="width:50%;"></div>';
					$introoutputviewer=$row['status']=='active'?$introdata:"No introduction has been made for this section";
					$contenttitle=$row['title'];
				}else{
					$outvar=getSingleGeneralInfo($row['id'],);
					// these are for single page content only
					if($counttocont<1){
						$contentid=$row['id'];
						$contenttitle=$row['title'];
						$contentintro=$row['intro'];
						$contentdata=$row['content'];
						$coverid=$outvar['coverid'];
						$coverimgpath=$outvar['coverpath'];
						$row['contentid']=$contentid;
						$row['contenttitle']=$contenttitle;
						$row['contentintro']=$contentintro;
						$row['coverimgpath']=$coverimgpath;
					}
					// end
					$adminoutput.=$outvar['adminoutput'];
					$tabout.=$outvar['tabout'];
					$linkdata.=$outvar['linkoutput'];
					// for home page intro service display
					if($counttocont>0 &&$counttocont<7 && $type=="productservices"){
						if($countcontent==0||($countcontent%2==0&&$countcontent<$numrows)){
							$countcontent==0?$linkdatatwo.='<div class="dnd_column_dd_span4">':$linkdatatwo.='</div><div class="dnd_column_dd_span4">';
							$next3=$countcontent+2;
						}
							$linkdatatwo.=str_replace("../", "$host_addr",$outvar['vieweroutputmini']);
						if($countcontent==$next3||$countcontent==$numrows-1){
						  $linkdatatwo.=' 
						  </div>';
						}
						$countcontent++;
					}
					$linkdatatwofull.=$outvar['linkoutputtwo'];
					$vieweroutputmaxi.=$outvar['vieweroutputmaxi'];
					$vieweroutputmini.=$outvar['vieweroutputmini'];
					// echo $outvar['vieweroutputmini']." putmini<br>";
					$vieweroutput.=str_replace("../", "$host_addr",$outvar['vieweroutput']);
					$selection.='<option value="'.$outvar['id'].'">'.$outvar['title'].'</option>';
					$selection2.='<option value="'.$outvar['id'].'">'.$outvar['title'].'</option>';
					$counttocont++;

				}

			}
		}else{
			if($type=="servicepageintro"){
				$vieweroutputmini="Sorry Nothing has been posted yet";
			}
		}
		$contentimageout=$coverimgpath!==""&&strtolower($coverimgpath)!=="no image set"?'<img src="'.$coverimgpath.'" class="defcontentimage"/>':"No Image set";
		$vieweroutputmaxi=$vieweroutputmaxi==""?"Nothing posted yet":$vieweroutputmaxi;
		$vieweroutputmini=$vieweroutputmini==""?"Nothing posted yet":$vieweroutputmini;
		$linkdata=$linkdata==""?"Nothing posted yet":$linkdata;
		$linkdatatwo=$linkdatatwo==""?"Nothing posted yet":$linkdatatwo;
	
		$row['vieweroutputmaxi']=$vieweroutputmaxi;
		$row['vieweroutputmini']=$vieweroutputmini;
		$row['linkdata']=$linkdata;
		$row['linkdatatwo']=$linkdatatwo;
		$row['linkdatatwofull']=$linkdatatwofull;

		if($counttocont>1){
			$typeoutcontroller="tabbed";
		}
		$row['numrows']=$numrows;
		$row['contentdata']=$contentdata;
		$row['contentrows']=$counttocont;				
		$row['contentid']=$contentid;
		$row['contenttitle']=$contenttitle;
		$row['contentintro']=$contentintro;
		$row['contentsdata']=$contentdata;
		$row['coverimgpath']=$contentimageout;
		$row['coverimgpathtwo']=$coverimgpath;
		$row['introoutput']='
			<div class="row">
				<form name="introform" method="POST"  enctype="multipart/form-data" action="../snippets/edit.php">
	        		<input type="hidden" name="entryvariant" value="introentryupdate"/>
	        		<input type="hidden" name="maintype" value="'.$type.'"/>
	        		<input type="hidden" name="subtype" value="intro"/>
	        		<input type="hidden" name="entryid" value="'.$introid.'"/>
	        		'.$formmonitor.'
	                <div class="col-md-12" style="'.$showhidetitle.'">
                    		<div class="form-group">
			                  <label>Page Title</label>
			                  <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-bars"></i>
			                      </div>
			                      <input type="text" class="form-control" name="contenttitle" Placeholder="The main heading for this page"/>
			                   </div><!-- /.input group -->
			                </div>
			            </div>
	                <div class="col-md-12">
	                    <label>Page Intro</label>
			            <textarea class="form-control" rows="3" name="intro" id="postersmallthree" placeholder="Provide information concerning what this page is about">'.$introdata.'</textarea>
	                </div>
	                <div class="col-md-12">
	        			<div class="box-footer">
		                    <input type="button" class="btn btn-danger" name="submitintro" value="Create/Update"/>
		                </div>
	            	</div>
	            </form>
	            <script>
					tinyMCE.init({
				        theme : "modern",
				        selector: "textarea#adminposter",
				        skin:"lightgray",
				        width:"94%",
				        height:"650px",
				        external_image_list_url : ""+host_addr+"snippets/mceexternalimages.php",
				        plugins : [
				         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
				         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
				        ],
				        // toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
				        toolbar1: "undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
				        toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
				        image_advtab: true ,
				        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
				        content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
				        external_filemanager_path:""+host_addr+"scripts/filemanager/",
				        filemanager_title:"Adsbounty Admin Blog Content Filemanager" ,
				        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
					});
					tinyMCE.init({
					        theme : "modern",
					        selector:"textarea#postersmallthree",
					        menubar:false,
					        statusbar: false,
					        plugins : [
					         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
					         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
					         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
					        ],
					        width:"100%",
					        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
					        toolbar2: "| link unlink anchor | emoticons",
					        image_advtab: true ,
					        content_css:""+host_addr+"stylesheets/mce.css",
					        external_filemanager_path:""+host_addr+"scripts/filemanager/",
					        filemanager_title:"Site Content Filemanager" ,
					        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
					});
					tinyMCE.init({
					        theme : "modern",
					        selector:"textarea#postersmallfive",
					        menubar:false,
					        statusbar: false,
					        plugins : [
					         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
					         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
					         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
					        ],
					        width:"80%",
					        height:"300px",
					        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
					        toolbar2: "| responsivefilemanager | link unlink anchor | emoticons",
					        image_advtab: true ,
					        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
					        content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
							external_filemanager_path:""+host_addr+"scripts/filemanager/",
						   	filemanager_title:"Max-Migold Admin Blog Content Filemanager" ,
						   	external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
					});   
				</script>
	        </div>
		';
		$row['introoutputtwo']='
			<div class="row">
				<form name="introform" method="POST" enctype="multipart/form-data" action="../snippets/edit.php">
	        		<input type="hidden" name="entryvariant" value="contententryupdate"/>
	        		<input type="hidden" name="maintype" value="'.$type.'"/>
	        		<input type="hidden" name="subtype" value="content"/>
	        		<input type="hidden" name="entryid" value="'.$contentid.'"/>
	                <div class="col-md-12" style="'.$showhideimage.'">
	                	<input type="hidden" name="coverid" value="'.$coverid.'">
	                	'.$contentimageout.'
                		<div class="form-group">
		                  <label>Content Image</label>
		                  <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-bars"></i>
		                      </div>
		                      <input type="file" class="form-control" name="contentpic" value="'.str_replace('"', "'", $contenttitle).'" Placeholder="The main heading for this page"/>
		                   </div><!-- /.input group -->
		                </div>
		            </div>
	                <div class="col-md-12" style="'.$showhidetitle.'">
                		<div class="form-group">
		                  <label>Title</label>
		                  <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-bars"></i>
		                      </div>
		                      <input type="text" class="form-control" name="contenttitle" value="'.str_replace('"', "'", $contenttitle).'" Placeholder="The main heading for this page"/>
		                   </div><!-- /.input group -->
		                </div>
		            </div>
	                <div class="col-md-12">
	                    <label> Content</label>
			            <textarea class="form-control" rows="3" name="intro" id="postersmallthree" placeholder="Provide information concerning what this page is about">'.$contentdata.'</textarea>
	                </div>
	                <div class="col-md-12">
	        			<div class="box-footer">
		                    <input type="button" class="btn btn-danger" name="submitintro" value="Create/Update"/>
		                </div>
	            	</div>
	            </form>
	            <script>
					tinyMCE.init({
				        theme : "modern",
				        selector: "textarea#adminposter",
				        skin:"lightgray",
				        width:"94%",
				        height:"650px",
				        external_image_list_url : ""+host_addr+"snippets/mceexternalimages.php",
				        plugins : [
				         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
				         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
				        ],
				        // toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
				        toolbar1: "undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
				        toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
				        image_advtab: true ,
				        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
				        content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
				        external_filemanager_path:""+host_addr+"scripts/filemanager/",
				        filemanager_title:"Adsbounty Admin Blog Content Filemanager" ,
				        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
					});
					tinyMCE.init({
					        theme : "modern",
					        selector:"textarea#postersmallthree",
					        menubar:false,
					        statusbar: false,
					        plugins : [
					         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
					         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
					         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
					        ],
					        width:"100%",
					        height:"400px",
					        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
					        toolbar2: "| link unlink anchor | code",
					        image_advtab: true ,
					        content_css:""+host_addr+"stylesheets/mce.css",
					        external_filemanager_path:""+host_addr+"scripts/filemanager/",
					        filemanager_title:"Site Content Filemanager" ,
					        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
					});
					tinyMCE.init({
					        theme : "modern",
					        selector:"textarea#postersmallfive",
					        menubar:false,
					        statusbar: false,
					        plugins : [
					         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
					         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
					         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
					        ],
					        width:"80%",
					        height:"300px",
					        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
					        toolbar2: "| responsivefilemanager | link unlink anchor | emoticons",
					        image_advtab: true ,
					        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
					        content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
							external_filemanager_path:""+host_addr+"scripts/filemanager/",
						   	filemanager_title:"Max-Migold Admin Blog Content Filemanager" ,
						   	external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
					});   
				</script>
	        </div>
		';
		$row['introoutputviewer']=$introoutputviewer;
		$row['newcontentoutput']='
			<div class="row">
	            <form name="contentform" method="POST" enctype="multipart/form-data" action="../snippets/basicsignup.php">
            		<input type="hidden" name="entryvariant" value="contententry"/>
            		<input type="hidden" name="maintype" value="'.$type.'"/>
            		'.$subtypeout.'
	        		'.$formmonitor.'
                    <div class="col-md-12">
                    	<h4>'.$contenttextheaderout.'</h4>
                    	'.$extraformtitle.'
                    	<div class="col-md-6" style="'.$showhidetitle.'">
                    		<div class="form-group">
			                  <label>'.$contenttexttitleout.'</label>
			                  <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-bars"></i>
			                      </div>
			                      <input type="text" class="form-control" name="contenttitle" Placeholder="'.$contentplaceholdertitleout.'"/>
			                   </div><!-- /.input group -->
			                </div>
			            </div>
                    	'.$extraformimage.'
			            <div class="col-md-6" style="'.$showhideimage.'">
                    		<div class="form-group">
			                  <label>'.$contenttextimageout.'</label>
			                  <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-image"></i>
			                      </div>
			                      <input type="file" class="form-control" name="contentpic" Placeholder="'.$contentplaceholderimageout.'"/>
			                   </div><!-- /.input group -->
			                </div>
			            </div>
                    	'.$extraformintro.'
                    	<div class="col-md-12" style="'.$showhideintro.'">
                    		<div class="form-group">
			                  <label>'.$contenttextintroout.'</label>
			                  <textarea class="form-control" rows="3" name="contentintro" placeholder="'.$contentplaceholderintroout.'"></textarea>
			                </div>
			            </div>
			            '.$extraformcontent.'
                    	<div class="col-md-12" style="'.$showhidecontent.'">
                    		<div class="form-group">
			                  <label>'.$contenttextcontentout.'</label>
			                  <textarea class="form-control" rows="3" name="contentpost" id="postersmallfour" placeholder="'.$contentplaceholdercontentout.'"></textarea>
			                </div>
			            </div>
			            '.$extraformdata.'
                	</div>
                	<div class="col-md-12">
            			<div class="box-footer">
		                    <input type="button" class="btn btn-danger" name="'.$formtypeout.'" value="Create/Update"/>
		                </div>
	            	</div>
                </form>
	        </div>	
		';
		$adminoutput=$adminoutput==""?"<td>No Content entries for this</td><td></td><td></td><td></td><td></td>":$adminoutput;
		
		$outs=paginatejavascript($rowmonitor['chiefquery']);
		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
				<input type="hidden" name="curstamp" value="'.$curstamp.'"/>
				<input type="hidden" name="outputtype" value="generalinfo-'.$outputtype.'"/>
				<input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
				<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
				<div class="pagination">
					  '.$outs['usercontrols'].'
				</div>
			</div>
		</div>
		<div id="paginateddatahold" data-name="contentholder">';

		$paginatebottom='
		</div><div id="paginationhold">
			<div class="meneame">
				<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
			</div>
		</div>';
		$row['paginatetop']='
			<div id="paginationhold">
				<div class="meneametwo">
					<input type="hidden" name="curquery" data-target="generalinfo-'.$outputtype.'" value="'.$rowmonitor['chiefquery'].'"/>
					<input type="hidden" name="curstamp" data-target="generalinfo-'.$outputtype.'" value="'.$curstamp.'"/>
					<input type="hidden" name="outputtype" data-target="generalinfo-'.$outputtype.'" value="generalinfo-'.$outputtype.'"/>
					<input type="hidden" name="currentview" data-target="generalinfo-'.$outputtype.'" data-ipp="15" data-page="1" value="1"/>
					<div class="pagination" data-name="paginationpagesholdtwo" data-target="generalinfo-'.$outputtype.'">'.$outs['pageout'].'</div>
					<div class="pagination" data-target="generalinfo-'.$outputtype.'">
						  '.$outs['usercontrols'].'
					</div>
				</div>
			</div>
		';

		$row['paginatebottom']='
			<div id="paginationhold">
				<div class="meneametwo">
					<div class="pagination" data-name="paginationpagesholdtwo" data-target="generalinfo-'.$outputtype.'">'.$outs['pageout'].'</div>
				</div>
			</div>
		';
		$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
		$row['adminoutputtwo']=$top.$adminoutput.$bottom;
		$row['vieweroutput']=$vieweroutput;
		$row['selection']=$selection;
		$row['fullviewhead']='
			<div class="box">
	            <div class="box-header with-border">
	              <h3 class="box-title">'.$mainmsgout.'</h3>
	              <div class="box-tools pull-right">
	                <!-- <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button> -->
	                <!-- <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button> -->
	              </div>
	            </div>
	            <div class="box-body">

		';
		$row['fullviewaccordh']='<div class="box-group" id="generaldataaccordion">';
		/*for extremely custom admin accordion entry*/
		$row['fullviewaccordconea']='
			<div class="panel box overflowhidden box-primary">
	          <div class="box-header with-border">
	                <h4 class="box-title">
	                  <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#';
		$row['fullviewaccordconeb']='">';
		// then use content id for the accordion entry comes in between the fullview above and below
		$row['fullviewaccordconec']='
					</a>
	            </h4>
	        </div>
	    ';
	    $row['fullviewaccordconed']='</div>';
	    /*custom accord entry example usage of custom entry
		* $outs=getAllGeneralData("admin","about","");
		* $content="The content";
		* $thetitle="Edit the Content";
		* $idoutput="ContentAccord";
		* $fulloutput=$outs['fullviewhead'].$outs['fullviewaccordh'].$outs['fullviewaccordconea'].$outs['fullviewaccordconeb']
		* .$contentid.$outs['fullviewaccordconeb'].$thetitle.$outs['fullviewaccordconec']
		* the mainentrystarts here following the format.'<div id="'.$idoutput.'" class="panel-collapse collapse in">'.$content.'</div>'.$outs['fullviewaccordconed'].$outs['fullviewaccordb'].$outs['fullviewbottom']
	    */

		$row['fullviewaccordhone']='<div id="NewPageManagerBlock" class="panel-collapse collapse in">';
		$row['fullviewaccordbone']='</div>';

		$row['fullviewaccordb']='</div>';
		$row['fullviewbottom']='</div>
			 </div>';

		$row['fullview']='
			 <div class="box">
	            <div class="box-header with-border">
	              <h3 class="box-title">'.$mainmsgout.'</h3>
	              <div class="box-tools pull-right">
	                <!-- <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button> -->
	                <!-- <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button> -->
	              </div>
	            </div>
	            <div class="box-body">
	            	<div class="box-group" id="generaldataaccordion">
	            		<div class="panel box overflowhidden box-primary">
	                      <div class="box-header with-border">
		                        <h4 class="box-title">
		                          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#NewPageManagerBlock">
		                            <i class="fa fa-sliders"></i> '.$mainmsgintroout.'
		                          </a>
		                        </h4>
	                      </div>
			                <div id="NewPageManagerBlock" class="panel-collapse collapse in">
								'.$row['introoutput'].'
								'.$row['newcontentoutput'].'
								<script>
										tinyMCE.init({
									        theme : "modern",
									        selector: "textarea#adminposter",
									        skin:"lightgray",
									        width:"94%",
									        height:"650px",
									        external_image_list_url : ""+host_addr+"snippets/mceexternalimages.php",
									        plugins : [
									         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
									         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
									         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
									        ],
									        // toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
									        toolbar1: "undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
									        toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
									        image_advtab: true ,
									        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
									        content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
									        external_filemanager_path:""+host_addr+"scripts/filemanager/",
									        filemanager_title:"Adsbounty Admin Blog Content Filemanager" ,
									        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
										});
										tinyMCE.init({
										        theme : "modern",
										        selector:"textarea#postersmallthree",
										        menubar:false,
										        statusbar: false,
										        plugins : [
										         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
										         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
										         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
										        ],
										        width:"100%",
										        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
										        toolbar2: "| link unlink anchor | emoticons | code ",
										        image_advtab: true ,
										        content_css:""+host_addr+"stylesheets/mce.css",
										        external_filemanager_path:""+host_addr+"scripts/filemanager/",
										        filemanager_title:"Site Content Filemanager" ,
										        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
										});
										tinyMCE.init({
										        theme : "modern",
										        selector:"textarea#postersmallfour",
										        menubar:false,
										        statusbar: false,
										        plugins : [
										         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
										         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
										         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
										        ],
										        width:"80%",
										        height:"300px",
										        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
										        toolbar2: "| responsivefilemanager | link unlink anchor | emoticons | code ",
										        image_advtab: true ,
										        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
										        content_css:""+host_addr+"stylesheets/Max-Migoldlagos.css?"+ new Date().getTime(),
												external_filemanager_path:""+host_addr+"scripts/filemanager/",
											   	filemanager_title:"Max-Migold Admin Blog Content Filemanager" ,
											   	external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
										});   
								</script>
							</div>
						</div>
						<div class="panel box overflowhidden box-primary">
	                      <div class="box-header with-border">
	                        <h4 class="box-title">
	                          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#EditBlock">
	                            <i class="fa fa-gear"></i> '.$mainmsgcontentout.'
	                          </a>
	                        </h4>
	                      </div>
	                      <div id="EditBlock" class="panel-collapse collapse">
	                        <div class="box-body">
	                        	<div class="col-md-12">
		                        	'.$row['adminoutput'].'
	                        	</div>
	                        </div>
	                      </div>
	                  	</div>
					</div>
				</div>
			 </div>
		';
		$row['fullviewtwo']='
			 <div class="box">
	            <div class="box-header with-border">
	              <h3 class="box-title">'.$mainmsgout.'</h3>
	              <div class="box-tools pull-right">
	                <!-- <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button> -->
	                <!-- <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button> -->
	              </div>
	            </div>
	            <div class="box-body">
	            	<div class="box-group" id="generaldataaccordion">
	            		<div class="panel box overflowhidden box-primary">
	                      <div class="box-header with-border">
		                        <h4 class="box-title">
		                          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#NewPageManagerBlock">
		                            <i class="fa fa-sliders"></i> '.$mainmsgintroout.'
		                          </a>
		                        </h4>
	                      </div>
			                <div id="NewPageManagerBlock" class="panel-collapse collapse in">
								'.$row['newcontentoutput'].'
								<script>
									tinyMCE.init({
								        theme : "modern",
								        selector: "textarea#adminposter",
								        skin:"lightgray",
								        width:"94%",
								        height:"650px",
								        external_image_list_url : ""+host_addr+"snippets/mceexternalimages.php",
								        plugins : [
								         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
								         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
								         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
								        ],
								        // toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
								        toolbar1: "undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
								        toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
								        image_advtab: true ,
								        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
								        content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
								        external_filemanager_path:""+host_addr+"scripts/filemanager/",
								        filemanager_title:"Adsbounty Admin Blog Content Filemanager" ,
								        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
									});
									tinyMCE.init({
									        theme : "modern",
									        selector:"textarea#postersmallthree",
									        menubar:false,
									        statusbar: false,
									        plugins : [
									         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
									         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
									         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
									        ],
									        width:"100%",
									        height:"450px",
									        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
									        toolbar2: "| link unlink anchor | emoticons | code ",
									        image_advtab: true ,
									        content_css:""+host_addr+"stylesheets/mce.css",
									        external_filemanager_path:""+host_addr+"scripts/filemanager/",
									        filemanager_title:"Site Content Filemanager" ,
									        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
									});
									tinyMCE.init({
									        theme : "modern",
									        selector:"textarea#postersmallfour",
									        menubar:false,
									        statusbar: false,
									        plugins : [
									         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
									         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
									         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
									        ],
									        width:"80%",
									        height:"400px",
									        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
									        toolbar2: "| responsivefilemanager | link unlink anchor | emoticons | code ",
									        image_advtab: true ,
									        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
									        content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
											external_filemanager_path:""+host_addr+"scripts/filemanager/",
										   	filemanager_title:"Max-Migold Admin Blog Content Filemanager" ,
										   	external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
									});   
								</script>
							</div>
						</div>
						<div class="panel box overflowhidden box-primary">
	                      <div class="box-header with-border">
	                        <h4 class="box-title">
	                          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#EditBlock">
	                            <i class="fa fa-gear"></i> '.$mainmsgcontentout.'
	                          </a>
	                        </h4>
	                      </div>
	                      <div id="EditBlock" class="panel-collapse collapse">
	                        <div class="box-body">
	                        	<div class="col-md-12">
		                        	'.$row['adminoutput'].'
	                        	</div>
	                        </div>
	                      </div>
	                  	</div>
					</div>
				</div>
			 </div>
		';
		$row['fullviewthree']='
			 <div class="box">
	            <div class="box-header with-border">
	              <h3 class="box-title">'.$mainmsgout.'</h3>
	              <div class="box-tools pull-right">
	                <!-- <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button> -->
	                <!-- <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button> -->
	              </div>
	            </div>
	            <div class="box-body">
	            	<div class="box-group" id="generaldataaccordion">
	            		<div class="panel box overflowhidden box-primary">
	                      <div class="box-header with-border">
		                        <h4 class="box-title">
		                          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#NewPageManagerBlock">
		                            <i class="fa fa-sliders"></i> '.$mainmsgintroout.'
		                          </a>
		                        </h4>
	                      </div>
			                <div id="NewPageManagerBlock" class="panel-collapse collapse in">
								'.$row['introoutputtwo'].'
								<script>
										tinyMCE.init({
									        theme : "modern",
									        selector: "textarea#adminposter",
									        skin:"lightgray",
									        width:"94%",
									        height:"650px",
									        external_image_list_url : ""+host_addr+"snippets/mceexternalimages.php",
									        plugins : [
									         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
									         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
									         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
									        ],
									        // toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
									        toolbar1: "undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
									        toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
									        image_advtab: true ,
									        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
									        content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
									        external_filemanager_path:""+host_addr+"scripts/filemanager/",
									        filemanager_title:"Adsbounty Admin Blog Content Filemanager" ,
									        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
										});
										tinyMCE.init({
										        theme : "modern",
										        selector:"textarea#postersmallthree",
										        menubar:false,
										        statusbar: false,
										        plugins : [
										         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
										         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
										         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
										        ],
										        width:"100%",
										        height:"600px",
										        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
										        toolbar2: "| responsivefilemanager | link unlink anchor | emoticons | code ",
										        image_advtab: true ,
										        content_css:""+host_addr+"stylesheets/mce.css",
										        external_filemanager_path:""+host_addr+"scripts/filemanager/",
										        filemanager_title:"Site Content Filemanager" ,
										        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
										});
										tinyMCE.init({
										        theme : "modern",
										        selector:"textarea#postersmallfour",
										        menubar:false,
										        statusbar: false,
										        plugins : [
										         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
										         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
										         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
										        ],
										        width:"80%",
										        height:"300px",
										        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
										        toolbar2: "| responsivefilemanager | link unlink anchor | emoticons | code ",
										        image_advtab: true ,
										        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
										        content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
												external_filemanager_path:""+host_addr+"scripts/filemanager/",
											   	filemanager_title:"Max-Migold Admin Blog Content Filemanager" ,
											   	external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
										});   
								</script>
							</div>
						</div>
						
					</div>
				</div>
			 </div>
		';
		/*
								
		*/
		$row['recadminview']="";
		if ($typeoutcontroller=="tabbed") {
			# code...
			$row['pageout']='
				<div data-role="tabs" id="tabs">
					<div data-role="navbar">
					    <ul data-role="" data-inset="true" class="" data-theme="b">
					      '.$tabout.'
					    </ul>
					</div>
					'.$vieweroutputmaxi.'					

				</div>
			';	
		}else{
			$row['pageout']='
				'.$vieweroutput.'					
			';	
		}
		return $row;
	}
function generateMultipleGDataSelect($viewer,$frameout){
	$outs=array();
	$frameout=isset($frameout)&&$frameout!==""?$frameout:"WHERE id=0";
	if($viewer=="admin"){
		$query="SELECT * FROM generalinfo $frameout order by id desc";

	}else if($viewer=="viewer"){
		$query="SELECT * FROM generalinfo $frameout AND status='active' order by id desc";
	}
    $run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
    $numrows=mysql_num_rows($run);
    $countit=0;
    $extradata="";
    $fniselection="";
    if($numrows>0){
        while ($row=mysql_fetch_assoc($run)) {
            # code...
            $countit++;
            $giid=$row['id'];
            $dataout=getSingleGeneralInfo($giid);
            // echo $dataout['productselection']."<br>";
            // $extradata.=$dataout['productselection']!==""?$dataout['productselection']:"";
            $fniselection.='<option value="'.$row['title'].'" data-id="'.$giid.'">'.$row['title'].'</option>';
            
        }
    }
    $outs['selection']=$fniselection;
    return $outs;
}
?>