<?php
	$slideractive="";
	// define a highlight color array showing the
	// marker and the corresponding element and class to apply
	$parsehighlight=array();
	$parsehighlight['span-red']['class']="color-red";
	$parsehighlight['span-orange']['class']="color-orange";
	$parsehighlight['span-yellow']['class']="color-yellow";
	$parsehighlight['span-green']['class']="color-green";
	$parsehighlight['span-blue']['class']="color-blue";
	$parsehighlight['span-indigo']['class']="color-indigo";
	$parsehighlight['span-violet']['class']="color-violet";
	$parsehighlight['span-ts-red']['class']="text-shadow-color-red";
	$parsehighlight['span-ts-orange']['class']="text-shadow-color-orange";
	$parsehighlight['span-ts-yellow']['class']="text-shadow-color-yellow";
	$parsehighlight['span-ts-green']['class']="text-shadow-color-green";
	$parsehighlight['span-ts-blue']['class']="text-shadow-color-blue";
	$parsehighlight['span-ts-indigo']['class']="text-shadow-color-indigo";
	$parsehighlight['span-ts-violet']['class']="text-shadow-color-violet";
	function getSingleHomeBanner($id,$slideIndex=0,$maxorder=1,$lazyload=""){
		global $host_addr,$slideractive,$parsehighlight;
		$lazyclass="";
		$lazyout='';
		if($lazyload=="true"){
			$lazyclass="lazy";
			$lazyout='class="lazy"';

		}
		$row=array();
		$row=getSingleMediaDataTwo($id);
		$id=$row['id'];
		$mainid=$row['mainid'];
		$mainidout=$mainid-1>-1?$mainid-1:0;
		$detailsdata=explode("[|><|]",$row['details']);
		$headercaption=$detailsdata[0];
		$minicaption=$detailsdata[1];
		$linkaddress=$detailsdata[2];
		$linkdisplay=$detailsdata[3];
		$row['headercaption']=$headercaption;
		$row['minicaption']=$minicaption;
		$row['linkaddress']=$linkaddress;
		$row['linkdisplay']=$linkdisplay;
		$thumbnail=isset($row['thumbnail'])?$row['thumbnail']:$row['location'];
		$row['thumbnail']=$thumbnail;
		$row['original']=$row['location'];
		$medsize=$row['medsize'];
		$row['medsize']=$medsize;
		$headercaptiontwo="";
		$minicaptiontwo="";
		$headerout="";
		$miniout="";
		$linkout="";
		if($headercaption!==""){
			$headercaptiontwo=preg_replace_callback(
					"~\[\|([a-z]{1,})(.*?)?\|\](.*?)\[\|/([a-z]{1,})\|\]|\[\|([a-z]{1,}-[^\|\]\"\'=\s\[]{1,}+)(.*?)\|\](.*?)\[\|/([a-z]{1,}-[^\|\]\"\'=\s\[]{1,}+)\|\]~im",
		            function($matches) use($parsehighlight){
		            	$input=$matches;
		            	$output="";
		            	$fullmatch="";
			    		$frcode="";
			    		$attrgroup="";
			    		$content="";
			    		$endcode="";
			    		$counter1=0;$counter2=0;
		            	if (is_array($input)) {
					    	$fullmatch=$input[0];
					    	if(isset($input[5])&&$input[5]!==""){
					    		$frcode=$input[5];
					    		$attrgroup=$input[6];
					    		$content=$input[7];
					    		$endcode=$input[8];
					    	}else{
					    		$frcode=$input[1];
					    		$attrgroup=$input[2];
					    		$content=$input[3];
					    		$endcode=$input[4];
					    	}

					    	if($frcode==$endcode&&$frcode!==""&&isset($parsehighlight[$frcode])){
						    	$scodedata=$parsehighlight[$frcode];	            	
								$output='<span class="'.$scodedata['class'].'">'.$content.'</span>';
						        // $input = '<div style="margin-left: 10px">'.$input[1].'</div>';
					    		
					    	}
					    }
		  				return $output;
					},$headercaption);
			$headercaptiontwo!==""?$headercaptiontwo=$headercaptiontwo:$headercaptiontwo=$headercaption;
		}
		if($minicaption!==""){
			$minicaptiontwo=preg_replace_callback(
					"~\[\|([a-z]{1,})(.*?)?\|\](.*?)\[\|/([a-z]{1,})\|\]|\[\|([a-z]{1,}-[^\|\]\"\'=\s\[]{1,}+)(.*?)\|\](.*?)\[\|/([a-z]{1,}-[^\|\]\"\'=\s\[]{1,}+)\|\]~im",
		            function($matches) use($parsehighlight){
		            	$input=$matches;
		            	$fullmatch="";
			    		$frcode="";
			    		$attrgroup="";
			    		$content="";
			    		$endcode="";
			    		$counter1=0;$counter2=0;
		            	if (is_array($input)) {
					    	$fullmatch=$input[0];
					    	if(isset($input[5])&&$input[5]!==""){
					    		$frcode=$input[5];
					    		$attrgroup=$input[6];
					    		$content=$input[7];
					    		$endcode=$input[8];
					    	}else{
					    		$frcode=$input[1];
					    		$attrgroup=$input[2];
					    		$content=$input[3];
					    		$endcode=$input[4];
					    	}

					    	if($frcode==$endcode&&$frcode!==""&&isset($parsehighlight[$frcode])){
						    	$scodedata=$parsehighlight[$frcode];	            	
								$output='<span class="'.$scodedata['class'].'">'.$content.'</span>';
						        // $input = '<div style="margin-left: 10px">'.$input[1].'</div>';
					    		
					    	}
					    }
		  				return $output;
					},$minicaption);
			$minicaptiontwo!==""?$minicaptiontwo=$minicaptiontwo:$minicaptiontwo=$minicaption;
		}
		if($linkaddress!==""&&$linkdisplay!==""){
			$linkout='
				<form action="'.$linkaddress.'">
                	<button type="submit" class="btn btn-danger">'.$linkdisplay.'</button>
                </form>
			';
		}
		$coverphoto=isset($row['thumbnail'])?$row['thumbnail']:$row['location'];
		$coverout=file_exists('.'.$coverphoto.'')?'<img src="'.$host_addr.''.$coverphoto.'"/>':"No image set";
		$coverpathout=$row['location']!==""?''.$host_addr.''.$row['location'].'':"";
		$imgid=$row['id'];
		$row['coverout']=$coverout;
		$row['coverpath']=$coverpathout;
		$status=$row['status'];
		$outoptions="";
		for($i=1;$i<=$maxorder;$i++){
			$outoptions.='
				<option value="'.$i.'" data-slideorder="'.$mainidout.'" data-maxorder="'.$maxorder.'">'.$i.'</option>
			';
		}
		$row['adminoutput']='
			<tr data-id="'.$id.'">
				<td class="tdimg"><a href="'.$coverpathout.'" data-lightbox="slidegallery" data-src="'.$coverpathout.'">'.$coverout.'</a></td><td>'.$mainid.'</td><td>'.$headercaption.'</td><td>'.$minicaption.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsinglehomebanner" data-divid="'.$id.'">Edit</a></td>
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
				<div class="row">
				    <form name="homebannerform" method="POST" enctype="multipart/form-data" action="../snippets/edit.php">
				  		<input type="hidden" name="entryvariant" value="edithomebanner"/>
				  		<input type="hidden" name="entryid" value="'.$id.'"/>
				  		<input type="hidden" name="prevmainid_'.$id.'" value="'.$mainid.'"/>
				  		<div class="col-md-12">
				          	<h4>Edit Home Page Banner</h4>
				          	<div class="col-md-12">
								<small>
									Highlightcodes for captions: <br>
									<b>[|span-green|][|/span-green|]</b>,  
									<b>[|span-blue|][|/span-blue|]</b>,  
									<b>[|span-orange|][|/span-orange|]</b><br>
									copy and paste code in any of the caption fields below, then place your text
									between the tags.
								</small>
                            </div>
				          	<div class="col-md-12">
				                <div class="col-xs-12">
				    				<div class="form-group">
				                      <label>Select Slide Image(Preferrably 1920x1280 or 1170x900):</label>
				                      <div class="input-group">
				                        <div class="input-group-addon">
				                          <i class="fa fa-image"></i>
				                        </div>
				                        <input type="file" class="form-control" name="slide'.$id.'" Placeholder=""/>
				                      </div><!-- /.input group -->
				                    </div><!-- /.form group -->
				                    <div class="form-group">
				                      <label>Header Caption(Large size caption):</label>
				                      <div class="input-group">
				                        <div class="input-group-addon">
				                          <i class="fa fa-image"></i>
				                        </div>
				                        <input type="text" class="form-control" name="headercaption'.$id.'" value=\''.str_replace("'", "&#39;",$headercaption).'\' Placeholder=""/>
				                      </div><!-- /.input group -->
				                    </div><!-- /.form group -->
				                    <div class="form-group">
				                      <label>Mini Caption(Small size caption):</label>
				                      <div class="input-group">
				                        <div class="input-group-addon">
				                          <i class="fa fa-image"></i>
				                        </div>
				                        <input type="text" class="form-control" name="minicaption'.$id.'" value=\''.str_replace("'", "&#39;",$minicaption).'\' Placeholder=""/>
				                      </div><!-- /.input group -->
				                    </div><!-- /.form group -->
				                    <div class="form-group">
				                      <label>link Address(for links in the caption):</label>
				                      <div class="input-group">
				                        <div class="input-group-addon">
				                          <i class="fa fa-file-text"></i>
				                        </div>
				                        <input type="text" class="form-control" name="linkaddress'.$id.'" value="'.$linkaddress.'" Placeholder=""/>
				                      </div><!-- /.input group -->
				                    </div><!-- /.form group -->
				                    <div class="form-group">
				                      <label>link Title(The text the link displays):</label>
				                      <div class="input-group">
				                        <div class="input-group-addon">
				                          <i class="fa fa-file-text"></i>
				                        </div>
				                        <input type="text" class="form-control" name="linktitle'.$id.'" value="'.$linkdisplay.'" Placeholder=""/>
				                      </div><!-- /.input group -->
				                    </div><!-- /.form group -->
				                    <div class="form-group">
				                      <label>Slide Position(<b>'.$mainid.'</b>):</label>
				                      <div class="input-group">
				                        <div class="input-group-addon">
				                          <i class="fa fa-arrows-v"></i>
				                        </div>
				                        <select name="mainid_'.$id.'" class="form-control">
											<option value="">Change Slide Position?</option>
											'.$outoptions.'
										</select>
				                      </div><!-- /.input group -->
				                    </div><!-- /.form group -->
				                    <div class="form-group">
				                      <label>Delete This?:</label>
				                      <div class="input-group">
				                        <div class="input-group-addon">
				                          <i class="fa fa-trash"></i>
				                        </div>
				                        <select name="status'.$id.'" class="form-control">
											<option value="">Delete?</option>
											<option value="inactive">Yes</option>
										</select>
				                      </div><!-- /.input group -->
				                    </div><!-- /.form group -->
				                    
				                </div>
				            </div>
				        </div>
				          <div class="col-md-12">

				          </div>
				          <div class="col-md-12">
			  		        <div class="box-footer">
			                  <input type="submit" class="btn btn-danger" name="homebannersubmit" value="Update"/>
			              </div>
				      	</div>
					  </form>
				</div>
		';
		
		$row['tabout']='

			';
		$row['vieweroutput']="";
		$mainsrc=''.$host_addr.''.$medsize.'';
		$datasrc='';
		if($lazyload=="true"){
			$mainsrc=''.$host_addr.'images/tp.png';
			$datasrc='data-src="'.$host_addr.''.$medsize.'"';
		}
		$row['home_bxslider']='
			<li>
                <img src="'.$mainsrc.'" '.$datasrc.' '.$lazyout.' alt="img"/>
                <div class="caption">
                    <div class="holder">
                        <h1>
                            '.$headercaptiontwo.'
                        </h1>
                        <strong class="title">'.$minicaptiontwo.'</strong>
                    </div>
                </div>
            </li>		
		';
		$row['home_bxthumbslider']='
			<a data-slide-index="'.$mainidout.'" href="##" class="rollIn animated slide-thumb-holder">
                <img src="'.$thumbnail.'" class="Image" alt="img"/>
            </a>
		';
		
		return $row;
	}
	function getAllHomebanners($viewer,$type,$limit,$slidetype='homebanner',$lazyload=""){
		global $host_addr,$slideractive;
		if($slidetype==""){
			$slidetype='homebanner';
		}
		$row=array();
		$outputtype="admin";
		str_replace("-", "", $limit);

		$testit=strpos($limit,"-");

		$testit?$limit="":$limit=$limit;
		$testittwo=strpos($limit,",");
		if($testittwo===0||$testittwo===true||$testittwo>0){
			$limit=$limit;
		}else{
			if(strtolower($limit)=="all"){
				$limit="";
			}else{
				$limit="LIMIT 0,15";				
			}
		}
		$frameout="WHERE id=0";
		$mainmsgout=''.strtoupper($type).' Page Manager';
		$mainmsgintroout='Edit '.strtoupper($type).' Page Intro/Create New Content';
		$mainmsgcontentout='Edit '.strtoupper($type).' Page Contents';
		$showhidetitle="";
		$showhideimage="";
		$count=0;
		$row=array();
		$rowmonitor['chiefquery']="SELECT * FROM media WHERE ownertype='$slidetype' AND status='active' order by id desc";
		if($limit!==""&&$viewer=="admin"){
			$query="SELECT * FROM media WHERE ownertype='$slidetype' AND status='active' order by id desc ".$limit."";
		}else if($limit==""&&$viewer=="admin"){
			$query="SELECT * FROM media WHERE ownertype='$slidetype' AND status='active' order by id desc LIMIT 0,15";		
		}elseif($limit!==""&&$viewer=="viewer"){
			$query="SELECT * FROM media WHERE ownertype='$slidetype' AND status='active' order by mainid $limit";
			$rowmonitor['chiefquery']="SELECT * FROM media WHERE ownertype='$slidetype' AND status='active' order by id desc";
		}else if($limit==""&&$viewer=="viewer"){
			$query="SELECT * FROM media WHERE ownertype='$slidetype' AND status='active' order by mainid";		
			$rowmonitor['chiefquery']="SELECT * FROM media WHERE ownertype='$slidetype' AND status='active' order by id desc ";
		}
		/*if($typeid=="active"){
			$query="SELECT * FROM faq WHERE status='active'";
		}*/
		$selection="";
		$run=mysql_query($query)or die(mysql_error()." Line 77");
		$numrows=mysql_num_rows($run);
		$top='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>CoverImage</th><th>SlideIndex</th><th>HeaderCaption</th><th>Minicaption</th><th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		$bottom='	</tbody>
				</table>';
		$adminoutput="<td>No entries</td><td></td><td></td><td></td><td></td><td></td>";
		$vieweroutput='';
		$selection="";
		$carouselindicators="";
		$home_bxslider="";
		$home_bxthumbslider="";
		$curstamp = date("Y-m-d H:i:s"); // current time 

		$curstamp=md5(strtotime($curstamp));

		$estamp="_".$curstamp;

		$_SESSION['gd_contentgroup'.$estamp.'']="";

		if($numrows>0){
			// echo $numrows;
			$vieweroutput="";
			$adminoutput="";
			while($row=mysql_fetch_assoc($run)){
				// end
				if($count==0){
					$slideractive=" active";

				}else{
					$slideractive="";
				}
				$count++;
				if($count==1){
					$cload="";
				}else{
					$cload=$lazyload;
				}
				$outvar=getSingleHomeBanner($row['id'],0,abs($numrows),$cload);
				$home_bxslider.=$outvar['home_bxslider'];
				$home_bxthumbslider.=$outvar['home_bxthumbslider'];
				$adminoutput.=$outvar['adminoutput'];
				$vieweroutput.=str_replace("../", "$host_addr",$outvar['vieweroutput']);
				$selection.='<option value="'.$outvar['id'].'">'.$outvar['title'].'</option>';
				$carouselindicators.='
		            <li data-target="#myCarousel" data-slide-to="'.$count.'" class="active"></li>
				';
			}

		}
		$row['carouselindicators']=$carouselindicators;
		$defbanner='
			<div class="item active">
                <!-- Set the first background image using inline CSS below. -->
                <style type="text/css">
                    .slide1{
                        background-image: url(\'./img/homeBanner1.jpg\');
                        background-size: cover;
                    }
                </style>
                <!-- <img src="./img/homeBanner1.jpg" class="forcewidth"/> -->
                <div class="fill slide1"></div>
                <div class="carousel-caption">
                    <div>
                        <h2 class="textWhite">HAPPY<br />
                            WORKFORCE<br />
                        </h2>
                        <p class="textWhite">Creativity x 100% Efficiency</p>
                        <br />
                        <form action="about.php">
                        <button type="submit" class="btn btn-danger">About Us</button>
                        </form>
                    </div>
                </div>
            </div>
		';
		$row['home_bxslider']=$home_bxslider;
		$row['home_bxthumbslider']=$home_bxthumbslider;
		$row['numrows']=$numrows;
		$adminoutput=$adminoutput==""?"<td>No Content entries for this</td><td></td><td></td><td></td><td></td><td></td>":$adminoutput;
		
		$outs=paginatejavascript($rowmonitor['chiefquery']);
		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
				<input type="hidden" name="outputtype" value="homebanner"/>
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
		$row['adminoutput']=$paginatetop.$top.$adminoutput.$bottom.$paginatebottom;
		$row['adminoutputtwo']=$top.$adminoutput.$bottom;
		if($vieweroutput==""){
			$row['vieweroutput']=$defbanner;
		}else{
			$row['vieweroutput']=$vieweroutput;
			
		}

		$row['selection']=$selection;


		return $row;
	}
?>