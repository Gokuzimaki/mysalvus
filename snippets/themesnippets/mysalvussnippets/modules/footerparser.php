<?php
	// this file contains sidebar content for the pages using this theme
	// absolute urls must be used as required for handling assets
	// here widgets are defined and knitted together to form sidebar contents 
	// for varying purposes.
	// usually, most of the b_options variable settings array could be defined 
	// before including this file
		
	// or the options can be set based on the preset sidebartype variable
	
	// clear all previous options
	unset($b_options);
	// $b_options=NULL;
	$footertype=!isset($footertype)||(isset($footertype)&&$footertype=="")?"default"
	:$footertype;

	$blogtypeid=!isset($blogtypeid)||(isset($blogtypeid)&&$blogtypeid=="")?1
	:$blogtypeid;
	// use this section to init the b_options array variables for each 
	// widget type to be used
	if($footertype=="default"){
		// $b_options['querydebug']=true;

		$b_options['pagination']=false;

		// adjust the blog most commented post data output
		// $b_options['bmcptype']="mainsidebarmini2";
		
		// handle the number of comment posts to be displayed
		// $b_options['bmcplimit']=1;

		// handle the number of latest posts to be displayed
		$b_options['blplimit']=3;
		$widget_options['theme_blpwidget']=true;
	}

	// bring in the system default widget set
	include($host_tpathplain.'modules/defaultwidgets.php');
		// echo "$b_options[blplimit] blp limit";

	// widget titles the defaultwidgets bring in
	// theme_blpwidget: for blog latest posts
	// theme_bppwidget: for blog popular posts(mostviewed)
	// theme_bmcpwidget: for blog most commented posts
	// theme_bfpwidget: for blog featured posts


	//  begin widget definitions as per theme
	// include sorted widget definitions file

	include('widgets.php');
	
	if($footertype=="default"){
?>
			<div class="col-md-3 col-sm-6 bottom-box1">
                <h4 class="module-title2">About Us </h4> 
                <p><img alt="" class="mini" src="<?php echo $host_logo_imagemini;?>"></p>
                <p>
                	MySalvus is an interactive sexual violence 
					reporting platform where cases of sexual violence can be reported 
		  			anonymously.</p>
            </div>
            
            <div class="col-md-3 col-sm-6 bottom-box3">
            	<h4 class="module-title2">Latest News</h4>		
            	<?php 
				if(isset($theme_lbpfwidget)){
					echo $theme_lbpfwidget;
				}else{
			?>
                <ul class="latest-news">
                    <li><img src="<?php echo $host_addr;?>images/mysalvusimages/pic01.jpg" width="45" height="45" 
                    	class="pull-left img-circle img-responsive right-space-for-image" alt="">
                    	<a href="#">No posts yes </li>
                    
                </ul>
            	<?php
            	}	
            	?>
            </div>
            

            <div class="col-md-3 col-sm-6 bottom-box4 links">
            	<h4 class="module-title2">Privacy and Usage</h4>
                
                <ul class="link-list-1">
                    <li><a href="#">
                    	<i class="fa fa-angle-double-right"></i>Terms of Use</a></li>
                    <li><a href="#">
                    	<i class="fa fa-angle-double-right"></i>Privacy</a></li>
                </ul>
                
            </div>
            <div class="col-md-3 col-sm-6 bottom-box2 pull-left">
            	<h4 class="module-title2">Contact & Support</h4>
                <p>
                  <i class="fa fa-map-marker"></i>
                  34 Curtis Adeniyi Jones Close off, Adeniran Ogunsanya St, 
                  101211, Lagos
                </p>
                
                <p>
                  <i class="fa fa-phone"></i> (+234) 704-683-5377
                </p>

                <p>
                	For urgent help, contact the Lagos state Domestic Sexual Violence 
                	Response Team 
                  <a href="http://dsvrtlagos.org" target="_blank">'DSVRT'</a>
                </p>
            </div>
	
<?php
	}
?>