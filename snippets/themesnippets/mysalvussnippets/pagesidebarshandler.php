<?php
	// get testimonials data
    $mpagetestimonialsdata=getAllClientRecommendations("viewer",'testimonial',"LIMIT 0,20");
    // create testimonial for sidebar
    $testdataout='<li class="side-bar-null">No testimonials available yet</li>';
    $testdataoutmain=$testdataout;
    if($mpagetestimonialsdata['numrows']>0){
    	$testdataout="";
    	$testdataoutmain="";
    	// var_dump($mpagetestimonialsdata['resultdataset']);
    	for ($i=0; $i <$mpagetestimonialsdata['numrows'] ; $i++) { 
    		# code...
    		$curdataset=$mpagetestimonialsdata['resultdataset'][$i];
    		$testdataout.='
    			<li>
	                <div class="testimonial-mini-hold">
	                	<div class="testimonial-mini">
	                		<div class="testimony">
	                			<blockquote>
	                				<q>
	                                <i class="fa fa-quote-left"></i>
	                    			'.$curdataset['content'].'
	                			</blockquote>
	                		</div>
	                		<div class="testimoner">
	                			<div class="user-detail">
	                                <a href="'.$curdataset['personalsite'].'" class="user">
	                    				<img src="'.$curdataset['coverphotothumb'].'"/>
	                                </a>
	                                <strong class="name">'.$curdataset['fullname'].'</strong>
	                                <a href="'.$curdataset['companysite'].'" class="web">'.$curdataset['companyname'].'</a>
	                            </div>
	                		</div>
	                	</div>
	                </div>
	        	</li>
    		';
    		$testdataoutmain.='
    			<li>
                	<div class="testimonial-slide-item-holder">
                		<div class="col-sm-8 testimonial-slide">
                			<div class="testimonial-text">
                				<div class="testimony">
	                    			'.$curdataset['content'].'
                					
                				</div>
                			</div>
                		</div>
                		<div class="col-sm-4 testimonial-slide-user-img">
                			<img src="'.$curdataset['coverphotothumb'].'"/>
                			<p class="name">'.$curdataset['fullname'].'</p>
                			<h3><a href="'.$curdataset['companysite'].'" class="url">'.$curdataset['companyname'].'</a></h3>
                		</div>
                	</div>
            	</li>
    		';
    	}
    }/*else{
	    for($i=1;$i<=12;$i++){ 	
	    	$testdataout.='
	        	<li>
	                <div class="testimonial-mini-hold">
	                	<div class="testimonial-mini">
	                		<div class="testimony">
	                			<blockquote>
	                				<q>
	                                <i class="fa fa-quote-left"></i>
	                    			Lorem ipsum dolor sit amet, 
	            					consectetuer adipiscing elit.
	            					Aenean commodo ligula eget dolor. 
	            					Aenean massa. Cum sociis natoque penatibus
									et magnis dis parturient montes, 
									nascetur ridiculus mus. Donec quam felis, 
									ultricies nec, pellentesque eu, pretium quis, 
									sem. Nulla consequat massa quis enim. 
									Donec pede justo, fringilla vel, aliquet nec, 
									vulputate eget, arcu.
	                			</blockquote>
	                		</div>
	                		<div class="testimoner">
	                			<div class="user-detail">
	                                <a href="#" class="user">
	                    				<img src="images/default.gif"/>
	                                </a>
	                                <strong class="name">Adam Dale</strong>
	                                <a href="#" class="web">CEO James blockade</a>
	                            </div>
	                		</div>
	                	</div>
	                </div>
	        	</li>
	    	';
	    }	
    }*/
    /*end testimonial data*/

    // get events data
    $mpagelatestevents="";
    $eventoutdata='<p class="side-bar-null">No events available</p>';
    if(function_exists('getSingleEventEntry')){
	    $mpagelatestevents=getSingleEventEntry(0,"lastupcoming");
	    if($mpagelatestevents['numrows']>0){
	    	$eventoutdata=$mpagelatestevents['vieweroutputminisb'];
	    }
    }
    // end events data
	$mpagedefaultsidebar='
		<aside class="sidebar">			
			<div class="sidebar-default bg-green-gradient">
                <div class="head">
                    <h3 class=""><i class="fa fa-money"></i> Donations</h3>
                </div>
                <div class="sidebar-default-content">                                        	
                    <p class="text-center">Support us by making a donation today</p>
                	<a href="##" class="readmore lead-on-btn color-green">Learn More</a>
                </div>
			</div>
			<div class="sidebar-default bg-purple-gradient">
                <div class="head">
                    <h3 class="color-white"> <i class="fa fa-users"></i> Membership/Partnerships</h3>
                </div>
                <div class="sidebar-default-content">
                	<img class="partnership-poster" src="images/fvtimages/partnership.jpg">
                	<p>Want to join or partner with us, follow the link below</p>
                	<a href="##" class="readmore lead-on-btn color-purple">Learn more</a>
                </div>
			</div>
			<div class="sidebar-default">
                <div class="head">
                    <h3 class="">Upcoming event</h3>
                </div>
                <div class="sidebar-default-content">
                	<div class="next-event">
                        '.$eventoutdata.'
                    </div>
                </div>
			</div>
			<div class="sidebar-default bg-orange-gradient">
				<div class="head">
                    <h3 class="color-white text-shadow-color-white"><i class="fa fa-spin fa-star"></i> Our Values</h3>
                </div>
                <div class="sidebar-default-content">
                	<span class="values-pan">Transparency</span><br>
					<span class="values-pan">Committment</span><br>
					<span class="values-pan">Making a difference</span><br>
					<span class="values-pan">Leadership</span><br>
					<span class="values-pan">Strategic Alliance</span><br>
					<span class="values-pan">Financial Stability</span>
                </div>
			</div>
			<div class="sidebar-testimonial">
                <div class="head">
                    <h3>Testimonials</h3>
                </div>
                <ul id="sidebar-testimonial">
                	'.$testdataout.'
                </ul>
            </div>

		</aside>
	';
?>