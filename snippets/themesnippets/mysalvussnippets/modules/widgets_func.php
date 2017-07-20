<?php
	// this file is holds the widget parser for the current theme

	function theme_Widgets($widget_data,$b_options,$theme_data=array()){
		global $host_tpathplain;
		include($host_tpathplain.'globalsmodule.php');
		// the output variable carryin the result set
		$output="";

		if(!isset($b_options)){
			// init $b_options and set its 'type' index value to the default
			// 'maincontent'
			$b_options=array();
		}
		$b_options['limit']=!isset($b_options['limit'])?"LIMIT 0,15":$b_options['limit'];
		$b_options['queryextra']=!isset($b_options['queryextra'])?"":
		$b_options['queryextra'];
		$b_options['type']=!isset($b_options['type'])?"maincontent":$b_options['type'];
		// for pagination content
		// specify that pagination be made available by default
		$b_options['pagination']=!isset($b_options['pagination'])?true:
		$b_options['pagination'];

		$b_options['paginationtype']=!isset($b_options['paginationtype'])?"default":
		$b_options['paginationtype'];
		
		// check for pagination option from the url get parameters
		if(isset($_GET['b_optpag'])&&$_GET['b_optpag']!==""){
			$b_options['paginationtype']=$_GET['b_optpag'];
		}
		if(isset($_POST['b_optpag'])&&$_POST['b_optpag']!==""){
			$b_options['paginationtype']=$_POST['b_optpag'];
			
		}

		// control whether access to the top pagination or bottom pagination is
		// allowed
		$b_options['showtoppages']=!isset($b_options['showtoppages'])?true:
		$b_options['showtoppages'];

		$b_options['showbottompages']=!isset($b_options['showbottompages'])?true:
		$b_options['showbottompages'];

		// adjust the value of the limit variable so it can be controlled via the
		// $b_options array allowing for flexibility when controlling expected output
		$limit=isset($b_options['limit'])&&$b_options['limit']!==""?$b_options['limit']:
		$outs['limit'];

		// adjust the value of the 'queryextra' and 'ordercontent' entry to the 
		// database.



		// get the blogdata
		$btypedata=isset($widget_data['type_blog']['blogdata'])?
		$widget_data['type_blog']['blogdata']:NULL;
		// handles blog based widget outputs
		if($btypedata==true){
			$b_data["single"]["type"]="blockdeeprun";
			$blogdata=getAllBlogEntries('viewer',$limit,"",$b_options);
		}
		
		if(isset($blogdata)){
			// the blog data set has full data entry content for blog posts
			// valid return content can be found in the 'return_data' index

			// generate blog post contents
			if(isset($blogdata['numrows'])&&$blogdata['numrows']>0){
				// any other alterations to the blog post content outputted is made available
				// by using the multidimensional array $b_options, basic indexes for this 
				// array are as follows:
				// 'type' : tells the nature of the output to be produced, the major 'type'
				// values are : "maintrailer", "maincontent", "mainsidebar", "mainfooter".
				// variations can be made for individual themes to suit any purposed output
				// e.g maincontent2 or mainsidebarmini, e.t.c can represent other kinds of
				// output content
				// the blogposts results are available in the 'resultdataset' 
				$maxrows=$blogdata['numrows'];

				// store the final output in the below variable
				$output="";

				// check if a limit has been placed on the number of results to be produced
				// via the b_options array
				if(isset($b_options['outlimit'])&&$b_options['outlimit']!==""){
					$maxrows=$b_options['outlimit'];
				}	
				for($i=0;$i<$maxrows;$i++){
					// current blog data
					if(isset($blogdata['resultdataset'][$i])){
						$cblogdata=$blogdata['resultdataset'][$i];
						// $i==0?var_dump($cblogdata):$city="donothing";
						// clear the coverdata variable to ensure no duplicate content
						// is entring
						if(isset($coverdata)){
							unset($coverdata);
						}
						$coverdata=$cblogdata['profpicdata'];
						
						// check to see if the post actuallyhas an image, if not use your 
						// default placeholder image or nothing, whatever works
						// the  propicdata 

						$extra='';//for carrying extra markup data for cover related content
						// for holding the name for the default jpg images associated with 
						// each blogentrytype when no coverphoto is specified
						$fname="blognormal";

						if($cblogdata['blogentrytype']=="gallery"){
							$fname="bloggallery";
						}else if($cblogdata['blogentrytype']=="banner"){
							$fname="bloggallery";
							$extra='<div class="blog-video-cover">
					                    <a href="'.$cblogdata['pagelink'].'" class="video-play 
					                      	color-white color-whitehover 
					                      	bg-darkindigo-gradient bg-lightred-gradienthover">
					                      	<i class="fa fa-file-image-o"></i>
					                    </a>
				                    </div>';
						}else if($cblogdata['blogentrytype']=="video"){
							$fname="blogvideo";
							$extra='<div class="blog-video-cover">
					                    <a href="'.$cblogdata['pagelink'].'" class="video-play 
					                      	color-white color-whitehover 
					                      	bg-darkindigo-gradient bg-lightred-gradienthover">
					                      	<i class="fa fa-play"></i>
					                    </a>
				                    </div>';
						}else if($cblogdata['blogentrytype']=="audio"){
							$fname="blogaudio";
							$extra='<div class="blog-video-cover">
					                    <a href="'.$cblogdata['pagelink'].'" class="video-play 
					                      	color-white color-whitehover 
					                      	bg-darkindigo-gradient bg-lightred-gradienthover">
					                      	<i class="fa fa-volume-up"></i>
					                    </a>
				                    </div>';
						}

						if($coverdata['location']!==""){
							// place your covercontent image details 
							$covercontent='
								<div class="blog-image">
									<picture>
										<source media="(max-width: 767px)" 
									  	srcset="'.$coverdata['medsize'].'">
									  	<source media="(max-width: 320px)" 
									  	srcset="'.$coverdata['thumbnail'].'">
									  	<img src="'.$coverdata['location'].'" 
									  	alt="'.$cblogdata['title'].'">
									</picture>
									'.$extra.'
								</div>
							';

							$coverimgurl=$coverdata['location'];
							$coverimgmedsizeurl=$coverdata['medsize'];
							$coverimgthumbnailurl=$coverdata['thumbnail'];
						}else{
							$coverimgurl='
							'.$host_addr.'images/dbrimages/defaults/'.$fname.'.jpg';
							$coverimgmedsizeurl=
							''.$host_addr.'images/dbrimages/defaults/'.$fname.'.jpg';
							$coverimgthumbnailurl=
							''.$host_addr.'images/dbrimages/defaults/'.$fname.'.jpg';
							$covercontent='
								<div class="blog-image">
									<img 
									src="'.$host_addr.'images/dbrimages/defaults/'.$fname.'.jpg" 
									  	alt="'.$cblogdata['title'].'"/>
									'.$extra.'
								</div>

							';
						}
						
						if($cblogdata['blogentrytype']=="gallery"){

							if($cblogdata['gallerydata']['total']>0){
								$covercontent="";
								$covercontent.='
									<div class="blog-gallery blog-carousel-slider">
								';
								// place your covercontent image details 
								$gallerydata=$cblogdata['gallerydata'];
								for($t=0;$t<4;$t++){
									$cimgdata=$gallerydata[$t];
									if($cimgdata['location']!==""){

										$caption=$cimgdata['caption']!==""?'<h2 
										class="gallery-title">'.$cimgdata['caption'].'</h2>
											':"";
										$details=$cimgdata['details']!==""?'<div 
										class="gallery-details">'.$cimgdata['details'].'</div>
											':"";
										$covercontent.='
											<div class="blog-gallery-item">
												<picture>
												  	<source media="(max-width: 767px)" 
												  	srcset="'.$cimgdata['medsize'].'">
												  	<source media="(max-width: 240px)" 
												  	srcset="'.$cimgdata['thumbnail'].'">
												  	<img src="'.$cimgdata['location'].'" 
												  	alt="'.$cblogdata['title'].'">
												</picture>
												<div class="blog-gallery-details">
													'.$caption.'
													'.$details.'
												</div>
											</div>
										';
									}
								}
								$covercontent.='</div>';
							}
							$contenticon='fa-file-image-o';
						}else if($cblogdata['blogentrytype']=="video"){
							if($cblogdata['viddata']['total']>0){
								$viddata=$cblogdata['viddata'];
								// this array carries the following indexes
								// 'videowebm','videoflv','videomp4','video3gp',
								// 'videoembed'(embed code for a video), 'caption'
								// but for multiple displays you would concern yourself
								// with the coverphoto only 

							}

							$contenticon='fa-television';
						}else if($cblogdata['blogentrytype']=="audio"){
							if($cblogdata['audiodata']['total']>0){
								$audiodata=$cblogdata['audiodata'];
								// this array carries the following indexes
								// 'videowebm','videoflv','videomp4','video3gp',
								// 'videoembed'(embed code for a video), 'caption'
								// but for multiple displays you would concern yourself
								// with the coverphoto only 
								
							}
							$contenticon='fa-volume-up';
						}else if($cblogdata['blogentrytype']=="banner"){
							if($cblogdata['bannerdata']['total']>0){
								$bannerdata=$cblogdata['bannerdata'];
								// this array carries the following indexes
								 
								
							}
							$contenticon='fa-file-image-o';
						}else{
							$contenticon='fa-file-text-o';
						}

						// handle comment count data
						if($cblogdata['commenttype']=="normal"){
							$comment_count=$cblogdata['viewercommentcount'];
						}else{
							$comment_count=$cblogdata['comment_count'];
						}
				

						if($b_options['type']=="maintrailercontent"){
							// for displaying usually text only data can be used in 
							// a small slider/marquee
						}

						if($b_options['type']=="maincontent"){
							// this section is for producing content in a multiview format
							// usually used in displaying the main blog posts to site
							// visitors on the 'blog' page
						
							$output='
								<div class="blog-classic">
									<div class="blog-classic-item">
										<div class="blog-header">
											<div class="blog-date">
												<span class="blog-day">
													'.$cblogdata['day'].'
												</span>
												<span class="blog-month _2">
													'.$cblogdata['month'].'
												</span>
												<span class="blog-month">
													'.$cblogdata['year'].'
												</span>
			                      				<span class="blog-type 
				                      				background-color-indigo 
				                      				bg-indigo-gradient">
				                      				<i class="fa '.$contenticon.'"></i>
				                      			</span>

											</div>
											<div class="blog-info">
												<a href="'.$cblogdata['pagelink'].'"
													class="blogtitle">
													<h2 class="blog-title">
														'.$cblogdata['title'].'
													</h2>
												</a>
												<div class="blog-details">
													by '.$cblogdata['poster']['fullname'].'/ 
													in '.$cblogdata['blogcatdata']['catname'].'/ 
													Comments: '.$comment_count.'
													Views: '.number_format($cblogdata['views']).'
												</div>
											</div>
										</div>
										'.$covercontent.'
										<div class="blog-post-content">
											'.$cblogdata['introout'].'
											<a href="'.$cblogdata['pagelink'].'"
											 class="readmore _1">More</a>
										</div>
									</div>
								</div>
							';

						}

						if($b_options['type']=="maincontent2"){
							// this is for multiple related posts on the main blog post
							// view
							$output='
								<div class="related-post-item">
									<div class="related-post-container _blog" 
									style="background-image: url(\''.$coverimgmedsizeurl.'\');"
									data-thumb="'.$coverimgthumbnailurl.'"
									data-med="'.$coverimgmedsizeurl.'">
										<div class="related-post-overlay"></div>
										<div class="related-post">
											<div class="related-post-content">
												<h4>
													<a href="'.$cblogdata['pagelink'].'">
														'.$cblogdata['title'].'
													</a><br>
													<small>
														<i class="fa fa-comments"></i> 
														'.$cblogdata['viewercommentcount'].'
													</small>
													<small>
														<i class="fa fa-eye"></i> 
														'.$cblogdata['views'].'
													</small>
												</h4>
												<p>'.$cblogdata['plaindescription'].'</p>
											</div>
										</div>
									</div>
								</div>
							';

						}

						if($b_options['type']=="mainsidebar"){
							// this is for single blog side bar view

						}

						if($b_options['type']=="mainsidebarmini"){
							// this is for multiple list blog sidebar view
							$output='
								<li class="post-item">
									<a href="'.$cblogdata['pagelink'].'" class="post-image">

										<img src="'.$coverimgthumbnailurl.'" 
										alt="'.$cblogdata['title'].'">
									</a>
									<div class="post-content">
										<h5 class="post-title">
											<a href="'.$cblogdata['pagelink'].'">
												'.$cblogdata['title'].'
											</a>
										</h5>
										<span class="post-date">
											'.$cblogdata['day'].',
											'.$cblogdata['month2'].'
											'.$cblogdata['year'].'
										</span>
									</div>
								</li>
							';

						}

						if($b_options['type']=="mainsidebarmini2"){
							// this is for single list blog sidebar view
							// 
							$output='
								<li class="post-item">
									<a href="'.$cblogdata['pagelink'].'" 
										class="post-image mini">
										<picture>
											<source media="(max-width: 320px)" 
										  	srcset="'.$coverimgthumbnailurl.'">
											<img src="'.$coverimgmedsizeurl.'" 
											alt="'.$cblogdata['title'].'">
										</picture>
									</a>
									<div class="post-content mini">
										<h5 class="post-title">
											<a href="'.$cblogdata['pagelink'].'">
												'.$cblogdata['title'].'
											</a>
										</h5>
										<span class="post-date">
											'.$cblogdata['day'].',
											'.$cblogdata['month2'].'
											'.$cblogdata['year'].',
										</span>
										<span class="post-comments">
											Comments: '.$cblogdata['viewercommentcount'].'
										</span>
										<div class="post-mini-details">
											'.$cblogdata['plaindescription'].'
										</div>

									</div>
								</li>
							';

						}

						if($b_options['type']=="mainfooter"){
							// this is for producing footer related blog post segments
							// the data presented here is usually minimal

						}
					
						// end if
					}
					// end for loop
				} 
			}else{

				$deftag=isset($b_options['defaulttag'])&&$b_options['defaulttag']!==""?
				$b_options['defaulttag']:"div";
				$defclass=isset($b_options['defaultclass'])&&$b_options['defaultclass']!==""?
				$b_options['defaultclass']:"col-md-12 _genericnopost";
				$deftext=isset($b_options['defaulttext'])&&$b_options['defaulttext']!==""?
				$b_options['defaulttext']:"
				<p>No posts to display yet</p>
				";
				$output='
					<'.$deftag.' class=" '.$defclass.'">
						'.$deftext.'	
					</'.$deftag.'>
				';

			}
		}

		// handles portfolio based widget outputs
		$portdata=isset($widget_data['type_port']['portdata'])?
		$widget_data['type_port']['portdata']:NULL;

		return $output;
	}
?>
