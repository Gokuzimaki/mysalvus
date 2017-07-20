<?php
	// blogdataparse.php
	// This snippet is concerned with parsing data from the blogpost table into useable
	// component formats for rendering in markup on the single blog post page

	$coverdata=$curblogdata['profpicdata'];
	
	// check to see if the post actually has an image, if not use your 
	// default placeholder image or nothing, whatever works
	// the  propicdata 

	$extra='';//for carrying extra markup data for cover related content

	// for holding the name for the default jpg images associated with 
	// each blogentrytype when no coverphoto is specified
	$fname="blognormal";

	if($curblogdata['blogentrytype']=="gallery"){
		$fname="bloggallery";
	}else if($curblogdata['blogentrytype']=="banner"){
		$fname="bloggallery";

	}else if($curblogdata['blogentrytype']=="video"){
		$fname="blogvideo";

	}else if($curblogdata['blogentrytype']=="audio"){
		$fname="blogaudio";

	}

	if($coverdata['location']!==""){
		// place your covercontent image details 
		$covercontent='
				<picture>
					<source media="(max-width: 767px)" 
				  	srcset="'.$coverdata['medsize'].'">
				  	<source media="(max-width: 480px)" 
				  	srcset="'.$coverdata['thumbnail'].'">
				  	<img src="'.$coverdata['location'].'" 
				  	alt="'.$curblogdata['title'].'">
				</picture>
				
		';
		$coverimgurl=$coverdata['location'];
		$coverimgmedsizeurl=$coverdata['medsize'];
		$coverimgthumbnailurl=$coverdata['thumbnail'];
	}else{
		$coverimgurl=''.$host_addr.'images/mysalvusimages/defaults/'.$fname.'.jpg';
		$coverimgmedsizeurl=
		''.$host_addr.'images/mysalvusimages/defaults/'.$fname.'.jpg';
		$coverimgthumbnailurl=
		''.$host_addr.'images/mysalvusimages/defaults/'.$fname.'.jpg';
		$covercontent='
			<div class="blog-image">
				<img 
				src="'.$host_addr.'images/mysalvusimages/defaults/'.$fname.'.jpg" 
				  	alt="'.$curblogdata['title'].'"/>
				'.$extra.'
			</div>

		';
	}
	// set the page url again here if the content
	$mpageimage=isset($mpageimage)&&$mpageimage==""?$coverimgurl:$test="success";
	// this variable is only useful for 
	$sourcetotal="";
	if($curblogdata['blogentrytype']=="gallery"){

		if($curblogdata['gallerydata']['total']>0){
			$covercontent="";
			$covercontent.='
				<div class="owl-slider-container _mainblog top-banner">
					<div class="owl-slider">
			';
			$bsimgcover="";
			$bsCarousel='<div id="myCarousel'.$curblogdata['id'].'" 
			class="carousel slide" data-ride="carousel">';
			$covercontent.='
				<div class="blog-gallery blog-carousel-slider">
			';
			$bspoints="";
			// place your covercontent image details 
			$gallerydata=$curblogdata['gallerydata'];
			for($t=0;$t<$curblogdata['gallerydata']['total'];$t++){
				$cimgdata=$gallerydata[$t];
				$cgalclass=$t==0?"active":"";
				$ti=$t+1;
				if($cimgdata['location']!==""){

					$caption=$cimgdata['caption']!==""?'<h2 
					class="gallery-title">'.$cimgdata['caption'].'</h2>
						':"";
					$details=$cimgdata['details']!==""?'<div 
					class="gallery-details">'.$cimgdata['details'].'</div>
						':"";
					$covercontent.='
						<div class="slider-image">
							<picture>
							  	<source media="(max-width: 767px)" 
							  	srcset="'.$cimgdata['medsize'].'">
							  	<source media="(max-width: 240px)" 
							  	srcset="'.$cimgdata['thumbnail'].'">
							  	<img src="'.$cimgdata['location'].'" 
							  	alt="'.$curblogdata['title'].'">
							</picture>
							<div class="blog-gallery-details">
								'.$caption.'
								'.$details.'
							</div>
						</div>
					';
					$bspoints.=
					    '<li data-target="#myCarousel'.$curblogdata['id'].'" 
					    data-slide-to="'.$t.'" class="'.$cgalclass.'"></li>';
					$bsimgcover.='
						<div class="item '.$cgalclass.'">
							<picture>
							  	<source media="(max-width: 767px)" 
							  	srcset="'.$cimgdata['medsize'].'">
							  	<source media="(max-width: 240px)" 
							  	srcset="'.$cimgdata['thumbnail'].'">
							  	<img src="'.$cimgdata['location'].'" 
							  	alt="'.$curblogdata['title'].'">
							</picture>
					      	<div class="carousel-caption">
					      		'.$caption.'
					      		'.$details.'
					      	</div>
					    </div>';
									
				}
			}
			$covercontent.='</div></div>';
			$bsCarousel.='
				<!-- Indicators -->
				<ol class="carousel-indicators">
					'.$bspoints.'  	
				</ol>
				<div class="carousel-inner" role="listbox">
					'.$bsimgcover.'
				</div>
				<!-- Left and right controls -->
				<a class="left carousel-control" 
				  href="#myCarousel'.$curblogdata['id'].'" 
				  role="button" data-slide="prev">
				    <span class="glyphicon glyphicon-chevron-left" 
				    aria-hidden="true"></span>
				    <span class="sr-only">Previous</span>
				</a>
				<a class="right carousel-control" 
				  href="#myCarousel'.$curblogdata['id'].'" 
				  role="button" data-slide="next">
				    <span class="glyphicon glyphicon-chevron-right" 
				    aria-hidden="true"></span>
				    <span class="sr-only">Next</span>
				</a>
			</div>
		';
			$covercontent=$bsCarousel;
		}
		$contenticon='fa-file-image-o';
	}else if($curblogdata['blogentrytype']=="video"){
		if($curblogdata['viddata']['total']>0){
			$viddata=$curblogdata['viddata'][0];
			// this array carries the following indexes
			// 'videowebm','videoflv','videomp4','video3gp',
			// 'videoembed'(embed code for a video), 'caption'
			// but for multiple displays you would concern yourself
			// with the coverphoto only
			if($viddata['disptype']=="local"){
				$sourcetotal="";
				if($viddata['videowebm']!==""){
					$sourcetotal.='<source src="'.$viddata['videowebm'].'" 
					type="video/webm"/>';
				}
				if($viddata['video3gp']!==""){
					$sourcetotal.='<source src="'.$viddata['video3gp'].'" 
					type="video/3gp"/>';
				}
				if($viddata['videoflv']!==""){
					$sourcetotal.='<source src="'.$viddata['videoflv'].'" 
					type="video/flv"/>';
				}
				if($viddata['videomp4']!==""){
					$sourcetotal.='<source src="'.$viddata['videomp4'].'" 
					type="video/mp4"/>';
				}
				$covercontent='<video preload="metadata" controls>
							'.$sourcetotal.'
						</video>';
			} else {

				$covercontent=$viddata['videoembed'];
				$sourcetotal=$viddata['videoembed'];
			}
		}

		$contenticon='fa-television';
	}else if($curblogdata['blogentrytype']=="audio"){
		if($curblogdata['audiodata']['total']>0){
			$audiodata=$curblogdata['audiodata'][0];
			// this array carries the following indexes
			// 'location','audiocaption','audioembed','disptype',
			$sourcetotal=$audiodata['location'];
			$covercontent='
				<audio src="'.$audiodata['location'].'" controls>
					Your Browser does not support 
					HTML5 audio
				</audio>

			';
			if($audiodata['disptype']=="embed"){
				$ehid="";
				$lhid="hidden";
				$covercontent=$audiodata['audioembed'];
				$sourcetotal=$audiodata['audioembed'];
			}
			
		}
		$contenticon='fa-volume-up';
	}else if($curblogdata['blogentrytype']=="banner"){
		if($curblogdata['bannerdata']['total']>0){
			$bannerdata=$curblogdata['bannerdata'];
			// this array carries the following indexes
			 
		}
		$contenticon='fa-file-image-o';
	}else{
		$contenticon='fa-file-text-o';
	}
	// sort the next and previous blog post data
	$nextposturl="##";
	$nextposttype="normal";
	$nextposttitle="End of Posts";
	$nextblogdata=$curblogdata['nextblogdata'];
	$prevposturl="##";
	$prevposttype="normal";
	$prevposttitle="End of Posts";
	$prevblogdata=$curblogdata['prevblogdata'];
	// for next post
	if(isset($nextblogdata)&&$nextblogdata['numrows']>0){
		
		$nextposturl=$nextblogdata['pagelink'];
		$nextposttype=$nextblogdata['blogentrytype'];
		$nextposttitle=$nextblogdata['title'];
		unset($nextblogdata);
	}	

	// for previous posts
	if(isset($prevblogdata)&&$prevblogdata['numrows']>0){
		
		$prevposturl=$prevblogdata['pagelink'];
		$prevposttype=$prevblogdata['blogentrytype'];
		$prevposttitle=$prevblogdata['title'];
		unset($prevblogdata);
	}	

	include($host_tpathplain."modules/blogcommentparser.php");
?>