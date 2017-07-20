<?php
	// This module is used by the blog system to control the  final single page render
	// of blog posts. It allows for inclusion of other render pages based on the 
	// blog type the post belongs to and has access to all the content of the blog
	// post as found in the getSingleBlogEntry function return data.
	// the variables directly available to this file are as follows:
	// int $pageid, is the id of the blog post as found in the blogentries table
	// array $blogdata, is the returned data retrieval from the getSingleBlogEntry
	// function
	
	// get the renderpath for the theme the current post is using;
	// and pull in its blog parser page
	if($blogdata['blogtypedata']['renderpath']!==""){
		$typedata=$blogdata['blogtypedata'];
		if(file_exists($host_tpath.$typedata['renderpath']."/modules/blogpageparser.php")){
			// assign the necessary mpage values to be used by the header file in the
			// theme blogpageparser.php file to be used
			$blogtypeid=$blogdata['blogtypeid'];
			$mpageimage=$blogdata['profpicdata']['location'];
			$mpageblogid=$blogdata['id'];
			
			$mpagetitle=$blogdata['title'];
			$mpageurl=$host_addr."blog/?p=$mpageblogid&trender=true";
			$mpagecanonicalurl=$host_addr."blog/?p=$mpageblogid&trender=true";
			$mpageogtype="Article";
			$mpagedescription=$blogdata['plaindescription'];
			$mpagekeywords=$blogdata['seometakeywords'];
			// utilise the share plugin to count the social shares for the post url
			$mpagescriptextras='
				<script>
					$(document).ready(function(){
						if($.fn.sharre){
							$(\'#sharecounter span.counter\').sharrre({
							  share: {
							    googlePlus: true,
							    facebook: true,
							    twitter: true,
							    digg: true,
							    delicious: true
							  },
							  url: \''.$mpagecanonicalurl.'\',
							  enableHover: false,
							  render: function(api, options){
							  	var total=options.count.googlePlus+options.count.twitter;
							  	total+=options.count.facebook+options.count.linkedin;
							  	total+=options.count.digg+options.count.delicious;
							  	total+=options.count.stumbleupon;
							  	$(\'#sharecount span.counter\').html(total);
							  }
							});
							
						}
					})
				</script>
			';
			// loads up tinymce just incase
			$mpagescriptextras='

				<script language="javascript" 
				type="text/javascript" 
				src="'.$host_addr.'plugins/sharrre/jquery.sharrre.min.js"></script>
				<script language="javascript" 
				type="text/javascript" 
				src="'.$host_addr.'scripts/lib/tinymce/jquery.tinymce.min.js"></script>
			    <script language="javascript"  type="text/javascript" 
			    src="'.$host_addr.'scripts/lib/tinymce/tinymce.min.js"></script>
			    <script language="javascript"  type="text/javascript" 
			    src="'.$host_addr.'scripts/lib/tinymce/basic_config.js"></script>
			';
			// disable the default maps
			$mpagemaps="";
			$mpagescriptarray[]="plugins/sharrre/jquery.sharrre.min.js";
			$mpagescriptarray[]="scripts/lib/tinymce/jquery.tinymce.min.js";
			$mpagescriptarray[]="scripts/lib/tinymce/tinymce.min.js";
			$mpagescriptarray[]="scripts/lib/tinymce/basic_config.js";
			// check if a comment was currently made in the event that the website
			// forces comment approval before they go on the platform
			$comment_message="Comment Successfully made, after approval, will be visible 
			on the platform";
			$comcheck=isset($_GET['c'])&&$_GET['c']!==""?:"";
			if($comcheck=="success"){
				// check if the last success notification session is active
				if(isset($_SESSION['lastsuccess'])&&$_SESSION['lastsuccess']==0){
					$comment_message="Comment Successfully made, after approval, will be visible 
					on the platform";
					unset($_SESSION['lastsuccess']);
				}
			}else{
				// check if the last success notification session is active
				if(isset($_SESSION['lastsuccess'])&&$_SESSION['lastsuccess']==0){
					$comment_message="Commenting Failed!!! Either the comment field 
					was empty or there were missing field values";
					unset($_SESSION['lastsuccess']);
				}
			}

			// create the social platform share urls
			$facebook_url="http://www.facebook.com/sharer/sharer.php?u=$mpagecanonicalurl";
			$twitter_url="https://twitter.com/share?url=$mpagecanonicalurl&text=$mpagetitle";
			$googleplus_url="https://plus.google.com/share?url=$mpagecanonicalurl";
			$linkedin_url="https://www.linkedin.com/cws/share?url=$mpagecanonicalurl";
			$stumbleupon_url="http://www.stumbleupon.com/submit?url=$mpagecanonicalurl&title=$mpagetitle";
			$whatsapp_url="whatsapp://send?text=$mpagetitle%20$mpagecanonicalurl";
			// "https://mail.google.com/mail/u/0/?ui=2&view=cm&fs=1&tf=1&su=$mpagetitle&body=$mpagedescription% Follow the link to learn more."
			$googlemail_url="http://www.addtoany.com/add_to/google_gmail?linkurl=$mpagecanonicalurl&linkname=$mpagetitle.&linknote=$mpagedescription%20 Follow the link to learn more. $mpagecanonicalurl";
			$reddit_url="http://reddit.com/submit?url=$mpagecanonicalurl&title=$mpagetitle";
			$digg_url="https://digg.com/submit?url=$mpagecanonicalurl&title=$mpagetitle&bodytext=$mpagedescription";
			$hackernews_url="https://news.ycombinator.com/submitlink?u=$mpagecanonicalurl&sref=$host_website_name";
			$odnoklassniki_url="https://connect.ok.ru/dk?st.cmd=OAuth2Login&st.layout=w&st.redirect=%252Fdk%253Fcmd%253DWidgetSharePreview%2526amp%253Bst.cmd%253DWidgetSharePreview%2526amp%253Bst.shareUrl%253D$mpagecanonicalurl&st.client_id=-1";
			$skype_url="https://web.skype.com/share?url=$mpagecanonicalurl";
			$evernote_url="https://www.evernote.com/clip.action?url=$mpagecanonicalurl&title=$mpagetitle";
			$flipboard_url="https://share.flipboard.com/u/login?done=%2Fbookmarklet%2Fpopout%3Fv%3D2%26url%3D$mpagecanonicalurl%26title%3D$mpagetitle%26utm_medium%3Dweb%26utm_campaign%3Dwidgets%26utm_source%3D$host_website_name&fromBookmarklet=1";
			$email_url="mailto:?subject=$mpagetitle&body=$mpagedescription%20 Follow the link to learn more. $mpagecanonicalurl";
			// parse the tags into easy to use format by exploding them into an array
			$tagcloud=explode(",",$blogdata['tags']);

			// pull in the parser file for the current theme 
			include($host_tpath.$typedata['renderpath']."/modules/blogpageparser.php");
		}else{
			// we doing redirection for now
			// first we reset the view count from the blog post information
			genericSingleUpdate("blogentries","views",$blogdata['views'],"id",
				$blogdata['id']);
			//now we redirect in peace 
			// header('location:'.$host_addr.'');
		}
	}else{
		// pull default blog parsing page for the platform or simply redirect them 
		// to the index page


		// we doing redirection for now
		// first we reset the view count from the blog post information
		genericSingleUpdate("blogentries","views",$blogdata['views'],"id",$blogdata['id']);
		//now we redirect in peace 
		// header('location:'.$host_addr.'');
	}
?>