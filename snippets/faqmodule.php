<?php
	function getSingleFAQ($id,$data=array()){
		global $host_addr;
		$row=array();
		$viewer="admin";
		// controls order of query data output
		if(isset($data['single']['viewer'])&&$data['single']['viewer']!==""){
			$viewer=$data['single']['viewer'];
		}

		$adminoutput="No entries";
		$vieweroutput='No FAQs Yet, Sorry, we are working on it';
		$queryextra="";
		$ordercontent="order by id desc";
		$qcat=$viewer=="admin"?"WHERE":"AND";

		
		// appendable subqueries 
		if(isset($data['single']['queryextra'])&&$data['single']['queryextra']!==""){
			if($queryextra==""){
				$queryextra.=" $qcat ".$data['single']['queryextra'];
			}else{
				$queryextra.=" AND ".$data['single']['queryextra'];
			}
		}

		// controls order of query data output
		if(isset($data['single']['queryorder'])&&$data['single']['queryorder']!==""){
			$ordercontent=$data['single']['queryorder'];
		}

		$query="SELECT * FROM faq where id=$id $queryextra";
		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
		$numrows=mysql_num_rows($run);
		/*$query2="SELECT * FROM surveys where catid=$typeid";
		$run2=mysql_query($query2)or die(mysql_error()." Line 899");
		$row2=mysql_fetch_assoc($run2);*/

		$row=mysql_fetch_assoc($run);
		$id=$row['id'];
		$name=$row['title'];
		$content=$row['content'];
		$status=$row['status'];
		$row['numrows']=$numrows;
		$row['adminoutput']='
			<tr data-id="'.$id.'">
				<td>'.$name.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsinglefaq" data-divid="'.$id.'">Edit</a></td>
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
					<div id="form" style="background-color:#fefefe;">
						<form action="../snippets/edit.php" name="editfaq" method="post">
							<input type="hidden" name="entryvariant" value="editfaq"/>
							<input type="hidden" name="entryid" value="'.$id.'"/>
							<div id="formheader">Edit "'.$name.'"</div>
								<div id="formend">
									Change Faq title <br>
									<input type="text" placeholder="Enter Faq Title" name="title" value="'.$name.'" class="curved"/>
								</div>
								<div id="formend" style="">
									<span style="font-size:18px;">Change Details:</span><br>
									<textarea name="content" id="adminposter" Placeholder="" class="">'.$content.'</textarea>
								</div>
								<div id="formend">
									Change Status<br>
									<select name="status" class="curved2">
										<option value="">Change Status</option>
										<option value="active">Active</option>
										<option value="inactive">Inactive</option>
									</select>
								</div>
							<div id="formend">
								<input type="submit" name="Update" value="Submit" class="submitbutton"/>
							</div>
						</form>
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
						        filemanager_title:"NYSC LAGOS Admin Blog Content Filemanager" ,
						        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
							});
							tinyMCE.init({
							        theme : "modern",
							        selector:"textarea#postersmalltwo",
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
							        toolbar2: "| link unlink anchor | emoticons",
							        image_advtab: true ,
							        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
							        content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
									external_filemanager_path:""+host_addr+"scripts/filemanager/",
								   	filemanager_title:"NYSC LAGOS Admin Blog Content Filemanager" ,
								   	external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
							});   
					</script>
		';
		$row['vieweroutput']='
			<div class="accordion-group">
                <div class="accordion-heading">
                  <a class="accordion-toggle" data-toggle="collapse" 
                  data-parent="#accordion2" href="#collapse'.$id.'">
                  	'.$name.'
                  </a>
                </div>
                <div id="collapse'.$id.'" class="accordion-body collapse">
                  <div class="accordion-inner">
                  	'.$content.'
                  </div>
                </div>
            </div>
		';
		return $row;
	}

	function getAllFAQ($viewer,$limit,$type="",$data=array()){
		global $host_addr;
		$row=array();
	 	// this block handles the content of the limit data
	 	// testing and stripping it of unnecessary/unwanted characters
		str_replace("-", "", $limit);

		$testittwo=strpos($limit,",");
		// echo $testittwo;
		if($testittwo!==false||$testittwo===true||$testittwo>0){
			$limit=$limit;
		}else{
			if(strtolower($limit)=="all"){
				$limit="";
			}else{
				$limit="LIMIT 0,15";				
			}
		}

		$adminoutput="No entries";
		$vieweroutput='No FAQs Yet, Sorry, we are working on it';
		$queryextra="";
		$ordercontent="order by id desc";
		$qcat=$viewer=="admin"?"WHERE":"AND";

		// controls order of query data output
		if(isset($data['queryorder'])&&$data['queryorder']!==""){
			$ordercontent=$data['queryorder'];
		}
		
		// appendable subqueries 
		if(isset($data['queryextra'])&&$data['queryextra']!==""){
			if($queryextra==""){
				$queryextra.=" $qcat ".$data['queryextra'];
			}else{
				$queryextra.=" AND ".$data['queryextra'];
			}
		}
		$sdata=array();
		$sdata['single']['viewer']="$viewer";
		if($viewer=="admin"){
			$query="SELECT * FROM faq $queryextra $ordercontent $limit";
			$rowmonitor['chiefquery']="SELECT * FROM faq $queryextra $ordercontent";
		}elseif($viewer=="viewer"){
			$query="SELECT * FROM faq WHERE status='active' $queryextra 
			$ordercontent $limit";
			$rowmonitor['chiefquery']="SELECT * FROM faq WHERE status='active' 
			$queryextra $ordercontent";
		}

		if(is_array($viewer)){
			$type=$viewer[0];
			if($type=="search"){
				$searchterm=mysql_real_escape_string($viewer[1]);
				$viewer="viewer";
				$searchdata=explode(" ",$searchterm);
				$searchdataout="";
				for($i=0;$i<count($searchdata);$i++){
					$searchdataout==""?$searchdataout.='(title LIKE \'%'.$searchdata[$i].'%\' OR content LIKE \'%'.$searchdata[$i].'%\') ':$searchdataout.='OR (title LIKE \'%'.$searchdata[$i].'%\' OR content LIKE \'%'.$searchdata[$i].'%\') ';
				}
				$query="SELECT * FROM faq WHERE $searchdataout AND status='active'";		
				$rowmonitor['chiefquery']="SELECT * FROM faq WHERE $searchdataout AND status='active'";
				$vieweroutput='No entries matched <b>"'.$searchterm.'"</b>';

			}
		}
		/*if($typeid=="active"){
			$query="SELECT * FROM faq WHERE status='active'";
		}*/
		$selection="";
		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
		$numrows=mysql_num_rows($run);
		$top='<table id="resultcontenttable" cellspacing="0">
					<thead><tr><th>Title</th><th>Status</th><th>View/Edit</th></tr></thead>
					<tbody>';
		$bottom='	</tbody>
				</table>';

		$monitorpoint="";
		$rowout=array();
		if($numrows>0){
			$vieweroutput="";
			$adminoutput="";
			while($row=mysql_fetch_assoc($run)){
				$outvar=getSingleFAQ($row['id'],$sdata);
				$adminoutput.=$outvar['adminoutput'];
				$rowout[]=$outvar;
				$vieweroutput.=str_replace("../", "$host_addr",$outvar['vieweroutput']);
				$selection.='<option value="'.$outvar['id'].'">'.$outvar['title'].'</option>';

			}
		}
		$outs=paginatejavascript($rowmonitor['chiefquery']);
		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.$rowmonitor['chiefquery'].'"/>
				<input type="hidden" name="outputtype" value="faq"/>
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
		$row['vieweroutput']='
			<div class="accordion">'.$vieweroutput.'</div>	
		';
		$row['selection']=$selection;

		$row['numrows']=$numrows;
		$row['resultdataset']=$rowout;
		return $row;
	}
?>