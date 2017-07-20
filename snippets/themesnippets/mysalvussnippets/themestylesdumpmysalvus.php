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
	$stylesarray=array();
	// echo '<meta name="break">';
	if(isset($mpagemergedstyles)&&$mpagemergedstyles!==""){
		// check to see if run homonculus session is active
		// unset($_SESSION['runhomonculuscss']);
		if(!isset($_SESSION['runhomonculuscss'])){
			$stylesarray[]='bootstrap/css/bootstrap.css';
			$stylesarray[]='stylesheets/lightbox.css';
			$stylesarray[]='stylesheets/lightbox.css';
			// $stylesarray[]='stylesheets/font-awesome/css/font-awesome.min.css';
			$stylesarray[]='stylesheets/themestyles/mysalvuscss/navbar.css';
			$stylesarray[]='stylesheets/themestyles/mysalvuscss/color/green.css';
			$stylesarray[]='stylesheets/themestyles/mysalvuscss/styles.css';
			$stylesarray[]='stylesheets/themestyles/mysalvuscss/header-fullwidth.css';
			$stylesarray[]='stylesheets/themestyles/mysalvuscss/tools.css';
			$stylesarray[]='stylesheets/themestyles/mysalvuscss/footer-dark.css';
			$stylesarray[]='stylesheets/themestyles/mysalvuscss/socialmediaicons.css';
			$stylesarray[]='plugins/wow/css/animate.css';
			// $stylesarray[]='stylesheets/themestyles/mysalvuscss/megamenu.css';
			$stylesarray[]='stylesheets/themestyles/mysalvuscss/responsive.css';
			$stylesarray[]='stylesheets/my-color.css';

			$totalcontent="";
			// echo dirname(__FILE__); /*__FILE__*/
			// 
			/*if($host_servertype=="windows"){
				$tpath=dirname(__FILE__)."\..\\";
				
			}else{
				$tpath=dirname(__FILE__)."/../";
			}
			*/
	  

			for ($i=0; $i < count($stylesarray); $i++) { 
				# code...
				if($host_servertype=="windows"){
					$cpath=$host_tpath.str_replace("/", "\\", $stylesarray[$i]);
				}else{
					$cpath=$host_tpath.$stylesarray[$i];
				}
					// echo "$cpath <br>";
				if(file_exists($cpath)){
					// echo "$cpath <br>";
					$totalcontent.=file_get_contents($cpath);
				}
			}
			if($totalcontent!==""){
				// minify
				// $sourcePath = $fpath;
				// $minifier = new Minify\CSS();
				// $minifier = new CSS();
				// $minifier = new CSS($totalcontent);
				// we can even add another file, they'll then be
				// joined in 1 output file
				// $sourcePath2 = '/path/to/second/source/css/file.css';
				// $minifier->add($sourcePath2);

				// or we can just add plain CSS
				// $css = 'body { color: #000000; }';
				// $minifier->add($totalcontent);

				// save minified file to disk
				// $ftpath=str_replace("/tempdir", "", $fpath);
				// $ftpath=str_replace("\\tempdir", "", $ftpath);
				// $minifiedPath = $ftpath;
				// $totalcontent=$minifier->minify();

				// or just output the content
				// echo $minifier->minify();
				if($host_servertype=="windows"){
					// $fpath=$host_tpath.'stylesheets\themestyles\mysalvuscss\tempdir\homonculusfullmergedbr.css';
					$fpath=$host_tpath.'stylesheets\themestyles\mysalvuscss\homonculusfullmergedbr.css';
				}else{
					// $fpath=$host_tpath.'stylesheets/themestyles/mysalvuscss/tempdir/homonculusfullmergedbr.css';
					$fpath=$host_tpath.'stylesheets/themestyles/mysalvuscss/homonculusfullmergedbr.css';
				}
				// file_put_contents($fpath, $totalcontent); 
				$handle2=fopen("$fpath","w")or die('cant open the file');
				fwrite($handle2,$totalcontent)or die('could not write file');
				if(!isset($_SESSION['runhomonculuscss'])){
					// homonculus is currently active
					$_SESSION['runhomonculuscss']="ran";
				}
			}
		
		}

?>

<link async="true" rel="stylesheet" href="<?php echo $host_addr?>icons/font-awesome/css/font-awesome.min.css">
<link async="true" href="<?php echo $host_addr;?>stylesheets/themestyles/mysalvuscss/homonculusfullmergedbr.css" rel="stylesheet"/>
<?php
}else{
?>
<?php 
	// sectionfor holding 
	if(isset($mpagetitle)){
		echo $mpagestylesextras;
	}
?>
	<link rel="stylesheet" href="<?php echo $host_addr?>plugins/wow/css/animate.css">
	<link async="true" rel="stylesheet" href="<?php echo $host_addr?>icons/font-awesome/css/font-awesome.min.css">
	<link async="true" rel="stylesheet" href="<?php echo $host_addr?>bootstrap/css/bootstrap.css">
    
    <!-- Custom styles for this template -->
    <link rel="stylesheet" type="text/css" 
    href="<?php echo $host_addr;?>stylesheets/themestyles/mysalvuscss/navbar.css">
    <link rel="stylesheet" type="text/css" 
    href="<?php echo $host_addr;?>stylesheets/themestyles/mysalvuscss/color/green.css">
    <link rel="stylesheet" type="text/css" 
    href="<?php echo $host_addr;?>stylesheets/themestyles/mysalvuscss/styles.css">
    <link rel="stylesheet" type="text/css" 
    href="<?php echo $host_addr;?>stylesheets/themestyles/mysalvuscss/header-fullwidth.css">
    <link rel="stylesheet" type="text/css" 
    href="<?php echo $host_addr;?>stylesheets/themestyles/mysalvuscss/tools.css">
    <link rel="stylesheet" type="text/css" 
    href="<?php echo $host_addr;?>stylesheets/themestyles/mysalvuscss/footer-dark.css">
    <link rel="stylesheet" type="text/css" 
    href="<?php echo $host_addr;?>stylesheets/themestyles/mysalvuscss/socialmediaicons.css">
    <link rel="stylesheet" type="text/css" 
    href="<?php echo $host_addr;?>plugins/wow/css/animate.css">
   <!-- MegaMenu styles-->
    <!-- <link href="<?php echo $host_addr;?>stylesheets/themestyles/mysalvuscss/megamenu.css" rel="stylesheet"> -->
    <link rel="stylesheet" type="text/css" 
    href="<?php echo $host_addr;?>stylesheets/themestyles/mysalvuscss/responsive.css">
    <link rel="stylesheet" type="text/css" 
    href="<?php echo $host_addr;?>stylesheets/my-color.css">
	<!-- For Sticky Header -->
    
  	
<?php 
}
?>