<?php
	// this file handles siderbar and footer widgets it carries all the widgets 
	// associated to a theme
	
	###BEGIN WIDGET SET CREATION
	

	/**
	*	@author olagoke okebukola
	*	
	*	
	*	
	*/

	// widget: Blog search bS 
	// description: allows users to launch a search on the blog page
	 
	$theme_bswidget='
		<div class="row custom-search">
			<h5 class="underlined"><strong>Search</strong> Blog</h5>
			<form class="sidebar-searchbox" method="get" action="'.$host_addr.'blog.php">
				<input type="search" name="q" placeholder="Type and hit Enter!">
				<button type="submit"><span class="glyphicon glyphicon-search"></span></button>
			</form>
		</div>
    ';

	// widget: faq search fs 
	// description: allows users to launch a search on the blog page
	 
	$theme_fswidget='
		<div class="row custom-search">
			<h5 class="underlined"><strong>Search</strong> FAQs</h5>
			<form class="sidebar-searchbox" method="get" action="'.$host_addr.'faq.php">
				<input type="search" name="q" placeholder="Type and hit Enter!">
				<button type="submit"><span class="glyphicon glyphicon-search"></span></button>
			</form>
		</div>
    ';    

	// widget: Blog subscription (bsub) 
	// description: allows users to a blog type
	$theme_bsubwidget='
		<div class="widget widget_subscribe">
			<h5 class="underlined"><strong>Subscribe</strong> to this Blog</h5>
			<form class="sidebar-subscriptionbox" onSubmit="return false;" 
			name="subscriptionform" method="post" 
			action="'.$host_addr.'snippets/basicsignup.php">
				<input type="hidden" name="returl"  
				value="'.$host_cur_page.'"/>
				<input type="hidden" name="entryvariant" 
				value="blogtypesubscription">
				<input type="hidden" name="catid" 
				value="'.$blogtypeid.'">
				<input type="email" name="email" placeholder="Your Email Here."
				data-evalidate="true" 
				onfocus="$(\'form[name=subscriptionform] input[name=returl]\').val(location.href)">
				<input type="hidden" name="formdata" value="subscriptionform"/>
                <input type="hidden" name="extraformdata" value="email-:-input"/>
                <input type="hidden" name="errormap" 
                value="email-:-Please provide a valid email address"/>

                <div class="col-md-12 clearboth">
	                <div class="box-footer">
	                    <input type="button" class="btn btn-success" 
	                    name="createentry" 
	                    data-formdata="" onclick="
	                    submitCustom(\'subscriptionform\',\'complete\')" value="Subscribe"/>
	                </div>
	            </div>
			</form>
		</div>
	';
	// widget: single advert space mini (sasm)
	// this widgets backend is not yet available on the platform
	$theme_sasmwidget='
	<div class="widget">
		<h5 class="underlined"><strong>Advert</strong> Space</h5>							
		<img src="'.$host_addr.'images/dbp_1.jpg" class="adspacefull">
	</div>';

	// widget: random faqs (rfaqs)
	// this widgets backend is not yet available on the platform
	$faqout='<p>No FAQs Yet</p>';
	$fstamp=substr(md5(date("Y-m-d H:i:s")), 0,8) ;
	if(isset($theme_rfwidget)){
		if($theme_rfwidget['numrows']>0){
			$faqout='<div class="panel-group" id="accordion'.$fstamp.'faq">';
			for($oi=0;$oi<$theme_rfwidget['numrows'];$oi++){
				$cfaq=$theme_rfwidget['resultdata'][$oi];
				$curcontent=$cfaq['content']!==""?
				substr(html2txt($cfaq['content']),0,157)."...":
				"";
				$faqout.='
					<div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" 
                                data-parent="#accordion'.$fstamp.'faq" 
                                href="#collapseOne">
                                <span class="glyphicon glyphicon-folder-close">
                                </span>'.$cfaq['title'].'</a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse ">
                            <div class="panel-body-accordion">
                                '.$curcontent.'
                                <a href="'.$host_addr.'faqs?qid='.$cfaq['id'].'">
                                Learn More</a>
                            </div>
                        </div>
                    </div>
				';
			}
			$faqout.="</div>";
		}
	}
	$theme_rafqswidget='
	<div class="widget">
		'.$faqout.'
	</div>
		
	';

	// widget: single advert space large (sasl)
	// this widgets backend is not yet available on the platform
	$theme_saslwidget='
	<div class="widget">
		<h5 class="underlined"><strong>Advert</strong> Space</h5>							
		<img src="'.$host_addr.'images/mysalvusimages/mysalvuswidgets.jpg" 
		class="adspacefull">
	</div>';

	// widget: quad advert space (qas);
	// this widgets backend is not yet available on the platform
	$theme_qaswidget='
		<div class="widget">
			<h5 class="underlined"><strong>Advert</strong> Space</h5>
			<div class="clear-both"></div>
			<div class="col-sm-6 quadad">
				<img src="'.$host_addr.'images/dbp_1_2.jpg" class="adspacefull">
			</div>
			<div class="col-sm-6 quadad">
				<img src="'.$host_addr.'images/dbp_1_2.jpg" class="adspacefull">
			</div>
			<div class="col-sm-6 quadad">
				<img src="'.$host_addr.'images/dbp_1_2.jpg" class="adspacefull">
			</div>
			<div class="col-sm-6 quadad">
				<img src="'.$host_addr.'images/dbp_1_2.jpg" class="adspacefull">
			</div>
			<div class="clear-both"></div>
		</div>
	';

	// widget: popular blog posts  (pbp)
	// this widgets backend is not yet available on the platform
	$theme_pbpwidget='
		<div class="widget">
			<h5 class="underlined">Most Viewed <strong>Posts</strong></h5>
			<ul class="latest-posts">
				'.$theme_bppwidget.'
			</ul>							
		</div>
	';

	// widget: popular blog posts footer (pbpf)
	// for showing popular posts in the footer area
	$theme_pbpfwidget='
		<h4 class="underlined">Popular <strong>Posts</strong></h4>
		<ul class="latest-posts">
			'.$theme_bppwidget.'
		</ul>							
	';

	// widget: latest blog posts footer (lbpf)
	// for showing recent posts in the footer area
	$theme_lbpfwidget='
		<ul class="latest-news">
			'.$theme_blpwidget.'
		</ul>							
	';

	// widget: featured blog posts  (fbp)
	// this widgets backend is not yet available on the platform
	$theme_fbpwidget='
		<div class="widget">
			<h5 class="underlined">Popular <strong>Posts</strong></h5>
			<ul class="latest-posts">
				'.$theme_bfpwidget.'
			</ul>							
		</div>
	';
	// widget: most commented blog posts  (mcbp)
	// this widgets backend is not yet available on the platform
	$theme_mcbpwidget='
		<div class="widget">
			<h5 class="underlined">Most <strong>Commented</strong> Post</h5>
			<ul class="latest-posts">
				'.$theme_bmcpwidget.'
			</ul>							
		</div>
	';
	
	// widget: most commented blog posts  (mcbp)
	// this widgets backend is not yet available on the platform
	$theme_mcbpwidget='
		<div class="widget">
			<h5 class="underlined">Most <strong>Commented</strong> Post</h5>
			<ul class="latest-posts">
				'.$theme_bmcpwidget.'
			</ul>							
		</div>
	';
	// widget: most commented blog posts footer  (mcbp)
	// this widgets backend is not yet available on the platform
	$theme_mcbpfwidget='
		<div class="col-md-4 footer-widget">
			<h4 class="underlined">Most <strong>Commented</strong> Post</h4>
			<ul class="latest-posts">
				'.$theme_bmcpwidget.'
			</ul>							
		</div>
	';

	
?>

