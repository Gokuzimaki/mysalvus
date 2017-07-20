<?php 
	//  blogcommentparser.php
	// this file is responsible for parsing blog post comments output
	// based on normal i.e system defined post or utilised a predefined set of supported
	// plugins
	// the variable $curblogdata is expected to be available
	if(isset($curblogdata)){
		// first we check for the type of comment being used
		// current comment types include normal and disqus though disqus is still
		// being integrated
		// var_dump($curblogdata);
		// this variable is used to hold the final output for the comment data
		$blog_comment_data="";
		
		// $blog_comment_count=0;
		// handle comment output data for embed
		$blog_comment_data_output="";
		// handle comment count data
		
		if($curblogdata['commenttype']=="normal"){
			
			$blog_comment_count=& $curblogdata['viewercommentcount'];
			$blog_comment_data=& $curblogdata['viewercomments']; 

		}else if($curblogdata['commenttype']=="disqus"){
			// handle disqus related info
			// set the scripts data to be loaded
			// set embed code to be loaded
			$blog_comment_data_output='<div id="disqus_thread"></div>';
			$blog_comment_count='<span class="disqus-comment-count" data-disqus-url="'.$curblogdata['pagelink'].'"
			data-disqus-identifier="'.$curblogdata['id'].'_blog">';
			// include the necessary file 
			include($host_tpathplain."modules/disqus/embed.php");
			// append the count script 
			include($host_tpathplain."modules/disqus/scripts/include.php");


		}
		// setup the necessary data for the compulsory comment form elements
		$c_form_name="comment_form";
		$c_form_attr='onsubmit="return false;"';
		$c_form_url=$host_addr."snippets/basicsignup.php";
		// substr(string, start)
		$c_form_validate_number=substr(md5(date("Y-m-d H:i:s")),0,8);

		$c_form_fields='
			<input type="hidden" name="entryvariant" value="createblogcomment"/>
			<input type="hidden" name="blogentryid" 
			value="'.$curblogdata['id'].'"/>
			<input type="hidden" name="returl" 
			value="'.$curblogdata['pagelink'].'"/>';
		$c_form_security_field='<input type="hidden" 
		name="sectester" value="'.$c_form_validate_number.'"/>';
		$c_form_validate_fieldname="secnumber";
		$c_form_validate_fieldattr='data-cvalidate="true" 
		data-element-data="sectester-:-input"';

		// carries the submit attributes for the form incase the general data validation
		// module needs to be invoked 
		$c_form_submit_attr='data-formdata="'.$c_form_name.'" 
		onclick="submitCustom(\''.$c_form_name.'\',\'complete\')"';
		
		// for handling default scripts that assist the form comment fields
		// to provide rich content for commenting, the rich content is created using
		// tinyMCE
		$c_form_script='
		<script>
			$(document).ready(function(){
				// if($.fn.tinyMCE){

					var curmceminadminposter=[];
					curmceminadminposter[\'width\']="100%";
					curmceminadminposter[\'height\']="200px";
					curmceminadminposter[\'rfmanager\']="";
					curmceminadminposter[\'toolbar1\']="undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fontselect | fontsizeselect | styleselect ";
					curmceminadminposter[\'toolbar2\']=" ";
					callTinyMCEInit("textarea[id*=comment]",curmceminadminposter);
				// }
			})
		</script>
		';


		$c_form_email_attr='data-evalidate="true"';
		$c_form_comment_attr='data-mce="true"';

		// this handles the forms data map incase the general data module is to be used
		$c_form_extra_data='
			<input type="hidden" name="formdata" value="'.$c_form_name.'"/>
            <input type="hidden" name="extraformdata" value="fullname-:-input<|>
            email-:-input<|>
            secnumber-:-input<|>
            comment-:-textarea"/>
			<input type="hidden" name="errormap" value="fullname-:-
			Please fill the Fullname Field<|>
            email-:-Please provide a valid email address.<|>
            secnumber-:-Please provide a matching security number as shown within the 
            form<|>
            comment-:-Please give your comment"/>
		';

		// handles the form verification using botdetect captcha
		$_form_captcha="";
?>

<?php

	}
?>