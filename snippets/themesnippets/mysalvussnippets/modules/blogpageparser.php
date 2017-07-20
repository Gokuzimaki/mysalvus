<?php
	// this file is responsible for producing the final render of the blog page
	// based on the current theme
	if($blogdata['status']=="active"){
		// reassign the blogdata variable and unset it to allow other file includes
		// that rely on it function properly 
		// var_dump($blogdata);
		$curblogdata= $blogdata;
		// unset($blogdata);
		$mcolc=9;
		$sbcolc=3;
		$sbstyle="";
		if($curblogdata['blogentrytype']=="banner"){
			$mcolc=12;
			$sbcolc="12 _fullwidth";
			$sbstyle='style="display:hidden;"';

		}
		// this files handles the parsing and output of blog page content
		// it is a system module
		include($host_tpathplain.'modules/blogdataparser.php');
		// perform extra operations on the header 'mpage' variables here
		if(isset($mpagescriptextras)){
			$mpagescriptextras.=$c_form_script;
		}else{
			$mpagescriptextras=$c_form_script;

		}
		// bring in the system default widgets	

		// control options content for empty brp retrieval
		$b_options['brpdefaulttext']="No more posts here";
		$b_options['brpdefaultclass']="related-post-item";
		include($host_tpathplain.'modules/defaultwidgets.php');

		// this file is the blogdataparser for the current theme, make any changes to
		// it to provide you desired output should the default above prove 
		// unsatisfactory

		// this variable represents the content of the full-width-section class
		// based on the type of the blog being displayed
		$eclass="";
		if($curblogdata['blogentrytype']=="video"){
			$eclass="_video";

		}
		if($curblogdata['blogentrytype']=="audio"){
			$eclass="_audio";

		}
		if($curblogdata['blogentrytype']=="banner"){
			$eclass="_banner";

		}

?>
<?php  
		// the main opengraph meta tags used in the header content are as follows
		
		// this mpage variable can carry and deposit multple meta tags in the theme
		// header file
		$mpageextrametas='';
		
		// NB to get the total comments for a page, style out an element with the attr
		// id="sharecounter" and a span element in it with the class="count"
		// the span element will carry the count data for the shares across the platforms
		// googleplus, facebook, twitter, linkedin, digg, delicious and stumbleupon

		// the share urls for the post are given to you via the format variables
		// $socialnetwork_url e.g $facebook_url
		// note that you have to verify the variable exists before applying it
		// or if you want you can create your own sharing mechanism here, but remember
		// to use the url stored in $mpagecanonicalurl for sharing.
		$mpagecrumbdata='
			<li><a href="'.$host_addr.'">Home</a></li>
			<li class=""><a href="'.$host_addr.'/blog.php">Blog</a></li>
			<li class="active">'.$mpagetitle.'</li>
		';
		$mpagecrumbtitle="Blog Post";
		$mpagecrumbclass="";
		// obtain data
		include($host_tpathplain.'themesnippets/mysalvussnippets/headcontentmysalvus.php');

?>
	<body>
		<!--[if lt IE 8]>
			<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->
		<div id="container" class="">
			<?php
				include($host_tpathplain.'themesnippets/mysalvussnippets/toplinksmysalvus.php');
			?>
                <div class="top20 hold bottom20">

                </div>
			
				<div class="container padded-bottom">
					<div class="row top-margin-sm ">
						<div class="col-md-<?php echo $mcolc;?> blog-item blog-post-single">
							
                          <h2 class="page-header"><?php echo $curblogdata['title'];?></h2>
                          	  <!-- Blog Item Start -->

                              <!-- <h2><a href="blog-single-post.html#">A Tempor Dolore Magna Aliquyam Erat</a></h2> -->
                              
                              <p class="post-meta">
                              	<span><i class="glyphicon glyphicon-time"></i> 
                              		Posted on
                              		<?php echo $curblogdata['maindayout'];?> 
								</span>

                                
                                <span><i class="glyphicon glyphicon-user"></i> By 
	                            	<a href="##">
	                            		<?php echo $curblogdata['poster']['fullname'];?>
	                            	</a>
	                            </span>

	                            <span>
	                            	<i class="glyphicon glyphicon-list-alt"></i> 
	                            	<a href="##">
	                            		<?php echo $curblogdata['blogcatdata']['catname'];?>
	                            	</a>
	                            </span>
	                            <span><i class="glyphicon glyphicon-comment"></i> 
	                            	<?php echo $blog_comment_count;?></span>
                            	<span><i class="fa fa-eye"></i> 
                            	<?php echo $curblogdata['views'];?></span>
                              </p>
                              <div class="seperator-3d-dark top10 bottom10"></div>
                              <div class="blog-cover <?php echo $eclass;?>">
                              	<?php
									echo $covercontent;
								?>
                              </div>
                            <div class="top20 hold bottom20">
                            	<?php echo $curblogdata['blogpost'];?>
                            </div>
                              <!-- Blog Item End -->
                              
                              <!-- Blog Pager Start -->
                              <ul class="pager">
                                <li class="previous"><a href="<?php echo $prevposturl;?>">
                                	<i class="fa fa-chevron-left"></i> Previous Post</a></li>
                                <li class="next"><a href="<?php echo $nextposturl;?>">
                                	Next Post <i class="fa fa-chevron-right"></i></a></li>
                              </ul>
                             <!-- Blog Pager End -->
                    		 
                                 <!-- Separator Start -->
                                 <div class="separator top40 bottom40">
                                    <div class="separator-style"></div>
                                 </div>
                                 <!-- Separator End -->
                    
                              
                    		 <!-- Tags Start -->
                             <div class="row">
	                             <div class="col-md-12">

	                                <div class="col-md-6 col-sm-12 tags">
	                                     <span>
	                                     	<i class="glyphicon glyphicon-tags"></i>   :
	                                     	<?php
												// the post tags have been stored in an array
												// $tagcloud
												// the default url for a tag is
												// $host/theblogpage?tag=thetagname
												// unless otherwise changed by the theme
												// user
												if(count($tagcloud)>0){
													$tagvalid=false;
													for($tc=0;$tc<count($tagcloud);$tc++){
														$ctag=$tagcloud[$tc];
														$comma=$tc<count($tagcloud)-1?",":"";
														if($ctag!==""){
															$tagvalid=true;
											?>
															<a href="<?php echo $host_addr;?>/blog.php?tag=<?php echo $ctag;?>">
																<?php echo $ctag;?>
															</a>
																<?php echo $comma;?>
											<?php
														}
											?>
											<?php
													}
													if($tagvalid==false){
											?>
														<a href="#">No tags for this post</a>
											<?php
													}
											 	}else{

											?>
											<a href="#">No tags for this post detected</a>

											<?php
												}
											?>  
	                                </div>
	                                <div class="col-md-6 col-sm-12 blog-share">
										<i class="fa fa-share-alt"></i>
										<div class="blog-share-socials">
											<a href="<?php echo $facebook_url;?>">
												<i class="fa fa-facebook"></i>
											</a>
											<a href="<?php echo $twitter_url;?>">
												<i class="fa fa-twitter"></i>
											</a> 
											<a href="<?php echo $googleplus_url;?>">
												<i class="fa fa-google-plus"></i></a>
											<a href="<?php echo $googlemail_url;?>">
												<i class="fa fa-google-plus-official"></i></a>
											<a href="<?php echo $email_url;?>">
												<i class="fa fa-envelope-o"></i></a>
										</div>								
									</div>
	                             </div>
                             </div>
                             <!-- Tags End -->
                             
                                 <!-- Separator Start -->
                                 <div class="separator top40 bottom40">
                                    <div class="separator-style"></div>
                                 </div>
                                 <!-- Separator End -->
                    
                      
							
							<?php
								include($host_tpathplain.'themesnippets/mysalvussnippets/modules/blogcommentparser.php');
							?>
						</div>
						<div class="col-md-<?php echo $sbcolc;?>">
							<div id="sidebar-area">
								<?php 
									include($host_tpathplain.'themesnippets/mysalvussnippets/modules/sidebarparser.php');

								?>	
							</div>
						</div>
					</div>
				</div>
			
			<?php
				include($host_tpathplain.'themesnippets/mysalvussnippets/footermysalvus.php');
			?>
		</div>
		<?php
			include($host_tpathplain.'themesnippets/mysalvussnippets/themescriptsdumpmysalvus.php');
		?>
	</body>
</html>

<?php
	}else{
?>

<?php
	}
?>