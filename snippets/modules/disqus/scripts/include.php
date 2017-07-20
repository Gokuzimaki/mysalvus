<?php
	// this snippet controls what scripts if any to enque to the rendering page via
	// the $mpage variables, its commonly used in pages that have multiple content
	// displaying and requiring certain update on each content as regards the 
	// current scripts function


	// first we check for the availability of the $mpagelibscriptextras variable
	$mpagelibscriptextras=isset($mpagelibscriptextras)?$mpagelibscriptextras:"";

	// next we setup the default disqus username data
	$disqus_name=isset($defaultdisqus)&&$defaultdisqus!==""?
	$defaultdisqus.".":"mysalvus.";
	$mpagelibscriptextras.='<script id="dsq-count-scr" src="//'.$disqus_name.'disqus.com/count.js" async></script>
	<script>
		$(document).ready(function(){
			if(typeof DISQUSWIDGETS=="object"){
				DISQUSWIDGETS.getCount({reset: true});
			}
		});
	</script>
	';
	$commentscripts='
	<script id="dsq-count-scr" src="//'.$disqus_name.'disqus.com/count.js" async></script>
	<script>
		$(document).ready(function(){
			if(typeof DISQUSWIDGETS=="object"){
				DISQUSWIDGETS.getCount({reset: true});
			}
		});
	</script>
	';
	
?>