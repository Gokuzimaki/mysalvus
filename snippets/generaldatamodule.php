<?php
	// The purpose of this module is to allow a general storage and retrieval 
	// 
	$curactive="";
	// for products n services proper link creation, a global sentinel variable is created, just in case
	$prodnservcounter=0;
	// check to see if global url admin pointer session variable is active
	// this variable allows a general redirection to occur to the url
	// specified by the value of the session variable
	if(session_id() == ''){
		session_start();
		$rurladmin=isset($_SESSION['rurladmin'])?$_SESSION['rurladmin']:"";
	}else{
		$rurladmin=isset($_SESSION['rurladmin'])?$_SESSION['rurladmin']:"";
	}
	if(session_id() == ''){
		session_start();
	}
	/** 
	* function name = "getSingleGeneralInfo"
	* this function runs through the 'generalinfo' table and produces result based on id value
	* by default, data parsing is also carried out for rows that have the extradata 
	* column field value in expected format.
	* 
	* @param int|string  $id is the integer id of a VALID entry in the generalinfo
	* table, when this parameter is empty, then a subordinate operation is expected
	* to be carried out as substitute for the id key selection
	* 
	* @param array $datatype is an array that carries a varying number of entries
	* which are used for controlling the nature of the query carried out.
	* the allowed content in the $datatype array are as follows
	* $datatype[0] -> the value here is bipolar in nature, it is either 'viewer'  
	* 				or 'admin', the 'admin' value is the default in the event that
	* 				this index is present in this array. The purpose here is to let
	* 				the application know what kind of data or query is acceptable
	* 				to the 
	* 
	* $datatype[1] -> the value here is used to offset the original id based selection
	* 				query. The value here is usually one that ties in to a 'maintype'
	* 				column value from the generalinfo table.
	* 
	* $datatype[2] -> defines the current entryvariant element value on related forms
	* 				the value defaults to contententry.
	* 
	* $datatype[3] -> the value of this entry controls if the current content being
	* 				selected will be parsed using a custom edit file which is usually
	* 				in the format of edit$subtype.php, where $subtype is the value
	* 				value of the currently selected subtype column valuein the 
	* 				generalinfo table
	* 
	* $datatype[4] -> the value here specifies if the active session variable
	*  $_SESSION['gd_contentgroup'] is to be used or not, if the index is unavailable			
	*  'nouse' is its default value
	* 				
	* @param  multidimensional associative array $contentgroupdata represents a group of
	* data that is used to prep administrative and viewer output data sets
	* 
	* @param md5(date('Y-m-d H:i:s')) $curstamp is a md5 hash value used for marking unique
	* session variable data 
	* 
	* @return multidimensionalassociativearray $row is a multidimensional array that carries a myriad
	* of data which represent the general processed output of the query carried out.
	* This data carries content for pagination of results, administrative view of result
	* formdata for editting the currentresultset 
	*/

	function getSingleGeneralInfo($id,$datatype=array(),$contentgroupdata=array(),$curstamp=""){
		include('globalsmodule.php');
		$row=array();
		$curvariant=isset($datatype[2])&&$datatype[2]!==""?$datatype[2]:"contententry";
		$contentvalue=isset($datatype[0])&&$datatype[0]!==""?$datatype[0]:"";
		$viewertype=isset($datatype[1])&&$datatype[1]!==""?$datatype[1]:"admin";
		$viewer=$viewertype;
		$contentmonitor=isset($datatype[1])&&$datatype[1]=="viewer"?"AND status='active'":"";
		// controls the single  data retrieval for extradata fields, so editform
		// parsing is done where and when necessary
		$runedata=isset($datatype[3])&&$datatype[3]!==""?$datatype[3]:"dosingle";
		// controls if an existing session variable is used or not
		$datamarker=isset($datatype[4])&&$datatype[4]!==""?$datatype[4]:"nouse";

		// controls datapagination for users with ajax, this affects the relative path
		// for the edit file in the event of it being an extradata type
		$viewerpaginate=isset($datatype[5])&&$datatype[5]!==""?$datatype[5]:"nopaginate";
		// for use in the edit singlegeneraldata of the display.php file
		$maincontentvalue=isset($datatype[6])&&$datatype[6]!==""?$datatype[6]:"";
		// var_dump($datatype);
		// echo $viewerpaginate." - Viewer paginate";
		$outputtype=$viewertype."-".$contentvalue;
		if($maincontentvalue!==""){
			$outputtype=$viewertype."-".$maincontentvalue;
			// echo $outputtype;
		}
		// variables controlling the form name and 
		// submit button name of the general data name
		$formdataname="contentform";
		$formvariant="contententry";
		$formsubmitname="submitcontent";
		$formsubmittitle="Create/Update";
		$formsubmittype="submit";
		$formsubmitdataattr="";
		$mceidtrip="";
		$mceidtriptwo="";
		$mainmsgout="";
		$mainmsgintroout="";
		$mainmsgcontentout="";
		$formtypeout="";
		$subtypeout="";
		$heading_extras="";

		// set curstamp value appropriately
		$curstamp==""&&isset($contentgroupdata['curstamp'])?$curstamp=$contentgroupdata['curstamp']:$curstamp=$curstamp;
		$showhidetitle="";
		$showhidesubtitle="";
		$showhideimage="";
		$showhideintro="";
		$showhidecontent="";
		$showhidestatus="";
		$showhideall="";
		$extraformdata=$rurladmin!==""?'<input type="hidden" name="rurladmin" value="'.$rurladmin.'">':"";

		// Edit form content header and output displays
		$contenttextheaderout="Edit Content Entry";
		$contenttexttitleout="Content Title";
		$contenttextsubtitleout="Content Sub-Title";
		$contenttextimageout="Content Photo";
		$contenttextintroout="Content Intro";
		$contenttextcontentout="Content Post";
		$contenttextstatusout="Disable/Enable this";

		// Edit form attribute displays
		$contentattrtitleout="";
		$contentattrsubtitleout="";
		$contentattrimageout="";
		$contentattrintroout="";
		$contentattrcontentout="";
		$contentattrstatusout="";

		// Edit form Placeholders  and output displays
		$contentplaceholdertitleout="The title of the entry";
		$contentplaceholdersubtitleout="The subtitle of the entry";
		$contentplaceholderimageout="The Image for the entry";
		$contentplaceholderintroout="The introduction for the entry";
		$contentplaceholdercontentout="The content for the entry";
		$contentplaceholderstatusout="--Choose Status--";

		// for intro forms only
		$itsubmitbtnname=isset($itsubmitbtnname)?$itsubmitbtnname:"submitintro";
		$itsubmitbtntype=isset($itsubmitbtntype)?$itsubmitbtntype:"button";
		$itsubmitbtnattr=isset($itsubmitbtnattr)?$itsubmitbtnattr:"";
		$itformname=isset($itformname)?$itformname:"introform";

		// extraformdata positioning variables, each variable puts the extra data 
		// at the appropriate point in the form
		$extraformtitle="";
		$extraformsubtitle="";
		$extraformimage="";
		$extraformintro="";
		$extraformcontent="";
		$extraformstatus="";
		$extraformscript="";
		$extraformcasingtop="";
		$extraformcasingbottom="";

		// tinymce script segment, holds default values for common mce elements
		$wholescript='
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
		        filemanager_title:"Admin Blog Content Filemanager" ,
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
				   	filemanager_title:"Admin Content Filemanager" ,
				   	external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
			});
		';

		$query=isset($contentvalue)&&$contentvalue!==""&&$id==""?
		"SELECT * FROM generalinfo WHERE maintype='$contentvalue' $contentmonitor":
		"SELECT * FROM generalinfo where id=$id";

		// echo "curquery - $query<br>";
		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__."<br>$query<br>");
		$numrows=mysql_num_rows($run);
		$row=mysql_fetch_assoc($run);
		if($numrows>0){
			$id=$row['id'];
			$maintype=$row['maintype'];
			$subtype=$row['subtype'];
			$subtypestyleout='style="display:none"';
			$title=$row['title'];
			$subtitle=$row['subtitle'];
			$intro=$row['intro'];
			$content=str_replace("../../", "$host_addr",$row['content']);
			$date=$row['entrydate'];
			$default=$row['defaultmarker'];
			$defaultcontent=$row['defaultcontent'];
			$extradata=$row['extradata'];
			$formdata=$row['formdata'];
			$extraformdata=$row['extraformdata'];
			$errormap=$row['formerrordata'];
			$row['numrows']=$numrows;

			$status=$row['status'];
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

			// echo count($contentgroupdata).", the count out<br>";
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
			if ($extradata!=="") {
				// this variable tells if the current data being handled 
				// carries a controlpoint data map attribute with it
				$plaingd_edit="";
				// carries the gd_unit information
				// init the datamap variable for holding data map info from the 
				// contentgroupdata array index ['datamap'] and placing it in the
				// td[name=trcontrolpoint] a "data-edata" attribute 
				$datamap="";
				$gd_data=array();
				$processroute="";
				$editroute="";

				// get the edit file for the current module being used
				$editfile="edit".$subtype.".php";
				// get the default editfile module
				$editfiledefault="gdmoduledataeditdefault.php";
					// echo $editfile;
				// if ita viewer coming in, the path changes relative to the page the script is
				// called from
				if($viewer=="viewer"&&$viewerpaginate=="nopaginate"){
					$editfile="snippets/".$editfile;
					// echo $editfile;
				}
				// $editfile=$host_addr."snippets/edit".$subtype.".php";
				// parse the content from the module below
				include('gdmoduledataparser.php');
				// echo file_exists("$editfile")."$extradata $runedata $subtype $editfile sortee<br><br><br><br>";
				if(file_exists("$editfile")&&$editfile!=="edit.php"&&$runedata!=="rundouble"){
					// pull in the edit file 
					// echo $editfile." - edit file test";
					include("$editfile");
				}else{
					include("$editfiledefault");
					$plaingd_edit="true";

					// echo "no go $viewer $editfile";
				}
			}
			$curstamp = date("Y-m-d H:i:s"); // current time 
			$curstamp=md5(strtotime($curstamp));
			$estamp="_".$curstamp;
			$usesession="";
			// check if the contentgroupdata has its own information 
			if(count($contentgroupdata)>0&&(
				isset($contentgroupdata['curstamp'])
				||isset($contentgroupdata['evaldata'])
				||isset($contentgroupdata['contentlabels'])
				||isset($contentgroupdata['contentplaceholder'])
				||isset($contentgroupdata['extraformdata'])
				||isset($contentgroupdata['datamap'])
				||isset($contentgroupdata['editmap'])
				||isset($contentgroupdata['validationmap'])
				||isset($contentgroupdata['adminheadings'])
				||isset($contentgroupdata['adminheadingsdata'])
				)){
				$usesession="false";
			}
			// prep the unique session variable for carrying content data across
			// other pages, and hijack the contentgroupdata array if conditions are met
			if(isset($_SESSION['gd_contentgroup'][''.$outputtype.''])
				&&isset($_SESSION['gd_contentgroup'][''.$outputtype.'']['curstamp'])
				&&$_SESSION['gd_contentgroup'][''.$outputtype.'']['curstamp']!==""
				&&$usesession!=="false"&&count($contentgroupdata)<1){
				$curstamp=$_SESSION['gd_contentgroup'][''.$outputtype.'']['curstamp'];
				$contentgroupdata=$_SESSION['gd_contentgroup'][''.$outputtype.''];
				// var_dump($contentgroupdata);

				$estamp="_".$curstamp;
			}else{
				// create a new session variable
				unset($_SESSION['gd_contentgroup'][''.$outputtype.'']);
				$_SESSION['gd_contentgroup'][''.$outputtype.'']="";
			}
			// var_dump($contentgroupdata);
			$datamapout="";
			$datamap="";
			if(count($contentgroupdata)>0){
				$contentgroupdata['curstamp']=$curstamp;
				$_SESSION['gd_contentgroup'][''.$outputtype.'']=$contentgroupdata;
				if(isset($contentgroupdata['contentmap'])){
					// hidden content map for the default set of elements available to the
					// generaldatamodule

				}
				if(isset($contentgroupdata['datamap'])){
					// data map for operating on the current set of data 
					// as regards ht process or editrout for handling it
					$datamap=$contentgroupdata['datamap'];
					$gd_testdata=JSONtoPHP($datamap);
					$gd_dataoutput=$gd_testdata['arrayoutput'];
					// var_dump($gd_dataoutput);

					$processroute=isset($gd_dataoutput['pr'])?$gd_dataoutput['pr']:"";

					$editroute=isset($gd_dataoutput['er'])?$gd_dataoutput['er']:$processroute;

					$datamapout='data-edata=\''.$contentgroupdata['datamap'].'\'';

				}
				if(isset($contentgroupdata['contentgdset'])){
					// the labels for the current set of content are passed in here
					$mainmsgout=isset($contentgroupdata['contentgdset']['mainmsgout'])?$contentgroupdata['contentgdset']['mainmsgout']:$mainmsgout;
					$mainmsgintroout=isset($contentgroupdata['contentgdset']['mainmsgintroout'])?$contentgroupdata['contentgdset']['mainmsgintroout']:$mainmsgintroout;
					$mainmsgcontentout=isset($contentgroupdata['contentgdset']['mainmsgcontentout'])?$contentgroupdata['contentgdset']['mainmsgcontentout']:$mainmsgcontentout;

					$mceidtrip=isset($contentgroupdata['contentgdset']['mceidtrip'])?$contentgroupdata['contentgdset']['mceidtrip']:$mceidtrip;
					$mceidtriptwo=isset($contentgroupdata['contentgdset']['mceidtriptwo'])?$contentgroupdata['contentgdset']['mceidtriptwo']:$mceidtriptwo;

					// hidden content map
					$showhidetitle=isset($contentgroupdata['contentgdset']['showhidetitle'])?$contentgroupdata['contentgdset']['showhidetitle']:$showhidetitle;
					$showhidesubtitle=isset($contentgroupdata['contentgdset']['showhidesubtitle'])?$contentgroupdata['contentgdset']['showhidesubtitle']:$showhidesubtitle;
					$showhideimage=isset($contentgroupdata['contentgdset']['showhideimage'])?$contentgroupdata['contentgdset']['showhideimage']:$showhideimage;
					$showhideintro=isset($contentgroupdata['contentgdset']['showhideintro'])?$contentgroupdata['contentgdset']['showhideintro']:$showhideintro;
					$showhidecontent=isset($contentgroupdata['contentgdset']['showhidecontent'])?$contentgroupdata['contentgdset']['showhidecontent']:$showhidecontent;
					$showhidestatus=isset($contentgroupdata['contentgdset']['showhidestatus'])?$contentgroupdata['contentgdset']['showhidestatus']:$showhidestatus;


					$formtypeout=isset($contentgroupdata['contentgdset']['formtypeout'])?$contentgroupdata['contentgdset']['formtypeout']:$formtypeout;
					$formmonitor=isset($contentgroupdata['contentgdset']['formmonitor'])?$contentgroupdata['contentgdset']['formmonitor']:$formmonitor;
					$wholescript=isset($contentgroupdata['contentgdset']['wholescript'])?$contentgroupdata['contentgdset']['wholescript']:$wholescript;

					// for breaking into or removing subtype
					$subtypeout=isset($contentgroupdata['contentgdset']['subtypeout'])?$contentgroupdata['contentgdset']['subtypeout']:$subtypeout;
					$subtypestyleout=isset($contentgroupdata['contentgdset']['subtypestyleout'])?$contentgroupdata['contentgdset']['subtypestyleout']:$subtypestyleout;
					// new form content header and output displays
					$contenttextheaderout=isset($contentgroupdata['contentgdset']['contenttextheaderout'])?$contentgroupdata['contentgdset']['contenttextheaderout']:$contenttextheaderout;
					$contenttexttitleout=isset($contentgroupdata['contentgdset']['contenttexttitleout'])?$contentgroupdata['contentgdset']['contenttexttitleout']:$contenttexttitleout;
					$contenttextsubtitleout=isset($contentgroupdata['contentgdset']['contenttextsubtitleout'])?$contentgroupdata['contentgdset']['contenttextsubtitleout']:$contenttextsubtitleout;
					$contenttextimageout=isset($contentgroupdata['contentgdset']['contenttextimageout'])?$contentgroupdata['contentgdset']['contenttextimageout']:$contenttextimageout;
					$contenttextintroout=isset($contentgroupdata['contentgdset']['contenttextintroout'])?$contentgroupdata['contentgdset']['contenttextintroout']:$contenttextintroout;				
					$contenttextcontentout=isset($contentgroupdata['contentgdset']['contenttextcontentout'])?$contentgroupdata['contentgdset']['contenttextcontentout']:$contenttextcontentout;
					$contenttextstatusout=isset($contentgroupdata['contentgdset']['contenttextstatusout'])?$contentgroupdata['contentgdset']['contenttextstatusout']:$contenttextstatusout;

					// Edit form Placeholders  and output displays
					$contentplaceholdertitleout=isset($contentgroupdata['contentgdset']['contentplaceholdertitleout'])?$contentgroupdata['contentgdset']['contentplaceholdertitleout']:$contentplaceholdertitleout;
					$contentplaceholdersubtitleout=isset($contentgroupdata['contentgdset']['contentplaceholdersubtitleout'])?$contentgroupdata['contentgdset']['contentplaceholdersubtitleout']:$contentplaceholdersubtitleout;
					$contentplaceholderimageout=isset($contentgroupdata['contentgdset']['contentplaceholderimageout'])?$contentgroupdata['contentgdset']['contentplaceholderimageout']:$contentplaceholderimageout;
					$contentplaceholderintroout=isset($contentgroupdata['contentgdset']['contentplaceholderintroout'])?$contentgroupdata['contentgdset']['contentplaceholderintroout']:$contentplaceholderintroout;
					$contentplaceholdercontentout=isset($contentgroupdata['contentgdset']['contentplaceholdercontentout'])?$contentgroupdata['contentgdset']['contentplaceholdercontentout']:$contentplaceholdercontentout;
					$contentplaceholderstatusout=isset($contentgroupdata['contentgdset']['contentplaceholderstatusout'])?$contentgroupdata['contentgdset']['contentplaceholderstatusout']:$contentplaceholderstatusout;

					$itsubmitbtnname=isset($contentgroupdata['contentgdset']['itsubmitbtnname'])?$contentgroupdata['contentgdset']['itsubmitbtnname']:$itsubmitbtnname;
					$itsubmitbtntype=isset($contentgroupdata['contentgdset']['itsubmitbtntype'])?$contentgroupdata['contentgdset']['itsubmitbtntype']:$itsubmitbtntype;
					$itsubmitbtnattr=isset($contentgroupdata['contentgdset']['itsubmitbtnattr'])?$contentgroupdata['contentgdset']['itsubmitbtnattr']:$itsubmitbtnattr;
					$itformname=isset($contentgroupdata['contentgdset']['itformname'])?$contentgroupdata['contentgdset']['itformname']:$itformname;

					$extraformdata.=isset($contentgroupdata['contentgdset']['extraformdata'])?$contentgroupdata['contentgdset']['extraformdata']:$extraformdata;
					// extraformdata positioning variables, each variable puts the extra data at 
					// the appropriate point in the form
					$extraformtitle=isset($contentgroupdata['contentgdset']['extraformtitle'])?$contentgroupdata['contentgdset']['extraformtitle']:$extraformtitle;
					$extraformsubtitle=isset($contentgroupdata['contentgdset']['extraformsubtitle'])?$contentgroupdata['contentgdset']['extraformsubtitle']:$extraformsubtitle;
					$extraformimage=isset($contentgroupdata['contentgdset']['extraformimage'])?$contentgroupdata['contentgdset']['extraformimage']:$extraformimage;
					$extraformintro=isset($contentgroupdata['contentgdset']['extraformintro'])?$contentgroupdata['contentgdset']['extraformintro']:$extraformintro;
					$extraformcontent=isset($contentgroupdata['contentgdset']['extraformcontent'])?$contentgroupdata['contentgdset']['extraformcontent']:$extraformcontent;
					$extraformstatus=isset($contentgroupdata['contentgdset']['extraformstatus'])?$contentgroupdata['contentgdset']['extraformstatus']:$extraformstatus;
					$extraformscript=isset($contentgroupdata['contentgdset']['extraformscript'])?$contentgroupdata['contentgdset']['extraformscript']:$extraformscript;
					$heading_extras=isset($contentgroupdata['contentgdset']['heading_extras'])?$contentgroupdata['contentgdset']['heading_extras']:$heading_extras;
				}
				
				if(isset($contentgroupdata['evaldata'])){
					// for running php code directly when necessary and ONLY when
					// necessary, to be used with caution
					// its associative array values are as follows for the single and general
					// data sections
					// the variables below act as markers telling the availablility
					// of any eval-able array values at certain positions in the code
					$doiniteval="";
					$doprocesseval="";
					$doadminoutputeval="";
					$dooutputeval="";
					$dopostoutputeval="";
					$doinitevaldata="";
					$doprocessevaldata="";
					$doadminoutputevaldata="";
					$dooutputevaldata="";
					$dopostoutputevaldata="";
					/*Welcome to the evil eval section*/
					// initialisation section for variables to be used before the $type
					// control block
					// ['single']['initeval'] 
					// ['single']['processeval'] 
					// ['single']['adminoutputeval'] 
					// ['single']['outputeval'] 
					// initialisation section for variables to be used in the single loop
					// section after intended query is run
					// ['general']['initvars'] var 
					// ['general']['functions'] function to call as required
					// ['single']['initvars'] initialisation section for variables to be used
					// ['single']['functions'] function to call or code to exec as required
					if(isset($contentgroupdata['evaldata']['single'])){
						if (isset($contentgroupdata['evaldata']['single']['initeval'])
							&&$contentgroupdata['evaldata']['single']['initeval']!=="") {
							# code...
							// echo "init done<br>";
							$doiniteval="true";
							$doinitevaldata=$contentgroupdata['evaldata']['single']['initeval'];
							// echo $doinitevaldata." - do init eval<br>";
						}
						if (isset($contentgroupdata['evaldata']['single']['processeval'])
							&&$contentgroupdata['evaldata']['single']['processeval']!=="") {
							# code...
							$doprocesseval="true";
							$doprocessevaldata=$contentgroupdata['evaldata']['single']['processeval'];
						}
						if (isset($contentgroupdata['evaldata']['single']['adminoutputeval'])
							&&$contentgroupdata['evaldata']['single']['adminoutputeval']!=="") {
							# code...
							$doadminoutputeval="true";
							$doadminoutputevaldata=$contentgroupdata['evaldata']['single']['adminoutputeval'];
						}
						if (isset($contentgroupdata['evaldata']['single']['outputeval'])
							&&$contentgroupdata['evaldata']['single']['outputeval']!=="") {
							# code...
							$dooutputeval="true";
							$dooutputevaldata=$contentgroupdata['evaldata']['single']['outputeval'];
						}
						if (isset($contentgroupdata['evaldata']['single']['postoutputeval'])
							&&$contentgroupdata['evaldata']['single']['postoutputeval']!=="") {
							# code...
							$dopostoutputeval="true";
							$dopostoutputevaldata=$contentgroupdata['evaldata']['single']['postoutputeval'];
						}
					}
				}
				if(isset($contentgroupdata['adminheadings'])){
					// for carrying default content in the admin edit interfaces
					$adminrowdefaults=isset($contentgroupdata['adminheadings']['rowdefaults'])
										&&$contentgroupdata['adminheadings']['rowdefaults']!==""?
										$contentgroupdata['adminheadings']['rowdefaults']:"";
					$adminoutputdefaults=isset($contentgroupdata['adminheadings']['output'])
					&&$contentgroupdata['adminheadings']['output']!==""?$contentgroupdata['adminheadings']['output']:"";
					// do a loop that generates the expected number of td elements for empty instances
					if(is_numeric($adminoutputdefaults)&&$adminoutputdefaults>0){
						$ad="";
						for($i=1;$i<=$adminoutputdefaults;$i++){
							$ad.=$i==1?'<td>No entries Yet</td>':'<td></td>';
						}
						$adminoutputdefaults=$ad;
					}else if(is_numeric($adminoutputdefaults)&&$adminoutputdefaults==0){
						$adminoutputdefaults="";
					}
				}

				if(isset($contentgroupdata['adminheadingsdata'])){
					// used for passing in content for the admin table heading and individual
					// content heading
					$adminheadings=isset($contentgroupdata['adminheadingsdata']['singlecontent'])?
									$contentgroupdata['adminheadingsdata']['singlecontent']:"";
					$dummiedata=explode("<th>", $adminheadingsdata);
					$dummies="";
					if(count($dummiedata)>0){
						$heading_extras=$adminheadings;
						$dummies=count($dummiedata);
					}
				}
			}
			// this portion handles the generation of an edit form for the current
			// content by first initialising necessary variables from its datamap
			// and then processing its contents and storing them into a variable
			// then ultimately overiding the adminoutputtwo output array index
			// this is the adminoutputoverride variable 
			$aotoverride="";
			if($extradata!==""&&$plaingd_edit=="true"){

				// at this point there are several variables at the ready
				// from the gdmoduledataeditdefault.php file.
				// the filepath for processing is obtained from the previously processed
				// datamap entry, the variable holding the current path to
				// the file for editing the current extradata cotnent
				// is $editroute, this route/path is a relative path from
				// the root directory of the project
				$processroute=isset($gd_dataoutput['pr'])?$gd_dataoutput['pr']:"";
				$editroute=isset($gd_dataoutput['er'])?$gd_dataoutput['er']:$processroute;
				if($editroute!=="" &&file_exists("../$editroute")){
					// get the editfile contents with all variables available
					$aotoverride=get_include_contents("../$editroute",$gd_vars,$gd_vals);
					// echo $editroute;
					
				}else{
					$aotoverride="<div class='col-md-12'> No valid route found</div>";
				}
				// isset($gd_dataoutput)?var_dump($gd_dataoutput):$gd_dataoutput="";
			}

			// do init eval here
			if(isset($doiniteval)&&$doiniteval=="true"){
				// var_dump($doinitevaldata);
				eval($doinitevaldata);
			}
			// do hide all
			if($showhideall=="hide"){
				$showhidetitle='display:none;';
				$showhidesubtitle='display:none;';
				$showhideimage='display:none;';
				$showhideintro='display:none;';
				$showhidecontent='display:none;';
				$showhidestatus='display:none;';
			}else if($showhideall=="statusonly"){
				$showhidetitle='display:none;';
				$showhidesubtitle='display:none;';
				$showhideimage='display:none;';
				$showhideintro='display:none;';
				$showhidecontent='display:none;';
			}

			if($maintype=="defaultsocial"){
				$subtypestyleout="";
				// $showhidetitle="display:none;";
				$showhidesubtitle="display:none;";
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
	                              <option value="defaultfacebookpage">Facebook Page <i class="fa fa-facebook"></i></option>
	                              <option value="defaulttwitter">Twitter <i class="fa fa-twitter"></i></option>
	                              <option value="defaultlinkedin">LinkedIn <i class="fa fa-linkedin"></i></option>
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
				$showhidesubtitle="display:none;";
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
			}
			// do process eval here
			if(isset($doprocesseval)&&$doprocesseval=="true"){
				eval($doprocessevaldata);
			}
			$tddataoutput=isset($tddataoutput)&&$tddataoutput!==""?$tddataoutput:'<td class="tdimg">'.$coverout.'</td><td>'.strtoupper($maintype).'</td><td '.$subtypestyleout.'>'.strtoupper($subtype).'</td><td>'.$title.'</td><td>'.$status.'</td><td name="trcontrolpoint"><a href="#&id='.$id.'" name="edit" data-type="editsinglegeneraldata" data-divid="'.$id.'">Edit</a></td>';
			$row['adminoutput']='
				<tr data-id="'.$id.'">
					'.$tddataoutput.'
				</tr>
				<tr name="tableeditcontainer" data-state="inactive" data-divid="'.$id.'" '.$datamapout.'>
					<td colspan="100%">
						<div id="completeresultdisplay" data-type="editmodal" data-load="unloaded" data-divid="'.$id.'">
							<div id="completeresultdisplaycontent" data-type="editdisplay" data-divid="'.$id.'">
								
							</div>
						</div>
					</td>
				</tr>
				';
			$row['adminoutputtwo']='
				'.$extraformcasingtop.'
				<div class="col-md-12">
					<form name="'.$formdataname.'" method="POST" enctype="multipart/form-data" action="../snippets/edit.php">
	            		<input type="hidden" name="entryvariant" value="'.$curvariant.'"/>
	            		<input type="hidden" name="maintype" value="'.$maintype.'"/>
	            		<input type="hidden" name="subtype" value="'.$subtype.'"/>
	            		<input type="hidden" name="entryid" value="'.$id.'"/>
	            		<input type="hidden" name="coverid" value="'.$imgid.'"/>
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
				                      <input type="text" class="form-control" name="contenttitle'.$showhidetitle.'" '.$contentattrtitleout.'value="'.str_replace('"', "'",$title).'" Placeholder="'.$contentplaceholdertitleout.'"/>
				                   </div><!-- /.input group -->
				                </div>
				            </div>
				            '.$extraformsubtitle.'
	                    	<div class="col-md-6" style="'.$showhidesubtitle.'">
	                    		<div class="form-group">
				                  <label>'.$contenttextsubtitleout.'</label>
				                  <div class="input-group">
				                      <div class="input-group-addon">
				                        <i class="fa fa-bars"></i>
				                      </div>
				                      <input type="text" class="form-control" name="contentsubtitle'.$showhidesubtitle.'" '.$contentattrsubtitleout.' value="'.str_replace('"', "'",$subtitle).'" Placeholder="'.$contentplaceholdersubtitleout.'"/>
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
				                      <input type="file" class="form-control" name="contentpic'.$showhideimage.'" '.$contentattrimageout.' Placeholder="'.$contentplaceholderimageout.'"/>
				                   </div><!-- /.input group -->
				                </div>
				            </div>
				            '.$extraformintro.'
				            <div class="col-md-12" style="'.$showhideintro.'">
	                    		<div class="form-group">
				                  <label>'.$contenttextintroout.'</label>
				                  <textarea class="form-control" rows="3" name="contentintro'.$showhideintro.'" '.$contentattrintroout.' id="postersmallthree'.$mceidtrip.'" placeholder="'.$contentplaceholderintroout.'">'.$intro.'</textarea>
				                </div>
				            </div>
				            '.$extraformcontent.'
	                    	<div class="col-md-12" style="'.$showhidecontent.'">
	                    		<div class="form-group">
				                  <label>'.$contenttextcontentout.'</label>
				                  <textarea class="form-control" rows="3" name="contentpost'.$showhidecontent.'" '.$contentattrcontentout.' id="postersmallfive'.$mceidtriptwo.'" placeholder="'.$contentplaceholdercontentout.'">'.$content.'</textarea>
				                </div>
				            </div>
				            '.$extraformdata.'
	                	</div>
				        '.$extraformstatus.'
	                	<div class="col-md-12" style="'.$showhidestatus.'">
	                        <label>'.$contenttextstatusout.'</label>
	                        <select name="status" id="status" '.$contentattrstatusout.' style="'.$showhidestatus.'" class="form-control">
	                        	<option value="">'.$contentplaceholderstatusout.'</option>
	                        	<option value="active">Active</option>
	                        	<option value="inactive">Inactive</option>
					  	    </select>
	                    </div>
	            		<input type="hidden" name="formdata" value="'.$formdataname.'"/>
						<script>
								'.$wholescript.'
								'.$extraformscript.'  
						</script>
						<div class="col-md-12">
		        			<div class="box-footer">
			                    <input type="'.$formsubmittype.'" class="btn btn-danger" name="'.$formsubmitname.'" '.$formsubmitdataattr.' value="'.$formsubmittitle.'"/>
			                </div>
		            	</div>
		            </form>
		        </div>
				'.$extraformcasingbottom.'
			';
			// override the adminoutputtwo index with the $aotoverride variable
			// if necessary conditions are met
			if($extradata!==""&&$plaingd_edit=="true"&&$aotoverride!==""){
				$row['adminoutputtwo']=$aotoverride;
				// echo "overrride";
			}
			// do adminoutput eval here
			if(isset($doadminoutputeval)&&$doadminoutputeval=="true"){
				eval($doadminoutputevaldata);
			}
			// initialize default output arrays
			$row['vieweroutputmaxi']='';
			$row['vieweroutputmini']='';
			$row['linkoutput']="";
			$row['linkoutputtwo']="";
			$row['tabout']='';
			$row['vieweroutput']='';
			$row['vieweroutputmini']='';
			$row['vieweroutputmaxi']='';
			// do ouput eval here
			if(isset($dooutputeval)&&$dooutputeval=="true"){
				eval($dooutputevaldata);
			}
			if ($subtype=="defaultphonenumbers"||$subtype=="defaultemailaddress"||$subtype=="defaultmainaddress") {
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
				
			}
			// do post ouput eval here
			if(isset($dopostoutputeval)&&$dopostoutputeval=="true"){
				// echo "Doingoutput eva;";
				eval($dopostoutputevaldata);
			}
		}else{
			$row['id']="";
			$row['maintype']="";
			$row['subtype']="";
			$subtypestyleout='style="display:none"';
			$row['title']="";
			$row['subtitle']="";
			$row['intro']="";
			$row['content']="";
			$row['entrydate']="";
			$row['defaultmarker']="yes";
			$row['defaultcontent']="";
			$row['extradata']="";
			$row['formdata']="";
			$row['extraformdata']="";
			$row['formerrordata']="";
			$row['status']="";
			$row['_gdunit_data']="";
			$row['numrows']=0;
		}
		return $row;
	}

	/** 
	* function name = "getSingleGeneralInfo"
	* this function runs through the 'generalinfo' table and produces result based on id value
	* by default, data parsing is also carried out for rows that have the extradata 
	* column field value in expected format.
	* 
	* @param int|string  $id is the integer id of a VALID entry in the generalinfo
	* table, when this parameter is empty, then a subordinate operation is expected
	* to be carried out as substitute for the id key selection
	* 
	* @param array $datatype is an array that carries a varying number of entries
	* which are used for controlling the nature of the query carried out.
	* the allowed content in the $datatype array are as follows
	* $datatype[0] -> the value here is bipolar in nature, it is either 'viewer'  
	* 				or 'admin', the 'admin' value is the default in the event that
	* 				this index is present in this array. The purpose here is to let
	* 				the application know what kind of data or query is acceptable
	* 				to the 
	* 
	* $datatype[1] -> the value here is used to offset the original id based selection
	* 				query. The value here is usually one that ties in to a 'maintype'
	* 				column value from the generalinfo table.
	* 
	* $datatype[2] -> defines the current entryvariant element value on related forms
	* 				the value defaults to contententry.
	* 
	* $datatype[3] -> the value of this entry controls if the current content being
	* 				selected will be parsed using a custom edit file which is usually
	* 				in the format of edit$subtype.php, where $subtype is the value
	* 				value of the currently selected subtype column valuein the 
	* 				generalinfo table
	* 
	* $datatype[4] -> the value here specifies if the active session variable
	*  $_SESSION['gd_contentgroup'] is to be used or not, if the index is unavailable			
	*  'nouse' is its default value
	* 				
	* @param  multidimensional associative array $contentgroupdata represents a group of
	* data that is used to prep administrative and viewer output data sets
	* 
	* @param md5(date('Y-m-d H:i:s')) $curstamp is a md5 hash value used for marking unique
	* session variable data 
	* 
	* @return multidimensionalassociativearray $row is a multidimensional array that carries a myriad
	* of data which represent the general processed output of the query carried out.
	* This data carries content for pagination of results, administrative view of result
	* formdata for editting the currentresultset 
	*/

	function getAllGeneralInfo($viewer,$type,$limit,$storetype='',$contentgroupdata=array(),$datamarker=""){
		include('globalsmodule.php');
		$row=array();
		$testit=strpos($limit,"-");
		if($testit===0||$testit===true||$testit>0){
			$limit=$limit;
		}else{
			$limit="";
		}
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
		// variable holding final mysql query order data,
		// can also be used to hold more query related info along with the order info
		$ordercontent="order by id desc";
		if(isset($contentgroupdata['queryorder'])&&$contentgroupdata['queryorder']!==""){
			$ordercontent=$contentgroupdata['queryorder'];
		}
		// for broader spectrum data transmission to the 
		// singlegeneral info function
		if(is_array($type)){
			//curvariant
			isset($type[1])?$datatype[2]=$type[1]:$endor="";
			// runedata - the editform parser trigger
			isset($type[2])?$datatype[3]=$type[2]:$endor="";
			// datamarker - controls use of existing session gdmodule variable
			// for current entry processing
			isset($type[3])?$datatype[4]=$type[3]:$endor="";
			isset($type[4])?$datatype[5]=$type[4]:$datatype[5]="nopaginate";
			isset($type['order'])?$ordercontent=$type['order']:$endor="";
			// echo $ordercontent;
			// set the type value to the original value it should have
			isset($type[0])?$type=$type[0]:$type=$storetype;
		}
		$storetype==""?$storetype=$type:$storetype=$storetype;
		$outputtype=$viewer."-".$storetype;
		$datatype[0]=$storetype;
		$datatype[1]=$viewer;
		$frameout="WHERE maintype='$storetype'";
		// default content message title
		$mainmsgout=''.strtoupper($type).' Page Manager';
		$mainmsgintroout='Edit '.strtoupper($type).' Page Intro/Create New Content';
		$mainmsgcontentout='Edit '.strtoupper($type).' Page Contents';
		// hidden content map
		$showhidetitle="";
		$showhidesubtitle="";
		$showhideimage="";
		$showhideintro="";
		$showhidecontent="";
		$showhidestatus="";
		$formtypeout="submitcontent";
		$formmonitor="";
		$wholescript="";
		// init variable for holding datamap value froom
		// contentgroupdata array index 'datamap'
		// the final value of this variable is placed in the pagination
		// section for the output table.
		$mapelement="";
		// default values accrueable to formmonitor
		// hidden element variables are 1,2,3,4,5
		// where 1 means monitor the title
		// where 5 means monitor the subtitle
		// where 2 means monitor the image
		// where 3 means monitor the introparagraph(if present);
		// where 4 means monitor the content(if present);
		// for breaking into or removing subtype
		$subtypeout='<input type="hidden" name="subtype" value="content"/>';
		$subtypestyleout='style="display:none"';

		// new form content header and output displays
		$contenttextheaderout="New Content Entry";
		$contenttexttitleout="Content Title";
		$contenttextsubtitleout="Content Sub-Title";
		$contenttextimageout="Content Photo";
		$contenttextintroout="Content Intro";
		$contenttextcontentout="Content Post";
		$contenttextstatusout="Disable/Enable this";

		$mceidtrip="";
		$mceidtriptwo="";

		// edit form element attribute content
		$contentattrtitleout="";
		$contentattrsubtitleout="";
		$contentattrimageout="";
		$contentattrintroout="";
		$contentattrcontentout="";
		$contentattrstatusout="";

		// Edit form Placeholders  and output displays
		$contentplaceholdertitleout="The title of the entry";
		$contentplaceholdersubtitleout="The Sub-title of the entry";
		$contentplaceholderimageout="The Image for the entry";
		$contentplaceholderintroout="The introduction for the entry";
		$contentplaceholdercontentout="The content for the entry";
		$contentplaceholderstatusout="--Choose Status--";

		// for intro forms only
		$itsubmitbtnname=isset($itsubmitbtnname)?$itsubmitbtnname:"submitintro";
		$itsubmitbtntype=isset($itsubmitbtntype)?$itsubmitbtntype:"button";
		$itsubmitbtnattr=isset($itsubmitbtnattr)?$itsubmitbtnattr:"";
		$itformname=isset($itformname)?$itformname:"introform";

		$extraformdata=$rurladmin!==""?'<input type="hidden" name="rurladmin" value="'.$rurladmin.'">':"";
		// extraformdata positioning variables, each variable puts the extra data at 
		// the appropriate point in the form
		$extraformtitle="";
		$extraformsubtitle="";
		$extraformimage="";
		$extraformintro="";
		$extraformcontent="";
		$extraformstatus="";
		$extraformscript="";
		$heading_extras="";
		$curstamp = date("Y-m-d H:i:s"); // current time 
		$curstamp=md5(strtotime($curstamp));
		$estamp="_".$curstamp;
		$usesession="";
		// check if the contentgroupdata has its own information 
		// and if it does, do not use the existing session data
		if(count($contentgroupdata)>0&&(
			isset($contentgroupdata['curstamp'])
			||isset($contentgroupdata['evaldata'])
			||isset($contentgroupdata['contentlabels'])
			||isset($contentgroupdata['contentplaceholder'])
			||isset($contentgroupdata['extraformdata'])
			||isset($contentgroupdata['datamap'])
			||isset($contentgroupdata['editmap'])
			||isset($contentgroupdata['validationmap'])
			||isset($contentgroupdata['adminheadings'])
			||isset($contentgroupdata['adminheadingsdata'])
			)){
			$usesession="false";
		}
		$adminoutputdefaults="";
		// var_dump($contentgroupdata);
		// prep the unique session variable for carrying content data across
		// other pages, and hijack the contentgroupdata array if conditions are met
		if(isset($_SESSION['gd_contentgroup'][''.$outputtype.''])
			&&isset($_SESSION['gd_contentgroup'][''.$outputtype.'']['curstamp'])
			&&$_SESSION['gd_contentgroup'][''.$outputtype.'']['curstamp']!==""
			&&$usesession!=="false"&&count($contentgroupdata)<1){
			// use the session variable if explicitly told to via the
			// datamarker parameter
			if($datamarker!==""){
				// allow the current marker trickle to the singlegeneralinfosection
				$datatype[4]=$viewer;

			}
			$curstamp=$_SESSION['gd_contentgroup'][''.$outputtype.'']['curstamp'];
			$contentgroupdata=$_SESSION['gd_contentgroup'][''.$outputtype.''];
			$estamp="_".$curstamp;
			// echo "through here";
		}else{
			// create a new session variable
			unset($_SESSION['gd_contentgroup'][''.$outputtype.'']);
			$_SESSION['gd_contentgroup'][''.$outputtype.'']="";
		}
		$dummies="";
		
		if(count($contentgroupdata)>0){
			$contentgroupdata['curstamp']=$curstamp;
			$contentgroupdata['curmaintype']=$storetype;
			$_SESSION['gd_contentgroup'][''.$outputtype.'']=$contentgroupdata;
			if(isset($contentgroupdata['contentmap'])){
				// hidden content map for the default set of elements available to the
				// generaldatamodule

			}
			if(isset($contentgroupdata['contentgdset'])){
				// the labels for the current set of content are passed in here
				$mainmsgout=isset($contentgroupdata['contentgdset']['mainmsgout'])?$contentgroupdata['contentgdset']['mainmsgout']:$mainmsgout;
				$mainmsgintroout=isset($contentgroupdata['contentgdset']['mainmsgintroout'])?$contentgroupdata['contentgdset']['mainmsgintroout']:$mainmsgintroout;
				$mainmsgcontentout=isset($contentgroupdata['contentgdset']['mainmsgcontentout'])?$contentgroupdata['contentgdset']['mainmsgcontentout']:$mainmsgcontentout;
				// hidden content map
				$showhidetitle=isset($contentgroupdata['contentgdset']['showhidetitle'])?$contentgroupdata['contentgdset']['showhidetitle']:$showhidetitle;
				$showhidesubtitle=isset($contentgroupdata['contentgdset']['showhidesubtitle'])?$contentgroupdata['contentgdset']['showhidesubtitle']:$showhidesubtitle;
				$showhideimage=isset($contentgroupdata['contentgdset']['showhideimage'])?$contentgroupdata['contentgdset']['showhideimage']:$showhideimage;
				$showhideintro=isset($contentgroupdata['contentgdset']['showhideintro'])?$contentgroupdata['contentgdset']['showhideintro']:$showhideintro;
				$showhidecontent=isset($contentgroupdata['contentgdset']['showhidecontent'])?$contentgroupdata['contentgdset']['showhidecontent']:$showhidecontent;
				$showhidestatus=isset($contentgroupdata['contentgdset']['showhidestatus'])?$contentgroupdata['contentgdset']['showhidestatus']:$showhidestatus;


				$formtypeout=isset($contentgroupdata['contentgdset']['formtypeout'])?$contentgroupdata['contentgdset']['formtypeout']:$formtypeout;
				$formmonitor=isset($contentgroupdata['contentgdset']['formmonitor'])?$contentgroupdata['contentgdset']['formmonitor']:$formmonitor;
				$wholescript=isset($contentgroupdata['contentgdset']['wholescript'])?$contentgroupdata['contentgdset']['wholescript']:$wholescript;

				// for breaking into or removing subtype
				$subtypeout=isset($contentgroupdata['contentgdset']['subtypeout'])?$contentgroupdata['contentgdset']['subtypeout']:$subtypeout;
				$subtypestyleout=isset($contentgroupdata['contentgdset']['subtypestyleout'])?$contentgroupdata['contentgdset']['subtypestyleout']:$subtypestyleout;
				// new form content header and output displays
				$contenttextheaderout=isset($contentgroupdata['contentgdset']['contenttextheaderout'])?$contentgroupdata['contentgdset']['contenttextheaderout']:$contenttextheaderout;
				$contenttexttitleout=isset($contentgroupdata['contentgdset']['contenttexttitleout'])?$contentgroupdata['contentgdset']['contenttexttitleout']:$contenttexttitleout;
				$contenttextsubtitleout=isset($contentgroupdata['contentgdset']['contenttextsubtitleout'])?$contentgroupdata['contentgdset']['contenttextsubtitleout']:$contenttextsubtitleout;
				$contenttextimageout=isset($contentgroupdata['contentgdset']['contenttextimageout'])?$contentgroupdata['contentgdset']['contenttextimageout']:$contenttextimageout;
				$contenttextintroout=isset($contentgroupdata['contentgdset']['contenttextintroout'])?$contentgroupdata['contentgdset']['contenttextintroout']:$contenttextintroout;				
				$contenttextcontentout=isset($contentgroupdata['contentgdset']['contenttextcontentout'])?$contentgroupdata['contentgdset']['contenttextcontentout']:$contenttextcontentout;
				$contenttextstatusout=isset($contentgroupdata['contentgdset']['contenttextstatusout'])?$contentgroupdata['contentgdset']['contenttextstatusout']:$contenttextstatusout;

				// Edit form Placeholders  and output displays
				$contentplaceholdertitleout=isset($contentgroupdata['contentgdset']['contentplaceholdertitleout'])?$contentgroupdata['contentgdset']['contentplaceholdertitleout']:$contentplaceholdertitleout;
				$contentplaceholdersubtitleout=isset($contentgroupdata['contentgdset']['contentplaceholdersubtitleout'])?$contentgroupdata['contentgdset']['contentplaceholdersubtitleout']:$contentplaceholdersubtitleout;
				$contentplaceholderimageout=isset($contentgroupdata['contentgdset']['contentplaceholderimageout'])?$contentgroupdata['contentgdset']['contentplaceholderimageout']:$contentplaceholderimageout;
				$contentplaceholderintroout=isset($contentgroupdata['contentgdset']['contentplaceholderintroout'])?$contentgroupdata['contentgdset']['contentplaceholderintroout']:$contentplaceholderintroout;
				$contentplaceholdercontentout=isset($contentgroupdata['contentgdset']['contentplaceholdercontentout'])?$contentgroupdata['contentgdset']['contentplaceholdercontentout']:$contentplaceholdercontentout;
				$contentplaceholderstatusout=isset($contentgroupdata['contentgdset']['contentplaceholderstatusout'])?$contentgroupdata['contentgdset']['contentplaceholderstatusout']:$contentplaceholderstatusout;

				$mceidtrip=isset($contentgroupdata['contentgdset']['mceidtrip'])?$contentgroupdata['contentgdset']['mceidtrip']:$mceidtrip;
				$mceidtriptwo=isset($contentgroupdata['contentgdset']['mceidtriptwo'])?$contentgroupdata['contentgdset']['mceidtriptwo']:$mceidtriptwo;
				
				$contentattrtitleout=isset($contentgroupdata['contentgdset']['contentattrtitleout'])?$contentgroupdata['contentgdset']['contentattrtitleout']:$contentattrtitleout;
				$contentattrsubtitleout=isset($contentgroupdata['contentgdset']['contentattrsubtitleout'])?$contentgroupdata['contentgdset']['contentattrsubtitleout']:$contentattrsubtitleout;
				$contentattrimageout=isset($contentgroupdata['contentgdset']['contentattrimageout'])?$contentgroupdata['contentgdset']['contentattrimageout']:$contentattrimageout;
				$contentattrintroout=isset($contentgroupdata['contentgdset']['contentattrintroout'])?$contentgroupdata['contentgdset']['contentattrintroout']:$contentattrintroout;
				$contentattrcontentout=isset($contentgroupdata['contentgdset']['contentattrcontentout'])?$contentgroupdata['contentgdset']['contentattrcontentout']:$contentattrcontentout;
				$contentattrstatusout=isset($contentgroupdata['contentgdset']['contentattrstatusout'])?$contentgroupdata['contentgdset']['contentattrstatusout']:$contentattrstatusout;

				$itsubmitbtnname=isset($contentgroupdata['contentgdset']['itsubmitbtnname'])?$contentgroupdata['contentgdset']['itsubmitbtnname']:$itsubmitbtnname;
				$itsubmitbtntype=isset($contentgroupdata['contentgdset']['itsubmitbtntype'])?$contentgroupdata['contentgdset']['itsubmitbtntype']:$itsubmitbtntype;
				$itsubmitbtnattr=isset($contentgroupdata['contentgdset']['itsubmitbtnattr'])?$contentgroupdata['contentgdset']['itsubmitbtnattr']:$itsubmitbtnattr;
				$itformname=isset($contentgroupdata['contentgdset']['itformname'])?$contentgroupdata['contentgdset']['itformname']:$itformname;

				$extraformdata.=isset($contentgroupdata['contentgdset']['extraformdata'])?$contentgroupdata['contentgdset']['extraformdata']:$extraformdata;
				// extraformdata positioning variables, each variable puts the extra data at 
				// the appropriate point in the form
				$extraformtitle=isset($contentgroupdata['contentgdset']['extraformtitle'])?$contentgroupdata['contentgdset']['extraformtitle']:$extraformtitle;
				$extraformsubtitle=isset($contentgroupdata['contentgdset']['extraformsubtitle'])?$contentgroupdata['contentgdset']['extraformsubtitle']:$extraformsubtitle;
				$extraformimage=isset($contentgroupdata['contentgdset']['extraformimage'])?$contentgroupdata['contentgdset']['extraformimage']:$extraformimage;
				$extraformintro=isset($contentgroupdata['contentgdset']['extraformintro'])?$contentgroupdata['contentgdset']['extraformintro']:$extraformintro;
				$extraformcontent=isset($contentgroupdata['contentgdset']['extraformcontent'])?$contentgroupdata['contentgdset']['extraformcontent']:$extraformcontent;
				$extraformstatus=isset($contentgroupdata['contentgdset']['extraformstatus'])?$contentgroupdata['contentgdset']['extraformstatus']:$extraformstatus;
				$extraformscript=isset($contentgroupdata['contentgdset']['extraformscript'])?$contentgroupdata['contentgdset']['extraformscript']:$extraformscript;
				$heading_extras=isset($contentgroupdata['contentgdset']['heading_extras'])?$contentgroupdata['contentgdset']['heading_extras']:$heading_extras;
			}

			if(isset($contentgroupdata['contentplaceholders'])){
				// placeholders for the default content set of the general datamodule
			}
			if(isset($contentgroupdata['contenterrmsgs'])){
				// error messages for the default content set of the general datamodule
			}
			if(isset($contentgroupdata['extraformdata'])){
				// extra element data and their content position in the generaldatamodule
				// form
			}
			if(isset($contentgroupdata['extraformdata'])){
				// extra element data and their content position in the generaldatamodule
				// form
			}
			if(isset($contentgroupdata['datamap'])){
				// array map element map data for handling custom gd request
				// echo "maptrick<br>";
				$curdatamap=JSONtoPHP($contentgroupdata['datamap']);
				if($curdatamap['error']=="No error"){
					$mapelement='
						<input type="hidden" name="datamap" value=\''.$contentgroupdata['datamap'].'\' data-ipp="15" data-page="1" value="1"/>
					';
				}
			}
			if(isset($contentgroupdata['editmap'])){
				// map data for the edit section of the generaldatamodule
			}
			if(isset($contentgroupdata['validationmap'])){
				// a set of script data validation for the current form entry
				// userful for handling extraform data validation

			}
			if(isset($contentgroupdata['evaldata'])){
				// echo "present<br>";.
				// for running php code directly when necessary and ONLY when
				// necessary, to be used with caution
				// its associative array values are as follows for the single and general
				// data sections
				// the variables below act as markers telling the availablility
				// of any eval-able array values at certain positions in the code
				$doiniteval="";
				$dopreloopeval="";
				$dopostloopeval="";
				$doloopintroeval="";
				$doloopsingleeval="";
				$dofullloopeval="";
				$doemptyeval="";
				$dooutputeval="";
				$dopostoutputeval="";
				$doinitevaldata="";
				$dopreloopevaldata="";
				$dopostloopevaldata="";
				$doloopintroevaldata="";
				$doloopsingleevaldata="";
				$dofullloopevaldata="";
				$doemptyevaldata="";
				$dooutputevaldata="";
				$dopostoutputevaldata="";
				// initialisation section for variables to be used before the $type
				// control block
				// ['general']['thenameofthesubarray'] 
				// ['general']['singleloop'] 
				// ['general']['loop'] 
				// ['general']['afterloop'] 
				// initialisation section for variables to be used in the single loop
				// section after intended query is run
				// ['single']['thenameofthesubarray']
					// var_dump($contentgroupdata);

				if (isset($contentgroupdata['evaldata']['general']['initeval'])
					&&$contentgroupdata['evaldata']['general']['initeval']!=="") {
					# code...
					$doiniteval="true";
					$doinitevaldata=$contentgroupdata['evaldata']['general']['initeval'];
					// echo $doinitevaldata." - init evaldata<br>";
				}
				if (isset($contentgroupdata['evaldata']['general']['preloopeval'])
					&&$contentgroupdata['evaldata']['general']['preloopeval']!=="") {
					# code...
					$dopreloopeval="true";
					$dopreloopevaldata=$contentgroupdata['evaldata']['general']['preloopeval'];
				}
				if (isset($contentgroupdata['evaldata']['general']['postpreloopeval'])
					&&$contentgroupdata['evaldata']['general']['postpreloopeval']!=="") {
					# code...
					$dopostpreloopeval="true";
					$dopostpreloopevaldata=$contentgroupdata['evaldata']['general']['postpreloopeval'];
				}
				if (isset($contentgroupdata['evaldata']['general']['loopintroeval'])
					&&$contentgroupdata['evaldata']['general']['loopintroeval']!=="") {
					# code...
					$doloopintroeval="true";
					$doloopintroevaldata=$contentgroupdata['evaldata']['general']['loopintroeval'];
				}
				if (isset($contentgroupdata['evaldata']['general']['loopsingleeval'])
					&&$contentgroupdata['evaldata']['general']['loopsingleeval']!=="") {
					# code...
					$doloopsingleeval="true";
					$doloopsingleevaldata=$contentgroupdata['evaldata']['general']['loopsingleeval'];
				}
				if (isset($contentgroupdata['evaldata']['general']['fullloopeval'])
					&&$contentgroupdata['evaldata']['general']['fullloopeval']!=="") {
					# code...
					$dofullloopeval="true";
					$dofullloopevaldata=$contentgroupdata['evaldata']['general']['fullloopeval'];
				}
				if (isset($contentgroupdata['evaldata']['general']['emptyeval'])
					&&$contentgroupdata['evaldata']['general']['emptyeval']!=="") {
					# code...
					$doemptyeval="true";
					$doemptyevaldata=$contentgroupdata['evaldata']['general']['emptyeval'];
				}
				if (isset($contentgroupdata['evaldata']['general']['outputeval'])
					&&$contentgroupdata['evaldata']['general']['outputeval']!=="") {
					# code...
					$dooutputeval="true";
					$dooutputevaldata=$contentgroupdata['evaldata']['general']['outputeval'];
				}
				if (isset($contentgroupdata['evaldata']['general']['postoutputeval'])
					&&$contentgroupdata['evaldata']['general']['postoutputeval']!=="") {
					# code...
					$dopostoutputeval="true";
					$dopostoutputevaldata=$contentgroupdata['evaldata']['general']['postoutputeval'];
				}
			
			}
			if(isset($contentgroupdata['adminheadings'])){
				// for carrying default content in the admin edit interfaces
				$adminrowdefaults=isset($contentgroupdata['adminheadings']['rowdefaults'])
				&&$contentgroupdata['adminheadings']['rowdefaults']!==""?$contentgroupdata['adminheadings']['rowdefaults']:"";
				
				$adminoutputdefaults=isset($contentgroupdata['adminheadings']['output'])
				&&$contentgroupdata['adminheadings']['output']!==""?$contentgroupdata['adminheadings']['output']:"";
				// do a loop that generates the expected number of td elements for empty instances
				if(is_numeric($adminoutputdefaults)&&$adminoutputdefaults>0){
					$ad="";
					for($i=1;$i<=$adminoutputdefaults;$i++){
						$ad.=$i==1?'<td>No entries Yet</td>':'<td></td>';
					}
					$adminoutputdefaults=$ad;
				}else if(is_numeric($adminoutputdefaults)&&$adminoutputdefaults==0){
					$adminoutputdefaults="";
				}
			}
			// echo $adminoutputdefaults." - output defaults";
			if(isset($contentgroup[8])||isset($contentgroup['adminheadingsdata'])){
				// used for passing in content for the admin table heading and individual
				// content heading
				$adminheadings=isset($contentgroup['adminheadingsdata']['singlecontent'])&&
				$contentgroup['adminheadingsdata']['singlecontent']!==""?$contentgroup['adminheadingsdata']['singlecontent']:"";
				$dummiedata=explode("<td>", $adminheadingsdata);
				$dummies="";
				if(count($dummiedata)>0){
					$heading_extras=$adminheadings;
					$dummies=count($dummiedata);
				}
			}
		}

		// do init eval here
		if(isset($doiniteval)&&$doiniteval=="true"){
			eval($doinitevaldata);
		}

		// test to see if current section is a type of welcomemsg
		$tdata=strpos("$storetype", "welcomemsg");
		$tdata2=strpos("$storetype", "datasection");
		if($type=="about"){
			$frameout="WHERE maintype='$storetype'";
			$showhidetitle="display:none;";
			$showhidesubtitle="display:none;";
			$showhideimage="display:none;";
		}else if($type=="homewelcomemsg"){
			$frameout="WHERE maintype='$storetype'";
			$mainmsgout="Create/Update Home Page Welcome Msg";
			$mainmsgintroout="Create/Update Message";
			$mainmsgcontentout="Create/Update Message";
			// $showhidetitle="display:none;";
			// $showhidesubtitle="display:none;";
			$showhideimage="display:none;";
			// $showhideintro="display:none;";
			// $showhidecontent="display:none;";
			$contenttexttitleout="The Section Title";
			$contenttextsubtitleout="The Section Sub-title(optional)";
			$contenttextcontentout="Place Message Here";
		}else if($type=="businesshours"){
			$frameout="WHERE maintype='$storetype'";
			$mainmsgout="Business Hours";
			$mainmsgintroout="Create/Update Business Hours Info";
			// $mainmsgcontentout="Create/Update Message";
			$showhidetitle="display:none;";
			$showhidesubtitle="display:none;";
			$showhideimage="display:none;";
			$showhideintro="display:none;";
			// $showhidecontent="display:none;";
		}else if($type=="defaultsocial"){
			$frameout="WHERE maintype='$storetype'";
			$subtypestyleout="";
			$mainmsgout="Create or Update website default Social Data";
			$mainmsgintroout="Create";
			$mainmsgcontentout="Update";
			$subtypeout="";
			// $showhidetitle="display:none;";
			$showhidesubtitle="display:none;";
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
                              <option value="defaultfacebookpage">Facebook Page <i class="fa fa-facebook"></i></option>
                              <option value="defaulttwitter">Twitter <i class="fa fa-twitter"></i></option>
                              <option value="defaultlinkedin">LinkedIn <i class="fa fa-linkedin"></i></option>
                              <option value="defaultgoogleplus">Google+ <i class="fa fa-google"></i></option>
                              <option value="defaultpinterest">Pinterest <i class="fa fa-pinterest"></i></option>
                              <option value="defaultinstagram">Instagram <i class="fa fa-instagram"></i></option>
                              <option value="defaultskype">Skype <i class="fa fa-skype"></i></option>
                            </select>
                        </div><!-- /.input group -->
                     </div>
                </div>
                ';
            $formtypeout="submitcustom";
			$formmonitor='<input type="hidden" name="monitorcustom" value="nomonitor">';
		}else if($type=="defaultfacebookpage"||$type=="defaultfacebook"
			||$type=="defaulttwitter"||$type=="defaultlinkedin"
			||$type=="defaultgoogleplus"||$type=="defaultpinterest"||$type=="defaultskype"
			||$type=="defaultphonenumbers"||$type=="defaultemailaddress"||$type=="defaultmainaddress"){
			$frameout="WHERE subtype='$type'";
		}elseif($type=="defaultinfo"){
			$frameout="WHERE maintype='$type'";
			$subtypestyleout="";
			$mainmsgout="Create or Update website default data";
			$mainmsgintroout="Create";
			$mainmsgcontentout="Update";
			// $showhidetitle="display:none;";
			$showhidesubtitle="display:none;";
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
		}else if($tdata!==false||$tdata===true){
			$exp1=explode("welcomemsg", $storetype);
			$pagename=isset($exp1[0])&&$exp1[0]!==""?ucfirst($exp1[0])." Page":"Page";

			// test the obtained info for an underscore extension
			if($exp1[0]!==""&&strpos($exp1[0], "_")===true){
				$exp2=explode("_", $exp1[0]);
				$pagename=isset($exp2[0])&&$exp2[0]!==""?ucfirst($exp2[0])." Page":"Page";
			}
			$frameout="WHERE maintype='$storetype'";
			$mainmsgout="Create/Update $pagename Welcome Msg";
			$mainmsgintroout="Create/Update Message";
			$mainmsgcontentout="Create/Update Message";
			// $showhidetitle="display:none;";
			// $showhidesubtitle="display:none;";
			$showhideimage="display:none;";
			// $showhideintro="display:none;";
			// $showhidecontent="display:none;";
			$contenttexttitleout="The Section Title";
			$contenttextsubtitleout="The Section Sub-title(optional)";
			$contenttextcontentout="Place Message Here";
		}else if($tdata2!==false||$tdata2===true){
			$exp1=explode("datasection", $storetype);
			$pagename=isset($exp1[0])&&$exp1[0]!==""?ucfirst($exp1[0])." Section":"Section";

			// test the obtained info for an underscore extension
			if($exp1[0]!==""&&strpos($exp1[0], "_")===true){
				$exp2=explode("_", $exp1[0]);
				$pagename=isset($exp2[0])&&$exp2[0]!==""?ucfirst($exp2[0])." Section":"Section";
			}
			$frameout="WHERE maintype='$storetype'";
			$mainmsgout="Create/Update $pagename Section Data";
			$mainmsgintroout="Create/Update Message";
			$mainmsgcontentout="Create/Update Message";
			// $showhidetitle="display:none;";
			// $showhidesubtitle="display:none;";
			$showhideimage="display:none;";
			// $showhideintro="display:none;";
			// $showhidecontent="display:none;";
			$contenttexttitleout="The Section Title";
			$contenttextsubtitleout="The Section Sub-title(optional)";
			$contenttextcontentout="Place Message Here";
		}else{
			$frameout=$frameout==""?"WHERE maintype='$type'":$frameout;
			// $showhidetitle="";
			// $showhideimage="";

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
			$query="SELECT * FROM generalinfo $frameout AND status='active' $ordercontent";		
			$rowmonitor['chiefquery']="SELECT * FROM generalinfo $frameout AND status='active' $ordercontent";
		}
		// echo $query;
		/*if($typeid=="active"){
			$query="SELECT * FROM faq WHERE status='active'";
		}*/
		$selection="";
		// echo $query."<br>";
		$run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
		$numrows=mysql_num_rows($run);
		$adminoutput=isset($adminoutputdefaults)&&$adminoutputdefaults!==""?$adminoutputdefaults:"<td>No entries</td><td></td><td $subtypestyleout></td><td></td>$dummies<td></td><td></td>";
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
		$contentsubtitle="";
		$coverimgpath="";
		$introoutputviewer="No introduction has been made for this section";
		$introid=0;
		$contentid=0;
		$typeoutcontroller="";
		$coverid=0;
		$counttocont=0;
		$countcontent=0;
		$selection2="";
		// do preloop eval
		if(isset($dopreloopeval)&&$dopreloopeval=="true"){
			eval($dopreloopevaldata);
		}
		$resultdatasetarr=array();
		if($numrows>0){
			$vieweroutput="";
			$adminoutput="";
			$introdata="";
			// do postpreloop eval here
			if(isset($dopostpreloopeval)&&$dopostpreloopeval=="true"){
				eval($dopostpreloopevaldata);
			}
			while($row=mysql_fetch_assoc($run)){
				$prodnservcounter++;
				if($counttocont>0){
					$curactive="";
				}
				if($row['subtype']=="intro"){
					$resultdatasetarr[]=$row;
					$introid=$row['id'];
					$introdata=$row['intro'];
					$maintitle=$row['title']!==""?'<div class="largeheader">'.$row['title'].'<br><img src="'.$host_addr.'./images/headingborder.png" style="width:50%;"></div>':'<div class="largeheader">Max-Migold.<br><img src="'.$host_addr.'./images/headingborder.png" style="width:50%;"></div>';
					$introoutputviewer=$row['status']=='active'?$introdata:"No introduction has been made for this section";
					$contenttitle=$row['title'];
					// do loop intro eval here
					if(isset($doloopintroeval)&&$doloopintroeval=="true"){
						eval($doloopintroevaldata);
					}
				}else{
					$outvar=getSingleGeneralInfo($row['id'],$datatype,$contentgroupdata,$curstamp);
					$resultdatasetarr[]=$outvar;

					// these are for single page content only
					if($counttocont<1){
						$contentid=$row['id'];
						$contenttitle=$row['title'];
						$contentsubtitle=$row['subtitle'];
						$contentintro=$row['intro'];
						$contentdata=$row['content'];
						$coverid=$outvar['coverid'];
						$coverimgpath=$outvar['coverpath'];
						$row['contentid']=$contentid;
						$row['contenttitle']=$contenttitle;
						$row['contentintro']=$contentintro;
						$row['coverimgpath']=$coverimgpath;

						// do loop single eval here
						if(isset($doloopsingleeval)&&$doloopsingleeval=="true"){
							eval($doloopsingleevaldata);
						}
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
					// do full loop eval here
					if(isset($dofullloopeval)&&$dofullloopeval=="true"){
						eval($dofullloopevaldata);
					}

					$counttocont++;

				}

			}
		}else{
			// do empty eval
			if(isset($doemptyeval)&&$doemptyeval=="true"){
				eval($doemptyevaldata);
			}
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
		$row['resultdataset']=$resultdatasetarr;
		$row['contentdata']=$contentdata;
		$row['contentrows']=$counttocont;				
		$row['contentid']=$contentid;
		$row['contenttitle']=$contenttitle;
		$row['contentsubtitle']=$contentsubtitle;
		$row['contentintro']=$contentintro;
		$row['contentsdata']=$contentdata;
		$row['coverimgpath']=$contentimageout;
		$row['coverimgpathtwo']=$coverimgpath;
		
		// these output forms are used for displaying 
		// new or previously inputted unique data from the general info table
		// for creation or update
		// they can only be used for content that only occurs once
		// such as the introduction for a page, or particular text sections of a page 
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
	                <div class="col-md-12" style="'.$showhideintro.'">
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
				        filemanager_title:"Content Filemanager" ,
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
					'.$extraformscript.'
				</script>
	        </div>
		';
		
		$row['introoutputtwo']='
			<div class="row">
				<form name="'.$itformname.'" method="POST" enctype="multipart/form-data" action="../snippets/edit.php">
	        		<input type="hidden" name="entryvariant" value="contententryupdate"/>
	        		<input type="hidden" name="maintype" value="'.$type.'"/>
	        		<input type="hidden" name="subtype" value="content"/>
	        		<input type="hidden" name="entryid" value="'.$contentid.'"/>
	        		'.$formmonitor.'
	                <div class="col-md-12" style="'.$showhideimage.'">
	                	<input type="hidden" name="coverid" value="'.$coverid.'">
	                	'.$contentimageout.'
                		<div class="form-group">
		                  <label>'.$contenttextimageout.'</label>
		                  <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-bars"></i>
		                      </div>
		                      <input type="file" class="form-control" name="contentpic" '.$contentattrimageout.' value="'.str_replace('"', "'", $contenttitle).'" Placeholder="The main heading for this page"/>
		                   </div><!-- /.input group -->
		                </div>
		            </div>
		            '.$extraformimage.'
	                <div class="col-md-12" style="'.$showhidetitle.'">
                		<div class="form-group">
		                  <label>'.$contenttexttitleout.'</label>
		                  <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-bars"></i>
		                      </div>
		                      <input type="text" class="form-control" name="contenttitle" '.$contentattrtitleout.' value="'.str_replace('"', "'", $contenttitle).'" Placeholder="'.$contentplaceholdertitleout.'"/>
		                   </div><!-- /.input group -->
		                </div>
		            </div>
		            '.$extraformtitle.'
		            <div class="col-md-12" style="'.$showhidesubtitle.'">
                		<div class="form-group">
		                  <label>'.$contenttextsubtitleout.'</label>
		                  <div class="input-group">
		                      <div class="input-group-addon">
		                        <i class="fa fa-bars"></i>
		                      </div>
		                      <input type="text" class="form-control" name="contentsubtitle" '.$contentattrsubtitleout.' value="'.str_replace('"', "'", $contentsubtitle).'" Placeholder="'.$contentplaceholdersubtitleout.'"/>
		                   </div><!-- /.input group -->
		                </div>
		            </div>
		            '.$extraformsubtitle.'
	                <div class="col-md-12" style="'.$showhideintro.'">
	                    <label>'.$contenttextcontentout.'</label>
			            <textarea class="form-control" rows="3" name="intro" id="postersmallthree'.$mceidtriptwo.'" '.$contentattrintroout.' placeholder="Provide information concerning what this page is about">'.$contentdata.'</textarea>
	                </div>
		            '.$extraformintro.'
	                <div class="col-md-12">
	        			<div class="box-footer">
		                    <input type="'.$itsubmitbtntype.'" class="btn btn-danger" '.$itsubmitbtnattr.' name="'.$itsubmitbtnname.'" value="Create/Update"/>
		                </div>
	            	</div>
	            </form>
	            <script>
					'.$extraformscript.'
				</script>
	        </div>
		';
		$row['introoutputviewer']=$introoutputviewer;
		// strictly for new generalinfo table entries, teh form here
		// doesnt carry any values with it
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
			                      <input type="text" class="form-control" name="contenttitle" '.$contentattrtitleout.' Placeholder="'.$contentplaceholdertitleout.'"/>
			                   </div><!-- /.input group -->
			                </div>
			            </div>
                    	'.$extraformsubtitle.'
			            <div class="col-md-6" style="'.$showhidesubtitle.'">
                    		<div class="form-group">
			                  <label>'.$contenttextsubtitleout.'</label>
			                  <div class="input-group">
			                      <div class="input-group-addon">
			                        <i class="fa fa-bars"></i>
			                      </div>
			                      <input type="text" class="form-control" name="contentsubtitle" '.$contentattrsubtitleout.' Placeholder="'.$contentplaceholdersubtitleout.'"/>
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
			                      <input type="file" class="form-control" name="contentpic" '.$contentattrimageout.' Placeholder="'.$contentplaceholderimageout.'"/>
			                   </div><!-- /.input group -->
			                </div>
			            </div>
                    	'.$extraformintro.'
                    	<div class="col-md-12" style="'.$showhideintro.'">
                    		<div class="form-group">
			                  <label>'.$contenttextintroout.'</label>
			                  <textarea class="form-control" rows="3" name="contentintro" '.$contentattrintroout.' placeholder="'.$contentplaceholderintroout.'"></textarea>
			                </div>
			            </div>
			            '.$extraformcontent.'
                    	<div class="col-md-12" style="'.$showhidecontent.'">
                    		<div class="form-group">
			                  <label>'.$contenttextcontentout.'</label>
			                  <textarea class="form-control" rows="3" name="contentpost" '.$contentattrcontentout.' id="postersmallfour'.$mceidtrip.'" placeholder="'.$contentplaceholdercontentout.'"></textarea>
			                </div>
			            </div>
			            '.$extraformdata.'
                	</div>
                	<div class="col-md-12">
            			<div class="box-footer">
		                    <input type="button" class="btn btn-danger" name="'.$formtypeout.'" data-formdata="contentform" value="Create/Update"/>
		                </div>
	            	</div>
                </form>
				'.$extraformscript.'
	        </div>	
		';



		// output eval
		if(isset($dooutputeval)&&$dooutputeval=="true"){
			eval($dooutputevaldata);
		}
		$adminoutput=$adminoutput==""?"<td>No Content entries for this</td><td></td><td></td><td></td><td></td>":$adminoutput;
		$outs=paginatejavascript($rowmonitor['chiefquery']);
		$row['chiefquery']=$rowmonitor['chiefquery'];
		$row['num_pages']=$outs['num_pages'];
		// the default set of headers for the administrators edit view
		$adminrowdefaults=isset($adminrowdefaults)&&$adminrowdefaults!==""?$adminrowdefaults:'<tr><th>CoverImage</th><th>maintype</th><th '.$subtypestyleout.'>subtype</th><th>Title</th>'.$heading_extras.'<th>Status</th><th>View/Edit</th></tr>';
		$top='<table id="resultcontenttable" cellspacing="0">
					<thead>'.$adminrowdefaults.'</thead>
					<tbody>';
		$bottom='	</tbody>
				</table>';
		// test the hashquerysystem
		// use the 'curqueries' session index to secure this query
		$rmd5=md5($rowmonitor['chiefquery']);
		$_SESSION['cq']["$rmd5"]=$rowmonitor['chiefquery'];
		$paginatetop='
		<div id="paginationhold">
			<div class="meneame">
				<input type="hidden" name="curquery" value="'.$rmd5.'"/>
				<input type="hidden" name="curstamp" value="'.$curstamp.'"/>
				<input type="hidden" name="outputtype" value="generalinfo-'.$outputtype.'"/>
				<input type="hidden" name="currentview" data-ipp="15" data-page="1" value="1"/>
				'.$mapelement.'
				<div class="pagination" data-name="paginationpageshold">'.$outs['pageout'].'</div>
				<div class="pagination">
					  '.$outs['usercontrols'].'
				</div>
			</div>
		</div>
		<div id="paginateddatahold" data-name="contentholder">';

		$row['outputtype']="generalinfo-$outputtype";
		
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
					'.$mapelement.'
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
									        filemanager_title:"Admin Blog Content Filemanager" ,
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
			                <div id="NewPageManagerBlock" class="panel-collapse collapse">
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
								        filemanager_title:"Admin Blog Content Filemanager" ,
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
	                      <div id="EditBlock" class="panel-collapse collapse in">
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
									        filemanager_title:"Admin Blog Content Filemanager" ,
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
		// do post output eval
		if(isset($dopostoutputeval)&&$dopostoutputeval=="true"){
			eval($dopostoutputevaldata);
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
function getSingleGeneralInfoPlain($id,$maintype=""){
	include('globalsmodule.php');
	$row=array();
	if(is_numeric($id)){
		$query="SELECT * FROM generalinfo WHERE id='$id' order by id desc";
	}else{
		$query="SELECT * FROM generalinfo WHERE maintype='$id' order by id desc";
	}
    $run=mysql_query($query)or die(mysql_error()." Line ".__LINE__);
    $numrows=mysql_num_rows($run);
    $countit=0;
    $extradata="";
    $fniselection="";
    $row=mysql_fetch_assoc($run);
    if($numrows>0){
	    $id=$row['id'];
	    $maintype=$row['maintype'];
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
		$imgid=$coverdata['id'];
		$row['coverout']=$coverout;
		$coverpathout=$coverdata['location']!==""?''.$host_addr.''.$coverdata['location'].'':"";
		$coverphoto=isset($coverdata['thumbnail'])?$host_addr.$coverdata['thumbnail']:$host_addr.$coverdata['details'];

		// echo count($contentgroupdata).", the count out<br>";
		if($medianumrows<1){
			$coverphoto=$host_addr."images/default.gif";
			$coverout="No Image Set";
			$coverpathout=$coverphoto;
			$imgid=0;
		}
		$row['coverid']=$imgid;
		$row['coverpath']=$coverpathout;
	    $row['numrows']=$numrows;
    }else{
    	$row['id']="";
		$row['maintype']="";
		$row['subtype']="";
		$row['title']="";
		$row['subtitle']="";
		$row['intro']="";
		$row['content']="";
		$row['entrydate']="";
		$row['defaultmarker']="yes";
		$row['defaultcontent']="";
		$row['extradata']="";
		$row['formdata']="";
		$row['extraformdata']="";
		$row['formerrordata']="";
		$row['status']="";
		$row['numrows']=0;
    }
    return $row;
}

function getExtradataModuleTypes(){
	$row=array();
	// this array carries every maintype and subtype that uses the extradata system 
	/*$row['subtype'][]="collagebox";
	$row['subtype'][]="resourcecontent";*/
	// $row['maintype'][]="collagebox";
	$row['maintype'][]="hometopcollagebox";
	$row['maintype'][]="homebottomcollagebox";
	$row['maintype'][]="resourcearticles";
	$row['maintype'][]="resourcevideos";
	$row['maintype'][]="resourceseminars";
	$row['maintype'][]="resourcecasestudy";
	$row['maintype'][]="portfoliogallery";
	$row['maintype'][]="directorsection";
	$row['maintype'][]="trustees";
	$row['maintype'][]="chartersection";

	// point maintypes to respective subtypespro
	$row['maintype']['homebottomcollagebox']="collagebox";
	$row['maintype']['hometopcollagebox']="collagebox";

	$row['maintype']['resourcearticles']="resourcecontent";
	$row['maintype']['resourcevideos']="resourcecontent";
	$row['maintype']['resourceseminars']="resourcecontent";
	$row['maintype']['resourcecasestudy']="resourcecontent";
	
	$row['maintype']['directorsection']="aboutcontent";
	$row['maintype']['trustees']="aboutcontent";
	$row['maintype']['chartersection']="aboutcontent";

	$row['maintype']['portfoliogallery']="portfoliocontent";

	// preferably pulled from database

	return $row;
}
?>