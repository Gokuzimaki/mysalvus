<?php
	// this file carries the default embed code for the disqus output in a single
	// page
	// for this to truly function, the variable $curblogdata(array) is used
	// and must have the following index
	// 'id' 'pagelink'(absolute url to page), 'status'(determines if page is active)
	// 
	// first setup the default disqus username data contained in the defaultsmodule.php
	// file
	$disqus_name=isset($defaultdisqusname)&&$defaultdisqusname!==""?
	$defaultdisqusname.".":"mysalvus.";
	// this variable specifies the nature of the page content
	// it could be either blog/portfolio/page/store
	// depending on the nature of the page being 
	$disqus_output_type=isset($disqus_output_type)&&$disqus_output_type!==""?
	$disqus_output_type:"blog";
	

	if($disqus_name!==""){
		if(isset($curblogdata)&&$curblogdata['status']!=="inactive"){
	
?>
<script>
/**
*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION
*  BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: 
*  https://disqus.com/admin/universalcode/#configuration-variables
*/

var disqus_config = function () {
	// Replace PAGE_URL with your page's canonical URL variable
	this.page.url = '<?php echo $curblogdata['pagelink']."";?>';  
	// Replace PAGE_IDENTIFIER with your page's unique identifier variable
	this.page.identifier = '<?php echo $curblogdata['id']."_$disqus_output_type";?>'; 
};
if(typeof DISQUS!=="object"){
	(function() { // DON'T EDIT BELOW THIS LINE
	var d = document, s = d.createElement('script');
	s.src = 'https://<?php echo $disqus_name;?>disqus.com/embed.js';
	s.setAttribute('data-timestamp', +new Date());
	(d.head || d.body).appendChild(s);
	})();

}else{
	DISQUS.reset({
	  reload: true,
	  config: disqus_config
	});
}
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

<?php
		
	}else{
?>
		<script>
			console.log("<b>Disqus Error:</b>",
				" Parse Error, No Data provided from php script");
		</script>
<?php
		
	}
?>

<?php

	}else{
?>
	<script>
		console.log("<b>Disqus Error:</b>"," Parse Error, No Disqus Default Name detected for Disqus Functionality");
	</script>
<?php
	}
?>