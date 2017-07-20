<?php
	function theme_Widget_Admin($b_options=array(),$opts=array()){
		global $host_tpathplain;
		include($host_tpathplain.'globalsmodule.php');
		$output="";
		$blogtypeid=isset($opts['blogtypeid'])&&$opts['blogtypeid']!==""?
		$opts['blogtypeid']:0;
		// check to see if blogtype id is available first, if it is search for a renderpath
		// value through it, otherwise use the renderpath value available via the 
		// $opts['renderpath'] index if set
		// when the render path is obtained, traverse it to locate the widget function
		// in the dir $host/renderpath/modulets/widgets_func.php
		$renderpath="";
		if(isset($blogtypeid)&&$blogtypeid>0){
			$typedata=getSingleBlogType($blogtypeid,"blockdeeprun");
			$renderpath=$host_tpath.$typedata['renderpath'];
		}else if(isset($opts['renderpath'])&&$opts['renderpath']!==""){
			$renderpath=$opts['renderpath'];
		}
		if($renderpath!==""){
			include_once($renderpath."/modules/widgets_func.php");
			// obtain the spare data sets if available

			// execute the themes widget function with the provided option variables
			$output=theme_Widgets($opts,$b_options);
		}

		return $output;
	}
?>