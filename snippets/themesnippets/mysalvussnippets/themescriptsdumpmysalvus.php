<!-- Scripts -->
<?php 
	// minification library inclusion
	/*include_once ($host_tpathplain . 'minifier/minify/src/Minify.php');
	include_once ($host_tpathplain . 'minifier/minify/src/CSS.php');
	include_once ($host_tpathplain . 'minifier/minify/src/JS.php');
	include_once ($host_tpathplain . 'minifier/minify/src/Exception.php');
	include_once ($host_tpathplain . 'minifier/minify/src/Exceptions/BasicException.php');
	include_once ($host_tpathplain . 'minifier/minify/src/Exceptions/FileImportException.php');
	include_once ($host_tpathplain . 'minifier/minify/src/Exceptions/IOException.php');
	include_once ($host_tpathplain . 'minifier/path-converter/src/ConverterInterface.php');
	include_once ($host_tpathplain . 'minifier/path-converter/src/Converter.php');

	use MatthiasMullie\Minify;*/

	$scriptspatharrout=array();
	
	$scriptarray=array();
	if(isset($mpagestyleload)&&$mpagestyleload=="bottom"){
		echo $mpagelibstyleextras;
		include($host_tpathplain .'themesnippets/mysalvussnippets/themestylesdumpmysalvus.php');
	}
	// load the styles for the page at the bottom if the mpageload
	if(isset($mpagemergedscripts)&&$mpagemergedscripts!==""){
		// check to see if run homonculus session is active
		// unset($_SESSION['runhomonculusjs']);
		if(!isset($_SESSION['runhomonculusjs'])){
			$mpagescriptarray[]='scripts/lib/jquery.js';
			$mpagescriptarray[]='bootstrap/js/bootstrap.min.js';
			$mpagescriptarray[]='scripts/lightbox.js';
			$mpagescriptarray[]='scripts/jquery.lazyload.min.js';
			$mpagescriptarray[]='plugins/wow/js/wow.js';
			// $mpagescriptarray[]='scripts/themescripts/mysalvusscripts/hoverIntent.js';
			// $mpagescriptarray[]='plugins/rs-plugin/js/jquery.themepunch.tools.min.js';
			// $mpagescriptarray[]='plugins/rs-plugin/js/jquery.themepunch.revolution.min.js';
			// $mpagescriptarray[]='scripts/themescripts/mysalvusscripts/superfish.js';
			// $mpagescriptarray[]='scripts/themescripts/mysalvusscripts/plugins-scroll.js';
			// $mpagescriptarray[]='scripts/themescripts/mysalvusscripts/owl.carousel.min.js';
			// $mpagescriptarray[]='scripts/themescripts/mysalvusscripts/jquery.bxslider.min.js';
			// $mpagescriptarray[]='scripts/themescripts/mysalvusscripts/imagesloaded.pkgd.min.js';
			$mpagescriptarray[]='scripts/formchecker.js';
			$mpagescriptarray[]='scripts/mylib.js';
			$mpagescriptarray[]='scripts/themescripts/mysalvusscripts/custom.js';
			$mpagescriptarray[]='scripts/themescripts/mysalvusscripts/for_megamenu_run_prettify.js';
			$mpagescriptarray[]='scripts/themescripts/mysalvusscripts/parallax-scrolling-script.js';
			// $mpagescriptarray[]='scripts/themescripts/mysalvusscripts/retina.js';


			$totalcontent="";
			// echo dirname(__FILE__); /*__FILE__*/
			// 
			if($host_servertype=="windows"){
				$tpath=dirname(__FILE__)."\..\\";
				
			}else{
				$tpath=dirname(__FILE__)."/../";
			}
			
	  

			for ($i=0; $i < count($mpagescriptarray); $i++) { 
				# code...
				if($host_servertype=="windows"){
					$cpath=$host_tpath.str_replace("/", "\\", $mpagescriptarray[$i]);
				}else{
					$cpath=$host_tpath.$mpagescriptarray[$i];
				}
					// echo "$cpath <br>";
				if(file_exists($cpath)){
					// echo "$cpath <br>";
					$totalcontent.=file_get_contents($cpath);
				}
			}
			if($totalcontent!==""){
				// $minifier = new Minify\JS();

				// $minifier->add($totalcontent);

				// $totalcontent=$minifier->minify();


				if($host_servertype=="windows"){
					$fpath=$host_tpath.'scripts\themescripts\mysalvusscripts\homonculusfullmergemysalvus.js';
				}else{
					$fpath=$host_tpath.'scripts/themescripts/mysalvusscripts/homonculusfullmergemysalvus.js';

				}
				// file_put_contents($fpath, $totalcontent); 
				$handle2=fopen("$fpath","w")or die('cant open the file');
				fwrite($handle2,$totalcontent)or die('could not write file');
				if(!isset($_SESSION['runhomonculusjs'])){
					// homonculus is currently active
					$_SESSION['runhomonculusjs']="ran";
				}
			}
		}
?>
	
	<script src="<?php echo $host_addr;?>scripts/themescripts/mysalvusscripts/homonculusfullmergemysalvus.js"></script>
	<?php 	
		echo $mpagelibscriptextras;
		echo $mpagemaps;
		echo $mpagescriptextras;
	?>
<?php
	include(''.$host_tpath.'snippets/facebooksdksingle.php');
}else{
	// section for holding  extrascript libraries
	
?>
	<?php 	
	if(isset($mpage_lsb)&&$mpage_lsb=="true"){
		// load scripts bottom 
	?>
		<script src="<?php echo $host_addr;?>scripts/lib/jquery.js"></script>
		<script src="<?php echo $host_addr;?>bootstrap/js/bootstrap.js"></script>
		<script src="<?php echo $host_addr;?>scripts/lightbox.js"></script>
		<script src="<?php echo $host_addr;?>scripts/formchecker.js"></script>
		<script src="<?php echo $host_addr;?>plugins/wow/js/wow.js"></script>
		<script src="<?php echo $host_addr;?>scripts/lib/jquery.lazyload.min.js"></script>
		<script src="<?php echo $host_addr;?>scripts/mylib.js"></script>
		<?php 
			if(isset($mpagetitle)){
				echo $mpagelibscriptextras;
			}
		?>
	<?php 	
	}
		
	?>


    <script type="text/javascript" 
    src="<?php echo $host_addr;?>scripts/themescripts/mysalvusscripts/custom.js"></script>
    <!-- for Mega Menu -->
    <script src="<?php echo $host_addr;?>scripts/themescripts/mysalvusscripts/for_megamenu_run_prettify.js"></script>
    <script src="<?php echo $host_addr;?>scripts/themescripts/mysalvusscripts/parallax-scrolling-script.js"></script>
    
    <!-- for Retina Graphics -->
    <!-- // <script type="text/javascript" src="<?php echo $host_addr;?>scripts/themescripts/mysalvusscripts/retina.js"></script>  -->
	<?php
		if(isset($mpagetitle)){
			echo $mpagescriptextras;
			echo $mpagemaps;
		}
}
?>