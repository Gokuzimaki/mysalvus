<?php 
	// setup the default data set here to avoid breaking the code
	isset($host_addr)?$host_addr:$host_addr="";
	isset($host_tpath)?$host_tpath:$host_tpath="";
	isset($host_tpathplain)?$host_tpathplain:$host_tpathplain="";

	isset($host_logo_image)?$host_logo_image:$host_logo_image="";
	
	isset($host_target_addr)?$host_target_addr:$host_target_addr="";
	
	isset($host_keywords)?$host_keywords:$host_keywords="";
	
	isset($host_title_name)?$host_title_name:$host_title_name="";
	
	isset($host_website_name)?$host_website_name:$host_website_name="";
	
	isset($host_name)?$host_name:$host_name="";
	
	isset($host_admin_title_name)?$host_admin_title_name:$host_admin_title_name="";
	
	isset($host_default_poster)?$host_default_poster:$host_default_poster="";
	
	isset($host_default_cover_image)?$host_default_cover_image:$host_default_cover_image="";
	
	// specifies the maximum number of blog category posts to be retrieved
	isset($host_blog_catpost_max)&&$host_blog_catpost_max!==""?
	$host_blog_catpost_max:$host_blog_catpost_max=6;
	
	// specifies the default level of audio or video preview time for blogposts
	isset($host_blog_play_limit)?$host_blog_play_limit:$host_blog_play_limit="";
	
	isset($host_sessionvar_prefix)?$host_sessionvar_prefix:$host_sessionvar_prefix="";
	
	isset($host_sessionvar_suffix)?$host_sessionvar_suffix:$host_sessionvar_suffix="";
	
	isset($host_env)?$host_env:$host_env="";
	
	isset($host_favicon)?$host_favicon:$host_favicon="";
	
	isset($curactive)?$curactive:$curactive="";
	
	isset($prodnservcounter)?$prodnservcounter:$prodnservcounter="";
	
	isset($rurladmin)?$rurladmin:$rurladmin="";
	
	isset($cnrcount)?$cnrcount:$cnrcount=0;
	
	// default global variables for functions to use
	global  $host_addr,
			$host_tpath,
			$host_tpathplain,
			$host_target_addr,
			$host_cur_page,
			$host_logo_image,
			$host_target_addr,
			$host_price_limit,
			$host_keywords,
			$host_title_name,
			$host_website_name,
			$host_admin_title_name,
			$host_default_poster,
			$host_default_cover_image,
			$host_blog_play_limit,
			$host_blog_catpost_max,
			$host_sessionvar_prefix,
			$host_sessionvar_suffix,
			$host_env,
			$curactive,
			$prodnservcounter,
			$rurladmin,
			$host_favicon;
?>