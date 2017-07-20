<?php
	// for scheduled publishing of posts
	function publishPost($postid){
		include('globalsmodule.php');
		$postdata=getSingleBlogEntry($postid);
		$pagename=$postdata['pagelink'];
		$pagelink=$postdata['pagelink'];
		$title=$postdata['title'];
		$introparagraph=$postdata['introparagraph'];
		$blogtypeid=$postdata['blogtypeid'];
		$blogcategoryid=$postdata['blogcatid'];
		$datetime= date("D, d M Y H:i:s", strtotime($postdata['postperiod']))." +0100";
		$date=date("d, F Y H:i:s", strtotime($postdata['postperiod']));
		//create blog post rss entry
		$introrssentry=str_replace("../",$host_addr,$introparagraph);
		$rssentry='<item>
		<title>'.$title.'</title>
		<link>'.$pagelink.'</link>
		<pubDate>'.$datetime.'</pubDate>
		<guid isPermaLink="false">'.$host_addr.'blog/?p='.$postid.'</guid>
		<description>
		<![CDATA['.$introrssentry.']]>
		</description>
		</item>
		';
		$rssentry=mysql_real_escape_string($rssentry);
		// echo $rssentry;
		$rssquery="INSERT INTO rssentries(blogtypeid,blogcategoryid,blogentryid,rssentry)VALUES('$blogtypeid','$blogcategoryid','$postid','$rssentry')";
		$rssrun=mysql_query($rssquery)or die(mysql_error());
		//write rss information to respective blog type(for autoposting) and blog category
		writeRssData($blogtypeid,0);
		writeRssData(0,$blogcategoryid);
		// update the blogpost date column and satus
		$postperiod=$postdata['postperiod'];
		genericSingleUpdate("blogentries","date","$postperiod","id",$postid);
		genericSingleUpdate("blogentries","entrydate","$date","id",$postid);
		genericSingleUpdate("blogentries","status","active","id",$postid);
		sendSubscriberEmail($postid);
	}
	// for on the spot publishing of posts
	function livePublishing(){
		include('globalsmodule.php');
		$postdata=getSingleBlogEntry($postid);
		$pagename=$postdata['pagelink'];
		$pagelink=$postdata['pagelink'];
		$title=$postdata['title'];
		$introparagraph=$postdata['introparagraph'];
		$blogtypeid=$postdata['blogtypeid'];
		$blogcategoryid=$postdata['blogcatid'];
		$datetime= date("D, d M Y H:i:s")." +0100";
		$date=date("d, F Y H:i:s");
		//create blog post rss entry
		$introrssentry=str_replace("../",$host_addr,$introparagraph);
		$rssentry='<item>
		<title>'.$title.'</title>
		<link>'.$pagelink.'</link>
		<pubDate>'.$datetime.'</pubDate>
		<guid isPermaLink="false">'.$host_addr.'blog/?p='.$postid.'</guid>
		<description>
		<![CDATA['.$introrssentry.']]>
		</description>
		</item>
		';
		$rssentry=mysql_real_escape_string($rssentry);
		// echo $rssentry;
		$rssquery="INSERT INTO rssentries(blogtypeid,blogcategoryid,blogentryid,rssentry)VALUES('$blogtypeid','$blogcategoryid','$postid','$rssentry')";
		$rssrun=mysql_query($rssquery)or die(mysql_error());
		//write rss information to respective blog type(for autoposting) and blog category
		writeRssData($blogtypeid,0);
		writeRssData(0,$blogcategoryid);
		sendSubscriberEmail($postid);
		// update the blogpost date column and satus
		$postperiod=$postdata['postperiod'];
		genericSingleUpdate("blogentries","date","$postperiod","id",$postid);
		genericSingleUpdate("blogentries","entrydate","$date","id",$postid);
		genericSingleUpdate("blogentries","status","active","id",$postid);
	}
?>